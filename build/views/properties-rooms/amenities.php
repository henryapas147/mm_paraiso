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
    --color-border-primary: #185fa5;
    --color-border-secondary: #e2e8f0;
    --color-border-tertiary: #cbd5e1;
    --color-text-primary: #1e293b;
    --color-text-secondary: #64748b;
  }
  .am-page { padding: 24px; max-width: 100%; margin: 0 auto; overflow-x: hidden; }
  .am-stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
  .am-stat-card {
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 12px;
    padding: 16px;
  }
  .am-stat-label { font-size: 12px; font-weight: 500; color: var(--color-text-secondary); text-transform: uppercase; letter-spacing: 0.3px; }
  .am-stat-value { font-size: 24px; font-weight: 600; color: var(--color-text-primary); margin-top: 4px; }
  .am-card {
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 12px;
    margin-bottom: 0;
  }
  .am-card-header {
    padding: 16px 20px;
    border-bottom: 0.5px solid var(--color-border-tertiary);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }
  .am-card-title { font-size: 16px; font-weight: 500; }
  .am-toolbar { display: flex; align-items: center; gap: 8px; flex-wrap: nowrap; }
  .am-search-wrap {
    display: flex;
    align-items: center;
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 6px;
    padding: 4px 10px 4px 30px;
    background: #f9fafb;
    position: relative;
    min-width: 200px;
  }
  .am-search-wrap i {
    position: absolute;
    left: 8px;
    color: var(--color-text-secondary);
    font-size: 12px;
  }
  .am-search-input {
    border: none;
    outline: none;
    background: transparent;
    font-size: 13px;
    width: 100%;
    color: var(--color-text-primary);
  }
  .am-filter-select {
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 13px;
    color: var(--color-text-primary);
    outline: none;
    background: #f9fafb;
  }
  .am-btn {
    border: 0.5px solid var(--color-border-secondary);
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-primary);
    background: #ffffff;
    cursor: pointer;
    transition: all 0.18s ease;
  }
  .am-btn:hover { background: #f1f5f9; border-color: #cbd5e1; }
  .am-btn-primary {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
    border: none;
  }
  .am-btn-primary:hover { opacity: 0.9; box-shadow: 0 2px 8px rgba(59,130,246,0.25); }
  .am-table-wrap { overflow-x: auto; }
  .am-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }
  .am-table th {
    text-align: left;
    padding: 10px 12px;
    border-bottom: 0.5px solid var(--color-border-secondary);
    color: var(--color-text-secondary);
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    background: #f9fafb;
  }
  .am-table td {
    padding: 10px 12px;
    border-bottom: 0.5px solid var(--color-border-secondary);
    color: var(--color-text-primary);
  }
  .am-table tr:hover td { background: #f8fafc; }
  .am-name { font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px; }
  .am-code { font-size: 12px; color: var(--color-text-secondary); }
  .am-text { color: var(--color-text-primary); font-size: 13px; }
  .am-status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }
  .am-status-active { background: #dcfce7; color: #166534; }
  .am-status-inactive { background: #e5e7eb; color: #374151; }
  .am-actions { display: flex; gap: 4px; justify-content: flex-end; }
  .am-icon-btn {
    border: 0.5px solid var(--color-border-secondary);
    border-radius: 6px;
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    background: #ffffff;
    color: var(--color-text-secondary);
    font-size: 12px;
    transition: all 0.18s ease;
  }
  .am-icon-btn.view:hover { border-color: #3b82f6; color: #3b82f6; background: #eff6ff; }
  .am-icon-btn.edit:hover { border-color: #f59e0b; color: #f59e0b; background: #fffbeb; }
  .am-icon-btn.del:hover { border-color: #ef4444; color: #ef4444; background: #fef2f2; }
  .am-empty-state { text-align: center; padding: 24px; color: var(--color-text-secondary); font-size: 13px; }
  .am-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.4);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.18s ease;
  }
  .am-modal-backdrop.open { opacity: 1; pointer-events: auto; }
  .am-modal {
    background: #ffffff;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    transform: scale(0.96) translateY(10px);
    transition: transform 0.18s ease;
  }
  .am-modal-backdrop.open .am-modal { transform: scale(1) translateY(0); }
  .am-modal-header {
    padding: 14px 18px;
    border-bottom: 0.5px solid var(--color-border-secondary);
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    background: #ffffff;
    z-index: 1;
  }
  .am-modal-title { font-size: 15px; font-weight: 500; }
  .am-modal-close {
    border: none;
    background: none;
    cursor: pointer;
    color: var(--color-text-secondary);
    font-size: 16px;
    padding: 4px;
    border-radius: 4px;
  }
  .am-modal-close:hover { background: #f1f5f9; color: var(--color-text-primary); }
  .am-modal-body { padding: 16px 18px; }
  .am-modal-footer {
    padding: 12px 18px;
    border-top: 0.5px solid var(--color-border-secondary);
    display: flex;
    gap: 8px;
    justify-content: flex-end;
    position: sticky;
    bottom: 0;
    background: #ffffff;
  }
  .am-section-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin: 0 0 10px;
  }
  .am-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px; }
  .am-form-group { display: flex; flex-direction: column; gap: 4px; }
  .am-form-group.full { grid-column: 1 / -1; }
  .am-form-group label { font-size: 12px; font-weight: 500; color: var(--color-text-secondary); }
  .am-form-group input,
  .am-form-group select,
  .am-form-group textarea {
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 6px;
    padding: 7px 10px;
    font-size: 13px;
    color: var(--color-text-primary);
    outline: none;
    background: #ffffff;
  }
  .am-form-group input:focus,
  .am-form-group select:focus,
  .am-form-group textarea:focus { border-color: var(--color-border-primary); }
  .am-form-group textarea { resize: vertical; min-height: 60px; }
  .am-checkbox-wrap { display: flex; align-items: center; gap: 6px; font-size: 13px; }
  .am-checkbox-wrap input[type=checkbox] {
    width: 14px;
    height: 14px;
    accent-color: var(--color-border-primary);
  }
  .pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    border-top: 0.5px solid var(--color-border-secondary);
    gap: 12px;
    flex-wrap: wrap;
  }
  .pag-info {
    font-size: 13px;
    color: var(--color-text-secondary);
  }
  .per-page-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--color-text-secondary);
  }
  .per-page-wrap select {
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 12px;
    color: var(--color-text-primary);
    outline: none;
    background: #ffffff;
  }
  .pag-controls {
    display: flex;
    align-items: center;
    gap: 4px;
  }
  .pag-btn {
    border: 0.5px solid var(--color-border-secondary);
    border-radius: 4px;
    padding: 5px 10px;
    font-size: 12px;
    color: var(--color-text-primary);
    background: #ffffff;
    cursor: pointer;
    transition: all 0.18s ease;
    min-width: 32px;
  }
  .pag-btn:hover:not(:disabled) { background: #f1f5f9; border-color: #cbd5e1; }
  .pag-btn:disabled { opacity: 0.5; cursor: not-allowed; }
  .pag-btn.active {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
    border-color: transparent;
  }
  .am-toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #10b981;
    color: white;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.25s ease;
    z-index: 2000;
  }
  .am-toast.error { background: #ef4444; }
  .am-toast.show { opacity: 1; transform: translateY(0); }
  @media (max-width: 1024px) {
    .am-table th:nth-child(3),
    .am-table td:nth-child(3) { display: none; }
  }
  @media (max-width: 768px) {
    .am-stats-grid { grid-template-columns: repeat(2, 1fr); }
    .am-card-header { flex-direction: column; align-items: stretch; }
    .am-toolbar { flex-wrap: wrap; }
    .am-search-wrap { min-width: 100%; }
    .am-form-grid { grid-template-columns: 1fr; }
    .am-modal { width: 95%; max-height: 95vh; }
    .am-table th:nth-child(2),
    .am-table td:nth-child(2) { display: none; }
  }
  @media (max-width: 480px) {
    .am-page { padding: 12px; }
    .am-card-header { padding: 12px; }
    .am-toolbar { gap: 6px; }
    .am-btn { font-size: 12px; padding: 5px 10px; }
    .am-icon-btn { width: 26px; height: 26px; }
    .am-stats-grid { grid-template-columns: 1fr; }
  }
</style>

<script>
  // CSRF Token for API requests (from db.php)
  const CSRF_TOKEN = <?php echo json_encode(CSRF_TOKEN); ?>;

  // Data will be fetched from API on page load
  let amenitiesData = [];
  let currentPage = 1;
  let itemsPerPage = 10;
  let totalItems = 0;

  // Fetch data from API on page load
  document.addEventListener('DOMContentLoaded', async function() {
    await fetchAmenitiesData();
  });

  async function fetchAmenitiesData() {
    try {
      const offset = (currentPage - 1) * itemsPerPage;
      const response = await fetch(`../api/amenities.php?action=list&limit=${itemsPerPage}&offset=${offset}`);
      const result = await response.json();
      if (result.success) {
        amenitiesData = result.data;
        totalItems = result.total ?? amenitiesData.length;
        renderAmenitiesTable();
        renderPagination();
        updateStatsCounts();
      }
    } catch (error) {
      console.error('Error fetching amenities:', error);
      showAmToast('Failed to load amenities data', 'error');
    }
  }

  function showAmToast(message, type = 'success') {
    const toast = document.getElementById('amToast');
    toast.textContent = message;
    toast.className = `am-toast ${type}`;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
  }

  function updateStatsCounts() {
    const totalCount = amenitiesData.length;
    const comfortCount = amenitiesData.filter(a => a.amenity_type === 'comfort').length;
    const techCount = amenitiesData.filter(a => a.amenity_type === 'technology').length;
    const safetyCount = amenitiesData.filter(a => a.amenity_type === 'safety').length;

    const totalEl = document.getElementById('totalAmenitiesCount');
    const comfortEl = document.getElementById('comfortAmenitiesCount');
    const techEl = document.getElementById('techAmenitiesCount');
    const safetyEl = document.getElementById('safetyAmenitiesCount');

    if (totalEl) totalEl.textContent = totalCount;
    if (comfortEl) comfortEl.textContent = comfortCount;
    if (techEl) techEl.textContent = techCount;
    if (safetyEl) safetyEl.textContent = safetyCount;
  }

  function renderAmenitiesTable() {
    const tbody = document.querySelector('#amenitiesTableBody');
    if (!tbody) return;
    if (amenitiesData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="4"><div class="am-empty-state">No amenities found. <a href="javascript:;" onclick="openAddAmenityModal()" style="color:#185fa5;font-weight:500;">Add your first amenity</a>.</div></td></tr>`;
      return;
    }
    tbody.innerHTML = amenitiesData.map((amenity) => {
      const iconHtml = amenity.icon_code ? `<i class="fa-solid ${amenity.icon_code}"></i>` : '';
      return `
        <tr class="amenity-row">
          <td>
            <div class="am-name">${iconHtml}<span>${amenity.amenity_name}</span></div>
          </td>
          <td><span class="am-code">${amenity.icon_code || '—'}</span></td>
          <td><span class="am-text">${amenity.category || 'general'}</span></td>
          <td style="text-align:center;"><span class="am-status-badge am-status-${amenity.is_active ? 'active' : 'inactive'}">${amenity.is_active ? 'Active' : 'Inactive'}</span></td>
          <td>
            <div class="am-actions">
              <button class="am-icon-btn edit" onclick="openEditAmenityModal('${String(amenity.amenity_id).replace(/'/g, "\\'")}')" title="Edit"><i class="fas fa-pen"></i></button>
              <button class="am-icon-btn del" onclick="confirmDeleteAmenity('${String(amenity.amenity_id).replace(/'/g, "\\'")}', '${String(amenity.amenity_name).replace(/'/g, "\\'")}')" title="Delete"><i class="fas fa-trash"></i></button>
            </div>
          </td>
        </tr>
      `;
    }).join('');
  }

  async function confirmDeleteAmenity(amenityId, amenityName) {
    if (!confirm(`Delete "${amenityName}"? This action cannot be undone.`)) return;
    try {
      const response = await fetch('../api/amenities.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'delete', amenity_id: amenityId, csrf_token: CSRF_TOKEN })
      });
      const result = await response.json();
      if (result.success) {
        showAmToast('Amenity deleted successfully!');
        await fetchAmenitiesData();
      } else {
        showAmToast(result.message || 'Failed to delete amenity', 'error');
      }
    } catch (error) {
      showAmToast('An error occurred while deleting the amenity', 'error');
    }
  }

  // Unified Form Submission (API-based)
  async function handleAmenityFormSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const amenityId = formData.get('amenity_id');
    const isEdit = !!amenityId;
    const amenity_id = isEdit ? amenityId : generateUUID();
    const data = {
      amenity_id: amenity_id,
      amenity_code: formData.get('icon_code') || formData.get('amenity_name').substring(0, 3).toUpperCase(),
      amenity_name: formData.get('amenity_name'),
      amenity_type: formData.get('amenity_type') || 'comfort',
      icon: formData.get('icon_code') || null,
      is_active: formData.get('is_active') || 1,
      sort_order: formData.get('sort_order') || 0
    };
    try {
      const response = await fetch('../api/amenities.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: isEdit ? 'update' : 'create', ...data, csrf_token: CSRF_TOKEN })
      });
      const result = await response.json();
      if (result.success) {
        showAmToast(isEdit ? 'Amenity updated successfully!' : 'Amenity added successfully!');
        closeAmenityFormModal();
        await fetchAmenitiesData();
      } else {
        showAmToast(result.message || 'Failed to save amenity', 'error');
      }
    } catch (error) {
      showAmToast('An error occurred while saving the amenity', 'error');
    }
  }
</script>

<!-- PAGE CONTENT -->
<div class="am-page">
  <div class="am-stats-grid">
    <div class="am-stat-card">
      <p class="am-stat-label">Total Amenities</p>
      <p class="am-stat-value" id="totalAmenitiesCount">0</p>
    </div>
    <div class="am-stat-card">
      <p class="am-stat-label">Comfort</p>
      <p class="am-stat-value" id="comfortAmenitiesCount">0</p>
    </div>
    <div class="am-stat-card">
      <p class="am-stat-label">Technology</p>
      <p class="am-stat-value" id="techAmenitiesCount">0</p>
    </div>
    <div class="am-stat-card">
      <p class="am-stat-label">Safety</p>
      <p class="am-stat-value" id="safetyAmenitiesCount">0</p>
    </div>
  </div>

  <div class="am-card">
    <div class="am-card-header">
      <span class="am-card-title">Amenities</span>
      <div class="am-toolbar">
        <div class="am-search-wrap">
          <i class="fas fa-magnifying-glass"></i>
          <input type="text" id="searchInput" class="am-search-input" placeholder="Search amenities…" oninput="filterTable()">
        </div>
        <select class="am-filter-select" id="typeFilter" onchange="filterTable()">
          <option value="">All types</option>
          <option value="comfort">Comfort</option>
          <option value="technology">Technology</option>
          <option value="safety">Safety</option>
          <option value="bathroom">Bathroom</option>
          <option value="kitchen">Kitchen</option>
          <option value="outdoor">Outdoor</option>
          <option value="accessibility">Accessibility</option>
          <option value="entertainment">Entertainment</option>
          <option value="other">Other</option>
        </select>
        <button class="am-btn am-btn-primary" onclick="openAddAmenityModal()">
          <i class="fas fa-plus"></i> Add Amenity
        </button>
      </div>
    </div>
    <div class="am-table-wrap">
      <table class="am-table" id="amenitiesTable">
        <thead>
          <tr>
            <th>Amenity</th>
            <th>Icon Code</th>
            <th>Category</th>
            <th style="text-align:center;">Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody id="amenitiesTableBody"></tbody>
      </table>
    </div>
    <div id="paginationContainer"></div>
  </div>
</div>

<div id="amToast" class="am-toast"></div>

<!-- Add/Edit Amenity Modal -->
<div id="amenityFormModal" class="am-modal-backdrop" onclick="if(event.target===this)closeAmenityFormModal()">
  <div class="am-modal">
    <div class="am-modal-header">
      <span class="am-modal-title" id="formModalTitle">Add Amenity</span>
      <button class="am-modal-close" onclick="closeAmenityFormModal()"><i class="fas fa-times"></i></button>
    </div>
    <form id="amenityForm" onsubmit="handleAmenityFormSubmit(event)">
      <input type="hidden" name="amenity_id" id="formAmenityId">
      <div class="am-modal-body">
        <p class="am-section-label">Basic Information</p>
        <div class="am-form-grid">
          <div class="am-form-group full">
            <label>Amenity Name *</label>
            <input type="text" name="amenity_name" id="formAmenityName" required placeholder="e.g. WiFi">
          </div>
          <div class="am-form-group">
            <label>Icon Code</label>
            <input type="text" name="icon_code" id="formIconCode" placeholder="e.g. fa-wifi">
          </div>
          <div class="am-form-group">
            <label>Category *</label>
            <select name="amenity_type" id="formAmenityType" required>
              <option value="comfort">Comfort</option>
              <option value="technology">Technology</option>
              <option value="bathroom">Bathroom</option>
              <option value="kitchen">Kitchen</option>
              <option value="outdoor">Outdoor</option>
              <option value="safety">Safety</option>
              <option value="accessibility">Accessibility</option>
              <option value="entertainment">Entertainment</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="am-form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" id="formSortOrder" value="0">
          </div>
          <div class="am-form-group">
            <label class="am-checkbox-wrap">
              <input type="checkbox" name="is_active" id="formIsActive" value="1" checked>
              Active
            </label>
          </div>
        </div>
      </div>
      <div class="am-modal-footer">
        <button type="button" class="am-btn" onclick="closeAmenityFormModal()">Cancel</button>
        <button type="submit" class="am-btn am-btn-primary"><i class="fas fa-save"></i> Save Amenity</button>
      </div>
    </form>
  </div>
</div>

<script>
  function filterTable() {
    const filter = (document.getElementById('searchInput')?.value || '').toLowerCase();
    const typeFilter = (document.getElementById('typeFilter')?.value || '').toLowerCase();
    const rows = document.querySelectorAll('#amenitiesTableBody .amenity-row');
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      const matchText = !filter || text.includes(filter);
      const matchType = !typeFilter || text.includes(typeFilter);
      row.style.display = (matchText && matchType) ? '' : 'none';
    });
  }

  function openAddAmenityModal() {
    document.getElementById('amenityForm').reset();
    document.getElementById('formModalTitle').textContent = 'Add Amenity';
    document.getElementById('formIsActive').checked = true;
    document.getElementById('amenityFormModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function openEditAmenityModal(amenityId) {
    const amenity = amenitiesData.find(a => a.amenity_id == amenityId);
    if (!amenity) return;
    document.getElementById('formModalTitle').textContent = 'Edit Amenity';
    document.getElementById('formAmenityId').value = amenity.amenity_id;
    document.getElementById('formAmenityName').value = amenity.amenity_name;
    document.getElementById('formIconCode').value = amenity.icon_code || '';
    document.getElementById('formAmenityType').value = amenity.category || 'comfort';
    document.getElementById('formSortOrder').value = amenity.sort_order || 0;
    document.getElementById('formIsActive').checked = amenity.is_active == 1;
    document.getElementById('amenityFormModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeAmenityFormModal() {
    document.getElementById('amenityFormModal').classList.remove('open');
    document.body.style.overflow = '';
    document.getElementById('amenityForm').reset();
  }

  // Pagination functions
  function renderPagination() {
    const paginationContainer = document.getElementById('paginationContainer');
    if (!paginationContainer) return;

    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const startItem = totalItems === 0 ? 0 : (currentPage - 1) * itemsPerPage + 1;
    const endItem = Math.min(currentPage * itemsPerPage, totalItems);

    let paginationHtml = `
      <div class="pagination">
        <div class="pag-info">
          Showing ${startItem} to ${endItem} of ${totalItems} entries
        </div>
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap">
          <div class="per-page-wrap">
            Show
            <select
              style="width: 64px; padding: 4px 8px; font-size: 12px"
              onchange="changeItemsPerPage(this.value)"
            >
              <option value="5" ${itemsPerPage === 5 ? 'selected' : ''}>5</option>
              <option value="10" ${itemsPerPage === 10 ? 'selected' : ''}>10</option>
              <option value="25" ${itemsPerPage === 25 ? 'selected' : ''}>25</option>
              <option value="50" ${itemsPerPage === 50 ? 'selected' : ''}>50</option>
            </select>
            entries
          </div>
          <div class="pag-controls">
            <button onclick="goToPage(1)" ${currentPage === 1 ? 'disabled' : ''} class="pag-btn">
              <i class="fas fa-chevrons-left"></i>
            </button>
            <button onclick="goToPage(currentPage - 1)" ${currentPage === 1 ? 'disabled' : ''} class="pag-btn">
              <i class="fas fa-chevron-left"></i>
            </button>
            ${getPageNumbers().map(page => `
              <button onclick="goToPage(${page})" ${page === currentPage ? 'disabled' : ''} class="pag-btn ${page === currentPage ? 'active' : ''}">
                ${page}
              </button>
            `).join('')}
            <button onclick="goToPage(currentPage + 1)" ${currentPage === totalPages ? 'disabled' : ''} class="pag-btn">
              <i class="fas fa-chevron-right"></i>
            </button>
            <button onclick="goToPage(totalPages)" ${currentPage === totalPages ? 'disabled' : ''} class="pag-btn">
              <i class="fas fa-chevrons-right"></i>
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

  function changeItemsPerPage(value) {
    itemsPerPage = parseInt(value);
    currentPage = 1;
    fetchAmenitiesData();
  }

  function goToPage(page) {
    if (page === '...' || page < 1 || page > Math.ceil(totalItems / itemsPerPage)) return;
    currentPage = page;
    fetchAmenitiesData();
  }

  window.addEventListener('load', function() {
    <?php if (isset($error_message)): ?>
      showAmToast(<?php echo json_encode($error_message); ?>, 'error');
    <?php endif; ?>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === 'amenity_added') {
      window.history.replaceState({}, document.title, window.location.pathname);
      showAmToast('Amenity added successfully!');
    }
    if (urlParams.get('success') === 'amenity_updated') {
      window.history.replaceState({}, document.title, window.location.pathname);
      showAmToast('Amenity updated successfully!');
    }
    if (urlParams.get('success') === 'amenity_deleted') {
      window.history.replaceState({}, document.title, window.location.pathname);
      showAmToast('Amenity deleted successfully!');
    }
  });
</script>