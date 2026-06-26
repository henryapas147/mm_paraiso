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
      requirePermission('properties.view');
      if ($action === 'list') {
        // Get total count
        $countStmt = $pdo->query("SELECT COUNT(*) as total FROM dining_tables");
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Get paginated dining tables (support both limit/per_page and offset/page parameters)
        $limit = isset($_GET['per_page']) ? (int)$_GET['per_page'] : (isset($_GET['limit']) ? (int)$_GET['limit'] : 10);
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : (($page - 1) * $limit);
        $stmt = $pdo->prepare("SELECT * FROM dining_tables ORDER BY sort_order ASC, table_number ASC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $tables, 'total' => $total]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        // Get single dining table
        $stmt = $pdo->prepare("SELECT * FROM dining_tables WHERE table_id = ?");
        $stmt->execute([$_GET['id']]);
        $table = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $table]);
      }
      break;

    case 'POST':
      if ($action === 'create') {
        requirePermission('properties.edit');
        // Validate required fields
        if (empty($data['table_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: table_id']);
          exit;
        }
        if (empty($data['table_number'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: table_number']);
          exit;
        }
        try {
          $stmt = $pdo->prepare("INSERT INTO dining_tables (table_id, property_id, table_number, table_name, capacity, location, is_occupied, is_active, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
          $stmt->execute([
            $data['table_id'],
            $data['property_id'] ?? null,
            $data['table_number'],
            $data['table_name'] ?? null,
            $data['capacity'] ?? 4,
            $data['location'] ?? null,
            $data['is_occupied'] ?? 0,
            $data['is_active'] ?? 1,
            $data['sort_order'] ?? 0
          ]);
          echo json_encode(['success' => true, 'message' => 'Dining table created successfully']);
        } catch (PDOException $e) {
          echo json_encode(['success' => false, 'message' => 'Database error on create: ' . $e->getMessage()]);
        }
      } else if ($action === 'update') {
        requirePermission('properties.edit');
        // Validate required fields
        if (empty($data['table_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: table_id']);
          exit;
        }
        if (empty($data['table_number'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: table_number']);
          exit;
        }
        try {
          $stmt = $pdo->prepare("UPDATE dining_tables SET property_id = ?, table_number = ?, table_name = ?, capacity = ?, location = ?, is_occupied = ?, is_active = ?, sort_order = ?, updated_at = NOW() WHERE table_id = ?");
          $result = $stmt->execute([
            $data['property_id'] ?? null,
            $data['table_number'],
            $data['table_name'] ?? null,
            $data['capacity'] ?? 4,
            $data['location'] ?? null,
            $data['is_occupied'] ?? 0,
            $data['is_active'] ?? 1,
            $data['sort_order'] ?? 0,
            $data['table_id']
          ]);
          $rowCount = $stmt->rowCount();
          echo json_encode(['success' => true, 'message' => 'Dining table updated successfully', 'rows_affected' => $rowCount]);
        } catch (PDOException $e) {
          echo json_encode(['success' => false, 'message' => 'Database error on update: ' . $e->getMessage()]);
        }
      } else if ($action === 'delete') {
        requirePermission('properties.edit');
        if (empty($data['table_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: table_id']);
          exit;
        }
        try {
          $stmt = $pdo->prepare("DELETE FROM dining_tables WHERE table_id = ?");
          $result = $stmt->execute([$data['table_id']]);
          $rowCount = $stmt->rowCount();
          if ($rowCount === 0) {
            echo json_encode(['success' => false, 'message' => 'No dining table found with id: ' . $data['table_id']]);
          } else {
            echo json_encode(['success' => true, 'message' => 'Dining table deleted successfully', 'rows_affected' => $rowCount]);
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
