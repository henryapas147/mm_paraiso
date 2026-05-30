<?php
header('Content-Type: application/json');
require_once '../config/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        $propertyId = $_GET['property_id'] ?? null;
        $categoryId = $_GET['category_id'] ?? null;
        $policyType = $_GET['policy_type'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT bp.*, p.property_name, c.category_name 
                FROM booking_policies bp 
                LEFT JOIN properties p ON bp.property_id = p.property_id 
                LEFT JOIN room_categories c ON bp.category_id = c.category_id 
                WHERE bp.is_active = 1";
        $params = [];
        
        if ($propertyId) {
          $sql .= " AND (bp.property_id = ? OR bp.property_id IS NULL)";
          $params[] = $propertyId;
        }
        if ($categoryId) {
          $sql .= " AND (bp.category_id = ? OR bp.category_id IS NULL)";
          $params[] = $categoryId;
        }
        if ($policyType) {
          $sql .= " AND bp.policy_type = ?";
          $params[] = $policyType;
        }
        
        $sql .= " ORDER BY bp.policy_type, bp.policy_name";
        
        $countSql = "SELECT COUNT(*) FROM ($sql) as count_table";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();
        
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $totalPages = ceil($total / $limit);
        
        echo json_encode([
          'success' => true,
          'data' => $data,
          'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'totalPages' => $totalPages
          ]
        ]);

      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT bp.*, p.property_name, c.category_name FROM booking_policies bp LEFT JOIN properties p ON bp.property_id = p.property_id LEFT JOIN room_categories c ON bp.category_id = c.category_id WHERE bp.policy_id = ? AND bp.is_active = 1");
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
