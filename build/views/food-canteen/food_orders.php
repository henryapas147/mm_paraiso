<?php if (false) {} ?>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Lora:ital,wght@0,600;1,500&display=swap" rel="stylesheet">
<style>
  /* ── Design Tokens (matches menu_items.php system) ── */
  :root {
    --brand:          #C84B31;
    --brand-dark:     #A03525;
    --brand-light:    #FDF0ED;
    --accent:         #F0A500;
    --accent-light:   #FFF8E7;
    --surface:        #FFFFFF;
    --surface-2:      #FAFAFA;
    --border:         #EDEAE6;
    --border-strong:  #D6D0C8;
    --text-primary:   #1A1410;
    --text-secondary: #6B6460;
    --text-muted:     #A09890;
    --success:        #2D7D4F;
    --success-bg:     #EDFAF3;
    --warning:        #B45309;
    --warning-bg:     #FEF7EC;
    --danger:         #C0392B;
    --danger-bg:      #FEF0EE;
    --info:           #1D5FA1;
    --info-bg:        #EFF6FF;
    --radius-sm:      6px;
    --radius-md:      10px;
    --radius-lg:      16px;
    --radius-xl:      20px;
    --shadow-card:    0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.06);
    --shadow-modal:   0 24px 64px rgba(0,0,0,.18);
    --font-display:   'Lora', Georgia, serif;
    --font-body:      'DM Sans', system-ui, sans-serif;
  }

  /* ── Stats Grid ── */
  .fo-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }
  @media (max-width: 1024px) { .fo-stats-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 600px)  { .fo-stats-grid { grid-template-columns: 1fr 1fr; } }

  .fo-stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 20px 22px;
    box-shadow: var(--shadow-card);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: box-shadow .2s, transform .2s;
  }
  .fo-stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(0,0,0,.10); }

  .fo-stat-icon {
    width: 48px; height: 48px;
    border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
  }
  .fo-stat-icon.total   { background: #EFF3FF; color: #3B5BDB; }
  .fo-stat-icon.pending { background: var(--warning-bg); color: var(--warning); }
  .fo-stat-icon.ready   { background: var(--success-bg); color: var(--success); }
  .fo-stat-icon.unpaid  { background: var(--danger-bg);  color: var(--danger); }

  .fo-stat-label {
    font-family: var(--font-body);
    font-size: 11.5px; font-weight: 600;
    letter-spacing: .06em; text-transform: uppercase;
    color: var(--text-muted); margin-bottom: 4px;
  }
  .fo-stat-value {
    font-family: var(--font-display);
    font-size: 28px; font-weight: 600;
    color: var(--text-primary); line-height: 1;
  }

  /* ── Main Table Card ── */
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

  .fo-card-title {
    font-family: var(--font-display);
    font-size: 20px; font-weight: 600;
    color: var(--text-primary); margin: 0;
    font-style: italic;
  }

  .fo-card-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

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
  .fo-btn-primary:hover { background: var(--brand-dark); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(200,75,49,.38); }
  .fo-btn-primary:active { transform: translateY(0); }

  /* ── Table ── */
  .fo-table { width: 100%; border-collapse: collapse; font-family: var(--font-body); }
  .fo-table thead tr { background: var(--surface-2); border-bottom: 1px solid var(--border); }
  .fo-table th {
    padding: 11px 16px;
    font-size: 11px; font-weight: 700;
    letter-spacing: .07em; text-transform: uppercase;
    color: var(--text-muted); white-space: nowrap; text-align: left;
  }
  .fo-table th.right  { text-align: right; }
  .fo-table th.center { text-align: center; }

  .order-row td {
    padding: 13px 16px;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
    font-size: 13.5px;
    color: var(--text-primary);
  }
  .order-row:last-child td { border-bottom: none; }
  .order-row { transition: background .12s; }
  .order-row:hover td { background: #FDF9F8; }

  /* Order number cell */
  .fo-order-num {
    font-weight: 700; font-size: 13px;
    color: var(--text-primary); letter-spacing: .01em;
  }
  .fo-order-time {
    font-size: 11.5px; color: var(--text-muted); margin-top: 2px;
  }

  /* Customer cell */
  .fo-customer-name { font-weight: 600; font-size: 13.5px; }
  .fo-reservation-code {
    font-size: 11.5px; color: var(--text-muted);
    font-family: 'Courier New', monospace; margin-top: 2px;
  }

  /* Type cell */
  .fo-type-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px;
    border-radius: 999px; font-size: 11.5px; font-weight: 600;
    white-space: nowrap;
  }
  .fo-type-badge.dine-in      { background: #EFF6FF; color: #1D5FA1; }
  .fo-type-badge.room-service { background: #F5F0FF; color: #6230B5; }
  .fo-type-badge.takeout      { background: #FFF8ED; color: #9A5B00; }

  .fo-table-num {
    font-size: 11.5px; color: var(--text-muted); margin-top: 3px;
  }

  /* Amount cell */
  .fo-amount {
    font-weight: 700; font-size: 14px; text-align: right;
    color: var(--text-primary);
  }
  .fo-payment-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 2px 8px; border-radius: 999px;
    font-size: 11px; font-weight: 600; margin-top: 3px;
    white-space: nowrap;
  }
  .fo-payment-badge.unpaid         { background: var(--danger-bg); color: var(--danger); }
  .fo-payment-badge.paid           { background: var(--success-bg); color: var(--success); }
  .fo-payment-badge.charged-to-room{ background: var(--info-bg); color: var(--info); }

  /* Status badge — order status */
  .fo-status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 11px; border-radius: 999px;
    font-size: 11.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .04em;
    white-space: nowrap;
  }
  .fo-status-badge::before {
    content: ''; display: block;
    width: 6px; height: 6px; border-radius: 50%;
    background: currentColor; opacity: .7;
  }
  .fo-status-badge.pending   { background: #FEF7EC; color: #B45309; }
  .fo-status-badge.confirmed { background: #EFF6FF; color: #1D5FA1; }
  .fo-status-badge.preparing { background: #F5F0FF; color: #6230B5; }
  .fo-status-badge.ready     { background: var(--success-bg); color: var(--success); }
  .fo-status-badge.delivered { background: #F4F4F5; color: #52525B; }
  .fo-status-badge.cancelled { background: var(--danger-bg); color: var(--danger); }

  /* Action buttons */
  .fo-actions { display: flex; align-items: center; gap: 6px; justify-content: flex-end; }
  .fo-action-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 11px;
    font-family: var(--font-body); font-size: 12px; font-weight: 600;
    border: 1px solid var(--border);
    background: var(--surface); color: var(--text-secondary);
    border-radius: var(--radius-sm);
    cursor: pointer; white-space: nowrap;
    transition: all .15s;
  }
  .fo-action-btn:hover        { background: var(--surface-2); color: var(--text-primary); border-color: var(--border-strong); }
  .fo-action-btn.view:hover   { background: var(--info-bg);    color: var(--info);    border-color: #C5D0FA; }
  .fo-action-btn.status:hover { background: var(--warning-bg); color: var(--warning); border-color: #F9D89A; }
  .fo-action-btn.pay:hover    { background: var(--success-bg); color: var(--success); border-color: #B7E4CC; }
  .fo-action-btn.edit:hover   { background: #EFF3FF; color: #3B5BDB; border-color: #C5D0FA; }
  .fo-action-btn.del:hover    { background: var(--danger-bg); color: var(--danger); border-color: #F5BCBA; }

  /* Empty state */
  .fo-empty { text-align: center; padding: 64px 24px; }
  .fo-empty-icon {
    width: 72px; height: 72px; border-radius: 50%;
    background: var(--surface-2); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; color: var(--text-muted);
    margin: 0 auto 20px;
  }
  .fo-empty h4 {
    font-family: var(--font-display); font-size: 18px; font-weight: 600;
    color: var(--text-primary); margin: 0 0 6px; font-style: italic;
  }
  .fo-empty p { font-size: 13.5px; color: var(--text-secondary); margin: 0 0 20px; }

  .fo-loading-row td {
    padding: 24px 16px; text-align: center;
    color: var(--text-muted); font-family: var(--font-body); font-size: 13.5px;
  }

  /* ── Modals ── */
  .fo-modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(20,14,10,.50);
    backdrop-filter: blur(3px);
    z-index: 9000;
    align-items: center; justify-content: center;
    padding: 20px;
    animation: foOverlayIn .18s ease;
  }
  .fo-modal-overlay.open { display: flex; }
  @keyframes foOverlayIn { from { opacity:0; } to { opacity:1; } }

  .fo-modal {
    background: var(--surface);
    border-radius: var(--radius-xl);
    border: 1px solid var(--border);
    width: 100%; max-width: 700px;
    max-height: 90vh; overflow-y: auto;
    box-shadow: var(--shadow-modal);
    animation: foModalIn .22s cubic-bezier(.34,1.35,.64,1);
  }
  .fo-modal.wide { max-width: 860px; }
  @keyframes foModalIn {
    from { opacity:0; transform: scale(.94) translateY(10px); }
    to   { opacity:1; transform: scale(1) translateY(0); }
  }

  .fo-modal-header {
    padding: 22px 26px 18px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; background: var(--surface); z-index: 1;
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
  }
  .fo-modal-title {
    font-family: var(--font-display);
    font-size: 19px; font-weight: 600; font-style: italic;
    color: var(--text-primary); margin: 0;
  }
  .fo-modal-close {
    width: 34px; height: 34px;
    display: flex; align-items: center; justify-content: center;
    background: var(--surface-2); border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text-secondary); font-size: 15px; cursor: pointer;
    transition: all .15s;
  }
  .fo-modal-close:hover { background: var(--danger-bg); color: var(--danger); border-color: #F5BCBA; }

  .fo-modal-body   { padding: 24px 26px; }
  .fo-modal-footer {
    padding: 16px 26px; border-top: 1px solid var(--border);
    display: flex; justify-content: flex-end; gap: 10px;
    position: sticky; bottom: 0; background: var(--surface);
    border-radius: 0 0 var(--radius-xl) var(--radius-xl);
  }

  /* ── Form Styles ── */
  .fo-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
  @media (max-width: 540px) { .fo-form-grid { grid-template-columns: 1fr; } }
  .fo-form-group { display: flex; flex-direction: column; gap: 6px; }
  .fo-form-group.full { grid-column: 1 / -1; }

  .fo-label {
    font-size: 11px; font-weight: 700;
    letter-spacing: .07em; text-transform: uppercase;
    color: var(--text-secondary);
  }
  .fo-label .req { color: var(--brand); margin-left: 2px; }

  .fo-input, .fo-select, .fo-textarea {
    font-family: var(--font-body); font-size: 13.5px; color: var(--text-primary);
    background: var(--surface-2); border: 1px solid var(--border);
    border-radius: var(--radius-md); padding: 9px 13px;
    outline: none; width: 100%;
    transition: border-color .15s, box-shadow .15s, background .15s;
  }
  .fo-input:focus, .fo-select:focus, .fo-textarea:focus {
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(200,75,49,.10);
    background: var(--surface);
  }
  .fo-textarea { resize: vertical; min-height: 72px; }

  /* Cancel button */
  .fo-btn-cancel {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px;
    font-family: var(--font-body); font-size: 13px; font-weight: 600;
    border: 1px solid var(--border-strong); border-radius: var(--radius-md);
    background: var(--surface); color: var(--text-secondary);
    cursor: pointer; transition: all .15s;
  }
  .fo-btn-cancel:hover { background: var(--surface-2); color: var(--text-primary); }

  /* ── Order Detail View ── */
  .fo-detail-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 0;
    border: 1px solid var(--border); border-radius: var(--radius-md);
    overflow: hidden; margin-bottom: 20px;
  }
  .fo-detail-cell {
    padding: 13px 16px;
    border-bottom: 1px solid var(--border);
  }
  .fo-detail-cell:nth-last-child(-n+2) { border-bottom: none; }
  .fo-detail-cell:nth-child(odd) { border-right: 1px solid var(--border); background: var(--surface-2); }
  .fo-detail-label {
    font-size: 10.5px; font-weight: 700; letter-spacing: .06em;
    text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;
  }
  .fo-detail-value {
    font-size: 13.5px; font-weight: 600; color: var(--text-primary);
  }

  .fo-items-section {
    background: var(--surface-2); border: 1px solid var(--border);
    border-radius: var(--radius-md); overflow: hidden;
  }
  .fo-items-header {
    padding: 11px 16px;
    font-size: 11px; font-weight: 700; letter-spacing: .07em;
    text-transform: uppercase; color: var(--text-muted);
    border-bottom: 1px solid var(--border);
    background: var(--surface);
  }
  .fo-item-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 12px 16px; border-bottom: 1px solid var(--border);
    font-family: var(--font-body);
  }
  .fo-item-row:last-child { border-bottom: none; }
  .fo-item-name { font-weight: 600; font-size: 13.5px; color: var(--text-primary); }
  .fo-item-qty  { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
  .fo-item-total{ font-weight: 700; font-size: 14px; color: var(--text-primary); }

  .fo-notes-box {
    background: var(--accent-light);
    border: 1px solid #EDD99A;
    border-radius: var(--radius-md);
    padding: 13px 16px; margin-bottom: 16px;
    font-size: 13px; color: #7A5C00;
    display: flex; gap: 10px; align-items: flex-start;
  }
  .fo-notes-box i { margin-top: 1px; flex-shrink: 0; }

  /* Scrollbar polish */
  .fo-modal::-webkit-scrollbar { width: 5px; }
  .fo-modal::-webkit-scrollbar-track { background: transparent; }
  .fo-modal::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 4px; }

  .fo-card *, .fo-modal * { box-sizing: border-box; }

  /* ── Status History ── */
  .fo-history-row {
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  .fo-history-row:last-child { border-bottom: none; }
  .fo-history-time {
    font-size: 11px;
    color: var(--text-muted);
  }
  .fo-history-change {
    display: flex;
    align-items: center;
    font-size: 13px;
    font-weight: 500;
  }
  .fo-history-old {
    color: var(--text-muted);
    text-decoration: line-through;
  }
  .fo-history-new {
    color: var(--text-primary);
    font-weight: 600;
  }
  .fo-history-user {
    font-size: 12px;
    color: var(--text-secondary);
  }
  .fo-history-reason {
    font-size: 12px;
    color: var(--text-muted);
    font-style: italic;
  }

  /* ── Toast Notifications ── */
  .fo-toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .fo-toast {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 14px 18px;
    box-shadow: var(--shadow-modal);
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 280px;
    max-width: 400px;
    animation: foToastIn .3s cubic-bezier(.34,1.35,.64,1);
  }
  @keyframes foToastIn {
    from { opacity: 0; transform: translateX(30px); }
    to { opacity: 1; transform: translateX(0); }
  }
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  @keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .fo-toast.success { border-left: 4px solid var(--success); }
  .fo-toast.error { border-left: 4px solid var(--danger); }
  .fo-toast.info { border-left: 4px solid var(--info); }
  .fo-toast-icon {
    width: 24px; height: 24px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%; flex-shrink: 0;
  }
  .fo-toast.success .fo-toast-icon { background: var(--success-bg); color: var(--success); }
  .fo-toast.error .fo-toast-icon { background: var(--danger-bg); color: var(--danger); }
  .fo-toast.info .fo-toast-icon { background: var(--info-bg); color: var(--info); }
  .fo-toast-message {
    font-size: 13px; font-weight: 500;
    color: var(--text-primary);
  }

  /* ── Responsive ──────────────────────────────────── */
  @media (max-width: 640px) {
    .fo-stats-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .fo-stat-card {
      padding: 16px 18px;
    }
  }

  @media (max-width: 640px) {
    .fo-stat-icon {
      width: 36px;
      height: 36px;
      font-size: 16px;
    }
  }

  @media (max-width: 640px) {
    .fo-stat-label {
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .fo-stat-value {
      font-size: 24px;
    }
  }

  @media (max-width: 768px) {
    .fo-card-header {
      flex-direction: column;
      align-items: stretch;
      gap: 16px;
      padding: 16px 18px;
    }
  }

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

  @media (max-width: 768px) {
    .fo-search {
      width: 100%;
      flex: 1;
    }
  }

  @media (max-width: 480px) {
    .fo-btn-primary {
      width: 100%;
      justify-content: center;
    }
  }

  @media (max-width: 640px) {
    .fo-table th {
      padding: 10px 12px;
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .order-row td {
      padding: 10px 12px;
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .fo-order-num {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .fo-order-time {
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .fo-customer-name {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .fo-reservation-code {
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .fo-type-badge {
      font-size: 10px;
      padding: 3px 8px;
    }
  }

  @media (max-width: 640px) {
    .fo-table-num {
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .fo-amount {
      font-size: 13px;
    }
  }

  @media (max-width: 640px) {
    .fo-payment-badge {
      font-size: 10px;
      padding: 2px 6px;
    }
  }

  @media (max-width: 640px) {
    .fo-status-badge {
      font-size: 10px;
      padding: 3px 8px;
    }
  }

  @media (max-width: 640px) {
    .fo-action-btn {
      padding: 4px 8px;
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .fo-empty {
      padding: 48px 20px;
    }
  }

  @media (max-width: 640px) {
    .fo-empty-icon {
      width: 56px;
      height: 56px;
      font-size: 22px;
    }
  }

  @media (max-width: 640px) {
    .fo-empty h4 {
      font-size: 16px;
    }
  }

  @media (max-width: 640px) {
    .fo-empty p {
      font-size: 12px;
    }
  }

  @media (max-width: 768px) {
    .fo-modal {
      max-width: 90vw;
    }
  }

  @media (max-width: 768px) {
    .fo-modal.wide {
      max-width: 90vw;
    }
  }

  @media (max-width: 480px) {
    .fo-modal {
      max-width: none;
      max-height: 95vh;
      border-radius: var(--radius-lg);
    }
  }

  @media (max-width: 480px) {
    .fo-modal.wide {
      max-width: none;
    }
  }

  @media (max-width: 768px) {
    .fo-modal-body {
      padding: 20px;
    }
  }

  @media (max-width: 480px) {
    .fo-modal-body {
      padding: 18px;
      overflow-y: auto;
      overflow-x: hidden;
    }
  }

  @media (max-width: 480px) {
    .fo-modal-footer {
      flex-direction: column;
    }
  }

  @media (max-width: 480px) {
    .fo-modal-footer .fo-btn-primary,
    .fo-modal-footer .fo-btn-cancel {
      width: 100%;
      justify-content: center;
    }
  }

  @media (max-width: 640px) {
    .fo-detail-grid {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 640px) {
    .fo-detail-cell:nth-child(odd) {
      border-right: none;
      background: var(--surface);
    }
  }

  @media (max-width: 640px) {
    .fo-detail-cell {
      padding: 10px 12px;
    }
  }

  @media (max-width: 640px) {
    .fo-item-row {
      padding: 10px 12px;
    }
  }

  @media (max-width: 640px) {
    .fo-item-name {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .fo-item-qty {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .fo-item-total {
      font-size: 13px;
    }
  }

  @media (max-width: 640px) {
    .fo-notes-box {
      padding: 10px 12px;
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .fo-history-row {
      padding: 8px 0;
    }
  }

  @media (max-width: 640px) {
    .fo-history-time {
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .fo-history-change {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .fo-history-user {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .fo-history-reason {
      font-size: 11px;
    }
  }

  @media (max-width: 480px) {
    .fo-toast {
      min-width: auto;
      max-width: calc(100vw - 40px);
    }
  }
</style>

<script>
  let foodOrdersData = [];
  let propertiesData = [];
  let reservationsData = [];
  let menuItemsData = [];
  let paymentMethodsData = [];
  let diningTablesData = [];
  let csrfToken = '';

  async function fetchCsrfToken() {
    try {
      const res = await fetch('../api/users.php?action=csrf_token');
      const json = await res.json();
      if (json.success) csrfToken = json.csrf_token;
    } catch (e) {
      console.error('Error fetching CSRF token:', e);
    }
  }

  async function fetchOrders() {
    try {
      const res = await fetch('../api/food_orders.php?action=list');
      const json = await res.json();
      if (json.success) {
        foodOrdersData = json.data;
        renderStats();
        renderTable();
      }
    } catch (e) { console.error('Error fetching orders:', e); }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/food_orders.php?action=related');
      const json = await res.json();
      if (json.success) {
        propertiesData = json.data.properties;
        reservationsData = json.data.reservations;
        menuItemsData = json.data.menuItems;
        paymentMethodsData = json.data.paymentMethods;
        diningTablesData = json.data.diningTables || [];
        populateDropdowns();
      }
    } catch (e) { console.error('Error fetching related data:', e); }
  }

  function renderStats() {
    const pending = foodOrdersData.filter(o => ['pending','confirmed','preparing'].includes(o.order_status)).length;
    const ready   = foodOrdersData.filter(o => ['ready','delivered'].includes(o.order_status)).length;
    const unpaid  = foodOrdersData.filter(o => o.payment_status === 'unpaid').length;
    const pairs = [
      ['statTotal',   foodOrdersData.length],
      ['statPending', pending],
      ['statReady',   ready],
      ['statUnpaid',  unpaid]
    ];
    pairs.forEach(([id, end]) => {
      const el = document.getElementById(id);
      if (!el) return;
      if (end === 0) { el.textContent = '0'; return; }
      let start = 0, step = Math.max(1, Math.ceil(end / 20));
      const timer = setInterval(() => {
        start = Math.min(start + step, end);
        el.textContent = start;
        if (start >= end) clearInterval(timer);
      }, 25);
    });
  }

  function renderTable() {
    const tbody = document.getElementById('ordersTableBody');
    if (!foodOrdersData.length) {
      tbody.innerHTML = `
        <tr class="fo-loading-row">
          <td colspan="7">
            <div class="fo-empty">
              <div class="fo-empty-icon"><i class="fas fa-receipt"></i></div>
              <h4>No food orders yet</h4>
              <p>Create your first order to get started</p>
              <button onclick="openAddOrderModal()" class="fo-btn-primary">
                <i class="fas fa-plus"></i> Create Order
              </button>
            </div>
          </td>
        </tr>`;
      return;
    }

    const typeCls = { dine_in: 'dine-in', room_service: 'room-service', takeout: 'takeout' };
    const typeIcon = { dine_in: 'fa-chair', room_service: 'fa-concierge-bell', takeout: 'fa-shopping-bag' };
    const typeLabel = { dine_in: 'Dine In', room_service: 'Room Service', takeout: 'Takeout' };

    tbody.innerHTML = foodOrdersData.map(order => {
      const stCls = (order.order_status || 'pending').replace(/_/g, '-');
      const stLabel = (order.order_status || '').replace(/_/g, ' ');
      const payCls = (order.payment_status || 'unpaid').replace(/_/g, '-');
      const payLabel = (order.payment_status || '').replace(/_/g, ' ');
      const ot = order.order_type || 'dine_in';
      const date = new Date(order.created_at);
      const dateStr = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
      const timeStr = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

      return `
      <tr class="order-row">
        <td>
          <div class="fo-order-num">#${order.order_number}</div>
          <div class="fo-order-time">${dateStr} · ${timeStr}</div>
        </td>
        <td>
          <span class="fo-type-badge ${typeCls[ot] || 'dine-in'}">
            <i class="fas ${typeIcon[ot] || 'fa-chair'}" style="font-size:10px"></i>
            ${typeLabel[ot] || ot}
          </span>
          ${order.table_number ? `<div class="fo-table-num"><i class="fas fa-table" style="font-size:10px;margin-right:3px"></i>Table ${order.table_number}</div>` : ''}
        </td>
        <td>
          <div class="fo-customer-name">${order.guest_name || order.customer_name || 'Walk-in'}</div>
          ${order.reservation_code ? `<div class="fo-reservation-code">${order.reservation_code}</div>` : ''}
        </td>
        <td style="font-size:13px;color:var(--text-secondary)">${order.property_name || '—'}</td>
        <td style="text-align:right">
          <div class="fo-amount">₱${parseFloat(order.total_amount).toFixed(2)}</div>
          <div style="display:flex;justify-content:flex-end;margin-top:4px">
            <span class="fo-payment-badge ${payCls}">${payLabel}</span>
          </div>
        </td>
        <td style="text-align:center">
          <span class="fo-status-badge ${stCls}">${stLabel}</span>
        </td>
        <td>
          <div class="fo-actions">
            <button onclick="viewOrderDetails('${order.order_id}')" class="fo-action-btn view" title="View Details">
              <i class="fas fa-eye"></i> View
            </button>
            <button onclick="updateOrderStatus('${order.order_id}')" class="fo-action-btn status" title="Update Status">
              <i class="fas fa-sync-alt"></i>
            </button>
            <button onclick="updateOrderPayment('${order.order_id}')" class="fo-action-btn pay" title="Update Payment">
              <i class="fas fa-credit-card"></i>
            </button>
            <button onclick="openEditOrderModal('${order.order_id}')" class="fo-action-btn edit" title="Edit Order">
              <i class="fas fa-pen"></i>
            </button>
            <button onclick="deleteOrder('${order.order_id}', '${order.order_number}')" class="fo-action-btn del" title="Delete Order">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
        </td>
      </tr>`;
    }).join('');
  }

  function populateDropdowns() {
    const propOpts = '<option value="">Select Property</option>' + propertiesData.map(p => `<option value="${p.property_id}">${p.property_name}</option>`).join('');
    const resOpts  = '<option value="">Walk-in / Select Reservation</option>' + reservationsData.map(r => `<option value="${r.reservation_id}">${r.display}</option>`).join('');
    const itemOpts = menuItemsData.map(m => `<option value="${m.item_id}" data-price="${m.price}">${m.item_name} — ${m.item_code} — ₱${parseFloat(m.price).toFixed(2)}</option>`).join('');
    const payOpts  = '<option value="">Select Payment Method</option>' + paymentMethodsData.map(p => `<option value="${p.method_id}">${p.method_name}</option>`).join('');
    const tableOpts = '<option value="">Select Table</option>' + diningTablesData.map(t => `<option value="${t.table_id}" data-number="${t.table_number}">${t.table_number}${t.table_name ? ' - ' + t.table_name : ''} (${t.capacity} seats)</option>`).join('');
    ['addProperty','editProperty'].forEach(id => { const s = document.getElementById(id); if (s) s.innerHTML = propOpts; });
    ['addReservation','editReservation'].forEach(id => { const s = document.getElementById(id); if (s) s.innerHTML = resOpts; });
    const s3 = document.getElementById('addMenuItem');    if (s3) s3.innerHTML = itemOpts;
    const s4 = document.getElementById('addPaymentMethod'); if (s4) s4.innerHTML = payOpts;
    document.querySelectorAll('.table-select').forEach(s => s.innerHTML = tableOpts);
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchCsrfToken();
    fetchOrders();
    fetchRelated();
  });
</script>

<!-- PAGE CONTENT -->

      <!-- Stats Cards -->
      <div class="fo-stats-grid">
        <div class="fo-stat-card">
          <div class="fo-stat-icon total"><i class="fas fa-receipt"></i></div>
          <div>
            <div class="fo-stat-label">Total Orders</div>
            <div class="fo-stat-value" id="statTotal">—</div>
          </div>
        </div>
        <div class="fo-stat-card">
          <div class="fo-stat-icon pending"><i class="fas fa-clock"></i></div>
          <div>
            <div class="fo-stat-label">In Progress</div>
            <div class="fo-stat-value" id="statPending">—</div>
          </div>
        </div>
        <div class="fo-stat-card">
          <div class="fo-stat-icon ready"><i class="fas fa-check-circle"></i></div>
          <div>
            <div class="fo-stat-label">Ready / Delivered</div>
            <div class="fo-stat-value" id="statReady">—</div>
          </div>
        </div>
        <div class="fo-stat-card">
          <div class="fo-stat-icon unpaid"><i class="fas fa-exclamation-circle"></i></div>
          <div>
            <div class="fo-stat-label">Unpaid</div>
            <div class="fo-stat-value" id="statUnpaid">—</div>
          </div>
        </div>
      </div>

      <!-- Food Orders Table -->
      <div class="fo-card">
        <div class="fo-card-header">
          <h2 class="fo-card-title">Food Orders</h2>
          <div class="fo-card-actions">
            <div class="fo-search-wrap">
              <i class="fas fa-search"></i>
              <input type="text" id="searchInput" class="fo-search" placeholder="Search orders…" oninput="filterTable()">
            </div>
            <button onclick="openAddOrderModal()" class="fo-btn-primary">
              <i class="fas fa-plus"></i> New Order
            </button>
          </div>
        </div>
        <div style="overflow-x:auto">
          <table class="fo-table" id="orderTable">
            <thead>
              <tr>
                <th>Order</th>
                <th>Type</th>
                <th>Customer</th>
                <th>Property</th>
                <th class="right">Total</th>
                <th class="center">Status</th>
                <th class="right">Actions</th>
              </tr>
            </thead>
            <tbody id="ordersTableBody">
              <tr class="fo-loading-row">
                <td colspan="7">
                  <i class="fas fa-spinner fa-spin" style="margin-right:8px;color:var(--text-muted)"></i>
                  Loading orders…
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- View Order Details Modal -->
      <div id="orderDetailsModal" class="fo-modal-overlay" style="display:none" onclick="if(event.target===this)closeOrderDetailsModal()">
        <div class="fo-modal wide">
          <div class="fo-modal-header">
            <h3 class="fo-modal-title" id="orderModalTitle">Order Details</h3>
            <button onclick="closeOrderDetailsModal()" class="fo-modal-close"><i class="fas fa-times"></i></button>
          </div>
          <div class="fo-modal-body" id="orderModalContent">
            <!-- populated by JS -->
          </div>
        </div>
      </div>

      <!-- Add Order Modal -->
      <div id="addOrderModal" class="fo-modal-overlay" style="display:none" onclick="if(event.target===this)closeAddOrderModal()">
        <div class="fo-modal">
          <div class="fo-modal-header">
            <h3 class="fo-modal-title">Create New Food Order</h3>
            <button onclick="closeAddOrderModal()" class="fo-modal-close"><i class="fas fa-times"></i></button>
          </div>
          <form id="addOrderForm" onsubmit="submitAddOrder(event)">
            <div class="fo-modal-body">
              <div class="fo-form-grid">
                <div class="fo-form-group">
                  <label class="fo-label">Property <span class="req">*</span></label>
                  <select name="property_id" id="addProperty" required class="fo-select">
                    <option value="">Select Property</option>
                  </select>
                </div>
                <div class="fo-form-group">
                  <label class="fo-label">Order Type <span class="req">*</span></label>
                  <select name="order_type" required class="fo-select">
                    <option value="dine_in">Dine In</option>
                    <option value="room_service">Room Service</option>
                    <option value="takeout">Takeout</option>
                  </select>
                </div>
                <div class="fo-form-group">
                  <label class="fo-label">Reservation</label>
                  <select name="reservation_id" id="addReservation" class="fo-select">
                    <option value="">Walk-in Customer</option>
                  </select>
                </div>
                <div class="fo-form-group">
                  <label class="fo-label">Customer Name</label>
                  <input type="text" name="customer_name" class="fo-input" placeholder="Customer name">
                </div>
                <div class="fo-form-group">
                  <label class="fo-label">Table</label>
                  <select name="table_id" class="fo-select table-select">
                    <option value="">Select Table</option>
                  </select>
                </div>
                <div class="fo-form-group">
                  <label class="fo-label">Order Status</label>
                  <select name="order_status" class="fo-select">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="preparing">Preparing</option>
                    <option value="ready">Ready</option>
                    <option value="delivered">Delivered</option>
                  </select>
                </div>
                <div class="fo-form-group">
                  <label class="fo-label">Payment Status</label>
                  <select name="payment_status" class="fo-select">
                    <option value="unpaid">Unpaid</option>
                    <option value="charged_to_room">Charged to Room</option>
                    <option value="paid">Paid</option>
                  </select>
                </div>
                <div class="fo-form-group full">
                  <label class="fo-label">Notes</label>
                  <textarea name="notes" rows="2" class="fo-textarea" placeholder="Additional notes or special requests…"></textarea>
                </div>
              </div>
            </div>
            <div class="fo-modal-footer">
              <button type="button" onclick="closeAddOrderModal()" class="fo-btn-cancel">Cancel</button>
              <button type="submit" class="fo-btn-primary"><i class="fas fa-plus"></i> Create Order</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Order Modal -->
      <div id="editOrderModal" class="fo-modal-overlay" style="display:none" onclick="if(event.target===this)closeEditOrderModal()">
        <div class="fo-modal">
          <div class="fo-modal-header">
            <h3 class="fo-modal-title">Edit Food Order</h3>
            <button onclick="closeEditOrderModal()" class="fo-modal-close"><i class="fas fa-times"></i></button>
          </div>
          <form id="editOrderForm" onsubmit="submitEditOrder(event)">
            <div class="fo-modal-body">
              <div class="fo-form-grid">
                <input type="hidden" name="order_id" id="editOrderId">
                <div class="fo-form-group">
                  <label class="fo-label">Property <span class="req">*</span></label>
                  <select name="property_id" id="editProperty" required class="fo-select">
                    <option value="">Select Property</option>
                  </select>
                </div>
                <div class="fo-form-group">
                  <label class="fo-label">Order Type <span class="req">*</span></label>
                  <select name="order_type" required class="fo-select">
                    <option value="dine_in">Dine In</option>
                    <option value="room_service">Room Service</option>
                    <option value="takeout">Takeout</option>
                  </select>
                </div>
                <div class="fo-form-group">
                  <label class="fo-label">Reservation</label>
                  <select name="reservation_id" id="editReservation" class="fo-select">
                    <option value="">Walk-in Customer</option>
                  </select>
                </div>
                <div class="fo-form-group">
                  <label class="fo-label">Customer Name</label>
                  <input type="text" name="customer_name" class="fo-input" placeholder="Customer name">
                </div>
                <div class="fo-form-group">
                  <label class="fo-label">Table</label>
                  <select name="table_id" class="fo-select table-select">
                    <option value="">Select Table</option>
                  </select>
                </div>
                <div class="fo-form-group">
                  <label class="fo-label">Order Status</label>
                  <select name="order_status" class="fo-select">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="preparing">Preparing</option>
                    <option value="ready">Ready</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                  </select>
                </div>
                <div class="fo-form-group">
                  <label class="fo-label">Payment Status</label>
                  <select name="payment_status" class="fo-select">
                    <option value="unpaid">Unpaid</option>
                    <option value="charged_to_room">Charged to Room</option>
                    <option value="paid">Paid</option>
                  </select>
                </div>
                <div class="fo-form-group full">
                  <label class="fo-label">Notes</label>
                  <textarea name="notes" rows="2" class="fo-textarea" placeholder="Additional notes or special requests…"></textarea>
                </div>
              </div>
            </div>
            <div class="fo-modal-footer">
              <button type="button" onclick="closeEditOrderModal()" class="fo-btn-cancel">Cancel</button>
              <button type="submit" class="fo-btn-primary"><i class="fas fa-save"></i> Update Order</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Update Status Modal -->
      <div id="updateStatusModal" class="fo-modal-overlay" style="display:none" onclick="if(event.target===this)closeUpdateStatusModal()">
        <div class="fo-modal">
          <div class="fo-modal-header">
            <h3 class="fo-modal-title">Update Order Status</h3>
            <button onclick="closeUpdateStatusModal()" class="fo-modal-close"><i class="fas fa-times"></i></button>
          </div>
          <form id="updateStatusForm" onsubmit="submitUpdateStatus(event)">
            <div class="fo-modal-body">
              <input type="hidden" name="order_id" id="updateStatusOrderId">
              <div class="fo-form-group">
                <label class="fo-label">Select New Status</label>
                <select name="order_status" required class="fo-select">
                  <option value="pending">Pending</option>
                  <option value="confirmed">Confirmed</option>
                  <option value="preparing">Preparing</option>
                  <option value="ready">Ready</option>
                  <option value="delivered">Delivered</option>
                  <option value="cancelled">Cancelled</option>
                </select>
              </div>
            </div>
            <div class="fo-modal-footer">
              <button type="button" onclick="closeUpdateStatusModal()" class="fo-btn-cancel">Cancel</button>
              <button type="submit" class="fo-btn-primary"><i class="fas fa-sync-alt"></i> Update Status</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Toast Container -->
      <div id="toastContainer" class="fo-toast-container"></div>

<script>
  // Toast notification function
  function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `fo-toast ${type}`;

    const iconMap = {
      success: 'fa-check',
      error: 'fa-times',
      info: 'fa-info'
    };

    toast.innerHTML = `
      <div class="fo-toast-icon">
        <i class="fas ${iconMap[type] || iconMap.info}"></i>
      </div>
      <div class="fo-toast-message">${message}</div>
    `;

    container.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateX(30px)';
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }

  // Custom confirm dialog
  function showConfirm(title, message, onConfirm) {
    const overlay = document.createElement('div');
    overlay.style.cssText = `
      position: fixed; top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.5); z-index: 10000;
      display: flex; align-items: center; justify-content: center;
      animation: fadeIn 0.2s ease-out;
    `;

    const modal = document.createElement('div');
    modal.style.cssText = `
      background: #fff; border-radius: 12px; padding: 24px;
      max-width: 400px; width: 90%;
      box-shadow: 0 20px 64px rgba(0,0,0,0.18);
      animation: slideUp 0.2s ease-out;
    `;

    modal.innerHTML = `
      <h3 style="margin: 0 0 12px; font-size: 18px; font-weight: 600; color: #1A1410;">${title}</h3>
      <p style="margin: 0 0 20px; font-size: 14px; color: #6B6460; line-height: 1.5;">${message}</p>
      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button id="confirmCancel" style="
          padding: 8px 16px; border: 1px solid #EDEAE6; background: #fff;
          border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer;
          color: #6B6460; transition: all 0.15s;
        ">Cancel</button>
        <button id="confirmOk" style="
          padding: 8px 16px; border: none; background: #C84B31;
          border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;
          color: #fff; transition: all 0.15s;
        ">Confirm</button>
      </div>
    `;

    overlay.appendChild(modal);
    document.body.appendChild(overlay);

    document.getElementById('confirmCancel').onclick = () => {
      overlay.style.opacity = '0';
      setTimeout(() => overlay.remove(), 200);
    };

    document.getElementById('confirmOk').onclick = () => {
      overlay.style.opacity = '0';
      setTimeout(() => {
        overlay.remove();
        onConfirm();
      }, 200);
    };
  }

  // Filter table
  function filterTable() {
    const filter = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.getElementById('orderTable').getElementsByTagName('tr');
    for (let i = 1; i < rows.length; i++) {
      const text = rows[i].textContent || rows[i].innerText;
      rows[i].style.display = text.toLowerCase().includes(filter) ? '' : 'none';
    }
  }

  // View Order Details
  async function viewOrderDetails(orderId) {
    try {
      const res  = await fetch(`../api/food_orders.php?action=single&id=${orderId}`);
      const json = await res.json();
      if (!json.success || !json.data) return;
      const order = json.data;

      // Fetch status history
      const historyRes = await fetch(`../api/food_orders.php?action=status_history&id=${orderId}`);
      const historyJson = await historyRes.json();
      const statusHistory = historyJson.success ? historyJson.data : [];

      const stCls   = (order.order_status || 'pending').replace(/_/g, '-');
      const stLabel = (order.order_status || '').replace(/_/g, ' ');
      const payCls   = (order.payment_status || 'unpaid').replace(/_/g, '-');
      const payLabel = (order.payment_status || '').replace(/_/g, ' ');
      const typeLabel = { dine_in: 'Dine In', room_service: 'Room Service', takeout: 'Takeout' };

      const itemsHtml = order.items && order.items.length
        ? order.items.map(i => `
          <div class="fo-item-row">
            <div>
              <div class="fo-item-name">${i.item_name}</div>
              <div class="fo-item-qty">Qty: ${i.quantity} × ₱${parseFloat(i.unit_price).toFixed(2)}</div>
            </div>
            <div class="fo-item-total">₱${parseFloat(i.total_price).toFixed(2)}</div>
          </div>`).join('')
        : `<div class="fo-item-row" style="color:var(--text-muted);font-style:italic;font-size:13px">No items on this order.</div>`;

      const historyHtml = statusHistory.length > 0
        ? statusHistory.map(h => `
          <div class="fo-history-row">
            <div class="fo-history-time">${new Date(h.created_at).toLocaleString()}</div>
            <div class="fo-history-change">
              <span class="fo-history-old">${h.old_status || '—'}</span>
              <i class="fas fa-arrow-right" style="font-size:10px;margin:0 6px;color:var(--text-muted)"></i>
              <span class="fo-history-new">${h.new_status}</span>
            </div>
            <div class="fo-history-user">${h.first_name ? h.first_name + ' ' + h.last_name : 'System'}</div>
            ${h.change_reason ? `<div class="fo-history-reason">${h.change_reason}</div>` : ''}
          </div>`).join('')
        : `<div style="color:var(--text-muted);font-style:italic;font-size:13px;padding:12px 0">No status history yet.</div>`;

      document.getElementById('orderModalTitle').textContent = `Order #${order.order_number}`;
      document.getElementById('orderModalContent').innerHTML = `
        <div class="fo-detail-grid">
          <div class="fo-detail-cell">
            <div class="fo-detail-label">Order Number</div>
            <div class="fo-detail-value">#${order.order_number}</div>
          </div>
          <div class="fo-detail-cell">
            <div class="fo-detail-label">Order Type</div>
            <div class="fo-detail-value">${typeLabel[order.order_type] || order.order_type}</div>
          </div>
          <div class="fo-detail-cell">
            <div class="fo-detail-label">Customer</div>
            <div class="fo-detail-value">${order.customer_name || order.guest_name || 'Walk-in'}</div>
          </div>
          <div class="fo-detail-cell">
            <div class="fo-detail-label">Table</div>
            <div class="fo-detail-value">${order.table_number || '—'}</div>
          </div>
          <div class="fo-detail-cell">
            <div class="fo-detail-label">Order Status</div>
            <div class="fo-detail-value"><span class="fo-status-badge ${stCls}">${stLabel}</span></div>
          </div>
          <div class="fo-detail-cell">
            <div class="fo-detail-label">Payment</div>
            <div class="fo-detail-value"><span class="fo-payment-badge ${payCls}">${payLabel}</span></div>
          </div>
          <div class="fo-detail-cell">
            <div class="fo-detail-label">Subtotal</div>
            <div class="fo-detail-value">₱${parseFloat(order.subtotal || 0).toFixed(2)}</div>
          </div>
          <div class="fo-detail-cell">
            <div class="fo-detail-label">Total Amount</div>
            <div class="fo-detail-value" style="color:var(--brand);font-size:16px">₱${parseFloat(order.total_amount).toFixed(2)}</div>
          </div>
        </div>
        ${order.notes ? `
        <div class="fo-notes-box">
          <i class="fas fa-sticky-note"></i>
          <div><strong>Notes:</strong> ${order.notes}</div>
        </div>` : ''}
        <div class="fo-items-section">
          <div class="fo-items-header">Order Items</div>
          ${itemsHtml}
        </div>
        <div class="fo-items-section">
          <div class="fo-items-header">Status History</div>
          ${historyHtml}
        </div>`;

      document.getElementById('orderDetailsModal').style.display = 'flex';
    } catch (e) { console.error('Error fetching order details:', e); }
  }

  function closeOrderDetailsModal() {
    document.getElementById('orderDetailsModal').style.display = 'none';
  }

  // Add Order Modal
  async function openAddOrderModal() {
    // Fetch related data if not loaded yet
    if (!propertiesData || propertiesData.length === 0) {
      await fetchRelated();
    }
    const m = document.getElementById('addOrderModal');
    m.style.display = 'flex';
    document.getElementById('addOrderForm').reset();
  }
  function closeAddOrderModal() {
    document.getElementById('addOrderModal').style.display = 'none';
  }

  function submitAddOrder(event) {
    event.preventDefault();
    const form = document.getElementById('addOrderForm');
    const payload = {
      property_id:    form.elements['property_id'].value,
      reservation_id: form.elements['reservation_id'].value || null,
      order_type:     form.elements['order_type'].value,
      customer_name:  form.elements['customer_name'].value,
      table_number:   form.elements['table_id'].selectedOptions[0]?.dataset?.number || '',
      order_status:   form.elements['order_status'].value,
      payment_status: form.elements['payment_status'].value,
      notes:          form.elements['notes'].value,
      subtotal: 0, total_amount: 0, items: []
    };

    fetch('../api/food_orders.php?action=create', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({...payload, csrf_token: csrfToken}) })
      .then(r => r.json())
      .then(data => {
        closeAddOrderModal();
        if (data.success) {
          showToast(data.message || 'Order created successfully', 'success');
          fetchOrders();
        } else {
          showToast(data.message || 'Error creating order', 'error');
        }
      })
      .catch(() => {
        closeAddOrderModal();
        showToast('An error occurred while creating the order', 'error');
      });
  }

  // ── Inventory Stock Checking ───────────────────────────────────────────────
  async function checkRecipeStock(itemId, quantity = 1) {
    try {
      const response = await fetch(`../api/inventory_transactions.php?action=check_recipe_stock&item_id=${itemId}&quantity=${quantity}`);
      const result = await response.json();
      return result;
    } catch (error) {
      console.error('Error checking stock:', error);
      return { success: false, available: true, issues: [] };
    }
  }

  function showStockWarning(issues) {
    if (!issues || issues.length === 0) return;

    const warningHtml = issues.map(i =>
      `<div style="margin-bottom: 8px;">
        <strong>${i.product_name}</strong>: Required ${i.required} ${i.uom}, Available ${i.available} ${i.uom}
      </div>`
    ).join('');

    showConfirm('Insufficient Stock', `<div style="text-align:left;">${warningHtml}</div>`, () => {
      // Proceed with order despite low stock
    });
  }

  // Edit Order Modal
  async function openEditOrderModal(orderId) {
    try {
      const res  = await fetch(`../api/food_orders.php?action=single&id=${orderId}`);
      const json = await res.json();
      if (!json.success || !json.data) return;
      const order = json.data;
      document.getElementById('editOrderId').value = order.order_id;
      const form = document.getElementById('editOrderForm');
      form.elements['property_id'].value    = order.property_id;
      form.elements['order_type'].value     = order.order_type;
      form.elements['reservation_id'].value = order.reservation_id || '';
      form.elements['customer_name'].value  = order.customer_name || '';
      // Find table_id by table_number for edit modal
      const tableMatch = diningTablesData.find(t => t.table_number === order.table_number);
      form.elements['table_id'].value = tableMatch ? tableMatch.table_id : '';
      form.elements['order_status'].value   = order.order_status;
      form.elements['payment_status'].value = order.payment_status;
      form.elements['notes'].value          = order.notes || '';
      document.getElementById('editOrderModal').style.display = 'flex';
    } catch (e) { console.error('Error fetching order for edit:', e); }
  }

  function closeEditOrderModal() {
    document.getElementById('editOrderModal').style.display = 'none';
  }

  function submitEditOrder(event) {
    event.preventDefault();
    const form = document.getElementById('editOrderForm');
    const payload = {
      order_id:       form.elements['order_id'].value,
      property_id:    form.elements['property_id'].value,
      reservation_id: form.elements['reservation_id'].value || null,
      order_type:     form.elements['order_type'].value,
      customer_name:  form.elements['customer_name'].value,
      table_number:   form.elements['table_id'].selectedOptions[0]?.dataset?.number || '',
      order_status:   form.elements['order_status'].value,
      payment_status: form.elements['payment_status'].value,
      notes:          form.elements['notes'].value
    };

    fetch('../api/food_orders.php?action=update', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({...payload, csrf_token: csrfToken}) })
      .then(r => r.json())
      .then(data => {
        closeEditOrderModal();
        if (data.success) {
          showToast(data.message || 'Order updated successfully', 'success');
          fetchOrders();
        } else {
          showToast(data.message || 'Error updating order', 'error');
        }
      })
      .catch(() => {
        closeEditOrderModal();
        showToast('An error occurred while updating the order', 'error');
      });
  }

  // Delete Order
  function deleteOrder(orderId, orderNumber) {
    showConfirm('Delete Order', `You are about to delete order "${orderNumber}". This action cannot be undone.`, () => {
      fetch('../api/food_orders.php?action=delete', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ order_id: orderId, csrf_token: csrfToken }) })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            showToast(data.message || 'Order deleted successfully', 'success');
            fetchOrders();
          } else {
            showToast(data.message || 'Error deleting order', 'error');
          }
        })
        .catch(() => showToast('An error occurred while deleting the order', 'error'));
    });
  }

  // Update Order Status
  function updateOrderStatus(orderId) {
    const order = foodOrdersData.find(o => o.order_id === orderId);
    document.getElementById('updateStatusOrderId').value = orderId;
    document.getElementById('updateStatusForm').reset();
    
    // Set current status in dropdown
    if (order && order.order_status) {
      const statusSelect = document.querySelector('#updateStatusForm select[name="order_status"]');
      statusSelect.value = order.order_status;
    }
    
    document.getElementById('updateStatusModal').style.display = 'flex';
  }

  function closeUpdateStatusModal() {
    document.getElementById('updateStatusModal').style.display = 'none';
  }

  function submitUpdateStatus(event) {
    event.preventDefault();
    const form = document.getElementById('updateStatusForm');
    const payload = {
      order_id: form.elements['order_id'].value,
      order_status: form.elements['order_status'].value,
      csrf_token: csrfToken
    };

    closeUpdateStatusModal();

    fetch('../api/food_orders.php?action=update_status', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload) })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          showToast(data.message, 'success');
          fetchOrders();
        } else {
          showToast(data.message, 'error');
        }
      })
      .catch(() => showToast('An error occurred while updating status.', 'error'));
  }

  // Update Payment Status (alias for updateOrderPayment referenced in old code)
  function updateOrderPayment(orderId) {
    const order = foodOrdersData.find(o => o.order_id == orderId);
    const orderNumber = order ? order.order_number : orderId;
    changePaymentStatus(orderId, orderNumber);
  }

  function changePaymentStatus(orderId, orderNumber) {
    const overlay = document.createElement('div');
    overlay.style.cssText = `
      position: fixed; top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.5); z-index: 10000;
      display: flex; align-items: center; justify-content: center;
      animation: fadeIn 0.2s ease-out;
    `;

    const modal = document.createElement('div');
    modal.style.cssText = `
      background: #fff; border-radius: 12px; padding: 24px;
      max-width: 400px; width: 90%;
      box-shadow: 0 20px 64px rgba(0,0,0,0.18);
      animation: slideUp 0.2s ease-out;
    `;

    modal.innerHTML = `
      <h3 style="margin: 0 0 12px; font-size: 18px; font-weight: 600; color: #1A1410;">Change Payment Status</h3>
      <p style="margin: 0 0 20px; font-size: 14px; color: #6B6460;">Update payment for order "${orderNumber}"</p>
      <select id="paymentStatusSelect" style="
        width: 100%; padding: 10px; border: 1px solid #EDEAE6;
        border-radius: 8px; font-size: 14px; margin-bottom: 20px;
      ">
        <option value="unpaid">Unpaid</option>
        <option value="charged_to_room">Charged to Room</option>
        <option value="paid">Paid</option>
      </select>
      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button id="paymentCancel" style="
          padding: 8px 16px; border: 1px solid #EDEAE6; background: #fff;
          border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer;
          color: #6B6460; transition: all 0.15s;
        ">Cancel</button>
        <button id="paymentConfirm" style="
          padding: 8px 16px; border: none; background: #C84B31;
          border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;
          color: #fff; transition: all 0.15s;
        ">Update</button>
      </div>
    `;

    overlay.appendChild(modal);
    document.body.appendChild(overlay);

    document.getElementById('paymentCancel').onclick = () => {
      overlay.style.opacity = '0';
      setTimeout(() => overlay.remove(), 200);
    };

    document.getElementById('paymentConfirm').onclick = () => {
      const newStatus = document.getElementById('paymentStatusSelect').value;
      overlay.style.opacity = '0';
      setTimeout(() => {
        overlay.remove();
        fetch('../api/food_orders.php?action=update_payment', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ order_id: orderId, payment_status: newStatus, csrf_token: csrfToken }) })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              showToast(data.message || 'Payment status updated', 'success');
              fetchOrders();
            } else {
              showToast(data.message || 'Error updating payment', 'error');
            }
          })
          .catch(() => showToast('An error occurred while updating payment status', 'error'));
      }, 200);
    };
  }

  // Add Items to Order (stub)
  function addOrderItems(orderId, orderNumber) {
    showToast('Add items to order feature coming soon', 'info');
  }

  // Print Order (stub)
  function printOrder(orderId, orderNumber) {
    showToast('Print order feature coming soon', 'info');
  }
</script>