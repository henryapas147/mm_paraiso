<?php
/**
 * Room Categories API
 * Returns room categories for a specific property with pricing details
 */

require_once '../../config/db.php';


header('Content-Type: application/json');

// Check if property_id is provided
$propertyId = $_GET['property_id'] ?? null;

if (empty($propertyId)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Property ID is required']);
    exit;
}

try {
    // Fetch room categories for the property
    $stmt = $pdo->prepare("
        SELECT 
            category_id,
            category_code,
            category_name,
            description,
            base_price_day_use,
            base_price_per_night,
            base_price_per_hour,
            minimum_hours,
            max_adult_capacity,
            max_child_capacity,
            max_total_capacity,
            extra_adult_fee,
            extra_child_fee,
            extra_senior_fee,
            floor_area_sqm,
            bed_configuration,
            view_type,
            allows_day_use,
            allows_overnight,
            allows_hourly
        FROM room_categories
        WHERE property_id = ?
        AND is_active = 1
        ORDER BY base_price_day_use ASC, category_name ASC
    ");
    $stmt->execute([$propertyId]);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch booking type settings (property-specific or global)
    $settingsStmt = $pdo->prepare("
        SELECT 
            hourly_min_hours,
            hourly_max_hours,
            overnight_min_hours,
            overnight_requires_midnight,
            day_use_max_hours,
            hourly_presets,
            hourly_rates
        FROM booking_type_settings
        WHERE (property_id = ? OR property_id IS NULL)
        AND is_active = 1
        ORDER BY property_id DESC
        LIMIT 1
    ");
    $settingsStmt->execute([$propertyId]);
    $bookingSettings = $settingsStmt->fetch(PDO::FETCH_ASSOC);

    // Default settings if none found
    if (!$bookingSettings) {
        $bookingSettings = [
            'hourly_min_hours' => 3,
            'hourly_max_hours' => 12,
            'overnight_min_hours' => 12,
            'overnight_requires_midnight' => 1,
            'day_use_max_hours' => 12,
            'hourly_presets' => '[3,4,5,6,7]',
            'hourly_rates' => '{"3": 500.00, "4": 650.00, "5": 800.00, "6": 950.00, "7": 1100.00}'
        ];
    }

    // Parse hourly presets JSON
    $hourlyPresets = json_decode($bookingSettings['hourly_presets'] ?? '[3,4,5,6,7]', true);
    if (!is_array($hourlyPresets)) {
        $hourlyPresets = [3, 4, 5, 6, 7];
    }

    // Parse hourly rates JSON
    $hourlyRates = json_decode($bookingSettings['hourly_rates'] ?? '{}', true);
    if (!is_array($hourlyRates)) {
        $hourlyRates = [];
    }

    // Format prices for display
    $formattedCategories = array_map(function($category) {
        return [
            'category_id' => $category['category_id'],
            'category_code' => $category['category_code'],
            'category_name' => $category['category_name'],
            'description' => $category['description'],
            'base_price_day_use' => floatval($category['base_price_day_use']),
            'base_price_per_night' => floatval($category['base_price_per_night']),
            'base_price_per_hour' => floatval($category['base_price_per_hour']),
            'minimum_hours' => intval($category['minimum_hours']),
            'max_adult_capacity' => intval($category['max_adult_capacity']),
            'max_child_capacity' => intval($category['max_child_capacity']),
            'max_total_capacity' => intval($category['max_total_capacity']),
            'extra_adult_fee' => floatval($category['extra_adult_fee']),
            'extra_child_fee' => floatval($category['extra_child_fee']),
            'extra_senior_fee' => floatval($category['extra_senior_fee']),
            'floor_area_sqm' => $category['floor_area_sqm'] ? floatval($category['floor_area_sqm']) : null,
            'bed_configuration' => $category['bed_configuration'],
            'view_type' => $category['view_type'],
            'allows_day_use' => boolval($category['allows_day_use']),
            'allows_overnight' => boolval($category['allows_overnight']),
            'allows_hourly' => boolval($category['allows_hourly']),
            // Formatted display values
            'base_price_day_use_formatted' => '₱' . number_format($category['base_price_day_use'], 2),
            'capacity_display' => $category['max_adult_capacity'] . ' Adults, ' . $category['max_child_capacity'] . ' Children',
            'extra_fees_display' => ($category['extra_adult_fee'] > 0 || $category['extra_child_fee'] > 0 || $category['extra_senior_fee'] > 0)
                ? 'Extra: Adult +₱' . number_format($category['extra_adult_fee'], 2) . ', Child +₱' . number_format($category['extra_child_fee'], 2) . ', Senior +₱' . number_format($category['extra_senior_fee'], 2)
                : null
        ];
    }, $categories);

    echo json_encode([
        'success' => true,
        'categories' => $formattedCategories,
        'count' => count($categories),
        'booking_type_settings' => [
            'hourly_min_hours' => intval($bookingSettings['hourly_min_hours']),
            'hourly_max_hours' => intval($bookingSettings['hourly_max_hours']),
            'overnight_min_hours' => intval($bookingSettings['overnight_min_hours']),
            'overnight_requires_midnight' => boolval($bookingSettings['overnight_requires_midnight']),
            'day_use_max_hours' => intval($bookingSettings['day_use_max_hours']),
            'hourly_presets' => $hourlyPresets,
            'hourly_rates' => $hourlyRates
        ]
    ]);

} catch (PDOException $e) {
    error_log("Room categories API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error occurred']);
}
