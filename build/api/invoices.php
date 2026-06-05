<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      requirePermission('payments.view');
      if ($action === 'list') {
        $stmt = $pdo->query("
          SELECT i.*, r.reservation_code
          FROM invoices i
          LEFT JOIN reservations r ON i.reservation_id = r.reservation_id
          ORDER BY i.invoice_date DESC, i.created_at DESC
        ");
        $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $invoices]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT i.*, r.reservation_code FROM invoices i LEFT JOIN reservations r ON i.reservation_id = r.reservation_id WHERE i.invoice_id = ?");
        $stmt->execute([$_GET['id']]);
        $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $invoice]);
      }
      break;

    case 'POST':
      requirePermission('payments.create');
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $invoice_id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
          mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));

        $stmt = $pdo->prepare("
          INSERT INTO invoices (invoice_id, reservation_id, invoice_number, invoice_type,
            invoice_date, due_date, subtotal, discount_amount, tax_amount, total_amount,
            amount_paid, balance, status, notes)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
          $invoice_id,
          $data['reservation_id'],
          $data['invoice_number'],
          $data['invoice_type'],
          $data['invoice_date'],
          $data['due_date'] ?? null,
          $data['subtotal'],
          $data['discount_amount'] ?? 0,
          $data['tax_amount'] ?? 0,
          $data['total_amount'],
          $data['amount_paid'] ?? 0,
          $data['balance'],
          $data['status'] ?? 'draft',
          $data['notes'] ?? null,
        ]);
        echo json_encode(['success' => true, 'message' => 'Invoice created successfully', 'invoice_id' => $invoice_id]);
      } else if ($action === 'update') {
        $stmt = $pdo->prepare("
          UPDATE invoices SET reservation_id=?, invoice_number=?, invoice_type=?,
            invoice_date=?, due_date=?, subtotal=?, discount_amount=?, tax_amount=?,
            total_amount=?, amount_paid=?, balance=?, status=?, notes=?
          WHERE invoice_id=?
        ");
        $stmt->execute([
          $data['reservation_id'],
          $data['invoice_number'],
          $data['invoice_type'],
          $data['invoice_date'],
          $data['due_date'] ?? null,
          $data['subtotal'],
          $data['discount_amount'] ?? 0,
          $data['tax_amount'] ?? 0,
          $data['total_amount'],
          $data['amount_paid'] ?? 0,
          $data['balance'],
          $data['status'],
          $data['notes'] ?? null,
          $data['invoice_id'],
        ]);
        echo json_encode(['success' => true, 'message' => 'Invoice updated successfully']);
      } else if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM invoices WHERE invoice_id = ?");
        $stmt->execute([$data['invoice_id']]);
        echo json_encode(['success' => true, 'message' => 'Invoice deleted successfully']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
