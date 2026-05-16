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
} catch (PDOException $e) {
    error_log("Failed to fetch data: " . $e->getMessage());
}

$companyName = $companyInfo['company_name'] ?? 'M&M Paraiso';
$phone = $companyInfo['phone_number'] ?? '+63 XXX XXX XXXX';
$email = $companyInfo['email'] ?? 'info@company.com';

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

        $selectedTime = strtotime($_POST['visit_time']);
        $earliestTime = strtotime($property['earliest_check_in'] ?? '08:00:00');
        $latestTime = strtotime($property['latest_check_out'] ?? '22:00:00');

        if ($selectedTime < $earliestTime || $selectedTime > $latestTime) {
            throw new Exception('Visit time must be between ' . substr($property['earliest_check_in'], 0, 5) . ' and ' . substr($property['latest_check_out'], 0, 5));
        }

        // Check for availability blocks
        $blockStmt = $pdo->prepare("
            SELECT COUNT(*) as block_count
            FROM room_availability_blocks
            WHERE room_id IN (SELECT room_id FROM rooms WHERE property_id = ? AND is_active = 1)
            AND is_active = 1
            AND date_start <= ?
            AND date_end >= ?
        ");
        $blockStmt->execute([$_POST['property_id'], $_POST['visit_date'], $_POST['visit_date']]);
        $blockCount = $blockStmt->fetch()['block_count'] ?? 0;

        // Check existing bookings for time slot
        $maxVisitorsPerSlot = 10;
        $resStmt = $pdo->prepare("
            SELECT COUNT(*) as visitor_count
            FROM reservations
            WHERE property_id = ?
            AND check_in_date = ?
            AND check_in_time = ?
            AND reservation_status IN ('pending', 'confirmed')
            AND booking_type = 'day_use'
        ");
        $resStmt->execute([$_POST['property_id'], $_POST['visit_date'], $_POST['visit_time']]);
        $existingVisitors = $resStmt->fetch()['visitor_count'] ?? 0;

        if ($existingVisitors >= $maxVisitorsPerSlot) {
            throw new Exception('This time slot is fully booked. Please select another time.');
        }

        // Generate UUID for reservation
        $reservationId = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        // Generate reservation code
        $year = date('Y');
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM reservations WHERE YEAR(created_at) = ?");
        $stmt->execute([$year]);
        $result = $stmt->fetch();
        $count = ($result['count'] ?? 0) + 1;
        $reservationCode = 'RSV-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Insert reservation
        $stmt = $pdo->prepare("
            INSERT INTO reservations (
                reservation_id, reservation_code, property_id, room_id, room_category_id,
                booking_type, reservation_status, payment_status,
                check_in_date, check_in_time,
                walkin_first_name, walkin_last_name, walkin_email, walkin_phone,
                num_adults, num_children, special_requests,
                source_channel, total_amount, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");

        $stmt->execute([
            $reservationId,
            $reservationCode,
            $_POST['property_id'],
            $_POST['room_id'] ?? null,
            $_POST['room_category_id'] ?? null,
            'day_use',
            'pending',
            'unpaid',
            $_POST['visit_date'],
            $_POST['visit_time'],
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['num_adults'] ?? 1,
            $_POST['num_children'] ?? 0,
            $_POST['visit_purpose'] . ': ' . ($_POST['message'] ?? '') ?? null,
            'website',
            0.00
        ]);

        $success = true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        error_log("Schedule visit error: " . $error);
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
              <form id="schedule-visit-form" action="" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                
                <!-- Room and Category Hidden Fields -->
                <?php if ($preselectedRoomId): ?>
                  <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($preselectedRoomId); ?>">
                <?php endif; ?>
                <?php if ($preselectedCategoryId): ?>
                  <input type="hidden" name="room_category_id" value="<?php echo htmlspecialchars($preselectedCategoryId); ?>">
                <?php endif; ?>
                
                <!-- Room/Category Info Display -->
                <?php if ($preselectedRoomId || $preselectedCategoryId): ?>
                  <?php
                  $roomInfo = null;
                  $categoryInfo = null;
                  if ($preselectedRoomId) {
                      $roomStmt = $pdo->prepare("
                          SELECT r.room_number, r.room_name, rc.category_name, p.property_name
                          FROM rooms r
                          INNER JOIN room_categories rc ON r.category_id = rc.category_id
                          INNER JOIN properties p ON r.property_id = p.property_id
                          WHERE r.room_id = ? AND r.is_active = 1
                      ");
                      $roomStmt->execute([$preselectedRoomId]);
                      $roomInfo = $roomStmt->fetch();
                  } elseif ($preselectedCategoryId) {
                      $catStmt = $pdo->prepare("
                          SELECT rc.category_name, p.property_name
                          FROM room_categories rc
                          INNER JOIN properties p ON rc.property_id = p.property_id
                          WHERE rc.category_id = ? AND rc.is_active = 1
                      ");
                      $catStmt->execute([$preselectedCategoryId]);
                      $categoryInfo = $catStmt->fetch();
                  }
                  ?>
                  <div class="alert alert-info mb-4">
                    <?php if ($roomInfo): ?>
                      <strong>Booking for Room:</strong> <?php echo htmlspecialchars($roomInfo['room_name'] ?: $roomInfo['room_number']); ?>
                      (<?php echo htmlspecialchars($roomInfo['category_name']); ?> at <?php echo htmlspecialchars($roomInfo['property_name']); ?>)
                    <?php elseif ($categoryInfo): ?>
                      <strong>Booking for Category:</strong> <?php echo htmlspecialchars($categoryInfo['category_name']); ?>
                      (<?php echo htmlspecialchars($categoryInfo['property_name']); ?>)
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
                          <div class="col-lg-3 mb-3">
                            <label for="visit_date" class="form-label">Check-in Date *</label>
                            <input type="date" name="visit_date" id="visit_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>" value="<?php echo htmlspecialchars($preselectedVisitDate); ?>">
                          </div>
                          <div class="col-lg-3 mb-3">
                            <label for="visit_time" class="form-label">Check-in Time *</label>
                            <select name="visit_time" id="visit_time" class="form-select" required>
                              <option value="">-- Select Time --</option>
                              <option value="08:00" <?php echo $preselectedVisitTime == '08:00' ? 'selected' : ''; ?>>8:00 AM</option>
                              <option value="09:00" <?php echo $preselectedVisitTime == '09:00' ? 'selected' : ''; ?>>9:00 AM</option>
                              <option value="10:00" <?php echo $preselectedVisitTime == '10:00' ? 'selected' : ''; ?>>10:00 AM</option>
                              <option value="11:00" <?php echo $preselectedVisitTime == '11:00' ? 'selected' : ''; ?>>11:00 AM</option>
                              <option value="12:00" <?php echo $preselectedVisitTime == '12:00' ? 'selected' : ''; ?>>12:00 PM</option>
                              <option value="13:00" <?php echo $preselectedVisitTime == '13:00' ? 'selected' : ''; ?>>1:00 PM</option>
                              <option value="14:00" <?php echo $preselectedVisitTime == '14:00' ? 'selected' : ''; ?>>2:00 PM</option>
                              <option value="15:00" <?php echo $preselectedVisitTime == '15:00' ? 'selected' : ''; ?>>3:00 PM</option>
                              <option value="16:00" <?php echo $preselectedVisitTime == '16:00' ? 'selected' : ''; ?>>4:00 PM</option>
                              <option value="17:00" <?php echo $preselectedVisitTime == '17:00' ? 'selected' : ''; ?>>5:00 PM</option>
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
                              <option value="08:00" <?php echo $preselectedCheckoutTime == '08:00' ? 'selected' : ''; ?>>8:00 AM</option>
                              <option value="09:00" <?php echo $preselectedCheckoutTime == '09:00' ? 'selected' : ''; ?>>9:00 AM</option>
                              <option value="10:00" <?php echo $preselectedCheckoutTime == '10:00' ? 'selected' : ''; ?>>10:00 AM</option>
                              <option value="11:00" <?php echo $preselectedCheckoutTime == '11:00' ? 'selected' : ''; ?>>11:00 AM</option>
                              <option value="12:00" <?php echo $preselectedCheckoutTime == '12:00' ? 'selected' : ''; ?>>12:00 PM</option>
                              <option value="13:00" <?php echo $preselectedCheckoutTime == '13:00' ? 'selected' : ''; ?>>1:00 PM</option>
                              <option value="14:00" <?php echo $preselectedCheckoutTime == '14:00' ? 'selected' : ''; ?>>2:00 PM</option>
                              <option value="15:00" <?php echo $preselectedCheckoutTime == '15:00' ? 'selected' : ''; ?>>3:00 PM</option>
                              <option value="16:00" <?php echo $preselectedCheckoutTime == '16:00' ? 'selected' : ''; ?>>4:00 PM</option>
                              <option value="17:00" <?php echo $preselectedCheckoutTime == '17:00' ? 'selected' : ''; ?>>5:00 PM</option>
                            </select>
                          </div>
                          <div class="col-lg-6 mb-3">
                            <label for="num_adults" class="form-label">Number of Adults *</label>
                            <input type="number" name="num_adults" id="num_adults" class="form-control" value="<?php echo htmlspecialchars($preselectedNumAdults); ?>" min="1" max="20" required>
                          </div>
                          <div class="col-lg-6 mb-3">
                            <label for="num_children" class="form-label">Number of Children</label>
                            <input type="number" name="num_children" id="num_children" class="form-control" value="0" min="0" max="20">
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

                  <!-- Availability Alert -->
                  <div class="col-lg-12 mb-3" id="availabilityAlert" style="display: none;">
                    <div class="alert alert-info mb-0">
                      <div id="availabilityContent"></div>
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
    async function checkAvailability() {
      const propertyId = propertySelect.value;
      const visitDate = dateInput.value;
      const visitTime = timeSelect.value;

      // Clear previous alerts
      availabilityAlert.style.display = 'none';
      operatingHoursDiv.style.display = 'none';
      submitButton.disabled = false;
      submitButton.textContent = 'Schedule Visit';

      if (!propertyId || !visitDate || !visitTime) {
        return;
      }

      try {
        const response = await fetch(`api/check-availability.php?property_id=${encodeURIComponent(propertyId)}&visit_date=${encodeURIComponent(visitDate)}&visit_time=${encodeURIComponent(visitTime)}`);
        const result = await response.json();

        if (result.success) {
          let alertHtml = '';
          let alertClass = 'alert-info';

          // Operating hours info
          if (result.operating_hours) {
            operatingHoursDiv.innerHTML = `<i class="fa fa-clock"></i> Operating hours: ${result.operating_hours.earliest} - ${result.operating_hours.latest}`;
            operatingHoursDiv.style.display = 'block';
          }

          // Time validation error
          if (!result.time_valid) {
            alertClass = 'alert-danger';
            alertHtml += `<p class="mb-1"><strong><i class="fa fa-exclamation-triangle"></i> Invalid Time</strong></p>`;
            alertHtml += `<p class="mb-0">${result.time_error}</p>`;
            submitButton.disabled = true;
            submitButton.textContent = 'Please select valid time';
          }

          // Availability blocks warning
          if (result.blocks && result.blocks.length > 0) {
            alertClass = 'alert-warning';
            alertHtml += `<p class="mb-1"><strong><i class="fa fa-exclamation-circle"></i> Scheduled Maintenance/Event</strong></p>`;
            result.blocks.forEach(block => {
              alertHtml += `<p class="mb-0">- ${block.type}${block.time_info}: ${block.reason}</p>`;
            });
            alertHtml += `<p class="mt-2 mb-0 text-muted small">You may still schedule a visit, but some areas may be inaccessible.</p>`;
          }

          // Slot availability
          if (result.slots_remaining !== undefined) {
            if (result.slots_remaining === 0) {
              alertClass = 'alert-danger';
              alertHtml += `<p class="mb-1"><strong><i class="fa fa-ban"></i> Fully Booked</strong></p>`;
              alertHtml += `<p class="mb-0">All ${result.max_slots} slots for this time are taken. Please select another time.</p>`;
              submitButton.disabled = true;
              submitButton.textContent = 'Time slot full - Select another';
            } else if (result.slots_remaining <= 3) {
              alertClass = alertClass === 'alert-info' ? 'alert-warning' : alertClass;
              alertHtml += `<p class="mb-1"><strong><i class="fa fa-users"></i> Limited Slots</strong></p>`;
              alertHtml += `<p class="mb-0">Only ${result.slots_remaining} slot${result.slots_remaining > 1 ? 's' : ''} remaining for this time.</p>`;
            } else {
              alertHtml += `<p class="mb-0"><i class="fa fa-check-circle text-success"></i> ${result.slots_remaining} slots available</p>`;
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

    // Add event listeners
    propertySelect.addEventListener('change', checkAvailability);
    dateInput.addEventListener('change', checkAvailability);
    timeSelect.addEventListener('change', checkAvailability);

    // Initial check if values are pre-filled
    if (propertySelect.value && dateInput.value && timeSelect.value) {
      checkAvailability();
    }
  </script>

</body>

</html>
