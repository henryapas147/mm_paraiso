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
          SELECT
            psl.*,
            p.product_name,
            p.product_code,
            p.current_stock AS total_stock,
            p.reorder_point,
            il.location_name,
            il.location_type,
            prop.property_name
          FROM product_stock_per_location psl
          INNER JOIN products p ON psl.product_id = p.product_id
          INNER JOIN inventory_locations il ON psl.location_id = il.location_id
          INNER JOIN properties prop ON il.property_id = prop.property_id
          WHERE p.is_active = 1 AND il.is_active = 1
          ORDER BY prop.property_name ASC, il.location_name ASC, p.product_name ASC
        ");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

      } elseif ($action === 'related') {
        $products = $pdo->query("SELECT product_id, product_name, product_code FROM products WHERE is_active = 1 ORDER BY product_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $locations = $pdo->query("SELECT il.location_id, il.location_name, il.location_type, prop.property_name FROM inventory_locations il INNER JOIN properties prop ON il.property_id = prop.property_id WHERE il.is_active = 1 ORDER BY prop.property_name ASC, il.location_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['products' => $products, 'locations' => $locations]]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $check = $pdo->prepare("SELECT stock_loc_id FROM product_stock_per_location WHERE product_id = ? AND location_id = ?");
        $check->execute([$data['product_id'], $data['location_id']]);
        if ($check->fetch()) {
          echo json_encode(['success' => false, 'message' => 'A stock record for this product at this location already exists.']);
          break;
        }
        $stmt = $pdo->prepare("INSERT INTO product_stock_per_location (product_id, location_id, quantity, notes) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['product_id'], $data['location_id'], $data['quantity'], $data['notes'] ?? null]);
        echo json_encode(['success' => true, 'message' => 'Stock record created successfully']);

      } elseif ($action === 'adjust') {
        $type = $data['adjustment_type'] ?? 'add';
        if ($type === 'add') {
          $stmt = $pdo->prepare("UPDATE product_stock_per_location SET quantity = quantity + ?, notes = ? WHERE stock_loc_id = ?");
        } else {
          $stmt = $pdo->prepare("UPDATE product_stock_per_location SET quantity = GREATEST(0, quantity - ?), notes = ? WHERE stock_loc_id = ?");
        }
        $stmt->execute([$data['quantity'], $data['notes'] ?? null, $data['stock_loc_id']]);
        echo json_encode(['success' => true, 'message' => 'Stock adjusted successfully']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM product_stock_per_location WHERE stock_loc_id = ?");
        $stmt->execute([$data['stock_loc_id']]);
        echo json_encode(['success' => true, 'message' => 'Stock record deleted successfully']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
