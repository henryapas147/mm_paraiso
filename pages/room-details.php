<?php
require_once 'config/config.php';

$companyName = $companyInfo['company_name'] ?? 'M&M Paraiso';
$roomId = $_GET['room_id'] ?? null;
$categoryId = $_GET['category_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('structure/head.php'); ?>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <title><?php echo htmlspecialchars($companyName); ?> - Room Details</title>

  <style>
    .room-gallery {
      margin-bottom: 30px;
    }
    .room-gallery .main-image {
      width: 100%;
      height: 400px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 15px;
    }
    .room-gallery .thumb-images {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }
    .room-gallery .thumb-images img {
      width: 100px;
      height: 75px;
      object-fit: cover;
      border-radius: 5px;
      cursor: pointer;
      transition: opacity 0.3s;
    }
    .room-gallery .thumb-images img:hover {
      opacity: 0.8;
    }
    .room-features-list {
      list-style: none;
      padding: 0;
    }
    .room-features-list li {
      padding: 10px 0;
      border-bottom: 1px solid #eee;
      display: flex;
      justify-content: space-between;
    }
    .room-features-list li:last-child {
      border-bottom: none;
    }
    .room-features-list li i {
      color: #f35525;
      margin-right: 10px;
    }
    .price-box {
      background: #fbd9cf;
      padding: 25px;
      border-radius: 10px;
      text-align: center;
      margin-bottom: 20px;
    }
    .price-box .price {
      font-size: 32px;
      font-weight: 700;
      color: #1e1e1e;
    }
    .price-box .price-label {
      font-size: 14px;
      color: #666;
    }
    .booking-options {
      background: #fafafa;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
    }
    .booking-options h4 {
      margin-bottom: 15px;
    }
    .booking-options ul {
      list-style: none;
      padding: 0;
    }
    .booking-options ul li {
      padding: 8px 0;
      color: #666;
    }
    .booking-options ul li i {
      color: #f35525;
      margin-right: 10px;
    }
    .info-card {
      background: #fafafa;
      padding: 25px;
      border-radius: 10px;
      margin-bottom: 20px;
    }
    .info-card h4 {
      margin-bottom: 20px;
      font-size: 18px;
    }
    .individual-rooms {
      background: #fff;
      border: 1px solid #eee;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 30px;
    }
    .room-status-badge {
      display: inline-block;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }
    .status-available {
      background: #e8f5e9;
      color: #2e7d32;
    }
    .status-occupied {
      background: #ffebee;
      color: #c62828;
    }
    .status-reserved {
      background: #fff3e0;
      color: #ef6c00;
    }
  </style>

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
          <span class="breadcrumb"><a href="index.php">Home</a> / <a href="rooms.php">Rooms</a> / <span id="breadcrumbRoomName">Room Details</span></span>
          <h3 id="pageTitle">Room Details</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="single-property section">
    <div class="container">
      <div class="row" id="roomDetailsContainer">
        <div class="col-lg-12 text-center">
          <p>Loading room details...</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Booking Policies Section -->
  <div class="section" id="bookingPoliciesSection" style="background: #fafafa;">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="section-heading" style="text-align: center; margin-bottom: 40px;">
            <h6>| Booking Policies</h6>
            <h2>Room-Specific Policies</h2>
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

  <?php include('structure/footer.php'); ?>

  <!-- Scripts -->
  <?php include('structure/scripts.php'); ?>

  <script>
    // Get room ID and category ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const roomId = urlParams.get('room_id');
    const categoryId = urlParams.get('category_id');

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

    // Helper function to format image type
    function formatImageType(type) {
      const typeLabels = {
        'interior': 'Interior',
        'exterior': 'Exterior',
        'bathroom': 'Bathroom',
        'view': 'View',
        'amenity': 'Amenities',
        'bed': 'Bed',
        'balcony': 'Balcony',
        'other': 'Other'
      };
      return typeLabels[type] || type.charAt(0).toUpperCase() + type.slice(1);
    }

    // Format price
    function formatPrice(price) {
      if (!price || price == 0) return 'Contact for price';
      return '₱' + parseFloat(price).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    // Fetch room details from API
    async function fetchRoomDetails() {
      if (!roomId && !categoryId) {
        document.getElementById('roomDetailsContainer').innerHTML = `
          <div class="col-lg-12 text-center">
            <p>No room selected. <a href="rooms.php">Browse rooms</a></p>
          </div>
        `;
        return;
      }

      try {
        let apiUrl;
        if (roomId) {
          // Fetch specific room details
          apiUrl = `api/rooms.php?action=room&id=${encodeURIComponent(roomId)}`;
        } else {
          // Fetch category details (fallback)
          apiUrl = `api/rooms.php?action=single&id=${encodeURIComponent(categoryId)}`;
        }

        const response = await fetch(apiUrl);
        const result = await response.json();

        if (result.success) {
          // Amenities now included in API response, no separate fetch needed
          renderRoomDetails(result.data, roomId ? 'room' : 'category');
          // Fetch booking policies for this room/category
          if (result.data.category_id) {
            fetchBookingPolicies(result.data.category_id);
          }
        } else {
          document.getElementById('roomDetailsContainer').innerHTML = `
            <div class="col-lg-12 text-center">
              <p>Room not found. <a href="rooms.php">Browse rooms</a></p>
            </div>
          `;
        }
      } catch (error) {
        console.error('Error fetching room details:', error);
        document.getElementById('roomDetailsContainer').innerHTML = `
          <div class="col-lg-12 text-center">
            <p>Error loading room details. Please try again later.</p>
          </div>
        `;
      }
    }

    // Fetch room amenities
    async function fetchRoomAmenities(roomId, categoryId) {
      try {
        let apiUrl;
        if (roomId) {
          apiUrl = `../build/api/room_amenities.php?action=by_room&room_id=${encodeURIComponent(roomId)}`;
        } else {
          apiUrl = `../build/api/room_amenities.php?action=by_category&category_id=${encodeURIComponent(categoryId)}`;
        }

        const response = await fetch(apiUrl);
        const result = await response.json();

        if (result.success) {
          return result.data;
        }
        return [];
      } catch (error) {
        console.error('Error fetching amenities:', error);
        return [];
      }
    }

    // Fetch booking policies for this room category
    async function fetchBookingPolicies(categoryId) {
      try {
        const response = await fetch(`api/booking_policies.php?action=list&category_id=${encodeURIComponent(categoryId)}`);
        const result = await response.json();

        if (result.success && result.data && result.data.length > 0) {
          renderBookingPolicies(result.data);
        } else {
          document.getElementById('bookingPoliciesSection').style.display = 'none';
        }
      } catch (error) {
        console.error('Error fetching booking policies:', error);
        document.getElementById('bookingPoliciesSection').style.display = 'none';
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

    // Render room details
    function renderRoomDetails(room, dataType = 'category') {
      // Determine if rendering specific room or category
      const isRoom = dataType === 'room';
      
      // Update page title and breadcrumb
      const displayName = isRoom ? (room.room_name || room.room_number) : room.category_name;
      document.getElementById('pageTitle').textContent = displayName;
      document.getElementById('breadcrumbRoomName').textContent = displayName;
      document.title = `${escapeHtml(displayName)} - Room Details`;

      // Format location using geocode fields
      const locationParts = [];
      if (room.barangay_name) locationParts.push(room.barangay_name);
      if (room.city_municipality_name) locationParts.push(room.city_municipality_name);
      if (room.province_name) locationParts.push(room.province_name);
      const location = locationParts.join(', ') || 'Location not available';

      // Build features list
      const features = [];
      if (room.floor_area_sqm) {
        features.push(`<li><span><i class="fa fa-expand"></i> Floor Area</span> <strong>${room.floor_area_sqm} m²</strong></li>`);
      }
      if (room.max_adult_capacity) {
        features.push(`<li><span><i class="fa fa-user"></i> Max Adults</span> <strong>${room.max_adult_capacity}</strong></li>`);
      }
      if (room.max_child_capacity) {
        features.push(`<li><span><i class="fa fa-child"></i> Max Children</span> <strong>${room.max_child_capacity}</strong></li>`);
      }
      if (room.max_total_capacity) {
        features.push(`<li><span><i class="fa fa-users"></i> Total Capacity</span> <strong>${room.max_total_capacity} Guests</strong></li>`);
      }
      if (room.bed_configuration) {
        features.push(`<li><span><i class="fa fa-bed"></i> Bed Configuration</span> <strong>${escapeHtml(room.bed_configuration)}</strong></li>`);
      }
      if (room.view_type) {
        features.push(`<li><span><i class="fa fa-eye"></i> View</span> <strong>${escapeHtml(room.view_type)}</strong></li>`);
      }
      if (room.smoking_policy) {
        const smokingText = room.smoking_policy === 'non_smoking' ? 'Non-Smoking' : 
                           room.smoking_policy === 'smoking' ? 'Smoking Allowed' : 'Designated Area';
        features.push(`<li><span><i class="fa fa-ban"></i> Smoking Policy</span> <strong>${smokingText}</strong></li>`);
      }
      if (room.pet_friendly) {
        features.push(`<li><span><i class="fa fa-paw"></i> Pet Friendly</span> <strong>Yes</strong></li>`);
      }
      if (room.minimum_hours) {
        features.push(`<li><span><i class="fa fa-clock-o"></i> Minimum Hours</span> <strong>${room.minimum_hours} hours</strong></li>`);
      }

      // Booking options
      const bookingOptions = [];
      if (room.allows_overnight) bookingOptions.push('<li><i class="fa fa-moon-o"></i> Overnight Booking Available</li>');
      if (room.allows_day_use) bookingOptions.push('<li><i class="fa fa-sun-o"></i> Day Use Booking Available</li>');
      if (room.allows_hourly) bookingOptions.push('<li><i class="fa fa-clock-o"></i> Hourly Booking Available</li>');

      // Pricing options (use override prices if available for specific room)
      const pricePerNight = room.override_price_per_night || room.base_price_per_night;
      const pricePerHour = room.override_price_per_hour || room.base_price_per_hour;
      const priceDayUse = room.override_price_day_use || room.base_price_day_use;

      const pricingOptions = [];
      if (pricePerNight > 0) {
        pricingOptions.push({
          price: formatPrice(pricePerNight),
          label: 'per night',
          icon: 'fa-moon-o'
        });
      }
      if (priceDayUse > 0) {
        pricingOptions.push({
          price: formatPrice(priceDayUse),
          label: 'day use',
          icon: 'fa-sun-o'
        });
      }
      if (pricePerHour > 0) {
        pricingOptions.push({
          price: formatPrice(pricePerHour),
          label: 'per hour',
          icon: 'fa-clock-o'
        });
      }

      // Main price (priority: night > day use > hourly)
      let mainPrice = { price: 'Contact for price', label: '' };
      if (pricePerNight > 0) {
        mainPrice = { price: formatPrice(pricePerNight), label: 'per night' };
      } else if (priceDayUse > 0) {
        mainPrice = { price: formatPrice(priceDayUse), label: 'day use' };
      } else if (pricePerHour > 0) {
        mainPrice = { price: formatPrice(pricePerHour), label: 'per hour' };
      }

      // Extra fees
      const extraFees = [];
      if (room.extra_adult_fee > 0) {
        extraFees.push(`<li><span>Extra Adult</span> <strong>${formatPrice(room.extra_adult_fee)}</strong></li>`);
      }
      if (room.extra_child_fee > 0) {
        extraFees.push(`<li><span>Extra Child</span> <strong>${formatPrice(room.extra_child_fee)}</strong></li>`);
      }
      if (room.extra_senior_fee > 0) {
        extraFees.push(`<li><span>Extra Senior</span> <strong>${formatPrice(room.extra_senior_fee)}</strong></li>`);
      }

      // Get room images
      let mainRoomImage = 'assets/images/single-property.jpg';
      let thumbImagesHtml = '';
      let imageTypeTabsHtml = '';
      let imageTypeSectionsHtml = '';
      
      if (room.images && room.images.length > 0) {
        // Group images by type
        const imagesByType = {};
        room.images.forEach(img => {
          const type = img.image_type || 'other';
          if (!imagesByType[type]) {
            imagesByType[type] = [];
          }
          imagesByType[type].push(img);
        });

        // Set main image to first image
        mainRoomImage = '../build/' + room.images[0].image_url;

        // Generate thumbnail images
        thumbImagesHtml = room.images.slice(0, 8).map((img, index) => `
          <img src="../build/${escapeHtml(img.image_url)}" alt="${escapeHtml(img.caption || room.room_number || '')}" onclick="changeMainImage('../build/${escapeHtml(img.image_url)}')" title="${escapeHtml(img.caption || '')}">
        `).join('');

        // Generate image type filter tabs
        const imageTypes = Object.keys(imagesByType);
        if (imageTypes.length > 1) {
          imageTypeTabsHtml = `
            <div style="margin-bottom: 20px;">
              <button class="btn btn-sm btn-outline-danger active" onclick="filterImagesByType('all', this)" style="margin-right: 5px;">All</button>
              ${imageTypes.map(type => `
                <button class="btn btn-sm btn-outline-danger" onclick="filterImagesByType('${escapeHtml(type)}', this)" style="margin-right: 5px;">${formatImageType(type)}</button>
              `).join('')}
            </div>
          `;
        }

        // Generate image type sections
        imageTypeSectionsHtml = Object.keys(imagesByType).map(type => `
          <div class="image-type-section" data-type="${escapeHtml(type)}" style="margin-bottom: 30px;">
            <h5 style="margin-bottom: 15px; color: #f35525;">${formatImageType(type)}</h5>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px;">
              ${imagesByType[type].map(img => `
                <div style="position: relative;">
                  <img src="../build/${escapeHtml(img.image_url)}" alt="${escapeHtml(img.caption || '')}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 5px; cursor: pointer;" onclick="changeMainImage('../build/${escapeHtml(img.image_url)}')">
                  ${img.caption ? `<div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.6); color: white; padding: 5px; font-size: 11px; border-radius: 0 0 5px 5px;">${escapeHtml(img.caption)}</div>` : ''}
                </div>
              `).join('')}
            </div>
          </div>
        `).join('');
      }

      // Individual rooms list (only for category view)
      let individualRoomsHtml = '';
      let roomCountBadge = '';
      
      if (!isRoom && room.rooms && room.rooms.length > 0) {
        const roomsList = room.rooms.map(r => {
          let statusClass = 'status-available';
          if (r.room_status === 'occupied') statusClass = 'status-occupied';
          if (r.room_status === 'reserved') statusClass = 'status-reserved';
          
          return `
            <div style="padding: 10px 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
              <span><i class="fa fa-door-open" style="color: #f35525; margin-right: 10px;"></i> ${escapeHtml(r.room_number)}${r.room_name ? ' - ' + escapeHtml(r.room_name) : ''}</span>
              <span class="room-status-badge ${statusClass}">${r.room_status.replace('_', ' ').toUpperCase()}</span>
            </div>
          `;
        }).join('');

        individualRoomsHtml = `
          <div class="col-lg-12 mb-30">
            <div class="individual-rooms">
              <h4 style="margin-bottom: 20px;"><i class="fa fa-list" style="color: #f35525; margin-right: 10px;"></i>Individual Rooms (${room.room_count})</h4>
              ${roomsList}
            </div>
          </div>
        `;

        roomCountBadge = room.room_count > 0 
          ? `<span style="background: #e8f5e9; color: #2e7d32; padding: 8px 15px; border-radius: 20px; font-size: 14px; font-weight: 600; margin-left: 15px;">${room.room_count} room${room.room_count > 1 ? 's' : ''} available</span>`
          : `<span style="background: #ffebee; color: #c62828; padding: 8px 15px; border-radius: 20px; font-size: 14px; font-weight: 600; margin-left: 15px;">No rooms available</span>`;
      } else if (isRoom) {
        // Show room status badge for single room
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
        roomCountBadge = room.room_status 
          ? `<span style="background: ${statusColors[room.room_status] || '#e0e0e0'}; color: ${statusTextColors[room.room_status] || '#424242'}; padding: 8px 15px; border-radius: 20px; font-size: 14px; font-weight: 600; margin-left: 15px;">${room.room_status.replace('_', ' ').toUpperCase()}</span>`
          : '';
      }

      document.getElementById('roomDetailsContainer').innerHTML = `
        <div class="col-lg-8">
          <!-- Room Gallery -->
          <div class="room-gallery">
            <img src="${escapeHtml(mainRoomImage)}" alt="${escapeHtml(displayName)}" class="main-image" id="mainRoomImage">
            ${thumbImagesHtml ? `
              <div class="thumb-images">
                ${thumbImagesHtml}
              </div>
            ` : ''}
          </div>

          <!-- Image Type Filter -->
          ${imageTypeTabsHtml ? `
            <div style="margin-bottom: 30px;">
              ${imageTypeTabsHtml}
            </div>
          ` : ''}

          <!-- Image Type Sections -->
          ${imageTypeSectionsHtml ? `
            <div id="imageTypeSections">
              ${imageTypeSectionsHtml}
            </div>
          ` : ''}

          <!-- Room Description -->
          <div class="main-content" style="margin-bottom: 30px;">
            <span class="category">${formatPropertyType(room.property_type)}</span>
            <h4 style="display: inline-block;">${escapeHtml(isRoom ? (room.room_name || room.room_number) : room.category_name)}</h4>
            ${roomCountBadge}
            <div style="margin: 15px 0; color: #666;">
              <i class="fa fa-building"></i> <a href="property-details.php?id=${escapeHtml(room.property_id)}">${escapeHtml(room.property_name)}</a> • 
              <i class="fa fa-map-marker"></i> ${escapeHtml(location)}
              ${isRoom ? ` • <i class="fa fa-door-open"></i> Room ${escapeHtml(room.room_number)}` : ''}
            </div>
            <p>${(isRoom ? room.category_description : room.description) ? escapeHtml(isRoom ? room.category_description : room.description) : 'No description available.'}</p>
            
            ${isRoom && room.building_block ? `
            <!-- Building & Floor Info Cards -->
            <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 20px;">
              <!-- Building Card -->
              <div style="display: flex; align-items: center; gap: 15px; padding: 15px 20px; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-left: 4px solid #f35525; border-radius: 8px; min-width: 200px;">
                <div style="width: 50px; height: 50px; background: #f35525; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                  <i class="fa fa-building" style="color: white; font-size: 24px;"></i>
                </div>
                <div>
                  <div style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Building</div>
                  <div style="font-size: 16px; font-weight: 600; color: #1e293b;">${escapeHtml(room.building_block)}</div>
                </div>
              </div>
              
              <!-- Floor Card -->
              <div style="display: flex; align-items: center; gap: 15px; padding: 15px 20px; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-left: 4px solid #3b82f6; border-radius: 8px; min-width: 200px;">
                <div style="width: 50px; height: 50px; background: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                  <i class="fa fa-level-up" style="color: white; font-size: 24px;"></i>
                </div>
                <div>
                  <div style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Floor Level</div>
                  <div style="font-size: 16px; font-weight: 600; color: #1e293b;">${room.floor_number ? 'Floor ' + escapeHtml(String(room.floor_number)) : 'Ground Floor'}</div>
                </div>
              </div>
              
              ${room.room_number ? `
              <!-- Room Number Card -->
              <div style="display: flex; align-items: center; gap: 15px; padding: 15px 20px; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-left: 4px solid #10b981; border-radius: 8px; min-width: 200px;">
                <div style="width: 50px; height: 50px; background: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                  <i class="fa fa-door-open" style="color: white; font-size: 24px;"></i>
                </div>
                <div>
                  <div style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Room Number</div>
                  <div style="font-size: 16px; font-weight: 600; color: #1e293b;">${escapeHtml(room.room_number)}</div>
                </div>
              </div>
              ` : ''}
            </div>
            ` : ''}
          </div>

          <!-- Room Features -->
          <div class="info-card">
            <h4><i class="fa fa-info-circle" style="color: #f35525; margin-right: 10px;"></i>Room Features</h4>
            <ul class="room-features-list">
              ${features.length > 0 ? features.join('') : '<li>No additional features listed</li>'}
            </ul>
          </div>

          <!-- Room Amenities -->
          ${room.amenities && room.amenities.length > 0 ? `
            <div class="info-card">
              <h4><i class="fa fa-concierge-bell" style="color: #f35525; margin-right: 10px;"></i>Amenities</h4>
              <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px;">
                ${(() => {
                  // Font Awesome 6 to FA4 icon mapping - comprehensive for amenities
                  const iconMap = {
                    // Temperature & Climate
                    'fa-temperature-arrow-down': 'fa-thermometer-half',
                    'fa-temperature-arrow-up': 'fa-thermometer-half',
                    'fa-temperature-empty': 'fa-thermometer-empty',
                    'fa-temperature-half': 'fa-thermometer-half',
                    'fa-temperature-full': 'fa-thermometer-full',
                    'fa-temperature-quarter': 'fa-thermometer-quarter',
                    'fa-temperature-three-quarters': 'fa-thermometer-three-quarters',
                    'fa-snowflake': 'fa-archive',
                    'fa-fire': 'fa-fire',
                    'fa-fire-burner': 'fa-fire',
                    'fa-fan': 'fa-refresh',
                    'fa-wind': 'fa-refresh',
                    'fa-air-conditioner': 'fa-thermometer-half',

                    // Bath & Toilet
                    'fa-shower': 'fa-shower',
                    'fa-bath': 'fa-bath',
                    'fa-bathtub': 'fa-bath',
                    'fa-soap': 'fa-shopping-bag',
                    'fa-sink': 'fa-tint',
                    'fa-toilet': 'fa-male',
                    'fa-toilet-paper': 'fa-clipboard',
                    'fa-spray-can': 'fa-spray',
                    'fa-sprayer': 'fa-spray',
                    'fa-hand-holding-droplet': 'fa-hand-o-up',
                    'fa-droplet': 'fa-tint',
                    'fa-water': 'fa-tint',
                    'fa-hot-tub': 'fa-bath',
                    'fa-spa': 'fa-leaf',

                    // Bed & Furniture
                    'fa-bed': 'fa-bed',
                    'fa-bed-pulse': 'fa-bed',
                    'fa-mattress-pillow': 'fa-bed',
                    'fa-couch': 'fa-couch',
                    'fa-chair': 'fa-couch',
                    'fa-table': 'fa-table',
                    'fa-desk': 'fa-table',
                    'fa-lamp': 'fa-lightbulb-o',
                    'fa-lightbulb': 'fa-lightbulb-o',
                    'fa-blinds': 'fa-columns',
                    'fa-dungeon': 'fa-lock',
                    'fa-closet': 'fa-archive',

                    // Kitchen & Dining
                    'fa-kitchen-set': 'fa-cutlery',
                    'fa-utensils': 'fa-cutlery',
                    'fa-utensil-spoon': 'fa-spoon',
                    'fa-utensil-fork': 'fa-cutlery',
                    'fa-utensil-knife': 'fa-cutlery',
                    'fa-mug-hot': 'fa-coffee',
                    'fa-mug-saucer': 'fa-coffee',
                    'fa-coffee': 'fa-coffee',
                    'fa-cup-straw': 'fa-glass',
                    'fa-glass-water': 'fa-glass',
                    'fa-wine-glass': 'fa-glass',
                    'fa-champagne-glasses': 'fa-glass',
                    'fa-ice-cream': 'fa-birthday-cake',
                    'fa-refrigerator': 'fa-archive',
                    'fa-fridge': 'fa-archive',
                    'fa-microwave': 'fa-fire',
                    'fa-stove': 'fa-fire',
                    'fa-oven': 'fa-fire',
                    'fa-dishwasher': 'fa-tint',
                    'fa-plate-wheat': 'fa-cutlery',
                    'fa-bread-slice': 'fa-birthday-cake',
                    'fa-burger': 'fa-cutlery',
                    'fa-pizza-slice': 'fa-cutlery',

                    // Electronics & Entertainment
                    'fa-tv': 'fa-television',
                    'fa-television': 'fa-television',
                    'fa-radio': 'fa-music',
                    'fa-headphones': 'fa-headphones',
                    'fa-music': 'fa-music',
                    'fa-film': 'fa-film',
                    'fa-video': 'fa-video-camera',
                    'fa-laptop': 'fa-laptop',
                    'fa-computer': 'fa-desktop',
                    'fa-desktop': 'fa-desktop',
                    'fa-mobile': 'fa-mobile',
                    'fa-mobile-screen': 'fa-mobile',
                    'fa-phone': 'fa-phone',
                    'fa-voicemail': 'fa-microphone',
                    'fa-microphone': 'fa-microphone',
                    'fa-speaker': 'fa-volume-up',
                    'fa-volume-high': 'fa-volume-up',
                    'fa-gamepad': 'fa-gamepad',
                    'fa-cable-car': 'fa-cog',

                    // Internet & Communication
                    'fa-wifi': 'fa-wifi',
                    'fa-wifi-3': 'fa-wifi',
                    'fa-wifi-strong': 'fa-wifi',
                    'fa-signal': 'fa-signal',
                    'fa-tower-cell': 'fa-signal',
                    'fa-satellite-dish': 'fa-signal',
                    'fa-globe': 'fa-globe',
                    'fa-earth-americas': 'fa-globe',
                    'fa-envelope': 'fa-envelope',
                    'fa-envelope-open': 'fa-envelope-o',

                    // Security & Safety
                    'fa-lock': 'fa-lock',
                    'fa-key': 'fa-key',
                    'fa-fingerprint': 'fa-hand-o-up',
                    'fa-id-card': 'fa-id-card',
                    'fa-id-badge': 'fa-id-badge',
                    'fa-shield-halved': 'fa-shield',
                    'fa-shield': 'fa-shield',
                    'fa-user-shield': 'fa-shield',
                    'fa-video-slash': 'fa-video-camera',
                    'fa-camera': 'fa-camera',
                    'fa-camera-security': 'fa-camera',
                    'fa-fire-extinguisher': 'fa-fire-extinguisher',
                    'fa-life-ring': 'fa-life-ring',

                    // Cleaning & Housekeeping
                    'fa-broom': 'fa-broom',
                    'fa-broom-ball': 'fa-broom',
                    'fa-vacuum': 'fa-cog',
                    'fa-socks': 'fa-archive',
                    'fa-shirt': 'fa-archive',
                    'fa-soap': 'fa-shopping-bag',
                    'fa-pump-soap': 'fa-shopping-bag',
                    'fa-hands-bubbles': 'fa-hand-o-up',
                    'fa-hand-sparkles': 'fa-hand-o-up',
                    'fa-spray-can-sparkles': 'fa-spray',
                    'fa-air-freshener': 'fa-leaf',

                    // Accessibility
                    'fa-wheelchair': 'fa-wheelchair',
                    'fa-wheelchair-move': 'fa-wheelchair',
                    'fa-person-cane': 'fa-blind',
                    'fa-braille': 'fa-braille',
                    'fa-universal-access': 'fa-wheelchair',
                    'fa-ear-listen': 'fa-assistive-listening-systems',
                    'fa-hands-american-sign-language-interpreting': 'fa-deaf',
                    'fa-tty': 'fa-tty',

                    // Nature & Outdoor
                    'fa-tree': 'fa-tree',
                    'fa-leaf': 'fa-leaf',
                    'fa-seedling': 'fa-leaf',
                    'fa-plant-wilt': 'fa-leaf',
                    'fa-sun': 'fa-sun-o',
                    'fa-sun-plant-wilt': 'fa-sun-o',
                    'fa-cloud-sun': 'fa-cloud',
                    'fa-mountain-sun': 'fa-mountain',
                    'fa-mountain': 'fa-mountain',
                    'fa-mountains': 'fa-mountain',
                    'fa-umbrella-beach': 'fa-umbrella',
                    'fa-water-ladder': 'fa-life-ring',
                    'fa-swimmer': 'fa-life-ring',
                    'fa-person-swimming': 'fa-life-ring',
                    'fa-fish': 'fa-anchor',
                    'fa-crab': 'fa-anchor',

                    // Fitness & Wellness
                    'fa-dumbbell': 'fa-bolt',
                    'fa-person-running': 'fa-male',
                    'fa-person-walking': 'fa-male',
                    'fa-person-biking': 'fa-bicycle',
                    'fa-bicycle': 'fa-bicycle',
                    'fa-heart-pulse': 'fa-heartbeat',
                    'fa-heart': 'fa-heart',
                    'fa-medal': 'fa-trophy',
                    'fa-trophy': 'fa-trophy',

                    // Business & Work
                    'fa-briefcase': 'fa-briefcase',
                    'fa-suitcase': 'fa-suitcase',
                    'fa-laptop-file': 'fa-laptop',
                    'fa-print': 'fa-print',
                    'fa-fax': 'fa-fax',
                    'fa-calculator': 'fa-calculator',
                    'fa-copy': 'fa-copy',
                    'fa-file': 'fa-file',
                    'fa-folder': 'fa-folder',
                    'fa-pen': 'fa-pencil',
                    'fa-pen-to-square': 'fa-edit',

                    // Parking & Transport
                    'fa-car': 'fa-car',
                    'fa-car-side': 'fa-car',
                    'fa-taxi': 'fa-taxi',
                    'fa-bus': 'fa-bus',
                    'fa-van-shuttle': 'fa-bus',
                    'fa-plane': 'fa-plane',
                    'fa-train': 'fa-train',
                    'fa-train-subway': 'fa-subway',
                    'fa-ferry': 'fa-ship',
                    'fa-motorcycle': 'fa-motorcycle',
                    'fa-bicycle': 'fa-bicycle',
                    'fa-square-parking': 'fa-car',
                    'fa-parking': 'fa-car',

                    // Services
                    'fa-bell-concierge': 'fa-bell',
                    'fa-bell': 'fa-bell',
                    'fa-clipboard': 'fa-clipboard',
                    'fa-clipboard-list': 'fa-clipboard',
                    'fa-clock': 'fa-clock-o',
                    'fa-clock-four': 'fa-clock-o',
                    'fa-alarm-clock': 'fa-clock-o',
                    'fa-stopwatch': 'fa-clock-o',
                    'fa-hourglass': 'fa-hourglass-half',
                    'fa-calendar': 'fa-calendar',
                    'fa-calendar-check': 'fa-calendar-check',
                    'fa-calendar-days': 'fa-calendar',
                    'fa-tags': 'fa-tags',
                    'fa-tag': 'fa-tag',
                    'fa-money-bill': 'fa-money',
                    'fa-money-bill-wave': 'fa-money',
                    'fa-credit-card': 'fa-credit-card',
                    'fa-coins': 'fa-money',
                    'fa-gift': 'fa-gift',
                    'fa-box-open': 'fa-archive',
                    'fa-truck': 'fa-truck',
                    'fa-truck-fast': 'fa-truck',

                    // Other common amenities
                    'fa-smoking': 'fa-fire',
                    'fa-ban-smoking': 'fa-ban',
                    'fa-ban': 'fa-ban',
                    'fa-paw': 'fa-paw',
                    'fa-dog': 'fa-paw',
                    'fa-cat': 'fa-paw',
                    'fa-children': 'fa-child',
                    'fa-baby': 'fa-child',
                    'fa-baby-carriage': 'fa-child',
                    'fa-person-breastfeeding': 'fa-female',
                    'fa-building': 'fa-building',
                    'fa-building-columns': 'fa-university',
                    'fa-landmark': 'fa-university',
                    'fa-shop': 'fa-shopping-cart',
                    'fa-store': 'fa-shopping-cart',
                    'fa-martini-glass': 'fa-glass',
                    'fa-wine-bottle': 'fa-glass',
                    'fa-champagne-glass': 'fa-glass',
                    'fa-mug-beer': 'fa-beer',
                    'fa-beer-mug-empty': 'fa-beer',
                    'fa-newspaper': 'fa-newspaper-o',
                    'fa-book': 'fa-book',
                    'fa-book-open': 'fa-book',
                    'fa-mirror': 'fa-columns',
                    'fa-icicles': 'fa-snowflake-o',
                    'fa-snowplow': 'fa-truck',
                    'fa-person-skiing': 'fa-male',
                    'fa-person-hiking': 'fa-male',
                    'fa-campground': 'fa-fire',
                    'fa-binoculars': 'fa-binoculars',
                    'fa-compass': 'fa-compass',
                    'fa-map': 'fa-map',
                    'fa-map-location': 'fa-map-marker',
                    'fa-map-location-dot': 'fa-map-marker',
                    'fa-road': 'fa-road',
                    'fa-street-view': 'fa-street-view',
                    'fa-person-praying': 'fa-male',
                    'fa-place-of-worship': 'fa-university',
                    'fa-om': 'fa-circle',
                    'fa-cross': 'fa-plus',
                    'fa-star-of-david': 'fa-star',
                    'fa-mosque': 'fa-university',
                    'fa-church': 'fa-university',
                    'fa-temple': 'fa-university'
                  };
                  return room.amenities.map(amenity => {
                    const fa4Icon = amenity.icon_code ? (iconMap[amenity.icon_code] || amenity.icon_code) : null;
                    return `
                      <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #fff; border: 1px solid #eee; border-radius: 8px;">
                        ${fa4Icon ? `<i class="fa ${escapeHtml(fa4Icon)}" style="color: #f35525; font-size: 18px;"></i>` : '<i class="fa fa-check" style="color: #f35525;"></i>'}
                        <span style="font-size: 13px; color: #333;">${escapeHtml(amenity.amenity_name)}</span>
                      </div>
                    `;
                  }).join('');
                })()}
              </div>
            </div>
          ` : ''}

          ${individualRoomsHtml}
        </div>

        <div class="col-lg-4">
          <!-- Price Box -->
          <div class="price-box">
            <div class="price">${mainPrice.price}</div>
            <div class="price-label">${mainPrice.label}</div>
          </div>

          <!-- Alternative Pricing -->
          ${pricingOptions.length > 1 ? `
            <div class="info-card">
              <h4>Other Pricing Options</h4>
              ${pricingOptions.slice(1).map(opt => `
                <div style="padding: 10px 0; border-bottom: 1px solid #eee;">
                  <strong>${opt.price}</strong> <span style="color: #666; font-size: 14px;">${opt.label}</span>
                </div>
              `).join('')}
            </div>
          ` : ''}

          <!-- Extra Fees -->
          ${extraFees.length > 0 ? `
            <div class="info-card">
              <h4>Extra Person Fees</h4>
              <ul class="room-features-list">
                ${extraFees.join('')}
              </ul>
            </div>
          ` : ''}

          <!-- Booking Options -->
          <div class="booking-options">
            <h4>Booking Options</h4>
            ${bookingOptions.length > 0 ? `<ul>${bookingOptions.join('')}</ul>` : '<p>No booking options available</p>'}
          </div>

          <!-- Property Contact -->
          <div class="info-card">
            <h4>Property Contact</h4>
            ${room.phone_primary ? `<p><i class="fa fa-phone" style="color: #f35525; margin-right: 10px;"></i> ${escapeHtml(room.phone_primary)}</p>` : ''}
            ${room.email ? `<p><i class="fa fa-envelope" style="color: #f35525; margin-right: 10px;"></i> ${escapeHtml(room.email)}</p>` : ''}
          </div>

          <!-- Action Buttons -->
          <div class="main-button" style="margin-bottom: 15px;">
            <a href="schedule-visit.php?property_id=${escapeHtml(room.property_id)}&room_category_id=${escapeHtml(room.category_id)}${isRoom ? '&room_id=' + escapeHtml(room.room_id) : ''}" style="width: 100%; text-align: center; display: block;">
              <i class="fa fa-calendar"></i> Book This Room
            </a>
          </div>
          <div style="text-align: center;">
            <a href="property-details.php?id=${escapeHtml(room.property_id)}" style="color: #666; text-decoration: underline;">
              View Property Details
            </a>
            ${isRoom ? `<br><a href="rooms.php" style="color: #666; text-decoration: underline; margin-top: 10px; display: inline-block;">View All Rooms</a>` : ''}
          </div>
        </div>
      `;
    }

    // Change main image when thumbnail is clicked
    function changeMainImage(imageUrl) {
      document.getElementById('mainRoomImage').src = imageUrl;
    }

    // Filter images by type
    function filterImagesByType(type, button) {
      // Update button states
      const buttons = button.parentElement.querySelectorAll('button');
      buttons.forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');

      // Filter image type sections
      const sections = document.querySelectorAll('.image-type-section');
      sections.forEach(section => {
        if (type === 'all') {
          section.style.display = 'block';
        } else {
          section.style.display = section.dataset.type === type ? 'block' : 'none';
        }
      });
    }

    // Load content when page loads
    document.addEventListener('DOMContentLoaded', () => {
      fetchRoomDetails();
    });
  </script>

</body>

</html>
