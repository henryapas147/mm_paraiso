<?php if (false) {} ?>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Lora:ital,wght@0,600;1,500&display=swap" rel="stylesheet">
<style>
  /* ── Design Tokens ── */
  :root {
    --brand:        #C84B31;
    --brand-dark:   #A03525;
    --brand-light:  #FDF0ED;
    --accent:       #F0A500;
    --accent-light: #FFF8E7;
    --surface:      #FFFFFF;
    --surface-2:    #FAFAFA;
    --border:       #EDEAE6;
    --border-strong:#D6D0C8;
    --text-primary: #1A1410;
    --text-secondary:#6B6460;
    --text-muted:   #A09890;
    --success:      #2D7D4F;
    --success-bg:   #EDFAF3;
    --warning:      #B45309;
    --warning-bg:   #FEF7EC;
    --danger:       #C0392B;
    --danger-bg:    #FEF0EE;
    --radius-sm:    6px;
    --radius-md:    10px;
    --radius-lg:    16px;
    --radius-xl:    20px;
    --shadow-card:  0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.06);
    --shadow-modal: 0 24px 64px rgba(0,0,0,.18);
    --font-display: 'Lora', Georgia, serif;
    --font-body:    'DM Sans', system-ui, sans-serif;
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
    display: flex; align-items: center; gap: 16px;
    transition: box-shadow .2s, transform .2s;
  }
  .fo-stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(0,0,0,.10); }

  .fo-stat-icon {
    width: 48px; height: 48px;
    border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
  }
  .fo-stat-icon.transactions { background: var(--brand-light); color: var(--brand); }
  .fo-stat-icon.additions { background: var(--success-bg); color: var(--success); }
  .fo-stat-icon.deductions { background: var(--danger-bg); color: var(--danger); }
  .fo-stat-icon.rollbacks { background: var(--warning-bg); color: var(--warning); }

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

  /* ── Card ── */
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

  /* Buttons */
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

  .fo-btn-secondary {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 18px;
    font-family: var(--font-body); font-size: 13px; font-weight: 600;
    background: var(--surface); color: var(--text-secondary);
    border: 1px solid var(--border-strong); border-radius: var(--radius-md);
    cursor: pointer;
    transition: all .15s;
  }
  .fo-btn-secondary:hover { background: var(--surface-2); color: var(--text-primary); }

  /* ── Form Fields ── */
  .fo-form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }
  @media (max-width: 768px) { .fo-form-grid { grid-template-columns: 1fr; } }

  .fo-form-group { margin-bottom: 16px; }

  .fo-label {
    display: block; font-size: 12px; font-weight: 600;
    color: var(--text-secondary); margin-bottom: 6px;
    text-transform: uppercase; letter-spacing: .03em;
  }

  .fo-input, .fo-select, .fo-textarea {
    width: 100%; padding: 10px 14px;
    font-family: var(--font-body); font-size: 14px;
    background: var(--surface); border: 1px solid var(--border-strong);
    border-radius: var(--radius-sm); color: var(--text-primary);
    transition: border-color .15s, box-shadow .15s;
  }
  .fo-input:focus, .fo-select:focus, .fo-textarea:focus {
    border-color: var(--brand);
    outline: none;
    box-shadow: 0 0 0 3px rgba(200,75,49,.08);
  }
  .fo-textarea { resize: vertical; min-height: 100px; }

  /* ── Table ── */
  .fo-table { width: 100%; border-collapse: collapse; }
  .fo-table th {
    padding: 11px 16px;
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: var(--text-muted);
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
    text-align: left;
  }
  .fo-table th.center { text-align: center; }
  .fo-table td {
    padding: 13px 16px;
    font-size: 13.5px;
    color: var(--text-primary);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
  }
  .fo-table td.center { text-align: center; }
  .fo-table tr:last-child td { border-bottom: none; }
  .fo-table tr:hover td { background: var(--surface-2); }

  /* Transaction Type Badges */
  .trans-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 99px;
    font-size: 11px; font-weight: 600;
    text-transform: uppercase;
  }
  .trans-badge-add { background: var(--success-bg); color: var(--success); }
  .trans-badge-deduct { background: var(--danger-bg); color: var(--danger); }
  .trans-badge-rollback { background: var(--warning-bg); color: var(--warning); }

  /* ── Table Footer & Pagination ── */
  .fo-table-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-top: 1px solid var(--border);
    gap: 12px;
    flex-wrap: wrap;
  }
  .fo-table-info {
    font-size: 12px;
    color: var(--text-secondary);
  }
  .fo-table-pagination {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .fo-table-pagination button {
    padding: 6px 10px;
    font-size: 12px;
  }
  .fo-table-pagination button:disabled {
    opacity: 0.4;
    cursor: not-allowed;
  }
  .fo-table-pagination span {
    font-size: 12px;
    color: var(--text-secondary);
  }

  /* ── Modal ── */
  .fo-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .45);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    padding: 20px
  }
  .fo-modal-overlay.open { display: flex }
  .fo-modal {
    background: #fff;
    border-radius: 12px;
    border: 1px solid var(--border);
    width: 100%;
    max-width: 500px;
    box-shadow: 0 16px 32px rgba(0, 0, 0, .15)
  }
  .fo-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
  }
  .fo-modal-title {
    font-family: var(--font-display);
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
    font-style: italic;
  }
  .fo-modal-close {
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    font-size: 18px;
    padding: 4px;
    transition: color .15s;
  }
  .fo-modal-close:hover { color: var(--text-primary); }
  .fo-modal-body { padding: 24px; }
  .fo-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 24px;
    border-top: 1px solid var(--border);
  }

  /* ── Custom Alert Modal ── */
  .alert-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .45);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    padding: 20px
  }
  .alert-overlay.open { display: flex }
  .alert-box {
    background: #fff;
    border-radius: 12px;
    border: 1px solid var(--border);
    width: 100%;
    max-width: 400px;
    padding: 24px;
    box-shadow: 0 16px 32px rgba(0, 0, 0, .15)
  }
  .alert-title {
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 12px;
  }
  .alert-message {
    font-size: 14px;
    color: var(--text-secondary);
    margin-bottom: 20px;
    line-height: 1.5;
  }
  .alert-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
  }
  .alert-btn-cancel {
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 600;
    background: var(--surface);
    color: var(--text-secondary);
    border: 1px solid var(--border-strong);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all .15s
  }
  .alert-btn-cancel:hover {
    background: var(--surface-2);
    color: var(--text-primary)
  }
  .alert-btn-confirm {
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 600;
    background: var(--brand);
    color: #fff;
    border: none;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all .15s
  }
  .alert-btn-confirm:hover {
    background: var(--brand-dark)
  }
</style>

<!-- Stats Grid -->
<div class="fo-stats-grid">
  <div class="fo-stat-card">
    <div class="fo-stat-icon transactions"><i class="fas fa-exchange-alt"></i></div>
    <div>
      <div class="fo-stat-label">Total Transactions</div>
      <div class="fo-stat-value" id="statTotal">—</div>
    </div>
  </div>
  <div class="fo-stat-card">
    <div class="fo-stat-icon additions"><i class="fas fa-plus-circle"></i></div>
    <div>
      <div class="fo-stat-label">Additions</div>
      <div class="fo-stat-value" id="statAdditions">—</div>
    </div>
  </div>
  <div class="fo-stat-card">
    <div class="fo-stat-icon deductions"><i class="fas fa-minus-circle"></i></div>
    <div>
      <div class="fo-stat-label">Deductions</div>
      <div class="fo-stat-value" id="statDeductions">—</div>
    </div>
  </div>
  <div class="fo-stat-card">
    <div class="fo-stat-icon rollbacks"><i class="fas fa-undo"></i></div>
    <div>
      <div class="fo-stat-label">Rollbacks</div>
      <div class="fo-stat-value" id="statRollbacks">—</div>
    </div>
  </div>
</div>

<!-- Transactions Table -->
<div class="fo-card">
  <div class="fo-card-header">
    <h2 class="fo-card-title">Inventory Transactions</h2>
    <div class="fo-card-actions">
      <div class="fo-search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" class="fo-search" placeholder="Search transactions..." oninput="filterTransactions()">
      </div>
      <select id="typeFilter" class="fo-select" style="width: auto; padding: 8px 12px;" onchange="filterTransactions()">
        <option value="">All Types</option>
        <option value="add">Additions</option>
        <option value="deduct">Deductions</option>
        <option value="rollback">Rollbacks</option>
      </select>
      <input type="date" id="dateFrom" class="fo-input" style="width: auto; padding: 8px 12px;" onchange="filterTransactions()">
      <input type="date" id="dateTo" class="fo-input" style="width: auto; padding: 8px 12px;" onchange="filterTransactions()">
      <button onclick="clearDateFilter()" class="fo-btn-secondary" style="padding: 8px 12px;">
        <i class="fas fa-times"></i> Clear Dates
      </button>
      <button onclick="exportTransactions()" class="fo-btn-secondary" style="padding: 8px 12px;">
        <i class="fas fa-download"></i> Export
      </button>
      <button onclick="openAdjustModal()" class="fo-btn-primary">
        <i class="fas fa-plus"></i> Adjust Stock
      </button>
    </div>
  </div>
  <div style="overflow-x:auto">
    <table class="fo-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Product</th>
          <th>Type</th>
          <th>Quantity</th>
          <th>Before/After</th>
          <th>Reference</th>
          <th>Reason</th>
          <th class="center">Actions</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        <tr>
          <td colspan="8" class="fo-empty">
            <i class="fas fa-spinner fa-spin" style="margin-right:8px;color:var(--text-muted)"></i>
            Loading transactions…
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="fo-table-footer">
    <div class="fo-table-info">
      <span id="tableInfo">Showing 0 of 0 entries</span>
    </div>
    <div class="fo-table-pagination">
      <select id="rowsPerPage" class="fo-select" style="width: auto; padding: 6px 10px; font-size: 12px;" onchange="changeRowsPerPage()">
        <option value="10">10 per page</option>
        <option value="25">25 per page</option>
        <option value="50">50 per page</option>
        <option value="100">100 per page</option>
      </select>
      <button class="fo-btn-secondary" id="prevBtn" onclick="changePage(-1)" disabled>
        <i class="fas fa-chevron-left"></i>
      </button>
      <span id="pageInfo">Page 1 of 1</span>
      <button class="fo-btn-secondary" id="nextBtn" onclick="changePage(1)" disabled>
        <i class="fas fa-chevron-right"></i>
      </button>
    </div>
  </div>
</div>

<!-- Stock Adjustment Modal -->
<div id="adjustModal" class="fo-modal-overlay" onclick="if(event.target===this)closeAdjustModal()">
  <div class="fo-modal">
    <div class="fo-modal-header">
      <h3 class="fo-modal-title">Adjust Stock</h3>
      <button onclick="closeAdjustModal()" class="fo-modal-close"><i class="fas fa-times"></i></button>
    </div>
    <form id="adjustForm" onsubmit="saveAdjustment(event)">
      <div class="fo-modal-body">
        <div class="fo-form-group">
          <label class="fo-label">Product <span style="color:var(--danger)">*</span></label>
          <select id="productId" name="product_id" class="fo-select" required>
            <option value="">Select Product</option>
          </select>
        </div>
        <div class="fo-form-grid">
          <div class="fo-form-group">
            <label class="fo-label">Transaction Type <span style="color:var(--danger)">*</span></label>
            <select id="transactionType" name="transaction_type" class="fo-select" required>
              <option value="add">Add Stock</option>
              <option value="deduct">Deduct Stock</option>
            </select>
          </div>
          <div class="fo-form-group">
            <label class="fo-label">Quantity <span style="color:var(--danger)">*</span></label>
            <input type="number" id="quantity" name="quantity" class="fo-input" min="0.001" step="0.001" required placeholder="0.00">
          </div>
        </div>
        <div class="fo-form-group">
          <label class="fo-label">Reason</label>
          <input type="text" id="reason" name="reason" class="fo-input" placeholder="e.g. Purchase, Damage, etc.">
        </div>
        <div class="fo-form-group">
          <label class="fo-label">Current Stock</label>
          <input type="text" id="currentStock" class="fo-input" readonly style="background: var(--surface-2);">
        </div>
      </div>
      <div class="fo-modal-footer">
        <button type="button" class="fo-btn-secondary" onclick="closeAdjustModal()">Cancel</button>
        <button type="submit" class="fo-btn-primary">Save Adjustment</button>
      </div>
    </form>
  </div>
</div>

<!-- Custom Alert Modal -->
<div class="alert-overlay" id="alertModal">
  <div class="alert-box">
    <div class="alert-title" id="alertTitle">Confirm Action</div>
    <div class="alert-message" id="alertMessage">Are you sure you want to proceed?</div>
    <div class="alert-actions">
      <button class="alert-btn-cancel" onclick="closeAlert()">Cancel</button>
      <button class="alert-btn-confirm" id="alertConfirmBtn">Confirm</button>
    </div>
  </div>
</div>

<script>
  let transactions = [];
  let products = [];
  let filteredTransactions = [];
  let currentPage = 1;
  let rowsPerPage = 10;
  const csrfToken = '<?php echo CSRF_TOKEN; ?>';

  document.addEventListener('DOMContentLoaded', async () => {
    // Set default date range to last 7 days
    const today = new Date();
    const sevenDaysAgo = new Date();
    sevenDaysAgo.setDate(today.getDate() - 7);
    
    document.getElementById('dateTo').value = today.toISOString().split('T')[0];
    document.getElementById('dateFrom').value = sevenDaysAgo.toISOString().split('T')[0];
    
    await Promise.all([fetchTransactions(), fetchProducts()]);
    updateStats();
    renderTable();
  });

  async function fetchTransactions() {
    try {
      const response = await fetch('../api/inventory_transactions.php?action=list');
      const result = await response.json();
      if (result.success) {
        transactions = result.data;
        filteredTransactions = [...transactions];
      }
    } catch (error) {
      console.error('Error fetching transactions:', error);
    }
  }

  async function fetchProducts() {
    try {
      const response = await fetch('../api/products.php?action=list&is_active=1');
      const result = await response.json();
      if (result.success) {
        products = result.data;
        populateProductSelect();
      }
    } catch (error) {
      console.error('Error fetching products:', error);
    }
  }

  function populateProductSelect() {
    const select = document.getElementById('productId');
    select.innerHTML = '<option value="">Select Product</option>';
    products.forEach(p => {
      const option = document.createElement('option');
      option.value = p.product_id;
      option.textContent = `${p.product_name} (Stock: ${p.stock_on_hand || 0} ${p.uom_code || ''})`;
      option.dataset.stock = p.stock_on_hand || 0;
      select.appendChild(option);
    });
  }

  function updateStats() {
    document.getElementById('statTotal').textContent = transactions.length;
    document.getElementById('statAdditions').textContent = transactions.filter(t => t.transaction_type === 'add').length;
    document.getElementById('statDeductions').textContent = transactions.filter(t => t.transaction_type === 'deduct').length;
    document.getElementById('statRollbacks').textContent = transactions.filter(t => t.transaction_type === 'rollback').length;
  }

  function renderTable() {
    const tbody = document.getElementById('tableBody');
    
    if (filteredTransactions.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="8">
            <div class="fo-empty">
              <div class="fo-empty-icon"><i class="fas fa-exchange-alt"></i></div>
              <h4>No transactions found</h4>
              <p>Adjust stock to see transaction history</p>
            </div>
          </td>
        </tr>
      `;
      updatePaginationInfo(0);
      return;
    }

    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageData = filteredTransactions.slice(start, end);

    tbody.innerHTML = pageData.map(t => {
      const typeClass = t.transaction_type === 'add' ? 'add' : t.transaction_type === 'deduct' ? 'deduct' : 'rollback';
      const typeLabel = t.transaction_type === 'add' ? 'Add' : t.transaction_type === 'deduct' ? 'Deduct' : 'Rollback';
      const reference = t.reference_type === 'manual_adjustment' ? 'Manual' : 
                       t.reference_type === 'food_order' ? `Order #${t.reference_id}` :
                       t.reference_type === 'reservation' ? `Reservation #${t.reference_id}` : t.reference_type;
      
      return `
        <tr>
          <td>${new Date(t.created_at).toLocaleString('en-US', { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true })}</td>
          <td>${t.product_name || '—'}</td>
          <td><span class="trans-badge trans-badge-${typeClass}">${typeLabel}</span></td>
          <td>${parseFloat(t.quantity).toFixed(3)} ${t.uom_code || ''}</td>
          <td>${parseFloat(t.stock_before).toFixed(3)} → ${parseFloat(t.stock_after).toFixed(3)}</td>
          <td>${reference}</td>
          <td>${t.reason || '—'}</td>
          <td class="center">
            ${t.transaction_type === 'deduct' && !t.rollback_transaction_id ? 
              `<button onclick="rollbackTransaction(${t.transaction_id})" class="fo-btn-secondary" style="padding:6px 12px;font-size:12px;">
                <i class="fas fa-undo"></i> Rollback
              </button>` : ''}
          </td>
        </tr>
      `;
    }).join('');

    updatePaginationInfo(filteredTransactions.length);
  }

  function updatePaginationInfo(totalEntries) {
    const totalPages = Math.ceil(totalEntries / rowsPerPage) || 1;
    const start = totalEntries === 0 ? 0 : (currentPage - 1) * rowsPerPage + 1;
    const end = Math.min(currentPage * rowsPerPage, totalEntries);

    document.getElementById('tableInfo').textContent = `Showing ${start}-${end} of ${totalEntries} entries`;
    document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
    document.getElementById('prevBtn').disabled = currentPage === 1;
    document.getElementById('nextBtn').disabled = currentPage === totalPages;
  }

  function changePage(delta) {
    const totalPages = Math.ceil(filteredTransactions.length / rowsPerPage) || 1;
    const newPage = currentPage + delta;
    if (newPage >= 1 && newPage <= totalPages) {
      currentPage = newPage;
      renderTable();
    }
  }

  function changeRowsPerPage() {
    rowsPerPage = parseInt(document.getElementById('rowsPerPage').value);
    currentPage = 1;
    renderTable();
  }

  function filterTransactions() {
    const searchTerm = document.getElementById('searchInput')?.value?.toLowerCase() || '';
    const typeFilter = document.getElementById('typeFilter')?.value || '';
    const dateFrom = document.getElementById('dateFrom')?.value;
    const dateTo = document.getElementById('dateTo')?.value;

    filteredTransactions = transactions.filter(t => {
      const matchesSearch = 
        (t.product_name || '').toLowerCase().includes(searchTerm) ||
        (t.reason || '').toLowerCase().includes(searchTerm);
      const matchesType = !typeFilter || t.transaction_type === typeFilter;
      
      let matchesDate = true;
      if (dateFrom || dateTo) {
        const transDate = new Date(t.created_at);
        if (dateFrom) {
          const fromDate = new Date(dateFrom);
          fromDate.setHours(0, 0, 0, 0);
          if (transDate < fromDate) matchesDate = false;
        }
        if (dateTo) {
          const toDate = new Date(dateTo);
          toDate.setHours(23, 59, 59, 999);
          if (transDate > toDate) matchesDate = false;
        }
      }
      
      return matchesSearch && matchesType && matchesDate;
    });
    currentPage = 1;
    renderTable();
  }

  function clearDateFilter() {
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
    filterTransactions();
  }

  function exportTransactions() {
    if (filteredTransactions.length === 0) {
      showAlert('Info', 'No transactions to export.');
      return;
    }

    const headers = ['Date', 'Product', 'Type', 'Quantity', 'UOM', 'Before', 'After', 'Reference', 'Reason', 'Created By'];
    const rows = filteredTransactions.map(t => [
      new Date(t.created_at).toLocaleString('en-US', { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true }),
      t.product_name || '—',
      t.transaction_type,
      parseFloat(t.quantity).toFixed(3),
      t.uom_code || '',
      parseFloat(t.stock_before).toFixed(3),
      parseFloat(t.stock_after).toFixed(3),
      t.reference_type === 'manual_adjustment' ? 'Manual' : 
      t.reference_type === 'food_order' ? `Order #${t.reference_id}` :
      t.reference_type === 'reservation' ? `Reservation #${t.reference_id}` : t.reference_type,
      t.reason || '—',
      t.created_by_name || '—'
    ]);

    let csv = headers.join(',') + '\n';
    rows.forEach(row => {
      csv += row.map(cell => `"${String(cell).replace(/"/g, '""')}"`).join(',') + '\n';
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', `inventory_transactions_${new Date().toISOString().split('T')[0]}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }

  function openAdjustModal() {
    document.getElementById('adjustForm').reset();
    document.getElementById('currentStock').value = '';
    document.getElementById('adjustModal').classList.add('open');
  }

  function closeAdjustModal() {
    document.getElementById('adjustModal').classList.remove('open');
  }

  document.getElementById('productId')?.addEventListener('change', function() {
    const product = products.find(p => p.product_id === this.value);
    if (product) {
      document.getElementById('currentStock').value = `${product.stock_on_hand || 0} ${product.uom_code || ''}`;
    }
  });

  async function saveAdjustment(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());

    try {
      const response = await fetch('../api/inventory_transactions.php?action=adjust', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify(data)
      });

      const result = await response.json();
      if (result.success) {
        showAlert('Success', result.message || 'Stock adjusted successfully.', () => {
          closeAdjustModal();
          fetchTransactions();
          updateStats();
          renderTable();
        });
      } else {
        showAlert('Error', result.message || 'Failed to adjust stock.');
      }
    } catch (error) {
      console.error('Error saving adjustment:', error);
      showAlert('Error', 'An error occurred while saving the adjustment.');
    }
  }

  async function rollbackTransaction(transactionId) {
    showAlert('Confirm Rollback', 'Are you sure you want to rollback this transaction? This will add the stock back.', async () => {
      try {
        const response = await fetch('../api/inventory_transactions.php?action=rollback', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken
          },
          body: JSON.stringify({ transaction_id: transactionId, reason: 'Manual rollback' })
        });

        const result = await response.json();
        if (result.success) {
          showAlert('Success', result.message || 'Transaction rolled back successfully.', () => {
            fetchTransactions();
            updateStats();
            renderTable();
          });
        } else {
          showAlert('Error', result.message || 'Failed to rollback transaction.');
        }
      } catch (error) {
        console.error('Error rolling back transaction:', error);
        showAlert('Error', 'An error occurred while rolling back the transaction.');
      }
    });
  }

  // Custom alert functions
  function showAlert(title, message, onConfirm) {
    document.getElementById('alertTitle').textContent = title;
    document.getElementById('alertMessage').textContent = message;
    document.getElementById('alertModal').classList.add('open');
    
    const confirmBtn = document.getElementById('alertConfirmBtn');
    confirmBtn.onclick = function() {
      closeAlert();
      if (onConfirm) onConfirm();
    };
  }

  function closeAlert() {
    document.getElementById('alertModal').classList.remove('open');
  }
</script>
