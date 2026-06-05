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
        $products = $pdo->query("SELECT product_id, product_name, product_code, uom_id AS base_uom_id FROM products WHERE is_active = 1 ORDER BY product_name ASC")->fetchAll(PDO::FETCH_ASSOC);

        // Fetch UOM conversions for each product
        foreach ($products as &$product) {
          $convStmt = $pdo->prepare("
            SELECT puc.from_uom_id, puc.to_uom_id, puc.conversion_factor,
                   u_from.uom_name AS from_uom_name, u_to.uom_name AS to_uom_name
            FROM product_uom_conversions puc
            INNER JOIN units_of_measure u_from ON puc.from_uom_id = u_from.uom_id
            INNER JOIN units_of_measure u_to ON puc.to_uom_id = u_to.uom_id
            WHERE puc.product_id = ? AND puc.is_active = 1
          ");
          $convStmt->execute([$product['product_id']]);
          $product['uom_conversions'] = $convStmt->fetchAll(PDO::FETCH_ASSOC);
        }
        unset($product);

        $locations = $pdo->query("SELECT il.location_id, il.location_name, il.location_type, prop.property_name FROM inventory_locations il INNER JOIN properties prop ON il.property_id = prop.property_id WHERE il.is_active = 1 ORDER BY prop.property_name ASC, il.location_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $uoms = $pdo->query("SELECT uom_id, uom_name, uom_type FROM units_of_measure WHERE is_active = 1 ORDER BY uom_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['products' => $products, 'locations' => $locations, 'uoms' => $uoms]]);
      }
      break;

    case 'POST':
      requirePermission('inventory.create');
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $check = $pdo->prepare("SELECT stock_loc_id FROM product_stock_per_location WHERE product_id = ? AND location_id = ?");
        $check->execute([$data['product_id'], $data['location_id']]);
        if ($check->fetch()) {
          echo json_encode(['success' => false, 'message' => 'A stock record for this product at this location already exists.']);
          break;
        }

        // Get product's base UOM
        $prodStmt = $pdo->prepare("SELECT uom_id AS base_uom_id FROM products WHERE product_id = ?");
        $prodStmt->execute([$data['product_id']]);
        $product = $prodStmt->fetch(PDO::FETCH_ASSOC);
        $baseUomId = $product['base_uom_id'];
        $inputUomId = $data['uom_id'] ?? $baseUomId;
        $qty = floatval($data['quantity']);

        // Convert to base UOM if needed
        if ($inputUomId != $baseUomId) {
          $convStmt = $pdo->prepare("SELECT conversion_factor FROM product_uom_conversions WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ? AND is_active = 1");
          $convStmt->execute([$data['product_id'], $inputUomId, $baseUomId]);
          $conv = $convStmt->fetch(PDO::FETCH_ASSOC);
          if ($conv) {
            $qty = $qty * floatval($conv['conversion_factor']);
          }
        }

        $stmt = $pdo->prepare("INSERT INTO product_stock_per_location (product_id, location_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$data['product_id'], $data['location_id'], $qty]);
        echo json_encode(['success' => true, 'message' => 'Stock record created successfully']);

      } elseif ($action === 'adjust') {
        // Get product's base UOM
        $stockStmt = $pdo->prepare("SELECT psl.stock_loc_id, psl.product_id, p.uom_id AS base_uom_id FROM product_stock_per_location psl INNER JOIN products p ON psl.product_id = p.product_id WHERE psl.stock_loc_id = ?");
        $stockStmt->execute([$data['stock_loc_id']]);
        $stock = $stockStmt->fetch(PDO::FETCH_ASSOC);
        if (!$stock) {
          echo json_encode(['success' => false, 'message' => 'Stock record not found']);
          break;
        }

        $baseUomId = $stock['base_uom_id'];
        $inputUomId = $data['uom_id'] ?? $baseUomId;
        $qty = floatval($data['quantity']);

        // Convert to base UOM if needed
        if ($inputUomId != $baseUomId) {
          $convStmt = $pdo->prepare("SELECT conversion_factor FROM product_uom_conversions WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ? AND is_active = 1");
          $convStmt->execute([$stock['product_id'], $inputUomId, $baseUomId]);
          $conv = $convStmt->fetch(PDO::FETCH_ASSOC);
          if ($conv) {
            $qty = $qty * floatval($conv['conversion_factor']);
          }
        }

        $type = $data['adjustment_type'] ?? 'add';
        if ($type === 'add') {
          $stmt = $pdo->prepare("UPDATE product_stock_per_location SET quantity = quantity + ? WHERE stock_loc_id = ?");
        } else {
          $stmt = $pdo->prepare("UPDATE product_stock_per_location SET quantity = GREATEST(0, quantity - ?) WHERE stock_loc_id = ?");
        }
        $stmt->execute([$qty, $data['stock_loc_id']]);
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
