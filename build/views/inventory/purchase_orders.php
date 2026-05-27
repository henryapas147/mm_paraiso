<?php if (false) {
} ?>
<style>
  @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=DM+Mono:wght@400;500&display=swap');

  :root {
    --po-blue:        #1A6BBF;
    --po-blue-dark:   #0F4C8A;
    --po-blue-pale:   #EBF3FC;
    --po-green:       #2E7D32;
    --po-green-pale:  #E8F5E9;
    --po-red:         #C62828;
    --po-red-pale:    #FFEBEE;
    --po-amber:       #E65100;
    --po-amber-pale:  #FFF3E0;
    --po-purple:      #6230B5;
    --po-purple-pale: #F5F0FF;
    --po-slate:       #475569;
    --po-slate-pale:  #F1F5F9;
    --po-r-sm: 6px; --po-r-md: 10px; --po-r-lg: 14px; --po-r-xl: 20px;
    --po-shadow-sm: 0 1px 3px rgba(0,0,0,.07), 0 1px 2px rgba(0,0,0,.05);
    --po-shadow-md: 0 4px 12px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.05);
    --po-shadow-lg: 0 20px 48px rgba(0,0,0,.14), 0 8px 16px rgba(0,0,0,.07);
    --po-font: 'DM Sans', sans-serif;
    --po-mono: 'DM Mono', monospace;
  }

  /* ── Page ─────────────────────────────────────────── */
  .po-page {
    padding: 28px 28px 48px;
    background: var(--color-background-tertiary, #F4F5F7);
    min-height: 100%;
    border-radius: var(--border-radius-xl, 18px);
    font-family: var(--po-font);
  }

  /* ── Header ──────────────────────────────────────── */
  .po-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
    gap: 16px;
    flex-wrap: wrap;
  }

  .po-eyebrow {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--po-blue);
    margin-bottom: 4px;
  }

  .po-page-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.4px;
    line-height: 1.2;
  }

  .po-page-subtitle {
    font-size: 13px;
    color: var(--color-text-secondary);
    margin-top: 3px;
  }

  /* ── Stat Cards ──────────────────────────────────── */
  .po-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
  }

  .po-stat-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0,0,0,.07);
    border-radius: var(--po-r-lg);
    padding: 20px 22px;
    box-shadow: var(--po-shadow-sm);
    position: relative;
    overflow: hidden;
    transition: box-shadow .2s, transform .2s;
  }

  .po-stat-card:hover { box-shadow: var(--po-shadow-md); transform: translateY(-1px); }

  .po-stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: var(--po-r-lg) var(--po-r-lg) 0 0;
    background: var(--_accent, #CBD5E1);
  }

  .po-stat-card.blue   { --_accent: var(--po-blue); }
  .po-stat-card.amber  { --_accent: var(--po-amber); }
  .po-stat-card.green  { --_accent: var(--po-green); }
  .po-stat-card.red    { --_accent: var(--po-red); }
  .po-stat-card.purple { --_accent: var(--po-purple); }

  .po-stat-icon {
    width: 36px; height: 36px;
    border-radius: var(--po-r-sm);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px;
    margin-bottom: 14px;
    background: var(--po-blue-pale);
    color: var(--po-blue);
  }

  .po-stat-card.amber  .po-stat-icon { background: var(--po-amber-pale);  color: var(--po-amber); }
  .po-stat-card.green  .po-stat-icon { background: var(--po-green-pale);  color: var(--po-green); }
  .po-stat-card.red    .po-stat-icon { background: var(--po-red-pale);    color: var(--po-red); }
  .po-stat-card.purple .po-stat-icon { background: var(--po-purple-pale); color: var(--po-purple); }

  .po-stat-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-secondary);
    margin-bottom: 6px;
  }

  .po-stat-value {
    font-size: 32px;
    font-weight: 600;
    color: var(--color-text-primary);
    font-family: var(--po-mono);
    letter-spacing: -.5px;
    line-height: 1;
  }

  .po-stat-value.blue   { color: var(--po-blue); }
  .po-stat-value.amber  { color: var(--po-amber); }
  .po-stat-value.green  { color: var(--po-green); }
  .po-stat-value.red    { color: var(--po-red); }
  .po-stat-value.purple { color: var(--po-purple); }

  /* ── Card ────────────────────────────────────────── */
  .po-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0,0,0,.07);
    border-radius: var(--po-r-lg);
    box-shadow: var(--po-shadow-sm);
    overflow: hidden;
  }

  .po-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid rgba(0,0,0,.06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }

  .po-card-title { font-size: 15px; font-weight: 600; color: var(--color-text-primary); letter-spacing: -.2px; }
  .po-card-count { font-size: 12px; color: var(--color-text-secondary); margin-top: 2px; }

  /* ── Toolbar ─────────────────────────────────────── */
  .po-toolbar { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

  .po-search-wrap { position: relative; }
  .po-search-wrap i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--color-text-secondary); font-size: 12px; pointer-events: none; }

  .po-search {
    padding: 8px 14px 8px 34px;
    font-size: 13px;
    border: 1px solid rgba(0,0,0,.12);
    border-radius: var(--po-r-md);
    background: var(--color-background-secondary, #F8FAFC);
    color: var(--color-text-primary);
    outline: none;
    width: 230px;
    font-family: var(--po-font);
    transition: border-color .15s, box-shadow .15s;
  }

  .po-search:focus {
    border-color: var(--po-blue);
    box-shadow: 0 0 0 3px rgba(26,107,191,.12);
    background: var(--color-background-primary, #fff);
  }

  /* ── Filter tabs ─────────────────────────────────── */
  .po-filter-tabs {
    display: flex;
    gap: 4px;
    background: var(--color-background-secondary, #F1F5F9);
    border-radius: var(--po-r-md);
    padding: 3px;
  }

  .po-filter-tab {
    padding: 5px 13px;
    font-size: 12px;
    font-weight: 500;
    border-radius: 7px;
    border: none;
    background: transparent;
    color: var(--color-text-secondary);
    cursor: pointer;
    transition: all .15s;
    font-family: var(--po-font);
    white-space: nowrap;
  }

  .po-filter-tab.active {
    background: var(--color-background-primary, #fff);
    color: var(--color-text-primary);
    box-shadow: var(--po-shadow-sm);
    font-weight: 600;
  }

  /* ── Buttons ─────────────────────────────────────── */
  .po-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 500;
    border-radius: var(--po-r-md);
    border: 1px solid rgba(0,0,0,.12);
    background: var(--color-background-primary, #fff);
    color: var(--color-text-primary);
    cursor: pointer;
    transition: all .15s;
    font-family: var(--po-font);
    line-height: 1;
  }

  .po-btn:hover { background: var(--color-background-secondary, #F8FAFC); }

  .po-btn-primary {
    background: var(--po-blue);
    color: #fff;
    border-color: var(--po-blue);
    box-shadow: 0 1px 3px rgba(26,107,191,.3);
  }

  .po-btn-primary:hover {
    background: var(--po-blue-dark);
    border-color: var(--po-blue-dark);
    box-shadow: 0 2px 6px rgba(26,107,191,.35);
  }

  /* ── Table ───────────────────────────────────────── */
  .po-table-wrap { overflow-x: auto; }

  .po-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    font-family: var(--po-font);
  }

  .po-table thead tr { background: var(--color-background-secondary, #F8FAFC); }

  .po-table th {
    padding: 11px 18px;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .08em;
    border-bottom: 1px solid rgba(0,0,0,.07);
    white-space: nowrap;
  }

  .po-table th.right { text-align: right; }
  .po-table th.center { text-align: center; }

  .po-table tbody tr {
    border-bottom: 1px solid rgba(0,0,0,.05);
    transition: background .12s;
  }

  .po-table tbody tr:last-child { border-bottom: none; }
  .po-table tbody tr:hover { background: var(--color-background-secondary, #F8FAFC); }

  .po-table td { padding: 13px 18px; vertical-align: middle; white-space: nowrap; }
  .po-table td.right { text-align: right; }
  .po-table td.center { text-align: center; }

  /* PO number cell */
  .po-name-cell { display: flex; align-items: center; }

  .po-avatar {
    width: 36px; height: 36px;
    border-radius: var(--po-r-sm);
    background: var(--po-blue-pale);
    color: var(--po-blue);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
    margin-right: 10px;
  }

  .po-name-primary { font-size: 13px; font-weight: 600; color: var(--color-text-primary); font-family: var(--po-mono); }
  .po-name-secondary { font-size: 11px; color: var(--color-text-secondary); margin-top: 2px; font-family: var(--po-font); }

  /* Amount */
  .po-amount { font-size: 13px; font-weight: 600; color: var(--color-text-primary); font-family: var(--po-mono); }
  .po-amount-currency { font-size: 11px; color: var(--color-text-secondary); margin-right: 1px; font-family: var(--po-font); font-weight: 400; }

  /* Status badge */
  .po-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .03em;
    white-space: nowrap;
  }

  .po-badge::before {
    content: '';
    width: 5px; height: 5px;
    border-radius: 50%;
    background: currentColor;
    opacity: .7;
    flex-shrink: 0;
  }

  .po-badge.draft            { background: var(--po-slate-pale);   color: var(--po-slate); }
  .po-badge.submitted        { background: var(--po-blue-pale);    color: var(--po-blue); }
  .po-badge.approved         { background: #E0F2FE;                color: #0369A1; }
  .po-badge.partially_received { background: var(--po-amber-pale); color: var(--po-amber); }
  .po-badge.fully_received   { background: var(--po-green-pale);   color: var(--po-green); }
  .po-badge.cancelled        { background: var(--po-red-pale);     color: var(--po-red); }
  .po-badge.closed           { background: var(--po-slate-pale);   color: var(--po-slate); }

  /* Action buttons */
  .po-actions { display: flex; align-items: center; gap: 5px; }

  .po-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px;
    font-size: 12px;
    font-weight: 500;
    border-radius: var(--po-r-sm);
    border: 1px solid rgba(0,0,0,.1);
    background: transparent;
    cursor: pointer;
    transition: all .15s;
    color: var(--color-text-secondary);
    font-family: var(--po-font);
  }

  .po-action-btn.view:hover  { background: var(--po-blue-pale);  color: var(--po-blue);  border-color: rgba(26,107,191,.2); }
  .po-action-btn.edit:hover  { background: var(--po-amber-pale); color: var(--po-amber); border-color: rgba(230,81,0,.2); }
  .po-action-btn.danger:hover{ background: var(--po-red-pale);   color: var(--po-red);   border-color: rgba(198,40,40,.2); }

  /* Empty state */
  .po-empty { padding: 64px 24px; text-align: center; }
  .po-empty-icon { font-size: 32px; opacity: .2; margin-bottom: 12px; }
  .po-empty-text { font-size: 14px; font-weight: 500; color: var(--color-text-secondary); }
  .po-empty-sub  { font-size: 12px; color: var(--color-text-secondary); opacity: .7; margin-top: 4px; }

  /* ── Pagination ──────────────────────────────────── */
  .po-pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 22px;
    border-top: 1px solid rgba(0,0,0,.06);
    gap: 16px;
    flex-wrap: wrap;
  }

  .po-pagination-left { display: flex; align-items: center; gap: 10px; }
  .po-pagination-info { font-size: 12px; color: var(--color-text-secondary); }

  .po-entries-wrap { display: flex; align-items: center; gap: 6px; }
  .po-entries-label { font-size: 12px; color: var(--color-text-secondary); }

  .po-entries-select {
    padding: 5px 28px 5px 10px;
    font-size: 12px;
    border: 1px solid rgba(0,0,0,.12);
    border-radius: var(--po-r-sm);
    background: var(--color-background-secondary, #F8FAFC);
    color: var(--color-text-primary);
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748B' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    font-family: var(--po-font);
    outline: none;
  }

  .po-entries-select:focus { border-color: var(--po-blue); box-shadow: 0 0 0 3px rgba(26,107,191,.12); }

  .po-pagination { display: flex; align-items: center; gap: 4px; }

  .po-page-btn {
    min-width: 32px; height: 32px;
    padding: 0 8px;
    border: 1px solid rgba(0,0,0,.12);
    border-radius: var(--po-r-sm);
    background: var(--color-background-primary, #fff);
    color: var(--color-text-primary);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all .15s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-family: var(--po-font);
  }

  .po-page-btn:hover:not(:disabled) { background: var(--po-blue-pale); border-color: var(--po-blue); color: var(--po-blue); }
  .po-page-btn.active { background: var(--po-blue); border-color: var(--po-blue); color: #fff; }
  .po-page-btn:disabled { opacity: .35; cursor: not-allowed; }

  /* ── Modals ──────────────────────────────────────── */
  .po-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,.5);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 9000;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }

  .po-overlay.open { display: flex; }

  .po-modal {
    background: var(--color-background-primary, #fff);
    border-radius: var(--po-r-xl);
    border: 1px solid rgba(0,0,0,.08);
    width: 100%;
    max-width: 720px;
    max-height: 92vh;
    overflow-y: auto;
    box-shadow: var(--po-shadow-lg);
    animation: poModalIn .2s cubic-bezier(.22,.68,0,1.2);
  }

  .po-modal.wide { max-width: 900px; }

  @keyframes poModalIn {
    from { opacity: 0; transform: translateY(14px) scale(.98); }
    to   { opacity: 1; transform: none; }
  }

  .po-modal-header {
    padding: 22px 26px 18px;
    border-bottom: 1px solid rgba(0,0,0,.06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    position: sticky;
    top: 0;
    background: var(--color-background-primary, #fff);
    border-radius: var(--po-r-xl) var(--po-r-xl) 0 0;
    z-index: 1;
  }

  .po-modal-title { font-size: 17px; font-weight: 600; color: var(--color-text-primary); letter-spacing: -.3px; }
  .po-modal-subtitle { font-size: 12px; color: var(--color-text-secondary); margin-top: 2px; }

  .po-modal-close {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: var(--color-background-secondary, #F1F5F9);
    border: none;
    cursor: pointer;
    color: var(--color-text-secondary);
    font-size: 14px;
    display: flex; align-items: center; justify-content: center;
    transition: all .15s;
    flex-shrink: 0;
  }

  .po-modal-close:hover { background: var(--po-red-pale); color: var(--po-red); }

  .po-modal-body { padding: 26px; }

  .po-modal-footer {
    padding: 18px 26px;
    border-top: 1px solid rgba(0,0,0,.06);
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    position: sticky;
    bottom: 0;
    background: var(--color-background-primary, #fff);
    border-radius: 0 0 var(--po-r-xl) var(--po-r-xl);
  }

  /* ── Form grid ───────────────────────────────────── */
  .po-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
  }

  .po-form-group { display: flex; flex-direction: column; gap: 6px; }
  .po-form-group.full { grid-column: 1 / -1; }

  .po-form-section {
    grid-column: 1 / -1;
    font-size: 11px;
    font-weight: 700;
    color: var(--po-blue);
    text-transform: uppercase;
    letter-spacing: .1em;
    padding-top: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .po-form-divider { grid-column: 1 / -1; border: none; border-top: 1px solid rgba(0,0,0,.06); margin: 2px 0; }

  .po-form-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .07em;
  }

  .po-form-label .req { color: var(--po-red); margin-left: 2px; }

  .po-form-input,
  .po-form-select,
  .po-form-textarea {
    font-family: var(--po-font);
    font-size: 13.5px;
    color: var(--color-text-primary);
    background: var(--color-background-secondary, #F8FAFC);
    border: 1px solid rgba(0,0,0,.12);
    border-radius: var(--po-r-md);
    padding: 9px 13px;
    outline: none;
    width: 100%;
    transition: border-color .15s, box-shadow .15s, background .15s;
  }

  .po-form-input:focus,
  .po-form-select:focus,
  .po-form-textarea:focus {
    border-color: var(--po-blue);
    box-shadow: 0 0 0 3px rgba(26,107,191,.12);
    background: var(--color-background-primary, #fff);
  }

  .po-form-textarea { resize: vertical; min-height: 72px; line-height: 1.5; }

  /* ── Details view ────────────────────────────────── */
  .po-detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 28px; }

  .po-detail-section-title {
    font-size: 11px;
    font-weight: 700;
    color: var(--po-blue);
    text-transform: uppercase;
    letter-spacing: .1em;
    margin-bottom: 14px;
    padding-bottom: 8px;
    border-bottom: 2px solid var(--po-blue-pale);
  }

  .po-detail-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    gap: 12px;
    padding: 7px 0;
    border-bottom: 1px solid rgba(0,0,0,.04);
  }

  .po-detail-row:last-child { border-bottom: none; }
  .po-detail-key { font-size: 12px; color: var(--color-text-secondary); font-weight: 500; flex-shrink: 0; }
  .po-detail-val { font-size: 13px; color: var(--color-text-primary); font-weight: 500; text-align: right; }
  .po-detail-val.big { font-size: 22px; font-family: var(--po-mono); letter-spacing: -.5px; }

  /* ── Responsive ──────────────────────────────────── */
  @media (max-width: 600px) {
    .po-form-grid, .po-detail-grid { grid-template-columns: 1fr; }
    .po-form-group.full, .po-form-section, .po-form-divider { grid-column: 1; }
    .po-search { width: 160px; }
    .po-filter-tabs { display: none; }
  }
</style>

<div class="po-page">

  <!-- Header -->
  <div class="po-page-header">
    <div>
      <div class="po-eyebrow"><i class="fas fa-file-invoice" style="margin-right:5px"></i>Procurement</div>
      <div class="po-page-title">Purchase Orders</div>
      <div class="po-page-subtitle">Track and manage supplier purchase orders</div>
    </div>
  </div>

  <!-- Stats -->
  <div class="po-stats-grid">
    <div class="po-stat-card blue">
      <div class="po-stat-icon"><i class="fas fa-layer-group"></i></div>
      <div class="po-stat-label">Total Orders</div>
      <div class="po-stat-value blue" id="statTotal">—</div>
    </div>
    <div class="po-stat-card amber">
      <div class="po-stat-icon"><i class="fas fa-clock"></i></div>
      <div class="po-stat-label">Pending</div>
      <div class="po-stat-value amber" id="statPending">—</div>
    </div>
    <div class="po-stat-card green">
      <div class="po-stat-icon"><i class="fas fa-circle-check"></i></div>
      <div class="po-stat-label">Received</div>
      <div class="po-stat-value green" id="statReceived">—</div>
    </div>
    <div class="po-stat-card red">
      <div class="po-stat-icon"><i class="fas fa-peso-sign"></i></div>
      <div class="po-stat-label">Unpaid</div>
      <div class="po-stat-value red" id="statUnpaid">—</div>
    </div>
  </div>

  <!-- Table Card -->
  <div class="po-card">
    <div class="po-card-header">
      <div>
        <div class="po-card-title">All Purchase Orders</div>
        <div class="po-card-count" id="poTableCount">Loading…</div>
      </div>
      <div class="po-toolbar">
        <!-- Filter Tabs -->
        <div class="po-filter-tabs">
          <button class="po-filter-tab active" onclick="setFilter('all', this)">All</button>
          <button class="po-filter-tab" onclick="setFilter('pending', this)">Pending</button>
          <button class="po-filter-tab" onclick="setFilter('received', this)">Received</button>
          <button class="po-filter-tab" onclick="setFilter('cancelled', this)">Cancelled</button>
        </div>
        <!-- Search -->
        <div class="po-search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" class="po-search" id="poSearchInput" placeholder="Search orders…" oninput="applyFilters()">
        </div>
        <!-- New Order -->
        <button class="po-btn po-btn-primary" onclick="openAddPOModal()">
          <i class="fas fa-plus"></i> New Order
        </button>
      </div>
    </div>

    <!-- Table -->
    <div class="po-table-wrap">
      <table class="po-table" id="poTable">
        <thead>
          <tr>
            <th>PO Number</th>
            <th>Supplier</th>
            <th>Property</th>
            <th>PO Date</th>
            <th>Expected Delivery</th>
            <th class="right">Total</th>
            <th class="center">Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="poTableBody">
          <tr><td colspan="8">
            <div class="po-empty">
              <div class="po-empty-icon"><i class="fas fa-spinner fa-spin"></i></div>
              <div class="po-empty-text">Loading purchase orders…</div>
            </div>
          </td></tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="po-pagination-wrap">
      <div class="po-pagination-left">
        <div class="po-entries-wrap">
          <span class="po-entries-label">Show</span>
          <select class="po-entries-select" id="poEntriesSelect" onchange="changeEntries(this.value)">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="po-entries-label">entries</span>
        </div>
        <span class="po-pagination-info" id="poPaginationInfo"></span>
      </div>
      <div class="po-pagination" id="poPaginationBtns"></div>
    </div>
  </div>
</div>

<!-- ── View PO Details Modal ─────────────────────── -->
<div class="po-overlay" id="poDetailsModal" onclick="if(event.target===this)closePODetailsModal()">
  <div class="po-modal wide" role="dialog" aria-modal="true">
    <div class="po-modal-header">
      <div>
        <div class="po-modal-title" id="poModalTitle">Purchase Order</div>
        <div class="po-modal-subtitle" id="poModalSubtitle"></div>
      </div>
      <button class="po-modal-close" onclick="closePODetailsModal()" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>
    <div class="po-modal-body" id="poModalContent"></div>
  </div>
</div>

<!-- ── Add PO Modal ───────────────────────────────── -->
<div class="po-overlay" id="addPOModal" onclick="if(event.target===this)closeAddPOModal()">
  <div class="po-modal wide" role="dialog" aria-modal="true">
    <div class="po-modal-header">
      <div>
        <div class="po-modal-title">Create Purchase Order</div>
        <div class="po-modal-subtitle">Fill in the details to create a new purchase order</div>
      </div>
      <button class="po-modal-close" onclick="closeAddPOModal()" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>
    <form id="addPOForm" onsubmit="submitAddPO(event)">
      <div class="po-modal-body">
        <div class="po-form-grid">
          <div class="po-form-section"><i class="fas fa-info-circle" style="opacity:.6"></i> Order Information</div>

          <div class="po-form-group">
            <label class="po-form-label">PO Number <span class="req">*</span></label>
            <input type="text" name="po_number" required class="po-form-input" placeholder="e.g., PO-2024-000001">
          </div>
          <div class="po-form-group">
            <label class="po-form-label">PO Date <span class="req">*</span></label>
            <input type="date" name="po_date" required class="po-form-input">
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Property <span class="req">*</span></label>
            <select name="property_id" required class="po-form-select"><option value="">Select Property</option></select>
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Supplier <span class="req">*</span></label>
            <select name="supplier_id" required class="po-form-select"><option value="">Select Supplier</option></select>
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Expected Delivery</label>
            <input type="date" name="expected_delivery" class="po-form-input">
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Status</label>
            <select name="po_status" class="po-form-select">
              <option value="draft">Draft</option>
              <option value="submitted">Submitted</option>
              <option value="approved">Approved</option>
            </select>
          </div>

          <hr class="po-form-divider">
          <div class="po-form-section"><i class="fas fa-credit-card" style="opacity:.6"></i> Payment & Delivery</div>

          <div class="po-form-group">
            <label class="po-form-label">Payment Terms</label>
            <input type="text" name="payment_terms" class="po-form-input" placeholder="e.g., Net 30, COD">
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Delivery Address</label>
            <input type="text" name="delivery_address" class="po-form-input" placeholder="Delivery address">
          </div>
          <div class="po-form-group full">
            <label class="po-form-label">Notes</label>
            <textarea name="notes" rows="2" class="po-form-textarea" placeholder="Additional notes…"></textarea>
          </div>
        </div>
      </div>
      <div class="po-modal-footer">
        <button type="button" class="po-btn" onclick="closeAddPOModal()"><i class="fas fa-times"></i> Cancel</button>
        <button type="submit" class="po-btn po-btn-primary"><i class="fas fa-save"></i> Create Order</button>
      </div>
    </form>
  </div>
</div>

<!-- ── Edit PO Modal ──────────────────────────────── -->
<div class="po-overlay" id="editPOModal" onclick="if(event.target===this)closeEditPOModal()">
  <div class="po-modal wide" role="dialog" aria-modal="true">
    <div class="po-modal-header">
      <div>
        <div class="po-modal-title">Edit Purchase Order</div>
        <div class="po-modal-subtitle" id="editPOModalSubtitle">Update the purchase order details</div>
      </div>
      <button class="po-modal-close" onclick="closeEditPOModal()" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>
    <form id="editPOForm" onsubmit="submitEditPO(event)">
      <div class="po-modal-body">
        <input type="hidden" name="po_id" id="editPOId">
        <div class="po-form-grid">
          <div class="po-form-section"><i class="fas fa-info-circle" style="opacity:.6"></i> Order Information</div>

          <div class="po-form-group">
            <label class="po-form-label">PO Number <span class="req">*</span></label>
            <input type="text" name="po_number" required class="po-form-input" placeholder="e.g., PO-2024-000001">
          </div>
          <div class="po-form-group">
            <label class="po-form-label">PO Date <span class="req">*</span></label>
            <input type="date" name="po_date" required class="po-form-input">
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Property <span class="req">*</span></label>
            <select name="property_id" required class="po-form-select"><option value="">Select Property</option></select>
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Supplier <span class="req">*</span></label>
            <select name="supplier_id" required class="po-form-select"><option value="">Select Supplier</option></select>
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Expected Delivery</label>
            <input type="date" name="expected_delivery" class="po-form-input">
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Status</label>
            <select name="po_status" class="po-form-select">
              <option value="draft">Draft</option>
              <option value="submitted">Submitted</option>
              <option value="approved">Approved</option>
              <option value="partially_received">Partially Received</option>
              <option value="fully_received">Fully Received</option>
              <option value="cancelled">Cancelled</option>
              <option value="closed">Closed</option>
            </select>
          </div>

          <hr class="po-form-divider">
          <div class="po-form-section"><i class="fas fa-credit-card" style="opacity:.6"></i> Payment & Delivery</div>

          <div class="po-form-group">
            <label class="po-form-label">Payment Terms</label>
            <input type="text" name="payment_terms" class="po-form-input" placeholder="e.g., Net 30, COD">
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Delivery Address</label>
            <input type="text" name="delivery_address" class="po-form-input" placeholder="Delivery address">
          </div>
          <div class="po-form-group full">
            <label class="po-form-label">Notes</label>
            <textarea name="notes" rows="2" class="po-form-textarea" placeholder="Additional notes…"></textarea>
          </div>
        </div>
      </div>
      <div class="po-modal-footer">
        <button type="button" class="po-btn" onclick="closeEditPOModal()"><i class="fas fa-times"></i> Cancel</button>
        <button type="submit" class="po-btn po-btn-primary"><i class="fas fa-save"></i> Update Order</button>
      </div>
    </form>
  </div>
</div>

<script>
  let purchaseOrdersData = [];
  let suppliersData      = [];
  let propertiesData     = [];

  // Pagination & filter state
  let currentPage    = 1;
  let entriesPerPage = 10;
  let activeFilter   = 'all';
  let filteredData   = [];

  /* ── Data fetching ─────────────────────────── */
  async function fetchPurchaseOrders() {
    try {
      const res  = await fetch('../api/purchase_orders.php?action=list');
      const json = await res.json();
      if (json.success) {
        purchaseOrdersData = json.data;
        renderStats();
        applyFilters();
      }
    } catch (e) { console.error('Error fetching purchase orders:', e); }
  }

  async function fetchRelated() {
    try {
      const res  = await fetch('../api/purchase_orders.php?action=related');
      const json = await res.json();
      if (json.success) {
        suppliersData  = json.data.suppliers;
        propertiesData = json.data.properties;
        populateDropdowns();
      }
    } catch (e) { console.error('Error fetching related data:', e); }
  }

  function renderStats() {
    const pending  = purchaseOrdersData.filter(o => ['draft','submitted','approved'].includes(o.po_status));
    const received = purchaseOrdersData.filter(o => ['partially_received','fully_received'].includes(o.po_status));
    const unpaid   = purchaseOrdersData.filter(o => ['unpaid','partial'].includes(o.payment_status));
    document.getElementById('statTotal').textContent    = purchaseOrdersData.length;
    document.getElementById('statPending').textContent  = pending.length;
    document.getElementById('statReceived').textContent = received.length;
    document.getElementById('statUnpaid').textContent   = unpaid.length;
  }

  function populateDropdowns() {
    const propOpts = '<option value="">Select Property</option>' +
      propertiesData.map(p => `<option value="${p.property_id}">${p.property_name}</option>`).join('');
    const supOpts  = '<option value="">Select Supplier</option>' +
      suppliersData.map(s => `<option value="${s.supplier_id}">${s.company_name}</option>`).join('');
    document.querySelectorAll('select[name="property_id"]').forEach(s => s.innerHTML = propOpts);
    document.querySelectorAll('select[name="supplier_id"]').forEach(s => s.innerHTML = supOpts);
  }

  /* ── Filter & Search ───────────────────────── */
  function setFilter(filter, btn) {
    activeFilter = filter;
    document.querySelectorAll('.po-filter-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    currentPage = 1;
    applyFilters();
  }

  function applyFilters() {
    const q = (document.getElementById('poSearchInput').value || '').toLowerCase();
    const pending  = ['draft','submitted','approved'];
    const received = ['partially_received','fully_received'];

    filteredData = purchaseOrdersData.filter(po => {
      const matchFilter =
        activeFilter === 'all'       ? true :
        activeFilter === 'pending'   ? pending.includes(po.po_status) :
        activeFilter === 'received'  ? received.includes(po.po_status) :
        activeFilter === 'cancelled' ? po.po_status === 'cancelled' : true;

      const matchSearch = !q || [po.po_number, po.company_name, po.property_name, po.po_status]
        .some(v => v && v.toLowerCase().includes(q));

      return matchFilter && matchSearch;
    });

    renderTable();
    renderPagination();
  }

  /* ── Table render ──────────────────────────── */
  function fmtDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
  }

  function badgeClass(status) {
    const map = {
      draft: 'draft', submitted: 'submitted', approved: 'approved',
      partially_received: 'partially_received', fully_received: 'fully_received',
      cancelled: 'cancelled', closed: 'closed'
    };
    return map[status] || 'draft';
  }

  function badgeLabel(status) {
    return (status || '').replace(/_/g, ' ');
  }

  function renderTable() {
    const tbody = document.getElementById('poTableBody');
    const count = document.getElementById('poTableCount');

    if (!filteredData.length) {
      count.textContent = '0 orders';
      tbody.innerHTML = `<tr><td colspan="8">
        <div class="po-empty">
          <div class="po-empty-icon"><i class="fas fa-file-invoice"></i></div>
          <div class="po-empty-text">No purchase orders found</div>
          <div class="po-empty-sub"><a href="javascript:;" onclick="openAddPOModal()" style="color:var(--po-blue);font-weight:500">Create the first order</a></div>
        </div>
      </td></tr>`;
      return;
    }

    const total = filteredData.length;
    const start = (currentPage - 1) * entriesPerPage;
    const end   = Math.min(start + entriesPerPage, total);
    const page  = filteredData.slice(start, end);

    count.textContent = `${total} order${total !== 1 ? 's' : ''}`;

    tbody.innerHTML = page.map(po => `
      <tr class="po-row">
        <td>
          <div class="po-name-cell">
            <div class="po-avatar"><i class="fas fa-file-alt"></i></div>
            <div>
              <div class="po-name-primary">${po.po_number}</div>
              <div class="po-name-secondary">${fmtDate(po.po_date)}</div>
            </div>
          </div>
        </td>
        <td>
          <div class="po-name-primary" style="font-family:var(--po-font)">${po.company_name || '—'}</div>
        </td>
        <td>
          <div class="po-name-secondary" style="font-size:13px;color:var(--color-text-primary)">${po.property_name || '—'}</div>
        </td>
        <td><span style="font-size:13px;color:var(--color-text-secondary)">${fmtDate(po.po_date)}</span></td>
        <td><span style="font-size:13px;color:var(--color-text-secondary)">${fmtDate(po.expected_delivery)}</span></td>
        <td class="right">
          <span class="po-amount"><span class="po-amount-currency">PHP</span>${parseFloat(po.total_amount||0).toFixed(2)}</span>
        </td>
        <td class="center">
          <span class="po-badge ${badgeClass(po.po_status)}">${badgeLabel(po.po_status)}</span>
        </td>
        <td>
          <div class="po-actions">
            <button onclick="viewPODetails('${po.po_id}')" class="po-action-btn view"><i class="fas fa-eye"></i> View</button>
            <button onclick="openEditPOModal('${po.po_id}')" class="po-action-btn edit"><i class="fas fa-pen"></i> Edit</button>
            <button onclick="deletePO(this)" data-id="${po.po_id}" data-number="${(po.po_number||'').replace(/"/g,'&quot;')}" class="po-action-btn danger"><i class="fas fa-trash"></i></button>
          </div>
        </td>
      </tr>`).join('');
  }

  /* ── Pagination ────────────────────────────── */
  function renderPagination() {
    const total    = filteredData.length;
    const pages    = Math.ceil(total / entriesPerPage);
    const start    = total ? (currentPage - 1) * entriesPerPage + 1 : 0;
    const end      = Math.min(currentPage * entriesPerPage, total);
    const info     = document.getElementById('poPaginationInfo');
    const btnWrap  = document.getElementById('poPaginationBtns');

    info.textContent = total ? `Showing ${start}–${end} of ${total}` : '';

    if (pages <= 1) { btnWrap.innerHTML = ''; return; }

    let btns = `<button class="po-page-btn" onclick="goPage(${currentPage-1})" ${currentPage===1?'disabled':''}><i class="fas fa-chevron-left" style="font-size:10px"></i></button>`;

    for (let i = 1; i <= pages; i++) {
      if (pages > 7 && i > 2 && i < pages - 1 && Math.abs(i - currentPage) > 1) {
        if (i === 3 || i === pages - 2) btns += `<span style="padding:0 4px;color:var(--color-text-secondary);line-height:32px">…</span>`;
        continue;
      }
      btns += `<button class="po-page-btn${i===currentPage?' active':''}" onclick="goPage(${i})">${i}</button>`;
    }

    btns += `<button class="po-page-btn" onclick="goPage(${currentPage+1})" ${currentPage===pages?'disabled':''}><i class="fas fa-chevron-right" style="font-size:10px"></i></button>`;
    btnWrap.innerHTML = btns;
  }

  function goPage(p) {
    const pages = Math.ceil(filteredData.length / entriesPerPage);
    if (p < 1 || p > pages) return;
    currentPage = p;
    renderTable();
    renderPagination();
  }

  function changeEntries(val) {
    entriesPerPage = parseInt(val);
    currentPage    = 1;
    renderTable();
    renderPagination();
  }

  /* ── View Modal ────────────────────────────── */
  function viewPODetails(poId) {
    const po = purchaseOrdersData.find(p => p.po_id == poId);
    if (!po) return;

    document.getElementById('poModalTitle').textContent    = po.po_number;
    document.getElementById('poModalSubtitle').textContent = po.company_name + ' · ' + fmtDate(po.po_date);

    document.getElementById('poModalContent').innerHTML = `
      <div class="po-detail-grid">
        <div>
          <div class="po-detail-section-title">Order Information</div>
          <div class="po-detail-row"><div class="po-detail-key">PO Number</div><div class="po-detail-val" style="font-family:var(--po-mono)">${po.po_number}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">Supplier</div><div class="po-detail-val">${po.company_name||'—'}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">Property</div><div class="po-detail-val">${po.property_name||'—'}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">PO Date</div><div class="po-detail-val">${fmtDate(po.po_date)}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">Expected Delivery</div><div class="po-detail-val">${fmtDate(po.expected_delivery)}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">Status</div><div class="po-detail-val"><span class="po-badge ${badgeClass(po.po_status)}">${badgeLabel(po.po_status)}</span></div></div>
        </div>
        <div>
          <div class="po-detail-section-title">Financial Details</div>
          <div class="po-detail-row"><div class="po-detail-key">Total Amount</div><div class="po-detail-val big">₱${parseFloat(po.total_amount||0).toFixed(2)}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">Payment Terms</div><div class="po-detail-val">${po.payment_terms||'—'}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">Payment Status</div><div class="po-detail-val">${po.payment_status||'—'}</div></div>
          <div class="po-detail-section-title" style="margin-top:20px">Delivery & Notes</div>
          <div class="po-detail-row"><div class="po-detail-key">Delivery Address</div><div class="po-detail-val">${po.delivery_address||'—'}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">Notes</div><div class="po-detail-val">${po.notes||'—'}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">Created</div><div class="po-detail-val">${po.created_at ? new Date(po.created_at).toLocaleString() : '—'}</div></div>
        </div>
      </div>`;

    document.getElementById('poDetailsModal').classList.add('open');
  }

  function closePODetailsModal() { document.getElementById('poDetailsModal').classList.remove('open'); }

  /* ── Add Modal ─────────────────────────────── */
  function openAddPOModal()  { document.getElementById('addPOModal').classList.add('open'); }
  function closeAddPOModal() {
    document.getElementById('addPOModal').classList.remove('open');
    document.getElementById('addPOForm').reset();
  }

  function submitAddPO(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('addPOForm'));
    formData.append('action', 'add');
    fetch('../api/purchase_orders.php', { method: 'POST', body: formData })
      .then(r => r.json())
      .then(data => {
        closeAddPOModal();
        if (data.success) fetchPurchaseOrders();
        else alert(data.message || 'Error creating purchase order');
      })
      .catch(() => { closeAddPOModal(); alert('Error creating purchase order'); });
  }

  /* ── Edit Modal ────────────────────────────── */
  function openEditPOModal(poId) {
    const po = purchaseOrdersData.find(p => p.po_id == poId);
    if (!po) return;

    const form = document.getElementById('editPOForm');
    document.getElementById('editPOId').value          = po.po_id;
    document.getElementById('editPOModalSubtitle').textContent = `Editing ${po.po_number}`;
    form.elements['po_number'].value                   = po.po_number;
    form.elements['property_id'].value                 = po.property_id;
    form.elements['supplier_id'].value                 = po.supplier_id;
    form.elements['po_date'].value                     = (po.po_date||'').split(' ')[0];
    form.elements['expected_delivery'].value           = (po.expected_delivery||'').split(' ')[0];
    form.elements['po_status'].value                   = po.po_status;
    form.elements['payment_terms'].value               = po.payment_terms || '';
    form.elements['delivery_address'].value            = po.delivery_address || '';
    form.elements['notes'].value                       = po.notes || '';

    document.getElementById('editPOModal').classList.add('open');
  }

  function closeEditPOModal() {
    document.getElementById('editPOModal').classList.remove('open');
    document.getElementById('editPOForm').reset();
  }

  function submitEditPO(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('editPOForm'));
    formData.append('action', 'edit');
    fetch('../api/purchase_orders.php', { method: 'POST', body: formData })
      .then(r => r.json())
      .then(data => {
        closeEditPOModal();
        if (data.success) fetchPurchaseOrders();
        else alert(data.message || 'Error updating purchase order');
      })
      .catch(() => { closeEditPOModal(); alert('Error updating purchase order'); });
  }

  /* ── Delete ────────────────────────────────── */
  function deletePO(btn) {
    const poId     = btn.dataset.id;
    const poNumber = btn.dataset.number;
    if (!confirm(`Delete PO "${poNumber}"? This cannot be undone.`)) return;

    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('po_id', poId);

    fetch('../api/purchase_orders.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(data => {
        if (data.success) fetchPurchaseOrders();
        else alert(data.message || 'Error deleting purchase order');
      })
      .catch(() => alert('Error deleting purchase order'));
  }

  /* ── Init ──────────────────────────────────── */
  document.addEventListener('DOMContentLoaded', () => {
    fetchPurchaseOrders();
    fetchRelated();
  });
</script>