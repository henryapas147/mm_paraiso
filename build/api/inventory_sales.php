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
          SELECT isale.*, p.property_name, pm.method_name as payment_method_name
          FROM inventory_sales isale
          LEFT JOIN properties p ON isale.property_id = p.property_id
          LEFT JOIN payment_methods pm ON isale.payment_method_id = pm.method_id
          ORDER BY isale.sale_date DESC, isale.created_at DESC
        ");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      } elseif ($action === 'related') {
        $properties = $pdo->query("SELECT property_id, property_name FROM properties WHERE is_active = 1 ORDER BY property_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $products = $pdo->query("
          SELECT p.product_id, p.product_name, p.product_code, p.selling_price, p.current_stock, p.uom_id, p.reorder_point, p.is_for_sale
          FROM products p
          WHERE p.is_active = 1 AND (p.is_for_sale = 1 OR p.selling_price > 0)
          ORDER BY p.product_name ASC
        ")->fetchAll(PDO::FETCH_ASSOC);
        $paymentMethods = $pdo->query("SELECT method_id, method_name FROM payment_methods WHERE is_active = 1 ORDER BY method_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['properties' => $properties, 'products' => $products, 'payment_methods' => $paymentMethods]]);
      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("
          SELECT isale.*, p.property_name, pm.method_name as payment_method_name
          FROM inventory_sales isale
          LEFT JOIN properties p ON isale.property_id = p.property_id
          LEFT JOIN payment_methods pm ON isale.payment_method_id = pm.method_id
          WHERE isale.sale_id = ?
        ");
        $stmt->execute([$_GET['id']]);
        $sale = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($sale) {
          $items = $pdo->prepare("
            SELECT isi.*, pr.product_name, pr.product_code, u.uom_name
            FROM inventory_sale_items isi
            INNER JOIN products pr ON isi.product_id = pr.product_id
            INNER JOIN units_of_measure u ON isi.uom_id = u.uom_id
            WHERE isi.sale_id = ?
          ");
          $items->execute([$sale['sale_id']]);
          $sale['items'] = $items->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode(['success' => true, 'data' => $sale]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $saleId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        
        $pdo->beginTransaction();
        
        // Create the sale record
        $stmt = $pdo->prepare("
          INSERT INTO inventory_sales (
            sale_id, sale_number, property_id, sale_date, sale_type,
            customer_name, subtotal, tax_amount, total_amount,
            payment_method_id, payment_status, notes, created_by
          ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");
        
        // Get payment_method_id from name or use provided ID
        $paymentMethodId = null;
        if (!empty($data['payment_method'])) {
          $pmStmt = $pdo->prepare("SELECT method_id FROM payment_methods WHERE method_name = ? OR method_id = ? LIMIT 1");
          $pmStmt->execute([$data['payment_method'], $data['payment_method']]);
          $pmResult = $pmStmt->fetch(PDO::FETCH_ASSOC);
          if ($pmResult) {
            $paymentMethodId = $pmResult['method_id'];
          }
        }
        
        $userId = $_SESSION['user_id'] ?? null;
        $stmt->execute([
          $saleId, $data['sale_number'], $data['property_id'] ?? null,
          $data['sale_date'] ?? date('Y-m-d'), $data['sale_type'] ?? 'pos',
          $data['customer_name'] ?? null, $data['subtotal'] ?? 0,
          $data['tax_amount'] ?? 0, $data['total_amount'] ?? 0,
          $paymentMethodId, $data['payment_status'] ?? 'paid',
          $data['notes'] ?? null, $userId
        ]);
        
        // Process each sale item
        foreach ($data['items'] ?? [] as $item) {
          $itemId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
            mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
          
          // Get product cost for margin calculation
          $costStmt = $pdo->prepare("SELECT unit_cost, current_stock FROM products WHERE product_id = ?");
          $costStmt->execute([$item['product_id']]);
          $productData = $costStmt->fetch(PDO::FETCH_ASSOC);
          $unitCost = $productData ? $productData['unit_cost'] : 0;
          $currentStock = $productData ? floatval($productData['current_stock']) : 0;
          $quantity = floatval($item['quantity']);
          
          // Check stock availability
          if ($currentStock < $quantity) {
            throw new Exception('Insufficient stock for product: ' . $item['product_name']);
          }
          
          // Create stock transaction for the sale
          $txnId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
            mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
          
          $newStock = $currentStock - $quantity;
          $stmtTxn = $pdo->prepare("
            INSERT INTO stock_transactions (
              txn_id, product_id, location_id, transaction_type, quantity,
              uom_id, unit_cost, stock_before, stock_after,
              reference_type, reference_id, reference_number,
              transaction_date, notes, created_by
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
          ");
          $stmtTxn->execute([
            $txnId, $item['product_id'], null, 'stock_out_sale',
            $quantity, $item['uom_id'], $unitCost,
            $currentStock, $newStock,
            'inventory_sales', $saleId, $data['sale_number'],
            $data['sale_date'] ?? date('Y-m-d'),
            'POS Sale - ' . $data['sale_number'], $userId
          ]);
          
          // Deduct stock from product
          $updateStock = $pdo->prepare("UPDATE products SET current_stock = current_stock - ? WHERE product_id = ?");
          $updateStock->execute([$quantity, $item['product_id']]);
          
          // Create sale item with transaction link
          $si = $pdo->prepare("
            INSERT INTO inventory_sale_items (
              sale_item_id, sale_id, product_id, uom_id, quantity,
              unit_price, unit_cost, total_price, txn_id
            ) VALUES (?,?,?,?,?,?,?,?,?)
          ");
          $si->execute([
            $itemId, $saleId, $item['product_id'], $item['uom_id'],
            $quantity, $item['unit_price'], $unitCost,
            $item['total_price'], $txnId
          ]);
        }
        
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Sale created successfully', 'sale_id' => $saleId]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("
          UPDATE inventory_sales SET
            sale_number=?, property_id=?, sale_type=?, customer_name=?,
            sale_date=?, subtotal=?, tax_amount=?, total_amount=?,
            payment_method_id=?, payment_status=?, notes=?
          WHERE sale_id=?
        ");
        $stmt->execute([
          $data['sale_number'], $data['property_id'] ?? null, $data['sale_type'],
          $data['customer_name'] ?? null, $data['sale_date'],
          $data['subtotal'] ?? 0, $data['tax_amount'] ?? 0, $data['total_amount'] ?? 0,
          $data['payment_method_id'] ?? null, $data['payment_status'],
          $data['notes'] ?? null, $data['sale_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Sale updated']);

      } elseif ($action === 'delete') {
        $pdo->beginTransaction();
        
        // Get all transaction IDs associated with this sale
        $txnStmt = $pdo->prepare("SELECT txn_id FROM inventory_sale_items WHERE sale_id = ?");
        $txnStmt->execute([$data['sale_id']]);
        $txns = $txnStmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Restore stock for each item
        $itemsStmt = $pdo->prepare("
          SELECT product_id, quantity FROM inventory_sale_items WHERE sale_id = ?
        ");
        $itemsStmt->execute([$data['sale_id']]);
        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($items as $item) {
          $restoreStmt = $pdo->prepare("
            UPDATE products SET current_stock = current_stock + ? WHERE product_id = ?
          ");
          $restoreStmt->execute([$item['quantity'], $item['product_id']]);
        }
        
        // Delete stock transactions
        if (!empty($txns)) {
          $placeholders = implode(',', array_fill(0, count($txns), '?'));
          $pdo->prepare("DELETE FROM stock_transactions WHERE txn_id IN ($placeholders)")->execute($txns);
        }
        
        // Delete sale items and sale
        $stmt = $pdo->prepare("DELETE FROM inventory_sale_items WHERE sale_id=?");
        $stmt->execute([$data['sale_id']]);
        $stmt = $pdo->prepare("DELETE FROM inventory_sales WHERE sale_id=?");
        $stmt->execute([$data['sale_id']]);
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Sale deleted and stock restored']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
