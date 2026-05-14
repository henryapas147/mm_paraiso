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
        $stmt = $pdo->query("
          SELECT p.*, pm.method_label, pm.method_type
          FROM payments p
          LEFT JOIN payment_methods pm ON p.method_id = pm.method_id
          ORDER BY p.payment_date DESC, p.created_at DESC
        ");
        $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $payments]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT p.*, pm.method_label FROM payments p LEFT JOIN payment_methods pm ON p.method_id = pm.method_id WHERE p.payment_id = ?");
        $stmt->execute([$_GET['id']]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $payment]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $payment_id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
          mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));

        $stmt = $pdo->prepare("
          INSERT INTO payments (payment_id, reservation_id, method_id, transaction_type, amount,
            reference_number, proof_image_url, payment_status, payment_date, payment_time, notes)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
          $payment_id,
          $data['reservation_id'],
          $data['method_id'],
          $data['transaction_type'],
          $data['amount'],
          $data['reference_number'] ?? null,
          $data['proof_image_url'] ?? null,
          $data['payment_status'] ?? 'pending',
          $data['payment_date'],
          $data['payment_time'] ?? null,
          $data['notes'] ?? null,
        ]);
        echo json_encode(['success' => true, 'message' => 'Payment added successfully', 'payment_id' => $payment_id]);
      } else if ($action === 'update') {
        $stmt = $pdo->prepare("
          UPDATE payments SET reservation_id=?, method_id=?, transaction_type=?, amount=?,
            reference_number=?, proof_image_url=?, payment_status=?, payment_date=?, payment_time=?, notes=?
          WHERE payment_id=?
        ");
        $stmt->execute([
          $data['reservation_id'],
          $data['method_id'],
          $data['transaction_type'],
          $data['amount'],
          $data['reference_number'] ?? null,
          $data['proof_image_url'] ?? null,
          $data['payment_status'],
          $data['payment_date'],
          $data['payment_time'] ?? null,
          $data['notes'] ?? null,
          $data['payment_id'],
        ]);
        echo json_encode(['success' => true, 'message' => 'Payment updated successfully']);
      } else if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM payments WHERE payment_id = ?");
        $stmt->execute([$data['payment_id']]);
        echo json_encode(['success' => true, 'message' => 'Payment deleted successfully']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
