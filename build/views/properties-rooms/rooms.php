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

<style>
  :root {
    --color-background-primary: #ffffff;
    --color-background-secondary: #f8fafc;
    --color-background-tertiary: #f1f5f9;
    --color-border-primary: #185fa5;
    --color-border-secondary: #e2e8f0;
    --color-border-tertiary: #cbd5e1;
    --color-text-primary: #1e293b;
    --color-text-secondary: #64748b;
    --color-text-tertiary: #94a3b8;
    --border-radius-md: 8px;
    --border-radius-lg: 12px;
  }
  .rm-page { padding: 24px; max-width: 100%; margin: 0 auto; overflow-x: hidden; }
  .rm-card {
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: var(--border-radius-lg);
    margin-bottom: 0;
  }
  .rm-card-header {
    padding: 16px 20px;
    border-bottom: 0.5px solid var(--color-border-tertiary);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }
  .rm-card-title { font-size: 16px; font-weight: 500; }
  .rm-toolbar { display: flex; align-items: center; gap: 8px; flex-wrap: nowrap; }
  .rm-search-wrap { position: relative; }
  .rm-search-wrap i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--color-text-secondary); font-size: 13px; pointer-events: none; }
  .rm-search-input {
    padding: 7px 12px 7px 32px;
    font-size: 13px;
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    background: var(--color-background-secondary);
    color: var(--color-text-primary);
    outline: none;
    width: 180px;
  }
  .rm-search-input:focus { border-color: var(--color-border-primary); background: var(--color-background-primary); }
  .rm-filter-select {
    padding: 7px 12px;
    font-size: 13px;
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    background: var(--color-background-secondary);
    color: var(--color-text-primary);
    outline: none; cursor: pointer;
  }
  .rm-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; font-size: 13px; font-weight: 500;
    border-radius: var(--border-radius-md);
    border: 0.5px solid var(--color-border-secondary);
    background: var(--color-background-primary);
    color: var(--color-text-primary);
    cursor: pointer; transition: background 0.15s; white-space: nowrap;
  }
  .rm-btn:hover { background: var(--color-background-secondary); }
  .rm-btn-primary { background: #185fa5; color: #fff; border-color: #185fa5; }
  .rm-btn-primary:hover { background: #0c447c; border-color: #0c447c; }
  .rm-btn i { font-size: 13px; }
  .rm-table-wrap { overflow-x: auto; }
  .rm-table { width: 100%; border-collapse: collapse; font-size: 13px; table-layout: fixed; }
  .rm-table thead th {
    padding: 10px 16px; text-align: left;
    font-size: 11px; font-weight: 500; color: var(--color-text-secondary);
    text-transform: uppercase; letter-spacing: 0.06em;
    border-bottom: 0.5px solid var(--color-border-tertiary); white-space: nowrap;
  }
  .rm-table thead th:nth-child(1) { width: 28%; }
  .rm-table thead th:nth-child(2) { width: 20%; }
  .rm-table thead th:nth-child(3) { width: 18%; }
  .rm-table thead th:nth-child(4) { width: 10%; }
  .rm-table thead th:nth-child(5) { width: 11%; text-align: center; }
  .rm-table thead th:nth-child(6) { width: 13%; text-align: right; }
  .rm-table tbody tr { border-bottom: 0.5px solid var(--color-border-tertiary); transition: background 0.1s; }
  .rm-table tbody tr:last-child { border-bottom: none; }
  .rm-table tbody tr:hover { background: var(--color-background-secondary); }
  .rm-table td { padding: 12px 16px; vertical-align: middle; }
  .rm-avatar {
    width: 40px; height: 40px;
    border-radius: var(--border-radius-md);
    background: #e6f1fb;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; overflow: hidden;
  }
  .rm-avatar img { width: 100%; height: 100%; object-fit: cover; }
  .rm-avatar i { font-size: 16px; color: #185fa5; }
  .rm-name { font-size: 13px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; }
  .rm-code { font-size: 11px; color: var(--color-text-secondary); margin-top: 1px; }
  .rm-text { font-size: 13px; color: var(--color-text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .rm-text-secondary { font-size: 12px; color: var(--color-text-secondary); }
  .rm-status-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 9999px; font-size: 11px; font-weight: 500; text-transform: capitalize;
  }
  .rm-status-badge::before { content: ""; width: 5px; height: 5px; border-radius: 50%; background: currentColor; display: inline-block; }
  .rm-status-available  { background: #eaf3de; color: #27500a; }
  .rm-status-occupied   { background: #fee2e2; color: #991b1b; }
  .rm-status-reserved   { background: #dbeafe; color: #1e40af; }
  .rm-status-cleaning   { background: #fef9c3; color: #854d0e; }
  .rm-status-maintenance{ background: #f1f5f9; color: #475569; }
  .rm-status-out_of_order{ background: #fecdd3; color: #9f1239; }
  .rm-status-blocked    { background: #ede9fe; color: #5b21b6; }
  .rm-actions { display: flex; align-items: center; gap: 5px; justify-content: flex-end; }
  .rm-icon-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px;
    border-radius: var(--border-radius-md);
    border: 0.5px solid var(--color-border-tertiary);
    background: transparent; color: var(--color-text-secondary);
    cursor: pointer; transition: all 0.15s; font-size: 13px;
  }
  .rm-icon-btn:hover { background: var(--color-background-secondary); color: var(--color-text-primary); }
  .rm-icon-btn.view:hover  { background: #e6f1fb; color: #0c447c; border-color: #85b7eb; }
  .rm-icon-btn.edit:hover  { background: #faeeda; color: #633806; border-color: #ef9f27; }
  .rm-icon-btn.del:hover   { background: #fcebeb; color: #791f1f; border-color: #f09595; }
  .rm-empty-state { padding: 48px 24px; text-align: center; color: var(--color-text-secondary); font-size: 13px; }
  /* Pagination */
  .rm-pagination {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 16px; border-top: 0.5px solid var(--color-border-tertiary);
    flex-wrap: wrap; gap: 10px;
  }
  .rm-pag-info { font-size: 12px; color: var(--color-text-secondary); white-space: nowrap; }
  .rm-per-page-wrap { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--color-text-secondary); }
  .rm-per-page-select {
    padding: 4px 8px; font-size: 12px;
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    background: var(--color-background-secondary);
    color: var(--color-text-primary); outline: none; cursor: pointer;
  }
  .rm-pag-controls { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }
  .rm-pag-btn {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 30px; height: 30px; padding: 0 6px;
    font-size: 12px; border-radius: var(--border-radius-md);
    border: 0.5px solid var(--color-border-secondary);
    background: var(--color-background-primary); color: var(--color-text-secondary);
    cursor: pointer; transition: all 0.15s;
  }
  .rm-pag-btn:hover:not(:disabled) { background: var(--color-background-secondary); color: var(--color-text-primary); }
  .rm-pag-btn.active { background: #185fa5; color: #fff; border-color: #185fa5; }
  .rm-pag-btn:disabled { opacity: 0.4; cursor: not-allowed; }
  /* Modal */
  .rm-modal-backdrop {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.45); z-index: 9000;
    align-items: flex-start; justify-content: center;
    padding: 20px; overflow-y: auto;
  }
  .rm-modal-backdrop.open { display: flex; }
  .rm-modal {
    background: var(--color-background-primary);
    border-radius: 16px;
    border: 0.5px solid var(--color-border-tertiary);
    width: 100%; max-width: 860px; margin: auto;
  }
  .rm-modal-header {
    padding: 18px 24px; border-bottom: 0.5px solid var(--color-border-tertiary);
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; background: var(--color-background-primary);
    z-index: 1; border-radius: 16px 16px 0 0;
  }
  .rm-modal-title { font-size: 15px; font-weight: 600; }
  .rm-modal-close {
    width: 30px; height: 30px; border-radius: var(--border-radius-md);
    border: 0.5px solid var(--color-border-tertiary);
    background: transparent; color: var(--color-text-secondary);
    cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 14px;
  }
  .rm-modal-close:hover { background: var(--color-background-secondary); }
  .rm-modal-body { padding: 20px 24px; }
  .rm-modal-footer {
    padding: 14px 24px; border-top: 0.5px solid var(--color-border-tertiary);
    display: flex; justify-content: flex-end; gap: 8px;
    position: sticky; bottom: 0; background: var(--color-background-primary);
    border-radius: 0 0 16px 16px;
  }
  /* Form inside modal */
  .rm-section-label { font-size: 11px; font-weight: 600; letter-spacing: 0.07em; text-transform: uppercase; color: var(--color-text-secondary); margin: 0 0 12px; padding-bottom: 6px; border-bottom: 0.5px solid var(--color-border-tertiary); }
  .rm-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px; }
  .rm-form-group { display: flex; flex-direction: column; gap: 5px; }
  .rm-form-group.full { grid-column: 1 / -1; }
  .rm-form-group label { font-size: 12px; font-weight: 500; color: var(--color-text-secondary); }
  .rm-form-group input,
  .rm-form-group select,
  .rm-form-group textarea {
    padding: 8px 12px; font-size: 13px;
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    background: var(--color-background-secondary);
    color: var(--color-text-primary); outline: none;
    transition: border-color 0.15s;
  }
  .rm-form-group input:focus,
  .rm-form-group select:focus,
  .rm-form-group textarea:focus { border-color: var(--color-border-primary); background: var(--color-background-primary); }
  .rm-form-group textarea { resize: vertical; }
  /* Image entry */
  .rm-image-entry {
    display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;
    padding: 12px; border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md); margin-bottom: 10px; position: relative;
  }
  .rm-image-entry-remove {
    position: absolute; top: 8px; right: 8px;
    width: 22px; height: 22px; border-radius: 50%;
    background: #fee2e2; color: #991b1b; border: none;
    cursor: pointer; font-size: 11px; display: flex; align-items: center; justify-content: center;
  }
  .rm-add-image-btn { font-size: 12px; color: #185fa5; background: none; border: none; cursor: pointer; padding: 4px 0; display: inline-flex; align-items: center; gap: 4px; }
  .rm-existing-images { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }
  .rm-existing-img { position: relative; }
  .rm-existing-img img { width: 80px; height: 80px; object-fit: cover; border-radius: var(--border-radius-md); border: 0.5px solid var(--color-border-secondary); display: block; }
  .rm-existing-img-del {
    position: absolute; top: 4px; right: 4px;
    width: 20px; height: 20px; border-radius: 50%;
    background: rgba(220,38,38,0.85); color: #fff; border: none;
    cursor: pointer; font-size: 10px; display: flex; align-items: center; justify-content: center;
  }
  .rm-existing-img p { font-size: 10px; color: var(--color-text-secondary); margin-top: 3px; width: 80px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-align: center; }
  /* Toast */
  .rm-toast {
    position: fixed; bottom: 24px; right: 24px;
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    padding: 11px 16px; font-size: 13px;
    display: flex; align-items: center; gap: 8px;
    z-index: 9999; transform: translateY(80px); opacity: 0; transition: all 0.25s;
  }
  .rm-toast.show { transform: none; opacity: 1; }
  .rm-toast.success { border-color: #9fe1cb; color: #085041; }
  .rm-toast.error   { border-color: #f7c1c1; color: #791f1f; }

  /* Responsive */
  @media (max-width: 1024px) {
    .rm-table thead th:nth-child(4), .rm-table tbody td:nth-child(4) { display: none; }
    .rm-table thead th:nth-child(1) { width: 32%; }
    .rm-table thead th:nth-child(2) { width: 24%; }
    .rm-table thead th:nth-child(3) { width: 22%; }
  }
  @media (max-width: 768px) {
    .rm-page { padding: 16px; }
    .rm-card-header { flex-direction: column; align-items: stretch; gap: 10px; }
    .rm-toolbar { width: 100%; flex-wrap: wrap; }
    .rm-search-wrap { flex: 1 1 auto; } .rm-search-input { width: 100%; }
    .rm-filter-select { flex: 1 1 auto; }
    .rm-table thead th:nth-child(3), .rm-table tbody td:nth-child(3) { display: none; }
    .rm-table thead th:nth-child(1) { width: 42%; }
    .rm-table thead th:nth-child(2) { width: 25%; }
    .rm-table thead th:nth-child(5) { width: 18%; }
    .rm-table thead th:nth-child(6) { width: 15%; }
    .rm-modal-backdrop { padding: 0; align-items: flex-end; }
    .rm-modal { border-radius: 16px 16px 0 0; max-width: 100%; max-height: 95vh; overflow-y: auto; margin: 0; }
    .rm-modal-header { border-radius: 16px 16px 0 0; } .rm-modal-footer { border-radius: 0; }
    .rm-form-grid { grid-template-columns: 1fr; }
    .rm-form-group.full { grid-column: 1; }
    .rm-image-entry { grid-template-columns: 1fr; }
    .rm-pagination { flex-direction: column; align-items: flex-start; gap: 8px; }
  }
  @media (max-width: 480px) {
    .rm-page { padding: 12px; }
    .rm-table thead th:nth-child(2), .rm-table tbody td:nth-child(2) { display: none; }
    .rm-table thead th:nth-child(1) { width: 50%; }
    .rm-table thead th:nth-child(5) { width: 20%; }
    .rm-table thead th:nth-child(6) { width: 30%; }
    .rm-name { max-width: 100px; font-size: 12px; }
    .rm-code { display: none; }
    .rm-avatar { width: 34px; height: 34px; }
    .rm-icon-btn { width: 26px; height: 26px; font-size: 12px; }
    .rm-actions { gap: 3px; }
  }
  @media (hover: none) and (pointer: coarse) {
    .rm-icon-btn { width: 36px; height: 36px; }
    .rm-pag-btn { min-width: 36px; height: 36px; }
    .rm-btn { padding: 9px 16px; }
    .rm-form-group input, .rm-form-group select, .rm-form-group textarea { font-size: 16px; }
  }
</style>

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
      showRmToast('Failed to load rooms data', 'error');
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

  function showRmToast(message, type = 'success') {
    const toast = document.getElementById('rmToast');
    toast.textContent = message;
    toast.className = `rm-toast ${type}`;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
  }

  function renderRoomsTable() {
    const tbody = document.querySelector('#roomsTableBody');
    if (!tbody) return;

    if (roomsData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="6"><div class="rm-empty-state">No rooms found. <a href="javascript:;" onclick="openAddRoomModal()" style="color:#185fa5;font-weight:500;">Add your first room</a>.</div></td></tr>`;
      renderPaginationControls();
      return;
    }

    tbody.innerHTML = roomsData.map((room) => {
      const property = propertiesData.find(p => p.property_id === room.property_id);
      const category = categoriesData.find(c => c.category_id === room.category_id);
      const status = room.room_status || 'available';
      const statusLabel = status.replace(/_/g, ' ');
      const avatarHtml = (room.images && room.images.length > 0)
        ? `<div class="rm-avatar"><img src="../${room.images[0].image_url}" alt="${room.room_name || room.room_number}"></div>`
        : `<div class="rm-avatar"><i class="fas fa-door-open"></i></div>`;

      return `
        <tr class="room-row">
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              ${avatarHtml}
              <div>
                <div class="rm-name">${room.room_name || room.room_number}</div>
                <div class="rm-code">${room.room_number}</div>
              </div>
            </div>
          </td>
          <td><span class="rm-text">${property ? property.property_name : '—'}</span></td>
          <td><span class="rm-text">${category ? category.category_name : '—'}</span></td>
          <td><span class="rm-text">${room.floor_number || '—'}</span></td>
          <td style="text-align:center;"><span class="rm-status-badge rm-status-${status}">${statusLabel}</span></td>
          <td>
            <div class="rm-actions">
              <button class="rm-icon-btn view" onclick="viewRoomDetails('${room.room_id}')" title="View"><i class="fas fa-eye"></i></button>
              <button class="rm-icon-btn edit" onclick="openEditRoomModal('${room.room_id}')" title="Edit"><i class="fas fa-pen"></i></button>
              <button class="rm-icon-btn del" onclick="confirmDeleteRoom('${room.room_id}', '${(room.room_name || room.room_number).replace(/'/g, "\\'")}')" title="Delete"><i class="fas fa-trash"></i></button>
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

    let pageButtons = '';
    const maxVisible = 5;
    let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
    let end = Math.min(totalPages, start + maxVisible - 1);
    if (end - start < maxVisible - 1) start = Math.max(1, end - maxVisible + 1);
    for (let i = start; i <= end; i++) {
      pageButtons += `<button class="rm-pag-btn${i === currentPage ? ' active' : ''}" onclick="changePage(${i})">${i}</button>`;
    }

    container.innerHTML = `
      <div class="rm-per-page-wrap">
        Show
        <select class="rm-per-page-select" onchange="changeItemsPerPage(this.value)">
          <option value="5"  ${itemsPerPage === 5  ? 'selected' : ''}>5</option>
          <option value="10" ${itemsPerPage === 10 ? 'selected' : ''}>10</option>
          <option value="25" ${itemsPerPage === 25 ? 'selected' : ''}>25</option>
          <option value="50" ${itemsPerPage === 50 ? 'selected' : ''}>50</option>
        </select>
        entries
      </div>
      <span class="rm-pag-info">Showing ${startItem}–${endItem} of ${totalItems}</span>
      <div class="rm-pag-controls">
        <button class="rm-pag-btn" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>
        ${pageButtons}
        <button class="rm-pag-btn" onclick="changePage(${currentPage + 1})" ${currentPage >= totalPages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>
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

    document.getElementById('roomDetailsModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeRoomDetailsModal() {
    document.getElementById('roomDetailsModal').classList.remove('open');
    document.body.style.overflow = '';
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

        showRmToast('Room added successfully!');
        closeAddRoomModal();
        await fetchRoomsData();
      } else {
        showRmToast(result.message || 'Failed to add room', 'error');
      }
    } catch (error) {
      showRmToast('An error occurred while adding the room', 'error');
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

        showRmToast('Room updated successfully!');
        closeEditRoomModal();
        await fetchRoomsData();
      } else {
        showRmToast(result.message || 'Failed to update room', 'error');
      }
    } catch (error) {
      showRmToast('An error occurred while updating the room', 'error');
    }
  }

  // Delete Room Function (API-based)
  async function confirmDeleteRoom(roomId, roomNumber) {
    if (!confirm(`Delete room "${roomNumber}"? This action cannot be undone.`)) return;
    try {
      const response = await fetch('../api/rooms.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken },
        body: JSON.stringify({ action: 'delete', room_id: roomId, csrf_token: csrfToken })
      });
      const result = await response.json();
      if (result.success) {
        showRmToast('Room deleted successfully!');
        await fetchRoomsData();
      } else {
        showRmToast(result.message || 'Failed to delete room', 'error');
      }
    } catch (error) {
      showRmToast('An error occurred while deleting the room', 'error');
    }
  }
</script>

<!-- PAGE CONTENT -->
<div class="rm-page">
  <div class="rm-card">
    <div class="rm-card-header">
      <span class="rm-card-title">Rooms</span>
      <div class="rm-toolbar">
        <div class="rm-search-wrap">
          <i class="fas fa-magnifying-glass"></i>
          <input type="text" id="searchInput" class="rm-search-input" placeholder="Search rooms…" oninput="filterTable()">
        </div>
        <select class="rm-filter-select" id="statusFilter" onchange="filterTable()">
          <option value="">All statuses</option>
          <option value="available">Available</option>
          <option value="occupied">Occupied</option>
          <option value="reserved">Reserved</option>
          <option value="cleaning">Cleaning</option>
          <option value="maintenance">Maintenance</option>
          <option value="out_of_order">Out of Order</option>
          <option value="blocked">Blocked</option>
        </select>
        <button class="rm-btn rm-btn-primary" onclick="openAddRoomModal()">
          <i class="fas fa-plus"></i> Add Room
        </button>
      </div>
    </div>
    <div class="rm-table-wrap">
      <table class="rm-table" id="roomsTable">
        <thead>
          <tr>
            <th>Room</th>
            <th>Property</th>
            <th>Category</th>
            <th>Floor</th>
            <th style="text-align:center;">Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody id="roomsTableBody"></tbody>
      </table>
    </div>
    <div class="rm-pagination" id="paginationControls"></div>
  </div>
</div>

<div id="rmToast" class="rm-toast"></div>

<!-- Room Details Modal -->
<div id="roomDetailsModal" class="rm-modal-backdrop" onclick="if(event.target===this)closeRoomDetailsModal()">
  <div class="rm-modal">
    <div class="rm-modal-header">
      <span class="rm-modal-title" id="modalRoomName">Room Details</span>
      <button class="rm-modal-close" onclick="closeRoomDetailsModal()"><i class="fas fa-times"></i></button>
    </div>
    <div class="rm-modal-body" id="modalRoomContent"></div>
  </div>
</div>

<!-- Add Room Modal -->
<div id="addRoomModal" class="rm-modal-backdrop" onclick="if(event.target===this)closeAddRoomModal()">
  <div class="rm-modal">
    <div class="rm-modal-header">
      <span class="rm-modal-title">Add New Room</span>
      <button class="rm-modal-close" onclick="closeAddRoomModal()"><i class="fas fa-times"></i></button>
    </div>
    <form id="addRoomForm" onsubmit="handleAddRoomSubmit(event)">
      <div class="rm-modal-body">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
        <p class="rm-section-label">Basic Info</p>
        <div class="rm-form-grid">
          <div class="rm-form-group">
            <label>Property *</label>
            <select name="property_id" id="addPropertyDropdown" required><option value="">Select Property</option></select>
          </div>
          <div class="rm-form-group">
            <label>Room Category *</label>
            <select name="category_id" id="addCategoryDropdown" required><option value="">Select Category</option></select>
          </div>
          <div class="rm-form-group">
            <label>Room Number *</label>
            <input type="text" name="room_number" required placeholder="e.g., 101, A-02">
          </div>
          <div class="rm-form-group">
            <label>Room Name</label>
            <input type="text" name="room_name" placeholder="e.g., Sunset Villa">
          </div>
          <div class="rm-form-group">
            <label>Floor Number</label>
            <input type="number" name="floor_number" placeholder="e.g., 1">
          </div>
          <div class="rm-form-group">
            <label>Building Block</label>
            <input type="text" name="building_block" placeholder="e.g., Building A">
          </div>
          <div class="rm-form-group">
            <label>Room Status *</label>
            <select name="room_status" required>
              <option value="available">Available</option>
              <option value="occupied">Occupied</option>
              <option value="reserved">Reserved</option>
              <option value="cleaning">Cleaning</option>
              <option value="maintenance">Maintenance</option>
              <option value="out_of_order">Out of Order</option>
              <option value="blocked">Blocked</option>
            </select>
          </div>
          <div class="rm-form-group full"></div>
        </div>
        <p class="rm-section-label">Pricing Overrides</p>
        <div class="rm-form-grid">
          <div class="rm-form-group">
            <label>Per Night (PHP)</label>
            <input type="number" step="0.01" name="override_price_per_night" placeholder="Leave empty for category price">
          </div>
          <div class="rm-form-group">
            <label>Per Hour (PHP)</label>
            <input type="number" step="0.01" name="override_price_per_hour" placeholder="Leave empty for category price">
          </div>
          <div class="rm-form-group">
            <label>Day Use (PHP)</label>
            <input type="number" step="0.01" name="override_price_day_use" placeholder="Leave empty for category price">
          </div>
          <div class="rm-form-group full">
            <label>Internal Notes</label>
            <textarea name="internal_notes" rows="3" placeholder="Any notes about this room..."></textarea>
          </div>
        </div>
        <p class="rm-section-label">Room Images</p>
        <div id="imageUploadContainer">
          <div class="rm-image-entry">
            <div class="rm-form-group"><label>Image File</label><input type="file" name="room_images[]" accept="image/*"></div>
            <div class="rm-form-group"><label>Image Type</label>
              <select name="image_type[]">
                <option value="interior">Interior</option><option value="exterior">Exterior</option>
                <option value="bathroom">Bathroom</option><option value="view">View</option>
                <option value="amenity">Amenity</option><option value="bed">Bed</option>
                <option value="balcony">Balcony</option><option value="other">Other</option>
              </select>
            </div>
            <div class="rm-form-group"><label>Caption</label><input type="text" name="image_caption[]" placeholder="Image caption"></div>
          </div>
        </div>
        <button type="button" class="rm-add-image-btn" onclick="addImageField()"><i class="fas fa-plus"></i> Add another image</button>
      </div>
      <div class="rm-modal-footer">
        <button type="button" class="rm-btn" onclick="closeAddRoomModal()">Cancel</button>
        <button type="submit" class="rm-btn rm-btn-primary"><i class="fas fa-save"></i> Save Room</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Room Modal -->
<div id="editRoomModal" class="rm-modal-backdrop" onclick="if(event.target===this)closeEditRoomModal()">
  <div class="rm-modal">
    <div class="rm-modal-header">
      <span class="rm-modal-title">Edit Room</span>
      <button class="rm-modal-close" onclick="closeEditRoomModal()"><i class="fas fa-times"></i></button>
    </div>
    <form id="editRoomForm" onsubmit="handleEditRoomSubmit(event)">
      <div class="rm-modal-body">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
        <input type="hidden" name="room_id" id="edit_room_id">
        <p class="rm-section-label">Basic Info</p>
        <div class="rm-form-grid">
          <div class="rm-form-group">
            <label>Property *</label>
            <select name="property_id" id="editPropertyDropdown" required><option value="">Select Property</option></select>
          </div>
          <div class="rm-form-group">
            <label>Room Category *</label>
            <select name="category_id" id="editCategoryDropdown" required><option value="">Select Category</option></select>
          </div>
          <div class="rm-form-group">
            <label>Room Number *</label>
            <input type="text" name="room_number" id="edit_room_number" required placeholder="e.g., 101, A-02">
          </div>
          <div class="rm-form-group">
            <label>Room Name</label>
            <input type="text" name="room_name" id="edit_room_name" placeholder="e.g., Sunset Villa">
          </div>
          <div class="rm-form-group">
            <label>Floor Number</label>
            <input type="number" name="floor_number" id="edit_floor_number" placeholder="e.g., 1">
          </div>
          <div class="rm-form-group">
            <label>Building Block</label>
            <input type="text" name="building_block" id="edit_building_block" placeholder="e.g., Building A">
          </div>
          <div class="rm-form-group">
            <label>Room Status *</label>
            <select name="room_status" id="edit_room_status" required>
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
        <p class="rm-section-label">Pricing Overrides</p>
        <div class="rm-form-grid">
          <div class="rm-form-group">
            <label>Per Night (PHP)</label>
            <input type="number" step="0.01" name="override_price_per_night" id="edit_override_price_per_night" placeholder="Leave empty for category price">
          </div>
          <div class="rm-form-group">
            <label>Per Hour (PHP)</label>
            <input type="number" step="0.01" name="override_price_per_hour" id="edit_override_price_per_hour" placeholder="Leave empty for category price">
          </div>
          <div class="rm-form-group">
            <label>Day Use (PHP)</label>
            <input type="number" step="0.01" name="override_price_day_use" id="edit_override_price_day_use" placeholder="Leave empty for category price">
          </div>
          <div class="rm-form-group full">
            <label>Internal Notes</label>
            <textarea name="internal_notes" id="edit_internal_notes" rows="3" placeholder="Any notes about this room..."></textarea>
          </div>
        </div>
        <p class="rm-section-label">Images</p>
        <div class="rm-existing-images" id="existingImagesContainer"></div>
        <p class="rm-section-label" style="margin-top:12px;">Add New Images</p>
        <div id="editImageUploadContainer">
          <div class="rm-image-entry">
            <div class="rm-form-group"><label>Image File</label><input type="file" name="room_images[]" accept="image/*"></div>
            <div class="rm-form-group"><label>Image Type</label>
              <select name="image_type[]">
                <option value="interior">Interior</option><option value="exterior">Exterior</option>
                <option value="bathroom">Bathroom</option><option value="view">View</option>
                <option value="amenity">Amenity</option><option value="bed">Bed</option>
                <option value="balcony">Balcony</option><option value="other">Other</option>
              </select>
            </div>
            <div class="rm-form-group"><label>Caption</label><input type="text" name="image_caption[]" placeholder="Image caption"></div>
          </div>
        </div>
        <button type="button" class="rm-add-image-btn" onclick="addEditImageField()"><i class="fas fa-plus"></i> Add another image</button>
      </div>
      <div class="rm-modal-footer">
        <button type="button" class="rm-btn" onclick="closeEditRoomModal()">Cancel</button>
        <button type="submit" class="rm-btn rm-btn-primary"><i class="fas fa-save"></i> Update Room</button>
      </div>
    </form>
  </div>
</div>

<script>

  function openAddRoomModal() {
    document.getElementById('addRoomModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeAddRoomModal() {
    document.getElementById('addRoomModal').classList.remove('open');
    document.body.style.overflow = '';
    document.getElementById('addRoomForm').reset();
  }

  function addImageField() {
    const container = document.getElementById('imageUploadContainer');
    const imageEntry = document.createElement('div');
    imageEntry.className = 'rm-image-entry';
    imageEntry.innerHTML = `
      <button type="button" class="rm-image-entry-remove" onclick="this.closest('.rm-image-entry').remove()"><i class="fas fa-times"></i></button>
      <div class="rm-form-group"><label>Image File</label><input type="file" name="room_images[]" accept="image/*"></div>
      <div class="rm-form-group"><label>Image Type</label>
        <select name="image_type[]">
          <option value="interior">Interior</option><option value="exterior">Exterior</option>
          <option value="bathroom">Bathroom</option><option value="view">View</option>
          <option value="amenity">Amenity</option><option value="bed">Bed</option>
          <option value="balcony">Balcony</option><option value="other">Other</option>
        </select>
      </div>
      <div class="rm-form-group"><label>Caption</label><input type="text" name="image_caption[]" placeholder="Image caption"></div>
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

    document.getElementById('editRoomModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeEditRoomModal() {
    document.getElementById('editRoomModal').classList.remove('open');
    document.body.style.overflow = '';
    document.getElementById('editRoomForm').reset();
    document.getElementById('existingImagesContainer').innerHTML = '';
    document.getElementById('editImageUploadContainer').innerHTML = `
      <div class="rm-image-entry">
        <div class="rm-form-group"><label>Image File</label><input type="file" name="room_images[]" accept="image/*"></div>
        <div class="rm-form-group"><label>Image Type</label>
          <select name="image_type[]">
            <option value="interior">Interior</option><option value="exterior">Exterior</option>
            <option value="bathroom">Bathroom</option><option value="view">View</option>
            <option value="amenity">Amenity</option><option value="bed">Bed</option>
            <option value="balcony">Balcony</option><option value="other">Other</option>
          </select>
        </div>
        <div class="rm-form-group"><label>Caption</label><input type="text" name="image_caption[]" placeholder="Image caption"></div>
      </div>
    `;
  }

  function addEditImageField() {
    const container = document.getElementById('editImageUploadContainer');
    const imageEntry = document.createElement('div');
    imageEntry.className = 'rm-image-entry';
    imageEntry.innerHTML = `
      <button type="button" class="rm-image-entry-remove" onclick="this.closest('.rm-image-entry').remove()"><i class="fas fa-times"></i></button>
      <div class="rm-form-group"><label>Image File</label><input type="file" name="room_images[]" accept="image/*"></div>
      <div class="rm-form-group"><label>Image Type</label>
        <select name="image_type[]">
          <option value="interior">Interior</option><option value="exterior">Exterior</option>
          <option value="bathroom">Bathroom</option><option value="view">View</option>
          <option value="amenity">Amenity</option><option value="bed">Bed</option>
          <option value="balcony">Balcony</option><option value="other">Other</option>
        </select>
      </div>
      <div class="rm-form-group"><label>Caption</label><input type="text" name="image_caption[]" placeholder="Image caption"></div>
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
      <div class="rm-existing-img">
        <img src="../${img.image_url}" alt="${img.caption || ''}">
        <button type="button" class="rm-existing-img-del" onclick="markImageForDeletion('${img.image_id}', this)"><i class="fas fa-times"></i></button>
        <input type="hidden" name="delete_images[]" id="delete_img_${img.image_id}" value="">
        <p>${img.caption || img.image_type}</p>
      </div>
    `).join('');
  }

  function markImageForDeletion(imageId, button) {
    const input = document.getElementById(`delete_img_${imageId}`);
    const wrapper = button.closest('.rm-existing-img');
    if (input.value === imageId) {
      input.value = '';
      wrapper.style.opacity = '1';
    } else {
      input.value = imageId;
      wrapper.style.opacity = '0.4';
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

  window.addEventListener('load', function() {
    <?php if (isset($error_message)): ?>
      showRmToast(<?php echo json_encode($error_message); ?>, 'error');
    <?php endif; ?>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === 'room_added') {
      window.history.replaceState({}, document.title, window.location.pathname);
      showRmToast('Room added successfully!');
    }
    if (urlParams.get('success') === 'room_deleted') {
      window.history.replaceState({}, document.title, window.location.pathname);
      showRmToast('Room deleted successfully!');
    }
    if (urlParams.get('success') === 'room_updated') {
      window.history.replaceState({}, document.title, window.location.pathname);
      showRmToast('Room updated successfully!');
    }
  });

  function filterTable() {
    const filter = (document.getElementById('searchInput')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilter')?.value || '').toLowerCase();
    const rows = document.querySelectorAll('#roomsTableBody .room-row');
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      const matchText = !filter || text.includes(filter);
      const matchStatus = !statusFilter || text.includes(statusFilter.replace(/_/g, ' '));
      row.style.display = (matchText && matchStatus) ? '' : 'none';
    });
  }
</script>