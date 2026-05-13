<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

// Require login for all RBAC operations
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Require super_admin or admin role for RBAC management
if (!in_array($_SESSION['role_code'] ?? '', ['super_admin', 'admin'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden: Insufficient permissions']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            // Get all roles with permissions
            $stmt = $pdo->query("
                SELECT role_id, role_code, role_label, permissions, is_system_role, is_active
                FROM user_roles
                ORDER BY role_id ASC
            ");
            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Decode JSON permissions for each role
            foreach ($roles as &$role) {
                $role['permissions'] = json_decode($role['permissions'], true) ?? [];
            }

            echo json_encode(['success' => true, 'data' => $roles]);
            break;

        case 'PUT':
            // Update role permissions
            $input = json_decode(file_get_contents('php://input'), true);

            if (!isset($input['role_code']) || !isset($input['permissions'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                exit;
            }

            $roleCode = $input['role_code'];
            $permissions = $input['permissions'];

            // Check if role exists
            $stmt = $pdo->prepare("SELECT role_id, is_system_role FROM user_roles WHERE role_code = ?");
            $stmt->execute([$roleCode]);
            $role = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$role) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Role not found']);
                exit;
            }

            // Prevent modifying system roles (optional - remove if you want to allow it)
            // if ($role['is_system_role'] && $_SESSION['role_code'] !== 'super_admin') {
            //     http_response_code(403);
            //     echo json_encode(['success' => false, 'message' => 'Cannot modify system roles']);
            //     exit;
            // }

            // Update permissions
            $stmt = $pdo->prepare("
                UPDATE user_roles 
                SET permissions = ?,
                    updated_at = CURRENT_TIMESTAMP
                WHERE role_code = ?
            ");
            $stmt->execute([json_encode($permissions), $roleCode]);

            echo json_encode(['success' => true, 'message' => 'Permissions updated successfully']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            break;
    }
} catch (PDOException $e) {
    error_log("RBAC Settings API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
} catch (Exception $e) {
    error_log("RBAC Settings Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
