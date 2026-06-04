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

.table-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
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
@media (max-width: 768px) {
    .header-card {
        padding: 1.5rem 1.25rem;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .page-title {
        font-size: 1.8rem;
    }
    
    .res-count-badge {
        align-self: flex-start;
    }
    
    .table-card {
        border-radius: 12px;
    }
    
    .res-table thead th {
        padding: 0.75rem 1rem;
        font-size: 0.6rem;
    }
    
    .res-table tbody td {
        padding: 0.85rem 1rem;
        font-size: 0.8rem;
    }
    
    .property-icon {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }
    
    .status-badge {
        font-size: 0.68rem;
        padding: 0.25rem 0.7rem;
    }
    
    .view-btn {
        padding: 0.35rem 0.75rem;
        font-size: 0.68rem;
    }
}

@media (max-width: 680px) {
    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .res-table {
        min-width: 600px;
    }
    
    .res-table thead {
        position: sticky;
        top: 0;
        z-index: 5;
    }
}

@media (max-width: 480px) {
    .header-card {
        padding: 1.25rem 1rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .page-sub {
        font-size: 0.75rem;
    }
    
    .res-count-badge {
        font-size: 0.72rem;
        padding: 0.35rem 0.85rem;
    }
    
    .res-table thead th {
        padding: 0.6rem 0.85rem;
        font-size: 0.55rem;
    }
    
    .res-table tbody td {
        padding: 0.75rem 0.85rem;
        font-size: 0.75rem;
    }
    
    .property-cell {
        gap: 0.5rem;
    }
    
    .property-icon {
        width: 28px;
        height: 28px;
        font-size: 0.75rem;
    }
    
    .property-name {
        font-size: 0.85rem;
    }
    
    .date-main {
        font-size: 0.85rem;
    }
    
    .date-day {
        font-size: 0.68rem;
    }
    
    .status-badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.6rem;
    }
    
    .view-btn {
        padding: 0.3rem 0.65rem;
        font-size: 0.65rem;
    }
    
    .empty-icon {
        font-size: 2.5rem;
    }
    
    .empty-title {
        font-size: 1.2rem;
    }
    
    .empty-sub {
        font-size: 0.75rem;
    }
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
    max-width: 800px;
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

.modal-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.modal-section {
    background: #FAFAF9;
    border: 1px solid var(--border-table);
    border-radius: 12px;
    padding: 1.25rem;
}

.modal-section-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.1rem;
    font-weight: 500;
    color: var(--ink);
    margin: 0 0 1rem 0;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border);
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
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

@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        max-height: 95vh;
        border-radius: 12px;
    }
    
    .modal-header {
        padding: 1.25rem 1.5rem;
    }
    
    .modal-title {
        font-size: 1.2rem;
    }
    
    .modal-body {
        padding: 1.25rem 1.5rem;
    }
    
    .modal-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .modal-section {
        padding: 1rem;
    }
    
    .modal-section-title {
        font-size: 1rem;
        margin-bottom: 0.85rem;
    }
    
    .detail-row {
        padding: 0.4rem 0;
    }
    
    .detail-label {
        font-size: 0.78rem;
    }
    
    .detail-value {
        font-size: 0.85rem;
    }
}

@media (max-width: 480px) {
    .modal-content {
        width: 98%;
        max-height: 98vh;
        border-radius: 8px;
    }
    
    .modal-header {
        padding: 1rem 1.25rem;
    }
    
    .modal-title {
        font-size: 1.1rem;
    }
    
    .modal-close {
        font-size: 1.3rem;
    }
    
    .modal-body {
        padding: 1rem 1.25rem;
    }
    
    .modal-section {
        padding: 0.875rem;
        border-radius: 8px;
    }
    
    .modal-section-title {
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
        padding-bottom: 0.6rem;
    }
    
    .detail-row {
        flex-direction: column;
        gap: 0.25rem;
        padding: 0.5rem 0;
    }
    
    .detail-value {
        text-align: left;
    }
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
    currentReservationId = reservationId;
    try {
      const [resRes, roomsRes, reqRes] = await Promise.all([
        fetch(`../api/reservations.php?action=single&id=${reservationId}`),
        fetch(`../api/reservation_rooms.php?action=by_reservation&reservation_id=${reservationId}`),
        fetch(`../api/reservation_requirements.php?action=by_reservation&reservation_id=${reservationId}`)
      ]);
      
      const resJson = await resRes.json();
      const roomsJson = await roomsRes.json();
      const reqJson = await reqRes.json();
      
      if (resJson.success && resJson.data) {
        showReservationModal(resJson.data, roomsJson.success ? roomsJson.data : [], reqJson.success ? reqJson.data : []);
      } else {
        alert('Failed to load reservation details');
      }
    } catch (e) {
      console.error('Error loading reservation details:', e);
      alert('Failed to load reservation details');
    }
  }

  function showReservationModal(data, rooms, requirements) {
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
      <div class="modal-grid">
        <!-- Left Column -->
        <div>
          <div class="modal-section">
            <h4 class="modal-section-title">Reservation Details</h4>
            <div class="detail-row">
              <span class="detail-label">Reservation Code</span>
              <span class="detail-value">${escapeHtml(data.reservation_code || '—')}</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Property</span>
              <span class="detail-value">${escapeHtml(data.property_name || '—')}</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Booking Type</span>
              <span class="detail-value">${capitalize(data.booking_type || '—')}</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Status</span>
              <span class="detail-value"><span class="status-badge ${statusClass(data.reservation_status)}">${capitalize(data.reservation_status)}</span></span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Payment Status</span>
              <span class="detail-value"><span class="status-badge ${statusClass(data.payment_status)}">${capitalize(data.payment_status)}</span></span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Booked On</span>
              <span class="detail-value">${data.created_at ? formatDate(data.created_at).main : '—'}</span>
            </div>
          </div>

          <div class="modal-section" style="margin-top: 1.5rem;">
            <h4 class="modal-section-title">Guest Information</h4>
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
              <span class="detail-label">Guests</span>
              <span class="detail-value">${data.num_adults || 0} Adults, ${data.num_children || 0} Children${data.num_seniors ? `, ${data.num_seniors} Seniors` : ''}${data.num_infants ? `, ${data.num_infants} Infants` : ''}</span>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div>
          <div class="modal-section">
            <h4 class="modal-section-title">Stay Details</h4>
            <div class="detail-row">
              <span class="detail-label">Check In</span>
              <span class="detail-value">${checkIn.main} (${checkIn.day})</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Check In Time</span>
              <span class="detail-value">${data.check_in_time || '—'}</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Check Out</span>
              <span class="detail-value">${checkOut.main} (${checkOut.day})</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Check Out Time</span>
              <span class="detail-value">${data.check_out_time || '—'}</span>
            </div>
            <div class="detail-row" style="flex-direction: column; align-items: flex-start;">
              <span class="detail-label" style="margin-bottom: 0.5rem;">Reserved Rooms</span>
              <div style="width: 100%;">${roomsHtml}</div>
            </div>
            ${data.special_requests ? `
            <div class="detail-row" style="flex-direction: column; align-items: flex-start;">
              <span class="detail-label" style="margin-bottom: 0.5rem;">Special Requests</span>
              <div style="width: 100%; font-size: 0.85rem; color: var(--ink-soft);">${escapeHtml(data.special_requests)}</div>
            </div>
            ` : ''}
          </div>

          <div class="modal-section" style="margin-top: 1.5rem;">
            <h4 class="modal-section-title">Payment Summary</h4>
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
          </div>
        </div>
      </div>

      ${requirements && requirements.length > 0 ? `
      <div class="modal-section" style="margin-top: 1.5rem;">
        <h4 class="modal-section-title">📋 Required Documents</h4>
        <div style="width: 100%;">
          ${requirements.map((req, index) => {
            const statusColors = {
              pending: 'background: #FFFBEB; color: #D97706; border-color: #FCD34D;',
              submitted: 'background: #EFF6FF; color: #2563EB; border-color: #93C5FD;',
              approved: 'background: #ECFDF5; color: #059669; border-color: #6EE7B7;',
              rejected: 'background: #FEF2F2; color: #DC2626; border-color: #FCA5A5;'
            };
            const sc = statusColors[req.status] || 'background: #F9FAFB; color: #6B7280; border-color: #E5E7EB;';
            return `
              <div style="background: #fff; border: 2px solid ${req.file_url ? '#6EE7B7' : sc.split(';')[2] || '#E5E7EB'}; border-radius: 12px; padding: 1.25rem; margin-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1rem;">
                  <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                      <div style="font-size: 1rem; font-weight: 600; color: var(--ink);">${escapeHtml(req.req_label || '—')}</div>
                      <span style="font-size: 0.7rem; padding: 0.2rem 0.6rem; border-radius: 50px; font-weight: 600; ${req.is_mandatory ? 'background: #FEF2F2; color: #DC2626;' : 'background: #F3F4F6; color: #6B7280;'}">${req.is_mandatory ? 'Required' : 'Optional'}</span>
                    </div>
                    <div style="font-size: 0.8rem; color: #9CA3AF; line-height: 1.4;">
                      ${req.is_mandatory ? 'This document is required for your reservation.' : 'This document is optional but recommended.'}
                    </div>
                  </div>
                  <span style="font-size: 0.75rem; padding: 0.4rem 1rem; border-radius: 50px; font-weight: 600; border: 1px solid ${sc.split(';')[2] || '#E5E7EB'}; ${sc}">${req.status ? req.status.charAt(0).toUpperCase() + req.status.slice(1) : 'Pending'}</span>
                </div>
                
                ${req.template_url ? `
                  <div style="margin-bottom: 1rem; padding: 0.75rem; background: #EFF6FF; border: 1px solid #93C5FD; border-radius: 8px;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <div style="width: 36px; height: 36px; background: #93C5FD; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #2563EB; font-size: 1rem;">
                        📋
                      </div>
                      <div style="flex: 1;">
                        <div style="font-size: 0.85rem; font-weight: 500; color: #2563EB;">Template Available</div>
                        <div style="font-size: 0.75rem; color: #1E40AF; margin-top: 0.25rem;">Download the form template as reference</div>
                      </div>
                      <a href="${escapeHtml(req.template_url)}" target="_blank" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 500; background: #2563EB; color: #fff; border-radius: 6px; text-decoration: none; white-space: nowrap;">Download</a>
                    </div>
                  </div>
                ` : ''}
                
                ${req.file_url ? `
                  <div style="background: #ECFDF5; border: 1px solid #6EE7B7; border-radius: 8px; padding: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <div style="width: 40px; height: 40px; background: #6EE7B7; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #059669; font-size: 1.2rem;">
                        ✓
                      </div>
                      <div style="flex: 1;">
                        <div style="font-size: 0.9rem; font-weight: 500; color: #059669;">Document Submitted</div>
                        <div style="font-size: 0.75rem; color: #047857; margin-top: 0.25rem;">Your document has been uploaded and is pending review.</div>
                      </div>
                    </div>
                  </div>
                ` : `
                  <div style="background: #F9FAFB; border: 2px dashed #D1D5DB; border-radius: 8px; padding: 1.5rem; text-align: center; cursor: pointer; transition: all 0.2s;" onclick="document.getElementById('reqFile_${index}').click()" onmouseover="this.style.borderColor='#185FA5'; this.style.background='#EFF6FF'" onmouseout="this.style.borderColor='#D1D5DB'; this.style.background='#F9FAFB'">
                    <div style="font-size: 2rem; color: #9CA3AF; margin-bottom: 0.5rem;">📄</div>
                    <div style="font-size: 0.9rem; font-weight: 500; color: var(--ink); margin-bottom: 0.25rem;">Click to upload document</div>
                    <div style="font-size: 0.75rem; color: #9CA3AF;">PDF, JPG, or PNG (Max 5MB)</div>
                    <input type="file" id="reqFile_${index}" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" onchange="uploadRequirementFile('${req.req_id}', this)">
                  </div>
                `}
                
                ${req.notes ? `
                  <div style="margin-top: 0.75rem; padding: 0.75rem; background: #FFFBEB; border-left: 3px solid #FCD34D; border-radius: 4px; font-size: 0.8rem; color: #92400E;">
                    <strong>Note:</strong> ${escapeHtml(req.notes)}
                  </div>
                ` : ''}
              </div>
            `;
          }).join('')}
        </div>
      </div>
      ` : ''}
    `;
    
    modal.classList.add('active');
  }

  function closeModal() {
    document.getElementById('reservationModal').classList.remove('active');
  }

  // Custom Alert Functions
  function showCustomAlert(type, title, message) {
    // Create alert modal if it doesn't exist
    let alertModal = document.getElementById('customAlertModal');
    if (!alertModal) {
      alertModal = document.createElement('div');
      alertModal.id = 'customAlertModal';
      alertModal.style.cssText = `
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: none; align-items: center; justify-content: center;
        z-index: 2000; padding: 20px;
      `;
      alertModal.innerHTML = `
        <div style="background: #fff; border-radius: 12px; border: 1px solid #e0e0e0;
                    width: 100%; max-width: 400px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);">
          <div style="padding: 16px 20px; border-bottom: 1px solid #e0e0e0;
                      display: flex; align-items: center; gap: 12px;">
            <div id="alertIcon" style="width: 32px; height: 32px; border-radius: 50%;
                                 display: flex; align-items: center; justify-content: center;
                                 font-size: 16px;">✓</div>
            <div id="alertTitle" style="font-size: 14px; font-weight: 600; color: #1a1a1a;">Success</div>
          </div>
          <div id="alertMessage" style="padding: 20px; font-size: 13px; color: #666; line-height: 1.5;">
            Operation completed successfully.
          </div>
          <div style="padding: 12px 20px; border-top: 1px solid #e0e0e0;
                      display: flex; justify-content: flex-end;">
            <button onclick="closeCustomAlert()" style="padding: 8px 16px; font-size: 13px; font-weight: 500;
                                                border-radius: 6px; border: 1px solid #185FA5; background: #185FA5;
                                                color: #fff; cursor: pointer;">OK</button>
          </div>
        </div>
      `;
      document.body.appendChild(alertModal);
    }

    const icon = document.getElementById('alertIcon');
    const titleEl = document.getElementById('alertTitle');
    const messageEl = document.getElementById('alertMessage');

    // Reset icon styles
    icon.style.cssText = 'width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px;';

    // Set icon and styling based on type
    switch(type) {
      case 'success':
        icon.style.background = '#E8F5E9';
        icon.style.color = '#2E7D32';
        icon.textContent = '✓';
        break;
      case 'error':
        icon.style.background = '#FFEBEE';
        icon.style.color = '#C62828';
        icon.textContent = '✕';
        break;
      case 'warning':
        icon.style.background = '#FFFBEB';
        icon.style.color = '#D97706';
        icon.textContent = '⚠';
        break;
      case 'info':
        icon.style.background = '#EFF6FF';
        icon.style.color = '#2563EB';
        icon.textContent = 'ℹ';
        break;
      default:
        icon.style.background = '#E8F5E9';
        icon.style.color = '#2E7D32';
        icon.textContent = '✓';
    }

    titleEl.textContent = title;
    messageEl.textContent = message;
    alertModal.style.display = 'flex';
  }

  function closeCustomAlert() {
    const alertModal = document.getElementById('customAlertModal');
    if (alertModal) {
      alertModal.style.display = 'none';
    }
  }

  let currentReservationId = null;
  let csrfToken = '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>';

  async function uploadRequirementFile(reqId, fileInput) {
    const file = fileInput.files[0];
    if (!file) return;

    // Validate file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
      showCustomAlert('error', 'File Size Error', 'File size must be less than 5MB');
      fileInput.value = '';
      return;
    }

    // Validate file type
    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
    if (!allowedTypes.includes(file.type)) {
      showCustomAlert('error', 'Invalid File Type', 'Only PDF, JPG, and PNG files are allowed');
      fileInput.value = '';
      return;
    }

    const formData = new FormData();
    formData.append('req_id', reqId);
    formData.append('file', file);
    formData.append('status', 'submitted');
    formData.append('csrf_token', csrfToken);

    try {
      const response = await fetch('../api/reservation_requirements.php?action=upload', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        showCustomAlert('success', 'Success', 'Document uploaded successfully');
        // Refresh the modal to show updated status
        if (currentReservationId) {
          viewReservation(currentReservationId);
        }
      } else {
        showCustomAlert('error', 'Upload Failed', 'Failed to upload document: ' + (result.message || 'Unknown error'));
        fileInput.value = '';
      }
    } catch (error) {
      console.error('Error uploading file:', error);
      showCustomAlert('error', 'Upload Failed', 'Failed to upload document');
      fileInput.value = '';
    }
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
        <div class="table-wrapper">
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