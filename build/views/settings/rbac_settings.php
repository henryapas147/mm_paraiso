<style>
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0
  }

  .page {
    padding: 24px;
    max-width: 100%;
    margin: 0 auto
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
    margin-bottom: 24px
  }

  .stat-card {
    background: #fff;
    border: 0.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 16px
  }

  .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0
  }

  .stat-icon.blue {
    background: linear-gradient(135deg, #185FA5, #534AB7);
    color: #fff
  }

  .stat-icon.green {
    background: linear-gradient(135deg, #3B6D11, #537D1E);
    color: #fff
  }

  .stat-content {
    flex: 1
  }

  .stat-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 4px
  }

  .stat-value {
    font-size: 28px;
    font-weight: 500;
    color: #333
  }

  .main-layout {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 16px
  }

  @media (max-width: 1024px) {
    .main-layout {
      grid-template-columns: 1fr
    }
  }

  .card {
    background: #fff;
    border: 0.5px solid #e5e7eb;
    border-radius: 12px
  }

  .card-header {
    padding: 16px 20px;
    border-bottom: 0.5px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap
  }

  .card-title {
    font-size: 16px;
    font-weight: 500
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
    border-bottom: 0.5px solid #e5e7eb;
    white-space: nowrap
  }

  thead th.center {
    text-align: center
  }

  tbody tr {
    border-bottom: 0.5px solid #e5e7eb;
    transition: background .1s;
    cursor: pointer
  }

  tbody tr:last-child {
    border-bottom: none
  }

  tbody tr:hover {
    background: #f9fafb
  }

  tbody tr.selected {
    background: rgba(24, 95, 165, 0.07)
  }

  td {
    padding: 11px 16px;
    vertical-align: middle;
    white-space: nowrap
  }

  .status-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500
  }

  .status-badge.active {
    background: #EAF3DE;
    color: #27500A
  }

  .status-badge.inactive {
    background: #F1EFE8;
    color: #5F5E5A
  }

  .status-badge.granted {
    background: #EAF3DE;
    color: #27500A
  }

  .status-badge.denied {
    background: #F1EFE8;
    color: #5F5E5A
  }

  .role-name {
    font-size: 13px;
    font-weight: 500;
    color: #333
  }

  .role-meta {
    font-size: 12px;
    color: #666
  }

  .placeholder {
    padding: 48px 24px;
    text-align: center;
    color: #666
  }

  .placeholder i {
    font-size: 32px;
    margin-bottom: 12px;
    display: block;
    color: #999
  }

  .panel-header {
    padding: 16px 20px;
    border-bottom: 0.5px solid #e5e7eb
  }

  .panel-title {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 4px
  }

  .panel-subtitle {
    font-size: 12px;
    color: #666
  }

  .toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 8px;
    border: 0.5px solid #d1d5db;
    background: #fff;
    color: #333;
    cursor: pointer;
    transition: background .15s
  }

  .btn:hover {
    background: #f9fafb
  }

  .btn-primary {
    background: #185FA5;
    color: #fff;
    border-color: #185FA5
  }

  .btn-primary:hover {
    background: #0C447C;
    border-color: #0C447C
  }

  .btn-success {
    background: #3B6D11;
    color: #fff;
    border-color: #3B6D11
  }

  .btn-success:hover {
    background: #2D530C;
    border-color: #2D530C
  }

  .btn i {
    font-size: 14px
  }

  .perm-manager {
    margin-top: 16px;
    padding: 16px;
    background: #f9fafb;
    border: 0.5px solid #e5e7eb;
    border-radius: 8px
  }

  .perm-manager-title {
    font-size: 12px;
    font-weight: 500;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 12px
  }

  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 6px
  }

  label {
    font-size: 12px;
    font-weight: 500;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.05em
  }

  label .req {
    color: #A32D2D;
    margin-left: 2px
  }

  input,
  select,
  textarea {
    font-family: system-ui, sans-serif;
    font-size: 13px;
    color: #333;
    background: #fff;
    border: 0.5px solid #d1d5db;
    border-radius: 8px;
    padding: 8px 12px;
    outline: none;
    width: 100%;
    transition: border .15s
  }

  input:focus,
  select:focus,
  textarea:focus {
    border-color: #185FA5;
    background: #fff
  }

  .search-wrap {
    margin-top: 16px;
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
    border: 0.5px solid #d1d5db;
    border-radius: 8px;
    background: #fff;
    color: #333;
    outline: none;
    width: 100%
  }

  .search-input:focus {
    border-color: #185FA5
  }

  .category-row {
    background: #f9fafb;
    cursor: pointer
  }

  .category-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 16px;
    gap: 8px
  }

  .category-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #666
  }

  .category-count {
    font-size: 11px;
    color: #999
  }

  .category-toggle {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #666;
    cursor: pointer
  }

  .category-toggle input {
    width: 16px;
    height: 16px;
    accent-color: #185FA5;
    cursor: pointer
  }

  .perm-row {
    transition: background .1s
  }

  .perm-row:hover {
    background: #f9fafb
  }

  .perm-checkbox {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    cursor: pointer
  }

  .perm-checkbox input {
    width: 16px;
    height: 16px;
    accent-color: #185FA5;
    flex-shrink: 0;
    cursor: pointer;
    margin-top: 2px
  }

  .perm-code {
    font-size: 13px;
    font-weight: 500;
    color: #333
  }

  .perm-label {
    font-size: 12px;
    color: #666
  }

  .perm-action {
    font-size: 12px;
    color: #666;
    text-transform: capitalize
  }

  .toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: #fff;
    border: 0.5px solid #d1d5db;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
    z-index: 9999;
    box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
    transform: translateY(80px);
    opacity: 0;
    transition: all .25s;
    min-width: 260px
  }

  .toast.show {
    transform: none;
    opacity: 1
  }

  .toast.success {
    border-color: #9FE1CB;
    color: #085041
  }

  .toast.error {
    border-color: #F7C1C1;
    color: #791F1F
  }

  .toast-close {
    background: none;
    border: none;
    cursor: pointer;
    color: #666;
    font-size: 16px;
    line-height: 1;
    padding: 4px;
    margin-left: auto
  }

  .toast-close:hover {
    color: #333
  }

  .skeleton-row td {
    padding: 12px 16px
  }

  .skeleton-line {
    height: 12px;
    background: #e5e7eb;
    border-radius: 4px;
    animation: pulse 1.5s ease-in-out infinite
  }

  .skeleton-line.short {
    width: 50%
  }

  @keyframes pulse {
    0%, 100% { opacity: 1 }
    50% { opacity: 0.5 }
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
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .stat-icon {
      width: 40px;
      height: 40px;
      font-size: 18px;
    }
  }

  @media (max-width: 640px) {
    .stat-label {
      font-size: 11px;
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
    .toolbar {
      flex-direction: column;
      align-items: stretch;
      gap: 8px;
    }
  }

  @media (max-width: 480px) {
    .btn {
      width: 100%;
      justify-content: center;
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
    .status-badge {
      font-size: 10px;
      padding: 2px 8px;
    }
  }

  @media (max-width: 640px) {
    .role-name {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .role-meta {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .placeholder {
      padding: 40px 20px;
    }
  }

  @media (max-width: 640px) {
    .placeholder i {
      font-size: 28px;
    }
  }

  @media (max-width: 640px) {
    .panel-header {
      padding: 14px 18px;
    }
  }

  @media (max-width: 640px) {
    .panel-title {
      font-size: 14px;
    }
  }

  @media (max-width: 640px) {
    .panel-subtitle {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .perm-manager {
      padding: 12px;
    }
  }

  @media (max-width: 640px) {
    .perm-manager-title {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .form-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    label {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    input,
    select,
    textarea {
      font-size: 12px;
      padding: 7px 10px;
    }
  }

  @media (max-width: 640px) {
    .search-input {
      font-size: 12px;
      padding: 7px 12px 7px 32px;
    }
  }

  @media (max-width: 640px) {
    .category-header {
      padding: 6px 12px;
    }
  }

  @media (max-width: 640px) {
    .category-label {
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .category-count {
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .category-toggle {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .category-toggle input {
      width: 14px;
      height: 14px;
    }
  }

  @media (max-width: 640px) {
    .category-row {
      cursor: pointer;
    }
  }

  @media (max-width: 640px) {
    .category-label i {
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .perm-row {
      cursor: default;
    }
  }

  @media (max-width: 640px) {
    .perm-checkbox {
      gap: 10px;
    }
  }

  @media (max-width: 640px) {
    .perm-checkbox input {
      width: 14px;
      height: 14px;
    }
  }

  @media (max-width: 640px) {
    .perm-code {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .perm-label {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .perm-action {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .toast {
      left: 16px;
      right: 16px;
      bottom: 16px;
      font-size: 12px;
      min-width: auto;
    }
  }
</style>

<!-- Toast -->
<div id="rbacToast" class="toast"></div>

<div class="page">
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon blue"><i class="fas fa-users"></i></div>
      <div class="stat-content">
        <div class="stat-label">Total Roles</div>
        <div class="stat-value" id="statTotal">—</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"><i class="fas fa-check"></i></div>
      <div class="stat-content">
        <div class="stat-label">Active Roles</div>
        <div class="stat-value" id="statActive">—</div>
      </div>
    </div>
  </div>

  <div class="main-layout">
    <!-- Left: Role List -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">User Roles</span>
      </div>
      <div class="table-wrap">
        <table>
          <colgroup>
            <col style="width:70%">
            <col style="width:30%">
          </colgroup>
          <thead>
            <tr>
              <th>Role</th>
              <th class="center">Status</th>
            </tr>
          </thead>
          <tbody id="rolesList">
            <?php for ($i = 0; $i < 6; $i++): ?>
            <tr class="skeleton-row">
              <td>
                <div class="skeleton-line"></div>
                <div class="skeleton-line short"></div>
              </td>
              <td class="center">
                <div class="skeleton-line short" style="width:55px;margin:0 auto"></div>
              </td>
            </tr>
            <?php endfor; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Right: Permissions -->
    <div class="card">
      <!-- Placeholder -->
      <div id="permissionPlaceholder" class="placeholder">
        <i class="fas fa-shield"></i>
        Select a role to manage permissions
      </div>

      <!-- Panel -->
      <div id="permissionPanel" style="display:none;">
        <div class="panel-header">
          <div>
            <div class="panel-title" id="panelRoleName"></div>
            <div class="panel-subtitle" id="panelPermCount"></div>
          </div>
          <div class="toolbar">
            <button onclick="togglePermissionManager()" class="btn btn-success"><i class="fas fa-key"></i> Manage Permissions</button>
            <button id="panelSaveBtn" class="btn btn-primary"><i class="fas fa-device-floppy"></i> Save Changes</button>
          </div>
        </div>

        <!-- Permission Manager Panel -->
        <div id="permissionManagerPanel" style="display:none;" class="perm-manager">
          <div class="perm-manager-title">Add New Permission</div>
          <div class="form-grid">
            <div class="form-group">
              <label>Permission Code <span class="req">*</span></label>
              <input type="text" id="newPermCode" placeholder="e.g., module.action">
            </div>
            <div class="form-group">
              <label>Label <span class="req">*</span></label>
              <input type="text" id="newPermLabel" placeholder="Human readable name">
            </div>
            <div class="form-group">
              <label>Category</label>
              <select id="newPermCategory">
                <option value="General">General</option>
                <option value="Dashboard">Dashboard</option>
                <option value="Users">Users</option>
                <option value="Properties">Properties</option>
                <option value="Reservations">Reservations</option>
                <option value="Customers">Customers</option>
                <option value="Inventory">Inventory</option>
                <option value="Food & Canteen">Food & Canteen</option>
                <option value="Payments">Payments</option>
                <option value="Reports">Reports</option>
                <option value="Settings">Settings</option>
              </select>
            </div>
            <div class="form-group">
              <label>Description</label>
              <input type="text" id="newPermDesc" placeholder="Optional description">
            </div>
          </div>
          <div style="margin-top:12px;display:flex;justify-content:flex-end">
            <button onclick="createPermission()" class="btn btn-success"><i class="fas fa-plus"></i> Create Permission</button>
          </div>
        </div>

        <!-- Search -->
        <div class="search-wrap">
          <i class="fas fa-search"></i>
          <input id="permSearch" type="text" placeholder="Search permissions..." oninput="applyPermFilter(this.value)" class="search-input">
        </div>

        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Permission</th>
                <th>Action</th>
                <th class="center">Access</th>
              </tr>
            </thead>
            <tbody id="permTableBody"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  let rolesData = [];
  let selectedRole = null;
  let permFilter = '';
  let permissionsData = {}; // Fetched from API: { category: [permissions] }
  let allPermissions = []; // Flat list of all permission codes
  let permissionCategories = [];
  let showPermissionManager = false;

  function getTotalPerms() {
    return allPermissions.length;
  }

  function showToast(msg, isError) {
    const t = document.getElementById('rbacToast');
    t.textContent = msg;
    t.className = 'toast ' + (isError ? 'error' : 'success') + ' show';
    setTimeout(() => { t.classList.remove('show'); }, 3500);
  }

  async function fetchPermissions() {
    try {
      const res = await fetch('../api/permissions.php?action=list');
      const json = await res.json();
      if (!json.success) throw new Error(json.message);
      permissionsData = json.data.permissions || {};
      permissionCategories = json.data.categories || [];
      // Build flat list
      allPermissions = [];
      Object.values(permissionsData).forEach(catPerms => {
        catPerms.forEach(p => allPermissions.push(p.permission_code));
      });
      return true;
    } catch (e) {
      showToast('Failed to load permissions: ' + e.message, true);
      return false;
    }
  }

  async function fetchRoles() {
    try {
      const res = await fetch('../api/rbac_settings.php');
      const json = await res.json();
      if (!json.success) throw new Error(json.message);
      rolesData = json.data;
      document.getElementById('statTotal').textContent = rolesData.length;
      document.getElementById('statActive').textContent = rolesData.filter(r => r.is_active).length;
      renderRolesList();
      if (rolesData.length) selectRole(rolesData[0].role_code);
    } catch (e) {
      showToast('Failed to load roles: ' + e.message, true);
    } finally {
      // skeleton rows are replaced by renderRolesList()
    }
  }

  function renderRolesList() {
    document.getElementById('rolesList').innerHTML = rolesData.map(role => {
      const count = role.permissions ? role.permissions.length : 0;
      return `
        <tr id="roleRow_${role.role_code}" onclick="selectRole('${role.role_code}')"
            class="role-row">
          <td>
            <div class="role-name">${role.role_label}</div>
            <div class="role-meta">${count} of ${getTotalPerms()} permissions</div>
          </td>
          <td class="center">
            <span class="status-badge ${role.is_active ? 'active' : 'inactive'}">${role.is_active ? 'Active' : 'Inactive'}</span>
          </td>
        </tr>`;
    }).join('');
  }

  function selectRole(roleCode) {
    selectedRole = roleCode;
    document.querySelectorAll('.role-row').forEach(r => {
      r.style.background = '';
    });
    const row = document.getElementById('roleRow_' + roleCode);
    if (row) row.style.background = 'rgba(94,114,228,0.07)';
    renderPermissionMatrix(roleCode);
  }

  function renderPermissionMatrix(roleCode) {
    const role = rolesData.find(r => r.role_code === roleCode);
    if (!role) return;
    const granted = role.permissions ? role.permissions.length : 0;
    const filter = (permFilter || '').toLowerCase().trim();

    let tableRows = '';
    let visibleCount = 0;

    // Use permissionsData from API
    for (const [cat, perms] of Object.entries(permissionsData)) {
      // Filter permissions within this category
      const filtered = filter
        ? perms.filter(p =>
            p.permission_code.toLowerCase().includes(filter) ||
            (p.permission_label && p.permission_label.toLowerCase().includes(filter)) ||
            cat.toLowerCase().includes(filter)
          )
        : perms;

      if (!filtered.length) continue;

      // Get all permission codes in this category for "All" checkbox check
      const allPermCodes = perms.map(p => p.permission_code);
      const allChecked = allPermCodes.every(p => role.permissions.includes(p));
      const catId = 'cat-' + cat.replace(/[^a-zA-Z0-9]/g, '-').toLowerCase().replace(/-+/g, '-').replace(/^-|-$/g, '');

      tableRows += `
        <tr class="category-row" onclick="toggleCategoryCollapse('${catId}')">
          <td colspan="3">
            <div class="category-header">
              <div class="category-label">
                <i id="icon-${catId}" class="fas fa-chevron-down"></i>
                ${cat}
                <span class="category-count">(${filtered.length})</span>
              </div>
              <label class="category-toggle" onclick="event.stopPropagation()">
                <input type="checkbox" class="cat-toggle" data-role="${roleCode}" data-cat="${cat}"
                  ${allChecked ? 'checked' : ''}
                  onchange="toggleCategory('${roleCode}','${cat}',this.checked)">
                All
              </label>
            </div>
          </td>
        </tr>`;

      filtered.forEach(perm => {
        const permCode = perm.permission_code;
        const action = permCode.split('.').slice(1).join('.');
        const isGranted = role.permissions.includes(permCode);
        visibleCount++;
        tableRows += `
        <tr class="perm-row perm-row-${catId}">
          <td>
            <label class="perm-checkbox">
              <input type="checkbox" class="perm-cb" data-role="${roleCode}" data-perm="${permCode}" data-cat="${cat}"
                ${isGranted ? 'checked' : ''} onchange="onPermChange('${roleCode}','${cat}',this)">
              <div>
                <div class="perm-code">${permCode}</div>
                ${perm.permission_label !== permCode ? `<div class="perm-label">${perm.permission_label}</div>` : ''}
              </div>
            </label>
          </td>
          <td>
            <span class="perm-action">${action.replace(/_/g, ' ')}</span>
          </td>
          <td class="center">
            <span class="status-badge ${isGranted ? 'granted' : 'denied'}">${isGranted ? 'Granted' : 'Denied'}</span>
          </td>
        </tr>`;
      });
    }

    if (visibleCount === 0 && filter) {
      tableRows = `<tr><td colspan="3" class="placeholder">
        No permissions match "${filter}"
      </td></tr>`;
    }

    document.getElementById('panelRoleName').textContent = role.role_label;
    document.getElementById('panelPermCount').textContent = `${granted} of ${getTotalPerms()} permissions granted`;
    document.getElementById('panelSaveBtn').setAttribute('onclick', `savePermissions('${roleCode}')`);
    document.getElementById('permTableBody').innerHTML = tableRows;
    document.getElementById('permissionPanel').style.display = 'block';
    document.getElementById('permissionPlaceholder').style.display = 'none';

    // Restore collapsed state from localStorage
    restoreCollapsedCategories();

    // Fix indeterminate
    document.querySelectorAll('.cat-toggle').forEach(cb => {
      const cat = cb.dataset.cat;
      const catPerms = permissionsData[cat];
      if (!catPerms) return;
      const permCodes = catPerms.map(p => p.permission_code);
      const all = permCodes.every(p => role.permissions.includes(p));
      const some = permCodes.some(p => role.permissions.includes(p));
      cb.indeterminate = some && !all;
    });
  }

  function applyPermFilter(v) {
    permFilter = v;
    if (selectedRole) renderPermissionMatrix(selectedRole);
  }

  function updateBadge(cb) {
    if (!cb) return;
    const badge = cb.closest('tr')?.querySelector('.status-badge');
    if (!badge) return;
    badge.className = `status-badge ${cb.checked ? 'granted' : 'denied'}`;
    badge.textContent = cb.checked ? 'Granted' : 'Denied';
  }

  function updateCounter(roleCode) {
    const checked = document.querySelectorAll(`.perm-cb[data-role="${roleCode}"]:checked`).length;
    document.getElementById('panelPermCount').textContent = `${checked} of ${getTotalPerms()} permissions granted`;
  }

  function onPermChange(roleCode, cat, cb) {
    const boxes = document.querySelectorAll(`.perm-cb[data-role="${roleCode}"][data-cat="${cat}"]`);
    const toggle = document.querySelector(`.cat-toggle[data-role="${roleCode}"][data-cat="${cat}"]`);
    const n = Array.from(boxes).filter(c => c.checked).length;
    if (toggle) { toggle.checked = n === boxes.length; toggle.indeterminate = n > 0 && n < boxes.length; }
    updateBadge(cb);
    updateCounter(roleCode);
  }

  function toggleCategory(roleCode, cat, checked) {
    document.querySelectorAll(`.perm-cb[data-role="${roleCode}"][data-cat="${cat}"]`)
      .forEach(cb => { cb.checked = checked; updateBadge(cb); });
    updateCounter(roleCode);
  }

  async function savePermissions(roleCode) {
    const permissions = Array.from(document.querySelectorAll(`.perm-cb[data-role="${roleCode}"]:checked`))
      .map(cb => cb.dataset.perm);
    const btn = document.getElementById('panelSaveBtn');
    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';
    btn.disabled = true;
    try {
      const res = await fetch('../api/rbac_settings.php', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': '<?php echo htmlspecialchars($csrf_token ?? ''); ?>'
        },
        body: JSON.stringify({ role_code: roleCode, permissions })
      });
      const json = await res.json();
      if (json.success) {
        showToast('Permissions saved for ' + (rolesData.find(r => r.role_code === roleCode)?.role_label ?? roleCode));
        await fetchRoles();
        selectRole(roleCode);
      } else {
        showToast('Error: ' + json.message, true);
      }
    } catch (e) {
      showToast('Network error. Try again.', true);
    } finally {
      btn.innerHTML = orig;
      btn.disabled = false;
    }
  }

  // Permission CRUD functions
  async function createPermission() {
    const code = document.getElementById('newPermCode')?.value.trim();
    const label = document.getElementById('newPermLabel')?.value.trim();
    const description = document.getElementById('newPermDesc')?.value.trim();
    const category = document.getElementById('newPermCategory')?.value || 'General';

    if (!code || !label) {
      showToast('Permission code and label are required', true);
      return;
    }

    try {
      const res = await fetch('../api/permissions.php?action=create', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': '<?php echo htmlspecialchars($csrf_token ?? ''); ?>'
        },
        body: JSON.stringify({ permission_code: code, permission_label: label, description, category })
      });
      const json = await res.json();
      if (json.success) {
        showToast('Permission created successfully');
        await fetchPermissions();
        if (selectedRole) renderPermissionMatrix(selectedRole);
        document.getElementById('newPermCode').value = '';
        document.getElementById('newPermLabel').value = '';
        document.getElementById('newPermDesc').value = '';
      } else {
        showToast('Error: ' + json.message, true);
      }
    } catch (e) {
      showToast('Failed to create permission', true);
    }
  }

  async function deletePermission(permId) {
    if (!confirm('Are you sure you want to delete this permission?')) return;

    try {
      const res = await fetch('../api/permissions.php?action=delete', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': '<?php echo htmlspecialchars($csrf_token ?? ''); ?>'
        },
        body: JSON.stringify({ permission_id: permId })
      });
      const json = await res.json();
      if (json.success) {
        showToast('Permission deleted');
        await fetchPermissions();
        if (selectedRole) renderPermissionMatrix(selectedRole);
      } else {
        showToast('Error: ' + json.message, true);
      }
    } catch (e) {
      showToast('Failed to delete permission', true);
    }
  }

  function togglePermissionManager() {
    showPermissionManager = !showPermissionManager;
    const panel = document.getElementById('permissionManagerPanel');
    if (panel) panel.style.display = showPermissionManager ? 'block' : 'none';
  }

  function toggleCategoryCollapse(catId, forceState = null) {
    const rows = document.querySelectorAll(`.perm-row-${catId}`);
    const icon = document.getElementById(`icon-${catId}`);
    const isExpanded = forceState !== null ? forceState : icon?.classList.contains('ti-chevron-down');

    rows.forEach(row => {
      row.style.display = isExpanded ? 'none' : '';
    });

    if (icon) {
      icon.classList.toggle('ti-chevron-down', !isExpanded);
      icon.classList.toggle('ti-chevron-right', isExpanded);
    }

    // Save to localStorage
    const collapsed = JSON.parse(localStorage.getItem('rbacCollapsedCats') || '[]');
    if (isExpanded) {
      if (!collapsed.includes(catId)) collapsed.push(catId);
    } else {
      const idx = collapsed.indexOf(catId);
      if (idx > -1) collapsed.splice(idx, 1);
    }
    localStorage.setItem('rbacCollapsedCats', JSON.stringify(collapsed));
  }

  function restoreCollapsedCategories() {
    const collapsed = JSON.parse(localStorage.getItem('rbacCollapsedCats') || '[]');
    collapsed.forEach(catId => {
      toggleCategoryCollapse(catId, true); // Force collapse
    });
  }

  // Initialize - fetch permissions first, then roles
  async function init() {
    const success = await fetchPermissions();
    if (success) {
      await fetchRoles();
    }
  }

  document.addEventListener('DOMContentLoaded', init);
</script>