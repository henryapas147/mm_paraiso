<?php if (false) {} ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
<style>
  /* ── Occupancy Reports Design System ── */
  .occ-page {
    --occ-bg-main: #f0f2f8;
    --occ-bg-card: #ffffff;
    --occ-text-primary: #1a1f36;
    --occ-text-secondary: #6b7280;
    --occ-text-muted: #9ca3af;
    --occ-border: #e5e7eb;
    --occ-accent-blue: #4f46e5;
    --occ-accent-blue-light: #eef2ff;
    --occ-accent-emerald: #10b981;
    --occ-accent-rose: #f43f5e;
    --occ-accent-orange: #f97316;
    --occ-accent-violet: #8b5cf6;
    --occ-shadow-sm: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
    --occ-shadow-md: 0 4px 16px rgba(0, 0, 0, .08);
    --occ-radius: 16px;
    --occ-radius-sm: 10px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--occ-text-primary);
  }

  .occ-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
  }

  /* ── STAT CARDS ── */
  .occ-page .stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
  }

  .occ-page .stat-card {
    background: var(--occ-bg-card);
    border-radius: var(--occ-radius);
    padding: 20px;
    box-shadow: var(--occ-shadow-sm);
    border: 1px solid var(--occ-border);
    display: flex;
    flex-direction: column;
    gap: 14px;
    transition: box-shadow .2s, transform .2s;
    position: relative;
    overflow: hidden;
  }

  .occ-page .stat-card:hover {
    box-shadow: var(--occ-shadow-md);
    transform: translateY(-2px);
  }

  .occ-page .stat-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: var(--occ-radius) var(--occ-radius) 0 0;
  }

  .occ-page .stat-card.blue::after {
    background: linear-gradient(90deg, #4f46e5, #8b5cf6);
  }

  .occ-page .stat-card.rose::after {
    background: linear-gradient(90deg, #f43f5e, #fb7185);
  }

  .occ-page .stat-card.green::after {
    background: linear-gradient(90deg, #10b981, #34d399);
  }

  .occ-page .stat-card.orange::after {
    background: linear-gradient(90deg, #f97316, #fbbf24);
  }

  .occ-page .stat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .occ-page .stat-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--occ-text-muted);
  }

  .occ-page .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
  }

  .occ-page .stat-icon.blue {
    background: linear-gradient(135deg, #4f46e5, #8b5cf6);
  }

  .occ-page .stat-icon.rose {
    background: linear-gradient(135deg, #f43f5e, #fb923c);
  }

  .occ-page .stat-icon.green {
    background: linear-gradient(135deg, #10b981, #34d399);
  }

  .occ-page .stat-icon.orange {
    background: linear-gradient(135deg, #f97316, #fbbf24);
  }

  .occ-page .stat-value {
    font-size: 26px;
    font-weight: 800;
    color: var(--occ-text-primary);
    font-family: 'DM Mono', monospace;
  }

  .occ-page .stat-footer {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--occ-text-muted);
  }

  .occ-page .stat-badge {
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 2px;
  }

  .occ-page .stat-badge.up {
    color: var(--occ-accent-emerald);
  }

  .occ-page .stat-badge.down {
    color: var(--occ-accent-rose);
  }

  /* ── CARD ── */
  .occ-page .card {
    background: var(--occ-bg-card);
    border: 1px solid var(--occ-border);
    border-radius: var(--occ-radius);
    box-shadow: var(--occ-shadow-sm);
    overflow: hidden;
  }

  .occ-page .card-header {
    padding: 18px 20px 14px;
    border-bottom: 1px solid var(--occ-border);
  }

  .occ-page .card-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--occ-text-primary);
  }

  .occ-page .card-body {
    padding: 20px;
  }

  /* ── TABLE ── */
  .occ-page .data-table {
    width: 100%;
    border-collapse: collapse;
  }

  .occ-page .data-table th {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--occ-text-muted);
    text-align: left;
    padding: 12px 16px;
    border-bottom: 1px solid var(--occ-border);
  }

  .occ-page .data-table th.text-right {
    text-align: right;
  }

  .occ-page .data-table th.text-center {
    text-align: center;
  }

  .occ-page .data-table td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--occ-border);
    font-size: 13px;
    color: var(--occ-text-primary);
    vertical-align: middle;
  }

  .occ-page .data-table td.text-right {
    text-align: right;
  }

  .occ-page .data-table td.text-center {
    text-align: center;
  }

  .occ-page .data-table tr:last-child td {
    border-bottom: none;
  }

  .occ-page .mono {
    font-family: 'DM Mono', monospace;
    font-weight: 600;
  }

  /* ── BADGES ── */
  .occ-page .badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
  }

  .occ-page .badge-high {
    background: #d1fae5;
    color: #059669;
  }

  .occ-page .badge-medium {
    background: #fef3c7;
    color: #d97706;
  }

  .occ-page .badge-low {
    background: #fee2e2;
    color: #dc2626;
  }

  /* ── RESPONSIVE ── */
  @media (max-width: 1100px) {
    .occ-page .stat-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 768px) {
    .occ-page .stat-grid {
      grid-template-columns: 1fr 1fr;
    }
  }

  @media (max-width: 640px) {
    .occ-page .stat-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .occ-page .stat-card {
      padding: 16px;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .occ-page .stat-icon {
      width: 40px;
      height: 40px;
      font-size: 18px;
    }
  }

  @media (max-width: 640px) {
    .occ-page .stat-label {
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .occ-page .stat-value {
      font-size: 22px;
    }
  }

  @media (max-width: 640px) {
    .occ-page .stat-footer {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .occ-page .stat-badge {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .occ-page .card-header {
      padding: 14px 16px 12px;
    }
  }

  @media (max-width: 640px) {
    .occ-page .card-title {
      font-size: 14px;
    }
  }

  @media (max-width: 640px) {
    .occ-page .card-body {
      padding: 16px;
    }
  }

  @media (max-width: 640px) {
    .occ-page .data-table th {
      padding: 8px 12px;
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .occ-page .data-table td {
      padding: 10px 12px;
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .occ-page .badge {
      padding: 3px 8px;
      font-size: 10px;
    }
  }

  @media (max-width: 768px) {
    .occ-content > div[style*="grid-template-columns: 1fr 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }

  @media (max-width: 480px) {
    .occ-page .stat-grid {
      gap: 10px;
    }
  }

  @media (max-width: 480px) {
    .occ-page .stat-card {
      padding: 14px;
    }
  }

  @media (max-width: 480px) {
    .occ-page .stat-value {
      font-size: 20px;
    }
  }

  @media (max-width: 480px) {
    .occ-page .card-header {
      padding: 12px 14px 10px;
    }
  }

  @media (max-width: 480px) {
    .occ-page .card-body {
      padding: 14px;
    }
  }

  @media (max-width: 480px) {
    .occ-page .data-table th {
      padding: 6px 10px;
      font-size: 9px;
    }
  }

  @media (max-width: 480px) {
    .occ-page .data-table td {
      padding: 8px 10px;
      font-size: 11px;
    }
  }

  @media (max-width: 480px) {
    .occ-page .badge {
      padding: 2px 6px;
      font-size: 9px;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    loadOccupancySummary();
    loadOccupancyData();
    loadRoomTypeOccupancy();
  });

  async function loadOccupancySummary() {
    try {
      const res = await fetch('../api/reports.php?action=occupancy_summary');
      const json = await res.json();
      if (json.success && json.data) {
        const d = json.data;
        document.getElementById('statTotalRooms').textContent = d.total_rooms ?? '—';
        document.getElementById('statOccupied').textContent = d.occupied ?? '—';
        document.getElementById('statAvailable').textContent = (d.total_rooms && d.occupied != null) ? (d.total_rooms - d.occupied) : '—';
        document.getElementById('statOccupancyRate').textContent = d.today != null ? d.today + '%' : '—';
      }
    } catch (e) {
      console.error('Error loading occupancy summary:', e);
    }
  }

  async function loadOccupancyData() {
    try {
      const res = await fetch('../api/reports.php?action=occupancy_daily');
      const json = await res.json();
      
      if (json.success) {
        renderOccupancyTable(json.data);
      }
    } catch (e) {
      console.error('Error:', e);
    }
  }

  async function loadRoomTypeOccupancy() {
    try {
      const res = await fetch('../api/reports.php?action=occupancy_by_room_type');
      const json = await res.json();
      
      if (json.success) {
        renderRoomTypeTable(json.data);
      }
    } catch (e) {
      console.error('Error:', e);
    }
  }

  function renderOccupancyTable(data) {
    const tbody = document.getElementById('occupancyTableBody');
    tbody.innerHTML = data.map(row => `
      <tr>
        <td>${new Date(row.date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</td>
        <td class="text-center">${row.check_ins || 0}</td>
        <td class="text-center">${row.check_outs || 0}</td>
      </tr>
    `).join('');
  }

  function renderRoomTypeTable(data) {
    const tbody = document.getElementById('roomTypeTableBody');
    tbody.innerHTML = data.map(row => {
      const pct = row.total_rooms > 0 ? Math.round((row.occupied / row.total_rooms) * 100) : 0;
      const badgeClass = pct > 80 ? 'badge-high' : pct > 50 ? 'badge-medium' : 'badge-low';
      return `
        <tr>
          <td>${row.category_name}</td>
          <td class="text-center">${row.total_rooms}</td>
          <td class="text-center">${row.occupied || 0}</td>
          <td class="text-center"><span class="badge ${badgeClass}">${pct}%</span></td>
        </tr>
      `;
    }).join('');
  }
</script>

<div class="occ-page">
  <div class="occ-content">

    <!-- Stat Cards -->
    <div class="stat-grid">
      <div class="stat-card blue">
        <div class="stat-header">
          <span class="stat-label">Total Rooms</span>
          <div class="stat-icon blue"><i class="fa-solid fa-hotel"></i></div>
        </div>
        <div class="stat-value" id="statTotalRooms">—</div>
        <div class="stat-footer">
          total rooms
        </div>
      </div>

      <div class="stat-card green">
        <div class="stat-header">
          <span class="stat-label">Occupied</span>
          <div class="stat-icon green">✅</div>
        </div>
        <div class="stat-value" id="statOccupied">—</div>
        <div class="stat-footer">
          currently occupied
        </div>
      </div>

      <div class="stat-card orange">
        <div class="stat-header">
          <span class="stat-label">Available</span>
          <div class="stat-icon orange"><i class="fa-solid fa-key"></i></div>
        </div>
        <div class="stat-value" id="statAvailable">—</div>
        <div class="stat-footer">
          ready for booking
        </div>
      </div>

      <div class="stat-card rose">
        <div class="stat-header">
          <span class="stat-label">Occupancy Rate</span>
          <div class="stat-icon rose"><i class="fa-solid fa-chart-pie"></i></div>
        </div>
        <div class="stat-value" id="statOccupancyRate">—</div>
        <div class="stat-footer">
          overall rate
        </div>
      </div>
    </div>

    <!-- Row: Daily Check-ins + Room Type Occupancy -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">

      <!-- Daily Check-ins/Check-outs -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">Daily Check-ins/Check-outs</div>
        </div>
        <div class="card-body" style="padding: 0; overflow-x: auto;">
          <table class="data-table">
            <thead>
              <tr>
                <th>Date</th>
                <th class="text-center">Check-ins</th>
                <th class="text-center">Check-outs</th>
              </tr>
            </thead>
            <tbody id="occupancyTableBody">
              <tr>
                <td colspan="3" style="text-align: center; color: var(--occ-text-muted);">Loading...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Occupancy by Room Type -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">Occupancy by Room Type</div>
        </div>
        <div class="card-body" style="padding: 0; overflow-x: auto;">
          <table class="data-table">
            <thead>
              <tr>
                <th>Room Type</th>
                <th class="text-center">Total</th>
                <th class="text-center">Occupied</th>
                <th class="text-center">Rate</th>
              </tr>
            </thead>
            <tbody id="roomTypeTableBody">
              <tr>
                <td colspan="4" style="text-align: center; color: var(--occ-text-muted);">Loading...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>

  </div>
</div>
