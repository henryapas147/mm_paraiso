<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$data = null;

// For POST requests, read action from request body
if ($method === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  $action = $data['action'] ?? '';
}

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        // Get total count
        $countStmt = $pdo->query("SELECT COUNT(*) as total FROM amenities");
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Get paginated amenities (support both limit/per_page and offset/page parameters)
        $limit = isset($_GET['per_page']) ? (int)$_GET['per_page'] : (isset($_GET['limit']) ? (int)$_GET['limit'] : 10);
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : (($page - 1) * $limit);
        $stmt = $pdo->prepare("SELECT * FROM amenities ORDER BY amenity_name ASC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        $amenities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $amenities, 'total' => $total]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        // Get single amenity
        $stmt = $pdo->prepare("SELECT * FROM amenities WHERE amenity_id = ?");
        $stmt->execute([$_GET['id']]);
        $amenity = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $amenity]);
      }
      break;

    case 'POST':
      if ($action === 'create') {
        // Validate required fields
        if (empty($data['amenity_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: amenity_id']);
          exit;
        }
        if (empty($data['amenity_name'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: amenity_name']);
          exit;
        }
        try {
          $stmt = $pdo->prepare("INSERT INTO amenities (amenity_id, amenity_name, icon_code, category, is_active, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
          $stmt->execute([
            $data['amenity_id'],
            $data['amenity_name'],
            $data['icon'] ?? $data['icon_code'] ?? null,
            $data['amenity_type'] ?? $data['category'] ?? 'comfort',
            $data['is_active'] ?? 1,
            $data['sort_order'] ?? 0
          ]);
          echo json_encode(['success' => true, 'message' => 'Amenity created successfully']);
        } catch (PDOException $e) {
          echo json_encode(['success' => false, 'message' => 'Database error on create: ' . $e->getMessage()]);
        }
      } else if ($action === 'update') {
        // Validate required fields
        if (empty($data['amenity_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: amenity_id']);
          exit;
        }
        if (empty($data['amenity_name'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: amenity_name']);
          exit;
        }
        try {
          $stmt = $pdo->prepare("UPDATE amenities SET amenity_name = ?, icon_code = ?, category = ?, is_active = ?, sort_order = ?, updated_at = NOW() WHERE amenity_id = ?");
          $result = $stmt->execute([
            $data['amenity_name'],
            $data['icon'] ?? $data['icon_code'] ?? null,
            $data['amenity_type'] ?? $data['category'] ?? 'comfort',
            $data['is_active'] ?? 1,
            $data['sort_order'] ?? 0,
            $data['amenity_id']
          ]);
          $rowCount = $stmt->rowCount();
          echo json_encode(['success' => true, 'message' => 'Amenity updated successfully', 'rows_affected' => $rowCount]);
        } catch (PDOException $e) {
          echo json_encode(['success' => false, 'message' => 'Database error on update: ' . $e->getMessage()]);
        }
      } else if ($action === 'delete') {
        if (empty($data['amenity_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: amenity_id']);
          exit;
        }
        try {
          $stmt = $pdo->prepare("DELETE FROM amenities WHERE amenity_id = ?");
          $result = $stmt->execute([$data['amenity_id']]);
          $rowCount = $stmt->rowCount();
          if ($rowCount === 0) {
            echo json_encode(['success' => false, 'message' => 'No amenity found with id: ' . $data['amenity_id']]);
          } else {
            echo json_encode(['success' => true, 'message' => 'Amenity deleted successfully', 'rows_affected' => $rowCount]);
          }
        } catch (PDOException $e) {
          echo json_encode(['success' => false, 'message' => 'Database error on delete: ' . $e->getMessage()]);
        }
      } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action: ' . $action]);
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
