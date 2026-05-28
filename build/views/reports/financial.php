<?php if (false) {} ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
<style>
    /* ── Financial Reports Design System ── */
    .fin-page {
        --fin-bg-main: #f0f2f8;
        --fin-bg-card: #ffffff;
        --fin-text-primary: #1a1f36;
        --fin-text-secondary: #6b7280;
        --fin-text-muted: #9ca3af;
        --fin-border: #e5e7eb;
        --fin-accent-blue: #4f46e5;
        --fin-accent-blue-light: #eef2ff;
        --fin-accent-emerald: #10b981;
        --fin-accent-rose: #f43f5e;
        --fin-accent-orange: #f97316;
        --fin-accent-violet: #8b5cf6;
        --fin-shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --fin-shadow-md: 0 4px 16px rgba(0,0,0,.08);
        --fin-radius: 16px;
        --fin-radius-sm: 10px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--fin-text-primary);
    }

    .fin-content { display: flex; flex-direction: column; gap: 24px; }

    /* ── CARD ── */
    .fin-page .card {
        background: var(--fin-bg-card);
        border: 1px solid var(--fin-border);
        border-radius: var(--fin-radius);
        box-shadow: var(--fin-shadow-sm);
        overflow: hidden;
    }
    .fin-page .card-header {
        padding: 18px 20px 14px;
        border-bottom: 1px solid var(--fin-border);
        display: flex; align-items: center; justify-content: space-between;
    }
    .fin-page .card-title { font-size: 15px; font-weight: 700; color: var(--fin-text-primary); }
    .fin-page .card-body { padding: 20px; }

    /* ── BUTTONS ── */
    .fin-page .btn {
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        padding: 7px 14px;
        border-radius: 8px;
        cursor: pointer;
        transition: all .18s;
        border: 1.5px solid transparent;
    }
    .fin-page .btn-primary {
        background: var(--fin-accent-blue-light);
        color: var(--fin-accent-blue);
        border-color: var(--fin-accent-blue);
    }
    .fin-page .btn-primary:hover {
        background: var(--fin-accent-blue);
        color: #fff;
    }
    .fin-page .btn-secondary {
        background: var(--fin-bg-main);
        color: var(--fin-text-secondary);
        border-color: var(--fin-border);
    }
    .fin-page .btn-secondary:hover {
        border-color: var(--fin-accent-blue);
        color: var(--fin-accent-blue);
    }

    /* ── REPORT TYPES LIST ── */
    .fin-page .report-list { display: flex; flex-direction: column; gap: 8px; }
    .fin-page .report-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 14px;
        border-radius: var(--fin-radius-sm);
        transition: background .18s;
        cursor: pointer;
    }
    .fin-page .report-item:hover { background: var(--fin-bg-main); }
    .fin-page .report-item.active { background: var(--fin-accent-blue-light); }
    .fin-page .report-icon {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        background: var(--fin-accent-blue-light);
        color: var(--fin-accent-blue);
    }
    .fin-page .report-name { font-size: 14px; font-weight: 600; color: var(--fin-text-primary); }
    .fin-page .report-desc { font-size: 11px; color: var(--fin-text-muted); }

    /* ── PERIOD GRID ── */
    .fin-page .period-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
    .fin-page .period-btn {
        padding: 16px 12px;
        background: var(--fin-bg-main);
        border: 1.5px solid var(--fin-border);
        border-radius: var(--fin-radius-sm);
        cursor: pointer;
        transition: all .18s;
        text-align: center;
    }
    .fin-page .period-btn:hover {
        border-color: var(--fin-accent-blue);
        background: var(--fin-accent-blue-light);
    }
    .fin-page .period-btn.active {
        border-color: var(--fin-accent-blue);
        background: var(--fin-accent-blue-light);
    }
    .fin-page .period-label { font-size: 13px; font-weight: 600; color: var(--fin-text-primary); }
    .fin-page .period-sub { font-size: 11px; color: var(--fin-text-muted); }

    /* ── STATS ROW ── */
    .fin-page .stats-row { display: flex; gap: 16px; justify-content: center; margin-top: 24px; }
    .fin-page .stat-box {
        padding: 16px 20px;
        background: var(--fin-bg-main);
        border-radius: var(--fin-radius-sm);
        text-align: center;
    }
    .fin-page .stat-label { font-size: 11px; color: var(--fin-text-muted); font-weight: 500; }
    .fin-page .stat-value { font-size: 18px; font-weight: 700; font-family: 'DM Mono', monospace; margin-top: 4px; }
    .fin-page .stat-value.emerald { color: var(--fin-accent-emerald); }
    .fin-page .stat-value.rose { color: var(--fin-accent-rose); }
    .fin-page .stat-value.blue { color: var(--fin-accent-blue); }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
        .fin-page .period-grid { grid-template-columns: repeat(2, 1fr); }
        .fin-page .stats-row { flex-direction: column; }
    }
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    loadFinancialSummary();
  });

  async function loadFinancialSummary() {
    try {
      document.getElementById('financialContent').innerHTML = `
        <div style="padding: 48px 24px; text-align: center;">
          <div style="font-size: 48px; margin-bottom: 16px;">📊</div>
          <h6 style="font-size: 18px; font-weight: 600; color: var(--fin-text-primary); margin-bottom: 8px;">Financial Reports</h6>
          <p style="font-size: 13px; color: var(--fin-text-muted); max-width: 400px; margin: 0 auto; line-height: 1.6;">
            Comprehensive financial reports including Profit & Loss statements, 
            Balance Sheets, and Cash Flow analysis will be implemented here.
          </p>
          <div class="stats-row">
            <div class="stat-box">
              <p class="stat-label">Revenue (YTD)</p>
              <p class="stat-value emerald">₱0.00</p>
            </div>
            <div class="stat-box">
              <p class="stat-label">Expenses (YTD)</p>
              <p class="stat-value rose">₱0.00</p>
            </div>
            <div class="stat-box">
              <p class="stat-label">Net Profit</p>
              <p class="stat-value blue">₱0.00</p>
            </div>
          </div>
        </div>
      `;
    } catch (e) {
      console.error('Error:', e);
    }
  }
</script>

<div class="fin-page">
<div class="fin-content">

  <!-- Main Card -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">Financial Reports</div>
      <div style="display: flex; gap: 8px;">
        <button class="btn btn-primary">P&L Statement</button>
        <button class="btn btn-secondary">Balance Sheet</button>
        <button class="btn btn-secondary">Cash Flow</button>
      </div>
    </div>
    <div class="card-body" id="financialContent">
      <div style="padding: 32px; text-align: center;">
        <p style="color: var(--fin-text-muted);">Loading financial data...</p>
      </div>
    </div>
  </div>

  <!-- Row: Report Types + Period Selection -->
  <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 16px;">

    <!-- Report Types -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">Report Types</div>
      </div>
      <div class="card-body">
        <div class="report-list">
          <div class="report-item active">
            <div class="report-icon">💰</div>
            <div>
              <div class="report-name">Profit & Loss</div>
              <div class="report-desc">Income vs Expenses</div>
            </div>
          </div>
          <div class="report-item">
            <div class="report-icon" style="background: var(--fin-bg-main); color: var(--fin-text-secondary);">⚖️</div>
            <div>
              <div class="report-name">Balance Sheet</div>
              <div class="report-desc">Assets, Liabilities, Equity</div>
            </div>
          </div>
          <div class="report-item">
            <div class="report-icon" style="background: var(--fin-bg-main); color: var(--fin-text-secondary);">📈</div>
            <div>
              <div class="report-name">Cash Flow</div>
              <div class="report-desc">Operating, Investing, Financing</div>
            </div>
          </div>
          <div class="report-item">
            <div class="report-icon" style="background: var(--fin-bg-main); color: var(--fin-text-secondary);">📊</div>
            <div>
              <div class="report-name">Tax Reports</div>
              <div class="report-desc">VAT, Sales Tax summaries</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Period Selection -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">Period Selection</div>
      </div>
      <div class="card-body">
        <div class="period-grid">
          <div class="period-btn active">
            <div class="period-label">This Month</div>
            <div class="period-sub">Oct 2026</div>
          </div>
          <div class="period-btn">
            <div class="period-label">Last Month</div>
            <div class="period-sub">Sep 2026</div>
          </div>
          <div class="period-btn">
            <div class="period-label">This Quarter</div>
            <div class="period-sub">Q4 2026</div>
          </div>
          <div class="period-btn">
            <div class="period-label">This Year</div>
            <div class="period-sub">2026</div>
          </div>
        </div>
      </div>
    </div>

  </div>

</div>
</div>
