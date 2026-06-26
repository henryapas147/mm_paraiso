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
      requirePermission('payments.view');
      if ($action === 'list') {
        // Get total count
        $countStmt = $pdo->query("SELECT COUNT(*) as total FROM payment_accounts");
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Get paginated payment accounts (support both limit/per_page and offset/page parameters)
        $limit = isset($_GET['per_page']) ? (int)$_GET['per_page'] : (isset($_GET['limit']) ? (int)$_GET['limit'] : 10);
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : (($page - 1) * $limit);
        $stmt = $pdo->prepare("SELECT pa.*, pm.method_label FROM payment_accounts pa LEFT JOIN payment_methods pm ON pa.method_id = pm.method_id ORDER BY pa.is_default DESC, pa.account_name ASC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $accounts, 'total' => $total]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        // Get single payment account
        $stmt = $pdo->prepare("SELECT pa.*, pm.method_label FROM payment_accounts pa LEFT JOIN payment_methods pm ON pa.method_id = pm.method_id WHERE pa.account_id = ?");
        $stmt->execute([$_GET['id']]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $account]);
      }
      break;

    case 'POST':
      if ($action === 'create') {
        requirePermission('payments.edit');
        // Validate required fields
        if (empty($data['account_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: account_id']);
          exit;
        }
        if (empty($data['property_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: property_id']);
          exit;
        }
        if (empty($data['method_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: method_id']);
          exit;
        }
        if (empty($data['account_name'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: account_name']);
          exit;
        }
        try {
          $stmt = $pdo->prepare("INSERT INTO payment_accounts (account_id, property_id, method_id, account_name, account_number, bank_name, branch_name, qr_code_image, instructions, is_default, is_active, created_at, updated_at, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
          $stmt->execute([
            $data['account_id'],
            $data['property_id'],
            $data['method_id'],
            $data['account_name'],
            $data['account_number'] ?? null,
            $data['bank_name'] ?? null,
            $data['branch_name'] ?? null,
            $data['qr_code_image'] ?? null,
            $data['instructions'] ?? null,
            $data['is_default'] ?? 0,
            $data['is_active'] ?? 1,
            $data['created_by'] ?? null
          ]);
          echo json_encode(['success' => true, 'message' => 'Payment account created successfully']);
        } catch (PDOException $e) {
          echo json_encode(['success' => false, 'message' => 'Database error on create: ' . $e->getMessage()]);
        }
      } else if ($action === 'update') {
        requirePermission('payments.edit');
        // Validate required fields
        if (empty($data['account_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: account_id']);
          exit;
        }
        if (empty($data['property_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: property_id']);
          exit;
        }
        if (empty($data['method_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: method_id']);
          exit;
        }
        if (empty($data['account_name'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: account_name']);
          exit;
        }
        try {
          $stmt = $pdo->prepare("UPDATE payment_accounts SET property_id = ?, method_id = ?, account_name = ?, account_number = ?, bank_name = ?, branch_name = ?, qr_code_image = ?, instructions = ?, is_default = ?, is_active = ?, updated_at = NOW() WHERE account_id = ?");
          $result = $stmt->execute([
            $data['property_id'],
            $data['method_id'],
            $data['account_name'],
            $data['account_number'] ?? null,
            $data['bank_name'] ?? null,
            $data['branch_name'] ?? null,
            $data['qr_code_image'] ?? null,
            $data['instructions'] ?? null,
            $data['is_default'] ?? 0,
            $data['is_active'] ?? 1,
            $data['account_id']
          ]);
          $rowCount = $stmt->rowCount();
          echo json_encode(['success' => true, 'message' => 'Payment account updated successfully', 'rows_affected' => $rowCount]);
        } catch (PDOException $e) {
          echo json_encode(['success' => false, 'message' => 'Database error on update: ' . $e->getMessage()]);
        }
      } else if ($action === 'delete') {
        requirePermission('payments.edit');
        if (empty($data['account_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing required field: account_id']);
          exit;
        }
        try {
          $stmt = $pdo->prepare("DELETE FROM payment_accounts WHERE account_id = ?");
          $result = $stmt->execute([$data['account_id']]);
          $rowCount = $stmt->rowCount();
          if ($rowCount === 0) {
            echo json_encode(['success' => false, 'message' => 'No payment account found with id: ' . $data['account_id']]);
          } else {
            echo json_encode(['success' => true, 'message' => 'Payment account deleted successfully', 'rows_affected' => $rowCount]);
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
