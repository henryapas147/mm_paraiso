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
          SELECT psl.*, p.product_name, p.product_code, il.location_name, il.location_type,
                 prop.property_name, u.uom_name
          FROM product_stock_levels psl
          INNER JOIN products p ON psl.product_id = p.product_id
          INNER JOIN inventory_locations il ON psl.location_id = il.location_id
          INNER JOIN properties prop ON il.property_id = prop.property_id
          INNER JOIN units_of_measure u ON p.uom_id = u.uom_id
          WHERE p.is_active = 1
          ORDER BY prop.property_name ASC, il.location_name ASC, p.product_name ASC
        ");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
