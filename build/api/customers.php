<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        // Join user_accounts with user_profiles and geocode tables, filter by customer role (role_id = 7)
        $stmt = $pdo->query("SELECT 
          ua.user_id, ua.email, ua.phone_number, ua.account_status, ua.is_email_verified, ua.is_phone_verified, ua.created_at, ua.last_login_at,
          up.first_name, up.last_name, up.middle_name, up.profile_photo_url,
          up.region_code, up.province_code, up.city_municipality_code, up.barangay_code,
          up.address_line1, up.address_line2, up.postal_code, up.country,
          r.region_name, p.province_name, c.city_municipality_name, b.barangay_name
          FROM user_accounts ua
          LEFT JOIN user_profiles up ON ua.user_id = up.user_id
          LEFT JOIN psgc_regions r ON up.region_code COLLATE utf8mb4_unicode_ci = r.region_code COLLATE utf8mb4_unicode_ci
          LEFT JOIN psgc_provinces p ON up.province_code COLLATE utf8mb4_unicode_ci = p.province_code COLLATE utf8mb4_unicode_ci
          LEFT JOIN psgc_cities_municipalities c ON up.city_municipality_code COLLATE utf8mb4_unicode_ci = c.city_municipality_code COLLATE utf8mb4_unicode_ci
          LEFT JOIN psgc_barangays b ON up.barangay_code COLLATE utf8mb4_unicode_ci = b.barangay_code COLLATE utf8mb4_unicode_ci
          WHERE ua.role_id = 7 AND ua.deleted_at IS NULL
          ORDER BY ua.created_at DESC");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT 
          ua.user_id, ua.email, ua.phone_number, ua.account_status, ua.is_email_verified, ua.is_phone_verified, ua.created_at, ua.last_login_at,
          up.first_name, up.last_name, up.middle_name, up.date_of_birth, up.gender, up.nationality, up.id_type, up.id_number, up.id_verified,
          up.profile_photo_url, up.address_line1, up.address_line2, up.postal_code, up.country,
          up.region_code, up.province_code, up.city_municipality_code, up.barangay_code,
          up.emergency_contact_name, up.emergency_contact_phone, up.emergency_contact_relation, up.notes
          FROM user_accounts ua
          LEFT JOIN user_profiles up ON ua.user_id = up.user_id
          WHERE ua.user_id = ? AND ua.role_id = 7 AND ua.deleted_at IS NULL");
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
      }
      break;

    case 'POST':
      // Support both JSON and multipart/form-data
      $isMultipart = isset($_FILES['profile_photo']) || isset($_POST['user_id']) || isset($_POST['first_name']);
      $input = $isMultipart ? $_POST : (json_decode(file_get_contents('php://input'), true) ?? []);

      // Helper: handle photo upload, returns saved URL or null
      $uploadPhoto = function($fileKey, $userId) {
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
          // Dynamic path based on current environment
          $basePath = dirname($_SERVER['PHP_SELF']); // Gets /admin_booking/build or equivalent
          $uploadsPath = dirname($basePath) . '/uploads/profile_photos'; // Gets /admin_booking/uploads/profile_photos
          return $uploadsPath . '/' . $filename;
        }
        return null;
      };

      // Helper: delete old photo file
      $deletePhotoFile = function($photoUrl) {
        if (empty($photoUrl)) return;
        $filename = basename($photoUrl);
        $filePath = __DIR__ . '/../../uploads/profile_photos/' . $filename;
        if (file_exists($filePath)) {
          unlink($filePath);
        }
      };

      $data = $input;

      if ($action === 'create') {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT user_id FROM user_accounts WHERE email = ? AND deleted_at IS NULL");
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) {
          echo json_encode(['success' => false, 'message' => 'Email already exists']);
          exit;
        }

        // Generate UUID for new customer
        $user_id = sprintf('ua-%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

        // Generate password hash (default password: Customer@123)
        $password_hash = password_hash($data['password'] ?? 'Customer@123', PASSWORD_DEFAULT);
        $photoUrl = $uploadPhoto('profile_photo', $user_id);

        // Insert into user_accounts
        $stmt = $pdo->prepare("INSERT INTO user_accounts
          (user_id, role_id, email, phone_number, password_hash, account_status, is_email_verified, is_phone_verified, created_at)
          VALUES (?, 7, ?, ?, ?, ?, ?, ?, NOW())");

        $stmt->execute([
          $user_id,
          $data['email'],
          $data['phone_number'] ?? null,
          $password_hash,
          $data['account_status'] ?? 'active',
          $data['is_email_verified'] ?? 0,
          $data['is_phone_verified'] ?? 0
        ]);

        // Insert into user_profiles
        $stmt = $pdo->prepare("INSERT INTO user_profiles
          (user_id, first_name, last_name, middle_name, region_code, province_code, city_municipality_code, barangay_code,
           address_line1, address_line2, postal_code, country, profile_photo_url, notes, created_at)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        $stmt->execute([
          $user_id,
          $data['first_name'] ?? null,
          $data['last_name'] ?? null,
          $data['middle_name'] ?? null,
          $data['region_code'] ?? null,
          $data['province_code'] ?? null,
          $data['city_municipality_code'] ?? null,
          $data['barangay_code'] ?? null,
          $data['address_line1'] ?? null,
          $data['address_line2'] ?? null,
          $data['postal_code'] ?? null,
          $data['country'] ?? 'Philippines',
          $photoUrl,
          $data['notes'] ?? null
        ]);

        echo json_encode(['success' => true, 'message' => 'Customer created', 'user_id' => $user_id]);

      } elseif ($action === 'update') {
        // Check if email already exists (excluding current user)
        $stmt = $pdo->prepare("SELECT user_id FROM user_accounts WHERE email = ? AND user_id != ? AND deleted_at IS NULL");
        $stmt->execute([$data['email'], $data['user_id']]);
        if ($stmt->fetch()) {
          echo json_encode(['success' => false, 'message' => 'Email already exists']);
          exit;
        }

        $userId = $data['user_id'];
        $removePhoto = ($data['remove_photo'] ?? '0') === '1';
        
        // Get existing photo URL for cleanup
        $stmt = $pdo->prepare("SELECT profile_photo_url FROM user_profiles WHERE user_id = ?");
        $stmt->execute([$userId]);
        $oldPhotoUrl = $stmt->fetchColumn();
        
        $photoUrl = $uploadPhoto('profile_photo', $userId);

        $pdo->beginTransaction();

        // Update user_accounts
        $stmt = $pdo->prepare("UPDATE user_accounts SET
          email=?, phone_number=?, account_status=?, is_email_verified=?, is_phone_verified=?
          WHERE user_id=? AND role_id=7");

        $stmt->execute([
          $data['email'],
          $data['phone_number'] ?? null,
          $data['account_status'] ?? 'active',
          $data['is_email_verified'] ?? 0,
          $data['is_phone_verified'] ?? 0,
          $data['user_id']
        ]);

        // Update user_profiles
        if ($photoUrl !== null) {
          // New photo uploaded
          $stmt = $pdo->prepare("UPDATE user_profiles SET
            first_name=?, last_name=?, middle_name=?, region_code=?, province_code=?, city_municipality_code=?, barangay_code=?,
            address_line1=?, address_line2=?, postal_code=?, country=?, profile_photo_url=?, notes=?
            WHERE user_id=?");

          $stmt->execute([
            $data['first_name'] ?? null,
            $data['last_name'] ?? null,
            $data['middle_name'] ?? null,
            $data['region_code'] ?? null,
            $data['province_code'] ?? null,
            $data['city_municipality_code'] ?? null,
            $data['barangay_code'] ?? null,
            $data['address_line1'] ?? null,
            $data['address_line2'] ?? null,
            $data['postal_code'] ?? null,
            $data['country'] ?? 'Philippines',
            $photoUrl,
            $data['notes'] ?? null,
            $data['user_id']
          ]);
          
          // Delete old photo file after successful update
          $deletePhotoFile($oldPhotoUrl);
        } elseif ($removePhoto) {
          // Remove existing photo
          $stmt = $pdo->prepare("UPDATE user_profiles SET
            first_name=?, last_name=?, middle_name=?, region_code=?, province_code=?, city_municipality_code=?, barangay_code=?,
            address_line1=?, address_line2=?, postal_code=?, country=?, profile_photo_url=NULL, notes=?
            WHERE user_id=?");

          $stmt->execute([
            $data['first_name'] ?? null,
            $data['last_name'] ?? null,
            $data['middle_name'] ?? null,
            $data['region_code'] ?? null,
            $data['province_code'] ?? null,
            $data['city_municipality_code'] ?? null,
            $data['barangay_code'] ?? null,
            $data['address_line1'] ?? null,
            $data['address_line2'] ?? null,
            $data['postal_code'] ?? null,
            $data['country'] ?? 'Philippines',
            $data['notes'] ?? null,
            $data['user_id']
          ]);
          
          // Delete old photo file after successful update
          $deletePhotoFile($oldPhotoUrl);
        } else {
          // No photo change
          $stmt = $pdo->prepare("UPDATE user_profiles SET
            first_name=?, last_name=?, middle_name=?, region_code=?, province_code=?, city_municipality_code=?, barangay_code=?,
            address_line1=?, address_line2=?, postal_code=?, country=?, notes=?
            WHERE user_id=?");

          $stmt->execute([
            $data['first_name'] ?? null,
            $data['last_name'] ?? null,
            $data['middle_name'] ?? null,
            $data['region_code'] ?? null,
            $data['province_code'] ?? null,
            $data['city_municipality_code'] ?? null,
            $data['barangay_code'] ?? null,
            $data['address_line1'] ?? null,
            $data['address_line2'] ?? null,
            $data['postal_code'] ?? null,
            $data['country'] ?? 'Philippines',
            $data['notes'] ?? null,
            $data['user_id']
          ]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Customer updated']);

      } elseif ($action === 'delete') {
        // Soft delete - set deleted_at timestamp
        $stmt = $pdo->prepare("UPDATE user_accounts SET deleted_at=NOW(), account_status='inactive' WHERE user_id=? AND role_id=7");
        $stmt->execute([$data['user_id']]);
        echo json_encode(['success' => true, 'message' => 'Customer deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
