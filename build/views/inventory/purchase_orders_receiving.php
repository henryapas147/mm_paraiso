<?php if (false) {
} ?>
<style>
  @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=DM+Mono:wght@400;500&display=swap');

  :root {
    --rcv-blue: #1A6BBF;
    --rcv-blue-dark: #0F4C8A;
    --rcv-blue-pale: #EBF3FC;
    --rcv-green: #2E7D32;
    --rcv-green-pale: #E8F5E9;
    --rcv-amber: #E65100;
    --rcv-amber-pale: #FFF3E0;
    --rcv-purple: #6230B5;
    --rcv-purple-pale: #F5F0FF;
    --rcv-slate: #475569;
    --rcv-slate-pale: #F1F5F9;
    --rcv-r-sm: 6px;
    --rcv-r-md: 10px;
    --rcv-r-lg: 14px;
    --rcv-r-xl: 20px;
    --rcv-shadow-sm: 0 1px 3px rgba(0, 0, 0, .07), 0 1px 2px rgba(0, 0, 0, .05);
    --rcv-shadow-md: 0 4px 12px rgba(0, 0, 0, .08), 0 2px 4px rgba(0, 0, 0, .05);
    --rcv-shadow-lg: 0 20px 48px rgba(0, 0, 0, .14), 0 8px 16px rgba(0, 0, 0, .07);
    --rcv-font: 'DM Sans', sans-serif;
    --rcv-mono: 'DM Mono', monospace;
  }

  .rcv-page {
    padding: 28px 28px 48px;
    background: var(--color-background-tertiary, #F4F5F7);
    min-height: 100%;
    border-radius: var(--border-radius-xl, 18px);
    font-family: var(--rcv-font);
  }

  .rcv-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
    gap: 16px;
    flex-wrap: wrap;
  }

  .rcv-eyebrow {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--rcv-green);
    margin-bottom: 4px;
  }

  .rcv-page-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.4px;
    line-height: 1.2;
  }

  .rcv-page-subtitle {
    font-size: 13px;
    color: var(--color-text-secondary);
    margin-top: 3px;
  }

  .rcv-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
  }

  .rcv-stat-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--rcv-r-lg);
    padding: 20px 22px;
    box-shadow: var(--rcv-shadow-sm);
    position: relative;
    overflow: hidden;
    transition: box-shadow .2s, transform .2s;
  }

  .rcv-stat-card:hover {
    box-shadow: var(--rcv-shadow-md);
    transform: translateY(-1px);
  }

  .rcv-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--rcv-r-lg) var(--rcv-r-lg) 0 0;
    background: var(--_accent, #CBD5E1);
  }

  .rcv-stat-card.blue {
    --_accent: var(--rcv-blue);
  }

  .rcv-stat-card.amber {
    --_accent: var(--rcv-amber);
  }

  .rcv-stat-card.green {
    --_accent: var(--rcv-green);
  }

  .rcv-stat-card.purple {
    --_accent: var(--rcv-purple);
  }

  .rcv-stat-icon {
    width: 36px;
    height: 36px;
    border-radius: var(--rcv-r-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    margin-bottom: 14px;
    background: var(--rcv-green-pale);
    color: var(--rcv-green);
  }

  .rcv-stat-card.blue .rcv-stat-icon {
    background: var(--rcv-blue-pale);
    color: var(--rcv-blue);
  }

  .rcv-stat-card.amber .rcv-stat-icon {
    background: var(--rcv-amber-pale);
    color: var(--rcv-amber);
  }

  .rcv-stat-card.purple .rcv-stat-icon {
    background: var(--rcv-purple-pale);
    color: var(--rcv-purple);
  }

  .rcv-stat-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-secondary);
    margin-bottom: 6px;
  }

  .rcv-stat-value {
    font-size: 32px;
    font-weight: 600;
    color: var(--color-text-primary);
    font-family: var(--rcv-mono);
    letter-spacing: -.5px;
    line-height: 1;
  }

  .rcv-stat-value.blue {
    color: var(--rcv-blue);
  }

  .rcv-stat-value.green {
    color: var(--rcv-green);
  }

  .rcv-stat-value.amber {
    color: var(--rcv-amber);
  }

  .rcv-stat-value.purple {
    color: var(--rcv-purple);
  }

  .rcv-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--rcv-r-lg);
    box-shadow: var(--rcv-shadow-sm);
    overflow: hidden;
  }

  .rcv-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }

  .rcv-card-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.2px;
  }

  .rcv-card-count {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-top: 2px;
  }

  .rcv-toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
  }

  .rcv-search-wrap {
    position: relative;
  }

  .rcv-search-wrap i {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-text-secondary);
    font-size: 12px;
    pointer-events: none;
  }

  .rcv-search {
    padding: 8px 14px 8px 34px;
    font-size: 13px;
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--rcv-r-md);
    background: var(--color-background-secondary, #F8FAFC);
    color: var(--color-text-primary);
    outline: none;
    width: 230px;
    font-family: var(--rcv-font);
    transition: border-color .15s, box-shadow .15s;
  }

  .rcv-search:focus {
    border-color: var(--rcv-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    background: var(--color-background-primary, #fff);
  }

  .rcv-filter-tabs {
    display: flex;
    gap: 4px;
    background: var(--color-background-secondary, #F1F5F9);
    border-radius: var(--rcv-r-md);
    padding: 3px;
  }

  .rcv-filter-tab {
    padding: 5px 13px;
    font-size: 12px;
    font-weight: 500;
    border-radius: 7px;
    border: none;
    background: transparent;
    color: var(--color-text-secondary);
    cursor: pointer;
    transition: all .15s;
    font-family: var(--rcv-font);
    white-space: nowrap;
  }

  .rcv-filter-tab.active {
    background: var(--color-background-primary, #fff);
    color: var(--color-text-primary);
    box-shadow: var(--rcv-shadow-sm);
    font-weight: 600;
  }

  .rcv-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 500;
    border-radius: var(--rcv-r-md);
    border: 1px solid rgba(0, 0, 0, .12);
    background: var(--color-background-primary, #fff);
    color: var(--color-text-primary);
    cursor: pointer;
    transition: all .15s;
    font-family: var(--rcv-font);
    line-height: 1;
  }

  .rcv-btn:hover {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .rcv-btn-primary {
    background: var(--rcv-green);
    color: #fff;
    border-color: var(--rcv-green);
    box-shadow: 0 1px 3px rgba(46, 125, 50, .3);
  }

  .rcv-btn-primary:hover {
    background: #1B5E20;
    border-color: #1B5E20;
    box-shadow: 0 2px 6px rgba(46, 125, 50, .35);
  }

  .rcv-table-wrap {
    overflow-x: auto;
  }

  .rcv-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    font-family: var(--rcv-font);
  }

  .rcv-table thead tr {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .rcv-table th {
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

  .rcv-table th.right {
    text-align: right;
  }

  .rcv-table th.center {
    text-align: center;
  }

  .rcv-table tbody tr {
    border-bottom: 1px solid rgba(0, 0, 0, .05);
    transition: background .12s;
  }

  .rcv-table tbody tr:last-child {
    border-bottom: none;
  }

  .rcv-table tbody tr:hover {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .rcv-table td {
    padding: 13px 18px;
    vertical-align: middle;
    white-space: nowrap;
  }

  .rcv-table td.right {
    text-align: right;
  }

  .rcv-table td.center {
    text-align: center;
  }

  .rcv-name-cell {
    display: flex;
    align-items: center;
  }

  .rcv-avatar {
    width: 36px;
    height: 36px;
    border-radius: var(--rcv-r-sm);
    background: var(--rcv-green-pale);
    color: var(--rcv-green);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
    margin-right: 10px;
  }

  .rcv-name-primary {
    font-size: 13px;
    font-weight: 600;
    color: var(--color-text-primary);
    font-family: var(--rcv-mono);
  }

  .rcv-name-secondary {
    font-size: 11px;
    color: var(--color-text-secondary);
    margin-top: 2px;
    font-family: var(--rcv-font);
  }

  .rcv-progress-wrap {
    width: 120px;
    height: 6px;
    background: #E2E8F0;
    border-radius: 3px;
    overflow: hidden;
  }

  .rcv-progress-bar {
    height: 100%;
    background: var(--rcv-green);
    border-radius: 3px;
    transition: width .3s ease;
  }

  .rcv-progress-bar.partial {
    background: var(--rcv-amber);
  }

  .rcv-progress-text {
    font-size: 11px;
    color: var(--color-text-secondary);
    margin-top: 4px;
    text-align: center;
  }

  .rcv-badge {
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

  .rcv-badge::before {
    content: '';
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: currentColor;
    opacity: .7;
  }

  .rcv-badge.approved {
    background: var(--rcv-blue-pale);
    color: var(--rcv-blue);
  }

  .rcv-badge.partially_received {
    background: var(--rcv-amber-pale);
    color: var(--rcv-amber);
  }

  .rcv-badge.draft {
    background: var(--rcv-slate-pale);
    color: var(--rcv-slate);
  }

  .rcv-badge.submitted {
    background: var(--rcv-blue-pale);
    color: var(--rcv-blue);
  }

  .rcv-badge.fully_received {
    background: var(--rcv-green-pale);
    color: var(--rcv-green);
  }

  .rcv-badge.cancelled {
    background: #FFEBEE;
    color: #C62828;
  }

  .rcv-actions {
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .rcv-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px;
    font-size: 12px;
    font-weight: 500;
    border-radius: var(--rcv-r-sm);
    border: 1px solid rgba(0, 0, 0, .1);
    background: transparent;
    cursor: pointer;
    transition: all .15s;
    color: var(--color-text-secondary);
    font-family: var(--rcv-font);
  }

  .rcv-action-btn.receive:hover {
    background: var(--rcv-green-pale);
    color: var(--rcv-green);
    border-color: rgba(46, 125, 50, .2);
  }

  .rcv-action-btn.view:hover {
    background: var(--rcv-blue-pale);
    color: var(--rcv-blue);
    border-color: rgba(26, 107, 191, .2);
  }

  .rcv-empty {
    padding: 64px 24px;
    text-align: center;
  }

  .rcv-empty-icon {
    font-size: 32px;
    opacity: .2;
    margin-bottom: 12px;
  }

  .rcv-empty-text {
    font-size: 14px;
    font-weight: 500;
    color: var(--color-text-secondary);
  }

  .rcv-empty-sub {
    font-size: 12px;
    color: var(--color-text-secondary);
    opacity: .7;
    margin-top: 4px;
  }

  .rcv-pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 22px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    gap: 16px;
    flex-wrap: wrap;
  }

  .rcv-pagination-left {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .rcv-pagination-info {
    font-size: 12px;
    color: var(--color-text-secondary);
  }

  .rcv-entries-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .rcv-entries-label {
    font-size: 12px;
    color: var(--color-text-secondary);
  }

  .rcv-entries-select {
    padding: 5px 28px 5px 10px;
    font-size: 12px;
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--rcv-r-sm);
    background: var(--color-background-secondary, #F8FAFC);
    color: var(--color-text-primary);
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748B' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    font-family: var(--rcv-font);
    outline: none;
  }

  .rcv-entries-select:focus {
    border-color: var(--rcv-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
  }

  .rcv-pagination {
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .rcv-page-btn {
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--rcv-r-sm);
    background: var(--color-background-primary, #fff);
    color: var(--color-text-primary);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all .15s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-family: var(--rcv-font);
  }

  .rcv-page-btn:hover:not(:disabled) {
    background: var(--rcv-blue-pale);
    border-color: var(--rcv-blue);
    color: var(--rcv-blue);
  }

  .rcv-page-btn.active {
    background: var(--rcv-green);
    border-color: var(--rcv-green);
    color: #fff;
  }

  .rcv-page-btn:disabled {
    opacity: .35;
    cursor: not-allowed;
  }

  .rcv-overlay {
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
    overflow: hidden;
  }

  .rcv-overlay.open {
    display: flex;
  }

  .rcv-modal {
    background: var(--color-background-primary, #fff);
    border-radius: var(--rcv-r-xl);
    border: 1px solid rgba(0, 0, 0, .08);
    width: 100%;
    max-width: 900px;
    max-height: 92vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: var(--rcv-shadow-lg);
    animation: rcvModalIn .2s cubic-bezier(.22, .68, 0, 1.2);
  }

  @keyframes rcvModalIn {
    from {
      opacity: 0;
      transform: translateY(14px) scale(.98);
    }

    to {
      opacity: 1;
      transform: none;
    }
  }

  .rcv-modal-header {
    padding: 22px 26px 18px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-shrink: 0;
    background: var(--color-background-primary, #fff);
    border-radius: var(--rcv-r-xl) var(--rcv-r-xl) 0 0;
  }

  .rcv-modal-title {
    font-size: 17px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.3px;
  }

  .rcv-modal-subtitle {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-top: 2px;
  }

  .rcv-modal-close {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--color-background-secondary, #F1F5F9);
    border: none;
    cursor: pointer;
    color: var(--color-text-secondary);
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .15s;
    flex-shrink: 0;
  }

  .rcv-modal-close:hover {
    background: #FFEBEE;
    color: #C62828;
  }

  .rcv-modal-body {
    padding: 26px;
    flex: 1 1 auto;
    overflow-y: auto;
    overflow-x: hidden;
    min-height: 0;
  }

  .rcv-modal-footer {
    padding: 18px 26px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    flex-shrink: 0;
    background: var(--color-background-primary, #fff);
    border-radius: 0 0 var(--rcv-r-xl) var(--rcv-r-xl);
  }

  .rcv-po-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
    padding: 16px;
    background: var(--color-background-secondary, #F8FAFC);
    border-radius: var(--rcv-r-md);
  }

  .rcv-info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .rcv-info-label {
    font-size: 11px;
    font-weight: 600;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .05em;
  }

  .rcv-info-value {
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-primary);
  }

  .rcv-info-value.mono {
    font-family: var(--rcv-mono);
  }

  .rcv-items-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    margin-top: 16px;
  }

  .rcv-items-table th {
    padding: 10px 12px;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    color: var(--color-text-secondary);
    background: var(--color-background-secondary, #F8FAFC);
    border-bottom: 1px solid rgba(0, 0, 0, .07);
    text-transform: uppercase;
    letter-spacing: .05em;
  }

  .rcv-items-table th.right {
    text-align: right;
  }

  .rcv-items-table th.center {
    text-align: center;
  }

  .rcv-items-table td {
    padding: 12px;
    border-bottom: 1px solid rgba(0, 0, 0, .05);
    vertical-align: middle;
  }

  .rcv-items-table td.right {
    text-align: right;
  }

  .rcv-items-table td.center {
    text-align: center;
  }

  .rcv-item-input {
    width: 80px;
    padding: 6px 10px;
    font-size: 13px;
    font-family: var(--rcv-mono);
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--rcv-r-sm);
    text-align: right;
    background: var(--color-background-primary, #fff);
    transition: border-color .15s, box-shadow .15s;
  }

  .rcv-item-input:focus {
    border-color: var(--rcv-green);
    box-shadow: 0 0 0 3px rgba(46, 125, 50, .12);
    outline: none;
  }

  .rcv-item-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    border-radius: 9999px;
    font-size: 10px;
    font-weight: 600;
  }

  .rcv-item-status.pending {
    background: var(--rcv-slate-pale);
    color: var(--rcv-slate);
  }

  .rcv-item-status.partial {
    background: var(--rcv-amber-pale);
    color: var(--rcv-amber);
  }

  .rcv-item-status.received {
    background: var(--rcv-green-pale);
    color: var(--rcv-green);
  }

  .rcv-qty-display {
    font-family: var(--rcv-mono);
    font-size: 12px;
    color: var(--color-text-secondary);
  }

  .rcv-qty-received {
    font-family: var(--rcv-mono);
    font-size: 12px;
    font-weight: 600;
    color: var(--rcv-green);
  }

  .rcv-alert-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, .5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 20px;
  }

  .rcv-alert-overlay.open {
    display: flex;
  }

  .rcv-alert-box {
    background: var(--color-background-primary, #fff);
    border-radius: var(--rcv-r-lg);
    box-shadow: var(--rcv-shadow-lg);
    max-width: 400px;
    width: 100%;
    padding: 24px;
    animation: rcvAlertIn .2s cubic-bezier(.22, .68, 0, 1.2);
  }

  @keyframes rcvAlertIn {
    from {
      opacity: 0;
      transform: scale(.95) translateY(10px);
    }

    to {
      opacity: 1;
      transform: scale(1) translateY(0);
    }
  }

  .rcv-alert-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--color-text-primary);
    margin-bottom: 8px;
  }

  .rcv-alert-message {
    font-size: 14px;
    color: var(--color-text-secondary);
    line-height: 1.5;
    margin-bottom: 20px;
  }

  .rcv-alert-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
  }

  .rcv-alert-btn-cancel,
  .rcv-alert-btn-confirm {
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 500;
    border-radius: var(--rcv-r-md);
    border: 1px solid rgba(0, 0, 0, .12);
    background: var(--color-background-primary, #fff);
    color: var(--color-text-primary);
    cursor: pointer;
    transition: all .15s;
    font-family: var(--rcv-font);
  }

  .rcv-alert-btn-cancel:hover {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .rcv-alert-btn-confirm {
    background: var(--rcv-green);
    color: #fff;
    border-color: var(--rcv-green);
  }

  .rcv-alert-btn-confirm:hover {
    background: #1B5E20;
    border-color: #1B5E20;
  }

  @media (max-width: 768px) {
    .rcv-form-grid {
      grid-template-columns: 1fr;
    }

    .rcv-search {
      width: 160px;
    }

    .rcv-filter-tabs {
      display: none;
    }

    .rcv-items-table {
      font-size: 12px;
    }

    .rcv-items-table th,
    .rcv-items-table td {
      padding: 8px;
    }

    .rcv-item-input {
      width: 60px;
    }
  }
</style>

<div class="rcv-page">
  <div class="rcv-page-header">
    <div>
      <div class="rcv-eyebrow"><i class="fas fa-truck-fast" style="margin-right:5px"></i>Warehouse</div>
      <div class="rcv-page-title">PO Receiving</div>
      <div class="rcv-page-subtitle">Receive and update inventory from purchase orders</div>
    </div>
  </div>

  <div class="rcv-stats-grid">
    <div class="rcv-stat-card green">
      <div class="rcv-stat-icon"><i class="fas fa-clipboard-check"></i></div>
      <div class="rcv-stat-label">Ready to Receive</div>
      <div class="rcv-stat-value green" id="statReady">—</div>
    </div>
    <div class="rcv-stat-card amber">
      <div class="rcv-stat-icon"><i class="fas fa-clock"></i></div>
      <div class="rcv-stat-label">Partially Received</div>
      <div class="rcv-stat-value amber" id="statPartial">—</div>
    </div>
    <div class="rcv-stat-card blue">
      <div class="rcv-stat-icon"><i class="fas fa-boxes"></i></div>
      <div class="rcv-stat-label">Items Pending</div>
      <div class="rcv-stat-value blue" id="statItemsPending">—</div>
    </div>
    <div class="rcv-stat-card purple">
      <div class="rcv-stat-icon"><i class="fas fa-calendar-check"></i></div>
      <div class="rcv-stat-label">Expected Today</div>
      <div class="rcv-stat-value purple" id="statExpectedToday">—</div>
    </div>
  </div>

  <div class="rcv-card">
    <div class="rcv-card-header">
      <div>
        <div class="rcv-card-title">Purchase Orders to Receive</div>
        <div class="rcv-card-count" id="rcvTableCount">Loading…</div>
      </div>
      <div class="rcv-toolbar">
        <div class="rcv-filter-tabs">
          <button class="rcv-filter-tab active" onclick="setRcvFilter('all', this)">All</button>
          <button class="rcv-filter-tab" onclick="setRcvFilter('approved', this)">Approved</button>
          <button class="rcv-filter-tab" onclick="setRcvFilter('partial', this)">Partial</button>
          <button class="rcv-filter-tab" onclick="setRcvFilter('today', this)">Expected Today</button>
        </div>
        <div class="rcv-search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" class="rcv-search" id="rcvSearchInput" placeholder="Search orders…"
            oninput="applyRcvFilters()">
        </div>
      </div>
    </div>

    <div class="rcv-table-wrap">
      <table class="rcv-table" id="rcvTable">
        <thead>
          <tr>
            <th>PO Number</th>
            <th>Supplier</th>
            <th>Property</th>
            <th>Expected Delivery</th>
            <th class="center">Receiving Progress</th>
            <th class="center">Status</th>
            <th class="right">Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="rcvTableBody">
          <tr>
            <td colspan="8">
              <div class="rcv-empty">
                <div class="rcv-empty-icon"><i class="fas fa-spinner fa-spin"></i></div>
                <div class="rcv-empty-text">Loading purchase orders…</div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="rcv-pagination-wrap">
      <div class="rcv-pagination-left">
        <div class="rcv-entries-wrap">
          <span class="rcv-entries-label">Show</span>
          <select class="rcv-entries-select" id="rcvEntriesSelect" onchange="changeRcvEntries(this.value)">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="rcv-entries-label">entries</span>
        </div>
        <span class="rcv-pagination-info" id="rcvPaginationInfo"></span>
      </div>
      <div class="rcv-pagination" id="rcvPaginationBtns"></div>
    </div>
  </div>
</div>

<div class="rcv-overlay" id="receiveModal" onclick="if(event.target===this)closeReceiveModal()">
  <div class="rcv-modal" role="dialog" aria-modal="true">
    <div class="rcv-modal-header">
      <div>
        <div class="rcv-modal-title"><i class="fas fa-truck-loading"
            style="margin-right:8px;color:var(--rcv-green)"></i>Receive Purchase Order</div>
        <div class="rcv-modal-subtitle" id="rcvModalSubtitle"></div>
      </div>
      <button class="rcv-modal-close" onclick="closeReceiveModal()" aria-label="Close"><i
          class="fas fa-times"></i></button>
    </div>
    <div class="rcv-modal-body" id="rcvModalBody">
      <div class="rcv-po-info-grid" id="rcvPoInfoGrid"></div>
      <table class="rcv-items-table">
        <thead>
          <tr>
            <th>Product</th>
            <th class="center">Ordered</th>
            <th class="center">Already Received</th>
            <th class="center">To Receive</th>
            <th class="center">Status</th>
          </tr>
        </thead>
        <tbody id="rcvItemsTableBody"></tbody>
      </table>
    </div>
    <div class="rcv-modal-footer">
      <button class="rcv-btn" onclick="closeReceiveModal()">Cancel</button>
      <button class="rcv-btn rcv-btn-primary" onclick="submitReceive()">Receive Items</button>
    </div>
  </div>
</div>

<div class="rcv-alert-overlay" id="rcvAlertOverlay">
  <div class="rcv-alert-box">
    <div class="rcv-alert-title" id="rcvAlertTitle"></div>
    <div class="rcv-alert-message" id="rcvAlertMessage"></div>
    <div class="rcv-alert-actions">
      <button class="rcv-alert-btn-cancel" onclick="closeRcvAlert()">Cancel</button>
      <button class="rcv-alert-btn-confirm" id="rcvAlertConfirmBtn">Confirm</button>
    </div>
  </div>
</div>

<script>
  const csrfToken = '<?php echo CSRF_TOKEN; ?>';
  const userPermissions = <?php echo isset($_SESSION['permissions']) ? json_encode($_SESSION['permissions']) : '[]'; ?>;
  const userRole = '<?php echo $_SESSION['role_code'] ?? ''; ?>';

  let rcvData = [];
  let rcvFilteredData = [];
  let rcvCurrentPage = 1;
  let rcvPerPage = 10;
  let rcvCurrentFilter = 'all';
  let rcvCurrentPO = null;
  let rcvAlertCallback = null;

  function rcvModalOpen(modal) {
    document.body.appendChild(modal);
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('open'), 10);
  }

  function rcvModalClose(modal) {
    modal.classList.remove('open');
    setTimeout(() => modal.style.display = 'none', 200);
  }

  async function fetchReceivingPOs() {
    try {
      const res = await fetch('../api/purchase_orders.php?action=list');
      const json = await res.json();
      if (json.success) {
        rcvData = json.data.filter(po =>
          ['draft', 'submitted', 'approved', 'partially_received'].includes(po.po_status)
        );
        applyRcvFilters();
        updateRcvStats();
      }
    } catch (e) { console.error('Error fetching POs:', e); }
  }

  function updateRcvStats() {
    const ready = rcvData.filter(po => ['draft', 'submitted', 'approved'].includes(po.po_status)).length;
    const partial = rcvData.filter(po => po.po_status === 'partially_received').length;
    const today = new Date().toISOString().split('T')[0];
    const expectedToday = rcvData.filter(po => po.expected_delivery === today).length;

    let itemsPending = 0;
    rcvData.forEach(po => {
      if (po.items && po.items.length) {
        po.items.forEach(item => {
          const ordered = parseFloat(item.ordered_qty) || 0;
          const received = parseFloat(item.received_qty) || 0;
          if (received < ordered) itemsPending += (ordered - received);
        });
      }
    });

    document.getElementById('statReady').textContent = ready;
    document.getElementById('statPartial').textContent = partial;
    document.getElementById('statItemsPending').textContent = itemsPending;
    document.getElementById('statExpectedToday').textContent = expectedToday;
  }

  function setRcvFilter(filter, btn) {
    rcvCurrentFilter = filter;
    document.querySelectorAll('.rcv-filter-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    applyRcvFilters();
  }

  function applyRcvFilters() {
    const search = document.getElementById('rcvSearchInput').value.toLowerCase();
    const today = new Date().toISOString().split('T')[0];

    rcvFilteredData = rcvData.filter(po => {
      const matchesSearch = po.po_number.toLowerCase().includes(search) ||
        po.company_name.toLowerCase().includes(search);

      if (!matchesSearch) return false;

      switch (rcvCurrentFilter) {
        case 'approved': return po.po_status === 'approved';
        case 'partial': return po.po_status === 'partially_received';
        case 'today': return po.expected_delivery === today;
        default: return true;
      }
    });

    rcvCurrentPage = 1;
    renderRcvTable();
  }

  const STATUS_MAP = {
    draft: { class: 'draft', label: 'Draft' },
    submitted: { class: 'submitted', label: 'Submitted' },
    approved: { class: 'approved', label: 'Approved' },
    partially_received: { class: 'partially_received', label: 'Partially Received' },
    fully_received: { class: 'fully_received', label: 'Fully Received' },
    cancelled: { class: 'cancelled', label: 'Cancelled' },
    closed: { class: 'closed', label: 'Closed' }
  };

  function renderRcvTable() {
    const start = (rcvCurrentPage - 1) * rcvPerPage;
    const end = start + rcvPerPage;
    const pageData = rcvFilteredData.slice(start, end);

    const canReceive = userPermissions.includes('receive_purchase_order') || ['super_admin', 'admin', 'manager', 'ceo'].includes(userRole);

    const tbody = document.getElementById('rcvTableBody');
    if (pageData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="8"><div class="rcv-empty"><div class="rcv-empty-icon"><i class="fas fa-inbox"></i></div><div class="rcv-empty-text">No purchase orders to receive</div></div></td></tr>`;
      document.getElementById('rcvTableCount').textContent = '0 orders';
      return;
    }

    let html = '';
    pageData.forEach(po => {
      const progress = calculatePOProgress(po);
      const statusMap = STATUS_MAP[po.po_status] || { class: po.po_status, label: po.po_status };
      const statusClass = statusMap.class;
      const statusLabel = statusMap.label;

      html += `<tr>
      <td>
        <div class="rcv-name-cell">
          <div class="rcv-avatar"><i class="fas fa-file-invoice"></i></div>
          <div>
            <div class="rcv-name-primary">${po.po_number}</div>
            <div class="rcv-name-secondary">${po.po_date || ''}</div>
          </div>
        </div>
      </td>
      <td>${po.company_name}</td>
      <td>${po.property_name}</td>
      <td>${po.expected_delivery || '—'}</td>
      <td class="center">
        <div class="rcv-progress-wrap">
          <div class="rcv-progress-bar ${progress < 100 ? 'partial' : ''}" style="width:${progress}%"></div>
        </div>
        <div class="rcv-progress-text">${progress}%</div>
      </td>
      <td class="center"><span class="rcv-badge ${statusClass}">${statusLabel}</span></td>
      <td class="right">₱${parseFloat(po.total_amount).toFixed(2)}</td>
      <td>
        <div class="rcv-actions">
          ${po.po_status === 'draft' || !canReceive
            ? `<span class="rcv-badge ${statusClass}" style="opacity:0.6">Not available</span>`
            : `<button class="rcv-action-btn receive" onclick="openReceiveModal('${po.po_id}')"><i class="fas fa-truck-loading"></i> Receive</button>`
          }
        </div>
      </td>
    </tr>`;
    });

    tbody.innerHTML = html;
    document.getElementById('rcvTableCount').textContent = `${rcvFilteredData.length} orders`;
    renderRcvPagination();
  }

  function calculatePOProgress(po) {
    if (po.items && po.items.length > 0) {
      let totalOrdered = 0;
      let totalReceived = 0;
      po.items.forEach(item => {
        const ordered = parseFloat(item.ordered_qty) || 0;
        const received = parseFloat(item.received_qty) || 0;
        totalOrdered += ordered;
        totalReceived += received;
      });
      return totalOrdered > 0 ? Math.round((totalReceived / totalOrdered) * 100) : 0;
    }
    // Fallback: estimate from status when items not in list response
    switch (po.po_status) {
      case 'fully_received': return 100;
      case 'partially_received': return 50;
      case 'approved': return 0;
      case 'submitted': return 0;
      case 'draft': return 0;
      default: return 0;
    }
  }

  function renderRcvPagination() {
    const totalPages = Math.ceil(rcvFilteredData.length / rcvPerPage);
    const btns = document.getElementById('rcvPaginationBtns');
    const info = document.getElementById('rcvPaginationInfo');

    const start = (rcvCurrentPage - 1) * rcvPerPage + 1;
    const end = Math.min(rcvCurrentPage * rcvPerPage, rcvFilteredData.length);
    info.textContent = `Showing ${start} to ${end} of ${rcvFilteredData.length} entries`;

    let html = '';
    html += `<button class="rcv-page-btn" onclick="rcvCurrentPage=1;renderRcvTable()" ${rcvCurrentPage === 1 ? 'disabled' : ''}><i class="fas fa-angle-double-left"></i></button>`;
    html += `<button class="rcv-page-btn" onclick="rcvCurrentPage--;renderRcvTable()" ${rcvCurrentPage === 1 ? 'disabled' : ''}><i class="fas fa-angle-left"></i></button>`;

    for (let i = 1; i <= totalPages; i++) {
      if (i === 1 || i === totalPages || (i >= rcvCurrentPage - 1 && i <= rcvCurrentPage + 1)) {
        html += `<button class="rcv-page-btn ${i === rcvCurrentPage ? 'active' : ''}" onclick="rcvCurrentPage=${i};renderRcvTable()">${i}</button>`;
      } else if (i === rcvCurrentPage - 2 || i === rcvCurrentPage + 2) {
        html += `<span style="padding:0 4px;color:var(--color-text-secondary)">...</span>`;
      }
    }

    html += `<button class="rcv-page-btn" onclick="rcvCurrentPage++;renderRcvTable()" ${rcvCurrentPage === totalPages ? 'disabled' : ''}><i class="fas fa-angle-right"></i></button>`;
    html += `<button class="rcv-page-btn" onclick="rcvCurrentPage=${totalPages};renderRcvTable()" ${rcvCurrentPage === totalPages ? 'disabled' : ''}><i class="fas fa-angle-double-right"></i></button>`;

    btns.innerHTML = html;
  }

  function changeRcvEntries(val) {
    rcvPerPage = parseInt(val);
    rcvCurrentPage = 1;
    renderRcvTable();
  }

  async function openReceiveModal(poId) {
    try {
      const res = await fetch(`../api/purchase_orders.php?action=single&id=${poId}`);
      const json = await res.json();
      if (json.success) {
        rcvCurrentPO = json.data;
        document.getElementById('rcvModalSubtitle').textContent = rcvCurrentPO.po_number;

        const infoGrid = document.getElementById('rcvPoInfoGrid');
        infoGrid.innerHTML = `
        <div class="rcv-info-item"><div class="rcv-info-label">Supplier</div><div class="rcv-info-value">${rcvCurrentPO.company_name}</div></div>
        <div class="rcv-info-item"><div class="rcv-info-label">Property</div><div class="rcv-info-value">${rcvCurrentPO.property_name}</div></div>
        <div class="rcv-info-item"><div class="rcv-info-label">Expected Delivery</div><div class="rcv-info-value">${rcvCurrentPO.expected_delivery || '—'}</div></div>
        <div class="rcv-info-item"><div class="rcv-info-label">Total Amount</div><div class="rcv-info-value mono">₱${parseFloat(rcvCurrentPO.total_amount).toFixed(2)}</div></div>
      `;

        renderRcvItemsTable();
        rcvModalOpen(document.getElementById('receiveModal'));
      }
    } catch (e) { console.error('Error fetching PO:', e); }
  }

  function renderRcvItemsTable() {
    const tbody = document.getElementById('rcvItemsTableBody');
    let html = '';

    rcvCurrentPO.items.forEach((item, index) => {
      const ordered = parseFloat(item.ordered_qty) || 0;
      const received = parseFloat(item.received_qty) || 0;
      const remaining = ordered - received;
      const status = remaining <= 0 ? 'received' : (received > 0 ? 'partial' : 'pending');
      const statusLabel = remaining <= 0 ? 'Received' : (received > 0 ? 'Partial' : 'Pending');

      html += `<tr data-index="${index}">
      <td><strong>${item.product_name}</strong><br><small style="color:var(--color-text-secondary)">${item.uom_name}</small></td>
      <td class="center"><span class="rcv-qty-display">${ordered.toFixed(3)}</span></td>
      <td class="center"><span class="rcv-qty-received">${received.toFixed(3)}</span></td>
      <td class="center">
        <input type="number" class="rcv-item-input" id="rcvQty${index}" value="${remaining > 0 ? remaining : 0}" min="0" max="${remaining}" step="0.001" onchange="updateRcvItemStatus(${index})">
      </td>
      <td class="center"><span class="rcv-item-status ${status}" id="rcvStatus${index}">${statusLabel}</span></td>
    </tr>`;
    });

    tbody.innerHTML = html;
  }

  function updateRcvItemStatus(index) {
    const input = document.getElementById(`rcvQty${index}`);
    const statusSpan = document.getElementById(`rcvStatus${index}`);
    const item = rcvCurrentPO.items[index];
    const ordered = parseFloat(item.ordered_qty) || 0;
    const alreadyReceived = parseFloat(item.received_qty) || 0;
    const toReceive = parseFloat(input.value) || 0;

    const totalAfter = alreadyReceived + toReceive;
    let status = 'pending';
    let label = 'Pending';

    if (totalAfter >= ordered) {
      status = 'received';
      label = 'Will be Received';
    } else if (toReceive > 0) {
      status = 'partial';
      label = 'Partial';
    }

    statusSpan.className = `rcv-item-status ${status}`;
    statusSpan.textContent = label;
  }

  function closeReceiveModal() {
    rcvModalClose(document.getElementById('receiveModal'));
    rcvCurrentPO = null;
  }

  async function submitReceive() {
    if (!rcvCurrentPO) return;

    const items = [];
    let hasItemsToReceive = false;

    rcvCurrentPO.items.forEach((item, index) => {
      const input = document.getElementById(`rcvQty${index}`);
      const toReceive = parseFloat(input.value) || 0;
      if (toReceive > 0) {
        hasItemsToReceive = true;
        items.push({
          po_item_id: item.po_item_id,
          product_id: item.product_id,
          uom_id: item.uom_id,
          ordered_qty: item.ordered_qty,
          received_qty: toReceive
        });
      }
    });

    if (!hasItemsToReceive) {
      showRcvAlert('No Items', 'Please enter quantities for items to receive.', null);
      return;
    }

    const fd = new FormData();
    fd.append('action', 'receive');
    fd.append('po_id', rcvCurrentPO.po_id);
    fd.append('items_json', JSON.stringify(items));
    fd.append('notes', 'Received via PO Receiving page');
    fd.append('csrf_token', csrfToken);

    try {
      const res = await fetch('../api/purchase_orders.php', { method: 'POST', body: fd });
      const json = await res.json();
      if (json.success) {
        closeReceiveModal();
        fetchReceivingPOs();
        showRcvAlert('Success', 'Items received successfully and inventory updated.', null);
      } else {
        showRcvAlert('Error', json.message || 'Error receiving items', null);
      }
    } catch (e) {
      showRcvAlert('Error', 'Error receiving items', null);
    }
  }

  function showRcvAlert(title, message, callback) {
    document.getElementById('rcvAlertTitle').textContent = title;
    document.getElementById('rcvAlertMessage').textContent = message;
    rcvAlertCallback = callback;
    const confirmBtn = document.getElementById('rcvAlertConfirmBtn');
    if (callback) {
      confirmBtn.style.display = 'inline-flex';
      confirmBtn.onclick = () => {
        closeRcvAlert();
        if (rcvAlertCallback) rcvAlertCallback();
      };
    } else {
      confirmBtn.style.display = 'none';
    }
    document.getElementById('rcvAlertOverlay').classList.add('open');
  }

  function closeRcvAlert() {
    document.getElementById('rcvAlertOverlay').classList.remove('open');
    rcvAlertCallback = null;
  }

  fetchReceivingPOs();
</script>