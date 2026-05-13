<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        $stmt = $pdo->query("
          SELECT fo.*, p.property_name, r.reservation_id as res_reservation_id, r.reservation_code
          FROM food_orders fo
          INNER JOIN properties p ON fo.property_id = p.property_id
          LEFT JOIN reservations r ON fo.reservation_id = r.reservation_id
          ORDER BY fo.created_at DESC
          LIMIT 200
        ");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
          $stmt = $pdo->prepare("SELECT foi.*, mi.item_name, mi.item_code FROM food_order_items foi INNER JOIN menu_items mi ON foi.item_id = mi.item_id WHERE foi.order_id = ?");
          $stmt->execute([$order['order_id']]);
          $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        unset($order);

        echo json_encode(['success' => true, 'data' => $orders]);

      } elseif ($action === 'related') {
        $properties = $pdo->query("SELECT property_id, property_name FROM properties ORDER BY property_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $reservations = $pdo->query("SELECT reservation_id, reservation_code, CONCAT(walkin_first_name, ' ', walkin_last_name) as display FROM reservations WHERE reservation_status IN ('confirmed', 'checked_in') ORDER BY reservation_code ASC")->fetchAll(PDO::FETCH_ASSOC);
        $menuItems = $pdo->query("SELECT item_id, item_name, item_code, price FROM menu_items WHERE is_active = 1 AND is_available = 1 ORDER BY item_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $paymentMethods = $pdo->query("SELECT method_id, method_name FROM payment_methods WHERE is_active = 1 ORDER BY method_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['properties' => $properties, 'reservations' => $reservations, 'menuItems' => $menuItems, 'paymentMethods' => $paymentMethods]]);

      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT fo.*, p.property_name, r.reservation_id as res_reservation_id, r.reservation_code FROM food_orders fo INNER JOIN properties p ON fo.property_id = p.property_id LEFT JOIN reservations r ON fo.reservation_id = r.reservation_id WHERE fo.order_id = ?");
        $stmt->execute([$_GET['id']]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($order) {
          $stmt = $pdo->prepare("SELECT foi.*, mi.item_name, mi.item_code FROM food_order_items foi INNER JOIN menu_items mi ON foi.item_id = mi.item_id WHERE foi.order_id = ?");
          $stmt->execute([$order['order_id']]);
          $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode(['success' => true, 'data' => $order]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        
        // Generate order number
        $year = date('Y');
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM food_orders WHERE order_number LIKE ?");
        $stmt->execute(["FO-{$year}%"]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'] + 1;
        $orderNumber = "FO-{$year}" . str_pad($count, 4, '0', STR_PAD_LEFT);

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO food_orders (order_id, property_id, reservation_id, order_number, order_type, table_number, room_id, customer_name, order_status, subtotal, discount_amount, tax_amount, total_amount, payment_status, payment_method_id, notes, created_by) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $id, $data['property_id'], $data['reservation_id'] ?? null, $orderNumber,
          $data['order_type'] ?? 'dine_in', $data['table_number'] ?? null, $data['room_id'] ?? null,
          $data['customer_name'] ?? null, $data['order_status'] ?? 'pending', $data['subtotal'] ?? 0,
          $data['discount_amount'] ?? 0, $data['tax_amount'] ?? 0, $data['total_amount'] ?? 0,
          $data['payment_status'] ?? 'unpaid', $data['payment_method_id'] ?? null, $data['notes'] ?? null,
          $data['created_by'] ?? null
        ]);

        foreach ($data['items'] ?? [] as $item) {
          $itemId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
            mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
          $stmt = $pdo->prepare("INSERT INTO food_order_items (order_item_id, order_id, item_id, item_name, quantity, unit_price, total_price, special_request) VALUES (?,?,?,?,?,?,?,?)");
          $stmt->execute([
            $itemId, $id, $item['item_id'], $item['item_name'], $item['quantity'],
            $item['unit_price'], $item['total_price'], $item['special_request'] ?? null
          ]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Order created', 'order_id' => $id, 'order_number' => $orderNumber]);

      } elseif ($action === 'update_status') {
        $stmt = $pdo->prepare("UPDATE food_orders SET order_status=? WHERE order_id=?");
        $stmt->execute([$data['order_status'], $data['order_id']]);
        echo json_encode(['success' => true, 'message' => 'Order status updated']);

      } elseif ($action === 'update_payment') {
        $stmt = $pdo->prepare("UPDATE food_orders SET payment_status=?, payment_method_id=? WHERE order_id=?");
        $stmt->execute([$data['payment_status'], $data['payment_method_id'] ?? null, $data['order_id']]);
        echo json_encode(['success' => true, 'message' => 'Payment status updated']);

      } elseif ($action === 'delete') {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("DELETE FROM food_order_items WHERE order_id=?");
        $stmt->execute([$data['order_id']]);
        $stmt = $pdo->prepare("DELETE FROM food_orders WHERE order_id=?");
        $stmt->execute([$data['order_id']]);
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Order deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
