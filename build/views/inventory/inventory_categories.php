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

  .cat-page {
    padding: 28px 28px 40px;
    max-width: 100%;
    margin: 0 auto;
    background: var(--color-background-tertiary, #F4F5F7);
    min-height: 100%;
    border-radius: var(--border-radius-xl, 18px);
    font-family: var(--prod-font);
  }

  .cat-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
    gap: 16px;
    flex-wrap: wrap;
  }

  .cat-page-eyebrow {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--prod-blue);
    margin-bottom: 4px;
  }

  .cat-page-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.4px;
    line-height: 1.2;
  }

  .cat-page-subtitle {
    font-size: 14px;
    color: var(--color-text-secondary);
    margin-top: 3px;
    font-weight: 400;
  }

  .cat-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
  }

  .cat-stat-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    padding: 20px 22px;
    box-shadow: var(--prod-shadow-sm);
    position: relative;
    overflow: hidden;
    transition: box-shadow .2s, transform .2s;
  }

  .cat-stat-card:hover {
    box-shadow: var(--prod-shadow-md);
    transform: translateY(-1px);
  }

  .cat-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--prod-radius-lg) var(--prod-radius-lg) 0 0;
    background: var(--_accent, #CBD5E1);
  }

  .cat-stat-card.blue {
    --_accent: var(--prod-blue);
  }

  .cat-stat-card.purple {
    --_accent: var(--prod-purple);
  }

  .cat-stat-card.green {
    --_accent: var(--prod-green);
  }

  .cat-stat-icon {
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

  .cat-stat-card.blue .cat-stat-icon {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
  }

  .cat-stat-card.purple .cat-stat-icon {
    background: var(--prod-purple-pale);
    color: var(--prod-purple);
  }

  .cat-stat-card.green .cat-stat-icon {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .cat-stat-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-secondary);
    letter-spacing: .02em;
    margin-bottom: 6px;
  }

  .cat-stat-value {
    font-size: 32px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.5px;
    line-height: 1;
    font-family: var(--prod-mono);
  }

  .cat-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    box-shadow: var(--prod-shadow-sm);
    overflow: hidden;
  }

  .cat-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    background: var(--color-background-primary, #fff);
  }

  .cat-card-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.2px;
  }

  .cat-card-count {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-top: 1px;
  }

  .cat-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .cat-search-wrap {
    position: relative;
  }

  .cat-search-wrap i {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-text-secondary);
    font-size: 12px;
    pointer-events: none;
  }

  .cat-search {
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

  .cat-search:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    background: var(--color-background-primary, #fff);
  }

  .cat-btn {
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

  .cat-btn:hover {
    background: var(--color-background-secondary, #F8FAFC);
    border-color: rgba(0, 0, 0, .18);
  }

  .cat-btn-primary {
    background: var(--prod-blue);
    color: #fff;
    border-color: var(--prod-blue);
    box-shadow: 0 1px 3px rgba(26, 107, 191, .3);
  }

  .cat-btn-primary:hover {
    background: var(--prod-blue-dark);
    border-color: var(--prod-blue-dark);
    box-shadow: 0 2px 6px rgba(26, 107, 191, .35);
  }

  .cat-table-wrap {
    overflow-x: auto;
  }

  .cat-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    font-family: var(--prod-font);
  }

  .cat-table thead tr {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .cat-table thead th {
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

  .cat-table thead th.center {
    text-align: center;
  }

  .cat-table thead th.right {
    text-align: right;
  }

  .cat-table tbody tr {
    border-bottom: 1px solid rgba(0, 0, 0, .05);
    transition: background .12s;
  }

  .cat-table tbody tr:last-child {
    border-bottom: none;
  }

  .cat-table tbody tr:hover {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .cat-table td {
    padding: 13px 18px;
    vertical-align: middle;
    white-space: nowrap;
  }

  .cat-cell {
    display: flex;
    align-items: center;
    gap: 13px;
  }

  .cat-avatar {
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

  .cat-name-cell {
    display: flex;
    align-items: center;
  }

  .cat-name-primary {
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-primary);
    letter-spacing: -.1px;
  }

  .cat-name-secondary {
    font-size: 11px;
    color: var(--color-text-secondary);
    margin-top: 2px;
    font-family: var(--prod-mono);
    font-weight: 400;
  }

  .cat-desc {
    font-size: 13px;
    color: var(--color-text-secondary);
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .cat-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .02em;
  }

  .cat-badge.parent {
    background: var(--prod-purple-pale);
    color: var(--prod-purple);
  }

  .cat-badge.sub {
    background: var(--color-background-secondary, #F1F5F9);
    color: var(--color-text-secondary);
  }

  .cat-badge.active {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .cat-sort-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: var(--prod-radius-sm);
    background: var(--color-background-secondary);
    border: 1px solid rgba(0, 0, 0, .08);
    font-size: 12px;
    font-weight: 700;
    color: var(--color-text-secondary);
  }

  .cat-actions-cell {
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .cat-action-btn {
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
    font-family: var(--prod-font);
    letter-spacing: -.05em;
  }

  .cat-action-btn:hover {
    background: var(--color-background-secondary);
    color: var(--color-text-primary);
    border-color: rgba(0, 0, 0, .15);
  }

  .cat-action-btn.view:hover {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
    border-color: rgba(26, 107, 191, .2);
  }

  .cat-action-btn.edit:hover {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
    border-color: rgba(230, 81, 0, .2);
  }

  .cat-action-btn.danger:hover {
    background: var(--prod-red-pale);
    color: var(--prod-red);
    border-color: rgba(198, 40, 40, .2);
  }

  .cat-empty-state {
    padding: 64px 24px;
    text-align: center;
    color: var(--color-text-secondary);
  }

  .cat-empty-icon {
    font-size: 32px;
    opacity: .25;
    margin-bottom: 12px;
  }

  .cat-empty-text {
    font-size: 14px;
    font-weight: 500;
    color: var(--color-text-secondary);
    margin-bottom: 4px;
  }

  .cat-empty-sub {
    font-size: 12px;
    color: var(--color-text-secondary);
    opacity: .7;
  }

  .cat-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, .5);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 9000;
    align-items: flex-start;
    justify-content: center;
    padding: 40px 24px 24px;
  }

  .cat-overlay.open {
    display: flex;
  }

  .cat-modal {
    background: var(--color-background-primary, #fff);
    border-radius: var(--prod-radius-xl);
    border: 1px solid rgba(0, 0, 0, .08);
    width: 100%;
    max-width: 700px;
    max-height: 90vh;
    overflow-y: auto;
    overflow-x: hidden;
    box-shadow: var(--prod-shadow-lg);
    animation: modalIn .25s cubic-bezier(.22, .68, 0, 1.2);
    margin: auto;
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

  .cat-modal-header {
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

  .cat-modal-title {
    font-size: 17px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.3px;
  }

  .cat-modal-subtitle {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-top: 2px;
  }

  .cat-modal-close {
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

  .cat-modal-close:hover {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .cat-modal-body {
    padding: 26px;
  }

  .cat-modal-footer {
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
  .cat-alert-overlay {
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

  .cat-alert-overlay.open {
    display: flex;
  }

  .cat-alert {
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

  .cat-alert-header {
    padding: 22px 26px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .cat-alert-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }

  .cat-alert-icon.success { background: var(--prod-green-pale); color: var(--prod-green); }
  .cat-alert-icon.error { background: var(--prod-red-pale); color: var(--prod-red); }
  .cat-alert-icon.warning { background: var(--prod-amber-pale); color: var(--prod-amber); }
  .cat-alert-icon.info { background: var(--prod-blue-pale); color: var(--prod-blue); }

  .cat-alert-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.3px;
  }

  .cat-alert-body {
    padding: 0 26px 20px;
  }

  .cat-alert-message {
    font-size: 14px;
    color: var(--color-text-secondary);
    line-height: 1.5;
  }

  .cat-alert-footer {
    padding: 0 26px 22px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    flex-wrap: wrap;
  }

  .cat-alert-btn {
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

  .cat-alert-btn:hover {
    background: var(--color-background-secondary, #F8FAFC);
    border-color: rgba(0, 0, 0, .18);
  }

  .cat-alert-btn.primary {
    background: var(--prod-blue);
    color: #fff;
    border-color: var(--prod-blue);
  }

  .cat-alert-btn.primary:hover {
    background: var(--prod-blue-dark);
    border-color: var(--prod-blue-dark);
  }

  .cat-alert-btn.danger {
    background: var(--prod-red);
    color: #fff;
    border-color: var(--prod-red);
  }

  .cat-alert-btn.danger:hover {
    background: #a02222;
    border-color: #a02222;
  }

  .cat-alert-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 26px;
    gap: 12px;
  }

  .cat-alert-loading i {
    font-size: 24px;
    color: var(--prod-blue);
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }

  .cat-alert-loading-text {
    font-size: 14px;
    color: var(--color-text-secondary);
  }

  .cat-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
  }

  .cat-form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .cat-form-group.full {
    grid-column: 1 / -1;
  }

  .cat-form-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .07em;
  }

  .cat-form-label .req {
    color: var(--prod-red);
    margin-left: 2px;
  }

  .cat-form-input,
  .cat-form-select,
  .cat-form-textarea {
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

  .cat-form-input:focus,
  .cat-form-select:focus,
  .cat-form-textarea:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    background: var(--color-background-primary, #fff);
  }

  .cat-form-textarea {
    resize: vertical;
    min-height: 76px;
    line-height: 1.5;
  }

  .cat-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 28px;
  }

  .cat-details-section-title {
    font-size: 11px;
    font-weight: 700;
    color: var(--prod-blue);
    text-transform: uppercase;
    letter-spacing: .1em;
    margin-bottom: 14px;
    padding-bottom: 8px;
    border-bottom: 2px solid var(--prod-blue-pale);
  }

  .cat-detail-row {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 12px;
    padding: 7px 0;
    border-bottom: 1px solid rgba(0, 0, 0, .04);
  }

  .cat-detail-row:last-child {
    border-bottom: none;
  }

  .cat-detail-key {
    font-size: 12px;
    color: var(--color-text-secondary);
    flex-shrink: 0;
    font-weight: 500;
  }

  .cat-detail-val {
    font-size: 13px;
    color: var(--color-text-primary);
    font-weight: 500;
    text-align: right;
  }

  /* Pagination */
  .cat-pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    background: var(--color-background-primary, #fff);
    gap: 16px;
    flex-wrap: wrap;
  }

  .cat-pagination-info {
    font-size: 12px;
    color: var(--color-text-secondary);
  }

  .cat-pagination {
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .cat-page-btn {
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

  .cat-page-btn:hover:not(:disabled) {
    background: var(--prod-blue-pale);
    border-color: var(--prod-blue);
    color: var(--prod-blue);
  }

  .cat-page-btn.active {
    background: var(--prod-blue);
    border-color: var(--prod-blue);
    color: #fff;
  }

  .cat-page-btn:disabled {
    opacity: .4;
    cursor: not-allowed;
  }

  .cat-entries-select {
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

  .cat-entries-select:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    outline: none;
  }

  .cat-entries-label {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-right: 8px;
  }

  .cat-entries-wrap {
    display: flex;
    align-items: center;
  }

  /* ── Responsive ───────────────────────────────────────── */
  @media (max-width: 1400px) {
    .cat-page { padding: 24px 24px 36px; }
    .cat-search { width: 200px; }
  }

  @media (max-width: 1200px) {
    .cat-page { padding: 20px 20px 32px; }
    .cat-page-header { flex-direction: column; align-items: flex-start; }
    .cat-toolbar { width: 100%; flex-wrap: wrap; }
    .cat-search { width: 180px; }
    .cat-btn { padding: 7px 14px; font-size: 12px; }
  }

  @media (max-width: 1024px) {
    .cat-page { padding: 18px 18px 28px; }
    .cat-stats-grid { grid-template-columns: repeat(2, 1fr); }
    .cat-card-header { flex-direction: column; align-items: flex-start; gap: 10px; }
    .cat-toolbar { width: 100%; flex-wrap: wrap; gap: 6px; }
    .cat-search { width: 100%; }
    .cat-table th:nth-child(3),
    .cat-table td:nth-child(3) { display: none; }
  }

  @media (max-width: 992px) {
    .cat-page { padding: 16px 16px 24px; }
    .cat-page-title { font-size: 20px; }
    .cat-stat-value { font-size: 28px; }
    .cat-card-header { padding: 14px 18px; }
    .cat-table td { padding: 10px 12px; }
    .cat-table th { padding: 10px 12px; font-size: 11px; }
    .cat-name-primary { font-size: 12px; }
    .cat-name-secondary { font-size: 10px; }
    .cat-action-btn { width: 30px; height: 30px; font-size: 11px; }
    .cat-overlay { padding: 32px 16px 16px; }
    .cat-modal { max-width: 94vw; max-height: 88vh; }
    .cat-modal-header { padding: 18px 22px 16px; }
    .cat-modal-body { padding: 20px 22px; }
    .cat-modal-footer { padding: 14px 22px; }
  }

  @media (max-width: 768px) {
    .cat-page { padding: 14px 14px 20px; }
    .cat-page-header { gap: 12px; margin-bottom: 20px; }
    .cat-page-title { font-size: 18px; }
    .cat-page-subtitle { font-size: 12px; }
    .cat-stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .cat-stat-card { padding: 16px 18px; }
    .cat-stat-icon { width: 32px; height: 32px; font-size: 14px; }
    .cat-stat-value { font-size: 24px; }
    .cat-card-header { padding: 12px 16px; }
    .cat-toolbar { gap: 6px; }
    .cat-table th:nth-child(2),
    .cat-table td:nth-child(2) { display: none; }
    .cat-table td { padding: 8px 10px; }
    .cat-table th { padding: 8px 10px; font-size: 10px; }
    .cat-table th.right,
    .cat-table td:last-child { text-align: right; }
    .cat-name-primary { font-size: 11px; }
    .cat-name-secondary { font-size: 9px; }
    .cat-avatar { width: 32px; height: 32px; font-size: 11px; }
    .cat-action-btn { width: 28px; height: 28px; font-size: 10px; }
    /* Modal adjustments */
    .cat-overlay { padding: 24px 12px 12px; align-items: flex-start; }
    .cat-modal { max-width: 100%; max-height: 85vh; }
    .cat-modal-header { padding: 18px 20px 14px; }
    .cat-modal-footer { padding: 14px 20px; flex-wrap: wrap; }
    .cat-modal-body { padding: 20px; }
    .cat-modal-title { font-size: 16px; }
    .cat-modal-subtitle { font-size: 11px; }
    .cat-modal-close { width: 32px; height: 32px; font-size: 14px; }
    .cat-form-grid { gap: 16px; }
    .cat-form-label { font-size: 10px; }
    .cat-form-input, .cat-form-select, .cat-form-textarea { font-size: 14px; padding: 10px 12px; }
  }

  @media (max-width: 640px) {
    .cat-card-header { flex-direction: column; align-items: stretch; }
    .cat-toolbar { flex-direction: column; align-items: stretch; }
    .cat-search, .cat-btn { width: 100%; }
    .cat-stats-grid { grid-template-columns: 1fr; }
  }

  @media (max-width: 600px) {

    .cat-details-grid,
    .cat-form-grid {
      grid-template-columns: 1fr;
    }

    .cat-form-group.full {
      grid-column: 1;
    }

    .cat-search {
      width: 100%;
    }

    .cat-page { padding: 12px 12px 18px; }
    .cat-page-title { font-size: 16px; }
    .cat-page-subtitle { font-size: 11px; }
    .cat-stat-card { padding: 14px 16px; }
    .cat-stat-icon { width: 30px; height: 30px; font-size: 13px; }
    .cat-stat-value { font-size: 22px; }
    .cat-card-header { padding: 10px 14px; }
    .cat-card-title { font-size: 13px; }
    .cat-table th:nth-child(3),
    .cat-table td:nth-child(3) { display: none; }
    /* Modal adjustments */
    .cat-overlay { padding: 20px 10px 10px; align-items: flex-start; }
    .cat-modal { max-width: 100%; max-height: 88vh; }
    .cat-modal-header { padding: 16px 18px 12px; }
    .cat-modal-body { padding: 18px; }
    .cat-modal-footer { padding: 12px 18px; flex-direction: column; align-items: stretch; }
    .cat-modal-footer .cat-btn { width: 100%; justify-content: center; }
    .cat-modal-title { font-size: 15px; }
    .cat-modal-subtitle { font-size: 10px; }
    .cat-modal-close { width: 30px; height: 30px; font-size: 13px; }
    .cat-form-grid { gap: 14px; }
    .cat-form-input, .cat-form-select, .cat-form-textarea { font-size: 14px; padding: 9px 12px; }
    .cat-form-label { font-size: 10px; }
    /* Alert dialog adjustments */
    .cat-alert { max-width: 90vw; }
    .cat-alert-header { padding: 18px 20px 14px; }
    .cat-alert-body { padding: 0 20px 16px; }
    .cat-alert-footer { padding: 0 20px 18px; }
    .cat-alert-icon { width: 38px; height: 38px; font-size: 18px; }
    .cat-alert-title { font-size: 16px; }
    .cat-alert-message { font-size: 13px; }
  }

  @media (max-width: 480px) {
    .cat-page { padding: 10px 10px 16px; }
    .cat-page-title { font-size: 15px; }
    .cat-page-subtitle { font-size: 10px; }
    .cat-stat-value { font-size: 20px; }
    /* Modal adjustments */
    .cat-overlay { padding: 16px 8px 8px; align-items: flex-start; }
    .cat-modal { max-width: 100%; max-height: 90vh; }
    .cat-modal-header { padding: 14px 16px 10px; }
    .cat-modal-body { padding: 16px; }
    .cat-modal-footer { padding: 10px 16px; }
    .cat-modal-title { font-size: 14px; }
    .cat-modal-subtitle { font-size: 10px; }
    .cat-modal-close { width: 28px; height: 28px; font-size: 12px; }
    .cat-form-grid { gap: 12px; }
    .cat-form-input, .cat-form-select, .cat-form-textarea { font-size: 13px; padding: 8px 11px; }
    .cat-form-label { font-size: 9px; }
    .cat-btn { padding: 8px 12px; font-size: 11px; }
    /* Alert dialog adjustments */
    .cat-alert { max-width: 92vw; }
    .cat-alert-header { padding: 16px 18px 12px; }
    .cat-alert-body { padding: 0 18px 14px; }
    .cat-alert-footer { padding: 0 18px 16px; }
    .cat-alert-icon { width: 36px; height: 36px; font-size: 16px; }
    .cat-alert-title { font-size: 15px; }
    .cat-alert-message { font-size: 12px; }
    .cat-alert-btn { padding: 8px 14px; font-size: 12px; }
  }

  @media (max-width: 360px) {
    .cat-page { padding: 8px 8px 14px; }
    .cat-page-title { font-size: 14px; }
    .cat-page-subtitle { font-size: 9px; }
    .cat-stat-card { padding: 12px 14px; }
    .cat-stat-value { font-size: 18px; }
    /* Modal adjustments */
    .cat-overlay { padding: 12px 6px 6px; align-items: flex-start; }
    .cat-modal { max-width: 100%; max-height: 92vh; }
    .cat-modal-header { padding: 12px 14px 8px; }
    .cat-modal-body { padding: 14px; }
    .cat-modal-footer { padding: 8px 14px; }
    .cat-modal-title { font-size: 13px; }
    .cat-modal-subtitle { font-size: 9px; }
    .cat-modal-close { width: 26px; height: 26px; font-size: 11px; }
    .cat-form-grid { gap: 10px; }
    .cat-form-input, .cat-form-select, .cat-form-textarea { font-size: 12px; padding: 7px 10px; }
    .cat-form-label { font-size: 9px; }
    .cat-btn { padding: 7px 10px; font-size: 10px; }
    /* Alert dialog adjustments */
    .cat-alert { max-width: 94vw; }
    .cat-alert-header { padding: 14px 16px 10px; }
    .cat-alert-body { padding: 0 16px 12px; }
    .cat-alert-footer { padding: 0 16px 14px; }
    .cat-alert-icon { width: 34px; height: 34px; font-size: 15px; }
    .cat-alert-title { font-size: 14px; }
    .cat-alert-message { font-size: 11px; }
    .cat-alert-btn { padding: 7px 12px; font-size: 11px; }
  }

  @media (max-width: 768px) and (orientation: landscape) {
    .cat-overlay { padding: 8px; align-items: center; }
    .cat-modal { border-radius: var(--prod-radius-xl); max-height: 90vh; }
    .cat-modal-header { border-radius: var(--prod-radius-xl) var(--prod-radius-xl) 0 0; }
    .cat-modal-body { padding: 14px 18px; }
    .cat-form-grid { grid-template-columns: 1fr 1fr; }
    .cat-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
  }

  @media (hover: none) and (pointer: coarse) {
    .cat-action-btn { width: 36px; height: 36px; font-size: 13px; }
    .cat-btn { padding: 9px 16px; }
    .cat-form-input, .cat-form-select, .cat-form-textarea { font-size: 16px; }
  }
</style>

<div class="cat-page">
  <!-- Header -->
  <div class="cat-page-header">
    <div>
      <div class="cat-page-eyebrow"><i class="fas fa-folder-tree" style="margin-right:5px"></i>Inventory</div>
      <div class="cat-page-title">Categories</div>
      <div class="cat-page-subtitle">Manage product categories and hierarchy</div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="cat-stats-grid">
    <div class="cat-stat-card blue">
      <div class="cat-stat-icon"><i class="fas fa-layer-group"></i></div>
      <div class="cat-stat-label">Total Categories</div>
      <div class="cat-stat-value blue" id="statTotal">—</div>
    </div>
    <div class="cat-stat-card purple">
      <div class="cat-stat-icon"><i class="fas fa-folder"></i></div>
      <div class="cat-stat-label">Parent Categories</div>
      <div class="cat-stat-value" id="statParent">—</div>
    </div>
    <div class="cat-stat-card green">
      <div class="cat-stat-icon"><i class="fas fa-folder-open"></i></div>
      <div class="cat-stat-label">Sub Categories</div>
      <div class="cat-stat-value green" id="statSub">—</div>
    </div>
  </div>

  <!-- Categories Table -->
  <div class="cat-card">
    <div class="cat-card-header">
      <div>
        <div class="cat-card-title">All Categories</div>
        <div class="cat-card-count" id="tableCount">Loading…</div>
      </div>
      <div class="cat-toolbar">
        <div class="cat-entries-wrap">
          <span class="cat-entries-label">Show</span>
          <select class="cat-entries-select" id="entriesPerPage" onchange="changeEntriesPerPage()">
            <option value="10">10</option>
            <option value="25" selected>25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="cat-entries-label">entries</span>
        </div>
        <div class="cat-search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" id="searchInput" class="cat-search" placeholder="Search categories…" oninput="filterTable()">
        </div>
        <button onclick="openAddCategoryModal()" class="cat-btn cat-btn-primary">
          <i class="fas fa-plus"></i> Add Category
        </button>
      </div>
    </div>
    <div class="cat-table-wrap">
      <table class="cat-table" id="categoriesTable">
        <thead>
          <tr>
            <th>Category</th>
            <th>Parent Category</th>
            <th>Description</th>
            <th class="center">Status</th>
            <th class="right">Actions</th>
          </tr>
        </thead>
        <tbody id="categoriesTableBody">
          <tr>
            <td colspan="5">
              <div class="cat-empty-state">
                <div class="cat-empty-icon"><i class="fas fa-spinner fa-spin"></i></div>
                <div class="cat-empty-text">Loading categories…</div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="cat-pagination-wrap" id="paginationWrap" style="display:none">
      <div class="cat-pagination-info" id="paginationInfo">Showing 1-25 of 100 entries</div>
      <div class="cat-pagination" id="paginationControls"></div>
    </div>
  </div>

  <!-- Category Details Modal -->
  <div class="cat-overlay" id="categoryDetailsModal">
    <div class="cat-modal" role="dialog" aria-modal="true" aria-labelledby="modalCategoryName">
      <div class="cat-modal-header">
        <div>
          <div class="cat-modal-title" id="modalCategoryName">Category Details</div>
          <div class="cat-modal-subtitle" id="modalCategoryCode"></div>
        </div>
        <button class="cat-modal-close" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="cat-modal-body" id="modalCategoryContent"></div>
    </div>
  </div>

  <!-- Add Category Modal -->
  <div class="cat-overlay" id="addCategoryModal">
    <div class="cat-modal" role="dialog" aria-modal="true">
      <div class="cat-modal-header">
        <div>
          <div class="cat-modal-title">Add New Category</div>
          <div class="cat-modal-subtitle">Fill in the details to create a new category</div>
        </div>
        <button class="cat-modal-close" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <form id="addCategoryForm" onsubmit="submitAddCategory(event)">
        <div class="cat-modal-body">
          <div class="cat-form-grid">
            <div class="cat-form-group">
              <label class="cat-form-label">Category Code <span class="req">*</span></label>
              <input type="text" name="category_code" required class="cat-form-input" placeholder="e.g., CAT-001">
            </div>
            <div class="cat-form-group">
              <label class="cat-form-label">Category Name <span class="req">*</span></label>
              <input type="text" name="category_name" required class="cat-form-input" placeholder="e.g., Toiletries">
            </div>
            <div class="cat-form-group">
              <label class="cat-form-label">Parent Category</label>
              <select name="parent_id" id="addParentId" class="cat-form-select">
                <option value="">No Parent (Root Category)</option>
              </select>
            </div>
            <div class="cat-form-group">
              <label class="cat-form-label">Sort Order</label>
              <input type="number" name="sort_order" step="1" min="0" value="0" class="cat-form-input" placeholder="0">
            </div>
            <div class="cat-form-group full">
              <label class="cat-form-label">Description</label>
              <textarea name="description" rows="3" class="cat-form-textarea" placeholder="Category description..."></textarea>
            </div>
          </div>
        </div>
        <div class="cat-modal-footer">
          <button type="button" class="cat-btn cat-modal-cancel">
            <i class="fas fa-times"></i> Cancel
          </button>
          <button type="submit" class="cat-btn cat-btn-primary">
            <i class="fas fa-save"></i> Save Category
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Category Modal -->
  <div class="cat-overlay" id="editCategoryModal">
    <div class="cat-modal" role="dialog" aria-modal="true">
      <div class="cat-modal-header">
        <div>
          <div class="cat-modal-title">Edit Category</div>
          <div class="cat-modal-subtitle">Update category details</div>
        </div>
        <button class="cat-modal-close" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <form id="editCategoryForm" onsubmit="submitEditCategory(event)">
        <div class="cat-modal-body">
          <div class="cat-form-grid">
            <input type="hidden" name="category_id">
            <div class="cat-form-group">
              <label class="cat-form-label">Category Code <span class="req">*</span></label>
              <input type="text" name="category_code" required class="cat-form-input" placeholder="e.g., CAT-001">
            </div>
            <div class="cat-form-group">
              <label class="cat-form-label">Category Name <span class="req">*</span></label>
              <input type="text" name="category_name" required class="cat-form-input" placeholder="e.g., Toiletries">
            </div>
            <div class="cat-form-group">
              <label class="cat-form-label">Parent Category</label>
              <select name="parent_id" id="editParentId" class="cat-form-select">
                <option value="">No Parent (Root Category)</option>
              </select>
            </div>
            <div class="cat-form-group">
              <label class="cat-form-label">Sort Order</label>
              <input type="number" name="sort_order" step="1" min="0" value="0" class="cat-form-input" placeholder="0">
            </div>
            <div class="cat-form-group full">
              <label class="cat-form-label">Description</label>
              <textarea name="description" rows="3" class="cat-form-textarea" placeholder="Category description..."></textarea>
            </div>
          </div>
        </div>
        <div class="cat-modal-footer">
          <button type="button" class="cat-btn cat-modal-cancel">
            <i class="fas fa-times"></i> Cancel
          </button>
          <button type="submit" class="cat-btn cat-btn-primary">
            <i class="fas fa-save"></i> Update Category
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Custom Alert Dialog (replaces SweetAlert) -->
  <div class="cat-alert-overlay" id="catAlertOverlay">
    <div class="cat-alert" id="catAlert">
      <div class="cat-alert-header">
        <div class="cat-alert-icon" id="catAlertIcon"><i class="fas fa-info"></i></div>
        <div class="cat-alert-title" id="catAlertTitle">Alert</div>
      </div>
      <div class="cat-alert-body">
        <div class="cat-alert-message" id="catAlertMessage">Message goes here</div>
      </div>
      <div class="cat-alert-footer" id="catAlertFooter">
        <button class="cat-alert-btn" id="catAlertCancelBtn" style="display:none">Cancel</button>
        <button class="cat-alert-btn primary" id="catAlertConfirmBtn">OK</button>
      </div>
    </div>
  </div>

  <script>
    let categoriesData = [];
    let filteredData = [];
    let currentPage = 1;
    let entriesPerPage = 25;
    const CSRF_TOKEN = <?php echo json_encode(CSRF_TOKEN); ?>;

    // Custom Alert Dialog Helper (replaces SweetAlert)
    function showCatAlert(options) {
      const overlay = document.getElementById('catAlertOverlay');
      const alertEl = document.getElementById('catAlert');

      // Handle loading state
      if (options.loading) {
        alertEl.innerHTML = `
          <div class="cat-alert-loading">
            <i class="fas fa-spinner"></i>
            <span class="cat-alert-loading-text">${options.loadingText || 'Loading...'}</span>
          </div>
        `;
        overlay.classList.add('open');
        return;
      }

      // Restore normal structure if it was in loading state
      if (!alertEl.querySelector('.cat-alert-header')) {
        alertEl.innerHTML = `
          <div class="cat-alert-header">
            <div class="cat-alert-icon" id="catAlertIcon"><i class="fas fa-info"></i></div>
            <div class="cat-alert-title" id="catAlertTitle">Alert</div>
          </div>
          <div class="cat-alert-body">
            <div class="cat-alert-message" id="catAlertMessage">Message goes here</div>
          </div>
          <div class="cat-alert-footer" id="catAlertFooter">
            <button class="cat-alert-btn" id="catAlertCancelBtn" style="display:none">Cancel</button>
            <button class="cat-alert-btn primary" id="catAlertConfirmBtn">OK</button>
          </div>
        `;
      }

      // Re-query elements after possible HTML restoration
      const iconEl = document.getElementById('catAlertIcon');
      const titleEl = document.getElementById('catAlertTitle');
      const messageEl = document.getElementById('catAlertMessage');
      const cancelBtn = document.getElementById('catAlertCancelBtn');
      const confirmBtn = document.getElementById('catAlertConfirmBtn');

      // Set icon
      const iconMap = {
        success: 'fa-check',
        error: 'fa-times',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info'
      };
      const iconClass = options.icon || 'info';
      iconEl.className = 'cat-alert-icon ' + iconClass;
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
      confirmBtn.className = 'cat-alert-btn ' + (options.confirmButtonClass || 'primary');
      if (options.confirmButtonColor === '#C62828') {
        confirmBtn.className = 'cat-alert-btn danger';
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

    function closeCatAlert() {
      document.getElementById('catAlertOverlay').classList.remove('open');
    }

    // ── Modal Manager ─────────────────────────────────────────────────────────────
    class ModalManager {
      constructor() {
        this.modals = {};
        this.init();
      }

      init() {
        // Move all modals to body to avoid z-index issues
        ['categoryDetailsModal', 'addCategoryModal', 'editCategoryModal', 'catAlertOverlay'].forEach(id => {
          const el = document.getElementById(id);
          if (el) {
            document.body.appendChild(el);
            this.modals[id] = el;
          }
        });

        // Setup overlay click handlers
        Object.values(this.modals).forEach(modal => {
          if (modal.classList.contains('cat-overlay')) {
            modal.addEventListener('click', (e) => {
              if (e.target === modal) {
                this.close(modal.id);
              }
            });
          }
        });

        // Setup close button handlers
        document.querySelectorAll('.cat-modal-close').forEach(btn => {
          btn.addEventListener('click', () => {
            const modal = btn.closest('.cat-overlay');
            if (modal) this.close(modal.id);
          });
        });

        // Setup cancel button handlers
        document.querySelectorAll('.cat-modal-cancel').forEach(btn => {
          btn.addEventListener('click', () => {
            const modal = btn.closest('.cat-overlay');
            if (modal) this.close(modal.id);
          });
        });

        // ESC key to close
        document.addEventListener('keydown', (e) => {
          if (e.key === 'Escape') {
            Object.values(this.modals).forEach(modal => {
              if (modal.classList.contains('open')) {
                this.close(modal.id);
              }
            });
          }
        });
      }

      open(modalId) {
        const modal = this.modals[modalId];
        if (modal) {
          modal.classList.add('open');
          document.body.style.overflow = 'hidden';
        }
      }

      close(modalId) {
        const modal = this.modals[modalId];
        if (modal) {
          modal.classList.remove('open');
          document.body.style.overflow = '';
          // Reset form if exists
          const form = modal.querySelector('form');
          if (form) form.reset();
        }
      }

      isOpen(modalId) {
        const modal = this.modals[modalId];
        return modal ? modal.classList.contains('open') : false;
      }
    }

    const modalManager = new ModalManager();

    // ── Modal Functions (using ModalManager) ─────────────────────────────────────
    function openAddCategoryModal() {
      modalManager.open('addCategoryModal');
    }

    function closeAddCategoryModal() {
      modalManager.close('addCategoryModal');
    }

    function openEditCategoryModal(categoryId) {
      const category = categoriesData.find(c => String(c.category_id) === String(categoryId));
      if (!category) return;

      const form = document.getElementById('editCategoryForm');
      form.querySelector('[name="category_id"]').value = category.category_id;
      form.querySelector('[name="category_code"]').value = category.category_code;
      form.querySelector('[name="category_name"]').value = category.category_name;
      document.getElementById('editParentId').value = category.parent_id || '';
      form.querySelector('[name="sort_order"]').value = category.sort_order || 0;
      form.querySelector('[name="description"]').value = category.description || '';

      modalManager.open('editCategoryModal');
    }

    function closeEditCategoryModal() {
      modalManager.close('editCategoryModal');
    }

    function viewCategoryDetails(categoryId) {
      const category = categoriesData.find(c => String(c.category_id) === String(categoryId));
      if (!category) return;

      document.getElementById('modalCategoryName').textContent = category.category_name;
      document.getElementById('modalCategoryCode').textContent = category.category_code;

      const isParent = !category.parent_id;

      document.getElementById('modalCategoryContent').innerHTML = `
      <div class="cat-details-grid">
        <div>
          <div class="cat-details-section-title"><i class="fas fa-info-circle" style="margin-right:5px;opacity:.6"></i>Basic Information</div>
          <div class="cat-detail-row"><div class="cat-detail-key">Category Code</div><div class="cat-detail-val">${category.category_code}</div></div>
          <div class="cat-detail-row"><div class="cat-detail-key">Category Name</div><div class="cat-detail-val">${category.category_name}</div></div>
          <div class="cat-detail-row"><div class="cat-detail-key">Parent Category</div><div class="cat-detail-val">${isParent ? '<span class="cat-badge parent"><i class="fas fa-folder" style="margin-right:4px;font-size:9px"></i>Parent</span>' : (category.parent_name || '—')}</div></div>
          <div class="cat-detail-row"><div class="cat-detail-key">Sort Order</div><div class="cat-detail-val">${category.sort_order || '0'}</div></div>
          <div class="cat-detail-row"><div class="cat-detail-key">Description</div><div class="cat-detail-val">${category.description || '—'}</div></div>
        </div>
        <div>
          <div class="cat-details-section-title" style="margin-top:20px"><i class="fas fa-sliders" style="margin-right:5px;opacity:.6"></i>Settings</div>
          <div class="cat-detail-row"><div class="cat-detail-key">Status</div><div class="cat-detail-val"><span class="cat-badge active">Active</span></div></div>
          <div class="cat-detail-row"><div class="cat-detail-key">Created At</div><div class="cat-detail-val">${new Date(category.created_at).toLocaleString()}</div></div>
        </div>
      </div>`;

      modalManager.open('categoryDetailsModal');
    }

    function closeCategoryDetailsModal() {
      modalManager.close('categoryDetailsModal');
    }

    document.addEventListener('DOMContentLoaded', function() {
      fetchCategories();
    });

    async function fetchCategories() {
      try {
        const res = await fetch('../api/inventory_categories.php?action=list');
        const result = await res.json();
        if (result.success) {
          categoriesData = result.data;
          filteredData = [...categoriesData];
          currentPage = 1;
          renderTable();
          renderStats();
          populateParentDropdowns();
        } else {
          showEmptyState(result.message);
        }
      } catch (e) {
        showEmptyState('Failed to load data');
      }
    }

    function showEmptyState(msg) {
      document.getElementById('categoriesTableBody').innerHTML = `
      <tr><td colspan="5">
        <div class="cat-empty-state">
          <div class="cat-empty-icon"><i class="fas fa-circle-xmark"></i></div>
          <div class="cat-empty-text">${msg}</div>
        </div>
      </td></tr>`;
    }

    function renderStats() {
      const total = categoriesData.length;
      const parents = categoriesData.filter(c => !c.parent_id).length;
      const subs = categoriesData.filter(c => !!c.parent_id).length;

      const pairs = [
        ['statTotal', total],
        ['statParent', parents],
        ['statSub', subs]
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

    function populateParentDropdowns() {
      const opts = '<option value="">No Parent (Root Category)</option>' +
        categoriesData.map(c => `<option value="${c.category_id}">${c.category_name} (${c.category_code})</option>`).join('');
      document.getElementById('addParentId').innerHTML = opts;
      document.getElementById('editParentId').innerHTML = opts;
    }

    function renderTable() {
      const tbody = document.getElementById('categoriesTableBody');
      const count = document.getElementById('tableCount');

      if (!filteredData.length) {
        count.textContent = '0 categories';
        document.getElementById('paginationWrap').style.display = 'none';
        tbody.innerHTML = `
        <tr><td colspan="5">
          <div class="cat-empty-state">
            <div class="cat-empty-icon"><i class="fas fa-layer-group"></i></div>
            <div class="cat-empty-text">No categories found</div>
            <div class="cat-empty-sub"><a href="javascript:;" onclick="openAddCategoryModal()" style="color:var(--prod-blue);font-weight:500">Add your first category</a></div>
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

      count.textContent = `${totalEntries} categor${totalEntries !== 1 ? 'ies' : 'y'}`;

      // Show/hide pagination based on total entries
      const paginationWrap = document.getElementById('paginationWrap');
      if (totalEntries > 10) {
        paginationWrap.style.display = 'flex';
        renderPagination(totalPages, start, end, totalEntries);
      } else {
        paginationWrap.style.display = 'none';
      }

      tbody.innerHTML = paginatedData.map(c => {
        const isParent = !c.parent_id;
        return `
      <tr class="category-row">
        <td>
          <div class="cat-name-cell">
            <div class="cat-avatar">${c.category_name.charAt(0).toUpperCase()}</div>
            <div>
              <div class="cat-name-primary">${c.category_name}</div>
              <div class="cat-name-secondary">${c.category_code}</div>
            </div>
          </div>
        </td>
        <td>
          ${isParent
            ? `<span class="cat-badge parent"><i class="fas fa-folder" style="margin-right:4px;font-size:9px"></i>Parent</span>`
            : `<span class="cat-badge sub">${c.parent_name || '—'}</span>`}
        </td>
        <td>
          <span class="cat-desc">${(c.description || '—').substring(0, 50)}${c.description && c.description.length > 50 ? '…' : ''}</span>
        </td>
        <td style="text-align:center">
          <span class="cat-badge active">Active</span>
        </td>
        <td>
          <div class="cat-actions-cell">
            <button onclick="viewCategoryDetails('${c.category_id}')" class="cat-action-btn view"><i class="fas fa-eye"></i></button>
            <button onclick="openEditCategoryModal('${c.category_id}')" class="cat-action-btn edit"><i class="fas fa-pen"></i></button>
            <button onclick="deleteCategory('${c.category_id}', '${c.category_name.replace(/'/g, "\\'")}')" class="cat-action-btn danger"><i class="fas fa-trash-alt"></i></button>
          </div>
        </td>
      </tr>`;
      }).join('');
    }

    function filterTable() {
      const filter = document.getElementById('searchInput').value.toLowerCase();
      currentPage = 1;

      if (!filter) {
        filteredData = [...categoriesData];
      } else {
        filteredData = categoriesData.filter(c =>
          c.category_name.toLowerCase().includes(filter) ||
          c.category_code.toLowerCase().includes(filter) ||
          (c.parent_name && c.parent_name.toLowerCase().includes(filter))
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
      html += `<button class="cat-page-btn" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`;

      const maxButtons = 5;
      let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
      let endPage = Math.min(totalPages, startPage + maxButtons - 1);
      if (endPage - startPage < maxButtons - 1) {
        startPage = Math.max(1, endPage - maxButtons + 1);
      }

      if (startPage > 1) {
        html += `<button class="cat-page-btn" onclick="goToPage(1)">1</button>`;
        if (startPage > 2) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
      }

      for (let i = startPage; i <= endPage; i++) {
        html += `<button class="cat-page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
      }

      if (endPage < totalPages) {
        if (endPage < totalPages - 1) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
        html += `<button class="cat-page-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
      }

      html += `<button class="cat-page-btn" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`;
      paginationControls.innerHTML = html;
    }

    function submitEditCategory(event) {
      event.preventDefault();
      const form = document.getElementById('editCategoryForm');
      const formData = new FormData(form);

      showCatAlert({
        title: 'Updating…',
        loading: true,
        loadingText: 'Updating category...'
      });

      const data = {
        category_id: formData.get('category_id'),
        category_code: formData.get('category_code'),
        category_name: formData.get('category_name'),
        parent_id: formData.get('parent_id'),
        sort_order: formData.get('sort_order'),
        description: formData.get('description'),
        csrf_token: CSRF_TOKEN
      };

      fetch('../api/inventory_categories.php?action=update', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        })
        .then(r => r.json())
        .then(data => {
          closeEditCategoryModal();
          closeCatAlert();
          if (data.success) {
            showCatAlert({
              icon: 'success',
              title: 'Updated!',
              text: data.message,
              confirmButtonColor: '#1A6BBF'
            }).then(() => fetchCategories());
          } else {
            showCatAlert({
              icon: 'error',
              title: 'Error',
              text: data.message,
              confirmButtonColor: '#C62828'
            });
          }
        })
        .catch(() => {
          closeEditCategoryModal();
          closeCatAlert();
          showCatAlert({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred.',
            confirmButtonColor: '#C62828'
          });
        });
    }

    function deleteCategory(categoryId, categoryName) {
      showCatAlert({
        title: 'Delete category?',
        html: `<span style="font-size:14px">You're about to delete <strong>${categoryName}</strong>. This action cannot be undone.</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#C62828',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          showCatAlert({
            title: 'Deleting…',
            loading: true,
            loadingText: 'Deleting category...'
          });

          fetch('../api/inventory_categories.php?action=delete', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                category_id: categoryId,
                csrf_token: CSRF_TOKEN
              })
            })
            .then(r => r.json())
            .then(data => {
              closeCatAlert();
              if (data.success) {
                showCatAlert({
                  icon: 'success',
                  title: 'Deleted!',
                  text: data.message,
                  confirmButtonColor: '#1A6BBF'
                }).then(() => fetchCategories());
              } else {
                showCatAlert({
                  icon: 'error',
                  title: 'Error',
                  text: data.message,
                  confirmButtonColor: '#C62828'
                });
              }
            })
            .catch(() => {
              closeCatAlert();
              showCatAlert({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred.',
                confirmButtonColor: '#C62828'
              });
            });
        }
      });
    }

    function openAddCategoryModal() {
      document.getElementById('addCategoryModal').classList.add('open');
    }

    function closeAddCategoryModal() {
      document.getElementById('addCategoryModal').classList.remove('open');
      document.getElementById('addCategoryForm').reset();
    }

    function submitAddCategory(event) {
      event.preventDefault();
      const form = document.getElementById('addCategoryForm');
      const formData = new FormData(form);

      showCatAlert({
        title: 'Adding Category…',
        loading: true,
        loadingText: 'Adding category...'
      });

      const data = {
        category_code: formData.get('category_code'),
        category_name: formData.get('category_name'),
        parent_id: formData.get('parent_id'),
        sort_order: formData.get('sort_order'),
        description: formData.get('description'),
        csrf_token: CSRF_TOKEN
      };

      fetch('../api/inventory_categories.php?action=create', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        })
        .then(r => r.json())
        .then(data => {
          closeAddCategoryModal();
          closeCatAlert();
          if (data.success) {
            showCatAlert({
              icon: 'success',
              title: 'Category Added!',
              text: data.message,
              confirmButtonColor: '#1A6BBF'
            }).then(() => fetchCategories());
          } else {
            showCatAlert({
              icon: 'error',
              title: 'Error',
              text: data.message,
              confirmButtonColor: '#C62828'
            });
          }
        })
        .catch(() => {
          closeAddCategoryModal();
          closeCatAlert();
          showCatAlert({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred.',
            confirmButtonColor: '#C62828'
          });
        });
    }
  </script>