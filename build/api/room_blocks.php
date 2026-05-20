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
      if ($action === 'list') {
        // Get total count
        $countStmt = $pdo->query("SELECT COUNT(*) as total FROM room_availability_blocks");
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Get paginated blocks with room and property details
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $stmt = $pdo->prepare("
          SELECT rab.*, r.room_name, r.room_number, p.property_name
          FROM room_availability_blocks rab
          LEFT JOIN rooms r ON rab.room_id = r.room_id
          LEFT JOIN properties p ON r.property_id = p.property_id
          ORDER BY rab.date_start DESC
          LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
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
          $stmt = $pdo->prepare("DELETE FROM room_availability_blocks WHERE block_id = ?");
          $result = $stmt->execute([$data['block_id']]);
          $rowCount = $stmt->rowCount();
          if ($rowCount === 0) {
            echo json_encode(['success' => false, 'message' => 'No block found with id: ' . $data['block_id']]);
          } else {
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
