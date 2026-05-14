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
        
        $sql = "SELECT pr.*, p.property_name, c.category_name, r.room_number 
                FROM pricing_rules pr 
                INNER JOIN properties p ON pr.property_id = p.property_id 
                LEFT JOIN room_categories c ON pr.category_id = c.category_id 
                LEFT JOIN rooms r ON pr.room_id = r.room_id 
                WHERE 1=1";
        $params = [];
        
        if ($propertyId) {
          $sql .= " AND pr.property_id = ?";
          $params[] = $propertyId;
        }
        
        $sql .= " ORDER BY pr.priority DESC, pr.rule_name";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

      } elseif ($action === 'related') {
        $properties = $pdo->query("SELECT property_id, property_name FROM properties ORDER BY property_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $categories = $pdo->query("SELECT category_id, category_name FROM room_categories ORDER BY category_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $rooms = $pdo->query("SELECT room_id, room_number, category_id FROM rooms ORDER BY room_number ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['properties' => $properties, 'categories' => $categories, 'rooms' => $rooms]]);

      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT pr.*, p.property_name, c.category_name, r.room_number FROM pricing_rules pr INNER JOIN properties p ON pr.property_id = p.property_id LEFT JOIN room_categories c ON pr.category_id = c.category_id LEFT JOIN rooms r ON pr.room_id = r.room_id WHERE pr.rule_id = ?");
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $ruleId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        
        $stmt = $pdo->prepare("INSERT INTO pricing_rules (rule_id, property_id, category_id, room_id, rule_name, rule_type, applies_to, rule_value, minimum_nights, day_of_week, date_start, date_end, priority, is_active, created_by) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $ruleId, $data['property_id'], $data['category_id'] ?? null, $data['room_id'] ?? null,
          $data['rule_name'], $data['rule_type'], $data['applies_to'] ?? 'all',
          $data['rule_value'], $data['minimum_nights'] ?? null,
          is_array($data['day_of_week']) ? implode(',', $data['day_of_week']) : $data['day_of_week'],
          $data['date_start'] ?? null, $data['date_end'] ?? null,
          $data['priority'] ?? 0, $data['is_active'] ?? 1, $data['created_by'] ?? null
        ]);
        echo json_encode(['success' => true, 'message' => 'Pricing rule created', 'rule_id' => $ruleId]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE pricing_rules SET property_id=?, category_id=?, room_id=?, rule_name=?, rule_type=?, applies_to=?, rule_value=?, minimum_nights=?, day_of_week=?, date_start=?, date_end=?, priority=?, is_active=?, created_by=? WHERE rule_id=?");
        $stmt->execute([
          $data['property_id'], $data['category_id'] ?? null, $data['room_id'] ?? null,
          $data['rule_name'], $data['rule_type'], $data['applies_to'] ?? 'all',
          $data['rule_value'], $data['minimum_nights'] ?? null,
          is_array($data['day_of_week']) ? implode(',', $data['day_of_week']) : $data['day_of_week'],
          $data['date_start'] ?? null, $data['date_end'] ?? null,
          $data['priority'] ?? 0, $data['is_active'] ?? 1, $data['created_by'] ?? null,
          $data['rule_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Pricing rule updated']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM pricing_rules WHERE rule_id=?");
        $stmt->execute([$data['rule_id']]);
        echo json_encode(['success' => true, 'message' => 'Pricing rule deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
