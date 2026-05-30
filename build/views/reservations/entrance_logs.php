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
    flex-wrap: wrap
  }

  .entries-select {
    padding: 7px 12px;
    font-size: 13px;
    border: 1px solid #ccc;
    border-radius: 6px;
    background: #fff;
    color: #1a1a1a;
    outline: none
  }

  .entries-select:focus {
    border-color: #185FA5
  }

  .pagination {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-top: 1px solid #e0e0e0;
    justify-content: space-between;
    flex-wrap: wrap
  }

  .pagination-info {
    font-size: 13px;
    color: #666
  }

  .pagination-controls {
    display: flex;
    align-items: center;
    gap: 6px
  }

  .pagination-btn {
    padding: 6px 12px;
    font-size: 13px;
    border: 1px solid #ccc;
    border-radius: 6px;
    background: #fff;
    color: #1a1a1a;
    cursor: pointer;
    transition: all .2s
  }

  .pagination-btn:hover:not(:disabled) {
    background: #f5f5f5;
    border-color: #999
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
        <select id="entriesPerPage" class="entries-select" onchange="changeEntriesPerPage()">
          <option value="10">10 entries</option>
          <option value="25">25 entries</option>
          <option value="50">50 entries</option>
          <option value="100">100 entries</option>
        </select>
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
    <div class="pagination" id="pagination">
      <div class="pagination-info" id="paginationInfo">Showing 0 of 0 entries</div>
      <div class="pagination-controls" id="paginationControls"></div>
    </div>
  </div>
</div>

<script>
  let entranceLogsData = [];
  let filteredData = [];
  let currentPage = 1;
  let entriesPerPage = 10;

  document.addEventListener('DOMContentLoaded', function() {
    fetchEntranceLogsData();
  });

  async function fetchEntranceLogsData() {
    try {
      const response = await fetch('../api/entrance_logs.php?action=list');
      const result = await response.json();
      if (result.success) {
        entranceLogsData = result.data;
        filteredData = [...entranceLogsData];
        currentPage = 1;
        renderEntranceLogsTable();
        renderPagination();
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

    if (filteredData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="9" class="empty-state"><i class="fa-regular fa-clipboard"></i>No entrance logs found</td></tr>`;
      return;
    }

    const startIndex = (currentPage - 1) * entriesPerPage;
    const endIndex = startIndex + entriesPerPage;
    const pageData = filteredData.slice(startIndex, endIndex);

    tbody.innerHTML = pageData.map(log => {
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

  function renderPagination() {
    const paginationInfo = document.getElementById('paginationInfo');
    const paginationControls = document.getElementById('paginationControls');
    const totalPages = Math.ceil(filteredData.length / entriesPerPage);

    if (filteredData.length === 0) {
      paginationInfo.textContent = 'Showing 0 of 0 entries';
      paginationControls.innerHTML = '';
      return;
    }

    const startEntry = (currentPage - 1) * entriesPerPage + 1;
    const endEntry = Math.min(currentPage * entriesPerPage, filteredData.length);
    paginationInfo.textContent = `Showing ${startEntry}-${endEntry} of ${filteredData.length} entries`;

    let buttons = '';
    buttons += `<button class="pagination-btn" onclick="goToPage(1)" ${currentPage === 1 ? 'disabled' : ''}>First</button>`;
    buttons += `<button class="pagination-btn" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>Prev</button>`;

    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

    if (endPage - startPage + 1 < maxVisiblePages) {
      startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
      buttons += `<button class="pagination-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
    }

    buttons += `<button class="pagination-btn" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>Next</button>`;
    buttons += `<button class="pagination-btn" onclick="goToPage(${totalPages})" ${currentPage === totalPages ? 'disabled' : ''}>Last</button>`;

    paginationControls.innerHTML = buttons;
  }

  function goToPage(page) {
    const totalPages = Math.ceil(filteredData.length / entriesPerPage);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    renderEntranceLogsTable();
    renderPagination();
  }

  function changeEntriesPerPage() {
    entriesPerPage = parseInt(document.getElementById('entriesPerPage').value);
    currentPage = 1;
    renderEntranceLogsTable();
    renderPagination();
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    filteredData = entranceLogsData.filter(log => {
      const text = Object.values(log).join(' ').toUpperCase();
      return text.includes(filter);
    });
    currentPage = 1;
    renderEntranceLogsTable();
    renderPagination();
  }
</script>