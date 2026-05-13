<?php
/**
 * Facebook OAuth Callback Controller
 * Handles Facebook Sign-In/Sign-Up and creates/updates user accounts
 * FIXED: Uses JavaScript redirect to clear hash fragments from Facebook OAuth
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\Controller as BaseController;

// Database configuration - Auto-detect if online or localhost based on host
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$USE_ONLINE_DB = !in_array($host, ['localhost', '127.0.0.1']) && !preg_match('/^192\.168\./', $host) && !preg_match('/^10\./', $host);

if ($USE_ONLINE_DB) {
    // Online database credentials - Hostinger specific
    $DB_HOST = 'localhost';
    $DB_NAME = 'u843921121_sociallogin';
    $DB_USER = 'u843921121_anthonzcatzqms';
    $DB_PASS = '^M05uc3IuAxf';
    $DB_OPTIONS = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_TIMEOUT            => 5,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci; SET time_zone = '+08:00'"
    ];
} else {
    // Offline/local database credentials
    $DB_HOST = 'localhost';
    $DB_NAME = 'booking';
    $DB_USER = 'root';
    $DB_PASS = '';
    $DB_OPTIONS = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_TIMEOUT            => 5
    ];
}

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        $DB_OPTIONS
    );
} catch (Throwable $e) {
    die("Database connection failed: " . $e->getMessage() . "<br>Using " . ($USE_ONLINE_DB ? "online" : "local") . " database.");
}

// Initialize Controller
$controller = new BaseController();

// Dynamic base path based on current environment (MUST BE BEFORE TRY BLOCK)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'];
$basePath = dirname($_SERVER['SCRIPT_NAME']); // Gets /admin_booking/build or equivalent

// Check if we have an authorization code
if (!isset($_GET['code'])) {
    $controller->viewRaw('auth/facebook-callback-error', [
        'title'       => 'Missing Authorization',
        'message'     => 'No authorization code provided. Please try again.',
        'redirectUrl' => $basePath . '/sign-up'
    ]);
    exit;
}

$code = $_GET['code'];

// ------------------------------------------------------------------
// Resolve the correct upload directory regardless of environment.
// ------------------------------------------------------------------
function resolveUploadDir(): string
{
    // Option 1: DOCUMENT_ROOT/uploads (standard setup)
    $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/\\');
    if ($docRoot && is_dir($docRoot . DIRECTORY_SEPARATOR . 'uploads')) {
        return $docRoot . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'profile_photos' . DIRECTORY_SEPARATOR;
    }

    // Option 2: Uploads outside public_html (one level up from DOCUMENT_ROOT)
    // This is common on Hostinger for security
    if ($docRoot) {
        $parentDir = dirname($docRoot);
        if (is_dir($parentDir . DIRECTORY_SEPARATOR . 'uploads')) {
            return $parentDir . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'profile_photos' . DIRECTORY_SEPARATOR;
        }
    }

    // Option 3: Walk up from __DIR__ (fallback)
    $dir = __DIR__;
    for ($i = 0; $i < 5; $i++) {
        $candidate = $dir . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'profile_photos' . DIRECTORY_SEPARATOR;
        if (is_dir($dir . DIRECTORY_SEPARATOR . 'uploads') || is_writable($dir)) {
            return $candidate;
        }
        $dir = dirname($dir);
    }

    // Last resort: two levels up from __DIR__
    return __DIR__ . '/../../uploads/profile_photos/';
}

try {
    $credentials = json_decode(file_get_contents(__DIR__ . '/../config/facebook-credentials.json'), true);

    // Dynamic redirect URI based on current environment
    $redirectUri = $protocol . '://' . $host . $basePath . '/facebook-callback';

    $appId = $credentials['web']['app_id'];
    $appSecret = $credentials['web']['app_secret'];

    // Exchange code for access token
    $tokenUrl = 'https://graph.facebook.com/v18.0/oauth/access_token';
    $tokenParams = [
        'client_id'     => $appId,
        'client_secret' => $appSecret,
        'redirect_uri'  => $redirectUri,
        'code'          => $code
    ];

    $ch = curl_init($tokenUrl . '?' . http_build_query($tokenParams));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $tokenResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$tokenResponse) {
        throw new Exception("Failed to exchange code for access token. HTTP $httpCode");
    }

    $tokenData = json_decode($tokenResponse, true);
    if (!isset($tokenData['access_token'])) {
        throw new Exception('Access token not found in response');
    }

    $accessToken = $tokenData['access_token'];

    // Get user info from Facebook
    $userInfoUrl = 'https://graph.facebook.com/v18.0/me?fields=id,name,email,picture.width(200).height(200)';
    $ch = curl_init($userInfoUrl . '&access_token=' . $accessToken);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $userResponse = curl_exec($ch);
    curl_close($ch);

    $facebookUser = json_decode($userResponse, true);

    if (!isset($facebookUser['id'])) {
        throw new Exception('Failed to get user info from Facebook');
    }

    $facebookId = $facebookUser['id'];
    $email = $facebookUser['email'] ?? null;
    $name = $facebookUser['name'] ?? 'Facebook User';
    $profilePictureUrl = $facebookUser['picture']['data']['url'] ?? null;

    // If no email provided by Facebook, we can't proceed
    if (empty($email)) {
        $controller->viewRaw('auth/facebook-callback-error', [
            'title'       => 'Email Required',
            'message'     => 'Facebook did not provide an email address. Please ensure your Facebook account has a valid email and try again.',
            'redirectUrl' => $basePath . '/sign-up'
        ]);
        exit;
    }

    // ------------------------------------------------------------------
    // CASE 1: User already exists with this Facebook ID — just log them in
    // ------------------------------------------------------------------
    $checkFacebookId = $pdo->prepare("SELECT user_id, email, account_status, role_id, facebook_id FROM user_accounts WHERE facebook_id = ? AND deleted_at IS NULL");
    $checkFacebookId->execute([$facebookId]);
    $existingFacebookUser = $checkFacebookId->fetch();

    if ($existingFacebookUser) {
        if ($existingFacebookUser['account_status'] === 'active') {
            $roleStmt = $pdo->prepare("SELECT role_code, role_label FROM user_roles WHERE role_id = ?");
            $roleStmt->execute([$existingFacebookUser['role_id']]);
            $role = $roleStmt->fetch();

            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $updateStmt = $pdo->prepare("
                UPDATE user_accounts
                SET failed_login_count = 0, locked_until = NULL,
                    last_login_at = NOW(), last_login_ip = ?
                WHERE user_id = ?
            ");
            $updateStmt->execute([$ipAddress, $existingFacebookUser['user_id']]);

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            session_regenerate_id(true);

            $_SESSION['user_id'] = $existingFacebookUser['user_id'];
            $_SESSION['email'] = $existingFacebookUser['email'];
            $_SESSION['role_code'] = $role['role_code'];
            $_SESSION['role_label'] = $role['role_label'];
            $_SESSION['logged_in'] = true;
            $_SESSION['last_activity'] = time();
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            $sessionId = generateUUID();
            $sessionToken = bin2hex(random_bytes(32));
            $sessionTokenHash = hash('sha256', $sessionToken);
            $sessionExpiresAt = date('Y-m-d H:i:s', strtotime('+2 hours'));

            $sessionStmt = $pdo->prepare("
                INSERT INTO user_sessions (session_id, user_id, token_hash, device_type, ip_address, expires_at, is_active)
                VALUES (?, ?, ?, 'web', ?, ?, 1)
            ");
            $sessionStmt->execute([$sessionId, $existingFacebookUser['user_id'], $sessionTokenHash, $ipAddress, $sessionExpiresAt]);

            $_SESSION['session_id'] = $sessionId;

            // FIX: Use JavaScript redirect to clear hash fragments
            redirectToDashboard($basePath);
        } else {
            $controller->viewRaw('auth/facebook-callback-error', [
                'title'       => 'Account Inactive',
                'message'     => 'Your account is not active. Please contact administrator.',
                'redirectUrl' => $basePath . '/sign-in'
            ]);
            exit;
        }
    }

    // ------------------------------------------------------------------
    // CASE 2: Email exists but no Facebook ID — link accounts
    // ------------------------------------------------------------------
    $checkEmail = $pdo->prepare("SELECT user_id, email, account_status, facebook_id FROM user_accounts WHERE email = ? AND deleted_at IS NULL");
    $checkEmail->execute([$email]);
    $existingEmailUser = $checkEmail->fetch();

    if ($existingEmailUser) {
        if ($existingEmailUser['facebook_id']) {
            $controller->viewRaw('auth/facebook-callback-error', [
                'title'       => 'Account Conflict',
                'message'     => 'This email is already linked to a different Facebook account.',
                'redirectUrl' => $basePath . '/sign-in'
            ]);
            exit;
        }

        // Link Facebook ID to existing account
        $stmt = $pdo->prepare("UPDATE user_accounts SET facebook_id = ?, is_email_verified = 1 WHERE user_id = ?");
        $stmt->execute([$facebookId, $existingEmailUser['user_id']]);

        if ($existingEmailUser['account_status'] === 'active') {
            $roleStmt = $pdo->prepare("
                SELECT ua.role_id, ur.role_code, ur.role_label
                FROM user_accounts ua
                LEFT JOIN user_roles ur ON ua.role_id = ur.role_id
                WHERE ua.user_id = ?
            ");
            $roleStmt->execute([$existingEmailUser['user_id']]);
            $userWithRole = $roleStmt->fetch();

            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $updateStmt = $pdo->prepare("
                UPDATE user_accounts
                SET failed_login_count = 0, locked_until = NULL,
                    last_login_at = NOW(), last_login_ip = ?
                WHERE user_id = ?
            ");
            $updateStmt->execute([$ipAddress, $existingEmailUser['user_id']]);

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            session_regenerate_id(true);

            $_SESSION['user_id'] = $existingEmailUser['user_id'];
            $_SESSION['email'] = $existingEmailUser['email'];
            $_SESSION['role_code'] = $userWithRole['role_code'];
            $_SESSION['role_label'] = $userWithRole['role_label'];
            $_SESSION['logged_in'] = true;
            $_SESSION['last_activity'] = time();
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            $sessionId = generateUUID();
            $sessionToken = bin2hex(random_bytes(32));
            $sessionTokenHash = hash('sha256', $sessionToken);
            $sessionExpiresAt = date('Y-m-d H:i:s', strtotime('+2 hours'));

            $sessionStmt = $pdo->prepare("
                INSERT INTO user_sessions (session_id, user_id, token_hash, device_type, ip_address, expires_at, is_active)
                VALUES (?, ?, ?, 'web', ?, ?, 1)
            ");
            $sessionStmt->execute([$sessionId, $existingEmailUser['user_id'], $sessionTokenHash, $ipAddress, $sessionExpiresAt]);

            $_SESSION['session_id'] = $sessionId;

            // FIX: Use JavaScript redirect to clear hash fragments
            redirectToDashboard($basePath);
        } else {
            $stmt = $pdo->prepare("UPDATE user_accounts SET account_status = 'active', is_email_verified = 1 WHERE user_id = ?");
            $stmt->execute([$existingEmailUser['user_id']]);
            $controller->viewRaw('auth/facebook-callback-success', [
                'title'       => 'Account Activated!',
                'message'     => 'Your account has been activated and linked to Facebook. Please log in.',
                'redirectUrl' => $basePath . '/sign-in'
            ]);
            exit;
        }
    }

    // ------------------------------------------------------------------
    // CASE 3: Brand new user — create account + download profile photo
    // ------------------------------------------------------------------
    $userId = generateUUID();

    $roleStmt = $pdo->prepare("SELECT role_id FROM user_roles WHERE role_code = 'customer' LIMIT 1");
    $roleStmt->execute();
    $role = $roleStmt->fetch();

    if (!$role) {
        $controller->viewRaw('auth/facebook-callback-error', [
            'title'       => 'System Error',
            'message'     => 'Customer role not found. Please contact support.',
            'redirectUrl' => $basePath . '/sign-up'
        ]);
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO user_accounts (user_id, role_id, email, account_status, is_email_verified, facebook_id)
        VALUES (?, ?, ?, 'active', 1, ?)
    ");
    $stmt->execute([$userId, $role['role_id'], $email, $facebookId]);

    // Download and store profile picture
    $profilePhotoPath = null;
    if ($profilePictureUrl) {
        $profilePhotoPath = downloadProfileImage($profilePictureUrl, $userId);
        if (!$profilePhotoPath) {
            $profilePhotoPath = '/build/assets/images/default_profile.jpg';
        }
    } else {
        $profilePhotoPath = '/build/assets/images/default_profile.jpg';
    }

    $profileId = generateUUID();
    $nameParts = explode(' ', $name, 2);
    $firstName = $nameParts[0];
    $lastName = $nameParts[1] ?? '';

    $profileStmt = $pdo->prepare("
        INSERT INTO user_profiles (profile_id, user_id, first_name, last_name, profile_photo_url)
        VALUES (?, ?, ?, ?, ?)
    ");
    $profileStmt->execute([$profileId, $userId, $firstName, $lastName, $profilePhotoPath]);

    // Auto-login the new user
    $roleStmt = $pdo->prepare("SELECT role_code, role_label FROM user_roles WHERE role_id = ?");
    $roleStmt->execute([$role['role_id']]);
    $userRole = $roleStmt->fetch();

    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $updateStmt = $pdo->prepare("
        UPDATE user_accounts
        SET last_login_at = NOW(), last_login_ip = ?
        WHERE user_id = ?
    ");
    $updateStmt->execute([$ipAddress, $userId]);

    session_start();
    session_regenerate_id(true);

    $_SESSION['user_id'] = $userId;
    $_SESSION['email'] = $email;
    $_SESSION['role_code'] = $userRole['role_code'];
    $_SESSION['role_label'] = $userRole['role_label'];
    $_SESSION['logged_in'] = true;
    $_SESSION['last_activity'] = time();
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

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

    // FIX: Use JavaScript redirect to clear hash fragments
    redirectToDashboard($basePath);

} catch (Exception $e) {
    $controller->viewRaw('auth/facebook-callback-error', [
        'title'       => 'Authentication Failed',
        'message'     => 'There was an error with Facebook authentication. Please try again.',
        'redirectUrl' => $basePath . '/sign-up'
    ]);
    exit;
}

// ---------------------------------------------------------------
// Helper Functions
// ---------------------------------------------------------------

function generateUUID(): string
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

/**
 * Safely redirect to dashboard and clear hash fragments from Facebook OAuth
 * 
 * Facebook OAuth adds #_=_ hash to redirect URIs which can confuse frontend routers.
 * This function uses JavaScript redirect to ensure clean navigation.
 */
function redirectToDashboard(string $basePath): void
{
    $roleCode = $_SESSION['role_code'] ?? '';
    $destination = ($roleCode === 'customer')
        ? addslashes($basePath) . '/customer/dashboard'
        : addslashes($basePath) . '/';
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Redirecting...</title>
        <script>
            // Clear hash fragments and redirect based on role
            window.location.replace('<?php echo $destination; ?>');
        </script>
    </head>
    <body>
        <p>Redirecting...</p>
    </body>
    </html>
    <?php
    exit;
}

/**
 * Download Facebook profile image and save to local disk.
 */
function downloadProfileImage(string $imageUrl, string $userId): ?string
{
    if (empty($imageUrl)) {
        return null;
    }

    // Resolve upload directory using DOCUMENT_ROOT
    $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/\\');

    if ($docRoot && is_dir($docRoot)) {
        $uploadDir = $docRoot . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'profile_photos' . DIRECTORY_SEPARATOR;
    } else {
        // Fallback: walk up from __DIR__ looking for a writable parent
        $dir = __DIR__;
        $uploadDir = null;
        for ($i = 0; $i < 5; $i++) {
            if (is_writable($dir)) {
                $uploadDir = $dir . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'profile_photos' . DIRECTORY_SEPARATOR;
                break;
            }
            $dir = dirname($dir);
        }
        if (!$uploadDir) {
            $uploadDir = __DIR__ . '/../../uploads/profile_photos/';
        }
    }

    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            return null;
        }
    }

    // Check directory is writable
    if (!is_writable($uploadDir)) {
        return null;
    }

    $filename = $userId . '_' . time() . '.jpg';
    $filepath = $uploadDir . $filename;

    // Download image via cURL
    $ch = curl_init($imageUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $imageData = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($imageData === false || $httpCode !== 200) {
        return null;
    }

    if (file_put_contents($filepath, $imageData) === false) {
        return null;
    }

    // Return web-accessible path for storage in DB
    return '/uploads/profile_photos/' . $filename;
}