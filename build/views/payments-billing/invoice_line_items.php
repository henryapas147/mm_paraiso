<?php
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

  /* ── Responsive ──────────────────────────────────── */
  @media (max-width: 640px) {
    .stats-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .stat-card {
      padding: 14px 16px;
    }
  }

  @media (max-width: 640px) {
    .stat-label {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .stat-value {
      font-size: 24px;
    }
  }

  @media (max-width: 480px) {
    .page {
      padding: 16px;
    }
  }

  @media (max-width: 768px) {
    .card-header {
      flex-direction: column;
      align-items: stretch;
      gap: 16px;
      padding: 16px 18px;
    }
  }

  @media (max-width: 480px) {
    .btn {
      width: 100%;
      justify-content: center;
    }
  }

  @media (max-width: 640px) {
    thead th {
      padding: 8px 12px;
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    td {
      padding: 8px 12px;
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .action-btn {
      padding: 4px 8px;
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .empty-state {
      padding: 40px 20px;
    }
  }

  @media (max-width: 640px) {
    .empty-state i {
      font-size: 28px;
    }
  }

  @media (max-width: 768px) {
    .modal {
      max-width: 90vw;
    }
  }

  @media (max-width: 480px) {
    .modal {
      max-width: none;
      max-height: 95vh;
      border-radius: 12px;
    }
  }

  @media (max-width: 768px) {
    .modal-body {
      padding: 18px;
    }
  }

  @media (max-width: 480px) {
    .modal-body {
      padding: 16px;
      overflow-y: auto;
      overflow-x: hidden;
    }
  }

  @media (max-width: 480px) {
    .modal-footer {
      flex-direction: column;
    }
  }

  @media (max-width: 480px) {
    .modal-footer .btn {
      width: 100%;
      justify-content: center;
    }
  }

  @media (max-width: 640px) {
    .modal-header {
      padding: 14px 18px;
    }
  }

  @media (max-width: 640px) {
    .modal-title {
      font-size: 16px;
    }
  }

  @media (max-width: 640px) {
    .modal-close {
      font-size: 20px;
    }
  }

  @media (max-width: 640px) {
    .form-label {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .form-input,
    .form-select,
    .form-textarea {
      font-size: 12px;
      padding: 7px 10px;
    }
  }

  @media (max-width: 640px) {
    .view-row {
      flex-direction: column;
      gap: 4px;
    }
  }

  @media (max-width: 640px) {
    .view-label {
      width: auto;
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .view-value {
      font-size: 12px;
    }
  }
</style>

<!-- PAGE CONTENT -->

<div class="page">
  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total Items</div>
      <div class="stat-value" id="statTotal">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Room Charges</div>
      <div class="stat-value" id="statRooms">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Services</div>
      <div class="stat-value" id="statServices">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total Value</div>
      <div class="stat-value" id="statValue">-</div>
    </div>
  </div>

  <!-- Line Items Table -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">Invoice Line Items</div>
      <button onclick="openAddLineItemModal()" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i> Add Line Item
      </button>
    </div>
    <div class="table-wrap">
      <table id="lineItemsTable">
        <thead>
          <tr>
            <th>Description</th>
            <th>Invoice #</th>
            <th>Type</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="lineItemsTableBody">
          <tr>
            <td colspan="7" class="empty-state">
              <i class="fa-regular fa-clipboard"></i>
              Loading...
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Line Item Modal -->
<div id="addLineItemModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Add New Line Item</div>
      <button onclick="closeAddLineItemModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <form id="addLineItemForm">
        <div class="form-group">
          <label for="invoice_id" class="form-label">Invoice</label>
          <select name="invoice_id" id="invoice_id" required class="form-select">
            <option value="">Loading...</option>
          </select>
        </div>
        <div class="form-group">
          <label for="description" class="form-label">Description</label>
          <input type="text" name="description" id="description" required class="form-input">
        </div>
        <div class="form-group">
          <label for="item_type" class="form-label">Item Type</label>
          <select name="item_type" id="item_type" required class="form-select">
            <option value="room_charge">Room Charge</option>
            <option value="extra_guest">Extra Guest</option>
            <option value="food_order">Food Order</option>
            <option value="service">Service</option>
            <option value="discount">Discount</option>
            <option value="tax">Tax</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="form-group">
          <label for="quantity" class="form-label">Quantity</label>
          <input type="number" name="quantity" id="quantity" step="0.01" required class="form-input" value="1.00">
        </div>
        <div class="form-group">
          <label for="unit_price" class="form-label">Unit Price</label>
          <input type="number" name="unit_price" id="unit_price" step="0.01" required class="form-input" value="0.00">
        </div>
        <div class="form-group">
          <label for="total_price" class="form-label">Total Price</label>
          <input type="number" name="total_price" id="total_price" step="0.01" required class="form-input" value="0.00">
        </div>
        <div class="form-group">
          <label for="reference_id" class="form-label">Reference ID</label>
          <input type="text" name="reference_id" id="reference_id" class="form-input">
        </div>
        <div class="form-group">
          <label for="sort_order" class="form-label">Sort Order</label>
          <input type="number" name="sort_order" id="sort_order" class="form-input" value="0">
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" onclick="closeAddLineItemModal()" class="btn btn-secondary">Cancel</button>
      <button type="button" onclick="submitAddLineItem()" class="btn btn-primary">Add Line Item</button>
    </div>
  </div>
</div>

<!-- Edit Line Item Modal -->
<div id="editLineItemModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Edit Line Item</div>
      <button onclick="closeEditLineItemModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <form id="editLineItemForm">
        <input type="hidden" id="edit_line_item_id">
        <div class="form-group">
          <label for="edit_invoice_id" class="form-label">Invoice</label>
          <select name="invoice_id" id="edit_invoice_id" required class="form-select">
            <option value="">Loading...</option>
          </select>
        </div>
        <div class="form-group">
          <label for="edit_description" class="form-label">Description</label>
          <input type="text" name="description" id="edit_description" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_item_type" class="form-label">Item Type</label>
          <select name="item_type" id="edit_item_type" required class="form-select">
            <option value="room_charge">Room Charge</option>
            <option value="extra_guest">Extra Guest</option>
            <option value="food_order">Food Order</option>
            <option value="service">Service</option>
            <option value="discount">Discount</option>
            <option value="tax">Tax</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="form-group">
          <label for="edit_quantity" class="form-label">Quantity</label>
          <input type="number" name="quantity" id="edit_quantity" step="0.01" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_unit_price" class="form-label">Unit Price</label>
          <input type="number" name="unit_price" id="edit_unit_price" step="0.01" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_total_price" class="form-label">Total Price</label>
          <input type="number" name="total_price" id="edit_total_price" step="0.01" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_reference_id" class="form-label">Reference ID</label>
          <input type="text" name="reference_id" id="edit_reference_id" class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_sort_order" class="form-label">Sort Order</label>
          <input type="number" name="sort_order" id="edit_sort_order" class="form-input">
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" onclick="closeEditLineItemModal()" class="btn btn-secondary">Cancel</button>
      <button type="button" onclick="submitEditLineItem()" class="btn btn-primary">Update Line Item</button>
    </div>
  </div>
</div>

<!-- View Line Item Modal -->
<div id="viewLineItemModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Line Item Details</div>
      <button onclick="closeViewLineItemModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <div class="view-row">
        <div class="view-label">Description</div>
        <div class="view-value" id="view_description"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Invoice</div>
        <div class="view-value" id="view_invoice"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Item Type</div>
        <div class="view-value" id="view_item_type"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Quantity</div>
        <div class="view-value" id="view_quantity"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Unit Price</div>
        <div class="view-value" id="view_unit_price"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Total Price</div>
        <div class="view-value" id="view_total_price"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Reference ID</div>
        <div class="view-value" id="view_reference_id"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Sort Order</div>
        <div class="view-value" id="view_sort_order"></div>
      </div>
    </div>
  </div>
</div>

<script>
  let lineItemsData = [];
  let invoicesDropdownData = [];

  document.addEventListener('DOMContentLoaded', function () {
    fetchLineItems();
    fetchInvoicesForDropdown();
  });

  async function fetchLineItems() {
    try {
      const res = await fetch('../api/invoice_line_items.php?action=list');
      const result = await res.json();
      if (result.success) {
        lineItemsData = result.data;
        renderTable();
        renderStats();
      } else {
        document.getElementById('lineItemsTableBody').innerHTML = `<tr><td colspan="7" class="empty-state"><i>⚠</i>${result.message}</td></tr>`;
      }
    } catch (e) {
      document.getElementById('lineItemsTableBody').innerHTML = `<tr><td colspan="7" class="empty-state"><i>⚠</i>Failed to load data</td></tr>`;
    }
  }

  async function fetchInvoicesForDropdown() {
    try {
      const res = await fetch('../api/invoices.php?action=list');
      const result = await res.json();
      if (result.success) {
        invoicesDropdownData = result.data;
        const opts = invoicesDropdownData.map(i => `<option value="${i.invoice_id}">${i.invoice_number}</option>`).join('');
        document.getElementById('invoice_id').innerHTML = opts || '<option value="">No invoices found</option>';
        document.getElementById('edit_invoice_id').innerHTML = opts || '<option value="">No invoices found</option>';
      }
    } catch (e) { console.error('Error fetching invoices:', e); }
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = lineItemsData.length;
    document.getElementById('statRooms').textContent = lineItemsData.filter(i => i.item_type === 'room_charge').length;
    document.getElementById('statServices').textContent = lineItemsData.filter(i => i.item_type === 'service').length;
    const total = lineItemsData.reduce((s, i) => s + parseFloat(i.line_total || i.total_price || 0), 0);
    document.getElementById('statValue').textContent = '₱' + total.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function renderTable() {
    const tbody = document.getElementById('lineItemsTableBody');
    if (!lineItemsData.length) {
      tbody.innerHTML = `<tr><td colspan="7" class="empty-state"><i class="fa-regular fa-clipboard"></i>No line items found. <a href="javascript:;" onclick="openAddLineItemModal()" style="color:#185FA5;font-weight:500">Add your first line item</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = lineItemsData.map(item => {
      const typeLabel = (item.item_type || '').replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
      const unitPrice = parseFloat(item.unit_price || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 });
      const totalPrice = parseFloat(item.line_total || item.total_price || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 });
      return `
        <tr class="line-item-row">
          <td>${item.description || '-'}</td>
          <td>${item.invoice_number || 'N/A'}</td>
          <td>${typeLabel}</td>
          <td>${item.quantity}</td>
          <td>₱${unitPrice}</td>
          <td>₱${totalPrice}</td>
          <td>
            <div class="action-btns">
              <button onclick="openViewLineItemModal('${item.line_item_id}')" class="action-btn">View</button>
              <button onclick="openEditLineItemModal('${item.line_item_id}')" class="action-btn">Edit</button>
              <button onclick="confirmDeleteLineItem('${item.line_item_id}')" class="action-btn delete">Delete</button>
            </div>
          </td>
        </tr>`;
    }).join('');
  }

  function openAddLineItemModal() {
    document.getElementById('addLineItemForm').reset();
    document.getElementById('addLineItemModal').style.display = 'flex';
  }

  function closeAddLineItemModal() {
    document.getElementById('addLineItemModal').style.display = 'none';
  }

  async function submitAddLineItem() {
    const data = {
      invoice_id: document.getElementById('invoice_id').value,
      description: document.getElementById('description').value,
      item_type: document.getElementById('item_type').value,
      quantity: document.getElementById('quantity').value,
      unit_price: document.getElementById('unit_price').value,
      total_price: document.getElementById('total_price').value,
      sort_order: document.getElementById('sort_order').value,
    };
    try {
      const res = await fetch('../api/invoice_line_items.php?action=create', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeAddLineItemModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Line item added successfully', timer: 2000, showConfirmButton: false });
        fetchLineItems();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to add line item', confirmButtonColor: '#3085d6' });
    }
  }

  function openEditLineItemModal(lineItemId) {
    const item = lineItemsData.find(i => i.line_item_id === lineItemId);
    if (item) {
      document.getElementById('edit_line_item_id').value = item.line_item_id;
      document.getElementById('edit_invoice_id').value = item.invoice_id;
      document.getElementById('edit_description').value = item.description;
      document.getElementById('edit_item_type').value = item.item_type;
      document.getElementById('edit_quantity').value = item.quantity;
      document.getElementById('edit_unit_price').value = item.unit_price;
      document.getElementById('edit_total_price').value = item.line_total || item.total_price;
      document.getElementById('edit_reference_id').value = item.reference_id || '';
      document.getElementById('edit_sort_order').value = item.sort_order || 0;
      document.getElementById('editLineItemModal').style.display = 'flex';
    }
  }

  function closeEditLineItemModal() {
    document.getElementById('editLineItemModal').style.display = 'none';
  }

  async function submitEditLineItem() {
    const data = {
      line_item_id: document.getElementById('edit_line_item_id').value,
      invoice_id: document.getElementById('edit_invoice_id').value,
      description: document.getElementById('edit_description').value,
      item_type: document.getElementById('edit_item_type').value,
      quantity: document.getElementById('edit_quantity').value,
      unit_price: document.getElementById('edit_unit_price').value,
      total_price: document.getElementById('edit_total_price').value,
      sort_order: document.getElementById('edit_sort_order').value,
    };
    try {
      const res = await fetch('../api/invoice_line_items.php?action=update', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeEditLineItemModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Line item updated successfully', timer: 2000, showConfirmButton: false });
        fetchLineItems();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to update line item', confirmButtonColor: '#3085d6' });
    }
  }

  function openViewLineItemModal(lineItemId) {
    const item = lineItemsData.find(i => i.line_item_id === lineItemId);
    if (item) {
      document.getElementById('view_description').textContent = item.description;
      document.getElementById('view_invoice').textContent = item.invoice_number || 'N/A';
      document.getElementById('view_item_type').textContent = item.item_type;
      document.getElementById('view_quantity').textContent = item.quantity;
      document.getElementById('view_unit_price').textContent = '₱' + parseFloat(item.unit_price).toFixed(2);
      document.getElementById('view_total_price').textContent = '₱' + parseFloat(item.line_total || item.total_price || 0).toFixed(2);
      document.getElementById('view_reference_id').textContent = item.reference_id || 'N/A';
      document.getElementById('view_sort_order').textContent = item.sort_order || 0;
      document.getElementById('viewLineItemModal').style.display = 'flex';
    }
  }

  function closeViewLineItemModal() {
    document.getElementById('viewLineItemModal').style.display = 'none';
  }

  function confirmDeleteLineItem(lineItemId) {
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
          const res = await fetch('../api/invoice_line_items.php?action=delete', {
            method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>' }, body: JSON.stringify({ line_item_id: lineItemId })
          });
          const r = await res.json();
          if (r.success) {
            Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Line item deleted.', timer: 1500, showConfirmButton: false });
            fetchLineItems();
          } else {
            Swal.fire({ icon: 'error', title: 'Error!', text: r.message, confirmButtonColor: '#3085d6' });
          }
        } catch (e) {
          Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to delete line item', confirmButtonColor: '#3085d6' });
        }
      }
    });
  }

</script>