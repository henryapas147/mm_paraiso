<?php
require_once 'config/config.php';

// Fetch properties for check availability form
$properties = [];
try {
    $propStmt = $pdo->query("
        SELECT 
            p.property_id,
            p.property_name,
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
} catch (PDOException $e) {
    error_log("Failed to fetch properties: " . $e->getMessage());
}

// Fetch company information for contact section
$companyInfo = null;
try {
    $stmt = $pdo->prepare("SELECT * FROM company_information WHERE is_active = 1 LIMIT 1");
    $stmt->execute();
    $companyInfo = $stmt->fetch();
} catch (PDOException $e) {
    error_log("Failed to fetch company info: " . $e->getMessage());
}

// Check if user is logged in and get their info for contact form
$userInfo = null;
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("
            SELECT ua.email, up.first_name, up.last_name 
            FROM user_accounts ua 
            LEFT JOIN user_profiles up ON ua.user_id = up.user_id 
            WHERE ua.user_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $userInfo = $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Failed to fetch user info: " . $e->getMessage());
    }
}

// Pre-fill form for logged in users
$contactName = '';
$contactEmail = '';
if ($userInfo) {
    $contactName = trim(($userInfo['first_name'] ?? '') . ' ' . ($userInfo['last_name'] ?? ''));
    $contactEmail = $userInfo['email'] ?? '';
}

// Use database values or fallback to defaults
$phone = $companyInfo['phone_number'] ?? '010-020-0340';
$email = $companyInfo['email'] ?? 'info@villa.co';
$googleMapsUrl = $companyInfo['google_maps_embed_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12469.776493332698!2d-80.14036379941481!3d25.907788681148624!2m3!1f357.26927939317244!2f20.870722720054623!3f0!3m2!1i1024!2i768!4f35!3m3!1m2!1s0x88d9add4b4ac788f%3A0xe77469d09480fcdb!2sSunny%20Isles%20Beach!5e1!3m2!1sen!2sth!4v1642869952544!5m2!1sen!2sth';
$youtubeUrl = $companyInfo['youtube_url'] ?? null;
$companyDescription = $companyInfo['description'] ?? null;
$companyCheckinTime = null;

// Fetch featured room: highest-priced available room with a cover image
$featuredRoom = null;
$featuredRoomImage = null;
$featuredAmenities = [];
try {
    $featStmt = $pdo->query("
        SELECT 
            r.room_id,
            r.category_id,
            r.room_name,
            r.room_number,
            r.room_status,
            r.building_block,
            r.floor_number,
            COALESCE(r.override_price_per_night, rc.base_price_per_night) AS price_per_night,
            COALESCE(r.override_price_per_hour, rc.base_price_per_hour)   AS price_per_hour,
            COALESCE(r.override_price_day_use, rc.base_price_day_use)     AS price_day_use,
            rc.category_name,
            rc.description AS category_description,
            rc.max_adult_capacity,
            rc.max_child_capacity,
            rc.max_total_capacity,
            rc.floor_area_sqm,
            rc.bed_configuration,
            rc.view_type,
            rc.pet_friendly,
            rc.allows_overnight,
            rc.allows_hourly,
            rc.allows_day_use,
            rc.smoking_policy,
            p.property_name,
            p.check_in_time,
            p.check_out_time,
            COALESCE(
                (SELECT image_url FROM room_images WHERE room_id = r.room_id AND is_cover = 1 AND is_active = 1 LIMIT 1),
                (SELECT image_url FROM room_images WHERE room_id = r.room_id AND is_active = 1 ORDER BY image_id ASC LIMIT 1)
            ) AS cover_image
        FROM rooms r
        INNER JOIN room_categories rc ON r.category_id = rc.category_id
        INNER JOIN properties p ON r.property_id = p.property_id
        WHERE r.is_active = 1 AND rc.is_active = 1 AND p.is_active = 1
          AND r.room_status = 'available'
        ORDER BY COALESCE(r.override_price_per_night, rc.base_price_per_night) DESC
        LIMIT 1
    ");
    $featuredRoom = $featStmt->fetch(PDO::FETCH_ASSOC);
    if ($featuredRoom) {
        $featuredRoomImage = $featuredRoom['cover_image'] ? '../build/' . $featuredRoom['cover_image'] : 'assets/images/featured.jpg';
        $companyCheckinTime = $featuredRoom['check_in_time'];
        // Fetch up to 4 amenities for this room
        $amStmt = $pdo->prepare("
            SELECT a.amenity_name, a.icon_code
            FROM room_amenities ra
            JOIN amenities a ON ra.amenity_id = a.amenity_id
            WHERE ra.room_id = ?
            ORDER BY a.sort_order ASC
            LIMIT 4
        ");
        $amStmt->execute([$featuredRoom['room_id']]);
        $featuredAmenities = $amStmt->fetchAll(PDO::FETCH_ASSOC);
        // If no room-level amenities, try category-level
        if (empty($featuredAmenities)) {
            $amStmt2 = $pdo->prepare("
                SELECT a.amenity_name, a.icon_code
                FROM room_amenities ra
                JOIN amenities a ON ra.amenity_id = a.amenity_id
                WHERE ra.category_id = ? AND ra.room_id IS NULL
                ORDER BY a.sort_order ASC
                LIMIT 4
            ");
            $amStmt2->execute([$featuredRoom['category_id']]);
            $featuredAmenities = $amStmt2->fetchAll(PDO::FETCH_ASSOC);
        }
    }
} catch (PDOException $e) {
    error_log("Failed to fetch featured room: " . $e->getMessage());
}

// Fetch real stats
$statTotalRooms = 0;
$statTotalProperties = 0;
$statYearsActive = 1;
try {
    $statStmt = $pdo->query("SELECT COUNT(*) AS cnt FROM rooms WHERE is_active = 1");
    $statTotalRooms = (int)($statStmt->fetchColumn() ?? 0);

    $statStmt2 = $pdo->query("SELECT COUNT(*) AS cnt FROM properties WHERE is_active = 1");
    $statTotalProperties = (int)($statStmt2->fetchColumn() ?? 0);

    $statStmt3 = $pdo->query("SELECT MIN(created_at) AS earliest FROM properties WHERE is_active = 1");
    $earliest = $statStmt3->fetchColumn();
    if ($earliest) {
        $statYearsActive = max(1, (int)date('Y') - (int)date('Y', strtotime($earliest)));
    }
} catch (PDOException $e) {
    error_log("Failed to fetch stats: " . $e->getMessage());
}

// Build YouTube embed URL from channel/video URL
$youtubeEmbedUrl = null;
if ($youtubeUrl) {
    if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([\w-]{11})/', $youtubeUrl, $m)) {
        $youtubeEmbedUrl = 'https://www.youtube.com/embed/' . $m[1];
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

  <title>M&M Paraiso</title>


  <!--

TemplateMo 591 villa agency

https://templatemo.com/tm-591-villa-agency

-->
</head>

<body>

  <!-- ***** Preloader Start ***** -->
  <?php include('structure/preloader.php'); ?>
  <!-- ***** Preloader End ***** -->

  <?php include('structure/subheader.php'); ?>

  <!-- ***** Header Area Start ***** -->
  <?php include('structure/header.php'); ?>
  <!-- ***** Header Area End ***** -->

  <!-- Carousel Start -->
  <?php include('structure/carousel.php');?>
  <!-- Carousel End  -->

  <!-- Check Availability Section -->
  <div class="section" style="background: #f35525; padding: 40px 0; margin-top: -50px; position: relative; z-index: 10;">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="check-availability-form" style="background: white; border-radius: 10px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <h3 style="text-align: center; margin-bottom: 25px; color: #1e1e1e;">Check Availability</h3>
            <form id="checkAvailabilityForm">
              <div class="row">
                <div class="col-lg-3 col-md-6 mb-3">
                  <label for="checkin" style="font-weight: 500; font-size: 14px; margin-bottom: 8px; display: block;">Check-in Date</label>
                  <input type="date" id="checkin" name="checkin" class="form-control" style="padding: 12px; border: 1px solid #ddd; border-radius: 5px;" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                  <label for="checkin_time" style="font-weight: 500; font-size: 14px; margin-bottom: 8px; display: block;">Check-in Time</label>
                  <input type="time" id="checkin_time" name="checkin_time" class="form-control" style="padding: 12px; border: 1px solid #ddd; border-radius: 5px;" required value="14:00">
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                  <label for="checkout" style="font-weight: 500; font-size: 14px; margin-bottom: 8px; display: block;">Check-out Date</label>
                  <input type="date" id="checkout" name="checkout" class="form-control" style="padding: 12px; border: 1px solid #ddd; border-radius: 5px;" required>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                  <label for="checkout_time" style="font-weight: 500; font-size: 14px; margin-bottom: 8px; display: block;">Check-out Time</label>
                  <input type="time" id="checkout_time" name="checkout_time" class="form-control" style="padding: 12px; border: 1px solid #ddd; border-radius: 5px;" required value="12:00">
                </div>
                <div class="col-lg-6 col-md-6 mb-3">
                  <label for="guests" style="font-weight: 500; font-size: 14px; margin-bottom: 8px; display: block;">Guests</label>
                  <select id="guests" name="guests" class="form-select" style="padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                    <option value="1">1 Guest</option>
                    <option value="2">2 Guests</option>
                    <option value="3">3 Guests</option>
                    <option value="4">4 Guests</option>
                    <option value="5">5 Guests</option>
                    <option value="6">6+ Guests</option>
                  </select>
                </div>
                <div class="col-lg-6 col-md-6 mb-3" style="display: flex; align-items: flex-end;">
                  <button type="submit" class="btn btn-primary w-100" style="background: #f35525; border: none; padding: 12px 40px; border-radius: 5px; color: white; font-weight: 600; font-size: 16px; cursor: pointer; transition: all 0.3s;">Check Availability</button>
                </div>
              </div>
            </form>
            
            <!-- Availability Results -->
            <div id="availabilityResults" style="margin-top: 30px; display: none;">
              <h4 style="text-align: center; margin-bottom: 20px; color: #1e1e1e;">Available Rooms</h4>
              <div id="roomsResultsContainer" class="row"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="featured section">
    <div class="container">

      <!-- Row 1: Image + Room Header -->
      <div class="row align-items-center mb-4">
        <div class="col-lg-5">
          <div class="featured-room-image" style="position:relative; border-radius:12px; overflow:hidden; box-shadow:0 8px 30px rgba(0,0,0,0.12);">
            <?php if ($featuredRoom): ?>
              <a href="room-details.php?room_id=<?php echo htmlspecialchars($featuredRoom['room_id']); ?>&category_id=<?php echo htmlspecialchars($featuredRoom['category_id']); ?>">
                <img src="<?php echo htmlspecialchars($featuredRoomImage); ?>" alt="<?php echo htmlspecialchars($featuredRoom['room_name'] ?? $featuredRoom['room_number']); ?>" style="width:100%; height:360px; object-fit:cover; display:block;">
              </a>
              <?php if ($featuredRoom['room_status'] === 'available'): ?>
                <span style="position:absolute; top:15px; left:15px; background:#28a745; color:#fff; padding:5px 14px; border-radius:20px; font-size:12px; font-weight:600; z-index:2;">Available Now</span>
              <?php endif; ?>
            <?php else: ?>
              <img src="assets/images/featured.jpg" alt="Featured Room" style="width:100%; height:360px; object-fit:cover; display:block;">
            <?php endif; ?>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="section-heading" style="margin-bottom:15px;">
            <h6>| Featured Room</h6>
            <?php if ($featuredRoom): ?>
              <h2 style="width:100%;"><?php echo htmlspecialchars($featuredRoom['room_name'] ?? $featuredRoom['room_number']); ?></h2>
              <p style="color:#888; font-size:14px; margin-top:6px; margin-bottom:12px;">
                <strong style="color:#555;"><?php echo htmlspecialchars($featuredRoom['category_name']); ?></strong>
                <?php if ($featuredRoom['property_name']): ?>&nbsp;&mdash;&nbsp;<?php echo htmlspecialchars($featuredRoom['property_name']); ?><?php endif; ?>
              </p>
            <?php else: ?>
              <h2>Our Best Room &amp; Finest View</h2>
            <?php endif; ?>
          </div>

          <?php if ($featuredRoom): ?>
          <!-- Price -->
          <div style="margin-bottom:20px;">
            <?php if ($featuredRoom['price_per_night'] > 0): ?>
              <span style="font-size:28px; font-weight:700; color:#f35525;">&#8369;<?php echo number_format($featuredRoom['price_per_night'], 0); ?></span>
              <span style="font-size:14px; color:#999; margin-left:4px;">/ night</span>
            <?php endif; ?>
            <?php if ($featuredRoom['price_per_hour'] > 0): ?>
              <span style="font-size:14px; color:#888; margin-left:14px;">&#8369;<?php echo number_format($featuredRoom['price_per_hour'], 0); ?> / hr</span>
            <?php endif; ?>
            <?php if ($featuredRoom['price_day_use'] > 0): ?>
              <span style="font-size:14px; color:#888; margin-left:14px;">&#8369;<?php echo number_format($featuredRoom['price_day_use'], 0); ?> / day use</span>
            <?php endif; ?>
          </div>

          <!-- Quick detail chips -->
          <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:20px;">
            <?php if ($featuredRoom['max_total_capacity']): ?>
              <span style="background:#fff3f0; color:#f35525; border:1px solid #f35525; border-radius:20px; padding:4px 14px; font-size:13px;"><i class="fa fa-users" style="margin-right:5px;"></i><?php echo (int)$featuredRoom['max_total_capacity']; ?> Guests Max</span>
            <?php endif; ?>
            <?php if ($featuredRoom['floor_area_sqm']): ?>
              <span style="background:#f5f5f5; color:#555; border-radius:20px; padding:4px 14px; font-size:13px;"><i class="fa fa-expand" style="margin-right:5px;"></i><?php echo $featuredRoom['floor_area_sqm']; ?> m&sup2;</span>
            <?php endif; ?>
            <?php if ($featuredRoom['bed_configuration']): ?>
              <span style="background:#f5f5f5; color:#555; border-radius:20px; padding:4px 14px; font-size:13px;"><i class="fa fa-bed" style="margin-right:5px;"></i><?php echo htmlspecialchars($featuredRoom['bed_configuration']); ?></span>
            <?php endif; ?>
            <?php if ($featuredRoom['view_type']): ?>
              <span style="background:#f5f5f5; color:#555; border-radius:20px; padding:4px 14px; font-size:13px;"><i class="fa fa-eye" style="margin-right:5px;"></i><?php echo htmlspecialchars(ucwords($featuredRoom['view_type'])); ?> View</span>
            <?php endif; ?>
            <?php if ($featuredRoom['pet_friendly']): ?>
              <span style="background:#f5f5f5; color:#555; border-radius:20px; padding:4px 14px; font-size:13px;"><i class="fa fa-paw" style="margin-right:5px;"></i>Pet-Friendly</span>
            <?php endif; ?>
          </div>

          <!-- Booking type badges -->
          <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:25px;">
            <?php if ($featuredRoom['allows_overnight']): ?>
              <span style="background:#1e1e1e; color:#fff; border-radius:20px; padding:5px 14px; font-size:12px; font-weight:600;"><i class="fa fa-moon" style="margin-right:5px;"></i>Overnight</span>
            <?php endif; ?>
            <?php if ($featuredRoom['allows_hourly']): ?>
              <span style="background:#1e1e1e; color:#fff; border-radius:20px; padding:5px 14px; font-size:12px; font-weight:600;"><i class="fa fa-clock" style="margin-right:5px;"></i>Hourly</span>
            <?php endif; ?>
            <?php if ($featuredRoom['allows_day_use']): ?>
              <span style="background:#1e1e1e; color:#fff; border-radius:20px; padding:5px 14px; font-size:12px; font-weight:600;"><i class="fa fa-sun" style="margin-right:5px;"></i>Day Use</span>
            <?php endif; ?>
          </div>

          <!-- CTA button -->
          <div class="main-button">
            <a href="room-details.php?room_id=<?php echo htmlspecialchars($featuredRoom['room_id']); ?>&category_id=<?php echo htmlspecialchars($featuredRoom['category_id']); ?>">View Room Details</a>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Row 2: Accordion + Info Table -->
      <div class="row mt-3">
        <div class="col-lg-8">
          <div class="accordion" id="accordionExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Room Details &amp; Capacity
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <?php if ($featuredRoom): ?>
                    <ul class="featured-accordion-list">
                      <?php if ($featuredRoom['max_adult_capacity']): ?><li><i class="fa fa-user"></i> Up to <strong><?php echo (int)$featuredRoom['max_adult_capacity']; ?> adults</strong><?php if ($featuredRoom['max_child_capacity']): ?> &amp; <?php echo (int)$featuredRoom['max_child_capacity']; ?> children<?php endif; ?></li><?php endif; ?>
                      <?php if ($featuredRoom['floor_area_sqm']): ?><li><i class="fa fa-expand"></i> <?php echo $featuredRoom['floor_area_sqm']; ?> m&sup2; floor area</li><?php endif; ?>
                      <?php if ($featuredRoom['bed_configuration']): ?><li><i class="fa fa-bed"></i> <?php echo htmlspecialchars($featuredRoom['bed_configuration']); ?></li><?php endif; ?>
                      <?php if ($featuredRoom['view_type']): ?><li><i class="fa fa-eye"></i> <?php echo htmlspecialchars(ucwords($featuredRoom['view_type'])); ?> View</li><?php endif; ?>
                      <?php if ($featuredRoom['building_block']): ?><li><i class="fa fa-building"></i> <?php echo htmlspecialchars($featuredRoom['building_block']); ?><?php if ($featuredRoom['floor_number']): ?>, Floor <?php echo (int)$featuredRoom['floor_number']; ?><?php endif; ?></li><?php endif; ?>
                      <?php if ($featuredRoom['smoking_policy'] === 'non_smoking'): ?><li><i class="fa fa-ban"></i> Non-Smoking Room</li><?php endif; ?>
                    </ul>
                  <?php else: ?>
                    <p>Contact us to learn more about our available rooms and accommodations.</p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Booking Options
                </button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <?php if ($featuredRoom): ?>
                    <ul class="featured-accordion-list">
                      <?php if ($featuredRoom['allows_overnight']): ?><li><i class="fa fa-moon"></i> <strong>Overnight Stay</strong> &mdash; Check-in <?php echo $featuredRoom['check_in_time'] ? date('g:i A', strtotime($featuredRoom['check_in_time'])) : '2:00 PM'; ?>, Check-out <?php echo $featuredRoom['check_out_time'] ? date('g:i A', strtotime($featuredRoom['check_out_time'])) : '12:00 PM'; ?></li><?php endif; ?>
                      <?php if ($featuredRoom['allows_hourly']): ?><li><i class="fa fa-clock"></i> <strong>Hourly Booking</strong> available</li><?php endif; ?>
                      <?php if ($featuredRoom['allows_day_use']): ?><li><i class="fa fa-sun"></i> <strong>Day Use</strong> available</li><?php endif; ?>
                      <?php if ($featuredRoom['pet_friendly']): ?><li><i class="fa fa-paw"></i> <strong>Pet-Friendly</strong> room</li><?php endif; ?>
                    </ul>
                  <?php else: ?>
                    <p>We offer overnight stays, day use, and hourly bookings to suit your schedule.</p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  Amenities &amp; Features
                </button>
              </h2>
              <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <?php if (!empty($featuredAmenities)): ?>
                    <ul class="featured-accordion-list">
                      <?php foreach ($featuredAmenities as $amenity): ?>
                        <li><i class="fa fa-check-circle"></i> <?php echo htmlspecialchars($amenity['amenity_name']); ?></li>
                      <?php endforeach; ?>
                    </ul>
                  <?php elseif ($featuredRoom): ?>
                    <p>Contact us or view the room details page for a full list of amenities.</p>
                  <?php else: ?>
                    <p>All our rooms come with premium amenities for a comfortable and relaxing stay.</p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="info-table">
            <ul>
              <li>
                <img src="assets/images/info-icon-01.png" alt="" style="max-width: 52px;">
                <h4><?php echo $statTotalRooms > 0 ? $statTotalRooms : '—'; ?><br><span>Total Rooms</span></h4>
              </li>
              <li>
                <img src="assets/images/info-icon-02.png" alt="" style="max-width: 52px;">
                <h4><?php echo $statTotalProperties > 0 ? $statTotalProperties : '—'; ?><br><span><?php echo $statTotalProperties === 1 ? 'Property' : 'Properties'; ?></span></h4>
              </li>
              <li>
                <img src="assets/images/info-icon-03.png" alt="" style="max-width: 52px;">
                <h4><?php echo $featuredRoom && $featuredRoom['check_in_time'] ? date('g:i A', strtotime($featuredRoom['check_in_time'])) : '2:00 PM'; ?><br><span>Check-in Time</span></h4>
              </li>
              <li>
                <img src="assets/images/info-icon-04.png" alt="" style="max-width: 52px;">
                <h4><?php echo $featuredRoom && $featuredRoom['check_out_time'] ? date('g:i A', strtotime($featuredRoom['check_out_time'])) : '12:00 PM'; ?><br><span>Check-out Time</span></h4>
              </li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Booking Policies Section -->
  <div class="section" style="background: #fafafa;">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 offset-lg-4">
          <div class="section-heading text-center" style="margin-bottom: 40px;">
            <h6>| Booking Policies</h6>
            <h2>Important Information</h2>
          </div>
        </div>
      </div>
      <div class="row" id="policiesContainer">
        <div class="col-lg-12 text-center">
          <p>Loading booking policies...</p>
        </div>
      </div>
    </div>
  </div>

  <div class="video section">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 offset-lg-4">
          <div class="section-heading text-center">
            <h6>| Video View</h6>
            <h2>Get Closer View & Different Feeling</h2>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="video-content">
    <div class="container">
      <div class="row">
        <div class="col-lg-10 offset-lg-1">
          <div class="video-frame">
            <?php if ($youtubeEmbedUrl): ?>
              <div style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden; border-radius:10px; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
                <iframe src="<?php echo htmlspecialchars($youtubeEmbedUrl); ?>" title="<?php echo htmlspecialchars($companyInfo['company_name'] ?? 'M&M Paraiso'); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position:absolute; top:0; left:0; width:100%; height:100%; border-radius:10px;"></iframe>
              </div>
            <?php else: ?>
              <img src="assets/images/video-frame.jpg" alt="">
              <?php if ($youtubeUrl): ?>
                <a href="<?php echo htmlspecialchars($youtubeUrl); ?>" target="_blank"><i class="fa fa-play"></i></a>
              <?php else: ?>
                <a href="https://youtube.com" target="_blank"><i class="fa fa-play"></i></a>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="fun-facts">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="wrapper">
            <div class="row">
              <div class="col-lg-4">
                <div class="counter">
                  <h2 class="timer count-title count-number" data-to="<?php echo $statTotalRooms; ?>" data-speed="1000"></h2>
                  <p class="count-text">Rooms<br>Available</p>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="counter">
                  <h2 class="timer count-title count-number" data-to="<?php echo $statTotalProperties; ?>" data-speed="1000"></h2>
                  <p class="count-text">Active<br>Properties</p>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="counter">
                  <h2 class="timer count-title count-number" data-to="<?php echo $statYearsActive; ?>" data-speed="1000"></h2>
                  <p class="count-text">Years<br>Experience</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Special Offers Section -->
  <div class="section best-deal">
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <div class="section-heading">
            <h6>| Special Offers</h6>
            <h2>Exclusive Deals & Promotions</h2>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="tabs-content">
            <div class="row">
              <div class="nav-wrapper">
                <ul class="nav nav-tabs" role="tablist" id="specialOffersTabs">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-deals-tab" data-bs-toggle="tab" data-bs-target="#all-deals" type="button" role="tab" aria-controls="all-deals" aria-selected="true">All Deals</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="seasonal-tab" data-bs-toggle="tab" data-bs-target="#seasonal" type="button" role="tab" aria-controls="seasonal" aria-selected="false">Seasonal</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="packages-tab" data-bs-toggle="tab" data-bs-target="#packages" type="button" role="tab" aria-controls="packages" aria-selected="false">Packages</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="early-bird-tab" data-bs-toggle="tab" data-bs-target="#early-bird" type="button" role="tab" aria-controls="early-bird" aria-selected="false">Early Bird</button>
                  </li>
                </ul>
              </div>
              <div class="tab-content" id="specialOffersContent">
                <div class="tab-pane fade show active" id="all-deals" role="tabpanel" aria-labelledby="all-deals-tab">
                  <div class="row" id="allDealsContainer">
                    <div class="col-lg-12 text-center">
                      <p>Loading special offers...</p>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="seasonal" role="tabpanel" aria-labelledby="seasonal-tab">
                  <div class="row" id="seasonalContainer">
                    <div class="col-lg-12 text-center">
                      <p>Loading seasonal offers...</p>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="packages" role="tabpanel" aria-labelledby="packages-tab">
                  <div class="row" id="packagesContainer">
                    <div class="col-lg-12 text-center">
                      <p>Loading packages...</p>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="early-bird" role="tabpanel" aria-labelledby="early-bird-tab">
                  <div class="row" id="earlyBirdContainer">
                    <div class="col-lg-12 text-center">
                      <p>Loading early bird offers...</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="properties section">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 offset-lg-4">
          <div class="section-heading text-center">
            <h6>| Rooms</h6>
            <h2>Choose Your Perfect Room</h2>
          </div>
        </div>
      </div>
      <div class="row" id="roomsContainer">
        <div class="col-lg-12 text-center">
          <p>Loading rooms...</p>
        </div>
      </div>
      <div class="row" style="margin-top: 30px;">
        <div class="col-lg-12 text-center">
          <div class="main-button">
            <a href="rooms.php">View All Rooms</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="contact section">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 offset-lg-4">
          <div class="section-heading text-center">
            <h6>| Contact Us</h6>
            <h2>Get In Touch With Our Agents</h2>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="contact-content">
    <div class="container">
      <div class="row">
        <div class="col-lg-7">
          <div id="map">
            <iframe src="<?php echo htmlspecialchars($googleMapsUrl); ?>" width="100%" height="500px" frameborder="0" style="border:0; border-radius: 10px; box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.15);" allowfullscreen=""></iframe>
          </div>
          <p>We'd love to hear from you! Whether you have a question about our properties, pricing, need assistance with booking, or anything else, our team is ready to answer all your questions.</p>
          <div class="row">
            <div class="col-lg-6">
              <div class="item phone">
                <img src="assets/images/phone-icon.png" alt="" style="max-width: 52px;">
                <h6><?php echo htmlspecialchars($phone); ?><br><span>Phone Number</span></h6>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="item email">
                <img src="assets/images/email-icon.png" alt="" style="max-width: 52px;">
                <h6><?php echo htmlspecialchars($email); ?><br><span>Business Email</span></h6>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <!-- Message container for AJAX responses -->
          <div id="formMessage"></div>

          <form id="contact-form">
            <div class="row">
              <div class="col-lg-12">
                <fieldset>
                  <label for="name">Full Name</label>
                  <input type="text" name="name" id="name" placeholder="Your Name..." autocomplete="on" required value="<?php echo htmlspecialchars($contactName); ?>">
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="email">Email Address</label>
                  <input type="email" name="email" id="email" placeholder="Your E-mail..." required value="<?php echo htmlspecialchars($contactEmail); ?>">
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="subject">Subject</label>
                  <input type="text" name="subject" id="subject" placeholder="Subject..." autocomplete="on">
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="message">Message</label>
                  <textarea name="message" id="message" placeholder="Your Message" required></textarea>
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <button type="submit" id="form-submit" class="orange-button">
                    <span id="btnText">Send Message</span>
                    <span id="btnSpinner" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Sending...</span>
                  </button>
                </fieldset>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php include('structure/footer.php');?>

  <!-- Scripts -->
  <?php include('structure/scripts.php');?>

  <script>
    // Helper function to escape HTML
    function escapeHtml(text) {
      if (!text) return '';
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    // Helper function to format property type
    function formatPropertyType(type) {
      if (!type) return '';
      return type.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
    }

    // Helper function to get property type class
    function getPropertyTypeClass(type) {
      const typeMap = {
        'resort': 'resort',
        'hotel': 'hotel',
        'villa': 'villa',
        'beach_resort': 'beach',
        'mountain_resort': 'mountain',
        'pension_house': 'pension',
        'hostel': 'hostel',
        'guest_house': 'guest',
        'apartment': 'apartment',
        'motel': 'motel',
        'house': 'house',
        'other': 'other'
      };
      return typeMap[type] || 'other';
    }

    // Fetch and render rooms
    async function fetchRooms() {
      try {
        const response = await fetch('api/rooms.php?action=list&page=1&per_page=6');
        const result = await response.json();

        if (result.success && result.data) {
          renderRooms(result.data.data);
        } else {
          document.getElementById('roomsContainer').innerHTML = `
            <div class="col-lg-12 text-center">
              <p>Error loading rooms. Please try again later.</p>
            </div>
          `;
        }
      } catch (error) {
        console.error('Error fetching rooms:', error);
        document.getElementById('roomsContainer').innerHTML = `
          <div class="col-lg-12 text-center">
            <p>Error loading rooms. Please try again later.</p>
          </div>
        `;
      }
    }

    // Render rooms
    function renderRooms(rooms) {
      const container = document.getElementById('roomsContainer');

      if (!rooms || rooms.length === 0) {
        container.innerHTML = `
          <div class="col-lg-12 text-center">
            <p>No rooms available at this time.</p>
          </div>
        `;
        return;
      }

      container.innerHTML = rooms.map(room => {
        // Get room image
        let roomImage = 'assets/images/property-01.jpg';
        if (room.room_image) {
          roomImage = '../build/' + room.room_image;
        } else if (room.images && room.images.length > 0) {
          const coverImage = room.images.find(img => img.is_cover) || room.images[0];
          roomImage = '../build/' + coverImage.image_url;
        }

        // Room status badge
        const statusColors = {
          'available': '#e8f5e9',
          'occupied': '#fff3e0',
          'reserved': '#e3f2fd',
          'cleaning': '#fce4ec',
          'maintenance': '#ffebee',
          'out_of_order': '#37474f'
        };
        const statusTextColors = {
          'available': '#2e7d32',
          'occupied': '#e65100',
          'reserved': '#1565c0',
          'cleaning': '#c2185b',
          'maintenance': '#c62828',
          'out_of_order': '#fff'
        };
        const statusBadge = room.room_status 
          ? `<span style="background: ${statusColors[room.room_status] || '#e0e0e0'}; color: ${statusTextColors[room.room_status] || '#424242'}; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase;">${room.room_status.replace('_', ' ')}</span>`
          : '';

        // Format prices
        const priceHtml = `
          <div style="font-size: 16px; color: #666; margin-bottom: 5px;">
            ${room.base_price_per_night ? `<span style="color: #f35525; font-weight: 700; font-size: 18px;">₱${parseFloat(room.base_price_per_night).toLocaleString()}</span> / night` : ''}
            ${room.base_price_per_hour ? `<span style="margin-left: 10px;">| ₱${parseFloat(room.base_price_per_hour).toLocaleString()} / hr</span>` : ''}
          </div>
        `;

        // Capacity info
        const capacityHtml = `
          <ul style="font-size: 13px; color: #666; margin-bottom: 10px; list-style: none; padding: 0;">
            ${room.max_adult_capacity ? `<li style="display: inline-block; margin-right: 15px;"><i class="fa fa-user" style="color: #f35525;"></i> ${room.max_adult_capacity} Adults</li>` : ''}
            ${room.max_child_capacity ? `<li style="display: inline-block; margin-right: 15px;"><i class="fa fa-child" style="color: #f35525;"></i> ${room.max_child_capacity} Children</li>` : ''}
            ${room.max_total_capacity ? `<li style="display: inline-block; margin-right: 15px;"><i class="fa fa-users" style="color: #f35525;"></i> Max ${room.max_total_capacity}</li>` : ''}
          </ul>
        `;

        // Room details
        const detailsHtml = `
          <ul style="font-size: 13px; color: #666; margin-bottom: 10px; list-style: none; padding: 0;">
            ${room.floor_area_sqm ? `<li style="display: inline-block; margin-right: 15px;"><i class="fa fa-expand" style="color: #f35525;"></i> ${room.floor_area_sqm} m²</li>` : ''}
            ${room.bed_configuration ? `<li style="display: inline-block; margin-right: 15px;"><i class="fa fa-bed" style="color: #f35525;"></i> ${escapeHtml(room.bed_configuration)}</li>` : ''}
            ${room.view_type ? `<li style="display: inline-block; margin-right: 15px;"><i class="fa fa-eye" style="color: #f35525;"></i> ${escapeHtml(room.view_type)} View</li>` : ''}
          </ul>
        `;

        // Location info
        const locationHtml = `
          <p style="font-size: 12px; color: #999; margin-bottom: 10px;">
            <i class="fa fa-building"></i> ${escapeHtml(room.building_block || 'Main Building')} • Floor ${room.floor_number || '1'}
          </p>
        `;

        return `
          <div class="col-lg-4 col-md-6">
            <div class="item" style="background: #fafafa; border-radius: 10px; padding: 20px;">
              <div style="position: relative;">
                <a href="room-details.php?room_id=${escapeHtml(room.room_id)}&category_id=${escapeHtml(room.category_id)}">
                  <img src="${escapeHtml(roomImage)}" alt="${escapeHtml(room.room_name || room.room_number)}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                </a>
                <div style="position: absolute; top: 10px; right: 10px;">
                  ${statusBadge}
                </div>
              </div>
              <span class="category">${escapeHtml(room.category_name)}</span>
              <h4><a href="room-details.php?room_id=${escapeHtml(room.room_id)}&category_id=${escapeHtml(room.category_id)}">${escapeHtml(room.room_name || room.room_number)}</a></h4>
              ${locationHtml}
              ${capacityHtml}
              ${detailsHtml}
              ${priceHtml}
              <div class="main-button">
                <a href="room-details.php?room_id=${escapeHtml(room.room_id)}&category_id=${escapeHtml(room.category_id)}">View Details</a>
              </div>
            </div>
          </div>
        `;
      }).join('');
    }

    // Render available rooms from availability check
    function renderAvailableRooms(rooms, checkin, checkout, guests, checkinTime, checkoutTime) {
      const container = document.getElementById('roomsResultsContainer');
      
      if (rooms.length === 0) {
        container.innerHTML = '<div class="col-lg-12 text-center"><p>No rooms available for the selected dates. Please try different dates.</p></div>';
        return;
      }

      container.innerHTML = rooms.map(room => {
        const roomImage = room.room_image ? '../build/' + room.room_image : 'assets/images/property-01.jpg';
        const locationParts = [];
        if (room.barangay_name) locationParts.push(room.barangay_name);
        if (room.city_municipality_name) locationParts.push(room.city_municipality_name);
        if (room.province_name) locationParts.push(room.province_name);
        const location = locationParts.join(', ') || 'Location not available';
        
        // Format price (use override prices if available, otherwise category prices)
        const pricePerNight = room.override_price_per_night || room.base_price_per_night;
        const pricePerHour = room.override_price_per_hour || room.base_price_per_hour;
        const priceDayUse = room.override_price_day_use || room.base_price_day_use;

        let priceHtml = '';
        if (pricePerNight > 0) {
          priceHtml = `<div style="font-size: 18px; color: #f35525; font-weight: 700; margin: 10px 0;">₱${parseFloat(pricePerNight).toLocaleString()} <small>/ night</small></div>`;
        } else if (priceDayUse > 0) {
          priceHtml = `<div style="font-size: 18px; color: #f35525; font-weight: 700; margin: 10px 0;">₱${parseFloat(priceDayUse).toLocaleString()} <small>/ day use</small></div>`;
        }

        // Capacity info
        const capacityHtml = `
          <ul style="font-size: 13px; color: #666; margin-bottom: 10px; list-style: none; padding: 0;">
            ${room.max_adult_capacity ? `<li style="display: inline-block; margin-right: 15px;"><i class="fa fa-user" style="color: #f35525;"></i> ${room.max_adult_capacity} Adults</li>` : ''}
            ${room.max_child_capacity ? `<li style="display: inline-block; margin-right: 15px;"><i class="fa fa-child" style="color: #f35525;"></i> ${room.max_child_capacity} Children</li>` : ''}
          </ul>
        `;

        // Room details
        const detailsHtml = `
          <ul style="font-size: 13px; color: #666; margin-bottom: 10px; list-style: none; padding: 0;">
            ${room.floor_area_sqm ? `<li style="display: inline-block; margin-right: 15px;"><i class="fa fa-expand" style="color: #f35525;"></i> ${room.floor_area_sqm} m²</li>` : ''}
            ${room.bed_configuration ? `<li style="display: inline-block; margin-right: 15px;"><i class="fa fa-bed" style="color: #f35525;"></i> ${escapeHtml(room.bed_configuration)}</li>` : ''}
          </ul>
        `;

        // Build booking URL
        const bookingParams = new URLSearchParams();
        if (room.property_id) bookingParams.append('property_id', room.property_id);
        bookingParams.append('room_id', room.room_id);
        bookingParams.append('room_category_id', room.category_id);
        bookingParams.append('visit_date', checkin);
        bookingParams.append('visit_time', checkinTime || '14:00');
        if (checkout) bookingParams.append('checkout_date', checkout);
        if (checkoutTime) bookingParams.append('checkout_time', checkoutTime);
        bookingParams.append('num_adults', guests);

        return `
          <div class="col-lg-4 col-md-6 mb-30">
            <div class="item" style="background: #fafafa; border-radius: 10px; padding: 20px;">
              <div style="position: relative;">
                <img src="${escapeHtml(roomImage)}" alt="${escapeHtml(room.room_name || room.room_number)}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 15px;">
              </div>
              <span class="category">${escapeHtml(room.category_name)}</span>
              <h4>${escapeHtml(room.room_name || room.room_number)}</h4>
              <p style="font-size: 13px; color: #666; margin: 5px 0;">${escapeHtml(room.property_name)} - ${escapeHtml(location)}</p>
              ${capacityHtml}
              ${detailsHtml}
              ${priceHtml}
              <div class="main-button" style="margin-top: 20px;">
                <a href="schedule-visit.php?${bookingParams.toString()}" style="width: 100%; text-align: center; display: block;">Book This Room</a>
              </div>
            </div>
          </div>
        `;
      }).join('');
    }

    // Render special offers section (fetch from API)
    async function renderSpecialOffers() {
      try {
        const response = await fetch('api/promotions.php');
        const result = await response.json();

        if (result.success && result.promotions) {
          const specialOffers = result.promotions;

          // Render offer card
          const renderOfferCard = (offer) => `
            <div class="col-lg-4 col-md-6">
              <div class="item" style="background: #fafafa; border-radius: 10px; padding: 25px; margin-bottom: 30px; border-left: 4px solid #f35525;">
                ${offer.image ? `
                  <div style="margin-bottom: 15px; border-radius: 8px; overflow: hidden;">
                    <img src="${offer.image}" alt="${offer.title}" style="width: 100%; height: 150px; object-fit: cover;">
                  </div>
                ` : `
                  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <span style="background: #f35525; color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: 600;">${offer.discount}</span>
                    <i class="fa fa-tag" style="font-size: 24px; color: #f35525;"></i>
                  </div>
                `}
                <h4 style="margin-bottom: 10px; color: #2a2a2a;">${offer.title}</h4>
                <p style="color: #666; font-size: 14px; margin-bottom: 15px; line-height: 1.6;">${offer.description || 'Special promotion for your stay.'}</p>
                ${offer.code ? `
                  <div style="background: #fff3cd; padding: 8px 12px; border-radius: 5px; margin-bottom: 15px; border: 1px dashed #ffc107;">
                    <span style="font-size: 12px; color: #856404; font-weight: 600;">Code: ${offer.code}</span>
                  </div>
                ` : ''}
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                  <span style="font-size: 12px; color: #999;"><i class="fa fa-calendar"></i> Valid until: ${offer.valid_until}</span>
                  <a href="schedule-visit.php?promo=${offer.code}" style="color: #f35525; font-size: 14px; font-weight: 600; text-decoration: none;">Book Now &rarr;</a>
                </div>
                ${offer.usage_remaining !== null ? `
                  <div style="margin-top: 10px; font-size: 11px; color: #666;">
                    <i class="fa fa-users"></i> ${offer.usage_remaining} remaining
                  </div>
                ` : ''}
              </div>
            </div>
          `;

          // Render all offers
          document.getElementById('allDealsContainer').innerHTML = specialOffers.length > 0
            ? specialOffers.map(renderOfferCard).join('')
            : '<div class="col-lg-12 text-center"><p>No special offers available at this time.</p></div>';

          // Render by type
          const seasonalOffers = specialOffers.filter(o => o.type === 'seasonal');
          document.getElementById('seasonalContainer').innerHTML = seasonalOffers.length > 0
            ? seasonalOffers.map(renderOfferCard).join('')
            : '<div class="col-lg-12 text-center"><p>No seasonal offers available at this time.</p></div>';

          const packageOffers = specialOffers.filter(o => o.type === 'packages');
          document.getElementById('packagesContainer').innerHTML = packageOffers.length > 0
            ? packageOffers.map(renderOfferCard).join('')
            : '<div class="col-lg-12 text-center"><p>No packages available at this time.</p></div>';

          const earlyBirdOffers = specialOffers.filter(o => o.type === 'early-bird');
          document.getElementById('earlyBirdContainer').innerHTML = earlyBirdOffers.length > 0
            ? earlyBirdOffers.map(renderOfferCard).join('')
            : '<div class="col-lg-12 text-center"><p>No early bird offers available at this time.</p></div>';
        } else {
          // Fallback to empty state
          const emptyMessage = '<div class="col-lg-12 text-center"><p>No special offers available at this time.</p></div>';
          document.getElementById('allDealsContainer').innerHTML = emptyMessage;
          document.getElementById('seasonalContainer').innerHTML = emptyMessage;
          document.getElementById('packagesContainer').innerHTML = emptyMessage;
          document.getElementById('earlyBirdContainer').innerHTML = emptyMessage;
        }
      } catch (error) {
        console.error('Failed to load promotions:', error);
        const errorMessage = '<div class="col-lg-12 text-center"><p>Failed to load special offers. Please try again later.</p></div>';
        document.getElementById('allDealsContainer').innerHTML = errorMessage;
        document.getElementById('seasonalContainer').innerHTML = errorMessage;
        document.getElementById('packagesContainer').innerHTML = errorMessage;
        document.getElementById('earlyBirdContainer').innerHTML = errorMessage;
      }
    }

    // Fetch booking policies (global policies without property filter)
    async function fetchBookingPolicies() {
      try {
        const response = await fetch('api/booking_policies.php?action=list');
        const result = await response.json();

        if (result.success && result.data && result.data.length > 0) {
          renderBookingPolicies(result.data);
        } else {
          document.getElementById('policiesContainer').innerHTML = '<div class="col-lg-12 text-center"><p>No booking policies available at this time.</p></div>';
        }
      } catch (error) {
        console.error('Error fetching booking policies:', error);
        document.getElementById('policiesContainer').innerHTML = '<div class="col-lg-12 text-center"><p>Failed to load booking policies.</p></div>';
      }
    }

    // Render booking policies
    function renderBookingPolicies(policies) {
      const container = document.getElementById('policiesContainer');

      // Group policies by type
      const policiesByType = {};
      policies.forEach(policy => {
        if (!policiesByType[policy.policy_type]) {
          policiesByType[policy.policy_type] = [];
        }
        policiesByType[policy.policy_type].push(policy);
      });

      let html = '';

      // Render each policy type
      for (const [type, typePolicies] of Object.entries(policiesByType)) {
        const typeLabel = type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        const typeIcon = type === 'cancellation' ? 'fa-times-circle' :
                        type === 'deposit' ? 'fa-money-bill' :
                        type === 'check_in' ? 'fa-sign-in-alt' :
                        type === 'check_out' ? 'fa-sign-out-alt' : 'fa-info-circle';

        html += `
          <div class="col-lg-6" style="margin-bottom: 30px;">
            <div style="background: white; border-radius: 10px; padding: 25px; border-left: 4px solid #f35525; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
              <h4 style="margin-bottom: 20px; color: #2a2a2a;">
                <i class="fa ${typeIcon}" style="color: #f35525; margin-right: 10px;"></i>
                ${typeLabel}
              </h4>
              <div class="row">
                ${typePolicies.map(policy => `
                  <div class="col-lg-12" style="margin-bottom: 20px;">
                    <div style="background: #fafafa; padding: 15px; border-radius: 8px;">
                      <h5 style="margin-bottom: 10px; color: #f35525; font-size: 16px;">${escapeHtml(policy.policy_name)}</h5>
                      ${policy.description ? `<p style="color: #666; font-size: 14px; margin-bottom: 10px; line-height: 1.6;">${escapeHtml(policy.description)}</p>` : ''}
                      <div style="font-size: 13px; color: #444;">
                        ${policy.free_cancel_hours !== null ? `<div><strong>Free Cancellation:</strong> Up to ${policy.free_cancel_hours} hours before check-in</div>` : ''}
                        ${policy.penalty_type && policy.penalty_value ? `<div><strong>Penalty:</strong> ${policy.penalty_type === 'percentage' ? policy.penalty_value + '%' : '₱' + parseFloat(policy.penalty_value).toLocaleString('en-PH')} ${policy.penalty_type === 'percentage' ? 'of total' : 'flat fee'}</div>` : ''}
                        ${policy.deposit_type && policy.deposit_value ? `<div><strong>Deposit:</strong> ${policy.deposit_type === 'percentage' ? policy.deposit_value + '%' : '₱' + parseFloat(policy.deposit_value).toLocaleString('en-PH')} ${policy.deposit_type === 'percentage' ? 'of total' : 'flat fee'}</div>` : ''}
                        ${policy.min_nights ? `<div><strong>Minimum Nights:</strong> ${policy.min_nights} night${policy.min_nights > 1 ? 's' : ''}</div>` : ''}
                        ${policy.property_name ? `<div><strong>Applies to:</strong> ${escapeHtml(policy.property_name)}</div>` : ''}
                      </div>
                    </div>
                  </div>
                `).join('')}
              </div>
            </div>
          </div>
        `;
      }

      container.innerHTML = html;
    }

    // Load content when page loads
    document.addEventListener('DOMContentLoaded', async () => {
      fetchRooms();
      await renderSpecialOffers();
      fetchBookingPolicies();
      
      // Handle check availability form
      const checkAvailabilityForm = document.getElementById('checkAvailabilityForm');
      if (checkAvailabilityForm) {
        checkAvailabilityForm.addEventListener('submit', async function(e) {
          e.preventDefault();
          
          const checkin = document.getElementById('checkin').value;
          const checkinTime = document.getElementById('checkin_time').value;
          const checkout = document.getElementById('checkout').value;
          const checkoutTime = document.getElementById('checkout_time').value;
          const guests = document.getElementById('guests').value;
          
          // Validate dates
          if (checkin && checkout) {
            const checkinDate = new Date(checkin);
            const checkoutDate = new Date(checkout);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (checkinDate < today) {
              alert('Check-in date cannot be in the past');
              return;
            }
            
            if (checkoutDate <= checkinDate) {
              alert('Check-out date must be after check-in date');
              return;
            }
          }
          
          // Show loading state
          const resultsDiv = document.getElementById('availabilityResults');
          const roomsContainer = document.getElementById('roomsResultsContainer');
          resultsDiv.style.display = 'block';
          roomsContainer.innerHTML = '<div class="col-lg-12 text-center"><p>Checking availability...</p></div>';
          
          try {
            // Build API URL
            const params = new URLSearchParams();
            params.append('checkin_date', checkin);
            params.append('checkin_time', checkinTime);
            if (checkout) params.append('checkout_date', checkout);
            if (checkoutTime) params.append('checkout_time', checkoutTime);
            params.append('guests', guests);

            console.log('Check availability params:', { checkin, checkinTime, checkout, checkoutTime, guests });
            console.log('API URL:', 'api/check-availability.php?' + params.toString());

            const response = await fetch('api/check-availability.php?' + params.toString());
            const result = await response.json();
            
            if (result.success && result.data && result.data.length > 0) {
              renderAvailableRooms(result.data, checkin, checkout, guests, checkinTime, checkoutTime);
            } else {
              roomsContainer.innerHTML = '<div class="col-lg-12 text-center"><p>No rooms available for the selected dates. Please try different dates.</p></div>';
            }
          } catch (error) {
            console.error('Error checking availability:', error);
            roomsContainer.innerHTML = '<div class="col-lg-12 text-center"><p>Error checking availability. Please try again.</p></div>';
          }
        });
        
        // Set minimum checkout date when checkin date changes
        document.getElementById('checkin').addEventListener('change', function() {
          const checkinDate = new Date(this.value);
          checkinDate.setDate(checkinDate.getDate() + 1);
          const minCheckout = checkinDate.toISOString().split('T')[0];
          document.getElementById('checkout').min = minCheckout;
          
          // If checkout is before new minimum, update it
          const currentCheckout = document.getElementById('checkout').value;
          if (currentCheckout && new Date(currentCheckout) < new Date(minCheckout)) {
            document.getElementById('checkout').value = minCheckout;
          }
        });
      }
    });

    // Contact form AJAX submission
    document.getElementById('contact-form').addEventListener('submit', async function(e) {
      e.preventDefault();

      const btnText = document.getElementById('btnText');
      const btnSpinner = document.getElementById('btnSpinner');
      const messageDiv = document.getElementById('formMessage');

      // Show loading state
      btnText.style.display = 'none';
      btnSpinner.style.display = 'inline';

      // Get form data
      const formData = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        subject: document.getElementById('subject').value,
        message: document.getElementById('message').value
      };

      try {
        const res = await fetch('api/contact_inquiries.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(formData)
        });

        const data = await res.json();

        if (data.success) {
          messageDiv.innerHTML = '<div id="alertBox" class="alert alert-success mb-4 p-4 rounded bg-green-100 text-green-800">' + data.message + '</div>';
          // Clear subject and message fields, keep name and email for logged in users
          document.getElementById('subject').value = '';
          document.getElementById('message').value = '';
          // Auto-hide after 5 seconds
          setTimeout(() => {
            const alertBox = document.getElementById('alertBox');
            if (alertBox) alertBox.style.display = 'none';
          }, 5000);
        } else {
          messageDiv.innerHTML = '<div id="alertBox" class="alert alert-danger mb-4 p-4 rounded bg-red-100 text-red-800">' + (data.message || 'An error occurred') + '</div>';
          setTimeout(() => {
            const alertBox = document.getElementById('alertBox');
            if (alertBox) alertBox.style.display = 'none';
          }, 5000);
        }
      } catch (error) {
        console.error('Error:', error);
        messageDiv.innerHTML = '<div id="alertBox" class="alert alert-danger mb-4 p-4 rounded bg-red-100 text-red-800">Failed to send message. Please try again.</div>';
        setTimeout(() => {
          const alertBox = document.getElementById('alertBox');
          if (alertBox) alertBox.style.display = 'none';
        }, 5000);
      } finally {
        // Reset button state
        btnText.style.display = 'inline';
        btnSpinner.style.display = 'none';
      }
    });
  </script>

</body>

</html>