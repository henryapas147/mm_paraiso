<?php
/**
 * API Endpoint: Process Schedule Visit
 * Creates reservation with payment information
 */

require_once '../../config/db.php';
require_once '../../build/services/EmailService.php';

use App\Services\EmailService;

// Set JSON content type
header('Content-Type: application/json; charset=utf-8');

// Check CSRF token
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Invalid security token. Please refresh the page and try again.']);
    exit;
}

// Validate required fields
$required = ['first_name', 'last_name', 'email', 'phone', 'visit_date', 'visit_time', 'property_id', 'room_category_id'];
$missing = [];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        $missing[] = ucwords(str_replace('_', ' ', $field));
    }
}

if (!empty($missing)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Required fields missing: ' . implode(', ', $missing)]);
    exit;
}

// Validate email
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Please enter a valid email address.']);
    exit;
}

// Get logged in user ID if available
$userId = $_SESSION['user_id'] ?? null;

try {
    $pdo->beginTransaction();

    // Get payment method details (form sends method_code like 'gcash', not method_id)
    $paymentMethodCode = $_POST['payment_method'] ?? '';
    $paymentMethodStmt = $pdo->prepare("SELECT method_id, method_label, method_type FROM payment_methods WHERE method_code = ? AND is_active = 1");
    $paymentMethodStmt->execute([$paymentMethodCode]);
    $paymentMethod = $paymentMethodStmt->fetch();

    if (!$paymentMethod) {
        throw new Exception('Invalid payment method selected.');
    }

    // Get payment settings
    $settingsStmt = $pdo->prepare("
        SELECT payment_type, payment_value, min_payment_required, 
               allow_full_payment, allow_partial_payment
        FROM payment_settings 
        WHERE is_active = 1 
        ORDER BY property_id DESC 
        LIMIT 1
    ");
    $settingsStmt->execute();
    $paymentSettings = $settingsStmt->fetch();

    if (!$paymentSettings) {
        $paymentSettings = [
            'payment_type' => 'percentage',
            'payment_value' => 30.00,
            'min_payment_required' => 500.00,
            'allow_full_payment' => 1,
            'allow_partial_payment' => 1
        ];
    }

    // Calculate required payment
    $totalAmount = floatval($_POST['total_amount'] ?? $_POST['calculated_total'] ?? 0);
    $paymentOption = $_POST['payment_option'] ?? 'full';

    if ($paymentOption === 'full' && $paymentSettings['allow_full_payment']) {
        $requiredPayment = $totalAmount;
    } else {
        if ($paymentSettings['payment_type'] === 'percentage') {
            $requiredPayment = $totalAmount * ($paymentSettings['payment_value'] / 100);
        } else {
            $requiredPayment = max($paymentSettings['payment_value'], $paymentSettings['min_payment_required']);
        }
    }

    // Determine payment status
    $amountPaid = floatval($_POST['amount_paid'] ?? 0);
    if ($amountPaid >= $totalAmount) {
        $paymentStatus = 'paid';
    } elseif ($amountPaid > 0) {
        $paymentStatus = 'partial';
    } else {
        $paymentStatus = 'unpaid';
    }

    // Generate unique reservation code
    $reservationCode = 'RES-' . strtoupper(substr(uniqid(), -8));

    // Create reservation
    $reservationId = 'res-' . uniqid();
    $reservationStmt = $pdo->prepare("
        INSERT INTO reservations (
            reservation_id, reservation_code, property_id, customer_user_id,
            booking_type, reservation_status, payment_status,
            check_in_date, check_in_time, check_out_date, check_out_time,
            walkin_first_name, walkin_last_name, walkin_email, walkin_phone,
            num_adults, num_children, special_requests,
            source_channel, total_amount, paid_amount, balance_amount, created_at, updated_at
        ) VALUES (
            :reservation_id, :reservation_code, :property_id, :customer_user_id,
            :booking_type, 'pending', :payment_status,
            :check_in_date, :check_in_time, :check_out_date, :check_out_time,
            :first_name, :last_name, :email, :phone,
            :num_adults, :num_children, :special_requests,
            'website', :total_amount, :paid_amount, :balance_amount, NOW(), NOW()
        )
    ");

    $balanceAmount = $totalAmount - $amountPaid;

    $reservationStmt->execute([
        ':reservation_id' => $reservationId,
        ':reservation_code' => $reservationCode,
        ':property_id' => $_POST['property_id'],
        ':customer_user_id' => $userId,
        ':booking_type' => $_POST['booking_type'] ?? 'day_use',
        ':payment_status' => $paymentStatus,
        ':check_in_date' => $_POST['visit_date'],
        ':check_in_time' => $_POST['visit_time'],
        ':check_out_date' => $_POST['checkout_date'] ?? $_POST['visit_date'],
        ':check_out_time' => $_POST['checkout_time'] ?? '17:00',
        ':first_name' => $_POST['first_name'],
        ':last_name' => $_POST['last_name'],
        ':email' => $_POST['email'],
        ':phone' => $_POST['phone'],
        ':num_adults' => $_POST['num_adults'] ?? 1,
        ':num_children' => $_POST['num_children'] ?? 0,
        ':special_requests' => $_POST['message'] ?? '',
        ':total_amount' => $totalAmount,
        ':paid_amount' => $amountPaid,
        ':balance_amount' => $balanceAmount
    ]);

    // Get payment account ID if method is not cash
    $accountId = null;
    if ($paymentMethod['method_type'] !== 'cash') {
        $accountStmt = $pdo->prepare("
            SELECT account_id FROM payment_accounts 
            WHERE method_id = ? AND is_active = 1 
            ORDER BY is_default DESC LIMIT 1
        ");
        $accountStmt->execute([$paymentMethod['method_id']]);
        $accountData = $accountStmt->fetch();
        if ($accountData) {
            $accountId = $accountData['account_id'];
        }
    }

    // Record payment
    $paymentId = 'pay-' . uniqid();
    $paymentStmt = $pdo->prepare("
        INSERT INTO payments (
            payment_id, reservation_id, amount, method_id, account_id,
            transaction_type, payment_status, reference_number,
            payment_date, payment_time,
            created_at, updated_at
        ) VALUES (
            :payment_id, :reservation_id, :amount, :method_id, :account_id,
            :transaction_type, :status, :reference,
            CURDATE(), CURTIME(),
            NOW(), NOW()
        )
    ");

    // Determine transaction type based on payment option
    $transactionType = ($paymentOption === 'partial') ? 'partial_payment' : 'payment';

    $paymentStmt->execute([
        ':payment_id' => $paymentId,
        ':reservation_id' => $reservationId,
        ':amount' => $amountPaid,
        ':method_id' => $paymentMethod['method_id'],
        ':account_id' => $accountId,
        ':transaction_type' => $transactionType,
        ':status' => $paymentStatus,
        ':reference' => $_POST['transaction_reference'] ?? null
    ]);

    // Record payment status history
    $historyId = 'hist-' . uniqid();
    $historyStmt = $pdo->prepare("
        INSERT INTO payment_status_history (
            history_id, payment_id, old_status, new_status,
            changed_by, change_reason, created_at
        ) VALUES (?, ?, NULL, ?, ?, ?, NOW())
    ");
    $historyStmt->execute([
        $historyId,
        $paymentId,
        $paymentStatus,
        $userId,
        'Initial payment recorded via website booking'
    ]);

    // Handle file upload
    $hasAttachment = false;
    if (isset($_FILES['proof_of_payment']) && $_FILES['proof_of_payment']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($_FILES['proof_of_payment']['type'], $allowedTypes)) {
            throw new Exception('Invalid file type. Please upload an image (JPG, PNG, GIF) or PDF.');
        }

        if ($_FILES['proof_of_payment']['size'] > $maxSize) {
            throw new Exception('File is too large. Maximum size is 5MB.');
        }

        // Generate unique filename
        $fileExtension = pathinfo($_FILES['proof_of_payment']['name'], PATHINFO_EXTENSION);
        $newFilename = 'payment_' . $paymentId . '_' . time() . '.' . $fileExtension;
        $uploadDir = __DIR__ . '/../../uploads/payments/payment_attachments/';
        $uploadPath = $uploadDir . $newFilename;

        // Ensure upload directory exists
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new Exception('Failed to create upload directory.');
            }
        }

        if (!move_uploaded_file($_FILES['proof_of_payment']['tmp_name'], $uploadPath)) {
            throw new Exception('Failed to upload file. Please try again.');
        }

        // Save attachment record
        $attachmentId = 'attach-' . uniqid();
        $relativePath = 'uploads/payments/payment_attachments/' . $newFilename;

        $attachStmt = $pdo->prepare("
            INSERT INTO payment_attachments (
                attachment_id, payment_id, file_name, file_path, file_type, file_size, original_name, uploaded_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $attachStmt->execute([
            $attachmentId,
            $paymentId,
            $newFilename,
            $relativePath,
            $_FILES['proof_of_payment']['type'],
            $_FILES['proof_of_payment']['size'],
            $_FILES['proof_of_payment']['name']
        ]);

        $hasAttachment = true;
    }

    // Create notification for new reservation
    $notifId = 'notif-' . uniqid();
    $notifStmt = $pdo->prepare("
        INSERT INTO notifications (
            notif_id, property_id, notif_type, title, body,
            action_url, target_audience, reference_type, reference_id,
            status, created_by, created_at
        ) VALUES (
            ?, ?, 'reservation_confirmed', ?, ?,
            ?, 'all_staff', 'reservation', ?,
            'sent', ?, NOW()
        )
    ");
    $notifTitle = 'New Reservation: ' . $reservationCode;
    $notifBody = 'New reservation from ' . $_POST['first_name'] . ' ' . $_POST['last_name'] . 
                  ' for ' . date('M d, Y', strtotime($_POST['visit_date'])) . '.
Total: ₱' . number_format($totalAmount, 2);
    $notifAction = 'reservations/reservations?id=' . $reservationId;
    $notifStmt->execute([
        $notifId,
        $_POST['property_id'],
        $notifTitle,
        $notifBody,
        $notifAction,
        $reservationId,
        $userId
    ]);

    // Add notification recipients - all active staff
    $staffStmt = $pdo->prepare("
        SELECT ua.user_id FROM user_accounts ua
        JOIN user_roles ur ON ua.role_id = ur.role_id
        WHERE ur.role_code IN ('super_admin', 'admin', 'front_desk')
        AND ua.account_status = 'active'
    ");
    $staffStmt->execute();
    $staffMembers = $staffStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($staffMembers as $staff) {
        $recipStmt = $pdo->prepare("
            INSERT INTO notification_recipients (
                recipient_id, notif_id, user_id, is_sent, sent_at, delivery_status
            ) VALUES (?, ?, ?, 1, NOW(), 'delivered')
        ");
        $recipId = 'recip-' . uniqid();
        $recipStmt->execute([$recipId, $notifId, $staff['user_id']]);
    }

    // Get room pricing details from form
    $priceNight = floatval($_POST['price_per_night'] ?? 0);
    $priceHour = floatval($_POST['price_per_hour'] ?? 0);
    $numNights = intval($_POST['num_nights'] ?? 0);
    $numHours = floatval($_POST['num_hours'] ?? 0);
    $extraAdultFee = floatval($_POST['extra_adult_fee'] ?? 0);
    $extraChildFee = floatval($_POST['extra_child_fee'] ?? 0);

    // Create reservation room entry
    $resRoomId = 'rr-' . uniqid();
    $resRoomStmt = $pdo->prepare("
        INSERT INTO reservation_rooms (
            res_room_id, reservation_id, category_id, room_id,
            booking_type, price_per_night, price_per_hour,
            num_nights, num_hours, extra_adult_fee, extra_child_fee,
            room_total, room_status, created_at, updated_at
        ) VALUES (
            ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?,
            ?, 'reserved', NOW(), NOW()
        )
    ");

    $resRoomStmt->execute([
        $resRoomId,
        $reservationId,
        $_POST['room_category_id'],
        $_POST['room_id'] ?? null,
        $_POST['booking_type'] ?? 'day_use',
        $priceNight,
        $priceHour,
        $numNights,
        $numHours,
        $extraAdultFee,
        $extraChildFee,
        $totalAmount
    ]);

    $pdo->commit();

    // Send confirmation email
    try {
        $emailService = new EmailService($pdo);
        $emailResult = $emailService->sendReservationConfirmation([
            'reservation_code' => $reservationCode,
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'visit_date' => $_POST['visit_date'],
            'visit_time' => $_POST['visit_time'],
            'num_adults' => $_POST['num_adults'] ?? 1,
            'num_children' => $_POST['num_children'] ?? 0,
            'payment_method' => $paymentMethod['method_label'] ?? 'N/A',
            'payment_option' => $paymentOption,
            'total_amount' => $totalAmount,
            'amount_required' => $requiredPayment,
            'amount_paid' => $amountPaid,
            'payment_status' => $paymentStatus,
            'transaction_reference' => $_POST['transaction_reference'] ?? null
        ]);
        
        if (!$emailResult['success']) {
            error_log("Failed to send confirmation email: " . $emailResult['message']);
        }
    } catch (Exception $e) {
        error_log("Failed to send confirmation email: " . $e->getMessage());
    }

    // Return success response with redirect URL
    echo json_encode([
        'success' => true,
        'reservation_id' => $reservationId,
        'reservation_code' => $reservationCode,
        'message' => 'Your reservation has been created successfully!',
        'redirect_url' => '../pages/schedule-visit.php'
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Schedule visit API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
