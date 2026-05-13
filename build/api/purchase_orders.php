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
          SELECT po.*, s.company_name, p.property_name
          FROM purchase_orders po
          INNER JOIN suppliers s ON po.supplier_id = s.supplier_id
          INNER JOIN properties p ON po.property_id = p.property_id
          ORDER BY po.po_date DESC, po.po_number DESC
        ");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      } elseif ($action === 'related') {
        $suppliers = $pdo->query("SELECT supplier_id, company_name, supplier_code FROM suppliers WHERE is_active = 1 ORDER BY company_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $properties = $pdo->query("SELECT property_id, property_name FROM properties ORDER BY property_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['suppliers' => $suppliers, 'properties' => $properties]]);
      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT po.*, s.company_name, p.property_name FROM purchase_orders po INNER JOIN suppliers s ON po.supplier_id = s.supplier_id INNER JOIN properties p ON po.property_id = p.property_id WHERE po.po_id = ?");
        $stmt->execute([$_GET['id']]);
        $po = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($po) {
          $items = $pdo->prepare("SELECT poi.*, pr.product_name, pr.product_code, u.uom_name FROM purchase_order_items poi INNER JOIN products pr ON poi.product_id = pr.product_id INNER JOIN units_of_measure u ON poi.uom_id = u.uom_id WHERE poi.po_id = ?");
          $items->execute([$po['po_id']]);
          $po['items'] = $items->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode(['success' => true, 'data' => $po]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO purchase_orders (po_id, po_number, supplier_id, property_id, po_date, expected_delivery, po_status, subtotal, tax_amount, total_amount, notes) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $id, $data['po_number'], $data['supplier_id'], $data['property_id'],
          $data['po_date'] ?? date('Y-m-d'), $data['expected_delivery'] ?? null,
          $data['po_status'] ?? 'draft', $data['subtotal'] ?? 0,
          $data['tax_amount'] ?? 0, $data['total_amount'] ?? 0, $data['notes'] ?? null
        ]);
        foreach ($data['items'] ?? [] as $item) {
          $itemId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
            mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
          $pi = $pdo->prepare("INSERT INTO purchase_order_items (po_item_id, po_id, product_id, uom_id, quantity_ordered, unit_cost, total_cost) VALUES (?,?,?,?,?,?,?)");
          $pi->execute([$itemId, $id, $item['product_id'], $item['uom_id'], $item['quantity_ordered'], $item['unit_cost'], $item['total_cost']]);
        }
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Purchase order created', 'po_id' => $id]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE purchase_orders SET supplier_id=?, property_id=?, po_number=?, po_date=?, expected_delivery=?, po_status=?, payment_terms=?, delivery_address=?, notes=? WHERE po_id=?");
        $stmt->execute([
          $data['supplier_id'], $data['property_id'], $data['po_number'],
          $data['po_date'], $data['expected_delivery'] ?: null, $data['po_status'],
          $data['payment_terms'] ?? null, $data['delivery_address'] ?? null,
          $data['notes'] ?? null, $data['po_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Purchase order updated']);

      } elseif ($action === 'update_status') {
        $stmt = $pdo->prepare("UPDATE purchase_orders SET po_status=? WHERE po_id=?");
        $stmt->execute([$data['po_status'], $data['po_id']]);
        echo json_encode(['success' => true, 'message' => 'PO status updated']);

      } elseif ($action === 'delete') {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("DELETE FROM purchase_order_items WHERE po_id=?");
        $stmt->execute([$data['po_id']]);
        $stmt = $pdo->prepare("DELETE FROM purchase_orders WHERE po_id=?");
        $stmt->execute([$data['po_id']]);
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Purchase order deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
