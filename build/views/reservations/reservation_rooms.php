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

  .page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 24px;
    gap: 16px;
    flex-wrap: wrap
  }

  .page-title {
    font-family: 'Lora', Georgia, serif;
    font-size: 22px;
    font-weight: 600;
    font-style: italic;
    color: #1a1a1a
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

  thead th.right {
    text-align: right
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

  .status-badge.reserved {
    background: #E3F2FD;
    color: #1565C0
  }

  .status-badge.checked_in {
    background: #E8F5E9;
    color: #2E7D32
  }

  .status-badge.checked_out {
    background: #F5F5F5;
    color: #666
  }

  .status-badge.cancelled {
    background: #FFEBEE;
    color: #C62828
  }

  .status-badge.no_show {
    background: #FFF3E0;
    color: #E65100
  }

  .type-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
    text-transform: capitalize;
    background: #E6F1FB;
    color: #0C447C
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
      <div class="card-title">Reservation Rooms</div>
      <div class="toolbar">
        <div class="search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="searchInput" class="search-input" placeholder="Search rooms..." onkeyup="filterTable()">
        </div>
      </div>
    </div>
    <div class="table-wrap">
      <table id="reservationRoomsTable">
        <thead>
          <tr>
            <th>Reservation</th>
            <th>Property</th>
            <th>Room</th>
            <th>Category</th>
            <th>Type</th>
            <th class="right">Price/Night</th>
            <th class="right">Nights/Hours</th>
            <th class="right">Room Total</th>
            <th class="center">Status</th>
          </tr>
        </thead>
        <tbody id="reservationRoomsTableBody">
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
  let reservationRoomsData = [];

  document.addEventListener('DOMContentLoaded', function() {
    fetchReservationRoomsData();
  });

  async function fetchReservationRoomsData() {
    try {
      const response = await fetch('../api/reservation_rooms.php?action=list');
      const result = await response.json();
      if (result.success) {
        reservationRoomsData = result.data;
        renderReservationRoomsTable();
      } else {
        document.getElementById('reservationRoomsTableBody').innerHTML = `<tr><td colspan="9" class="empty-state"><i>⚠</i>${result.message}</td></tr>`;
      }
    } catch (error) {
      console.error('Error fetching reservation rooms:', error);
      document.getElementById('reservationRoomsTableBody').innerHTML = `<tr><td colspan="9" class="empty-state"><i>⚠</i>Failed to load data</td></tr>`;
    }
  }

  function renderReservationRoomsTable() {
    const tbody = document.getElementById('reservationRoomsTableBody');
    if (!tbody) return;

    if (reservationRoomsData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="9" class="empty-state"><i class="fa-regular fa-clipboard"></i>No reservation rooms found</td></tr>`;
      return;
    }

    tbody.innerHTML = reservationRoomsData.map(rr => {
      const nightsHours = rr.booking_type === 'hourly'
        ? `${rr.num_hours || '-'} hrs`
        : `${rr.num_nights || '-'} nights`;
      return `
        <tr class="resroom-row">
          <td>
            <strong>${rr.reservation_code || '-'}</strong>
          </td>
          <td>${rr.property_name || '-'}</td>
          <td>
            <div>${rr.room_number || ''}</div>
            <div style="font-size:12px;color:#666">${rr.room_name || ''}</div>
          </td>
          <td>${rr.category_name || '-'}</td>
          <td>
            <span class="type-badge">${rr.booking_type || '-'}</span>
          </td>
          <td style="text-align:right">
            <strong>${parseFloat(rr.price_per_night || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</strong>
          </td>
          <td style="text-align:right">${nightsHours}</td>
          <td style="text-align:right">
            <strong>${parseFloat(rr.room_total || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</strong>
          </td>
          <td style="text-align:center">
            <span class="status-badge ${rr.room_status || ''}">${(rr.room_status || '-').replace(/_/g, ' ')}</span>
          </td>
        </tr>
      `;
    }).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    document.querySelectorAll('#reservationRoomsTable .resroom-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }
</script>