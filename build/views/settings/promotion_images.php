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

  .preview-cell {
    display: flex;
    align-items: center;
    gap: 10px
  }

  .preview-thumb {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 6px;
    background: #f3f4f6
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

  .type-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500
  }

  .type-badge.banner {
    background: #E6F1FB;
    color: #0C447C
  }

  .type-badge.thumbnail {
    background: #F3E6F6;
    color: #534AB7
  }

  .type-badge.detail {
    background: #EAF3DE;
    color: #27500A
  }

  .type-badge.other {
    background: #F1EFE8;
    color: #5F5E5A
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
</style>

<script>
  let promotionImagesData = [];
  let promotionsData = [];
  let pagination = {
    page: 1,
    limit: 10,
    total: 0,
    totalPages: 0
  };

  async function fetchPromotionImages() {
    try {
      const res = await fetch(`../api/promotion_images.php?action=list&page=${pagination.page}&limit=${pagination.limit}`);
      const json = await res.json();
      if (json.success) {
        promotionImagesData = json.data;
        pagination = json.pagination;
        renderStats();
        renderTable();
        renderPagination();
      }
    } catch (e) {
      console.error('Error fetching promotion images:', e);
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/promotion_images.php?action=related');
      const json = await res.json();
      if (json.success) {
        promotionsData = json.data.promotions;
        populateDropdowns();
      }
    } catch (e) {
      console.error('Error fetching related data:', e);
    }
  }

  function imageClass(t) {
    return ['banner', 'thumbnail', 'detail', 'other'].includes(t) ? t : 'other'
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = pagination.total;
    document.getElementById('statActive').textContent = promotionImagesData.filter(i => i.is_active == 1).length;
    document.getElementById('statBanner').textContent = promotionImagesData.filter(i => i.image_type == 'banner').length;
    document.getElementById('statThumb').textContent = promotionImagesData.filter(i => i.image_type == 'thumbnail').length;
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
      fetchPromotionImages();
    }
  }

  function goToPage(page) {
    pagination.page = page;
    fetchPromotionImages();
  }

  function changeEntriesPerPage() {
    pagination.limit = parseInt(document.getElementById('entriesPerPage').value);
    pagination.page = 1;
    fetchPromotionImages();
  }

  function renderTable() {
    const tbody = document.getElementById('promotionImagesTableBody');
    if (!promotionImagesData.length) {
      tbody.innerHTML = `<tr><td colspan="6" class="empty-state"><i class="fas fa-database-off"></i>No promotion images found.</td></tr>`;
      return;
    }
    tbody.innerHTML = promotionImagesData.map(img => `
      <tr class="promotion-image-row" data-id="${img.promo_image_id}">
        <td>
          <div class="preview-cell">
            <img src="${img.image_url || ''}" alt="" class="preview-thumb" onerror="this.src='https://placehold.co/40x40?text=N/A'">
            <span class="label-text">${img.image_url || '—'}</span>
          </div>
        </td>
        <td><span class="value-text">${img.promo_title || '—'}</span></td>
        <td><span class="type-badge ${imageClass(img.image_type)}">${img.image_type}</span></td>
        <td><span class="value-text">${img.sort_order || 0}</span></td>
        <td style="text-align:center"><span class="status-badge ${img.is_active?'active':'inactive'}">${img.is_active?'Active':'Inactive'}</span></td>
        <td>
          <div class="actions-cell">
            <button class="action-btn danger" onclick="confirmDelete('${img.promo_image_id}','${img.image_url}')">
              <i class="fas fa-trash"></i> Delete
            </button>
          </div>
        </td>
      </tr>`).join('');
  }

  function populateDropdowns() {
    const promoOpts = '<option value="">Select Promotion</option>' + promotionsData.map(p => `<option value="${p.promo_id}">${p.promo_title}</option>`).join('');
    ['addPromotion', 'editPromotion'].forEach(id => { const el = document.getElementById(id); if (el) el.innerHTML = promoOpts; });
  }

  function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#promotionImagesTableBody .promotion-image-row').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchPromotionImages();
    fetchRelated();
  });
</script>

<div class="page">
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total images</div>
      <div class="stat-value" id="statTotal">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Active images</div>
      <div class="stat-value green" id="statActive">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Banner images</div>
      <div class="stat-value blue" id="statBanner">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Thumbnail images</div>
      <div class="stat-value purple" id="statThumb">—</div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <span class="card-title">Promotion images</span>
      <div class="toolbar">
        <div class="search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" class="search-input" id="searchInput" placeholder="Search images…" oninput="filterTable()">
        </div>
        <button class="btn" onclick="showToast('Bulk upload coming soon', 'success')"><i class="fas fa-upload"></i> Bulk upload</button>
        <button class="btn" onclick="showToast('Reorder coming soon', 'success')"><i class="fas fa-sort"></i> Reorder</button>
        <button class="btn btn-primary" onclick="openAddModal()"><i class="fas fa-plus"></i> New image</button>
      </div>
    </div>
    <div class="table-wrap">
      <table id="imagesTable">
        <thead>
          <tr>
            <th>Preview</th>
            <th>Promotion</th>
            <th>Type</th>
            <th>Sort order</th>
            <th class="center">Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="promotionImagesTableBody">
          <tr><td colspan="6" class="empty-state">
            <i class="fas fa-loader-2"></i>
            Loading promotion images…
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
      <span class="modal-title" id="addModalTitle">New promotion image</span>
      <button class="modal-close" onclick="closeAddModal()" aria-label="Close">×</button>
    </div>
    <form id="addForm" onsubmit="submitAdd(event)">
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label>Promotion <span class="req">*</span></label>
            <select name="promo_id" id="addPromotion" required>
              <option value="">Select Promotion</option>
            </select>
          </div>
          <div class="form-group">
            <label>Image type <span class="req">*</span></label>
            <select name="image_type" required>
              <option value="banner">Banner</option>
              <option value="thumbnail">Thumbnail</option>
              <option value="detail">Detail</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group full">
            <label>Image URL <span class="req">*</span></label>
            <input type="url" name="image_url" required placeholder="https://example.com/image.jpg">
          </div>
          <div class="form-group full">
            <label>Thumbnail URL</label>
            <input type="url" name="thumbnail_url" placeholder="https://example.com/thumb.jpg">
          </div>
          <div class="form-group">
            <label>Sort order <span class="req">*</span></label>
            <input type="number" name="sort_order" required placeholder="e.g. 0">
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
        <button type="submit" class="btn btn-primary"><i class="fas fa-device-floppy"></i> Add image</button>
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

  function handleOverlayClick(e, id) {
    if (e.target === document.getElementById(id)) document.getElementById(id).classList.remove('open');
  }

  function submitAdd(e) {
    e.preventDefault();
    const f = document.getElementById('addForm');
    const payload = {
      promo_id: f.elements['promo_id'].value,
      image_url: f.elements['image_url'].value,
      thumbnail_url: f.elements['thumbnail_url'].value || null,
      image_type: f.elements['image_type'].value,
      sort_order: f.elements['sort_order'].value || 0,
      is_active: f.elements['is_active'].value
    };
    fetch('../api/promotion_images.php?action=create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddModal();
        if (data.success) {
          fetchPromotionImages();
          showToast('Image added', 'success');
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => {
        closeAddModal();
        showToast('An error occurred while adding the image', 'error');
      });
  }

  function confirmDelete(id, name) {
    if (!confirm(`Delete image "${name}"? This cannot be undone.`)) return;
    fetch('../api/promotion_images.php?action=delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ promo_image_id: id })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          fetchPromotionImages();
          showToast('Image deleted', 'success');
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => {
        showToast('An error occurred while deleting the image', 'error');
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