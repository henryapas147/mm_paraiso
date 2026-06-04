<?php if (false) {} ?>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Lora:ital,wght@0,600;1,500&display=swap" rel="stylesheet">
<style>
  /* ── Design Tokens (shared system) ── */
  :root {
    --brand:          #C84B31;
    --brand-dark:     #A03525;
    --brand-light:    #FDF0ED;
    --accent:         #F0A500;
    --accent-light:   #FFF8E7;
    --surface:        #FFFFFF;
    --surface-2:      #FAFAFA;
    --border:         #EDEAE6;
    --border-strong:  #D6D0C8;
    --text-primary:   #1A1410;
    --text-secondary: #6B6460;
    --text-muted:     #A09890;
    --success:        #2D7D4F;
    --success-bg:     #EDFAF3;
    --danger:         #C0392B;
    --danger-bg:      #FEF0EE;
    --info:           #1D5FA1;
    --info-bg:        #EFF6FF;
    --radius-sm:      6px;
    --radius-md:      10px;
    --radius-lg:      16px;
    --radius-xl:      20px;
    --shadow-card:    0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.06);
    --shadow-modal:   0 24px 64px rgba(0,0,0,.18);
    --font-display:   'Lora', Georgia, serif;
    --font-body:      'DM Sans', system-ui, sans-serif;
  }

  /* ── Stats Grid ── */
  .fc-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }
  @media (max-width: 1024px) { .fc-stats-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 600px)  { .fc-stats-grid { grid-template-columns: 1fr 1fr; } }

  .fc-stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 20px 22px;
    box-shadow: var(--shadow-card);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: box-shadow .2s, transform .2s;
  }
  .fc-stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(0,0,0,.10); }

  .fc-stat-icon {
    width: 48px; height: 48px;
    border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
  }
  .fc-stat-icon.total    { background: #EFF3FF; color: #3B5BDB; }
  .fc-stat-icon.active   { background: var(--success-bg); color: var(--success); }
  .fc-stat-icon.inactive { background: #F4F4F5; color: #71717A; }
  .fc-stat-icon.global   { background: var(--info-bg); color: var(--info); }

  .fc-stat-label {
    font-family: var(--font-body);
    font-size: 11.5px; font-weight: 600;
    letter-spacing: .06em; text-transform: uppercase;
    color: var(--text-muted); margin-bottom: 4px;
  }
  .fc-stat-value {
    font-family: var(--font-display);
    font-size: 28px; font-weight: 600;
    color: var(--text-primary); line-height: 1;
  }

  /* ── Main Card ── */
  .fc-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-card);
    overflow: hidden;
    margin-bottom: 24px;
  }

  .fc-card-header {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 20px 24px 18px;
    border-bottom: 1px solid var(--border);
    gap: 12px; flex-wrap: wrap;
  }

  .fc-card-title {
    font-family: var(--font-display);
    font-size: 20px; font-weight: 600; font-style: italic;
    color: var(--text-primary); margin: 0;
  }

  .fc-card-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

  /* Search */
  .fc-search-wrap { position: relative; }
  .fc-search-wrap i {
    position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
    color: var(--text-muted); font-size: 13px; pointer-events: none;
  }
  .fc-search {
    font-family: var(--font-body); font-size: 13px;
    background: var(--surface-2); border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 8px 12px 8px 32px;
    color: var(--text-primary); outline: none; width: 220px;
    transition: border-color .15s, box-shadow .15s;
  }
  .fc-search:focus {
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(200,75,49,.10);
    background: var(--surface);
  }

  /* Primary Button */
  .fc-btn-primary {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 18px;
    font-family: var(--font-body); font-size: 13px; font-weight: 600;
    background: var(--brand); color: #fff;
    border: none; border-radius: var(--radius-md);
    cursor: pointer;
    transition: background .15s, box-shadow .15s, transform .1s;
    box-shadow: 0 2px 8px rgba(200,75,49,.30);
    white-space: nowrap;
  }
  .fc-btn-primary:hover { background: var(--brand-dark); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(200,75,49,.38); }
  .fc-btn-primary:active { transform: translateY(0); }

  /* ── Table ── */
  .fc-table { width: 100%; border-collapse: collapse; font-family: var(--font-body); }
  .fc-table thead tr { background: var(--surface-2); border-bottom: 1px solid var(--border); }
  .fc-table th {
    padding: 11px 16px;
    font-size: 11px; font-weight: 700;
    letter-spacing: .07em; text-transform: uppercase;
    color: var(--text-muted); white-space: nowrap; text-align: left;
  }
  .fc-table th.center { text-align: center; }
  .fc-table th.right  { text-align: right; }

  .category-row td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
    font-size: 13.5px;
    color: var(--text-primary);
  }
  .category-row:last-child td { border-bottom: none; }
  .category-row { transition: background .12s; }
  .category-row:hover td { background: #FDF9F8; }

  /* Category identity cell */
  .fc-cat-cell { display: flex; align-items: center; gap: 13px; }
  .fc-cat-icon {
    width: 42px; height: 42px; border-radius: var(--radius-md);
    background: linear-gradient(135deg, #C84B31 0%, #F0A500 100%);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 16px; flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(200,75,49,.25);
  }
  .fc-cat-name { font-weight: 600; font-size: 14px; color: var(--text-primary); }
  .fc-cat-icon-url {
    font-size: 11.5px; color: var(--text-muted);
    font-family: 'Courier New', monospace;
    margin-top: 2px;
    max-width: 180px;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
  }

  /* Description */
  .fc-desc {
    font-size: 13px; color: var(--text-secondary);
    max-width: 220px;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
  }
  .fc-desc.empty { color: var(--text-muted); font-style: italic; }

  /* Property badge */
  .fc-prop-global {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 999px;
    font-size: 11.5px; font-weight: 600;
    background: var(--info-bg); color: var(--info);
  }
  .fc-prop-name { font-size: 13px; color: var(--text-secondary); }

  /* Sort order chip */
  .fc-sort-chip {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    background: var(--surface-2); border: 1px solid var(--border);
    font-size: 13px; font-weight: 700; color: var(--text-secondary);
  }

  /* Status badge */
  .fc-status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 11px; border-radius: 999px;
    font-size: 11.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .04em;
    white-space: nowrap;
  }
  .fc-status-badge::before {
    content: ''; display: block;
    width: 6px; height: 6px; border-radius: 50%;
    background: currentColor; opacity: .7;
  }
  .fc-status-badge.active   { background: var(--success-bg); color: var(--success); }
  .fc-status-badge.inactive { background: #F4F4F5; color: #71717A; }

  /* Action buttons */
  .fc-actions { display: flex; align-items: center; gap: 6px; justify-content: flex-end; }
  .fc-action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 34px; height: 34px;
    font-family: var(--font-body); font-size: 13px; font-weight: 600;
    border: 1px solid var(--border);
    background: var(--surface); color: var(--text-secondary);
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: all .15s;
  }
  .fc-action-btn:hover          { background: var(--surface-2); color: var(--text-primary); border-color: var(--border-strong); }
  .fc-action-btn.toggle-on      { color: var(--success); border-color: #B7E4CC; background: var(--success-bg); }
  .fc-action-btn.toggle-on:hover{ background: #D5F2E4; }
  .fc-action-btn.toggle-off:hover{ background: var(--success-bg); color: var(--success); border-color: #B7E4CC; }
  .fc-action-btn.edit:hover     { background: #EFF3FF; color: #3B5BDB; border-color: #C5D0FA; }
  .fc-action-btn.danger:hover   { background: var(--danger-bg); color: var(--danger); border-color: #F5BCBA; }

  /* Empty & loading */
  .fc-empty { text-align: center; padding: 64px 24px; }
  .fc-empty-icon {
    width: 72px; height: 72px; border-radius: 50%;
    background: var(--surface-2); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; color: var(--text-muted);
    margin: 0 auto 20px;
  }
  .fc-empty h4 {
    font-family: var(--font-display); font-size: 18px; font-weight: 600;
    color: var(--text-primary); margin: 0 0 6px; font-style: italic;
  }
  .fc-empty p { font-size: 13.5px; color: var(--text-secondary); margin: 0 0 20px; }
  .fc-loading-row td {
    padding: 24px 16px; text-align: center;
    color: var(--text-muted); font-family: var(--font-body); font-size: 13.5px;
  }

  /* ── Modals ── */
  .fc-modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(20,14,10,.50);
    backdrop-filter: blur(3px);
    z-index: 9000;
    align-items: center; justify-content: center;
    padding: 20px;
  }
  @keyframes fcOverlayIn { from { opacity:0; } to { opacity:1; } }
  @keyframes fcModalIn {
    from { opacity:0; transform: scale(.94) translateY(10px); }
    to   { opacity:1; transform: scale(1) translateY(0); }
  }

  .fc-modal {
    background: var(--surface);
    border-radius: var(--radius-xl);
    border: 1px solid var(--border);
    width: 100%; max-width: 560px;
    max-height: 90vh; overflow-y: auto;
    box-shadow: var(--shadow-modal);
    animation: fcModalIn .22s cubic-bezier(.34,1.35,.64,1);
  }

  .fc-modal-header {
    padding: 22px 26px 18px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; background: var(--surface); z-index: 1;
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
  }
  .fc-modal-title {
    font-family: var(--font-display);
    font-size: 19px; font-weight: 600; font-style: italic;
    color: var(--text-primary); margin: 0;
  }
  .fc-modal-close {
    width: 34px; height: 34px;
    display: flex; align-items: center; justify-content: center;
    background: var(--surface-2); border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text-secondary); font-size: 15px; cursor: pointer;
    transition: all .15s;
  }
  .fc-modal-close:hover { background: var(--danger-bg); color: var(--danger); border-color: #F5BCBA; }

  .fc-modal-body   { padding: 24px 26px; }
  .fc-modal-footer {
    padding: 16px 26px; border-top: 1px solid var(--border);
    display: flex; justify-content: flex-end; gap: 10px;
    position: sticky; bottom: 0; background: var(--surface);
    border-radius: 0 0 var(--radius-xl) var(--radius-xl);
  }

  /* ── Form ── */
  .fc-form-stack { display: flex; flex-direction: column; gap: 18px; }
  .fc-form-row   { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  @media (max-width: 480px) { .fc-form-row { grid-template-columns: 1fr; } }

  .fc-form-group { display: flex; flex-direction: column; gap: 6px; }

  .fc-label {
    font-size: 11px; font-weight: 700;
    letter-spacing: .07em; text-transform: uppercase;
    color: var(--text-secondary);
  }
  .fc-label .req { color: var(--brand); margin-left: 2px; }

  .fc-input, .fc-select, .fc-textarea {
    font-family: var(--font-body); font-size: 13.5px; color: var(--text-primary);
    background: var(--surface-2); border: 1px solid var(--border);
    border-radius: var(--radius-md); padding: 9px 13px;
    outline: none; width: 100%;
    transition: border-color .15s, box-shadow .15s, background .15s;
  }
  .fc-input:focus, .fc-select:focus, .fc-textarea:focus {
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(200,75,49,.10);
    background: var(--surface);
  }
  .fc-textarea { resize: vertical; min-height: 72px; }

  .fc-btn-cancel {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px;
    font-family: var(--font-body); font-size: 13px; font-weight: 600;
    border: 1px solid var(--border-strong); border-radius: var(--radius-md);
    background: var(--surface); color: var(--text-secondary);
    cursor: pointer; transition: all .15s;
  }
  .fc-btn-cancel:hover { background: var(--surface-2); color: var(--text-primary); }

  .fc-modal::-webkit-scrollbar { width: 5px; }
  .fc-modal::-webkit-scrollbar-track { background: transparent; }
  .fc-modal::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 4px; }

  .fc-card *, .fc-modal * { box-sizing: border-box; }

  /* ── Responsive ──────────────────────────────────── */
  @media (max-width: 640px) {
    .fc-stats-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .fc-stat-card {
      padding: 16px 18px;
    }
  }

  @media (max-width: 640px) {
    .fc-stat-icon {
      width: 36px;
      height: 36px;
      font-size: 16px;
    }
  }

  @media (max-width: 640px) {
    .fc-stat-label {
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .fc-stat-value {
      font-size: 24px;
    }
  }

  @media (max-width: 768px) {
    .fc-card-header {
      flex-direction: column;
      align-items: stretch;
      gap: 16px;
      padding: 16px 18px;
    }
  }

  @media (max-width: 768px) {
    .fc-card-actions {
      width: 100%;
      justify-content: space-between;
    }
  }

  @media (max-width: 480px) {
    .fc-card-actions {
      flex-direction: column;
      align-items: stretch;
    }
  }

  @media (max-width: 768px) {
    .fc-search {
      width: 100%;
      flex: 1;
    }
  }

  @media (max-width: 480px) {
    .fc-btn-primary {
      width: 100%;
      justify-content: center;
    }
  }

  @media (max-width: 640px) {
    .fc-table th {
      padding: 10px 12px;
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .category-row td {
      padding: 10px 12px;
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .fc-cat-icon {
      width: 36px;
      height: 36px;
      font-size: 14px;
    }
  }

  @media (max-width: 640px) {
    .fc-cat-name {
      font-size: 13px;
    }
  }

  @media (max-width: 640px) {
    .fc-cat-icon-url {
      font-size: 10px;
      max-width: 140px;
    }
  }

  @media (max-width: 640px) {
    .fc-desc {
      font-size: 12px;
      max-width: 150px;
    }
  }

  @media (max-width: 640px) {
    .fc-status-badge {
      font-size: 10px;
      padding: 3px 8px;
    }
  }

  @media (max-width: 640px) {
    .fc-action-btn {
      width: 32px;
      height: 32px;
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .fc-empty {
      padding: 48px 20px;
    }
  }

  @media (max-width: 640px) {
    .fc-empty-icon {
      width: 56px;
      height: 56px;
      font-size: 22px;
    }
  }

  @media (max-width: 640px) {
    .fc-empty h4 {
      font-size: 16px;
    }
  }

  @media (max-width: 640px) {
    .fc-empty p {
      font-size: 12px;
    }
  }

  @media (max-width: 768px) {
    .fc-modal {
      max-width: 90vw;
    }
  }

  @media (max-width: 480px) {
    .fc-modal {
      max-width: none;
      max-height: 95vh;
      border-radius: var(--radius-lg);
    }
  }

  @media (max-width: 768px) {
    .fc-modal-body {
      padding: 20px;
    }
  }

  @media (max-width: 480px) {
    .fc-modal-body {
      padding: 18px;
      overflow-y: auto;
      overflow-x: hidden;
    }
  }

  @media (max-width: 480px) {
    .fc-modal-footer {
      flex-direction: column;
    }
  }

  @media (max-width: 480px) {
    .fc-modal-footer .fc-btn-primary,
    .fc-modal-footer .fc-btn-cancel {
      width: 100%;
      justify-content: center;
    }
  }
</style>

<script>
  let foodCategoriesData = [];
  let propertiesData = [];

  async function fetchCategories() {
    try {
      const res  = await fetch('../api/food_categories.php?action=list');
      const json = await res.json();
      if (json.success) {
        foodCategoriesData = json.data;
        renderStats();
        renderTable();
      }
    } catch (e) { console.error('Error fetching categories:', e); }
  }

  async function fetchRelated() {
    try {
      const res  = await fetch('../api/food_categories.php?action=related');
      const json = await res.json();
      if (json.success) {
        propertiesData = json.data.properties;
        populateDropdowns();
      }
    } catch (e) { console.error('Error fetching related data:', e); }
  }

  function renderStats() {
    const active   = foodCategoriesData.filter(c => c.is_active == 1).length;
    const inactive = foodCategoriesData.filter(c => c.is_active == 0).length;
    const global   = foodCategoriesData.filter(c => c.property_id === null).length;
    const pairs = [
      ['statTotal',    foodCategoriesData.length],
      ['statActive',   active],
      ['statInactive', inactive],
      ['statGlobal',   global]
    ];
    pairs.forEach(([id, end]) => {
      const el = document.getElementById(id);
      if (!el) return;
      if (end === 0) { el.textContent = '0'; return; }
      let start = 0, step = Math.max(1, Math.ceil(end / 20));
      const timer = setInterval(() => {
        start = Math.min(start + step, end);
        el.textContent = start;
        if (start >= end) clearInterval(timer);
      }, 25);
    });
  }

  function renderTable() {
    const tbody = document.getElementById('categoryTableBody');
    if (!foodCategoriesData.length) {
      tbody.innerHTML = `
        <tr class="fc-loading-row">
          <td colspan="6">
            <div class="fc-empty">
              <div class="fc-empty-icon"><i class="fas fa-tags"></i></div>
              <h4>No categories yet</h4>
              <p>Create your first food category to start organizing your menu</p>
              <button onclick="openAddCategoryModal()" class="fc-btn-primary">
                <i class="fas fa-plus"></i> Create Category
              </button>
            </div>
          </td>
        </tr>`;
      return;
    }

    tbody.innerHTML = foodCategoriesData.map(cat => {
      const isActive = cat.is_active == 1;
      const toggleCls = isActive ? 'toggle-on' : 'toggle-off';
      const toggleTitle = isActive ? 'Deactivate' : 'Activate';
      const toggleIcon = isActive ? 'fa-toggle-on' : 'fa-toggle-off';

      return `
      <tr class="category-row">
        <td>
          <div class="fc-cat-cell">
            <div class="fc-cat-icon"><i class="fas fa-utensils"></i></div>
            <div>
              <div class="fc-cat-name">${cat.category_name}</div>
              ${cat.icon_url ? `<div class="fc-cat-icon-url" title="${cat.icon_url}">${cat.icon_url}</div>` : ''}
            </div>
          </div>
        </td>
        <td>
          ${cat.description
            ? `<span class="fc-desc">${cat.description}</span>`
            : `<span class="fc-desc empty">No description</span>`}
        </td>
        <td>
          ${cat.property_id
            ? `<span class="fc-prop-name">${cat.property_name}</span>`
            : `<span class="fc-prop-global"><i class="fas fa-globe" style="font-size:10px"></i> All Properties</span>`}
        </td>
        <td style="text-align:center">
          <span class="fc-sort-chip">${cat.sort_order}</span>
        </td>
        <td style="text-align:center">
          <span class="fc-status-badge ${isActive ? 'active' : 'inactive'}">${isActive ? 'Active' : 'Inactive'}</span>
        </td>
        <td>
          <div class="fc-actions">
            <button onclick="toggleCategoryStatus(this)" data-id="${cat.category_id}" data-status="${cat.is_active}"
                    class="fc-action-btn ${toggleCls}" title="${toggleTitle}">
              <i class="fas ${toggleIcon}"></i>
            </button>
            <button onclick="openEditCategoryModal('${cat.category_id}')" class="fc-action-btn edit" title="Edit Category">
              <i class="fas fa-pen"></i>
            </button>
            <button onclick="deleteCategory(this)" data-id="${cat.category_id}" data-name="${cat.category_name}"
                    class="fc-action-btn danger" title="Delete Category">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
        </td>
      </tr>`;
    }).join('');
  }

  function populateDropdowns() {
    const propOpts = '<option value="">All Properties (Global)</option>' +
      propertiesData.map(p => `<option value="${p.property_id}">${p.property_name}</option>`).join('');
    ['addProperty', 'editProperty'].forEach(id => {
      const sel = document.getElementById(id);
      if (sel) sel.innerHTML = propOpts;
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchCategories();
    fetchRelated();
  });
</script>

<!-- PAGE CONTENT -->

      <!-- Stats Cards -->
      <div class="fc-stats-grid">
        <div class="fc-stat-card">
          <div class="fc-stat-icon total"><i class="fas fa-tags"></i></div>
          <div>
            <div class="fc-stat-label">Total Categories</div>
            <div class="fc-stat-value" id="statTotal">—</div>
          </div>
        </div>
        <div class="fc-stat-card">
          <div class="fc-stat-icon active"><i class="fas fa-check-circle"></i></div>
          <div>
            <div class="fc-stat-label">Active</div>
            <div class="fc-stat-value" id="statActive">—</div>
          </div>
        </div>
        <div class="fc-stat-card">
          <div class="fc-stat-icon inactive"><i class="fas fa-ban"></i></div>
          <div>
            <div class="fc-stat-label">Inactive</div>
            <div class="fc-stat-value" id="statInactive">—</div>
          </div>
        </div>
        <div class="fc-stat-card">
          <div class="fc-stat-icon global"><i class="fas fa-globe"></i></div>
          <div>
            <div class="fc-stat-label">Global</div>
            <div class="fc-stat-value" id="statGlobal">—</div>
          </div>
        </div>
      </div>

      <!-- Food Categories Table -->
      <div class="fc-card">
        <div class="fc-card-header">
          <h2 class="fc-card-title">Food Categories</h2>
          <div class="fc-card-actions">
            <div class="fc-search-wrap">
              <i class="fas fa-search"></i>
              <input type="text" id="searchInput" class="fc-search" placeholder="Search categories…" oninput="filterTable()">
            </div>
            <button onclick="openAddCategoryModal()" class="fc-btn-primary">
              <i class="fas fa-plus"></i> New Category
            </button>
          </div>
        </div>
        <div style="overflow-x:auto">
          <table class="fc-table" id="categoryTable">
            <thead>
              <tr>
                <th>Category</th>
                <th>Description</th>
                <th>Property</th>
                <th class="center">Order</th>
                <th class="center">Status</th>
                <th class="right">Actions</th>
              </tr>
            </thead>
            <tbody id="categoryTableBody">
              <tr class="fc-loading-row">
                <td colspan="6">
                  <i class="fas fa-spinner fa-spin" style="margin-right:8px;color:var(--text-muted)"></i>
                  Loading categories…
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Add Category Modal -->
      <div id="addCategoryModal" class="fc-modal-overlay" style="display:none" onclick="if(event.target===this)closeAddCategoryModal()">
        <div class="fc-modal">
          <div class="fc-modal-header">
            <h3 class="fc-modal-title">Create New Category</h3>
            <button onclick="closeAddCategoryModal()" class="fc-modal-close"><i class="fas fa-times"></i></button>
          </div>
          <form id="addCategoryForm" onsubmit="submitAddCategory(event)">
            <div class="fc-modal-body">
              <div class="fc-form-stack">
                <div class="fc-form-group">
                  <label class="fc-label">Category Name <span class="req">*</span></label>
                  <input type="text" name="category_name" required class="fc-input" placeholder="e.g., Breakfast, Lunch, Snacks">
                </div>
                <div class="fc-form-group">
                  <label class="fc-label">Property</label>
                  <select name="property_id" id="addProperty" class="fc-select">
                    <option value="">All Properties (Global)</option>
                  </select>
                </div>
                <div class="fc-form-group">
                  <label class="fc-label">Description</label>
                  <textarea name="description" rows="2" class="fc-textarea" placeholder="Brief description of this category…"></textarea>
                </div>
                <div class="fc-form-group">
                  <label class="fc-label">Icon URL</label>
                  <input type="text" name="icon_url" class="fc-input" placeholder="https://example.com/icon.png">
                </div>
                <div class="fc-form-row">
                  <div class="fc-form-group">
                    <label class="fc-label">Sort Order</label>
                    <input type="number" name="sort_order" value="0" class="fc-input">
                  </div>
                  <div class="fc-form-group">
                    <label class="fc-label">Status</label>
                    <select name="is_active" class="fc-select">
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="fc-modal-footer">
              <button type="button" onclick="closeAddCategoryModal()" class="fc-btn-cancel">Cancel</button>
              <button type="submit" class="fc-btn-primary"><i class="fas fa-plus"></i> Create Category</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Category Modal -->
      <div id="editCategoryModal" class="fc-modal-overlay" style="display:none" onclick="if(event.target===this)closeEditCategoryModal()">
        <div class="fc-modal">
          <div class="fc-modal-header">
            <h3 class="fc-modal-title">Edit Category</h3>
            <button onclick="closeEditCategoryModal()" class="fc-modal-close"><i class="fas fa-times"></i></button>
          </div>
          <form id="editCategoryForm" onsubmit="submitEditCategory(event)">
            <div class="fc-modal-body">
              <div class="fc-form-stack">
                <input type="hidden" name="category_id" id="editCategoryId">
                <div class="fc-form-group">
                  <label class="fc-label">Category Name <span class="req">*</span></label>
                  <input type="text" name="category_name" required class="fc-input" placeholder="e.g., Breakfast, Lunch, Snacks">
                </div>
                <div class="fc-form-group">
                  <label class="fc-label">Property</label>
                  <select name="property_id" id="editProperty" class="fc-select">
                    <option value="">All Properties (Global)</option>
                  </select>
                </div>
                <div class="fc-form-group">
                  <label class="fc-label">Description</label>
                  <textarea name="description" rows="2" class="fc-textarea" placeholder="Brief description of this category…"></textarea>
                </div>
                <div class="fc-form-group">
                  <label class="fc-label">Icon URL</label>
                  <input type="text" name="icon_url" class="fc-input" placeholder="https://example.com/icon.png">
                </div>
                <div class="fc-form-row">
                  <div class="fc-form-group">
                    <label class="fc-label">Sort Order</label>
                    <input type="number" name="sort_order" value="0" class="fc-input">
                  </div>
                  <div class="fc-form-group">
                    <label class="fc-label">Status</label>
                    <select name="is_active" class="fc-select">
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="fc-modal-footer">
              <button type="button" onclick="closeEditCategoryModal()" class="fc-btn-cancel">Cancel</button>
              <button type="submit" class="fc-btn-primary"><i class="fas fa-save"></i> Update Category</button>
            </div>
          </form>
        </div>
      </div>

<script>
  // Filter table
  function filterTable() {
    const filter = document.getElementById('searchInput').value.toLowerCase();
    const rows   = document.getElementById('categoryTable').getElementsByTagName('tr');
    for (let i = 1; i < rows.length; i++) {
      const text = rows[i].textContent || rows[i].innerText;
      rows[i].style.display = text.toLowerCase().includes(filter) ? '' : 'none';
    }
  }

  // Add Category Modal
  function openAddCategoryModal() {
    document.getElementById('addCategoryModal').style.display = 'flex';
    document.getElementById('addCategoryForm').reset();
  }
  function closeAddCategoryModal() {
    document.getElementById('addCategoryModal').style.display = 'none';
  }

  function submitAddCategory(event) {
    event.preventDefault();
    const form = document.getElementById('addCategoryForm');
    const payload = {
      category_name: form.elements['category_name'].value,
      property_id:   form.elements['property_id'].value || null,
      description:   form.elements['description'].value,
      icon_url:      form.elements['icon_url'].value,
      sort_order:    form.elements['sort_order'].value,
      is_active:     form.elements['is_active'].value
    };

    Swal.fire({ title: 'Creating…', allowOutsideClick: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'swal2-popup-high-z' } });

    fetch('../api/food_categories.php?action=create', {
      method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
    })
      .then(r => r.json())
      .then(data => {
        closeAddCategoryModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchCategories());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeAddCategoryModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while creating the category.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  // Edit Category Modal
  async function openEditCategoryModal(categoryId) {
    try {
      const res  = await fetch(`../api/food_categories.php?action=single&id=${categoryId}`);
      const json = await res.json();
      if (!json.success || !json.data) return;
      const cat = json.data;

      document.getElementById('editCategoryId').value = cat.category_id;
      const form = document.getElementById('editCategoryForm');
      form.elements['category_name'].value = cat.category_name;
      form.elements['property_id'].value   = cat.property_id || '';
      form.elements['description'].value   = cat.description || '';
      form.elements['icon_url'].value      = cat.icon_url || '';
      form.elements['sort_order'].value    = cat.sort_order;
      form.elements['is_active'].value     = cat.is_active;

      document.getElementById('editCategoryModal').style.display = 'flex';
    } catch (e) { console.error('Error fetching category for edit:', e); }
  }

  function closeEditCategoryModal() {
    document.getElementById('editCategoryModal').style.display = 'none';
  }

  function submitEditCategory(event) {
    event.preventDefault();
    const form = document.getElementById('editCategoryForm');
    const payload = {
      category_id:   form.elements['category_id'].value,
      category_name: form.elements['category_name'].value,
      property_id:   form.elements['property_id'].value || null,
      description:   form.elements['description'].value,
      icon_url:      form.elements['icon_url'].value,
      sort_order:    form.elements['sort_order'].value,
      is_active:     form.elements['is_active'].value
    };

    Swal.fire({ title: 'Updating…', allowOutsideClick: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'swal2-popup-high-z' } });

    fetch('../api/food_categories.php?action=update', {
      method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
    })
      .then(r => r.json())
      .then(data => {
        closeEditCategoryModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchCategories());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeEditCategoryModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while updating the category.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  // Delete Category
  function deleteCategory(button) {
    const categoryId   = button.dataset.id;
    const categoryName = button.dataset.name;

    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to delete "${categoryName}". This action cannot be undone.`,
      icon: 'warning', showCancelButton: true,
      confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!', cancelButtonText: 'Cancel',
      customClass: { popup: 'swal2-popup-high-z' }
    }).then(result => {
      if (!result.isConfirmed) return;
      Swal.fire({ title: 'Deleting…', allowOutsideClick: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'swal2-popup-high-z' } });

      fetch('../api/food_categories.php?action=delete', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ category_id: categoryId })
      })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
              .then(() => fetchCategories());
          } else {
            Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
          }
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while deleting the category.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } }));
    });
  }

  // Toggle Category Status
  function toggleCategoryStatus(button) {
    const categoryId  = button.dataset.id;
    const newStatus   = parseInt(button.dataset.status) === 1 ? 0 : 1;
    const statusText  = newStatus === 1 ? 'activate' : 'deactivate';

    Swal.fire({
      title: 'Change Status?',
      text: `Are you sure you want to ${statusText} this category?`,
      icon: 'question', showCancelButton: true,
      confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, change it!', cancelButtonText: 'Cancel',
      customClass: { popup: 'swal2-popup-high-z' }
    }).then(result => {
      if (!result.isConfirmed) return;

      fetch('../api/food_categories.php?action=update', {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ category_id: categoryId, is_active: newStatus })
      })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            Swal.fire({ icon: 'success', title: 'Status Changed!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
              .then(() => fetchCategories());
          } else {
            Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
          }
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while updating status.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } }));
    });
  }

  // Duplicate Category (stub)
  function duplicateCategory(categoryId, categoryName) {
    Swal.fire({
      title: 'Duplicate Category?', text: `Create a copy of "${categoryName}"?`,
      icon: 'question', showCancelButton: true,
      confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, duplicate it!', cancelButtonText: 'Cancel'
    }).then(result => {
      if (result.isConfirmed) {
        Swal.fire({ icon: 'success', title: 'Duplicated!', text: 'Category has been duplicated.', confirmButtonColor: '#3085d6' })
          .then(() => location.reload());
      }
    });
  }

  // View Category Items (stub)
  function viewCategoryItems(categoryId, categoryName) {
    Swal.fire({ title: categoryName, text: 'View menu items in this category', icon: 'info', confirmButtonColor: '#3085d6' });
  }
</script>