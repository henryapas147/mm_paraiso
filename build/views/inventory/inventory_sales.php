<?php if (false) {
} ?>
<style>
  @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=DM+Mono:wght@400;500&display=swap');

  :root {
    --prod-blue: #1A6BBF;
    --prod-blue-dark: #0F4C8A;
    --prod-blue-pale: #EBF3FC;
    --prod-green: #2E7D32;
    --prod-green-pale: #E8F5E9;
    --prod-red: #C62828;
    --prod-red-pale: #FFEBEE;
    --prod-amber: #E65100;
    --prod-amber-pale: #FFF3E0;
    --prod-purple: #6230B5;
    --prod-purple-pale: #F5F0FF;
    --prod-radius-sm: 6px;
    --prod-radius-md: 10px;
    --prod-radius-lg: 14px;
    --prod-radius-xl: 20px;
    --prod-shadow-sm: 0 1px 3px rgba(0, 0, 0, .07), 0 1px 2px rgba(0, 0, 0, .05);
    --prod-shadow-md: 0 4px 12px rgba(0, 0, 0, .08), 0 2px 4px rgba(0, 0, 0, .05);
    --prod-shadow-lg: 0 20px 48px rgba(0, 0, 0, .14), 0 8px 16px rgba(0, 0, 0, .07);
    --prod-font: 'DM Sans', sans-serif;
    --prod-mono: 'DM Mono', monospace;
  }

  .sale-page {
    padding: 28px 28px 40px;
    max-width: 100%;
    margin: 0 auto;
    background: var(--color-background-tertiary, #F4F5F7);
    min-height: 100%;
    border-radius: var(--border-radius-xl, 18px);
    font-family: var(--prod-font);
  }

  .sale-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
    gap: 16px;
    flex-wrap: wrap;
  }

  .sale-page-eyebrow {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--prod-blue);
    margin-bottom: 4px;
  }

  .sale-page-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.4px;
    line-height: 1.2;
  }

  .sale-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
  }

  .sale-stat-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    padding: 20px 22px;
    box-shadow: var(--prod-shadow-sm);
    position: relative;
    overflow: hidden;
  }

  .sale-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--prod-radius-lg) var(--prod-radius-lg) 0 0;
    background: var(--_accent, #CBD5E1);
  }

  .sale-stat-card.blue {
    --_accent: var(--prod-blue);
  }

  .sale-stat-card.green {
    --_accent: var(--prod-green);
  }

  .sale-stat-card.purple {
    --_accent: var(--prod-purple);
  }

  .sale-stat-card.amber {
    --_accent: var(--prod-amber);
  }

  .sale-stat-card.red {
    --_accent: var(--prod-red);
  }

  .sale-stat-card.red .sale-stat-icon {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .sale-stat-icon {
    width: 36px;
    height: 36px;
    border-radius: var(--prod-radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    margin-bottom: 14px;
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
  }

  .sale-stat-card.green .sale-stat-icon {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .sale-stat-card.purple .sale-stat-icon {
    background: var(--prod-purple-pale);
    color: var(--prod-purple);
  }

  .sale-stat-card.amber .sale-stat-icon {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
  }

  .sale-stat-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-secondary);
    margin-bottom: 6px;
  }

  .sale-stat-value {
    font-size: 32px;
    font-weight: 600;
    color: var(--color-text-primary);
    font-family: var(--prod-mono);
  }

  .sale-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    box-shadow: var(--prod-shadow-sm);
    overflow: hidden;
  }

  .sale-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }

  .sale-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .sale-search-wrap {
    position: relative;
  }

  .sale-search-wrap i {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-text-secondary);
    font-size: 12px;
    pointer-events: none;
  }

  .sale-search {
    padding: 8px 14px 8px 34px;
    font-size: 13px;
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--prod-radius-md);
    background: var(--color-background-secondary, #F8FAFC);
    color: var(--color-text-primary);
    outline: none;
    width: 230px;
  }

  .sale-search:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
  }

  .sale-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 500;
    border-radius: var(--prod-radius-md);
    border: 1px solid rgba(0, 0, 0, .12);
    background: var(--color-background-primary, #fff);
    color: var(--color-text-primary);
    cursor: pointer;
    transition: all .15s;
  }

  .sale-btn:hover {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .sale-btn-primary {
    background: var(--prod-blue);
    color: #fff;
    border-color: var(--prod-blue);
    box-shadow: 0 1px 3px rgba(26, 107, 191, .3);
  }

  .sale-btn-primary:hover {
    background: var(--prod-blue-dark);
  }

  .sale-table-wrap {
    overflow-x: auto;
  }

  .sale-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }

  .sale-table thead tr {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .sale-table th {
    padding: 11px 18px;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .08em;
    border-bottom: 1px solid rgba(0, 0, 0, .07);
  }

  .sale-table td {
    padding: 13px 18px;
    vertical-align: middle;
  }

  .sale-name-cell {
    display: flex;
    align-items: center;
  }

  .sale-avatar {
    width: 36px;
    height: 36px;
    border-radius: var(--prod-radius-sm);
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 600;
    margin-right: 10px;
  }

  .sale-name-primary {
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-primary);
  }

  .sale-name-secondary {
    font-size: 11px;
    color: var(--color-text-secondary);
  }

  .sale-badge {
    display: inline-flex;
    padding: 4px 10px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 600;
  }

  .sale-badge.paid {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .sale-badge.unpaid {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .sale-badge.charged {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
  }

  .sale-actions {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
  }

  .sale-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px;
    font-size: 12px;
    font-weight: 500;
    border-radius: var(--prod-radius-sm);
    border: 1px solid rgba(0, 0, 0, .1);
    background: transparent;
    cursor: pointer;
    transition: all .15s;
  }

  .sale-action-btn.view:hover {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
  }

  .sale-action-btn.edit:hover {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
  }

  .sale-action-btn.danger:hover {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .sale-empty {
    padding: 64px;
    text-align: center;
    color: var(--color-text-secondary);
  }

  .sale-empty-icon {
    font-size: 32px;
    opacity: .25;
    margin-bottom: 12px;
  }

  .sale-pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    background: var(--color-background-primary, #fff);
    gap: 16px;
    flex-wrap: wrap;
  }

  .sale-pagination-info {
    font-size: 12px;
    color: var(--color-text-secondary);
  }

  .sale-pagination {
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .sale-page-btn {
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--prod-radius-sm);
    background: var(--color-background-primary, #fff);
    color: var(--color-text-primary);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all .15s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .sale-page-btn:hover:not(:disabled) {
    background: var(--prod-blue-pale);
    border-color: var(--prod-blue);
    color: var(--prod-blue);
  }

  .sale-page-btn.active {
    background: var(--prod-blue);
    border-color: var(--prod-blue);
    color: #fff;
  }

  .sale-page-btn:disabled {
    opacity: .4;
    cursor: not-allowed;
  }

  .sale-entries-select {
    padding: 6px 28px 6px 12px;
    font-size: 13px;
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--prod-radius-md);
    background: var(--color-background-secondary, #F8FAFC);
    color: var(--color-text-primary);
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748B' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
  }

  .sale-entries-select:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    outline: none;
  }

  .sale-entries-label {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-right: 8px;
  }

  .sale-entries-wrap {
    display: flex;
    align-items: center;
  }

  .sale-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, .5);
    backdrop-filter: blur(4px);
    z-index: 9000;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }

  .sale-overlay.open {
    display: flex;
  }

  .sale-modal {
    background: var(--color-background-primary, #fff);
    border-radius: var(--prod-radius-xl);
    border: 1px solid rgba(0, 0, 0, .08);
    width: 100%;
    max-width: 700px;
    max-height: 92vh;
    overflow-y: auto;
    box-shadow: var(--prod-shadow-lg);
  }

  .sale-modal-header {
    padding: 22px 26px 18px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  .sale-modal-title {
    font-size: 17px;
    font-weight: 600;
    color: var(--color-text-primary);
  }

  .sale-modal-close {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--color-background-secondary, #F1F5F9);
    border: none;
    cursor: pointer;
    color: var(--color-text-secondary);
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .sale-modal-close:hover {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .sale-modal-body {
    padding: 26px;
  }

  .sale-modal-footer {
    padding: 18px 26px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    justify-content: flex-end;
    gap: 8px;
  }

  .sale-form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 18px;
  }

  .sale-form-group.half {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }

  .sale-form-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .07em;
  }

  .sale-form-label .req {
    color: var(--prod-red);
    margin-left: 2px;
  }

  .sale-form-input,
  .sale-form-select,
  .sale-form-textarea {
    font-family: var(--prod-font);
    font-size: 13.5px;
    color: var(--color-text-primary);
    background: var(--color-background-secondary, #F8FAFC);
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--prod-radius-md);
    padding: 9px 13px;
    outline: none;
    width: 100%;
  }

  .sale-form-input:focus,
  .sale-form-select:focus,
  .sale-form-textarea:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    background: var(--color-background-primary, #fff);
  }

  @keyframes slideOut {
    from { opacity: 1; transform: translateX(0); }
    to { opacity: 0; transform: translateX(100%); }
  }

  @media (max-width: 600px) {
    .sale-search {
      width: 160px;
    }

    .sale-form-group.half {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="sale-page">
  <div class="sale-page-header">
    <div>
      <div class="sale-page-eyebrow"><i class="fas fa-cash-register" style="margin-right:5px"></i>Inventory</div>
      <div class="sale-page-title">Sales</div>
    </div>
  </div>

  <div class="sale-stats-grid">
    <div class="sale-stat-card blue">
      <div class="sale-stat-icon"><i class="fas fa-money-bill-wave"></i></div>
      <div class="sale-stat-label">Total Revenue</div>
      <div class="sale-stat-value" id="statTotal">—</div>
    </div>
    <div class="sale-stat-card green">
      <div class="sale-stat-icon"><i class="fas fa-cash-register"></i></div>
      <div class="sale-stat-label">POS Revenue</div>
      <div class="sale-stat-value" id="statPos">—</div>
    </div>
    <div class="sale-stat-card purple">
      <div class="sale-stat-icon"><i class="fas fa-utensils"></i></div>
      <div class="sale-stat-label">Canteen Revenue</div>
      <div class="sale-stat-value" id="statCanteen">—</div>
    </div>
    <div class="sale-stat-card amber">
      <div class="sale-stat-icon"><i class="fas fa-bed"></i></div>
      <div class="sale-stat-label">Room Charge Revenue</div>
      <div class="sale-stat-value" id="statRoom">—</div>
    </div>
    <div class="sale-stat-card red">
      <div class="sale-stat-icon"><i class="fas fa-user-tie"></i></div>
      <div class="sale-stat-label">Staff Purchase Revenue</div>
      <div class="sale-stat-value" id="statStaff">—</div>
    </div>
  </div>

  <div class="sale-card">
    <div class="sale-card-header">
      <div>
        <strong>All Sales</strong>
        <span id="tableCount" style="font-size:12px;color:var(--color-text-secondary);margin-left:8px">Loading…</span>
      </div>
      <div class="sale-toolbar">
        <div class="sale-entries-wrap">
          <span class="sale-entries-label">Show</span>
          <select class="sale-entries-select" id="entriesPerPage" onchange="changeEntriesPerPage()">
            <option value="10">10</option>
            <option value="25" selected>25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="sale-entries-label">entries</span>
        </div>
        <div class="sale-search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" id="searchInput" class="sale-search" placeholder="Search sales…" oninput="filterTable()">
        </div>
        <button onclick="openAddSaleModal()" class="sale-btn sale-btn-primary">
          <i class="fas fa-plus"></i> New Sale
        </button>
      </div>
    </div>
    <div class="sale-table-wrap">
      <table class="sale-table" id="salesTable">
        <thead>
          <tr>
            <th>Sale #</th>
            <th>Property</th>
            <th>Type</th>
            <th>Customer</th>
            <th>Date</th>
            <th style="text-align:right">Amount</th>
            <th style="text-align:center">Status</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="salesTableBody">
          <tr>
            <td colspan="8" class="sale-empty">
              <div class="sale-empty-icon"><i class="fas fa-spinner fa-spin"></i></div>
              <div>Loading sales…</div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="sale-pagination-wrap" id="paginationWrap" style="display:none">
      <div class="sale-pagination-info" id="paginationInfo">Showing 1-25 of 100 entries</div>
      <div class="sale-pagination" id="paginationControls"></div>
    </div>
  </div>
</div>

<!-- View Sale Modal -->
<div class="sale-overlay" id="viewSaleOverlay">
  <div class="sale-modal" style="max-width:600px">
    <div class="sale-modal-header">
      <span class="sale-modal-title" id="viewSaleTitle">Sale Details</span>
      <button class="sale-modal-close" onclick="closeViewSaleModal()"><i class="fas fa-times"></i></button>
    </div>
    <div class="sale-modal-body" id="viewSaleBody"></div>
    <div class="sale-modal-footer">
      <button class="sale-btn" onclick="closeViewSaleModal()">Close</button>
    </div>
  </div>
</div>

<!-- Add Sale Modal -->
<div class="sale-overlay" id="addSaleOverlay">
  <div class="sale-modal">
    <div class="sale-modal-header">
      <span class="sale-modal-title">New Sale</span>
      <button class="sale-modal-close" onclick="closeAddSaleModal()"><i class="fas fa-times"></i></button>
    </div>
    <form id="addSaleForm" onsubmit="submitAddSale(event)">
      <div class="sale-modal-body">
        <div class="sale-form-group half">
          <div>
            <label class="sale-form-label">Sale Number <span class="req">*</span></label>
            <input type="text" name="sale_number" class="sale-form-input" required>
          </div>
          <div>
            <label class="sale-form-label">Property <span class="req">*</span></label>
            <select name="property_id" class="sale-form-select" required>
              <option value="">Select Property</option>
            </select>
          </div>
        </div>
        <div class="sale-form-group half">
          <div>
            <label class="sale-form-label">Sale Type <span class="req">*</span></label>
            <select name="sale_type" class="sale-form-select" required>
              <option value="">Select Type</option>
              <option value="pos">POS</option>
              <option value="canteen">Canteen</option>
              <option value="room_charge">Room Charge</option>
            </select>
          </div>
          <div>
            <label class="sale-form-label">Customer Name</label>
            <input type="text" name="customer_name" class="sale-form-input">
          </div>
        </div>
        <div class="sale-form-group half">
          <div>
            <label class="sale-form-label">Sale Date <span class="req">*</span></label>
            <input type="date" name="sale_date" class="sale-form-input" required>
          </div>
          <div>
            <label class="sale-form-label">Total Amount <span class="req">*</span></label>
            <input type="number" name="total_amount" step="0.01" class="sale-form-input" required>
          </div>
        </div>
        <div class="sale-form-group">
          <label class="sale-form-label">Payment Status <span class="req">*</span></label>
          <select name="payment_status" class="sale-form-select" required>
            <option value="unpaid">Unpaid</option>
            <option value="paid">Paid</option>
            <option value="charged_to_room">Charged to Room</option>
          </select>
        </div>
        <div class="sale-form-group">
          <label class="sale-form-label">Notes</label>
          <textarea name="notes" class="sale-form-textarea" rows="3"></textarea>
        </div>
      </div>
      <div class="sale-modal-footer">
        <button type="button" class="sale-btn" onclick="closeAddSaleModal()">Cancel</button>
        <button type="submit" class="sale-btn sale-btn-primary"><i class="fas fa-plus"></i> Create Sale</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Sale Modal -->
<div class="sale-overlay" id="editSaleOverlay">
  <div class="sale-modal">
    <div class="sale-modal-header">
      <span class="sale-modal-title">Edit Sale</span>
      <button class="sale-modal-close" onclick="closeEditSaleModal()"><i class="fas fa-times"></i></button>
    </div>
    <form id="editSaleForm" onsubmit="submitEditSale(event)">
      <input type="hidden" name="sale_id" id="editSaleId">
      <div class="sale-modal-body">
        <div class="sale-form-group half">
          <div>
            <label class="sale-form-label">Sale Number <span class="req">*</span></label>
            <input type="text" name="sale_number" class="sale-form-input" required>
          </div>
          <div>
            <label class="sale-form-label">Property <span class="req">*</span></label>
            <select name="property_id" class="sale-form-select" required>
              <option value="">Select Property</option>
            </select>
          </div>
        </div>
        <div class="sale-form-group half">
          <div>
            <label class="sale-form-label">Sale Type <span class="req">*</span></label>
            <select name="sale_type" class="sale-form-select" required>
              <option value="">Select Type</option>
              <option value="pos">POS</option>
              <option value="canteen">Canteen</option>
              <option value="room_charge">Room Charge</option>
            </select>
          </div>
          <div>
            <label class="sale-form-label">Customer Name</label>
            <input type="text" name="customer_name" class="sale-form-input">
          </div>
        </div>
        <div class="sale-form-group half">
          <div>
            <label class="sale-form-label">Sale Date <span class="req">*</span></label>
            <input type="date" name="sale_date" class="sale-form-input" required>
          </div>
          <div>
            <label class="sale-form-label">Total Amount <span class="req">*</span></label>
            <input type="number" name="total_amount" step="0.01" class="sale-form-input" required>
          </div>
        </div>
        <div class="sale-form-group">
          <label class="sale-form-label">Payment Status <span class="req">*</span></label>
          <select name="payment_status" class="sale-form-select" required>
            <option value="unpaid">Unpaid</option>
            <option value="paid">Paid</option>
            <option value="charged_to_room">Charged to Room</option>
          </select>
        </div>
        <div class="sale-form-group">
          <label class="sale-form-label">Notes</label>
          <textarea name="notes" class="sale-form-textarea" rows="3"></textarea>
        </div>
      </div>
      <div class="sale-modal-footer">
        <button type="button" class="sale-btn" onclick="closeEditSaleModal()">Cancel</button>
        <button type="submit" class="sale-btn sale-btn-primary"><i class="fas fa-save"></i> Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
  let salesData = [];
  let filteredData = [];
  let currentPage = 1;
  let entriesPerPage = 25;
  let propertiesData = [];

  const csrfToken = '<?php echo htmlspecialchars(CSRF_TOKEN, ENT_QUOTES, 'UTF-8'); ?>';
  const userPermissions = <?php echo isset($_SESSION['permissions']) ? json_encode($_SESSION['permissions']) : '[]'; ?>;
  const userRole = '<?php echo $_SESSION['role_code'] ?? ''; ?>';

  function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
      position: fixed; top: 20px; right: 20px; z-index: 10000;
      padding: 12px 20px; border-radius: 8px; font-size: 13px; font-weight: 500;
      color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      animation: slideIn 0.3s ease-out;
      ${type === 'success' ? 'background: #2E7D32;' : type === 'error' ? 'background: #C62828;' : 'background: #1A6BBF;'}
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => {
      toast.style.animation = 'slideOut 0.3s ease-out';
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }

  function hasPermission(permission) {
    if (userRole === 'super_admin' || userRole === 'admin') return true;
    return Array.isArray(userPermissions) && userPermissions.includes(permission);
  }

  async function fetchSales() {
    try {
      const res = await fetch('../api/inventory_sales.php?action=list');
      const json = await res.json();
      if (json.success) {
        salesData = json.data;
        filteredData = [...salesData];
        currentPage = 1;
        renderStats();
        renderTable();
      }
    } catch (e) {
      console.error('Error fetching sales:', e);
      document.getElementById('salesTableBody').innerHTML = `<tr><td colspan="8" class="sale-empty"><div class="sale-empty-icon"><i class="fas fa-circle-xmark"></i></div><div>Failed to load data.</div></td></tr>`;
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/inventory_sales.php?action=related');
      const json = await res.json();
      if (json.success) {
        propertiesData = json.data.properties;
        populateDropdowns();
      }
    } catch (e) {
      console.error('Error fetching related data:', e);
    }
  }

  function renderStats() {
    const totalRevenue = salesData.reduce((sum, s) => sum + parseFloat(s.total_amount || 0), 0);
    const posRevenue = salesData.filter(s => s.sale_type === 'pos').reduce((sum, s) => sum + parseFloat(s.total_amount || 0), 0);
    const canteenRevenue = salesData.filter(s => s.sale_type === 'canteen').reduce((sum, s) => sum + parseFloat(s.total_amount || 0), 0);
    const roomRevenue = salesData.filter(s => s.sale_type === 'room_charge').reduce((sum, s) => sum + parseFloat(s.total_amount || 0), 0);
    const staffRevenue = salesData.filter(s => s.sale_type === 'staff_purchase').reduce((sum, s) => sum + parseFloat(s.total_amount || 0), 0);

    const pairs = [
      ['statTotal', totalRevenue],
      ['statPos', posRevenue],
      ['statCanteen', canteenRevenue],
      ['statRoom', roomRevenue],
      ['statStaff', staffRevenue]
    ];

    pairs.forEach(([id, end]) => {
      const el = document.getElementById(id);
      if (!el) return;
      if (end === 0) {
        el.textContent = '₱0.00';
        return;
      }
      let start = 0,
        step = Math.max(0.01, end / 20);
      const timer = setInterval(() => {
        start = Math.min(start + step, end);
        el.textContent = '₱' + start.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        if (start >= end) clearInterval(timer);
      }, 25);
    });
  }

  const badgeClasses = {
    'unpaid': 'unpaid',
    'paid': 'paid',
    'charged_to_room': 'charged'
  };

  function renderTable() {
    const tbody = document.getElementById('salesTableBody');
    const count = document.getElementById('tableCount');

    if (!filteredData.length) {
      count.textContent = '0 sales';
      document.getElementById('paginationWrap').style.display = 'none';
      tbody.innerHTML = `<tr><td colspan="8" class="sale-empty"><div class="sale-empty-icon"><i class="fas fa-cash-register"></i></div><div>No sales found</div><div style="margin-top:8px"><a href="javascript:;" onclick="openAddSaleModal()" style="color:var(--prod-blue);font-weight:500">Create your first sale</a></div></td></tr>`;
      return;
    }

    const totalEntries = filteredData.length;
    const totalPages = Math.ceil(totalEntries / entriesPerPage);
    const start = (currentPage - 1) * entriesPerPage;
    const end = Math.min(start + entriesPerPage, totalEntries);
    const paginatedData = filteredData.slice(start, end);

    count.textContent = `${totalEntries} sale${totalEntries !== 1 ? 's' : ''}`;

    const paginationWrap = document.getElementById('paginationWrap');
    if (totalEntries > 10) {
      paginationWrap.style.display = 'flex';
      renderPagination(totalPages, start, end, totalEntries);
    } else {
      paginationWrap.style.display = 'none';
    }

    tbody.innerHTML = paginatedData.map(sale => {
      const badgeClass = badgeClasses[sale.payment_status] || 'unpaid';
      const dateStr = sale.sale_date ? new Date(sale.sale_date).toLocaleDateString('en-US', {
        month: 'short',
        day: '2-digit',
        year: 'numeric'
      }) : '—';
      const saleType = sale.sale_type ? sale.sale_type.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) : '—';
      return `
      <tr class="sale-row">
        <td>
          <div class="sale-name-cell">
            <div class="sale-avatar"><i class="fas fa-money-bill-wave"></i></div>
            <div>
              <div class="sale-name-primary">${sale.sale_number}</div>
            </div>
          </div>
        </td>
        <td><span class="sale-name-primary">${sale.property_name}</span></td>
        <td><span class="sale-name-primary">${saleType}</span></td>
        <td><span class="sale-name-primary">${sale.customer_name || '—'}</span></td>
        <td><span class="sale-name-secondary">${dateStr}</span></td>
        <td style="text-align:right"><span class="sale-name-primary" style="font-family:var(--prod-mono)">₱${parseFloat(sale.total_amount).toFixed(2)}</span></td>
        <td style="text-align:center"><span class="sale-badge ${badgeClass}">${(sale.payment_status || '').replace(/_/g, ' ')}</span></td>
        <td>
          <div class="sale-actions">
            <button onclick="viewSaleDetails('${sale.sale_id}')" class="sale-action-btn view"><i class="fas fa-eye"></i> View</button>
            <button onclick="openEditSaleModal('${sale.sale_id}')" class="sale-action-btn edit"><i class="fas fa-pen"></i> Edit</button>
            <button onclick="deleteSale(this)" data-id="${sale.sale_id}" data-number="${sale.sale_number}" class="sale-action-btn danger"><i class="fas fa-trash-alt"></i></button>
          </div>
        </td>
      </tr>`;
    }).join('');
  }

  function changeEntriesPerPage() {
    entriesPerPage = parseInt(document.getElementById('entriesPerPage').value);
    currentPage = 1;
    renderTable();
  }

  function goToPage(page) {
    const totalPages = Math.ceil(filteredData.length / entriesPerPage);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    renderTable();
  }

  function renderPagination(totalPages, start, end, total) {
    const paginationInfo = document.getElementById('paginationInfo');
    const paginationControls = document.getElementById('paginationControls');

    paginationInfo.textContent = `Showing ${start + 1}-${end} of ${total} entries`;

    let html = '';
    html += `<button class="sale-page-btn" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`;

    const maxButtons = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);
    if (endPage - startPage < maxButtons - 1) {
      startPage = Math.max(1, endPage - maxButtons + 1);
    }

    if (startPage > 1) {
      html += `<button class="sale-page-btn" onclick="goToPage(1)">1</button>`;
      if (startPage > 2) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
    }

    for (let i = startPage; i <= endPage; i++) {
      html += `<button class="sale-page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
    }

    if (endPage < totalPages) {
      if (endPage < totalPages - 1) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
      html += `<button class="sale-page-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
    }

    html += `<button class="sale-page-btn" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`;
    paginationControls.innerHTML = html;
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toLowerCase();
    currentPage = 1;

    if (!filter) {
      filteredData = [...salesData];
    } else {
      filteredData = salesData.filter(s =>
        s.sale_number.toLowerCase().includes(filter) ||
        (s.customer_name && s.customer_name.toLowerCase().includes(filter)) ||
        s.property_name.toLowerCase().includes(filter)
      );
    }

    renderTable();
  }

  function populateDropdowns() {
    const propertySelects = document.querySelectorAll('select[name="property_id"]');
    const propOptions = '<option value="">Select Property</option>' + propertiesData.map(p => `<option value="${p.property_id}">${p.property_name}</option>`).join('');
    propertySelects.forEach(sel => sel.innerHTML = propOptions);
  }

  // View Sale Details
  function viewSaleDetails(saleId) {
    const sale = salesData.find(s => s.sale_id == saleId);
    if (!sale) return;

    const badgeClass = badgeClasses[sale.payment_status] || 'unpaid';
    const dateStr = sale.sale_date ? new Date(sale.sale_date).toLocaleDateString('en-US', {
      month: 'short',
      day: '2-digit',
      year: 'numeric'
    }) : '—';
    const saleType = sale.sale_type ? sale.sale_type.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) : '—';

    const overlay = document.getElementById('viewSaleOverlay');
    document.getElementById('viewSaleTitle').textContent = `Sale: ${sale.sale_number}`;
    document.getElementById('viewSaleBody').innerHTML = `
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:22px;">
        <div>
          <div class="sale-form-label" style="margin-bottom:12px;">Sale Information</div>
          <div style="margin-bottom:10px"><span style="font-size:11px;color:var(--color-text-secondary)">Sale Number</span><br><span class="sale-name-primary" style="font-family:var(--prod-mono)">${sale.sale_number}</span></div>
          <div style="margin-bottom:10px"><span style="font-size:11px;color:var(--color-text-secondary)">Property</span><br><span class="sale-name-primary">${sale.property_name}</span></div>
          <div style="margin-bottom:10px"><span style="font-size:11px;color:var(--color-text-secondary)">Sale Type</span><br><span class="sale-name-primary">${saleType}</span></div>
          <div style="margin-bottom:10px"><span style="font-size:11px;color:var(--color-text-secondary)">Customer</span><br><span class="sale-name-primary">${sale.customer_name || '—'}</span></div>
        </div>
        <div>
          <div class="sale-form-label" style="margin-bottom:12px;">Financial Details</div>
          <div style="margin-bottom:10px"><span style="font-size:11px;color:var(--color-text-secondary)">Total Amount</span><br><span class="sale-name-primary" style="font-size:22px;font-family:var(--prod-mono)">₱${parseFloat(sale.total_amount).toFixed(2)}</span></div>
          <div style="margin-bottom:10px"><span style="font-size:11px;color:var(--color-text-secondary)">Payment Status</span><br><span class="sale-badge ${badgeClass}">${(sale.payment_status || '').replace(/_/g, ' ')}</span></div>
          <div style="margin-bottom:10px"><span style="font-size:11px;color:var(--color-text-secondary)">Sale Date</span><br><span class="sale-name-primary">${dateStr}</span></div>
          <div style="margin-bottom:10px"><span style="font-size:11px;color:var(--color-text-secondary)">Notes</span><br><span class="sale-name-primary">${sale.notes || '—'}</span></div>
        </div>
      </div>
    `;
    overlay.classList.add('open');
  }

  function closeViewSaleModal() {
    document.getElementById('viewSaleOverlay').classList.remove('open');
  }

  // Add Sale Modal
  function openAddSaleModal() {
    document.getElementById('addSaleOverlay').classList.add('open');
  }

  function closeAddSaleModal() {
    document.getElementById('addSaleOverlay').classList.remove('open');
    document.getElementById('addSaleForm').reset();
  }

  function submitAddSale(event) {
    event.preventDefault();
    const form = document.getElementById('addSaleForm');
    const formData = new FormData(form);
    formData.append('action', 'add_inventory_sale');
    formData.append('csrf_token', csrfToken);

    fetch('../api/inventory_sales.php', {
        method: 'POST',
        body: formData
      })
      .then(r => r.json())
      .then(data => {
        closeAddSaleModal();
        if (data.success) {
          showToast('Sale created successfully', 'success');
          fetchSales();
        } else {
          showToast(data.message || 'Error creating sale', 'error');
        }
      })
      .catch(e => {
        closeAddSaleModal();
        showToast('Error creating sale', 'error');
        console.error(e);
      });
  }

  // Edit Sale Modal
  function openEditSaleModal(saleId) {
    const sale = salesData.find(s => s.sale_id == saleId);
    if (!sale) return;

    document.getElementById('editSaleId').value = sale.sale_id;
    const form = document.getElementById('editSaleForm');
    form.elements['sale_number'].value = sale.sale_number;
    form.elements['property_id'].value = sale.property_id;
    form.elements['sale_type'].value = sale.sale_type;
    form.elements['customer_name'].value = sale.customer_name || '';
    form.elements['sale_date'].value = (sale.sale_date || '').split(' ')[0];
    form.elements['total_amount'].value = parseFloat(sale.total_amount);
    form.elements['payment_status'].value = sale.payment_status;
    form.elements['notes'].value = sale.notes || '';

    document.getElementById('editSaleOverlay').classList.add('open');
  }

  function closeEditSaleModal() {
    document.getElementById('editSaleOverlay').classList.remove('open');
    document.getElementById('editSaleForm').reset();
  }

  function submitEditSale(event) {
    event.preventDefault();
    const form = document.getElementById('editSaleForm');
    const formData = new FormData(form);
    formData.append('action', 'edit_inventory_sale');
    formData.append('csrf_token', csrfToken);

    fetch('../api/inventory_sales.php', {
        method: 'POST',
        body: formData
      })
      .then(r => r.json())
      .then(data => {
        closeEditSaleModal();
        if (data.success) {
          showToast('Sale updated successfully', 'success');
          fetchSales();
        } else {
          showToast(data.message || 'Error updating sale', 'error');
        }
      })
      .catch(e => {
        closeEditSaleModal();
        showToast('Error updating sale', 'error');
        console.error(e);
      });
  }

  // Delete Sale
  function deleteSale(btn) {
    const saleId = btn.dataset.id;
    const saleNumber = btn.dataset.number;
    if (!confirm(`Are you sure you want to delete sale "${saleNumber}"? This cannot be undone.`)) return;

    const formData = new FormData();
    formData.append('action', 'delete_inventory_sale');
    formData.append('sale_id', saleId);
    formData.append('csrf_token', csrfToken);

    fetch('../api/inventory_sales.php', {
        method: 'POST',
        body: formData
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          showToast('Sale deleted successfully', 'success');
          fetchSales();
        } else {
          showToast(data.message || 'Error deleting sale', 'error');
        }
      })
      .catch(e => {
        showToast('Error deleting sale', 'error');
        console.error(e);
      });
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchSales();
    fetchRelated();
  });
</script>