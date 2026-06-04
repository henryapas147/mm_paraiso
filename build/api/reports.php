<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      switch ($action) {
        // Dashboard Summary Endpoints
        case 'revenue_summary':
          // Today's revenue
          $stmt = $pdo->query("SELECT COALESCE(SUM(p.amount), 0) as today FROM payments p WHERE DATE(p.payment_date) = CURDATE() AND p.payment_status IN ('paid', 'partial')");
          $today = $stmt->fetchColumn();

          // Month revenue
          $stmt = $pdo->query("SELECT COALESCE(SUM(p.amount), 0) as month FROM payments p WHERE MONTH(p.payment_date) = MONTH(CURDATE()) AND YEAR(p.payment_date) = YEAR(CURDATE()) AND p.payment_status IN ('paid', 'partial')");
          $month = $stmt->fetchColumn();

          echo json_encode(['success' => true, 'data' => ['today' => $today, 'month' => $month]]);
          break;

        case 'occupancy_summary':
          // Today's occupancy (placeholder - would need actual room count)
          $stmt = $pdo->query("SELECT COUNT(DISTINCT rr.room_id) as occupied FROM reservation_rooms rr JOIN reservations r ON rr.reservation_id = r.reservation_id WHERE DATE(r.check_in_date) <= CURDATE() AND DATE(r.check_out_date) >= CURDATE() AND r.reservation_status IN ('checked_in', 'confirmed')");
          $occupied = $stmt->fetchColumn();
          
          // Get total rooms
          $stmt = $pdo->query("SELECT COUNT(*) FROM rooms WHERE is_active = 1");
          $total = $stmt->fetchColumn();
          
          $today_pct = $total > 0 ? round(($occupied / $total) * 100) : 0;
          
          // Month average (simplified)
          echo json_encode(['success' => true, 'data' => ['today' => $today_pct, 'month' => $today_pct]]);
          break;

        case 'customer_summary':
          // Total customers
          $stmt = $pdo->query("SELECT COUNT(*) FROM user_accounts WHERE role_id = 7 AND deleted_at IS NULL");
          $total = $stmt->fetchColumn();
          
          // New this month
          $stmt = $pdo->query("SELECT COUNT(*) FROM user_accounts WHERE role_id = 7 AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) AND deleted_at IS NULL");
          $new = $stmt->fetchColumn();
          
          echo json_encode(['success' => true, 'data' => ['total' => $total, 'new_this_month' => $new]]);
          break;

        case 'inventory_summary':
          // Low stock items (below reorder point)
          $stmt = $pdo->query("SELECT COUNT(*) FROM products p WHERE p.current_stock <= p.reorder_point AND p.is_active = 1 AND p.track_inventory = 1");
          $low_stock = $stmt->fetchColumn();
          
          // Inventory value
          $stmt = $pdo->query("SELECT COALESCE(SUM(p.current_stock * p.unit_cost), 0) FROM products p WHERE p.is_active = 1");
          $value = $stmt->fetchColumn();
          
          echo json_encode(['success' => true, 'data' => ['low_stock' => $low_stock, 'total_value' => $value]]);
          break;

        // Detailed Reports
        case 'revenue_daily':
          $days = $_GET['days'] ?? 30;
          $stmt = $pdo->prepare("SELECT DATE(p.payment_date) as date, COALESCE(SUM(p.amount), 0) as revenue, COUNT(*) as transactions FROM payments p WHERE p.payment_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY) AND p.payment_status IN ('paid', 'partial') GROUP BY DATE(p.payment_date) ORDER BY date DESC");
          $stmt->execute([$days]);
          echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
          break;

        case 'revenue_monthly':
          $stmt = $pdo->query("SELECT DATE_FORMAT(p.payment_date, '%Y-%m') as month, COALESCE(SUM(p.amount), 0) as revenue, COUNT(*) as transactions FROM payments p WHERE p.payment_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) AND p.payment_status IN ('paid', 'partial') GROUP BY DATE_FORMAT(p.payment_date, '%Y-%m') ORDER BY month DESC");
          echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
          break;

        case 'revenue_by_source':
          $stmt = $pdo->query("SELECT r.source_channel, COALESCE(SUM(p.amount), 0) as revenue, COUNT(*) as bookings FROM payments p JOIN reservations r ON p.reservation_id = r.reservation_id WHERE p.payment_status IN ('paid', 'partial') GROUP BY r.source_channel");
          echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
          break;

        case 'occupancy_daily':
          $stmt = $pdo->query("SELECT DATE(check_in_date) as date, COUNT(DISTINCT reservation_id) as check_ins, COUNT(DISTINCT CASE WHEN reservation_status = 'checked_out' THEN reservation_id END) as check_outs FROM reservations WHERE check_in_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY DATE(check_in_date) ORDER BY date DESC");
          echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
          break;

        case 'occupancy_by_room_type':
          $stmt = $pdo->query("SELECT rc.category_name, COUNT(r.room_id) as total_rooms, COUNT(CASE WHEN rr.room_id IS NOT NULL THEN 1 END) as occupied FROM room_categories rc LEFT JOIN rooms r ON rc.category_id = r.category_id LEFT JOIN reservation_rooms rr ON r.room_id = rr.room_id LEFT JOIN reservations res ON rr.reservation_id = res.reservation_id AND DATE(res.check_in_date) <= CURDATE() AND DATE(res.check_out_date) >= CURDATE() AND res.reservation_status IN ('checked_in', 'confirmed') GROUP BY rc.category_id, rc.category_name");
          echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
          break;

        case 'inventory_valuation':
          $stmt = $pdo->query("SELECT ic.category_name, COUNT(p.product_id) as product_count, COALESCE(SUM(p.current_stock * p.unit_cost), 0) as total_value FROM inventory_categories ic LEFT JOIN products p ON ic.category_id = p.category_id WHERE p.is_active = 1 OR p.product_id IS NULL GROUP BY ic.category_id, ic.category_name");
          echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
          break;

        case 'stock_movements':
          $days = $_GET['days'] ?? 30;
          $stmt = $pdo->prepare("SELECT DATE(it.created_at) as date, it.transaction_type, COUNT(*) as count, COALESCE(SUM(it.quantity), 0) as total_quantity FROM inventory_transactions it WHERE it.created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY) GROUP BY DATE(it.created_at), it.transaction_type ORDER BY date DESC");
          $stmt->execute([$days]);
          echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
          break;

        case 'customer_demographics':
          $stmt = $pdo->query("SELECT up.city, COUNT(*) as count FROM user_accounts ua JOIN user_profiles up ON ua.user_id = up.user_id WHERE ua.role_id = 7 AND ua.deleted_at IS NULL GROUP BY up.city ORDER BY count DESC LIMIT 10");
          echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
          break;

        case 'customer_booking_frequency':
          $stmt = $pdo->query("SELECT ua.user_id, up.first_name, up.last_name, COUNT(r.reservation_id) as booking_count, COALESCE(SUM(r.total_amount), 0) as total_spent FROM user_accounts ua JOIN user_profiles up ON ua.user_id = up.user_id LEFT JOIN reservations r ON ua.user_id = r.customer_user_id WHERE ua.role_id = 7 AND ua.deleted_at IS NULL GROUP BY ua.user_id, up.first_name, up.last_name ORDER BY booking_count DESC LIMIT 20");
          echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
          break;

        case 'food_sales_daily':
          $days = $_GET['days'] ?? 30;
          $stmt = $pdo->prepare("SELECT DATE(fo.created_at) as date, COALESCE(SUM(fo.subtotal), 0) as sales, COUNT(*) as orders FROM food_orders fo WHERE fo.order_status != 'cancelled' AND fo.created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY) GROUP BY DATE(fo.created_at) ORDER BY date DESC");
          $stmt->execute([$days]);
          echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
          break;

        case 'food_sales_by_category':
          $stmt = $pdo->query("SELECT fc.category_name, COALESCE(SUM(foi.quantity * foi.unit_price), 0) as revenue, COUNT(DISTINCT foi.order_id) as order_count FROM food_categories fc LEFT JOIN menu_items mi ON fc.category_id = mi.category_id LEFT JOIN food_order_items foi ON mi.item_id = foi.item_id LEFT JOIN food_orders fo ON foi.order_id = fo.order_id WHERE fo.order_status != 'cancelled' OR fo.order_id IS NULL GROUP BY fc.category_id, fc.category_name");
          echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
          break;

        case 'top_menu_items':
          $stmt = $pdo->query("SELECT mi.item_name, fc.category_name, mii.image_url, COALESCE(SUM(foi.quantity), 0) as total_sold, COALESCE(SUM(foi.quantity * foi.unit_price), 0) as revenue FROM menu_items mi JOIN food_categories fc ON mi.category_id = fc.category_id LEFT JOIN menu_item_images mii ON mi.item_id = mii.item_id AND mii.is_cover = 1 LEFT JOIN food_order_items foi ON mi.item_id = foi.item_id LEFT JOIN food_orders fo ON foi.order_id = fo.order_id WHERE fo.order_status != 'cancelled' OR fo.order_id IS NULL GROUP BY mi.item_id, mi.item_name, fc.category_name, mii.image_url ORDER BY total_sold DESC LIMIT 10");
          echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
          break;

        case 'recent_reservations':
          $stmt = $pdo->query("SELECT r.reservation_code, r.check_in_date, r.check_out_date, r.reservation_status, r.total_amount, COALESCE(up.first_name, r.walkin_first_name) as first_name, COALESCE(up.last_name, r.walkin_last_name) as last_name, up.profile_photo_url FROM reservations r LEFT JOIN user_accounts ua ON r.customer_user_id = ua.user_id LEFT JOIN user_profiles up ON ua.user_id = up.user_id ORDER BY r.created_at DESC LIMIT 12");
          echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
          break;

        case 'financial_summary':
          $period = $_GET['period'] ?? 'year';
          $year = $_GET['year'] ?? date('Y');
          
          // Build date condition based on period
          $dateCondition = '';
          if ($period === 'month') {
            $dateCondition = "AND MONTH(p.payment_date) = MONTH(CURDATE()) AND YEAR(p.payment_date) = YEAR(CURDATE())";
            $expenseDateCondition = "AND MONTH(st.transaction_date) = MONTH(CURDATE()) AND YEAR(st.transaction_date) = YEAR(CURDATE())";
          } elseif ($period === 'last_month') {
            $dateCondition = "AND MONTH(p.payment_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(p.payment_date) = YEAR(CURDATE() - INTERVAL 1 MONTH)";
            $expenseDateCondition = "AND MONTH(st.transaction_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(st.transaction_date) = YEAR(CURDATE() - INTERVAL 1 MONTH)";
          } elseif ($period === 'quarter') {
            $dateCondition = "AND QUARTER(p.payment_date) = QUARTER(CURDATE()) AND YEAR(p.payment_date) = YEAR(CURDATE())";
            $expenseDateCondition = "AND QUARTER(st.transaction_date) = QUARTER(CURDATE()) AND YEAR(st.transaction_date) = YEAR(CURDATE())";
          } else {
            // year (default)
            $dateCondition = "AND YEAR(p.payment_date) = ?";
            $expenseDateCondition = "AND YEAR(st.transaction_date) = ?";
          }
          
          // Revenue from payments
          if ($period === 'year') {
            $stmt = $pdo->prepare("SELECT COALESCE(SUM(p.amount), 0) as revenue FROM payments p WHERE p.payment_status IN ('paid', 'partial') $dateCondition");
            $stmt->execute([$year]);
          } else {
            $stmt = $pdo->query("SELECT COALESCE(SUM(p.amount), 0) as revenue FROM payments p WHERE p.payment_status IN ('paid', 'partial') $dateCondition");
          }
          $revenue = $stmt->fetchColumn();
          
          // Expenses from stock_transactions
          if ($period === 'year') {
            $stmt = $pdo->prepare("SELECT COALESCE(SUM(st.quantity * st.unit_cost), 0) as expenses FROM stock_transactions st WHERE st.transaction_type IN ('stock_out_sale', 'stock_out_use', 'stock_out_damage') $expenseDateCondition");
            $stmt->execute([$year]);
          } else {
            $stmt = $pdo->query("SELECT COALESCE(SUM(st.quantity * st.unit_cost), 0) as expenses FROM stock_transactions st WHERE st.transaction_type IN ('stock_out_sale', 'stock_out_use', 'stock_out_damage') $expenseDateCondition");
          }
          $expenses = $stmt->fetchColumn();
          
          // Net profit
          $netProfit = $revenue - $expenses;
          
          echo json_encode(['success' => true, 'data' => [
            'revenue' => $revenue,
            'expenses' => $expenses,
            'net_profit' => $netProfit,
            'year' => $year,
            'period' => $period
          ]]);
          break;

        default:
          echo json_encode(['success' => false, 'message' => 'Invalid action']);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);
      
      switch ($action) {
        case 'export':
          // Handle report export
          echo json_encode(['success' => true, 'message' => 'Export functionality pending']);
          break;

        default:
          echo json_encode(['success' => false, 'message' => 'Invalid action']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
