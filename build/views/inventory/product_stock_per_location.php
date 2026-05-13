<?php if (false) {} ?>
<style>
  .swal2-popup { border-radius: 1rem !important; }
  .swal2-confirm { background-color: #3085d6 !important; color: white !important; border: none !important; padding: 0.75rem 1.5rem !important; border-radius: 0.5rem !important; font-weight: 600 !important; }
  .swal2-cancel { background-color: #d33 !important; color: white !important; border: none !important; padding: 0.75rem 1.5rem !important; border-radius: 0.5rem !important; font-weight: 600 !important; }
</style>

<!-- PAGE CONTENT -->

      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Stock Records</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statTotal">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Below Reorder Point</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-red-500" id="statBelowReorder">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Zero Stock</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-orange-500" id="statZero">...</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Stock Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Stock by Location</h6>
              <button onclick="openAddStockModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Adjust Stock
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="stockTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Product</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Location</th>
                      <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Quantity</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="stockTableBody">
                    <tr><td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- View Stock Details Modal -->
      <div id="stockDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeStockDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white" id="stockModalTitle" style="font-size: 1.25rem; font-weight: bold; margin: 0;">Stock Details</h3>
            <button onclick="closeStockDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6" id="stockModalContent" style="padding: 1.5rem;"></div>
        </div>
      </div>

      <!-- Add Stock Modal -->
      <div id="addStockModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddStockModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 600px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Add Stock Adjustment</h3>
            <button onclick="closeAddStockModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addStockForm" onsubmit="submitAddStock(event)">
            <div class="p-6">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Product <span class="text-red-500">*</span></label>
                  <select name="product_id" id="addProductSelect" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Product</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Location <span class="text-red-500">*</span></label>
                  <select name="location_id" id="addLocationSelect" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Location</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Adjustment Type <span class="text-red-500">*</span></label>
                  <select name="adjustment_type" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="add">Add Stock (+)</option>
                    <option value="deduct">Deduct Stock (-)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Quantity <span class="text-red-500">*</span></label>
                  <input type="number" name="quantity" step="0.001" min="0" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.000">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Reason for adjustment..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeAddStockModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">Cancel</button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Save Adjustment
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit/Adjust Stock Modal -->
      <div id="editStockModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditStockModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 600px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Stock</h3>
            <button onclick="closeEditStockModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="editStockForm" onsubmit="submitEditStock(event)">
            <div class="p-6">
              <div class="space-y-4">
                <input type="hidden" name="stock_loc_id" id="editStockLocId">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Product</label>
                  <input type="text" id="editProductName" readonly class="w-full px-4 py-2 text-sm border border-gray-200 dark:border-white/20 dark:bg-slate-700/30 dark:text-white rounded-lg bg-gray-50">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Location</label>
                  <input type="text" id="editLocationName" readonly class="w-full px-4 py-2 text-sm border border-gray-200 dark:border-white/20 dark:bg-slate-700/30 dark:text-white rounded-lg bg-gray-50">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Current Quantity</label>
                  <input type="text" id="editCurrentQuantity" readonly class="w-full px-4 py-2 text-sm border border-gray-200 dark:border-white/20 dark:bg-slate-700/30 dark:text-white rounded-lg bg-gray-50">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Adjustment Type <span class="text-red-500">*</span></label>
                  <select name="adjustment_type" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="add">Add Stock (+)</option>
                    <option value="deduct">Deduct Stock (-)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Quantity <span class="text-red-500">*</span></label>
                  <input type="number" name="quantity" step="0.001" min="0" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.000">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Reason for adjustment..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeEditStockModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">Cancel</button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Save Adjustment
              </button>
            </div>
          </form>
        </div>
      </div>

<script>
  let stockData = [];
  let productsData = [];
  let locationsData = [];

  document.addEventListener('DOMContentLoaded', () => {
    fetchStock();
    fetchRelated();
  });

  async function fetchStock() {
    try {
      const res = await fetch('../api/product_stock_per_location.php?action=list');
      const json = await res.json();
      if (json.success) {
        stockData = json.data;
        renderStats();
        renderTable();
      }
    } catch (e) {
      console.error('Error fetching stock:', e);
      document.getElementById('stockTableBody').innerHTML = `<tr><td colspan="6" class="p-4 text-center text-red-400">Failed to load data.</td></tr>`;
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/product_stock_per_location.php?action=related');
      const json = await res.json();
      if (json.success) {
        productsData = json.data.products;
        locationsData = json.data.locations;
        populateDropdowns();
      }
    } catch (e) {
      console.error('Error fetching related:', e);
    }
  }

  function renderStats() {
    const belowReorder = stockData.filter(s => parseFloat(s.quantity) < parseFloat(s.reorder_point) && parseFloat(s.reorder_point) > 0).length;
    const zeroStock = stockData.filter(s => parseFloat(s.quantity) == 0).length;
    document.getElementById('statTotal').textContent = stockData.length;
    document.getElementById('statBelowReorder').textContent = belowReorder;
    document.getElementById('statZero').textContent = zeroStock;
  }

  function renderTable() {
    const tbody = document.getElementById('stockTableBody');
    if (!stockData.length) {
      tbody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">No stock records found.</td></tr>`;
      return;
    }
    tbody.innerHTML = stockData.map(stock => {
      const qty = parseFloat(stock.quantity);
      const reorder = parseFloat(stock.reorder_point);
      const locType = (stock.location_type || '').replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
      let statusBadge;
      if (qty == 0) {
        statusBadge = `<span class="bg-gradient-to-tl from-orange-500 to-amber-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Zero</span>`;
      } else if (qty < reorder && reorder > 0) {
        statusBadge = `<span class="bg-gradient-to-tl from-red-500 to-rose-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Low Stock</span>`;
      } else {
        statusBadge = `<span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">In Stock</span>`;
      }
      return `
      <tr class="stock-row">
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex px-2 py-1">
            <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
              <i class="fas fa-box"></i>
            </div>
            <div class="flex flex-col justify-center">
              <h6 class="mb-0 text-sm leading-normal dark:text-white">${stock.product_name}</h6>
              <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400">${stock.product_code}</p>
            </div>
          </div>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${stock.property_name}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${stock.location_name}</p>
          <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400">${locType}</p>
        </td>
        <td class="p-2 text-right align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-sm font-bold leading-tight dark:text-white">${qty.toFixed(3)}</p>
        </td>
        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          ${statusBadge}
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex items-center gap-2">
            <button onclick="viewStockDetails('${stock.stock_loc_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
              <i class="fas fa-info-circle mr-1.5 text-xs"></i><span>View</span>
            </button>
            <button onclick="openEditStockModal('${stock.stock_loc_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Adjust Stock">
              <i class="fas fa-edit mr-1.5 text-xs"></i><span>Adjust</span>
            </button>
            <button onclick="deleteStock(this)" data-id="${stock.stock_loc_id}" data-product="${stock.product_name}" data-location="${stock.location_name}" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Stock">
              <i class="fas fa-trash-alt mr-1.5 text-xs"></i><span>Delete</span>
            </button>
          </div>
        </td>
      </tr>`;
    }).join('');
  }

  function populateDropdowns() {
    document.getElementById('addProductSelect').innerHTML = '<option value="">Select Product</option>' +
      productsData.map(p => `<option value="${p.product_id}">${p.product_name} (${p.product_code})</option>`).join('');
    document.getElementById('addLocationSelect').innerHTML = '<option value="">Select Location</option>' +
      locationsData.map(l => `<option value="${l.location_id}">${l.property_name} - ${l.location_name}</option>`).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#stockTable .stock-row').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
  }

  function viewStockDetails(stockLocId) {
    const stock = stockData.find(s => s.stock_loc_id == stockLocId);
    if (!stock) return;

    document.getElementById('stockModalTitle').textContent = `${stock.product_name} - ${stock.location_name}`;

    const qty = parseFloat(stock.quantity);
    const reorder = parseFloat(stock.reorder_point);
    let statusBadge;
    if (qty == 0) {
      statusBadge = `<span class="bg-gradient-to-tl from-orange-500 to-amber-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Zero</span>`;
    } else if (qty < reorder && reorder > 0) {
      statusBadge = `<span class="bg-gradient-to-tl from-red-500 to-rose-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Low Stock</span>`;
    } else {
      statusBadge = `<span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">In Stock</span>`;
    }

    document.getElementById('stockModalContent').innerHTML = `
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Product Information</h4>
          <div class="space-y-3">
            <div><p class="text-xs text-slate-500 dark:text-white/60">Product Name</p><p class="text-sm dark:text-white">${stock.product_name}</p></div>
            <div><p class="text-xs text-slate-500 dark:text-white/60">Product Code</p><p class="text-sm dark:text-white">${stock.product_code}</p></div>
            <div><p class="text-xs text-slate-500 dark:text-white/60">Total Stock (All Locations)</p><p class="text-sm dark:text-white">${parseFloat(stock.total_stock).toFixed(3)}</p></div>
            <div><p class="text-xs text-slate-500 dark:text-white/60">Reorder Point</p><p class="text-sm dark:text-white">${parseFloat(stock.reorder_point).toFixed(3)}</p></div>
          </div>
        </div>
        <div>
          <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Location Information</h4>
          <div class="space-y-3">
            <div><p class="text-xs text-slate-500 dark:text-white/60">Property</p><p class="text-sm dark:text-white">${stock.property_name}</p></div>
            <div><p class="text-xs text-slate-500 dark:text-white/60">Location</p><p class="text-sm dark:text-white">${stock.location_name}</p></div>
            <div><p class="text-xs text-slate-500 dark:text-white/60">Location Type</p><p class="text-sm dark:text-white">${(stock.location_type || '').replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())}</p></div>
          </div>
        </div>
        <div>
          <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Stock at This Location</h4>
          <div class="space-y-3">
            <div><p class="text-xs text-slate-500 dark:text-white/60">Current Quantity</p><p class="text-2xl font-bold dark:text-white">${qty.toFixed(3)}</p></div>
            <div><p class="text-xs text-slate-500 dark:text-white/60">Status</p>${statusBadge}</div>
          </div>
        </div>
        <div>
          <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Timestamp</h4>
          <div class="space-y-3">
            <div><p class="text-xs text-slate-500 dark:text-white/60">Last Updated</p><p class="text-sm dark:text-white">${stock.updated_at ? new Date(stock.updated_at).toLocaleString() : '-'}</p></div>
          </div>
        </div>
      </div>`;

    const modal = document.getElementById('stockDetailsModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeStockDetailsModal() {
    const modal = document.getElementById('stockDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  function openAddStockModal() {
    const modal = document.getElementById('addStockModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeAddStockModal() {
    const modal = document.getElementById('addStockModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('addStockForm').reset();
  }

  function submitAddStock(event) {
    event.preventDefault();
    Swal.fire({ title: 'Processing...', text: 'Please wait while we process the stock adjustment.', allowOutsideClick: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'swal2-popup-high-z' } });

    const form = document.getElementById('addStockForm');
    const payload = {
      product_id: form.elements['product_id'].value,
      location_id: form.elements['location_id'].value,
      adjustment_type: form.elements['adjustment_type'].value,
      quantity: form.elements['quantity'].value,
      notes: form.elements['notes'].value
    };

    fetch('../api/product_stock_per_location.php?action=create', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddStockModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } }).then(() => fetchStock());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => { closeAddStockModal(); Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while processing the stock adjustment.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } }); });
  }

  function openEditStockModal(stockLocId) {
    const stock = stockData.find(s => s.stock_loc_id == stockLocId);
    if (!stock) return;
    document.getElementById('editStockLocId').value = stock.stock_loc_id;
    document.getElementById('editProductName').value = `${stock.product_name} (${stock.product_code})`;
    document.getElementById('editLocationName').value = `${stock.property_name} - ${stock.location_name}`;
    document.getElementById('editCurrentQuantity').value = parseFloat(stock.quantity).toFixed(3);
    const modal = document.getElementById('editStockModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeEditStockModal() {
    const modal = document.getElementById('editStockModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('editStockForm').reset();
  }

  function submitEditStock(event) {
    event.preventDefault();
    Swal.fire({ title: 'Processing...', text: 'Please wait while we process the stock adjustment.', allowOutsideClick: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'swal2-popup-high-z' } });

    const form = document.getElementById('editStockForm');
    const payload = {
      stock_loc_id: form.elements['stock_loc_id'].value,
      adjustment_type: form.elements['adjustment_type'].value,
      quantity: form.elements['quantity'].value,
      notes: form.elements['notes'].value
    };

    fetch('../api/product_stock_per_location.php?action=adjust', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditStockModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } }).then(() => fetchStock());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => { closeEditStockModal(); Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while processing the stock adjustment.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } }); });
  }

  function deleteStock(el) {
    const stockLocId = el.dataset.id;
    const productName = el.dataset.product;
    const locationName = el.dataset.location;
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete the stock record for "${productName}" at "${locationName}"? This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
      customClass: { popup: 'swal2-popup-high-z' }
    }).then(result => {
      if (result.isConfirmed) {
        Swal.fire({ title: 'Deleting...', text: 'Please wait while we delete the stock record.', allowOutsideClick: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'swal2-popup-high-z' } });
        fetch('../api/product_stock_per_location.php?action=delete', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ stock_loc_id: stockLocId })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } }).then(() => fetchStock());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
            }
          })
          .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while deleting the stock record.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } }));
      }
    });
  }
</script>
