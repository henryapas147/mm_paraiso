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

  .trf-page {
    padding: 28px 28px 40px;
    max-width: 100%;
    margin: 0 auto;
    background: var(--color-background-tertiary, #F4F5F7);
    min-height: 100%;
    border-radius: var(--border-radius-xl, 18px);
    font-family: var(--prod-font);
  }

  .trf-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
    gap: 16px;
    flex-wrap: wrap;
  }

  .trf-page-eyebrow {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--prod-blue);
    margin-bottom: 4px;
  }

  .trf-page-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.4px;
    line-height: 1.2;
  }

  .trf-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
  }

  .trf-stat-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    padding: 20px 22px;
    box-shadow: var(--prod-shadow-sm);
    position: relative;
    overflow: hidden;
  }

  .trf-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--prod-radius-lg) var(--prod-radius-lg) 0 0;
    background: var(--_accent, #CBD5E1);
  }

  .trf-stat-card.blue {
    --_accent: var(--prod-blue);
  }

  .trf-stat-card.amber {
    --_accent: var(--prod-amber);
  }

  .trf-stat-card.purple {
    --_accent: var(--prod-purple);
  }

  .trf-stat-card.green {
    --_accent: var(--prod-green);
  }

  .trf-stat-icon {
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

  .trf-stat-card.amber .trf-stat-icon {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
  }

  .trf-stat-card.purple .trf-stat-icon {
    background: var(--prod-purple-pale);
    color: var(--prod-purple);
  }

  .trf-stat-card.green .trf-stat-icon {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .trf-stat-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-secondary);
    margin-bottom: 6px;
  }

  .trf-stat-value {
    font-size: 32px;
    font-weight: 600;
    color: var(--color-text-primary);
    font-family: var(--prod-mono);
  }

  .trf-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    box-shadow: var(--prod-shadow-sm);
    overflow: hidden;
  }

  .trf-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }

  .trf-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .trf-search-wrap {
    position: relative;
  }

  .trf-search-wrap i {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-text-secondary);
    font-size: 12px;
    pointer-events: none;
  }

  .trf-search {
    padding: 8px 14px 8px 34px;
    font-size: 13px;
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--prod-radius-md);
    background: var(--color-background-secondary, #F8FAFC);
    color: var(--color-text-primary);
    outline: none;
    width: 230px;
  }

  .trf-search:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
  }

  .trf-btn {
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
  }

  .trf-btn:hover {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .trf-btn-primary {
    background: var(--prod-blue);
    color: #fff;
    border-color: var(--prod-blue);
    box-shadow: 0 1px 3px rgba(26, 107, 191, .3);
  }

  .trf-btn-primary:hover {
    background: var(--prod-blue-dark);
  }

  .trf-table-wrap {
    overflow-x: auto;
  }

  .trf-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }

  .trf-table thead tr {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .trf-table th {
    padding: 11px 18px;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .08em;
    border-bottom: 1px solid rgba(0, 0, 0, .07);
  }

  .trf-table td {
    padding: 13px 18px;
    vertical-align: middle;
  }

  .trf-name-cell {
    display: flex;
    align-items: center;
  }

  .trf-avatar {
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
    margin-right: 10px;
  }

  .trf-name-primary {
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-primary);
  }

  .trf-name-secondary {
    font-size: 11px;
    color: var(--color-text-secondary);
  }

  .trf-badge {
    display: inline-flex;
    padding: 4px 10px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 600;
  }

  .trf-badge.pending {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
  }

  .trf-badge.in_transit {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
  }

  .trf-badge.completed {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .trf-badge.cancelled {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .trf-actions {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
  }

  .trf-action-btn {
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

  .trf-action-btn.view:hover {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
  }

  .trf-action-btn.edit:hover {
    background: var(--prod-amber-pale);
    color: var(--prod-amber);
  }

  .trf-empty {
    padding: 64px;
    text-align: center;
    color: var(--color-text-secondary);
  }

  .trf-empty-icon {
    font-size: 32px;
    opacity: .25;
    margin-bottom: 12px;
  }

  .trf-pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    background: var(--color-background-primary, #fff);
    gap: 16px;
    flex-wrap: wrap;
  }

  .trf-pagination-info {
    font-size: 12px;
    color: var(--color-text-secondary);
  }

  .trf-pagination {
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .trf-page-btn {
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

  .trf-page-btn:hover:not(:disabled) {
    background: var(--prod-blue-pale);
    border-color: var(--prod-blue);
    color: var(--prod-blue);
  }

  .trf-page-btn.active {
    background: var(--prod-blue);
    border-color: var(--prod-blue);
    color: #fff;
  }

  .trf-page-btn:disabled {
    opacity: .4;
    cursor: not-allowed;
  }

  .trf-entries-select {
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

  .trf-entries-select:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    outline: none;
  }

  .trf-entries-label {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-right: 8px;
  }

  .trf-entries-wrap {
    display: flex;
    align-items: center;
  }

  @media (max-width: 600px) {
    .trf-search {
      width: 160px;
    }
  }
</style>

<div class="trf-page">
  <div class="trf-page-header">
    <div>
      <div class="trf-page-eyebrow"><i class="fas fa-random" style="margin-right:5px"></i>Inventory</div>
      <div class="trf-page-title">Stock Transfers</div>
    </div>
  </div>

  <div class="trf-stats-grid">
    <div class="trf-stat-card blue">
      <div class="trf-stat-icon"><i class="fas fa-random"></i></div>
      <div class="trf-stat-label">Total Transfers</div>
      <div class="trf-stat-value" id="statTotal">—</div>
    </div>
    <div class="trf-stat-card amber">
      <div class="trf-stat-icon"><i class="fas fa-clock"></i></div>
      <div class="trf-stat-label">Pending</div>
      <div class="trf-stat-value" id="statPending">—</div>
    </div>
    <div class="trf-stat-card purple">
      <div class="trf-stat-icon"><i class="fas fa-truck"></i></div>
      <div class="trf-stat-label">In Transit</div>
      <div class="trf-stat-value" id="statInTransit">—</div>
    </div>
    <div class="trf-stat-card green">
      <div class="trf-stat-icon"><i class="fas fa-check-circle"></i></div>
      <div class="trf-stat-label">Completed</div>
      <div class="trf-stat-value" id="statCompleted">—</div>
    </div>
  </div>

  <div class="trf-card">
    <div class="trf-card-header">
      <div>
        <strong>All Transfers</strong>
        <span id="tableCount" style="font-size:12px;color:var(--color-text-secondary);margin-left:8px">Loading…</span>
      </div>
      <div class="trf-toolbar">
        <div class="trf-entries-wrap">
          <span class="trf-entries-label">Show</span>
          <select class="trf-entries-select" id="entriesPerPage" onchange="changeEntriesPerPage()">
            <option value="10">10</option>
            <option value="25" selected>25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="trf-entries-label">entries</span>
        </div>
        <div class="trf-search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" id="searchInput" class="trf-search" placeholder="Search transfers…" oninput="filterTable()">
        </div>
        <button onclick="openAddTransferModal()" class="trf-btn trf-btn-primary">
          <i class="fas fa-plus"></i> New Transfer
        </button>
      </div>
    </div>
    <div class="trf-table-wrap">
      <table class="trf-table" id="transfersTable">
        <thead>
          <tr>
            <th>Transfer #</th>
            <th>Product</th>
            <th>From → To</th>
            <th>Date</th>
            <th style="text-align:center">Status</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="transfersTableBody">
          <tr>
            <td colspan="6" class="trf-empty">
              <div class="trf-empty-icon"><i class="fas fa-spinner fa-spin"></i></div>
              <div>Loading transfers…</div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="trf-pagination-wrap" id="paginationWrap" style="display:none">
      <div class="trf-pagination-info" id="paginationInfo">Showing 1-25 of 100 entries</div>
      <div class="trf-pagination" id="paginationControls"></div>
    </div>
  </div>
</div>

<script>
  let transfersData = [];
  let filteredData = [];
  let currentPage = 1;
  let entriesPerPage = 25;
  let productsData = [];
  let locationsData = [];
  let uomsData = [];

  async function fetchTransfers() {
    try {
      const res = await fetch('../api/stock_transfers.php?action=list');
      const json = await res.json();
      if (json.success) {
        transfersData = json.data;
        filteredData = [...transfersData];
        currentPage = 1;
        renderStats();
        renderTable();
      }
    } catch (e) {
      console.error('Error fetching transfers:', e);
      document.getElementById('transfersTableBody').innerHTML = `<tr><td colspan="6" class="trf-empty"><div class="trf-empty-icon"><i class="fas fa-circle-xmark"></i></div><div>Failed to load data.</div></td></tr>`;
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/stock_transfers.php?action=related');
      const json = await res.json();
      if (json.success) {
        productsData = json.data.products;
        locationsData = json.data.locations;
        uomsData = json.data.uoms;
        populateDropdowns();
      }
    } catch (e) {
      console.error('Error fetching related data:', e);
    }
  }

  function renderStats() {
    const total = transfersData.length;
    const pending = transfersData.filter(t => t.transfer_status === 'pending').length;
    const inTransit = transfersData.filter(t => t.transfer_status === 'in_transit').length;
    const completed = transfersData.filter(t => t.transfer_status === 'completed').length;

    const pairs = [
      ['statTotal', total],
      ['statPending', pending],
      ['statInTransit', inTransit],
      ['statCompleted', completed]
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

  const badgeClasses = {
    'pending': 'pending',
    'in_transit': 'in_transit',
    'completed': 'completed',
    'cancelled': 'cancelled'
  };

  function renderTable() {
    const tbody = document.getElementById('transfersTableBody');
    const count = document.getElementById('tableCount');

    if (!filteredData.length) {
      count.textContent = '0 transfers';
      document.getElementById('paginationWrap').style.display = 'none';
      tbody.innerHTML = `<tr><td colspan="6" class="trf-empty"><div class="trf-empty-icon"><i class="fas fa-random"></i></div><div>No transfers found</div><div style="margin-top:8px"><a href="javascript:;" onclick="openAddTransferModal()" style="color:var(--prod-blue);font-weight:500">Create your first transfer</a></div></td></tr>`;
      return;
    }

    const totalEntries = filteredData.length;
    const totalPages = Math.ceil(totalEntries / entriesPerPage);
    const start = (currentPage - 1) * entriesPerPage;
    const end = Math.min(start + entriesPerPage, totalEntries);
    const paginatedData = filteredData.slice(start, end);

    count.textContent = `${totalEntries} transfer${totalEntries !== 1 ? 's' : ''}`;

    const paginationWrap = document.getElementById('paginationWrap');
    if (totalEntries > 10) {
      paginationWrap.style.display = 'flex';
      renderPagination(totalPages, start, end, totalEntries);
    } else {
      paginationWrap.style.display = 'none';
    }

    tbody.innerHTML = paginatedData.map(trf => {
      const badgeClass = badgeClasses[trf.transfer_status] || 'pending';
      const dateStr = trf.transfer_date ? new Date(trf.transfer_date).toLocaleDateString('en-US', {
        month: 'short',
        day: '2-digit',
        year: 'numeric'
      }) : '—';
      const transferLabel = trf.transfer_number || trf.transfer_id.substring(0, 8).toUpperCase();
      const productNames = (trf.items && trf.items.length) ? trf.items.map(i => i.product_name).join(', ') : '—';
      return `
      <tr class="trf-row">
        <td>
          <div class="trf-name-cell">
            <div class="trf-avatar"><i class="fas fa-random"></i></div>
            <div>
              <div class="trf-name-primary">${transferLabel}</div>
            </div>
          </div>
        </td>
        <td><span class="trf-name-primary">${productNames}</span></td>
        <td><span class="trf-name-secondary">${trf.from_location_name || '—'} → ${trf.to_location_name || '—'}</span></td>
        <td><span class="trf-name-secondary">${dateStr}</span></td>
        <td style="text-align:center"><span class="trf-badge ${badgeClass}">${trf.transfer_status.replace(/_/g, ' ')}</span></td>
        <td>
          <div class="trf-actions">
            <button onclick="viewTransferDetails('${trf.transfer_id}')" class="trf-action-btn view"><i class="fas fa-eye"></i> View</button>
            <button onclick="openEditTransferModal('${trf.transfer_id}')" class="trf-action-btn edit"><i class="fas fa-pen"></i> Edit</button>
          </div>
        </td>
      </tr>`;
    }).join('');
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
    html += `<button class="trf-page-btn" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`;

    const maxButtons = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);
    if (endPage - startPage < maxButtons - 1) {
      startPage = Math.max(1, endPage - maxButtons + 1);
    }

    if (startPage > 1) {
      html += `<button class="trf-page-btn" onclick="goToPage(1)">1</button>`;
      if (startPage > 2) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
    }

    for (let i = startPage; i <= endPage; i++) {
      html += `<button class="trf-page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
    }

    if (endPage < totalPages) {
      if (endPage < totalPages - 1) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
      html += `<button class="trf-page-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
    }

    html += `<button class="trf-page-btn" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`;
    paginationControls.innerHTML = html;
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toLowerCase();
    currentPage = 1;

    if (!filter) {
      filteredData = [...transfersData];
    } else {
      filteredData = transfersData.filter(t => {
        const transferLabel = (t.transfer_number || t.transfer_id || '').toLowerCase();
        const productNames = (t.items && t.items.length) ? t.items.map(i => i.product_name).join(' ').toLowerCase() : '';
        return transferLabel.includes(filter) ||
          productNames.includes(filter) ||
          (t.from_location_name || '').toLowerCase().includes(filter) ||
          (t.to_location_name || '').toLowerCase().includes(filter);
      });
    }

    renderTable();
  }

  function populateDropdowns() {
    const productSelects = document.querySelectorAll('select[name="product_id"]');
    const fromLocSelects = document.querySelectorAll('select[name="from_location_id"]');
    const toLocSelects = document.querySelectorAll('select[name="to_location_id"]');
    const prodOptions = '<option value="">Select Product</option>' + productsData.map(p => `<option value="${p.product_id}">${p.product_name}</option>`).join('');
    const locOptions = '<option value="">Select Location</option>' + locationsData.map(l => `<option value="${l.location_id}">${l.location_name}</option>`).join('');
    productSelects.forEach(sel => sel.innerHTML = prodOptions);
    fromLocSelects.forEach(sel => sel.innerHTML = locOptions);
    toLocSelects.forEach(sel => sel.innerHTML = locOptions);
  }

  // View Transfer Details
  function viewTransferDetails(transferId) {
    const trf = transfersData.find(t => String(t.transfer_id) === String(transferId));
    if (!trf) return;

    const badgeClass = badgeClasses[trf.transfer_status] || 'pending';
    const dateStr = trf.transfer_date ? new Date(trf.transfer_date).toLocaleDateString('en-US', {month:'short', day:'2-digit', year:'numeric'}) : '—';

    const overlay = document.getElementById('viewTransferOverlay');
    const transferLabel = trf.transfer_number || trf.transfer_id.substring(0, 8).toUpperCase();
    const itemsHtml = (trf.items && trf.items.length) ? trf.items.map(i => `${i.product_name} (${i.quantity} ${i.uom_name || ''})`).join('<br>') : '—';

    document.getElementById('viewTransferTitle').textContent = `Transfer: ${transferLabel}`;
    document.getElementById('viewTransferBody').innerHTML = `
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:22px;">
        <div>
          <div style="font-size:11px;font-weight:700;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.07em;margin-bottom:12px;">Transfer Information</div>
          <div style="margin-bottom:10px"><span style="font-size:11px;color:var(--color-text-secondary)">Transfer ID</span><br><span class="trf-name-primary" style="font-family:var(--prod-mono)">${transferLabel}</span></div>
          <div style="margin-bottom:10px"><span style="font-size:11px;color:var(--color-text-secondary)">Items</span><br><span class="trf-name-primary">${itemsHtml}</span></div>
          <div style="margin-bottom:10px"><span style="font-size:11px;color:var(--color-text-secondary)">Transfer Date</span><br><span class="trf-name-primary">${dateStr}</span></div>
        </div>
        <div>
          <div style="font-size:11px;font-weight:700;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.07em;margin-bottom:12px;">Location & Status</div>
          <div style="margin-bottom:10px"><span style="font-size:11px;color:var(--color-text-secondary)">From</span><br><span class="trf-name-primary">${trf.from_location_name || '—'} (${trf.from_property_name || ''})</span></div>
          <div style="margin-bottom:10px"><span style="font-size:11px;color:var(--color-text-secondary)">To</span><br><span class="trf-name-primary">${trf.to_location_name || '—'} (${trf.to_property_name || ''})</span></div>
          <div style="margin-bottom:10px"><span style="font-size:11px;color:var(--color-text-secondary)">Status</span><br><span class="trf-badge ${badgeClass}">${trf.transfer_status.replace(/_/g, ' ')}</span></div>
          <div style="margin-bottom:10px"><span style="font-size:11px;color:var(--color-text-secondary)">Notes</span><br><span class="trf-name-primary">${trf.notes || '—'}</span></div>
        </div>
      </div>
    `;
    overlay.classList.add('open');
  }

  function closeViewTransferModal() {
    document.getElementById('viewTransferOverlay').classList.remove('open');
  }

  // Add Transfer Modal
  function openAddTransferModal() {
    document.getElementById('addTransferOverlay').classList.add('open');
  }

  function closeAddTransferModal() {
    document.getElementById('addTransferOverlay').classList.remove('open');
    document.getElementById('addTransferForm').reset();
  }

  function submitAddTransfer(event) {
    event.preventDefault();
    const form = document.getElementById('addTransferForm');
    const formData = new FormData(form);
    formData.append('action', 'add');

    fetch('../api/stock_transfers.php', { method: 'POST', body: formData })
      .then(r => r.json())
      .then(data => {
        closeAddTransferModal();
        if (data.success) {
          fetchTransfers();
        } else {
          alert(data.message || 'Error creating transfer');
        }
      })
      .catch(e => { closeAddTransferModal(); alert('Error creating transfer'); console.error(e); });
  }

  // Edit Transfer Modal
  function openEditTransferModal(transferId) {
    const trf = transfersData.find(t => String(t.transfer_id) === String(transferId));
    if (!trf) return;

    document.getElementById('editTransferId').value = trf.transfer_id;
    const form = document.getElementById('editTransferForm');
    const firstItem = (trf.items && trf.items.length) ? trf.items[0] : {};
    form.elements['product_id'].value = firstItem.product_id || '';
    form.elements['from_location_id'].value = trf.from_location_id || '';
    form.elements['to_location_id'].value = trf.to_location_id || '';
    form.elements['quantity'].value = firstItem.quantity || '';
    form.elements['transfer_date'].value = (trf.transfer_date || '').split(' ')[0];
    form.elements['transfer_status'].value = trf.transfer_status;
    form.elements['notes'].value = trf.notes || '';

    document.getElementById('editTransferOverlay').classList.add('open');
  }

  function closeEditTransferModal() {
    document.getElementById('editTransferOverlay').classList.remove('open');
    document.getElementById('editTransferForm').reset();
  }

  function submitEditTransfer(event) {
    event.preventDefault();
    const form = document.getElementById('editTransferForm');
    const formData = new FormData(form);
    formData.append('action', 'edit');

    fetch('../api/stock_transfers.php', { method: 'POST', body: formData })
      .then(r => r.json())
      .then(data => {
        closeEditTransferModal();
        if (data.success) {
          fetchTransfers();
        } else {
          alert(data.message || 'Error updating transfer');
        }
      })
      .catch(e => { closeEditTransferModal(); alert('Error updating transfer'); console.error(e); });
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchTransfers();
    fetchRelated();
  });
</script>

<!-- View Transfer Modal -->
<div class="trf-overlay" id="viewTransferOverlay" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.5);backdrop-filter:blur(4px);z-index:9000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:var(--color-background-primary,#fff);border-radius:var(--prod-radius-xl);border:1px solid rgba(0,0,0,.08);width:100%;max-width:600px;max-height:92vh;overflow-y:auto;box-shadow:var(--prod-shadow-lg);">
    <div style="padding:22px 26px 18px;border-bottom:1px solid rgba(0,0,0,.06);display:flex;align-items:center;justify-content:space-between;gap:12px;">
      <span style="font-size:17px;font-weight:600;color:var(--color-text-primary)" id="viewTransferTitle">Transfer Details</span>
      <button onclick="closeViewTransferModal()" style="width:30px;height:30px;border-radius:50%;background:var(--color-background-secondary,#F1F5F9);border:none;cursor:pointer;color:var(--color-text-secondary);font-size:16px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-times"></i></button>
    </div>
    <div style="padding:26px;" id="viewTransferBody"></div>
    <div style="padding:18px 26px;border-top:1px solid rgba(0,0,0,.06);display:flex;justify-content:flex-end;gap:8px;">
      <button class="trf-btn" onclick="closeViewTransferModal()">Close</button>
    </div>
  </div>
</div>

<!-- Add Transfer Modal -->
<div class="trf-overlay" id="addTransferOverlay" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.5);backdrop-filter:blur(4px);z-index:9000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:var(--color-background-primary,#fff);border-radius:var(--prod-radius-xl);border:1px solid rgba(0,0,0,.08);width:100%;max-width:700px;max-height:92vh;overflow-y:auto;box-shadow:var(--prod-shadow-lg);">
    <div style="padding:22px 26px 18px;border-bottom:1px solid rgba(0,0,0,.06);display:flex;align-items:center;justify-content:space-between;gap:12px;">
      <span style="font-size:17px;font-weight:600;color:var(--color-text-primary)">New Transfer</span>
      <button onclick="closeAddTransferModal()" style="width:30px;height:30px;border-radius:50%;background:var(--color-background-secondary,#F1F5F9);border:none;cursor:pointer;color:var(--color-text-secondary);font-size:16px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-times"></i></button>
    </div>
    <form id="addTransferForm" onsubmit="submitAddTransfer(event)">
      <div style="padding:26px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px;">
          <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:700;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.07em;">Product <span style="color:var(--prod-red)">*</span></label>
            <select name="product_id" class="trf-search" style="width:100%;padding-left:13px;" required><option value="">Select Product</option></select>
          </div>
          <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:700;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.07em;">Quantity <span style="color:var(--prod-red)">*</span></label>
            <input type="number" name="quantity" step="0.01" class="trf-search" style="width:100%;padding-left:13px;" required>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px;">
          <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:700;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.07em;">From Location <span style="color:var(--prod-red)">*</span></label>
            <select name="from_location_id" class="trf-search" style="width:100%;padding-left:13px;" required><option value="">Select Location</option></select>
          </div>
          <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:700;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.07em;">To Location <span style="color:var(--prod-red)">*</span></label>
            <select name="to_location_id" class="trf-search" style="width:100%;padding-left:13px;" required><option value="">Select Location</option></select>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px;">
          <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:700;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.07em;">Transfer Date <span style="color:var(--prod-red)">*</span></label>
            <input type="date" name="transfer_date" class="trf-search" style="width:100%;padding-left:13px;" required>
          </div>
          <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:700;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.07em;">Status</label>
            <select name="transfer_status" class="trf-search" style="width:100%;padding-left:13px;">
              <option value="pending">Pending</option>
              <option value="in_transit">In Transit</option>
              <option value="completed">Completed</option>
            </select>
          </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:18px;">
          <label style="font-size:11px;font-weight:700;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.07em;">Notes</label>
          <textarea name="notes" rows="3" class="trf-search" style="width:100%;padding-left:13px;resize:vertical;"></textarea>
        </div>
      </div>
      <div style="padding:18px 26px;border-top:1px solid rgba(0,0,0,.06);display:flex;justify-content:flex-end;gap:8px;">
        <button type="button" class="trf-btn" onclick="closeAddTransferModal()">Cancel</button>
        <button type="submit" class="trf-btn trf-btn-primary"><i class="fas fa-plus"></i> Create Transfer</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Transfer Modal -->
<div class="trf-overlay" id="editTransferOverlay" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.5);backdrop-filter:blur(4px);z-index:9000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:var(--color-background-primary,#fff);border-radius:var(--prod-radius-xl);border:1px solid rgba(0,0,0,.08);width:100%;max-width:700px;max-height:92vh;overflow-y:auto;box-shadow:var(--prod-shadow-lg);">
    <div style="padding:22px 26px 18px;border-bottom:1px solid rgba(0,0,0,.06);display:flex;align-items:center;justify-content:space-between;gap:12px;">
      <span style="font-size:17px;font-weight:600;color:var(--color-text-primary)">Edit Transfer</span>
      <button onclick="closeEditTransferModal()" style="width:30px;height:30px;border-radius:50%;background:var(--color-background-secondary,#F1F5F9);border:none;cursor:pointer;color:var(--color-text-secondary);font-size:16px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-times"></i></button>
    </div>
    <form id="editTransferForm" onsubmit="submitEditTransfer(event)">
      <input type="hidden" name="transfer_id" id="editTransferId">
      <div style="padding:26px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px;">
          <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:700;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.07em;">Product <span style="color:var(--prod-red)">*</span></label>
            <select name="product_id" class="trf-search" style="width:100%;padding-left:13px;" required><option value="">Select Product</option></select>
          </div>
          <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:700;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.07em;">Quantity <span style="color:var(--prod-red)">*</span></label>
            <input type="number" name="quantity" step="0.01" class="trf-search" style="width:100%;padding-left:13px;" required>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px;">
          <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:700;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.07em;">From Location <span style="color:var(--prod-red)">*</span></label>
            <select name="from_location_id" class="trf-search" style="width:100%;padding-left:13px;" required><option value="">Select Location</option></select>
          </div>
          <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:700;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.07em;">To Location <span style="color:var(--prod-red)">*</span></label>
            <select name="to_location_id" class="trf-search" style="width:100%;padding-left:13px;" required><option value="">Select Location</option></select>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px;">
          <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:700;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.07em;">Transfer Date <span style="color:var(--prod-red)">*</span></label>
            <input type="date" name="transfer_date" class="trf-search" style="width:100%;padding-left:13px;" required>
          </div>
          <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:700;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.07em;">Status</label>
            <select name="transfer_status" class="trf-search" style="width:100%;padding-left:13px;">
              <option value="pending">Pending</option>
              <option value="in_transit">In Transit</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:18px;">
          <label style="font-size:11px;font-weight:700;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.07em;">Notes</label>
          <textarea name="notes" rows="3" class="trf-search" style="width:100%;padding-left:13px;resize:vertical;"></textarea>
        </div>
      </div>
      <div style="padding:18px 26px;border-top:1px solid rgba(0,0,0,.06);display:flex;justify-content:flex-end;gap:8px;">
        <button type="button" class="trf-btn" onclick="closeEditTransferModal()">Cancel</button>
        <button type="submit" class="trf-btn trf-btn-primary"><i class="fas fa-save"></i> Save Changes</button>
      </div>
    </form>
  </div>
</div>

<style>
  .trf-overlay.open { display: flex !important; }
</style>