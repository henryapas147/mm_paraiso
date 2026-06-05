<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      requirePermission('settings.view');
      if ($action === 'list') {
        $promoId = $_GET['promo_id'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;
        
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
        
        $countSql = "SELECT COUNT(*) FROM ($sql) as count_table";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();
        
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $totalPages = ceil($total / $limit);
        
        echo json_encode([
          'success' => true,
          'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
          'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'totalPages' => $totalPages
          ]
        ]);

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
      requirePermission('settings.edit');
      if ($action === 'create') {
        $imageId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

        $imageUrl = null;
        $thumbnailUrl = null;

        // Handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
          $uploadDir = __DIR__ . '/../../uploads/promotion_images/';
          if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
          }

          $fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
          $fileName = $imageId . '.' . $fileExt;
          $filePath = $uploadDir . $fileName;

          if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            $imageUrl = 'uploads/promotion_images/' . $fileName;
          } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
            exit;
          }
        } else {
          // Fallback to URL if no file uploaded
          $data = json_decode(file_get_contents('php://input'), true);
          $imageUrl = $data['image_url'] ?? null;
          $thumbnailUrl = $data['thumbnail_url'] ?? null;
        }

        // Get other form data
        $promoId = $_POST['promo_id'] ?? ($data['promo_id'] ?? null);
        $imageType = $_POST['image_type'] ?? ($data['image_type'] ?? 'banner');
        $sortOrder = $_POST['sort_order'] ?? ($data['sort_order'] ?? 0);
        $isActive = $_POST['is_active'] ?? ($data['is_active'] ?? 1);
        $uploadedBy = $_POST['uploaded_by'] ?? ($data['uploaded_by'] ?? null);

        if (!$imageUrl) {
          echo json_encode(['success' => false, 'message' => 'Image URL or file is required']);
          exit;
        }

        $stmt = $pdo->prepare("INSERT INTO promotion_images (promo_image_id, promo_id, image_url, thumbnail_url, image_type, sort_order, is_active, uploaded_by) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([
          $imageId, $promoId, $imageUrl, $thumbnailUrl,
          $imageType, $sortOrder, $isActive, $uploadedBy
        ]);
        echo json_encode(['success' => true, 'message' => 'Promotion image added', 'promo_image_id' => $imageId]);

      } elseif ($action === 'update') {
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("UPDATE promotion_images SET promo_id=?, image_url=?, thumbnail_url=?, image_type=?, sort_order=?, is_active=?, uploaded_by=? WHERE promo_image_id=?");
        $stmt->execute([
          $data['promo_id'], $data['image_url'], $data['thumbnail_url'] ?? null,
          $data['image_type'] ?? 'banner', $data['sort_order'] ?? 0, $data['is_active'] ?? 1,
          $data['uploaded_by'] ?? null, $data['promo_image_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Promotion image updated']);

      } elseif ($action === 'delete') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Get image info to delete file
        $stmt = $pdo->prepare("SELECT image_url FROM promotion_images WHERE promo_image_id=?");
        $stmt->execute([$data['promo_image_id']]);
        $image = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($image && $image['image_url']) {
          $filePath = realpath(__DIR__ . '/../../..') . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $image['image_url']);
          if (file_exists($filePath)) {
            unlink($filePath);
          }
        }
        
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
