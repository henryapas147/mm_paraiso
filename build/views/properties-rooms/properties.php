<?php
// Helper function to generate UUID
function generateUUID()
{
  return sprintf(
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
}
?>

<script>
  // Data will be fetched from API on page load
  let propertiesData = [];
  let propertyImagesData = [];
  let propertyRoomsData = {};
  let regionsData = [];
  let provincesData = [];
  let citiesData = [];
  let barangaysData = [];
  let currentPage = 1;
  let itemsPerPage = 10;
  let totalItems = 0;

  // Fetch data from API on page load
  document.addEventListener('DOMContentLoaded', async function() {
    await loadGeographyData();
    await loadPropertyTypes();
    await fetchPropertiesData();
  });

  async function loadGeographyData() {
    try {
      regionsData = await fetchRegions();
    } catch (e) {
      console.error('Error loading geography data:', e);
    }
  }

  async function loadPropertyTypes() {
    try {
      const response = await fetch('../api/properties.php?action=all_types');
      const result = await response.json();
      if (result.success && result.data) {
        populateTypeFilter(result.data);
      }
    } catch (e) {
      console.error('Error loading property types:', e);
    }
  }

  function populateTypeFilter(types) {
    const typeFilter = document.getElementById('typeFilter');
    if (!typeFilter) return;

    typeFilter.innerHTML = '<option value="">All Types</option>';
    types.forEach(type => {
      const formattedType = type.replace(/_/g, ' ').split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
      typeFilter.innerHTML += `<option value="${type}">${formattedType}</option>`;
    });
  }

  async function loadPropertyGeographyData(properties) {
    try {
      // Collect unique codes
      const uniqueRegionCodes = [...new Set(properties.map(p => p.region_code).filter(Boolean))];
      const uniqueProvinceCodes = [...new Set(properties.map(p => p.province_code).filter(Boolean))];
      const uniqueCityCodes = [...new Set(properties.map(p => p.city_municipality_code).filter(Boolean))];
      const uniqueBarangayCodes = [...new Set(properties.map(p => p.barangay_code).filter(Boolean))];

      // Load provinces for unique regions
      const regionMap = new Map();
      for (const regionCode of uniqueRegionCodes) {
        const provinces = await fetchProvinces(regionCode);
        provincesData.push(...provinces);
        regionMap.set(regionCode, provinces);
      }

      // Load cities for unique provinces
      const provinceMap = new Map();
      for (const provinceCode of uniqueProvinceCodes) {
        const cities = await fetchCities(provinceCode);
        citiesData.push(...cities);
        provinceMap.set(provinceCode, cities);
      }

      // Load barangays for unique cities
      for (const cityCode of uniqueCityCodes) {
        const barangays = await fetchBarangays(cityCode);
        barangaysData.push(...barangays);
      }
    } catch (e) {
      console.error('Error loading property geography data:', e);
    }
  }

  function getRegionName(code) {
    if (!code) return '-';
    const region = regionsData.find(r => r.region_code === code);
    return region ? region.region_name : '-';
  }

  function getProvinceName(code) {
    if (!code) return '-';
    const province = provincesData.find(p => p.province_code === code);
    return province ? province.province_name : '-';
  }

  function getCityName(code) {
    if (!code) return '-';
    const city = citiesData.find(c => c.city_municipality_code === code);
    return city ? city.city_municipality_name : '-';
  }

  function getBarangayName(code) {
    if (!code) return '-';
    const barangay = barangaysData.find(b => b.barangay_code === code);
    return barangay ? barangay.barangay_name : '-';
  }

  async function fetchPropertiesData() {
    try {
      const offset = (currentPage - 1) * itemsPerPage;
      const response = await fetch(`../api/properties.php?action=list&limit=${itemsPerPage}&offset=${offset}`);
      const result = await response.json();
      if (result.success) {
        propertiesData = result.data.data || result.data;
        totalItems = result.total || propertiesData.length;

        // Load geography data for these properties
        await loadPropertyGeographyData(propertiesData);

        // Update stats
        document.getElementById('statTotal').textContent = totalItems;
        document.getElementById('statActive').textContent = propertiesData.filter(p => p.is_active).length;

        // Fetch total rooms count
        try {
          const roomsRes = await fetch('../api/rooms.php?action=list');
          const roomsResult = await roomsRes.json();
          if (roomsResult.success) {
            document.getElementById('statRooms').textContent = roomsResult.data.length;
          }
        } catch (e) {
          console.error('Error fetching rooms count:', e);
        }

        // Fetch images for each property
        for (const property of propertiesData) {
          const imgResponse = await fetch(`../api/properties.php?action=images&id=${property.property_id}`);
          const imgResult = await imgResponse.json();
          if (imgResult.success) {
            propertyImagesData[property.property_id] = imgResult.data;
          }
        }

        renderPropertiesTable();
      }
    } catch (error) {
      console.error('Error fetching properties:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to load properties data',
        confirmButtonColor: '#3085d6'
      });
    }
  }

  function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value;

    const filtered = propertiesData.filter(property => {
      const matchesSearch = property.property_name.toLowerCase().includes(searchTerm) ||
        property.property_code.toLowerCase().includes(searchTerm) ||
        (property.city || '').toLowerCase().includes(searchTerm);
      const matchesType = !typeFilter || property.property_type === typeFilter;
      return matchesSearch && matchesType;
    });

    renderPropertiesTable(filtered);
  }

  function renderPropertiesTable(data = propertiesData) {
    const tbody = document.querySelector('#propertiesTableBody');
    if (!tbody) return;

    if (data.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="6" class="p-6 text-center text-slate-400 dark:text-white/60 text-base">
            No properties found. <a href="javascript:;" onclick="openAddPropertyModal()" class="text-blue-500 font-semibold">Add your first property</a>.
          </td>
        </tr>
      `;
    } else {
      tbody.innerHTML = data.map((property, index) => {
        const property_images = propertyImagesData[property.property_id] || [];
        const cover_image = property_images.find(img => img.image_type === 'cover') || property_images[0];

        let imageHtml = '';
        if (cover_image) {
          imageHtml = `
            <img src="../../${cover_image.image_url}" alt="${property.property_name}" class="mr-4 h-9 w-9 object-cover rounded-xl" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="hidden inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
              <i class="fas fa-building"></i>
            </div>
          `;
        } else {
          imageHtml = `
            <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
              <i class="fas fa-building"></i>
            </div>
          `;
        }

        const createdDate = property.created_at ? new Date(property.created_at).toLocaleString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true }) : '-';

        return `
          <tr class="property-row">
            <td class="p-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
              <div class="flex px-2 py-2">
                <div>${imageHtml}</div>
                <div class="flex flex-col justify-center">
                  <h6 class="mb-0 text-base leading-normal dark:text-white font-semibold">${property.property_name}</h6>
                  <p class="mb-0 text-sm leading-tight dark:text-white dark:opacity-80 text-slate-400">${property.property_code}</p>
                </div>
              </div>
            </td>
            <td class="p-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
              <p class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">${property.property_type ? property.property_type.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ') : ''}</p>
            </td>
            <td class="p-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
              <p class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">${getCityName(property.city_municipality_code)}${getProvinceName(property.province_code) !== '-' ? ', ' + getProvinceName(property.province_code) : ''}</p>
            </td>
            <td class="p-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
              <p class="mb-0 text-sm leading-tight dark:text-white dark:opacity-80">${createdDate}</p>
            </td>
            <td class="p-4 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
              <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-4 py-2 text-base rounded-1.8 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Active</span>
            </td>
            <td class="p-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
              <div class="flex items-center gap-2">
                <button onclick="viewPropertyDetails('${property.property_id}')" class="group relative inline-flex items-center px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
                  <i class="fas fa-info-circle mr-1.5 text-sm"></i>
                  <span>View</span>
                </button>
                <button onclick="toggleRoomsDropdown('${property.property_id}')" class="group relative inline-flex items-center px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-500/20 hover:text-emerald-600 dark:hover:text-emerald-400 hover:border-emerald-300 dark:hover:border-emerald-500 transition-all duration-200 shadow-sm hover:shadow-md" id="roomsBtn-${property.property_id}" title="View Rooms">
                  <i class="fas fa-door-open mr-1.5 text-sm"></i>
                  <span>Rooms</span>
                </button>
                <button onclick="openEditPropertyModal('${property.property_id}')" class="group relative inline-flex items-center px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Property">
                  <i class="fas fa-edit mr-1.5 text-sm"></i>
                  <span>Edit</span>
                </button>
                <button onclick="confirmDeleteProperty('${property.property_id}', '${property.property_name}')" class="group relative inline-flex items-center px-4 py-2 text-sm font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Property">
                  <i class="fas fa-trash-alt mr-1.5 text-sm"></i>
                  <span>Delete</span>
                </button>
              </div>
            </td>
          </tr>
          <tr id="roomsDropdown-${property.property_id}" class="hidden">
            <td colspan="6" class="p-0 align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
              <div class="p-6 bg-gray-50 dark:bg-slate-800/50">
                <div class="mb-3 flex items-center justify-between">
                  <h6 class="text-base font-bold dark:text-white">Rooms for ${property.property_name}</h6>
                  <span class="text-sm text-slate-500 dark:text-white/60">Loading rooms...</span>
                </div>
              </div>
            </td>
          </tr>
        `;
      }).join('');
    }

    renderPagination();
  }

  function renderPagination() {
    const paginationContainer = document.getElementById('paginationContainer');
    if (!paginationContainer) return;

    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const startItem = totalItems === 0 ? 0 : (currentPage - 1) * itemsPerPage + 1;
    const endItem = Math.min(currentPage * itemsPerPage, totalItems);

    let paginationHtml = `
      <div class="flex flex-col sm:flex-row items-center justify-between p-4 bg-white dark:bg-slate-850 border-t dark:border-white/10">
        <div class="text-sm text-slate-600 dark:text-white/70 mb-4 sm:mb-0">
          Showing <span class="font-semibold">${startItem}</span> to <span class="font-semibold">${endItem}</span> of <span class="font-semibold">${totalItems}</span> entries
        </div>
        <div class="flex items-center gap-2">
          <div class="flex items-center gap-2 mr-4">
            <label class="text-sm text-slate-600 dark:text-white/70">Show</label>
            <select id="itemsPerPageSelect" onchange="changeItemsPerPage(this.value)" class="px-3 py-1 border border-gray-300 dark:border-white/20 rounded-lg text-sm dark:bg-slate-800 dark:text-white">
              <option value="5" ${itemsPerPage === 5 ? 'selected' : ''}>5</option>
              <option value="10" ${itemsPerPage === 10 ? 'selected' : ''}>10</option>
              <option value="25" ${itemsPerPage === 25 ? 'selected' : ''}>25</option>
              <option value="50" ${itemsPerPage === 50 ? 'selected' : ''}>50</option>
            </select>
            <label class="text-sm text-slate-600 dark:text-white/70">entries</label>
          </div>
          <div class="flex items-center gap-1">
            <button onclick="goToPage(1)" ${currentPage === 1 ? 'disabled' : ''} class="px-3 py-1 border border-gray-300 dark:border-white/20 rounded-lg text-sm dark:bg-slate-800 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed">
              <i class="fas fa-angle-double-left"></i>
            </button>
            <button onclick="goToPage(currentPage - 1)" ${currentPage === 1 ? 'disabled' : ''} class="px-3 py-1 border border-gray-300 dark:border-white/20 rounded-lg text-sm dark:bg-slate-800 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed">
              <i class="fas fa-angle-left"></i>
            </button>
            ${getPageNumbers().map(page => `
              <button onclick="goToPage(${page})" ${page === currentPage ? 'disabled' : ''} class="px-3 py-1 border ${page === currentPage ? 'bg-blue-500 text-white border-blue-500' : 'border-gray-300 dark:border-white/20 dark:bg-slate-800 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700'} rounded-lg text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                ${page}
              </button>
            `).join('')}
            <button onclick="goToPage(currentPage + 1)" ${currentPage === totalPages ? 'disabled' : ''} class="px-3 py-1 border border-gray-300 dark:border-white/20 rounded-lg text-sm dark:bg-slate-800 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed">
              <i class="fas fa-angle-right"></i>
            </button>
            <button onclick="goToPage(totalPages)" ${currentPage === totalPages ? 'disabled' : ''} class="px-3 py-1 border border-gray-300 dark:border-white/20 rounded-lg text-sm dark:bg-slate-800 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed">
              <i class="fas fa-angle-double-right"></i>
            </button>
          </div>
        </div>
      </div>
    `;

    paginationContainer.innerHTML = paginationHtml;
  }

  function getPageNumbers() {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const pages = [];
    const maxVisible = 5;

    if (totalPages <= maxVisible) {
      for (let i = 1; i <= totalPages; i++) {
        pages.push(i);
      }
    } else {
      if (currentPage <= 3) {
        for (let i = 1; i <= 4; i++) {
          pages.push(i);
        }
        pages.push('...');
        pages.push(totalPages);
      } else if (currentPage >= totalPages - 2) {
        pages.push(1);
        pages.push('...');
        for (let i = totalPages - 3; i <= totalPages; i++) {
          pages.push(i);
        }
      } else {
        pages.push(1);
        pages.push('...');
        for (let i = currentPage - 1; i <= currentPage + 1; i++) {
          pages.push(i);
        }
        pages.push('...');
        pages.push(totalPages);
      }
    }

    return pages;
  }

  function goToPage(page) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    if (page < 1 || page > totalPages || page === currentPage) return;
    currentPage = page;
    fetchPropertiesData();
  }

  function changeItemsPerPage(value) {
    itemsPerPage = parseInt(value);
    currentPage = 1;
    fetchPropertiesData();
  }
</script>

<!-- PAGE CONTENT -->
<!-- Stats Cards -->
<div class="flex flex-wrap -mx-3 mb-6">
  <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-2/3 max-w-full px-3">
            <div>
              <p class="mb-0 font-sans text-xs font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Total Properties</p>
              <h5 class="mb-0 font-bold dark:text-white" id="statTotal">0</h5>
            </div>
          </div>
          <div class="px-3 text-right basis-1/3">
            <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-blue-500 to-violet-500">
              <i class="ni leading-none ni-building text-lg relative top-3.5 text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-2/3 max-w-full px-3">
            <div>
              <p class="mb-0 font-sans text-xs font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Active Properties</p>
              <h5 class="mb-0 font-bold dark:text-white" id="statActive">0</h5>
            </div>
          </div>
          <div class="px-3 text-right basis-1/3">
            <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-emerald-500 to-teal-400">
              <i class="ni leading-none ni-check-bold text-lg relative top-3.5 text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-2/3 max-w-full px-3">
            <div>
              <p class="mb-0 font-sans text-xs font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Total Rooms</p>
              <h5 class="mb-0 font-bold dark:text-white" id="statRooms">0</h5>
            </div>
          </div>
          <div class="px-3 text-right basis-1/3">
            <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-orange-500 to-yellow-500">
              <i class="ni leading-none ni-square-pin text-lg relative top-3.5 text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div>
              <button onclick="openAddPropertyModal()" class="w-full inline-flex items-center justify-center px-4 py-2.5 text-xs font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-plus mr-2"></i>Add New Property
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Properties Table -->
<div class="flex flex-wrap -mx-3">
  <div class="w-full max-w-full px-3">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-6 pb-0 mb-0 rounded-t-2xl">
        <div class="flex items-center justify-between">
          <h6 class="mb-0 text-lg font-bold dark:text-white">Properties List</h6>
          <div class="flex items-center gap-2">
            <input type="text" id="searchInput" placeholder="Search properties..." class="px-4 py-2.5 text-base border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white" onkeyup="filterTable()">
            <select id="typeFilter" class="px-4 py-2.5 text-base border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white" onchange="filterTable()">
              <option value="">All Types</option>
            </select>
          </div>
        </div>
      </div>
      <div class="p-0 overflow-x-auto">
        <table class="items-center justify-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
          <thead class="align-bottom">
            <tr>
              <th class="px-6 py-5 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
              <th class="px-6 py-5 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
              <th class="px-6 py-5 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">City</th>
              <th class="px-6 py-5 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Created</th>
              <th class="px-6 py-5 font-bold text-center uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
              <th class="px-6 py-5 font-bold text-center uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Actions</th>
            </tr>
          </thead>
          <tbody id="propertiesTableBody">
            <tr>
              <td colspan="6" class="p-6 text-center text-slate-400 dark:text-white/60 text-base">Loading properties...</td>
            </tr>
          </tbody>
        </table>
        <div id="paginationContainer"></div>
      </div>
    </div>
  </div>
</div>

<!-- Property Details Modal -->
<div id="propertyDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closePropertyDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
  <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 1200px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
    <div class="p-8 border-b border-gray-200 dark:border-white/10 flex justify-between items-center" style="padding: 2rem; border-bottom: 1px solid #e5e7eb;">
      <h3 class="text-2xl font-bold dark:text-white" id="modalPropertyName" style="font-size: 1.5rem; font-weight: bold; margin: 0;">Property Details</h3>
      <button onclick="closePropertyDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.5rem;">
        <i class="fas fa-times text-2xl"></i>
      </button>
    </div>
    <div class="p-8" id="modalPropertyContent" style="padding: 2rem;">
      <!-- Content will be populated by JavaScript -->
    </div>
  </div>
</div>

<!-- Add Property Modal -->
<div id="addPropertyModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddPropertyModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
  <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
    <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
      <h3 class="text-xl font-bold dark:text-white">Add New Property</h3>
      <button onclick="closeAddPropertyModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
        <i class="fas fa-times text-xl"></i>
      </button>
    </div>
    <form id="addPropertyForm" class="p-6" onsubmit="handleAddPropertySubmit(event)">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

      <!-- Basic Information -->
      <div class="mb-6">
        <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Basic Information</h4>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Property Code *</label>
            <input type="text" name="property_code" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. RST-001">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Property Name *</label>
            <input type="text" name="property_name" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. Sunset Beach Resort">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Property Type *</label>
            <select name="property_type" id="property_type" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
              <option value="">Loading...</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Tagline</label>
            <input type="text" name="tagline" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. Your Paradise Awaits">
          </div>
          <div style="grid-column: span 2 / span 2;">
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="Property description..."></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Status</label>
            <select name="is_active" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
              <option value="1" selected>Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Location -->
      <div class="mb-6">
        <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Location</h4>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Region</label>
            <input type="text" id="region_code_display" list="region_list" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="Select region" onchange="onRegionChange()" />
            <input type="hidden" id="region_code" name="region_code" />
            <datalist id="region_list"></datalist>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Province</label>
            <input type="text" id="province_code_display" list="province_list" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="Select province" onchange="onProvinceChange()" />
            <input type="hidden" id="province_code" name="province_code" />
            <datalist id="province_list"></datalist>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">City/Municipality</label>
            <input type="text" id="city_municipality_code_display" list="city_list" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="Select city/municipality" onchange="onCityChange()" />
            <input type="hidden" id="city_municipality_code" name="city_municipality_code" />
            <datalist id="city_list"></datalist>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Barangay</label>
            <input type="text" id="barangay_code_display" list="barangay_list" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="Select barangay" onchange="onBarangayChange()" />
            <input type="hidden" id="barangay_code" name="barangay_code" />
            <datalist id="barangay_list"></datalist>
          </div>
          <div style="grid-column: span 2 / span 2;">
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Address Line 1 *</label>
            <input type="text" name="address_line1" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="Street address">
          </div>
          <div style="grid-column: span 2 / span 2;">
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Address Line 2</label>
            <input type="text" name="address_line2" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="Apartment, suite, etc.">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Postal Code</label>
            <input type="text" name="postal_code" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. 1000">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Country</label>
            <input type="text" name="country" value="Philippines" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Latitude</label>
            <input type="text" name="latitude" id="latitude" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. 14.5995">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Longitude</label>
            <input type="text" name="longitude" id="longitude" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. 120.9842">
          </div>
          <div style="grid-column: span 2 / span 2;">
            <button type="button" onclick="geocodeAddress()" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
              <i class="fas fa-map-marker-alt mr-2"></i>Get Coordinates from Address
            </button>
          </div>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="mb-6">
        <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Contact Information</h4>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Primary Phone</label>
            <input type="text" name="phone_primary" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. +63 912 345 6789">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Secondary Phone</label>
            <input type="text" name="phone_secondary" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. +63 912 345 6790">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Email</label>
            <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. info@resort.com">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Website URL</label>
            <input type="url" name="website_url" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. https://www.resort.com">
          </div>
          <div style="grid-column: span 2 / span 2;">
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Facebook URL</label>
            <input type="url" name="facebook_url" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. https://www.facebook.com/resort">
          </div>
        </div>
      </div>

      <!-- Check-in/Check-out Settings -->
      <div class="mb-6">
        <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Check-in/Check-out Settings</h4>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Check-in Time</label>
            <input type="time" name="check_in_time" value="14:00" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Check-out Time</label>
            <input type="time" name="check_out_time" value="12:00" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Earliest Check-in</label>
            <input type="time" name="earliest_check_in" value="08:00" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Latest Check-out</label>
            <input type="time" name="latest_check_out" value="22:00" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Late Check-out Fee</label>
            <input type="number" step="0.01" name="late_check_out_fee" value="0.00" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
        </div>
      </div>

      <!-- Settings -->
      <div class="mb-6">
        <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Settings</h4>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Timezone</label>
            <select name="timezone" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
              <option value="Asia/Manila" selected>Asia/Manila</option>
              <option value="Asia/Tokyo">Asia/Tokyo</option>
              <option value="Asia/Singapore">Asia/Singapore</option>
              <option value="Asia/Hong_Kong">Asia/Hong Kong</option>
              <option value="UTC">UTC</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Currency Code</label>
            <select name="currency_code" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
              <option value="PHP" selected>PHP (Philippine Peso)</option>
              <option value="USD">USD (US Dollar)</option>
              <option value="EUR">EUR (Euro)</option>
              <option value="SGD">SGD (Singapore Dollar)</option>
              <option value="JPY">JPY (Japanese Yen)</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Tax Rate (%)</label>
            <input type="number" step="0.01" name="tax_rate_percent" value="0.00" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Sort Order</label>
            <input type="number" name="sort_order" value="0" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
        </div>
      </div>

      <!-- Image Upload -->
      <div class="mb-6">
        <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Property Images</h4>
        <div class="border-2 border-dashed border-gray-300 dark:border-white/20 rounded-lg p-6">
          <div class="text-center mb-4">
            <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-2"></i>
            <p class="text-sm text-slate-600 dark:text-white/60">Click to upload or drag and drop</p>
            <p class="text-xs text-slate-400 dark:text-white/40">JPG, JPEG, PNG, GIF, WEBP (Max 5MB each)</p>
          </div>
          <input type="file" name="property_images[]" multiple accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" id="propertyImagesInput" onchange="handleImagePreview(this)">
          <div id="imagePreviewContainer" style="margin-top: 1rem; display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem;"></div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
        <button type="button" onclick="closeAddPropertyModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
          Cancel
        </button>
        <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
          <i class="fas fa-plus mr-2"></i>Add Property
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Property Modal -->
<div id="editPropertyModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditPropertyModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
  <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
    <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
      <h3 class="text-xl font-bold dark:text-white">Edit Property</h3>
      <button onclick="closeEditPropertyModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
        <i class="fas fa-times text-xl"></i>
      </button>
    </div>
    <form id="editPropertyForm" class="p-6" onsubmit="handleEditPropertySubmit(event)">
      <input type="hidden" name="property_id" id="edit_property_id">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

      <!-- Basic Information -->
      <div class="mb-6">
        <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Basic Information</h4>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Property Code *</label>
            <input type="text" name="property_code" id="edit_property_code" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Property Name *</label>
            <input type="text" name="property_name" id="edit_property_name" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Property Type *</label>
            <select name="property_type" id="edit_property_type" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
              <option value="">Loading...</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Tagline</label>
            <input type="text" name="tagline" id="edit_tagline" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div style="grid-column: span 2 / span 2;">
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Description</label>
            <textarea name="description" id="edit_description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Property Status</label>
            <select name="is_active" id="edit_is_active" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Location -->
      <div class="mb-6">
        <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Location</h4>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Region</label>
            <input type="text" id="edit_region_code_display" list="edit_region_list" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="Select region" onchange="onEditRegionChange()" />
            <input type="hidden" id="edit_region_code" name="region_code" />
            <datalist id="edit_region_list"></datalist>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Province</label>
            <input type="text" id="edit_province_code_display" list="edit_province_list" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="Select province" onchange="onEditProvinceChange()" />
            <input type="hidden" id="edit_province_code" name="province_code" />
            <datalist id="edit_province_list"></datalist>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">City/Municipality</label>
            <input type="text" id="edit_city_municipality_code_display" list="edit_city_list" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="Select city/municipality" onchange="onEditCityChange()" />
            <input type="hidden" id="edit_city_municipality_code" name="city_municipality_code" />
            <datalist id="edit_city_list"></datalist>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Barangay</label>
            <input type="text" id="edit_barangay_code_display" list="edit_barangay_list" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="Select barangay" onchange="onEditBarangayChange()" />
            <input type="hidden" id="edit_barangay_code" name="barangay_code" />
            <datalist id="edit_barangay_list"></datalist>
          </div>
          <div style="grid-column: span 2 / span 2;">
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Address Line 1</label>
            <input type="text" name="address_line1" id="edit_address_line1" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div style="grid-column: span 2 / span 2;">
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Address Line 2</label>
            <input type="text" name="address_line2" id="edit_address_line2" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Postal Code</label>
            <input type="text" name="postal_code" id="edit_postal_code" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Country</label>
            <input type="text" name="country" id="edit_country" value="Philippines" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Latitude</label>
            <input type="number" step="any" name="latitude" id="edit_latitude" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Longitude</label>
            <input type="number" step="any" name="longitude" id="edit_longitude" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div style="grid-column: span 2 / span 2; display: flex; align-items: flex-end;">
            <button type="button" onclick="geocodeEditAddress()" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
              <i class="fas fa-map-marker-alt mr-2"></i>Get Coordinates
            </button>
          </div>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="mb-6">
        <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Contact Information</h4>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Primary Phone</label>
            <input type="tel" name="phone_primary" id="edit_phone_primary" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Secondary Phone</label>
            <input type="tel" name="phone_secondary" id="edit_phone_secondary" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Email</label>
            <input type="email" name="email" id="edit_email" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Website URL</label>
            <input type="url" name="website_url" id="edit_website_url" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div style="grid-column: span 2 / span 2;">
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Facebook URL</label>
            <input type="url" name="facebook_url" id="edit_facebook_url" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
        </div>
      </div>

      <!-- Check-in/Check-out Settings -->
      <div class="mb-6">
        <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Check-in/Check-out Settings</h4>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Check-in Time</label>
            <input type="time" name="check_in_time" id="edit_check_in_time" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Check-out Time</label>
            <input type="time" name="check_out_time" id="edit_check_out_time" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Earliest Check-in</label>
            <input type="time" name="earliest_check_in" id="edit_earliest_check_in" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Latest Check-out</label>
            <input type="time" name="latest_check_out" id="edit_latest_check_out" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Late Check-out Fee</label>
            <input type="number" step="0.01" name="late_check_out_fee" id="edit_late_check_out_fee" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
        </div>
      </div>

      <!-- Settings -->
      <div class="mb-6">
        <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Settings</h4>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Timezone</label>
            <select name="timezone" id="edit_timezone" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
              <option value="Asia/Manila">Asia/Manila</option>
              <option value="Asia/Tokyo">Asia/Tokyo</option>
              <option value="Asia/Singapore">Asia/Singapore</option>
              <option value="Asia/Hong_Kong">Asia/Hong Kong</option>
              <option value="UTC">UTC</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Currency Code</label>
            <select name="currency_code" id="edit_currency_code" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
              <option value="PHP">PHP (Philippine Peso)</option>
              <option value="USD">USD (US Dollar)</option>
              <option value="EUR">EUR (Euro)</option>
              <option value="SGD">SGD (Singapore Dollar)</option>
              <option value="JPY">JPY (Japanese Yen)</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Tax Rate (%)</label>
            <input type="number" step="0.01" name="tax_rate_percent" id="edit_tax_rate_percent" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Sort Order</label>
            <input type="number" name="sort_order" id="edit_sort_order" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
          </div>
        </div>
      </div>

      <!-- Existing Images -->
      <div class="mb-6">
        <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Existing Images</h4>
        <div id="editExistingImagesContainer" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem;"></div>
      </div>

      <!-- Image Upload -->
      <div class="mb-6">
        <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Add New Images</h4>
        <div class="border-2 border-dashed border-gray-300 dark:border-white/20 rounded-lg p-6">
          <div class="text-center mb-4">
            <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-2"></i>
            <p class="text-sm text-slate-600 dark:text-white/60">Click to upload or drag and drop</p>
            <p class="text-xs text-slate-400 dark:text-white/40">JPG, JPEG, PNG, GIF, WEBP (Max 5MB each)</p>
          </div>
          <input type="file" name="property_images[]" multiple accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" id="editPropertyImagesInput" onchange="handleEditImagePreview(this)">
          <div id="editImagePreviewContainer" style="margin-top: 1rem; display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem;"></div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
        <button type="button" onclick="closeEditPropertyModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
          Cancel
        </button>
        <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
          <i class="fas fa-save mr-2"></i>Update Property
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  async function viewPropertyDetails(propertyId) {
    const property = propertiesData.find(p => p.property_id === propertyId);
    if (!property) return;

    const modal = document.getElementById('propertyDetailsModal');
    const modalName = document.getElementById('modalPropertyName');
    const modalContent = document.getElementById('modalPropertyContent');

    modalName.textContent = property.property_name;

    // Get property images
    const images = propertyImagesData[propertyId] || [];

    // Fetch geography data to get names from codes
    let regionName = '-';
    let provinceName = '-';
    let cityName = '-';
    let barangayName = '-';

    if (property.region_code) {
      const regions = await fetchRegions();
      const region = regions.find(r => r.region_code === property.region_code);
      regionName = region ? region.region_name : '-';
    }

    if (property.province_code) {
      const provinces = await fetchProvinces(property.region_code);
      const province = provinces.find(p => p.province_code === property.province_code);
      provinceName = province ? province.province_name : '-';
    }

    if (property.city_municipality_code) {
      const cities = await fetchCities(property.province_code);
      const city = cities.find(c => c.city_municipality_code === property.city_municipality_code);
      cityName = city ? city.city_municipality_name : '-';
    }

    if (property.barangay_code) {
      const barangays = await fetchBarangays(property.city_municipality_code);
      const barangay = barangays.find(b => b.barangay_code === property.barangay_code);
      barangayName = barangay ? barangay.barangay_name : '-';
    }

    // Build images HTML
    let imagesHtml = '';
    if (images.length > 0) {
      imagesHtml = `
          <div class="mb-6">
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Property Images</h4>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem;">
              ${images.map(img => {
                // The image_url is stored as 'uploads/properties/filename.png'
                // From build/pages/properties.php, we need to go up 2 levels to reach root
                const imagePath = `../../${img.image_url}`;
                console.log('Image URL from DB:', img.image_url);
                console.log('Full image path:', imagePath);
                return `
                <div class="relative group">
                  <img src="${imagePath}" alt="${img.caption || 'Property image'}" style="width: 100%; height: 6rem; object-fit: cover; border-radius: 0.5rem; cursor: pointer;" class="hover:opacity-90 transition-opacity bg-gray-200" onerror="console.log('Failed to load:', '${imagePath}'); this.style.display='none'; this.parentElement.innerHTML='<div style=\'width: 100%; height: 6rem; background: #e5e7eb; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: #6b7280; font-size: 0.75rem;\'>Image not found: ${img.image_url}</div>'" onclick="window.open('${imagePath}', '_blank')">
                  ${img.caption ? `<p class="text-xs text-slate-600 dark:text-white/80 mt-1 truncate">${img.caption}</p>` : ''}
                  <span class="absolute top-2 right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                    ${img.image_type}
                  </span>
                </div>
              `}).join('')}
            </div>
          </div>
        `;
    } else {
      imagesHtml = `
          <div class="mb-6">
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Property Images</h4>
            <p class="text-sm text-slate-500 dark:text-white/60">No images uploaded for this property.</p>
          </div>
        `;
    }

    modalContent.innerHTML = `
        ${imagesHtml}
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
          <div>
            <h4 class="text-base font-bold text-slate-500 dark:text-white/60 uppercase mb-4" style="font-size: 0.875rem; font-weight: bold; text-transform: uppercase; margin-bottom: 1rem;">Basic Information</h4>
            <div class="space-y-4">
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Property Code</p>
                <p class="text-base font-semibold dark:text-white" style="font-size: 1rem; font-weight: 600;">${property.property_code}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Property Type</p>
                <p class="text-base font-semibold dark:text-white" style="font-size: 1rem; font-weight: 600;">${property.property_type.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Tagline</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.tagline || '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Description</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.description || '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Status</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.is_active ? 'Active' : 'Inactive'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-base font-bold text-slate-500 dark:text-white/60 uppercase mb-4" style="font-size: 0.875rem; font-weight: bold; text-transform: uppercase; margin-bottom: 1rem;">Location</h4>
            <div class="space-y-4">
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Address Line 1</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.address_line1 || '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Address Line 2</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.address_line2 || '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Region</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${regionName}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Province</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${provinceName}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">City/Municipality</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${cityName}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Barangay</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${barangayName}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Postal Code</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.postal_code || '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Country</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.country}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Coordinates</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.latitude && property.longitude ? `${property.latitude}, ${property.longitude}` : '-'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-base font-bold text-slate-500 dark:text-white/60 uppercase mb-4" style="font-size: 0.875rem; font-weight: bold; text-transform: uppercase; margin-bottom: 1rem;">Contact Information</h4>
            <div class="space-y-4">
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Primary Phone</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.phone_primary || '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Secondary Phone</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.phone_secondary || '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Email</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.email || '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Website</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.website_url ? `<a href="${property.website_url}" target="_blank" class="text-blue-500 hover:underline">${property.website_url}</a>` : '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Facebook</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.facebook_url ? `<a href="${property.facebook_url}" target="_blank" class="text-blue-500 hover:underline">${property.facebook_url}</a>` : '-'}</p>
              </div>
            </div>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; margin-top: 2rem;">
          <div>
            <h4 class="text-base font-bold text-slate-500 dark:text-white/60 uppercase mb-4" style="font-size: 0.875rem; font-weight: bold; text-transform: uppercase; margin-bottom: 1rem;">Check-in/Check-out</h4>
            <div class="space-y-4">
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Check-in Time</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.check_in_time}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Check-out Time</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.check_out_time}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Earliest Check-in</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.earliest_check_in}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Latest Check-out</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.latest_check_out}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Late Check-out Fee</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.currency_code || 'PHP'} ${parseFloat(property.late_check_out_fee).toFixed(2)}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-base font-bold text-slate-500 dark:text-white/60 uppercase mb-4" style="font-size: 0.875rem; font-weight: bold; text-transform: uppercase; margin-bottom: 1rem;">Settings</h4>
            <div class="space-y-4">
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Timezone</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.timezone}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Currency</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.currency_code}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Tax Rate</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.tax_rate_percent}%</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Created At</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${new Date(property.created_at).toLocaleString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true })}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Updated At</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.updated_at ? new Date(property.updated_at).toLocaleString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true }) : '-'}</p>
              </div>
            </div>
          </div>
        </div>
      `;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closePropertyDetailsModal() {
    const modal = document.getElementById('propertyDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  // Toggle Rooms Dropdown Function
  async function toggleRoomsDropdown(propertyId) {
    const dropdown = document.getElementById('roomsDropdown-' + propertyId);
    const btn = document.getElementById('roomsBtn-' + propertyId);

    if (dropdown.classList.contains('hidden')) {
      dropdown.classList.remove('hidden');
      btn.innerHTML = '<i class="fas fa-chevron-up mr-1"></i>Hide Rooms';
      
      // Fetch rooms for this property
      await fetchRoomsForProperty(propertyId);
    } else {
      dropdown.classList.add('hidden');
      btn.innerHTML = '<i class="fas fa-door-open mr-1.5 text-sm"></i><span>Rooms</span>';
    }
  }

  async function fetchRoomsForProperty(propertyId) {
    const dropdown = document.getElementById('roomsDropdown-' + propertyId);
    const contentDiv = dropdown.querySelector('div');
    
    try {
      const response = await fetch(`../api/rooms.php?action=by_property&property_id=${propertyId}`);
      const result = await response.json();
      
      if (result.success && result.data.length > 0) {
        renderRoomsInDropdown(propertyId, result.data);
      } else {
        contentDiv.innerHTML = `
          <div class="p-6 text-center">
            <p class="text-slate-500 dark:text-white/60">No rooms found for this property.</p>
          </div>
        `;
      }
    } catch (error) {
      console.error('Error fetching rooms:', error);
      contentDiv.innerHTML = `
        <div class="p-6 text-center">
          <p class="text-red-500">Error loading rooms. Please try again.</p>
        </div>
      `;
    }
  }

  function renderRoomsInDropdown(propertyId, rooms) {
    const dropdown = document.getElementById('roomsDropdown-' + propertyId);
    const contentDiv = dropdown.querySelector('div');
    
    let roomsHtml = `
      <div class="p-6">
        <div class="mb-4 flex items-center justify-between">
          <h6 class="text-base font-bold dark:text-white">Rooms (${rooms.length})</h6>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    `;
    
    rooms.forEach(room => {
      const statusColor = room.status === 'available' ? 'bg-emerald-500' : 
                          room.status === 'occupied' ? 'bg-red-500' : 
                          room.status === 'maintenance' ? 'bg-amber-500' : 'bg-slate-500';
      
      roomsHtml += `
        <div class="p-4 bg-white dark:bg-slate-700 rounded-lg border border-gray-200 dark:border-white/10 shadow-sm">
          <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-semibold dark:text-white">${room.room_number}</span>
            <span class="px-2 py-1 text-xs font-semibold text-white rounded-full ${statusColor}">${room.status}</span>
          </div>
          <div class="space-y-1 text-sm text-slate-600 dark:text-white/70">
            <p><span class="text-xs text-slate-500 dark:text-white/50">Type:</span> ${room.room_type || '-'}</p>
            <p><span class="text-xs text-slate-500 dark:text-white/50">Floor:</span> ${room.floor_number || '-'}</p>
            <p><span class="text-xs text-slate-500 dark:text-white/50">Capacity:</span> ${room.max_occupancy || '-'} guests</p>
            <p><span class="text-xs text-slate-500 dark:text-white/50">Price:</span> ₱${room.base_price || '0'}</p>
          </div>
        </div>
      `;
    });
    
    roomsHtml += `
        </div>
      </div>
    `;
    
    contentDiv.innerHTML = roomsHtml;
  }

  // Add Property Modal Functions
  function openAddPropertyModal() {
    const modal = document.getElementById('addPropertyModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }

  function closeAddPropertyModal() {
    const modal = document.getElementById('addPropertyModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    // Reset form
    const form = modal.querySelector('form');
    if (form) {
      form.reset();
    }
    // Clear image preview
    const previewContainer = document.getElementById('imagePreviewContainer');
    if (previewContainer) {
      previewContainer.innerHTML = '';
    }
  }

  // Image Preview Function
  function handleImagePreview(input) {
    const previewContainer = document.getElementById('imagePreviewContainer');
    previewContainer.innerHTML = '';

    if (input.files && input.files.length > 0) {
      Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();

        reader.onload = function(e) {
          const previewDiv = document.createElement('div');
          previewDiv.className = 'relative';
          previewDiv.innerHTML = `
              <img src="${e.target.result}" alt="Preview ${index + 1}" style="width: 100%; height: 6rem; object-fit: cover; border-radius: 0.5rem;">
              <button type="button" onclick="removeImage(${index})" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors">
                <i class="fas fa-times text-xs"></i>
              </button>
              <input type="text" name="image_captions[]" placeholder="Caption (optional)" class="mt-2 w-full px-2 py-1 text-xs border border-gray-300 dark:border-white/20 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
            `;
          previewContainer.appendChild(previewDiv);
        };

        reader.readAsDataURL(file);
      });
    }
  }

  function removeImage(index) {
    const input = document.getElementById('propertyImagesInput');
    const previewContainer = document.getElementById('imagePreviewContainer');

    // Remove file from input
    const filesArray = Array.from(input.files);
    filesArray.splice(index, 1);

    // Create new FileList
    const dataTransfer = new DataTransfer();
    filesArray.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;

    // Refresh preview
    handleImagePreview(input);
  }

  // Delete Property Function (API-based)
  async function confirmDeleteProperty(propertyId, propertyName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete "${propertyName}"? This action will also delete all associated images and cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'swal-confirm-button',
        cancelButton: 'swal-cancel-button'
      },
      buttonsStyling: true
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          const response = await fetch('../api/properties.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              action: 'delete',
              property_id: propertyId
            })
          });
          const result = await response.json();

          if (result.success) {
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Property deleted successfully!',
              timer: 2000,
              showConfirmButton: false
            });
            // Refresh the data
            await fetchPropertiesData();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: result.message || 'Failed to delete property',
              confirmButtonColor: '#3085d6'
            });
          }
        } catch (error) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while deleting the property',
            confirmButtonColor: '#3085d6'
          });
        }
      }
    });
  }

  // Edit Property Modal Functions
  async function openEditPropertyModal(propertyId) {
    try {
      const property = propertiesData.find(p => p.property_id === propertyId);
      if (!property) return;

      // Initialize geography dropdowns
      const regions = await fetchRegions();
      populateDatalist('edit_region_list', regions, 'region_name', 'region_code');

      // Initialize property types dropdown
      await fetchPropertyTypes('edit_property_type');

      // Populate form fields with existing data
      document.getElementById('edit_property_id').value = property.property_id;
      document.getElementById('edit_property_code').value = property.property_code;
      document.getElementById('edit_property_name').value = property.property_name;
      document.getElementById('edit_property_type').value = property.property_type;
      document.getElementById('edit_tagline').value = property.tagline || '';
      document.getElementById('edit_description').value = property.description || '';
      document.getElementById('edit_address_line1').value = property.address_line1;
      document.getElementById('edit_address_line2').value = property.address_line2 || '';
      document.getElementById('edit_postal_code').value = property.postal_code || '';
      document.getElementById('edit_country').value = property.country;
      document.getElementById('edit_latitude').value = property.latitude || '';
      document.getElementById('edit_longitude').value = property.longitude || '';
      document.getElementById('edit_phone_primary').value = property.phone_primary || '';
      document.getElementById('edit_phone_secondary').value = property.phone_secondary || '';
      document.getElementById('edit_email').value = property.email || '';
      document.getElementById('edit_website_url').value = property.website_url || '';
      document.getElementById('edit_facebook_url').value = property.facebook_url || '';
      document.getElementById('edit_check_in_time').value = property.check_in_time || '14:00';
      document.getElementById('edit_check_out_time').value = property.check_out_time || '12:00';
      document.getElementById('edit_earliest_check_in').value = property.earliest_check_in || '08:00';
      document.getElementById('edit_latest_check_out').value = property.latest_check_out || '22:00';
      document.getElementById('edit_late_check_out_fee').value = property.late_check_out_fee || 0.00;
      document.getElementById('edit_timezone').value = property.timezone || 'Asia/Manila';
      document.getElementById('edit_currency_code').value = property.currency_code || 'PHP';
      document.getElementById('edit_tax_rate_percent').value = property.tax_rate_percent || 0.00;
      document.getElementById('edit_sort_order').value = property.sort_order || 0;
      document.getElementById('edit_is_active').value = property.is_active ? '1' : '0';

      // Populate geography dropdowns with existing data
      if (property.region_code) {
        document.getElementById('edit_region_code').value = property.region_code;
        const region = regions.find(r => r.region_code === property.region_code);
        if (region) {
          document.getElementById('edit_region_code_display').value = region.region_name;
          const provinces = await fetchProvinces(property.region_code);
          populateDatalist('edit_province_list', provinces, 'province_name', 'province_code');
        }
      }

      if (property.province_code) {
        document.getElementById('edit_province_code').value = property.province_code;
        const provinces = await fetchProvinces(property.region_code);
        const province = provinces.find(p => p.province_code === property.province_code);
        if (province) {
          document.getElementById('edit_province_code_display').value = province.province_name;
          const cities = await fetchCities(property.province_code);
          populateDatalist('edit_city_list', cities, 'city_municipality_name', 'city_municipality_code');
        }
      }

      if (property.city_municipality_code) {
        document.getElementById('edit_city_municipality_code').value = property.city_municipality_code;
        const cities = await fetchCities(property.province_code);
        const city = cities.find(c => c.city_municipality_code === property.city_municipality_code);
        if (city) {
          document.getElementById('edit_city_municipality_code_display').value = city.city_municipality_name;
          const barangays = await fetchBarangays(property.city_municipality_code);
          populateDatalist('edit_barangay_list', barangays, 'barangay_name', 'barangay_code');
        }
      }

      if (property.barangay_code) {
        document.getElementById('edit_barangay_code').value = property.barangay_code;
        const barangays = await fetchBarangays(property.city_municipality_code);
        const barangay = barangays.find(b => b.barangay_code === property.barangay_code);
        if (barangay) {
          document.getElementById('edit_barangay_code_display').value = barangay.barangay_name;
        }
      }
    } catch (error) {
      console.error('Error opening edit modal:', error);
    }

    // Open modal (always try to open even if there's an error)
    const modal = document.getElementById('editPropertyModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';

    // Display existing images
    const images = propertyImagesData[propertyId] || [];
    const existingImagesContainer = document.getElementById('editExistingImagesContainer');
    existingImagesContainer.innerHTML = '';

    if (images.length > 0) {
      images.forEach(img => {
        const imgDiv = document.createElement('div');
        imgDiv.className = 'relative group';
        imgDiv.innerHTML = `
            <img src="../../${img.image_url}" alt="${img.caption || 'Property image'}" style="width: 100%; height: 6rem; object-fit: cover; border-radius: 0.5rem;">
            <div class="absolute top-2 right-2 flex gap-1">
              <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">${img.image_type}</span>
              <button type="button" onclick="markImageForDeletion('${img.image_id}', this)" class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors" title="Delete image">
                <i class="fas fa-trash text-xs"></i>
              </button>
            </div>
            <input type="hidden" name="delete_images[]" value="" data-image-id="${img.image_id}" class="delete-image-input">
            ${img.caption ? `<p class="text-xs text-slate-600 dark:text-white/80 mt-1 truncate">${img.caption}</p>` : ''}
          `;
        existingImagesContainer.appendChild(imgDiv);
      });
    } else {
      existingImagesContainer.innerHTML = '<p class="text-sm text-slate-500 dark:text-white/60" style="grid-column: span 4 / span 4;">No images uploaded for this property.</p>';
    }
  }

  function closeEditPropertyModal() {
    const modal = document.getElementById('editPropertyModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';

    // Reset form
    const form = modal.querySelector('form');
    if (form) {
      form.reset();
    }

    // Clear image preview
    const previewContainer = document.getElementById('editImagePreviewContainer');
    if (previewContainer) {
      previewContainer.innerHTML = '';
    }
  }

  function markImageForDeletion(imageId, button) {
    const input = document.querySelector(`.delete-image-input[data-image-id="${imageId}"]`);
    if (input) {
      if (input.value === imageId) {
        // Unmark for deletion
        input.value = '';
        button.classList.remove('bg-red-600');
        button.classList.add('bg-red-500');
        button.parentElement.parentElement.querySelector('img').style.opacity = '1';
      } else {
        // Mark for deletion
        input.value = imageId;
        button.classList.remove('bg-red-500');
        button.classList.add('bg-red-600');
        button.parentElement.parentElement.querySelector('img').style.opacity = '0.5';
      }
    }
  }

  function handleEditImagePreview(input) {
    const previewContainer = document.getElementById('editImagePreviewContainer');
    previewContainer.innerHTML = '';

    if (input.files && input.files.length > 0) {
      Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();

        reader.onload = function(e) {
          const previewDiv = document.createElement('div');
          previewDiv.className = 'relative';
          previewDiv.innerHTML = `
              <img src="${e.target.result}" alt="Preview ${index + 1}" style="width: 100%; height: 6rem; object-fit: cover; border-radius: 0.5rem;">
              <button type="button" onclick="removeEditImage(${index})" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors">
                <i class="fas fa-times text-xs"></i>
              </button>
              <input type="text" name="image_captions[]" placeholder="Caption (optional)" class="mt-2 w-full px-2 py-1 text-xs border border-gray-300 dark:border-white/20 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
            `;
          previewContainer.appendChild(previewDiv);
        };

        reader.readAsDataURL(file);
      });
    }
  }

  function removeEditImage(index) {
    const input = document.getElementById('editPropertyImagesInput');
    const previewContainer = document.getElementById('editImagePreviewContainer');

    // Remove file from input
    const filesArray = Array.from(input.files);
    filesArray.splice(index, 1);

    // Create new FileList
    const dataTransfer = new DataTransfer();
    filesArray.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;

    // Refresh preview
    handleEditImagePreview(input);
  }

  // Add Property Form Submission (API-based)
  async function handleAddPropertySubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // Generate UUID for property_id
    const property_id = generateUUID();

    // Get selected geography codes
    const regionCode = document.getElementById('region_code').value;
    const provinceCode = document.getElementById('province_code').value;
    const cityCode = document.getElementById('city_municipality_code').value;
    const barangayCode = document.getElementById('barangay_code').value;

    // Prepare data for API
    const data = {
      csrf_token: formData.get('csrf_token'),
      property_id: property_id,
      property_code: formData.get('property_code'),
      property_name: formData.get('property_name'),
      property_type: formData.get('property_type'),
      tagline: formData.get('tagline') || null,
      description: formData.get('description') || null,
      address_line1: formData.get('address_line1'),
      address_line2: formData.get('address_line2') || null,
      region_code: regionCode || null,
      province_code: provinceCode || null,
      city_municipality_code: cityCode || null,
      barangay_code: barangayCode || null,
      postal_code: formData.get('postal_code') || null,
      country: formData.get('country') || 'Philippines',
      latitude: formData.get('latitude') || null,
      longitude: formData.get('longitude') || null,
      phone_primary: formData.get('phone_primary') || null,
      phone_secondary: formData.get('phone_secondary') || null,
      email: formData.get('email') || null,
      website_url: formData.get('website_url') || null,
      facebook_url: formData.get('facebook_url') || null,
      check_in_time: formData.get('check_in_time') || '14:00:00',
      check_out_time: formData.get('check_out_time') || '12:00:00',
      earliest_check_in: formData.get('earliest_check_in') || '08:00:00',
      latest_check_out: formData.get('latest_check_out') || '22:00:00',
      late_check_out_fee: formData.get('late_check_out_fee') || 0.00,
      timezone: formData.get('timezone') || 'Asia/Manila',
      currency_code: formData.get('currency_code') || 'PHP',
      tax_rate_percent: formData.get('tax_rate_percent') || 0.00,
      is_active: formData.get('is_active') || 1,
      sort_order: formData.get('sort_order') || 0
    };

    try {
      console.log('Submitting property data:', data);

      const response = await fetch('../api/properties.php?action=create', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': data.csrf_token
        },
        body: JSON.stringify(data)
      });

      console.log('Response status:', response.status);

      const result = await response.json();
      console.log('Response data:', result);

      if (result.success) {
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: result.message || 'Property added successfully!',
          timer: 2000,
          showConfirmButton: false
        });
        closeAddPropertyModal();
        await fetchPropertiesData();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: result.message || 'Failed to add property',
          confirmButtonColor: '#3085d6'
        });
      }
    } catch (error) {
      console.error('Fetch error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'An error occurred while adding the property: ' + error.message,
        confirmButtonColor: '#3085d6'
      });
    }
  }

  // Edit Property Form Submission (API-based)
  async function handleEditPropertySubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // Get selected geography codes
    const regionCode = document.getElementById('edit_region_code').value;
    const provinceCode = document.getElementById('edit_province_code').value;
    const cityCode = document.getElementById('edit_city_municipality_code').value;
    const barangayCode = document.getElementById('edit_barangay_code').value;

    const data = {
      csrf_token: formData.get('csrf_token'),
      property_id: formData.get('property_id'),
      property_code: formData.get('property_code'),
      property_name: formData.get('property_name'),
      property_type: formData.get('property_type'),
      tagline: formData.get('tagline') || null,
      description: formData.get('description') || null,
      address_line1: formData.get('address_line1'),
      address_line2: formData.get('address_line2') || null,
      region_code: regionCode || null,
      province_code: provinceCode || null,
      city_municipality_code: cityCode || null,
      barangay_code: barangayCode || null,
      postal_code: formData.get('postal_code') || null,
      country: formData.get('country') || 'Philippines',
      latitude: formData.get('latitude') || null,
      longitude: formData.get('longitude') || null,
      phone_primary: formData.get('phone_primary') || null,
      phone_secondary: formData.get('phone_secondary') || null,
      email: formData.get('email') || null,
      website_url: formData.get('website_url') || null,
      facebook_url: formData.get('facebook_url') || null,
      check_in_time: formData.get('check_in_time') || '14:00:00',
      check_out_time: formData.get('check_out_time') || '12:00:00',
      earliest_check_in: formData.get('earliest_check_in') || '08:00:00',
      latest_check_out: formData.get('latest_check_out') || '22:00:00',
      late_check_out_fee: formData.get('late_check_out_fee') || 0.00,
      timezone: formData.get('timezone') || 'Asia/Manila',
      currency_code: formData.get('currency_code') || 'PHP',
      tax_rate_percent: formData.get('tax_rate_percent') || 0.00,
      is_active: formData.get('is_active') || 1,
      sort_order: formData.get('sort_order') || 0
    };

    try {
      console.log('Submitting update data:', data);

      const response = await fetch('../api/properties.php?action=update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': data.csrf_token
        },
        body: JSON.stringify(data)
      });

      console.log('Response status:', response.status);

      const result = await response.json();
      console.log('Response data:', result);

      if (result.success) {
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: result.message || 'Property updated successfully!',
          timer: 2000,
          showConfirmButton: false
        });
        closeEditPropertyModal();
        await fetchPropertiesData();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: result.message || 'Failed to update property',
          confirmButtonColor: '#3085d6'
        });
      }
    } catch (error) {
      console.error('Fetch error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'An error occurred while updating the property: ' + error.message,
        confirmButtonColor: '#3085d6'
      });
    }
  }

  // Filter table function
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('propertiesTable');
    const rows = table.getElementsByClassName('property-row');

    for (let i = 0; i < rows.length; i++) {
      const row = rows[i];
      const cells = row.getElementsByTagName('td');
      let found = false;

      for (let j = 0; j < cells.length; j++) {
        if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
          found = true;
          break;
        }
      }

      row.style.display = found ? '' : 'none';
    }
  }

  // Geography dropdown functions

  // Geocoding function using OpenStreetMap Nominatim API
  async function geocodeAddress() {
    const addressLine1 = document.getElementById('address_line1').value;
    const cityDisplay = document.getElementById('city_municipality_code_display').value;
    const provinceDisplay = document.getElementById('province_code_display').value;

    if (!cityDisplay) {
      console.log('City not selected, skipping geocoding');
      return;
    }

    try {
      const lat = document.getElementById('latitude');
      const lon = document.getElementById('longitude');
      const result = await nominatimGeocode(addressLine1, cityDisplay, provinceDisplay);
      if (result) {
        lat.value = result.lat;
        lon.value = result.lon;
      }
    } catch (error) {
      console.error('Geocoding error:', error);
    }
  }

  // Edit modal geocoding function
  async function geocodeEditAddress() {
    const addressLine1 = document.getElementById('edit_address_line1').value;
    const cityDisplay = document.getElementById('edit_city_municipality_code_display').value;
    const provinceDisplay = document.getElementById('edit_province_code_display').value;

    if (!cityDisplay) {
      console.log('City not selected, skipping geocoding');
      return;
    }

    try {
      const lat = document.getElementById('edit_latitude');
      const lon = document.getElementById('edit_longitude');
      const result = await nominatimGeocode(addressLine1, cityDisplay, provinceDisplay);
      if (result) {
        lat.value = result.lat;
        lon.value = result.lon;
      }
    } catch (error) {
      console.error('Geocoding error:', error);
    }
  }

  // Shared Nominatim geocoding with fallback strategy
  async function nominatimGeocode(street, city, province) {
    const baseUrl = 'https://nominatim.openstreetmap.org/search';
    const headers = { 'Accept-Language': 'en' };

    // Strategy 1: structured query with street + city + province
    if (street) {
      const params = new URLSearchParams({ format: 'json', limit: 1, countrycodes: 'ph', street, city, county: province });
      const res = await fetch(`${baseUrl}?${params}`, { headers });
      const data = await res.json();
      if (data.length > 0) return data[0];
    }

    // Strategy 2: city + province structured
    const params2 = new URLSearchParams({ format: 'json', limit: 1, countrycodes: 'ph', city, county: province });
    const res2 = await fetch(`${baseUrl}?${params2}`, { headers });
    const data2 = await res2.json();
    if (data2.length > 0) return data2[0];

    // Strategy 3: city only
    const params3 = new URLSearchParams({ format: 'json', limit: 1, countrycodes: 'ph', city });
    const res3 = await fetch(`${baseUrl}?${params3}`, { headers });
    const data3 = await res3.json();
    if (data3.length > 0) return data3[0];

    console.log('No geocoding results found for:', city, province);
    return null;
  }

  async function fetchRegions() {
    try {
      const response = await fetch('../api/geocode.php?action=regions');
      const result = await response.json();
      if (result.success) {
        regionsData = result.data;
        return result.data;
      }
      return [];
    } catch (error) {
      console.error('Error fetching regions:', error);
      return [];
    }
  }

  async function fetchProvinces(regionCode) {
    try {
      const response = await fetch(`../api/geocode.php?action=provinces&region_code=${regionCode}`);
      const result = await response.json();
      if (result.success) {
        return result.data;
      }
      return [];
    } catch (error) {
      console.error('Error fetching provinces:', error);
      return [];
    }
  }

  async function fetchCities(provinceCode) {
    try {
      const response = await fetch(`../api/geocode.php?action=cities_municipalities&province_code=${provinceCode}`);
      const result = await response.json();
      if (result.success) {
        return result.data;
      }
      return [];
    } catch (error) {
      console.error('Error fetching cities:', error);
      return [];
    }
  }

  async function fetchBarangays(cityCode) {
    try {
      const response = await fetch(`../api/geocode.php?action=barangays&city_municipality_code=${cityCode}`);
      const result = await response.json();
      if (result.success) {
        return result.data;
      }
      return [];
    } catch (error) {
      console.error('Error fetching barangays:', error);
      return [];
    }
  }

  function populateDatalist(datalistId, data, labelField, codeField = null) {
    const datalist = document.getElementById(datalistId);
    if (!datalist) return;
    datalist.innerHTML = '';
    data.forEach(item => {
      const option = document.createElement('option');
      option.value = item[labelField];
      if (codeField) {
        option.dataset.code = item[codeField];
      }
      datalist.appendChild(option);
    });
  }

  function getSelectedCode(displayInputId, datalistId) {
    const displayInput = document.getElementById(displayInputId);
    const datalist = document.getElementById(datalistId);
    if (!displayInput || !datalist) return null;
    const selectedValue = displayInput.value;
    const option = Array.from(datalist.options).find(opt => opt.value === selectedValue);
    return option ? option.dataset.code : null;
  }

  // Add Property modal geography handlers
  async function onRegionChange() {
    const regionCode = getSelectedCode('region_code_display', 'region_list');
    document.getElementById('region_code').value = regionCode || '';
    if (regionCode) {
      const provinces = await fetchProvinces(regionCode);
      populateDatalist('province_list', provinces, 'province_name', 'province_code');
    }
    document.getElementById('province_code_display').value = '';
    document.getElementById('province_code').value = '';
    document.getElementById('city_municipality_code_display').value = '';
    document.getElementById('city_municipality_code').value = '';
    document.getElementById('barangay_code_display').value = '';
    document.getElementById('barangay_code').value = '';
  }

  async function onProvinceChange() {
    const provinceCode = getSelectedCode('province_code_display', 'province_list');
    document.getElementById('province_code').value = provinceCode || '';
    if (provinceCode) {
      const cities = await fetchCities(provinceCode);
      populateDatalist('city_list', cities, 'city_municipality_name', 'city_municipality_code');
    }
    document.getElementById('city_municipality_code_display').value = '';
    document.getElementById('city_municipality_code').value = '';
    document.getElementById('barangay_code_display').value = '';
    document.getElementById('barangay_code').value = '';
  }

  async function onCityChange() {
    const cityCode = getSelectedCode('city_municipality_code_display', 'city_list');
    document.getElementById('city_municipality_code').value = cityCode || '';
    if (cityCode) {
      const barangays = await fetchBarangays(cityCode);
      populateDatalist('barangay_list', barangays, 'barangay_name', 'barangay_code');
      // Auto-geocode when city is selected
      geocodeAddress();
    }
    document.getElementById('barangay_code_display').value = '';
    document.getElementById('barangay_code').value = '';
  }

  function onBarangayChange() {
    const barangayCode = getSelectedCode('barangay_code_display', 'barangay_list');
    document.getElementById('barangay_code').value = barangayCode || '';
    // Auto-geocode when barangay is selected
    if (barangayCode) {
      geocodeAddress();
    }
  }

  // Edit Property modal geography handlers
  async function onEditRegionChange() {
    const regionCode = getSelectedCode('edit_region_code_display', 'edit_region_list');
    document.getElementById('edit_region_code').value = regionCode || '';
    if (regionCode) {
      const provinces = await fetchProvinces(regionCode);
      populateDatalist('edit_province_list', provinces, 'province_name', 'province_code');
    }
    document.getElementById('edit_province_code_display').value = '';
    document.getElementById('edit_province_code').value = '';
    document.getElementById('edit_city_municipality_code_display').value = '';
    document.getElementById('edit_city_municipality_code').value = '';
    document.getElementById('edit_barangay_code_display').value = '';
    document.getElementById('edit_barangay_code').value = '';
  }

  async function onEditProvinceChange() {
    const provinceCode = getSelectedCode('edit_province_code_display', 'edit_province_list');
    document.getElementById('edit_province_code').value = provinceCode || '';
    if (provinceCode) {
      const cities = await fetchCities(provinceCode);
      populateDatalist('edit_city_list', cities, 'city_municipality_name', 'city_municipality_code');
    }
    document.getElementById('edit_city_municipality_code_display').value = '';
    document.getElementById('edit_city_municipality_code').value = '';
    document.getElementById('edit_barangay_code_display').value = '';
    document.getElementById('edit_barangay_code').value = '';
  }

  async function onEditCityChange() {
    const cityCode = getSelectedCode('edit_city_municipality_code_display', 'edit_city_list');
    document.getElementById('edit_city_municipality_code').value = cityCode || '';
    if (cityCode) {
      const barangays = await fetchBarangays(cityCode);
      populateDatalist('edit_barangay_list', barangays, 'barangay_name', 'barangay_code');
      // Auto-geocode when city is selected
      geocodeEditAddress();
    }
    document.getElementById('edit_barangay_code_display').value = '';
    document.getElementById('edit_barangay_code').value = '';
  }

  function onEditBarangayChange() {
    const barangayCode = getSelectedCode('edit_barangay_code_display', 'edit_barangay_list');
    document.getElementById('edit_barangay_code').value = barangayCode || '';
    // Auto-geocode when barangay is selected
    if (barangayCode) {
      geocodeEditAddress();
    }
  }

  // Initialize geography dropdowns when modals open
  const originalOpenAddPropertyModal = openAddPropertyModal;
  openAddPropertyModal = async function() {
    const regions = await fetchRegions();
    populateDatalist('region_list', regions, 'region_name', 'region_code');
    await fetchPropertyTypes('property_type');
    originalOpenAddPropertyModal();
  };

  // Fetch and populate property types
  async function fetchPropertyTypes(selectId) {
    try {
      const response = await fetch('../api/property_types.php?action=list');
      const result = await response.json();
      if (result.success) {
        const select = document.getElementById(selectId);
        if (select) {
          select.innerHTML = '<option value="">Select property type</option>';
          result.data.forEach(type => {
            const option = document.createElement('option');
            option.value = type.type_code;
            option.textContent = type.type_name;
            select.appendChild(option);
          });
        }
      }
    } catch (error) {
      console.error('Error fetching property types:', error);
    }
  }

  // No wrapper needed for openEditPropertyModal since it's already async and initializes dropdowns
</script>