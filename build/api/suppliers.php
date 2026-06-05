<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      requirePermission('inventory.view');
      if ($action === 'list') {
        $stmt = $pdo->query("SELECT * FROM suppliers WHERE is_active = 1 ORDER BY company_name ASC");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      } elseif ($action === 'single' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM suppliers WHERE supplier_id = ?");
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
      }
      break;

    case 'POST':
      requirePermission('inventory.create');
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
          mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
          mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
        $stmt = $pdo->prepare("INSERT INTO suppliers (supplier_id, supplier_code, company_name, contact_person, email, phone_primary, phone_secondary, address_line1, address_line2, city, province, tin_number, payment_terms, notes, is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$id, $data['supplier_code'], $data['company_name'], $data['contact_person'] ?? null, $data['email'] ?? null, $data['phone_primary'] ?? null, $data['phone_secondary'] ?? null, $data['address_line1'] ?? null, $data['address_line2'] ?? null, $data['city'] ?? null, $data['province'] ?? null, $data['tin_number'] ?? null, $data['payment_terms'] ?? null, $data['notes'] ?? null, $data['is_active'] ?? 1]);
        echo json_encode(['success' => true, 'message' => 'Supplier created', 'supplier_id' => $id]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE suppliers SET supplier_code=?, company_name=?, contact_person=?, email=?, phone_primary=?, phone_secondary=?, address_line1=?, address_line2=?, city=?, province=?, tin_number=?, payment_terms=?, notes=?, is_active=? WHERE supplier_id=?");
        $stmt->execute([$data['supplier_code'], $data['company_name'], $data['contact_person'] ?? null, $data['email'] ?? null, $data['phone_primary'] ?? null, $data['phone_secondary'] ?? null, $data['address_line1'] ?? null, $data['address_line2'] ?? null, $data['city'] ?? null, $data['province'] ?? null, $data['tin_number'] ?? null, $data['payment_terms'] ?? null, $data['notes'] ?? null, $data['is_active'] ?? 1, $data['supplier_id']]);
        echo json_encode(['success' => true, 'message' => 'Supplier updated']);

      } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("UPDATE suppliers SET is_active=0 WHERE supplier_id=?");
        $stmt->execute([$data['supplier_id']]);
        echo json_encode(['success' => true, 'message' => 'Supplier deleted']);
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
