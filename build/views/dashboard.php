<!-- Dashboard Page Content -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
<style>
    /* ── Dashboard Design System ── */
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
        --dash-accent-cyan: #06b6d4;
        --dash-shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --dash-shadow-md: 0 4px 16px rgba(0,0,0,.08);
        --dash-radius: 16px;
        --dash-radius-sm: 10px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--dash-text-primary);
    }

    .dash-content { padding: 0; display: flex; flex-direction: column; gap: 24px; }

    /* ── STAT CARDS ── */
    .dash-page .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .dash-page .stat-card {
      background: var(--dash-bg-card);
      border-radius: var(--dash-radius);
      padding: 20px;
      box-shadow: var(--dash-shadow-sm);
      border: 1px solid var(--dash-border);
      display: flex; flex-direction: column; gap: 14px;
      transition: box-shadow .2s, transform .2s;
      position: relative; overflow: hidden;
    }
    .dash-page .stat-card:hover { box-shadow: var(--dash-shadow-md); transform: translateY(-2px); }
    .dash-page .stat-card::after {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
      border-radius: var(--dash-radius) var(--dash-radius) 0 0;
    }
    .dash-page .stat-card.blue::after   { background: linear-gradient(90deg, #4f46e5, #8b5cf6); }
    .dash-page .stat-card.rose::after   { background: linear-gradient(90deg, #f43f5e, #fb7185); }
    .dash-page .stat-card.green::after  { background: linear-gradient(90deg, #10b981, #34d399); }
    .dash-page .stat-card.orange::after { background: linear-gradient(90deg, #f97316, #fbbf24); }

    .dash-page .stat-header { display: flex; align-items: center; justify-content: space-between; }
    .dash-page .stat-label { font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--dash-text-muted); }
    .dash-page .stat-icon {
      width: 44px; height: 44px;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 20px;
    }
    .dash-page .stat-icon.blue   { background: linear-gradient(135deg,#4f46e5,#8b5cf6); }
    .dash-page .stat-icon.rose   { background: linear-gradient(135deg,#f43f5e,#fb923c); }
    .dash-page .stat-icon.green  { background: linear-gradient(135deg,#10b981,#34d399); }
    .dash-page .stat-icon.orange { background: linear-gradient(135deg,#f97316,#fbbf24); }

    .dash-page .stat-value { font-size: 26px; font-weight: 800; color: var(--dash-text-primary); font-family: 'DM Mono', monospace; }
    .dash-page .stat-footer { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--dash-text-muted); }
    .dash-page .stat-badge { font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 2px; }
    .dash-page .stat-badge.up   { color: var(--dash-accent-emerald); }
    .dash-page .stat-badge.down { color: var(--dash-accent-rose); }

    /* ── ROW 2 ── */
    .dash-page .row2 { display: grid; grid-template-columns: 7fr 5fr; gap: 16px; }

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
      display: flex; align-items: center; justify-content: space-between;
    }
    .dash-page .card-title { font-size: 15px; font-weight: 700; color: var(--dash-text-primary); }
    .dash-page .card-subtitle { font-size: 12px; color: var(--dash-text-muted); margin-top: 2px; }
    .dash-page .card-body { padding: 20px; }
    .dash-page .card-tag {
      font-size: 11px; font-weight: 700;
      padding: 4px 10px; border-radius: 20px;
      background: var(--dash-accent-blue-light);
      color: var(--dash-accent-blue);
    }

    /* ── CHART AREA ── */
    .dash-page .chart-wrap { position: relative; height: 260px; }

    /* ── DONUT CHART CARD ── */
    .dash-page .donut-wrap { display: flex; flex-direction: column; align-items: center; gap: 20px; }
    .dash-page .donut-canvas-wrap { position: relative; width: 200px; height: 200px; }
    .dash-page .donut-center {
      position: absolute; inset: 0;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
    }
    .dash-page .donut-center-val { font-size: 26px; font-weight: 800; color: var(--dash-text-primary); font-family: 'DM Mono', monospace; }
    .dash-page .donut-center-label { font-size: 11px; color: var(--dash-text-muted); }
    .dash-page .donut-legend { display: flex; flex-direction: column; gap: 10px; width: 100%; }
    .dash-page .legend-item { display: flex; align-items: center; justify-content: space-between; font-size: 13px; }
    .dash-page .legend-dot-label { display: flex; align-items: center; gap: 8px; }
    .dash-page .legend-dot { width: 10px; height: 10px; border-radius: 50%; }
    .dash-page .legend-val { font-weight: 700; font-family: 'DM Mono', monospace; font-size: 13px; }

    /* ── ROW 3 ── */
    .dash-page .row3 { display: grid; grid-template-columns: 7fr 5fr; gap: 16px; }

    /* ── SALES BY COUNTRY ── */
    .dash-page .country-table { width: 100%; border-collapse: collapse; }
    .dash-page .country-table th {
      font-size: 11px; font-weight: 700;
      text-transform: uppercase; letter-spacing: .08em;
      color: var(--dash-text-muted);
      text-align: left; padding: 0 16px 12px;
    }
    .dash-page .country-table td {
      padding: 12px 16px;
      border-top: 1px solid var(--dash-border);
      font-size: 13px; color: var(--dash-text-primary);
      vertical-align: middle;
    }
    .dash-page .country-name { font-weight: 600; }
    .dash-page .country-flag { font-size: 24px; margin-right: 12px; }
    .dash-page .mono { font-family: 'DM Mono', monospace; font-size: 13px; }
    .dash-page .trend-badge {
      display: inline-flex; align-items: center; gap: 2px;
      font-size: 11px; font-weight: 700;
      padding: 3px 8px; border-radius: 20px;
    }
    .dash-page .trend-badge.up   { background: #d1fae5; color: #059669; }
    .dash-page .trend-badge.down { background: #fee2e2; color: #dc2626; }

    /* ── CATEGORIES ── */
    .dash-page .category-list { display: flex; flex-direction: column; gap: 4px; }
    .dash-page .category-item {
      display: flex; align-items: center; justify-content: space-between;
      padding: 12px 14px;
      border-radius: var(--dash-radius-sm);
      transition: background .18s;
      cursor: pointer;
    }
    .dash-page .category-item:hover { background: var(--dash-bg-main); }
    .dash-page .cat-left { display: flex; align-items: center; gap: 12px; }
    .dash-page .cat-icon-wrap {
      width: 38px; height: 38px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 18px;
    }
    .dash-page .cat-name { font-size: 14px; font-weight: 600; color: var(--dash-text-primary); }
    .dash-page .cat-sub { font-size: 11px; color: var(--dash-text-muted); }
    .dash-page .cat-arrow { color: var(--dash-text-muted); font-size: 14px; transition: transform .18s; }
    .dash-page .category-item:hover .cat-arrow { transform: translateX(4px); color: var(--dash-accent-blue); }

    /* ── ROW 4 – RECENT ORDERS ── */
    .dash-page .orders-table { width: 100%; border-collapse: collapse; }
    .dash-page .orders-table th {
      font-size: 11px; font-weight: 700;
      text-transform: uppercase; letter-spacing: .08em;
      color: var(--dash-text-muted);
      text-align: left; padding: 0 16px 12px;
    }
    .dash-page .orders-table td {
      padding: 14px 16px;
      border-top: 1px solid var(--dash-border);
      font-size: 13px; color: var(--dash-text-primary);
      vertical-align: middle;
    }
    .dash-page .order-status {
      display: inline-block;
      font-size: 11px; font-weight: 700;
      padding: 3px 10px; border-radius: 20px;
    }
    .dash-page .order-status.delivered  { background: #d1fae5; color: #059669; }
    .dash-page .order-status.pending    { background: #fef3c7; color: #d97706; }
    .dash-page .order-status.cancelled  { background: #fee2e2; color: #dc2626; }
    .dash-page .order-status.processing { background: #dbeafe; color: #2563eb; }
    .dash-page .customer-avatar {
      width: 30px; height: 30px; border-radius: 50%;
      background: linear-gradient(135deg, #4f46e5, #8b5cf6);
      display: inline-flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 700; color: #fff; margin-right: 8px;
    }

    /* ── ROW 5 – ACTIVITY + QUICK ACTIONS ── */
    .dash-page .row5 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }

    /* ── ACTIVITY FEED ── */
    .dash-page .activity-list { display: flex; flex-direction: column; }
    .dash-page .activity-item {
      display: flex; gap: 12px;
      padding: 12px 0;
      border-bottom: 1px solid var(--dash-border);
    }
    .dash-page .activity-item:last-child { border-bottom: none; }
    .dash-page .activity-dot-wrap { display: flex; flex-direction: column; align-items: center; }
    .dash-page .activity-dot {
      width: 32px; height: 32px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px; flex-shrink: 0;
    }
    .dash-page .activity-line { width: 2px; flex: 1; background: var(--dash-border); margin-top: 4px; }
    .dash-page .activity-content { flex: 1; }
    .dash-page .activity-text { font-size: 13px; color: var(--dash-text-primary); line-height: 1.5; }
    .dash-page .activity-text strong { font-weight: 700; }
    .dash-page .activity-time { font-size: 11px; color: var(--dash-text-muted); margin-top: 3px; }

    /* ── QUICK ACTIONS ── */
    .dash-page .quick-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .dash-page .quick-btn {
      display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;
      padding: 16px 12px;
      background: var(--dash-bg-main);
      border: 1.5px solid var(--dash-border);
      border-radius: var(--dash-radius-sm);
      cursor: pointer;
      transition: all .18s;
      font-size: 12px; font-weight: 600; color: var(--dash-text-secondary);
    }
    .dash-page .quick-btn:hover { border-color: var(--dash-accent-blue); background: var(--dash-accent-blue-light); color: var(--dash-accent-blue); }
    .dash-page .quick-btn span { font-size: 24px; }

    /* ── TOP PRODUCTS ── */
    .dash-page .product-list { display: flex; flex-direction: column; gap: 14px; }
    .dash-page .product-item { display: flex; align-items: center; gap: 12px; }
    .dash-page .product-rank { font-size: 13px; font-weight: 800; color: var(--dash-text-muted); width: 20px; font-family: 'DM Mono', monospace; }
    .dash-page .product-img {
      width: 42px; height: 42px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 22px;
      background: var(--dash-bg-main);
      border: 1px solid var(--dash-border);
    }
    .dash-page .product-info { flex: 1; }
    .dash-page .product-name { font-size: 13px; font-weight: 600; color: var(--dash-text-primary); }
    .dash-page .product-cat  { font-size: 11px; color: var(--dash-text-muted); }
    .dash-page .product-rev  { font-size: 13px; font-weight: 700; font-family: 'DM Mono', monospace; color: var(--dash-accent-blue); }

    /* ── RESPONSIVE ── */
    @media (max-width: 1100px) {
      .dash-page .stat-grid { grid-template-columns: repeat(2,1fr); }
      .dash-page .row2, .dash-page .row3 { grid-template-columns: 1fr; }
      .dash-page .row5 { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
      .dash-page .stat-grid { grid-template-columns: 1fr 1fr; }
    }

    /* ── ANIMATIONS ── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(18px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .dash-page .stat-card  { animation: fadeUp .5s both; }
    .dash-page .stat-card:nth-child(1) { animation-delay: .05s; }
    .dash-page .stat-card:nth-child(2) { animation-delay: .10s; }
    .dash-page .stat-card:nth-child(3) { animation-delay: .15s; }
    .dash-page .stat-card:nth-child(4) { animation-delay: .20s; }
</style>

<!-- PAGE CONTENT WRAPPER -->
<div class="dash-page">
<div class="dash-content">

    <!-- ── ROW 1 – STAT CARDS ── -->
    <div class="stat-grid">

      <!-- Today's Money -->
      <div class="stat-card blue">
        <div class="stat-header">
          <span class="stat-label">Today's Money</span>
          <div class="stat-icon blue">💰</div>
        </div>
        <div class="stat-value">$53,000</div>
        <div class="stat-footer">
          <span class="stat-badge up">▲ +55%</span>
          since yesterday
        </div>
      </div>

      <!-- Today's Users -->
      <div class="stat-card rose">
        <div class="stat-header">
          <span class="stat-label">Today's Users</span>
          <div class="stat-icon rose">🌐</div>
        </div>
        <div class="stat-value">2,300</div>
        <div class="stat-footer">
          <span class="stat-badge up">▲ +3%</span>
          since last week
        </div>
      </div>

      <!-- New Clients -->
      <div class="stat-card green">
        <div class="stat-header">
          <span class="stat-label">New Clients</span>
          <div class="stat-icon green">📋</div>
        </div>
        <div class="stat-value">+3,462</div>
        <div class="stat-footer">
          <span class="stat-badge down">▼ -2%</span>
          since last quarter
        </div>
      </div>

      <!-- Sales -->
      <div class="stat-card orange">
        <div class="stat-header">
          <span class="stat-label">Sales</span>
          <div class="stat-icon orange">🛒</div>
        </div>
        <div class="stat-value">$103,430</div>
        <div class="stat-footer">
          <span class="stat-badge up">▲ +5%</span>
          than last month
        </div>
      </div>
    </div>

    <!-- ── ROW 2 – SALES OVERVIEW + TRAFFIC DONUT ── -->
    <div class="row2">

      <!-- Sales Overview Line Chart -->
      <div class="card">
        <div class="card-header">
          <div>
            <div class="card-title">Sales Overview</div>
            <div class="card-subtitle">▲ <strong>4% more</strong> than last year</div>
          </div>
          <div style="display:flex;gap:8px;align-items:center;">
            <select id="chartRange" style="font-family:inherit;font-size:12px;border:1.5px solid var(--dash-border);background:var(--dash-bg-main);color:var(--dash-text-primary);border-radius:8px;padding:5px 10px;cursor:pointer;outline:none;">
              <option>Last 6 Months</option>
              <option>Last 12 Months</option>
              <option>This Year</option>
            </select>
            <span class="card-tag">Live</span>
          </div>
        </div>
        <div class="card-body">
          <div class="chart-wrap">
            <canvas id="dashboard-chart-line"></canvas>
          </div>
        </div>
      </div>

      <!-- Traffic Donut -->
      <div class="card">
        <div class="card-header">
          <div>
            <div class="card-title">Traffic Sources</div>
            <div class="card-subtitle">Breakdown by channel</div>
          </div>
          <span class="card-tag">Monthly</span>
        </div>
        <div class="card-body">
          <div class="donut-wrap">
            <div class="donut-canvas-wrap">
              <canvas id="dashboard-chart-donut" width="200" height="200"></canvas>
              <div class="donut-center">
                <div class="donut-center-val">68%</div>
                <div class="donut-center-label">Organic</div>
              </div>
            </div>
            <div class="donut-legend">
              <div class="legend-item">
                <div class="legend-dot-label"><div class="legend-dot" style="background:#4f46e5"></div>Organic</div>
                <div class="legend-val">68%</div>
              </div>
              <div class="legend-item">
                <div class="legend-dot-label"><div class="legend-dot" style="background:#8b5cf6"></div>Referral</div>
                <div class="legend-val">15%</div>
              </div>
              <div class="legend-item">
                <div class="legend-dot-label"><div class="legend-dot" style="background:#06b6d4"></div>Social</div>
                <div class="legend-val">10%</div>
              </div>
              <div class="legend-item">
                <div class="legend-dot-label"><div class="legend-dot" style="background:#f97316"></div>Direct</div>
                <div class="legend-val">7%</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── ROW 3 – SALES BY COUNTRY + CATEGORIES ── -->
    <div class="row3">

      <!-- Sales by Country -->
      <div class="card">
        <div class="card-header">
          <div>
            <div class="card-title">Sales by Country</div>
            <div class="card-subtitle">Top performing regions</div>
          </div>
          <span class="card-tag">This Month</span>
        </div>
        <div class="card-body" style="padding:0 0 8px;">
          <table class="country-table">
            <thead>
              <tr>
                <th>Country</th>
                <th>Sales</th>
                <th>Value</th>
                <th>Bounce</th>
                <th>Trend</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><span class="country-flag">🇺🇸</span><span class="country-name">United States</span></td>
                <td class="mono">2,500</td>
                <td class="mono">$230,900</td>
                <td class="mono">29.9%</td>
                <td><span class="trend-badge up">▲ +12%</span></td>
              </tr>
              <tr>
                <td><span class="country-flag">🇩🇪</span><span class="country-name">Germany</span></td>
                <td class="mono">3,900</td>
                <td class="mono">$440,000</td>
                <td class="mono">40.2%</td>
                <td><span class="trend-badge up">▲ +7%</span></td>
              </tr>
              <tr>
                <td><span class="country-flag">🇬🇧</span><span class="country-name">Great Britain</span></td>
                <td class="mono">1,400</td>
                <td class="mono">$190,700</td>
                <td class="mono">23.4%</td>
                <td><span class="trend-badge down">▼ -3%</span></td>
              </tr>
              <tr>
                <td><span class="country-flag">🇧🇷</span><span class="country-name">Brazil</span></td>
                <td class="mono">562</td>
                <td class="mono">$143,960</td>
                <td class="mono">32.1%</td>
                <td><span class="trend-badge up">▲ +21%</span></td>
              </tr>
              <tr>
                <td><span class="country-flag">🇯🇵</span><span class="country-name">Japan</span></td>
                <td class="mono">1,120</td>
                <td class="mono">$178,300</td>
                <td class="mono">18.7%</td>
                <td><span class="trend-badge up">▲ +9%</span></td>
              </tr>
              <tr>
                <td><span class="country-flag">🇦🇺</span><span class="country-name">Australia</span></td>
                <td class="mono">840</td>
                <td class="mono">$112,500</td>
                <td class="mono">27.5%</td>
                <td><span class="trend-badge down">▼ -1%</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Categories -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">Categories</div>
          <span class="card-tag">4 Active</span>
        </div>
        <div class="card-body" style="padding:12px 8px;">
          <div class="category-list">
            <div class="category-item">
              <div class="cat-left">
                <div class="cat-icon-wrap" style="background:linear-gradient(135deg,#1e293b,#334155)">📱</div>
                <div>
                  <div class="cat-name">Devices</div>
                  <div class="cat-sub">250 in stock · <strong>346+ sold</strong></div>
                </div>
              </div>
              <span class="cat-arrow">›</span>
            </div>
            <div class="category-item">
              <div class="cat-left">
                <div class="cat-icon-wrap" style="background:linear-gradient(135deg,#1e293b,#334155)">🎫</div>
                <div>
                  <div class="cat-name">Tickets</div>
                  <div class="cat-sub">123 closed · <strong>15 open</strong></div>
                </div>
              </div>
              <span class="cat-arrow">›</span>
            </div>
            <div class="category-item">
              <div class="cat-left">
                <div class="cat-icon-wrap" style="background:linear-gradient(135deg,#1e293b,#334155)">⚠️</div>
                <div>
                  <div class="cat-name">Error Logs</div>
                  <div class="cat-sub">1 active · <strong>40 closed</strong></div>
                </div>
              </div>
              <span class="cat-arrow">›</span>
            </div>
            <div class="category-item">
              <div class="cat-left">
                <div class="cat-icon-wrap" style="background:linear-gradient(135deg,#1e293b,#334155)">😊</div>
                <div>
                  <div class="cat-name">Happy Users</div>
                  <div class="cat-sub"><strong>+430</strong> this month</div>
                </div>
              </div>
              <span class="cat-arrow">›</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── ROW 4 – RECENT ORDERS ── -->
    <div class="card">
      <div class="card-header">
        <div>
          <div class="card-title">Recent Orders</div>
          <div class="card-subtitle">Showing latest 6 transactions</div>
        </div>
        <button onclick="" style="font-family:inherit;font-size:13px;font-weight:600;color:var(--dash-accent-blue);background:var(--dash-accent-blue-light);border:none;border-radius:8px;padding:7px 14px;cursor:pointer;">View All</button>
      </div>
      <div style="overflow-x:auto;">
        <table class="orders-table">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Product</th>
              <th>Date</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="mono">#ORD-0041</td>
              <td><div style="display:flex;align-items:center;"><div class="customer-avatar">AM</div>Alice Martin</div></td>
              <td>iPhone 15 Pro</td>
              <td>May 27, 2026</td>
              <td class="mono">$1,299</td>
              <td><span class="order-status delivered">Delivered</span></td>
              <td><a href="#" style="font-size:12px;color:var(--dash-accent-blue);font-weight:600;">Details</a></td>
            </tr>
            <tr>
              <td class="mono">#ORD-0040</td>
              <td><div style="display:flex;align-items:center;"><div class="customer-avatar" style="background:linear-gradient(135deg,#10b981,#34d399);">BK</div>Bob Kim</div></td>
              <td>MacBook Air M3</td>
              <td>May 26, 2026</td>
              <td class="mono">$1,099</td>
              <td><span class="order-status processing">Processing</span></td>
              <td><a href="#" style="font-size:12px;color:var(--dash-accent-blue);font-weight:600;">Details</a></td>
            </tr>
            <tr>
              <td class="mono">#ORD-0039</td>
              <td><div style="display:flex;align-items:center;"><div class="customer-avatar" style="background:linear-gradient(135deg,#f43f5e,#fb7185);">CL</div>Clara Lee</div></td>
              <td>AirPods Pro</td>
              <td>May 25, 2026</td>
              <td class="mono">$249</td>
              <td><span class="order-status delivered">Delivered</span></td>
              <td><a href="#" style="font-size:12px;color:var(--dash-accent-blue);font-weight:600;">Details</a></td>
            </tr>
            <tr>
              <td class="mono">#ORD-0038</td>
              <td><div style="display:flex;align-items:center;"><div class="customer-avatar" style="background:linear-gradient(135deg,#f97316,#fbbf24);">DW</div>David Wu</div></td>
              <td>iPad Pro 12.9"</td>
              <td>May 24, 2026</td>
              <td class="mono">$1,799</td>
              <td><span class="order-status pending">Pending</span></td>
              <td><a href="#" style="font-size:12px;color:var(--dash-accent-blue);font-weight:600;">Details</a></td>
            </tr>
            <tr>
              <td class="mono">#ORD-0037</td>
              <td><div style="display:flex;align-items:center;"><div class="customer-avatar" style="background:linear-gradient(135deg,#06b6d4,#0ea5e9);">EP</div>Eva Park</div></td>
              <td>Apple Watch Ultra 2</td>
              <td>May 23, 2026</td>
              <td class="mono">$799</td>
              <td><span class="order-status cancelled">Cancelled</span></td>
              <td><a href="#" style="font-size:12px;color:var(--dash-accent-blue);font-weight:600;">Details</a></td>
            </tr>
            <tr>
              <td class="mono">#ORD-0036</td>
              <td><div style="display:flex;align-items:center;"><div class="customer-avatar" style="background:linear-gradient(135deg,#8b5cf6,#a78bfa);">FJ</div>Frank Jones</div></td>
              <td>Mac Mini M4</td>
              <td>May 22, 2026</td>
              <td class="mono">$599</td>
              <td><span class="order-status delivered">Delivered</span></td>
              <td><a href="#" style="font-size:12px;color:var(--dash-accent-blue);font-weight:600;">Details</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ── ROW 5 – ACTIVITY FEED + QUICK ACTIONS + TOP PRODUCTS ── -->
    <div class="row5">

      <!-- Activity Feed -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">Recent Activity</div>
          <span class="card-tag">Live</span>
        </div>
        <div class="card-body" style="padding-top:8px;">
          <div class="activity-list">
            <div class="activity-item">
              <div class="activity-dot-wrap">
                <div class="activity-dot" style="background:#eef2ff;">🛒</div>
                <div class="activity-line"></div>
              </div>
              <div class="activity-content">
                <div class="activity-text"><strong>Alice Martin</strong> placed a new order for iPhone 15 Pro</div>
                <div class="activity-time">2 minutes ago</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-dot-wrap">
                <div class="activity-dot" style="background:#d1fae5;">✅</div>
                <div class="activity-line"></div>
              </div>
              <div class="activity-content">
                <div class="activity-text">Order <strong>#ORD-0036</strong> was successfully delivered</div>
                <div class="activity-time">18 minutes ago</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-dot-wrap">
                <div class="activity-dot" style="background:#fef3c7;">👤</div>
                <div class="activity-line"></div>
              </div>
              <div class="activity-content">
                <div class="activity-text"><strong>47 new users</strong> signed up today</div>
                <div class="activity-time">1 hour ago</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-dot-wrap">
                <div class="activity-dot" style="background:#fee2e2;">⚠️</div>
                <div class="activity-line"></div>
              </div>
              <div class="activity-content">
                <div class="activity-text">Server error log resolved — <strong>1 active</strong> remaining</div>
                <div class="activity-time">3 hours ago</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-dot-wrap">
                <div class="activity-dot" style="background:#ede9fe;">📊</div>
              </div>
              <div class="activity-content">
                <div class="activity-text">Monthly <strong>sales report</strong> is ready for review</div>
                <div class="activity-time">Yesterday at 11:30 PM</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">Quick Actions</div>
        </div>
        <div class="card-body">
          <div class="quick-grid">
            <div class="quick-btn"><span>➕</span>New Order</div>
            <div class="quick-btn"><span>👤</span>Add Client</div>
            <div class="quick-btn"><span>📦</span>Add Product</div>
            <div class="quick-btn"><span>📤</span>Export CSV</div>
            <div class="quick-btn"><span>📩</span>Send Email</div>
            <div class="quick-btn"><span>🔍</span>Run Report</div>
          </div>
        </div>
      </div>

      <!-- Top Products -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">Top Products</div>
          <span class="card-tag">May 2026</span>
        </div>
        <div class="card-body">
          <div class="product-list">
            <div class="product-item">
              <div class="product-rank">#1</div>
              <div class="product-img">📱</div>
              <div class="product-info">
                <div class="product-name">iPhone 15 Pro</div>
                <div class="product-cat">Devices · 842 sold</div>
              </div>
              <div class="product-rev">$28,900</div>
            </div>
            <div class="product-item">
              <div class="product-rank">#2</div>
              <div class="product-img">💻</div>
              <div class="product-info">
                <div class="product-name">MacBook Air M3</div>
                <div class="product-cat">Devices · 610 sold</div>
              </div>
              <div class="product-rev">$22,400</div>
            </div>
            <div class="product-item">
              <div class="product-rank">#3</div>
              <div class="product-img">⌚</div>
              <div class="product-info">
                <div class="product-name">Apple Watch Ultra 2</div>
                <div class="product-cat">Devices · 430 sold</div>
              </div>
              <div class="product-rev">$17,100</div>
            </div>
            <div class="product-item">
              <div class="product-rank">#4</div>
              <div class="product-img">🎧</div>
              <div class="product-info">
                <div class="product-name">AirPods Pro</div>
                <div class="product-cat">Accessories · 980 sold</div>
              </div>
              <div class="product-rev">$12,250</div>
            </div>
            <div class="product-item">
              <div class="product-rank">#5</div>
              <div class="product-img">🖥️</div>
              <div class="product-info">
                <div class="product-name">iPad Pro 12.9"</div>
                <div class="product-cat">Devices · 310 sold</div>
              </div>
              <div class="product-rev">$9,800</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── ROW 6 – BAR CHART: MONTHLY REVENUE ── -->
    <div class="card">
      <div class="card-header">
        <div>
          <div class="card-title">Monthly Revenue vs Expenses</div>
          <div class="card-subtitle">Full year comparison — 2026</div>
        </div>
        <span class="card-tag">YTD</span>
      </div>
      <div class="card-body">
        <div class="chart-wrap" style="height:240px;">
          <canvas id="dashboard-chart-bar"></canvas>
        </div>
      </div>
    </div>

  </div><!-- /dash-content -->
</div><!-- /dash-page -->

<script>
/* ─────────────────────────────────────────
   CHART INITIALIZATION
───────────────────────────────────────── */

/* ─────────────────────────────────────────
   LINE CHART – SALES OVERVIEW
───────────────────────────────────────── */
let lineChart;
function buildLineChart() {
  const canvas = document.getElementById('dashboard-chart-line');
  if (!canvas) return;
  
  // Destroy any existing chart instance
  const existingChart = Chart.getChart(canvas);
  if (existingChart) {
    existingChart.destroy();
  }
  
  const ctx = canvas.getContext('2d');
  const gridColor = 'rgba(0,0,0,0.05)';
  const textColor = '#6b7280';

  lineChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Dec','Jan','Feb','Mar','Apr','May'],
      datasets: [
        {
          label: 'Revenue',
          data: [62000, 75000, 68000, 90000, 85000, 103000],
          borderColor: '#4f46e5',
          backgroundColor: 'rgba(79,70,229,0.12)',
          fill: true, tension: 0.4, borderWidth: 2.5, pointRadius: 4,
          pointBackgroundColor: '#4f46e5'
        },
        {
          label: 'Expenses',
          data: [38000, 42000, 40000, 55000, 50000, 61000],
          borderColor: '#8b5cf6',
          backgroundColor: 'rgba(139,92,246,0.06)',
          fill: true, tension: 0.4, borderWidth: 2, borderDash: [6,3],
          pointRadius: 4, pointBackgroundColor: '#8b5cf6'
        }
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: {
        legend: { position: 'top', align: 'end',
          labels: { color: textColor, font: { size: 12, family: 'Plus Jakarta Sans' }, boxWidth: 12, boxHeight: 12 }
        },
        tooltip: { mode: 'index', intersect: false,
          callbacks: { label: ctx => ' $' + ctx.raw.toLocaleString() }
        }
      },
      scales: {
        x: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 12 } } },
        y: {
          grid: { color: gridColor },
          ticks: { color: textColor, font: { size: 12 },
            callback: v => '$' + (v/1000) + 'k' }
        }
      }
    }
  });
}

/* ─────────────────────────────────────────
   DONUT CHART – TRAFFIC SOURCES
───────────────────────────────────────── */
let donutChart;
function buildDonutChart() {
  const canvas = document.getElementById('dashboard-chart-donut');
  if (!canvas) return;
  
  // Destroy any existing chart instance
  const existingChart = Chart.getChart(canvas);
  if (existingChart) {
    existingChart.destroy();
  }
  
  const ctx = canvas.getContext('2d');
  donutChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Organic','Referral','Social','Direct'],
      datasets: [{
        data: [68, 15, 10, 7],
        backgroundColor: ['#4f46e5','#8b5cf6','#06b6d4','#f97316'],
        borderWidth: 0, hoverOffset: 8
      }]
    },
    options: {
      responsive: false,
      cutout: '70%',
      plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': ' + ctx.raw + '%' } } },
      animation: { animateRotate: true, duration: 900 }
    }
  });
}

/* ─────────────────────────────────────────
   BAR CHART – MONTHLY REVENUE
───────────────────────────────────────── */
let barChart;
function buildBarChart() {
  const canvas = document.getElementById('dashboard-chart-bar');
  if (!canvas) return;
  
  // Destroy any existing chart instance
  const existingChart = Chart.getChart(canvas);
  if (existingChart) {
    existingChart.destroy();
  }
  
  const ctx = canvas.getContext('2d');
  const gridColor = 'rgba(0,0,0,0.05)';
  const textColor = '#6b7280';
  barChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
      datasets: [
        {
          label: 'Revenue',
          data: [75000, 68000, 90000, 85000, 103000, 0, 0, 0, 0, 0, 0, 0],
          backgroundColor: '#4f46e5cc',
          borderRadius: 6, borderSkipped: false
        },
        {
          label: 'Expenses',
          data: [42000, 40000, 55000, 50000, 61000, 0, 0, 0, 0, 0, 0, 0],
          backgroundColor: '#8b5cf688',
          borderRadius: 6, borderSkipped: false
        }
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: {
        legend: { position: 'top', align: 'end',
          labels: { color: textColor, font: { size: 12, family: 'Plus Jakarta Sans' }, boxWidth: 12 }
        },
        tooltip: { mode: 'index', intersect: false,
          callbacks: { label: ctx => ' $' + ctx.raw.toLocaleString() }
        }
      },
      scales: {
        x: { grid: { display: false }, ticks: { color: textColor, font: { size: 12 } } },
        y: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 12 },
            callback: v => '$' + (v/1000) + 'k' } }
      }
    }
  });
}

let chartsInitialized = false;

function initCharts() {
  if (chartsInitialized) return;
  chartsInitialized = true;

  buildLineChart();
  buildDonutChart();
  buildBarChart();

  // Chart range selector
  const chartRange = document.getElementById('chartRange');
  if (chartRange) {
    chartRange.addEventListener('change', buildLineChart);
  }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initCharts);
} else {
  initCharts();
}

</script>