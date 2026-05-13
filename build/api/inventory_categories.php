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
          SELECT ic.*, parent.category_name AS parent_name
          FROM inventory_categories ic
          LEFT JOIN inventory_categories parent ON ic.parent_id = parent.category_id
          WHERE ic.is_active = 1
          ORDER BY ic.sort_order ASC, ic.category_name ASC
        ");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM inventory_categories WHERE category_id = ?");
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
        $stmt = $pdo->prepare("INSERT INTO inventory_categories (category_id, category_code, category_name, parent_id, description, sort_order, is_active) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$id, $data['category_code'], $data['category_name'], $data['parent_id'] ?: null, $data['description'] ?? null, $data['sort_order'] ?? 0, $data['is_active'] ?? 1]);
        echo json_encode(['success' => true, 'message' => 'Category created', 'category_id' => $id]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE inventory_categories SET category_code=?, category_name=?, parent_id=?, description=?, sort_order=?, is_active=? WHERE category_id=?");
        $stmt->execute([$data['category_code'], $data['category_name'], $data['parent_id'] ?: null, $data['description'] ?? null, $data['sort_order'] ?? 0, $data['is_active'] ?? 1, $data['category_id']]);
        echo json_encode(['success' => true, 'message' => 'Category updated']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("UPDATE inventory_categories SET is_active=0 WHERE category_id=?");
        $stmt->execute([$data['category_id']]);
        echo json_encode(['success' => true, 'message' => 'Category deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
