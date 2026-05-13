<?php if (false) {} ?>
<script>
  let transfersData = [];
  let productsData = [];
  let locationsData = [];
  let uomsData = [];

  async function fetchTransfers() {
    try {
      const res = await fetch('../api/stock_transfers.php?action=list');
      const json = await res.json();
      if (json.success) {
        transfersData = json.data;
        renderStats();
        renderTable();
      }
    } catch (e) {
      console.error('Error fetching transfers:', e);
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/stock_transfers.php?action=related');
      const json = await res.json();
      if (json.success) {
        productsData = json.data.products;
        locationsData = json.data.locations;
        uomsData = json.data.uoms;
        populateDropdowns();
      }
    } catch (e) {
      console.error('Error fetching related data:', e);
    }
  }

  function renderStats() {
    const pending = transfersData.filter(t => t.transfer_status === 'pending').length;
    const inTransit = transfersData.filter(t => t.transfer_status === 'in_transit').length;
    const completed = transfersData.filter(t => t.transfer_status === 'completed').length;
    document.getElementById('statTotal').textContent = transfersData.length;
    document.getElementById('statPending').textContent = pending;
    document.getElementById('statInTransit').textContent = inTransit;
    document.getElementById('statCompleted').textContent = completed;
  }

  function renderTable() {
    const tbody = document.getElementById('transfersTableBody');
    if (!transfersData.length) {
      tbody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">No stock transfers found. <a href="javascript:;" onclick="openAddTransferModal()" class="text-blue-500 font-semibold">Create your first transfer</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = transfersData.map(transfer => {
      const statusColors = {
        'pending': 'from-orange-500 to-amber-400',
        'in_transit': 'from-blue-500 to-cyan-400',
        'completed': 'from-emerald-500 to-teal-400',
        'cancelled': 'from-red-500 to-rose-400'
      };
      const color = statusColors[transfer.transfer_status] ?? 'from-slate-500 to-slate-400';
      return `
      <tr class="transfer-row">
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex px-2 py-1">
            <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
              <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="flex flex-col justify-center">
              <h6 class="mb-0 text-sm leading-normal dark:text-white">${transfer.transfer_id.substring(0, 8)}...</h6>
              <p class="mb-0 text-xs text-slate-400 dark:text-white/60">${transfer.items?.length || 0} item(s)</p>
            </div>
          </div>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${transfer.from_location_name}</p>
          <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400">${transfer.from_property_name}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${transfer.to_location_name}</p>
          <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400">${transfer.to_property_name}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${new Date(transfer.transfer_date).toLocaleDateString('en-US', {month:'short', day:'2-digit', year:'numeric'})}</p>
        </td>
        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <span class="bg-gradient-to-tl ${color} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${transfer.transfer_status.replace(/_/g, ' ')}</span>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex items-center gap-2">
            <button onclick="viewTransferDetails('${transfer.transfer_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
              <i class="fas fa-info-circle mr-1.5 text-xs"></i><span>View</span>
            </button>
            <button onclick="openEditTransferModal('${transfer.transfer_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Transfer">
              <i class="fas fa-edit mr-1.5 text-xs"></i><span>Edit</span>
            </button>
            <button onclick="deleteTransfer(this)" data-id="${transfer.transfer_id}" data-short="${transfer.transfer_id.substring(0, 8)}" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Transfer">
              <i class="fas fa-trash-alt mr-1.5 text-xs"></i><span>Delete</span>
            </button>
          </div>
        </td>
      </tr>`;
    }).join('');
  }

  function populateDropdowns() {
    const fromLocSel = document.getElementById('addFromLocation');
    const toLocSel = document.getElementById('addToLocation');
    const editFromLocSel = document.getElementById('editFromLocation');
    const editToLocSel = document.getElementById('editToLocation');
    const opts = '<option value="">Select Location</option>' + locationsData.map(l => `<option value="${l.location_id}">${l.property_name} - ${l.location_name}</option>`).join('');
    [fromLocSel, toLocSel, editFromLocSel, editToLocSel].forEach(sel => { if (sel) sel.innerHTML = opts; });
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchTransfers();
    fetchRelated();
  });
</script>

<!-- PAGE CONTENT -->

      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Transfers</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statTotal">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Pending</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-orange-500" id="statPending">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">In Transit</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-blue-500" id="statInTransit">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Completed</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-emerald-500" id="statCompleted">...</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Transfers Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Stock Transfers</h6>
              <button onclick="openAddTransferModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> New Transfer
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="transfersTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Transfer ID</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">From</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">To</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Date</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="transfersTableBody">
                    <tr><td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- View Transfer Details Modal -->
      <div id="transferDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeTransferDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white" id="transferModalTitle">Transfer Details</h3>
            <button onclick="closeTransferDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6" id="transferModalContent">
            <!-- Content will be populated by JavaScript -->
          </div>
        </div>
      </div>

      <!-- Add Transfer Modal -->
      <div id="addTransferModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddTransferModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Create New Stock Transfer</h3>
            <button onclick="closeAddTransferModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addTransferForm" onsubmit="submitAddTransfer(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">From Location <span class="text-red-500">*</span></label>
                  <select name="from_location_id" id="addFromLocation" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select From Location</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">To Location <span class="text-red-500">*</span></label>
                  <select name="to_location_id" id="addToLocation" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select To Location</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Transfer Date <span class="text-red-500">*</span></label>
                  <input type="date" name="transfer_date" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Status</label>
                  <select name="transfer_status" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="pending" selected>Pending</option>
                    <option value="in_transit">In Transit</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                  </select>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Additional notes..."></textarea>
                </div>
              </div>

              <div class="mt-6">
                <div class="flex justify-between items-center mb-3">
                  <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase">Transfer Items</h4>
                  <button type="button" onclick="addTransferItem()" class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-plus mr-1"></i> Add Item
                  </button>
                </div>
                <div id="transferItemsContainer" class="space-y-3">
                  <!-- Transfer items will be added here dynamically -->
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeAddTransferModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Create Transfer
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Transfer Modal -->
      <div id="editTransferModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditTransferModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Stock Transfer</h3>
            <button onclick="closeEditTransferModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="editTransferForm" onsubmit="submitEditTransfer(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="transfer_id" id="editTransferId">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">From Location <span class="text-red-500">*</span></label>
                  <select name="from_location_id" id="editFromLocation" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select From Location</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">To Location <span class="text-red-500">*</span></label>
                  <select name="to_location_id" id="editToLocation" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select To Location</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Transfer Date <span class="text-red-500">*</span></label>
                  <input type="date" name="transfer_date" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Status</label>
                  <select name="transfer_status" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="pending" selected>Pending</option>
                    <option value="in_transit">In Transit</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                  </select>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Additional notes..."></textarea>
                </div>
              </div>

              <div class="mt-6">
                <div class="flex justify-between items-center mb-3">
                  <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase">Transfer Items</h4>
                  <button type="button" onclick="addTransferItem('edit')" class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-plus mr-1"></i> Add Item
                  </button>
                </div>
                <div id="editTransferItemsContainer" class="space-y-3">
                  <!-- Transfer items will be added here dynamically -->
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeEditTransferModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Update Transfer
              </button>
            </div>
          </form>
        </div>
      </div>

<script>
  // Filter table function
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('transfersTable');
    const rows = table.getElementsByClassName('transfer-row');

    for (let i = 0; i < rows.length; i++) {
      const row = rows[i];
      const cells = row.getElementsByTagName('td');
      let found = false;

      for (let j = 0; j < cells.length; j++) {
        if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
          found = true;
          break;
        }
      }

      row.style.display = found ? '' : 'none';
    }
  }

  // View Transfer Details
  async function viewTransferDetails(transferId) {
    try {
      const res = await fetch(`../api/stock_transfers.php?action=single&id=${transferId}`);
      const json = await res.json();
      if (!json.success || !json.data) return;
      const transfer = json.data;

    const modal = document.getElementById('transferDetailsModal');
    const modalTitle = document.getElementById('transferModalTitle');
    const modalContent = document.getElementById('transferModalContent');

    modalTitle.textContent = `Transfer: ${transfer.transfer_id.substring(0, 8)}...`;

    const statusColors = {
      'pending': 'from-orange-500 to-amber-400',
      'in_transit': 'from-blue-500 to-cyan-400',
      'completed': 'from-emerald-500 to-teal-400',
      'cancelled': 'from-red-500 to-rose-400'
    };
    const color = statusColors[transfer.transfer_status] ?? 'from-slate-500 to-slate-400';

    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">From Location</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Property</p>
                <p class="text-sm dark:text-white">${transfer.from_property_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Location</p>
                <p class="text-sm dark:text-white">${transfer.from_location_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Location Type</p>
                <p class="text-sm dark:text-white">${transfer.from_location_type.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">To Location</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Property</p>
                <p class="text-sm dark:text-white">${transfer.to_property_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Location</p>
                <p class="text-sm dark:text-white">${transfer.to_location_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Location Type</p>
                <p class="text-sm dark:text-white">${transfer.to_location_type.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Transfer Details</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Transfer ID</p>
                <p class="text-sm dark:text-white font-mono">${transfer.transfer_id}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Transfer Date</p>
                <p class="text-sm dark:text-white">${new Date(transfer.transfer_date).toLocaleDateString()}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Status</p>
                <span class="bg-gradient-to-tl ${color} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${transfer.transfer_status.replace(/_/g, ' ')}</span>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Created At</p>
                <p class="text-sm dark:text-white">${new Date(transfer.created_at).toLocaleString()}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Notes</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Notes</p>
                <p class="text-sm dark:text-white">${transfer.notes || '-'}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-6">
          <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Transfer Items (${transfer.items.length})</h4>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b border-gray-200 dark:border-white/10">
                  <th class="text-left py-2 px-3 text-xs font-semibold text-slate-500 dark:text-white/60">Product</th>
                  <th class="text-left py-2 px-3 text-xs font-semibold text-slate-500 dark:text-white/60">Code</th>
                  <th class="text-right py-2 px-3 text-xs font-semibold text-slate-500 dark:text-white/60">Quantity</th>
                  <th class="text-left py-2 px-3 text-xs font-semibold text-slate-500 dark:text-white/60">UOM</th>
                </tr>
              </thead>
              <tbody>
                ${transfer.items.map(item => `
                  <tr class="border-b border-gray-100 dark:border-white/5">
                    <td class="py-2 px-3 dark:text-white">${item.product_name}</td>
                    <td class="py-2 px-3 dark:text-white/60">${item.product_code}</td>
                    <td class="py-2 px-3 text-right dark:text-white font-semibold">${parseFloat(item.quantity_requested).toFixed(3)}</td>
                    <td class="py-2 px-3 dark:text-white/60">${item.uom_name}</td>
                  </tr>
                `).join('')}
                ${transfer.items.length === 0 ? '<tr><td colspan="4" class="py-4 text-center text-slate-400">No items in this transfer</td></tr>' : ''}
              </tbody>
            </table>
          </div>
        </div>
      `;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
    } catch (e) {
      console.error('Error fetching transfer details:', e);
    }
  }

  function closeTransferDetailsModal() {
    const modal = document.getElementById('transferDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  // Add Transfer Modal Functions
  let transferItemIndex = 0;

  function openAddTransferModal() {
    const modal = document.getElementById('addTransferModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    // Add first item by default
    document.getElementById('transferItemsContainer').innerHTML = '';
    transferItemIndex = 0;
    addTransferItem('add');
  }

  function closeAddTransferModal() {
    const modal = document.getElementById('addTransferModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('addTransferForm').reset();
    document.getElementById('transferItemsContainer').innerHTML = '';
    transferItemIndex = 0;
  }

  function addTransferItem(mode = 'add') {
    const containerId = mode === 'edit' ? 'editTransferItemsContainer' : 'transferItemsContainer';
    const container = document.getElementById(containerId);

    const itemHtml = `
        <div class="transfer-item-row bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg" data-item-index="${transferItemIndex}">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
              <label class="block text-xs font-semibold text-slate-600 dark:text-white mb-1">Product <span class="text-red-500">*</span></label>
              <select name="items[${transferItemIndex}][product_id]" required class="w-full px-3 py-1.5 text-xs border border-gray-300 dark:border-white/20 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Select Product</option>
                ${productsData.map(p => `<option value="${p.product_id}">${p.product_name} (${p.product_code})</option>`).join('')}
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-600 dark:text-white mb-1">Quantity <span class="text-red-500">*</span></label>
              <input type="number" step="0.001" name="items[${transferItemIndex}][quantity]" required class="w-full px-3 py-1.5 text-xs border border-gray-300 dark:border-white/20 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Qty">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-600 dark:text-white mb-1">UOM</label>
              <select name="items[${transferItemIndex}][uom_id]" required class="w-full px-3 py-1.5 text-xs border border-gray-300 dark:border-white/20 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Select UOM</option>
                ${uomsData.map(u => `<option value="${u.uom_id}">${u.uom_name}</option>`).join('')}
              </select>
            </div>
            <div class="flex items-end">
              <button type="button" onclick="removeTransferItem(this)" class="w-full px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 dark:bg-red-500/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-500/30 transition-colors">
                <i class="fas fa-trash-alt mr-1"></i> Remove
              </button>
            </div>
          </div>
        </div>
      `;

    container.insertAdjacentHTML('beforeend', itemHtml);
    transferItemIndex++;
  }

  function removeTransferItem(button) {
    const row = button.closest('.transfer-item-row');
    row.remove();
  }

  function submitAddTransfer(event) {
    event.preventDefault();

    const form = document.getElementById('addTransferForm');
    const formData = new FormData(form);
    const items = [];
    document.querySelectorAll('#transferItemsContainer .transfer-item-row').forEach(row => {
      const index = row.dataset.itemIndex;
      items.push({
        product_id: row.querySelector(`select[name="items[${index}][product_id]"]`).value,
        quantity_requested: row.querySelector(`input[name="items[${index}][quantity]"]`).value,
        uom_id: row.querySelector(`select[name="items[${index}][uom_id]"]`).value
      });
    });

    if (items.length === 0) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Please add at least one item to the transfer.',
        confirmButtonColor: '#d33',
        customClass: { popup: 'swal2-popup-high-z' }
      });
      return;
    }

    Swal.fire({
      title: 'Creating...',
      text: 'Please wait while we create the stock transfer.',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); },
      customClass: { popup: 'swal2-popup-high-z' }
    });

    const payload = {
      from_location_id: form.elements['from_location_id'].value,
      to_location_id: form.elements['to_location_id'].value,
      transfer_date: form.elements['transfer_date'].value,
      transfer_status: form.elements['transfer_status'].value,
      notes: form.elements['notes'].value,
      items: items
    };

    fetch('../api/stock_transfers.php?action=create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddTransferModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchTransfers());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeAddTransferModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while creating the stock transfer.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  // Edit Transfer Modal Functions
  async function openEditTransferModal(transferId) {
    try {
      const res = await fetch(`../api/stock_transfers.php?action=single&id=${transferId}`);
      const json = await res.json();
      if (!json.success || !json.data) return;
      const transfer = json.data;

    document.getElementById('editTransferId').value = transfer.transfer_id;
    const form = document.getElementById('editTransferForm');
    form.elements['from_location_id'].value = transfer.from_location_id;
    form.elements['to_location_id'].value = transfer.to_location_id;
    form.elements['transfer_date'].value = transfer.transfer_date.split(' ')[0];
    form.elements['transfer_status'].value = transfer.transfer_status;
    form.elements['notes'].value = transfer.notes || '';

    // Populate existing items
    const container = document.getElementById('editTransferItemsContainer');
    container.innerHTML = '';
    transferItemIndex = 0;

    if (transfer.items && transfer.items.length > 0) {
      transfer.items.forEach(item => {
        addTransferItem('edit');
        const lastRow = container.querySelector('.transfer-item-row:last-child');
        lastRow.querySelector(`select[name="items[${transferItemIndex - 1}][product_id]"]`).value = item.product_id;
        lastRow.querySelector(`input[name="items[${transferItemIndex - 1}][quantity]"]`).value = parseFloat(item.quantity_requested);
        lastRow.querySelector(`select[name="items[${transferItemIndex - 1}][uom_id]"]`).value = item.uom_id;
      });
    } else {
      addTransferItem('edit');
    }

    const modal = document.getElementById('editTransferModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    } catch (e) {
      console.error('Error fetching transfer for edit:', e);
    }
  }

  function closeEditTransferModal() {
    const modal = document.getElementById('editTransferModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('editTransferForm').reset();
    document.getElementById('editTransferItemsContainer').innerHTML = '';
    transferItemIndex = 0;
  }

  function submitEditTransfer(event) {
    event.preventDefault();

    const form = document.getElementById('editTransferForm');
    const items = [];
    document.querySelectorAll('#editTransferItemsContainer .transfer-item-row').forEach(row => {
      const index = row.dataset.itemIndex;
      items.push({
        product_id: row.querySelector(`select[name="items[${index}][product_id]"]`).value,
        quantity_requested: row.querySelector(`input[name="items[${index}][quantity]"]`).value,
        uom_id: row.querySelector(`select[name="items[${index}][uom_id]"]`).value
      });
    });

    if (items.length === 0) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Please add at least one item to the transfer.',
        confirmButtonColor: '#d33',
        customClass: { popup: 'swal2-popup-high-z' }
      });
      return;
    }

    Swal.fire({
      title: 'Updating...',
      text: 'Please wait while we update the stock transfer.',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); },
      customClass: { popup: 'swal2-popup-high-z' }
    });

    const payload = {
      transfer_id: form.elements['transfer_id'].value,
      from_location_id: form.elements['from_location_id'].value,
      to_location_id: form.elements['to_location_id'].value,
      transfer_date: form.elements['transfer_date'].value,
      transfer_status: form.elements['transfer_status'].value,
      notes: form.elements['notes'].value,
      items: items
    };

    fetch('../api/stock_transfers.php?action=update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditTransferModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchTransfers());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeEditTransferModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while updating the stock transfer.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  // Delete Transfer Function with SweetAlert
  function deleteTransfer(button) {
    const transferId = button.dataset.id;
    const transferShortId = button.dataset.short;

    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete stock transfer "${transferShortId}..."? This action cannot be undone.`,
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
          text: 'Please wait while we delete the stock transfer.',
          allowOutsideClick: false,
          didOpen: () => { Swal.showLoading(); },
          customClass: { popup: 'swal2-popup-high-z' }
        });

        fetch('../api/stock_transfers.php?action=delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ transfer_id: transferId })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
                .then(() => fetchTransfers());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
            }
          })
          .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while deleting the stock transfer.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
          });
      }
    });
  }
</script>