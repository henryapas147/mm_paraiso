<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        // Get all properties
        $stmt = $pdo->query("SELECT property_id, property_name, property_code, property_type, is_active, created_at FROM properties ORDER BY property_name ASC");
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $properties]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        // Get single property
        $stmt = $pdo->prepare("SELECT * FROM properties WHERE property_id = ?");
        $stmt->execute([$_GET['id']]);
        $property = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $property]);
      } else if ($action === 'images' && isset($_GET['id'])) {
        // Get property images
        $stmt = $pdo->prepare("SELECT * FROM property_images WHERE property_id = ? ORDER BY sort_order ASC");
        $stmt->execute([$_GET['id']]);
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $images]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);
      
      if ($action === 'create') {
        // Create new property
        $stmt = $pdo->prepare("INSERT INTO properties (property_id, property_name, property_code, property_type, address_line1, city, province, postal_code, country, phone_primary, email, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
          $data['property_id'],
          $data['property_name'],
          $data['property_code'],
          $data['property_type'],
          $data['address_line1'],
          $data['city'],
          $data['province'],
          $data['postal_code'],
          $data['country'],
          $data['phone_primary'],
          $data['email'],
          $data['is_active']
        ]);
        echo json_encode(['success' => true, 'message' => 'Property created successfully']);
      } else if ($action === 'update') {
        // Update property
        $stmt = $pdo->prepare("UPDATE properties SET property_name = ?, property_code = ?, property_type = ?, address_line1 = ?, city = ?, province = ?, postal_code = ?, country = ?, phone_primary = ?, email = ?, is_active = ?, updated_at = NOW() WHERE property_id = ?");
        $stmt->execute([
          $data['property_name'],
          $data['property_code'],
          $data['property_type'],
          $data['address_line1'],
          $data['city'],
          $data['province'],
          $data['postal_code'],
          $data['country'],
          $data['phone_primary'],
          $data['email'],
          $data['is_active'],
          $data['property_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Property updated successfully']);
      } else if ($action === 'delete') {
        // Delete property
        $pdo->beginTransaction();
        
        // Delete property images
        $stmt = $pdo->prepare("SELECT image_url FROM property_images WHERE property_id = ?");
        $stmt->execute([$data['property_id']]);
        $images = $stmt->fetchAll();
        
        foreach ($images as $img) {
          $image_path = __DIR__ . '/../' . $img['image_url'];
          if (file_exists($image_path)) {
            unlink($image_path);
          }
        }
        
        $stmt = $pdo->prepare("DELETE FROM property_images WHERE property_id = ?");
        $stmt->execute([$data['property_id']]);
        
        $stmt = $pdo->prepare("DELETE FROM properties WHERE property_id = ?");
        $stmt->execute([$data['property_id']]);
        
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Property deleted successfully']);
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
