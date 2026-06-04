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
  @media (max-width: 640px) {
    .po-page {
      padding: 20px 16px 32px;
    }
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
  @media (max-width: 640px) {
    .po-page-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 12px;
      margin-bottom: 20px;
    }
  }

  .po-eyebrow {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--po-blue);
    margin-bottom: 4px;
  }
  @media (max-width: 640px) {
    .po-eyebrow {
      font-size: 10px;
    }
  }

  .po-page-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.4px;
    line-height: 1.2;
  }
  @media (max-width: 640px) {
    .po-page-title {
      font-size: 20px;
    }
  }

  .po-page-subtitle {
    font-size: 13px;
    color: var(--color-text-secondary);
    margin-top: 3px;
  }
  @media (max-width: 640px) {
    .po-page-subtitle {
      font-size: 12px;
    }
  }

  /* ── Stat Cards ──────────────────────────────────── */
  .po-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
  }
  @media (max-width: 1200px) {
    .po-stats-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  @media (max-width: 640px) {
    .po-stats-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
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
  @media (max-width: 640px) {
    .po-stat-card {
      padding: 16px 18px;
    }
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
  @media (max-width: 640px) {
    .po-stat-icon {
      width: 32px; height: 32px;
      font-size: 13px;
      margin-bottom: 10px;
    }
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
  @media (max-width: 640px) {
    .po-stat-label {
      font-size: 11px;
    }
  }
  .po-stat-value {
    font-size: 32px;
    font-weight: 600;
    color: var(--color-text-primary);
    font-family: var(--po-mono);
    letter-spacing: -.5px;
    line-height: 1;
  }
  @media (max-width: 640px) {
    .po-stat-value {
      font-size: 26px;
    }
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
  @media (max-width: 768px) {
    .po-card-header {
      flex-direction: column;
      align-items: stretch;
      gap: 16px;
      padding: 16px 18px;
    }
  }
  .po-card-title { font-size: 15px; font-weight: 600; color: var(--color-text-primary); letter-spacing: -.2px; }
  @media (max-width: 768px) {
    .po-card-title {
      font-size: 14px;
    }
  }
  .po-card-count { font-size: 12px; color: var(--color-text-secondary); margin-top: 2px; }

  /* ── Toolbar ─────────────────────────────────────── */
  .po-toolbar { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
  @media (max-width: 768px) {
    .po-toolbar {
      width: 100%;
      justify-content: space-between;
    }
  }
  @media (max-width: 480px) {
    .po-toolbar {
      flex-direction: column;
      align-items: stretch;
    }
  }

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
  @media (max-width: 768px) {
    .po-search {
      width: 100%;
      flex: 1;
    }
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
  @media (max-width: 480px) {
    .po-filter-tabs {
      width: 100%;
      justify-content: center;
    }
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
  @media (max-width: 480px) {
    .po-filter-tab {
      flex: 1;
      justify-content: center;
      font-size: 11px;
      padding: 5px 10px;
    }
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
  @media (max-width: 480px) {
    .po-btn {
      width: 100%;
      justify-content: center;
    }
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
  @media (max-width: 640px) {
    .po-table th {
      padding: 10px 12px;
      font-size: 10px;
    }
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
  @media (max-width: 640px) {
    .po-table td {
      padding: 10px 12px;
      font-size: 12px;
    }
  }
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
  @media (max-width: 640px) {
    .po-badge {
      font-size: 10px;
      padding: 3px 8px;
    }
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
  @media (max-width: 640px) {
    .po-empty {
      padding: 48px 20px;
    }
  }
  .po-empty-icon { font-size: 32px; opacity: .2; margin-bottom: 12px; }
  @media (max-width: 640px) {
    .po-empty-icon {
      font-size: 28px;
    }
  }
  .po-empty-text { font-size: 14px; font-weight: 500; color: var(--color-text-secondary); }
  @media (max-width: 640px) {
    .po-empty-text {
      font-size: 13px;
    }
  }
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
  @media (max-width: 640px) {
    .po-pagination-wrap {
      flex-direction: column;
      align-items: stretch;
      gap: 12px;
      padding: 12px 16px;
    }
  }
  .po-pagination-left { display: flex; align-items: center; gap: 10px; }
  @media (max-width: 640px) {
    .po-pagination-left {
      justify-content: center;
      flex-wrap: wrap;
    }
  }
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
  @media (max-width: 640px) {
    .po-pagination {
      justify-content: center;
      flex-wrap: wrap;
    }
  }

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
  /*
   * Overlays are always moved to <body> via poModalOpen() so position:fixed
   * is always relative to the true viewport — never a transformed/scrollable
   * ancestor inside a dashboard shell.
   */
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
    /* prevent the overlay itself from scrolling */
    overflow: hidden;
  }
  @media (max-width: 480px) {
    .po-overlay {
      padding: 16px;
    }
  }
  .po-overlay.open { display: flex; }

  .po-modal {
    background: var(--color-background-primary, #fff);
    border-radius: var(--po-r-xl);
    border: 1px solid rgba(0,0,0,.08);
    width: 100%;
    max-width: 720px;
    max-height: 92vh;
    /* flex column so header/footer stay sticky and only body scrolls */
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: var(--po-shadow-lg);
    animation: poModalIn .2s cubic-bezier(.22,.68,0,1.2);
  }
  @media (max-width: 480px) {
    .po-modal {
      max-width: none;
      max-height: 95vh;
      border-radius: var(--po-r-lg);
    }
  }
  .po-modal.wide { max-width: 900px; }
  @media (max-width: 480px) {
    .po-modal.wide {
      max-width: none;
    }
  }

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
    flex-shrink: 0;
    background: var(--color-background-primary, #fff);
    border-radius: var(--po-r-xl) var(--po-r-xl) 0 0;
  }
  @media (max-width: 480px) {
    .po-modal-header {
      padding: 16px 18px;
    }
  }
  .po-modal-title { font-size: 17px; font-weight: 600; color: var(--color-text-primary); letter-spacing: -.3px; }
  @media (max-width: 480px) {
    .po-modal-title {
      font-size: 15px;
    }
  }
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

  /* This is the ONLY element that scrolls inside the modal */
  .po-modal-body {
    padding: 26px;
    flex: 1 1 auto;
    overflow-y: auto;
    overflow-x: hidden;
    min-height: 0;
    -webkit-overflow-scrolling: touch;
  }
  @media (max-width: 768px) {
    .po-modal-body {
      padding: 20px;
    }
  }
  @media (max-width: 480px) {
    .po-modal-body {
      padding: 18px;
      overflow-y: auto;
      overflow-x: hidden;
    }
  }
  .po-modal-footer {
    padding: 18px 26px;
    border-top: 1px solid rgba(0,0,0,.06);
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    flex-shrink: 0;
    background: var(--color-background-primary, #fff);
    border-radius: 0 0 var(--po-r-xl) var(--po-r-xl);
  }
  @media (max-width: 480px) {
    .po-modal-footer {
      padding: 16px 18px;
      flex-direction: column;
    }
    .po-modal-footer .po-btn {
      width: 100%;
    }
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

  /* ── Custom Alert Modal ───────────────────────────── */
  .po-alert-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 20px;
  }
  @media (max-width: 480px) {
    .po-alert-overlay {
      padding: 16px;
    }
  }
  .po-alert-overlay.open { display: flex; }

  .po-alert-box {
    background: var(--color-background-primary, #fff);
    border-radius: var(--po-r-lg);
    box-shadow: var(--po-shadow-lg);
    max-width: 400px;
    width: 100%;
    padding: 24px;
    animation: poAlertIn .2s cubic-bezier(.22,.68,0,1.2);
  }
  @media (max-width: 480px) {
    .po-alert-box {
      padding: 20px;
      max-width: none;
    }
  }

  @keyframes poAlertIn {
    from { opacity: 0; transform: scale(.95) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
  }

  .po-alert-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--color-text-primary);
    margin-bottom: 8px;
  }

  .po-alert-message {
    font-size: 14px;
    color: var(--color-text-secondary);
    line-height: 1.5;
    margin-bottom: 20px;
  }

  .po-alert-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
  }
  @media (max-width: 480px) {
    .po-alert-actions {
      flex-direction: column;
    }
    .po-alert-actions .po-alert-btn-cancel,
    .po-alert-actions .po-alert-btn-confirm {
      width: 100%;
    }
  }

  .po-alert-btn-cancel,
  .po-alert-btn-confirm {
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
  }

  .po-alert-btn-cancel:hover { background: var(--color-background-secondary, #F8FAFC); }

  .po-alert-btn-confirm {
    background: var(--po-blue);
    color: #fff;
    border-color: var(--po-blue);
  }

  .po-alert-btn-confirm:hover {
    background: var(--po-blue-dark);
    border-color: var(--po-blue-dark);
  }

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
  @media (max-width: 1200px) {
    .po-form-grid, .po-detail-grid { grid-template-columns: 1fr; }
  }
  @media (max-width: 768px) {
    .po-form-grid, .po-detail-grid { grid-template-columns: 1fr; }
    .po-form-group.full, .po-form-section, .po-form-divider { grid-column: 1; }
  }
  @media (max-width: 640px) {
    .po-form-grid, .po-detail-grid { grid-template-columns: 1fr; }
    .po-form-group.full, .po-form-section, .po-form-divider { grid-column: 1; }
  }
  @media (max-width: 480px) {
    .po-filter-tabs {
      display: flex;
    }
  }

  /* ── Add Item Modal ──────────────────────────────── */
  .po-modal-add-item {
    max-width: 500px;
  }
  @media (max-width: 768px) {
    .po-modal-add-item {
      max-width: 90vw;
    }
  }
  @media (max-width: 480px) {
    .po-modal-add-item {
      max-width: none;
      border-radius: var(--po-r-lg);
    }
  }

  /* Product search input with button */
  .po-product-search {
    display: flex;
    gap: 8px;
    align-items: center;
  }
  .po-product-search .po-form-input {
    flex: 1;
    min-width: 0;
  }
  .po-btn-icon {
    padding: 8px 12px;
    flex-shrink: 0;
  }
  @media (max-width: 480px) {
    .po-product-search {
      flex-direction: column;
      align-items: stretch;
    }
    .po-btn-icon {
      width: 100%;
      justify-content: center;
      margin-top: 8px;
    }
  }

  /* Item total display */
  .po-item-total {
    background: var(--po-blue-pale);
    padding: 12px;
    border-radius: 8px;
    margin-top: 8px;
  }
  .po-item-total .po-form-label {
    margin-bottom: 4px;
  }
  .po-item-total #poItemTotal {
    font-size: 18px;
    font-weight: 600;
    color: var(--po-blue);
  }
  @media (max-width: 480px) {
    .po-item-total {
      padding: 10px;
    }
    .po-item-total #poItemTotal {
      font-size: 16px;
    }
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
    <form id="addPOForm" onsubmit="submitAddPO(event)" style="display:flex; flex-direction:column; flex:1 1 auto; min-height:0; overflow:hidden;">
      <div class="po-modal-body">
        <div class="po-form-grid">
          <div class="po-form-section"><i class="fas fa-info-circle" style="opacity:.6"></i> Order Information</div>

          <div class="po-form-group">
            <label class="po-form-label">PO Number <span class="req">*</span></label>
            <input type="text" name="po_number" id="addPoNumber" required class="po-form-input" placeholder="Auto-generated" readonly style="background: var(--po-slate-pale);">
          </div>
          <div class="po-form-group">
            <label class="po-form-label">PO Date <span class="req">*</span></label>
            <input type="date" name="po_date" id="addPoDate" required class="po-form-input">
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Property <span class="req">*</span></label>
            <select name="property_id" required class="po-form-select"><option value="">Select Property</option></select>
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Supplier <span class="req">*</span></label>
            <select name="supplier_id" id="addPoSupplier" required class="po-form-select" onchange="onAddSupplierChange()">
              <option value="">Select Supplier</option>
            </select>
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
          <div class="po-form-section"><i class="fas fa-boxes" style="opacity:.6"></i> Order Items</div>

          <div class="po-form-group full">
            <div id="poItemsSection" style="border: 1px solid rgba(0,0,0,.08); border-radius: 10px; padding: 16px;">
              <div id="poItemsList" style="margin-bottom: 12px;">
                <!-- Items will be added here dynamically -->
                <div class="po-item-row" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 100px 40px; gap: 8px; align-items: center; padding: 8px 0; border-bottom: 1px dashed rgba(0,0,0,.08);">
                  <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">Product</div>
                  <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">UOM</div>
                  <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">Qty</div>
                  <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">Unit Cost</div>
                  <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">Total</div>
                  <div></div>
                </div>
              </div>
              <button type="button" onclick="openAddItemModal('add')" class="po-btn" style="font-size: 12px; padding: 6px 12px;">
                <i class="fas fa-plus"></i> Add Item
              </button>
              <div id="poTotals" style="display: flex; justify-content: flex-end; gap: 24px; margin-top: 16px; padding-top: 12px; border-top: 1px solid rgba(0,0,0,.08);">
                <div><span style="font-size: 12px; color: var(--color-text-secondary);">Total:</span> <span id="poTotal" style="font-weight: 600; color: var(--po-blue);">₱0.00</span></div>
              </div>
            </div>
          </div>

          <hr class="po-form-divider">
          <div class="po-form-section"><i class="fas fa-credit-card" style="opacity:.6"></i> Payment & Delivery</div>

          <div class="po-form-group">
            <label class="po-form-label">Payment Terms</label>
            <select name="payment_terms" id="addPoPaymentTerms" class="po-form-select">
              <option value="">Select Terms</option>
              <option value="COD">COD - Cash on Delivery</option>
              <option value="Net 15">Net 15 - Due in 15 days</option>
              <option value="Net 30">Net 30 - Due in 30 days</option>
              <option value="Net 45">Net 45 - Due in 45 days</option>
              <option value="Net 60">Net 60 - Due in 60 days</option>
              <option value="Prepaid">Prepaid - Full payment before delivery</option>
              <option value="50/50">50/50 - 50% down, 50% on delivery</option>
            </select>
          </div>

          <!-- Geocode Delivery Address -->
          <div class="po-form-group full">
            <label class="po-form-label">Delivery Address</label>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
              <div>
                <input type="text" list="region_list" id="po_region_display" class="po-form-input" placeholder="Region" onchange="onPORegionChange()">
                <input type="hidden" name="delivery_region_code" id="po_region_code">
              </div>
              <div>
                <input type="text" list="province_list" id="po_province_display" class="po-form-input" placeholder="Province" onchange="onPOProvinceChange()">
                <input type="hidden" name="delivery_province_code" id="po_province_code">
              </div>
              <div>
                <input type="text" list="city_list" id="po_city_display" class="po-form-input" placeholder="City/Municipality" onchange="onPOCityChange()">
                <input type="hidden" name="delivery_city_code" id="po_city_code">
              </div>
              <div>
                <input type="text" list="barangay_list" id="po_barangay_display" class="po-form-input" placeholder="Barangay" onchange="onPOBarangayChange()">
                <input type="hidden" name="delivery_barangay_code" id="po_barangay_code">
              </div>
            </div>
            <input type="text" name="delivery_address" id="po_full_address" class="po-form-input" style="margin-top: 8px;" placeholder="Street/Building/House No.">
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
    <form id="editPOForm" onsubmit="submitEditPO(event)" style="display:flex; flex-direction:column; flex:1 1 auto; min-height:0; overflow:hidden;">
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
          <div class="po-form-section"><i class="fas fa-boxes" style="opacity:.6"></i> Order Items</div>

          <div class="po-form-group full">
            <div id="editPoItemsSection" style="border: 1px solid rgba(0,0,0,.08); border-radius: 10px; padding: 16px;">
              <div id="editPoItemsList" style="margin-bottom: 12px;">
                <div class="po-item-row" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 100px 40px; gap: 8px; align-items: center; padding: 8px 0; border-bottom: 1px dashed rgba(0,0,0,.08);">
                  <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">Product</div>
                  <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">UOM</div>
                  <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">Qty</div>
                  <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">Unit Cost</div>
                  <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">Total</div>
                  <div></div>
                </div>
              </div>
              <button type="button" onclick="openAddItemModal('edit')" class="po-btn" style="font-size: 12px; padding: 6px 12px;">
                <i class="fas fa-plus"></i> Add Item
              </button>
              <div id="editPoTotals" style="display: flex; justify-content: flex-end; gap: 24px; margin-top: 16px; padding-top: 12px; border-top: 1px solid rgba(0,0,0,.08);">
                <div><span style="font-size: 12px; color: var(--color-text-secondary);">Total:</span> <span id="editPoTotal" style="font-weight: 600; color: var(--po-blue);">₱0.00</span></div>
              </div>
            </div>
          </div>

          <hr class="po-form-divider">
          <div class="po-form-section"><i class="fas fa-credit-card" style="opacity:.6"></i> Payment & Delivery</div>

          <div class="po-form-group">
            <label class="po-form-label">Payment Terms</label>
            <input type="text" name="payment_terms" class="po-form-input" placeholder="e.g., Net 30, COD">
          </div>

          <!-- Geocode Delivery Address -->
          <div class="po-form-group full">
            <label class="po-form-label">Delivery Address</label>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
              <div>
                <input type="text" list="region_list" id="edit_po_region_display" class="po-form-input" placeholder="Region" onchange="onEditPORegionChange()">
                <input type="hidden" name="delivery_region_code" id="edit_po_region_code">
              </div>
              <div>
                <input type="text" list="province_list" id="edit_po_province_display" class="po-form-input" placeholder="Province" onchange="onEditPOProvinceChange()">
                <input type="hidden" name="delivery_province_code" id="edit_po_province_code">
              </div>
              <div>
                <input type="text" list="city_list" id="edit_po_city_display" class="po-form-input" placeholder="City/Municipality" onchange="onEditPOCityChange()">
                <input type="hidden" name="delivery_city_code" id="edit_po_city_code">
              </div>
              <div>
                <input type="text" list="barangay_list" id="edit_po_barangay_display" class="po-form-input" placeholder="Barangay" onchange="onEditPOBarangayChange()">
                <input type="hidden" name="delivery_barangay_code" id="edit_po_barangay_code">
              </div>
            </div>
            <input type="text" name="delivery_address" id="edit_po_full_address" class="po-form-input" style="margin-top: 8px;" placeholder="Street/Building/House No.">
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

<!-- Geocode Datalists -->
<datalist id="region_list"></datalist>
<datalist id="province_list"></datalist>
<datalist id="city_list"></datalist>
<datalist id="barangay_list"></datalist>

<!-- Product Datalist for search -->
<datalist id="product_search_list"></datalist>

<!-- Add PO Item Modal -->
<div class="po-overlay" id="addItemModal" onclick="if(event.target===this)closeAddItemModal()">
  <div class="po-modal po-modal-add-item" role="dialog" aria-modal="true">
    <div class="po-modal-header">
      <div>
        <div class="po-modal-title">Add Item to Purchase Order</div>
      </div>
      <button class="po-modal-close" onclick="closeAddItemModal()" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>
    <div class="po-modal-body">
      <input type="hidden" id="addItemModalContext" value="add">
      <div class="po-form-grid">
        <div class="po-form-group full">
          <label class="po-form-label">Product <span class="req">*</span></label>
          <div class="po-product-search">
            <input type="text" id="poItemProduct" list="product_search_list" class="po-form-input" placeholder="Search product..." onchange="onProductSelectChange()">
            <input type="hidden" id="poItemProductId">
            <button type="button" onclick="openQuickProductModal()" class="po-btn po-btn-icon" title="Add New Product">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <div class="po-form-group">
          <label class="po-form-label">UOM <span class="req">*</span></label>
          <select id="poItemUom" class="po-form-select" onchange="onUomChange()">
            <option value="">Select UOM</option>
          </select>
          <input type="hidden" id="poItemUomId">
        </div>
        <div class="po-form-group">
          <label class="po-form-label">Quantity <span class="req">*</span></label>
          <input type="number" id="poItemQty" class="po-form-input" min="0.001" step="0.001" placeholder="0.00" onchange="calculateItemTotal()">
        </div>
        <div class="po-form-group">
          <label class="po-form-label">Unit Cost <span class="req">*</span></label>
          <input type="number" id="poItemCost" class="po-form-input" min="0" step="0.01" placeholder="0.00" onchange="calculateItemTotal()">
        </div>
        <div class="po-form-group full">
          <div class="po-item-total">
            <label class="po-form-label">Total Cost</label>
            <div id="poItemTotal">₱0.00</div>
          </div>
        </div>
      </div>
    </div>
    <div class="po-modal-footer">
      <button type="button" class="po-btn" onclick="closeAddItemModal()"><i class="fas fa-times"></i> Cancel</button>
      <button type="button" onclick="addItemToPO()" class="po-btn po-btn-primary"><i class="fas fa-plus"></i> Add to PO</button>
    </div>
  </div>
</div>

<!-- Quick Add Product Modal -->
<div class="po-overlay" id="quickProductModal" onclick="if(event.target===this)closeQuickProductModal()">
  <div class="po-modal" role="dialog" aria-modal="true" style="max-width: 500px;">
    <div class="po-modal-header">
      <div>
        <div class="po-modal-title">Quick Add Product</div>
        <div class="po-modal-subtitle">Create a new product for this purchase order</div>
      </div>
      <button class="po-modal-close" onclick="closeQuickProductModal()" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>
    <div class="po-modal-body">
      <div class="po-form-group">
        <label class="po-form-label">Product Name <span class="req">*</span></label>
        <input type="text" id="quickProductName" class="po-form-input" placeholder="e.g., Shampoo 500ml">
      </div>
      <div class="po-form-group">
        <label class="po-form-label">Product Code</label>
        <input type="text" id="quickProductCode" class="po-form-input" placeholder="Auto-generated if empty">
      </div>
      <div class="po-form-group">
        <label class="po-form-label">Category <span class="req">*</span></label>
        <select id="quickProductCategory" class="po-form-select">
          <option value="">Select Category</option>
        </select>
      </div>
      <div class="po-form-group">
        <label class="po-form-label">Base UOM <span class="req">*</span></label>
        <select id="quickProductUom" class="po-form-select">
          <option value="">Select UOM</option>
        </select>
      </div>
      <div class="po-form-group">
        <label class="po-form-label">Product Type</label>
        <select id="quickProductType" class="po-form-select">
          <option value="consumable">Consumable</option>
          <option value="non_consumable">Non-Consumable</option>
          <option value="food">Food</option>
          <option value="beverage">Beverage</option>
          <option value="amenity">Amenity</option>
          <option value="cleaning">Cleaning Supply</option>
          <option value="linen">Linen</option>
          <option value="equipment">Equipment</option>
          <option value="other">Other</option>
        </select>
      </div>
    </div>
    <div class="po-modal-footer">
      <button type="button" class="po-btn" onclick="closeQuickProductModal()"><i class="fas fa-times"></i> Cancel</button>
      <button type="button" onclick="saveQuickProduct()" class="po-btn po-btn-primary"><i class="fas fa-save"></i> Save Product</button>
    </div>
  </div>
</div>

<!-- Receive PO Modal -->
<div class="po-overlay" id="receivePOModal" onclick="if(event.target===this)closeReceiveModal()">
  <div class="po-modal wide" role="dialog" aria-modal="true">
    <div class="po-modal-header">
      <div>
        <div class="po-modal-title">Receive Purchase Order</div>
        <div class="po-modal-subtitle" id="receivePOModalSubtitle">Receiving items</div>
      </div>
      <button class="po-modal-close" onclick="closeReceiveModal()" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>
    <div class="po-modal-body">
      <input type="hidden" id="receivePOId">
      <div class="po-form-group full">
        <label class="po-form-label">Notes</label>
        <textarea id="receiveNotes" rows="2" class="po-form-textarea" placeholder="Optional notes about this receiving..."></textarea>
      </div>
      <div class="po-detail-section-title" style="margin-top:16px">Items to Receive</div>
      <div id="receiveItemsList" style="margin-top:12px;"></div>
    </div>
    <div class="po-modal-footer">
      <button type="button" class="po-btn" onclick="closeReceiveModal()"><i class="fas fa-times"></i> Cancel</button>
      <button type="button" onclick="submitReceive()" class="po-btn po-btn-primary"><i class="fas fa-check"></i> Confirm Receive</button>
    </div>
  </div>
</div>

<!-- Payment Modal -->
<div class="po-overlay" id="paymentModal" onclick="if(event.target===this)closePaymentModal()">
  <div class="po-modal" role="dialog" aria-modal="true" style="max-width: 500px;">
    <div class="po-modal-header">
      <div>
        <div class="po-modal-title">Record Payment</div>
        <div class="po-modal-subtitle" id="paymentModalSubtitle">Payment for PO</div>
      </div>
      <button class="po-modal-close" onclick="closePaymentModal()" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>
    <div class="po-modal-body">
      <input type="hidden" id="paymentPOId">
      <div class="po-form-group">
        <label class="po-form-label">Total Amount</label>
        <div id="paymentTotalAmount" style="font-size:18px; font-weight:600; color:var(--po-blue);">₱0.00</div>
      </div>
      <div class="po-form-group">
        <label class="po-form-label">Amount Paid</label>
        <div id="paymentAmountPaid" style="font-size:14px; color:var(--color-text-secondary);">₱0.00</div>
      </div>
      <div class="po-form-group">
        <label class="po-form-label">Remaining Balance</label>
        <div id="paymentBalance" style="font-size:18px; font-weight:600; color:var(--color-error);">₱0.00</div>
      </div>
      <div class="po-form-group">
        <label class="po-form-label">Payment Amount <span class="req">*</span></label>
        <input type="number" id="paymentAmount" class="po-form-input" min="0.01" step="0.01" placeholder="0.00">
      </div>
      <div class="po-form-group">
        <label class="po-form-label">Payment Method</label>
        <select id="paymentMethod" class="po-form-select" onchange="onPaymentMethodChange()">
          <option value="">Select Method</option>
        </select>
      </div>

      <!-- Dynamic payment method fields -->
      <div id="paymentMethodFields" style="display:none;">
        <!-- Bank Transfer Fields -->
        <div id="bankFields" style="display:none;">
          <div class="po-form-group">
            <label class="po-form-label">Bank Account Number</label>
            <input type="text" id="paymentBankAccount" class="po-form-input" placeholder="Account number">
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Account Name</label>
            <input type="text" id="paymentAccountName" class="po-form-input" placeholder="Account holder name">
          </div>
        </div>

        <!-- Digital Wallet Fields -->
        <div id="walletFields" style="display:none;">
          <div class="po-form-group">
            <label class="po-form-label">Wallet Number</label>
            <input type="text" id="paymentWalletNumber" class="po-form-input" placeholder="Mobile number">
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Account Name</label>
            <input type="text" id="paymentWalletName" class="po-form-input" placeholder="Account holder name">
          </div>
        </div>

        <!-- Card Fields -->
        <div id="cardFields" style="display:none;">
          <div class="po-form-group">
            <label class="po-form-label">Card Last 4 Digits</label>
            <input type="text" id="paymentCardLast4" class="po-form-input" maxlength="4" placeholder="XXXX">
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Cardholder Name</label>
            <input type="text" id="paymentCardholderName" class="po-form-input" placeholder="Name on card">
          </div>
        </div>

        <!-- Check Fields -->
        <div id="checkFields" style="display:none;">
          <div class="po-form-group">
            <label class="po-form-label">Check Number</label>
            <input type="text" id="paymentCheckNumber" class="po-form-input" placeholder="Check #">
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Bank Name</label>
            <input type="text" id="paymentCheckBank" class="po-form-input" placeholder="Issuing bank">
          </div>
          <div class="po-form-group">
            <label class="po-form-label">Check Date</label>
            <input type="date" id="paymentCheckDate" class="po-form-input">
          </div>
        </div>
      </div>

      <div class="po-form-group">
        <label class="po-form-label">Reference Number</label>
        <input type="text" id="paymentReference" class="po-form-input" placeholder="e.g., OR #12345">
      </div>
      <div class="po-form-group">
        <label class="po-form-label">Payment Date</label>
        <input type="date" id="paymentDate" class="po-form-input">
      </div>
      <div class="po-form-group full">
        <label class="po-form-label">Notes</label>
        <textarea id="paymentNotes" rows="2" class="po-form-textarea" placeholder="Optional notes..."></textarea>
      </div>
    </div>
    <div class="po-modal-footer">
      <button type="button" class="po-btn" onclick="closePaymentModal()"><i class="fas fa-times"></i> Cancel</button>
      <button type="button" onclick="submitPayment()" class="po-btn po-btn-primary"><i class="fas fa-save"></i> Record Payment</button>
    </div>
  </div>
</div>

<!-- Custom Alert Modal -->
<div class="po-alert-overlay" id="poAlertModal">
  <div class="po-alert-box">
    <div class="po-alert-title" id="poAlertTitle">Confirm Action</div>
    <div class="po-alert-message" id="poAlertMessage">Are you sure you want to proceed?</div>
    <div class="po-alert-actions">
      <button class="po-alert-btn-cancel" onclick="closePOAlert()">Cancel</button>
      <button class="po-alert-btn-confirm" id="poAlertConfirmBtn">Confirm</button>
    </div>
  </div>
</div>

<script>
  const csrfToken = '<?php echo CSRF_TOKEN; ?>';
  const userPermissions = <?php echo isset($_SESSION['permissions']) ? json_encode($_SESSION['permissions']) : '[]'; ?>;
  const userRole = '<?php echo $_SESSION['role_code'] ?? ''; ?>';
  let purchaseOrdersData = [];
  let suppliersData      = [];
  let propertiesData     = [];
  let productsData       = [];
  let uomsData           = [];
  let categoriesData     = [];
  let poItems            = []; // Items for create modal
  let editPoItems        = []; // Items for edit modal

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
        productsData   = json.data.products || [];
        uomsData       = json.data.uoms || [];
        populateDropdowns();
        populateProductDropdown();
        populateUomDropdowns();
      }
    } catch (e) { console.error('Error fetching related data:', e); }

    // Fetch categories for quick product modal
    try {
      const catRes = await fetch('../api/inventory_categories.php?action=list');
      const catJson = await catRes.json();
      if (catJson.success) {
        categoriesData = catJson.data || [];
        populateCategoryDropdown();
      }
    } catch (e) { console.error('Error fetching categories:', e); }

    // Initialize geocode datalists
    await initGeocodeDatalists();
  }

  function populateProductDropdown() {
    const datalist = document.getElementById('product_search_list');
    if (!datalist) return;
    datalist.innerHTML = '';
    productsData.forEach(p => {
      const option = document.createElement('option');
      option.value = `${p.product_name} (${p.product_code || 'No Code'})`;
      option.dataset.productId = p.product_id;
      option.dataset.uomId = p.uom_id;
      option.dataset.uomName = p.uom_name || '';
      option.dataset.uomCode = p.uom_code || '';
      datalist.appendChild(option);
    });
  }

  function populateUomDropdowns() {
    const opts = '<option value="">Select UOM</option>' +
      uomsData.map(u => `<option value="${u.uom_id}">${u.uom_name} (${u.uom_code})</option>`).join('');
    document.getElementById('poItemUom').innerHTML = opts;
    document.getElementById('quickProductUom').innerHTML = opts;
  }

  function populateCategoryDropdown() {
    const opts = '<option value="">Select Category</option>' +
      categoriesData.map(c => `<option value="${c.category_id}">${c.category_name}</option>`).join('');
    document.getElementById('quickProductCategory').innerHTML = opts;
  }

  async function initGeocodeDatalists() {
    const regions = await fetchRegions();
    populateDatalist('region_list', regions, 'region_name');
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
  async function viewPODetails(poId) {
    try {
      const res = await fetch(`../api/purchase_orders.php?action=single&id=${poId}`);
      const json = await res.json();
      if (!json.success) return;

      const po = json.data;
      document.getElementById('poModalTitle').textContent    = po.po_number;
      document.getElementById('poModalSubtitle').textContent = po.company_name + ' · ' + fmtDate(po.po_date);

      let itemsHtml = '';
      if (po.items && po.items.length > 0) {
        itemsHtml = `
          <div class="po-detail-section-title" style="margin-top:24px">Order Items</div>
          <div style="overflow-x:auto">
            <table style="width:100%; border-collapse:collapse; font-size:13px;">
              <thead>
                <tr style="background:var(--po-slate-pale); text-align:left;">
                  <th style="padding:10px 12px; font-weight:600; color:var(--color-text-secondary);">Product</th>
                  <th style="padding:10px 12px; font-weight:600; color:var(--color-text-secondary);">UOM</th>
                  <th style="padding:10px 12px; font-weight:600; color:var(--color-text-secondary); text-align:right;">Qty</th>
                  <th style="padding:10px 12px; font-weight:600; color:var(--color-text-secondary); text-align:right;">Unit Cost</th>
                  <th style="padding:10px 12px; font-weight:600; color:var(--color-text-secondary); text-align:right;">Total</th>
                </tr>
              </thead>
              <tbody>
                ${po.items.map(item => `
                  <tr style="border-bottom:1px solid rgba(0,0,0,.05);">
                    <td style="padding:10px 12px;">
                      <div style="font-weight:500;">${item.product_name||'—'}</div>
                      <div style="font-size:11px; color:var(--color-text-secondary);">${item.product_code||''}</div>
                    </td>
                    <td style="padding:10px 12px;">${item.uom_name||'—'}</td>
                    <td style="padding:10px 12px; text-align:right;">${parseFloat(item.ordered_qty||0).toFixed(3)}</td>
                    <td style="padding:10px 12px; text-align:right;">₱${parseFloat(item.unit_cost||0).toFixed(2)}</td>
                    <td style="padding:10px 12px; text-align:right; font-weight:600;">₱${parseFloat(item.total_cost||0).toFixed(2)}</td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        `;
      } else {
        itemsHtml = `<div class="po-detail-section-title" style="margin-top:24px">Order Items</div><div style="padding:20px; text-align:center; color:var(--color-text-secondary);">No items in this purchase order</div>`;
      }

      const canApprove = userPermissions.includes('approve_purchase_order') || ['super_admin', 'admin', 'manager', 'ceo'].includes(userRole);
      const canEdit = userPermissions.includes('edit_purchase_order') || ['super_admin', 'admin'].includes(userRole);
      const canReceive = userPermissions.includes('receive_purchase_order') || ['super_admin', 'admin', 'manager', 'ceo'].includes(userRole);
      const canPay = userPermissions.includes('pay_purchase_order') || ['super_admin', 'admin', 'manager', 'ceo'].includes(userRole);
      const isApprovable = ['draft', 'submitted'].includes(po.po_status);
      const isReceivable = ['approved', 'partially_received'].includes(po.po_status);
      const isPayable = ['approved', 'partially_received', 'fully_received'].includes(po.po_status) && (po.balance || 0) > 0;

      let actionButtons = '';
      if (canApprove && isApprovable) {
        actionButtons = `<button class="po-btn po-btn-primary" onclick="approvePO('${po.po_id}')" style="margin-right:8px;"><i class="fas fa-check"></i> Approve</button>`;
      }
      if (canReceive && isReceivable) {
        actionButtons += `<button class="po-btn po-btn-primary" onclick="openReceiveModal('${po.po_id}')" style="margin-right:8px;"><i class="fas fa-box"></i> Receive</button>`;
      }
      if (canPay && isPayable) {
        actionButtons += `<button class="po-btn po-btn-primary" onclick="openPaymentModal('${po.po_id}')" style="margin-right:8px;"><i class="fas fa-credit-card"></i> Pay</button>`;
      }
      if (canEdit) {
        actionButtons += `<button class="po-btn" onclick="closePODetailsModal(); openEditPOModal('${po.po_id}')" style="margin-right:8px;"><i class="fas fa-edit"></i> Edit</button>`;
      }

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
          <div class="po-detail-row"><div class="po-detail-key">Amount Paid</div><div class="po-detail-val" style="color:var(--po-green); font-weight:600;">₱${parseFloat(po.amount_paid||0).toFixed(2)}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">Balance</div><div class="po-detail-val" style="color:${(po.balance||0) > 0 ? 'var(--po-red)' : 'var(--po-green)'}; font-weight:600;">₱${parseFloat(po.balance||0).toFixed(2)}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">Payment Terms</div><div class="po-detail-val">${po.payment_terms||'—'}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">Payment Status</div><div class="po-detail-val"><span class="po-badge ${po.payment_status === 'paid' ? 'green' : po.payment_status === 'partial' ? 'amber' : 'red'}">${po.payment_status||'—'}</span></div></div>
          <div class="po-detail-section-title" style="margin-top:20px">Delivery & Notes</div>
          <div class="po-detail-row"><div class="po-detail-key">Delivery Address</div><div class="po-detail-val">${po.delivery_address||'—'}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">Region</div><div class="po-detail-val">${po.region_name||'—'}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">Province</div><div class="po-detail-val">${po.province_name||'—'}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">City/Municipality</div><div class="po-detail-val">${po.city_municipality_name||'—'}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">Barangay</div><div class="po-detail-val">${po.barangay_name||'—'}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">Notes</div><div class="po-detail-val">${po.notes||'—'}</div></div>
          <div class="po-detail-row"><div class="po-detail-key">Created</div><div class="po-detail-val">${po.created_at ? new Date(po.created_at).toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: '2-digit', hour: '2-digit', minute: '2-digit' }) : '—'}</div></div>
        </div>
      </div>
      ${itemsHtml}

      ${po.payments && po.payments.length > 0 ? `
        <div class="po-detail-section-title" style="margin-top:24px">Payment History</div>
        <div style="margin-top:12px; background:var(--color-background-secondary, #F8FAFC); border-radius:var(--po-r-md); overflow:hidden;">
          <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
              <tr style="background:rgba(0,0,0,.03);">
                <th style="padding:10px 12px; text-align:left; font-weight:600; color:var(--color-text-secondary);">Date</th>
                <th style="padding:10px 12px; text-align:left; font-weight:600; color:var(--color-text-secondary);">Method</th>
                <th style="padding:10px 12px; text-align:right; font-weight:600; color:var(--color-text-secondary);">Amount</th>
                <th style="padding:10px 12px; text-align:left; font-weight:600; color:var(--color-text-secondary);">Reference</th>
                <th style="padding:10px 12px; text-align:left; font-weight:600; color:var(--color-text-secondary);">Details</th>
              </tr>
            </thead>
            <tbody>
              ${po.payments.map(pay => {
                let details = '—';
                if (pay.payment_details) {
                  try {
                    const pd = JSON.parse(pay.payment_details);
                    if (pd.bank_account) details = `Account: ${pd.bank_account}`;
                    else if (pd.wallet_number) details = `Wallet: ${pd.wallet_number}`;
                    else if (pd.card_last4) details = `Card: ****${pd.card_last4}`;
                    else if (pd.check_number) details = `Check #${pd.check_number}`;
                  } catch (e) { details = '—'; }
                }
                return `
                <tr style="border-bottom:1px solid rgba(0,0,0,.05);">
                  <td style="padding:10px 12px;">${pay.payment_date ? new Date(pay.payment_date).toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: '2-digit' }) : '—'}</td>
                  <td style="padding:10px 12px;">${pay.method_label || '—'}</td>
                  <td style="padding:10px 12px; text-align:right; font-weight:600; color:var(--po-green);">₱${parseFloat(pay.amount||0).toFixed(2)}</td>
                  <td style="padding:10px 12px;">${pay.reference_number || 'None'}</td>
                  <td style="padding:10px 12px; font-size:12px; color:var(--color-text-secondary);">${details}</td>
                </tr>
                `;
              }).join('')}
            </tbody>
          </table>
        </div>
      ` : ''}

      ${actionButtons ? `<div style="margin-top:24px; padding-top:16px; border-top:1px solid rgba(0,0,0,.08); display:flex; justify-content:flex-end;">${actionButtons}</div>` : ''}`;

      poModalOpen(document.getElementById('poDetailsModal'));
    } catch (e) { console.error('Error fetching PO details:', e); }
  }

  function closePODetailsModal() {
    poModalClose(document.getElementById('poDetailsModal'));
  }

  function approvePO(poId) {
    showPOAlert('Confirm Approval', 'Approve this purchase order?', async () => {
      const fd = new FormData();
      fd.append('action', 'approve');
      fd.append('po_id', poId);
      fd.append('csrf_token', csrfToken);

      try {
        const res = await fetch('../api/purchase_orders.php', { method: 'POST', body: fd });
        const data = await res.json();
        if (data.success) {
          closePODetailsModal();
          fetchPurchaseOrders();
          showPOAlert('Success', 'Purchase order approved successfully');
        } else {
          showPOAlert('Error', data.message || 'Error approving purchase order');
        }
      } catch (e) {
        showPOAlert('Error', 'Error approving purchase order');
      }
    });
  }

  let receiveItems = [];

  async function openReceiveModal(poId) {
    try {
      const res = await fetch(`../api/purchase_orders.php?action=single&id=${poId}`);
      const json = await res.json();
      if (!json.success) return;

      const po = json.data;
      document.getElementById('receivePOId').value = po.po_id;
      document.getElementById('receivePOModalSubtitle').textContent = `Receiving: ${po.po_number}`;
      document.getElementById('receiveNotes').value = '';

      receiveItems = po.items ? po.items.map(item => ({
        po_item_id: item.po_item_id,
        product_id: item.product_id,
        product_name: item.product_name,
        uom_id: item.uom_id,
        uom_name: item.uom_name,
        ordered_qty: parseFloat(item.ordered_qty),
        received_qty: parseFloat(item.received_qty || 0),
        unit_cost: parseFloat(item.unit_cost)
      })) : [];

      renderReceiveItems();
      poModalOpen(document.getElementById('receivePOModal'));
    } catch (e) { console.error('Error fetching PO for receive:', e); }
  }

  function closeReceiveModal() {
    poModalClose(document.getElementById('receivePOModal'));
    receiveItems = [];
  }

  function renderReceiveItems() {
    const list = document.getElementById('receiveItemsList');
    if (receiveItems.length === 0) {
      list.innerHTML = '<div style="padding:20px; text-align:center; color:var(--color-text-secondary);">No items to receive</div>';
      return;
    }

    let html = '<div style="overflow-x:auto"><table style="width:100%; border-collapse:collapse; font-size:13px;">';
    html += '<thead><tr style="background:var(--po-slate-pale); text-align:left;">';
    html += '<th style="padding:10px 12px; font-weight:600; color:var(--color-text-secondary);">Product</th>';
    html += '<th style="padding:10px 12px; font-weight:600; color:var(--color-text-secondary);">UOM</th>';
    html += '<th style="padding:10px 12px; font-weight:600; color:var(--color-text-secondary); text-align:right;">Ordered</th>';
    html += '<th style="padding:10px 12px; font-weight:600; color:var(--color-text-secondary); text-align:right;">Already Received</th>';
    html += '<th style="padding:10px 12px; font-weight:600; color:var(--color-text-secondary); text-align:right;">To Receive</th>';
    html += '</tr></thead><tbody>';

    receiveItems.forEach((item, index) => {
      const remaining = item.ordered_qty - item.received_qty;
      html += `<tr style="border-bottom:1px solid rgba(0,0,0,.05);">
        <td style="padding:10px 12px;">${item.product_name}</td>
        <td style="padding:10px 12px;">${item.uom_name}</td>
        <td style="padding:10px 12px; text-align:right;">${item.ordered_qty.toFixed(3)}</td>
        <td style="padding:10px 12px; text-align:right;">${item.received_qty.toFixed(3)}</td>
        <td style="padding:10px 12px; text-align:right;">
          <input type="number" id="receiveQty_${index}" class="po-form-input" style="width:100px; text-align:right;" min="0" step="0.001" max="${remaining}" value="${remaining}" onchange="validateReceiveQty(${index}, ${remaining})">
        </td>
      </tr>`;
    });

    html += '</tbody></table></div>';
    list.innerHTML = html;
  }

  function validateReceiveQty(index, maxQty) {
    const input = document.getElementById(`receiveQty_${index}`);
    const val = parseFloat(input.value) || 0;
    if (val < 0) input.value = 0;
    if (val > maxQty) input.value = maxQty;
  }

  async function submitReceive() {
    const itemsToReceive = receiveItems.map((item, index) => {
      const qtyInput = document.getElementById(`receiveQty_${index}`);
      const qty = parseFloat(qtyInput.value) || 0;
      return {
        po_item_id: item.po_item_id,
        product_id: item.product_id,
        uom_id: item.uom_id,
        ordered_qty: item.ordered_qty,
        received_qty: item.received_qty + qty
      };
    });

    const totalToReceive = itemsToReceive.reduce((sum, item) => sum + (item.received_qty - (receiveItems.find(i => i.po_item_id === item.po_item_id)?.received_qty || 0)), 0);

    if (totalToReceive <= 0) {
      showPOAlert('Validation Error', 'Please enter quantities to receive');
      return;
    }

    const fd = new FormData();
    fd.append('action', 'receive');
    fd.append('po_id', document.getElementById('receivePOId').value);
    fd.append('notes', document.getElementById('receiveNotes').value);
    fd.append('items_json', JSON.stringify(itemsToReceive));
    fd.append('csrf_token', csrfToken);

    try {
      const res = await fetch('../api/purchase_orders.php', { method: 'POST', body: fd });
      const data = await res.json();
      if (data.success) {
        closeReceiveModal();
        closePODetailsModal();
        fetchPurchaseOrders();
        showPOAlert('Success', 'Items received successfully');
      } else {
        showPOAlert('Error', data.message || 'Error receiving items');
      }
    } catch (e) {
      showPOAlert('Error', 'Error receiving items');
    }
  }

  async function openPaymentModal(poId) {
    try {
      const res = await fetch(`../api/purchase_orders.php?action=single&id=${poId}`);
      const json = await res.json();
      if (!json.success) return;

      const po = json.data;
      document.getElementById('paymentPOId').value = po.po_id;
      document.getElementById('paymentModalSubtitle').textContent = `Payment for ${po.po_number}`;
      document.getElementById('paymentTotalAmount').textContent = '₱' + parseFloat(po.total_amount || 0).toFixed(2);
      document.getElementById('paymentAmountPaid').textContent = '₱' + parseFloat(po.amount_paid || 0).toFixed(2);
      document.getElementById('paymentBalance').textContent = '₱' + parseFloat(po.balance || 0).toFixed(2);
      document.getElementById('paymentAmount').value = '';
      document.getElementById('paymentAmount').max = po.balance || 0;
      document.getElementById('paymentReference').value = '';
      document.getElementById('paymentDate').value = new Date().toISOString().split('T')[0];
      document.getElementById('paymentNotes').value = '';

      // Reset dynamic fields
      document.getElementById('paymentMethodFields').style.display = 'none';
      document.getElementById('bankFields').style.display = 'none';
      document.getElementById('walletFields').style.display = 'none';
      document.getElementById('cardFields').style.display = 'none';
      document.getElementById('checkFields').style.display = 'none';
      document.getElementById('paymentBankAccount').value = '';
      document.getElementById('paymentAccountName').value = '';
      document.getElementById('paymentWalletNumber').value = '';
      document.getElementById('paymentWalletName').value = '';
      document.getElementById('paymentCardLast4').value = '';
      document.getElementById('paymentCardholderName').value = '';
      document.getElementById('paymentCheckNumber').value = '';
      document.getElementById('paymentCheckBank').value = '';
      document.getElementById('paymentCheckDate').value = '';

      // Fetch payment methods
      const methodsRes = await fetch('../api/payment_methods.php?action=list');
      const methodsJson = await methodsRes.json();
      const methodSelect = document.getElementById('paymentMethod');
      methodSelect.innerHTML = '<option value="">Select Method</option>';
      if (methodsJson.success && methodsJson.data) {
        methodsJson.data.forEach(method => {
          methodSelect.innerHTML += `<option value="${method.method_id}" data-type="${method.method_type}">${method.method_label}</option>`;
        });
      }

      poModalOpen(document.getElementById('paymentModal'));
    } catch (e) { console.error('Error fetching PO for payment:', e); }
  }

  function onPaymentMethodChange() {
    const methodSelect = document.getElementById('paymentMethod');
    const selectedOption = methodSelect.options[methodSelect.selectedIndex];
    const methodType = selectedOption.dataset.type;

    // Hide all dynamic fields
    document.getElementById('paymentMethodFields').style.display = 'none';
    document.getElementById('bankFields').style.display = 'none';
    document.getElementById('walletFields').style.display = 'none';
    document.getElementById('cardFields').style.display = 'none';
    document.getElementById('checkFields').style.display = 'none';

    if (!methodType) return;

    // Show relevant fields based on method type
    document.getElementById('paymentMethodFields').style.display = 'block';

    switch (methodType) {
      case 'bank_transfer':
        document.getElementById('bankFields').style.display = 'block';
        break;
      case 'digital_wallet':
        document.getElementById('walletFields').style.display = 'block';
        break;
      case 'card':
        document.getElementById('cardFields').style.display = 'block';
        break;
      case 'check':
        document.getElementById('checkFields').style.display = 'block';
        break;
      case 'cash':
        // Cash doesn't need additional fields
        document.getElementById('paymentMethodFields').style.display = 'none';
        break;
    }
  }

  function closePaymentModal() {
    poModalClose(document.getElementById('paymentModal'));
  }

  async function submitPayment() {
    const amount = parseFloat(document.getElementById('paymentAmount').value) || 0;
    const methodId = document.getElementById('paymentMethod').value;
    const reference = document.getElementById('paymentReference').value.trim();
    const paymentDate = document.getElementById('paymentDate').value;
    const notes = document.getElementById('paymentNotes').value.trim();

    if (amount <= 0) {
      showPOAlert('Validation Error', 'Please enter a valid payment amount');
      return;
    }

    // Collect method-specific details
    const methodSelect = document.getElementById('paymentMethod');
    const selectedOption = methodSelect.options[methodSelect.selectedIndex];
    const methodType = selectedOption.dataset.type;
    let paymentDetails = {};

    switch (methodType) {
      case 'bank_transfer':
        paymentDetails = {
          bank_account: document.getElementById('paymentBankAccount').value.trim(),
          account_name: document.getElementById('paymentAccountName').value.trim()
        };
        break;
      case 'digital_wallet':
        paymentDetails = {
          wallet_number: document.getElementById('paymentWalletNumber').value.trim(),
          account_name: document.getElementById('paymentWalletName').value.trim()
        };
        break;
      case 'card':
        paymentDetails = {
          card_last4: document.getElementById('paymentCardLast4').value.trim(),
          cardholder_name: document.getElementById('paymentCardholderName').value.trim()
        };
        break;
      case 'check':
        paymentDetails = {
          check_number: document.getElementById('paymentCheckNumber').value.trim(),
          bank_name: document.getElementById('paymentCheckBank').value.trim(),
          check_date: document.getElementById('paymentCheckDate').value
        };
        break;
    }

    const fd = new FormData();
    fd.append('action', 'payment');
    fd.append('po_id', document.getElementById('paymentPOId').value);
    fd.append('amount', amount);
    fd.append('method_id', methodId || null);
    fd.append('reference_number', reference || null);
    fd.append('payment_date', paymentDate);
    fd.append('payment_details', JSON.stringify(paymentDetails));
    fd.append('notes', notes || null);
    fd.append('csrf_token', csrfToken);

    try {
      const res = await fetch('../api/purchase_orders.php', { method: 'POST', body: fd });
      const data = await res.json();
      if (data.success) {
        closePaymentModal();
        closePODetailsModal();
        fetchPurchaseOrders();
        showPOAlert('Success', 'Payment recorded successfully');
      } else {
        showPOAlert('Error', data.message || 'Error recording payment');
      }
    } catch (e) {
      showPOAlert('Error', 'Error recording payment');
    }
  }

  /* ── Add Modal ─────────────────────────────── */
  async function generateNextPONumber() {
    const year = new Date().getFullYear();
    try {
      const res = await fetch(`../api/purchase_orders.php?action=next_number&year=${year}`);
      const json = await res.json();
      if (json.success) {
        return json.po_number;
      }
    } catch (e) { console.error('Error generating PO number:', e); }
    // Fallback: generate locally
    const random = Math.floor(100000 + Math.random() * 900000);
    return `PO-${year}-${random}`;
  }

  async function openAddPOModal()  {
    poModalOpen(document.getElementById('addPOModal'));

    // Set default date to today
    document.getElementById('addPoDate').value = new Date().toISOString().split('T')[0];

    // Generate PO number
    const poNumber = await generateNextPONumber();
    document.getElementById('addPoNumber').value = poNumber;

    // Clear items
    poItems = [];
    renderPoItems();

    // Reset payment terms
    document.getElementById('addPoPaymentTerms').value = '';
  }

  function onAddSupplierChange() {
    const supplierId = document.getElementById('addPoSupplier').value;
    const supplier = suppliersData.find(s => s.supplier_id === supplierId);
    if (supplier && supplier.payment_terms) {
      document.getElementById('addPoPaymentTerms').value = supplier.payment_terms;
    }
  }

  function closeAddPOModal() {
    poModalClose(document.getElementById('addPOModal'));
    document.getElementById('addPOForm').reset();
    poItems = [];
    renderPoItems();
  }

  function buildDeliveryAddress(prefix) {
    const region = document.getElementById(prefix + 'region_display').value;
    const province = document.getElementById(prefix + 'province_display').value;
    const city = document.getElementById(prefix + 'city_display').value;
    const barangay = document.getElementById(prefix + 'barangay_display').value;
    const street = document.getElementById(prefix + 'full_address').value;
    let addr = '';
    if (street) addr += street;
    if (barangay) addr += (addr ? ', ' : '') + barangay;
    if (city) addr += (addr ? ', ' : '') + city;
    if (province) addr += (addr ? ', ' : '') + province;
    if (region) addr += (addr ? ', ' : '') + region;
    return addr;
  }

  function submitAddPO(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('addPOForm'));
    formData.append('action', 'add');
    formData.append('csrf_token', csrfToken);

    // Add items as JSON
    formData.append('items_json', JSON.stringify(poItems));

    // Delivery address codes are already in hidden fields

    // Add totals
    const total = parseFloat(document.getElementById('poTotal').textContent.replace('₱', '').replace(',', '')) || 0;
    formData.append('subtotal', total);
    formData.append('tax_amount', 0);
    formData.append('total_amount', total);

    fetch('../api/purchase_orders.php', { method: 'POST', body: formData })
      .then(r => r.json())
      .then(data => {
        closeAddPOModal();
        poItems = [];
        renderPoItems();
        if (data.success) fetchPurchaseOrders();
        else showPOAlert('Error', data.message || 'Error creating purchase order');
      })
      .catch(() => { closeAddPOModal(); showPOAlert('Error', 'Error creating purchase order'); });
  }

  /* ── Edit Modal ────────────────────────────── */
  async function openEditPOModal(poId) {
    try {
      const res = await fetch(`../api/purchase_orders.php?action=single&id=${poId}`);
      const json = await res.json();
      if (!json.success) return;

      const po = json.data;
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
      form.elements['notes'].value                       = po.notes || '';

      // Load delivery address codes
      document.getElementById('edit_po_full_address').value = po.delivery_address || '';
      document.getElementById('edit_po_region_code').value = po.delivery_region_code || '';
      document.getElementById('edit_po_province_code').value = po.delivery_province_code || '';
      document.getElementById('edit_po_city_code').value = po.delivery_city_code || '';
      document.getElementById('edit_po_barangay_code').value = po.delivery_barangay_code || '';

      // Load display fields with actual names from geo tables
      document.getElementById('edit_po_region_display').value = po.region_name || '';
      document.getElementById('edit_po_province_display').value = po.province_name || '';
      document.getElementById('edit_po_city_display').value = po.city_municipality_name || '';
      document.getElementById('edit_po_barangay_display').value = po.barangay_name || '';

      // Populate geo datalists for dropdowns
      if (po.delivery_region_code) {
        const provinces = await fetchProvinces(po.delivery_region_code);
        populateDatalist('province_list', provinces, 'province_name');
      }
      if (po.delivery_province_code) {
        const cities = await fetchCitiesMunicipalities(po.delivery_province_code);
        populateDatalist('city_list', cities, 'city_municipality_name');
      }
      if (po.delivery_city_code) {
        const barangays = await fetchBarangays(po.delivery_city_code);
        populateDatalist('barangay_list', barangays, 'barangay_name');
      }

      // Load items
      editPoItems = po.items ? po.items.map(item => ({
        product_id: item.product_id,
        product_name: item.product_name,
        uom_id: item.uom_id,
        uom_name: item.uom_name,
        uom_code: item.uom_code,
        quantity_ordered: parseFloat(item.ordered_qty),
        unit_cost: parseFloat(item.unit_cost),
        total_cost: parseFloat(item.total_cost)
      })) : [];
      renderEditPoItems();

      poModalOpen(document.getElementById('editPOModal'));
    } catch (e) { console.error('Error fetching PO for edit:', e); }
  }

  function closeEditPOModal() {
    poModalClose(document.getElementById('editPOModal'));
    document.getElementById('editPOForm').reset();
    editPoItems = [];
    renderEditPoItems();
  }

  function submitEditPO(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('editPOForm'));
    formData.append('action', 'edit');
    formData.append('csrf_token', csrfToken);

    // Add items as JSON
    formData.append('items_json', JSON.stringify(editPoItems));

    // Build full delivery address
    const fullAddr = buildDeliveryAddress('edit_po_');
    if (fullAddr) formData.set('delivery_address', fullAddr);

    // Add totals
    const total = parseFloat(document.getElementById('editPoTotal').textContent.replace('₱', '').replace(',', '')) || 0;
    formData.append('subtotal', total);
    formData.append('tax_amount', 0);
    formData.append('total_amount', total);

    fetch('../api/purchase_orders.php', { method: 'POST', body: formData })
      .then(r => r.json())
      .then(data => {
        closeEditPOModal();
        if (data.success) fetchPurchaseOrders();
        else showPOAlert('Error', data.message || 'Error updating purchase order');
      })
      .catch(() => { closeEditPOModal(); showPOAlert('Error', 'Error updating purchase order'); });
  }

  /* ── Delete ────────────────────────────────── */
  function deletePO(btn) {
    const poId     = btn.dataset.id;
    const poNumber = btn.dataset.number;
    showPOAlert('Confirm Delete', `Delete PO "${poNumber}"? This cannot be undone.`, () => {
      const fd = new FormData();
      fd.append('action', 'delete');
      fd.append('po_id', poId);
      fd.append('csrf_token', csrfToken);

      fetch('../api/purchase_orders.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
          if (data.success) fetchPurchaseOrders();
          else showPOAlert('Error', data.message || 'Error deleting purchase order');
        })
        .catch(() => showPOAlert('Error', 'Error deleting purchase order'));
    });
  }

  /* ── PO Items Management ────────────────────── */
  function openAddItemModal(context) {
    document.getElementById('addItemModalContext').value = context;
    document.getElementById('poItemProduct').value = '';
    document.getElementById('poItemUom').value = '';
    document.getElementById('poItemQty').value = '';
    document.getElementById('poItemCost').value = '';
    document.getElementById('poItemTotal').textContent = '₱0.00';
    poModalOpen(document.getElementById('addItemModal'));
  }

  function closeAddItemModal() {
    poModalClose(document.getElementById('addItemModal'));
  }

  async function onProductSelectChange() {
    const productInput = document.getElementById('poItemProduct');
    const selectedValue = productInput.value;

    // Find the matching product from datalist
    const datalist = document.getElementById('product_search_list');
    const selectedOption = Array.from(datalist.options).find(opt => opt.value === selectedValue);

    if (selectedOption) {
      const productId = selectedOption.dataset.productId;
      const uomId = selectedOption.dataset.uomId;
      const uomName = selectedOption.dataset.uomName;
      const uomCode = selectedOption.dataset.uomCode;

      document.getElementById('poItemProductId').value = productId;

      // Fetch UOM conversions for this product
      await fetchProductUOMs(productId, uomId);
    } else {
      // Clear if no match
      document.getElementById('poItemProductId').value = '';
      document.getElementById('poItemUomId').value = '';
      document.getElementById('poItemUom').innerHTML = '<option value="">Select UOM</option>';
    }
  }

  async function fetchProductUOMs(productId, defaultUomId) {
    try {
      const res = await fetch(`../api/product_uom_conversions.php?action=list&product_id=${productId}`);
      const json = await res.json();
      if (json.success) {
        const uomSelect = document.getElementById('poItemUom');
        uomSelect.innerHTML = '<option value="">Select UOM</option>';

        // Add base UOM and conversion UOMs
        const uniqueUOMs = new Map();
        json.data.forEach(conv => {
          const key = conv.to_uom_id;
          if (!uniqueUOMs.has(key)) {
            uniqueUOMs.set(key, {
              uom_id: conv.to_uom_id,
              uom_name: conv.to_uom_name,
              uom_code: conv.to_uom_code,
              conversion_factor: conv.conversion_factor,
              is_default: conv.is_default
            });
          }
        });

        // Also add the base UOM if not in conversions
        const baseUom = productsData.find(p => p.product_id === productId);
        if (baseUom && baseUom.uom_id && !uniqueUOMs.has(baseUom.uom_id)) {
          uniqueUOMs.set(baseUom.uom_id, {
            uom_id: baseUom.uom_id,
            uom_name: baseUom.uom_name,
            uom_code: baseUom.uom_code || '',
            conversion_factor: 1,
            is_default: 1
          });
        }

        // Populate dropdown
        uniqueUOMs.forEach((uom, id) => {
          const option = document.createElement('option');
          option.value = uom.uom_id;
          option.textContent = `${uom.uom_name} (${uom.uom_code})`;
          option.dataset.conversionFactor = uom.conversion_factor;
          if (uom.uom_id === defaultUomId || uom.is_default) {
            option.selected = true;
            document.getElementById('poItemUomId').value = uom.uom_id;
          }
          uomSelect.appendChild(option);
        });

        // Call onUomChange to populate dataset for auto-selected UOM
        if (uomSelect.value) {
          onUomChange();
        }
      }
    } catch (e) { console.error('Error fetching UOM conversions:', e); }
  }

  function onUomChange() {
    const uomSelect = document.getElementById('poItemUom');
    const selectedOption = uomSelect.options[uomSelect.selectedIndex];
    if (selectedOption && selectedOption.value) {
      document.getElementById('poItemUomId').value = selectedOption.value;
      // Store UOM name and code in hidden fields for later use
      const textParts = selectedOption.textContent.match(/(.+?)\s*\((.+?)\)/);
      if (textParts) {
        document.getElementById('poItemUom').dataset.uomName = textParts[1];
        document.getElementById('poItemUom').dataset.uomCode = textParts[2];
      } else {
        document.getElementById('poItemUom').dataset.uomName = selectedOption.textContent;
        document.getElementById('poItemUom').dataset.uomCode = '';
      }
    } else {
      document.getElementById('poItemUomId').value = '';
      delete document.getElementById('poItemUom').dataset.uomName;
      delete document.getElementById('poItemUom').dataset.uomCode;
    }
  }

  function calculateItemTotal() {
    const qty = parseFloat(document.getElementById('poItemQty').value) || 0;
    const cost = parseFloat(document.getElementById('poItemCost').value) || 0;
    const total = qty * cost;
    document.getElementById('poItemTotal').textContent = '₱' + total.toFixed(2);
  }

  function addItemToPO() {
    const productId = document.getElementById('poItemProductId').value;
    const uomId = document.getElementById('poItemUomId').value;
    const productInput = document.getElementById('poItemProduct').value;
    const qty = parseFloat(document.getElementById('poItemQty').value) || 0;
    const cost = parseFloat(document.getElementById('poItemCost').value) || 0;

    if (!productId || !uomId || qty <= 0 || cost <= 0) {
      showPOAlert('Validation Error', 'Please fill in all required fields');
      return;
    }

    const product = productsData.find(p => p.product_id === productId);
    const uomSelect = document.getElementById('poItemUom');
    const uomName = uomSelect.dataset.uomName || '';
    const uomCode = uomSelect.dataset.uomCode || '';

    const item = {
      product_id: productId,
      product_name: product ? product.product_name : productInput,
      uom_id: uomId,
      uom_name: uomName,
      uom_code: uomCode,
      quantity_ordered: qty,
      unit_cost: cost,
      total_cost: qty * cost
    };

    const context = document.getElementById('addItemModalContext').value;
    if (context === 'add') {
      poItems.push(item);
      renderPoItems();
    } else {
      editPoItems.push(item);
      renderEditPoItems();
    }

    closeAddItemModal();
  }

  function removePoItem(index, context) {
    if (context === 'add') {
      poItems.splice(index, 1);
      renderPoItems();
    } else {
      editPoItems.splice(index, 1);
      renderEditPoItems();
    }
  }

  function renderPoItems() {
    const list = document.getElementById('poItemsList');
    let html = `<div class="po-item-row" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 100px 40px; gap: 8px; align-items: center; padding: 8px 0; border-bottom: 1px dashed rgba(0,0,0,.08);">
      <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">Product</div>
      <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">UOM</div>
      <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">Qty</div>
      <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">Unit Cost</div>
      <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">Total</div>
      <div></div>
    </div>`;

    let subtotal = 0;
    poItems.forEach((item, index) => {
      subtotal += item.total_cost;
      html += `<div class="po-item-row" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 100px 40px; gap: 8px; align-items: center; padding: 10px 0; border-bottom: 1px solid rgba(0,0,0,.05);">
        <div style="font-weight: 500;">${item.product_name}</div>
        <div>${item.uom_code || item.uom_name}</div>
        <div>${item.quantity_ordered.toFixed(3)}</div>
        <div>₱${item.unit_cost.toFixed(2)}</div>
        <div style="font-weight: 600;">₱${item.total_cost.toFixed(2)}</div>
        <div><button type="button" onclick="removePoItem(${index}, 'add')" class="po-action-btn danger" style="padding: 4px 8px; font-size: 11px;"><i class="fas fa-trash"></i></button></div>
      </div>`;
    });

    list.innerHTML = html;

    document.getElementById('poTotal').textContent = '₱' + subtotal.toFixed(2);
  }

  function renderEditPoItems() {
    const list = document.getElementById('editPoItemsList');
    let html = `<div class="po-item-row" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 100px 40px; gap: 8px; align-items: center; padding: 8px 0; border-bottom: 1px dashed rgba(0,0,0,.08);">
      <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">Product</div>
      <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">UOM</div>
      <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">Qty</div>
      <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">Unit Cost</div>
      <div style="font-size: 12px; font-weight: 500; color: var(--color-text-secondary);">Total</div>
      <div></div>
    </div>`;

    let subtotal = 0;
    editPoItems.forEach((item, index) => {
      subtotal += item.total_cost;
      html += `<div class="po-item-row" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 100px 40px; gap: 8px; align-items: center; padding: 10px 0; border-bottom: 1px solid rgba(0,0,0,.05);">
        <div style="font-weight: 500;">${item.product_name}</div>
        <div>${item.uom_code || item.uom_name}</div>
        <div>${item.quantity_ordered.toFixed(3)}</div>
        <div>₱${item.unit_cost.toFixed(2)}</div>
        <div style="font-weight: 600;">₱${item.total_cost.toFixed(2)}</div>
        <div><button type="button" onclick="removePoItem(${index}, 'edit')" class="po-action-btn danger" style="padding: 4px 8px; font-size: 11px;"><i class="fas fa-trash"></i></button></div>
      </div>`;
    });

    list.innerHTML = html;

    document.getElementById('editPoTotal').textContent = '₱' + subtotal.toFixed(2);
  }

  /* ── Quick Product Modal ─────────────────────── */
  function openQuickProductModal() {
    document.getElementById('quickProductName').value = '';
    document.getElementById('quickProductCode').value = '';
    document.getElementById('quickProductCategory').value = '';
    document.getElementById('quickProductUom').value = '';
    document.getElementById('quickProductType').value = 'consumable';
    poModalOpen(document.getElementById('quickProductModal'));
  }

  function closeQuickProductModal() {
    poModalClose(document.getElementById('quickProductModal'));
  }

  async function saveQuickProduct() {
    const name = document.getElementById('quickProductName').value.trim();
    const code = document.getElementById('quickProductCode').value.trim();
    const categoryId = document.getElementById('quickProductCategory').value;
    const uomId = document.getElementById('quickProductUom').value;
    const type = document.getElementById('quickProductType').value;

    if (!name || !categoryId || !uomId) {
      showPOAlert('Validation Error', 'Please fill in all required fields');
      return;
    }

    try {
      const res = await fetch('../api/products.php?action=create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          product_name: name,
          product_code: code,
          category_id: categoryId,
          uom_id: uomId,
          product_type: type,
          is_active: 1,
          csrf_token: csrfToken
        })
      });
      const json = await res.json();
      if (json.success) {
        // Refresh products and select the new one
        await fetchRelated();
        if (json.product_id) {
          document.getElementById('poItemProduct').value = json.product_id;
          onProductSelectChange();
        }
        closeQuickProductModal();
        showPOAlert('Success', 'Product created successfully');
      } else {
        showPOAlert('Error', json.message || 'Error creating product');
      }
    } catch (e) {
      console.error(e);
      showPOAlert('Error', 'Error creating product');
    }
  }

  /* ── Geocode Functions ───────────────────────── */
  async function fetchRegions() {
    try {
      const res = await fetch('../api/geocode.php?action=regions');
      const json = await res.json();
      if (json.success) return json.data;
    } catch (e) { console.error('fetchRegions:', e); }
    return [];
  }

  async function fetchProvinces(regionCode) {
    try {
      const res = await fetch(`../api/geocode.php?action=provinces&region_code=${encodeURIComponent(regionCode)}`);
      const json = await res.json();
      if (json.success) return json.data;
    } catch (e) { console.error('fetchProvinces:', e); }
    return [];
  }

  async function fetchCitiesMunicipalities(provinceCode) {
    try {
      const res = await fetch(`../api/geocode.php?action=cities_municipalities&province_code=${encodeURIComponent(provinceCode)}`);
      const json = await res.json();
      if (json.success) return json.data;
    } catch (e) { console.error('fetchCities:', e); }
    return [];
  }

  async function fetchBarangays(cityMunicipalityCode) {
    try {
      const res = await fetch(`../api/geocode.php?action=barangays&city_municipality_code=${encodeURIComponent(cityMunicipalityCode)}`);
      const json = await res.json();
      if (json.success) return json.data;
    } catch (e) { console.error('fetchBarangays:', e); }
    return [];
  }

  function populateDatalist(datalistId, data, textField) {
    const datalist = document.getElementById(datalistId);
    if (!datalist) return;
    datalist.innerHTML = '';
    data.forEach(item => {
      const option = document.createElement('option');
      option.value = item[textField];
      if (item.region_code) option.dataset.code = item.region_code;
      if (item.province_code) option.dataset.code = item.province_code;
      if (item.city_municipality_code) option.dataset.code = item.city_municipality_code;
      if (item.barangay_code) option.dataset.code = item.barangay_code;
      datalist.appendChild(option);
    });
  }

  // Add Modal Geocode Handlers
  async function onPORegionChange() {
    const regionInput = document.getElementById('po_region_display');
    const regionName = regionInput.value;
    const regions = await fetchRegions();
    const region = regions.find(r => r.region_name === regionName);
    if (region) {
      regionInput.dataset.code = region.region_code;
      document.getElementById('po_region_code').value = region.region_code;
      const provinces = await fetchProvinces(region.region_code);
      populateDatalist('province_list', provinces, 'province_name');
    }
  }

  async function onPOProvinceChange() {
    const provinceInput = document.getElementById('po_province_display');
    const provinceName = provinceInput.value;
    const regionCode = document.getElementById('po_region_code').value;
    if (regionCode) {
      const provinces = await fetchProvinces(regionCode);
      const province = provinces.find(p => p.province_name === provinceName);
      if (province) {
        provinceInput.dataset.code = province.province_code;
        document.getElementById('po_province_code').value = province.province_code;
        const cities = await fetchCitiesMunicipalities(province.province_code);
        populateDatalist('city_list', cities, 'city_municipality_name');
      }
    }
  }

  async function onPOCityChange() {
    const cityInput = document.getElementById('po_city_display');
    const cityName = cityInput.value;
    const provinceCode = document.getElementById('po_province_code').value;
    if (provinceCode) {
      const cities = await fetchCitiesMunicipalities(provinceCode);
      const city = cities.find(c => c.city_municipality_name === cityName);
      if (city) {
        cityInput.dataset.code = city.city_municipality_code;
        document.getElementById('po_city_code').value = city.city_municipality_code;
        const barangays = await fetchBarangays(city.city_municipality_code);
        populateDatalist('barangay_list', barangays, 'barangay_name');
      }
    }
  }

  async function onPOBarangayChange() {
    const barangayInput = document.getElementById('po_barangay_display');
    const barangayName = barangayInput.value;
    const cityCode = document.getElementById('po_city_code').value;
    if (cityCode) {
      const barangays = await fetchBarangays(cityCode);
      const barangay = barangays.find(b => b.barangay_name === barangayName);
      if (barangay) {
        barangayInput.dataset.code = barangay.barangay_code;
        document.getElementById('po_barangay_code').value = barangay.barangay_code;
      }
    }
  }

  // Edit Modal Geocode Handlers
  async function onEditPORegionChange() {
    const regionInput = document.getElementById('edit_po_region_display');
    const regionName = regionInput.value;
    const regions = await fetchRegions();
    const region = regions.find(r => r.region_name === regionName);
    if (region) {
      regionInput.dataset.code = region.region_code;
      document.getElementById('edit_po_region_code').value = region.region_code;
      const provinces = await fetchProvinces(region.region_code);
      populateDatalist('province_list', provinces, 'province_name');
    }
  }

  async function onEditPOProvinceChange() {
    const provinceInput = document.getElementById('edit_po_province_display');
    const provinceName = provinceInput.value;
    const regionCode = document.getElementById('edit_po_region_code').value;
    if (regionCode) {
      const provinces = await fetchProvinces(regionCode);
      const province = provinces.find(p => p.province_name === provinceName);
      if (province) {
        provinceInput.dataset.code = province.province_code;
        document.getElementById('edit_po_province_code').value = province.province_code;
        const cities = await fetchCitiesMunicipalities(province.province_code);
        populateDatalist('city_list', cities, 'city_municipality_name');
      }
    }
  }

  async function onEditPOCityChange() {
    const cityInput = document.getElementById('edit_po_city_display');
    const cityName = cityInput.value;
    const provinceCode = document.getElementById('edit_po_province_code').value;
    if (provinceCode) {
      const cities = await fetchCitiesMunicipalities(provinceCode);
      const city = cities.find(c => c.city_municipality_name === cityName);
      if (city) {
        cityInput.dataset.code = city.city_municipality_code;
        document.getElementById('edit_po_city_code').value = city.city_municipality_code;
        const barangays = await fetchBarangays(city.city_municipality_code);
        populateDatalist('barangay_list', barangays, 'barangay_name');
      }
    }
  }

  async function onEditPOBarangayChange() {
    const barangayInput = document.getElementById('edit_po_barangay_display');
    const barangayName = barangayInput.value;
    const cityCode = document.getElementById('edit_po_city_code').value;
    if (cityCode) {
      const barangays = await fetchBarangays(cityCode);
      const barangay = barangays.find(b => b.barangay_name === barangayName);
      if (barangay) {
        barangayInput.dataset.code = barangay.barangay_code;
        document.getElementById('edit_po_barangay_code').value = barangay.barangay_code;
      }
    }
  }

  /* ── Custom Alert Functions ─────────────────────── */
  function showPOAlert(title, message, onConfirm) {
    document.getElementById('poAlertTitle').textContent = title;
    document.getElementById('poAlertMessage').textContent = message;
    poModalOpen(document.getElementById('poAlertModal'));

    const confirmBtn = document.getElementById('poAlertConfirmBtn');
    confirmBtn.onclick = function() {
      closePOAlert();
      if (onConfirm) onConfirm();
    };
  }

  function closePOAlert() {
    poModalClose(document.getElementById('poAlertModal'));
  }

  /* ── Modal Portal Manager ───────────────────────────
   * Moves every overlay to <body> before opening so that
   * position:fixed always anchors to the real viewport,
   * not a transformed/scrollable dashboard ancestor.
   * Also locks the nearest scrollable parent (not just body).
   * ─────────────────────────────────────────────────── */
  let _poScrollLockTarget = null;
  let _poScrollLockSaved  = '';
  let _poOpenCount        = 0;

  function _findScrollParent(el) {
    // Walk up from the overlay's original location to find the scrolling ancestor
    let node = el.parentElement;
    while (node && node !== document.body) {
      const style = window.getComputedStyle(node);
      if (/auto|scroll/.test(style.overflow + style.overflowY)) return node;
      node = node.parentElement;
    }
    return document.body;
  }

  function poModalOpen(overlayEl) {
    // Teleport to <body> if not already there (portal pattern)
    if (overlayEl.parentElement !== document.body) {
      // Remember original location so we can restore on close
      if (!overlayEl._poOrigParent) {
        overlayEl._poOrigParent    = overlayEl.parentElement;
        overlayEl._poOrigNextSibling = overlayEl.nextSibling;
      }
      document.body.appendChild(overlayEl);
    }

    overlayEl.classList.add('open');
    _poOpenCount++;

    // Lock scroll — find the real scrolling container
    if (_poOpenCount === 1) {
      _poScrollLockTarget = _findScrollParent(overlayEl._poOrigParent || overlayEl);
      _poScrollLockSaved  = _poScrollLockTarget.style.overflow;
      _poScrollLockTarget.style.overflow = 'hidden';
      // Always lock body too as a fallback
      document.body.style.overflow = 'hidden';
    }
  }

  function poModalClose(overlayEl) {
    if (!overlayEl) return;
    overlayEl.classList.remove('open');
    // Reset internal scroll position of the modal body
    const body = overlayEl.querySelector('.po-modal-body');
    if (body) body.scrollTop = 0;

    _poOpenCount = Math.max(0, _poOpenCount - 1);

    // Release scroll lock when no modals remain open
    if (_poOpenCount === 0) {
      if (_poScrollLockTarget) {
        _poScrollLockTarget.style.overflow = _poScrollLockSaved;
        _poScrollLockTarget = null;
        _poScrollLockSaved  = '';
      }
      document.body.style.overflow = '';
    }
  }

  /* ── Refactored open/close functions ─────────────── */
  function closePODetailsModal() {
    poModalClose(document.getElementById('poDetailsModal'));
  }

  function closeReceiveModal() {
    poModalClose(document.getElementById('receivePOModal'));
    receiveItems = [];
  }

  function closePaymentModal() {
    poModalClose(document.getElementById('paymentModal'));
  }

  function closeAddPOModal() {
    poModalClose(document.getElementById('addPOModal'));
    document.getElementById('addPOForm').reset();
    poItems = [];
    renderPoItems();
  }

  function closeEditPOModal() {
    poModalClose(document.getElementById('editPOModal'));
    document.getElementById('editPOForm').reset();
    editPoItems = [];
    renderEditPoItems();
  }

  function closeAddItemModal() {
    poModalClose(document.getElementById('addItemModal'));
  }

  function closeQuickProductModal() {
    poModalClose(document.getElementById('quickProductModal'));
  }

  /* ── Init ──────────────────────────────────── */
  document.addEventListener('DOMContentLoaded', () => {
    fetchPurchaseOrders();
    fetchRelated();
  });
</script>