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
        requirePermission('payments.view');
        $stmt = $pdo->query("
          SELECT p.*, pm.method_label, pm.method_type
          FROM payments p
          LEFT JOIN payment_methods pm ON p.method_id = pm.method_id
          ORDER BY p.payment_date DESC, p.created_at DESC
        ");
        $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $payments]);
      } else if ($action === 'my_payments') {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
          echo json_encode(['success' => false, 'message' => 'Not authenticated']);
          exit;
        }
        $stmt = $pdo->prepare("
          SELECT p.*, pm.method_label, pm.method_type, r.check_in_date, r.check_out_date
          FROM payments p
          LEFT JOIN payment_methods pm ON p.method_id = pm.method_id
          JOIN reservations r ON p.reservation_id = r.reservation_id
          WHERE r.customer_user_id = ?
          ORDER BY p.payment_date DESC
        ");
        $stmt->execute([$userId]);
        $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $payments]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        requirePermission('payments.view');
        $stmt = $pdo->prepare("SELECT p.*, pm.method_label FROM payments p LEFT JOIN payment_methods pm ON p.method_id = pm.method_id WHERE p.payment_id = ?");
        $stmt->execute([$_GET['id']]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $payment]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        requirePermission('payments.create');
        $payment_id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
          mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));

        // Handle payment_method_code to get method_id
        $method_id = $data['method_id'] ?? null;
        if (isset($data['payment_method_code']) && !$method_id) {
          $stmt = $pdo->prepare("SELECT method_id FROM payment_methods WHERE method_code = ? AND is_active = 1");
          $stmt->execute([$data['payment_method_code']]);
          $method = $stmt->fetch(PDO::FETCH_ASSOC);
          $method_id = $method ? $method['method_id'] : null;
        }

        $stmt = $pdo->prepare("
          INSERT INTO payments (payment_id, reservation_id, method_id, transaction_type, amount,
            reference_number, proof_image_url, payment_status, payment_date, payment_time, notes)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
          $payment_id,
          $data['reservation_id'],
          $method_id,
          $data['transaction_type'],
          $data['amount'],
          $data['reference_number'] ?? null,
          $data['proof_image_url'] ?? null,
          $data['payment_status'] ?? 'pending',
          $data['payment_date'],
          $data['payment_time'] ?? null,
          $data['notes'] ?? null,
        ]);

        // Update reservation paid_amount and balance_amount
        $amount = floatval($data['amount']);
        $updateStmt = $pdo->prepare("
          UPDATE reservations 
          SET paid_amount = paid_amount + ?, 
              balance_amount = balance_amount - ?,
              payment_status = CASE 
                WHEN balance_amount - ? <= 0 THEN 'paid'
                WHEN paid_amount + ? > 0 THEN 'partial'
                ELSE 'unpaid'
              END,
              updated_at = NOW()
          WHERE reservation_id = ?
        ");
        $updateStmt->execute([$amount, $amount, $amount, $amount, $data['reservation_id']]);

        echo json_encode(['success' => true, 'message' => 'Payment added successfully', 'payment_id' => $payment_id]);
      } else if ($action === 'update') {
        requirePermission('payments.edit');
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
        requirePermission('payments.delete');
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
