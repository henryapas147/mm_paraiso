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
        $stmt = $pdo->query("SELECT * FROM units_of_measure WHERE is_active = 1 ORDER BY uom_type ASC, uom_name ASC");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM units_of_measure WHERE uom_id = ?");
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $stmt = $pdo->prepare("INSERT INTO units_of_measure (uom_code, uom_name, uom_type, description, is_active) VALUES (?,?,?,?,?)");
        $stmt->execute([$data['uom_code'], $data['uom_name'], $data['uom_type'] ?? 'count', $data['description'] ?? null, $data['is_active'] ?? 1]);
        echo json_encode(['success' => true, 'message' => 'Unit of measure created', 'uom_id' => $pdo->lastInsertId()]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE units_of_measure SET uom_code=?, uom_name=?, uom_type=?, description=?, is_active=? WHERE uom_id=?");
        $stmt->execute([$data['uom_code'], $data['uom_name'], $data['uom_type'] ?? 'count', $data['description'] ?? null, $data['is_active'] ?? 1, $data['uom_id']]);
        echo json_encode(['success' => true, 'message' => 'Unit of measure updated']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("UPDATE units_of_measure SET is_active=0 WHERE uom_id=?");
        $stmt->execute([$data['uom_id']]);
        echo json_encode(['success' => true, 'message' => 'Unit of measure deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
