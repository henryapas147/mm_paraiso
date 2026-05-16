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

  <title><?php echo htmlspecialchars($companyName ?? 'M&M Paraiso'); ?> - Properties</title>

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
          <span class="breadcrumb"><a href="index.php">Home</a> / Properties</span>
          <h3>Properties</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="section properties">
    <div class="container" style="overflow: visible;">
      <div id="filterContainer">
        <ul class="properties-filter" id="propertiesFilter">
          <li>
            <a class="is_active" href="#!" data-filter="*">Show All</a>
          </li>
          <!-- Filter buttons will be loaded dynamically -->
        </ul>
      </div>
      <!-- Showing info (outside Isotope container) -->
      <div class="row" id="showingInfoContainer"></div>
      
      <div class="row properties-box" id="propertiesContainer">
        <div class="col-lg-12 text-center">
          <p>Loading properties...</p>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <ul class="pagination" id="paginationContainer">
            <!-- Pagination will be loaded dynamically -->
          </ul>
        </div>
      </div>
    </div>
  </div>

  <?php include('structure/footer.php'); ?>

  <!-- Scripts -->
  <?php include('structure/scripts.php'); ?>

  <script>
    // Pagination state
    let currentPage = 1;
    let totalPages = 1;
    let perPage = 6;
    let allProperties = []; // Store all properties for filtering
    let currentFilter = '*'; // Track current filter

    // Helper function to get property type class for filtering
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

    // Helper function to format property type display name
    function formatPropertyType(type) {
      if (!type) return '';
      return type.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
    }

    // Helper function to escape HTML
    function escapeHtml(text) {
      if (!text) return '';
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    // Fetch properties from API
    async function fetchProperties(page = 1, fetchAll = false) {
      try {
        const perPageParam = fetchAll ? 1000 : perPage;
        const response = await fetch(`api/properties.php?action=list&page=${page}&per_page=${perPageParam}`);
        const result = await response.json();

        if (result.success && result.data) {
          currentPage = result.data.pagination.page;
          totalPages = result.data.pagination.total_pages;
          allProperties = result.data.data;
          
          // If filter is active, apply it
          const propertiesToRender = currentFilter === '*' ? result.data.data : filterProperties(result.data.data, currentFilter);
          
          renderProperties(propertiesToRender, fetchAll ? null : result.data.pagination);
          renderFilterButtons(result.property_types);
          renderPagination(fetchAll ? null : result.data.pagination);
        } else {
          document.getElementById('propertiesContainer').innerHTML = `
            <div class="col-lg-12 text-center">
              <p>Error loading properties. Please try again later.</p>
            </div>
          `;
        }
      } catch (error) {
        console.error('Error fetching properties:', error);
        document.getElementById('propertiesContainer').innerHTML = `
          <div class="col-lg-12 text-center">
            <p>Error loading properties. Please try again later.</p>
          </div>
        `;
      }
    }

    // Filter properties by type
    function filterProperties(properties, filterValue) {
      if (filterValue === '*') return properties;
      return properties.filter(property => {
        const typeClass = getPropertyTypeClass(property.property_type);
        return typeClass === filterValue.replace('.', '');
      });
    }

    // Render pagination controls
    function renderPagination(pagination) {
      const container = document.getElementById('paginationContainer');
      
      if (!pagination || pagination.total_pages <= 1) {
        container.innerHTML = '';
        return;
      }

      let paginationHtml = '';
      
      // Previous button
      paginationHtml += `
        <li class="${pagination.page === 1 ? 'disabled' : ''}">
          <a href="#!" onclick="${pagination.page > 1 ? `fetchProperties(${pagination.page - 1}); return false;` : 'return false;'}" 
             style="${pagination.page === 1 ? 'opacity: 0.5; pointer-events: none;' : ''}">&lt;</a>
        </li>
      `;

      // Page numbers
      const maxVisiblePages = 5;
      let startPage = Math.max(1, pagination.page - Math.floor(maxVisiblePages / 2));
      let endPage = Math.min(pagination.total_pages, startPage + maxVisiblePages - 1);
      
      if (endPage - startPage < maxVisiblePages - 1) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
      }

      // First page + ellipsis
      if (startPage > 1) {
        paginationHtml += `<li><a href="#!" onclick="fetchProperties(1); return false;">1</a></li>`;
        if (startPage > 2) {
          paginationHtml += `<li><span style="padding: 8px 12px;">...</span></li>`;
        }
      }

      // Page numbers
      for (let i = startPage; i <= endPage; i++) {
        paginationHtml += `
          <li class="${i === pagination.page ? 'is_active' : ''}">
            <a href="#!" onclick="fetchProperties(${i}); return false;">${i}</a>
          </li>
        `;
      }

      // Last page + ellipsis
      if (endPage < pagination.total_pages) {
        if (endPage < pagination.total_pages - 1) {
          paginationHtml += `<li><span style="padding: 8px 12px;">...</span></li>`;
        }
        paginationHtml += `<li><a href="#!" onclick="fetchProperties(${pagination.total_pages}); return false;">${pagination.total_pages}</a></li>`;
      }

      // Next button
      paginationHtml += `
        <li class="${pagination.page === pagination.total_pages ? 'disabled' : ''}">
          <a href="#!" onclick="${pagination.page < pagination.total_pages ? `fetchProperties(${pagination.page + 1}); return false;` : 'return false;'}" 
             style="${pagination.page === pagination.total_pages ? 'opacity: 0.5; pointer-events: none;' : ''}">&gt;</a>
        </li>
      `;

      container.innerHTML = paginationHtml;
    }

    // Render filter buttons
    function renderFilterButtons(propertyTypes) {
      const filterContainer = document.getElementById('filterContainer');
      
      // Use dropdown if more than 5 property types
      if (propertyTypes.length > 5) {
        filterContainer.innerHTML = `
          <div class="property-filter" style="text-align: center; margin-bottom: 30px; position: relative;">
            <div id="customDropdown" style="position: relative; display: inline-block; z-index: 1000;">
              <button id="dropdownButton" style="padding: 15px 25px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; min-width: 280px; cursor: pointer; background: white; display: flex; justify-content: space-between; align-items: center;">
                <span id="selectedOption">Show All</span>
                <span style="margin-left: 10px; font-size: 12px;">▼</span>
              </button>
              <div id="dropdownOptions" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-radius: 5px; margin-top: 5px; max-height: 350px; overflow-y: auto; display: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <div class="dropdown-option" data-value="*" style="padding: 15px 25px; cursor: pointer; border-bottom: 1px solid #f0f0f0; transition: background 0.2s; font-size: 16px;">Show All</div>
                ${propertyTypes.map(type => `<div class="dropdown-option" data-value=".${getPropertyTypeClass(type)}" style="padding: 15px 25px; cursor: pointer; border-bottom: 1px solid #f0f0f0; transition: background 0.2s; font-size: 16px;">${formatPropertyType(type)}</div>`).join('')}
              </div>
            </div>
          </div>
        `;
        initCustomDropdown();
      } else {
        filterContainer.innerHTML = `
          <ul class="properties-filter" id="propertiesFilter">
            <li>
              <a class="is_active" href="#!" data-filter="*">Show All</a>
            </li>
          </ul>
        `;
        const ul = filterContainer.querySelector('#propertiesFilter');
        
        propertyTypes.forEach(type => {
          const li = document.createElement('li');
          li.innerHTML = `
            <a href="#!" data-filter=".${getPropertyTypeClass(type)}">${formatPropertyType(type)}</a>
          `;
          ul.appendChild(li);
        });

        // Re-initialize filter click handlers
        initFilterHandlers();
      }
    }

    // Render properties
    function renderProperties(properties, pagination) {
      const container = document.getElementById('propertiesContainer');
      const showingInfoContainer = document.getElementById('showingInfoContainer');

      if (properties.length === 0) {
        container.innerHTML = `
          <div class="col-lg-12 text-center">
            <p>No properties available at the moment.</p>
          </div>
        `;
        if (showingInfoContainer) showingInfoContainer.innerHTML = '';
        return;
      }

      // Update showing info (outside Isotope container)
      if (showingInfoContainer && pagination) {
        const start = (pagination.page - 1) * pagination.per_page + 1;
        const end = Math.min(start + properties.length - 1, pagination.total);
        showingInfoContainer.innerHTML = `<div class="col-lg-12 text-center" style="margin-bottom: 20px;"><p style="color: #666;">Showing ${start}-${end} of ${pagination.total} properties</p></div>`;
      } else if (showingInfoContainer) {
        showingInfoContainer.innerHTML = '';
      }

      // Destroy existing Isotope instance if exists
      if (window.propertiesIsotope) {
        window.propertiesIsotope.isotope('destroy');
        window.propertiesIsotope = null;
      }

      container.innerHTML = properties.map(property => {
        const typeClass = getPropertyTypeClass(property.property_type);
        const coverImage = property.cover_image ? '../' + property.cover_image : 'assets/images/property-01.jpg';
        
        // Format full address using geocode fields
        const addressParts = [];
        if (property.address_line1) addressParts.push(property.address_line1);
        if (property.barangay_name) addressParts.push(property.barangay_name);
        if (property.city_municipality_name) addressParts.push(property.city_municipality_name);
        if (property.province_name) addressParts.push(property.province_name);
        if (property.region_name) addressParts.push(property.region_name);
        const fullAddress = addressParts.join(', ');
        const location = escapeHtml(fullAddress) || 'Location not available';

        const taglineHtml = property.tagline ? `
          <p style="color: #999; font-size: 13px; margin-bottom: 15px;">
            ${escapeHtml(property.tagline)}
          </p>
        ` : '';

        return `
          <div class="col-lg-4 col-md-6 align-self-center mb-30 properties-items col-md-6 ${typeClass}">
            <div class="item">
              <a href="property-details.php?id=${escapeHtml(property.property_id)}">
                <img src="${escapeHtml(coverImage)}" alt="${escapeHtml(property.property_name)}">
              </a>
              <span class="category">${formatPropertyType(property.property_type)}</span>
              <h6>${escapeHtml(property.property_code)}</h6>
              <h4>
                <a href="property-details.php?id=${escapeHtml(property.property_id)}">
                  ${escapeHtml(property.property_name)}
                </a>
              </h4>
              <p style="color: #666; font-size: 14px; margin-bottom: 10px; line-height: 1.4;">
                <i class="fa fa-map-marker"></i> ${location}
              </p>
              ${taglineHtml}
              <div class="main-button">
                <a href="schedule-visit.php?property_id=${escapeHtml(property.property_id)}">Schedule a visit</a>
              </div>
            </div>
          </div>
        `;
      }).join('');

      // Re-initialize Isotope after rendering (with delay for DOM to settle)
      setTimeout(() => {
        initIsotope();
      }, 50);
    }

    // Initialize filter click handlers (for buttons)
    function initFilterHandlers() {
      const filterLinks = document.querySelectorAll('.properties-filter a');
      filterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          
          // Update active class
          filterLinks.forEach(l => l.classList.remove('is_active'));
          this.classList.add('is_active');

          // Get filter value
          const filterValue = this.getAttribute('data-filter');
          currentFilter = filterValue;

          // If filter is not Show All, fetch all properties and disable pagination
          if (filterValue !== '*') {
            fetchProperties(1, true);
          } else {
            // Show All - re-enable pagination
            fetchProperties(1, false);
          }
        });
      });
    }

    // Initialize custom dropdown
    function initCustomDropdown() {
      const button = document.getElementById('dropdownButton');
      const options = document.getElementById('dropdownOptions');
      const selectedOption = document.getElementById('selectedOption');
      
      if (!button || !options) return;

      // Toggle dropdown
      button.addEventListener('click', function(e) {
        e.stopPropagation();
        options.style.display = options.style.display === 'block' ? 'none' : 'block';
      });

      // Close dropdown when clicking outside
      document.addEventListener('click', function() {
        options.style.display = 'none';
      });

      // Handle option selection
      const optionElements = options.querySelectorAll('.dropdown-option');
      optionElements.forEach(option => {
        option.addEventListener('click', function() {
          const value = this.getAttribute('data-value');
          const text = this.textContent;
          
          selectedOption.textContent = text;
          options.style.display = 'none';
          currentFilter = value;

          // If filter is not Show All, fetch all properties and disable pagination
          if (value !== '*') {
            fetchProperties(1, true);
          } else {
            // Show All - re-enable pagination
            fetchProperties(1, false);
          }
        });

        // Hover effect
        option.addEventListener('mouseenter', function() {
          this.style.background = '#f5f5f5';
        });
        option.addEventListener('mouseleave', function() {
          this.style.background = 'white';
        });
      });
    }

    // Initialize Isotope
    function initIsotope() {
      if (typeof jQuery !== 'undefined' && jQuery('.properties-box').length) {
        // Remove any existing Isotope data first
        const $grid = jQuery('.properties-box');
        
        // Initialize Isotope with proper settings
        $grid.isotope({
          itemSelector: '.properties-items',
          layoutMode: 'masonry',
          transitionDuration: '0.3s',
          resize: true
        });
        
        // Store reference
        window.propertiesIsotope = $grid;
        
        // Force initial layout
        $grid.isotope('layout');
      }
    }

    // Load properties when page loads
    document.addEventListener('DOMContentLoaded', fetchProperties);
  </script>

</body>

</html>