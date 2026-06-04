<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Lora:ital,wght@0,600;1,500&display=swap" rel="stylesheet">

<style>
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0
  }

  body {
    font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
    background: #f5f5f5;
    color: #1a1a1a
  }

  .page {
    padding: 24px;
    max-width: 100%;
    margin: 0 auto
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px
  }

  .stat-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 16px
  }

  .stat-label {
    font-size: 13px;
    font-weight: 500;
    color: #666;
    margin-bottom: 8px
  }

  .stat-value {
    font-size: 28px;
    font-weight: 600;
    color: #1a1a1a
  }

  .card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px
  }

  .card-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap
  }

  .card-title {
    font-family: 'Lora', Georgia, serif;
    font-size: 16px;
    font-weight: 600;
    font-style: italic
  }

  .table-wrap {
    overflow-x: auto
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px
  }

  thead th {
    padding: 10px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 500;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    border-bottom: 1px solid #e0e0e0;
    white-space: nowrap
  }

  tbody tr {
    border-bottom: 1px solid #e0e0e0;
    transition: background .1s
  }

  tbody tr:last-child {
    border-bottom: none
  }

  tbody tr:hover {
    background: #f9f9f9
  }

  td {
    padding: 11px 16px;
    vertical-align: middle;
    white-space: nowrap
  }

  .action-btns {
    display: flex;
    gap: 6px
  }

  .action-btn {
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid #e0e0e0;
    background: #fff;
    color: #1a1a1a;
    transition: all .2s
  }

  .action-btn:hover {
    background: #f5f5f5
  }

  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999
  }

  .modal {
    background: #fff;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25)
  }

  .modal-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: space-between
  }

  .modal-title {
    font-family: 'Lora', Georgia, serif;
    font-size: 18px;
    font-weight: 600;
    font-style: italic
  }

  .modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666
  }

  .modal-body {
    padding: 20px;
    overflow-y: auto;
    flex: 1
  }

  .empty-state {
    padding: 48px 24px;
    text-align: center;
    color: #666
  }

  .empty-state i {
    font-size: 32px;
    margin-bottom: 12px;
    display: block;
    color: #ccc
  }

  .view-row {
    display: flex;
    margin-bottom: 12px
  }

  .view-label {
    width: 140px;
    font-size: 13px;
    color: #666;
    font-weight: 500
  }

  .view-value {
    flex: 1;
    font-size: 13px;
    color: #1a1a1a
  }

  /* ── Responsive ──────────────────────────────────── */
  @media (max-width: 640px) {
    .stats-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .stat-card {
      padding: 14px 16px;
    }
  }

  @media (max-width: 640px) {
    .stat-label {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .stat-value {
      font-size: 24px;
    }
  }

  @media (max-width: 480px) {
    .page {
      padding: 16px;
    }
  }

  @media (max-width: 768px) {
    .card-header {
      flex-direction: column;
      align-items: stretch;
      gap: 16px;
      padding: 16px 18px;
    }
  }

  @media (max-width: 640px) {
    thead th {
      padding: 8px 12px;
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    td {
      padding: 8px 12px;
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .action-btn {
      padding: 4px 8px;
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .empty-state {
      padding: 40px 20px;
    }
  }

  @media (max-width: 640px) {
    .empty-state i {
      font-size: 28px;
    }
  }

  @media (max-width: 768px) {
    .modal {
      max-width: 90vw;
    }
  }

  @media (max-width: 480px) {
    .modal {
      max-width: none;
      max-height: 95vh;
      border-radius: 12px;
    }
  }

  @media (max-width: 768px) {
    .modal-body {
      padding: 18px;
    }
  }

  @media (max-width: 480px) {
    .modal-body {
      padding: 16px;
      overflow-y: auto;
      overflow-x: hidden;
    }
  }

  @media (max-width: 640px) {
    .modal-header {
      padding: 14px 18px;
    }
  }

  @media (max-width: 640px) {
    .modal-title {
      font-size: 16px;
    }
  }

  @media (max-width: 640px) {
    .modal-close {
      font-size: 20px;
    }
  }

  @media (max-width: 640px) {
    .view-row {
      flex-direction: column;
      gap: 4px;
    }
  }

  @media (max-width: 640px) {
    .view-label {
      width: auto;
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .view-value {
      font-size: 12px;
    }
  }
</style>

<!-- PAGE CONTENT -->

<div class="page">
  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total Usages</div>
      <div class="stat-value" id="statTotal">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total Savings</div>
      <div class="stat-value" id="statSavings">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Unique Discounts</div>
      <div class="stat-value" id="statUnique">-</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Avg Savings</div>
      <div class="stat-value" id="statAvg">-</div>
    </div>
  </div>

  <!-- Discount Usages Table -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">Discount Usages</div>
    </div>
    <div class="table-wrap">
      <table id="discountUsagesTable">
        <thead>
          <tr>
            <th>Discount Code</th>
            <th>Reservation</th>
            <th>Amount Saved</th>
            <th>Applied At</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="discountUsagesTableBody">
          <tr>
            <td colspan="5" class="empty-state">
              <i class="fa-regular fa-clipboard"></i>
              Loading...
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- View Usage Modal -->
<div id="viewUsageModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Discount Usage Details</div>
      <button onclick="closeViewUsageModal()" class="modal-close">×</button>
    </div>
    <div class="modal-body">
      <div class="view-row">
        <div class="view-label">Discount Code</div>
        <div class="view-value" id="view_discount_code"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Discount Name</div>
        <div class="view-value" id="view_discount_name"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Reservation</div>
        <div class="view-value" id="view_reservation"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Amount Saved</div>
        <div class="view-value" id="view_amount_saved"></div>
      </div>
      <div class="view-row">
        <div class="view-label">Applied At</div>
        <div class="view-value" id="view_applied_at"></div>
      </div>
    </div>
  </div>
</div>

<script>
  let discountUsagesData = [];

  document.addEventListener('DOMContentLoaded', function () {
    fetchDiscountUsages();
  });

  async function fetchDiscountUsages() {
    try {
      const res = await fetch('../api/discount_usages.php?action=list');
      const result = await res.json();
      if (result.success) {
        discountUsagesData = result.data;
        renderTable();
        renderStats();
      } else {
        document.getElementById('discountUsagesTableBody').innerHTML = `<tr><td colspan="5" class="empty-state"><i>⚠</i>${result.message}</td></tr>`;
      }
    } catch (e) {
      console.error('Error fetching discount usages:', e);
      document.getElementById('discountUsagesTableBody').innerHTML = `<tr><td colspan="5" class="empty-state"><i>⚠</i>Failed to load data</td></tr>`;
    }
  }

  function renderStats() {
    const total = discountUsagesData.length;
    const savings = discountUsagesData.reduce((s, u) => s + parseFloat(u.amount_saved || 0), 0);
    const unique = new Set(discountUsagesData.map(u => u.discount_id)).size;
    const avg = total > 0 ? savings / total : 0;
    document.getElementById('statTotal').textContent = total;
    document.getElementById('statSavings').textContent = '₱' + savings.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('statUnique').textContent = unique;
    document.getElementById('statAvg').textContent = '₱' + avg.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function renderTable() {
    const tbody = document.getElementById('discountUsagesTableBody');
    if (!discountUsagesData.length) {
      tbody.innerHTML = `<tr><td colspan="5" class="empty-state"><i class="fa-regular fa-clipboard"></i>No discount usages found.</td></tr>`;
      return;
    }
    tbody.innerHTML = discountUsagesData.map(u => {
      const appliedAt = u.applied_at ? new Date(u.applied_at).toLocaleString('en-US', { month: 'short', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';
      return `
        <tr class="discount-usage-row">
          <td>
            <div>${u.discount_code || '-'}</div>
            <div style="font-size:11px;color:#666">${u.discount_name || '-'}</div>
          </td>
          <td>${u.reservation_id ? u.reservation_id.substring(0, 8) + '...' : '-'}</td>
          <td>₱${parseFloat(u.amount_saved || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
          <td>${appliedAt}</td>
          <td>
            <div class="action-btns">
              <button onclick="openViewUsageModal('${u.usage_id}')" class="action-btn">View</button>
            </div>
          </td>
        </tr>`;
    }).join('');
  }

  function openViewUsageModal(usageId) {
    const usage = discountUsagesData.find(u => u.usage_id === usageId);
    if (usage) {
      document.getElementById('view_discount_code').textContent = usage.discount_code || '-';
      document.getElementById('view_discount_name').textContent = usage.discount_name || '-';
      document.getElementById('view_reservation').textContent = usage.reservation_id || '-';
      document.getElementById('view_amount_saved').textContent = '₱' + parseFloat(usage.amount_saved).toFixed(2);
      document.getElementById('view_applied_at').textContent = usage.applied_at ? new Date(usage.applied_at).toLocaleString() : '-';
      document.getElementById('viewUsageModal').style.display = 'flex';
    }
  }

  function closeViewUsageModal() {
    document.getElementById('viewUsageModal').style.display = 'none';
  }
</script>