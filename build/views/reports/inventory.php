<?php if (false) {} ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
<style>
  /* ── Inventory Reports Design System ── */
  .inv-page {
    --inv-bg-main: #f0f2f8;
    --inv-bg-card: #ffffff;
    --inv-text-primary: #1a1f36;
    --inv-text-secondary: #6b7280;
    --inv-text-muted: #9ca3af;
    --inv-border: #e5e7eb;
    --inv-accent-blue: #4f46e5;
    --inv-accent-blue-light: #eef2ff;
    --inv-accent-emerald: #10b981;
    --inv-accent-rose: #f43f5e;
    --inv-accent-orange: #f97316;
    --inv-accent-violet: #8b5cf6;
    --inv-shadow-sm: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
    --inv-shadow-md: 0 4px 16px rgba(0, 0, 0, .08);
    --inv-radius: 16px;
    --inv-radius-sm: 10px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--inv-text-primary);
  }

  .inv-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
  }

  /* ── STAT CARDS ── */
  .inv-page .stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
  }

  .inv-page .stat-card {
    background: var(--inv-bg-card);
    border-radius: var(--inv-radius);
    padding: 20px;
    box-shadow: var(--inv-shadow-sm);
    border: 1px solid var(--inv-border);
    display: flex;
    flex-direction: column;
    gap: 14px;
    transition: box-shadow .2s, transform .2s;
    position: relative;
    overflow: hidden;
  }

  .inv-page .stat-card:hover {
    box-shadow: var(--inv-shadow-md);
    transform: translateY(-2px);
  }

  .inv-page .stat-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--inv-radius) var(--inv-radius) 0 0;
  }

  .inv-page .stat-card.blue::after {
    background: linear-gradient(90deg, #4f46e5, #8b5cf6);
  }

  .inv-page .stat-card.rose::after {
    background: linear-gradient(90deg, #f43f5e, #fb7185);
  }

  .inv-page .stat-card.green::after {
    background: linear-gradient(90deg, #10b981, #34d399);
  }

  .inv-page .stat-card.orange::after {
    background: linear-gradient(90deg, #f97316, #fbbf24);
  }

  .inv-page .stat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .inv-page .stat-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--inv-text-muted);
  }

  .inv-page .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
  }

  .inv-page .stat-icon.blue {
    background: linear-gradient(135deg, #4f46e5, #8b5cf6);
  }

  .inv-page .stat-icon.rose {
    background: linear-gradient(135deg, #f43f5e, #fb923c);
  }

  .inv-page .stat-icon.green {
    background: linear-gradient(135deg, #10b981, #34d399);
  }

  .inv-page .stat-icon.orange {
    background: linear-gradient(135deg, #f97316, #fbbf24);
  }

  .inv-page .stat-value {
    font-size: 26px;
    font-weight: 800;
    color: var(--inv-text-primary);
    font-family: 'DM Mono', monospace;
  }

  .inv-page .stat-footer {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--inv-text-muted);
  }

  .inv-page .stat-badge {
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 2px;
  }

  .inv-page .stat-badge.up {
    color: var(--inv-accent-emerald);
  }

  .inv-page .stat-badge.down {
    color: var(--inv-accent-rose);
  }

  /* ── CARD ── */
  .inv-page .card {
    background: var(--inv-bg-card);
    border: 1px solid var(--inv-border);
    border-radius: var(--inv-radius);
    box-shadow: var(--inv-shadow-sm);
    overflow: hidden;
  }

  .inv-page .card-header {
    padding: 18px 20px 14px;
    border-bottom: 1px solid var(--inv-border);
  }

  .inv-page .card-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--inv-text-primary);
  }

  .inv-page .card-body {
    padding: 20px;
  }

  /* ── TABLE ── */
  .inv-page .data-table {
    width: 100%;
    border-collapse: collapse;
  }

  .inv-page .data-table th {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--inv-text-muted);
    text-align: left;
    padding: 12px 16px;
    border-bottom: 1px solid var(--inv-border);
  }

  .inv-page .data-table th.text-right {
    text-align: right;
  }

  .inv-page .data-table th.text-center {
    text-align: center;
  }

  .inv-page .data-table td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--inv-border);
    font-size: 13px;
    color: var(--inv-text-primary);
    vertical-align: middle;
  }

  .inv-page .data-table td.text-right {
    text-align: right;
  }

  .inv-page .data-table td.text-center {
    text-align: center;
  }

  .inv-page .data-table tr:last-child td {
    border-bottom: none;
  }

  .inv-page .mono {
    font-family: 'DM Mono', monospace;
    font-weight: 600;
  }

  .inv-page .text-emerald {
    color: var(--inv-accent-emerald);
  }

  /* ── BADGES ── */
  .inv-page .badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
  }

  .inv-page .badge-in {
    background: #d1fae5;
    color: #059669;
  }

  .inv-page .badge-out {
    background: #fee2e2;
    color: #dc2626;
  }

  .inv-page .badge-adjust {
    background: #dbeafe;
    color: #2563eb;
  }

  /* ── RESPONSIVE ── */
  @media (max-width: 1200px) {
    .inv-page .stat-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 768px) {
    .inv-page .stat-grid {
      grid-template-columns: 1fr 1fr;
    }
  }

  @media (max-width: 640px) {
    .inv-page .stat-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .inv-page .stat-card {
      padding: 16px;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .inv-page .stat-icon {
      width: 40px;
      height: 40px;
      font-size: 18px;
    }
  }

  @media (max-width: 640px) {
    .inv-page .stat-label {
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .inv-page .stat-value {
      font-size: 22px;
    }
  }

  @media (max-width: 640px) {
    .inv-page .stat-footer {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .inv-page .stat-badge {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .inv-page .card-header {
      padding: 14px 16px 12px;
    }
  }

  @media (max-width: 640px) {
    .inv-page .card-title {
      font-size: 14px;
    }
  }

  @media (max-width: 640px) {
    .inv-page .card-body {
      padding: 16px;
    }
  }

  @media (max-width: 640px) {
    .inv-page .data-table th {
      padding: 8px 12px;
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .inv-page .data-table td {
      padding: 10px 12px;
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .inv-page .badge {
      padding: 3px 8px;
      font-size: 10px;
    }
  }

  @media (max-width: 768px) {
    .inv-content > div[style*="grid-template-columns: 1fr 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }

  @media (max-width: 480px) {
    .inv-page .stat-grid {
      gap: 10px;
    }
  }

  @media (max-width: 480px) {
    .inv-page .stat-card {
      padding: 14px;
    }
  }

  @media (max-width: 480px) {
    .inv-page .stat-value {
      font-size: 20px;
    }
  }

  @media (max-width: 480px) {
    .inv-page .card-header {
      padding: 12px 14px 10px;
    }
  }

  @media (max-width: 480px) {
    .inv-page .card-body {
      padding: 14px;
    }
  }

  @media (max-width: 480px) {
    .inv-page .data-table th {
      padding: 6px 10px;
      font-size: 9px;
    }
  }

  @media (max-width: 480px) {
    .inv-page .data-table td {
      padding: 8px 10px;
      font-size: 11px;
    }
  }

  @media (max-width: 480px) {
    .inv-page .badge {
      padding: 2px 6px;
      font-size: 9px;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    loadInventoryValuation();
    loadStockMovements();
  });

  async function loadInventoryValuation() {
    try {
      const res = await fetch('../api/reports.php?action=inventory_valuation');
      const json = await res.json();
      
      if (json.success) {
        renderValuationTable(json.data);
        const total = json.data.reduce((sum, row) => sum + parseFloat(row.total_value || 0), 0);
        document.getElementById('totalValue').textContent = '₱' + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
      }
    } catch (e) {
      console.error('Error:', e);
    }
  }

  async function loadStockMovements() {
    try {
      const res = await fetch('../api/reports.php?action=stock_movements&days=30');
      const json = await res.json();
      
      if (json.success) {
        renderMovementsTable(json.data);
      }
    } catch (e) {
      console.error('Error:', e);
    }
  }

  function renderValuationTable(data) {
    const tbody = document.getElementById('valuationTableBody');
    tbody.innerHTML = data.map(row => `
      <tr>
        <td>${row.category_name}</td>
        <td class="text-center">${row.product_count || 0}</td>
        <td class="text-right mono text-emerald">₱${parseFloat(row.total_value || 0).toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
      </tr>
    `).join('');
  }

  function renderMovementsTable(data) {
    const tbody = document.getElementById('movementsTableBody');
    tbody.innerHTML = data.map(row => {
      const badgeClass = row.transaction_type === 'in' ? 'badge-in' : row.transaction_type === 'out' ? 'badge-out' : 'badge-adjust';
      return `
      <tr>
        <td>${new Date(row.date).toLocaleDateString()}</td>
        <td class="text-center"><span class="badge ${badgeClass}">${row.transaction_type}</span></td>
        <td class="text-center">${row.count}</td>
        <td class="text-center">${row.total_quantity}</td>
      </tr>
    `}).join('');
  }
</script>

<div class="inv-page">
  <div class="inv-content">

    <!-- Stat Cards -->
    <div class="stat-grid">
      <div class="stat-card orange">
        <div class="stat-header">
          <span class="stat-label">Total Value</span>
          <div class="stat-icon orange"><i class="fa-solid fa-box"></i></div>
        </div>
        <div class="stat-value" id="totalValue">₱0.00</div>
        <div class="stat-footer">
          <span class="stat-badge up">▲ +8%</span>
          since last month
        </div>
      </div>

      <div class="stat-card blue">
        <div class="stat-header">
          <span class="stat-label">Total Products</span>
          <div class="stat-icon blue"><i class="fa-solid fa-clipboard-list"></i></div>
        </div>
        <div class="stat-value">2,456</div>
        <div class="stat-footer">
          <span class="stat-badge up">▲ +12%</span>
          across all categories
        </div>
      </div>

      <div class="stat-card green">
        <div class="stat-header">
          <span class="stat-label">In Stock</span>
          <div class="stat-icon green"><i class="fa-solid fa-check-circle"></i></div>
        </div>
        <div class="stat-value">1,892</div>
        <div class="stat-footer">
          <span class="stat-badge up">▲ +5%</span>
          available items
        </div>
      </div>

      <div class="stat-card rose">
        <div class="stat-header">
          <span class="stat-label">Low Stock</span>
          <div class="stat-icon rose"><i class="fa-solid fa-triangle-exclamation"></i></div>
        </div>
        <div class="stat-value">23</div>
        <div class="stat-footer">
          <span class="stat-badge down">▼ -2%</span>
          need reorder
        </div>
      </div>
    </div>

    <!-- Row: Valuation + Movements -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">

      <!-- Inventory Valuation -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">Inventory Valuation by Category</div>
        </div>
        <div class="card-body" style="padding: 0; overflow-x: auto;">
          <table class="data-table">
            <thead>
              <tr>
                <th>Category</th>
                <th class="text-center">Products</th>
                <th class="text-right">Value</th>
              </tr>
            </thead>
            <tbody id="valuationTableBody">
              <tr>
                <td colspan="3" style="text-align: center; color: var(--inv-text-muted);">Loading...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Stock Movements -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">Stock Movements (Last 30 Days)</div>
        </div>
        <div class="card-body" style="padding: 0; overflow-x: auto;">
          <table class="data-table">
            <thead>
              <tr>
                <th>Date</th>
                <th class="text-center">Type</th>
                <th class="text-center">Transactions</th>
                <th class="text-center">Quantity</th>
              </tr>
            </thead>
            <tbody id="movementsTableBody">
              <tr>
                <td colspan="4" style="text-align: center; color: var(--inv-text-muted);">Loading...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>

  </div>
</div>
