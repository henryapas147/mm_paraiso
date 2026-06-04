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
  .rb-page { padding: 24px; max-width: 100%; margin: 0 auto; overflow-x: hidden; }
  .rb-stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
  .rb-stat-card {
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 12px;
    padding: 16px;
  }
  .rb-stat-label { font-size: 12px; font-weight: 500; color: var(--color-text-secondary); text-transform: uppercase; letter-spacing: 0.3px; }
  .rb-stat-value { font-size: 24px; font-weight: 600; color: var(--color-text-primary); margin-top: 4px; }
  .rb-stat-value.blue { color: #185fa5; }
  .rb-stat-value.green { color: #3b6d11; }
  .rb-stat-value.orange { color: #854f0b; }
  .rb-stat-value.red { color: #791f1f; }
  .rb-card {
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 12px;
    margin-bottom: 0;
  }
  .rb-card-header {
    padding: 16px 20px;
    border-bottom: 0.5px solid var(--color-border-tertiary);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }
  .rb-card-title { font-size: 16px; font-weight: 500; }
  .rb-toolbar { display: flex; align-items: center; gap: 8px; flex-wrap: nowrap; }
  .rb-search-wrap {
    display: flex;
    align-items: center;
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 6px;
    padding: 4px 10px 4px 30px;
    background: #f9fafb;
    position: relative;
    min-width: 200px;
  }
  .rb-search-wrap i {
    position: absolute;
    left: 8px;
    color: var(--color-text-secondary);
    font-size: 12px;
  }
  .rb-search-input {
    border: none;
    outline: none;
    background: transparent;
    font-size: 13px;
    width: 100%;
    color: var(--color-text-primary);
  }
  .rb-filter-select {
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 13px;
    color: var(--color-text-primary);
    outline: none;
    background: #f9fafb;
  }
  .rb-btn {
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
  .rb-btn:hover { background: #f1f5f9; border-color: #cbd5e1; }
  .rb-btn-primary {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
    border: none;
  }
  .rb-btn-primary:hover { opacity: 0.9; box-shadow: 0 2px 8px rgba(59,130,246,0.25); }
  .rb-table-wrap { overflow-x: auto; }
  .rb-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }
  .rb-table th {
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
  .rb-table td {
    padding: 10px 12px;
    border-bottom: 0.5px solid var(--color-border-secondary);
    color: var(--color-text-primary);
  }
  .rb-table tr:hover td { background: #f8fafc; }
  .rb-name { font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px; }
  .rb-code { font-size: 12px; color: var(--color-text-secondary); }
  .rb-text { color: var(--color-text-primary); font-size: 13px; }
  .rb-status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }
  .rb-status-active { background: #eaf3de; color: #27500a; }
  .rb-status-inactive { background: #fcebeb; color: #791f1f; }
  .rb-actions { display: flex; gap: 4px; justify-content: flex-end; }
  .rb-actions-dropdown {
    position: relative;
    display: none;
  }
  .rb-actions-dropdown-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px;
    border-radius: 6px;
    border: 0.5px solid var(--color-border-secondary);
    background: #ffffff; color: var(--color-text-secondary);
    cursor: pointer; font-size: 12px;
  }
  .rb-actions-dropdown-btn:hover { background: #f1f5f9; color: var(--color-text-primary); }
  .rb-actions-dropdown-menu {
    position: absolute; right: 0; top: calc(100% + 4px);
    background: #ffffff;
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 6px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    min-width: 140px; z-index: 100; overflow: hidden; display: none;
  }
  .rb-actions-dropdown-menu.open { display: block; }
  .rb-actions-dropdown-menu button {
    display: flex; align-items: center; gap: 8px;
    width: 100%; padding: 8px 12px; font-size: 12px;
    background: transparent; border: none;
    color: var(--color-text-primary); cursor: pointer; text-align: left;
  }
  .rb-actions-dropdown-menu button:hover { background: #f8fafc; }
  .rb-actions-dropdown-menu button.del-action:hover { background: #fef2f2; color: #ef4444; }
  .rb-actions-dropdown-menu button i { width: 16px; text-align: center; font-size: 12px; color: var(--color-text-secondary); }
  .rb-actions-dropdown-menu button.del-action:hover i { color: #ef4444; }
  .rb-icon-btn {
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
  .rb-icon-btn.view:hover { border-color: #3b82f6; color: #3b82f6; background: #eff6ff; }
  .rb-icon-btn.edit:hover { border-color: #f59e0b; color: #f59e0b; background: #fffbeb; }
  .rb-icon-btn.del:hover { border-color: #ef4444; color: #ef4444; background: #fef2f2; }
  .rb-empty-state { text-align: center; padding: 24px; color: var(--color-text-secondary); font-size: 13px; }
  .rb-modal-backdrop {
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
  .rb-modal-backdrop.open { opacity: 1; pointer-events: auto; }
  .rb-modal {
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
  .rb-modal-backdrop.open .rb-modal { transform: scale(1) translateY(0); }
  .rb-modal-header {
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
  .rb-modal-title {
    font-size: 15px;
    font-weight: 500;
    flex: 1;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .rb-modal-close {
    border: none;
    background: none;
    cursor: pointer;
    color: var(--color-text-secondary);
    font-size: 16px;
    padding: 4px;
    border-radius: 4px;
    flex-shrink: 0;
  }
  .rb-modal-close:hover { background: #f1f5f9; color: var(--color-text-primary); }
  .rb-modal-body { padding: 16px 18px; }
  .rb-modal-footer {
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
  .rb-section-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin: 0 0 10px;
  }
  .rb-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px; }
  .rb-form-group { display: flex; flex-direction: column; gap: 4px; }
  .rb-form-group.full { grid-column: 1 / -1; }
  .rb-form-group label { font-size: 12px; font-weight: 500; color: var(--color-text-secondary); }
  .rb-form-group input,
  .rb-form-group select,
  .rb-form-group textarea {
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 6px;
    padding: 7px 10px;
    font-size: 13px;
    color: var(--color-text-primary);
    outline: none;
    background: #ffffff;
  }
  .rb-form-group input:focus,
  .rb-form-group select:focus,
  .rb-form-group textarea:focus { border-color: var(--color-border-primary); }
  .rb-form-group textarea { resize: vertical; min-height: 60px; }
  .rb-checkbox-wrap { display: flex; align-items: center; gap: 6px; font-size: 13px; }
  .rb-checkbox-wrap input[type=checkbox] {
    width: 14px;
    height: 14px;
    accent-color: var(--color-border-primary);
  }
  .rb-searchable-dropdown { position: relative; }
  .rb-searchable-dropdown input[type="text"] {
    width: 100%;
  }
  .rb-dropdown-options {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    max-height: 200px;
    overflow-y: auto;
    background: #ffffff;
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 6px;
    margin-top: 4px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    z-index: 10;
    display: none;
  }
  .rb-dropdown-options.show { display: block; }
  .rb-dropdown-option {
    padding: 8px 10px;
    cursor: pointer;
    font-size: 13px;
    color: var(--color-text-primary);
    transition: background 0.18s ease;
  }
  .rb-dropdown-option:hover { background: #f1f5f9; }
  .rb-dropdown-option.selected { background: #e0f2fe; color: var(--color-border-primary); }
  .rb-dropdown-option .room-name { font-weight: 500; }
  .rb-dropdown-option .room-property { font-size: 11px; color: var(--color-text-secondary); margin-top: 2px; }
  .rb-toast {
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
    transition: all 0.3s ease;
    z-index: 2000;
  }
  .rb-toast.show { opacity: 1; transform: translateY(0); }
  .rb-toast.error { background: #ef4444; }
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

  /* ── Responsive ── */
  @media (max-width: 1400px) {
    .rb-page { padding: 20px; }
    .rb-search-wrap { min-width: 160px; }
  }

  @media (max-width: 1200px) {
    .rb-page { padding: 18px; }
    .rb-card-header { flex-direction: column; align-items: flex-start; }
    .rb-toolbar { width: 100%; flex-wrap: wrap; }
    .rb-search-wrap { min-width: 140px; }
    .rb-btn { padding: 5px 10px; font-size: 12px; }
  }

  @media (max-width: 1024px) {
    .rb-stats-grid { grid-template-columns: repeat(2, 1fr); }
    .rb-table th:nth-child(3),
    .rb-table td:nth-child(3) { display: none; }
    .rb-card-title { font-size: 14px; }
    .rb-table td { padding: 8px 10px; }
    .rb-table th { padding: 8px 10px; font-size: 11px; }
  }

  @media (max-width: 992px) {
    .rb-page { padding: 16px; }
    .rb-card-header { padding: 14px 16px; flex-direction: column; align-items: flex-start; gap: 10px; }
    .rb-toolbar { width: 100%; flex-wrap: wrap; gap: 6px; }
    .rb-search-wrap { min-width: 100%; }
    .rb-filter-select { flex: 1 1 auto; }
    .rb-btn { padding: 5px 10px; font-size: 12px; }
    .rb-table td { padding: 8px 10px; }
    .rb-table th { padding: 8px 10px; font-size: 10px; }
    .rb-name { font-size: 12px; }
    .rb-code { font-size: 11px; }
    .rb-text { font-size: 12px; }
    .rb-status-badge { font-size: 10px; padding: 2px 8px; }
    .rb-icon-btn { width: 26px; height: 26px; font-size: 11px; }
    .rb-actions { gap: 3px; }
    .pag-info { font-size: 11px; }
    .pag-btn { min-width: 28px; height: 28px; font-size: 11px; }
    /* Modal */
    .rb-modal { max-width: 96vw; }
    .rb-modal-header { padding: 12px 16px; }
    .rb-modal-body { padding: 14px 16px; }
    .rb-modal-footer { padding: 10px 16px; }
  }

  @media (max-width: 768px) {
    .rb-page { padding: 14px; }
    .rb-card-header { padding: 12px; }
    .rb-card-title { font-size: 13px; }
    .rb-toolbar { gap: 6px; }
    .rb-stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .rb-form-grid { grid-template-columns: 1fr; }
    .rb-form-group.full { grid-column: 1; }
    .rb-table th:nth-child(2),
    .rb-table td:nth-child(2) { display: none; }
    .rb-table td { padding: 8px 10px; }
    .rb-table th { padding: 8px 10px; font-size: 9px; }
    .rb-name { font-size: 11px; }
    .rb-code { font-size: 10px; }
    .rb-status-badge { font-size: 9px; padding: 2px 6px; }
    .rb-icon-btn { width: 24px; height: 24px; font-size: 11px; }
    .rb-actions { gap: 2px; }
    /* Slide-up modal */
    .rb-modal-backdrop { padding: 0; align-items: flex-end; }
    .rb-modal { border-radius: 16px 16px 0 0; max-width: 100%; max-height: 95vh; width: 100%; }
    .rb-modal-header { border-radius: 16px 16px 0 0; padding: 12px 16px; }
    .rb-modal-footer { border-radius: 0; padding: 10px 16px; flex-wrap: wrap; }
    .rb-modal-body { padding: 12px 16px; }
    .rb-modal-title { font-size: 13px; }
    .pagination { flex-direction: column; align-items: flex-start; gap: 8px; }
    .per-page-wrap { display: none; }
  }

  @media (max-width: 640px) {
    .rb-card-header { flex-direction: column; align-items: stretch; }
    .rb-toolbar { flex-direction: column; align-items: stretch; }
    .rb-search-wrap, .rb-filter-select, .rb-btn { width: 100%; }
    /* Dropdown actions on small screens */
    .rb-actions .rb-icon-btn { display: none; }
    .rb-actions-dropdown { display: block; }
  }

  @media (max-width: 480px) {
    .rb-page { padding: 12px; }
    .rb-card-header { padding: 10px 12px; }
    .rb-toolbar { gap: 4px; }
    .rb-btn { font-size: 11px; padding: 5px 10px; }
    .rb-icon-btn { width: 24px; height: 24px; }
    .rb-stats-grid { grid-template-columns: 1fr; }
    .rb-actions .rb-icon-btn { display: none; }
    .rb-actions-dropdown { display: block; }
    .rb-modal-footer { flex-direction: column; align-items: stretch; }
    .rb-modal-footer .rb-btn { width: 100%; justify-content: center; }
    .rb-modal-title { font-size: 12px; }
    .rb-form-grid { gap: 10px; }
    .rb-form-group input, .rb-form-group select, .rb-form-group textarea { font-size: 14px; padding: 7px 10px; }
    .rb-section-label { font-size: 10px; }
    .pag-btn { min-width: 26px; height: 26px; font-size: 10px; }
  }

  @media (max-width: 360px) {
    .rb-page { padding: 10px; }
    .rb-card-title { font-size: 12px; }
    .rb-modal-header { padding: 10px 12px; }
    .rb-modal-body { padding: 10px 12px; }
    .rb-modal-footer { padding: 8px 12px; }
    .rb-modal-title { font-size: 11px; }
    .rb-form-group input, .rb-form-group select, .rb-form-group textarea { font-size: 13px; padding: 6px 9px; }
    .pag-btn { min-width: 24px; height: 24px; font-size: 9px; }
  }

  @media (max-width: 768px) and (orientation: landscape) {
    .rb-modal-backdrop { padding: 8px; align-items: center; }
    .rb-modal { border-radius: 12px; max-height: 90vh; }
    .rb-modal-header { border-radius: 12px 12px 0 0; }
    .rb-modal-body { padding: 10px 14px; }
    .rb-form-grid { grid-template-columns: 1fr 1fr; }
    .rb-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
  }

  @media (hover: none) and (pointer: coarse) {
    .rb-icon-btn { width: 32px; height: 32px; }
    .rb-btn { padding: 8px 14px; }
    .rb-form-group input, .rb-form-group select, .rb-form-group textarea { font-size: 16px; }
  }
</style>

<script>
  // CSRF Token for API requests (from db.php)
  const CSRF_TOKEN = <?php echo json_encode(CSRF_TOKEN); ?>;

  // Data will be fetched from API on page load
  let blocksData = [];
  let propertiesData = [];
  let roomsData = [];
  let currentPage = 1;
  let itemsPerPage = 10;
  let totalItems = 0;

  // Fetch data from API on page load
  // UUID generator function
  function generateUUID() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
      const r = Math.random() * 16 | 0;
      const v = c === 'x' ? r : (r & 0x3 | 0x8);
      return v.toString(16);
    });
  }

  document.addEventListener('DOMContentLoaded', async function() {
    await fetchBlocksData();
  });

  async function fetchBlocksData() {
    try {
      // Fetch blocks with pagination
      const offset = (currentPage - 1) * itemsPerPage;
      const response = await fetch(`../api/room_blocks.php?action=list&limit=${itemsPerPage}&offset=${offset}`);
      const result = await response.json();
      if (result.success) {
        blocksData = result.data;
        totalItems = result.total ?? blocksData.length;
      }

      // Fetch properties for dropdowns
      const propsResponse = await fetch('../api/properties.php?action=list');
      const propsResult = await propsResponse.json();
      if (propsResult.success) {
        propertiesData = propsResult.data.data || propsResult.data;
      }

      // Fetch rooms for dropdowns (fetch all without pagination)
      const roomsResponse = await fetch('../api/rooms.php?action=list&per_page=100');
      const roomsResult = await roomsResponse.json();
      if (roomsResult.success) {
        roomsData = roomsResult.data.data || roomsResult.data;
      }

      renderBlocksTable();
      renderPagination();
      populateDropdowns();
      updateStatsCounts();
    } catch (error) {
      console.error('Error fetching blocks:', error);
      showRbToast('Failed to load blocks data', 'error');
    }
  }

  function showRbToast(message, type = 'success') {
    const toast = document.getElementById('rbToast');
    if (!toast) return;
    toast.textContent = message;
    toast.className = 'rb-toast show ' + (type === 'error' ? 'error' : '');
    setTimeout(() => {
      toast.className = 'rb-toast';
    }, 3000);
  }

  function populateDropdowns() {
    const addPropertyDropdown = document.getElementById('addPropertyDropdown');
    const editPropertyDropdown = document.getElementById('editPropertyDropdown');
    const addRoomDropdown = document.getElementById('addRoomDropdown');
    const editRoomDropdown = document.getElementById('editRoomDropdown');
    
    if (addPropertyDropdown && propertiesData.length > 0) {
      addPropertyDropdown.innerHTML = '<option value="">Select Property</option>';
      propertiesData.forEach(property => {
        const option = document.createElement('option');
        option.value = property.property_id;
        option.textContent = `${property.property_name} (${property.property_code})`;
        addPropertyDropdown.appendChild(option);
      });
    }

    if (editPropertyDropdown && propertiesData.length > 0) {
      editPropertyDropdown.innerHTML = '<option value="">Select Property</option>';
      propertiesData.forEach(property => {
        const option = document.createElement('option');
        option.value = property.property_id;
        option.textContent = `${property.property_name} (${property.property_code})`;
        editPropertyDropdown.appendChild(option);
      });
    }

    if (addRoomDropdown && roomsData.length > 0) {
      addRoomDropdown.innerHTML = '<option value="">Select Room</option>';
      roomsData.forEach(room => {
        const option = document.createElement('option');
        option.value = room.room_id;
        option.textContent = `${room.room_name || room.room_number}`;
        addRoomDropdown.appendChild(option);
      });
    }

    if (editRoomDropdown && roomsData.length > 0) {
      editRoomDropdown.innerHTML = '<option value="">Select Room</option>';
      roomsData.forEach(room => {
        const option = document.createElement('option');
        option.value = room.room_id;
        option.textContent = `${room.room_name || room.room_number}`;
        editRoomDropdown.appendChild(option);
      });
    }
  }

  function updateStatsCounts() {
    const totalCount = blocksData.length;
    const maintenanceCount = blocksData.filter(b => b.block_type === 'maintenance').length;
    const ownerCount = blocksData.filter(b => b.block_type === 'owner_use').length;

    const today = new Date().toISOString().split('T')[0];
    const activeCount = blocksData.filter(b => b.is_active == 1 && b.date_start <= today && b.date_end >= today).length;

    const totalEl = document.getElementById('totalBlocksCount');
    const maintenanceEl = document.getElementById('maintenanceBlocksCount');
    const ownerEl = document.getElementById('ownerBlocksCount');
    const activeEl = document.getElementById('activeBlocksCount');

    if (totalEl) totalEl.textContent = totalCount;
    if (maintenanceEl) maintenanceEl.textContent = maintenanceCount;
    if (ownerEl) ownerEl.textContent = ownerCount;
    if (activeEl) activeEl.textContent = activeCount;
  }

  function renderBlocksTable() {
    const tbody = document.querySelector('#blocksTableBody');
    if (!tbody) return;

    if (blocksData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="6"><div class="rb-empty-state">No blocks found. <a href="javascript:;" onclick="openAddBlockModal()" style="color:#185fa5;font-weight:500;">Add your first block</a>.</div></td></tr>`;
      return;
    }

    tbody.innerHTML = blocksData.map((block) => {
      const statusClass = block.is_active == 1 ? 'rb-status-active' : 'rb-status-inactive';
      const statusText = block.is_active == 1 ? 'Active' : 'Inactive';

      // Format block type: ucfirst and replace underscores with spaces
      const formatBlockType = (type) => {
        if (!type) return 'N/A';
        return type.charAt(0).toUpperCase() + type.slice(1).replace(/_/g, ' ');
      };

      // Format date as "May 4, 2026"
      const formatDate = (dateStr) => {
        if (!dateStr) return 'N/A';
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
      };

      // Format time as 12hr format (e.g., "10:02 AM")
      const formatTime = (timeStr) => {
        if (!timeStr) return '';
        const [hours, minutes] = timeStr.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        return `${hour12}:${minutes} ${ampm}`;
      };

      const dateRange = `${formatDate(block.date_start)}${block.time_start ? ' ' + formatTime(block.time_start) : ''} - ${formatDate(block.date_end)}${block.time_end ? ' ' + formatTime(block.time_end) : ''}`;

      return `
        <tr class="block-row">
          <td>
            <div class="rb-name">${block.room_name || block.room_number || 'N/A'}</div>
            <div class="rb-code">${block.property_name || 'N/A'}</div>
          </td>
          <td><span class="rb-text">${formatBlockType(block.block_type)}</span></td>
          <td><span class="rb-text">${dateRange}</span></td>
          <td><span class="rb-text">${block.reason || '—'}</span></td>
          <td style="text-align:center;"><span class="rb-status-badge ${statusClass}">${statusText}</span></td>
          <td>
            <div class="rb-actions">
              <button class="rb-icon-btn edit" onclick="openEditBlockModal('${String(block.block_id).replace(/'/g, "\\'")}')" title="Edit"><i class="fas fa-pen"></i></button>
              <button class="rb-icon-btn del" onclick="confirmDeleteBlock('${String(block.block_id).replace(/'/g, "\\'")}')" title="Delete"><i class="fas fa-trash"></i></button>
              <div class="rb-actions-dropdown" id="rbActDrop-${block.block_id}">
                <button class="rb-actions-dropdown-btn" onclick="rbToggleActionsMenu('${block.block_id}', event)" title="Actions">
                  <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="rb-actions-dropdown-menu" id="rbActMenu-${block.block_id}">
                  <button onclick="openEditBlockModal('${String(block.block_id).replace(/'/g, "\\'")}');rbCloseActionsMenu('${block.block_id}')">
                    <i class="fas fa-pen"></i> Edit Block
                  </button>
                  <button class="del-action" onclick="confirmDeleteBlock('${String(block.block_id).replace(/'/g, "\\'")}');rbCloseActionsMenu('${block.block_id}')">
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
  function rbToggleActionsMenu(blockId, event) {
    event.stopPropagation();
    const menu = document.getElementById('rbActMenu-' + blockId);
    document.querySelectorAll('.rb-actions-dropdown-menu.open').forEach(m => { if (m !== menu) m.classList.remove('open'); });
    menu.classList.toggle('open');
  }

  function rbCloseActionsMenu(blockId) {
    const menu = document.getElementById('rbActMenu-' + blockId);
    if (menu) menu.classList.remove('open');
  }

  document.addEventListener('click', function() {
    document.querySelectorAll('.rb-actions-dropdown-menu.open').forEach(m => m.classList.remove('open'));
  });

  // Delete Block Function (API-based)
  async function confirmDeleteBlock(blockId) {
    if (confirm('Are you sure you want to delete this block? This action cannot be undone.')) {
      try {
        const response = await fetch('../api/room_blocks.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            action: 'delete',
            block_id: blockId,
            csrf_token: CSRF_TOKEN
          })
        });
        const result = await response.json();

        if (result.success) {
          showRbToast('Block deleted successfully');
          await fetchBlocksData();
        } else {
          showRbToast(result.message || 'Failed to delete block', 'error');
        }
      } catch (error) {
        showRbToast('An error occurred while deleting the block', 'error');
      }
    }
  }

  // Add Block Form Submission (API-based)
  async function handleAddBlockSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // Generate UUID for block_id
    const block_id = generateUUID();

    // Prepare data for API
    const data = {
      block_id: block_id,
      property_id: formData.get('property_id'),
      room_id: formData.get('room_id'),
      block_name: formData.get('block_name'),
      block_type: formData.get('block_type') || 'maintenance',
      start_date: formData.get('start_date'),
      end_date: formData.get('end_date'),
      start_time: formData.get('start_time'),
      end_time: formData.get('end_time'),
      reason: formData.get('reason'),
      status: formData.get('status') || 'pending',
      notes: formData.get('notes'),
      csrf_token: CSRF_TOKEN
    };

    try {
      const response = await fetch('../api/room_blocks.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: 'create', ...data })
      });
      const result = await response.json();

      if (result.success) {
        showRbToast('Block added successfully');
        closeAddBlockModal();
        await fetchBlocksData();
      } else {
        showRbToast(result.message || 'Failed to add block', 'error');
      }
    } catch (error) {
      showRbToast('An error occurred while adding the block', 'error');
    }
  }

  // Edit Block Form Submission (API-based)
  async function handleEditBlockSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    const data = {
      block_id: formData.get('block_id'),
      property_id: formData.get('property_id'),
      room_id: formData.get('room_id'),
      block_name: formData.get('block_name'),
      block_type: formData.get('block_type') || 'maintenance',
      start_date: formData.get('start_date'),
      end_date: formData.get('end_date'),
      start_time: formData.get('start_time'),
      end_time: formData.get('end_time'),
      reason: formData.get('reason'),
      status: formData.get('status') || 'pending',
      notes: formData.get('notes'),
      csrf_token: CSRF_TOKEN
    };

    try {
      const response = await fetch('../api/room_blocks.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: 'update', ...data })
      });
      const result = await response.json();

      if (result.success) {
        showRbToast('Block updated successfully');
        closeEditBlockModal();
        await fetchBlocksData();
      } else {
        showRbToast(result.message || 'Failed to update block', 'error');
      }
    } catch (error) {
      showRbToast('An error occurred while updating the block', 'error');
    }
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
    fetchBlocksData();
  }

  function goToPage(page) {
    if (page === '...' || page < 1 || page > Math.ceil(totalItems / itemsPerPage)) return;
    currentPage = page;
    fetchBlocksData();
  }
</script>

<!-- PAGE CONTENT -->
<div class="rb-page">
  <!-- Stats Cards -->
  <div class="rb-stats-grid">
    <div class="rb-stat-card">
      <div class="rb-stat-label">Total Blocks</div>
      <div class="rb-stat-value" id="totalBlocksCount">0</div>
    </div>
    <div class="rb-stat-card">
      <div class="rb-stat-label">Maintenance</div>
      <div class="rb-stat-value orange" id="maintenanceBlocksCount">0</div>
    </div>
    <div class="rb-stat-card">
      <div class="rb-stat-label">Owner Use</div>
      <div class="rb-stat-value blue" id="ownerBlocksCount">0</div>
    </div>
    <div class="rb-stat-card">
      <div class="rb-stat-label">Active Now</div>
      <div class="rb-stat-value red" id="activeBlocksCount">0</div>
    </div>
  </div>

  <!-- Room Blocks Table -->
  <div class="rb-card">
    <div class="rb-card-header">
      <div class="rb-card-title">Room Availability Blocks</div>
      <div class="rb-toolbar">
        <div class="rb-search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" id="searchInput" class="rb-search-input" placeholder="Search blocks..." onkeyup="filterTable()">
        </div>
        <select id="filterType" class="rb-filter-select" onchange="filterTable()">
          <option value="">All Types</option>
          <option value="maintenance">Maintenance</option>
          <option value="owner_use">Owner Use</option>
          <option value="event_hold">Event Hold</option>
          <option value="deep_cleaning">Deep Cleaning</option>
          <option value="renovation">Renovation</option>
          <option value="other">Other</option>
        </select>
        <button onclick="openAddBlockModal()" class="rb-btn rb-btn-primary">
          <i class="fas fa-plus"></i> Add Block
        </button>
      </div>
    </div>
    <div class="rb-table-wrap">
      <table class="rb-table" id="blocksTable">
        <thead>
          <tr>
            <th>Room</th>
            <th>Block Type</th>
            <th>Date Range</th>
            <th>Reason</th>
            <th style="text-align:center;">Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody id="blocksTableBody"></tbody>
      </table>
    </div>
    <div id="paginationContainer"></div>
  </div>
</div>

<div id="rbToast" class="rb-toast"></div>

<!-- Add/Edit Block Modal -->
<div id="blockFormModal" class="rb-modal-backdrop" onclick="if(event.target===this)closeBlockFormModal()">
  <div class="rb-modal">
    <div class="rb-modal-header">
      <span class="rb-modal-title" id="formModalTitle">Add New Block</span>
      <button class="rb-modal-close" onclick="closeBlockFormModal()"><i class="fas fa-times"></i></button>
    </div>
    <form id="blockForm" onsubmit="handleBlockFormSubmit(event)">
      <input type="hidden" name="block_id" id="formBlockId">
      <div class="rb-modal-body">
        <p class="rb-section-label">Block Information</p>
        <div class="rb-form-grid">
          <div class="rb-form-group">
            <label>Room *</label>
            <div class="rb-searchable-dropdown">
              <input type="text" id="formRoomSearch" placeholder="Search rooms..." autocomplete="off">
              <input type="hidden" name="room_id" id="formRoomId" required>
              <div id="formRoomDropdown" class="rb-dropdown-options"></div>
            </div>
          </div>
          <div class="rb-form-group">
            <label>Block Type *</label>
            <select name="block_type" id="formBlockType" required>
              <option value="maintenance">Maintenance</option>
              <option value="owner_use">Owner Use</option>
              <option value="event_hold">Event Hold</option>
              <option value="deep_cleaning">Deep Cleaning</option>
              <option value="renovation">Renovation</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="rb-form-group">
            <label>Start Date *</label>
            <input type="date" name="date_start" id="formStartDate" required>
          </div>
          <div class="rb-form-group">
            <label>End Date *</label>
            <input type="date" name="date_end" id="formEndDate" required>
          </div>
          <div class="rb-form-group">
            <label>Start Time</label>
            <input type="time" name="time_start" id="formStartTime">
          </div>
          <div class="rb-form-group">
            <label>End Time</label>
            <input type="time" name="time_end" id="formEndTime">
          </div>
          <div class="rb-form-group full">
            <label>Reason</label>
            <textarea name="reason" id="formReason" rows="3" placeholder="Reason for blocking..."></textarea>
          </div>
          <div class="rb-form-group full">
            <div class="rb-checkbox-wrap">
              <input type="checkbox" name="is_active" value="1" id="formIsActive" checked>
              <span>Active</span>
            </div>
          </div>
        </div>
      </div>
      <div class="rb-modal-footer">
        <button type="button" onclick="closeBlockFormModal()" class="rb-btn">Cancel</button>
        <button type="submit" class="rb-btn rb-btn-primary">Save Block</button>
      </div>
    </form>
  </div>
</div>

<script>
  // Filter table function
  function filterTable() {
    const input = document.getElementById('searchInput');
    const typeFilter = document.getElementById('filterType');
    const filter = input.value.toLowerCase();
    const typeValue = typeFilter.value.toLowerCase();
    const table = document.getElementById('blocksTable');
    const rows = table.getElementsByClassName('block-row');

    for (let i = 0; i < rows.length; i++) {
      const row = rows[i];
      const cells = row.getElementsByTagName('td');
      let found = false;
      let typeMatch = true;

      for (let j = 0; j < cells.length; j++) {
        if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
          found = true;
        }
      }

      if (typeValue) {
        const blockTypeCell = cells[1];
        if (blockTypeCell && !blockTypeCell.textContent.toLowerCase().includes(typeValue)) {
          typeMatch = false;
        }
      }

      row.style.display = (found && typeMatch) ? '' : 'none';
    }
  }

  // Modal functions
  function openAddBlockModal() {
    document.getElementById('formModalTitle').textContent = 'Add New Block';
    document.getElementById('formBlockId').value = '';
    document.getElementById('blockForm').reset();
    document.getElementById('formIsActive').checked = true;
    document.getElementById('blockFormModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function openEditBlockModal(blockId) {
    const block = blocksData.find(b => b.block_id == blockId);
    if (!block) return;
    document.getElementById('formModalTitle').textContent = 'Edit Block';
    document.getElementById('formBlockId').value = block.block_id;

    // Populate searchable dropdown
    const room = roomsData.find(r => r.room_id === block.room_id);
    const property = propertiesData.find(p => p.property_id === room?.property_id);
    if (room) {
      document.getElementById('formRoomId').value = room.room_id;
      document.getElementById('formRoomSearch').value = `${room.room_name || room.room_number} - ${property ? property.property_name : ''}`;
    }

    document.getElementById('formBlockType').value = block.block_type || 'maintenance';
    document.getElementById('formStartDate').value = block.date_start || '';
    document.getElementById('formEndDate').value = block.date_end || '';
    document.getElementById('formStartTime').value = block.time_start || '';
    document.getElementById('formEndTime').value = block.time_end || '';
    document.getElementById('formReason').value = block.reason || '';
    document.getElementById('formIsActive').checked = block.is_active == 1;
    document.getElementById('blockFormModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeBlockFormModal() {
    document.getElementById('blockFormModal').classList.remove('open');
    document.body.style.overflow = '';
    document.getElementById('blockForm').reset();
  }

  // Consolidate form handler for add/edit
  async function handleBlockFormSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const blockId = formData.get('block_id');

    const data = {
      room_id: formData.get('room_id'),
      block_type: formData.get('block_type') || 'maintenance',
      date_start: formData.get('date_start'),
      date_end: formData.get('date_end'),
      time_start: formData.get('time_start'),
      time_end: formData.get('time_end'),
      reason: formData.get('reason'),
      is_active: formData.get('is_active') ? 1 : 0,
      csrf_token: CSRF_TOKEN
    };

    if (blockId) {
      data.block_id = blockId;
      data.action = 'update';
    } else {
      data.block_id = generateUUID();
      data.action = 'create';
    }

    try {
      const response = await fetch('../api/room_blocks.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      });
      const result = await response.json();

      if (result.success) {
        showRbToast(blockId ? 'Block updated successfully' : 'Block added successfully');
        closeBlockFormModal();
        await fetchBlocksData();
      } else {
        showRbToast(result.message || 'Failed to save block', 'error');
      }
    } catch (error) {
      showRbToast('An error occurred while saving the block', 'error');
    }
  }

  // Update populateDropdowns to use new modal form ID
  function populateDropdowns() {
    const formRoomDropdown = document.getElementById('formRoomDropdown');

    if (formRoomDropdown && roomsData.length > 0) {
      renderRoomDropdown(roomsData);
    }
  }

  function renderRoomDropdown(rooms) {
    const formRoomDropdown = document.getElementById('formRoomDropdown');
    if (!formRoomDropdown) return;

    if (rooms.length === 0) {
      formRoomDropdown.innerHTML = '<div class="rb-dropdown-option" style="color:var(--color-text-secondary);cursor:default;">No rooms found</div>';
      return;
    }

    formRoomDropdown.innerHTML = rooms.map(room => {
      const property = propertiesData.find(p => p.property_id === room.property_id);
      return `
        <div class="rb-dropdown-option" data-room-id="${room.room_id}" onclick="selectRoom('${room.room_id}', '${room.room_name || room.room_number}', '${property ? property.property_name : ''}')">
          <div class="room-name">${room.room_name || room.room_number}</div>
          <div class="room-property">${property ? property.property_name : ''}</div>
        </div>
      `;
    }).join('');
  }

  function selectRoom(roomId, roomName, propertyName) {
    document.getElementById('formRoomId').value = roomId;
    document.getElementById('formRoomSearch').value = `${roomName} - ${propertyName}`;
    document.getElementById('formRoomDropdown').classList.remove('show');
  }

  // Search functionality for room dropdown
  document.addEventListener('DOMContentLoaded', function() {
    const roomSearchInput = document.getElementById('formRoomSearch');
    const roomDropdown = document.getElementById('formRoomDropdown');

    if (roomSearchInput) {
      roomSearchInput.addEventListener('focus', function() {
        renderRoomDropdown(roomsData);
        roomDropdown.classList.add('show');
      });

      roomSearchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const filteredRooms = roomsData.filter(room => {
          const property = propertiesData.find(p => p.property_id === room.property_id);
          const roomName = (room.room_name || room.room_number).toLowerCase();
          const propertyName = property ? property.property_name.toLowerCase() : '';
          return roomName.includes(searchTerm) || propertyName.includes(searchTerm);
        });
        renderRoomDropdown(filteredRooms);
        roomDropdown.classList.add('show');
      });

      // Close dropdown when clicking outside
      document.addEventListener('click', function(e) {
        if (!e.target.closest('.rb-searchable-dropdown')) {
          roomDropdown.classList.remove('show');
        }
      });
    }
  });
</script>

</html>