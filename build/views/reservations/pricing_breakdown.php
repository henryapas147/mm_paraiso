<style>
  .pricing-breakdown {
    background: #fff;
    border: 0.5px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
  }

  .pb-header {
    padding: 16px 20px;
    border-bottom: 0.5px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .pb-title {
    font-size: 16px;
    font-weight: 500;
  }

  .pb-subtitle {
    font-size: 13px;
    color: #666;
    margin-top: 2px;
  }

  .pb-toolbar {
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

  .pb-table-wrap {
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

  tbody tr.total-row {
    background: #f9fafb;
    font-weight: 500;
  }

  tbody tr.total-row.grand-total {
    background: #E6F1FB;
    font-weight: 600;
  }

  td {
    padding: 12px 16px;
    vertical-align: middle;
  }

  .component-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
    text-transform: capitalize;
  }

  .component-badge.base_rate {
    background: #EAF3DE;
    color: #27500A;
  }

  .component-badge.seasonal_rate {
    background: #E6F1FB;
    color: #0C447C;
  }

  .component-badge.pricing_rule {
    background: #FAEEDA;
    color: #633806;
  }

  .component-badge.promotion {
    background: #EEEDFE;
    color: #3C3489;
  }

  .component-badge.extra_guest_fee {
    background: #FEF6E6;
    color: #C75100;
  }

  .component-badge.tax {
    background: #FCEBEB;
    color: #791F1F;
  }

  .component-badge.deposit {
    background: #F1EFE8;
    color: #5F5E5A;
  }

  .rule-link {
    color: #185FA5;
    cursor: pointer;
    text-decoration: none;
  }

  .rule-link:hover {
    text-decoration: underline;
  }

  .multiplier {
    font-family: monospace;
    font-size: 12px;
    color: #666;
  }

  .discount {
    color: #A32D2D;
  }

  .amount {
    font-family: monospace;
    font-size: 13px;
  }

  .amount.negative {
    color: #A32D2D;
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

  .totals-summary {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    padding: 16px 20px;
    border-top: 0.5px solid #e5e7eb;
    background: #f9fafb;
  }

  .total-card {
    background: #fff;
    border: 0.5px solid #e5e7eb;
    border-radius: 8px;
    padding: 12px 16px;
  }

  .total-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 4px;
  }

  .total-value {
    font-size: 20px;
    font-weight: 500;
    font-family: monospace;
  }

  .total-value.discount {
    color: #A32D2D;
  }

  .total-value.final {
    color: #185FA5;
  }

  /* ── Responsive ──────────────────────────────────── */
  @media (max-width: 768px) {
    .pb-header {
      flex-direction: column;
      align-items: stretch;
      gap: 12px;
      padding: 16px 18px;
    }
    .pb-toolbar {
      flex-wrap: wrap;
      width: 100%;
    }
  }

  @media (max-width: 480px) {
    .pb-title {
      font-size: 14px;
    }
    .pb-subtitle {
      font-size: 12px;
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
    .component-badge {
      font-size: 10px;
      padding: 2px 8px;
    }
  }

  @media (max-width: 640px) {
    .multiplier {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .amount {
      font-size: 12px;
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

  @media (max-width: 640px) {
    .totals-summary {
      grid-template-columns: 1fr;
      gap: 12px;
      padding: 12px 18px;
    }
  }

  @media (max-width: 640px) {
    .total-card {
      padding: 10px 14px;
    }
  }

  @media (max-width: 640px) {
    .total-label {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .total-value {
      font-size: 18px;
    }
  }

  @media (max-width: 768px) {
    .reservation-selector > div {
      flex-direction: column;
      align-items: stretch;
    }
  }
</style>

<script>
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
        document.getElementById('reservationContext').style.display = 'block';
        
        // Update price comparison section
        const subtotal = parseFloat(data.subtotal_amount || 0);
        const discount = parseFloat(data.discount_amount || 0);
        const total = parseFloat(data.total_amount || 0);
        
        document.getElementById('comparisonOriginal').textContent = formatCurrency(subtotal > 0 ? subtotal : (subtotal + discount));
        document.getElementById('comparisonAdjustments').textContent = discount > 0 ? '-' + formatCurrency(discount) : '₱0.00';
        document.getElementById('comparisonFinal').textContent = formatCurrency(total > 0 ? total : (subtotal + discount - discount));
        
        if (subtotal > 0 || discount > 0 || total > 0) {
          document.getElementById('priceComparison').style.display = 'block';
        }
      }
    } catch (e) {
      console.error('Error loading context:', e);
    }
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
      document.getElementById('reservationId').value = resId;
    }
  }
  
  function loadSelectedReservation() {
    const resId = document.getElementById('reservationSelect').value;
    if (!resId) {
      showToast('Please select a reservation', 'error');
      return;
    }
    window.location.href = `?reservation_id=${resId}`;
  }

  async function fetchPricingBreakdown(reservationId) {
    try {
      const res = await fetch(`../api/reservation_pricing_breakdown.php?action=list&reservation_id=${reservationId}`);
      const json = await res.json();
      if (json.success) {
        renderBreakdown(json.data, json.totals);
      }
    } catch (e) {
      console.error('Error fetching pricing breakdown:', e);
      document.getElementById('emptyStateText').textContent = 'Failed to load pricing breakdown';
      document.getElementById('emptyStateMsg').innerHTML = '<i class="fas fa-exclamation-circle"></i> Failed to load pricing breakdown';
    }
  }

  function componentClass(type) {
    return ['base_rate', 'seasonal_rate', 'pricing_rule', 'promotion', 'extra_guest_fee', 'tax', 'deposit'].includes(type) ? type : 'base_rate';
  }

  function formatCurrency(amount) {
    return new Intl.NumberFormat('en-PH', {
      style: 'currency',
      currency: 'PHP',
      minimumFractionDigits: 2
    }).format(amount);
  }

  function renderBreakdown(data, totals) {
    const tbody = document.getElementById('breakdownTableBody');
    const totalsSection = document.getElementById('totalsSection');
    
    if (!data || data.length === 0) {
      tbody.innerHTML = `
        <tr><td colspan="7" class="empty-state">
          <i class="fas fa-receipt"></i>
          No pricing breakdown available
        </td></tr>
      `;
      totalsSection.style.display = 'none';
      return;
    }

    totalsSection.style.display = 'grid';
    
    // Update totals
    document.getElementById('totalBase').textContent = formatCurrency(totals.total_base || 0);
    document.getElementById('totalDiscount').textContent = formatCurrency(totals.total_discount || 0);
    document.getElementById('totalFinal').textContent = formatCurrency(totals.total_final || 0);

    // Render rows
    tbody.innerHTML = data.map((row, index) => {
      const isLast = index === data.length - 1;
      const multiplier = row.multiplier ? `${row.multiplier}x` : '';
      const discount = row.discount_amount ? `-${formatCurrency(row.discount_amount)}` : '';
      const hasRule = row.rule_id;
      const hasSeason = row.season_id;
      const hasPromo = row.promo_id;
      
      let componentName = row.component_name;
      if (hasRule) {
        componentName += ` <a class="rule-link" onclick="viewRule('${row.rule_id}', 'rule')" title="View rule"><i class="fas fa-external-link-alt" style="font-size:10px"></i></a>`;
      } else if (hasSeason) {
        componentName += ` <a class="rule-link" onclick="viewRule('${row.season_id}', 'season')" title="View season"><i class="fas fa-external-link-alt" style="font-size:10px"></i></a>`;
      } else if (hasPromo) {
        componentName += ` <a class="rule-link" onclick="viewRule('${row.promo_id}', 'promo')" title="View promotion"><i class="fas fa-external-link-alt" style="font-size:10px"></i></a>`;
      }

      return `
        <tr class="${isLast ? 'total-row' : ''}">
          <td><span class="component-badge ${componentClass(row.component_type)}">${row.component_type.replace(/_/g, ' ')}</span></td>
          <td>${componentName}</td>
          <td class="numeric">${formatCurrency(row.base_amount)}</td>
          <td class="numeric multiplier">${multiplier}</td>
          <td class="numeric discount">${discount}</td>
          <td class="numeric amount">${formatCurrency(row.final_amount)}</td>
          <td class="center">${row.is_visible == 1 ? '<i class="fas fa-eye" title="Visible on receipt"></i>' : '<i class="fas fa-eye-slash" style="color:#999" title="Internal only"></i>'}</td>
        </tr>
      `;
    }).join('');
  }

  function viewRule(id, type) {
    // Navigate to the appropriate settings page
    switch(type) {
      case 'rule':
        window.open(`settings/pricing_rules.php?view=${id}`, '_blank');
        break;
      case 'season':
        window.open(`settings/seasonal_rates.php?view=${id}`, '_blank');
        break;
      case 'promo':
        window.open(`settings/promotions.php?view=${id}`, '_blank');
        break;
    }
  }

  async function recalculatePricing(reservationId) {
    if (!confirm('Recalculate pricing? This will clear the current breakdown and regenerate based on current rules.')) return;
    
    try {
      const res = await fetch('../api/reservation_pricing_breakdown.php?action=delete_by_reservation', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ reservation_id: reservationId })
      });
      const json = await res.json();
      if (json.success) {
        // Reload to see updated breakdown (the API would need to recalculate)
        showToast('Pricing cleared. Recalculation would happen on next price calculation.', 'success');
        fetchPricingBreakdown(reservationId);
      }
    } catch (e) {
      showToast('Failed to recalculate', 'error');
    }
  }

  let toastTimer;
  function showToast(msg, type = 'success') {
    const t = document.getElementById('pbToast');
    t.textContent = msg;
    t.className = 'toast ' + type + ' show';
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { t.classList.remove('show'); }, 3000);
  }

  // Initialize when reservation_id is available
  document.addEventListener('DOMContentLoaded', () => {
    const reservationId = document.getElementById('reservationId')?.value;
    if (reservationId) {
      fetchPricingBreakdown(reservationId);
      loadReservationContext(reservationId);
    } else {
      loadReservationsList();
    }
  });
</script>

<div class="pricing-breakdown">
  <div class="pb-header">
    <div>
      <div class="pb-title">Pricing Breakdown</div>
      <div class="pb-subtitle">How this reservation's total was calculated</div>
    </div>
    <div class="pb-toolbar">
      <button class="btn" onclick="recalculatePricing(document.getElementById('reservationId').value)">
        <i class="fas fa-refresh"></i> Recalculate
      </button>
      <button class="btn btn-primary" onclick="window.print()">
        <i class="fas fa-print"></i> Print
      </button>
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
        <div style="font-size:18px;font-weight:600;color:#333" id="comparisonOriginal">₱0.00</div>
      </div>
      <div style="color:#999"><i class="fas fa-minus"></i></div>
      <div style="text-align:center">
        <div style="font-size:11px;color:#666;text-transform:uppercase;letter-spacing:0.05em">Adjustments</div>
        <div style="font-size:18px;font-weight:600;color:#A32D2D" id="comparisonAdjustments">-₱0.00</div>
      </div>
      <div style="color:#999"><i class="fas fa-equals"></i></div>
      <div style="text-align:center">
        <div style="font-size:11px;color:#666;text-transform:uppercase;letter-spacing:0.05em">Final Total</div>
        <div style="font-size:18px;font-weight:600;color:#27500A" id="comparisonFinal">₱0.00</div>
      </div>
    </div>
  </div>

  <div class="pb-table-wrap">
    <table>
      <thead>
        <tr>
          <th>Type</th>
          <th>Component</th>
          <th class="numeric">Base Amount</th>
          <th class="numeric">Multiplier</th>
          <th class="numeric">Discount</th>
          <th class="numeric">Final</th>
          <th class="center">Visible</th>
        </tr>
      </thead>
      <tbody id="breakdownTableBody">
        <tr><td colspan="7" class="empty-state" id="emptyStateMsg">
          <i class="fas fa-receipt"></i>
          <span id="emptyStateText"><?php echo empty($_GET['reservation_id']) ? 'Select a reservation to view pricing breakdown' : 'Loading pricing breakdown...'; ?></span>
        </td></tr>
      </tbody>
    </table>
  </div>
  
  <div class="totals-summary" id="totalsSection" style="display:none;">
    <div class="total-card">
      <div class="total-label">Total Base</div>
      <div class="total-value" id="totalBase">₱0.00</div>
    </div>
    <div class="total-card">
      <div class="total-label">Total Discounts</div>
      <div class="total-value discount" id="totalDiscount">₱0.00</div>
    </div>
    <div class="total-card">
      <div class="total-label">Final Total</div>
      <div class="total-value final" id="totalFinal">₱0.00</div>
    </div>
  </div>
</div>

<input type="hidden" id="reservationId" value="<?php echo $_GET['reservation_id'] ?? ''; ?>">

<div class="toast" id="pbToast" role="status" aria-live="polite"></div>
