<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

// Base URL configuration
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
if (!preg_match('/^[a-zA-Z0-9\-\.\:]+$/', $host)) {
  $host = 'localhost';
}
$BASE_URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$host}";

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($method) {
        case 'GET':
            if ($action === 'list') {
                $stmt = $pdo->query("
                    SELECT ua.user_id, ua.email, ua.phone_number, ua.account_status,
                           ua.is_email_verified, ua.last_login_at, ua.created_at,
                           ua.role_id, ur.role_label, ur.role_code,
                           up.first_name, up.last_name, up.profile_photo_url
                    FROM user_accounts ua
                    LEFT JOIN user_profiles up ON ua.user_id = up.user_id
                    LEFT JOIN user_roles ur ON ua.role_id = ur.role_id
                    WHERE ua.deleted_at IS NULL
                    ORDER BY ua.created_at DESC
                ");
                echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

            } elseif ($action === 'roles') {
                $stmt = $pdo->query("SELECT role_id, role_code, role_label FROM user_roles WHERE is_active = 1 ORDER BY role_id ASC");
                echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

            } else {
                echo json_encode(['success' => false, 'message' => 'Unknown action']);
            }
            break;

        case 'POST':
            // Support both JSON and multipart/form-data
            $isMultipart = isset($_FILES['profile_photo']) || isset($_POST['user_id']) || isset($_POST['first_name']);
            $input = $isMultipart ? $_POST : (json_decode(file_get_contents('php://input'), true) ?? []);

            // Helper: handle photo upload, returns saved URL or null
            $uploadPhoto = function($fileKey, $userId) use ($BASE_URL) {
                if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) return null;
                $file = $_FILES[$fileKey];
                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($file['type'], $allowed)) return null;
                if ($file['size'] > 2 * 1024 * 1024) return null; // 2MB max
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                
                $uploadDir = __DIR__ . '/../../uploads/profile_photos/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $filename = $userId . '_' . time() . '.' . $ext;
                $destPath = $uploadDir . $filename;
                if (move_uploaded_file($file['tmp_name'], $destPath)) {
                    // Dynamic path based on current environment - use absolute URL
                    $basePath = dirname($_SERVER['PHP_SELF']); // Gets /admin_booking/build/api
                    $uploadsPath = dirname(dirname($basePath)) . '/uploads/profile_photos'; // Gets /admin_booking/uploads/profile_photos
                    return $BASE_URL . $uploadsPath . '/' . $filename;
                }
                return null;
            };

            // Helper: delete old photo file
            $deletePhotoFile = function($photoUrl) {
                if (empty($photoUrl)) return;
                // Convert URL to filesystem path
                $filename = basename($photoUrl);
                $filePath = __DIR__ . '/../../uploads/profile_photos/' . $filename;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            };

            if ($action === 'create') {
                $required = ['first_name', 'last_name', 'email', 'role_id'];
                foreach ($required as $field) {
                    if (empty($input[$field])) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => "Field '$field' is required"]);
                        exit;
                    }
                }

                $check = $pdo->prepare("SELECT user_id FROM user_accounts WHERE email = ?");
                $check->execute([$input['email']]);
                if ($check->fetch()) {
                    echo json_encode(['success' => false, 'message' => 'Email already exists']);
                    exit;
                }

                $userId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                    mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
                    mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
                    mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
                $password = password_hash(!empty($input['password']) ? $input['password'] : 'Admin@123', PASSWORD_BCRYPT);
                $photoUrl = $uploadPhoto('profile_photo', $userId);

                $pdo->beginTransaction();

                $stmt = $pdo->prepare("
                    INSERT INTO user_accounts (user_id, role_id, email, phone_number, password_hash, account_status, is_email_verified)
                    VALUES (?, ?, ?, ?, ?, 'active', 0)
                ");
                $stmt->execute([$userId, $input['role_id'], $input['email'], $input['phone_number'] ?? null, $password]);

                $stmt2 = $pdo->prepare("
                    INSERT INTO user_profiles (profile_id, user_id, first_name, last_name, profile_photo_url)
                    VALUES (UUID(), ?, ?, ?, ?)
                ");
                $stmt2->execute([$userId, $input['first_name'], $input['last_name'], $photoUrl]);

                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'User created successfully']);

            } elseif ($action === 'update') {
                if (empty($input['user_id'])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'user_id is required']);
                    exit;
                }

                $userId = $input['user_id'];
                $removePhoto = ($input['remove_photo'] ?? '0') === '1';
                
                // Get existing photo URL for cleanup
                $stmt = $pdo->prepare("SELECT profile_photo_url FROM user_profiles WHERE user_id = ?");
                $stmt->execute([$userId]);
                $oldPhotoUrl = $stmt->fetchColumn();
                
                $photoUrl = $uploadPhoto('profile_photo', $userId);

                $pdo->beginTransaction();

                $stmt = $pdo->prepare("
                    UPDATE user_accounts SET role_id = ?, phone_number = ?, account_status = ?, updated_at = NOW()
                    WHERE user_id = ? AND deleted_at IS NULL
                ");
                $stmt->execute([$input['role_id'], $input['phone_number'] ?? null, $input['account_status'] ?? 'active', $userId]);

                if ($photoUrl !== null) {
                    // New photo uploaded
                    $stmt2 = $pdo->prepare("
                        UPDATE user_profiles SET first_name = ?, last_name = ?, profile_photo_url = ?, updated_at = NOW()
                        WHERE user_id = ?
                    ");
                    $stmt2->execute([$input['first_name'], $input['last_name'], $photoUrl, $userId]);
                    
                    // Delete old photo file after successful update
                    $deletePhotoFile($oldPhotoUrl);
                } elseif ($removePhoto) {
                    // Remove existing photo
                    $stmt2 = $pdo->prepare("
                        UPDATE user_profiles SET first_name = ?, last_name = ?, profile_photo_url = NULL, updated_at = NOW()
                        WHERE user_id = ?
                    ");
                    $stmt2->execute([$input['first_name'], $input['last_name'], $userId]);
                    
                    // Delete old photo file after successful update
                    $deletePhotoFile($oldPhotoUrl);
                } else {
                    // No photo change
                    $stmt2 = $pdo->prepare("
                        UPDATE user_profiles SET first_name = ?, last_name = ?, updated_at = NOW()
                        WHERE user_id = ?
                    ");
                    $stmt2->execute([$input['first_name'], $input['last_name'], $userId]);
                }

                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'User updated successfully']);

            } else {
                echo json_encode(['success' => false, 'message' => 'Unknown action']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log("Users API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
