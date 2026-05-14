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
          SELECT mi.*, fc.category_name, p.property_name
          FROM menu_items mi
          INNER JOIN food_categories fc ON mi.category_id = fc.category_id
          LEFT JOIN properties p ON mi.property_id = p.property_id
          ORDER BY mi.sort_order ASC, mi.item_name ASC
        ");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      } elseif ($action === 'related') {
        $categories = $pdo->query("SELECT category_id, category_name FROM food_categories WHERE is_active = 1 ORDER BY category_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $properties = $pdo->query("SELECT property_id, property_name FROM properties ORDER BY property_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['categories' => $categories, 'properties' => $properties]]);
      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT mi.*, fc.category_name, p.property_name FROM menu_items mi INNER JOIN food_categories fc ON mi.category_id = fc.category_id LEFT JOIN properties p ON mi.property_id = p.property_id WHERE mi.item_id = ?");
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
        $stmt = $pdo->prepare("INSERT INTO menu_items (item_id, property_id, category_id, item_code, item_name, description, price, cost_price, is_available, is_featured, sort_order, is_active, created_by) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $id, $data['property_id'] ?? null, $data['category_id'], $data['item_code'] ?? null,
          $data['item_name'], $data['description'] ?? null, $data['price'], $data['cost_price'] ?? 0,
          $data['is_available'] ?? 1, $data['is_featured'] ?? 0, $data['sort_order'] ?? 0,
          $data['is_active'] ?? 1, $data['created_by'] ?? null
        ]);
        echo json_encode(['success' => true, 'message' => 'Menu item created', 'item_id' => $id]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE menu_items SET property_id=?, category_id=?, item_code=?, item_name=?, description=?, price=?, cost_price=?, is_available=?, is_featured=?, sort_order=?, is_active=? WHERE item_id=?");
        $stmt->execute([
          $data['property_id'] ?? null, $data['category_id'], $data['item_code'] ?? null,
          $data['item_name'], $data['description'] ?? null, $data['price'], $data['cost_price'] ?? 0,
          $data['is_available'] ?? 1, $data['is_featured'] ?? 0, $data['sort_order'] ?? 0,
          $data['is_active'] ?? 1, $data['item_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Menu item updated']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM menu_items WHERE item_id=?");
        $stmt->execute([$data['item_id']]);
        echo json_encode(['success' => true, 'message' => 'Menu item deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
