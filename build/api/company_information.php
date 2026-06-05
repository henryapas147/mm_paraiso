<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      requirePermission('settings.view');
      if ($action === 'list') {
        $stmt = $pdo->query("SELECT * FROM company_information WHERE is_active = 1 LIMIT 1");
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $data]);

      } elseif ($action === 'related') {
        $regions = $pdo->query("SELECT region_code, region_name FROM psgc_regions ORDER BY region_name")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['regions' => $regions]]);
      }
      break;

    case 'POST':
      requirePermission('settings.edit');
      // Validate CSRF token
      $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? null;
      if (!$csrfToken || $csrfToken !== ($_SESSION['csrf_token'] ?? '')) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Invalid or missing CSRF token']);
        exit;
      }

      // Handle both JSON and FormData (for file uploads)
      $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
      $isFormData = strpos($contentType, 'multipart/form-data') !== false;
      
      if ($isFormData) {
        $data = $_POST;
        $videoFile = $_FILES['video_file'] ?? null;
      } else {
        $data = json_decode(file_get_contents('php://input'), true);
        $videoFile = null;
      }

      if ($action === 'create') {
        $companyId = bin2hex(random_bytes(16));
        $stmt = $pdo->prepare("INSERT INTO company_information (
          company_id, company_name, legal_name, business_reg_number, tax_id,
          address_line1, address_line2, city, province, postal_code, country,
          region_code, province_code, city_municipality_code, barangay_code, barangay,
          phone_number, phone_number_2, email, email_2, website, google_maps_embed_url, description,
          facebook_url, instagram_url, twitter_url, linkedin_url, youtube_url, video_title, video_description, video_file, video_source_preference,
          business_hours, is_active, created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        // Accept a pre-assembled chunked upload path
        if (!empty($data['uploaded_video_path'])) {
            $videoFilePath = $data['uploaded_video_path'];
        } else {
            $videoFilePath = null;
        }
        if ($videoFile && $videoFile['error'] === UPLOAD_ERR_OK) {
          // Validate file size (max 100MB)
          $maxSize = 100 * 1024 * 1024;
          if ($videoFile['size'] > $maxSize) {
            echo json_encode(['success' => false, 'message' => 'Video file is too large. Maximum size is 100MB.']);
            exit;
          }
          $uploadDir = __DIR__ . '/../assets/videos/';
          if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
          }
          $ext = pathinfo($videoFile['name'], PATHINFO_EXTENSION);
          $filename = 'company_video_' . $companyId . '_' . time() . '.' . $ext;
          $videoFilePath = 'assets/videos/' . $filename;
          move_uploaded_file($videoFile['tmp_name'], $uploadDir . $filename);
        }

        $stmt->execute([
          $companyId, $data['company_name'], $data['legal_name'] ?? null, $data['business_reg_number'] ?? null,
          $data['tax_id'] ?? null, $data['address_line1'], $data['address_line2'] ?? null, $data['city'],
          $data['province'] ?? null, $data['postal_code'] ?? null, $data['country'] ?? 'Philippines',
          $data['region_code'] ?? null, $data['province_code'] ?? null, $data['city_municipality_code'] ?? null, $data['barangay_code'] ?? null, $data['barangay'] ?? null,
          $data['phone_number'], $data['phone_number_2'] ?? null, $data['email'], $data['email_2'] ?? null,
          $data['website'] ?? null, $data['google_maps_embed_url'] ?? null, $data['description'] ?? null,
          $data['facebook_url'] ?? null, $data['instagram_url'] ?? null, $data['twitter_url'] ?? null,
          $data['linkedin_url'] ?? null, $data['youtube_url'] ?? null, $data['video_title'] ?? null, $data['video_description'] ?? null, $videoFilePath, $data['video_source_preference'] ?? 'youtube_priority',
          json_encode($data['business_hours'] ?? []), 1, $data['user_id'] ?? null
        ]);
        echo json_encode(['success' => true, 'message' => 'Company created', 'company_id' => $companyId]);

      } elseif ($action === 'update') {
        // Get current video_file to preserve it if no new file is uploaded
        $currentStmt = $pdo->prepare("SELECT video_file FROM company_information WHERE company_id = ?");
        $currentStmt->execute([$data['company_id']]);
        $currentData = $currentStmt->fetch(PDO::FETCH_ASSOC);
        $videoFilePath = $currentData['video_file'] ?? null;

        // Accept a pre-assembled chunked upload path
        if (!empty($data['uploaded_video_path'])) {
            // Delete old video file if it exists
            if ($videoFilePath) {
                $oldFilePath = __DIR__ . '/../' . $videoFilePath;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            $videoFilePath = $data['uploaded_video_path'];
        }

        // Handle video file upload
        if ($videoFile && $videoFile['error'] === UPLOAD_ERR_OK) {
          // Validate file size (max 100MB)
          $maxSize = 100 * 1024 * 1024;
          if ($videoFile['size'] > $maxSize) {
            echo json_encode(['success' => false, 'message' => 'Video file is too large. Maximum size is 100MB.']);
            exit;
          }

          // Delete old video file if it exists
          if ($videoFilePath) {
            $oldFilePath = __DIR__ . '/../' . $videoFilePath;
            if (file_exists($oldFilePath)) {
              unlink($oldFilePath);
            }
          }

          $uploadDir = __DIR__ . '/../assets/videos/';
          if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
          }
          $ext = pathinfo($videoFile['name'], PATHINFO_EXTENSION);
          $filename = 'company_video_' . $data['company_id'] . '_' . time() . '.' . $ext;
          $videoFilePath = 'assets/videos/' . $filename;
          move_uploaded_file($videoFile['tmp_name'], $uploadDir . $filename);
        } elseif (isset($data['remove_video_flag']) && $data['remove_video_flag'] == '1') {
          // User explicitly requested to remove the video
          if ($videoFilePath) {
            $oldFilePath = __DIR__ . '/../' . $videoFilePath;
            if (file_exists($oldFilePath)) {
              unlink($oldFilePath);
            }
          }
          $videoFilePath = null;
        }

        // Build dynamic UPDATE statement with only changed fields
        $updateFields = [];
        $updateValues = [];

        $fieldMap = [
          'company_name' => 'company_name=?',
          'legal_name' => 'legal_name=?',
          'business_reg_number' => 'business_reg_number=?',
          'tax_id' => 'tax_id=?',
          'address_line1' => 'address_line1=?',
          'address_line2' => 'address_line2=?',
          'city' => 'city=?',
          'province' => 'province=?',
          'postal_code' => 'postal_code=?',
          'country' => 'country=?',
          'region_code' => 'region_code=?',
          'province_code' => 'province_code=?',
          'city_municipality_code' => 'city_municipality_code=?',
          'barangay_code' => 'barangay_code=?',
          'barangay' => 'barangay=?',
          'phone_number' => 'phone_number=?',
          'phone_number_2' => 'phone_number_2=?',
          'email' => 'email=?',
          'email_2' => 'email_2=?',
          'website' => 'website=?',
          'google_maps_embed_url' => 'google_maps_embed_url=?',
          'description' => 'description=?',
          'facebook_url' => 'facebook_url=?',
          'instagram_url' => 'instagram_url=?',
          'twitter_url' => 'twitter_url=?',
          'linkedin_url' => 'linkedin_url=?',
          'youtube_url' => 'youtube_url=?',
          'video_title' => 'video_title=?',
          'video_description' => 'video_description=?',
          'video_source_preference' => 'video_source_preference=?',
        ];

        // Add fields that are present in the data
        foreach ($fieldMap as $field => $sql) {
          if (array_key_exists($field, $data)) {
            $updateFields[] = $sql;
            $updateValues[] = $data[$field] ?? null;
          }
        }

        // Always add video_file if it changed
        if ($videoFilePath !== $currentData['video_file']) {
          $updateFields[] = 'video_file=?';
          $updateValues[] = $videoFilePath;
        }

        // Add business_hours if present
        if (isset($data['business_hours'])) {
          $updateFields[] = 'business_hours=?';
          $updateValues[] = json_encode($data['business_hours']);
        }

        // Add updated_by
        $updateFields[] = 'updated_by=?';
        $updateValues[] = $data['user_id'] ?? null;

        // Add company_id for WHERE clause
        $updateValues[] = $data['company_id'];

        if (empty($updateFields)) {
          echo json_encode(['success' => true, 'message' => 'No changes to save']);
          exit;
        }

        $sql = "UPDATE company_information SET " . implode(', ', $updateFields) . " WHERE company_id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($updateValues);
        echo json_encode(['success' => true, 'message' => 'Company updated']);
      }
      break;

    default:
      http_response_code(405);
      echo json_encode(['success' => false, 'message' => 'Method not allowed']);
  }
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
