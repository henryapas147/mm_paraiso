<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// Define allowed actions for security
$allowedGetActions = ['dashboard_summary', 'recent_invoices', 'recent_transactions', 'company_info', 'payment_methods'];
$allowedPostActions = ['update_invoice_status', 'confirm_payment', 'reject_payment', 'update_company_info'];

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
      break;

    case 'POST':
      requirePermission('payments.edit');
      if (!in_array($action, $allowedPostActions)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
      }

      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'update_invoice_status') {
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
  echo json_encode(['success' => false, 'message' => 'An internal error occurred.']);
}
