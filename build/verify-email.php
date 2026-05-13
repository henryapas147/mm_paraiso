<?php
/**
 * Email Verification Controller
 * Handles email verification token validation
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

// View paths
$viewPath = __DIR__ . '/views/auth/';

// Helper function to render view
function renderView($viewFile, $data = []) {
    global $viewPath;
    extract($data);
    $viewFile = $viewPath . $viewFile . '.php';
    if (file_exists($viewFile)) {
        include $viewFile;
    } else {
        die("View not found: $viewFile");
    }
}

// Get token from query string
$token = $_GET['token'] ?? null;

if (!$token) {
    renderView('email-verify-error', [
        'title' => 'Invalid Request',
        'message' => 'No verification token provided.',
        'redirectUrl' => '/build/sign-in'
    ]);
    exit;
}

// Connect to database
try {
    $DB_HOST = 'localhost';
    $DB_NAME = 'booking';
    $DB_USER = 'root';
    $DB_PASS = '';

    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    $tokenHash = hash('sha256', $token);

    $stmt = $pdo->prepare("
        UPDATE user_accounts
        SET is_email_verified = 1, email_verify_token = NULL, email_verify_expiry = NULL, account_status = 'active'
        WHERE email_verify_token = ? AND email_verify_expiry > NOW()
    ");
    $stmt->execute([$tokenHash]);

    if ($stmt->rowCount() > 0) {
        renderView('email-verify-success', [
            'title' => 'Email Verified Successfully!',
            'message' => 'Your account has been verified. You can now log in to access your account.',
            'redirectUrl' => '/build/sign-in'
        ]);
    } else {
        renderView('email-verify-error', [
            'title' => 'Verification Failed',
            'message' => 'The verification link is invalid or has expired. Please request a new verification email.',
            'redirectUrl' => '/build/sign-in'
        ]);
    }
} catch (PDOException $e) {
    error_log("Email verification error: " . $e->getMessage());
    renderView('email-verify-error', [
        'title' => 'System Error',
        'message' => 'An error occurred while processing your request. Please try again later.',
        'redirectUrl' => '/build/sign-in'
    ]);
}
