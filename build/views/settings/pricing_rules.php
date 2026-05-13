<?php if (false) {} ?>
<script>
  let pricingRulesData = [];
  let propertiesData = [];
  let categoriesData = [];

  async function fetchPricingRules() {
    try {
      const res = await fetch('../api/pricing_rules.php?action=list');
      const json = await res.json();
      if (json.success) {
        pricingRulesData = json.data;
        renderStats();
        renderTable();
      }
    } catch (e) {
      console.error('Error fetching pricing rules:', e);
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/pricing_rules.php?action=related');
      const json = await res.json();
      if (json.success) {
        propertiesData = json.data.properties;
        categoriesData = json.data.categories;
        populateDropdowns();
      }
    } catch (e) {
      console.error('Error fetching related data:', e);
    }
  }

  function renderStats() {
    const active = pricingRulesData.filter(r => r.is_active == 1).length;
    const discount = pricingRulesData.filter(r => ['percentage_discount', 'fixed_add'].includes(r.rule_type)).length;
    const minStay = pricingRulesData.filter(r => r.rule_type == 'minimum_stay').length;
    document.getElementById('statTotal').textContent = pricingRulesData.length;
    document.getElementById('statActive').textContent = active;
    document.getElementById('statDiscount').textContent = discount;
    document.getElementById('statMinStay').textContent = minStay;
  }

  function renderTable() {
    const tbody = document.getElementById('pricingRulesTableBody');
    if (!pricingRulesData.length) {
      tbody.innerHTML = `<tr><td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">No pricing rules found.</td></tr>`;
      return;
    }
    tbody.innerHTML = pricingRulesData.map(rule => `
      <tr class="pricing-rule-row">
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${rule.rule_name}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <span class="bg-gradient-to-tl from-blue-500 to-cyan-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${rule.rule_type.replace(/_/g, ' ')}</span>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${rule.property_name || 'All Properties'}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${rule.category_name || 'All Categories'}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${rule.rule_value}</p>
        </td>
        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <span class="bg-gradient-to-tl ${rule.is_active ? 'from-emerald-500 to-teal-400' : 'from-slate-500 to-slate-400'} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${rule.is_active ? 'Active' : 'Inactive'}</span>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex items-center gap-2">
            <button onclick="openEditRuleModal('${rule.rule_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Rule">
              <i class="fas fa-edit mr-1.5 text-xs"></i><span>Edit</span>
            </button>
            <button onclick="deleteRule(this)" data-id="${rule.rule_id}" data-name="${rule.rule_name}" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Rule">
              <i class="fas fa-trash-alt mr-1.5 text-xs"></i><span>Delete</span>
            </button>
          </div>
        </td>
      </tr>`).join('');
  }

  function populateDropdowns() {
    const propOpts = '<option value="">All Properties</option>' + propertiesData.map(p => `<option value="${p.property_id}">${p.property_name}</option>`).join('');
    const catOpts = '<option value="">All Categories</option>' + categoriesData.map(c => `<option value="${c.category_id}">${c.category_name}</option>`).join('');
    ['addProperty', 'editProperty'].forEach(id => { const sel = document.getElementById(id); if (sel) sel.innerHTML = propOpts; });
    ['addCategory', 'editCategory'].forEach(id => { const sel = document.getElementById(id); if (sel) sel.innerHTML = catOpts; });
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchPricingRules();
    fetchRelated();
  });
</script>

<!-- PAGE CONTENT -->
      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Rules</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statTotal">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Active Rules</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-emerald-500" id="statActive">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Discount Rules</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-blue-500" id="statDiscount">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Min Stay Rules</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-orange-500" id="statMinStay">...</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Pricing Rules Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Pricing Rules</h6>
              <div class="flex gap-2">
                <button class="bg-gradient-to-tl from-slate-500 to-slate-400 text-white px-3 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                  <i class="fas fa-download mr-1"></i> Export
                </button>
                <button class="bg-gradient-to-tl from-purple-500 to-indigo-400 text-white px-3 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                  <i class="fas fa-copy mr-1"></i> Duplicate
                </button>
                <button class="bg-gradient-to-tl from-emerald-500 to-teal-400 text-white px-3 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                  <i class="fas fa-check-double mr-1"></i> Activate All
                </button>
                <button class="bg-gradient-to-tl from-slate-500 to-slate-400 text-white px-3 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                  <i class="fas fa-ban mr-1"></i> Deactivate All
                </button>
                <button onclick="openAddRuleModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                  <i class="fas fa-plus mr-1"></i> New Rule
                </button>
              </div>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="rulesTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Rule Name</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Value</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Priority</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="pricingRulesTableBody">
                    <tr><td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add Rule Modal -->
      <div id="addRuleModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddRuleModal()"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Create New Pricing Rule</h3>
            <button onclick="closeAddRuleModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addRuleForm" onsubmit="submitAddRule(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Property <span class="text-red-500">*</span></label>
                  <select name="property_id" id="addProperty" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Properties</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Category</label>
                  <select name="category_id" id="addCategory" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Categories</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Rule Name <span class="text-red-500">*</span></label>
                  <input type="text" name="rule_name" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Weekend Rate">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Rule Type <span class="text-red-500">*</span></label>
                  <select name="rule_type" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="fixed_price">Fixed Price</option>
                    <option value="percentage_add">Percentage Add</option>
                    <option value="percentage_discount">Percentage Discount</option>
                    <option value="fixed_add">Fixed Add</option>
                    <option value="minimum_stay">Minimum Stay</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Applies To <span class="text-red-500">*</span></label>
                  <select name="applies_to" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all">All Types</option>
                    <option value="overnight">Overnight</option>
                    <option value="hourly">Hourly</option>
                    <option value="day_use">Day Use</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Rule Value <span class="text-red-500">*</span></label>
                  <input type="number" step="0.01" name="rule_value" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., 10.00">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Minimum Nights</label>
                  <input type="number" name="minimum_nights" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., 2">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Priority <span class="text-red-500">*</span></label>
                  <input type="number" name="priority" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., 0">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Date Start</label>
                  <input type="date" name="date_start" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Date End</label>
                  <input type="date" name="date_end" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Days of Week</label>
                  <div class="flex flex-wrap gap-2">
                    <?php $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun']; ?>
                    <?php foreach ($days as $day): ?>
                      <label class="inline-flex items-center">
                        <input type="checkbox" name="day_of_week[]" value="<?php echo $day; ?>" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-slate-600 dark:text-white capitalize"><?php echo $day; ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Is Active</label>
                  <select name="is_active" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeAddRuleModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Create Rule
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Rule Modal -->
      <div id="editRuleModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditRuleModal()"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Pricing Rule</h3>
            <button onclick="closeEditRuleModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="editRuleForm" onsubmit="submitEditRule(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="rule_id" id="editRuleId">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Property <span class="text-red-500">*</span></label>
                  <select name="property_id" id="editProperty" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Properties</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Category</label>
                  <select name="category_id" id="editCategory" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Categories</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Rule Name <span class="text-red-500">*</span></label>
                  <input type="text" name="rule_name" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Weekend Rate">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Rule Type <span class="text-red-500">*</span></label>
                  <select name="rule_type" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="fixed_price">Fixed Price</option>
                    <option value="percentage_add">Percentage Add</option>
                    <option value="percentage_discount">Percentage Discount</option>
                    <option value="fixed_add">Fixed Add</option>
                    <option value="minimum_stay">Minimum Stay</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Applies To <span class="text-red-500">*</span></label>
                  <select name="applies_to" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all">All Types</option>
                    <option value="overnight">Overnight</option>
                    <option value="hourly">Hourly</option>
                    <option value="day_use">Day Use</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Rule Value <span class="text-red-500">*</span></label>
                  <input type="number" step="0.01" name="rule_value" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., 10.00">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Minimum Nights</label>
                  <input type="number" name="minimum_nights" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., 2">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Priority <span class="text-red-500">*</span></label>
                  <input type="number" name="priority" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., 0">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Date Start</label>
                  <input type="date" name="date_start" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Date End</label>
                  <input type="date" name="date_end" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Days of Week</label>
                  <div class="flex flex-wrap gap-2">
                    <?php $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun']; ?>
                    <?php foreach ($days as $day): ?>
                      <label class="inline-flex items-center">
                        <input type="checkbox" name="day_of_week[]" value="<?php echo $day; ?>" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-slate-600 dark:text-white capitalize"><?php echo $day; ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Is Active</label>
                  <select name="is_active" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeEditRuleModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Update Rule
              </button>
            </div>
          </form>
        </div>
      </div>

<script>
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('rulesTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
      const row = rows[i];
      const cells = row.getElementsByTagName('td');
      let found = false;

      for (let j = 0; j < cells.length; j++) {
        if (cells[j]) {
          const text = cells[j].textContent || cells[j].innerText;
          if (text.toLowerCase().indexOf(filter) > -1) {
            found = true;
            break;
          }
        }
      }

      row.style.display = found ? '' : 'none';
    }
  }

  function openAddRuleModal() {
    document.getElementById('addRuleModal').style.display = 'block';
  }

  function closeAddRuleModal() {
    document.getElementById('addRuleModal').style.display = 'none';
    document.getElementById('addRuleForm').reset();
  }

  function openEditRuleModal(ruleId) {
    const rule = pricingRulesData.find(r => r.rule_id == ruleId);
    if (rule) {
      document.getElementById('editRuleId').value = rule.rule_id;
      const form = document.getElementById('editRuleForm');
      form.property_id.value = rule.property_id;
      form.category_id.value = rule.category_id || '';
      form.rule_name.value = rule.rule_name;
      form.rule_type.value = rule.rule_type;
      form.applies_to.value = rule.applies_to;
      form.rule_value.value = rule.rule_value;
      form.minimum_nights.value = rule.minimum_nights || '';
      form.priority.value = rule.priority;
      form.date_start.value = rule.date_start || '';
      form.date_end.value = rule.date_end || '';
      form.is_active.value = rule.is_active;

      // Handle day_of_week checkboxes
      const days = rule.day_of_week ? explode(',', rule.day_of_week) : [];
      const checkboxes = form.querySelectorAll('input[name="day_of_week[]"]');
      checkboxes.forEach(cb => {
        cb.checked = days.includes(cb.value);
      });

      document.getElementById('editRuleModal').style.display = 'block';
    }
  }

  function closeEditRuleModal() {
    document.getElementById('editRuleModal').style.display = 'none';
    document.getElementById('editRuleForm').reset();
  }

  function submitAddRule(event) {
    event.preventDefault();
    const form = document.getElementById('addRuleForm');
    const checkboxes = form.querySelectorAll('input[name="day_of_week[]"]:checked');
    const daysOfWeek = Array.from(checkboxes).map(cb => cb.value);
    const payload = {
      property_id: form.elements['property_id'].value,
      category_id: form.elements['category_id'].value || null,
      room_id: form.elements['room_id'].value || null,
      rule_name: form.elements['rule_name'].value,
      rule_type: form.elements['rule_type'].value,
      applies_to: form.elements['applies_to'].value || 'all',
      rule_value: form.elements['rule_value'].value,
      minimum_nights: form.elements['minimum_nights'].value || null,
      day_of_week: daysOfWeek,
      date_start: form.elements['date_start'].value || null,
      date_end: form.elements['date_end'].value || null,
      priority: form.elements['priority'].value || 0,
      is_active: form.elements['is_active'].value
    };

    Swal.fire({
      title: 'Creating...',
      text: 'Please wait while we create the pricing rule.',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); },
      customClass: { popup: 'swal2-popup-high-z' }
    });

    fetch('../api/pricing_rules.php?action=create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddRuleModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchPricingRules());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeAddRuleModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while creating the pricing rule.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  function submitEditRule(event) {
    event.preventDefault();
    const form = document.getElementById('editRuleForm');
    const checkboxes = form.querySelectorAll('input[name="day_of_week[]"]:checked');
    const daysOfWeek = Array.from(checkboxes).map(cb => cb.value);
    const payload = {
      rule_id: form.elements['rule_id'].value,
      property_id: form.elements['property_id'].value,
      category_id: form.elements['category_id'].value || null,
      room_id: form.elements['room_id'].value || null,
      rule_name: form.elements['rule_name'].value,
      rule_type: form.elements['rule_type'].value,
      applies_to: form.elements['applies_to'].value || 'all',
      rule_value: form.elements['rule_value'].value,
      minimum_nights: form.elements['minimum_nights'].value || null,
      day_of_week: daysOfWeek,
      date_start: form.elements['date_start'].value || null,
      date_end: form.elements['date_end'].value || null,
      priority: form.elements['priority'].value || 0,
      is_active: form.elements['is_active'].value
    };

    Swal.fire({
      title: 'Updating...',
      text: 'Please wait while we update the pricing rule.',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); },
      customClass: { popup: 'swal2-popup-high-z' }
    });

    fetch('../api/pricing_rules.php?action=update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditRuleModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchPricingRules());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeEditRuleModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while updating the pricing rule.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  function deleteRule(btn, ruleId, ruleName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to delete the pricing rule: ${ruleName}`,
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
          text: 'Please wait while we delete the pricing rule.',
          allowOutsideClick: false,
          didOpen: () => { Swal.showLoading(); },
          customClass: { popup: 'swal2-popup-high-z' }
        });

        fetch('../api/pricing_rules.php?action=delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ rule_id: ruleId })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
                .then(() => fetchPricingRules());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
            }
          })
          .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while deleting the pricing rule.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
          });
      }
    });
  }
</script>