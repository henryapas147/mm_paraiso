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

  /* ── Page Shell ─────────────────────────────────────── */
  .prod-page {
    padding: 28px 32px 40px;
    max-width: 1600px;
    width: 100%;
    margin: 0 auto;
    background: var(--color-background-tertiary, #F4F5F7);
    min-height: 100%;
    border-radius: var(--border-radius-xl, 18px);
    font-family: var(--prod-font);
    box-sizing: border-box;
  }

  /* ── Header ─────────────────────────────────────────── */
  .prod-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
    gap: 16px;
    flex-wrap: wrap;
  }

  .prod-page-title-wrap {}

  .prod-page-eyebrow {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--prod-blue);
    margin-bottom: 4px;
  }

  .prod-page-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.4px;
    line-height: 1.2;
  }

  .prod-page-subtitle {
    font-size: 13px;
    color: var(--color-text-secondary);
    margin-top: 3px;
    font-weight: 400;
  }

  /* ── Stat Cards ──────────────────────────────────────── */
  .prod-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }

  .prod-stat-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    padding: 20px 22px;
    box-shadow: var(--prod-shadow-sm);
    position: relative;
    overflow: hidden;
    transition: box-shadow .2s, transform .2s;
  }

  .prod-stat-card:hover {
    box-shadow: var(--prod-shadow-md);
    transform: translateY(-1px);
  }

  .prod-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--prod-radius-lg) var(--prod-radius-lg) 0 0;
    background: var(--_accent, #CBD5E1);
  }

  .prod-stat-card.blue {
    --_accent: var(--prod-blue);
  }

  .prod-stat-card.green {
    --_accent: var(--prod-green);
  }

  .prod-stat-card.red {
    --_accent: var(--prod-red);
  }

  .prod-stat-card.amber {
    --_accent: var(--prod-amber);
  }

  .prod-stat-icon {
    width: 36px;
    height: 36px;
    border-radius: var(--prod-radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    margin-bottom: 14px;
    background: var(--_icon-bg, #F1F5F9);
    color: var(--_icon-color, #64748B);
  }

  .prod-stat-card.blue .prod-stat-icon {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
  }

  .prod-stat-card.green .prod-stat-icon {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .prod-stat-card.red .prod-stat-icon {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .prod-stat-card.amber .prod-stat-icon {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
  }

  .prod-stat-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-secondary);
    letter-spacing: .02em;
    margin-bottom: 6px;
  }

  .prod-stat-value {
    font-size: clamp(24px, 2vw, 36px);
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.5px;
    line-height: 1;
    font-family: var(--prod-mono);
  }

  .prod-stat-value.blue {
    color: var(--prod-blue);
  }

  .prod-stat-value.green {
    color: var(--prod-green);
  }

  .prod-stat-value.red {
    color: var(--prod-red);
  }

  .prod-stat-value.amber {
    color: var(--prod-amber);
  }

  /* ── Card ───────────────────────────────────────────── */
  .prod-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    box-shadow: var(--prod-shadow-sm);
    overflow: hidden;
  }

  .prod-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    background: var(--color-background-primary, #fff);
  }

  .prod-card-title-wrap {}

  .prod-card-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.2px;
  }

  .prod-card-count {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-top: 1px;
  }

  /* ── Toolbar ─────────────────────────────────────────── */
  .prod-toolbar {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1 1 auto;
    min-width: 0;
    justify-content: flex-end;
  }

  .prod-toolbar-left,
  .prod-toolbar-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
  }

  .prod-toolbar-center {
    display: flex;
    align-items: center;
    flex: 1 1 auto;
    min-width: 0;
  }

  .prod-search-wrap {
    position: relative;
  }

  .prod-search-wrap i {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-text-secondary);
    font-size: 12px;
    pointer-events: none;
  }

  .prod-search-input {
    padding: 8px 14px 8px 34px;
    font-size: 13px;
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--prod-radius-md);
    background: var(--color-background-secondary, #F8FAFC);
    color: var(--color-text-primary);
    outline: none;
    width: 200px;
    font-family: var(--prod-font);
    transition: border-color .15s, box-shadow .15s;
  }

  .prod-search-input:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    background: var(--color-background-primary, #fff);
  }

  .prod-search-input::placeholder {
    color: var(--color-text-secondary);
    opacity: .7;
  }

  /* ── Filter Bar ───────────────────────────────────────── */
  .prod-filter-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: nowrap;
    width: 100%;
  }

  .prod-filter-item {
    flex: 1 1 0;
    min-width: 100px;
  }

  .prod-filter-select {
    width: 100%;
    padding: 8px 28px 8px 12px;
    font-size: 13px;
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--prod-radius-md);
    background: var(--color-background-primary, #fff);
    color: var(--color-text-primary);
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    transition: border-color .15s, box-shadow .15s;
  }

  .prod-filter-select:hover {
    border-color: var(--prod-blue);
  }

  .prod-filter-select:focus {
    outline: none;
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
  }

  .prod-filter-clear {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    padding: 0;
    border: 1px solid rgba(198, 40, 40, .3);
    border-radius: var(--prod-radius-md);
    background: var(--prod-red-pale);
    color: var(--prod-red);
    cursor: pointer;
    font-size: 12px;
    transition: all .15s;
  }

  .prod-filter-clear:hover {
    background: var(--prod-red);
    color: #fff;
    border-color: var(--prod-red);
  }

  /* ── Product Image Upload ────────────────────────────── */
  .prod-image-upload {
    position: relative;
    width: 100%;
    max-width: 200px;
  }

  .prod-image-input {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
  }

  .prod-image-preview {
    width: 100%;
    aspect-ratio: 1;
    border: 2px dashed rgba(0, 0, 0, .15);
    border-radius: var(--prod-radius-lg);
    overflow: hidden;
    background: var(--color-background-secondary, #F8FAFC);
    transition: border-color .2s, background .2s;
  }

  .prod-image-upload:hover .prod-image-preview {
    border-color: var(--prod-blue);
    background: var(--prod-blue-pale);
  }

  .prod-image-preview.has-image {
    border-style: solid;
    border-color: var(--prod-blue);
  }

  .prod-image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .prod-image-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    gap: 8px;
    color: var(--color-text-secondary);
  }

  .prod-image-placeholder i {
    font-size: 24px;
    opacity: .5;
  }

  .prod-image-placeholder span {
    font-size: 11px;
    text-align: center;
    padding: 0 12px;
  }

  /* ── Product Avatar with Image ───────────────────────── */
  .prod-avatar {
    width: 36px;
    height: 36px;
    border-radius: var(--prod-radius-sm);
    background: linear-gradient(135deg, var(--prod-blue-pale) 0%, #E2E8F0 100%);
    color: var(--prod-blue);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 13px;
    flex-shrink: 0;
    overflow: hidden;
  }

  .prod-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  /* ── Buttons ─────────────────────────────────────────── */
  .prod-btn {
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
    letter-spacing: -.1px;
    line-height: 1;
  }

  .prod-btn:hover {
    background: var(--color-background-secondary, #F8FAFC);
    border-color: rgba(0, 0, 0, .18);
  }

  .prod-btn-primary {
    background: var(--prod-blue);
    color: #fff;
    border-color: var(--prod-blue);
    box-shadow: 0 1px 3px rgba(26, 107, 191, .3);
  }

  .prod-btn-primary:hover {
    background: var(--prod-blue-dark);
    border-color: var(--prod-blue-dark);
    box-shadow: 0 2px 6px rgba(26, 107, 191, .35);
  }

  /* ── Toast Notifications ── */
  .prod-toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .prod-toast {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--prod-radius-md);
    padding: 14px 18px;
    box-shadow: var(--prod-shadow-md);
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 280px;
    max-width: 400px;
    animation: prodToastIn .3s cubic-bezier(.34,1.35,.64,1);
  }
  @keyframes prodToastIn {
    from { opacity: 0; transform: translateX(30px); }
    to { opacity: 1; transform: translateX(0); }
  }
  .prod-toast.success { border-left: 4px solid var(--prod-green); }
  .prod-toast.error { border-left: 4px solid var(--prod-red); }
  .prod-toast.info { border-left: 4px solid var(--prod-blue); }
  .prod-toast-icon {
    width: 24px; height: 24px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%; flex-shrink: 0;
  }
  .prod-toast.success .prod-toast-icon { background: var(--prod-green-pale); color: var(--prod-green); }
  .prod-toast.error .prod-toast-icon { background: var(--prod-red-pale); color: var(--prod-red); }
  .prod-toast.info .prod-toast-icon { background: var(--prod-blue-pale); color: var(--prod-blue); }
  .prod-toast-message {
    font-size: 13px; font-weight: 500;
    color: var(--color-text-primary);
  }

  /* ── Table ──────────────────────────────────────────── */
  .prod-table-wrap {
    overflow-x: auto;
  }

  .prod-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    font-family: var(--prod-font);
  }

  .prod-table thead tr {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .prod-table thead th {
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

  .prod-table thead th.center {
    text-align: center;
  }

  .prod-table tbody tr {
    border-bottom: 1px solid rgba(0, 0, 0, .05);
    transition: background .12s;
  }

  .prod-table tbody tr:last-child {
    border-bottom: none;
  }

  .prod-table tbody tr:hover {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .prod-table td {
    padding: 13px 18px;
    vertical-align: middle;
    white-space: nowrap;
  }

  /* Product name cell */
  .prod-name-primary {
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-primary);
    letter-spacing: -.1px;
  }

  .prod-name-secondary {
    font-size: 11px;
    color: var(--color-text-secondary);
    margin-top: 2px;
    font-family: var(--prod-mono);
    font-weight: 400;
  }

  /* Product avatar */
  .prod-avatar {
    width: 36px;
    height: 36px;
    border-radius: var(--prod-radius-sm);
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
    flex-shrink: 0;
    letter-spacing: -.05em;
    margin-right: 10px;
  }

  .prod-name-cell {
    display: flex;
    align-items: center;
  }

  /* Badges */
  .prod-type-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
    text-transform: capitalize;
    background: var(--color-background-secondary, #F1F5F9);
    color: var(--color-text-secondary);
    border: 1px solid rgba(0, 0, 0, .07);
    letter-spacing: .01em;
  }

  .prod-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .02em;
  }

  .prod-status-badge::before {
    content: '';
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: currentColor;
    opacity: .7;
  }

  .prod-status-badge.active {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .prod-status-badge.low {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  /* Stock cell */
  .prod-stock-val {
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-primary);
    font-family: var(--prod-mono);
  }

  .prod-stock-uom {
    font-size: 11px;
    color: var(--color-text-secondary);
    margin-left: 3px;
    font-family: var(--prod-font);
  }

  /* Price */
  .prod-price {
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-primary);
    font-family: var(--prod-mono);
  }

  .prod-price-currency {
    font-size: 11px;
    color: var(--color-text-secondary);
    margin-right: 1px;
    font-family: var(--prod-font);
  }

  /* Action buttons */
  .prod-actions-cell {
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .prod-action-btn {
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
    color: var(--color-text-secondary);
    font-family: var(--prod-font);
    letter-spacing: -.05em;
  }

  .prod-action-btn:hover {
    background: var(--color-background-secondary, #F8FAFC);
    color: var(--color-text-primary);
    border-color: rgba(0, 0, 0, .15);
  }

  .prod-action-btn.view:hover {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
    border-color: rgba(26, 107, 191, .2);
  }

  .prod-action-btn.edit:hover {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
    border-color: rgba(230, 81, 0, .2);
  }

  .prod-action-btn.danger:hover {
    background: var(--prod-red-pale);
    color: var(--prod-red);
    border-color: rgba(198, 40, 40, .2);
  }

  /* Empty state */
  .prod-empty-state {
    padding: 64px 24px;
    text-align: center;
    color: var(--color-text-secondary);
  }

  .prod-empty-icon {
    font-size: 32px;
    opacity: .25;
    margin-bottom: 12px;
  }

  .prod-empty-text {
    font-size: 14px;
    font-weight: 500;
    color: var(--color-text-secondary);
    margin-bottom: 4px;
  }

  .prod-empty-sub {
    font-size: 12px;
    color: var(--color-text-secondary);
    opacity: .7;
  }

  /* ── Modals ──────────────────────────────────────────── */
  .prod-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, .5);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 9000;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }

  .prod-overlay.open {
    display: flex;
  }

  .prod-modal {
    background: var(--color-background-primary, #fff);
    border-radius: var(--prod-radius-xl);
    border: 1px solid rgba(0, 0, 0, .08);
    width: 100%;
    max-width: 700px;
    max-height: 92vh;
    overflow-y: auto;
    box-shadow: var(--prod-shadow-lg);
    animation: modalIn .2s cubic-bezier(.22, .68, 0, 1.2);
  }

  .prod-modal.wide {
    max-width: 880px;
  }

  @keyframes modalIn {
    from {
      opacity: 0;
      transform: translateY(12px) scale(.98);
    }

    to {
      opacity: 1;
      transform: none;
    }
  }

  .prod-modal-header {
    padding: 22px 26px 18px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    position: sticky;
    top: 0;
    background: var(--color-background-primary, #fff);
    border-radius: var(--prod-radius-xl) var(--prod-radius-xl) 0 0;
    z-index: 1;
  }

  .prod-modal-title {
    font-size: 17px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.3px;
  }

  .prod-modal-subtitle {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-top: 2px;
  }

  .prod-modal-close {
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
    transition: all .15s;
    flex-shrink: 0;
  }

  .prod-modal-close:hover {
    background: rgba(0, 0, 0, .1);
    color: var(--color-text-primary);
  }

  .prod-modal-body {
    padding: 26px;
  }

  .prod-modal-footer {
    padding: 18px 26px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    position: sticky;
    bottom: 0;
    background: var(--color-background-primary, #fff);
    border-radius: 0 0 var(--prod-radius-xl) var(--prod-radius-xl);
    flex-wrap: wrap;
  }

  /* ── Custom Alert Dialog (replaces SweetAlert) ─────────── */
  .prod-alert-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, .5);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }

  .prod-alert-overlay.open {
    display: flex;
  }

  .prod-alert {
    background: var(--color-background-primary, #fff);
    border-radius: var(--prod-radius-xl);
    border: 1px solid rgba(0, 0, 0, .08);
    width: 100%;
    max-width: 420px;
    box-shadow: var(--prod-shadow-lg);
    animation: alertIn .2s cubic-bezier(.22, .68, 0, 1.2);
  }

  @keyframes alertIn {
    from { opacity: 0; transform: translateY(12px) scale(.98); }
    to { opacity: 1; transform: none; }
  }

  .prod-alert-header {
    padding: 22px 26px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .prod-alert-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }

  .prod-alert-icon.success { background: var(--prod-green-pale); color: var(--prod-green); }
  .prod-alert-icon.error { background: var(--prod-red-pale); color: var(--prod-red); }
  .prod-alert-icon.warning { background: var(--prod-amber-pale); color: var(--prod-amber); }
  .prod-alert-icon.info { background: var(--prod-blue-pale); color: var(--prod-blue); }

  .prod-alert-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.3px;
  }

  .prod-alert-body {
    padding: 0 26px 20px;
  }

  .prod-alert-message {
    font-size: 14px;
    color: var(--color-text-secondary);
    line-height: 1.5;
  }

  .prod-alert-footer {
    padding: 0 26px 22px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    flex-wrap: wrap;
  }

  .prod-alert-btn {
    padding: 9px 18px;
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

  .prod-alert-btn:hover {
    background: var(--color-background-secondary, #F8FAFC);
    border-color: rgba(0, 0, 0, .18);
  }

  .prod-alert-btn.primary {
    background: var(--prod-blue);
    color: #fff;
    border-color: var(--prod-blue);
  }

  .prod-alert-btn.primary:hover {
    background: var(--prod-blue-dark);
    border-color: var(--prod-blue-dark);
  }

  .prod-alert-btn.danger {
    background: var(--prod-red);
    color: #fff;
    border-color: var(--prod-red);
  }

  .prod-alert-btn.danger:hover {
    background: #a02222;
    border-color: #a02222;
  }

  .prod-alert-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 26px;
    gap: 12px;
  }

  .prod-alert-loading i {
    font-size: 24px;
    color: var(--prod-blue);
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }

  .prod-alert-loading-text {
    font-size: 14px;
    color: var(--color-text-secondary);
  }

  /* ── Form ───────────────────────────────────────────── */
  .prod-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
  }

  .prod-form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .prod-form-group.full {
    grid-column: 1 / -1;
  }

  .prod-form-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .07em;
  }

  .prod-form-label .req {
    color: var(--prod-red);
    margin-left: 2px;
  }

  .prod-form-input,
  .prod-form-select,
  .prod-form-textarea {
    font-family: var(--prod-font);
    font-size: 13.5px;
    color: var(--color-text-primary);
    background: var(--color-background-secondary, #F8FAFC);
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--prod-radius-md);
    padding: 9px 13px;
    outline: none;
    width: 100%;
    transition: border-color .15s, box-shadow .15s, background .15s;
  }

  .prod-form-input:focus,
  .prod-form-select:focus,
  .prod-form-textarea:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    background: var(--color-background-primary, #fff);
  }

  .prod-form-textarea {
    resize: vertical;
    min-height: 76px;
    line-height: 1.5;
  }

  .prod-divider {
    grid-column: 1 / -1;
    border: none;
    border-top: 1px solid rgba(0, 0, 0, .06);
    margin: 2px 0;
  }

  .prod-section-label {
    grid-column: 1 / -1;
    font-size: 11px;
    font-weight: 700;
    color: var(--prod-blue);
    text-transform: uppercase;
    letter-spacing: .1em;
    padding-top: 2px;
  }

  .prod-checkbox-group {
    grid-column: 1 / -1;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }

  .prod-checkbox-item {
    display: flex;
    align-items: center;
    gap: 9px;
    font-size: 13px;
    color: var(--color-text-primary);
    cursor: pointer;
    padding: 9px 14px;
    border: 1px solid rgba(0, 0, 0, .1);
    border-radius: var(--prod-radius-md);
    transition: all .15s;
    user-select: none;
    background: var(--color-background-secondary, #F8FAFC);
  }

  .prod-checkbox-item:has(input:checked) {
    background: var(--prod-blue-pale);
    border-color: rgba(26, 107, 191, .3);
    color: var(--prod-blue);
  }

  .prod-checkbox-item input[type=checkbox] {
    width: 15px;
    height: 15px;
    cursor: pointer;
    accent-color: var(--prod-blue);
  }

  /* ── Details grid (view modal) ────────────────────────── */
  .prod-details-image-wrap {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
  }

  .prod-details-image {
    max-width: 200px;
    max-height: 200px;
    object-fit: cover;
    border-radius: var(--prod-radius-lg);
    border: 1px solid rgba(0, 0, 0, .08);
    box-shadow: var(--prod-shadow-sm);
  }

  .prod-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 28px;
  }

  .prod-details-section-title {
    font-size: 11px;
    font-weight: 700;
    color: var(--prod-blue);
    text-transform: uppercase;
    letter-spacing: .1em;
    margin-bottom: 14px;
    padding-bottom: 8px;
    border-bottom: 2px solid var(--prod-blue-pale);
  }

  .prod-detail-row {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 12px;
    padding: 7px 0;
    border-bottom: 1px solid rgba(0, 0, 0, .04);
  }

  .prod-detail-row:last-child {
    border-bottom: none;
  }

  .prod-detail-key {
    font-size: 12px;
    color: var(--color-text-secondary);
    flex-shrink: 0;
    font-weight: 500;
  }

  .prod-detail-val {
    font-size: 13px;
    color: var(--color-text-primary);
    font-weight: 500;
    text-align: right;
  }

  /* ── Loading skeleton ─────────────────────────────────── */
  .prod-skeleton {
    background: linear-gradient(90deg, rgba(0, 0, 0, .05) 25%, rgba(0, 0, 0, .08) 50%, rgba(0, 0, 0, .05) 75%);
    background-size: 200% 100%;
    animation: shimmer 1.4s infinite;
    border-radius: 4px;
    display: inline-block;
  }

  @keyframes shimmer {
    0% {
      background-position: 200% 0;
    }

    100% {
      background-position: -200% 0;
    }
  }

  /* ── Pagination ─────────────────────────────────────── */
  .prod-pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    background: var(--color-background-primary, #fff);
    gap: 16px;
    flex-wrap: wrap;
  }

  .prod-pagination-info {
    font-size: 12px;
    color: var(--color-text-secondary);
  }

  .prod-pagination {
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .prod-page-btn {
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
    font-family: var(--prod-font);
  }

  .prod-page-btn:hover:not(:disabled) {
    background: var(--prod-blue-pale);
    border-color: var(--prod-blue);
    color: var(--prod-blue);
  }

  .prod-page-btn.active {
    background: var(--prod-blue);
    border-color: var(--prod-blue);
    color: #fff;
  }

  .prod-page-btn:disabled {
    opacity: .4;
    cursor: not-allowed;
  }

  .prod-entries-select {
    padding: 6px 28px 6px 12px;
    font-size: 13px;
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--prod-radius-md);
    background: var(--color-background-secondary, #F8FAFC);
    color: var(--color-text-primary);
    cursor: pointer;
    font-family: var(--prod-font);
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748B' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
  }

  .prod-entries-select:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    outline: none;
  }

  .prod-entries-label {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-right: 8px;
  }

  .prod-entries-wrap {
    display: flex;
    align-items: center;
  }

  /* ── Responsive ───────────────────────────────────────── */
  @media (max-width: 1400px) {
    .prod-page { padding: 24px 24px 36px; }
    .prod-search-input { width: 180px; }
  }

  @media (max-width: 1200px) {
    .prod-page { padding: 20px 20px 32px; }
    .prod-filter-item { min-width: 90px; }
    .prod-search-input { width: 160px; }
    .prod-btn { padding: 7px 14px; font-size: 12px; }
  }

  @media (max-width: 1024px) {
    .prod-page { padding: 18px 18px 28px; }
    .prod-stats-grid { grid-template-columns: repeat(2, 1fr); }
    .prod-card-header { flex-direction: column; align-items: stretch; gap: 12px; }
    .prod-toolbar { width: 100%; flex-wrap: wrap; gap: 10px; justify-content: space-between; }
    .prod-toolbar-left { order: 1; }
    .prod-toolbar-center { order: 3; width: 100%; }
    .prod-toolbar-right { order: 2; flex: 1 1 auto; justify-content: flex-end; }
    .prod-filter-bar { flex-wrap: wrap; gap: 8px; }
    .prod-filter-item { flex: 1 1 calc(25% - 8px); min-width: 100px; }
    .prod-search-wrap { min-width: 200px; }
    .prod-search-input { width: 100%; }
    .prod-table th:nth-child(4),
    .prod-table td:nth-child(4) { display: none; }
  }

  @media (max-width: 992px) {
    .prod-page { padding: 16px 16px 24px; }
    .prod-page-title { font-size: 20px; }
    .prod-stat-value { font-size: 28px; }
    .prod-card-header { padding: 14px 18px; }
    .prod-table td { padding: 10px 12px; }
    .prod-table th { padding: 10px 12px; font-size: 11px; }
    .prod-name-primary { font-size: 12px; }
    .prod-name-secondary { font-size: 10px; }
    .prod-type-badge, .prod-status-badge { font-size: 10px; padding: 2px 8px; }
    .prod-action-btn { width: 30px; height: 30px; font-size: 11px; }
    .prod-modal { max-width: 96vw; }
    .prod-modal-header { padding: 18px 22px 16px; }
    .prod-modal-body { padding: 20px 22px; }
    .prod-modal-footer { padding: 14px 22px; }
  }

  @media (max-width: 768px) {
    .prod-page { padding: 14px 14px 20px; }
    .prod-page-header { gap: 12px; margin-bottom: 20px; }
    .prod-page-title { font-size: 18px; }
    .prod-page-subtitle { font-size: 12px; }
    .prod-stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .prod-stat-card { padding: 16px 18px; }
    .prod-stat-icon { width: 32px; height: 32px; font-size: 14px; }
    .prod-stat-value { font-size: 24px; }
    .prod-card-header { padding: 12px 16px; }
    .prod-toolbar { flex-direction: column; align-items: stretch; gap: 10px; }
    .prod-toolbar-left { display: none; } /* Hide entries on tablet */
    .prod-toolbar-center { order: 2; width: 100%; }
    .prod-toolbar-right { order: 1; width: 100%; flex-direction: column; gap: 8px; }
    .prod-filter-bar { flex-wrap: wrap; gap: 8px; }
    .prod-filter-item { flex: 1 1 calc(50% - 4px); min-width: unset; max-width: none; }
    .prod-filter-select { width: 100%; font-size: 12px; padding: 7px 24px 7px 10px; }
    .prod-search-wrap { width: 100%; }
    .prod-search-input { width: 100%; }
    .prod-btn { width: 100%; justify-content: center; }
    .prod-table th:nth-child(2),
    .prod-table td:nth-child(2) { display: none; }
    .prod-table td { padding: 8px 10px; }
    .prod-table th { padding: 8px 10px; font-size: 10px; }
    .prod-name-primary { font-size: 11px; }
    .prod-name-secondary { font-size: 9px; }
    .prod-avatar { width: 32px; height: 32px; font-size: 11px; }
    .prod-stock-val { font-size: 12px; }
    .prod-stock-uom { font-size: 10px; }
    .prod-type-badge, .prod-status-badge { font-size: 9px; padding: 2px 6px; }
    .prod-action-btn { width: 28px; height: 28px; font-size: 10px; }
    /* Slide-up modal */
    .prod-overlay { padding: 0; align-items: flex-end; }
    .prod-modal { border-radius: 20px 20px 0 0; max-width: 100%; max-height: 95vh; }
    .prod-modal-header { border-radius: 20px 20px 0 0; padding: 16px 20px 14px; }
    .prod-modal-footer { border-radius: 0; padding: 12px 20px; flex-wrap: wrap; }
    .prod-modal-body { padding: 18px 20px; }
    .prod-modal-title { font-size: 15px; }
    .prod-pagination-wrap { flex-direction: column; align-items: flex-start; gap: 8px; }
    .prod-pagination-info { display: none; }
  }

  @media (max-width: 640px) {
    .prod-card-header { flex-direction: column; align-items: stretch; gap: 12px; }
    .prod-toolbar { flex-direction: column; align-items: stretch; gap: 8px; }
    .prod-toolbar-left { display: none; } /* Hide entries on mobile */
    .prod-toolbar-center { order: 3; width: 100%; }
    .prod-toolbar-right { order: 1; width: 100%; gap: 8px; }
    .prod-filter-bar { flex-direction: column; gap: 6px; }
    .prod-filter-item { width: 100%; flex: none; }
    .prod-filter-select { width: 100%; font-size: 14px; padding: 10px 28px 10px 12px; }
    .prod-filter-clear { width: 100%; height: 36px; }
    .prod-search-input { width: 100%; padding: 10px 14px 10px 34px; font-size: 14px; }
    .prod-btn { width: 100%; justify-content: center; padding: 10px 16px; font-size: 14px; }
    .prod-stats-grid { grid-template-columns: repeat(2, 1fr); }
  }

  @media (max-width: 600px) {

    .prod-details-grid,
    .prod-form-grid {
      grid-template-columns: 1fr;
    }

    .prod-form-group.full {
      grid-column: 1;
    }

    .prod-section-label,
    .prod-divider,
    .prod-details-section-title {
      grid-column: 1;
    }

    .prod-checkbox-group {
      grid-column: 1;
    }

    .prod-search-input {
      width: 100%;
    }

    .prod-page { padding: 12px 12px 18px; }
    .prod-page-title { font-size: 16px; }
    .prod-page-subtitle { font-size: 11px; }
    .prod-stat-card { padding: 14px 16px; }
    .prod-stat-icon { width: 30px; height: 30px; font-size: 13px; }
    .prod-stat-value { font-size: 22px; }
    .prod-card-header { padding: 10px 14px; }
    .prod-card-title { font-size: 13px; }
    .prod-table th:nth-child(3),
    .prod-table td:nth-child(3) { display: none; }
    .prod-modal-footer { flex-direction: column; align-items: stretch; }
    .prod-modal-footer .prod-btn { width: 100%; justify-content: center; }
    .prod-modal-title { font-size: 14px; }
    .prod-form-grid { gap: 14px; }
    .prod-form-input, .prod-form-select, .prod-form-textarea { font-size: 14px; padding: 8px 12px; }
    .prod-form-label { font-size: 11px; }
  }

  @media (max-width: 480px) {
    .prod-page { padding: 10px 10px 16px; }
    .prod-page-title { font-size: 15px; }
    .prod-stat-value { font-size: 20px; }
    .prod-modal-header { padding: 14px 18px 12px; }
    .prod-modal-body { padding: 16px 18px; }
    .prod-modal-footer { padding: 10px 18px; }
    .prod-modal-title { font-size: 13px; }
    .prod-form-input, .prod-form-select, .prod-form-textarea { font-size: 13px; padding: 7px 11px; }
    .prod-btn { padding: 7px 12px; font-size: 11px; }
  }

  @media (max-width: 360px) {
    .prod-page { padding: 8px 8px 14px; }
    .prod-page-title { font-size: 14px; }
    .prod-stat-card { padding: 12px 14px; }
    .prod-stat-value { font-size: 18px; }
    .prod-modal-header { padding: 12px 16px 10px; }
    .prod-modal-body { padding: 14px 16px; }
    .prod-modal-footer { padding: 8px 16px; }
    .prod-modal-title { font-size: 12px; }
    .prod-form-input, .prod-form-select, .prod-form-textarea { font-size: 12px; padding: 6px 10px; }
  }

  @media (max-width: 768px) and (orientation: landscape) {
    .prod-overlay { padding: 8px; align-items: center; }
    .prod-modal { border-radius: var(--prod-radius-xl); max-height: 90vh; }
    .prod-modal-header { border-radius: var(--prod-radius-xl) var(--prod-radius-xl) 0 0; }
    .prod-modal-body { padding: 14px 18px; }
    .prod-form-grid { grid-template-columns: 1fr 1fr; }
    .prod-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
  }

  @media (hover: none) and (pointer: coarse) {
    .prod-action-btn { width: 36px; height: 36px; font-size: 13px; }
    .prod-btn { padding: 9px 16px; }
    .prod-form-input, .prod-form-select, .prod-form-textarea { font-size: 16px; }
  }
</style>

<div class="prod-page">
  <!-- Header -->
  <div class="prod-page-header">
    <div class="prod-page-title-wrap">
      <div class="prod-page-eyebrow"><i class="fas fa-box-open" style="margin-right:5px"></i>Inventory</div>
      <div class="prod-page-title">Products</div>
      <div class="prod-page-subtitle">Manage products, stock levels, and costing</div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="prod-stats-grid">
    <div class="prod-stat-card blue">
      <div class="prod-stat-icon"><i class="fas fa-cube"></i></div>
      <div class="prod-stat-label">Total Products</div>
      <div class="prod-stat-value blue" id="statTotal">—</div>
    </div>
    <div class="prod-stat-card">
      <div class="prod-stat-icon"><i class="fas fa-tags"></i></div>
      <div class="prod-stat-label">Categories</div>
      <div class="prod-stat-value" id="statCategories">—</div>
    </div>
    <div class="prod-stat-card green">
      <div class="prod-stat-icon"><i class="fas fa-truck"></i></div>
      <div class="prod-stat-label">Suppliers</div>
      <div class="prod-stat-value green" id="statSuppliers">—</div>
    </div>
    <div class="prod-stat-card red">
      <div class="prod-stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
      <div class="prod-stat-label">Low Stock Items</div>
      <div class="prod-stat-value red" id="statLowStock">—</div>
    </div>
  </div>

  <!-- Products Table Card -->
  <div class="prod-card">
    <div class="prod-card-header">
      <div class="prod-card-title-wrap">
        <div class="prod-card-title">All Products</div>
        <div class="prod-card-count" id="tableCount">Loading…</div>
      </div>
      <div class="prod-toolbar">
        <div class="prod-toolbar-left">
          <div class="prod-entries-wrap">
            <span class="prod-entries-label">Show</span>
            <select class="prod-entries-select" id="entriesPerPage" onchange="changeEntriesPerPage()">
              <option value="10">10</option>
              <option value="25" selected>25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
          </div>
        </div>
        <div class="prod-toolbar-center">
          <div class="prod-filter-bar">
            <div class="prod-filter-item">
              <select class="prod-filter-select" id="filterCategory" onchange="filterTable()" aria-label="Filter by category">
                <option value="">All Categories</option>
              </select>
            </div>
            <div class="prod-filter-item">
              <select class="prod-filter-select" id="filterSupplier" onchange="filterTable()" aria-label="Filter by supplier">
                <option value="">All Suppliers</option>
              </select>
            </div>
            <div class="prod-filter-item">
              <select class="prod-filter-select" id="filterType" onchange="filterTable()" aria-label="Filter by type">
                <option value="">All Types</option>
                <option value="consumable">Consumable</option>
                <option value="non_consumable">Non-Consumable</option>
                <option value="food">Food</option>
                <option value="beverage">Beverage</option>
                <option value="cleaning">Cleaning</option>
                <option value="amenity">Amenity</option>
              </select>
            </div>
            <div class="prod-filter-item">
              <select class="prod-filter-select" id="filterStatus" onchange="filterTable()" aria-label="Filter by status">
                <option value="">All Status</option>
                <option value="for_sale">For Sale</option>
                <option value="for_rooms">For Rooms</option>
                <option value="low_stock">Low Stock</option>
              </select>
            </div>
            <button type="button" class="prod-filter-clear" id="clearFiltersBtn" onclick="clearAllFilters()" style="display:none" aria-label="Clear all filters">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="prod-toolbar-right">
          <div class="prod-search-wrap">
            <i class="fas fa-search" aria-hidden="true"></i>
            <input type="text" class="prod-search-input" id="searchInput" placeholder="Search products…" oninput="filterTable()">
          </div>
          <button class="prod-btn prod-btn-primary" onclick="openAddProductModal()">
            <i class="fas fa-plus" aria-hidden="true"></i> Add Product
          </button>
        </div>
      </div><!-- /.prod-toolbar -->
    </div><!-- /.prod-card-header -->
    <div class="prod-table-wrap">
      <table class="prod-table" id="productsTable">
        <thead>
          <tr>
            <th>Product</th>
            <th>Category</th>
            <th>Stock</th>
            <th>Unit Cost</th>
            <th class="center">Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="productsTableBody">
          <tr>
            <td colspan="6">
              <div class="prod-empty-state">
                <div class="prod-empty-icon"><i class="fas fa-spinner fa-spin"></i></div>
                <div class="prod-empty-text">Loading products…</div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="prod-pagination-wrap" id="paginationWrap" style="display:none">
      <div class="prod-pagination-info" id="paginationInfo">Showing 1-25 of 100 entries</div>
      <div class="prod-pagination" id="paginationControls">
        <!-- Pagination buttons generated by JS -->
      </div>
    </div>
  </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="prod-toast-container"></div>

<!-- Product Details Modal -->
<div class="prod-overlay" id="productDetailsModal" onclick="handleProductDetailsOverlayClick(event)">
  <div class="prod-modal wide" role="dialog" aria-modal="true" aria-labelledby="modalProductName">
    <div class="prod-modal-header">
      <div>
        <div class="prod-modal-title" id="modalProductName">Product Details</div>
        <div class="prod-modal-subtitle" id="modalProductCode"></div>
      </div>
      <button class="prod-modal-close" onclick="closeProductDetailsModal()" aria-label="Close">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="prod-modal-body" id="modalProductContent"></div>
  </div>
</div>

<!-- Quick Stock Adjustment Modal -->
<div class="prod-overlay" id="quickStockModal" onclick="handleQuickStockOverlayClick(event)">
  <div class="prod-modal" role="dialog" aria-modal="true">
    <div class="prod-modal-header">
      <div>
        <div class="prod-modal-title">Quick Stock Adjustment</div>
        <div class="prod-modal-subtitle" id="quickStockProductName"></div>
      </div>
      <button class="prod-modal-close" onclick="closeQuickStockModal()" aria-label="Close">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <form id="quickStockForm" onsubmit="submitQuickStockAdjust(event)">
      <div class="prod-modal-body">
        <div class="prod-form-group">
          <label class="prod-form-label">Current Stock</label>
          <input type="text" class="prod-form-input" id="quickStockCurrent" readonly style="background:var(--color-background-secondary)">
        </div>
        <div class="prod-form-group">
          <label class="prod-form-label">Adjustment Type</label>
          <select class="prod-form-select" id="quickStockType" required>
            <option value="add">Add Stock (+)</option>
            <option value="deduct">Deduct Stock (-)</option>
          </select>
        </div>
        <div class="prod-form-group">
          <label class="prod-form-label">Quantity</label>
          <input type="number" class="prod-form-input" id="quickStockQuantity" step="0.001" min="0.001" required>
        </div>
        <div class="prod-form-group">
          <label class="prod-form-label">Reason</label>
          <input type="text" class="prod-form-input" id="quickStockReason" placeholder="e.g., Stock count, Damaged items, etc.">
        </div>
        <input type="hidden" id="quickStockProductId">
      </div>
      <div class="prod-modal-footer">
        <button type="button" class="prod-btn" onclick="closeQuickStockModal()">
          <i class="fas fa-times"></i> Cancel
        </button>
        <button type="submit" class="prod-btn prod-btn-primary">
          <i class="fas fa-save"></i> Adjust Stock
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Add Product Modal -->
<div class="prod-overlay" id="addProductModal" onclick="handleAddProductOverlayClick(event)">
  <div class="prod-modal wide" role="dialog" aria-modal="true">
    <div class="prod-modal-header">
      <div>
        <div class="prod-modal-title">Add New Product</div>
        <div class="prod-modal-subtitle">Fill in the details to create a new inventory product</div>
      </div>
      <button class="prod-modal-close" onclick="closeAddProductModal()" aria-label="Close">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <form id="addProductForm" onsubmit="submitAddProduct(event)">
      <div class="prod-modal-body">
        <div class="prod-form-grid">

          <div class="prod-section-label"><i class="fas fa-info-circle" style="margin-right:5px;opacity:.6"></i>Basic Information</div>

          <div class="prod-form-group">
            <label class="prod-form-label">Product Code <span class="req">*</span></label>
            <input type="text" name="product_code" required class="prod-form-input" placeholder="e.g., PROD-001">
          </div>
          <div class="prod-form-group">
            <label class="prod-form-label">Product Name <span class="req">*</span></label>
            <input type="text" name="product_name" required class="prod-form-input" placeholder="e.g., Premium Shampoo">
          </div>
          <div class="prod-form-group">
            <label class="prod-form-label">Category <span class="req">*</span></label>
            <select name="category_id" id="addCategoryId" required class="prod-form-select">
              <option value="">Select Category</option>
            </select>
          </div>
          <div class="prod-form-group">
            <label class="prod-form-label">Unit of Measure <span class="req">*</span></label>
            <select name="uom_id" id="addUomId" required class="prod-form-select">
              <option value="">Select UOM</option>
            </select>
          </div>
          <div class="prod-form-group">
            <label class="prod-form-label">Product Type <span class="req">*</span></label>
            <select name="product_type" required class="prod-form-select">
              <option value="consumable">Consumable</option>
              <option value="non_consumable">Non-Consumable</option>
              <option value="food">Food</option>
              <option value="beverage">Beverage</option>
              <option value="amenity">Amenity</option>
              <option value="cleaning">Cleaning</option>
              <option value="linen">Linen</option>
              <option value="equipment">Equipment</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="prod-form-group">
            <label class="prod-form-label">Brand</label>
            <input type="text" name="brand" class="prod-form-input" placeholder="e.g., Dove">
          </div>
          <div class="prod-form-group full">
            <label class="prod-form-label">Description</label>
            <textarea name="description" rows="2" class="prod-form-textarea" placeholder="Product description…"></textarea>
          </div>

          <div class="prod-form-group full">
            <label class="prod-form-label">Product Image</label>
            <div class="prod-image-upload">
              <input type="file" id="addProductImage" name="product_image" accept="image/*" class="prod-image-input" onchange="previewProductImage(this, 'addProductPreview')">
              <div class="prod-image-preview" id="addProductPreview">
                <div class="prod-image-placeholder">
                  <i class="fas fa-camera"></i>
                  <span>Click to upload image</span>
                </div>
              </div>
            </div>
          </div>

          <hr class="prod-divider">
          <div class="prod-section-label"><i class="fas fa-peso-sign" style="margin-right:5px;opacity:.6"></i>Costing</div>

          <div class="prod-form-group">
            <label class="prod-form-label">Pricing Mode</label>
            <select name="pricing_mode" id="addPricingMode" class="prod-form-select" onchange="onProductPricingModeChange('add')">
              <option value="fixed_price">Fixed Price (Manual)</option>
              <option value="percentage_markup">Percentage Markup (based on cost)</option>
              <option value="fixed_markup">Fixed Amount Markup (based on cost)</option>
            </select>
          </div>

          <div class="prod-form-group" id="addMarkupFields" style="display:none;">
            <label class="prod-form-label" id="addMarkupLabel">Markup Value</label>
            <input type="number" name="markup_value" id="addMarkupValue" step="0.01" min="0" class="prod-form-input" placeholder="0" onchange="calculateProductSellingPrice('add')">
            <small style="color:var(--color-text-secondary);font-size:11px;margin-top:4px;display:block;" id="addMarkupHint">Enter percentage or fixed amount</small>
          </div>

          <div class="prod-form-group">
            <label class="prod-form-label">Unit Cost (PHP)</label>
            <div style="display:flex;gap:8px;">
              <input type="number" name="unit_cost" id="addUnitCost" step="0.01" min="0" value="0" class="prod-form-input" placeholder="0.00" onchange="calculateProductSellingPrice('add')" style="flex:1">
              <button type="button" class="prod-btn" onclick="fetchLastPoCostByCode('add')" title="Fetch last PO cost from purchase orders">
                <i class="fas fa-sync-alt"></i>
              </button>
            </div>
            <small style="color:var(--color-text-secondary);font-size:11px;margin-top:4px;display:block;">
      Enter product code and UOM, then click sync to fetch last PO cost
    </small>
          </div>
          <div class="prod-form-group">
            <label class="prod-form-label">Selling Price (PHP)</label>
            <input type="number" name="selling_price" id="addSellingPrice" step="0.01" min="0" value="0" class="prod-form-input" placeholder="0.00">
            <small style="color:var(--color-text-secondary);font-size:11px;margin-top:4px;display:block;" id="addSellingPriceHint">Enter manual selling price</small>
          </div>

          <div id="addCalculatedPriceDisplay" style="display:none;margin-top:8px;padding:8px 12px;background:var(--prod-green-pale);border-radius:var(--prod-radius-sm);font-size:12px;color:var(--prod-green);">
            <i class="fas fa-calculator"></i> Calculated: <strong id="addCalculatedPriceValue">₱0.00</strong>
          </div>

          <hr class="prod-divider">
          <div class="prod-section-label"><i class="fas fa-boxes-stacked" style="margin-right:5px;opacity:.6"></i>Stock Control</div>

          <div class="prod-form-group">
            <label class="prod-form-label">Initial Stock</label>
            <input type="number" name="initial_stock" step="0.001" min="0" value="0" class="prod-form-input" placeholder="0">
          </div>
          <div class="prod-form-group">
            <label class="prod-form-label">Reorder Point</label>
            <input type="number" name="reorder_point" step="0.001" min="0" value="0" class="prod-form-input" placeholder="0">
          </div>
          <div class="prod-form-group">
            <label class="prod-form-label">Minimum Stock</label>
            <input type="number" name="minimum_stock" step="0.001" min="0" value="0" class="prod-form-input" placeholder="0">
          </div>
          <div class="prod-form-group">
            <label class="prod-form-label">Maximum Stock</label>
            <input type="number" name="maximum_stock" step="0.001" min="0" class="prod-form-input" placeholder="Optional">
          </div>

          <hr class="prod-divider">
          <div class="prod-section-label"><i class="fas fa-truck-fast" style="margin-right:5px;opacity:.6"></i>Supplier</div>

          <div class="prod-form-group full">
            <label class="prod-form-label">Preferred Supplier</label>
            <select name="preferred_supplier_id" id="addSupplierId" class="prod-form-select">
              <option value="">No Supplier</option>
            </select>
          </div>

          <hr class="prod-divider">
          <div class="prod-section-label"><i class="fas fa-sliders" style="margin-right:5px;opacity:.6"></i>Settings</div>

          <div class="prod-checkbox-group">
            <label class="prod-checkbox-item">
              <input type="checkbox" name="is_for_sale"> <span>Available for Sale</span>
            </label>
            <label class="prod-checkbox-item">
              <input type="checkbox" name="is_for_rooms"> <span>For Rooms</span>
            </label>
            <label class="prod-checkbox-item">
              <input type="checkbox" name="track_inventory" checked> <span>Track Inventory</span>
            </label>
          </div>
        </div>
      </div>
      <div class="prod-modal-footer">
        <button type="button" class="prod-btn" onclick="closeAddProductModal()">
          <i class="fas fa-times"></i> Cancel
        </button>
        <button type="submit" class="prod-btn prod-btn-primary">
          <i class="fas fa-save"></i> Save Product
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Product Modal -->
<div class="prod-overlay" id="editProductModal" onclick="handleEditProductOverlayClick(event)">
  <div class="prod-modal wide" role="dialog" aria-modal="true">
    <div class="prod-modal-header">
      <div>
        <div class="prod-modal-title">Edit Product</div>
        <div class="prod-modal-subtitle">Update product information</div>
      </div>
      <button class="prod-modal-close" onclick="closeEditProductModal()" aria-label="Close">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <form id="editProductForm" onsubmit="submitEditProduct(event)">
      <div class="prod-modal-body">
        <div class="prod-form-grid">
          <input type="hidden" id="editProductId" name="product_id">

          <div class="prod-section-label"><i class="fas fa-info-circle" style="margin-right:5px;opacity:.6"></i>Basic Information</div>

          <div class="prod-form-group">
            <label class="prod-form-label">Product Code <span class="req">*</span></label>
            <input type="text" id="editProductCode" name="product_code" required class="prod-form-input">
          </div>

          <div class="prod-form-group">
            <label class="prod-form-label">Product Name <span class="req">*</span></label>
            <input type="text" id="editProductName" name="product_name" required class="prod-form-input">
          </div>

          <div class="prod-form-group">
            <label class="prod-form-label">Category</label>
            <select id="editCategoryId" name="category_id" class="prod-form-select">
              <option value="">Select Category</option>
            </select>
          </div>

          <div class="prod-form-group">
            <label class="prod-form-label">Unit of Measure</label>
            <select id="editUomId" name="uom_id" class="prod-form-select">
              <option value="">Select UOM</option>
            </select>
          </div>

          <div class="prod-form-group">
            <label class="prod-form-label">Product Type</label>
            <input type="text" id="editProductType" name="product_type" class="prod-form-input" placeholder="e.g., Consumable, Asset">
          </div>

          <div class="prod-form-group">
            <label class="prod-form-label">Brand</label>
            <input type="text" id="editBrand" name="brand" class="prod-form-input" placeholder="e.g., Brand Name">
          </div>

          <div class="prod-form-group full">
            <label class="prod-form-label">Description</label>
            <textarea id="editDescription" name="description" class="prod-form-textarea" rows="2" placeholder="Product description..."></textarea>
          </div>

          <div class="prod-form-group full">
            <label class="prod-form-label">Product Image</label>
            <div class="prod-image-upload">
              <input type="file" id="editProductImage" name="product_image" accept="image/*" class="prod-image-input" onchange="previewProductImage(this, 'editProductPreview')">
              <div class="prod-image-preview" id="editProductPreview">
                <div class="prod-image-placeholder">
                  <i class="fas fa-camera"></i>
                  <span>Click to upload image</span>
                </div>
              </div>
              <input type="hidden" id="editProductImageUrl" name="product_image_url">
            </div>
          </div>

          <div class="prod-divider"></div>

          <div class="prod-section-label"><i class="fas fa-dollar-sign" style="margin-right:5px;opacity:.6"></i>Costing &amp; Stock</div>

          <div class="prod-form-group">
            <label class="prod-form-label">Pricing Mode</label>
            <select name="pricing_mode" id="editPricingMode" class="prod-form-select" onchange="onProductPricingModeChange('edit')">
              <option value="fixed_price">Fixed Price (Manual)</option>
              <option value="percentage_markup">Percentage Markup (based on cost)</option>
              <option value="fixed_markup">Fixed Amount Markup (based on cost)</option>
            </select>
          </div>

          <div class="prod-form-group" id="editMarkupFields" style="display:none;">
            <label class="prod-form-label" id="editMarkupLabel">Markup Value</label>
            <input type="number" name="markup_value" id="editMarkupValue" step="0.01" min="0" class="prod-form-input" placeholder="0" onchange="calculateProductSellingPrice('edit')">
            <small style="color:var(--color-text-secondary);font-size:11px;margin-top:4px;display:block;" id="editMarkupHint">Enter percentage or fixed amount</small>
          </div>

          <div class="prod-form-group">
            <label class="prod-form-label">Unit Cost <span class="req">*</span></label>
            <div style="display:flex;gap:8px;">
              <input type="number" id="editUnitCost" name="unit_cost" step="0.01" min="0" required class="prod-form-input" placeholder="0.00" onchange="calculateProductSellingPrice('edit')" style="flex:1">
              <button type="button" class="prod-btn" onclick="fetchProductLastPoCost(document.getElementById('editProductId').value, document.getElementById('editUomId').value, 'edit')" title="Fetch last PO cost from purchase orders">
                <i class="fas fa-sync-alt"></i>
              </button>
            </div>
          </div>

          <div class="prod-form-group">
            <label class="prod-form-label">Selling Price <span class="req">*</span></label>
            <input type="number" id="editSellingPrice" name="selling_price" step="0.01" min="0" required class="prod-form-input" placeholder="0.00">
            <small style="color:var(--color-text-secondary);font-size:11px;margin-top:4px;display:block;" id="editSellingPriceHint">Enter manual selling price</small>
          </div>

          <div id="editCalculatedPriceDisplay" style="display:none;margin-top:8px;padding:8px 12px;background:var(--prod-green-pale);border-radius:var(--prod-radius-sm);font-size:12px;color:var(--prod-green);">
            <i class="fas fa-calculator"></i> Calculated: <strong id="editCalculatedPriceValue">₱0.00</strong>
          </div>

          <div class="prod-form-group">
            <label class="prod-form-label">Reorder Point</label>
            <input type="number" id="editReorderPoint" name="reorder_point" step="0.001" min="0" class="prod-form-input" placeholder="Low stock threshold">
          </div>

          <div class="prod-form-group">
            <label class="prod-form-label">Minimum Stock</label>
            <input type="number" id="editMinimumStock" name="minimum_stock" step="0.001" min="0" class="prod-form-input" placeholder="Minimum stock level">
          </div>

          <div class="prod-form-group">
            <label class="prod-form-label">Maximum Stock</label>
            <input type="number" id="editMaximumStock" name="maximum_stock" step="0.001" min="0" class="prod-form-input" placeholder="Maximum stock level">
          </div>

          <div class="prod-form-group">
            <label class="prod-form-label">Preferred Supplier</label>
            <select id="editSupplierId" name="preferred_supplier_id" class="prod-form-select">
              <option value="">Select Supplier</option>
            </select>
          </div>

          <div class="prod-divider"></div>

          <div class="prod-section-label"><i class="fas fa-cog" style="margin-right:5px;opacity:.6"></i>Settings</div>

          <div class="prod-checkbox-group">
            <label class="prod-checkbox-item">
              <input type="checkbox" id="editForSale" name="is_for_sale"> <span>For Sale</span>
            </label>
            <label class="prod-checkbox-item">
              <input type="checkbox" id="editForRooms" name="is_for_rooms"> <span>For Rooms</span>
            </label>
            <label class="prod-checkbox-item">
              <input type="checkbox" id="editTrackInventory" name="track_inventory"> <span>Track Inventory</span>
            </label>
          </div>
        </div>
      </div>
      <div class="prod-modal-footer">
        <button type="button" class="prod-btn" onclick="closeEditProductModal()">
          <i class="fas fa-times"></i> Cancel
        </button>
        <button type="submit" class="prod-btn prod-btn-primary">
          <i class="fas fa-save"></i> Update Product
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Custom Alert Dialog (replaces SweetAlert) -->
<div class="prod-alert-overlay" id="prodAlertOverlay">
  <div class="prod-alert" id="prodAlert">
    <div class="prod-alert-header">
      <div class="prod-alert-icon" id="prodAlertIcon"><i class="fas fa-info"></i></div>
      <div class="prod-alert-title" id="prodAlertTitle">Alert</div>
    </div>
    <div class="prod-alert-body">
      <div class="prod-alert-message" id="prodAlertMessage">Message goes here</div>
    </div>
    <div class="prod-alert-footer" id="prodAlertFooter">
      <button class="prod-alert-btn" id="prodAlertCancelBtn" style="display:none">Cancel</button>
      <button class="prod-alert-btn primary" id="prodAlertConfirmBtn">OK</button>
    </div>
  </div>
</div>

<script>
  let productsData = [];
  let filteredData = [];
  let currentPage = 1;
  let entriesPerPage = 25;
  let relatedData = {
    categories: [],
    uoms: [],
    suppliers: []
  };
  let currentEditingProduct = null;
  const CSRF_TOKEN = <?php echo json_encode(CSRF_TOKEN); ?>;

  // Normalize product image URL for display (uses relative paths)
  function normalizeProductImageUrl(url) {
    if (!url) return null;
    
    // If it's already an absolute URL (http/https), use as-is
    if (url.startsWith('http')) return url;
    
    // Extract filename from various path formats
    const filename = url.split('/').pop();
    
    // Return relative path from build/views/inventory/ to uploads/products/
    // build/views/inventory/ -> ../../uploads/products/filename
    return '../../uploads/products/' + filename;
  }

  // Toast notification function
  function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `prod-toast ${type}`;
    
    const iconMap = {
      success: 'fa-check',
      error: 'fa-times',
      info: 'fa-info'
    };
    
    toast.innerHTML = `
      <div class="prod-toast-icon">
        <i class="fas ${iconMap[type] || iconMap.info}"></i>
      </div>
      <div class="prod-toast-message">${message}</div>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateX(30px)';
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }

  // Custom Alert Dialog Helper (replaces SweetAlert)
  function showProdAlert(options) {
    const overlay = document.getElementById('prodAlertOverlay');
    const alertEl = document.getElementById('prodAlert');

    // Handle loading state
    if (options.loading) {
      alertEl.innerHTML = `
        <div class="prod-alert-loading">
          <i class="fas fa-spinner"></i>
          <span class="prod-alert-loading-text">${options.loadingText || 'Loading...'}</span>
        </div>
      `;
      overlay.classList.add('open');
      return;
    }

    // Restore normal structure if it was in loading state
    if (!alertEl.querySelector('.prod-alert-header')) {
      alertEl.innerHTML = `
        <div class="prod-alert-header">
          <div class="prod-alert-icon" id="prodAlertIcon"><i class="fas fa-info"></i></div>
          <div class="prod-alert-title" id="prodAlertTitle">Alert</div>
        </div>
        <div class="prod-alert-body">
          <div class="prod-alert-message" id="prodAlertMessage">Message goes here</div>
        </div>
        <div class="prod-alert-footer" id="prodAlertFooter">
          <button class="prod-alert-btn" id="prodAlertCancelBtn" style="display:none">Cancel</button>
          <button class="prod-alert-btn primary" id="prodAlertConfirmBtn">OK</button>
        </div>
      `;
    }

    // Re-query elements after possible HTML restoration
    const iconEl = document.getElementById('prodAlertIcon');
    const titleEl = document.getElementById('prodAlertTitle');
    const messageEl = document.getElementById('prodAlertMessage');
    const cancelBtn = document.getElementById('prodAlertCancelBtn');
    const confirmBtn = document.getElementById('prodAlertConfirmBtn');

    // Set icon
    const iconMap = {
      success: 'fa-check',
      error: 'fa-times',
      warning: 'fa-exclamation-triangle',
      info: 'fa-info'
    };
    const iconClass = options.icon || 'info';
    iconEl.className = 'prod-alert-icon ' + iconClass;
    iconEl.innerHTML = `<i class="fas ${iconMap[iconClass] || iconMap.info}"></i>`;

    // Set title and message
    titleEl.textContent = options.title || 'Alert';
    messageEl.innerHTML = options.text || options.html || '';

    // Handle buttons
    if (options.showCancelButton) {
      cancelBtn.style.display = 'inline-flex';
      cancelBtn.textContent = options.cancelButtonText || 'Cancel';
      cancelBtn.onclick = () => {
        overlay.classList.remove('open');
        if (options.cancelCallback) options.cancelCallback();
      };
    } else {
      cancelBtn.style.display = 'none';
    }

    confirmBtn.textContent = options.confirmButtonText || 'OK';
    confirmBtn.className = 'prod-alert-btn ' + (options.confirmButtonClass || 'primary');
    if (options.confirmButtonColor === '#C62828') {
      confirmBtn.className = 'prod-alert-btn danger';
    }
    confirmBtn.onclick = () => {
      overlay.classList.remove('open');
      if (options.confirmCallback) options.confirmCallback();
    };

    // Show alert
    overlay.classList.add('open');

    // Return promise-like object
    return {
      then: function(callback) {
        confirmBtn.onclick = () => {
          overlay.classList.remove('open');
          callback({ isConfirmed: true });
        };
        if (options.showCancelButton) {
          cancelBtn.onclick = () => {
            overlay.classList.remove('open');
            callback({ isConfirmed: false });
          };
        }
      }
    };
  }

  function closeProdAlert() {
    document.getElementById('prodAlertOverlay').classList.remove('open');
  }

  document.addEventListener('DOMContentLoaded', function() {
    ['productDetailsModal', 'addProductModal', 'editProductModal', 'quickStockModal', 'prodAlertOverlay'].forEach(function(id) {
      var el = document.getElementById(id);
      if (el) document.body.appendChild(el);
    });
    fetchProducts();
  });

  async function fetchProducts() {
    try {
      const [prodRes, relRes] = await Promise.all([
        fetch('../api/products.php?action=list'),
        fetch('../api/products.php?action=related')
      ]);
      const prodResult = await prodRes.json();
      const relResult = await relRes.json();
      if (prodResult.success) {
        productsData = prodResult.data;
        filteredData = [...productsData];
        currentPage = 1;
        renderTable();
        renderStats(relResult);
      } else {
        showEmptyState(prodResult.message);
      }
      if (relResult.success) {
        relatedData = relResult;
        populateDropdowns();
      }
    } catch (e) {
      showEmptyState('Failed to load data');
    }
  }

  function showEmptyState(msg) {
    document.getElementById('productsTableBody').innerHTML = `
      <tr><td colspan="6">
        <div class="prod-empty-state">
          <div class="prod-empty-icon"><i class="fas fa-circle-xmark"></i></div>
          <div class="prod-empty-text">${msg}</div>
        </div>
      </td></tr>`;
  }

  function renderStats(rel) {
    document.getElementById('statTotal').textContent = productsData.length;
    document.getElementById('statCategories').textContent = rel.categories ? rel.categories.length : '—';
    document.getElementById('statSuppliers').textContent = rel.suppliers ? rel.suppliers.length : '—';
    document.getElementById('statLowStock').textContent = productsData.filter(p => parseFloat(p.stock_on_hand) <= parseFloat(p.low_stock_threshold)).length;
  }

  function populateDropdowns() {
    const categoryOptions = '<option value="">Select Category</option>' +
      relatedData.categories.map(c => `<option value="${c.category_id}">${c.category_name} (${c.category_code})</option>`).join('');
    const uomOptions = '<option value="">Select UOM</option>' +
      relatedData.uoms.map(u => `<option value="${u.uom_id}">${u.uom_name} (${u.uom_code})</option>`).join('');
    const supplierOptions = '<option value="">No Supplier</option>' +
      relatedData.suppliers.map(s => `<option value="${s.supplier_id}">${s.company_name}</option>`).join('');

    // Populate add modal dropdowns
    document.getElementById('addCategoryId').innerHTML = categoryOptions;
    document.getElementById('addUomId').innerHTML = uomOptions;
    document.getElementById('addSupplierId').innerHTML = supplierOptions;

    // Populate edit modal dropdowns
    document.getElementById('editCategoryId').innerHTML = categoryOptions;
    document.getElementById('editUomId').innerHTML = uomOptions;
    document.getElementById('editSupplierId').innerHTML = supplierOptions;

    // Populate filter dropdowns
    const filterCategoryOptions = '<option value="">All Categories</option>' +
      relatedData.categories.map(c => `<option value="${c.category_id}">${c.category_name}</option>`).join('');
    const filterSupplierOptions = '<option value="">All Suppliers</option>' +
      relatedData.suppliers.map(s => `<option value="${s.supplier_id}">${s.company_name}</option>`).join('');
    
    document.getElementById('filterCategory').innerHTML = filterCategoryOptions;
    document.getElementById('filterSupplier').innerHTML = filterSupplierOptions;
  }

  function getInitials(name) {
    return name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
  }

  function previewProductImage(input, previewId) {
    const previewContainer = document.getElementById(previewId);
    
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      
      reader.onload = function(e) {
        previewContainer.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        previewContainer.classList.add('has-image');
      };
      
      reader.readAsDataURL(input.files[0]);
    }
  }

  function resetProductImage(previewId) {
    const previewContainer = document.getElementById(previewId);
    previewContainer.innerHTML = `
      <div class="prod-image-placeholder">
        <i class="fas fa-camera"></i>
        <span>Click to upload image</span>
      </div>`;
    previewContainer.classList.remove('has-image');
  }

  function renderTable() {
    const tbody = document.getElementById('productsTableBody');
    const count = document.getElementById('tableCount');

    if (!filteredData.length) {
      count.textContent = '0 products';
      document.getElementById('paginationWrap').style.display = 'none';
      tbody.innerHTML = `
        <tr><td colspan="6">
          <div class="prod-empty-state">
            <div class="prod-empty-icon"><i class="fas fa-box-open"></i></div>
            <div class="prod-empty-text">No products found</div>
            <div class="prod-empty-sub"><a href="javascript:;" onclick="openAddProductModal()" style="color:var(--prod-blue);font-weight:500">Add your first product</a></div>
          </div>
        </td></tr>`;
      return;
    }

    // Calculate pagination
    const totalEntries = filteredData.length;
    const totalPages = Math.ceil(totalEntries / entriesPerPage);
    const start = (currentPage - 1) * entriesPerPage;
    const end = Math.min(start + entriesPerPage, totalEntries);
    const paginatedData = filteredData.slice(start, end);

    count.textContent = `${totalEntries} product${totalEntries !== 1 ? 's' : ''}`;

    // Show/hide pagination based on total entries
    const paginationWrap = document.getElementById('paginationWrap');
    if (totalEntries > 10) {
      paginationWrap.style.display = 'flex';
      renderPagination(totalPages, start, end, totalEntries);
    } else {
      paginationWrap.style.display = 'none';
    }

    tbody.innerHTML = paginatedData.map(p => {
      const isLow = parseFloat(p.stock_on_hand) <= parseFloat(p.low_stock_threshold);
      return `
      <tr class="product-row">
        <td>
          <div class="prod-name-cell">
            <div class="prod-avatar">${p.product_image_url ? `<img src="${normalizeProductImageUrl(p.product_image_url)}" alt="${p.product_name}" loading="lazy" onerror="this.parentElement.innerHTML='${getInitials(p.product_name)}'">` : getInitials(p.product_name)}</div>
            <div>
              <div class="prod-name-primary">${p.product_name}</div>
              <div class="prod-name-secondary">${p.product_code}</div>
            </div>
          </div>
        </td>
        <td>
          <div class="prod-name-primary">${p.category_name || '—'}</div>
          <div class="prod-name-secondary">${p.category_code || ''}</div>
        </td>
        <td>
          <div style="display:flex;flex-direction:column;gap:2px">
            <div style="display:flex;align-items:center;gap:6px">
              <span style="font-weight:500;font-size:13px">${parseFloat(p.stock_on_hand).toFixed(3)} ${p.uom_code || ''}</span>
              <button onclick="openQuickStockAdjust('${p.product_id}', '${p.product_name.replace(/'/g, "\\'")}', ${p.stock_on_hand})" style="background:var(--prod-blue-pale);color:var(--prod-blue);border:none;border-radius:4px;padding:2px 6px;font-size:10px;cursor:pointer;font-weight:500;min-width:24px">+/-</button>
            </div>
            <div style="display:flex;align-items:center;gap:6px">
              <span style="font-size:11px;color:var(--color-text-secondary)">Cur: ${parseFloat(p.current_stock).toFixed(3)}</span>
              ${isLow ? '<span class="prod-status-badge low" style="font-size:10px;padding:1px 6px">Low</span>' : ''}
            </div>
          </div>
        </td>
        <td>
          <span class="prod-price"><span class="prod-price-currency">PHP</span>${parseFloat(p.unit_cost).toFixed(2)}</span>
        </td>
        <td style="text-align:center">
          <span class="prod-status-badge active">Active</span>
        </td>
        <td>
          <div class="prod-actions-cell">
            <button onclick="viewProductDetails('${p.product_id}')" class="prod-action-btn view"><i class="fas fa-eye"></i></button>
            <button onclick="openEditProductModal('${p.product_id}')" class="prod-action-btn edit"><i class="fas fa-pen"></i></button>
            <button onclick="deleteProduct('${p.product_id}', '${p.product_name.replace(/'/g, "\\'")}')" class="prod-action-btn danger"><i class="fas fa-trash"></i></button>
          </div>
        </td>
      </tr>`;
    }).join('');
  }

  function filterTable() {
    const searchFilter = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('filterCategory').value;
    const supplierFilter = document.getElementById('filterSupplier').value;
    const typeFilter = document.getElementById('filterType').value;
    const statusFilter = document.getElementById('filterStatus').value;
    
    currentPage = 1; // Reset to first page when filtering

    // Show/hide clear filters button
    const hasActiveFilters = searchFilter || categoryFilter || supplierFilter || typeFilter || statusFilter;
    document.getElementById('clearFiltersBtn').style.display = hasActiveFilters ? 'flex' : 'none';

    filteredData = productsData.filter(p => {
      // Search filter (product name, code, category name)
      const matchesSearch = !searchFilter ||
        p.product_name.toLowerCase().includes(searchFilter) ||
        p.product_code.toLowerCase().includes(searchFilter) ||
        (p.category_name && p.category_name.toLowerCase().includes(searchFilter));

      // Category filter
      const matchesCategory = !categoryFilter || 
        p.category_id == categoryFilter;

      // Supplier filter
      const matchesSupplier = !supplierFilter || 
        p.preferred_supplier_id == supplierFilter;

      // Product type filter
      const matchesType = !typeFilter || 
        p.product_type === typeFilter;

      // Status filter
      let matchesStatus = true;
      if (statusFilter === 'for_sale') {
        matchesStatus = p.is_for_sale == 1;
      } else if (statusFilter === 'for_rooms') {
        matchesStatus = p.is_for_rooms == 1;
      } else if (statusFilter === 'low_stock') {
        const stockOnHand = parseFloat(p.stock_on_hand) || 0;
        const lowThreshold = parseFloat(p.low_stock_threshold) || 0;
        matchesStatus = stockOnHand <= lowThreshold;
      }

      return matchesSearch && matchesCategory && matchesSupplier && matchesType && matchesStatus;
    });

    renderTable();
  }

  function clearAllFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterCategory').value = '';
    document.getElementById('filterSupplier').value = '';
    document.getElementById('filterType').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('clearFiltersBtn').style.display = 'none';
    filterTable();
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

    // Previous button
    html += `<button class="prod-page-btn" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`;

    // Page numbers
    const maxButtons = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);

    if (endPage - startPage < maxButtons - 1) {
      startPage = Math.max(1, endPage - maxButtons + 1);
    }

    if (startPage > 1) {
      html += `<button class="prod-page-btn" onclick="goToPage(1)">1</button>`;
      if (startPage > 2) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
    }

    for (let i = startPage; i <= endPage; i++) {
      html += `<button class="prod-page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
    }

    if (endPage < totalPages) {
      if (endPage < totalPages - 1) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
      html += `<button class="prod-page-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
    }

    // Next button
    html += `<button class="prod-page-btn" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`;

    paginationControls.innerHTML = html;
  }

  async function viewProductDetails(productId) {
    const product = productsData.find(p => p.product_id === productId);
    if (!product) return;

    const modal = document.getElementById('productDetailsModal');
    document.getElementById('modalProductName').textContent = product.product_name;
    document.getElementById('modalProductCode').textContent = product.product_code;

    const isLow = parseFloat(product.stock_on_hand) <= parseFloat(product.low_stock_threshold);

    document.getElementById('modalProductContent').innerHTML = `
      ${product.product_image_url ? `
      <div class="prod-details-image-wrap">
        <img src="${normalizeProductImageUrl(product.product_image_url)}" alt="${product.product_name}" class="prod-details-image" onerror="this.style.display='none'">
      </div>` : ''}
      <div class="prod-details-grid">
        <div>
          <div class="prod-details-section-title">Basic Information</div>
          <div class="prod-detail-row"><div class="prod-detail-key">Product Code</div><div class="prod-detail-val">${product.product_code}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">Product Name</div><div class="prod-detail-val">${product.product_name}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">Category</div><div class="prod-detail-val">${product.category_name || '—'} ${product.category_code ? '('+product.category_code+')' : ''}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">Unit of Measure</div><div class="prod-detail-val">${product.uom_name || '—'} ${product.uom_code ? '('+product.uom_code+')' : ''}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">Product Type</div><div class="prod-detail-val">${product.product_type || '—'}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">Brand</div><div class="prod-detail-val">${product.brand || '—'}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">Description</div><div class="prod-detail-val">${product.description || '—'}</div></div>
        </div>
        <div>
          <div class="prod-details-section-title">Costing &amp; Stock</div>
          <div class="prod-detail-row"><div class="prod-detail-key">Unit Cost</div><div class="prod-detail-val">PHP ${parseFloat(product.unit_cost).toFixed(2)}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">Selling Price</div><div class="prod-detail-val">PHP ${parseFloat(product.selling_price).toFixed(2)}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">Stock on Hand</div><div class="prod-detail-val">${parseFloat(product.stock_on_hand).toFixed(3)} ${product.uom_code || ''} ${isLow ? '<span class="prod-status-badge low" style="margin-left:6px">Low</span>':''}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">Low Stock Threshold</div><div class="prod-detail-val">${parseFloat(product.low_stock_threshold).toFixed(3)}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">Min / Max Stock</div><div class="prod-detail-val">${parseFloat(product.minimum_stock).toFixed(3)} / ${product.maximum_stock ? parseFloat(product.maximum_stock).toFixed(3) : '—'}</div></div>
          <div class="prod-details-section-title" style="margin-top:20px">Supplier</div>
          <div class="prod-detail-row"><div class="prod-detail-key">Preferred Supplier</div><div class="prod-detail-val">${product.supplier_name || '—'}</div></div>
          <div class="prod-details-section-title" style="margin-top:20px">Settings</div>
          <div class="prod-detail-row"><div class="prod-detail-key">For Sale</div><div class="prod-detail-val">${product.is_for_sale ? '<span class="prod-status-badge active">Yes</span>' : 'No'}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">For Rooms</div><div class="prod-detail-val">${product.is_for_rooms ? '<span class="prod-status-badge active">Yes</span>' : 'No'}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">Track Inventory</div><div class="prod-detail-val">${product.track_inventory ? '<span class="prod-status-badge active">Yes</span>' : 'No'}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">Created At</div><div class="prod-detail-val">${new Date(product.created_at).toLocaleString()}</div></div>
        </div>
      </div>
      <div id="productStockByLocation" style="margin-top:20px">
        <div class="prod-details-section-title">Stock by Location</div>
        <div style="text-align:center;padding:16px;color:var(--color-text-secondary);font-size:13px"><i class="fas fa-spinner fa-spin"></i> Loading...</div>
      </div>`;

    modal.classList.add('open');

    // Fetch stock by location
    try {
      const res = await fetch(`../api/product_stock_per_location.php?action=list&product_id=${productId}`);
      const json = await res.json();
      const container = document.getElementById('productStockByLocation');
      if (json.success && json.data.length > 0) {
        const uomCode = product.uom_code || '';
        let html = `<div class="prod-details-section-title">Stock by Location</div>
          <table style="width:100%;border-collapse:collapse;font-size:13px;margin-top:8px">
            <thead>
              <tr style="background:var(--color-background-secondary,#F8FAFC)">
                <th style="padding:8px 12px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--color-text-secondary);border-bottom:1px solid rgba(0,0,0,.07)">Location</th>
                <th style="padding:8px 12px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--color-text-secondary);border-bottom:1px solid rgba(0,0,0,.07)">Property</th>
                <th style="padding:8px 12px;text-align:right;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--color-text-secondary);border-bottom:1px solid rgba(0,0,0,.07)">Quantity</th>
              </tr>
            </thead>
            <tbody>`;
        json.data.forEach(row => {
          const qty = parseFloat(row.quantity);
          const isLowLoc = qty <= parseFloat(product.reorder_point || 0);
          html += `<tr>
            <td style="padding:8px 12px;border-bottom:1px solid rgba(0,0,0,.04)">${row.location_name} <span style="font-size:11px;color:var(--color-text-secondary)">(${row.location_type || ''})</span></td>
            <td style="padding:8px 12px;border-bottom:1px solid rgba(0,0,0,.04)">${row.property_name}</td>
            <td style="padding:8px 12px;border-bottom:1px solid rgba(0,0,0,.04);text-align:right;font-family:var(--prod-mono,monospace);font-weight:600">
              ${qty.toFixed(3)} ${uomCode}
              ${isLowLoc ? '<span class="prod-status-badge low" style="margin-left:6px;font-size:10px">Low</span>' : ''}
            </td>
          </tr>`;
        });
        html += '</tbody></table>';
        container.innerHTML = html;
      } else {
        container.innerHTML = `<div class="prod-details-section-title">Stock by Location</div>
          <div style="padding:16px;color:var(--color-text-secondary);font-size:13px;text-align:center">No stock records found for any location.</div>`;
      }
    } catch (e) {
      const container = document.getElementById('productStockByLocation');
      if (container) container.innerHTML = '';
    }
  }

  function closeProductDetailsModal() {
    document.getElementById('productDetailsModal').classList.remove('open');
  }

  function handleProductDetailsOverlayClick(e) {
    if (e.target === document.getElementById('productDetailsModal')) closeProductDetailsModal();
  }

  function handleAddProductOverlayClick(e) {
    if (e.target === document.getElementById('addProductModal')) closeAddProductModal();
  }

  function openEditProductModal(productId) {
    const product = productsData.find(p => p.product_id === productId);
    if (!product) return;

    // Store original product data for comparison
    currentEditingProduct = {...product};

    // Populate edit form
    document.getElementById('editProductId').value = product.product_id;
    document.getElementById('editProductCode').value = product.product_code || '';
    document.getElementById('editProductName').value = product.product_name || '';
    document.getElementById('editCategoryId').value = product.category_id || '';
    document.getElementById('editUomId').value = product.uom_id || '';
    document.getElementById('editProductType').value = product.product_type || '';
    document.getElementById('editBrand').value = product.brand || '';
    document.getElementById('editDescription').value = product.description || '';
    document.getElementById('editUnitCost').value = product.unit_cost || '';
    document.getElementById('editSellingPrice').value = product.selling_price || '';
    document.getElementById('editReorderPoint').value = product.reorder_point || '';
    document.getElementById('editMinimumStock').value = product.minimum_stock || '';
    document.getElementById('editMaximumStock').value = product.maximum_stock || '';
    document.getElementById('editSupplierId').value = product.preferred_supplier_id || '';
    document.getElementById('editForSale').checked = product.is_for_sale == 1;
    document.getElementById('editForRooms').checked = product.is_for_rooms == 1;
    document.getElementById('editTrackInventory').checked = product.track_inventory == 1;
    document.getElementById('editProductImageUrl').value = product.product_image_url || '';

    // Populate pricing fields
    document.getElementById('editPricingMode').value = product.pricing_mode || 'fixed_price';
    document.getElementById('editMarkupValue').value = product.markup_value || '';
    onProductPricingModeChange('edit');

    // Fetch last PO cost for the product and UOM
    if (product.product_id && product.uom_id) {
      fetchProductLastPoCost(product.product_id, product.uom_id, 'edit');
    }

    // Set image preview
    const previewContainer = document.getElementById('editProductPreview');
    if (product.product_image_url) {
      previewContainer.innerHTML = `<img src="${normalizeProductImageUrl(product.product_image_url)}" alt="${product.product_name}">`;
      previewContainer.classList.add('has-image');
    } else {
      previewContainer.innerHTML = `
        <div class="prod-image-placeholder">
          <i class="fas fa-camera"></i>
          <span>Click to upload image</span>
        </div>`;
      previewContainer.classList.remove('has-image');
    }

    document.getElementById('editProductModal').classList.add('open');
  }

  function closeEditProductModal() {
    document.getElementById('editProductModal').classList.remove('open');
    document.getElementById('editProductForm').reset();
    currentEditingProduct = null;
  }

  function handleEditProductOverlayClick(e) {
    if (e.target === document.getElementById('editProductModal')) closeEditProductModal();
  }

  async function submitEditProduct(event) {
    event.preventDefault();
    const form = document.getElementById('editProductForm');
    const formData = new FormData(form);
    const imageFile = document.getElementById('editProductImage').files[0];

    if (!currentEditingProduct) {
      showProdAlert({ icon: 'error', title: 'Error', text: 'No product data found.' });
      return;
    }

    showProdAlert({
      title: 'Updating Product…',
      loading: true,
      loadingText: imageFile ? 'Uploading image...' : 'Updating product...'
    });

    try {
      let newImageUrl = null;
      let imageChanged = false;
      
      // Upload image if a new one was selected
      if (imageFile) {
        const imageFormData = new FormData();
        imageFormData.append('product_image', imageFile);
        imageFormData.append('csrf_token', CSRF_TOKEN);
        
        const uploadRes = await fetch('../api/upload-product-image.php', {
          method: 'POST',
          headers: {
            'X-CSRF-Token': CSRF_TOKEN
          },
          body: imageFormData
        });
        
        const uploadData = await uploadRes.json();
        if (!uploadData.success) {
          throw new Error(uploadData.message || 'Image upload failed');
        }
        newImageUrl = uploadData.image_url;
        imageChanged = true;
      }

      // Build payload with only changed fields
      const payload = { product_id: formData.get('product_id'), csrf_token: CSRF_TOKEN };

      const fields = [
        'product_code', 'product_name', 'category_id', 'uom_id', 'product_type',
        'brand', 'description', 'unit_cost', 'selling_price', 'pricing_mode',
        'markup_value', 'reorder_point', 'minimum_stock', 'maximum_stock',
        'preferred_supplier_id'
      ];

      fields.forEach(field => {
        let newValue = formData.get(field);
        let oldValue = currentEditingProduct[field] ?? '';
        
        // Normalize empty values for comparison
        if (newValue === '' || newValue === null) newValue = '';
        if (oldValue === '' || oldValue === null) oldValue = '';
        
        // Convert numeric fields for proper comparison
        if (['unit_cost', 'selling_price', 'reorder_point', 'minimum_stock', 'maximum_stock'].includes(field)) {
          newValue = newValue === '' ? '' : parseFloat(newValue);
          oldValue = oldValue === '' ? '' : parseFloat(oldValue);
        }
        
        if (newValue !== oldValue) {
          payload[field] = formData.get(field) || null;
        }
      });

      // Handle checkbox fields
      const checkboxFields = ['is_for_sale', 'is_for_rooms', 'track_inventory'];
      checkboxFields.forEach(field => {
        const newValue = formData.get(field) ? 1 : 0;
        const oldValue = parseInt(currentEditingProduct[field]) || 0;
        if (newValue !== oldValue) {
          payload[field] = newValue;
        }
      });
      
      // Handle image field if changed
      if (imageChanged) {
        payload.product_image_url = newImageUrl;
      }

      const res = await fetch('../api/products.php?action=update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      
      const data = await res.json();
      closeEditProductModal();
      closeProdAlert();
      
      if (data.success) {
        showProdAlert({
          icon: 'success',
          title: 'Product Updated!',
          text: data.message,
          confirmButtonColor: '#1A6BBF'
        }).then(() => fetchProducts());
      } else {
        showProdAlert({
          icon: 'error',
          title: 'Error',
          text: data.message,
          confirmButtonColor: '#C62828'
        });
      }
    } catch (err) {
      closeEditProductModal();
      closeProdAlert();
      showProdAlert({
        icon: 'error',
        title: 'Error',
        text: err.message || 'An error occurred.',
        confirmButtonColor: '#C62828'
      });
    }
  }

  function deleteProduct(productId, productName) {
    showProdAlert({
      title: 'Delete product?',
      html: `<span style="font-size:14px">You're about to delete <strong>${productName}</strong>. This action cannot be undone.</span>`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#C62828',
      cancelButtonColor: '#64748B',
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        showProdAlert({
          title: 'Deleting…',
          loading: true,
          loadingText: 'Deleting product...'
        });
        fetch('../api/products.php?action=delete', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              product_id: productId
            })
          })
          .then(r => r.json())
          .then(data => {
            closeProdAlert();
            if (data.success) {
              showProdAlert({
                icon: 'success',
                title: 'Deleted!',
                text: data.message,
                confirmButtonColor: '#1A6BBF'
              }).then(() => fetchProducts());
            } else {
              showProdAlert({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonColor: '#C62828'
              });
            }
          })
          .catch(() => {
            closeProdAlert();
            showProdAlert({
              icon: 'error',
              title: 'Error',
              text: 'An error occurred.',
              confirmButtonColor: '#C62828'
            });
          });
      }
    });
  }

  function openAddProductModal() {
    document.getElementById('addProductModal').classList.add('open');
  }

  function closeAddProductModal() {
    document.getElementById('addProductModal').classList.remove('open');
    document.getElementById('addProductForm').reset();
    resetProductImage('addProductPreview');
    // Reset pricing mode display
    onProductPricingModeChange('add');
  }

  function onProductPricingModeChange(prefix) {
    const mode = document.getElementById(`${prefix}PricingMode`).value;
    const markupFields = document.getElementById(`${prefix}MarkupFields`);
    const sellingPriceInput = document.getElementById(`${prefix}SellingPrice`);
    const markupLabel = document.getElementById(`${prefix}MarkupLabel`);
    const markupHint = document.getElementById(`${prefix}MarkupHint`);
    const calculatedDisplay = document.getElementById(`${prefix}CalculatedPriceDisplay`);
    const sellingPriceHint = document.getElementById(`${prefix}SellingPriceHint`);

    if (mode === 'fixed_price') {
      markupFields.style.display = 'none';
      calculatedDisplay.style.display = 'none';
      sellingPriceInput.readOnly = false;
      sellingPriceHint.textContent = 'Enter manual selling price';
    } else {
      markupFields.style.display = 'block';
      calculatedDisplay.style.display = 'block';
      sellingPriceInput.readOnly = true;
      sellingPriceHint.textContent = 'Auto-calculated based on markup';

      if (mode === 'percentage_markup') {
        markupLabel.textContent = 'Markup Percentage (%)';
        markupHint.textContent = 'Enter percentage to add to cost (e.g., 20 for 20%)';
      } else {
        markupLabel.textContent = 'Fixed Markup Amount (₱)';
        markupHint.textContent = 'Enter fixed amount to add to cost';
      }

      calculateProductSellingPrice(prefix);
    }
  }

  async function fetchProductLastPoCost(productId, uomId, prefix) {
    if (!productId || !uomId) return;

    try {
      const response = await fetch(`../api/product_uom_conversions.php?action=last_po_cost&product_id=${productId}&uom_id=${uomId}`);
      const result = await response.json();

      if (result.success && result.data) {
        document.getElementById(`${prefix}UnitCost`).value = result.data.unit_cost;
        calculateProductSellingPrice(prefix);
      }
    } catch (error) {
      console.error('Error fetching last PO cost:', error);
    }
  }

  async function fetchLastPoCostByCode(prefix) {
    const productCode = document.getElementById(`${prefix}ProductCode`).value;
    const uomId = document.getElementById(`${prefix}UomId`).value;

    if (!productCode || !uomId) {
      showToast('Please enter product code and select UOM first', 'error');
      return;
    }

    try {
      // First, find the product by code
      const productResponse = await fetch(`../api/products.php?action=list`);
      const productResult = await productResponse.json();

      if (productResult.success) {
        const product = productResult.data.find(p => p.product_code === productCode);
        if (product) {
          await fetchProductLastPoCost(product.product_id, uomId, prefix);
        } else {
          showToast('Product not found with this code', 'error');
        }
      }
    } catch (error) {
      console.error('Error fetching product:', error);
      showToast('Error fetching product information', 'error');
    }
  }

  function calculateProductSellingPrice(prefix) {
    const mode = document.getElementById(`${prefix}PricingMode`).value;
    const unitCost = parseFloat(document.getElementById(`${prefix}UnitCost`).value) || 0;
    const markupValue = parseFloat(document.getElementById(`${prefix}MarkupValue`).value) || 0;
    const sellingPriceInput = document.getElementById(`${prefix}SellingPrice`);

    let calculatedPrice = 0;

    if (mode === 'percentage_markup' && unitCost > 0) {
      calculatedPrice = unitCost * (1 + (markupValue / 100));
    } else if (mode === 'fixed_markup') {
      calculatedPrice = unitCost + markupValue;
    }

    if (calculatedPrice > 0) {
      sellingPriceInput.value = calculatedPrice.toFixed(2);
      document.getElementById(`${prefix}CalculatedPriceValue`).textContent = '₱' + calculatedPrice.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
  }

  function openQuickStockAdjust(productId, productName, currentStock) {
    document.getElementById('quickStockProductId').value = productId;
    document.getElementById('quickStockProductName').textContent = productName;
    document.getElementById('quickStockCurrent').value = currentStock;
    document.getElementById('quickStockQuantity').value = '';
    document.getElementById('quickStockReason').value = '';
    document.getElementById('quickStockModal').classList.add('open');
  }

  function closeQuickStockModal() {
    document.getElementById('quickStockModal').classList.remove('open');
    document.getElementById('quickStockForm').reset();
  }

  function handleQuickStockOverlayClick(e) {
    if (e.target === document.getElementById('quickStockModal')) closeQuickStockModal();
  }

  function submitQuickStockAdjust(event) {
    event.preventDefault();
    const form = document.getElementById('quickStockForm');
    const payload = {
      product_id: document.getElementById('quickStockProductId').value,
      transaction_type: document.getElementById('quickStockType').value,
      quantity: parseFloat(document.getElementById('quickStockQuantity').value),
      reason: document.getElementById('quickStockReason').value || 'Quick adjustment',
      csrf_token: CSRF_TOKEN
    };

    fetch('../api/inventory_transactions.php?action=adjust', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
      closeQuickStockModal();
      if (data.success) {
        showToast(data.message, 'success');
        fetchProducts();
      } else {
        showToast(data.message, 'error');
      }
    })
    .catch(() => {
      closeQuickStockModal();
      showToast('An error occurred while adjusting stock.', 'error');
    });
  }

  async function submitAddProduct(event) {
    event.preventDefault();
    const form = document.getElementById('addProductForm');
    const formData = new FormData(form);
    const imageFile = document.getElementById('addProductImage').files[0];

    showProdAlert({
      title: 'Adding Product…',
      loading: true,
      loadingText: imageFile ? 'Uploading image...' : 'Adding product...'
    });

    try {
      let imageUrl = null;
      
      // Upload image if selected
      if (imageFile) {
        const imageFormData = new FormData();
        imageFormData.append('product_image', imageFile);
        imageFormData.append('csrf_token', CSRF_TOKEN);
        
        const uploadRes = await fetch('../api/upload-product-image.php', {
          method: 'POST',
          headers: {
            'X-CSRF-Token': CSRF_TOKEN
          },
          body: imageFormData
        });
        
        const uploadData = await uploadRes.json();
        if (!uploadData.success) {
          throw new Error(uploadData.message || 'Image upload failed');
        }
        imageUrl = uploadData.image_url;
      }

      const payload = {
        product_code: formData.get('product_code'),
        product_name: formData.get('product_name'),
        category_id: formData.get('category_id'),
        uom_id: formData.get('uom_id'),
        product_type: formData.get('product_type'),
        brand: formData.get('brand'),
        description: formData.get('description'),
        unit_cost: formData.get('unit_cost'),
        selling_price: formData.get('selling_price'),
        pricing_mode: formData.get('pricing_mode'),
        markup_value: formData.get('markup_value'),
        reorder_point: formData.get('reorder_point'),
        minimum_stock: formData.get('minimum_stock'),
        maximum_stock: formData.get('maximum_stock'),
        preferred_supplier_id: formData.get('preferred_supplier_id'),
        is_for_sale: formData.get('is_for_sale') ? 1 : 0,
        is_for_rooms: formData.get('is_for_rooms') ? 1 : 0,
        track_inventory: formData.get('track_inventory') ? 1 : 0,
        product_image_url: imageUrl
      };

      const res = await fetch('../api/products.php?action=create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      
      const data = await res.json();
      closeAddProductModal();
      closeProdAlert();
      
      if (data.success) {
        showProdAlert({
          icon: 'success',
          title: 'Product Added!',
          text: data.message,
          confirmButtonColor: '#1A6BBF'
        }).then(() => fetchProducts());
      } else {
        showProdAlert({
          icon: 'error',
          title: 'Error',
          text: data.message,
          confirmButtonColor: '#C62828'
        });
      }
    } catch (err) {
      closeAddProductModal();
      closeProdAlert();
      showProdAlert({
        icon: 'error',
        title: 'Error',
        text: err.message || 'An error occurred.',
        confirmButtonColor: '#C62828'
      });
    }
  }
</script>