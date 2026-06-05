<?php

/**
 * Properties API - Clean CRUD Implementation
 */

header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// Define all property fields with their default values
$PROPERTY_FIELDS = [
  'property_id' => null,
  'property_name' => null,
  'property_code' => null,
  'property_type' => null,
  'tagline' => null,
  'description' => null,
  'address_line1' => null,
  'address_line2' => null,
  'region_code' => null,
  'province_code' => null,
  'city_municipality_code' => null,
  'barangay_code' => null,
  'postal_code' => null,
  'country' => 'Philippines',
  'latitude' => null,
  'longitude' => null,
  'phone_primary' => null,
  'phone_secondary' => null,
  'email' => null,
  'website_url' => null,
  'facebook_url' => null,
  'check_in_time' => '14:00:00',
  'check_out_time' => '12:00:00',
  'earliest_check_in' => '08:00:00',
  'latest_check_out' => '22:00:00',
  'late_check_out_fee' => 0.00,
  'timezone' => 'Asia/Manila',
  'currency_code' => 'PHP',
  'tax_rate_percent' => 0.00,
  'is_active' => 1,
  'sort_order' => 0
];

// Required fields for validation (CREATE only - UPDATE can have empty fields)
$REQUIRED_FIELDS_CREATE = ['property_name', 'property_code', 'property_type'];
$REQUIRED_FIELDS_UPDATE = ['property_name', 'property_code', 'property_type'];

/**
 * Extract property data from request with defaults
 */
function extractPropertyData(array $data, array $fields): array
{
  $result = [];
  foreach ($fields as $field => $default) {
    $result[$field] = $data[$field] ?? $default;
  }
  return $result;
}

/**
 * Validate required fields
 */
function validateRequiredFields(array $data, array $required): array
{
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
function buildInsertSql(string $table, array $fields): string
{
  $columns = implode(', ', array_keys($fields));
  $placeholders = implode(', ', array_fill(0, count($fields), '?'));
  return "INSERT INTO {$table} ({$columns}, created_at, updated_at) VALUES ({$placeholders}, NOW(), NOW())";
}

/**
 * Build UPDATE SQL from fields
 */
function buildUpdateSql(string $table, array $fields, string $whereColumn): string
{
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
function sendResponse(bool $success, string $message, array $data = []): void
{
  $response = ['success' => $success, 'message' => $message];
  if (!empty($data)) {
    $response['data'] = $data;
  }
  echo json_encode($response);
  exit;
}

try {
  switch ($method) {
    case 'GET':
      handleGetRequest($action, $pdo);
      break;

    case 'POST':
      // Check if this is a multipart form data request (file upload)
      if (isset($_FILES['property_images'])) {
        handlePropertyImageUpload($pdo);
      } else {
        handlePostRequest($action, $pdo, $PROPERTY_FIELDS, $REQUIRED_FIELDS_CREATE, $REQUIRED_FIELDS_UPDATE);
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
function handleGetRequest(string $action, PDO $pdo): void
{
  switch ($action) {
    case 'list':
      requirePermission('properties.view');
      $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
      $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

      // Get total count
      $countStmt = $pdo->query("SELECT COUNT(*) as total FROM properties");
      $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

      // Get paginated data
      $stmt = $pdo->prepare("SELECT property_id, property_name, property_code, property_type, tagline, description, address_line1, address_line2, region_code, province_code, city_municipality_code, barangay_code, city, province, postal_code, country, latitude, longitude, phone_primary, phone_secondary, email, website_url, facebook_url, check_in_time, check_out_time, earliest_check_in, latest_check_out, late_check_out_fee, timezone, currency_code, tax_rate_percent, is_active, sort_order, created_at, updated_at FROM properties ORDER BY property_name ASC LIMIT :limit OFFSET :offset");
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->execute();

      sendResponse(true, 'Properties retrieved', [
        'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
        'total' => $total,
        'limit' => $limit,
        'offset' => $offset
      ]);
      break;

    case 'all_types':
      $stmt = $pdo->query("SELECT DISTINCT property_type FROM properties WHERE property_type IS NOT NULL AND property_type != '' ORDER BY property_type ASC");
      $types = $stmt->fetchAll(PDO::FETCH_COLUMN);
      sendResponse(true, 'Property types retrieved', $types);
      break;

    case 'single':
      requirePermission('properties.view');
      if (!isset($_GET['id'])) {
        sendResponse(false, 'Property ID required');
      }
      $stmt = $pdo->prepare("SELECT * FROM properties WHERE property_id = ?");
      $stmt->execute([$_GET['id']]);
      $property = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$property) {
        sendResponse(false, 'Property not found');
      }
      sendResponse(true, 'Property retrieved', $property);
      break;

    case 'images':
      if (!isset($_GET['id'])) {
        sendResponse(false, 'Property ID required');
      }
      $stmt = $pdo->prepare("SELECT * FROM property_images WHERE property_id = ? ORDER BY sort_order ASC");
      $stmt->execute([$_GET['id']]);
      sendResponse(true, 'Images retrieved', $stmt->fetchAll(PDO::FETCH_ASSOC));
      break;

    default:
      sendResponse(false, 'Invalid action');
  }
}

/**
 * Handle POST requests
 */
function handlePostRequest(string $action, PDO $pdo, array $fields, array $requiredCreate, array $requiredUpdate): void
{
  $data = json_decode(file_get_contents('php://input'), true);

  if (json_last_error() !== JSON_ERROR_NONE) {
    sendResponse(false, 'Invalid JSON data');
  }

  // If action is not in GET parameter, check JSON body
  if (empty($action) && !empty($data['action'])) {
    $action = $data['action'];
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

    case 'delete_image':
      handleDeleteImage($data, $pdo);
      break;

    case 'update_image_type':
      handleUpdateImageType($data, $pdo);
      break;

    default:
      sendResponse(false, 'Invalid action');
  }
}

/**
 * Handle Create Property
 */
function handleCreate(array $data, PDO $pdo, array $fields, array $required): void
{
  requirePermission('properties.create');
  // Validate required fields
  $missing = validateRequiredFields($data, $required);
  if (!empty($missing)) {
    sendResponse(false, 'Missing required fields: ' . implode(', ', $missing));
  }

  // Extract and prepare data
  $propertyData = extractPropertyData($data, $fields);

  // Generate UUID for property_id if not provided
  if (empty($propertyData['property_id'])) {
    $propertyData['property_id'] = sprintf(
      '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff),
      mt_rand(0, 0x0fff) | 0x4000,
      mt_rand(0, 0x3fff) | 0x8000,
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff)
    );
  }

  try {
    $sql = buildInsertSql('properties', $propertyData);
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($propertyData));
    sendResponse(true, 'Property created successfully', ['property_id' => $propertyData['property_id']]);
  } catch (PDOException $e) {
    sendResponse(false, 'Database error: ' . $e->getMessage());
  }
}

/**
 * Handle Update Property
 */
function handleUpdate(array $data, PDO $pdo, array $fields, array $required): void
{
  requirePermission('properties.edit');
  if (empty($data['property_id'])) {
    sendResponse(false, 'Property ID required');
  }

  // Validate required fields
  $missing = validateRequiredFields($data, $required);
  if (!empty($missing)) {
    sendResponse(false, 'Missing required fields: ' . implode(', ', $missing));
  }

  // Extract and prepare data (exclude property_id from SET clause)
  $updateFields = $fields;
  unset($updateFields['property_id']);
  $propertyData = extractPropertyData($data, $updateFields);

  try {
    $sql = buildUpdateSql('properties', $propertyData, 'property_id');
    $stmt = $pdo->prepare($sql);
    // Add property_id as the last parameter for WHERE clause
    $values = array_values($propertyData);
    $values[] = $data['property_id'];
    $stmt->execute($values);
    sendResponse(true, 'Property updated successfully');
  } catch (PDOException $e) {
    sendResponse(false, 'Database error: ' . $e->getMessage());
  }
}

/**
 * Handle Delete Property
 */
function handleDelete(array $data, PDO $pdo): void
{
  requirePermission('properties.delete');
  if (empty($data['property_id'])) {
    sendResponse(false, 'Property ID required');
  }

  try {
    // Check if property has reservations
    $stmt = $pdo->prepare("SELECT COUNT(*) as reservation_count FROM reservations WHERE property_id = ?");
    $stmt->execute([$data['property_id']]);
    $reservationCount = $stmt->fetch(PDO::FETCH_ASSOC)['reservation_count'];

    if ($reservationCount > 0) {
      sendResponse(false, "Cannot delete property because it has {$reservationCount} reservation(s). Please cancel or reassign the reservations first.");
    }

    // Check if property has rooms
    $stmt = $pdo->prepare("SELECT COUNT(*) as room_count FROM rooms WHERE property_id = ?");
    $stmt->execute([$data['property_id']]);
    $roomCount = $stmt->fetch(PDO::FETCH_ASSOC)['room_count'];

    if ($roomCount > 0) {
      sendResponse(false, "Cannot delete property because it has {$roomCount} room(s). Please delete or reassign the rooms first.");
    }

    // Check if property has inventory locations
    $stmt = $pdo->prepare("SELECT COUNT(*) as inventory_count FROM inventory_locations WHERE property_id = ?");
    $stmt->execute([$data['property_id']]);
    $inventoryCount = $stmt->fetch(PDO::FETCH_ASSOC)['inventory_count'];

    if ($inventoryCount > 0) {
      sendResponse(false, "Cannot delete property because it has {$inventoryCount} inventory location(s). Please delete or reassign the inventory locations first.");
    }

    $pdo->beginTransaction();

    // Delete property images files
    $stmt = $pdo->prepare("SELECT image_url FROM property_images WHERE property_id = ?");
    $stmt->execute([$data['property_id']]);
    $images = $stmt->fetchAll();

    foreach ($images as $img) {
      $image_path = __DIR__ . '/../../' . $img['image_url'];
      if (file_exists($image_path)) {
        unlink($image_path);
      }
    }

    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM property_images WHERE property_id = ?");
    $stmt->execute([$data['property_id']]);

    $stmt = $pdo->prepare("DELETE FROM properties WHERE property_id = ?");
    $stmt->execute([$data['property_id']]);

    $pdo->commit();
    sendResponse(true, 'Property deleted successfully');
  } catch (Exception $e) {
    $pdo->rollBack();
    sendResponse(false, 'Error deleting property: ' . $e->getMessage());
  }
}

/**
 * Handle Delete Single Property Image
 */
function handleDeleteImage(array $data, PDO $pdo): void
{
  requirePermission('properties.edit');
  if (empty($data['image_id'])) {
    sendResponse(false, 'Image ID required');
  }

  $stmt = $pdo->prepare("SELECT image_url FROM property_images WHERE image_id = ?");
  $stmt->execute([$data['image_id']]);
  $image = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$image) {
    sendResponse(false, 'Image not found');
  }

  $filePath = __DIR__ . '/../../' . $image['image_url'];
  if (file_exists($filePath)) {
    unlink($filePath);
  }

  try {
    $stmt = $pdo->prepare("DELETE FROM property_images WHERE image_id = ?");
    $stmt->execute([$data['image_id']]);
    sendResponse(true, 'Image deleted successfully');
  } catch (PDOException $e) {
    sendResponse(false, 'DB error deleting image: ' . $e->getMessage());
  }
}

/**
 * Handle Update Image Type
 */
function handleUpdateImageType(array $data, PDO $pdo): void
{
  if (empty($data['image_id'])) {
    sendResponse(false, 'Image ID required');
  }

  $validImageTypes = ['cover', 'gallery', 'facilities', 'amenities', 'map', 'other'];
  if (empty($data['image_type']) || !in_array($data['image_type'], $validImageTypes)) {
    sendResponse(false, 'Invalid image type');
  }

  try {
    $stmt = $pdo->prepare("UPDATE property_images SET image_type = ? WHERE image_id = ?");
    $stmt->execute([$data['image_type'], $data['image_id']]);
    sendResponse(true, 'Image type updated');
  } catch (PDOException $e) {
    sendResponse(false, 'DB error updating image type: ' . $e->getMessage());
  }
}

/**
 * Handle Property Image Upload
 */
function handlePropertyImageUpload(PDO $pdo): void
{
  $data = $_POST;
  $uploadedImages = [];

  if (empty($data['property_id'])) {
    sendResponse(false, 'Property ID is required for image upload');
  }

  $uploadDir = '../../uploads/properties';
  if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
      sendResponse(false, 'Failed to create upload directory');
    }
  }

  if (!isset($_FILES['property_images'])) {
    sendResponse(false, 'No images received');
  }

  // Normalize to array — handles both single and multiple files
  $files = $_FILES['property_images'];
  if (!is_array($files['name'])) {
    $files['name']     = [$files['name']];
    $files['tmp_name'] = [$files['tmp_name']];
    $files['error']    = [$files['error']];
    $files['size']     = [$files['size']];
    $files['type']     = [$files['type']];
  }

  foreach ($files['name'] as $key => $name) {
    if ($files['error'][$key] !== UPLOAD_ERR_OK) {
      sendResponse(false, 'File upload error code: ' . $files['error'][$key]);
    }

    $tmpName   = $files['tmp_name'][$key];
    $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    // Validate extension
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($extension, $allowed)) {
      sendResponse(false, 'Invalid file type: ' . $extension);
    }

    $newFileName = uniqid() . '_' . time() . '.' . $extension;
    $uploadPath  = $uploadDir . '/' . $newFileName;

    if (!move_uploaded_file($tmpName, $uploadPath)) {
      sendResponse(false, 'Failed to move uploaded file');
    }

    $validImageTypes = ['cover', 'gallery', 'facilities', 'amenities', 'map', 'other'];
    $imageType = isset($data['image_type'][$key]) && in_array($data['image_type'][$key], $validImageTypes)
      ? $data['image_type'][$key]
      : 'gallery';
    $caption   = isset($data['image_caption'][$key]) ? $data['image_caption'][$key] : '';
    $sortOrder = $key;

    $imageId = sprintf(
      '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff),
      mt_rand(0, 0x0fff) | 0x4000,
      mt_rand(0, 0x3fff) | 0x8000,
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff)
    );

    try {
      $stmt = $pdo->prepare("
        INSERT INTO property_images
          (image_id, property_id, image_url, thumbnail_url,
           caption, image_type, sort_order, is_active, uploaded_by, created_at)
        VALUES (?, ?, ?, NULL, ?, ?, ?, 1, NULL, NOW())
      ");
      $stmt->execute([
        $imageId,
        $data['property_id'],
        'uploads/properties/' . $newFileName,
        $caption,
        $imageType,
        $sortOrder
      ]);

      $uploadedImages[] = [
        'image_id'   => $imageId,
        'image_url'  => 'uploads/properties/' . $newFileName,
        'image_type' => $imageType,
        'caption'    => $caption
      ];
    } catch (PDOException $e) {
      sendResponse(false, 'DB error saving image: ' . $e->getMessage());
    }
  }

  sendResponse(true, 'Images uploaded successfully', ['images' => $uploadedImages]);
}
