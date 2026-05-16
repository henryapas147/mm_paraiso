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
          renderRoomDetails(result.data, roomId ? 'room' : 'category');
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
            ${isRoom && room.building_block ? `<p style="font-size: 13px; color: #666;"><i class="fa fa-building"></i> Building: ${escapeHtml(room.building_block)} • Floor: ${room.floor_number || '1'}</p>` : ''}
          </div>

          <!-- Room Features -->
          <div class="info-card">
            <h4><i class="fa fa-info-circle" style="color: #f35525; margin-right: 10px;"></i>Room Features</h4>
            <ul class="room-features-list">
              ${features.length > 0 ? features.join('') : '<li>No additional features listed</li>'}
            </ul>
          </div>

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
