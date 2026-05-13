<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        $stmt = $pdo->query("
          SELECT ili.*, i.invoice_number
          FROM invoice_line_items ili
          LEFT JOIN invoices i ON ili.invoice_id = i.invoice_id
          ORDER BY ili.sort_order ASC
        ");
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $items]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT ili.*, i.invoice_number FROM invoice_line_items ili LEFT JOIN invoices i ON ili.invoice_id = i.invoice_id WHERE ili.line_item_id = ?");
        $stmt->execute([$_GET['id']]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $item]);
      } else if ($action === 'by_invoice' && isset($_GET['invoice_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM invoice_line_items WHERE invoice_id = ? ORDER BY sort_order ASC");
        $stmt->execute([$_GET['invoice_id']]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $items]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $line_item_id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
          mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));

        $stmt = $pdo->prepare("
          INSERT INTO invoice_line_items (line_item_id, invoice_id, description, item_type,
            quantity, unit_price, total_price, reference_id, sort_order)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
          $line_item_id,
          $data['invoice_id'],
          $data['description'],
          $data['item_type'] ?? 'room_charge',
          $data['quantity'] ?? 1,
          $data['unit_price'] ?? 0,
          $data['total_price'],
          $data['reference_id'] ?? null,
          $data['sort_order'] ?? 0,
        ]);
        echo json_encode(['success' => true, 'message' => 'Line item created successfully', 'line_item_id' => $line_item_id]);
      } else if ($action === 'update') {
        $stmt = $pdo->prepare("
          UPDATE invoice_line_items SET description=?, item_type=?, quantity=?,
            unit_price=?, total_price=?, reference_id=?, sort_order=?
          WHERE line_item_id=?
        ");
        $stmt->execute([
          $data['description'],
          $data['item_type'] ?? 'room_charge',
          $data['quantity'] ?? 1,
          $data['unit_price'] ?? 0,
          $data['total_price'],
          $data['reference_id'] ?? null,
          $data['sort_order'] ?? 0,
          $data['line_item_id'],
        ]);
        echo json_encode(['success' => true, 'message' => 'Line item updated successfully']);
      } else if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM invoice_line_items WHERE line_item_id = ?");
        $stmt->execute([$data['line_item_id']]);
        echo json_encode(['success' => true, 'message' => 'Line item deleted successfully']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
