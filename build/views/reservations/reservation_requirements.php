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
          </tr>
        </thead>
        <tbody id="reservationRequirementsTableBody">
          <tr>
            <td colspan="8" class="empty-state">
              <i class="fa-regular fa-clipboard"></i>
              Loading...
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  let requirementsData = [];

  document.addEventListener('DOMContentLoaded', function() {
    fetchRequirementsData();
  });

  async function fetchRequirementsData() {
    try {
      const response = await fetch('../api/reservation_requirements.php?action=list');
      const result = await response.json();
      if (result.success) {
        requirementsData = result.data;
        renderRequirementsTable();
      } else {
        document.getElementById('reservationRequirementsTableBody').innerHTML = `<tr><td colspan="8" class="empty-state"><i>⚠</i>${result.message}</td></tr>`;
      }
    } catch (error) {
      console.error('Error fetching requirements:', error);
      document.getElementById('reservationRequirementsTableBody').innerHTML = `<tr><td colspan="8" class="empty-state"><i>⚠</i>Failed to load data</td></tr>`;
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
      tbody.innerHTML = `<tr><td colspan="8" class="empty-state"><i class="fa-regular fa-clipboard"></i>No reservation requirements found</td></tr>`;
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
</script>