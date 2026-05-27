<?php if (false) {} ?>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Lora:ital,wght@0,600;1,500&display=swap" rel="stylesheet">
<style>
  /* ── Design Tokens ── */
  :root {
    --brand:        #C84B31;
    --brand-dark:   #A03525;
    --brand-light:  #FDF0ED;
    --accent:       #F0A500;
    --accent-light: #FFF8E7;
    --surface:      #FFFFFF;
    --surface-2:    #FAFAFA;
    --border:       #EDEAE6;
    --border-strong:#D6D0C8;
    --text-primary: #1A1410;
    --text-secondary:#6B6460;
    --text-muted:   #A09890;
    --success:      #2D7D4F;
    --success-bg:   #EDFAF3;
    --warning:      #B45309;
    --warning-bg:   #FEF7EC;
    --danger:       #C0392B;
    --danger-bg:    #FEF0EE;
    --radius-sm:    6px;
    --radius-md:    10px;
    --radius-lg:    16px;
    --radius-xl:    20px;
    --shadow-card:  0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.06);
    --shadow-modal: 0 24px 64px rgba(0,0,0,.18);
    --font-display: 'Lora', Georgia, serif;
    --font-body:    'DM Sans', system-ui, sans-serif;
  }

  /* ── Stats Cards ── */
  .mi-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }
  @media (max-width: 1024px) { .mi-stats-grid { grid-template-columns: repeat(2,1fr); } }
  @media (max-width: 600px)  { .mi-stats-grid { grid-template-columns: 1fr 1fr; } }

  .mi-stat-card {
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
  .mi-stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(0,0,0,.10); }

  .mi-stat-icon {
    width: 48px; height: 48px;
    border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
  }
  .mi-stat-icon.total   { background: #EFF3FF; color: #3B5BDB; }
  .mi-stat-icon.avail   { background: var(--success-bg); color: var(--success); }
  .mi-stat-icon.featured{ background: var(--accent-light); color: var(--accent); }
  .mi-stat-icon.inactive{ background: #F4F4F5; color: #71717A; }

  .mi-stat-label {
    font-family: var(--font-body);
    font-size: 11.5px;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 4px;
  }
  .mi-stat-value {
    font-family: var(--font-display);
    font-size: 28px;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1;
  }

  /* ── Main Table Card ── */
  .mi-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-card);
    overflow: hidden;
    margin-bottom: 24px;
  }

  .mi-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px 18px;
    border-bottom: 1px solid var(--border);
    gap: 12px;
    flex-wrap: wrap;
  }

  .mi-card-title {
    font-family: var(--font-display);
    font-size: 20px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
    font-style: italic;
  }

  .mi-card-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

  /* ── Search Input ── */
  .mi-search-wrap {
    position: relative;
  }
  .mi-search-wrap i {
    position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
    color: var(--text-muted); font-size: 13px; pointer-events: none;
  }
  .mi-search {
    font-family: var(--font-body);
    font-size: 13px;
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 8px 12px 8px 32px;
    color: var(--text-primary);
    outline: none;
    width: 220px;
    transition: border-color .15s, box-shadow .15s;
  }
  .mi-search:focus {
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(200,75,49,.10);
    background: var(--surface);
  }

  /* ── Primary Button ── */
  .mi-btn-primary {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 18px;
    font-family: var(--font-body);
    font-size: 13px; font-weight: 600;
    background: var(--brand);
    color: #fff;
    border: none; border-radius: var(--radius-md);
    cursor: pointer;
    transition: background .15s, box-shadow .15s, transform .1s;
    box-shadow: 0 2px 8px rgba(200,75,49,.30);
    white-space: nowrap;
  }
  .mi-btn-primary:hover { background: var(--brand-dark); box-shadow: 0 4px 14px rgba(200,75,49,.38); transform: translateY(-1px); }
  .mi-btn-primary:active { transform: translateY(0); }

  /* ── Table ── */
  .mi-table { width: 100%; border-collapse: collapse; font-family: var(--font-body); }
  .mi-table thead tr {
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
  }
  .mi-table th {
    padding: 11px 16px;
    font-size: 11px; font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: var(--text-muted);
    white-space: nowrap;
    text-align: left;
  }
  .mi-table th.right { text-align: right; }
  .mi-table th.center { text-align: center; }

  .item-row td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
    font-size: 13.5px;
    color: var(--text-primary);
  }
  .item-row:last-child td { border-bottom: none; }
  .item-row { transition: background .12s; }
  .item-row:hover td { background: #FDF9F8; }

  /* Item thumbnail + name cell */
  .mi-item-cell { display: flex; align-items: center; gap: 13px; }
  .mi-thumb-wrap { position: relative; flex-shrink: 0; }
  .mi-thumb {
    width: 52px; height: 52px;
    border-radius: var(--radius-md);
    object-fit: cover;
    border: 1.5px solid var(--border);
    background: var(--surface-2);
    display: block;
    transition: transform .2s;
  }
  .item-row:hover .mi-thumb { transform: scale(1.06); }
  .mi-featured-dot {
    position: absolute; top: -4px; right: -4px;
    width: 16px; height: 16px;
    background: var(--accent);
    border-radius: 50%;
    border: 2px solid #fff;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 7px;
  }
  .mi-item-name {
    font-weight: 600;
    font-size: 14px;
    color: var(--text-primary);
    line-height: 1.3;
  }
  .mi-item-code {
    font-size: 11.5px;
    color: var(--text-muted);
    font-family: 'Courier New', monospace;
    letter-spacing: .04em;
    margin-top: 2px;
  }

  /* Badges */
  .mi-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 11.5px; font-weight: 600;
    white-space: nowrap;
  }
  .mi-badge.cat-meals     { background: #FFF8ED; color: #9A5B00; }
  .mi-badge.cat-drinks    { background: #EFF6FF; color: #1D5FA1; }
  .mi-badge.cat-snacks    { background: #F5F0FF; color: #6230B5; }
  .mi-badge.cat-desserts  { background: #FFF0F5; color: #AA2860; }
  .mi-badge.cat-beverages { background: #E6FAFA; color: #0E7C7C; }
  .mi-badge.cat-default   { background: #F4F4F5; color: #52525B; }

  .mi-badge.status-available  { background: var(--success-bg); color: var(--success); }
  .mi-badge.status-unavailable{ background: var(--warning-bg); color: var(--warning); }
  .mi-badge.status-inactive   { background: #F4F4F5; color: #71717A; }

  .mi-price {
    font-weight: 700; font-size: 14px;
    color: var(--text-primary);
    text-align: right;
  }
  .mi-property {
    font-size: 13px; color: var(--text-secondary);
  }
  .mi-property-all {
    font-size: 12px; font-weight: 600;
    color: var(--brand); background: var(--brand-light);
    padding: 2px 8px; border-radius: 999px;
    display: inline-block;
  }

  /* ── Action Buttons ── */
  .mi-actions { display: flex; align-items: center; gap: 6px; justify-content: flex-end; }
  .mi-action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 34px; height: 34px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    background: var(--surface);
    color: var(--text-secondary);
    cursor: pointer;
    font-size: 13px;
    transition: all .15s;
  }
  .mi-action-btn:hover          { background: var(--surface-2); color: var(--text-primary); border-color: var(--border-strong); }
  .mi-action-btn.toggle-on      { color: var(--success); border-color: #B7E4CC; background: var(--success-bg); }
  .mi-action-btn.toggle-on:hover{ background: #D5F2E4; }
  .mi-action-btn.toggle-off:hover{ background: var(--warning-bg); color: var(--warning); border-color: #F9D89A; }
  .mi-action-btn.edit:hover     { background: #EFF3FF; color: #3B5BDB; border-color: #C5D0FA; }
  .mi-action-btn.danger:hover   { background: var(--danger-bg); color: var(--danger); border-color: #F5BCBA; }

  /* ── Empty State ── */
  .mi-empty {
    text-align: center; padding: 64px 24px;
  }
  .mi-empty-icon {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: var(--surface-2);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; color: var(--text-muted);
    margin: 0 auto 20px;
  }
  .mi-empty h4 {
    font-family: var(--font-display);
    font-size: 18px; font-weight: 600; color: var(--text-primary);
    margin: 0 0 6px; font-style: italic;
  }
  .mi-empty p { font-size: 13.5px; color: var(--text-secondary); margin: 0 0 20px; }

  /* ── Modals ── */
  .mi-modal-overlay {
    display: none;
    position: fixed; inset: 0;
    background: rgba(20,14,10,.50);
    backdrop-filter: blur(3px);
    z-index: 9000;
    align-items: center; justify-content: center;
    padding: 20px;
    animation: miOverlayIn .18s ease;
  }
  .mi-modal-overlay.open { display: flex; }
  @keyframes miOverlayIn { from { opacity:0; } to { opacity:1; } }

  .mi-modal {
    background: var(--surface);
    border-radius: var(--radius-xl);
    border: 1px solid var(--border);
    width: 100%; max-width: 680px;
    max-height: 90vh; overflow-y: auto;
    box-shadow: var(--shadow-modal);
    animation: miModalIn .22s cubic-bezier(.34,1.35,.64,1);
  }
  @keyframes miModalIn {
    from { opacity:0; transform: scale(.94) translateY(10px); }
    to   { opacity:1; transform: scale(1) translateY(0); }
  }

  .mi-modal-header {
    padding: 22px 26px 18px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; background: var(--surface); z-index: 1;
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
  }
  .mi-modal-title {
    font-family: var(--font-display);
    font-size: 19px; font-weight: 600; font-style: italic;
    color: var(--text-primary); margin: 0;
  }
  .mi-modal-close {
    width: 34px; height: 34px;
    display: flex; align-items: center; justify-content: center;
    background: var(--surface-2); border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text-secondary); font-size: 15px; cursor: pointer;
    transition: all .15s;
  }
  .mi-modal-close:hover { background: var(--danger-bg); color: var(--danger); border-color: #F5BCBA; }

  .mi-modal-body  { padding: 24px 26px; }
  .mi-modal-footer{
    padding: 16px 26px;
    border-top: 1px solid var(--border);
    display: flex; justify-content: flex-end; gap: 10px;
    position: sticky; bottom: 0; background: var(--surface);
    border-radius: 0 0 var(--radius-xl) var(--radius-xl);
  }

  /* ── Form Styles ── */
  .mi-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
  }
  @media (max-width: 540px) { .mi-form-grid { grid-template-columns: 1fr; } }

  .mi-form-group { display: flex; flex-direction: column; gap: 6px; }
  .mi-form-group.full { grid-column: 1 / -1; }

  .mi-label {
    font-size: 11px; font-weight: 700;
    letter-spacing: .07em; text-transform: uppercase;
    color: var(--text-secondary);
  }
  .mi-label .req { color: var(--brand); margin-left: 2px; }

  .mi-input, .mi-select, .mi-textarea {
    font-family: var(--font-body);
    font-size: 13.5px; color: var(--text-primary);
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 9px 13px;
    outline: none; width: 100%;
    transition: border-color .15s, box-shadow .15s, background .15s;
  }
  .mi-input:focus, .mi-select:focus, .mi-textarea:focus {
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(200,75,49,.10);
    background: var(--surface);
  }
  .mi-textarea { resize: vertical; min-height: 80px; }

  /* Image upload area */
  .mi-img-upload-area {
    display: flex; align-items: center; gap: 16px;
    padding: 16px;
    border: 1.5px dashed var(--border-strong);
    border-radius: var(--radius-md);
    background: var(--surface-2);
    transition: border-color .15s, background .15s;
  }
  .mi-img-upload-area:hover { border-color: var(--brand); background: var(--brand-light); }

  .mi-img-preview {
    width: 72px; height: 72px;
    border-radius: var(--radius-md);
    object-fit: cover;
    border: 1.5px solid var(--border);
    background: var(--surface);
    flex-shrink: 0;
  }
  .mi-img-upload-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 14px;
    font-family: var(--font-body);
    font-size: 12.5px; font-weight: 600;
    border: 1px solid var(--border-strong);
    border-radius: var(--radius-sm);
    background: var(--surface);
    color: var(--text-primary);
    cursor: pointer;
    transition: all .15s;
  }
  .mi-img-upload-btn:hover { background: var(--brand); color: #fff; border-color: var(--brand); }
  .mi-img-hint { font-size: 11.5px; color: var(--text-muted); margin-top: 5px; }

  /* Toggle switch-style selects (Status/Available/Featured row) */
  .mi-toggle-row { display: flex; gap: 12px; }
  .mi-toggle-row > div { flex: 1; }

  /* Cancel button */
  .mi-btn-cancel {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px;
    font-family: var(--font-body); font-size: 13px; font-weight: 600;
    border: 1px solid var(--border-strong);
    border-radius: var(--radius-md);
    background: var(--surface);
    color: var(--text-secondary);
    cursor: pointer;
    transition: all .15s;
  }
  .mi-btn-cancel:hover { background: var(--surface-2); color: var(--text-primary); }

  /* Loading skeleton */
  .mi-loading-row td {
    padding: 24px 16px;
    text-align: center;
    color: var(--text-muted);
    font-family: var(--font-body);
    font-size: 13.5px;
  }

  /* Scrollbar polish */
  .mi-modal::-webkit-scrollbar { width: 5px; }
  .mi-modal::-webkit-scrollbar-track { background: transparent; }
  .mi-modal::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 4px; }

  /* Override Tailwind conflicts from parent theme */
  .mi-card *, .mi-modal * { box-sizing: border-box; }
</style>

<script>
  let menuItemsData = [];
  let categoriesData = [];
  let propertiesData = [];
  let csrfToken = '';

  async function fetchCsrfToken() {
    try {
      const res = await fetch('../api/users.php?action=csrf_token');
      const json = await res.json();
      if (json.success) csrfToken = json.csrf_token;
    } catch (e) {
      console.error('Error fetching CSRF token:', e);
    }
  }

  async function fetchMenuItems() {
    try {
      const res = await fetch('../api/menu_items.php?action=list');
      const json = await res.json();
      if (json.success) {
        menuItemsData = json.data;
        renderStats();
        renderTable();
      }
    } catch (e) {
      console.error('Error fetching menu items:', e);
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/menu_items.php?action=related');
      const json = await res.json();
      if (json.success) {
        categoriesData = json.data.categories;
        propertiesData = json.data.properties;
        populateDropdowns();
      }
    } catch (e) {
      console.error('Error fetching related data:', e);
    }
  }

  function renderStats() {
    const available = menuItemsData.filter(i => i.is_available == 1 && i.is_active == 1).length;
    const featured  = menuItemsData.filter(i => i.is_featured == 1).length;
    const inactive  = menuItemsData.filter(i => i.is_active == 0).length;
    const pairs = [['statTotal', menuItemsData.length], ['statAvailable', available], ['statFeatured', featured], ['statInactive', inactive]];
    pairs.forEach(([id, end]) => {
      const el = document.getElementById(id);
      if (!el) return;
      if (end === 0) { el.textContent = '0'; return; }
      let start = 0, step = Math.max(1, Math.ceil(end / 20));
      const timer = setInterval(() => { start = Math.min(start + step, end); el.textContent = start; if (start >= end) clearInterval(timer); }, 25);
    });
  }

  function renderTable() {
    const tbody = document.getElementById('itemTableBody');
    if (!menuItemsData.length) {
      tbody.innerHTML = `
        <tr class="mi-loading-row">
          <td colspan="6">
            <div class="mi-empty">
              <div class="mi-empty-icon"><i class="fas fa-utensils"></i></div>
              <h4>No menu items yet</h4>
              <p>Create your first item to start building your menu</p>
              <button onclick="openAddItemModal()" class="mi-btn-primary">
                <i class="fas fa-plus"></i> Add First Item
              </button>
            </div>
          </td>
        </tr>`;
      return;
    }

    tbody.innerHTML = menuItemsData.map(item => {
      // Status badge
      let statusCls, statusTxt;
      if (item.is_active == 0) {
        statusCls = 'status-inactive'; statusTxt = 'Inactive';
      } else if (item.is_available == 0) {
        statusCls = 'status-unavailable'; statusTxt = 'Unavailable';
      } else {
        statusCls = 'status-available'; statusTxt = 'Available';
      }

      // Category badge
      const catMap = { meals:'cat-meals', drinks:'cat-drinks', snacks:'cat-snacks', desserts:'cat-desserts', beverages:'cat-beverages' };
      const catCls = catMap[(item.category_name||'').toLowerCase()] || 'cat-default';

      // Image URL - convert absolute paths to relative for admin view
      let imgUrl = '../../uploads/foods/default-food.png';
      if (item.image_url) {
        if (item.image_url.startsWith('http')) {
          imgUrl = item.image_url;
        } else if (item.image_url.startsWith('/uploads/foods/')) {
          // Convert /uploads/foods/ to ../../uploads/foods/
          imgUrl = '../..' + item.image_url;
        } else if (item.image_url.startsWith('/')) {
          imgUrl = item.image_url;
        } else {
          imgUrl = '../../uploads/foods/' + item.image_url;
        }
      } else if (item.thumbnail_url) {
        if (item.thumbnail_url.startsWith('http')) {
          imgUrl = item.thumbnail_url;
        } else if (item.thumbnail_url.startsWith('/uploads/foods/')) {
          imgUrl = '../..' + item.thumbnail_url;
        } else if (item.thumbnail_url.startsWith('/')) {
          imgUrl = item.thumbnail_url;
        } else {
          imgUrl = '../../uploads/foods/' + item.thumbnail_url;
        }
      }

      const toggleIcon = item.is_available ? 'fa-toggle-on' : 'fa-toggle-off';
      const toggleCls  = item.is_available ? 'toggle-on' : 'toggle-off';

      return `
      <tr class="item-row">
        <td>
          <div class="mi-item-cell">
            <div class="mi-thumb-wrap">
              <img src="${imgUrl}" alt="${item.item_name}" class="mi-thumb"
                   onerror="if(!this.dataset.fb){this.dataset.fb='1';this.src='../../uploads/foods/default-food.png';}">
              ${item.is_featured == 1 ? `<span class="mi-featured-dot"><i class="fas fa-star"></i></span>` : ''}
            </div>
            <div>
              <div class="mi-item-name">${item.item_name}</div>
              ${item.item_code ? `<div class="mi-item-code">${item.item_code}</div>` : ''}
            </div>
          </div>
        </td>
        <td><span class="mi-badge ${catCls}">${item.category_name}</span></td>
        <td>
          ${item.property_id
            ? `<span class="mi-property">${item.property_name}</span>`
            : `<span class="mi-property-all">All Properties</span>`}
        </td>
        <td style="text-align:right"><span class="mi-price">₱${parseFloat(item.price).toFixed(2)}</span></td>
        <td style="text-align:center"><span class="mi-badge ${statusCls}">${statusTxt}</span></td>
        <td>
          <div class="mi-actions">
            <button onclick="toggleItemAvailable(this)" data-id="${item.item_id}" data-status="${item.is_available}"
                    class="mi-action-btn ${toggleCls}" title="${item.is_available ? 'Mark Unavailable' : 'Mark Available'}">
              <i class="fas ${toggleIcon}"></i>
            </button>
            <button onclick="openEditItemModal('${item.item_id}')" class="mi-action-btn edit" title="Edit Item">
              <i class="fas fa-pen"></i>
            </button>
            <button onclick="deleteItem(this)" data-id="${item.item_id}" data-name="${item.item_name}"
                    class="mi-action-btn danger" title="Delete Item">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
        </td>
      </tr>`;
    }).join('');
  }

  function populateDropdowns() {
    const catOpts = '<option value="">Select Category</option>' + categoriesData.map(c => `<option value="${c.category_id}">${c.category_name}</option>`).join('');
    const propOpts = '<option value="">All Properties (Global)</option>' + propertiesData.map(p => `<option value="${p.property_id}">${p.property_name}</option>`).join('');
    ['addCategory', 'editCategory'].forEach(id => { const sel = document.getElementById(id); if (sel) sel.innerHTML = catOpts; });
    ['addProperty', 'editProperty'].forEach(id => { const sel = document.getElementById(id); if (sel) sel.innerHTML = propOpts; });
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchCsrfToken();
    fetchMenuItems();
    fetchRelated();
  });
</script>

<!-- PAGE CONTENT -->

      <!-- Stats Cards -->
      <div class="mi-stats-grid">
        <div class="mi-stat-card">
          <div class="mi-stat-icon total"><i class="fas fa-list-ul"></i></div>
          <div>
            <div class="mi-stat-label">Total Items</div>
            <div class="mi-stat-value" id="statTotal">—</div>
          </div>
        </div>
        <div class="mi-stat-card">
          <div class="mi-stat-icon avail"><i class="fas fa-check-circle"></i></div>
          <div>
            <div class="mi-stat-label">Available</div>
            <div class="mi-stat-value" id="statAvailable">—</div>
          </div>
        </div>
        <div class="mi-stat-card">
          <div class="mi-stat-icon featured"><i class="fas fa-star"></i></div>
          <div>
            <div class="mi-stat-label">Featured</div>
            <div class="mi-stat-value" id="statFeatured">—</div>
          </div>
        </div>
        <div class="mi-stat-card">
          <div class="mi-stat-icon inactive"><i class="fas fa-ban"></i></div>
          <div>
            <div class="mi-stat-label">Inactive</div>
            <div class="mi-stat-value" id="statInactive">—</div>
          </div>
        </div>
      </div>

      <!-- Menu Items Table -->
      <div class="mi-card">
        <div class="mi-card-header">
          <h2 class="mi-card-title">Menu Items</h2>
          <div class="mi-card-actions">
            <div class="mi-search-wrap">
              <i class="fas fa-search"></i>
              <input type="text" id="searchInput" class="mi-search" placeholder="Search items…" oninput="filterTable()">
            </div>
            <button onclick="openAddItemModal()" class="mi-btn-primary">
              <i class="fas fa-plus"></i> New Item
            </button>
          </div>
        </div>
        <div style="overflow-x:auto;">
          <table class="mi-table" id="itemTable">
            <thead>
              <tr>
                <th>Item</th>
                <th>Category</th>
                <th>Property</th>
                <th class="right">Price</th>
                <th class="center">Status</th>
                <th class="right">Actions</th>
              </tr>
            </thead>
            <tbody id="itemTableBody">
              <tr class="mi-loading-row">
                <td colspan="6">
                  <i class="fas fa-spinner fa-spin" style="margin-right:8px;color:var(--text-muted)"></i>
                  Loading menu items…
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Add Item Modal -->
      <div id="addItemModal" class="mi-modal-overlay" style="display:none;" onclick="if(event.target===this)closeAddItemModal()">
        <div class="mi-modal">
          <div class="mi-modal-header">
            <h3 class="mi-modal-title">Create New Menu Item</h3>
            <button onclick="closeAddItemModal()" class="mi-modal-close"><i class="fas fa-times"></i></button>
          </div>
          <form id="addItemForm" onsubmit="submitAddItem(event)" enctype="multipart/form-data">
            <div class="mi-modal-body">
              <div class="mi-form-grid">
                <div class="mi-form-group">
                  <label class="mi-label">Item Name <span class="req">*</span></label>
                  <input type="text" name="item_name" required class="mi-input" placeholder="e.g., Grilled Chicken, Pasta">
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Item Code</label>
                  <input type="text" name="item_code" class="mi-input" placeholder="e.g., BRG-001">
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Category <span class="req">*</span></label>
                  <select name="category_id" id="addCategory" required class="mi-select">
                    <option value="">Select Category</option>
                  </select>
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Property</label>
                  <select name="property_id" id="addProperty" class="mi-select">
                    <option value="">All Properties (Global)</option>
                  </select>
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Price <span class="req">*</span></label>
                  <input type="number" step="0.01" name="price" required class="mi-input" placeholder="0.00">
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Cost Price</label>
                  <input type="number" step="0.01" name="cost_price" class="mi-input" placeholder="0.00">
                </div>
                <div class="mi-form-group full">
                  <label class="mi-label">Description</label>
                  <textarea name="description" rows="3" class="mi-textarea" placeholder="Brief description of the item…"></textarea>
                </div>
                <div class="mi-form-group full">
                  <label class="mi-label">Item Image</label>
                  <div class="mi-img-upload-area">
                    <img id="addImagePreview" src="../../uploads/foods/default-food.png" class="mi-img-preview">
                    <div>
                      <input type="file" name="image_file" id="addImageInput" accept="image/*" style="display:none" onchange="previewAddImage(this)">
                      <label for="addImageInput" class="mi-img-upload-btn">
                        <i class="fas fa-camera"></i> Choose Image
                      </label>
                      <div class="mi-img-hint">JPG, PNG, or WEBP · max 5 MB</div>
                    </div>
                  </div>
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Sort Order</label>
                  <input type="number" name="sort_order" value="0" class="mi-input">
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Status</label>
                  <select name="is_active" class="mi-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Available</label>
                  <select name="is_available" class="mi-select">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                  </select>
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Featured</label>
                  <select name="is_featured" class="mi-select">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="mi-modal-footer">
              <button type="button" onclick="closeAddItemModal()" class="mi-btn-cancel">Cancel</button>
              <button type="submit" class="mi-btn-primary"><i class="fas fa-plus"></i> Create Item</button>
            </div>
          </form>
        </div>
      </div>
      <!-- Edit Item Modal -->
      <div id="editItemModal" class="mi-modal-overlay" style="display:none;" onclick="if(event.target===this)closeEditItemModal()">
        <div class="mi-modal">
          <div class="mi-modal-header">
            <h3 class="mi-modal-title">Edit Menu Item</h3>
            <button onclick="closeEditItemModal()" class="mi-modal-close"><i class="fas fa-times"></i></button>
          </div>
          <form id="editItemForm" onsubmit="submitEditItem(event)" enctype="multipart/form-data">
            <div class="mi-modal-body">
              <div class="mi-form-grid">
                <input type="hidden" name="item_id" id="editItemId">
                <div class="mi-form-group">
                  <label class="mi-label">Item Name <span class="req">*</span></label>
                  <input type="text" name="item_name" required class="mi-input" placeholder="e.g., Grilled Chicken, Pasta">
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Item Code</label>
                  <input type="text" name="item_code" class="mi-input" placeholder="e.g., BRG-001">
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Category <span class="req">*</span></label>
                  <select name="category_id" id="editCategory" required class="mi-select">
                    <option value="">Select Category</option>
                  </select>
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Property</label>
                  <select name="property_id" id="editProperty" class="mi-select">
                    <option value="">All Properties (Global)</option>
                  </select>
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Price <span class="req">*</span></label>
                  <input type="number" step="0.01" name="price" required class="mi-input" placeholder="0.00">
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Cost Price</label>
                  <input type="number" step="0.01" name="cost_price" class="mi-input" placeholder="0.00">
                </div>
                <div class="mi-form-group full">
                  <label class="mi-label">Description</label>
                  <textarea name="description" rows="3" class="mi-textarea" placeholder="Brief description of the item…"></textarea>
                </div>
                <div class="mi-form-group full">
                  <label class="mi-label">Item Image</label>
                  <div class="mi-img-upload-area">
                    <img id="editImagePreview" src="../../uploads/foods/default-food.png" class="mi-img-preview">
                    <div>
                      <input type="file" name="image_file" id="editImageInput" accept="image/*" style="display:none" onchange="previewEditImage(this)">
                      <label for="editImageInput" class="mi-img-upload-btn">
                        <i class="fas fa-camera"></i> Change Image
                      </label>
                      <div class="mi-img-hint">JPG, PNG, or WEBP · max 5 MB</div>
                    </div>
                  </div>
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Sort Order</label>
                  <input type="number" name="sort_order" value="0" class="mi-input">
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Status</label>
                  <select name="is_active" class="mi-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Available</label>
                  <select name="is_available" class="mi-select">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                  </select>
                </div>
                <div class="mi-form-group">
                  <label class="mi-label">Featured</label>
                  <select name="is_featured" class="mi-select">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="mi-modal-footer">
              <button type="button" onclick="closeEditItemModal()" class="mi-btn-cancel">Cancel</button>
              <button type="submit" class="mi-btn-primary"><i class="fas fa-save"></i> Update Item</button>
            </div>
          </form>
        </div>
      </div>

<script>
  // Filter table function
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('itemTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
      const row = rows[i];
      const cells = row.getElementsByTagName('td');
      let found = false;

      for (let j = 0; j < cells.length; j++) {
        if (cells[j]) {
          const textValue = cells[j].textContent || cells[j].innerText;
          if (textValue.toLowerCase().indexOf(filter) > -1) {
            found = true;
            break;
          }
        }
      }

      row.style.display = found ? '' : 'none';
    }
  }

  // Add Item Modal
  function openAddItemModal() {
    const m = document.getElementById('addItemModal');
    m.style.display = 'flex';
    document.getElementById('addItemForm').reset();
    document.getElementById('addImagePreview').src = '../../uploads/foods/default-food.png';
  }

  function closeAddItemModal() {
    document.getElementById('addItemModal').style.display = 'none';
  }

  function submitAddItem(event) {
    event.preventDefault();
    const form = document.getElementById('addItemForm');
    const formData = new FormData();
    formData.append('item_name', form.elements['item_name'].value);
    formData.append('item_code', form.elements['item_code'].value);
    formData.append('category_id', form.elements['category_id'].value);
    formData.append('property_id', form.elements['property_id'].value || '');
    formData.append('price', form.elements['price'].value);
    formData.append('cost_price', form.elements['cost_price'].value);
    formData.append('description', form.elements['description'].value);
    formData.append('sort_order', form.elements['sort_order'].value);
    formData.append('is_active', form.elements['is_active'].value);
    formData.append('is_available', form.elements['is_available'].value);
    formData.append('is_featured', form.elements['is_featured'].value);
    formData.append('csrf_token', csrfToken);
    
    // Add image file if selected
    const imageFile = document.getElementById('addImageInput').files[0];
    if (imageFile) {
      formData.append('image_file', imageFile);
    }

    Swal.fire({
      title: 'Creating...',
      text: 'Please wait while we create the menu item.',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); },
      customClass: { popup: 'swal2-popup-high-z' }
    });

    fetch('../api/menu_items.php?action=create', {
        method: 'POST',
        body: formData
      })
      .then(r => r.json())
      .then(data => {
        closeAddItemModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchMenuItems());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeAddItemModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while creating the menu item.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  // Edit Item Modal
  async function openEditItemModal(itemId) {
    try {
      const res = await fetch(`../api/menu_items.php?action=single&id=${itemId}`);
      const json = await res.json();
      if (!json.success || !json.data) return;
      const item = json.data;

    document.getElementById('editItemId').value = item.item_id;
    const form = document.getElementById('editItemForm');
    form.elements['item_name'].value = item.item_name;
    form.elements['item_code'].value = item.item_code || '';
    form.elements['category_id'].value = item.category_id;
    form.elements['property_id'].value = item.property_id || '';
    form.elements['price'].value = item.price;
    form.elements['cost_price'].value = item.cost_price;
    form.elements['description'].value = item.description || '';
    form.elements['sort_order'].value = item.sort_order;
    form.elements['is_active'].value = item.is_active;
    form.elements['is_available'].value = item.is_available;
    form.elements['is_featured'].value = item.is_featured;

    // Load item image
    let imgUrl = '../../uploads/foods/default-food.png';
    if (item.image_url) {
      imgUrl = item.image_url.startsWith('http') ? item.image_url : (item.image_url.startsWith('/') ? item.image_url : '../../uploads/foods/' + item.image_url);
    } else if (item.thumbnail_url) {
      imgUrl = item.thumbnail_url.startsWith('http') ? item.thumbnail_url : (item.thumbnail_url.startsWith('/') ? item.thumbnail_url : '../../uploads/foods/' + item.thumbnail_url);
    }
    document.getElementById('editImagePreview').src = imgUrl;
    document.getElementById('editImageInput').value = '';

    document.getElementById('editItemModal').style.display = 'flex';
    } catch (e) {
      console.error('Error fetching item for edit:', e);
    }
  }

  function closeEditItemModal() {
    document.getElementById('editItemModal').style.display = 'none';
  }

  function submitEditItem(event) {
    event.preventDefault();
    const form = document.getElementById('editItemForm');
    const formData = new FormData();
    formData.append('item_id', form.elements['item_id'].value);
    formData.append('item_name', form.elements['item_name'].value);
    formData.append('item_code', form.elements['item_code'].value);
    formData.append('category_id', form.elements['category_id'].value);
    formData.append('property_id', form.elements['property_id'].value || '');
    formData.append('price', form.elements['price'].value);
    formData.append('cost_price', form.elements['cost_price'].value);
    formData.append('description', form.elements['description'].value);
    formData.append('sort_order', form.elements['sort_order'].value);
    formData.append('is_active', form.elements['is_active'].value);
    formData.append('is_available', form.elements['is_available'].value);
    formData.append('is_featured', form.elements['is_featured'].value);
    formData.append('csrf_token', csrfToken);
    
    // Add image file if selected
    const imageFile = document.getElementById('editImageInput').files[0];
    if (imageFile) {
      formData.append('image_file', imageFile);
    }

    Swal.fire({
      title: 'Updating...',
      text: 'Please wait while we update the menu item.',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); },
      customClass: { popup: 'swal2-popup-high-z' }
    });

    fetch('../api/menu_items.php?action=update', {
        method: 'POST',
        body: formData
      })
      .then(r => r.json())
      .then(data => {
        closeEditItemModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchMenuItems());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeEditItemModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while updating the menu item.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  // Delete Item
  function deleteItem(button) {
    const itemId = button.dataset.id;
    const itemName = button.dataset.name;

    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to delete "${itemName}". This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
      customClass: { popup: 'swal2-popup-high-z' }
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Deleting...',
          text: 'Please wait while we delete the menu item.',
          allowOutsideClick: false,
          didOpen: () => { Swal.showLoading(); },
          customClass: { popup: 'swal2-popup-high-z' }
        });

        fetch('../api/menu_items.php?action=delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ item_id: itemId, csrf_token: csrfToken })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
                .then(() => fetchMenuItems());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
            }
          })
          .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while deleting the menu item.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
          });
      }
    });
  }

  // Toggle Item Available
  function toggleItemAvailable(button) {
    const itemId = button.dataset.id;
    const currentStatus = parseInt(button.dataset.status);
    const newStatus = currentStatus === 1 ? 0 : 1;
    const statusText = newStatus === 1 ? 'mark as available' : 'mark as unavailable';

    Swal.fire({
      title: 'Change Availability?',
      text: `Are you sure you want to ${statusText} this item?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, change it!',
      cancelButtonText: 'Cancel',
      customClass: { popup: 'swal2-popup-high-z' }
    }).then((result) => {
      if (result.isConfirmed) {
        const payload = { item_id: itemId, is_available: newStatus, csrf_token: csrfToken };
        fetch('../api/menu_items.php?action=update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Availability Changed!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
                .then(() => fetchMenuItems());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
            }
          })
          .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while updating availability.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
          });
      }
    });
  }

  // Toggle Item Featured
  function toggleItemFeatured(button) {
    const itemId = button.dataset.id;
    const currentStatus = parseInt(button.dataset.status);
    const newStatus = currentStatus === 1 ? 0 : 1;
    const statusText = newStatus === 1 ? 'feature' : 'unfeature';

    Swal.fire({
      title: 'Change Featured Status?',
      text: `Are you sure you want to ${statusText} this item?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, change it!',
      cancelButtonText: 'Cancel',
      customClass: { popup: 'swal2-popup-high-z' }
    }).then((result) => {
      if (result.isConfirmed) {
        const payload = { item_id: itemId, is_featured: newStatus, csrf_token: csrfToken };
        fetch('../api/menu_items.php?action=update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Featured Status Changed!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
                .then(() => fetchMenuItems());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
            }
          })
          .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while updating featured status.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
          });
      }
    });
  }

  // Manage Item Images
  function manageItemImages(itemId, itemName) {
    Swal.fire({
      title: 'Manage Images',
      text: `Manage images for "${itemName}"`,
      icon: 'info',
      confirmButtonColor: '#3085d6'
    });
    // TODO: Implement modal to manage item images
  }

  // Duplicate Item
  function duplicateItem(itemId, itemName) {
    Swal.fire({
      title: 'Duplicate Item?',
      text: `Create a copy of "${itemName}"?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, duplicate it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        // TODO: Implement AJAX call to duplicate item
        Swal.fire({
          icon: 'success',
          title: 'Duplicated!',
          text: 'Menu item has been duplicated.',
          confirmButtonColor: '#3085d6'
        }).then(() => {
          location.reload();
        });
      }
    });
  }

  // View Item Order History
  function viewItemOrderHistory(itemId, itemName) {
    Swal.fire({
      title: 'Order History',
      text: `View order history for "${itemName}"`,
      icon: 'info',
      confirmButtonColor: '#3085d6'
    });
    // TODO: Implement modal to show order history
  }

  // Image Preview Functions
  function previewAddImage(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('addImagePreview').src = e.target.result;
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  function previewEditImage(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('editImagePreview').src = e.target.result;
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>