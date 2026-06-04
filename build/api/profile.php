<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($method) {
        case 'GET':
            if ($action === 'get_profile') {
                $userId = $_SESSION['user_id'];
                $stmt = $pdo->prepare("
                    SELECT up.profile_photo_url, up.cover_photo_url, up.first_name, up.last_name, up.display_name,
                           up.address_line1, up.address_line2,
                           up.region_code, up.province_code, up.city_municipality_code, up.barangay_code,
                           r.region_name, p.province_name, c.city_municipality_name, b.barangay_name,
                           up.country, up.postal_code,
                           ua.email, ua.role_id
                    FROM user_profiles up
                    INNER JOIN user_accounts ua ON up.user_id = ua.user_id
                    LEFT JOIN psgc_regions r ON up.region_code COLLATE utf8mb4_unicode_ci = r.region_code COLLATE utf8mb4_unicode_ci
                    LEFT JOIN psgc_provinces p ON up.province_code COLLATE utf8mb4_unicode_ci = p.province_code COLLATE utf8mb4_unicode_ci
                    LEFT JOIN psgc_cities_municipalities c ON up.city_municipality_code COLLATE utf8mb4_unicode_ci = c.city_municipality_code COLLATE utf8mb4_unicode_ci
                    LEFT JOIN psgc_barangays b ON up.barangay_code COLLATE utf8mb4_unicode_ci = b.barangay_code COLLATE utf8mb4_unicode_ci
                    WHERE up.user_id = ?
                ");
                $stmt->execute([$userId]);
                $profile = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($profile) {
                    // Get role label
                    $roleStmt = $pdo->prepare("SELECT role_label FROM user_roles WHERE role_id = ?");
                    $roleStmt->execute([$profile['role_id']]);
                    $role = $roleStmt->fetch();
                    $profile['role_label'] = $role['role_label'] ?? '';
                    
                    echo json_encode(['success' => true, 'data' => $profile]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Profile not found']);
                }
            } else if ($action === 'get_statistics') {
                $userId = $_SESSION['user_id'];

                // Get upcoming bookings (check_in_date >= today)
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) as count
                    FROM reservations
                    WHERE customer_user_id = ? AND check_in_date >= CURDATE()
                ");
                $stmt->execute([$userId]);
                $upcomingBookings = $stmt->fetchColumn();

                // Get total reservations
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) as count
                    FROM reservations
                    WHERE customer_user_id = ?
                ");
                $stmt->execute([$userId]);
                $totalReservations = $stmt->fetchColumn();

                // Get total payments (JOIN with reservations to get customer_user_id)
                $stmt = $pdo->prepare("
                    SELECT COALESCE(SUM(p.amount), 0) as total
                    FROM payments p
                    INNER JOIN reservations r ON p.reservation_id = r.reservation_id
                    WHERE r.customer_user_id = ?
                ");
                $stmt->execute([$userId]);
                $totalPayments = $stmt->fetchColumn();

                echo json_encode([
                    'success' => true,
                    'data' => [
                        'upcoming_bookings' => $upcomingBookings ?? 0,
                        'total_reservations' => $totalReservations ?? 0,
                        'total_payments' => $totalPayments ?? 0
                    ]
                ]);
            } else if ($action === 'get_upcoming_checkins') {
                $userId = $_SESSION['user_id'];
                
                $stmt = $pdo->prepare("
                    SELECT r.reservation_code, r.check_in_date, r.check_out_date, r.reservation_status,
                           p.property_name, r.total_amount,
                           DATEDIFF(r.check_out_date, r.check_in_date) as nights
                    FROM reservations r
                    INNER JOIN properties p ON r.property_id = p.property_id
                    WHERE r.customer_user_id = ? AND r.check_in_date >= CURDATE()
                    ORDER BY r.check_in_date ASC
                    LIMIT 5
                ");
                $stmt->execute([$userId]);
                $checkins = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode(['success' => true, 'data' => $checkins]);
            } else if ($action === 'get_recent_activity') {
                $userId = $_SESSION['user_id'];
                
                $activities = [];
                
                // Get recent payments
                $stmt = $pdo->prepare("
                    SELECT 'payment' as type, p.payment_date as created_at, 
                           CONCAT('₱', FORMAT(p.amount, 2)) as description,
                           CONCAT('Payment for booking #', r.reservation_code) as details
                    FROM payments p
                    INNER JOIN reservations r ON p.reservation_id = r.reservation_id
                    WHERE r.customer_user_id = ?
                    ORDER BY p.payment_date DESC
                    LIMIT 3
                ");
                $stmt->execute([$userId]);
                $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $activities = array_merge($activities, $payments);
                
                // Get recent bookings
                $stmt = $pdo->prepare("
                    SELECT 'booking' as type, r.created_at, 
                           'Booking confirmed' as description,
                           CONCAT(r.reservation_code, ' - ', p.property_name) as details
                    FROM reservations r
                    INNER JOIN properties p ON r.property_id = p.property_id
                    WHERE r.customer_user_id = ?
                    ORDER BY r.created_at DESC
                    LIMIT 3
                ");
                $stmt->execute([$userId]);
                $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $activities = array_merge($activities, $bookings);
                
                // Sort by date and limit
                usort($activities, function($a, $b) {
                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                });
                $activities = array_slice($activities, 0, 5);
                
                echo json_encode(['success' => true, 'data' => $activities]);
            } else if ($action === 'get_messages') {
                $userId = $_SESSION['user_id'];
                $filter = $_GET['filter'] ?? 'all';
                
                $whereClause = "nr.user_id = ?";
                $params = [$userId];
                
                if ($filter !== 'all') {
                    $typeMap = [
                        'system' => ['reservation_confirmed', 'reservation_cancelled', 'check_in_reminder', 'check_out_reminder', 'payment_received', 'payment_due'],
                        'support' => ['system_alert', 'staff_alert'],
                        'promotional' => ['promo_announcement']
                    ];
                    
                    if (isset($typeMap[$filter])) {
                        $placeholders = str_repeat('?,', count($typeMap[$filter]) - 1) . '?';
                        $whereClause .= " AND n.notif_type IN ($placeholders)";
                        $params = array_merge($params, $typeMap[$filter]);
                    }
                }
                
                $stmt = $pdo->prepare("
                    SELECT nr.recipient_id, nr.is_read, nr.read_at, nr.created_at,
                           n.notif_id, n.notif_type, n.title, n.body, n.action_url,
                           n.reference_type, n.reference_id
                    FROM notification_recipients nr
                    INNER JOIN notifications n ON nr.notif_id = n.notif_id
                    WHERE $whereClause
                    ORDER BY nr.created_at DESC
                    LIMIT 20
                ");
                $stmt->execute($params);
                $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Map notif_type to message category
                $typeToCategory = [
                    'reservation_confirmed' => 'system',
                    'reservation_cancelled' => 'system',
                    'check_in_reminder' => 'system',
                    'check_out_reminder' => 'system',
                    'payment_received' => 'system',
                    'payment_due' => 'system',
                    'promo_announcement' => 'promotional',
                    'system_alert' => 'support',
                    'staff_alert' => 'support',
                    'custom' => 'system'
                ];
                
                foreach ($messages as &$msg) {
                    $msg['category'] = $typeToCategory[$msg['notif_type']] ?? 'system';
                }
                
                echo json_encode(['success' => true, 'data' => $messages]);
            } else if ($action === 'mark_message_read') {
                $userId = $_SESSION['user_id'];
                $recipientId = $_POST['recipient_id'] ?? null;
                
                if (!$recipientId) {
                    echo json_encode(['success' => false, 'message' => 'Missing recipient_id']);
                    exit;
                }
                
                $stmt = $pdo->prepare("
                    UPDATE notification_recipients
                    SET is_read = 1, read_at = NOW()
                    WHERE recipient_id = ? AND user_id = ?
                ");
                $stmt->execute([$recipientId, $userId]);
                
                echo json_encode(['success' => true, 'message' => 'Message marked as read']);
            } else if ($action === 'mark_all_read') {
                $userId = $_SESSION['user_id'];
                
                $stmt = $pdo->prepare("
                    UPDATE notification_recipients
                    SET is_read = 1, read_at = NOW()
                    WHERE user_id = ? AND is_read = 0
                ");
                $stmt->execute([$userId]);
                
                echo json_encode(['success' => true, 'message' => 'All messages marked as read']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Unknown action']);
            }
            break;
        case 'POST':
            if ($action === 'update_profile') {
                $userId = $_SESSION['user_id'];
                $firstName = trim($_POST['first_name'] ?? '');
                $lastName = trim($_POST['last_name'] ?? '');
                $addressLine1 = trim($_POST['address_line1'] ?? '');
                $addressLine2 = trim($_POST['address_line2'] ?? '');
                $regionCode = trim($_POST['region_code'] ?? '');
                $provinceCode = trim($_POST['province_code'] ?? '');
                $cityMunicipalityCode = trim($_POST['city_municipality_code'] ?? '');
                $barangayCode = trim($_POST['barangay_code'] ?? '');
                $country = trim($_POST['country'] ?? '');
                $postalCode = trim($_POST['postal_code'] ?? '');

                try {
                    $pdo->beginTransaction();

                    // Check if profile exists
                    $checkStmt = $pdo->prepare("SELECT user_id FROM user_profiles WHERE user_id = ?");
                    $checkStmt->execute([$userId]);
                    $profileExists = $checkStmt->fetch();

                    if ($profileExists) {
                        // Update existing profile
                        $stmt = $pdo->prepare("
                            UPDATE user_profiles
                            SET first_name = ?, last_name = ?, address_line1 = ?, address_line2 = ?,
                                region_code = ?, province_code = ?, city_municipality_code = ?, barangay_code = ?,
                                country = ?, postal_code = ?, updated_at = NOW()
                            WHERE user_id = ?
                        ");
                        $stmt->execute([$firstName, $lastName, $addressLine1, $addressLine2,
                            $regionCode, $provinceCode, $cityMunicipalityCode, $barangayCode,
                            $country, $postalCode, $userId]);
                    } else {
                        // Insert new profile
                        $stmt = $pdo->prepare("
                            INSERT INTO user_profiles
                            (user_id, first_name, last_name, address_line1, address_line2,
                             region_code, province_code, city_municipality_code, barangay_code,
                             country, postal_code, updated_at)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
                        ");
                        $stmt->execute([$userId, $firstName, $lastName, $addressLine1, $addressLine2,
                            $regionCode, $provinceCode, $cityMunicipalityCode, $barangayCode,
                            $country, $postalCode]);
                    }

                    $pdo->commit();
                    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
                } catch (PDOException $e) {
                    $pdo->rollBack();
                    error_log("Profile update error: " . $e->getMessage());
                    echo json_encode(['success' => false, 'message' => 'Error updating profile: ' . $e->getMessage()]);
                }
            } else if ($action === 'update_cover_photo') {
                if (!isset($_FILES['cover_photo']) || $_FILES['cover_photo']['error'] !== UPLOAD_ERR_OK) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'No cover photo uploaded']);
                    exit;
                }

                $file = $_FILES['cover_photo'];
                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($file['type'], $allowed)) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Invalid file type. Allowed: JPEG, PNG, GIF, WebP']);
                    exit;
                }

                if ($file['size'] > 5 * 1024 * 1024) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'File too large. Max size: 5MB']);
                    exit;
                }

                $userId = $_SESSION['user_id'];
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

                // Detect environment and set upload directory accordingly
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $isLocalhost = in_array($host, ['localhost', '127.0.0.1']) || preg_match('/^192\.168\./', $host) || preg_match('/^10\./', $host);

                // Generate new filename
                $filename = $userId . '_cover_' . time() . '.' . $ext;

                if ($isLocalhost) {
                    $uploadDir = __DIR__ . '/../../uploads/cover_photos/';
                    $webPath = '/admin_booking/uploads/cover_photos/' . $filename;
                } else {
                    $uploadDir = __DIR__ . '/../../uploads/cover_photos/';
                    $webPath = '/uploads/cover_photos/' . $filename;
                }

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Move uploaded file
                if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
                    exit;
                }

                // Get existing cover photo URL for cleanup
                $stmt = $pdo->prepare("SELECT cover_photo_url FROM user_profiles WHERE user_id = ?");
                $stmt->execute([$userId]);
                $existingPhoto = $stmt->fetchColumn();

                // Update database
                $stmt = $pdo->prepare("UPDATE user_profiles SET cover_photo_url = ? WHERE user_id = ?");
                $stmt->execute([$webPath, $userId]);

                // Delete old cover photo if it exists
                if ($existingPhoto && file_exists(__DIR__ . '/../../..' . $existingPhoto)) {
                    unlink(__DIR__ . '/../../..' . $existingPhoto);
                }

                echo json_encode(['success' => true, 'message' => 'Cover photo updated successfully', 'cover_photo_url' => $webPath]);
            } else if ($action === 'update_photo') {
                if (!isset($_FILES['profile_photo']) || $_FILES['profile_photo']['error'] !== UPLOAD_ERR_OK) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'No photo uploaded']);
                    exit;
                }

                $file = $_FILES['profile_photo'];
                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($file['type'], $allowed)) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Invalid file type. Allowed: JPEG, PNG, GIF, WebP']);
                    exit;
                }

                if ($file['size'] > 2 * 1024 * 1024) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'File too large. Max size: 2MB']);
                    exit;
                }

                $userId = $_SESSION['user_id'];
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                
                // Get basePath for web path calculation
                // Need to go up from api directory to build directory
                $basePath = dirname(dirname($_SERVER['PHP_SELF'])); // Gets /admin_booking/build or /build
                
                // Detect environment and set upload directory accordingly
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $isLocalhost = in_array($host, ['localhost', '127.0.0.1']) || preg_match('/^192\.168\./', $host) || preg_match('/^10\./', $host);
                
                // Generate new filename
                $filename = $userId . '_' . time() . '.' . $ext;
                
                if ($isLocalhost) {
                    // Localhost: use C:\xampp\htdocs\admin_booking\uploads\profile_photos
                    $uploadDir = __DIR__ . '/../../uploads/profile_photos/';
                    $webPath = '/admin_booking/uploads/profile_photos/' . $filename;
                } else {
                    // Hostinger: use uploads/profile_photos/ (same structure as localhost)
                    $uploadDir = __DIR__ . '/../../uploads/profile_photos/';
                    $webPath = '/uploads/profile_photos/' . $filename;
                }

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Get existing photo URL for cleanup
                $stmt = $pdo->prepare("SELECT profile_photo_url FROM user_profiles WHERE user_id = ?");
                $stmt->execute([$userId]);
                $oldPhotoUrl = $stmt->fetchColumn();

                $destPath = $uploadDir . $filename;

                if (move_uploaded_file($file['tmp_name'], $destPath)) {
                    $newPhotoUrl = $webPath;

                    // Update database
                    $pdo->beginTransaction();
                    $stmt = $pdo->prepare("
                        UPDATE user_profiles 
                        SET profile_photo_url = ?, updated_at = NOW() 
                        WHERE user_id = ?
                    ");
                    $stmt->execute([$newPhotoUrl, $userId]);
                    $pdo->commit();

                    // Delete old photo file after successful update
                    if ($oldPhotoUrl) {
                        // Handle both localhost path and Hostinger path
                        $oldFilename = basename($oldPhotoUrl);
                        // Try current environment location first
                        $oldFilePath = $uploadDir . $oldFilename;
                        if (!file_exists($oldFilePath)) {
                            // Try the other environment location
                            if ($isLocalhost) {
                                $oldFilePath = __DIR__ . '/../assets/images/uploads/profile_photos/' . $oldFilename;
                            } else {
                                $oldFilePath = __DIR__ . '/../../uploads/profile_photos/' . $oldFilename;
                            }
                        }
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }

                    echo json_encode(['success' => true, 'message' => 'Profile photo updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
                }

            } else {
                echo json_encode(['success' => false, 'message' => 'Unknown action']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Profile API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
