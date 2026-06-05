<?php
/**
 * Rooms API - Clean CRUD Implementation
 */

header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// Define all room fields with their default values
$ROOM_FIELDS = [
  'room_id' => null,
  'property_id' => null,
  'category_id' => null,
  'room_number' => null,
  'room_name' => null,
  'floor_number' => null,
  'building_block' => null,
  'room_status' => 'available',
  'override_price_per_night' => null,
  'override_price_per_hour' => null,
  'override_price_day_use' => null,
  'internal_notes' => null,
  'is_active' => 1
];

// Required fields for validation (property_id is derived from category_id)
$REQUIRED_FIELDS_CREATE = ['room_id', 'category_id', 'room_number'];
$REQUIRED_FIELDS_UPDATE = ['room_id', 'category_id', 'room_number'];

/**
 * Extract room data from request with defaults
 */
function extractRoomData(array $data, array $fields): array {
  $result = [];
  foreach ($fields as $field => $default) {
    $result[$field] = $data[$field] ?? $default;
  }
  return $result;
}

/**
 * Validate required fields
 */
function validateRequiredFields(array $data, array $required): array {
  $missing = [];
  foreach ($required as $field) {
    if (empty($data[$field])) {
      $missing[] = $field;
    }
  }
  return $missing;
}

/**
 * Build INSERT SQL from fields
 */
function buildInsertSql(string $table, array $fields): string {
  $columns = implode(', ', array_keys($fields));
  $placeholders = implode(', ', array_fill(0, count($fields), '?'));
  return "INSERT INTO {$table} ({$columns}, created_at, updated_at) VALUES ({$placeholders}, NOW(), NOW())";
}

/**
 * Build UPDATE SQL from fields
 */
function buildUpdateSql(string $table, array $fields, string $whereColumn): string {
  $sets = [];
  foreach (array_keys($fields) as $field) {
    $sets[] = "{$field} = ?";
  }
  $setClause = implode(', ', $sets);
  return "UPDATE {$table} SET {$setClause}, updated_at = NOW() WHERE {$whereColumn} = ?";
}

/**
 * Send JSON response
 */
function sendResponse(bool $success, string $message, array $data = []): void {
  $response = ['success' => $success, 'message' => $message];
  if (!empty($data)) {
    $response['data'] = $data;
  }
  echo json_encode($response);
  exit;
}

// Log incoming request for debugging
error_log("Rooms API - Method: $method, Action: $action");

try {
  switch ($method) {
    case 'GET':
      handleGetRequest($action, $pdo);
      break;

    case 'POST':
      // Check if this is a multipart form data request (file upload or image operations)
      if (isset($_FILES['room_images']) || isset($_FILES['property_images']) || isset($_FILES['replace_images'])) {
        handleImageUpload($pdo);
      } else {
        handlePostRequest($action, $pdo, $ROOM_FIELDS, $REQUIRED_FIELDS_CREATE, $REQUIRED_FIELDS_UPDATE);
      }
      break;

    default:
      sendResponse(false, 'Invalid request method');
  }
} catch (Exception $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }
  sendResponse(false, 'Error: ' . $e->getMessage());
}

/**
 * Handle GET requests
 */
function handleGetRequest(string $action, PDO $pdo): void {
  switch ($action) {
    case 'list':
      requirePermission('rooms.view');
      // Pagination parameters
      $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
      $perPage = isset($_GET['per_page']) ? min(100, max(5, (int)$_GET['per_page'])) : 10;
      $offset = ($page - 1) * $perPage;

      // Get total count
      $countStmt = $pdo->query("SELECT COUNT(*) as total FROM rooms");
      $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
      $totalPages = ceil($total / $perPage);

      // Get paginated rooms with property, category, and images info
      $stmt = $pdo->prepare("
        SELECT r.*, p.property_name, c.category_name,
               c.extra_adult_fee, c.extra_child_fee, c.extra_senior_fee,
               c.max_adult_capacity, c.max_child_capacity, c.max_total_capacity
        FROM rooms r
        LEFT JOIN properties p ON r.property_id = p.property_id
        LEFT JOIN room_categories c ON r.category_id = c.category_id
        ORDER BY p.property_name, r.room_number ASC
        LIMIT ? OFFSET ?
      ");
      $stmt->execute([$perPage, $offset]);
      $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Fetch images and amenities for each room
      foreach ($rooms as &$room) {
        // Fetch images
        $stmt = $pdo->prepare("SELECT * FROM room_images WHERE room_id = ? ORDER BY sort_order ASC");
        $stmt->execute([$room['room_id']]);
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $room['images'] = $images;

        // Fetch amenities with names
        $stmt = $pdo->prepare("
          SELECT ra.amenity_id, a.amenity_name, a.icon_code
          FROM room_amenities ra
          JOIN amenities a ON ra.amenity_id = a.amenity_id
          WHERE ra.room_id = ?
          ORDER BY a.sort_order ASC
        ");
        $stmt->execute([$room['room_id']]);
        $amenities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $room['amenities'] = $amenities;
      }

      sendResponse(true, 'Rooms retrieved', [
        'data' => $rooms,
        'pagination' => [
          'page' => $page,
          'per_page' => $perPage,
          'total' => $total,
          'total_pages' => $totalPages
        ]
      ]);
      break;

    case 'single':
      requirePermission('rooms.view');
      if (!isset($_GET['id'])) {
        sendResponse(false, 'Room ID required');
      }
      // Get single room with images
      $stmt = $pdo->prepare("SELECT * FROM rooms WHERE room_id = ?");
      $stmt->execute([$_GET['id']]);
      $room = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!$room) {
        sendResponse(false, 'Room not found');
      }
      
      $stmt = $pdo->prepare("SELECT * FROM room_images WHERE room_id = ? ORDER BY sort_order ASC");
      $stmt->execute([$room['room_id']]);
      $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $room['images'] = $images;

      // Fetch amenities with names
      $stmt = $pdo->prepare("
        SELECT ra.amenity_id, a.amenity_name, a.icon_code
        FROM room_amenities ra
        JOIN amenities a ON ra.amenity_id = a.amenity_id
        WHERE ra.room_id = ?
        ORDER BY a.sort_order ASC
      ");
      $stmt->execute([$room['room_id']]);
      $amenities = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $room['amenities'] = $amenities;

      sendResponse(true, 'Room retrieved', $room);
      break;

    case 'by_property':
      if (!isset($_GET['property_id'])) {
        sendResponse(false, 'Property ID required');
      }
      // Get rooms by property with images
      $stmt = $pdo->prepare("SELECT * FROM rooms WHERE property_id = ? ORDER BY room_number ASC");
      $stmt->execute([$_GET['property_id']]);
      $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      // Fetch images and amenities for each room
      foreach ($rooms as &$room) {
        // Fetch images
        $stmt = $pdo->prepare("SELECT * FROM room_images WHERE room_id = ? ORDER BY sort_order ASC");
        $stmt->execute([$room['room_id']]);
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $room['images'] = $images;

        // Fetch amenities with names
        $stmt = $pdo->prepare("
          SELECT ra.amenity_id, a.amenity_name, a.icon_code
          FROM room_amenities ra
          JOIN amenities a ON ra.amenity_id = a.amenity_id
          WHERE ra.room_id = ?
          ORDER BY a.sort_order ASC
        ");
        $stmt->execute([$room['room_id']]);
        $amenities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $room['amenities'] = $amenities;
      }

      sendResponse(true, 'Rooms retrieved', $rooms);
      break;

    default:
      sendResponse(false, 'Invalid action. Use: list, single, or by_property');
  }
}

/**
 * Handle POST requests
 */
function handlePostRequest(string $action, PDO $pdo, array $fields, array $requiredCreate, array $requiredUpdate): void {
  $jsonInput = file_get_contents('php://input');
  if (empty($jsonInput)) {
    sendResponse(false, 'No data received');
  }
  
  $data = json_decode($jsonInput, true);
  if (json_last_error() !== JSON_ERROR_NONE) {
    sendResponse(false, 'Invalid JSON data: ' . json_last_error_msg());
  }
  
  // If action is not in GET parameter, check JSON body
  if (empty($action) && !empty($data['action'])) {
    $action = $data['action'];
  }
  
  // Validate CSRF token
  if (empty($data['csrf_token'])) {
    sendResponse(false, 'CSRF token is required');
  }

  switch ($action) {
    case 'create':
      handleCreate($data, $pdo, $fields, $requiredCreate);
      break;

    case 'update':
      handleUpdate($data, $pdo, $fields, $requiredUpdate);
      break;

    case 'delete':
      handleDelete($data, $pdo);
      break;

    default:
      sendResponse(false, 'Invalid action');
  }
}

/**
 * Get property_id from category_id
 */
function getPropertyIdFromCategory(PDO $pdo, string $categoryId): ?string {
  $stmt = $pdo->prepare("SELECT property_id FROM room_categories WHERE category_id = ?");
  $stmt->execute([$categoryId]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result['property_id'] ?? null;
}

/**
 * Handle Create Room
 */
function handleCreate(array $data, PDO $pdo, array $fields, array $required): void {
  requirePermission('rooms.create');
  // Validate required fields
  $missing = validateRequiredFields($data, $required);
  if (!empty($missing)) {
    sendResponse(false, 'Missing required fields: ' . implode(', ', $missing));
  }

  // Derive property_id from category_id
  $propertyId = getPropertyIdFromCategory($pdo, $data['category_id']);
  if (!$propertyId) {
    sendResponse(false, 'Invalid category_id - could not determine property');
  }
  $data['property_id'] = $propertyId;

  // Extract and prepare data
  $roomData = extractRoomData($data, $fields);
  $amenities = $data['amenities'] ?? [];

  try {
    $pdo->beginTransaction();

    $sql = buildInsertSql('rooms', $roomData);
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($roomData));

    // Sync amenities - copy category defaults to room-specific rows
    $roomId = $data['room_id'];
    $categoryId = $data['category_id'];

    // For each amenity, INSERT new row with room_id (category defaults remain untouched)
    foreach ($amenities as $amenityId) {
      $insertStmt = $pdo->prepare("INSERT INTO room_amenities (category_id, room_id, amenity_id, notes) VALUES (?, ?, ?, NULL)");
      $insertStmt->execute([$categoryId, $roomId, $amenityId]);
    }

    $pdo->commit();
    sendResponse(true, 'Room created successfully');
  } catch (PDOException $e) {
    $pdo->rollBack();
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
      sendResponse(false, 'Room ID or room number already exists');
    } else if (strpos($e->getMessage(), 'foreign key constraint') !== false) {
      sendResponse(false, 'Invalid property or category ID');
    } else {
      sendResponse(false, 'Database error: ' . $e->getMessage());
    }
  }
}

/**
 * Handle Update Room
 */
function handleUpdate(array $data, PDO $pdo, array $fields, array $required): void {
  requirePermission('rooms.edit');
  if (empty($data['room_id'])) {
    sendResponse(false, 'Room ID required');
  }

  // Validate required fields
  $missing = validateRequiredFields($data, $required);
  if (!empty($missing)) {
    sendResponse(false, 'Missing required fields: ' . implode(', ', $missing));
  }

  // Derive property_id from category_id
  $propertyId = getPropertyIdFromCategory($pdo, $data['category_id']);
  if (!$propertyId) {
    sendResponse(false, 'Invalid category_id - could not determine property');
  }
  $data['property_id'] = $propertyId;

  // Extract and prepare data (exclude room_id from SET clause)
  $updateFields = $fields;
  unset($updateFields['room_id']);
  $roomData = extractRoomData($data, $updateFields);
  $amenities = $data['amenities'] ?? [];

  try {
    $pdo->beginTransaction();

    $sql = buildUpdateSql('rooms', $roomData, 'room_id');
    $stmt = $pdo->prepare($sql);
    // Add room_id as the last parameter for WHERE clause
    $values = array_values($roomData);
    $values[] = $data['room_id'];
    $result = $stmt->execute($values);

    // Sync amenities - delete room-specific amenities and insert new ones
    $roomId = $data['room_id'];
    $categoryId = $data['category_id'];

    // Delete existing room-specific amenities (category defaults remain untouched)
    $deleteStmt = $pdo->prepare("DELETE FROM room_amenities WHERE room_id = ?");
    $deleteStmt->execute([$roomId]);

    // Insert new room amenities
    foreach ($amenities as $amenityId) {
      $insertStmt = $pdo->prepare("INSERT INTO room_amenities (category_id, room_id, amenity_id, notes) VALUES (?, ?, ?, NULL)");
      $insertStmt->execute([$categoryId, $roomId, $amenityId]);
    }

    $pdo->commit();

    if ($result) {
      sendResponse(true, 'Room updated successfully');
    } else {
      sendResponse(false, 'No changes made or room not found');
    }
  } catch (PDOException $e) {
    $pdo->rollBack();
    if (strpos($e->getMessage(), 'foreign key constraint') !== false) {
      sendResponse(false, 'Invalid property or category ID');
    } else {
      sendResponse(false, 'Database error: ' . $e->getMessage());
    }
  }
}

/**
 * Handle Delete Room
 */
function handleDelete(array $data, PDO $pdo): void {
  requirePermission('rooms.delete');
  if (empty($data['room_id'])) {
    sendResponse(false, 'Room ID required');
  }

  try {
    // Check if room has reservations
    $stmt = $pdo->prepare("SELECT COUNT(*) as reservation_count FROM reservation_rooms WHERE room_id = ?");
    $stmt->execute([$data['room_id']]);
    $reservationCount = $stmt->fetch(PDO::FETCH_ASSOC)['reservation_count'];

    if ($reservationCount > 0) {
      sendResponse(false, "Cannot delete room because it has {$reservationCount} reservation(s). Please cancel or reassign the reservations first.");
    }

    $pdo->beginTransaction();

    // Delete room image files from filesystem
    $stmt = $pdo->prepare("SELECT image_url FROM room_images WHERE room_id = ?");
    $stmt->execute([$data['room_id']]);
    $images = $stmt->fetchAll();

    foreach ($images as $img) {
      $image_path = __DIR__ . '/../' . $img['image_url'];
      if (file_exists($image_path)) {
        unlink($image_path);
      }
    }

    // Delete room-specific amenities (category defaults remain untouched)
    $stmt = $pdo->prepare("DELETE FROM room_amenities WHERE room_id = ?");
    $stmt->execute([$data['room_id']]);

    // Delete from database (room_images will be deleted by CASCADE)
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE room_id = ?");
    $result = $stmt->execute([$data['room_id']]);
    
    if ($result && $stmt->rowCount() > 0) {
      $pdo->commit();
      sendResponse(true, 'Room deleted successfully');
    } else {
      $pdo->rollBack();
      sendResponse(false, 'Room not found');
    }
  } catch (PDOException $e) {
    $pdo->rollBack();
    sendResponse(false, 'Database error: ' . $e->getMessage());
  }
}

/**
 * Handle Image Upload, Replacement, and Deletion
 */
function handleImageUpload(PDO $pdo): void {
  $data = $_POST;
  $uploadedImages = [];
  $deletedImages = [];
  $replacedImages = [];
  
  // Validate room_id
  if (empty($data['room_id'])) {
    sendResponse(false, 'Room ID is required for image upload');
  }
  
  // Create upload directory if it doesn't exist
  $uploadDir = '../uploads/room_images';
  if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
      sendResponse(false, 'Failed to create upload directory');
    }
  }
  
  // Handle image deletions first
  if (isset($data['delete_images']) && is_array($data['delete_images'])) {
    foreach ($data['delete_images'] as $imageId) {
      if (empty($imageId)) continue;
      
      // Get the image path before deleting
      $stmt = $pdo->prepare("SELECT image_url FROM room_images WHERE image_id = ? AND room_id = ?");
      $stmt->execute([$imageId, $data['room_id']]);
      $image = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if ($image) {
        // Delete from database
        $deleteStmt = $pdo->prepare("DELETE FROM room_images WHERE image_id = ? AND room_id = ?");
        $deleteStmt->execute([$imageId, $data['room_id']]);
        
        // Delete physical file if exists
        $filePath = '../' . $image['image_url'];
        if (file_exists($filePath)) {
          unlink($filePath);
        }
        
        $deletedImages[] = $imageId;
      }
    }
  }
  
  // Handle image replacements
  if (isset($_FILES['replace_images']) && is_array($_FILES['replace_images']['name'])) {
    foreach ($_FILES['replace_images']['name'] as $imageId => $name) {
      if ($_FILES['replace_images']['error'][$imageId] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['replace_images']['tmp_name'][$imageId];
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        $newFileName = uniqid() . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . '/' . $newFileName;
        
        // Get old image info before replacing
        $stmt = $pdo->prepare("SELECT image_url FROM room_images WHERE image_id = ? AND room_id = ?");
        $stmt->execute([$imageId, $data['room_id']]);
        $oldImage = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (move_uploaded_file($tmpName, $uploadPath)) {
          // Delete old file if exists
          if ($oldImage && file_exists('../' . $oldImage['image_url'])) {
            unlink('../' . $oldImage['image_url']);
          }
          
          // Update database with new image URL, keep existing metadata
          $imageType = isset($data['image_type'][$imageId]) ? $data['image_type'][$imageId] : 'interior';
          $caption = isset($data['image_caption'][$imageId]) ? $data['image_caption'][$imageId] : '';
          
          $updateStmt = $pdo->prepare("UPDATE room_images SET image_url = ? WHERE image_id = ? AND room_id = ?");
          try {
            $updateStmt->execute(['uploads/room_images/' . $newFileName, $imageId, $data['room_id']]);
            
            $replacedImages[] = [
              'image_id' => $imageId,
              'image_url' => 'uploads/room_images/' . $newFileName
            ];
          } catch (PDOException $e) {
            sendResponse(false, 'Failed to update replaced image in database: ' . $e->getMessage());
          }
        } else {
          sendResponse(false, 'Failed to move replaced file');
        }
      } else {
        sendResponse(false, 'File upload error for replacement: ' . $_FILES['replace_images']['error'][$imageId]);
      }
    }
  }
  
  // Handle new room images
  if (isset($_FILES['room_images']) && is_array($_FILES['room_images']['name'])) {
    foreach ($_FILES['room_images']['name'] as $key => $name) {
      if ($_FILES['room_images']['error'][$key] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['room_images']['tmp_name'][$key];
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        $newFileName = uniqid() . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . '/' . $newFileName;
        
        if (move_uploaded_file($tmpName, $uploadPath)) {
          $imageType = isset($data['image_type'][$key]) ? $data['image_type'][$key] : 'interior';
          $caption = isset($data['image_caption'][$key]) ? $data['image_caption'][$key] : '';
          
          // Insert image into database
          $imageId = uniqid();
          $stmt = $pdo->prepare("INSERT INTO room_images (image_id, room_id, category_id, image_url, thumbnail_url, caption, image_type, is_cover, sort_order, is_active, uploaded_by, created_at) VALUES (?, ?, NULL, ?, NULL, ?, ?, 0, 0, 1, NULL, NOW())");
          try {
            $stmt->execute([$imageId, $data['room_id'], 'uploads/room_images/' . $newFileName, $imageType, $caption]);
            
            $uploadedImages[] = [
              'image_id' => $imageId,
              'image_url' => 'uploads/room_images/' . $newFileName,
              'image_type' => $imageType,
              'caption' => $caption
            ];
          } catch (PDOException $e) {
            sendResponse(false, 'Failed to save image to database: ' . $e->getMessage());
          }
        } else {
          sendResponse(false, 'Failed to move uploaded file');
        }
      } else {
        sendResponse(false, 'File upload error: ' . $_FILES['room_images']['error'][$key]);
      }
    }
  }
  
  $result = [
    'images' => $uploadedImages,
    'deleted' => $deletedImages,
    'replaced' => $replacedImages
  ];
  
  sendResponse(true, 'Image operations completed successfully', $result);
}
