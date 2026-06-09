<?php if (false) {} ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
<style>
  .pos-rpt {
    --rpt-bg-main: #f0f2f8;
    --rpt-bg-card: #ffffff;
    --rpt-text-primary: #1a1f36;
    --rpt-text-secondary: #6b7280;
    --rpt-text-muted: #9ca3af;
    --rpt-border: #e5e7eb;
    --rpt-blue: #4f46e5;
    --rpt-blue-light: #eef2ff;
    --rpt-green: #10b981;
    --rpt-green-light: #d1fae5;
    --rpt-orange: #f97316;
    --rpt-orange-light: #fff7ed;
    --rpt-red: #f43f5e;
    --rpt-red-light: #fff1f2;
    --rpt-shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --rpt-shadow-md: 0 4px 16px rgba(0,0,0,.08);
    --rpt-radius: 16px;
    --rpt-radius-sm: 10px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--rpt-text-primary);
  }

  .pos-rpt .rpt-content { display: flex; flex-direction: column; gap: 24px; }

  /* Header */
  .pos-rpt .rpt-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
  }
  .pos-rpt .rpt-title {
    font-size: 24px;
    font-weight: 800;
    color: var(--rpt-text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .pos-rpt .rpt-title i { color: var(--rpt-blue); font-size: 22px; }

  /* Filter Bar */
  .pos-rpt .filter-bar {
    background: var(--rpt-bg-card);
    border-radius: var(--rpt-radius);
    padding: 20px 24px;
    box-shadow: var(--rpt-shadow-sm);
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    align-items: flex-end;
  }
  .pos-rpt .filter-group { display: flex; flex-direction: column; gap: 5px; }
  .pos-rpt .filter-label { font-size: 11px; font-weight: 700; color: var(--rpt-text-secondary); text-transform: uppercase; letter-spacing: 0.5px; }
  .pos-rpt .filter-input {
    padding: 9px 13px;
    border: 1.5px solid var(--rpt-border);
    border-radius: var(--rpt-radius-sm);
    font-size: 13px;
    min-width: 150px;
    font-family: inherit;
    background: #fff;
    transition: border-color .2s;
  }
  .pos-rpt .filter-input:focus { outline: none; border-color: var(--rpt-blue); }
  .pos-rpt .rpt-btn {
    padding: 9px 18px;
    border-radius: var(--rpt-radius-sm);
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-family: inherit;
    transition: all .2s;
  }
  .pos-rpt .rpt-btn-primary { background: var(--rpt-blue); color: #fff; }
  .pos-rpt .rpt-btn-primary:hover { background: #4338ca; transform: translateY(-1px); }
  .pos-rpt .rpt-btn-outline { background: #fff; color: var(--rpt-text-primary); border: 1.5px solid var(--rpt-border); }
  .pos-rpt .rpt-btn-outline:hover { border-color: var(--rpt-blue); color: var(--rpt-blue); }

  /* Stat Cards */
  .pos-rpt .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
  .pos-rpt .stat-card {
    background: var(--rpt-bg-card);
    border-radius: var(--rpt-radius);
    padding: 22px;
    box-shadow: var(--rpt-shadow-sm);
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: box-shadow .2s, transform .2s;
  }
  .pos-rpt .stat-card:hover { box-shadow: var(--rpt-shadow-md); transform: translateY(-2px); }
  .pos-rpt .stat-row { display: flex; align-items: center; justify-content: space-between; }
  .pos-rpt .stat-label { font-size: 13px; font-weight: 600; color: var(--rpt-text-secondary); }
  .pos-rpt .stat-icon {
    width: 42px; height: 42px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; font-size: 18px;
  }
  .pos-rpt .stat-icon.blue { background: var(--rpt-blue-light); color: var(--rpt-blue); }
  .pos-rpt .stat-icon.green { background: var(--rpt-green-light); color: var(--rpt-green); }
  .pos-rpt .stat-icon.orange { background: var(--rpt-orange-light); color: var(--rpt-orange); }
  .pos-rpt .stat-icon.red { background: var(--rpt-red-light); color: var(--rpt-red); }
  .pos-rpt .stat-value { font-size: 28px; font-weight: 800; color: var(--rpt-text-primary); font-family: 'DM Mono', monospace; }
  .pos-rpt .stat-sub { font-size: 12px; color: var(--rpt-text-muted); }

  /* Cards */
  .pos-rpt .card-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  .pos-rpt .rpt-card { background: var(--rpt-bg-card); border-radius: var(--rpt-radius); box-shadow: var(--rpt-shadow-sm); overflow: hidden; }
  .pos-rpt .rpt-card.full { grid-column: span 2; }
  .pos-rpt .card-header {
    padding: 18px 22px;
    border-bottom: 1px solid var(--rpt-border);
    display: flex; align-items: center; gap: 10px;
  }
  .pos-rpt .card-header i { font-size: 18px; }
  .pos-rpt .card-title { font-size: 16px; font-weight: 700; color: var(--rpt-text-primary); }
  .pos-rpt .card-body { padding: 22px; }

  /* Chart */
  .pos-rpt .chart-wrap { height: 280px; position: relative; }

  /* Table */
  .pos-rpt .tbl-wrap { overflow-x: auto; }
  .pos-rpt .rpt-table { width: 100%; border-collapse: collapse; }
  .pos-rpt .rpt-table th {
    text-align: left; padding: 12px 16px;
    font-size: 11px; font-weight: 700; color: var(--rpt-text-secondary);
    text-transform: uppercase; letter-spacing: 0.5px;
    background: #f8fafc; border-bottom: 1px solid var(--rpt-border);
  }
  .pos-rpt .rpt-table td { padding: 13px 16px; font-size: 13px; border-bottom: 1px solid var(--rpt-border); }
  .pos-rpt .rpt-table tr:last-child td { border-bottom: none; }
  .pos-rpt .rpt-table tr:hover td { background: #f8fafc; }
  .pos-rpt .text-right { text-align: right; }
  .pos-rpt .text-center { text-align: center; }
  .pos-rpt .badge {
    display: inline-flex; align-items: center;
    padding: 3px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 600;
  }
  .pos-rpt .badge-blue { background: var(--rpt-blue-light); color: var(--rpt-blue); }
  .pos-rpt .badge-green { background: var(--rpt-green-light); color: var(--rpt-green); }
  .pos-rpt .badge-orange { background: var(--rpt-orange-light); color: var(--rpt-orange); }

  /* Empty */
  .pos-rpt .empty-state { text-align: center; padding: 48px 20px; color: var(--rpt-text-muted); }
  .pos-rpt .empty-state i { font-size: 40px; margin-bottom: 12px; display: block; }

  /* Loading */
  .pos-rpt .loading-state { display: flex; align-items: center; justify-content: center; gap: 10px; padding: 48px 20px; color: var(--rpt-text-secondary); }

  @media (max-width: 1100px) {
    .pos-rpt .stat-grid { grid-template-columns: repeat(2, 1fr); }
    .pos-rpt .filter-bar { gap: 10px; }
    .pos-rpt .filter-input { min-width: 130px; }
  }
  @media (max-width: 768px) {
    .pos-rpt .stat-grid { grid-template-columns: 1fr; }
    .pos-rpt .card-grid { grid-template-columns: 1fr; }
    .pos-rpt .rpt-card.full { grid-column: span 1; }
    .pos-rpt .filter-bar { flex-direction: column; gap: 12px; }
    .pos-rpt .filter-input { min-width: 100%; }
    .pos-rpt .filter-group { width: 100%; }
    .pos-rpt .rpt-btn { width: 100%; justify-content: center; }
  }
</style>

<div class="pos-rpt">
  <div class="rpt-content">

    <!-- Filter Bar -->
    <div class="filter-bar">
      <div class="filter-group">
        <label class="filter-label">From Date</label>
        <input type="date" id="filterFrom" class="filter-input">
      </div>
      <div class="filter-group">
        <label class="filter-label">To Date</label>
        <input type="date" id="filterTo" class="filter-input">
      </div>
      <div class="filter-group">
        <label class="filter-label">Property</label>
        <select id="filterProperty" class="filter-input">
          <option value="">All Properties</option>
        </select>
      </div>
      <div class="filter-group">
        <label class="filter-label">Payment Method</label>
        <select id="filterPayment" class="filter-input">
          <option value="">All Methods</option>
        </select>
      </div>
      <div class="filter-group">
        <label class="filter-label">Sale Type</label>
        <select id="filterSaleType" class="filter-input">
          <option value="">All Types</option>
          <option value="pos">POS</option>
          <option value="staff_purchase">Staff Purchase</option>
        </select>
      </div>
      <div class="filter-group">
        <button class="rpt-btn rpt-btn-primary" onclick="loadReport()">
          <i class="fas fa-filter"></i> Apply
        </button>
      </div>
      <div class="filter-group">
        <button class="rpt-btn rpt-btn-outline" onclick="window.print()">
          <i class="fas fa-print"></i> Print
        </button>
      </div>
    </div>

    <!-- Stat Cards -->
    <div class="stat-grid">
      <div class="stat-card">
        <div class="stat-row">
          <span class="stat-label">Total Transactions</span>
          <div class="stat-icon blue"><i class="fas fa-receipt"></i></div>
        </div>
        <div class="stat-value" id="statTransactions">—</div>
        <div class="stat-sub">Sales completed</div>
      </div>
      <div class="stat-card">
        <div class="stat-row">
          <span class="stat-label">Total Sales</span>
          <div class="stat-icon green"><i class="fas fa-money-bill-wave"></i></div>
        </div>
        <div class="stat-value" id="statTotal">—</div>
        <div class="stat-sub">Gross revenue</div>
      </div>
      <div class="stat-card">
        <div class="stat-row">
          <span class="stat-label">Total Tax (VAT)</span>
          <div class="stat-icon orange"><i class="fas fa-percent"></i></div>
        </div>
        <div class="stat-value" id="statTax">—</div>
        <div class="stat-sub">Tax collected</div>
      </div>
      <div class="stat-card">
        <div class="stat-row">
          <span class="stat-label">Avg Transaction</span>
          <div class="stat-icon red"><i class="fas fa-chart-line"></i></div>
        </div>
        <div class="stat-value" id="statAvg">—</div>
        <div class="stat-sub">Per sale average</div>
      </div>
    </div>

    <!-- Charts -->
    <div class="card-grid">
      <div class="rpt-card">
        <div class="card-header">
          <i class="fas fa-chart-area" style="color:var(--rpt-blue)"></i>
          <span class="card-title">Daily Sales Trend</span>
        </div>
        <div class="card-body">
          <div class="chart-wrap"><canvas id="salesTrendChart"></canvas></div>
        </div>
      </div>
      <div class="rpt-card">
        <div class="card-header">
          <i class="fas fa-credit-card" style="color:var(--rpt-green)"></i>
          <span class="card-title">Sales by Payment Method</span>
        </div>
        <div class="card-body">
          <div class="chart-wrap"><canvas id="paymentChart"></canvas></div>
        </div>
      </div>
    </div>

    <!-- Top Products -->
    <div class="rpt-card full">
      <div class="card-header">
        <i class="fas fa-box-open" style="color:var(--rpt-orange)"></i>
        <span class="card-title">Top Selling Products</span>
      </div>
      <div class="card-body">
        <div class="tbl-wrap">
          <table class="rpt-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Product</th>
                <th>Code</th>
                <th class="text-center">UOM</th>
                <th class="text-right">Qty Sold</th>
                <th class="text-right">Revenue</th>
                <th class="text-center">Transactions</th>
              </tr>
            </thead>
            <tbody id="topProductsBody">
              <tr><td colspan="7"><div class="loading-state"><i class="fas fa-spinner fa-spin"></i> Loading...</div></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Payment Summary + Recent Sales -->
    <div class="card-grid">
      <div class="rpt-card">
        <div class="card-header">
          <i class="fas fa-wallet" style="color:var(--rpt-blue)"></i>
          <span class="card-title">Payment Method Summary</span>
        </div>
        <div class="card-body">
          <div class="tbl-wrap">
            <table class="rpt-table">
              <thead>
                <tr>
                  <th>Method</th>
                  <th class="text-center">Count</th>
                  <th class="text-right">Total</th>
                  <th class="text-right">%</th>
                </tr>
              </thead>
              <tbody id="paymentSummaryBody">
                <tr><td colspan="4"><div class="loading-state"><i class="fas fa-spinner fa-spin"></i></div></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="rpt-card">
        <div class="card-header">
          <i class="fas fa-list-ul" style="color:var(--rpt-blue)"></i>
          <span class="card-title">Recent Sales</span>
        </div>
        <div class="card-body">
          <div class="tbl-wrap">
            <table class="rpt-table">
              <thead>
                <tr>
                  <th>Sale #</th>
                  <th>Date</th>
                  <th>Customer</th>
                  <th class="text-right">Total</th>
                </tr>
              </thead>
              <tbody id="recentSalesBody">
                <tr><td colspan="4"><div class="loading-state"><i class="fas fa-spinner fa-spin"></i></div></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  const API_BASE = window.location.pathname.split('/build/')[0] + '/build/api';
  let trendChart = null;
  let paymentChart = null;

  // Set default date range (current month)
  const today = new Date();
  const firstDay = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
  document.getElementById('filterFrom').value = firstDay;
  document.getElementById('filterTo').value = today.toISOString().split('T')[0];

  function formatMoney(v) {
    return '₱' + parseFloat(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  async function loadFilters() {
    try {
      const res = await fetch(`${API_BASE}/inventory_sales.php?action=related`);
      const json = await res.json();
      if (!json.success) return;

      const propSel = document.getElementById('filterProperty');
      (json.data.properties || []).forEach(p => {
        const opt = document.createElement('option');
        opt.value = p.property_id;
        opt.textContent = p.property_name;
        propSel.appendChild(opt);
      });

      const pmSel = document.getElementById('filterPayment');
      (json.data.payment_methods || []).forEach(pm => {
        const opt = document.createElement('option');
        opt.value = pm.method_id;
        opt.textContent = pm.method_name;
        pmSel.appendChild(opt);
      });
    } catch (e) {
      console.error('Failed to load filters:', e);
    }
  }

  async function loadReport() {
    const from = document.getElementById('filterFrom').value;
    const to = document.getElementById('filterTo').value;
    const property = document.getElementById('filterProperty').value;
    const payment = document.getElementById('filterPayment').value;
    const saleType = document.getElementById('filterSaleType').value;

    const params = new URLSearchParams({ action: 'pos_report', date_from: from, date_to: to });
    if (property) params.append('property_id', property);
    if (payment) params.append('payment_method_id', payment);
    if (saleType) params.append('sale_type', saleType);

    try {
      const res = await fetch(`${API_BASE}/reports.php?${params}`);
      const json = await res.json();
      if (!json.success) { console.error(json.message); return; }

      renderSummary(json.data.summary);
      renderTrendChart(json.data.daily_trend);
      renderPaymentChart(json.data.by_payment);
      renderTopProducts(json.data.top_products);
      renderPaymentSummary(json.data.by_payment, json.data.summary);
      renderRecentSales(json.data.recent_sales);
    } catch (e) {
      console.error('Failed to load report:', e);
    }
  }

  function renderSummary(s) {
    document.getElementById('statTransactions').textContent = parseInt(s.total_transactions || 0).toLocaleString();
    document.getElementById('statTotal').textContent = formatMoney(s.total_sales);
    document.getElementById('statTax').textContent = formatMoney(s.total_tax);
    document.getElementById('statAvg').textContent = formatMoney(s.avg_transaction);
  }

  function renderTrendChart(data) {
    if (trendChart) trendChart.destroy();
    const ctx = document.getElementById('salesTrendChart').getContext('2d');
    trendChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: data.map(d => d.sale_date),
        datasets: [{
          label: 'Sales (₱)',
          data: data.map(d => d.total_sales),
          borderColor: '#4f46e5',
          backgroundColor: 'rgba(79,70,229,0.08)',
          fill: true,
          tension: 0.4,
          pointRadius: 4,
          pointHoverRadius: 6
        }, {
          label: 'Transactions',
          data: data.map(d => d.transaction_count),
          borderColor: '#10b981',
          backgroundColor: 'transparent',
          yAxisID: 'y1',
          tension: 0.4,
          pointRadius: 3
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        interaction: { intersect: false, mode: 'index' },
        plugins: { legend: { position: 'top', labels: { usePointStyle: true, padding: 16, font: { size: 12 } } } },
        scales: {
          y: { beginAtZero: true, ticks: { callback: v => '₱' + v.toLocaleString() }, grid: { color: 'rgba(0,0,0,.04)' } },
          y1: { type: 'linear', display: true, position: 'right', beginAtZero: true, grid: { drawOnChartArea: false }, ticks: { stepSize: 1 } },
          x: { grid: { display: false } }
        }
      }
    });
  }

  function renderPaymentChart(data) {
    if (paymentChart) paymentChart.destroy();
    const ctx = document.getElementById('paymentChart').getContext('2d');
    paymentChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: data.map(p => p.payment_method || 'Unknown'),
        datasets: [{ data: data.map(p => p.total_amount), backgroundColor: ['#4f46e5','#10b981','#f97316','#f43f5e','#8b5cf6','#64748b'], borderWidth: 0 }]
      },
      options: {
        responsive: true, maintainAspectRatio: false, cutout: '65%',
        plugins: {
          legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16, font: { size: 12 } } },
          tooltip: { callbacks: { label: ctx => {
            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
            const pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
            return `${ctx.label}: ₱${ctx.parsed.toLocaleString()} (${pct}%)`;
          }}}
        }
      }
    });
  }

  function renderTopProducts(data) {
    const tbody = document.getElementById('topProductsBody');
    if (!data || data.length === 0) {
      tbody.innerHTML = '<tr><td colspan="7"><div class="empty-state"><i class="fas fa-inbox"></i><p>No sales data found</p></div></td></tr>';
      return;
    }
    tbody.innerHTML = data.map((p, i) => `
      <tr>
        <td style="color:var(--rpt-text-muted);font-weight:600">${i + 1}</td>
        <td><strong>${p.product_name}</strong></td>
        <td style="color:var(--rpt-text-secondary)">${p.product_code}</td>
        <td class="text-center"><span class="badge badge-blue">${p.uom_code}</span></td>
        <td class="text-right" style="font-family:'DM Mono',monospace">${parseFloat(p.total_qty_sold).toFixed(2)}</td>
        <td class="text-right" style="font-weight:700;color:var(--rpt-green)">${formatMoney(p.total_revenue)}</td>
        <td class="text-center"><span class="badge badge-green">${p.times_sold}x</span></td>
      </tr>
    `).join('');
  }

  function renderPaymentSummary(data, summary) {
    const tbody = document.getElementById('paymentSummaryBody');
    const total = parseFloat(summary.total_sales || 0);
    if (!data || data.length === 0) {
      tbody.innerHTML = '<tr><td colspan="4"><div class="empty-state"><p>No data</p></div></td></tr>';
      return;
    }
    tbody.innerHTML = data.map(p => {
      const pct = total > 0 ? ((parseFloat(p.total_amount) / total) * 100).toFixed(1) : '0.0';
      return `
        <tr>
          <td><strong>${p.payment_method || 'Unknown'}</strong></td>
          <td class="text-center">${parseInt(p.transaction_count).toLocaleString()}</td>
          <td class="text-right" style="font-weight:600">${formatMoney(p.total_amount)}</td>
          <td class="text-right"><span class="badge badge-orange">${pct}%</span></td>
        </tr>
      `;
    }).join('');
  }

  function renderRecentSales(data) {
    const tbody = document.getElementById('recentSalesBody');
    if (!data || data.length === 0) {
      tbody.innerHTML = '<tr><td colspan="4"><div class="empty-state"><p>No recent sales</p></div></td></tr>';
      return;
    }
    tbody.innerHTML = data.map(s => `
      <tr>
        <td style="font-family:'DM Mono',monospace;font-size:12px">${s.sale_number}</td>
        <td style="color:var(--rpt-text-secondary)">${s.sale_date}</td>
        <td>${s.customer_name || '<span style="color:var(--rpt-text-muted)">Walk-in</span>'}</td>
        <td class="text-right" style="font-weight:700">${formatMoney(s.total_amount)}</td>
      </tr>
    `).join('');
  }

  document.addEventListener('DOMContentLoaded', () => {
    loadFilters();
    loadReport();
  });
</script>
