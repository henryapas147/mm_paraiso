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

    @media (max-width: 768px) {
        .fin-content > div[style*="grid-template-columns: 1fr 2fr"] {
            grid-template-columns: 1fr !important;
        }
    }

    @media (max-width: 640px) {
        .fin-page .card-header {
            padding: 14px 16px 12px;
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }
    }

    @media (max-width: 640px) {
        .fin-page .card-title {
            font-size: 14px;
        }
    }

    @media (max-width: 640px) {
        .fin-page .card-body {
            padding: 16px;
        }
    }

    @media (max-width: 640px) {
        .fin-page .btn {
            font-size: 11px;
            padding: 6px 12px;
        }
    }

    @media (max-width: 640px) {
        .fin-page .report-item {
            padding: 10px 12px;
        }
    }

    @media (max-width: 640px) {
        .fin-page .report-icon {
            width: 34px;
            height: 34px;
            font-size: 16px;
        }
    }

    @media (max-width: 640px) {
        .fin-page .report-name {
            font-size: 13px;
        }
    }

    @media (max-width: 640px) {
        .fin-page .report-desc {
            font-size: 10px;
        }
    }

    @media (max-width: 640px) {
        .fin-page .period-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
    }

    @media (max-width: 640px) {
        .fin-page .period-btn {
            padding: 12px 10px;
        }
    }

    @media (max-width: 640px) {
        .fin-page .period-label {
            font-size: 12px;
        }
    }

    @media (max-width: 640px) {
        .fin-page .period-sub {
            font-size: 10px;
        }
    }

    @media (max-width: 640px) {
        .fin-page .stat-box {
            padding: 12px 16px;
        }
    }

    @media (max-width: 640px) {
        .fin-page .stat-label {
            font-size: 10px;
        }
    }

    @media (max-width: 640px) {
        .fin-page .stat-value {
            font-size: 16px;
        }
    }

    @media (max-width: 480px) {
        .fin-page .card-header {
            padding: 12px 14px 10px;
        }
    }

    @media (max-width: 480px) {
        .fin-page .card-body {
            padding: 14px;
        }
    }

    @media (max-width: 480px) {
        .fin-page .btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .fin-page .report-item {
            padding: 8px 10px;
        }
    }

    @media (max-width: 480px) {
        .fin-page .report-icon {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .fin-page .period-btn {
            padding: 10px 8px;
        }
    }

    @media (max-width: 480px) {
        .fin-page .stat-box {
            padding: 10px 14px;
        }
    }

    @media (max-width: 480px) {
        .fin-page .stat-value {
            font-size: 14px;
        }
    }
</style>

<script>
  let currentReportType = 'pl';
  let currentPeriod = 'year';

  document.addEventListener('DOMContentLoaded', () => {
    loadFinancialSummary();
    setupReportTypeHandlers();
    setupPeriodHandlers();
    setupHeaderButtons();
  });

  function setupReportTypeHandlers() {
    const reportItems = document.querySelectorAll('.report-item');
    reportItems.forEach((item, index) => {
      item.addEventListener('click', () => {
        reportItems.forEach(i => {
          i.classList.remove('active');
          i.querySelector('.report-icon').style.background = 'var(--fin-bg-main)';
          i.querySelector('.report-icon').style.color = 'var(--fin-text-secondary)';
        });
        item.classList.add('active');
        item.querySelector('.report-icon').style.background = 'var(--fin-accent-blue-light)';
        item.querySelector('.report-icon').style.color = 'var(--fin-accent-blue)';
        
        const types = ['pl', 'balance_sheet', 'cash_flow', 'tax'];
        currentReportType = types[index];
        loadFinancialSummary();
      });
    });
  }

  function setupPeriodHandlers() {
    const periodBtns = document.querySelectorAll('.period-btn');
    periodBtns.forEach((btn, index) => {
      btn.addEventListener('click', () => {
        periodBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        const periods = ['month', 'last_month', 'quarter', 'year'];
        currentPeriod = periods[index];
        loadFinancialSummary();
      });
    });
  }

  function setupHeaderButtons() {
    const headerBtns = document.querySelectorAll('.card-header .btn');
    headerBtns.forEach((btn, index) => {
      btn.addEventListener('click', () => {
        headerBtns.forEach(b => {
          b.classList.remove('btn-primary');
          b.classList.add('btn-secondary');
        });
        btn.classList.remove('btn-secondary');
        btn.classList.add('btn-primary');
        
        const types = ['pl', 'balance_sheet', 'cash_flow'];
        currentReportType = types[index];
        
        // Update report item selection
        const reportItems = document.querySelectorAll('.report-item');
        reportItems.forEach((item, i) => {
          item.classList.remove('active');
          item.querySelector('.report-icon').style.background = 'var(--fin-bg-main)';
          item.querySelector('.report-icon').style.color = 'var(--fin-text-secondary)';
          if (i === index) {
            item.classList.add('active');
            item.querySelector('.report-icon').style.background = 'var(--fin-accent-blue-light)';
            item.querySelector('.report-icon').style.color = 'var(--fin-accent-blue)';
          }
        });
        
        loadFinancialSummary();
      });
    });
  }

  async function loadFinancialSummary() {
    try {
      const res = await fetch(`../api/reports.php?action=financial_summary&period=${currentPeriod}`);
      const result = await res.json();
      
      if (result.success) {
        const data = result.data;
        const fmt = v => '₱' + parseFloat(v || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        const revenue = parseFloat(data.revenue || 0);
        const extraCharges = parseFloat(data.extra_charges || 0);
        const totalRevenue = parseFloat(data.total_revenue || 0);
        const expenses = parseFloat(data.expenses || 0);
        const netProfit = parseFloat(data.net_profit || 0);
        const margin = totalRevenue > 0 ? ((netProfit / totalRevenue) * 100).toFixed(1) : '0.0';

        let content = '';

        if (currentReportType === 'pl') {
          content = `
            <h6 style="font-size: 14px; font-weight: 700; color: var(--fin-text-primary); margin-bottom: 16px;">
              <i class="fa-solid fa-money-bill-wave" style="color: var(--fin-accent-blue); margin-right: 6px;"></i>
              Profit & Loss Statement — ${data.year}
            </h6>
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
              <tr style="background: var(--fin-bg-main);">
                <th style="text-align: left; padding: 10px 14px; font-size: 11px; text-transform: uppercase; color: var(--fin-text-muted); font-weight: 700;" colspan="2">Income</th>
              </tr>
              <tr style="border-bottom: 1px solid var(--fin-border);">
                <td style="padding: 12px 14px; color: var(--fin-text-primary);">Room Revenue (Payments Received)</td>
                <td style="padding: 12px 14px; text-align: right; font-weight: 700; color: var(--fin-accent-emerald); font-family: 'DM Mono', monospace;">${fmt(revenue)}</td>
              </tr>
              <tr style="border-bottom: 1px solid var(--fin-border);">
                <td style="padding: 12px 14px; color: var(--fin-text-primary);">Extra Charges (Damage, Late Checkout, etc.)</td>
                <td style="padding: 12px 14px; text-align: right; font-weight: 700; color: var(--fin-accent-blue); font-family: 'DM Mono', monospace;">${fmt(extraCharges)}</td>
              </tr>
              <tr style="background: var(--fin-bg-main); font-weight: 700;">
                <td style="padding: 12px 14px; color: var(--fin-text-primary);">Total Revenue</td>
                <td style="padding: 12px 14px; text-align: right; font-weight: 700; color: var(--fin-accent-emerald); font-family: 'DM Mono', monospace;">${fmt(totalRevenue)}</td>
              </tr>
              <tr style="background: var(--fin-bg-main);">
                <th style="text-align: left; padding: 10px 14px; font-size: 11px; text-transform: uppercase; color: var(--fin-text-muted); font-weight: 700;" colspan="2">Cost of Goods Sold</th>
              </tr>
              <tr style="border-bottom: 1px solid var(--fin-border);">
                <td style="padding: 12px 14px; color: var(--fin-text-primary);">Inventory & Stock Costs</td>
                <td style="padding: 12px 14px; text-align: right; font-weight: 700; color: var(--fin-accent-rose); font-family: 'DM Mono', monospace;">(${fmt(expenses)})</td>
              </tr>
              <tr style="background: ${netProfit >= 0 ? '#ecfdf5' : '#fff1f2'}; font-weight: 700; border-top: 2px solid var(--fin-border);">
                <td style="padding: 14px; font-size: 14px; color: var(--fin-text-primary);">Net Profit</td>
                <td style="padding: 14px; text-align: right; font-size: 16px; font-family: 'DM Mono', monospace; color: ${netProfit >= 0 ? 'var(--fin-accent-emerald)' : 'var(--fin-accent-rose)'};">${fmt(netProfit)}</td>
              </tr>
            </table>
            <div class="stats-row" style="margin-top: 20px;">
              <div class="stat-box"><p class="stat-label">Room Revenue</p><p class="stat-value emerald">${fmt(revenue)}</p></div>
              <div class="stat-box"><p class="stat-label">Extra Charges</p><p class="stat-value blue">${fmt(extraCharges)}</p></div>
              <div class="stat-box"><p class="stat-label">Total Revenue</p><p class="stat-value emerald">${fmt(totalRevenue)}</p></div>
              <div class="stat-box"><p class="stat-label">Net Profit</p><p class="stat-value ${netProfit >= 0 ? 'emerald' : 'rose'}">${fmt(netProfit)}</p></div>
            </div>`;
        } else if (currentReportType === 'balance_sheet') {
          content = `
            <h6 style="font-size: 14px; font-weight: 700; color: var(--fin-text-primary); margin-bottom: 16px;">
              <i class="fa-solid fa-scale-balanced" style="color: var(--fin-accent-blue); margin-right: 6px;"></i>
              Balance Sheet — ${data.year}
            </h6>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
              <div>
                <p style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--fin-text-muted); margin-bottom: 10px;">Assets</p>
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                  <tr style="border-bottom: 1px solid var(--fin-border);">
                    <td style="padding: 10px 0; color: var(--fin-text-secondary);">Revenue Collected</td>
                    <td style="padding: 10px 0; text-align: right; font-family: 'DM Mono', monospace; color: var(--fin-accent-emerald); font-weight: 600;">${fmt(revenue)}</td>
                  </tr>
                  <tr style="border-top: 2px solid var(--fin-border); font-weight: 700;">
                    <td style="padding: 12px 0; color: var(--fin-text-primary);">Total Assets</td>
                    <td style="padding: 12px 0; text-align: right; font-family: 'DM Mono', monospace; color: var(--fin-accent-emerald);">${fmt(revenue)}</td>
                  </tr>
                </table>
              </div>
              <div>
                <p style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--fin-text-muted); margin-bottom: 10px;">Liabilities & Equity</p>
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                  <tr style="border-bottom: 1px solid var(--fin-border);">
                    <td style="padding: 10px 0; color: var(--fin-text-secondary);">Cost of Goods Sold</td>
                    <td style="padding: 10px 0; text-align: right; font-family: 'DM Mono', monospace; color: var(--fin-accent-rose); font-weight: 600;">${fmt(expenses)}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid var(--fin-border);">
                    <td style="padding: 10px 0; color: var(--fin-text-secondary);">Net Equity (Retained Profit)</td>
                    <td style="padding: 10px 0; text-align: right; font-family: 'DM Mono', monospace; font-weight: 600; color: ${netProfit >= 0 ? 'var(--fin-accent-emerald)' : 'var(--fin-accent-rose)'};">${fmt(netProfit)}</td>
                  </tr>
                  <tr style="border-top: 2px solid var(--fin-border); font-weight: 700;">
                    <td style="padding: 12px 0; color: var(--fin-text-primary);">Total Liabilities + Equity</td>
                    <td style="padding: 12px 0; text-align: right; font-family: 'DM Mono', monospace; color: var(--fin-accent-emerald);">${fmt(revenue)}</td>
                  </tr>
                </table>
              </div>
            </div>`;
        } else if (currentReportType === 'cash_flow') {
          content = `
            <h6 style="font-size: 14px; font-weight: 700; color: var(--fin-text-primary); margin-bottom: 16px;">
              <i class="fa-solid fa-arrow-trend-up" style="color: var(--fin-accent-blue); margin-right: 6px;"></i>
              Cash Flow Statement — ${data.year}
            </h6>
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
              <tr style="background: var(--fin-bg-main);">
                <th style="text-align: left; padding: 10px 14px; font-size: 11px; text-transform: uppercase; color: var(--fin-text-muted); font-weight: 700;" colspan="2">Operating Activities</th>
              </tr>
              <tr style="border-bottom: 1px solid var(--fin-border);">
                <td style="padding: 12px 14px; color: var(--fin-text-primary);">Cash received from customers</td>
                <td style="padding: 12px 14px; text-align: right; font-family: 'DM Mono', monospace; color: var(--fin-accent-emerald); font-weight: 600;">${fmt(revenue)}</td>
              </tr>
              <tr style="border-bottom: 1px solid var(--fin-border);">
                <td style="padding: 12px 14px; color: var(--fin-text-primary);">Cash paid for inventory/operations</td>
                <td style="padding: 12px 14px; text-align: right; font-family: 'DM Mono', monospace; color: var(--fin-accent-rose); font-weight: 600;">(${fmt(expenses)})</td>
              </tr>
              <tr style="background: #ecfdf5; border-bottom: 2px solid var(--fin-border); font-weight: 700;">
                <td style="padding: 12px 14px; color: var(--fin-text-primary);">Net Cash from Operating Activities</td>
                <td style="padding: 12px 14px; text-align: right; font-family: 'DM Mono', monospace; color: ${netProfit >= 0 ? 'var(--fin-accent-emerald)' : 'var(--fin-accent-rose)'};">${fmt(netProfit)}</td>
              </tr>
              <tr style="background: var(--fin-bg-main);">
                <th style="text-align: left; padding: 10px 14px; font-size: 11px; text-transform: uppercase; color: var(--fin-text-muted); font-weight: 700;" colspan="2">Investing & Financing</th>
              </tr>
              <tr style="border-bottom: 1px solid var(--fin-border);">
                <td style="padding: 12px 14px; color: var(--fin-text-secondary);">No investing/financing data available</td>
                <td style="padding: 12px 14px; text-align: right; font-family: 'DM Mono', monospace; color: var(--fin-text-muted);">—</td>
              </tr>
              <tr style="font-weight: 700; border-top: 2px solid var(--fin-border);">
                <td style="padding: 14px; font-size: 14px; color: var(--fin-text-primary);">Net Change in Cash</td>
                <td style="padding: 14px; text-align: right; font-size: 16px; font-family: 'DM Mono', monospace; color: ${netProfit >= 0 ? 'var(--fin-accent-emerald)' : 'var(--fin-accent-rose)'};">${fmt(netProfit)}</td>
              </tr>
            </table>`;
        } else if (currentReportType === 'tax') {
          const vat = (totalRevenue * 0.12).toFixed(2);
          const vatableBase = (totalRevenue / 1.12).toFixed(2);
          content = `
            <h6 style="font-size: 14px; font-weight: 700; color: var(--fin-text-primary); margin-bottom: 16px;">
              <i class="fa-solid fa-chart-pie" style="color: var(--fin-accent-blue); margin-right: 6px;"></i>
              Tax Reports — ${data.year}
            </h6>
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
              <tr style="background: var(--fin-bg-main);">
                <th style="text-align: left; padding: 10px 14px; font-size: 11px; text-transform: uppercase; color: var(--fin-text-muted); font-weight: 700;" colspan="2">Revenue Breakdown</th>
              </tr>
              <tr style="border-bottom: 1px solid var(--fin-border);">
                <td style="padding: 12px 14px; color: var(--fin-text-primary);">Room Revenue</td>
                <td style="padding: 12px 14px; text-align: right; font-family: 'DM Mono', monospace; font-weight: 600;">${fmt(revenue)}</td>
              </tr>
              <tr style="border-bottom: 1px solid var(--fin-border);">
                <td style="padding: 12px 14px; color: var(--fin-text-primary);">Extra Charges</td>
                <td style="padding: 12px 14px; text-align: right; font-family: 'DM Mono', monospace; font-weight: 600;">${fmt(extraCharges)}</td>
              </tr>
              <tr style="border-bottom: 1px solid var(--fin-border); background: var(--fin-bg-main); font-weight: 700;">
                <td style="padding: 12px 14px; color: var(--fin-text-primary);">Total Revenue</td>
                <td style="padding: 12px 14px; text-align: right; font-family: 'DM Mono', monospace; font-weight: 700;">${fmt(totalRevenue)}</td>
              </tr>
              <tr style="background: var(--fin-bg-main);">
                <th style="text-align: left; padding: 10px 14px; font-size: 11px; text-transform: uppercase; color: var(--fin-text-muted); font-weight: 700;" colspan="2">VAT Summary (12%)</th>
              </tr>
              <tr style="border-bottom: 1px solid var(--fin-border);">
                <td style="padding: 12px 14px; color: var(--fin-text-primary);">Gross Sales (VAT-inclusive)</td>
                <td style="padding: 12px 14px; text-align: right; font-family: 'DM Mono', monospace; font-weight: 600;">${fmt(totalRevenue)}</td>
              </tr>
              <tr style="border-bottom: 1px solid var(--fin-border);">
                <td style="padding: 12px 14px; color: var(--fin-text-primary);">VATable Base (÷ 1.12)</td>
                <td style="padding: 12px 14px; text-align: right; font-family: 'DM Mono', monospace; font-weight: 600;">${fmt(vatableBase)}</td>
              </tr>
              <tr style="border-bottom: 2px solid var(--fin-border); background: #fef3c7;">
                <td style="padding: 12px 14px; font-weight: 700; color: var(--fin-text-primary);">Output VAT (12%)</td>
                <td style="padding: 12px 14px; text-align: right; font-family: 'DM Mono', monospace; font-weight: 700; color: var(--fin-accent-orange);">${fmt(vat)}</td>
              </tr>
              <tr style="background: var(--fin-bg-main);">
                <th style="text-align: left; padding: 10px 14px; font-size: 11px; text-transform: uppercase; color: var(--fin-text-muted); font-weight: 700;" colspan="2">Input VAT (Purchases)</th>
              </tr>
              <tr style="border-bottom: 1px solid var(--fin-border);">
                <td style="padding: 12px 14px; color: var(--fin-text-secondary);">No input VAT data available</td>
                <td style="padding: 12px 14px; text-align: right; font-family: 'DM Mono', monospace; color: var(--fin-text-muted);">—</td>
              </tr>
            </table>
            <div class="stats-row" style="margin-top: 20px;">
              <div class="stat-box"><p class="stat-label">Total Revenue</p><p class="stat-value blue">${fmt(totalRevenue)}</p></div>
              <div class="stat-box"><p class="stat-label">VATable Base</p><p class="stat-value blue">${fmt(vatableBase)}</p></div>
              <div class="stat-box"><p class="stat-label">Output VAT</p><p class="stat-value" style="color: var(--fin-accent-orange);">${fmt(vat)}</p></div>
            </div>`;
        }

        document.getElementById('financialContent').innerHTML = `<div style="padding: 20px;">${content}</div>`;
      } else {
        document.getElementById('financialContent').innerHTML = `
          <div style="padding: 48px 24px; text-align: center;">
            <p style="color: var(--fin-text-muted);">Error loading financial data</p>
          </div>
        `;
      }
    } catch (e) {
      console.error('Error:', e);
      document.getElementById('financialContent').innerHTML = `
        <div style="padding: 48px 24px; text-align: center;">
          <p style="color: var(--fin-text-muted);">Error loading financial data</p>
        </div>
      `;
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
            <div class="report-icon"><i class="fa-solid fa-money-bill-wave"></i></div>
            <div>
              <div class="report-name">Profit & Loss</div>
              <div class="report-desc">Income vs Expenses</div>
            </div>
          </div>
          <div class="report-item">
            <div class="report-icon" style="background: var(--fin-bg-main); color: var(--fin-text-secondary);"><i class="fa-solid fa-scale-balanced"></i></div>
            <div>
              <div class="report-name">Balance Sheet</div>
              <div class="report-desc">Assets, Liabilities, Equity</div>
            </div>
          </div>
          <div class="report-item">
            <div class="report-icon" style="background: var(--fin-bg-main); color: var(--fin-text-secondary);"><i class="fa-solid fa-arrow-trend-up"></i></div>
            <div>
              <div class="report-name">Cash Flow</div>
              <div class="report-desc">Operating, Investing, Financing</div>
            </div>
          </div>
          <div class="report-item">
            <div class="report-icon" style="background: var(--fin-bg-main); color: var(--fin-text-secondary);"><i class="fa-solid fa-chart-pie"></i></div>
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
