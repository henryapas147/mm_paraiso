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
          SELECT st.*,
            from_loc.location_name AS from_location_name, from_loc.location_type AS from_location_type,
            to_loc.location_name AS to_location_name, to_loc.location_type AS to_location_type,
            from_prop.property_name AS from_property_name, to_prop.property_name AS to_property_name
          FROM stock_transfers st
          INNER JOIN inventory_locations from_loc ON st.from_location_id = from_loc.location_id
          INNER JOIN inventory_locations to_loc ON st.to_location_id = to_loc.location_id
          INNER JOIN properties from_prop ON from_loc.property_id = from_prop.property_id
          INNER JOIN properties to_prop ON to_loc.property_id = to_prop.property_id
          ORDER BY st.transfer_date DESC
        ");
        $transfers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($action === 'list') {
          foreach ($transfers as &$transfer) {
            $si = $pdo->prepare("SELECT sti.transfer_item_id, sti.transfer_id, sti.product_id, sti.uom_id, sti.quantity, p.product_name, p.product_code, u.uom_name FROM stock_transfer_items sti INNER JOIN products p ON sti.product_id = p.product_id INNER JOIN units_of_measure u ON sti.uom_id = u.uom_id WHERE sti.transfer_id = ?");
            $si->execute([$transfer['transfer_id']]);
            $transfer['items'] = $si->fetchAll(PDO::FETCH_ASSOC);
          }
          unset($transfer);
        }
        echo json_encode(['success' => true, 'data' => $transfers]);

      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT st.*, from_loc.location_name AS from_location_name, to_loc.location_name AS to_location_name FROM stock_transfers st INNER JOIN inventory_locations from_loc ON st.from_location_id = from_loc.location_id INNER JOIN inventory_locations to_loc ON st.to_location_id = to_loc.location_id WHERE st.transfer_id = ?");
        $stmt->execute([$_GET['id']]);
        $transfer = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($transfer) {
          $si = $pdo->prepare("SELECT sti.transfer_item_id, sti.transfer_id, sti.product_id, sti.uom_id, sti.quantity, p.product_name, u.uom_name FROM stock_transfer_items sti INNER JOIN products p ON sti.product_id = p.product_id INNER JOIN units_of_measure u ON sti.uom_id = u.uom_id WHERE sti.transfer_id = ?");
          $si->execute([$transfer['transfer_id']]);
          $transfer['items'] = $si->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode(['success' => true, 'data' => $transfer]);
      } elseif ($action === 'related') {
        $products = $pdo->query("SELECT product_id, product_name, product_code, uom_id AS base_uom_id, current_stock FROM products WHERE is_active = 1 ORDER BY product_name ASC")->fetchAll(PDO::FETCH_ASSOC);

        // Fetch UOM conversions and per-location stock for each product
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

          // Fetch per-location stock
          $stockStmt = $pdo->prepare("
            SELECT location_id, quantity
            FROM product_stock_per_location
            WHERE product_id = ?
          ");
          $stockStmt->execute([$product['product_id']]);
          $stockData = $stockStmt->fetchAll(PDO::FETCH_ASSOC);
          $product['location_stock'] = [];
          foreach ($stockData as $stock) {
            $product['location_stock'][$stock['location_id']] = $stock['quantity'];
          }
        }
        unset($product);

        $locations = $pdo->query("SELECT il.location_id, il.location_name, il.location_type, prop.property_name FROM inventory_locations il INNER JOIN properties prop ON il.property_id = prop.property_id WHERE il.is_active = 1 ORDER BY prop.property_name ASC, il.location_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $uoms = $pdo->query("SELECT uom_id, uom_name, uom_type FROM units_of_measure WHERE is_active = 1 ORDER BY uom_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['products' => $products, 'locations' => $locations, 'uoms' => $uoms]]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO stock_transfers (transfer_id, from_location_id, to_location_id, transfer_date, transfer_status, notes) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$id, $data['from_location_id'], $data['to_location_id'], $data['transfer_date'] ?? date('Y-m-d'), $data['transfer_status'] ?? 'pending', $data['notes'] ?? null]);
        foreach ($data['items'] ?? [] as $item) {
          $si = $pdo->prepare("INSERT INTO stock_transfer_items (transfer_id, product_id, uom_id, quantity) VALUES (?,?,?,?)");
          $si->execute([$id, $item['product_id'], $item['uom_id'], $item['quantity_requested'] ?? $item['quantity'] ?? 0]);
        }
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Transfer created', 'transfer_id' => $id]);

      } elseif ($action === 'edit') {
        $pdo->beginTransaction();
        $transferId = $data['transfer_id'];

        // Update transfer header
        $stmt = $pdo->prepare("UPDATE stock_transfers SET from_location_id=?, to_location_id=?, transfer_date=?, transfer_status=?, notes=? WHERE transfer_id=?");
        $stmt->execute([$data['from_location_id'], $data['to_location_id'], $data['transfer_date'], $data['transfer_status'], $data['notes'], $transferId]);

        // Delete existing items and re-insert
        $delStmt = $pdo->prepare("DELETE FROM stock_transfer_items WHERE transfer_id=?");
        $delStmt->execute([$transferId]);

        foreach ($data['items'] ?? [] as $item) {
          $si = $pdo->prepare("INSERT INTO stock_transfer_items (transfer_id, product_id, uom_id, quantity) VALUES (?,?,?,?)");
          $si->execute([$transferId, $item['product_id'], $item['uom_id'], $item['quantity_requested'] ?? $item['quantity'] ?? 0]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Transfer updated']);

      } elseif ($action === 'update_status') {
        $pdo->beginTransaction();
        $newStatus = $data['transfer_status'];
        $transferId = $data['transfer_id'];

        // Get current transfer status with row lock
        $tStmt = $pdo->prepare("SELECT transfer_status, from_location_id, to_location_id FROM stock_transfers WHERE transfer_id=? FOR UPDATE");
        $tStmt->execute([$transferId]);
        $transfer = $tStmt->fetch(PDO::FETCH_ASSOC);

        if (!$transfer) {
          $pdo->rollBack();
          echo json_encode(['success' => false, 'message' => 'Transfer not found']);
          break;
        }

        // Validate source and destination are different
        if ($transfer['from_location_id'] == $transfer['to_location_id']) {
          $pdo->rollBack();
          echo json_encode(['success' => false, 'message' => 'Source and destination locations cannot be the same']);
          break;
        }

        $stmt = $pdo->prepare("UPDATE stock_transfers SET transfer_status=? WHERE transfer_id=?");
        $stmt->execute([$newStatus, $transferId]);

        // When completing a transfer, update product stock and record stock_transactions
        if ($newStatus === 'completed' && $transfer['transfer_status'] !== 'completed') {
          $items = $pdo->prepare("
            SELECT sti.quantity, sti.uom_id, sti.product_id,
                   p.current_stock, p.stock_on_hand, p.uom_id AS base_uom_id
            FROM stock_transfer_items sti
            INNER JOIN products p ON sti.product_id = p.product_id
            WHERE sti.transfer_id = ?
          ");
          $items->execute([$transferId]);
          $transferItems = $items->fetchAll(PDO::FETCH_ASSOC);

          foreach ($transferItems as $item) {
            $qty = floatval($item['quantity']);
            $currentStock = floatval($item['current_stock'] ?? 0);
            $stockOnHand = floatval($item['stock_on_hand'] ?? 0);
            $itemUomId = intval($item['uom_id']);
            $baseUomId = intval($item['base_uom_id']);

            // Convert to base UOM if the transfer UOM differs from the product's base UOM
            $qtyToDeduct = $qty;
            if ($itemUomId !== $baseUomId) {
              // Try direct conversion first (from transfer UOM to base UOM)
              $convStmt = $pdo->prepare("
                SELECT conversion_factor FROM product_uom_conversions
                WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ? AND is_active = 1
              ");
              $convStmt->execute([$item['product_id'], $itemUomId, $baseUomId]);
              $conv = $convStmt->fetch(PDO::FETCH_ASSOC);

              if (!$conv) {
                // Try reverse: base → transfer UOM. Conversion factor means "1 base = N transfer"
                // So: transfer_qty * factor = base_qty (e.g. 2 packs * 3 = 6 pieces)
                $convStmt = $pdo->prepare("
                  SELECT conversion_factor FROM product_uom_conversions
                  WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ? AND is_active = 1
                ");
                $convStmt->execute([$item['product_id'], $baseUomId, $itemUomId]);
                $conv = $convStmt->fetch(PDO::FETCH_ASSOC);
                if ($conv && floatval($conv['conversion_factor']) > 0) {
                  $qtyToDeduct = $qty * floatval($conv['conversion_factor']);
                }
              } else {
                // Direct: transfer UOM → base UOM, multiply by factor
                $qtyToDeduct = $qty * floatval($conv['conversion_factor']);
              }
            }

            // Deduct from source product stock (using base UOM quantity)
            $newCurrentStock = $currentStock - $qtyToDeduct;
            $newStockOnHand = $stockOnHand - $qtyToDeduct;

            $pdo->prepare("UPDATE products SET current_stock=?, stock_on_hand=? WHERE product_id=?")
               ->execute([$newCurrentStock, $newStockOnHand, $item['product_id']]);

            // Update per-location stock: deduct from source
            $pdo->prepare("
              UPDATE product_stock_per_location
              SET quantity = quantity - ?
              WHERE product_id = ? AND location_id = ?
            ")->execute([$qtyToDeduct, $item['product_id'], $transfer['from_location_id']]);

            // Update per-location stock: add to destination (upsert)
            $pdo->prepare("
              INSERT INTO product_stock_per_location (product_id, location_id, quantity)
              VALUES (?, ?, ?)
              ON DUPLICATE KEY UPDATE quantity = quantity + ?
            ")->execute([$item['product_id'], $transfer['to_location_id'], $qtyToDeduct, $qtyToDeduct]);

            // Record stock_transactions for transfer_out (from source location)
            $txnId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
              mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
              mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
              mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
            $pdo->prepare("
              INSERT INTO stock_transactions (txn_id, product_id, location_id, uom_id, transaction_type, quantity, stock_before, stock_after, reference_type, reference_id, transaction_date, notes, created_by)
              VALUES (?,?,?,?,?,?,?,?,?,?,CURRENT_DATE,?,?)
            ")->execute([$txnId, $item['product_id'], $transfer['from_location_id'], $baseUomId, 'transfer_out', $qtyToDeduct, $currentStock, $newCurrentStock, 'transfer', $transferId, null, $_SESSION['user_id'] ?? null]);

            // Record stock_transactions for transfer_in (to destination location)
            $destStockBefore = 0;
            $destStockStmt = $pdo->prepare("SELECT quantity FROM product_stock_per_location WHERE product_id = ? AND location_id = ?");
            $destStockStmt->execute([$item['product_id'], $transfer['to_location_id']]);
            $destStockRow = $destStockStmt->fetch(PDO::FETCH_ASSOC);
            if ($destStockRow) {
              $destStockBefore = floatval($destStockRow['quantity']);
            }
            $destStockAfter = $destStockBefore + $qtyToDeduct;

            $txnInId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
              mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
              mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
              mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
            $pdo->prepare("
              INSERT INTO stock_transactions (txn_id, product_id, location_id, uom_id, transaction_type, quantity, stock_before, stock_after, reference_type, reference_id, transaction_date, notes, created_by)
              VALUES (?,?,?,?,?,?,?,?,?,?,CURRENT_DATE,?,?)
            ")->execute([$txnInId, $item['product_id'], $transfer['to_location_id'], $baseUomId, 'transfer_in', $qtyToDeduct, $destStockBefore, $destStockAfter, 'transfer', $transferId, null, $_SESSION['user_id'] ?? null]);

            // Record inventory_transactions for audit trail
            $invTxnId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
              mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
              mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
              mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
            $pdo->prepare("
              INSERT INTO inventory_transactions (transaction_id, product_id, transaction_type, quantity, uom_id, reference_type, reference_id, stock_before, stock_after, created_at, created_by)
              VALUES (?,?,?,?,?,?,?,?,?,NOW(),?)
            ")->execute([$invTxnId, $item['product_id'], 'deduct', $qtyToDeduct, $baseUomId, 'transfer', $transferId, $currentStock, $newCurrentStock, $_SESSION['user_id'] ?? null]);
          }
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Transfer status updated']);

      } elseif ($action === 'delete') {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("DELETE FROM stock_transfer_items WHERE transfer_id=?");
        $stmt->execute([$data['transfer_id']]);
        $stmt = $pdo->prepare("DELETE FROM stock_transfers WHERE transfer_id=?");
        $stmt->execute([$data['transfer_id']]);
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Transfer deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Throwable $e) {
  if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
  error_log('Stock transfers API error: ' . $e->getMessage());
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
