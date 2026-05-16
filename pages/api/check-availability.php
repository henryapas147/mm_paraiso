<?php
/**
 * Check Availability API
 * Returns available rooms for given date range
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once '../../config/db.php';

$propertyId = $_GET['property_id'] ?? null;
$checkinDate = $_GET['checkin_date'] ?? null;
$checkinTime = $_GET['checkin_time'] ?? '14:00';
$checkoutDate = $_GET['checkout_date'] ?? null;
$checkoutTime = $_GET['checkout_time'] ?? '12:00';
$guests = $_GET['guests'] ?? 1;

if (!$checkinDate) {
    echo json_encode(['success' => false, 'message' => 'Check-in date required']);
    exit;
}

try {
    // Build simplified query without complex subqueries for debugging
    $sql = "
        SELECT 
            r.room_id,
            r.room_number,
            r.room_name,
            r.floor_number,
            r.building_block,
            r.room_status,
            r.override_price_per_night,
            r.override_price_per_hour,
            r.override_price_day_use,
            r.category_id,
            rc.category_name,
            rc.base_price_per_night,
            rc.base_price_per_hour,
            rc.base_price_day_use,
            rc.max_adult_capacity,
            rc.max_child_capacity,
            rc.max_total_capacity,
            rc.floor_area_sqm,
            rc.bed_configuration,
            rc.view_type,
            rc.pet_friendly,
            rc.allows_hourly,
            rc.allows_overnight,
            rc.allows_day_use,
            p.property_id,
            p.property_name,
            p.property_code,
            p.property_type,
            p.address_line1,
            brg.barangay_name,
            cm.city_municipality_name,
            prov.province_name,
            (
                SELECT image_url 
                FROM room_images 
                WHERE room_id = r.room_id 
                AND is_active = 1 
                AND is_cover = 1 
                LIMIT 1
            ) as room_image
        FROM rooms r
        INNER JOIN room_categories rc ON r.category_id = rc.category_id
        INNER JOIN properties p ON r.property_id = p.property_id
        LEFT JOIN psgc_barangays brg ON p.barangay_code = brg.barangay_code COLLATE utf8mb4_unicode_ci
        LEFT JOIN psgc_cities_municipalities cm ON p.city_municipality_code = cm.city_municipality_code COLLATE utf8mb4_unicode_ci
        LEFT JOIN psgc_provinces prov ON p.province_code = prov.province_code COLLATE utf8mb4_unicode_ci
        WHERE r.is_active = 1
        AND rc.is_active = 1
        AND p.is_active = 1
        AND r.room_status = 'available'
    ";

    $params = [];
    
    if ($propertyId) {
        $sql .= " AND r.property_id = ?";
        $params[] = $propertyId;
    }

    if ($guests) {
        $sql .= " AND rc.max_adult_capacity >= ?";
        $params[] = $guests;
    }

    $sql .= " ORDER BY p.property_name ASC, rc.category_name ASC, r.room_number ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $availableRooms = $rooms;

    echo json_encode([
        'success' => true,
        'data' => $availableRooms,
        'total' => count($availableRooms),
        'search_params' => [
            'property_id' => $propertyId,
            'checkin_date' => $checkinDate,
            'checkout_date' => $checkoutDate,
            'guests' => $guests
        ]
    ]);

} catch (PDOException $e) {
    error_log("Availability check error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
