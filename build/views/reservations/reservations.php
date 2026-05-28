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
    border: 1px solid #e0e0e0;
    border-radius: 8px;
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
    font-family: 'Lora', Georgia, serif;
    font-size: 28px;
    font-weight: 600
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

  .stat-value.orange {
    color: #E65100
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

  @media (max-width: 768px) {
    .card-header {
      flex-direction: column;
      align-items: stretch
    }
    .toolbar {
      flex-wrap: wrap
    }
    .search-input {
      width: 100%
    }
    .filter-select {
      flex: 1;
      min-width: 120px
    }
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

  .filter-select {
    padding: 7px 12px;
    font-size: 13px;
    border: 1px solid #ccc;
    border-radius: 6px;
    background: #fff;
    color: #1a1a1a;
    outline: none
  }

  .filter-select:focus {
    border-color: #185FA5
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

  .status-badge.confirmed {
    background: #E8F5E9;
    color: #2E7D32
  }

  .status-badge.checked_in {
    background: #E3F2FD;
    color: #1565C0
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

  .status-badge.waitlisted {
    background: #F3E5F5;
    color: #7B1FA2
  }

  .status-badge.unpaid {
    background: #FFEBEE;
    color: #C62828
  }

  .status-badge.partial {
    background: #FFF3E0;
    color: #E65100
  }

  .status-badge.paid {
    background: #E8F5E9;
    color: #2E7D32
  }

  .status-badge.refunded {
    background: #E3F2FD;
    color: #1565C0
  }

  .status-badge.void {
    background: #F5F5F5;
    color: #666
  }

  .action-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
    font-size: 12px;
    font-weight: 500;
    border-radius: 6px;
    border: 1px solid #e0e0e0;
    background: transparent;
    cursor: pointer;
    transition: all .15s;
    color: #666
  }

  .action-btn:hover {
    background: #f5f5f5;
    color: #1a1a1a;
    border-color: #ccc
  }

  .action-btn.danger:hover {
    background: #FFEBEE;
    color: #C62828;
    border-color: #EF9A9A
  }

  .action-btn i {
    font-size: 12px
  }

  .actions-cell {
    display: flex;
    align-items: center;
    gap: 6px
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

  .pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-top: 1px solid #e0e0e0
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

  /* Modal */
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
    border: 1px solid #e0e0e0;
    width: 100%;
    max-width: 900px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 24px 48px rgba(0, 0, 0, .18)
  }

  .modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: space-between
  }

  .modal-title {
    font-family: 'Lora', Georgia, serif;
    font-size: 16px;
    font-weight: 600;
    font-style: italic
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
    color: #1a1a1a
  }

  .modal-body {
    padding: 24px
  }

  .modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #e0e0e0;
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
    font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
    font-size: 13px;
    color: #1a1a1a;
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 6px;
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

  .detail-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0
  }

  .detail-row:last-child {
    border-bottom: none
  }

  .detail-label {
    font-size: 13px;
    color: #666;
    font-weight: 500
  }

  .detail-value {
    font-size: 13px;
    color: #1a1a1a;
    text-align: right
  }

  .detail-value strong {
    font-weight: 600
  }

  /* Custom Alert */
  .alert-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .45);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    padding: 20px
  }

  .alert-overlay.open {
    display: flex
  }

  .alert-box {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    width: 100%;
    max-width: 400px;
    padding: 24px;
    box-shadow: 0 16px 32px rgba(0, 0, 0, .15)
  }

  .alert-title {
    font-family: 'Lora', Georgia, serif;
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 8px
  }

  .alert-message {
    font-size: 13px;
    color: #666;
    margin-bottom: 20px;
    line-height: 1.5
  }

  .alert-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px
  }

  .alert-btn-cancel {
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

  .alert-btn-cancel:hover {
    background: #f5f5f5
  }

  .alert-btn-confirm {
    padding: 7px 14px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 6px;
    border: 1px solid #C62828;
    background: #C62828;
    color: #fff;
    cursor: pointer;
    transition: background .15s
  }

  .alert-btn-confirm:hover {
    background: #B71C1C
  }
</style>

<!-- PAGE CONTENT -->

<div class="page">
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total Reservations</div>
      <div class="stat-value blue" id="statTotal">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Pending</div>
      <div class="stat-value orange" id="statPending">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Confirmed</div>
      <div class="stat-value green" id="statConfirmed">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Checked In</div>
      <div class="stat-value purple" id="statCheckedIn">—</div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title">All Reservations</div>
      <div class="toolbar">
        <button class="btn btn-primary" onclick="openWalkInModal()" style="white-space:nowrap"><i class="fa-solid fa-user-plus"></i> Walk-In Check-In</button>
        <div class="search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="searchInput" class="search-input" placeholder="Search reservations..." onkeyup="filterTable()">
        </div>
        <select id="statusFilter" class="filter-select" onchange="filterTable()">
          <option value="">All Status</option>
          <option value="pending">Pending</option>
          <option value="confirmed">Confirmed</option>
          <option value="checked_in">Checked In</option>
          <option value="checked_out">Checked Out</option>
          <option value="cancelled">Cancelled</option>
          <option value="no_show">No Show</option>
          <option value="waitlisted">Waitlisted</option>
        </select>
        <select id="paymentFilter" class="filter-select" onchange="filterTable()">
          <option value="">All Payment</option>
          <option value="unpaid">Unpaid</option>
          <option value="partial">Partial</option>
          <option value="paid">Paid</option>
          <option value="refunded">Refunded</option>
          <option value="void">Void</option>
        </select>
      </div>
    </div>
    <div class="table-wrap">
      <table id="reservationsTable">
        <thead>
          <tr>
            <th>Code</th>
            <th>Guest</th>
            <th>Property</th>
            <th>Type</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th class="center">Status</th>
            <th class="center">Payment</th>
            <th>Total</th>
            <th class="center">Actions</th>
          </tr>
        </thead>
        <tbody id="reservationsTableBody">
          <tr>
            <td colspan="10" class="empty-state">
              <i class="fa-regular fa-clipboard"></i>
              Loading...
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="pagination-wrap">
      <div class="pagination-info">
        Showing <span id="showingCount">0</span> of <span id="totalCount">0</span> reservations
      </div>
      <div class="pagination-controls">
        <button id="prevPage" onclick="changePage(-1)" class="btn" disabled>← Previous</button>
        <span id="pageInfo">Page 1 of 1</span>
        <button id="nextPage" onclick="changePage(1)" class="btn" disabled>Next →</button>
      </div>
    </div>
  </div>
</div>

<!-- View Modal -->
<div class="overlay" id="viewModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Reservation Details</div>
      <button class="modal-close" onclick="closeModal('viewModal')">&times;</button>
    </div>
    <div class="modal-body" id="viewModalBody">
      <!-- Reservation details will be loaded here -->
    </div>
    <div class="modal-footer" id="viewModalFooter">
      <button class="btn" onclick="closeModal('viewModal')">Close</button>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="overlay" id="editModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Edit Reservation</div>
      <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
    </div>
    <div class="modal-body">
      <form id="editForm" class="form-grid">
        <input type="hidden" id="editReservationId">
        <div class="form-group full">
          <label>Reservation Code</label>
          <input type="text" id="editReservationCode" readonly>
        </div>
        <div class="form-group">
          <label>First Name <span class="req">*</span></label>
          <input type="text" id="editFirstName" required>
        </div>
        <div class="form-group">
          <label>Last Name <span class="req">*</span></label>
          <input type="text" id="editLastName" required>
        </div>
        <div class="form-group">
          <label>Email <span class="req">*</span></label>
          <input type="email" id="editEmail" required>
        </div>
        <div class="form-group">
          <label>Phone <span class="req">*</span></label>
          <input type="tel" id="editPhone" required>
        </div>
        <div class="form-group">
          <label>Check-in Date <span class="req">*</span></label>
          <input type="date" id="editCheckInDate" required>
        </div>
        <div class="form-group">
          <label>Check-in Time <span class="req">*</span></label>
          <input type="time" id="editCheckInTime" required>
        </div>
        <div class="form-group">
          <label>Check-out Date</label>
          <input type="date" id="editCheckOutDate">
        </div>
        <div class="form-group">
          <label>Check-out Time</label>
          <input type="time" id="editCheckOutTime">
        </div>
        <div class="form-group">
          <label>Reservation Status</label>
          <select id="editStatus">
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="checked_in">Checked In</option>
            <option value="checked_out">Checked Out</option>
            <option value="cancelled">Cancelled</option>
            <option value="no_show">No Show</option>
            <option value="waitlisted">Waitlisted</option>
          </select>
        </div>
        <div class="form-group">
          <label>Payment Status</label>
          <select id="editPaymentStatus">
            <option value="unpaid">Unpaid</option>
            <option value="partial">Partial</option>
            <option value="paid">Paid</option>
            <option value="refunded">Refunded</option>
            <option value="void">Void</option>
          </select>
        </div>
        <div class="form-group">
          <label>Adults</label>
          <input type="number" id="editNumAdults" min="1" value="1">
        </div>
        <div class="form-group">
          <label>Children</label>
          <input type="number" id="editNumChildren" min="0" value="0">
        </div>
        <div class="form-group">
          <label>Infants</label>
          <input type="number" id="editNumInfants" min="0" value="0">
        </div>
        <div class="form-group">
          <label>Seniors</label>
          <input type="number" id="editNumSeniors" min="0" value="0">
        </div>
        <div class="form-group full">
          <label>Total Amount</label>
          <input type="number" id="editTotalAmount" step="0.01" min="0">
        </div>
        <div class="form-group full">
          <label>Special Requests</label>
          <textarea id="editSpecialRequests" rows="3"></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('editModal')">Cancel</button>
      <button class="btn btn-primary" onclick="saveReservation()">Save Changes</button>
    </div>
  </div>
</div>

<!-- Custom Alert Modal -->
<div class="alert-overlay" id="alertModal">
  <div class="alert-box">
    <div class="alert-title" id="alertTitle">Confirm Action</div>
    <div class="alert-message" id="alertMessage">Are you sure you want to proceed?</div>
    <div class="alert-actions">
      <button class="alert-btn-cancel" onclick="closeAlert()">Cancel</button>
      <button class="alert-btn-confirm" id="alertConfirmBtn">Confirm</button>
    </div>
  </div>
</div>

<!-- Check In Modal -->
<div class="overlay" id="checkInModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Check In Guest</div>
      <button class="modal-close" onclick="closeModal('checkInModal')">&times;</button>
    </div>
    <div class="modal-body">
      <form id="checkInForm" class="form-grid">
        <input type="hidden" id="checkInReservationId">
        <div class="form-group full">
          <label>Reservation Code</label>
          <input type="text" id="checkInReservationCode" readonly>
        </div>
        <div class="form-group full" id="balanceWarning" style="display:none;background:#FFF3E0;padding:12px;border-radius:8px;border:1px solid #FFE0B2;">
          <label style="color:#E65100;margin-bottom:4px;">⚠️ Outstanding Balance</label>
          <div style="font-size:14px;color:#E65100;font-weight:500;">₱<span id="checkInBalanceAmount">0.00</span></div>
          <div style="font-size:12px;color:#666;margin-top:4px;">Guest has an outstanding balance. Please collect payment before check-in.</div>
        </div>
        <div class="form-group full" id="paymentSection" style="display:none;background:#E8F5E9;padding:12px;border-radius:8px;border:1px solid #A5D6A7;">
          <label style="color:#2E7D32;margin-bottom:8px;">💰 Collect Payment</label>
          <div class="form-group">
            <label>Payment Amount</label>
            <input type="number" id="checkInPaymentAmount" step="0.01" min="0" placeholder="Enter amount">
          </div>
          <div class="form-group">
            <label>Payment Method</label>
            <select id="checkInPaymentMethod">
              <option value="cash">Cash</option>
              <option value="gcash">GCash</option>
              <option value="maya">Maya</option>
              <option value="card">Credit/Debit Card</option>
              <option value="bank_transfer">Bank Transfer</option>
            </select>
          </div>
          <div class="form-group full">
            <label>Reference Number (Optional)</label>
            <input type="text" id="checkInPaymentReference" placeholder="Transaction reference if applicable">
          </div>
        </div>
        <div class="form-group">
          <label>Event Type</label>
          <select id="checkInEventType" required>
            <option value="check_in">Check In</option>
            <option value="early_check_in">Early Check In</option>
          </select>
        </div>
        <div class="form-group">
          <label>Event Date</label>
          <input type="date" id="checkInEventDate" required>
        </div>
        <div class="form-group">
          <label>Event Time (12hr format)</label>
          <input type="text" id="checkInEventTime" placeholder="e.g., 2:30 PM" required>
        </div>
        <div class="form-group full">
          <label>Notes</label>
          <textarea id="checkInNotes" rows="3" placeholder="Optional notes about check-in..."></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('checkInModal')">Cancel</button>
      <button class="btn btn-primary" onclick="processCheckIn()">Process Check In</button>
    </div>
  </div>
</div>

<!-- Check Out Modal -->
<div class="overlay" id="checkOutModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Check Out Guest</div>
      <button class="modal-close" onclick="closeModal('checkOutModal')">&times;</button>
    </div>
    <div class="modal-body">
      <form id="checkOutForm" class="form-grid">
        <input type="hidden" id="checkOutReservationId">
        <div class="form-group full">
          <label>Reservation Code</label>
          <input type="text" id="checkOutReservationCode" readonly>
        </div>
        <div class="form-group">
          <label>Event Type</label>
          <select id="checkOutEventType" required>
            <option value="check_out">Check Out</option>
            <option value="late_check_out">Late Check Out</option>
          </select>
        </div>
        <div class="form-group">
          <label>Event Date & Time</label>
          <input type="datetime-local" id="checkOutEventAt" required>
        </div>
        <div class="form-group full">
          <label>Notes</label>
          <textarea id="checkOutNotes" rows="3" placeholder="Optional notes about check-out..."></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('checkOutModal')">Cancel</button>
      <button class="btn btn-primary" onclick="processCheckOut()">Process Check Out</button>
    </div>
  </div>
</div>

<!-- Walk-In Check-In Modal -->
<div class="overlay" id="walkInModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Walk-In Guest Check-In</div>
      <button class="modal-close" onclick="closeModal('walkInModal')">&times;</button>
    </div>
    <div class="modal-body">
      <form id="walkInForm" class="form-grid">
        <div class="form-group full">
          <label>Property <span class="req">*</span></label>
          <select id="walkInProperty" required onchange="loadAvailableRooms()">
            <option value="">Select Property</option>
          </select>
        </div>
        <div class="form-group full">
          <label>Room (Optional)</label>
          <select id="walkInRoom" onchange="calculateWalkInAmount()">
            <option value="">No Room - General Entry</option>
          </select>
        </div>
        <div class="form-group">
          <label>First Name <span class="req">*</span></label>
          <input type="text" id="walkInFirstName" required>
        </div>
        <div class="form-group">
          <label>Last Name <span class="req">*</span></label>
          <input type="text" id="walkInLastName" required>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" id="walkInEmail">
        </div>
        <div class="form-group">
          <label>Phone <span class="req">*</span></label>
          <input type="tel" id="walkInPhone" required>
        </div>
        <div class="form-group">
          <label>Booking Type <span class="req">*</span></label>
          <select id="walkInBookingType" required onchange="toggleDateFields()">
            <option value="overnight">Overnight</option>
            <option value="hourly">Hourly</option>
            <option value="day_use">Day Use</option>
          </select>
        </div>
        <div class="form-group">
          <label>Check-in Date <span class="req">*</span></label>
          <input type="date" id="walkInCheckInDate" required onchange="updateHourlyCheckoutTime()">
        </div>
        <div class="form-group">
          <label>Check-in Time <span class="req">*</span></label>
          <input type="time" id="walkInCheckInTime" required onchange="updateHourlyCheckoutTime()">
        </div>
        <div class="form-group" id="walkInCheckOutDateGroup">
          <label>Check-out Date <span class="req">*</span></label>
          <input type="date" id="walkInCheckOutDate">
        </div>
        <div class="form-group" id="walkInCheckOutTimeGroup">
          <label>Check-out Time <span class="req">*</span></label>
          <input type="time" id="walkInCheckOutTime">
        </div>
        <div class="form-group" id="walkInDurationGroup" style="display:none">
          <label>Duration (Hours) <span class="req">*</span></label>
          <input type="number" id="walkInDuration" min="1" step="0.5" onchange="calculateWalkInAmount(); updateHourlyCheckoutTime();">
        </div>
        <div class="form-group" id="walkInCalculatedCheckoutGroup" style="display:none">
          <label>Calculated Check-out</label>
          <input type="text" id="walkInCalculatedCheckout" readonly style="background:#f5f5f5">
        </div>
        <div class="form-group">
          <label>Adults <span class="req">*</span></label>
          <input type="number" id="walkInAdults" min="1" value="1" required>
        </div>
        <div class="form-group">
          <label>Children</label>
          <input type="number" id="walkInChildren" min="0" value="0">
        </div>
        <div class="form-group">
          <label>Seniors/PWD</label>
          <input type="number" id="walkInSeniors" min="0" value="0">
        </div>
        <div class="form-group">
          <label>Infants</label>
          <input type="number" id="walkInInfants" min="0" value="0">
        </div>
        <div class="form-group full">
          <label>Total Amount <span class="req">*</span></label>
          <input type="number" id="walkInTotalAmount" step="0.01" min="0" required readonly style="background:#f5f5f5">
        </div>
        <div class="form-group full">
          <label>Payment Amount <span class="req">*</span></label>
          <input type="number" id="walkInPaymentAmount" step="0.01" min="0" required>
        </div>
        <div class="form-group">
          <label>Payment Method <span class="req">*</span></label>
          <select id="walkInPaymentMethod" required>
            <option value="cash">Cash</option>
            <option value="gcash">GCash</option>
            <option value="maya">Maya</option>
            <option value="card">Card</option>
            <option value="bank_transfer">Bank Transfer</option>
          </select>
        </div>
        <div class="form-group">
          <label>Reference Number</label>
          <input type="text" id="walkInReference" placeholder="Transaction reference">
        </div>
        <div class="form-group full">
          <label>Notes</label>
          <textarea id="walkInNotes" rows="3" placeholder="Optional notes..."></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('walkInModal')">Cancel</button>
      <button class="btn btn-primary" onclick="processWalkIn()">Process Walk-In Check-In</button>
    </div>
  </div>
</div>

<script>
  let reservationsData = [];
  let currentPage = 1;
  let itemsPerPage = 10;
  let totalItems = 0;
  let currentViewReservationId = null;

  document.addEventListener('DOMContentLoaded', function() {
    fetchReservationsData();
    checkUrlParams();
    loadProperties();
  });

  async function fetchReservationsData(page = 1) {
    try {
      const response = await fetch(`../api/reservations.php?action=list&page=${page}&limit=${itemsPerPage}`);
      const result = await response.json();
      if (result.success) {
        reservationsData = result.data;
        totalItems = result.pagination?.total || 0;
        currentPage = result.pagination?.page || 1;
        renderReservationsTable();
        updateStats();
        updatePaginationInfo();
      } else {
        showError('reservationsTableBody', result.message);
      }
    } catch (error) {
      console.error('Error fetching reservations:', error);
      showError('reservationsTableBody', 'Failed to load reservations data');
    }
  }

  async function loadProperties() {
    try {
      const response = await fetch('../api/properties.php?action=list');
      const result = await response.json();
      if (result.success && result.data && result.data.data) {
        const select = document.getElementById('walkInProperty');
        select.innerHTML = '<option value="">Select Property</option>';
        result.data.data.forEach(prop => {
          select.innerHTML += `<option value="${prop.property_id}">${prop.property_name}</option>`;
        });
      }
    } catch (error) {
      console.error('Error loading properties:', error);
    }
  }

  // Store room data and entrance fees for amount calculation
  let availableRooms = [];
  let entranceFees = { hourly: 0, day_use: 0, overnight: 0 };

  async function loadEntranceFees() {
    try {
      const response = await fetch('../api/system_settings.php?action=entrance_fees');
      const result = await response.json();
      if (result.success && result.data) {
        entranceFees = result.data;
      }
    } catch (error) {
      console.error('Error loading entrance fees:', error);
    }
  }

  async function loadAvailableRooms() {
    const propertyId = document.getElementById('walkInProperty').value;
    const roomSelect = document.getElementById('walkInRoom');
    roomSelect.innerHTML = '<option value="">No Room - General Entry</option>';
    availableRooms = [];

    if (!propertyId) return;

    try {
      const response = await fetch(`../api/rooms.php?action=list&property_id=${propertyId}&per_page=100`);
      const result = await response.json();
      if (result.success && result.data && result.data.data) {
        // Filter rooms by selected property
        const rooms = result.data.data.filter(room => room.property_id === propertyId);
        availableRooms = rooms;
        rooms.forEach(room => {
          roomSelect.innerHTML += `<option value="${room.room_id}" data-overnight="${room.override_price_per_night || 0}" data-hourly="${room.override_price_per_hour || 0}" data-dayuse="${room.override_price_day_use || 0}">${room.room_number} - ${room.room_name}</option>`;
        });
      }
    } catch (error) {
      console.error('Error loading rooms:', error);
    }
  }

  function calculateWalkInAmount() {
    const roomSelect = document.getElementById('walkInRoom');
    const bookingType = document.getElementById('walkInBookingType').value;
    const duration = parseFloat(document.getElementById('walkInDuration').value) || 0;
    const totalAmountInput = document.getElementById('walkInTotalAmount');

    const selectedOption = roomSelect.options[roomSelect.selectedIndex];
    let amount = 0;

    if (selectedOption && selectedOption.value) {
      // Room selected - calculate based on room rates
      if (bookingType === 'overnight') {
        amount = parseFloat(selectedOption.dataset.overnight) || 0;
      } else if (bookingType === 'hourly') {
        const hourlyRate = parseFloat(selectedOption.dataset.hourly) || 0;
        amount = hourlyRate * duration;
      } else if (bookingType === 'day_use') {
        amount = parseFloat(selectedOption.dataset.dayuse) || 0;
      }
    } else {
      // No room selected - use entrance fee from database
      if (bookingType === 'overnight') {
        amount = entranceFees.overnight || 0;
      } else if (bookingType === 'hourly') {
        amount = entranceFees.hourly || 0;
      } else if (bookingType === 'day_use') {
        amount = entranceFees.day_use || 0;
      }
    }

    totalAmountInput.value = amount.toFixed(2);
    totalAmountInput.readOnly = true;
    totalAmountInput.style.background = '#f5f5f5';
  }

  function updateHourlyCheckoutTime() {
    const checkInDate = document.getElementById('walkInCheckInDate').value;
    const checkInTime = document.getElementById('walkInCheckInTime').value;
    const duration = parseFloat(document.getElementById('walkInDuration').value) || 0;
    const calculatedCheckoutInput = document.getElementById('walkInCalculatedCheckout');

    if (checkInDate && checkInTime && duration > 0) {
      const checkInDateTime = new Date(`${checkInDate}T${checkInTime}`);
      const checkoutDateTime = new Date(checkInDateTime.getTime() + duration * 60 * 60 * 1000);

      const checkoutDate = checkoutDateTime.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
      const checkoutTime = checkoutDateTime.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });

      calculatedCheckoutInput.value = `${checkoutDate} at ${checkoutTime}`;
    } else {
      calculatedCheckoutInput.value = '';
    }
  }

  function toggleDateFields() {
    const bookingType = document.getElementById('walkInBookingType').value;
    const checkOutDateGroup = document.getElementById('walkInCheckOutDateGroup');
    const checkOutTimeGroup = document.getElementById('walkInCheckOutTimeGroup');
    const durationGroup = document.getElementById('walkInDurationGroup');

    const calculatedCheckoutGroup = document.getElementById('walkInCalculatedCheckoutGroup');

    if (bookingType === 'hourly') {
      checkOutDateGroup.style.display = 'none';
      checkOutTimeGroup.style.display = 'none';
      durationGroup.style.display = 'block';
      calculatedCheckoutGroup.style.display = 'block';
      updateHourlyCheckoutTime();
    } else {
      checkOutDateGroup.style.display = 'block';
      checkOutTimeGroup.style.display = 'block';
      durationGroup.style.display = 'none';
      calculatedCheckoutGroup.style.display = 'none';
    }

    // Recalculate amount when booking type changes
    calculateWalkInAmount();
  }

  async function openWalkInModal() {
    // Load entrance fees first
    await loadEntranceFees();

    // Set current date/time as defaults
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    document.getElementById('walkInCheckInDate').value = `${year}-${month}-${day}`;
    document.getElementById('walkInCheckInTime').value = `${hours}:${minutes}`;

    // Set default check-out to tomorrow for overnight
    const tomorrow = new Date(now);
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowYear = tomorrow.getFullYear();
    const tomorrowMonth = String(tomorrow.getMonth() + 1).padStart(2, '0');
    const tomorrowDay = String(tomorrow.getDate()).padStart(2, '0');
    document.getElementById('walkInCheckOutDate').value = `${tomorrowYear}-${tomorrowMonth}-${tomorrowDay}`;
    document.getElementById('walkInCheckOutTime').value = '11:00';

    // Reset and calculate initial amount based on default booking type
    document.getElementById('walkInRoom').value = '';
    calculateWalkInAmount();

    document.getElementById('walkInModal').classList.add('open');
  }

  async function processWalkIn() {
    const propertyId = document.getElementById('walkInProperty').value;
    const roomId = document.getElementById('walkInRoom').value;
    const firstName = document.getElementById('walkInFirstName').value;
    const lastName = document.getElementById('walkInLastName').value;
    const email = document.getElementById('walkInEmail').value;
    const phone = document.getElementById('walkInPhone').value;
    const bookingType = document.getElementById('walkInBookingType').value;
    const checkInDate = document.getElementById('walkInCheckInDate').value;
    const checkInTime = document.getElementById('walkInCheckInTime').value;
    const checkOutDate = document.getElementById('walkInCheckOutDate').value;
    const checkOutTime = document.getElementById('walkInCheckOutTime').value;
    const duration = document.getElementById('walkInDuration').value;
    const adults = document.getElementById('walkInAdults').value;
    const children = document.getElementById('walkInChildren').value;
    const seniors = document.getElementById('walkInSeniors').value;
    const infants = document.getElementById('walkInInfants').value;
    const totalAmount = document.getElementById('walkInTotalAmount').value;
    const paymentAmount = document.getElementById('walkInPaymentAmount').value;
    const paymentMethod = document.getElementById('walkInPaymentMethod').value;
    const reference = document.getElementById('walkInReference').value;
    const notes = document.getElementById('walkInNotes').value;

    if (!propertyId || !firstName || !lastName || !phone || !checkInDate || !checkInTime || !totalAmount || !paymentAmount) {
      showAlert('Error', 'Please fill in all required fields', () => {});
      return;
    }

    if (bookingType === 'hourly' && !duration) {
      showAlert('Error', 'Please specify duration for hourly booking', () => {});
      return;
    }

    if (bookingType !== 'hourly' && (!checkOutDate || !checkOutTime)) {
      showAlert('Error', 'Please specify check-out date and time', () => {});
      return;
    }

    showAlert(
      'Confirm Walk-In Check-In',
      `Process walk-in check-in for ${firstName} ${lastName}?`,
      () => confirmProcessWalkIn({
        propertyId, roomId, firstName, lastName, email, phone, bookingType,
        checkInDate, checkInTime, checkOutDate, checkOutTime, duration,
        adults, children, seniors, infants, totalAmount, paymentAmount,
        paymentMethod, reference, notes
      })
    );
  }

  async function confirmProcessWalkIn(data) {
    try {
      const response = await fetch('../api/reservations.php?action=create_walkin', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>'
        },
        body: JSON.stringify(data)
      });
      const result = await response.json();

      if (result.success) {
        closeModal('walkInModal');
        fetchReservationsData(currentPage);
        showAlert('Success', 'Walk-in guest checked in successfully', () => {});
      } else {
        showAlert('Error', 'Failed to process walk-in check-in: ' + (result.message || 'Unknown error'), () => {});
      }
    } catch (error) {
      console.error('Error processing walk-in check-in:', error);
      showAlert('Error', 'Failed to process walk-in check-in', () => {});
    }
  }

  function updateStats() {
    const total = reservationsData.length;
    const pending = reservationsData.filter(r => r.reservation_status === 'pending').length;
    const confirmed = reservationsData.filter(r => r.reservation_status === 'confirmed').length;
    const checkedIn = reservationsData.filter(r => r.reservation_status === 'checked_in').length;

    document.getElementById('statTotal').textContent = total;
    document.getElementById('statPending').textContent = pending;
    document.getElementById('statConfirmed').textContent = confirmed;
    document.getElementById('statCheckedIn').textContent = checkedIn;
  }

  function updatePaginationInfo() {
    const showingCount = reservationsData.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);

    document.getElementById('showingCount').textContent = showingCount;
    document.getElementById('totalCount').textContent = totalItems;
    document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;

    document.getElementById('prevPage').disabled = currentPage <= 1;
    document.getElementById('nextPage').disabled = currentPage >= totalPages;
  }

  function changePage(delta) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const newPage = currentPage + delta;

    if (newPage >= 1 && newPage <= totalPages) {
      fetchReservationsData(newPage);
    }
  }

  function formatDate(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', {
      month: 'short',
      day: '2-digit',
      year: 'numeric'
    });
  }

  function formatTime(timeStr) {
    if (!timeStr) return '-';
    const [h, m] = timeStr.split(':');
    const hour = parseInt(h);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    return `${hour % 12 || 12}:${m} ${ampm}`;
  }

  function renderReservationsTable() {
    const tbody = document.getElementById('reservationsTableBody');
    if (!tbody) return;

    if (reservationsData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="10" class="empty-state"><i><i class="fa-regular fa-clipboard"></i></i>No reservations found</td></tr>`;
      return;
    }

    tbody.innerHTML = reservationsData.map(r => `
      <tr class="reservation-row cursor-pointer" onclick="viewReservation('${r.reservation_id}')">
        <td>
          <strong>${r.reservation_code || '-'}</strong>
        </td>
        <td>
          <div>${r.guest_name || '-'}</div>
          <div style="font-size:12px;color:#666">${r.guest_email || '-'}</div>
        </td>
        <td>${r.property_name || '-'}</td>
        <td>
          <span class="status-badge" style="background:#E6F1FB;color:#0C447C">${r.booking_type || '-'}</span>
        </td>
        <td>
          <div>${formatDate(r.check_in_date)}</div>
          <div style="font-size:12px;color:#666">${formatTime(r.check_in_time)}</div>
        </td>
        <td>
          <div>${formatDate(r.check_out_date)}</div>
          <div style="font-size:12px;color:#666">${r.check_out_date ? formatTime(r.check_out_time) : ''}</div>
        </td>
        <td class="center">
          <span class="status-badge ${r.reservation_status}">${r.reservation_status ? r.reservation_status.replace(/_/g, ' ') : '-'}</span>
        </td>
        <td class="center">
          <span class="status-badge ${r.payment_status}">${r.payment_status ? r.payment_status.replace(/_/g, ' ') : '-'}</span>
        </td>
        <td>
          <strong>${parseFloat(r.total_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</strong>
        </td>
        <td class="center">
          <div class="actions-cell">
            <button onclick="event.stopPropagation(); viewReservation('${r.reservation_id}')" class="action-btn"><i class="fa-solid fa-eye"></i> View</button>
            ${r.reservation_status === 'pending' || r.reservation_status === 'confirmed' ? `<button onclick="event.stopPropagation(); openCheckInModal('${r.reservation_id}')" class="action-btn" style="background:#E8F5E9;color:#2E7D32;border-color:#A5D6A7"><i class="fa-solid fa-right-to-bracket"></i> Check In</button>` : ''}
            ${r.reservation_status === 'checked_in' ? `<button onclick="event.stopPropagation(); openCheckOutModal('${r.reservation_id}')" class="action-btn" style="background:#E3F2FD;color:#1565C0;border-color:#90CAF9"><i class="fa-solid fa-right-from-bracket"></i> Check Out</button>` : ''}
            <button onclick="event.stopPropagation(); editReservation('${r.reservation_id}')" class="action-btn"><i class="fa-solid fa-pencil"></i> Edit</button>
            <button onclick="event.stopPropagation(); deleteReservation('${r.reservation_id}')" class="action-btn danger"><i class="fa-solid fa-trash"></i> Delete</button>
          </div>
        </td>
      </tr>
    `).join('');
  }

  function showError(tbodyId, msg) {
    const tbody = document.getElementById(tbodyId);
    if (tbody) tbody.innerHTML = `<tr><td colspan="10" class="empty-state"><i>⚠</i>${msg}</td></tr>`;
  }

  function filterTable() {
    const search = document.getElementById('searchInput').value.toUpperCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const paymentFilter = document.getElementById('paymentFilter').value;
    const rows = document.querySelectorAll('#reservationsTable .reservation-row');

    rows.forEach(row => {
      const text = row.textContent.toUpperCase();
      const statusCell = row.querySelector('td:nth-child(7)');
      const paymentCell = row.querySelector('td:nth-child(8)');

      const matchesSearch = text.includes(search);
      const matchesStatus = !statusFilter || (statusCell && statusCell.textContent.toLowerCase().includes(statusFilter.toLowerCase().replace('_', ' ')));
      const matchesPayment = !paymentFilter || (paymentCell && paymentCell.textContent.toLowerCase().includes(paymentFilter.toLowerCase()));

      row.style.display = matchesSearch && matchesStatus && matchesPayment ? '' : 'none';
    });
  }

  function viewReservation(reservationId) {
    fetchReservationDetails(reservationId);
  }

  function editReservation(reservationId) {
    fetchReservationForEdit(reservationId);
  }

  function deleteReservation(reservationId) {
    showAlert(
      'Delete Reservation',
      'Are you sure you want to delete this reservation? This action cannot be undone.',
      () => confirmDeleteReservation(reservationId)
    );
  }

  function approveReservation(reservationId) {
    showAlert(
      'Approve Reservation',
      'Are you sure you want to approve this reservation? A confirmation email will be sent to the guest.',
      () => confirmApproveReservation(reservationId)
    );
  }

  function rejectReservation(reservationId) {
    showAlert(
      'Reject Reservation',
      'Are you sure you want to reject this reservation? A rejection email will be sent to the guest.',
      () => confirmRejectReservation(reservationId)
    );
  }

  function approveReservationFromModal() {
    if (currentViewReservationId) {
      approveReservation(currentViewReservationId);
    }
  }

  function rejectReservationFromModal() {
    if (currentViewReservationId) {
      rejectReservation(currentViewReservationId);
    }
  }

  function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('open');
  }

  function showAlert(title, message, onConfirm) {
    document.getElementById('alertTitle').textContent = title;
    document.getElementById('alertMessage').textContent = message;
    document.getElementById('alertModal').classList.add('open');
    
    const confirmBtn = document.getElementById('alertConfirmBtn');
    confirmBtn.onclick = function() {
      closeAlert();
      onConfirm();
    };
  }

  function closeAlert() {
    document.getElementById('alertModal').classList.remove('open');
  }

  async function fetchReservationDetails(reservationId) {
    try {
      const response = await fetch(`../api/reservations.php?action=single&id=${reservationId}`);
      const result = await response.json();
      
      if (result.success && result.data) {
        currentViewReservationId = reservationId;
        renderViewModal(result.data);
        document.getElementById('viewModal').classList.add('open');
      } else {
        showAlert('Error', 'Failed to load reservation details', () => {});
      }
    } catch (error) {
      console.error('Error fetching reservation details:', error);
      showAlert('Error', 'Failed to load reservation details', () => {});
    }
  }

  function renderViewModal(data) {
    const body = document.getElementById('viewModalBody');
    const footer = document.getElementById('viewModalFooter');
    
    body.innerHTML = `
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px">
        <div>
          <h3 style="margin:0 0 12px;font-size:14px;font-weight:600;color:#185FA5;text-transform:uppercase;letter-spacing:0.05em">Reservation Information</h3>
          <div class="detail-row">
            <div class="detail-label">Reservation Code</div>
            <div class="detail-value"><strong>${data.reservation_code || '-'}</strong></div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Reservation Status</div>
            <div class="detail-value"><span class="status-badge ${data.reservation_status}">${data.reservation_status ? data.reservation_status.replace(/_/g, ' ') : '-'}</span></div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Created At</div>
            <div class="detail-value">${formatDate(data.created_at)} ${formatTime(data.created_at)}</div>
          </div>
        </div>

        <div>
          <h3 style="margin:0 0 12px;font-size:14px;font-weight:600;color:#185FA5;text-transform:uppercase;letter-spacing:0.05em">Guest Information</h3>
          <div class="detail-row">
            <div class="detail-label">Guest Name</div>
            <div class="detail-value">${data.guest_name || '-'}</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Email</div>
            <div class="detail-value">${data.guest_email || '-'}</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Phone</div>
            <div class="detail-value">${data.guest_phone || '-'}</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Guests</div>
            <div class="detail-value">${data.num_adults || 0} Adult${(data.num_adults || 0) > 1 ? 's' : ''}${(data.num_children || 0) > 0 ? ', ' + data.num_children + ' Child' + ((data.num_children || 0) > 1 ? 'ren' : '') : ''}</div>
          </div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px">
        <div>
          <h3 style="margin:0 0 12px;font-size:14px;font-weight:600;color:#185FA5;text-transform:uppercase;letter-spacing:0.05em">Booking Details</h3>
          <div class="detail-row">
            <div class="detail-label">Property</div>
            <div class="detail-value">${data.property_name || '-'}</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Booking Type</div>
            <div class="detail-value">${data.booking_type || '-'}</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Check-in</div>
            <div class="detail-value">${formatDate(data.check_in_date)} ${formatTime(data.check_in_time)}</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Check-out</div>
            <div class="detail-value">${formatDate(data.check_out_date)} ${formatTime(data.check_out_time)}</div>
          </div>
        </div>

        <div>
          <h3 style="margin:0 0 12px;font-size:14px;font-weight:600;color:#185FA5;text-transform:uppercase;letter-spacing:0.05em">Payment Information</h3>
          <div class="detail-row">
            <div class="detail-label">Payment Status</div>
            <div class="detail-value"><span class="status-badge ${data.payment_status}">${data.payment_status ? data.payment_status.replace(/_/g, ' ') : '-'}</span></div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Total Amount</div>
            <div class="detail-value"><strong>₱${parseFloat(data.total_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</strong></div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Amount Paid</div>
            <div class="detail-value">₱${parseFloat(data.paid_amount || data.amount_paid || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
          </div>
          ${data.balance_amount > 0 ? `
          <div class="detail-row">
            <div class="detail-label">Balance Due</div>
            <div class="detail-value" style="color:#C62828;font-weight:600">₱${parseFloat(data.balance_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
          </div>
          ` : ''}
          <div class="detail-row">
            <div class="detail-label">Payment Method</div>
            <div class="detail-value">${data.payment_method_label || '-'}</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Payment Option</div>
            <div class="detail-value">${data.payment_option ? data.payment_option.charAt(0).toUpperCase() + data.payment_option.slice(1).replace(/_/g, ' ') : '-'}</div>
          </div>
          ${data.transaction_reference ? `
          <div class="detail-row">
            <div class="detail-label">Transaction Reference</div>
            <div class="detail-value">${data.transaction_reference}</div>
          </div>
          ` : ''}
          ${data.payment_attachments ? `
          <div class="detail-row">
            <div class="detail-label">Payment Attachment</div>
            <div class="detail-value">
              ${data.payment_attachments.split(',').map(attachment => `
                <a href="../../${attachment.trim()}" target="_blank" style="color:#185FA5;text-decoration:none;font-size:12px;">
                  <i class="fa-solid fa-paperclip"></i> View Attachment
                </a>
              `).join('<br>')}
            </div>
          </div>
          ` : ''}
        </div>
      </div>

      <div>
        <h3 style="margin:0 0 12px;font-size:14px;font-weight:600;color:#185FA5;text-transform:uppercase;letter-spacing:0.05em">Additional Information</h3>
        <div class="detail-row">
          <div class="detail-label">Source Channel</div>
          <div class="detail-value">${data.source_channel ? data.source_channel.charAt(0).toUpperCase() + data.source_channel.slice(1) : '-'}</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">Special Requests</div>
          <div class="detail-value">${data.special_requests || '-'}</div>
        </div>
      </div>
    `;

    // Update footer with approve/reject buttons if status is pending
    if (data.reservation_status === 'pending') {
      footer.innerHTML = `
        <button class="btn btn-primary" onclick="approveReservationFromModal()" style="background:#2E7D32;border-color:#2E7D32">✓ Approve</button>
        <button class="btn danger" onclick="rejectReservationFromModal()" style="background:#C62828;border-color:#C62828;color:#fff">✕ Reject</button>
        <button class="btn" onclick="closeModal('viewModal')">Close</button>
      `;
    } else {
      footer.innerHTML = `
        <button class="btn" onclick="closeModal('viewModal')">Close</button>
      `;
    }
  }

  async function fetchReservationForEdit(reservationId) {
    try {
      const response = await fetch(`../api/reservations.php?action=single&id=${reservationId}`);
      const result = await response.json();
      
      if (result.success && result.data) {
        populateEditForm(result.data);
        document.getElementById('editModal').classList.add('open');
      } else {
        showAlert('Error', 'Failed to load reservation details', () => {});
      }
    } catch (error) {
      console.error('Error fetching reservation details:', error);
      showAlert('Error', 'Failed to load reservation details', () => {});
    }
  }

  function populateEditForm(data) {
    document.getElementById('editReservationId').value = data.reservation_id;
    document.getElementById('editReservationCode').value = data.reservation_code;
    document.getElementById('editFirstName').value = data.walkin_first_name || data.first_name || '';
    document.getElementById('editLastName').value = data.walkin_last_name || data.last_name || '';
    document.getElementById('editEmail').value = data.walkin_email || data.email || '';
    document.getElementById('editPhone').value = data.walkin_phone || data.phone || '';
    document.getElementById('editCheckInDate').value = data.check_in_date || '';
    document.getElementById('editCheckInTime').value = data.check_in_time || '';
    document.getElementById('editCheckOutDate').value = data.check_out_date || '';
    document.getElementById('editCheckOutTime').value = data.check_out_time || '';
    document.getElementById('editStatus').value = data.reservation_status || 'pending';
    document.getElementById('editPaymentStatus').value = data.payment_status || 'unpaid';
    document.getElementById('editNumAdults').value = data.num_adults || 1;
    document.getElementById('editNumChildren').value = data.num_children || 0;
    document.getElementById('editNumInfants').value = data.num_infants || 0;
    document.getElementById('editNumSeniors').value = data.num_seniors || 0;
    document.getElementById('editTotalAmount').value = data.total_amount || 0;
    document.getElementById('editSpecialRequests').value = data.special_requests || '';
  }

  async function saveReservation() {
    const reservationId = document.getElementById('editReservationId').value;
    const formData = {
      reservation_id: reservationId,
      first_name: document.getElementById('editFirstName').value,
      last_name: document.getElementById('editLastName').value,
      email: document.getElementById('editEmail').value,
      phone: document.getElementById('editPhone').value,
      check_in_date: document.getElementById('editCheckInDate').value,
      check_in_time: document.getElementById('editCheckInTime').value,
      check_out_date: document.getElementById('editCheckOutDate').value,
      check_out_time: document.getElementById('editCheckOutTime').value,
      reservation_status: document.getElementById('editStatus').value,
      payment_status: document.getElementById('editPaymentStatus').value,
      num_adults: parseInt(document.getElementById('editNumAdults').value) || 1,
      num_children: parseInt(document.getElementById('editNumChildren').value) || 0,
      num_infants: parseInt(document.getElementById('editNumInfants').value) || 0,
      num_seniors: parseInt(document.getElementById('editNumSeniors').value) || 0,
      total_amount: parseFloat(document.getElementById('editTotalAmount').value) || 0,
      special_requests: document.getElementById('editSpecialRequests').value
    };

    try {
      const response = await fetch('../api/reservations.php?action=update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>'
        },
        body: JSON.stringify(formData)
      });
      const result = await response.json();
      
      if (result.success) {
        closeModal('editModal');
        fetchReservationsData(currentPage);
        showAlert('Success', 'Reservation updated successfully', () => {});
      } else {
        showAlert('Error', 'Failed to update reservation: ' + (result.message || 'Unknown error'), () => {});
      }
    } catch (error) {
      console.error('Error updating reservation:', error);
      showAlert('Error', 'Failed to update reservation', () => {});
    }
  }

  async function confirmDeleteReservation(reservationId) {
    try {
      const response = await fetch(`../api/reservations.php?action=delete`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>'
        },
        body: JSON.stringify({
          reservation_id: reservationId
        })
      });
      const result = await response.json();
      
      if (result.success) {
        closeModal('alertModal');
        fetchReservationsData(currentPage);
        showAlert('Success', 'Reservation deleted successfully', () => {});
      } else {
        showAlert('Error', 'Failed to delete reservation: ' + (result.message || 'Unknown error'), () => {});
      }
    } catch (error) {
      console.error('Error deleting reservation:', error);
      showAlert('Error', 'Failed to delete reservation', () => {});
    }
  }

  async function confirmApproveReservation(reservationId) {
    try {
      const response = await fetch(`../api/reservations.php?action=update_status`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>'
        },
        body: JSON.stringify({
          reservation_id: reservationId,
          status: 'confirmed',
          send_notification: true
        })
      });
      const result = await response.json();
      
      if (result.success) {
        closeModal('alertModal');
        fetchReservationsData(currentPage);
        showAlert('Success', 'Reservation approved successfully', () => {});
      } else {
        showAlert('Error', 'Failed to approve reservation: ' + (result.message || 'Unknown error'), () => {});
      }
    } catch (error) {
      console.error('Error approving reservation:', error);
      showAlert('Error', 'Failed to approve reservation', () => {});
    }
  }

  async function confirmRejectReservation(reservationId) {
    try {
      const response = await fetch(`../api/reservations.php?action=update_status`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>'
        },
        body: JSON.stringify({
          reservation_id: reservationId,
          status: 'cancelled',
          send_notification: true
        })
      });
      const result = await response.json();
      
      if (result.success) {
        closeModal('alertModal');
        fetchReservationsData(currentPage);
        showAlert('Success', 'Reservation rejected successfully', () => {});
      } else {
        showAlert('Error', 'Failed to reject reservation: ' + (result.message || 'Unknown error'), () => {});
      }
    } catch (error) {
      console.error('Error rejecting reservation:', error);
      showAlert('Error', 'Failed to reject reservation', () => {});
    }
  }

  async function openCheckInModal(reservationId) {
    try {
      const response = await fetch(`../api/reservations.php?action=single&id=${reservationId}`);
      const result = await response.json();
      
      if (result.success && result.data) {
        document.getElementById('checkInReservationId').value = reservationId;
        document.getElementById('checkInReservationCode').value = result.data.reservation_code || '';
        
        // Check for outstanding balance
        const balanceAmount = parseFloat(result.data.balance_amount || 0);
        const balanceWarning = document.getElementById('balanceWarning');
        const paymentSection = document.getElementById('paymentSection');
        
        if (balanceAmount > 0) {
          document.getElementById('checkInBalanceAmount').textContent = balanceAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
          document.getElementById('checkInPaymentAmount').value = balanceAmount.toFixed(2);
          balanceWarning.style.display = 'block';
          paymentSection.style.display = 'block';
        } else {
          balanceWarning.style.display = 'none';
          paymentSection.style.display = 'none';
          document.getElementById('checkInPaymentAmount').value = '';
        }
        
        // Set current date/time as default
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = now.getHours();
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const hours12 = hours % 12 || 12;
        
        document.getElementById('checkInEventDate').value = `${year}-${month}-${day}`;
        document.getElementById('checkInEventTime').value = `${String(hours12).padStart(2, '0')}:${minutes} ${ampm}`;
        
        document.getElementById('checkInModal').classList.add('open');
      } else {
        showAlert('Error', 'Failed to load reservation details', () => {});
      }
    } catch (error) {
      console.error('Error loading reservation details:', error);
      showAlert('Error', 'Failed to load reservation details', () => {});
    }
  }

  async function openCheckOutModal(reservationId) {
    try {
      const response = await fetch(`../api/reservations.php?action=single&id=${reservationId}`);
      const result = await response.json();
      
      if (result.success && result.data) {
        document.getElementById('checkOutReservationId').value = reservationId;
        document.getElementById('checkOutReservationCode').value = result.data.reservation_code || '';
        
        // Set current date/time as default
        const now = new Date();
        const offset = now.getTimezoneOffset() * -1;
        const localISOTime = new Date(now.getTime() - offset * 60000).toISOString().slice(0, 16);
        document.getElementById('checkOutEventAt').value = localISOTime;
        
        document.getElementById('checkOutModal').classList.add('open');
      } else {
        showAlert('Error', 'Failed to load reservation details', () => {});
      }
    } catch (error) {
      console.error('Error loading reservation details:', error);
      showAlert('Error', 'Failed to load reservation details', () => {});
    }
  }

  async function processCheckIn() {
    const reservationId = document.getElementById('checkInReservationId').value;
    const eventType = document.getElementById('checkInEventType').value;
    const eventDate = document.getElementById('checkInEventDate').value;
    const eventTime = document.getElementById('checkInEventTime').value;
    const notes = document.getElementById('checkInNotes').value;

    if (!reservationId || !eventDate || !eventTime) {
      showAlert('Error', 'Please fill in all required fields', () => {});
      return;
    }

    // Combine date and time into datetime format
    const eventAt = `${eventDate} ${eventTime}`;

    const eventTypeName = eventType === 'early_check_in' ? 'Early Check In' : 'Check In';
    showAlert(
      'Confirm Check In',
      `Are you sure you want to process ${eventTypeName} for this reservation?`,
      () => confirmProcessCheckIn(reservationId, eventType, eventAt, notes)
    );
  }

  async function confirmProcessCheckIn(reservationId, eventType, eventAt, notes) {
    try {
      // Process payment if provided
      const paymentAmount = parseFloat(document.getElementById('checkInPaymentAmount').value || 0);
      const paymentMethod = document.getElementById('checkInPaymentMethod').value;
      const paymentReference = document.getElementById('checkInPaymentReference').value;
      
      if (paymentAmount > 0) {
        const paymentId = 'pay-' + Date.now();
        const paymentResponse = await fetch('../api/payments.php?action=create', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>'
          },
          body: JSON.stringify({
            payment_id: paymentId,
            reservation_id: reservationId,
            method_id: null, // Will be determined by method_code
            transaction_type: 'payment',
            amount: paymentAmount,
            reference_number: paymentReference || null,
            payment_status: 'paid',
            payment_date: new Date().toISOString().split('T')[0],
            payment_time: new Date().toTimeString().split(' ')[0],
            payment_method_code: paymentMethod
          })
        });
        const paymentResult = await paymentResponse.json();
        
        if (!paymentResult.success) {
          showAlert('Error', 'Failed to process payment: ' + (paymentResult.message || 'Unknown error'), () => {});
          return;
        }
      }

      // Create check-in log
      const logId = 'log-' + Date.now();
      const logResponse = await fetch('../api/check_in_logs.php?action=create', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>'
        },
        body: JSON.stringify({
          log_id: logId,
          reservation_id: reservationId,
          event_type: eventType,
          event_at: eventAt,
          processed_by: '<?php echo htmlspecialchars($_SESSION["user_id"] ?? "", ENT_QUOTES, "UTF-8"); ?>',
          notes: notes
        })
      });
      const logResult = await logResponse.json();

      if (logResult.success) {
        // Update reservation status to checked_in
        const statusResponse = await fetch(`../api/reservations.php?action=update_status`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>'
          },
          body: JSON.stringify({
            reservation_id: reservationId,
            status: 'checked_in',
            send_notification: false
          })
        });
        const statusResult = await statusResponse.json();

        if (statusResult.success) {
          closeModal('checkInModal');
          fetchReservationsData(currentPage);
          const successMsg = paymentAmount > 0 
            ? `Guest checked in successfully. Payment of ₱${paymentAmount.toLocaleString()} collected.`
            : 'Guest checked in successfully.';
          showAlert('Success', successMsg, () => {});
        } else {
          showAlert('Error', 'Check-in log created but failed to update reservation status', () => {});
        }
      } else {
        showAlert('Error', 'Failed to create check-in log: ' + (logResult.message || 'Unknown error'), () => {});
      }
    } catch (error) {
      console.error('Error processing check-in:', error);
      showAlert('Error', 'Failed to process check-in', () => {});
    }
  }

  async function processCheckOut() {
    const reservationId = document.getElementById('checkOutReservationId').value;
    const eventType = document.getElementById('checkOutEventType').value;
    const eventAt = document.getElementById('checkOutEventAt').value;
    const notes = document.getElementById('checkOutNotes').value;

    if (!reservationId || !eventAt) {
      showAlert('Error', 'Please fill in all required fields', () => {});
      return;
    }

    const eventTypeName = eventType === 'late_check_out' ? 'Late Check Out' : 'Check Out';
    showAlert(
      'Confirm Check Out',
      `Are you sure you want to process ${eventTypeName} for this reservation?`,
      () => confirmProcessCheckOut(reservationId, eventType, eventAt, notes)
    );
  }

  async function confirmProcessCheckOut(reservationId, eventType, eventAt, notes) {
    try {
      // Create check-out log
      const logId = 'log-' + Date.now();
      const logResponse = await fetch('../api/check_in_logs.php?action=create', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>'
        },
        body: JSON.stringify({
          log_id: logId,
          reservation_id: reservationId,
          event_type: eventType,
          event_at: eventAt,
          processed_by: '<?php echo htmlspecialchars($_SESSION["user_id"] ?? "", ENT_QUOTES, "UTF-8"); ?>',
          notes: notes
        })
      });
      const logResult = await logResponse.json();

      if (logResult.success) {
        // Update reservation status to checked_out
        const statusResponse = await fetch(`../api/reservations.php?action=update_status`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>'
          },
          body: JSON.stringify({
            reservation_id: reservationId,
            status: 'checked_out',
            send_notification: false
          })
        });
        const statusResult = await statusResponse.json();

        if (statusResult.success) {
          closeModal('checkOutModal');
          fetchReservationsData(currentPage);
          showAlert('Success', 'Guest checked out successfully', () => {});
        } else {
          showAlert('Error', 'Check-out log created but failed to update reservation status', () => {});
        }
      } else {
        showAlert('Error', 'Failed to create check-out log: ' + (logResult.message || 'Unknown error'), () => {});
      }
    } catch (error) {
      console.error('Error processing check-out:', error);
      showAlert('Error', 'Failed to process check-out', () => {});
    }
  }

  function checkUrlParams() {
    const urlParams = new URLSearchParams(window.location.search);
    const reservationId = urlParams.get('id');
    if (reservationId) {
      viewReservation(reservationId);
    }
  }
</script>