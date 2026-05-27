<?php
require_once 'config/config.php';

// Fetch company information and properties
$companyInfo = null;
$properties = [];
$preselectedPropertyId = $_GET['property_id'] ?? null;
$preselectedRoomId = $_GET['room_id'] ?? null;
$preselectedCategoryId = $_GET['room_category_id'] ?? null;
$preselectedVisitDate = $_GET['visit_date'] ?? null;
$preselectedVisitTime = $_GET['visit_time'] ?? '14:00';
$preselectedCheckoutDate = $_GET['checkout_date'] ?? null;
$preselectedCheckoutTime = $_GET['checkout_time'] ?? '12:00';
$preselectedNumAdults = $_GET['num_adults'] ?? '1';

try {
  $stmt = $pdo->prepare("SELECT * FROM company_information WHERE is_active = 1 LIMIT 1");
  $stmt->execute();
  $companyInfo = $stmt->fetch();

  $propStmt = $pdo->query("
        SELECT 
            p.property_id,
            p.property_name,
            p.property_type,
            brg.barangay_name,
            cm.city_municipality_name,
            prov.province_name
        FROM properties p
        LEFT JOIN psgc_barangays brg ON p.barangay_code = brg.barangay_code COLLATE utf8mb4_unicode_ci
        LEFT JOIN psgc_cities_municipalities cm ON p.city_municipality_code = cm.city_municipality_code COLLATE utf8mb4_unicode_ci
        LEFT JOIN psgc_provinces prov ON p.province_code = prov.province_code COLLATE utf8mb4_unicode_ci
        WHERE p.is_active = 1
        ORDER BY p.property_name
    ");
  $properties = $propStmt->fetchAll(PDO::FETCH_ASSOC);

  // Validate preselected property if provided
  if ($preselectedPropertyId) {
    $validProperty = false;
    foreach ($properties as $prop) {
      if ($prop['property_id'] === $preselectedPropertyId) {
        $validProperty = true;
        break;
      }
    }
    if (!$validProperty) {
      $preselectedPropertyId = null;
    }
  }

  // Derive room_category_id from room_id if needed (for "Book This Room" flow)
  if ($preselectedRoomId && !$preselectedCategoryId) {
    $roomCatStmt = $pdo->prepare("
      SELECT r.category_id, r.property_id 
      FROM rooms r 
      WHERE r.room_id = ? AND r.is_active = 1
    ");
    $roomCatStmt->execute([$preselectedRoomId]);
    $roomData = $roomCatStmt->fetch();
    if ($roomData) {
      $preselectedCategoryId = $roomData['category_id'];
      // Also set property if not already set
      if (!$preselectedPropertyId) {
        $preselectedPropertyId = $roomData['property_id'];
      }
    }
  }

  // Fetch room categories for preselected property (for dropdown population)
  $preselectedCategories = [];
  $preselectedCategoryData = null;
  if ($preselectedPropertyId) {
    $catStmt = $pdo->prepare("
      SELECT 
        category_id, category_code, category_name, description,
        base_price_day_use, base_price_per_night, base_price_per_hour,
        minimum_hours,
        max_adult_capacity, max_child_capacity, max_total_capacity,
        extra_adult_fee, extra_child_fee,
        allows_overnight, allows_day_use, allows_hourly,
        bed_configuration, view_type
      FROM room_categories 
      WHERE property_id = ? AND is_active = 1
      ORDER BY base_price_day_use ASC, category_name ASC
    ");
    $catStmt->execute([$preselectedPropertyId]);
    $preselectedCategories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get specific category data if preselected
    if ($preselectedCategoryId) {
      foreach ($preselectedCategories as $cat) {
        if ($cat['category_id'] === $preselectedCategoryId) {
          $preselectedCategoryData = $cat;
          break;
        }
      }
    }
  }
} catch (PDOException $e) {
  error_log("Failed to fetch data: " . $e->getMessage());
  $preselectedCategories = [];
  $preselectedCategoryData = null;
}

$companyName = $companyInfo['company_name'] ?? 'M&M Paraiso';
$phone = $companyInfo['phone_number'] ?? '+63 XXX XXX XXXX';
$email = $companyInfo['email'] ?? 'info@company.com';

// Fetch payment settings and methods
$paymentSettings = null;
$paymentMethods = [];
try {
  // Get global payment settings (or property-specific if needed)
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

  // Default settings if none configured
  if (!$paymentSettings) {
    $paymentSettings = [
      'payment_type' => 'percentage',
      'payment_value' => 30.00,
      'min_payment_required' => 500.00,
      'allow_full_payment' => 1,
      'allow_partial_payment' => 1
    ];
  }

  // Get active payment methods
  $methodsStmt = $pdo->query("
    SELECT method_id, method_code, method_label, method_type
    FROM payment_methods 
    WHERE is_active = 1
    ORDER BY sort_order, method_label
  ");
  $paymentMethods = $methodsStmt->fetchAll(PDO::FETCH_ASSOC);

  // Get booking type settings (for preselected property if available)
  $bookingTypeSettings = null;
  $hourlyRates = [];
  if ($preselectedPropertyId) {
    $bookingSettingsStmt = $pdo->prepare("
      SELECT 
        hourly_min_hours, hourly_max_hours,
        overnight_min_hours, overnight_requires_midnight,
        day_use_max_hours, hourly_presets, hourly_rates
      FROM booking_type_settings
      WHERE (property_id = ? OR property_id IS NULL)
      AND is_active = 1
      ORDER BY property_id DESC
      LIMIT 1
    ");
    $bookingSettingsStmt->execute([$preselectedPropertyId]);
    $bookingTypeSettings = $bookingSettingsStmt->fetch();

    // Parse hourly rates
    if ($bookingTypeSettings && !empty($bookingTypeSettings['hourly_rates'])) {
      $hourlyRates = json_decode($bookingTypeSettings['hourly_rates'], true);
    }
  }

  // Default booking type settings if none found
  if (!$bookingTypeSettings) {
    $bookingTypeSettings = [
      'hourly_min_hours' => 3,
      'hourly_max_hours' => 12,
      'overnight_min_hours' => 12,
      'overnight_requires_midnight' => 1,
      'day_use_max_hours' => 12,
      'hourly_presets' => '[3,4,5,6,7]',
      'hourly_rates' => '{"3": 500.00, "4": 650.00, "5": 800.00, "6": 950.00, "7": 1100.00}'
    ];
    $hourlyRates = json_decode($bookingTypeSettings['hourly_rates'], true);
  }

  // Parse hourly presets
  $hourlyPresets = json_decode($bookingTypeSettings['hourly_presets'] ?? '[3,4,5,6,7]', true);
  if (!is_array($hourlyPresets)) {
    $hourlyPresets = [3, 4, 5, 6, 7];
  }

  // Parse hourly rates (duration-specific pricing)
  if (empty($hourlyRates)) {
    $hourlyRates = json_decode($bookingTypeSettings['hourly_rates'] ?? '{}', true);
    if (!is_array($hourlyRates)) {
      $hourlyRates = [];
    }
  }
} catch (PDOException $e) {
  error_log("Failed to fetch payment data: " . $e->getMessage());
  // Set defaults
  $bookingTypeSettings = [
    'hourly_min_hours' => 3,
    'hourly_max_hours' => 12,
    'overnight_min_hours' => 12,
    'overnight_requires_midnight' => 1,
    'day_use_max_hours' => 12,
    'hourly_presets' => '[3,4,5,6,7]',
    'hourly_rates' => '{"3": 500.00, "4": 650.00, "5": 800.00, "6": 950.00, "7": 1100.00}'
  ];
  $hourlyPresets = [3, 4, 5, 6, 7];
  $hourlyRates = json_decode($bookingTypeSettings['hourly_rates'], true);
  // Provide defaults
  $paymentSettings = [
    'payment_type' => 'percentage',
    'payment_value' => 30.00,
    'min_payment_required' => 500.00,
    'allow_full_payment' => 1,
    'allow_partial_payment' => 1
  ];
  $paymentMethods = [];
}

// Handle form submission
$success = false;
$error = '';
$reservationCode = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
      throw new Exception('Invalid security token. Please try again.');
    }

    // Validate required fields
    $required = ['first_name', 'last_name', 'email', 'phone', 'visit_date', 'visit_time'];
    foreach ($required as $field) {
      if (empty($_POST[$field])) {
        throw new Exception(ucwords(str_replace('_', ' ', $field)) . ' is required');
      }
    }

    // Validate email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      throw new Exception('Please enter a valid email address');
    }

    // Validate phone (basic format)
    $phone = preg_replace('/[^0-9+]/', '', $_POST['phone']);
    if (strlen($phone) < 10) {
      throw new Exception('Please enter a valid phone number');
    }

    // Validate date is not in the past
    $visitDate = new DateTime($_POST['visit_date']);
    $today = new DateTime();
    $today->setTime(0, 0, 0);
    if ($visitDate < $today) {
      throw new Exception('Visit date cannot be in the past');
    }

    // Validate checkout date if provided
    if (!empty($_POST['checkout_date'])) {
      $checkoutDate = new DateTime($_POST['checkout_date']);
      if ($checkoutDate < $visitDate) {
        throw new Exception('Checkout date cannot be before visit date');
      }
      // If same date, validate checkout time is after visit time
      if ($_POST['checkout_date'] === $_POST['visit_date']) {
        $visitTime = strtotime($_POST['visit_time']);
        $checkoutTime = strtotime($_POST['checkout_time'] ?? '12:00');
        if ($checkoutTime <= $visitTime) {
          throw new Exception('Checkout time must be after visit time for same-day visits');
        }
      }
    }

    // Validate property
    if (empty($_POST['property_id'])) {
      throw new Exception('Please select a property');
    }

    // Get property operating hours and validate time
    $propStmt = $pdo->prepare("SELECT earliest_check_in, latest_check_out FROM properties WHERE property_id = ? AND is_active = 1");
    $propStmt->execute([$_POST['property_id']]);
    $property = $propStmt->fetch();

    if (!$property) {
      throw new Exception('Invalid property selected');
    }

    // Validate room category
    if (empty($_POST['room_category_id'])) {
      throw new Exception('Please select a room category');
    }

    // Verify room category belongs to selected property and get capacity info
    $catCheckStmt = $pdo->prepare("
      SELECT category_id, max_adult_capacity, max_child_capacity, extra_adult_fee, extra_child_fee
      FROM room_categories 
      WHERE category_id = ? AND property_id = ? AND is_active = 1
    ");
    $catCheckStmt->execute([$_POST['room_category_id'], $_POST['property_id']]);
    $categoryData = $catCheckStmt->fetch();
    if (!$categoryData) {
      throw new Exception('Invalid room category selected for this property');
    }

    // Validate guest count against capacity and extra fees
    $numAdults = intval($_POST['num_adults'] ?? 1);
    $numChildren = intval($_POST['num_children'] ?? 0);
    $maxAdults = intval($categoryData['max_adult_capacity']);
    $maxChildren = intval($categoryData['max_child_capacity']);
    $extraAdultFee = floatval($categoryData['extra_adult_fee']);
    $extraChildFee = floatval($categoryData['extra_child_fee']);

    // Check if extra adults exceed capacity without configured fee
    if ($numAdults > $maxAdults && $extraAdultFee <= 0) {
      $extraAdults = $numAdults - $maxAdults;
      throw new Exception("Guest count error: {$extraAdults} extra adult(s) exceeds capacity (max {$maxAdults}), but no extra adult fee is configured. Please contact us for assistance.");
    }

    // Check if extra children exceed capacity without configured fee
    if ($numChildren > $maxChildren && $extraChildFee <= 0) {
      $extraChildren = $numChildren - $maxChildren;
      throw new Exception("Guest count error: {$extraChildren} extra child(ren) exceeds capacity (max {$maxChildren}), but no extra child fee is configured. Please contact us for assistance.");
    }

    $selectedTime = strtotime($_POST['visit_time']);
    $earliestTime = strtotime($property['earliest_check_in'] ?? '08:00:00');
    $latestTime = strtotime($property['latest_check_out'] ?? '22:00:00');

    if ($selectedTime < $earliestTime || $selectedTime > $latestTime) {
      throw new Exception('Visit time must be between ' . substr($property['earliest_check_in'], 0, 5) . ' and ' . substr($property['latest_check_out'], 0, 5));
    }

    // Check for availability blocks
    $blockStmt = $pdo->prepare("
            SELECT 
                rab.block_type,
                rab.reason,
                rab.time_start,
                rab.time_end
            FROM room_availability_blocks rab
            INNER JOIN rooms r ON rab.room_id = r.room_id
            WHERE r.property_id = ?
            AND r.is_active = 1
            AND rab.is_active = 1
            AND rab.date_start <= ?
            AND rab.date_end >= ?
        ");
    $blockStmt->execute([$_POST['property_id'], $_POST['visit_date'], $_POST['visit_date']]);
    $blocks = $blockStmt->fetchAll(PDO::FETCH_ASSOC);

    // If there are blocks covering the visit time, prevent booking
    foreach ($blocks as $block) {
      // If no time specified on block, entire day is blocked
      if (!$block['time_start'] || !$block['time_end']) {
        throw new Exception('This date is unavailable due to: ' . $block['reason'] . ' (' . $block['block_type'] . ')');
      }
      // If visit time falls within block time range
      $visitTime = strtotime($_POST['visit_time']);
      $blockStart = strtotime($block['time_start']);
      $blockEnd = strtotime($block['time_end']);
      if ($visitTime >= $blockStart && $visitTime <= $blockEnd) {
        throw new Exception('This time slot is unavailable due to: ' . $block['reason'] . ' (' . $block['block_type'] . ' ' . substr($block['time_start'], 0, 5) . '-' . substr($block['time_end'], 0, 5) . ')');
      }
    }

    // Check existing bookings at room level with date overlap detection
    $overlapCondition = "";
    $params = [$_POST['property_id']];

    if (!empty($_POST['checkout_date'])) {
      // For date range bookings (overnight, day_use), check for overlap
      $overlapCondition = "AND (
        (r.check_in_date < ? AND r.check_out_date > ?) OR
        (r.check_in_date < ? AND r.check_out_date > ?) OR
        (r.check_in_date >= ? AND r.check_out_date <= ?)
      )";
      $params = array_merge($params, [$_POST['checkout_date'], $_POST['visit_date'], $_POST['visit_date'], $_POST['checkout_date'], $_POST['visit_date'], $_POST['checkout_date']]);
    } else {
      // If no checkout_date provided, check both:
      // 1. Exact time match for hourly/day_use bookings
      // 2. Overnight bookings that span the visit_date
      $overlapCondition = "AND (
        (r.check_in_date = ? AND r.check_in_time = ?) OR
        (r.booking_type = 'overnight' AND r.check_in_date <= ? AND r.check_out_date > ?)
      )";
      $params = array_merge($params, [$_POST['visit_date'], $_POST['visit_time'], $_POST['visit_date'], $_POST['visit_date']]);
    }

    $resStmt = $pdo->prepare("
      SELECT DISTINCT rr.room_id
      FROM reservation_rooms rr
      INNER JOIN reservations r ON rr.reservation_id = r.reservation_id
      WHERE r.property_id = ?
      $overlapCondition
      AND r.reservation_status IN ('pending', 'confirmed')
      AND r.booking_type IN ('hourly', 'day_use', 'overnight')
    ");
    $resStmt->execute($params);
    $bookedRoomIds = $resStmt->fetchAll(PDO::FETCH_COLUMN);

    // If a specific room is selected, check if it's booked
    if (!empty($_POST['room_id']) && in_array($_POST['room_id'], $bookedRoomIds)) {
      throw new Exception('This room is already booked for the selected dates. Please select another room or different dates.');
    }

    // If only a category is selected, check if any room in that category is available
    if (!empty($_POST['room_category_id']) && empty($_POST['room_id'])) {
      $categoryRoomsStmt = $pdo->prepare("
        SELECT room_id FROM rooms
        WHERE category_id = ? AND property_id = ? AND is_active = 1 AND room_status = 'available'
      ");
      $categoryRoomsStmt->execute([$_POST['room_category_id'], $_POST['property_id']]);
      $categoryRoomIds = $categoryRoomsStmt->fetchAll(PDO::FETCH_COLUMN);

      // Filter out booked rooms
      $availableRoomIds = array_diff($categoryRoomIds, $bookedRoomIds);

      if (empty($availableRoomIds)) {
        throw new Exception('No rooms available in this category for the selected dates. Please select another category or different dates.');
      }
    }

    // ============================================
    // PAYMENT VALIDATION
    // ============================================

    // Validate payment option
    if (empty($_POST['payment_option'])) {
      throw new Exception('Please select a payment option (partial or full)');
    }
    if (!in_array($_POST['payment_option'], ['partial', 'full'])) {
      throw new Exception('Invalid payment option selected');
    }

    // Validate payment method
    if (empty($_POST['payment_method'])) {
      throw new Exception('Please select a payment method');
    }

    // Get payment method details
    $methodStmt = $pdo->prepare("
      SELECT method_id, method_code, method_label, method_type
      FROM payment_methods 
      WHERE method_code = ? AND is_active = 1
    ");
    $methodStmt->execute([$_POST['payment_method']]);
    $paymentMethod = $methodStmt->fetch();

    if (!$paymentMethod) {
      throw new Exception('Invalid payment method selected');
    }

    // Determine if proof is required (cash methods don't require proof)
    $paymentMethod['requires_proof'] = ($paymentMethod['method_type'] !== 'cash');

    // Get property payment settings
    $settingsStmt = $pdo->prepare("
      SELECT payment_type, payment_value, min_payment_required, 
             allow_full_payment, allow_partial_payment
      FROM payment_settings 
      WHERE (property_id = ? OR property_id IS NULL) 
      AND is_active = 1 
      ORDER BY property_id DESC 
      LIMIT 1
    ");
    $settingsStmt->execute([$_POST['property_id']]);
    $paymentSettings = $settingsStmt->fetch();

    // Default settings if none configured
    if (!$paymentSettings) {
      $paymentSettings = [
        'payment_type' => 'percentage',
        'payment_value' => 30.00,
        'min_payment_required' => 500.00,
        'allow_full_payment' => 1,
        'allow_partial_payment' => 1
      ];
    }

    // Get calculated total amount from form (calculated by JavaScript based on room category)
    $totalAmount = floatval($_POST['total_amount'] ?? 0);
    if ($totalAmount <= 0) {
      throw new Exception('Invalid total amount. Please select a room category and verify guest counts.');
    }

    // Calculate required payment amount
    if ($_POST['payment_option'] === 'partial') {
      if (!$paymentSettings['allow_partial_payment']) {
        throw new Exception('Partial payment is not allowed. Please select full payment.');
      }
      if ($paymentSettings['payment_type'] === 'percentage') {
        $requiredPayment = $totalAmount * ($paymentSettings['payment_value'] / 100);
      } else {
        $requiredPayment = $paymentSettings['payment_value'];
      }
      // Ensure minimum payment
      $requiredPayment = max($requiredPayment, $paymentSettings['min_payment_required']);
    } else {
      if (!$paymentSettings['allow_full_payment']) {
        throw new Exception('Full payment is not allowed. Please select partial payment.');
      }
      $requiredPayment = $totalAmount;
    }

    // Validate proof of payment upload for methods that require it
    if ($paymentMethod['requires_proof']) {
      if ($_POST['payment_method'] !== 'cash') {
        if (!isset($_FILES['proof_of_payment']) || $_FILES['proof_of_payment']['error'] === UPLOAD_ERR_NO_FILE) {
          throw new Exception('Please upload proof of payment');
        }

        // Validate file upload
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if ($_FILES['proof_of_payment']['error'] !== UPLOAD_ERR_OK) {
          throw new Exception('File upload error. Please try again.');
        }

        if (!in_array($_FILES['proof_of_payment']['type'], $allowedTypes)) {
          throw new Exception('Invalid file type. Please upload JPG, PNG, GIF, or PDF.');
        }

        if ($_FILES['proof_of_payment']['size'] > $maxSize) {
          throw new Exception('File is too large. Maximum size is 5MB.');
        }
      }
    }

    // Get payment account for this property and method
    $accountStmt = $pdo->prepare("
      SELECT pa.account_id, pa.account_name, pa.account_number, pa.bank_name
      FROM payment_accounts pa
      WHERE pa.property_id = ? 
      AND pa.method_id = ?
      AND pa.is_active = 1
      ORDER BY pa.is_default DESC
      LIMIT 1
    ");
    $accountStmt->execute([$_POST['property_id'], $paymentMethod['method_id']]);
    $paymentAccount = $accountStmt->fetch();

    // Generate UUID for reservation
    $reservationId = sprintf(
      '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff),
      mt_rand(0, 0x0fff) | 0x4000,
      mt_rand(0, 0x3fff) | 0x8000,
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff)
    );

    // Start transaction for atomic booking creation
    $pdo->beginTransaction();

    try {
      // Generate reservation code
      $year = date('Y');
      $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM reservations WHERE YEAR(created_at) = ?");
      $stmt->execute([$year]);
      $result = $stmt->fetch();
      $count = ($result['count'] ?? 0) + 1;
      $reservationCode = 'RSV-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

      // ============================================
      // CHECK FOR EXISTING USER
      // ============================================
      // Look up user by email (and optionally verify name matches)
      $customerUserId = null;
      $userStmt = $pdo->prepare("
        SELECT user_id, first_name, last_name 
        FROM user_accounts 
        WHERE email = ? AND is_active = 1
        LIMIT 1
      ");
      $userStmt->execute([$_POST['email']]);
      $existingUser = $userStmt->fetch();

      if ($existingUser) {
        // Optional: Verify name matches (case-insensitive)
        $inputFirstName = strtolower(trim($_POST['first_name']));
        $inputLastName = strtolower(trim($_POST['last_name']));
        $dbFirstName = strtolower(trim($existingUser['first_name']));
        $dbLastName = strtolower(trim($existingUser['last_name']));

        // If names match, link to existing user
        if ($inputFirstName === $dbFirstName && $inputLastName === $dbLastName) {
          $customerUserId = $existingUser['user_id'];
          error_log("Linked reservation to existing user: {$customerUserId} ({$_POST['email']})");
        } else {
          // Email exists but name differs - treat as guest (could be same email, different person)
          error_log("Email match but name mismatch for: {$_POST['email']}. Treating as guest booking.");
        }
      }

      // ============================================
      // INSERT RESERVATION
      // ============================================
      $stmt = $pdo->prepare("
                INSERT INTO reservations (
                    reservation_id, reservation_code, property_id, customer_user_id,
                    booking_type, reservation_status, payment_status,
                    check_in_date, check_in_time, check_out_date, check_out_time,
                    walkin_first_name, walkin_last_name, walkin_email, walkin_phone,
                    num_adults, num_children, special_requests,
                    source_channel, total_amount, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ");

      $stmt->execute([
        $reservationId,
        $reservationCode,
        $_POST['property_id'],
        $customerUserId, // NULL if guest, user_id if existing account found
        $_POST['booking_type'] ?? 'day_use',
        'pending',
        'unpaid',
        $_POST['visit_date'],
        $_POST['visit_time'],
        $_POST['checkout_date'] ?? null,
        $_POST['checkout_time'] ?? null,
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['num_adults'] ?? 1,
        $_POST['num_children'] ?? 0,
        ($_POST['visit_purpose'] ?? '') . ($_POST['message'] ? ': ' . $_POST['message'] : ''),
        'website',
        floatval($_POST['total_amount'] ?? 0)
      ]);

      // Insert reservation_rooms if room or category is specified
      if (!empty($_POST['room_id']) || !empty($_POST['room_category_id'])) {
        $resRoomId = sprintf(
          '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0, 0xffff),
          mt_rand(0, 0xffff),
          mt_rand(0, 0xffff),
          mt_rand(0, 0x0fff) | 0x4000,
          mt_rand(0, 0x3fff) | 0x8000,
          mt_rand(0, 0xffff),
          mt_rand(0, 0xffff),
          mt_rand(0, 0xffff)
        );

        // Get category_id from room if room_id provided, otherwise use room_category_id
        $categoryId = $_POST['room_category_id'] ?? null;
        $roomId = $_POST['room_id'] ?? null;

        if ($roomId && !$categoryId) {
          $catStmt = $pdo->prepare("SELECT category_id FROM rooms WHERE room_id = ?");
          $catStmt->execute([$roomId]);
          $categoryId = $catStmt->fetch()['category_id'] ?? null;
        }

        // Only insert if we have category_id (room_id is optional)
        if ($categoryId) {
          // Get category pricing snapshot
          $catPriceStmt = $pdo->prepare("
            SELECT base_price_day_use, base_price_per_night, base_price_per_hour
            FROM room_categories WHERE category_id = ?
          ");
          $catPriceStmt->execute([$categoryId]);
          $catPrice = $catPriceStmt->fetch();

          // Use room_id if available, otherwise NULL (category-only booking)
          $actualRoomId = $roomId ?: null;

          // Get booking type and nights/hours from form
          $bookingType = $_POST['booking_type'] ?? 'day_use';
          $numNights = intval($_POST['num_nights'] ?? 0);

          $roomStmt = $pdo->prepare("
            INSERT INTO reservation_rooms (
              res_room_id, reservation_id, room_id, category_id,
              booking_type, price_per_night, price_per_hour,
              num_nights, room_total, room_status, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'reserved', NOW(), NOW())
          ");
          $roomStmt->execute([
            $resRoomId,
            $reservationId,
            $actualRoomId,
            $categoryId,
            $bookingType,
            $catPrice['base_price_per_night'] ?? 0.00,
            $catPrice['base_price_per_hour'] ?? 0.00,
            $numNights > 0 ? $numNights : 1,
            floatval($_POST['total_amount'] ?? 0)
          ]);
        } // end if ($categoryId)
      } // end if (!empty($room_id) || !empty($room_category_id))

      $pdo->commit();
      $success = true;

      // Send confirmation email and admin notification
      sendReservationConfirmation([
        'reservation_id'        => $reservationId,
        'reservation_code'      => $reservationCode,
        'property_id'           => $_POST['property_id'],
        'first_name'            => $_POST['first_name'],
        'last_name'             => $_POST['last_name'],
        'email'                 => $_POST['email'],
        'phone'                 => $phone,
        'visit_date'            => $_POST['visit_date'],
        'visit_time'            => $_POST['visit_time'],
        'num_adults'            => $_POST['num_adults'] ?? 1,
        'visit_purpose'         => $_POST['visit_purpose'] ?? '',
        'message'               => $_POST['message'] ?? '',
        'payment_method'        => $_POST['payment_method'] ?? 'N/A',
        'payment_option'        => $_POST['payment_option'] ?? 'N/A',
        'total_amount'          => floatval($_POST['total_amount'] ?? 0),
        'amount_required'       => $requiredPayment ?? 0,
        'amount_paid'           => floatval($_POST['amount_paid'] ?? 0),
        'payment_status'        => 'pending',
        'transaction_reference' => $_POST['transaction_reference'] ?? '',
        'has_attachment'        => isset($_FILES['proof_of_payment']) && $_FILES['proof_of_payment']['error'] === UPLOAD_ERR_OK,
      ]);

      createAdminNotification([
        'reservation_id'   => $reservationId,
        'reservation_code' => $reservationCode,
        'property_id'      => $_POST['property_id'],
        'first_name'       => $_POST['first_name'],
        'last_name'        => $_POST['last_name'],
        'email'            => $_POST['email'],
        'phone'            => $phone,
        'visit_date'       => $_POST['visit_date'],
        'visit_time'       => $_POST['visit_time'],
        'payment_method'   => $_POST['payment_method'] ?? 'N/A',
        'payment_option'   => $_POST['payment_option'] ?? 'N/A',
        'total_amount'     => floatval($_POST['total_amount'] ?? 0),
        'amount_required'  => $requiredPayment ?? 0,
        'amount_paid'      => floatval($_POST['amount_paid'] ?? 0),
        'payment_status'   => 'pending',
        'has_attachment'   => isset($_FILES['proof_of_payment']) && $_FILES['proof_of_payment']['error'] === UPLOAD_ERR_OK,
      ]);
    } catch (Exception $e) {
      $pdo->rollBack();
      throw $e; // re-throw to outer catch
    }
  } catch (Exception $e) {
    $error = $e->getMessage();
    error_log("Reservation submission error: " . $e->getMessage());
  }
} // end if (POST)


function sendReservationConfirmation($data)
{
  global $pdo, $companyName, $phone, $email;

  // Format purpose
  $purposes = [
    'family_day' => 'Family Day / Recreation',
    'team_building' => 'Corporate Team Building',
    'event' => 'Private Event / Celebration',
    'photo_shoot' => 'Photo / Video Shoot',
    'other' => 'Other Purpose'
  ];
  $purpose = $purposes[$data['visit_purpose']] ?? $data['visit_purpose'];
  $formattedMessage = nl2br(htmlspecialchars($data['message'] ?? 'None'));

  // Pre-compute all variables for email template (heredoc only supports simple variable interpolation)
  $paymentMethod = htmlspecialchars($data['payment_method'] ?? 'N/A');
  $paymentOption = ucfirst(htmlspecialchars($data['payment_option'] ?? 'N/A'));
  $totalAmount = number_format($data['total_amount'] ?? 0, 2);
  $amountRequired = number_format($data['amount_required'] ?? 0, 2);
  $amountPaid = number_format($data['amount_paid'] ?? 0, 2);
  $paymentStatus = ucfirst(htmlspecialchars($data['payment_status'] ?? 'pending'));
  $phoneSafe = htmlspecialchars($data['phone']);
  $companyPhoneSafe = htmlspecialchars($phone);
  $companyEmailSafe = htmlspecialchars($email);
  $companyNameSafe = htmlspecialchars($companyName);
  $txnRef = !empty($data['transaction_reference']) ? "<p style='margin:0 0 8px;font-size:14px;'><strong>Reference Number:</strong> " . htmlspecialchars($data['transaction_reference']) . "</p>" : '';
  $companyPhone = htmlspecialchars($phone);
  $companyEmail = htmlspecialchars($email);
  $year = date('Y');

  // Full HTML email template
  $body = <<<HTML
                <h3 style="margin:0 0 15px;font-size:16px;color:#2e7d32;"><i class="fa fa-credit-card"></i> Payment Information</h3>
                <p style="margin:0 0 8px;font-size:14px;"><strong>Payment Method:</strong> {$paymentMethod}</p>
                <p style="margin:0 0 8px;font-size:14px;"><strong>Payment Option:</strong> {$paymentOption}</p>
                <p style="margin:0 0 8px;font-size:14px;"><strong>Total Amount:</strong> ₱{$totalAmount}</p>
                <p style="margin:0 0 8px;font-size:14px;"><strong>Amount Required:</strong> ₱{$amountRequired}</p>
                <p style="margin:0 0 8px;font-size:14px;"><strong>Amount Paid:</strong> ₱{$amountPaid}</p>
                {$txnRef}
                <p style="margin:8px 0 0;font-size:13px;color:#666;"><strong>Status:</strong> <span style="color:#f35525;">{$paymentStatus}</span> (Awaiting admin confirmation)</p>
              </div>

              <p style="margin:20px 0;font-size:14px;color:#666;line-height:1.6;">
                We will contact you at <strong>{$phoneSafe}</strong> to confirm your visit details and payment.
              </p>

              <div style="background:#fff8f6;border-left:4px solid #f35525;padding:15px 20px;margin:20px 0;">
                <p style="margin:0;font-size:13px;color:#666;"><strong>Additional Details:</strong><br>{$formattedMessage}</p>
              </div>
            </td>
          </tr>
          <tr>
            <td style="padding:0 40px 28px;">
              <p style="margin:0;font-size:13px;color:#888;">If you need to modify or cancel your visit, please contact us at {$companyPhone} or reply to this email.</p>
            </td>
          </tr>
          <tr>
            <td style="background:#1e1e1e;padding:20px 40px;text-align:center;border-radius:0 0 12px 12px;">
              <p style="margin:0 0 6px;color:#aaaaaa;font-size:12px;">&copy; {$year} {$companyNameSafe}. All rights reserved.</p>
              <p style="margin:0;color:#666666;font-size:11px;">{$companyEmail} | {$companyPhone}</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  </body>
  </html>
HTML;

  error_log("Reservation confirmation prepared for: {$data['email']}");
}


function createAdminNotification($data)
{
  global $pdo;

  try {
    // Get property name
    $propStmt = $pdo->prepare("SELECT property_name FROM properties WHERE property_id = ?");
    $propStmt->execute([$data['property_id']]);
    $propertyName = $propStmt->fetch()['property_name'] ?? 'Unknown Property';

    // Generate notification ID
    $notifId = sprintf(
      '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff),
      mt_rand(0, 0x0fff) | 0x4000,
      mt_rand(0, 0x3fff) | 0x8000,
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff)
    );

    // Create notification
    $stmt = $pdo->prepare("
            INSERT INTO notifications (
                notif_id, property_id, notif_type, title, body,
                action_url, reference_type, reference_id, status, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'unread', NOW())
        ");

    $title = 'New Visit Scheduled - ' . $data['reservation_code'];
    $body = sprintf(
      "%s %s scheduled a visit at %s on %s at %s.\n\nContact: %s / %s\n\nPAYMENT INFO:\nMethod: %s\nOption: %s\nTotal: ₱%s\nRequired: ₱%s\nPaid: ₱%s\nStatus: %s%s",
      $data['first_name'],
      $data['last_name'],
      $propertyName,
      date('M j, Y', strtotime($data['visit_date'])),
      date('g:i A', strtotime($data['visit_time'])),
      $data['phone'],
      $data['email'],
      $data['payment_method'] ?? 'N/A',
      ucfirst($data['payment_option'] ?? 'N/A'),
      number_format($data['total_amount'] ?? 0, 2),
      number_format($data['amount_required'] ?? 0, 2),
      number_format($data['amount_paid'] ?? 0, 2),
      ucfirst($data['payment_status'] ?? 'pending'),
      ($data['has_attachment'] ? '\n📎 Proof of payment attached' : '')
    );

    $actionUrl = '/build/views/reservations/index.php?reservation_id=' . $data['reservation_id'];

    $stmt->execute([
      $notifId,
      $data['property_id'],
      'reservation',
      $title,
      $body,
      $actionUrl,
      'reservation',
      $data['reservation_id']
    ]);

    error_log("Admin notification created for reservation: {$data['reservation_code']}");
  } catch (Exception $e) {
    error_log("Failed to create admin notification: {$e->getMessage()}");
    // Don't throw - notification failure shouldn't break the booking
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('structure/head.php'); ?>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <title>Schedule a Visit - <?php echo htmlspecialchars($companyName ?? 'M&M Paraiso'); ?></title>

</head>

<body>

  <!-- ***** Preloader Start ***** -->
  <?php include('structure/preloader.php'); ?>
  <!-- ***** Preloader End ***** -->

  <?php include('structure/subheader.php'); ?>

  <!-- ***** Header Area Start ***** -->
  <?php include('structure/header.php'); ?>
  <!-- ***** Header Area End ***** -->

  <div class="page-heading header-text">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <span class="breadcrumb"><a href="index.php">Home</a> / Schedule a Visit</span>
          <h3>Schedule a Visit</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="contact-page section">
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <div class="section-heading">
            <h6>| Schedule a Visit</h6>
            <h2 style="color: #f35525; font-weight: 700; line-height: 1.3; white-space: nowrap;">Book Your Room<br>Visit Today</h2>
            <p style="line-height: 1.6; color: #666; margin-top: 10px; white-space: nowrap;">Interested in seeing our property in person? <br> Schedule a visit and experience the beauty of <?php echo htmlspecialchars($companyName ?? 'M&M Paraiso'); ?> <br> firsthand.</p>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="item phone">
                <img src="assets/images/phone-icon.png" alt="" style="max-width: 52px;">
                <h6><?php echo htmlspecialchars($phone ?? '+63 XXX XXX XXXX'); ?><br><span>Phone Number</span></h6>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="item email">
                <img src="assets/images/email-icon.png" alt="" style="max-width: 52px;">
                <h6><?php echo htmlspecialchars($email ?? 'info@company.com'); ?><br><span>Business Email</span></h6>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="card shadow">
            <div class="card-body">
              <?php if ($success): ?>
                <div class="alert alert-success">
                  <h4 class="alert-heading">✓ Visit Scheduled Successfully!</h4>
                  <p class="mb-2">Thank you for your interest in <?php echo htmlspecialchars($companyName); ?>.</p>
                  <p class="mb-2"><strong>Reservation Code:</strong> <span class="fs-5 fw-bold"><?php echo htmlspecialchars($reservationCode); ?></span></p>
                  <p class="mb-2">We will contact you at <?php echo htmlspecialchars($_POST['email']); ?> to confirm your visit.</p>
                  <hr>
                  <p class="mb-0"><a href="schedule-visit.php" class="alert-link">Schedule another visit</a></p>
                </div>
              <?php elseif ($error): ?>
                <div class="alert alert-danger">
                  <h4 class="alert-heading">⚠ Error</h4>
                  <p class="mb-0"><?php echo htmlspecialchars($error); ?></p>
                </div>
              <?php endif; ?>

              <?php if (!$success): ?>
                <h4 class="card-title mb-4">Schedule Your Visit</h4>
                <form id="schedule-visit-form" action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

                  <!-- Room Hidden Field (if coming from "Book This Room") -->
                  <?php if ($preselectedRoomId): ?>
                    <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($preselectedRoomId); ?>">
                  <?php endif; ?>

                  <!-- Room/Category Info Display -->
                  <?php if ($preselectedRoomId || $preselectedCategoryId): ?>
                    <div class="alert alert-info mb-4">
                      <?php if ($preselectedRoomId): ?>
                        <?php
                        // Fetch room info for display
                        $roomStmt = $pdo->prepare("
                            SELECT r.room_number, r.room_name, rc.category_name, p.property_name
                            FROM rooms r
                            INNER JOIN room_categories rc ON r.category_id = rc.category_id
                            INNER JOIN properties p ON r.property_id = p.property_id
                            WHERE r.room_id = ? AND r.is_active = 1
                        ");
                        $roomStmt->execute([$preselectedRoomId]);
                        $roomInfo = $roomStmt->fetch();
                        ?>
                        <?php if ($roomInfo): ?>
                          <strong>Booking for Room:</strong> <?php echo htmlspecialchars($roomInfo['room_name'] ?: $roomInfo['room_number']); ?>
                          (<?php echo htmlspecialchars($roomInfo['category_name']); ?> at <?php echo htmlspecialchars($roomInfo['property_name']); ?>)
                        <?php endif; ?>
                      <?php elseif ($preselectedCategoryData): ?>
                        <strong>Booking for Category:</strong> <?php echo htmlspecialchars($preselectedCategoryData['category_name']); ?>
                        <?php
                        // Get property name from preselected properties list
                        $propName = '';
                        foreach ($properties as $prop) {
                          if ($prop['property_id'] === $preselectedPropertyId) {
                            $propName = $prop['property_name'];
                            break;
                          }
                        }
                        ?>
                        (<?php echo htmlspecialchars($propName); ?>)
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>

                  <div class="row">
                    <!-- Booking Details Section -->
                    <div class="col-lg-12 mb-4">
                      <h5 class="mb-3" style="color: #f35525;">Booking Details</h5>
                      <div class="card" style="border: 1px solid #e0e0e0; border-radius: 8px;">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-lg-12 mb-3">
                              <label for="property_id" class="form-label">Select Property *</label>
                              <select name="property_id" id="property_id" class="form-select" required>
                                <option value="">-- Choose a Property --</option>
                                <?php foreach ($properties as $prop): ?>
                                  <option value="<?php echo htmlspecialchars($prop['property_id']); ?>" <?php echo $preselectedPropertyId === $prop['property_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($prop['property_name']); ?> (<?php echo htmlspecialchars($prop['city_municipality_name'] ?? $prop['province_name'] ?? 'N/A'); ?>)
                                  </option>
                                <?php endforeach; ?>
                                <?php if (empty($properties)): ?>
                                  <option value="" disabled>No properties available</option>
                                <?php endif; ?>
                              </select>
                            </div>

                            <!-- Room Category Selection -->
                            <div class="col-lg-12 mb-3">
                              <label for="room_category_id" class="form-label">Select Room Category *</label>
                              <select name="room_category_id" id="room_category_id" class="form-select" required>
                                <?php if ($preselectedPropertyId && !empty($preselectedCategories)): ?>
                                  <option value="">-- Select Room Category --</option>
                                  <?php foreach ($preselectedCategories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat['category_id']); ?>"
                                      data-base-price-day="<?php echo floatval($cat['base_price_day_use']); ?>"
                                      data-base-price-night="<?php echo floatval($cat['base_price_per_night']); ?>"
                                      data-base-price-hour="<?php echo floatval($cat['base_price_per_hour']); ?>"
                                      data-minimum-hours="<?php echo intval($cat['minimum_hours'] ?? 2); ?>"
                                      data-max-adults="<?php echo intval($cat['max_adult_capacity']); ?>"
                                      data-max-children="<?php echo intval($cat['max_child_capacity']); ?>"
                                      data-extra-adult-fee="<?php echo floatval($cat['extra_adult_fee']); ?>"
                                      data-extra-child-fee="<?php echo floatval($cat['extra_child_fee']); ?>"
                                      data-allows-overnight="<?php echo $cat['allows_overnight'] ? '1' : '0'; ?>"
                                      data-allows-day-use="<?php echo $cat['allows_day_use'] ? '1' : '0'; ?>"
                                      data-allows-hourly="<?php echo $cat['allows_hourly'] ? '1' : '0'; ?>"
                                      <?php echo $preselectedCategoryId === $cat['category_id'] ? 'selected' : ''; ?>>
                                      <?php echo htmlspecialchars($cat['category_name']); ?> - ₱<?php echo number_format($cat['base_price_day_use'], 2); ?>/day
                                      (<?php echo $cat['max_adult_capacity']; ?> Adults, <?php echo $cat['max_child_capacity']; ?> Children)
                                    </option>
                                  <?php endforeach; ?>
                                <?php else: ?>
                                  <option value="">-- Select a Property First --</option>
                                <?php endif; ?>
                              </select>
                              <div id="categoryDetails" class="form-text" style="display: <?php echo $preselectedCategoryData ? 'block' : 'none'; ?>;">
                                <?php if ($preselectedCategoryData): ?>
                                  <strong>Capacity:</strong> <?php echo $preselectedCategoryData['max_adult_capacity']; ?> Adults, <?php echo $preselectedCategoryData['max_child_capacity']; ?> Children
                                  <?php if ($preselectedCategoryData['extra_adult_fee'] > 0 || $preselectedCategoryData['extra_child_fee'] > 0): ?>
                                    | <strong>Extra: Adult +₱<?php echo number_format($preselectedCategoryData['extra_adult_fee'], 2); ?>, Child +₱<?php echo number_format($preselectedCategoryData['extra_child_fee'], 2); ?></strong>
                                  <?php endif; ?>
                                  <?php if ($preselectedCategoryData['bed_configuration']): ?>
                                    <br><strong>Bed:</strong> <?php echo htmlspecialchars($preselectedCategoryData['bed_configuration']); ?>
                                  <?php endif; ?>
                                  <?php if ($preselectedCategoryData['view_type']): ?>
                                    | <strong>View:</strong> <?php echo htmlspecialchars($preselectedCategoryData['view_type']); ?>
                                  <?php endif; ?>
                                <?php endif; ?>
                              </div>
                              <!-- Hidden inputs for pricing calculation - populated via PHP if preselected -->
                              <input type="hidden" id="categoryBasePriceDay" value="<?php echo $preselectedCategoryData ? floatval($preselectedCategoryData['base_price_day_use']) : '0'; ?>">
                              <input type="hidden" id="categoryBasePriceNight" value="<?php echo $preselectedCategoryData ? floatval($preselectedCategoryData['base_price_per_night']) : '0'; ?>">
                              <input type="hidden" id="categoryBasePriceHour" value="<?php echo $preselectedCategoryData ? floatval($preselectedCategoryData['base_price_per_hour']) : '0'; ?>">
                              <input type="hidden" id="categoryMinimumHours" value="<?php echo $preselectedCategoryData ? intval($preselectedCategoryData['minimum_hours'] ?? 2) : '2'; ?>">
                              <input type="hidden" id="categoryMaxAdults" value="<?php echo $preselectedCategoryData ? intval($preselectedCategoryData['max_adult_capacity']) : '2'; ?>">
                              <input type="hidden" id="categoryMaxChildren" value="<?php echo $preselectedCategoryData ? intval($preselectedCategoryData['max_child_capacity']) : '2'; ?>">
                              <input type="hidden" id="categoryExtraAdultFee" value="<?php echo $preselectedCategoryData ? floatval($preselectedCategoryData['extra_adult_fee']) : '0'; ?>">
                              <input type="hidden" id="categoryExtraChildFee" value="<?php echo $preselectedCategoryData ? floatval($preselectedCategoryData['extra_child_fee']) : '0'; ?>">
                              <input type="hidden" id="categoryAllowsOvernight" value="<?php echo $preselectedCategoryData ? ($preselectedCategoryData['allows_overnight'] ? '1' : '0') : '1'; ?>">
                              <input type="hidden" id="categoryAllowsDayUse" value="<?php echo $preselectedCategoryData ? ($preselectedCategoryData['allows_day_use'] ? '1' : '0') : '1'; ?>">
                              <input type="hidden" id="categoryAllowsHourly" value="<?php echo $preselectedCategoryData ? ($preselectedCategoryData['allows_hourly'] ? '1' : '0') : '0'; ?>">
                              <input type="hidden" id="bookingType" value="day_use">
                              <!-- Booking Type Settings (database-driven) -->
                              <input type="hidden" id="dbHourlyMinHours" value="<?php echo intval($bookingTypeSettings['hourly_min_hours'] ?? 3); ?>">
                              <input type="hidden" id="dbHourlyMaxHours" value="<?php echo intval($bookingTypeSettings['hourly_max_hours'] ?? 12); ?>">
                              <input type="hidden" id="dbOvernightMinHours" value="<?php echo intval($bookingTypeSettings['overnight_min_hours'] ?? 12); ?>">
                              <input type="hidden" id="dbOvernightRequiresMidnight" value="<?php echo $bookingTypeSettings['overnight_requires_midnight'] ? '1' : '0'; ?>">
                              <input type="hidden" id="dbDayUseMaxHours" value="<?php echo intval($bookingTypeSettings['day_use_max_hours'] ?? 12); ?>">

                              <!-- Pass preselected categories data to JS -->
                              <script>
                                window.preselectedCategories = <?php echo json_encode(array_map(function ($cat) {
                                                                  return [
                                                                    'category_id' => $cat['category_id'],
                                                                    'category_name' => $cat['category_name'],
                                                                    'base_price_day_use' => floatval($cat['base_price_day_use']),
                                                                    'base_price_per_night' => floatval($cat['base_price_per_night']),
                                                                    'base_price_per_hour' => floatval($cat['base_price_per_hour']),
                                                                    'base_price_day_use_formatted' => '₱' . number_format($cat['base_price_day_use'], 2),
                                                                    'base_price_per_night_formatted' => '₱' . number_format($cat['base_price_per_night'], 2),
                                                                    'base_price_per_hour_formatted' => '₱' . number_format($cat['base_price_per_hour'], 2),
                                                                    'minimum_hours' => intval($cat['minimum_hours'] ?? 2),
                                                                    'max_adult_capacity' => intval($cat['max_adult_capacity']),
                                                                    'max_child_capacity' => intval($cat['max_child_capacity']),
                                                                    'extra_adult_fee' => floatval($cat['extra_adult_fee']),
                                                                    'extra_child_fee' => floatval($cat['extra_child_fee']),
                                                                    'allows_overnight' => boolval($cat['allows_overnight']),
                                                                    'allows_day_use' => boolval($cat['allows_day_use']),
                                                                    'allows_hourly' => boolval($cat['allows_hourly']),
                                                                    'bed_configuration' => $cat['bed_configuration'],
                                                                    'view_type' => $cat['view_type'],
                                                                    'capacity_display' => $cat['max_adult_capacity'] . ' Adults, ' . $cat['max_child_capacity'] . ' Children',
                                                                    'extra_fees_display' => ($cat['extra_adult_fee'] > 0 || $cat['extra_child_fee'] > 0)
                                                                      ? 'Extra: Adult +₱' . number_format($cat['extra_adult_fee'], 2) . ', Child +₱' . number_format($cat['extra_child_fee'], 2)
                                                                      : null
                                                                  ];
                                                                }, $preselectedCategories)); ?>;

                                // Pass booking type settings to JS
                                window.bookingTypeSettings = {
                                  hourly_min_hours: <?php echo intval($bookingTypeSettings['hourly_min_hours'] ?? 3); ?>,
                                  hourly_max_hours: <?php echo intval($bookingTypeSettings['hourly_max_hours'] ?? 12); ?>,
                                  overnight_min_hours: <?php echo intval($bookingTypeSettings['overnight_min_hours'] ?? 12); ?>,
                                  overnight_requires_midnight: <?php echo $bookingTypeSettings['overnight_requires_midnight'] ? 'true' : 'false'; ?>,
                                  day_use_max_hours: <?php echo intval($bookingTypeSettings['day_use_max_hours'] ?? 12); ?>,
                                  hourly_presets: <?php echo json_encode($hourlyPresets); ?>,
                                  hourly_rates: <?php echo json_encode($hourlyRates); ?>
                                };
                              </script>
                            </div>
                            <div class="col-lg-3 mb-3">
                              <label for="visit_date" class="form-label">Check-in Date *</label>
                              <input type="date" name="visit_date" id="visit_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>" value="<?php echo htmlspecialchars($preselectedVisitDate); ?>">
                            </div>
                            <div class="col-lg-3 mb-3">
                              <label for="visit_time" class="form-label">Check-in Time *</label>
                              <select name="visit_time" id="visit_time" class="form-select" required>
                                <option value="">-- Select Time --</option>
                                <?php for ($h = 6; $h <= 23; $h++): ?>
                                  <?php $timeValue = sprintf('%02d:00', $h); ?>
                                  <?php $timeLabel = ($h < 12) ? $h . ':00 AM' : (($h == 12) ? '12:00 PM' : ($h - 12) . ':00 PM'); ?>
                                  <option value="<?php echo $timeValue; ?>" <?php echo $preselectedVisitTime == $timeValue ? 'selected' : ''; ?>><?php echo $timeLabel; ?></option>
                                <?php endfor; ?>
                              </select>
                              <div id="operatingHours" class="form-text" style="display: none;"></div>
                            </div>
                            <div class="col-lg-3 mb-3">
                              <label for="checkout_date" class="form-label">Check-out Date *</label>
                              <input type="date" name="checkout_date" id="checkout_date" class="form-control" required value="<?php echo htmlspecialchars($preselectedCheckoutDate); ?>">
                            </div>
                            <div class="col-lg-3 mb-3">
                              <label for="checkout_time" class="form-label">Check-out Time *</label>
                              <select name="checkout_time" id="checkout_time" class="form-select" required>
                                <option value="">-- Select Time --</option>
                                <?php for ($h = 6; $h <= 23; $h++): ?>
                                  <?php $timeValue = sprintf('%02d:00', $h); ?>
                                  <?php $timeLabel = ($h < 12) ? $h . ':00 AM' : (($h == 12) ? '12:00 PM' : ($h - 12) . ':00 PM'); ?>
                                  <option value="<?php echo $timeValue; ?>" <?php echo $preselectedCheckoutTime == $timeValue ? 'selected' : ''; ?>><?php echo $timeLabel; ?></option>
                                <?php endfor; ?>
                              </select>
                            </div>

                            <!-- Quick Duration Presets (for Hourly Bookings) -->
                            <div class="col-lg-12 mb-3" id="hourlyDurationPresets" style="display: none;">
                              <label class="form-label">Quick Duration (Hourly)</label>
                              <div class="d-flex gap-2 flex-wrap" id="hourlyPresetsContainer">
                                <?php foreach ($hourlyPresets as $presetHours): ?>
                                  <button type="button" class="btn btn-outline-primary btn-sm" onclick="setDurationHours(<?php echo intval($presetHours); ?>)"><?php echo intval($presetHours); ?> Hours</button>
                                <?php endforeach; ?>
                              </div>
                              <div class="form-text text-muted">Minimum: <?php echo intval($bookingTypeSettings['hourly_min_hours'] ?? 3); ?> hours | Select a preset or set custom times above</div>
                            </div>

                            <!-- Booking Type Validation Alert -->
                            <div class="col-lg-12 mb-3" id="bookingTypeValidationAlert" style="display: none;">
                              <div class="alert alert-warning mb-0">
                                <div id="bookingTypeAlertContent"></div>
                              </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                              <label for="num_adults" class="form-label">Number of Adults *</label>
                              <input type="number" name="num_adults" id="num_adults" class="form-control" value="<?php echo htmlspecialchars($preselectedNumAdults); ?>" min="1" max="20" required>
                            </div>
                            <div class="col-lg-6 mb-3">
                              <label for="num_children" class="form-label">Number of Children</label>
                              <input type="number" name="num_children" id="num_children" class="form-control" value="0" min="0" max="20">
                            </div>

                            <!-- Availability Alert -->
                            <div class="col-lg-12 mb-3" id="availabilityAlert" style="display: none;">
                              <div class="alert alert-info mb-0">
                                <div id="availabilityContent"></div>
                              </div>
                            </div>

                            <!-- Capacity Validation Alert -->
                            <div class="col-lg-12 mb-3" id="capacityValidationAlert" style="display: none;">
                              <div class="alert alert-warning mb-0">
                                <div id="capacityAlertContent"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="col-lg-12 mb-4">
                      <h5 class="mb-3" style="color: #f35525;">Personal Information</h5>
                      <div class="card" style="border: 1px solid #e0e0e0; border-radius: 8px;">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-lg-6 mb-3">
                              <label for="first_name" class="form-label">First Name *</label>
                              <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Your First Name..." autocomplete="given-name" required>
                            </div>
                            <div class="col-lg-6 mb-3">
                              <label for="last_name" class="form-label">Last Name *</label>
                              <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Your Last Name..." autocomplete="family-name" required>
                            </div>
                            <div class="col-lg-6 mb-3">
                              <label for="email" class="form-label">Email Address *</label>
                              <input type="email" name="email" id="email" class="form-control" placeholder="your.email@example.com" autocomplete="email" required>
                            </div>
                            <div class="col-lg-6 mb-3">
                              <label for="phone" class="form-label">Phone Number *</label>
                              <input type="tel" name="phone" id="phone" class="form-control" placeholder="+63 9XX XXX XXXX" autocomplete="tel" required>
                              <div class="form-text">Format: +63 9XX XXX XXXX</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Additional Information Section -->
                    <div class="col-lg-12 mb-4">
                      <h5 class="mb-3" style="color: #f35525;">Additional Information</h5>
                      <div class="card" style="border: 1px solid #e0e0e0; border-radius: 8px;">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-lg-12 mb-3">
                              <label for="visit_purpose" class="form-label">Purpose of Visit *</label>
                              <select name="visit_purpose" id="visit_purpose" class="form-select" required>
                                <option value="">-- Select Purpose --</option>
                                <option value="Property Tour">Property Tour</option>
                                <option value="Booking Inquiry">Booking Inquiry</option>
                                <option value="Event Planning">Event Planning</option>
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Other">Other</option>
                              </select>
                            </div>
                            <div class="col-lg-12 mb-3">
                              <label for="message" class="form-label">Additional Details (Optional)</label>
                              <textarea name="message" id="message" class="form-control" rows="4" placeholder="Any special requests, questions, or additional information..."></textarea>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Payment Section -->
                    <div class="col-lg-12 mb-4">
                      <h5 class="mb-3" style="color: #f35525;">Payment</h5>
                      <div class="card" style="border: 1px solid #e0e0e0; border-radius: 8px;">
                        <div class="card-body">
                          <div class="row">
                            <!-- Total Amount Display -->
                            <div class="col-lg-12 mb-3" id="totalAmountSection" style="display: none;">
                              <div class="alert alert-primary">
                                <h6 class="mb-2"><i class="fa fa-calculator"></i> Booking Summary</h6>
                                <div id="bookingSummaryContent">
                                  <!-- Booking Type & Duration -->
                                  <div class="row mb-2">
                                    <div class="col-6"><strong>Booking Type:</strong></div>
                                    <div class="col-6 text-end">
                                      <span id="bookingTypeDisplay" class="badge bg-info">Day Use</span>
                                    </div>
                                  </div>
                                  <div class="row mb-2" id="durationRow">
                                    <div class="col-6">Duration:</div>
                                    <div class="col-6 text-end" id="durationDisplay">-</div>
                                  </div>
                                  <div class="row mb-2">
                                    <div class="col-6">Rate:</div>
                                    <div class="col-6 text-end" id="rateDisplay">₱0.00 per day</div>
                                  </div>
                                  <hr class="my-2">
                                  <!-- Base Rate Calculation -->
                                  <div class="row">
                                    <div class="col-6" id="baseRateLabel">Base Rate:</div>
                                    <div class="col-6 text-end" id="baseRateDisplay">₱0.00</div>
                                  </div>
                                  <div class="row" id="extraAdultsRow" style="display: none;">
                                    <div class="col-6">Extra Adults (<span id="extraAdultsCount">0</span>):</div>
                                    <div class="col-6 text-end" id="extraAdultsFee">+₱0.00</div>
                                  </div>
                                  <div class="row" id="extraChildrenRow" style="display: none;">
                                    <div class="col-6">Extra Children (<span id="extraChildrenCount">0</span>):</div>
                                    <div class="col-6 text-end" id="extraChildrenFee">+₱0.00</div>
                                  </div>
                                  <hr class="my-2">
                                  <div class="row fw-bold">
                                    <div class="col-6">Total Amount:</div>
                                    <div class="col-6 text-end" id="totalAmountDisplay" style="font-size: 1.2em; color: #f35525;">₱0.00</div>
                                  </div>
                                  <input type="hidden" name="total_amount" id="totalAmountInput" value="0">
                                  <input type="hidden" name="booking_type" id="bookingTypeInput" value="day_use">
                                  <input type="hidden" name="num_nights" id="numNightsInput" value="0">
                                  <input type="hidden" name="num_hours" id="numHoursInput" value="0">
                                </div>
                              </div>
                            </div>

                            <!-- Payment Amount Selection -->
                            <div class="col-lg-12 mb-3" id="paymentOptions">
                              <label class="form-label">Select Payment Option *</label>
                              <div class="alert alert-info mb-2">
                                <small><i class="fa fa-info-circle"></i> Payment is required to complete your booking. Choose to pay partial or full amount.</small>
                              </div>
                              <div class="d-flex gap-3 flex-wrap">
                                <?php if ($paymentSettings['allow_partial_payment']): ?>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_option" id="payPartial" value="partial" required
                                      <?php echo !$paymentSettings['allow_full_payment'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="payPartial">
                                      <strong>Pay Partial (<?php echo $paymentSettings['payment_type'] === 'percentage' ? intval($paymentSettings['payment_value']) . '%' : '₱' . number_format($paymentSettings['payment_value'], 2); ?>)</strong><br>
                                      <small class="text-muted" id="partialAmount">Calculated after selecting property</small>
                                    </label>
                                  </div>
                                <?php endif; ?>
                                <?php if ($paymentSettings['allow_full_payment']): ?>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_option" id="payFull" value="full"
                                      <?php echo !$paymentSettings['allow_partial_payment'] ? 'checked required' : ''; ?>>
                                    <label class="form-check-label" for="payFull">
                                      <strong>Pay Full Amount</strong><br>
                                      <small class="text-muted" id="fullAmount">Calculated after selecting property</small>
                                    </label>
                                  </div>
                                <?php endif; ?>
                              </div>
                              <!-- Hidden inputs for JS calculations -->
                              <input type="hidden" id="paymentType" value="<?php echo htmlspecialchars($paymentSettings['payment_type']); ?>">
                              <input type="hidden" id="paymentValue" value="<?php echo floatval($paymentSettings['payment_value']); ?>">
                              <input type="hidden" id="minPayment" value="<?php echo floatval($paymentSettings['min_payment_required']); ?>">
                            </div>

                            <!-- Amount to Pay -->
                            <div class="col-lg-12 mb-3" id="amountToPaySection" style="display: none;">
                              <div class="alert alert-success mb-0">
                                <!-- Partial: editable input -->
                                <div id="partialAmountInputWrapper" style="display: none;">
                                  <label for="customAmountInput" class="form-label mb-1">
                                    <strong><i class="fa fa-money"></i> Amount to Pay</strong>
                                    <small class="text-muted ms-1" id="partialAmountHint"></small>
                                  </label>
                                  <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" id="customAmountInput" class="form-control"
                                      step="0.01" min="0" placeholder="Enter amount">
                                  </div>
                                  <div id="customAmountError" class="text-danger mt-1" style="display:none; font-size: 0.85em;"></div>
                                </div>
                                <!-- Full: locked display -->
                                <div id="fullAmountDisplayWrapper" style="display: none;">
                                  <div class="row align-items-center">
                                    <div class="col-6">
                                      <strong><i class="fa fa-money"></i> Amount to Pay:</strong>
                                    </div>
                                    <div class="col-6 text-end">
                                      <span id="amountToPayDisplay" style="font-size: 1.3em; font-weight: bold; color: #155724;">₱0.00</span>
                                    </div>
                                  </div>
                                </div>
                                <input type="hidden" name="amount_paid" id="amountPaidInput" value="0">
                              </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="col-lg-12 mb-3">
                              <label for="payment_method" class="form-label">Payment Method *</label>
                              <select name="payment_method" id="payment_method" class="form-select" required>
                                <option value="">-- Select Payment Method --</option>
                                <?php foreach ($paymentMethods as $method): ?>
                                  <option value="<?php echo htmlspecialchars($method['method_code']); ?>">
                                    <?php echo htmlspecialchars($method['method_label']); ?>
                                    <?php if ($method['method_type'] === 'cash'): ?>(No upfront payment)<?php endif; ?>
                                  </option>
                                <?php endforeach; ?>
                              </select>
                            </div>

                            <!-- Payment Instructions Display -->
                            <div class="col-lg-12 mb-3" id="paymentInstructions" style="display: none;">
                              <div class="alert alert-warning">
                                <h6 class="alert-heading"><i class="fa fa-info-circle"></i> Payment Instructions</h6>
                                <div id="paymentInstructionsContent"></div>
                              </div>
                            </div>

                            <!-- QR Code Display -->
                            <div class="col-lg-12 mb-3 text-center" id="qrCodeContainer" style="display: none;">
                              <img id="qrCodeImage" src="" alt="Payment QR Code" class="img-fluid" style="max-width: 250px;">
                              <p class="text-muted mt-2"><small>Scan this QR code to pay</small></p>
                            </div>

                            <!-- Transaction Reference -->
                            <div class="col-lg-12 mb-3" id="referenceField" style="display: none;">
                              <label for="transaction_reference" class="form-label">Transaction Reference Number</label>
                              <input type="text" name="transaction_reference" id="transaction_reference" class="form-control" placeholder="e.g., 1234567890, TRX-ABC123">
                              <div class="form-text">Enter the reference/confirmation number from your payment</div>
                            </div>

                            <!-- Proof of Payment Upload -->
                            <div class="col-lg-12 mb-3" id="proofUploadField" style="display: none;">
                              <label for="proof_of_payment" class="form-label">Proof of Payment *</label>
                              <input type="file" name="proof_of_payment" id="proof_of_payment" class="form-control" accept="image/jpeg,image/png,image/gif,application/pdf">
                              <div class="form-text">
                                Upload screenshot or receipt (JPG, PNG, GIF, PDF - Max 5MB)
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Food & Beverages Section (Optional) -->
                    <div class="col-lg-12 mb-4">
                      <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0" style="cursor: pointer;" onclick="toggleFoodSection()">
                          <div class="d-flex justify-content-between align-items-center py-2">
                            <h6 class="mb-0"><i class="fa fa-cutlery me-2" style="color: #f35525;"></i>Food & Beverages <span class="text-muted">(Optional)</span></h6>
                            <div class="d-flex align-items-center gap-3">
                              <span id="foodSummary" class="text-muted small" style="display: none;"></span>
                              <i id="foodToggleIcon" class="fa fa-chevron-down text-muted"></i>
                            </div>
                          </div>
                        </div>
                        <div class="card-body" id="foodSection" style="display: none;">
                          <p class="text-muted small mb-3">Pre-order food to be ready for your arrival. You can skip this and order later.</p>
                          
                          <!-- Food Menu Container -->
                          <div id="foodMenuContainer">
                            <p class="text-muted text-center py-3">Select a property first to view available food items</p>
                          </div>
                          
                          <!-- Food Cart Summary -->
                          <div id="foodCartSummary" class="mt-3 pt-3 border-top" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center">
                              <span class="fw-bold">Food Subtotal:</span>
                              <span id="foodSubtotal" class="fw-bold" style="color: #f35525;">₱0.00</span>
                            </div>
                          </div>
                          
                          <!-- Hidden input for food items -->
                          <input type="hidden" name="food_items" id="foodItemsInput" value="">
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-12">
                      <button type="submit" id="form-submit" class="btn btn-primary w-100 py-3" style="background: #f35525; border: none; font-weight: 600;">Schedule Visit</button>
                    </div>
                  </div>
                </form>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Success Modal -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="successModalLabel">
            <i class="fa fa-check-circle text-success me-2"></i> Reservation Submitted!
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="successMessage">
          Your reservation has been received. We will contact you shortly to confirm your visit.
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <?php include('structure/footer.php'); ?>

  <!-- Scripts -->
  <?php include('structure/scripts.php'); ?>

  <script>
    // Availability check functionality
    const propertySelect = document.getElementById('property_id');
    const dateInput = document.getElementById('visit_date');
    const timeSelect = document.getElementById('visit_time');
    const availabilityAlert = document.getElementById('availabilityAlert');
    const availabilityContent = document.getElementById('availabilityContent');
    const operatingHoursDiv = document.getElementById('operatingHours');
    const submitButton = document.getElementById('form-submit');

    // Check availability when property, date, or time changes
    const checkoutDateInput = document.getElementById('checkout_date');
    const checkoutTimeSelect = document.getElementById('checkout_time');

    async function checkAvailability() {
      const propertyId = propertySelect.value;
      const visitDate = dateInput.value;
      const visitTime = timeSelect.value;
      const checkoutDate = checkoutDateInput?.value || '';
      const checkoutTime = checkoutTimeSelect?.value || '';

      // Clear previous alerts
      availabilityAlert.style.display = 'none';
      operatingHoursDiv.style.display = 'none';
      submitButton.disabled = false;
      submitButton.textContent = 'Schedule Visit';

      if (!propertyId || !visitDate || !visitTime) {
        return;
      }

      try {
        let url = `api/check-availability.php?property_id=${encodeURIComponent(propertyId)}&checkin_date=${encodeURIComponent(visitDate)}&checkin_time=${encodeURIComponent(visitTime)}`;
        if (checkoutDate) {
          url += `&checkout_date=${encodeURIComponent(checkoutDate)}`;
        }
        if (checkoutTime) {
          url += `&checkout_time=${encodeURIComponent(checkoutTime)}`;
        }
        const response = await fetch(url);
        const result = await response.json();

        if (result.success) {
          let alertHtml = '';
          let alertClass = 'alert-info';

          // Get property data from response
          const propertyData = result.properties && result.properties.length > 0 ? result.properties[0] : {};

          // Operating hours info
          if (propertyData.operating_hours) {
            operatingHoursDiv.innerHTML = `<i class="fa fa-clock"></i> Operating hours: ${propertyData.operating_hours.earliest} - ${propertyData.operating_hours.latest}`;
            operatingHoursDiv.style.display = 'block';
          }

          // Time validation error
          if (!propertyData.time_valid) {
            alertClass = 'alert-danger';
            alertHtml += `<p class="mb-1"><strong><i class="fa fa-exclamation-triangle"></i> Invalid Time</strong></p>`;
            alertHtml += `<p class="mb-0">${propertyData.time_error || 'Invalid time selected'}</p>`;
            submitButton.disabled = true;
            submitButton.textContent = 'Please select valid time';
          }

          // Availability blocks warning
          if (propertyData.blocks && propertyData.blocks.length > 0) {
            alertClass = 'alert-warning';
            alertHtml += `<p class="mb-1"><strong><i class="fa fa-exclamation-circle"></i> Scheduled Maintenance/Event</strong></p>`;
            propertyData.blocks.forEach(block => {
              alertHtml += `<p class="mb-0">- ${block.type}${block.time_info}: ${block.reason}</p>`;
            });
            alertHtml += `<p class="mt-2 mb-0 text-muted small">You may still schedule a visit, but some areas may be inaccessible.</p>`;
          }

          // Room availability
          if (propertyData.available_rooms !== undefined) {
            if (propertyData.available_rooms === 0) {
              alertClass = 'alert-danger';
              alertHtml += `<p class="mb-1"><strong><i class="fa fa-ban"></i> Fully Booked</strong></p>`;
              alertHtml += `<p class="mb-0">All ${propertyData.total_rooms} room${propertyData.total_rooms > 1 ? 's' : ''} are booked for these dates. Please select different dates.</p>`;
              submitButton.disabled = true;
              submitButton.textContent = 'Fully booked - Select other dates';
            } else {
              alertHtml += `<p class="mb-2"><i class="fa fa-check-circle text-success"></i> ${propertyData.available_rooms} room${propertyData.available_rooms > 1 ? 's' : ''} available (${propertyData.booked_rooms} of ${propertyData.total_rooms} booked)</p>`;

              // Show available rooms as suggestions
              if (result.data && result.data.length > 0) {
                alertHtml += `<div class="mt-3 pt-2 border-top">`;
                alertHtml += `<p class="mb-2 small text-muted"><strong>Available rooms:</strong></p>`;
                alertHtml += `<div class="list-group list-group-flush small">`;
                result.data.forEach(room => {
                  alertHtml += `
                    <div class="list-group-item px-0 py-1 bg-transparent">
                      <i class="fa fa-door-open text-success me-2"></i>
                      <strong>${room.room_name || room.room_number}</strong>
                      <span class="text-muted">(${room.category_name})</span>
                    </div>
                  `;
                });
                alertHtml += `</div></div>`;
              }

              if (propertyData.available_rooms <= 2) {
                alertClass = alertClass === 'alert-info' ? 'alert-warning' : alertClass;
              }
            }
          }

          // Show alert if there's content
          if (alertHtml) {
            availabilityContent.innerHTML = alertHtml;
            availabilityAlert.className = `col-lg-12 mb-3 alert ${alertClass} mb-0`;
            availabilityAlert.style.display = 'block';
          }
        }
      } catch (error) {
        console.error('Error checking availability:', error);
      }
    }

    // Add event listeners (with null checks)
    if (dateInput) {
      dateInput.addEventListener('change', checkAvailability);
    }
    if (timeSelect) {
      timeSelect.addEventListener('change', checkAvailability);
    }
    if (checkoutDateInput) {
      checkoutDateInput.addEventListener('change', checkAvailability);
    }
    if (checkoutTimeSelect) {
      checkoutTimeSelect.addEventListener('change', checkAvailability);
    }

    // Initial check if values are pre-filled
    if (propertySelect?.value && dateInput?.value && timeSelect?.value) {
      checkAvailability();
    }

    // ============================================
    // ROOM CATEGORY & PRICING FUNCTIONALITY
    // ============================================

    const roomCategorySelect = document.getElementById('room_category_id');
    const categoryDetailsDiv = document.getElementById('categoryDetails');
    const totalAmountSection = document.getElementById('totalAmountSection');
    const numAdultsInput = document.getElementById('num_adults');
    const numChildrenInput = document.getElementById('num_children');

    // Store room categories data - use preselected if available
    let roomCategoriesData = window.preselectedCategories || [];

    // Load room categories when property changes
    async function loadRoomCategories() {
      const propertyId = propertySelect?.value;
      if (!propertyId) {
        roomCategoriesData = [];
        roomCategorySelect.innerHTML = '<option value="">-- Select a Property First --</option>';
        categoryDetailsDiv.style.display = 'none';
        totalAmountSection.style.display = 'none';
        return;
      }

      // If we have preselected categories for this property, use them
      if (window.preselectedCategories && window.preselectedCategories.length > 0) {
        roomCategoriesData = window.preselectedCategories;
        totalAmountSection.style.display = 'block';
        // If category already selected, calculate totals
        if (roomCategorySelect?.value) {
          calculateTotalAmount();
        }
        return; // Already populated by PHP
      }

      try {
        const response = await fetch(`api/room-categories.php?property_id=${encodeURIComponent(propertyId)}`);
        const result = await response.json();

        if (result.success) {
          roomCategoriesData = result.categories || [];

          // Update booking type settings from API response
          if (result.booking_type_settings) {
            const settings = result.booking_type_settings;
            document.getElementById('dbHourlyMinHours').value = settings.hourly_min_hours;
            document.getElementById('dbHourlyMaxHours').value = settings.hourly_max_hours;
            document.getElementById('dbOvernightMinHours').value = settings.overnight_min_hours;
            document.getElementById('dbOvernightRequiresMidnight').value = settings.overnight_requires_midnight ? '1' : '0';
            document.getElementById('dbDayUseMaxHours').value = settings.day_use_max_hours;

            // Update global settings object
            window.bookingTypeSettings = settings;

            // Update hourly presets if container exists
            updateHourlyPresetsFromSettings(settings.hourly_presets || [3, 4, 5, 6, 7]);
          }

          // Populate category dropdown with all data attributes
          let optionsHtml = '<option value="">-- Select Room Category --</option>';
          roomCategoriesData.forEach(cat => {
            optionsHtml += `<option value="${cat.category_id}"
              data-base-price-day="${cat.base_price_day_use}"
              data-base-price-night="${cat.base_price_per_night}"
              data-base-price-hour="${cat.base_price_per_hour}"
              data-minimum-hours="${cat.minimum_hours}"
              data-max-adults="${cat.max_adult_capacity}"
              data-max-children="${cat.max_child_capacity}"
              data-extra-adult-fee="${cat.extra_adult_fee}"
              data-extra-child-fee="${cat.extra_child_fee}"
              data-allows-overnight="${cat.allows_overnight ? '1' : '0'}"
              data-allows-day-use="${cat.allows_day_use ? '1' : '0'}"
              data-allows-hourly="${cat.allows_hourly ? '1' : '0'}">
              ${cat.category_name} - ${cat.base_price_day_use_formatted}/day
              ${cat.capacity_display ? `(${cat.capacity_display})` : ''}
            </option>`;
          });
          roomCategorySelect.innerHTML = optionsHtml;

          // Auto-select if only one category available
          if (roomCategoriesData.length === 1) {
            roomCategorySelect.value = roomCategoriesData[0].category_id;
            updateCategoryDetails();
            calculateTotalAmount();
          }
        }
      } catch (error) {
        console.error('Error loading room categories:', error);
        roomCategorySelect.innerHTML = '<option value="">-- Error loading categories --</option>';
      }
    }

    // Update category details display
    function updateCategoryDetails() {
      const selectedOption = roomCategorySelect?.selectedOptions[0];
      if (!selectedOption || !selectedOption.value) {
        categoryDetailsDiv.style.display = 'none';
        return;
      }

      const category = roomCategoriesData.find(c => c.category_id === selectedOption.value);
      if (category) {
        // Update hidden inputs for calculations
        // Update all pricing hidden inputs
        document.getElementById('categoryBasePriceDay').value = category.base_price_day_use;
        document.getElementById('categoryBasePriceNight').value = category.base_price_per_night;
        document.getElementById('categoryBasePriceHour').value = category.base_price_per_hour;
        document.getElementById('categoryMinimumHours').value = category.minimum_hours;
        document.getElementById('categoryMaxAdults').value = category.max_adult_capacity;
        document.getElementById('categoryMaxChildren').value = category.max_child_capacity;
        document.getElementById('categoryExtraAdultFee').value = category.extra_adult_fee;
        document.getElementById('categoryExtraChildFee').value = category.extra_child_fee;
        document.getElementById('categoryAllowsOvernight').value = category.allows_overnight ? '1' : '0';
        document.getElementById('categoryAllowsDayUse').value = category.allows_day_use ? '1' : '0';
        document.getElementById('categoryAllowsHourly').value = category.allows_hourly ? '1' : '0';

        // Show category details
        let detailsHtml = `<strong>Capacity:</strong> ${category.capacity_display}`;
        if (category.extra_fees_display) {
          detailsHtml += ` | <strong>${category.extra_fees_display}</strong>`;
        }
        if (category.bed_configuration) {
          detailsHtml += `<br><strong>Bed:</strong> ${category.bed_configuration}`;
        }
        if (category.view_type) {
          detailsHtml += ` | <strong>View:</strong> ${category.view_type}`;
        }
        categoryDetailsDiv.innerHTML = detailsHtml;
        categoryDetailsDiv.style.display = 'block';

        // Show total amount section
        totalAmountSection.style.display = 'block';
      }
    }

    // Show capacity validation alert
    function showCapacityAlert(type, extraCount, maxCapacity) {
      const alertDiv = document.getElementById('capacityValidationAlert');
      const alertContent = document.getElementById('capacityAlertContent');
      const typeLabel = type === 'adult' ? 'adults' : 'children';
      const singularLabel = type === 'adult' ? 'an adult' : 'a child';

      if (alertDiv && alertContent) {
        alertContent.innerHTML = `
          <strong><i class="fa fa-exclamation-triangle"></i> Capacity Exceeded</strong><br>
          You've added ${extraCount} extra ${typeLabel} (maximum is ${maxCapacity}).<br>
          <strong>No extra fee is configured for ${singularLabel}.</strong><br>
          Please contact us for assistance with large groups.
        `;
        alertDiv.style.display = 'block';
        submitButton.disabled = true;
        submitButton.textContent = 'Please adjust guest count';
      }
    }

    // Clear capacity validation alert
    function clearCapacityAlert() {
      const alertDiv = document.getElementById('capacityValidationAlert');
      if (alertDiv) {
        alertDiv.style.display = 'none';
      }
      submitButton.disabled = false;
      submitButton.textContent = 'Schedule Visit';
    }

    // Update hourly preset buttons from database settings
    function updateHourlyPresetsFromSettings(presets) {
      const container = document.getElementById('hourlyPresetsContainer');
      if (!container || !Array.isArray(presets)) return;

      // Get minimum hours for text display
      const minHours = parseInt(document.getElementById('dbHourlyMinHours')?.value || 3);

      // Generate button HTML
      let buttonsHtml = '';
      presets.forEach(hours => {
        buttonsHtml += `<button type="button" class="btn btn-outline-primary btn-sm" onclick="setDurationHours(${hours})">${hours} Hours</button>`;
      });

      container.innerHTML = buttonsHtml;

      // Update the minimum hours text
      const minHoursText = container.parentElement?.querySelector('.form-text');
      if (minHoursText) {
        minHoursText.textContent = `Minimum: ${minHours} hours | Select a preset or set custom times above`;
      }
    }

    // Set duration hours for quick hourly booking
    function setDurationHours(hours) {
      const checkInDate = document.getElementById('visit_date')?.value;
      const checkInTime = document.getElementById('visit_time')?.value;

      if (!checkInDate || !checkInTime) {
        alert('Please select check-in date and time first');
        return;
      }

      // Calculate check-out datetime
      const checkIn = new Date(`${checkInDate}T${checkInTime}`);
      const checkOut = new Date(checkIn.getTime() + (hours * 60 * 60 * 1000));

      // Format check-out date and time
      const checkOutDate = checkOut.toISOString().split('T')[0];
      const checkOutHours = String(checkOut.getHours()).padStart(2, '0');
      const checkOutMinutes = String(checkOut.getMinutes()).padStart(2, '0');
      const checkOutTime = `${checkOutHours}:${checkOutMinutes}`;

      // Set check-out fields
      document.getElementById('checkout_date').value = checkOutDate;
      document.getElementById('checkout_time').value = checkOutTime;

      // Recalculate totals
      calculateTotalAmount();
    }

    // Show booking type validation alert
    function showBookingTypeAlert(detectedType, allowedTypes) {
      const alertDiv = document.getElementById('bookingTypeValidationAlert');
      const alertContent = document.getElementById('bookingTypeAlertContent');

      const typeLabels = {
        'overnight': 'Overnight stay (spans midnight)',
        'day_use': 'Day use (same day)',
        'hourly': 'Hourly booking (short duration)'
      };

      const allowedLabels = [];
      if (allowedTypes.overnight) allowedLabels.push('Overnight');
      if (allowedTypes.day_use) allowedLabels.push('Day Use');
      if (allowedTypes.hourly) allowedLabels.push('Hourly');

      if (alertDiv && alertContent) {
        alertContent.innerHTML = `
          <strong><i class="fa fa-exclamation-triangle"></i> Booking Type Mismatch</strong><br>
          Your selected times indicate: <strong>${typeLabels[detectedType]}</strong><br>
          This room category only allows: <strong>${allowedLabels.join(', ') || 'None configured'}</strong><br>
          <small>Please adjust your check-in/check-out times or select a different room category.</small>
        `;
        alertDiv.style.display = 'block';
        submitButton.disabled = true;
        submitButton.textContent = 'Please adjust booking times';
      }
    }

    // Clear booking type validation alert
    function clearBookingTypeAlert() {
      const alertDiv = document.getElementById('bookingTypeValidationAlert');
      if (alertDiv) {
        alertDiv.style.display = 'none';
      }
      // Only re-enable if capacity alert is also clear
      const capacityAlert = document.getElementById('capacityValidationAlert');
      if (!capacityAlert || capacityAlert.style.display === 'none') {
        submitButton.disabled = false;
        submitButton.textContent = 'Schedule Visit';
      }
    }

    // Update hourly presets visibility and regenerate buttons dynamically
    function updateHourlyPresetsVisibility(bookingType) {
      const presetsDiv = document.getElementById('hourlyDurationPresets');
      if (!presetsDiv) return;

      if (bookingType === 'hourly') {
        // Regenerate preset buttons from current settings
        const presets = window.bookingTypeSettings?.hourly_presets || [3, 4, 5, 6, 7];
        const minHours = window.bookingTypeSettings?.hourly_min_hours || 3;
        const container = document.getElementById('hourlyPresetsContainer');

        if (container && Array.isArray(presets)) {
          let buttonsHtml = '';
          presets.forEach(hours => {
            // Only show presets that are >= minimum hours
            if (hours >= minHours) {
              buttonsHtml += `<button type="button" class="btn btn-outline-primary btn-sm" onclick="setDurationHours(${hours})">${hours} Hours</button>`;
            }
          });
          container.innerHTML = buttonsHtml;

          // Update the minimum hours text
          const minHoursText = container.parentElement?.querySelector('.form-text');
          if (minHoursText) {
            minHoursText.textContent = `Minimum: ${minHours} hours | Select a preset or set custom times above`;
          }
        }

        presetsDiv.style.display = 'block';
      } else {
        presetsDiv.style.display = 'none';
      }
    }

    // Calculate total amount based on category pricing, guest counts, and dates
    function calculateTotalAmount() {
      // Get pricing data
      const priceDay = parseFloat(document.getElementById('categoryBasePriceDay')?.value || 0);
      const priceNight = parseFloat(document.getElementById('categoryBasePriceNight')?.value || 0);
      const priceHour = parseFloat(document.getElementById('categoryBasePriceHour')?.value || 0);
      // Get database-driven booking type thresholds
      const dbMinHours = parseInt(document.getElementById('dbHourlyMinHours')?.value || 3);
      const dbMaxHours = parseInt(document.getElementById('dbHourlyMaxHours')?.value || 12);
      const dbOvernightMin = parseInt(document.getElementById('dbOvernightMinHours')?.value || 12);
      const dbOvernightRequiresMidnight = document.getElementById('dbOvernightRequiresMidnight')?.value === '1';
      const dbDayUseMax = parseInt(document.getElementById('dbDayUseMaxHours')?.value || 12);

      // Category-specific allowances
      const allowsOvernight = document.getElementById('categoryAllowsOvernight')?.value === '1';
      const allowsDayUse = document.getElementById('categoryAllowsDayUse')?.value === '1';
      const allowsHourly = document.getElementById('categoryAllowsHourly')?.value === '1';

      const maxAdults = parseInt(document.getElementById('categoryMaxAdults')?.value || 2);
      const maxChildren = parseInt(document.getElementById('categoryMaxChildren')?.value || 2);
      const extraAdultFee = parseFloat(document.getElementById('categoryExtraAdultFee')?.value || 0);
      const extraChildFee = parseFloat(document.getElementById('categoryExtraChildFee')?.value || 0);

      const numAdults = parseInt(numAdultsInput?.value || 1);
      const numChildren = parseInt(numChildrenInput?.value || 0);

      // Get dates
      const checkInDate = document.getElementById('visit_date')?.value;
      const checkInTime = document.getElementById('visit_time')?.value;
      const checkOutDate = document.getElementById('checkout_date')?.value;
      const checkOutTime = document.getElementById('checkout_time')?.value;

      // Clear previous validation alerts
      clearCapacityAlert();
      clearBookingTypeAlert();

      // Need dates to calculate
      if (!checkInDate || !checkOutDate || !checkInTime || !checkOutTime) {
        return 0;
      }

      // Calculate duration
      const checkIn = new Date(`${checkInDate}T${checkInTime}`);
      const checkOut = new Date(`${checkOutDate}T${checkOutTime}`);
      const durationMs = checkOut - checkIn;
      const durationHours = durationMs / (1000 * 60 * 60);
      const durationDays = Math.ceil(durationHours / 24);

      // Determine booking type and base rate
      let bookingType = 'day_use';
      let baseRate = priceDay;
      let rateLabel = 'per day';
      let durationDisplay = '';
      let numNights = 0;
      let numHours = 0;

      // Check if dates are different (spans midnight)
      const spansMidnight = checkInDate !== checkOutDate;

      // Detect booking type based on DATABASE-DRIVEN thresholds and dates
      let detectedBookingType = 'day_use';

      // Overnight: Must span midnight (if required) AND meet minimum hours
      const meetsOvernightRequirement = dbOvernightRequiresMidnight ? spansMidnight : true;
      if (durationHours >= dbOvernightMin && meetsOvernightRequirement) {
        detectedBookingType = 'overnight';
      } else if (durationHours <= dbMaxHours && durationHours >= dbMinHours) {
        // Hourly: Within database-configured min/max hours
        detectedBookingType = 'hourly';
      } else if (!spansMidnight && durationHours <= dbDayUseMax) {
        // Day use: Same day and within max hours
        detectedBookingType = 'day_use';
      } else if (spansMidnight && durationHours < dbOvernightMin) {
        // Spans midnight but less than overnight minimum - still day use
        detectedBookingType = 'day_use';
      }

      // Validate booking type against room category allowances
      const allowedTypes = {
        overnight: allowsOvernight,
        day_use: allowsDayUse,
        hourly: allowsHourly
      };

      // Check if detected type is allowed
      if (detectedBookingType === 'overnight' && !allowsOvernight) {
        showBookingTypeAlert('overnight', allowedTypes);
        return 0;
      } else if (detectedBookingType === 'hourly' && !allowsHourly) {
        showBookingTypeAlert('hourly', allowedTypes);
        return 0;
      } else if (detectedBookingType === 'day_use' && !allowsDayUse) {
        showBookingTypeAlert('day_use', allowedTypes);
        return 0;
      }

      // Now apply the booking type and rates
      if (detectedBookingType === 'overnight' && allowsOvernight) {
        // Overnight booking (spans midnight and 12+ hours)
        bookingType = 'overnight';
        numNights = Math.max(1, Math.round(durationHours / 24));
        baseRate = priceNight * numNights;
        rateLabel = `per night x ${numNights} night${numNights > 1 ? 's' : ''}`;
        durationDisplay = `${numNights} night${numNights > 1 ? 's' : ''}`;
      } else if (detectedBookingType === 'hourly' && allowsHourly && priceHour > 0) {
        // Hourly booking (within hourly range)
        bookingType = 'hourly';
        numHours = Math.ceil(durationHours);

        // Check for duration-specific rate from database settings
        const hourlyRates = window.bookingTypeSettings?.hourly_rates || {};
        const hoursKey = String(numHours);

        // Use duration-specific rate if configured, otherwise calculate per-hour rate
        if (hourlyRates[hoursKey]) {
          baseRate = parseFloat(hourlyRates[hoursKey]);
          rateLabel = `for ${numHours} hours (special rate)`;
        } else {
          baseRate = priceHour * numHours;
          rateLabel = `per hour x ${numHours} hour${numHours > 1 ? 's' : ''}`;
        }
        durationDisplay = `${numHours} hour${numHours > 1 ? 's' : ''}`;
      } else {
        // Day use (default)
        bookingType = 'day_use';
        baseRate = priceDay;
        rateLabel = 'per day';
        durationDisplay = `${Math.round(durationHours)} hours`;
      }

      let totalAmount = baseRate;

      // Calculate extra adult fees and validate
      let extraAdults = 0;
      let extraAdultsCost = 0;
      if (numAdults > maxAdults) {
        extraAdults = numAdults - maxAdults;
        if (extraAdultFee <= 0) {
          showCapacityAlert('adult', extraAdults, maxAdults);
        }
        extraAdultsCost = extraAdults * extraAdultFee;
        totalAmount += extraAdultsCost;
      }

      // Calculate extra child fees and validate
      let extraChildren = 0;
      let extraChildrenCost = 0;
      if (numChildren > maxChildren) {
        extraChildren = numChildren - maxChildren;
        if (extraChildFee <= 0) {
          showCapacityAlert('child', extraChildren, maxChildren);
        }
        extraChildrenCost = extraChildren * extraChildFee;
        totalAmount += extraChildrenCost;
      }

      // Update booking summary display
      const bookingTypeDisplay = document.getElementById('bookingTypeDisplay');
      if (bookingTypeDisplay) {
        bookingTypeDisplay.textContent = bookingType === 'overnight' ? 'Overnight' :
          bookingType === 'hourly' ? 'Hourly' : 'Day Use';
        bookingTypeDisplay.className = `badge ${bookingType === 'overnight' ? 'bg-dark' :
                                                      bookingType === 'hourly' ? 'bg-warning text-dark' :
                                                      'bg-info'}`;
      }

      // Update hourly presets visibility based on booking type
      updateHourlyPresetsVisibility(bookingType);

      const durationDisplayEl = document.getElementById('durationDisplay');
      if (durationDisplayEl) {
        durationDisplayEl.textContent = durationDisplay || '-';
      }

      const rateDisplayEl = document.getElementById('rateDisplay');
      if (rateDisplayEl) {
        let unitPrice;
        let displayLabel;

        if (bookingType === 'overnight') {
          unitPrice = priceNight;
          displayLabel = rateLabel;
        } else if (bookingType === 'hourly') {
          // Check if using duration-specific rate
          const hourlyRates = window.bookingTypeSettings?.hourly_rates || {};
          const hoursKey = String(numHours);
          if (hourlyRates[hoursKey]) {
            unitPrice = parseFloat(hourlyRates[hoursKey]);
            displayLabel = `for ${numHours} hours`;
          } else {
            unitPrice = priceHour * numHours;
            displayLabel = `per hour x ${numHours}`;
          }
        } else {
          unitPrice = priceDay;
          displayLabel = 'per day';
        }

        rateDisplayEl.textContent = `₱${unitPrice.toLocaleString('en-PH', {minimumFractionDigits: 2})} ${displayLabel}`;
      }

      // Update hidden inputs
      document.getElementById('bookingType').value = bookingType;
      document.getElementById('bookingTypeInput').value = bookingType;
      document.getElementById('numNightsInput').value = numNights;
      document.getElementById('numHoursInput').value = numHours;

      // Update base rate display
      const baseRateLabel = document.getElementById('baseRateLabel');
      if (baseRateLabel) {
        baseRateLabel.textContent = bookingType === 'overnight' ? `Base Rate (${numNights} nights):` :
          bookingType === 'hourly' ? `Base Rate (${numHours} hours):` :
          'Base Rate (day use):';
      }
      document.getElementById('baseRateDisplay').textContent = '₱' + baseRate.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });

      // Update extra adults row
      const extraAdultsRow = document.getElementById('extraAdultsRow');
      if (extraAdults > 0) {
        document.getElementById('extraAdultsCount').textContent = extraAdults;
        document.getElementById('extraAdultsFee').textContent = '+₱' + extraAdultsCost.toLocaleString('en-PH', {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        });
        extraAdultsRow.style.display = 'flex';
      } else {
        extraAdultsRow.style.display = 'none';
      }

      // Update extra children row
      const extraChildrenRow = document.getElementById('extraChildrenRow');
      if (extraChildren > 0) {
        document.getElementById('extraChildrenCount').textContent = extraChildren;
        document.getElementById('extraChildrenFee').textContent = '+₱' + extraChildrenCost.toLocaleString('en-PH', {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        });
        extraChildrenRow.style.display = 'flex';
      } else {
        extraChildrenRow.style.display = 'none';
      }

      // Update total
      document.getElementById('totalAmountDisplay').textContent = '₱' + totalAmount.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
      document.getElementById('totalAmountInput').value = totalAmount.toFixed(2);

      // Also update payment option labels
      updatePaymentAmounts(totalAmount);

      return totalAmount;
    }

    // Update payment amount labels based on total
    function updatePaymentAmounts(totalAmount) {
      const partialAmountLabel = document.getElementById('partialAmount');
      const fullAmountLabel = document.getElementById('fullAmount');
      if (!partialAmountLabel || !fullAmountLabel) return;

      const paymentType = document.getElementById('paymentType')?.value || 'percentage';
      const paymentValue = parseFloat(document.getElementById('paymentValue')?.value || 30);
      const minPayment = parseFloat(document.getElementById('minPayment')?.value || 500);

      // Calculate partial payment
      let partialAmount;
      if (paymentType === 'percentage') {
        partialAmount = totalAmount * (paymentValue / 100);
      } else {
        partialAmount = paymentValue;
      }
      partialAmount = Math.max(partialAmount, minPayment);

      // Update labels
      partialAmountLabel.textContent = `₱${partialAmount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})} of ₱${totalAmount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
      fullAmountLabel.textContent = `₱${totalAmount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    }

    // Add event listeners for calculations
    if (roomCategorySelect) {
      roomCategorySelect.addEventListener('change', function() {
        updateCategoryDetails();
        calculateTotalAmount();
      });
    }

    if (numAdultsInput) {
      numAdultsInput.addEventListener('change', calculateTotalAmount);
      numAdultsInput.addEventListener('input', calculateTotalAmount);
    }

    if (numChildrenInput) {
      numChildrenInput.addEventListener('change', calculateTotalAmount);
      numChildrenInput.addEventListener('input', calculateTotalAmount);
    }

    // Add event listeners for date/time changes
    const checkInDateInput = document.getElementById('visit_date');
    const checkInTimeInput = document.getElementById('visit_time');
    const checkOutDateInput = document.getElementById('checkout_date');
    const checkOutTimeInput = document.getElementById('checkout_time');

    if (checkInDateInput) {
      checkInDateInput.addEventListener('change', calculateTotalAmount);
    }
    if (checkInTimeInput) {
      checkInTimeInput.addEventListener('change', calculateTotalAmount);
    }
    if (checkOutDateInput) {
      checkOutDateInput.addEventListener('change', calculateTotalAmount);
    }
    if (checkOutTimeInput) {
      checkOutTimeInput.addEventListener('change', calculateTotalAmount);
    }

    // Form submission handler (AJAX to API)
    const form = document.getElementById('schedule-visit-form');
    const successModalEl = document.getElementById('successModal');
    const successModal = successModalEl ? new bootstrap.Modal(successModalEl) : null;
    const successMessage = document.getElementById('successMessage');

    if (form) {
      form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton?.textContent || 'Schedule Visit';

        // Disable submit button
        if (submitButton) {
          submitButton.disabled = true;
          submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
        }

        try {
          // Create FormData
          const formData = new FormData(form);

          // Submit via API
          const response = await fetch('api/process-schedule-visit.php', {
            method: 'POST',
            body: formData
          });

          const result = await response.json();

          if (result.success) {
            // Reset form
            form.reset();
            
            // Reset food cart
            foodCart = [];
            updateFoodUI();
            document.getElementById('foodSection').style.display = 'none';
            document.getElementById('foodToggleIcon').classList.remove('fa-chevron-up');
            document.getElementById('foodToggleIcon').classList.add('fa-chevron-down');

            // Show success modal
            if (successMessage) {
              let foodInfo = '';
              if (result.food_order_code) {
                foodInfo = `<p class="mb-1"><strong>Food Order:</strong> ${result.food_order_code}</p>`;
              }
              
              successMessage.innerHTML = `
                <div class="alert alert-success mb-3">
                  <h5 class="alert-heading mb-2">Reservation Created!</h5>
                  <p class="mb-1"><strong>Reservation Code:</strong> ${result.reservation_code}</p>
                  ${foodInfo}
                  <p class="mb-0">${result.message}</p>
                </div>
                <p class="text-muted small">A confirmation email has been sent to ${document.getElementById('email')?.value || 'your email'}.</p>
              `;
            }
            successModal?.show();

            // Redirect after delay
            if (result.redirect_url) {
              setTimeout(() => {
                window.location.href = result.redirect_url;
              }, 3000);
            }
          } else {
            throw new Error(result.error || 'Failed to create reservation');
          }
        } catch (error) {
          console.error('Form submission error:', error);

          // Show error in alert
          const alertDiv = document.createElement('div');
          alertDiv.className = 'alert alert-danger alert-dismissible fade show mb-3';
          alertDiv.innerHTML = `
            <strong>Error!</strong> ${error.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          `;
          form.insertBefore(alertDiv, form.firstChild);
        } finally {
          // Re-enable submit button
          if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
          }
        }
      });
    }

    // Add event listeners (with null checks)
    if (dateInput) {
      dateInput.addEventListener('change', checkAvailability);
    }
    if (checkInTimeInput) {
      checkInTimeInput.addEventListener('change', checkAvailability);
    }
    if (checkOutDateInput) {
      checkOutDateInput.addEventListener('change', calculateTotalAmount);
    }
    if (checkOutTimeInput) {
      checkOutTimeInput.addEventListener('change', calculateTotalAmount);
    }

    // Initial calculations
    if (roomCategorySelect) {
      updateCategoryDetails();
    }
    calculateTotalAmount();
    checkAvailability();

    // ============================================
    // PAYMENT FUNCTIONALITY
    // ============================================

    const paymentMethodSelect = document.getElementById('payment_method');
    const paymentInstructions = document.getElementById('paymentInstructions');
    const paymentInstructionsContent = document.getElementById('paymentInstructionsContent');
    const qrCodeContainer = document.getElementById('qrCodeContainer');
    const qrCodeImage = document.getElementById('qrCodeImage');
    const referenceField = document.getElementById('referenceField');
    const proofUploadField = document.getElementById('proofUploadField');
    const proofOfPaymentInput = document.getElementById('proof_of_payment');
    const amountPaidInput = document.getElementById('amountPaidInput');
    const paymentOptionRadios = document.querySelectorAll('input[name="payment_option"]');

    const customAmountInput      = document.getElementById('customAmountInput');
    const customAmountError      = document.getElementById('customAmountError');
    const partialAmountHint      = document.getElementById('partialAmountHint');
    const partialAmountWrapper   = document.getElementById('partialAmountInputWrapper');
    const fullAmountWrapper      = document.getElementById('fullAmountDisplayWrapper');
    const amountToPaySection     = document.getElementById('amountToPaySection');
    const amountToPayDisplay     = document.getElementById('amountToPayDisplay');

    function getMinimumPayment(totalAmount) {
        const paymentType  = document.getElementById('paymentType')?.value || 'percentage';
        const paymentValue = parseFloat(document.getElementById('paymentValue')?.value || 30);
        const minPayment   = parseFloat(document.getElementById('minPayment')?.value || 500);
        let calculated = 0;
        if (paymentType === 'percentage') {
            calculated = totalAmount * (paymentValue / 100);
        } else {
            calculated = paymentValue;
        }
        return Math.max(calculated, minPayment);
    }

    function validateCustomAmount() {
        const totalAmount = parseFloat(totalAmountInput?.value || 0);
        const minAmount   = getMinimumPayment(totalAmount);
        const entered     = parseFloat(customAmountInput?.value || 0);

        if (!entered || entered <= 0) {
            customAmountError.textContent = 'Please enter an amount.';
            customAmountError.style.display = 'block';
            if (amountPaidInput) amountPaidInput.value = 0;
            return false;
        }
        if (entered < minAmount) {
            customAmountError.textContent = `Minimum amount is ₱${minAmount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}.`;
            customAmountError.style.display = 'block';
            if (amountPaidInput) amountPaidInput.value = 0;
            return false;
        }
        if (entered > totalAmount) {
            customAmountError.textContent = `Amount cannot exceed the total of ₱${totalAmount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}.`;
            customAmountError.style.display = 'block';
            if (amountPaidInput) amountPaidInput.value = 0;
            return false;
        }
        customAmountError.style.display = 'none';
        if (amountPaidInput) amountPaidInput.value = entered.toFixed(2);
        return true;
    }

    function updateAmountPaid() {
        const selectedOption = document.querySelector('input[name="payment_option"]:checked');
        const totalAmount    = parseFloat(totalAmountInput?.value || 0);

        if (!totalAmount || !selectedOption) {
            if (amountPaidInput) amountPaidInput.value = 0;
            if (amountToPaySection) amountToPaySection.style.display = 'none';
            return;
        }

        amountToPaySection.style.display = 'block';

        if (selectedOption.value === 'partial') {
            // Show editable input, hide locked display
            partialAmountWrapper.style.display = 'block';
            fullAmountWrapper.style.display    = 'none';

            const minAmount = getMinimumPayment(totalAmount);
            partialAmountHint.textContent = `(min. ₱${minAmount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})} — max. ₱${totalAmount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})})`;

            // Pre-fill with minimum if empty or below min
            const current = parseFloat(customAmountInput?.value || 0);
            if (!current || current < minAmount) {
                customAmountInput.value = minAmount.toFixed(2);
            }
            customAmountInput.min = minAmount.toFixed(2);
            customAmountInput.max = totalAmount.toFixed(2);
            validateCustomAmount();

        } else if (selectedOption.value === 'full') {
            // Show locked display, hide editable input
            partialAmountWrapper.style.display = 'none';
            fullAmountWrapper.style.display    = 'block';

            if (amountToPayDisplay) amountToPayDisplay.textContent = '₱' + totalAmount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            if (amountPaidInput) amountPaidInput.value = totalAmount.toFixed(2);
            if (customAmountError) customAmountError.style.display = 'none';
        }
    }

    // Validate on every keystroke in the custom amount field
    if (customAmountInput) {
        customAmountInput.addEventListener('input', validateCustomAmount);
    }

    // Listen for payment option changes
    paymentOptionRadios.forEach(radio => {
        radio.addEventListener('change', updateAmountPaid);
    });

    // Also update when total changes
    const observer = new MutationObserver(() => {
        updateAmountPaid();
    });
    const totalAmountInput = document.getElementById('totalAmountInput');
    if (totalAmountInput) {
        observer.observe(totalAmountInput, { attributes: true });
    }

    // Load payment accounts when property changes
    async function loadPaymentAccounts() {
      const propertyId = propertySelect?.value;
      if (!propertyId) {
        paymentAccountsData = [];
        updatePaymentDisplay();
        return;
      }

      try {
        const response = await fetch(`api/payment-accounts.php?property_id=${encodeURIComponent(propertyId)}`);
        const result = await response.json();

        if (result.success) {
          paymentAccountsData = result.accounts || [];

          // Filter payment method dropdown to only show methods with accounts
          const availableMethodCodes = paymentAccountsData.map(acc => acc.method_code);
          const methodOptions = paymentMethodSelect?.querySelectorAll('option');
          if (methodOptions) {
            methodOptions.forEach(option => {
              if (option.value === '') return; // Keep default option
              option.style.display = availableMethodCodes.includes(option.value) ? '' : 'none';
            });
          }

          updatePaymentDisplay();
        }
      } catch (error) {
        console.error('Error loading payment accounts:', error);
      }
    }

    // Calculate and display payment amounts based on database settings
    function calculatePaymentAmounts() {
      const partialAmountLabel = document.getElementById('partialAmount');
      const fullAmountLabel = document.getElementById('fullAmount');
      if (!partialAmountLabel || !fullAmountLabel) return;

      // Get payment settings from hidden inputs
      const paymentType = document.getElementById('paymentType')?.value || 'percentage';
      const paymentValue = parseFloat(document.getElementById('paymentValue')?.value || 30);
      const minPayment = parseFloat(document.getElementById('minPayment')?.value || 500);

      // Base amount (should be calculated from property/room rates in production)
      // For now using placeholder - in real implementation fetch from property pricing
      const baseAmount = 1000; // This should come from property/room rate API

      // Calculate partial payment
      let partialAmount;
      if (paymentType === 'percentage') {
        partialAmount = baseAmount * (paymentValue / 100);
      } else {
        partialAmount = paymentValue;
      }
      // Ensure minimum payment
      partialAmount = Math.max(partialAmount, minPayment);

      // Update labels
      partialAmountLabel.textContent = `₱${partialAmount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})} of ₱${baseAmount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
      fullAmountLabel.textContent = `₱${baseAmount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    }

    // Calculate amounts when property is selected
    if (propertySelect) {
      propertySelect.addEventListener('change', calculatePaymentAmounts);
    }

    // Initial calculation
    calculatePaymentAmounts();

    // Update payment display based on selected method
    function updatePaymentDisplay() {
      const selectedMethod = paymentMethodSelect?.value;

      if (!selectedMethod) {
        paymentInstructions.style.display = 'none';
        qrCodeContainer.style.display = 'none';
        referenceField.style.display = 'none';
        proofUploadField.style.display = 'none';
        if (proofOfPaymentInput) proofOfPaymentInput.required = false;
        return;
      }

      // Find account for selected method
      const account = paymentAccountsData.find(acc => acc.method_code === selectedMethod);

      if (selectedMethod === 'cash') {
        // Cash on arrival - no proof needed
        paymentInstructions.style.display = 'block';
        paymentInstructionsContent.innerHTML = `
          <p>You have selected <strong>Cash on Arrival</strong>.</p>
          <p>No upfront payment is required, but please note:</p>
          <ul>
            <li>Your reservation will be marked as "pending" until confirmed by admin</li>
            <li>Please bring the exact amount in cash when you arrive</li>
            <li>The property reserves the right to cancel unconfirmed reservations</li>
          </ul>
        `;
        qrCodeContainer.style.display = 'none';
        referenceField.style.display = 'none';
        proofUploadField.style.display = 'none';
        if (proofOfPaymentInput) proofOfPaymentInput.required = false;
      } else if (account) {
        // Online payment method with account details
        paymentInstructions.style.display = 'block';

        let instructionsHtml = `<p><strong>${account.method_name}</strong></p>`;

        if (account.account_name) {
          instructionsHtml += `<p><strong>Account Name:</strong> ${account.account_name}</p>`;
        }
        if (account.account_number) {
          instructionsHtml += `<p><strong>Account Number:</strong> ${account.account_number}</p>`;
        }
        if (account.bank_name) {
          instructionsHtml += `<p><strong>Bank Name:</strong> ${account.bank_name}</p>`;
        }
        if (account.instructions) {
          instructionsHtml += `<hr><p>${account.instructions}</p>`;
        }

        paymentInstructionsContent.innerHTML = instructionsHtml;

        // Show QR code if available
        if (account.qr_code_image) {
          qrCodeContainer.style.display = 'block';
          qrCodeImage.src = account.qr_code_image;
        } else {
          qrCodeContainer.style.display = 'none';
        }

        // Show reference field and proof upload
        referenceField.style.display = 'block';
        proofUploadField.style.display = 'block';
        if (proofOfPaymentInput) proofOfPaymentInput.required = true;
      } else {
        // Method selected but no account configured
        paymentInstructions.style.display = 'block';
        paymentInstructionsContent.innerHTML = `
          <p class="text-warning"><i class="fa fa-exclamation-triangle"></i> 
          Payment account not configured for this property. Please select a different method or contact support.</p>
        `;
        qrCodeContainer.style.display = 'none';
        referenceField.style.display = 'none';
        proofUploadField.style.display = 'none';
        if (proofOfPaymentInput) proofOfPaymentInput.required = false;
      }
    }

    // File upload validation
    if (proofOfPaymentInput) {
      proofOfPaymentInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file size (5MB max)
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
          alert('File is too large. Maximum size is 5MB.');
          e.target.value = '';
          return;
        }

        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        if (!allowedTypes.includes(file.type)) {
          alert('Invalid file type. Please upload JPG, PNG, GIF, or PDF.');
          e.target.value = '';
          return;
        }
      });
    }

    // Event listeners
    if (propertySelect) {
      propertySelect.addEventListener('change', function() {
        checkAvailability();
        loadRoomCategories();
        loadPaymentAccounts();
      });
    }

    if (paymentMethodSelect) {
      paymentMethodSelect.addEventListener('change', updatePaymentDisplay);
    }

    // Initial load if property is pre-selected
    if (propertySelect?.value) {
      loadRoomCategories();
      loadPaymentAccounts();
      // Calculate totals if category is also preselected
      if (roomCategorySelect?.value) {
        calculateTotalAmount();
      }
      // Load food menu if property selected
      loadFoodMenu(propertySelect.value);
    }

    // ============================================
    // FOOD & BEVERAGES SECTION
    // ============================================
    
    let foodCart = [];
    let foodMenuItems = [];
    let foodCurrentPage = 1;
    const foodItemsPerPage = 6;
    
    function toggleFoodSection() {
      const section = document.getElementById('foodSection');
      const icon = document.getElementById('foodToggleIcon');
      if (section.style.display === 'none') {
        section.style.display = 'block';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
        // Load food if property selected
        const propertyId = propertySelect?.value;
        if (propertyId) {
          loadFoodMenu(propertyId);
        }
      } else {
        section.style.display = 'none';
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
      }
    }
    
    async function loadFoodMenu(propertyId) {
      const container = document.getElementById('foodMenuContainer');
      if (!propertyId) {
        container.innerHTML = '<p class="text-muted text-center py-3">Select a property first to view available food items</p>';
        return;
      }
      
      container.innerHTML = '<p class="text-muted text-center py-3"><i class="fa fa-spinner fa-spin"></i> Loading menu...</p>';
      
      try {
        const res = await fetch(`api/food-menu.php?action=menu&property_id=${propertyId}`);
        const data = await res.json();
        if (data.success && data.data.length > 0) {
          foodMenuItems = data.data;
          renderFoodMenu();
        } else {
          container.innerHTML = '<p class="text-muted text-center py-3">No food items available for this property</p>';
        }
      } catch (err) {
        container.innerHTML = '<p class="text-danger text-center py-3">Failed to load food menu</p>';
      }
    }
    
    function renderFoodMenu() {
      const container = document.getElementById('foodMenuContainer');
      const totalItems = foodMenuItems.length;
      const totalPages = Math.ceil(totalItems / foodItemsPerPage);
      
      // Ensure current page is valid
      if (foodCurrentPage > totalPages) foodCurrentPage = 1;
      if (foodCurrentPage < 1) foodCurrentPage = 1;
      
      // Get items for current page
      const start = (foodCurrentPage - 1) * foodItemsPerPage;
      const end = start + foodItemsPerPage;
      const pageItems = foodMenuItems.slice(start, end);
      
      // Build pagination HTML
      let paginationHtml = '';
      if (totalPages > 1) {
        paginationHtml = `
          <div class="d-flex justify-content-center align-items-center gap-2 mt-3 pt-3 border-top">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="changeFoodPage(${foodCurrentPage - 1})" ${foodCurrentPage === 1 ? 'disabled' : ''}>
              <i class="fa fa-chevron-left"></i> Prev
            </button>
            <span class="text-muted small">Page ${foodCurrentPage} of ${totalPages} (${totalItems} items)</span>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="changeFoodPage(${foodCurrentPage + 1})" ${foodCurrentPage === totalPages ? 'disabled' : ''}>
              Next <i class="fa fa-chevron-right"></i>
            </button>
          </div>
        `;
      }
      
      container.innerHTML = pageItems.map(item => {
        const existing = foodCart.find(c => c.item_id === item.item_id);
        const qty = existing ? existing.qty : 0;
        // Fix image path - use uploads/foods directory
        let imgUrl = '../uploads/foods/default-food.png';
        if (item.image_url) {
          if (item.image_url.startsWith('http')) {
            imgUrl = item.image_url;
          } else if (item.image_url.startsWith('/uploads/foods/')) {
            imgUrl = '..' + item.image_url;
          } else if (item.image_url.startsWith('/')) {
            imgUrl = '..' + item.image_url;
          } else {
            imgUrl = '../uploads/foods/' + item.image_url;
          }
        } else if (item.thumbnail_url) {
          if (item.thumbnail_url.startsWith('http')) {
            imgUrl = item.thumbnail_url;
          } else if (item.thumbnail_url.startsWith('/uploads/foods/')) {
            imgUrl = '..' + item.thumbnail_url;
          } else if (item.thumbnail_url.startsWith('/')) {
            imgUrl = '..' + item.thumbnail_url;
          } else {
            imgUrl = '../uploads/foods/' + item.thumbnail_url;
          }
        }
        
        return `
        <div class="d-flex align-items-center gap-3 p-2 border-bottom">
          <img src="${escapeHtml(imgUrl)}" alt="${escapeHtml(item.item_name)}" 
               style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; flex-shrink: 0;">
          <div class="flex-grow-1" style="min-width: 0;">
            <h6 class="mb-1 text-truncate">${escapeHtml(item.item_name)}</h6>
            <p class="text-muted small mb-0 text-truncate">${escapeHtml(item.description || '')}</p>
            <div class="d-flex align-items-center justify-content-between mt-1">
              <span class="text-primary fw-bold">₱${parseFloat(item.price).toFixed(2)}</span>
              <span class="badge bg-light text-dark" style="font-size: 0.75rem;">${escapeHtml(item.category_name)}</span>
            </div>
          </div>
          <div class="d-flex align-items-center gap-1" style="flex-shrink: 0;">
            <button type="button" class="btn btn-sm btn-outline-secondary px-2" onclick="updateFoodQty('${item.item_id}', -1)">-</button>
            <span id="food-qty-${item.item_id}" class="mx-1" style="min-width: 24px; text-align: center;">${qty}</span>
            <button type="button" class="btn btn-sm btn-outline-primary px-2" onclick="updateFoodQty('${item.item_id}', 1)">+</button>
          </div>
        </div>
      `}).join('') + paginationHtml;
    }
    
    function changeFoodPage(page) {
      foodCurrentPage = page;
      renderFoodMenu();
    }
    
    function updateFoodQty(itemId, delta) {
      const item = foodMenuItems.find(i => i.item_id === itemId);
      const existing = foodCart.find(c => c.item_id === itemId);
      
      if (existing) {
        existing.qty += delta;
        if (existing.qty <= 0) {
          foodCart = foodCart.filter(c => c.item_id !== itemId);
        }
      } else if (delta > 0) {
        // Fix image path for cart
        let cartImgUrl = '../uploads/foods/default-food.png';
        if (item.image_url) {
          if (item.image_url.startsWith('http')) {
            cartImgUrl = item.image_url;
          } else if (item.image_url.startsWith('/uploads/foods/')) {
            cartImgUrl = '..' + item.image_url;
          } else if (item.image_url.startsWith('/')) {
            cartImgUrl = '..' + item.image_url;
          } else {
            cartImgUrl = '../uploads/foods/' + item.image_url;
          }
        } else if (item.thumbnail_url) {
          if (item.thumbnail_url.startsWith('http')) {
            cartImgUrl = item.thumbnail_url;
          } else if (item.thumbnail_url.startsWith('/uploads/foods/')) {
            cartImgUrl = '..' + item.thumbnail_url;
          } else if (item.thumbnail_url.startsWith('/')) {
            cartImgUrl = '..' + item.thumbnail_url;
          } else {
            cartImgUrl = '../uploads/foods/' + item.thumbnail_url;
          }
        }
        
        foodCart.push({
          item_id: itemId,
          name: item.item_name,
          price: parseFloat(item.price),
          image: cartImgUrl,
          qty: 1
        });
      }
      
      updateFoodUI();
      // Re-render to show updated quantities on current page
      renderFoodMenu();
    }
    
    function updateFoodUI() {
      // Update quantity displays for visible items only
      foodMenuItems.forEach(item => {
        const existing = foodCart.find(c => c.item_id === item.item_id);
        const qtyEl = document.getElementById(`food-qty-${item.item_id}`);
        if (qtyEl) qtyEl.textContent = existing ? existing.qty : 0;
      });
      
      // Update summary
      const totalQty = foodCart.reduce((sum, item) => sum + item.qty, 0);
      const subtotal = foodCart.reduce((sum, item) => sum + (item.price * item.qty), 0);
      
      const summaryEl = document.getElementById('foodSummary');
      const cartSummaryEl = document.getElementById('foodCartSummary');
      const subtotalEl = document.getElementById('foodSubtotal');
      const inputEl = document.getElementById('foodItemsInput');
      
      if (totalQty > 0) {
        summaryEl.textContent = `${totalQty} item${totalQty > 1 ? 's' : ''} - ₱${subtotal.toFixed(2)}`;
        summaryEl.style.display = 'inline';
        cartSummaryEl.style.display = 'block';
        subtotalEl.textContent = '₱' + subtotal.toFixed(2);
        inputEl.value = JSON.stringify(foodCart);
      } else {
        summaryEl.style.display = 'none';
        cartSummaryEl.style.display = 'none';
        inputEl.value = '';
      }
    }
    
    function escapeHtml(text) {
      if (!text) return '';
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }
    
    // Listen for property changes to reload food menu
    if (propertySelect) {
      propertySelect.addEventListener('change', function() {
        // Reset pagination and cart when property changes
        foodCurrentPage = 1;
        
        if (this.value) {
          loadFoodMenu(this.value);
        } else {
          document.getElementById('foodMenuContainer').innerHTML = '<p class="text-muted text-center py-3">Select a property first to view available food items</p>';
          foodCart = [];
          updateFoodUI();
        }
      });
    }
  </script>

</body>

</html>