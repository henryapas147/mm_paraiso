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
    --prod-cyan: #00838F;
    --prod-cyan-pale: #E0F7FA;
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

  .uom-page {
    padding: 28px 28px 40px;
    max-width: 100%;
    margin: 0 auto;
    background: var(--color-background-tertiary, #F4F5F7);
    min-height: 100%;
    border-radius: var(--border-radius-xl, 18px);
    font-family: var(--prod-font);
  }

  .uom-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
    gap: 16px;
    flex-wrap: wrap;
  }

  .uom-page-eyebrow {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--prod-blue);
    margin-bottom: 4px;
  }

  .uom-page-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.4px;
    line-height: 1.2;
  }

  .uom-page-subtitle {
    font-size: 14px;
    color: var(--color-text-secondary);
    margin-top: 3px;
    font-weight: 400;
  }

  .uom-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
  }

  .uom-stat-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    padding: 20px 22px;
    box-shadow: var(--prod-shadow-sm);
    position: relative;
    overflow: hidden;
    transition: box-shadow .2s, transform .2s;
  }

  .uom-stat-card:hover {
    box-shadow: var(--prod-shadow-md);
    transform: translateY(-1px);
  }

  .uom-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--prod-radius-lg) var(--prod-radius-lg) 0 0;
    background: var(--_accent, #CBD5E1);
  }

  .uom-stat-card.blue {
    --_accent: var(--prod-blue);
  }

  .uom-stat-card.green {
    --_accent: var(--prod-green);
  }

  .uom-stat-card.purple {
    --_accent: var(--prod-purple);
  }

  .uom-stat-card.cyan {
    --_accent: var(--prod-cyan);
  }

  .uom-stat-card.amber {
    --_accent: var(--prod-amber);
  }

  .uom-stat-icon {
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

  .uom-stat-card.blue .uom-stat-icon {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
  }

  .uom-stat-card.green .uom-stat-icon {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .uom-stat-card.purple .uom-stat-icon {
    background: var(--prod-purple-pale);
    color: var(--prod-purple);
  }

  .uom-stat-card.cyan .uom-stat-icon {
    background: var(--prod-cyan-pale);
    color: var(--prod-cyan);
  }

  .uom-stat-card.amber .uom-stat-icon {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
  }

  .uom-stat-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-secondary);
    letter-spacing: .02em;
    margin-bottom: 6px;
  }

  .uom-stat-value {
    font-size: 32px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.5px;
    line-height: 1;
    font-family: var(--prod-mono);
  }

  .uom-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    box-shadow: var(--prod-shadow-sm);
    overflow: hidden;
  }

  .uom-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    background: var(--color-background-primary, #fff);
  }

  .uom-card-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.2px;
  }

  .uom-card-count {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-top: 1px;
  }

  .uom-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .uom-search-wrap {
    position: relative;
  }

  .uom-search-wrap i {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-text-secondary);
    font-size: 12px;
    pointer-events: none;
  }

  .uom-search {
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

  .uom-search:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    background: var(--color-background-primary, #fff);
  }

  .uom-btn {
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

  .uom-btn:hover {
    background: var(--color-background-secondary, #F8FAFC);
    border-color: rgba(0, 0, 0, .18);
  }

  .uom-btn-primary {
    background: var(--prod-blue);
    color: #fff;
    border-color: var(--prod-blue);
    box-shadow: 0 1px 3px rgba(26, 107, 191, .3);
  }

  .uom-btn-primary:hover {
    background: var(--prod-blue-dark);
    border-color: var(--prod-blue-dark);
    box-shadow: 0 2px 6px rgba(26, 107, 191, .35);
  }

  .uom-table-wrap {
    overflow-x: auto;
  }

  .uom-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    font-family: var(--prod-font);
  }

  .uom-table thead tr {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .uom-table thead th {
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

  .uom-table thead th.center {
    text-align: center;
  }

  .uom-table thead th.right {
    text-align: right;
  }

  .uom-table tbody tr {
    border-bottom: 1px solid rgba(0, 0, 0, .05);
    transition: background .12s;
  }

  .uom-table tbody tr:last-child {
    border-bottom: none;
  }

  .uom-table tbody tr:hover {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .uom-table td {
    padding: 13px 18px;
    vertical-align: middle;
    white-space: nowrap;
  }

  .uom-name-cell {
    display: flex;
    align-items: center;
  }

  .uom-avatar {
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
    letter-spacing: -.05em;
    margin-right: 10px;
  }

  .uom-avatar.weight {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
  }

  .uom-avatar.volume {
    background: var(--prod-purple-pale);
    color: var(--prod-purple);
  }

  .uom-avatar.length {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .uom-avatar.area {
    background: var(--prod-cyan-pale);
    color: var(--prod-cyan);
  }

  .uom-avatar.count {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
  }

  .uom-avatar.other {
    background: var(--prod-purple-pale);
    color: var(--prod-purple);
  }

  .uom-name-primary {
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-primary);
    letter-spacing: -.1px;
  }

  .uom-code {
    font-size: 11px;
    color: var(--color-text-secondary);
    margin-top: 2px;
    font-family: var(--prod-mono);
    font-weight: 400;
    text-transform: uppercase;
  }

  .uom-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .02em;
    text-transform: capitalize;
  }

  .uom-badge.count {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
  }

  .uom-badge.weight {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
  }

  .uom-badge.volume {
    background: var(--prod-purple-pale);
    color: var(--prod-purple);
  }

  .uom-badge.length {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .uom-badge.area {
    background: var(--prod-cyan-pale);
    color: var(--prod-cyan);
  }

  .uom-badge.other {
    background: var(--color-background-secondary, #F1F5F9);
    color: var(--color-text-secondary);
  }

  .uom-badge.active {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .uom-actions-cell {
    display: flex;
    align-items: center;
    gap: 5px;
    justify-content: flex-end;
  }

  .uom-action-btn {
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

  .uom-action-btn:hover {
    background: var(--color-background-secondary);
    color: var(--color-text-primary);
    border-color: rgba(0, 0, 0, .15);
  }

  .uom-action-btn.view:hover {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
    border-color: rgba(26, 107, 191, .2);
  }

  .uom-action-btn.edit:hover {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
    border-color: rgba(230, 81, 0, .2);
  }

  .uom-action-btn.danger:hover {
    background: var(--prod-red-pale);
    color: var(--prod-red);
    border-color: rgba(198, 40, 40, .2);
  }

  .uom-empty-state {
    padding: 64px 24px;
    text-align: center;
    color: var(--color-text-secondary);
  }

  .uom-empty-icon {
    font-size: 32px;
    opacity: .25;
    margin-bottom: 12px;
  }

  .uom-empty-text {
    font-size: 14px;
    font-weight: 500;
    color: var(--color-text-secondary);
    margin-bottom: 4px;
  }

  .uom-empty-sub {
    font-size: 12px;
    color: var(--color-text-secondary);
    opacity: .7;
  }

  .uom-overlay {
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

  .uom-overlay.open {
    display: flex;
  }

  .uom-modal {
    background: var(--color-background-primary, #fff);
    border-radius: var(--prod-radius-xl);
    border: 1px solid rgba(0, 0, 0, .08);
    width: 100%;
    max-width: 500px;
    max-height: 92vh;
    overflow-y: auto;
    box-shadow: var(--prod-shadow-lg);
    animation: modalIn .2s cubic-bezier(.22, .68, 0, 1.2);
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

  .uom-modal-header {
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

  .uom-modal-title {
    font-size: 17px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.3px;
  }

  .uom-modal-subtitle {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-top: 2px;
  }

  .uom-modal-close {
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

  .uom-modal-close:hover {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .uom-modal-body {
    padding: 26px;
  }

  .uom-modal-footer {
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

  .uom-form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 18px;
  }

  .uom-form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .uom-form-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .07em;
  }

  .uom-form-label .req {
    color: var(--prod-red);
    margin-left: 2px;
  }

  .uom-form-input,
  .uom-form-select {
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

  .uom-form-input:focus,
  .uom-form-select:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    background: var(--color-background-primary, #fff);
  }

  .uom-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 28px;
  }

  .uom-details-section-title {
    font-size: 11px;
    font-weight: 700;
    color: var(--prod-blue);
    text-transform: uppercase;
    letter-spacing: .1em;
    margin-bottom: 14px;
    padding-bottom: 8px;
    border-bottom: 2px solid var(--prod-blue-pale);
  }

  .uom-detail-row {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 12px;
    padding: 7px 0;
    border-bottom: 1px solid rgba(0, 0, 0, .04);
  }

  .uom-detail-row:last-child {
    border-bottom: none;
  }

  .uom-detail-key {
    font-size: 12px;
    color: var(--color-text-secondary);
    flex-shrink: 0;
    font-weight: 500;
  }

  .uom-detail-val {
    font-size: 13px;
    color: var(--color-text-primary);
    font-weight: 500;
    text-align: right;
  }

  /* Pagination */
  .uom-pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    background: var(--color-background-primary, #fff);
    gap: 16px;
    flex-wrap: wrap;
  }

  .uom-pagination-info {
    font-size: 12px;
    color: var(--color-text-secondary);
  }

  .uom-pagination {
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .uom-page-btn {
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

  .uom-page-btn:hover:not(:disabled) {
    background: var(--prod-blue-pale);
    border-color: var(--prod-blue);
    color: var(--prod-blue);
  }

  .uom-page-btn.active {
    background: var(--prod-blue);
    border-color: var(--prod-blue);
    color: #fff;
  }

  .uom-page-btn:disabled {
    opacity: .4;
    cursor: not-allowed;
  }

  .uom-entries-select {
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

  .uom-entries-select:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    outline: none;
  }

  .uom-entries-label {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-right: 8px;
  }

  .uom-entries-wrap {
    display: flex;
    align-items: center;
  }

  @media (max-width: 600px) {
    .uom-details-grid {
      grid-template-columns: 1fr;
    }

    .uom-search {
      width: 160px;
    }
  }
</style>

<div class="uom-page">
  <!-- Header -->
  <div class="uom-page-header">
    <div>
      <div class="uom-page-eyebrow"><i class="fas fa-ruler-combined" style="margin-right:5px"></i>Inventory</div>
      <div class="uom-page-title">Units of Measure</div>
      <div class="uom-page-subtitle">Manage measurement units for products</div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="uom-stats-grid">
    <div class="uom-stat-card blue">
      <div class="uom-stat-icon"><i class="fas fa-cubes"></i></div>
      <div class="uom-stat-label">Total UOMs</div>
      <div class="uom-stat-value" id="statTotal">—</div>
    </div>
    <div class="uom-stat-card green">
      <div class="uom-stat-icon"><i class="fas fa-hashtag"></i></div>
      <div class="uom-stat-label">Count Type</div>
      <div class="uom-stat-value" id="statCount">—</div>
    </div>
    <div class="uom-stat-card amber">
      <div class="uom-stat-icon"><i class="fas fa-weight-hanging"></i></div>
      <div class="uom-stat-label">Weight Type</div>
      <div class="uom-stat-value" id="statWeight">—</div>
    </div>
    <div class="uom-stat-card purple">
      <div class="uom-stat-icon"><i class="fas fa-flask"></i></div>
      <div class="uom-stat-label">Volume Type</div>
      <div class="uom-stat-value" id="statVolume">—</div>
    </div>
  </div>

  <!-- UOM Table -->
  <div class="uom-card">
    <div class="uom-card-header">
      <div>
        <div class="uom-card-title">All Units</div>
        <div class="uom-card-count" id="tableCount">Loading…</div>
      </div>
      <div class="uom-toolbar">
        <div class="uom-entries-wrap">
          <span class="uom-entries-label">Show</span>
          <select class="uom-entries-select" id="entriesPerPage" onchange="changeEntriesPerPage()">
            <option value="10">10</option>
            <option value="25" selected>25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="uom-entries-label">entries</span>
        </div>
        <div class="uom-search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" id="searchInput" class="uom-search" placeholder="Search units…" oninput="filterTable()">
        </div>
        <button onclick="openAddUomModal()" class="uom-btn uom-btn-primary">
          <i class="fas fa-plus"></i> Add UOM
        </button>
      </div>
    </div>
    <div class="uom-table-wrap">
      <table class="uom-table" id="uomsTable">
        <thead>
          <tr>
            <th>Unit</th>
            <th>Code</th>
            <th>Type</th>
            <th class="center">Status</th>
            <th class="right">Actions</th>
          </tr>
        </thead>
        <tbody id="uomsTableBody">
          <tr>
            <td colspan="5">
              <div class="uom-empty-state">
                <div class="uom-empty-icon"><i class="fas fa-spinner fa-spin"></i></div>
                <div class="uom-empty-text">Loading units…</div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="uom-pagination-wrap" id="paginationWrap" style="display:none">
      <div class="uom-pagination-info" id="paginationInfo">Showing 1-25 of 100 entries</div>
      <div class="uom-pagination" id="paginationControls"></div>
    </div>
  </div>

  <!-- UOM Details Modal -->
  <div class="uom-overlay" id="uomDetailsModal" onclick="if(event.target===this)closeUomDetailsModal()">
    <div class="uom-modal" role="dialog" aria-modal="true" aria-labelledby="modalUomName">
      <div class="uom-modal-header">
        <div>
          <div class="uom-modal-title" id="modalUomName">Unit Details</div>
          <div class="uom-modal-subtitle" id="modalUomCode"></div>
        </div>
        <button class="uom-modal-close" onclick="closeUomDetailsModal()" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="uom-modal-body" id="modalUomContent"></div>
    </div>
  </div>

  <!-- Add UOM Modal -->
  <div class="uom-overlay" id="addUomModal" onclick="if(event.target===this)closeAddUomModal()">
    <div class="uom-modal" role="dialog" aria-modal="true">
      <div class="uom-modal-header">
        <div>
          <div class="uom-modal-title">Add New Unit</div>
          <div class="uom-modal-subtitle">Create a new unit of measure</div>
        </div>
        <button class="uom-modal-close" onclick="closeAddUomModal()" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <form id="addUomForm" onsubmit="submitAddUom(event)">
        <div class="uom-modal-body">
          <div class="uom-form-grid">
            <div class="uom-form-group">
              <label class="uom-form-label">UOM Code <span class="req">*</span></label>
              <input type="text" name="uom_code" required class="uom-form-input" placeholder="e.g., PCS, KG, LTR">
            </div>
            <div class="uom-form-group">
              <label class="uom-form-label">UOM Name <span class="req">*</span></label>
              <input type="text" name="uom_name" required class="uom-form-input" placeholder="e.g., Pieces, Kilograms, Liters">
            </div>
            <div class="uom-form-group">
              <label class="uom-form-label">UOM Type <span class="req">*</span></label>
              <select name="uom_type" required class="uom-form-select">
                <option value="">Select Type</option>
                <option value="count">Count</option>
                <option value="weight">Weight</option>
                <option value="volume">Volume</option>
                <option value="length">Length</option>
                <option value="area">Area</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>
        </div>
        <div class="uom-modal-footer">
          <button type="button" class="uom-btn" onclick="closeAddUomModal()">
            <i class="fas fa-times"></i> Cancel
          </button>
          <button type="submit" class="uom-btn uom-btn-primary">
            <i class="fas fa-save"></i> Save UOM
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit UOM Modal -->
  <div class="uom-overlay" id="editUomModal" onclick="if(event.target===this)closeEditUomModal()">
    <div class="uom-modal" role="dialog" aria-modal="true">
      <div class="uom-modal-header">
        <div>
          <div class="uom-modal-title">Edit Unit</div>
          <div class="uom-modal-subtitle">Update unit details</div>
        </div>
        <button class="uom-modal-close" onclick="closeEditUomModal()" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <form id="editUomForm" onsubmit="submitEditUom(event)">
        <div class="uom-modal-body">
          <div class="uom-form-grid">
            <input type="hidden" name="uom_id" id="editUomId">
            <div class="uom-form-group">
              <label class="uom-form-label">UOM Code <span class="req">*</span></label>
              <input type="text" name="uom_code" id="editUomCode" required class="uom-form-input" placeholder="e.g., PCS, KG, LTR">
            </div>
            <div class="uom-form-group">
              <label class="uom-form-label">UOM Name <span class="req">*</span></label>
              <input type="text" name="uom_name" id="editUomName" required class="uom-form-input" placeholder="e.g., Pieces, Kilograms, Liters">
            </div>
            <div class="uom-form-group">
              <label class="uom-form-label">UOM Type <span class="req">*</span></label>
              <select name="uom_type" id="editUomType" required class="uom-form-select">
                <option value="">Select Type</option>
                <option value="count">Count</option>
                <option value="weight">Weight</option>
                <option value="volume">Volume</option>
                <option value="length">Length</option>
                <option value="area">Area</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>
        </div>
        <div class="uom-modal-footer">
          <button type="button" class="uom-btn" onclick="closeEditUomModal()">
            <i class="fas fa-times"></i> Cancel
          </button>
          <button type="submit" class="uom-btn uom-btn-primary">
            <i class="fas fa-save"></i> Update UOM
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    let uomsData = [];
    let filteredData = [];
    let currentPage = 1;
    let entriesPerPage = 25;

    const csrfToken = '<?php echo htmlspecialchars(CSRF_TOKEN, ENT_QUOTES, 'UTF-8'); ?>';

    document.addEventListener('DOMContentLoaded', function() {
      fetchUoms();
    });

    async function fetchUoms() {
      try {
        const res = await fetch('../api/units_of_measure.php?action=list');
        const result = await res.json();
        if (result.success) {
          uomsData = result.data;
          filteredData = [...uomsData];
          currentPage = 1;
          renderTable();
          renderStats();
        } else {
          showEmptyState(result.message);
        }
      } catch (e) {
        showEmptyState('Failed to load data');
      }
    }

    function showEmptyState(msg) {
      document.getElementById('uomsTableBody').innerHTML = `
      <tr><td colspan="5">
        <div class="uom-empty-state">
          <div class="uom-empty-icon"><i class="fas fa-circle-xmark"></i></div>
          <div class="uom-empty-text">${msg}</div>
        </div>
      </td></tr>`;
    }

    function renderStats() {
      const total = uomsData.length;
      const count = uomsData.filter(u => u.uom_type === 'count').length;
      const weight = uomsData.filter(u => u.uom_type === 'weight').length;
      const volume = uomsData.filter(u => u.uom_type === 'volume').length;

      const pairs = [
        ['statTotal', total],
        ['statCount', count],
        ['statWeight', weight],
        ['statVolume', volume]
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
      const tbody = document.getElementById('uomsTableBody');
      const count = document.getElementById('tableCount');

      if (!filteredData.length) {
        count.textContent = '0 units';
        document.getElementById('paginationWrap').style.display = 'none';
        tbody.innerHTML = `
        <tr><td colspan="5">
          <div class="uom-empty-state">
            <div class="uom-empty-icon"><i class="fas fa-ruler-combined"></i></div>
            <div class="uom-empty-text">No units found</div>
            <div class="uom-empty-sub"><a href="javascript:;" onclick="openAddUomModal()" style="color:var(--prod-blue);font-weight:500">Add your first unit</a></div>
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

      count.textContent = `${totalEntries} unit${totalEntries !== 1 ? 's' : ''}`;

      // Show/hide pagination based on total entries
      const paginationWrap = document.getElementById('paginationWrap');
      if (totalEntries > 10) {
        paginationWrap.style.display = 'flex';
        renderPagination(totalPages, start, end, totalEntries);
      } else {
        paginationWrap.style.display = 'none';
      }

      tbody.innerHTML = paginatedData.map(u => {
        const typeClass = u.uom_type || 'other';
        const typeIcon = {
          'count': 'fa-hashtag',
          'weight': 'fa-weight-hanging',
          'volume': 'fa-flask',
          'length': 'fa-ruler',
          'area': 'fa-square',
          'other': 'fa-ellipsis-h'
        }[typeClass] || 'fa-ruler';

        return `
      <tr class="uom-row">
        <td>
          <div class="uom-name-cell">
            <div class="uom-avatar ${typeClass}"><i class="fas ${typeIcon}"></i></div>
            <div>
              <div class="uom-name-primary">${u.uom_name}</div>
            </div>
          </div>
        </td>
        <td>
          <span class="uom-code">${u.uom_code}</span>
        </td>
        <td>
          <span class="uom-badge ${typeClass}">${ucfirst(u.uom_type)}</span>
        </td>
        <td style="text-align:center">
          <span class="uom-badge active">Active</span>
        </td>
        <td>
          <div class="uom-actions-cell">
            <button onclick="viewUomDetails(${u.uom_id})" class="uom-action-btn view"><i class="fas fa-eye"></i> View</button>
            <button onclick="openEditUomModal(${u.uom_id})" class="uom-action-btn edit"><i class="fas fa-pen"></i> Edit</button>
            <button onclick="deleteUom(${u.uom_id}, '${u.uom_name.replace(/'/g, "\\'")}')" class="uom-action-btn danger"><i class="fas fa-trash-alt"></i></button>
          </div>
        </td>
      </tr>`;
      }).join('');
    }

    function filterTable() {
      const filter = document.getElementById('searchInput').value.toLowerCase();
      currentPage = 1;

      if (!filter) {
        filteredData = [...uomsData];
      } else {
        filteredData = uomsData.filter(u =>
          u.uom_name.toLowerCase().includes(filter) ||
          u.uom_code.toLowerCase().includes(filter) ||
          (u.uom_type && u.uom_type.toLowerCase().includes(filter))
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
      html += `<button class="uom-page-btn" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`;

      const maxButtons = 5;
      let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
      let endPage = Math.min(totalPages, startPage + maxButtons - 1);
      if (endPage - startPage < maxButtons - 1) {
        startPage = Math.max(1, endPage - maxButtons + 1);
      }

      if (startPage > 1) {
        html += `<button class="uom-page-btn" onclick="goToPage(1)">1</button>`;
        if (startPage > 2) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
      }

      for (let i = startPage; i <= endPage; i++) {
        html += `<button class="uom-page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
      }

      if (endPage < totalPages) {
        if (endPage < totalPages - 1) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
        html += `<button class="uom-page-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
      }

      html += `<button class="uom-page-btn" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`;
      paginationControls.innerHTML = html;
    }

    function viewUomDetails(uomId) {
      const uom = uomsData.find(u => u.uom_id == uomId);
      if (!uom) return;

      document.getElementById('modalUomName').textContent = uom.uom_name;
      document.getElementById('modalUomCode').textContent = uom.uom_code;

      const typeClass = uom.uom_type || 'other';

      document.getElementById('modalUomContent').innerHTML = `
      <div class="uom-details-grid">
        <div>
          <div class="uom-details-section-title"><i class="fas fa-info-circle" style="margin-right:5px;opacity:.6"></i>Basic Information</div>
          <div class="uom-detail-row"><div class="uom-detail-key">UOM Code</div><div class="uom-detail-val">${uom.uom_code}</div></div>
          <div class="uom-detail-row"><div class="uom-detail-key">UOM Name</div><div class="uom-detail-val">${uom.uom_name}</div></div>
          <div class="uom-detail-row"><div class="uom-detail-key">UOM Type</div><div class="uom-detail-val"><span class="uom-badge ${typeClass}">${ucfirst(uom.uom_type)}</span></div></div>
        </div>
        <div>
          <div class="uom-details-section-title" style="margin-top:20px"><i class="fas fa-sliders" style="margin-right:5px;opacity:.6"></i>Settings</div>
          <div class="uom-detail-row"><div class="uom-detail-key">Status</div><div class="uom-detail-val"><span class="uom-badge active">Active</span></div></div>
        </div>
      </div>`;

      document.getElementById('uomDetailsModal').classList.add('open');
    }

    function closeUomDetailsModal() {
      document.getElementById('uomDetailsModal').classList.remove('open');
    }

    function openAddUomModal() {
      document.getElementById('addUomModal').classList.add('open');
    }

    function closeAddUomModal() {
      document.getElementById('addUomModal').classList.remove('open');
      document.getElementById('addUomForm').reset();
    }

    function submitAddUom(event) {
      event.preventDefault();
      const form = document.getElementById('addUomForm');
      const formData = new FormData(form);

      Swal.fire({
        title: 'Adding Unit…',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
        customClass: {
          popup: 'swal2-popup-high-z'
        }
      });

      const payload = {
        csrf_token: csrfToken,
        uom_code: formData.get('uom_code'),
        uom_name: formData.get('uom_name'),
        uom_type: formData.get('uom_type')
      };

      fetch('../api/units_of_measure.php?action=create', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(data => {
          closeAddUomModal();
          if (data.success) {
            Swal.fire({
              icon: 'success',
              title: 'Unit Added!',
              text: data.message,
              confirmButtonColor: '#1A6BBF'
            }).then(() => fetchUoms());
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
          closeAddUomModal();
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred.',
            confirmButtonColor: '#C62828'
          });
        });
    }

    function openEditUomModal(uomId) {
      const uom = uomsData.find(u => u.uom_id == uomId);
      if (!uom) return;

      document.getElementById('editUomId').value = uom.uom_id;
      document.getElementById('editUomCode').value = uom.uom_code;
      document.getElementById('editUomName').value = uom.uom_name;
      document.getElementById('editUomType').value = uom.uom_type;

      document.getElementById('editUomModal').classList.add('open');
    }

    function closeEditUomModal() {
      document.getElementById('editUomModal').classList.remove('open');
      document.getElementById('editUomForm').reset();
    }

    function submitEditUom(event) {
      event.preventDefault();
      const form = document.getElementById('editUomForm');
      const formData = new FormData(form);

      Swal.fire({
        title: 'Updating…',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
        customClass: {
          popup: 'swal2-popup-high-z'
        }
      });

      const payload = {
        csrf_token: csrfToken,
        uom_id: formData.get('uom_id'),
        uom_code: formData.get('uom_code'),
        uom_name: formData.get('uom_name'),
        uom_type: formData.get('uom_type')
      };

      fetch('../api/units_of_measure.php?action=update', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(data => {
          closeEditUomModal();
          if (data.success) {
            Swal.fire({
              icon: 'success',
              title: 'Updated!',
              text: data.message,
              confirmButtonColor: '#1A6BBF'
            }).then(() => fetchUoms());
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
          closeEditUomModal();
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred.',
            confirmButtonColor: '#C62828'
          });
        });
    }

    function deleteUom(uomId, uomName) {
      Swal.fire({
        title: 'Delete unit?',
        html: `<span style="font-size:14px">You're about to delete <strong>${uomName}</strong>. This action cannot be undone.</span>`,
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

          fetch('../api/units_of_measure.php?action=delete', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                csrf_token: csrfToken,
                uom_id: uomId
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
                }).then(() => fetchUoms());
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

    function ucfirst(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    }
  </script>