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
  // Helper function to generate UUID (JavaScript version)
  function generateUUID() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
      const r = Math.random() * 16 | 0;
      const v = c === 'x' ? r : (r & 0x3 | 0x8);
      return v.toString(16);
    });
  }

  // Helper function to capitalize first letter of each word (PHP ucwords equivalent)
  function ucwords(str) {
    return str.toLowerCase().replace(/\b\w/g, function(char) {
      return char.toUpperCase();
    });
  }

  // Helper function to get icon for image type
  function getImageTypeIcon(imageType) {
    const iconMap = {
      'interior': 'fa-couch',
      'exterior': 'fa-building',
      'bathroom': 'fa-bath',
      'view': 'fa-eye',
      'amenity': 'fa-star',
      'bed': 'fa-bed',
      'balcony': 'fa-sun',
      'other': 'fa-image'
    };
    return iconMap[imageType] || 'fa-image';
  }

  // Inject custom styles for SweetAlert buttons
  const style = document.createElement('style');
  style.textContent = `
    .swal-confirm-button {
      background-color: #d33 !important;
      color: white !important;
      border: none !important;
      padding: 12px 24px !important;
      border-radius: 8px !important;
      font-weight: 600 !important;
      font-size: 14px !important;
    }
    .swal-cancel-button {
      background-color: #6b7280 !important;
      color: white !important;
      border: none !important;
      padding: 12px 24px !important;
      border-radius: 8px !important;
      font-weight: 600 !important;
      font-size: 14px !important;
    }
  `;
  document.head.appendChild(style);

  // Data will be fetched from API on page load
  let roomsData = [];
  let propertiesData = [];
  let categoriesData = [];
  let roomImagesData = {};
  let csrfToken = "<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>";

  // Pagination state
  let currentPage = 1;
  let itemsPerPage = 10;
  let totalItems = 0;
  let totalPages = 0;

  // Fetch data from API on page load
  document.addEventListener('DOMContentLoaded', async function() {
    await fetchRoomsData();
  });

  async function fetchRoomsData() {
    try {
      // Fetch rooms with pagination
      const response = await fetch(`../api/rooms.php?action=list&page=${currentPage}&per_page=${itemsPerPage}`);
      const result = await response.json();
      if (result.success) {
        roomsData = result.data.data || [];
        const pagination = result.data.pagination || {};
        totalItems = pagination.total || 0;
        totalPages = pagination.total_pages || 0;

        // Populate roomImagesData from the rooms data
        roomImagesData = {};
        roomsData.forEach(room => {
          if (room.images && room.images.length > 0) {
            roomImagesData[room.room_id] = room.images;
          }
        });
      }

      // Fetch properties for dropdowns
      const propsResponse = await fetch('../api/properties.php?action=list');
      const propsResult = await propsResponse.json();
      if (propsResult.success) {
        propertiesData = propsResult.data.data || propsResult.data || [];
      }

      // Fetch categories for dropdowns
      const catsResponse = await fetch('../api/room_categories.php?action=list');
      const catsResult = await catsResponse.json();
      if (catsResult.success) {
        categoriesData = catsResult.data.data || catsResult.data || [];
      }

      renderRoomsTable();
      populateDropdowns();
    } catch (error) {
      console.error('Error fetching rooms:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to load rooms data',
        confirmButtonColor: '#3085d6'
      });
    }
  }

  function populateDropdowns() {
    // Populate property dropdowns
    const addPropertyDropdown = document.getElementById('addPropertyDropdown');
    const editPropertyDropdown = document.getElementById('editPropertyDropdown');

    if (addPropertyDropdown && propertiesData.length > 0) {
      addPropertyDropdown.innerHTML = '<option value="">Select Property</option>';
      propertiesData.forEach(property => {
        const option = document.createElement('option');
        option.value = property.property_id;
        option.textContent = `${property.property_name} (${property.property_code})`;
        addPropertyDropdown.appendChild(option);
      });
    }

    if (editPropertyDropdown && propertiesData.length > 0) {
      editPropertyDropdown.innerHTML = '<option value="">Select Property</option>';
      propertiesData.forEach(property => {
        const option = document.createElement('option');
        option.value = property.property_id;
        option.textContent = `${property.property_name} (${property.property_code})`;
        editPropertyDropdown.appendChild(option);
      });
    }

    // Populate category dropdowns
    const addCategoryDropdown = document.getElementById('addCategoryDropdown');
    const editCategoryDropdown = document.getElementById('editCategoryDropdown');

    if (addCategoryDropdown && categoriesData.length > 0) {
      addCategoryDropdown.innerHTML = '<option value="">Select Category</option>';
      categoriesData.forEach(category => {
        const option = document.createElement('option');
        option.value = category.category_id;
        option.textContent = `${category.category_name} (${category.category_code})`;
        addCategoryDropdown.appendChild(option);
      });
    }

    if (editCategoryDropdown && categoriesData.length > 0) {
      editCategoryDropdown.innerHTML = '<option value="">Select Category</option>';
      categoriesData.forEach(category => {
        const option = document.createElement('option');
        option.value = category.category_id;
        option.textContent = `${category.category_name} (${category.category_code})`;
        editCategoryDropdown.appendChild(option);
      });
    }
  }

  function renderRoomsTable() {
    const tbody = document.querySelector('#roomsTableBody');
    if (!tbody) return;

    if (roomsData.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">
            No rooms found. <a href="javascript:;" onclick="openAddRoomModal()" class="text-blue-500 font-semibold">Add your first room</a>.
          </td>
        </tr>
      `;
      renderPaginationControls();
      return;
    }

    tbody.innerHTML = roomsData.map((room) => {
      const property = propertiesData.find(p => p.property_id === room.property_id);
      const category = categoriesData.find(c => c.category_id === room.category_id);

      const statusColors = {
        'available': 'from-emerald-500 to-teal-400',
        'occupied': 'from-red-500 to-rose-400',
        'reserved': 'from-blue-500 to-cyan-400',
        'cleaning': 'from-yellow-500 to-orange-400',
        'maintenance': 'from-slate-600 to-slate-500',
        'out_of_order': 'from-red-600 to-red-400',
        'blocked': 'from-purple-500 to-violet-400'
      };
      const statusClass = statusColors[room.room_status] || 'from-gray-500 to-slate-400';

      return `
        <tr class="room-row">
          <td class="align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent" style="padding: 1rem 0.75rem;">
            <div class="flex items-center" style="gap: 0.75rem;">
              ${room.images && room.images.length > 0
                ? `<img src="../${room.images[0].image_url}" alt="${room.room_name || room.room_number}" class="object-cover rounded-lg" style="width: 2.5rem; height: 2.5rem; background-color: #e5e7eb;">`
                : `<div class="inline-flex items-center justify-center text-sm text-white transition-all duration-200 ease-in-out rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500" style="width: 2.5rem; height: 2.5rem;">
                    <i class="fas fa-door-open text-xs"></i>
                  </div>`
              }
              <div>
                <h6 class="mb-0 text-sm leading-normal dark:text-white" style="font-size: 0.875rem;">${room.room_name || room.room_number}</h6>
                <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400">${room.room_number}</p>
              </div>
            </div>
          </td>
          <td class="align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent" style="padding: 1rem 0.75rem;">
            <p class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80" style="font-size: 0.875rem;">${property ? property.property_name : 'N/A'}</p>
          </td>
          <td class="align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent" style="padding: 1rem 0.75rem;">
            <p class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80" style="font-size: 0.875rem;">${category ? category.category_name : 'N/A'}</p>
          </td>
          <td class="align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent" style="padding: 1rem 0.75rem;">
            <p class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80" style="font-size: 0.875rem;">${room.floor_number || 'None'}</p>
          </td>
          <td class="align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent" style="padding: 1rem 0.75rem;">
            <span class="bg-gradient-to-tl ${statusClass} inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white rounded-lg" style="padding: 0.5rem 1rem; font-size: 0.875rem;">${room.room_status || 'available'}</span>
          </td>
          <td class="align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent" style="padding: 1rem 0.75rem;">
            <div class="flex items-center gap-2">
              <button onclick="viewRoomDetails('${room.room_id}')" class="group relative inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-600 dark:text-blue-400 bg-white dark:bg-slate-700/50 border border-blue-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-700 dark:hover:text-blue-300 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Room" style="font-size: 0.875rem;">
                <i class="fas fa-eye mr-1.5 text-xs"></i>
                <span>View</span>
              </button>
              <button onclick="openEditRoomModal('${room.room_id}')" class="group relative inline-flex items-center px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Room" style="font-size: 0.875rem;">
                <i class="fas fa-edit mr-1.5 text-xs"></i>
                <span>Edit</span>
              </button>
              <button onclick="confirmDeleteRoom('${room.room_id}', '${room.room_number}')" class="group relative inline-flex items-center px-4 py-2 text-sm font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Room">
                <i class="fas fa-trash-alt mr-1.5 text-xs"></i>
                <span>Delete</span>
              </button>
            </div>
          </td>
        </tr>
      `;
    }).join('');

    renderPaginationControls();
  }

  function renderPaginationControls() {
    const container = document.getElementById('paginationControls');
    if (!container) return;

    const startItem = totalItems === 0 ? 0 : (currentPage - 1) * itemsPerPage + 1;
    const endItem = Math.min(currentPage * itemsPerPage, totalItems);

    container.innerHTML = `
      <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
        <label style="font-size: 0.875rem; color: #64748b; white-space: nowrap;">Show</label>
        <select id="itemsPerPage" onchange="changeItemsPerPage(this.value)" style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; outline: none;">
          <option value="5" ${itemsPerPage === 5 ? 'selected' : ''}>5</option>
          <option value="10" ${itemsPerPage === 10 ? 'selected' : ''}>10</option>
          <option value="25" ${itemsPerPage === 25 ? 'selected' : ''}>25</option>
          <option value="50" ${itemsPerPage === 50 ? 'selected' : ''}>50</option>
          <option value="100" ${itemsPerPage === 100 ? 'selected' : ''}>100</option>
        </select>
        <label style="font-size: 0.875rem; color: #64748b; white-space: nowrap;">entries</label>
      </div>
      <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
        <span style="font-size: 0.875rem; color: #64748b; white-space: nowrap;">
          Showing ${startItem} to ${endItem} of ${totalItems} entries
        </span>
        <button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} style="padding: 0.5rem 0.75rem; font-size: 0.875rem; font-weight: 600; color: #475569; background: white; border: 1px solid #e2e8f0; border-radius: 0.5rem; cursor: pointer; ${currentPage === 1 ? 'opacity: 0.5; cursor: not-allowed;' : ''}">
          <i class="fas fa-chevron-left"></i>
        </button>
        <span style="font-size: 0.875rem; font-weight: 600; color: #475569; white-space: nowrap;">Page ${currentPage} of ${totalPages}</span>
        <button onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} style="padding: 0.5rem 0.75rem; font-size: 0.875rem; font-weight: 600; color: #475569; background: white; border: 1px solid #e2e8f0; border-radius: 0.5rem; cursor: pointer; ${currentPage === totalPages ? 'opacity: 0.5; cursor: not-allowed;' : ''}">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
    `;
  }

  async function changePage(newPage) {
    if (newPage < 1 || newPage > totalPages) return;
    currentPage = newPage;
    await fetchRoomsData();
  }

  async function changeItemsPerPage(newPerPage) {
    itemsPerPage = parseInt(newPerPage);
    currentPage = 1;
    await fetchRoomsData();
  }

  async function viewRoomDetails(roomId) {
    const room = roomsData.find(r => r.room_id === roomId);
    if (!room) return;

    const modal = document.getElementById('roomDetailsModal');
    const modalName = document.getElementById('modalRoomName');
    const modalContent = document.getElementById('modalRoomContent');

    modalName.textContent = room.room_name || room.room_number;

    const property = propertiesData.find(p => p.property_id === room.property_id);
    const category = categoriesData.find(c => c.category_id === room.category_id);

    const statusColors = {
      'available':    { bg: '#d1fae5', text: '#065f46', dot: '#10b981' },
      'occupied':     { bg: '#fee2e2', text: '#991b1b', dot: '#ef4444' },
      'reserved':     { bg: '#dbeafe', text: '#1e40af', dot: '#3b82f6' },
      'cleaning':     { bg: '#fef9c3', text: '#854d0e', dot: '#eab308' },
      'maintenance':  { bg: '#f1f5f9', text: '#475569', dot: '#94a3b8' },
      'out_of_order': { bg: '#fecdd3', text: '#9f1239', dot: '#f43f5e' },
      'blocked':      { bg: '#ede9fe', text: '#5b21b6', dot: '#a855f7' }
    };
    const sc = statusColors[room.room_status] || statusColors['maintenance'];
    const statusLabel = (room.room_status || 'unknown').replace(/_/g, ' ');

    const fmt = (val) => val ? `PHP ${parseFloat(val).toLocaleString('en-PH', { minimumFractionDigits: 2 })}` : '<span style="color:var(--color-text-tertiary,#94a3b8);font-style:italic;">Using category default</span>';
    const fmtDate = (d) => d ? new Date(d).toLocaleString('en-PH', { dateStyle: 'medium', timeStyle: 'short' }) : '<span style="color:var(--color-text-tertiary,#94a3b8);font-style:italic;">Never</span>';
    const val = (v) => v || '<span style="color:var(--color-text-tertiary,#94a3b8);">—</span>';

    modalContent.innerHTML = `
      <style>
        .rm-section { margin-bottom: 1.5rem; }
        .rm-section-title { font-size: 11px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: #94a3b8; margin-bottom: 0.75rem; padding-bottom: 0.5rem; border-bottom: 0.5px solid #e2e8f0; }
        .rm-grid { display: grid; gap: 1rem; }
        .rm-grid-2 { grid-template-columns: 1fr 1fr; }
        .rm-grid-3 { grid-template-columns: 1fr 1fr 1fr; }
        .rm-field label { font-size: 11px; color: #94a3b8; display: block; margin-bottom: 3px; }
        .rm-field p { font-size: 14px; color: #1e293b; margin: 0; font-weight: 500; }
        .rm-price-card { background: #f8fafc; border: 0.5px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem; }
        .rm-price-card .rm-price-label { font-size: 11px; color: #94a3b8; margin-bottom: 4px; }
        .rm-price-card .rm-price-base { font-size: 15px; font-weight: 600; color: #1e293b; }
        .rm-price-card .rm-price-override { font-size: 12px; margin-top: 4px; color: #64748b; }
        .rm-price-override-active { color: #0f766e !important; font-weight: 600; }
        .rm-images-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 0.75rem; }
        .rm-img-thumb { position: relative; }
        .rm-img-thumb img { width: 100%; height: 90px; object-fit: cover; border-radius: 6px; display: block; cursor: pointer; }
        .rm-img-icon { position: absolute; top: 8px; right: 8px; background: rgba(0,0,0,0.6); color: #fff; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; backdrop-filter: blur(4px); }
        .rm-img-caption { font-size: 13px; color: #94a3b8; margin-top: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-transform: uppercase; font-weight: 600; }
        .rm-status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize; }
        .rm-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
        @media (max-width: 600px) {
          .rm-grid-2, .rm-grid-3 { grid-template-columns: 1fr; }
        }
      </style>

      ${(room.images && room.images.length > 0) ? `
      <div class="rm-section">
        <div class="rm-section-title">Photos</div>
        <div class="rm-images-grid">
          ${room.images.map(img => `
            <div class="rm-img-thumb">
              <img src="../${img.image_url}" alt="${img.caption || img.image_type}" onclick="window.open('../${img.image_url}', '_blank')" title="Click to open full size">
              <div class="rm-img-icon"><i class="fas ${getImageTypeIcon(img.image_type)}"></i></div>
              ${img.caption ? `<div class="rm-img-caption">${img.caption}</div>` : ''}
            </div>
          `).join('')}
        </div>
      </div>` : ''}

      <div class="rm-section">
        <div class="rm-section-title">Room identity</div>
        <div class="rm-grid rm-grid-2">
          <div class="rm-field"><label>Room number</label><p>${val(room.room_number)}</p></div>
          <div class="rm-field"><label>Room name</label><p>${val(room.room_name)}</p></div>
          <div class="rm-field"><label>Property</label><p>${val(property ? property.property_name : room.property_name)} <span style="font-size:11px;color:#94a3b8;font-weight:400;">${property ? (property.property_code ? '· ' + property.property_code : '') : ''}</span></p></div>
          <div class="rm-field"><label>Category</label><p>${val(category ? category.category_name : room.category_name)} <span style="font-size:11px;color:#94a3b8;font-weight:400;">${category ? (category.category_code ? '· ' + category.category_code : '') : ''}</span></p></div>
          <div class="rm-field"><label>Floor</label><p>${val(room.floor_number)}</p></div>
          <div class="rm-field"><label>Building block</label><p>${val(room.building_block)}</p></div>
        </div>
      </div>

      <div class="rm-section">
        <div class="rm-section-title">Status</div>
        <span class="rm-status-badge" style="background:${sc.bg};color:${sc.text};">
          <span class="rm-dot" style="background:${sc.dot};"></span>
          ${statusLabel}
        </span>
      </div>

      <div class="rm-section">
        <div class="rm-section-title">Pricing</div>
        <div class="rm-grid rm-grid-3">
          <div class="rm-price-card">
            <div class="rm-price-label">Per night</div>
            <div class="rm-price-base">PHP ${parseFloat(room.base_price_per_night || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</div>
            <div class="rm-price-override ${room.override_price_per_night ? 'rm-price-override-active' : ''}">
              ${room.override_price_per_night ? '↑ Override: PHP ' + parseFloat(room.override_price_per_night).toLocaleString('en-PH', { minimumFractionDigits: 2 }) : 'No override'}
            </div>
          </div>
          <div class="rm-price-card">
            <div class="rm-price-label">Per hour</div>
            <div class="rm-price-base">PHP ${parseFloat(room.base_price_per_hour || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</div>
            <div class="rm-price-override ${room.override_price_per_hour ? 'rm-price-override-active' : ''}">
              ${room.override_price_per_hour ? '↑ Override: PHP ' + parseFloat(room.override_price_per_hour).toLocaleString('en-PH', { minimumFractionDigits: 2 }) : 'No override'}
            </div>
          </div>
          <div class="rm-price-card">
            <div class="rm-price-label">Day use</div>
            <div class="rm-price-base">PHP ${parseFloat(room.base_price_day_use || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</div>
            <div class="rm-price-override ${room.override_price_day_use ? 'rm-price-override-active' : ''}">
              ${room.override_price_day_use ? '↑ Override: PHP ' + parseFloat(room.override_price_day_use).toLocaleString('en-PH', { minimumFractionDigits: 2 }) : 'No override'}
            </div>
          </div>
        </div>
      </div>

      <div class="rm-section">
        <div class="rm-section-title">Housekeeping</div>
        <div class="rm-grid rm-grid-2">
          <div class="rm-field"><label>Last cleaned</label><p style="font-weight:400;">${fmtDate(room.last_cleaned_at)}</p></div>
          <div class="rm-field"><label>Last inspected</label><p style="font-weight:400;">${fmtDate(room.last_inspected_at)}</p></div>
        </div>
      </div>

      ${room.internal_notes ? `
      <div class="rm-section">
        <div class="rm-section-title">Internal notes</div>
        <p style="font-size:14px;color:#475569;margin:0;line-height:1.6;padding:0.75rem 1rem;background:#f8fafc;border-left:3px solid #cbd5e1;border-radius:0 6px 6px 0;">${room.internal_notes}</p>
      </div>` : ''}

      <div class="rm-section" style="margin-bottom:0;">
        <div class="rm-section-title">Record info</div>
        <div class="rm-grid rm-grid-2">
          <div class="rm-field"><label>Created</label><p style="font-weight:400;">${fmtDate(room.created_at)}</p></div>
          <div class="rm-field"><label>Last updated</label><p style="font-weight:400;">${fmtDate(room.updated_at)}</p></div>
        </div>
      </div>
    `;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }

  function closeRoomDetailsModal() {
    const modal = document.getElementById('roomDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
  }

  // Add Room Form Submission (API-based)
  async function handleAddRoomSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // Generate UUID for room_id
    const room_id = generateUUID();

    // Prepare data for API
    const data = {
      csrf_token: formData.get('csrf_token'),
      room_id: room_id,
      property_id: formData.get('property_id'),
      category_id: formData.get('category_id'),
      room_number: formData.get('room_number'),
      room_name: formData.get('room_name') || null,
      floor_number: formData.get('floor_number') || null,
      building_block: formData.get('building_block') || null,
      room_status: formData.get('room_status') || 'available',
      override_price_per_night: formData.get('override_price_per_night') || null,
      override_price_per_hour: formData.get('override_price_per_hour') || null,
      override_price_day_use: formData.get('override_price_day_use') || null,
      internal_notes: formData.get('internal_notes') || null
    };

    try {
      // First create the room
      const response = await fetch('../api/rooms.php?action=create', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      });
      const result = await response.json();

      if (result.success) {
        // Upload images if any
        const imageFormData = new FormData();
        const imageFiles = form.querySelectorAll('input[type="file"][name*="room_images"]');

        let hasImages = false;
        imageFiles.forEach((input, index) => {
          console.log('Image input:', input, 'files:', input.files);
          if (input.files && input.files[0]) {
            hasImages = true;
            imageFormData.append('room_images[]', input.files[0]);

            // Find the corresponding type and caption inputs
            const parent = input.closest('.image-entry') || input.closest('div');
            const typeInput = parent ? parent.querySelector('select[name*="image_type"]') : null;
            const captionInput = parent ? parent.querySelector('input[name*="image_caption"]') : null;

            imageFormData.append('image_type[]', typeInput ? typeInput.value : 'interior');
            imageFormData.append('image_caption[]', captionInput ? captionInput.value : '');
          }
        });

        console.log('Has images:', hasImages);

        if (hasImages) {
          imageFormData.append('room_id', room_id);
          imageFormData.append('csrf_token', formData.get('csrf_token'));
          const imageResponse = await fetch('../api/rooms.php', {
            method: 'POST',
            body: imageFormData
          });
          const imageResult = await imageResponse.json();
          console.log('Image upload result:', imageResult);

          if (!imageResult.success) {
            console.error('Image upload failed:', imageResult.message);
          }
        }

        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: 'Room added successfully!',
          confirmButtonColor: '#3085d6'
        });

        closeAddRoomModal();
        await fetchRoomsData();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: result.message || 'Failed to add room',
          confirmButtonColor: '#3085d6'
        });
      }
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'An error occurred while adding the room',
        confirmButtonColor: '#3085d6'
      });
    }
  }

  // Edit Room Form Submission (API-based)
  async function handleEditRoomSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    const data = {
      csrf_token: formData.get('csrf_token'),
      room_id: formData.get('room_id'),
      property_id: formData.get('property_id'),
      category_id: formData.get('category_id'),
      room_number: formData.get('room_number'),
      room_name: formData.get('room_name') || null,
      floor_number: formData.get('floor_number') || null,
      building_block: formData.get('building_block') || null,
      room_status: formData.get('room_status') || 'available',
      override_price_per_night: formData.get('override_price_per_night') || null,
      override_price_per_hour: formData.get('override_price_per_hour') || null,
      override_price_day_use: formData.get('override_price_day_use') || null,
      internal_notes: formData.get('internal_notes') || null
    };

    try {
      // First update the room
      const response = await fetch('../api/rooms.php?action=update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      });
      const result = await response.json();

      if (result.success) {
        // Upload new images if any
        const imageFormData = new FormData();
        const imageFiles = form.querySelectorAll('input[type="file"][name*="room_images"]');

        let hasImages = false;
        imageFiles.forEach((input, index) => {
          console.log('Image input:', input, 'files:', input.files);
          if (input.files && input.files[0]) {
            hasImages = true;
            imageFormData.append('room_images[]', input.files[0]);

            // Find the corresponding type and caption inputs
            const parent = input.closest('.image-entry') || input.closest('div');
            const typeInput = parent ? parent.querySelector('select[name*="image_type"]') : null;
            const captionInput = parent ? parent.querySelector('input[name*="image_caption"]') : null;

            imageFormData.append('image_type[]', typeInput ? typeInput.value : 'interior');
            imageFormData.append('image_caption[]', captionInput ? captionInput.value : '');
          }
        });

        console.log('Has images:', hasImages);

        if (hasImages) {
          imageFormData.append('room_id', data.room_id);
          imageFormData.append('csrf_token', formData.get('csrf_token'));
          const imageResponse = await fetch('../api/rooms.php', {
            method: 'POST',
            body: imageFormData
          });
          const imageResult = await imageResponse.json();
          console.log('Image upload result:', imageResult);

          if (!imageResult.success) {
            console.error('Image upload failed:', imageResult.message);
          }
        }

        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: 'Room updated successfully!',
          timer: 2000,
          showConfirmButton: false
        });
        closeEditRoomModal();
        await fetchRoomsData();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: result.message || 'Failed to update room',
          confirmButtonColor: '#3085d6'
        });
      }
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'An error occurred while updating the room',
        confirmButtonColor: '#3085d6'
      });
    }
  }

  // Delete Room Function (API-based)
  async function confirmDeleteRoom(roomId, roomNumber) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete room "${roomNumber}"? This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'swal-confirm-button',
        cancelButton: 'swal-cancel-button'
      }
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          const response = await fetch('../api/rooms.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify({
              action: 'delete',
              room_id: roomId,
              csrf_token: csrfToken
            })
          });
          const result = await response.json();

          if (result.success) {
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Room deleted successfully!',
              timer: 2000,
              showConfirmButton: false
            });
            await fetchRoomsData();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: result.message || 'Failed to delete room',
              confirmButtonColor: '#3085d6'
            });
          }
        } catch (error) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while deleting the room',
            confirmButtonColor: '#3085d6'
          });
        }
      }
    });
  }
</script>

<!-- PAGE CONTENT -->
<!-- table 1 -->
<div class="flex flex-wrap -mx-3">
  <div class="flex-none w-full max-w-full px-3">
    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
        <h6 class="dark:text-white">Rooms</h6>
        <button onclick="openAddRoomModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
          <i class="fas fa-plus mr-1"></i> Add Room
        </button>
      </div>
      <div class="flex-auto px-0 pt-0 pb-2">
        <div class="p-0 overflow-x-auto">
          <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="roomsTable">
            <thead class="align-bottom">
              <tr>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Room</th>
                <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Category</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Floor Number</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Action</th>

              </tr>
            </thead>
            <tbody id="roomsTableBody">
              <!-- Table rows will be populated by JavaScript -->
            </tbody>
          </table>
        </div>
      </div>
      <!-- Pagination Controls -->
      <div class="px-6 py-4 border-t border-gray-200 dark:border-white/10 flex flex-col sm:flex-row justify-between items-center gap-4" id="paginationControls">
        <!-- Pagination will be rendered by JavaScript -->
      </div>
    </div>
  </div>
</div>

<!-- Room Details Modal -->
<div id="roomDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeRoomDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
  <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
    <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
      <h3 class="text-xl font-bold dark:text-white" id="modalRoomName" style="font-size: 1.25rem; font-weight: bold; margin: 0;">Room Details</h3>
      <button onclick="closeRoomDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
        <i class="fas fa-times text-xl"></i>
      </button>
    </div>
    <div class="p-6" id="modalRoomContent" style="padding: 1.5rem;">
      <!-- Content will be populated by JavaScript -->
    </div>
  </div>
</div>

<!-- Add Room Modal -->
<div id="addRoomModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddRoomModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
  <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
    <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
      <h3 class="text-xl font-bold dark:text-white">Add New Room</h3>
      <button onclick="closeAddRoomModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
        <i class="fas fa-times text-xl"></i>
      </button>
    </div>
    <form id="addRoomForm" class="p-6" onsubmit="handleAddRoomSubmit(event)" style="padding: 1.5rem;">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <!-- Property Selection -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2" style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Property *</label>
          <select name="property_id" id="addPropertyDropdown" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;">
            <option value="">Select Property</option>
          </select>
        </div>

        <!-- Category Selection -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2" style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Room Category *</label>
          <select name="category_id" id="addCategoryDropdown" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;">
            <option value="">Select Category</option>
          </select>
        </div>

        <!-- Room Number -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2" style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Room Number *</label>
          <input type="text" name="room_number" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="e.g., 101, A-02, Cottage-3">
        </div>

        <!-- Room Name -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2" style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Room Name</label>
          <input type="text" name="room_name" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="e.g., Sunset Villa, Lagoon Cottage">
        </div>

        <!-- Floor Number -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2" style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Floor Number</label>
          <input type="number" name="floor_number" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="e.g., 1, 2, 3">
        </div>

        <!-- Building Block -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2" style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Building Block</label>
          <input type="text" name="building_block" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="e.g., Building A, East Wing">
        </div>

        <!-- Room Status -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2" style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Room Status *</label>
          <select name="room_status" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;">
            <option value="available">Available</option>
            <option value="occupied">Occupied</option>
            <option value="reserved">Reserved</option>
            <option value="cleaning">Cleaning</option>
            <option value="maintenance">Maintenance</option>
            <option value="out_of_order">Out of Order</option>
            <option value="blocked">Blocked</option>
          </select>
        </div>

        <!-- Price Override Per Night -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2" style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Override Price Per Night (PHP)</label>
          <input type="number" step="0.01" name="override_price_per_night" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="Leave empty to use category price">
        </div>

        <!-- Price Override Per Hour -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2" style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Override Price Per Hour (PHP)</label>
          <input type="number" step="0.01" name="override_price_per_hour" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="Leave empty to use category price">
        </div>

        <!-- Price Override Day Use -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2" style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Override Price Day Use (PHP)</label>
          <input type="number" step="0.01" name="override_price_day_use" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="Leave empty to use category price">
        </div>

        <!-- Internal Notes -->
        <div style="grid-column: 1 / -1;">
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2" style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Internal Notes</label>
          <textarea name="internal_notes" rows="3" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="Any additional notes about this room..."></textarea>
        </div>

        <!-- Image Upload Section -->
        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Images</label>
          <div id="imageUploadContainer">
            <div class="image-entry mb-4 p-4 border border-gray-200 rounded-lg dark:border-white/20">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                  <label class="block text-xs text-slate-500 dark:text-white/60 mb-2">Image File</label>
                  <input type="file" name="room_images[]" accept="image/*" class="w-full text-sm text-slate-500 dark:text-white/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-white">
                </div>
                <div>
                  <label class="block text-xs text-slate-500 dark:text-white/60 mb-2">Image Type</label>
                  <select name="image_type[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="interior">Interior</option>
                    <option value="exterior">Exterior</option>
                    <option value="bathroom">Bathroom</option>
                    <option value="view">View</option>
                    <option value="amenity">Amenity</option>
                    <option value="bed">Bed</option>
                    <option value="balcony">Balcony</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs text-slate-500 dark:text-white/60 mb-2">Caption</label>
                  <input type="text" name="image_caption[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Image caption">
                </div>
              </div>
            </div>
          </div>
          <button type="button" onclick="addImageField()" class="mt-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
            <i class="fas fa-plus mr-1"></i> Add Another Image
          </button>
        </div>
      </div>

      <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-white/10">
        <button type="button" onclick="closeAddRoomModal()" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600">
          Cancel
        </button>
        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:shadow-md transition-all">
          <i class="fas fa-save mr-1"></i> Save Room
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Room Modal -->
<div id="editRoomModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditRoomModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
  <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
    <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
      <h3 class="text-xl font-bold dark:text-white">Edit Room</h3>
      <button onclick="closeEditRoomModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
        <i class="fas fa-times text-xl"></i>
      </button>
    </div>
    <form id="editRoomForm" class="p-6" onsubmit="handleEditRoomSubmit(event)">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
      <input type="hidden" name="room_id" id="edit_room_id">

      <!-- Basic Information -->
      <div style="margin-bottom: 1.5rem;">
        <h4 style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 1rem; text-transform: uppercase;">Basic Information</h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Property *</label>
            <select name="property_id" id="editPropertyDropdown" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;">
              <option value="">Select Property</option>
            </select>
          </div>
          <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Room Category *</label>
            <select name="category_id" id="editCategoryDropdown" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;">
              <option value="">Select Category</option>
            </select>
          </div>
          <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Room Number *</label>
            <input type="text" name="room_number" id="edit_room_number" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="e.g., 101, A-02, Cottage-3">
          </div>
          <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Room Name</label>
            <input type="text" name="room_name" id="edit_room_name" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="e.g., Sunset Villa, Lagoon Cottage">
          </div>
        </div>
      </div>

      <!-- Location -->
      <div style="margin-bottom: 1.5rem;">
        <h4 style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 1rem; text-transform: uppercase;">Location</h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Floor Number</label>
            <input type="number" name="floor_number" id="edit_floor_number" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="e.g., 1, 2, 3">
          </div>
          <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Building Block</label>
            <input type="text" name="building_block" id="edit_building_block" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="e.g., Building A, East Wing">
          </div>
        </div>
      </div>

      <!-- Status -->
      <div style="margin-bottom: 1.5rem;">
        <h4 style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 1rem; text-transform: uppercase;">Status</h4>
        <div>
          <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Room Status *</label>
          <select name="room_status" id="edit_room_status" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;">
            <option value="available">Available</option>
            <option value="occupied">Occupied</option>
            <option value="reserved">Reserved</option>
            <option value="cleaning">Cleaning</option>
            <option value="maintenance">Maintenance</option>
            <option value="out_of_order">Out of Order</option>
            <option value="blocked">Blocked</option>
          </select>
        </div>
      </div>

      <!-- Pricing -->
      <div style="margin-bottom: 1.5rem;">
        <h4 style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 1rem; text-transform: uppercase;">Pricing Overrides</h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
          <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Per Night (PHP)</label>
            <input type="number" step="0.01" name="override_price_per_night" id="edit_override_price_per_night" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="Leave empty to use category price">
          </div>
          <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Per Hour (PHP)</label>
            <input type="number" step="0.01" name="override_price_per_hour" id="edit_override_price_per_hour" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="Leave empty to use category price">
          </div>
          <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Day Use (PHP)</label>
            <input type="number" step="0.01" name="override_price_day_use" id="edit_override_price_day_use" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="Leave empty to use category price">
          </div>
        </div>
      </div>

      <!-- Notes -->
      <div style="margin-bottom: 1.5rem;">
        <h4 style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 1rem; text-transform: uppercase;">Notes</h4>
        <div>
          <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Internal Notes</label>
          <textarea name="internal_notes" id="edit_internal_notes" rows="3" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="Any additional notes about this room..."></textarea>
        </div>
      </div>

      <!-- Images -->
      <div style="margin-bottom: 1.5rem;">
        <h4 style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 1rem; text-transform: uppercase;">Images</h4>

        <!-- Existing Images -->
        <div style="margin-bottom: 1rem;">
          <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Existing Images</label>
          <div id="existingImagesContainer" style="margin-bottom: 1rem;">
            <!-- Existing images will be loaded here -->
          </div>
        </div>

        <!-- New Images -->
        <div>
          <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Add New Images</label>
          <div id="editImageUploadContainer">
            <div style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem;">
              <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                <div>
                  <label style="display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 0.25rem;">Image File</label>
                  <input type="file" name="room_images[]" accept="image/*" class="w-full text-sm text-slate-500 dark:text-white/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-white">
                </div>
                <div>
                  <label style="display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 0.25rem;">Image Type</label>
                  <select name="image_type[]" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;">
                    <option value="interior">Interior</option>
                    <option value="exterior">Exterior</option>
                    <option value="bathroom">Bathroom</option>
                    <option value="view">View</option>
                    <option value="amenity">Amenity</option>
                    <option value="bed">Bed</option>
                    <option value="balcony">Balcony</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label style="display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 0.25rem;">Caption</label>
                  <input type="text" name="image_caption[]" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #1f2937; outline: none;" placeholder="Image caption">
                </div>
              </div>
            </div>
          </div>
          <button type="button" onclick="addEditImageField()" style="margin-top: 0.5rem; font-size: 0.875rem; color: #2563eb; background: none; border: none; cursor: pointer;">
            <i class="fas fa-plus" style="margin-right: 0.25rem;"></i> Add Another Image
          </button>
        </div>
      </div>

      <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-white/10">
        <button type="button" onclick="closeEditRoomModal()" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600">
          Cancel
        </button>
        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:shadow-md transition-all">
          <i class="fas fa-save mr-1"></i> Update Room
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function deleteRoom(roomId, roomName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete the room "${roomName}"? This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'swal-confirm-button',
        cancelButton: 'swal-cancel-button'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.location.href;

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete_room';

        const roomIdInput = document.createElement('input');
        roomIdInput.type = 'hidden';
        roomIdInput.name = 'room_id';
        roomIdInput.value = roomId;

        form.appendChild(actionInput);
        form.appendChild(roomIdInput);
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  function openAddRoomModal() {
    const modal = document.getElementById('addRoomModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeAddRoomModal() {
    const modal = document.getElementById('addRoomModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('addRoomForm').reset();
  }

  function addImageField() {
    const container = document.getElementById('imageUploadContainer');
    const imageEntry = document.createElement('div');
    imageEntry.className = 'image-entry mb-4 p-4 border border-gray-200 rounded-lg dark:border-white/20 relative';
    imageEntry.innerHTML = `
        <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
          <i class="fas fa-times"></i>
        </button>
        <div class="grid grid-cols-1 md:grid-cols-3" style="gap: 1.5rem;">
          <div>
            <label style="display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem;">Image File</label>
            <input type="file" name="room_images[]" accept="image/*" class="w-full text-sm text-slate-500 dark:text-white/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-white">
          </div>
          <div>
            <label style="display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem;">Image Type</label>
            <select name="image_type[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="interior">Interior</option>
              <option value="exterior">Exterior</option>
              <option value="bathroom">Bathroom</option>
              <option value="view">View</option>
              <option value="amenity">Amenity</option>
              <option value="bed">Bed</option>
              <option value="balcony">Balcony</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div>
            <label style="display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem;">Caption</label>
            <input type="text" name="image_caption[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Image caption">
          </div>
        </div>
      `;
    container.appendChild(imageEntry);
  }

  function openEditRoomModal(roomId) {
    const room = roomsData.find(r => r.room_id === roomId);
    if (!room) return;

    // Populate form fields with existing data
    document.getElementById('edit_room_id').value = room.room_id;
    document.getElementById('editPropertyDropdown').value = room.property_id;
    document.getElementById('editCategoryDropdown').value = room.category_id;
    document.getElementById('edit_room_number').value = room.room_number;
    document.getElementById('edit_room_name').value = room.room_name || '';
    document.getElementById('edit_floor_number').value = room.floor_number || '';
    document.getElementById('edit_building_block').value = room.building_block || '';
    document.getElementById('edit_room_status').value = room.room_status;
    document.getElementById('edit_override_price_per_night').value = room.override_price_per_night || '';
    document.getElementById('edit_override_price_per_hour').value = room.override_price_per_hour || '';
    document.getElementById('edit_override_price_day_use').value = room.override_price_day_use || '';
    document.getElementById('edit_internal_notes').value = room.internal_notes || '';

    // Load existing images
    loadExistingImages(roomId);

    const modal = document.getElementById('editRoomModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeEditRoomModal() {
    const modal = document.getElementById('editRoomModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('editRoomForm').reset();
    document.getElementById('existingImagesContainer').innerHTML = '';
    document.getElementById('editImageUploadContainer').innerHTML = `
        <div class="image-entry mb-4 p-4 border border-gray-200 rounded-lg dark:border-white/20">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image File</label>
              <input type="file" name="room_images[]" accept="image/*" class="w-full text-sm text-slate-500 dark:text-white/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-white">
            </div>
            <div>
              <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image Type</label>
              <select name="image_type[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="interior">Interior</option>
                <option value="exterior">Exterior</option>
                <option value="bathroom">Bathroom</option>
                <option value="view">View</option>
                <option value="amenity">Amenity</option>
                <option value="bed">Bed</option>
                <option value="balcony">Balcony</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Caption</label>
              <input type="text" name="image_caption[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Image caption">
            </div>
          </div>
        </div>
      `;
  }

  function addEditImageField() {
    const container = document.getElementById('editImageUploadContainer');
    const imageEntry = document.createElement('div');
    imageEntry.className = 'image-entry mb-4 p-4 border border-gray-200 rounded-lg dark:border-white/20 relative';
    imageEntry.innerHTML = `
        <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
          <i class="fas fa-times"></i>
        </button>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image File</label>
            <input type="file" name="room_images[]" accept="image/*" class="w-full text-sm text-slate-500 dark:text-white/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-white">
          </div>
          <div>
            <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image Type</label>
            <select name="image_type[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="interior">Interior</option>
              <option value="exterior">Exterior</option>
              <option value="bathroom">Bathroom</option>
              <option value="view">View</option>
              <option value="amenity">Amenity</option>
              <option value="bed">Bed</option>
              <option value="balcony">Balcony</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div>
            <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Caption</label>
            <input type="text" name="image_caption[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Image caption">
          </div>
        </div>
      `;
    container.appendChild(imageEntry);
  }

  function loadExistingImages(roomId) {
    const container = document.getElementById('existingImagesContainer');
    const images = roomImagesData[roomId] || [];

    if (images.length === 0) {
      container.innerHTML = '<p class="text-sm text-slate-500">No images uploaded yet.</p>';
      return;
    }

    container.innerHTML = images.map(img => `
        <div class="relative inline-block mr-2 mb-2">
          <img src="../${img.image_url}" alt="${img.caption || ''}" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
          <button type="button" onclick="markImageForDeletion('${img.image_id}', this)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
            <i class="fas fa-times"></i>
          </button>
          <input type="hidden" name="delete_images[]" id="delete_img_${img.image_id}" value="">
          <p class="text-xs text-slate-500 mt-1 w-24 truncate">${img.caption || img.image_type}</p>
        </div>
      `).join('');
  }

  function markImageForDeletion(imageId, button) {
    const input = document.getElementById(`delete_img_${imageId}`);
    if (input.value === imageId) {
      input.value = '';
      button.classList.remove('bg-red-600');
      button.classList.add('bg-red-500');
      button.parentElement.classList.remove('opacity-50');
    } else {
      input.value = imageId;
      button.classList.remove('bg-red-500');
      button.classList.add('bg-red-600');
      button.parentElement.classList.add('opacity-50');
    }
  }

  // Filter categories based on selected property
  document.addEventListener('DOMContentLoaded', function() {
    const propertySelect = document.querySelector('select[name="property_id"]');
    const categorySelect = document.querySelector('select[name="category_id"]');

    if (propertySelect && categorySelect) {
      propertySelect.addEventListener('change', function() {
        const selectedPropertyId = this.value;
        const categoryOptions = categorySelect.querySelectorAll('option[data-property-id]');

        categoryOptions.forEach(option => {
          if (option.dataset.propertyId === selectedPropertyId || selectedPropertyId === '') {
            option.style.display = '';
          } else {
            option.style.display = 'none';
          }
        });

        // Reset category selection if current selection is not valid
        if (selectedPropertyId !== '' && categorySelect.value !== '') {
          const selectedOption = categorySelect.querySelector('option[value="' + categorySelect.value + '"]');
          if (selectedOption && selectedOption.dataset.propertyId !== selectedPropertyId) {
            categorySelect.value = '';
          }
        }
      });
    }
  });

  // Show success/error messages
  window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);

    // Show error message if present
    <?php if (isset($error_message)): ?>
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: <?php echo json_encode($error_message); ?>,
        confirmButtonColor: '#3085d6'
      });
    <?php endif; ?>

    if (urlParams.get('success') === 'room_added') {
      const newUrl = window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);

      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Room added successfully!',
        timer: 3000,
        showConfirmButton: false
      });
    }

    if (urlParams.get('success') === 'room_deleted') {
      const newUrl = window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);

      Swal.fire({
        icon: 'success',
        title: 'Deleted!',
        text: 'Room deleted successfully!',
        timer: 3000,
        showConfirmButton: false
      });
    }

    if (urlParams.get('success') === 'room_updated') {
      const newUrl = window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);

      Swal.fire({
        icon: 'success',
        title: 'Updated!',
        text: 'Room updated successfully!',
        timer: 3000,
        showConfirmButton: false
      });
    }
  });

  // Filter table function
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('roomsTable');
    const rows = table.getElementsByClassName('room-row');

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
</script>