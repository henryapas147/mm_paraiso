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

  .status-badge.sent {
    background: #EAF3DE;
    color: #27500A
  }

  .status-badge.scheduled {
    background: #E6F1FB;
    color: #0C447C
  }

  .status-badge.draft {
    background: #F1EFE8;
    color: #5F5E5A
  }

  .status-badge.cancelled {
    background: #FCEBEB;
    color: #791F1F
  }

  .type-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500
  }

  .type-badge.reservation_confirmed,
  .type-badge.reservation_cancelled {
    background: #E6F1FB;
    color: #0C447C
  }

  .type-badge.check_in_reminder,
  .type-badge.check_out_reminder {
    background: #EAF3DE;
    color: #27500A
  }

  .type-badge.payment_received,
  .type-badge.payment_due {
    background: #F3E6F6;
    color: #534AB7
  }

  .type-badge.promo_announcement,
  .type-badge.custom {
    background: #FFF4E6;
    color: #C75100
  }

  .type-badge.system_alert,
  .type-badge.staff_alert {
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
  let notificationsData = [];
  let propertiesData = [];
  let pagination = {
    page: 1,
    limit: 10,
    total: 0,
    totalPages: 0
  };

  async function fetchNotifications() {
    try {
      const res = await fetch(`../api/notifications.php?action=list&page=${pagination.page}&limit=${pagination.limit}`);
      const json = await res.json();
      if (json.success) {
        notificationsData = json.data;
        pagination = json.pagination;
        renderStats();
        renderTable();
        renderPagination();
      }
    } catch (e) {
      console.error('Error fetching notifications:', e);
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/notifications.php?action=related');
      const json = await res.json();
      if (json.success) {
        propertiesData = json.data.properties;
        populateDropdowns();
      }
    } catch (e) {
      console.error('Error fetching related data:', e);
    }
  }

  function statusClass(s) {
    return ['sent', 'scheduled', 'draft', 'cancelled'].includes(s) ? s : 'draft'
  }

  function typeClass(t) {
    const map = {
      'reservation_confirmed': 'reservation_confirmed',
      'reservation_cancelled': 'reservation_cancelled',
      'check_in_reminder': 'check_in_reminder',
      'check_out_reminder': 'check_out_reminder',
      'payment_received': 'payment_received',
      'payment_due': 'payment_due',
      'promo_announcement': 'promo_announcement',
      'system_alert': 'system_alert',
      'staff_alert': 'staff_alert',
      'custom': 'custom'
    };
    return map[t] || 'custom'
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = pagination.total;
    document.getElementById('statSent').textContent = notificationsData.filter(n => n.status == 'sent').length;
    document.getElementById('statScheduled').textContent = notificationsData.filter(n => n.status == 'scheduled').length;
    document.getElementById('statDraft').textContent = notificationsData.filter(n => n.status == 'draft').length;
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
      fetchNotifications();
    }
  }

  function goToPage(page) {
    pagination.page = page;
    fetchNotifications();
  }

  function changeEntriesPerPage() {
    pagination.limit = parseInt(document.getElementById('entriesPerPage').value);
    pagination.page = 1;
    fetchNotifications();
  }

  function renderTable() {
    const tbody = document.getElementById('notificationsTableBody');
    if (!notificationsData.length) {
      tbody.innerHTML = `<tr><td colspan="6" class="empty-state"><i class="fas fa-database-off"></i>No notifications found.</td></tr>`;
      return;
    }
    tbody.innerHTML = notificationsData.map(n => `
      <tr class="notification-row" data-id="${n.notif_id}">
        <td><span class="label-text">${n.title || '—'}</span></td>
        <td><span class="type-badge ${typeClass(n.notif_type)}">${n.notif_type.replace(/_/g, ' ')}</span></td>
        <td><span class="value-text">${n.property_name || 'All Properties'}</span></td>
        <td><span class="value-text">${n.scheduled_at || '—'}</span></td>
        <td style="text-align:center"><span class="status-badge ${statusClass(n.status)}">${n.status}</span></td>
        <td>
          <div class="actions-cell">
            <button class="action-btn" onclick="openEditModal('${n.notif_id}')">
              <i class="fas fa-pencil"></i> Edit
            </button>
            <button class="action-btn danger" onclick="confirmDelete('${n.notif_id}','${n.title}')">
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
    document.querySelectorAll('#notificationsTableBody .notification-row').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchNotifications();
    fetchRelated();
  });
</script>

<div class="page">
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total notifications</div>
      <div class="stat-value" id="statTotal">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Sent</div>
      <div class="stat-value green" id="statSent">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Scheduled</div>
      <div class="stat-value blue" id="statScheduled">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Draft</div>
      <div class="stat-value" id="statDraft">—</div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <span class="card-title">Notifications</span>
      <div class="toolbar">
        <div class="search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" class="search-input" id="searchInput" placeholder="Search notifications…" oninput="filterTable()">
        </div>
        <button class="btn" onclick="showToast('Send now coming soon', 'success')"><i class="fas fa-send"></i> Send now</button>
        <button class="btn" onclick="showToast('Duplicate coming soon', 'success')"><i class="fas fa-copy"></i> Duplicate</button>
        <button class="btn" onclick="showToast('Cancel scheduled coming soon', 'success')"><i class="fas fa-x"></i> Cancel scheduled</button>
        <button class="btn btn-primary" onclick="openAddModal()"><i class="fas fa-plus"></i> New notification</button>
      </div>
    </div>
    <div class="table-wrap">
      <table id="notifsTable">
        <thead>
          <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Target</th>
            <th>Scheduled</th>
            <th class="center">Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="notificationsTableBody">
          <tr><td colspan="6" class="empty-state">
            <i class="fas fa-loader-2"></i>
            Loading notifications…
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
      <span class="modal-title" id="addModalTitle">New notification</span>
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
            <label>Notification type <span class="req">*</span></label>
            <select name="notif_type" required>
              <option value="reservation_confirmed">Reservation Confirmed</option>
              <option value="reservation_cancelled">Reservation Cancelled</option>
              <option value="check_in_reminder">Check-in Reminder</option>
              <option value="check_out_reminder">Check-out Reminder</option>
              <option value="payment_received">Payment Received</option>
              <option value="payment_due">Payment Due</option>
              <option value="promo_announcement">Promo Announcement</option>
              <option value="system_alert">System Alert</option>
              <option value="staff_alert">Staff Alert</option>
              <option value="custom">Custom</option>
            </select>
          </div>
          <div class="form-group full">
            <label>Title <span class="req">*</span></label>
            <input type="text" name="title" required placeholder="e.g. Booking Confirmation">
          </div>
          <div class="form-group full">
            <label>Body <span class="req">*</span></label>
            <textarea name="body" required placeholder="Notification message..."></textarea>
          </div>
          <div class="form-group">
            <label>Target audience <span class="req">*</span></label>
            <select name="target_audience" required>
              <option value="specific_user">Specific User</option>
              <option value="all_customers">All Customers</option>
              <option value="all_staff">All Staff</option>
              <option value="reservation_guests">Reservation Guests</option>
            </select>
          </div>
          <div class="form-group">
            <label>Action URL</label>
            <input type="url" name="action_url" placeholder="https://example.com">
          </div>
          <div class="form-group">
            <label>Reference type</label>
            <input type="text" name="reference_type" placeholder="e.g. reservation">
          </div>
          <div class="form-group">
            <label>Reference ID</label>
            <input type="text" name="reference_id" placeholder="e.g. UUID">
          </div>
          <div class="form-group">
            <label>Scheduled at</label>
            <input type="datetime-local" name="scheduled_at">
          </div>
          <div class="form-group">
            <label>Status <span class="req">*</span></label>
            <select name="status" required>
              <option value="draft">Draft</option>
              <option value="scheduled">Scheduled</option>
              <option value="sent">Sent</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" onclick="closeAddModal()">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-device-floppy"></i> Create notification</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="overlay" id="editOverlay" onclick="handleOverlayClick(event,'editOverlay')">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="editModalTitle">
    <div class="modal-header">
      <span class="modal-title" id="editModalTitle">Edit notification</span>
      <button class="modal-close" onclick="closeEditModal()" aria-label="Close">×</button>
    </div>
    <form id="editForm" onsubmit="submitEdit(event)">
      <div class="modal-body">
        <div class="form-grid">
          <input type="hidden" name="notif_id" id="editNotifId">
          <div class="form-group">
            <label>Property</label>
            <select name="property_id" id="editProperty">
              <option value="">All Properties</option>
            </select>
          </div>
          <div class="form-group">
            <label>Notification type <span class="req">*</span></label>
            <select name="notif_type" required>
              <option value="reservation_confirmed">Reservation Confirmed</option>
              <option value="reservation_cancelled">Reservation Cancelled</option>
              <option value="check_in_reminder">Check-in Reminder</option>
              <option value="check_out_reminder">Check-out Reminder</option>
              <option value="payment_received">Payment Received</option>
              <option value="payment_due">Payment Due</option>
              <option value="promo_announcement">Promo Announcement</option>
              <option value="system_alert">System Alert</option>
              <option value="staff_alert">Staff Alert</option>
              <option value="custom">Custom</option>
            </select>
          </div>
          <div class="form-group full">
            <label>Title <span class="req">*</span></label>
            <input type="text" name="title" required placeholder="e.g. Booking Confirmation">
          </div>
          <div class="form-group full">
            <label>Body <span class="req">*</span></label>
            <textarea name="body" required placeholder="Notification message..."></textarea>
          </div>
          <div class="form-group">
            <label>Target audience <span class="req">*</span></label>
            <select name="target_audience" required>
              <option value="specific_user">Specific User</option>
              <option value="all_customers">All Customers</option>
              <option value="all_staff">All Staff</option>
              <option value="reservation_guests">Reservation Guests</option>
            </select>
          </div>
          <div class="form-group">
            <label>Action URL</label>
            <input type="url" name="action_url" placeholder="https://example.com">
          </div>
          <div class="form-group">
            <label>Reference type</label>
            <input type="text" name="reference_type" placeholder="e.g. reservation">
          </div>
          <div class="form-group">
            <label>Reference ID</label>
            <input type="text" name="reference_id" placeholder="e.g. UUID">
          </div>
          <div class="form-group">
            <label>Scheduled at</label>
            <input type="datetime-local" name="scheduled_at">
          </div>
          <div class="form-group">
            <label>Status <span class="req">*</span></label>
            <select name="status" required>
              <option value="draft">Draft</option>
              <option value="scheduled">Scheduled</option>
              <option value="sent">Sent</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" onclick="closeEditModal()">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-device-floppy"></i> Update notification</button>
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
    const n = notificationsData.find(x => x.notif_id == id);
    if (!n) return;
    const f = document.getElementById('editForm');
    document.getElementById('editNotifId').value = n.notif_id;
    f.property_id.value = n.property_id || '';
    f.notif_type.value = n.notif_type;
    f.title.value = n.title;
    f.body.value = n.body;
    f.target_audience.value = n.target_audience;
    f.action_url.value = n.action_url || '';
    f.reference_type.value = n.reference_type || '';
    f.reference_id.value = n.reference_id || '';
    f.scheduled_at.value = n.scheduled_at ? n.scheduled_at.replace(' ', 'T') : '';
    f.status.value = n.status;
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
      notif_type: f.elements['notif_type'].value,
      title: f.elements['title'].value,
      body: f.elements['body'].value,
      target_audience: f.elements['target_audience'].value,
      action_url: f.elements['action_url'].value || null,
      reference_type: f.elements['reference_type'].value || null,
      reference_id: f.elements['reference_id'].value || null,
      scheduled_at: f.elements['scheduled_at'].value || null,
      status: f.elements['status'].value,
      recipients: []
    };
    fetch('../api/notifications.php?action=create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddModal();
        if (data.success) {
          fetchNotifications();
          showToast('Notification created', 'success');
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => {
        closeAddModal();
        showToast('An error occurred while creating the notification', 'error');
      });
  }

  function submitEdit(e) {
    e.preventDefault();
    const f = document.getElementById('editForm');
    const payload = {
      notif_id: document.getElementById('editNotifId').value,
      property_id: f.elements['property_id'].value || null,
      notif_type: f.elements['notif_type'].value,
      title: f.elements['title'].value,
      body: f.elements['body'].value,
      target_audience: f.elements['target_audience'].value,
      action_url: f.elements['action_url'].value || null,
      reference_type: f.elements['reference_type'].value || null,
      reference_id: f.elements['reference_id'].value || null,
      scheduled_at: f.elements['scheduled_at'].value || null,
      status: f.elements['status'].value,
      recipients: []
    };
    fetch('../api/notifications.php?action=update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditModal();
        if (data.success) {
          fetchNotifications();
          showToast('Notification updated', 'success');
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => {
        closeEditModal();
        showToast('An error occurred while updating the notification', 'error');
      });
  }

  function confirmDelete(id, name) {
    if (!confirm(`Delete notification "${name}"? This cannot be undone.`)) return;
    fetch('../api/notifications.php?action=delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ notif_id: id })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          fetchNotifications();
          showToast('Notification deleted', 'success');
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => {
        showToast('An error occurred while deleting the notification', 'error');
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