<?php
if (false) {
  try {
    error_log("Starting invoice deletion process...");

    $invoice_id = $_POST['invoice_id'] ?? '';
    error_log("Deleting invoice_id: " . $invoice_id);

    if (empty($invoice_id)) {
      throw new Exception("Invoice ID is required");
    }

    // Start transaction
    $pdo->beginTransaction();

    // Delete invoice from database
    $stmt = $pdo->prepare("DELETE FROM invoices WHERE invoice_id = ?");
    $stmt->execute([$invoice_id]);
    error_log("Deleted invoice from database");

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: invoices.php?success=invoice_deleted');
    exit;
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("Error deleting invoice: " . $e->getMessage());
    $error_message = "Failed to delete invoice. Please try again. Error: " . $e->getMessage();
  }
}

// Handle Edit Invoice Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_invoice') {
  try {
    error_log("Starting invoice edit process...");

    $invoice_id = $_POST['invoice_id'] ?? '';
    error_log("Editing invoice_id: " . $invoice_id);

    if (empty($invoice_id)) {
      throw new Exception("Invoice ID is required");
    }

    // Start transaction
    $pdo->beginTransaction();

    // Update invoice data
    $stmt = $pdo->prepare("
            UPDATE invoices SET
                reservation_id = ?,
                invoice_number = ?,
                invoice_type = ?,
                invoice_date = ?,
                due_date = ?,
                subtotal = ?,
                discount_amount = ?,
                tax_amount = ?,
                total_amount = ?,
                amount_paid = ?,
                balance = ?,
                status = ?,
                notes = ?
            WHERE invoice_id = ?
        ");

    $stmt->execute([
      $_POST['reservation_id'],
      $_POST['invoice_number'],
      $_POST['invoice_type'],
      $_POST['invoice_date'],
      $_POST['due_date'] ?? null,
      $_POST['subtotal'],
      $_POST['discount_amount'],
      $_POST['tax_amount'],
      $_POST['total_amount'],
      $_POST['amount_paid'],
      $_POST['balance'],
      $_POST['status'],
      $_POST['notes'] ?? null,
      $invoice_id
    ]);

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: invoices.php?success=invoice_updated');
    exit;
  } catch (PDOException $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("PDO Error editing invoice: " . $e->getMessage());
    $error_message = "Failed to update invoice. Please try again. Error: " . $e->getMessage();
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("General Error editing invoice: " . $e->getMessage());
    $error_message = "Failed to update invoice. Please try again. Error: " . $e->getMessage();
  }
}

// Handle Add Invoice Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_invoice') {
  try {
    error_log("Starting invoice addition process...");

    // Generate UUID for invoice_id
    $invoice_id = sprintf(
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

    // Generate invoice number
    $year = date('Y');
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM invoices WHERE invoice_number LIKE ?");
    $stmt->execute(["OR-$year-%"]);
    $result = $stmt->fetch();
    $next_num = str_pad(($result['count'] + 1), 6, '0', STR_PAD_LEFT);
    $invoice_number = "OR-$year-$next_num";

    // Start transaction
    $pdo->beginTransaction();

    // Insert invoice data
    $stmt = $pdo->prepare("
            INSERT INTO invoices (
                invoice_id, reservation_id, invoice_number, invoice_type, invoice_date, 
                due_date, subtotal, discount_amount, tax_amount, total_amount, 
                amount_paid, balance, status, notes
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

    $params = [
      $invoice_id,
      $_POST['reservation_id'],
      $invoice_number,
      $_POST['invoice_type'],
      $_POST['invoice_date'],
      $_POST['due_date'] ?? null,
      $_POST['subtotal'],
      $_POST['discount_amount'],
      $_POST['tax_amount'],
      $_POST['total_amount'],
      $_POST['amount_paid'],
      $_POST['balance'],
      $_POST['status'],
      $_POST['notes'] ?? null
    ];

    error_log("Executing invoice insert with params: " . json_encode($params));
    $stmt->execute($params);
    error_log("Invoice inserted successfully");

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: invoices.php?success=invoice_added');
    exit;
  } catch (PDOException $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("PDO Error adding invoice: " . $e->getMessage());
    error_log("Error code: " . $e->getCode());
    error_log("Error info: " . print_r($e->errorInfo, true));
    $error_message = "Failed to add invoice. Please try again. Error: " . $e->getMessage();
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("General Error adding invoice: " . $e->getMessage());
    $error_message = "Failed to add invoice. Please try again. Error: " . $e->getMessage();
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

  .status-badge.paid {
    background: #E8F5E9;
    color: #2E7D32
  }

  .status-badge.partial {
    background: #E3F2FD;
    color: #1976D2
  }

  .status-badge.draft {
    background: #F5F5F5;
    color: #666
  }

  .status-badge.sent {
    background: #FFF3E0;
    color: #E65100
  }

  .status-badge.overdue {
    background: #FFEBEE;
    color: #C62828
  }

  .status-badge.void {
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

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px
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

  .view-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 12px
  }

  .view-grid-item {
    display: flex
  }

  .view-grid-item .view-label {
    width: 100px
  }
</style>

<!-- PAGE CONTENT -->

<div class="page">
  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total Invoices</div>
      <div class="stat-value" id="statTotal">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Paid</div>
      <div class="stat-value" id="statPaid">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Pending</div>
      <div class="stat-value" id="statPending">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total Outstanding</div>
      <div class="stat-value" id="statOutstanding">-</div>
    </div>
  </div>

  <!-- Invoices Table -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">Invoices</div>
      <button onclick="openAddInvoiceModal()" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i> Add Invoice
      </button>
    </div>
    <div class="table-wrap">
      <table id="invoicesTable">
        <thead>
          <tr>
            <th>Invoice #</th>
            <th>Reservation</th>
            <th>Type</th>
            <th>Date</th>
            <th>Total</th>
            <th>Balance</th>
            <th class="center">Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="invoicesTableBody">
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

<!-- Add Invoice Modal -->
<div id="addInvoiceModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Add New Invoice</div>
      <button onclick="closeAddInvoiceModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <form id="addInvoiceForm">
        <div class="form-group">
          <label for="reservation_id" class="form-label">Reservation ID</label>
          <input type="text" name="reservation_id" id="reservation_id" required class="form-input" placeholder="UUID">
        </div>
        <div class="form-group">
          <label for="invoice_type" class="form-label">Invoice Type</label>
          <select name="invoice_type" id="invoice_type" required class="form-select">
            <option value="official_receipt">Official Receipt</option>
            <option value="proforma">Proforma</option>
            <option value="billing_statement">Billing Statement</option>
            <option value="refund_receipt">Refund Receipt</option>
          </select>
        </div>
        <div class="form-group">
          <label for="invoice_date" class="form-label">Invoice Date</label>
          <input type="date" name="invoice_date" id="invoice_date" required class="form-input">
        </div>
        <div class="form-group">
          <label for="due_date" class="form-label">Due Date</label>
          <input type="date" name="due_date" id="due_date" class="form-input">
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="subtotal" class="form-label">Subtotal</label>
            <input type="number" name="subtotal" id="subtotal" step="0.01" required class="form-input" value="0.00">
          </div>
          <div class="form-group">
            <label for="discount_amount" class="form-label">Discount</label>
            <input type="number" name="discount_amount" id="discount_amount" step="0.01" class="form-input" value="0.00">
          </div>
        </div>
        <div class="form-group">
          <label for="tax_amount" class="form-label">Tax Amount</label>
          <input type="number" name="tax_amount" id="tax_amount" step="0.01" class="form-input" value="0.00">
        </div>
        <div class="form-group">
          <label for="total_amount" class="form-label">Total Amount</label>
          <input type="number" name="total_amount" id="total_amount" step="0.01" required class="form-input" value="0.00">
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="amount_paid" class="form-label">Amount Paid</label>
            <input type="number" name="amount_paid" id="amount_paid" step="0.01" class="form-input" value="0.00">
          </div>
          <div class="form-group">
            <label for="balance" class="form-label">Balance</label>
            <input type="number" name="balance" id="balance" step="0.01" class="form-input" value="0.00">
          </div>
        </div>
        <div class="form-group">
          <label for="status" class="form-label">Status</label>
          <select name="status" id="status" required class="form-select">
            <option value="draft">Draft</option>
            <option value="sent">Sent</option>
            <option value="paid">Paid</option>
            <option value="partial">Partial</option>
            <option value="overdue">Overdue</option>
            <option value="void">Void</option>
          </select>
        </div>
        <div class="form-group">
          <label for="notes" class="form-label">Notes</label>
          <textarea name="notes" id="notes" rows="3" class="form-textarea"></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" onclick="closeAddInvoiceModal()" class="btn btn-secondary">Cancel</button>
      <button type="button" onclick="submitAddInvoice()" class="btn btn-primary">Add Invoice</button>
    </div>
  </div>
</div>

<!-- Edit Invoice Modal -->
<div id="editInvoiceModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Edit Invoice</div>
      <button onclick="closeEditInvoiceModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <form id="editInvoiceForm">
        <input type="hidden" id="edit_invoice_id">
        <div class="form-group">
          <label for="edit_reservation_id" class="form-label">Reservation ID</label>
          <input type="text" name="reservation_id" id="edit_reservation_id" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_invoice_number" class="form-label">Invoice Number</label>
          <input type="text" name="invoice_number" id="edit_invoice_number" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_invoice_type" class="form-label">Invoice Type</label>
          <select name="invoice_type" id="edit_invoice_type" required class="form-select">
            <option value="official_receipt">Official Receipt</option>
            <option value="proforma">Proforma</option>
            <option value="billing_statement">Billing Statement</option>
            <option value="refund_receipt">Refund Receipt</option>
          </select>
        </div>
        <div class="form-group">
          <label for="edit_invoice_date" class="form-label">Invoice Date</label>
          <input type="date" name="invoice_date" id="edit_invoice_date" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_due_date" class="form-label">Due Date</label>
          <input type="date" name="due_date" id="edit_due_date" class="form-input">
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="edit_subtotal" class="form-label">Subtotal</label>
            <input type="number" name="subtotal" id="edit_subtotal" step="0.01" required class="form-input">
          </div>
          <div class="form-group">
            <label for="edit_discount_amount" class="form-label">Discount</label>
            <input type="number" name="discount_amount" id="edit_discount_amount" step="0.01" class="form-input">
          </div>
        </div>
        <div class="form-group">
          <label for="edit_tax_amount" class="form-label">Tax Amount</label>
          <input type="number" name="tax_amount" id="edit_tax_amount" step="0.01" class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_total_amount" class="form-label">Total Amount</label>
          <input type="number" name="total_amount" id="edit_total_amount" step="0.01" required class="form-input">
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="edit_amount_paid" class="form-label">Amount Paid</label>
            <input type="number" name="amount_paid" id="edit_amount_paid" step="0.01" class="form-input">
          </div>
          <div class="form-group">
            <label for="edit_balance" class="form-label">Balance</label>
            <input type="number" name="balance" id="edit_balance" step="0.01" class="form-input">
          </div>
        </div>
        <div class="form-group">
          <label for="edit_status" class="form-label">Status</label>
          <select name="status" id="edit_status" required class="form-select">
            <option value="draft">Draft</option>
            <option value="sent">Sent</option>
            <option value="paid">Paid</option>
            <option value="partial">Partial</option>
            <option value="overdue">Overdue</option>
            <option value="void">Void</option>
          </select>
        </div>
        <div class="form-group">
          <label for="edit_notes" class="form-label">Notes</label>
          <textarea name="notes" id="edit_notes" rows="3" class="form-textarea"></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" onclick="closeEditInvoiceModal()" class="btn btn-secondary">Cancel</button>
      <button type="button" onclick="submitEditInvoice()" class="btn btn-primary">Update Invoice</button>
    </div>
  </div>
</div>

<!-- View Invoice Modal -->
<div id="viewInvoiceModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Invoice Details</div>
      <button onclick="closeViewInvoiceModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <div class="view-row">
        <div class="view-label">Invoice Number</div>
        <div class="view-value" id="view_invoice_number"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Reservation</div>
        <div class="view-value" id="view_reservation_id"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Invoice Type</div>
        <div class="view-value" id="view_invoice_type"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Invoice Date</div>
        <div class="view-value" id="view_invoice_date"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Due Date</div>
        <div class="view-value" id="view_due_date"></div>
      </div>
      <div class="view-grid">
        <div class="view-grid-item">
          <div class="view-label">Subtotal</div>
          <div class="view-value" id="view_subtotal"></div>
        </div>
        <div class="view-grid-item">
          <div class="view-label">Discount</div>
          <div class="view-value" id="view_discount_amount"></div>
        </div>
      </div>
      <div class="view-row">
        <div class="view-label">Tax Amount</div>
        <div class="view-value" id="view_tax_amount"></div>
      </div>
      <div class="view-grid">
        <div class="view-grid-item">
          <div class="view-label">Total Amount</div>
          <div class="view-value" id="view_total_amount"></div>
        </div>
        <div class="view-grid-item">
          <div class="view-label">Amount Paid</div>
          <div class="view-value" id="view_amount_paid"></div>
        </div>
      </div>
      <div class="view-row">
        <div class="view-label">Balance</div>
        <div class="view-value" id="view_balance"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Status</div>
        <div class="view-value" id="view_status"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Notes</div>
        <div class="view-value" id="view_notes"></div>
      </div>
    </div>
  </div>
</div>

<script>
  let invoicesData = [];

  document.addEventListener('DOMContentLoaded', function () {
    fetchInvoices();
  });

  async function fetchInvoices() {
    try {
      const res = await fetch('../api/invoices.php?action=list');
      const result = await res.json();
      if (result.success) {
        invoicesData = result.data;
        renderTable();
        renderStats();
      } else {
        document.getElementById('invoicesTableBody').innerHTML = `<tr><td colspan="8" class="empty-state"><i>⚠</i>${result.message}</td></tr>`;
      }
    } catch (e) {
      console.error('Error fetching invoices:', e);
      document.getElementById('invoicesTableBody').innerHTML = `<tr><td colspan="8" class="empty-state"><i>⚠</i>Failed to load data</td></tr>`;
    }
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = invoicesData.length;
    document.getElementById('statPaid').textContent = invoicesData.filter(i => i.status === 'paid').length;
    document.getElementById('statPending').textContent = invoicesData.filter(i => ['draft','sent','partial'].includes(i.status)).length;
    const outstanding = invoicesData.reduce((s, i) => s + parseFloat(i.balance || 0), 0);
    document.getElementById('statOutstanding').textContent = '₱' + outstanding.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function renderTable() {
    const tbody = document.getElementById('invoicesTableBody');
    if (!invoicesData.length) {
      tbody.innerHTML = `<tr><td colspan="8" class="empty-state"><i class="fa-regular fa-clipboard"></i>No invoices found. <a href="javascript:;" onclick="openAddInvoiceModal()" style="color:#185FA5;font-weight:500">Add your first invoice</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = invoicesData.map(inv => {
      const typeLabel = (inv.invoice_type || '').replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
      const dateStr = inv.invoice_date ? new Date(inv.invoice_date).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) : '-';
      return `
        <tr class="invoice-row">
          <td>${inv.invoice_number}</td>
          <td>${inv.reservation_id ? inv.reservation_id.substring(0, 8) + '...' : '-'}</td>
          <td>${typeLabel}</td>
          <td>${dateStr}</td>
          <td>₱${parseFloat(inv.total_amount || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
          <td>₱${parseFloat(inv.balance || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
          <td style="text-align:center"><span class="status-badge ${inv.status || ''}">${inv.status}</span></td>
          <td>
            <div class="action-btns">
              <button onclick="openViewInvoiceModal('${inv.invoice_id}')" class="action-btn">View</button>
              <button onclick="openEditInvoiceModal('${inv.invoice_id}')" class="action-btn">Edit</button>
              <button onclick="confirmDeleteInvoice('${inv.invoice_id}')" class="action-btn delete">Delete</button>
            </div>
          </td>
        </tr>`;
    }).join('');
  }

  function openAddInvoiceModal() {
    document.getElementById('addInvoiceForm').reset();
    document.getElementById('addInvoiceModal').style.display = 'flex';
  }

  function closeAddInvoiceModal() {
    document.getElementById('addInvoiceModal').style.display = 'none';
  }

  async function submitAddInvoice() {
    const data = {
      reservation_id: document.getElementById('reservation_id').value,
      invoice_type: document.getElementById('invoice_type').value,
      invoice_date: document.getElementById('invoice_date').value,
      due_date: document.getElementById('due_date').value,
      subtotal: document.getElementById('subtotal').value,
      discount_amount: document.getElementById('discount_amount').value,
      tax_amount: document.getElementById('tax_amount').value,
      total_amount: document.getElementById('total_amount').value,
      amount_paid: document.getElementById('amount_paid').value,
      balance: document.getElementById('balance').value,
      status: document.getElementById('status').value,
      notes: document.getElementById('notes').value,
    };
    try {
      const res = await fetch('../api/invoices.php?action=create', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeAddInvoiceModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Invoice added successfully', timer: 2000, showConfirmButton: false });
        fetchInvoices();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to add invoice', confirmButtonColor: '#3085d6' });
    }
  }

  function openEditInvoiceModal(invoiceId) {
    const invoice = invoicesData.find(i => i.invoice_id === invoiceId);
    if (invoice) {
      document.getElementById('edit_invoice_id').value = invoice.invoice_id;
      document.getElementById('edit_reservation_id').value = invoice.reservation_id;
      document.getElementById('edit_invoice_number').value = invoice.invoice_number;
      document.getElementById('edit_invoice_type').value = invoice.invoice_type;
      document.getElementById('edit_invoice_date').value = invoice.invoice_date;
      document.getElementById('edit_due_date').value = invoice.due_date || '';
      document.getElementById('edit_subtotal').value = invoice.subtotal;
      document.getElementById('edit_discount_amount').value = invoice.discount_amount;
      document.getElementById('edit_tax_amount').value = invoice.tax_amount;
      document.getElementById('edit_total_amount').value = invoice.total_amount;
      document.getElementById('edit_amount_paid').value = invoice.amount_paid;
      document.getElementById('edit_balance').value = invoice.balance;
      document.getElementById('edit_status').value = invoice.status;
      document.getElementById('edit_notes').value = invoice.notes || '';
      document.getElementById('editInvoiceModal').style.display = 'flex';
    }
  }

  function closeEditInvoiceModal() {
    document.getElementById('editInvoiceModal').style.display = 'none';
  }

  async function submitEditInvoice() {
    const data = {
      invoice_id: document.getElementById('edit_invoice_id').value,
      reservation_id: document.getElementById('edit_reservation_id').value,
      invoice_number: document.getElementById('edit_invoice_number').value,
      invoice_type: document.getElementById('edit_invoice_type').value,
      invoice_date: document.getElementById('edit_invoice_date').value,
      due_date: document.getElementById('edit_due_date').value,
      subtotal: document.getElementById('edit_subtotal').value,
      discount_amount: document.getElementById('edit_discount_amount').value,
      tax_amount: document.getElementById('edit_tax_amount').value,
      total_amount: document.getElementById('edit_total_amount').value,
      amount_paid: document.getElementById('edit_amount_paid').value,
      balance: document.getElementById('edit_balance').value,
      status: document.getElementById('edit_status').value,
      notes: document.getElementById('edit_notes').value,
    };
    try {
      const res = await fetch('../api/invoices.php?action=update', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeEditInvoiceModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Invoice updated successfully', timer: 2000, showConfirmButton: false });
        fetchInvoices();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to update invoice', confirmButtonColor: '#3085d6' });
    }
  }

  function openViewInvoiceModal(invoiceId) {
    const invoice = invoicesData.find(i => i.invoice_id === invoiceId);
    if (invoice) {
      document.getElementById('view_invoice_number').textContent = invoice.invoice_number;
      document.getElementById('view_reservation_id').textContent = invoice.reservation_id || '-';
      document.getElementById('view_invoice_type').textContent = invoice.invoice_type;
      document.getElementById('view_invoice_date').textContent = invoice.invoice_date ? new Date(invoice.invoice_date).toLocaleDateString() : '-';
      document.getElementById('view_due_date').textContent = invoice.due_date ? new Date(invoice.due_date).toLocaleDateString() : 'N/A';
      document.getElementById('view_subtotal').textContent = '₱' + parseFloat(invoice.subtotal || 0).toFixed(2);
      document.getElementById('view_discount_amount').textContent = '₱' + parseFloat(invoice.discount_amount || 0).toFixed(2);
      document.getElementById('view_tax_amount').textContent = '₱' + parseFloat(invoice.tax_amount || 0).toFixed(2);
      document.getElementById('view_total_amount').textContent = '₱' + parseFloat(invoice.total_amount || 0).toFixed(2);
      document.getElementById('view_amount_paid').textContent = '₱' + parseFloat(invoice.amount_paid || 0).toFixed(2);
      document.getElementById('view_balance').textContent = '₱' + parseFloat(invoice.balance || 0).toFixed(2);
      document.getElementById('view_status').textContent = invoice.status;
      document.getElementById('view_notes').textContent = invoice.notes || 'N/A';
      document.getElementById('viewInvoiceModal').style.display = 'flex';
    }
  }

  function closeViewInvoiceModal() {
    document.getElementById('viewInvoiceModal').style.display = 'none';
  }

  function confirmDeleteInvoice(invoiceId) {
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
          const res = await fetch('../api/invoices.php?action=delete', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ invoice_id: invoiceId })
          });
          const r = await res.json();
          if (r.success) {
            Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Invoice deleted.', timer: 1500, showConfirmButton: false });
            fetchInvoices();
          } else {
            Swal.fire({ icon: 'error', title: 'Error!', text: r.message, confirmButtonColor: '#3085d6' });
          }
        } catch (e) {
          Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to delete invoice', confirmButtonColor: '#3085d6' });
        }
      }
    });
  }
</script>