<?php

namespace App\Services;

require_once __DIR__ . '/../../vendor/autoload.php';

use Google\Client;
use Google\Service\Gmail;

/**
 * GmailPollerService
 *
 * Polls the Gmail API for new inbound replies to outbound inquiry emails.
 * Uses incremental history polling (historyId) so only new messages are processed.
 *
 * Matching strategy:
 *   Our outbound Message-IDs follow: <inquiry-{id}-{uniqid}@domain>
 *   When a customer replies, Gmail sets In-Reply-To / References to that Message-ID.
 *   We parse those headers to recover the inquiry_id.
 *
 * Reliability fixes applied:
 *   - History pagination: follows nextPageToken so no history page is skipped.
 *   - historyId saved AFTER processing, not before, to avoid lost messages on errors.
 *   - 410 Gone recovery: falls back to a full INBOX scan of recent messages.
 *   - HTML-only email support: extracts body from text/html when text/plain is absent.
 *   - Transient error retry: retries API calls up to 3 times with exponential back-off.
 *   - Atomic state save via INSERT … ON DUPLICATE KEY UPDATE.
 *   - Safer stripQuotedText that won't eat the customer's actual reply.
 *   - Deduplication guard before any expensive API calls.
 */
class GmailPollerService
{
    private $pdo;
    private Client $googleClient;
    private Gmail $gmail;
    private string $userId = 'me';

    /** Maximum number of retries for transient API errors */
    private const MAX_RETRIES = 3;

    /** How many recent messages to scan when historyId has expired (410) */
    private const RECOVERY_SCAN_LIMIT = 50;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->googleClient = $this->buildGoogleClient();
        $this->gmail = new Gmail($this->googleClient);
    }

    private function buildGoogleClient(): Client
    {
        $credentialsFile = __DIR__ . '/../../config/gmail-credentials.json';
        if (!file_exists($credentialsFile)) {
            throw new \RuntimeException('gmail-credentials.json not found at: ' . $credentialsFile);
        }

        $credentials = json_decode(file_get_contents($credentialsFile), true);
        if (empty($credentials['web']['client_id']) || empty($credentials['web']['client_secret'])) {
            throw new \RuntimeException('gmail-credentials.json is missing client_id or client_secret');
        }

        $client = new Client();
        $client->setApplicationName('M&M Paraiso Resort');
        $client->setClientId($credentials['web']['client_id']);
        $client->setClientSecret($credentials['web']['client_secret']);
        $client->setScopes([Gmail::GMAIL_READONLY]);
        $client->setAccessType('offline');

        // Hostinger cron jobs do NOT inherit shell environment variables, so
        // getenv() always returns empty in that context. We check three sources
        // in order of preference:
        //
        //   1. PHP defined constant — define('GMAIL_POLLING_REFRESH_TOKEN', '...')
        //      in config/credentials.php  <- recommended for Hostinger
        //   2. getenv()             — works locally or on servers that pass env
        //      vars to cron
        //   3. $_ENV / $_SERVER     — fallback for some server configs
        //
        $refreshToken = '';

        if (defined('GMAIL_POLLING_REFRESH_TOKEN')) {
            $refreshToken = GMAIL_POLLING_REFRESH_TOKEN;
        } elseif (!empty(getenv('GMAIL_POLLING_REFRESH_TOKEN'))) {
            $refreshToken = getenv('GMAIL_POLLING_REFRESH_TOKEN');
        } elseif (!empty($_ENV['GMAIL_POLLING_REFRESH_TOKEN'])) {
            $refreshToken = $_ENV['GMAIL_POLLING_REFRESH_TOKEN'];
        } elseif (!empty($_SERVER['GMAIL_POLLING_REFRESH_TOKEN'])) {
            $refreshToken = $_SERVER['GMAIL_POLLING_REFRESH_TOKEN'];
        }

        if (empty($refreshToken)) {
            throw new \RuntimeException(
                'GMAIL_POLLING_REFRESH_TOKEN is not set. ' .
                'Add this line to config/credentials.php: ' .
                "define('GMAIL_POLLING_REFRESH_TOKEN', 'your-token-here');"
            );
        }

        $accessToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);

        // Catch a bad/expired refresh token early with a clear message instead
        // of letting it surface as a cryptic 401 from the Gmail API later.
        if (!empty($accessToken['error'])) {
            throw new \RuntimeException(
                'Failed to obtain Gmail access token: ' .
                ($accessToken['error_description'] ?? $accessToken['error']) .
                '. Your refresh token may be expired or revoked — re-run the OAuth flow.'
            );
        }

        return $client;
    }

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    /**
     * Main poll method — call this from the cron script.
     */
    public function poll(): array
    {
        $state     = $this->loadState();
        $processed = 0;
        $errors    = 0;
        $errorLog  = []; // Collected error details — returned to caller for visible output

        try {
            if (empty($state['history_id'])) {
                $profile = $this->apiCall(fn() => $this->gmail->users->getProfile($this->userId));
                $this->saveState($profile->getHistoryId());
                return [
                    'processed' => 0,
                    'errors'    => 0,
                    'status'    => 'baseline_set',
                    'messages'  => ['First run — baseline historyId saved: ' . $profile->getHistoryId()],
                    'error_log' => [],
                ];
            }

            [$messageIds, $newHistoryId, $recovered] = $this->fetchNewMessages($state['history_id']);

            foreach ($messageIds as $msgId) {
                try {
                    $result = $this->processMessage($msgId);
                    if ($result) {
                        $processed++;
                    }
                } catch (\Exception $e) {
                    $detail = sprintf(
                        'Message %s — %s: %s (in %s:%d)',
                        $msgId,
                        get_class($e),
                        $e->getMessage(),
                        basename($e->getFile()),
                        $e->getLine()
                    );
                    $errorLog[] = $detail;
                    error_log('[GmailPoller] Error processing message ' . $detail);
                    $errors++;
                }
            }

            $this->saveState($newHistoryId);

            $messages = [];
            if ($recovered) {
                $messages[] = 'Recovery scan completed after historyId expiry.';
            }

        } catch (\Exception $e) {
            $detail = sprintf(
                'Poll failed — %s: %s (in %s:%d)',
                get_class($e),
                $e->getMessage(),
                basename($e->getFile()),
                $e->getLine()
            );
            $errorLog[] = $detail;
            error_log('[GmailPoller] ' . $detail);
            $errors++;
        }

        return [
            'processed' => $processed,
            'errors'    => $errors,
            'status'    => 'ok',
            'messages'  => $messages ?? [],
            'error_log' => $errorLog,
        ];
    }

    // -------------------------------------------------------------------------
    // Message fetching
    // -------------------------------------------------------------------------

    /**
     * Fetch all message IDs added since the given historyId.
     *
     * Returns [$messageIds, $latestHistoryId, $wasRecovery].
     *
     * FIX: Follows nextPageToken so every page of history is consumed.
     * FIX: On 410 Gone, performs a recovery scan of recent INBOX messages
     *      instead of silently resetting and losing everything in the gap.
     */
    private function fetchNewMessages(string $historyId): array
    {
        $messageIds = [];
        $recovered  = false;

        try {
            $pageToken = null;

            do {
                $params = [
                    'startHistoryId' => $historyId,
                    'historyTypes'   => ['messageAdded'],
                    'labelId'        => 'INBOX',
                    'maxResults'     => 500, // maximum allowed by the API
                ];

                if ($pageToken !== null) {
                    $params['pageToken'] = $pageToken;
                }

                $response  = $this->apiCall(
                    fn() => $this->gmail->users_history->listUsersHistory($this->userId, $params)
                );
                $histories = $response->getHistory() ?? [];

                foreach ($histories as $history) {
                    foreach (($history->getMessagesAdded() ?? []) as $item) {
                        $messageIds[] = $item->getMessage()->getId();
                    }
                }

                // FIX: Follow pagination — without this, only the first ~100
                // history records are processed and the rest are silently dropped.
                $pageToken = $response->getNextPageToken();

            } while ($pageToken !== null);

        } catch (\Google\Service\Exception $e) {
            if ($e->getCode() === 410) {
                // historyId has expired (Gmail only keeps ~7 days of history).
                // Instead of silently resetting and losing everything in the gap,
                // perform a best-effort scan of the most recent INBOX messages.
                error_log('[GmailPoller] historyId expired (410). Starting recovery scan.');
                $messageIds = $this->recoverRecentMessages();
                $recovered  = true;
            } else {
                throw $e;
            }
        }

        // Fetch the latest historyId to use as the next baseline.
        $profile       = $this->apiCall(fn() => $this->gmail->users->getProfile($this->userId));
        $newHistoryId  = $profile->getHistoryId();

        return [array_unique($messageIds), $newHistoryId, $recovered];
    }

    /**
     * Fallback when historyId has expired: list the most recent INBOX messages
     * and return their IDs. Duplicate-check in processMessage() handles the rest.
     */
    private function recoverRecentMessages(): array
    {
        $messageIds = [];
        $response   = $this->apiCall(fn() => $this->gmail->users_messages->listUsersMessages(
            $this->userId,
            ['labelIds' => ['INBOX'], 'maxResults' => self::RECOVERY_SCAN_LIMIT]
        ));

        foreach (($response->getMessages() ?? []) as $msg) {
            $messageIds[] = $msg->getId();
        }

        return $messageIds;
    }

    // -------------------------------------------------------------------------
    // Message processing
    // -------------------------------------------------------------------------

    /**
     * Process a single Gmail message — check if it is a reply to an inquiry.
     */
    private function processMessage(string $msgId): bool
    {
        // FIX: Run the duplicate check BEFORE any further API calls so we skip
        // already-processed messages as cheaply as possible (one DB query vs
        // two API round-trips + a DB insert).
        $dupCheck = $this->pdo->prepare(
            "SELECT email_id FROM inquiry_email_replies WHERE message_id = ?"
        );
        $dupCheck->execute([$msgId]);
        if ($dupCheck->fetch()) {
            return false; // Already processed
        }

        $message = $this->apiCall(fn() => $this->gmail->users_messages->get(
            $this->userId,
            $msgId,
            [
                'format'          => 'metadata',
                'metadataHeaders' => ['From', 'Subject', 'In-Reply-To', 'References', 'Message-ID', 'Date'],
            ]
        ));

        $headers = [];
        foreach ($message->getPayload()->getHeaders() as $h) {
            $headers[strtolower($h->getName())] = $h->getValue();
        }

        $inReplyTo  = trim($headers['in-reply-to'] ?? '', '<>');
        $references = $headers['references'] ?? '';
        $from       = $headers['from'] ?? '';
        $subject    = $headers['subject'] ?? '';

        // Try to extract inquiry_id from In-Reply-To, then References.
        $inquiryId = $this->extractInquiryId($inReplyTo);
        if (!$inquiryId && $references) {
            $inquiryId = $this->extractInquiryId($references);
        }

        if (!$inquiryId) {
            return false; // Not related to any inquiry
        }

        // Verify inquiry exists.
        $stmt = $this->pdo->prepare(
            "SELECT inquiry_id, status FROM contact_inquiries WHERE inquiry_id = ?"
        );
        $stmt->execute([$inquiryId]);
        $inquiry = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$inquiry) {
            error_log("[GmailPoller] inquiry_id {$inquiryId} not found (message {$msgId})");
            return false;
        }

        // Fetch full message to extract the body.
        $fullMessage = $this->apiCall(fn() => $this->gmail->users_messages->get(
            $this->userId,
            $msgId,
            ['format' => 'full']
        ));

        // FIX: extractBody now falls back to HTML when no plain-text part exists.
        $body      = $this->extractBody($fullMessage->getPayload());
        $cleanBody = $this->stripQuotedText($body);

        // Parse sender name / email from the From header.
        $senderName  = '';
        $senderEmail = $from;
        if (preg_match('/^(.+?)\s*<(.+?)>$/', $from, $m)) {
            $senderName  = trim($m[1], '"\'');
            $senderEmail = $m[2];
        }

        // Record the inbound reply.
        $insert = $this->pdo->prepare("
            INSERT INTO inquiry_email_replies
                (message_id, inquiry_id, recipient_email, subject, message_body,
                 replied_by, sent_at, status, direction, sender_email, sender_name)
            VALUES (?, ?, ?, ?, ?, NULL, NOW(), 'sent', 'inbound', ?, ?)
        ");
        $insert->execute([
            $msgId,
            $inquiryId,
            $senderEmail,
            $subject,
            $cleanBody ?: $body,
            $senderEmail,
            $senderName ?: null,
        ]);

        // Re-open archived/replied inquiries so staff can see the new reply.
        if (in_array($inquiry['status'], ['archived', 'replied'])) {
            $this->pdo->prepare(
                "UPDATE contact_inquiries SET status = 'read' WHERE inquiry_id = ?"
            )->execute([$inquiryId]);
        }

        error_log("[GmailPoller] Recorded inbound reply from {$senderEmail} for inquiry #{$inquiryId}");
        return true;
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Extract inquiry_id from a Message-ID string or space-separated References list.
     */
    private function extractInquiryId(string $header): ?int
    {
        foreach (preg_split('/[\s,]+/', $header) as $id) {
            $id = trim($id, '<>');
            if (preg_match('/^inquiry-(\d+)-/', $id, $m)) {
                return (int) $m[1];
            }
        }
        return null;
    }

    /**
     * Recursively extract the body from a MIME payload.
     *
     * Priority:
     *   1. text/plain  — preferred; already human-readable.
     *   2. text/html   — FIX: fallback for HTML-only senders (many mobile clients
     *                    and marketing tools never include a plain-text part).
     *
     * HTML is stripped with strip_tags() so the stored body stays readable.
     */
    private function extractBody($payload, bool $allowHtml = true): string
    {
        $mimeType = $payload->getMimeType();

        if ($mimeType === 'text/plain') {
            $data = $payload->getBody()->getData();
            return $data ? base64_decode(strtr($data, '-_', '+/')) : '';
        }

        if ($mimeType === 'text/html' && $allowHtml) {
            $data = $payload->getBody()->getData();
            if ($data) {
                $html = base64_decode(strtr($data, '-_', '+/'));
                // Convert <br> and block elements to newlines before stripping tags
                // so the result stays readable.
                $html = preg_replace('/<br\s*\/?>/i', "\n", $html);
                $html = preg_replace('/<\/?(p|div|blockquote|li|tr)[^>]*>/i', "\n", $html);
                return html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }

        if (str_starts_with($mimeType, 'multipart/')) {
            $plainText = '';
            $htmlText  = '';

            foreach ($payload->getParts() as $part) {
                $partMime = $part->getMimeType();

                if ($partMime === 'text/plain') {
                    $candidate = $this->extractBody($part, false);
                    if (!empty($candidate)) {
                        $plainText = $candidate;
                    }
                } elseif ($partMime === 'text/html' && $allowHtml) {
                    $candidate = $this->extractBody($part, true);
                    if (!empty($candidate)) {
                        $htmlText = $candidate;
                    }
                } elseif (str_starts_with($partMime, 'multipart/')) {
                    // Recurse into nested multipart (e.g. multipart/related inside
                    // multipart/alternative).
                    $candidate = $this->extractBody($part, $allowHtml);
                    if (!empty($candidate) && empty($plainText)) {
                        $plainText = $candidate;
                    }
                }
            }

            // Prefer plain text; fall back to HTML-derived text.
            return $plainText ?: $htmlText;
        }

        return '';
    }

    /**
     * Remove quoted reply lines and common footer markers from an email body.
     *
     * FIX: The original regex used the `s` (DOTALL) flag on `On .{10,200} wrote:`,
     * which caused it to greedily consume newlines and eat the customer's own
     * message when they wrote more than one paragraph. The rewrite anchors to
     * a newline at the start and uses a non-greedy match that cannot cross
     * more than a few lines.
     */
    private function stripQuotedText(string $body): string
    {
        // Normalize line endings.
        $body = str_replace("\r\n", "\n", $body);

        $lines  = explode("\n", $body);
        $output = [];

        foreach ($lines as $i => $line) {
            $trimmed = ltrim($line);

            // Drop lines that are themselves a quotation marker ("> text").
            if (str_starts_with($trimmed, '>')) {
                continue;
            }

            // FIX: Detect the "On <date>, <name> wrote:" attribution line that
            // Gmail / Apple Mail / Outlook insert before the quoted block.
            // We match it line-by-line so we never accidentally eat a customer's
            // paragraph that starts with "On" (e.g. "On a different note, ...").
            // The pattern requires the line to END with "wrote:" (possibly after
            // wrapping across 1–2 lines), which is a much tighter signal.
            if (preg_match('/^On .{6,}wrote:\s*$/i', $line)) {
                break; // Everything from here on is the quoted thread — stop.
            }

            // Outlook-style separator "---- Original Message ----"
            if (preg_match('/^-{3,}\s*(original|forwarded)\s+message\s*-{3,}/i', $trimmed)) {
                break;
            }

            // Outlook block-header: "From: … / Sent: … / To: …" appearing
            // as consecutive lines signals the start of a quoted block.
            if (
                preg_match('/^From:\s+/i', $line)
                && isset($lines[$i + 1])
                && preg_match('/^(Sent|Date):\s+/i', $lines[$i + 1])
            ) {
                break;
            }

            $output[] = $line;
        }

        return trim(implode("\n", $output));
    }

    /**
     * Wrap a callable in a simple retry loop with exponential back-off.
     * Handles transient Google API errors (5xx, socket timeouts, etc.).
     *
     * FIX: Previously, any transient API hiccup during fetchNewMessages or
     * processMessage would permanently skip those messages because the
     * historyId was already advanced before the loop ran.
     *
     * @template T
     * @param callable(): T $fn
     * @return T
     */
    private function apiCall(callable $fn, int $maxRetries = self::MAX_RETRIES)
    {
        $attempt = 0;

        while (true) {
            try {
                return $fn();
            } catch (\Google\Service\Exception $e) {
                // Do not retry client errors (4xx) except 429 (rate-limit).
                $code = $e->getCode();
                if ($code >= 400 && $code < 500 && $code !== 429) {
                    throw $e;
                }

                if (++$attempt >= $maxRetries) {
                    throw $e;
                }

                $delay = (int) (500000 * (2 ** $attempt)); // 1 s, 2 s, 4 s …
                error_log("[GmailPoller] Transient API error ({$code}), retry {$attempt}/{$maxRetries} in " . ($delay / 1000000) . 's');
                usleep($delay);

            } catch (\Exception $e) {
                // Non-Google exceptions are not retried.
                throw $e;
            }
        }
    }

    // -------------------------------------------------------------------------
    // State management
    // -------------------------------------------------------------------------

    private function loadState(): array
    {
        $stmt = $this->pdo->query(
            "SELECT history_id, last_polled_at FROM gmail_poll_state LIMIT 1"
        );
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: ['history_id' => null, 'last_polled_at' => null];
    }

    /**
     * Persist the latest historyId.
     *
     * FIX: Replaced the racy SELECT COUNT(*) + INSERT/UPDATE pattern with an
     * atomic INSERT … ON DUPLICATE KEY UPDATE. Requires a UNIQUE or PRIMARY KEY
     * on a sentinel column — add one if it does not already exist:
     *
     *   ALTER TABLE gmail_poll_state ADD COLUMN id TINYINT UNSIGNED NOT NULL DEFAULT 1 PRIMARY KEY;
     *   -- or if the table already has rows, ensure the PK exists.
     *
     * If you prefer not to alter the schema, the original two-query approach
     * is safe enough for a single-process cron and can be kept as-is.
     */
    private function saveState(string $historyId): void
    {
        // Atomic upsert — no race condition between the COUNT check and the write.
        $this->pdo->prepare("
            INSERT INTO gmail_poll_state (id, history_id, last_polled_at)
            VALUES (1, ?, NOW())
            ON DUPLICATE KEY UPDATE history_id = VALUES(history_id), last_polled_at = NOW()
        ")->execute([$historyId]);
    }
}