<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// Define allowed actions for security
$allowedGetActions = ['dashboard_summary', 'recent_invoices', 'recent_transactions', 'company_info', 'payment_methods', 'unpaid_summary', 'unpaid_reservations', 'reservation_bill', 'payment_history'];
$allowedPostActions = ['update_invoice_status', 'confirm_payment', 'reject_payment', 'update_company_info', 'record_payment'];

try {
  switch ($method) {
    case 'GET':
      requirePermission('payments.view');
      if (!in_array($action, $allowedGetActions)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
      }
      
      if ($action === 'dashboard_summary') {
        // Fix: Use separate subqueries instead of CROSS JOIN
        $stmt = $pdo->query("
          SELECT 
            (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payment_status = 'confirmed') AS total_revenue,
            (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payment_status = 'pending') AS pending_payments,
            (SELECT COUNT(*) FROM payments WHERE payment_status = 'pending') AS pending_count,
            (SELECT COALESCE(SUM(total_amount), 0) FROM invoices WHERE status = 'paid') AS total_invoiced,
            (SELECT COALESCE(SUM(balance), 0) FROM invoices WHERE status IN ('partial', 'sent')) AS outstanding_balance
        ");
        $summary = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $summary], JSON_HEX_TAG | JSON_HEX_AMP);
      } 
      else if ($action === 'recent_invoices') {
        // Cap limit to prevent data dumping
        $limit = min(intval($_GET['limit'] ?? 5), 100);
        $offset = intval($_GET['offset'] ?? 0);
        $dateFrom = $_GET['date_from'] ?? null;
        $dateTo = $_GET['date_to'] ?? null;
        
        $sql = "
          SELECT i.*, r.reservation_code, up.first_name, up.last_name
          FROM invoices i
          LEFT JOIN reservations r ON i.reservation_id = r.reservation_id
          LEFT JOIN user_profiles up ON r.customer_user_id = up.user_id
        ";
        
        $params = [];
        $conditions = [];
        
        if ($dateFrom) {
          $conditions[] = "i.invoice_date >= ?";
          $params[] = $dateFrom;
        }
        
        if ($dateTo) {
          $conditions[] = "i.invoice_date <= ?";
          $params[] = $dateTo;
        }
        
        if (!empty($conditions)) {
          $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        // Get total count for pagination
        $countSql = str_replace("SELECT i.*", "SELECT COUNT(*)", $sql);
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();
        
        $sql .= " ORDER BY i.invoice_date DESC, i.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
          'success' => true, 
          'data' => $invoices,
          'pagination' => [
            'total' => intval($total),
            'limit' => $limit,
            'offset' => $offset,
            'has_more' => ($offset + $limit) < $total
          ]
        ], JSON_HEX_TAG | JSON_HEX_AMP);
      }
      else if ($action === 'recent_transactions') {
        // Cap limit to prevent data dumping
        $limit = min(intval($_GET['limit'] ?? 10), 100);
        $offset = intval($_GET['offset'] ?? 0);
        $dateFrom = $_GET['date_from'] ?? null;
        $dateTo = $_GET['date_to'] ?? null;
        
        $sql = "
          SELECT p.*, pm.method_label, pm.method_type, r.reservation_code, up.first_name, up.last_name
          FROM payments p
          LEFT JOIN payment_methods pm ON p.method_id = pm.method_id
          LEFT JOIN reservations r ON p.reservation_id = r.reservation_id
          LEFT JOIN user_profiles up ON r.customer_user_id = up.user_id
        ";
        
        $params = [];
        $conditions = [];
        
        if ($dateFrom) {
          $conditions[] = "p.payment_date >= ?";
          $params[] = $dateFrom;
        }
        
        if ($dateTo) {
          $conditions[] = "p.payment_date <= ?";
          $params[] = $dateTo;
        }
        
        if (!empty($conditions)) {
          $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        // Get total count for pagination
        $countSql = str_replace("SELECT p.*", "SELECT COUNT(*)", $sql);
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();
        
        $sql .= " ORDER BY p.payment_date DESC, p.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
          'success' => true, 
          'data' => $transactions,
          'pagination' => [
            'total' => intval($total),
            'limit' => $limit,
            'offset' => $offset,
            'has_more' => ($offset + $limit) < $total
          ]
        ], JSON_HEX_TAG | JSON_HEX_AMP);
      }
      else if ($action === 'company_info') {
        $stmt = $pdo->query("
          SELECT * FROM company_information WHERE is_active = 1 LIMIT 1
        ");
        $company = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$company) {
          http_response_code(404);
          echo json_encode(['success' => false, 'message' => 'No company information found']);
        } else {
          echo json_encode(['success' => true, 'data' => $company], JSON_HEX_TAG | JSON_HEX_AMP);
        }
      }
      else if ($action === 'payment_methods') {
        $stmt = $pdo->query("
          SELECT * FROM payment_methods WHERE is_active = 1 ORDER BY sort_order, method_label
        ");
        $methods = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $methods], JSON_HEX_TAG | JSON_HEX_AMP);
      }
      else if ($action === 'unpaid_summary') {
        $stmt = $pdo->query("
          SELECT
            (SELECT COUNT(*) FROM reservations
              WHERE balance_amount > 0 AND deleted_at IS NULL
              AND reservation_status NOT IN ('cancelled','checked_out')) AS unpaid_count,
            (SELECT COALESCE(SUM(balance_amount),0) FROM reservations
              WHERE balance_amount > 0 AND deleted_at IS NULL
              AND reservation_status NOT IN ('cancelled','checked_out')) AS total_outstanding,
            (SELECT COALESCE(SUM(fo.total_amount),0) FROM food_orders fo
              WHERE fo.payment_status = 'unpaid'
              AND fo.order_status NOT IN ('cancelled')) AS unpaid_food_total,
            (SELECT COUNT(*) FROM food_orders fo
              WHERE fo.payment_status = 'unpaid'
              AND fo.order_status NOT IN ('cancelled')) AS unpaid_food_count,
            (SELECT COALESCE(SUM(ec.amount),0) FROM extra_charges ec
              WHERE ec.payment_status = 'unpaid'
                AND ec.reservation_id IN (
                  SELECT reservation_id FROM reservations
                  WHERE balance_amount > 0 AND deleted_at IS NULL
                )) AS extra_charges_total
        ");
        $summary = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $summary], JSON_HEX_TAG | JSON_HEX_AMP);
      }
      else if ($action === 'unpaid_reservations') {
        $limit  = min(intval($_GET['limit'] ?? 20), 100);
        $offset = intval($_GET['offset'] ?? 0);
        $search = trim($_GET['search'] ?? '');
        $status = $_GET['status'] ?? '';

        $where  = ["r.balance_amount > 0", "r.deleted_at IS NULL", "r.reservation_status NOT IN ('cancelled','checked_out')"];
        $params = [];

        if ($search !== '') {
          $where[]  = "(r.reservation_code LIKE ? OR CONCAT(up.first_name,' ',up.last_name) LIKE ? OR up.first_name LIKE ? OR up.last_name LIKE ?)";
          $like     = "%{$search}%";
          $params   = array_merge($params, [$like, $like, $like, $like]);
        }
        if ($status !== '') {
          $where[]  = "r.reservation_status = ?";
          $params[] = $status;
        }

        $whereClause = 'WHERE ' . implode(' AND ', $where);

        $countStmt = $pdo->prepare("
          SELECT COUNT(*) FROM reservations r
          LEFT JOIN user_profiles up ON r.customer_user_id = up.user_id
          $whereClause
        ");
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();

        $sql = "
          SELECT r.reservation_id, r.reservation_code, r.reservation_status, r.payment_status,
                 r.total_amount, r.paid_amount, r.balance_amount,
                 r.check_in_date, r.check_out_date, r.check_in_time, r.check_out_time,
                 CONCAT(COALESCE(up.first_name,''),' ',COALESCE(up.last_name,'')) AS guest_name,
                 up.first_name, up.last_name,
                 p.property_name, ro.room_name, ro.room_number,
                 (SELECT COALESCE(SUM(fo.total_amount), 0)
                    FROM food_orders fo
                    WHERE fo.reservation_id = r.reservation_id
                      AND fo.payment_status = 'unpaid'
                      AND fo.order_status NOT IN ('cancelled')
                 ) AS unpaid_food,
                 (SELECT COALESCE(SUM(ec.amount), 0)
                    FROM extra_charges ec
                    WHERE ec.reservation_id = r.reservation_id
                 ) AS extra_charges
          FROM reservations r
          LEFT JOIN user_profiles up ON r.customer_user_id = up.user_id
          LEFT JOIN properties p ON r.property_id = p.property_id
          LEFT JOIN reservation_rooms rr ON rr.reservation_id = r.reservation_id
          LEFT JOIN rooms ro ON ro.room_id = rr.room_id
          $whereClause
          GROUP BY r.reservation_id, r.reservation_code, r.reservation_status, r.payment_status,
                   r.total_amount, r.paid_amount, r.balance_amount,
                   r.check_in_date, r.check_out_date, r.check_in_time, r.check_out_time,
                   up.first_name, up.last_name, p.property_name, ro.room_name, ro.room_number
          ORDER BY r.check_in_date ASC, r.balance_amount DESC
          LIMIT {$limit} OFFSET {$offset}
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $rows, 'total' => intval($total), 'limit' => $limit, 'offset' => $offset], JSON_HEX_TAG | JSON_HEX_AMP);
      }
      else if ($action === 'reservation_bill') {
        $reservationId = $_GET['reservation_id'] ?? '';
        if (!$reservationId) {
          http_response_code(400);
          echo json_encode(['success' => false, 'message' => 'Missing reservation_id']);
          break;
        }

        // Reservation base info
        $stmt = $pdo->prepare("
          SELECT r.*, CONCAT(COALESCE(up.first_name,''),' ',COALESCE(up.last_name,'')) AS guest_name,
                 up.first_name, up.last_name, ua.phone_number,
                 p.property_name,
                 GROUP_CONCAT(DISTINCT ro.room_name ORDER BY ro.room_name SEPARATOR ', ') AS room_name,
                 GROUP_CONCAT(DISTINCT ro.room_number ORDER BY ro.room_number SEPARATOR ', ') AS room_number
          FROM reservations r
          LEFT JOIN user_profiles up ON r.customer_user_id = up.user_id
          LEFT JOIN user_accounts ua ON r.customer_user_id = ua.user_id
          LEFT JOIN properties p ON r.property_id = p.property_id
          LEFT JOIN reservation_rooms rr ON rr.reservation_id = r.reservation_id
          LEFT JOIN rooms ro ON ro.room_id = rr.room_id
          WHERE r.reservation_id = ?
          GROUP BY r.reservation_id, up.first_name, up.last_name, ua.phone_number, p.property_name
        ");
        $stmt->execute([$reservationId]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reservation) {
          http_response_code(404);
          echo json_encode(['success' => false, 'message' => 'Reservation not found']);
          break;
        }

        // Extra charges (all statuses for full breakdown)
        $ecStmt = $pdo->prepare("
          SELECT ec.*, CONCAT(ua_staff.first_name,' ',ua_staff.last_name) AS charged_by_name
          FROM extra_charges ec
          LEFT JOIN user_profiles ua_staff ON ec.charged_by = ua_staff.user_id
          WHERE ec.reservation_id = ?
          ORDER BY ec.charged_at ASC
        ");
        $ecStmt->execute([$reservationId]);
        $extraCharges = $ecStmt->fetchAll(PDO::FETCH_ASSOC);

        // Food orders (all non-cancelled for full breakdown)
        $foStmt = $pdo->prepare("
          SELECT fo.order_id, fo.order_number, fo.order_status, fo.payment_status,
                 fo.total_amount, fo.created_at,
                 GROUP_CONCAT(CONCAT(foi.quantity,'x ',foi.item_name) ORDER BY foi.item_name SEPARATOR ', ') AS items_summary
          FROM food_orders fo
          LEFT JOIN food_order_items foi ON fo.order_id = foi.order_id
          WHERE fo.reservation_id = ? AND fo.order_status NOT IN ('cancelled')
          GROUP BY fo.order_id
          ORDER BY fo.created_at ASC
        ");
        $foStmt->execute([$reservationId]);
        $foodOrders = $foStmt->fetchAll(PDO::FETCH_ASSOC);

        // Payments already made
        $pyStmt = $pdo->prepare("
          SELECT py.*, pm.method_label
          FROM payments py
          LEFT JOIN payment_methods pm ON py.method_id = pm.method_id
          WHERE py.reservation_id = ?
          ORDER BY py.payment_date ASC, py.created_at ASC
        ");
        $pyStmt->execute([$reservationId]);
        $payments = $pyStmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
          'success'       => true,
          'data'          => [
            'reservation'   => $reservation,
            'extra_charges' => $extraCharges,
            'food_orders'   => $foodOrders,
            'payments'      => $payments,
          ]
        ], JSON_HEX_TAG | JSON_HEX_AMP);
      }
      else if ($action === 'payment_history') {
        $limit  = min(intval($_GET['limit'] ?? 20), 100);
        $offset = intval($_GET['offset'] ?? 0);
        $search = trim($_GET['search'] ?? '');
        $method_filter = $_GET['method'] ?? '';
        $date_from  = $_GET['date_from'] ?? '';
        $date_to    = $_GET['date_to'] ?? '';

        $where = ['py.payment_status = \'confirmed\''];
        $params = [];

        if ($search !== '') {
          $where[] = '(r.reservation_code LIKE ? OR CONCAT(up.first_name,\' \',up.last_name) LIKE ?)';
          $params[] = '%' . $search . '%';
          $params[] = '%' . $search . '%';
        }
        if ($method_filter !== '') {
          $where[] = 'pm.method_code = ?';
          $params[] = $method_filter;
        }
        if ($date_from !== '') {
          $where[] = 'py.payment_date >= ?';
          $params[] = $date_from;
        }
        if ($date_to !== '') {
          $where[] = 'py.payment_date <= ?';
          $params[] = $date_to;
        }

        $whereClause = 'WHERE ' . implode(' AND ', $where);

        $countStmt = $pdo->prepare("
          SELECT COUNT(*) FROM payments py
          LEFT JOIN reservations r ON py.reservation_id = r.reservation_id
          LEFT JOIN user_profiles up ON r.customer_user_id = up.user_id
          LEFT JOIN payment_methods pm ON py.method_id = pm.method_id
          $whereClause
        ");
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();

        $sql = "
          SELECT py.payment_id, py.payment_date, py.payment_time, py.amount,
                 py.transaction_type, py.payment_status, py.reference_number, py.notes,
                 pm.method_label, pm.method_type, pm.method_code,
                 r.reservation_id, r.reservation_code, r.reservation_status,
                 CONCAT(COALESCE(up.first_name,''),' ',COALESCE(up.last_name,'')) AS guest_name,
                 p.property_name,
                 CONCAT(COALESCE(staff.first_name,''),' ',COALESCE(staff.last_name,'')) AS recorded_by_name
          FROM payments py
          LEFT JOIN reservations r   ON py.reservation_id = r.reservation_id
          LEFT JOIN user_profiles up ON r.customer_user_id = up.user_id
          LEFT JOIN payment_methods pm ON py.method_id = pm.method_id
          LEFT JOIN properties p ON r.property_id = p.property_id
          LEFT JOIN user_profiles staff ON py.confirmed_by = staff.user_id
          $whereClause
          ORDER BY py.payment_date DESC, py.payment_time DESC, py.created_at DESC
          LIMIT {$limit} OFFSET {$offset}
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $rows, 'total' => intval($total), 'limit' => $limit, 'offset' => $offset], JSON_HEX_TAG | JSON_HEX_AMP);
      }
      break;

    case 'POST':
      requirePermission('payments.edit');
      if (!in_array($action, $allowedPostActions)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
      }

      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'record_payment') {
        $reservationId   = $data['reservation_id'] ?? '';
        $amount          = floatval($data['amount'] ?? 0);
        $methodCode      = $data['payment_method_code'] ?? 'cash';
        $reference       = $data['reference_number'] ?? null;
        $notes           = $data['notes'] ?? null;
        $foodOrderIds    = $data['food_order_ids'] ?? [];

        if (!$reservationId || $amount <= 0) {
          http_response_code(400);
          echo json_encode(['success' => false, 'message' => 'Missing reservation_id or invalid amount']);
          break;
        }

        $pdo->beginTransaction();
        try {
          // Resolve method_id from code
          $mStmt = $pdo->prepare("SELECT method_id FROM payment_methods WHERE method_code = ? AND is_active = 1 LIMIT 1");
          $mStmt->execute([$methodCode]);
          $method = $mStmt->fetch(PDO::FETCH_ASSOC);
          $methodId = $method ? $method['method_id'] : null;

          // Insert payment record
          $paymentId = 'pay-' . bin2hex(random_bytes(8));
          $pStmt = $pdo->prepare("
            INSERT INTO payments (payment_id, reservation_id, method_id, transaction_type, amount,
              reference_number, payment_status, payment_date, payment_time, notes, created_at, updated_at)
            VALUES (?, ?, ?, 'payment', ?, ?, 'confirmed', CURDATE(), CURTIME(), ?, NOW(), NOW())
          ");
          $pStmt->execute([$paymentId, $reservationId, $methodId, $amount, $reference, $notes]);

          // Update reservation paid_amount / balance_amount / payment_status
          $rStmt = $pdo->prepare("
            UPDATE reservations
            SET paid_amount    = paid_amount + ?,
                balance_amount = GREATEST(balance_amount - ?, 0),
                payment_status = CASE
                  WHEN GREATEST(balance_amount - ?, 0) <= 0 THEN 'paid'
                  WHEN paid_amount + ? > 0                  THEN 'partial'
                  ELSE 'unpaid'
                END,
                confirmed_by = ?,
                updated_at   = NOW()
            WHERE reservation_id = ?
          ");
          $rStmt->execute([$amount, $amount, $amount, $amount, $_SESSION['user_id'] ?? null, $reservationId]);

          // Mark food orders as paid if specified
          if (!empty($foodOrderIds)) {
            $placeholders = implode(',', array_fill(0, count($foodOrderIds), '?'));
            $foStmt = $pdo->prepare("UPDATE food_orders SET payment_status = 'paid' WHERE order_id IN ($placeholders)");
            $foStmt->execute($foodOrderIds);
          }

          // Check updated balance and settle extra charges if fully paid
          $balStmt = $pdo->prepare("SELECT balance_amount FROM reservations WHERE reservation_id = ?");
          $balStmt->execute([$reservationId]);
          $updatedBalance = floatval($balStmt->fetchColumn());

          if ($updatedBalance <= 0) {
            // Fully settled — mark all unpaid extra charges as paid
            $ecSettleStmt = $pdo->prepare("
              UPDATE extra_charges
              SET payment_status = 'paid', paid_at = NOW()
              WHERE reservation_id = ? AND payment_status = 'unpaid'
            ");
            $ecSettleStmt->execute([$reservationId]);
          }

          $pdo->commit();
          echo json_encode(['success' => true, 'message' => 'Payment recorded successfully', 'payment_id' => $paymentId], JSON_HEX_TAG | JSON_HEX_AMP);
        } catch (Exception $e) {
          $pdo->rollBack();
          throw $e;
        }
      }
      else if ($action === 'update_invoice_status') {
        if (!isset($data['invoice_id']) || !isset($data['status'])) {
          http_response_code(400);
          echo json_encode(['success' => false, 'message' => 'Missing required fields']);
          break;
        }

        $stmt = $pdo->prepare("
          UPDATE invoices 
          SET status = ?, updated_at = NOW() 
          WHERE invoice_id = ?
        ");
        $stmt->execute([$data['status'], $data['invoice_id']]);
        echo json_encode(['success' => true, 'message' => 'Invoice status updated'], JSON_HEX_TAG | JSON_HEX_AMP);
      }
      else if ($action === 'confirm_payment') {
        if (!isset($data['payment_id'])) {
          http_response_code(400);
          echo json_encode(['success' => false, 'message' => 'Missing payment_id']);
          break;
        }

        $pdo->beginTransaction();
        try {
          // Update payment status
          $stmt = $pdo->prepare("
            UPDATE payments 
            SET payment_status = 'confirmed', 
                confirmed_by = ?,
                confirmed_at = NOW(),
                updated_at = NOW()
            WHERE payment_id = ?
          ");
          $stmt->execute([$_SESSION['user_id'] ?? null, $data['payment_id']]);

          // Get payment amount and reservation_id
          $stmt = $pdo->prepare("SELECT amount, reservation_id FROM payments WHERE payment_id = ?");
          $stmt->execute([$data['payment_id']]);
          $payment = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($payment) {
            // Update reservation paid_amount
            $amount = floatval($payment['amount']);
            $updateStmt = $pdo->prepare("
              UPDATE reservations 
              SET paid_amount = paid_amount + ?, 
                  balance_amount = balance_amount - ?,
                  payment_status = CASE 
                    WHEN balance_amount - ? <= 0 THEN 'paid'
                    WHEN paid_amount + ? > 0 THEN 'partial'
                    ELSE 'unpaid'
                  END,
                  updated_at = NOW()
              WHERE reservation_id = ?
            ");
            $updateStmt->execute([$amount, $amount, $amount, $amount, $payment['reservation_id']]);
          }

          $pdo->commit();
          echo json_encode(['success' => true, 'message' => 'Payment confirmed'], JSON_HEX_TAG | JSON_HEX_AMP);
        } catch (Exception $e) {
          $pdo->rollBack();
          throw $e;
        }
      }
      else if ($action === 'reject_payment') {
        if (!isset($data['payment_id']) || !isset($data['reason'])) {
          http_response_code(400);
          echo json_encode(['success' => false, 'message' => 'Missing required fields']);
          break;
        }

        $stmt = $pdo->prepare("
          UPDATE payments 
          SET payment_status = 'rejected', 
              notes = CONCAT(COALESCE(notes, ''), ' Rejected: ', ?),
              updated_at = NOW()
          WHERE payment_id = ?
        ");
        $stmt->execute([$data['reason'], $data['payment_id']]);
        echo json_encode(['success' => true, 'message' => 'Payment rejected'], JSON_HEX_TAG | JSON_HEX_AMP);
      }
      else if ($action === 'update_company_info') {
        $stmt = $pdo->prepare("
          UPDATE company_information 
          SET company_name = ?,
              legal_name = ?,
              business_reg_number = ?,
              tax_id = ?,
              address_line1 = ?,
              address_line2 = ?,
              city = ?,
              province = ?,
              postal_code = ?,
              phone_number = ?,
              email = ?,
              website = ?,
              description = ?,
              updated_by = ?,
              updated_at = NOW()
          WHERE company_id = ?
        ");
        $stmt->execute([
          $data['company_name'] ?? null,
          $data['legal_name'] ?? null,
          $data['business_reg_number'] ?? null,
          $data['tax_id'] ?? null,
          $data['address_line1'] ?? null,
          $data['address_line2'] ?? null,
          $data['city'] ?? null,
          $data['province'] ?? null,
          $data['postal_code'] ?? null,
          $data['phone_number'] ?? null,
          $data['email'] ?? null,
          $data['website'] ?? null,
          $data['description'] ?? null,
          $_SESSION['user_id'] ?? null,
          $data['company_id'] ?? null
        ]);
        echo json_encode(['success' => true, 'message' => 'Company information updated'], JSON_HEX_TAG | JSON_HEX_AMP);
      }
      break;

    default:
      http_response_code(405);
      echo json_encode(['success' => false, 'message' => 'Method not allowed']);
  }
} catch (Exception $e) {
  error_log('Billing API Error: ' . $e->getMessage());
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}