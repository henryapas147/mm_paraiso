<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

// Validate CSRF token for POST requests
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
  $headers = getallheaders();
  $token = $headers['X-CSRF-Token'] ?? $_POST['csrf_token'] ?? null;
  
  if (!$token || $token !== ($_SESSION['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
  }
}

$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      requirePermission('reservations.view');
      if ($action === 'list') {
        $stmt = $pdo->query("
          SELECT rt.*, p.property_name
          FROM reservation_requirement_types rt
          LEFT JOIN properties p ON rt.property_id = p.property_id
          ORDER BY rt.sort_order ASC, rt.req_label ASC
        ");
        $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $types]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM reservation_requirement_types WHERE req_type_id = ?");
        $stmt->execute([$_GET['id']]);
        $type = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $type]);
      }
      break;

    case 'POST':
      requirePermission('settings.edit');
      // Handle file upload
      if ($action === 'upload') {
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
          echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
          exit;
        }

        $reqTypeId = $_POST['req_type_id'] ?? null;
        if (!$reqTypeId) {
          echo json_encode(['success' => false, 'message' => 'Requirement Type ID is required']);
          exit;
        }

        $file = $_FILES['file'];
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $maxSize = 10 * 1024 * 1024; // 10MB

        if (!in_array($file['type'], $allowedTypes)) {
          echo json_encode(['success' => false, 'message' => 'Invalid file type. Only PDF, JPG, PNG, and Word documents are allowed']);
          exit;
        }

        if ($file['size'] > $maxSize) {
          echo json_encode(['success' => false, 'message' => 'File size exceeds 10MB limit']);
          exit;
        }

        // Create upload directory if it doesn't exist
        $uploadDir = __DIR__ . '/../uploads/requirement_templates/';
        if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'template_' . $reqTypeId . '_' . time() . '_' . uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
          echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file']);
          exit;
        }

        // Update requirement type record with template file URL
        $fileUrl = '../uploads/requirement_templates/' . $filename;
        $stmt = $pdo->prepare("UPDATE reservation_requirement_types SET template_url = ?, updated_at = NOW() WHERE req_type_id = ?");
        $stmt->execute([$fileUrl, $reqTypeId]);

        echo json_encode(['success' => true, 'message' => 'Template file uploaded successfully', 'template_url' => $fileUrl]);
        exit;
      }

      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $stmt = $pdo->prepare("
          INSERT INTO reservation_requirement_types (
            req_code, req_label, property_id,
            applies_to, is_mandatory, is_active, sort_order,
            description, template_url, created_at, updated_at
          ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([
          $data['req_code'],
          $data['req_label'],
          $data['property_id'] ?? null,
          $data['applies_to'] ?? '',
          $data['is_mandatory'] ?? 0,
          $data['is_active'] ?? 1,
          $data['sort_order'] ?? 0,
          $data['description'] ?? null,
          $data['template_url'] ?? null
        ]);
        echo json_encode(['success' => true, 'message' => 'Requirement type created successfully', 'req_type_id' => $pdo->lastInsertId()]);
      } else if ($action === 'update') {
        $stmt = $pdo->prepare("
          UPDATE reservation_requirement_types SET
            req_code = ?, req_label = ?, property_id = ?,
            applies_to = ?, is_mandatory = ?, is_active = ?,
            sort_order = ?, description = ?, template_url = ?, updated_at = NOW()
          WHERE req_type_id = ?
        ");
        $stmt->execute([
          $data['req_code'],
          $data['req_label'],
          $data['property_id'] ?? null,
          $data['applies_to'] ?? '',
          $data['is_mandatory'] ?? 0,
          $data['is_active'] ?? 1,
          $data['sort_order'] ?? 0,
          $data['description'] ?? null,
          $data['template_url'] ?? null,
          $data['req_type_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Requirement type updated successfully']);
      } else if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM reservation_requirement_types WHERE req_type_id = ?");
        $stmt->execute([$data['req_type_id']]);
        echo json_encode(['success' => true, 'message' => 'Requirement type deleted successfully']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
