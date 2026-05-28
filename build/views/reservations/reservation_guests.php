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

  .type-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
    text-transform: capitalize
  }

  .type-badge.primary {
    background: #F3E5F5;
    color: #7B1FA2
  }

  .type-badge.companion {
    background: #E3F2FD;
    color: #1565C0
  }

  .type-badge.child {
    background: #E8F5E9;
    color: #2E7D32
  }

  .type-badge.senior {
    background: #FFF3E0;
    color: #E65100
  }

  .type-badge.infant {
    background: #FCE4EC;
    color: #C2185B
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
      <div class="card-title">Reservation Guests</div>
      <div class="toolbar">
        <div class="search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="searchInput" class="search-input" placeholder="Search guests..." onkeyup="filterTable()">
        </div>
      </div>
    </div>
    <div class="table-wrap">
      <table id="reservationGuestsTable">
        <thead>
          <tr>
            <th>Reservation</th>
            <th>Property</th>
            <th>Guest Name</th>
            <th>Type</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Nationality</th>
            <th>ID Type</th>
            <th>ID Number</th>
          </tr>
        </thead>
        <tbody id="reservationGuestsTableBody">
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
  let guestsData = [];

  document.addEventListener('DOMContentLoaded', function() {
    fetchGuestsData();
  });

  async function fetchGuestsData() {
    try {
      const response = await fetch('../api/reservation_guests.php?action=list');
      const result = await response.json();
      if (result.success) {
        guestsData = result.data;
        renderGuestsTable();
      } else {
        document.getElementById('reservationGuestsTableBody').innerHTML = `<tr><td colspan="9" class="empty-state"><i>⚠</i>${result.message}</td></tr>`;
      }
    } catch (error) {
      console.error('Error fetching guests:', error);
      document.getElementById('reservationGuestsTableBody').innerHTML = `<tr><td colspan="9" class="empty-state"><i>⚠</i>Failed to load guests data</td></tr>`;
    }
  }

  function renderGuestsTable() {
    const tbody = document.getElementById('reservationGuestsTableBody');
    if (!tbody) return;

    if (guestsData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="9" class="empty-state"><i class="fa-regular fa-clipboard"></i>No reservation guests found</td></tr>`;
      return;
    }

    tbody.innerHTML = guestsData.map(g => {
      return `
        <tr class="guest-row">
          <td><strong>${g.reservation_code || '-'}</strong></td>
          <td>${g.property_name || '-'}</td>
          <td><strong>${(g.first_name || '') + ' ' + (g.last_name || '')}</strong></td>
          <td><span class="type-badge ${g.guest_type || ''}">${g.guest_type || '-'}</span></td>
          <td>${g.age || '-'}</td>
          <td style="text-transform:capitalize">${g.gender || '-'}</td>
          <td>${g.nationality || '-'}</td>
          <td>${g.id_type || '-'}</td>
          <td>${g.id_number || '-'}</td>
        </tr>
      `;
    }).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    document.querySelectorAll('#reservationGuestsTable .guest-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }
</script>