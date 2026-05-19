<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';

use Google\Client;

// Load credentials
$credentialsPath = __DIR__ . '/../config/gmail-credentials.json';
if (!file_exists($credentialsPath)) {
    die("Error: config/gmail-credentials.json not found at " . $credentialsPath);
}

$credentials = json_decode(file_get_contents($credentialsPath), true);

if (!$credentials || !isset($credentials['web']['client_id'])) {
    die("Error: Invalid credentials file format. Expected 'web.client_id'");
}

// Dynamic redirect URI based on current environment
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$basePath = dirname($_SERVER['PHP_SELF']); // Gets /admin_booking/build or equivalent
$redirectUri = $protocol . '://' . $host . $basePath . '/get_refresh_token.php';

$client = new Client();
$client->setApplicationName('Resort Booking System');
$client->setClientId($credentials['web']['client_id']);
$client->setClientSecret($credentials['web']['client_secret']);
$client->setRedirectUri($redirectUri);
$client->setScopes([\Google\Service\Gmail::GMAIL_SEND, \Google\Service\Gmail::GMAIL_READONLY]);
$client->setAccessType('offline');
$client->setPrompt('consent');

// Check if we have an authorization code
if (isset($_GET['code'])) {
    // Exchange code for access token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    $refreshToken = $token['refresh_token'] ?? 'NOT RETURNED — re-run with ?prompt=consent';
    echo "<h2>✅ Success! Your refresh token:</h2>";
    echo "<p>Copy the value below and add it to <code>config/credentials.php</code>:</p>";
    echo "<pre style='background:#1e1e1e;color:#4ec9b0;padding:16px;border-radius:8px;font-size:14px;'>putenv('GMAIL_POLLING_REFRESH_TOKEN=" . htmlspecialchars($refreshToken) . "');</pre>";
    echo "<hr>";
    echo "<details><summary>Full token response</summary><pre>" . htmlspecialchars(json_encode($token, JSON_PRETTY_PRINT)) . "</pre></details>";
} else {
    // Generate authorization URL
    $authUrl = $client->createAuthUrl();
    
    echo "<h2>Step 1: Authorize the application</h2>";
    echo "<p>Click the link below to authorize your Gmail account:</p>";
    echo "<a href='" . $authUrl . "' style='background-color: #4CAF50; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;'>Authorize Gmail Access</a>";
    echo "<p>After authorization, you'll be redirected back to this page with your refresh token.</p>";
}
