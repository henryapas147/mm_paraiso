<?php

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

// Check if user is already logged in, redirect to dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  // If user came from pages, redirect back there
  if (isset($_SESSION['auth_referrer']) && $_SESSION['auth_referrer'] === 'pages') {
    unset($_SESSION['auth_referrer']);
    header('Location: ../pages/');
    exit;
  }
  // Redirect to the last visited URL if stored, otherwise dashboard
  $redirectUrl = $_SESSION['last_visited_url'] ?? '../';
  unset($_SESSION['last_visited_url']); // Clear the stored URL
  header('Location: ' . $redirectUrl);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Validate CSRF token
  if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $error = 'Invalid request. Please refresh the page and try again.';
  } else {
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
            // Successful login - reset failed count and update last login
            $updateStmt = $pdo->prepare("
                            UPDATE user_accounts
                            SET failed_login_count = 0, locked_until = NULL,
                                last_login_at = NOW(), last_login_ip = ?
                            WHERE user_id = ?
                        ");
            $updateStmt->execute([$ipAddress, $user['user_id']]);

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
            
            // If user came from pages, redirect back there
            if (isset($_SESSION['auth_referrer']) && $_SESSION['auth_referrer'] === 'pages') {
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
                    $googleAuthUrl = 'https://accounts.google.com/o/oauth2/v2/auth?client_id=684828627007-f0b26teteotc7u87r96fnbl3c8idnosg.apps.googleusercontent.com&redirect_uri=' . urlencode($redirectUri) . '&response_type=code&scope=openid%20profile%20email&access_type=offline';
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
                  <?php if ($error): ?>
                    <div class="mb-4 p-3 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg">
                      <?php echo htmlspecialchars($error); ?>
                    </div>
                  <?php endif; ?>

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
  document.getElementById('loginForm').addEventListener('submit', function(e) {
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