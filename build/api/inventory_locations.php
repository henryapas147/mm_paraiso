<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      requirePermission('inventory.view');
      if ($action === 'list') {
        $stmt = $pdo->query("
          SELECT il.*, p.property_name
          FROM inventory_locations il
          INNER JOIN properties p ON il.property_id = p.property_id
          WHERE il.is_active = 1
          ORDER BY p.property_name ASC, il.location_type ASC, il.location_name ASC
        ");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      } elseif ($action === 'properties') {
        $stmt = $pdo->query("SELECT property_id, property_name FROM properties WHERE is_active = 1 ORDER BY property_name ASC");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT il.*, p.property_name FROM inventory_locations il INNER JOIN properties p ON il.property_id = p.property_id WHERE il.location_id = ?");
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
      }
      break;

    case 'POST':
      requirePermission('inventory.create');
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        $stmt = $pdo->prepare("INSERT INTO inventory_locations (location_id, property_id, location_code, location_name, location_type, description, is_active) VALUES (?,?,?,?,?,?,?)");
        $locationCode = 'LOC-' . strtoupper(substr(str_replace('-', '', $id), 0, 8));
        $stmt->execute([$id, $data['property_id'], $locationCode, $data['location_name'], $data['location_type'] ?? 'other', $data['description'] ?? null, $data['is_active'] ?? 1]);
        echo json_encode(['success' => true, 'message' => 'Location created', 'location_id' => $id]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE inventory_locations SET property_id=?, location_name=?, location_type=?, description=?, is_active=? WHERE location_id=?");
        $stmt->execute([$data['property_id'], $data['location_name'], $data['location_type'] ?? 'other', $data['description'] ?? null, $data['is_active'] ?? 1, $data['location_id']]);
        echo json_encode(['success' => true, 'message' => 'Location updated']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("UPDATE inventory_locations SET is_active=0 WHERE location_id=?");
        $stmt->execute([$data['location_id']]);
        echo json_encode(['success' => true, 'message' => 'Location deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
