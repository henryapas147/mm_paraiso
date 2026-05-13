<?php
require_once __DIR__ . '/../../config/db.php';

// Generate UUID v4
function generateUUID()
{
  $data = random_bytes(16);
  $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // version 4
  $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // variant
  return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

$error = '';
$success = '';

// Check if user is already logged in, redirect to dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  // Redirect to the last visited URL if stored, otherwise dashboard
  $redirectUrl = $_SESSION['last_visited_url'] ?? 'dashboard.php';
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

            // Redirect to dashboard
            header('Location: dashboard.php');
            exit;
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

<?php $assets_path = '../assets';
$page_title = 'Sign In'; ?>
<!DOCTYPE html>
<html>

<head>
  <?php include('../../structure/head.php'); ?>
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
                  <p class="mx-auto mb-6 leading-normal text-sm">Don't have an account? <a href="../pages/sign-up.php" class="font-semibold text-transparent bg-clip-text bg-gradient-to-tl from-blue-500 to-violet-500">Sign up</a></p>
                </div>
              </div>
            </div>
            <div class="absolute top-0 right-0 flex-col justify-center hidden w-6/12 h-full max-w-full px-3 pr-0 my-auto text-center flex-0 lg:flex">
              <div class="relative flex flex-col justify-center h-full bg-cover px-24 m-4 overflow-hidden bg-[url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signin-ill.jpg')] rounded-xl ">
                <span class="absolute top-0 left-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-blue-500 to-violet-500 opacity-60"></span>
                <h4 class="z-20 mt-12 font-bold text-white">"Attention is the new currency"</h4>
                <p class="z-20 text-white ">The more effortless the writing looks, the more effort the writer actually put into the process.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <?php include('../../structure/in_up_footer.php');?>
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

<?php include('../../structure/script.php'); ?>

</html>