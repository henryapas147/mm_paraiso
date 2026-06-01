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
        // List all recipes with basic info
        $stmt = $pdo->query("
          SELECT r.*, mi.item_name, mi.category_id, fc.category_name
          FROM recipes r
          LEFT JOIN menu_items mi ON r.item_id = mi.item_id
          LEFT JOIN food_categories fc ON mi.category_id = fc.category_id
          WHERE r.is_active = 1
          ORDER BY mi.item_name ASC
        ");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        
      } elseif ($action === 'by_item' && isset($_GET['item_id'])) {
        // Get recipe with ingredients for a specific menu item
        $recipeStmt = $pdo->prepare("
          SELECT r.*, mi.item_name, mi.price as menu_price
          FROM recipes r
          INNER JOIN menu_items mi ON r.item_id = mi.item_id
          WHERE r.item_id = ? AND r.is_active = 1
        ");
        $recipeStmt->execute([$_GET['item_id']]);
        $recipe = $recipeStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($recipe) {
          // Get ingredients
          $ingStmt = $pdo->prepare("
            SELECT ri.*, p.product_name, p.unit_cost as current_unit_cost, u.uom_code
            FROM recipe_ingredients ri
            INNER JOIN products p ON ri.product_id = p.product_id
            LEFT JOIN units_of_measure u ON ri.uom_id = u.uom_id
            WHERE ri.recipe_id = ?
            ORDER BY ri.sort_order ASC
          ");
          $ingStmt->execute([$recipe['recipe_id']]);
          $recipe['ingredients'] = $ingStmt->fetchAll(PDO::FETCH_ASSOC);
          
          // Calculate total cost
          $totalCost = 0;
          foreach ($recipe['ingredients'] as $ing) {
            $totalCost += $ing['quantity'] * $ing['cost_per_unit'];
          }
          $recipe['total_cost'] = $totalCost;
          $recipe['cost_per_serving'] = $recipe['yield_quantity'] > 0 ? $totalCost / $recipe['yield_quantity'] : 0;
          $recipe['profit_margin'] = $recipe['menu_price'] > 0 ? (($recipe['menu_price'] - $recipe['cost_per_serving']) / $recipe['menu_price'] * 100) : 0;
          
          echo json_encode(['success' => true, 'data' => $recipe]);
        } else {
          echo json_encode(['success' => false, 'message' => 'No recipe found for this menu item']);
        }
        
      } elseif ($action === 'single' && isset($_GET['id'])) {
        // Get single recipe with ingredients
        $recipeStmt = $pdo->prepare("
          SELECT r.*, mi.item_name, mi.price as menu_price
          FROM recipes r
          INNER JOIN menu_items mi ON r.item_id = mi.item_id
          WHERE r.recipe_id = ? AND r.is_active = 1
        ");
        $recipeStmt->execute([$_GET['id']]);
        $recipe = $recipeStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($recipe) {
          $ingStmt = $pdo->prepare("
            SELECT ri.*, p.product_name, u.uom_code
            FROM recipe_ingredients ri
            INNER JOIN products p ON ri.product_id = p.product_id
            LEFT JOIN units_of_measure u ON ri.uom_id = u.uom_id
            WHERE ri.recipe_id = ?
            ORDER BY ri.sort_order ASC
          ");
          $ingStmt->execute([$recipe['recipe_id']]);
          $recipe['ingredients'] = $ingStmt->fetchAll(PDO::FETCH_ASSOC);
          
          echo json_encode(['success' => true, 'data' => $recipe]);
        } else {
          echo json_encode(['success' => false, 'message' => 'Recipe not found']);
        }
        
      } elseif ($action === 'related') {
        // Get related data for dropdowns
        $menuItems = $pdo->query("SELECT item_id, item_name, category_id FROM menu_items WHERE is_active = 1 ORDER BY item_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $products = $pdo->query("SELECT product_id, product_name, uom_id, unit_cost FROM products WHERE is_active = 1 ORDER BY product_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $uoms = $pdo->query("SELECT uom_id, uom_code, uom_name FROM units_of_measure WHERE is_active = 1 ORDER BY uom_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
          'success' => true, 
          'data' => [
            'menuItems' => $menuItems,
            'products' => $products,
            'uoms' => $uoms
          ]
        ]);
      } else {
        echo json_encode(['success' => false, 'message' => 'Invalid or missing action: ' . htmlspecialchars($action)]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);
      
      if ($action === 'create') {
        // Check if recipe already exists for this item
        $checkStmt = $pdo->prepare("SELECT recipe_id FROM recipes WHERE item_id = ?");
        $checkStmt->execute([$data['item_id']]);
        $existing = $checkStmt->fetchColumn();
        
        if ($existing) {
          // Update existing instead of creating new
          $data['recipe_id'] = $existing;
          $action = 'update';
        } else {
          // Create new recipe
          $recipeId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
            mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
          
          $stmt = $pdo->prepare("
            INSERT INTO recipes 
            (recipe_id, item_id, recipe_name, description, portion_size, preparation_time, cooking_time, yield_quantity, is_active, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?)
          ");
          $stmt->execute([
            $recipeId,
            $data['item_id'],
            $data['recipe_name'],
            $data['description'] ?? null,
            $data['portion_size'] ?? null,
            $data['preparation_time'] ?? null,
            $data['cooking_time'] ?? null,
            $data['yield_quantity'] ?? 1,
            $_SESSION['user_id'] ?? null
          ]);
          
          // Insert ingredients
          if (!empty($data['ingredients'])) {
            $ingStmt = $pdo->prepare("
              INSERT INTO recipe_ingredients 
              (recipe_id, product_id, quantity, uom_id, ingredient_name, cost_per_unit, total_cost, sort_order)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $sortOrder = 0;
            foreach ($data['ingredients'] as $ing) {
              $totalCost = $ing['quantity'] * $ing['cost_per_unit'];
              $ingStmt->execute([
                $recipeId,
                $ing['product_id'],
                $ing['quantity'],
                $ing['uom_id'],
                $ing['ingredient_name'],
                $ing['cost_per_unit'],
                $totalCost,
                $sortOrder++
              ]);
            }
          }
          
          echo json_encode(['success' => true, 'message' => 'Recipe created successfully', 'recipe_id' => $recipeId]);
          break;
        }
      }
      
      if ($action === 'update') {
        $recipeId = $data['recipe_id'];
        
        // Update recipe header
        $stmt = $pdo->prepare("
          UPDATE recipes SET
            recipe_name = ?,
            description = ?,
            portion_size = ?,
            preparation_time = ?,
            cooking_time = ?,
            yield_quantity = ?,
            updated_at = NOW()
          WHERE recipe_id = ?
        ");
        $stmt->execute([
          $data['recipe_name'],
          $data['description'] ?? null,
          $data['portion_size'] ?? null,
          $data['preparation_time'] ?? null,
          $data['cooking_time'] ?? null,
          $data['yield_quantity'] ?? 1,
          $recipeId
        ]);
        
        // Delete existing ingredients
        $delStmt = $pdo->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = ?");
        $delStmt->execute([$recipeId]);
        
        // Insert new ingredients
        if (!empty($data['ingredients'])) {
          $ingStmt = $pdo->prepare("
            INSERT INTO recipe_ingredients 
            (recipe_id, product_id, quantity, uom_id, ingredient_name, cost_per_unit, total_cost, sort_order)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
          ");
          
          $sortOrder = 0;
          foreach ($data['ingredients'] as $ing) {
            $totalCost = $ing['quantity'] * $ing['cost_per_unit'];
            $ingStmt->execute([
              $recipeId,
              $ing['product_id'],
              $ing['quantity'],
              $ing['uom_id'],
              $ing['ingredient_name'],
              $ing['cost_per_unit'],
              $totalCost,
              $sortOrder++
            ]);
          }
        }
        
        echo json_encode(['success' => true, 'message' => 'Recipe updated successfully']);
      } elseif ($action === 'delete') {
        $recipeId = $data['recipe_id'];
        
        // Soft delete - set is_active to 0
        $stmt = $pdo->prepare("UPDATE recipes SET is_active = 0, updated_at = NOW() WHERE recipe_id = ?");
        $stmt->execute([$recipeId]);
        
        echo json_encode(['success' => true, 'message' => 'Recipe deleted successfully']);
      } else {
        echo json_encode(['success' => false, 'message' => 'Invalid POST action: ' . htmlspecialchars($action)]);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}