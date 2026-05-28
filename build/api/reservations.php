<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
require_once '../services/EmailService.php';
applyAuthMiddleware();

use App\Services\EmailService;

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        $stmt = $pdo->query("
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
          ORDER BY r.created_at DESC
        ");
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $reservations]);
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
        $totalAmount = floatval($data['totalAmount']);
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
        
        // Create reservation
        $stmt = $pdo->prepare("
          INSERT INTO reservations (
            reservation_id, reservation_code, property_id, customer_user_id,
            booking_type, reservation_status, payment_status,
            check_in_date, check_in_time, check_out_date, check_out_time,
            duration_hours, actual_check_in_at,
            num_adults, num_children, num_seniors, num_infants,
            walkin_first_name, walkin_last_name, walkin_email, walkin_phone,
            total_amount, paid_amount, balance_amount, special_requests,
            source_channel, created_at, updated_at
          ) VALUES (?, ?, ?, NULL, ?, 'checked_in', ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'walkin', NOW(), NOW())
        ");
        $stmt->execute([
          $reservationId,
          $reservationCode,
          $data['propertyId'],
          $data['bookingType'],
          $paymentStatus,
          $data['checkInDate'],
          $data['checkInTime'],
          $checkOutDate,
          $checkOutTime,
          $durationHours,
          $data['adults'],
          $data['children'],
          $data['seniors'],
          $data['infants'],
          $data['firstName'],
          $data['lastName'],
          $data['email'] ?? null,
          $data['phone'],
          $totalAmount,
          $paymentAmount,
          $balanceAmount,
          $data['notes'] ?? null
        ]);
        
        $resRoomId = null; // Will be set if room is selected
        
        // Only create reservation_room if a room was selected
        if (!empty($data['roomId'])) {
          // Get room details
          $roomStmt = $pdo->prepare("SELECT * FROM rooms WHERE room_id = ?");
          $roomStmt->execute([$data['roomId']]);
          $room = $roomStmt->fetch(PDO::FETCH_ASSOC);
          
          if ($room) {
            // Create reservation_room
            $resRoomId = 'rr-' . uniqid();
            $roomTotal = $totalAmount;
            
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
        
        // Create check-in log
        $logId = 'log-' . uniqid();
        $logStmt = $pdo->prepare("
          INSERT INTO check_in_logs (
            log_id, reservation_id, res_room_id, event_type, event_at,
            processed_by, notes, created_at
          ) VALUES (?, ?, ?, 'walk_in_check_in', NOW(), ?, ?, NOW())
        ");
        $logStmt->execute([
          $logId,
          $reservationId,
          $resRoomId, // Can be null if no room selected
          $_SESSION['user_id'] ?? null,
          $data['notes'] ?? null
        ]);
        
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
