<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      requirePermission('extra_charges.view');
      if ($action === 'list') {
        $stmt = $pdo->query("
          SELECT ec.*
          FROM extra_charges ec
          ORDER BY ec.charged_at DESC
        ");
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $items]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM extra_charges WHERE charge_id = ?");
        $stmt->execute([$_GET['id']]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $item]);
      } else if ($action === 'by_reservation' && isset($_GET['reservation_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM extra_charges WHERE reservation_id = ? ORDER BY charged_at DESC");
        $stmt->execute([$_GET['reservation_id']]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $items]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        requirePermission('extra_charges.create');
        $charge_id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
          mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));

        $stmt = $pdo->prepare("
          INSERT INTO extra_charges (charge_id, reservation_id, charge_type, description, amount, charged_at, notes)
          VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
          $charge_id,
          $data['reservation_id'],
          $data['charge_type'] ?? 'other',
          $data['description'],
          $data['amount'],
          $data['charged_at'] ?? date('Y-m-d H:i:s'),
          $data['notes'] ?? null,
        ]);
        echo json_encode(['success' => true, 'message' => 'Extra charge created successfully', 'charge_id' => $charge_id]);

      } else if ($action === 'update') {
        requirePermission('extra_charges.edit');
        $stmt = $pdo->prepare("
          UPDATE extra_charges SET reservation_id=?, charge_type=?, description=?, amount=?, charged_at=?, notes=?
          WHERE charge_id=?
        ");
        $stmt->execute([
          $data['reservation_id'],
          $data['charge_type'] ?? 'other',
          $data['description'],
          $data['amount'],
          $data['charged_at'] ?? date('Y-m-d H:i:s'),
          $data['notes'] ?? null,
          $data['charge_id'],
        ]);
        echo json_encode(['success' => true, 'message' => 'Extra charge updated successfully']);

      } else if ($action === 'delete') {
        requirePermission('extra_charges.delete');
        $stmt = $pdo->prepare("DELETE FROM extra_charges WHERE charge_id = ?");
        $stmt->execute([$data['charge_id']]);
        echo json_encode(['success' => true, 'message' => 'Extra charge deleted successfully']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
