<?php if (false) {} ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
<style>
  /* ── Revenue Reports Design System ── */
  .rev-page {
    --rev-bg-main: #f0f2f8;
    --rev-bg-card: #ffffff;
    --rev-text-primary: #1a1f36;
    --rev-text-secondary: #6b7280;
    --rev-text-muted: #9ca3af;
    --rev-border: #e5e7eb;
    --rev-accent-blue: #4f46e5;
    --rev-accent-blue-light: #eef2ff;
    --rev-accent-emerald: #10b981;
    --rev-accent-rose: #f43f5e;
    --rev-accent-orange: #f97316;
    --rev-accent-violet: #8b5cf6;
    --rev-shadow-sm: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
    --rev-shadow-md: 0 4px 16px rgba(0, 0, 0, .08);
    --rev-radius: 16px;
    --rev-radius-sm: 10px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--rev-text-primary);
  }

  .rev-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
  }

  /* ── STAT CARDS ── */
  .rev-page .stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
  }

  .rev-page .stat-card {
    background: var(--rev-bg-card);
    border-radius: var(--rev-radius);
    padding: 20px;
    box-shadow: var(--rev-shadow-sm);
    border: 1px solid var(--rev-border);
    display: flex;
    flex-direction: column;
    gap: 14px;
    transition: box-shadow .2s, transform .2s;
    position: relative;
    overflow: hidden;
  }

  .rev-page .stat-card:hover {
    box-shadow: var(--rev-shadow-md);
    transform: translateY(-2px);
  }

  .rev-page .stat-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--rev-radius) var(--rev-radius) 0 0;
  }

  .rev-page .stat-card.blue::after {
    background: linear-gradient(90deg, #4f46e5, #8b5cf6);
  }

  .rev-page .stat-card.rose::after {
    background: linear-gradient(90deg, #f43f5e, #fb7185);
  }

  .rev-page .stat-card.green::after {
    background: linear-gradient(90deg, #10b981, #34d399);
  }

  .rev-page .stat-card.orange::after {
    background: linear-gradient(90deg, #f97316, #fbbf24);
  }

  .rev-page .stat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .rev-page .stat-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--rev-text-muted);
  }

  .rev-page .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
  }

  .rev-page .stat-icon.blue {
    background: linear-gradient(135deg, #4f46e5, #8b5cf6);
  }

  .rev-page .stat-icon.rose {
    background: linear-gradient(135deg, #f43f5e, #fb923c);
  }

  .rev-page .stat-icon.green {
    background: linear-gradient(135deg, #10b981, #34d399);
  }

  .rev-page .stat-icon.orange {
    background: linear-gradient(135deg, #f97316, #fbbf24);
  }

  .rev-page .stat-value {
    font-size: 26px;
    font-weight: 800;
    color: var(--rev-text-primary);
    font-family: 'DM Mono', monospace;
  }

  .rev-page .stat-footer {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--rev-text-muted);
  }

  .rev-page .stat-badge {
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 2px;
  }

  .rev-page .stat-badge.up {
    color: var(--rev-accent-emerald);
  }

  .rev-page .stat-badge.down {
    color: var(--rev-accent-rose);
  }

  /* ── CARD ── */
  .rev-page .card {
    background: var(--rev-bg-card);
    border: 1px solid var(--rev-border);
    border-radius: var(--rev-radius);
    box-shadow: var(--rev-shadow-sm);
    overflow: hidden;
  }

  .rev-page .card-header {
    padding: 18px 20px 14px;
    border-bottom: 1px solid var(--rev-border);
  }

  .rev-page .card-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--rev-text-primary);
  }

  .rev-page .card-body {
    padding: 20px;
  }

  /* ── TABLE ── */
  .rev-page .data-table {
    width: 100%;
    border-collapse: collapse;
  }

  .rev-page .data-table th {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--rev-text-muted);
    text-align: left;
    padding: 12px 16px;
    border-bottom: 1px solid var(--rev-border);
  }

  .rev-page .data-table th.text-right {
    text-align: right;
  }

  .rev-page .data-table th.text-center {
    text-align: center;
  }

  .rev-page .data-table td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--rev-border);
    font-size: 13px;
    color: var(--rev-text-primary);
    vertical-align: middle;
  }

  .rev-page .data-table td.text-right {
    text-align: right;
  }

  .rev-page .data-table td.text-center {
    text-align: center;
  }

  .rev-page .data-table tr:last-child td {
    border-bottom: none;
  }

  .rev-page .mono {
    font-family: 'DM Mono', monospace;
    font-weight: 600;
  }

  .rev-page .text-emerald {
    color: var(--rev-accent-emerald);
  }

  /* ── BUTTONS ── */
  .rev-page .btn {
    font-family: inherit;
    font-size: 12px;
    font-weight: 600;
    padding: 7px 14px;
    border-radius: 8px;
    cursor: pointer;
    transition: all .18s;
    border: 1.5px solid transparent;
  }

  .rev-page .btn-primary {
    background: var(--rev-accent-blue-light);
    color: var(--rev-accent-blue);
    border-color: var(--rev-accent-blue);
  }

  .rev-page .btn-primary:hover {
    background: var(--rev-accent-blue);
    color: #fff;
  }

  .rev-page .btn-secondary {
    background: var(--rev-bg-main);
    color: var(--rev-text-secondary);
    border-color: var(--rev-border);
  }

  .rev-page .btn-secondary:hover {
    border-color: var(--rev-accent-blue);
    color: var(--rev-accent-blue);
  }

  .rev-page .btn-secondary.active {
    background: var(--rev-accent-blue-light);
    color: var(--rev-accent-blue);
    border-color: var(--rev-accent-blue);
  }

  .rev-page .btn-export {
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    color: #fff;
    border: none;
  }

  .rev-page .btn-export:hover {
    opacity: 0.9;
  }

  /* ── RESPONSIVE ── */
  @media (max-width: 1100px) {
    .rev-page .stat-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 768px) {
    .rev-page .stat-grid {
      grid-template-columns: 1fr 1fr;
    }
  }

  @media (max-width: 640px) {
    .rev-page .stat-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .rev-page .stat-card {
      padding: 16px;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .rev-page .stat-icon {
      width: 40px;
      height: 40px;
      font-size: 18px;
    }
  }

  @media (max-width: 640px) {
    .rev-page .stat-label {
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .rev-page .stat-value {
      font-size: 22px;
    }
  }

  @media (max-width: 640px) {
    .rev-page .stat-footer {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .rev-page .stat-badge {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .rev-page .card-header {
      padding: 14px 16px 12px;
    }
  }

  @media (max-width: 640px) {
    .rev-page .card-title {
      font-size: 14px;
    }
  }

  @media (max-width: 640px) {
    .rev-page .card-body {
      padding: 16px;
    }
  }

  @media (max-width: 640px) {
    .rev-page .data-table th {
      padding: 8px 12px;
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .rev-page .data-table td {
      padding: 10px 12px;
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .rev-page .btn {
      font-size: 11px;
      padding: 6px 12px;
    }
  }

  @media (max-width: 768px) {
    .rev-content > div[style*="grid-template-columns: 2fr 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }

  @media (max-width: 768px) {
    .rev-content > div[style*="grid-template-columns: 1fr 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }

  @media (max-width: 640px) {
    .rev-content > div[style*="height: 300px"] {
      height: 250px !important;
    }
  }

  @media (max-width: 480px) {
    .rev-page .stat-grid {
      gap: 10px;
    }
  }

  @media (max-width: 480px) {
    .rev-page .stat-card {
      padding: 14px;
    }
  }

  @media (max-width: 480px) {
    .rev-page .stat-value {
      font-size: 20px;
    }
  }

  @media (max-width: 480px) {
    .rev-page .card-header {
      padding: 12px 14px 10px;
    }
  }

  @media (max-width: 480px) {
    .rev-page .card-body {
      padding: 14px;
    }
  }

  @media (max-width: 480px) {
    .rev-page .data-table th {
      padding: 6px 10px;
      font-size: 9px;
    }
  }

  @media (max-width: 480px) {
    .rev-page .data-table td {
      padding: 8px 10px;
      font-size: 11px;
    }
  }

  @media (max-width: 480px) {
    .rev-page .btn {
      width: 100%;
      justify-content: center;
    }
  }

  @media (max-width: 480px) {
    .rev-content > div[style*="height: 300px"] {
      height: 200px !important;
    }
  }
</style>

<script>
  let revenueChart;
  let sourceChart;

  document.addEventListener('DOMContentLoaded', () => {
    loadRevenueData(30);
    loadRevenueBySource();
    loadMonthlyRevenue();
  });

  async function loadRevenueData(days) {
    try {
      const res = await fetch(`../api/reports.php?action=revenue_daily&days=${days}`);
      const json = await res.json();
      
      if (json.success && json.data.length > 0) {
        renderRevenueChart(json.data);
        renderRevenueTable(json.data);
        
        const total = json.data.reduce((sum, row) => sum + parseFloat(row.revenue), 0);
        const transactions = json.data.reduce((sum, row) => sum + parseInt(row.transactions), 0);
        document.getElementById('totalRevenue').textContent = '₱' + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('totalTransactions').textContent = transactions.toLocaleString();
      } else {
        document.getElementById('revenueTableBody').innerHTML = '<tr><td colspan="4" style="text-align: center; color: var(--rev-text-muted);">No data available</td></tr>';
      }
    } catch (e) {
      console.error('Error loading revenue data:', e);
    }
  }

  async function loadRevenueBySource() {
    try {
      const res = await fetch('../api/reports.php?action=revenue_by_source');
      const json = await res.json();
      
      if (json.success) {
        renderSourceChart(json.data);
      }
    } catch (e) {
      console.error('Error loading source data:', e);
    }
  }

  async function loadMonthlyRevenue() {
    try {
      const res = await fetch('../api/reports.php?action=revenue_monthly');
      const json = await res.json();
      
      if (json.success) {
        renderMonthlyTable(json.data);
      }
    } catch (e) {
      console.error('Error loading monthly data:', e);
    }
  }

  function renderRevenueChart(data) {
    const canvas = document.getElementById('revenue-revenue-chart');
    if (!canvas) return;
    
    const existingChart = Chart.getChart(canvas);
    if (existingChart) {
      existingChart.destroy();
    }

    const ctx = canvas.getContext('2d');
    const labels = data.map(row => new Date(row.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
    const revenues = data.map(row => parseFloat(row.revenue));

    revenueChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels.reverse(),
        datasets: [{
          label: 'Revenue',
          data: revenues.reverse(),
          borderColor: '#10b981',
          backgroundColor: 'rgba(16, 185, 129, 0.1)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return '₱' + value.toLocaleString();
              }
            }
          }
        }
      }
    });
  }

  function renderSourceChart(data) {
    const canvas = document.getElementById('revenue-source-chart');
    if (!canvas) return;
    
    const existingChart = Chart.getChart(canvas);
    if (existingChart) {
      existingChart.destroy();
    }

    const ctx = canvas.getContext('2d');
    
    sourceChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: data.map(row => row.source_channel || 'Unknown'),
        datasets: [{
          data: data.map(row => parseFloat(row.revenue)),
          backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  }

  function renderRevenueTable(data) {
    const tbody = document.getElementById('revenueTableBody');
    tbody.innerHTML = data.map(row => `
      <tr>
        <td>${new Date(row.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td>
        <td class="text-right mono text-emerald">₱${parseFloat(row.revenue).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
        <td class="text-center">${row.transactions}</td>
        <td class="text-right mono">₱${(parseFloat(row.revenue) / parseInt(row.transactions)).toFixed(2)}</td>
      </tr>
    `).join('');
  }

  function renderMonthlyTable(data) {
    const tbody = document.getElementById('monthlyTableBody');
    tbody.innerHTML = data.map(row => `
      <tr>
        <td>${row.month}</td>
        <td class="text-right mono text-emerald">₱${parseFloat(row.revenue).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
        <td class="text-center">${row.transactions}</td>
      </tr>
    `).join('');
  }

  function changePeriod(days) {
    loadRevenueData(days);
    const buttons = document.querySelectorAll('.period-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
  }

  function exportReport() {
    alert('Export functionality will generate CSV/PDF');
  }
</script>

<div class="rev-page">
  <div class="rev-content">

    <!-- Stat Cards -->
    <div class="stat-grid">
      <div class="stat-card green">
        <div class="stat-header">
          <span class="stat-label">Total Revenue</span>
          <div class="stat-icon green"><i class="fa-solid fa-money-bill-wave"></i></div>
        </div>
        <div class="stat-value" id="totalRevenue">₱0.00</div>
        <div class="stat-footer">
          <span class="stat-badge up">▲ +15%</span>
          vs last period
        </div>
      </div>

      <div class="stat-card blue">
        <div class="stat-header">
          <span class="stat-label">Transactions</span>
          <div class="stat-icon blue">💳</div>
        </div>
        <div class="stat-value" id="totalTransactions">0</div>
        <div class="stat-footer">
          <span class="stat-badge up">▲ +8%</span>
          total count
        </div>
      </div>

      <div class="stat-card orange">
        <div class="stat-header">
          <span class="stat-label">Period</span>
          <div class="stat-icon orange"><i class="fa-solid fa-calendar-days"></i></div>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
          <button onclick="changePeriod(7)" class="btn btn-secondary period-btn">7 Days</button>
          <button onclick="changePeriod(30)" class="btn btn-secondary active period-btn">30 Days</button>
          <button onclick="changePeriod(90)" class="btn btn-secondary period-btn">90 Days</button>
        </div>
      </div>

      <div class="stat-card rose">
        <div class="stat-header">
          <span class="stat-label">Actions</span>
          <div class="stat-icon rose"><i class="fa-solid fa-download"></i></div>
        </div>
        <button onclick="exportReport()" class="btn btn-export" style="width: 100%;">Export Report</button>
      </div>
    </div>

    <!-- Charts Row -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px;">
      <div class="card">
        <div class="card-header">
          <div class="card-title">Daily Revenue Trend</div>
        </div>
        <div class="card-body">
          <div style="height: 300px;">
            <canvas id="revenue-revenue-chart"></canvas>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="card-title">Revenue by Source</div>
        </div>
        <div class="card-body">
          <div style="height: 300px;">
            <canvas id="revenue-source-chart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Tables Row -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
      <div class="card">
        <div class="card-header">
          <div class="card-title">Daily Breakdown</div>
        </div>
        <div class="card-body" style="padding: 0; overflow-x: auto;">
          <table class="data-table">
            <thead>
              <tr>
                <th>Date</th>
                <th class="text-right">Revenue</th>
                <th class="text-center">Transactions</th>
                <th class="text-right">Avg/Trans</th>
              </tr>
            </thead>
            <tbody id="revenueTableBody">
              <tr><td colspan="4" style="text-align: center; color: var(--rev-text-muted);">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="card-title">Monthly Summary</div>
        </div>
        <div class="card-body" style="padding: 0; overflow-x: auto;">
          <table class="data-table">
            <thead>
              <tr>
                <th>Month</th>
                <th class="text-right">Revenue</th>
                <th class="text-center">Transactions</th>
              </tr>
            </thead>
            <tbody id="monthlyTableBody">
              <tr><td colspan="3" style="text-align: center; color: var(--rev-text-muted);">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
