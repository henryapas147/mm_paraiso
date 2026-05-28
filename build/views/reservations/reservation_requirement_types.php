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

  .toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: nowrap
  }

  .search-wrap {
    position: relative
  }

  .search-wrap i {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    font-size: 15px;
    pointer-events: none
  }

  .search-input {
    padding: 7px 12px 7px 34px;
    font-size: 13px;
    border: 1px solid #ccc;
    border-radius: 6px;
    background: #fff;
    color: #1a1a1a;
    outline: none;
    width: 220px;
    min-width: 160px
  }

  .search-input:focus {
    border-color: #185FA5
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

  thead th.center {
    text-align: center
  }

  thead th.right {
    text-align: right
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

  .badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
    text-transform: capitalize
  }

  .badge.yes {
    background: #E8F5E9;
    color: #2E7D32
  }

  .badge.no {
    background: #F5F5F5;
    color: #666
  }

  .badge.active {
    background: #E8F5E9;
    color: #2E7D32
  }

  .badge.inactive {
    background: #FFEBEE;
    color: #C62828
  }

  .badge.applies {
    background: #E3F2FD;
    color: #1565C0;
    margin-right: 4px
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
</style>

<!-- PAGE CONTENT -->

<div class="page">
  <div class="card">
    <div class="card-header">
      <div class="card-title">Requirement Types</div>
      <div class="toolbar">
        <div class="search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="searchInput" class="search-input" placeholder="Search types..." onkeyup="filterTable()">
        </div>
      </div>
    </div>
    <div class="table-wrap">
      <table id="requirementTypesTable">
        <thead>
          <tr>
            <th>Code</th>
            <th>Label</th>
            <th>Property</th>
            <th>Applies To</th>
            <th class="center">Mandatory</th>
            <th class="center">Active</th>
            <th class="right">Sort Order</th>
          </tr>
        </thead>
        <tbody id="requirementTypesTableBody">
          <tr>
            <td colspan="7" class="empty-state">
              <i class="fa-regular fa-clipboard"></i>
              Loading...
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  let requirementTypesData = [];

  document.addEventListener('DOMContentLoaded', function() {
    fetchRequirementTypesData();
  });

  async function fetchRequirementTypesData() {
    try {
      const response = await fetch('../api/reservation_requirements.php?action=types');
      const result = await response.json();
      if (result.success) {
        requirementTypesData = result.data;
        renderRequirementTypesTable();
      } else {
        document.getElementById('requirementTypesTableBody').innerHTML = `<tr><td colspan="7" class="empty-state"><i>⚠</i>${result.message}</td></tr>`;
      }
    } catch (error) {
      console.error('Error fetching requirement types:', error);
      document.getElementById('requirementTypesTableBody').innerHTML = `<tr><td colspan="7" class="empty-state"><i>⚠</i>Failed to load data</td></tr>`;
    }
  }

  function renderRequirementTypesTable() {
    const tbody = document.getElementById('requirementTypesTableBody');
    if (!tbody) return;

    if (requirementTypesData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="7" class="empty-state"><i class="fa-regular fa-clipboard"></i>No requirement types found</td></tr>`;
      return;
    }

    tbody.innerHTML = requirementTypesData.map(rt => {
      const appliesTo = (rt.applies_to || '').split(',').map(t => t.trim()).filter(Boolean);
      const appliesHtml = appliesTo.map(t =>
        `<span class="badge applies">${t.replace(/_/g, ' ')}</span>`
      ).join('');

      const mandatoryBadge = rt.is_mandatory == 1
        ? `<span class="badge yes">Yes</span>`
        : `<span class="badge no">No</span>`;

      const activeBadge = rt.is_active == 1
        ? `<span class="badge active">Active</span>`
        : `<span class="badge inactive">Inactive</span>`;

      const propertyCell = rt.property_name
        ? rt.property_name
        : `<span style="color:#999">All Properties</span>`;

      return `
        <tr class="reqtype-row">
          <td><strong>${rt.req_code || '-'}</strong></td>
          <td><strong>${rt.req_label || '-'}</strong></td>
          <td>${propertyCell}</td>
          <td>${appliesHtml || '<span style="color:#999">-</span>'}</td>
          <td style="text-align:center">${mandatoryBadge}</td>
          <td style="text-align:center">${activeBadge}</td>
          <td style="text-align:right">${rt.sort_order || '-'}</td>
        </tr>
      `;
    }).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    document.querySelectorAll('#requirementTypesTable .reqtype-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }
</script>
