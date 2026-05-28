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

  .search-input {
    padding: 8px 12px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    font-size: 13px;
    outline: none
  }

  .search-input:focus {
    border-color: #185FA5
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

  .type-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
    text-transform: uppercase
  }

  .type-badge.damage {
    background: #FEE2E2;
    color: #DC2626
  }

  .type-badge.late_checkout {
    background: #FEF3C7;
    color: #D97706
  }

  .type-badge.extra_amenity {
    background: #DBEAFE;
    color: #2563EB
  }

  .type-badge.cleaning {
    background: #CCFBF1;
    color: #0D9488
  }

  .type-badge.extension {
    background: #EDE9FE;
    color: #7C3AED
  }

  .type-badge.other {
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
    max-width: 560px;
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
      <div class="stat-label">Total Charges</div>
      <div class="stat-value" id="statTotal">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Damage Fees</div>
      <div class="stat-value" id="statDamage">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Late Checkout</div>
      <div class="stat-value" id="statLate">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total Amount</div>
      <div class="stat-value" id="statAmount">-</div>
    </div>
  </div>

  <!-- Extra Charges Table -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">Extra Charges</div>
      <div style="display:flex;gap:8px">
        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search..." class="search-input">
        <button onclick="openAddChargeModal()" class="btn btn-primary">
          <i class="fas fa-plus mr-1"></i> Add Charge
        </button>
      </div>
    </div>
    <div class="table-wrap">
      <table id="extraChargesTable">
        <thead>
          <tr>
            <th>Reservation</th>
            <th>Type</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Charged At</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="extraChargesTableBody">
          <tr>
            <td colspan="6" class="empty-state">
              <i class="fa-regular fa-clipboard"></i>
              Loading...
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Charge Modal -->
<div id="addChargeModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Add Extra Charge</div>
      <button onclick="closeAddChargeModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <form id="addChargeForm">
        <div class="form-group">
          <label for="reservation_id" class="form-label">Reservation ID</label>
          <input type="text" id="reservation_id" required class="form-input" placeholder="UUID">
        </div>
        <div class="form-group">
          <label for="charge_type" class="form-label">Charge Type</label>
          <select id="charge_type" required class="form-select">
            <option value="damage">Damage</option>
            <option value="late_checkout">Late Checkout</option>
            <option value="extra_amenity">Extra Amenity</option>
            <option value="cleaning">Cleaning</option>
            <option value="extension">Extension</option>
            <option value="other" selected>Other</option>
          </select>
        </div>
        <div class="form-group">
          <label for="description" class="form-label">Description</label>
          <input type="text" id="description" required class="form-input" placeholder="e.g., Broken chair">
        </div>
        <div class="form-group">
          <label for="amount" class="form-label">Amount (₱)</label>
          <input type="number" id="amount" step="0.01" required class="form-input" value="0.00">
        </div>
        <div class="form-group">
          <label for="charged_at" class="form-label">Charged At</label>
          <input type="datetime-local" id="charged_at" class="form-input">
        </div>
        <div class="form-group">
          <label for="notes" class="form-label">Notes</label>
          <textarea id="notes" rows="2" class="form-textarea"></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" onclick="closeAddChargeModal()" class="btn btn-secondary">Cancel</button>
      <button type="button" onclick="submitAddCharge()" class="btn btn-primary">Add Charge</button>
    </div>
  </div>
</div>

<!-- Edit Charge Modal -->
<div id="editChargeModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Edit Extra Charge</div>
      <button onclick="closeEditChargeModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <form id="editChargeForm">
        <input type="hidden" id="edit_charge_id">
        <div class="form-group">
          <label for="edit_reservation_id" class="form-label">Reservation ID</label>
          <input type="text" id="edit_reservation_id" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_charge_type" class="form-label">Charge Type</label>
          <select id="edit_charge_type" required class="form-select">
            <option value="damage">Damage</option>
            <option value="late_checkout">Late Checkout</option>
            <option value="extra_amenity">Extra Amenity</option>
            <option value="cleaning">Cleaning</option>
            <option value="extension">Extension</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="form-group">
          <label for="edit_description" class="form-label">Description</label>
          <input type="text" id="edit_description" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_amount" class="form-label">Amount (₱)</label>
          <input type="number" id="edit_amount" step="0.01" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_charged_at" class="form-label">Charged At</label>
          <input type="datetime-local" id="edit_charged_at" class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_notes" class="form-label">Notes</label>
          <textarea id="edit_notes" rows="2" class="form-textarea"></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" onclick="closeEditChargeModal()" class="btn btn-secondary">Cancel</button>
      <button type="button" onclick="submitEditCharge()" class="btn btn-primary">Update Charge</button>
    </div>
  </div>
</div>

<!-- View Charge Modal -->
<div id="viewChargeModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Charge Details</div>
      <button onclick="closeViewChargeModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <div class="view-row">
        <div class="view-label">Reservation ID</div>
        <div class="view-value" id="view_reservation_id"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Charge Type</div>
        <div class="view-value" id="view_charge_type"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Description</div>
        <div class="view-value" id="view_description"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Amount</div>
        <div class="view-value" id="view_amount"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Charged At</div>
        <div class="view-value" id="view_charged_at"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Notes</div>
        <div class="view-value" id="view_notes"></div>
      </div>
    </div>
  </div>
</div>

<script>
  let extraChargesData = [];

  document.addEventListener('DOMContentLoaded', function () {
    fetchExtraCharges();
  });

  async function fetchExtraCharges() {
    try {
      const res = await fetch('../api/extra_charges.php?action=list');
      const result = await res.json();
      if (result.success) {
        extraChargesData = result.data;
        renderTable();
        renderStats();
      } else {
        document.getElementById('extraChargesTableBody').innerHTML = `<tr><td colspan="6" class="empty-state"><i>⚠</i>${result.message}</td></tr>`;
      }
    } catch (e) {
      console.error('Error fetching extra charges:', e);
      document.getElementById('extraChargesTableBody').innerHTML = `<tr><td colspan="6" class="empty-state"><i>⚠</i>Failed to load data</td></tr>`;
    }
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = extraChargesData.length;
    document.getElementById('statDamage').textContent = extraChargesData.filter(c => c.charge_type === 'damage').length;
    document.getElementById('statLate').textContent = extraChargesData.filter(c => c.charge_type === 'late_checkout').length;
    const total = extraChargesData.reduce((s, c) => s + parseFloat(c.amount || 0), 0);
    document.getElementById('statAmount').textContent = '₱' + total.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function renderTable() {
    const tbody = document.getElementById('extraChargesTableBody');
    if (!extraChargesData.length) {
      tbody.innerHTML = `<tr><td colspan="6" class="empty-state"><i class="fa-regular fa-clipboard"></i>No extra charges found. <a href="javascript:;" onclick="openAddChargeModal()" style="color:#185FA5;font-weight:500">Add your first charge</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = extraChargesData.map(c => {
      const typeClass = c.charge_type || 'other';
      const typeLabel = (c.charge_type || '').replace(/_/g, ' ').replace(/\b\w/g, ch => ch.toUpperCase());
      const chargedAt = c.charged_at ? new Date(c.charged_at).toLocaleString('en-US', { month: 'short', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';
      return `
        <tr class="charge-row">
          <td>${c.reservation_id ? c.reservation_id.substring(0, 8) + '...' : '-'}</td>
          <td><span class="type-badge ${typeClass}">${typeLabel}</span></td>
          <td>${c.description || '-'}</td>
          <td>₱${parseFloat(c.amount || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
          <td>${chargedAt}</td>
          <td>
            <div class="action-btns">
              <button onclick="openViewChargeModal('${c.charge_id}')" class="action-btn">View</button>
              <button onclick="openEditChargeModal('${c.charge_id}')" class="action-btn">Edit</button>
              <button onclick="confirmDeleteCharge('${c.charge_id}')" class="action-btn delete">Delete</button>
            </div>
          </td>
        </tr>`;
    }).join('');
  }

  function openAddChargeModal() {
    document.getElementById('addChargeForm').reset();
    document.getElementById('addChargeModal').style.display = 'flex';
  }

  function closeAddChargeModal() {
    document.getElementById('addChargeModal').style.display = 'none';
  }

  async function submitAddCharge() {
    const data = {
      reservation_id: document.getElementById('reservation_id').value,
      charge_type: document.getElementById('charge_type').value,
      description: document.getElementById('description').value,
      amount: document.getElementById('amount').value,
      charged_at: document.getElementById('charged_at').value,
      notes: document.getElementById('notes').value,
    };
    try {
      const res = await fetch('../api/extra_charges.php?action=create', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeAddChargeModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Extra charge added successfully', timer: 2000, showConfirmButton: false });
        fetchExtraCharges();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to add charge', confirmButtonColor: '#3085d6' });
    }
  }

  function openEditChargeModal(chargeId) {
    const charge = extraChargesData.find(c => c.charge_id === chargeId);
    if (charge) {
      document.getElementById('edit_charge_id').value = charge.charge_id;
      document.getElementById('edit_reservation_id').value = charge.reservation_id;
      document.getElementById('edit_charge_type').value = charge.charge_type;
      document.getElementById('edit_description').value = charge.description;
      document.getElementById('edit_amount').value = charge.amount;
      document.getElementById('edit_charged_at').value = charge.charged_at ? charge.charged_at.replace(' ', 'T').substring(0, 16) : '';
      document.getElementById('edit_notes').value = charge.notes || '';
      document.getElementById('editChargeModal').style.display = 'flex';
    }
  }

  function closeEditChargeModal() {
    document.getElementById('editChargeModal').style.display = 'none';
  }

  async function submitEditCharge() {
    const data = {
      charge_id: document.getElementById('edit_charge_id').value,
      reservation_id: document.getElementById('edit_reservation_id').value,
      charge_type: document.getElementById('edit_charge_type').value,
      description: document.getElementById('edit_description').value,
      amount: document.getElementById('edit_amount').value,
      charged_at: document.getElementById('edit_charged_at').value,
      notes: document.getElementById('edit_notes').value,
    };
    try {
      const res = await fetch('../api/extra_charges.php?action=update', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeEditChargeModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Extra charge updated successfully', timer: 2000, showConfirmButton: false });
        fetchExtraCharges();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to update charge', confirmButtonColor: '#3085d6' });
    }
  }

  function openViewChargeModal(chargeId) {
    const charge = extraChargesData.find(c => c.charge_id === chargeId);
    if (charge) {
      document.getElementById('view_reservation_id').textContent = charge.reservation_id || '-';
      document.getElementById('view_charge_type').textContent = (charge.charge_type || '').replace(/_/g, ' ').replace(/\b\w/g, ch => ch.toUpperCase());
      document.getElementById('view_description').textContent = charge.description || '-';
      document.getElementById('view_amount').textContent = '₱' + parseFloat(charge.amount || 0).toFixed(2);
      document.getElementById('view_charged_at').textContent = charge.charged_at ? new Date(charge.charged_at).toLocaleString() : '-';
      document.getElementById('view_notes').textContent = charge.notes || 'N/A';
      document.getElementById('viewChargeModal').style.display = 'flex';
    }
  }

  function closeViewChargeModal() {
    document.getElementById('viewChargeModal').style.display = 'none';
  }

  function confirmDeleteCharge(chargeId) {
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
          const res = await fetch('../api/extra_charges.php?action=delete', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ charge_id: chargeId })
          });
          const r = await res.json();
          if (r.success) {
            Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Extra charge deleted.', timer: 1500, showConfirmButton: false });
            fetchExtraCharges();
          } else {
            Swal.fire({ icon: 'error', title: 'Error!', text: r.message, confirmButtonColor: '#3085d6' });
          }
        } catch (e) {
          Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to delete charge', confirmButtonColor: '#3085d6' });
        }
      }
    });
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    document.querySelectorAll('#extraChargesTable .charge-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }
</script>
