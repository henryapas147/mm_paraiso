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

  .stat-value.yellow {
    color: #C77600
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

  .promo-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500
  }

  .promo-badge.banner {
    background: #E6F1FB;
    color: #0C447C
  }

  .promo-badge.package {
    background: #FCEBEB;
    color: #791F1F
  }

  .promo-badge.seasonal_deal {
    background: #EAF3DE;
    color: #27500A
  }

  .promo-badge.referral {
    background: #F3E6F6;
    color: #534AB7
  }

  .promo-badge.loyalty {
    background: #FEF6E6;
    color: #C75100
  }

  .promo-badge.voucher {
    background: #F6E6E6;
    color: #A32D2D
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
    .promo-badge {
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
  let csrfToken = "<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>";
  let promotionsData = [];
  let propertiesData = [];
  let pagination = {
    page: 1,
    limit: 10,
    total: 0,
    totalPages: 0
  };

  async function fetchPromotions() {
    try {
      const res = await fetch(`../api/promotions.php?action=list&page=${pagination.page}&limit=${pagination.limit}`);
      const json = await res.json();
      if (json.success) {
        promotionsData = json.data;
        pagination = json.pagination;
        renderStats();
        renderTable();
        renderPagination();
      }
    } catch (e) {
      console.error('Error fetching promotions:', e);
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/promotions.php?action=related');
      const json = await res.json();
      if (json.success) {
        propertiesData = json.data.properties;
        populateDropdowns();
      }
    } catch (e) {
      console.error('Error fetching related data:', e);
    }
  }

  function promoClass(t) {
    return ['banner', 'package', 'seasonal_deal', 'referral', 'loyalty', 'voucher'].includes(t) ? t : 'banner'
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = pagination.total;
    document.getElementById('statActive').textContent = promotionsData.filter(p => p.is_active == 1).length;
    document.getElementById('statFeatured').textContent = promotionsData.filter(p => p.is_featured == 1).length;
    document.getElementById('statViews').textContent = promotionsData.reduce((sum, p) => sum + (p.view_count || 0), 0).toLocaleString();
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
      fetchPromotions();
    }
  }

  function goToPage(page) {
    pagination.page = page;
    fetchPromotions();
  }

  function changeEntriesPerPage() {
    pagination.limit = parseInt(document.getElementById('entriesPerPage').value);
    pagination.page = 1;
    fetchPromotions();
  }

  function renderTable() {
    const tbody = document.getElementById('promotionsTableBody');
    if (!promotionsData.length) {
      tbody.innerHTML = `<tr><td colspan="8" class="empty-state"><i class="fas fa-database-off"></i>No promotions found.</td></tr>`;
      return;
    }
    tbody.innerHTML = promotionsData.map(p => `
      <tr class="promotion-row" data-id="${p.promo_id}">
        <td><span class="label-text">${p.promo_title}</span></td>
        <td><span class="value-text">${p.property_name || 'All Properties'}</span></td>
        <td><span class="promo-badge ${promoClass(p.promo_type)}">${p.promo_type}</span></td>
        <td><span class="value-text">${p.valid_from ? new Date(p.valid_from).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '—'}</span></td>
        <td><span class="value-text">${p.valid_until ? new Date(p.valid_until).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '—'}</span></td>
        <td><span class="value-text">${p.view_count || 0}</span></td>
        <td style="text-align:center"><span class="status-badge ${p.is_active?'active':'inactive'}">${p.is_active?'Active':'Inactive'}</span></td>
        <td>
          <div class="actions-cell">
            <button class="action-btn" onclick="openEditModal('${p.promo_id}')">
              <i class="fas fa-edit"></i> Edit
            </button>
            <button class="action-btn danger" onclick="confirmDelete('${p.promo_id}','${p.promo_title}')">
              <i class="fas fa-trash"></i> Delete
            </button>
          </div>
        </td>
      </tr>`).join('');
  }

  function populateDropdowns() {
    const propOpts = '<option value="">All Properties</option>' + propertiesData.map(p => `<option value="${p.property_id}">${p.property_name}</option>`).join('');
    ['addProperty', 'editProperty'].forEach(id => { const el = document.getElementById(id); if (el) el.innerHTML = propOpts; });
  }

  function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#promotionsTableBody .promotion-row').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchPromotions();
    fetchRelated();
  });
</script>

<div class="page">
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total promotions</div>
      <div class="stat-value" id="statTotal">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Active</div>
      <div class="stat-value green" id="statActive">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Featured</div>
      <div class="stat-value yellow" id="statFeatured">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total views</div>
      <div class="stat-value blue" id="statViews">—</div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <span class="card-title">Promotions</span>
      <div class="toolbar">
        <div class="search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" class="search-input" id="searchInput" placeholder="Search promotions…" oninput="filterTable()">
        </div>
        <button class="btn" onclick="showToast('Export coming soon', 'success')"><i class="fas fa-download"></i> Export</button>
        <button class="btn" onclick="showToast('Duplicate coming soon', 'success')"><i class="fas fa-copy"></i> Duplicate</button>
        <button class="btn" onclick="showToast('Feature coming soon', 'success')"><i class="fas fa-star"></i> Feature</button>
        <button class="btn btn-primary" onclick="openAddModal()"><i class="fas fa-plus"></i> New promotion</button>
      </div>
    </div>
    <div class="table-wrap">
      <table id="promosTable">
        <thead>
          <tr>
            <th>Title</th>
            <th>Property</th>
            <th>Type</th>
            <th>Valid from</th>
            <th>Valid until</th>
            <th>Views</th>
            <th class="center">Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="promotionsTableBody">
          <tr><td colspan="8" class="empty-state">
            <i class="fas fa-loader-2"></i>
            Loading promotions…
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
      <span class="modal-title" id="addModalTitle">New promotion</span>
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
            <label>Promo code</label>
            <input type="text" name="promo_code" placeholder="e.g. SUMMER2024">
          </div>
          <div class="form-group full">
            <label>Promo title <span class="req">*</span></label>
            <input type="text" name="promo_title" required placeholder="e.g. Summer Sale 2024">
          </div>
          <div class="form-group">
            <label>Promo type <span class="req">*</span></label>
            <select name="promo_type" required>
              <option value="banner">Banner</option>
              <option value="package">Package</option>
              <option value="seasonal_deal">Seasonal Deal</option>
              <option value="referral">Referral</option>
              <option value="loyalty">Loyalty</option>
              <option value="voucher">Voucher</option>
            </select>
          </div>
          <div class="form-group">
            <label>Discount ID</label>
            <input type="text" name="discount_id" placeholder="e.g. UUID of discount">
          </div>
          <div class="form-group full">
            <label>Short description</label>
            <input type="text" name="short_desc" placeholder="Brief description...">
          </div>
          <div class="form-group full">
            <label>Full description</label>
            <textarea name="full_description" placeholder="Full promotion details..."></textarea>
          </div>
          <div class="form-group full">
            <label>Terms & conditions</label>
            <textarea name="terms_conditions" placeholder="Terms and conditions..."></textarea>
          </div>
          <div class="form-group">
            <label>Valid from <span class="req">*</span></label>
            <input type="date" name="valid_from" required>
          </div>
          <div class="form-group">
            <label>Valid until <span class="req">*</span></label>
            <input type="date" name="valid_until" required>
          </div>
          <div class="form-group">
            <label>Sort order</label>
            <input type="number" name="sort_order" placeholder="e.g. 0">
          </div>
          <div class="form-group">
            <label>Is featured</label>
            <select name="is_featured">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
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
        <button type="submit" class="btn btn-primary"><i class="fas fa-device-floppy"></i> Create promotion</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="overlay" id="editOverlay" onclick="handleOverlayClick(event,'editOverlay')">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="editModalTitle">
    <div class="modal-header">
      <span class="modal-title" id="editModalTitle">Edit promotion</span>
      <button class="modal-close" onclick="closeEditModal()" aria-label="Close">×</button>
    </div>
    <form id="editForm" onsubmit="submitEdit(event)">
      <div class="modal-body">
        <div class="form-grid">
          <input type="hidden" name="promo_id" id="editPromoId">
          <div class="form-group">
            <label>Property</label>
            <select name="property_id" id="editProperty">
              <option value="">All Properties</option>
            </select>
          </div>
          <div class="form-group">
            <label>Promo code</label>
            <input type="text" name="promo_code" placeholder="e.g. SUMMER2024">
          </div>
          <div class="form-group full">
            <label>Promo title <span class="req">*</span></label>
            <input type="text" name="promo_title" required placeholder="e.g. Summer Sale 2024">
          </div>
          <div class="form-group">
            <label>Promo type <span class="req">*</span></label>
            <select name="promo_type" required>
              <option value="banner">Banner</option>
              <option value="package">Package</option>
              <option value="seasonal_deal">Seasonal Deal</option>
              <option value="referral">Referral</option>
              <option value="loyalty">Loyalty</option>
              <option value="voucher">Voucher</option>
            </select>
          </div>
          <div class="form-group">
            <label>Discount ID</label>
            <input type="text" name="discount_id" placeholder="e.g. UUID of discount">
          </div>
          <div class="form-group full">
            <label>Short description</label>
            <input type="text" name="short_desc" placeholder="Brief description...">
          </div>
          <div class="form-group full">
            <label>Full description</label>
            <textarea name="full_description" placeholder="Full promotion details..."></textarea>
          </div>
          <div class="form-group full">
            <label>Terms & conditions</label>
            <textarea name="terms_conditions" placeholder="Terms and conditions..."></textarea>
          </div>
          <div class="form-group">
            <label>Valid from <span class="req">*</span></label>
            <input type="date" name="valid_from" required>
          </div>
          <div class="form-group">
            <label>Valid until <span class="req">*</span></label>
            <input type="date" name="valid_until" required>
          </div>
          <div class="form-group">
            <label>Sort order</label>
            <input type="number" name="sort_order" placeholder="e.g. 0">
          </div>
          <div class="form-group">
            <label>Is featured</label>
            <select name="is_featured">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
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
        <button type="submit" class="btn btn-primary"><i class="fas fa-device-floppy"></i> Update promotion</button>
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
    const p = promotionsData.find(x => x.promo_id == id);
    if (!p) return;
    const f = document.getElementById('editForm');
    document.getElementById('editPromoId').value = p.promo_id;
    f.property_id.value = p.property_id || '';
    f.promo_code.value = p.promo_code || '';
    f.promo_title.value = p.promo_title;
    f.promo_type.value = p.promo_type;
    f.discount_id.value = p.discount_id || '';
    f.short_desc.value = p.short_desc || '';
    f.full_description.value = p.full_description || '';
    f.terms_conditions.value = p.terms_conditions || '';
    f.valid_from.value = p.valid_from;
    f.valid_until.value = p.valid_until;
    f.sort_order.value = p.sort_order;
    f.is_featured.value = p.is_featured;
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
      promo_code: f.elements['promo_code'].value || null,
      promo_title: f.elements['promo_title'].value,
      promo_type: f.elements['promo_type'].value,
      discount_id: f.elements['discount_id'].value || null,
      short_desc: f.elements['short_desc'].value || null,
      full_description: f.elements['full_description'].value || null,
      terms_conditions: f.elements['terms_conditions'].value || null,
      valid_from: f.elements['valid_from'].value,
      valid_until: f.elements['valid_until'].value,
      sort_order: f.elements['sort_order'].value || 0,
      is_featured: f.elements['is_featured'].value,
      is_active: f.elements['is_active'].value
    };
    fetch('../api/promotions.php?action=create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddModal();
        if (data.success) {
          fetchPromotions();
          showToast('Promotion created', 'success');
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => {
        closeAddModal();
        showToast('An error occurred while creating the promotion', 'error');
      });
  }

  function submitEdit(e) {
    e.preventDefault();
    const f = document.getElementById('editForm');
    const payload = {
      promotion_id: document.getElementById('editPromoId').value,
      property_id: f.elements['property_id'].value || null,
      promo_code: f.elements['promo_code'].value || null,
      promo_title: f.elements['promo_title'].value,
      promo_type: f.elements['promo_type'].value,
      discount_id: f.elements['discount_id'].value || null,
      short_desc: f.elements['short_desc'].value || null,
      full_description: f.elements['full_description'].value || null,
      terms_conditions: f.elements['terms_conditions'].value || null,
      valid_from: f.elements['valid_from'].value,
      valid_until: f.elements['valid_until'].value,
      sort_order: f.elements['sort_order'].value || 0,
      is_featured: f.elements['is_featured'].value,
      is_active: f.elements['is_active'].value
    };
    fetch('../api/promotions.php?action=update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditModal();
        if (data.success) {
          fetchPromotions();
          showToast('Promotion updated', 'success');
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => {
        closeEditModal();
        showToast('An error occurred while updating the promotion', 'error');
      });
  }

  function confirmDelete(id, name) {
    if (!confirm(`Delete promotion "${name}"? This cannot be undone.`)) return;
    fetch('../api/promotions.php?action=delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken },
        body: JSON.stringify({ promotion_id: id })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          fetchPromotions();
          showToast('Promotion deleted', 'success');
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => {
        showToast('An error occurred while deleting the promotion', 'error');
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