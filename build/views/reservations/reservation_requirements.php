<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Lora:ital,wght@0,600;1,500&display=swap" rel="stylesheet">

<style>
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0
  }

  body {
    font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
    background: #f5f5f5;
    color: #1a1a1a
  }

  .page {
    padding: 24px;
    max-width: 100%;
    margin: 0 auto
  }

  .card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px
  }

  .card-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap
  }

  .card-title {
    font-family: 'Lora', Georgia, serif;
    font-size: 16px;
    font-weight: 600;
    font-style: italic
  }

  .toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: nowrap
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 6px;
    border: 1px solid #ccc;
    background: #fff;
    color: #1a1a1a;
    cursor: pointer;
    transition: background .15s
  }

  .btn:hover {
    background: #f5f5f5
  }

  .btn-sm {
    padding: 4px 8px;
    font-size: 11px
  }

  .btn-success {
    background: #059669;
    color: #fff;
    border-color: #059669
  }

  .btn-success:hover {
    background: #047857;
    border-color: #047857
  }

  .btn-danger {
    background: #DC2626;
    color: #fff;
    border-color: #DC2626
  }

  .btn-danger:hover {
    background: #B91C1C;
    border-color: #B91C1C
  }

  .actions-cell {
    display: flex;
    align-items: center;
    gap: 6px
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
    border: 1px solid #ccc;
    border-radius: 6px;
    background: #fff;
    color: #1a1a1a;
    outline: none;
    width: 220px;
    min-width: 160px
  }

  .search-input:focus {
    border-color: #185FA5
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
    border-bottom: 1px solid #e0e0e0;
    white-space: nowrap
  }

  thead th.center {
    text-align: center
  }

  tbody tr {
    border-bottom: 1px solid #e0e0e0;
    transition: background .1s
  }

  tbody tr:last-child {
    border-bottom: none
  }

  tbody tr:hover {
    background: #f9f9f9
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
    font-weight: 500;
    text-transform: capitalize
  }

  .status-badge.pending {
    background: #FFF3E0;
    color: #E65100
  }

  .status-badge.submitted {
    background: #E3F2FD;
    color: #1565C0
  }

  .status-badge.approved {
    background: #E8F5E9;
    color: #2E7D32
  }

  .status-badge.rejected {
    background: #FFEBEE;
    color: #C62828
  }

  .file-link {
    color: #185FA5;
    text-decoration: none;
    font-size: 12px
  }

  .file-link:hover {
    text-decoration: underline
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
    color: #ccc
  }

  /* Custom Alert Modal */
  .alert-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .5);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    padding: 20px
  }

  .alert-overlay.open {
    display: flex
  }

  .alert-modal {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, .15)
  }

  .alert-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    gap: 12px
  }

  .alert-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px
  }

  .alert-icon.success {
    background: #E8F5E9;
    color: #2E7D32
  }

  .alert-icon.error {
    background: #FFEBEE;
    color: #C62828
  }

  .alert-icon.warning {
    background: #FFFBEB;
    color: #D97706
  }

  .alert-icon.info {
    background: #EFF6FF;
    color: #2563EB
  }

  .alert-title {
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a
  }

  .alert-body {
    padding: 20px;
    font-size: 13px;
    color: #666;
    line-height: 1.5
  }

  .alert-footer {
    padding: 12px 20px;
    border-top: 1px solid #e0e0e0;
    display: flex;
    justify-content: flex-end;
    gap: 8px
  }

  .alert-btn {
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 6px;
    border: 1px solid #ccc;
    background: #fff;
    color: #1a1a1a;
    cursor: pointer;
    transition: background .15s
  }

  .alert-btn:hover {
    background: #f5f5f5
  }

  .alert-btn.primary {
    background: #185FA5;
    color: #fff;
    border-color: #185FA5
  }

  .alert-btn.primary:hover {
    background: #0C447C;
    border-color: #0C447C
  }
</style>

<!-- PAGE CONTENT -->

<div class="page">
  <div class="card">
    <div class="card-header">
      <div class="card-title">Reservation Requirements</div>
      <div class="toolbar">
        <div class="search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="searchInput" class="search-input" placeholder="Search requirements..." onkeyup="filterTable()">
        </div>
      </div>
    </div>
    <div class="table-wrap">
      <table id="reservationRequirementsTable">
        <thead>
          <tr>
            <th>Reservation</th>
            <th>Requirement</th>
            <th>Guest</th>
            <th class="center">Status</th>
            <th>File</th>
            <th>Notes</th>
            <th>Reviewed By</th>
            <th>Reviewed At</th>
            <th class="center">Actions</th>
          </tr>
        </thead>
        <tbody id="reservationRequirementsTableBody">
          <tr>
            <td colspan="9" class="empty-state">
              <i class="fa-regular fa-clipboard"></i>
              Loading...
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Custom Alert Modal -->
<div class="alert-overlay" id="alertModal">
  <div class="alert-modal">
    <div class="alert-header">
      <div class="alert-icon" id="alertIcon">✓</div>
      <div class="alert-title" id="alertTitle">Success</div>
    </div>
    <div class="alert-body" id="alertMessage">
      Operation completed successfully.
    </div>
    <div class="alert-footer">
      <button class="alert-btn primary" onclick="closeAlert()">OK</button>
    </div>
  </div>
</div>

<!-- Custom Confirm Modal -->
<div class="alert-overlay" id="confirmModal">
  <div class="alert-modal">
    <div class="alert-header">
      <div class="alert-icon warning" id="confirmIcon">⚠</div>
      <div class="alert-title" id="confirmTitle">Confirm</div>
    </div>
    <div class="alert-body" id="confirmMessage">
      Are you sure you want to proceed?
    </div>
    <div class="alert-footer">
      <button class="alert-btn" onclick="closeConfirm()">Cancel</button>
      <button class="alert-btn primary" id="confirmExecuteBtn" onclick="executeConfirm()">Confirm</button>
    </div>
  </div>
</div>

<script>
  let requirementsData = [];
  // FIX: Store session values in JS variables so PHP parses them once at page load
  let csrfToken = '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>';
  let currentUserId = '<?php echo htmlspecialchars($_SESSION["user_id"] ?? "", ENT_QUOTES, "UTF-8"); ?>';

  document.addEventListener('DOMContentLoaded', function() {
    fetchRequirementsData();
  });

  // Custom Alert Functions
  function showAlert(type, title, message) {
    const modal = document.getElementById('alertModal');
    const icon = document.getElementById('alertIcon');
    const titleEl = document.getElementById('alertTitle');
    const messageEl = document.getElementById('alertMessage');

    icon.className = 'alert-icon';

    switch(type) {
      case 'success':
        icon.classList.add('success');
        icon.textContent = '✓';
        break;
      case 'error':
        icon.classList.add('error');
        icon.textContent = '✕';
        break;
      case 'warning':
        icon.classList.add('warning');
        icon.textContent = '⚠';
        break;
      case 'info':
        icon.classList.add('info');
        icon.textContent = 'ℹ';
        break;
      default:
        icon.classList.add('success');
        icon.textContent = '✓';
    }

    titleEl.textContent = title;
    messageEl.textContent = message;
    modal.classList.add('open');
  }

  function closeAlert() {
    document.getElementById('alertModal').classList.remove('open');
  }

  // Custom Confirm Dialog
  let confirmCallback = null;

  function showConfirm(message, onConfirm) {
    const modal = document.getElementById('confirmModal');
    const messageEl = document.getElementById('confirmMessage');

    messageEl.textContent = message;
    // FIX: Store callback before opening modal
    confirmCallback = onConfirm;

    modal.classList.add('open');
  }

  function closeConfirm() {
    document.getElementById('confirmModal').classList.remove('open');
    confirmCallback = null;
  }

  function executeConfirm() {
    // FIX: Capture callback reference before closing (closeConfirm nullifies it)
    const callback = confirmCallback;
    closeConfirm();
    if (callback) {
      callback();
    }
  }

  async function fetchRequirementsData() {
    try {
      const response = await fetch('../api/reservation_requirements.php?action=list');
      const result = await response.json();
      if (result.success) {
        requirementsData = result.data;
        renderRequirementsTable();
      } else {
        document.getElementById('reservationRequirementsTableBody').innerHTML =
          `<tr><td colspan="9" class="empty-state"><i>⚠</i>${result.message}</td></tr>`;
      }
    } catch (error) {
      console.error('Error fetching requirements:', error);
      document.getElementById('reservationRequirementsTableBody').innerHTML =
        `<tr><td colspan="9" class="empty-state"><i>⚠</i>Failed to load data</td></tr>`;
    }
  }

  function formatDateTime(dtStr) {
    if (!dtStr) return '-';
    const d = new Date(dtStr);
    return d.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) + ' ' +
      d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
  }

  function renderRequirementsTable() {
    const tbody = document.getElementById('reservationRequirementsTableBody');
    if (!tbody) return;

    if (requirementsData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="9" class="empty-state"><i class="fa-regular fa-clipboard"></i>No reservation requirements found</td></tr>`;
      return;
    }

    tbody.innerHTML = requirementsData.map(req => {
      const guest = req.first_name
        ? `<div>${req.first_name} ${req.last_name}</div><div style="font-size:12px;color:#666;text-transform:capitalize">${req.guest_type || ''}</div>`
        : `<span style="color:#999">-</span>`;
      const fileCell = req.file_url
        ? `<a href="${req.file_url}" target="_blank" class="file-link">View File</a>`
        : `<span style="color:#999">No file</span>`;
      const reviewedBy = req.reviewed_by ? 'Staff ID: ' + req.reviewed_by.substring(0, 8) : '-';

      const canReview = req.status === 'submitted' && req.file_url;
      const actionsCell = canReview
        ? `<div class="actions-cell" style="justify-content:center">
            <button class="btn btn-sm btn-success" onclick="reviewRequirement('${req.req_id}', 'approved')" title="Approve">
              <i class="fa-solid fa-check"></i>
            </button>
            <button class="btn btn-sm btn-danger" onclick="reviewRequirement('${req.req_id}', 'rejected')" title="Reject">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>`
        : `<span style="color:#999;font-size:12px">—</span>`;

      return `
        <tr class="req-row">
          <td><strong>${req.reservation_code || '-'}</strong></td>
          <td>
            <div><strong>${req.req_label || '-'}</strong></div>
            <div style="font-size:12px;color:#666">${req.req_code || ''}</div>
          </td>
          <td>${guest}</td>
          <td style="text-align:center">
            <span class="status-badge ${req.status || ''}">${req.status || '-'}</span>
          </td>
          <td>${fileCell}</td>
          <td>${req.notes || '-'}</td>
          <td>${reviewedBy}</td>
          <td>${formatDateTime(req.reviewed_at)}</td>
          <td style="text-align:center">${actionsCell}</td>
        </tr>
      `;
    }).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    document.querySelectorAll('#reservationRequirementsTable .req-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }

  async function reviewRequirement(reqId, status) {
    showConfirm(`Are you sure you want to mark this requirement as ${status}?`, async () => {
      try {
        const response = await fetch('../api/reservation_requirements.php?action=update', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            // FIX: Use the JS variable instead of inline PHP inside the callback
            'X-CSRF-Token': csrfToken
          },
          body: JSON.stringify({
            req_id: reqId,
            status: status,
            // FIX: Use the JS variable; omit file_url/notes so API knows this is a review-only update
            reviewed_by: currentUserId
          })
        });
        const result = await response.json();

        if (result.success) {
          showAlert('success', 'Success', `Requirement ${status} successfully`);
          fetchRequirementsData();
        } else {
          showAlert('error', 'Error', 'Failed to update: ' + (result.message || 'Unknown error'));
        }
      } catch (error) {
        console.error('Error updating requirement:', error);
        showAlert('error', 'Error', 'Failed to update requirement');
      }
    });
  }
</script>