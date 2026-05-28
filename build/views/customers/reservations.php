<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=DM+Sans:wght@300;400;500&display=swap');

:root {
    --gold: #C9A96E;
    --gold-light: #E8D5B0;
    --gold-dark: #A07840;
    --ink: #1A1714;
    --ink-soft: #3D3530;
    --parchment: #FAF7F2;
    --card-bg: #FFFFFF;
    --border: rgba(201,169,110,0.2);
    --border-table: #F0EBE1;
}

.res-wrap {
    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    padding: 0 0 2rem;
}

/* ── Page header card ── */
.header-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 2rem 2.25rem;
    margin-bottom: 1.75rem;
}

.page-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 1rem;
}

.page-header-left {}

.page-eyebrow {
    font-size: 0.68rem;
    font-weight: 500;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 0.3rem;
}

.page-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.2rem;
    font-weight: 400;
    color: var(--ink);
    margin: 0;
    line-height: 1.1;
}

.page-sub {
    font-size: 0.82rem;
    color: #9CA3AF;
    margin-top: 0.3rem;
    font-weight: 300;
}

.res-count-badge {
    background: var(--parchment);
    border: 1px solid var(--border);
    border-radius: 50px;
    padding: 0.4rem 1rem;
    font-size: 0.78rem;
    font-weight: 500;
    color: var(--gold-dark);
    flex-shrink: 0;
}

/* ── Table card ── */
.table-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
}

.res-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}

.res-table thead {
    background: #FAFAF9;
    border-bottom: 1px solid var(--border-table);
}

.res-table thead th {
    padding: 0.9rem 1.5rem;
    font-size: 0.65rem;
    font-weight: 500;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #9CA3AF;
    text-align: left;
    white-space: nowrap;
}

.res-table tbody tr {
    border-bottom: 1px solid var(--border-table);
    transition: background 0.15s ease;
}

.res-table tbody tr:last-child { border-bottom: none; }
.res-table tbody tr:hover { background: #FDFCFA; }

.res-table tbody td {
    padding: 1rem 1.5rem;
    color: var(--ink-soft);
    vertical-align: middle;
}

/* View button */
.view-btn {
    padding: 0.4rem 0.9rem;
    font-size: 0.72rem;
    font-weight: 500;
    border: 1px solid var(--border);
    border-radius: 50px;
    background: var(--parchment);
    color: var(--gold-dark);
    cursor: pointer;
    transition: all 0.2s;
}

.view-btn:hover {
    background: var(--gold-light);
    border-color: var(--gold);
}

.property-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.property-icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    background: linear-gradient(135deg, #FEF3C7, #FDE68A);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold-dark);
    font-size: 0.9rem;
    flex-shrink: 0;
}

.property-name {
    font-weight: 500;
    color: var(--ink);
}

.date-cell {
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
}

.date-main {
    font-weight: 500;
    color: var(--ink);
}

.date-day {
    font-size: 0.72rem;
    color: #9CA3AF;
}

/* Status badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.85rem;
    border-radius: 50px;
    font-size: 0.72rem;
    font-weight: 500;
    letter-spacing: 0.04em;
    white-space: nowrap;
}

.status-badge::before {
    content: '';
    width: 5px; height: 5px;
    border-radius: 50%;
    background: currentColor;
    opacity: 0.7;
}

.status-completed { background: #ECFDF5; color: #059669; }
.status-cancelled { background: #FEF2F2; color: #DC2626; }
.status-confirmed { background: #EFF6FF; color: #2563EB; }
.status-pending   { background: #FFFBEB; color: #D97706; }
.status-unknown   { background: #F9FAFB; color: #6B7280; }

/* Empty state */
.empty-state {
    text-align: center;
    padding: 5rem 2rem;
    color: #9CA3AF;
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    display: block;
    opacity: 0.4;
}

.empty-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.4rem;
    font-weight: 400;
    color: var(--ink-soft);
    margin-bottom: 0.4rem;
}

.empty-sub { font-size: 0.82rem; }

/* Responsive */
@media (max-width: 680px) {
    .res-table { display: block; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .page-header { flex-direction: column; align-items: flex-start; }
}

/* Modal */
.modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-overlay.active { display: flex; }

.modal-content {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 1.5rem 1.75rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modal-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.4rem;
    font-weight: 500;
    color: var(--ink);
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #9CA3AF;
    cursor: pointer;
    padding: 0.25rem;
}

.modal-close:hover { color: var(--ink); }

.modal-body {
    padding: 1.5rem 1.75rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-table);
}

.detail-row:last-child { border-bottom: none; }

.detail-label {
    font-size: 0.82rem;
    color: #9CA3AF;
    font-weight: 500;
}

.detail-value {
    font-size: 0.9rem;
    color: var(--ink-soft);
    font-weight: 500;
    text-align: right;
}
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    loadReservations();
  });

  async function loadReservations() {
    try {
      const res = await fetch('../api/reservations.php?action=my_reservations');
      const json = await res.json();
      
      if (json.success && json.data.length > 0) {
        renderReservations(json.data);
        document.getElementById('resCountBadge').textContent = json.data.length + ' reservation' + (json.data.length !== 1 ? 's' : '');
        document.getElementById('resCountBadge').style.display = 'flex';
      } else {
        showEmptyState();
      }
    } catch (e) {
      console.error('Error loading reservations:', e);
      showEmptyState();
    }
  }

  function renderReservations(data) {
    const tbody = document.getElementById('reservationsTableBody');
    tbody.innerHTML = data.map(r => {
      const checkIn = r.check_in_date ? formatDate(r.check_in_date) : { main: '—', day: '' };
      const checkOut = r.check_out_date ? formatDate(r.check_out_date) : { main: '—', day: '' };
      const status = r.reservation_status || 'unknown';
      
      return `
        <tr>
          <td>
            <div class="property-cell">
              <div class="property-icon">
                <i class="ni ni-building"></i>
              </div>
              <span class="property-name">${escapeHtml(r.property_name || '—')}</span>
            </div>
          </td>
          <td>
            <div class="date-cell">
              <span class="date-main">${checkIn.main}</span>
              <span class="date-day">${checkIn.day}</span>
            </div>
          </td>
          <td>
            <div class="date-cell">
              <span class="date-main">${checkOut.main}</span>
              <span class="date-day">${checkOut.day}</span>
            </div>
          </td>
          <td>
            <span class="status-badge ${statusClass(status)}">
              ${capitalize(status)}
            </span>
          </td>
          <td>
            <button class="view-btn" onclick="viewReservation('${r.reservation_id}')">View</button>
          </td>
        </tr>
      `;
    }).join('');
  }

  function showEmptyState() {
    document.getElementById('reservationsTableBody').innerHTML = '';
    document.getElementById('emptyState').style.display = 'block';
    document.getElementById('resCountBadge').style.display = 'none';
  }

  function formatDate(dateStr) {
    const date = new Date(dateStr);
    return {
      main: date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
      day: date.toLocaleDateString('en-US', { weekday: 'long' })
    };
  }

  function statusClass(status) {
    const classes = {
      'completed': 'status-completed',
      'cancelled': 'status-cancelled',
      'confirmed': 'status-confirmed',
      'pending': 'status-pending'
    };
    return classes[status] || 'status-unknown';
  }

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  async function viewReservation(reservationId) {
    try {
      const [resRes, roomsRes] = await Promise.all([
        fetch(`../api/reservations.php?action=single&id=${reservationId}`),
        fetch(`../api/reservation_rooms.php?action=by_reservation&reservation_id=${reservationId}`)
      ]);
      
      const resJson = await resRes.json();
      const roomsJson = await roomsRes.json();
      
      if (resJson.success && resJson.data) {
        showReservationModal(resJson.data, roomsJson.success ? roomsJson.data : []);
      } else {
        alert('Failed to load reservation details');
      }
    } catch (e) {
      console.error('Error loading reservation details:', e);
      alert('Failed to load reservation details');
    }
  }

  function showReservationModal(data, rooms) {
    const modal = document.getElementById('reservationModal');
    const modalBody = document.getElementById('modalBody');
    
    const checkIn = data.check_in_date ? formatDate(data.check_in_date) : { main: '—', day: '' };
    const checkOut = data.check_out_date ? formatDate(data.check_out_date) : { main: '—', day: '' };
    
    const roomsHtml = rooms.length > 0 
      ? rooms.map(r => `
          <div style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-table);">
            <div style="font-size: 0.85rem; font-weight: 500; color: var(--ink);">${escapeHtml(r.room_name || r.room_number || '—')}</div>
            <div style="font-size: 0.75rem; color: #9CA3AF;">${escapeHtml(r.category_name || '—')} - ₱${parseFloat(r.price_per_night || 0).toLocaleString()}/night</div>
          </div>
        `).join('')
      : '<div style="padding: 0.5rem 0; color: #9CA3AF; font-size: 0.85rem;">No rooms assigned</div>';
    
    modalBody.innerHTML = `
      <div class="detail-row">
        <span class="detail-label">Reservation Code</span>
        <span class="detail-value">${escapeHtml(data.reservation_code || '—')}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Property</span>
        <span class="detail-value">${escapeHtml(data.property_name || '—')}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Guest Name</span>
        <span class="detail-value">${escapeHtml(data.guest_name || '—')}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Email</span>
        <span class="detail-value">${escapeHtml(data.guest_email || '—')}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Phone</span>
        <span class="detail-value">${escapeHtml(data.guest_phone || '—')}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Check In</span>
        <span class="detail-value">${checkIn.main} (${checkIn.day})</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Check Out</span>
        <span class="detail-value">${checkOut.main} (${checkOut.day})</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Guests</span>
        <span class="detail-value">${data.num_adults || 0} Adults, ${data.num_children || 0} Children</span>
      </div>
      <div class="detail-row" style="flex-direction: column; align-items: flex-start;">
        <span class="detail-label" style="margin-bottom: 0.5rem;">Reserved Rooms</span>
        <div style="width: 100%;">${roomsHtml}</div>
      </div>
      <div class="detail-row">
        <span class="detail-label">Status</span>
        <span class="detail-value"><span class="status-badge ${statusClass(data.reservation_status)}">${capitalize(data.reservation_status)}</span></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Total Amount</span>
        <span class="detail-value">₱${parseFloat(data.total_amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Paid Amount</span>
        <span class="detail-value">₱${parseFloat(data.paid_amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Balance</span>
        <span class="detail-value">₱${parseFloat(data.balance_amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
      </div>
    `;
    
    modal.classList.add('active');
  }

  function closeModal() {
    document.getElementById('reservationModal').classList.remove('active');
  }

  // Close modal on overlay click
  document.addEventListener('click', (e) => {
    const modal = document.getElementById('reservationModal');
    if (e.target === modal) {
      closeModal();
    }
  });
</script>

<div class="res-wrap">

    <div class="header-card">
        <div class="page-header">
            <div class="page-header-left">
                <h1 class="page-title">My Reservations</h1>
                <p class="page-sub">Booking history and upcoming reservations</p>
            </div>
            <div class="res-count-badge" id="resCountBadge" style="display: none;">0 reservations</div>
        </div>
    </div>

    <div class="table-card">
        <div id="emptyState" class="empty-state" style="display: none;">
            <i class="ni ni-calendar-grid-58 empty-icon"></i>
            <p class="empty-title">No reservations yet</p>
            <p class="empty-sub">Your booking history will appear here once you make a reservation.</p>
        </div>
        <div style="overflow-x:auto;">
            <table class="res-table">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="reservationsTableBody">
                    <tr><td colspan="5" style="text-align: center; color: #9CA3AF;">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Reservation Details Modal -->
    <div class="modal-overlay" id="reservationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Reservation Details</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Details will be injected here -->
            </div>
        </div>
    </div>

</div>