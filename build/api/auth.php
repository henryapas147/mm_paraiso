<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
require_once '../../vendor/autoload.php';
applyPublicMiddleware();

// Build a dynamic base URL that works on both localhost and production
function getBaseUrl() {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $scriptPath = $_SERVER['PHP_SELF']; // e.g. /admin_booking/build/api/auth.php
    // Go up two levels from /api/ to get the /build/ root
    $buildPath = dirname(dirname($scriptPath)); // e.g. /admin_booking/build
    return $protocol . '://' . $host . $buildPath;
}

use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'POST':
      $input = json_decode(file_get_contents('php://input'), true) ?? [];

      if ($action === 'register') {
        // Validate CSRF token
        if (!isset($input['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $input['csrf_token'])) {
          echo json_encode(['success' => false, 'message' => 'Invalid request. Please refresh the page and try again.']);
          exit;
        }

        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $agreeTerms = $input['agree_terms'] ?? false;

        // Basic validation
        if (empty($name) || empty($email) || empty($password)) {
          echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
          exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
          exit;
        }
        if (strlen($password) < 8) {
          echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long.']);
          exit;
        }
        if (!$agreeTerms) {
          echo json_encode(['success' => false, 'message' => 'You must agree to the Terms and Conditions.']);
          exit;
        }

        // Check if email already exists
        $checkEmail = $pdo->prepare("
          SELECT COUNT(*) as count
          FROM user_accounts
          WHERE email = ? AND deleted_at IS NULL
        ");
        $checkEmail->execute([$email]);
        $emailExists = $checkEmail->fetch();

        if ($emailExists && $emailExists['count'] > 0) {
          echo json_encode(['success' => false, 'message' => 'An account with this email already exists.']);
          exit;
        }

        // Generate verification token
        $verifyToken = bin2hex(random_bytes(32));
        $verifyTokenHash = hash('sha256', $verifyToken);
        $verifyExpiry = date('Y-m-d H:i:s', strtotime('+24 hours'));

        // Generate UUID v4
        if (!function_exists('generateUUID')) {
          function generateUUID() {
            $data = random_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
          }
        }

        // Create user account
        $userId = generateUUID();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // First, get the customer role_id
        $roleStmt = $pdo->prepare("SELECT role_id FROM user_roles WHERE role_code = 'customer' LIMIT 1");
        $roleStmt->execute();
        $role = $roleStmt->fetch();

        if (!$role) {
          echo json_encode(['success' => false, 'message' => 'System error: Customer role not found. Please contact support.']);
          exit;
        }

        $stmt = $pdo->prepare("
          INSERT INTO user_accounts (user_id, role_id, email, password_hash, account_status, is_email_verified, email_verify_token, email_verify_expiry)
          VALUES (?, ?, ?, ?, 'pending_verification', 0, ?, ?)
        ");
        $stmt->execute([$userId, $role['role_id'], $email, $passwordHash, $verifyTokenHash, $verifyExpiry]);

        // Create user profile
        $profileId = generateUUID();
        $nameParts = explode(' ', $name, 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        $profileStmt = $pdo->prepare("
          INSERT INTO user_profiles (profile_id, user_id, first_name, last_name)
          VALUES (?, ?, ?, ?)
        ");
        $profileStmt->execute([$profileId, $userId, $firstName, $lastName]);

        // Send verification email using Gmail API with OAuth 2.0
        $verifyUrl = getBaseUrl() . "/api/auth.php?action=verify&token=$verifyToken";

        try {
          $credentials = json_decode(file_get_contents('../../config/gmail-credentials.json'), true);

          $client = new Client();
          $client->setApplicationName('Resort Booking System');
          $client->setClientId($credentials['web']['client_id']);
          $client->setClientSecret($credentials['web']['client_secret']);
          $client->setScopes([Gmail::GMAIL_SEND]);
          $client->setAccessType('offline');
          $client->setPrompt('consent');

          // Use refresh token to get access token
          $client->fetchAccessTokenWithRefreshToken($credentials['refresh_token']);

          $service = new Gmail($client);

          $subject = 'Verify Your Email Address';
          $body = "
            <html>
            <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
              <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h2 style='color: #333;'>Welcome to Resort Booking System!</h2>
                <p>Hi $firstName,</p>
                <p>Thank you for registering. Please verify your email address by clicking the button below:</p>
                <p>
                  <a href='$verifyUrl' style='background-color: #4CAF50; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;'>Verify Email</a>
                </p>
                <p>Or copy and paste this link into your browser:</p>
                <p style='word-break: break-all; color: #666;'>$verifyUrl</p>
                <p>This link will expire in 24 hours.</p>
                <p style='color: #999; font-size: 12px;'>If you did not create an account, please ignore this email.</p>
              </div>
            </body>
            </html>
          ";

          // Construct RFC 822 formatted message
          $rawMessage = "To: $email\r\n";
          $rawMessage .= "From: {$credentials['email']}\r\n";
          $rawMessage .= "Subject: $subject\r\n";
          $rawMessage .= "Content-Type: text/html; charset=utf-8\r\n\r\n";
          $rawMessage .= $body;

          $message = new Message();
          $message->setRaw(base64_encode($rawMessage));

          $service->users_messages->send('me', $message);

          echo json_encode([
            'success' => true,
            'message' => 'Account created successfully! Please check your email to verify your account.'
          ]);
        } catch (Exception $e) {
          // Email failed but account was created - log this
          error_log("Failed to send verification email to: $email");
          error_log("Error message: " . $e->getMessage());
          error_log("Error trace: " . $e->getTraceAsString());
          echo json_encode([
            'success' => true,
            'message' => 'Account created successfully! (Email sending failed: ' . $e->getMessage() . ')',
            'verify_token' => $verifyToken
          ]);
        }
      }
      break;

    case 'GET':
      if ($action === 'google-callback' && isset($_GET['code'])) {
        // Google OAuth callback for Sign-In
        $code = $_GET['code'];
        $state = $_GET['state'] ?? null;

        try {
          $credentials = json_decode(file_get_contents('../../config/gmail-credentials.json'), true);

          $client = new Client();
          $client->setApplicationName('Resort Booking System');
          $client->setClientId($credentials['web']['client_id']);
          $client->setClientSecret($credentials['web']['client_secret']);
          $client->setRedirectUri(getBaseUrl() . '/api/auth.php?action=google-callback');
          $client->setScopes(['openid', 'profile', 'email']);
          $client->setAccessType('offline');

          // Exchange code for access token
          $token = $client->fetchAccessTokenWithAuthCode($code);
          $client->setAccessToken($token);

          // Get user info from Google
          $oauth2 = new \Google\Service\Oauth2($client);
          $googleUser = $oauth2->userinfo->get();

          $email = $googleUser->getEmail();
          $name = $googleUser->getName();
          $googleId = $googleUser->getId();

          // Check if user already exists
          $checkEmail = $pdo->prepare("
            SELECT ua.user_id, ua.email, ua.account_status, ur.role_code, ur.role_label
            FROM user_accounts ua
            LEFT JOIN user_roles ur ON ua.role_id = ur.role_id
            WHERE ua.email = ? AND ua.deleted_at IS NULL
          ");
          $checkEmail->execute([$email]);
          $existingUser = $checkEmail->fetch();

          if ($existingUser) {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            // Update account: activate if pending, link google_id if missing, reset login tracking
            $stmt = $pdo->prepare("
              UPDATE user_accounts
              SET account_status    = 'active',
                  is_email_verified = 1,
                  google_id         = COALESCE(google_id, ?),
                  failed_login_count = 0,
                  locked_until      = NULL,
                  last_login_at     = NOW(),
                  last_login_ip     = ?
              WHERE user_id = ?
            ");
            $stmt->execute([$googleId, $ipAddress, $existingUser['user_id']]);

            $loginUserId    = $existingUser['user_id'];
            $loginRoleCode  = $existingUser['role_code'];
            $loginRoleLabel = $existingUser['role_label'];
            $loginEmail     = $existingUser['email'];
          } else {
            // Create new user
            $newUserId = generateUUID();

            // Get customer role
            $roleStmt = $pdo->prepare("SELECT role_id FROM user_roles WHERE role_code = 'customer' LIMIT 1");
            $roleStmt->execute();
            $role = $roleStmt->fetch();

            if (!$role) {
              header('Location: ' . getBaseUrl() . '/sign-up?error=system_error');
              exit;
            }

            // Create user account
            $stmt = $pdo->prepare("
              INSERT INTO user_accounts (user_id, role_id, email, account_status, is_email_verified, google_id)
              VALUES (?, ?, ?, 'active', 1, ?)
            ");
            $stmt->execute([$newUserId, $role['role_id'], $email, $googleId]);

            // Create user profile
            $profileId = generateUUID();
            $nameParts = explode(' ', $name, 2);
            $firstName = $nameParts[0];
            $lastName  = $nameParts[1] ?? '';

            $profileStmt = $pdo->prepare("
              INSERT INTO user_profiles (profile_id, user_id, first_name, last_name)
              VALUES (?, ?, ?, ?)
            ");
            $profileStmt->execute([$profileId, $newUserId, $firstName, $lastName]);

            $loginUserId    = $newUserId;
            $loginRoleCode  = 'customer';
            $loginRoleLabel = 'Customer';
            $loginEmail     = $email;
          }

          // Log the user in — set session variables
          session_regenerate_id(true);
          $_SESSION['user_id']    = $loginUserId;
          $_SESSION['email']      = $loginEmail;
          $_SESSION['role_code']  = $loginRoleCode;
          $_SESSION['role_label'] = $loginRoleLabel;
          $_SESSION['logged_in']  = true;
          $_SESSION['last_activity'] = time();
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

          // Store session in DB
          $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
          $sessionId = generateUUID();
          $sessionTokenHash = hash('sha256', bin2hex(random_bytes(32)));
          $sessionExpiresAt = date('Y-m-d H:i:s', strtotime('+2 hours'));
          $sessionStmt = $pdo->prepare("
            INSERT INTO user_sessions (session_id, user_id, token_hash, device_type, ip_address, expires_at, is_active)
            VALUES (?, ?, ?, 'web', ?, ?, 1)
          ");
          $sessionStmt->execute([$sessionId, $loginUserId, $sessionTokenHash, $ipAddress, $sessionExpiresAt]);
          $_SESSION['session_id'] = $sessionId;

          // Redirect: use session's last_visited_url first, then role-based dashboard
          if (isset($_SESSION['last_visited_url'])) {
            $redirectUrl = $_SESSION['last_visited_url'];
            unset($_SESSION['last_visited_url']);
          } else {
            $redirectUrl = ($loginRoleCode === 'customer')
              ? getBaseUrl() . '/customer/dashboard'
              : getBaseUrl() . '/';
          }
          header('Location: ' . $redirectUrl);
          exit;
        } catch (Exception $e) {
          error_log("Google OAuth error: " . $e->getMessage());
          header('Location: ' . getBaseUrl() . '/sign-up?error=oauth_failed');
          exit;
        }
      } elseif ($action === 'verify' && isset($_GET['token'])) {
        // Redirect to the new email verification controller
        header('Location: ' . getBaseUrl() . '/verify-email?token=' . urlencode($_GET['token']));
        exit;
      }
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
      break;
  }
} catch (PDOException $e) {
  error_log("Auth API error: " . $e->getMessage());
  error_log("Auth API error trace: " . $e->getTraceAsString());
  echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
