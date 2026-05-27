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
    padding: 28px 28px 40px;
    max-width: 100%;
    margin: 0 auto;
    background: var(--color-background-tertiary, #F4F5F7);
    min-height: 100%;
    border-radius: var(--border-radius-xl, 18px);
    font-family: var(--prod-font);
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
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
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
    font-size: 32px;
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
    gap: 12px;
    flex-wrap: wrap;
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
    gap: 8px;
    flex-wrap: wrap;
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
    width: 230px;
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

  /* Responsive */
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
      width: 160px;
    }
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
        <div class="prod-entries-wrap">
          <span class="prod-entries-label">Show</span>
          <select class="prod-entries-select" id="entriesPerPage" onchange="changeEntriesPerPage()">
            <option value="10">10</option>
            <option value="25" selected>25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="prod-entries-label">entries</span>
        </div>
        <div class="prod-search-wrap">
          <i class="fas fa-search" aria-hidden="true"></i>
          <input type="text" class="prod-search-input" id="searchInput" placeholder="Search products…" oninput="filterTable()">
        </div>
        <button class="prod-btn prod-btn-primary" onclick="openAddProductModal()">
          <i class="fas fa-plus" aria-hidden="true"></i> Add Product
        </button>
      </div>
    </div>
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

          <hr class="prod-divider">
          <div class="prod-section-label"><i class="fas fa-peso-sign" style="margin-right:5px;opacity:.6"></i>Costing</div>

          <div class="prod-form-group">
            <label class="prod-form-label">Unit Cost (PHP)</label>
            <input type="number" name="unit_cost" step="0.01" min="0" value="0" class="prod-form-input" placeholder="0.00">
          </div>
          <div class="prod-form-group">
            <label class="prod-form-label">Selling Price (PHP)</label>
            <input type="number" name="selling_price" step="0.01" min="0" value="0" class="prod-form-input" placeholder="0.00">
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

  document.addEventListener('DOMContentLoaded', function() {
    ['productDetailsModal', 'addProductModal'].forEach(function(id) {
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
    document.getElementById('statLowStock').textContent = productsData.filter(p => parseFloat(p.current_stock) <= parseFloat(p.reorder_point)).length;
  }

  function populateDropdowns() {
    document.getElementById('addCategoryId').innerHTML = '<option value="">Select Category</option>' +
      relatedData.categories.map(c => `<option value="${c.category_id}">${c.category_name} (${c.category_code})</option>`).join('');
    document.getElementById('addUomId').innerHTML = '<option value="">Select UOM</option>' +
      relatedData.uoms.map(u => `<option value="${u.uom_id}">${u.uom_name} (${u.uom_code})</option>`).join('');
    document.getElementById('addSupplierId').innerHTML = '<option value="">No Supplier</option>' +
      relatedData.suppliers.map(s => `<option value="${s.supplier_id}">${s.company_name}</option>`).join('');
  }

  function getInitials(name) {
    return name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
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
      const isLow = parseFloat(p.current_stock) <= parseFloat(p.reorder_point);
      return `
      <tr class="product-row">
        <td>
          <div class="prod-name-cell">
            <div class="prod-avatar">${getInitials(p.product_name)}</div>
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
          <span class="prod-stock-val">${parseFloat(p.current_stock).toFixed(3)}</span>
          <span class="prod-stock-uom">${p.uom_code || ''}</span>
          ${isLow ? '<br><span class="prod-status-badge low" style="margin-top:4px">Low Stock</span>' : ''}
        </td>
        <td>
          <span class="prod-price"><span class="prod-price-currency">PHP</span>${parseFloat(p.unit_cost).toFixed(2)}</span>
        </td>
        <td style="text-align:center">
          <span class="prod-status-badge active">Active</span>
        </td>
        <td>
          <div class="prod-actions-cell">
            <button onclick="viewProductDetails('${p.product_id}')" class="prod-action-btn view"><i class="fas fa-eye"></i> View</button>
            <button onclick="openEditProductModal('${p.product_id}')" class="prod-action-btn edit"><i class="fas fa-pen"></i> Edit</button>
            <button onclick="deleteProduct('${p.product_id}', '${p.product_name.replace(/'/g, "\\'")}')" class="prod-action-btn danger"><i class="fas fa-trash"></i></button>
          </div>
        </td>
      </tr>`;
    }).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toLowerCase();
    currentPage = 1; // Reset to first page when filtering

    if (!filter) {
      filteredData = [...productsData];
    } else {
      filteredData = productsData.filter(p =>
        p.product_name.toLowerCase().includes(filter) ||
        p.product_code.toLowerCase().includes(filter) ||
        (p.category_name && p.category_name.toLowerCase().includes(filter))
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

  function viewProductDetails(productId) {
    const product = productsData.find(p => p.product_id === productId);
    if (!product) return;

    const modal = document.getElementById('productDetailsModal');
    document.getElementById('modalProductName').textContent = product.product_name;
    document.getElementById('modalProductCode').textContent = product.product_code;

    const isLow = parseFloat(product.current_stock) <= parseFloat(product.reorder_point);

    document.getElementById('modalProductContent').innerHTML = `
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
          <div class="prod-detail-row"><div class="prod-detail-key">Current Stock</div><div class="prod-detail-val">${parseFloat(product.current_stock).toFixed(3)} ${product.uom_code || ''} ${isLow ? '<span class="prod-status-badge low" style="margin-left:6px">Low</span>':''}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">Reorder Point</div><div class="prod-detail-val">${parseFloat(product.reorder_point).toFixed(3)}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">Min / Max Stock</div><div class="prod-detail-val">${parseFloat(product.minimum_stock).toFixed(3)} / ${product.maximum_stock ? parseFloat(product.maximum_stock).toFixed(3) : '—'}</div></div>
          <div class="prod-details-section-title" style="margin-top:20px">Supplier</div>
          <div class="prod-detail-row"><div class="prod-detail-key">Preferred Supplier</div><div class="prod-detail-val">${product.supplier_name || '—'}</div></div>
          <div class="prod-details-section-title" style="margin-top:20px">Settings</div>
          <div class="prod-detail-row"><div class="prod-detail-key">For Sale</div><div class="prod-detail-val">${product.is_for_sale ? '<span class="prod-status-badge active">Yes</span>' : 'No'}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">For Rooms</div><div class="prod-detail-val">${product.is_for_rooms ? '<span class="prod-status-badge active">Yes</span>' : 'No'}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">Track Inventory</div><div class="prod-detail-val">${product.track_inventory ? '<span class="prod-status-badge active">Yes</span>' : 'No'}</div></div>
          <div class="prod-detail-row"><div class="prod-detail-key">Created At</div><div class="prod-detail-val">${new Date(product.created_at).toLocaleString()}</div></div>
        </div>
      </div>`;

    modal.classList.add('open');
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
    Swal.fire({
      icon: 'info',
      title: 'Coming Soon',
      text: 'Edit product functionality will be implemented soon.',
      confirmButtonColor: '#1A6BBF'
    });
  }

  function deleteProduct(productId, productName) {
    Swal.fire({
      title: 'Delete product?',
      html: `<span style="font-size:14px">You're about to delete <strong>${productName}</strong>. This action cannot be undone.</span>`,
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
          title: 'Deleting…',
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading(),
          customClass: {
            popup: 'swal2-popup-high-z'
          }
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
            if (data.success) {
              Swal.fire({
                  icon: 'success',
                  title: 'Deleted!',
                  text: data.message,
                  confirmButtonColor: '#1A6BBF'
                })
                .then(() => fetchProducts());
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

  function openAddProductModal() {
    document.getElementById('addProductModal').classList.add('open');
  }

  function closeAddProductModal() {
    document.getElementById('addProductModal').classList.remove('open');
    document.getElementById('addProductForm').reset();
  }

  function submitAddProduct(event) {
    event.preventDefault();
    const form = document.getElementById('addProductForm');
    const formData = new FormData(form);

    Swal.fire({
      title: 'Adding Product…',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

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
      reorder_point: formData.get('reorder_point'),
      minimum_stock: formData.get('minimum_stock'),
      maximum_stock: formData.get('maximum_stock'),
      preferred_supplier_id: formData.get('preferred_supplier_id'),
      is_for_sale: formData.get('is_for_sale') ? 1 : 0,
      is_for_rooms: formData.get('is_for_rooms') ? 1 : 0,
      track_inventory: formData.get('track_inventory') ? 1 : 0
    };

    fetch('../api/products.php?action=create', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddProductModal();
        if (data.success) {
          Swal.fire({
              icon: 'success',
              title: 'Product Added!',
              text: data.message,
              confirmButtonColor: '#1A6BBF'
            })
            .then(() => fetchProducts());
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
        closeAddProductModal();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred.',
          confirmButtonColor: '#C62828'
        });
      });
  }
</script>