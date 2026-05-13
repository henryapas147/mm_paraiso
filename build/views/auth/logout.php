<?php
require_once __DIR__ . '/../../config/db.php';

// Get session info before destroying
$userId = $_SESSION['user_id'] ?? null;
$rememberToken = $_COOKIE['remember_token'] ?? null;

// Invalidate session in database
if ($userId) {
    try {
        // Deactivate all sessions for this user
        $deactivateSession = $pdo->prepare("
            UPDATE user_sessions 
            SET is_active = 0
            WHERE user_id = ?
        ");
        $deactivateSession->execute([$userId]);
    } catch (PDOException $e) {
        error_log("Logout database error: " . $e->getMessage());
    }
}

// Destroy all session data
$_SESSION = array();

// Delete the session cookie
if (isset($_COOKIE[session_name()])) {
    $secureCookie = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    setcookie(session_name(), '', time()-42000, '/', '', $secureCookie, true);
}

// Delete remember me cookie if exists
if (isset($_COOKIE['remember_token'])) {
    $secureCookie = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    setcookie('remember_token', '', time()-42000, '/', '', $secureCookie, true);
}

// Destroy the session
session_destroy();

// Redirect to sign-in page using relative path
header('Location: sign-in');
exit;
?>
