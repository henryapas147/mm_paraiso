<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();
require_once 'inventory_functions.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$userId = $_SESSION['user_id'] ?? null;

// ── GET REQUESTS ─────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  requirePermission('inventory.view');
  // List transactions with optional filters
  if ($action === 'list') {
    $productId = $_GET['product_id'] ?? null;
    $transactionType = $_GET['transaction_type'] ?? null;
    $referenceType = $_GET['reference_type'] ?? null;
    $referenceId = $_GET['reference_id'] ?? null;
    $limit = $_GET['limit'] ?? 50;
    $offset = $_GET['offset'] ?? 0;

    $where = ['1=1'];
    $params = [];

    if ($productId) {
      $where[] = 'it.product_id = ?';
      $params[] = $productId;
    }
    if ($transactionType) {
      $where[] = 'it.transaction_type = ?';
      $params[] = $transactionType;
    }
    if ($referenceType) {
      $where[] = 'it.reference_type = ?';
      $params[] = $referenceType;
    }
    if ($referenceId) {
      $where[] = 'it.reference_id = ?';
      $params[] = $referenceId;
    }

    $whereClause = implode(' AND ', $where);

    $stmt = $pdo->prepare("
      SELECT it.*,
             p.product_name,
             u.uom_code,
             u.uom_name,
             CONCAT(up.first_name, ' ', up.last_name) as created_by_name
      FROM inventory_transactions it
      LEFT JOIN products p ON it.product_id = p.product_id
      LEFT JOIN units_of_measure u ON it.uom_id = u.uom_id
      LEFT JOIN user_profiles up ON it.created_by = up.user_id
      WHERE $whereClause
      ORDER BY it.created_at DESC
      LIMIT ? OFFSET ?
    ");
    $params[] = $limit;
    $params[] = $offset;
    $stmt->execute($params);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total count
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM inventory_transactions it WHERE $whereClause");
    $countParams = array_slice($params, 0, -2);
    $countStmt->execute($countParams);
    $total = $countStmt->fetchColumn();

    echo json_encode(['success' => true, 'data' => $transactions, 'total' => $total]);
  }

  // Get transaction by ID
  elseif ($action === 'get') {
    $transactionId = $_GET['transaction_id'] ?? null;
    if (!$transactionId) {
      echo json_encode(['success' => false, 'message' => 'Transaction ID required']);
      exit;
    }

    $stmt = $pdo->prepare("
      SELECT it.*,
             p.product_name,
             u.uom_code,
             u.uom_name,
             CONCAT(up.first_name, ' ', up.last_name) as created_by_name
      FROM inventory_transactions it
      LEFT JOIN products p ON it.product_id = p.product_id
      LEFT JOIN units_of_measure u ON it.uom_id = u.uom_id
      LEFT JOIN user_profiles up ON it.created_by = up.user_id
      WHERE it.transaction_id = ?
    ");
    $stmt->execute([$transactionId]);
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($transaction) {
      echo json_encode(['success' => true, 'data' => $transaction]);
    } else {
      echo json_encode(['success' => false, 'message' => 'Transaction not found']);
    }
  }

  // Get product stock status
  elseif ($action === 'stock_status') {
    $productId = $_GET['product_id'] ?? null;
    if (!$productId) {
      echo json_encode(['success' => false, 'message' => 'Product ID required']);
      exit;
    }

    $stmt = $pdo->prepare("
      SELECT p.product_id, p.product_name, p.stock_on_hand, p.low_stock_threshold,
             p.uom_id, u.uom_code, u.uom_name
      FROM products p
      LEFT JOIN units_of_measure u ON p.uom_id = u.uom_id
      WHERE p.product_id = ?
    ");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
      $status = 'ok';
      if ($product['stock_on_hand'] <= 0) {
        $status = 'out';
      } elseif ($product['stock_on_hand'] <= $product['low_stock_threshold']) {
        $status = 'low';
      }
      $product['stock_status'] = $status;
      echo json_encode(['success' => true, 'data' => $product]);
    } else {
      echo json_encode(['success' => false, 'message' => 'Product not found']);
    }
  }

  // Check stock availability for recipe
  elseif ($action === 'check_recipe_stock') {
    $itemId = $_GET['item_id'] ?? null;
    $quantity = $_GET['quantity'] ?? 1;

    if (!$itemId) {
      echo json_encode(['success' => false, 'message' => 'Menu item ID required']);
      exit;
    }

    // Get recipe ingredients
    $stmt = $pdo->prepare("
      SELECT ri.product_id, ri.quantity, ri.uom_id, p.product_name, p.stock_on_hand, p.uom_id as base_uom_id,
             u.uom_code as ingredient_uom_code, bu.uom_code as base_uom_code
      FROM recipe_ingredients ri
      JOIN products p ON ri.product_id = p.product_id
      LEFT JOIN units_of_measure u ON ri.uom_id = u.uom_id
      LEFT JOIN units_of_measure bu ON p.uom_id = bu.uom_id
      WHERE ri.recipe_id = (SELECT recipe_id FROM recipes WHERE item_id = ?)
    ");
    $stmt->execute([$itemId]);
    $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stockIssues = [];
    $allAvailable = true;

    foreach ($ingredients as $ing) {
      $requiredQty = $ing['quantity'] * $quantity;
      $stockBefore = $ing['stock_on_hand'];
      $baseUomId = $ing['base_uom_id'];

      // Convert to base UOM if needed
      if ($ing['uom_id'] != $baseUomId) {
        $convStmt = $pdo->prepare("
          SELECT conversion_factor FROM product_uom_conversions
          WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ?
        ");
        $convStmt->execute([$ing['product_id'], $ing['uom_id'], $baseUomId]);
        $conv = $convStmt->fetch(PDO::FETCH_ASSOC);
        if ($conv) {
          $requiredQty = $requiredQty * $conv['conversion_factor'];
        }
      }

      if ($stockBefore < $requiredQty) {
        $allAvailable = false;
        $stockIssues[] = [
          'product_name' => $ing['product_name'],
          'required' => $requiredQty,
          'available' => $stockBefore,
          'uom' => $ing['base_uom_code'] ?? 'units'
        ];
      }
    }

    echo json_encode(['success' => true, 'available' => $allAvailable, 'issues' => $stockIssues]);
  }

  // Get low stock count for dashboard
  elseif ($action === 'low_stock_count') {
    $stmt = $pdo->prepare("
      SELECT COUNT(*) as count
      FROM products
      WHERE stock_on_hand <= low_stock_threshold
      AND is_active = 1
    ");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'count' => (int)$result['count']]);
  }

  else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
  }
}

// ── POST REQUESTS ────────────────────────────────────────────────────────────
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  requirePermission('inventory.create');
  $data = json_decode(file_get_contents('php://input'), true);

  // Manual stock adjustment (add or deduct)
  if ($action === 'adjust') {
    $productId = $data['product_id'] ?? null;
    $quantity = $data['quantity'] ?? null;
    $transactionType = $data['transaction_type'] ?? 'add'; // 'add' or 'deduct'
    $reason = $data['reason'] ?? 'Manual adjustment';

    if (!$productId || $quantity === null) {
      echo json_encode(['success' => false, 'message' => 'Product ID and quantity required']);
      exit;
    }

    if (!in_array($transactionType, ['add', 'deduct'])) {
      echo json_encode(['success' => false, 'message' => 'Invalid transaction type']);
      exit;
    }

    $pdo->beginTransaction();

    try {
      // Get current stock and product UOM
      $stmt = $pdo->prepare("SELECT current_stock, stock_on_hand, uom_id FROM products WHERE product_id = ?");
      $stmt->execute([$productId]);
      $product = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$product) {
        throw new Exception('Product not found');
      }

      $currentStockBefore = $product['current_stock'];
      $stockOnHandBefore = $product['stock_on_hand'];
      $uomId = $product['uom_id'];

      // Calculate new stock
      if ($transactionType === 'add') {
        $currentStockAfter = $currentStockBefore + $quantity;
        $stockOnHandAfter = $stockOnHandBefore + $quantity;
      } else {
        $currentStockAfter = $currentStockBefore - $quantity;
        $stockOnHandAfter = $stockOnHandBefore - $quantity;
        if ($stockOnHandAfter < 0) {
          throw new Exception('Insufficient stock for deduction');
        }
      }

      // Generate UUID for transaction
      $transactionId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
        mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

      // Update product stock (both columns)
      $updateStmt = $pdo->prepare("UPDATE products SET current_stock=?, stock_on_hand=? WHERE product_id = ?");
      $updateStmt->execute([$currentStockAfter, $stockOnHandAfter, $productId]);

      // Record transaction
      $transStmt = $pdo->prepare("
        INSERT INTO inventory_transactions
        (transaction_id, product_id, transaction_type, quantity, uom_id, reference_type, reason, stock_before, stock_after, created_by)
        VALUES (?, ?, ?, ?, ?, 'manual_adjustment', ?, ?, ?, ?)
      ");
      $transStmt->execute([$transactionId, $productId, $transactionType, $quantity, $uomId, $reason, $currentStockBefore, $currentStockAfter, $userId]);

      $pdo->commit();
      echo json_encode(['success' => true, 'message' => 'Stock adjusted successfully', 'transaction_id' => $transactionId]);
    } catch (Exception $e) {
      $pdo->rollBack();
      echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
  }

  // Deduct inventory for food order
  elseif ($action === 'deduct_order') {
    $orderId = $data['order_id'] ?? null;
    $itemId = $data['item_id'] ?? null;
    $quantity = $data['quantity'] ?? 1;

    if (!$itemId || !$quantity) {
      echo json_encode(['success' => false, 'message' => 'Menu item ID and quantity required']);
      exit;
    }

    $pdo->beginTransaction();

    try {
      // Get recipe ingredients
      $stmt = $pdo->prepare("
        SELECT ri.product_id, ri.quantity, ri.uom_id, p.current_stock, p.stock_on_hand, p.uom_id as base_uom_id
        FROM recipe_ingredients ri
        JOIN products p ON ri.product_id = p.product_id
        WHERE ri.recipe_id = (SELECT recipe_id FROM recipes WHERE item_id = ?)
      ");
      $stmt->execute([$itemId]);
      $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if (empty($ingredients)) {
        throw new Exception('No recipe found for this menu item');
      }

      foreach ($ingredients as $ing) {
        $requiredQty = $ing['quantity'] * $quantity;
        $currentStockBefore = $ing['current_stock'];
        $stockOnHandBefore = $ing['stock_on_hand'];
        $baseUomId = $ing['base_uom_id'];

        // Handle NULL stock
        if ($currentStockBefore === null) $currentStockBefore = 0;
        if ($stockOnHandBefore === null) $stockOnHandBefore = 0;

        // Convert to base UOM if needed
        if ($ing['uom_id'] != $baseUomId) {
          $convStmt = $pdo->prepare("
            SELECT conversion_factor FROM product_uom_conversions
            WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ?
          ");
          $convStmt->execute([$ing['product_id'], $ing['uom_id'], $baseUomId]);
          $conv = $convStmt->fetch(PDO::FETCH_ASSOC);
          if ($conv) {
            $requiredQty = $requiredQty * $conv['conversion_factor'];
          }
        }

        $currentStockAfter = $currentStockBefore - $requiredQty;
        $stockOnHandAfter = $stockOnHandBefore - $requiredQty;
        if ($stockOnHandAfter < 0) {
          throw new Exception('Insufficient stock for product: ' . $ing['product_id']);
        }

        // Generate UUID for transaction
        $transactionId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

        // Update product stock (both columns)
        $updateStmt = $pdo->prepare("UPDATE products SET current_stock=?, stock_on_hand=? WHERE product_id = ?");
        $updateStmt->execute([$currentStockAfter, $stockOnHandAfter, $ing['product_id']]);

        // Record transaction
        $transStmt = $pdo->prepare("
          INSERT INTO inventory_transactions
          (transaction_id, product_id, transaction_type, quantity, uom_id, reference_type, reference_id, stock_before, stock_after, created_by)
          VALUES (?, ?, 'deduct', ?, ?, 'food_order', ?, ?, ?, ?)
        ");
        $transStmt->execute([$transactionId, $ing['product_id'], $requiredQty, $baseUomId, $orderId, $currentStockBefore, $currentStockAfter, $_SESSION['user_id'] ?? null]);
      }

      $pdo->commit();
      echo json_encode(['success' => true, 'message' => 'Inventory deducted successfully']);
    } catch (Exception $e) {
      $pdo->rollBack();
      echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
  }

  // Rollback transaction (add stock back)
  elseif ($action === 'rollback') {
    $transactionId = $data['transaction_id'] ?? null;
    $reason = $data['reason'] ?? 'Order cancelled';

    if (!$transactionId) {
      echo json_encode(['success' => false, 'message' => 'Transaction ID required']);
      exit;
    }

    $pdo->beginTransaction();

    try {
      // Get original transaction
      $stmt = $pdo->prepare("SELECT * FROM inventory_transactions WHERE transaction_id = ?");
      $stmt->execute([$transactionId]);
      $originalTrans = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$originalTrans) {
        throw new Exception('Transaction not found');
      }

      if ($originalTrans['transaction_type'] !== 'deduct') {
        throw new Exception('Can only rollback deduction transactions');
      }

      $productId = $originalTrans['product_id'];
      $quantity = $originalTrans['quantity'];
      $uomId = $originalTrans['uom_id'];

      // Get current stock
      $stockStmt = $pdo->prepare("SELECT current_stock, stock_on_hand FROM products WHERE product_id = ?");
      $stockStmt->execute([$productId]);
      $stockRow = $stockStmt->fetch(PDO::FETCH_ASSOC);
      $stockBefore = $stockRow['stock_on_hand'];
      $currentStockBefore = $stockRow['current_stock'];
      $stockAfter = $stockBefore + $quantity;
      $currentStockAfter = $currentStockBefore + $quantity;

      // Generate UUID for rollback transaction
      $rollbackTransactionId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
        mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

      // Update product stock (both columns)
      $updateStmt = $pdo->prepare("UPDATE products SET current_stock = ?, stock_on_hand = ? WHERE product_id = ?");
      $updateStmt->execute([$currentStockAfter, $stockAfter, $productId]);

      // Record rollback transaction
      $transStmt = $pdo->prepare("
        INSERT INTO inventory_transactions
        (transaction_id, product_id, transaction_type, quantity, uom_id, reference_type, reason, stock_before, stock_after, created_by, rollback_transaction_id)
        VALUES (?, ?, 'rollback', ?, ?, ?, ?, ?, ?, ?, ?)
      ");
      $transStmt->execute([$rollbackTransactionId, $productId, $quantity, $uomId, $originalTrans['reference_type'], $reason, $stockBefore, $stockAfter, $userId, $transactionId]);

      $pdo->commit();
      echo json_encode(['success' => true, 'message' => 'Transaction rolled back successfully']);
    } catch (Exception $e) {
      $pdo->rollBack();
      echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
  }

  else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
  }
}
