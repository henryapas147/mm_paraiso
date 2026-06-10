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

  .sin-page {
    padding: 28px 28px 40px;
    max-width: 100%;
    margin: 0 auto;
    background: var(--color-background-tertiary, #F4F5F7);
    min-height: 100%;
    border-radius: var(--border-radius-xl, 18px);
    font-family: var(--prod-font);
  }

  .sin-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
    gap: 16px;
    flex-wrap: wrap;
  }

  .sin-page-eyebrow {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--prod-blue);
    margin-bottom: 4px;
  }

  .sin-page-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--color-text-primary);
    letter-spacing: -.4px;
    line-height: 1.2;
  }

  .sin-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
  }

  .sin-stat-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    padding: 20px 22px;
    box-shadow: var(--prod-shadow-sm);
    position: relative;
    overflow: hidden;
  }

  .sin-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--prod-radius-lg) var(--prod-radius-lg) 0 0;
    background: var(--_accent, #CBD5E1);
  }

  .sin-stat-card.blue { --_accent: var(--prod-blue); }
  .sin-stat-card.green { --_accent: var(--prod-green); }
  .sin-stat-card.amber { --_accent: var(--prod-amber); }
  .sin-stat-card.purple { --_accent: var(--prod-purple); }

  .sin-stat-icon {
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

  .sin-stat-card.green .sin-stat-icon { background: var(--prod-green-pale); color: var(--prod-green); }
  .sin-stat-card.amber .sin-stat-icon { background: var(--prod-amber-pale); color: var(--prod-amber); }
  .sin-stat-card.purple .sin-stat-icon { background: var(--prod-purple-pale); color: var(--prod-purple); }

  .sin-stat-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-secondary);
    margin-bottom: 6px;
  }

  .sin-stat-value {
    font-size: 32px;
    font-weight: 600;
    color: var(--color-text-primary);
    font-family: var(--prod-mono);
  }

  .sin-card {
    background: var(--color-background-primary, #fff);
    border: 1px solid rgba(0, 0, 0, .07);
    border-radius: var(--prod-radius-lg);
    box-shadow: var(--prod-shadow-sm);
    overflow: hidden;
  }

  .sin-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }

  .sin-form-section {
    padding: 24px 22px;
  }

  .sin-form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
  }

  .sin-form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .sin-form-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .06em;
  }

  .sin-form-input,
  .sin-form-select,
  .sin-form-textarea {
    padding: 10px 14px;
    font-size: 13px;
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: var(--prod-radius-md);
    background: var(--color-background-primary, #fff);
    color: var(--color-text-primary);
    outline: none;
    transition: all .15s;
  }

  .sin-form-input:focus,
  .sin-form-select:focus,
  .sin-form-textarea:focus {
    border-color: var(--prod-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, .12);
  }

  .product-search {
    min-width: 250px;
  }

  .sin-form-textarea {
    resize: vertical;
    min-height: 80px;
  }

  .sin-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 20px;
    font-size: 13px;
    font-weight: 500;
    border-radius: var(--prod-radius-md);
    border: 1px solid rgba(0, 0, 0, .12);
    background: var(--color-background-primary, #fff);
    color: var(--color-text-primary);
    cursor: pointer;
    transition: all .15s;
  }

  .sin-btn:hover {
    background: var(--color-background-secondary, #F8FAFC);
  }

  .sin-btn-primary {
    background: var(--prod-blue);
    color: #fff;
    border-color: var(--prod-blue);
    box-shadow: 0 1px 3px rgba(26, 107, 191, .3);
  }

  .sin-btn-primary:hover {
    background: var(--prod-blue-dark);
  }

  .sin-items-section {
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid rgba(0, 0, 0, .06);
  }

  .sin-items-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
  }

  .sin-items-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }

  .sin-items-table th {
    padding: 10px 14px;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: .08em;
    border-bottom: 1px solid rgba(0, 0, 0, .07);
    background: var(--color-background-secondary, #F8FAFC);
  }

  .sin-items-table td {
    padding: 12px 14px;
    border-bottom: 1px solid rgba(0, 0, 0, .04);
  }

  .sin-remove-btn {
    color: var(--prod-red);
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: var(--prod-radius-sm);
    transition: all .15s;
  }

  .sin-remove-btn:hover {
    background: var(--prod-red-pale);
  }

  .sin-total-section {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    display: flex;
    justify-content: flex-end;
    gap: 12px;
  }

  @media (max-width: 640px) {
    .sin-page { padding: 20px 16px 32px; }
    .sin-page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
    .sin-page-title { font-size: 20px; }
    .sin-stats-grid { grid-template-columns: 1fr; gap: 12px; }
    .sin-form-row { grid-template-columns: 1fr; }
    .sin-items-header { flex-direction: column; align-items: flex-start; gap: 12px; }
    .sin-total-section { flex-direction: column; }
    .sin-btn { width: 100%; justify-content: center; }
  }
</style>

<div class="sin-page">
  <div class="sin-page-header">
    <div>
      <div class="sin-page-eyebrow"><i class="fas fa-arrow-down" style="margin-right:5px"></i>Inventory</div>
      <div class="sin-page-title">Stock In</div>
    </div>
  </div>

  <div class="sin-stats-grid">
    <div class="sin-stat-card blue">
      <div class="sin-stat-icon"><i class="fas fa-boxes"></i></div>
      <div class="sin-stat-label">Total Stock-ins</div>
      <div class="sin-stat-value" id="statTotal">—</div>
    </div>
    <div class="sin-stat-card green">
      <div class="sin-stat-icon"><i class="fas fa-plus-circle"></i></div>
      <div class="sin-stat-label">This Month</div>
      <div class="sin-stat-value" id="statMonth">—</div>
    </div>
    <div class="sin-stat-card amber">
      <div class="sin-stat-icon"><i class="fas fa-cube"></i></div>
      <div class="sin-stat-label">Total Quantity</div>
      <div class="sin-stat-value" id="statQuantity">—</div>
    </div>
    <div class="sin-stat-card purple">
      <div class="sin-stat-icon"><i class="fas fa-map-marker-alt"></i></div>
      <div class="sin-stat-label">Locations</div>
      <div class="sin-stat-value" id="statLocations">—</div>
    </div>
  </div>

  <div class="sin-card">
    <div class="sin-card-header">
      <strong>New Stock In Entry</strong>
    </div>
    
    <form id="stockInForm" class="sin-form-section">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(CSRF_TOKEN, ENT_QUOTES, 'UTF-8'); ?>">
      
      <div class="sin-form-row">
        <div class="sin-form-group">
          <label class="sin-form-label">Reference Type</label>
          <select class="sin-form-select" name="reference_type" required>
            <option value="">Select Type</option>
            <option value="MANUAL">Manual Entry</option>
            <option value="RETURN">Return from Customer</option>
            <option value="DONATION">Donation</option>
            <option value="TRANSFER">Transfer In</option>
            <option value="ADJUSTMENT">Stock Adjustment</option>
            <option value="OTHER">Other</option>
          </select>
        </div>
        <div class="sin-form-group">
          <label class="sin-form-label">Reference Number</label>
          <input type="text" class="sin-form-input" name="reference_number" placeholder="e.g., RTN-001, DON-2024-001">
        </div>
        <div class="sin-form-group">
          <label class="sin-form-label">Location</label>
          <select class="sin-form-select" name="location_id" required>
            <option value="">Select Location</option>
          </select>
        </div>
        <div class="sin-form-group">
          <label class="sin-form-label">Date</label>
          <input type="date" class="sin-form-input" name="transaction_date" required>
        </div>
      </div>

      <div class="sin-form-group" style="margin-bottom: 20px;">
        <label class="sin-form-label">Notes</label>
        <textarea class="sin-form-textarea" name="notes" placeholder="Add any notes about this stock-in entry..."></textarea>
      </div>

      <div class="sin-items-section">
        <div class="sin-items-header">
          <strong>Items</strong>
          <button type="button" onclick="addItemRow()" class="sin-btn sin-btn-primary">
            <i class="fas fa-plus"></i> Add Item
          </button>
        </div>
        
        <table class="sin-items-table">
          <thead>
            <tr>
              <th style="width: 40%">Product</th>
              <th style="width: 15%">Quantity</th>
              <th style="width: 15%">UOM</th>
              <th style="width: 25%">Notes</th>
              <th style="width: 5%"></th>
            </tr>
          </thead>
          <tbody id="itemsTableBody">
            <tr>
              <td colspan="5" style="text-align: center; padding: 30px; color: var(--color-text-secondary);">
                <i class="fas fa-box-open" style="font-size: 24px; opacity: 0.3; margin-bottom: 8px;"></i>
                <div>No items added yet</div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="sin-total-section">
        <button type="button" onclick="resetForm()" class="sin-btn">
          <i class="fas fa-times"></i> Clear
        </button>
        <button type="submit" class="sin-btn sin-btn-primary">
          <i class="fas fa-check"></i> Submit Stock In
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Stock In History -->
<div class="sin-card" style="margin-top:24px">
  <div class="sin-card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <div>
      <div class="sin-card-title"><i class="fas fa-history" style="margin-right:8px;color:var(--prod-blue)"></i>Stock In History</div>
      <div class="sin-card-subtitle">Recent manual stock-in transactions</div>
    </div>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
      <input type="text" id="historySearch" placeholder="Search product, ref no..." oninput="filterHistory()" class="sin-form-input" style="width:220px;font-size:13px;padding:6px 10px">
      <select id="historyRefTypeFilter" onchange="filterHistory()" class="sin-form-select" style="font-size:13px;padding:6px 10px">
        <option value="">All Types</option>
        <option value="MANUAL">Manual</option>
        <option value="RETURN">Return</option>
        <option value="DONATION">Donation</option>
        <option value="TRANSFER">Transfer</option>
        <option value="ADJUSTMENT">Adjustment</option>
      </select>
      <button onclick="fetchHistory()" class="sin-btn" style="padding:6px 12px;font-size:13px"><i class="fas fa-sync-alt"></i></button>
    </div>
  </div>
  <div style="overflow-x:auto">
    <table style="width:100%;border-collapse:collapse;font-size:13px">
      <thead>
        <tr style="background:var(--color-background-secondary,#F8FAFC)">
          <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--color-text-secondary);border-bottom:1px solid rgba(0,0,0,.07)">Date</th>
          <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--color-text-secondary);border-bottom:1px solid rgba(0,0,0,.07)">Product</th>
          <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--color-text-secondary);border-bottom:1px solid rgba(0,0,0,.07)">Location</th>
          <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--color-text-secondary);border-bottom:1px solid rgba(0,0,0,.07)">Ref Type</th>
          <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--color-text-secondary);border-bottom:1px solid rgba(0,0,0,.07)">Ref No.</th>
          <th style="padding:10px 14px;text-align:right;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--color-text-secondary);border-bottom:1px solid rgba(0,0,0,.07)">Qty</th>
          <th style="padding:10px 14px;text-align:right;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--color-text-secondary);border-bottom:1px solid rgba(0,0,0,.07)">Stock After</th>
          <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--color-text-secondary);border-bottom:1px solid rgba(0,0,0,.07)">Notes</th>
        </tr>
      </thead>
      <tbody id="historyTableBody">
        <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--color-text-secondary)"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>
      </tbody>
    </table>
  </div>
  <div id="historyPagination" style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-top:1px solid rgba(0,0,0,.06);font-size:12px;color:var(--color-text-secondary)"></div>
</div>

<!-- Product datalist for search -->
<datalist id="product_search_list"></datalist>

<script>
  let productsData = [];
  let locationsData = [];
  let uomsData = [];
  let itemCount = 0;

  const csrfToken = '<?php echo htmlspecialchars(CSRF_TOKEN, ENT_QUOTES, 'UTF-8'); ?>';

  async function fetchRelatedData() {
    try {
      const res = await fetch('../api/inventory_actions.php?action=related_data');
      const json = await res.json();
      if (json.success) {
        productsData = json.data.products || [];
        locationsData = json.data.locations || [];
        uomsData = json.data.uoms || [];
        populateDropdowns();
        fetchStats();
      }
    } catch (e) {
      console.error('Error fetching related data:', e);
    }
  }

  async function fetchStats() {
    try {
      const res = await fetch('../api/inventory_actions.php?action=stock_in_stats');
      const json = await res.json();
      if (json.success) {
        const stats = json.data;
        animateValue('statTotal', stats.total || 0);
        animateValue('statMonth', stats.this_month || 0);
        animateValue('statQuantity', stats.total_quantity || 0);
        animateValue('statLocations', stats.locations || 0);
      }
    } catch (e) {
      console.error('Error fetching stats:', e);
    }
  }

  function animateValue(id, end) {
    const el = document.getElementById(id);
    if (!el) return;
    if (end === 0) {
      el.textContent = '0';
      return;
    }
    let start = 0;
    const step = Math.max(1, Math.ceil(end / 20));
    const timer = setInterval(() => {
      start = Math.min(start + step, end);
      el.textContent = start;
      if (start >= end) clearInterval(timer);
    }, 25);
  }

  function populateDropdowns() {
    // Populate location dropdown
    const locSelect = document.querySelector('select[name="location_id"]');
    if (locSelect) {
      locSelect.innerHTML = '<option value="">Select Location</option>' + 
        locationsData.map(l => `<option value="${l.location_id}">${l.location_name} (${l.property_name || ''})</option>`).join('');
    }

    // Populate product datalist
    const productDatalist = document.getElementById('product_search_list');
    if (productDatalist) {
      productDatalist.innerHTML = productsData.map(p => 
        `<option value="${p.product_name}" data-id="${p.product_id}" data-code="${p.product_code || ''}"></option>`
      ).join('');
    }
  }

  function addItemRow() {
    itemCount++;
    const tbody = document.getElementById('itemsTableBody');
    
    // Remove empty row if exists
    if (tbody.querySelector('td[colspan]')) {
      tbody.innerHTML = '';
    }

    const row = document.createElement('tr');
    row.dataset.rowId = itemCount;
    row.innerHTML = `
      <td>
        <input type="text" class="sin-form-input product-search" 
               list="product_search_list" 
               name="items[${itemCount}][product_name]"
               placeholder="Search product..."
               oninput="updateProductId(this, ${itemCount})"
               required>
        <input type="hidden" name="items[${itemCount}][product_id]" id="product_id_${itemCount}">
      </td>
      <td>
        <input type="number" class="sin-form-input" 
               name="items[${itemCount}][quantity]"
               min="1" 
               placeholder="0" 
               required>
      </td>
      <td>
        <select class="sin-form-select" name="items[${itemCount}][uom_id]" required>
          <option value="">Select UOM</option>
          ${uomsData.map(u => `<option value="${u.uom_id}">${u.uom_name}</option>`).join('')}
        </select>
      </td>
      <td>
        <input type="text" class="sin-form-input" 
               name="items[${itemCount}][item_notes]"
               placeholder="Optional notes">
      </td>
      <td style="text-align: center;">
        <button type="button" onclick="removeItemRow(${itemCount})" class="sin-remove-btn">
          <i class="fas fa-trash"></i>
        </button>
      </td>
    `;
    tbody.appendChild(row);
  }

  function updateProductId(input, rowId) {
    const productName = input.value;
    const hiddenInput = document.getElementById(`product_id_${rowId}`);
    const product = productsData.find(p => p.product_name === productName);
    
    if (product) {
      hiddenInput.value = product.product_id;
      // Fetch UOMs for this product
      fetchProductUOMs(product.product_id, rowId);
    } else {
      hiddenInput.value = '';
      // Reset UOM dropdown to all UOMs
      const uomSelect = input.closest('tr').querySelector('select[name*="uom_id"]');
      if (uomSelect) {
        uomSelect.innerHTML = '<option value="">Select UOM</option>' + 
          uomsData.map(u => `<option value="${u.uom_id}">${u.uom_name}</option>`).join('');
      }
    }
  }

  async function fetchProductUOMs(productId, rowId) {
    try {
      const res = await fetch(`../api/product_uom_conversions.php?action=list&product_id=${productId}`);
      const json = await res.json();
      if (json.success) {
        const uomSelect = document.querySelector(`tr[data-row-id="${rowId}"] select[name*="uom_id"]`);
        if (uomSelect) {
          uomSelect.innerHTML = '<option value="">Select UOM</option>';

          // Add base UOM and conversion UOMs
          const uniqueUOMs = new Map();
          json.data.forEach(conv => {
            const key = conv.to_uom_id;
            if (!uniqueUOMs.has(key)) {
              uniqueUOMs.set(key, {
                uom_id: conv.to_uom_id,
                uom_name: conv.to_uom_name,
                uom_code: conv.to_uom_code,
                conversion_factor: conv.conversion_factor,
                is_default: conv.is_default
              });
            }
          });

          // Also add the base UOM from products table if not in conversions
          const product = productsData.find(p => p.product_id === productId);
          if (product && product.uom_id && !uniqueUOMs.has(product.uom_id)) {
            const baseUom = uomsData.find(u => u.uom_id === product.uom_id);
            if (baseUom) {
              uniqueUOMs.set(product.uom_id, {
                uom_id: baseUom.uom_id,
                uom_name: baseUom.uom_name,
                uom_code: baseUom.uom_code,
                conversion_factor: 1,
                is_default: true
              });
            }
          }

          // Populate dropdown
          uniqueUOMs.forEach((uom) => {
            const option = document.createElement('option');
            option.value = uom.uom_id;
            option.textContent = `${uom.uom_name} (${uom.uom_code})`;
            if (uom.is_default) {
              option.selected = true;
            }
            uomSelect.appendChild(option);
          });
        }
      }
    } catch (e) {
      console.error('Error fetching product UOMs:', e);
    }
  }

  function removeItemRow(rowId) {
    const row = document.querySelector(`tr[data-row-id="${rowId}"]`);
    if (row) {
      row.remove();
      
      // Check if table is empty
      const tbody = document.getElementById('itemsTableBody');
      if (tbody.querySelectorAll('tr').length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="5" style="text-align: center; padding: 30px; color: var(--color-text-secondary);">
              <i class="fas fa-box-open" style="font-size: 24px; opacity: 0.3; margin-bottom: 8px;"></i>
              <div>No items added yet</div>
            </td>
          </tr>
        `;
      }
    }
  }

  function resetForm() {
    document.getElementById('stockInForm').reset();
    document.getElementById('itemsTableBody').innerHTML = `
      <tr>
        <td colspan="5" style="text-align: center; padding: 30px; color: var(--color-text-secondary);">
          <i class="fas fa-box-open" style="font-size: 24px; opacity: 0.3; margin-bottom: 8px;"></i>
          <div>No items added yet</div>
        </td>
      </tr>
    `;
    itemCount = 0;
  }

  function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
      position: fixed; top: 20px; right: 20px; z-index: 10000;
      padding: 12px 20px; border-radius: 8px; font-size: 13px; font-weight: 500;
      color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      animation: slideIn 0.3s ease-out;
    `;
    toast.textContent = message;
    if (type === 'success') toast.style.background = 'var(--prod-green, #2E7D32)';
    else if (type === 'error') toast.style.background = 'var(--prod-red, #C62828)';
    else toast.style.background = 'var(--prod-blue, #1A6BBF)';
    document.body.appendChild(toast);
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transition = 'opacity 0.3s';
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }

  document.getElementById('stockInForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
      action: 'stock_in',
      csrf_token: csrfToken,
      reference_type: formData.get('reference_type'),
      reference_number: formData.get('reference_number'),
      location_id: formData.get('location_id'),
      transaction_date: formData.get('transaction_date'),
      notes: formData.get('notes'),
      items: []
    };

    // Collect items
    const rows = document.querySelectorAll('#itemsTableBody tr:not([data-row-id=""])');
    rows.forEach(row => {
      const rowId = row.dataset.rowId;
      const productId = document.getElementById(`product_id_${rowId}`)?.value;
      const productName = row.querySelector(`input[name="items[${rowId}][product_name]"]`)?.value;
      const quantity = row.querySelector(`input[name="items[${rowId}][quantity]"]`)?.value;
      const uomId = row.querySelector(`select[name="items[${rowId}][uom_id]"]`)?.value;
      const itemNotes = row.querySelector(`input[name="items[${rowId}][item_notes]"]`)?.value;

      if (productId && quantity) {
        data.items.push({
          product_id: productId,
          product_name: productName,
          quantity: parseFloat(quantity),
          uom_id: uomId,
          notes: itemNotes
        });
      }
    });

    if (data.items.length === 0) {
      showToast('Please add at least one item', 'error');
      return;
    }

    try {
      const res = await fetch('../api/inventory_actions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });
      const result = await res.json();
      
      if (result.success) {
        showToast('Stock in recorded successfully', 'success');
        resetForm();
        fetchStats();
        fetchHistory();
      } else {
        showToast(result.message || 'Failed to record stock in', 'error');
      }
    } catch (error) {
      console.error('Error:', error);
      showToast('Network error. Please try again.', 'error');
    }
  });

  // Set default date to today
  document.querySelector('input[name="transaction_date"]').valueAsDate = new Date();

  // ── History ──────────────────────────────────────────
  let historyData = [];
  let historyFiltered = [];
  let historyPage = 1;
  const HISTORY_PER_PAGE = 15;

  const REF_TYPE_LABELS = {
    MANUAL: { label: 'Manual', color: 'var(--prod-blue)', bg: 'var(--prod-blue-pale)' },
    RETURN: { label: 'Return', color: 'var(--prod-green)', bg: 'var(--prod-green-pale)' },
    DONATION: { label: 'Donation', color: 'var(--prod-purple)', bg: 'var(--prod-purple-pale)' },
    TRANSFER: { label: 'Transfer', color: 'var(--prod-amber)', bg: 'var(--prod-amber-pale)' },
    ADJUSTMENT: { label: 'Adjustment', color: '#555', bg: '#F0F0F0' },
  };

  async function fetchHistory() {
    document.getElementById('historyTableBody').innerHTML =
      `<tr><td colspan="8" style="text-align:center;padding:40px;color:var(--color-text-secondary)"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>`;
    try {
      const res = await fetch('../api/stock_transactions.php?action=list');
      const json = await res.json();
      if (json.success) {
        // Filter only transactions submitted from the Stock In page
        historyData = json.data.filter(t =>
          ['MANUAL', 'RETURN', 'DONATION', 'TRANSFER', 'ADJUSTMENT'].includes(t.reference_type) &&
          ['stock_in', 'stock_in_manual', 'adjustment_add', 'transfer_in'].includes(t.transaction_type)
        );
        historyFiltered = [...historyData];
        historyPage = 1;
        renderHistory();
      }
    } catch (e) {
      document.getElementById('historyTableBody').innerHTML =
        `<tr><td colspan="8" style="text-align:center;padding:40px;color:var(--prod-red)">Failed to load history.</td></tr>`;
    }
  }

  function filterHistory() {
    const search = document.getElementById('historySearch').value.toLowerCase();
    const refType = document.getElementById('historyRefTypeFilter').value;
    historyFiltered = historyData.filter(t => {
      const matchSearch = !search ||
        (t.product_name || '').toLowerCase().includes(search) ||
        (t.reference_number || '').toLowerCase().includes(search) ||
        (t.notes || '').toLowerCase().includes(search);
      const matchType = !refType || t.reference_type === refType;
      return matchSearch && matchType;
    });
    historyPage = 1;
    renderHistory();
  }

  function renderHistory() {
    const tbody = document.getElementById('historyTableBody');
    const total = historyFiltered.length;
    const totalPages = Math.max(1, Math.ceil(total / HISTORY_PER_PAGE));
    const start = (historyPage - 1) * HISTORY_PER_PAGE;
    const rows = historyFiltered.slice(start, start + HISTORY_PER_PAGE);

    if (rows.length === 0) {
      tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:40px;color:var(--color-text-secondary)"><i class="fas fa-inbox" style="font-size:24px;opacity:.3;display:block;margin-bottom:8px"></i>No stock-in records found.</td></tr>`;
      document.getElementById('historyPagination').innerHTML = '';
      return;
    }

    tbody.innerHTML = rows.map(t => {
      const badge = REF_TYPE_LABELS[t.reference_type] || { label: t.reference_type, color: '#555', bg: '#F0F0F0' };
      const date = t.transaction_date ? new Date(t.transaction_date).toLocaleDateString() : '—';
      return `<tr style="border-bottom:1px solid rgba(0,0,0,.04);transition:background .15s" onmouseenter="this.style.background='rgba(0,0,0,.02)'" onmouseleave="this.style.background=''">
        <td style="padding:10px 14px;white-space:nowrap;color:var(--color-text-secondary);font-size:12px">${date}</td>
        <td style="padding:10px 14px;font-weight:500">${t.product_name || '—'}<br><span style="font-size:11px;color:var(--color-text-secondary)">${t.product_code || ''}</span></td>
        <td style="padding:10px 14px;font-size:12px">${t.location_name || '—'}<br><span style="font-size:11px;color:var(--color-text-secondary)">${t.property_name || ''}</span></td>
        <td style="padding:10px 14px">
          <span style="background:${badge.bg};color:${badge.color};padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600">${badge.label}</span>
        </td>
        <td style="padding:10px 14px;font-family:var(--prod-mono);font-size:12px">${t.reference_number || '—'}</td>
        <td style="padding:10px 14px;text-align:right;font-family:var(--prod-mono);font-weight:600;color:var(--prod-green)">
          +${parseFloat(t.quantity).toFixed(3)} ${t.uom_name || ''}
        </td>
        <td style="padding:10px 14px;text-align:right;font-family:var(--prod-mono);font-size:12px">
          ${parseFloat(t.stock_after).toFixed(3)}
        </td>
        <td style="padding:10px 14px;font-size:12px;color:var(--color-text-secondary)">${t.notes || '—'}</td>
      </tr>`;
    }).join('');

    // Pagination
    document.getElementById('historyPagination').innerHTML = `
      <span>Showing ${start + 1}–${Math.min(start + HISTORY_PER_PAGE, total)} of ${total}</span>
      <div style="display:flex;gap:4px">
        <button onclick="historyPage=Math.max(1,historyPage-1);renderHistory()" ${historyPage===1?'disabled':''} class="sin-btn" style="padding:3px 10px;font-size:12px"><i class="fas fa-chevron-left"></i></button>
        <button onclick="historyPage=Math.min(${totalPages},historyPage+1);renderHistory()" ${historyPage===totalPages?'disabled':''} class="sin-btn" style="padding:3px 10px;font-size:12px"><i class="fas fa-chevron-right"></i></button>
      </div>`;
  }

  // Initialize
  document.addEventListener('DOMContentLoaded', () => {
    fetchRelatedData();
    fetchHistory();
  });
</script>
