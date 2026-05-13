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
            rr.*,
            r.reservation_code,
            rt.req_code,
            rt.req_label,
            rg.first_name,
            rg.last_name,
            rg.guest_type
          FROM reservation_requirements rr
          INNER JOIN reservations r ON rr.reservation_id = r.reservation_id
          INNER JOIN reservation_requirement_types rt ON rr.req_type_id = rt.req_type_id
          LEFT JOIN reservation_guests rg ON rr.res_guest_id = rg.res_guest_id
          ORDER BY rr.created_at DESC
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $rows]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT rr.*, r.reservation_code, rt.req_label FROM reservation_requirements rr INNER JOIN reservations r ON rr.reservation_id = r.reservation_id INNER JOIN reservation_requirement_types rt ON rr.req_type_id = rt.req_type_id WHERE rr.req_id = ?");
        $stmt->execute([$_GET['id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $row]);
      } else if ($action === 'types') {
        $stmt = $pdo->query("
          SELECT rt.*, p.property_name
          FROM reservation_requirement_types rt
          LEFT JOIN properties p ON rt.property_id = p.property_id
          ORDER BY rt.sort_order ASC, rt.req_label ASC
        ");
        $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $types]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $stmt = $pdo->prepare("
          INSERT INTO reservation_requirements (
            req_id, reservation_id, req_type_id, res_guest_id,
            status, file_url, notes, created_at, updated_at
          ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([
          $data['req_id'],
          $data['reservation_id'],
          $data['req_type_id'],
          $data['res_guest_id'] ?? null,
          $data['status'] ?? 'pending',
          $data['file_url'] ?? null,
          $data['notes'] ?? null,
        ]);
        echo json_encode(['success' => true, 'message' => 'Requirement created successfully']);
      } else if ($action === 'update') {
        $stmt = $pdo->prepare("
          UPDATE reservation_requirements SET
            status = ?, file_url = ?, notes = ?,
            reviewed_by = ?, reviewed_at = NOW(), updated_at = NOW()
          WHERE req_id = ?
        ");
        $stmt->execute([
          $data['status'],
          $data['file_url'] ?? null,
          $data['notes'] ?? null,
          $data['reviewed_by'] ?? null,
          $data['req_id'],
        ]);
        echo json_encode(['success' => true, 'message' => 'Requirement updated successfully']);
      } else if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM reservation_requirements WHERE req_id = ?");
        $stmt->execute([$data['req_id']]);
        echo json_encode(['success' => true, 'message' => 'Requirement deleted successfully']);
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
