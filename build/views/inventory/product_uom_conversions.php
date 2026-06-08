<?php if (false) {} ?>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Lora:ital,wght@0,600;1,500&display=swap" rel="stylesheet">
<style>
  /* ── Design Tokens ── */
  :root {
    --brand:        #C84B31;
    --brand-dark:   #A03525;
    --brand-light:  #FDF0ED;
    --accent:       #F0A500;
    --accent-light: #FFF8E7;
    --surface:      #FFFFFF;
    --surface-2:    #FAFAFA;
    --border:       #EDEAE6;
    --border-strong:#D6D0C8;
    --text-primary: #1A1410;
    --text-secondary:#6B6460;
    --text-muted:   #A09890;
    --success:      #2D7D4F;
    --success-bg:   #EDFAF3;
    --warning:      #B45309;
    --warning-bg:   #FEF7EC;
    --danger:       #C0392B;
    --danger-bg:    #FEF0EE;
    --radius-sm:    6px;
    --radius-md:    10px;
    --radius-lg:    16px;
    --radius-xl:    20px;
    --shadow-card:  0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.06);
    --font-display: 'Lora', Georgia, serif;
    --font-body:    'DM Sans', system-ui, sans-serif;
  }

  /* ── Page Header ── */
  .puc-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border);
    gap: 16px;
    flex-wrap: wrap;
  }
  @media (max-width: 640px) {
    .puc-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 12px;
    }
  }
  .puc-title {
    font-family: var(--font-display);
    font-size: 26px; font-weight: 600;
    color: var(--text-primary);
    margin: 0;
  }
  @media (max-width: 640px) {
    .puc-title {
      font-size: 22px;
    }
  }
  .puc-subtitle {
    font-size: 14px; color: var(--text-secondary);
    margin-top: 4px;
  }
  @media (max-width: 640px) {
    .puc-subtitle {
      font-size: 13px;
    }
  }

  /* ── Stats Grid ── */
  .fo-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }
  @media (max-width: 1200px) { .fo-stats-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 640px) { .fo-stats-grid { grid-template-columns: 1fr; } }

  .fo-stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 20px 22px;
    box-shadow: var(--shadow-card);
    display: flex; align-items: center; gap: 16px;
    transition: box-shadow .2s, transform .2s;
  }
  @media (max-width: 640px) {
    .fo-stat-card {
      padding: 16px 18px;
      gap: 12px;
    }
  }
  .fo-stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(0,0,0,.10); }

  .fo-stat-icon {
    width: 48px; height: 48px;
    border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
  }
  @media (max-width: 640px) {
    .fo-stat-icon {
      width: 40px; height: 40px;
      font-size: 18px;
    }
  }
  .fo-stat-icon.products { background: #EFF3FF; color: #3B5BDB; }
  .fo-stat-icon.uoms { background: var(--brand-light); color: var(--brand); }
  .fo-stat-icon.conversions { background: var(--success-bg); color: var(--success); }
  .fo-stat-icon.default { background: var(--accent-light); color: var(--accent); }

  .fo-stat-label {
    font-family: var(--font-body);
    font-size: 11.5px; font-weight: 600;
    letter-spacing: .06em; text-transform: uppercase;
    color: var(--text-muted); margin-bottom: 4px;
  }
  @media (max-width: 640px) {
    .fo-stat-label {
      font-size: 10.5px;
    }
  }
  .fo-stat-value {
    font-family: var(--font-display);
    font-size: 28px; font-weight: 600;
    color: var(--text-primary); line-height: 1;
  }
  @media (max-width: 640px) {
    .fo-stat-value {
      font-size: 24px;
    }
  }

  /* ── Card ── */
  .fo-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-card);
    overflow: hidden;
    margin-bottom: 24px;
  }

  .fo-card-header {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 20px 24px 18px;
    border-bottom: 1px solid var(--border);
    gap: 12px; flex-wrap: wrap;
  }
  @media (max-width: 768px) {
    .fo-card-header {
      flex-direction: column;
      align-items: stretch;
      gap: 16px;
    }
  }

  .fo-card-title {
    font-family: var(--font-display);
    font-size: 20px; font-weight: 600;
    color: var(--text-primary); margin: 0;
    font-style: italic;
  }
  @media (max-width: 768px) {
    .fo-card-title {
      font-size: 18px;
    }
  }

  .fo-card-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
  @media (max-width: 768px) {
    .fo-card-actions {
      width: 100%;
      justify-content: space-between;
    }
  }
  @media (max-width: 480px) {
    .fo-card-actions {
      flex-direction: column;
      align-items: stretch;
    }
  }

  /* Search */
  .fo-search-wrap { position: relative; }
  .fo-search-wrap i {
    position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
    color: var(--text-muted); font-size: 13px; pointer-events: none;
  }
  .fo-search {
    font-family: var(--font-body); font-size: 13px;
    background: var(--surface-2); border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 8px 12px 8px 32px;
    color: var(--text-primary); outline: none; width: 220px;
    transition: border-color .15s, box-shadow .15s;
  }
  @media (max-width: 768px) {
    .fo-search {
      width: 100%;
      flex: 1;
    }
  }
  .fo-search:focus {
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(200,75,49,.10);
    background: var(--surface);
  }

  /* Primary Button */
  .fo-btn-primary {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 18px;
    font-family: var(--font-body); font-size: 13px; font-weight: 600;
    background: var(--brand); color: #fff;
    border: none; border-radius: var(--radius-md);
    cursor: pointer;
    transition: background .15s, box-shadow .15s, transform .1s;
    box-shadow: 0 2px 8px rgba(200,75,49,.30);
    white-space: nowrap;
  }
  @media (max-width: 480px) {
    .fo-btn-primary {
      width: 100%;
      justify-content: center;
    }
  }
  .fo-btn-primary:hover { background: var(--brand-dark); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(200,75,49,.38); }
  .fo-btn-primary:active { transform: translateY(0); }

  .fo-btn-secondary {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 18px;
    font-family: var(--font-body); font-size: 13px; font-weight: 600;
    background: var(--surface); color: var(--text-secondary);
    border: 1px solid var(--border-strong); border-radius: var(--radius-md);
    cursor: pointer;
    transition: all .15s;
  }
  @media (max-width: 480px) {
    .fo-btn-secondary {
      width: 100%;
      justify-content: center;
    }
  }
  .fo-btn-secondary:hover { background: var(--surface-2); color: var(--text-primary); }

  /* ── Table ── */
  .fo-table { width: 100%; border-collapse: collapse; font-family: var(--font-body); }
  .fo-table thead tr { background: var(--surface-2); border-bottom: 1px solid var(--border); }
  .fo-table th {
    padding: 11px 16px;
    font-size: 11px; font-weight: 700;
    letter-spacing: .07em; text-transform: uppercase;
    color: var(--text-muted); white-space: nowrap; text-align: left;
  }
  @media (max-width: 640px) {
    .fo-table th {
      padding: 10px 12px;
      font-size: 10px;
    }
  }
  .fo-table th.right  { text-align: right; }
  .fo-table th.center { text-align: center; }

  .puc-row td {
    padding: 13px 16px;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
    font-size: 13.5px;
    color: var(--text-primary);
  }
  @media (max-width: 640px) {
    .puc-row td {
      padding: 10px 12px;
      font-size: 12px;
    }
  }
  .puc-row:last-child td { border-bottom: none; }
  .puc-row { transition: background .12s; }
  .puc-row:hover td { background: #FDF9F8; }

  .puc-product-cell { font-weight: 600; }
  .puc-base-uom { 
    font-size: 11px; color: var(--text-muted);
    background: var(--surface-2); padding: 2px 8px;
    border-radius: 999px; display: inline-block; margin-top: 4px;
  }

  .puc-conversion-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 12px;
    background: var(--brand-light);
    border: 1px solid var(--border-strong);
    border-radius: var(--radius-sm);
    font-size: 12px;
    margin: 2px;
  }
  @media (max-width: 640px) {
    .puc-conversion-badge {
      font-size: 11px;
      padding: 3px 10px;
      margin: 2px 1px;
    }
  }
  .puc-conversion-badge.default {
    background: var(--success-bg);
    border-color: var(--success);
    color: var(--success);
  }
  .puc-conversion-factor {
    font-weight: 600; color: var(--brand);
  }

  /* ── Form Fields ── */
  .fo-form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }
  @media (max-width: 768px) { .fo-form-grid { grid-template-columns: 1fr; } }

  .fo-form-group { margin-bottom: 16px; }

  .fo-label {
    display: block; font-size: 12px; font-weight: 600;
    color: var(--text-secondary); margin-bottom: 6px;
    text-transform: uppercase; letter-spacing: .03em;
  }

  .fo-input, .fo-select {
    width: 100%; padding: 10px 14px;
    font-family: var(--font-body); font-size: 14px;
    background: var(--surface); border: 1px solid var(--border-strong);
    border-radius: var(--radius-sm); color: var(--text-primary);
    transition: border-color .15s, box-shadow .15s;
  }
  .fo-input:focus, .fo-select:focus {
    border-color: var(--brand);
    outline: none;
    box-shadow: 0 0 0 3px rgba(200,75,49,.08);
  }

  /* ── Searchable Dropdown ── */
  .searchable-dropdown {
    position: relative;
    width: 100%;
  }

  .searchable-dropdown-toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 10px 14px;
    font-family: var(--font-body);
    font-size: 14px;
    background: var(--surface);
    border: 1px solid var(--border-strong);
    border-radius: var(--radius-sm);
    color: var(--text-muted);
    cursor: pointer;
    transition: border-color .15s, box-shadow .15s;
    user-select: none;
  }

  .searchable-dropdown-toggle:hover {
    border-color: var(--brand);
  }

  .searchable-dropdown-toggle.disabled {
    background: var(--surface-2);
    color: var(--text-muted);
    cursor: not-allowed;
    border-color: var(--border);
  }

  .searchable-dropdown-toggle .selected {
    color: var(--text-primary);
  }

  .searchable-dropdown-toggle i {
    font-size: 12px;
    color: var(--text-muted);
    transition: transform .15s;
  }

  .searchable-dropdown-menu.show + .searchable-dropdown-toggle i,
  .searchable-dropdown:has(.show) .searchable-dropdown-toggle i {
    transform: rotate(180deg);
  }

  .searchable-dropdown-menu {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    background: var(--surface);
    border: 1px solid var(--border-strong);
    border-radius: var(--radius-sm);
    box-shadow: 0 4px 16px rgba(0,0,0,.12);
    z-index: 1000;
    max-height: 320px;
    display: none;
    flex-direction: column;
    overflow: hidden;
  }

  .searchable-dropdown-menu.show {
    display: flex;
  }

  .searchable-dropdown-search {
    padding: 10px 12px;
    border-bottom: 1px solid var(--border);
    position: relative;
  }

  .searchable-dropdown-search i {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 13px;
  }

  .searchable-dropdown-search input {
    width: 100%;
    padding: 8px 12px 8px 32px;
    font-family: var(--font-body);
    font-size: 13px;
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    outline: none;
    transition: border-color .15s, box-shadow .15s;
  }

  .searchable-dropdown-search input:focus {
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(200,75,49,.08);
  }

  .searchable-dropdown-search input::placeholder {
    color: var(--text-muted);
  }

  .searchable-dropdown-list {
    flex: 1;
    overflow-y: auto;
    max-height: 240px;
  }

  .searchable-dropdown-item {
    padding: 10px 14px;
    cursor: pointer;
    border-bottom: 1px solid var(--border);
    transition: background .12s;
  }

  .searchable-dropdown-item:last-child {
    border-bottom: none;
  }

  .searchable-dropdown-item:hover {
    background: var(--brand-light);
  }

  .searchable-dropdown-item .product-name {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 2px;
  }

  .searchable-dropdown-item .product-code {
    font-size: 11px;
    color: var(--text-muted);
  }

  .searchable-dropdown-item[style*="none"] {
    display: none !important;
  }

  /* ── Modal ── */
  .fo-modal-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.50);
    display: flex; align-items: center; justify-content: center;
    z-index: 9999; opacity: 0; visibility: hidden;
    transition: opacity .2s, visibility .2s;
    padding: 20px;
  }
  @media (max-width: 480px) {
    .fo-modal-overlay {
      padding: 16px;
    }
  }
  .fo-modal-overlay.open { opacity: 1; visibility: visible; }

  .fo-modal {
    background: var(--surface);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-modal);
    width: 90%; max-width: 560px;
    max-height: 90vh; overflow-y: auto;
    transform: translateY(20px) scale(.98);
    transition: transform .2s;
  }
  @media (max-width: 480px) {
    .fo-modal {
      width: 100%;
      max-width: none;
      border-radius: var(--radius-lg);
    }
  }
  .fo-modal-overlay.open .fo-modal { transform: translateY(0) scale(1); }

  .fo-modal-header {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
  }
  @media (max-width: 480px) {
    .fo-modal-header {
      padding: 16px 18px;
    }
  }
  .fo-modal-title {
    font-family: var(--font-display);
    font-size: 18px; font-weight: 600; margin: 0;
  }
  @media (max-width: 480px) {
    .fo-modal-title {
      font-size: 16px;
    }
  }
  .fo-modal-close {
    width: 36px; height: 36px;
    border: none; border-radius: var(--radius-md);
    background: var(--surface-2); color: var(--text-secondary);
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: all .15s;
  }
  .fo-modal-close:hover { background: var(--border); color: var(--text-primary); }

  .fo-modal-body { padding: 24px; }
  @media (max-width: 480px) {
    .fo-modal-body {
      padding: 18px;
    }
  }
  .fo-modal-footer {
    display: flex; justify-content: flex-end; gap: 12px;
    padding: 16px 24px; border-top: 1px solid var(--border);
  }
  @media (max-width: 480px) {
    .fo-modal-footer {
      padding: 16px 18px;
      flex-direction: column;
    }
    .fo-modal-footer .fo-btn-primary,
    .fo-modal-footer .fo-btn-secondary {
      width: 100%;
    }
  }

  /* ── Empty State ── */
  .fo-empty {
    text-align: center; padding: 64px 24px;
  }
  @media (max-width: 640px) {
    .fo-empty {
      padding: 48px 20px;
    }
  }
  .fo-empty-icon {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: var(--surface-2);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; color: var(--text-muted);
    margin: 0 auto 20px;
  }
  @media (max-width: 640px) {
    .fo-empty-icon {
      width: 56px; height: 56px;
      font-size: 22px;
      margin-bottom: 16px;
    }
  }
  .fo-empty h4 {
    font-family: var(--font-display);
    font-size: 18px; font-weight: 600; color: var(--text-primary);
    margin: 0 0 6px; font-style: italic;
  }
  @media (max-width: 640px) {
    .fo-empty h4 {
      font-size: 16px;
    }
  }
  .fo-empty p { font-size: 13.5px; color: var(--text-secondary); margin: 0 0 20px; }
  @media (max-width: 640px) {
    .fo-empty p {
      font-size: 12px;
    }
  }

  /* ── Checkbox ── */
  .fo-checkbox-wrap {
    display: flex; align-items: center; gap: 8px;
    cursor: pointer;
  }
  .fo-checkbox-wrap input[type="checkbox"] {
    width: 18px; height: 18px;
    accent-color: var(--brand);
    cursor: pointer;
  }
  .fo-checkbox-label {
    font-size: 14px; color: var(--text-primary);
  }

  /* ── Table Footer & Pagination ── */
  .fo-table-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-top: 1px solid var(--border);
    gap: 12px;
    flex-wrap: wrap;
  }
  @media (max-width: 640px) {
    .fo-table-footer {
      flex-direction: column;
      align-items: stretch;
      gap: 12px;
    }
  }
  .fo-table-info {
    font-size: 12px;
    color: var(--text-secondary);
  }
  @media (max-width: 640px) {
    .fo-table-info {
      text-align: center;
    }
  }
  .fo-table-pagination {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  @media (max-width: 640px) {
    .fo-table-pagination {
    justify-content: center;
    flex-wrap: wrap;
    }
  }
  .fo-table-pagination button {
    padding: 6px 10px;
    font-size: 12px;
  }
  .fo-table-pagination button:disabled {
    opacity: 0.4;
    cursor: not-allowed;
  }
  .fo-table-pagination span {
    font-size: 12px;
    color: var(--text-secondary);
  }

  /* ── Custom Alert Modal ── */
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
  @media (max-width: 480px) {
    .alert-overlay {
      padding: 16px;
    }
  }
  .alert-overlay.open { display: flex }
  .alert-box {
    background: #fff;
    border-radius: 12px;
    border: 1px solid var(--border);
    width: 100%;
    max-width: 400px;
    padding: 24px;
    box-shadow: 0 16px 32px rgba(0, 0, 0, .15)
  }
  @media (max-width: 480px) {
    .alert-box {
      padding: 20px;
      max-width: none;
    }
  }
  .alert-title {
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px
  }
  .alert-message {
    font-size: 13px;
    color: var(--text-secondary);
    margin-bottom: 20px;
    line-height: 1.5
  }
  .alert-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end
  }
  @media (max-width: 480px) {
    .alert-actions {
      flex-direction: column;
    }
    .alert-actions button {
      width: 100%;
    }
  }
  .alert-btn-cancel {
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 600;
    background: var(--surface);
    color: var(--text-secondary);
    border: 1px solid var(--border-strong);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all .15s
  }
  .alert-btn-cancel:hover {
    background: var(--surface-2);
    color: var(--text-primary)
  }
  .alert-btn-confirm {
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 600;
    background: var(--brand);
    color: #fff;
    border: none;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all .15s
  }
  .alert-btn-confirm:hover {
    background: var(--brand-dark)
  }
</style>

<!-- Stats Grid -->
<div class="fo-stats-grid">
  <div class="fo-stat-card">
    <div class="fo-stat-icon products"><i class="fas fa-boxes"></i></div>
    <div>
      <div class="fo-stat-label">Products</div>
      <div class="fo-stat-value" id="statProducts">—</div>
    </div>
  </div>
  <div class="fo-stat-card">
    <div class="fo-stat-icon uoms"><i class="fas fa-ruler-combined"></i></div>
    <div>
      <div class="fo-stat-label">UOM Types</div>
      <div class="fo-stat-value" id="statUoms">—</div>
    </div>
  </div>
  <div class="fo-stat-card">
    <div class="fo-stat-icon conversions"><i class="fas fa-exchange-alt"></i></div>
    <div>
      <div class="fo-stat-label">Conversions</div>
      <div class="fo-stat-value" id="statConversions">—</div>
    </div>
  </div>
  <div class="fo-stat-card">
    <div class="fo-stat-icon default"><i class="fas fa-check-circle"></i></div>
    <div>
      <div class="fo-stat-label">Default Set</div>
      <div class="fo-stat-value" id="statDefault">—</div>
    </div>
  </div>
</div>

<!-- Product Conversions Table -->
<div class="fo-card">
  <div class="fo-card-header">
    <h2 class="fo-card-title">Product UOM Conversions</h2>
    <div class="fo-card-actions">
      <div class="fo-search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" class="fo-search" placeholder="Search products..." oninput="filterTable()">
      </div>
      <button onclick="openAddModal()" class="fo-btn-primary">
        <i class="fas fa-plus"></i> Add Conversion
      </button>
    </div>
  </div>
  <div style="overflow-x:auto">
    <table class="fo-table" id="conversionsTable">
      <thead>
        <tr>
          <th>Product</th>
          <th>Base UOM</th>
          <th>Conversions</th>
          <th class="center">Actions</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        <tr>
          <td colspan="4" class="fo-empty">
            <i class="fas fa-spinner fa-spin" style="margin-right:8px;color:var(--text-muted)"></i>
            Loading conversions…
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="fo-table-footer">
    <div class="fo-table-info">
      <span id="tableInfo">Showing 0 of 0 entries</span>
    </div>
    <div class="fo-table-pagination">
      <select id="rowsPerPage" class="fo-select" style="width: auto; padding: 6px 10px; font-size: 12px;" onchange="changeRowsPerPage()">
        <option value="10">10 per page</option>
        <option value="25">25 per page</option>
        <option value="50">50 per page</option>
        <option value="100">100 per page</option>
      </select>
      <button class="fo-btn-secondary" id="prevBtn" onclick="changePage(-1)" disabled>
        <i class="fas fa-chevron-left"></i>
      </button>
      <span id="pageInfo">Page 1 of 1</span>
      <button class="fo-btn-secondary" id="nextBtn" onclick="changePage(1)" disabled>
        <i class="fas fa-chevron-right"></i>
      </button>
    </div>
  </div>
</div>

<!-- Add/Edit Conversion Modal -->
<div id="conversionModal" class="fo-modal-overlay" onclick="if(event.target===this)closeModal()">
  <div class="fo-modal">
    <div class="fo-modal-header">
      <h3 class="fo-modal-title" id="modalTitle">Add UOM Conversion</h3>
      <button onclick="closeModal()" class="fo-modal-close"><i class="fas fa-times"></i></button>
    </div>
    <form id="conversionForm" onsubmit="saveConversion(event)">
      <div class="fo-modal-body">
        <input type="hidden" id="conversionId" name="conversion_id">
        
        <div class="fo-form-group">
          <label class="fo-label">Product <span style="color:var(--danger)">*</span></label>
          <!-- Searchable Product Dropdown -->
          <div class="searchable-dropdown" id="productDropdown">
            <div class="searchable-dropdown-toggle" id="productDropdownToggle" onclick="toggleProductDropdown()">
              <span id="productDisplayText">Select Product</span>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="searchable-dropdown-menu" id="productDropdownMenu">
              <div class="searchable-dropdown-search">
                <i class="fas fa-search"></i>
                <input type="text" id="productSearchInput" placeholder="Search products..." 
                  onkeyup="filterProductDropdown()" onkeydown="handleSearchKeydown(event)">
              </div>
              <div class="searchable-dropdown-list" id="productDropdownList">
                <!-- Options populated by JS -->
              </div>
            </div>
            <input type="hidden" id="productId" name="product_id" required>
          </div>
        </div>
        
        <div class="fo-form-group">
          <label class="fo-label">Base UOM</label>
          <input type="text" id="baseUomDisplay" class="fo-input" readonly disabled>
        </div>
        
        <div class="fo-form-grid">
          <div class="fo-form-group">
            <label class="fo-label">From UOM <span style="color:var(--danger)">*</span></label>
            <select id="fromUomId" name="from_uom_id" class="fo-select" required>
              <option value="">Select UOM</option>
            </select>
          </div>
          <div class="fo-form-group">
            <label class="fo-label">To UOM <span style="color:var(--danger)">*</span></label>
            <select id="toUomId" name="to_uom_id" class="fo-select" required>
              <option value="">Select UOM</option>
            </select>
          </div>
        </div>
        
        <div class="fo-form-group">
          <label class="fo-label">Conversion Factor <span style="color:var(--danger)">*</span></label>
          <input type="number" id="conversionFactor" name="conversion_factor" class="fo-input" 
            step="0.000001" min="0.000001" required placeholder="e.g. 1000 (1 kg = 1000 g)">
          <small style="color:var(--text-muted);font-size:11px;margin-top:4px;display:block;">
            Example: 1 From UOM = <span id="factorExample">?</span> To UOM
          </small>
        </div>
        
        <div class="fo-form-group">
          <label class="fo-checkbox-wrap">
            <input type="checkbox" id="isDefault" name="is_default" value="1">
            <span class="fo-checkbox-label">Set as default conversion for this product</span>
          </label>
        </div>
        
        <div style="margin-top:20px;padding-top:16px;border-top:2px solid var(--border);">
          <div style="font-size:12px;font-weight:600;color:var(--brand);text-transform:uppercase;letter-spacing:.05em;margin-bottom:12px;">
            <i class="fas fa-tag"></i> Alternative Product Code & Pricing
          </div>
          
          <div class="fo-form-grid">
            <div class="fo-form-group">
              <label class="fo-label">Alternative Product Code</label>
              <input type="text" id="altProductCode" name="alt_product_code" class="fo-input" 
                placeholder="e.g., RICE-SACK, RICE-1KG">
              <small style="color:var(--text-muted);font-size:11px;margin-top:4px;display:block;">
                Unique code for this UOM variant
              </small>
            </div>
            <div class="fo-form-group">
              <label class="fo-label">Barcode</label>
              <input type="text" id="barcode" name="barcode" class="fo-input" 
                placeholder="Scan or enter barcode">
            </div>
          </div>
          
          <div class="fo-form-group">
            <label class="fo-label">Pricing Mode</label>
            <select id="pricingMode" name="pricing_mode" class="fo-select" onchange="onPricingModeChange()">
              <option value="fixed_price">Fixed Price (Manual)</option>
              <option value="percentage_markup">Percentage Markup (based on PO cost)</option>
              <option value="fixed_markup">Fixed Amount Markup (based on PO cost)</option>
            </select>
          </div>
          
          <div class="fo-form-grid" id="markupFields" style="display:none;">
            <div class="fo-form-group">
              <label class="fo-label">Last PO Cost (per base unit)</label>
              <input type="number" id="lastPoCost" name="last_po_cost" class="fo-input" 
                step="0.0001" min="0" placeholder="0.00" onchange="calculateSellingPrice()">
              <small style="color:var(--text-muted);font-size:11px;margin-top:4px;display:block;">
                Cost from your last purchase order
              </small>
            </div>
            <div class="fo-form-group">
              <label class="fo-label" id="markupLabel">Markup Value</label>
              <input type="number" id="markupValue" name="markup_value" class="fo-input" 
                step="0.01" min="0" placeholder="0" onchange="calculateSellingPrice()">
              <small style="color:var(--text-muted);font-size:11px;margin-top:4px;display:block;" id="markupHint">
                Enter percentage or fixed amount
              </small>
            </div>
          </div>
          
          <div class="fo-form-group" id="sellingPriceField">
            <label class="fo-label">Selling Price</label>
            <input type="number" id="sellingPrice" name="selling_price" class="fo-input" 
              step="0.0001" min="0" placeholder="0.00">
            <small style="color:var(--text-muted);font-size:11px;margin-top:4px;display:block;" id="sellingPriceHint">
              Final selling price for this UOM
            </small>
          </div>
          
          <div id="calculatedPriceDisplay" style="display:none;margin-top:8px;padding:8px 12px;background:var(--success-bg);border-radius:var(--radius-sm);font-size:12px;color:var(--success);">
            <i class="fas fa-calculator"></i> Calculated: <strong id="calculatedPriceValue">₱0.00</strong>
          </div>
        </div>
        
        <!-- Audit Trail -->
        <div id="auditTrailSection" style="display:none;margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
          <div style="font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">Audit Trail</div>
          <div style="font-size:12px;color:var(--text-secondary);line-height:1.6;">
            <div><strong>Created:</strong> <span id="auditCreatedAt">—</span></div>
            <div><strong>Updated:</strong> <span id="auditUpdatedAt">—</span></div>
          </div>
        </div>
      </div>
      <div class="fo-modal-footer">
        <button type="button" onclick="closeModal()" class="fo-btn-secondary">Cancel</button>
        <button type="submit" class="fo-btn-primary">
          <i class="fas fa-save"></i> Save Conversion
        </button>
      </div>
    </form>
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

<script>
  let products = [];
  let uoms = [];
  let conversions = [];
  let filteredProducts = [];
  let currentPage = 1;
  let rowsPerPage = 10;
  const csrfToken = '<?php echo CSRF_TOKEN; ?>';

  // Load data on page load
  document.addEventListener('DOMContentLoaded', async () => {
    await Promise.all([
      fetchProducts(),
      fetchUoms(),
      fetchConversions()
    ]);
    updateStats();
    filteredProducts = [...products];
    renderTable();
  });

  // Custom alert functions
  function showAlert(title, message, onConfirm) {
    document.getElementById('alertTitle').textContent = title;
    document.getElementById('alertMessage').textContent = message;
    document.getElementById('alertModal').classList.add('open');
    
    const confirmBtn = document.getElementById('alertConfirmBtn');
    confirmBtn.onclick = function() {
      closeAlert();
      if (onConfirm) onConfirm();
    };
  }

  function closeAlert() {
    document.getElementById('alertModal').classList.remove('open');
  }

  async function fetchProducts() {
    try {
      const response = await fetch('../api/products.php?action=list&is_active=1');
      const result = await response.json();
      if (result.success) {
        products = result.data;
        populateProductSelect();
      }
    } catch (error) {
      console.error('Error fetching products:', error);
    }
  }

  async function fetchUoms() {
    try {
      const response = await fetch('../api/units_of_measure.php?action=list&is_active=1');
      const result = await response.json();
      if (result.success) {
        uoms = result.data;
        populateUomSelects();
      }
    } catch (error) {
      console.error('Error fetching UOMs:', error);
    }
  }

  async function fetchConversions() {
    try {
      const response = await fetch('../api/product_uom_conversions.php?action=list');
      const result = await response.json();
      if (result.success) {
        conversions = result.data;
      }
    } catch (error) {
      console.error('Error fetching conversions:', error);
    }
  }

  function updateStats() {
    document.getElementById('statProducts').textContent = products.length;
    document.getElementById('statUoms').textContent = uoms.length;
    document.getElementById('statConversions').textContent = conversions.length;
    document.getElementById('statDefault').textContent = conversions.filter(c => c.is_default == 1).length;
  }

  function populateProductSelect() {
    const list = document.getElementById('productDropdownList');
    list.innerHTML = '';
    
    products.forEach(p => {
      const item = document.createElement('div');
      item.className = 'searchable-dropdown-item';
      item.dataset.value = p.product_id;
      item.dataset.uomId = p.uom_id;
      item.dataset.uomCode = p.uom_code || 'unit';
      item.innerHTML = `
        <div class="product-name">${p.product_name}</div>
        <div class="product-code">${p.product_code || 'No code'} • ${p.uom_code || 'no base UOM'}</div>
      `;
      item.onclick = () => selectProduct(p);
      list.appendChild(item);
    });
  }

  function toggleProductDropdown() {
    const toggle = document.getElementById('productDropdownToggle');
    if (toggle.classList.contains('disabled')) return;
    
    const menu = document.getElementById('productDropdownMenu');
    const isOpen = menu.classList.contains('show');
    
    // Close all other dropdowns
    document.querySelectorAll('.searchable-dropdown-menu').forEach(m => m.classList.remove('show'));
    
    if (!isOpen) {
      menu.classList.add('show');
      setTimeout(() => document.getElementById('productSearchInput').focus(), 50);
    }
  }

  function closeProductDropdown() {
    document.getElementById('productDropdownMenu').classList.remove('show');
  }

  function filterProductDropdown() {
    const searchTerm = document.getElementById('productSearchInput').value.toLowerCase();
    const items = document.querySelectorAll('.searchable-dropdown-item');
    
    items.forEach(item => {
      const text = item.textContent.toLowerCase();
      item.style.display = text.includes(searchTerm) ? '' : 'none';
    });
  }

  function handleSearchKeydown(event) {
    if (event.key === 'Escape') {
      closeProductDropdown();
    }
  }

  function selectProduct(product) {
    document.getElementById('productId').value = product.product_id;
    document.getElementById('productDisplayText').textContent = `${product.product_name} (${product.uom_code || 'no base UOM'})`;
    document.getElementById('productDisplayText').classList.add('selected');
    closeProductDropdown();
    document.getElementById('productSearchInput').value = '';
    filterProductDropdown();
    onProductChange();
  }

  // Close dropdown when clicking outside
  document.addEventListener('click', (e) => {
    if (!e.target.closest('#productDropdown')) {
      closeProductDropdown();
    }
  });

  function populateUomSelects() {
    const fromSelect = document.getElementById('fromUomId');
    const toSelect = document.getElementById('toUomId');
    
    const options = '<option value="">Select UOM</option>' + 
      uoms.map(u => `<option value="${u.uom_id}">${u.uom_name} (${u.uom_code})</option>`).join('');
    
    fromSelect.innerHTML = options;
    toSelect.innerHTML = options;
  }

  function onProductChange() {
    const productId = document.getElementById('productId').value;
    const product = products.find(p => p.product_id === productId);
    if (product) {
      document.getElementById('baseUomDisplay').value = `${product.uom_name || 'Unknown'} (${product.uom_code || 'unit'})`;

      // Auto-select base UOM in "From UOM" dropdown
      if (product.uom_id) {
        document.getElementById('fromUomId').value = product.uom_id;
        updateFactorExample();
      }

      // Auto-generate alternative product code suggestion
      const altCodeField = document.getElementById('altProductCode');
      if (product && !altCodeField.value && !document.getElementById('conversionId').value) {
        const toUom = uoms.find(u => u.uom_id == document.getElementById('toUomId').value);
        if (toUom) {
          altCodeField.value = `${product.product_code}-${toUom.uom_code}`.toUpperCase();
        }
      }

      // Fetch last PO cost for the base UOM
      fetchLastPoCost(productId, product.uom_id);
    }
  }

  async function fetchLastPoCost(productId, uomId) {
    if (!productId || !uomId) return;

    try {
      const response = await fetch(`../api/product_uom_conversions.php?action=last_po_cost&product_id=${productId}&uom_id=${uomId}`);
      const result = await response.json();

      if (result.success && result.data) {
        document.getElementById('lastPoCost').value = result.data.unit_cost;
        calculateSellingPrice();
      }
    } catch (error) {
      console.error('Error fetching last PO cost:', error);
    }
  }

  function onPricingModeChange() {
    const mode = document.getElementById('pricingMode').value;
    const markupFields = document.getElementById('markupFields');
    const sellingPriceField = document.getElementById('sellingPriceField');
    const sellingPriceInput = document.getElementById('sellingPrice');
    const markupLabel = document.getElementById('markupLabel');
    const markupHint = document.getElementById('markupHint');
    const calculatedDisplay = document.getElementById('calculatedPriceDisplay');
    
    if (mode === 'fixed_price') {
      markupFields.style.display = 'none';
      calculatedDisplay.style.display = 'none';
      sellingPriceInput.readOnly = false;
      document.getElementById('sellingPriceHint').textContent = 'Enter manual selling price';
    } else {
      markupFields.style.display = 'grid';
      calculatedDisplay.style.display = 'block';
      sellingPriceInput.readOnly = true;
      document.getElementById('sellingPriceHint').textContent = 'Auto-calculated based on markup';
      
      if (mode === 'percentage_markup') {
        markupLabel.textContent = 'Markup Percentage (%)';
        markupHint.textContent = 'Enter percentage to add to cost (e.g., 20 for 20%)';
      } else {
        markupLabel.textContent = 'Fixed Markup Amount (₱)';
        markupHint.textContent = 'Enter fixed amount to add to cost';
      }
      
      calculateSellingPrice();
    }
  }

  function calculateSellingPrice() {
    const mode = document.getElementById('pricingMode').value;
    const lastPoCost = parseFloat(document.getElementById('lastPoCost').value) || 0;
    const markupValue = parseFloat(document.getElementById('markupValue').value) || 0;
    const conversionFactor = parseFloat(document.getElementById('conversionFactor').value) || 1;
    const sellingPriceInput = document.getElementById('sellingPrice');
    
    let calculatedPrice = 0;
    
    if (mode === 'percentage_markup' && lastPoCost > 0) {
      calculatedPrice = (lastPoCost * conversionFactor) * (1 + (markupValue / 100));
    } else if (mode === 'fixed_markup') {
      calculatedPrice = (lastPoCost * conversionFactor) + markupValue;
    }
    
    if (calculatedPrice > 0) {
      sellingPriceInput.value = calculatedPrice.toFixed(4);
      document.getElementById('calculatedPriceValue').textContent = '₱' + calculatedPrice.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 4});
    }
  }

  // Update factor example when inputs change
  document.getElementById('fromUomId')?.addEventListener('change', updateFactorExample);
  document.getElementById('toUomId')?.addEventListener('change', () => {
    updateFactorExample();
    // Auto-generate alt product code if empty
    const altCodeField = document.getElementById('altProductCode');
    const productId = document.getElementById('productId').value;
    const product = products.find(p => p.product_id === productId);
    const toUom = uoms.find(u => u.uom_id == document.getElementById('toUomId').value);
    if (product && toUom && !altCodeField.value && !document.getElementById('conversionId').value) {
      altCodeField.value = `${product.product_code}-${toUom.uom_code}`.toUpperCase();
    }
  });
  document.getElementById('conversionFactor')?.addEventListener('input', () => {
    updateFactorExample();
    // Recalculate selling price if using markup mode
    const pricingMode = document.getElementById('pricingMode').value;
    if (pricingMode !== 'fixed_price') {
      calculateSellingPrice();
    }
  });

  function updateFactorExample() {
    const fromUomId = document.getElementById('fromUomId').value;
    const toUomId = document.getElementById('toUomId').value;
    const factor = document.getElementById('conversionFactor').value;
    
    const fromUom = uoms.find(u => u.uom_id == fromUomId);
    const toUom = uoms.find(u => u.uom_id == toUomId);
    
    let example = '?';
    if (fromUom && toUom && factor) {
      example = `1 ${fromUom.uom_code} = ${factor} ${toUom.uom_code}`;
    } else if (fromUom && toUom) {
      example = `1 ${fromUom.uom_code} = ? ${toUom.uom_code}`;
    }
    
    document.getElementById('factorExample').textContent = example;
  }

  function renderTable() {
    const tbody = document.getElementById('tableBody');
    
    // Group conversions by product
    const productConversions = {};
    conversions.forEach(c => {
      if (!productConversions[c.product_id]) {
        productConversions[c.product_id] = [];
      }
      productConversions[c.product_id].push(c);
    });
    
    if (filteredProducts.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="4">
            <div class="fo-empty">
              <div class="fo-empty-icon"><i class="fas fa-ruler-combined"></i></div>
              <h4>No products found</h4>
              <p>Add UOM conversions for your products</p>
            </div>
          </td>
        </tr>
      `;
      updatePaginationInfo(0);
      return;
    }
    
    // Paginate
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageData = filteredProducts.slice(start, end);
    
    tbody.innerHTML = pageData.map(product => {
      const productConvs = productConversions[product.product_id] || [];
      const convsHtml = productConvs.length > 0 
        ? productConvs.map(c => `
            <span class="puc-conversion-badge ${c.is_default == 1 ? 'default' : ''}" title="${c.is_default == 1 ? 'Default conversion' : ''}">
              ${c.from_uom_code} → ${c.to_uom_code}: <span class="puc-conversion-factor">${parseFloat(c.conversion_factor).toLocaleString()}</span>
              ${c.alt_product_code ? `<br><small style="font-size:10px;opacity:0.8;">Code: ${c.alt_product_code}</small>` : ''}
              ${c.selling_price ? `<br><small style="font-size:10px;opacity:0.8;">₱${parseFloat(c.selling_price).toFixed(2)}</small>` : ''}
              ${c.is_default == 1 ? '<i class="fas fa-check" style="font-size:10px;margin-left:4px;"></i>' : ''}
              <button onclick="event.stopPropagation();editConversion(${c.conversion_id})" style="background:none;border:none;color:var(--text-muted);cursor:pointer;margin-left:6px;padding:2px;"><i class="fas fa-edit" style="font-size:11px;"></i></button>
              <button onclick="event.stopPropagation();deleteConversion(${c.conversion_id})" style="background:none;border:none;color:var(--danger);cursor:pointer;margin-left:4px;padding:2px;"><i class="fas fa-trash" style="font-size:11px;"></i></button>
            </span>
          `).join('')
        : '<span style="color:var(--text-muted);font-size:12px;">No conversions set</span>';
      
      return `
        <tr class="puc-row">
          <td class="puc-product-cell">
            ${product.product_name}
            <div class="puc-base-uom">Base: ${product.uom_code || 'unit'}</div>
          </td>
          <td>${product.uom_code || '—'}</td>
          <td>${convsHtml}</td>
          <td class="center">
            <button onclick="openAddModal('${product.product_id}')" class="fo-btn-secondary" style="padding:6px 12px;font-size:12px;">
              <i class="fas fa-plus"></i> Add
            </button>
          </td>
        </tr>
      `;
    }).join('');

    updatePaginationInfo(filteredProducts.length);
  }

  function updatePaginationInfo(totalEntries) {
    const totalPages = Math.ceil(totalEntries / rowsPerPage) || 1;
    const start = totalEntries === 0 ? 0 : (currentPage - 1) * rowsPerPage + 1;
    const end = Math.min(currentPage * rowsPerPage, totalEntries);

    document.getElementById('tableInfo').textContent = `Showing ${start}-${end} of ${totalEntries} entries`;
    document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
    document.getElementById('prevBtn').disabled = currentPage === 1;
    document.getElementById('nextBtn').disabled = currentPage === totalPages;
  }

  function changePage(delta) {
    const totalPages = Math.ceil(filteredProducts.length / rowsPerPage) || 1;
    const newPage = currentPage + delta;
    if (newPage >= 1 && newPage <= totalPages) {
      currentPage = newPage;
      renderTable();
    }
  }

  function changeRowsPerPage() {
    rowsPerPage = parseInt(document.getElementById('rowsPerPage').value);
    currentPage = 1;
    renderTable();
  }

  function filterTable() {
    const searchTerm = document.getElementById('searchInput')?.value?.toLowerCase() || '';
    
    // Filter products
    filteredProducts = products.filter(p => {
      const matchesProduct = p.product_name.toLowerCase().includes(searchTerm) ||
        p.product_code?.toLowerCase().includes(searchTerm);
      
      // Also check if any conversion matches the search term
      const productConvs = conversions.filter(c => c.product_id === p.product_id);
      const matchesConversion = productConvs.some(c => 
        c.alt_product_code?.toLowerCase().includes(searchTerm) ||
        c.barcode?.toLowerCase().includes(searchTerm)
      );
      
      return matchesProduct || matchesConversion;
    });
    
    currentPage = 1;
    renderTable();
  }

  function openAddModal(preselectedProductId = null) {
    document.getElementById('conversionForm').reset();
    document.getElementById('conversionId').value = '';
    document.getElementById('modalTitle').textContent = 'Add UOM Conversion';
    
    // Reset searchable dropdown
    const productDisplayText = document.getElementById('productDisplayText');
    productDisplayText.textContent = 'Select Product';
    productDisplayText.classList.remove('selected');
    document.getElementById('productDropdownList').querySelectorAll('.searchable-dropdown-item').forEach(item => {
      item.style.display = '';
    });
    
    // Hide audit trail for new conversions
    document.getElementById('auditTrailSection').style.display = 'none';
    
    if (preselectedProductId) {
      document.getElementById('productId').value = preselectedProductId;
      // Set display text for preselected product
      const product = products.find(p => p.product_id === preselectedProductId);
      if (product) {
        productDisplayText.textContent = `${product.product_name} (${product.uom_code || 'no base UOM'})`;
        productDisplayText.classList.add('selected');
      }
      onProductChange();
      document.getElementById('productDropdownToggle')?.classList.add('disabled');
    } else {
      document.getElementById('productDropdownToggle')?.classList.remove('disabled');
    }
    
    document.getElementById('conversionModal').classList.add('open');
  }

  async function editConversion(conversionId) {
    const conversion = conversions.find(c => c.conversion_id === conversionId);
    if (!conversion) return;
    
    document.getElementById('conversionId').value = conversion.conversion_id;
    document.getElementById('productId').value = conversion.product_id;
    
    // Set product display text for searchable dropdown
    const product = products.find(p => p.product_id === conversion.product_id);
    if (product) {
      document.getElementById('productDisplayText').textContent = `${product.product_name} (${product.uom_code || 'no base UOM'})`;
      document.getElementById('productDisplayText').classList.add('selected');
    }
    
    document.getElementById('fromUomId').value = conversion.from_uom_id;
    document.getElementById('toUomId').value = conversion.to_uom_id;
    document.getElementById('conversionFactor').value = conversion.conversion_factor;
    document.getElementById('isDefault').checked = conversion.is_default == 1;
    
    // Populate new pricing fields
    document.getElementById('altProductCode').value = conversion.alt_product_code || '';
    document.getElementById('barcode').value = conversion.barcode || '';
    document.getElementById('pricingMode').value = conversion.pricing_mode || 'fixed_price';
    document.getElementById('lastPoCost').value = conversion.last_po_cost || '';
    document.getElementById('markupValue').value = conversion.markup_value || '';
    document.getElementById('sellingPrice').value = conversion.selling_price || '';
    
    // Trigger pricing mode change to show/hide markup fields
    onPricingModeChange();
    
    // Show audit trail
    document.getElementById('auditTrailSection').style.display = 'block';
    document.getElementById('auditCreatedAt').textContent = conversion.created_at ? new Date(conversion.created_at).toLocaleString() : '—';
    document.getElementById('auditUpdatedAt').textContent = conversion.updated_at ? new Date(conversion.updated_at).toLocaleString() : '—';
    
    onProductChange();
    document.getElementById('productId').disabled = true;
    document.getElementById('productDropdownToggle').classList.add('disabled');
    document.getElementById('modalTitle').textContent = 'Edit UOM Conversion';
    document.getElementById('conversionModal').classList.add('open');
  }

  function deleteConversion(conversionId) {
    showAlert(
      'Delete Conversion',
      'Are you sure you want to delete this UOM conversion?',
      () => confirmDeleteConversion(conversionId)
    );
  }

  async function confirmDeleteConversion(conversionId) {
    try {
      const response = await fetch('../api/product_uom_conversions.php?action=delete', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify({ conversion_id: conversionId })
      });
      
      const result = await response.json();
      
      if (result.success) {
        showAlert('Success', 'Conversion deleted successfully');
        await fetchConversions();
        updateStats();
        renderTable();
      } else {
        showAlert('Error', result.message || 'Failed to delete conversion');
      }
    } catch (error) {
      console.error('Error deleting conversion:', error);
      showAlert('Error', 'An error occurred while deleting.');
    }
  }

  function closeModal() {
    document.getElementById('conversionModal').classList.remove('open');
    document.getElementById('productId').disabled = false;
    document.getElementById('productDropdownToggle')?.classList.remove('disabled');
    closeProductDropdown();
  }

  async function saveConversion(event) {
    event.preventDefault();
    
    const formData = {
      conversion_id: document.getElementById('conversionId').value,
      product_id: document.getElementById('productId').value,
      from_uom_id: document.getElementById('fromUomId').value,
      to_uom_id: document.getElementById('toUomId').value,
      conversion_factor: parseFloat(document.getElementById('conversionFactor').value),
      is_default: document.getElementById('isDefault').checked ? 1 : 0,
      alt_product_code: document.getElementById('altProductCode').value || null,
      barcode: document.getElementById('barcode').value || null,
      pricing_mode: document.getElementById('pricingMode').value,
      last_po_cost: parseFloat(document.getElementById('lastPoCost').value) || null,
      markup_value: parseFloat(document.getElementById('markupValue').value) || null,
      selling_price: parseFloat(document.getElementById('sellingPrice').value) || null
    };
    
    try {
      const action = formData.conversion_id ? 'update' : 'create';
      const response = await fetch(`../api/product_uom_conversions.php?action=${action}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify(formData)
      });
      
      const result = await response.json();
      
      if (result.success) {
        showAlert('Success', result.message);
        closeModal();
        await fetchConversions();
        updateStats();
        renderTable();
      } else {
        showAlert('Error', result.message || 'Failed to save conversion');
      }
    } catch (error) {
      console.error('Error saving conversion:', error);
      showAlert('Error', 'An error occurred while saving.');
    }
  }
</script>
