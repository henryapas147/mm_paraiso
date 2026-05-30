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
        $reservationId = $_GET['reservation_id'] ?? null;
        $resRoomId = $_GET['res_room_id'] ?? null;
        
        if (!$reservationId && !$resRoomId) {
          echo json_encode(['success' => false, 'message' => 'reservation_id or res_room_id required']);
          exit;
        }
        
        $sql = "SELECT pb.*, pr.rule_name, sr.season_name, p.promo_title, p.promo_code
                FROM reservation_pricing_breakdown pb
                LEFT JOIN pricing_rules pr ON pb.rule_id = pr.rule_id
                LEFT JOIN seasonal_rates sr ON pb.season_id = sr.season_id
                LEFT JOIN promotions p ON pb.promo_id = p.promo_id
                WHERE 1=1";
        $params = [];
        
        if ($reservationId) {
          $sql .= " AND pb.reservation_id = ?";
          $params[] = $reservationId;
        }
        if ($resRoomId) {
          $sql .= " AND pb.res_room_id = ?";
          $params[] = $resRoomId;
        }
        
        $sql .= " ORDER BY pb.sort_order ASC, pb.component_type ASC, pb.created_at ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate totals
        $totals = [
          'total_base' => 0,
          'total_discount' => 0,
          'total_final' => 0
        ];
        foreach ($data as $row) {
          $totals['total_base'] += (float)$row['base_amount'];
          $totals['total_discount'] += (float)$row['discount_amount'];
          $totals['total_final'] += (float)$row['final_amount'];
        }
        
        echo json_encode(['success' => true, 'data' => $data, 'totals' => $totals]);
        
      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT pb.*, pr.rule_name, sr.season_name, p.promo_title, p.promo_code
                              FROM reservation_pricing_breakdown pb
                              LEFT JOIN pricing_rules pr ON pb.rule_id = pr.rule_id
                              LEFT JOIN seasonal_rates sr ON pb.season_id = sr.season_id
                              LEFT JOIN promotions p ON pb.promo_id = p.promo_id
                              WHERE pb.breakdown_id = ?");
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $breakdownId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        
        $stmt = $pdo->prepare("INSERT INTO reservation_pricing_breakdown 
          (breakdown_id, reservation_id, res_room_id, component_type, rule_id, season_id, promo_id,
           component_name, description, base_amount, multiplier, discount_percent, discount_amount, 
           final_amount, sort_order, is_visible) 
          VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $breakdownId,
          $data['reservation_id'],
          $data['res_room_id'] ?? null,
          $data['component_type'],
          $data['rule_id'] ?? null,
          $data['season_id'] ?? null,
          $data['promo_id'] ?? null,
          $data['component_name'],
          $data['description'] ?? null,
          $data['base_amount'] ?? 0,
          $data['multiplier'] ?? null,
          $data['discount_percent'] ?? null,
          $data['discount_amount'] ?? 0,
          $data['final_amount'] ?? ($data['base_amount'] ?? 0),
          $data['sort_order'] ?? 0,
          $data['is_visible'] ?? 1
        ]);
        echo json_encode(['success' => true, 'message' => 'Pricing breakdown entry created', 'breakdown_id' => $breakdownId]);

      } elseif ($action === 'create_batch') {
        // Batch insert multiple pricing components for a reservation
        $entries = $data['entries'] ?? [];
        $breakdownIds = [];
        
        $stmt = $pdo->prepare("INSERT INTO reservation_pricing_breakdown 
          (breakdown_id, reservation_id, res_room_id, component_type, rule_id, season_id, promo_id,
           component_name, description, base_amount, multiplier, discount_percent, discount_amount, 
           final_amount, sort_order, is_visible) 
          VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        
        foreach ($entries as $entry) {
          $breakdownId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
            mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
          
          $stmt->execute([
            $breakdownId,
            $entry['reservation_id'],
            $entry['res_room_id'] ?? null,
            $entry['component_type'],
            $entry['rule_id'] ?? null,
            $entry['season_id'] ?? null,
            $entry['promo_id'] ?? null,
            $entry['component_name'],
            $entry['description'] ?? null,
            $entry['base_amount'] ?? 0,
            $entry['multiplier'] ?? null,
            $entry['discount_percent'] ?? null,
            $entry['discount_amount'] ?? 0,
            $entry['final_amount'] ?? ($entry['base_amount'] ?? 0),
            $entry['sort_order'] ?? 0,
            $entry['is_visible'] ?? 1
          ]);
          $breakdownIds[] = $breakdownId;
        }
        
        echo json_encode(['success' => true, 'message' => count($breakdownIds) . ' pricing entries created', 'breakdown_ids' => $breakdownIds]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE reservation_pricing_breakdown SET 
          reservation_id=?, res_room_id=?, component_type=?, rule_id=?, season_id=?, promo_id=?,
          component_name=?, description=?, base_amount=?, multiplier=?, discount_percent=?, 
          discount_amount=?, final_amount=?, sort_order=?, is_visible=? 
          WHERE breakdown_id=?");
        $stmt->execute([
          $data['reservation_id'],
          $data['res_room_id'] ?? null,
          $data['component_type'],
          $data['rule_id'] ?? null,
          $data['season_id'] ?? null,
          $data['promo_id'] ?? null,
          $data['component_name'],
          $data['description'] ?? null,
          $data['base_amount'] ?? 0,
          $data['multiplier'] ?? null,
          $data['discount_percent'] ?? null,
          $data['discount_amount'] ?? 0,
          $data['final_amount'] ?? ($data['base_amount'] ?? 0),
          $data['sort_order'] ?? 0,
          $data['is_visible'] ?? 1,
          $data['breakdown_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Pricing breakdown entry updated']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM reservation_pricing_breakdown WHERE breakdown_id=?");
        $stmt->execute([$data['breakdown_id']]);
        echo json_encode(['success' => true, 'message' => 'Pricing breakdown entry deleted']);
        
      } elseif ($action === 'delete_by_reservation') {
        // Clear all pricing entries for a reservation (useful when recalculating)
        $stmt = $pdo->prepare("DELETE FROM reservation_pricing_breakdown WHERE reservation_id=? AND component_type != 'deposit'");
        $stmt->execute([$data['reservation_id']]);
        echo json_encode(['success' => true, 'message' => 'Pricing breakdown cleared for reservation', 'deleted_count' => $stmt->rowCount()]);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
