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
          SELECT du.*, d.discount_code, d.discount_name
          FROM discount_usages du
          LEFT JOIN discounts d ON du.discount_id = d.discount_id
          ORDER BY du.applied_at DESC
        ");
        $usages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $usages]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT du.*, d.discount_code, d.discount_name FROM discount_usages du LEFT JOIN discounts d ON du.discount_id = d.discount_id WHERE du.usage_id = ?");
        $stmt->execute([$_GET['id']]);
        $usage = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $usage]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $usage_id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
          mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));

        $stmt = $pdo->prepare("
          INSERT INTO discount_usages (usage_id, discount_id, reservation_id, invoice_id,
            amount_saved, applied_at, applied_by, notes)
          VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)
        ");
        $stmt->execute([
          $usage_id,
          $data['discount_id'],
          $data['reservation_id'] ?? null,
          $data['invoice_id'] ?? null,
          $data['amount_saved'],
          $data['applied_by'] ?? null,
          $data['notes'] ?? null,
        ]);
        echo json_encode(['success' => true, 'message' => 'Discount usage recorded successfully']);
      } else if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM discount_usages WHERE usage_id = ?");
        $stmt->execute([$data['usage_id']]);
        echo json_encode(['success' => true, 'message' => 'Discount usage deleted successfully']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
