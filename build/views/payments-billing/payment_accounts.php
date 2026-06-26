<?php
// Helper function to generate UUID
function generateUUID()
{
  return sprintf(
    '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0x0fff) | 0x4000,
    mt_rand(0, 0x3fff) | 0x8000,
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff)
  );
}
?>

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Lora:ital,wght@0,600;1,500&display=swap" rel="stylesheet">

<style>
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0
  }

  body {
    font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
    background: #f5f5f5;
    color: #1a1a1a
  }

  .page {
    padding: 24px;
    max-width: 100%;
    margin: 0 auto
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px
  }

  .stat-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 16px
  }

  .stat-label {
    font-size: 13px;
    font-weight: 500;
    color: #666;
    margin-bottom: 8px
  }

  .stat-value {
    font-size: 28px;
    font-weight: 600;
    color: #1a1a1a
  }

  .card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px
  }

  .card-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap
  }

  .card-title {
    font-family: 'Lora', Georgia, serif;
    font-size: 16px;
    font-weight: 600;
    font-style: italic
  }

  .toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap
  }

  .search-wrap {
    display: flex;
    align-items: center;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    padding: 6px 10px 6px 30px;
    background: #f9f9f9;
    position: relative;
    min-width: 200px
  }

  .search-wrap i {
    position: absolute;
    left: 8px;
    color: #666;
    font-size: 12px
  }

  .search-input {
    border: none;
    outline: none;
    background: transparent;
    font-size: 13px;
    width: 100%;
    color: #1a1a1a
  }

  .filter-select {
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 13px;
    color: #1a1a1a;
    outline: none;
    background: #f9f9f9
  }

  .btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all .2s
  }

  .btn-primary {
    background: #185FA5;
    color: #fff
  }

  .btn-primary:hover {
    background: #145090
  }

  .btn-secondary {
    background: #fff;
    color: #1a1a1a;
    border: 1px solid #e0e0e0
  }

  .btn-secondary:hover {
    background: #f5f5f5
  }

  .btn-danger {
    background: #fff;
    color: #dc2626;
    border: 1px solid #fecaca
  }

  .btn-danger:hover {
    background: #fef2f2
  }

  .table-wrap {
    overflow-x: auto
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px
  }

  thead th {
    padding: 10px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 500;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    border-bottom: 1px solid #e0e0e0;
    white-space: nowrap
  }

  thead th.center {
    text-align: center
  }

  tbody tr {
    border-bottom: 1px solid #e0e0e0;
    transition: background .1s
  }

  tbody tr:last-child {
    border-bottom: none
  }

  tbody tr:hover {
    background: #f9f9f9
  }

  td {
    padding: 11px 16px;
    vertical-align: middle;
    white-space: nowrap
  }

  .status-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
    text-transform: uppercase
  }

  .status-badge.active {
    background: #E8F5E9;
    color: #2E7D32
  }

  .status-badge.inactive {
    background: #F5F5F5;
    color: #666
  }

  .default-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
    text-transform: uppercase;
    background: #FEF3C7;
    color: #92400E
  }

  .action-btns {
    display: flex;
    gap: 6px
  }

  .action-btn {
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid #e0e0e0;
    background: #fff;
    color: #1a1a1a;
    transition: all .2s
  }

  .action-btn:hover {
    background: #f5f5f5
  }

  .action-btn.delete:hover {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fecaca
  }

  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999
  }

  .modal {
    background: #fff;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25)
  }

  .modal-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: space-between
  }

  .modal-title {
    font-family: 'Lora', Georgia, serif;
    font-size: 18px;
    font-weight: 600;
    font-style: italic
  }

  .modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666
  }

  .modal-close:hover {
    color: #1a1a1a
  }

  .modal-body {
    padding: 20px;
    overflow-y: auto;
    flex: 1
  }

  .form-group {
    margin-bottom: 16px
  }

  .form-label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #1a1a1a;
    margin-bottom: 6px
  }

  .form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    font-size: 13px;
    outline: none
  }

  .form-input:focus, .form-select:focus, .form-textarea:focus {
    border-color: #185FA5
  }

  .form-textarea {
    resize: vertical;
    min-height: 80px
  }

  .form-checkbox {
    display: flex;
    align-items: center;
    gap: 8px
  }

  .modal-footer {
    padding: 16px 20px;
    border-top: 1px solid #e0e0e0;
    display: flex;
    justify-content: flex-end;
    gap: 10px
  }

  .empty-state {
    padding: 48px 24px;
    text-align: center;
    color: #666
  }

  .empty-state i {
    font-size: 32px;
    margin-bottom: 12px;
    display: block;
    color: #ccc
  }

  .view-row {
    display: flex;
    margin-bottom: 12px
  }

  .view-label {
    width: 140px;
    font-size: 13px;
    color: #666;
    font-weight: 500
  }

  .view-value {
    flex: 1;
    font-size: 13px;
    color: #1a1a1a
  }

  .pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    border-top: 1px solid #e0e0e0;
    gap: 12px;
    flex-wrap: wrap
  }

  .pag-info {
    font-size: 13px;
    color: #666
  }

  .per-page-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #666
  }

  .per-page-wrap select {
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 12px;
    color: #1a1a1a;
    outline: none;
    background: #fff
  }

  .pag-controls {
    display: flex;
    align-items: center;
    gap: 4px
  }

  .pag-btn {
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    padding: 5px 10px;
    font-size: 12px;
    color: #1a1a1a;
    background: #fff;
    cursor: pointer;
    transition: all .2s;
    min-width: 32px
  }

  .pag-btn:hover:not(:disabled) {
    background: #f5f5f5;
    border-color: #ccc
  }

  .pag-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed
  }

  .pag-btn.active {
    background: #185FA5;
    color: white;
    border-color: transparent
  }

  /* ── Responsive ──────────────────────────────────── */
  @media (max-width: 640px) {
    .stats-grid {
      grid-template-columns: 1fr;
      gap: 12px
    }
    .stat-card {
      padding: 14px 16px
    }
    .stat-label {
      font-size: 12px
    }
    .stat-value {
      font-size: 24px
    }
    .page {
      padding: 16px
    }
    .card-header {
      flex-direction: column;
      align-items: stretch;
      gap: 16px;
      padding: 16px 18px
    }
    .toolbar {
      flex-direction: column;
      align-items: stretch
    }
    .search-wrap, .filter-select, .btn {
      width: 100%
    }
    .btn {
      justify-content: center
    }
    thead th {
      padding: 8px 12px;
      font-size: 10px
    }
    td {
      padding: 8px 12px;
      font-size: 12px
    }
    .status-badge {
      font-size: 10px;
      padding: 2px 8px
    }
    .action-btn {
      padding: 4px 8px;
      font-size: 10px
    }
    .empty-state {
      padding: 40px 20px
    }
    .empty-state i {
      font-size: 28px
    }
    .modal {
      max-width: 90vw
    }
    .modal {
      max-width: none;
      max-height: 95vh;
      border-radius: 12px
    }
    .modal-body {
      padding: 18px
    }
    .modal-body {
      padding: 16px;
      overflow-y: auto;
      overflow-x: hidden
    }
    .modal-footer {
      flex-direction: column
    }
    .modal-footer .btn {
      width: 100%;
      justify-content: center
    }
    .modal-header {
      padding: 14px 18px
    }
    .modal-title {
      font-size: 16px
    }
    .modal-close {
      font-size: 20px
    }
    .form-label {
      font-size: 12px
    }
    .form-input,
    .form-select,
    .form-textarea {
      font-size: 12px;
      padding: 7px 10px
    }
    .view-row {
      flex-direction: column;
      gap: 4px
    }
    .view-label {
      width: auto;
      font-size: 12px
    }
    .view-value {
      font-size: 12px
    }
    .pagination {
      flex-direction: column;
      align-items: flex-start;
      gap: 8px
    }
    .per-page-wrap {
      display: none
    }
  }
</style>

<!-- PAGE CONTENT -->

<div class="page">
  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total Accounts</div>
      <div class="stat-value" id="statTotal">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Active</div>
      <div class="stat-value" id="statActive">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Default</div>
      <div class="stat-value" id="statDefault">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Bank Transfer</div>
      <div class="stat-value" id="statBank">-</div>
    </div>
  </div>

  <!-- Payment Accounts Table -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">Payment Accounts</div>
      <div class="toolbar">
        <div class="search-wrap">
          <i class="fas fa-magnifying-glass"></i>
          <input type="text" id="searchInput" class="search-input" placeholder="Search accounts..." oninput="filterTable()">
        </div>
        <select class="filter-select" id="statusFilter" onchange="filterTable()">
          <option value="">All status</option>
          <option value="1">Active</option>
          <option value="0">Inactive</option>
        </select>
        <button class="btn btn-primary" onclick="openAddPaymentAccountModal()">
          <i class="fas fa-plus mr-1"></i> Add Account
        </button>
      </div>
    </div>
    <div class="table-wrap">
      <table id="paymentAccountsTable">
        <thead>
          <tr>
            <th>Account Name</th>
            <th>Method</th>
            <th>Bank</th>
            <th>Account Number</th>
            <th class="center">Default</th>
            <th class="center">Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="paymentAccountsTableBody">
          <tr>
            <td colspan="7" class="empty-state">
              <i class="fa-regular fa-clipboard"></i>
              Loading...
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div id="paginationContainer"></div>
  </div>
</div>

<!-- Add Payment Account Modal -->
<div id="addPaymentAccountModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Add New Payment Account</div>
      <button class="modal-close" onclick="closeAddPaymentAccountModal()">×</button>
    </div>
    <div class="modal-body">
      <form id="addPaymentAccountForm">
        <input type="hidden" name="account_id" id="formAccountId">
        <div class="form-group">
          <label for="formPropertyId" class="form-label">Property ID *</label>
          <input type="text" name="property_id" id="formPropertyId" required class="form-input" placeholder="Property UUID">
        </div>
        <div class="form-group">
          <label for="formMethodId" class="form-label">Payment Method *</label>
          <select name="method_id" id="formMethodId" required class="form-select">
            <option value="">Select Method</option>
          </select>
        </div>
        <div class="form-group">
          <label for="formAccountName" class="form-label">Account Name *</label>
          <input type="text" name="account_name" id="formAccountName" required class="form-input" placeholder="e.g., Main GCash Account">
        </div>
        <div class="form-group">
          <label for="formAccountNumber" class="form-label">Account Number</label>
          <input type="text" name="account_number" id="formAccountNumber" class="form-input" placeholder="e.g., 09123456789">
        </div>
        <div class="form-group">
          <label for="formBankName" class="form-label">Bank Name</label>
          <input type="text" name="bank_name" id="formBankName" class="form-input" placeholder="e.g., BDO">
        </div>
        <div class="form-group">
          <label for="formBranchName" class="form-label">Branch Name</label>
          <input type="text" name="branch_name" id="formBranchName" class="form-input" placeholder="e.g., Main Branch">
        </div>
        <div class="form-group">
          <label for="formQrCodeImage" class="form-label">QR Code Image URL</label>
          <input type="text" name="qr_code_image" id="formQrCodeImage" class="form-input" placeholder="https://...">
        </div>
        <div class="form-group">
          <label for="formInstructions" class="form-label">Instructions</label>
          <textarea name="instructions" id="formInstructions" class="form-textarea" placeholder="Payment instructions..."></textarea>
        </div>
        <div class="form-group">
          <label class="form-checkbox">
            <input type="checkbox" name="is_default" id="formIsDefault" value="1">
            <span>Set as Default</span>
          </label>
        </div>
        <div class="form-group">
          <label class="form-checkbox">
            <input type="checkbox" name="is_active" id="formIsActive" value="1" checked>
            <span>Active</span>
          </label>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" onclick="closeAddPaymentAccountModal()">Cancel</button>
      <button type="button" class="btn btn-primary" onclick="submitAddPaymentAccount()">Add Account</button>
    </div>
  </div>
</div>

<!-- Edit Payment Account Modal -->
<div id="editPaymentAccountModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Edit Payment Account</div>
      <button class="modal-close" onclick="closeEditPaymentAccountModal()">×</button>
    </div>
    <div class="modal-body">
      <form id="editPaymentAccountForm">
        <input type="hidden" name="account_id" id="editFormAccountId">
        <div class="form-group">
          <label for="editFormPropertyId" class="form-label">Property ID *</label>
          <input type="text" name="property_id" id="editFormPropertyId" required class="form-input">
        </div>
        <div class="form-group">
          <label for="editFormMethodId" class="form-label">Payment Method *</label>
          <select name="method_id" id="editFormMethodId" required class="form-select">
            <option value="">Select Method</option>
          </select>
        </div>
        <div class="form-group">
          <label for="editFormAccountName" class="form-label">Account Name *</label>
          <input type="text" name="account_name" id="editFormAccountName" required class="form-input">
        </div>
        <div class="form-group">
          <label for="editFormAccountNumber" class="form-label">Account Number</label>
          <input type="text" name="account_number" id="editFormAccountNumber" class="form-input">
        </div>
        <div class="form-group">
          <label for="editFormBankName" class="form-label">Bank Name</label>
          <input type="text" name="bank_name" id="editFormBankName" class="form-input">
        </div>
        <div class="form-group">
          <label for="editFormBranchName" class="form-label">Branch Name</label>
          <input type="text" name="branch_name" id="editFormBranchName" class="form-input">
        </div>
        <div class="form-group">
          <label for="editFormQrCodeImage" class="form-label">QR Code Image URL</label>
          <input type="text" name="qr_code_image" id="editFormQrCodeImage" class="form-input">
        </div>
        <div class="form-group">
          <label for="editFormInstructions" class="form-label">Instructions</label>
          <textarea name="instructions" id="editFormInstructions" class="form-textarea"></textarea>
        </div>
        <div class="form-group">
          <label class="form-checkbox">
            <input type="checkbox" name="is_default" id="editFormIsDefault" value="1">
            <span>Set as Default</span>
          </label>
        </div>
        <div class="form-group">
          <label class="form-checkbox">
            <input type="checkbox" name="is_active" id="editFormIsActive" value="1">
            <span>Active</span>
          </label>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" onclick="closeEditPaymentAccountModal()">Cancel</button>
      <button type="button" class="btn btn-primary" onclick="submitEditPaymentAccount()">Update Account</button>
    </div>
  </div>
</div>

<script>
  // CSRF Token for API requests
  const CSRF_TOKEN = <?php echo json_encode(CSRF_TOKEN); ?>;

  let paymentAccountsData = [];
  let paymentMethodsData = [];
  let currentPage = 1;
  let itemsPerPage = 10;
  let totalItems = 0;

  document.addEventListener('DOMContentLoaded', function () {
    fetchPaymentMethods();
    fetchPaymentAccounts();
  });

  async function fetchPaymentMethods() {
    try {
      const res = await fetch('../api/payment_methods.php?action=list');
      const result = await res.json();
      if (result.success) {
        paymentMethodsData = result.data;
      }
    } catch (e) {
      console.error('Error fetching payment methods:', e);
    }
  }

  function populateMethodSelects() {
    const selects = [document.getElementById('formMethodId'), document.getElementById('editFormMethodId')];
    selects.forEach(select => {
      if (select) {
        select.innerHTML = '<option value="">Select Method</option>';
        paymentMethodsData.forEach(method => {
          const option = document.createElement('option');
          option.value = method.method_id;
          option.textContent = method.method_label;
          select.appendChild(option);
        });
      }
    });
  }

  async function fetchPaymentAccounts() {
    try {
      const offset = (currentPage - 1) * itemsPerPage;
      const res = await fetch(`../api/payment_accounts.php?action=list&limit=${itemsPerPage}&offset=${offset}`);
      const result = await res.json();
      if (result.success) {
        paymentAccountsData = result.data;
        totalItems = result.total ?? paymentAccountsData.length;
        renderTable();
        renderStats();
        renderPagination();
      } else {
        document.getElementById('paymentAccountsTableBody').innerHTML = `<tr><td colspan="7" class="empty-state"><i>⚠</i>${result.message}</td></tr>`;
      }
    } catch (e) {
      console.error('Error fetching payment accounts:', e);
      document.getElementById('paymentAccountsTableBody').innerHTML = `<tr><td colspan="7" class="empty-state"><i>⚠</i>Failed to load data</td></tr>`;
    }
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = paymentAccountsData.length;
    document.getElementById('statActive').textContent = paymentAccountsData.filter(a => a.is_active == 1).length;
    document.getElementById('statDefault').textContent = paymentAccountsData.filter(a => a.is_default == 1).length;
    document.getElementById('statBank').textContent = paymentAccountsData.filter(a => a.method_name && a.method_name.toLowerCase().includes('bank')).length;
  }

  function renderTable() {
    const tbody = document.getElementById('paymentAccountsTableBody');
    if (!paymentAccountsData.length) {
      tbody.innerHTML = `<tr><td colspan="7" class="empty-state"><i class="fa-regular fa-clipboard"></i>No payment accounts found. <button class="action-btn" onclick="openAddPaymentAccountModal()" style="color:#185FA5;font-weight:500;border:none;background:none;padding:0">Add your first payment account</button>.</td></tr>`;
      return;
    }
    tbody.innerHTML = paymentAccountsData.map(a => {
      const defaultBadge = a.is_default == 1
        ? `<span class="default-badge">Default</span>`
        : `<span style="color:#999;font-size:11px">—</span>`;
      const statusBadge = a.is_active == 1
        ? `<span class="status-badge active">Active</span>`
        : `<span class="status-badge inactive">Inactive</span>`;
      return `
        <tr class="payment-account-row" data-account-id="${a.account_id}">
          <td><strong>${a.account_name}</strong></td>
          <td>${a.method_label || '—'}</td>
          <td>${a.bank_name || '—'}</td>
          <td>${a.account_number || '—'}</td>
          <td style="text-align:center">${defaultBadge}</td>
          <td style="text-align:center">${statusBadge}</td>
          <td>
            <div class="action-btns">
              <button class="action-btn" onclick="openEditPaymentAccountModal('${a.account_id}')">Edit</button>
              <button class="action-btn delete" onclick="confirmDeletePaymentAccount('${a.account_id}', '${a.account_name.replace(/'/g, "\\'")}')">Delete</button>
            </div>
          </td>
        </tr>`;
    }).join('');
  }

  function filterTable() {
    const filter = (document.getElementById('searchInput')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilter')?.value || '');
    const rows = document.querySelectorAll('#paymentAccountsTableBody .payment-account-row');
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      const matchText = !filter || text.includes(filter);
      const matchStatus = !statusFilter || row.textContent.includes(statusFilter === '1' ? 'active' : 'inactive');
      row.style.display = (matchText && matchStatus) ? '' : 'none';
    });
  }

  function openAddPaymentAccountModal() {
    document.getElementById('addPaymentAccountForm').reset();
    document.getElementById('formAccountId').value = generateUUID();
    document.getElementById('formIsActive').checked = true;
    document.getElementById('formIsDefault').checked = false;
    populateMethodSelects();
    document.getElementById('addPaymentAccountModal').style.display = 'flex';
  }

  function closeAddPaymentAccountModal() {
    document.getElementById('addPaymentAccountModal').style.display = 'none';
  }

  async function submitAddPaymentAccount() {
    const data = {
      account_id: document.getElementById('formAccountId').value,
      property_id: document.getElementById('formPropertyId').value,
      method_id: document.getElementById('formMethodId').value,
      account_name: document.getElementById('formAccountName').value,
      account_number: document.getElementById('formAccountNumber').value,
      bank_name: document.getElementById('formBankName').value,
      branch_name: document.getElementById('formBranchName').value,
      qr_code_image: document.getElementById('formQrCodeImage').value,
      instructions: document.getElementById('formInstructions').value,
      is_default: document.getElementById('formIsDefault').checked ? 1 : 0,
      is_active: document.getElementById('formIsActive').checked ? 1 : 0,
      created_by: null
    };
    try {
      const res = await fetch('../api/payment_accounts.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'create', ...data, csrf_token: CSRF_TOKEN })
      });
      const result = await res.json();
      if (result.success) {
        closeAddPaymentAccountModal();
        alert('Payment account added successfully');
        fetchPaymentAccounts();
      } else {
        alert('Error: ' + result.message);
      }
    } catch (e) {
      console.error('Error adding payment account:', e);
      alert('Failed to add payment account');
    }
  }

  function openEditPaymentAccountModal(accountId) {
    const account = paymentAccountsData.find(a => a.account_id == accountId);
    if (account) {
      populateMethodSelects();
      document.getElementById('editFormAccountId').value = account.account_id;
      document.getElementById('editFormPropertyId').value = account.property_id;
      document.getElementById('editFormMethodId').value = account.method_id;
      document.getElementById('editFormAccountName').value = account.account_name;
      document.getElementById('editFormAccountNumber').value = account.account_number || '';
      document.getElementById('editFormBankName').value = account.bank_name || '';
      document.getElementById('editFormBranchName').value = account.branch_name || '';
      document.getElementById('editFormQrCodeImage').value = account.qr_code_image || '';
      document.getElementById('editFormInstructions').value = account.instructions || '';
      document.getElementById('editFormIsDefault').checked = account.is_default == 1;
      document.getElementById('editFormIsActive').checked = account.is_active == 1;
      document.getElementById('editPaymentAccountModal').style.display = 'flex';
    } else {
      console.error('Payment account not found:', accountId);
    }
  }

  function closeEditPaymentAccountModal() {
    document.getElementById('editPaymentAccountModal').style.display = 'none';
  }

  async function submitEditPaymentAccount() {
    const data = {
      account_id: document.getElementById('editFormAccountId').value,
      property_id: document.getElementById('editFormPropertyId').value,
      method_id: document.getElementById('editFormMethodId').value,
      account_name: document.getElementById('editFormAccountName').value,
      account_number: document.getElementById('editFormAccountNumber').value,
      bank_name: document.getElementById('editFormBankName').value,
      branch_name: document.getElementById('editFormBranchName').value,
      qr_code_image: document.getElementById('editFormQrCodeImage').value,
      instructions: document.getElementById('editFormInstructions').value,
      is_default: document.getElementById('editFormIsDefault').checked ? 1 : 0,
      is_active: document.getElementById('editFormIsActive').checked ? 1 : 0
    };
    try {
      const res = await fetch('../api/payment_accounts.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'update', ...data, csrf_token: CSRF_TOKEN })
      });
      const result = await res.json();
      if (result.success) {
        closeEditPaymentAccountModal();
        alert('Payment account updated successfully');
        fetchPaymentAccounts();
      } else {
        alert('Error: ' + result.message);
      }
    } catch (e) {
      console.error('Error updating payment account:', e);
      alert('Failed to update payment account');
    }
  }

  async function confirmDeletePaymentAccount(accountId, accountName) {
    if (!confirm(`Delete "${accountName}"? This action cannot be undone.`)) return;
    try {
      const res = await fetch('../api/payment_accounts.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'delete', account_id: accountId, csrf_token: CSRF_TOKEN })
      });
      const result = await res.json();
      if (result.success) {
        alert('Payment account deleted successfully');
        fetchPaymentAccounts();
      } else {
        alert('Error: ' + result.message);
      }
    } catch (e) {
      console.error('Error deleting payment account:', e);
      alert('Failed to delete payment account');
    }
  }

  function renderPagination() {
    const paginationContainer = document.getElementById('paginationContainer');
    if (!paginationContainer) return;

    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const startItem = totalItems === 0 ? 0 : (currentPage - 1) * itemsPerPage + 1;
    const endItem = Math.min(currentPage * itemsPerPage, totalItems);

    let paginationHtml = `
      <div class="pagination">
        <div class="pag-info">
          Showing ${startItem} to ${endItem} of ${totalItems} entries
        </div>
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap">
          <div class="per-page-wrap">
            Show
            <select onchange="changeItemsPerPage(this.value)">
              <option value="5" ${itemsPerPage === 5 ? 'selected' : ''}>5</option>
              <option value="10" ${itemsPerPage === 10 ? 'selected' : ''}>10</option>
              <option value="25" ${itemsPerPage === 25 ? 'selected' : ''}>25</option>
              <option value="50" ${itemsPerPage === 50 ? 'selected' : ''}>50</option>
            </select>
            entries
          </div>
          <div class="pag-controls">
            <button onclick="goToPage(1)" ${currentPage === 1 ? 'disabled' : ''} class="pag-btn">
              <i class="fas fa-chevrons-left"></i>
            </button>
            <button onclick="goToPage(currentPage - 1)" ${currentPage === 1 ? 'disabled' : ''} class="pag-btn">
              <i class="fas fa-chevron-left"></i>
            </button>
            ${getPageNumbers().map(page => `
              <button onclick="goToPage(${page})" ${page === currentPage ? 'disabled' : ''} class="pag-btn ${page === currentPage ? 'active' : ''}">
                ${page}
              </button>
            `).join('')}
            <button onclick="goToPage(currentPage + 1)" ${currentPage === totalPages ? 'disabled' : ''} class="pag-btn">
              <i class="fas fa-chevron-right"></i>
            </button>
            <button onclick="goToPage(totalPages)" ${currentPage === totalPages ? 'disabled' : ''} class="pag-btn">
              <i class="fas fa-chevrons-right"></i>
            </button>
          </div>
        </div>
      </div>
    `;

    paginationContainer.innerHTML = paginationHtml;
  }

  function getPageNumbers() {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const pages = [];
    const maxVisible = 5;

    if (totalPages <= maxVisible) {
      for (let i = 1; i <= totalPages; i++) {
        pages.push(i);
      }
    } else {
      if (currentPage <= 3) {
        for (let i = 1; i <= 4; i++) {
          pages.push(i);
        }
        pages.push('...');
        pages.push(totalPages);
      } else if (currentPage >= totalPages - 2) {
        pages.push(1);
        pages.push('...');
        for (let i = totalPages - 3; i <= totalPages; i++) {
          pages.push(i);
        }
      } else {
        pages.push(1);
        pages.push('...');
        for (let i = currentPage - 1; i <= currentPage + 1; i++) {
          pages.push(i);
        }
        pages.push('...');
        pages.push(totalPages);
      }
    }
    return pages;
  }

  function changeItemsPerPage(value) {
    itemsPerPage = parseInt(value);
    currentPage = 1;
    fetchPaymentAccounts();
  }

  function goToPage(page) {
    if (page === '...' || page < 1 || page > Math.ceil(totalItems / itemsPerPage)) return;
    currentPage = page;
    fetchPaymentAccounts();
  }
</script>
