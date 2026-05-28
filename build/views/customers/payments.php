<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=DM+Sans:wght@300;400;500&display=swap');

:root {
    --gold: #C9A96E;
    --gold-light: #E8D5B0;
    --gold-dark: #A07840;
    --ink: #1A1714;
    --ink-soft: #3D3530;
    --parchment: #FAF7F2;
    --card-bg: #FFFFFF;
    --border: rgba(201,169,110,0.2);
    --border-table: #F0EBE1;
}

.pay-wrap {
    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    padding: 0 0 2rem;
}

/* ── Page header card ── */
.header-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 2rem 2.25rem;
    margin-bottom: 1.75rem;
}

.page-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 1rem;
}

.page-eyebrow {
    font-size: 0.68rem;
    font-weight: 500;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 0.3rem;
}

.page-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.2rem;
    font-weight: 400;
    color: var(--ink);
    margin: 0;
    line-height: 1.1;
}

.page-sub {
    font-size: 0.82rem;
    color: #9CA3AF;
    margin-top: 0.3rem;
    font-weight: 300;
}

/* ── Summary strips ── */
.summary-strip {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.75rem;
}

@media (max-width: 520px) { .summary-strip { grid-template-columns: 1fr; } }

.summary-pill {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 1.4rem 1.75rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.summary-pill-icon {
    width: 42px; height: 42px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.summary-pill--paid .summary-pill-icon   { background: #ECFDF5; color: #059669; }
.summary-pill--pending .summary-pill-icon { background: #FFFBEB; color: #D97706; }

.summary-pill-body {}

.summary-pill-label {
    font-size: 0.67rem;
    font-weight: 500;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #9CA3AF;
    margin-bottom: 0.2rem;
}

.summary-pill-value {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.6rem;
    font-weight: 500;
    color: var(--ink);
    line-height: 1;
}

.currency-sym { font-size: 1rem; opacity: 0.6; margin-right: 1px; }

/* ── Table card ── */
.table-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
}

.pay-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}

.pay-table thead {
    background: #FAFAF9;
    border-bottom: 1px solid var(--border-table);
}

.pay-table thead th {
    padding: 0.9rem 1.5rem;
    font-size: 0.65rem;
    font-weight: 500;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #9CA3AF;
    text-align: left;
    white-space: nowrap;
}

.pay-table tbody tr {
    border-bottom: 1px solid var(--border-table);
    transition: background 0.15s ease;
}

.pay-table tbody tr:last-child { border-bottom: none; }
.pay-table tbody tr:hover { background: #FDFCFA; }

.pay-table tbody td {
    padding: 1rem 1.5rem;
    color: var(--ink-soft);
    vertical-align: middle;
}

.amount-cell {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.15rem;
    font-weight: 500;
    color: var(--ink);
    letter-spacing: 0.01em;
}

.amount-sym { font-size: 0.85rem; opacity: 0.55; margin-right: 1px; }

.method-cell {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.method-icon {
    width: 28px; height: 28px;
    border-radius: 7px;
    background: #F3F4F6;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.8rem;
    color: #6B7280;
    flex-shrink: 0;
}

.date-cell { display: flex; flex-direction: column; gap: 0.1rem; }
.date-main { font-weight: 500; color: var(--ink); }
.date-day  { font-size: 0.72rem; color: #9CA3AF; }

/* Status badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.85rem;
    border-radius: 50px;
    font-size: 0.72rem;
    font-weight: 500;
    letter-spacing: 0.04em;
    white-space: nowrap;
}
.status-badge::before {
    content: '';
    width: 5px; height: 5px;
    border-radius: 50%;
    background: currentColor;
    opacity: 0.7;
}
.status-paid    { background: #ECFDF5; color: #059669; }
.status-pending { background: #FFFBEB; color: #D97706; }
.status-failed  { background: #FEF2F2; color: #DC2626; }
.status-unknown { background: #F9FAFB; color: #6B7280; }

/* Empty state */
.empty-state { text-align: center; padding: 5rem 2rem; color: #9CA3AF; }
.empty-icon  { font-size: 3rem; margin-bottom: 1rem; display: block; opacity: 0.4; }
.empty-title { font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; font-weight: 400; color: var(--ink-soft); margin-bottom: 0.4rem; }
.empty-sub   { font-size: 0.82rem; }

@media (max-width: 680px) {
    .pay-table { display: block; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .page-header { flex-direction: column; align-items: flex-start; }
}
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    loadPayments();
  });

  async function loadPayments() {
    try {
      const res = await fetch('../api/payments.php?action=my_payments');
      const json = await res.json();
      
      if (json.success && json.data.length > 0) {
        renderPayments(json.data);
        updateSummary(json.data);
        document.getElementById('summaryStrip').style.display = 'grid';
      } else {
        showEmptyState();
      }
    } catch (e) {
      console.error('Error loading payments:', e);
      showEmptyState();
    }
  }

  function renderPayments(data) {
    const tbody = document.getElementById('paymentsTableBody');
    tbody.innerHTML = data.map(p => {
      const date = p.payment_date ? formatDate(p.payment_date) : { main: '—', day: '' };
      const status = p.payment_status || 'unknown';
      const method = p.method_label || p.transaction_type || '—';
      
      return `
        <tr>
          <td>
            <div class="date-cell">
              <span class="date-main">${date.main}</span>
              <span class="date-day">${date.day}</span>
            </div>
          </td>
          <td>
            <span class="amount-cell">
              <span class="amount-sym">₱</span>${parseFloat(p.amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}
            </span>
          </td>
          <td>
            <div class="method-cell">
              <div class="method-icon"><i class="${methodIcon(method)}"></i></div>
              ${escapeHtml(method)}
            </div>
          </td>
          <td>
            <span class="status-badge ${payStatusClass(status)}">
              ${capitalize(status)}
            </span>
          </td>
        </tr>
      `;
    }).join('');
  }

  function updateSummary(data) {
    let totalPaid = 0;
    let totalPending = 0;
    
    data.forEach(p => {
      const amt = parseFloat(p.amount || 0);
      if (p.payment_status === 'paid') totalPaid += amt;
      if (p.payment_status === 'pending') totalPending += amt;
    });
    
    document.getElementById('totalPaid').innerHTML = '<span class="currency-sym">₱</span>' + totalPaid.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('totalPending').innerHTML = '<span class="currency-sym">₱</span>' + totalPending.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
  }

  function showEmptyState() {
    document.getElementById('paymentsTableBody').innerHTML = '';
    document.getElementById('emptyState').style.display = 'block';
    document.getElementById('summaryStrip').style.display = 'none';
  }

  function formatDate(dateStr) {
    const date = new Date(dateStr);
    return {
      main: date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
      day: date.toLocaleDateString('en-US', { weekday: 'long' })
    };
  }

  function payStatusClass(status) {
    const classes = {
      'paid': 'status-paid',
      'pending': 'status-pending',
      'failed': 'status-failed'
    };
    return classes[status] || 'status-unknown';
  }

  function methodIcon(method) {
    const m = method.toLowerCase();
    if (m.includes('card') || m.includes('credit') || m.includes('debit')) return 'ni ni-credit-card';
    if (m.includes('cash')) return 'ni ni-money-coins';
    if (m.includes('gcash') || m.includes('maya') || m.includes('mobile')) return 'ni ni-mobile-button';
    return 'ni ni-single-02';
  }

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }
</script>

<div class="pay-wrap">

    <!-- Header -->
    <div class="header-card">
        <div class="page-header">
            <div>
                <h1 class="page-title">My Payments</h1>
                <p class="page-sub">Payment history and outstanding balances</p>
            </div>
        </div>
    </div>

    <!-- Summary strip -->
    <div class="summary-strip" id="summaryStrip" style="display: none;">
        <div class="summary-pill summary-pill--paid">
            <div class="summary-pill-icon"><i class="ni ni-check-bold"></i></div>
            <div class="summary-pill-body">
                <p class="summary-pill-label">Total Paid</p>
                <div class="summary-pill-value" id="totalPaid">₱0.00</div>
            </div>
        </div>
        <div class="summary-pill summary-pill--pending">
            <div class="summary-pill-icon"><i class="ni ni-time-alarm"></i></div>
            <div class="summary-pill-body">
                <p class="summary-pill-label">Outstanding</p>
                <div class="summary-pill-value" id="totalPending">₱0.00</div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-card">
        <div id="emptyState" class="empty-state" style="display: none;">
            <i class="ni ni-credit-card empty-icon"></i>
            <p class="empty-title">No payment records</p>
            <p class="empty-sub">Your payment history will appear here once transactions are processed.</p>
        </div>
        <div style="overflow-x:auto;">
            <table class="pay-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="paymentsTableBody">
                    <tr><td colspan="4" style="text-align: center; color: #9CA3AF;">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

</div>