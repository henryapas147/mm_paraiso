<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

// Support both JSON and FormData
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (strpos($contentType, 'application/json') !== false) {
  $data = json_decode(file_get_contents('php://input'), true);
} else {
  $data = $_POST;
}

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
        $suppliers = $pdo->query("SELECT supplier_id, company_name, supplier_code, payment_terms FROM suppliers WHERE is_active = 1 ORDER BY company_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $properties = $pdo->query("SELECT property_id, property_name FROM properties ORDER BY property_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        // Fetch products for item selection
        $products = $pdo->query("
          SELECT p.product_id, p.product_name, p.product_code, p.uom_id, u.uom_name 
          FROM products p 
          LEFT JOIN units_of_measure u ON p.uom_id = u.uom_id 
          WHERE p.is_active = 1 
          ORDER BY p.product_name ASC
        ") ->fetchAll(PDO::FETCH_ASSOC);
        $uoms = $pdo->query("SELECT uom_id, uom_code, uom_name FROM units_of_measure WHERE is_active = 1 ORDER BY uom_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['suppliers' => $suppliers, 'properties' => $properties, 'products' => $products, 'uoms' => $uoms]]);
      } elseif ($action === 'next_number') {
        $year = $_GET['year'] ?? date('Y');
        // Get the highest PO number for this year
        $stmt = $pdo->prepare("SELECT po_number FROM purchase_orders WHERE po_number LIKE ? ORDER BY po_number DESC LIMIT 1");
        $stmt->execute(["PO-{$year}-%"]);
        $last = $stmt->fetch(PDO::FETCH_ASSOC);

        $nextNum = 1;
        if ($last) {
          // Extract the number from format PO-2024-000001
          $parts = explode('-', $last['po_number']);
          if (count($parts) >= 3) {
            $lastNum = intval(end($parts));
            $nextNum = $lastNum + 1;
          }
        }
        $poNumber = sprintf("PO-%s-%06d", $year, $nextNum);
        echo json_encode(['success' => true, 'po_number' => $poNumber]);
      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT po.*, s.company_name, p.property_name,
          r.region_name, pr.province_name, cm.city_municipality_name, b.barangay_name
          FROM purchase_orders po
          INNER JOIN suppliers s ON po.supplier_id = s.supplier_id
          INNER JOIN properties p ON po.property_id = p.property_id
          LEFT JOIN psgc_regions r ON po.delivery_region_code COLLATE utf8mb4_general_ci = r.region_code
          LEFT JOIN psgc_provinces pr ON po.delivery_province_code COLLATE utf8mb4_general_ci = pr.province_code
          LEFT JOIN psgc_cities_municipalities cm ON po.delivery_city_code COLLATE utf8mb4_general_ci = cm.city_municipality_code
          LEFT JOIN psgc_barangays b ON po.delivery_barangay_code COLLATE utf8mb4_general_ci = b.barangay_code
          WHERE po.po_id = ?");
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
      if ($action === 'add' || $action === 'create') {
        $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO purchase_orders (po_id, po_number, supplier_id, property_id, po_date, expected_delivery, po_status, subtotal, tax_amount, shipping_fee, discount_amount, total_amount, amount_paid, balance, payment_status, payment_terms, delivery_address, delivery_region_code, delivery_province_code, delivery_city_code, delivery_barangay_code, notes) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $id, $data['po_number'], $data['supplier_id'], $data['property_id'],
          $data['po_date'] ?? date('Y-m-d'), $data['expected_delivery'] ?? null,
          $data['po_status'] ?? 'draft', $data['subtotal'] ?? 0,
          $data['tax_amount'] ?? 0, $data['shipping_fee'] ?? 0,
          $data['discount_amount'] ?? 0, $data['total_amount'] ?? 0,
          $data['amount_paid'] ?? 0, $data['balance'] ?? 0,
          $data['payment_status'] ?? 'unpaid', $data['payment_terms'] ?? null,
          $data['delivery_address'] ?? null,
          $data['delivery_region_code'] ?? null,
          $data['delivery_province_code'] ?? null,
          $data['delivery_city_code'] ?? null,
          $data['delivery_barangay_code'] ?? null,
          $data['notes'] ?? null
        ]);
        // Handle items from JSON body or items_json field
        $items = $data['items'] ?? [];
        if (empty($items) && !empty($data['items_json'])) {
          $items = json_decode($data['items_json'], true) ?? [];
        }
        foreach ($items as $item) {
          $pi = $pdo->prepare("INSERT INTO purchase_order_items (po_id, product_id, uom_id, ordered_qty, unit_cost, total_cost) VALUES (?,?,?,?,?,?)");
          $pi->execute([$id, $item['product_id'], $item['uom_id'], $item['quantity_ordered'], $item['unit_cost'], $item['total_cost']]);
        }
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Purchase order created', 'po_id' => $id]);

      } elseif ($action === 'edit' || $action === 'update') {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("UPDATE purchase_orders SET supplier_id=?, property_id=?, po_number=?, po_date=?, expected_delivery=?, po_status=?, payment_terms=?, delivery_address=?, delivery_region_code=?, delivery_province_code=?, delivery_city_code=?, delivery_barangay_code=?, notes=? WHERE po_id=?");
        $stmt->execute([
          $data['supplier_id'], $data['property_id'], $data['po_number'],
          $data['po_date'], $data['expected_delivery'] ?: null, $data['po_status'],
          $data['payment_terms'] ?? null, $data['delivery_address'] ?? null,
          $data['delivery_region_code'] ?? null,
          $data['delivery_province_code'] ?? null,
          $data['delivery_city_code'] ?? null,
          $data['delivery_barangay_code'] ?? null,
          $data['notes'] ?? null, $data['po_id']
        ]);
        // Handle items update
        $items = $data['items'] ?? [];
        if (empty($items) && !empty($data['items_json'])) {
          $items = json_decode($data['items_json'], true) ?? [];
        }
        if (!empty($items)) {
          // Delete existing items and re-insert
          $stmt = $pdo->prepare("DELETE FROM purchase_order_items WHERE po_id=?");
          $stmt->execute([$data['po_id']]);
          foreach ($items as $item) {
            $pi = $pdo->prepare("INSERT INTO purchase_order_items (po_id, product_id, uom_id, ordered_qty, unit_cost, total_cost) VALUES (?,?,?,?,?,?)");
            $pi->execute([$data['po_id'], $item['product_id'], $item['uom_id'], $item['quantity_ordered'], $item['unit_cost'], $item['total_cost']]);
          }
        }
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Purchase order updated']);

      } elseif ($action === 'update_status') {
        $stmt = $pdo->prepare("UPDATE purchase_orders SET po_status=? WHERE po_id=?");
        $stmt->execute([$data['po_status'], $data['po_id']]);
        echo json_encode(['success' => true, 'message' => 'PO status updated']);

      } elseif ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE purchase_orders SET po_status='approved', approved_by=?, approved_at=CURRENT_TIMESTAMP WHERE po_id=?");
        $stmt->execute([$_SESSION['user_id'] ?? null, $data['po_id']]);
        echo json_encode(['success' => true, 'message' => 'Purchase order approved']);

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
