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
    echo json_encode(['success' => false, 'message' => 'Invalid or missing CSRF token']);
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
          SELECT
            rr.*,
            r.reservation_code,
            rt.req_code,
            rt.req_label,
            rg.first_name,
            rg.last_name,
            rg.guest_type
          FROM reservation_requirements rr
          INNER JOIN reservations r ON rr.reservation_id = r.reservation_id
          INNER JOIN reservation_requirement_types rt ON rr.req_type_id = rt.req_type_id
          LEFT JOIN reservation_guests rg ON rr.res_guest_id = rg.res_guest_id
          WHERE r.deleted_at IS NULL
          ORDER BY rr.created_at DESC
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $rows]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT rr.*, r.reservation_code, rt.req_label FROM reservation_requirements rr INNER JOIN reservations r ON rr.reservation_id = r.reservation_id INNER JOIN reservation_requirement_types rt ON rr.req_type_id = rt.req_type_id WHERE rr.req_id = ? AND r.deleted_at IS NULL");
        $stmt->execute([$_GET['id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $row]);
      } else if ($action === 'types') {
        $stmt = $pdo->query("
          SELECT rt.*, p.property_name
          FROM reservation_requirement_types rt
          LEFT JOIN properties p ON rt.property_id = p.property_id
          ORDER BY rt.sort_order ASC, rt.req_label ASC
        ");
        $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $types]);
      } else if ($action === 'by_reservation' && isset($_GET['reservation_id'])) {
        $stmt = $pdo->prepare("
          SELECT rr.*, rt.req_label, rt.req_code, rt.is_mandatory, rt.template_url
          FROM reservation_requirements rr
          INNER JOIN reservation_requirement_types rt ON rr.req_type_id = rt.req_type_id
          WHERE rr.reservation_id = ?
          ORDER BY rt.sort_order ASC
        ");
        $stmt->execute([$_GET['reservation_id']]);
        $requirements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $requirements]);
      }
      break;

    case 'POST':
      requirePermission('reservations.edit');
      if ($action === 'upload') {
        // Handle file upload
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
          echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
          exit;
        }

        $reqId = $_POST['req_id'] ?? null;
        $status = $_POST['status'] ?? 'submitted';

        if (!$reqId) {
          echo json_encode(['success' => false, 'message' => 'Requirement ID is required']);
          exit;
        }

        $file = $_FILES['file'];
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
          echo json_encode(['success' => false, 'message' => 'Invalid file type. Only PDF, JPG, and PNG are allowed']);
          exit;
        }

        if ($file['size'] > $maxSize) {
          echo json_encode(['success' => false, 'message' => 'File size exceeds 5MB limit']);
          exit;
        }

        // Create upload directory if it doesn't exist
        $uploadDir = __DIR__ . '/../uploads/requirements/';
        if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'req_' . $reqId . '_' . time() . '_' . uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
          echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file']);
          exit;
        }

        // Update requirement record with file URL
        $fileUrl = '../uploads/requirements/' . $filename;
        $stmt = $pdo->prepare("UPDATE reservation_requirements SET file_url = ?, status = ?, updated_at = NOW() WHERE req_id = ?");
        $stmt->execute([$fileUrl, $status, $reqId]);

        echo json_encode(['success' => true, 'message' => 'File uploaded successfully', 'file_url' => $fileUrl]);
        exit;
      }

      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $stmt = $pdo->prepare("
          INSERT INTO reservation_requirements (
            req_id, reservation_id, req_type_id, res_guest_id,
            status, file_url, notes, created_at, updated_at
          ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([
          $data['req_id'],
          $data['reservation_id'],
          $data['req_type_id'],
          $data['res_guest_id'] ?? null,
          $data['status'] ?? 'pending',
          $data['file_url'] ?? null,
          $data['notes'] ?? null,
        ]);
        echo json_encode(['success' => true, 'message' => 'Requirement created successfully']);

      } else if ($action === 'update') {
        // FIX: Separate review action (approve/reject) from full update
        // to avoid overwriting file_url and notes with null
        if (isset($data['reviewed_by']) && !array_key_exists('file_url', $data)) {
          // Review-only update: status + reviewed_by + reviewed_at only
          $stmt = $pdo->prepare("
            UPDATE reservation_requirements SET
              status = ?,
              reviewed_by = ?,
              reviewed_at = NOW(),
              updated_at = NOW()
            WHERE req_id = ?
          ");
          $stmt->execute([
            $data['status'],
            $data['reviewed_by'],
            $data['req_id'],
          ]);
        } else {
          // Full update: all fields
          $stmt = $pdo->prepare("
            UPDATE reservation_requirements SET
              status = ?,
              file_url = ?,
              notes = ?,
              reviewed_by = ?,
              reviewed_at = NOW(),
              updated_at = NOW()
            WHERE req_id = ?
          ");
          $stmt->execute([
            $data['status'],
            $data['file_url'] ?? null,
            $data['notes'] ?? null,
            $data['reviewed_by'] ?? null,
            $data['req_id'],
          ]);
        }
        echo json_encode(['success' => true, 'message' => 'Requirement updated successfully']);

      } else if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM reservation_requirements WHERE req_id = ?");
        $stmt->execute([$data['req_id']]);
        echo json_encode(['success' => true, 'message' => 'Requirement deleted successfully']);
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