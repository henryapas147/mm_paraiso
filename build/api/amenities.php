<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        // Get all amenities
        $stmt = $pdo->query("SELECT * FROM amenities ORDER BY amenity_name ASC");
        $amenities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $amenities]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        // Get single amenity
        $stmt = $pdo->prepare("SELECT * FROM amenities WHERE amenity_id = ?");
        $stmt->execute([$_GET['id']]);
        $amenity = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $amenity]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);
      
      if ($action === 'create') {
        // Create new amenity
        $stmt = $pdo->prepare("INSERT INTO amenities (amenity_id, amenity_name, amenity_code, description, icon_class, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
          $data['amenity_id'],
          $data['amenity_name'],
          $data['amenity_code'],
          $data['description'],
          $data['icon_class'],
          $data['status']
        ]);
        echo json_encode(['success' => true, 'message' => 'Amenity created successfully']);
      } else if ($action === 'update') {
        // Update amenity
        $stmt = $pdo->prepare("UPDATE amenities SET amenity_name = ?, amenity_code = ?, description = ?, icon_class = ?, status = ?, updated_at = NOW() WHERE amenity_id = ?");
        $stmt->execute([
          $data['amenity_name'],
          $data['amenity_code'],
          $data['description'],
          $data['icon_class'],
          $data['status'],
          $data['amenity_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Amenity updated successfully']);
      } else if ($action === 'delete') {
        // Delete amenity
        $stmt = $pdo->prepare("DELETE FROM amenities WHERE amenity_id = ?");
        $stmt->execute([$data['amenity_id']]);
        echo json_encode(['success' => true, 'message' => 'Amenity deleted successfully']);
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
