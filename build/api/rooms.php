<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        // Get all rooms with property and category info
        $stmt = $pdo->query("
          SELECT r.*, p.property_name, c.category_name 
          FROM rooms r 
          LEFT JOIN properties p ON r.property_id = p.property_id 
          LEFT JOIN room_categories c ON r.category_id = c.category_id 
          ORDER BY p.property_name, r.room_number ASC
        ");
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $rooms]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        // Get single room
        $stmt = $pdo->prepare("SELECT * FROM rooms WHERE room_id = ?");
        $stmt->execute([$_GET['id']]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $room]);
      } else if ($action === 'by_property' && isset($_GET['property_id'])) {
        // Get rooms by property
        $stmt = $pdo->prepare("SELECT * FROM rooms WHERE property_id = ? ORDER BY room_number ASC");
        $stmt->execute([$_GET['property_id']]);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $rooms]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);
      
      if ($action === 'create') {
        // Create new room
        $stmt = $pdo->prepare("INSERT INTO rooms (room_id, property_id, category_id, room_number, floor_number, room_type, base_price, max_occupancy, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
          $data['room_id'],
          $data['property_id'],
          $data['category_id'],
          $data['room_number'],
          $data['floor_number'],
          $data['room_type'],
          $data['base_price'],
          $data['max_occupancy'],
          $data['status']
        ]);
        echo json_encode(['success' => true, 'message' => 'Room created successfully']);
      } else if ($action === 'update') {
        // Update room
        $stmt = $pdo->prepare("UPDATE rooms SET property_id = ?, category_id = ?, room_number = ?, floor_number = ?, room_type = ?, base_price = ?, max_occupancy = ?, status = ?, updated_at = NOW() WHERE room_id = ?");
        $stmt->execute([
          $data['property_id'],
          $data['category_id'],
          $data['room_number'],
          $data['floor_number'],
          $data['room_type'],
          $data['base_price'],
          $data['max_occupancy'],
          $data['status'],
          $data['room_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Room updated successfully']);
      } else if ($action === 'delete') {
        // Delete room
        $stmt = $pdo->prepare("DELETE FROM rooms WHERE room_id = ?");
        $stmt->execute([$data['room_id']]);
        echo json_encode(['success' => true, 'message' => 'Room deleted successfully']);
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
