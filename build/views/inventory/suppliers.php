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

  .sup-page {
    padding: 28px 28px 40px;
    max-width: 100%;
    margin: 0 auto;
    background: var(--color-background-tertiary, #F4F5F7);
    min-height: 100%;
    border-radius: var(--border-radius-xl, 18px);
    font-family: var(--prod-font);
  }

  .sup-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
    gap: 16px;
    flex-wrap: wrap;
  }

  .sup-page-eyebrow {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--prod-blue);
    margin-bottom: 4px;
  }

  .sup-page-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.4px;
    line-height: 1.2;
  }

  .sup-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
  }

  .sup-stat-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    padding: 20px 22px;
    box-shadow: var(--prod-shadow-sm);
    position: relative;
    overflow: hidden;
  }

  .sup-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--prod-radius-lg) var(--prod-radius-lg) 0 0;
    background: var(--_accent, #CBD5E1);
  }

  .sup-stat-card.blue {
    --_accent: var(--prod-blue);
  }

  .sup-stat-card.green {
    --_accent: var(--prod-green);
  }

  .sup-stat-card.purple {
    --_accent: var(--prod-purple);
  }

  .sup-stat-icon {
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

  .sup-stat-card.green .sup-stat-icon {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .sup-stat-card.purple .sup-stat-icon {
    background: var(--prod-purple-pale);
    color: var(--prod-purple);
  }

  .sup-stat-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-secondary);
    margin-bottom: 6px;
  }

  .sup-stat-value {
    font-size: 32px;
    font-weight: 600;
    color: var(--color-text-primary);
    font-family: var(--prod-mono);
  }

  .sup-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    box-shadow: var(--prod-shadow-sm);
    overflow: hidden;
  }

  .sup-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }

  .sup-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .sup-search-wrap {
    position: relative;
  }

  .sup-search-wrap i {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-text-secondary);
    font-size: 12px;
    pointer-events: none;
  }

  .sup-search {
    padding: 8px 14px 8px 34px;
    font-size: 13px;
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--prod-radius-md);
    background: var(--color-background-secondary, #F8FAFC);
    color: var(--color-text-primary);
    outline: none;
    width: 230px;
  }

  .sup-search:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
  }

  .sup-btn {
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

  .sup-btn:hover {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .sup-btn-primary {
    background: var(--prod-blue);
    color: #fff;
    border-color: var(--prod-blue);
    box-shadow: 0 1px 3px rgba(26, 107, 191, .3);
  }

  .sup-btn-primary:hover {
    background: var(--prod-blue-dark);
  }

  .sup-table-wrap {
    overflow-x: auto;
  }

  .sup-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }

  .sup-table thead tr {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .sup-table th {
    padding: 11px 18px;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .08em;
    border-bottom: 1px solid rgba(0, 0, 0, .07);
  }

  .sup-table td {
    padding: 13px 18px;
    vertical-align: middle;
  }

  .sup-name-cell {
    display: flex;
    align-items: center;
  }

  .sup-avatar {
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

  .sup-name-primary {
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-primary);
  }

  .sup-name-secondary {
    font-size: 11px;
    color: var(--color-text-secondary);
  }

  .sup-badge {
    display: inline-flex;
    padding: 4px 10px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 600;
  }

  .sup-badge.active {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .sup-actions {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
  }

  .sup-action-btn {
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

  .sup-action-btn.view:hover {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
  }

  .sup-action-btn.edit:hover {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
  }

  .sup-action-btn.danger:hover {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .sup-empty {
    padding: 64px;
    text-align: center;
    color: var(--color-text-secondary);
  }

  .sup-empty-icon {
    font-size: 32px;
    opacity: .25;
    margin-bottom: 12px;
  }

  .sup-pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    background: var(--color-background-primary, #fff);
    gap: 16px;
    flex-wrap: wrap;
  }

  .sup-pagination-info {
    font-size: 12px;
    color: var(--color-text-secondary);
  }

  .sup-pagination {
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .sup-page-btn {
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

  .sup-page-btn:hover:not(:disabled) {
    background: var(--prod-blue-pale);
    border-color: var(--prod-blue);
    color: var(--prod-blue);
  }

  .sup-page-btn.active {
    background: var(--prod-blue);
    border-color: var(--prod-blue);
    color: #fff;
  }

  .sup-page-btn:disabled {
    opacity: .4;
    cursor: not-allowed;
  }

  .sup-entries-select {
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

  .sup-entries-select:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    outline: none;
  }

  .sup-entries-label {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-right: 8px;
  }

  .sup-entries-wrap {
    display: flex;
    align-items: center;
  }

  .sup-overlay {
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

  .sup-overlay.open {
    display: flex;
  }

  .sup-modal {
    background: var(--color-background-primary, #fff);
    border-radius: var(--prod-radius-xl);
    border: 1px solid rgba(0, 0, 0, .08);
    width: 100%;
    max-width: 600px;
    max-height: 92vh;
    overflow-y: auto;
    box-shadow: var(--prod-shadow-lg);
  }

  .sup-modal-header {
    padding: 22px 26px 18px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  .sup-modal-title {
    font-size: 17px;
    font-weight: 600;
    color: var(--color-text-primary);
  }

  .sup-modal-close {
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

  .sup-modal-close:hover {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .sup-modal-body {
    padding: 26px;
  }

  .sup-modal-footer {
    padding: 18px 26px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    justify-content: flex-end;
    gap: 8px;
  }

  .sup-form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 18px;
  }

  .sup-form-group.half {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }

  .sup-form-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .07em;
  }

  .sup-form-label .req {
    color: var(--prod-red);
    margin-left: 2px;
  }

  .sup-form-input,
  .sup-form-select,
  .sup-form-textarea {
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

  .sup-form-input:focus,
  .sup-form-select:focus,
  .sup-form-textarea:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    background: var(--color-background-primary, #fff);
  }

  .sup-form-textarea {
    resize: vertical;
    min-height: 76px;
  }

  @media (max-width: 600px) {
    .sup-search {
      width: 160px;
    }

    .sup-form-group.half {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="sup-page">
  <div class="sup-page-header">
    <div>
      <div class="sup-page-eyebrow"><i class="fas fa-truck-field" style="margin-right:5px"></i>Inventory</div>
      <div class="sup-page-title">Suppliers</div>
    </div>
  </div>

  <div class="sup-stats-grid">
    <div class="sup-stat-card blue">
      <div class="sup-stat-icon"><i class="fas fa-building"></i></div>
      <div class="sup-stat-label">Total Suppliers</div>
      <div class="sup-stat-value" id="statTotal">—</div>
    </div>
    <div class="sup-stat-card green">
      <div class="sup-stat-icon"><i class="fas fa-user-check"></i></div>
      <div class="sup-stat-label">With Contact</div>
      <div class="sup-stat-value" id="statContact">—</div>
    </div>
    <div class="sup-stat-card purple">
      <div class="sup-stat-icon"><i class="fas fa-id-card"></i></div>
      <div class="sup-stat-label">With TIN</div>
      <div class="sup-stat-value" id="statTin">—</div>
    </div>
  </div>

  <div class="sup-card">
    <div class="sup-card-header">
      <div>
        <strong>All Suppliers</strong>
        <span id="tableCount" style="font-size:12px;color:var(--color-text-secondary);margin-left:8px">Loading…</span>
      </div>
      <div class="sup-toolbar">
        <div class="sup-entries-wrap">
          <span class="sup-entries-label">Show</span>
          <select class="sup-entries-select" id="entriesPerPage" onchange="changeEntriesPerPage()">
            <option value="10">10</option>
            <option value="25" selected>25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="sup-entries-label">entries</span>
        </div>
        <div class="sup-search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" id="searchInput" class="sup-search" placeholder="Search suppliers…" oninput="filterTable()">
        </div>
        <button onclick="openAddSupplierModal()" class="sup-btn sup-btn-primary">
          <i class="fas fa-plus"></i> Add Supplier
        </button>
      </div>
    </div>
    <div class="sup-table-wrap">
      <table class="sup-table" id="suppliersTable">
        <thead>
          <tr>
            <th>Supplier</th>
            <th>Contact</th>
            <th>Location</th>
            <th>Payment Terms</th>
            <th style="text-align:center">Status</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="suppliersTableBody">
          <tr>
            <td colspan="6" class="sup-empty">
              <div class="sup-empty-icon"><i class="fas fa-spinner fa-spin"></i></div>
              <div>Loading suppliers…</div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="sup-pagination-wrap" id="paginationWrap" style="display:none">
      <div class="sup-pagination-info" id="paginationInfo">Showing 1-25 of 100 entries</div>
      <div class="sup-pagination" id="paginationControls"></div>
    </div>
  </div>
</div>

<!-- Supplier Details Modal -->
<div class="sup-overlay" id="supplierDetailsModal" onclick="if(event.target===this)closeSupplierDetailsModal()">
  <div class="sup-modal" role="dialog" aria-modal="true" aria-labelledby="modalSupplierName">
    <div class="sup-modal-header">
      <div class="sup-modal-title" id="modalSupplierName">Supplier Details</div>
      <button class="sup-modal-close" onclick="closeSupplierDetailsModal()" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>
    <div class="sup-modal-body" id="modalSupplierContent"></div>
  </div>
</div>

<!-- Add Supplier Modal -->
<div class="sup-overlay" id="addSupplierModal" onclick="if(event.target===this)closeAddSupplierModal()">
  <div class="sup-modal" role="dialog" aria-modal="true">
    <div class="sup-modal-header">
      <div class="sup-modal-title">Add New Supplier</div>
      <button class="sup-modal-close" onclick="closeAddSupplierModal()" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>
    <form id="addSupplierForm" onsubmit="submitAddSupplier(event)">
      <div class="sup-modal-body">
        <div class="sup-form-group half">
          <div>
            <label class="sup-form-label">Supplier Code</label>
            <input type="text" name="supplier_code" class="sup-form-input" placeholder="e.g., SUP-001">
          </div>
          <div>
            <label class="sup-form-label">Company Name <span class="req">*</span></label>
            <input type="text" name="company_name" required class="sup-form-input" placeholder="e.g., ABC Supplies">
          </div>
        </div>
        <div class="sup-form-group">
          <label class="sup-form-label">Contact Person</label>
          <input type="text" name="contact_person" class="sup-form-input" placeholder="Full name">
        </div>
        <div class="sup-form-group half">
          <div>
            <label class="sup-form-label">Email</label>
            <input type="email" name="email" class="sup-form-input" placeholder="email@example.com">
          </div>
          <div>
            <label class="sup-form-label">Phone (Primary)</label>
            <input type="text" name="phone_primary" class="sup-form-input" placeholder="+1 234 567 890">
          </div>
        </div>
        <div class="sup-form-group">
          <label class="sup-form-label">Phone (Secondary)</label>
          <input type="text" name="phone_secondary" class="sup-form-input" placeholder="+1 234 567 890">
        </div>
        <div class="sup-form-group">
          <label class="sup-form-label">Address Line 1</label>
          <input type="text" name="address_line1" class="sup-form-input" placeholder="Street address">
        </div>
        <div class="sup-form-group">
          <label class="sup-form-label">Address Line 2</label>
          <input type="text" name="address_line2" class="sup-form-input" placeholder="Apartment, suite, etc.">
        </div>
        <div class="sup-form-group half">
          <div>
            <label class="sup-form-label">City</label>
            <input type="text" name="city" class="sup-form-input" placeholder="City name">
          </div>
          <div>
            <label class="sup-form-label">Province</label>
            <input type="text" name="province" class="sup-form-input" placeholder="State/Province">
          </div>
        </div>
        <div class="sup-form-group half">
          <div>
            <label class="sup-form-label">TIN Number</label>
            <input type="text" name="tin_number" class="sup-form-input" placeholder="Tax ID">
          </div>
          <div>
            <label class="sup-form-label">Payment Terms</label>
            <input type="text" name="payment_terms" class="sup-form-input" placeholder="e.g., Net 30, COD">
          </div>
        </div>
        <div class="sup-form-group">
          <label class="sup-form-label">Notes</label>
          <textarea name="notes" rows="3" class="sup-form-textarea" placeholder="Additional notes..."></textarea>
        </div>
      </div>
      <div class="sup-modal-footer">
        <button type="button" class="sup-btn" onclick="closeAddSupplierModal()"><i class="fas fa-times"></i> Cancel</button>
        <button type="submit" class="sup-btn sup-btn-primary"><i class="fas fa-save"></i> Save Supplier</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Supplier Modal -->
<div class="sup-overlay" id="editSupplierModal" onclick="if(event.target===this)closeEditSupplierModal()">
  <div class="sup-modal" role="dialog" aria-modal="true">
    <div class="sup-modal-header">
      <div class="sup-modal-title">Edit Supplier</div>
      <button class="sup-modal-close" onclick="closeEditSupplierModal()" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>
    <form id="editSupplierForm" onsubmit="submitEditSupplier(event)">
      <div class="sup-modal-body">
        <input type="hidden" name="supplier_id" id="editSupplierId">
        <div class="sup-form-group half">
          <div>
            <label class="sup-form-label">Supplier Code</label>
            <input type="text" name="supplier_code" id="editSupplierCode" class="sup-form-input" placeholder="e.g., SUP-001">
          </div>
          <div>
            <label class="sup-form-label">Company Name <span class="req">*</span></label>
            <input type="text" name="company_name" id="editCompanyName" required class="sup-form-input" placeholder="e.g., ABC Supplies">
          </div>
        </div>
        <div class="sup-form-group">
          <label class="sup-form-label">Contact Person</label>
          <input type="text" name="contact_person" id="editContactPerson" class="sup-form-input" placeholder="Full name">
        </div>
        <div class="sup-form-group half">
          <div>
            <label class="sup-form-label">Email</label>
            <input type="email" name="email" id="editEmail" class="sup-form-input" placeholder="email@example.com">
          </div>
          <div>
            <label class="sup-form-label">Phone (Primary)</label>
            <input type="text" name="phone_primary" id="editPhonePrimary" class="sup-form-input" placeholder="+1 234 567 890">
          </div>
        </div>
        <div class="sup-form-group">
          <label class="sup-form-label">Phone (Secondary)</label>
          <input type="text" name="phone_secondary" id="editPhoneSecondary" class="sup-form-input" placeholder="+1 234 567 890">
        </div>
        <div class="sup-form-group">
          <label class="sup-form-label">Address Line 1</label>
          <input type="text" name="address_line1" id="editAddressLine1" class="sup-form-input" placeholder="Street address">
        </div>
        <div class="sup-form-group">
          <label class="sup-form-label">Address Line 2</label>
          <input type="text" name="address_line2" id="editAddressLine2" class="sup-form-input" placeholder="Apartment, suite, etc.">
        </div>
        <div class="sup-form-group half">
          <div>
            <label class="sup-form-label">City</label>
            <input type="text" name="city" id="editCity" class="sup-form-input" placeholder="City name">
          </div>
          <div>
            <label class="sup-form-label">Province</label>
            <input type="text" name="province" id="editProvince" class="sup-form-input" placeholder="State/Province">
          </div>
        </div>
        <div class="sup-form-group half">
          <div>
            <label class="sup-form-label">TIN Number</label>
            <input type="text" name="tin_number" id="editTinNumber" class="sup-form-input" placeholder="Tax ID">
          </div>
          <div>
            <label class="sup-form-label">Payment Terms</label>
            <input type="text" name="payment_terms" id="editPaymentTerms" class="sup-form-input" placeholder="e.g., Net 30, COD">
          </div>
        </div>
        <div class="sup-form-group">
          <label class="sup-form-label">Notes</label>
          <textarea name="notes" id="editNotes" rows="3" class="sup-form-textarea" placeholder="Additional notes..."></textarea>
        </div>
      </div>
      <div class="sup-modal-footer">
        <button type="button" class="sup-btn" onclick="closeEditSupplierModal()"><i class="fas fa-times"></i> Cancel</button>
        <button type="submit" class="sup-btn sup-btn-primary"><i class="fas fa-save"></i> Update Supplier</button>
      </div>
    </form>
  </div>
</div>

<script>
  let suppliersData = [];
  let filteredData = [];
  let currentPage = 1;
  let entriesPerPage = 25;

  document.addEventListener('DOMContentLoaded', function() {
    fetchSuppliers();
  });

  async function fetchSuppliers() {
    try {
      const res = await fetch('../api/suppliers.php?action=list');
      const result = await res.json();
      if (result.success) {
        suppliersData = result.data;
        filteredData = [...suppliersData];
        currentPage = 1;
        renderTable();
        renderStats();
      } else {
        showEmptyState(result.message);
      }
    } catch (e) {
      showEmptyState('Failed to load data');
    }
  }

  function showEmptyState(msg) {
    document.getElementById('suppliersTableBody').innerHTML = `
    <tr><td colspan="6">
      <div class="sup-empty">
        <div class="sup-empty-icon"><i class="fas fa-circle-xmark"></i></div>
        <div>${msg}</div>
      </div>
    </td></tr>`;
  }

  function renderStats() {
    const total = suppliersData.length;
    const contact = suppliersData.filter(s => s.contact_person).length;
    const tin = suppliersData.filter(s => s.tin_number).length;

    const pairs = [
      ['statTotal', total],
      ['statContact', contact],
      ['statTin', tin]
    ];

    pairs.forEach(([id, end]) => {
      const el = document.getElementById(id);
      if (!el) return;
      if (end === 0) {
        el.textContent = '0';
        return;
      }
      let start = 0,
        step = Math.max(1, Math.ceil(end / 20));
      const timer = setInterval(() => {
        start = Math.min(start + step, end);
        el.textContent = start;
        if (start >= end) clearInterval(timer);
      }, 25);
    });
  }

  function renderTable() {
    const tbody = document.getElementById('suppliersTableBody');
    const count = document.getElementById('tableCount');

    if (!filteredData.length) {
      count.textContent = '0 suppliers';
      document.getElementById('paginationWrap').style.display = 'none';
      tbody.innerHTML = `
      <tr><td colspan="6">
        <div class="sup-empty">
          <div class="sup-empty-icon"><i class="fas fa-building"></i></div>
          <div>No suppliers found</div>
          <div style="margin-top:8px"><a href="javascript:;" onclick="openAddSupplierModal()" style="color:var(--prod-blue);font-weight:500">Add your first supplier</a></div>
        </div>
      </td></tr>`;
      return;
    }

    const totalEntries = filteredData.length;
    const totalPages = Math.ceil(totalEntries / entriesPerPage);
    const start = (currentPage - 1) * entriesPerPage;
    const end = Math.min(start + entriesPerPage, totalEntries);
    const paginatedData = filteredData.slice(start, end);

    count.textContent = `${totalEntries} supplier${totalEntries !== 1 ? 's' : ''}`;

    const paginationWrap = document.getElementById('paginationWrap');
    if (totalEntries > 10) {
      paginationWrap.style.display = 'flex';
      renderPagination(totalPages, start, end, totalEntries);
    } else {
      paginationWrap.style.display = 'none';
    }

    tbody.innerHTML = paginatedData.map(s => `
      <tr class="supplier-row">
        <td>
          <div class="sup-name-cell">
            <div class="sup-avatar"><i class="fas fa-building"></i></div>
            <div>
              <div class="sup-name-primary">${s.company_name}</div>
              <div class="sup-name-secondary">${s.supplier_code || '—'}</div>
            </div>
          </div>
        </td>
        <td>
          <div class="sup-name-primary">${s.contact_person || '—'}</div>
          <div class="sup-name-secondary">${s.email || '—'}</div>
        </td>
        <td>
          <div class="sup-name-primary">${s.city || '—'}</div>
          <div class="sup-name-secondary">${s.province || '—'}</div>
        </td>
        <td>
          <span class="sup-name-primary">${s.payment_terms || '—'}</span>
        </td>
        <td style="text-align:center">
          <span class="sup-badge active">Active</span>
        </td>
        <td>
          <div class="sup-actions">
            <button onclick="viewSupplierDetails('${s.supplier_id}')" class="sup-action-btn view"><i class="fas fa-eye"></i> View</button>
            <button onclick="openEditSupplierModal('${s.supplier_id}')" class="sup-action-btn edit"><i class="fas fa-pen"></i> Edit</button>
            <button onclick="deleteSupplier('${s.supplier_id}', '${s.company_name.replace(/'/g, "\\'")}')" class="sup-action-btn danger"><i class="fas fa-trash-alt"></i></button>
          </div>
        </td>
      </tr>`).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toLowerCase();
    currentPage = 1;

    if (!filter) {
      filteredData = [...suppliersData];
    } else {
      filteredData = suppliersData.filter(s =>
        s.company_name.toLowerCase().includes(filter) ||
        (s.supplier_code && s.supplier_code.toLowerCase().includes(filter)) ||
        (s.contact_person && s.contact_person.toLowerCase().includes(filter)) ||
        (s.email && s.email.toLowerCase().includes(filter))
      );
    }

    renderTable();
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
    html += `<button class="sup-page-btn" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`;

    const maxButtons = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);
    if (endPage - startPage < maxButtons - 1) {
      startPage = Math.max(1, endPage - maxButtons + 1);
    }

    if (startPage > 1) {
      html += `<button class="sup-page-btn" onclick="goToPage(1)">1</button>`;
      if (startPage > 2) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
    }

    for (let i = startPage; i <= endPage; i++) {
      html += `<button class="sup-page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
    }

    if (endPage < totalPages) {
      if (endPage < totalPages - 1) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
      html += `<button class="sup-page-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
    }

    html += `<button class="sup-page-btn" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`;
    paginationControls.innerHTML = html;
  }

  function viewSupplierDetails(supplierId) {
    const supplier = suppliersData.find(s => s.supplier_id === supplierId);
    if (!supplier) return;

    document.getElementById('modalSupplierName').textContent = supplier.company_name;

    const address = [supplier.address_line1, supplier.address_line2, supplier.city, supplier.province].filter(Boolean).join(', ');

    document.getElementById('modalSupplierContent').innerHTML = `
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:28px;">
      <div>
        <div style="font-size:11px;font-weight:700;color:var(--prod-blue);text-transform:uppercase;letter-spacing:.1em;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid var(--prod-blue-pale);"><i class="fas fa-info-circle" style="margin-right:5px;opacity:.6"></i>Basic Information</div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Supplier Code</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${supplier.supplier_code || '—'}</div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Company Name</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${supplier.company_name}</div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Contact Person</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${supplier.contact_person || '—'}</div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Email</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${supplier.email || '—'}</div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Phone (Primary)</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${supplier.phone_primary || '—'}</div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Phone (Secondary)</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${supplier.phone_secondary || '—'}</div></div>
      </div>
      <div>
        <div style="font-size:11px;font-weight:700;color:var(--prod-blue);text-transform:uppercase;letter-spacing:.1em;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid var(--prod-blue-pale);margin-top:20px;"><i class="fas fa-map-marker-alt" style="margin-right:5px;opacity:.6"></i>Address</div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Address</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${address || '—'}</div></div>
        <div style="font-size:11px;font-weight:700;color:var(--prod-blue);text-transform:uppercase;letter-spacing:.1em;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid var(--prod-blue-pale);margin-top:20px;"><i class="fas fa-briefcase" style="margin-right:5px;opacity:.6"></i>Business Details</div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">TIN Number</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${supplier.tin_number || '—'}</div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Payment Terms</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${supplier.payment_terms || '—'}</div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Notes</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${supplier.notes || '—'}</div></div>
        <div style="font-size:11px;font-weight:700;color:var(--prod-blue);text-transform:uppercase;letter-spacing:.1em;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid var(--prod-blue-pale);margin-top:20px;"><i class="fas fa-sliders" style="margin-right:5px;opacity:.6"></i>Settings</div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Status</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;"><span class="sup-badge active">${supplier.is_active ? 'Active' : 'Inactive'}</span></div></div>
      </div>
    </div>`;

    document.getElementById('supplierDetailsModal').classList.add('open');
  }

  function closeSupplierDetailsModal() {
    document.getElementById('supplierDetailsModal').classList.remove('open');
  }

  function openAddSupplierModal() {
    document.getElementById('addSupplierModal').classList.add('open');
  }

  function closeAddSupplierModal() {
    document.getElementById('addSupplierModal').classList.remove('open');
    document.getElementById('addSupplierForm').reset();
  }

  function submitAddSupplier(event) {
    event.preventDefault();
    const form = document.getElementById('addSupplierForm');
    const formData = new FormData(form);

    Swal.fire({
      title: 'Adding...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

    const payload = {
      supplier_code: formData.get('supplier_code'),
      company_name: formData.get('company_name'),
      contact_person: formData.get('contact_person'),
      email: formData.get('email'),
      phone_primary: formData.get('phone_primary'),
      phone_secondary: formData.get('phone_secondary'),
      address_line1: formData.get('address_line1'),
      address_line2: formData.get('address_line2'),
      city: formData.get('city'),
      province: formData.get('province'),
      tin_number: formData.get('tin_number'),
      payment_terms: formData.get('payment_terms'),
      notes: formData.get('notes')
    };
    fetch('../api/suppliers.php?action=create', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddSupplierModal();
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Added!',
            text: data.message,
            confirmButtonColor: '#1A6BBF'
          }).then(() => fetchSuppliers());
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message,
            confirmButtonColor: '#C62828'
          });
        }
      })
      .catch(() => {
        closeAddSupplierModal();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred.',
          confirmButtonColor: '#C62828'
        });
      });
  }

  function openEditSupplierModal(supplierId) {
    const supplier = suppliersData.find(s => s.supplier_id === supplierId);
    if (!supplier) return;

    document.getElementById('editSupplierId').value = supplier.supplier_id;
    document.getElementById('editSupplierCode').value = supplier.supplier_code || '';
    document.getElementById('editCompanyName').value = supplier.company_name;
    document.getElementById('editContactPerson').value = supplier.contact_person || '';
    document.getElementById('editEmail').value = supplier.email || '';
    document.getElementById('editPhonePrimary').value = supplier.phone_primary || '';
    document.getElementById('editPhoneSecondary').value = supplier.phone_secondary || '';
    document.getElementById('editAddressLine1').value = supplier.address_line1 || '';
    document.getElementById('editAddressLine2').value = supplier.address_line2 || '';
    document.getElementById('editCity').value = supplier.city || '';
    document.getElementById('editProvince').value = supplier.province || '';
    document.getElementById('editTinNumber').value = supplier.tin_number || '';
    document.getElementById('editPaymentTerms').value = supplier.payment_terms || '';
    document.getElementById('editNotes').value = supplier.notes || '';

    document.getElementById('editSupplierModal').classList.add('open');
  }

  function closeEditSupplierModal() {
    document.getElementById('editSupplierModal').classList.remove('open');
    document.getElementById('editSupplierForm').reset();
  }

  function submitEditSupplier(event) {
    event.preventDefault();
    const form = document.getElementById('editSupplierForm');
    const formData = new FormData(form);

    Swal.fire({
      title: 'Updating...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

    const payload = {
      supplier_id: formData.get('supplier_id'),
      supplier_code: formData.get('supplier_code'),
      company_name: formData.get('company_name'),
      contact_person: formData.get('contact_person'),
      email: formData.get('email'),
      phone_primary: formData.get('phone_primary'),
      phone_secondary: formData.get('phone_secondary'),
      address_line1: formData.get('address_line1'),
      address_line2: formData.get('address_line2'),
      city: formData.get('city'),
      province: formData.get('province'),
      tin_number: formData.get('tin_number'),
      payment_terms: formData.get('payment_terms'),
      notes: formData.get('notes')
    };
    fetch('../api/suppliers.php?action=update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditSupplierModal();
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: data.message,
            confirmButtonColor: '#1A6BBF'
          }).then(() => fetchSuppliers());
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message,
            confirmButtonColor: '#C62828'
          });
        }
      })
      .catch(() => {
        closeEditSupplierModal();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred.',
          confirmButtonColor: '#C62828'
        });
      });
  }

  function deleteSupplier(supplierId, companyName) {
    Swal.fire({
      title: 'Delete supplier?',
      html: `<span style="font-size:14px">You're about to delete <strong>${companyName}</strong>. This cannot be undone.</span>`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#C62828',
      cancelButtonColor: '#64748B',
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel',
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Deleting...',
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading(),
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });
        fetch('../api/suppliers.php?action=delete', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              supplier_id: supplierId
            })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: data.message,
                confirmButtonColor: '#1A6BBF'
              }).then(() => fetchSuppliers());
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonColor: '#C62828'
              });
            }
          })
          .catch(() => Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred.',
            confirmButtonColor: '#C62828'
          }));
      }
    });
  }
</script>