<?php
/**
 * API Endpoint for Sending Email Replies to Contact Inquiries
 * Uses Gmail SMTP via PHPMailer
 */

header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();
require_once __DIR__ . '/../services/EmailService.php';

use App\Services\EmailService;


// Check if user is logged in and has proper permissions
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please log in.']);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
    exit;
}

// Validate required fields
$inquiryId = $data['inquiry_id'] ?? null;
$recipientEmail = $data['recipient_email'] ?? null;
$recipientName = $data['recipient_name'] ?? '';
$subject = $data['subject'] ?? '';
$message = $data['message'] ?? '';

if (!$inquiryId || !$recipientEmail || !$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields: inquiry_id, recipient_email, message']);
    exit;
}

// Validate email format
if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

try {
    // Verify the inquiry exists and get its details
    $stmt = $pdo->prepare("
        SELECT inquiry_id, full_name, email, subject, status
        FROM contact_inquiries
        WHERE inquiry_id = ?
    ");
    $stmt->execute([$inquiryId]);
    $inquiry = $stmt->fetch();

    if (!$inquiry) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Inquiry not found']);
        exit;
    }

    // Check if Gmail credentials are configured
    $gmailUser = getenv('GMAIL_USER');
    $gmailPass = getenv('GMAIL_APP_PASSWORD');

    if (empty($gmailUser) || empty($gmailPass)) {
        http_response_code(503);
        echo json_encode([
            'success' => false,
            'message' => 'Gmail is not configured. Please set GMAIL_USER and GMAIL_APP_PASSWORD in your credentials.php file.'
        ]);
        exit;
    }

    // Initialize email service
    $emailService = new EmailService($pdo);

    // Send the reply
    $result = $emailService->sendInquiryReply(
        $recipientEmail,
        $recipientName ?: $inquiry['full_name'],
        $subject ?: $inquiry['subject'],
        $message,
        $inquiryId,
        $_SESSION['user_id']
    );

    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'message' => 'Reply sent successfully via Gmail',
            'email_id' => $result['email_id'],
            'sent_at' => date('Y-m-d H:i:s')
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => $result['message']
        ]);
    }

} catch (PDOException $e) {
    error_log('Database error in send-email-reply: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
} catch (Exception $e) {
    error_log('Error in send-email-reply: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}
