<?php if (false) {} ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
<style>
    /* ── Food Canteen Reports Design System ── */
    .food-page {
        --food-bg-main: #f0f2f8;
        --food-bg-card: #ffffff;
        --food-text-primary: #1a1f36;
        --food-text-secondary: #6b7280;
        --food-text-muted: #9ca3af;
        --food-border: #e5e7eb;
        --food-accent-blue: #4f46e5;
        --food-accent-blue-light: #eef2ff;
        --food-accent-emerald: #10b981;
        --food-accent-rose: #f43f5e;
        --food-accent-orange: #f97316;
        --food-shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --food-shadow-md: 0 4px 16px rgba(0,0,0,.08);
        --food-radius: 16px;
        --food-radius-sm: 10px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--food-text-primary);
    }

    .food-content { display: flex; flex-direction: column; gap: 24px; }

    /* ── STAT CARD ── */
    .food-page .stat-card {
        background: var(--food-bg-card);
        border-radius: var(--food-radius);
        padding: 20px;
        box-shadow: var(--food-shadow-sm);
        border: 1px solid var(--food-border);
        display: flex; align-items: center; gap: 16px;
    }
    .food-page .stat-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        background: linear-gradient(135deg, #f43f5e, #fb7185);
    }
    .food-page .stat-label { font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--food-text-muted); }
    .food-page .stat-value { font-size: 24px; font-weight: 800; color: var(--food-text-primary); font-family: 'DM Mono', monospace; }

    /* ── CARD ── */
    .food-page .card {
        background: var(--food-bg-card);
        border: 1px solid var(--food-border);
        border-radius: var(--food-radius);
        box-shadow: var(--food-shadow-sm);
        overflow: hidden;
    }
    .food-page .card-header {
        padding: 18px 20px 14px;
        border-bottom: 1px solid var(--food-border);
    }
    .food-page .card-title { font-size: 15px; font-weight: 700; color: var(--food-text-primary); }
    .food-page .card-body { padding: 20px; }

    /* ── TABLE ── */
    .food-page .data-table { width: 100%; border-collapse: collapse; }
    .food-page .data-table th {
        font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .08em;
        color: var(--food-text-muted);
        text-align: left; padding: 12px 16px;
        border-bottom: 1px solid var(--food-border);
    }
    .food-page .data-table th.text-right { text-align: right; }
    .food-page .data-table th.text-center { text-align: center; }
    .food-page .data-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--food-border);
        font-size: 13px; color: var(--food-text-primary);
        vertical-align: middle;
    }
    .food-page .data-table td.text-right { text-align: right; }
    .food-page .data-table td.text-center { text-align: center; }
    .food-page .data-table tr:last-child td { border-bottom: none; }
    .food-page .mono { font-family: 'DM Mono', monospace; font-weight: 600; }
    .food-page .text-emerald { color: var(--food-accent-emerald); }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
        .food-page .stat-card { flex-direction: column; text-align: center; }
    }

    @media (max-width: 640px) {
        .food-page .stat-card {
            padding: 16px;
            gap: 12px;
        }
    }

    @media (max-width: 640px) {
        .food-page .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }
    }

    @media (max-width: 640px) {
        .food-page .stat-label {
            font-size: 10px;
        }
    }

    @media (max-width: 640px) {
        .food-page .stat-value {
            font-size: 20px;
        }
    }

    @media (max-width: 640px) {
        .food-page .card-header {
            padding: 14px 16px 12px;
        }
    }

    @media (max-width: 640px) {
        .food-page .card-title {
            font-size: 14px;
        }
    }

    @media (max-width: 640px) {
        .food-page .card-body {
            padding: 16px;
        }
    }

    @media (max-width: 640px) {
        .food-page .data-table th {
            padding: 8px 12px;
            font-size: 10px;
        }
    }

    @media (max-width: 640px) {
        .food-page .data-table td {
            padding: 10px 12px;
            font-size: 12px;
        }
    }

    @media (max-width: 768px) {
        .food-content > div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }

    @media (max-width: 480px) {
        .food-page .stat-card {
            padding: 14px;
        }
    }

    @media (max-width: 480px) {
        .food-page .stat-value {
            font-size: 18px;
        }
    }

    @media (max-width: 480px) {
        .food-page .card-header {
            padding: 12px 14px 10px;
        }
    }

    @media (max-width: 480px) {
        .food-page .card-body {
            padding: 14px;
        }
    }

    @media (max-width: 480px) {
        .food-page .data-table th {
            padding: 6px 10px;
            font-size: 9px;
        }
    }

    @media (max-width: 480px) {
        .food-page .data-table td {
            padding: 8px 10px;
            font-size: 11px;
        }
    }
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    loadFoodSales();
    loadTopMenuItems();
  });

  async function loadFoodSales() {
    try {
      const res = await fetch('../api/reports.php?action=food_sales_daily&days=30');
      const json = await res.json();
      
      if (json.success) {
        const tbody = document.getElementById('salesTableBody');
        tbody.innerHTML = json.data.map(row => `
          <tr>
            <td>${new Date(row.date).toLocaleDateString()}</td>
            <td class="text-right mono text-emerald">₱${parseFloat(row.sales).toLocaleString()}</td>
            <td class="text-center">${row.orders}</td>
          </tr>
        `).join('');
        
        const total = json.data.reduce((sum, row) => sum + parseFloat(row.sales), 0);
        document.getElementById('totalSales').textContent = '₱' + total.toLocaleString(undefined, {minimumFractionDigits: 2});
      }
    } catch (e) {
      console.error('Error:', e);
    }
  }

  async function loadTopMenuItems() {
    try {
      const res = await fetch('../api/reports.php?action=top_menu_items');
      const json = await res.json();
      
      if (json.success) {
        const tbody = document.getElementById('topItemsTableBody');
        tbody.innerHTML = json.data.map((row, index) => `
          <tr>
            <td class="text-center">${index + 1}</td>
            <td>${row.item_name}</td>
            <td>${row.category_name}</td>
            <td class="text-center">${row.total_sold}</td>
            <td class="text-right mono">₱${parseFloat(row.revenue).toLocaleString()}</td>
          </tr>
        `).join('');
      }
    } catch (e) {
      console.error('Error:', e);
    }
  }
</script>

<div class="food-page">
<div class="food-content">

  <!-- Stat Card -->
  <div class="stat-card">
    <div class="stat-icon">🛒</div>
    <div>
      <div class="stat-label">30-Day Sales</div>
      <div class="stat-value" id="totalSales">₱0.00</div>
    </div>
  </div>

  <!-- Row: Sales Table + Top Items -->
  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">

    <!-- Daily Sales Table -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">Daily Food Sales (Last 30 Days)</div>
      </div>
      <div class="card-body" style="padding: 0; overflow-x: auto;">
        <table class="data-table">
          <thead>
            <tr>
              <th>Date</th>
              <th class="text-right">Sales</th>
              <th class="text-center">Orders</th>
            </tr>
          </thead>
          <tbody id="salesTableBody">
            <tr><td colspan="3" style="text-align: center; color: var(--food-text-muted);">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Top Menu Items Table -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">Top 10 Menu Items</div>
      </div>
      <div class="card-body" style="padding: 0; overflow-x: auto;">
        <table class="data-table">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th>Item</th>
              <th>Category</th>
              <th class="text-center">Sold</th>
              <th class="text-right">Revenue</th>
            </tr>
          </thead>
          <tbody id="topItemsTableBody">
            <tr><td colspan="5" style="text-align: center; color: var(--food-text-muted);">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>

</div>
</div>
