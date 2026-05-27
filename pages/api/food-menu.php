<?php
/**
 * Food Menu API - Public endpoint for menu data
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once '../../config/db.php';

$action = $_GET['action'] ?? 'menu';
$propertyId = $_GET['property_id'] ?? '';

try {
    switch ($action) {
        case 'categories':
            // Fetch active categories for property
            if (empty($propertyId)) {
                $sql = "
                    SELECT category_id, category_name, description, icon_url 
                    FROM food_categories 
                    WHERE (property_id IS NULL OR property_id = '') 
                    AND is_active = 1 
                    ORDER BY sort_order, category_name
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            } else {
                $sql = "
                    SELECT category_id, category_name, description, icon_url 
                    FROM food_categories 
                    WHERE (property_id = ? OR property_id IS NULL OR property_id = '') 
                    AND is_active = 1 
                    ORDER BY sort_order, category_name
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$propertyId]);
            }
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'data' => $categories]);
            break;

        case 'menu':
            // Fetch available menu items with categories
            // If no property selected, show items with NULL or empty property_id (all properties)
            if (empty($propertyId)) {
                $sql = "
                    SELECT 
                        mi.item_id,
                        mi.item_name,
                        mi.description,
                        mi.price,
                        mi.item_code,
                        mi.category_id,
                        fc.category_name,
                        mii.image_url,
                        mii.thumbnail_url
                    FROM menu_items mi
                    JOIN food_categories fc ON mi.category_id = fc.category_id
                    LEFT JOIN menu_item_images mii ON mi.item_id = mii.item_id AND mii.is_cover = 1
                    WHERE (mi.property_id IS NULL OR mi.property_id = '')
                    AND mi.is_active = 1 
                    AND mi.is_available = 1
                    AND fc.is_active = 1
                    ORDER BY fc.sort_order, mi.sort_order, mi.item_name
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            } else {
                $sql = "
                    SELECT 
                        mi.item_id,
                        mi.item_name,
                        mi.description,
                        mi.price,
                        mi.item_code,
                        mi.category_id,
                        fc.category_name,
                        mii.image_url,
                        mii.thumbnail_url
                    FROM menu_items mi
                    JOIN food_categories fc ON mi.category_id = fc.category_id
                    LEFT JOIN menu_item_images mii ON mi.item_id = mii.item_id AND mii.is_cover = 1
                    WHERE (mi.property_id = ? OR mi.property_id IS NULL OR mi.property_id = '')
                    AND mi.is_active = 1 
                    AND mi.is_available = 1
                    AND fc.is_active = 1
                    ORDER BY fc.sort_order, mi.sort_order, mi.item_name
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$propertyId]);
            }
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'data' => $items]);
            break;

        case 'rooms':
            // Fetch available rooms for room service
            // Query rooms with reserved/checked_in status and confirmed reservations
            if (empty($propertyId)) {
                $sql = "
                    SELECT DISTINCT r.room_id, r.room_number, r.room_name, rc.category_name, p.property_name,
                           res.reservation_code, res.customer_user_id, res.walkin_first_name, res.walkin_last_name,
                           rr.notes,
                           CONCAT(res.walkin_first_name, ' ', res.walkin_last_name) as guest_name
                    FROM rooms r
                    JOIN room_categories rc ON r.category_id = rc.category_id
                    JOIN properties p ON r.property_id = p.property_id
                    JOIN reservation_rooms rr ON r.room_id = rr.room_id
                    JOIN reservations res ON rr.reservation_id = res.reservation_id
                    WHERE rr.room_status IN ('reserved', 'checked_in')
                    AND res.reservation_status IN ('confirmed', 'checked_in')
                    AND r.is_active = 1
                    AND p.is_active = 1
                    ORDER BY p.property_name, r.room_number
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            } else {
                $sql = "
                    SELECT DISTINCT r.room_id, r.room_number, r.room_name, rc.category_name, p.property_name,
                           res.reservation_code, res.customer_user_id, res.walkin_first_name, res.walkin_last_name,
                           rr.notes,
                           CONCAT(res.walkin_first_name, ' ', res.walkin_last_name) as guest_name
                    FROM rooms r
                    JOIN room_categories rc ON r.category_id = rc.category_id
                    JOIN properties p ON r.property_id = p.property_id
                    JOIN reservation_rooms rr ON r.room_id = rr.room_id
                    JOIN reservations res ON rr.reservation_id = res.reservation_id
                    WHERE r.property_id = ?
                    AND rr.room_status IN ('reserved', 'checked_in')
                    AND res.reservation_status IN ('confirmed', 'checked_in')
                    AND r.is_active = 1
                    ORDER BY r.room_number
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$propertyId]);
            }
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'data' => $rooms]);
            break;

        case 'tables':
            // Fetch available dining tables for dine-in orders
            if (empty($propertyId)) {
                $sql = "
                    SELECT table_id, table_number, table_name, capacity, location, is_occupied
                    FROM dining_tables
                    WHERE is_active = 1
                    AND (property_id IS NULL OR property_id = '')
                    ORDER BY sort_order, table_number
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            } else {
                $sql = "
                    SELECT table_id, table_number, table_name, capacity, location, is_occupied
                    FROM dining_tables
                    WHERE (property_id = ? OR property_id IS NULL OR property_id = '')
                    AND is_active = 1
                    ORDER BY sort_order, table_number
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$propertyId]);
            }
            $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'data' => $tables]);
            break;

        case 'settings':
            // Fetch food canteen settings (tax rate, etc.)
            $sql = "SELECT setting_key, setting_value FROM system_settings WHERE setting_group = 'food_canteen' AND is_public = 1";
            $stmt = $pdo->query($sql);
            $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            // Debug logging
            error_log("Food settings from DB: " . json_encode($settings));
            
            // Check if food_tax_rate_percent exists and is valid
            $taxRate = 12; // default
            if (isset($settings['food_tax_rate_percent']) && is_numeric($settings['food_tax_rate_percent'])) {
                $taxRate = floatval($settings['food_tax_rate_percent']);
            }
            
            $response = [
                'tax_rate_percent' => $taxRate,
                'currency' => 'PHP',
                'currency_symbol' => '₱',
                '_debug_settings' => $settings // temporary debug info
            ];
            
            echo json_encode(['success' => true, 'data' => $response]);
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
} catch (PDOException $e) {
    error_log("Food menu API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
