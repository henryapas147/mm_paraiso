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
          SELECT
            rr.*,
            r.reservation_code,
            r.reservation_status,
            rm.room_number,
            rm.room_name,
            rc.category_name,
            p.property_name
          FROM reservation_rooms rr
          INNER JOIN reservations r ON rr.reservation_id = r.reservation_id
          INNER JOIN rooms rm ON rr.room_id = rm.room_id
          INNER JOIN room_categories rc ON rr.category_id = rc.category_id
          INNER JOIN properties p ON r.property_id = p.property_id
          ORDER BY rr.created_at DESC
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $rows]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT rr.*, r.reservation_code FROM reservation_rooms rr INNER JOIN reservations r ON rr.reservation_id = r.reservation_id WHERE rr.res_room_id = ?");
        $stmt->execute([$_GET['id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $row]);
      } else if ($action === 'by_reservation' && isset($_GET['reservation_id'])) {
        $stmt = $pdo->prepare("
          SELECT rr.*, rm.room_number, rm.room_name, rc.category_name
          FROM reservation_rooms rr
          INNER JOIN rooms rm ON rr.room_id = rm.room_id
          INNER JOIN room_categories rc ON rr.category_id = rc.category_id
          WHERE rr.reservation_id = ?
        ");
        $stmt->execute([$_GET['reservation_id']]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $rows]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $stmt = $pdo->prepare("
          INSERT INTO reservation_rooms (
            res_room_id, reservation_id, room_id, category_id,
            booking_type, price_per_night, num_nights, num_hours,
            room_total, room_status, notes, created_at, updated_at
          ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([
          $data['res_room_id'],
          $data['reservation_id'],
          $data['room_id'],
          $data['category_id'],
          $data['booking_type'],
          $data['price_per_night'],
          $data['num_nights'] ?? null,
          $data['num_hours'] ?? null,
          $data['room_total'],
          $data['room_status'] ?? 'reserved',
          $data['notes'] ?? null,
        ]);
        echo json_encode(['success' => true, 'message' => 'Reservation room added successfully']);
      } else if ($action === 'update') {
        $stmt = $pdo->prepare("
          UPDATE reservation_rooms SET
            room_status = ?, notes = ?, updated_at = NOW()
          WHERE res_room_id = ?
        ");
        $stmt->execute([
          $data['room_status'],
          $data['notes'] ?? null,
          $data['res_room_id'],
        ]);
        echo json_encode(['success' => true, 'message' => 'Reservation room updated successfully']);
      } else if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM reservation_rooms WHERE res_room_id = ?");
        $stmt->execute([$data['res_room_id']]);
        echo json_encode(['success' => true, 'message' => 'Reservation room deleted successfully']);
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
