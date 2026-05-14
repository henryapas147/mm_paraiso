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
        $stmt = $pdo->query("SELECT * FROM discounts ORDER BY created_at DESC");
        $discounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $discounts]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM discounts WHERE discount_id = ?");
        $stmt->execute([$_GET['id']]);
        $discount = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $discount]);
      } else if ($action === 'active') {
        $stmt = $pdo->query("SELECT * FROM discounts WHERE is_active = 1 AND (valid_until IS NULL OR valid_until >= CURDATE()) ORDER BY discount_name ASC");
        $discounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $discounts]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $discount_id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
          mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));

        $stmt = $pdo->prepare("
          INSERT INTO discounts (discount_id, discount_code, discount_name, discount_type,
            discount_value, min_amount, max_discount, applies_to, valid_from, valid_until,
            usage_limit, times_used, is_active, notes)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?)
        ");
        $stmt->execute([
          $discount_id,
          $data['discount_code'],
          $data['discount_name'],
          $data['discount_type'],
          $data['discount_value'],
          $data['min_amount'] ?? null,
          $data['max_discount'] ?? null,
          $data['applies_to'] ?? null,
          $data['valid_from'] ?? null,
          $data['valid_until'] ?? null,
          $data['usage_limit'] ?? null,
          $data['is_active'] ?? 1,
          $data['notes'] ?? null,
        ]);
        echo json_encode(['success' => true, 'message' => 'Discount created successfully', 'discount_id' => $discount_id]);
      } else if ($action === 'update') {
        $stmt = $pdo->prepare("
          UPDATE discounts SET discount_code=?, discount_name=?, discount_type=?,
            discount_value=?, min_amount=?, max_discount=?, applies_to=?,
            valid_from=?, valid_until=?, usage_limit=?, is_active=?, notes=?
          WHERE discount_id=?
        ");
        $stmt->execute([
          $data['discount_code'],
          $data['discount_name'],
          $data['discount_type'],
          $data['discount_value'],
          $data['min_amount'] ?? null,
          $data['max_discount'] ?? null,
          $data['applies_to'] ?? null,
          $data['valid_from'] ?? null,
          $data['valid_until'] ?? null,
          $data['usage_limit'] ?? null,
          $data['is_active'] ?? 1,
          $data['notes'] ?? null,
          $data['discount_id'],
        ]);
        echo json_encode(['success' => true, 'message' => 'Discount updated successfully']);
      } else if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM discounts WHERE discount_id = ?");
        $stmt->execute([$data['discount_id']]);
        echo json_encode(['success' => true, 'message' => 'Discount deleted successfully']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
