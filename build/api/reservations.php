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
            total_amount = ?, special_requests = ?, updated_at = NOW()
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
