<?php
require_once '../../config/db.php';

// Helper function to generate UUID
function generateUUID() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

$action = $_POST['action'] ?? '';

if ($action === 'delete_product') {
    try {
        $product_id = $_POST['product_id'] ?? '';

        if (empty($product_id)) {
            throw new Exception("Product ID is required");
        }

        $pdo->beginTransaction();

        // Soft delete - set is_active = 0
        $stmt = $pdo->prepare("UPDATE products SET is_active = 0 WHERE product_id = ?");
        $stmt->execute([$product_id]);

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'add_product') {
    try {
        // Get form data
        $product_code = trim($_POST['product_code'] ?? '');
        $product_name = trim($_POST['product_name'] ?? '');
        $category_id = $_POST['category_id'] ?? '';
        $uom_id = $_POST['uom_id'] ?? '';
        $product_type = $_POST['product_type'] ?? 'consumable';
        $brand = trim($_POST['brand'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $unit_cost = floatval($_POST['unit_cost'] ?? 0);
        $selling_price = floatval($_POST['selling_price'] ?? 0);
        $initial_stock = floatval($_POST['initial_stock'] ?? 0);
        $reorder_point = floatval($_POST['reorder_point'] ?? 0);
        $minimum_stock = floatval($_POST['minimum_stock'] ?? 0);
        $maximum_stock = floatval($_POST['maximum_stock'] ?? 0);
        $preferred_supplier_id = $_POST['preferred_supplier_id'] ?? null;
        $is_for_sale = isset($_POST['is_for_sale']) ? 1 : 0;
        $is_for_rooms = isset($_POST['is_for_rooms']) ? 1 : 0;
        $track_inventory = isset($_POST['track_inventory']) ? 1 : 0;

        // Validate required fields
        if (empty($product_code) || empty($product_name) || empty($category_id) || empty($uom_id) || empty($product_type)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        // Check if product code already exists
        $stmt = $pdo->prepare("SELECT product_id FROM products WHERE product_code = ?");
        $stmt->execute([$product_code]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Product code already exists']);
            exit;
        }

        // Generate product ID
        $product_id = generateUUID();

        // Insert product
        $sql = "INSERT INTO products (
            product_id, 
            category_id, 
            uom_id, 
            preferred_supplier_id, 
            product_code, 
            barcode, 
            product_name, 
            brand, 
            description, 
            product_type, 
            unit_cost, 
            selling_price, 
            current_stock, 
            reorder_point, 
            minimum_stock, 
            maximum_stock, 
            is_for_sale, 
            is_for_rooms, 
            track_inventory, 
            is_active
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $product_id,
            $category_id,
            $uom_id,
            $preferred_supplier_id ?: null,
            $product_code,
            $product_code, // using product_code as barcode for now
            $product_name,
            $brand ?: null,
            $description ?: null,
            $product_type,
            $unit_cost,
            $selling_price,
            $initial_stock,
            $reorder_point,
            $minimum_stock,
            $maximum_stock ?: null,
            $is_for_sale,
            $is_for_rooms,
            $track_inventory
        ]);

        if ($result) {
            // If initial stock > 0, create a stock transaction
            if ($initial_stock > 0 && $track_inventory) {
                $txn_id = generateUUID();
                $sql = "INSERT INTO stock_transactions (
                    txn_id,
                    product_id,
                    transaction_type,
                    quantity,
                    uom_id,
                    stock_before,
                    stock_after,
                    reference_type,
                    reference_id,
                    notes,
                    transaction_date
                ) VALUES (?, ?, 'stock_in_manual', ?, ?, 0, ?, 'INITIAL', ?, ?, NOW())";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $txn_id,
                    $product_id,
                    $initial_stock,
                    $uom_id,
                    $initial_stock,
                    $product_id,
                    'Initial stock entry'
                ]);
            }

            echo json_encode(['success' => true, 'message' => 'Product added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add product']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} elseif ($action === 'add_category') {
    try {
        // Get form data
        $category_code = trim($_POST['category_code'] ?? '');
        $category_name = trim($_POST['category_name'] ?? '');
        $parent_id = $_POST['parent_id'] ?? null;
        $sort_order = intval($_POST['sort_order'] ?? 0);
        $description = trim($_POST['description'] ?? '');

        // Validate required fields
        if (empty($category_code) || empty($category_name)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        // Check if category code already exists
        $stmt = $pdo->prepare("SELECT category_id FROM inventory_categories WHERE category_code = ?");
        $stmt->execute([$category_code]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Category code already exists']);
            exit;
        }

        // Generate category ID
        $category_id = generateUUID();

        // Insert category
        $sql = "INSERT INTO inventory_categories (
            category_id,
            category_code,
            category_name,
            parent_id,
            description,
            sort_order,
            is_active
        ) VALUES (?, ?, ?, ?, ?, ?, 1)";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $category_id,
            $category_code,
            $category_name,
            $parent_id ?: null,
            $description ?: null,
            $sort_order
        ]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Category added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add category']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} elseif ($action === 'delete_category') {
    try {
        $category_id = $_POST['category_id'] ?? '';

        if (empty($category_id)) {
            throw new Exception("Category ID is required");
        }

        $pdo->beginTransaction();

        // Check if category has child categories
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM inventory_categories WHERE parent_id = ? AND is_active = 1");
        $stmt->execute([$category_id]);
        $child_count = $stmt->fetchColumn();

        if ($child_count > 0) {
            throw new Exception("Cannot delete category with child categories. Please delete or reassign child categories first.");
        }

        // Check if category has products
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ? AND is_active = 1");
        $stmt->execute([$category_id]);
        $product_count = $stmt->fetchColumn();

        if ($product_count > 0) {
            throw new Exception("Cannot delete category with products. Please reassign or delete products first.");
        }

        // Soft delete - set is_active = 0
        $stmt = $pdo->prepare("UPDATE inventory_categories SET is_active = 0 WHERE category_id = ?");
        $stmt->execute([$category_id]);

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Category deleted successfully']);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'edit_category') {
    try {
        // Get form data
        $category_id = $_POST['category_id'] ?? '';
        $category_code = trim($_POST['category_code'] ?? '');
        $category_name = trim($_POST['category_name'] ?? '');
        $parent_id = $_POST['parent_id'] ?? null;
        $sort_order = intval($_POST['sort_order'] ?? 0);
        $description = trim($_POST['description'] ?? '');

        // Validate required fields
        if (empty($category_id) || empty($category_code) || empty($category_name)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        // Check if category exists
        $stmt = $pdo->prepare("SELECT category_id FROM inventory_categories WHERE category_id = ?");
        $stmt->execute([$category_id]);
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Category not found']);
            exit;
        }

        // Check if category code already exists (excluding current category)
        $stmt = $pdo->prepare("SELECT category_id FROM inventory_categories WHERE category_code = ? AND category_id != ?");
        $stmt->execute([$category_code, $category_id]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Category code already exists']);
            exit;
        }

        // Prevent setting parent to self
        if ($parent_id === $category_id) {
            echo json_encode(['success' => false, 'message' => 'Cannot set category as its own parent']);
            exit;
        }

        // Update category
        $sql = "UPDATE inventory_categories SET
            category_code = ?,
            category_name = ?,
            parent_id = ?,
            description = ?,
            sort_order = ?
        WHERE category_id = ?";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $category_code,
            $category_name,
            $parent_id ?: null,
            $description ?: null,
            $sort_order,
            $category_id
        ]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Category updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update category']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} elseif ($action === 'add_uom') {
    try {
        // Get form data
        $uom_code = trim($_POST['uom_code'] ?? '');
        $uom_name = trim($_POST['uom_name'] ?? '');
        $uom_type = $_POST['uom_type'] ?? '';

        // Validate required fields
        if (empty($uom_code) || empty($uom_name) || empty($uom_type)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        // Check if UOM code already exists
        $stmt = $pdo->prepare("SELECT uom_id FROM units_of_measure WHERE uom_code = ?");
        $stmt->execute([$uom_code]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'UOM code already exists']);
            exit;
        }

        // Insert UOM (uom_id is auto-increment)
        $sql = "INSERT INTO units_of_measure (
            uom_code,
            uom_name,
            uom_type,
            is_active
        ) VALUES (?, ?, ?, 1)";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $uom_code,
            $uom_name,
            $uom_type
        ]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Unit of Measure added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add unit of measure']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} elseif ($action === 'edit_uom') {
    try {
        // Get form data
        $uom_id = $_POST['uom_id'] ?? '';
        $uom_code = trim($_POST['uom_code'] ?? '');
        $uom_name = trim($_POST['uom_name'] ?? '');
        $uom_type = $_POST['uom_type'] ?? '';

        // Validate required fields
        if (empty($uom_id) || empty($uom_code) || empty($uom_name) || empty($uom_type)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        // Check if UOM exists
        $stmt = $pdo->prepare("SELECT uom_id FROM units_of_measure WHERE uom_id = ?");
        $stmt->execute([$uom_id]);
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Unit of Measure not found']);
            exit;
        }

        // Check if UOM code already exists (excluding current UOM)
        $stmt = $pdo->prepare("SELECT uom_id FROM units_of_measure WHERE uom_code = ? AND uom_id != ?");
        $stmt->execute([$uom_code, $uom_id]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'UOM code already exists']);
            exit;
        }

        // Update UOM
        $sql = "UPDATE units_of_measure SET
            uom_code = ?,
            uom_name = ?,
            uom_type = ?
        WHERE uom_id = ?";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $uom_code,
            $uom_name,
            $uom_type,
            $uom_id
        ]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Unit of Measure updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update unit of measure']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} elseif ($action === 'delete_uom') {
    try {
        $uom_id = $_POST['uom_id'] ?? '';

        if (empty($uom_id)) {
            throw new Exception("UOM ID is required");
        }

        $pdo->beginTransaction();

        // Check if UOM is used by products
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE uom_id = ? AND is_active = 1");
        $stmt->execute([$uom_id]);
        $product_count = $stmt->fetchColumn();

        if ($product_count > 0) {
            throw new Exception("Cannot delete unit of measure that is in use by products. Please reassign or delete products first.");
        }

        // Soft delete - set is_active = 0
        $stmt = $pdo->prepare("UPDATE units_of_measure SET is_active = 0 WHERE uom_id = ?");
        $stmt->execute([$uom_id]);

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Unit of Measure deleted successfully']);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'add_location') {
    try {
        // Get form data
        $property_id = $_POST['property_id'] ?? '';
        $location_name = trim($_POST['location_name'] ?? '');
        $location_type = $_POST['location_type'] ?? '';
        $description = trim($_POST['description'] ?? '');

        // Validate required fields
        if (empty($property_id) || empty($location_name) || empty($location_type)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        // Insert location (location_id is auto-increment)
        $sql = "INSERT INTO inventory_locations (
            property_id,
            location_name,
            location_type,
            description,
            is_active
        ) VALUES (?, ?, ?, ?, 1)";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $property_id,
            $location_name,
            $location_type,
            $description ?: null
        ]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Location added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add location']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} elseif ($action === 'edit_location') {
    try {
        // Get form data
        $location_id = $_POST['location_id'] ?? '';
        $property_id = $_POST['property_id'] ?? '';
        $location_name = trim($_POST['location_name'] ?? '');
        $location_type = $_POST['location_type'] ?? '';
        $description = trim($_POST['description'] ?? '');

        // Validate required fields
        if (empty($location_id) || empty($property_id) || empty($location_name) || empty($location_type)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        // Check if location exists
        $stmt = $pdo->prepare("SELECT location_id FROM inventory_locations WHERE location_id = ?");
        $stmt->execute([$location_id]);
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Location not found']);
            exit;
        }

        // Update location
        $sql = "UPDATE inventory_locations SET
            property_id = ?,
            location_name = ?,
            location_type = ?,
            description = ?
        WHERE location_id = ?";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $property_id,
            $location_name,
            $location_type,
            $description ?: null,
            $location_id
        ]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Location updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update location']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} elseif ($action === 'delete_location') {
    try {
        $location_id = $_POST['location_id'] ?? '';

        if (empty($location_id)) {
            throw new Exception("Location ID is required");
        }

        $pdo->beginTransaction();

        // Check if location has stock
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM product_stock_per_location WHERE location_id = ?");
        $stmt->execute([$location_id]);
        $stock_count = $stmt->fetchColumn();

        if ($stock_count > 0) {
            throw new Exception("Cannot delete location that has stock. Please transfer or remove stock first.");
        }

        // Soft delete - set is_active = 0
        $stmt = $pdo->prepare("UPDATE inventory_locations SET is_active = 0 WHERE location_id = ?");
        $stmt->execute([$location_id]);

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Location deleted successfully']);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'add_supplier') {
    try {
        // Get form data
        $supplier_code = trim($_POST['supplier_code'] ?? '');
        $company_name = trim($_POST['company_name'] ?? '');
        $contact_person = trim($_POST['contact_person'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone_primary = trim($_POST['phone_primary'] ?? '');
        $phone_secondary = trim($_POST['phone_secondary'] ?? '');
        $address_line1 = trim($_POST['address_line1'] ?? '');
        $address_line2 = trim($_POST['address_line2'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $province = trim($_POST['province'] ?? '');
        $tin_number = trim($_POST['tin_number'] ?? '');
        $payment_terms = trim($_POST['payment_terms'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        // Validate required fields
        if (empty($company_name)) {
            echo json_encode(['success' => false, 'message' => 'Company name is required']);
            exit;
        }

        // Check if supplier code already exists
        if (!empty($supplier_code)) {
            $stmt = $pdo->prepare("SELECT supplier_id FROM suppliers WHERE supplier_code = ?");
            $stmt->execute([$supplier_code]);
            if ($stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Supplier code already exists']);
                exit;
            }
        }

        // Generate supplier ID
        $supplier_id = generateUUID();

        // Insert supplier
        $sql = "INSERT INTO suppliers (
            supplier_id,
            supplier_code,
            company_name,
            contact_person,
            email,
            phone_primary,
            phone_secondary,
            address_line1,
            address_line2,
            city,
            province,
            tin_number,
            payment_terms,
            notes,
            is_active
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $supplier_id,
            $supplier_code ?: null,
            $company_name,
            $contact_person ?: null,
            $email ?: null,
            $phone_primary ?: null,
            $phone_secondary ?: null,
            $address_line1 ?: null,
            $address_line2 ?: null,
            $city ?: null,
            $province ?: null,
            $tin_number ?: null,
            $payment_terms ?: null,
            $notes ?: null
        ]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Supplier added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add supplier']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} elseif ($action === 'edit_supplier') {
    try {
        // Get form data
        $supplier_id = $_POST['supplier_id'] ?? '';
        $supplier_code = trim($_POST['supplier_code'] ?? '');
        $company_name = trim($_POST['company_name'] ?? '');
        $contact_person = trim($_POST['contact_person'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone_primary = trim($_POST['phone_primary'] ?? '');
        $phone_secondary = trim($_POST['phone_secondary'] ?? '');
        $address_line1 = trim($_POST['address_line1'] ?? '');
        $address_line2 = trim($_POST['address_line2'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $province = trim($_POST['province'] ?? '');
        $tin_number = trim($_POST['tin_number'] ?? '');
        $payment_terms = trim($_POST['payment_terms'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        // Validate required fields
        if (empty($supplier_id) || empty($company_name)) {
            echo json_encode(['success' => false, 'message' => 'Supplier ID and company name are required']);
            exit;
        }

        // Check if supplier exists
        $stmt = $pdo->prepare("SELECT supplier_id FROM suppliers WHERE supplier_id = ?");
        $stmt->execute([$supplier_id]);
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Supplier not found']);
            exit;
        }

        // Check if supplier code already exists (excluding current supplier)
        if (!empty($supplier_code)) {
            $stmt = $pdo->prepare("SELECT supplier_id FROM suppliers WHERE supplier_code = ? AND supplier_id != ?");
            $stmt->execute([$supplier_code, $supplier_id]);
            if ($stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Supplier code already exists']);
                exit;
            }
        }

        // Update supplier
        $sql = "UPDATE suppliers SET
            supplier_code = ?,
            company_name = ?,
            contact_person = ?,
            email = ?,
            phone_primary = ?,
            phone_secondary = ?,
            address_line1 = ?,
            address_line2 = ?,
            city = ?,
            province = ?,
            tin_number = ?,
            payment_terms = ?,
            notes = ?
        WHERE supplier_id = ?";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $supplier_code ?: null,
            $company_name,
            $contact_person ?: null,
            $email ?: null,
            $phone_primary ?: null,
            $phone_secondary ?: null,
            $address_line1 ?: null,
            $address_line2 ?: null,
            $city ?: null,
            $province ?: null,
            $tin_number ?: null,
            $payment_terms ?: null,
            $notes ?: null,
            $supplier_id
        ]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Supplier updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update supplier']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} elseif ($action === 'delete_supplier') {
    try {
        $supplier_id = $_POST['supplier_id'] ?? '';

        if (empty($supplier_id)) {
            throw new Exception("Supplier ID is required");
        }

        $pdo->beginTransaction();

        // Check if supplier is used by products
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE preferred_supplier_id = ? AND is_active = 1");
        $stmt->execute([$supplier_id]);
        $product_count = $stmt->fetchColumn();

        if ($product_count > 0) {
            throw new Exception("Cannot delete supplier that is in use by products. Please reassign or delete products first.");
        }

        // Check if supplier has purchase orders
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM purchase_orders WHERE supplier_id = ?");
        $stmt->execute([$supplier_id]);
        $po_count = $stmt->fetchColumn();

        if ($po_count > 0) {
            throw new Exception("Cannot delete supplier that has purchase orders. Please delete or close purchase orders first.");
        }

        // Soft delete - set is_active = 0
        $stmt = $pdo->prepare("UPDATE suppliers SET is_active = 0 WHERE supplier_id = ?");
        $stmt->execute([$supplier_id]);

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Supplier deleted successfully']);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'add_stock_per_location') {
    try {
        // Get form data
        $product_id = $_POST['product_id'] ?? '';
        $location_id = $_POST['location_id'] ?? '';
        $adjustment_type = $_POST['adjustment_type'] ?? '';
        $quantity = floatval($_POST['quantity'] ?? 0);
        $notes = trim($_POST['notes'] ?? '');

        // Validate required fields
        if (empty($product_id) || empty($location_id) || empty($adjustment_type) || $quantity <= 0) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        $pdo->beginTransaction();

        // Get product UOM for stock transaction
        $stmt = $pdo->prepare("SELECT uom_id FROM products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        if (!$product) {
            throw new Exception("Product not found");
        }
        $uom_id = $product['uom_id'];

        // Check if stock record exists for this product-location combination
        $stmt = $pdo->prepare("SELECT stock_loc_id, quantity FROM product_stock_per_location WHERE product_id = ? AND location_id = ?");
        $stmt->execute([$product_id, $location_id]);
        $existing_stock = $stmt->fetch();

        $new_quantity = 0;
        $stock_before = 0;

        if ($existing_stock) {
            $stock_before = $existing_stock['quantity'];
            // Calculate new quantity based on adjustment type
            if ($adjustment_type === 'add') {
                $new_quantity = $stock_before + $quantity;
            } elseif ($adjustment_type === 'deduct') {
                $new_quantity = $stock_before - $quantity;
                if ($new_quantity < 0) {
                    throw new Exception("Cannot deduct more than current stock. Current stock: " . $stock_before);
                }
            } else {
                throw new Exception("Invalid adjustment type");
            }

            // Update existing record
            $stmt = $pdo->prepare("UPDATE product_stock_per_location SET quantity = ? WHERE stock_loc_id = ?");
            $stmt->execute([$new_quantity, $existing_stock['stock_loc_id']]);
        } else {
            $stock_before = 0;
            // Only allow add for new records
            if ($adjustment_type === 'deduct') {
                throw new Exception("Cannot deduct from a non-existent stock record. Use 'Add' instead.");
            }
            $new_quantity = $quantity;

            // Insert new stock record
            $sql = "INSERT INTO product_stock_per_location (product_id, location_id, quantity) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$product_id, $location_id, $new_quantity]);
        }

        // Determine transaction type
        $transaction_type = 'adjustment_add';
        if ($adjustment_type === 'deduct') {
            $transaction_type = 'adjustment_deduct';
        }

        // Create stock transaction
        $txn_id = generateUUID();
        $txn_quantity = $quantity;

        $sql = "INSERT INTO stock_transactions (
            txn_id,
            product_id,
            location_id,
            transaction_type,
            quantity,
            uom_id,
            stock_before,
            stock_after,
            reference_type,
            notes,
            transaction_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'MANUAL_ADJUSTMENT', ?, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $txn_id,
            $product_id,
            $location_id,
            $transaction_type,
            $txn_quantity,
            $uom_id,
            $stock_before,
            $new_quantity,
            $notes ?: 'Manual stock adjustment'
        ]);

        // Update product's total stock
        $stmt = $pdo->prepare("
            SELECT COALESCE(SUM(quantity), 0) as total_stock
            FROM product_stock_per_location
            WHERE product_id = ?
        ");
        $stmt->execute([$product_id]);
        $total_stock = $stmt->fetchColumn();

        $stmt = $pdo->prepare("UPDATE products SET current_stock = ? WHERE product_id = ?");
        $stmt->execute([$total_stock, $product_id]);

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Stock adjusted successfully']);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'edit_stock_per_location') {
    try {
        // Get form data
        $stock_loc_id = $_POST['stock_loc_id'] ?? '';
        $adjustment_type = $_POST['adjustment_type'] ?? '';
        $quantity = floatval($_POST['quantity'] ?? 0);
        $notes = trim($_POST['notes'] ?? '');

        // Validate required fields
        if (empty($stock_loc_id) || empty($adjustment_type) || $quantity <= 0) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        $pdo->beginTransaction();

        // Get current stock record
        $stmt = $pdo->prepare("
            SELECT psl.*, p.uom_id
            FROM product_stock_per_location psl
            INNER JOIN products p ON psl.product_id = p.product_id
            WHERE psl.stock_loc_id = ?
        ");
        $stmt->execute([$stock_loc_id]);
        $stock = $stmt->fetch();

        if (!$stock) {
            throw new Exception("Stock record not found");
        }

        $stock_before = $stock['quantity'];
        $product_id = $stock['product_id'];
        $location_id = $stock['location_id'];
        $uom_id = $stock['uom_id'];

        // Calculate new quantity based on adjustment type
        if ($adjustment_type === 'add') {
            $new_quantity = $stock_before + $quantity;
        } elseif ($adjustment_type === 'deduct') {
            $new_quantity = $stock_before - $quantity;
            if ($new_quantity < 0) {
                throw new Exception("Cannot deduct more than current stock. Current stock: " . $stock_before);
            }
        } else {
            throw new Exception("Invalid adjustment type");
        }

        // Update stock record
        $stmt = $pdo->prepare("UPDATE product_stock_per_location SET quantity = ? WHERE stock_loc_id = ?");
        $stmt->execute([$new_quantity, $stock_loc_id]);

        // Determine transaction type
        $transaction_type = 'adjustment_add';
        if ($adjustment_type === 'deduct') {
            $transaction_type = 'adjustment_deduct';
        }

        // Create stock transaction
        $txn_id = generateUUID();
        $txn_quantity = $quantity;

        $sql = "INSERT INTO stock_transactions (
            txn_id,
            product_id,
            location_id,
            transaction_type,
            quantity,
            uom_id,
            stock_before,
            stock_after,
            reference_type,
            notes,
            transaction_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'MANUAL_ADJUSTMENT', ?, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $txn_id,
            $product_id,
            $location_id,
            $transaction_type,
            $txn_quantity,
            $uom_id,
            $stock_before,
            $new_quantity,
            $notes ?: 'Manual stock adjustment'
        ]);

        // Update product's total stock
        $stmt = $pdo->prepare("
            SELECT COALESCE(SUM(quantity), 0) as total_stock
            FROM product_stock_per_location
            WHERE product_id = ?
        ");
        $stmt->execute([$product_id]);
        $total_stock = $stmt->fetchColumn();

        $stmt = $pdo->prepare("UPDATE products SET current_stock = ? WHERE product_id = ?");
        $stmt->execute([$total_stock, $product_id]);

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Stock adjusted successfully']);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'delete_stock_per_location') {
    try {
        $stock_loc_id = $_POST['stock_loc_id'] ?? '';

        if (empty($stock_loc_id)) {
            throw new Exception("Stock location ID is required");
        }

        $pdo->beginTransaction();

        // Get stock record details before deletion
        $stmt = $pdo->prepare("
            SELECT psl.*, p.uom_id
            FROM product_stock_per_location psl
            INNER JOIN products p ON psl.product_id = p.product_id
            WHERE psl.stock_loc_id = ?
        ");
        $stmt->execute([$stock_loc_id]);
        $stock = $stmt->fetch();

        if (!$stock) {
            throw new Exception("Stock record not found");
        }

        $product_id = $stock['product_id'];
        $location_id = $stock['location_id'];
        $quantity = $stock['quantity'];
        $uom_id = $stock['uom_id'];

        // Delete the stock record
        $stmt = $pdo->prepare("DELETE FROM product_stock_per_location WHERE stock_loc_id = ?");
        $stmt->execute([$stock_loc_id]);

        // Create stock transaction for the deletion
        $txn_id = generateUUID();
        $sql = "INSERT INTO stock_transactions (
            txn_id,
            product_id,
            location_id,
            transaction_type,
            quantity,
            uom_id,
            stock_before,
            stock_after,
            reference_type,
            notes,
            transaction_date
        ) VALUES (?, ?, ?, 'adjustment_deduct', ?, ?, ?, 0, 'MANUAL_DELETE', ?, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $txn_id,
            $product_id,
            $location_id,
            $quantity,
            $uom_id,
            $quantity,
            'Stock record deleted'
        ]);

        // Update product's total stock
        $stmt = $pdo->prepare("
            SELECT COALESCE(SUM(quantity), 0) as total_stock
            FROM product_stock_per_location
            WHERE product_id = ?
        ");
        $stmt->execute([$product_id]);
        $total_stock = $stmt->fetchColumn();

        $stmt = $pdo->prepare("UPDATE products SET current_stock = ? WHERE product_id = ?");
        $stmt->execute([$total_stock, $product_id]);

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Stock record deleted successfully']);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'add_purchase_order') {
    try {
        // Get form data
        $property_id = $_POST['property_id'] ?? '';
        $supplier_id = $_POST['supplier_id'] ?? '';
        $po_number = trim($_POST['po_number'] ?? '');
        $po_date = $_POST['po_date'] ?? '';
        $expected_delivery = $_POST['expected_delivery'] ?? null;
        $po_status = $_POST['po_status'] ?? 'draft';
        $payment_terms = trim($_POST['payment_terms'] ?? '');
        $delivery_address = trim($_POST['delivery_address'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        // Validate required fields
        if (empty($property_id) || empty($supplier_id) || empty($po_number) || empty($po_date)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        // Check if PO number already exists
        $stmt = $pdo->prepare("SELECT po_id FROM purchase_orders WHERE po_number = ?");
        $stmt->execute([$po_number]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'PO number already exists']);
            exit;
        }

        $pdo->beginTransaction();

        // Generate PO ID
        $po_id = generateUUID();

        // Insert purchase order
        $sql = "INSERT INTO purchase_orders (
            po_id,
            property_id,
            supplier_id,
            po_number,
            po_date,
            expected_delivery,
            po_status,
            subtotal,
            tax_amount,
            shipping_fee,
            discount_amount,
            total_amount,
            amount_paid,
            balance,
            payment_status,
            payment_terms,
            delivery_address,
            notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 0, 0, 0, 0, 0, 0, 0, 'unpaid', ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $po_id,
            $property_id,
            $supplier_id,
            $po_number,
            $po_date,
            $expected_delivery ?: null,
            $po_status,
            $payment_terms ?: null,
            $delivery_address ?: null,
            $notes ?: null
        ]);

        if ($result) {
            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Purchase order created successfully']);
        } else {
            throw new Exception("Failed to create purchase order");
        }

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'edit_purchase_order') {
    try {
        // Get form data
        $po_id = $_POST['po_id'] ?? '';
        $property_id = $_POST['property_id'] ?? '';
        $supplier_id = $_POST['supplier_id'] ?? '';
        $po_number = trim($_POST['po_number'] ?? '');
        $po_date = $_POST['po_date'] ?? '';
        $expected_delivery = $_POST['expected_delivery'] ?? null;
        $po_status = $_POST['po_status'] ?? 'draft';
        $payment_terms = trim($_POST['payment_terms'] ?? '');
        $delivery_address = trim($_POST['delivery_address'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        // Validate required fields
        if (empty($po_id) || empty($property_id) || empty($supplier_id) || empty($po_number) || empty($po_date)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        $pdo->beginTransaction();

        // Check if PO exists
        $stmt = $pdo->prepare("SELECT po_id FROM purchase_orders WHERE po_id = ?");
        $stmt->execute([$po_id]);
        if (!$stmt->fetch()) {
            throw new Exception("Purchase order not found");
        }

        // Check if PO number already exists (excluding current PO)
        $stmt = $pdo->prepare("SELECT po_id FROM purchase_orders WHERE po_number = ? AND po_id != ?");
        $stmt->execute([$po_number, $po_id]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'PO number already exists']);
            exit;
        }

        // Update purchase order
        $sql = "UPDATE purchase_orders SET
            property_id = ?,
            supplier_id = ?,
            po_number = ?,
            po_date = ?,
            expected_delivery = ?,
            po_status = ?,
            payment_terms = ?,
            delivery_address = ?,
            notes = ?
        WHERE po_id = ?";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $property_id,
            $supplier_id,
            $po_number,
            $po_date,
            $expected_delivery ?: null,
            $po_status,
            $payment_terms ?: null,
            $delivery_address ?: null,
            $notes ?: null,
            $po_id
        ]);

        if ($result) {
            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Purchase order updated successfully']);
        } else {
            throw new Exception("Failed to update purchase order");
        }

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'delete_purchase_order') {
    try {
        $po_id = $_POST['po_id'] ?? '';

        if (empty($po_id)) {
            throw new Exception("Purchase Order ID is required");
        }

        $pdo->beginTransaction();

        // Check if PO exists
        $stmt = $pdo->prepare("SELECT po_status FROM purchase_orders WHERE po_id = ?");
        $stmt->execute([$po_id]);
        $po = $stmt->fetch();

        if (!$po) {
            throw new Exception("Purchase order not found");
        }

        // Check if PO has items
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM purchase_order_items WHERE po_id = ?");
        $stmt->execute([$po_id]);
        $item_count = $stmt->fetchColumn();

        if ($item_count > 0) {
            throw new Exception("Cannot delete purchase order that has items. Please delete items first.");
        }

        // Check if PO has payments
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM po_payments WHERE po_id = ?");
        $stmt->execute([$po_id]);
        $payment_count = $stmt->fetchColumn();

        if ($payment_count > 0) {
            throw new Exception("Cannot delete purchase order that has payments. Please delete payments first.");
        }

        // Delete the PO
        $stmt = $pdo->prepare("DELETE FROM purchase_orders WHERE po_id = ?");
        $stmt->execute([$po_id]);

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Purchase order deleted successfully']);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'add_stock_transaction') {
    try {
        // Get form data
        $product_id = $_POST['product_id'] ?? '';
        $location_id = $_POST['location_id'] ?? '';
        $transaction_type = $_POST['transaction_type'] ?? '';
        $uom_id = $_POST['uom_id'] ?? '';
        $quantity = floatval($_POST['quantity'] ?? 0);
        $transaction_date = $_POST['transaction_date'] ?? '';
        $reference_type = $_POST['reference_type'] ?? 'MANUAL';
        $reference_id = trim($_POST['reference_id'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        // Validate required fields
        if (empty($product_id) || empty($transaction_type) || empty($uom_id) || $quantity <= 0 || empty($transaction_date)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        $pdo->beginTransaction();

        // Get current stock for the product-location combination
        $stmt = $pdo->prepare("
            SELECT COALESCE(quantity, 0) as current_stock
            FROM product_stock_per_location
            WHERE product_id = ? AND location_id = ?
        ");
        $stmt->execute([$product_id, $location_id]);
        $stock = $stmt->fetch();
        $stock_before = $stock ? $stock['current_stock'] : 0;

        // Calculate new stock based on transaction type
        $stock_after = $stock_before;
        $is_stock_in = in_array($transaction_type, ['stock_in', 'stock_in_manual', 'adjustment_add', 'transfer_in']);

        if ($is_stock_in) {
            $stock_after = $stock_before + $quantity;
        } else {
            $stock_after = $stock_before - $quantity;
            if ($stock_after < 0) {
                throw new Exception("Insufficient stock. Current stock: " . $stock_before);
            }
        }

        // Create stock transaction
        $txn_id = generateUUID();
        $sql = "INSERT INTO stock_transactions (
            txn_id,
            product_id,
            location_id,
            transaction_type,
            quantity,
            uom_id,
            stock_before,
            stock_after,
            reference_type,
            reference_id,
            notes,
            transaction_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $txn_id,
            $product_id,
            $location_id ?: null,
            $transaction_type,
            $quantity,
            $uom_id,
            $stock_before,
            $stock_after,
            $reference_type,
            $reference_id ?: null,
            $notes ?: null,
            $transaction_date
        ]);

        if ($result) {
            // Update or create stock record
            if ($stock) {
                // Update existing stock
                $stmt = $pdo->prepare("UPDATE product_stock_per_location SET quantity = ? WHERE product_id = ? AND location_id = ?");
                $stmt->execute([$stock_after, $product_id, $location_id]);
            } else {
                // Create new stock record
                $sql = "INSERT INTO product_stock_per_location (product_id, location_id, quantity) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$product_id, $location_id ?: null, $stock_after]);
            }

            // Update product's total stock
            $stmt = $pdo->prepare("
                SELECT COALESCE(SUM(quantity), 0) as total_stock
                FROM product_stock_per_location
                WHERE product_id = ?
            ");
            $stmt->execute([$product_id]);
            $total_stock = $stmt->fetchColumn();

            $stmt = $pdo->prepare("UPDATE products SET current_stock = ? WHERE product_id = ?");
            $stmt->execute([$total_stock, $product_id]);

            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Stock transaction created successfully']);
        } else {
            throw new Exception("Failed to create stock transaction");
        }

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'edit_stock_transaction') {
    try {
        // Get form data
        $txn_id = $_POST['txn_id'] ?? '';
        $product_id = $_POST['product_id'] ?? '';
        $location_id = $_POST['location_id'] ?? '';
        $transaction_type = $_POST['transaction_type'] ?? '';
        $uom_id = $_POST['uom_id'] ?? '';
        $quantity = floatval($_POST['quantity'] ?? 0);
        $transaction_date = $_POST['transaction_date'] ?? '';
        $reference_type = $_POST['reference_type'] ?? 'MANUAL';
        $reference_id = trim($_POST['reference_id'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        // Validate required fields
        if (empty($txn_id) || empty($product_id) || empty($transaction_type) || empty($uom_id) || $quantity <= 0 || empty($transaction_date)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        $pdo->beginTransaction();

        // Get existing transaction
        $stmt = $pdo->prepare("SELECT * FROM stock_transactions WHERE txn_id = ?");
        $stmt->execute([$txn_id]);
        $old_txn = $stmt->fetch();

        if (!$old_txn) {
            throw new Exception("Transaction not found");
        }

        // Reverse the old transaction's effect on stock
        $old_location_id = $old_txn['location_id'];
        $stmt = $pdo->prepare("
            SELECT COALESCE(quantity, 0) as current_stock
            FROM product_stock_per_location
            WHERE product_id = ? AND location_id = ?
        ");
        $stmt->execute([$old_txn['product_id'], $old_location_id]);
        $old_stock = $stmt->fetch();
        $current_stock = $old_stock ? $old_stock['current_stock'] : 0;

        $old_is_stock_in = in_array($old_txn['transaction_type'], ['stock_in', 'stock_in_manual', 'adjustment_add', 'transfer_in']);
        $reversed_stock = $old_is_stock_in ? $current_stock - $old_txn['quantity'] : $current_stock + $old_txn['quantity'];

        // Apply the new transaction's effect
        $new_location_id = $location_id;
        $stmt = $pdo->prepare("
            SELECT COALESCE(quantity, 0) as current_stock
            FROM product_stock_per_location
            WHERE product_id = ? AND location_id = ?
        ");
        $stmt->execute([$product_id, $new_location_id]);
        $new_stock = $stmt->fetch();
        $base_stock = $new_stock ? $new_stock['current_stock'] : 0;

        $is_stock_in = in_array($transaction_type, ['stock_in', 'stock_in_manual', 'adjustment_add', 'transfer_in']);
        $stock_after = $is_stock_in ? $base_stock + $quantity : $base_stock - $quantity;

        if ($stock_after < 0) {
            throw new Exception("Insufficient stock for the new transaction. Current stock at location: " . $base_stock);
        }

        // Update the transaction
        $sql = "UPDATE stock_transactions SET
            product_id = ?,
            location_id = ?,
            transaction_type = ?,
            quantity = ?,
            uom_id = ?,
            stock_before = ?,
            stock_after = ?,
            reference_type = ?,
            reference_id = ?,
            notes = ?,
            transaction_date = ?
        WHERE txn_id = ?";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $product_id,
            $new_location_id ?: null,
            $transaction_type,
            $quantity,
            $uom_id,
            $base_stock,
            $stock_after,
            $reference_type,
            $reference_id ?: null,
            $notes ?: null,
            $transaction_date,
            $txn_id
        ]);

        if ($result) {
            // Update the old location's stock
            if ($old_stock) {
                $stmt = $pdo->prepare("UPDATE product_stock_per_location SET quantity = ? WHERE product_id = ? AND location_id = ?");
                $stmt->execute([$reversed_stock, $old_txn['product_id'], $old_location_id]);
            } else {
                if ($reversed_stock > 0) {
                    $sql = "INSERT INTO product_stock_per_location (product_id, location_id, quantity) VALUES (?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$old_txn['product_id'], $old_location_id, $reversed_stock]);
                }
            }

            // Update the new location's stock
            if ($new_stock) {
                $stmt = $pdo->prepare("UPDATE product_stock_per_location SET quantity = ? WHERE product_id = ? AND location_id = ?");
                $stmt->execute([$stock_after, $product_id, $new_location_id]);
            } else {
                $sql = "INSERT INTO product_stock_per_location (product_id, location_id, quantity) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$product_id, $new_location_id, $stock_after]);
            }

            // Update product's total stock
            $stmt = $pdo->prepare("
                SELECT COALESCE(SUM(quantity), 0) as total_stock
                FROM product_stock_per_location
                WHERE product_id = ?
            ");
            $stmt->execute([$product_id]);
            $total_stock = $stmt->fetchColumn();

            $stmt = $pdo->prepare("UPDATE products SET current_stock = ? WHERE product_id = ?");
            $stmt->execute([$total_stock, $product_id]);

            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Stock transaction updated successfully']);
        } else {
            throw new Exception("Failed to update stock transaction");
        }

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'reverse_stock_transaction') {
    try {
        $txn_id = $_POST['txn_id'] ?? '';
        $notes = trim($_POST['notes'] ?? '');

        if (empty($txn_id) || empty($notes)) {
            echo json_encode(['success' => false, 'message' => 'Transaction ID and reason are required']);
            exit;
        }

        $pdo->beginTransaction();

        // Get original transaction details
        $stmt = $pdo->prepare("SELECT * FROM stock_transactions WHERE txn_id = ?");
        $stmt->execute([$txn_id]);
        $original_txn = $stmt->fetch();

        if (!$original_txn) {
            throw new Exception("Transaction not found");
        }

        // Determine reverse transaction type
        $reverse_type_map = [
            'stock_in' => 'stock_out_damage',
            'stock_in_manual' => 'adjustment_deduct',
            'adjustment_add' => 'adjustment_deduct',
            'stock_out_sale' => 'adjustment_add',
            'stock_out_use' => 'adjustment_add',
            'stock_out_damage' => 'adjustment_add',
            'adjustment_deduct' => 'adjustment_add',
            'transfer_out' => 'transfer_in',
            'transfer_in' => 'transfer_out',
            'return_to_supplier' => 'stock_in_manual'
        ];

        $reverse_type = $reverse_type_map[$original_txn['transaction_type']] ?? 'adjustment_deduct';

        // Get current stock
        $stmt = $pdo->prepare("
            SELECT COALESCE(quantity, 0) as current_stock
            FROM product_stock_per_location
            WHERE product_id = ? AND location_id = ?
        ");
        $stmt->execute([$original_txn['product_id'], $original_txn['location_id']]);
        $stock = $stmt->fetch();
        $stock_before = $stock ? $stock['current_stock'] : 0;

        // Calculate new stock (reverse the original transaction)
        $is_original_stock_in = in_array($original_txn['transaction_type'], ['stock_in', 'stock_in_manual', 'adjustment_add', 'transfer_in']);
        $stock_after = $is_original_stock_in ? $stock_before - $original_txn['quantity'] : $stock_before + $original_txn['quantity'];

        if ($stock_after < 0) {
            throw new Exception("Cannot reverse transaction. It would result in negative stock. Current stock: " . $stock_before);
        }

        // Create reverse transaction
        $reverse_txn_id = generateUUID();
        $sql = "INSERT INTO stock_transactions (
            txn_id,
            product_id,
            location_id,
            transaction_type,
            quantity,
            uom_id,
            stock_before,
            stock_after,
            reference_type,
            reference_id,
            reference_number,
            notes,
            transaction_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'REVERSAL', ?, ?, ?, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $reverse_txn_id,
            $original_txn['product_id'],
            $original_txn['location_id'],
            $reverse_type,
            $original_txn['quantity'],
            $original_txn['uom_id'],
            $stock_before,
            $stock_after,
            $original_txn['txn_id'],
            'REV-' . substr($original_txn['txn_id'], 0, 8),
            'Reversal: ' . $notes
        ]);

        // Update stock
        if ($stock) {
            if ($stock_after == 0) {
                $stmt = $pdo->prepare("DELETE FROM product_stock_per_location WHERE product_id = ? AND location_id = ?");
                $stmt->execute([$original_txn['product_id'], $original_txn['location_id']]);
            } else {
                $stmt = $pdo->prepare("UPDATE product_stock_per_location SET quantity = ? WHERE product_id = ? AND location_id = ?");
                $stmt->execute([$stock_after, $original_txn['product_id'], $original_txn['location_id']]);
            }
        } else {
            if ($stock_after > 0) {
                $sql = "INSERT INTO product_stock_per_location (product_id, location_id, quantity) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$original_txn['product_id'], $original_txn['location_id'], $stock_after]);
            }
        }

        // Update product's total stock
        $stmt = $pdo->prepare("
            SELECT COALESCE(SUM(quantity), 0) as total_stock
            FROM product_stock_per_location
            WHERE product_id = ?
        ");
        $stmt->execute([$original_txn['product_id']]);
        $total_stock = $stmt->fetchColumn();

        $stmt = $pdo->prepare("UPDATE products SET current_stock = ? WHERE product_id = ?");
        $stmt->execute([$total_stock, $original_txn['product_id']]);

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Stock transaction reversed successfully']);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'add_stock_transfer') {
    try {
        // Get form data
        $from_location_id = $_POST['from_location_id'] ?? '';
        $to_location_id = $_POST['to_location_id'] ?? '';
        $transfer_date = $_POST['transfer_date'] ?? '';
        $transfer_status = $_POST['transfer_status'] ?? 'pending';
        $notes = trim($_POST['notes'] ?? '');
        $items = $_POST['items'] ?? [];

        // Validate required fields
        if (empty($from_location_id) || empty($to_location_id) || empty($transfer_date) || empty($items)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        if ($from_location_id === $to_location_id) {
            echo json_encode(['success' => false, 'message' => 'From and To locations cannot be the same']);
            exit;
        }

        $pdo->beginTransaction();

        // Generate transfer ID
        $transfer_id = generateUUID();

        // Insert stock transfer header
        $sql = "INSERT INTO stock_transfers (
            transfer_id,
            from_location_id,
            to_location_id,
            transfer_date,
            transfer_status,
            notes
        ) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $transfer_id,
            $from_location_id,
            $to_location_id,
            $transfer_date,
            $transfer_status,
            $notes ?: null
        ]);

        if ($result) {
            // Insert transfer items and create transfer_out transaction
            foreach ($items as $item) {
                $product_id = $item['product_id'] ?? '';
                $quantity = floatval($item['quantity'] ?? 0);
                $uom_id = $item['uom_id'] ?? '';

                if (empty($product_id) || $quantity <= 0 || empty($uom_id)) {
                    continue;
                }

                // Check stock availability at source location
                $stmt = $pdo->prepare("
                    SELECT COALESCE(quantity, 0) as current_stock
                    FROM product_stock_per_location
                    WHERE product_id = ? AND location_id = ?
                ");
                $stmt->execute([$product_id, $from_location_id]);
                $from_stock = $stmt->fetch();

                if (!$from_stock || $from_stock['current_stock'] < $quantity) {
                    throw new Exception("Insufficient stock for product. Current stock: " . ($from_stock ? $from_stock['current_stock'] : 0));
                }

                // Create transfer_out transaction immediately
                $new_from_stock = $from_stock['current_stock'] - $quantity;
                $txn_id_out = generateUUID();
                $stmt = $pdo->prepare("
                    INSERT INTO stock_transactions (txn_id, product_id, location_id, transaction_type, quantity, uom_id, stock_before, stock_after, reference_type, reference_id, reference_number, notes, transaction_date)
                    VALUES (?, ?, ?, 'transfer_out', ?, ?, ?, ?, 'TRANSFER', ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $txn_id_out,
                    $product_id,
                    $from_location_id,
                    $quantity,
                    $uom_id,
                    $from_stock['current_stock'],
                    $new_from_stock,
                    $transfer_id,
                    substr($transfer_id, 0, 8),
                    'Stock transfer out - ' . ($transfer_status === 'completed' ? 'completed' : 'in transit'),
                    $transfer_date
                ]);

                // Deduct from source location
                $stmt = $pdo->prepare("UPDATE product_stock_per_location SET quantity = ? WHERE product_id = ? AND location_id = ?");
                $stmt->execute([$new_from_stock, $product_id, $from_location_id]);

                // If status is completed, also create transfer_in transaction and add to destination
                $txn_id_in = null;
                if ($transfer_status === 'completed') {
                    $stmt = $pdo->prepare("
                        SELECT COALESCE(quantity, 0) as current_stock
                        FROM product_stock_per_location
                        WHERE product_id = ? AND location_id = ?
                    ");
                    $stmt->execute([$product_id, $to_location_id]);
                    $to_stock = $stmt->fetch();

                    $new_to_stock = ($to_stock ? $to_stock['current_stock'] : 0) + $quantity;

                    if ($to_stock) {
                        $stmt = $pdo->prepare("UPDATE product_stock_per_location SET quantity = ? WHERE product_id = ? AND location_id = ?");
                        $stmt->execute([$new_to_stock, $product_id, $to_location_id]);
                    } else {
                        $sql = "INSERT INTO product_stock_per_location (product_id, location_id, quantity) VALUES (?, ?, ?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$product_id, $to_location_id, $new_to_stock]);
                    }

                    $txn_id_in = generateUUID();
                    $stmt = $pdo->prepare("
                        INSERT INTO stock_transactions (txn_id, product_id, location_id, transaction_type, quantity, uom_id, stock_before, stock_after, reference_type, reference_id, reference_number, notes, transaction_date)
                        VALUES (?, ?, ?, 'transfer_in', ?, ?, ?, ?, 'TRANSFER', ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $txn_id_in,
                        $product_id,
                        $to_location_id,
                        $quantity,
                        $uom_id,
                        $to_stock ? $to_stock['current_stock'] : 0,
                        $new_to_stock,
                        $transfer_id,
                        substr($transfer_id, 0, 8),
                        'Stock transfer in - received',
                        $transfer_date
                    ]);
                }

                // Insert transfer item with linked transaction IDs
                $sql = "INSERT INTO stock_transfer_items (transfer_id, product_id, quantity, uom_id, out_txn_id, in_txn_id) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$transfer_id, $product_id, $quantity, $uom_id, $txn_id_out, $txn_id_in]);

                // Update product's total stock
                $stmt = $pdo->prepare("
                    SELECT COALESCE(SUM(quantity), 0) as total_stock
                    FROM product_stock_per_location
                    WHERE product_id = ?
                ");
                $stmt->execute([$product_id]);
                $total_stock = $stmt->fetchColumn();

                $stmt = $pdo->prepare("UPDATE products SET current_stock = ? WHERE product_id = ?");
                $stmt->execute([$total_stock, $product_id]);
            }

            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Stock transfer created successfully']);
        } else {
            throw new Exception("Failed to create stock transfer");
        }

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'edit_stock_transfer') {
    try {
        // Get form data
        $transfer_id = $_POST['transfer_id'] ?? '';
        $from_location_id = $_POST['from_location_id'] ?? '';
        $to_location_id = $_POST['to_location_id'] ?? '';
        $transfer_date = $_POST['transfer_date'] ?? '';
        $transfer_status = $_POST['transfer_status'] ?? 'pending';
        $notes = trim($_POST['notes'] ?? '');
        $items = $_POST['items'] ?? [];

        // Validate required fields
        if (empty($transfer_id) || empty($from_location_id) || empty($to_location_id) || empty($transfer_date) || empty($items)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        if ($from_location_id === $to_location_id) {
            echo json_encode(['success' => false, 'message' => 'From and To locations cannot be the same']);
            exit;
        }

        $pdo->beginTransaction();

        // Get old transfer to check if status is changing
        $stmt = $pdo->prepare("SELECT * FROM stock_transfers WHERE transfer_id = ?");
        $stmt->execute([$transfer_id]);
        $old_transfer = $stmt->fetch();

        if (!$old_transfer) {
            throw new Exception("Transfer not found");
        }

        // Prevent editing completed transfers
        if ($old_transfer['transfer_status'] === 'completed') {
            throw new Exception("Cannot edit completed transfers");
        }

        // Update stock transfer header
        $sql = "UPDATE stock_transfers SET
            from_location_id = ?,
            to_location_id = ?,
            transfer_date = ?,
            transfer_status = ?,
            notes = ?
        WHERE transfer_id = ?";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $from_location_id,
            $to_location_id,
            $transfer_date,
            $transfer_status,
            $notes ?: null,
            $transfer_id
        ]);

        if ($result) {
            // Get existing transfer items with their out_txn_id
            $stmt = $pdo->prepare("SELECT * FROM stock_transfer_items WHERE transfer_id = ?");
            $stmt->execute([$transfer_id]);
            $existing_items = $stmt->fetchAll();

            // Delete existing transfer items
            $stmt = $pdo->prepare("DELETE FROM stock_transfer_items WHERE transfer_id = ?");
            $stmt->execute([$transfer_id]);

            // Insert new transfer items
            foreach ($items as $item) {
                $product_id = $item['product_id'] ?? '';
                $quantity = floatval($item['quantity'] ?? 0);
                $uom_id = $item['uom_id'] ?? '';

                if (empty($product_id) || $quantity <= 0 || empty($uom_id)) {
                    continue;
                }

                // Find existing item to get out_txn_id
                $existing_item = null;
                foreach ($existing_items as $ei) {
                    if ($ei['product_id'] === $product_id) {
                        $existing_item = $ei;
                        break;
                    }
                }

                $out_txn_id = $existing_item ? $existing_item['out_txn_id'] : null;
                $in_txn_id = null;

                // If status changed to completed, create transfer_in transaction
                if ($old_transfer['transfer_status'] !== 'completed' && $transfer_status === 'completed') {
                    // Add to destination location
                    $stmt = $pdo->prepare("
                        SELECT COALESCE(quantity, 0) as current_stock
                        FROM product_stock_per_location
                        WHERE product_id = ? AND location_id = ?
                    ");
                    $stmt->execute([$product_id, $to_location_id]);
                    $to_stock = $stmt->fetch();

                    $new_to_stock = ($to_stock ? $to_stock['current_stock'] : 0) + $quantity;

                    if ($to_stock) {
                        $stmt = $pdo->prepare("UPDATE product_stock_per_location SET quantity = ? WHERE product_id = ? AND location_id = ?");
                        $stmt->execute([$new_to_stock, $product_id, $to_location_id]);
                    } else {
                        $sql = "INSERT INTO product_stock_per_location (product_id, location_id, quantity) VALUES (?, ?, ?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$product_id, $to_location_id, $new_to_stock]);
                    }

                    // Create transfer_in transaction
                    $in_txn_id = generateUUID();
                    $stmt = $pdo->prepare("
                        INSERT INTO stock_transactions (txn_id, product_id, location_id, transaction_type, quantity, uom_id, stock_before, stock_after, reference_type, reference_id, reference_number, notes, transaction_date)
                        VALUES (?, ?, ?, 'transfer_in', ?, ?, ?, ?, 'TRANSFER', ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $in_txn_id,
                        $product_id,
                        $to_location_id,
                        $quantity,
                        $uom_id,
                        $to_stock ? $to_stock['current_stock'] : 0,
                        $new_to_stock,
                        $transfer_id,
                        substr($transfer_id, 0, 8),
                        'Stock transfer in - received',
                        $transfer_date
                    ]);

                    // Update product's total stock
                    $stmt = $pdo->prepare("
                        SELECT COALESCE(SUM(quantity), 0) as total_stock
                        FROM product_stock_per_location
                        WHERE product_id = ?
                    ");
                    $stmt->execute([$product_id]);
                    $total_stock = $stmt->fetchColumn();

                    $stmt = $pdo->prepare("UPDATE products SET current_stock = ? WHERE product_id = ?");
                    $stmt->execute([$total_stock, $product_id]);
                }

                // Insert transfer item with linked transaction IDs
                $sql = "INSERT INTO stock_transfer_items (transfer_id, product_id, quantity, uom_id, out_txn_id, in_txn_id) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$transfer_id, $product_id, $quantity, $uom_id, $out_txn_id, $in_txn_id]);
            }

            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Stock transfer updated successfully']);
        } else {
            throw new Exception("Failed to update stock transfer");
        }

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'delete_stock_transfer') {
    try {
        $transfer_id = $_POST['transfer_id'] ?? '';

        if (empty($transfer_id)) {
            throw new Exception("Transfer ID is required");
        }

        $pdo->beginTransaction();

        // Check if transfer exists and get its status
        $stmt = $pdo->prepare("SELECT * FROM stock_transfers WHERE transfer_id = ?");
        $stmt->execute([$transfer_id]);
        $transfer = $stmt->fetch();

        if (!$transfer) {
            throw new Exception("Transfer not found");
        }

        // Prevent deleting completed transfers
        if ($transfer['transfer_status'] === 'completed') {
            throw new Exception("Cannot delete completed transfers. Create a reversal transfer instead.");
        }

        // Get transfer items to reverse transfer_out transactions
        $stmt = $pdo->prepare("SELECT * FROM stock_transfer_items WHERE transfer_id = ?");
        $stmt->execute([$transfer_id]);
        $transfer_items = $stmt->fetchAll();

        // Reverse transfer_out transactions for each item
        foreach ($transfer_items as $item) {
            if ($item['out_txn_id']) {
                // Reverse the transfer_out transaction by creating a transfer_in at source
                $stmt = $pdo->prepare("SELECT * FROM stock_transactions WHERE txn_id = ?");
                $stmt->execute([$item['out_txn_id']]);
                $out_txn = $stmt->fetch();

                if ($out_txn) {
                    // Get current stock at source location
                    $stmt = $pdo->prepare("
                        SELECT COALESCE(quantity, 0) as current_stock
                        FROM product_stock_per_location
                        WHERE product_id = ? AND location_id = ?
                    ");
                    $stmt->execute([$out_txn['product_id'], $out_txn['location_id']]);
                    $stock = $stmt->fetch();
                    $stock_before = $stock ? $stock['current_stock'] : 0;

                    // Calculate new stock (reverse the transfer_out)
                    $stock_after = $stock_before + $out_txn['quantity'];

                    // Create reverse transaction (transfer_in at source to undo transfer_out)
                    $reverse_txn_id = generateUUID();
                    $stmt = $pdo->prepare("
                        INSERT INTO stock_transactions (txn_id, product_id, location_id, transaction_type, quantity, uom_id, stock_before, stock_after, reference_type, reference_id, reference_number, notes, transaction_date)
                        VALUES (?, ?, ?, 'transfer_in', ?, ?, ?, ?, 'TRANSFER_REVERSAL', ?, ?, ?, NOW())
                    ");
                    $stmt->execute([
                        $reverse_txn_id,
                        $out_txn['product_id'],
                        $out_txn['location_id'],
                        $out_txn['quantity'],
                        $out_txn['uom_id'],
                        $stock_before,
                        $stock_after,
                        $transfer_id,
                        'REV-' . substr($transfer_id, 0, 8),
                        'Transfer deleted - reversing transfer_out'
                    ]);

                    // Update stock at source location
                    if ($stock) {
                        $stmt = $pdo->prepare("UPDATE product_stock_per_location SET quantity = ? WHERE product_id = ? AND location_id = ?");
                        $stmt->execute([$stock_after, $out_txn['product_id'], $out_txn['location_id']]);
                    } else {
                        $sql = "INSERT INTO product_stock_per_location (product_id, location_id, quantity) VALUES (?, ?, ?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$out_txn['product_id'], $out_txn['location_id'], $stock_after]);
                    }

                    // Update product's total stock
                    $stmt = $pdo->prepare("
                        SELECT COALESCE(SUM(quantity), 0) as total_stock
                        FROM product_stock_per_location
                        WHERE product_id = ?
                    ");
                    $stmt->execute([$out_txn['product_id']]);
                    $total_stock = $stmt->fetchColumn();

                    $stmt = $pdo->prepare("UPDATE products SET current_stock = ? WHERE product_id = ?");
                    $stmt->execute([$total_stock, $out_txn['product_id']]);

                    // Delete the original transfer_out transaction
                    $stmt = $pdo->prepare("DELETE FROM stock_transactions WHERE txn_id = ?");
                    $stmt->execute([$item['out_txn_id']]);
                }
            }
        }

        // Delete transfer items first (due to foreign key constraint)
        $stmt = $pdo->prepare("DELETE FROM stock_transfer_items WHERE transfer_id = ?");
        $stmt->execute([$transfer_id]);

        // Delete stock transfer header
        $stmt = $pdo->prepare("DELETE FROM stock_transfers WHERE transfer_id = ?");
        $stmt->execute([$transfer_id]);

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Stock transfer deleted successfully']);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'add_inventory_sale') {
    try {
        // Get form data
        $sale_number = $_POST['sale_number'] ?? '';
        $property_id = $_POST['property_id'] ?? '';
        $sale_type = $_POST['sale_type'] ?? '';
        $customer_name = trim($_POST['customer_name'] ?? '');
        $sale_date = $_POST['sale_date'] ?? '';
        $total_amount = floatval($_POST['total_amount'] ?? 0);
        $payment_status = $_POST['payment_status'] ?? 'unpaid';
        $notes = trim($_POST['notes'] ?? '');

        // Validate required fields
        if (empty($sale_number) || empty($property_id) || empty($sale_type) || $total_amount <= 0 || empty($sale_date)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        $pdo->beginTransaction();

        // Generate sale ID
        $sale_id = generateUUID();

        // Insert inventory sale
        $sql = "INSERT INTO inventory_sales (
            sale_id,
            sale_number,
            property_id,
            sale_type,
            customer_name,
            sale_date,
            total_amount,
            payment_status,
            notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $sale_id,
            $sale_number,
            $property_id,
            $sale_type,
            $customer_name ?: null,
            $sale_date,
            $total_amount,
            $payment_status,
            $notes ?: null
        ]);

        if ($result) {
            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Inventory sale created successfully']);
        } else {
            throw new Exception("Failed to create inventory sale");
        }

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'edit_inventory_sale') {
    try {
        // Get form data
        $sale_id = $_POST['sale_id'] ?? '';
        $sale_number = $_POST['sale_number'] ?? '';
        $property_id = $_POST['property_id'] ?? '';
        $sale_type = $_POST['sale_type'] ?? '';
        $customer_name = trim($_POST['customer_name'] ?? '');
        $sale_date = $_POST['sale_date'] ?? '';
        $total_amount = floatval($_POST['total_amount'] ?? 0);
        $payment_status = $_POST['payment_status'] ?? 'unpaid';
        $notes = trim($_POST['notes'] ?? '');

        // Validate required fields
        if (empty($sale_id) || empty($sale_number) || empty($property_id) || empty($sale_type) || $total_amount <= 0 || empty($sale_date)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        $pdo->beginTransaction();

        // Update the inventory sale
        $sql = "UPDATE inventory_sales SET
            sale_number = ?,
            property_id = ?,
            sale_type = ?,
            customer_name = ?,
            sale_date = ?,
            total_amount = ?,
            payment_status = ?,
            notes = ?
        WHERE sale_id = ?";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $sale_number,
            $property_id,
            $sale_type,
            $customer_name ?: null,
            $sale_date,
            $total_amount,
            $payment_status,
            $notes ?: null,
            $sale_id
        ]);

        if ($result) {
            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Inventory sale updated successfully']);
        } else {
            throw new Exception("Failed to update inventory sale");
        }

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'delete_inventory_sale') {
    try {
        $sale_id = $_POST['sale_id'] ?? '';

        if (empty($sale_id)) {
            throw new Exception("Sale ID is required");
        }

        $pdo->beginTransaction();

        // Delete the inventory sale
        $stmt = $pdo->prepare("DELETE FROM inventory_sales WHERE sale_id = ?");
        $stmt->execute([$sale_id]);

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Inventory sale deleted successfully']);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>
