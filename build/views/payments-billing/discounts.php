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

  .checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 8px
  }

  .checkbox-item {
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
    .form-row {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 640px) {
    .checkbox-group {
      gap: 6px;
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
      <div class="stat-label">Total Discounts</div>
      <div class="stat-value" id="statTotal">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Active</div>
      <div class="stat-value" id="statActive">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Percentage</div>
      <div class="stat-value" id="statPercentage">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total Uses</div>
      <div class="stat-value" id="statUses">-</div>
    </div>
  </div>

  <!-- Discounts Table -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">Discounts</div>
      <button onclick="openAddDiscountModal()" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i> Add Discount
      </button>
    </div>
    <div class="table-wrap">
      <table id="discountsTable">
        <thead>
          <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Type</th>
            <th>Value</th>
            <th>Valid Period</th>
            <th class="center">Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="discountsTableBody">
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

<!-- Add Discount Modal -->
<div id="addDiscountModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Add New Discount</div>
      <button onclick="closeAddDiscountModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <form id="addDiscountForm">
        <div class="form-group">
          <label for="discount_code" class="form-label">Discount Code</label>
          <input type="text" name="discount_code" id="discount_code" required class="form-input" placeholder="e.g., SUMMER2024">
        </div>
        <div class="form-group">
          <label for="discount_name" class="form-label">Discount Name</label>
          <input type="text" name="discount_name" id="discount_name" required class="form-input" placeholder="e.g., Summer Sale">
        </div>
        <div class="form-group">
          <label for="description" class="form-label">Description</label>
          <textarea name="description" id="description" rows="2" class="form-textarea"></textarea>
        </div>
        <div class="form-group">
          <label for="discount_type" class="form-label">Discount Type</label>
          <select name="discount_type" id="discount_type" required class="form-select">
            <option value="percentage">Percentage</option>
            <option value="fixed_amount">Fixed Amount</option>
            <option value="free_night">Free Night</option>
            <option value="upgrade">Upgrade</option>
          </select>
        </div>
        <div class="form-group">
          <label for="discount_value" class="form-label">Discount Value</label>
          <input type="number" name="discount_value" id="discount_value" step="0.01" required class="form-input" placeholder="e.g., 10 for 10%">
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="min_nights" class="form-label">Min Nights</label>
            <input type="number" name="min_nights" id="min_nights" class="form-input">
          </div>
          <div class="form-group">
            <label for="min_booking_amount" class="form-label">Min Booking Amount</label>
            <input type="number" name="min_booking_amount" id="min_booking_amount" step="0.01" class="form-input">
          </div>
        </div>
        <div class="form-group">
          <label for="max_discount_amount" class="form-label">Max Discount Amount</label>
          <input type="number" name="max_discount_amount" id="max_discount_amount" step="0.01" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-label">Applies To</label>
          <div class="checkbox-group">
            <label class="checkbox-item">
              <input type="checkbox" name="applies_to[]" value="overnight" checked>
              <span>Overnight</span>
            </label>
            <label class="checkbox-item">
              <input type="checkbox" name="applies_to[]" value="hourly" checked>
              <span>Hourly</span>
            </label>
            <label class="checkbox-item">
              <input type="checkbox" name="applies_to[]" value="day_use" checked>
              <span>Day Use</span>
            </label>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="usage_limit" class="form-label">Usage Limit</label>
            <input type="number" name="usage_limit" id="usage_limit" class="form-input" placeholder="Empty for unlimited">
          </div>
          <div class="form-group">
            <label for="per_user_limit" class="form-label">Per User Limit</label>
            <input type="number" name="per_user_limit" id="per_user_limit" class="form-input">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="valid_from" class="form-label">Valid From</label>
            <input type="date" name="valid_from" id="valid_from" required class="form-input">
          </div>
          <div class="form-group">
            <label for="valid_until" class="form-label">Valid Until</label>
            <input type="date" name="valid_until" id="valid_until" required class="form-input">
          </div>
        </div>
        <div class="form-group">
          <label class="checkbox-item">
            <input type="checkbox" name="is_active" value="1" checked>
            <span>Active</span>
          </label>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" onclick="closeAddDiscountModal()" class="btn btn-secondary">Cancel</button>
      <button type="button" onclick="submitAddDiscount()" class="btn btn-primary">Add Discount</button>
    </div>
  </div>
</div>

<!-- Edit Discount Modal -->
<div id="editDiscountModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Edit Discount</div>
      <button onclick="closeEditDiscountModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <form id="editDiscountForm">
        <input type="hidden" id="edit_discount_id">
        <div class="form-group">
          <label for="edit_discount_code" class="form-label">Discount Code</label>
          <input type="text" name="discount_code" id="edit_discount_code" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_discount_name" class="form-label">Discount Name</label>
          <input type="text" name="discount_name" id="edit_discount_name" required class="form-input">
        </div>
        <div class="form-group">
          <label for="edit_description" class="form-label">Description</label>
          <textarea name="description" id="edit_description" rows="2" class="form-textarea"></textarea>
        </div>
        <div class="form-group">
          <label for="edit_discount_type" class="form-label">Discount Type</label>
          <select name="discount_type" id="edit_discount_type" required class="form-select">
            <option value="percentage">Percentage</option>
            <option value="fixed_amount">Fixed Amount</option>
            <option value="free_night">Free Night</option>
            <option value="upgrade">Upgrade</option>
          </select>
        </div>
        <div class="form-group">
          <label for="edit_discount_value" class="form-label">Discount Value</label>
          <input type="number" name="discount_value" id="edit_discount_value" step="0.01" required class="form-input">
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="edit_min_nights" class="form-label">Min Nights</label>
            <input type="number" name="min_nights" id="edit_min_nights" class="form-input">
          </div>
          <div class="form-group">
            <label for="edit_min_booking_amount" class="form-label">Min Booking Amount</label>
            <input type="number" name="min_booking_amount" id="edit_min_booking_amount" step="0.01" class="form-input">
          </div>
        </div>
        <div class="form-group">
          <label for="edit_max_discount_amount" class="form-label">Max Discount Amount</label>
          <input type="number" name="max_discount_amount" id="edit_max_discount_amount" step="0.01" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-label">Applies To</label>
          <div class="checkbox-group">
            <label class="checkbox-item">
              <input type="checkbox" name="applies_to[]" value="overnight" id="edit_applies_overnight">
              <span>Overnight</span>
            </label>
            <label class="checkbox-item">
              <input type="checkbox" name="applies_to[]" value="hourly" id="edit_applies_hourly">
              <span>Hourly</span>
            </label>
            <label class="checkbox-item">
              <input type="checkbox" name="applies_to[]" value="day_use" id="edit_applies_day_use">
              <span>Day Use</span>
            </label>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="edit_usage_limit" class="form-label">Usage Limit</label>
            <input type="number" name="usage_limit" id="edit_usage_limit" class="form-input">
          </div>
          <div class="form-group">
            <label for="edit_per_user_limit" class="form-label">Per User Limit</label>
            <input type="number" name="per_user_limit" id="edit_per_user_limit" class="form-input">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="edit_valid_from" class="form-label">Valid From</label>
            <input type="date" name="valid_from" id="edit_valid_from" required class="form-input">
          </div>
          <div class="form-group">
            <label for="edit_valid_until" class="form-label">Valid Until</label>
            <input type="date" name="valid_until" id="edit_valid_until" required class="form-input">
          </div>
        </div>
        <div class="form-group">
          <label class="checkbox-item">
            <input type="checkbox" name="is_active" value="1" id="edit_is_active">
            <span>Active</span>
          </label>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" onclick="closeEditDiscountModal()" class="btn btn-secondary">Cancel</button>
      <button type="button" onclick="submitEditDiscount()" class="btn btn-primary">Update Discount</button>
    </div>
  </div>
</div>

<!-- View Discount Modal -->
<div id="viewDiscountModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Discount Details</div>
      <button onclick="closeViewDiscountModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <div class="view-row">
        <div class="view-label">Discount Code</div>
        <div class="view-value" id="view_discount_code"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Discount Name</div>
        <div class="view-value" id="view_discount_name"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Description</div>
        <div class="view-value" id="view_description"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Discount Type</div>
        <div class="view-value" id="view_discount_type"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Discount Value</div>
        <div class="view-value" id="view_discount_value"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Valid Period</div>
        <div class="view-value" id="view_valid_period"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Applies To</div>
        <div class="view-value" id="view_applies_to"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Usage Limit</div>
        <div class="view-value" id="view_usage_limit"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Usage Count</div>
        <div class="view-value" id="view_usage_count"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Status</div>
        <div class="view-value" id="view_is_active"></div>
      </div>
    </div>
  </div>
</div>

<script>
  let csrfToken = "<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>";
  let discountsData = [];

  document.addEventListener('DOMContentLoaded', function () {
    fetchDiscounts();
  });

  async function fetchDiscounts() {
    try {
      const res = await fetch('../api/discounts.php?action=list');
      const result = await res.json();
      if (result.success) {
        discountsData = result.data;
        renderTable();
        renderStats();
      } else {
        document.getElementById('discountsTableBody').innerHTML = `<tr><td colspan="7" class="empty-state"><i>⚠</i>${result.message}</td></tr>`;
      }
    } catch (e) {
      console.error('Error fetching discounts:', e);
      document.getElementById('discountsTableBody').innerHTML = `<tr><td colspan="7" class="empty-state"><i>⚠</i>Failed to load data</td></tr>`;
    }
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = discountsData.length;
    document.getElementById('statActive').textContent = discountsData.filter(d => d.is_active == 1).length;
    document.getElementById('statPercentage').textContent = discountsData.filter(d => d.discount_type === 'percentage').length;
    document.getElementById('statUses').textContent = discountsData.reduce((s, d) => s + parseInt(d.usage_count || 0), 0);
  }

  function renderTable() {
    const tbody = document.getElementById('discountsTableBody');
    if (!discountsData.length) {
      tbody.innerHTML = `<tr><td colspan="7" class="empty-state"><i class="fa-regular fa-clipboard"></i>No discounts found. <a href="javascript:;" onclick="openAddDiscountModal()" style="color:#185FA5;font-weight:500">Add your first discount</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = discountsData.map(d => {
      const valueStr = d.discount_type === 'percentage' ? d.discount_value + '%' : '₱' + parseFloat(d.discount_value).toLocaleString('en-PH', { minimumFractionDigits: 2 });
      const validFrom = d.valid_from ? new Date(d.valid_from).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) : '-';
      const validUntil = d.valid_until ? new Date(d.valid_until).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) : '-';
      const statusBadge = d.is_active == 1
        ? `<span class="status-badge active">Active</span>`
        : `<span class="status-badge inactive">Inactive</span>`;
      return `
        <tr class="discount-row">
          <td>${d.discount_code}</td>
          <td>${d.discount_name}</td>
          <td>${d.discount_type.charAt(0).toUpperCase() + d.discount_type.slice(1)}</td>
          <td>${valueStr}</td>
          <td>${validFrom} - ${validUntil}</td>
          <td class="center">${statusBadge}</td>
          <td>
            <div class="action-btns">
              <button onclick="openViewDiscountModal('${d.discount_id}')" class="action-btn">View</button>
              <button onclick="openEditDiscountModal('${d.discount_id}')" class="action-btn">Edit</button>
              <button onclick="confirmDeleteDiscount('${d.discount_id}')" class="action-btn delete">Delete</button>
            </div>
          </td>
        </tr>`;
    }).join('');
  }

  function openAddDiscountModal() {
    document.getElementById('addDiscountForm').reset();
    document.getElementById('addDiscountModal').style.display = 'flex';
  }

  function closeAddDiscountModal() {
    document.getElementById('addDiscountModal').style.display = 'none';
  }

  async function submitAddDiscount() {
    const appliesTo = [];
    document.querySelectorAll('#addDiscountForm input[name="applies_to[]"]').forEach(cb => { if (cb.checked) appliesTo.push(cb.value); });
    const data = {
      discount_code: document.getElementById('discount_code').value,
      discount_name: document.getElementById('discount_name').value,
      description: document.getElementById('description').value,
      discount_type: document.getElementById('discount_type').value,
      discount_value: document.getElementById('discount_value').value,
      min_nights: document.getElementById('min_nights').value,
      min_booking_amount: document.getElementById('min_booking_amount').value,
      max_discount_amount: document.getElementById('max_discount_amount').value,
      applies_to: appliesTo.join(','),
      usage_limit: document.getElementById('usage_limit').value,
      per_user_limit: document.getElementById('per_user_limit').value,
      valid_from: document.getElementById('valid_from').value,
      valid_until: document.getElementById('valid_until').value,
      is_active: document.querySelector('#addDiscountForm input[name="is_active"]').checked ? 1 : 0,
    };
    try {
      const res = await fetch('../api/discounts.php?action=create', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeAddDiscountModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Discount added successfully', timer: 2000, showConfirmButton: false });
        fetchDiscounts();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to add discount', confirmButtonColor: '#3085d6' });
    }
  }

  function openEditDiscountModal(discountId) {
    const discount = discountsData.find(d => d.discount_id === discountId);
    if (discount) {
      document.getElementById('edit_discount_id').value = discount.discount_id;
      document.getElementById('edit_discount_code').value = discount.discount_code;
      document.getElementById('edit_discount_name').value = discount.discount_name;
      document.getElementById('edit_description').value = discount.description || '';
      document.getElementById('edit_discount_type').value = discount.discount_type;
      document.getElementById('edit_discount_value').value = discount.discount_value;
      document.getElementById('edit_min_nights').value = discount.min_nights || '';
      document.getElementById('edit_min_booking_amount').value = discount.min_booking_amount || '';
      document.getElementById('edit_max_discount_amount').value = discount.max_discount_amount || '';
      const appliesTo = discount.applies_to ? discount.applies_to.split(',') : [];
      document.getElementById('edit_applies_overnight').checked = appliesTo.includes('overnight');
      document.getElementById('edit_applies_hourly').checked = appliesTo.includes('hourly');
      document.getElementById('edit_applies_day_use').checked = appliesTo.includes('day_use');
      document.getElementById('edit_usage_limit').value = discount.usage_limit || '';
      document.getElementById('edit_per_user_limit').value = discount.per_user_limit || '';
      document.getElementById('edit_valid_from').value = discount.valid_from;
      document.getElementById('edit_valid_until').value = discount.valid_until;
      document.getElementById('edit_is_active').checked = discount.is_active == 1;
      document.getElementById('editDiscountModal').style.display = 'flex';
    }
  }

  function closeEditDiscountModal() {
    document.getElementById('editDiscountModal').style.display = 'none';
  }

  async function submitEditDiscount() {
    const appliesTo = [];
    document.querySelectorAll('#editDiscountForm input[name="applies_to[]"]').forEach(cb => { if (cb.checked) appliesTo.push(cb.value); });
    const data = {
      discount_id: document.getElementById('edit_discount_id').value,
      discount_code: document.getElementById('edit_discount_code').value,
      discount_name: document.getElementById('edit_discount_name').value,
      description: document.getElementById('edit_description').value,
      discount_type: document.getElementById('edit_discount_type').value,
      discount_value: document.getElementById('edit_discount_value').value,
      min_nights: document.getElementById('edit_min_nights').value,
      min_booking_amount: document.getElementById('edit_min_booking_amount').value,
      max_discount_amount: document.getElementById('edit_max_discount_amount').value,
      applies_to: appliesTo.join(','),
      usage_limit: document.getElementById('edit_usage_limit').value,
      per_user_limit: document.getElementById('edit_per_user_limit').value,
      valid_from: document.getElementById('edit_valid_from').value,
      valid_until: document.getElementById('edit_valid_until').value,
      is_active: document.getElementById('edit_is_active').checked ? 1 : 0,
    };
    try {
      const res = await fetch('../api/discounts.php?action=update', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeEditDiscountModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Discount updated successfully', timer: 2000, showConfirmButton: false });
        fetchDiscounts();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to update discount', confirmButtonColor: '#3085d6' });
    }
  }

  function openViewDiscountModal(discountId) {
    const discount = discountsData.find(d => d.discount_id === discountId);
    if (discount) {
      document.getElementById('view_discount_code').textContent = discount.discount_code;
      document.getElementById('view_discount_name').textContent = discount.discount_name;
      document.getElementById('view_description').textContent = discount.description || 'N/A';
      document.getElementById('view_discount_type').textContent = discount.discount_type;
      document.getElementById('view_discount_value').textContent = discount.discount_type === 'percentage' ? discount.discount_value + '%' : '₱' + parseFloat(discount.discount_value).toFixed(2);
      document.getElementById('view_valid_period').textContent = (discount.valid_from ? new Date(discount.valid_from).toLocaleDateString() : '-') + ' - ' + (discount.valid_until ? new Date(discount.valid_until).toLocaleDateString() : '-');
      document.getElementById('view_applies_to').textContent = discount.applies_to ? discount.applies_to.replace(/,/g, ', ') : 'N/A';
      document.getElementById('view_usage_limit').textContent = discount.usage_limit || 'Unlimited';
      document.getElementById('view_usage_count').textContent = discount.usage_count || 0;
      document.getElementById('view_is_active').textContent = discount.is_active == 1 ? 'Active' : 'Inactive';
      document.getElementById('viewDiscountModal').style.display = 'flex';
    }
  }

  function closeViewDiscountModal() {
    document.getElementById('viewDiscountModal').style.display = 'none';
  }

  function confirmDeleteDiscount(discountId) {
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
          const res = await fetch('../api/discounts.php?action=delete', {
            method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken }, body: JSON.stringify({ discount_id: discountId })
          });
          const r = await res.json();
          if (r.success) {
            Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Discount deleted.', timer: 1500, showConfirmButton: false });
            fetchDiscounts();
          } else {
            Swal.fire({ icon: 'error', title: 'Error!', text: r.message, confirmButtonColor: '#3085d6' });
          }
        } catch (e) {
          Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to delete discount', confirmButtonColor: '#3085d6' });
        }
      }
    });
  }

</script>