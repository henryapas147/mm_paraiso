<?php
// base_url is passed from Controller
// If not set or empty, calculate it from SCRIPT_NAME
if (!isset($base_url) || empty($base_url)) {
    $script_name = $_SERVER['SCRIPT_NAME'];
    $script_dir = dirname($script_name);
    if (substr($script_dir, -1) != '/') {
        $script_dir .= '/';
    }
    $base_url = $script_dir;
}
?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
<style>
  /* ── Reports Dashboard Design System ── */
  .dash-page {
    --dash-bg-main: #f0f2f8;
    --dash-bg-card: #ffffff;
    --dash-text-primary: #1a1f36;
    --dash-text-secondary: #6b7280;
    --dash-text-muted: #9ca3af;
    --dash-border: #e5e7eb;
    --dash-accent-blue: #4f46e5;
    --dash-accent-blue-light: #eef2ff;
    --dash-accent-emerald: #10b981;
    --dash-accent-rose: #f43f5e;
    --dash-accent-orange: #f97316;
    --dash-accent-violet: #8b5cf6;
    --dash-shadow-sm: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
    --dash-shadow-md: 0 4px 16px rgba(0, 0, 0, .08);
    --dash-radius: 16px;
    --dash-radius-sm: 10px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--dash-text-primary);
  }

  .dash-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
  }

  /* ── STAT CARDS ── */
  .dash-page .stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
  }

  .dash-page .stat-card {
    background: var(--dash-bg-card);
    border-radius: var(--dash-radius);
    padding: 20px;
    box-shadow: var(--dash-shadow-sm);
    border: 1px solid var(--dash-border);
    display: flex;
    flex-direction: column;
    gap: 14px;
    transition: box-shadow .2s, transform .2s;
    position: relative;
    overflow: hidden;
  }

  .dash-page .stat-card:hover {
    box-shadow: var(--dash-shadow-md);
    transform: translateY(-2px);
  }

  .dash-page .stat-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--dash-radius) var(--dash-radius) 0 0;
  }

  .dash-page .stat-card.green::after {
    background: linear-gradient(90deg, #10b981, #34d399);
  }

  .dash-page .stat-card.blue::after {
    background: linear-gradient(90deg, #4f46e5, #8b5cf6);
  }

  .dash-page .stat-card.orange::after {
    background: linear-gradient(90deg, #f97316, #fbbf24);
  }

  .dash-page .stat-card.rose::after {
    background: linear-gradient(90deg, #f43f5e, #fb7185);
  }

  .dash-page .stat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .dash-page .stat-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--dash-text-muted);
  }

  .dash-page .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
  }

  .dash-page .stat-icon.green {
    background: linear-gradient(135deg, #10b981, #34d399);
  }

  .dash-page .stat-icon.blue {
    background: linear-gradient(135deg, #4f46e5, #8b5cf6);
  }

  .dash-page .stat-icon.orange {
    background: linear-gradient(135deg, #f97316, #fbbf24);
  }

  .dash-page .stat-icon.rose {
    background: linear-gradient(135deg, #f43f5e, #fb923c);
  }

  .dash-page .stat-value {
    font-size: 26px;
    font-weight: 800;
    color: var(--dash-text-primary);
    font-family: 'DM Mono', monospace;
  }

  .dash-page .stat-sub {
    font-size: 13px;
    color: var(--dash-text-secondary);
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .dash-page .stat-sub-label {
    font-weight: 600;
  }

  .dash-page .stat-sub-label.emerald {
    color: var(--dash-accent-emerald);
  }

  .dash-page .stat-sub-label.blue {
    color: var(--dash-accent-blue);
  }

  .dash-page .stat-sub-label.orange {
    color: var(--dash-accent-orange);
  }

  .dash-page .stat-sub-label.rose {
    color: var(--dash-accent-rose);
  }

  /* ── CARD ── */
  .dash-page .card {
    background: var(--dash-bg-card);
    border: 1px solid var(--dash-border);
    border-radius: var(--dash-radius);
    box-shadow: var(--dash-shadow-sm);
    overflow: hidden;
  }

  .dash-page .card-header {
    padding: 18px 20px 14px;
    border-bottom: 1px solid var(--dash-border);
  }

  .dash-page .card-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--dash-text-primary);
  }

  .dash-page .card-body {
    padding: 20px;
  }

  /* ── REPORT LINKS ── */
  .dash-page .report-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
  }

  .dash-page .report-link {
    display: flex;
    align-items: center;
    padding: 16px;
    border-radius: var(--dash-radius-sm);
    text-decoration: none;
    transition: all .2s;
    border: 1px solid var(--dash-border);
    background: var(--dash-bg-card);
  }

  .dash-page .report-link:hover {
    box-shadow: var(--dash-shadow-md);
    transform: translateY(-2px);
  }

  .dash-page .report-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-right: 16px;
  }

  .dash-page .report-icon.green {
    background: linear-gradient(135deg, #10b981, #34d399);
  }

  .dash-page .report-icon.blue {
    background: linear-gradient(135deg, #4f46e5, #8b5cf6);
  }

  .dash-page .report-icon.orange {
    background: linear-gradient(135deg, #f97316, #fbbf24);
  }

  .dash-page .report-icon.purple {
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
  }

  .dash-page .report-icon.rose {
    background: linear-gradient(135deg, #f43f5e, #fb7185);
  }

  .dash-page .report-icon.cyan {
    background: linear-gradient(135deg, #06b6d4, #3b82f6);
  }

  .dash-page .report-info h6 {
    font-size: 14px;
    font-weight: 700;
    color: var(--dash-text-primary);
    margin-bottom: 4px;
  }

  .dash-page .report-info p {
    font-size: 12px;
    color: var(--dash-text-secondary);
    margin: 0;
  }

  /* ── RESPONSIVE ── */
  @media (max-width: 1100px) {
    .dash-page .stat-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    .dash-page .report-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 768px) {
    .dash-page .stat-grid {
      grid-template-columns: 1fr 1fr;
    }
    .dash-page .report-grid {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 640px) {
    .dash-page .stat-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .dash-page .stat-card {
      padding: 16px;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .dash-page .stat-icon {
      width: 40px;
      height: 40px;
      font-size: 18px;
    }
  }

  @media (max-width: 640px) {
    .dash-page .stat-label {
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .dash-page .stat-value {
      font-size: 22px;
    }
  }

  @media (max-width: 640px) {
    .dash-page .stat-sub {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .dash-page .card-header {
      padding: 14px 16px 12px;
    }
  }

  @media (max-width: 640px) {
    .dash-page .card-title {
      font-size: 14px;
    }
  }

  @media (max-width: 640px) {
    .dash-page .card-body {
      padding: 16px;
    }
  }

  @media (max-width: 640px) {
    .dash-page .report-grid {
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .dash-page .report-link {
      padding: 12px;
    }
  }

  @media (max-width: 640px) {
    .dash-page .report-icon {
      width: 42px;
      height: 42px;
      font-size: 20px;
      margin-right: 12px;
    }
  }

  @media (max-width: 640px) {
    .dash-page .report-info h6 {
      font-size: 13px;
    }
  }

  @media (max-width: 640px) {
    .dash-page .report-info p {
      font-size: 11px;
    }
  }

  @media (max-width: 768px) {
    .dash-content > div[style*="grid-template-columns: 1fr 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }

  @media (max-width: 480px) {
    .dash-page .stat-grid {
      gap: 10px;
    }
  }

  @media (max-width: 480px) {
    .dash-page .stat-card {
      padding: 14px;
    }
  }

  @media (max-width: 480px) {
    .dash-page .stat-value {
      font-size: 20px;
    }
  }

  @media (max-width: 480px) {
    .dash-page .card-header {
      padding: 12px 14px 10px;
    }
  }

  @media (max-width: 480px) {
    .dash-page .card-body {
      padding: 14px;
    }
  }

  @media (max-width: 480px) {
    .dash-page .report-link {
      flex-direction: column;
      text-align: center;
      padding: 14px;
    }
  }

  @media (max-width: 480px) {
    .dash-page .report-icon {
      margin-right: 0;
      margin-bottom: 10px;
    }
  }
</style>

<script>
  // Base URL for API calls
  const API_BASE_URL = '<?php echo $base_url; ?>api/reports.php';

  // Reports Dashboard JavaScript
  document.addEventListener('DOMContentLoaded', () => {
    loadDashboardStats();
    loadRecentReservations();
    loadTopMenuItems();
  });

  async function loadDashboardStats() {
    try {
      const [revenue, occupancy, customers, inventory] = await Promise.all([
        fetch(API_BASE_URL + '?action=revenue_summary').then(r => r.json()),
        fetch(API_BASE_URL + '?action=occupancy_summary').then(r => r.json()),
        fetch(API_BASE_URL + '?action=customer_summary').then(r => r.json()),
        fetch(API_BASE_URL + '?action=inventory_summary').then(r => r.json())
      ]);

      if (revenue.success) {
        document.getElementById('todayRevenue').textContent = '₱' + (revenue.data.today || 0).toLocaleString();
        document.getElementById('monthRevenue').textContent = '₱' + (revenue.data.month || 0).toLocaleString();
      }

      if (occupancy.success) {
        document.getElementById('todayOccupancy').textContent = (occupancy.data.today || 0) + '%';
        document.getElementById('monthOccupancy').textContent = (occupancy.data.month || 0) + '%';
      }

      if (customers.success) {
        document.getElementById('totalCustomers').textContent = (customers.data.total || 0).toLocaleString();
        document.getElementById('newCustomers').textContent = (customers.data.new_this_month || 0);
      }

      if (inventory.success) {
        document.getElementById('lowStockItems').textContent = inventory.data.low_stock || 0;
        document.getElementById('inventoryValue').textContent = '₱' + (inventory.data.total_value || 0).toLocaleString();
      }
    } catch (e) {
      console.error('Error loading dashboard stats:', e);
    }
  }

  async function loadRecentReservations() {
    const container = document.getElementById('recentReservations');
    try {
      const res = await fetch(API_BASE_URL + '?action=recent_reservations');
      const result = await res.json();
      if (!result.success || !result.data?.length) {
        container.innerHTML = '<p style="text-align: center; color: var(--dash-text-muted);">No reservations found</p>';
        return;
      }
      const statusColors = { confirmed: '#4f46e5', checked_in: '#10b981', checked_out: '#6b7280', cancelled: '#f43f5e', pending: '#f97316' };
      container.innerHTML = '<table style="width: 100%; border-collapse: collapse;">' + result.data.slice(0, 5).map(r => {
        const name = [r.first_name, r.last_name].filter(Boolean).join(' ') || 'Guest';
        const color = statusColors[r.reservation_status] || '#6b7280';
        const statusLabel = (r.reservation_status || '').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        return `<tr style="border-bottom: 1px solid var(--dash-border);">
          <td style="padding: 12px 0; font-size: 13px; font-weight: 600;">${r.reservation_code || '—'}</td>
          <td style="padding: 12px 0; font-size: 13px;">${name}</td>
          <td style="padding: 12px 0; font-size: 13px;">${r.check_in_date || '—'}</td>
          <td style="padding: 12px 0;"><span style="background: ${color}20; color: ${color}; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 600;">${statusLabel}</span></td>
        </tr>`;
      }).join('') + '</table>';
    } catch(e) { container.innerHTML = '<p style="text-align: center; color: var(--dash-text-muted);">Error loading data</p>'; }
  }

  async function loadTopMenuItems() {
    const container = document.getElementById('topMenuItems');
    try {
      const res = await fetch(API_BASE_URL + '?action=top_menu_items');
      const result = await res.json();
      if (!result.success || !result.data?.length) {
        container.innerHTML = '<p style="text-align: center; color: var(--dash-text-muted);">No data available</p>';
        return;
      }
      container.innerHTML = result.data.slice(0, 5).map((item, i) => `
        <div style="display: flex; align-items: center; gap: 12px; padding: 8px 0; border-bottom: 1px solid var(--dash-border);">
          <div style="width: 24px; font-weight: 800; color: var(--dash-text-muted); font-family: 'DM Mono', monospace;">#${i + 1}</div>
          <div style="flex: 1;">
            <div style="font-size: 13px; font-weight: 600; color: var(--dash-text-primary);">${item.item_name}</div>
            <div style="font-size: 11px; color: var(--dash-text-muted);">${item.category_name} · ${parseInt(item.total_sold || 0).toLocaleString()} sold</div>
          </div>
          <div style="font-size: 13px; font-weight: 700; font-family: 'DM Mono', monospace; color: var(--dash-accent-blue);">₱${(item.revenue || 0).toLocaleString()}</div>
        </div>`).join('');
    } catch(e) { container.innerHTML = '<p style="text-align: center; color: var(--dash-text-muted);">Error loading data</p>'; }
  }
</script>

<div class="dash-page">
  <div class="dash-content">

    <!-- Stat Cards -->
    <div class="stat-grid">
      <div class="stat-card green">
        <div class="stat-header">
          <span class="stat-label">Today's Revenue</span>
          <div class="stat-icon green"><i class="fa-solid fa-money-bill-wave"></i></div>
        </div>
        <div class="stat-value" id="todayRevenue">₱0</div>
        <div class="stat-sub">
          <span class="stat-sub-label emerald">Month:</span>
          <span id="monthRevenue">₱0</span>
        </div>
      </div>

      <div class="stat-card blue">
        <div class="stat-header">
          <span class="stat-label">Today's Occupancy</span>
          <div class="stat-icon blue"><i class="fa-solid fa-hotel"></i></div>
        </div>
        <div class="stat-value" id="todayOccupancy">0%</div>
        <div class="stat-sub">
          <span class="stat-sub-label blue">Month Avg:</span>
          <span id="monthOccupancy">0%</span>
        </div>
      </div>

      <div class="stat-card orange">
        <div class="stat-header">
          <span class="stat-label">Total Customers</span>
          <div class="stat-icon orange"><i class="fa-solid fa-users"></i></div>
        </div>
        <div class="stat-value" id="totalCustomers">0</div>
        <div class="stat-sub">
          <span class="stat-sub-label orange">New This Month:</span>
          <span id="newCustomers">0</span>
        </div>
      </div>

      <div class="stat-card rose">
        <div class="stat-header">
          <span class="stat-label">Low Stock Items</span>
          <div class="stat-icon rose"><i class="fa-solid fa-box"></i></div>
        </div>
        <div class="stat-value" id="lowStockItems">0</div>
        <div class="stat-sub">
          <span class="stat-sub-label rose">Inventory Value:</span>
          <span id="inventoryValue">₱0</span>
        </div>
      </div>
    </div>

    <!-- Available Reports -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">Available Reports</div>
      </div>
      <div class="card-body">
        <div class="report-grid">
          <a href="<?php echo $base_url; ?>reports/revenue" class="report-link">
            <div class="report-icon green"><i class="fa-solid fa-money-bill-wave"></i></div>
            <div class="report-info">
              <h6>Revenue Reports</h6>
              <p>Daily, monthly, yearly revenue analysis</p>
            </div>
          </a>

          <a href="<?php echo $base_url; ?>reports/occupancy" class="report-link">
            <div class="report-icon blue"><i class="fa-solid fa-hotel"></i></div>
            <div class="report-info">
              <h6>Occupancy Reports</h6>
              <p>Room occupancy & utilization stats</p>
            </div>
          </a>

          <a href="<?php echo $base_url; ?>reports/inventory" class="report-link">
            <div class="report-icon orange"><i class="fa-solid fa-box"></i></div>
            <div class="report-info">
              <h6>Inventory Reports</h6>
              <p>Stock levels, valuations & movements</p>
            </div>
          </a>

          <a href="<?php echo $base_url; ?>reports/customers" class="report-link">
            <div class="report-icon purple"><i class="fa-solid fa-chart-pie"></i></div>
            <div class="report-info">
              <h6>Customer Analytics</h6>
              <p>Customer behavior & demographics</p>
            </div>
          </a>

          <a href="<?php echo $base_url; ?>reports/food-canteen" class="report-link">
            <div class="report-icon rose"><i class="fa-solid fa-utensils"></i></div>
            <div class="report-info">
              <h6>Food & Canteen</h6>
              <p>Sales reports & menu performance</p>
            </div>
          </a>

          <a href="<?php echo $base_url; ?>reports/financial" class="report-link">
            <div class="report-icon cyan"><i class="fa-solid fa-file-invoice-dollar"></i></div>
            <div class="report-info">
              <h6>Financial Reports</h6>
              <p>P&L, balance sheet & cash flow</p>
            </div>
          </a>
        </div>
      </div>
    </div>

    <!-- Recent Activity Row -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
      <div class="card">
        <div class="card-header">
          <div class="card-title">Recent Reservations</div>
        </div>
        <div class="card-body">
          <div id="recentReservations">
            <p style="text-align: center; color: var(--dash-text-muted);">Loading...</p>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="card-title">Top Selling Menu Items</div>
        </div>
        <div class="card-body">
          <div id="topMenuItems">
            <p style="text-align: center; color: var(--dash-text-muted);">Loading...</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
