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
            rg.*,
            r.reservation_code,
            r.check_in_date,
            r.check_out_date,
            CONCAT(COALESCE(r.walkin_first_name, up.first_name), ' ', COALESCE(r.walkin_last_name, up.last_name)) as primary_guest_name,
            p.property_name
          FROM reservation_guests rg
          INNER JOIN reservations r ON rg.reservation_id = r.reservation_id
          LEFT JOIN user_accounts ua ON r.customer_user_id = ua.user_id
          LEFT JOIN user_profiles up ON r.customer_user_id = up.user_id
          LEFT JOIN properties p ON r.property_id = p.property_id
          ORDER BY rg.created_at DESC
        ");
        $guests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $guests]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT rg.*, r.reservation_code FROM reservation_guests rg INNER JOIN reservations r ON rg.reservation_id = r.reservation_id WHERE rg.res_guest_id = ?");
        $stmt->execute([$_GET['id']]);
        $guest = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $guest]);
      } else if ($action === 'by_reservation' && isset($_GET['reservation_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM reservation_guests WHERE reservation_id = ? ORDER BY guest_type ASC");
        $stmt->execute([$_GET['reservation_id']]);
        $guests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $guests]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $stmt = $pdo->prepare("
          INSERT INTO reservation_guests (
            res_guest_id, reservation_id, guest_type, first_name, last_name,
            age, gender, nationality, id_type, id_number, notes, created_at, updated_at
          ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([
          $data['res_guest_id'],
          $data['reservation_id'],
          $data['guest_type'],
          $data['first_name'],
          $data['last_name'],
          $data['age'] ?? null,
          $data['gender'] ?? null,
          $data['nationality'] ?? null,
          $data['id_type'] ?? null,
          $data['id_number'] ?? null,
          $data['notes'] ?? null,
        ]);
        echo json_encode(['success' => true, 'message' => 'Guest added successfully']);
      } else if ($action === 'update') {
        $stmt = $pdo->prepare("
          UPDATE reservation_guests SET
            guest_type = ?, first_name = ?, last_name = ?,
            age = ?, gender = ?, nationality = ?,
            id_type = ?, id_number = ?, notes = ?, updated_at = NOW()
          WHERE res_guest_id = ?
        ");
        $stmt->execute([
          $data['guest_type'],
          $data['first_name'],
          $data['last_name'],
          $data['age'] ?? null,
          $data['gender'] ?? null,
          $data['nationality'] ?? null,
          $data['id_type'] ?? null,
          $data['id_number'] ?? null,
          $data['notes'] ?? null,
          $data['res_guest_id'],
        ]);
        echo json_encode(['success' => true, 'message' => 'Guest updated successfully']);
      } else if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM reservation_guests WHERE res_guest_id = ?");
        $stmt->execute([$data['res_guest_id']]);
        echo json_encode(['success' => true, 'message' => 'Guest deleted successfully']);
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
