<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
require_once '../services/NotificationService.php';
applyAuthMiddleware();
require_once 'inventory_functions.php';

use App\Services\NotificationService;

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        $stmt = $pdo->query("
          SELECT fo.*, p.property_name, r.reservation_id as res_reservation_id, r.reservation_code
          FROM food_orders fo
          LEFT JOIN properties p ON fo.property_id = p.property_id
          LEFT JOIN reservations r ON fo.reservation_id = r.reservation_id
          ORDER BY fo.created_at DESC
          LIMIT 200
        ");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
          $stmt = $pdo->prepare("SELECT foi.*, mi.item_name, mi.item_code FROM food_order_items foi INNER JOIN menu_items mi ON foi.item_id = mi.item_id WHERE foi.order_id = ?");
          $stmt->execute([$order['order_id']]);
          $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        unset($order);

        echo json_encode(['success' => true, 'data' => $orders]);

      } elseif ($action === 'related') {
        $properties = $pdo->query("SELECT property_id, property_name FROM properties ORDER BY property_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $reservations = $pdo->query("SELECT reservation_id, reservation_code, CONCAT(walkin_first_name, ' ', walkin_last_name) as display FROM reservations WHERE reservation_status IN ('confirmed', 'checked_in') ORDER BY reservation_code ASC")->fetchAll(PDO::FETCH_ASSOC);
        $menuItems = $pdo->query("SELECT item_id, item_name, item_code, price FROM menu_items WHERE is_active = 1 AND is_available = 1 ORDER BY item_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $paymentMethods = $pdo->query("SELECT method_id, method_label as method_name FROM payment_methods WHERE is_active = 1 ORDER BY method_label ASC")->fetchAll(PDO::FETCH_ASSOC);
        $diningTables = $pdo->query("SELECT table_id, property_id, table_number, table_name, capacity, location, is_occupied FROM dining_tables WHERE is_active = 1 ORDER BY table_number ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['properties' => $properties, 'reservations' => $reservations, 'menuItems' => $menuItems, 'paymentMethods' => $paymentMethods, 'diningTables' => $diningTables]]);

      } elseif ($action === 'my_orders') {
        // Get current user's customer info
        $userId = $_SESSION['user_id'] ?? null;
        $customerName = '';

        if ($userId) {
          $stmt = $pdo->prepare("SELECT first_name, last_name FROM user_profiles WHERE user_id = ?");
          $stmt->execute([$userId]);
          $profile = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($profile) {
            $customerName = trim($profile['first_name'] . ' ' . $profile['last_name']);
          }
        }

        // Get orders linked to customer's reservations or by customer name
        // Note: rooms are linked via reservation_rooms table
        $stmt = $pdo->prepare("
          SELECT DISTINCT fo.*, p.property_name, r.reservation_code,
                 rr.room_id, rm.room_number, rm.room_name
          FROM food_orders fo
          LEFT JOIN properties p ON fo.property_id = p.property_id
          LEFT JOIN reservations r ON fo.reservation_id = r.reservation_id
          LEFT JOIN reservation_rooms rr ON r.reservation_id = rr.reservation_id
          LEFT JOIN rooms rm ON rr.room_id = rm.room_id
          WHERE (r.customer_user_id = :user_id 
             OR fo.customer_name = :customer_name
             OR fo.order_id IN (
                 SELECT DISTINCT order_id FROM food_orders 
                 WHERE reservation_id IN (
                     SELECT reservation_id FROM reservations WHERE customer_user_id = :user_id2
                 )
             ))
          ORDER BY fo.created_at DESC
          LIMIT 100
        ");
        $stmt->execute([
          ':user_id' => $userId,
          ':customer_name' => $customerName,
          ':user_id2' => $userId
        ]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch items for each order
        foreach ($orders as &$order) {
          $stmt = $pdo->prepare("
            SELECT foi.*, mi.item_name, mi.item_code
            FROM food_order_items foi
            INNER JOIN menu_items mi ON foi.item_id = mi.item_id
            WHERE foi.order_id = ?
          ");
          $stmt->execute([$order['order_id']]);
          $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        unset($order);

        echo json_encode(['success' => true, 'data' => $orders]);

      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT fo.*, p.property_name, r.reservation_id as res_reservation_id, r.reservation_code FROM food_orders fo LEFT JOIN properties p ON fo.property_id = p.property_id LEFT JOIN reservations r ON fo.reservation_id = r.reservation_id WHERE fo.order_id = ?");
        $stmt->execute([$_GET['id']]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($order) {
          $stmt = $pdo->prepare("SELECT foi.*, mi.item_name, mi.item_code FROM food_order_items foi INNER JOIN menu_items mi ON foi.item_id = mi.item_id WHERE foi.order_id = ?");
          $stmt->execute([$order['order_id']]);
          $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode(['success' => true, 'data' => $order]);

      } elseif ($action === 'status_history' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("
          SELECT fosh.*, up.first_name, up.last_name
          FROM food_order_status_history fosh
          LEFT JOIN user_profiles up ON fosh.changed_by = up.user_id
          WHERE fosh.order_id = ?
          ORDER BY fosh.created_at ASC
        ");
        $stmt->execute([$_GET['id']]);
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $history]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        
        // Generate order number
        $year = date('Y');
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM food_orders WHERE order_number LIKE ?");
        $stmt->execute(["FO-{$year}%"]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'] + 1;
        $orderNumber = "FO-{$year}" . str_pad($count, 4, '0', STR_PAD_LEFT);

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO food_orders (order_id, property_id, reservation_id, order_number, order_type, table_number, room_id, customer_name, order_status, subtotal, discount_amount, tax_amount, total_amount, payment_status, payment_method_id, notes, served_by, created_by) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $id, $data['property_id'], $data['reservation_id'] ?? null, $orderNumber,
          $data['order_type'] ?? 'dine_in', $data['table_number'] ?? null, $data['room_id'] ?? null,
          $data['customer_name'] ?? null, $data['order_status'] ?? 'pending', $data['subtotal'] ?? 0,
          $data['discount_amount'] ?? 0, $data['tax_amount'] ?? 0, $data['total_amount'] ?? 0,
          $data['payment_status'] ?? 'unpaid', $data['payment_method_id'] ?? null, $data['notes'] ?? null,
          $data['served_by'] ?? null, $data['created_by'] ?? null
        ]);

        foreach ($data['items'] ?? [] as $item) {
          $itemId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
            mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
          $stmt = $pdo->prepare("INSERT INTO food_order_items (order_item_id, order_id, item_id, item_name, quantity, unit_price, total_price, special_request) VALUES (?,?,?,?,?,?,?,?)");
          $stmt->execute([
            $itemId, $id, $item['item_id'], $item['item_name'], $item['quantity'],
            $item['unit_price'], $item['total_price'], $item['special_request'] ?? null
          ]);
        }

        $pdo->commit();

        // Send notification to customer about food order
        try {
          $notificationService = new NotificationService($pdo);
          if (!empty($data['reservation_id'])) {
            // Get customer user ID from reservation
            $stmt = $pdo->prepare("SELECT customer_user_id FROM reservations WHERE reservation_id = ?");
            $stmt->execute([$data['reservation_id']]);
            $userId = $stmt->fetchColumn();
            
            if ($userId) {
              $notificationService->createNotification([
                'type' => 'system_alert',
                'title' => 'Food Order Placed',
                'body' => "Your food order #{$orderNumber} has been placed successfully.",
                'user_ids' => $userId,
                'property_id' => $data['property_id'],
                'reference_type' => 'food_order',
                'reference_id' => $id
              ]);
            }
          }
        } catch (Exception $notifError) {
          error_log("Failed to create notification for food order: " . $notifError->getMessage());
        }

        echo json_encode(['success' => true, 'message' => 'Order created', 'order_id' => $id, 'order_number' => $orderNumber]);

      } elseif ($action === 'update_status') {
        $pdo->beginTransaction();
        
        // Get current status
        $stmt = $pdo->prepare("SELECT order_status FROM food_orders WHERE order_id = ?");
        $stmt->execute([$data['order_id']]);
        $currentStatus = $stmt->fetchColumn();
        
        // Update status
        $stmt = $pdo->prepare("UPDATE food_orders SET order_status=? WHERE order_id=?");
        $stmt->execute([$data['order_status'], $data['order_id']]);
        
        // Log status change to history
        $historyId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        $stmt = $pdo->prepare("
          INSERT INTO food_order_status_history (history_id, order_id, old_status, new_status, changed_by, changed_by_type, change_reason)
          VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
          $historyId,
          $data['order_id'],
          $currentStatus,
          $data['order_status'],
          $_SESSION['user_id'] ?? null,
          'admin',
          $data['change_reason'] ?? null
        ]);
        
        // Deduct inventory when status changes to 'preparing' and hasn't been deducted yet
        if ($data['order_status'] === 'preparing' && $currentStatus !== 'preparing') {
          // Check if inventory already deducted for this order
          $stmt = $pdo->prepare("SELECT COUNT(*) FROM inventory_transactions WHERE reference_type = 'food_order' AND reference_id = ?");
          $stmt->execute([$data['order_id']]);
          $alreadyDeducted = $stmt->fetchColumn() > 0;
          
          if (!$alreadyDeducted) {
            // Get order items
            $stmt = $pdo->prepare("SELECT item_id, quantity FROM food_order_items WHERE order_id = ?");
            $stmt->execute([$data['order_id']]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Deduct inventory for each item
            $inventoryErrors = [];
            foreach ($items as $item) {
              $result = deductInventoryForOrder($pdo, $data['order_id'], $item['item_id'], $item['quantity'], $_SESSION['user_id'] ?? null);
              if (!$result['success']) {
                $inventoryErrors[] = $result['message'];
              }
            }
            
            if (!empty($inventoryErrors)) {
              $pdo->rollBack();
              echo json_encode(['success' => false, 'message' => 'Inventory deduction failed: ' . implode(', ', $inventoryErrors)]);
              exit;
            }
          }
        }
        
        $pdo->commit();

        // Send notification to customer about status update
        try {
          $notificationService = new NotificationService($pdo);
          
          // Get order details
          $stmt = $pdo->prepare("SELECT reservation_id, order_number FROM food_orders WHERE order_id = ?");
          $stmt->execute([$data['order_id']]);
          $order = $stmt->fetch(PDO::FETCH_ASSOC);
          
          if ($order && !empty($order['reservation_id'])) {
            // Get customer user ID from reservation
            $stmt = $pdo->prepare("SELECT customer_user_id FROM reservations WHERE reservation_id = ?");
            $stmt->execute([$order['reservation_id']]);
            $userId = $stmt->fetchColumn();
            
            if ($userId) {
              // Map status to notification message
              $statusMessages = [
                'pending' => 'Your food order is pending confirmation.',
                'confirmed' => 'Your food order has been confirmed.',
                'preparing' => 'Your food order is now being prepared.',
                'ready' => 'Your food order is ready for pickup/delivery!',
                'served' => 'Your food order has been served.',
                'completed' => 'Your food order has been completed.',
                'cancelled' => 'Your food order has been cancelled.'
              ];
              
              $message = $statusMessages[$data['order_status']] ?? "Your food order status has been updated to: {$data['order_status']}.";
              
              $notificationService->createNotification([
                'type' => 'system_alert',
                'title' => 'Food Order Status Update',
                'body' => "Order #{$order['order_number']}: {$message}",
                'user_ids' => $userId,
                'property_id' => null,
                'reference_type' => 'food_order',
                'reference_id' => $data['order_id']
              ]);
            }
          }
        } catch (Exception $notifError) {
          error_log("Failed to create notification for food order status update: " . $notifError->getMessage());
        }

        echo json_encode(['success' => true, 'message' => 'Order status updated']);

      } elseif ($action === 'update_payment') {
        $stmt = $pdo->prepare("UPDATE food_orders SET payment_status=?, payment_method_id=? WHERE order_id=?");
        $stmt->execute([$data['payment_status'], $data['payment_method_id'] ?? null, $data['order_id']]);

        // Create inventory_sales record when payment is completed or charged to room
        if ($data['payment_status'] === 'paid' || $data['payment_status'] === 'charged_to_room') {
          $pdo->beginTransaction();

          // Check if inventory sale already created for this order
          $checkStmt = $pdo->prepare("SELECT sale_id FROM inventory_sales WHERE reference_type = 'food_order' AND reference_id = ?");
          $checkStmt->execute([$data['order_id']]);
          if (!$checkStmt->fetch()) {
            // Get order details
            $orderStmt = $pdo->prepare("SELECT * FROM food_orders WHERE order_id = ?");
            $orderStmt->execute([$data['order_id']]);
            $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

            if ($order) {
              // Create inventory sale record
              $saleId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
                mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
                mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

              $saleStmt = $pdo->prepare("
                INSERT INTO inventory_sales (
                  sale_id, sale_number, property_id, sale_date, sale_type,
                  customer_name, subtotal, tax_amount, total_amount,
                  payment_method_id, payment_status, notes, created_by,
                  reference_type, reference_id
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
              ");
              $saleStmt->execute([
                $saleId,
                $order['order_number'],
                $order['property_id'] ?? null,
                $order['created_at'],
                $data['payment_status'] === 'charged_to_room' ? 'room_charge' : 'canteen',
                $order['customer_name'],
                $order['subtotal'],
                $order['tax_amount'],
                $order['total_amount'],
                $order['payment_method_id'],
                $data['payment_status'],
                'Food Order - ' . $order['order_number'],
                $_SESSION['user_id'] ?? null,
                'food_order',
                $order['order_id']
              ]);

              // If charged to room, create invoice line item
              if ($data['payment_status'] === 'charged_to_room' && $order['reservation_id']) {
                // Get or create invoice for reservation
                $invStmt = $pdo->prepare("SELECT invoice_id FROM invoices WHERE reservation_id = ? AND status = 'draft' LIMIT 1");
                $invStmt->execute([$order['reservation_id']]);
                $invoice = $invStmt->fetch(PDO::FETCH_ASSOC);

                if (!$invoice) {
                  // Create new draft invoice
                  $invoiceId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                    mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
                    mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
                    mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

                  $invNumber = 'INV-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

                  $createInvStmt = $pdo->prepare("
                    INSERT INTO invoices (
                      invoice_id, invoice_number, reservation_id, invoice_date,
                      subtotal, tax_amount, total_amount, status, generated_by
                    ) VALUES (?,?,?,?,?,?,?,?,?)
                  ");
                  $createInvStmt->execute([
                    $invoiceId, $invNumber, $order['reservation_id'], date('Y-m-d'),
                    $order['total_amount'], $order['tax_amount'], $order['total_amount'],
                    'draft', $_SESSION['user_id'] ?? null
                  ]);
                  $invoice = ['invoice_id' => $invoiceId];
                }

                // Create invoice line item
                $lineItemId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                  mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
                  mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
                  mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

                $lineItemStmt = $pdo->prepare("
                  INSERT INTO invoice_line_items (
                    line_item_id, invoice_id, description, item_type,
                    quantity, unit_price, total_price, reference_id
                  ) VALUES (?,?,?,?,?,?,?,?)
                ");
                $lineItemStmt->execute([
                  $lineItemId,
                  $invoice['invoice_id'],
                  'Food Order - ' . $order['order_number'],
                  'food_order',
                  1,
                  $order['total_amount'],
                  $order['total_amount'],
                  $order['order_id']
                ]);

                // Update invoice totals
                $updateInvStmt = $pdo->prepare("
                  UPDATE invoices
                  SET subtotal = (SELECT COALESCE(SUM(total_price), 0) FROM invoice_line_items WHERE invoice_id = ?),
                      total_amount = subtotal + tax_amount
                  WHERE invoice_id = ?
                ");
                $updateInvStmt->execute([$invoice['invoice_id'], $invoice['invoice_id']]);
              }

              // Get order items
              $itemsStmt = $pdo->prepare("SELECT * FROM food_order_items WHERE order_id = ?");
              $itemsStmt->execute([$data['order_id']]);
              $orderItems = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

              // Process each order item
              foreach ($orderItems as $orderItem) {
                // Get recipe for this menu item
                $recipeStmt = $pdo->prepare("SELECT * FROM recipes WHERE item_id = ? AND is_active = 1");
                $recipeStmt->execute([$orderItem['item_id']]);
                $recipe = $recipeStmt->fetch(PDO::FETCH_ASSOC);

                if ($recipe) {
                  // Get recipe ingredients
                  $ingStmt = $pdo->prepare("
                    SELECT ri.*, p.product_name, p.uom_id as base_uom_id
                    FROM recipe_ingredients ri
                    INNER JOIN products p ON ri.product_id = p.product_id
                    WHERE ri.recipe_id = ?
                  ");
                  $ingStmt->execute([$recipe['recipe_id']]);
                  $ingredients = $ingStmt->fetchAll(PDO::FETCH_ASSOC);

                  // Calculate quantity multiplier based on order quantity vs recipe yield
                  $qtyMultiplier = $orderItem['quantity'] / $recipe['yield_quantity'];

                  foreach ($ingredients as $ingredient) {
                    $itemId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                      mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
                      mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
                      mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

                    // Calculate actual quantity needed
                    $ingredientQty = $ingredient['quantity'] * $qtyMultiplier;
                    $inputUomId = intval($ingredient['uom_id']);
                    $baseUomId = intval($ingredient['base_uom_id']);

                    // Convert to base UOM
                    $quantityInBaseUom = $ingredientQty;
                    if ($inputUomId !== $baseUomId && $baseUomId !== null) {
                      $convStmt = $pdo->prepare("
                        SELECT conversion_factor FROM product_uom_conversions
                        WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ? AND is_active = 1
                        LIMIT 1
                      ");
                      $convStmt->execute([$ingredient['product_id'], $inputUomId, $baseUomId]);
                      $conv = $convStmt->fetch(PDO::FETCH_ASSOC);

                      if ($conv) {
                        $quantityInBaseUom = $ingredientQty * floatval($conv['conversion_factor']);
                      } else {
                        $convStmt = $pdo->prepare("
                          SELECT conversion_factor FROM product_uom_conversions
                          WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ? AND is_active = 1
                          LIMIT 1
                        ");
                        $convStmt->execute([$ingredient['product_id'], $baseUomId, $inputUomId]);
                        $conv = $convStmt->fetch(PDO::FETCH_ASSOC);

                        if ($conv) {
                          $quantityInBaseUom = $ingredientQty * floatval($conv['conversion_factor']);
                        }
                      }
                    }

                    // Get product cost
                    $costStmt = $pdo->prepare("SELECT unit_cost, current_stock FROM products WHERE product_id = ?");
                    $costStmt->execute([$ingredient['product_id']]);
                    $productData = $costStmt->fetch(PDO::FETCH_ASSOC);
                    $unitCost = $productData ? $productData['unit_cost'] : 0;
                    $currentStock = $productData ? floatval($productData['current_stock']) : 0;

                    // Create stock transaction
                    $txnId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                      mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
                      mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
                      mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

                    $newStock = $currentStock - $quantityInBaseUom;
                    $txnStmt = $pdo->prepare("
                      INSERT INTO stock_transactions (
                        txn_id, product_id, location_id, transaction_type, quantity,
                        uom_id, unit_cost, stock_before, stock_after,
                        reference_type, reference_id, reference_number,
                        transaction_date, notes, created_by
                      ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
                    ");
                    $txnStmt->execute([
                      $txnId, $ingredient['product_id'], null, 'stock_out_sale',
                      $quantityInBaseUom, $baseUomId, $unitCost,
                      $currentStock, $newStock,
                      'inventory_sales', $saleId, $order['order_number'],
                      $order['created_at'],
                      'Canteen Sale - ' . $order['order_number'], $_SESSION['user_id'] ?? null
                    ]);

                    // Deduct stock
                    $updateStock = $pdo->prepare("UPDATE products SET current_stock = current_stock - ? WHERE product_id = ?");
                    $updateStock->execute([$quantityInBaseUom, $ingredient['product_id']]);

                    // Create sale item
                    $saleItemStmt = $pdo->prepare("
                      INSERT INTO inventory_sale_items (
                        sale_item_id, sale_id, product_id, uom_id, quantity,
                        unit_price, unit_cost, total_price, txn_id
                      ) VALUES (?,?,?,?,?,?,?,?,?)
                    ");
                    $saleItemStmt->execute([
                      $itemId, $saleId, $ingredient['product_id'], $baseUomId,
                      $quantityInBaseUom, 0, $unitCost,
                      $quantityInBaseUom * $unitCost, $txnId
                    ]);
                  }
                }
              }
            }
          }
          $pdo->commit();
        }

        echo json_encode(['success' => true, 'message' => 'Payment status updated']);

      } elseif ($action === 'delete') {
        $pdo->beginTransaction();

        // Delete inventory_sales record if exists
        $saleStmt = $pdo->prepare("SELECT sale_id FROM inventory_sales WHERE reference_type = 'food_order' AND reference_id = ?");
        $saleStmt->execute([$data['order_id']]);
        $sale = $saleStmt->fetch(PDO::FETCH_ASSOC);

        if ($sale) {
          // Get sale items with UOM info for stock restoration
          $itemsStmt = $pdo->prepare("
            SELECT isi.product_id, isi.quantity, isi.uom_id, p.uom_id as base_uom_id
            FROM inventory_sale_items isi
            INNER JOIN products p ON isi.product_id = p.product_id
            WHERE isi.sale_id = ?
          ");
          $itemsStmt->execute([$sale['sale_id']]);
          $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

          // Restore stock for each item with UOM conversion
          foreach ($items as $item) {
            $quantity = floatval($item['quantity']);
            $inputUomId = intval($item['uom_id']);
            $baseUomId = intval($item['base_uom_id']);

            // Convert quantity to base UOM
            $quantityInBaseUom = $quantity;
            if ($inputUomId !== $baseUomId && $baseUomId !== null) {
              $convStmt = $pdo->prepare("
                SELECT conversion_factor FROM product_uom_conversions
                WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ? AND is_active = 1
                LIMIT 1
              ");
              $convStmt->execute([$item['product_id'], $inputUomId, $baseUomId]);
              $conv = $convStmt->fetch(PDO::FETCH_ASSOC);

              if ($conv) {
                $quantityInBaseUom = $quantity * floatval($conv['conversion_factor']);
              } else {
                $convStmt = $pdo->prepare("
                  SELECT conversion_factor FROM product_uom_conversions
                  WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ? AND is_active = 1
                  LIMIT 1
                ");
                $convStmt->execute([$item['product_id'], $baseUomId, $inputUomId]);
                $conv = $convStmt->fetch(PDO::FETCH_ASSOC);

                if ($conv) {
                  $quantityInBaseUom = $quantity * floatval($conv['conversion_factor']);
                }
              }
            }

            $restoreStmt = $pdo->prepare("
              UPDATE products SET current_stock = current_stock + ? WHERE product_id = ?
            ");
            $restoreStmt->execute([$quantityInBaseUom, $item['product_id']]);
          }

          // Get transaction IDs and delete stock transactions
          $txnStmt = $pdo->prepare("SELECT txn_id FROM inventory_sale_items WHERE sale_id = ?");
          $txnStmt->execute([$sale['sale_id']]);
          $txns = $txnStmt->fetchAll(PDO::FETCH_COLUMN);

          if (!empty($txns)) {
            $placeholders = implode(',', array_fill(0, count($txns), '?'));
            $pdo->prepare("DELETE FROM stock_transactions WHERE txn_id IN ($placeholders)")->execute($txns);
          }

          // Delete sale items and sale
          $pdo->prepare("DELETE FROM inventory_sale_items WHERE sale_id = ?")->execute([$sale['sale_id']]);
          $pdo->prepare("DELETE FROM inventory_sales WHERE sale_id = ?")->execute([$sale['sale_id']]);
        }

        // Rollback old inventory transactions (legacy)
        $stmt = $pdo->prepare("SELECT transaction_id FROM inventory_transactions WHERE reference_type = 'food_order' AND reference_id = ?");
        $stmt->execute([$data['order_id']]);
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($transactions as $trans) {
          $rollbackData = ['transaction_id' => $trans['transaction_id'], 'reason' => 'Order deleted'];

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../inventory_transactions.php?action=rollback');
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($rollbackData));
          curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'X-CSRF-Token: ' . ($data['csrf_token'] ?? '')]);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $response = curl_exec($ch);
          curl_close($ch);
        }

        $stmt = $pdo->prepare("DELETE FROM food_order_items WHERE order_id=?");
        $stmt->execute([$data['order_id']]);
        $stmt = $pdo->prepare("DELETE FROM food_orders WHERE order_id=?");
        $stmt->execute([$data['order_id']]);
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Order deleted and inventory restored']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
