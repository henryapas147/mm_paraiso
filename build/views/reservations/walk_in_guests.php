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
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
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

  .stat-value.orange {
    color: #E65100
  }

  .stat-value.grey {
    color: #666
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

  tbody tr.cursor-pointer {
    cursor: pointer
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

  .status-badge.paid {
    background: #E8F5E9;
    color: #2E7D32
  }

  .status-badge.partial {
    background: #FFF3E0;
    color: #E65100
  }

  .status-badge.unpaid {
    background: #FFEBEE;
    color: #C62828
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
    max-width: 820px;
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
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    background: #fff;
    color: #1a1a1a;
    outline: none;
    width: 100%
  }

  input:focus,
  select:focus,
  textarea:focus {
    border-color: #185FA5
  }

  textarea {
    resize: vertical
  }

  .detail-row {
    display: flex;
    gap: 12px;
    padding: 7px 0;
    border-bottom: 1px solid #f0f0f0
  }

  .detail-row:last-child {
    border-bottom: none
  }

  .detail-label {
    font-size: 12px;
    color: #888;
    width: 140px;
    flex-shrink: 0
  }

  .detail-value {
    font-size: 13px;
    color: #1a1a1a
  }
</style>

<div class="page">
  <!-- Header -->
  <div class="card" style="margin-bottom:24px">
    <div class="card-header">
      <div>
        <div class="page-title">Walk-In Guests</div>
        <div class="page-subtitle">Manage walk-in guests who arrive without a prior reservation</div>
      </div>
      <button class="btn btn-primary" onclick="openWalkInModal()">
        <i class="fa-solid fa-plus"></i> New Walk-In
      </button>
    </div>
  </div>

  <!-- Stats -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total Walk-Ins</div>
      <div class="stat-value blue" id="statTotal">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Currently Checked In</div>
      <div class="stat-value green" id="statCheckedIn">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Checked Out Today</div>
      <div class="stat-value grey" id="statCheckedOut">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Today's Walk-Ins</div>
      <div class="stat-value orange" id="statToday">-</div>
    </div>
  </div>

  <!-- Table -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">Walk-In Records</div>
      <div class="toolbar">
        <div class="search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="searchInput" class="search-input" placeholder="Search guests..." oninput="filterTable()">
        </div>
        <select id="statusFilter" class="filter-select" onchange="filterTable()">
          <option value="">All Statuses</option>
          <option value="checked_in">Checked In</option>
          <option value="checked_out">Checked Out</option>
          <option value="cancelled">Cancelled</option>
        </select>
      </div>
    </div>
    <div class="table-wrap">
      <table id="walkInTable">
        <thead>
          <tr>
            <th>Code</th>
            <th>Guest</th>
            <th>Property</th>
            <th>Room</th>
            <th>Booking Type</th>
            <th>Check-In</th>
            <th>Check-Out</th>
            <th class="center">Status</th>
            <th class="center">Payment</th>
            <th>Amount</th>
            <th class="center">Actions</th>
          </tr>
        </thead>
        <tbody id="walkInTableBody">
          <tr>
            <td colspan="11" class="empty-state"><i class="fa-regular fa-clipboard"></i>Loading...</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="pagination-wrap">
      <div class="pagination-info">Showing <span id="showingCount">0</span> of <span id="totalCount">0</span> records</div>
      <div class="pagination-controls">
        <button class="btn" id="prevPage" onclick="changePage(-1)" disabled>&#8592; Prev</button>
        <span class="pagination-info" id="pageInfo">Page 1 of 1</span>
        <button class="btn" id="nextPage" onclick="changePage(1)" disabled>Next &#8594;</button>
      </div>
    </div>
  </div>
</div>

<!-- New Walk-In Modal -->
<div class="overlay" id="walkInModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">New Walk-In Guest</div>
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
          <input type="date" id="walkInCheckInDate" required onchange="onCheckInDateChange()">
        </div>
        <div class="form-group">
          <label>Check-in Time <span class="req">*</span></label>
          <input type="time" id="walkInCheckInTime" required onchange="updateHourlyCheckoutTime(); calculateWalkInAmount();">
        </div>
        <div class="form-group" id="walkInCheckOutDateGroup">
          <label id="walkInCheckOutDateLabel">Check-out Date <span class="req">*</span></label>
          <input type="date" id="walkInCheckOutDate">
        </div>
        <div class="form-group" id="walkInCheckOutTimeGroup">
          <label id="walkInCheckOutTimeLabel">Check-out Time <span class="req">*</span></label>
          <input type="time" id="walkInCheckOutTime">
        </div>
        <div class="form-group" id="walkInDurationGroup" style="display:none">
          <label>Duration (Hours) <span class="req">*</span></label>
          <input type="number" id="walkInDuration" min="0.5" step="0.5" onchange="calculateWalkInAmount(); updateHourlyCheckoutTime();">
        </div>
        <div class="form-group" id="walkInCalculatedCheckoutGroup" style="display:none">
          <label>Calculated Check-out</label>
          <input type="text" id="walkInCalculatedCheckout" readonly style="background:#f5f5f5">
        </div>
        <div class="form-group">
          <label>Adults <span class="req">*</span></label>
          <input type="number" id="walkInAdults" min="1" value="1" required onchange="calculateWalkInAmount()">
        </div>
        <div class="form-group">
          <label>Children</label>
          <input type="number" id="walkInChildren" min="0" value="0" onchange="calculateWalkInAmount()">
        </div>
        <div class="form-group">
          <label>Seniors/PWD</label>
          <input type="number" id="walkInSeniors" min="0" value="0" onchange="calculateWalkInAmount()">
        </div>
        <div class="form-group">
          <label>Infants</label>
          <input type="number" id="walkInInfants" min="0" value="0">
        </div>
        <div class="form-group full" id="walkInPriceBreakdown" style="display:none">
          <div style="background:#f8fafc;border:1px solid #e0e0e0;border-radius:6px;padding:12px;font-size:13px;">
            <div style="font-weight:600;color:#444;margin-bottom:8px;">Price Breakdown</div>
            
            <!-- Seasonal Rate Row (hidden by default) -->
            <div id="walkInSeasonalRateRow" style="display:none;margin-bottom:8px;">
              <div class="alert alert-info py-2 mb-1" style="font-size: 13px;">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <i class="fa fa-calendar-alt"></i>
                    <strong id="walkInSeasonalRateName">Seasonal Rate</strong>
                    <span class="badge bg-light text-dark ms-2" id="walkInSeasonalRateBadge">0%</span>
                  </div>
                  <div class="text-end">
                    <span id="walkInSeasonalRateAdjustment" class="text-success fw-bold">-₱0.00</span>
                    <span class="text-muted ms-2">→</span>
                    <span id="walkInSeasonalRateDisplay" class="fw-bold">₱0.00</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div id="walkInBreakdownLines" style="display:flex;flex-direction:column;gap:4px;"></div>
            <div style="border-top:1px solid #e0e0e0;margin-top:8px;padding-top:8px;display:flex;justify-content:space-between;">
              <span style="font-weight:600;">Total</span>
              <span id="walkInBreakdownTotal" style="font-weight:700;color:#185FA5;">₱0.00</span>
            </div>
          </div>
        </div>
        
        <!-- Promo Code Section -->
        <div class="form-group full" style="background:#f9fafb;border:0.5px solid #e5e7eb;border-radius:8px;padding:12px">
          <div style="display:flex;gap:12px;align-items:flex-end">
            <div style="flex:1">
              <label style="font-size:11px;text-transform:uppercase;letter-spacing:0.05em;color:#666">Promo Code</label>
              <input type="text" id="walkInPromoCode" placeholder="Enter promo code (optional)" style="width:100%" onchange="validateWalkInPromo()">
            </div>
            <button type="button" class="btn" onclick="validateWalkInPromo()" style="padding:8px 16px">
              <i class="fas fa-check"></i> Validate
            </button>
          </div>
          <div id="promoValidationResult" style="margin-top:8px;font-size:13px;display:none"></div>
          <input type="hidden" id="walkInPromoId">
          <input type="hidden" id="walkInPromoDiscount" value="0">
        </div>
        
        <div class="form-group full">
          <label>Subtotal (Original Amount)</label>
          <input type="number" id="walkInSubtotalAmount" step="0.01" min="0" readonly style="background:#f5f5f5;color:#666">
        </div>
        <div class="form-group full" id="discountRow" style="display:none">
          <label>Promo Discount</label>
          <input type="number" id="walkInDiscountDisplay" step="0.01" min="0" readonly style="background:#f5f5f5;color:#2E7D32">
        </div>
        <div class="form-group full">
          <label>Total Amount <span class="req">*</span></label>
          <input type="number" id="walkInTotalAmount" step="0.01" min="0" required readonly style="background:#f5f5f5;font-weight:600">
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

<!-- View Modal -->
<div class="overlay" id="viewModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Walk-In Guest Details</div>
      <button class="modal-close" onclick="closeModal('viewModal')">&times;</button>
    </div>
    <div class="modal-body" id="viewModalBody"></div>
    <div class="modal-footer" id="viewModalFooter">
      <button class="btn" onclick="closeModal('viewModal')">Close</button>
    </div>
  </div>
</div>

<!-- Check Out Modal -->
<div class="overlay" id="checkOutModal">
  <div class="modal" style="max-width:500px">
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

<!-- Alert Modal -->
<div class="overlay" id="alertModal">
  <div class="modal" style="max-width:440px">
    <div class="modal-header">
      <div class="modal-title" id="alertTitle">Confirm</div>
      <button class="modal-close" onclick="closeAlert()">&times;</button>
    </div>
    <div class="modal-body">
      <p id="alertMessage" style="font-size:14px;line-height:1.6;color:#444"></p>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeAlert()">Cancel</button>
      <button class="btn btn-primary" id="alertConfirmBtn">Confirm</button>
    </div>
  </div>
</div>

<script>
  let walkInData = [];
  let currentPage = 1;
  let itemsPerPage = 10;
  let totalItems = 0;
  let availableRooms = [];
  let entranceFees = {};
  let currentViewId = null;

  document.addEventListener('DOMContentLoaded', function() {
    fetchWalkInData();
    loadProperties();
  });

  async function fetchWalkInData(page = 1, search = '', statusFilter = '') {
    try {
      let url = `../api/reservations.php?action=list&page=${page}&limit=${itemsPerPage}&source_channel=walk_in`;
      if (search) url += `&search=${encodeURIComponent(search)}`;
      if (statusFilter) url += `&status=${encodeURIComponent(statusFilter)}`;
      const response = await fetch(url);
      const result = await response.json();
      if (result.success) {
        walkInData = result.data;
        totalItems = result.pagination?.total || 0;
        currentPage = result.pagination?.page || 1;
        renderTable();
        updateStats();
        updatePagination();
      } else {
        showTableError(result.message || 'Failed to load data');
      }
    } catch (e) {
      showTableError('Failed to load data');
    }
  }

  function updateStats() {
    document.getElementById('statTotal').textContent = totalItems;
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('statCheckedIn').textContent = walkInData.filter(r => r.reservation_status === 'checked_in').length;
    document.getElementById('statCheckedOut').textContent = walkInData.filter(r => r.reservation_status === 'checked_out').length;
    document.getElementById('statToday').textContent = walkInData.filter(r => (r.check_in_date || '').startsWith(today)).length;
  }

  function updatePagination() {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    document.getElementById('showingCount').textContent = walkInData.length;
    document.getElementById('totalCount').textContent = totalItems;
    document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${Math.max(1, totalPages)}`;
    document.getElementById('prevPage').disabled = currentPage <= 1;
    document.getElementById('nextPage').disabled = currentPage >= totalPages;
  }

  function changePage(delta) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const newPage = currentPage + delta;
    if (newPage >= 1 && newPage <= totalPages) fetchWalkInData(newPage, document.getElementById('searchInput').value, document.getElementById('statusFilter').value);
  }

  let filterTimer = null;

  function filterTable() {
    clearTimeout(filterTimer);
    filterTimer = setTimeout(() => {
      fetchWalkInData(1, document.getElementById('searchInput').value, document.getElementById('statusFilter').value);
    }, 300);
  }

  function formatDate(d) {
    if (!d) return '-';
    return new Date(d).toLocaleDateString('en-US', {
      month: 'short',
      day: '2-digit',
      year: 'numeric'
    });
  }

  function formatTime(t) {
    if (!t) return '-';
    const [h, m] = t.split(':');
    const hour = parseInt(h);
    return `${hour % 12 || 12}:${m} ${hour >= 12 ? 'PM' : 'AM'}`;
  }

  function renderTable() {
    const tbody = document.getElementById('walkInTableBody');
    if (!tbody) return;
    if (walkInData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="11" class="empty-state"><i class="fa-regular fa-clipboard"></i>No walk-in records found</td></tr>`;
      return;
    }
    tbody.innerHTML = walkInData.map(r => `
      <tr class="cursor-pointer" onclick="viewRecord('${r.reservation_id}')">
        <td><strong>${r.reservation_code || '-'}</strong></td>
        <td>
          <div>${r.guest_name || '-'}</div>
          <div style="font-size:12px;color:#666">${r.guest_phone || r.guest_email || '-'}</div>
        </td>
        <td>${r.property_name || '-'}</td>
        <td>${r.room_number || r.room_name || '<span style="color:#999">No room</span>'}</td>
        <td><span class="status-badge" style="background:#E6F1FB;color:#0C447C">${r.booking_type || '-'}</span></td>
        <td>
          <div>${formatDate(r.check_in_date)}</div>
          <div style="font-size:12px;color:#666">${formatTime(r.check_in_time)}</div>
        </td>
        <td>
          <div>${formatDate(r.check_out_date)}</div>
          <div style="font-size:12px;color:#666">${r.check_out_date ? formatTime(r.check_out_time) : ''}</div>
        </td>
        <td class="center"><span class="status-badge ${r.reservation_status}">${r.reservation_status ? r.reservation_status.replace(/_/g, ' ') : '-'}</span></td>
        <td class="center"><span class="status-badge ${r.payment_status}">${r.payment_status ? r.payment_status.replace(/_/g, ' ') : '-'}</span></td>
        <td><strong>₱${parseFloat(r.total_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</strong></td>
        <td class="center">
          <div class="actions-cell">
            <button onclick="event.stopPropagation(); viewRecord('${r.reservation_id}')" class="action-btn"><i class="fa-solid fa-eye"></i> View</button>
            ${r.reservation_status === 'checked_in' ? `<button onclick="event.stopPropagation(); openCheckOutModal('${r.reservation_id}')" class="action-btn" style="background:#E3F2FD;color:#1565C0;border-color:#90CAF9"><i class="fa-solid fa-right-from-bracket"></i> Check Out</button>` : ''}
          </div>
        </td>
      </tr>
    `).join('');
  }

  function showTableError(msg) {
    document.getElementById('walkInTableBody').innerHTML = `<tr><td colspan="11" class="empty-state"><i>⚠</i>${msg}</td></tr>`;
  }

  async function viewRecord(reservationId) {
    try {
      const response = await fetch(`../api/reservations.php?action=single&id=${reservationId}`);
      const result = await response.json();
      if (result.success && result.data) {
        currentViewId = reservationId;
        renderViewModal(result.data);
        document.getElementById('viewModal').classList.add('open');
      } else {
        showAlert('Error', 'Failed to load record', () => {});
      }
    } catch (e) {
      showAlert('Error', 'Failed to load record', () => {});
    }
  }

  function renderViewModal(data) {
    document.getElementById('viewModalBody').innerHTML = `
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px">
        <div>
          <h3 style="margin:0 0 12px;font-size:14px;font-weight:600;color:#185FA5;text-transform:uppercase;letter-spacing:0.05em">Guest Information</h3>
          <div class="detail-row"><div class="detail-label">Name</div><div class="detail-value">${data.guest_name || '-'}</div></div>
          <div class="detail-row"><div class="detail-label">Email</div><div class="detail-value">${data.guest_email || '-'}</div></div>
          <div class="detail-row"><div class="detail-label">Phone</div><div class="detail-value">${data.guest_phone || '-'}</div></div>
          <div class="detail-row"><div class="detail-label">Guests</div><div class="detail-value">
            ${data.num_adults || 0} Adult${(data.num_adults || 0) > 1 ? 's' : ''}
            ${(data.num_children || 0) > 0 ? ', ' + data.num_children + ' Child' + ((data.num_children || 0) > 1 ? 'ren' : '') : ''}
            ${(data.num_seniors || 0) > 0 ? ', ' + data.num_seniors + ' Senior' + ((data.num_seniors || 0) > 1 ? 's' : '') : ''}
            ${(data.num_infants || 0) > 0 ? ', ' + data.num_infants + ' Infant' + ((data.num_infants || 0) > 1 ? 's' : '') : ''}
          </div></div>
        </div>
        <div>
          <h3 style="margin:0 0 12px;font-size:14px;font-weight:600;color:#185FA5;text-transform:uppercase;letter-spacing:0.05em">Booking Details</h3>
          <div class="detail-row"><div class="detail-label">Code</div><div class="detail-value"><strong>${data.reservation_code || '-'}</strong></div></div>
          <div class="detail-row"><div class="detail-label">Property</div><div class="detail-value">${data.property_name || '-'}</div></div>
          <div class="detail-row"><div class="detail-label">Booking Type</div><div class="detail-value">${data.booking_type || '-'}</div></div>
          <div class="detail-row"><div class="detail-label">Status</div><div class="detail-value"><span class="status-badge ${data.reservation_status}">${data.reservation_status ? data.reservation_status.replace(/_/g, ' ') : '-'}</span></div></div>
          <div class="detail-row"><div class="detail-label">Check-in</div><div class="detail-value">${formatDate(data.check_in_date)} ${formatTime(data.check_in_time)}</div></div>
          <div class="detail-row"><div class="detail-label">Check-out</div><div class="detail-value">${formatDate(data.check_out_date)} ${data.check_out_date ? formatTime(data.check_out_time) : ''}</div></div>
        </div>
      </div>
      <div>
        <h3 style="margin:0 0 12px;font-size:14px;font-weight:600;color:#185FA5;text-transform:uppercase;letter-spacing:0.05em">Payment</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div>
            <div class="detail-row"><div class="detail-label">Payment Status</div><div class="detail-value"><span class="status-badge ${data.payment_status}">${data.payment_status ? data.payment_status.replace(/_/g,'  ') : '-'}</span></div></div>
            ${data.base_amount > 0 ? `<div class="detail-row"><div class="detail-label"><i class="fas fa-calculator" style="margin-right:6px;color:#666"></i>Base Amount</div><div class="detail-value"><span style="background:#f8f9fa;padding:4px 8px;border-radius:4px;font-family:monospace">₱${parseFloat(data.base_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</span></div></div>` : ''}
            ${data.seasonal_rate_discount && parseFloat(data.seasonal_rate_discount) !== 0 ? `<div class="detail-row"><div class="detail-label"><i class="fas fa-snowflake" style="margin-right:6px;color:#2196F3"></i>Seasonal Rate</div><div class="detail-value"><span style="background:${parseFloat(data.seasonal_rate_discount) > 0 ? '#ffebee' : '#e8f5e8'};color:${parseFloat(data.seasonal_rate_discount) > 0 ? '#c62828' : '#2e7d32'};padding:4px 8px;border-radius:4px;font-weight:500;font-family:monospace">${parseFloat(data.seasonal_rate_discount) > 0 ? '+' : ''}₱${parseFloat(data.seasonal_rate_discount).toLocaleString('en-US', {minimumFractionDigits: 2})}</span></div></div>` : ''}
            ${data.subtotal_amount > 0 ? `<div class="detail-row"><div class="detail-label"><i class="fas fa-minus" style="margin-right:6px;color:#666"></i>Subtotal</div><div class="detail-value"><span style="background:#f3e5f5;padding:4px 8px;border-radius:4px;color:#6a1b9a;font-family:monospace">₱${parseFloat(data.subtotal_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</span></div></div>` : ''}
            ${data.discount_amount > 0 ? `<div class="detail-row"><div class="detail-label"><i class="fas fa-tag" style="margin-right:6px;color:#4CAF50"></i>Promo Discount</div><div class="detail-value"><span style="background:#e8f5e8;color:#2e7d32;padding:4px 8px;border-radius:4px;font-weight:500;font-family:monospace">-₱${parseFloat(data.discount_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</span></div></div>` : ''}
            <div class="detail-row"><div class="detail-label"><i class="fas fa-receipt" style="margin-right:6px;color:#FF5722"></i>Total Amount</div><div class="detail-value"><strong style="background:#fff3e0;color:#e65100;padding:6px 12px;border-radius:6px;font-size:14px;font-family:monospace">₱${parseFloat(data.total_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</strong></div></div>
            <div class="detail-row"><div class="detail-label">Amount Paid</div><div class="detail-value">₱${parseFloat(data.paid_amount || data.amount_paid || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</div></div>
          </div>
          <div>
            <div class="detail-row"><div class="detail-label">Payment Method</div><div class="detail-value">${data.payment_method_label || '-'}</div></div>
            ${data.transaction_reference ? `<div class="detail-row"><div class="detail-label">Reference</div><div class="detail-value">${data.transaction_reference}</div></div>` : ''}
            ${data.balance_amount > 0 ? `<div class="detail-row"><div class="detail-label">Balance Due</div><div class="detail-value" style="color:#C62828;font-weight:600">₱${parseFloat(data.balance_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</div></div>` : ''}
          </div>
        </div>
      </div>
      ${data.notes ? `<div style="margin-top:20px"><h3 style="margin:0 0 8px;font-size:14px;font-weight:600;color:#185FA5;text-transform:uppercase;letter-spacing:0.05em">Notes</h3><p style="font-size:13px;color:#444">${data.notes}</p></div>` : ''}
      
      <!-- Pricing Breakdown -->
      ${data.pricing_breakdown?.length ? `
      <div style="margin-top:24px">
        <h3 style="margin:0 0 12px;font-size:14px;font-weight:600;color:#185FA5;text-transform:uppercase;letter-spacing:0.05em">
          <i class="fas fa-chart-line" style="margin-right:6px;color:#2196F3"></i>Pricing Breakdown
        </h3>
        <div style="background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%);border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 4px 6px rgba(0,0,0,0.05)">
          <table style="width:100%;border-collapse:collapse;font-size:13px">
            <thead>
              <tr style="background:linear-gradient(135deg,#e2e8f0 0%,#cbd5e1 100%)">
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:0.05em">Component</th>
                <th style="padding:12px 16px;text-align:right;font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:0.05em">Base</th>
                <th style="padding:12px 16px;text-align:right;font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:0.05em">Adjustment</th>
                <th style="padding:12px 16px;text-align:right;font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:0.05em">Final</th>
              </tr>
            </thead>
            <tbody>
              ${data.pricing_breakdown.map(item => {
                const componentConfig = {
                  'base_rate': { bg: '#dcfce7', color: '#166534', icon: 'fa-bed' },
                  'seasonal_rate': { bg: '#dbeafe', color: '#1d4ed8', icon: 'fa-snowflake' },
                  'pricing_rule': { bg: '#fef3c7', color: '#92400e', icon: 'fa-gavel' },
                  'promotion': { bg: '#fce7f3', color: '#9f1239', icon: 'fa-tag' },
                  'extra_guest_fee': { bg: '#fed7aa', color: '#9a3412', icon: 'fa-users' },
                  'tax': { bg: '#fecaca', color: '#991b1b', icon: 'fa-receipt' },
                  'deposit': { bg: '#e5e7eb', color: '#374151', icon: 'fa-lock' }
                };
                const config = componentConfig[item.component_type] || { bg: '#f3f4f6', color: '#666', icon: 'fa-circle' };
                const typeLabel = item.component_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                const adj = item.discount_amount ? 
                  `<span style="color:#dc2626;font-weight:600">-₱${parseFloat(item.discount_amount).toLocaleString('en-US', {minimumFractionDigits:2})}</span>` : 
                  item.multiplier ? `<span style="color:#059669;font-weight:600">${item.multiplier}x</span>` : 
                  '<span style="color:#9ca3af">-</span>';
                
                return `
                <tr style="border-top:1px solid #e2e8f0;transition:background-color 0.2s" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                  <td style="padding:14px 16px">
                    <div style="display:flex;align-items:center;gap:10px">
                      <div style="width:32px;height:32px;background:${config.bg};color:${config.color};border-radius:8px;display:flex;align-items:center;justify-content:center">
                        <i class="fas ${config.icon}" style="font-size:12px"></i>
                      </div>
                      <div>
                        <div style="font-weight:600;color:#1e293b;font-size:13px">${item.component_name}</div>
                        <div style="display:flex;align-items:center;gap:6px;margin-top:3px">
                          <span style="background:${config.bg};color:${config.color};padding:2px 6px;border-radius:4px;font-size:9px;font-weight:600;letter-spacing:0.5px">${typeLabel}</span>
                          ${item.season_name ? `<span style="font-size:10px;color:#64748b"><i class="fas fa-snowflake" style="margin-right:2px"></i>${item.season_name}</span>` : ''}
                          ${item.rule_name ? `<span style="font-size:10px;color:#64748b"><i class="fas fa-gavel" style="margin-right:2px"></i>${item.rule_name}</span>` : ''}
                          ${item.promo_title ? `<span style="font-size:10px;color:#64748b"><i class="fas fa-tag" style="margin-right:2px"></i>${item.promo_title}</span>` : ''}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td style="padding:14px 16px;text-align:right;font-family:monospace;font-weight:500;color:#475569">₱${parseFloat(item.base_amount).toLocaleString('en-US', {minimumFractionDigits:2})}</td>
                  <td style="padding:14px 16px;text-align:right;font-family:monospace;font-weight:600">${adj}</td>
                  <td style="padding:14px 16px;text-align:right;font-family:monospace;font-weight:600;color:#0f172a;background:#f8fafc;border-radius:6px">₱${parseFloat(item.final_amount).toLocaleString('en-US', {minimumFractionDigits:2})}</td>
                </tr>`;
              }).join('')}
              <tr style="background:#E6F1FB;font-weight:600">
                <td style="padding:12px;text-align:right" colspan="3">TOTAL</td>
                <td style="padding:12px;text-align:right;font-family:monospace;font-size:14px">₱${parseFloat(data.total_amount || 0).toLocaleString('en-US', {minimumFractionDigits:2})}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>` : ''}
      
      <!-- Applied Promotions -->
      ${data.applied_promotions?.length ? `
      <div style="margin-top:24px">
        <h3 style="margin:0 0 12px;font-size:14px;font-weight:600;color:#185FA5;text-transform:uppercase;letter-spacing:0.05em">
          <i class="fas fa-ticket" style="margin-right:6px;color:#FF9800"></i>Applied Promotions
        </h3>
        <div style="display:flex;flex-direction:column;gap:10px">
          ${data.applied_promotions.map(promo => `
            <div style="display:flex;justify-content:space-between;align-items:center;padding:16px;background:linear-gradient(135deg,#fff8e1 0%,#fff3c4 100%);border:1px solid #ffcc02;border-radius:10px;box-shadow:0 2px 4px rgba(255,152,0,0.1)">
              <div style="display:flex;align-items:center;gap:12px">
                <div style="width:40px;height:40px;background:#FF9800;border-radius:8px;display:flex;align-items:center;justify-content:center">
                  <i class="fas fa-tag" style="color:white;font-size:16px"></i>
                </div>
                <div>
                  <div style="font-weight:600;color:#e65100;font-size:14px">${promo.promo_title}</div>
                  <div style="font-size:12px;color:#666;margin-top:2px">
                    <i class="fas fa-barcode" style="margin-right:4px;font-size:10px"></i>
                    ${promo.promo_code_used || 'No code'}
                  </div>
                </div>
              </div>
              <div style="text-align:right">
                <div style="background:#e8f5e8;color:#2e7d32;padding:6px 12px;border-radius:6px;font-weight:600;font-family:monospace;font-size:13px">
                  -₱${parseFloat(promo.discount_amount).toLocaleString('en-US', {minimumFractionDigits:2})}
                </div>
                <div style="font-size:11px;color:#666;margin-top:4px;font-style:italic">
                  ${promo.discount_type === 'percentage' ? promo.discount_value + '% off' : 'Fixed discount'}
                </div>
              </div>
            </div>
          `).join('')}
        </div>
      </div>` : ''}
    `;

    const footer = document.getElementById('viewModalFooter');
    const baseUrl = '<?php echo $base_url ?? "/build/"; ?>';
    const managePromoBtn = `<button class="btn" onclick="window.open('${baseUrl}reservations/reservation-promotions?reservation_id=${data.reservation_id}&check_in_date=${data.check_in_date}&base_amount=${data.total_amount}', '_blank')" style="background:#E6F1FB;border-color:#185FA5;color:#185FA5"><i class="fas fa-ticket"></i> Promotions</button>`;
    const pricingBtn = `<button class="btn" onclick="window.open('${baseUrl}reservations/pricing-breakdown?reservation_id=${data.reservation_id}', '_blank')" style="background:#EAF3DE;border-color:#3B6D11;color:#3B6D11"><i class="fas fa-receipt"></i> Pricing</button>`;
    
    footer.innerHTML = data.reservation_status === 'checked_in' ?
      `${managePromoBtn}${pricingBtn}<button class="btn btn-primary" onclick="closeModal('viewModal'); openCheckOutModal('${data.reservation_id}')" style="background:#1565C0;border-color:#1565C0"><i class="fa-solid fa-right-from-bracket"></i> Check Out</button>
         <button class="btn" onclick="closeModal('viewModal')">Close</button>` :
      `${managePromoBtn}${pricingBtn}<button class="btn" onclick="closeModal('viewModal')">Close</button>`;
  }

  async function openCheckOutModal(reservationId) {
    try {
      const response = await fetch(`../api/reservations.php?action=single&id=${reservationId}`);
      const result = await response.json();
      if (result.success && result.data) {
        document.getElementById('checkOutReservationId').value = reservationId;
        document.getElementById('checkOutReservationCode').value = result.data.reservation_code || '';
        const now = new Date();
        const offset = now.getTimezoneOffset() * -1;
        document.getElementById('checkOutEventAt').value = new Date(now.getTime() - offset * 60000).toISOString().slice(0, 16);
        document.getElementById('checkOutModal').classList.add('open');
      } else {
        showAlert('Error', 'Failed to load record', () => {});
      }
    } catch (e) {
      showAlert('Error', 'Failed to load record', () => {});
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
    showAlert('Confirm Check Out', 'Are you sure you want to process check-out for this guest?', () => confirmCheckOut(reservationId, eventType, eventAt, notes));
  }

  async function confirmCheckOut(reservationId, eventType, eventAt, notes) {
    try {
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
          notes
        })
      });
      const logResult = await logResponse.json();
      if (logResult.success) {
        const statusResponse = await fetch('../api/reservations.php?action=update_status', {
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
          fetchWalkInData(currentPage);
          showAlert('Success', 'Guest checked out successfully', () => {});
        } else {
          showAlert('Error', 'Failed to update status: ' + (statusResult.message || 'Unknown error'), () => {});
        }
      } else {
        showAlert('Error', 'Failed to create check-out log: ' + (logResult.message || 'Unknown error'), () => {});
      }
    } catch (e) {
      showAlert('Error', 'Failed to process check-out', () => {});
    }
  }

  async function loadProperties() {
    try {
      const response = await fetch('../api/properties.php?action=list&per_page=100');
      const result = await response.json();
      const select = document.getElementById('walkInProperty');
      if (result.success && result.data && result.data.data) {
        result.data.data.forEach(p => {
          select.innerHTML += `<option value="${p.property_id}">${p.property_name}</option>`;
        });
        if (result.data.data.length === 1) {
          select.value = result.data.data[0].property_id;
          loadAvailableRooms();
        }
      }
    } catch (e) {
      console.error('Error loading properties:', e);
    }
  }

  async function loadEntranceFees() {
    try {
      const response = await fetch('../api/system_settings.php?action=entrance_fees');
      const result = await response.json();
      if (result.success && result.data) entranceFees = result.data;
    } catch (e) {
      console.error('Error loading entrance fees:', e);
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
        const rooms = result.data.data.filter(r => r.property_id === propertyId);
        availableRooms = rooms;
        rooms.forEach(room => {
          roomSelect.innerHTML += `<option value="${room.room_id}"
            data-category-id="${room.category_id || ''}"
            data-overnight="${room.override_price_per_night || 0}"
            data-hourly="${room.override_price_per_hour || 0}"
            data-dayuse="${room.override_price_day_use || 0}"
            data-extra-adult="${room.extra_adult_fee || 0}"
            data-extra-child="${room.extra_child_fee || 0}"
            data-extra-senior="${room.extra_senior_fee || 0}"
            data-max-adult="${room.max_adult_capacity || 999}"
            >${room.room_number}${room.room_name ? ' - ' + room.room_name : ''}</option>`;
        });
      }
    } catch (e) {
      console.error('Error loading rooms:', e);
    }
    calculateWalkInAmount();
  }

  async function calculateWalkInAmount() {
    const roomSelect    = document.getElementById('walkInRoom');
    const bookingType   = document.getElementById('walkInBookingType').value;
    const duration      = parseFloat(document.getElementById('walkInDuration').value) || 0;
    const adults        = parseInt(document.getElementById('walkInAdults').value) || 1;
    const children      = parseInt(document.getElementById('walkInChildren').value) || 0;
    const seniors       = parseInt(document.getElementById('walkInSeniors').value) || 0;
    const totalAmountInput  = document.getElementById('walkInTotalAmount');
    const breakdownSection  = document.getElementById('walkInPriceBreakdown');
    const breakdownLines    = document.getElementById('walkInBreakdownLines');
    const breakdownTotal    = document.getElementById('walkInBreakdownTotal');
    const selectedOption    = roomSelect.options[roomSelect.selectedIndex];
    const hasRoom           = selectedOption && selectedOption.value;

    const lines = [];
    let total = 0;

    if (hasRoom) {
      // ── Room selected: base rate + extra-person fees ──────────────────────
      let base = 0;
      if (bookingType === 'overnight')      base = parseFloat(selectedOption.dataset.overnight) || 0;
      else if (bookingType === 'hourly')    base = (parseFloat(selectedOption.dataset.hourly) || 0) * duration;
      else if (bookingType === 'day_use')   base = parseFloat(selectedOption.dataset.dayuse) || 0;

      const extraAdultFee  = parseFloat(selectedOption.dataset.extraAdult)  || 0;
      const extraChildFee  = parseFloat(selectedOption.dataset.extraChild)  || 0;
      const extraSeniorFee = parseFloat(selectedOption.dataset.extraSenior) || 0;
      const maxAdults      = parseInt(selectedOption.dataset.maxAdult)      || 999;

      const baseLabel = bookingType === 'hourly'
        ? `Base rate (${duration}h)`
        : `Base rate (${bookingType.replace('_', ' ')})`;
      lines.push({ label: baseLabel, amount: base });
      total += base;

      if (extraAdultFee > 0 && adults > maxAdults) {
        const extraCount = adults - maxAdults;
        const amt = extraAdultFee * extraCount;
        lines.push({ label: `Extra adults (${extraCount} × ₱${extraAdultFee.toFixed(2)})`, amount: amt });
        total += amt;
      }
      if (extraChildFee > 0 && children > 0) {
        const amt = extraChildFee * children;
        lines.push({ label: `Children (${children} × ₱${extraChildFee.toFixed(2)})`, amount: amt });
        total += amt;
      }
      if (extraSeniorFee > 0 && seniors > 0) {
        const amt = extraSeniorFee * seniors;
        lines.push({ label: `Seniors/PWD (${seniors} × ₱${extraSeniorFee.toFixed(2)})`, amount: amt });
        total += amt;
      }

    } else {
      // ── No room: per-person entrance fee with senior discount ─────────────
      const adultRate        = entranceFees.adult_rate || 0;
      const childRate        = entranceFees.child_rate || 0;
      const seniorDiscountPct = entranceFees.senior_discount_pct ?? 20;

      if (adultRate > 0) {
        // Per-person pricing configured
        const adultAmt = adultRate * adults;
        lines.push({ label: `Adults (${adults} × ₱${adultRate.toFixed(2)})`, amount: adultAmt });
        total += adultAmt;

        if (childRate > 0 && children > 0) {
          const childAmt = childRate * children;
          lines.push({ label: `Children (${children} × ₱${childRate.toFixed(2)})`, amount: childAmt });
          total += childAmt;
        }

        if (seniors > 0) {
          const seniorSubtotal = adultRate * seniors;
          const seniorDiscount = seniorSubtotal * (seniorDiscountPct / 100);
          lines.push({ label: `Seniors/PWD (${seniors} × ₱${adultRate.toFixed(2)})`, amount: seniorSubtotal });
          lines.push({ label: `Senior/PWD discount (${seniorDiscountPct}%)`, amount: -seniorDiscount, discount: true });
          total += seniorSubtotal - seniorDiscount;
        }

      } else {
        // Fallback: flat entrance fee (per-person rates not configured)
        let flat = 0;
        if (bookingType === 'overnight')     flat = entranceFees.overnight || 0;
        else if (bookingType === 'hourly')   flat = entranceFees.hourly || 0;
        else if (bookingType === 'day_use')  flat = entranceFees.day_use || 0;
        const flatLabel = bookingType === 'hourly'
          ? `Entrance fee (${duration}h)`
          : `Entrance fee (${bookingType.replace('_', ' ')})`;
        if (flat > 0) lines.push({ label: flatLabel, amount: flat });
        total += flat;
      }
    }

    // Store base amount (before any discounts)
    const baseAmount = total;
    
    // Apply dynamic pricing (seasonal rates & pricing rules)
    let seasonalRateDiscount = 0;
    const propertySelect = document.getElementById('walkInProperty');
    const propertyId = propertySelect.value;
    const checkInDate = document.getElementById('walkInCheckInDate').value;
    
    if (propertyId && checkInDate && total > 0) {
      try {
        // Get room category ID if room is selected
        const roomSelect = document.getElementById('walkInRoom');
        const selectedOption = roomSelect.options[roomSelect.selectedIndex];
        const categoryId = (selectedOption && selectedOption.value && selectedOption.dataset.categoryId) ? selectedOption.dataset.categoryId : null;
        
        const priceResponse = await fetch(`../../pages/api/calculate-price.php?property_id=${propertyId}&category_id=${categoryId}&base_amount=${total}&check_in_date=${checkInDate}&booking_type=${bookingType}`);
        const priceResult = await priceResponse.json();

        if (priceResult.success) {
          // Calculate seasonal rate discount
          const seasonalRateData = priceResult.breakdown?.find(b => b.type === 'seasonal_rate');
          if (seasonalRateData) {
            seasonalRateDiscount = Math.abs(seasonalRateData.adjustment || 0);
          }
          
          total = priceResult.final_amount;
          
          // Display seasonal rate if present
          const seasonalRate = priceResult.breakdown?.find(b => b.type === 'seasonal_rate');
          if (seasonalRate) {
            const multiplier = seasonalRate.multiplier;
            let percentageText = '';
            let adjustmentClass = '';
            const isDiscount = multiplier < 1;
            
            if (isDiscount) {
              const discountPercent = ((1 - multiplier) * 100).toFixed(0);
              percentageText = `-${discountPercent}%`;
              adjustmentClass = 'text-success';
            } else if (multiplier > 1) {
              const increasePercent = ((multiplier - 1) * 100).toFixed(0);
              percentageText = `+${increasePercent}%`;
              adjustmentClass = 'text-danger';
            }
            
            // Update seasonal rate display row
            document.getElementById('walkInSeasonalRateName').textContent = seasonalRate.name;
            document.getElementById('walkInSeasonalRateBadge').textContent = percentageText;
            document.getElementById('walkInSeasonalRateAdjustment').textContent = (isDiscount ? '-' : '+') + '₱' + seasonalRateDiscount.toLocaleString('en-PH', {
              minimumFractionDigits: 2,
              maximumFractionDigits: 2
            });
            document.getElementById('walkInSeasonalRateAdjustment').className = adjustmentClass + ' fw-bold';
            document.getElementById('walkInSeasonalRateDisplay').textContent = '₱' + seasonalRate.final_amount.toLocaleString('en-PH', {
              minimumFractionDigits: 2,
              maximumFractionDigits: 2
            });
            document.getElementById('walkInSeasonalRateRow').style.display = 'block';
            
            lines.push({ 
              label: `${seasonalRate.name} (${percentageText})`, 
              amount: seasonalRateDiscount, 
              discount: isDiscount,
              seasonal: true 
            });
          } else {
            // Hide seasonal rate row if no seasonal rate
            document.getElementById('walkInSeasonalRateRow').style.display = 'none';
          }
        }
      } catch (error) {
        console.error('Dynamic pricing error:', error);
        // Continue with base amount if API fails
      }
    }
    
    // Calculate subtotal (before promo discount)
    const subtotal = total;
    
    // Apply promo discount if validated
    const promoDiscount = parseFloat(document.getElementById('walkInPromoDiscount').value) || 0;
    if (promoDiscount > 0) {
      lines.push({ label: 'Promo Discount', amount: -promoDiscount, discount: true });
      total -= promoDiscount;
    }

    if (lines.length > 1 || (lines.length === 1 && lines.some(l => l.discount))) {
      breakdownSection.style.display = 'block';
      breakdownLines.innerHTML = lines.map(l =>
        `<div style="display:flex;justify-content:space-between;${l.discount ? 'color:#2E7D32' : 'color:#555'}">
          <span>${l.label}</span>
          <span>${l.discount ? '-' : ''}₱${Math.abs(l.amount).toFixed(2)}</span>
        </div>`
      ).join('');
      breakdownTotal.textContent = `₱${total.toFixed(2)}`;
    } else {
      breakdownSection.style.display = 'none';
    }

    // Update form fields
    document.getElementById('walkInSubtotalAmount').value = subtotal.toFixed(2);
    
    // Add hidden fields for base amount and seasonal rate discount if they don't exist
    if (!document.getElementById('walkInBaseAmount')) {
      const baseInput = document.createElement('input');
      baseInput.type = 'hidden';
      baseInput.id = 'walkInBaseAmount';
      baseInput.name = 'base_amount';
      document.getElementById('walkInForm').appendChild(baseInput);
    }
    if (!document.getElementById('walkInSeasonalRateDiscount')) {
      const seasonalInput = document.createElement('input');
      seasonalInput.type = 'hidden';
      seasonalInput.id = 'walkInSeasonalRateDiscount';
      seasonalInput.name = 'seasonal_rate_discount';
      document.getElementById('walkInForm').appendChild(seasonalInput);
    }
    
    // Set the values
    document.getElementById('walkInBaseAmount').value = baseAmount.toFixed(2);
    document.getElementById('walkInSeasonalRateDiscount').value = seasonalRateDiscount.toFixed(2);
    
    if (promoDiscount > 0) {
      document.getElementById('discountRow').style.display = 'block';
      document.getElementById('walkInDiscountDisplay').value = promoDiscount.toFixed(2);
    } else {
      document.getElementById('discountRow').style.display = 'none';
    }
    totalAmountInput.value = total.toFixed(2);
  }

  function toggleDateFields() {
    const bookingType = document.getElementById('walkInBookingType').value;
    const checkOutDateGroup = document.getElementById('walkInCheckOutDateGroup');
    const checkOutTimeGroup = document.getElementById('walkInCheckOutTimeGroup');
    const durationGroup = document.getElementById('walkInDurationGroup');
    const calculatedGroup = document.getElementById('walkInCalculatedCheckoutGroup');
    const checkOutDateLabel = document.getElementById('walkInCheckOutDateLabel');
    const checkOutTimeLabel = document.getElementById('walkInCheckOutTimeLabel');
    const checkInDate = document.getElementById('walkInCheckInDate').value;

    if (bookingType === 'hourly') {
      checkOutDateGroup.style.display = 'none';
      checkOutTimeGroup.style.display = 'none';
      durationGroup.style.display = 'block';
      calculatedGroup.style.display = 'block';
      updateHourlyCheckoutTime();
    } else if (bookingType === 'day_use') {
      checkOutDateGroup.style.display = 'none';
      checkOutTimeGroup.style.display = 'block';
      durationGroup.style.display = 'none';
      calculatedGroup.style.display = 'none';
      checkOutTimeLabel.innerHTML = 'Check-out Time <span class="req">*</span>';
      // Lock check-out to same day as check-in
      if (checkInDate) document.getElementById('walkInCheckOutDate').value = checkInDate;
      // Default check-out time for day use: 5:00 PM
      if (!document.getElementById('walkInCheckOutTime').value) {
        document.getElementById('walkInCheckOutTime').value = '17:00';
      }
    } else {
      // overnight
      checkOutDateGroup.style.display = 'block';
      checkOutTimeGroup.style.display = 'block';
      durationGroup.style.display = 'none';
      calculatedGroup.style.display = 'none';
      checkOutDateLabel.innerHTML = 'Check-out Date <span class="req">*</span>';
      checkOutTimeLabel.innerHTML = 'Check-out Time <span class="req">*</span>';
    }

    calculateWalkInAmount();
  }

  function onCheckInDateChange() {
    const bookingType = document.getElementById('walkInBookingType').value;
    const checkInDate = document.getElementById('walkInCheckInDate').value;
    if (bookingType === 'day_use' && checkInDate) {
      document.getElementById('walkInCheckOutDate').value = checkInDate;
    }
    updateHourlyCheckoutTime();
  }

  function updateHourlyCheckoutTime() {
    const checkInDate = document.getElementById('walkInCheckInDate').value;
    const checkInTime = document.getElementById('walkInCheckInTime').value;
    const duration = parseFloat(document.getElementById('walkInDuration').value) || 0;
    const calculatedCheckoutInput = document.getElementById('walkInCalculatedCheckout');
    if (checkInDate && checkInTime && duration > 0) {
      const checkInDateTime = new Date(`${checkInDate}T${checkInTime}`);
      const checkoutDateTime = new Date(checkInDateTime.getTime() + duration * 3600000);
      calculatedCheckoutInput.value = checkoutDateTime.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
      }) + ' at ' + checkoutDateTime.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
      });
    } else {
      calculatedCheckoutInput.value = '';
    }
  }

  async function openWalkInModal() {
    await loadEntranceFees();
    const now = new Date();
    const pad = n => String(n).padStart(2, '0');
    document.getElementById('walkInCheckInDate').value = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}`;
    document.getElementById('walkInCheckInTime').value = `${pad(now.getHours())}:${pad(now.getMinutes())}`;
    const tomorrow = new Date(now);
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('walkInCheckOutDate').value = `${tomorrow.getFullYear()}-${pad(tomorrow.getMonth()+1)}-${pad(tomorrow.getDate())}`;
    document.getElementById('walkInCheckOutTime').value = '11:00';
    document.getElementById('walkInRoom').value = '';
    document.getElementById('walkInFirstName').value = '';
    document.getElementById('walkInLastName').value = '';
    document.getElementById('walkInEmail').value = '';
    document.getElementById('walkInPhone').value = '';
    document.getElementById('walkInAdults').value = 1;
    document.getElementById('walkInChildren').value = 0;
    document.getElementById('walkInSeniors').value = 0;
    document.getElementById('walkInInfants').value = 0;
    document.getElementById('walkInPaymentAmount').value = '';
    document.getElementById('walkInReference').value = '';
    document.getElementById('walkInNotes').value = '';
    // Reset promo code fields
    document.getElementById('walkInPromoCode').value = '';
    document.getElementById('walkInPromoId').value = '';
    document.getElementById('walkInPromoDiscount').value = '0';
    document.getElementById('promoValidationResult').style.display = 'none';
    
    // Reset seasonal rate display
    document.getElementById('walkInSeasonalRateRow').style.display = 'none';
    
    document.getElementById('walkInBookingType').value = 'overnight';
    document.getElementById('walkInCheckOutTime').value = '11:00';
    toggleDateFields();
    calculateWalkInAmount();
    document.getElementById('walkInModal').classList.add('open');
  }

  let validatedPromoData = null;

  async function validateWalkInPromo() {
    const code = document.getElementById('walkInPromoCode').value.trim();
    const checkInDate = document.getElementById('walkInCheckInDate').value;
    const resultDiv = document.getElementById('promoValidationResult');
    
    if (!code) {
      resultDiv.className = '';
      resultDiv.style.display = 'none';
      validatedPromoData = null;
      document.getElementById('walkInPromoId').value = '';
      document.getElementById('walkInPromoDiscount').value = '0';
      calculateWalkInAmount();
      return;
    }

    resultDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Validating...';
    resultDiv.style.display = 'block';
    
    try {
      const response = await fetch(`../api/reservation_promotions.php?action=validate&code=${encodeURIComponent(code)}&check_in_date=${checkInDate}`);
      const result = await response.json();
      
      if (result.success && result.promo) {
        validatedPromoData = result.promo;
        document.getElementById('walkInPromoId').value = result.promo.promo_id;
        
        // Calculate discount based on promo type
        const currentTotal = parseFloat(document.getElementById('walkInTotalAmount').value) || 0;
        let discount = 0;
        
        if (result.promo.promo_type === 'percentage') {
          discount = currentTotal * (result.promo.discount_value / 100);
        } else if (result.promo.promo_type === 'fixed') {
          discount = result.promo.discount_value || 0;
        } else {
          // Default 10% if no specific type
          discount = currentTotal * 0.10;
        }
        
        document.getElementById('walkInPromoDiscount').value = discount.toFixed(2);
        
        resultDiv.innerHTML = `<i class="fas fa-check-circle" style="color:#3B6D11"></i> <strong>${result.promo.promo_title}</strong> applied! Discount: ₱${discount.toFixed(2)}`;
        resultDiv.style.color = '#3B6D11';
        
        // Recalculate with discount
        calculateWalkInAmount();
      } else {
        validatedPromoData = null;
        document.getElementById('walkInPromoId').value = '';
        document.getElementById('walkInPromoDiscount').value = '0';
        resultDiv.innerHTML = `<i class="fas fa-times-circle" style="color:#C62828"></i> ${result.message || 'Invalid promo code'}`;
        resultDiv.style.color = '#C62828';
        calculateWalkInAmount();
      }
    } catch (e) {
      resultDiv.innerHTML = '<i class="fas fa-times-circle" style="color:#C62828"></i> Validation failed';
      resultDiv.style.color = '#C62828';
    }
  }

  async function processWalkIn() {
    const propertyId = document.getElementById('walkInProperty').value;
    const roomId = document.getElementById('walkInRoom').value;
    const firstName = document.getElementById('walkInFirstName').value.trim();
    const lastName = document.getElementById('walkInLastName').value.trim();
    const email = document.getElementById('walkInEmail').value.trim();
    const phone = document.getElementById('walkInPhone').value.trim();
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
    const subtotalAmount = document.getElementById('walkInSubtotalAmount').value;
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

    // Get promo data
    const promoId = document.getElementById('walkInPromoId').value;
    const promoCode = document.getElementById('walkInPromoCode').value.trim();
    const promoDiscount = parseFloat(document.getElementById('walkInPromoDiscount').value) || 0;

    // Get base amount and seasonal rate discount
    const baseAmount = parseFloat(document.getElementById('walkInBaseAmount')?.value || totalAmount);
    const seasonalRateDiscount = parseFloat(document.getElementById('walkInSeasonalRateDiscount')?.value || 0);

    showAlert('Confirm Walk-In', `Process walk-in check-in for ${firstName} ${lastName}?`, () => confirmWalkIn({
      propertyId,
      roomId,
      firstName,
      lastName,
      email,
      phone,
      bookingType,
      checkInDate,
      checkInTime,
      checkOutDate,
      checkOutTime,
      duration,
      adults,
      children,
      seniors,
      infants,
      subtotalAmount,
      baseAmount,
      seasonalRateDiscount,
      totalAmount,
      paymentAmount,
      paymentMethod,
      reference,
      notes,
      promoId,
      promoCode,
      promoDiscount
    }));
  }

  async function confirmWalkIn(data) {
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
        fetchWalkInData(currentPage);
        showAlert('Success', 'Walk-in guest checked in successfully', () => {});
      } else {
        showAlert('Error', 'Failed to process walk-in: ' + (result.message || 'Unknown error'), () => {});
      }
    } catch (e) {
      showAlert('Error', 'Failed to process walk-in', () => {});
    }
  }

  function closeModal(id) {
    document.getElementById(id).classList.remove('open');
  }

  function showAlert(title, message, onConfirm) {
    document.getElementById('alertTitle').textContent = title;
    document.getElementById('alertMessage').textContent = message;
    document.getElementById('alertModal').classList.add('open');
    document.getElementById('alertConfirmBtn').onclick = function() {
      closeAlert();
      onConfirm();
    };
  }

  function closeAlert() {
    document.getElementById('alertModal').classList.remove('open');
  }
</script>