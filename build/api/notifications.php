<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

/**
 * Check if user has enabled a specific notification preference
 *
 * @param PDO $pdo Database connection
 * @param string $userId User ID
 * @param string $settingKey Setting key (e.g., 'push_notifications')
 * @return bool True if setting is enabled or not found (default to enabled)
 */
function getUserNotificationPreference($pdo, $userId, $settingKey) {
    if (!$pdo || !$userId) {
        return true; // Default to enabled if no DB connection or no user ID
    }

    try {
        $stmt = $pdo->prepare("
            SELECT setting_value, setting_type
            FROM user_settings
            WHERE user_id = ? AND setting_key = ?
        ");
        $stmt->execute([$userId, $settingKey]);
        $setting = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$setting) {
            return true; // Default to enabled if setting not found
        }

        // Convert based on type
        $value = $setting['setting_value'];
        switch ($setting['setting_type']) {
            case 'boolean':
                return (bool) $value;
            case 'integer':
                return (int) $value === 1;
            default:
                return (string) $value === '1' || (string) $value === 'true';
        }
    } catch (Exception $e) {
        error_log('Failed to check user notification preference: ' . $e->getMessage());
        return true; // Default to enabled on error
    }
}

try {
  switch ($method) {
    case 'GET':
      requirePermission('dashboard.view');
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
          $stmt = $pdo->prepare("SELECT nr.*, u.email, p.first_name, p.last_name FROM notification_recipients nr LEFT JOIN user_accounts u ON nr.user_id = u.user_id LEFT JOIN user_profiles p ON u.user_id = p.user_id WHERE nr.notif_id = ?");
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
        $users = $pdo->query("SELECT u.user_id, u.email, CONCAT(p.first_name, ' ', p.last_name) as display FROM user_accounts u LEFT JOIN user_profiles p ON u.user_id = p.user_id WHERE u.account_status = 'active' ORDER BY u.email ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['properties' => $properties, 'users' => $users]]);

      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT n.*, p.property_name FROM notifications n LEFT JOIN properties p ON n.property_id = p.property_id WHERE n.notif_id = ?");
        $stmt->execute([$_GET['id']]);
        $notif = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($notif) {
          $stmt = $pdo->prepare("SELECT nr.*, u.email, p.first_name, p.last_name FROM notification_recipients nr LEFT JOIN user_accounts u ON nr.user_id = u.user_id LEFT JOIN user_profiles p ON u.user_id = p.user_id WHERE nr.notif_id = ?");
          $stmt->execute([$notif['notif_id']]);
          $notif['recipients'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        echo json_encode(['success' => true, 'data' => $notif]);

      } elseif ($action === 'my-notifications') {
        $userId = $_SESSION['user_id'] ?? null;

        // Get user role to filter notifications appropriately
        $userRole = null;
        if ($userId) {
          $stmt = $pdo->prepare("SELECT r.role_code FROM user_accounts ua JOIN user_roles r ON ua.role_id = r.role_id WHERE ua.user_id = ?");
          $stmt->execute([$userId]);
          $userRole = $stmt->fetchColumn();
        }

        // Debug: Log session user_id and role
        error_log("my-notifications: Session user_id = " . ($userId ?: 'NULL') . ", role = " . ($userRole ?: 'NULL'));

        if (!$userId) {
          echo json_encode(['success' => false, 'message' => 'Not authenticated', 'debug_session_user_id' => $userId]);
          break;
        }

        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

        // Filter by user role: only staff roles should see admin notifications
        $staffRoles = ['super_admin', 'admin', 'front_desk', 'housekeeping', 'canteen_staff', 'security'];
        $isStaff = in_array($userRole, $staffRoles);

        if (!$isStaff) {
          // Customers should not see admin notifications
          echo json_encode([
            'success' => true,
            'data' => [],
            'unread_count' => 0,
            'debug_user_id' => $userId,
            'debug_role' => $userRole,
            'debug_is_staff' => false
          ]);
          break;
        }

        // Check user notification preferences
        $inAppEnabled = getUserNotificationPreference($pdo, $userId, 'in_app_notifications');

        if (!$inAppEnabled) {
          // User has disabled in-app notifications
          echo json_encode([
            'success' => true,
            'data' => [],
            'unread_count' => 0,
            'debug_user_id' => $userId,
            'debug_role' => $userRole,
            'debug_is_staff' => $isStaff,
            'debug_in_app_disabled' => true
          ]);
          break;
        }

        $sql = "SELECT n.*, p.property_name
                FROM notifications n
                LEFT JOIN properties p ON n.property_id = p.property_id
                LEFT JOIN notification_recipients nr ON n.notif_id = nr.notif_id
                WHERE nr.user_id = ?
                AND n.status IN ('sent', 'delivered')
                ORDER BY n.created_at DESC
                LIMIT ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $limit]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        error_log("my-notifications: Found " . count($notifications) . " notifications for user $userId (role: $userRole)");

        $unreadCount = 0;
        foreach ($notifications as &$notif) {
          $stmt = $pdo->prepare("SELECT is_read FROM notification_recipients WHERE notif_id = ? AND user_id = ?");
          $stmt->execute([$notif['notif_id'], $userId]);
          $recipient = $stmt->fetch(PDO::FETCH_ASSOC);
          $notif['is_read'] = $recipient['is_read'] ?? 0;
          if (!$notif['is_read']) {
            $unreadCount++;
          }
        }
        unset($notif);

        echo json_encode([
          'success' => true,
          'data' => $notifications,
          'unread_count' => $unreadCount,
          'debug_user_id' => $userId,
          'debug_role' => $userRole,
          'debug_is_staff' => $isStaff
        ]);

      } elseif ($action === 'mark-read' && isset($_GET['id'])) {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
          echo json_encode(['success' => false, 'message' => 'Not authenticated']);
          break;
        }

        $stmt = $pdo->prepare("UPDATE notification_recipients SET is_read = 1, read_at = NOW() WHERE notif_id = ? AND user_id = ?");
        $stmt->execute([$_GET['id'], $userId]);
        
        echo json_encode(['success' => true, 'message' => 'Notification marked as read']);
      }
      break;

    case 'POST':
      requirePermission('dashboard.manage');
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
