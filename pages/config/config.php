<?php
/**
 * Pages Configuration Bridge
 * Connects pages to the main configuration files
 */

// Include main database configuration
require_once __DIR__ . '/../../config/db.php';

// Include authentication functions (uncomment if pages need auth)
// require_once __DIR__ . '/../../config/auth.php';

// Database connection is now available as $pdo
// Constants available: API_BASE_URL, CSRF_TOKEN
// Session management and security headers are already configured
?>
