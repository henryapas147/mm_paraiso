<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      requirePermission('reservations.view');
      if ($action === 'list') {
        $stmt = $pdo->query("
          SELECT
            el.*,
            p.property_name,
            r.reservation_code,
            rg.first_name,
            rg.last_name,
            rg.guest_type as res_guest_type
          FROM entrance_logs el
          INNER JOIN properties p ON el.property_id = p.property_id
          LEFT JOIN reservations r ON el.reservation_id = r.reservation_id
          LEFT JOIN reservation_guests rg ON el.res_guest_id = rg.res_guest_id
          ORDER BY el.event_at DESC
        ");
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $logs]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT el.*, p.property_name FROM entrance_logs el INNER JOIN properties p ON el.property_id = p.property_id WHERE el.entrance_log_id = ?");
        $stmt->execute([$_GET['id']]);
        $log = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $log]);
      }
      break;

    case 'POST':
      requirePermission('reservations.view');
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $stmt = $pdo->prepare("
          INSERT INTO entrance_logs (
            entrance_log_id, property_id, reservation_id, res_guest_id,
            visitor_name, visitor_type, entry_type, event_at,
            id_presented, logged_by, notes, created_at
          ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
          $data['entrance_log_id'],
          $data['property_id'],
          $data['reservation_id'] ?? null,
          $data['res_guest_id'] ?? null,
          $data['visitor_name'] ?? null,
          $data['visitor_type'],
          $data['entry_type'],
          $data['event_at'],
          $data['id_presented'] ?? null,
          $data['logged_by'] ?? null,
          $data['notes'] ?? null,
        ]);
        echo json_encode(['success' => true, 'message' => 'Entrance log created successfully']);
      } else if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM entrance_logs WHERE entrance_log_id = ?");
        $stmt->execute([$data['entrance_log_id']]);
        echo json_encode(['success' => true, 'message' => 'Entrance log deleted successfully']);
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
