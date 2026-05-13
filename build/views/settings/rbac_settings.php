<?php if (false) {} ?>

<!-- Toast -->
<div id="rbacToast" class="flex items-center bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl px-5 py-3"
  style="display:none; position:fixed; bottom:1.5rem; right:1.5rem; z-index:9999; min-width:260px;">
  <i id="rbacToastIcon" class="fas fa-check-circle mr-2 text-emerald-500"></i>
  <span id="rbacToastMsg" class="text-sm font-semibold dark:text-white flex-1"></span>
  <button onclick="document.getElementById('rbacToast').style.display='none'"
    class="ml-3 text-slate-400 hover:text-slate-600 dark:hover:text-white bg-transparent border-0 cursor-pointer p-0">
    <i class="fas fa-times text-xs"></i>
  </button>
</div>

<!-- PAGE CONTENT -->

<!-- Stats Row -->
<div class="flex flex-wrap -mx-3 mb-6">
  <div class="w-full max-w-full px-3 mb-4 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/2">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-2/3 max-w-full px-3">
            <p class="mb-0 font-sans text-xs font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Total Roles</p>
            <h5 class="mb-0 font-bold dark:text-white" id="statTotal">...</h5>
          </div>
          <div class="px-3 text-right basis-1/3">
            <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-blue-500 to-violet-500">
              <i class="ni leading-none ni-single-02 text-lg relative top-3.5 text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="w-full max-w-full px-3 mb-4 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/2">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-2/3 max-w-full px-3">
            <p class="mb-0 font-sans text-xs font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Active Roles</p>
            <h5 class="mb-0 font-bold dark:text-white" id="statActive">...</h5>
          </div>
          <div class="px-3 text-right basis-1/3">
            <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-emerald-500 to-teal-400">
              <i class="ni leading-none ni-check-bold text-lg relative top-3.5 text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Main Panel -->
<div class="flex flex-wrap -mx-3">

  <!-- Left: Role List -->
  <div class="flex-none w-full max-w-full px-3 lg:w-5/12 mb-6 lg:mb-0">
    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
        <h6 class="dark:text-white">User Roles</h6>
      </div>
      <div class="flex-auto px-0 pt-0 pb-2">
        <div class="p-0 overflow-x-auto">
          <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" style="table-layout:fixed;">
            <colgroup>
              <col style="width:70%;">
              <col style="width:30%;">
            </colgroup>
            <thead class="align-bottom">
              <tr>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Role</th>
                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
              </tr>
            </thead>
            <tbody id="rolesList">
              <?php for ($i = 0; $i < 6; $i++): ?>
              <tr class="skeleton-row">
                <td class="p-2 px-6 align-middle bg-transparent border-b dark:border-white/40">
                  <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded animate-pulse mb-1" style="width:80%"></div>
                  <div class="h-2 bg-slate-100 dark:bg-slate-700 rounded animate-pulse" style="width:50%"></div>
                </td>
                <td class="p-2 px-3 align-middle text-center bg-transparent border-b dark:border-white/40">
                  <div class="h-5 bg-slate-200 dark:bg-slate-700 rounded animate-pulse mx-auto" style="width:55px"></div>
                </td>
              </tr>
              <?php endfor; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Right: Permissions -->
  <div class="flex-none w-full max-w-full px-3 lg:w-7/12">
    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">

      <!-- Placeholder -->
      <div id="permissionPlaceholder" class="p-12 text-center">
        <i class="fas fa-shield-alt text-4xl text-slate-200 dark:text-white/10 mb-3 block"></i>
        <p class="mb-0 text-sm text-slate-400 dark:text-white/40">Select a role to manage permissions</p>
      </div>

      <!-- Panel -->
      <div id="permissionPanel" style="display:none;">
        <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
          <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
              <h6 class="dark:text-white mb-0" id="panelRoleName"></h6>
              <p class="mb-0 text-xs text-slate-400 dark:text-white/60" id="panelPermCount"></p>
            </div>
            <button id="panelSaveBtn" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wide shadow-md hover:shadow-xs transition-all border-0 cursor-pointer">
              <i class="fas fa-save mr-1"></i> Save Changes
            </button>
          </div>

          <!-- Search -->
          <div class="mt-4 relative">
            <i class="fas fa-search absolute text-xs text-slate-400 dark:text-white/40 pointer-events-none" style="left:12px; top:50%; transform:translateY(-50%);"></i>
            <input id="permSearch" type="text" placeholder="Search permissions..."
              oninput="applyPermFilter(this.value)"
              class="w-full text-xs border border-slate-200 dark:border-white/10 dark:bg-slate-700/50 dark:text-white rounded-lg transition-all"
              style="padding:8px 12px 8px 32px;">
          </div>
        </div>
        <div class="flex-auto px-0 pt-0 pb-2">
          <div class="p-0 overflow-x-auto">
            <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
              <thead class="align-bottom">
                <tr>
                  <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Permission</th>
                  <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Action</th>
                  <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Access</th>
                </tr>
              </thead>
              <tbody id="permTableBody"></tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>

<script>
  let rolesData = [];
  let selectedRole = null;
  let permFilter = '';

  const PERMISSIONS = {
    'Dashboard':      ['dashboard.view', 'dashboard.manage'],
    'Users':          ['users.view', 'users.create', 'users.edit', 'users.delete', 'users.roles.manage'],
    'Properties':     ['properties.view', 'properties.create', 'properties.edit', 'properties.delete',
                       'properties.properties.view', 'properties.rooms.view', 'properties.room_categories.view',
                       'properties.amenities.view', 'properties.room_blocks.view'],
    'Rooms':          ['rooms.view', 'rooms.create', 'rooms.edit', 'rooms.delete'],
    'Reservations':   ['reservations.view', 'reservations.create', 'reservations.edit', 'reservations.delete',
                       'reservations.check_in', 'reservations.check_out',
                       'reservations.reservations.view', 'reservations.rooms.view', 'reservations.guests.view',
                       'reservations.requirement_types.view', 'reservations.requirements.view',
                       'reservations.check_in_logs.view', 'reservations.entrance_logs.view'],
    'Customers':      ['customers.view', 'customers.create', 'customers.edit', 'customers.delete'],
    'Inventory':      ['inventory.view', 'inventory.create', 'inventory.edit', 'inventory.delete',
                       'inventory.products.view', 'inventory.categories.view', 'inventory.units.view',
                       'inventory.locations.view', 'inventory.suppliers.view', 'inventory.stock_location.view',
                       'inventory.purchase_orders.view', 'inventory.stock_transactions.view',
                       'inventory.stock_transfers.view', 'inventory.sales.view'],
    'Food & Canteen': ['food_canteen.view', 'food_canteen.create', 'food_canteen.edit', 'food_canteen.delete',
                       'food_canteen.categories.view', 'food_canteen.menu_items.view', 'food_canteen.orders.view'],
    'Payments':       ['payments.view', 'payments.create', 'payments.edit', 'payments.delete',
                       'payments.payment_methods.view', 'payments.payments.view', 'payments.invoices.view',
                       'payments.invoice_line_items.view', 'payments.discounts.view',
                       'payments.discount_usages.view', 'payments.extra_charges.view', 'payments.billing.view'],
    'Reports':        ['reports.view',
                       'reports.dashboard.view', 'reports.revenue.view', 'reports.occupancy.view',
                       'reports.inventory.view', 'reports.customers.view', 'reports.food_canteen.view',
                       'reports.financial.view'],
    'Settings':       ['settings.view', 'settings.edit', 'settings.manage',
                       'settings.system.view', 'settings.pricing_rules.view', 'settings.seasonal_rates.view',
                       'settings.booking_policies.view', 'settings.promotions.view', 'settings.promotion_images.view',
                       'settings.notifications.view', 'settings.notification_recipients.view', 'settings.rbac.view']
  };
  const TOTAL_PERMS = Object.values(PERMISSIONS).reduce((a, c) => a + c.length, 0);

  function showToast(msg, isError) {
    const t = document.getElementById('rbacToast');
    document.getElementById('rbacToastMsg').textContent = msg;
    document.getElementById('rbacToastIcon').className = isError
      ? 'fas fa-times-circle mr-2 text-red-500'
      : 'fas fa-check-circle mr-2 text-emerald-500';
    t.style.display = 'flex';
    setTimeout(() => { t.style.display = 'none'; }, 3500);
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
            class="role-row cursor-pointer hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
          <td class="p-2 px-6 align-middle bg-transparent border-b dark:border-white/40">
            <h6 class="mb-0 text-xs leading-normal dark:text-white">${role.role_label}</h6>
            <p class="mb-0 text-xs leading-tight text-slate-400 dark:text-white/60">${count} of ${TOTAL_PERMS} permissions</p>
          </td>
          <td class="p-2 px-3 align-middle text-center bg-transparent border-b dark:border-white/40">
            <span class="bg-gradient-to-tl ${role.is_active ? 'from-emerald-500 to-teal-400' : 'from-slate-600 to-slate-400'} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
              ${role.is_active ? 'Active' : 'Inactive'}
            </span>
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
    for (const [cat, perms] of Object.entries(PERMISSIONS)) {
      const filtered = filter
        ? perms.filter(p => p.toLowerCase().includes(filter) || cat.toLowerCase().includes(filter))
        : perms;
      if (!filtered.length) continue;

      const allChecked = perms.every(p => role.permissions.includes(p));
      tableRows += `
        <tr class="bg-slate-50 dark:bg-slate-700/30">
          <td colspan="3" class="px-6 py-2 border-b dark:border-white/40">
            <div class="flex items-center justify-between">
              <span class="text-xxs font-bold uppercase tracking-none text-slate-400 dark:text-white opacity-70">${cat}</span>
              <label class="flex items-center gap-1.5 cursor-pointer mb-0">
                <input type="checkbox" class="cat-toggle" data-role="${roleCode}" data-cat="${cat}"
                  ${allChecked ? 'checked' : ''}
                  onchange="toggleCategory('${roleCode}','${cat}',this.checked)"
                  style="accent-color:#5e72e4; width:13px; height:13px;">
                <span class="text-xxs text-slate-400 dark:text-white/60">All</span>
              </label>
            </div>
          </td>
        </tr>`;

      filtered.forEach(perm => {
        const action = perm.split('.').slice(1).join('.');
        const isGranted = role.permissions.includes(perm);
        visibleCount++;
        tableRows += `
        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
          <td class="p-2 px-6 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <label class="flex items-center cursor-pointer mb-0" style="gap:12px;">
              <input type="checkbox" class="perm-cb" data-role="${roleCode}" data-perm="${perm}" data-cat="${cat}"
                ${isGranted ? 'checked' : ''} onchange="onPermChange('${roleCode}','${cat}',this)"
                style="accent-color:#5e72e4; width:16px; height:16px; flex-shrink:0; cursor:pointer;">
              <span class="text-xs font-semibold leading-tight dark:text-white">${perm}</span>
            </label>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <span class="text-xs leading-tight text-slate-400 dark:text-white/60 uppercase">${action.replace(/_/g, ' ')}</span>
          </td>
          <td class="p-2 align-middle text-center bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <span class="status-badge bg-gradient-to-tl ${isGranted ? 'from-emerald-500 to-teal-400' : 'from-slate-600 to-slate-400'} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
              ${isGranted ? 'Granted' : 'Denied'}
            </span>
          </td>
        </tr>`;
      });
    }

    if (visibleCount === 0 && filter) {
      tableRows = `<tr><td colspan="3" class="p-6 text-center">
        <p class="mb-0 text-xs text-slate-400 dark:text-white/40">No permissions match "${filter}"</p>
      </td></tr>`;
    }

    document.getElementById('panelRoleName').textContent = role.role_label;
    document.getElementById('panelPermCount').textContent = `${granted} of ${TOTAL_PERMS} permissions granted`;
    document.getElementById('panelSaveBtn').setAttribute('onclick', `savePermissions('${roleCode}')`);
    document.getElementById('permTableBody').innerHTML = tableRows;
    document.getElementById('permissionPanel').style.display = 'block';
    document.getElementById('permissionPlaceholder').style.display = 'none';

    // Fix indeterminate
    document.querySelectorAll('.cat-toggle').forEach(cb => {
      const perms = PERMISSIONS[cb.dataset.cat];
      if (!perms) return;
      const all = perms.every(p => role.permissions.includes(p));
      const some = perms.some(p => role.permissions.includes(p));
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
    badge.className = `status-badge bg-gradient-to-tl ${cb.checked ? 'from-emerald-500 to-teal-400' : 'from-slate-600 to-slate-400'} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white`;
    badge.textContent = cb.checked ? 'Granted' : 'Denied';
  }

  function updateCounter(roleCode) {
    const checked = document.querySelectorAll(`.perm-cb[data-role="${roleCode}"]:checked`).length;
    document.getElementById('panelPermCount').textContent = `${checked} of ${TOTAL_PERMS} permissions granted`;
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
        headers: { 'Content-Type': 'application/json' },
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

  document.addEventListener('DOMContentLoaded', fetchRoles);
</script>