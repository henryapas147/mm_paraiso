<?php
require_once __DIR__ . '/db.php';

// Session validation middleware for protected pages
function requireAuth(): void {
  global $pdo;

  // Detect API/JSON request to return 401 JSON instead of HTML redirect
  $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
  $isApi = (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false)
        || (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest')
        || (strpos($scriptName, '/api/') !== false)
        || preg_match('#/api[^/]*/|/api\.php#', $scriptName);

  // Absolute sign-in base path — works regardless of which file calls requireAuth()
  $signInBase = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

  $deny = function(string $error, string $msg) use ($isApi, $signInBase): void {
    if ($isApi) {
      http_response_code(401);
      echo json_encode(['success' => false, 'message' => $msg, 'redirect' => 'sign-in']);
      exit;
    }
    $url = $_SERVER['REQUEST_URI'] ?? '';
    $_SESSION = [];
    session_destroy();
    session_start();
    // Never store API endpoint URLs — only store navigable page URLs
    if ($url && strpos($url, '/api/') === false) {
      $_SESSION['last_visited_url'] = $url;
    }
    header("Location: {$signInBase}/sign-in?error={$error}");
    exit;
  };

  // ── 1. Check logged_in flag ──────────────────────────────────────────────
  if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Check for remember-me token
    if (isset($_COOKIE['remember_token'])) {
      try {
        $tokenHash = hash('sha256', $_COOKIE['remember_token']);
        $stmt = $pdo->prepare("
          SELECT us.session_id, ua.user_id, ua.email, ua.account_status, ua.is_email_verified,
                 ur.role_code, ur.role_label
          FROM user_sessions us
          JOIN user_accounts ua ON us.user_id = ua.user_id
          LEFT JOIN user_roles ur ON ua.role_id = ur.role_id
          WHERE us.token_hash = ?
          AND us.is_active = 1
          AND ua.deleted_at IS NULL
        ");
        $stmt->execute([$tokenHash]);
        $session = $stmt->fetch();

        if ($session && strtotime($session['expires_at'] ?? '0') > time()) {
          if ($session['account_status'] === 'suspended') {
            setcookie('remember_token', '', time() - 42000, '/', '', true, true);
            $deny('suspended', 'Your account has been suspended.');
          }
          if ($session['account_status'] === 'inactive') {
            setcookie('remember_token', '', time() - 42000, '/', '', true, true);
            $deny('inactive', 'Your account is inactive.');
          }

          session_regenerate_id(true);
          $_SESSION['user_id']     = $session['user_id'];
          $_SESSION['email']       = $session['email'];
          $_SESSION['role_code']   = $session['role_code'];
          $_SESSION['role_label']  = $session['role_label'];
          $_SESSION['logged_in']   = true;
          $_SESSION['last_activity'] = time();
          $_SESSION['csrf_token']  = bin2hex(random_bytes(32));
          $_SESSION['session_id']  = $session['session_id']; // preserve for DB validation
          return;
        }
      } catch (PDOException $e) {
        error_log("Remember me validation error: " . $e->getMessage());
      }
    }

    $deny('unauthorized', 'Please log in to continue.');
  }

  // ── 2. Session timeout (30 minutes of inactivity) ───────────────────────
  if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 1800) {
    $deny('timeout', 'Your session has expired. Please log in again.');
  }

  $_SESSION['last_activity'] = time();

  // ── 3. DB session validation (PHP-side expiry — avoids timezone mismatch) ─
  $sessionId = $_SESSION['session_id'] ?? null;
  if ($sessionId !== null) {
    try {
      $sessionStmt = $pdo->prepare("
        SELECT us.expires_at, ua.account_status, ua.is_email_verified
        FROM user_sessions us
        JOIN user_accounts ua ON us.user_id = ua.user_id
        WHERE us.session_id = ?
        AND us.is_active = 1
      ");
      $sessionStmt->execute([$sessionId]);
      $dbSession = $sessionStmt->fetch();

      // PHP-side expiry check avoids MySQL vs PHP timezone mismatch
      if (!$dbSession || strtotime($dbSession['expires_at']) < time()) {
        $deny('invalid_session', 'Your session is invalid or has expired. Please log in again.');
      }

      if ($dbSession['account_status'] === 'suspended') {
        $deny('suspended', 'Your account has been suspended.');
      }
      if ($dbSession['account_status'] === 'inactive') {
        $deny('inactive', 'Your account is inactive.');
      }
    } catch (PDOException $e) {
      error_log("Session validation error: " . $e->getMessage());
      // Continue — don't kick user out on transient DB errors
    }
  }
}

// Role-based access control
function requireRole($allowedRoles) {
  requireAuth();
  
  if (!isset($_SESSION['role_code'])) {
    header('Location: build/sign-in?error=no_role');
    exit;
  }

  if (!in_array($_SESSION['role_code'], (array)$allowedRoles)) {
    header('Location: build/?error=unauthorized');
    exit;
  }
}

// Validate CSRF token for state-changing requests
function validateCsrfToken($token = null) {
  $provided = $token
    ?? ($_POST['csrf_token'] ?? null)
    ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
  if (!$provided || empty($_SESSION['csrf_token'])) return false;
  return hash_equals($_SESSION['csrf_token'], $provided);
}

// Check if user has specific role
function hasRole($role) {
  return isset($_SESSION['role_code']) && $_SESSION['role_code'] === $role;
}

// Get current user info
function getCurrentUser() {
  return [
    'user_id' => $_SESSION['user_id'] ?? null,
    'email' => $_SESSION['email'] ?? null,
    'role_code' => $_SESSION['role_code'] ?? null,
    'role_label' => $_SESSION['role_label'] ?? null,
    'logged_in' => $_SESSION['logged_in'] ?? false
  ];
}

// Log successful login for audit
function logSuccessfulLogin($userId, $ipAddress) {
  global $pdo;
  try {
    $stmt = $pdo->prepare("
      INSERT INTO login_attempts (email, ip_address, user_agent, status, created_at)
      SELECT email, ?, ?, 'success', NOW()
      FROM user_accounts
      WHERE user_id = ?
    ");
    $stmt->execute([$ipAddress, $_SERVER['HTTP_USER_AGENT'] ?? 'unknown', $userId]);
  } catch (PDOException $e) {
    error_log("Failed to log successful login: " . $e->getMessage());
  }
}
?>
