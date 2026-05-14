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
$REQUIRED_FIELDS_CREATE = ['property_name', 'property_code', 'property_type', 'address_line1'];
$REQUIRED_FIELDS_UPDATE = ['property_name', 'property_code', 'property_type'];

/**
 * Extract property data from request with defaults
 */
function extractPropertyData(array $data, array $fields): array {
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

try {
  switch ($method) {
    case 'GET':
      handleGetRequest($action, $pdo);
      break;

    case 'POST':
      handlePostRequest($action, $pdo, $PROPERTY_FIELDS, $REQUIRED_FIELDS_CREATE, $REQUIRED_FIELDS_UPDATE);
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
function handlePostRequest(string $action, PDO $pdo, array $fields, array $requiredCreate, array $requiredUpdate): void {
  $data = json_decode(file_get_contents('php://input'), true);

  if (json_last_error() !== JSON_ERROR_NONE) {
    sendResponse(false, 'Invalid JSON data');
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
 * Handle Create Property
 */
function handleCreate(array $data, PDO $pdo, array $fields, array $required): void {
  // Validate required fields
  $missing = validateRequiredFields($data, $required);
  if (!empty($missing)) {
    sendResponse(false, 'Missing required fields: ' . implode(', ', $missing));
  }

  // Extract and prepare data
  $propertyData = extractPropertyData($data, $fields);

  try {
    $sql = buildInsertSql('properties', $propertyData);
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($propertyData));
    sendResponse(true, 'Property created successfully');
  } catch (PDOException $e) {
    sendResponse(false, 'Database error: ' . $e->getMessage());
  }
}

/**
 * Handle Update Property
 */
function handleUpdate(array $data, PDO $pdo, array $fields, array $required): void {
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
function handleDelete(array $data, PDO $pdo): void {
  if (empty($data['property_id'])) {
    sendResponse(false, 'Property ID required');
  }

  try {
    $pdo->beginTransaction();

    // Delete property images files
    $stmt = $pdo->prepare("SELECT image_url FROM property_images WHERE property_id = ?");
    $stmt->execute([$data['property_id']]);
    $images = $stmt->fetchAll();

    foreach ($images as $img) {
      $image_path = __DIR__ . '/../' . $img['image_url'];
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
