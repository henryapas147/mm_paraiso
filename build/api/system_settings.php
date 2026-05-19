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
        $settingGroup = $_GET['setting_group'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT s.*, p.property_name 
                FROM system_settings s 
                LEFT JOIN properties p ON s.property_id = p.property_id 
                WHERE 1=1";
        $params = [];
        
        if ($propertyId) {
          $sql .= " AND (s.property_id = ? OR s.property_id IS NULL)";
          $params[] = $propertyId;
        }
        if ($settingGroup) {
          $sql .= " AND s.setting_group = ?";
          $params[] = $settingGroup;
        }
        
        $sql .= " ORDER BY s.setting_group, s.setting_label";
        
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

      } elseif ($action === 'related') {
        $properties = $pdo->query("SELECT property_id, property_name FROM properties ORDER BY property_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $settingGroups = $pdo->query("SELECT DISTINCT setting_group FROM system_settings ORDER BY setting_group")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['properties' => $properties, 'settingGroups' => $settingGroups]]);

      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT s.*, p.property_name FROM system_settings s LEFT JOIN properties p ON s.property_id = p.property_id WHERE s.setting_id = ?");
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $stmt = $pdo->prepare("INSERT INTO system_settings (property_id, setting_group, setting_key, setting_label, setting_value, data_type, is_public, description, updated_by) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $data['property_id'] ?? null, $data['setting_group'], $data['setting_key'],
          $data['setting_label'] ?? null, $data['setting_value'] ?? null,
          $data['data_type'] ?? 'string', $data['is_public'] ?? 0,
          $data['description'] ?? null, $data['updated_by'] ?? null
        ]);
        echo json_encode(['success' => true, 'message' => 'Setting created', 'setting_id' => $pdo->lastInsertId()]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE system_settings SET property_id=?, setting_group=?, setting_key=?, setting_label=?, setting_value=?, data_type=?, is_public=?, description=?, updated_by=? WHERE setting_id=?");
        $stmt->execute([
          $data['property_id'] ?? null, $data['setting_group'], $data['setting_key'],
          $data['setting_label'] ?? null, $data['setting_value'] ?? null,
          $data['data_type'] ?? 'string', $data['is_public'] ?? 0,
          $data['description'] ?? null, $data['updated_by'] ?? null,
          $data['setting_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Setting updated']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM system_settings WHERE setting_id=?");
        $stmt->execute([$data['setting_id']]);
        echo json_encode(['success' => true, 'message' => 'Setting deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
