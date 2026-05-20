<?php
/**
 * Public Rooms API - For frontend pages
 * Returns room categories with property information
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once '../../config/db.php';

$action = $_GET['action'] ?? 'list';

try {
    switch ($action) {
        case 'list':
            // Fetch all active individual rooms with property info
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 50;
            $offset = ($page - 1) * $perPage;
            $propertyFilter = isset($_GET['property_id']) ? "AND r.property_id = ?" : "";

            $sql = "
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
                    r.is_active,
                    r.created_at,
                    r.updated_at,
                    rc.category_name,
                    rc.base_price_per_night,
                    rc.base_price_per_hour,
                    rc.base_price_day_use,
                    rc.minimum_hours,
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
                    p.property_name,
                    p.property_code,
                    p.property_type,
                    p.address_line1,
                    brg.barangay_name,
                    cm.city_municipality_name,
                    prov.province_name,
                    reg.region_name
                FROM rooms r
                INNER JOIN room_categories rc ON r.category_id = rc.category_id
                INNER JOIN properties p ON r.property_id = p.property_id
                LEFT JOIN psgc_barangays brg ON p.barangay_code = brg.barangay_code COLLATE utf8mb4_unicode_ci
                LEFT JOIN psgc_cities_municipalities cm ON p.city_municipality_code = cm.city_municipality_code COLLATE utf8mb4_unicode_ci
                LEFT JOIN psgc_provinces prov ON p.province_code = prov.province_code COLLATE utf8mb4_unicode_ci
                LEFT JOIN psgc_regions reg ON p.region_code = reg.region_code COLLATE utf8mb4_unicode_ci
                WHERE r.is_active = 1 
                AND rc.is_active = 1
                AND p.is_active = 1
                {$propertyFilter}
                ORDER BY p.property_name ASC, rc.category_name ASC, r.room_number ASC
                LIMIT ? OFFSET ?
            ";

            if (isset($_GET['property_id'])) {
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_GET['property_id'], $perPage, $offset]);
            } else {
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$perPage, $offset]);
            }

            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get total count
            $countSql = "
                SELECT COUNT(*) as total
                FROM rooms r
                WHERE r.is_active = 1
                AND EXISTS (
                    SELECT 1 FROM room_categories rc 
                    WHERE rc.category_id = r.category_id 
                    AND rc.is_active = 1
                )
                AND EXISTS (
                    SELECT 1 FROM properties p 
                    WHERE p.property_id = r.property_id 
                    AND p.is_active = 1
                )
                {$propertyFilter}
            ";
            if (isset($_GET['property_id'])) {
                $countStmt = $pdo->prepare($countSql);
                $countStmt->execute([$_GET['property_id']]);
            } else {
                $countStmt = $pdo->query($countSql);
            }
            $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Fetch images and amenities for each room
            foreach ($rooms as &$room) {
                $imgStmt = $pdo->prepare("
                    SELECT image_id, image_url, caption, is_cover
                    FROM room_images
                    WHERE room_id = ? 
                    AND is_active = 1
                    ORDER BY is_cover DESC, sort_order ASC
                    LIMIT 1
                ");
                $imgStmt->execute([$room['room_id']]);
                $image = $imgStmt->fetch(PDO::FETCH_ASSOC);
                $room['room_image'] = $image ? $image['image_url'] : null;

                // Fetch amenities for this room
                $amenityStmt = $pdo->prepare("
                    SELECT ra.amenity_id, a.amenity_name, a.icon_code
                    FROM room_amenities ra
                    JOIN amenities a ON ra.amenity_id = a.amenity_id
                    WHERE ra.room_id = ?
                    ORDER BY a.sort_order ASC
                ");
                $amenityStmt->execute([$room['room_id']]);
                $room['amenities'] = $amenityStmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // Fetch properties for filter dropdown
            $propStmt = $pdo->query("
                SELECT property_id, property_name, city, province 
                FROM properties 
                WHERE is_active = 1 
                ORDER BY property_name ASC
            ");
            $properties = $propStmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'message' => 'Rooms retrieved',
                'data' => [
                    'data' => $rooms,
                    'pagination' => [
                        'page' => $page,
                        'per_page' => $perPage,
                        'total' => $total,
                        'total_pages' => ceil($total / $perPage)
                    ]
                ],
                'properties' => $properties
            ]);
            break;

        case 'single':
            if (empty($_GET['id'])) {
                echo json_encode(['success' => false, 'message' => 'Room category ID required']);
                exit;
            }

            $stmt = $pdo->prepare("
                SELECT 
                    rc.*,
                    p.property_name,
                    p.property_code,
                    p.property_type,
                    p.address_line1,
                    brg.barangay_name,
                    cm.city_municipality_name,
                    prov.province_name,
                    reg.region_name,
                    p.phone_primary,
                    p.email
                FROM room_categories rc
                INNER JOIN properties p ON rc.property_id = p.property_id
                LEFT JOIN psgc_barangays brg ON p.barangay_code = brg.barangay_code COLLATE utf8mb4_unicode_ci
                LEFT JOIN psgc_cities_municipalities cm ON p.city_municipality_code = cm.city_municipality_code COLLATE utf8mb4_unicode_ci
                LEFT JOIN psgc_provinces prov ON p.province_code = prov.province_code COLLATE utf8mb4_unicode_ci
                LEFT JOIN psgc_regions reg ON p.region_code = reg.region_code COLLATE utf8mb4_unicode_ci
                WHERE rc.category_id = ? 
                AND rc.is_active = 1
                AND p.is_active = 1
            ");
            $stmt->execute([$_GET['id']]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$room) {
                echo json_encode(['success' => false, 'message' => 'Room category not found']);
                exit;
            }

            // Get count of available rooms
            $countStmt = $pdo->prepare("
                SELECT COUNT(*) as room_count 
                FROM rooms 
                WHERE category_id = ? 
                AND is_active = 1
                AND room_status IN ('available', 'occupied', 'reserved')
            ");
            $countStmt->execute([$_GET['id']]);
            $room['room_count'] = $countStmt->fetch(PDO::FETCH_ASSOC)['room_count'] ?? 0;

            // Get specific rooms
            $roomsStmt = $pdo->prepare("
                SELECT room_id, room_number, room_name, room_status
                FROM rooms
                WHERE category_id = ? AND is_active = 1
                ORDER BY room_number ASC
            ");
            $roomsStmt->execute([$_GET['id']]);
            $room['rooms'] = $roomsStmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch images for each room in the category
            foreach ($room['rooms'] as &$roomItem) {
                $imgStmt = $pdo->prepare("
                    SELECT image_id, image_url, caption, is_cover
                    FROM room_images
                    WHERE room_id = ? 
                    AND is_active = 1
                    ORDER BY is_cover DESC, sort_order ASC
                ");
                $imgStmt->execute([$roomItem['room_id']]);
                $images = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
                $roomItem['images'] = $images;
                $roomItem['room_image'] = !empty($images) ? $images[0]['image_url'] : null;
            }

            // Fetch amenities for the category
            $amenityStmt = $pdo->prepare("
                SELECT ra.amenity_id, a.amenity_name, a.icon_code
                FROM room_amenities ra
                JOIN amenities a ON ra.amenity_id = a.amenity_id
                WHERE ra.category_id = ? AND ra.room_id IS NULL
                ORDER BY a.sort_order ASC
            ");
            $amenityStmt->execute([$_GET['id']]);
            $room['amenities'] = $amenityStmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'data' => $room]);
            break;

        case 'room':
            if (empty($_GET['id'])) {
                echo json_encode(['success' => false, 'message' => 'Room ID required']);
                exit;
            }

            $stmt = $pdo->prepare("
                SELECT 
                    r.*,
                    rc.category_name,
                    rc.category_code,
                    rc.description as category_description,
                    rc.base_price_per_night,
                    rc.base_price_per_hour,
                    rc.base_price_day_use,
                    rc.minimum_hours,
                    rc.max_adult_capacity,
                    rc.max_child_capacity,
                    rc.max_total_capacity,
                    rc.extra_adult_fee,
                    rc.extra_child_fee,
                    rc.extra_senior_fee,
                    rc.floor_area_sqm,
                    rc.bed_configuration,
                    rc.view_type,
                    rc.floor_level,
                    rc.smoking_policy,
                    rc.pet_friendly,
                    rc.allows_hourly,
                    rc.allows_overnight,
                    rc.allows_day_use,
                    p.property_name,
                    p.property_code,
                    p.property_type,
                    p.address_line1,
                    brg.barangay_name,
                    cm.city_municipality_name,
                    prov.province_name,
                    reg.region_name,
                    p.phone_primary,
                    p.email
                FROM rooms r
                INNER JOIN room_categories rc ON r.category_id = rc.category_id
                INNER JOIN properties p ON r.property_id = p.property_id
                LEFT JOIN psgc_barangays brg ON p.barangay_code = brg.barangay_code COLLATE utf8mb4_unicode_ci
                LEFT JOIN psgc_cities_municipalities cm ON p.city_municipality_code = cm.city_municipality_code COLLATE utf8mb4_unicode_ci
                LEFT JOIN psgc_provinces prov ON p.province_code = prov.province_code COLLATE utf8mb4_unicode_ci
                LEFT JOIN psgc_regions reg ON p.region_code = reg.region_code COLLATE utf8mb4_unicode_ci
                WHERE r.room_id = ? 
                AND r.is_active = 1
                AND rc.is_active = 1
                AND p.is_active = 1
            ");
            $stmt->execute([$_GET['id']]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$room) {
                echo json_encode(['success' => false, 'message' => 'Room not found']);
                exit;
            }

            // Fetch images for the room
            $imgStmt = $pdo->prepare("
                SELECT image_id, image_url, caption, image_type, is_cover
                FROM room_images
                WHERE room_id = ? 
                AND is_active = 1
                ORDER BY is_cover DESC, sort_order ASC
            ");
            $imgStmt->execute([$_GET['id']]);
            $room['images'] = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
            $room['room_image'] = !empty($room['images']) ? $room['images'][0]['image_url'] : null;

            // Fetch amenities for the room
            $amenityStmt = $pdo->prepare("
                SELECT ra.amenity_id, a.amenity_name, a.icon_code
                FROM room_amenities ra
                JOIN amenities a ON ra.amenity_id = a.amenity_id
                WHERE ra.room_id = ?
                ORDER BY a.sort_order ASC
            ");
            $amenityStmt->execute([$_GET['id']]);
            $room['amenities'] = $amenityStmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'data' => $room]);
            break;

        case 'by_property':
            if (empty($_GET['property_id'])) {
                echo json_encode(['success' => false, 'message' => 'Property ID required']);
                exit;
            }

            $propertyId = $_GET['property_id'];
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 50;
            $offset = ($page - 1) * $perPage;

            // Fetch individual rooms for the property
            $stmt = $pdo->prepare("
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
                    r.is_active,
                    r.created_at,
                    r.updated_at,
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
                    p.property_name
                FROM rooms r
                INNER JOIN room_categories rc ON r.category_id = rc.category_id
                INNER JOIN properties p ON r.property_id = p.property_id
                WHERE r.property_id = ? 
                AND r.is_active = 1
                AND rc.is_active = 1
                ORDER BY rc.category_name ASC, r.room_number ASC
                LIMIT ? OFFSET ?
            ");
            $stmt->execute([$propertyId, $perPage, $offset]);
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get total count
            $countStmt = $pdo->prepare("
                SELECT COUNT(*) as total
                FROM rooms r
                WHERE r.property_id = ? 
                AND r.is_active = 1
            ");
            $countStmt->execute([$propertyId]);
            $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Fetch images and amenities for each room
            foreach ($rooms as &$room) {
                $imgStmt = $pdo->prepare("
                    SELECT image_id, image_url, caption, is_cover
                    FROM room_images
                    WHERE room_id = ? 
                    AND is_active = 1
                    ORDER BY is_cover DESC, sort_order ASC
                ");
                $imgStmt->execute([$room['room_id']]);
                $room['images'] = $imgStmt->fetchAll(PDO::FETCH_ASSOC);

                // Fetch amenities for this room
                $amenityStmt = $pdo->prepare("
                    SELECT ra.amenity_id, a.amenity_name, a.icon_code
                    FROM room_amenities ra
                    JOIN amenities a ON ra.amenity_id = a.amenity_id
                    WHERE ra.room_id = ?
                    ORDER BY a.sort_order ASC
                ");
                $amenityStmt->execute([$room['room_id']]);
                $room['amenities'] = $amenityStmt->fetchAll(PDO::FETCH_ASSOC);
            }

            echo json_encode([
                'success' => true,
                'message' => 'Rooms retrieved',
                'data' => [
                    'data' => $rooms,
                    'pagination' => [
                        'page' => $page,
                        'per_page' => $perPage,
                        'total' => $total,
                        'total_pages' => ceil($total / $perPage)
                    ]
                ]
            ]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (PDOException $e) {
    error_log("Rooms API error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
