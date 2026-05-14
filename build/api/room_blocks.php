<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        // Get all room blocks with property and category info
        $stmt = $pdo->query("
          SELECT rb.*, p.property_name, c.category_name 
          FROM room_blocks rb 
          LEFT JOIN properties p ON rb.property_id = p.property_id 
          LEFT JOIN room_categories c ON rb.category_id = c.category_id 
          ORDER BY p.property_name, rb.block_name ASC
        ");
        $blocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $blocks]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        // Get single room block
        $stmt = $pdo->prepare("SELECT * FROM room_blocks WHERE block_id = ?");
        $stmt->execute([$_GET['id']]);
        $block = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $block]);
      } else if ($action === 'by_property' && isset($_GET['property_id'])) {
        // Get room blocks by property
        $stmt = $pdo->prepare("SELECT * FROM room_blocks WHERE property_id = ? ORDER BY block_name ASC");
        $stmt->execute([$_GET['property_id']]);
        $blocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $blocks]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);
      
      if ($action === 'create') {
        // Create new room block
        $stmt = $pdo->prepare("INSERT INTO room_blocks (block_id, property_id, category_id, block_name, block_code, floor_from, floor_to, total_rooms, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
          $data['block_id'],
          $data['property_id'],
          $data['category_id'],
          $data['block_name'],
          $data['block_code'],
          $data['floor_from'],
          $data['floor_to'],
          $data['total_rooms'],
          $data['status']
        ]);
        echo json_encode(['success' => true, 'message' => 'Room block created successfully']);
      } else if ($action === 'update') {
        // Update room block
        $stmt = $pdo->prepare("UPDATE room_blocks SET property_id = ?, category_id = ?, block_name = ?, block_code = ?, floor_from = ?, floor_to = ?, total_rooms = ?, status = ?, updated_at = NOW() WHERE block_id = ?");
        $stmt->execute([
          $data['property_id'],
          $data['category_id'],
          $data['block_name'],
          $data['block_code'],
          $data['floor_from'],
          $data['floor_to'],
          $data['total_rooms'],
          $data['status'],
          $data['block_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Room block updated successfully']);
      } else if ($action === 'delete') {
        // Delete room block
        $stmt = $pdo->prepare("DELETE FROM room_blocks WHERE block_id = ?");
        $stmt->execute([$data['block_id']]);
        echo json_encode(['success' => true, 'message' => 'Room block deleted successfully']);
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
