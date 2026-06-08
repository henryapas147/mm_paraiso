<?php
/**
 * Google OAuth Callback Controller
 * Handles Google Sign-In/Sign-Up and creates/updates user accounts
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

use Google\Client;
use App\Controllers\Controller;

// Load credentials from config file
require_once __DIR__ . '/../config/credentials.php';

// Database configuration - Auto-detect if online or localhost based on host
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$USE_ONLINE_DB = !in_array($host, ['localhost', '127.0.0.1']) && !preg_match('/^192\.168\./', $host) && !preg_match('/^10\./', $host);

if ($USE_ONLINE_DB) {
    // Online database credentials from config/credentials.php
    $DB_HOST = getenv('ONLINE_DB_HOST') ?: 'localhost';
    $DB_NAME = getenv('ONLINE_DB_NAME') ?: '';
    $DB_USER = getenv('ONLINE_DB_USER') ?: '';
    $DB_PASS = getenv('ONLINE_DB_PASS') ?: '';
    $DB_OPTIONS = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_TIMEOUT            => 5,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci; SET time_zone = '+08:00'"
    ];
} else {
    // Offline/local database credentials from config/credentials.php
    $DB_HOST = getenv('LOCAL_DB_HOST') ?: 'localhost';
    $DB_NAME = getenv('LOCAL_DB_NAME') ?: 'booking';
    $DB_USER = getenv('LOCAL_DB_USER') ?: 'root';
    $DB_PASS = getenv('LOCAL_DB_PASS') ?: '';
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
$controller = new Controller();

// Check if we have an authorization code
if (!isset($_GET['code'])) {
    $controller->viewRaw('auth/google-callback-error', [
        'title'       => 'Missing Authorization',
        'message'     => 'No authorization code provided. Please try again.',
        'redirectUrl' => 'sign-up'
    ]);
    exit;
}

$code = $_GET['code'];

// Dynamic base path based on current environment (must be defined before try block for catch block access)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'];
$basePath = dirname($_SERVER['SCRIPT_NAME']); // Gets /admin_booking/build or equivalent

// ------------------------------------------------------------------
// Resolve the correct upload directory regardless of environment.
//
// Strategy (in priority order):
//   1. DOCUMENT_ROOT — most reliable on both Hostinger and XAMPP.
//   2. Fallback: walk up from __DIR__ until we find a writable
//      "uploads" sibling, so the code still works if DOCUMENT_ROOT
//      points somewhere unexpected.
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
    $credentials = json_decode(file_get_contents(__DIR__ . '/../config/gmail-credentials.json'), true);

    // Dynamic redirect URI based on current environment
    $redirectUri = $protocol . '://' . $host . $basePath . '/google-callback';

    $client = new Client();
    $client->setApplicationName('Resort Booking System');
    $client->setClientId($credentials['web']['client_id']);
    $client->setClientSecret($credentials['web']['client_secret']);
    $client->setRedirectUri($redirectUri);
    $client->setScopes(['openid', 'profile', 'email']);
    $client->setAccessType('offline');

    // Exchange code for access token
    $token = $client->fetchAccessTokenWithAuthCode($code);
    $client->setAccessToken($token);

    // Get user info from Google
    $oauth2     = new \Google\Service\Oauth2($client);
    $googleUser = $oauth2->userinfo->get();

    $email    = $googleUser->getEmail();
    $name     = $googleUser->getName();
    $googleId = $googleUser->getId();

    // Request higher resolution profile picture (200px)
    $rawPictureUrl     = $googleUser->getPicture();
    $profilePictureUrl = $rawPictureUrl
        ? strtok($rawPictureUrl, '?') . '?sz=200'
        : null;

    // ------------------------------------------------------------------
    // CASE 1: User already exists with this Google ID — just log them in
    // ------------------------------------------------------------------
    $checkGoogleId = $pdo->prepare("SELECT user_id, email, account_status, role_id, google_id FROM user_accounts WHERE google_id = ? AND deleted_at IS NULL");
    $checkGoogleId->execute([$googleId]);
    $existingGoogleUser = $checkGoogleId->fetch();

    if ($existingGoogleUser) {
        if ($existingGoogleUser['account_status'] === 'active') {
            $roleStmt = $pdo->prepare("SELECT role_code, role_label FROM user_roles WHERE role_id = ?");
            $roleStmt->execute([$existingGoogleUser['role_id']]);
            $role = $roleStmt->fetch();

            $ipAddress  = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $updateStmt = $pdo->prepare("
                UPDATE user_accounts
                SET failed_login_count = 0, locked_until = NULL,
                    last_login_at = NOW(), last_login_ip = ?
                WHERE user_id = ?
            ");
            $updateStmt->execute([$ipAddress, $existingGoogleUser['user_id']]);

            if (session_status() === PHP_SESSION_NONE) {
                ini_set('session.cookie_samesite', 'Lax');
                session_start();
            }
            session_regenerate_id(true);

            $lastVisitedUrl = $_SESSION['last_visited_url'] ?? null;

            $_SESSION['user_id']       = $existingGoogleUser['user_id'];
            $_SESSION['email']         = $existingGoogleUser['email'];
            $_SESSION['role_code']     = $role['role_code'];
            $_SESSION['role_label']    = $role['role_label'];
            $_SESSION['logged_in']     = true;
            $_SESSION['last_activity'] = time();
            $_SESSION['csrf_token']    = bin2hex(random_bytes(32));
            unset($_SESSION['last_visited_url']);

            $sessionId        = generateUUID();
            $sessionToken     = bin2hex(random_bytes(32));
            $sessionTokenHash = hash('sha256', $sessionToken);
            $sessionExpiresAt = date('Y-m-d H:i:s', strtotime('+2 hours'));

            $sessionStmt = $pdo->prepare("
                INSERT INTO user_sessions (session_id, user_id, token_hash, device_type, ip_address, expires_at, is_active)
                VALUES (?, ?, ?, 'web', ?, ?, 1)
            ");
            $sessionStmt->execute([$sessionId, $existingGoogleUser['user_id'], $sessionTokenHash, $ipAddress, $sessionExpiresAt]);

            $_SESSION['session_id'] = $sessionId;

            if ($lastVisitedUrl) {
                header('Location: ' . $lastVisitedUrl);
            } elseif (($_SESSION['role_code'] ?? '') === 'customer') {
                header('Location: ./customer/dashboard');
            } else {
                header('Location: ./');
            }
            exit;
        } else {
            $controller->viewRaw('auth/google-callback-error', [
                'title'       => 'Account Inactive',
                'message'     => 'Your account is not active. Please contact administrator.',
                'redirectUrl' => $basePath . '/sign-in'
            ]);
            exit;
        }
    }

    // ------------------------------------------------------------------
    // CASE 2: Email exists but no Google ID — link accounts
    // ------------------------------------------------------------------
    $checkEmail = $pdo->prepare("SELECT user_id, email, account_status, google_id FROM user_accounts WHERE email = ? AND deleted_at IS NULL");
    $checkEmail->execute([$email]);
    $existingEmailUser = $checkEmail->fetch();

    if ($existingEmailUser) {
        if ($existingEmailUser['google_id']) {
            $controller->viewRaw('auth/google-callback-error', [
                'title'       => 'Account Conflict',
                'message'     => 'This email is already linked to a different Google account.',
                'redirectUrl' => $basePath . '/sign-in'
            ]);
            exit;
        }

        // Link Google ID to existing account
        $stmt = $pdo->prepare("UPDATE user_accounts SET google_id = ?, is_email_verified = 1 WHERE user_id = ?");
        $stmt->execute([$googleId, $existingEmailUser['user_id']]);

        if ($existingEmailUser['account_status'] === 'active') {
            $roleStmt = $pdo->prepare("
                SELECT ua.role_id, ur.role_code, ur.role_label
                FROM user_accounts ua
                LEFT JOIN user_roles ur ON ua.role_id = ur.role_id
                WHERE ua.user_id = ?
            ");
            $roleStmt->execute([$existingEmailUser['user_id']]);
            $userWithRole = $roleStmt->fetch();

            $ipAddress  = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $updateStmt = $pdo->prepare("
                UPDATE user_accounts
                SET failed_login_count = 0, locked_until = NULL,
                    last_login_at = NOW(), last_login_ip = ?
                WHERE user_id = ?
            ");
            $updateStmt->execute([$ipAddress, $existingEmailUser['user_id']]);

            if (session_status() === PHP_SESSION_NONE) {
                ini_set('session.cookie_samesite', 'Lax');
                session_start();
            }
            session_regenerate_id(true);

            $lastVisitedUrl = $_SESSION['last_visited_url'] ?? null;

            $_SESSION['user_id']       = $existingEmailUser['user_id'];
            $_SESSION['email']         = $existingEmailUser['email'];
            $_SESSION['role_code']     = $userWithRole['role_code'];
            $_SESSION['role_label']    = $userWithRole['role_label'];
            $_SESSION['logged_in']     = true;
            $_SESSION['last_activity'] = time();
            $_SESSION['csrf_token']    = bin2hex(random_bytes(32));
            unset($_SESSION['last_visited_url']);

            $sessionId        = generateUUID();
            $sessionToken     = bin2hex(random_bytes(32));
            $sessionTokenHash = hash('sha256', $sessionToken);
            $sessionExpiresAt = date('Y-m-d H:i:s', strtotime('+2 hours'));

            $sessionStmt = $pdo->prepare("
                INSERT INTO user_sessions (session_id, user_id, token_hash, device_type, ip_address, expires_at, is_active)
                VALUES (?, ?, ?, 'web', ?, ?, 1)
            ");
            $sessionStmt->execute([$sessionId, $existingEmailUser['user_id'], $sessionTokenHash, $ipAddress, $sessionExpiresAt]);

            $_SESSION['session_id'] = $sessionId;

            if ($lastVisitedUrl) {
                header('Location: ' . $lastVisitedUrl);
            } elseif (($_SESSION['role_code'] ?? '') === 'customer') {
                header('Location: ./customer/dashboard');
            } else {
                header('Location: ./');
            }
            exit;
        } else {
            $stmt = $pdo->prepare("UPDATE user_accounts SET account_status = 'active', is_email_verified = 1 WHERE user_id = ?");
            $stmt->execute([$existingEmailUser['user_id']]);
            $controller->viewRaw('auth/google-callback-success', [
                'title'       => 'Account Activated!',
                'message'     => 'Your account has been activated and linked to Google. Please log in.',
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
        $controller->viewRaw('auth/google-callback-error', [
            'title'       => 'System Error',
            'message'     => 'Customer role not found. Please contact support.',
            'redirectUrl' => $basePath . '/sign-up'
        ]);
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO user_accounts (user_id, role_id, email, account_status, is_email_verified, google_id)
        VALUES (?, ?, ?, 'active', 1, ?)
    ");
    $stmt->execute([$userId, $role['role_id'], $email, $googleId]);

    // Download and store profile picture
    $profilePhotoPath = null;
    if ($profilePictureUrl) {
        $profilePhotoPath = downloadProfileImage($profilePictureUrl, $userId);
        if (!$profilePhotoPath) {
            error_log("Profile image download failed for new user: $userId | URL: $profilePictureUrl");
            $profilePhotoPath = '/build/assets/images/default_profile.jpg';
        }
    } else {
        $profilePhotoPath = '/build/assets/images/default_profile.jpg';
    }

    $profileId = generateUUID();
    $nameParts = explode(' ', $name, 2);
    $firstName = $nameParts[0];
    $lastName  = $nameParts[1] ?? '';

    $profileStmt = $pdo->prepare("
        INSERT INTO user_profiles (profile_id, user_id, first_name, last_name, profile_photo_url)
        VALUES (?, ?, ?, ?, ?)
    ");
    $profileStmt->execute([$profileId, $userId, $firstName, $lastName, $profilePhotoPath]);

    // Auto-login the new user
    $roleStmt = $pdo->prepare("SELECT role_code, role_label FROM user_roles WHERE role_id = ?");
    $roleStmt->execute([$role['role_id']]);
    $userRole = $roleStmt->fetch();

    $ipAddress  = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $updateStmt = $pdo->prepare("
        UPDATE user_accounts
        SET last_login_at = NOW(), last_login_ip = ?
        WHERE user_id = ?
    ");
    $updateStmt->execute([$ipAddress, $userId]);

    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_samesite', 'Lax');
        session_start();
    }
    session_regenerate_id(true);

    $lastVisitedUrl = $_SESSION['last_visited_url'] ?? null;

    $_SESSION['user_id']       = $userId;
    $_SESSION['email']         = $email;
    $_SESSION['role_code']     = $userRole['role_code'];
    $_SESSION['role_label']    = $userRole['role_label'];
    $_SESSION['logged_in']     = true;
    $_SESSION['last_activity'] = time();
    $_SESSION['csrf_token']    = bin2hex(random_bytes(32));
    unset($_SESSION['last_visited_url']);

    $sessionId        = generateUUID();
    $sessionToken     = bin2hex(random_bytes(32));
    $sessionTokenHash = hash('sha256', $sessionToken);
    $sessionExpiresAt = date('Y-m-d H:i:s', strtotime('+2 hours'));

    $sessionStmt = $pdo->prepare("
        INSERT INTO user_sessions (session_id, user_id, token_hash, device_type, ip_address, expires_at, is_active)
        VALUES (?, ?, ?, 'web', ?, ?, 1)
    ");
    $sessionStmt->execute([$sessionId, $userId, $sessionTokenHash, $ipAddress, $sessionExpiresAt]);

    $_SESSION['session_id'] = $sessionId;

    if ($lastVisitedUrl) {
        header('Location: ' . $lastVisitedUrl);
    } elseif (($_SESSION['role_code'] ?? '') === 'customer') {
        header('Location: ./customer/dashboard');
    } else {
        header('Location: ./');
    }
    exit;

} catch (Exception $e) {
    error_log("Google OAuth error: " . $e->getMessage());
    $controller->viewRaw('auth/google-callback-error', [
        'title'       => 'Authentication Failed',
        'message'     => 'There was an error with Google authentication. Please try again.',
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
 * Download Google profile image and save to local disk.
 *
 * Uses DOCUMENT_ROOT to resolve the correct upload directory on both
 * Hostinger (online) and XAMPP (local), avoiding path resolution bugs
 * that occur when navigating with __DIR__ and relative '../' segments.
 *
 * Disk path  : {DOCUMENT_ROOT}/uploads/profile_photos/<file>.jpg
 * DB value   : /uploads/profile_photos/<file>.jpg
 * Browser    : https://yourdomain.com/uploads/profile_photos/<file>.jpg
 */
function downloadProfileImage(string $imageUrl, string $userId): ?string
{
    if (empty($imageUrl)) {
        return null;
    }

    // ------------------------------------------------------------------
    // Resolve upload directory using DOCUMENT_ROOT (primary strategy).
    // Falls back gracefully if DOCUMENT_ROOT is unavailable.
    // ------------------------------------------------------------------
    $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/\\');

    if ($docRoot && is_dir($docRoot)) {
        // Standard path on Hostinger: /home/u843921121/public_html/uploads/profile_photos/
        // Standard path on XAMPP:     C:/xampp/htdocs/uploads/profile_photos/
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
        // Hard fallback (original behaviour — two levels up)
        if (!$uploadDir) {
            $uploadDir = __DIR__ . '/../../uploads/profile_photos/';
        }
    }

    error_log("[downloadProfileImage] Resolved uploadDir: " . $uploadDir);

    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            error_log("[downloadProfileImage] Could not create upload directory: " . $uploadDir);
            return null;
        }
    }

    // Check directory is writable before attempting download
    if (!is_writable($uploadDir)) {
        error_log("[downloadProfileImage] Upload directory is not writable: " . $uploadDir);
        return null;
    }

    $filename = $userId . '_' . time() . '.jpg';
    $filepath = $uploadDir . $filename;

    error_log("[downloadProfileImage] Attempting to save profile image to: " . $filepath);

    // Download image via cURL
    $ch = curl_init($imageUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $imageData = curl_exec($ch);
    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($imageData === false || $httpCode !== 200) {
        error_log("[downloadProfileImage] Download failed — URL: $imageUrl | HTTP: $httpCode | cURL: $curlError");
        return null;
    }

    error_log("[downloadProfileImage] Downloaded image size: " . strlen($imageData) . " bytes");

    if (file_put_contents($filepath, $imageData) === false) {
        error_log("[downloadProfileImage] Failed to write file: $filepath — check directory permissions");
        return null;
    }

    error_log("[downloadProfileImage] Successfully saved: " . $filepath);

    // Return web-accessible path for storage in DB
    return '/uploads/profile_photos/' . $filename;
}