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

  .stat-value.red {
    color: #A32D2D
  }

  .stat-value.orange {
    color: #C75100
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

  .status-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500
  }

  .status-badge.active {
    background: #EAF3DE;
    color: #27500A
  }

  .status-badge.inactive {
    background: #F1EFE8;
    color: #5F5E5A
  }

  .policy-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500
  }

  .policy-badge.cancellation {
    background: #E6F1FB;
    color: #0C447C
  }

  .policy-badge.deposit {
    background: #FCEBEB;
    color: #791F1F
  }

  .policy-badge.no_show {
    background: #F1EFE8;
    color: #5F5E5A
  }

  .policy-badge.minimum_stay {
    background: #EAF3DE;
    color: #27500A
  }

  .policy-badge.advance_booking {
    background: #F3E6F6;
    color: #534AB7
  }

  .policy-badge.child_policy {
    background: #FEF6E6;
    color: #C75100
  }

  .policy-badge.pet_policy {
    background: #F6E6E6;
    color: #A32D2D
  }

  .policy-badge.early_checkout {
    background: #E6F1FB;
    color: #0C447C
  }

  .policy-badge.late_checkout {
    background: #FCEBEB;
    color: #791F1F
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
    max-width: 800px;
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

  /* ── Responsive ──────────────────────────────────── */
  @media (max-width: 640px) {
    .stats-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .stat-card {
      padding: 14px 16px;
    }
  }

  @media (max-width: 640px) {
    .stat-label {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .stat-value {
      font-size: 24px;
    }
  }

  @media (max-width: 480px) {
    .page {
      padding: 16px;
    }
  }

  @media (max-width: 768px) {
    .page-header {
      flex-direction: column;
      align-items: stretch;
      gap: 16px;
    }
  }

  @media (max-width: 768px) {
    .card-header {
      flex-direction: column;
      align-items: stretch;
      gap: 16px;
      padding: 16px 18px;
    }
  }

  @media (max-width: 640px) {
    .toolbar {
      flex-direction: column;
      align-items: stretch;
      gap: 8px;
    }
  }

  @media (max-width: 640px) {
    .search-input {
      width: 100%;
    }
  }

  @media (max-width: 480px) {
    .btn {
      width: 100%;
      justify-content: center;
    }
  }

  @media (max-width: 640px) {
    thead th {
      padding: 8px 12px;
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    td {
      padding: 8px 12px;
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .status-badge,
    .policy-badge {
      font-size: 10px;
      padding: 2px 8px;
    }
  }

  @media (max-width: 640px) {
    .action-btn {
      padding: 4px 8px;
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .actions-cell {
      flex-direction: column;
      gap: 4px;
    }
  }

  @media (max-width: 640px) {
    .empty-state {
      padding: 40px 20px;
    }
  }

  @media (max-width: 640px) {
    .empty-state i {
      font-size: 28px;
    }
  }

  @media (max-width: 768px) {
    .modal {
      max-width: 90vw;
    }
  }

  @media (max-width: 480px) {
    .modal {
      max-width: none;
      max-height: 95vh;
      border-radius: 12px;
    }
  }

  @media (max-width: 768px) {
    .modal-body {
      padding: 18px;
    }
  }

  @media (max-width: 480px) {
    .modal-body {
      padding: 16px;
      overflow-y: auto;
      overflow-x: hidden;
    }
  }

  @media (max-width: 480px) {
    .modal-footer {
      flex-direction: column;
    }
  }

  @media (max-width: 480px) {
    .modal-footer .btn {
      width: 100%;
      justify-content: center;
    }
  }

  @media (max-width: 640px) {
    .modal-header {
      padding: 14px 18px;
    }
  }

  @media (max-width: 640px) {
    .modal-title {
      font-size: 14px;
    }
  }

  @media (max-width: 640px) {
    .modal-close {
      font-size: 18px;
    }
  }

  @media (max-width: 640px) {
    .form-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    label {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    input,
    select,
    textarea {
      font-size: 12px;
      padding: 7px 10px;
    }
  }

  @media (max-width: 640px) {
    .pagination {
      flex-direction: column;
      gap: 12px;
      padding: 16px 18px;
    }
  }

  @media (max-width: 640px) {
    .pagination-controls {
      flex-wrap: wrap;
      justify-content: center;
    }
  }

  @media (max-width: 640px) {
    .pagination-btn {
      padding: 5px 10px;
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .entries-select {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .toast {
      left: 16px;
      right: 16px;
      bottom: 16px;
      font-size: 12px;
    }
  }
</style>

<script>
  let bookingPoliciesData = [];
  let propertiesData = [];
  let categoriesData = [];
  let pagination = {
    page: 1,
    limit: 10,
    total: 0,
    totalPages: 0
  };

  async function fetchBookingPolicies() {
    try {
      const res = await fetch(`../api/booking_policies.php?action=list&page=${pagination.page}&limit=${pagination.limit}`);
      const json = await res.json();
      if (json.success) {
        bookingPoliciesData = json.data;
        pagination = json.pagination;
        renderStats();
        renderTable();
        renderPagination();
      }
    } catch (e) {
      console.error('Error fetching booking policies:', e);
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/booking_policies.php?action=related');
      const json = await res.json();
      if (json.success) {
        propertiesData = json.data.properties;
        categoriesData = json.data.categories;
        populateDropdowns();
      }
    } catch (e) {
      console.error('Error fetching related data:', e);
    }
  }

  function policyClass(t) {
    return ['cancellation', 'deposit', 'no_show', 'minimum_stay', 'advance_booking', 'child_policy', 'pet_policy', 'early_checkout', 'late_checkout'].includes(t) ? t : 'cancellation'
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = pagination.total;
    document.getElementById('statActive').textContent = bookingPoliciesData.filter(p => p.is_active == 1).length;
    document.getElementById('statCancel').textContent = bookingPoliciesData.filter(p => p.policy_type == 'cancellation').length;
    document.getElementById('statDeposit').textContent = bookingPoliciesData.filter(p => p.policy_type == 'deposit').length;
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
      fetchBookingPolicies();
    }
  }

  function goToPage(page) {
    pagination.page = page;
    fetchBookingPolicies();
  }

  function changeEntriesPerPage() {
    pagination.limit = parseInt(document.getElementById('entriesPerPage').value);
    pagination.page = 1;
    fetchBookingPolicies();
  }

  function renderTable() {
    const tbody = document.getElementById('bookingPoliciesTableBody');
    if (!bookingPoliciesData.length) {
      tbody.innerHTML = `<tr><td colspan="7" class="empty-state"><i class="fas fa-database-off"></i>No booking policies found.</td></tr>`;
      return;
    }
    tbody.innerHTML = bookingPoliciesData.map(p => `
      <tr class="booking-policy-row" data-id="${p.policy_id}">
        <td><span class="label-text">${p.policy_name}</span></td>
        <td><span class="policy-badge ${policyClass(p.policy_type)}">${p.policy_type}</span></td>
        <td><span class="value-text">${p.property_name || 'All Properties'}</span></td>
        <td><span class="value-text">${p.category_name || 'All Categories'}</span></td>
        <td><span class="value-text">${p.description || '—'}</span></td>
        <td style="text-align:center"><span class="status-badge ${p.is_active?'active':'inactive'}">${p.is_active?'Active':'Inactive'}</span></td>
        <td>
          <div class="actions-cell">
            <button class="action-btn" onclick="openEditModal('${p.policy_id}')">
              <i class="fas fa-edit"></i> Edit
            </button>
            <button class="action-btn danger" onclick="confirmDelete('${p.policy_id}','${p.policy_name}')">
              <i class="fas fa-trash"></i> Delete
            </button>
          </div>
        </td>
      </tr>`).join('');
  }

  function populateDropdowns() {
    const propOpts = '<option value="">All Properties</option>' + propertiesData.map(p => `<option value="${p.property_id}">${p.property_name}</option>`).join('');
    const catOpts = '<option value="">All Categories</option>' + categoriesData.map(c => `<option value="${c.category_id}">${c.category_name}</option>`).join('');
    ['addProperty', 'editProperty'].forEach(id => { const el = document.getElementById(id); if (el) el.innerHTML = propOpts; });
    ['addCategory', 'editCategory'].forEach(id => { const el = document.getElementById(id); if (el) el.innerHTML = catOpts; });
  }

  function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#bookingPoliciesTableBody .booking-policy-row').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchBookingPolicies();
    fetchRelated();
  });
</script>

<div class="page">
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total policies</div>
      <div class="stat-value" id="statTotal">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Active</div>
      <div class="stat-value green" id="statActive">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Cancellation</div>
      <div class="stat-value blue" id="statCancel">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Deposit</div>
      <div class="stat-value orange" id="statDeposit">—</div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <span class="card-title">Booking policies</span>
      <div class="toolbar">
        <div class="search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" class="search-input" id="searchInput" placeholder="Search policies…" oninput="filterTable()">
        </div>
        <button class="btn" onclick="showToast('Export coming soon', 'success')"><i class="fas fa-download"></i> Export</button>
        <button class="btn" onclick="showToast('Duplicate coming soon', 'success')"><i class="fas fa-copy"></i> Duplicate</button>
        <button class="btn" onclick="showToast('Bulk actions coming soon', 'success')"><i class="fas fa-check"></i> Activate All</button>
        <button class="btn btn-primary" onclick="openAddModal()"><i class="fas fa-plus"></i> New policy</button>
      </div>
    </div>
    <div class="table-wrap">
      <table id="policiesTable">
        <thead>
          <tr>
            <th>Policy name</th>
            <th>Type</th>
            <th>Property</th>
            <th>Category</th>
            <th>Description</th>
            <th class="center">Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="bookingPoliciesTableBody">
          <tr><td colspan="7" class="empty-state">
            <i class="fas fa-loader-2"></i>
            Loading policies…
          </td></tr>
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
      <span class="modal-title" id="addModalTitle">New booking policy</span>
      <button class="modal-close" onclick="closeAddModal()" aria-label="Close">×</button>
    </div>
    <form id="addForm" onsubmit="submitAdd(event)">
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label>Property</label>
            <select name="property_id" id="addProperty">
              <option value="">All Properties</option>
            </select>
          </div>
          <div class="form-group">
            <label>Category</label>
            <select name="category_id" id="addCategory">
              <option value="">All Categories</option>
            </select>
          </div>
          <div class="form-group">
            <label>Policy type <span class="req">*</span></label>
            <select name="policy_type" required>
              <option value="cancellation">Cancellation</option>
              <option value="deposit">Deposit</option>
              <option value="no_show">No Show</option>
              <option value="early_checkout">Early Checkout</option>
              <option value="late_checkout">Late Checkout</option>
              <option value="minimum_stay">Minimum Stay</option>
              <option value="advance_booking">Advance Booking</option>
              <option value="child_policy">Child Policy</option>
              <option value="pet_policy">Pet Policy</option>
            </select>
          </div>
          <div class="form-group">
            <label>Policy name <span class="req">*</span></label>
            <input type="text" name="policy_name" required placeholder="e.g. Standard Cancellation Policy">
          </div>
          <div class="form-group full">
            <label>Description <span class="req">*</span></label>
            <textarea name="description" required placeholder="Policy description..."></textarea>
          </div>
          <div class="form-group">
            <label>Free cancel hours</label>
            <input type="number" name="free_cancel_hours" placeholder="e.g. 24">
          </div>
          <div class="form-group">
            <label>Penalty type</label>
            <select name="penalty_type">
              <option value="">Select type</option>
              <option value="percentage">Percentage</option>
              <option value="fixed">Fixed</option>
              <option value="first_night">First Night</option>
              <option value="full_stay">Full Stay</option>
            </select>
          </div>
          <div class="form-group">
            <label>Penalty value</label>
            <input type="number" step="0.01" name="penalty_value" placeholder="e.g. 10.00">
          </div>
          <div class="form-group">
            <label>Deposit type</label>
            <select name="deposit_type">
              <option value="">Select type</option>
              <option value="percentage">Percentage</option>
              <option value="fixed">Fixed</option>
              <option value="first_night">First Night</option>
            </select>
          </div>
          <div class="form-group">
            <label>Deposit value</label>
            <input type="number" step="0.01" name="deposit_value" placeholder="e.g. 20.00">
          </div>
          <div class="form-group">
            <label>Min nights</label>
            <input type="number" name="min_nights" placeholder="e.g. 2">
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="is_active">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" onclick="closeAddModal()">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-device-floppy"></i> Create policy</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="overlay" id="editOverlay" onclick="handleOverlayClick(event,'editOverlay')">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="editModalTitle">
    <div class="modal-header">
      <span class="modal-title" id="editModalTitle">Edit booking policy</span>
      <button class="modal-close" onclick="closeEditModal()" aria-label="Close">×</button>
    </div>
    <form id="editForm" onsubmit="submitEdit(event)">
      <div class="modal-body">
        <div class="form-grid">
          <input type="hidden" name="policy_id" id="editPolicyId">
          <div class="form-group">
            <label>Property</label>
            <select name="property_id" id="editProperty">
              <option value="">All Properties</option>
            </select>
          </div>
          <div class="form-group">
            <label>Category</label>
            <select name="category_id" id="editCategory">
              <option value="">All Categories</option>
            </select>
          </div>
          <div class="form-group">
            <label>Policy type <span class="req">*</span></label>
            <select name="policy_type" required>
              <option value="cancellation">Cancellation</option>
              <option value="deposit">Deposit</option>
              <option value="no_show">No Show</option>
              <option value="early_checkout">Early Checkout</option>
              <option value="late_checkout">Late Checkout</option>
              <option value="minimum_stay">Minimum Stay</option>
              <option value="advance_booking">Advance Booking</option>
              <option value="child_policy">Child Policy</option>
              <option value="pet_policy">Pet Policy</option>
            </select>
          </div>
          <div class="form-group">
            <label>Policy name <span class="req">*</span></label>
            <input type="text" name="policy_name" required placeholder="e.g. Standard Cancellation Policy">
          </div>
          <div class="form-group full">
            <label>Description <span class="req">*</span></label>
            <textarea name="description" required placeholder="Policy description..."></textarea>
          </div>
          <div class="form-group">
            <label>Free cancel hours</label>
            <input type="number" name="free_cancel_hours" placeholder="e.g. 24">
          </div>
          <div class="form-group">
            <label>Penalty type</label>
            <select name="penalty_type">
              <option value="">Select type</option>
              <option value="percentage">Percentage</option>
              <option value="fixed">Fixed</option>
              <option value="first_night">First Night</option>
              <option value="full_stay">Full Stay</option>
            </select>
          </div>
          <div class="form-group">
            <label>Penalty value</label>
            <input type="number" step="0.01" name="penalty_value" placeholder="e.g. 10.00">
          </div>
          <div class="form-group">
            <label>Deposit type</label>
            <select name="deposit_type">
              <option value="">Select type</option>
              <option value="percentage">Percentage</option>
              <option value="fixed">Fixed</option>
              <option value="first_night">First Night</option>
            </select>
          </div>
          <div class="form-group">
            <label>Deposit value</label>
            <input type="number" step="0.01" name="deposit_value" placeholder="e.g. 20.00">
          </div>
          <div class="form-group">
            <label>Min nights</label>
            <input type="number" name="min_nights" placeholder="e.g. 2">
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="is_active">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" onclick="closeEditModal()">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-device-floppy"></i> Update policy</button>
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
    const p = bookingPoliciesData.find(x => x.policy_id == id);
    if (!p) return;
    const f = document.getElementById('editForm');
    document.getElementById('editPolicyId').value = p.policy_id;
    f.property_id.value = p.property_id || '';
    f.category_id.value = p.category_id || '';
    f.policy_type.value = p.policy_type;
    f.policy_name.value = p.policy_name;
    f.description.value = p.description;
    f.free_cancel_hours.value = p.free_cancel_hours || '';
    f.penalty_type.value = p.penalty_type || '';
    f.penalty_value.value = p.penalty_value || '';
    f.deposit_type.value = p.deposit_type || '';
    f.deposit_value.value = p.deposit_value || '';
    f.min_nights.value = p.min_nights || '';
    f.is_active.value = p.is_active;
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
      category_id: f.elements['category_id'].value || null,
      policy_name: f.elements['policy_name'].value,
      policy_type: f.elements['policy_type'].value,
      description: f.elements['description'].value,
      free_cancel_hours: f.elements['free_cancel_hours'].value || null,
      penalty_type: f.elements['penalty_type'].value || null,
      penalty_value: f.elements['penalty_value'].value || null,
      deposit_type: f.elements['deposit_type'].value || null,
      deposit_value: f.elements['deposit_value'].value || null,
      min_nights: f.elements['min_nights'].value || null,
      is_active: f.elements['is_active'].value
    };
    fetch('../api/booking_policies.php?action=create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>' },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddModal();
        if (data.success) {
          fetchBookingPolicies();
          showToast('Policy created', 'success');
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => {
        closeAddModal();
        showToast('An error occurred while creating the policy', 'error');
      });
  }

  function submitEdit(e) {
    e.preventDefault();
    const f = document.getElementById('editForm');
    const payload = {
      policy_id: document.getElementById('editPolicyId').value,
      property_id: f.elements['property_id'].value || null,
      category_id: f.elements['category_id'].value || null,
      policy_name: f.elements['policy_name'].value,
      policy_type: f.elements['policy_type'].value,
      description: f.elements['description'].value,
      free_cancel_hours: f.elements['free_cancel_hours'].value || null,
      penalty_type: f.elements['penalty_type'].value || null,
      penalty_value: f.elements['penalty_value'].value || null,
      deposit_type: f.elements['deposit_type'].value || null,
      deposit_value: f.elements['deposit_value'].value || null,
      min_nights: f.elements['min_nights'].value || null,
      is_active: f.elements['is_active'].value
    };
    fetch('../api/booking_policies.php?action=update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>' },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditModal();
        if (data.success) {
          fetchBookingPolicies();
          showToast('Policy updated', 'success');
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => {
        closeEditModal();
        showToast('An error occurred while updating the policy', 'error');
      });
  }

  function confirmDelete(id, name) {
    if (!confirm(`Delete policy "${name}"? This cannot be undone.`)) return;
    fetch('../api/booking_policies.php?action=delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>' },
        body: JSON.stringify({ policy_id: id })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          fetchBookingPolicies();
          showToast('Policy deleted', 'success');
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => {
        showToast('An error occurred while deleting the policy', 'error');
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