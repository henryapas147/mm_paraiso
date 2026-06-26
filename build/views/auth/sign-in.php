<?php

session_start();

require_once __DIR__ . '/../../../vendor/autoload.php';
use OTPHP\TOTP;

// Track referrer if user came from pages directory
if (!isset($_SESSION['auth_referrer']) && isset($_SERVER['HTTP_REFERER'])) {
    $referrer = $_SERVER['HTTP_REFERER'];
    if (strpos($referrer, '/pages/') !== false || strpos($referrer, '/../pages') !== false) {
        $_SESSION['auth_referrer'] = 'pages';
    }
}

// Generate UUID v4
if (!function_exists('generateUUID')) {
function generateUUID()
{
  $data = random_bytes(16);
  $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // version 4
  $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // variant
  return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
}

$error = '';
$success = '';

if (isset($_GET['message'])) {
  $success = match($_GET['message']) {
    'registration_success' => 'Account created! Please sign in.',
    'account_activated'    => 'Your account has been activated. Please sign in.',
    'account_exists'       => 'An account with this email already exists. Please sign in.',
    default                => '',
  };
}

// Check if user is already logged in, redirect to dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  if (isset($_SESSION['last_visited_url'])) {
    $redirectUrl = $_SESSION['last_visited_url'];
    unset($_SESSION['last_visited_url']);
  } elseif (isset($_SESSION['auth_referrer']) && $_SESSION['auth_referrer'] === 'pages') {
    unset($_SESSION['auth_referrer']);
    $redirectUrl = '../pages/';
  } else {
    $redirectUrl = '../';
  }
  header('Location: ' . $redirectUrl);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Validate CSRF token
  if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $error = 'Invalid request. Please refresh the page and try again.';
  } else {
    // Handle 2FA verification step
    if (isset($_POST['step']) && $_POST['step'] === '2fa') {
      $code = $_POST['2fa_code'] ?? '';
      $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

      if (empty($code)) {
        $error = 'Please enter the 6-digit code from your authenticator app.';
      } elseif (!isset($_SESSION['pending_login_user_id'])) {
        $error = 'Session expired. Please sign in again.';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
      } else {
        // Verify 2FA code using the 2fa API
        $userId = $_SESSION['pending_login_user_id'];

        $stmt = $pdo->prepare("SELECT secret, backup_codes FROM user_2fa WHERE user_id = ? AND enabled = 1");
        $stmt->execute([$userId]);
        $twoFactorData = $stmt->fetch();

        if (!$twoFactorData) {
          $error = '2FA data not found. Please sign in again.';
          unset($_SESSION['pending_login_user_id']);
          header('Location: ' . $_SERVER['PHP_SELF']);
          exit;
        }

        // Check backup codes first
        $backupCodes = json_decode($twoFactorData['backup_codes'] ?? '[]', true);
        if (in_array($code, $backupCodes)) {
          // Remove used backup code
          $backupCodes = array_diff($backupCodes, [$code]);
          $stmt = $pdo->prepare("UPDATE user_2fa SET backup_codes = ? WHERE user_id = ?");
          $stmt->execute([json_encode(array_values($backupCodes)), $userId]);
          $codeValid = true;
        } else {
          // Verify TOTP code (window=2 allows ±60s drift for clock differences)
          $totp = TOTP::create($twoFactorData['secret']);
          $codeValid = $totp->verify($code, null, 2);
        }

        if ($codeValid) {
          // 2FA verified - complete login
          $userId = $_SESSION['pending_login_user_id'];
          $email = $_SESSION['pending_login_email'];
          $roleCode = $_SESSION['pending_login_role_code'];
          $roleLabel = $_SESSION['pending_login_role_label'];
          $remember = $_SESSION['pending_login_remember'];
          $ipAddress = $_SESSION['pending_login_ip'];

          // Clear pending login data
          unset($_SESSION['pending_login_user_id']);
          unset($_SESSION['pending_login_email']);
          unset($_SESSION['pending_login_role_code']);
          unset($_SESSION['pending_login_role_label']);
          unset($_SESSION['pending_login_remember']);
          unset($_SESSION['pending_login_ip']);

          // Regenerate session ID
          session_regenerate_id(true);

          // Set session variables
          $_SESSION['user_id'] = $userId;
          $_SESSION['email'] = $email;
          $_SESSION['role_code'] = $roleCode;
          $_SESSION['role_label'] = $roleLabel;
          $_SESSION['logged_in'] = true;
          $_SESSION['last_activity'] = time();
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

          // Store session in database
          $sessionId = generateUUID();
          $sessionToken = bin2hex(random_bytes(32));
          $sessionTokenHash = hash('sha256', $sessionToken);
          $sessionExpiresAt = date('Y-m-d H:i:s', strtotime('+2 hours'));

          $sessionStmt = $pdo->prepare("
            INSERT INTO user_sessions (session_id, user_id, token_hash, device_type, ip_address, expires_at, is_active)
            VALUES (?, ?, ?, 'web', ?, ?, 1)
          ");
          $sessionStmt->execute([$sessionId, $userId, $sessionTokenHash, $ipAddress, $sessionExpiresAt]);
          $_SESSION['session_id'] = $sessionId;

          // Set remember me cookie if checked
          if ($remember) {
            $token = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $token);
            $rememberExpiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));

            $rememberStmt = $pdo->prepare("
              INSERT INTO user_sessions (session_id, user_id, token_hash, device_type, ip_address, expires_at, is_active)
              VALUES (?, ?, ?, 'web', ?, ?, 1)
            ");
            $rememberStmt->execute([generateUUID(), $userId, $tokenHash, $ipAddress, $rememberExpiresAt]);

            setcookie('remember_token', $token, strtotime('+30 days'), '/', '', true, true);
          }

          // Redirect based on role
          if (isset($_SESSION['last_visited_url'])) {
            $redirectUrl = $_SESSION['last_visited_url'];
            unset($_SESSION['last_visited_url']);
          } elseif (isset($_SESSION['auth_referrer']) && $_SESSION['auth_referrer'] === 'pages') {
            unset($_SESSION['auth_referrer']);
            $redirectUrl = './customer/dashboard';
          } else {
            $redirectUrl = ($roleCode === 'customer') ? './customer/dashboard' : './';
          }

          header('Location: ' . $redirectUrl);
          exit;
        } else {
          $error = 'Invalid verification code. Please try again.';
        }
      }
    } else {
      // Regular login flow
      $email = trim($_POST['email'] ?? '');
      $password = $_POST['password'] ?? '';
      $remember = isset($_POST['remember']);
      $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    if (empty($email) || empty($password)) {
      $error = 'Please enter both email and password.';
    } else {
      // Rate limiting check - prevent brute force attacks
      try {
        $rateLimitStmt = $pdo->prepare("
                    SELECT COUNT(*) as attempt_count, 
                           MAX(created_at) as last_attempt
                    FROM login_attempts 
                    WHERE ip_address = ? 
                    AND created_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)
                ");
        $rateLimitStmt->execute([$ipAddress]);
        $rateLimit = $rateLimitStmt->fetch();

        if ($rateLimit && $rateLimit['attempt_count'] >= 10) {
          $error = 'Too many login attempts. Please wait 15 minutes before trying again.';
        } else {
          // Log this login attempt
          $logStmt = $pdo->prepare("
                        INSERT INTO login_attempts (email, ip_address, user_agent, status, created_at)
                        VALUES (?, ?, ?, 'failed', NOW())
                    ");
          $logStmt->execute([$email, $ipAddress, $_SERVER['HTTP_USER_AGENT'] ?? 'unknown']);
        }
      } catch (PDOException $e) {
        error_log("Rate limiting error: " . $e->getMessage());
        // Continue with login even if rate limiting fails
      }

      if (!$error) {
        try {
          // Check if user exists and get account info with role
          $stmt = $pdo->prepare("
                        SELECT ua.user_id, ua.email, ua.password_hash, ua.account_status,
                               ua.failed_login_count, ua.locked_until, ua.is_email_verified,
                               ur.role_code, ur.role_label
                        FROM user_accounts ua
                        LEFT JOIN user_roles ur ON ua.role_id = ur.role_id
                        WHERE ua.email = ? AND ua.deleted_at IS NULL
                    ");
          $stmt->execute([$email]);
          $user = $stmt->fetch();

          if (!$user) {
            $error = 'Invalid email or password.';
          } elseif ($user['account_status'] === 'suspended') {
            $error = 'Your account has been suspended. Please contact administrator.';
          } elseif ($user['account_status'] === 'inactive') {
            $error = 'Your account is inactive. Please contact administrator.';
          } elseif ($user['account_status'] === 'pending_verification') {
            $error = 'Please verify your email address before signing in.';
          } elseif (!$user['is_email_verified']) {
            $error = 'Please verify your email address before signing in.';
          } elseif ($user['locked_until'] && new DateTime($user['locked_until']) > new DateTime()) {
            $lockedTime = new DateTime($user['locked_until']);
            $currentTime = new DateTime();
            $lockedMinutes = ceil(($lockedTime->getTimestamp() - $currentTime->getTimestamp()) / 60);
            $error = "Account locked due to too many failed attempts. Please try again in {$lockedMinutes} minutes.";
          } elseif (!password_verify($password, $user['password_hash'])) {
            // Increment failed login count
            $failedCount = $user['failed_login_count'] + 1;
            $lockUntil = null;

            // Lock account after 5 failed attempts for 30 minutes
            if ($failedCount >= 5) {
              $lockUntil = date('Y-m-d H:i:s', strtotime('+30 minutes'));
            }

            $updateStmt = $pdo->prepare("
                            UPDATE user_accounts
                            SET failed_login_count = ?, locked_until = ?
                            WHERE user_id = ?
                        ");
            $updateStmt->execute([$failedCount, $lockUntil, $user['user_id']]);

            if ($lockUntil) {
              $error = 'Too many failed login attempts. Account locked for 30 minutes.';
            } else {
              $attemptsLeft = 5 - $failedCount;
              $error = "Invalid email or password. {$attemptsLeft} attempts remaining.";
            }
          } else {
            // Successful password verification - check if 2FA is enabled
            $updateStmt = $pdo->prepare("
                            UPDATE user_accounts
                            SET failed_login_count = 0, locked_until = NULL,
                                last_login_at = NOW(), last_login_ip = ?
                            WHERE user_id = ?
                        ");
            $updateStmt->execute([$ipAddress, $user['user_id']]);

            // Check if 2FA is enabled for this user
            $stmt2fa = $pdo->prepare("SELECT enabled FROM user_2fa WHERE user_id = ?");
            $stmt2fa->execute([$user['user_id']]);
            $twoFactor = $stmt2fa->fetch();

            if ($twoFactor && $twoFactor['enabled'] == 1) {
              // 2FA is enabled - store temp user data and show 2FA form
              $_SESSION['pending_login_user_id'] = $user['user_id'];
              $_SESSION['pending_login_email'] = $user['email'];
              $_SESSION['pending_login_role_code'] = $user['role_code'];
              $_SESSION['pending_login_role_label'] = $user['role_label'];
              $_SESSION['pending_login_remember'] = $remember;
              $_SESSION['pending_login_ip'] = $ipAddress;
              header('Location: ' . $_SERVER['PHP_SELF'] . '?step=2fa');
              exit;
            }

            // 2FA not enabled - proceed with normal login
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role_code'] = $user['role_code'];
            $_SESSION['role_label'] = $user['role_label'];
            $_SESSION['logged_in'] = true;
            $_SESSION['last_activity'] = time();
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Regenerate CSRF token after login

            // Always store session in database for tracking
            $sessionId = generateUUID();
            $sessionToken = bin2hex(random_bytes(32));
            $sessionTokenHash = hash('sha256', $sessionToken);
            $sessionExpiresAt = date('Y-m-d H:i:s', strtotime('+2 hours'));

            $sessionStmt = $pdo->prepare("
                            INSERT INTO user_sessions (session_id, user_id, token_hash, device_type, ip_address, expires_at, is_active)
                            VALUES (?, ?, ?, 'web', ?, ?, 1)
                        ");
            $sessionStmt->execute([$sessionId, $user['user_id'], $sessionTokenHash, $ipAddress, $sessionExpiresAt]);

            // Store the session ID for validation
            $_SESSION['session_id'] = $sessionId;

            // Set remember me cookie if checked (30 days)
            if ($remember) {
              $token = bin2hex(random_bytes(32));
              $tokenHash = hash('sha256', $token);
              $rememberExpiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));

              $rememberStmt = $pdo->prepare("
                                INSERT INTO user_sessions (session_id, user_id, token_hash, device_type, ip_address, expires_at, is_active)
                                VALUES (?, ?, ?, 'web', ?, ?, 1)
                            ");
              $rememberStmt->execute([generateUUID(), $user['user_id'], $tokenHash, $ipAddress, $rememberExpiresAt]);

              $secureCookie = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
              setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', $secureCookie, true);
            }

            // Redirect based on user role and referrer
            $redirectUrl = './';

            // If a specific return URL was stored (e.g. from schedule-visit auth guard), use it
            if (isset($_SESSION['last_visited_url'])) {
                $redirectUrl = $_SESSION['last_visited_url'];
                unset($_SESSION['last_visited_url']);
            // If user came from pages, redirect back there
            } elseif (isset($_SESSION['auth_referrer']) && $_SESSION['auth_referrer'] === 'pages') {
                $redirectUrl = '../pages/';
                unset($_SESSION['auth_referrer']);
            } else {
                switch ($user['role_code']) {
                  case 'customer':
                    $redirectUrl = './customer/dashboard';
                    break;
                  case 'api_client':
                    // API clients should not access the web dashboard
                    $error = 'API clients cannot access the web dashboard.';
                    break;
              default:
                // Admin and staff roles go to dashboard
                $redirectUrl = './';
                break;
            }
            }

            if (!$error) {
              header('Location: ' . $redirectUrl);
              exit;
            }
          }
        } catch (PDOException $e) {
          error_log("Login error: " . $e->getMessage());
          error_log("Login error details: " . $e->getTraceAsString());
          $error = 'An error occurred. Please try again later. Error: ' . $e->getMessage();
        }
      }
    }
    }
  }
}
?>

<?php $assets_path = 'assets';
$page_title = 'Sign In'; ?>
<!DOCTYPE html>
<html>

<head>
  <?php include(__DIR__ . '/../../../structure/head.php'); ?>
</head>

<body class="m-0 font-sans antialiased font-normal bg-white text-start text-base leading-default text-slate-500">
  <div class="container sticky top-0 z-sticky">
    <div class="flex flex-wrap -mx-3">
      <div class="w-full max-w-full px-3 flex-0">
        <!-- Navbar -->

      </div>
    </div>
  </div>
  <main class="mt-0 transition-all duration-200 ease-in-out">
    <section>
      <div class="relative flex items-center min-h-screen p-0 overflow-hidden bg-center bg-cover">
        <div class="container z-1">
          <div class="flex flex-wrap -mx-3">
            <div class="flex flex-col w-full max-w-full px-3 mx-auto lg:mx-0 shrink-0 md:flex-0 md:w-7/12 lg:w-5/12 xl:w-4/12">
              <div class="relative flex flex-col min-w-0 break-words bg-transparent border-0 shadow-none lg:py4 dark:bg-gray-950 rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0">
                  <h4 class="font-bold">Sign In</h4>
                  <p class="mb-0">Enter your email and password to sign in</p>
                </div>
                <div class="flex flex-wrap px-6 -mx-3">
                  <?php
                  $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                  $host = $_SERVER['HTTP_HOST'];
                  // On Hostinger: /build, on localhost: /admin_booking/build
                  $scriptPath = $_SERVER['PHP_SELF'];
                  if (in_array($host, ['localhost', '127.0.0.1']) || preg_match('/^192\.168\./', $host) || preg_match('/^10\./', $host)) {
                    $basePath = dirname($scriptPath); // /admin_booking/build
                  } else {
                    // Hostinger: remove /admin_booking if present
                    $basePath = strpos($scriptPath, '/admin_booking') === 0 
                        ? str_replace('/admin_booking', '', dirname($scriptPath)) 
                        : dirname($scriptPath); // /build
                  }
                  $redirectUri = $protocol . '://' . $host . $basePath . '/facebook-callback';
                  $fbAppId = trim('1630159231433753');
                  $facebookAuthUrl = 'https://www.facebook.com/v18.0/dialog/oauth?client_id=' . urlencode($fbAppId) . '&redirect_uri=' . urlencode($redirectUri) . '&scope=email,public_profile&state=' . bin2hex(random_bytes(16));
                  ?>
                  <div class="w-3/12 max-w-full px-1 ml-auto flex-0">
                    <a class="inline-block w-full px-5 py-2.5 mb-4 font-bold text-center text-gray-200 uppercase align-middle transition-all bg-transparent border border-gray-200 border-solid rounded-lg shadow-none cursor-pointer hover:-translate-y-px leading-pro text-xs ease-in tracking-tight-rem bg-150 bg-x-25 hover:bg-transparent hover:opacity-75" href="<?php echo htmlspecialchars($facebookAuthUrl); ?>">
                      <svg width="24px" height="32px" viewBox="0 0 64 64" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                          <g transform="translate(3.000000, 3.000000)" fill-rule="nonzero">
                            <circle fill="#3C5A9A" cx="29.5091719" cy="29.4927506" r="29.4882047"></circle>
                            <path d="M39.0974944,9.05587273 L32.5651312,9.05587273 C28.6886088,9.05587273 24.3768224,10.6862851 24.3768224,16.3054653 C24.395747,18.2634019 24.3768224,20.1385313 24.3768224,22.2488655 L19.8922122,22.2488655 L19.8922122,29.3852113 L24.5156022,29.3852113 L24.5156022,49.9295284 L33.0113092,49.9295284 L33.0113092,29.2496356 L38.6187742,29.2496356 L39.1261316,22.2288395 L32.8649196,22.2288395 C32.8649196,22.2288395 32.8789377,19.1056932 32.8649196,18.1987181 C32.8649196,15.9781412 35.1755132,16.1053059 35.3144932,16.1053059 C36.4140178,16.1053059 38.5518876,16.1085101 39.1006986,16.1053059 L39.1006986,9.05587273 L39.0974944,9.05587273 L39.0974944,9.05587273 Z" fill="#FFFFFF"></path>
                          </g>
                        </g>
                      </svg>
                    </a>
                  </div>
                  <div class="w-3/12 max-w-full px-1 mr-auto flex-0">
                    <?php
                    $redirectUri = $protocol . '://' . $host . $basePath . '/google-callback';
                    $googleAuthUrl = 'https://accounts.google.com/o/oauth2/v2/auth?client_id=684828627007-f0b26teteotc7u87r96fnbl3c8idnosg.apps.googleusercontent.com&redirect_uri=' . urlencode($redirectUri) . '&response_type=code&scope=openid%20profile%20email&access_type=offline&state=' . bin2hex(random_bytes(16));
                    ?>
                    <a class="inline-block w-full px-5 py-2.5 mb-4 font-bold text-center text-gray-200 uppercase align-middle transition-all bg-transparent border border-gray-200 border-solid rounded-lg shadow-none cursor-pointer hover:-translate-y-px leading-pro text-xs ease-in tracking-tight-rem bg-150 bg-x-25 hover:bg-transparent hover:opacity-75" href="<?php echo htmlspecialchars($googleAuthUrl); ?>">
                      <svg width="24px" height="32px" viewBox="0 0 64 64" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                          <g transform="translate(3.000000, 2.000000)" fill-rule="nonzero">
                            <path d="M57.8123233,30.1515267 C57.8123233,27.7263183 57.6155321,25.9565533 57.1896408,24.1212666 L29.4960833,24.1212666 L29.4960833,35.0674653 L45.7515771,35.0674653 C45.4239683,37.7877475 43.6542033,41.8844383 39.7213169,44.6372555 L39.6661883,45.0037254 L48.4223791,51.7870338 L49.0290201,51.8475849 C54.6004021,46.7020943 57.8123233,39.1313952 57.8123233,30.1515267" fill="#4285F4"></path>
                            <path d="M29.4960833,58.9921667 C37.4599129,58.9921667 44.1456164,56.3701671 49.0290201,51.8475849 L39.7213169,44.6372555 C37.2305867,46.3742596 33.887622,47.5868638 29.4960833,47.5868638 C21.6960582,47.5868638 15.0758763,42.4415991 12.7159637,35.3297782 L12.3700541,35.3591501 L3.26524241,42.4054492 L3.14617358,42.736447 C7.9965904,52.3717589 17.959737,58.9921667 29.4960833,58.9921667" fill="#34A853"></path>
                            <path d="M12.7159637,35.3297782 C12.0932812,33.4944915 11.7329116,31.5279353 11.7329116,29.4960833 C11.7329116,27.4640054 12.0932812,25.4976752 12.6832029,23.6623884 L12.6667095,23.2715173 L3.44779955,16.1120237 L3.14617358,16.2554937 C1.14708246,20.2539019 0,24.7439491 0,29.4960833 C0,34.2482175 1.14708246,38.7380388 3.14617358,42.736447 L12.7159637,35.3297782" fill="#FBBC05"></path>
                            <path d="M29.4960833,11.4050769 C35.0347044,11.4050769 38.7707997,13.7975244 40.9011602,15.7968415 L49.2255853,7.66898166 C44.1130815,2.91684746 37.4599129,0 29.4960833,0 C17.959737,0 7.9965904,6.62018183 3.14617358,16.2554937 L12.6832029,23.6623884 C15.0758763,16.5505675 21.6960582,11.4050769 29.4960833,11.4050769" fill="#EB4335"></path>
                          </g>
                        </g>
                      </svg>
                    </a>
                  </div>
                </div>
                <div class="flex-auto p-6">
                  <?php if ($success): ?>
                    <div class="mb-4 p-3 text-sm text-green-600 bg-green-50 border border-green-200 rounded-lg">
                      <?php echo htmlspecialchars($success); ?>
                    </div>
                  <?php endif; ?>
                  <?php if ($error): ?>
                    <div class="mb-4 p-3 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg">
                      <?php echo htmlspecialchars($error); ?>
                    </div>
                  <?php endif; ?>

                  <?php if (isset($_GET['step']) && $_GET['step'] === '2fa' && isset($_SESSION['pending_login_user_id'])): ?>
                    <!-- 2FA Verification Form -->
                    <div class="text-center mb-6">
                      <div class="inline-flex items-center justify-center w-16 h-16 mb-4 bg-indigo-100 rounded-full">
                        <i class="ni ni-key-25 text-2xl text-indigo-600"></i>
                      </div>
                      <h4 class="font-bold text-lg mb-2">Two-Factor Authentication</h4>
                      <p class="text-sm text-slate-500">Enter the 6-digit code from your authenticator app</p>

                      <!-- TOTP countdown timer (synced to server time) -->
                      <div class="flex items-center justify-center gap-2 mt-3">
                        <svg id="timerCircle" viewBox="0 0 36 36" class="w-10 h-10 -rotate-90">
                          <circle cx="18" cy="18" r="15.9" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                          <circle id="timerArc" cx="18" cy="18" r="15.9" fill="none" stroke="#6366f1" stroke-width="3"
                            stroke-dasharray="100 100" stroke-linecap="round"/>
                        </svg>
                        <span class="text-sm text-slate-500">Code refreshes in <span id="timerSeconds" class="font-semibold text-indigo-600">30</span>s</span>
                      </div>
                      <p class="text-xs text-slate-400 mt-1">Codes from the previous and next 60 seconds are also accepted.</p>
                    </div>

                    <form role="form" method="POST" action="" id="2faForm">
                      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                      <input type="hidden" name="step" value="2fa">

                      <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-slate-700">Verification Code</label>
                        <input type="text" name="2fa_code" id="2fa_code_input" placeholder="000000" maxlength="6" pattern="[0-9]{6}" required
                          autocomplete="one-time-code"
                          class="w-full px-4 py-3 text-center text-lg tracking-widest border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                          style="letter-spacing: 0.5em;">
                        <p id="codeExpiredWarning" class="hidden mt-2 text-xs text-amber-600 text-center">⚠ Code has refreshed — please enter the new code from your app.</p>
                      </div>

                      <div class="text-center mb-4">
                        <button type="submit" class="w-full px-6 py-3 font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                          Verify & Sign In
                        </button>
                      </div>

                      <div class="text-center">
                        <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="text-sm text-slate-500 hover:text-slate-700">
                          Cancel and return to sign in
                        </a>
                      </div>
                    </form>

                    <script>
                      (function () {
                        const arcEl      = document.getElementById('timerArc');
                        const secEl      = document.getElementById('timerSeconds');
                        const warnEl     = document.getElementById('codeExpiredWarning');
                        const codeInput  = document.getElementById('2fa_code_input');
                        const PERIOD     = 30;
                        // Anchor to PHP server time to avoid browser clock drift
                        const serverNow  = <?php echo time(); ?>;
                        const clientNow  = Math.floor(Date.now() / 1000);
                        const offset     = serverNow - clientNow;

                        function update() {
                          const now       = Math.floor(Date.now() / 1000) + offset;
                          const elapsed   = now % PERIOD;
                          const remaining = PERIOD - elapsed;
                          const pct       = (remaining / PERIOD) * 100;

                          secEl.textContent = remaining;
                          arcEl.setAttribute('stroke-dasharray', pct.toFixed(1) + ' 100');
                          arcEl.setAttribute('stroke', remaining <= 5 ? '#ef4444' : '#6366f1');
                          secEl.style.color = remaining <= 5 ? '#ef4444' : '';

                          // Warn when a new cycle starts while user has typed a code
                          if (elapsed === 0 && codeInput.value.length > 0) {
                            warnEl.classList.remove('hidden');
                          }
                        }

                        update();
                        setInterval(update, 1000);

                        codeInput.addEventListener('input', function () {
                          warnEl.classList.add('hidden');
                        });
                      })();
                    </script>
                  <?php else: ?>

                  <!-- Loading Spinner -->
                  <div id="loadingSpinner" class="hidden text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
                    <p class="mt-3 text-sm text-slate-600">Signing in...</p>
                  </div>

                  <!-- Form Content -->
                  <div id="formContent">
                    <form role="form" method="POST" action="" id="loginForm">
                      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                      <div class="mb-4">
                        <input type="email" name="email" placeholder="Email" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" required />
                      </div>
                      <div class="mb-4">
                        <input type="password" name="password" placeholder="Password" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" required />
                      </div>
                      <div class="flex items-center pl-12 mb-0.5 text-left min-h-6">
                        <input id="rememberMe" name="remember" class="mt-0.5 rounded-10 duration-250 ease-in-out after:rounded-circle after:shadow-2xl after:duration-250 checked:after:translate-x-5.3 h-5 relative float-left -ml-12 w-10 cursor-pointer appearance-none border border-solid border-gray-200 bg-zinc-700/10 bg-none bg-contain bg-left bg-no-repeat align-top transition-all after:absolute after:top-px after:h-4 after:w-4 after:translate-x-px after:bg-white after:content-[''] checked:border-blue-500/95 checked:bg-blue-500/95 checked:bg-none checked:bg-right" type="checkbox" value="1" />
                        <label class="ml-2 font-normal cursor-pointer select-none text-sm text-slate-700" for="rememberMe">Remember me</label>
                      </div>
                      <div class="text-center">
                        <button type="submit" class="inline-block w-full px-16 py-3.5 mt-6 mb-0 font-bold leading-normal text-center text-white align-middle transition-all bg-blue-500 border-0 rounded-lg cursor-pointer hover:-translate-y-px active:opacity-85 hover:shadow-xs text-sm ease-in tracking-tight-rem shadow-md bg-150 bg-x-25">Sign in</button>
                      </div>
                    </form>
                  </div>
                  <?php endif; ?>
                </div>
                <div class="border-black/12.5 rounded-b-2xl border-t-0 border-solid p-6 text-center pt-0 px-1 sm:px-6">
                  <p class="mx-auto mb-6 leading-normal text-sm">Don't have an account? <a href="sign-up" class="font-semibold text-transparent bg-clip-text bg-gradient-to-tl from-blue-500 to-violet-500">Sign up</a></p>
                </div>
              </div>
            </div>
            <div class="absolute top-0 right-0 flex-col justify-center hidden w-6/12 h-full max-w-full px-3 pr-0 my-auto text-center flex-0 lg:flex">
              <div class="relative flex flex-col justify-center h-full bg-cover px-24 m-4 overflow-hidden rounded-xl" style="background-image: url('<?php echo $assets_path; ?>/img/paraiso.jpg'); background-size: cover; background-position: center;">
                <span class="absolute top-0 left-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-blue-500 to-violet-500 opacity-60"></span>
                <h4 class="z-20 mt-12 font-bold text-white">Your Paradise Awaits</h4>
                <p class="z-20 text-white">Sign in and start planning your perfect stay at M&M Paraiso — where every moment feels like a getaway.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <?php include('../structure/in_up_footer.php');?>
</body>

<script>
  const loginForm = document.getElementById('loginForm');
  if (loginForm) loginForm.addEventListener('submit', function(e) {
    // Show loading spinner
    document.getElementById('loadingSpinner').classList.remove('hidden');
    // Hide form content
    document.getElementById('formContent').classList.add('hidden');
    // Disable button to prevent double submission
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;
  });
</script>

<?php include('../structure/script.php'); ?>

</html>