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
 */
class GmailPollerService
{
    private $pdo;
    private Client $googleClient;
    private Gmail $gmail;
    private string $userId = 'me';

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->googleClient = $this->buildGoogleClient();
        $this->gmail = new Gmail($this->googleClient);
    }

    private function buildGoogleClient(): Client
    {
        $credentialsFile = __DIR__ . '/../../config/gmail-credentials.json';
        $credentials = json_decode(file_get_contents($credentialsFile), true);

        $client = new Client();
        $client->setApplicationName('M&M Paraiso Resort');
        $client->setClientId($credentials['web']['client_id']);
        $client->setClientSecret($credentials['web']['client_secret']);
        $client->setScopes([Gmail::GMAIL_READONLY]);
        $client->setAccessType('offline');

        // Load the stored refresh token
        $refreshToken = getenv('GMAIL_POLLING_REFRESH_TOKEN') ?: '';
        if (empty($refreshToken)) {
            throw new \RuntimeException('GMAIL_POLLING_REFRESH_TOKEN is not set in credentials.php');
        }

        $client->fetchAccessTokenWithRefreshToken($refreshToken);

        return $client;
    }

    /**
     * Main poll method — call this from the cron script
     */
    public function poll(): array
    {
        $state = $this->loadState();
        $processed = 0;
        $errors = 0;

        try {
            if (empty($state['history_id'])) {
                // First run — get the current historyId as baseline, process nothing yet
                $profile = $this->gmail->users->getProfile($this->userId);
                $this->saveState($profile->getHistoryId());
                error_log('[GmailPoller] First run — baseline historyId saved: ' . $profile->getHistoryId());
                return ['processed' => 0, 'errors' => 0, 'status' => 'baseline_set'];
            }

            // Fetch history since last poll
            $messages = $this->fetchNewMessages($state['history_id']);

            foreach ($messages as $msgId) {
                try {
                    $result = $this->processMessage($msgId);
                    if ($result) $processed++;
                } catch (\Exception $e) {
                    error_log('[GmailPoller] Error processing message ' . $msgId . ': ' . $e->getMessage());
                    $errors++;
                }
            }

            // Save new history baseline after processing
            $profile = $this->gmail->users->getProfile($this->userId);
            $this->saveState($profile->getHistoryId());

        } catch (\Exception $e) {
            error_log('[GmailPoller] Poll failed: ' . $e->getMessage());
            $errors++;
        }

        return ['processed' => $processed, 'errors' => $errors, 'status' => 'ok'];
    }

    /**
     * Fetch message IDs of new emails received since the given historyId
     */
    private function fetchNewMessages(string $historyId): array
    {
        $messageIds = [];

        try {
            $params = [
                'startHistoryId' => $historyId,
                'historyTypes'   => ['messageAdded'],
                'labelId'        => 'INBOX',
            ];

            $response = $this->gmail->users_history->listUsersHistory($this->userId, $params);
            $histories = $response->getHistory() ?? [];

            foreach ($histories as $history) {
                $added = $history->getMessagesAdded() ?? [];
                foreach ($added as $item) {
                    $messageIds[] = $item->getMessage()->getId();
                }
            }
        } catch (\Google\Service\Exception $e) {
            // historyId expired (410 Gone) — reset baseline
            if ($e->getCode() === 410) {
                error_log('[GmailPoller] historyId expired, resetting baseline');
                $profile = $this->gmail->users->getProfile($this->userId);
                $this->saveState($profile->getHistoryId());
            } else {
                throw $e;
            }
        }

        return array_unique($messageIds);
    }

    /**
     * Process a single Gmail message — check if it's a reply to an inquiry
     */
    private function processMessage(string $msgId): bool
    {
        $message = $this->gmail->users_messages->get($this->userId, $msgId, ['format' => 'metadata',
            'metadataHeaders' => ['From', 'Subject', 'In-Reply-To', 'References', 'Message-ID', 'Date']
        ]);

        $headers = [];
        foreach ($message->getPayload()->getHeaders() as $h) {
            $headers[strtolower($h->getName())] = $h->getValue();
        }

        $inReplyTo  = trim($headers['in-reply-to'] ?? '', '<>');
        $references = $headers['references'] ?? '';
        $from       = $headers['from'] ?? '';
        $subject    = $headers['subject'] ?? '';

        // Try to extract inquiry_id from In-Reply-To or References
        $inquiryId = $this->extractInquiryId($inReplyTo);
        if (!$inquiryId && $references) {
            $inquiryId = $this->extractInquiryId($references);
        }

        if (!$inquiryId) {
            return false; // Not related to any inquiry
        }

        // Verify inquiry exists
        $stmt = $this->pdo->prepare("SELECT inquiry_id, status FROM contact_inquiries WHERE inquiry_id = ?");
        $stmt->execute([$inquiryId]);
        $inquiry = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$inquiry) {
            error_log("[GmailPoller] inquiry_id {$inquiryId} not found (message {$msgId})");
            return false;
        }

        // Avoid duplicate recording — check if this Gmail msgId was already saved
        $dupCheck = $this->pdo->prepare("SELECT email_id FROM inquiry_email_replies WHERE message_id = ?");
        $dupCheck->execute([$msgId]);
        if ($dupCheck->fetch()) {
            return false; // Already processed
        }

        // Fetch full message body
        $fullMessage = $this->gmail->users_messages->get($this->userId, $msgId, ['format' => 'full']);
        $body = $this->extractBody($fullMessage->getPayload());
        $cleanBody = $this->stripQuotedText($body);

        // Parse sender
        $senderName  = '';
        $senderEmail = $from;
        if (preg_match('/^(.+?)\s*<(.+?)>$/', $from, $m)) {
            $senderName  = trim($m[1], '"\'');
            $senderEmail = $m[2];
        }

        // Record inbound reply
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

        // Re-open archived/replied inquiries
        if (in_array($inquiry['status'], ['archived', 'replied'])) {
            $this->pdo->prepare("UPDATE contact_inquiries SET status = 'read' WHERE inquiry_id = ?")
                ->execute([$inquiryId]);
        }

        error_log("[GmailPoller] Recorded inbound reply from {$senderEmail} for inquiry #{$inquiryId}");
        return true;
    }

    /**
     * Extract inquiry_id from a Message-ID string or space-separated References list
     */
    private function extractInquiryId(string $header): ?int
    {
        foreach (preg_split('/[\s,]+/', $header) as $id) {
            $id = trim($id, '<>');
            if (preg_match('/^inquiry-(\d+)-/', $id, $m)) {
                return (int)$m[1];
            }
        }
        return null;
    }

    /**
     * Recursively extract plain-text body from MIME payload
     */
    private function extractBody($payload): string
    {
        $mimeType = $payload->getMimeType();

        if ($mimeType === 'text/plain') {
            $data = $payload->getBody()->getData();
            return $data ? base64_decode(strtr($data, '-_', '+/')) : '';
        }

        if (str_starts_with($mimeType, 'multipart/')) {
            foreach ($payload->getParts() as $part) {
                $text = $this->extractBody($part);
                if (!empty($text)) return $text;
            }
        }

        return '';
    }

    /**
     * Remove quoted reply lines and common footer markers from email body
     */
    private function stripQuotedText(string $body): string
    {
        // Normalize line endings
        $body = str_replace("\r\n", "\n", $body);

        // Remove everything from "On [date]... wrote:" onward (handles multi-line wrapping)
        // This covers Gmail's "On Mon, May 18, 2026 at 5:09 PM ... wrote:" pattern
        $body = preg_replace('/\nOn .{10,200}wrote:\s*$/si', '', $body);
        $body = preg_replace('/\nOn .{10,200}wrote:\n/si', '', $body);

        // Remove "--- Original Message ---" and everything after
        $body = preg_replace('/\n-{3,}[\s\w]*[Mm]essage[\s\w]*-{3,}.*/si', '', $body);

        // Remove "From: ... Sent: ... To: ..." Outlook-style quote header
        $body = preg_replace('/\nFrom:\s*.+\nSent:\s*.+\nTo:\s*.+/si', '', $body);

        // Strip remaining lines that start with >
        $lines = explode("\n", $body);
        $clean = array_filter($lines, fn($l) => !str_starts_with(ltrim($l), '>'));

        return trim(implode("\n", $clean));
    }

    private function loadState(): array
    {
        $stmt = $this->pdo->query("SELECT history_id, last_polled_at FROM gmail_poll_state LIMIT 1");
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: ['history_id' => null, 'last_polled_at' => null];
    }

    private function saveState(string $historyId): void
    {
        // Check if row exists, if not insert
        $check = $this->pdo->query("SELECT COUNT(*) FROM gmail_poll_state")->fetchColumn();
        if ($check == 0) {
            $this->pdo->prepare("INSERT INTO gmail_poll_state (history_id, last_polled_at) VALUES (?, NOW())")
                ->execute([$historyId]);
        } else {
            $this->pdo->prepare("UPDATE gmail_poll_state SET history_id = ?, last_polled_at = NOW()")
                ->execute([$historyId]);
        }
    }
}
