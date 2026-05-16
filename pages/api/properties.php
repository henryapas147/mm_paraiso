<?php
/**
 * Public Properties API - For frontend pages
 * Returns active properties with cover images for public display
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once '../../config/db.php';

$action = $_GET['action'] ?? 'list';

try {
    switch ($action) {
        case 'list':
            // Get pagination parameters
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $perPage = isset($_GET['per_page']) ? max(1, (int)$_GET['per_page']) : 6;
            $offset = ($page - 1) * $perPage;

            // Get total count for pagination
            $countStmt = $pdo->query("
                SELECT COUNT(*) as total 
                FROM properties 
                WHERE is_active = 1
            ");
            $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Fetch active properties with their cover images (paginated)
            $stmt = $pdo->prepare("
                SELECT 
                    p.property_id,
                    p.property_name,
                    p.property_code,
                    p.property_type,
                    p.tagline,
                    p.description,
                    p.address_line1,
                    p.region_code,
                    p.province_code,
                    p.city_municipality_code,
                    p.barangay_code,
                    p.phone_primary,
                    p.email,
                    pi.image_url as cover_image,
                    reg.region_name,
                    prov.province_name,
                    cm.city_municipality_name,
                    brgy.barangay_name
                FROM properties p
                LEFT JOIN property_images pi ON p.property_id = pi.property_id 
                    AND pi.image_type = 'cover' 
                    AND pi.is_active = 1
                LEFT JOIN psgc_regions reg ON p.region_code COLLATE utf8mb4_unicode_ci = reg.region_code COLLATE utf8mb4_unicode_ci
                LEFT JOIN psgc_provinces prov ON p.province_code COLLATE utf8mb4_unicode_ci = prov.province_code COLLATE utf8mb4_unicode_ci
                LEFT JOIN psgc_cities_municipalities cm ON p.city_municipality_code COLLATE utf8mb4_unicode_ci = cm.city_municipality_code COLLATE utf8mb4_unicode_ci
                LEFT JOIN psgc_barangays brgy ON p.barangay_code COLLATE utf8mb4_unicode_ci = brgy.barangay_code COLLATE utf8mb4_unicode_ci
                WHERE p.is_active = 1
                ORDER BY p.sort_order ASC, p.property_name ASC
                LIMIT ? OFFSET ?
            ");
            $stmt->execute([$perPage, $offset]);
            $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch distinct property types for filters (only show types that have properties)
            $typeStmt = $pdo->query("
                SELECT DISTINCT property_type 
                FROM properties 
                WHERE is_active = 1 
                AND property_type IS NOT NULL 
                AND property_type != ''
                ORDER BY property_type ASC
            ");
            $propertyTypes = $typeStmt->fetchAll(PDO::FETCH_COLUMN);

            echo json_encode([
                'success' => true,
                'message' => 'Properties retrieved',
                'data' => [
                    'data' => $properties,
                    'pagination' => [
                        'page' => $page,
                        'per_page' => $perPage,
                        'total' => $total,
                        'total_pages' => ceil($total / $perPage)
                    ]
                ],
                'property_types' => $propertyTypes
            ]);
            break;

        case 'single':
            if (empty($_GET['id'])) {
                echo json_encode(['success' => false, 'message' => 'Property ID required']);
                exit;
            }

            $stmt = $pdo->prepare("
                SELECT 
                    p.*,
                    pi.image_url as cover_image,
                    reg.region_name,
                    prov.province_name,
                    cm.city_municipality_name,
                    brgy.barangay_name
                FROM properties p
                LEFT JOIN property_images pi ON p.property_id = pi.property_id 
                    AND pi.image_type = 'cover' 
                    AND pi.is_active = 1
                LEFT JOIN psgc_regions reg ON p.region_code COLLATE utf8mb4_unicode_ci = reg.region_code COLLATE utf8mb4_unicode_ci
                LEFT JOIN psgc_provinces prov ON p.province_code COLLATE utf8mb4_unicode_ci = prov.province_code COLLATE utf8mb4_unicode_ci
                LEFT JOIN psgc_cities_municipalities cm ON p.city_municipality_code COLLATE utf8mb4_unicode_ci = cm.city_municipality_code COLLATE utf8mb4_unicode_ci
                LEFT JOIN psgc_barangays brgy ON p.barangay_code COLLATE utf8mb4_unicode_ci = brgy.barangay_code COLLATE utf8mb4_unicode_ci
                WHERE p.property_id = ? 
                AND p.is_active = 1
            ");
            $stmt->execute([$_GET['id']]);
            $property = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$property) {
                echo json_encode(['success' => false, 'message' => 'Property not found']);
                exit;
            }

            // Fetch all images for the property
            $imgStmt = $pdo->prepare("
                SELECT image_id, image_url, image_type, caption, sort_order
                FROM property_images
                WHERE property_id = ? AND is_active = 1
                ORDER BY sort_order ASC
            ");
            $imgStmt->execute([$_GET['id']]);
            $property['images'] = $imgStmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'data' => $property]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (PDOException $e) {
    $errorDetails = [
        'message' => $e->getMessage(),
        'code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ];
    
    // Log to error log
    error_log("Properties API error: " . json_encode($errorDetails));
    
    // Log to file
    $logFile = __DIR__ . '/logs/properties_api_error.log';
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . json_encode($errorDetails) . "\n", FILE_APPEND);
    
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
