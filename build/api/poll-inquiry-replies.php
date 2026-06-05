<?php

/**
 * API Endpoint: Poll Gmail for inbound inquiry replies (on-demand)
 *
 * Lets authenticated staff trigger the Gmail poller from the UI (the
 * "Check for replies" button on the inquiry view) instead of waiting for cron.
 *
 * Reuses the exact same logic as the cron runner via GmailPollerService.
 *
 * Method : POST
 * Auth   : session + CSRF (handled by applyAuthMiddleware)
 * Returns: { success, status, processed, errors, messages, error_log }
 */

header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();
require_once __DIR__ . '/../services/GmailPollerService.php';

use App\Services\GmailPollerService;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

requirePermission('settings.manage');

// Ensure the Gmail polling refresh token is configured before we try.
$refreshToken = getenv('GMAIL_POLLING_REFRESH_TOKEN') ?: '';
if (defined('GMAIL_POLLING_REFRESH_TOKEN')) {
    $refreshToken = GMAIL_POLLING_REFRESH_TOKEN;
}
if (empty($refreshToken) || $refreshToken === 'your-refresh-token-here') {
    http_response_code(503);
    echo json_encode([
        'success' => false,
        'message' => 'Gmail polling is not configured. Set GMAIL_POLLING_REFRESH_TOKEN in config/credentials.php (run build/get_refresh_token.php to obtain it).',
    ]);
    exit;
}

try {
    $poller = new GmailPollerService($pdo);
    $result = $poller->poll();

    echo json_encode([
        'success'   => $result['errors'] === 0,
        'status'    => $result['status'],
        'processed' => $result['processed'],
        'errors'    => $result['errors'],
        'messages'  => $result['messages'] ?? [],
        'error_log' => $result['error_log'] ?? [],
        'polled_at' => date('Y-m-d H:i:s'),
    ]);
} catch (\Throwable $e) {
    error_log('[poll-inquiry-replies] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to poll Gmail: ' . $e->getMessage(),
    ]);
}
