<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        $propertyId = $_GET['property_id'] ?? null;
        
        $sql = "SELECT sr.*, p.property_name, c.category_name 
                FROM seasonal_rates sr 
                INNER JOIN properties p ON sr.property_id = p.property_id 
                LEFT JOIN room_categories c ON sr.applies_to = c.category_id 
                WHERE 1=1";
        $params = [];
        
        if ($propertyId) {
          $sql .= " AND sr.property_id = ?";
          $params[] = $propertyId;
        }
        
        $sql .= " ORDER BY sr.date_start, sr.season_name";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

      } elseif ($action === 'related') {
        $properties = $pdo->query("SELECT property_id, property_name FROM properties ORDER BY property_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $categories = $pdo->query("SELECT category_id, category_name FROM room_categories ORDER BY category_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['properties' => $properties, 'categories' => $categories]]);

      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT sr.*, p.property_name, c.category_name FROM seasonal_rates sr INNER JOIN properties p ON sr.property_id = p.property_id LEFT JOIN room_categories c ON sr.applies_to = c.category_id WHERE sr.season_id = ?");
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $seasonId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        
        $stmt = $pdo->prepare("INSERT INTO seasonal_rates (season_id, property_id, season_name, date_start, date_end, rate_multiplier, applies_to, is_active, created_by) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $seasonId, $data['property_id'], $data['season_name'],
          $data['date_start'], $data['date_end'],
          $data['rate_multiplier'], $data['applies_to'] ?? null,
          $data['is_active'] ?? 1, $data['created_by'] ?? null
        ]);
        echo json_encode(['success' => true, 'message' => 'Seasonal rate created', 'season_id' => $seasonId]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE seasonal_rates SET property_id=?, season_name=?, date_start=?, date_end=?, rate_multiplier=?, applies_to=?, is_active=?, created_by=? WHERE season_id=?");
        $stmt->execute([
          $data['property_id'], $data['season_name'],
          $data['date_start'], $data['date_end'],
          $data['rate_multiplier'], $data['applies_to'] ?? null,
          $data['is_active'] ?? 1, $data['created_by'] ?? null,
          $data['season_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Seasonal rate updated']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM seasonal_rates WHERE season_id=?");
        $stmt->execute([$data['season_id']]);
        echo json_encode(['success' => true, 'message' => 'Seasonal rate deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
