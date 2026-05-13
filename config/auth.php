<?php
require_once __DIR__ . '/db.php';

// Session validation middleware for protected pages
function requireAuth() {
  global $pdo;
  
  // Check if user is logged in
  if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Check for remember me token
    if (isset($_COOKIE['remember_token'])) {
      try {
        $tokenHash = hash('sha256', $_COOKIE['remember_token']);
        $stmt = $pdo->prepare("
          SELECT ua.user_id, ua.email, ua.account_status, ua.is_email_verified,
                 ur.role_code, ur.role_label
          FROM user_sessions us
          JOIN user_accounts ua ON us.user_id = ua.user_id
          LEFT JOIN user_roles ur ON ua.role_id = ur.role_id
          WHERE us.token_hash = ?
          AND us.expires_at > NOW()
          AND us.is_active = 1
          AND ua.deleted_at IS NULL
        ");
        $stmt->execute([$tokenHash]);
        $session = $stmt->fetch();

        if ($session) {
          // Validate account status
          if ($session['account_status'] === 'suspended') {
            $_SESSION['last_visited_url'] = $_SERVER['REQUEST_URI'];
            setcookie('remember_token', '', time()-42000, '/', '', true, true);
            header('Location: build/sign-in?error=suspended');
            exit;
          }

          if ($session['account_status'] === 'inactive') {
            $_SESSION['last_visited_url'] = $_SERVER['REQUEST_URI'];
            setcookie('remember_token', '', time()-42000, '/', '', true, true);
            header('Location: build/sign-in?error=inactive');
            exit;
          }

          // Regenerate session
          session_regenerate_id(true);
          
          // Set session variables
          $_SESSION['user_id'] = $session['user_id'];
          $_SESSION['email'] = $session['email'];
          $_SESSION['role_code'] = $session['role_code'];
          $_SESSION['role_label'] = $session['role_label'];
          $_SESSION['logged_in'] = true;
          $_SESSION['last_activity'] = time();
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

          // Note: last_active_at auto-updates in database, no manual update needed

          return; // User is authenticated
        }
      } catch (PDOException $e) {
        error_log("Remember me validation error: " . $e->getMessage());
      }
    }

    // Not authenticated, redirect to login
    // Store the current URL for redirect after successful login
    $_SESSION['last_visited_url'] = $_SERVER['REQUEST_URI'];
    header('Location: sign-in.php?error=unauthorized');
    exit;
  }

  // Check session timeout (30 minutes of inactivity)
  if (isset($_SESSION['last_activity'])) {
    $inactiveTime = time() - $_SESSION['last_activity'];
    if ($inactiveTime > 1800) { // 30 minutes
      $_SESSION['last_visited_url'] = $_SERVER['REQUEST_URI'];
      session_destroy();
      header('Location: sign-in.php?error=timeout');
      exit;
    }
  }

  // Update last activity
  $_SESSION['last_activity'] = time();

  // Validate session in database
  $sessionId = $_SESSION['session_id'] ?? null;
  try {
    $sessionStmt = $pdo->prepare("
      SELECT us.expires_at, ua.account_status, ua.is_email_verified
      FROM user_sessions us
      JOIN user_accounts ua ON us.user_id = ua.user_id
      WHERE us.session_id = ?
      AND us.expires_at > NOW()
      AND us.is_active = 1
    ");
    $sessionStmt->execute([$sessionId]);
    $dbSession = $sessionStmt->fetch();

    if (!$dbSession) {
      $_SESSION['last_visited_url'] = $_SERVER['REQUEST_URI'];
      session_destroy();
      header('Location: sign-in.php?error=invalid_session');
      exit;
    }

    // Validate account status
    if ($dbSession['account_status'] === 'suspended') {
      $_SESSION['last_visited_url'] = $_SERVER['REQUEST_URI'];
      session_destroy();
      header('Location: sign-in.php?error=suspended');
      exit;
    }

    if ($dbSession['account_status'] === 'inactive') {
      $_SESSION['last_visited_url'] = $_SERVER['REQUEST_URI'];
      session_destroy();
      header('Location: sign-in.php?error=inactive');
      exit;
    }
  } catch (PDOException $e) {
    error_log("Session validation error: " . $e->getMessage());
    // Continue even if database check fails
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
