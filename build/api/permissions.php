<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/middleware.php';
applyAuthMiddleware();

// Require super_admin or admin role for permission management
if (!in_array($_SESSION['role_code'] ?? '', ['super_admin', 'admin'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden: Insufficient permissions']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($method) {
        case 'GET':
            requirePermission('users.roles.manage');
            if ($action === 'list' || $action === '') {
                // List all permissions with optional filtering
                $category = $_GET['category'] ?? null;
                $search = $_GET['search'] ?? null;
                
                $sql = "SELECT permission_id, permission_code, permission_label, description, category, is_system, is_active 
                        FROM permissions WHERE 1=1";
                $params = [];
                
                if ($category) {
                    $sql .= " AND category = ?";
                    $params[] = $category;
                }
                if ($search) {
                    $sql .= " AND (permission_code LIKE ? OR permission_label LIKE ? OR description LIKE ?)";
                    $params[] = "%$search%";
                    $params[] = "%$search%";
                    $params[] = "%$search%";
                }
                
                $sql .= " ORDER BY category, permission_label";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Group by category
                $grouped = [];
                foreach ($permissions as $perm) {
                    $cat = $perm['category'];
                    if (!isset($grouped[$cat])) {
                        $grouped[$cat] = [];
                    }
                    $grouped[$cat][] = $perm;
                }
                
                // Get distinct categories
                $categories = $pdo->query("SELECT DISTINCT category FROM permissions ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
                
                echo json_encode(['success' => true, 'data' => ['permissions' => $grouped, 'categories' => $categories]]);
                
            } elseif ($action === 'single' && isset($_GET['id'])) {
                // Get single permission
                $stmt = $pdo->prepare("SELECT * FROM permissions WHERE permission_id = ?");
                $stmt->execute([$_GET['id']]);
                $permission = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($permission) {
                    echo json_encode(['success' => true, 'data' => $permission]);
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Permission not found']);
                }
            } elseif ($action === 'categories') {
                // Get all categories
                $categories = $pdo->query("SELECT DISTINCT category FROM permissions ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
                echo json_encode(['success' => true, 'data' => $categories]);
            }
            break;

        case 'POST':
            requirePermission('users.roles.manage');
            $data = json_decode(file_get_contents('php://input'), true);
            
            if ($action === 'create') {
                // Validate required fields
                if (empty($data['permission_code']) || empty($data['permission_label'])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Permission code and label are required']);
                    exit;
                }
                
                // Check if permission_code already exists
                $stmt = $pdo->prepare("SELECT permission_id FROM permissions WHERE permission_code = ?");
                $stmt->execute([$data['permission_code']]);
                if ($stmt->fetch()) {
                    http_response_code(409);
                    echo json_encode(['success' => false, 'message' => 'Permission code already exists']);
                    exit;
                }
                
                $stmt = $pdo->prepare("INSERT INTO permissions (permission_code, permission_label, description, category, is_system) 
                                       VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $data['permission_code'],
                    $data['permission_label'],
                    $data['description'] ?? null,
                    $data['category'] ?? 'General',
                    $data['is_system'] ?? 0
                ]);
                
                $newId = $pdo->lastInsertId();
                echo json_encode(['success' => true, 'message' => 'Permission created', 'permission_id' => $newId]);
                
            } elseif ($action === 'update') {
                if (empty($data['permission_id'])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Permission ID is required']);
                    exit;
                }
                
                // Check if permission exists and is not a system permission
                $stmt = $pdo->prepare("SELECT is_system FROM permissions WHERE permission_id = ?");
                $stmt->execute([$data['permission_id']]);
                $existing = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$existing) {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Permission not found']);
                    exit;
                }
                
                // Build update query dynamically
                $fields = [];
                $params = [];
                
                if (isset($data['permission_label'])) {
                    $fields[] = "permission_label = ?";
                    $params[] = $data['permission_label'];
                }
                if (isset($data['description'])) {
                    $fields[] = "description = ?";
                    $params[] = $data['description'];
                }
                if (isset($data['category'])) {
                    $fields[] = "category = ?";
                    $params[] = $data['category'];
                }
                if (isset($data['is_active'])) {
                    $fields[] = "is_active = ?";
                    $params[] = $data['is_active'];
                }
                
                if (empty($fields)) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'No fields to update']);
                    exit;
                }
                
                $sql = "UPDATE permissions SET " . implode(', ', $fields) . " WHERE permission_id = ?";
                $params[] = $data['permission_id'];
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                
                echo json_encode(['success' => true, 'message' => 'Permission updated']);
                
            } elseif ($action === 'delete') {
                if (empty($data['permission_id'])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Permission ID is required']);
                    exit;
                }
                
                // Check if it's a system permission
                $stmt = $pdo->prepare("SELECT is_system FROM permissions WHERE permission_id = ?");
                $stmt->execute([$data['permission_id']]);
                $perm = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$perm) {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Permission not found']);
                    exit;
                }
                
                if ($perm['is_system']) {
                    http_response_code(403);
                    echo json_encode(['success' => false, 'message' => 'Cannot delete system permissions']);
                    exit;
                }
                
                $stmt = $pdo->prepare("DELETE FROM permissions WHERE permission_id = ?");
                $stmt->execute([$data['permission_id']]);
                
                echo json_encode(['success' => true, 'message' => 'Permission deleted']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (PDOException $e) {
    error_log("Permissions API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    error_log("Permissions API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
