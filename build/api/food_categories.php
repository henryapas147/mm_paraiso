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
          SELECT fc.*, p.property_name
          FROM food_categories fc
          LEFT JOIN properties p ON fc.property_id = p.property_id
          ORDER BY fc.sort_order ASC, fc.category_name ASC
        ");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      } elseif ($action === 'related') {
        $properties = $pdo->query("SELECT property_id, property_name FROM properties ORDER BY property_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['properties' => $properties]]);
      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT fc.*, p.property_name FROM food_categories fc LEFT JOIN properties p ON fc.property_id = p.property_id WHERE fc.category_id = ?");
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $stmt = $pdo->prepare("INSERT INTO food_categories (property_id, category_name, description, icon_url, sort_order, is_active) VALUES (?,?,?,?,?,?)");
        $stmt->execute([
          $data['property_id'] ?? null, $data['category_name'], $data['description'] ?? null,
          $data['icon_url'] ?? null, $data['sort_order'] ?? 0, $data['is_active'] ?? 1
        ]);
        echo json_encode(['success' => true, 'message' => 'Category created', 'category_id' => $pdo->lastInsertId()]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE food_categories SET property_id=?, category_name=?, description=?, icon_url=?, sort_order=?, is_active=? WHERE category_id=?");
        $stmt->execute([
          $data['property_id'] ?? null, $data['category_name'], $data['description'] ?? null,
          $data['icon_url'] ?? null, $data['sort_order'] ?? 0, $data['is_active'] ?? 1, $data['category_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Category updated']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM food_categories WHERE category_id=?");
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
