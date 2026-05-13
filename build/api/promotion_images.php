<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        $promoId = $_GET['promo_id'] ?? null;
        
        $sql = "SELECT pi.*, p.promo_title 
                FROM promotion_images pi 
                INNER JOIN promotions p ON pi.promo_id = p.promo_id 
                WHERE 1=1";
        $params = [];
        
        if ($promoId) {
          $sql .= " AND pi.promo_id = ?";
          $params[] = $promoId;
        }
        
        $sql .= " ORDER BY pi.sort_order ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

      } elseif ($action === 'related') {
        $promotions = $pdo->query("SELECT promo_id, promo_title FROM promotions WHERE is_active = 1 ORDER BY promo_title ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['promotions' => $promotions]]);

      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT pi.*, p.promo_title FROM promotion_images pi INNER JOIN promotions p ON pi.promo_id = p.promo_id WHERE pi.promo_image_id = ?");
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $imageId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        
        $stmt = $pdo->prepare("INSERT INTO promotion_images (promo_image_id, promo_id, image_url, thumbnail_url, image_type, sort_order, is_active, uploaded_by) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $imageId, $data['promo_id'], $data['image_url'], $data['thumbnail_url'] ?? null,
          $data['image_type'] ?? 'banner', $data['sort_order'] ?? 0, $data['is_active'] ?? 1, $data['uploaded_by'] ?? null
        ]);
        echo json_encode(['success' => true, 'message' => 'Promotion image added', 'promo_image_id' => $imageId]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE promotion_images SET promo_id=?, image_url=?, thumbnail_url=?, image_type=?, sort_order=?, is_active=?, uploaded_by=? WHERE promo_image_id=?");
        $stmt->execute([
          $data['promo_id'], $data['image_url'], $data['thumbnail_url'] ?? null,
          $data['image_type'] ?? 'banner', $data['sort_order'] ?? 0, $data['is_active'] ?? 1,
          $data['uploaded_by'] ?? null, $data['promo_image_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Promotion image updated']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM promotion_images WHERE promo_image_id=?");
        $stmt->execute([$data['promo_image_id']]);
        echo json_encode(['success' => true, 'message' => 'Promotion image deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
