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
        
        $sql = "SELECT pr.*, p.property_name 
                FROM promotions pr 
                LEFT JOIN properties p ON pr.property_id = p.property_id 
                WHERE 1=1";
        $params = [];
        
        if ($propertyId) {
          $sql .= " AND (pr.property_id = ? OR pr.property_id IS NULL)";
          $params[] = $propertyId;
        }
        
        $sql .= " ORDER BY pr.sort_order ASC, pr.created_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $promos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($promos as &$promo) {
          $stmt = $pdo->prepare("SELECT * FROM promotion_images WHERE promo_id = ? AND is_active = 1 ORDER BY sort_order ASC");
          $stmt->execute([$promo['promo_id']]);
          $promo['images'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        unset($promo);

        echo json_encode(['success' => true, 'data' => $promos]);

      } elseif ($action === 'related') {
        $properties = $pdo->query("SELECT property_id, property_name FROM properties ORDER BY property_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['properties' => $properties]]);

      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT pr.*, p.property_name FROM promotions pr LEFT JOIN properties p ON pr.property_id = p.property_id WHERE pr.promo_id = ?");
        $stmt->execute([$_GET['id']]);
        $promo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($promo) {
          $stmt = $pdo->prepare("SELECT * FROM promotion_images WHERE promo_id = ? ORDER BY sort_order ASC");
          $stmt->execute([$promo['promo_id']]);
          $promo['images'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        echo json_encode(['success' => true, 'data' => $promo]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $promoId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        
        $appliesTo = is_array($data['applies_to_booking_type']) ? implode(',', $data['applies_to_booking_type']) : $data['applies_to_booking_type'];
        
        $stmt = $pdo->prepare("INSERT INTO promotions (promo_id, property_id, promo_code, promo_title, promo_type, short_desc, full_description, terms_conditions, discount_id, applies_to_booking_type, valid_from, valid_until, is_featured, sort_order, is_active, created_by) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $promoId, $data['property_id'] ?? null, $data['promo_code'] ?? null,
          $data['promo_title'], $data['promo_type'] ?? 'banner',
          $data['short_desc'] ?? null, $data['full_description'] ?? null, $data['terms_conditions'] ?? null,
          $data['discount_id'] ?? null, $appliesTo,
          $data['valid_from'], $data['valid_until'],
          $data['is_featured'] ?? 0, $data['sort_order'] ?? 0,
          $data['is_active'] ?? 1, $data['created_by'] ?? null
        ]);
        echo json_encode(['success' => true, 'message' => 'Promotion created', 'promo_id' => $promoId]);

      } elseif ($action === 'update') {
        $appliesTo = is_array($data['applies_to_booking_type']) ? implode(',', $data['applies_to_booking_type']) : $data['applies_to_booking_type'];
        
        $stmt = $pdo->prepare("UPDATE promotions SET property_id=?, promo_code=?, promo_title=?, promo_type=?, short_desc=?, full_description=?, terms_conditions=?, discount_id=?, applies_to_booking_type=?, valid_from=?, valid_until=?, is_featured=?, sort_order=?, is_active=?, created_by=? WHERE promo_id=?");
        $stmt->execute([
          $data['property_id'] ?? null, $data['promo_code'] ?? null,
          $data['promo_title'], $data['promo_type'] ?? 'banner',
          $data['short_desc'] ?? null, $data['full_description'] ?? null, $data['terms_conditions'] ?? null,
          $data['discount_id'] ?? null, $appliesTo,
          $data['valid_from'], $data['valid_until'],
          $data['is_featured'] ?? 0, $data['sort_order'] ?? 0,
          $data['is_active'] ?? 1, $data['created_by'] ?? null,
          $data['promo_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Promotion updated']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM promotion_images WHERE promo_id=?");
        $stmt->execute([$data['promo_id']]);
        $stmt = $pdo->prepare("DELETE FROM promotions WHERE promo_id=?");
        $stmt->execute([$data['promo_id']]);
        echo json_encode(['success' => true, 'message' => 'Promotion deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
