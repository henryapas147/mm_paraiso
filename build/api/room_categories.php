<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        // Get all room categories
        $stmt = $pdo->query("SELECT * FROM room_categories ORDER BY category_name ASC");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $categories]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        // Get single category
        $stmt = $pdo->prepare("SELECT * FROM room_categories WHERE category_id = ?");
        $stmt->execute([$_GET['id']]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $category]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);
      
      if ($action === 'create') {
        // Create new room category
        $stmt = $pdo->prepare("INSERT INTO room_categories (category_id, category_name, category_code, description, base_price, max_occupancy, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
          $data['category_id'],
          $data['category_name'],
          $data['category_code'],
          $data['description'],
          $data['base_price'],
          $data['max_occupancy'],
          $data['status']
        ]);
        echo json_encode(['success' => true, 'message' => 'Room category created successfully']);
      } else if ($action === 'update') {
        // Update room category
        $stmt = $pdo->prepare("UPDATE room_categories SET category_name = ?, category_code = ?, description = ?, base_price = ?, max_occupancy = ?, status = ?, updated_at = NOW() WHERE category_id = ?");
        $stmt->execute([
          $data['category_name'],
          $data['category_code'],
          $data['description'],
          $data['base_price'],
          $data['max_occupancy'],
          $data['status'],
          $data['category_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Room category updated successfully']);
      } else if ($action === 'delete') {
        // Delete room category
        $stmt = $pdo->prepare("DELETE FROM room_categories WHERE category_id = ?");
        $stmt->execute([$data['category_id']]);
        echo json_encode(['success' => true, 'message' => 'Room category deleted successfully']);
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
