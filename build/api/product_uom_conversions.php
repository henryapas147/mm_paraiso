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
        
        $sql = "SELECT puc.*, 
                       p.product_name, p.product_code, p.uom_id as base_uom_id, p.selling_price as base_selling_price,
                       base_uom.uom_code as base_uom_code, base_uom.uom_name as base_uom_name,
                       from_uom.uom_code as from_uom_code, from_uom.uom_name as from_uom_name,
                       to_uom.uom_code as to_uom_code, to_uom.uom_name as to_uom_name
                FROM product_uom_conversions puc
                INNER JOIN products p ON puc.product_id = p.product_id
                INNER JOIN units_of_measure base_uom ON p.uom_id = base_uom.uom_id
                INNER JOIN units_of_measure from_uom ON puc.from_uom_id = from_uom.uom_id
                INNER JOIN units_of_measure to_uom ON puc.to_uom_id = to_uom.uom_id
                WHERE puc.is_active = 1";
        $params = [];
        
        if ($productId) {
          $sql .= " AND puc.product_id = ?";
          $params[] = $productId;
        }
        
        $sql .= " ORDER BY p.product_name, puc.is_default DESC, from_uom.uom_code";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'data' => $data]);
        
      } elseif ($action === 'convert' && isset($_GET['product_id']) && isset($_GET['from_uom_id']) && isset($_GET['to_uom_id']) && isset($_GET['quantity'])) {
        // Convert quantity from one UOM to another
        $productId = $_GET['product_id'];
        $fromUomId = $_GET['from_uom_id'];
        $toUomId = $_GET['to_uom_id'];
        $quantity = floatval($_GET['quantity']);

        // Check if direct conversion exists
        $stmt = $pdo->prepare("
          SELECT conversion_factor
          FROM product_uom_conversions
          WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ? AND is_active = 1
        ");
        $stmt->execute([$productId, $fromUomId, $toUomId]);
        $conversion = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($conversion) {
          $convertedQty = $quantity * floatval($conversion['conversion_factor']);
          echo json_encode(['success' => true, 'converted_quantity' => $convertedQty, 'conversion_factor' => $conversion['conversion_factor']]);
        } else {
          // Try reverse conversion (1/to_factor)
          $stmt = $pdo->prepare("
            SELECT conversion_factor
            FROM product_uom_conversions
            WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ? AND is_active = 1
          ");
          $stmt->execute([$productId, $toUomId, $fromUomId]);
          $reverseConversion = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($reverseConversion) {
            $convertedQty = $quantity / floatval($reverseConversion['conversion_factor']);
            echo json_encode(['success' => true, 'converted_quantity' => $convertedQty, 'conversion_factor' => 1 / floatval($reverseConversion['conversion_factor'])]);
          } else {
            echo json_encode(['success' => false, 'message' => 'No conversion found for these UOMs']);
          }
        }
      } elseif ($action === 'last_po_cost' && isset($_GET['product_id']) && isset($_GET['uom_id'])) {
        // Get last PO cost for a specific product and UOM
        $productId = $_GET['product_id'];
        $uomId = $_GET['uom_id'];

        $stmt = $pdo->prepare("
          SELECT poi.unit_cost, po.po_date, po.po_number
          FROM purchase_order_items poi
          INNER JOIN purchase_orders po ON poi.po_id = po.po_id
          WHERE poi.product_id = ? AND poi.uom_id = ?
          ORDER BY po.po_date DESC, po.po_number DESC
          LIMIT 1
        ");
        $stmt->execute([$productId, $uomId]);
        $lastPo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($lastPo) {
          echo json_encode([
            'success' => true,
            'data' => [
              'unit_cost' => $lastPo['unit_cost'],
              'po_date' => $lastPo['po_date'],
              'po_number' => $lastPo['po_number']
            ]
          ]);
        } else {
          echo json_encode(['success' => false, 'message' => 'No purchase order found for this product and UOM']);
        }
      }
      break;

    case 'POST':
      requirePermission('inventory.edit');
      $data = json_decode(file_get_contents('php://input'), true);
      
      if ($action === 'create') {
        // Calculate selling price based on pricing mode
        $sellingPrice = null;
        $calculatedPrice = null;
        if (isset($data['pricing_mode']) && $data['pricing_mode'] !== 'fixed_price') {
            $lastPoCost = isset($data['last_po_cost']) ? floatval($data['last_po_cost']) : 0;
            $markupValue = isset($data['markup_value']) ? floatval($data['markup_value']) : 0;
            $conversionFactor = floatval($data['conversion_factor']);
            
            if ($data['pricing_mode'] === 'percentage_markup' && $lastPoCost > 0) {
                $calculatedPrice = ($lastPoCost * $conversionFactor) * (1 + ($markupValue / 100));
            } elseif ($data['pricing_mode'] === 'fixed_markup') {
                $calculatedPrice = ($lastPoCost * $conversionFactor) + $markupValue;
            }
            $sellingPrice = $calculatedPrice;
        } elseif (isset($data['selling_price'])) {
            $sellingPrice = floatval($data['selling_price']);
        }
        
        // Check if conversion already exists
        $checkStmt = $pdo->prepare("
          SELECT conversion_id FROM product_uom_conversions 
          WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ?
        ");
        $checkStmt->execute([$data['product_id'], $data['from_uom_id'], $data['to_uom_id']]);
        $existing = $checkStmt->fetchColumn();
        
        if ($existing) {
          // Update existing
          $stmt = $pdo->prepare("
            UPDATE product_uom_conversions SET
              conversion_factor = ?,
              is_default = ?,
              is_active = 1,
              alt_product_code = ?,
              barcode = ?,
              selling_price = ?,
              pricing_mode = ?,
              markup_value = ?,
              last_po_cost = ?,
              calculated_selling_price = ?,
              updated_at = NOW()
            WHERE conversion_id = ?
          ");
          $stmt->execute([
            $data['conversion_factor'],
            $data['is_default'] ?? 0,
            $data['alt_product_code'] ?? null,
            $data['barcode'] ?? null,
            $sellingPrice,
            $data['pricing_mode'] ?? 'fixed_price',
            $data['markup_value'] ?? null,
            $data['last_po_cost'] ?? null,
            $calculatedPrice,
            $existing
          ]);
          
          echo json_encode(['success' => true, 'message' => 'Conversion updated successfully', 'conversion_id' => $existing]);
        } else {
          // Create new
          $stmt = $pdo->prepare("
            INSERT INTO product_uom_conversions 
            (product_id, from_uom_id, to_uom_id, conversion_factor, is_default, is_active, 
             alt_product_code, barcode, selling_price, pricing_mode, markup_value, last_po_cost, 
             calculated_selling_price, created_by, created_at)
            VALUES (?, ?, ?, ?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
          ");
          $stmt->execute([
            $data['product_id'],
            $data['from_uom_id'],
            $data['to_uom_id'],
            $data['conversion_factor'],
            $data['is_default'] ?? 0,
            $data['alt_product_code'] ?? null,
            $data['barcode'] ?? null,
            $sellingPrice,
            $data['pricing_mode'] ?? 'fixed_price',
            $data['markup_value'] ?? null,
            $data['last_po_cost'] ?? null,
            $calculatedPrice,
            $_SESSION['user_id'] ?? null
          ]);
          
          $conversionId = $pdo->lastInsertId();
          echo json_encode(['success' => true, 'message' => 'Conversion created successfully', 'conversion_id' => $conversionId]);
        }
        
      } elseif ($action === 'update') {
        // Calculate selling price based on pricing mode
        $sellingPrice = null;
        $calculatedPrice = null;
        if (isset($data['pricing_mode']) && $data['pricing_mode'] !== 'fixed_price') {
            $lastPoCost = isset($data['last_po_cost']) ? floatval($data['last_po_cost']) : 0;
            $markupValue = isset($data['markup_value']) ? floatval($data['markup_value']) : 0;
            $conversionFactor = floatval($data['conversion_factor'] ?? 1);
            
            if ($data['pricing_mode'] === 'percentage_markup' && $lastPoCost > 0) {
                $calculatedPrice = ($lastPoCost * $conversionFactor) * (1 + ($markupValue / 100));
            } elseif ($data['pricing_mode'] === 'fixed_markup') {
                $calculatedPrice = ($lastPoCost * $conversionFactor) + $markupValue;
            }
            $sellingPrice = $calculatedPrice;
        } elseif (isset($data['selling_price'])) {
            $sellingPrice = floatval($data['selling_price']);
        }
        
        $stmt = $pdo->prepare("
          UPDATE product_uom_conversions SET
            conversion_factor = ?,
            is_default = ?,
            alt_product_code = ?,
            barcode = ?,
            selling_price = ?,
            pricing_mode = ?,
            markup_value = ?,
            last_po_cost = ?,
            calculated_selling_price = ?,
            updated_at = NOW()
          WHERE conversion_id = ?
        ");
        $stmt->execute([
          $data['conversion_factor'],
          $data['is_default'] ?? 0,
          $data['alt_product_code'] ?? null,
          $data['barcode'] ?? null,
          $sellingPrice,
          $data['pricing_mode'] ?? 'fixed_price',
          $data['markup_value'] ?? null,
          $data['last_po_cost'] ?? null,
          $calculatedPrice,
          $data['conversion_id']
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Conversion updated successfully']);
        
      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("
          UPDATE product_uom_conversions SET
            is_active = 0,
            updated_at = NOW()
          WHERE conversion_id = ?
        ");
        $stmt->execute([$data['conversion_id']]);
        
        echo json_encode(['success' => true, 'message' => 'Conversion deleted successfully']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
