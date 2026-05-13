<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use Google\Client;

// Load credentials
$credentialsPath = __DIR__ . '/config/gmail-credentials.json';
if (!file_exists($credentialsPath)) {
    die("Error: config/gmail-credentials.json not found at " . $credentialsPath);
}

$credentials = json_decode(file_get_contents($credentialsPath), true);

if (!$credentials || !isset($credentials['web']['client_id'])) {
    die("Error: Invalid credentials file format. Expected 'web.client_id'");
}

$client = new Client();
$client->setApplicationName('Resort Booking System');
$client->setClientId($credentials['web']['client_id']);
$client->setClientSecret($credentials['web']['client_secret']);
$client->setRedirectUri($credentials['web']['redirect_uris'][0]);
$client->setScopes([\Google\Service\Gmail::GMAIL_SEND]);
$client->setAccessType('offline');
$client->setPrompt('consent');

// Check if we have an authorization code
if (isset($_GET['code'])) {
    // Exchange code for access token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    echo "<h2>Success! Copy your refresh token:</h2>";
    echo "<pre>";
    print_r($token);
    echo "</pre>";
    echo "<p><strong>Refresh Token:</strong> " . $token['refresh_token'] . "</p>";
    echo "<p>Add this to your config/gmail-credentials.json file in the 'refresh_token' field.</p>";
} else {
    // Generate authorization URL
    $authUrl = $client->createAuthUrl();
    
    echo "<h2>Step 1: Authorize the application</h2>";
    echo "<p>Click the link below to authorize your Gmail account:</p>";
    echo "<a href='" . $authUrl . "' target='_blank' style='background-color: #4CAF50; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;'>Authorize Gmail Access</a>";
    echo "<p>After authorization, you'll be redirected back to this page with your refresh token.</p>";
}
