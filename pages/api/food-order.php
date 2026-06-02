<?php
/**
 * Food Order API - Public endpoint for placing orders
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../../config/db.php';
require_once '../../build/services/EmailService.php';

// Get POST data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid request data']);
    exit;
}

// Validate required fields
$required = ['order_type', 'items'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => "Missing required field: {$field}"]);
        exit;
    }
}

// property_id is required for room_service, optional for dine_in and takeout
// For room_service, property_id can be derived from the room_id
$orderType = $data['order_type'] ?? '';
$propertyId = $data['property_id'] ?? null;
$reservationId = $data['reservation_id'] ?? null;

// If room_service with room_id provided, fetch property_id and reservation_id from room
if ($orderType === 'room_service' && !empty($data['room_id'])) {
    try {
        // Get property_id from room
        if (empty($propertyId)) {
            $stmt = $pdo->prepare("SELECT property_id FROM rooms WHERE room_id = ?");
            $stmt->execute([$data['room_id']]);
            $roomPropertyId = $stmt->fetchColumn();
            if ($roomPropertyId) {
                $propertyId = $roomPropertyId;
            }
        }

        // Get active reservation_id from reservation_rooms for this room
        if (empty($reservationId)) {
            $stmt = $pdo->prepare("
                SELECT rr.reservation_id
                FROM reservation_rooms rr
                INNER JOIN reservations r ON rr.reservation_id = r.reservation_id
                WHERE rr.room_id = ?
                  AND rr.room_status IN ('checked_in', 'reserved')
                  AND r.reservation_status IN ('confirmed', 'checked_in')
                ORDER BY rr.actual_check_in_at DESC, rr.created_at DESC
                LIMIT 1
            ");
            $stmt->execute([$data['room_id']]);
            $roomReservationId = $stmt->fetchColumn();
            if ($roomReservationId) {
                $reservationId = $roomReservationId;
            }
        }
    } catch (Exception $e) {
        error_log("Error fetching property/reservation from room: " . $e->getMessage());
    }
}

if (empty($propertyId) && $orderType === 'room_service') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Property is required for room service orders']);
    exit;
}

if (empty($data['items']) || !is_array($data['items'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Order must contain at least one item']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Generate order number
    $year = date('Y');
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM food_orders WHERE YEAR(created_at) = {$year}");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'] + 1;
    $orderNumber = 'FO-' . $year . str_pad($count, 6, '0', STR_PAD_LEFT);

    // Calculate totals (tax-inclusive pricing)
    $subtotal = 0;
    foreach ($data['items'] as $item) {
        $subtotal += $item['unit_price'] * $item['qty'];
    }
    
    // No separate tax - prices are tax-inclusive
    $taxAmount = 0;
    $totalAmount = $subtotal;

    // Create order
    $orderId = bin2hex(random_bytes(16)); // UUID-like
    
    $sql = "
        INSERT INTO food_orders (
            order_id, property_id, reservation_id, order_number, order_type, table_number, room_id,
            customer_name, order_status, subtotal, tax_amount, total_amount,
            payment_status, notes, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?, ?, 'unpaid', ?, NOW())
    ";
    
    $customerName = null;
    if (isset($_SESSION['user_id'])) {
        // Get customer name from session if logged in
        $stmt = $pdo->prepare("SELECT up.first_name, up.last_name FROM user_profiles up WHERE up.user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($profile) {
            $customerName = trim($profile['first_name'] . ' ' . $profile['last_name']);
        }
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $orderId,
        $propertyId, // Use resolved property_id (from request or room lookup)
        $reservationId, // Use resolved reservation_id (from request or room lookup)
        $orderNumber,
        $data['order_type'],
        $data['table_number'] ?? null,
        $data['room_id'] ?? null,
        $customerName,
        $subtotal,
        $taxAmount,
        $totalAmount,
        $data['notes'] ?? null
    ]);

    // Insert order items and collect data for email
    $itemSql = "
        INSERT INTO food_order_items (
            order_item_id, order_id, item_id, item_name, quantity, unit_price, total_price
        ) VALUES (?, ?, ?, ?, ?, ?, ?)
    ";
    
    $itemStmt = $pdo->prepare($itemSql);
    $emailItems = []; // Collect items for email
    
    foreach ($data['items'] as $item) {
        $itemId = bin2hex(random_bytes(16));
        $totalPrice = $item['unit_price'] * $item['qty'];
        
        // Get item name for snapshot
        $nameStmt = $pdo->prepare("SELECT item_name FROM menu_items WHERE item_id = ?");
        $nameStmt->execute([$item['item_id']]);
        $itemName = $nameStmt->fetchColumn() ?: 'Unknown Item';
        
        $itemStmt->execute([
            $itemId,
            $orderId,
            $item['item_id'],
            $itemName,
            $item['qty'],
            $item['unit_price'],
            $totalPrice
        ]);
        
        // Collect for email
        $emailItems[] = [
            'item_name' => $itemName,
            'quantity' => $item['qty'],
            'unit_price' => $item['unit_price'],
            'total_price' => $totalPrice
        ];
    }

    $pdo->commit();

    // Send confirmation email if customer email provided
    if (!empty($data['customer_email'])) {
        try {
            $emailService = new \App\Services\EmailService($pdo);
            
            // Get table/room info for email
            $tableNumber = $data['table_number'] ?? null;
            $roomNumber = null;
            if (!empty($data['room_id'])) {
                $roomStmt = $pdo->prepare("SELECT room_number FROM rooms WHERE room_id = ?");
                $roomStmt->execute([$data['room_id']]);
                $roomNumber = $roomStmt->fetchColumn();
            }
            
            $emailData = [
                'customer_email' => $data['customer_email'],
                'customer_name' => $customerName ?: 'Guest',
                'order_number' => $orderNumber,
                'order_type' => $data['order_type'],
                'table_number' => $tableNumber,
                'room_number' => $roomNumber,
                'items' => $emailItems,
                'total_amount' => $totalAmount,
                'notes' => $data['notes'] ?? null
            ];
            
            $emailResult = $emailService->sendFoodOrderConfirmation($emailData);
            error_log("Food order email result: " . json_encode($emailResult));
        } catch (Exception $e) {
            error_log("Failed to send food order email: " . $e->getMessage());
            // Don't fail the order if email fails
        }
    }

    // Return success with order number
    echo json_encode([
        'success' => true,
        'order_number' => $orderNumber,
        'order_id' => $orderId,
        'total' => $totalAmount
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Food order API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
