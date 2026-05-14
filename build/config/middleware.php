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
