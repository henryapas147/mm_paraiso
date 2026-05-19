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

  .status-badge.read {
    background: #EAF3DE;
    color: #27500A
  }

  .status-badge.unread {
    background: #F1EFE8;
    color: #5F5E5A
  }

  .delivery-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500
  }

  .delivery-badge.delivered {
    background: #EAF3DE;
    color: #27500A
  }

  .delivery-badge.pending {
    background: #FFF4E6;
    color: #C75100
  }

  .delivery-badge.failed {
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
</style>

<script>
  let recipientsData = [];
  let notificationsData = [];
  let usersData = [];
  let pagination = {
    page: 1,
    limit: 10,
    total: 0,
    totalPages: 0
  };

  async function fetchRecipients() {
    try {
      const res = await fetch(`../api/notification_recipients.php?action=list&page=${pagination.page}&limit=${pagination.limit}`);
      const json = await res.json();
      if (json.success) {
        recipientsData = json.data;
        pagination = json.pagination;
        renderStats();
        renderTable();
        renderPagination();
      }
    } catch (e) {
      console.error('Error fetching recipients:', e);
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/notification_recipients.php?action=related');
      const json = await res.json();
      if (json.success) {
        notificationsData = json.data.notifications;
        usersData = json.data.users;
        populateDropdowns();
      }
    } catch (e) {
      console.error('Error fetching related data:', e);
    }
  }

  function deliveryClass(s) {
    return ['delivered', 'pending', 'failed'].includes(s) ? s : 'pending'
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = pagination.total;
    document.getElementById('statRead').textContent = recipientsData.filter(r => r.is_read == 1).length;
    document.getElementById('statDelivered').textContent = recipientsData.filter(r => r.delivery_status == 'delivered').length;
    document.getElementById('statFailed').textContent = recipientsData.filter(r => r.delivery_status == 'failed').length;
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
      fetchRecipients();
    }
  }

  function goToPage(page) {
    pagination.page = page;
    fetchRecipients();
  }

  function changeEntriesPerPage() {
    pagination.limit = parseInt(document.getElementById('entriesPerPage').value);
    pagination.page = 1;
    fetchRecipients();
  }

  function renderTable() {
    const tbody = document.getElementById('recipientsTableBody');
    if (!recipientsData.length) {
      tbody.innerHTML = `<tr><td colspan="6" class="empty-state"><i class="fas fa-database-off"></i>No recipients found.</td></tr>`;
      return;
    }
    tbody.innerHTML = recipientsData.map(r => `
      <tr class="recipient-row" data-id="${r.recipient_id}">
        <td><span class="label-text">${r.notif_title || '—'}</span></td>
        <td><span class="value-text">${r.first_name || ''} ${r.last_name || ''} (${r.email || '—'})</span></td>
        <td style="text-align:center"><span class="status-badge ${r.is_read ? 'read' : 'unread'}">${r.is_read ? 'Read' : 'Unread'}</span></td>
        <td><span class="value-text">${r.sent_at || '—'}</span></td>
        <td style="text-align:center"><span class="delivery-badge ${deliveryClass(r.delivery_status)}">${r.delivery_status}</span></td>
        <td>
          <div class="actions-cell">
            <button class="action-btn danger" onclick="confirmDelete('${r.recipient_id}','${r.email || 'Unknown'}')">
              <i class="fas fa-trash"></i> Delete
            </button>
          </div>
        </td>
      </tr>`).join('');
  }

  function populateDropdowns() {
    const notifOpts = '<option value="">Select Notification</option>' + notificationsData.map(n => `<option value="${n.notif_id}">${n.title}</option>`).join('');
    const userOpts = '<option value="">Select User</option>' + usersData.map(u => `<option value="${u.user_id}">${u.display || u.email}</option>`).join('');
    ['addNotification', 'editNotification'].forEach(id => { const el = document.getElementById(id); if (el) el.innerHTML = notifOpts; });
    ['addUser', 'editUser'].forEach(id => { const el = document.getElementById(id); if (el) el.innerHTML = userOpts; });
  }

  function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#recipientsTableBody .recipient-row').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchRecipients();
    fetchRelated();
  });
</script>

<div class="page">
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total recipients</div>
      <div class="stat-value" id="statTotal">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Read</div>
      <div class="stat-value green" id="statRead">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Delivered</div>
      <div class="stat-value blue" id="statDelivered">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Failed</div>
      <div class="stat-value red" id="statFailed">—</div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <span class="card-title">Notification recipients</span>
      <div class="toolbar">
        <div class="search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" class="search-input" id="searchInput" placeholder="Search recipients…" oninput="filterTable()">
        </div>
        <button class="btn" onclick="showToast('Mark all read coming soon', 'success')"><i class="fas fa-checks"></i> Mark all read</button>
        <button class="btn" onclick="showToast('Resend failed coming soon', 'success')"><i class="fas fa-refresh"></i> Resend failed</button>
        <button class="btn" onclick="showToast('Export coming soon', 'success')"><i class="fas fa-download"></i> Export</button>
        <button class="btn btn-primary" onclick="openAddModal()"><i class="fas fa-plus"></i> Add recipient</button>
      </div>
    </div>
    <div class="table-wrap">
      <table id="recipientsTable">
        <thead>
          <tr>
            <th>Notification</th>
            <th>User</th>
            <th class="center">Read</th>
            <th>Sent</th>
            <th class="center">Delivery status</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="recipientsTableBody">
          <tr><td colspan="6" class="empty-state">
            <i class="fas fa-loader-2"></i>
            Loading recipients…
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
      <span class="modal-title" id="addModalTitle">Add recipient</span>
      <button class="modal-close" onclick="closeAddModal()" aria-label="Close">×</button>
    </div>
    <form id="addForm" onsubmit="submitAdd(event)">
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label>Notification <span class="req">*</span></label>
            <select name="notif_id" id="addNotification" required>
              <option value="">Select Notification</option>
            </select>
          </div>
          <div class="form-group">
            <label>User <span class="req">*</span></label>
            <select name="user_id" id="addUser" required>
              <option value="">Select User</option>
            </select>
          </div>
          <div class="form-group">
            <label>Is read</label>
            <select name="is_read">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>
          <div class="form-group">
            <label>Is sent</label>
            <select name="is_sent">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>
          <div class="form-group">
            <label>Delivery status <span class="req">*</span></label>
            <select name="delivery_status" required>
              <option value="pending">Pending</option>
              <option value="delivered">Delivered</option>
              <option value="failed">Failed</option>
            </select>
          </div>
          <div class="form-group">
            <label>Error message</label>
            <input type="text" name="error_message" placeholder="Error message if failed">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" onclick="closeAddModal()">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-device-floppy"></i> Add recipient</button>
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
      notif_id: f.elements['notif_id'].value,
      user_id: f.elements['user_id'].value,
      is_read: f.elements['is_read'].value,
      is_sent: f.elements['is_sent'].value,
      delivery_status: f.elements['delivery_status'].value,
      error_message: f.elements['error_message'].value || null
    };
    fetch('../api/notification_recipients.php?action=create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddModal();
        if (data.success) {
          fetchRecipients();
          showToast('Recipient added', 'success');
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => {
        closeAddModal();
        showToast('An error occurred while adding the recipient', 'error');
      });
  }

  function confirmDelete(id, name) {
    if (!confirm(`Delete recipient "${name}"? This cannot be undone.`)) return;
    fetch('../api/notification_recipients.php?action=delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ recipient_id: id })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          fetchRecipients();
          showToast('Recipient deleted', 'success');
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => {
        showToast('An error occurred while deleting the recipient', 'error');
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