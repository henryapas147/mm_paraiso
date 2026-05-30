<?php
require_once 'config/config.php';

$companyName = $companyInfo['company_name'] ?? 'M&M Paraiso';
$propertyId = $_GET['id'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('structure/head.php'); ?>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <title><?php echo htmlspecialchars($companyName); ?> - Property Details</title>

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
          <span class="breadcrumb"><a href="index.php">Home</a> / <a href="properties.php">Properties</a> / <span id="breadcrumbPropertyName">Property Details</span></span>
          <h3 id="pageTitle">Property Details</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="single-property section">
    <div class="container">
      <div class="row" id="propertyDetailsContainer">
        <div class="col-lg-12 text-center">
          <p>Loading property details...</p>
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

  <!-- Booking Policies Section -->
  <div class="section" id="bookingPoliciesSection" style="background: #fafafa;">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="section-heading" style="text-align: center; margin-bottom: 40px;">
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

  <!-- Available Rooms Section -->
  <div class="section" id="availableRoomsSection" style="background: #fff;">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="section-heading" style="text-align: center; margin-bottom: 40px;">
            <h6>| Available Rooms</h6>
            <h2>Choose Your Perfect Room</h2>
          </div>
        </div>
      </div>
      <div class="row" id="roomsListContainer">
        <div class="col-lg-12 text-center">
          <p>Loading available rooms...</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Lightbox Modal -->
  <div id="lightbox" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; justify-content: center; align-items: center;">
    <button id="closeLightbox" style="position: absolute; top: 20px; right: 30px; color: white; font-size: 40px; background: none; border: none; cursor: pointer; z-index: 10000;">&times;</button>
    <button id="prevImage" style="position: absolute; left: 30px; color: white; font-size: 50px; background: none; border: none; cursor: pointer; z-index: 10000;">&#10094;</button>
    <button id="nextImage" style="position: absolute; right: 30px; color: white; font-size: 50px; background: none; border: none; cursor: pointer; z-index: 10000;">&#10095;</button>
    <img id="lightboxImage" src="" alt="" style="max-width: 90%; max-height: 90%; object-fit: contain; cursor: zoom-in;">
    <div id="lightboxCaption" style="position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); color: white; background: rgba(0,0,0,0.7); padding: 10px 20px; border-radius: 5px; max-width: 80%; text-align: center;"></div>
    <button id="downloadImage" style="position: absolute; bottom: 80px; right: 30px; background: rgba(255,255,255,0.2); color: white; border: 1px solid white; padding: 10px 20px; cursor: pointer; border-radius: 5px; z-index: 10000;">Download</button>
    <button id="fullscreenGallery" style="position: absolute; bottom: 80px; left: 30px; background: rgba(255,255,255,0.2); color: white; border: 1px solid white; padding: 10px 20px; cursor: pointer; border-radius: 5px; z-index: 10000;">Fullscreen Gallery</button>
  </div>

  <!-- Fullscreen Gallery Modal -->
  <div id="fullscreenGalleryModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #000; z-index: 10001; overflow-y: auto;">
    <button id="closeFullscreen" style="position: fixed; top: 20px; right: 30px; color: white; font-size: 40px; background: none; border: none; cursor: pointer; z-index: 10002;">&times;</button>
    <div id="fullscreenContent" style="padding: 80px 20px 20px;"></div>
  </div>

  <?php include('structure/footer.php'); ?>


  <!-- Scripts -->
  <?php include('structure/scripts.php'); ?>

  <script>
    // Get property ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const propertyId = urlParams.get('id');

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

    // Helper function to render image filter tabs
    function renderImageFilterTabs(images) {
      if (!images || images.length === 0) return '';

      // Get unique image types (excluding cover)
      const types = [...new Set(images.filter(img => img.image_type !== 'cover').map(img => img.image_type))];
      
      if (types.length === 0) return '';

      let tabsHtml = '<div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">';
      tabsHtml += `<button onclick="filterGalleryByType('all')" style="padding: 8px 16px; border: 1px solid #f35525; background: #f35525; color: white; border-radius: 20px; cursor: pointer;">All</button>`;
      
      types.forEach(type => {
        const typeLabel = type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        tabsHtml += `
          <button onclick="filterGalleryByType('${type}')" 
                  style="padding: 8px 16px; border: 1px solid #ddd; background: white; color: #333; border-radius: 20px; cursor: pointer; transition: all 0.3s;"
                  onmouseenter="this.style.background='#f5f5f5'"
                  onmouseleave="this.style.background='white'">
            ${typeLabel}
          </button>
        `;
      });
      
      tabsHtml += '</div>';
      return tabsHtml;
    }

    // Helper function to render image gallery
    function renderImageGallery(images) {
      if (!images || images.length === 0) return '';

      // Group images by type (excluding cover since it's already shown)
      const imageGroups = {};
      images.forEach(img => {
        if (img.image_type !== 'cover') {
          if (!imageGroups[img.image_type]) {
            imageGroups[img.image_type] = [];
          }
          imageGroups[img.image_type].push(img);
        }
      });

      if (Object.keys(imageGroups).length === 0) return '';

      // Store images for lightbox
      window.galleryImages = images.filter(img => img.image_type !== 'cover');

      let galleryHtml = '';

      // Display images grouped by type
      for (const [type, typeImages] of Object.entries(imageGroups)) {
        const typeLabel = type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        galleryHtml += `
          <div class="image-type-section" data-type="${type}" style="margin-bottom: 25px;">
            <h5 style="margin-bottom: 15px; color: #f35525;">${typeLabel}</h5>
            <div class="image-carousel" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">
              ${typeImages.map((img, index) => {
                const imgUrl = '../' + img.image_url;
                const caption = img.caption ? escapeHtml(img.caption) : '';
                const globalIndex = window.galleryImages.findIndex(g => g.image_id === img.image_id);
                return `
                  <div style="position: relative;" class="gallery-item" data-index="${globalIndex}">
                    <img src="${escapeHtml(imgUrl)}" alt="${caption}" 
                         style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px; cursor: pointer; transition: transform 0.3s;"
                         loading="lazy"
                         onmouseenter="this.style.transform='scale(1.05)'"
                         onmouseleave="this.style.transform='scale(1)'"
                         onclick="openLightbox(${globalIndex})">
                    ${caption ? `<p style="font-size: 12px; color: #666; margin-top: 8px; text-align: center;">${caption}</p>` : ''}
                  </div>
                `;
              }).join('')}
            </div>
          </div>
        `;
      }

      return galleryHtml;
    }

    // Fetch property details from API
    async function fetchPropertyDetails() {
      if (!propertyId) {
        document.getElementById('propertyDetailsContainer').innerHTML = `
          <div class="col-lg-12 text-center">
            <p>No property selected. <a href="properties.php">Browse properties</a></p>
          </div>
        `;
        return;
      }

      try {
        const response = await fetch(`api/properties.php?action=single&id=${encodeURIComponent(propertyId)}`);
        const result = await response.json();

        if (result.success) {
          renderPropertyDetails(result.data);
          renderSpecialOffers();
          fetchBookingPolicies(result.data.property_id);
        } else {
          document.getElementById('propertyDetailsContainer').innerHTML = `
            <div class="col-lg-12 text-center">
              <p>Property not found. <a href="properties.php">Browse properties</a></p>
            </div>
          `;
        }
      } catch (error) {
        console.error('Error fetching property details:', error);
        document.getElementById('propertyDetailsContainer').innerHTML = `
          <div class="col-lg-12 text-center">
            <p>Error loading property details. Please try again later.</p>
          </div>
        `;
      }
    }

    // Render property details
    function renderPropertyDetails(property) {
      // Update page title and breadcrumb
      document.getElementById('pageTitle').textContent = property.property_name;
      document.getElementById('breadcrumbPropertyName').textContent = property.property_name;
      document.title = `${escapeHtml(property.property_name)} - Property Details`;

      // Fetch rooms for this property
      fetchPropertyRooms(property.property_id);

      // Format full address using geocode fields
      const addressParts = [];
      if (property.address_line1) addressParts.push(property.address_line1);
      if (property.barangay_name) addressParts.push(property.barangay_name);
      if (property.city_municipality_name) addressParts.push(property.city_municipality_name);
      if (property.province_name) addressParts.push(property.province_name);
      if (property.region_name) addressParts.push(property.region_name);
      const fullAddress = addressParts.join(', ') || 'Address not available';

      // Get cover image
      const coverImage = property.cover_image ? '../' + property.cover_image : 'assets/images/single-property.jpg';

      // Build property details HTML
      document.getElementById('propertyDetailsContainer').innerHTML = `
        <div class="col-lg-8">
          <div class="main-image">
            <img src="${escapeHtml(coverImage)}" alt="${escapeHtml(property.property_name)}" style="width: 880px; height: 490px; object-fit: cover;">
          </div>
          <div class="main-content">
            <span class="category">${formatPropertyType(property.property_type)}</span>
            <h4>${escapeHtml(fullAddress)}</h4>
            <p>${property.description ? escapeHtml(property.description) : 'No description available.'}</p>
          </div>
          
          ${property.images && property.images.length > 1 ? `
          <div class="property-gallery" style="margin-top: 30px;">
            <h4 style="margin-bottom: 20px;">Property Gallery</h4>
            ${renderImageFilterTabs(property.images)}
            ${renderImageGallery(property.images)}
          </div>
          ` : ''}
          
          <div class="accordion" id="accordionExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Property Information
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <strong>Property Code:</strong> ${escapeHtml(property.property_code)}<br>
                  <strong>Property Type:</strong> ${formatPropertyType(property.property_type)}<br>
                  ${property.tagline ? `<strong>Tagline:</strong> ${escapeHtml(property.tagline)}<br>` : ''}
                  ${property.phone_primary ? `<strong>Phone:</strong> ${escapeHtml(property.phone_primary)}<br>` : ''}
                  ${property.email ? `<strong>Email:</strong> ${escapeHtml(property.email)}<br>` : ''}
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Check-in / Check-out
                </button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  ${property.check_in_time ? `<strong>Check-in Time:</strong> ${escapeHtml(property.check_in_time)}<br>` : ''}
                  ${property.check_out_time ? `<strong>Check-out Time:</strong> ${escapeHtml(property.check_out_time)}<br>` : ''}
                  ${property.earliest_check_in ? `<strong>Earliest Check-in:</strong> ${escapeHtml(property.earliest_check_in)}<br>` : ''}
                  ${property.latest_check_out ? `<strong>Latest Check-out:</strong> ${escapeHtml(property.latest_check_out)}<br>` : ''}
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  Additional Details
                </button>
              </h2>
              <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  ${property.timezone ? `<strong>Timezone:</strong> ${escapeHtml(property.timezone)}<br>` : ''}
                  ${property.currency_code ? `<strong>Currency:</strong> ${escapeHtml(property.currency_code)}<br>` : ''}
                  ${property.tax_rate_percent ? `<strong>Tax Rate:</strong> ${escapeHtml(property.tax_rate_percent)}%<br>` : ''}
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
                <h4>${formatPropertyType(property.property_type)}<br><span>Property Type</span></h4>
              </li>
              <li>
                <img src="assets/images/info-icon-02.png" alt="" style="max-width: 52px;">
                <h4>${property.phone_primary ? escapeHtml(property.phone_primary) : 'N/A'}<br><span>Phone</span></h4>
              </li>
              <li>
                <img src="assets/images/info-icon-03.png" alt="" style="max-width: 52px;">
                <h4>${property.email ? escapeHtml(property.email) : 'N/A'}<br><span>Email</span></h4>
              </li>
              <li>
                <img src="assets/images/info-icon-04.png" alt="" style="max-width: 52px;">
                <h4>Active<br><span>Status</span></h4>
              </li>
            </ul>
          </div>
          <div class="main-button" style="margin-top: 20px;">
            <a href="schedule-visit.php?property_id=${escapeHtml(property.property_id)}">Schedule a visit</a>
          </div>
        </div>
      `;
    }

    // Fetch booking policies for this property
    async function fetchBookingPolicies(propertyId) {
      try {
        const response = await fetch(`api/booking_policies.php?action=list&property_id=${encodeURIComponent(propertyId)}`);
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
                        ${policy.category_name ? `<div><strong>Applies to:</strong> ${escapeHtml(policy.category_name)}</div>` : ''}
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
              <a href="schedule-visit.php" style="color: #f35525; font-size: 14px; font-weight: 600; text-decoration: none;">Book Now &rarr;</a>
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

    // Fetch and render available rooms for this property
    async function fetchPropertyRooms(propertyId) {
      try {
        const response = await fetch(`api/rooms.php?action=by_property&property_id=${encodeURIComponent(propertyId)}`);
        const result = await response.json();

        if (result.success && result.data && result.data.data && result.data.data.length > 0) {
          renderPropertyRooms(result.data.data, result.data.pagination);
        } else {
          document.getElementById('availableRoomsSection').style.display = 'none';
        }
      } catch (error) {
        console.error('Error fetching rooms:', error);
        document.getElementById('availableRoomsSection').style.display = 'none';
      }
    }

    // Render property rooms
    function renderPropertyRooms(rooms, pagination) {
      const container = document.getElementById('roomsListContainer');
      
      container.innerHTML = `
        <div class="col-lg-12">
          ${pagination ? `<p style="text-align: center; margin-bottom: 20px; color: #666;">Showing ${pagination.total} room${pagination.total > 1 ? 's' : ''}</p>` : ''}
          <div class="row">
            ${rooms.map(room => {
              // Format price (use override prices if available, otherwise category prices)
              let priceText = 'Contact for price';
              const pricePerNight = room.override_price_per_night || room.base_price_per_night;
              const pricePerHour = room.override_price_per_hour || room.base_price_per_hour;
              const priceDayUse = room.override_price_day_use || room.base_price_day_use;

              if (pricePerNight > 0) {
                priceText = `₱${parseFloat(pricePerNight).toLocaleString('en-PH')} / night`;
              } else if (priceDayUse > 0) {
                priceText = `₱${parseFloat(priceDayUse).toLocaleString('en-PH')} / day use`;
              } else if (pricePerHour > 0) {
                priceText = `₱${parseFloat(pricePerHour).toLocaleString('en-PH')} / hour`;
              }

              // Room features
              const features = [];
              if (room.max_total_capacity) features.push(`${room.max_total_capacity} Guests`);
              if (room.floor_area_sqm) features.push(`${room.floor_area_sqm} m²`);
              if (room.bed_configuration) features.push(room.bed_configuration);
              if (room.view_type) features.push(`${room.view_type} View`);
              if (room.pet_friendly) features.push('Pet Friendly');

              const featuresHtml = features.length > 0 
                ? `<p style="font-size: 13px; color: #666; margin: 10px 0;">${features.join(' • ')}</p>` 
                : '';

              // Booking options
              const bookingOptions = [];
              if (room.allows_overnight) bookingOptions.push('Overnight');
              if (room.allows_day_use) bookingOptions.push('Day Use');
              if (room.allows_hourly) bookingOptions.push('Hourly');
              const bookingHtml = bookingOptions.length > 0 
                ? `<p style="font-size: 12px; color: #999;"><i class="fa fa-clock"></i> ${bookingOptions.join(', ')}</p>` 
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
                ? `<span style="background: ${statusColors[room.room_status] || '#e0e0e0'}; color: ${statusTextColors[room.room_status] || '#424242'}; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase;">${room.room_status.replace('_', ' ')}</span>`
                : '';

              // Get room image
              let roomImage = 'assets/images/property-01.jpg';
              if (room.images && room.images.length > 0) {
                const coverImage = room.images.find(img => img.is_cover) || room.images[0];
                roomImage = '../build/' + coverImage.image_url;
              }

              return `
                <div class="col-lg-4 col-md-6 mb-30">
                  <div style="background: #fafafa; border-radius: 10px; padding: 25px;">
                    <img src="${escapeHtml(roomImage)}" alt="${escapeHtml(room.room_name || room.room_number)}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                      <h5 style="font-size: 18px; margin: 0;">${escapeHtml(room.room_name || room.room_number)}</h5>
                      ${statusBadge}
                    </div>
                    <p style="font-size: 13px; color: #666; margin-bottom: 10px;">${escapeHtml(room.category_name)}</p>
                    <div style="font-size: 20px; color: #f35525; font-weight: 700; margin: 15px 0;">
                      ${priceText}
                    </div>
                    ${featuresHtml}
                    ${bookingHtml}
                    <div class="main-button" style="margin-top: 20px;">
                      <a href="room-details.php?room_id=${escapeHtml(room.room_id)}&category_id=${escapeHtml(room.category_id)}">View Details</a>
                    </div>
                  </div>
                </div>
              `;
            }).join('')}
          </div>
        </div>
      `;
    }

    // Lightbox functionality
    let currentImageIndex = 0;
    let isZoomed = false;

    function openLightbox(index) {
      if (!window.galleryImages || window.galleryImages.length === 0) return;
      
      currentImageIndex = index;
      const img = window.galleryImages[index];
      const lightbox = document.getElementById('lightbox');
      const lightboxImage = document.getElementById('lightboxImage');
      const lightboxCaption = document.getElementById('lightboxCaption');
      
      lightboxImage.src = '../' + img.image_url;
      lightboxImage.alt = img.caption || '';
      lightboxCaption.textContent = img.caption || '';
      
      lightbox.style.display = 'flex';
      document.body.style.overflow = 'hidden';
      isZoomed = false;
      lightboxImage.style.transform = 'scale(1)';
      lightboxImage.style.cursor = 'zoom-in';
    }

    function closeLightbox() {
      const lightbox = document.getElementById('lightbox');
      lightbox.style.display = 'none';
      document.body.style.overflow = '';
    }

    function nextImage() {
      if (!window.galleryImages || window.galleryImages.length === 0) return;
      currentImageIndex = (currentImageIndex + 1) % window.galleryImages.length;
      openLightbox(currentImageIndex);
    }

    function prevImage() {
      if (!window.galleryImages || window.galleryImages.length === 0) return;
      currentImageIndex = (currentImageIndex - 1 + window.galleryImages.length) % window.galleryImages.length;
      openLightbox(currentImageIndex);
    }

    function toggleZoom() {
      const lightboxImage = document.getElementById('lightboxImage');
      isZoomed = !isZoomed;
      if (isZoomed) {
        lightboxImage.style.transform = 'scale(2)';
        lightboxImage.style.cursor = 'zoom-out';
      } else {
        lightboxImage.style.transform = 'scale(1)';
        lightboxImage.style.cursor = 'zoom-in';
      }
    }

    function downloadCurrentImage() {
      if (!window.galleryImages || window.galleryImages.length === 0) return;
      const img = window.galleryImages[currentImageIndex];
      const link = document.createElement('a');
      link.href = '../' + img.image_url;
      link.download = img.image_url.split('/').pop();
      link.click();
    }

    function openFullscreenGallery() {
      if (!window.galleryImages || window.galleryImages.length === 0) return;
      
      const modal = document.getElementById('fullscreenGalleryModal');
      const content = document.getElementById('fullscreenContent');
      
      let html = '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">';
      
      window.galleryImages.forEach((img, index) => {
        const imgUrl = '../' + img.image_url;
        const caption = img.caption ? escapeHtml(img.caption) : '';
        const typeLabel = img.image_type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        html += `
          <div style="position: relative;">
            <img src="${escapeHtml(imgUrl)}" alt="${caption}" 
                 style="width: 100%; height: 250px; object-fit: cover; border-radius: 8px; cursor: pointer;"
                 onclick="openLightbox(${index})">
            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); padding: 15px; border-radius: 0 0 8px 8px;">
              <p style="color: white; margin: 0; font-size: 12px;">${typeLabel}</p>
              ${caption ? `<p style="color: #ddd; margin: 5px 0 0 0; font-size: 11px;">${caption}</p>` : ''}
            </div>
          </div>
        `;
      });
      
      html += '</div>';
      content.innerHTML = html;
      modal.style.display = 'block';
    }

    function closeFullscreenGallery() {
      document.getElementById('fullscreenGalleryModal').style.display = 'none';
    }

    // Filter gallery by type
    function filterGalleryByType(type) {
      const sections = document.querySelectorAll('.image-type-section');
      sections.forEach(section => {
        if (type === 'all' || section.dataset.type === type) {
          section.style.display = 'block';
        } else {
          section.style.display = 'none';
        }
      });
    }

    // Lightbox event listeners
    document.getElementById('closeLightbox').addEventListener('click', closeLightbox);
    document.getElementById('prevImage').addEventListener('click', prevImage);
    document.getElementById('nextImage').addEventListener('click', nextImage);
    document.getElementById('lightboxImage').addEventListener('click', toggleZoom);
    document.getElementById('downloadImage').addEventListener('click', downloadCurrentImage);
    document.getElementById('fullscreenGallery').addEventListener('click', openFullscreenGallery);
    document.getElementById('closeFullscreen').addEventListener('click', closeFullscreenGallery);

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
      const lightbox = document.getElementById('lightbox');
      if (lightbox.style.display === 'flex') {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') nextImage();
        if (e.key === 'ArrowLeft') prevImage();
        if (e.key === '+' || e.key === '=') toggleZoom();
      }
      
      const fullscreenModal = document.getElementById('fullscreenGalleryModal');
      if (fullscreenModal.style.display === 'block') {
        if (e.key === 'Escape') closeFullscreenGallery();
      }
    });

    // Click outside to close lightbox
    document.getElementById('lightbox').addEventListener('click', (e) => {
      if (e.target.id === 'lightbox') closeLightbox();
    });

    // Load property details when page loads
    document.addEventListener('DOMContentLoaded', fetchPropertyDetails);
  </script>

</body>

</html>