<?php
/**
 * Gmail Reply Poller — Cron Script
 *
 * Run this every 5 minutes via cron or Windows Task Scheduler:
 *
 *   Linux/Mac cron (crontab -e):
 *     * /5 * * * * php /path/to/admin_booking/build/cron/poll-gmail-replies.php >> /var/log/gmail-poller.log 2>&1
 *
 *   Windows Task Scheduler:
 *     Program : C:\xampp\php\php.exe
 *     Arguments: C:\xampp\htdocs\admin_booking\build\cron\poll-gmail-replies.php
 *     Trigger  : Every 5 minutes
 *
 *   XAMPP local testing (manual run):
 *     php build/cron/poll-gmail-replies.php
 */

// Prevent running via browser
if (php_sapi_name() !== 'cli' && !defined('ALLOW_CRON_VIA_WEB')) {
    http_response_code(403);
    exit('CLI only');
}

define('CRON_START', microtime(true));

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../services/GmailPollerService.php';

// Load credentials (env vars)
$credentialsFile = __DIR__ . '/../../config/credentials.php';
if (file_exists($credentialsFile)) {
    require_once $credentialsFile;
}

use App\Services\GmailPollerService;

echo '[' . date('Y-m-d H:i:s') . '] Gmail poller started' . PHP_EOL;

try {
    $poller = new GmailPollerService($pdo);
    $result = $poller->poll();

    $elapsed = round(microtime(true) - CRON_START, 2);
    echo '[' . date('Y-m-d H:i:s') . '] Done — '
        . "processed: {$result['processed']}, "
        . "errors: {$result['errors']}, "
        . "status: {$result['status']}, "
        . "elapsed: {$elapsed}s" . PHP_EOL;

} catch (\RuntimeException $e) {
    // Config issues (missing token etc.)
    echo '[' . date('Y-m-d H:i:s') . '] CONFIG ERROR: ' . $e->getMessage() . PHP_EOL;
    exit(1);
} catch (\Exception $e) {
    echo '[' . date('Y-m-d H:i:s') . '] ERROR: ' . $e->getMessage() . PHP_EOL;
    error_log('[GmailPoller] Cron error: ' . $e->getMessage());
    exit(1);
}
