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

    // Get amounts and promo data
    $totalAmount = floatval($_POST['total_amount'] ?? $_POST['calculated_total'] ?? 0);
    $promoDiscount = floatval($_POST['promo_discount'] ?? 0);
    $promoId = $_POST['promo_id'] ?? null;
    $promoCode = $_POST['promo_code'] ?? null;

    // Calculate subtotal (amount before discount)
    $subtotalAmount = $totalAmount + $promoDiscount;

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
            num_adults, num_children, num_seniors, special_requests,
            source_channel, subtotal_amount, discount_amount, total_amount, paid_amount, balance_amount, created_at, updated_at
        ) VALUES (
            :reservation_id, :reservation_code, :property_id, :customer_user_id,
            :booking_type, 'pending', :payment_status,
            :check_in_date, :check_in_time, :check_out_date, :check_out_time,
            :first_name, :last_name, :email, :phone,
            :num_adults, :num_children, :num_seniors, :special_requests,
            'website', :subtotal_amount, :discount_amount, :total_amount, :paid_amount, :balance_amount, NOW(), NOW()
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
        ':num_seniors' => intval($_POST['num_seniors'] ?? 0),
        ':special_requests' => $_POST['message'] ?? '',
        ':subtotal_amount' => $subtotalAmount,
        ':discount_amount' => $promoDiscount,
        ':total_amount' => $totalAmount,
        ':paid_amount' => $amountPaid,
        ':balance_amount' => $balanceAmount
    ]);

    // Add primary guest to reservation_guests table
    $guestId = 'guest-' . uniqid();
    $guestStmt = $pdo->prepare("
        INSERT INTO reservation_guests (
            res_guest_id, reservation_id, guest_type, first_name, last_name,
            age, gender, nationality, id_type, id_number, created_at, updated_at
        ) VALUES (?, ?, 'primary', ?, ?, NULL, NULL, NULL, NULL, NULL, NOW(), NOW())
    ");
    $guestStmt->execute([
        $guestId,
        $reservationId,
        $_POST['first_name'],
        $_POST['last_name']
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

    // Resolve room_id — use submitted value or find any available room for this category
    $resolvedRoomId = !empty($_POST['room_id']) ? $_POST['room_id'] : null;
    if (!$resolvedRoomId) {
        $roomLookupStmt = $pdo->prepare("
            SELECT room_id FROM rooms
            WHERE category_id = ? AND property_id = ? AND is_active = 1 AND room_status = 'available'
            ORDER BY room_number ASC
            LIMIT 1
        ");
        $roomLookupStmt->execute([$_POST['room_category_id'], $_POST['property_id']]);
        $roomRow = $roomLookupStmt->fetch(PDO::FETCH_ASSOC);
        if ($roomRow) {
            $resolvedRoomId = $roomRow['room_id'];
        } else {
            throw new Exception('No available room found for the selected category. Please contact us for assistance.');
        }
    }

    // Get room pricing details from form
    $priceNight = floatval($_POST['price_per_night'] ?? 0);
    $priceHour = floatval($_POST['price_per_hour'] ?? 0);
    $numNights = intval($_POST['num_nights'] ?? 0);
    $numHours = floatval($_POST['num_hours'] ?? 0);
    $extraAdultFee = floatval($_POST['extra_adult_fee'] ?? 0);
    $extraChildFee = floatval($_POST['extra_child_fee'] ?? 0);
    $extraSeniorFee = floatval($_POST['extra_senior_fee'] ?? 0);

    // Create reservation room entry
    $resRoomId = 'rr-' . uniqid();
    $resRoomStmt = $pdo->prepare("
        INSERT INTO reservation_rooms (
            res_room_id, reservation_id, category_id, room_id,
            booking_type, price_per_night, price_per_hour,
            num_nights, num_hours, extra_adult_fee, extra_child_fee, extra_senior_fee,
            room_total, room_status, created_at, updated_at
        ) VALUES (
            ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?, ?,
            ?, 'reserved', NOW(), NOW()
        )
    ");

    $resRoomStmt->execute([
        $resRoomId,
        $reservationId,
        $_POST['room_category_id'],
        $resolvedRoomId,
        $_POST['booking_type'] ?? 'day_use',
        $priceNight,
        $priceHour,
        $numNights,
        $numHours,
        $extraAdultFee,
        $extraChildFee,
        $extraSeniorFee,
        $totalAmount
    ]);

    // Create food order if food items were selected
    $foodOrderId = null;
    $foodOrderCode = null;
    if (!empty($_POST['food_items'])) {
        $foodItems = json_decode($_POST['food_items'], true);
        if ($foodItems && is_array($foodItems) && count($foodItems) > 0) {
            // Generate food order number
            $year = date('Y');
            $foStmt = $pdo->prepare("SELECT COUNT(*) as count FROM food_orders WHERE YEAR(created_at) = ?");
            $foStmt->execute([$year]);
            $foCount = $foStmt->fetch()['count'] + 1;
            $foodOrderCode = 'FO-' . $year . str_pad($foCount, 6, '0', STR_PAD_LEFT);
            
            // Calculate food totals
            $foodSubtotal = 0;
            foreach ($foodItems as $item) {
                $foodSubtotal += $item['price'] * $item['qty'];
            }
            $foodTax = $foodSubtotal * 0.12; // 12% tax
            $foodTotal = $foodSubtotal + $foodTax;
            
            // Create food order linked to reservation
            $foodOrderId = 'fo-' . uniqid();
            $foStmt = $pdo->prepare("
                INSERT INTO food_orders (
                    order_id, property_id, reservation_id, order_number, order_type,
                    customer_name, order_status, subtotal, tax_amount, total_amount,
                    payment_status, notes, created_at, updated_at
                ) VALUES (?, ?, ?, ?, 'room_service', ?, 'pending', ?, ?, ?, 'unpaid', ?, NOW(), NOW())
            ");
            $foStmt->execute([
                $foodOrderId,
                $_POST['property_id'],
                $reservationId,
                $foodOrderCode,
                $_POST['first_name'] . ' ' . $_POST['last_name'],
                $foodSubtotal,
                $foodTax,
                $foodTotal,
                'Pre-order for reservation ' . $reservationCode
            ]);
            
            // Insert food order items
            foreach ($foodItems as $item) {
                $foiId = 'foi-' . uniqid();
                $foiStmt = $pdo->prepare("
                    INSERT INTO food_order_items (
                        order_item_id, order_id, item_id, item_name, quantity, unit_price, total_price
                    ) VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $foiStmt->execute([
                    $foiId,
                    $foodOrderId,
                    $item['item_id'],
                    $item['name'],
                    $item['qty'],
                    $item['price'],
                    $item['price'] * $item['qty']
                ]);
            }
        }
    }

    // Seed reservation requirements based on booking type
    $bookingType = $_POST['booking_type'] ?? 'day_use';
    $reqTypesStmt = $pdo->prepare("
        SELECT req_type_id, req_label, description, is_mandatory
        FROM reservation_requirement_types
        WHERE is_active = 1
          AND (property_id = ? OR property_id IS NULL)
          AND FIND_IN_SET(?, applies_to)
        ORDER BY sort_order ASC
    ");
    $reqTypesStmt->execute([$_POST['property_id'], $bookingType]);
    $reqTypes = $reqTypesStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($reqTypes as $reqType) {
        $reqId = 'req-' . uniqid();
        $reqInsertStmt = $pdo->prepare("
            INSERT INTO reservation_requirements (
                req_id, reservation_id, req_type_id, res_guest_id,
                status, created_at, updated_at
            ) VALUES (?, ?, ?, ?, 'pending', NOW(), NOW())
        ");
        $reqInsertStmt->execute([$reqId, $reservationId, $reqType['req_type_id'], $guestId]);
    }

    // Create pricing breakdown entry for base rate
    if ($subtotalAmount > 0) {
        $baseBreakdownId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
            mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
            mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

        $pbStmt = $pdo->prepare("
            INSERT INTO reservation_pricing_breakdown
            (breakdown_id, reservation_id, res_room_id, component_type, component_name,
             base_amount, final_amount, sort_order, is_visible)
            VALUES (?,?,?,?,?,?,?,?,?)
        ");
        $pbStmt->execute([
            $baseBreakdownId,
            $reservationId,
            $resRoomId ?? null,
            'base_rate',
            'Base Room Rate',
            $subtotalAmount,
            $subtotalAmount,
            0,
            1
        ]);

        $currentAmount = $subtotalAmount;
        $sortOrder = 1;

        // Check for seasonal rates
        $seasonStmt = $pdo->prepare("
            SELECT season_id, season_name, rate_multiplier, applies_to
            FROM seasonal_rates
            WHERE property_id = ?
            AND is_active = 1
            AND date_start <= ?
            AND date_end >= ?
            AND (applies_to IS NULL OR applies_to = ?)
            ORDER BY rate_multiplier DESC
            LIMIT 1
        ");
        $seasonStmt->execute([$_POST['property_id'], $_POST['visit_date'], $_POST['visit_date'], $_POST['room_category_id'] ?? null]);
        $season = $seasonStmt->fetch(PDO::FETCH_ASSOC);

        if ($season && $season['rate_multiplier'] != 1.0) {
            $multiplier = floatval($season['rate_multiplier']);
            $adjustedAmount = $currentAmount * $multiplier;

            $seasonBreakdownId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
                mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
                mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

            $pbStmt->execute([
                $seasonBreakdownId,
                $reservationId,
                $resRoomId ?? null,
                'seasonal_rate',
                $season['season_name'],
                $currentAmount,
                $adjustedAmount,
                $sortOrder++,
                1
            ]);

            // Update with proper references
            $updateStmt = $pdo->prepare("
                UPDATE reservation_pricing_breakdown
                SET season_id = ?, multiplier = ?, final_amount = ?
                WHERE breakdown_id = ?
            ");
            $updateStmt->execute([$season['season_id'], $multiplier, $adjustedAmount, $seasonBreakdownId]);

            $currentAmount = $adjustedAmount;
        }

        // Check for pricing rules
        $dayOfWeek = strtolower(date('D', strtotime($_POST['visit_date']))); // mon, tue, etc
        $rulesStmt = $pdo->prepare("
            SELECT rule_id, rule_name, rule_type, rule_value, applies_to
            FROM pricing_rules
            WHERE property_id = ?
            AND is_active = 1
            AND (category_id IS NULL OR category_id = ?)
            AND (applies_to = 'all' OR applies_to = ?)
            AND (date_start IS NULL OR date_start <= ?)
            AND (date_end IS NULL OR date_end >= ?)
            AND (day_of_week IS NULL OR FIND_IN_SET(?, day_of_week))
            ORDER BY priority DESC
        ");
        $rulesStmt->execute([
            $_POST['property_id'],
            $_POST['room_category_id'] ?? null,
            $_POST['booking_type'] ?? 'day_use',
            $_POST['visit_date'],
            $_POST['visit_date'],
            $dayOfWeek
        ]);
        $rules = $rulesStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rules as $rule) {
            $ruleValue = floatval($rule['rule_value']);
            $adjustedAmount = $currentAmount;
            $discountAmount = 0;
            $multiplier = null;

            switch ($rule['rule_type']) {
                case 'percentage_add':
                    $multiplier = 1 + ($ruleValue / 100);
                    $adjustedAmount = $currentAmount * $multiplier;
                    break;
                case 'percentage_discount':
                    $discountAmount = $currentAmount * ($ruleValue / 100);
                    $adjustedAmount = $currentAmount - $discountAmount;
                    break;
                case 'fixed_add':
                    $adjustedAmount = $currentAmount + $ruleValue;
                    break;
                case 'fixed_discount':
                    $discountAmount = $ruleValue;
                    $adjustedAmount = $currentAmount - $discountAmount;
                    break;
                default:
                    continue 2;
            }

            if ($adjustedAmount < 0) {
                $adjustedAmount = 0;
            }

            $ruleBreakdownId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
                mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
                mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

            $pbStmt->execute([
                $ruleBreakdownId,
                $reservationId,
                $resRoomId ?? null,
                'pricing_rule',
                $rule['rule_name'],
                $currentAmount,
                $adjustedAmount,
                $sortOrder++,
                1
            ]);

            // Update with proper references
            $updateStmt = $pdo->prepare("
                UPDATE reservation_pricing_breakdown
                SET rule_id = ?, discount_amount = ?, final_amount = ?
                WHERE breakdown_id = ?
            ");
            $updateStmt->execute([$rule['rule_id'], $discountAmount, $adjustedAmount, $ruleBreakdownId]);

            $currentAmount = $adjustedAmount;
        }

        // Add promo discount to pricing breakdown if applicable
        if ($promoDiscount > 0 && $promoId) {
            $promoLookup = $pdo->prepare("SELECT promo_title, promo_code FROM promotions WHERE promo_id = ?");
            $promoLookup->execute([$promoId]);
            $promoData = $promoLookup->fetch(PDO::FETCH_ASSOC);

            $promoBreakdownId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
                mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
                mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

            $pbStmt->execute([
                $promoBreakdownId,
                $reservationId,
                $resRoomId ?? null,
                'promotion',
                ($promoData['promo_title'] ?? 'Promo Discount') . ($promoData['promo_code'] ? ' (' . $promoData['promo_code'] . ')' : ''),
                0,
                -$promoDiscount,
                99,
                1
            ]);

            // Update with promo reference
            $updateStmt = $pdo->prepare("
                UPDATE reservation_pricing_breakdown
                SET promo_id = ?, discount_amount = ?, final_amount = ?
                WHERE breakdown_id = ?
            ");
            $updateStmt->execute([$promoId, $promoDiscount, -$promoDiscount, $promoBreakdownId]);

            // Create reservation_promotions entry
            $resPromoId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
                mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
                mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

            $rpStmt = $pdo->prepare("
                INSERT INTO reservation_promotions
                (res_promo_id, reservation_id, promo_id, promo_code_used, discount_amount,
                 discount_type, discount_value, validated_by, source_channel, is_applied)
                VALUES (?,?,?,?,?,?,?,?,?,?)
            ");
            $rpStmt->execute([
                $resPromoId,
                $reservationId,
                $promoId,
                $promoCode,
                $promoDiscount,
                'percentage',
                10,
                $userId,
                'website',
                1
            ]);
        }
    }

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
            'num_seniors' => intval($_POST['num_seniors'] ?? 0),
            'payment_method' => $paymentMethod['method_label'] ?? 'N/A',
            'payment_option' => $paymentOption,
            'total_amount' => $totalAmount,
            'amount_required' => $requiredPayment,
            'amount_paid' => $amountPaid,
            'payment_status' => $paymentStatus,
            'transaction_reference' => $_POST['transaction_reference'] ?? null,
            'requirements' => $reqTypes
        ]);
        
        if (!$emailResult['success']) {
            error_log("Failed to send confirmation email: " . $emailResult['message']);
        }
    } catch (Exception $e) {
        error_log("Failed to send confirmation email: " . $e->getMessage());
    }

    // Build success response
    $response = [
        'success' => true,
        'reservation_id' => $reservationId,
        'reservation_code' => $reservationCode,
        'message' => 'Your reservation has been created successfully!',
        'redirect_url' => '../pages/schedule-visit.php'
    ];
    
    // Include food order info if created
    if ($foodOrderId && $foodOrderCode) {
        $response['food_order_id'] = $foodOrderId;
        $response['food_order_code'] = $foodOrderCode;
        $response['message'] = 'Your reservation and food pre-order have been created successfully!';
    }
    
    echo json_encode($response);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Schedule visit API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
