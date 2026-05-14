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
          SELECT isale.*, p.property_name
          FROM inventory_sales isale
          INNER JOIN properties p ON isale.property_id = p.property_id
          ORDER BY isale.sale_date DESC, isale.sale_number DESC
        ");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      } elseif ($action === 'related') {
        $properties = $pdo->query("SELECT property_id, property_name FROM properties WHERE is_active = 1 ORDER BY property_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $products = $pdo->query("SELECT product_id, product_name, product_code, selling_price FROM products WHERE is_active = 1 ORDER BY product_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['properties' => $properties, 'products' => $products]]);
      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT isale.*, p.property_name FROM inventory_sales isale INNER JOIN properties p ON isale.property_id = p.property_id WHERE isale.sale_id = ?");
        $stmt->execute([$_GET['id']]);
        $sale = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($sale) {
          $items = $pdo->prepare("SELECT isi.*, pr.product_name, pr.product_code, u.uom_name FROM inventory_sale_items isi INNER JOIN products pr ON isi.product_id = pr.product_id INNER JOIN units_of_measure u ON isi.uom_id = u.uom_id WHERE isi.sale_id = ?");
          $items->execute([$sale['sale_id']]);
          $sale['items'] = $items->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode(['success' => true, 'data' => $sale]);
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
        $stmt = $pdo->prepare("INSERT INTO inventory_sales (sale_id, sale_number, property_id, sale_date, sale_type, customer_name, subtotal, tax_amount, total_amount, payment_method, sale_status, notes) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $id, $data['sale_number'], $data['property_id'], $data['sale_date'] ?? date('Y-m-d'),
          $data['sale_type'] ?? 'pos', $data['customer_name'] ?? null,
          $data['subtotal'] ?? 0, $data['tax_amount'] ?? 0, $data['total_amount'] ?? 0,
          $data['payment_method'] ?? null, $data['sale_status'] ?? 'completed', $data['notes'] ?? null
        ]);
        foreach ($data['items'] ?? [] as $item) {
          $itemId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
            mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
          $si = $pdo->prepare("INSERT INTO inventory_sale_items (sale_item_id, sale_id, product_id, uom_id, quantity, unit_price, total_price) VALUES (?,?,?,?,?,?,?)");
          $si->execute([$itemId, $id, $item['product_id'], $item['uom_id'], $item['quantity'], $item['unit_price'], $item['total_price']]);
        }
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Sale created', 'sale_id' => $id]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE inventory_sales SET sale_number=?, property_id=?, sale_type=?, customer_name=?, sale_date=?, total_amount=?, payment_status=?, notes=? WHERE sale_id=?");
        $stmt->execute([
          $data['sale_number'], $data['property_id'], $data['sale_type'],
          $data['customer_name'] ?? null, $data['sale_date'],
          $data['total_amount'], $data['payment_status'],
          $data['notes'] ?? null, $data['sale_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Sale updated']);

      } elseif ($action === 'delete') {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("DELETE FROM inventory_sale_items WHERE sale_id=?");
        $stmt->execute([$data['sale_id']]);
        $stmt = $pdo->prepare("DELETE FROM inventory_sales WHERE sale_id=?");
        $stmt->execute([$data['sale_id']]);
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Sale deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
