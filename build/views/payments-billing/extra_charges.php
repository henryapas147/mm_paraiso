<?php if (false) { } ?>

<!-- PAGE CONTENT -->
      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Charges</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statTotal">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Damage Fees</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statDamage">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Late Checkout</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statLate">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Amount</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statAmount">-</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Extra Charges Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Extra Charges</h6>
              <div class="flex items-center gap-3">
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search..." class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-white/20 dark:text-white dark:placeholder-white/50">
                <button onclick="openAddChargeModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                  <i class="fas fa-plus mr-1"></i> Add Charge
                </button>
              </div>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="extraChargesTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reservation</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Description</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Amount</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Charged At</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="extraChargesTableBody">
                    <tr><td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add Charge Modal -->
      <div id="addChargeModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddChargeModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 560px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Add Extra Charge</h3>
            <button onclick="closeAddChargeModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form id="addChargeForm">
              <div class="space-y-4">
                <div>
                  <label for="reservation_id" class="block text-sm font-medium text-gray-700">Reservation ID</label>
                  <input type="text" id="reservation_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="UUID">
                </div>
                <div>
                  <label for="charge_type" class="block text-sm font-medium text-gray-700">Charge Type</label>
                  <select id="charge_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="damage">Damage</option>
                    <option value="late_checkout">Late Checkout</option>
                    <option value="extra_amenity">Extra Amenity</option>
                    <option value="cleaning">Cleaning</option>
                    <option value="extension">Extension</option>
                    <option value="other" selected>Other</option>
                  </select>
                </div>
                <div>
                  <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                  <input type="text" id="description" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., Broken chair">
                </div>
                <div>
                  <label for="amount" class="block text-sm font-medium text-gray-700">Amount (₱)</label>
                  <input type="number" id="amount" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="0.00">
                </div>
                <div>
                  <label for="charged_at" class="block text-sm font-medium text-gray-700">Charged At</label>
                  <input type="datetime-local" id="charged_at" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                  <textarea id="notes" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
              </div>
              <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                <button type="button" onclick="closeAddChargeModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">Cancel</button>
                <button type="button" onclick="submitAddCharge()" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">Add Charge</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Edit Charge Modal -->
      <div id="editChargeModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditChargeModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 560px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Edit Extra Charge</h3>
            <button onclick="closeEditChargeModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form id="editChargeForm">
              <input type="hidden" id="edit_charge_id">
              <div class="space-y-4">
                <div>
                  <label for="edit_reservation_id" class="block text-sm font-medium text-gray-700">Reservation ID</label>
                  <input type="text" id="edit_reservation_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_charge_type" class="block text-sm font-medium text-gray-700">Charge Type</label>
                  <select id="edit_charge_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="damage">Damage</option>
                    <option value="late_checkout">Late Checkout</option>
                    <option value="extra_amenity">Extra Amenity</option>
                    <option value="cleaning">Cleaning</option>
                    <option value="extension">Extension</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                  <input type="text" id="edit_description" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_amount" class="block text-sm font-medium text-gray-700">Amount (₱)</label>
                  <input type="number" id="edit_amount" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_charged_at" class="block text-sm font-medium text-gray-700">Charged At</label>
                  <input type="datetime-local" id="edit_charged_at" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_notes" class="block text-sm font-medium text-gray-700">Notes</label>
                  <textarea id="edit_notes" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
              </div>
              <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                <button type="button" onclick="closeEditChargeModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">Cancel</button>
                <button type="button" onclick="submitEditCharge()" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">Update Charge</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- View Charge Modal -->
      <div id="viewChargeModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeViewChargeModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 560px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Charge Details</h3>
            <button onclick="closeViewChargeModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-500">Reservation ID</label>
                <p id="view_reservation_id" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Charge Type</label>
                <p id="view_charge_type" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Description</label>
                <p id="view_description" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Amount</label>
                <p id="view_amount" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Charged At</label>
                <p id="view_charged_at" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Notes</label>
                <p id="view_notes" class="mt-1 text-sm text-gray-900"></p>
              </div>
            </div>
          </div>
        </div>
      </div>

<script>
  let extraChargesData = [];

  const CHARGE_TYPE_GRADIENT = {
    'damage':        'from-red-500 to-rose-400',
    'late_checkout': 'from-amber-500 to-orange-400',
    'extra_amenity': 'from-blue-500 to-indigo-400',
    'cleaning':      'from-teal-500 to-cyan-400',
    'extension':     'from-violet-500 to-purple-400',
    'other':         'from-slate-500 to-slate-400',
  };

  document.addEventListener('DOMContentLoaded', function () {
    fetchExtraCharges();
  });

  async function fetchExtraCharges() {
    try {
      const res = await fetch('../api/extra_charges.php?action=list');
      const result = await res.json();
      if (result.success) {
        extraChargesData = result.data;
        renderTable();
        renderStats();
      } else {
        document.getElementById('extraChargesTableBody').innerHTML = `<tr><td colspan="6" class="p-4 text-center text-red-400">${result.message}</td></tr>`;
      }
    } catch (e) {
      console.error('Error fetching extra charges:', e);
      document.getElementById('extraChargesTableBody').innerHTML = `<tr><td colspan="6" class="p-4 text-center text-red-400">Failed to load data</td></tr>`;
    }
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = extraChargesData.length;
    document.getElementById('statDamage').textContent = extraChargesData.filter(c => c.charge_type === 'damage').length;
    document.getElementById('statLate').textContent = extraChargesData.filter(c => c.charge_type === 'late_checkout').length;
    const total = extraChargesData.reduce((s, c) => s + parseFloat(c.amount || 0), 0);
    document.getElementById('statAmount').textContent = '₱' + total.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function renderTable() {
    const tbody = document.getElementById('extraChargesTableBody');
    if (!extraChargesData.length) {
      tbody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">No extra charges found. <a href="javascript:;" onclick="openAddChargeModal()" class="text-blue-500 font-semibold">Add your first charge</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = extraChargesData.map(c => {
      const gradClass = CHARGE_TYPE_GRADIENT[c.charge_type] || 'from-slate-500 to-slate-400';
      const typeLabel = (c.charge_type || '').replace(/_/g, ' ').replace(/\b\w/g, ch => ch.toUpperCase());
      const chargedAt = c.charged_at ? new Date(c.charged_at).toLocaleString('en-US', { month: 'short', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';
      return `
        <tr class="charge-row">
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${c.reservation_id ? c.reservation_id.substring(0, 8) + '...' : '-'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <span class="bg-gradient-to-tl ${gradClass} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${typeLabel}</span>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${c.description || '-'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">₱${parseFloat(c.amount || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${chargedAt}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <div class="flex items-center gap-2">
              <button onclick="openViewChargeModal('${c.charge_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-info-circle mr-1.5 text-xs"></i><span>View</span>
              </button>
              <button onclick="openEditChargeModal('${c.charge_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 hover:text-amber-600 hover:border-amber-300 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-edit mr-1.5 text-xs"></i><span>Edit</span>
              </button>
              <button onclick="confirmDeleteCharge('${c.charge_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 hover:text-red-700 hover:border-red-300 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-trash-alt mr-1.5 text-xs"></i><span>Delete</span>
              </button>
            </div>
          </td>
        </tr>`;
    }).join('');
  }

  function openAddChargeModal() {
    document.getElementById('addChargeForm').reset();
    document.getElementById('addChargeModal').style.display = 'block';
    document.getElementById('addChargeModal').classList.remove('hidden');
  }

  function closeAddChargeModal() {
    document.getElementById('addChargeModal').style.display = 'none';
    document.getElementById('addChargeModal').classList.add('hidden');
  }

  async function submitAddCharge() {
    const data = {
      reservation_id: document.getElementById('reservation_id').value,
      charge_type: document.getElementById('charge_type').value,
      description: document.getElementById('description').value,
      amount: document.getElementById('amount').value,
      charged_at: document.getElementById('charged_at').value,
      notes: document.getElementById('notes').value,
    };
    try {
      const res = await fetch('../api/extra_charges.php?action=create', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeAddChargeModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Extra charge added successfully', timer: 2000, showConfirmButton: false });
        fetchExtraCharges();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to add charge', confirmButtonColor: '#3085d6' });
    }
  }

  function openEditChargeModal(chargeId) {
    const charge = extraChargesData.find(c => c.charge_id === chargeId);
    if (charge) {
      document.getElementById('edit_charge_id').value = charge.charge_id;
      document.getElementById('edit_reservation_id').value = charge.reservation_id;
      document.getElementById('edit_charge_type').value = charge.charge_type;
      document.getElementById('edit_description').value = charge.description;
      document.getElementById('edit_amount').value = charge.amount;
      document.getElementById('edit_charged_at').value = charge.charged_at ? charge.charged_at.replace(' ', 'T').substring(0, 16) : '';
      document.getElementById('edit_notes').value = charge.notes || '';
      document.getElementById('editChargeModal').style.display = 'block';
      document.getElementById('editChargeModal').classList.remove('hidden');
    }
  }

  function closeEditChargeModal() {
    document.getElementById('editChargeModal').style.display = 'none';
    document.getElementById('editChargeModal').classList.add('hidden');
  }

  async function submitEditCharge() {
    const data = {
      charge_id: document.getElementById('edit_charge_id').value,
      reservation_id: document.getElementById('edit_reservation_id').value,
      charge_type: document.getElementById('edit_charge_type').value,
      description: document.getElementById('edit_description').value,
      amount: document.getElementById('edit_amount').value,
      charged_at: document.getElementById('edit_charged_at').value,
      notes: document.getElementById('edit_notes').value,
    };
    try {
      const res = await fetch('../api/extra_charges.php?action=update', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeEditChargeModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Extra charge updated successfully', timer: 2000, showConfirmButton: false });
        fetchExtraCharges();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to update charge', confirmButtonColor: '#3085d6' });
    }
  }

  function openViewChargeModal(chargeId) {
    const charge = extraChargesData.find(c => c.charge_id === chargeId);
    if (charge) {
      document.getElementById('view_reservation_id').textContent = charge.reservation_id || '-';
      document.getElementById('view_charge_type').textContent = (charge.charge_type || '').replace(/_/g, ' ').replace(/\b\w/g, ch => ch.toUpperCase());
      document.getElementById('view_description').textContent = charge.description || '-';
      document.getElementById('view_amount').textContent = '₱' + parseFloat(charge.amount || 0).toFixed(2);
      document.getElementById('view_charged_at').textContent = charge.charged_at ? new Date(charge.charged_at).toLocaleString() : '-';
      document.getElementById('view_notes').textContent = charge.notes || 'N/A';
      document.getElementById('viewChargeModal').style.display = 'block';
      document.getElementById('viewChargeModal').classList.remove('hidden');
    }
  }

  function closeViewChargeModal() {
    document.getElementById('viewChargeModal').style.display = 'none';
    document.getElementById('viewChargeModal').classList.add('hidden');
  }

  function confirmDeleteCharge(chargeId) {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          const res = await fetch('../api/extra_charges.php?action=delete', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ charge_id: chargeId })
          });
          const r = await res.json();
          if (r.success) {
            Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Extra charge deleted.', timer: 1500, showConfirmButton: false });
            fetchExtraCharges();
          } else {
            Swal.fire({ icon: 'error', title: 'Error!', text: r.message, confirmButtonColor: '#3085d6' });
          }
        } catch (e) {
          Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to delete charge', confirmButtonColor: '#3085d6' });
        }
      }
    });
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    document.querySelectorAll('#extraChargesTable .charge-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }
</script>
