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
      <div class="row">
        <div class="col-lg-4">
          <div class="left-image">
            <img src="assets/images/featured.jpg" alt="">
            <a href="property-details.html"><img src="assets/images/featured-icon.png" alt="" style="max-width: 60px; padding: 0px;"></a>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="section-heading">
            <h6>| Featured</h6>
            <h2>Best Appartment &amp; Sea view</h2>
          </div>
          <div class="accordion" id="accordionExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Best useful links ?
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  Get <strong>the best villa</strong> website template in HTML CSS and Bootstrap for your business. TemplateMo provides you the <a href="https://www.google.com/search?q=best+free+css+templates" target="_blank">best free CSS templates</a> in the world. Please tell your friends about it.</div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  How does this work ?
                </button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  Dolor <strong>almesit amet</strong>, consectetur adipiscing elit, sed doesn't eiusmod tempor incididunt ut labore consectetur <code>adipiscing</code> elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  Why is Villa Agency the best ?
                </button>
              </h2>
              <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  Dolor <strong>almesit amet</strong>, consectetur adipiscing elit, sed doesn't eiusmod tempor incididunt ut labore consectetur <code>adipiscing</code> elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="info-table">
            <ul>
              <li>
                <img src="assets/images/info-icon-01.png" alt="" style="max-width: 52px;">
                <h4>250 m2<br><span>Total Flat Space</span></h4>
              </li>
              <li>
                <img src="assets/images/info-icon-02.png" alt="" style="max-width: 52px;">
                <h4>Contract<br><span>Contract Ready</span></h4>
              </li>
              <li>
                <img src="assets/images/info-icon-03.png" alt="" style="max-width: 52px;">
                <h4>Payment<br><span>Payment Process</span></h4>
              </li>
              <li>
                <img src="assets/images/info-icon-04.png" alt="" style="max-width: 52px;">
                <h4>Safety<br><span>24/7 Under Control</span></h4>
              </li>
            </ul>
          </div>
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
            <img src="assets/images/video-frame.jpg" alt="">
            <a href="https://youtube.com" target="_blank"><i class="fa fa-play"></i></a>
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
                  <h2 class="timer count-title count-number" data-to="34" data-speed="1000"></h2>
                  <p class="count-text ">Buildings<br>Finished Now</p>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="counter">
                  <h2 class="timer count-title count-number" data-to="12" data-speed="1000"></h2>
                  <p class="count-text ">Years<br>Experience</p>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="counter">
                  <h2 class="timer count-title count-number" data-to="24" data-speed="1000"></h2>
                  <p class="count-text ">Awwards<br>Won 2023</p>
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
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12469.776493332698!2d-80.14036379941481!3d25.907788681148624!2m3!1f357.26927939317244!2f20.870722720054623!3f0!3m2!1i1024!2i768!4f35!3m3!1m2!1s0x88d9add4b4ac788f%3A0xe77469d09480fcdb!2sSunny%20Isles%20Beach!5e1!3m2!1sen!2sth!4v1642869952544!5m2!1sen!2sth" width="100%" height="500px" frameborder="0" style="border:0; border-radius: 10px; box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.15);" allowfullscreen=""></iframe>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="item phone">
                <img src="assets/images/phone-icon.png" alt="" style="max-width: 52px;">
                <h6>010-020-0340<br><span>Phone Number</span></h6>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="item email">
                <img src="assets/images/email-icon.png" alt="" style="max-width: 52px;">
                <h6>info@villa.co<br><span>Business Email</span></h6>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <form id="contact-form" action="" method="post">
            <div class="row">
              <div class="col-lg-12">
                <fieldset>
                  <label for="name">Full Name</label>
                  <input type="name" name="name" id="name" placeholder="Your Name..." autocomplete="on" required>
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="email">Email Address</label>
                  <input type="text" name="email" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your E-mail..." required="">
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="subject">Subject</label>
                  <input type="subject" name="subject" id="subject" placeholder="Subject..." autocomplete="on">
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="message">Message</label>
                  <textarea name="message" id="message" placeholder="Your Message"></textarea>
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <button type="submit" id="form-submit" class="orange-button">Send Message</button>
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

    // Render special offers section (static)
    function renderSpecialOffers() {
      // Static mock data for special offers
      const specialOffers = [
        {
          id: 1,
          title: 'Summer Getaway Special',
          description: 'Book 3 nights and get 1 night free! Valid until August 31, 2026.',
          discount: '25% OFF',
          type: 'seasonal',
          validUntil: 'August 31, 2026',
          icon: 'fa-sun'
        },
        {
          id: 2,
          title: 'Honeymoon Package',
          description: 'Romantic package includes complimentary breakfast, spa treatment, and late checkout.',
          discount: '15% OFF',
          type: 'packages',
          validUntil: 'December 31, 2026',
          icon: 'fa-heart'
        },
        {
          id: 3,
          title: 'Early Bird Discount',
          description: 'Book at least 30 days in advance and enjoy special savings on your stay.',
          discount: '20% OFF',
          type: 'early-bird',
          validUntil: 'Ongoing',
          icon: 'fa-clock'
        },
        {
          id: 4,
          title: 'Family Weekend Package',
          description: 'Perfect for families! Includes free meals for children under 12.',
          discount: '10% OFF',
          type: 'packages',
          validUntil: 'September 30, 2026',
          icon: 'fa-users'
        },
        {
          id: 5,
          title: 'Monsoon Season Deal',
          description: 'Enjoy the rainy season with cozy indoor activities and hot beverages.',
          discount: '30% OFF',
          type: 'seasonal',
          validUntil: 'November 30, 2026',
          icon: 'fa-cloud-rain'
        },
        {
          id: 6,
          title: 'Corporate Booking Special',
          description: 'Special rates for corporate bookings and team retreats.',
          discount: '18% OFF',
          type: 'early-bird',
          validUntil: 'Ongoing',
          icon: 'fa-building'
        }
      ];

      // Render offer card
      const renderOfferCard = (offer) => `
        <div class="col-lg-4 col-md-6">
          <div class="item" style="background: #fafafa; border-radius: 10px; padding: 25px; margin-bottom: 30px; border-left: 4px solid #f35525;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
              <span style="background: #f35525; color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: 600;">${offer.discount}</span>
              <i class="fa ${offer.icon}" style="font-size: 24px; color: #f35525;"></i>
            </div>
            <h4 style="margin-bottom: 10px; color: #2a2a2a;">${offer.title}</h4>
            <p style="color: #666; font-size: 14px; margin-bottom: 15px; line-height: 1.6;">${offer.description}</p>
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <span style="font-size: 12px; color: #999;"><i class="fa fa-calendar"></i> Valid until: ${offer.validUntil}</span>
              <a href="properties.php" style="color: #f35525; font-size: 14px; font-weight: 600; text-decoration: none;">View Properties &rarr;</a>
            </div>
          </div>
        </div>
      `;

      // Render all offers
      document.getElementById('allDealsContainer').innerHTML = specialOffers.map(renderOfferCard).join('');

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
    }

    // Load content when page loads
    document.addEventListener('DOMContentLoaded', () => {
      fetchRooms();
      renderSpecialOffers();
      
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
            
            const response = await fetch('api/check-availability.php?' + params.toString());
            const result = await response.json();
            
            if (result.success && result.data.length > 0) {
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
  </script>

</body>

</html>