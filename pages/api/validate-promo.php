<?php
/**
 * Public API Endpoint: Validate Promo Code
 * Validates promo codes for public reservation pages
 */

require_once '../../config/db.php';

header('Content-Type: application/json');

// Get promo code from request
$promoCode = trim($_GET['promo_code'] ?? '');
$propertyId = $_GET['property_id'] ?? null;
$bookingType = $_GET['booking_type'] ?? 'day_use';
$totalAmount = floatval($_GET['total_amount'] ?? 0);

if (empty($promoCode)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Promo code is required']);
    exit;
}

try {
    // Look up promo code
    $stmt = $pdo->prepare("
        SELECT 
            p.promo_id,
            p.promo_code,
            p.promo_title,
            p.promo_type,
            p.valid_from,
            p.valid_until,
            p.property_id,
            p.discount_id,
            d.discount_code,
            d.discount_type,
            d.discount_value,
            d.usage_limit,
            d.usage_count
        FROM promotions p
        LEFT JOIN discounts d ON p.discount_id = d.discount_id
        WHERE p.promo_code = ?
        AND p.is_active = 1
        AND (p.property_id IS NULL OR p.property_id = ?)
        AND (p.valid_from <= CURDATE() OR p.valid_from IS NULL)
        AND (p.valid_until >= CURDATE() OR p.valid_until IS NULL)
        LIMIT 1
    ");
    $stmt->execute([$promoCode, $propertyId]);
    $promo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$promo) {
        echo json_encode([
            'success' => false,
            'valid' => false,
            'error' => 'Invalid or expired promo code'
        ]);
        exit;
    }

    // Check usage limit
    if ($promo['usage_limit'] && $promo['usage_count'] >= $promo['usage_limit']) {
        echo json_encode([
            'success' => true,
            'valid' => false,
            'error' => 'This promo code has reached its usage limit'
        ]);
        exit;
    }

    // Calculate discount amount
    $discountAmount = 0;
    $discountType = $promo['discount_type'] ?? 'percentage';
    $discountValue = floatval($promo['discount_value'] ?? 0);

    if ($discountType === 'percentage') {
        $discountAmount = $totalAmount * ($discountValue / 100);
    } else if ($discountType === 'fixed') {
        $discountAmount = $discountValue;
    }

    // Cap discount at total amount
    $discountAmount = min($discountAmount, $totalAmount);

    echo json_encode([
        'success' => true,
        'valid' => true,
        'promo' => [
            'promo_id' => $promo['promo_id'],
            'promo_code' => $promo['promo_code'],
            'promo_title' => $promo['promo_title'],
            'promo_type' => $promo['promo_type'],
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'discount_amount' => round($discountAmount, 2),
            'final_amount' => round($totalAmount - $discountAmount, 2)
        ]
    ]);

} catch (PDOException $e) {
    error_log("Promo validation error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error occurred']);
}
