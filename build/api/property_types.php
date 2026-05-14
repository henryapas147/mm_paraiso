<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyPublicMiddleware();

$action = $_GET['action'] ?? '';

try {
  switch ($action) {
    case 'list':
      // Fetch all active property types
      $stmt = $pdo->query("SELECT property_type_id, type_code, type_name, description, sort_order FROM property_types WHERE is_active = 1 ORDER BY sort_order ASC, type_name ASC");
      echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid action']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
