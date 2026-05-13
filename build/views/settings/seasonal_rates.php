<?php if (false) {} ?>
<script>
  let seasonalRatesData = [];
  let propertiesData = [];
  let categoriesData = [];

  async function fetchSeasonalRates() {
    try {
      const res = await fetch('../api/seasonal_rates.php?action=list');
      const json = await res.json();
      if (json.success) {
        seasonalRatesData = json.data;
        renderStats();
        renderTable();
      }
    } catch (e) {
      console.error('Error fetching seasonal rates:', e);
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/seasonal_rates.php?action=related');
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
    const active = seasonalRatesData.filter(s => s.is_active == 1).length;
    const peak = seasonalRatesData.filter(s => s.rate_multiplier > 1.0).length;
    const offPeak = seasonalRatesData.filter(s => s.rate_multiplier < 1.0).length;
    document.getElementById('statTotal').textContent = seasonalRatesData.length;
    document.getElementById('statActive').textContent = active;
    document.getElementById('statPeak').textContent = peak;
    document.getElementById('statOffPeak').textContent = offPeak;
  }

  function renderTable() {
    const tbody = document.getElementById('seasonalRatesTableBody');
    if (!seasonalRatesData.length) {
      tbody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">No seasonal rates found.</td></tr>`;
      return;
    }
    tbody.innerHTML = seasonalRatesData.map(rate => `
      <tr class="seasonal-rate-row">
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${rate.season_name}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${rate.property_name || 'All Properties'}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${rate.date_start}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${rate.date_end}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-bold leading-tight dark:text-white">${rate.rate_multiplier}x</p>
        </td>
        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <span class="bg-gradient-to-tl ${rate.is_active ? 'from-emerald-500 to-teal-400' : 'from-slate-500 to-slate-400'} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${rate.is_active ? 'Active' : 'Inactive'}</span>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex items-center gap-2">
            <button onclick="openEditSeasonModal('${rate.season_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Season">
              <i class="fas fa-edit mr-1.5 text-xs"></i><span>Edit</span>
            </button>
            <button onclick="deleteSeason(this)" data-id="${rate.season_id}" data-name="${rate.season_name}" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Season">
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
    fetchSeasonalRates();
    fetchRelated();
  });
</script>

<!-- PAGE CONTENT -->
      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Seasons</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statTotal">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Active Seasons</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-emerald-500" id="statActive">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Peak Seasons</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-red-500" id="statPeak">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Off-Peak Seasons</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-blue-500" id="statOffPeak">...</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Seasonal Rates Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Seasonal Rates</h6>
              <div class="flex gap-2">
                <button class="bg-gradient-to-tl from-slate-500 to-slate-400 text-white px-3 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                  <i class="fas fa-download mr-1"></i> Export
                </button>
                <button class="bg-gradient-to-tl from-purple-500 to-indigo-400 text-white px-3 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                  <i class="fas fa-copy mr-1"></i> Copy Season
                </button>
                <button class="bg-gradient-to-tl from-emerald-500 to-teal-400 text-white px-3 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                  <i class="fas fa-check-double mr-1"></i> Activate All
                </button>
                <button class="bg-gradient-to-tl from-slate-500 to-slate-400 text-white px-3 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                  <i class="fas fa-ban mr-1"></i> Deactivate All
                </button>
                <button onclick="openAddSeasonModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                  <i class="fas fa-plus mr-1"></i> New Season
                </button>
              </div>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="seasonsTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Season Name</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Date Range</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Multiplier</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="seasonalRatesTableBody">
                    <tr><td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add Season Modal -->
      <div id="addSeasonModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddSeasonModal()"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Create New Season</h3>
            <button onclick="closeAddSeasonModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addSeasonForm" onsubmit="submitAddSeason(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Property</label>
                  <select name="property_id" id="addProperty" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Properties</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Category (Optional)</label>
                  <select name="category_id" id="addCategory" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Categories</option>
                  </select>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Season Name <span class="text-red-500">*</span></label>
                  <input type="text" name="season_name" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Summer 2024">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Date Start <span class="text-red-500">*</span></label>
                  <input type="date" name="date_start" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Date End <span class="text-red-500">*</span></label>
                  <input type="date" name="date_end" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Rate Multiplier <span class="text-red-500">*</span></label>
                  <input type="number" step="0.001" name="rate_multiplier" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., 1.5 for 50% increase, 0.8 for 20% discount">
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
              <button type="button" onclick="closeAddSeasonModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Create Season
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Season Modal -->
      <div id="editSeasonModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditSeasonModal()"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Season</h3>
            <button onclick="closeEditSeasonModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="editSeasonForm" onsubmit="submitEditSeason(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="season_id" id="editSeasonId">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Property <span class="text-red-500">*</span></label>
                  <select name="property_id" id="editProperty" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Properties</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Category (Optional)</label>
                  <select name="category_id" id="editCategory" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Categories</option>
                  </select>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Season Name <span class="text-red-500">*</span></label>
                  <input type="text" name="season_name" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Summer 2024">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Date Start <span class="text-red-500">*</span></label>
                  <input type="date" name="date_start" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Date End <span class="text-red-500">*</span></label>
                  <input type="date" name="date_end" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Rate Multiplier <span class="text-red-500">*</span></label>
                  <input type="number" step="0.001" name="rate_multiplier" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., 1.5 for 50% increase, 0.8 for 20% discount">
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
              <button type="button" onclick="closeEditSeasonModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Update Season
              </button>
            </div>
          </form>
        </div>
      </div>

<script>
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('seasonsTable');
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

  function openAddSeasonModal() {
    document.getElementById('addSeasonModal').style.display = 'block';
  }

  function closeAddSeasonModal() {
    document.getElementById('addSeasonModal').style.display = 'none';
    document.getElementById('addSeasonForm').reset();
  }

  function openEditSeasonModal(seasonId) {
    const season = seasonalRatesData.find(s => s.season_id == seasonId);
    if (season) {
      document.getElementById('editSeasonId').value = season.season_id;
      const form = document.getElementById('editSeasonForm');
      form.property_id.value = season.property_id;
      form.applies_to.value = season.applies_to || '';
      form.season_name.value = season.season_name;
      form.date_start.value = season.date_start;
      form.date_end.value = season.date_end;
      form.rate_multiplier.value = season.rate_multiplier;
      form.is_active.value = season.is_active;
      document.getElementById('editSeasonModal').style.display = 'block';
    }
  }

  function closeEditSeasonModal() {
    document.getElementById('editSeasonModal').style.display = 'none';
    document.getElementById('editSeasonForm').reset();
  }

  function submitAddSeason(event) {
    event.preventDefault();
    const form = document.getElementById('addSeasonForm');
    const payload = {
      property_id: form.elements['property_id'].value,
      season_name: form.elements['season_name'].value,
      date_start: form.elements['date_start'].value,
      date_end: form.elements['date_end'].value,
      rate_multiplier: form.elements['rate_multiplier'].value,
      applies_to: form.elements['category_id'].value || null,
      is_active: form.elements['is_active'].value
    };

    Swal.fire({
      title: 'Creating...',
      text: 'Please wait while we create the seasonal rate.',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); },
      customClass: { popup: 'swal2-popup-high-z' }
    });

    fetch('../api/seasonal_rates.php?action=create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddSeasonModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchSeasonalRates());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeAddSeasonModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while creating the seasonal rate.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  function submitEditSeason(event) {
    event.preventDefault();
    const form = document.getElementById('editSeasonForm');
    const payload = {
      season_id: form.elements['season_id'].value,
      property_id: form.elements['property_id'].value,
      season_name: form.elements['season_name'].value,
      date_start: form.elements['date_start'].value,
      date_end: form.elements['date_end'].value,
      rate_multiplier: form.elements['rate_multiplier'].value,
      applies_to: form.elements['category_id'].value || null,
      is_active: form.elements['is_active'].value
    };

    Swal.fire({
      title: 'Updating...',
      text: 'Please wait while we update the seasonal rate.',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); },
      customClass: { popup: 'swal2-popup-high-z' }
    });

    fetch('../api/seasonal_rates.php?action=update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditSeasonModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchSeasonalRates());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeEditSeasonModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while updating the seasonal rate.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  function deleteSeason(btn, seasonId, seasonName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to delete the season: ${seasonName}`,
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
          text: 'Please wait while we delete the seasonal rate.',
          allowOutsideClick: false,
          didOpen: () => { Swal.showLoading(); },
          customClass: { popup: 'swal2-popup-high-z' }
        });

        fetch('../api/seasonal_rates.php?action=delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ season_id: seasonId })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
                .then(() => fetchSeasonalRates());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
            }
          })
          .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while deleting the seasonal rate.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
          });
      }
    });
  }
</script>