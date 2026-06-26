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

  .loc-page {
    padding: 28px 28px 40px;
    max-width: 100%;
    margin: 0 auto;
    background: var(--color-background-tertiary, #F4F5F7);
    min-height: 100%;
    border-radius: var(--border-radius-xl, 18px);
    font-family: var(--prod-font);
  }

  .loc-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
    gap: 16px;
    flex-wrap: wrap;
  }

  .loc-page-eyebrow {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--prod-blue);
    margin-bottom: 4px;
  }

  .loc-page-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.4px;
    line-height: 1.2;
  }

  .loc-page-subtitle {
    font-size: 14px;
    color: var(--color-text-secondary);
    margin-top: 3px;
    font-weight: 400;
  }

  .loc-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
  }

  .loc-stat-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    padding: 20px 22px;
    box-shadow: var(--prod-shadow-sm);
    position: relative;
    overflow: hidden;
    transition: box-shadow .2s, transform .2s;
  }

  .loc-stat-card:hover {
    box-shadow: var(--prod-shadow-md);
    transform: translateY(-1px);
  }

  .loc-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--prod-radius-lg) var(--prod-radius-lg) 0 0;
    background: var(--_accent, #CBD5E1);
  }

  .loc-stat-card.blue {
    --_accent: var(--prod-blue);
  }

  .loc-stat-card.purple {
    --_accent: var(--prod-purple);
  }

  .loc-stat-card.amber {
    --_accent: var(--prod-amber);
  }

  .loc-stat-card.cyan {
    --_accent: var(--prod-cyan);
  }

  .loc-stat-icon {
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

  .loc-stat-card.purple .loc-stat-icon {
    background: var(--prod-purple-pale);
    color: var(--prod-purple);
  }

  .loc-stat-card.amber .loc-stat-icon {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
  }

  .loc-stat-card.cyan .loc-stat-icon {
    background: var(--prod-cyan-pale);
    color: var(--prod-cyan);
  }

  .loc-stat-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-secondary);
    letter-spacing: .02em;
    margin-bottom: 6px;
  }

  .loc-stat-value {
    font-size: 32px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.5px;
    line-height: 1;
    font-family: var(--prod-mono);
  }

  .loc-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    box-shadow: var(--prod-shadow-sm);
    overflow: hidden;
  }

  .loc-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }

  .loc-card-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.2px;
  }

  .loc-card-count {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-top: 1px;
  }

  .loc-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .loc-search-wrap {
    position: relative;
  }

  .loc-search-wrap i {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-text-secondary);
    font-size: 12px;
    pointer-events: none;
  }

  .loc-search {
    padding: 8px 14px 8px 34px;
    font-size: 13px;
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--prod-radius-md);
    background: var(--color-background-secondary, #F8FAFC);
    color: var(--color-text-primary);
    outline: none;
    width: 230px;
    font-family: var(--prod-font);
  }

  .loc-search:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    background: var(--color-background-primary, #fff);
  }

  .loc-btn {
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
    font-family: var(--prod-font);
  }

  .loc-btn:hover {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .loc-btn-primary {
    background: var(--prod-blue);
    color: #fff;
    border-color: var(--prod-blue);
    box-shadow: 0 1px 3px rgba(26, 107, 191, .3);
  }

  .loc-btn-primary:hover {
    background: var(--prod-blue-dark);
    border-color: var(--prod-blue-dark);
  }

  .loc-table-wrap {
    overflow-x: auto;
  }

  .loc-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    font-family: var(--prod-font);
  }

  .loc-table thead tr {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .loc-table thead th {
    padding: 11px 18px;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .08em;
    border-bottom: 1px solid rgba(0, 0, 0, .07);
    white-space: nowrap;
  }

  .loc-table thead th.center {
    text-align: center;
  }

  .loc-table thead th.right {
    text-align: right;
  }

  .loc-table tbody tr {
    border-bottom: 1px solid rgba(0, 0, 0, .05);
    transition: background .12s;
  }

  .loc-table tbody tr:hover {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .loc-table td {
    padding: 13px 18px;
    vertical-align: middle;
    white-space: nowrap;
  }

  .loc-name-cell {
    display: flex;
    align-items: center;
  }

  .loc-avatar {
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
    flex-shrink: 0;
    margin-right: 10px;
  }

  .loc-name-primary {
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-primary);
    letter-spacing: -.1px;
  }

  .loc-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .02em;
    text-transform: capitalize;
  }

  .loc-badge.warehouse {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
  }

  .loc-badge.kitchen {
    background: var(--prod-purple-pale);
    color: var(--prod-purple);
  }

  .loc-badge.bar {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
  }

  .loc-badge.housekeeping {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .loc-badge.front_desk {
    background: var(--prod-cyan-pale);
    color: var(--prod-cyan);
  }

  .loc-badge.other {
    background: var(--color-background-secondary, #F1F5F9);
    color: var(--color-text-secondary);
  }

  .loc-badge.active {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .loc-actions-cell {
    display: flex;
    align-items: center;
    gap: 5px;
    justify-content: flex-end;
  }

  .loc-action-btn {
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

  .loc-action-btn.view:hover {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
  }

  .loc-action-btn.edit:hover {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
  }

  .loc-action-btn.danger:hover {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .loc-empty-state {
    padding: 64px 24px;
    text-align: center;
    color: var(--color-text-secondary);
  }

  .loc-empty-icon {
    font-size: 32px;
    opacity: .25;
    margin-bottom: 12px;
  }

  /* Pagination */
  .loc-pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    background: var(--color-background-primary, #fff);
    gap: 16px;
    flex-wrap: wrap;
  }

  .loc-pagination-info {
    font-size: 12px;
    color: var(--color-text-secondary);
  }

  .loc-pagination {
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .loc-page-btn {
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

  .loc-page-btn:hover:not(:disabled) {
    background: var(--prod-blue-pale);
    border-color: var(--prod-blue);
    color: var(--prod-blue);
  }

  .loc-page-btn.active {
    background: var(--prod-blue);
    border-color: var(--prod-blue);
    color: #fff;
  }

  .loc-page-btn:disabled {
    opacity: .4;
    cursor: not-allowed;
  }

  .loc-entries-select {
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

  .loc-entries-select:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    outline: none;
  }

  .loc-entries-label {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-right: 8px;
  }

  .loc-entries-wrap {
    display: flex;
    align-items: center;
  }

  .loc-overlay {
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

  .loc-overlay.open {
    display: flex;
  }

  .loc-modal {
    background: var(--color-background-primary, #fff);
    border-radius: var(--prod-radius-xl);
    border: 1px solid rgba(0, 0, 0, .08);
    width: 100%;
    max-width: 500px;
    max-height: 92vh;
    overflow-y: auto;
    box-shadow: var(--prod-shadow-lg);
  }

  .loc-modal-header {
    padding: 22px 26px 18px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  .loc-modal-title {
    font-size: 17px;
    font-weight: 600;
    color: var(--color-text-primary);
  }

  .loc-modal-close {
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

  .loc-modal-close:hover {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .loc-modal-body {
    padding: 26px;
  }

  .loc-modal-footer {
    padding: 18px 26px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    justify-content: flex-end;
    gap: 8px;
  }

  .loc-form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 18px;
  }

  .loc-form-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .07em;
  }

  .loc-form-label .req {
    color: var(--prod-red);
    margin-left: 2px;
  }

  .loc-form-input,
  .loc-form-select,
  .loc-form-textarea {
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

  .loc-form-input:focus,
  .loc-form-select:focus,
  .loc-form-textarea:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    background: var(--color-background-primary, #fff);
  }

  .loc-form-textarea {
    resize: vertical;
    min-height: 76px;
  }

  @media (max-width: 600px) {
    .loc-search {
      width: 160px;
    }
  }
</style>

<div class="loc-page">
  <!-- Header -->
  <div class="loc-page-header">
    <div>
      <div class="loc-page-eyebrow"><i class="fas fa-map-location-dot" style="margin-right:5px"></i>Inventory</div>
      <div class="loc-page-title">Storage Locations</div>
      <div class="loc-page-subtitle">Manage warehouses, kitchens, and storage areas</div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="loc-stats-grid">
    <div class="loc-stat-card blue">
      <div class="loc-stat-icon"><i class="fas fa-warehouse"></i></div>
      <div class="loc-stat-label">Total Locations</div>
      <div class="loc-stat-value" id="statTotal">—</div>
    </div>
    <div class="loc-stat-card amber">
      <div class="loc-stat-icon"><i class="fas fa-boxes-stacked"></i></div>
      <div class="loc-stat-label">Main Warehouses</div>
      <div class="loc-stat-value" id="statWarehouse">—</div>
    </div>
    <div class="loc-stat-card purple">
      <div class="loc-stat-icon"><i class="fas fa-utensils"></i></div>
      <div class="loc-stat-label">Kitchens</div>
      <div class="loc-stat-value" id="statKitchen">—</div>
    </div>
    <div class="loc-stat-card cyan">
      <div class="loc-stat-icon"><i class="fas fa-wine-glass"></i></div>
      <div class="loc-stat-label">Bars</div>
      <div class="loc-stat-value" id="statBar">—</div>
    </div>
  </div>

  <!-- Locations Table -->
  <div class="loc-card">
    <div class="loc-card-header">
      <div>
        <div class="loc-card-title">All Locations</div>
        <div class="loc-card-count" id="tableCount">Loading…</div>
      </div>
      <div class="loc-toolbar">
        <div class="loc-entries-wrap">
          <span class="loc-entries-label">Show</span>
          <select class="loc-entries-select" id="entriesPerPage" onchange="changeEntriesPerPage()">
            <option value="10">10</option>
            <option value="25" selected>25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="loc-entries-label">entries</span>
        </div>
        <div class="loc-search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" id="searchInput" class="loc-search" placeholder="Search locations…" oninput="filterTable()">
        </div>
        <button onclick="openAddLocationModal()" class="loc-btn loc-btn-primary">
          <i class="fas fa-plus"></i> Add Location
        </button>
      </div>
    </div>
    <div class="loc-table-wrap">
      <table class="loc-table" id="locationsTable">
        <thead>
          <tr>
            <th>Location</th>
            <th>Property</th>
            <th>Type</th>
            <th>Description</th>
            <th class="center">Status</th>
            <th class="right">Actions</th>
          </tr>
        </thead>
        <tbody id="locationsTableBody">
          <tr>
            <td colspan="6">
              <div class="loc-empty-state">
                <div class="loc-empty-icon"><i class="fas fa-spinner fa-spin"></i></div>
                <div class="loc-empty-text">Loading locations…</div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="loc-pagination-wrap" id="paginationWrap" style="display:none">
      <div class="loc-pagination-info" id="paginationInfo">Showing 1-25 of 100 entries</div>
      <div class="loc-pagination" id="paginationControls"></div>
    </div>
  </div>
</div>

<!-- Location Details Modal -->
<div class="loc-overlay" id="locationDetailsModal" onclick="if(event.target===this)closeLocationDetailsModal()">
  <div class="loc-modal" role="dialog" aria-modal="true" aria-labelledby="modalLocationName">
    <div class="loc-modal-header">
      <div>
        <div class="loc-modal-title" id="modalLocationName">Location Details</div>
      </div>
      <button class="loc-modal-close" onclick="closeLocationDetailsModal()" aria-label="Close">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="loc-modal-body" id="modalLocationContent"></div>
  </div>
</div>

<!-- Add Location Modal -->
<div class="loc-overlay" id="addLocationModal" onclick="if(event.target===this)closeAddLocationModal()">
  <div class="loc-modal" role="dialog" aria-modal="true">
    <div class="loc-modal-header">
      <div>
        <div class="loc-modal-title">Add New Location</div>
      </div>
      <button class="loc-modal-close" onclick="closeAddLocationModal()" aria-label="Close">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <form id="addLocationForm" onsubmit="submitAddLocation(event)">
      <div class="loc-modal-body">
        <div class="loc-form-group">
          <label class="loc-form-label">Property <span class="req">*</span></label>
          <select name="property_id" id="addPropertyId" required class="loc-form-select">
            <option value="">Select Property</option>
          </select>
        </div>
        <div class="loc-form-group">
          <label class="loc-form-label">Location Name <span class="req">*</span></label>
          <input type="text" name="location_name" required class="loc-form-input" placeholder="e.g., Main Stockroom">
        </div>
        <div class="loc-form-group">
          <label class="loc-form-label">Location Type <span class="req">*</span></label>
          <select name="location_type" required class="loc-form-select">
            <option value="">Select Type</option>
            <option value="main_warehouse">Main Warehouse</option>
            <option value="kitchen">Kitchen</option>
            <option value="bar">Bar</option>
            <option value="housekeeping">Housekeeping</option>
            <option value="front_desk">Front Desk</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="loc-form-group">
          <label class="loc-form-label">Description</label>
          <textarea name="description" rows="3" class="loc-form-textarea" placeholder="Optional description..."></textarea>
        </div>
      </div>
      <div class="loc-modal-footer">
        <button type="button" class="loc-btn" onclick="closeAddLocationModal()">
          <i class="fas fa-times"></i> Cancel
        </button>
        <button type="submit" class="loc-btn loc-btn-primary">
          <i class="fas fa-save"></i> Save Location
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Location Modal -->
<div class="loc-overlay" id="editLocationModal" onclick="if(event.target===this)closeEditLocationModal()">
  <div class="loc-modal" role="dialog" aria-modal="true">
    <div class="loc-modal-header">
      <div>
        <div class="loc-modal-title">Edit Location</div>
      </div>
      <button class="loc-modal-close" onclick="closeEditLocationModal()" aria-label="Close">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <form id="editLocationForm" onsubmit="submitEditLocation(event)">
      <div class="loc-modal-body">
        <input type="hidden" name="location_id" id="editLocationId">
        <div class="loc-form-group">
          <label class="loc-form-label">Property <span class="req">*</span></label>
          <select name="property_id" id="editPropertyId" required class="loc-form-select">
            <option value="">Select Property</option>
          </select>
        </div>
        <div class="loc-form-group">
          <label class="loc-form-label">Location Name <span class="req">*</span></label>
          <input type="text" name="location_name" id="editLocationName" required class="loc-form-input" placeholder="e.g., Main Stockroom">
        </div>
        <div class="loc-form-group">
          <label class="loc-form-label">Location Type <span class="req">*</span></label>
          <select name="location_type" id="editLocationType" required class="loc-form-select">
            <option value="">Select Type</option>
            <option value="main_warehouse">Main Warehouse</option>
            <option value="kitchen">Kitchen</option>
            <option value="bar">Bar</option>
            <option value="housekeeping">Housekeeping</option>
            <option value="front_desk">Front Desk</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="loc-form-group">
          <label class="loc-form-label">Description</label>
          <textarea name="description" id="editLocationDescription" rows="3" class="loc-form-textarea" placeholder="Optional description..."></textarea>
        </div>
      </div>
      <div class="loc-modal-footer">
        <button type="button" class="loc-btn" onclick="closeEditLocationModal()">
          <i class="fas fa-times"></i> Cancel
        </button>
        <button type="submit" class="loc-btn loc-btn-primary">
          <i class="fas fa-save"></i> Update Location
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  // CSRF Token for API requests
  const CSRF_TOKEN = <?php echo json_encode(CSRF_TOKEN); ?>;

  let locationsData = [];
  let filteredData = [];
  let currentPage = 1;
  let entriesPerPage = 25;
  let propertiesData = [];

  document.addEventListener('DOMContentLoaded', function() {
    fetchLocations();
  });

  async function fetchLocations() {
    try {
      const [locRes, propRes] = await Promise.all([
        fetch('../api/inventory_locations.php?action=list'),
        fetch('../api/inventory_locations.php?action=properties')
      ]);
      const locResult = await locRes.json();
      const propResult = await propRes.json();
      if (locResult.success) {
        locationsData = locResult.data;
        filteredData = [...locationsData];
        currentPage = 1;
        renderTable();
        renderStats();
      } else {
        showEmptyState(locResult.message);
      }
      if (propResult.success) {
        propertiesData = propResult.data;
        populatePropertyDropdowns();
      }
    } catch (e) {
      document.getElementById('locationsTableBody').innerHTML = `<tr><td colspan="6" class="p-4 text-center text-red-400">Failed to load data</td></tr>`;
    }
  }

  function populatePropertyDropdowns() {
    const opts = '<option value="">Select Property</option>' +
      propertiesData.map(p => `<option value="${p.property_id}">${p.property_name}</option>`).join('');
    document.getElementById('addPropertyId').innerHTML = opts;
    document.getElementById('editPropertyId').innerHTML = opts;
  }

  function showEmptyState(msg) {
    document.getElementById('locationsTableBody').innerHTML = `
    <tr><td colspan="6">
      <div class="loc-empty-state">
        <div class="loc-empty-icon"><i class="fas fa-circle-xmark"></i></div>
        <div class="loc-empty-text">${msg}</div>
      </div>
    </td></tr>`;
  }

  function renderStats() {
    const total = locationsData.length;
    const warehouse = locationsData.filter(l => l.location_type === 'main_warehouse').length;
    const kitchen = locationsData.filter(l => l.location_type === 'kitchen').length;
    const bar = locationsData.filter(l => l.location_type === 'bar').length;

    const pairs = [
      ['statTotal', total],
      ['statWarehouse', warehouse],
      ['statKitchen', kitchen],
      ['statBar', bar]
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
    const tbody = document.getElementById('locationsTableBody');
    const count = document.getElementById('tableCount');

    if (!filteredData.length) {
      count.textContent = '0 locations';
      document.getElementById('paginationWrap').style.display = 'none';
      tbody.innerHTML = `
      <tr><td colspan="6">
        <div class="loc-empty-state">
          <div class="loc-empty-icon"><i class="fas fa-warehouse"></i></div>
          <div class="loc-empty-text">No locations found</div>
          <div class="loc-empty-sub"><a href="javascript:;" onclick="openAddLocationModal()" style="color:var(--prod-blue);font-weight:500">Add your first location</a></div>
        </div>
      </td></tr>`;
      return;
    }

    const totalEntries = filteredData.length;
    const totalPages = Math.ceil(totalEntries / entriesPerPage);
    const start = (currentPage - 1) * entriesPerPage;
    const end = Math.min(start + entriesPerPage, totalEntries);
    const paginatedData = filteredData.slice(start, end);

    count.textContent = `${totalEntries} location${totalEntries !== 1 ? 's' : ''}`;

    const paginationWrap = document.getElementById('paginationWrap');
    if (totalEntries > 10) {
      paginationWrap.style.display = 'flex';
      renderPagination(totalPages, start, end, totalEntries);
    } else {
      paginationWrap.style.display = 'none';
    }

    tbody.innerHTML = paginatedData.map(l => {
      const typeClass = (l.location_type || '').replace('_', '-');
      return `
      <tr class="location-row">
        <td>
          <div class="loc-name-cell">
            <div class="loc-avatar"><i class="fas fa-map-marker-alt"></i></div>
            <div>
              <div class="loc-name-primary">${l.location_name}</div>
            </div>
          </div>
        </td>
        <td>
          <span class="loc-name-primary">${l.property_name || '—'}</span>
        </td>
        <td>
          <span class="loc-badge ${typeClass}">${ucfirst((l.location_type || '').replace(/_/g, ' '))}</span>
        </td>
        <td>
          <span class="loc-name-primary">${l.description ? l.description.substring(0, 50) + (l.description.length > 50 ? '…' : '') : '—'}</span>
        </td>
        <td style="text-align:center">
          <span class="loc-badge active">Active</span>
        </td>
        <td>
          <div class="loc-actions-cell">
            <button onclick="viewLocationDetails(${l.location_id})" class="loc-action-btn view"><i class="fas fa-eye"></i> View</button>
            <button onclick="openEditLocationModal(${l.location_id})" class="loc-action-btn edit"><i class="fas fa-pen"></i> Edit</button>
            <button onclick="deleteLocation(${l.location_id}, '${(l.location_name || '').replace(/'/g, "\\'")}')" class="loc-action-btn danger"><i class="fas fa-trash-alt"></i></button>
          </div>
        </td>
      </tr>`;
    }).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toLowerCase();
    currentPage = 1;

    if (!filter) {
      filteredData = [...locationsData];
    } else {
      filteredData = locationsData.filter(l =>
        l.location_name.toLowerCase().includes(filter) ||
        (l.property_name && l.property_name.toLowerCase().includes(filter)) ||
        (l.location_type && l.location_type.toLowerCase().includes(filter))
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
    html += `<button class="loc-page-btn" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`;

    const maxButtons = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);
    if (endPage - startPage < maxButtons - 1) {
      startPage = Math.max(1, endPage - maxButtons + 1);
    }

    if (startPage > 1) {
      html += `<button class="loc-page-btn" onclick="goToPage(1)">1</button>`;
      if (startPage > 2) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
    }

    for (let i = startPage; i <= endPage; i++) {
      html += `<button class="loc-page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
    }

    if (endPage < totalPages) {
      if (endPage < totalPages - 1) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
      html += `<button class="loc-page-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
    }

    html += `<button class="loc-page-btn" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`;
    paginationControls.innerHTML = html;
  }

  function ucfirst(str) {
    return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
  }

  function viewLocationDetails(locationId) {
    const location = locationsData.find(l => l.location_id == locationId);
    if (!location) return;

    document.getElementById('modalLocationName').textContent = location.location_name;

    const typeClass = (location.location_type || '').replace('_', '-');

    document.getElementById('modalLocationContent').innerHTML = `
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:28px;">
      <div>
        <div style="font-size:11px;font-weight:700;color:var(--prod-blue);text-transform:uppercase;letter-spacing:.1em;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid var(--prod-blue-pale);"><i class="fas fa-info-circle" style="margin-right:5px;opacity:.6"></i>Basic Information</div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);flex-shrink:0;font-weight:500;">Location Name</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${location.location_name}</div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);flex-shrink:0;font-weight:500;">Property</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${location.property_name || '—'}</div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);flex-shrink:0;font-weight:500;">Location Type</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;"><span class="loc-badge ${typeClass}">${ucfirst((location.location_type || '').replace(/_/g, ' '))}</span></div></div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);flex-shrink:0;font-weight:500;">Description</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;">${location.description || '—'}</div></div>
      </div>
      <div>
        <div style="font-size:11px;font-weight:700;color:var(--prod-blue);text-transform:uppercase;letter-spacing:.1em;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid var(--prod-blue-pale);margin-top:20px;"><i class="fas fa-sliders" style="margin-right:5px;opacity:.6"></i>Settings</div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid rgba(0,0,0,.04);"><div style="font-size:12px;color:var(--color-text-secondary);flex-shrink:0;font-weight:500;">Status</div><div style="font-size:13px;color:var(--color-text-primary);font-weight:500;text-align:right;"><span class="loc-badge active">${location.is_active ? 'Active' : 'Inactive'}</span></div></div>
      </div>
    </div>`;

    document.getElementById('locationDetailsModal').classList.add('open');
  }

  function closeLocationDetailsModal() {
    document.getElementById('locationDetailsModal').classList.remove('open');
  }

  function openAddLocationModal() {
    document.getElementById('addLocationModal').classList.add('open');
  }

  function closeAddLocationModal() {
    document.getElementById('addLocationModal').classList.remove('open');
    document.getElementById('addLocationForm').reset();
  }

  function submitAddLocation(event) {
    event.preventDefault();

    const form = document.getElementById('addLocationForm');
    const formData = new FormData(form);
    formData.append('action', 'add_location');

    // Show loading state with higher z-index
    Swal.fire({
      title: 'Adding Location...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

    const payload = {
      property_id: formData.get('property_id'),
      location_name: formData.get('location_name'),
      location_type: formData.get('location_type'),
      description: formData.get('description'),
      csrf_token: CSRF_TOKEN
    };
    fetch('../api/inventory_locations.php?action=create', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddLocationModal();
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Location Added!',
            text: data.message,
            confirmButtonColor: '#1A6BBF'
          }).then(() => fetchLocations());
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
        closeAddLocationModal();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred.',
          confirmButtonColor: '#C62828'
        });
      });
  }

  // Edit Location Modal Functions
  function openEditLocationModal(locationId) {
    const location = locationsData.find(l => l.location_id == locationId);
    if (!location) return;

    // Populate form with existing data
    document.getElementById('editLocationId').value = location.location_id;
    document.getElementById('editPropertyId').value = location.property_id;
    document.getElementById('editLocationName').value = location.location_name;
    document.getElementById('editLocationType').value = location.location_type;
    document.getElementById('editLocationDescription').value = location.description || '';

    document.getElementById('editLocationModal').classList.add('open');
  }

  function closeEditLocationModal() {
    document.getElementById('editLocationModal').classList.remove('open');
    document.getElementById('editLocationForm').reset();
  }

  function submitEditLocation(event) {
    event.preventDefault();

    const form = document.getElementById('editLocationForm');
    const formData = new FormData(form);
    formData.append('action', 'edit_location');

    Swal.fire({
      title: 'Updating...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

    const payload = {
      location_id: formData.get('location_id'),
      property_id: formData.get('property_id'),
      location_name: formData.get('location_name'),
      location_type: formData.get('location_type'),
      description: formData.get('description'),
      csrf_token: CSRF_TOKEN
    };
    fetch('../api/inventory_locations.php?action=update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditLocationModal();
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: data.message,
            confirmButtonColor: '#1A6BBF'
          }).then(() => fetchLocations());
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
        closeEditLocationModal();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred.',
          confirmButtonColor: '#C62828'
        });
      });
  }

  function deleteLocation(locationId, locationName) {
    Swal.fire({
      title: 'Delete location?',
      html: `<span style="font-size:14px">You're about to delete <strong>${locationName}</strong>. This action cannot be undone.</span>`,
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

        fetch('../api/inventory_locations.php?action=delete', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              location_id: locationId,
              csrf_token: CSRF_TOKEN
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
              }).then(() => fetchLocations());
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