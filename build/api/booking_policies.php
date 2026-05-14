<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        $propertyId = $_GET['property_id'] ?? null;
        $policyType = $_GET['policy_type'] ?? null;
        
        $sql = "SELECT bp.*, p.property_name, c.category_name 
                FROM booking_policies bp 
                LEFT JOIN properties p ON bp.property_id = p.property_id 
                LEFT JOIN room_categories c ON bp.category_id = c.category_id 
                WHERE 1=1";
        $params = [];
        
        if ($propertyId) {
          $sql .= " AND (bp.property_id = ? OR bp.property_id IS NULL)";
          $params[] = $propertyId;
        }
        if ($policyType) {
          $sql .= " AND bp.policy_type = ?";
          $params[] = $policyType;
        }
        
        $sql .= " ORDER BY bp.policy_type, bp.policy_name";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

      } elseif ($action === 'related') {
        $properties = $pdo->query("SELECT property_id, property_name FROM properties ORDER BY property_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $categories = $pdo->query("SELECT category_id, category_name FROM room_categories ORDER BY category_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $policyTypes = $pdo->query("SELECT DISTINCT policy_type FROM booking_policies ORDER BY policy_type")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['properties' => $properties, 'categories' => $categories, 'policyTypes' => $policyTypes]]);

      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT bp.*, p.property_name, c.category_name FROM booking_policies bp LEFT JOIN properties p ON bp.property_id = p.property_id LEFT JOIN room_categories c ON bp.category_id = c.category_id WHERE bp.policy_id = ?");
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $policyId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        
        $stmt = $pdo->prepare("INSERT INTO booking_policies (policy_id, property_id, category_id, policy_type, policy_name, description, free_cancel_hours, penalty_type, penalty_value, deposit_type, deposit_value, min_nights, is_active, created_by) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $policyId, $data['property_id'] ?? null, $data['category_id'] ?? null,
          $data['policy_type'], $data['policy_name'], $data['description'],
          $data['free_cancel_hours'] ?? null, $data['penalty_type'] ?? null, $data['penalty_value'] ?? null,
          $data['deposit_type'] ?? null, $data['deposit_value'] ?? null, $data['min_nights'] ?? null,
          $data['is_active'] ?? 1, $data['created_by'] ?? null
        ]);
        echo json_encode(['success' => true, 'message' => 'Booking policy created', 'policy_id' => $policyId]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE booking_policies SET property_id=?, category_id=?, policy_type=?, policy_name=?, description=?, free_cancel_hours=?, penalty_type=?, penalty_value=?, deposit_type=?, deposit_value=?, min_nights=?, is_active=?, created_by=? WHERE policy_id=?");
        $stmt->execute([
          $data['property_id'] ?? null, $data['category_id'] ?? null,
          $data['policy_type'], $data['policy_name'], $data['description'],
          $data['free_cancel_hours'] ?? null, $data['penalty_type'] ?? null, $data['penalty_value'] ?? null,
          $data['deposit_type'] ?? null, $data['deposit_value'] ?? null, $data['min_nights'] ?? null,
          $data['is_active'] ?? 1, $data['created_by'] ?? null, $data['policy_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Booking policy updated']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM booking_policies WHERE policy_id=?");
        $stmt->execute([$data['policy_id']]);
        echo json_encode(['success' => true, 'message' => 'Booking policy deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
