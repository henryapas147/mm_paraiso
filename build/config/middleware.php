<?php
require_once __DIR__ . '/../../config/auth.php';

function applyAuthMiddleware(): void {
  requireAuth();
  if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'PATCH', 'DELETE'])) {
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($_POST['csrf_token'] ?? null);
    // Also check JSON body if not found in POST or header
    if ($token === null && strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
      $jsonInput = json_decode(file_get_contents('php://input'), true);
      $token = $jsonInput['csrf_token'] ?? null;
    }
    if (!validateCsrfToken($token)) {
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'Invalid or missing CSRF token.']);
      exit;
    }
  }
}

function applyPublicMiddleware(): void {
  // DB and session already initialized via db.php (loaded through auth.php above).
  // Public endpoints handle their own per-action CSRF checks where needed.
}

function hasPermission(string $permission): bool {
  global $pdo;

  // Super admin has all permissions
  if (($_SESSION['role_code'] ?? '') === 'super_admin') {
    return true;
  }

  // Get user's role permissions
  $roleCode = $_SESSION['role_code'] ?? '';
  if (empty($roleCode)) {
    return false;
  }

  $stmt = $pdo->prepare("SELECT permissions FROM user_roles WHERE role_code = ?");
  $stmt->execute([$roleCode]);
  $role = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$role) {
    return false;
  }

  $permissions = json_decode($role['permissions'], true) ?? [];

  // Check if role has "all" permission or the specific permission
  return in_array('all', $permissions) || in_array($permission, $permissions);
}

function requirePermission(string $permission): void {
  if (!hasPermission($permission)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden: Insufficient permissions']);
    exit;
  }
}
