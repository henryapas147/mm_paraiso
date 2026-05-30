<style>
  .promo-manager {
    background: #fff;
    border: 0.5px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
  }

  .promo-header {
    padding: 16px 20px;
    border-bottom: 0.5px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .promo-title {
    font-size: 16px;
    font-weight: 500;
  }

  .promo-subtitle {
    font-size: 13px;
    color: #666;
    margin-top: 2px;
  }

  .promo-toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 8px;
    border: 0.5px solid #d1d5db;
    background: #fff;
    color: #333;
    cursor: pointer;
    transition: background .15s;
  }

  .btn:hover {
    background: #f9fafb;
  }

  .btn-primary {
    background: #185FA5;
    color: #fff;
    border-color: #185FA5;
  }

  .btn-primary:hover {
    background: #0C447C;
  }

  .btn-danger {
    background: #dc2626;
    color: #fff;
    border-color: #dc2626;
  }

  .btn-danger:hover {
    background: #991b1b;
  }

  .promo-apply-form {
    padding: 16px 20px;
    border-bottom: 0.5px solid #e5e7eb;
    background: #f9fafb;
    display: flex;
    gap: 12px;
    align-items: flex-end;
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .form-group label {
    font-size: 12px;
    font-weight: 500;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  input {
    font-family: system-ui, sans-serif;
    font-size: 13px;
    color: #333;
    background: #fff;
    border: 0.5px solid #d1d5db;
    border-radius: 8px;
    padding: 8px 12px;
    outline: none;
    width: 200px;
  }

  input:focus {
    border-color: #185FA5;
  }

  .validation-result {
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 13px;
    display: none;
  }

  .validation-result.success {
    background: #EAF3DE;
    color: #27500A;
    display: block;
  }

  .validation-result.error {
    background: #FCEBEB;
    color: #791F1F;
    display: block;
  }

  .promo-table-wrap {
    overflow-x: auto;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }

  thead th {
    padding: 10px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 500;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    border-bottom: 0.5px solid #e5e7eb;
    background: #f9fafb;
  }

  thead th.numeric,
  td.numeric {
    text-align: right;
  }

  thead th.center,
  td.center {
    text-align: center;
  }

  tbody tr {
    border-bottom: 0.5px solid #e5e7eb;
  }

  tbody tr:last-child {
    border-bottom: none;
  }

  tbody tr:hover {
    background: #f9fafb;
  }

  td {
    padding: 12px 16px;
    vertical-align: middle;
  }

  .promo-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
    text-transform: capitalize;
  }

  .promo-badge.banner {
    background: #E6F1FB;
    color: #0C447C;
  }

  .promo-badge.package {
    background: #FCEBEB;
    color: #791F1F;
  }

  .promo-badge.seasonal_deal {
    background: #EAF3DE;
    color: #27500A;
  }

  .promo-badge.referral {
    background: #F3E6F6;
    color: #534AB7;
  }

  .promo-badge.loyalty {
    background: #FEF6E6;
    color: #C75100;
  }

  .promo-badge.voucher {
    background: #F6E6E6;
    color: #A32D2D;
  }

  .status-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
  }

  .status-badge.active {
    background: #EAF3DE;
    color: #27500A;
  }

  .status-badge.removed {
    background: #F1EFE8;
    color: #5F5E5A;
    text-decoration: line-through;
  }

  .discount-amount {
    color: #A32D2D;
    font-weight: 500;
  }

  .empty-state {
    padding: 48px 24px;
    text-align: center;
    color: #666;
  }

  .empty-state i {
    font-size: 32px;
    margin-bottom: 12px;
    display: block;
    color: #999;
  }

  .totals-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 20px;
    border-top: 0.5px solid #e5e7eb;
    background: #f9fafb;
  }

  .total-label {
    font-size: 13px;
    color: #666;
  }

  .total-value {
    font-size: 18px;
    font-weight: 600;
    color: #A32D2D;
  }
</style>

<script>
  let currentPromos = [];
  let validatedPromo = null;

  // Load reservation details for context header
  async function loadReservationContext(reservationId) {
    try {
      const res = await fetch(`../api/reservations.php?action=single&id=${reservationId}`);
      const json = await res.json();
      if (json.success && json.data) {
        const data = json.data;
        document.getElementById('contextResCode').textContent = data.reservation_code || '-';
        document.getElementById('contextGuestName').textContent = data.guest_name || data.walkin_first_name + ' ' + data.walkin_last_name || '-';
        document.getElementById('contextCheckIn').textContent = data.check_in_date ? new Date(data.check_in_date).toLocaleDateString() : '-';
        
        // Store original amount for price comparison
        const originalAmt = parseFloat(data.total_amount || 0) + parseFloat(data.discount_amount || 0);
        document.getElementById('originalAmount').value = originalAmt;
        document.getElementById('baseAmount').value = originalAmt;
        
        document.getElementById('reservationContext').style.display = 'block';
        updatePriceComparison();
      }
    } catch (e) {
      console.error('Error loading context:', e);
    }
  }

  function updatePriceComparison() {
    const originalAmt = parseFloat(document.getElementById('originalAmount').value || 0);
    const totalDiscount = parseFloat(document.getElementById('totalDiscount').textContent.replace(/[^0-9.-]+/g, '') || 0);
    const finalAmt = Math.max(0, originalAmt - totalDiscount);
    
    document.getElementById('originalTotal').textContent = formatCurrency(originalAmt);
    document.getElementById('discountTotal').textContent = '-' + formatCurrency(totalDiscount);
    document.getElementById('finalTotal').textContent = formatCurrency(finalAmt);
    
    if (originalAmt > 0 || totalDiscount > 0) {
      document.getElementById('priceComparison').style.display = 'block';
    }
  }

  async function fetchReservationPromotions(reservationId) {
    try {
      const res = await fetch(`../api/reservation_promotions.php?action=list&reservation_id=${reservationId}`);
      const json = await res.json();
      if (json.success) {
        currentPromos = json.data;
        renderPromos(json.data, json.stats);
        updatePriceComparison();
      }
    } catch (e) {
      console.error('Error fetching promos:', e);
      document.getElementById('emptyStateText').textContent = 'Failed to load promotions';
      document.getElementById('emptyStateMsg').innerHTML = '<i class="fas fa-exclamation-circle"></i> Failed to load promotions';
    }
  }

  function promoClass(type) {
    return ['banner', 'package', 'seasonal_deal', 'referral', 'loyalty', 'voucher'].includes(type) ? type : 'banner';
  }

  function formatCurrency(amount) {
    return new Intl.NumberFormat('en-PH', {
      style: 'currency',
      currency: 'PHP',
      minimumFractionDigits: 2
    }).format(amount);
  }

  function formatDate(dateStr) {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleDateString();
  }

  function renderPromos(data, stats) {
    const tbody = document.getElementById('promoTableBody');
    const totalDiscount = document.getElementById('totalDiscount');
    
    totalDiscount.textContent = formatCurrency(stats?.total_discounts || 0);
    
    if (!data || data.length === 0) {
      tbody.innerHTML = `
        <tr><td colspan="8" class="empty-state">
          <i class="fas fa-ticket"></i>
          <span>No promotions applied yet. Enter a code above.</span>
        </td></tr>
      `;
      return;
    }

    tbody.innerHTML = data.map(row => {
      const isRemoved = row.is_applied == 0;
      return `
        <tr class="${isRemoved ? 'removed' : ''}">
          <td><span class="promo-badge ${promoClass(row.promo_type)}">${row.promo_type.replace(/_/g, ' ')}</span></td>
          <td>${row.promo_title}</td>
          <td>${row.promo_code_used || row.promo_code || '—'}</td>
          <td class="numeric discount-amount">${isRemoved ? '' : '-'}${formatCurrency(row.discount_amount)}</td>
          <td>${formatDate(row.valid_until || row.check_in_date)}</td>
          <td><span style="text-transform:capitalize">${row.source_channel || 'staff'}</span></td>
          <td class="center"><span class="status-badge ${isRemoved ? 'removed' : 'active'}">${isRemoved ? 'Removed' : 'Applied'}</span></td>
          <td class="center">
            ${!isRemoved ? `
              <button class="btn btn-danger" onclick="removePromo('${row.res_promo_id}', '${row.discount_amount}')" style="padding:4px 8px;font-size:12px">
                <i class="fas fa-times"></i>
              </button>
            ` : '—'}
          </td>
        </tr>
      `;
    }).join('');
  }

  // Reservation selector functions (for standalone use)
  let availableReservations = [];
  
  async function loadReservationsList() {
    try {
      const res = await fetch('../api/reservations.php?action=list&limit=100');
      const json = await res.json();
      if (json.success && json.data) {
        availableReservations = json.data;
        const select = document.getElementById('reservationSelect');
        select.innerHTML = '<option value="">-- Choose a reservation --</option>';
        json.data.forEach(r => {
          const option = document.createElement('option');
          option.value = r.reservation_id;
          option.textContent = `${r.reservation_code} - ${r.guest_name || r.walkin_first_name + ' ' + r.walkin_last_name} (${r.check_in_date})`;
          select.appendChild(option);
        });
      }
    } catch (e) {
      console.error('Error loading reservations:', e);
    }
  }
  
  function onReservationSelect() {
    const select = document.getElementById('reservationSelect');
    const resId = select.value;
    if (resId) {
      const res = availableReservations.find(r => r.reservation_id === resId);
      if (res) {
        document.getElementById('reservationId').value = resId;
        document.getElementById('checkInDate').value = res.check_in_date;
        document.getElementById('baseAmount').value = res.total_amount;
      }
    }
  }
  
  function loadSelectedReservation() {
    const resId = document.getElementById('reservationSelect').value;
    if (!resId) {
      showToast('Please select a reservation', 'error');
      return;
    }
    // Reload page with selected reservation
    window.location.href = `?reservation_id=${resId}&check_in_date=${document.getElementById('checkInDate').value}&base_amount=${document.getElementById('baseAmount').value}`;
  }

  async function validatePromoCode() {
    const code = document.getElementById('promoCodeInput').value.trim();
    const reservationId = document.getElementById('reservationId').value;
    const checkInDate = document.getElementById('checkInDate').value;
    const resultDiv = document.getElementById('validationResult');
    const applyBtn = document.getElementById('applyPromoBtn');
    
    if (!code) {
      resultDiv.className = 'validation-result error';
      resultDiv.textContent = 'Please enter a promo code';
      return;
    }

    try {
      const res = await fetch(`../api/reservation_promotions.php?action=validate&code=${encodeURIComponent(code)}&reservation_id=${reservationId}&check_in_date=${checkInDate}`);
      const json = await res.json();
      
      if (json.success) {
        validatedPromo = json.promo;
        resultDiv.className = 'validation-result success';
        resultDiv.innerHTML = `
          <i class="fas fa-check-circle"></i> 
          <strong>${json.promo.promo_title}</strong> — 
          Valid until ${new Date(json.promo.valid_until).toLocaleDateString()}
        `;
        applyBtn.disabled = false;
      } else {
        validatedPromo = null;
        resultDiv.className = 'validation-result error';
        resultDiv.innerHTML = `<i class="fas fa-times-circle"></i> ${json.message}`;
        applyBtn.disabled = true;
      }
    } catch (e) {
      resultDiv.className = 'validation-result error';
      resultDiv.textContent = 'Validation failed';
    }
  }

  async function applyPromo() {
    if (!validatedPromo) return;
    
    const reservationId = document.getElementById('reservationId').value;
    const code = document.getElementById('promoCodeInput').value.trim();
    const csrfToken = document.getElementById('csrfToken').value;
    
    // Calculate discount (simplified - would need actual discount rules)
    const baseAmount = parseFloat(document.getElementById('baseAmount')?.value || 0);
    const discountValue = 10; // Default 10%
    const discountAmount = baseAmount * (discountValue / 100);
    
    try {
      const res = await fetch('../api/reservation_promotions.php?action=apply', {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify({
          reservation_id: reservationId,
          promo_id: validatedPromo.promo_id,
          promo_code_used: code,
          discount_amount: discountAmount,
          discount_type: 'percentage',
          discount_value: discountValue,
          component_name: validatedPromo.promo_title,
          source_channel: 'staff'
        })
      });
      
      const json = await res.json();
      if (json.success) {
        showToast('Promo applied successfully');
        document.getElementById('promoCodeInput').value = '';
        document.getElementById('validationResult').style.display = 'none';
        document.getElementById('applyPromoBtn').disabled = true;
        validatedPromo = null;
        fetchReservationPromotions(reservationId);
      } else {
        showToast(json.message, 'error');
      }
    } catch (e) {
      showToast('Failed to apply promo', 'error');
    }
  }

  async function removePromo(resPromoId, discountAmount) {
    if (!confirm('Remove this promo? The discount will be reversed.')) return;
    
    const reservationId = document.getElementById('reservationId').value;
    const csrfToken = document.getElementById('csrfToken').value;
    
    try {
      const res = await fetch('../api/reservation_promotions.php?action=remove', {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify({
          res_promo_id: resPromoId,
          reservation_id: reservationId,
          discount_amount: discountAmount
        })
      });
      
      const json = await res.json();
      if (json.success) {
        showToast('Promo removed');
        fetchReservationPromotions(reservationId);
      } else {
        showToast(json.message, 'error');
      }
    } catch (e) {
      showToast('Failed to remove promo', 'error');
    }
  }

  let toastTimer;
  function showToast(msg, type = 'success') {
    const t = document.getElementById('promoToast');
    t.textContent = msg;
    t.className = 'toast ' + type + ' show';
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { t.classList.remove('show'); }, 3000);
  }

  document.addEventListener('DOMContentLoaded', () => {
    const reservationId = document.getElementById('reservationId')?.value;
    if (reservationId) {
      fetchReservationPromotions(reservationId);
      loadReservationContext(reservationId);
    } else {
      loadReservationsList();
    }
  });
</script>

<div class="promo-manager">
  <div class="promo-header">
    <div>
      <div class="promo-title">Promotions & Discounts</div>
      <div class="promo-subtitle">Apply promo codes and view discounts</div>
    </div>
  </div>

  <?php if (empty($_GET['reservation_id'])): ?>
  <!-- Reservation Selector when no ID provided -->
  <div class="reservation-selector" style="padding:20px;background:#f9fafb;border-bottom:0.5px solid #e5e7eb;">
    <div style="display:flex;gap:12px;align-items:flex-end;">
      <div class="form-group" style="flex:1">
        <label>Select Reservation</label>
        <select id="reservationSelect" style="width:100%;padding:8px 12px;border:0.5px solid #d1d5db;border-radius:8px;background:#fff;" onchange="onReservationSelect()">
          <option value="">-- Choose a reservation --</option>
        </select>
      </div>
      <button class="btn btn-primary" onclick="loadSelectedReservation()">
        <i class="fas fa-arrow-right"></i> Go
      </button>
    </div>
  </div>
  <?php else: ?>
  <!-- Reservation Context Header -->
  <div class="reservation-context" id="reservationContext" style="padding:16px 20px;background:#E6F1FB;border-bottom:0.5px solid #e5e7eb;display:none;">
    <div style="display:flex;gap:32px;align-items:center;flex-wrap:wrap;">
      <div>
        <div style="font-size:11px;color:#666;text-transform:uppercase;letter-spacing:0.05em">Reservation</div>
        <div style="font-size:15px;font-weight:600;color:#0C447C" id="contextResCode">-</div>
      </div>
      <div>
        <div style="font-size:11px;color:#666;text-transform:uppercase;letter-spacing:0.05em">Guest</div>
        <div style="font-size:15px;font-weight:500" id="contextGuestName">-</div>
      </div>
      <div>
        <div style="font-size:11px;color:#666;text-transform:uppercase;letter-spacing:0.05em">Check-in</div>
        <div style="font-size:15px;font-weight:500" id="contextCheckIn">-</div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Price Comparison Section -->
  <div class="price-comparison" id="priceComparison" style="padding:16px 20px;background:#f0f7ff;border-bottom:0.5px solid #e5e7eb;display:none;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
      <div style="text-align:center">
        <div style="font-size:11px;color:#666;text-transform:uppercase;letter-spacing:0.05em">Original Amount</div>
        <div style="font-size:18px;font-weight:600;color:#333" id="originalTotal">₱0.00</div>
      </div>
      <div style="color:#999"><i class="fas fa-minus"></i></div>
      <div style="text-align:center">
        <div style="font-size:11px;color:#666;text-transform:uppercase;letter-spacing:0.05em">Total Discounts</div>
        <div style="font-size:18px;font-weight:600;color:#A32D2D" id="discountTotal">-₱0.00</div>
      </div>
      <div style="color:#999"><i class="fas fa-equals"></i></div>
      <div style="text-align:center">
        <div style="font-size:11px;color:#666;text-transform:uppercase;letter-spacing:0.05em">Final Amount</div>
        <div style="font-size:18px;font-weight:600;color:#27500A" id="finalTotal">₱0.00</div>
      </div>
    </div>
  </div>

  <div class="promo-apply-form">
    <div class="form-group">
      <label>Promo Code</label>
      <input type="text" id="promoCodeInput" placeholder="Enter code (e.g. SUMMER2024)">
    </div>
    <button class="btn" onclick="validatePromoCode()">
      <i class="fas fa-search"></i> Validate
    </button>
    <button class="btn btn-primary" id="applyPromoBtn" onclick="applyPromo()" disabled>
      <i class="fas fa-plus"></i> Apply
    </button>
  </div>
  
  <div class="validation-result" id="validationResult"></div>
  
  <div class="promo-table-wrap">
    <table>
      <thead>
        <tr>
          <th>Type</th>
          <th>Promotion</th>
          <th>Code Used</th>
          <th class="numeric">Discount</th>
          <th>Valid Until</th>
          <th>Source</th>
          <th class="center">Status</th>
          <th class="center"></th>
        </tr>
      </thead>
      <tbody id="promoTableBody">
        <tr><td colspan="8" class="empty-state" id="emptyStateMsg">
          <i class="fas fa-ticket"></i>
          <span id="emptyStateText"><?php echo empty($_GET['reservation_id']) ? 'Select a reservation to manage promotions' : 'Loading promotions...'; ?></span>
        </td></tr>
      </tbody>
    </table>
  </div>

  <div class="totals-bar">
    <span class="total-label">Total Discounts Applied</span>
    <span class="total-value" id="totalDiscount">₱0.00</span>
  </div>
</div>

<input type="hidden" id="reservationId" value="<?php echo $_GET['reservation_id'] ?? ''; ?>">
<input type="hidden" id="checkInDate" value="<?php echo $_GET['check_in_date'] ?? date('Y-m-d'); ?>">
<input type="hidden" id="baseAmount" value="<?php echo $_GET['base_amount'] ?? 0; ?>">
<input type="hidden" id="originalAmount" value="<?php echo $_GET['base_amount'] ?? 0; ?>">
<input type="hidden" id="csrfToken" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

<div class="toast" id="promoToast" role="status" aria-live="polite"></div>
