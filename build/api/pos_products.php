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
        // Get only products available for POS sale
        $stmt = $pdo->query("
          SELECT 
            p.product_id,
            p.product_code,
            p.barcode,
            p.product_name,
            p.brand,
            p.description,
            p.selling_price,
            p.current_stock,
            p.reorder_point,
            p.product_image_url,
            p.category_id,
            p.uom_id as base_uom_id,
            ic.category_name,
            ic.category_code,
            u.uom_code as base_uom_code,
            u.uom_name as base_uom_name
          FROM products p
          LEFT JOIN inventory_categories ic ON p.category_id = ic.category_id
          LEFT JOIN units_of_measure u ON p.uom_id = u.uom_id
          WHERE p.is_active = 1
            AND p.is_for_sale = 1
            AND p.selling_price > 0
          ORDER BY p.product_name ASC
        ");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch UOM conversions for each product
        foreach ($products as &$product) {
          $convStmt = $pdo->prepare("
            SELECT
              puc.conversion_id,
              puc.from_uom_id,
              puc.to_uom_id,
              puc.conversion_factor,
              puc.is_default,
              puc.alt_product_code,
              puc.barcode as conversion_barcode,
              puc.selling_price as conversion_selling_price,
              puc.pricing_mode,
              puc.markup_value,
              puc.last_po_cost,
              from_uom.uom_code as from_uom_code,
              from_uom.uom_name as from_uom_name,
              to_uom.uom_code as to_uom_code,
              to_uom.uom_name as to_uom_name
            FROM product_uom_conversions puc
            INNER JOIN units_of_measure from_uom ON puc.from_uom_id = from_uom.uom_id
            INNER JOIN units_of_measure to_uom ON puc.to_uom_id = to_uom.uom_id
            WHERE puc.product_id = ? AND puc.is_active = 1
            ORDER BY puc.is_default DESC
          ");
          $convStmt->execute([$product['product_id']]);
          $product['uom_conversions'] = $convStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode(['success' => true, 'data' => $products]);
      } elseif ($action === 'categories') {
        // Get only categories that have products for sale
        $stmt = $pdo->query("
          SELECT DISTINCT
            ic.category_id,
            ic.category_name,
            ic.category_code,
            ic.sort_order
          FROM inventory_categories ic
          INNER JOIN products p ON ic.category_id = p.category_id
          WHERE ic.is_active = 1
            AND p.is_active = 1
            AND p.is_for_sale = 1
            AND p.selling_price > 0
          ORDER BY ic.sort_order ASC, ic.category_name ASC
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
