<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        $stmt = $pdo->query("SELECT * FROM payment_methods ORDER BY method_label ASC");
        $methods = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $methods]);
      } else if ($action === 'active') {
        $stmt = $pdo->query("SELECT * FROM payment_methods WHERE is_active = 1 ORDER BY method_label ASC");
        $methods = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $methods]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM payment_methods WHERE method_id = ?");
        $stmt->execute([$_GET['id']]);
        $pm = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $pm]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $method_id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
          mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));

        $stmt = $pdo->prepare("
          INSERT INTO payment_methods (method_id, method_code, method_label, method_type,
            description, is_active, sort_order)
          VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
          $method_id,
          $data['method_code'],
          $data['method_label'],
          $data['method_type'],
          $data['description'] ?? null,
          $data['is_active'] ?? 1,
          $data['sort_order'] ?? 0,
        ]);
        echo json_encode(['success' => true, 'message' => 'Payment method created successfully', 'method_id' => $method_id]);
      } else if ($action === 'update') {
        $stmt = $pdo->prepare("
          UPDATE payment_methods SET method_code=?, method_label=?, method_type=?,
            description=?, is_active=?, sort_order=?
          WHERE method_id=?
        ");
        $stmt->execute([
          $data['method_code'],
          $data['method_label'],
          $data['method_type'],
          $data['description'] ?? null,
          $data['is_active'] ?? 1,
          $data['sort_order'] ?? 0,
          $data['method_id'],
        ]);
        echo json_encode(['success' => true, 'message' => 'Payment method updated successfully']);
      } else if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM payment_methods WHERE method_id = ?");
        $stmt->execute([$data['method_id']]);
        echo json_encode(['success' => true, 'message' => 'Payment method deleted successfully']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
