<style>
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0
  }

  .page {
    padding: 24px;
    max-width: 100%;
    margin: 0 auto
  }

  .page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 24px;
    gap: 16px;
    flex-wrap: wrap
  }

  .page-title {
    font-size: 22px;
    font-weight: 500;
    color: #333
  }

  .page-subtitle {
    font-size: 13px;
    color: #666;
    margin-top: 2px
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 12px;
    margin-bottom: 24px
  }

  .stat-card {
    background: #fff;
    border: 0.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px 20px
  }

  .stat-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 8px
  }

  .stat-value {
    font-size: 28px;
    font-weight: 500
  }

  .stat-value.blue {
    color: #185FA5
  }

  .stat-value.green {
    color: #3B6D11
  }

  .stat-value.purple {
    color: #534AB7
  }

  .card {
    background: #fff;
    border: 0.5px solid #e5e7eb;
    border-radius: 12px
  }

  .card-header {
    padding: 16px 20px;
    border-bottom: 0.5px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap
  }

  .card-title {
    font-size: 16px;
    font-weight: 500
  }

  .toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap
  }

  .search-wrap {
    position: relative
  }

  .search-wrap i {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    font-size: 15px;
    pointer-events: none
  }

  .search-input {
    padding: 7px 12px 7px 34px;
    font-size: 13px;
    border: 0.5px solid #d1d5db;
    border-radius: 8px;
    background: #f9fafb;
    color: #333;
    outline: none;
    width: 220px
  }

  .search-input:focus {
    border-color: #185FA5
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 8px;
    border: 0.5px solid #d1d5db;
    background: #fff;
    color: #333;
    cursor: pointer;
    transition: background .15s
  }

  .btn:hover {
    background: #f9fafb
  }

  .btn-primary {
    background: #185FA5;
    color: #fff;
    border-color: #185FA5
  }

  .btn-primary:hover {
    background: #0C447C;
    border-color: #0C447C
  }

  .btn i {
    font-size: 14px
  }

  .table-wrap {
    overflow-x: auto
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px
  }

  thead th {
    padding: 10px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 500;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    border-bottom: 0.5px solid #e5e7eb;
    white-space: nowrap
  }

  thead th.center {
    text-align: center
  }

  tbody tr {
    border-bottom: 0.5px solid #e5e7eb;
    transition: background .1s
  }

  tbody tr:last-child {
    border-bottom: none
  }

  tbody tr:hover {
    background: #f9fafb
  }

  td {
    padding: 11px 16px;
    vertical-align: middle;
    white-space: nowrap
  }

  td.value-cell {
    max-width: 180px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis
  }

  .group-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
    text-transform: capitalize
  }

  .group-badge.booking {
    background: #E6F1FB;
    color: #0C447C
  }

  .group-badge.payment {
    background: #EAF3DE;
    color: #27500A
  }

  .group-badge.notifications {
    background: #FAEEDA;
    color: #633806
  }

  .group-badge.display {
    background: #EEEDFE;
    color: #3C3489
  }

  .group-badge.security {
    background: #FCEBEB;
    color: #791F1F
  }

  .group-badge.other {
    background: #F1EFE8;
    color: #444441
  }

  .pub-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500
  }

  .pub-badge.yes {
    background: #EAF3DE;
    color: #27500A
  }

  .pub-badge.no {
    background: #F1EFE8;
    color: #5F5E5A
  }

  .scope-badge {
    font-size: 12px;
    color: #666
  }

  .scope-badge.global {
    color: #185FA5;
    font-weight: 500
  }

  .action-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
    font-size: 12px;
    font-weight: 500;
    border-radius: 8px;
    border: 0.5px solid #e5e7eb;
    background: transparent;
    cursor: pointer;
    transition: all .15s;
    color: #666
  }

  .action-btn:hover {
    background: #f9fafb;
    color: #333;
    border-color: #d1d5db
  }

  .action-btn.danger:hover {
    background: #FCEBEB;
    color: #791F1F;
    border-color: #F09595
  }

  .action-btn i {
    font-size: 12px
  }

  .actions-cell {
    display: flex;
    align-items: center;
    gap: 6px
  }

  .key-text {
    font-family: monospace;
    font-size: 12px;
    color: #333
  }

  .label-text {
    font-size: 13px;
    color: #333
  }

  .value-text {
    font-size: 12px;
    color: #666
  }

  .empty-state {
    padding: 48px 24px;
    text-align: center;
    color: #666
  }

  .empty-state i {
    font-size: 32px;
    margin-bottom: 12px;
    display: block;
    color: #999
  }

  .overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .45);
    z-index: 9000;
    align-items: center;
    justify-content: center;
    padding: 20px
  }

  .overlay.open {
    display: flex
  }

  .modal {
    background: #fff;
    border-radius: 16px;
    border: 0.5px solid #e5e7eb;
    width: 100%;
    max-width: 640px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 24px 48px rgba(0, 0, 0, .18)
  }

  .modal-header {
    padding: 20px 24px;
    border-bottom: 0.5px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between
  }

  .modal-title {
    font-size: 16px;
    font-weight: 500
  }

  .modal-close {
    background: none;
    border: none;
    cursor: pointer;
    color: #666;
    font-size: 20px;
    line-height: 1;
    padding: 4px
  }

  .modal-close:hover {
    color: #333
  }

  .modal-body {
    padding: 24px
  }

  .modal-footer {
    padding: 16px 24px;
    border-top: 0.5px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
    gap: 8px
  }

  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 6px
  }

  .form-group.full {
    grid-column: 1/-1
  }

  label {
    font-size: 12px;
    font-weight: 500;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.05em
  }

  label .req {
    color: #A32D2D;
    margin-left: 2px
  }

  input,
  select,
  textarea {
    font-family: system-ui, sans-serif;
    font-size: 13px;
    color: #333;
    background: #f9fafb;
    border: 0.5px solid #d1d5db;
    border-radius: 8px;
    padding: 8px 12px;
    outline: none;
    width: 100%;
    transition: border .15s
  }

  input:focus,
  select:focus,
  textarea:focus {
    border-color: #185FA5;
    background: #fff
  }

  textarea {
    resize: vertical;
    min-height: 80px
  }

  .divider {
    grid-column: 1/-1;
    border: none;
    border-top: 0.5px solid #e5e7eb;
    margin: 4px 0
  }

  .section-label {
    grid-column: 1/-1;
    font-size: 11px;
    font-weight: 500;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    padding-top: 4px
  }

  .toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: #fff;
    border: 0.5px solid #d1d5db;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
    z-index: 9999;
    box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
    transform: translateY(80px);
    opacity: 0;
    transition: all .25s
  }

  .toast.show {
    transform: none;
    opacity: 1
  }

  .toast.success {
    border-color: #9FE1CB;
    color: #085041
  }

  .toast.error {
    border-color: #F7C1C1;
    color: #791F1F
  }

  .pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-top: 0.5px solid #e5e7eb
  }

  .pagination-info {
    font-size: 13px;
    color: #666
  }

  .pagination-controls {
    display: flex;
    align-items: center;
    gap: 8px
  }

  .pagination-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 8px;
    border: 0.5px solid #d1d5db;
    background: #fff;
    color: #333;
    cursor: pointer;
    transition: background .15s
  }

  .pagination-btn:hover:not(:disabled) {
    background: #f9fafb
  }

  .pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed
  }

  .pagination-btn.active {
    background: #185FA5;
    color: #fff;
    border-color: #185FA5
  }

  .entries-select {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #666
  }

  .entries-select select {
    padding: 6px 10px;
    font-size: 13px;
    border: 0.5px solid #d1d5db;
    border-radius: 8px;
    background: #f9fafb;
    color: #333;
    outline: none;
    cursor: pointer
  }

  .entries-select select:focus {
    border-color: #185FA5;
    background: #fff
  }
</style>

<script>
  let settingsData = [];
  let propertiesData = [];
  let pagination = {
    page: 1,
    limit: 10,
    total: 0,
    totalPages: 0
  };

  async function fetchSettings() {
    try {
      const res = await fetch(`../api/system_settings.php?action=list&page=${pagination.page}&limit=${pagination.limit}`);
      const json = await res.json();
      if (json.success) {
        settingsData = json.data;
        pagination = json.pagination;
        renderStats();
        renderTable();
        renderPagination();
      }
    } catch (e) {
      console.error('Error fetching settings:', e);
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/system_settings.php?action=related');
      const json = await res.json();
      if (json.success) {
        propertiesData = json.data.properties;
        populateDropdowns();
      }
    } catch (e) {
      console.error('Error fetching related data:', e);
    }
  }

  function groupClass(g) {
    return ['booking', 'payment', 'notifications', 'display', 'security'].includes(g) ? g : 'other'
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = pagination.total;
    document.getElementById('statGlobal').textContent = settingsData.filter(s => s.property_id === null).length;
    document.getElementById('statProperty').textContent = settingsData.filter(s => s.property_id !== null).length;
    document.getElementById('statPublic').textContent = settingsData.filter(s => s.is_public == 1).length;
  }

  function renderPagination() {
    const start = (pagination.page - 1) * pagination.limit + 1;
    const end = Math.min(pagination.page * pagination.limit, pagination.total);
    document.getElementById('paginationInfo').textContent = `Showing ${start} to ${end} of ${pagination.total} entries`;

    document.getElementById('prevBtn').disabled = pagination.page === 1;
    document.getElementById('nextBtn').disabled = pagination.page === pagination.totalPages;

    const pageNumbers = document.getElementById('pageNumbers');
    pageNumbers.innerHTML = '';

    const maxVisiblePages = 5;
    let startPage = Math.max(1, pagination.page - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(pagination.totalPages, startPage + maxVisiblePages - 1);

    if (endPage - startPage + 1 < maxVisiblePages) {
      startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
      const btn = document.createElement('button');
      btn.className = `pagination-btn ${i === pagination.page ? 'active' : ''}`;
      btn.textContent = i;
      btn.onclick = () => goToPage(i);
      pageNumbers.appendChild(btn);
    }
  }

  function changePage(delta) {
    const newPage = pagination.page + delta;
    if (newPage >= 1 && newPage <= pagination.totalPages) {
      pagination.page = newPage;
      fetchSettings();
    }
  }

  function goToPage(page) {
    pagination.page = page;
    fetchSettings();
  }

  function changeEntriesPerPage() {
    pagination.limit = parseInt(document.getElementById('entriesPerPage').value);
    pagination.page = 1;
    fetchSettings();
  }

  function renderTable() {
    const tbody = document.getElementById('settingsTableBody');
    if (!settingsData.length) {
      tbody.innerHTML = `<tr><td colspan="7" class="empty-state"><i class="fas fa-database-off"></i>No settings found.</td></tr>`;
      return;
    }
    tbody.innerHTML = settingsData.map(s => `
      <tr class="setting-row" data-id="${s.setting_id}">
        <td><span class="group-badge ${groupClass(s.setting_group)}">${s.setting_group}</span></td>
        <td><span class="key-text">${s.setting_key}</span></td>
        <td><span class="label-text">${s.setting_label||'—'}</span></td>
        <td class="value-cell"><span class="value-text" title="${s.setting_value||''}">${s.setting_value||'—'}</span></td>
        <td>${s.property_id?`<span class="scope-badge">${s.property_name}</span>`:`<span class="scope-badge global">Global</span>`}</td>
        <td style="text-align:center"><span class="pub-badge ${s.is_public==1?'yes':'no'}">${s.is_public==1?'Public':'Private'}</span></td>
        <td>
          <div class="actions-cell">
            <button class="action-btn" onclick="openEditModal(${s.setting_id})">
              <i class="fas fa-edit"></i> Edit
            </button>
            <button class="action-btn danger" onclick="confirmDelete(${s.setting_id},'${s.setting_key}')">
              <i class="fas fa-trash"></i> Delete
            </button>
          </div>
        </td>
      </tr>`).join('');
  }

  function populateDropdowns() {
    const opts = '<option value="">Global (all properties)</option>' + propertiesData.map(p => `<option value="${p.property_id}">${p.property_name}</option>`).join('');
    ['addProperty', 'editProperty'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.innerHTML = opts;
    });
  }

  function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#settingsTableBody .setting-row').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchSettings();
    fetchRelated();
  });
</script>

<div class="page">
  
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total settings</div>
      <div class="stat-value" id="statTotal">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Global</div>
      <div class="stat-value blue" id="statGlobal">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Property-specific</div>
      <div class="stat-value green" id="statProperty">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Public</div>
      <div class="stat-value purple" id="statPublic">—</div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <span class="card-title">Settings</span>
      <div class="toolbar">
        <div class="search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" class="search-input" id="searchInput" placeholder="Search settings…" oninput="filterTable()">
        </div>
        <button class="btn" onclick="showToast('Export coming soon', 'success')"><i class="fas fa-download"></i> Export</button>
        <button class="btn" onclick="showToast('Import coming soon', 'success')"><i class="fas fa-upload"></i> Import</button>
        <button class="btn" onclick="showToast('Settings reset', 'success')"><i class="fas fa-refresh"></i> Reset</button>
        <button class="btn btn-primary" onclick="openAddModal()"><i class="fas fa-plus"></i> New setting</button>
      </div>
    </div>
    <div class="table-wrap">
      <table id="settingsTable">
        <thead>
          <tr>
            <th>Group</th>
            <th>Key</th>
            <th>Label</th>
            <th>Value</th>
            <th>Scope</th>
            <th class="center">Visibility</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="settingsTableBody">
          <tr>
            <td colspan="7" class="empty-state">
              <i class="fas fa-loader-2"></i>
              Loading settings…
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="pagination">
      <div class="entries-select">
        <label>Show</label>
        <select id="entriesPerPage" onchange="changeEntriesPerPage()">
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
        <span>entries</span>
      </div>
      <div class="pagination-controls">
        <span class="pagination-info" id="paginationInfo">Showing 0 to 0 of 0 entries</span>
        <button class="pagination-btn" id="prevBtn" onclick="changePage(-1)" disabled>
          <i class="fas fa-chevron-left"></i> Previous
        </button>
        <div id="pageNumbers" style="display:flex;gap:4px"></div>
        <button class="pagination-btn" id="nextBtn" onclick="changePage(1)" disabled>
          Next <i class="fas fa-chevron-right"></i>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="overlay" id="addOverlay" onclick="handleOverlayClick(event,'addOverlay')">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addModalTitle">
    <div class="modal-header">
      <span class="modal-title" id="addModalTitle">New setting</span>
      <button class="modal-close" onclick="closeAddModal()" aria-label="Close">×</button>
    </div>
    <form id="addForm" onsubmit="submitAdd(event)">
      <div class="modal-body">
        <div class="form-grid">
          <div class="section-label">Scope</div>
          <div class="form-group">
            <label>Property</label>
            <select name="property_id" id="addProperty">
              <option value="">Global (all properties)</option>
            </select>
          </div>
          <div class="form-group">
            <label>Group <span class="req">*</span></label>
            <select name="setting_group" required>
              <option value="">Select group</option>
              <option value="booking">Booking</option>
              <option value="payment">Payment</option>
              <option value="notifications">Notifications</option>
              <option value="display">Display</option>
              <option value="security">Security</option>
            </select>
          </div>
          <hr class="divider">
          <div class="section-label">Metadata</div>
          <div class="form-group">
            <label>Setting key <span class="req">*</span></label>
            <input type="text" name="setting_key" required placeholder="e.g. max_booking_days">
          </div>
          <div class="form-group">
            <label>Label <span class="req">*</span></label>
            <input type="text" name="setting_label" required placeholder="e.g. Maximum booking days">
          </div>
          <div class="form-group">
            <label>Data type <span class="req">*</span></label>
            <select name="data_type" required>
              <option value="string">String</option>
              <option value="integer">Integer</option>
              <option value="decimal">Decimal</option>
              <option value="boolean">Boolean</option>
              <option value="json">JSON</option>
              <option value="time">Time</option>
              <option value="date">Date</option>
            </select>
          </div>
          <div class="form-group">
            <label>Visibility</label>
            <select name="is_public">
              <option value="0">Private</option>
              <option value="1">Public</option>
            </select>
          </div>
          <hr class="divider">
          <div class="section-label">Content</div>
          <div class="form-group full">
            <label>Value <span class="req">*</span></label>
            <textarea name="setting_value" required placeholder="Setting value…" rows="3"></textarea>
          </div>
          <div class="form-group full">
            <label>Description</label>
            <textarea name="description" placeholder="Optional description…" rows="2"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" onclick="closeAddModal()">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-device-floppy"></i> Create setting</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="overlay" id="editOverlay" onclick="handleOverlayClick(event,'editOverlay')">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="editModalTitle">
    <div class="modal-header">
      <span class="modal-title" id="editModalTitle">Edit setting</span>
      <button class="modal-close" onclick="closeEditModal()" aria-label="Close">×</button>
    </div>
    <form id="editForm" onsubmit="submitEdit(event)">
      <div class="modal-body">
        <div class="form-grid">
          <input type="hidden" name="setting_id" id="editSettingId">
          <div class="section-label">Scope</div>
          <div class="form-group">
            <label>Property</label>
            <select name="property_id" id="editProperty">
              <option value="">Global (all properties)</option>
            </select>
          </div>
          <div class="form-group">
            <label>Group <span class="req">*</span></label>
            <select name="setting_group" required>
              <option value="">Select group</option>
              <option value="booking">Booking</option>
              <option value="payment">Payment</option>
              <option value="notifications">Notifications</option>
              <option value="display">Display</option>
              <option value="security">Security</option>
            </select>
          </div>
          <hr class="divider">
          <div class="section-label">Metadata</div>
          <div class="form-group">
            <label>Setting key <span class="req">*</span></label>
            <input type="text" name="setting_key" required placeholder="e.g. max_booking_days">
          </div>
          <div class="form-group">
            <label>Label <span class="req">*</span></label>
            <input type="text" name="setting_label" required placeholder="e.g. Maximum booking days">
          </div>
          <div class="form-group">
            <label>Data type <span class="req">*</span></label>
            <select name="data_type" required>
              <option value="string">String</option>
              <option value="integer">Integer</option>
              <option value="decimal">Decimal</option>
              <option value="boolean">Boolean</option>
              <option value="json">JSON</option>
              <option value="time">Time</option>
              <option value="date">Date</option>
            </select>
          </div>
          <div class="form-group">
            <label>Visibility</label>
            <select name="is_public">
              <option value="0">Private</option>
              <option value="1">Public</option>
            </select>
          </div>
          <hr class="divider">
          <div class="section-label">Content</div>
          <div class="form-group full">
            <label>Value <span class="req">*</span></label>
            <textarea name="setting_value" required placeholder="Setting value…" rows="3"></textarea>
          </div>
          <div class="form-group full">
            <label>Description</label>
            <textarea name="description" placeholder="Optional description…" rows="2"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" onclick="closeEditModal()">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-device-floppy"></i> Update setting</button>
      </div>
    </form>
  </div>
</div>

<!-- Toast -->
<div class="toast" id="toast" role="status" aria-live="polite"></div>

<script>
  function openAddModal() {
    document.getElementById('addOverlay').classList.add('open')
  }

  function closeAddModal() {
    document.getElementById('addOverlay').classList.remove('open');
    document.getElementById('addForm').reset();
  }

  function openEditModal(id) {
    const s = settingsData.find(x => x.setting_id == id);
    if (!s) return;
    const f = document.getElementById('editForm');
    document.getElementById('editSettingId').value = s.setting_id;
    f.property_id.value = s.property_id || '';
    f.setting_group.value = s.setting_group;
    f.setting_key.value = s.setting_key;
    f.setting_label.value = s.setting_label;
    f.data_type.value = s.data_type;
    f.is_public.value = s.is_public;
    f.setting_value.value = s.setting_value || '';
    f.description.value = s.description || '';
    document.getElementById('editOverlay').classList.add('open');
  }

  function closeEditModal() {
    document.getElementById('editOverlay').classList.remove('open');
    document.getElementById('editForm').reset();
  }

  function handleOverlayClick(e, id) {
    if (e.target === document.getElementById(id)) document.getElementById(id).classList.remove('open');
  }

  function submitAdd(e) {
    e.preventDefault();
    const f = document.getElementById('addForm');
    const payload = {
      property_id: f.elements['property_id'].value || null,
      setting_group: f.elements['setting_group'].value,
      setting_key: f.elements['setting_key'].value,
      setting_label: f.elements['setting_label'].value,
      data_type: f.elements['data_type'].value,
      is_public: parseInt(f.elements['is_public'].value),
      setting_value: f.elements['setting_value'].value || null,
      description: f.elements['description'].value || null
    };
    fetch('../api/system_settings.php?action=create', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddModal();
        if (data.success) {
          fetchSettings();
          showToast('Setting created', 'success');
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => {
        closeAddModal();
        showToast('An error occurred while creating the setting', 'error');
      });
  }

  function submitEdit(e) {
    e.preventDefault();
    const f = document.getElementById('editForm');
    const payload = {
      setting_id: document.getElementById('editSettingId').value,
      property_id: f.elements['property_id'].value || null,
      setting_group: f.elements['setting_group'].value,
      setting_key: f.elements['setting_key'].value,
      setting_label: f.elements['setting_label'].value,
      data_type: f.elements['data_type'].value,
      is_public: parseInt(f.elements['is_public'].value),
      setting_value: f.elements['setting_value'].value || null,
      description: f.elements['description'].value || null
    };
    fetch('../api/system_settings.php?action=update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditModal();
        if (data.success) {
          fetchSettings();
          showToast('Setting updated', 'success');
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => {
        closeEditModal();
        showToast('An error occurred while updating the setting', 'error');
      });
  }

  function confirmDelete(id, key) {
    if (!confirm(`Delete setting "${key}"? This cannot be undone.`)) return;
    fetch('../api/system_settings.php?action=delete', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          setting_id: id
        })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          fetchSettings();
          showToast('Setting deleted', 'success');
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => {
        showToast('An error occurred while deleting the setting', 'error');
      });
  }

  let toastTimer;

  function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'toast ' + type + ' show';
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
      t.classList.remove('show')
    }, 3000);
  }
</script>