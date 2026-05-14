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
        $stmt = $pdo->query("
          SELECT st.*,
            from_loc.location_name AS from_location_name, from_loc.location_type AS from_location_type,
            to_loc.location_name AS to_location_name, to_loc.location_type AS to_location_type,
            from_prop.property_name AS from_property_name, to_prop.property_name AS to_property_name
          FROM stock_transfers st
          INNER JOIN inventory_locations from_loc ON st.from_location_id = from_loc.location_id
          INNER JOIN inventory_locations to_loc ON st.to_location_id = to_loc.location_id
          INNER JOIN properties from_prop ON from_loc.property_id = from_prop.property_id
          INNER JOIN properties to_prop ON to_loc.property_id = to_prop.property_id
          ORDER BY st.transfer_date DESC
        ");
        $transfers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($action === 'list') {
          foreach ($transfers as &$transfer) {
            $si = $pdo->prepare("SELECT sti.*, p.product_name, p.product_code, u.uom_name FROM stock_transfer_items sti INNER JOIN products p ON sti.product_id = p.product_id INNER JOIN units_of_measure u ON sti.uom_id = u.uom_id WHERE sti.transfer_id = ?");
            $si->execute([$transfer['transfer_id']]);
            $transfer['items'] = $si->fetchAll(PDO::FETCH_ASSOC);
          }
          unset($transfer);
        }
        echo json_encode(['success' => true, 'data' => $transfers]);

      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT st.*, from_loc.location_name AS from_location_name, to_loc.location_name AS to_location_name FROM stock_transfers st INNER JOIN inventory_locations from_loc ON st.from_location_id = from_loc.location_id INNER JOIN inventory_locations to_loc ON st.to_location_id = to_loc.location_id WHERE st.transfer_id = ?");
        $stmt->execute([$_GET['id']]);
        $transfer = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($transfer) {
          $si = $pdo->prepare("SELECT sti.*, p.product_name, u.uom_name FROM stock_transfer_items sti INNER JOIN products p ON sti.product_id = p.product_id INNER JOIN units_of_measure u ON sti.uom_id = u.uom_id WHERE sti.transfer_id = ?");
          $si->execute([$transfer['transfer_id']]);
          $transfer['items'] = $si->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode(['success' => true, 'data' => $transfer]);
      } elseif ($action === 'related') {
        $products = $pdo->query("SELECT product_id, product_name, product_code FROM products WHERE is_active = 1 ORDER BY product_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $locations = $pdo->query("SELECT il.location_id, il.location_name, il.location_type, prop.property_name FROM inventory_locations il INNER JOIN properties prop ON il.property_id = prop.property_id WHERE il.is_active = 1 ORDER BY prop.property_name ASC, il.location_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $uoms = $pdo->query("SELECT uom_id, uom_name, uom_type FROM units_of_measure WHERE is_active = 1 ORDER BY uom_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['products' => $products, 'locations' => $locations, 'uoms' => $uoms]]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO stock_transfers (transfer_id, from_location_id, to_location_id, transfer_date, transfer_status, notes) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$id, $data['from_location_id'], $data['to_location_id'], $data['transfer_date'] ?? date('Y-m-d'), $data['transfer_status'] ?? 'pending', $data['notes'] ?? null]);
        foreach ($data['items'] ?? [] as $item) {
          $itemId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
            mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
          $si = $pdo->prepare("INSERT INTO stock_transfer_items (transfer_item_id, transfer_id, product_id, uom_id, quantity_requested, quantity_sent, quantity_received) VALUES (?,?,?,?,?,?,?)");
          $si->execute([$itemId, $id, $item['product_id'], $item['uom_id'], $item['quantity_requested'], $item['quantity_sent'] ?? null, $item['quantity_received'] ?? null]);
        }
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Transfer created', 'transfer_id' => $id]);

      } elseif ($action === 'update_status') {
        $stmt = $pdo->prepare("UPDATE stock_transfers SET transfer_status=? WHERE transfer_id=?");
        $stmt->execute([$data['transfer_status'], $data['transfer_id']]);
        echo json_encode(['success' => true, 'message' => 'Transfer status updated']);

      } elseif ($action === 'delete') {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("DELETE FROM stock_transfer_items WHERE transfer_id=?");
        $stmt->execute([$data['transfer_id']]);
        $stmt = $pdo->prepare("DELETE FROM stock_transfers WHERE transfer_id=?");
        $stmt->execute([$data['transfer_id']]);
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Transfer deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
