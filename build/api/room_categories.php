<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$data = null;

if ($method === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  $action = $data['action'] ?? $action;
}

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        $stmt = $pdo->query("
          SELECT rc.*,
            COUNT(r.room_id) as room_count,
            SUM(CASE WHEN r.room_status = 'available' THEN 1 ELSE 0 END) as available_rooms,
            SUM(CASE WHEN r.room_status = 'occupied' THEN 1 ELSE 0 END) as occupied_rooms,
            SUM(CASE WHEN r.room_status = 'reserved' THEN 1 ELSE 0 END) as reserved_rooms,
            p.property_name
          FROM room_categories rc
          LEFT JOIN rooms r ON rc.category_id = r.category_id
          LEFT JOIN properties p ON rc.property_id = p.property_id
          GROUP BY rc.category_id
          ORDER BY rc.category_name ASC
        ");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch rooms for each category
        foreach ($categories as &$category) {
          $roomsStmt = $pdo->prepare("
            SELECT r.room_id, r.room_number, r.room_name, r.room_status, r.floor_number, r.building_block
            FROM rooms r
            WHERE r.category_id = ?
            ORDER BY r.room_number ASC
          ");
          $roomsStmt->execute([$category['category_id']]);
          $category['rooms'] = $roomsStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode(['success' => true, 'data' => $categories]);
      } else if ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("
          SELECT rc.*,
            COUNT(r.room_id) as room_count,
            SUM(CASE WHEN r.room_status = 'available' THEN 1 ELSE 0 END) as available_rooms,
            SUM(CASE WHEN r.room_status = 'occupied' THEN 1 ELSE 0 END) as occupied_rooms,
            SUM(CASE WHEN r.room_status = 'reserved' THEN 1 ELSE 0 END) as reserved_rooms,
            p.property_name
          FROM room_categories rc
          LEFT JOIN rooms r ON rc.category_id = r.category_id
          LEFT JOIN properties p ON rc.property_id = p.property_id
          WHERE rc.category_id = ?
          GROUP BY rc.category_id
        ");
        $stmt->execute([$_GET['id']]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category) {
          // Fetch rooms for this category
          $roomsStmt = $pdo->prepare("
            SELECT r.room_id, r.room_number, r.room_name, r.room_status, r.floor_number, r.building_block
            FROM rooms r
            WHERE r.category_id = ?
            ORDER BY r.room_number ASC
          ");
          $roomsStmt->execute([$_GET['id']]);
          $category['rooms'] = $roomsStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode(['success' => true, 'data' => $category]);
      }
      break;

    case 'POST':
      if ($action === 'create') {
        $amenities = $data['amenities'] ?? [];

        $stmt = $pdo->prepare("INSERT INTO room_categories (category_id, property_id, category_code, category_name, description, base_price_per_night, base_price_per_hour, base_price_day_use, minimum_hours, max_adult_capacity, max_child_capacity, max_total_capacity, extra_adult_fee, extra_child_fee, extra_senior_fee, floor_area_sqm, bed_configuration, view_type, floor_level, smoking_policy, pet_friendly, allows_hourly, allows_overnight, allows_day_use, sort_order, is_active, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
          $data['category_id'],
          $data['property_id'],
          $data['category_code'],
          $data['category_name'],
          $data['description'] ?? null,
          $data['base_price_per_night'] ?? 0,
          $data['base_price_per_hour'] ?? 0,
          $data['base_price_day_use'] ?? 0,
          $data['minimum_hours'] ?? 2,
          $data['max_adult_capacity'] ?? 2,
          $data['max_child_capacity'] ?? 2,
          $data['max_total_capacity'] ?? 4,
          $data['extra_adult_fee'] ?? 0,
          $data['extra_child_fee'] ?? 0,
          $data['extra_senior_fee'] ?? 0,
          $data['floor_area_sqm'] ?? null,
          $data['bed_configuration'] ?? null,
          $data['view_type'] ?? null,
          $data['floor_level'] ?? null,
          $data['smoking_policy'] ?? 'non_smoking',
          $data['pet_friendly'] ?? 0,
          $data['allows_hourly'] ?? 1,
          $data['allows_overnight'] ?? 1,
          $data['allows_day_use'] ?? 1,
          $data['sort_order'] ?? 0,
          $data['is_active'] ?? 1,
          $_SESSION['user_id'] ?? null
        ]);

        // Sync amenities - delete existing then insert new
        $deleteStmt = $pdo->prepare("DELETE FROM room_amenities WHERE category_id = ? AND room_id IS NULL");
        $deleteStmt->execute([$data['category_id']]);

        if (!empty($amenities)) {
          $insertStmt = $pdo->prepare("INSERT INTO room_amenities (category_id, amenity_id, notes) VALUES (?, ?, NULL)");
          foreach ($amenities as $amenityId) {
            $insertStmt->execute([$data['category_id'], $amenityId]);
          }
        }

        echo json_encode(['success' => true, 'message' => 'Room category created successfully']);
      } else if ($action === 'update') {
        $amenities = $data['amenities'] ?? [];

        $stmt = $pdo->prepare("UPDATE room_categories SET property_id = ?, category_code = ?, category_name = ?, description = ?, base_price_per_night = ?, base_price_per_hour = ?, base_price_day_use = ?, minimum_hours = ?, max_adult_capacity = ?, max_child_capacity = ?, max_total_capacity = ?, extra_adult_fee = ?, extra_child_fee = ?, extra_senior_fee = ?, floor_area_sqm = ?, bed_configuration = ?, view_type = ?, floor_level = ?, smoking_policy = ?, pet_friendly = ?, allows_hourly = ?, allows_overnight = ?, allows_day_use = ?, sort_order = ?, is_active = ?, updated_at = NOW() WHERE category_id = ?");
        $stmt->execute([
          $data['property_id'],
          $data['category_code'],
          $data['category_name'],
          $data['description'] ?? null,
          $data['base_price_per_night'] ?? 0,
          $data['base_price_per_hour'] ?? 0,
          $data['base_price_day_use'] ?? 0,
          $data['minimum_hours'] ?? 2,
          $data['max_adult_capacity'] ?? 2,
          $data['max_child_capacity'] ?? 2,
          $data['max_total_capacity'] ?? 4,
          $data['extra_adult_fee'] ?? 0,
          $data['extra_child_fee'] ?? 0,
          $data['extra_senior_fee'] ?? 0,
          $data['floor_area_sqm'] ?? null,
          $data['bed_configuration'] ?? null,
          $data['view_type'] ?? null,
          $data['floor_level'] ?? null,
          $data['smoking_policy'] ?? 'non_smoking',
          $data['pet_friendly'] ?? 0,
          $data['allows_hourly'] ?? 1,
          $data['allows_overnight'] ?? 1,
          $data['allows_day_use'] ?? 1,
          $data['sort_order'] ?? 0,
          $data['is_active'] ?? 1,
          $data['category_id']
        ]);

        // Sync amenities - delete existing then insert new
        $deleteStmt = $pdo->prepare("DELETE FROM room_amenities WHERE category_id = ? AND room_id IS NULL");
        $deleteStmt->execute([$data['category_id']]);

        if (!empty($amenities)) {
          $insertStmt = $pdo->prepare("INSERT INTO room_amenities (category_id, amenity_id, notes) VALUES (?, ?, NULL)");
          foreach ($amenities as $amenityId) {
            $insertStmt->execute([$data['category_id'], $amenityId]);
          }
        }

        echo json_encode(['success' => true, 'message' => 'Room category updated successfully']);
      } else if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM room_categories WHERE category_id = ?");
        $stmt->execute([$data['category_id']]);
        echo json_encode(['success' => true, 'message' => 'Room category deleted successfully']);
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
