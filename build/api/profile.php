<?php
header('Content-Type: application/json');
require_once '../config/db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($method) {
        case 'GET':
            if ($action === 'get_profile') {
                $userId = $_SESSION['user_id'];
                $stmt = $pdo->prepare("
                    SELECT up.profile_photo_url, up.first_name, up.last_name, up.display_name, ua.email, ua.role_id
                    FROM user_profiles up
                    INNER JOIN user_accounts ua ON up.user_id = ua.user_id
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
            } else {
                echo json_encode(['success' => false, 'message' => 'Unknown action']);
            }
            break;
        case 'POST':
            if ($action === 'update_photo') {
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
