<?php
/**
 * Check Availability API
 * Returns availability status for all properties or specific property
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once '../../config/db.php';

// Support both naming conventions for backward compatibility
$propertyId = $_GET['property_id'] ?? null;
$checkinDate = $_GET['checkin_date'] ?? $_GET['visit_date'] ?? null;
$checkinTime = $_GET['checkin_time'] ?? $_GET['visit_time'] ?? '14:00';
$checkoutDate = $_GET['checkout_date'] ?? null;
$checkoutTime = $_GET['checkout_time'] ?? '12:00';
$guests = $_GET['guests'] ?? 1;

$maxVisitorsPerSlot = 10;

if (!$checkinDate) {
    echo json_encode(['success' => false, 'message' => 'Visit date required']);
    exit;
}

try {
    // Get all active properties if no specific property requested
    if ($propertyId) {
        $propertiesStmt = $pdo->prepare("SELECT property_id, property_name, earliest_check_in, latest_check_out FROM properties WHERE property_id = ? AND is_active = 1");
        $propertiesStmt->execute([$propertyId]);
    } else {
        $propertiesStmt = $pdo->query("SELECT property_id, property_name, earliest_check_in, latest_check_out FROM properties WHERE is_active = 1 ORDER BY property_id");
    }
    $properties = $propertiesStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($properties)) {
        echo json_encode(['success' => false, 'message' => 'No properties available']);
        exit;
    }

    // Collect all property IDs
    $allPropertyIds = array_column($properties, 'property_id');

    // Check for existing bookings at room level across all properties
    $bookedRoomIds = [];
    if (!empty($allPropertyIds)) {
        $overlapCondition = "";
        $params = $allPropertyIds;

        if ($checkoutDate) {
            // For date range bookings (overnight, day_use), check for overlap
            // Overlap if: (existing_start < new_end) AND (existing_end > new_start)
            // Using combined date-time comparison for accuracy
            $overlapCondition = "AND (
                (r.check_in_date < ? OR (r.check_in_date = ? AND r.check_in_time <= ?)) AND
                (r.check_out_date > ? OR (r.check_out_date = ? AND r.check_out_time > ?))
            )";
            $params = array_merge($params, [$checkoutDate, $checkoutDate, $checkoutTime, $checkinDate, $checkinDate, $checkinTime]);
        } else {
            // For hourly bookings on same date, check time overlap
            $overlapCondition = "AND r.check_in_date = ? AND (
                (r.check_in_time < ? AND r.check_out_time > ?) OR
                (r.check_in_time >= ? AND r.check_out_time <= ?)
            )";
            $params = array_merge($params, [$checkinDate, $checkinTime, $checkinTime, $checkinTime, $checkinTime]);
        }

        $placeholders = implode(',', array_fill(0, count($allPropertyIds), '?'));
        $resStmt = $pdo->prepare("
            SELECT DISTINCT rr.room_id
            FROM reservation_rooms rr
            INNER JOIN reservations r ON rr.reservation_id = r.reservation_id
            WHERE r.property_id IN ($placeholders)
            $overlapCondition
            AND r.reservation_status IN ('pending', 'confirmed')
            AND r.booking_type IN ('hourly', 'day_use', 'overnight')
        ");
        $resStmt->execute($params);
        $bookedRoomIds = $resStmt->fetchAll(PDO::FETCH_COLUMN);
    }

    $propertiesData = [];

    foreach ($properties as $property) {
        $propId = $property['property_id'];
        $earliestTime = $property['earliest_check_in'] ?? '08:00:00';
        $latestTime = $property['latest_check_out'] ?? '22:00:00';

        $earliestTimestamp = strtotime($earliestTime);
        $latestTimestamp = strtotime($latestTime);
        $selectedTime = strtotime($checkinTime);

        // Check time validity
        $timeValid = ($selectedTime >= $earliestTimestamp && $selectedTime <= $latestTimestamp);
        $timeError = '';
        if (!$timeValid) {
            $timeError = 'Visit time must be between ' . substr($earliestTime, 0, 5) . ' and ' . substr($latestTime, 0, 5);
        }

        // Check for availability blocks
        $blockStmt = $pdo->prepare("
            SELECT
                rab.block_type,
                rab.reason,
                rab.time_start,
                rab.time_end
            FROM room_availability_blocks rab
            INNER JOIN rooms r ON rab.room_id = r.room_id
            WHERE r.property_id = ?
            AND r.is_active = 1
            AND rab.is_active = 1
            AND rab.date_start <= ?
            AND rab.date_end >= ?
        ");
        $blockStmt->execute([$propId, $checkinDate, $checkinDate]);
        $blocks = $blockStmt->fetchAll(PDO::FETCH_ASSOC);

        $blockData = [];
        foreach ($blocks as $block) {
            $timeInfo = '';
            if ($block['time_start'] && $block['time_end']) {
                $timeInfo = ' (' . substr($block['time_start'], 0, 5) . ' - ' . substr($block['time_end'], 0, 5) . ')';
            }
            $blockData[] = [
                'type' => $block['block_type'],
                'reason' => $block['reason'],
                'time_info' => $timeInfo
            ];
        }

        // Get total active rooms for this property
        $totalRoomsStmt = $pdo->prepare("SELECT COUNT(*) as total FROM rooms WHERE property_id = ? AND is_active = 1 AND room_status = 'available'");
        $totalRoomsStmt->execute([$propId]);
        $totalRooms = $totalRoomsStmt->fetch()['total'] ?? 0;

        // Count booked rooms for this property
        $propertyBookedRooms = [];
        foreach ($bookedRoomIds as $roomId) {
            // Need to check which property this room belongs to
            $roomPropStmt = $pdo->prepare("SELECT property_id FROM rooms WHERE room_id = ?");
            $roomPropStmt->execute([$roomId]);
            $roomProp = $roomPropStmt->fetch();
            if ($roomProp && $roomProp['property_id'] === $propId) {
                $propertyBookedRooms[] = $roomId;
            }
        }
        $bookedRoomsCount = count($propertyBookedRooms);
        $availableRoomsCount = max(0, $totalRooms - $bookedRoomsCount);

        $propertiesData[] = [
            'property_id' => $propId,
            'property_name' => $property['property_name'],
            'time_valid' => $timeValid,
            'time_error' => $timeError,
            'operating_hours' => [
                'earliest' => substr($earliestTime, 0, 5),
                'latest' => substr($latestTime, 0, 5)
            ],
            'blocks' => $blockData,
            'total_rooms' => (int)$totalRooms,
            'booked_rooms' => (int)$bookedRoomsCount,
            'available_rooms' => (int)$availableRoomsCount,
            'is_available' => $timeValid && empty($blockData) && $availableRoomsCount > 0
        ];
    }

    // Collect available property IDs to fetch their rooms
    $availablePropertyIds = array_column(
        array_filter($propertiesData, fn($p) => $p['is_available']),
        'property_id'
    );

    $roomsData = [];
    if (!empty($availablePropertyIds)) {
        $placeholders = implode(',', array_fill(0, count($availablePropertyIds), '?'));
        $excludeRooms = "";
        $roomParams = $availablePropertyIds;

        if (!empty($bookedRoomIds)) {
            $excludePlaceholders = implode(',', array_fill(0, count($bookedRoomIds), '?'));
            $excludeRooms = "AND r.room_id NOT IN ($excludePlaceholders)";
            $roomParams = array_merge($availablePropertyIds, $bookedRoomIds);
        }

        $roomsStmt = $pdo->prepare("
            SELECT
                r.room_id,
                r.property_id,
                r.category_id,
                r.room_number,
                r.room_name,
                r.floor_number,
                r.building_block,
                r.room_status,
                r.override_price_per_night,
                r.override_price_per_hour,
                r.override_price_day_use,
                rc.category_name,
                rc.base_price_per_night,
                rc.base_price_per_hour,
                rc.base_price_day_use,
                rc.max_adult_capacity,
                rc.max_child_capacity,
                rc.floor_area_sqm,
                rc.bed_configuration,
                p.property_name,
                brg.barangay_name,
                cm.city_municipality_name,
                prov.province_name,
                (SELECT ri.image_url FROM room_images ri WHERE ri.room_id = r.room_id ORDER BY ri.sort_order ASC LIMIT 1) AS room_image
            FROM rooms r
            LEFT JOIN room_categories rc ON r.category_id = rc.category_id
            LEFT JOIN properties p ON r.property_id = p.property_id
            LEFT JOIN psgc_barangays brg ON p.barangay_code = brg.barangay_code COLLATE utf8mb4_unicode_ci
            LEFT JOIN psgc_cities_municipalities cm ON p.city_municipality_code = cm.city_municipality_code COLLATE utf8mb4_unicode_ci
            LEFT JOIN psgc_provinces prov ON p.province_code = prov.province_code COLLATE utf8mb4_unicode_ci
            WHERE r.property_id IN ($placeholders)
            AND r.is_active = 1
            AND r.room_status = 'available'
            $excludeRooms
            ORDER BY p.property_name, r.room_number
        ");
        $roomsStmt->execute($roomParams);
        $roomsData = $roomsStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $response = [
        'success' => true,
        'visit_date' => $checkinDate,
        'visit_time' => $checkinTime,
        'data' => $roomsData,
        'properties' => $propertiesData,
        'total_properties' => count($propertiesData),
        'available_properties' => count(array_filter($propertiesData, fn($p) => $p['is_available']))
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    error_log("Availability check error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    error_log("Availability check error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
