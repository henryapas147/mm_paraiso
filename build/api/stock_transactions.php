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
          SELECT st.*, p.product_name, p.product_code, il.location_name, il.location_type,
                 prop.property_name, u.uom_name
          FROM stock_transactions st
          INNER JOIN products p ON st.product_id = p.product_id
          LEFT JOIN inventory_locations il ON st.location_id = il.location_id
          LEFT JOIN properties prop ON il.property_id = prop.property_id
          INNER JOIN units_of_measure u ON st.uom_id = u.uom_id
          ORDER BY st.transaction_date DESC, st.created_at DESC
          LIMIT 200
        ");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      } elseif ($action === 'related') {
        $products = $pdo->query("SELECT product_id, product_name, product_code FROM products WHERE is_active = 1 ORDER BY product_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $locations = $pdo->query("SELECT il.location_id, il.location_name, il.location_type, prop.property_name FROM inventory_locations il INNER JOIN properties prop ON il.property_id = prop.property_id WHERE il.is_active = 1 ORDER BY prop.property_name ASC, il.location_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $uoms = $pdo->query("SELECT uom_id, uom_name, uom_type FROM units_of_measure WHERE is_active = 1 ORDER BY uom_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['products' => $products, 'locations' => $locations, 'uoms' => $uoms]]);
      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT st.*, p.product_name, il.location_name, u.uom_name FROM stock_transactions st INNER JOIN products p ON st.product_id = p.product_id LEFT JOIN inventory_locations il ON st.location_id = il.location_id INNER JOIN units_of_measure u ON st.uom_id = u.uom_id WHERE st.txn_id = ?");
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        $stmt = $pdo->prepare("INSERT INTO stock_transactions (txn_id, product_id, location_id, uom_id, transaction_type, quantity, unit_cost, reference_type, reference_id, transaction_date, notes) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $id, $data['product_id'], $data['location_id'] ?: null, $data['uom_id'],
          $data['transaction_type'], $data['quantity'], $data['unit_cost'] ?? 0,
          $data['reference_type'] ?? null, $data['reference_id'] ?? null,
          $data['transaction_date'] ?? date('Y-m-d'), $data['notes'] ?? null
        ]);
        echo json_encode(['success' => true, 'message' => 'Transaction created', 'txn_id' => $id]);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM stock_transactions WHERE txn_id=?");
        $stmt->execute([$data['txn_id']]);
        echo json_encode(['success' => true, 'message' => 'Transaction deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
