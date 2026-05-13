<?php

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Secure session configuration
if (session_status() === PHP_SESSION_NONE) {
  ini_set('session.cookie_httponly', 1);
  ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on');
  ini_set('session.cookie_samesite', 'Strict');
  ini_set('session.use_strict_mode', 1);
  ini_set('session.gc_maxlifetime', 3600);
  session_start();
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Base URL configuration - Auto-detect if online or localhost
$BASE_URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$BASE_PATH = rtrim(dirname($_SERVER['PHP_SELF']), '/');

// Database configuration - Auto-detect if online or localhost based on host
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
// Debug: Log the detected host
error_log("Detected host: " . $host);
$USE_ONLINE_DB = !in_array($host, ['localhost', '127.0.0.1']) && !preg_match('/^192\.168\./', $host) && !preg_match('/^10\./', $host);
// Debug: Log which DB mode is being used
error_log("Using online DB: " . ($USE_ONLINE_DB ? 'YES' : 'NO'));

// Define constants for frontend use
define('API_BASE_URL', $BASE_URL . $BASE_PATH);
define('CSRF_TOKEN', $_SESSION['csrf_token']);

if ($USE_ONLINE_DB) {
  // Online database credentials - Hostinger specific
  $DB_HOST = 'localhost';
  $DB_NAME = 'u843921121_sociallogin';
  $DB_USER = 'u843921121_anthonzcatzqms';
  $DB_PASS = '^M05uc3IuAxf';
  // Debug: Log online DB credentials (without password)
  error_log("Online DB Config - Host: {$DB_HOST}, Name: {$DB_NAME}, User: {$DB_USER}");

  //   $DB_HOST = getenv('DB_HOST') ?: 'localhost';
  // $DB_NAME = getenv('DB_NAME') ?: 'u861050830_JDCO_DTBSE';
  // $DB_USER = getenv('DB_USER') ?: 'u861050830_JDCO_USRNME';
  // $DB_PASS = getenv('DB_PASS') ?: 'yntaxerror127001!!!Lu9;2SOvw?al';
  
  // Additional connection options for Hostinger
  $DB_OPTIONS = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_TIMEOUT => 5,
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    // PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
      PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci; SET time_zone = '+08:00'"
  ];
} else {
  // Offline/local database credentials
  $DB_HOST = 'localhost';
  $DB_NAME = 'booking';
  $DB_USER = 'root';
  $DB_PASS = '';
  
  $DB_OPTIONS = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_TIMEOUT => 5
  ];
}

// Validate database credentials
if (empty($DB_HOST) || empty($DB_NAME) || empty($DB_USER)) {
  error_log("Invalid database configuration: Missing required credentials");
  http_response_code(500);
  header('Content-Type: application/json');
  echo json_encode([
    'ok' => false,
    'error' => 'INVALID_CONFIG',
    'details' => 'Database configuration is incomplete'
  ]);
  exit;
}

try {
  $pdo = new PDO(
    "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
    $DB_USER,
    $DB_PASS,
    $DB_OPTIONS ?? [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
  );
  
  // Log which database is being used (for debugging)
  error_log("Using database: " . ($USE_ONLINE_DB ? "ONLINE - {$DB_HOST}/{$DB_NAME}" : "OFFLINE - {$DB_HOST}/{$DB_NAME}"));
  error_log("API Base URL: " . API_BASE_URL);
  
} catch (Throwable $e) {
  error_log("Database connection failed: " . $e->getMessage());
  http_response_code(500);
  header('Content-Type: application/json');
  echo json_encode([
    'ok' => false,
    'error' => 'DB_CONNECTION_FAILED',
    'details' => $USE_ONLINE_DB ? 'Online database unavailable' : 'Local database unavailable'
  ]);
  exit;
}
