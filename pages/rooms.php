<?php
require_once 'config/config.php';

$companyName = $companyInfo['company_name'] ?? 'M&M Paraiso';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('structure/head.php'); ?>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <title><?php echo htmlspecialchars($companyName); ?> - Rooms</title>

  <style>
    .rooms-filter {
      list-style: none;
      padding: 0;
      margin: 0 0 30px 0;
      text-align: center;
    }
    .rooms-filter li {
      display: inline-block;
      margin: 0 10px;
    }
    .rooms-filter a {
      display: inline-block;
      padding: 10px 20px;
      background: #fbd9cf;
      color: #1e1e1e;
      border-radius: 5px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s;
    }
    .rooms-filter a:hover,
    .rooms-filter a.is_active {
      background: #f35525;
      color: #fff;
    }
    .room-item {
      background: #fafafa;
      border-radius: 10px;
      padding: 30px;
      margin-bottom: 30px;
      transition: all 0.3s;
    }
    .room-item:hover {
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .room-item img {
      border-radius: 10px;
      width: 100%;
      height: 200px;
      object-fit: cover;
      margin-bottom: 20px;
    }
    .room-item .category {
      background: #fbd9cf;
      font-weight: 500;
      border-radius: 5px;
      font-size: 14px;
      color: #1e1e1e;
      padding: 5px 12px;
      display: inline-block;
    }
    .room-item .property-name {
      color: #f35525;
      font-size: 13px;
      margin-top: 15px;
      margin-bottom: 5px;
    }
    .room-item h4 {
      font-size: 19px;
      margin: 10px 0;
    }
    .room-item .price {
      font-size: 18px;
      color: #f35525;
      font-weight: 700;
      margin: 15px 0;
    }
    .room-item .features {
      list-style: none;
      padding: 0;
      margin: 15px 0;
    }
    .room-item .features li {
      display: inline-block;
      margin-right: 15px;
      font-size: 13px;
      color: #666;
    }
    .room-item .features li i {
      color: #f35525;
      margin-right: 5px;
    }
    .room-item .main-button {
      margin-top: 20px;
      display: flex;
      justify-content: center;
    }
    .room-item .main-button a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #1e1e1e;
      color: #fff;
      padding: 12px 30px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s;
    }
    .room-item .main-button a:hover {
      background: #f35525;
    }
    .property-filter {
      margin-bottom: 30px;
    }
    .property-filter select {
      padding: 12px 20px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
      width: 100%;
      max-width: 300px;
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
          <span class="breadcrumb"><a href="index.php">Home</a> / Rooms</span>
          <h3>Our Rooms</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="section properties">
    <div class="container">
      <!-- Property Filter Dropdown -->
      <div class="row property-filter">
        <div class="col-lg-12 text-center">
          <select id="propertyFilter" onchange="filterByProperty()">
            <option value="">All Properties</option>
            <!-- Options loaded dynamically -->
          </select>
        </div>
      </div>

      <!-- Rooms Grid -->
      <div class="row" id="roomsContainer">
        <div class="col-lg-12 text-center">
          <p>Loading rooms...</p>
        </div>
      </div>
    </div>
  </div>

  <?php include('structure/footer.php'); ?>

  <!-- Scripts -->
  <?php include('structure/scripts.php'); ?>

  <script>
    let allRooms = [];
    let properties = [];

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

    // Format price
    function formatPrice(price) {
      if (!price || price == 0) return 'Contact for price';
      return '₱' + parseFloat(price).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    // Fetch rooms from API
    async function fetchRooms() {
      try {
        const response = await fetch('api/rooms.php?action=list');
        const result = await response.json();

        if (result.success && result.data && result.data.data) {
          allRooms = result.data.data;
          properties = result.properties;
          renderPropertyFilter(properties);
          renderRooms(allRooms, result.data.pagination);
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

    // Render property filter dropdown
    function renderPropertyFilter(properties) {
      const select = document.getElementById('propertyFilter');
      
      properties.forEach(prop => {
        const option = document.createElement('option');
        option.value = prop.property_id;
        option.textContent = `${prop.property_name} (${prop.city || 'N/A'})`;
        select.appendChild(option);
      });
    }

    // Filter rooms by property
    function filterByProperty() {
      const propertyId = document.getElementById('propertyFilter').value;
      
      if (propertyId) {
        const filtered = allRooms.filter(room => room.property_id === propertyId);
        renderRooms(filtered);
      } else {
        renderRooms(allRooms);
      }
    }

    // Render rooms
    function renderRooms(rooms, pagination) {
      const container = document.getElementById('roomsContainer');

      if (rooms.length === 0) {
        container.innerHTML = `
          <div class="col-lg-12 text-center">
            <p>No rooms available at the moment.</p>
          </div>
        `;
        return;
      }

      container.innerHTML = `
        ${pagination ? `<div class="col-lg-12 text-center" style="margin-bottom: 20px;"><p style="color: #666;">Showing ${pagination.total} room${pagination.total > 1 ? 's' : ''}</p></div>` : ''}
        ${rooms.map(room => {
          const roomImage = room.room_image ? '../build/' + room.room_image : 'assets/images/property-01.jpg';
          
          // Format full address using geocode fields
          const addressParts = [];
          if (room.barangay_name) addressParts.push(room.barangay_name);
          if (room.city_municipality_name) addressParts.push(room.city_municipality_name);
          if (room.province_name) addressParts.push(room.province_name);
          const location = addressParts.join(', ') || 'Location not available';
          
          // Format price (use override prices if available, otherwise category prices)
          let priceHtml = '';
          const pricePerNight = room.override_price_per_night || room.base_price_per_night;
          const pricePerHour = room.override_price_per_hour || room.base_price_per_hour;
          const priceDayUse = room.override_price_day_use || room.base_price_day_use;

          if (pricePerNight > 0) {
            priceHtml += `<div class="price">${formatPrice(pricePerNight)} <small>/ night</small></div>`;
          } else if (priceDayUse > 0) {
            priceHtml += `<div class="price">${formatPrice(priceDayUse)} <small>/ day use</small></div>`;
          } else if (pricePerHour > 0) {
            priceHtml += `<div class="price">${formatPrice(pricePerHour)} <small>/ hour</small></div>`;
          }

          // Build features list
          const features = [];
          if (room.max_adult_capacity) features.push(`<li><i class="fa fa-user"></i> ${room.max_adult_capacity} Adults</li>`);
          if (room.max_child_capacity) features.push(`<li><i class="fa fa-child"></i> ${room.max_child_capacity} Children</li>`);
          if (room.floor_area_sqm) features.push(`<li><i class="fa fa-expand"></i> ${room.floor_area_sqm} m²</li>`);
          if (room.bed_configuration) features.push(`<li><i class="fa fa-bed"></i> ${escapeHtml(room.bed_configuration)}</li>`);
          if (room.view_type) features.push(`<li><i class="fa fa-eye"></i> ${escapeHtml(room.view_type)} View</li>`);
          if (room.pet_friendly) features.push(`<li><i class="fa fa-paw"></i> Pet Friendly</li>`);

          const featuresHtml = features.length > 0 ? `<ul class="features">${features.join('')}</ul>` : '';

          // Booking options
          const bookingOptions = [];
          if (room.allows_overnight) bookingOptions.push('Overnight');
          if (room.allows_day_use) bookingOptions.push('Day Use');
          if (room.allows_hourly) bookingOptions.push('Hourly');
          const bookingHtml = bookingOptions.length > 0 
            ? `<p style="font-size: 12px; color: #999; margin-top: 10px;"><i class="fa fa-clock"></i> Available for: ${bookingOptions.join(', ')}</p>` 
            : '';

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
            ? `<span style="background: ${statusColors[room.room_status] || '#e0e0e0'}; color: ${statusTextColors[room.room_status] || '#424242'}; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-left: 10px;">${room.room_status.replace('_', ' ')}</span>`
            : '';

          return `
            <div class="col-lg-4 col-md-6 mb-30">
              <div class="room-item">
                <img src="${escapeHtml(roomImage)}" alt="${escapeHtml(room.room_name || room.room_number)}">
                <span class="category">${formatPropertyType(room.property_type)}</span>
                <div class="property-name">
                  <i class="fa fa-building"></i> ${escapeHtml(room.property_name)} - ${escapeHtml(location)}
                </div>
                <h4>${escapeHtml(room.room_name || room.room_number)}</h4>
                <p style="font-size: 13px; color: #666; margin: 5px 0;">${escapeHtml(room.category_name)}${statusBadge}</p>
                ${priceHtml}
                ${featuresHtml}
                ${bookingHtml}
                <div class="main-button">
                  <a href="room-details.php?room_id=${escapeHtml(room.room_id)}&category_id=${escapeHtml(room.category_id)}">View Details</a>
                </div>
              </div>
            </div>
          `;
        }).join('')}
      `;
    }

    // Load rooms when page loads
    document.addEventListener('DOMContentLoaded', fetchRooms);
  </script>

</body>

</html>
