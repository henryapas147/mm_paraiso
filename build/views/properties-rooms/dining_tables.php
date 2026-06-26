<?php
// Helper function to generate UUID
function generateUUID()
{
  return sprintf(
    '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0x0fff) | 0x4000,
    mt_rand(0, 0x3fff) | 0x8000,
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff)
  );
}
?>

<style>
  :root {
    --color-background-primary: #ffffff;
    --color-background-secondary: #f8fafc;
    --color-border-primary: #185fa5;
    --color-border-secondary: #e2e8f0;
    --color-border-tertiary: #cbd5e1;
    --color-text-primary: #1e293b;
    --color-text-secondary: #64748b;
  }
  .dt-page { padding: 24px; max-width: 100%; margin: 0 auto; overflow-x: hidden; }
  .dt-stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
  .dt-stat-card {
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 12px;
    padding: 16px;
  }
  .dt-stat-label { font-size: 12px; font-weight: 500; color: var(--color-text-secondary); text-transform: uppercase; letter-spacing: 0.3px; }
  .dt-stat-value { font-size: 24px; font-weight: 600; color: var(--color-text-primary); margin-top: 4px; }
  .dt-card {
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 12px;
    margin-bottom: 0;
  }
  .dt-card-header {
    padding: 16px 20px;
    border-bottom: 0.5px solid var(--color-border-tertiary);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }
  .dt-card-title { font-size: 16px; font-weight: 500; }
  .dt-toolbar { display: flex; align-items: center; gap: 8px; flex-wrap: nowrap; }
  .dt-search-wrap {
    display: flex;
    align-items: center;
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 6px;
    padding: 4px 10px 4px 30px;
    background: #f9fafb;
    position: relative;
    min-width: 200px;
  }
  .dt-search-wrap i {
    position: absolute;
    left: 8px;
    color: var(--color-text-secondary);
    font-size: 12px;
  }
  .dt-search-input {
    border: none;
    outline: none;
    background: transparent;
    font-size: 13px;
    width: 100%;
    color: var(--color-text-primary);
  }
  .dt-filter-select {
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 13px;
    color: var(--color-text-primary);
    outline: none;
    background: #f9fafb;
  }
  .dt-btn {
    border: 0.5px solid var(--color-border-secondary);
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-primary);
    background: #ffffff;
    cursor: pointer;
    transition: all 0.18s ease;
  }
  .dt-btn:hover { background: #f1f5f9; border-color: #cbd5e1; }
  .dt-btn-primary {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
    border: none;
  }
  .dt-btn-primary:hover { opacity: 0.9; box-shadow: 0 2px 8px rgba(59,130,246,0.25); }
  .dt-table-wrap { overflow-x: auto; }
  .dt-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }
  .dt-table th {
    text-align: left;
    padding: 10px 12px;
    border-bottom: 0.5px solid var(--color-border-secondary);
    color: var(--color-text-secondary);
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    background: #f9fafb;
  }
  .dt-table td {
    padding: 10px 12px;
    border-bottom: 0.5px solid var(--color-border-secondary);
    color: var(--color-text-primary);
  }
  .dt-table tr:hover td { background: #f8fafc; }
  .dt-name { font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px; }
  .dt-code { font-size: 12px; color: var(--color-text-secondary); }
  .dt-text { color: var(--color-text-primary); font-size: 13px; }
  .dt-status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }
  .dt-status-active { background: #dcfce7; color: #166534; }
  .dt-status-inactive { background: #e5e7eb; color: #374151; }
  .dt-status-occupied { background: #fef3c7; color: #92400e; }
  .dt-status-available { background: #dcfce7; color: #166534; }
  .dt-actions { display: flex; gap: 4px; justify-content: flex-end; }
  .dt-actions-dropdown {
    position: relative;
    display: none;
  }
  .dt-actions-dropdown-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px;
    border-radius: 6px;
    border: 0.5px solid var(--color-border-secondary);
    background: #ffffff; color: var(--color-text-secondary);
    cursor: pointer; font-size: 12px;
  }
  .dt-actions-dropdown-btn:hover { background: #f1f5f9; color: var(--color-text-primary); }
  .dt-actions-dropdown-menu {
    position: absolute; right: 0; top: calc(100% + 4px);
    background: #ffffff;
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 6px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    min-width: 140px; z-index: 100; overflow: hidden; display: none;
  }
  .dt-actions-dropdown-menu.open { display: block; }
  .dt-actions-dropdown-menu button {
    display: flex; align-items: center; gap: 8px;
    width: 100%; padding: 8px 12px; font-size: 12px;
    background: transparent; border: none;
    color: var(--color-text-primary); cursor: pointer; text-align: left;
  }
  .dt-actions-dropdown-menu button:hover { background: #f8fafc; }
  .dt-actions-dropdown-menu button.del-action:hover { background: #fef2f2; color: #ef4444; }
  .dt-actions-dropdown-menu button i { width: 16px; text-align: center; font-size: 12px; color: var(--color-text-secondary); }
  .dt-actions-dropdown-menu button.del-action:hover i { color: #ef4444; }
  .dt-icon-btn {
    border: 0.5px solid var(--color-border-secondary);
    border-radius: 6px;
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    background: #ffffff;
    color: var(--color-text-secondary);
    font-size: 12px;
    transition: all 0.18s ease;
  }
  .dt-icon-btn.view:hover { border-color: #3b82f6; color: #3b82f6; background: #eff6ff; }
  .dt-icon-btn.edit:hover { border-color: #f59e0b; color: #f59e0b; background: #fffbeb; }
  .dt-icon-btn.del:hover { border-color: #ef4444; color: #ef4444; background: #fef2f2; }
  .dt-empty-state { text-align: center; padding: 24px; color: var(--color-text-secondary); font-size: 13px; }
  .dt-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.4);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.18s ease;
    overflow-y: auto;
  }
  .dt-modal-backdrop.open { opacity: 1; pointer-events: auto; }
  .dt-modal {
    background: #ffffff;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    transform: scale(0.96) translateY(10px);
    transition: transform 0.18s ease;
    margin: auto;
  }
  .dt-modal-backdrop.open .dt-modal { transform: scale(1) translateY(0); }
  .dt-modal-header {
    padding: 14px 18px;
    border-bottom: 0.5px solid var(--color-border-secondary);
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    background: #ffffff;
    z-index: 1;
    gap: 12px;
  }
  .dt-modal-title {
    font-size: 15px;
    font-weight: 500;
    flex: 1;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .dt-modal-close {
    border: none;
    background: none;
    cursor: pointer;
    color: var(--color-text-secondary);
    font-size: 16px;
    padding: 4px;
    border-radius: 4px;
    flex-shrink: 0;
  }
  .dt-modal-close:hover { background: #f1f5f9; color: var(--color-text-primary); }
  .dt-modal-body { padding: 16px 18px; }
  .dt-modal-footer {
    padding: 12px 18px;
    border-top: 0.5px solid var(--color-border-secondary);
    display: flex;
    gap: 8px;
    justify-content: flex-end;
    position: sticky;
    bottom: 0;
    background: #ffffff;
    flex-wrap: wrap;
  }
  .dt-section-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin: 0 0 10px;
  }
  .dt-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px; }
  .dt-form-group { display: flex; flex-direction: column; gap: 4px; }
  .dt-form-group.full { grid-column: 1 / -1; }
  .dt-form-group label { font-size: 12px; font-weight: 500; color: var(--color-text-secondary); }
  .dt-form-group input,
  .dt-form-group select,
  .dt-form-group textarea {
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 6px;
    padding: 7px 10px;
    font-size: 13px;
    color: var(--color-text-primary);
    outline: none;
    background: #ffffff;
  }
  .dt-form-group input:focus,
  .dt-form-group select:focus,
  .dt-form-group textarea:focus { border-color: var(--color-border-primary); }
  .dt-form-group textarea { resize: vertical; min-height: 60px; }
  .dt-checkbox-wrap { display: flex; align-items: center; gap: 6px; font-size: 13px; }
  .dt-checkbox-wrap input[type=checkbox] {
    width: 14px;
    height: 14px;
    accent-color: var(--color-border-primary);
  }
  .pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    border-top: 0.5px solid var(--color-border-secondary);
    gap: 12px;
    flex-wrap: wrap;
  }
  .pag-info {
    font-size: 13px;
    color: var(--color-text-secondary);
  }
  .per-page-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--color-text-secondary);
  }
  .per-page-wrap select {
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 12px;
    color: var(--color-text-primary);
    outline: none;
    background: #ffffff;
  }
  .pag-controls {
    display: flex;
    align-items: center;
    gap: 4px;
  }
  .pag-btn {
    border: 0.5px solid var(--color-border-secondary);
    border-radius: 4px;
    padding: 5px 10px;
    font-size: 12px;
    color: var(--color-text-primary);
    background: #ffffff;
    cursor: pointer;
    transition: all 0.18s ease;
    min-width: 32px;
  }
  .pag-btn:hover:not(:disabled) { background: #f1f5f9; border-color: #cbd5e1; }
  .pag-btn:disabled { opacity: 0.5; cursor: not-allowed; }
  .pag-btn.active {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
    border-color: transparent;
  }
  .dt-toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #10b981;
    color: white;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.25s ease;
    z-index: 2000;
  }
  .dt-toast.error { background: #ef4444; }
  .dt-toast.show { opacity: 1; transform: translateY(0); }

  /* ── Responsive ── */
  @media (max-width: 1400px) {
    .dt-page { padding: 20px; }
    .dt-search-wrap { min-width: 160px; }
  }

  @media (max-width: 1200px) {
    .dt-page { padding: 18px; }
    .dt-card-header { flex-direction: column; align-items: flex-start; }
    .dt-toolbar { width: 100%; flex-wrap: wrap; }
    .dt-search-wrap { min-width: 140px; }
    .dt-btn { padding: 5px 10px; font-size: 12px; }
  }

  @media (max-width: 1024px) {
    .dt-table th:nth-child(4),
    .dt-table td:nth-child(4) { display: none; }
    .dt-card-title { font-size: 14px; }
    .dt-table td { padding: 8px 10px; }
    .dt-table th { padding: 8px 10px; font-size: 11px; }
  }

  @media (max-width: 992px) {
    .dt-page { padding: 16px; }
    .dt-card-header { padding: 14px 16px; flex-direction: column; align-items: flex-start; gap: 10px; }
    .dt-toolbar { width: 100%; flex-wrap: wrap; gap: 6px; }
    .dt-search-wrap { min-width: 100%; }
    .dt-filter-select { flex: 1 1 auto; }
    .dt-btn { padding: 5px 10px; font-size: 12px; }
    .dt-table td { padding: 8px 10px; }
    .dt-table th { padding: 8px 10px; font-size: 10px; }
    .dt-name { font-size: 12px; }
    .dt-code { font-size: 11px; }
    .dt-text { font-size: 12px; }
    .dt-status-badge { font-size: 10px; padding: 2px 8px; }
    .dt-icon-btn { width: 26px; height: 26px; font-size: 11px; }
    .dt-actions { gap: 3px; }
    .pag-info { font-size: 11px; }
    .pag-btn { min-width: 28px; height: 28px; font-size: 11px; }
    /* Modal */
    .dt-modal { max-width: 96vw; }
    .dt-modal-header { padding: 12px 16px; }
    .dt-modal-body { padding: 14px 16px; }
    .dt-modal-footer { padding: 10px 16px; }
  }

  @media (max-width: 768px) {
    .dt-page { padding: 14px; }
    .dt-card-header { padding: 12px; }
    .dt-card-title { font-size: 13px; }
    .dt-toolbar { gap: 6px; }
    .dt-stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .dt-form-grid { grid-template-columns: 1fr; }
    .dt-form-group.full { grid-column: 1; }
    .dt-table th:nth-child(3),
    .dt-table td:nth-child(3) { display: none; }
    .dt-table td { padding: 8px 10px; }
    .dt-table th { padding: 8px 10px; font-size: 9px; }
    .dt-name { font-size: 11px; }
    .dt-code { font-size: 10px; }
    .dt-status-badge { font-size: 9px; padding: 2px 6px; }
    .dt-icon-btn { width: 24px; height: 24px; font-size: 11px; }
    .dt-actions { gap: 2px; }
    /* Slide-up modal */
    .dt-modal-backdrop { padding: 0; align-items: flex-end; }
    .dt-modal { border-radius: 16px 16px 0 0; max-width: 100%; max-height: 95vh; width: 100%; }
    .dt-modal-header { border-radius: 16px 16px 0 0; padding: 12px 16px; }
    .dt-modal-footer { border-radius: 0; padding: 10px 16px; flex-wrap: wrap; }
    .dt-modal-body { padding: 12px 16px; }
    .dt-modal-title { font-size: 13px; }
    .pagination { flex-direction: column; align-items: flex-start; gap: 8px; }
    .per-page-wrap { display: none; }
  }

  @media (max-width: 640px) {
    .dt-card-header { flex-direction: column; align-items: stretch; }
    .dt-toolbar { flex-direction: column; align-items: stretch; }
    .dt-search-wrap, .dt-filter-select, .dt-btn { width: 100%; }
    /* Dropdown actions on small screens */
    .dt-actions .dt-icon-btn { display: none; }
    .dt-actions-dropdown { display: block; }
  }

  @media (max-width: 480px) {
    .dt-page { padding: 12px; }
    .dt-card-header { padding: 10px 12px; }
    .dt-toolbar { gap: 4px; }
    .dt-btn { font-size: 11px; padding: 5px 10px; }
    .dt-icon-btn { width: 24px; height: 24px; }
    .dt-stats-grid { grid-template-columns: 1fr; }
    .dt-actions .dt-icon-btn { display: none; }
    .dt-actions-dropdown { display: block; }
    .dt-modal-footer { flex-direction: column; align-items: stretch; }
    .dt-modal-footer .dt-btn { width: 100%; justify-content: center; }
    .dt-modal-title { font-size: 12px; }
    .dt-form-grid { gap: 10px; }
    .dt-form-group input, .dt-form-group select, .dt-form-group textarea { font-size: 14px; padding: 7px 10px; }
    .dt-section-label { font-size: 10px; }
    .pag-btn { min-width: 26px; height: 26px; font-size: 10px; }
  }

  @media (max-width: 360px) {
    .dt-page { padding: 10px; }
    .dt-card-title { font-size: 12px; }
    .dt-modal-header { padding: 10px 12px; }
    .dt-modal-body { padding: 10px 12px; }
    .dt-modal-footer { padding: 8px 12px; }
    .dt-modal-title { font-size: 11px; }
    .dt-form-group input, .dt-form-group select, .dt-form-group textarea { font-size: 13px; padding: 6px 9px; }
    .pag-btn { min-width: 24px; height: 24px; font-size: 9px; }
  }

  @media (max-width: 768px) and (orientation: landscape) {
    .dt-modal-backdrop { padding: 8px; align-items: center; }
    .dt-modal { border-radius: 12px; max-height: 90vh; }
    .dt-modal-header { border-radius: 12px 12px 0 0; }
    .dt-modal-body { padding: 10px 14px; }
    .dt-form-grid { grid-template-columns: 1fr 1fr; }
    .dt-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
  }

  @media (hover: none) and (pointer: coarse) {
    .dt-icon-btn { width: 32px; height: 32px; }
    .dt-btn { padding: 8px 14px; }
    .dt-form-group input, .dt-form-group select, .dt-form-group textarea { font-size: 16px; }
  }
</style>

<script>
  // CSRF Token for API requests (from db.php)
  const CSRF_TOKEN = <?php echo json_encode(CSRF_TOKEN); ?>;

  // Data will be fetched from API on page load
  let diningTablesData = [];
  let currentPage = 1;
  let itemsPerPage = 10;
  let totalItems = 0;

  // Fetch data from API on page load
  document.addEventListener('DOMContentLoaded', async function() {
    await fetchDiningTablesData();
  });

  async function fetchDiningTablesData() {
    try {
      const offset = (currentPage - 1) * itemsPerPage;
      const response = await fetch(`../api/dining_tables.php?action=list&limit=${itemsPerPage}&offset=${offset}`);
      const result = await response.json();
      if (result.success) {
        diningTablesData = result.data;
        totalItems = result.total ?? diningTablesData.length;
        renderDiningTablesTable();
        renderPagination();
        updateStatsCounts();
      }
    } catch (error) {
      console.error('Error fetching dining tables:', error);
      showDtToast('Failed to load dining tables data', 'error');
    }
  }

  function showDtToast(message, type = 'success') {
    const toast = document.getElementById('dtToast');
    toast.textContent = message;
    toast.className = `dt-toast ${type}`;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
  }

  function updateStatsCounts() {
    const totalCount = diningTablesData.length;
    const availableCount = diningTablesData.filter(t => t.is_occupied == 0).length;
    const occupiedCount = diningTablesData.filter(t => t.is_occupied == 1).length;
    const activeCount = diningTablesData.filter(t => t.is_active == 1).length;

    const totalEl = document.getElementById('totalTablesCount');
    const availableEl = document.getElementById('availableTablesCount');
    const occupiedEl = document.getElementById('occupiedTablesCount');
    const activeEl = document.getElementById('activeTablesCount');

    if (totalEl) totalEl.textContent = totalCount;
    if (availableEl) availableEl.textContent = availableCount;
    if (occupiedEl) occupiedEl.textContent = occupiedCount;
    if (activeEl) activeEl.textContent = activeCount;
  }

  function renderDiningTablesTable() {
    const tbody = document.querySelector('#diningTablesTableBody');
    if (!tbody) return;
    if (diningTablesData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="6"><div class="dt-empty-state">No dining tables found. <a href="javascript:;" onclick="openAddDiningTableModal()" style="color:#185fa5;font-weight:500;">Add your first dining table</a>.</div></td></tr>`;
      return;
    }
    tbody.innerHTML = diningTablesData.map((table) => {
      return `
        <tr class="dining-table-row">
          <td>
            <div class="dt-name"><span>${table.table_number}</span></div>
          </td>
          <td><span class="dt-text">${table.table_name || '—'}</span></td>
          <td><span class="dt-text">${table.capacity || 4}</span></td>
          <td><span class="dt-text">${table.location || '—'}</span></td>
          <td style="text-align:center;">
            <span class="dt-status-badge dt-status-${table.is_occupied ? 'occupied' : 'available'}">${table.is_occupied ? 'Occupied' : 'Available'}</span>
          </td>
          <td style="text-align:center;">
            <span class="dt-status-badge dt-status-${table.is_active ? 'active' : 'inactive'}">${table.is_active ? 'Active' : 'Inactive'}</span>
          </td>
          <td>
            <div class="dt-actions">
              <button class="dt-icon-btn edit" onclick="openEditDiningTableModal('${String(table.table_id).replace(/'/g, "\\'")}')" title="Edit"><i class="fas fa-pen"></i></button>
              <button class="dt-icon-btn del" onclick="confirmDeleteDiningTable('${String(table.table_id).replace(/'/g, "\\'")}', '${String(table.table_number).replace(/'/g, "\\'")}')" title="Delete"><i class="fas fa-trash"></i></button>
              <div class="dt-actions-dropdown" id="dtActDrop-${table.table_id}">
                <button class="dt-actions-dropdown-btn" onclick="dtToggleActionsMenu('${table.table_id}', event)" title="Actions">
                  <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="dt-actions-dropdown-menu" id="dtActMenu-${table.table_id}">
                  <button onclick="openEditDiningTableModal('${String(table.table_id).replace(/'/g, "\\'")}');dtCloseActionsMenu('${table.table_id}')">
                    <i class="fas fa-pen"></i> Edit Table
                  </button>
                  <button class="del-action" onclick="confirmDeleteDiningTable('${String(table.table_id).replace(/'/g, "\\'")}', '${String(table.table_number).replace(/'/g, "\\'")}');dtCloseActionsMenu('${table.table_id}')">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </div>
              </div>
            </div>
          </td>
        </tr>
      `;
    }).join('');
  }

  // Mobile actions dropdown helpers
  function dtToggleActionsMenu(tableId, event) {
    event.stopPropagation();
    const menu = document.getElementById('dtActMenu-' + tableId);
    document.querySelectorAll('.dt-actions-dropdown-menu.open').forEach(m => { if (m !== menu) m.classList.remove('open'); });
    menu.classList.toggle('open');
  }

  function dtCloseActionsMenu(tableId) {
    const menu = document.getElementById('dtActMenu-' + tableId);
    if (menu) menu.classList.remove('open');
  }

  document.addEventListener('click', function() {
    document.querySelectorAll('.dt-actions-dropdown-menu.open').forEach(m => m.classList.remove('open'));
  });

  async function confirmDeleteDiningTable(tableId, tableNumber) {
    if (!confirm(`Delete table "${tableNumber}"? This action cannot be undone.`)) return;
    try {
      const response = await fetch('../api/dining_tables.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'delete', table_id: tableId, csrf_token: CSRF_TOKEN })
      });
      const result = await response.json();
      if (result.success) {
        showDtToast('Dining table deleted successfully!');
        await fetchDiningTablesData();
      } else {
        showDtToast(result.message || 'Failed to delete dining table', 'error');
      }
    } catch (error) {
      showDtToast('An error occurred while deleting the dining table', 'error');
    }
  }

  // Unified Form Submission (API-based)
  async function handleDiningTableFormSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const tableId = formData.get('table_id');
    const isEdit = !!tableId;
    const table_id = isEdit ? tableId : generateUUID();
    const data = {
      table_id: table_id,
      property_id: formData.get('property_id') || null,
      table_number: formData.get('table_number'),
      table_name: formData.get('table_name') || null,
      capacity: formData.get('capacity') || 4,
      location: formData.get('location') || null,
      is_occupied: formData.get('is_occupied') || 0,
      is_active: formData.get('is_active') || 1,
      sort_order: formData.get('sort_order') || 0
    };
    try {
      const response = await fetch('../api/dining_tables.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: isEdit ? 'update' : 'create', ...data, csrf_token: CSRF_TOKEN })
      });
      const result = await response.json();
      if (result.success) {
        showDtToast(isEdit ? 'Dining table updated successfully!' : 'Dining table added successfully!');
        closeDiningTableFormModal();
        await fetchDiningTablesData();
      } else {
        showDtToast(result.message || 'Failed to save dining table', 'error');
      }
    } catch (error) {
      showDtToast('An error occurred while saving the dining table', 'error');
    }
  }
</script>

<!-- PAGE CONTENT -->
<div class="dt-page">
  <div class="dt-stats-grid">
    <div class="dt-stat-card">
      <p class="dt-stat-label">Total Tables</p>
      <p class="dt-stat-value" id="totalTablesCount">0</p>
    </div>
    <div class="dt-stat-card">
      <p class="dt-stat-label">Available</p>
      <p class="dt-stat-value" id="availableTablesCount">0</p>
    </div>
    <div class="dt-stat-card">
      <p class="dt-stat-label">Occupied</p>
      <p class="dt-stat-value" id="occupiedTablesCount">0</p>
    </div>
    <div class="dt-stat-card">
      <p class="dt-stat-label">Active</p>
      <p class="dt-stat-value" id="activeTablesCount">0</p>
    </div>
  </div>

  <div class="dt-card">
    <div class="dt-card-header">
      <span class="dt-card-title">Dining Tables</span>
      <div class="dt-toolbar">
        <div class="dt-search-wrap">
          <i class="fas fa-magnifying-glass"></i>
          <input type="text" id="searchInput" class="dt-search-input" placeholder="Search tables…" oninput="filterTable()">
        </div>
        <select class="dt-filter-select" id="statusFilter" onchange="filterTable()">
          <option value="">All status</option>
          <option value="available">Available</option>
          <option value="occupied">Occupied</option>
        </select>
        <select class="dt-filter-select" id="activeFilter" onchange="filterTable()">
          <option value="">All active status</option>
          <option value="1">Active</option>
          <option value="0">Inactive</option>
        </select>
        <button class="dt-btn dt-btn-primary" onclick="openAddDiningTableModal()">
          <i class="fas fa-plus"></i> Add Table
        </button>
      </div>
    </div>
    <div class="dt-table-wrap">
      <table class="dt-table" id="diningTablesTable">
        <thead>
          <tr>
            <th>Table Number</th>
            <th>Table Name</th>
            <th>Capacity</th>
            <th>Location</th>
            <th style="text-align:center;">Occupancy</th>
            <th style="text-align:center;">Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody id="diningTablesTableBody"></tbody>
      </table>
    </div>
    <div id="paginationContainer"></div>
  </div>
</div>

<div id="dtToast" class="dt-toast"></div>

<!-- Add/Edit Dining Table Modal -->
<div id="diningTableFormModal" class="dt-modal-backdrop" onclick="if(event.target===this)closeDiningTableFormModal()">
  <div class="dt-modal">
    <div class="dt-modal-header">
      <span class="dt-modal-title" id="formModalTitle">Add Dining Table</span>
      <button class="dt-modal-close" onclick="closeDiningTableFormModal()"><i class="fas fa-times"></i></button>
    </div>
    <form id="diningTableForm" onsubmit="handleDiningTableFormSubmit(event)">
      <input type="hidden" name="table_id" id="formTableId">
      <div class="dt-modal-body">
        <p class="dt-section-label">Basic Information</p>
        <div class="dt-form-grid">
          <div class="dt-form-group">
            <label>Table Number *</label>
            <input type="text" name="table_number" id="formTableNumber" required placeholder="e.g. T-001">
          </div>
          <div class="dt-form-group">
            <label>Table Name</label>
            <input type="text" name="table_name" id="formTableName" placeholder="e.g. Window Table">
          </div>
          <div class="dt-form-group">
            <label>Capacity *</label>
            <input type="number" name="capacity" id="formCapacity" value="4" min="1" required>
          </div>
          <div class="dt-form-group">
            <label>Location</label>
            <input type="text" name="location" id="formLocation" placeholder="e.g. Main Hall">
          </div>
          <div class="dt-form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" id="formSortOrder" value="0">
          </div>
          <div class="dt-form-group">
            <label class="dt-checkbox-wrap">
              <input type="checkbox" name="is_occupied" id="formIsOccupied" value="1">
              Occupied
            </label>
          </div>
          <div class="dt-form-group">
            <label class="dt-checkbox-wrap">
              <input type="checkbox" name="is_active" id="formIsActive" value="1" checked>
              Active
            </label>
          </div>
        </div>
      </div>
      <div class="dt-modal-footer">
        <button type="button" class="dt-btn" onclick="closeDiningTableFormModal()">Cancel</button>
        <button type="submit" class="dt-btn dt-btn-primary"><i class="fas fa-save"></i> Save Table</button>
      </div>
    </form>
  </div>
</div>

<script>
  function filterTable() {
    const filter = (document.getElementById('searchInput')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilter')?.value || '').toLowerCase();
    const activeFilter = (document.getElementById('activeFilter')?.value || '');
    const rows = document.querySelectorAll('#diningTablesTableBody .dining-table-row');
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      const matchText = !filter || text.includes(filter);
      const matchStatus = !statusFilter || text.includes(statusFilter);
      const matchActive = !activeFilter || row.textContent.includes(activeFilter === '1' ? 'active' : 'inactive');
      row.style.display = (matchText && matchStatus && matchActive) ? '' : 'none';
    });
  }

  function openAddDiningTableModal() {
    document.getElementById('diningTableForm').reset();
    document.getElementById('formModalTitle').textContent = 'Add Dining Table';
    document.getElementById('formIsActive').checked = true;
    document.getElementById('formIsOccupied').checked = false;
    document.getElementById('formCapacity').value = 4;
    document.getElementById('formSortOrder').value = 0;
    document.getElementById('diningTableFormModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function openEditDiningTableModal(tableId) {
    const table = diningTablesData.find(t => t.table_id == tableId);
    if (!table) return;
    document.getElementById('formModalTitle').textContent = 'Edit Dining Table';
    document.getElementById('formTableId').value = table.table_id;
    document.getElementById('formTableNumber').value = table.table_number;
    document.getElementById('formTableName').value = table.table_name || '';
    document.getElementById('formCapacity').value = table.capacity || 4;
    document.getElementById('formLocation').value = table.location || '';
    document.getElementById('formSortOrder').value = table.sort_order || 0;
    document.getElementById('formIsOccupied').checked = table.is_occupied == 1;
    document.getElementById('formIsActive').checked = table.is_active == 1;
    document.getElementById('diningTableFormModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeDiningTableFormModal() {
    document.getElementById('diningTableFormModal').classList.remove('open');
    document.body.style.overflow = '';
    document.getElementById('diningTableForm').reset();
  }

  // Pagination functions
  function renderPagination() {
    const paginationContainer = document.getElementById('paginationContainer');
    if (!paginationContainer) return;

    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const startItem = totalItems === 0 ? 0 : (currentPage - 1) * itemsPerPage + 1;
    const endItem = Math.min(currentPage * itemsPerPage, totalItems);

    let paginationHtml = `
      <div class="pagination">
        <div class="pag-info">
          Showing ${startItem} to ${endItem} of ${totalItems} entries
        </div>
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap">
          <div class="per-page-wrap">
            Show
            <select
              style="width: 64px; padding: 4px 8px; font-size: 12px"
              onchange="changeItemsPerPage(this.value)"
            >
              <option value="5" ${itemsPerPage === 5 ? 'selected' : ''}>5</option>
              <option value="10" ${itemsPerPage === 10 ? 'selected' : ''}>10</option>
              <option value="25" ${itemsPerPage === 25 ? 'selected' : ''}>25</option>
              <option value="50" ${itemsPerPage === 50 ? 'selected' : ''}>50</option>
            </select>
            entries
          </div>
          <div class="pag-controls">
            <button onclick="goToPage(1)" ${currentPage === 1 ? 'disabled' : ''} class="pag-btn">
              <i class="fas fa-chevrons-left"></i>
            </button>
            <button onclick="goToPage(currentPage - 1)" ${currentPage === 1 ? 'disabled' : ''} class="pag-btn">
              <i class="fas fa-chevron-left"></i>
            </button>
            ${getPageNumbers().map(page => `
              <button onclick="goToPage(${page})" ${page === currentPage ? 'disabled' : ''} class="pag-btn ${page === currentPage ? 'active' : ''}">
                ${page}
              </button>
            `).join('')}
            <button onclick="goToPage(currentPage + 1)" ${currentPage === totalPages ? 'disabled' : ''} class="pag-btn">
              <i class="fas fa-chevron-right"></i>
            </button>
            <button onclick="goToPage(totalPages)" ${currentPage === totalPages ? 'disabled' : ''} class="pag-btn">
              <i class="fas fa-chevrons-right"></i>
            </button>
          </div>
        </div>
      </div>
    `;

    paginationContainer.innerHTML = paginationHtml;
  }

  function getPageNumbers() {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const pages = [];
    const maxVisible = 5;

    if (totalPages <= maxVisible) {
      for (let i = 1; i <= totalPages; i++) {
        pages.push(i);
      }
    } else {
      if (currentPage <= 3) {
        for (let i = 1; i <= 4; i++) {
          pages.push(i);
        }
        pages.push('...');
        pages.push(totalPages);
      } else if (currentPage >= totalPages - 2) {
        pages.push(1);
        pages.push('...');
        for (let i = totalPages - 3; i <= totalPages; i++) {
          pages.push(i);
        }
      } else {
        pages.push(1);
        pages.push('...');
        for (let i = currentPage - 1; i <= currentPage + 1; i++) {
          pages.push(i);
        }
        pages.push('...');
        pages.push(totalPages);
      }
    }
    return pages;
  }

  function changeItemsPerPage(value) {
    itemsPerPage = parseInt(value);
    currentPage = 1;
    fetchDiningTablesData();
  }

  function goToPage(page) {
    if (page === '...' || page < 1 || page > Math.ceil(totalItems / itemsPerPage)) return;
    currentPage = page;
    fetchDiningTablesData();
  }

  window.addEventListener('load', function() {
    <?php if (isset($error_message)): ?>
      showDtToast(<?php echo json_encode($error_message); ?>, 'error');
    <?php endif; ?>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === 'dining_table_added') {
      window.history.replaceState({}, document.title, window.location.pathname);
      showDtToast('Dining table added successfully!');
    }
    if (urlParams.get('success') === 'dining_table_updated') {
      window.history.replaceState({}, document.title, window.location.pathname);
      showDtToast('Dining table updated successfully!');
    }
    if (urlParams.get('success') === 'dining_table_deleted') {
      window.history.replaceState({}, document.title, window.location.pathname);
      showDtToast('Dining table deleted successfully!');
    }
  });
</script>
