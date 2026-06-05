<?php

/**
 * Gmail Inbound Reply Poller — Cron Runner
 *
 * Polls Gmail for new customer replies to inquiry emails and records them
 * into `inquiry_email_replies` (direction = 'inbound') via GmailPollerService.
 *
 * This file is the RUNNER. The polling logic lives in:
 *   build/services/GmailPollerService.php
 *
 * How to run
 * ----------
 *   CLI / cron (recommended):
 *     php /path/to/build/cron/poll-gmail-replies.php
 *
 *   Hostinger cron example (every 5 minutes use "5-minute" syntax):
 *     [slash]5 * * * * /usr/bin/php /home/USER/domains/DOMAIN/public_html/build/cron/poll-gmail-replies.php >> /home/USER/gmail-poll.log 2>&1
 *     (replace [slash]5 with an actual asterisk-slash-5 expression in your crontab)
 *
 *   Browser / on-demand (must supply the secret token):
 *     https://your-site/build/cron/poll-gmail-replies.php?token=YOUR_CRON_SECRET
 *
 * Security
 * --------
 *   When invoked over HTTP we require a matching CRON_POLL_SECRET so the
 *   endpoint cannot be triggered anonymously. Define it in config/credentials.php:
 *     putenv('CRON_POLL_SECRET=some-long-random-string');
 *   CLI invocations are always trusted.
 *
 * Requirements
 * ------------
 *   - config/credentials.php   (sets GMAIL_POLLING_REFRESH_TOKEN, DB creds, etc.)
 *   - config/gmail-credentials.json  (OAuth client_id / client_secret)
 */

declare(strict_types=1);

$isCli = (PHP_SAPI === 'cli');

// -----------------------------------------------------------------------------
// HTTP guard — only allow browser access with the correct secret token.
// -----------------------------------------------------------------------------
if (!$isCli) {
    header('Content-Type: application/json');

    // credentials.php is loaded below via db.php; load it early just for the secret.
    $credEarly = __DIR__ . '/../../config/credentials.php';
    if (is_file($credEarly)) {
        require_once $credEarly;
    }

    $expected = getenv('CRON_POLL_SECRET') ?: '';
    $provided = $_GET['token'] ?? '';

    if ($expected === '' || !hash_equals($expected, (string) $provided)) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'Forbidden. A valid ?token is required to run the poller over HTTP.',
        ]);
        exit;
    }
}

// -----------------------------------------------------------------------------
// Bootstrap: database connection (this also loads config/credentials.php which
// sets GMAIL_POLLING_REFRESH_TOKEN, GMAIL_USER, etc. via putenv()).
// -----------------------------------------------------------------------------
require_once __DIR__ . '/../../config/db.php';                 // provides $pdo
require_once __DIR__ . '/../services/GmailPollerService.php';  // App\Services\GmailPollerService

use App\Services\GmailPollerService;

/**
 * Emit a result line in a format appropriate to the execution context.
 */
function poll_output(array $result, bool $isCli): void
{
    if ($isCli) {
        $ts = date('Y-m-d H:i:s');
        echo "[{$ts}] Gmail poll — status: {$result['status']}, "
            . "processed: {$result['processed']}, errors: {$result['errors']}\n";

        foreach ($result['messages'] ?? [] as $m) {
            echo "  - {$m}\n";
        }
        foreach ($result['error_log'] ?? [] as $e) {
            echo "  ! {$e}\n";
        }
        return;
    }

    echo json_encode([
        'success'   => $result['errors'] === 0,
        'status'    => $result['status'],
        'processed' => $result['processed'],
        'errors'    => $result['errors'],
        'messages'  => $result['messages'] ?? [],
        'error_log' => $result['error_log'] ?? [],
        'polled_at' => date('Y-m-d H:i:s'),
    ]);
}

try {
    $poller = new GmailPollerService($pdo);
    $result = $poller->poll();
    poll_output($result, $isCli);
} catch (\Throwable $e) {
    $detail = sprintf(
        '%s: %s (in %s:%d)',
        get_class($e),
        $e->getMessage(),
        basename($e->getFile()),
        $e->getLine()
    );
    error_log('[GmailPoller][cron] Fatal: ' . $detail);

    if ($isCli) {
        fwrite(STDERR, '[' . date('Y-m-d H:i:s') . "] Gmail poll FAILED — {$detail}\n");
        exit(1);
    }

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'status'  => 'fatal',
        'message' => 'Gmail poll failed.',
        'error'   => $detail,
    ]);
    exit;
}
