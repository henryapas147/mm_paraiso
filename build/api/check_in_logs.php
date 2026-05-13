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
          SELECT
            cil.*,
            r.reservation_code,
            r.check_in_date,
            r.check_out_date,
            rm.room_number,
            rm.room_name,
            p.property_name
          FROM check_in_logs cil
          INNER JOIN reservations r ON cil.reservation_id = r.reservation_id
          LEFT JOIN reservation_rooms rr ON cil.res_room_id = rr.res_room_id
          LEFT JOIN rooms rm ON rr.room_id = rm.room_id
          LEFT JOIN properties p ON r.property_id = p.property_id
          ORDER BY cil.event_at DESC
        ");
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $logs]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT cil.*, r.reservation_code FROM check_in_logs cil INNER JOIN reservations r ON cil.reservation_id = r.reservation_id WHERE cil.log_id = ?");
        $stmt->execute([$_GET['id']]);
        $log = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $log]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $stmt = $pdo->prepare("
          INSERT INTO check_in_logs (
            log_id, reservation_id, res_room_id, event_type,
            event_at, processed_by, notes, created_at
          ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
          $data['log_id'],
          $data['reservation_id'],
          $data['res_room_id'] ?? null,
          $data['event_type'],
          $data['event_at'],
          $data['processed_by'] ?? null,
          $data['notes'] ?? null,
        ]);
        echo json_encode(['success' => true, 'message' => 'Check-in log created successfully']);
      } else if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM check_in_logs WHERE log_id = ?");
        $stmt->execute([$data['log_id']]);
        echo json_encode(['success' => true, 'message' => 'Log deleted successfully']);
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
