<?php
if (false) {
    try {
        error_log("Starting payment method deletion process...");
        
        $method_id = $_POST['method_id'] ?? '';
        error_log("Deleting method_id: " . $method_id);

        if (empty($method_id)) {
            throw new Exception("Payment Method ID is required");
        }

        // Start transaction
        $pdo->beginTransaction();

        // Delete payment method from database
        $stmt = $pdo->prepare("DELETE FROM payment_methods WHERE method_id = ?");
        $stmt->execute([$method_id]);
        error_log("Deleted payment method from database");

        $pdo->commit();

        // Redirect to avoid form resubmission
        header('Location: payment_methods.php?success=payment_method_deleted');
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Error deleting payment method: " . $e->getMessage());
        $error_message = "Failed to delete payment method. Please try again. Error: " . $e->getMessage();
    }
}

// Handle Edit Payment Method Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_payment_method') {
    try {
        error_log("Starting payment method edit process...");
        
        $method_id = $_POST['method_id'] ?? '';
        error_log("Editing method_id: " . $method_id);

        if (empty($method_id)) {
            throw new Exception("Payment Method ID is required");
        }

        // Start transaction
        $pdo->beginTransaction();

        // Update payment method data
        $stmt = $pdo->prepare("
            UPDATE payment_methods SET
                method_code = ?,
                method_label = ?,
                method_type = ?,
                icon_url = ?,
                is_active = ?,
                sort_order = ?
            WHERE method_id = ?
        ");

        $stmt->execute([
            $_POST['method_code'],
            $_POST['method_label'],
            $_POST['method_type'],
            $_POST['icon_url'] ?? null,
            $_POST['is_active'] ?? 1,
            $_POST['sort_order'] ?? 0,
            $method_id
        ]);

        $pdo->commit();

        // Redirect to avoid form resubmission
        header('Location: payment_methods.php?success=payment_method_updated');
        exit;

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("PDO Error editing payment method: " . $e->getMessage());
        $error_message = "Failed to update payment method. Please try again. Error: " . $e->getMessage();
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("General Error editing payment method: " . $e->getMessage());
        $error_message = "Failed to update payment method. Please try again. Error: " . $e->getMessage();
    }
}

// Handle Add Payment Method Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_payment_method') {
    try {
        error_log("Starting payment method addition process...");

        // Start transaction
        $pdo->beginTransaction();

        // Insert payment method data
        $stmt = $pdo->prepare("
            INSERT INTO payment_methods (
                method_code, method_label, method_type, icon_url, is_active, sort_order
            ) VALUES (?, ?, ?, ?, ?, ?)
        ");

        $params = [
            $_POST['method_code'],
            $_POST['method_label'],
            $_POST['method_type'],
            $_POST['icon_url'] ?? null,
            $_POST['is_active'] ?? 1,
            $_POST['sort_order'] ?? 0
        ];

        error_log("Executing payment method insert with params: " . json_encode($params));
        $stmt->execute($params);
        error_log("Payment method inserted successfully");

        $pdo->commit();

        // Redirect to avoid form resubmission
        header('Location: payment_methods.php?success=payment_method_added');
        exit;

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("PDO Error adding payment method: " . $e->getMessage());
        error_log("Error code: " . $e->getCode());
        error_log("Error info: " . print_r($e->errorInfo, true));
        $error_message = "Failed to add payment method. Please try again. Error: " . $e->getMessage();
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("General Error adding payment method: " . $e->getMessage());
        $error_message = "Failed to add payment method. Please try again. Error: " . $e->getMessage();
    }
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

  .form-input, .form-select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    font-size: 13px;
    outline: none
  }

  .form-input:focus, .form-select:focus {
    border-color: #185FA5
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
    width: 120px;
    font-size: 13px;
    color: #666;
    font-weight: 500
  }

  .view-value {
    flex: 1;
    font-size: 13px;
    color: #1a1a1a
  }
</style>

<!-- PAGE CONTENT -->

<div class="page">
  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total Methods</div>
      <div class="stat-value" id="statTotal">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Active</div>
      <div class="stat-value" id="statActive">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Cash</div>
      <div class="stat-value" id="statCash">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Digital Wallet</div>
      <div class="stat-value" id="statDigital">-</div>
    </div>
  </div>

  <!-- Payment Methods Table -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">Payment Methods</div>
      <button onclick="openAddPaymentMethodModal()" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i> Add Payment Method
      </button>
    </div>
    <div class="table-wrap">
      <table id="paymentMethodsTable">
        <thead>
          <tr>
            <th>Method</th>
            <th>Code</th>
            <th>Type</th>
            <th class="center">Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="paymentMethodsTableBody">
          <tr>
            <td colspan="5" class="empty-state">
              <i class="fa-regular fa-clipboard"></i>
              Loading...
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Payment Method Modal -->
<div id="addPaymentMethodModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Add New Payment Method</div>
      <button onclick="closeAddPaymentMethodModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <form id="addPaymentMethodForm">
        <div class="form-group">
          <label for="method_code" class="form-label">Method Code</label>
          <input type="text" name="method_code" id="method_code" required class="form-input" placeholder="e.g., gcash, maya, bdo">
        </div>
        <div class="form-group">
          <label for="method_label" class="form-label">Method Label</label>
          <input type="text" name="method_label" id="method_label" required class="form-input" placeholder="e.g., GCash, Maya, BDO">
        </div>
        <div class="form-group">
          <label for="method_type" class="form-label">Method Type</label>
          <select name="method_type" id="method_type" required class="form-select">
            <option value="cash">Cash</option>
            <option value="digital_wallet">Digital Wallet</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="card">Card</option>
            <option value="check">Check</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="form-group">
          <label for="icon_url" class="form-label">Icon URL</label>
          <input type="text" name="icon_url" id="icon_url" class="form-input" placeholder="https://...">
        </div>
        <div class="form-group">
          <label for="sort_order" class="form-label">Sort Order</label>
          <input type="number" name="sort_order" id="sort_order" value="0" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-checkbox">
            <input type="checkbox" name="is_active" value="1" checked>
            <span>Active</span>
          </label>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" onclick="closeAddPaymentMethodModal()" class="btn btn-secondary">Cancel</button>
      <button type="button" onclick="submitAddPaymentMethod()" class="btn btn-primary">Add Payment Method</button>
    </div>
  </div>
</div>

<!-- Edit Payment Method Modal -->
<div id="editPaymentMethodModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Edit Payment Method</div>
      <button onclick="closeEditPaymentMethodModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <form id="editPaymentMethodForm">
        <input type="hidden" id="edit_method_id">
        <div class="form-group">
          <label for="edit_method_code" class="form-label">Method Code</label>
          <input type="text" name="method_code" id="edit_method_code" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_method_label" class="form-label">Method Label</label>
          <input type="text" name="method_label" id="edit_method_label" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_method_type" class="form-label">Method Type</label>
          <select name="method_type" id="edit_method_type" required class="form-select">
            <option value="cash">Cash</option>
            <option value="digital_wallet">Digital Wallet</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="card">Card</option>
            <option value="check">Check</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="form-group">
          <label for="edit_icon_url" class="form-label">Icon URL</label>
          <input type="text" name="icon_url" id="edit_icon_url" class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_sort_order" class="form-label">Sort Order</label>
          <input type="number" name="sort_order" id="edit_sort_order" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-checkbox">
            <input type="checkbox" name="is_active" value="1" id="edit_is_active">
            <span>Active</span>
          </label>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" onclick="closeEditPaymentMethodModal()" class="btn btn-secondary">Cancel</button>
      <button type="button" onclick="submitEditPaymentMethod()" class="btn btn-primary">Update Payment Method</button>
    </div>
  </div>
</div>

<!-- View Payment Method Modal -->
<div id="viewPaymentMethodModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Payment Method Details</div>
      <button onclick="closeViewPaymentMethodModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <div class="view-row">
        <div class="view-label">Method Label</div>
        <div class="view-value" id="view_method_label"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Method Code</div>
        <div class="view-value" id="view_method_code"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Method Type</div>
        <div class="view-value" id="view_method_type"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Icon URL</div>
        <div class="view-value" id="view_icon_url"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Sort Order</div>
        <div class="view-value" id="view_sort_order"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Status</div>
        <div class="view-value" id="view_is_active"></div>
      </div>
    </div>
  </div>
</div>

<script>
  let paymentMethodsData = [];

  document.addEventListener('DOMContentLoaded', function () {
    fetchPaymentMethods();
  });

  async function fetchPaymentMethods() {
    try {
      const res = await fetch('../api/payment_methods.php?action=list');
      const result = await res.json();
      if (result.success) {
        paymentMethodsData = result.data;
        renderTable();
        renderStats();
      } else {
        document.getElementById('paymentMethodsTableBody').innerHTML = `<tr><td colspan="5" class="empty-state"><i>⚠</i>${result.message}</td></tr>`;
      }
    } catch (e) {
      console.error('Error fetching payment methods:', e);
      document.getElementById('paymentMethodsTableBody').innerHTML = `<tr><td colspan="5" class="empty-state"><i>⚠</i>Failed to load data</td></tr>`;
    }
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = paymentMethodsData.length;
    document.getElementById('statActive').textContent = paymentMethodsData.filter(m => m.is_active == 1).length;
    document.getElementById('statCash').textContent = paymentMethodsData.filter(m => m.method_type === 'cash').length;
    document.getElementById('statDigital').textContent = paymentMethodsData.filter(m => m.method_type === 'digital_wallet').length;
  }

  function renderTable() {
    const tbody = document.getElementById('paymentMethodsTableBody');
    if (!paymentMethodsData.length) {
      tbody.innerHTML = `<tr><td colspan="5" class="empty-state"><i class="fa-regular fa-clipboard"></i>No payment methods found. <a href="javascript:;" onclick="openAddPaymentMethodModal()" style="color:#185FA5;font-weight:500">Add your first payment method</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = paymentMethodsData.map(m => {
      const typeLabel = (m.method_type || '').replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
      const statusBadge = m.is_active == 1
        ? `<span class="status-badge active">Active</span>`
        : `<span class="status-badge inactive">Inactive</span>`;
      return `
        <tr class="payment-method-row">
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:36px;height:36px;background:#185FA5;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff">
                <i class="fas fa-credit-card"></i>
              </div>
              <strong>${m.method_label}</strong>
            </div>
          </td>
          <td>${m.method_code}</td>
          <td>${typeLabel}</td>
          <td style="text-align:center">${statusBadge}</td>
          <td>
            <div class="action-btns">
              <button onclick="openViewPaymentMethodModal('${m.method_id}')" class="action-btn">View</button>
              <button onclick="openEditPaymentMethodModal('${m.method_id}')" class="action-btn">Edit</button>
              <button onclick="confirmDeletePaymentMethod('${m.method_id}')" class="action-btn delete">Delete</button>
            </div>
          </td>
        </tr>`;
    }).join('');
  }

  function openAddPaymentMethodModal() {
    document.getElementById('addPaymentMethodForm').reset();
    document.getElementById('addPaymentMethodModal').style.display = 'flex';
  }

  function closeAddPaymentMethodModal() {
    document.getElementById('addPaymentMethodModal').style.display = 'none';
  }

  async function submitAddPaymentMethod() {
    const data = {
      method_code: document.getElementById('method_code').value,
      method_label: document.getElementById('method_label').value,
      method_type: document.getElementById('method_type').value,
      description: document.getElementById('icon_url').value,
      sort_order: document.getElementById('sort_order').value,
      is_active: document.querySelector('[name="is_active"]').checked ? 1 : 0,
    };
    try {
      const res = await fetch('../api/payment_methods.php?action=create', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeAddPaymentMethodModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Payment method added successfully', timer: 2000, showConfirmButton: false });
        fetchPaymentMethods();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to add payment method', confirmButtonColor: '#3085d6' });
    }
  }

  function openEditPaymentMethodModal(methodId) {
    const method = paymentMethodsData.find(m => m.method_id === methodId);
    if (method) {
      document.getElementById('edit_method_id').value = method.method_id;
      document.getElementById('edit_method_code').value = method.method_code;
      document.getElementById('edit_method_label').value = method.method_label;
      document.getElementById('edit_method_type').value = method.method_type;
      document.getElementById('edit_icon_url').value = method.icon_url || '';
      document.getElementById('edit_sort_order').value = method.sort_order;
      document.getElementById('edit_is_active').checked = method.is_active == 1;
      document.getElementById('editPaymentMethodModal').style.display = 'flex';
    }
  }

  function closeEditPaymentMethodModal() {
    document.getElementById('editPaymentMethodModal').style.display = 'none';
  }

  async function submitEditPaymentMethod() {
    const data = {
      method_id: document.getElementById('edit_method_id').value,
      method_code: document.getElementById('edit_method_code').value,
      method_label: document.getElementById('edit_method_label').value,
      method_type: document.getElementById('edit_method_type').value,
      description: document.getElementById('edit_icon_url').value,
      sort_order: document.getElementById('edit_sort_order').value,
      is_active: document.getElementById('edit_is_active').checked ? 1 : 0,
    };
    try {
      const res = await fetch('../api/payment_methods.php?action=update', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeEditPaymentMethodModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Payment method updated successfully', timer: 2000, showConfirmButton: false });
        fetchPaymentMethods();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to update payment method', confirmButtonColor: '#3085d6' });
    }
  }

  function openViewPaymentMethodModal(methodId) {
    const method = paymentMethodsData.find(m => m.method_id === methodId);
    if (method) {
      document.getElementById('view_method_label').textContent = method.method_label;
      document.getElementById('view_method_code').textContent = method.method_code;
      document.getElementById('view_method_type').textContent = method.method_type;
      document.getElementById('view_icon_url').textContent = method.icon_url || 'N/A';
      document.getElementById('view_sort_order').textContent = method.sort_order;
      document.getElementById('view_is_active').textContent = method.is_active == 1 ? 'Active' : 'Inactive';
      document.getElementById('viewPaymentMethodModal').style.display = 'flex';
    }
  }

  function closeViewPaymentMethodModal() {
    document.getElementById('viewPaymentMethodModal').style.display = 'none';
  }

  function confirmDeletePaymentMethod(methodId) {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          const res = await fetch('../api/payment_methods.php?action=delete', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ method_id: methodId })
          });
          const r = await res.json();
          if (r.success) {
            Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Payment method deleted.', timer: 1500, showConfirmButton: false });
            fetchPaymentMethods();
          } else {
            Swal.fire({ icon: 'error', title: 'Error!', text: r.message, confirmButtonColor: '#3085d6' });
          }
        } catch (e) {
          Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to delete payment method', confirmButtonColor: '#3085d6' });
        }
      }
    });
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    document.querySelectorAll('#paymentMethodsTable .payment-method-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }
</script>