<?php
// Helper functions for inventory operations
// This file can be included in other APIs without triggering endpoint logic

function deductInventoryForOrder($pdo, $orderId, $itemId, $quantity, $userId) {
  try {
    // Get recipe ingredients
    $stmt = $pdo->prepare("
      SELECT ri.product_id, ri.quantity, ri.uom_id, p.stock_on_hand, p.uom_id as base_uom_id
      FROM recipe_ingredients ri
      JOIN products p ON ri.product_id = p.product_id
      WHERE ri.recipe_id = (SELECT recipe_id FROM recipes WHERE item_id = ?)
    ");
    $stmt->execute([$itemId]);
    $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($ingredients)) {
      return ['success' => false, 'message' => 'No recipe found for this menu item'];
    }

    foreach ($ingredients as $ing) {
      $requiredQty = $ing['quantity'] * $quantity;
      $stockBefore = $ing['stock_on_hand'];
      $itemUomId = intval($ing['uom_id']);
      $baseUomId = intval($ing['base_uom_id']);

      // Handle NULL stock
      if ($stockBefore === null) {
        $stockBefore = 0;
      }

      // Convert to base UOM if needed
      if ($itemUomId !== $baseUomId) {
        // Try direct conversion first (recipe UOM → base UOM)
        $convStmt = $pdo->prepare("
          SELECT conversion_factor FROM product_uom_conversions
          WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ? AND is_active = 1
        ");
        $convStmt->execute([$ing['product_id'], $itemUomId, $baseUomId]);
        $conv = $convStmt->fetch(PDO::FETCH_ASSOC);

        if (!$conv) {
          // Try reverse: base → recipe UOM. Conversion factor means "1 base = N recipe"
          // So: recipe_qty * factor = base_qty
          $convStmt = $pdo->prepare("
            SELECT conversion_factor FROM product_uom_conversions
            WHERE product_id = ? AND from_uom_id = ? AND to_uom_id = ? AND is_active = 1
          ");
          $convStmt->execute([$ing['product_id'], $baseUomId, $itemUomId]);
          $conv = $convStmt->fetch(PDO::FETCH_ASSOC);
          if ($conv && floatval($conv['conversion_factor']) > 0) {
            $requiredQty = $requiredQty * floatval($conv['conversion_factor']);
          }
        } else {
          // Direct: recipe UOM → base UOM, multiply by factor
          $requiredQty = $requiredQty * floatval($conv['conversion_factor']);
        }
      }

      $stockAfter = $stockBefore - $requiredQty;
      if ($stockAfter < 0) {
        return ['success' => false, 'message' => 'Insufficient stock for product: ' . $ing['product_id']];
      }

      // Generate UUID for transaction
      $transactionId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
        mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

      // Update product stock (both columns)
      $updateStmt = $pdo->prepare("UPDATE products SET current_stock = ?, stock_on_hand = ? WHERE product_id = ?");
      $updateStmt->execute([$stockAfter, $stockAfter, $ing['product_id']]);

      // Record transaction
      $transStmt = $pdo->prepare("
        INSERT INTO inventory_transactions
        (transaction_id, product_id, transaction_type, quantity, uom_id, reference_type, reference_id, stock_before, stock_after, created_by)
        VALUES (?, ?, 'deduct', ?, ?, 'food_order', ?, ?, ?, ?)
      ");
      $transStmt->execute([$transactionId, $ing['product_id'], $requiredQty, $baseUomId, $orderId, $stockBefore, $stockAfter, $userId]);
    }

    return ['success' => true, 'message' => 'Inventory deducted successfully'];
  } catch (Exception $e) {
    error_log("Inventory deduction error: " . $e->getMessage());
    return ['success' => false, 'message' => $e->getMessage()];
  }
}
