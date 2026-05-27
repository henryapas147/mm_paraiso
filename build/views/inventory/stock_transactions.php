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

  .txn-page {
    padding: 28px 28px 40px;
    max-width: 100%;
    margin: 0 auto;
    background: var(--color-background-tertiary, #F4F5F7);
    min-height: 100%;
    border-radius: var(--border-radius-xl, 18px);
    font-family: var(--prod-font);
  }

  .txn-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
    gap: 16px;
    flex-wrap: wrap;
  }

  .txn-page-eyebrow {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--prod-blue);
    margin-bottom: 4px;
  }

  .txn-page-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.4px;
    line-height: 1.2;
  }

  .txn-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
  }

  .txn-stat-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    padding: 20px 22px;
    box-shadow: var(--prod-shadow-sm);
    position: relative;
    overflow: hidden;
  }

  .txn-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--prod-radius-lg) var(--prod-radius-lg) 0 0;
    background: var(--_accent, #CBD5E1);
  }

  .txn-stat-card.blue {
    --_accent: var(--prod-blue);
  }

  .txn-stat-card.green {
    --_accent: var(--prod-green);
  }

  .txn-stat-card.red {
    --_accent: var(--prod-red);
  }

  .txn-stat-card.purple {
    --_accent: var(--prod-purple);
  }

  .txn-stat-icon {
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

  .txn-stat-card.green .txn-stat-icon {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .txn-stat-card.red .txn-stat-icon {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .txn-stat-card.purple .txn-stat-icon {
    background: var(--prod-purple-pale);
    color: var(--prod-purple);
  }

  .txn-stat-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-secondary);
    margin-bottom: 6px;
  }

  .txn-stat-value {
    font-size: 32px;
    font-weight: 600;
    color: var(--color-text-primary);
    font-family: var(--prod-mono);
  }

  .txn-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    box-shadow: var(--prod-shadow-sm);
    overflow: hidden;
  }

  .txn-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }

  .txn-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .txn-search-wrap {
    position: relative;
  }

  .txn-search-wrap i {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-text-secondary);
    font-size: 12px;
    pointer-events: none;
  }

  .txn-search {
    padding: 8px 14px 8px 34px;
    font-size: 13px;
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--prod-radius-md);
    background: var(--color-background-secondary, #F8FAFC);
    color: var(--color-text-primary);
    outline: none;
    width: 230px;
  }

  .txn-search:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
  }

  .txn-btn {
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

  .txn-btn:hover {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .txn-btn-primary {
    background: var(--prod-blue);
    color: #fff;
    border-color: var(--prod-blue);
    box-shadow: 0 1px 3px rgba(26, 107, 191, .3);
  }

  .txn-btn-primary:hover {
    background: var(--prod-blue-dark);
  }

  .txn-table-wrap {
    overflow-x: auto;
  }

  .txn-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }

  .txn-table thead tr {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .txn-table th {
    padding: 11px 18px;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .08em;
    border-bottom: 1px solid rgba(0, 0, 0, .07);
  }

  .txn-table td {
    padding: 13px 18px;
    vertical-align: middle;
  }

  .txn-name-cell {
    display: flex;
    align-items: center;
  }

  .txn-avatar {
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

  .txn-name-primary {
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-primary);
  }

  .txn-name-secondary {
    font-size: 11px;
    color: var(--color-text-secondary);
  }

  .txn-badge {
    display: inline-flex;
    padding: 4px 10px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 600;
  }

  .txn-badge.in {
    background: var(--prod-green-pale);
    color: var(--prod-green);
  }

  .txn-badge.out {
    background: var(--prod-red-pale);
    color: var(--prod-red);
  }

  .txn-badge.transfer {
    background: var(--prod-purple-pale);
    color: var(--prod-purple);
  }

  .txn-actions {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
  }

  .txn-action-btn {
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

  .txn-action-btn.view:hover {
    background: var(--prod-blue-pale);
    color: var(--prod-blue);
  }

  .txn-empty {
    padding: 64px;
    text-align: center;
    color: var(--color-text-secondary);
  }

  .txn-empty-icon {
    font-size: 32px;
    opacity: .25;
    margin-bottom: 12px;
  }

  .txn-pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    background: var(--color-background-primary, #fff);
    gap: 16px;
    flex-wrap: wrap;
  }

  .txn-pagination-info {
    font-size: 12px;
    color: var(--color-text-secondary);
  }

  .txn-pagination {
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .txn-page-btn {
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

  .txn-page-btn:hover:not(:disabled) {
    background: var(--prod-blue-pale);
    border-color: var(--prod-blue);
    color: var(--prod-blue);
  }

  .txn-page-btn.active {
    background: var(--prod-blue);
    border-color: var(--prod-blue);
    color: #fff;
  }

  .txn-page-btn:disabled {
    opacity: .4;
    cursor: not-allowed;
  }

  .txn-entries-select {
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

  .txn-entries-select:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
    outline: none;
  }

  .txn-entries-label {
    font-size: 12px;
    color: var(--color-text-secondary);
    margin-right: 8px;
  }

  .txn-entries-wrap {
    display: flex;
    align-items: center;
  }

  @media (max-width: 600px) {
    .txn-search {
      width: 160px;
    }
  }
</style>

<div class="txn-page">
  <div class="txn-page-header">
    <div>
      <div class="txn-page-eyebrow"><i class="fas fa-exchange-alt" style="margin-right:5px"></i>Inventory</div>
      <div class="txn-page-title">Stock Transactions</div>
    </div>
  </div>

  <div class="txn-stats-grid">
    <div class="txn-stat-card blue">
      <div class="txn-stat-icon"><i class="fas fa-exchange-alt"></i></div>
      <div class="txn-stat-label">Total Transactions</div>
      <div class="txn-stat-value" id="statTotal">—</div>
    </div>
    <div class="txn-stat-card green">
      <div class="txn-stat-icon"><i class="fas fa-arrow-down"></i></div>
      <div class="txn-stat-label">Stock In</div>
      <div class="txn-stat-value" id="statStockIn">—</div>
    </div>
    <div class="txn-stat-card red">
      <div class="txn-stat-icon"><i class="fas fa-arrow-up"></i></div>
      <div class="txn-stat-label">Stock Out</div>
      <div class="txn-stat-value" id="statStockOut">—</div>
    </div>
    <div class="txn-stat-card purple">
      <div class="txn-stat-icon"><i class="fas fa-random"></i></div>
      <div class="txn-stat-label">Transfers</div>
      <div class="txn-stat-value" id="statTransfers">—</div>
    </div>
  </div>

  <div class="txn-card">
    <div class="txn-card-header">
      <div>
        <strong>All Transactions</strong>
        <span id="tableCount" style="font-size:12px;color:var(--color-text-secondary);margin-left:8px">Loading…</span>
      </div>
      <div class="txn-toolbar">
        <div class="txn-entries-wrap">
          <span class="txn-entries-label">Show</span>
          <select class="txn-entries-select" id="entriesPerPage" onchange="changeEntriesPerPage()">
            <option value="10">10</option>
            <option value="25" selected>25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="txn-entries-label">entries</span>
        </div>
        <div class="txn-search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" id="searchInput" class="txn-search" placeholder="Search transactions…" oninput="filterTable()">
        </div>
      </div>
    </div>
    <div class="txn-table-wrap">
      <table class="txn-table" id="transactionsTable">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Product</th>
            <th>Location</th>
            <th style="text-align:right">Quantity</th>
            <th style="text-align:center">Direction</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="transactionsTableBody">
          <tr>
            <td colspan="7" class="txn-empty">
              <div class="txn-empty-icon"><i class="fas fa-spinner fa-spin"></i></div>
              <div>Loading transactions…</div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="txn-pagination-wrap" id="paginationWrap" style="display:none">
      <div class="txn-pagination-info" id="paginationInfo">Showing 1-25 of 100 entries</div>
      <div class="txn-pagination" id="paginationControls"></div>
    </div>
  </div>
</div>

<script>
  let transactionsData = [];
  let filteredData = [];
  let currentPage = 1;
  let entriesPerPage = 25;
  let productsData = [];
  let locationsData = [];
  let uomsData = [];

  async function fetchTransactions() {
    try {
      const res = await fetch('../api/stock_transactions.php?action=list');
      const json = await res.json();
      if (json.success) {
        transactionsData = json.data;
        filteredData = [...transactionsData];
        currentPage = 1;
        renderStats();
        renderTable();
      }
    } catch (e) {
      console.error('Error fetching transactions:', e);
      document.getElementById('transactionsTableBody').innerHTML = `<tr><td colspan="7" class="txn-empty"><div class="txn-empty-icon"><i class="fas fa-circle-xmark"></i></div><div>Failed to load data.</div></td></tr>`;
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/stock_transactions.php?action=related');
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
    const total = transactionsData.length;
    const stockIn = transactionsData.filter(t => ['stock_in', 'stock_in_manual', 'adjustment_add'].includes(t.transaction_type)).length;
    const stockOut = transactionsData.filter(t => ['stock_out_sale', 'stock_out_use', 'stock_out_damage', 'adjustment_deduct'].includes(t.transaction_type)).length;
    const transfers = transactionsData.filter(t => ['transfer_out', 'transfer_in'].includes(t.transaction_type)).length;

    const pairs = [
      ['statTotal', total],
      ['statStockIn', stockIn],
      ['statStockOut', stockOut],
      ['statTransfers', transfers]
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
    'stock_in': 'in',
    'stock_in_manual': 'in',
    'adjustment_add': 'in',
    'stock_out_sale': 'out',
    'stock_out_use': 'out',
    'stock_out_damage': 'out',
    'adjustment_deduct': 'out',
    'transfer_out': 'transfer',
    'transfer_in': 'transfer'
  };

  const directionLabels = {
    'stock_in': 'In',
    'stock_in_manual': 'In',
    'adjustment_add': 'In',
    'stock_out_sale': 'Out',
    'stock_out_use': 'Out',
    'stock_out_damage': 'Out',
    'adjustment_deduct': 'Out',
    'transfer_out': 'Transfer',
    'transfer_in': 'Transfer'
  };

  function renderTable() {
    const tbody = document.getElementById('transactionsTableBody');
    const count = document.getElementById('tableCount');

    if (!filteredData.length) {
      count.textContent = '0 transactions';
      document.getElementById('paginationWrap').style.display = 'none';
      tbody.innerHTML = `<tr><td colspan="7" class="txn-empty"><div class="txn-empty-icon"><i class="fas fa-exchange-alt"></i></div><div>No transactions found</div></td></tr>`;
      return;
    }

    const totalEntries = filteredData.length;
    const totalPages = Math.ceil(totalEntries / entriesPerPage);
    const start = (currentPage - 1) * entriesPerPage;
    const end = Math.min(start + entriesPerPage, totalEntries);
    const paginatedData = filteredData.slice(start, end);

    count.textContent = `${totalEntries} transaction${totalEntries !== 1 ? 's' : ''}`;

    const paginationWrap = document.getElementById('paginationWrap');
    if (totalEntries > 10) {
      paginationWrap.style.display = 'flex';
      renderPagination(totalPages, start, end, totalEntries);
    } else {
      paginationWrap.style.display = 'none';
    }

    tbody.innerHTML = paginatedData.map(txn => {
      const badgeClass = badgeClasses[txn.transaction_type] || 'in';
      const direction = directionLabels[txn.transaction_type] || 'In';
      const dateStr = txn.transaction_date ? new Date(txn.transaction_date).toLocaleDateString('en-US', {
        month: 'short',
        day: '2-digit',
        year: 'numeric'
      }) : '—';
      const typeLabel = txn.transaction_type ? txn.transaction_type.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) : '—';
      return `
      <tr class="txn-row">
        <td><span class="txn-name-secondary">${dateStr}</span></td>
        <td><span class="txn-name-primary">${typeLabel}</span></td>
        <td>
          <div class="txn-name-cell">
            <div class="txn-avatar"><i class="fas fa-box"></i></div>
            <div>
              <div class="txn-name-primary">${txn.product_name}</div>
            </div>
          </div>
        </td>
        <td><span class="txn-name-primary">${txn.location_name}</span></td>
        <td style="text-align:right"><span class="txn-name-primary" style="font-family:var(--prod-mono)">${parseFloat(txn.quantity).toFixed(3)}</span></td>
        <td style="text-align:center"><span class="txn-badge ${badgeClass}">${direction}</span></td>
        <td>
          <div class="txn-actions">
            <button onclick="viewTransactionDetails('${txn.transaction_id}')" class="txn-action-btn view"><i class="fas fa-eye"></i> View</button>
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
    html += `<button class="txn-page-btn" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`;

    const maxButtons = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);
    if (endPage - startPage < maxButtons - 1) {
      startPage = Math.max(1, endPage - maxButtons + 1);
    }

    if (startPage > 1) {
      html += `<button class="txn-page-btn" onclick="goToPage(1)">1</button>`;
      if (startPage > 2) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
    }

    for (let i = startPage; i <= endPage; i++) {
      html += `<button class="txn-page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
    }

    if (endPage < totalPages) {
      if (endPage < totalPages - 1) html += `<span style="padding:0 8px;color:var(--color-text-secondary)">…</span>`;
      html += `<button class="txn-page-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
    }

    html += `<button class="txn-page-btn" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`;
    paginationControls.innerHTML = html;
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toLowerCase();
    currentPage = 1;

    if (!filter) {
      filteredData = [...transactionsData];
    } else {
      filteredData = transactionsData.filter(t =>
        t.product_name.toLowerCase().includes(filter) ||
        t.location_name.toLowerCase().includes(filter) ||
        t.transaction_type.toLowerCase().includes(filter)
      );
    }

    renderTable();
  }

  function populateDropdowns() {
    const productSelects = document.querySelectorAll('select[name="product_id"]');
    const locationSelects = document.querySelectorAll('select[name="location_id"]');
    const prodOptions = '<option value="">Select Product</option>' + productsData.map(p => `<option value="${p.product_id}">${p.product_name}</option>`).join('');
    const locOptions = '<option value="">Select Location</option>' + locationsData.map(l => `<option value="${l.location_id}">${l.location_name}</option>`).join('');
    productSelects.forEach(sel => sel.innerHTML = prodOptions);
    locationSelects.forEach(sel => sel.innerHTML = locOptions);
  }

  async function viewTransactionDetails(transactionId) {
    const txn = transactionsData.find(t => String(t.transaction_id) === String(transactionId));
    if (!txn) return;

    const typeLabel = txn.transaction_type ? txn.transaction_type.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) : '—';
    const dateStr = txn.transaction_date ? new Date(txn.transaction_date).toLocaleDateString('en-US', {
      month: 'short',
      day: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    }) : '—';

    let referenceInfo = '';
    if (txn.reference_type && txn.reference_id) {
      referenceInfo = `<div style="margin-bottom:12px"><strong style="color:var(--color-text-secondary);font-size:11px;text-transform:uppercase">Reference</strong><div>${txn.reference_type} #${txn.reference_id}</div></div>`;
    }

    let notesInfo = '';
    if (txn.notes) {
      notesInfo = `<div style="margin-bottom:12px"><strong style="color:var(--color-text-secondary);font-size:11px;text-transform:uppercase">Notes</strong><div style="white-space:pre-wrap">${txn.notes}</div></div>`;
    }

    const modalHtml = `
      <div id="txnDetailModal" style="position:fixed;inset:0;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:10000;font-family:var(--prod-font)" onclick="if(event.target===this)closeTransactionModal()">
        <div style="background:var(--color-background-primary,#fff);border-radius:var(--prod-radius-lg);max-width:500px;width:90%;max-height:90vh;overflow:auto;box-shadow:var(--prod-shadow-lg)">
          <div style="padding:20px 24px;border-bottom:1px solid rgba(0,0,0,0.06);display:flex;align-items:center;justify-content:space-between">
            <h3 style="margin:0;font-size:18px;font-weight:600">Transaction Details</h3>
            <button onclick="closeTransactionModal()" style="background:none;border:none;cursor:pointer;padding:4px;font-size:18px;color:var(--color-text-secondary)"><i class="fas fa-times"></i></button>
          </div>
          <div style="padding:24px">
            <div style="margin-bottom:16px">
              <span class="txn-badge ${badgeClasses[txn.transaction_type] || 'in'}">${directionLabels[txn.transaction_type] || 'In'}</span>
              <span style="margin-left:8px;font-size:13px;color:var(--color-text-secondary)">${typeLabel}</span>
            </div>
            <div style="display:grid;gap:12px;grid-template-columns:1fr 1fr;margin-bottom:16px">
              <div>
                <strong style="color:var(--color-text-secondary);font-size:11px;text-transform:uppercase">Date</strong>
                <div>${dateStr}</div>
              </div>
              <div>
                <strong style="color:var(--color-text-secondary);font-size:11px;text-transform:uppercase">Transaction ID</strong>
                <div style="font-family:var(--prod-mono);font-size:12px">${txn.transaction_id}</div>
              </div>
            </div>
            <div style="margin-bottom:12px">
              <strong style="color:var(--color-text-secondary);font-size:11px;text-transform:uppercase">Product</strong>
              <div>${txn.product_name}</div>
            </div>
            <div style="margin-bottom:12px">
              <strong style="color:var(--color-text-secondary);font-size:11px;text-transform:uppercase">Location</strong>
              <div>${txn.location_name}</div>
            </div>
            <div style="margin-bottom:12px">
              <strong style="color:var(--color-text-secondary);font-size:11px;text-transform:uppercase">Quantity</strong>
              <div style="font-family:var(--prod-mono);font-size:18px;font-weight:600">${parseFloat(txn.quantity).toFixed(3)}</div>
            </div>
            ${referenceInfo}
            ${notesInfo}
          </div>
        </div>
      </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHtml);
    document.body.style.overflow = 'hidden';
  }

  function closeTransactionModal() {
    const modal = document.getElementById('txnDetailModal');
    if (modal) {
      modal.remove();
      document.body.style.overflow = '';
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchTransactions();
    fetchRelated();
  });
</script>