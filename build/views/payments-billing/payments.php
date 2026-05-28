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

  .status-badge.confirmed {
    background: #E8F5E9;
    color: #2E7D32
  }

  .status-badge.pending {
    background: #FFF3E0;
    color: #E65100
  }

  .status-badge.rejected {
    background: #FFEBEE;
    color: #C62828
  }

  .status-badge.refunded {
    background: #F5F5F5;
    color: #666
  }

  .status-badge.voided {
    background: #ECEFF1;
    color: #546E7A
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
    max-width: 600px;
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
</style>

<!-- PAGE CONTENT -->

<div class="page">
  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total Payments</div>
      <div class="stat-value" id="statTotal">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Confirmed</div>
      <div class="stat-value" id="statConfirmed">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Pending</div>
      <div class="stat-value" id="statPending">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total Amount</div>
      <div class="stat-value" id="statAmount">-</div>
    </div>
  </div>

  <!-- Payments Table -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">Payments</div>
      <button onclick="openAddPaymentModal()" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i> Add Payment
      </button>
    </div>
    <div class="table-wrap">
      <table id="paymentsTable">
        <thead>
          <tr>
            <th>Payment ID</th>
            <th>Reservation</th>
            <th>Method</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Date</th>
            <th class="center">Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="paymentsTableBody">
          <tr>
            <td colspan="8" class="empty-state">
              <i class="fa-regular fa-clipboard"></i>
              Loading...
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Payment Modal -->
<div id="addPaymentModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Add New Payment</div>
      <button onclick="closeAddPaymentModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <form id="addPaymentForm">
        <div class="form-group">
          <label for="reservation_id" class="form-label">Reservation ID</label>
          <input type="text" name="reservation_id" id="reservation_id" required class="form-input" placeholder="UUID">
        </div>
        <div class="form-group">
          <label for="method_id" class="form-label">Payment Method</label>
          <select name="method_id" id="method_id" required class="form-select">
            <option value="">Loading...</option>
          </select>
        </div>
        <div class="form-group">
          <label for="transaction_type" class="form-label">Transaction Type</label>
          <select name="transaction_type" id="transaction_type" required class="form-select">
            <option value="payment">Payment</option>
            <option value="partial_payment">Partial Payment</option>
            <option value="refund">Refund</option>
            <option value="adjustment">Adjustment</option>
            <option value="void">Void</option>
          </select>
        </div>
        <div class="form-group">
          <label for="amount" class="form-label">Amount</label>
          <input type="number" name="amount" id="amount" step="0.01" required class="form-input" placeholder="0.00">
        </div>
        <div class="form-group">
          <label for="reference_number" class="form-label">Reference Number</label>
          <input type="text" name="reference_number" id="reference_number" class="form-input" placeholder="GCash ref #, bank ref #, etc.">
        </div>
        <div class="form-group">
          <label for="proof_image_url" class="form-label">Proof Image URL</label>
          <input type="text" name="proof_image_url" id="proof_image_url" class="form-input" placeholder="https://...">
        </div>
        <div class="form-group">
          <label for="payment_status" class="form-label">Payment Status</label>
          <select name="payment_status" id="payment_status" required class="form-select">
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="rejected">Rejected</option>
            <option value="refunded">Refunded</option>
            <option value="voided">Voided</option>
          </select>
        </div>
        <div class="form-group">
          <label for="payment_date" class="form-label">Payment Date</label>
          <input type="date" name="payment_date" id="payment_date" required class="form-input">
        </div>
        <div class="form-group">
          <label for="payment_time" class="form-label">Payment Time</label>
          <input type="time" name="payment_time" id="payment_time" class="form-input">
        </div>
        <div class="form-group">
          <label for="notes" class="form-label">Notes</label>
          <textarea name="notes" id="notes" rows="3" class="form-textarea"></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" onclick="closeAddPaymentModal()" class="btn btn-secondary">Cancel</button>
      <button type="button" onclick="submitAddPayment()" class="btn btn-primary">Add Payment</button>
    </div>
  </div>
</div>

<!-- Edit Payment Modal -->
<div id="editPaymentModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Edit Payment</div>
      <button onclick="closeEditPaymentModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <form id="editPaymentForm">
        <input type="hidden" id="edit_payment_id">
        <div class="form-group">
          <label for="edit_reservation_id" class="form-label">Reservation ID</label>
          <input type="text" name="reservation_id" id="edit_reservation_id" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_method_id" class="form-label">Payment Method</label>
          <select name="method_id" id="edit_method_id" required class="form-select">
            <option value="">Loading...</option>
          </select>
        </div>
        <div class="form-group">
          <label for="edit_transaction_type" class="form-label">Transaction Type</label>
          <select name="transaction_type" id="edit_transaction_type" required class="form-select">
            <option value="payment">Payment</option>
            <option value="partial_payment">Partial Payment</option>
            <option value="refund">Refund</option>
            <option value="adjustment">Adjustment</option>
            <option value="void">Void</option>
          </select>
        </div>
        <div class="form-group">
          <label for="edit_amount" class="form-label">Amount</label>
          <input type="number" name="amount" id="edit_amount" step="0.01" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_reference_number" class="form-label">Reference Number</label>
          <input type="text" name="reference_number" id="edit_reference_number" class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_proof_image_url" class="form-label">Proof Image URL</label>
          <input type="text" name="proof_image_url" id="edit_proof_image_url" class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_payment_status" class="form-label">Payment Status</label>
          <select name="payment_status" id="edit_payment_status" required class="form-select">
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="rejected">Rejected</option>
            <option value="refunded">Refunded</option>
            <option value="voided">Voided</option>
          </select>
        </div>
        <div class="form-group">
          <label for="edit_payment_date" class="form-label">Payment Date</label>
          <input type="date" name="payment_date" id="edit_payment_date" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_payment_time" class="form-label">Payment Time</label>
          <input type="time" name="payment_time" id="edit_payment_time" class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_notes" class="form-label">Notes</label>
          <textarea name="notes" id="edit_notes" rows="3" class="form-textarea"></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" onclick="closeEditPaymentModal()" class="btn btn-secondary">Cancel</button>
      <button type="button" onclick="submitEditPayment()" class="btn btn-primary">Update Payment</button>
    </div>
  </div>
</div>

<!-- View Payment Modal -->
<div id="viewPaymentModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Payment Details</div>
      <button onclick="closeViewPaymentModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <div class="view-row">
        <div class="view-label">Payment ID</div>
        <div class="view-value" id="view_payment_id"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Reservation</div>
        <div class="view-value" id="view_reservation_id"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Payment Method</div>
        <div class="view-value" id="view_method"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Transaction Type</div>
        <div class="view-value" id="view_transaction_type"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Amount</div>
        <div class="view-value" id="view_amount"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Reference Number</div>
        <div class="view-value" id="view_reference_number"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Payment Date</div>
        <div class="view-value" id="view_payment_date"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Payment Time</div>
        <div class="view-value" id="view_payment_time"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Status</div>
        <div class="view-value" id="view_payment_status"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Notes</div>
        <div class="view-value" id="view_notes"></div>
      </div>
    </div>
  </div>
</div>

<script>
  let paymentsData = [];
  let paymentMethodsData = [];

  document.addEventListener('DOMContentLoaded', function () {
    fetchPaymentsData();
    fetchPaymentMethods();
  });

  async function fetchPaymentsData() {
    try {
      const res = await fetch('../api/payments.php?action=list');
      const result = await res.json();
      if (result.success) {
        paymentsData = result.data;
        renderPaymentsTable();
        renderStats();
      } else {
        document.getElementById('paymentsTableBody').innerHTML = `<tr><td colspan="8" class="empty-state"><i>⚠</i>${result.message}</td></tr>`;
      }
    } catch (e) {
      console.error('Error fetching payments:', e);
      document.getElementById('paymentsTableBody').innerHTML = `<tr><td colspan="8" class="empty-state"><i>⚠</i>Failed to load data</td></tr>`;
    }
  }

  async function fetchPaymentMethods() {
    try {
      const res = await fetch('../api/payment_methods.php?action=active');
      const result = await res.json();
      if (result.success) {
        paymentMethodsData = result.data;
        populateMethodDropdowns();
      }
    } catch (e) {
      console.error('Error fetching payment methods:', e);
    }
  }

  function populateMethodDropdowns() {
    const opts = paymentMethodsData.map(m => `<option value="${m.method_id}">${m.method_label}</option>`).join('');
    document.getElementById('method_id').innerHTML = opts;
    document.getElementById('edit_method_id').innerHTML = opts;
  }

  function renderStats() {
    const confirmed = paymentsData.filter(p => p.payment_status === 'confirmed').length;
    const pending = paymentsData.filter(p => p.payment_status === 'pending').length;
    const total = paymentsData.reduce((s, p) => s + parseFloat(p.amount || 0), 0);
    document.getElementById('statTotal').textContent = paymentsData.length;
    document.getElementById('statConfirmed').textContent = confirmed;
    document.getElementById('statPending').textContent = pending;
    document.getElementById('statAmount').textContent = '₱' + total.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function renderPaymentsTable() {
    const tbody = document.getElementById('paymentsTableBody');
    if (!tbody) return;
    if (paymentsData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="8" class="empty-state"><i class="fa-regular fa-clipboard"></i>No payments found. <a href="javascript:;" onclick="openAddPaymentModal()" style="color:#185FA5;font-weight:500">Add your first payment</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = paymentsData.map(p => {
      const dateStr = p.payment_date ? new Date(p.payment_date).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) : '-';
      const txType = (p.transaction_type || '').replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
      return `
        <tr class="payment-row">
          <td>${p.payment_id.substring(0, 8)}...</td>
          <td>${p.reservation_id ? p.reservation_id.substring(0, 8) + '...' : '-'}</td>
          <td>${p.method_label || 'N/A'}</td>
          <td>${txType}</td>
          <td>₱${parseFloat(p.amount).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
          <td>${dateStr}</td>
          <td style="text-align:center"><span class="status-badge ${p.payment_status || ''}">${p.payment_status}</span></td>
          <td>
            <div class="action-btns">
              <button onclick="openViewPaymentModal('${p.payment_id}')" class="action-btn">View</button>
              <button onclick="openEditPaymentModal('${p.payment_id}')" class="action-btn">Edit</button>
              <button onclick="confirmDeletePayment('${p.payment_id}')" class="action-btn delete">Delete</button>
            </div>
          </td>
        </tr>`;
    }).join('');
  }

  function openAddPaymentModal() {
    document.getElementById('addPaymentForm').reset();
    document.getElementById('addPaymentModal').style.display = 'flex';
  }

  function closeAddPaymentModal() {
    document.getElementById('addPaymentModal').style.display = 'none';
  }

  async function submitAddPayment() {
    const form = document.getElementById('addPaymentForm');
    const data = {
      reservation_id: document.getElementById('reservation_id').value,
      method_id: document.getElementById('method_id').value,
      transaction_type: document.getElementById('transaction_type').value,
      amount: document.getElementById('amount').value,
      reference_number: document.getElementById('reference_number').value,
      proof_image_url: document.getElementById('proof_image_url').value,
      payment_status: document.getElementById('payment_status').value,
      payment_date: document.getElementById('payment_date').value,
      payment_time: document.getElementById('payment_time').value,
      notes: document.getElementById('notes').value,
    };
    try {
      const res = await fetch('../api/payments.php?action=create', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeAddPaymentModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Payment added successfully', timer: 2000, showConfirmButton: false });
        fetchPaymentsData();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to add payment', confirmButtonColor: '#3085d6' });
    }
  }

  function openEditPaymentModal(paymentId) {
    const payment = paymentsData.find(p => p.payment_id === paymentId);
    if (payment) {
      document.getElementById('edit_payment_id').value = payment.payment_id;
      document.getElementById('edit_reservation_id').value = payment.reservation_id;
      document.getElementById('edit_method_id').value = payment.method_id;
      document.getElementById('edit_transaction_type').value = payment.transaction_type;
      document.getElementById('edit_amount').value = payment.amount;
      document.getElementById('edit_reference_number').value = payment.reference_number || '';
      document.getElementById('edit_proof_image_url').value = payment.proof_image_url || '';
      document.getElementById('edit_payment_status').value = payment.payment_status;
      document.getElementById('edit_payment_date').value = payment.payment_date;
      document.getElementById('edit_payment_time').value = payment.payment_time || '';
      document.getElementById('edit_notes').value = payment.notes || '';
      document.getElementById('editPaymentModal').style.display = 'flex';
    }
  }

  function closeEditPaymentModal() {
    document.getElementById('editPaymentModal').style.display = 'none';
  }

  async function submitEditPayment() {
    const data = {
      payment_id: document.getElementById('edit_payment_id').value,
      reservation_id: document.getElementById('edit_reservation_id').value,
      method_id: document.getElementById('edit_method_id').value,
      transaction_type: document.getElementById('edit_transaction_type').value,
      amount: document.getElementById('edit_amount').value,
      reference_number: document.getElementById('edit_reference_number').value,
      proof_image_url: document.getElementById('edit_proof_image_url').value,
      payment_status: document.getElementById('edit_payment_status').value,
      payment_date: document.getElementById('edit_payment_date').value,
      payment_time: document.getElementById('edit_payment_time').value,
      notes: document.getElementById('edit_notes').value,
    };
    try {
      const res = await fetch('../api/payments.php?action=update', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeEditPaymentModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Payment updated successfully', timer: 2000, showConfirmButton: false });
        fetchPaymentsData();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to update payment', confirmButtonColor: '#3085d6' });
    }
  }

  function openViewPaymentModal(paymentId) {
    const payment = paymentsData.find(p => p.payment_id === paymentId);
    if (payment) {
      document.getElementById('view_payment_id').textContent = payment.payment_id;
      document.getElementById('view_reservation_id').textContent = payment.reservation_id || '-';
      document.getElementById('view_method').textContent = payment.method_label || 'N/A';
      document.getElementById('view_transaction_type').textContent = payment.transaction_type;
      document.getElementById('view_amount').textContent = '₱' + parseFloat(payment.amount).toFixed(2);
      document.getElementById('view_reference_number').textContent = payment.reference_number || 'N/A';
      document.getElementById('view_payment_date').textContent = payment.payment_date ? new Date(payment.payment_date).toLocaleDateString() : '-';
      document.getElementById('view_payment_time').textContent = payment.payment_time || 'N/A';
      document.getElementById('view_payment_status').textContent = payment.payment_status;
      document.getElementById('view_notes').textContent = payment.notes || 'N/A';
      document.getElementById('viewPaymentModal').style.display = 'flex';
    }
  }

  function closeViewPaymentModal() {
    document.getElementById('viewPaymentModal').style.display = 'none';
  }

  function confirmDeletePayment(paymentId) {
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
          const res = await fetch('../api/payments.php?action=delete', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ payment_id: paymentId })
          });
          const r = await res.json();
          if (r.success) {
            Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Payment deleted.', timer: 1500, showConfirmButton: false });
            fetchPaymentsData();
          } else {
            Swal.fire({ icon: 'error', title: 'Error!', text: r.message, confirmButtonColor: '#3085d6' });
          }
        } catch (e) {
          Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to delete payment', confirmButtonColor: '#3085d6' });
        }
      }
    });
  }
</script>