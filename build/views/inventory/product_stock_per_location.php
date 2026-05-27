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

  .stk-page {
    padding: 28px 28px 40px;
    max-width: 100%;
    margin: 0 auto;
    background: var(--color-background-tertiary, #F4F5F7);
    min-height: 100%;
    border-radius: var(--border-radius-xl, 18px);
    font-family: var(--prod-font);
  }

  .stk-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
    gap: 16px;
    flex-wrap: wrap;
  }

  .stk-page-eyebrow {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--prod-blue);
    margin-bottom: 4px;
  }

  .stk-page-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.4px;
    line-height: 1.2;
  }

  .stk-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
  }

  .stk-stat-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    padding: 20px 22px;
    box-shadow: var(--prod-shadow-sm);
    position: relative;
    overflow: hidden;
  }

  .stk-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--prod-radius-lg) var(--prod-radius-lg) 0 0;
    background: var(--_accent, #CBD5E1);
  }

  .stk-stat-card.blue {
    --_accent: var(--prod-blue);
  }

  .stk-stat-card.red {
    --_accent: var(--prod-red);
  }

  .stk-stat-card.orange {
    --_accent: var(--prod-amber);
  }

  .stk-stat-icon {
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

  .stk-stat-card.red .stk-stat-icon {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .stk-stat-card.orange .stk-stat-icon {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
  }

  .stk-stat-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-secondary);
    margin-bottom: 6px;
  }

  .stk-stat-value {
    font-size: 32px;
    font-weight: 600;
    color: var(--color-text-primary);
    font-family: var(--prod-mono);
  }

  .stk-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    box-shadow: var(--prod-shadow-sm);
    overflow: hidden;
  }

  .stk-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }

  .stk-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .stk-search-wrap {
    position: relative;
  }

  .stk-search-wrap i {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-text-secondary);
    font-size: 12px;
    pointer-events: none;
  }

  .stk-search {
    padding: 8px 14px 8px 34px;
    font-size: 13px;
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--prod-radius-md);
    background: var(--color-background-secondary, #F8FAFC);
    color: var(--color-text-primary);
    outline: none;
    width: 230px;
  }

  .stk-search:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
  }

  .stk-btn {
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

  .stk-btn:hover {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .stk-btn-primary {
    background: var(--prod-blue);
    color: #fff;
    border-color: var(--prod-blue);
    box-shadow: 0 1px 3px rgba(26, 107, 191, .3);
  }

  .stk-btn-primary:hover {
    background: var(--prod-blue-dark);
  }

  .stk-table-wrap {
    overflow-x: auto;
  }

  .stk-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }

  .stk-table thead tr {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .stk-table th {
    padding: 11px 18px;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .08em;
    border-bottom: 1px solid rgba(0, 0, 0, .07);
  }

  .stk-table td {
    padding: 13px 18px;
    vertical-align: middle;
  }

  .stk-name-cell {
    display: flex;
    align-items: center;
  }

  .stk-avatar {
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

  .stk-name-primary {
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-primary);
  }

  .stk-name-secondary {
    font-size: 11px;
    color: var(--color-text-secondary);
  }

  .stk-badge {
    display: inline-flex;
    padding: 4px 10px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 600;
  }

  .stk-badge.instock {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .stk-badge.low {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .stk-badge.zero {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
  }

  .stk-actions {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
  }

  .stk-action-btn {
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

  .stk-action-btn.view:hover {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
  }

  .stk-action-btn.edit:hover {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
  }

  .stk-action-btn.danger:hover {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .stk-empty {
    padding: 64px;
    text-align: center;
    color: var(--color-text-secondary);
  }

  .stk-empty-icon {
    font-size: 32px;
    opacity: .25;
    margin-bottom: 12px;
  }

  .stk-pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    background: var(--color-background-primary, #fff);
    gap: 16px;
    flex-wrap: wrap;
  }

  .stk-pagination-info {
    font-size: 12px;
    color: var(--color-text-secondary);
  }

  .stk-pagination {
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .stk-page-btn {
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

  .stk-page-btn:hover:not(:disabled) {
    background: var(--prod-blue-pale);
    border-color: var(--prod-blue);
    color: var(--prod-blue);
  }

  .stk-page-btn.active {
    background: var(--prod-blue);
    border-color: var(--prod-blue);
    color: #fff;
  }

  .stk-page-btn:disabled {
    opacity: .4;
    cursor: not-allowed;
  }

  .stk-entries-select {
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

  .stk-entries-select:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    outline: none;
  }

  .stk-entries-label {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-right: 8px;
  }

  .stk-entries-wrap {
    display: flex;
    align-items: center;
  }

  .stk-overlay {
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

  .stk-overlay.open {
    display: flex;
  }

  .stk-modal {
    background: var(--color-background-primary, #fff);
    border-radius: var(--prod-radius-xl);
    border: 1px solid rgba(0, 0, 0, .08);
    width: 100%;
    max-width: 700px;
    max-height: 92vh;
    overflow-y: auto;
    box-shadow: var(--prod-shadow-lg);
  }

  .stk-modal-header {
    padding: 22px 26px 18px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  .stk-modal-title {
    font-size: 17px;
    font-weight: 600;
    color: var(--color-text-primary);
  }

  .stk-modal-close {
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

  .stk-modal-close:hover {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .stk-modal-body {
    padding: 26px;
  }

  .stk-modal-footer {
    padding: 18px 26px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    justify-content: flex-end;
    gap: 8px;
  }

  .stk-form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 18px;
  }

  .stk-form-group.half {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }

  .stk-form-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .07em;
  }

  .stk-form-label .req {
    color: var(--prod-red);
    margin-left: 2px;
  }

  .stk-form-input,
  .stk-form-select,
  .stk-form-textarea {
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

  .stk-form-input:focus,
  .stk-form-select:focus,
  .stk-form-textarea:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    background: var(--color-background-primary, #fff);
  }

  @media (max-width: 600px) {
    .stk-search {
      width: 160px;
    }

    .stk-form-group.half {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="stk-page">
  <div class="stk-page-header">
    <div>
      <div class="stk-page-eyebrow"><i class="fas fa-boxes-stacked" style="margin-right:5px"></i>Inventory</div>
      <div class="stk-page-title">Product Stock per Location</div>
    </div>
  </div>

  <div class="stk-stats-grid">
    <div class="stk-stat-card blue">
      <div class="stk-stat-icon"><i class="fas fa-warehouse"></i></div>
      <div class="stk-stat-label">Total Stock Records</div>
      <div class="stk-stat-value" id="statTotal">—</div>
    </div>
    <div class="stk-stat-card red">
      <div class="stk-stat-icon"><i class="fas fa-triangle-exclamation"></i></div>
      <div class="stk-stat-label">Below Reorder Point</div>
      <div class="stk-stat-value" id="statBelowReorder">—</div>
    </div>
    <div class="stk-stat-card orange">
      <div class="stk-stat-icon"><i class="fas fa-circle-xmark"></i></div>
      <div class="stk-stat-label">Zero Stock</div>
      <div class="stk-stat-value" id="statZero">—</div>
    </div>
  </div>

  <div class="stk-card">
    <div class="stk-card-header">
      <div>
        <strong>Stock by Location</strong>
        <span id="tableCount" style="font-size:12px;color:var(--color-text-secondary);margin-left:8px">Loading…</span>
      </div>
      <div class="stk-toolbar">
        <div class="stk-entries-wrap">
          <span class="stk-entries-label">Show</span>
          <select class="stk-entries-select" id="entriesPerPage" onchange="changeEntriesPerPage()">
            <option value="10">10</option>
            <option value="25" selected>25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="stk-entries-label">entries</span>
        </div>
        <div class="stk-search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" id="searchInput" class="stk-search" placeholder="Search stock…" oninput="filterTable()">
        </div>
        <button onclick="openAddStockModal()" class="stk-btn stk-btn-primary">
          <i class="fas fa-plus"></i> Adjust Stock
        </button>
      </div>
    </div>
    <div class="stk-table-wrap">
      <table class="stk-table" id="stockTable">
        <thead>
          <tr>
            <th>Product</th>
            <th>Property</th>
            <th>Location</th>
            <th style="text-align:right">Quantity</th>
            <th style="text-align:center">Status</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="stockTableBody">
          <tr>
            <td colspan="6" class="stk-empty">
              <div class="stk-empty-icon"><i class="fas fa-spinner fa-spin"></i></div>
              <div>Loading stock records…</div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="stk-pagination-wrap" id="paginationWrap" style="display:none">
      <div class="stk-pagination-info" id="paginationInfo">Showing 1-25 of 100 entries</div>
      <div class="stk-pagination" id="paginationControls"></div>
    </div>
  </div>
</div>

<!-- View Stock Details Modal -->
<div class="stk-overlay" id="stockDetailsModal" onclick="if(event.target===this)closeStockDetailsModal()">
  <div class="stk-modal" role="dialog" aria-modal="true" aria-labelledby="stockModalTitle">
    <div class="stk-modal-header">
      <div class="stk-modal-title" id="stockModalTitle">Stock Details</div>
      <button class="stk-modal-close" onclick="closeStockDetailsModal()" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>
    <div class="stk-modal-body" id="stockModalContent"></div>
  </div>
</div>

<!-- Add Stock Modal -->
<div class="stk-overlay" id="addStockModal" onclick="if(event.target===this)closeAddStockModal()">
  <div class="stk-modal" role="dialog" aria-modal="true">
    <div class="stk-modal-header">
      <div class="stk-modal-title">Add Stock Adjustment</div>
      <button class="stk-modal-close" onclick="closeAddStockModal()" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>
    <form id="addStockForm" onsubmit="submitAddStock(event)">
      <div class="stk-modal-body">
        <div class="stk-form-group">
          <label class="stk-form-label">Product <span class="req">*</span></label>
          <select name="product_id" id="addProductSelect" required class="stk-form-select">
            <option value="">Select Product</option>
          </select>
        </div>
        <div class="stk-form-group">
          <label class="stk-form-label">Location <span class="req">*</span></label>
          <select name="location_id" id="addLocationSelect" required class="stk-form-select">
            <option value="">Select Location</option>
          </select>
        </div>
        <div class="stk-form-group">
          <label class="stk-form-label">Adjustment Type <span class="req">*</span></label>
          <select name="adjustment_type" required class="stk-form-select">
            <option value="add">Add Stock (+)</option>
            <option value="deduct">Deduct Stock (-)</option>
          </select>
        </div>
        <div class="stk-form-group">
          <label class="stk-form-label">Quantity <span class="req">*</span></label>
          <input type="number" name="quantity" step="0.001" min="0" required class="stk-form-input" placeholder="0.000">
        </div>
        <div class="stk-form-group">
          <label class="stk-form-label">Notes</label>
          <textarea name="notes" rows="2" class="stk-form-textarea" placeholder="Reason for adjustment..."></textarea>
        </div>
      </div>
      <div class="stk-modal-footer">
        <button type="button" class="stk-btn" onclick="closeAddStockModal()"><i class="fas fa-times"></i> Cancel</button>
        <button type="submit" class="stk-btn stk-btn-primary"><i class="fas fa-save"></i> Save Adjustment</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit/Adjust Stock Modal -->
<div class="stk-overlay" id="editStockModal" onclick="if(event.target===this)closeEditStockModal()">
  <div class="stk-modal" role="dialog" aria-modal="true">
    <div class="stk-modal-header">
      <div class="stk-modal-title">Edit Stock</div>
      <button class="stk-modal-close" onclick="closeEditStockModal()" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>
    <form id="editStockForm" onsubmit="submitEditStock(event)">
      <div class="stk-modal-body">
        <input type="hidden" name="stock_loc_id" id="editStockLocId">
        <div class="stk-form-group">
          <label class="stk-form-label">Product</label>
          <input type="text" id="editProductName" readonly class="stk-form-input" style="background:var(--color-background-secondary)">
        </div>
        <div class="stk-form-group">
          <label class="stk-form-label">Location</label>
          <input type="text" id="editLocationName" readonly class="stk-form-input" style="background:var(--color-background-secondary)">
        </div>
        <div class="stk-form-group">
          <label class="stk-form-label">Current Quantity</label>
          <input type="text" id="editCurrentQuantity" readonly class="stk-form-input" style="background:var(--color-background-secondary)">
        </div>
        <div class="stk-form-group">
          <label class="stk-form-label">Adjustment Type <span class="req">*</span></label>
          <select name="adjustment_type" required class="stk-form-select">
            <option value="add">Add Stock (+)</option>
            <option value="deduct">Deduct Stock (-)</option>
          </select>
        </div>
        <div class="stk-form-group">
          <label class="stk-form-label">Quantity <span class="req">*</span></label>
          <input type="number" name="quantity" step="0.001" min="0" required class="stk-form-input" placeholder="0.000">
        </div>
        <div class="stk-form-group">
          <label class="stk-form-label">Notes</label>
          <textarea name="notes" rows="2" class="stk-form-textarea" placeholder="Reason for adjustment..."></textarea>
        </div>
      </div>
      <div class="stk-modal-footer">
        <button type="button" class="stk-btn" onclick="closeEditStockModal()"><i class="fas fa-times"></i> Cancel</button>
        <button type="submit" class="stk-btn stk-btn-primary"><i class="fas fa-save"></i> Save Adjustment</button>
      </div>
    </form>
  </div>
</div>

<script>
  let stockData = [];
  let filteredData = [];
  let currentPage = 1;
  let entriesPerPage = 25;
  let productsData = [];
  let locationsData = [];

  document.addEventListener('DOMContentLoaded', () => {
    fetchStock();
    fetchRelated();
  });

  async function fetchStock() {
    try {
      const res = await fetch('../api/product_stock_per_location.php?action=list');
      const json = await res.json();
      if (json.success) {
        stockData = json.data;
        filteredData = [...stockData];
        currentPage = 1;
        renderStats();
        renderTable();
      }
    } catch (e) {
      console.error('Error fetching stock:', e);
      document.getElementById('stockTableBody').innerHTML = `<tr><td colspan="6" class="stk-empty"><div class="stk-empty-icon"><i class="fas fa-circle-xmark"></i></div><div>Failed to load data.</div></td></tr>`;
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/product_stock_per_location.php?action=related');
      const json = await res.json();
      if (json.success) {
        productsData = json.data.products;
        locationsData = json.data.locations;
        populateDropdowns();
      }
    } catch (e) {
      console.error('Error fetching related:', e);
    }
  }

  function renderStats() {
    const total = stockData.length;
    const belowReorder = stockData.filter(s => parseFloat(s.quantity) < parseFloat(s.reorder_point) && parseFloat(s.reorder_point) > 0).length;
    const zeroStock = stockData.filter(s => parseFloat(s.quantity) == 0).length;

    const pairs = [
      ['statTotal', total],
      ['statBelowReorder', belowReorder],
      ['statZero', zeroStock]
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
    const tbody = document.getElementById('stockTableBody');
    const count = document.getElementById('tableCount');

    if (!filteredData.length) {
      count.textContent = '0 records';
      document.getElementById('paginationWrap').style.display = 'none';
      tbody.innerHTML = `<tr><td colspan="6" class="stk-empty"><div class="stk-empty-icon"><i class="fas fa-boxes-stacked"></i></div><div>No stock records found.</div></td></tr>`;
      return;
    }

    const totalEntries = filteredData.length;
    const totalPages = Math.ceil(totalEntries / entriesPerPage);
    const start = (currentPage - 1) * entriesPerPage;
    const end = Math.min(start + entriesPerPage, totalEntries);
    const paginatedData = filteredData.slice(start, end);

    count.textContent = `${totalEntries} record${totalEntries !== 1 ? 's' : ''}`;

    const paginationWrap = document.getElementById('paginationWrap');
    if (totalEntries > 10) {
      paginationWrap.style.display = 'flex';
      renderPagination(totalPages, start, end, totalEntries);
    } else {
      paginationWrap.style.display = 'none';
    }

    tbody.innerHTML = paginatedData.map(stock => {
      const qty = parseFloat(stock.quantity);
      const reorder = parseFloat(stock.reorder_point);
      const locType = (stock.location_type || '').replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
      let statusClass, statusText;
      if (qty == 0) {
        statusClass = 'zero';
        statusText = 'Zero';
      } else if (qty < reorder && reorder > 0) {
        statusClass = 'low';
        statusText = 'Low Stock';
      } else {
        statusClass = 'instock';
        statusText = 'In Stock';
      }
      return `
      <tr class="stock-row">
        <td>
          <div class="stk-name-cell">
            <div class="stk-avatar"><i class="fas fa-box"></i></div>
            <div>
              <div class="stk-name-primary">${stock.product_name}</div>
              <div class="stk-name-secondary">${stock.product_code}</div>
            </div>
          </div>
        </td>
        <td>
          <span class="stk-name-primary">${stock.property_name}</span>
        </td>
        <td>
          <div class="stk-name-primary">${stock.location_name}</div>
          <div class="stk-name-secondary">${locType}</div>
        </td>
        <td style="text-align:right">
          <span class="stk-name-primary" style="font-family:var(--prod-mono)">${qty.toFixed(3)}</span>
        </td>
        <td style="text-align:center">
          <span class="stk-badge ${statusClass}">${statusText}</span>
        </td>
        <td>
          <div class="stk-actions">
            <button onclick="viewStockDetails('${stock.stock_loc_id}')" class="stk-action-btn view"><i class="fas fa-eye"></i> View</button>
            <button onclick="openEditStockModal('${stock.stock_loc_id}')" class="stk-action-btn edit"><i class="fas fa-pen"></i> Adjust</button>
            <button onclick="deleteStock(this)" data-id="${stock.stock_loc_id}" data-product="${stock.product_name}" data-location="${stock.location_name}" class="stk-action-btn danger"><i class="fas fa-trash-alt"></i></button>
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
    html += `<button class="stk-page-btn" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`;

    const maxButtons = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);
    if (endPage - startPage < maxButtons - 1) {
      startPage = Math.max(1, endPage - maxButtons + 1);
    }

    if (startPage > 1) {
      html += `<button class="stk-page-btn" onclick="goToPage(1)">1</button>`;
      if (startPage > 2) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
    }

    for (let i = startPage; i <= endPage; i++) {
      html += `<button class="stk-page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
    }

    if (endPage < totalPages) {
      if (endPage < totalPages - 1) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
      html += `<button class="stk-page-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
    }

    html += `<button class="stk-page-btn" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`;
    paginationControls.innerHTML = html;
  }

  function populateDropdowns() {
    document.getElementById('addProductSelect').innerHTML = '<option value="">Select Product</option>' +
      productsData.map(p => `<option value="${p.product_id}">${p.product_name} (${p.product_code})</option>`).join('');
    document.getElementById('addLocationSelect').innerHTML = '<option value="">Select Location</option>' +
      locationsData.map(l => `<option value="${l.location_id}">${l.property_name} - ${l.location_name}</option>`).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toLowerCase();
    currentPage = 1;

    if (!filter) {
      filteredData = [...stockData];
    } else {
      filteredData = stockData.filter(s =>
        s.product_name.toLowerCase().includes(filter) ||
        s.product_code.toLowerCase().includes(filter) ||
        s.location_name.toLowerCase().includes(filter) ||
        s.property_name.toLowerCase().includes(filter)
      );
    }

    renderTable();
  }

  function viewStockDetails(stockLocId) {
    const stock = stockData.find(s => s.stock_loc_id == stockLocId);
    if (!stock) return;

    document.getElementById('stockModalTitle').textContent = `${stock.product_name} - ${stock.location_name}`;

    const qty = parseFloat(stock.quantity);
    const reorder = parseFloat(stock.reorder_point);
    let statusBadge;
    if (qty == 0) {
      statusBadge = `<span class="bg-gradient-to-tl from-orange-500 to-amber-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Zero</span>`;
    } else if (qty < reorder && reorder > 0) {
      statusBadge = `<span class="bg-gradient-to-tl from-red-500 to-rose-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Low Stock</span>`;
    } else {
      statusBadge = `<span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">In Stock</span>`;
    }

    document.getElementById('stockModalContent').innerHTML = `
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:28px;">
      <div>
        <div style="font-size:11px;font-weight:700;color:var(--prod-blue);text-transform:uppercase;letter-spacing:.1em;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid var(--prod-blue-pale);"><i class="fas fa-box" style="margin-right:5px;opacity:.6"></i>Product Information</div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Product Name</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${stock.product_name}</div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Product Code</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${stock.product_code}</div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Total Stock</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;font-family:var(--prod-mono)">${parseFloat(stock.total_stock).toFixed(3)}</div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Reorder Point</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;font-family:var(--prod-mono)">${parseFloat(stock.reorder_point).toFixed(3)}</div></div>
      </div>
      <div>
        <div style="font-size:11px;font-weight:700;color:var(--prod-blue);text-transform:uppercase;letter-spacing:.1em;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid var(--prod-blue-pale);margin-top:20px;"><i class="fas fa-map-marker-alt" style="margin-right:5px;opacity:.6"></i>Location Information</div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Property</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${stock.property_name}</div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Location</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${stock.location_name}</div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Location Type</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${(stock.location_type || '').replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())}</div></div>
        <div style="font-size:11px;font-weight:700;color:var(--prod-blue);text-transform:uppercase;letter-spacing:.1em;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid var(--prod-blue-pale);margin-top:20px;"><i class="fas fa-warehouse" style="margin-right:5px;opacity:.6"></i>Stock</div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Current Quantity</div><div style="font-size:20px;color:var(--color-text-primary);font-weight:600;text-align:right;font-family:var(--prod-mono)">${qty.toFixed(3)}</div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Status</div><div style="text-align:right;">${statusBadge}</div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);font-weight:500;">Last Updated</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${stock.updated_at ? new Date(stock.updated_at).toLocaleString() : '—'}</div></div>
      </div>
    </div>`;

    document.getElementById('stockDetailsModal').classList.add('open');
  }

  function closeStockDetailsModal() {
    document.getElementById('stockDetailsModal').classList.remove('open');
  }

  function openAddStockModal() {
    document.getElementById('addStockModal').classList.add('open');
  }

  function closeAddStockModal() {
    document.getElementById('addStockModal').classList.remove('open');
    document.getElementById('addStockForm').reset();
  }

  function submitAddStock(event) {
    event.preventDefault();
    Swal.fire({
      title: 'Processing...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

    const form = document.getElementById('addStockForm');
    const payload = {
      product_id: form.elements['product_id'].value,
      location_id: form.elements['location_id'].value,
      adjustment_type: form.elements['adjustment_type'].value,
      quantity: form.elements['quantity'].value,
      notes: form.elements['notes'].value
    };

    fetch('../api/product_stock_per_location.php?action=create', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddStockModal();
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Added!',
            text: data.message,
            confirmButtonColor: '#1A6BBF'
          }).then(() => fetchStock());
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
        closeAddStockModal();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred.',
          confirmButtonColor: '#C62828'
        });
      });
  }

  function openEditStockModal(stockLocId) {
    const stock = stockData.find(s => s.stock_loc_id == stockLocId);
    if (!stock) return;
    document.getElementById('editStockLocId').value = stock.stock_loc_id;
    document.getElementById('editProductName').value = `${stock.product_name} (${stock.product_code})`;
    document.getElementById('editLocationName').value = `${stock.property_name} - ${stock.location_name}`;
    document.getElementById('editCurrentQuantity').value = parseFloat(stock.quantity).toFixed(3);
    document.getElementById('editStockModal').classList.add('open');
  }

  function closeEditStockModal() {
    document.getElementById('editStockModal').classList.remove('open');
    document.getElementById('editStockForm').reset();
  }

  function submitEditStock(event) {
    event.preventDefault();
    Swal.fire({
      title: 'Processing...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

    const form = document.getElementById('editStockForm');
    const payload = {
      stock_loc_id: form.elements['stock_loc_id'].value,
      adjustment_type: form.elements['adjustment_type'].value,
      quantity: form.elements['quantity'].value,
      notes: form.elements['notes'].value
    };

    fetch('../api/product_stock_per_location.php?action=adjust', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditStockModal();
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: data.message,
            confirmButtonColor: '#1A6BBF'
          }).then(() => fetchStock());
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
        closeEditStockModal();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred.',
          confirmButtonColor: '#C62828'
        });
      });
  }

  function deleteStock(el) {
    const stockLocId = el.dataset.id;
    const productName = el.dataset.product;
    const locationName = el.dataset.location;
    Swal.fire({
      title: 'Delete stock record?',
      html: `<span style="font-size:14px">You're about to delete stock for <strong>${productName}</strong> at <strong>${locationName}</strong>. This cannot be undone.</span>`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#C62828',
      cancelButtonColor: '#64748B',
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel',
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    }).then(result => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Deleting...',
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading(),
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });
        fetch('../api/product_stock_per_location.php?action=delete', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              stock_loc_id: stockLocId
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
              }).then(() => fetchStock());
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