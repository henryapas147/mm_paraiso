<?php if (false) {
} ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
<style>
  /* ── Customer Reports Design System ── */
  .cust-page {
    --cust-bg-main: #f0f2f8;
    --cust-bg-card: #ffffff;
    --cust-text-primary: #1a1f36;
    --cust-text-secondary: #6b7280;
    --cust-text-muted: #9ca3af;
    --cust-border: #e5e7eb;
    --cust-accent-blue: #4f46e5;
    --cust-accent-blue-light: #eef2ff;
    --cust-accent-emerald: #10b981;
    --cust-accent-rose: #f43f5e;
    --cust-accent-orange: #f97316;
    --cust-accent-violet: #8b5cf6;
    --cust-shadow-sm: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
    --cust-shadow-md: 0 4px 16px rgba(0, 0, 0, .08);
    --cust-radius: 16px;
    --cust-radius-sm: 10px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--cust-text-primary);
  }

  .cust-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
  }

  /* ── STAT CARDS ── */
  .cust-page .stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
  }

  .cust-page .stat-card {
    background: var(--cust-bg-card);
    border-radius: var(--cust-radius);
    padding: 20px;
    box-shadow: var(--cust-shadow-sm);
    border: 1px solid var(--cust-border);
    display: flex;
    flex-direction: column;
    gap: 14px;
    transition: box-shadow .2s, transform .2s;
    position: relative;
    overflow: hidden;
  }

  .cust-page .stat-card:hover {
    box-shadow: var(--cust-shadow-md);
    transform: translateY(-2px);
  }

  .cust-page .stat-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--cust-radius) var(--cust-radius) 0 0;
  }

  .cust-page .stat-card.blue::after {
    background: linear-gradient(90deg, #4f46e5, #8b5cf6);
  }

  .cust-page .stat-card.rose::after {
    background: linear-gradient(90deg, #f43f5e, #fb7185);
  }

  .cust-page .stat-card.green::after {
    background: linear-gradient(90deg, #10b981, #34d399);
  }

  .cust-page .stat-card.orange::after {
    background: linear-gradient(90deg, #f97316, #fbbf24);
  }

  .cust-page .stat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .cust-page .stat-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--cust-text-muted);
  }

  .cust-page .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
  }

  .cust-page .stat-icon.blue {
    background: linear-gradient(135deg, #4f46e5, #8b5cf6);
  }

  .cust-page .stat-icon.rose {
    background: linear-gradient(135deg, #f43f5e, #fb923c);
  }

  .cust-page .stat-icon.green {
    background: linear-gradient(135deg, #10b981, #34d399);
  }

  .cust-page .stat-icon.orange {
    background: linear-gradient(135deg, #f97316, #fbbf24);
  }

  .cust-page .stat-value {
    font-size: 26px;
    font-weight: 800;
    color: var(--cust-text-primary);
    font-family: 'DM Mono', monospace;
  }

  .cust-page .stat-footer {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--cust-text-muted);
  }

  .cust-page .stat-badge {
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 2px;
  }

  .cust-page .stat-badge.up {
    color: var(--cust-accent-emerald);
  }

  .cust-page .stat-badge.down {
    color: var(--cust-accent-rose);
  }

  /* ── CARD ── */
  .cust-page .card {
    background: var(--cust-bg-card);
    border: 1px solid var(--cust-border);
    border-radius: var(--cust-radius);
    box-shadow: var(--cust-shadow-sm);
    overflow: hidden;
  }

  .cust-page .card-header {
    padding: 18px 20px 14px;
    border-bottom: 1px solid var(--cust-border);
  }

  .cust-page .card-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--cust-text-primary);
  }

  .cust-page .card-body {
    padding: 20px;
  }

  /* ── TABLE ── */
  .cust-page .data-table {
    width: 100%;
    border-collapse: collapse;
  }

  .cust-page .data-table th {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--cust-text-muted);
    text-align: left;
    padding: 12px 16px;
    border-bottom: 1px solid var(--cust-border);
  }

  .cust-page .data-table th.text-right {
    text-align: right;
  }

  .cust-page .data-table th.text-center {
    text-align: center;
  }

  .cust-page .data-table td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--cust-border);
    font-size: 13px;
    color: var(--cust-text-primary);
    vertical-align: middle;
  }

  .cust-page .data-table td.text-right {
    text-align: right;
  }

  .cust-page .data-table td.text-center {
    text-align: center;
  }

  .cust-page .data-table tr:last-child td {
    border-bottom: none;
  }

  .cust-page .mono {
    font-family: 'DM Mono', monospace;
    font-weight: 600;
  }

  /* ── RESPONSIVE ── */
  @media (max-width: 1100px) {
    .cust-page .stat-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 768px) {
    .cust-page .stat-grid {
      grid-template-columns: 1fr 1fr;
    }
  }

  @media (max-width: 640px) {
    .cust-page .stat-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .cust-page .stat-card {
      padding: 16px;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .cust-page .stat-icon {
      width: 40px;
      height: 40px;
      font-size: 18px;
    }
  }

  @media (max-width: 640px) {
    .cust-page .stat-label {
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .cust-page .stat-value {
      font-size: 22px;
    }
  }

  @media (max-width: 640px) {
    .cust-page .stat-footer {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .cust-page .stat-badge {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .cust-page .card-header {
      padding: 14px 16px 12px;
    }
  }

  @media (max-width: 640px) {
    .cust-page .card-title {
      font-size: 14px;
    }
  }

  @media (max-width: 640px) {
    .cust-page .card-body {
      padding: 16px;
    }
  }

  @media (max-width: 640px) {
    .cust-page .data-table th {
      padding: 8px 12px;
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .cust-page .data-table td {
      padding: 10px 12px;
      font-size: 12px;
    }
  }

  @media (max-width: 768px) {
    .cust-content > div[style*="grid-template-columns: 1fr 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }

  @media (max-width: 480px) {
    .cust-page .stat-grid {
      gap: 10px;
    }
  }

  @media (max-width: 480px) {
    .cust-page .stat-card {
      padding: 14px;
    }
  }

  @media (max-width: 480px) {
    .cust-page .stat-value {
      font-size: 20px;
    }
  }

  @media (max-width: 480px) {
    .cust-page .card-header {
      padding: 12px 14px 10px;
    }
  }

  @media (max-width: 480px) {
    .cust-page .card-body {
      padding: 14px;
    }
  }

  @media (max-width: 480px) {
    .cust-page .data-table th {
      padding: 6px 10px;
      font-size: 9px;
    }
  }

  @media (max-width: 480px) {
    .cust-page .data-table td {
      padding: 8px 10px;
      font-size: 11px;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    loadCustomerSummary();
    loadCustomerDemographics();
    loadTopCustomers();
  });

  async function loadCustomerSummary() {
    try {
      const res = await fetch('../api/reports.php?action=customer_summary');
      const json = await res.json();
      if (json.success && json.data) {
        const d = json.data;
        document.getElementById('statTotalCustomers').textContent = (d.total ?? 0).toLocaleString();
        document.getElementById('statNewThisMonth').textContent = '+' + (d.new_this_month ?? 0).toLocaleString();
        document.getElementById('statActiveCustomers').textContent = (d.active ?? 0).toLocaleString();
        document.getElementById('statAvgSpending').textContent = '₱' + parseFloat(d.avg_spending ?? 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
      }
    } catch (e) {
      console.error('Error loading customer summary:', e);
    }
  }

  async function loadCustomerDemographics() {
    try {
      const res = await fetch('../api/reports.php?action=customer_demographics');
      const json = await res.json();

      if (json.success) {
        const tbody = document.getElementById('demographicsTableBody');
        tbody.innerHTML = json.data.map(row => `
          <tr>
            <td>${row.city || 'Unknown'}</td>
            <td class="text-center">${row.count}</td>
          </tr>
        `).join('');
      }
    } catch (e) {
      console.error('Error:', e);
    }
  }

  async function loadTopCustomers() {
    try {
      const res = await fetch('../api/reports.php?action=customer_booking_frequency');
      const json = await res.json();

      if (json.success) {
        const tbody = document.getElementById('topCustomersTableBody');
        tbody.innerHTML = json.data.map(row => `
          <tr>
            <td>${row.first_name} ${row.last_name}</td>
            <td class="text-center">${row.booking_count}</td>
            <td class="text-right mono">₱${parseFloat(row.total_spent).toLocaleString()}</td>
          </tr>
        `).join('');
      }
    } catch (e) {
      console.error('Error:', e);
    }
  }
</script>

<div class="cust-page">
  <div class="cust-content">

    <!-- Stat Cards -->
    <div class="stat-grid">
      <div class="stat-card blue">
        <div class="stat-header">
          <span class="stat-label">Total Customers</span>
          <div class="stat-icon blue"><i class="fa-solid fa-users"></i></div>
        </div>
        <div class="stat-value" id="statTotalCustomers">—</div>
        <div class="stat-footer">
          registered customers
        </div>
      </div>

      <div class="stat-card rose">
        <div class="stat-header">
          <span class="stat-label">New This Month</span>
          <div class="stat-icon rose"><i class="fa-solid fa-user-plus"></i></div>
        </div>
        <div class="stat-value" id="statNewThisMonth">—</div>
        <div class="stat-footer">
          this month
        </div>
      </div>

      <div class="stat-card green">
        <div class="stat-header">
          <span class="stat-label">Active Customers</span>
          <div class="stat-icon green"><i class="fa-solid fa-check-circle"></i></div>
        </div>
        <div class="stat-value" id="statActiveCustomers">—</div>
        <div class="stat-footer">
          booked in last 30 days
        </div>
      </div>

      <div class="stat-card orange">
        <div class="stat-header">
          <span class="stat-label">Avg Spending</span>
          <div class="stat-icon orange"><i class="fa-solid fa-money-bill-wave"></i></div>
        </div>
        <div class="stat-value" id="statAvgSpending">—</div>
        <div class="stat-footer">
          avg per customer
        </div>
      </div>
    </div>

    <!-- Row: City Demographics + Top Customers -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">

      <!-- Customers by City -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">Customers by City</div>
        </div>
        <div class="card-body" style="padding: 0; overflow-x: auto;">
          <table class="data-table">
            <thead>
              <tr>
                <th>City</th>
                <th class="text-center">Customers</th>
              </tr>
            </thead>
            <tbody id="demographicsTableBody">
              <tr>
                <td colspan="2" style="text-align: center; color: var(--cust-text-muted);">Loading...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Top Customers -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">Top Customers by Bookings</div>
        </div>
        <div class="card-body" style="padding: 0; overflow-x: auto;">
          <table class="data-table">
            <thead>
              <tr>
                <th>Customer</th>
                <th class="text-center">Bookings</th>
                <th class="text-right">Total Spent</th>
              </tr>
            </thead>
            <tbody id="topCustomersTableBody">
              <tr>
                <td colspan="3" style="text-align: center; color: var(--cust-text-muted);">Loading...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>

  </div>
</div>