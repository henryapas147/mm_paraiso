<?php

// Load credentials from gitignored credentials.php
$credentialsFile = __DIR__ . '/credentials.php';
if (file_exists($credentialsFile)) {
  require_once $credentialsFile;
}

// Debug mode flag — set APP_DEBUG=true in credentials.php for local dev only
$APP_DEBUG = getenv('APP_DEBUG') === 'true';

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://unpkg.com https://buttons.github.io; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://unpkg.com; font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data: https:; connect-src 'self' https://unpkg.com https://nominatim.openstreetmap.org; frame-src 'self' https://www.google.com https://www.google.com.ph https://maps.google.com https://maps.google.com.ph");
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');

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

// Base URL configuration — validate host to prevent Host Header Injection
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
if (!preg_match('/^[a-zA-Z0-9\-\.\:]+$/', $host)) {
  $host = 'localhost';
}
$BASE_URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$host}";

// Sanitize PHP_SELF using SCRIPT_NAME to prevent path injection
$BASE_PATH = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/');

// Database configuration - Auto-detect if online or localhost based on host
$hostForDb = strtolower(preg_replace('/:\d+$/', '', $host));
$USE_ONLINE_DB = !in_array($hostForDb, ['localhost', '127.0.0.1']) && !preg_match('/^192\.168\./', $hostForDb) && !preg_match('/^10\./', $hostForDb);
if ($APP_DEBUG) {
  error_log("Detected host: " . $host);
  error_log("Using online DB: " . ($USE_ONLINE_DB ? 'YES' : 'NO'));
}

// Define constants for frontend use
define('API_BASE_URL', $BASE_URL . $BASE_PATH);
define('CSRF_TOKEN', $_SESSION['csrf_token']);

if ($USE_ONLINE_DB) {
  // Online database credentials - loaded from credentials.php (gitignored)
  $DB_HOST = getenv('ONLINE_DB_HOST') ?: 'localhost';
  $DB_NAME = getenv('ONLINE_DB_NAME') ?: '';
  $DB_USER = getenv('ONLINE_DB_USER') ?: '';
  $DB_PASS = getenv('ONLINE_DB_PASS') ?: '';
  if ($APP_DEBUG) {
    error_log("Online DB Config - Host: {$DB_HOST}, Name: {$DB_NAME}, User: {$DB_USER}");
  }

  // Additional connection options for Hostinger
  $DB_OPTIONS = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_TIMEOUT => 5,
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci; SET time_zone = '+08:00'"
  ];
} else {
  // Offline/local database credentials - loaded from credentials.php (gitignored)
  $DB_HOST = getenv('LOCAL_DB_HOST') ?: 'localhost';
  $DB_NAME = getenv('LOCAL_DB_NAME') ?: 'booking';
  $DB_USER = getenv('LOCAL_DB_USER') ?: 'root';
  $DB_PASS = getenv('LOCAL_DB_PASS') ?: '';

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
  
  if ($APP_DEBUG) {
    error_log("Using database: " . ($USE_ONLINE_DB ? "ONLINE - {$DB_HOST}/{$DB_NAME}" : "OFFLINE - {$DB_HOST}/{$DB_NAME}"));
    error_log("API Base URL: " . API_BASE_URL);
  }
  
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
