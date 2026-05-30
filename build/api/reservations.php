<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
require_once '../services/EmailService.php';
applyAuthMiddleware();

use App\Services\EmailService;

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  /* ─── PRICING BREAKDOWN HELPER ─────────────────────────────────────────── */
  
  /**
   * Calculate and save pricing breakdown for a reservation
   * This creates audit trail entries linking to pricing_rules, seasonal_rates, promos
   */
  function savePricingBreakdown($pdo, $reservationId, $resRoomId, $propertyId, $categoryId, $checkInDate, $totalAmount, $bookingType = 'overnight', $skipBaseRate = false) {
    $sortOrder = $skipBaseRate ? 1 : 0; // Start at 1 if base_rate already created
    $entries = [];
    $baseAmount = $totalAmount;
    
    // 1. Base Rate (skip if already created by caller)
    if (!$skipBaseRate) {
      $breakdownId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
        mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
      
      $stmt = $pdo->prepare("INSERT INTO reservation_pricing_breakdown 
        (breakdown_id, reservation_id, res_room_id, component_type, component_name, 
         base_amount, final_amount, sort_order, is_visible) 
        VALUES (?,?,?,?,?,?,?,?,?)");
      $stmt->execute([
        $breakdownId, $reservationId, $resRoomId, 'base_rate', 'Base Room Rate',
        $baseAmount, $baseAmount, $sortOrder++, 1
      ]);
      $entries[] = $breakdownId;
    }
    
    // 2. Check for applicable seasonal rates
    $seasonStmt = $pdo->prepare("
      SELECT season_id, season_name, rate_multiplier 
      FROM seasonal_rates 
      WHERE property_id = ? 
        AND is_active = 1 
        AND date_start <= ? 
        AND date_end >= ?
        AND (applies_to IS NULL OR applies_to = ?)
      ORDER BY rate_multiplier DESC
      LIMIT 1
    ");
    $seasonStmt->execute([$propertyId, $checkInDate, $checkInDate, $categoryId]);
    $season = $seasonStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($season && $season['rate_multiplier'] != 1.0) {
      $multiplier = floatval($season['rate_multiplier']);
      $adjustedAmount = $baseAmount * $multiplier;
      $seasonBreakdownId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
        mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
      
      $stmt->execute([
        $seasonBreakdownId, $reservationId, $resRoomId, 'seasonal_rate', $season['season_name'],
        $baseAmount, $adjustedAmount, $sortOrder++, 1
      ]);
      // Update with proper references
      $updateStmt = $pdo->prepare("
        UPDATE reservation_pricing_breakdown 
        SET season_id = ?, multiplier = ?, final_amount = ?
        WHERE breakdown_id = ?
      ");
      $updateStmt->execute([$season['season_id'], $multiplier, $adjustedAmount, $seasonBreakdownId]);
      $entries[] = $seasonBreakdownId;
      $baseAmount = $adjustedAmount; // Carry forward for next calculation
    }
    
    // 3. Check for applicable pricing rules
    $dayOfWeek = strtolower(date('D', strtotime($checkInDate))); // mon, tue, etc.
    $rulesStmt = $pdo->prepare("
      SELECT rule_id, rule_name, rule_type, rule_value, applies_to
      FROM pricing_rules 
      WHERE property_id = ? 
        AND is_active = 1 
        AND (category_id IS NULL OR category_id = ?)
        AND (applies_to = 'all' OR applies_to = ?)
        AND (date_start IS NULL OR date_start <= ?)
        AND (date_end IS NULL OR date_end >= ?)
        AND (day_of_week IS NULL OR FIND_IN_SET(?, day_of_week))
      ORDER BY priority DESC
    ");
    $rulesStmt->execute([$propertyId, $categoryId, $bookingType, $checkInDate, $checkInDate, $dayOfWeek]);
    $rules = $rulesStmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($rules as $rule) {
      $ruleBreakdownId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
        mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
      
      $ruleValue = floatval($rule['rule_value']);
      $adjustedAmount = $baseAmount;
      $discountAmount = 0;
      $multiplier = null;
      
      switch ($rule['rule_type']) {
        case 'percentage_add':
          $multiplier = 1 + ($ruleValue / 100);
          $adjustedAmount = $baseAmount * $multiplier;
          break;
        case 'percentage_discount':
          $discountAmount = $baseAmount * ($ruleValue / 100);
          $adjustedAmount = $baseAmount - $discountAmount;
          break;
        case 'fixed_add':
          $adjustedAmount = $baseAmount + $ruleValue;
          break;
        case 'fixed_price':
          $adjustedAmount = $ruleValue;
          break;
      }
      
      $stmt->execute([
        $ruleBreakdownId, $reservationId, $resRoomId, 'pricing_rule', $rule['rule_name'],
        $baseAmount, $adjustedAmount, $sortOrder++, 1
      ]);
      
      // Update with rule details
      $updateStmt = $pdo->prepare("
        UPDATE reservation_pricing_breakdown 
        SET rule_id = ?, multiplier = ?, discount_amount = ?, final_amount = ?
        WHERE breakdown_id = ?
      ");
      $updateStmt->execute([
        $rule['rule_id'], 
        $multiplier, 
        $discountAmount,
        $adjustedAmount, 
        $ruleBreakdownId
      ]);
      $entries[] = $ruleBreakdownId;
      $baseAmount = $adjustedAmount;
    }
    
    // 4. Check for applicable automatic promotions (non-code based)
    $promoStmt = $pdo->prepare("
      SELECT promo_id, promo_title, promo_type, discount_id
      FROM promotions 
      WHERE (property_id IS NULL OR property_id = ?)
        AND is_active = 1 
        AND valid_from <= ?
        AND valid_until >= ?
        AND (promo_type = 'banner' OR promo_type = 'seasonal_deal')
        AND promo_code IS NULL
      ORDER BY sort_order ASC
    ");
    $promoStmt->execute([$propertyId, $checkInDate, $checkInDate]);
    $promos = $promoStmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($promos as $promo) {
      // Get discount details - simplified, would need discount_rules table in full implementation
      $promoBreakdownId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
        mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
      
      // Default 10% discount for auto-promos (would come from discount_rules table)
      $promoDiscount = $baseAmount * 0.10; 
      $adjustedAmount = $baseAmount - $promoDiscount;
      
      $stmt->execute([
        $promoBreakdownId, $reservationId, $resRoomId, 'promotion', $promo['promo_title'],
        $baseAmount, $adjustedAmount, $sortOrder++, 1
      ]);
      
      $updateStmt = $pdo->prepare("
        UPDATE reservation_pricing_breakdown 
        SET promo_id = ?, discount_amount = ?, final_amount = ?
        WHERE breakdown_id = ?
      ");
      $updateStmt->execute([$promo['promo_id'], $promoDiscount, $adjustedAmount, $promoBreakdownId]);
      
      $entries[] = $promoBreakdownId;
      $baseAmount = $adjustedAmount;
    }
    
    return $entries;
  }

  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        $page   = max(1, intval($_GET['page'] ?? 1));
        $limit  = max(1, min(100, intval($_GET['limit'] ?? 10)));
        $offset = ($page - 1) * $limit;

        $where  = [];
        $params = [];

        if (!empty($_GET['search'])) {
            $s = '%' . $_GET['search'] . '%';
            $where[] = "(r.reservation_code LIKE ? OR r.walkin_first_name LIKE ? OR r.walkin_last_name LIKE ? OR r.walkin_email LIKE ? OR r.walkin_phone LIKE ? OR up.first_name LIKE ? OR up.last_name LIKE ? OR ua.email LIKE ?)";
            array_push($params, $s, $s, $s, $s, $s, $s, $s, $s);
        }
        if (!empty($_GET['status'])) {
            $where[] = "r.reservation_status = ?";
            $params[] = $_GET['status'];
        }
        if (!empty($_GET['payment_status'])) {
            $where[] = "r.payment_status = ?";
            $params[] = $_GET['payment_status'];
        }
        if (!empty($_GET['source_channel'])) {
            $where[] = "r.source_channel = ?";
            $params[] = $_GET['source_channel'];
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $baseSelect = "
          FROM reservations r
          LEFT JOIN properties p ON r.property_id = p.property_id
          LEFT JOIN user_accounts ua ON r.customer_user_id = ua.user_id
          LEFT JOIN user_profiles up ON r.customer_user_id = up.user_id
          $whereClause
        ";

        $countStmt = $pdo->prepare("SELECT COUNT(*) $baseSelect");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $dataStmt = $pdo->prepare("
          SELECT
            r.*,
            p.property_name,
            p.property_code,
            CONCAT(COALESCE(r.walkin_first_name, up.first_name), ' ', COALESCE(r.walkin_last_name, up.last_name)) as guest_name,
            COALESCE(r.walkin_email, ua.email) as guest_email,
            COALESCE(r.walkin_phone, ua.phone_number) as guest_phone
          $baseSelect
          ORDER BY r.created_at DESC
          LIMIT $limit OFFSET $offset
        ");
        $dataStmt->execute($params);
        $reservations = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
          'success'    => true,
          'data'       => $reservations,
          'pagination' => ['total' => $total, 'page' => $page, 'limit' => $limit, 'pages' => ceil($total / $limit)]
        ]);
      } else if ($action === 'my_reservations') {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
          echo json_encode(['success' => false, 'message' => 'Not authenticated']);
          exit;
        }
        $stmt = $pdo->prepare("
          SELECT
            r.*,
            p.property_name,
            p.property_code,
            CONCAT(COALESCE(r.walkin_first_name, up.first_name), ' ', COALESCE(r.walkin_last_name, up.last_name)) as guest_name,
            COALESCE(r.walkin_email, ua.email) as guest_email,
            COALESCE(r.walkin_phone, ua.phone_number) as guest_phone
          FROM reservations r
          LEFT JOIN properties p ON r.property_id = p.property_id
          LEFT JOIN user_accounts ua ON r.customer_user_id = ua.user_id
          LEFT JOIN user_profiles up ON r.customer_user_id = up.user_id
          WHERE r.customer_user_id = ?
          ORDER BY r.check_in_date DESC
        ");
        $stmt->execute([$userId]);
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $reservations]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("
          SELECT r.*, p.property_name,
            CONCAT(COALESCE(r.walkin_first_name, up.first_name), ' ', COALESCE(r.walkin_last_name, up.last_name)) as guest_name,
            COALESCE(r.walkin_email, ua.email) as guest_email,
            COALESCE(r.walkin_phone, ua.phone_number) as guest_phone,
            pm.method_label as payment_method_label,
            pay.amount as amount_paid,
            pay.reference_number as transaction_reference,
            pay.transaction_type as payment_option,
            GROUP_CONCAT(pa.file_path) as payment_attachments
          FROM reservations r
          LEFT JOIN properties p ON r.property_id = p.property_id
          LEFT JOIN user_accounts ua ON r.customer_user_id = ua.user_id
          LEFT JOIN user_profiles up ON r.customer_user_id = up.user_id
          LEFT JOIN payments pay ON r.reservation_id = pay.reservation_id
          LEFT JOIN payment_methods pm ON pay.method_id = pm.method_id
          LEFT JOIN payment_attachments pa ON pay.payment_id = pa.payment_id
          WHERE r.reservation_id = ?
          GROUP BY r.reservation_id
        ");
        $stmt->execute([$_GET['id']]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($reservation) {
            $reqStmt = $pdo->prepare("
                SELECT rr.req_id, rr.status, rr.notes, rr.file_url,
                       rt.req_label, rt.req_code, rt.is_mandatory
                FROM reservation_requirements rr
                INNER JOIN reservation_requirement_types rt ON rr.req_type_id = rt.req_type_id
                WHERE rr.reservation_id = ?
                ORDER BY rt.sort_order ASC
            ");
            $reqStmt->execute([$_GET['id']]);
            $reservation['requirements'] = $reqStmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Fetch pricing breakdown
            $pbStmt = $pdo->prepare("
                SELECT pb.*, pr.rule_name, pr.rule_type, sr.season_name, p.promo_title, p.promo_code
                FROM reservation_pricing_breakdown pb
                LEFT JOIN pricing_rules pr ON pb.rule_id = pr.rule_id
                LEFT JOIN seasonal_rates sr ON pb.season_id = sr.season_id
                LEFT JOIN promotions p ON pb.promo_id = p.promo_id
                WHERE pb.reservation_id = ?
                ORDER BY pb.sort_order ASC, pb.component_type ASC
            ");
            $pbStmt->execute([$_GET['id']]);
            $breakdown = $pbStmt->fetchAll(PDO::FETCH_ASSOC);
            $reservation['pricing_breakdown'] = $breakdown;
            
            // Calculate totals
            $reservation['pricing_totals'] = [
              'total_base' => array_sum(array_column($breakdown, 'base_amount')),
              'total_discount' => array_sum(array_column($breakdown, 'discount_amount')),
              'total_final' => array_sum(array_column($breakdown, 'final_amount'))
            ];
            
            // Fetch applied promotions
            $promoStmt = $pdo->prepare("
                SELECT rp.*, p.promo_title, p.promo_type, p.promo_code
                FROM reservation_promotions rp
                INNER JOIN promotions p ON rp.promo_id = p.promo_id
                WHERE rp.reservation_id = ? AND rp.is_applied = 1
                ORDER BY rp.created_at ASC
            ");
            $promoStmt->execute([$_GET['id']]);
            $reservation['applied_promotions'] = $promoStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode(['success' => true, 'data' => $reservation]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $stmt = $pdo->prepare("
          INSERT INTO reservations (
            reservation_id, reservation_code, property_id, customer_user_id,
            booking_type, reservation_status, payment_status,
            check_in_date, check_in_time, check_out_date, check_out_time,
            num_adults, num_children, num_infants, num_seniors,
            walkin_first_name, walkin_last_name, walkin_email, walkin_phone,
            total_amount, notes, created_at, updated_at
          ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([
          $data['reservation_id'],
          $data['reservation_code'],
          $data['property_id'],
          $data['customer_user_id'] ?? null,
          $data['booking_type'],
          $data['reservation_status'] ?? 'pending',
          $data['payment_status'] ?? 'unpaid',
          $data['check_in_date'],
          $data['check_in_time'] ?? '14:00:00',
          $data['check_out_date'] ?? null,
          $data['check_out_time'] ?? null,
          $data['num_adults'] ?? 1,
          $data['num_children'] ?? 0,
          $data['num_infants'] ?? 0,
          $data['num_seniors'] ?? 0,
          $data['walkin_first_name'] ?? null,
          $data['walkin_last_name'] ?? null,
          $data['walkin_email'] ?? null,
          $data['walkin_phone'] ?? null,
          $data['total_amount'] ?? 0,
          $data['notes'] ?? null,
        ]);
        echo json_encode(['success' => true, 'message' => 'Reservation created successfully']);
      } else if ($action === 'update') {
        $stmt = $pdo->prepare("
          UPDATE reservations SET
            reservation_status = ?, payment_status = ?,
            check_in_date = ?, check_in_time = ?,
            check_out_date = ?, check_out_time = ?,
            num_adults = ?, num_children = ?, num_infants = ?, num_seniors = ?,
            total_amount = ?, special_requests = ?,
            walkin_first_name = ?, walkin_last_name = ?, walkin_email = ?, walkin_phone = ?,
            updated_at = NOW()
          WHERE reservation_id = ?
        ");
        $stmt->execute([
          $data['reservation_status'],
          $data['payment_status'],
          $data['check_in_date'],
          $data['check_in_time'],
          $data['check_out_date'] ?? null,
          $data['check_out_time'] ?? null,
          $data['num_adults'],
          $data['num_children'],
          $data['num_infants'],
          $data['num_seniors'],
          $data['total_amount'],
          $data['special_requests'] ?? null,
          $data['first_name'] ?? null,
          $data['last_name'] ?? null,
          $data['email'] ?? null,
          $data['phone'] ?? null,
          $data['reservation_id'],
        ]);
        echo json_encode(['success' => true, 'message' => 'Reservation updated successfully']);
      } else if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM reservations WHERE reservation_id = ?");
        $stmt->execute([$data['reservation_id']]);
        echo json_encode(['success' => true, 'message' => 'Reservation deleted successfully']);
      } else if ($action === 'update_status') {
        $stmt = $pdo->prepare("
          UPDATE reservations SET reservation_status = ?, updated_at = NOW()
          WHERE reservation_id = ?
        ");
        $stmt->execute([$data['status'], $data['reservation_id']]);
        
        // Send email notification if requested
        if (isset($data['send_notification']) && $data['send_notification']) {
          try {
            // Get reservation details
            $stmt = $pdo->prepare("
              SELECT r.*, p.property_name,
                CONCAT(COALESCE(r.walkin_first_name, up.first_name), ' ', COALESCE(r.walkin_last_name, up.last_name)) as guest_name,
                COALESCE(r.walkin_email, ua.email) as guest_email,
                COALESCE(r.walkin_phone, ua.phone_number) as guest_phone
              FROM reservations r
              LEFT JOIN properties p ON r.property_id = p.property_id
              LEFT JOIN user_accounts ua ON r.customer_user_id = ua.user_id
              LEFT JOIN user_profiles up ON r.customer_user_id = up.user_id
              WHERE r.reservation_id = ?
            ");
            $stmt->execute([$data['reservation_id']]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($reservation && $reservation['guest_email']) {
              $emailService = new EmailService($pdo);
              
              if ($data['status'] === 'confirmed') {
                $emailResult = $emailService->sendReservationStatusUpdate([
                  'reservation_code' => $reservation['reservation_code'],
                  'first_name' => $reservation['walkin_first_name'] ?? '',
                  'last_name' => $reservation['walkin_last_name'] ?? '',
                  'email' => $reservation['guest_email'],
                  'status' => 'confirmed',
                  'check_in_date' => $reservation['check_in_date'],
                  'check_in_time' => $reservation['check_in_time'],
                  'total_amount' => $reservation['total_amount']
                ]);
                
                if (!$emailResult['success']) {
                  error_log("Failed to send confirmation email: " . $emailResult['message']);
                }
              } elseif ($data['status'] === 'cancelled') {
                $emailResult = $emailService->sendReservationStatusUpdate([
                  'reservation_code' => $reservation['reservation_code'],
                  'first_name' => $reservation['walkin_first_name'] ?? '',
                  'last_name' => $reservation['walkin_last_name'] ?? '',
                  'email' => $reservation['guest_email'],
                  'status' => 'cancelled',
                  'check_in_date' => $reservation['check_in_date'],
                  'check_in_time' => $reservation['check_in_time']
                ]);
                
                if (!$emailResult['success']) {
                  error_log("Failed to send rejection email: " . $emailResult['message']);
                }
              }
            }
          } catch (Exception $e) {
            error_log("Failed to send status update email: " . $e->getMessage());
          }
        }
        
        echo json_encode(['success' => true, 'message' => 'Reservation status updated successfully']);
      } else if ($action === 'create_walkin') {
        $pdo->beginTransaction();
        
        // Generate reservation code
        $reservationCode = 'RES-' . strtoupper(substr(uniqid(), -8));
        $reservationId = 'res-' . uniqid();
        
        // Calculate payment status
        $totalAmount = floatval($data['totalAmount']); // Already discounted amount from frontend
        $promoDiscount = floatval($data['promoDiscount'] ?? 0);
        $subtotalAmount = !empty($data['subtotalAmount']) ? floatval($data['subtotalAmount']) : ($totalAmount + $promoDiscount);
        $paymentAmount = floatval($data['paymentAmount']);
        $balanceAmount = $totalAmount - $paymentAmount;
        
        if ($paymentAmount >= $totalAmount) {
          $paymentStatus = 'paid';
        } elseif ($paymentAmount > 0) {
          $paymentStatus = 'partial';
        } else {
          $paymentStatus = 'unpaid';
        }
        
        // Set check-out date/time based on booking type
        $checkOutDate = null;
        $checkOutTime = null;
        $durationHours = null;
        
        if ($data['bookingType'] === 'hourly') {
          $durationHours = floatval($data['duration']);
          // Calculate check-out date/time from check-in + duration
          $checkInDateTime = new DateTime($data['checkInDate'] . ' ' . $data['checkInTime']);
          $checkInDateTime->modify('+ ' . $durationHours . ' hours');
          $checkOutDate = $checkInDateTime->format('Y-m-d');
          $checkOutTime = $checkInDateTime->format('H:i:s');
        } else {
          $checkOutDate = $data['checkOutDate'];
          $checkOutTime = $data['checkOutTime'];
        }
        
        // Create reservation - track both original (subtotal) and discounted (total) amounts
        $stmt = $pdo->prepare("
          INSERT INTO reservations (
            reservation_id, property_id, reservation_code, customer_user_id,
            walkin_first_name, walkin_last_name, walkin_email, walkin_phone,
            booking_type, check_in_date, check_out_date, check_in_time, check_out_time,
            duration_hours, actual_check_in_at,
            num_adults, num_children, num_seniors, num_infants,
            reservation_status, source_channel, special_requests,
            subtotal_amount, discount_amount, total_amount, paid_amount, balance_amount, payment_status,
            confirmed_at, confirmed_by, created_by
          ) VALUES (
            ?, ?, ?, NULL,
            ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, NOW(),
            ?, ?, ?, ?,
            'checked_in', 'walk_in', ?,
            ?, ?, ?, ?, ?, ?,
            NOW(), ?, ?
          )
        ");
        $stmt->execute([
          $reservationId,
          $data['propertyId'],
          $reservationCode,
          $data['firstName'],
          $data['lastName'],
          $data['email'] ?? null,
          $data['phone'],
          $data['bookingType'],
          $data['checkInDate'],
          $checkOutDate,
          $data['checkInTime'],
          $checkOutTime,
          $durationHours,
          $data['adults'],
          $data['children'],
          $data['seniors'],
          $data['infants'],
          $data['notes'] ?? null,
          $subtotalAmount,  // Original amount before discount
          $promoDiscount,   // Discount amount
          $totalAmount,     // Final amount after discount
          $paymentAmount,
          $balanceAmount,
          $paymentStatus,
          $_SESSION['user_id'] ?? null,
          $_SESSION['user_id'] ?? null
        ]);
        
        $resRoomId = null; // Will be set if room is selected
        
        // Only create reservation_room if a room was selected
        if (!empty($data['roomId'])) {
          // Get room details
          $roomStmt = $pdo->prepare("SELECT * FROM rooms WHERE room_id = ?");
          $roomStmt->execute([$data['roomId']]);
          $room = $roomStmt->fetch(PDO::FETCH_ASSOC);
          
          if ($room) {
            // Create reservation_room - store original price (before discount)
            $resRoomId = 'rr-' . uniqid();
            $roomTotal = $subtotalAmount; // Original amount before promo discount
            
            $rrStmt = $pdo->prepare("
              INSERT INTO reservation_rooms (
                res_room_id, reservation_id, room_id, category_id,
                booking_type, price_per_night, price_per_hour,
                num_nights, num_hours, room_total,
                room_status, actual_check_in_at
              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'checked_in', NOW())
            ");
            $rrStmt->execute([
              $resRoomId,
              $reservationId,
              $data['roomId'],
              $room['category_id'] ?? null,
              $data['bookingType'],
              $roomTotal,
              $roomTotal,
              1,
              $durationHours ?? 0,
              $roomTotal
            ]);
            
            // Calculate and save pricing breakdown (use subtotal as base, skip base_rate since already created)
            savePricingBreakdown(
              $pdo, 
              $reservationId, 
              $resRoomId, 
              $data['propertyId'], 
              $room['category_id'] ?? null, 
              $data['checkInDate'], 
              $subtotalAmount, // Original amount before discount
              $data['bookingType'],
              true // skipBaseRate - already created above
            );
          }
        }
        
        // Add primary guest to reservation_guests
        $guestId = 'guest-' . uniqid();
        $guestStmt = $pdo->prepare("
          INSERT INTO reservation_guests (
            res_guest_id, reservation_id, guest_type, first_name, last_name,
            created_at, updated_at
          ) VALUES (?, ?, 'primary', ?, ?, NOW(), NOW())
        ");
        $guestStmt->execute([
          $guestId,
          $reservationId,
          $data['firstName'],
          $data['lastName']
        ]);
        
        // Create payment if amount > 0
        if ($paymentAmount > 0) {
          $paymentId = 'pay-' . uniqid();
          
          // Get payment method ID from code
          $methodStmt = $pdo->prepare("SELECT method_id FROM payment_methods WHERE method_code = ? AND is_active = 1");
          $methodStmt->execute([$data['paymentMethod']]);
          $method = $methodStmt->fetch(PDO::FETCH_ASSOC);
          $methodId = $method ? $method['method_id'] : null;
          
          $payStmt = $pdo->prepare("
            INSERT INTO payments (
              payment_id, reservation_id, method_id, transaction_type, amount,
              reference_number, payment_status, payment_date, payment_time,
              created_at, updated_at
            ) VALUES (?, ?, ?, 'payment', ?, ?, 'paid', ?, ?, NOW(), NOW())
          ");
          $payStmt->execute([
            $paymentId,
            $reservationId,
            $methodId,
            $paymentAmount,
            $data['reference'] ?? null,
            $data['checkInDate'],
            $data['checkInTime']
          ]);
        }
        
        // Insert single entrance_log for primary guest (walk-ins only have primary guest name)
        $staffId  = $_SESSION['user_id'] ?? null;
        $propId   = $data['propertyId'];
        $entryAt  = $data['checkInDate'] . ' ' . $data['checkInTime'];
        $guestDisplayName = trim(($data['firstName'] ?? '') . ' ' . ($data['lastName'] ?? ''));
        $guestNotes = $data['notes'] ?? null;

        $entryStmt = $pdo->prepare("
          INSERT INTO entrance_logs (
            entry_id, property_id, reservation_id, res_guest_id,
            visitor_name, visitor_type, entry_type, event_at,
            logged_by, notes, created_at
          ) VALUES (?, ?, ?, ?, ?, ?, 'entry', ?, ?, ?, NOW())
        ");
        $entryStmt->execute([
          'el-' . uniqid(),
          $propId,
          $reservationId,
          $guestId,
          $guestDisplayName,
          'adult',
          $entryAt,
          $staffId,
          $guestNotes,
        ]);
        
        // Create base_rate pricing breakdown entry for ALL walk-ins (entrance fee or room)
        $baseBreakdownId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        
        $pbStmt = $pdo->prepare("
          INSERT INTO reservation_pricing_breakdown 
          (breakdown_id, reservation_id, res_room_id, component_type, component_name, 
           base_amount, final_amount, sort_order, is_visible) 
          VALUES (?,?,?,?,?,?,?,?,?)
        ");
        $pbStmt->execute([
          $baseBreakdownId,
          $reservationId,
          $resRoomId, // NULL if no room selected
          'base_rate',
          $resRoomId ? 'Base Room Rate' : 'Entrance Fee',
          $subtotalAmount, // Original amount before discount
          $subtotalAmount,
          0, // First in sort order
          1
        ]);
        
        // Apply promo code if provided - Populate BOTH discount_usage (billing) and reservation_promotions (marketing)
        if (!empty($data['promoId']) && !empty($data['promoDiscount']) && $data['promoDiscount'] > 0) {
          // First, get the linked discount_id from promotions table
          $promoLookup = $pdo->prepare("SELECT discount_id, promo_title FROM promotions WHERE promo_id = ?");
          $promoLookup->execute([$data['promoId']]);
          $promoData = $promoLookup->fetch(PDO::FETCH_ASSOC);
          $linkedDiscountId = $promoData['discount_id'] ?? null;
          
          // 1. RECORD IN discount_usages (Billing Layer - for invoices)
          if ($linkedDiscountId) {
            $usageId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
              mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
              mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
              mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
            
            $usageStmt = $pdo->prepare("
              INSERT INTO discount_usages 
              (usage_id, discount_id, reservation_id, user_id, amount_saved, applied_at) 
              VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $usageStmt->execute([
              $usageId,
              $linkedDiscountId,
              $reservationId,
              $_SESSION['user_id'] ?? null,
              $data['promoDiscount']
            ]);
            
            // Update discount usage count
            $updateCount = $pdo->prepare("UPDATE discounts SET usage_count = usage_count + 1 WHERE discount_id = ?");
            $updateCount->execute([$linkedDiscountId]);
          }
          
          // 2. RECORD IN reservation_promotions (Marketing Layer - for attribution)
          $resPromoId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
            mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
          
          $promoStmt = $pdo->prepare("
            INSERT INTO reservation_promotions 
            (res_promo_id, reservation_id, promo_id, promo_code_used, discount_amount, 
             discount_type, discount_value, validated_by, source_channel, is_applied) 
            VALUES (?,?,?,?,?,?,?,?,?,?)
          ");
          $promoStmt->execute([
            $resPromoId,
            $reservationId,
            $data['promoId'],
            $data['promoCode'] ?? null,
            $data['promoDiscount'],
            $linkedDiscountId ? 'percentage' : 'fixed', // Infer from discount link
            $linkedDiscountId ? 10 : $data['promoDiscount'], // Use actual value if linked
            $_SESSION['user_id'] ?? null,
            'walk_in',
            1
          ]);
          
          // 3. Add to pricing breakdown (Audit Trail)
          $promoBreakdownId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
            mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
          
          $pbStmt = $pdo->prepare("
            INSERT INTO reservation_pricing_breakdown 
            (breakdown_id, reservation_id, component_type, promo_id, component_name, 
             discount_amount, final_amount, sort_order, is_visible) 
            VALUES (?,?,?,?,?,?,?,?,?)
          ");
          $pbStmt->execute([
            $promoBreakdownId,
            $reservationId,
            'promotion',
            $data['promoId'],
            ($promoData['promo_title'] ?? 'Promo Discount') . ($data['promoCode'] ? ' (' . $data['promoCode'] . ')' : ''),
            $data['promoDiscount'],
            -$data['promoDiscount'],
            99,
            1
          ]);
          
          // 4. UPDATE reservation totals to reflect discount
          $updateStmt = $pdo->prepare("
            UPDATE reservations 
            SET discount_amount = ?,
                balance_amount = GREATEST(0, total_amount - paid_amount - ?)
            WHERE reservation_id = ?
          ");
          $updateStmt->execute([
            $data['promoDiscount'],
            $data['promoDiscount'],
            $reservationId
          ]);
        }
        
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Walk-in check-in successful', 'reservation_id' => $reservationId, 'reservation_code' => $reservationCode]);
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
