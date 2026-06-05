<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

// Start session for CSRF validation
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$data = null;

// For POST requests, read action from request body
if ($method === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  $action = $data['action'] ?? $action;
}

try {
  switch ($method) {
    case 'GET':
      requirePermission('rooms.view');
      if ($action === 'list') {
        // Build query with optional room_id filter
        $whereClause = "";
        $params = [];

        if (isset($_GET['room_id'])) {
          $whereClause = "WHERE room_id = ?";
          $params[] = $_GET['room_id'];
        }

        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM room_availability_blocks " . $whereClause;
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($params);
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Get paginated blocks with room and property details
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        
        // For the main query with joins, use table alias to avoid ambiguity
        $whereClauseWithAlias = $whereClause ? str_replace('WHERE room_id = ?', 'WHERE rab.room_id = ?', $whereClause) : '';
        
        $sql = "
          SELECT rab.*, r.room_name, r.room_number, p.property_name
          FROM room_availability_blocks rab
          LEFT JOIN rooms r ON rab.room_id = r.room_id
          LEFT JOIN properties p ON r.property_id = p.property_id
          $whereClauseWithAlias
          ORDER BY rab.date_start DESC
          LIMIT ? OFFSET ?
        ";
        $params[] = $limit;
        $params[] = $offset;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $blocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $blocks, 'total' => $total]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        // Get single block with room and property details
        $stmt = $pdo->prepare("
          SELECT rab.*, r.room_name, r.room_number, p.property_name
          FROM room_availability_blocks rab
          LEFT JOIN rooms r ON rab.room_id = r.room_id
          LEFT JOIN properties p ON r.property_id = p.property_id
          WHERE rab.block_id = ?
        ");
        $stmt->execute([$_GET['id']]);
        $block = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $block]);
      }
      break;

    case 'POST':
      requirePermission('rooms.edit');
      // Validate CSRF token for write operations
      if (!validateCsrfToken($data['csrf_token'] ?? '')) {
        echo json_encode(['success' => false, 'message' => 'Invalid or missing CSRF token']);
        exit;
      }

      if ($action === 'create') {
        // Validate required fields
        if (empty($data['block_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: block_id']);
          exit;
        }
        if (empty($data['room_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: room_id']);
          exit;
        }
        if (empty($data['date_start'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: date_start']);
          exit;
        }
        if (empty($data['date_end'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: date_end']);
          exit;
        }
        try {
          $stmt = $pdo->prepare("INSERT INTO room_availability_blocks (block_id, room_id, block_type, date_start, date_end, time_start, time_end, reason, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
          $stmt->execute([
            $data['block_id'],
            $data['room_id'],
            $data['block_type'] ?? 'maintenance',
            $data['date_start'],
            $data['date_end'],
            $data['time_start'] ?? null,
            $data['time_end'] ?? null,
            $data['reason'] ?? null,
            $data['is_active'] ?? 1
          ]);
          syncRoomStatus($pdo, $data['room_id']);
          echo json_encode(['success' => true, 'message' => 'Block created successfully']);
        } catch (PDOException $e) {
          echo json_encode(['success' => false, 'message' => 'Database error on create: ' . $e->getMessage()]);
        }
      } else if ($action === 'update') {
        // Validate required fields
        if (empty($data['block_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: block_id']);
          exit;
        }
        if (empty($data['room_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: room_id']);
          exit;
        }
        if (empty($data['date_start'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: date_start']);
          exit;
        }
        if (empty($data['date_end'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: date_end']);
          exit;
        }
        try {
          $stmt = $pdo->prepare("UPDATE room_availability_blocks SET room_id = ?, block_type = ?, date_start = ?, date_end = ?, time_start = ?, time_end = ?, reason = ?, is_active = ?, updated_at = NOW() WHERE block_id = ?");
          $result = $stmt->execute([
            $data['room_id'],
            $data['block_type'] ?? 'maintenance',
            $data['date_start'],
            $data['date_end'],
            $data['time_start'] ?? null,
            $data['time_end'] ?? null,
            $data['reason'] ?? null,
            $data['is_active'] ?? 1,
            $data['block_id']
          ]);
          $rowCount = $stmt->rowCount();
          syncRoomStatus($pdo, $data['room_id']);
          echo json_encode(['success' => true, 'message' => 'Block updated successfully', 'rows_affected' => $rowCount]);
        } catch (PDOException $e) {
          echo json_encode(['success' => false, 'message' => 'Database error on update: ' . $e->getMessage()]);
        }
      } else if ($action === 'delete') {
        if (empty($data['block_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: block_id']);
          exit;
        }
        try {
          // Get room_id before deleting for status sync
          $stmt = $pdo->prepare("SELECT room_id FROM room_availability_blocks WHERE block_id = ?");
          $stmt->execute([$data['block_id']]);
          $block = $stmt->fetch(PDO::FETCH_ASSOC);
          $roomId = $block ? $block['room_id'] : null;

          $stmt = $pdo->prepare("DELETE FROM room_availability_blocks WHERE block_id = ?");
          $result = $stmt->execute([$data['block_id']]);
          $rowCount = $stmt->rowCount();
          if ($rowCount === 0) {
            echo json_encode(['success' => false, 'message' => 'No block found with id: ' . $data['block_id']]);
          } else {
            if ($roomId) {
              syncRoomStatus($pdo, $roomId);
            }
            echo json_encode(['success' => true, 'message' => 'Block deleted successfully', 'rows_affected' => $rowCount]);
          }
        } catch (PDOException $e) {
          echo json_encode(['success' => false, 'message' => 'Database error on delete: ' . $e->getMessage()]);
        }
      } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action: ' . $action]);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

/**
 * Sync room status with active availability blocks.
 * Should be called after any CRUD operation on room_availability_blocks.
 */
function syncRoomStatus($pdo, $roomId) {
  $nowDate = date('Y-m-d');
  $nowTime = date('H:i:s');

  // Find the most recently active block for this room
  $stmt = $pdo->prepare("
    SELECT block_type
    FROM room_availability_blocks
    WHERE room_id = ? AND is_active = 1
      AND (
        (date_start < ?) OR (date_start = ? AND (time_start IS NULL OR time_start <= ?))
      )
      AND (
        (date_end > ?) OR (date_end = ? AND (time_end IS NULL OR time_end > ?))
      )
    ORDER BY date_end DESC, time_end DESC
    LIMIT 1
  ");
  $stmt->execute([$roomId, $nowDate, $nowDate, $nowTime, $nowDate, $nowDate, $nowTime]);
  $activeBlock = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($activeBlock) {
    // Room has an active block - set status to the actual block_type
    $newStatus = $activeBlock['block_type'];
    $stmt = $pdo->prepare("UPDATE rooms SET room_status = ? WHERE room_id = ?");
    $stmt->execute([$newStatus, $roomId]);
  } else {
    // No active block - if room has any non-available status, reset to available
    $stmt = $pdo->prepare("UPDATE rooms SET room_status = 'available' WHERE room_id = ? AND room_status != 'available'");
    $stmt->execute([$roomId]);
  }
}
