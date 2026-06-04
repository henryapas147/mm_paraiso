<?php
/**
 * Public API for Contact Inquiries
 * Allows visitors to submit contact form data
 */

require_once '../../config/db.php';
require_once '../../build/services/NotificationService.php';

use App\Services\NotificationService;

// Set JSON response header
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get POST data (support both form-data and JSON)
$input = $_POST;
if (empty($input)) {
    $json = file_get_contents('php://input');
    $input = json_decode($json, true) ?: [];
}

// Validate required fields
$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$subject = trim($input['subject'] ?? '');
$message = trim($input['message'] ?? '');

if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields (Name, Email, Message).']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO contact_inquiries
        (full_name, email, subject, message, ip_address, user_agent, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

    $stmt->execute([$name, $email, $subject, $message, $ip_address, $user_agent]);

    // Notify admin staff about new inquiry
    try {
        $notificationService = new NotificationService($pdo);
        
        // Get admin user IDs to notify
        $adminStmt = $pdo->prepare("
            SELECT ua.user_id 
            FROM user_accounts ua
            INNER JOIN user_roles ur ON ua.role_id = ur.role_id
            WHERE ur.role_code IN ('admin', 'manager')
            AND ua.account_status = 'active'
        ");
        $adminStmt->execute();
        $adminUsers = $adminStmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (!empty($adminUsers)) {
            $notificationService->createSupportNotification(
                $adminUsers,
                "New Contact Inquiry: {$subject}",
                "From: {$name} ({$email})\n\n{$message}",
                null
            );
        }
    } catch (Exception $notifError) {
        // Log notification error but don't fail the request
        error_log("Failed to create notification for contact inquiry: " . $notifError->getMessage());
    }

    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your message! We will get back to you soon.'
    ]);

} catch (PDOException $e) {
    error_log("Contact form API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Sorry, there was an error sending your message. Please try again later.']);
}
