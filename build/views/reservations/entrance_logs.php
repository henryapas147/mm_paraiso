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

  .badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
    text-transform: capitalize
  }

  .badge.adult {
    background: #E3F2FD;
    color: #1565C0
  }

  .badge.child {
    background: #E8F5E9;
    color: #2E7D32
  }

  .badge.senior {
    background: #FFF3E0;
    color: #E65100
  }

  .badge.infant {
    background: #FCE4EC;
    color: #C2185B
  }

  .badge.visitor {
    background: #F3E5F5;
    color: #7B1FA2
  }

  .badge.staff {
    background: #F5F5F5;
    color: #666
  }

  .badge.delivery {
    background: #E0F7FA;
    color: #00838F
  }

  .badge.vendor {
    background: #E8EAF6;
    color: #3949AB
  }

  .badge.entry {
    background: #E8F5E9;
    color: #2E7D32
  }

  .badge.exit {
    background: #FFEBEE;
    color: #C62828
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
      <div class="card-title">Entrance/Exit Logs</div>
      <div class="toolbar">
        <div class="search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="searchInput" class="search-input" placeholder="Search logs..." onkeyup="filterTable()">
        </div>
      </div>
    </div>
    <div class="table-wrap">
      <table id="entranceLogsTable">
        <thead>
          <tr>
            <th>Property</th>
            <th>Reservation</th>
            <th>Visitor Name</th>
            <th>Visitor Type</th>
            <th class="center">Entry Type</th>
            <th>Event At</th>
            <th>ID Presented</th>
            <th>Logged By</th>
            <th>Notes</th>
          </tr>
        </thead>
        <tbody id="entranceLogsTableBody">
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

<script>
  let entranceLogsData = [];

  document.addEventListener('DOMContentLoaded', function() {
    fetchEntranceLogsData();
  });

  async function fetchEntranceLogsData() {
    try {
      const response = await fetch('../api/entrance_logs.php?action=list');
      const result = await response.json();
      if (result.success) {
        entranceLogsData = result.data;
        renderEntranceLogsTable();
      } else {
        document.getElementById('entranceLogsTableBody').innerHTML = `<tr><td colspan="9" class="empty-state"><i>⚠</i>${result.message}</td></tr>`;
      }
    } catch (error) {
      console.error('Error fetching entrance logs:', error);
      document.getElementById('entranceLogsTableBody').innerHTML = `<tr><td colspan="9" class="empty-state"><i>⚠</i>Failed to load data</td></tr>`;
    }
  }

  function formatDateTime(dtStr) {
    if (!dtStr) return '-';
    const d = new Date(dtStr);
    return d.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) + ' ' +
      d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
  }

  function renderEntranceLogsTable() {
    const tbody = document.getElementById('entranceLogsTableBody');
    if (!tbody) return;

    if (entranceLogsData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="9" class="empty-state"><i class="fa-regular fa-clipboard"></i>No entrance logs found</td></tr>`;
      return;
    }

    tbody.innerHTML = entranceLogsData.map(log => {
      const entryLabel = log.entry_type === 'entry' ? 'Entry' : 'Exit';
      const visitorName = log.visitor_name || (log.first_name ? `${log.first_name} ${log.last_name}` : '-');
      const reservation = log.reservation_code || '<span style="color:#999">Walk-in</span>';
      const loggedBy = log.logged_by ? 'Staff ID: ' + log.logged_by.substring(0, 8) : '-';
      return `
        <tr class="entrance-row">
          <td>${log.property_name || '-'}</td>
          <td>${reservation}</td>
          <td><strong>${visitorName}</strong></td>
          <td><span class="badge ${log.visitor_type || ''}">${log.visitor_type || '-'}</span></td>
          <td style="text-align:center"><span class="badge ${log.entry_type || ''}">${entryLabel}</span></td>
          <td>${formatDateTime(log.event_at)}</td>
          <td>${log.id_presented || '-'}</td>
          <td>${loggedBy}</td>
          <td>${log.notes || '-'}</td>
        </tr>
      `;
    }).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    document.querySelectorAll('#entranceLogsTable .entrance-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }
</script>