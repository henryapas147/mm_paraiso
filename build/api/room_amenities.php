<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      requirePermission('rooms.view');
      if ($action === 'by_category' && isset($_GET['category_id'])) {
        $stmt = $pdo->prepare("
          SELECT ra.*, a.amenity_name, a.icon_code
          FROM room_amenities ra
          JOIN amenities a ON ra.amenity_id = a.amenity_id
          WHERE ra.category_id = ? AND ra.room_id IS NULL
          ORDER BY a.sort_order ASC
        ");
        $stmt->execute([$_GET['category_id']]);
        $amenities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $amenities]);
      } else if ($action === 'by_room' && isset($_GET['room_id'])) {
        $stmt = $pdo->prepare("
          SELECT ra.*, a.amenity_name, a.icon_code
          FROM room_amenities ra
          JOIN amenities a ON ra.amenity_id = a.amenity_id
          WHERE ra.room_id = ? AND ra.category_id IS NULL
          ORDER BY a.sort_order ASC
        ");
        $stmt->execute([$_GET['room_id']]);
        $amenities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $amenities]);
      } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action or missing parameters']);
      }
      break;

    case 'POST':
      requirePermission('rooms.edit');
      $data = json_decode(file_get_contents('php://input'), true);
      $action = $data['action'] ?? '';

      if ($action === 'sync_category_amenities') {
        if (empty($data['category_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing category_id']);
          exit;
        }

        $categoryId = $data['category_id'];
        $amenities = $data['amenities'] ?? [];

        // Delete existing category amenities
        $deleteStmt = $pdo->prepare("DELETE FROM room_amenities WHERE category_id = ? AND room_id IS NULL");
        $deleteStmt->execute([$categoryId]);

        // Insert new amenity associations
        if (!empty($amenities)) {
          $insertStmt = $pdo->prepare("
            INSERT INTO room_amenities (category_id, amenity_id, notes)
            VALUES (?, ?, NULL)
          ");

          foreach ($amenities as $amenityId) {
            $insertStmt->execute([$categoryId, $amenityId]);
          }
        }

        echo json_encode(['success' => true, 'message' => 'Amenities synced successfully']);
      } else if ($action === 'sync_room_amenities') {
        if (empty($data['room_id'])) {
          echo json_encode(['success' => false, 'message' => 'Missing room_id']);
          exit;
        }

        $roomId = $data['room_id'];
        $amenities = $data['amenities'] ?? [];

        // Delete existing room amenities
        $deleteStmt = $pdo->prepare("DELETE FROM room_amenities WHERE room_id = ? AND category_id IS NULL");
        $deleteStmt->execute([$roomId]);

        // Insert new amenity associations
        if (!empty($amenities)) {
          $insertStmt = $pdo->prepare("
            INSERT INTO room_amenities (room_id, amenity_id, notes)
            VALUES (?, ?, NULL)
          ");

          foreach ($amenities as $amenityId) {
            $insertStmt->execute([$roomId, $amenityId]);
          }
        }

        echo json_encode(['success' => true, 'message' => 'Amenities synced successfully']);
      } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
