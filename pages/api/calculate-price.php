<?php
/**
 * Public API Endpoint: Calculate Dynamic Price
 * Returns base price with seasonal rates and pricing rules applied
 */

require_once '../../config/db.php';

header('Content-Type: application/json');

// Get parameters
$propertyId = $_GET['property_id'] ?? null;
$categoryId = $_GET['category_id'] ?? null;
$baseAmount = floatval($_GET['base_amount'] ?? 0);
$checkInDate = $_GET['check_in_date'] ?? null;
$bookingType = $_GET['booking_type'] ?? 'day_use';

if (!$propertyId || !$baseAmount || !$checkInDate) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'property_id, base_amount, and check_in_date are required']);
    exit;
}

try {
    $breakdown = [];
    $finalAmount = $baseAmount;
    $currentAmount = $baseAmount;

    // 1. Check for seasonal rates
    $seasonStmt = $pdo->prepare("
        SELECT season_id, season_name, rate_multiplier, applies_to
        FROM seasonal_rates
        WHERE property_id = ?
        AND is_active = 1
        AND date_start <= ?
        AND date_end >= ?
        AND (applies_to IS NULL OR applies_to = ?)
        ORDER BY rate_multiplier DESC
        LIMIT 1
    ");
    $seasonStmt->execute([$propertyId, $checkInDate, $checkInDate, $categoryId]);
    $season = $seasonStmt->fetch(PDO::FETCH_ASSOC);

    if ($season && $season['rate_multiplier'] != 1.0) {
        $multiplier = floatval($season['rate_multiplier']);
        $adjustedAmount = $currentAmount * $multiplier;
        $difference = $adjustedAmount - $currentAmount;

        $breakdown[] = [
            'type' => 'seasonal_rate',
            'name' => $season['season_name'],
            'base_amount' => $currentAmount,
            'multiplier' => $multiplier,
            'adjustment' => $difference,
            'final_amount' => $adjustedAmount
        ];

        $currentAmount = $adjustedAmount;
        $finalAmount = $adjustedAmount;
    }

    // 2. Check for pricing rules
    $dayOfWeek = strtolower(date('D', strtotime($checkInDate))); // mon, tue, etc.
    $rulesStmt = $pdo->prepare("
        SELECT rule_id, rule_name, rule_type, rule_value, applies_to
        FROM pricing_rules
        WHERE property_id = ?
        AND is_active = 1
        AND (category_id IS NULL OR category_id = ?)
        AND (applies_to = 'all' OR applies_to = ?)
        AND (date_start IS NULL OR date_start <= ?)
        AND (date_end IS NULL OR date_end >= ?)
        AND (day_of_week IS NULL OR FIND_IN_SET(?, day_of_week))
        ORDER BY priority DESC
    ");
    $rulesStmt->execute([$propertyId, $categoryId, $bookingType, $checkInDate, $checkInDate, $dayOfWeek]);
    $rules = $rulesStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rules as $rule) {
        $ruleValue = floatval($rule['rule_value']);
        $adjustment = 0;
        $multiplier = null;

        switch ($rule['rule_type']) {
            case 'percentage_add':
                $multiplier = 1 + ($ruleValue / 100);
                $adjustedAmount = $currentAmount * $multiplier;
                $adjustment = $adjustedAmount - $currentAmount;
                break;
            case 'percentage_discount':
                $adjustment = $currentAmount * ($ruleValue / 100);
                $adjustedAmount = $currentAmount - $adjustment;
                break;
            case 'fixed_add':
                $adjustment = $ruleValue;
                $adjustedAmount = $currentAmount + $adjustment;
                break;
            case 'fixed_discount':
                $adjustment = $ruleValue;
                $adjustedAmount = $currentAmount - $adjustment;
                break;
            default:
                continue 2; // Skip unknown rule types
        }

        if ($adjustedAmount < 0) {
            $adjustedAmount = 0;
        }

        $breakdown[] = [
            'type' => 'pricing_rule',
            'rule_id' => $rule['rule_id'],
            'name' => $rule['rule_name'],
            'rule_type' => $rule['rule_type'],
            'base_amount' => $currentAmount,
            'multiplier' => $multiplier,
            'adjustment' => $adjustment,
            'final_amount' => $adjustedAmount
        ];

        $currentAmount = $adjustedAmount;
        $finalAmount = $adjustedAmount;
    }

    echo json_encode([
        'success' => true,
        'base_amount' => $baseAmount,
        'final_amount' => round($finalAmount, 2),
        'total_adjustment' => round($finalAmount - $baseAmount, 2),
        'breakdown' => $breakdown
    ]);

} catch (PDOException $e) {
    error_log("Price calculation error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error occurred']);
}
