<?php
/**
 * Room Status Sync Script
 *
 * Run this periodically (e.g., via cron every minute) to keep room_status
 * in sync with active room_availability_blocks.
 *
 * This file is the RUNNER. Place it alongside poll-gmail-replies.php in:
 *   build/cron/sync_room_status.php
 *
 * How to run
 * ----------
 *   CLI / cron (recommended):
 *     php /path/to/build/cron/sync_room_status.php
 *
 *   Hostinger cron example (every minute):
 *     * * * * * /usr/bin/php /home/USER/domains/DOMAIN/public_html/build/cron/sync_room_status.php >> /home/USER/room-sync.log 2>&1
 *
 *   Browser / on-demand (must supply the secret token):
 *     https://your-site/build/cron/sync_room_status.php?token=YOUR_CRON_SYNC_SECRET
 *
 * Security
 * --------
 *   When invoked over HTTP we require a matching CRON_SYNC_SECRET. Define it in
 *   config/credentials.php:
 *     putenv('CRON_SYNC_SECRET=some-long-random-string');
 *   CLI invocations are always trusted.
 *
 * Usage:
 *   php sync_room_status.php
 *   php sync_room_status.php --dry-run  (shows what would change without updating)
 */

declare(strict_types=1);

$isCli = (PHP_SAPI === 'cli');

// -----------------------------------------------------------------------------
// Resolve config root relative to THIS file's location.
// This file must live in build/cron/ — same directory as poll-gmail-replies.php.
// __DIR__ will always be the real directory of this script, regardless of where
// the cron command is invoked from (fixes "Local database unavailable" on Hostinger).
// -----------------------------------------------------------------------------
$configDir = __DIR__ . '/../../config';

// -----------------------------------------------------------------------------
// HTTP guard — only allow browser access with the correct secret token.
// -----------------------------------------------------------------------------
if (!$isCli) {
    header('Content-Type: application/json');

    // Load credentials early (just for the secret — db.php loads it again below).
    $credEarly = $configDir . '/credentials.php';
    if (is_file($credEarly)) {
        require_once $credEarly;
    }

    $expected = getenv('CRON_SYNC_SECRET') ?: '';
    $provided = $_GET['token'] ?? '';

    if ($expected === '' || !hash_equals($expected, (string) $provided)) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'Forbidden. A valid ?token is required to run the sync over HTTP.',
        ]);
        exit;
    }
}

// -----------------------------------------------------------------------------
// Bootstrap: database connection (this also loads config/credentials.php).
// -----------------------------------------------------------------------------
require_once $configDir . '/db.php';

$dryRun = in_array('--dry-run', $argv ?? []);

$nowDate = date('Y-m-d');
$nowTime = date('H:i:s');

/**
 * Emit a result line in a format appropriate to the execution context.
 */
function sync_output(array $result, bool $isCli): void
{
    if ($isCli) {
        $ts = date('Y-m-d H:i:s');
        echo "[{$ts}] Room sync — updates: {$result['updates']}\n";
        foreach ($result['messages'] ?? [] as $m) {
            echo "  - {$m}\n";
        }
        return;
    }

    echo json_encode([
        'success'   => true,
        'updates'   => $result['updates'],
        'messages'  => $result['messages'] ?? [],
        'synced_at' => date('Y-m-d H:i:s'),
    ]);
}

try {
    $messages = [];

    // Step 1: Find all rooms that SHOULD be blocked/maintenance right now.
    $stmt = $pdo->prepare("
        SELECT DISTINCT r.room_id, rab.block_type
        FROM rooms r
        INNER JOIN room_availability_blocks rab ON r.room_id = rab.room_id
        WHERE rab.is_active = 1
          AND (
            (rab.date_start < ?) OR (rab.date_start = ? AND (rab.time_start IS NULL OR rab.time_start <= ?))
          )
          AND (
            (rab.date_end > ?) OR (rab.date_end = ? AND (rab.time_end IS NULL OR rab.time_end > ?))
          )
    ");
    $stmt->execute([$nowDate, $nowDate, $nowTime, $nowDate, $nowDate, $nowTime]);
    $activeBlocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $blockedRooms = [];
    foreach ($activeBlocks as $block) {
        // Use the actual block_type value as the room status
        $status = $block['block_type'];
        $blockedRooms[$block['room_id']] = $status;
    }

    // Step 2: Find all rooms currently marked with any non-available status.
    $stmt = $pdo->query("SELECT room_id, room_status FROM rooms WHERE room_status != 'available'");
    $currentlyBlocked = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $updates = 0;

    // Step 3: Update rooms that should be blocked/maintenance.
    foreach ($blockedRooms as $roomId => $status) {
        $stmt = $pdo->prepare("SELECT room_status FROM rooms WHERE room_id = ?");
        $stmt->execute([$roomId]);
        $current = $stmt->fetchColumn();

        if ($current !== $status) {
            if ($dryRun) {
                $messages[] = "[DRY-RUN] Would update room {$roomId}: {$current} -> {$status}";
            } else {
                $stmt = $pdo->prepare("UPDATE rooms SET room_status = ? WHERE room_id = ?");
                $stmt->execute([$status, $roomId]);
                $messages[] = "Updated room {$roomId}: {$current} -> {$status}";
            }
            $updates++;
        }
    }

    // Step 4: Reset rooms that are no longer blocked.
    foreach ($currentlyBlocked as $room) {
        if (!isset($blockedRooms[$room['room_id']])) {
            if ($dryRun) {
                $messages[] = "[DRY-RUN] Would update room {$room['room_id']}: {$room['room_status']} -> available";
            } else {
                $stmt = $pdo->prepare("UPDATE rooms SET room_status = 'available' WHERE room_id = ?");
                $stmt->execute([$room['room_id']]);
                $messages[] = "Updated room {$room['room_id']}: {$room['room_status']} -> available";
            }
            $updates++;
        }
    }

    if ($updates === 0) {
        $messages[] = 'All room statuses are already in sync.';
    }

    sync_output(['updates' => $updates, 'messages' => $messages], $isCli);

} catch (\Throwable $e) {
    $detail = sprintf(
        '%s: %s (in %s:%d)',
        get_class($e),
        $e->getMessage(),
        basename($e->getFile()),
        $e->getLine()
    );
    error_log('[RoomSync][cron] Fatal: ' . $detail);

    if ($isCli) {
        fwrite(STDERR, '[' . date('Y-m-d H:i:s') . "] Room sync FAILED — {$detail}\n");
        exit(1);
    }

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'status'  => 'fatal',
        'message' => 'Room sync failed.',
        'error'   => $detail,
    ]);
    exit;
}