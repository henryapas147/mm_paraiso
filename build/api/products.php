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
          SELECT p.*, ic.category_name, ic.category_code, u.uom_code, u.uom_name, s.company_name AS supplier_name
          FROM products p
          LEFT JOIN inventory_categories ic ON p.category_id = ic.category_id
          LEFT JOIN units_of_measure u ON p.uom_id = u.uom_id
          LEFT JOIN suppliers s ON p.preferred_supplier_id = s.supplier_id
          WHERE p.is_active = 1
          ORDER BY p.product_name ASC
        ");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      } elseif ($action === 'related') {
        $cats = $pdo->query("SELECT category_id, category_name, category_code FROM inventory_categories WHERE is_active=1 ORDER BY category_name")->fetchAll(PDO::FETCH_ASSOC);
        $uoms = $pdo->query("SELECT uom_id, uom_name, uom_code FROM units_of_measure WHERE is_active=1 ORDER BY uom_name")->fetchAll(PDO::FETCH_ASSOC);
        $sups = $pdo->query("SELECT supplier_id, company_name FROM suppliers WHERE is_active=1 ORDER BY company_name")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'categories' => $cats, 'uoms' => $uoms, 'suppliers' => $sups]);
      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT p.*, ic.category_name, u.uom_name, s.company_name AS supplier_name FROM products p LEFT JOIN inventory_categories ic ON p.category_id = ic.category_id LEFT JOIN units_of_measure u ON p.uom_id = u.uom_id LEFT JOIN suppliers s ON p.preferred_supplier_id = s.supplier_id WHERE p.product_id = ?");
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
        $stmt = $pdo->prepare("INSERT INTO products (product_id, product_code, product_name, category_id, uom_id, product_type, brand, description, unit_cost, selling_price, reorder_point, minimum_stock, maximum_stock, preferred_supplier_id, is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $id, $data['product_code'], $data['product_name'], $data['category_id'] ?: null,
          $data['uom_id'] ?: null, $data['product_type'] ?? 'consumable', $data['brand'] ?? null,
          $data['description'] ?? null, $data['unit_cost'] ?? 0, $data['selling_price'] ?? 0,
          $data['reorder_point'] ?? 0, $data['minimum_stock'] ?? 0, $data['maximum_stock'] ?? null,
          $data['preferred_supplier_id'] ?: null, $data['is_active'] ?? 1
        ]);
        echo json_encode(['success' => true, 'message' => 'Product created', 'product_id' => $id]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE products SET product_code=?, product_name=?, category_id=?, uom_id=?, product_type=?, brand=?, description=?, unit_cost=?, selling_price=?, reorder_point=?, minimum_stock=?, maximum_stock=?, preferred_supplier_id=?, is_active=? WHERE product_id=?");
        $stmt->execute([
          $data['product_code'], $data['product_name'], $data['category_id'] ?: null,
          $data['uom_id'] ?: null, $data['product_type'] ?? 'consumable', $data['brand'] ?? null,
          $data['description'] ?? null, $data['unit_cost'] ?? 0, $data['selling_price'] ?? 0,
          $data['reorder_point'] ?? 0, $data['minimum_stock'] ?? 0, $data['maximum_stock'] ?? null,
          $data['preferred_supplier_id'] ?: null, $data['is_active'] ?? 1, $data['product_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Product updated']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("UPDATE products SET is_active=0 WHERE product_id=?");
        $stmt->execute([$data['product_id']]);
        echo json_encode(['success' => true, 'message' => 'Product deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
