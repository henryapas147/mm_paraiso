<!-- Dashboard Page Content -->
<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$basePath = dirname($_SERVER['SCRIPT_NAME']);
$defaultProfilePath = $basePath . '/assets/images/default_profile.jpg';
?>
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
    .dash-page .stat-card.red::after    { background: linear-gradient(90deg, #ef4444, #f87171); }

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
    .dash-page .stat-icon.red    { background: linear-gradient(135deg,#ef4444,#f87171); }

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
    .dash-page .donut-canvas-wrap { position: relative; width: 200px; height: 200px; flex-shrink: 0; max-width: 100%; }
    .dash-page .donut-canvas-wrap canvas { width: 100% !important; height: auto !important; max-height: 200px; }
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
      overflow: hidden;
    }
    .dash-page .product-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .dash-page .product-info { flex: 1; }
    .dash-page .product-name { font-size: 13px; font-weight: 600; color: var(--dash-text-primary); }
    .dash-page .product-cat  { font-size: 11px; color: var(--dash-text-muted); }
    .dash-page .product-rev  { font-size: 13px; font-weight: 700; font-family: 'DM Mono', monospace; color: var(--dash-accent-blue); }

    /* ── RESPONSIVE ── */
    @media (max-width: 1400px) {
      .dash-page .stat-grid { gap: 12px; }
      .dash-page .stat-card { padding: 16px; }
      .dash-page .stat-value { font-size: 22px; }
      .dash-page .card-header { padding: 14px 16px 12px; }
      .dash-page .card-body { padding: 16px; }
      .dash-page .chart-wrap { height: 220px; }
    }

    @media (max-width: 1200px) {
      .dash-page .row2, .dash-page .row3 { grid-template-columns: 1fr; }
      .dash-page .donut-canvas-wrap { width: 180px; height: 180px; max-width: 180px; }
      .dash-page .donut-center-val { font-size: 22px; }
    }

    @media (max-width: 1100px) {
      .dash-page .stat-grid { grid-template-columns: repeat(2,1fr); }
      .dash-page .row5 { grid-template-columns: 1fr; }
      .dash-page .quick-grid { grid-template-columns: repeat(3,1fr); }
    }

    @media (max-width: 992px) {
      .dash-page .stat-card { padding: 14px; }
      .dash-page .stat-icon { width: 38px; height: 38px; font-size: 18px; }
      .dash-page .stat-value { font-size: 20px; }
      .dash-page .card-title { font-size: 14px; }
      .dash-page .card-subtitle { font-size: 11px; }
      .dash-page .chart-wrap { height: 200px; }
      .dash-page .donut-canvas-wrap { width: 160px; height: 160px; max-width: 160px; }
      .dash-page .donut-center-val { font-size: 20px; }
      .dash-page .country-table th, .dash-page .orders-table th { font-size: 10px; padding: 0 12px 10px; }
      .dash-page .country-table td, .dash-page .orders-table td { font-size: 12px; padding: 10px 12px; }
    }

    @media (max-width: 768px) {
      .dash-page .stat-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
      .dash-page .stat-card { padding: 12px; }
      .dash-page .stat-icon { width: 34px; height: 34px; font-size: 16px; }
      .dash-page .stat-value { font-size: 18px; }
      .dash-page .stat-label { font-size: 10px; }
      .dash-page .card { border-radius: 12px; }
      .dash-page .card-header { padding: 12px 14px 10px; }
      .dash-page .card-body { padding: 14px; }
      .dash-page .card-title { font-size: 13px; }
      .dash-page .card-subtitle { font-size: 10px; }
      .dash-page .card-tag { font-size: 10px; padding: 3px 8px; }
      .dash-page .chart-wrap { height: 180px; }
      .dash-page .donut-wrap { gap: 14px; }
      .dash-page .donut-canvas-wrap { width: 140px; height: 140px; max-width: 140px; }
      .dash-page .donut-center-val { font-size: 18px; }
      .dash-page .donut-center-label { font-size: 10px; }
      .dash-page .legend-item { font-size: 12px; }
      .dash-page .legend-val { font-size: 12px; }
      .dash-page .country-table th, .dash-page .orders-table th { font-size: 9px; padding: 0 8px 8px; }
      .dash-page .country-table td, .dash-page .orders-table td { font-size: 11px; padding: 8px; }
      .dash-page .country-flag { font-size: 18px; margin-right: 8px; }
      .dash-page .customer-avatar { width: 26px; height: 26px; font-size: 10px; }
      .dash-page .quick-grid { grid-template-columns: repeat(2,1fr); gap: 8px; }
      .dash-page .quick-btn { padding: 12px 8px; font-size: 11px; }
      .dash-page .quick-btn span { font-size: 20px; }
      .dash-page .category-item { padding: 10px 12px; }
      .dash-page .cat-icon-wrap { width: 34px; height: 34px; font-size: 16px; }
      .dash-page .cat-name { font-size: 13px; }
      .dash-page .cat-sub { font-size: 10px; }
      .dash-page .activity-dot { width: 28px; height: 28px; font-size: 12px; }
      .dash-page .activity-text { font-size: 12px; }
      .dash-page .activity-time { font-size: 10px; }
      .dash-page .product-img { width: 38px; height: 38px; font-size: 20px; }
      .dash-page .product-name { font-size: 12px; }
      .dash-page .product-cat { font-size: 10px; }
      .dash-page .product-rev { font-size: 12px; }
    }

    @media (max-width: 640px) {
      .dash-page .stat-grid { grid-template-columns: 1fr; }
      .dash-page .stat-card { padding: 14px; }
      .dash-page .stat-icon { width: 40px; height: 40px; font-size: 18px; }
      .dash-page .stat-value { font-size: 22px; }
      .dash-page .card-header { flex-direction: column; align-items: flex-start; gap: 8px; }
      .dash-page .card-header > div { width: 100%; }
      .dash-page .card-header > div:last-child { width: auto; align-self: flex-end; }
      .dash-page .chart-wrap { height: 160px; }
      .dash-page .donut-canvas-wrap { width: 160px; height: 160px; max-width: 160px; }
      .dash-page .donut-center-val { font-size: 20px; }
      .dash-page .country-table, .dash-page .orders-table { font-size: 10px; }
      .dash-page .country-table th, .dash-page .orders-table th { padding: 0 6px 6px; }
      .dash-page .country-table td, .dash-page .orders-table td { padding: 6px; }
      .dash-page .quick-grid { grid-template-columns: 1fr; }
      .dash-page .quick-btn { flex-direction: row; justify-content: flex-start; gap: 12px; padding: 12px 16px; }
      .dash-page .quick-btn span { font-size: 18px; }
    }

    @media (max-width: 480px) {
      .dash-content { gap: 16px; }
      .dash-page .stat-card { padding: 12px; }
      .dash-page .stat-icon { width: 36px; height: 36px; font-size: 16px; }
      .dash-page .stat-value { font-size: 20px; }
      .dash-page .stat-label { font-size: 9px; }
      .dash-page .card { border-radius: 10px; }
      .dash-page .card-header { padding: 10px 12px 8px; }
      .dash-page .card-body { padding: 12px; }
      .dash-page .card-title { font-size: 12px; }
      .dash-page .card-subtitle { font-size: 9px; }
      .dash-page .card-tag { font-size: 9px; padding: 2px 6px; }
      .dash-page .chart-wrap { height: 140px; }
      .dash-page .donut-canvas-wrap { width: 140px; height: 140px; max-width: 140px; }
      .dash-page .donut-center-val { font-size: 18px; }
      .dash-page .donut-center-label { font-size: 9px; }
      .dash-page .legend-item { font-size: 11px; }
      .dash-page .legend-val { font-size: 11px; }
      .dash-page .country-table th, .dash-page .orders-table th { font-size: 8px; padding: 0 4px 4px; }
      .dash-page .country-table td, .dash-page .orders-table td { font-size: 10px; padding: 4px; }
      .dash-page .country-flag { font-size: 16px; margin-right: 6px; }
      .dash-page .customer-avatar { width: 24px; height: 24px; font-size: 9px; }
      .dash-page .order-status { font-size: 9px; padding: 2px 6px; }
      .dash-page .category-item { padding: 8px 10px; }
      .dash-page .cat-icon-wrap { width: 32px; height: 32px; font-size: 14px; }
      .dash-page .cat-name { font-size: 12px; }
      .dash-page .cat-sub { font-size: 9px; }
      .dash-page .activity-dot { width: 26px; height: 26px; font-size: 11px; }
      .dash-page .activity-text { font-size: 11px; }
      .dash-page .activity-time { font-size: 9px; }
      .dash-page .product-img { width: 36px; height: 36px; font-size: 18px; }
      .dash-page .product-name { font-size: 11px; }
      .dash-page .product-cat { font-size: 9px; }
      .dash-page .product-rev { font-size: 11px; }
    }

    @media (max-width: 360px) {
      .dash-page .stat-value { font-size: 18px; }
      .dash-page .card-title { font-size: 11px; }
      .dash-page .chart-wrap { height: 120px; }
      .dash-page .donut-canvas-wrap { width: 120px; height: 120px; max-width: 120px; }
      .dash-page .donut-center-val { font-size: 16px; }
      .dash-page .quick-btn { font-size: 10px; }
      .dash-page .quick-btn span { font-size: 16px; }
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
          <span class="stat-label">Today's Revenue</span>
          <div class="stat-icon blue"><i class="fa-solid fa-peso-sign"></i></div>
        </div>
        <div class="stat-value" id="statTodayRevenue">—</div>
        <div class="stat-footer">
          <span class="stat-badge" id="statTodayRevenueBadge">loading...</span>
        </div>
      </div>

      <!-- Occupancy -->
      <div class="stat-card rose">
        <div class="stat-header">
          <span class="stat-label">Occupancy</span>
          <div class="stat-icon rose"><i class="fa-solid fa-couch"></i></div>
        </div>
        <div class="stat-value" id="statOccupancy">—</div>
        <div class="stat-footer">
          <span class="stat-badge" id="statOccupancyBadge">loading...</span>
        </div>
      </div>

      <!-- Total Customers -->
      <div class="stat-card green">
        <div class="stat-header">
          <span class="stat-label">Total Customers</span>
          <div class="stat-icon green"><i class="fa-solid fa-people-group"></i></div>
        </div>
        <div class="stat-value" id="statCustomers">—</div>
        <div class="stat-footer">
          <span class="stat-badge" id="statCustomersBadge">loading...</span>
        </div>
      </div>

      <!-- Low Stock Alert -->
      <div class="stat-card red" id="lowStockCard">
        <div class="stat-header">
          <span class="stat-label">Low Stock Alert</span>
          <div class="stat-icon red"><i class="fa-solid fa-triangle-exclamation"></i></div>
        </div>
        <div class="stat-value" id="lowStockCount">0</div>
        <div class="stat-footer">
          <span class="stat-badge down">items below threshold</span>
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
            <div class="card-subtitle" id="lineChartSubtitle">Loading revenue data...</div>
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

      <!-- Booking Sources Donut -->
      <div class="card">
        <div class="card-header">
          <div>
            <div class="card-title">Booking Sources</div>
            <div class="card-subtitle">Breakdown by channel</div>
          </div>
          <span class="card-tag">All Time</span>
        </div>
        <div class="card-body">
          <div class="donut-wrap">
            <div class="donut-canvas-wrap">
              <canvas id="dashboard-chart-donut" width="200" height="200"></canvas>
              <div class="donut-center">
                <div class="donut-center-val" id="donutCenterVal">—</div>
                <div class="donut-center-label" id="donutCenterLabel">bookings</div>
              </div>
            </div>
            <div class="donut-legend" id="donutLegend"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── ROW 3 – SALES BY COUNTRY + CATEGORIES ── -->
    <div class="row3">

      <!-- Recent Reservations table -->
      <div class="card">
        <div class="card-header">
          <div>
            <div class="card-title">Recent Reservations</div>
            <div class="card-subtitle">Latest 5 bookings</div>
          </div>
          <span class="card-tag">Live</span>
        </div>
        <div class="card-body" style="padding:0 0 8px;">
          <div style="overflow-x:auto;">
            <table class="country-table">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Guest</th>
                  <th>Check-in</th>
                  <th>Check-out</th>
                  <th>Amount</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="recentReservationsTbody">
                <tr><td colspan="6" style="text-align:center;color:var(--dash-text-muted);padding:20px;">Loading...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Inventory Categories -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">Inventory by Category</div>
          <span class="card-tag" id="invCatTag">Loading</span>
        </div>
        <div class="card-body" style="padding:12px 8px;">
          <div class="category-list" id="invCategoryList">
            <div style="text-align:center;color:var(--dash-text-muted);padding:20px;">Loading...</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── ROW 4 – RECENT POS SALES ── -->
    <div class="card">
      <div class="card-header">
        <div>
          <div class="card-title">Recent POS Sales</div>
          <div class="card-subtitle" id="posSalesSubtitle">Showing latest transactions</div>
        </div>
        <button onclick="window.location.href='inventory/pos'" style="font-family:inherit;font-size:13px;font-weight:600;color:var(--dash-accent-blue);background:var(--dash-accent-blue-light);border:none;border-radius:8px;padding:7px 14px;cursor:pointer;">Open POS</button>
      </div>
      <div style="overflow-x:auto;">
        <table class="orders-table">
          <thead>
            <tr>
              <th>Sale #</th>
              <th>Cashier</th>
              <th>Customer</th>
              <th>Items</th>
              <th>Date</th>
              <th>Amount</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="recentSalesTbody">
            <tr><td colspan="7" style="text-align:center;color:var(--dash-text-muted);padding:20px;">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ── ROW 5 – ACTIVITY FEED + QUICK ACTIONS + TOP PRODUCTS ── -->
    <div class="row5">

      <!-- Recent Inventory Transactions -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">Recent Stock Movements</div>
          <span class="card-tag">Live</span>
        </div>
        <div class="card-body" style="padding-top:8px;">
          <div class="activity-list" id="stockMovementsList">
            <div style="text-align:center;color:var(--dash-text-muted);padding:20px;">Loading...</div>
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
            <div class="quick-btn" onclick="window.location.href='inventory/pos'"><span><i class="fa-solid fa-cart-shopping"></i></span>Open POS</div>
            <div class="quick-btn" onclick="window.location.href='reservations/reservations'"><span><i class="fa-solid fa-clipboard-list"></i></span>New Booking</div>
            <div class="quick-btn" onclick="window.location.href='inventory/products'"><span><i class="fa-solid fa-boxes-stacked"></i></span>Products</div>
            <div class="quick-btn" onclick="window.location.href='inventory/purchase-orders'"><span><i class="fa-solid fa-truck"></i></span>Purchase Orders</div>
            <div class="quick-btn" onclick="window.location.href='customers/customers'"><span><i class="fa-solid fa-people-line"></i></span>Customers</div>
            <div class="quick-btn" onclick="window.location.href='inventory/inventory-transactions'"><span><i class="fa-solid fa-box"></i></span>Inventory</div>
          </div>
        </div>
      </div>

      <!-- Top Menu Items -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">Top Menu Items</div>
          <span class="card-tag">All Time</span>
        </div>
        <div class="card-body">
          <div class="product-list" id="topMenuItemsList">
            <div style="text-align:center;color:var(--dash-text-muted);padding:20px;">Loading...</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── ROW 6 – BAR CHART: MONTHLY REVENUE ── -->
    <div class="card">
      <div class="card-header">
        <div>
          <div class="card-title">Monthly Revenue vs Expenses</div>
          <div class="card-subtitle" id="barChartSubtitle">Monthly revenue — current year</div>
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
const API = 'api/';
const isLocalhost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
const DEFAULT_PROFILE_PATH = '<?php echo $defaultProfilePath; ?>';

function fmt(n) {
  return '₱' + parseFloat(n || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
function fmtShort(n) {
  n = parseFloat(n || 0);
  if (n >= 1000000) return '₱' + (n / 1000000).toFixed(1) + 'M';
  if (n >= 1000) return '₱' + (n / 1000).toFixed(1) + 'k';
  return '₱' + n.toFixed(2);
}

function normalizeProfilePhotoUrl(url) {
  if (!url) return DEFAULT_PROFILE_PATH;
  if (isLocalhost) {
    if (url.startsWith('/../uploads/profile_photos/')) return url;
    if (url.startsWith('/uploads/profile_photos/')) {
      return '/../uploads/profile_photos/' + url.split('/').pop();
    }
    if (url.startsWith('/build/assets/images/uploads/profile_photos/')) {
      return '/../uploads/profile_photos/' + url.split('/').pop();
    }
  } else {
    if (url.startsWith('/uploads/profile_photos/')) return url;
    if (url.startsWith('/../uploads/profile_photos/')) {
      return '/uploads/profile_photos/' + url.split('/').pop();
    }
    if (url.startsWith('/build/assets/images/uploads/profile_photos/')) {
      return '/uploads/profile_photos/' + url.split('/').pop();
    }
  }
  return url;
}

function normalizeFoodImageUrl(url) {
  const defaultImage = isLocalhost ? '../uploads/foods/default-food.png' : '/uploads/foods/default-food.png';
  if (!url) return defaultImage;
  if (url.startsWith('http://') || url.startsWith('https://')) return url;
  if (isLocalhost) {
    if (url.startsWith('/uploads/foods/')) {
      return '../uploads/foods/' + url.split('/').pop();
    }
    if (url.startsWith('/')) {
      return '..' + url;
    }
    return '../uploads/foods/' + url;
  } else {
    if (url.startsWith('/uploads/foods/')) return url;
    if (url.startsWith('/')) return url;
    return '/uploads/foods/' + url;
  }
}

/* ── STAT CARDS ── */
async function loadStatCards() {
  try {
    const [rev, occ, cust] = await Promise.all([
      fetch(API + 'reports.php?action=revenue_summary').then(r => r.json()),
      fetch(API + 'reports.php?action=occupancy_summary').then(r => r.json()),
      fetch(API + 'reports.php?action=customer_summary').then(r => r.json())
    ]);

    if (rev.success) {
      document.getElementById('statTodayRevenue').textContent = fmtShort(rev.data.today);
      const badge = document.getElementById('statTodayRevenueBadge');
      badge.textContent = 'This month: ' + fmtShort(rev.data.month);
      badge.className = 'stat-badge';
    }

    if (occ.success) {
      document.getElementById('statOccupancy').textContent = occ.data.today + '%';
      const badge = document.getElementById('statOccupancyBadge');
      badge.textContent = occ.data.today + '% rooms occupied';
      badge.className = 'stat-badge ' + (occ.data.today >= 50 ? 'up' : 'down');
    }

    if (cust.success) {
      document.getElementById('statCustomers').textContent = parseInt(cust.data.total).toLocaleString();
      const badge = document.getElementById('statCustomersBadge');
      badge.textContent = '+' + cust.data.new_this_month + ' this month';
      badge.className = 'stat-badge up';
    }
  } catch(e) { console.error('Stat cards error:', e); }
}

/* ── LOW STOCK ── */
async function fetchLowStockCount() {
  try {
    const res = await fetch(API + 'inventory_transactions.php?action=low_stock_count');
    const result = await res.json();
    if (result.success) {
      document.getElementById('lowStockCount').textContent = result.count;
      if (result.count > 0) {
        document.getElementById('lowStockCard').style.cursor = 'pointer';
        document.getElementById('lowStockCard').onclick = () => window.location.href = 'inventory/inventory-transactions';
      }
    }
  } catch(e) { console.error('Low stock error:', e); }
}

/* ── RECENT RESERVATIONS ── */
async function loadRecentReservations() {
  const tbody = document.getElementById('recentReservationsTbody');
  try {
    const res = await fetch(API + 'reports.php?action=recent_reservations');
    const result = await res.json();
    if (!result.success || !result.data.length) {
      tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;color:var(--dash-text-muted);padding:20px;">No reservations found</td></tr>`;
      return;
    }
    const statusColors = { confirmed:'#4f46e5', checked_in:'#10b981', checked_out:'#6b7280', cancelled:'#f43f5e', pending:'#f97316' };
    tbody.innerHTML = result.data.map(r => {
      const name = [r.first_name, r.last_name].filter(Boolean).join(' ') || 'Guest';
      const hasPhoto = r.profile_photo_url && r.profile_photo_url.trim() !== '';
      const photoUrl = hasPhoto ? normalizeProfilePhotoUrl(r.profile_photo_url) : DEFAULT_PROFILE_PATH;
      const color = statusColors[r.reservation_status] || '#6b7280';
      const statusLabel = (r.reservation_status || '').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
      return `<tr>
        <td class="mono">${r.reservation_code || '—'}</td>
        <td><div style="display:flex;align-items:center;gap:8px;"><img src="${photoUrl}" alt="" class="customer-avatar" style="object-fit:cover;" ${hasPhoto ? `onerror="this.src='${DEFAULT_PROFILE_PATH}'"` : ''}>${name}</div></td>
        <td>${r.check_in_date || '—'}</td>
        <td>${r.check_out_date || '—'}</td>
        <td class="mono">${fmt(r.total_amount)}</td>
        <td><span class="order-status" style="background:${color}20;color:${color};padding:3px 8px;border-radius:6px;font-size:11px;font-weight:600;">${statusLabel}</span></td>
      </tr>`;
    }).join('');
  } catch(e) { tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;color:var(--dash-text-muted);">Error loading data</td></tr>`; }
}

/* ── INVENTORY BY CATEGORY ── */
async function loadInventoryCategories() {
  const list = document.getElementById('invCategoryList');
  const tag = document.getElementById('invCatTag');
  try {
    const res = await fetch(API + 'reports.php?action=inventory_valuation');
    const result = await res.json();
    if (!result.success || !result.data.length) {
      list.innerHTML = `<div style="text-align:center;color:var(--dash-text-muted);padding:20px;">No categories found</div>`;
      return;
    }
    const colors = ['#4f46e5','#10b981','#f97316','#06b6d4','#8b5cf6','#f43f5e'];
    tag.textContent = result.data.length + ' Categories';
    list.innerHTML = result.data.map((cat, i) => `
      <div class="category-item">
        <div class="cat-left">
          <div class="cat-icon-wrap" style="background:${colors[i % colors.length]}22;color:${colors[i % colors.length]};font-size:16px;"><i class="fa-solid fa-box"></i></div>
          <div>
            <div class="cat-name">${cat.category_name}</div>
            <div class="cat-sub">${cat.product_count} products · <strong>${fmtShort(cat.total_value)}</strong></div>
          </div>
        </div>
        <span class="cat-arrow">›</span>
      </div>`).join('');
  } catch(e) { list.innerHTML = `<div style="text-align:center;color:var(--dash-text-muted);">Error loading data</div>`; }
}

/* ── RECENT POS SALES ── */
async function loadRecentSales() {
  const tbody = document.getElementById('recentSalesTbody');
  const subtitle = document.getElementById('posSalesSubtitle');
  try {
    const res = await fetch(API + 'inventory_sales.php?action=list&limit=6');
    const result = await res.json();
    if (!result.success || !result.data?.length) {
      tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;color:var(--dash-text-muted);padding:20px;">No sales found</td></tr>`;
      return;
    }
    const sales = result.data.slice(0, 6);
    subtitle.textContent = `Showing ${sales.length} latest transactions`;
    tbody.innerHTML = sales.map(s => {
      const date = s.sale_date ? new Date(s.sale_date).toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' }) : '—';
      const cashierName = s.staff_first_name && s.staff_last_name 
        ? `${s.staff_first_name} ${s.staff_last_name}` 
        : (s.staff_name || s.cashier_name || 'Staff');
      const customerName = s.customer_name || 'Walk-in';
      const hasPhoto = s.profile_photo_url && s.profile_photo_url.trim() !== '';
      const photoUrl = hasPhoto ? normalizeProfilePhotoUrl(s.profile_photo_url) : DEFAULT_PROFILE_PATH;
      
      // Status badge styling
      const statusColor = s.payment_status === 'paid' ? '#d1fae5' : '#fef3c7';
      const statusTextColor = s.payment_status === 'paid' ? '#059669' : '#d97706';
      const statusLabel = (s.payment_status || 'paid').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
      
      return `<tr>
        <td class="mono">#${String(s.sale_number || s.sale_id || '').slice(-8).toUpperCase()}</td>
        <td><div style="display:flex;align-items:center;gap:8px;"><img src="${photoUrl}" alt="" class="customer-avatar" style="object-fit:cover;" onerror="this.src='${DEFAULT_PROFILE_PATH}'">${cashierName}</div></td>
        <td>${customerName}</td>
        <td>${s.item_count || 0} items</td>
        <td>${date}</td>
        <td class="mono">${fmt(s.total_amount)}</td>
        <td><span class="order-status delivered" style="background:${statusColor};color:${statusTextColor};padding:3px 8px;border-radius:6px;font-size:11px;font-weight:600;">${statusLabel}</span></td>
      </tr>`;
    }).join('');
  } catch(e) { tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;color:var(--dash-text-muted);">Error loading data</td></tr>`; }
}

/* ── STOCK MOVEMENTS ── */
async function loadStockMovements() {
  const list = document.getElementById('stockMovementsList');
  try {
    const res = await fetch(API + 'inventory_transactions.php?action=list&limit=5');
    const result = await res.json();
    const items = result.data || result.transactions || [];
    if (!items.length) {
      list.innerHTML = `<div style="text-align:center;color:var(--dash-text-muted);padding:20px;">No recent stock movements</div>`;
      return;
    }
    const icons = { add: { bg: '#d1fae5', icon: '<i class="fa-solid fa-box-open"></i>' }, remove: { bg: '#fee2e2', icon: '📤' }, adjust: { bg: '#fef3c7', icon: '🔄' }, sale: { bg: '#ede9fe', icon: '🛒' } };
    list.innerHTML = items.slice(0, 5).map((t, i) => {
      const type = t.transaction_type || 'adjust';
      const style = icons[type] || { bg: '#f1f5f9', icon: '<i class="fa-solid fa-box"></i>' };
      const timeAgo = t.created_at ? new Date(t.created_at).toLocaleString('en-PH', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : '—';
      const isLast = i === items.slice(0,5).length - 1;
      return `<div class="activity-item">
        <div class="activity-dot-wrap">
          <div class="activity-dot" style="background:${style.bg};">${style.icon}</div>
          ${!isLast ? '<div class="activity-line"></div>' : ''}
        </div>
        <div class="activity-content">
          <div class="activity-text"><strong>${t.product_name || 'Product'}</strong> — ${type.replace(/_/g,' ')} <strong>${parseFloat(t.quantity||0).toFixed(1)} ${t.uom_name || 'units'}</strong></div>
          <div class="activity-time">${timeAgo}</div>
        </div>
      </div>`;
    }).join('');
  } catch(e) { list.innerHTML = `<div style="text-align:center;color:var(--dash-text-muted);">Error loading data</div>`; }
}

/* ── TOP MENU ITEMS ── */
async function loadTopMenuItems() {
  const list = document.getElementById('topMenuItemsList');
  try {
    const res = await fetch(API + 'reports.php?action=top_menu_items');
    const result = await res.json();
    if (!result.success || !result.data?.length) {
      list.innerHTML = `<div style="text-align:center;color:var(--dash-text-muted);padding:20px;">No data available</div>`;
      return;
    }
    const defaultFoodImage = isLocalhost ? '../uploads/foods/default-food.png' : '/uploads/foods/default-food.png';
    list.innerHTML = result.data.slice(0, 5).map((item, i) => {
      const hasImage = item.image_url && item.image_url.trim() !== '';
      const imgUrl = hasImage ? normalizeFoodImageUrl(item.image_url) : defaultFoodImage;
      return `
      <div class="product-item">
        <div class="product-rank">#${i + 1}</div>
        <div class="product-img"><img src="${imgUrl}" alt="${item.item_name}" ${hasImage ? `onerror="this.src='${defaultFoodImage}'"` : ''}></div>
        <div class="product-info">
          <div class="product-name">${item.item_name}</div>
          <div class="product-cat">${item.category_name} · ${parseInt(item.total_sold || 0).toLocaleString()} sold</div>
        </div>
        <div class="product-rev">${fmtShort(item.revenue)}</div>
      </div>`;
    }).join('');
  } catch(e) { list.innerHTML = `<div style="text-align:center;color:var(--dash-text-muted);">Error loading data</div>`; }
}

/* ── LINE CHART – MONTHLY REVENUE ── */
let lineChart;
async function buildLineChart() {
  const canvas = document.getElementById('dashboard-chart-line');
  if (!canvas) return;
  const existing = Chart.getChart(canvas);
  if (existing) existing.destroy();

  try {
    const res = await fetch(API + 'reports.php?action=revenue_monthly');
    const result = await res.json();
    const gridColor = 'rgba(0,0,0,0.05)', textColor = '#6b7280';

    let labels = [], data = [];
    if (result.success && result.data.length) {
      const sorted = result.data.slice().reverse().slice(-6);
      labels = sorted.map(r => { const [y, m] = r.month.split('-'); return new Date(y, m - 1).toLocaleString('en', { month: 'short' }) + ' ' + y.slice(2); });
      data = sorted.map(r => parseFloat(r.revenue));
      const total = data.reduce((a, b) => a + b, 0);
      document.getElementById('lineChartSubtitle').textContent = 'Last 6 months · Total: ' + fmtShort(total);
    } else {
      labels = ['No data']; data = [0];
    }

    lineChart = new Chart(canvas.getContext('2d'), {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'Revenue',
          data,
          borderColor: '#4f46e5',
          backgroundColor: 'rgba(79,70,229,0.12)',
          fill: true, tension: 0.4, borderWidth: 2.5, pointRadius: 4,
          pointBackgroundColor: '#4f46e5'
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
          legend: { position: 'top', align: 'end', labels: { color: textColor, font: { size: 12, family: 'Plus Jakarta Sans' }, boxWidth: 12 } },
          tooltip: { mode: 'index', intersect: false, callbacks: { label: ctx => ' ' + fmt(ctx.raw) } }
        },
        scales: {
          x: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 12 } } },
          y: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 12 }, callback: v => fmtShort(v) } }
        }
      }
    });
  } catch(e) { console.error('Line chart error:', e); }
}

/* ── DONUT CHART – BOOKING SOURCES ── */
let donutChart;
async function buildDonutChart() {
  const canvas = document.getElementById('dashboard-chart-donut');
  if (!canvas) return;
  const existing = Chart.getChart(canvas);
  if (existing) existing.destroy();

  try {
    const res = await fetch(API + 'reports.php?action=revenue_by_source');
    const result = await res.json();
    const colors = ['#4f46e5','#8b5cf6','#06b6d4','#f97316','#10b981','#f43f5e'];

    let labels = [], values = [];
    if (result.success && result.data.length) {
      const sorted = result.data.sort((a, b) => b.revenue - a.revenue).slice(0, 6);
      labels = sorted.map(d => (d.source_channel || 'Direct').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()));
      values = sorted.map(d => parseFloat(d.revenue));
      const total = sorted.reduce((a, b) => a + parseInt(b.bookings || 0), 0);
      document.getElementById('donutCenterVal').textContent = total.toLocaleString();
      document.getElementById('donutCenterLabel').textContent = 'bookings';
      const legend = document.getElementById('donutLegend');
      legend.innerHTML = sorted.map((d, i) => `
        <div class="legend-item">
          <div class="legend-dot-label"><div class="legend-dot" style="background:${colors[i % colors.length]}"></div>${labels[i]}</div>
          <div class="legend-val">${fmtShort(d.revenue)}</div>
        </div>`).join('');
    } else {
      labels = ['No data']; values = [1];
      document.getElementById('donutCenterVal').textContent = '0';
      document.getElementById('donutLegend').innerHTML = '<div style="text-align:center;color:var(--dash-text-muted);font-size:12px;">No booking source data</div>';
    }

    donutChart = new Chart(canvas.getContext('2d'), {
      type: 'doughnut',
      data: { labels, datasets: [{ data: values, backgroundColor: colors.slice(0, values.length), borderWidth: 0, hoverOffset: 8 }] },
      options: {
        responsive: false, cutout: '70%',
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': ' + fmtShort(ctx.raw) } } },
        animation: { animateRotate: true, duration: 900 }
      }
    });
  } catch(e) { console.error('Donut chart error:', e); }
}

/* ── BAR CHART – MONTHLY REVENUE (FULL YEAR) ── */
let barChart;
async function buildBarChart() {
  const canvas = document.getElementById('dashboard-chart-bar');
  if (!canvas) return;
  const existing = Chart.getChart(canvas);
  if (existing) existing.destroy();

  try {
    const res = await fetch(API + 'reports.php?action=revenue_monthly');
    const result = await res.json();
    const gridColor = 'rgba(0,0,0,0.05)', textColor = '#6b7280';
    const year = new Date().getFullYear();
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const revenueByMonth = {};

    if (result.success) {
      result.data.forEach(r => {
        if (r.month.startsWith(year)) revenueByMonth[r.month] = parseFloat(r.revenue);
      });
    }

    const data = months.map((_, i) => {
      const key = `${year}-${String(i + 1).padStart(2, '0')}`;
      return revenueByMonth[key] || 0;
    });

    document.getElementById('barChartSubtitle').textContent = `Monthly revenue — ${year}`;

    barChart = new Chart(canvas.getContext('2d'), {
      type: 'bar',
      data: {
        labels: months,
        datasets: [{ label: 'Revenue', data, backgroundColor: '#4f46e5cc', borderRadius: 6, borderSkipped: false }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
          legend: { position: 'top', align: 'end', labels: { color: textColor, font: { size: 12, family: 'Plus Jakarta Sans' }, boxWidth: 12 } },
          tooltip: { mode: 'index', intersect: false, callbacks: { label: ctx => ' ' + fmt(ctx.raw) } }
        },
        scales: {
          x: { grid: { display: false }, ticks: { color: textColor, font: { size: 12 } } },
          y: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 12 }, callback: v => fmtShort(v) } }
        }
      }
    });
  } catch(e) { console.error('Bar chart error:', e); }
}

/* ── INIT ── */
let chartsInitialized = false;
function initDashboard() {
  if (chartsInitialized) return;
  chartsInitialized = true;

  loadStatCards();
  fetchLowStockCount();
  loadRecentReservations();
  loadInventoryCategories();
  loadRecentSales();
  loadStockMovements();
  loadTopMenuItems();
  buildLineChart();
  buildDonutChart();
  buildBarChart();

  document.getElementById('chartRange')?.addEventListener('change', buildLineChart);
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initDashboard);
} else {
  initDashboard();
}
</script>