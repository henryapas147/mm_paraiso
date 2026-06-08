<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

// Support both JSON and FormData
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (strpos($contentType, 'application/json') !== false) {
  $data = json_decode(file_get_contents('php://input'), true);
} else {
  $data = $_POST;
}

try {
  switch ($method) {
    case 'GET':
      requirePermission('inventory.view');
      if ($action === 'list') {
        $stmt = $pdo->query("
          SELECT po.*, s.company_name, p.property_name
          FROM purchase_orders po
          INNER JOIN suppliers s ON po.supplier_id = s.supplier_id
          INNER JOIN properties p ON po.property_id = p.property_id
          ORDER BY po.po_date DESC, po.po_number DESC
        ");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch all items and attach to their respective POs
        if (!empty($orders)) {
          $poIds = array_column($orders, 'po_id');
          $placeholders = implode(',', array_fill(0, count($poIds), '?'));
          $itemStmt = $pdo->prepare("
            SELECT poi.*, pr.product_name, pr.product_code, u.uom_name
            FROM purchase_order_items poi
            INNER JOIN products pr ON poi.product_id = pr.product_id
            INNER JOIN units_of_measure u ON poi.uom_id = u.uom_id
            WHERE poi.po_id IN ($placeholders)
          ");
          $itemStmt->execute($poIds);
          $allItems = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

          // Group items by po_id
          $itemsByPo = [];
          foreach ($allItems as $item) {
            $itemsByPo[$item['po_id']][] = $item;
          }

          // Attach items to each order
          foreach ($orders as &$order) {
            $order['items'] = $itemsByPo[$order['po_id']] ?? [];
          }
          unset($order);
        }

        echo json_encode(['success' => true, 'data' => $orders]);
      } elseif ($action === 'related') {
        $suppliers = $pdo->query("SELECT supplier_id, company_name, supplier_code, payment_terms FROM suppliers WHERE is_active = 1 ORDER BY company_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $properties = $pdo->query("SELECT property_id, property_name FROM properties ORDER BY property_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        // Fetch products for item selection
        $products = $pdo->query("
          SELECT p.product_id, p.product_name, p.product_code, p.uom_id, u.uom_name 
          FROM products p 
          LEFT JOIN units_of_measure u ON p.uom_id = u.uom_id 
          WHERE p.is_active = 1 
          ORDER BY p.product_name ASC
        ") ->fetchAll(PDO::FETCH_ASSOC);
        $uoms = $pdo->query("SELECT uom_id, uom_code, uom_name FROM units_of_measure WHERE is_active = 1 ORDER BY uom_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['suppliers' => $suppliers, 'properties' => $properties, 'products' => $products, 'uoms' => $uoms]]);
      } elseif ($action === 'next_number') {
        $year = $_GET['year'] ?? date('Y');
        // Get the highest PO number for this year
        $stmt = $pdo->prepare("SELECT po_number FROM purchase_orders WHERE po_number LIKE ? ORDER BY po_number DESC LIMIT 1");
        $stmt->execute(["PO-{$year}-%"]);
        $last = $stmt->fetch(PDO::FETCH_ASSOC);

        $nextNum = 1;
        if ($last) {
          // Extract the number from format PO-2024-000001
          $parts = explode('-', $last['po_number']);
          if (count($parts) >= 3) {
            $lastNum = intval(end($parts));
            $nextNum = $lastNum + 1;
          }
        }
        $poNumber = sprintf("PO-%s-%06d", $year, $nextNum);
        echo json_encode(['success' => true, 'po_number' => $poNumber]);
      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT po.*, s.company_name, p.property_name,
          r.region_name, pr.province_name, cm.city_municipality_name, b.barangay_name
          FROM purchase_orders po
          INNER JOIN suppliers s ON po.supplier_id = s.supplier_id
          INNER JOIN properties p ON po.property_id = p.property_id
          LEFT JOIN psgc_regions r ON po.delivery_region_code COLLATE utf8mb4_general_ci = r.region_code
          LEFT JOIN psgc_provinces pr ON po.delivery_province_code COLLATE utf8mb4_general_ci = pr.province_code
          LEFT JOIN psgc_cities_municipalities cm ON po.delivery_city_code COLLATE utf8mb4_general_ci = cm.city_municipality_code
          LEFT JOIN psgc_barangays b ON po.delivery_barangay_code COLLATE utf8mb4_general_ci = b.barangay_code
          WHERE po.po_id = ?");
        $stmt->execute([$_GET['id']]);
        $po = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($po) {
          $items = $pdo->prepare("SELECT poi.*, pr.product_name, pr.product_code, u.uom_name FROM purchase_order_items poi INNER JOIN products pr ON poi.product_id = pr.product_id INNER JOIN units_of_measure u ON poi.uom_id = u.uom_id WHERE poi.po_id = ?");
          $items->execute([$po['po_id']]);
          $po['items'] = $items->fetchAll(PDO::FETCH_ASSOC);

          // Fetch payment history
          $payments = $pdo->prepare("SELECT pp.*, pm.method_label, pm.method_type FROM po_payments pp LEFT JOIN payment_methods pm ON pp.method_id = pm.method_id WHERE pp.po_id = ? ORDER BY pp.payment_date DESC, pp.created_at DESC");
          $payments->execute([$po['po_id']]);
          $po['payments'] = $payments->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode(['success' => true, 'data' => $po]);
      }
      break;

    case 'POST':
      requirePermission('inventory.create');
      if ($action === 'add' || $action === 'create') {
        $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        $pdo->beginTransaction();
        $totalAmount = floatval($data['total_amount'] ?? 0);
        $amountPaid = floatval($data['amount_paid'] ?? 0);
        $balance = $totalAmount - $amountPaid;
        $paymentStatus = $balance <= 0 ? 'paid' : ($amountPaid > 0 ? 'partial' : 'unpaid');

        $stmt = $pdo->prepare("INSERT INTO purchase_orders (po_id, po_number, supplier_id, property_id, po_date, expected_delivery, po_status, subtotal, tax_amount, shipping_fee, discount_amount, total_amount, amount_paid, balance, payment_status, payment_terms, delivery_address, delivery_region_code, delivery_province_code, delivery_city_code, delivery_barangay_code, notes) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $id, $data['po_number'], $data['supplier_id'], $data['property_id'],
          $data['po_date'] ?? date('Y-m-d'), $data['expected_delivery'] ?? null,
          $data['po_status'] ?? 'draft', $data['subtotal'] ?? 0,
          $data['tax_amount'] ?? 0, $data['shipping_fee'] ?? 0,
          $data['discount_amount'] ?? 0, $totalAmount,
          $amountPaid, $balance, $paymentStatus,
          $data['payment_terms'] ?? null,
          $data['delivery_address'] ?? null,
          $data['delivery_region_code'] ?? null,
          $data['delivery_province_code'] ?? null,
          $data['delivery_city_code'] ?? null,
          $data['delivery_barangay_code'] ?? null,
          $data['notes'] ?? null
        ]);
        // Handle items from JSON body or items_json field
        $items = $data['items'] ?? [];
        if (empty($items) && !empty($data['items_json'])) {
          $items = json_decode($data['items_json'], true) ?? [];
        }
        foreach ($items as $item) {
          $orderedQty = $item['ordered_qty'] ?? $item['quantity_ordered'] ?? 0;
          $pi = $pdo->prepare("INSERT INTO purchase_order_items (po_id, product_id, uom_id, ordered_qty, unit_cost, total_cost) VALUES (?,?,?,?,?,?)");
          $pi->execute([$id, $item['product_id'], $item['uom_id'], $orderedQty, $item['unit_cost'], $item['total_cost']]);
        }
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Purchase order created', 'po_id' => $id]);

      } elseif ($action === 'edit' || $action === 'update') {
        $pdo->beginTransaction();
        $totalAmount = floatval($data['total_amount'] ?? 0);
        $amountPaid = floatval($data['amount_paid'] ?? 0);
        $balance = $totalAmount - $amountPaid;
        $paymentStatus = $balance <= 0 ? 'paid' : ($amountPaid > 0 ? 'partial' : 'unpaid');

        $stmt = $pdo->prepare("UPDATE purchase_orders SET supplier_id=?, property_id=?, po_number=?, po_date=?, expected_delivery=?, po_status=?, subtotal=?, tax_amount=?, shipping_fee=?, discount_amount=?, total_amount=?, amount_paid=?, balance=?, payment_status=?, payment_terms=?, delivery_address=?, delivery_region_code=?, delivery_province_code=?, delivery_city_code=?, delivery_barangay_code=?, notes=? WHERE po_id=?");
        $stmt->execute([
          $data['supplier_id'], $data['property_id'], $data['po_number'],
          $data['po_date'], $data['expected_delivery'] ?: null, $data['po_status'],
          $data['subtotal'] ?? 0, $data['tax_amount'] ?? 0, $data['shipping_fee'] ?? 0,
          $data['discount_amount'] ?? 0, $totalAmount, $amountPaid, $balance, $paymentStatus,
          $data['payment_terms'] ?? null, $data['delivery_address'] ?? null,
          $data['delivery_region_code'] ?? null,
          $data['delivery_province_code'] ?? null,
          $data['delivery_city_code'] ?? null,
          $data['delivery_barangay_code'] ?? null,
          $data['notes'] ?? null, $data['po_id']
        ]);
        // Handle items update
        $items = $data['items'] ?? [];
        if (empty($items) && !empty($data['items_json'])) {
          $items = json_decode($data['items_json'], true) ?? [];
        }
        if (!empty($items)) {
          // Delete existing items and re-insert
          $stmt = $pdo->prepare("DELETE FROM purchase_order_items WHERE po_id=?");
          $stmt->execute([$data['po_id']]);
          foreach ($items as $item) {
            $orderedQty = $item['ordered_qty'] ?? $item['quantity_ordered'] ?? 0;
            $pi = $pdo->prepare("INSERT INTO purchase_order_items (po_id, product_id, uom_id, ordered_qty, unit_cost, total_cost) VALUES (?,?,?,?,?,?)");
            $pi->execute([$data['po_id'], $item['product_id'], $item['uom_id'], $orderedQty, $item['unit_cost'], $item['total_cost']]);
          }
        }
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Purchase order updated']);

      } elseif ($action === 'update_status') {
        $stmt = $pdo->prepare("UPDATE purchase_orders SET po_status=? WHERE po_id=?");
        $stmt->execute([$data['po_status'], $data['po_id']]);
        echo json_encode(['success' => true, 'message' => 'PO status updated']);

      } elseif ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE purchase_orders SET po_status='approved', approved_by=?, approved_at=CURRENT_TIMESTAMP WHERE po_id=?");
        $stmt->execute([$_SESSION['user_id'] ?? null, $data['po_id']]);
        echo json_encode(['success' => true, 'message' => 'Purchase order approved']);

      } elseif ($action === 'receive') {
        $pdo->beginTransaction();
        $poId = $data['po_id'];
        $items = $data['items'] ?? [];
        if (empty($items) && !empty($data['items_json'])) {
          $items = json_decode($data['items_json'], true) ?? [];
        }

        $allFullyReceived = true;
        $anyReceived = false;

        foreach ($items as $item) {
          $poItemId = $item['po_item_id'];
          $receivedQty = floatval($item['received_qty'] ?? 0);
          $orderedQty = floatval($item['ordered_qty'] ?? 0);

          // Update item received quantity and status
          if ($receivedQty >= $orderedQty) {
            $itemStatus = 'fully_received';
          } elseif ($receivedQty > 0) {
            $itemStatus = 'partially_received';
            $allFullyReceived = false;
          } else {
            $itemStatus = 'pending';
            $allFullyReceived = false;
          }

          if ($receivedQty > 0) {
            $anyReceived = true;
          }

          $stmt = $pdo->prepare("UPDATE purchase_order_items SET received_qty=?, item_status=? WHERE po_item_id=?");
          $stmt->execute([$receivedQty, $itemStatus, $poItemId]);

          // Create inventory transaction for received quantity
          if ($receivedQty > 0) {
            $txnId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
              mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
              mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
              mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

            // Get current stock and product base UOM
            $stockStmt = $pdo->prepare("SELECT current_stock, stock_on_hand, uom_id FROM products WHERE product_id=?");
            $stockStmt->execute([$item['product_id']]);
            $product = $stockStmt->fetch(PDO::FETCH_ASSOC);
            $currentStock = $product['current_stock'] ?: 0;
            $stockOnHand = $product['stock_on_hand'] ?: 0;
            $baseUomId = $product['uom_id'];
            $itemUomId = $item['uom_id'];

            // Convert received quantity to base UOM if different
            $qtyToAdd = $receivedQty;
            if ($itemUomId != $baseUomId) {
              // Try direct conversion first (item UOM -> base UOM)
              $convStmt = $pdo->prepare("
                SELECT conversion_factor FROM product_uom_conversions
                WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ? AND is_active = 1
              ");
              $convStmt->execute([$item['product_id'], $itemUomId, $baseUomId]);
              $conversion = $convStmt->fetch(PDO::FETCH_ASSOC);
              if ($conversion) {
                $qtyToAdd = $receivedQty * floatval($conversion['conversion_factor']);
              } else {
                // Try reverse conversion (base UOM -> item UOM)
                // If conversion exists as base->item, we need to multiply by the factor
                // Example: 12 bottles = 1 carton, to convert 3 cartons to bottles: 3 * 12 = 36
                $convStmt = $pdo->prepare("
                  SELECT conversion_factor FROM product_uom_conversions
                  WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ? AND is_active = 1
                ");
                $convStmt->execute([$item['product_id'], $baseUomId, $itemUomId]);
                $conversion = $convStmt->fetch(PDO::FETCH_ASSOC);
                if ($conversion && floatval($conversion['conversion_factor']) > 0) {
                  $qtyToAdd = $receivedQty * floatval($conversion['conversion_factor']);
                }
              }
            }

            $newCurrentStock = $currentStock + $qtyToAdd;
            $newStockOnHand = $stockOnHand + $qtyToAdd;

            $txnStmt = $pdo->prepare("INSERT INTO inventory_transactions (transaction_id, product_id, transaction_type, quantity, uom_id, reference_type, reference_id, reason, stock_before, stock_after, created_by) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $txnStmt->execute([
              $txnId,
              $item['product_id'],
              'add',
              $qtyToAdd,
              $baseUomId,
              'purchase',
              $poId,
              'PO receiving: ' . ($data['notes'] ?? 'No notes'),
              $currentStock,
              $newCurrentStock,
              $_SESSION['user_id'] ?? null
            ]);

            // Update product stock (both columns to calculated values)
            $updateStock = $pdo->prepare("UPDATE products SET current_stock=?, stock_on_hand=? WHERE product_id=?");
            $updateStock->execute([$newCurrentStock, $newStockOnHand, $item['product_id']]);
          }
        }

        // Update PO status based on receiving progress
        if ($allFullyReceived && $anyReceived) {
          $poStatus = 'fully_received';
        } elseif ($anyReceived) {
          $poStatus = 'partially_received';
        } else {
          $poStatus = 'approved'; // No items received, keep as approved
        }

        $stmt = $pdo->prepare("UPDATE purchase_orders SET po_status=?, actual_delivery=CURRENT_TIMESTAMP WHERE po_id=?");
        $stmt->execute([$poStatus, $poId]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Items received successfully']);

      } elseif ($action === 'payment') {
        $pdo->beginTransaction();
        $poId = $data['po_id'];
        $amount = floatval($data['amount'] ?? 0);
        $methodId = $data['method_id'] ?? null;
        $referenceNumber = $data['reference_number'] ?? null;
        $paymentDate = $data['payment_date'] ?? date('Y-m-d');
        $paymentDetails = $data['payment_details'] ?? null;
        $notes = $data['notes'] ?? null;

        if ($amount <= 0) {
          echo json_encode(['success' => false, 'message' => 'Payment amount must be greater than 0']);
          exit;
        }

        // Get current PO totals
        $stmt = $pdo->prepare("SELECT total_amount, amount_paid FROM purchase_orders WHERE po_id=?");
        $stmt->execute([$poId]);
        $po = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$po) {
          echo json_encode(['success' => false, 'message' => 'Purchase order not found']);
          exit;
        }

        $newAmountPaid = $po['amount_paid'] + $amount;
        $newBalance = $po['total_amount'] - $newAmountPaid;

        // Determine payment status
        if ($newBalance <= 0) {
          $paymentStatus = 'paid';
          $newBalance = 0;
        } elseif ($newAmountPaid > 0) {
          $paymentStatus = 'partial';
        } else {
          $paymentStatus = 'unpaid';
        }

        // Create payment record
        $paymentId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

        $stmt = $pdo->prepare("INSERT INTO po_payments (po_payment_id, po_id, method_id, amount, reference_number, payment_date, payment_details, notes, processed_by) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$paymentId, $poId, $methodId, $amount, $referenceNumber, $paymentDate, $paymentDetails, $notes, $_SESSION['user_id'] ?? null]);

        // Update PO payment totals
        $stmt = $pdo->prepare("UPDATE purchase_orders SET amount_paid=?, balance=?, payment_status=? WHERE po_id=?");
        $stmt->execute([$newAmountPaid, $newBalance, $paymentStatus, $poId]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Payment recorded successfully']);

      } elseif ($action === 'delete') {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("DELETE FROM purchase_order_items WHERE po_id=?");
        $stmt->execute([$data['po_id']]);
        $stmt = $pdo->prepare("DELETE FROM purchase_orders WHERE po_id=?");
        $stmt->execute([$data['po_id']]);
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Purchase order deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
