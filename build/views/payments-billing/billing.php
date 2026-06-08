<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Lora:ital,wght@0,600;1,500&display=swap" rel="stylesheet">

<script>
  const CSRF = '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>';

  const fmtDate = (v, includeTime = false) => {
    if (!v) return '—';
    const d = new Date(v.includes('T') || v.includes(' ') ? v : v + 'T00:00:00');
    if (isNaN(d)) return v;
    const datePart = d.toLocaleDateString('en-US', {month:'long', day:'numeric', year:'numeric'});
    if (!includeTime) return datePart;
    const timePart = d.toLocaleTimeString('en-US', {hour:'numeric', minute:'2-digit', hour12:true});
    return datePart + ', ' + timePart;
  };

  let billPage    = { limit: 20, offset: 0, total: 0, search: '', status: '' };
  const today = new Date();
  const weekAgo = new Date();
  weekAgo.setDate(today.getDate() - 7);
  let historyPage  = { limit: 20, offset: 0, total: 0, search: '', method: '', date_from: weekAgo.toISOString().split('T')[0], date_to: today.toISOString().split('T')[0] };
  let paymentMethods = [];
  let currentBill = null;

  document.addEventListener('DOMContentLoaded', () => {
    loadSummary();
    loadUnpaidReservations();
    loadPaymentMethods();
    loadPaymentHistory();
    const dateFromInput = document.querySelector('input[onchange="historyDateFrom(this.value)"]');
    const dateToInput   = document.querySelector('input[onchange="historyDateTo(this.value)"]');
    if (dateFromInput) dateFromInput.value = historyPage.date_from;
    if (dateToInput)   dateToInput.value   = historyPage.date_to;
  });

  async function loadSummary() {
    try {
      const res = await fetch('../api/billing.php?action=unpaid_summary');
      const r   = await res.json();
      if (!r.success) return;
      const d = r.data;
      const fmt = v => '₱' + parseFloat(v || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
      document.getElementById('sc-outstanding').textContent  = fmt(d.total_outstanding);
      document.getElementById('sc-unpaid-count').textContent = d.unpaid_count + ' reservations';
      document.getElementById('sc-food').textContent         = fmt(d.unpaid_food_total);
      document.getElementById('sc-food-count').textContent   = d.unpaid_food_count + ' orders';
      document.getElementById('sc-extra').textContent        = fmt(d.extra_charges_total);
      const total = parseFloat(d.total_outstanding || 0) + parseFloat(d.unpaid_food_total || 0);
      document.getElementById('sc-total').textContent        = fmt(total);
    } catch(e) { console.error('summary error', e); }
  }

  async function loadPaymentMethods() {
    try {
      const res = await fetch('../api/billing.php?action=payment_methods');
      const r   = await res.json();
      if (r.success) {
      paymentMethods = r.data;
      const sel = document.getElementById('histMethodFilter');
      if (sel) {
        paymentMethods.forEach(m => {
          const o = document.createElement('option');
          o.value = m.method_code; o.textContent = m.method_label;
          sel.appendChild(o);
        });
      }
    }
    } catch(e) {}
  }

  async function loadUnpaidReservations(reset = false) {
    if (reset) { billPage.offset = 0; }
    const { limit, offset, search, status } = billPage;
    const params = new URLSearchParams({ action: 'unpaid_reservations', limit, offset });
    if (search) params.set('search', search);
    if (status) params.set('status', status);

    document.getElementById('billTableBody').innerHTML = `<tr><td colspan="8" style="text-align:center;padding:32px;color:#999"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>`;

    try {
      const res = await fetch('../api/billing.php?' + params);
      const r   = await res.json();
      if (!r.success) return;
      billPage.total = r.total;
      renderBillTable(r.data);
      renderBillPagination();
    } catch(e) { console.error('table error', e); }
  }

  function renderBillTable(rows) {
    const tbody = document.getElementById('billTableBody');
    if (!rows || rows.length === 0) {
      tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;padding:48px;color:#999"><i class="fas fa-check-circle" style="font-size:28px;display:block;margin-bottom:10px;color:#ccc"></i>No outstanding bills found</td></tr>`;
      return;
    }
    const fmt = v => '₱' + parseFloat(v || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    tbody.innerHTML = rows.map(r => {
      const statusBadge = `<span class="status-badge ${r.reservation_status}">${(r.reservation_status || '').replace(/_/g,' ')}</span>`;
      const payBadge    = `<span class="status-badge ${r.payment_status}">${(r.payment_status || '').replace(/_/g,' ')}</span>`;
      const foodBadge   = parseFloat(r.unpaid_food || 0) > 0 ? `<span style="font-size:11px;color:#E65100;display:block">+${fmt(r.unpaid_food)} food</span>` : '';
      const extraBadge  = parseFloat(r.extra_charges || 0) > 0 ? `<span style="font-size:11px;color:#7B1FA2;display:block">+${fmt(r.extra_charges)} extra</span>` : '';
      return `<tr>
        <td><strong>${r.reservation_code || '—'}</strong></td>
        <td>${r.guest_name || '—'}</td>
        <td><div>${r.property_name || '—'}</div><div style="font-size:11px;color:#666">${r.room_name || ''} ${r.room_number ? '#'+r.room_number : ''}</div></td>
        <td>${fmtDate(r.check_in_date + (r.check_in_time ? ' ' + r.check_in_time : ''), !!r.check_in_time)}</td>
        <td>${fmtDate(r.check_out_date + (r.check_out_time ? ' ' + r.check_out_time : ''), !!r.check_out_time)}</td>
        <td>${statusBadge}</td>
        <td>${payBadge}</td>
        <td style="text-align:right">
          <strong style="color:#C62828;font-size:13px">${fmt(r.balance_amount)}</strong>
          ${foodBadge}${extraBadge}
        </td>
        <td style="text-align:center;white-space:nowrap">
          <button class="act-btn act-view" onclick="openBillDetail('${r.reservation_id}')" title="View Bill"><i class="fas fa-eye"></i> View</button>
          <button class="act-btn act-print" onclick="quickPrint('${r.reservation_id}')" title="Print Bill"><i class="fas fa-print"></i></button>
        </td>
      </tr>`;
    }).join('');
  }

  function renderBillPagination() {
    const { limit, offset, total } = billPage;
    const from = total === 0 ? 0 : offset + 1;
    const to   = Math.min(offset + limit, total);
    document.getElementById('billPagInfo').textContent = `${from}–${to} of ${total}`;
    document.getElementById('billPrevBtn').disabled = offset <= 0;
    document.getElementById('billNextBtn').disabled = offset + limit >= total;
  }

  function billSearch(val) {
    billPage.search = val;
    loadUnpaidReservations(true);
  }

  function billFilterStatus(val) {
    billPage.status = val;
    loadUnpaidReservations(true);
  }

  function billPrev() {
    if (billPage.offset > 0) { billPage.offset = Math.max(0, billPage.offset - billPage.limit); loadUnpaidReservations(); }
  }

  function billNext() {
    if (billPage.offset + billPage.limit < billPage.total) { billPage.offset += billPage.limit; loadUnpaidReservations(); }
  }

  // ── Bill Detail Modal ──────────────────────────────────────────
  async function openBillDetail(reservationId) {
    currentBill = null;
    const modal = document.getElementById('billDetailModal');
    modal.classList.add('open');
    document.getElementById('billDetailBody').innerHTML = `<div style="text-align:center;padding:48px;color:#999"><i class="fas fa-spinner fa-spin fa-2x"></i></div>`;
    document.getElementById('billDetailFooter').innerHTML = '';

    try {
      const res = await fetch(`../api/billing.php?action=reservation_bill&reservation_id=${reservationId}`);
      const r   = await res.json();
      if (!r.success) { document.getElementById('billDetailBody').innerHTML = `<p style="color:red">Failed to load bill.</p>`; return; }
      currentBill = r.data;
      renderBillDetail(r.data);
    } catch(e) {
      document.getElementById('billDetailBody').innerHTML = `<p style="color:red">Error loading bill.</p>`;
    }
  }

  function closeBillDetail() {
    document.getElementById('billDetailModal').classList.remove('open');
    currentBill = null;
  }

  function renderBillDetail(data) {
    const { reservation: res, extra_charges, food_orders, payments } = data;
    const fmt = v => '₱' + parseFloat(v || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    const paidBadge   = `<span style="font-size:10px;padding:1px 6px;border-radius:9999px;background:#E8F5E9;color:#2E7D32;font-weight:600;margin-left:6px">Paid</span>`;
    const unpaidBadge = `<span style="font-size:10px;padding:1px 6px;border-radius:9999px;background:#FFEBEE;color:#C62828;font-weight:600;margin-left:6px">Unpaid</span>`;

    let html = `
      <div class="bill-header">
        <div>
          <div class="bill-title">${res.reservation_code}</div>
          <div class="bill-sub">${res.guest_name || '—'} &nbsp;·&nbsp; ${res.property_name || ''} ${res.room_name ? '· Rm ' + (res.room_number || res.room_name) : ''}</div>
          <div class="bill-sub">Check-in: <strong>${fmtDate(res.check_in_date + (res.check_in_time ? ' ' + res.check_in_time : ''), !!res.check_in_time)}</strong> &nbsp; Check-out: <strong>${fmtDate(res.check_out_date + (res.check_out_time ? ' ' + res.check_out_time : ''), !!res.check_out_time)}</strong></div>
        </div>
        <div style="text-align:right">
          <span class="status-badge ${res.reservation_status}">${(res.reservation_status||'').replace(/_/g,' ')}</span><br>
          <span class="status-badge ${res.payment_status}" style="margin-top:4px;display:inline-flex">${(res.payment_status||'').replace(/_/g,' ')}</span>
        </div>
      </div>

      <table class="bill-table">
        <thead><tr><th>Description</th><th class="r">Amount</th></tr></thead>
        <tbody>
          <tr>
            <td>Room / Reservation Charge ${res.payment_status === 'paid' ? paidBadge : unpaidBadge}</td>
            <td class="r">${fmt(res.total_amount)}</td>
          </tr>`;

    extra_charges.forEach(ec => {
      const settled = ec.payment_status === 'paid' || ec.payment_status === 'waived';
      const badge   = settled ? paidBadge : unpaidBadge;
      const typeTag = `<span style="font-size:11px;color:#7B1FA2;text-transform:uppercase;margin-right:4px">${ec.charge_type}</span>`;
      html += `<tr style="${settled ? 'color:#888' : ''}">
        <td>${typeTag}${ec.description}${badge}</td>
        <td class="r" style="${settled ? 'text-decoration:line-through;color:#aaa' : ''}">${fmt(ec.amount)}</td>
      </tr>`;
    });

    food_orders.forEach(fo => {
      const settled = fo.payment_status === 'paid';
      const badge   = settled ? paidBadge : unpaidBadge;
      html += `<tr style="${settled ? 'color:#888' : ''}">
        <td><span style="font-size:11px;color:#E65100;text-transform:uppercase;margin-right:4px">Food</span>#${fo.order_number || fo.order_id.substring(0,8)}${badge}<br>
          <span style="font-size:11px;color:#999">${fo.items_summary || ''}</span></td>
        <td class="r" style="${settled ? 'text-decoration:line-through;color:#aaa' : ''}">${fmt(fo.total_amount)}</td>
      </tr>`;
    });

    const totalCharges = parseFloat(res.total_amount || 0)
      + extra_charges.reduce((s, e) => s + parseFloat(e.amount || 0), 0)
      + food_orders.reduce((s, f) => s + parseFloat(f.total_amount || 0), 0);
    const totalPaid = payments.reduce((s, p) => s + parseFloat(p.amount || 0), 0);
    const balance   = Math.max(0, totalCharges - totalPaid);

    html += `</tbody>
        <tfoot>
          <tr class="subtotal-row"><td>Total Charges</td><td class="r">${fmt(totalCharges)}</td></tr>`;

    if (payments.length > 0) {
      html += `<tr><td colspan="2" style="padding-top:8px;font-size:11px;font-weight:600;color:#666;text-transform:uppercase;letter-spacing:.04em">Payments Received</td></tr>`;
      payments.forEach(p => {
        const txType = p.transaction_type === 'refund'
          ? `<span style="font-size:10px;padding:1px 6px;border-radius:9999px;background:#EDE7F6;color:#6A1B9A;font-weight:600;margin-left:6px">Refund</span>`
          : '';
        html += `<tr style="color:#2E7D32">
          <td style="padding-left:12px">
            ${fmtDate(p.payment_date + (p.payment_time ? ' ' + p.payment_time : ''), true)}
            &nbsp;<strong>${p.method_label || ''}</strong>
            ${p.reference_number ? `<span style="color:#888"> · Ref: ${p.reference_number}</span>` : ''}
            ${txType}
          </td>
          <td class="r">− ${fmt(p.amount)}</td>
        </tr>`;
      });
      html += `<tr style="color:#2E7D32;font-weight:600"><td style="padding-left:12px">Total Paid</td><td class="r">− ${fmt(totalPaid)}</td></tr>`;
    }

    html += `<tr class="balance-row"><td>Balance Due</td><td class="r" style="color:${balance > 0 ? '#C62828' : '#2E7D32'}">${fmt(balance)}</td></tr>
        </tfoot>
      </table>`;

    const unpaidFoodOrders = food_orders.filter(f => f.payment_status !== 'paid');
    if (unpaidFoodOrders.length > 0) {
      html += `<div style="margin-top:16px;padding:10px 12px;background:#FFF3E0;border-radius:6px;font-size:12px;color:#E65100">
        <i class="fas fa-utensils"></i> ${unpaidFoodOrders.length} unpaid food order(s) will be marked as paid when payment is recorded.
      </div>`;
    }

    document.getElementById('billDetailBody').innerHTML = html;

    // Footer
    const footer = document.getElementById('billDetailFooter');
    const printBtn = `<button class="btn" onclick="printBill(currentBill)"><i class="fas fa-print"></i> Print</button>`;
    if (balance > 0) {
      footer.innerHTML = `
        <button class="btn btn-secondary" onclick="closeBillDetail()">Close</button>
        ${printBtn}
        <button class="btn btn-primary" onclick="openPaymentForm()"><i class="fas fa-money-bill-wave"></i> Record Payment</button>`;
    } else {
      footer.innerHTML = `<span style="font-size:13px;color:#2E7D32"><i class="fas fa-check-circle"></i> Fully paid</span>
        ${printBtn}
        <button class="btn btn-secondary" onclick="closeBillDetail()">Close</button>`;
    }
  }

  // ── Payment Form ───────────────────────────────────────────────
  function openPaymentForm() {
    if (!currentBill) return;
    const res           = currentBill.reservation;
    const extra_charges = currentBill.extra_charges || [];
    const food_orders   = currentBill.food_orders || [];
    const payments      = currentBill.payments || [];
    const totalCharges  = parseFloat(res.total_amount || 0)
      + extra_charges.reduce((s, e) => s + parseFloat(e.amount || 0), 0)
      + food_orders.reduce((s, f) => s + parseFloat(f.total_amount || 0), 0);
    const totalPaid     = payments.reduce((s, p) => s + parseFloat(p.amount || 0), 0);
    const balance       = Math.max(0, totalCharges - totalPaid);
    const fmt           = v => parseFloat(v || 0).toFixed(2);
    const fmtPeso       = v => '₱' + parseFloat(v || 0).toLocaleString('en-PH', {minimumFractionDigits:2, maximumFractionDigits:2});

    const fallbackMethods = [
      {method_code:'cash', method_label:'Cash', method_type:'cash'},
      {method_code:'gcash', method_label:'GCash', method_type:'digital_wallet'},
      {method_code:'maya', method_label:'Maya', method_type:'digital_wallet'},
      {method_code:'bank_transfer', method_label:'Bank Transfer', method_type:'bank_transfer'},
    ];
    const methods = paymentMethods.length ? paymentMethods : fallbackMethods;
    const methodOptions = methods.map(m => `<option value="${m.method_code}" data-type="${m.method_type || 'other'}">${m.method_label}</option>`).join('');
    const foodIds = (currentBill.food_orders || []).filter(f => f.payment_status !== 'paid').map(f => f.order_id);

    document.getElementById('billDetailBody').innerHTML = `
      <div style="margin-bottom:16px;padding:14px 16px;background:#FFF8E1;border-radius:8px;border:1px solid #FFE082;display:flex;justify-content:space-between;align-items:center">
        <div>
          <div style="font-size:12px;color:#888">Recording payment for</div>
          <div style="font-size:15px;font-weight:700">${res.reservation_code} &mdash; ${res.guest_name || '—'}</div>
        </div>
        <div style="text-align:right">
          <div style="font-size:11px;color:#888">Balance Due</div>
          <div style="font-size:20px;font-weight:800;color:#C62828">${fmtPeso(balance)}</div>
        </div>
      </div>

      <form id="paymentForm" autocomplete="off">
        <input type="hidden" id="pfReservationId" value="${res.reservation_id}">
        <input type="hidden" id="pfFoodOrderIds" value='${JSON.stringify(foodIds)}'>
        <input type="hidden" id="pfBalance" value="${fmt(balance)}">

        <div class="form-row">
          <div class="form-group">
            <label>Payment Method <span style="color:red">*</span></label>
            <select id="pfMethod" onchange="onPayMethodChange()" style="font-weight:600">${methodOptions}</select>
          </div>
          <div class="form-group">
            <label>Amount Tendered <span style="color:red">*</span></label>
            <input type="number" id="pfAmount" step="0.01" min="0.01" value="${fmt(balance)}"
              oninput="onPayAmountChange()" style="font-size:16px;font-weight:700">
          </div>
        </div>

        <!-- Cash: change calculator -->
        <div id="pfCashSection" style="display:none;margin-bottom:14px">
          <div style="padding:12px 14px;background:#E8F5E9;border-radius:8px;border:1px solid #A5D6A7">
            <div style="display:flex;justify-content:space-between;font-size:13px;color:#555;margin-bottom:4px">
              <span>Balance Due</span><span>${fmtPeso(balance)}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13px;color:#555;margin-bottom:4px">
              <span>Amount Tendered</span><span id="pfTenderedDisplay">${fmtPeso(balance)}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:15px;font-weight:700;color:#2E7D32;border-top:1px solid #A5D6A7;padding-top:6px;margin-top:4px">
              <span>Change</span><span id="pfChangeDisplay">${fmtPeso(0)}</span>
            </div>
          </div>
        </div>

        <!-- Digital wallet / GCash / Maya -->
        <div id="pfDigitalSection" style="display:none;margin-bottom:14px">
          <div style="padding:10px 14px;background:#E3F2FD;border-radius:8px;border:1px solid #90CAF9;font-size:12px;color:#1565C0">
            <i class="fas fa-mobile-alt"></i> Ask the guest to send to the registered account number and provide the transaction screenshot or reference number.
          </div>
        </div>

        <!-- Bank transfer -->
        <div id="pfBankSection" style="display:none;margin-bottom:14px">
          <div style="padding:10px 14px;background:#F3E5F5;border-radius:8px;border:1px solid #CE93D8;font-size:12px;color:#6A1B9A">
            <i class="fas fa-university"></i> Ensure bank deposit slip or transfer confirmation is collected before confirming.
          </div>
        </div>

        <!-- Card -->
        <div id="pfCardSection" style="display:none;margin-bottom:14px">
          <div style="padding:10px 14px;background:#FFF3E0;border-radius:8px;border:1px solid #FFCC80;font-size:12px;color:#E65100">
            <i class="fas fa-credit-card"></i> Process the card payment on the terminal first, then enter the approval code below as the reference number.
          </div>
        </div>

        <!-- Check -->
        <div id="pfCheckSection" style="display:none;margin-bottom:14px">
          <div style="padding:10px 14px;background:#ECEFF1;border-radius:8px;border:1px solid #B0BEC5;font-size:12px;color:#37474F">
            <i class="fas fa-money-check"></i> Record the check number and the issuing bank in the reference field below.
          </div>
        </div>

        <div class="form-row">
          <div class="form-group" id="pfReferenceGroup">
            <label id="pfReferenceLabel">Reference / OR Number</label>
            <input type="text" id="pfReference" placeholder="Optional">
          </div>
          <div class="form-group">
            <label>Notes</label>
            <input type="text" id="pfNotes" placeholder="Optional">
          </div>
        </div>
      </form>`;

    document.getElementById('billDetailFooter').innerHTML = `
      <button class="btn btn-secondary" onclick="renderBillDetail(currentBill)">&#8592; Back</button>
      <button class="btn btn-primary" onclick="submitPayment()"><i class="fas fa-check"></i> Confirm Payment</button>`;

    onPayMethodChange();
  }

  function onPayMethodChange() {
    const sel      = document.getElementById('pfMethod');
    const type     = sel.options[sel.selectedIndex]?.dataset.type || 'other';
    const sections = ['pfCashSection','pfDigitalSection','pfBankSection','pfCardSection','pfCheckSection'];
    sections.forEach(id => { const el = document.getElementById(id); if (el) el.style.display = 'none'; });

    const refLabel = document.getElementById('pfReferenceLabel');
    if (type === 'cash') {
      document.getElementById('pfCashSection').style.display = 'block';
      if (refLabel) refLabel.textContent = 'OR Number';
      onPayAmountChange();
    } else if (type === 'digital_wallet') {
      document.getElementById('pfDigitalSection').style.display = 'block';
      if (refLabel) refLabel.textContent = 'Transaction / Reference Number';
    } else if (type === 'bank_transfer') {
      document.getElementById('pfBankSection').style.display = 'block';
      if (refLabel) refLabel.textContent = 'Transfer / Deposit Reference';
    } else if (type === 'card') {
      document.getElementById('pfCardSection').style.display = 'block';
      if (refLabel) refLabel.textContent = 'Approval Code';
    } else if (type === 'check') {
      document.getElementById('pfCheckSection').style.display = 'block';
      if (refLabel) refLabel.textContent = 'Check Number';
    } else {
      if (refLabel) refLabel.textContent = 'Reference / OR Number';
    }
  }

  function onPayAmountChange() {
    const balance  = parseFloat(document.getElementById('pfBalance')?.value || 0);
    const tendered = parseFloat(document.getElementById('pfAmount')?.value || 0);
    const change   = Math.max(0, tendered - balance);
    const fmtPeso  = v => '₱' + parseFloat(v || 0).toLocaleString('en-PH', {minimumFractionDigits:2, maximumFractionDigits:2});
    const tEl = document.getElementById('pfTenderedDisplay');
    const cEl = document.getElementById('pfChangeDisplay');
    if (tEl) tEl.textContent = fmtPeso(tendered);
    if (cEl) { cEl.textContent = fmtPeso(change); cEl.style.color = change > 0 ? '#C62828' : '#2E7D32'; }
  }

  async function submitPayment() {
    const reservationId = document.getElementById('pfReservationId').value;
    const amount        = parseFloat(document.getElementById('pfAmount').value);
    const methodCode    = document.getElementById('pfMethod').value;
    const reference     = document.getElementById('pfReference').value.trim();
    const notes         = document.getElementById('pfNotes').value.trim();
    const foodOrderIds  = JSON.parse(document.getElementById('pfFoodOrderIds').value || '[]');

    if (!amount || amount <= 0) { alert('Please enter a valid amount.'); return; }

    const btn = document.querySelector('#billDetailFooter .btn-primary');
    btn.disabled = true;
    btn.textContent = 'Processing...';

    try {
      const res = await fetch('../api/billing.php?action=record_payment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': CSRF },
        body: JSON.stringify({ reservation_id: reservationId, amount, payment_method_code: methodCode, reference_number: reference || null, notes: notes || null, food_order_ids: foodOrderIds })
      });
      const r = await res.json();
      if (r.success) {
        closeBillDetail();
        loadSummary();
        loadUnpaidReservations();
        loadPaymentHistory(true);
        showToast('Payment recorded successfully!', 'success');
      } else {
        alert('Error: ' + (r.message || 'Failed to record payment'));
        btn.disabled = false;
        btn.textContent = 'Confirm Payment';
      }
    } catch(e) {
      alert('Network error. Please try again.');
      btn.disabled = false;
      btn.textContent = 'Confirm Payment';
    }
  }

  // ── Print ─────────────────────────────────────────────────────
  async function quickPrint(reservationId) {
    try {
      const res = await fetch(`../api/billing.php?action=reservation_bill&reservation_id=${reservationId}`);
      const r   = await res.json();
      if (r.success) printBill(r.data);
    } catch(e) { alert('Could not load bill for printing.'); }
  }

  function printBill(data) {
    const { reservation: res, extra_charges, food_orders, payments } = data;
    const fmt  = v => '₱' + parseFloat(v || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    const fmtN = v => parseFloat(v || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    const now  = fmtDate(new Date().toISOString(), true);

    const totalCharges = parseFloat(res.total_amount || 0)
      + extra_charges.reduce((s, e) => s + parseFloat(e.amount || 0), 0)
      + food_orders.reduce((s, f) => s + parseFloat(f.total_amount || 0), 0);
    const totalPaid = payments.reduce((s, p) => s + parseFloat(p.amount || 0), 0);
    const balance   = parseFloat(res.balance_amount || 0);

    let ecRows = extra_charges.map(ec => `
      <tr>
        <td>${ec.charge_type ? '<span class="tag ec">' + ec.charge_type + '</span> ' : ''}${ec.description || ''}</td>
        <td class="r">${fmtN(ec.amount)}</td>
      </tr>`).join('');

    let foRows = food_orders.map(fo => `
      <tr>
        <td><span class="tag fo">Food</span> Order #${fo.order_number || fo.order_id.substring(0,8)}<br><small>${fo.items_summary || ''}</small></td>
        <td class="r">${fmtN(fo.total_amount)}</td>
      </tr>`).join('');

    let pyRows = payments.length ? payments.map(p => `
      <tr class="pay-row">
        <td>${fmtDate(p.payment_date + (p.payment_time ? ' ' + p.payment_time : ''), true)} &nbsp; ${p.method_label || ''} ${p.reference_number ? '· Ref: ' + p.reference_number : ''}</td>
        <td class="r">− ${fmtN(p.amount)}</td>
      </tr>`).join('') : '';

    const html = `<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Bill — ${res.reservation_code}</title>
<style>
  @media print { @page { margin: 18mm 14mm; } }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; color: #1a1a1a; margin: 0; padding: 24px; }
  .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 2px solid #1a1a1a; padding-bottom: 14px; }
  .prop-name { font-size: 18px; font-weight: 700; }
  .prop-sub { font-size: 11px; color: #666; margin-top: 2px; }
  .bill-code { font-size: 22px; font-weight: 700; text-align: right; }
  .bill-date { font-size: 11px; color: #666; text-align: right; }
  .guest-box { display: flex; gap: 32px; margin-bottom: 18px; padding: 12px 14px; background: #f9f9f9; border-radius: 6px; }
  .guest-box div { flex: 1; }
  .lbl { font-size: 10px; text-transform: uppercase; letter-spacing: .05em; color: #888; margin-bottom: 3px; }
  .val { font-size: 12px; font-weight: 600; }
  table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
  th { font-size: 10px; text-transform: uppercase; letter-spacing: .05em; color: #666; border-bottom: 2px solid #ddd; padding: 7px 10px; text-align: left; }
  td { padding: 7px 10px; border-bottom: 1px solid #f0f0f0; vertical-align: top; }
  .r { text-align: right; }
  .tag { display: inline-block; padding: 1px 6px; border-radius: 3px; font-size: 9px; font-weight: 600; text-transform: uppercase; margin-right: 4px; }
  .tag.ec { background: #F3E5F5; color: #7B1FA2; }
  .tag.fo { background: #FFF3E0; color: #E65100; }
  small { font-size: 10px; color: #999; }
  tfoot tr td { font-weight: 600; padding: 8px 10px; }
  .subtotal td { border-top: 2px solid #ddd; border-bottom: none; }
  .pay-row td { color: #2E7D32; }
  .balance td { border-top: 2px solid #1a1a1a; font-size: 14px; font-weight: 700; }
  .balance .r { color: ${balance > 0 ? '#C62828' : '#2E7D32'}; }
  .footer-note { margin-top: 32px; font-size: 10px; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 12px; }
  .no-print { margin-bottom: 16px; }
  @media print { .no-print { display: none; } }
</style>
</head>
<body>
<div class="no-print">
  <button onclick="window.print()" style="padding:8px 20px;background:#185FA5;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px;">🖨 Print</button>
  <button onclick="window.close()" style="padding:8px 16px;background:#fff;border:1px solid #ccc;border-radius:6px;cursor:pointer;font-size:13px;margin-left:8px;">✕ Close</button>
</div>

<div class="header">
  <div>
    <div class="prop-name">${res.property_name || 'MM Paraiso'}</div>
    <div class="prop-sub">Billing Statement</div>
  </div>
  <div>
    <div class="bill-code">${res.reservation_code}</div>
    <div class="bill-date">Printed: ${now}</div>
  </div>
</div>

<div class="guest-box">
  <div><div class="lbl">Guest</div><div class="val">${res.guest_name || '—'}</div></div>
  <div><div class="lbl">Room</div><div class="val">${res.room_name || '—'} ${res.room_number ? '#' + res.room_number : ''}</div></div>
  <div><div class="lbl">Check-in</div><div class="val">${fmtDate(res.check_in_date + (res.check_in_time ? ' ' + res.check_in_time : ''), !!res.check_in_time)}</div></div>
  <div><div class="lbl">Check-out</div><div class="val">${fmtDate(res.check_out_date + (res.check_out_time ? ' ' + res.check_out_time : ''), !!res.check_out_time)}</div></div>
</div>

<table>
  <thead><tr><th>Description</th><th class="r">Amount (₱)</th></tr></thead>
  <tbody>
    <tr><td>Room / Reservation Charge</td><td class="r">${fmtN(res.total_amount)}</td></tr>
    ${ecRows}
    ${foRows}
  </tbody>
  <tfoot>
    <tr class="subtotal"><td>Total Charges</td><td class="r">${fmtN(totalCharges)}</td></tr>
    ${pyRows ? '<tr><td colspan="2" style="font-size:10px;text-transform:uppercase;letter-spacing:.05em;color:#888;padding-top:10px">Payments Received</td></tr>' + pyRows : ''}
    <tr class="balance"><td>Balance Due</td><td class="r">${fmtN(balance)}</td></tr>
  </tfoot>
</table>

<div class="footer-note">This is an official billing statement. Thank you for your stay.</div>
</body>
</html>`;

    const blob = new Blob([html], { type: 'text/html' });
    const url  = URL.createObjectURL(blob);
    const w    = window.open(url, '_blank', 'width=780,height=900,scrollbars=yes');
    w.addEventListener('unload', () => URL.revokeObjectURL(url));
  }

  // ── Payment History ───────────────────────────────────────────
  async function loadPaymentHistory(reset = false) {
    if (reset) historyPage.offset = 0;
    const { limit, offset, search, method, date_from, date_to } = historyPage;
    const params = new URLSearchParams({ action: 'payment_history', limit, offset });
    if (search)    params.set('search', search);
    if (method)    params.set('method', method);
    if (date_from) params.set('date_from', date_from);
    if (date_to)   params.set('date_to', date_to);

    document.getElementById('historyTableBody').innerHTML =
      `<tr><td colspan="7" style="text-align:center;padding:32px;color:#999"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>`;

    try {
      const res = await fetch('../api/billing.php?' + params);
      const r   = await res.json();
      if (!r.success) return;
      historyPage.total = r.total;
      renderHistoryTable(r.data);
      renderHistoryPagination();
    } catch(e) { console.error('history error', e); }
  }

  function renderHistoryTable(rows) {
    const tbody = document.getElementById('historyTableBody');
    if (!rows || rows.length === 0) {
      tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:48px;color:#999"><i class="fas fa-inbox" style="font-size:28px;display:block;margin-bottom:10px;color:#ccc"></i>No payment records found</td></tr>`;
      return;
    }
    const fmt = v => '₱' + parseFloat(v || 0).toLocaleString('en-PH', {minimumFractionDigits:2, maximumFractionDigits:2});
    const methodIcons = { cash:'fa-money-bill-wave', digital_wallet:'fa-mobile-alt', bank_transfer:'fa-university', card:'fa-credit-card', check:'fa-money-check' };
    tbody.innerHTML = rows.map(p => {
      const icon   = methodIcons[p.method_type] || 'fa-circle';
      const typeBadge = p.transaction_type === 'refund'
        ? `<span class="status-badge refund">Refund</span>`
        : `<span class="status-badge paid">Payment</span>`;
      const ref = p.reference_number ? `<div style="font-size:11px;color:#888;margin-top:2px">Ref: ${p.reference_number}</div>` : '';
      const by  = p.recorded_by_name?.trim() ? `<div style="font-size:11px;color:#aaa">by ${p.recorded_by_name}</div>` : '';
      return `<tr>
        <td>${fmtDate(p.payment_date + (p.payment_time ? ' ' + p.payment_time : ''), !!p.payment_time)}</td>
        <td><strong>${p.reservation_code || '—'}</strong><div style="font-size:11px;color:#666">${p.guest_name || ''}</div></td>
        <td>${p.property_name || '—'}</td>
        <td><i class="fas ${icon}" style="margin-right:5px;color:#185FA5"></i>${p.method_label || '—'}${ref}</td>
        <td>${typeBadge}</td>
        <td style="text-align:right;font-weight:700;color:#2E7D32">${fmt(p.amount)}${by}</td>
        <td style="text-align:center">
          <button class="act-btn act-view" onclick="openBillDetail('${p.reservation_id}')" title="View Bill"><i class="fas fa-eye"></i> View</button>
        </td>
      </tr>`;
    }).join('');
  }

  function renderHistoryPagination() {
    const { limit, offset, total } = historyPage;
    const from = total === 0 ? 0 : offset + 1;
    const to   = Math.min(offset + limit, total);
    document.getElementById('histPagInfo').textContent = `${from}–${to} of ${total}`;
    document.getElementById('histPrevBtn').disabled = offset <= 0;
    document.getElementById('histNextBtn').disabled = offset + limit >= total;
  }

  function historySearch(val)      { historyPage.search = val;    loadPaymentHistory(true); }
  function historyMethod(val)      { historyPage.method = val;    loadPaymentHistory(true); }
  function historyDateFrom(val)    { historyPage.date_from = val; loadPaymentHistory(true); }
  function historyDateTo(val)      { historyPage.date_to = val;   loadPaymentHistory(true); }
  function historyPrev() { if (historyPage.offset > 0) { historyPage.offset = Math.max(0, historyPage.offset - historyPage.limit); loadPaymentHistory(); } }
  function historyNext() { if (historyPage.offset + historyPage.limit < historyPage.total) { historyPage.offset += historyPage.limit; loadPaymentHistory(); } }

  // ── Tab Switching ────────────────────────────────────────────
  function switchTab(tabName) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));
    document.querySelector(`.tab-btn[data-tab="${tabName}"]`).classList.add('active');
    document.getElementById(`tab-${tabName}`).classList.add('active');
    // Refresh data when switching to ensure fresh view
    if (tabName === 'outstanding') {
      loadSummary();
      loadUnpaidReservations();
    } else if (tabName === 'history') {
      loadPaymentHistory();
    }
  }

  function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.style.cssText = `position:fixed;bottom:24px;right:24px;z-index:99999;padding:12px 20px;border-radius:8px;font-size:13px;font-weight:500;color:#fff;background:${type==='success'?'#2E7D32':'#C62828'};box-shadow:0 4px 12px rgba(0,0,0,.2);transition:opacity .3s`;
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3000);
  }
</script>

<style>
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'DM Sans',system-ui,sans-serif;background:#f5f5f5;color:#1a1a1a}
  .page{padding:24px;max-width:100%}

  /* Summary cards */
  .summary-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px}
  @media(max-width:1024px){.summary-grid{grid-template-columns:repeat(2,1fr)}}
  @media(max-width:480px){.summary-grid{grid-template-columns:1fr}}
  .sum-card{background:#fff;border:1px solid #e0e0e0;border-radius:10px;padding:18px 20px}
  .sum-card .sc-icon{width:44px;height:44px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:18px;margin-bottom:12px}
  .sum-card .sc-label{font-size:11px;text-transform:uppercase;letter-spacing:.05em;color:#888;margin-bottom:6px}
  .sum-card .sc-value{font-family:'Lora',Georgia,serif;font-size:22px;font-weight:600}
  .sum-card .sc-sub{font-size:12px;color:#666;margin-top:4px}
  .sc-red .sc-icon{background:#FFEBEE;color:#C62828}
  .sc-orange .sc-icon{background:#FFF3E0;color:#E65100}
  .sc-purple .sc-icon{background:#F3E5F5;color:#7B1FA2}
  .sc-green .sc-icon{background:#E8F5E9;color:#2E7D32}

  /* Card */
  .card{background:#fff;border:1px solid #e0e0e0;border-radius:10px}
  .card-header{padding:16px 20px;border-bottom:1px solid #e0e0e0;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
  .card-title{font-family:'Lora',Georgia,serif;font-size:16px;font-weight:600;font-style:italic}
  .card-body{padding:0}
  .toolbar{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
  .search-wrap{position:relative}
  .search-wrap i{position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#999;font-size:13px;pointer-events:none}
  .search-input{padding:7px 12px 7px 32px;font-size:13px;border:1px solid #ccc;border-radius:6px;width:220px;outline:none}
  .search-input:focus{border-color:#185FA5}
  .filter-select{padding:7px 12px;font-size:13px;border:1px solid #ccc;border-radius:6px;background:#fff;outline:none}

  /* Buttons */
  .btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;font-size:13px;font-weight:500;border-radius:6px;border:1px solid #ccc;background:#fff;color:#1a1a1a;cursor:pointer;transition:background .15s}
  .btn:hover{background:#f5f5f5}
  .btn:disabled{opacity:.5;cursor:not-allowed}
  .btn-primary{background:#185FA5;color:#fff;border-color:#185FA5}
  .btn-primary:hover{background:#0C447C;border-color:#0C447C}
  .btn-secondary{background:#fff;color:#444;border-color:#ccc}

  /* Action buttons in table */
  .act-btn{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;font-size:12px;font-weight:500;border-radius:5px;border:1px solid #ccc;background:#fff;cursor:pointer;transition:background .12s}
  .act-btn:hover{background:#f5f5f5}
  .act-view{color:#185FA5;border-color:#185FA5}
  .act-view:hover{background:#EFF6FF}
  .act-print{color:#555;margin-left:4px}
  .act-print:hover{background:#f0f0f0}

  /* Table */
  .table-wrap{overflow-x:auto}
  table{width:100%;border-collapse:collapse;font-size:13px}
  thead th{padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:#666;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e0e0e0;white-space:nowrap}
  th.r,td.r{text-align:right}
  tbody tr{border-bottom:1px solid #e0e0e0;transition:background .1s}
  tbody tr:last-child{border-bottom:none}
  tbody tr:hover{background:#f9f9f9}
  td{padding:11px 16px;vertical-align:middle}

  /* Status badges */
  .status-badge{display:inline-flex;align-items:center;padding:3px 9px;border-radius:9999px;font-size:11px;font-weight:500;text-transform:capitalize;white-space:nowrap}
  .status-badge.pending{background:#FFF3E0;color:#E65100}
  .status-badge.confirmed{background:#E8F5E9;color:#2E7D32}
  .status-badge.checked_in{background:#E3F2FD;color:#1565C0}
  .status-badge.checked_out{background:#F5F5F5;color:#666}
  .status-badge.cancelled{background:#FFEBEE;color:#C62828}
  .status-badge.unpaid{background:#FFEBEE;color:#C62828}
  .status-badge.partial{background:#FFF3E0;color:#E65100}
  .status-badge.paid{background:#E8F5E9;color:#2E7D32}
  .status-badge.refund{background:#EDE7F6;color:#6A1B9A}

  /* Tabs */
  .tabs-wrap{margin-top:8px}
  .tab-nav{display:flex;gap:4px;border-bottom:2px solid #e0e0e0;margin-bottom:-2px}
  .tab-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 20px;font-size:13px;font-weight:500;color:#666;background:#f5f5f5;border:2px solid transparent;border-bottom:none;border-radius:8px 8px 0 0;cursor:pointer;transition:all .15s;position:relative;top:2px}
  .tab-btn:hover{background:#e8e8e8;color:#444}
  .tab-btn.active{background:#fff;color:#185FA5;border-color:#e0e0e0;border-bottom-color:#fff;font-weight:600}
  .tab-btn i{font-size:14px}
  .tab-panel{display:none}
  .tab-panel.active{display:block}

  /* Pagination */
  .pagination-wrap{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid #e0e0e0}
  .pagination-info{font-size:13px;color:#666}
  .pagination-controls{display:flex;gap:8px}

  /* Modal */
  .overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9000;align-items:center;justify-content:center;padding:20px}
  .overlay.open{display:flex}
  .modal{background:#fff;border-radius:14px;border:1px solid #e0e0e0;width:100%;max-width:700px;max-height:92vh;overflow-y:auto;box-shadow:0 24px 48px rgba(0,0,0,.18);display:flex;flex-direction:column}
  .modal-header{padding:18px 24px;border-bottom:1px solid #e0e0e0;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
  .modal-title{font-family:'Lora',Georgia,serif;font-size:16px;font-weight:600;font-style:italic}
  .modal-close{background:none;border:none;cursor:pointer;color:#666;font-size:20px;line-height:1;padding:4px}
  .modal-close:hover{color:#1a1a1a}
  .modal-body{padding:24px;flex:1;overflow-y:auto}
  .modal-footer{padding:14px 24px;border-top:1px solid #e0e0e0;display:flex;justify-content:flex-end;gap:8px;flex-shrink:0}

  /* Bill detail styles */
  .bill-header{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:20px;flex-wrap:wrap}
  .bill-title{font-family:'Lora',serif;font-size:18px;font-weight:600;margin-bottom:4px}
  .bill-sub{font-size:12px;color:#666;margin-bottom:2px}
  .bill-table{width:100%;border-collapse:collapse;font-size:13px;margin-top:4px}
  .bill-table th{padding:8px 12px;font-size:11px;font-weight:600;text-transform:uppercase;color:#666;border-bottom:2px solid #e0e0e0;text-align:left}
  .bill-table td{padding:9px 12px;border-bottom:1px solid #f0f0f0;vertical-align:top}
  .bill-table tfoot td{padding:9px 12px;font-weight:600}
  .bill-table .subtotal-row td{border-top:2px solid #e0e0e0;border-bottom:none;font-weight:600}
  .bill-table .balance-row td{font-size:15px;font-weight:700;border-top:2px solid #1a1a1a}

  /* Payment form */
  .form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px}
  @media(max-width:480px){.form-row{grid-template-columns:1fr}}
  .form-group{display:flex;flex-direction:column;gap:5px}
  .form-group label{font-size:12px;font-weight:500;color:#444}
  .form-group input,.form-group select{padding:8px 10px;font-size:13px;border:1px solid #ccc;border-radius:6px;outline:none}
  .form-group input:focus,.form-group select:focus{border-color:#185FA5}
</style>

<div class="page">

  <!-- Summary Cards -->
  <div class="summary-grid">
    <div class="sum-card sc-red">
      <div class="sc-icon"><i class="fas fa-file-invoice-dollar"></i></div>
      <div class="sc-label">Total Outstanding</div>
      <div class="sc-value" id="sc-outstanding">₱0.00</div>
      <div class="sc-sub" id="sc-unpaid-count">— reservations</div>
    </div>
    <div class="sum-card sc-orange">
      <div class="sc-icon"><i class="fas fa-utensils"></i></div>
      <div class="sc-label">Unpaid Food Orders</div>
      <div class="sc-value" id="sc-food">₱0.00</div>
      <div class="sc-sub" id="sc-food-count">— orders</div>
    </div>
    <div class="sum-card sc-purple">
      <div class="sc-icon"><i class="fas fa-plus-circle"></i></div>
      <div class="sc-label">Extra Charges</div>
      <div class="sc-value" id="sc-extra">₱0.00</div>
      <div class="sc-sub">On unpaid reservations</div>
    </div>
    <div class="sum-card sc-green">
      <div class="sc-icon"><i class="fas fa-receipt"></i></div>
      <div class="sc-label">Grand Total Due</div>
      <div class="sc-value" id="sc-total">₱0.00</div>
      <div class="sc-sub">Across all charges</div>
    </div>
  </div>

  <!-- Tab Navigation -->
  <div class="tabs-wrap">
    <div class="tab-nav">
      <button class="tab-btn active" data-tab="outstanding" onclick="switchTab('outstanding')">
        <i class="fas fa-file-invoice-dollar"></i> Outstanding Bills
      </button>
      <button class="tab-btn" data-tab="history" onclick="switchTab('history')">
        <i class="fas fa-history"></i> Payment History
      </button>
    </div>

    <!-- Tab: Outstanding Bills -->
    <div class="tab-panel active" id="tab-outstanding">
      <div class="card" style="border-radius:0 8px 8px 8px">
        <div class="card-header">
          <div class="card-title">Outstanding Bills</div>
          <div class="toolbar">
            <div class="search-wrap">
              <i class="fas fa-search"></i>
              <input type="text" class="search-input" placeholder="Search guest or code…" oninput="billSearch(this.value)">
            </div>
            <select class="filter-select" onchange="billFilterStatus(this.value)">
              <option value="">All Statuses</option>
              <option value="pending">Pending</option>
              <option value="confirmed">Confirmed</option>
              <option value="checked_in">Checked In</option>
            </select>
            <button class="btn" onclick="loadSummary(); loadUnpaidReservations(true)"><i class="fas fa-sync-alt"></i> Refresh</button>
          </div>
        </div>
        <div class="card-body">
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Reservation</th>
                  <th>Guest</th>
                  <th>Property / Room</th>
                  <th>Check-in</th>
                  <th>Check-out</th>
                  <th>Status</th>
                  <th>Payment</th>
                  <th class="r">Balance Due</th>
                  <th style="text-align:center">Actions</th>
                </tr>
              </thead>
              <tbody id="billTableBody">
                <tr><td colspan="9" style="text-align:center;padding:32px;color:#999"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>
              </tbody>
            </table>
          </div>
          <div class="pagination-wrap">
            <div class="pagination-info" id="billPagInfo">—</div>
            <div class="pagination-controls">
              <button class="btn" id="billPrevBtn" onclick="billPrev()" disabled><i class="fas fa-chevron-left"></i></button>
              <button class="btn" id="billNextBtn" onclick="billNext()" disabled><i class="fas fa-chevron-right"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab: Payment History -->
    <div class="tab-panel" id="tab-history">
      <div class="card" style="border-radius:0 8px 8px 8px">
        <div class="card-header">
          <div class="card-title"><i class="fas fa-history" style="margin-right:8px;font-size:14px"></i>Payment History</div>
          <div class="toolbar">
            <div class="search-wrap">
              <i class="fas fa-search"></i>
              <input type="text" class="search-input" placeholder="Guest or reservation…" oninput="historySearch(this.value)">
            </div>
            <select class="filter-select" onchange="historyMethod(this.value)" id="histMethodFilter">
              <option value="">All Methods</option>
            </select>
            <input type="date" class="filter-select" style="width:150px" onchange="historyDateFrom(this.value)" title="From date">
            <input type="date" class="filter-select" style="width:150px" onchange="historyDateTo(this.value)" title="To date">
            <button class="btn" onclick="loadPaymentHistory(true)"><i class="fas fa-sync-alt"></i> Refresh</button>
          </div>
        </div>
        <div class="card-body">
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Date &amp; Time</th>
                  <th>Reservation / Guest</th>
                  <th>Property</th>
                  <th>Method</th>
                  <th>Type</th>
                  <th class="r">Amount</th>
                  <th style="text-align:center">Actions</th>
                </tr>
              </thead>
              <tbody id="historyTableBody">
                <tr><td colspan="7" style="text-align:center;padding:32px;color:#999"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>
              </tbody>
            </table>
          </div>
          <div class="pagination-wrap">
            <div class="pagination-info" id="histPagInfo">—</div>
            <div class="pagination-controls">
              <button class="btn" id="histPrevBtn" onclick="historyPrev()" disabled><i class="fas fa-chevron-left"></i></button>
              <button class="btn" id="histNextBtn" onclick="historyNext()" disabled><i class="fas fa-chevron-right"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Bill Detail Modal -->
<div class="overlay" id="billDetailModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Bill Details</div>
      <button class="modal-close" onclick="closeBillDetail()">&times;</button>
    </div>
    <div class="modal-body" id="billDetailBody"></div>
    <div class="modal-footer" id="billDetailFooter"></div>
  </div>
</div>
