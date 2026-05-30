<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
require_once '../../config/auth.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        $reservationId = $_GET['reservation_id'] ?? null;
        $promoId = $_GET['promo_id'] ?? null;
        
        $sql = "SELECT rp.*, p.promo_title, p.promo_type, p.promo_code,
                       r.reservation_code, r.check_in_date,
                       CONCAT(COALESCE(r.walkin_first_name, up.first_name), ' ', COALESCE(r.walkin_last_name, up.last_name)) as guest_name
                FROM reservation_promotions rp
                INNER JOIN promotions p ON rp.promo_id = p.promo_id
                INNER JOIN reservations r ON rp.reservation_id = r.reservation_id
                LEFT JOIN user_profiles up ON r.customer_user_id = up.user_id
                WHERE 1=1";
        $params = [];
        
        if ($reservationId) {
          $sql .= " AND rp.reservation_id = ?";
          $params[] = $reservationId;
        }
        if ($promoId) {
          $sql .= " AND rp.promo_id = ?";
          $params[] = $promoId;
        }
        
        $sql .= " ORDER BY rp.created_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate promo usage stats
        $stats = [
          'total_discounts' => array_sum(array_column($data, 'discount_amount')),
          'total_uses' => count($data),
          'unique_codes' => count(array_unique(array_column($data, 'promo_code_used')))
        ];
        
        echo json_encode(['success' => true, 'data' => $data, 'stats' => $stats]);
        
      } elseif ($action === 'validate') {
        // Validate a promo code before applying
        $code = $_GET['code'] ?? '';
        $reservationId = $_GET['reservation_id'] ?? null;
        $checkInDate = $_GET['check_in_date'] ?? date('Y-m-d');
        
        if (!$code) {
          echo json_encode(['success' => false, 'message' => 'Promo code required']);
          exit;
        }
        
        // Find active promo with matching code
        $stmt = $pdo->prepare("
          SELECT p.*, pr.property_name
          FROM promotions p
          LEFT JOIN properties pr ON p.property_id = pr.property_id
          WHERE p.promo_code = ?
            AND p.is_active = 1
            AND p.valid_from <= ?
            AND p.valid_until >= ?
        ");
        $stmt->execute([$code, $checkInDate, $checkInDate]);
        $promo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$promo) {
          echo json_encode(['success' => false, 'message' => 'Invalid or expired promo code']);
          exit;
        }
        
        // Check if already used on this reservation
        if ($reservationId) {
          $usedStmt = $pdo->prepare("SELECT COUNT(*) FROM reservation_promotions WHERE reservation_id = ? AND promo_id = ? AND is_applied = 1");
          $usedStmt->execute([$reservationId, $promo['promo_id']]);
          if ($usedStmt->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => 'Promo already applied to this reservation']);
            exit;
          }
        }
        
        echo json_encode(['success' => true, 'promo' => $promo, 'message' => 'Promo code valid']);
      }
      break;

    case 'POST':
      // Validate CSRF token for all POST actions
      $headers = getallheaders();
      $csrfToken = $headers['X-CSRF-Token'] ?? $headers['X-Csrf-Token'] ?? null;
      if (!validateCsrfToken($csrfToken)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Invalid or missing CSRF token']);
        exit;
      }
      
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'apply') {
        // Validate reservation_id exists
        if (empty($data['reservation_id'])) {
          echo json_encode(['success' => false, 'message' => 'Reservation ID is required']);
          exit;
        }
        
        // Check if reservation exists
        $checkRes = $pdo->prepare("SELECT reservation_id FROM reservations WHERE reservation_id = ?");
        $checkRes->execute([$data['reservation_id']]);
        if (!$checkRes->fetch()) {
          echo json_encode(['success' => false, 'message' => 'Reservation not found: ' . $data['reservation_id']]);
          exit;
        }
        
        // Apply promo to reservation
        $resPromoId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        
        $stmt = $pdo->prepare("
          INSERT INTO reservation_promotions 
          (res_promo_id, reservation_id, promo_id, promo_code_used, discount_amount, 
           discount_type, discount_value, validated_by, source_channel, notes) 
          VALUES (?,?,?,?,?,?,?,?,?,?)
        ");
        $stmt->execute([
          $resPromoId,
          $data['reservation_id'],
          $data['promo_id'],
          $data['promo_code_used'] ?? null,
          $data['discount_amount'] ?? 0,
          $data['discount_type'] ?? 'percentage',
          $data['discount_value'] ?? 0,
          $_SESSION['user_id'] ?? null,
          $data['source_channel'] ?? 'staff',
          $data['notes'] ?? null
        ]);
        
        // Also add to pricing breakdown
        $breakdownId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        
        $pbStmt = $pdo->prepare("
          INSERT INTO reservation_pricing_breakdown 
          (breakdown_id, reservation_id, res_room_id, component_type, promo_id, 
           component_name, discount_amount, final_amount, sort_order, is_visible) 
          VALUES (?,?,?,?,?,?,?,?,?,?)
        ");
        $pbStmt->execute([
          $breakdownId,
          $data['reservation_id'],
          $data['res_room_id'] ?? null,
          'promotion',
          $data['promo_id'],
          $data['component_name'] ?? 'Promo Discount',
          $data['discount_amount'] ?? 0,
          -($data['discount_amount'] ?? 0), // Negative = discount
          99, // Promos last
          1
        ]);
        
        // Update reservation totals
        $updateRes = $pdo->prepare("
          UPDATE reservations 
          SET discount_amount = discount_amount + ?, 
              total_amount = total_amount - ?,
              balance_amount = balance_amount - ?
          WHERE reservation_id = ?
        ");
        $updateRes->execute([
          $data['discount_amount'] ?? 0,
          $data['discount_amount'] ?? 0,
          $data['discount_amount'] ?? 0,
          $data['reservation_id']
        ]);
        
        echo json_encode([
          'success' => true, 
          'message' => 'Promo applied successfully',
          'res_promo_id' => $resPromoId,
          'breakdown_id' => $breakdownId
        ]);

      } elseif ($action === 'remove') {
        // Remove/void promo from reservation
        $stmt = $pdo->prepare("UPDATE reservation_promotions SET is_applied = 0, notes = ? WHERE res_promo_id = ?");
        $stmt->execute([$data['notes'] ?? 'Removed by staff', $data['res_promo_id']]);
        
        // Reverse the discount on pricing breakdown
        if ($data['breakdown_id']) {
          $pbStmt = $pdo->prepare("UPDATE reservation_pricing_breakdown SET is_visible = 0, component_name = CONCAT(component_name, ' (Removed)') WHERE breakdown_id = ?");
          $pbStmt->execute([$data['breakdown_id']]);
        }
        
        // Restore reservation totals
        if ($data['discount_amount']) {
          $updateRes = $pdo->prepare("
            UPDATE reservations 
            SET discount_amount = GREATEST(0, discount_amount - ?),
                total_amount = total_amount + ?,
                balance_amount = balance_amount + ?
            WHERE reservation_id = ?
          ");
          $updateRes->execute([
            $data['discount_amount'],
            $data['discount_amount'],
            $data['discount_amount'],
            $data['reservation_id']
          ]);
        }
        
        echo json_encode(['success' => true, 'message' => 'Promo removed']);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("
          UPDATE reservation_promotions 
          SET discount_amount = ?, discount_value = ?, notes = ?, is_applied = ?
          WHERE res_promo_id = ?
        ");
        $stmt->execute([
          $data['discount_amount'] ?? 0,
          $data['discount_value'] ?? 0,
          $data['notes'] ?? null,
          $data['is_applied'] ?? 1,
          $data['res_promo_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Promo updated']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
