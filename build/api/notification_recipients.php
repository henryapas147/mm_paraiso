<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        $notifId = $_GET['notif_id'] ?? null;

        $sql = "SELECT nr.*, n.title as notif_title, ua.email, up.first_name, up.last_name
                FROM notification_recipients nr
                INNER JOIN notifications n ON nr.notif_id = n.notif_id
                LEFT JOIN user_accounts ua ON nr.user_id = ua.user_id
                LEFT JOIN user_profiles up ON nr.user_id = up.user_id
                WHERE 1=1";
        $params = [];

        if ($notifId) {
          $sql .= " AND nr.notif_id = ?";
          $params[] = $notifId;
        }

        $sql .= " ORDER BY nr.created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

      } elseif ($action === 'related') {
        $notifications = $pdo->query("SELECT notif_id, title FROM notifications WHERE status IN ('draft', 'scheduled') ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
        $users = $pdo->query("SELECT ua.user_id, ua.email, CONCAT(up.first_name, ' ', up.last_name) as display FROM user_accounts ua LEFT JOIN user_profiles up ON ua.user_id = up.user_id WHERE ua.account_status = 'active' ORDER BY ua.email ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['notifications' => $notifications, 'users' => $users]]);

      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT nr.*, n.title as notif_title, ua.email, up.first_name, up.last_name FROM notification_recipients nr INNER JOIN notifications n ON nr.notif_id = n.notif_id LEFT JOIN user_accounts ua ON nr.user_id = ua.user_id LEFT JOIN user_profiles up ON nr.user_id = up.user_id WHERE nr.recipient_id = ?");
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $recipId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        
        $stmt = $pdo->prepare("INSERT INTO notification_recipients (recipient_id, notif_id, user_id) VALUES (?,?,?)");
        $stmt->execute([$recipId, $data['notif_id'], $data['user_id']]);
        echo json_encode(['success' => true, 'message' => 'Recipient added', 'recipient_id' => $recipId]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE notification_recipients SET notif_id=?, user_id=?, is_read=?, read_at=?, is_sent=?, sent_at=?, delivery_status=?, error_message=? WHERE recipient_id=?");
        $stmt->execute([
          $data['notif_id'], $data['user_id'], $data['is_read'] ?? 0, $data['read_at'] ?? null,
          $data['is_sent'] ?? 0, $data['sent_at'] ?? null, $data['delivery_status'] ?? 'pending',
          $data['error_message'] ?? null, $data['recipient_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Recipient updated']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM notification_recipients WHERE recipient_id=?");
        $stmt->execute([$data['recipient_id']]);
        echo json_encode(['success' => true, 'message' => 'Recipient deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
