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
        $propertyId = $_GET['property_id'] ?? null;
        $status = $_GET['status'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT n.*, p.property_name 
                FROM notifications n 
                LEFT JOIN properties p ON n.property_id = p.property_id 
                WHERE 1=1";
        $params = [];
        
        if ($propertyId) {
          $sql .= " AND (n.property_id = ? OR n.property_id IS NULL)";
          $params[] = $propertyId;
        }
        if ($status) {
          $sql .= " AND n.status = ?";
          $params[] = $status;
        }
        
        $sql .= " ORDER BY n.created_at DESC";
        
        $countSql = "SELECT COUNT(*) FROM ($sql) as count_table";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();
        
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($notifications as &$notif) {
          $stmt = $pdo->prepare("SELECT nr.*, u.username, u.email, u.first_name, u.last_name FROM notification_recipients nr LEFT JOIN users u ON nr.user_id = u.user_id WHERE nr.notif_id = ?");
          $stmt->execute([$notif['notif_id']]);
          $notif['recipients'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        unset($notif);

        $totalPages = ceil($total / $limit);
        
        echo json_encode([
          'success' => true,
          'data' => $notifications,
          'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'totalPages' => $totalPages
          ]
        ]);

      } elseif ($action === 'related') {
        $properties = $pdo->query("SELECT property_id, property_name FROM properties ORDER BY property_name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $users = $pdo->query("SELECT user_id, username, email, CONCAT(first_name, ' ', last_name) as display FROM users WHERE is_active = 1 ORDER BY username ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['properties' => $properties, 'users' => $users]]);

      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT n.*, p.property_name FROM notifications n LEFT JOIN properties p ON n.property_id = p.property_id WHERE n.notif_id = ?");
        $stmt->execute([$_GET['id']]);
        $notif = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($notif) {
          $stmt = $pdo->prepare("SELECT nr.*, u.username, u.email, u.first_name, u.last_name FROM notification_recipients nr LEFT JOIN users u ON nr.user_id = u.user_id WHERE nr.notif_id = ?");
          $stmt->execute([$notif['notif_id']]);
          $notif['recipients'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        echo json_encode(['success' => true, 'data' => $notif]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $notifId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        
        $stmt = $pdo->prepare("INSERT INTO notifications (notif_id, property_id, notif_type, title, body, action_url, target_audience, reference_type, reference_id, scheduled_at, status, created_by) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $notifId, $data['property_id'] ?? null, $data['notif_type'],
          $data['title'], $data['body'], $data['action_url'] ?? null,
          $data['target_audience'] ?? 'specific_user', $data['reference_type'] ?? null, $data['reference_id'] ?? null,
          $data['scheduled_at'] ?? null, $data['status'] ?? 'draft', $data['created_by'] ?? null
        ]);

        foreach ($data['recipients'] ?? [] as $recipient) {
          $recipId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
            mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
          $stmt = $pdo->prepare("INSERT INTO notification_recipients (recipient_id, notif_id, user_id) VALUES (?,?,?)");
          $stmt->execute([$recipId, $notifId, $recipient['user_id']]);
        }

        echo json_encode(['success' => true, 'message' => 'Notification created', 'notif_id' => $notifId]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE notifications SET property_id=?, notif_type=?, title=?, body=?, action_url=?, target_audience=?, reference_type=?, reference_id=?, scheduled_at=?, status=?, created_by=? WHERE notif_id=?");
        $stmt->execute([
          $data['property_id'] ?? null, $data['notif_type'],
          $data['title'], $data['body'], $data['action_url'] ?? null,
          $data['target_audience'] ?? 'specific_user', $data['reference_type'] ?? null, $data['reference_id'] ?? null,
          $data['scheduled_at'] ?? null, $data['status'] ?? 'draft', $data['created_by'] ?? null,
          $data['notif_id']
        ]);

        $stmt = $pdo->prepare("DELETE FROM notification_recipients WHERE notif_id=?");
        $stmt->execute([$data['notif_id']]);

        foreach ($data['recipients'] ?? [] as $recipient) {
          $recipId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
            mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
          $stmt = $pdo->prepare("INSERT INTO notification_recipients (recipient_id, notif_id, user_id) VALUES (?,?,?)");
          $stmt->execute([$recipId, $data['notif_id'], $recipient['user_id']]);
        }

        echo json_encode(['success' => true, 'message' => 'Notification updated']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM notification_recipients WHERE notif_id=?");
        $stmt->execute([$data['notif_id']]);
        $stmt = $pdo->prepare("DELETE FROM notifications WHERE notif_id=?");
        $stmt->execute([$data['notif_id']]);
        echo json_encode(['success' => true, 'message' => 'Notification deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
