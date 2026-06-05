<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      requirePermission('inventory.view');
      if ($action === 'list') {
        $productId = $_GET['product_id'] ?? null;
        $txnType   = $_GET['transaction_type'] ?? null;
        $refType   = $_GET['reference_type'] ?? null;

        $where  = ['1=1'];
        $params = [];
        if ($productId) { $where[] = 'st.product_id = ?'; $params[] = $productId; }
        if ($txnType)   { $where[] = 'st.transaction_type = ?'; $params[] = $txnType; }
        if ($refType)   { $where[] = 'st.reference_type = ?'; $params[] = $refType; }
        $whereClause = implode(' AND ', $where);

        $stmt = $pdo->prepare("
          SELECT st.txn_id AS transaction_id,
                 st.product_id, p.product_name, p.product_code,
                 st.transaction_type, st.quantity, st.uom_id, u.uom_name,
                 st.reference_type, st.reference_id,
                 st.stock_before, st.stock_after,
                 st.notes,
                 st.transaction_date,
                 st.created_at,
                 CONCAT(up.first_name, ' ', up.last_name) AS created_by_name,
                 il.location_name, il.location_type, prop.property_name
          FROM stock_transactions st
          LEFT JOIN products p ON st.product_id = p.product_id
          LEFT JOIN units_of_measure u ON st.uom_id = u.uom_id
          LEFT JOIN inventory_locations il ON st.location_id = il.location_id
          LEFT JOIN properties prop ON il.property_id = prop.property_id
          LEFT JOIN user_profiles up ON st.created_by = up.user_id
          WHERE $whereClause
          ORDER BY st.created_at DESC
          LIMIT 500
        ");
        $stmt->execute($params);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      } elseif ($action === 'related') {
        $products = $pdo->query("SELECT product_id, product_name, product_code FROM products WHERE is_active = 1 ORDER BY product_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $locations = $pdo->query("SELECT il.location_id, il.location_name, il.location_type, prop.property_name FROM inventory_locations il INNER JOIN properties prop ON il.property_id = prop.property_id WHERE il.is_active = 1 ORDER BY prop.property_name ASC, il.location_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $uoms = $pdo->query("SELECT uom_id, uom_name, uom_type FROM units_of_measure WHERE is_active = 1 ORDER BY uom_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['products' => $products, 'locations' => $locations, 'uoms' => $uoms]]);
      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("
          SELECT it.transaction_id AS transaction_id,
                 it.product_id, p.product_name, p.product_code,
                 it.transaction_type, it.quantity, it.uom_id, u.uom_name,
                 it.reference_type, it.reference_id,
                 it.stock_before, it.stock_after,
                 it.reason AS notes,
                 it.created_at AS transaction_date, it.created_at,
                 CONCAT(up.first_name, ' ', up.last_name) AS created_by_name
          FROM inventory_transactions it
          LEFT JOIN products p ON it.product_id = p.product_id
          LEFT JOIN units_of_measure u ON it.uom_id = u.uom_id
          LEFT JOIN user_profiles up ON it.created_by = up.user_id
          WHERE it.transaction_id = ?
        ");
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
      }
      break;

    case 'POST':
      requirePermission('inventory.create');
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
