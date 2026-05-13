<script>
  let transactionsData = [];
  let productsData = [];
  let locationsData = [];
  let uomsData = [];

  async function fetchTransactions() {
    try {
      const res = await fetch('../api/stock_transactions.php?action=list');
      const json = await res.json();
      if (json.success) {
        transactionsData = json.data;
        renderStats();
        renderTable();
      }
    } catch (e) {
      console.error('Error fetching transactions:', e);
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/stock_transactions.php?action=related');
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
    const stockIn = transactionsData.filter(t => ['stock_in', 'stock_in_manual', 'adjustment_add'].includes(t.transaction_type));
    const stockOut = transactionsData.filter(t => ['stock_out_sale', 'stock_out_use', 'stock_out_damage', 'adjustment_deduct'].includes(t.transaction_type));
    const transfers = transactionsData.filter(t => ['transfer_out', 'transfer_in'].includes(t.transaction_type));
    document.getElementById('statTotal').textContent = transactionsData.length;
    document.getElementById('statStockIn').textContent = stockIn.length;
    document.getElementById('statStockOut').textContent = stockOut.length;
    document.getElementById('statTransfers').textContent = transfers.length;
  }

  function renderTable() {
    const tbody = document.getElementById('transactionsTableBody');
    if (!transactionsData.length) {
      tbody.innerHTML = `<tr><td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">No stock transactions found.</td></tr>`;
      return;
    }
    tbody.innerHTML = transactionsData.map(txn => {
      const isIn = ['stock_in', 'stock_in_manual', 'adjustment_add'].includes(txn.transaction_type);
      const qtyClass = isIn ? 'text-emerald-500' : 'text-red-500';
      const qtyPrefix = isIn ? '+' : '-';
      const dateStr = txn.transaction_date ? new Date(txn.transaction_date).toLocaleDateString('en-US', {month:'short', day:'2-digit', year:'numeric'}) : '-';
      return `
      <tr class="transaction-row">
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex px-2 py-1">
            <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
              <i class="fas fa-box"></i>
            </div>
            <div class="flex flex-col justify-center">
              <h6 class="mb-0 text-sm leading-normal dark:text-white">${txn.product_name}</h6>
              <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400">${txn.product_code}</p>
            </div>
          </div>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${txn.transaction_type.replace(/_/g, ' ')}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${txn.location_name || '-'}</p>
          <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400">${txn.property_name || '-'}</p>
        </td>
        <td class="p-2 text-right align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-sm font-bold leading-tight ${qtyClass} dark:text-white">${qtyPrefix}${parseFloat(txn.quantity).toFixed(3)} ${txn.uom_name}</p>
        </td>
        <td class="p-2 text-right align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-sm font-bold leading-tight dark:text-white">${parseFloat(txn.stock_after).toFixed(3)}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${dateStr}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex items-center gap-2">
            <button onclick="viewTransactionDetails('${txn.txn_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
              <i class="fas fa-info-circle mr-1.5 text-xs"></i><span>View</span>
            </button>
            <button onclick="openReverseTransactionModal('${txn.txn_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-orange-600 dark:text-orange-400 bg-white dark:bg-slate-700/50 border border-orange-200 dark:border-white/10 rounded-lg hover:bg-orange-50 dark:hover:bg-orange-500/20 hover:text-orange-700 dark:hover:text-orange-300 hover:border-orange-300 dark:hover:border-orange-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Reverse Entry">
              <i class="fas fa-undo mr-1.5 text-xs"></i><span>Reverse</span>
            </button>
          </div>
        </td>
      </tr>`;
    }).join('');
  }

  function populateDropdowns() {
    const productSel = document.getElementById('txnProductSelect');
    const locationSel = document.getElementById('txnLocationSelect');
    const uomSel = document.getElementById('txnUomSelect');
    productSel.innerHTML = '<option value="">Select Product</option>' + productsData.map(p => `<option value="${p.product_id}">${p.product_name} (${p.product_code})</option>`).join('');
    locationSel.innerHTML = '<option value="">Select Location</option>' + locationsData.map(l => `<option value="${l.location_id}">${l.property_name} - ${l.location_name}</option>`).join('');
    uomSel.innerHTML = '<option value="">Select UOM</option>' + uomsData.map(u => `<option value="${u.uom_id}">${u.uom_name} (${u.uom_type})</option>`).join('');
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchTransactions();
    fetchRelated();
  });
</script>

<!-- PAGE CONTENT -->

      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Transactions</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statTotal">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Stock In</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-emerald-500" id="statStockIn">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Stock Out</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-red-500" id="statStockOut">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Transfers</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-blue-500" id="statTransfers">...</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Transactions Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Stock Transactions (Last 100)</h6>
              <button onclick="openAddTransactionModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Manual Entry
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="transactionsTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Product</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Location</th>
                      <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Qty</th>
                      <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Stock After</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Date</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="transactionsTableBody">
                    <tr><td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- View Transaction Details Modal -->
      <div id="transactionDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeTransactionDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white" id="transactionModalTitle">Transaction Details</h3>
            <button onclick="closeTransactionDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6" id="transactionModalContent">
            <!-- Content will be populated by JavaScript -->
          </div>
        </div>
      </div>

      <!-- Add Transaction Modal -->
      <div id="addTransactionModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddTransactionModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Manual Stock Entry</h3>
            <button onclick="closeAddTransactionModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addTransactionForm" onsubmit="submitAddTransaction(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Product <span class="text-red-500">*</span></label>
                  <select name="product_id" id="txnProductSelect" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Product</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Location <span class="text-red-500">*</span></label>
                  <select name="location_id" id="txnLocationSelect" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Location</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Transaction Type <span class="text-red-500">*</span></label>
                  <select name="transaction_type" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="stock_in_manual">Stock In (Manual)</option>
                    <option value="adjustment_add">Adjustment - Add</option>
                    <option value="adjustment_deduct">Adjustment - Deduct</option>
                    <option value="stock_out_damage">Stock Out - Damage</option>
                    <option value="stock_out_use">Stock Out - Use</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">UOM <span class="text-red-500">*</span></label>
                  <select name="uom_id" id="txnUomSelect" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select UOM</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Quantity <span class="text-red-500">*</span></label>
                  <input type="number" step="0.001" name="quantity" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter quantity">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Transaction Date <span class="text-red-500">*</span></label>
                  <input type="date" name="transaction_date" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Reference Type</label>
                  <select name="reference_type" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="MANUAL">Manual Entry</option>
                    <option value="ADJUSTMENT">Adjustment</option>
                    <option value="DAMAGE">Damage Report</option>
                    <option value="OTHER">Other</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Reference ID</label>
                  <input type="text" name="reference_id" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Reference ID (optional)">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Additional notes..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeAddTransactionModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Save Transaction
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Reverse Transaction Modal -->
      <div id="reverseTransactionModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeReverseTransactionModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 600px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Reverse Stock Transaction</h3>
            <button onclick="closeReverseTransactionModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="reverseTransactionForm" onsubmit="submitReverseTransaction(event)">
            <div class="p-6">
              <div class="space-y-4">
                <input type="hidden" name="txn_id" id="reverseTxnId">
                <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                  <p class="text-sm text-orange-800 dark:text-orange-200">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Warning:</strong> This will create a reverse transaction to undo the selected transaction. The original transaction will remain in the audit trail.
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Original Transaction</label>
                  <div id="reverseTxnInfo" class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3 text-sm dark:text-white">
                    <!-- Will be populated by JavaScript -->
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Reason for Reversal <span class="text-red-500">*</span></label>
                  <textarea name="notes" rows="3" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Explain why this transaction needs to be reversed..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeReverseTransactionModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-orange-500 to-red-500 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-undo mr-2"></i>Reverse Transaction
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
    const table = document.getElementById('transactionsTable');
    const rows = table.getElementsByClassName('transaction-row');

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

  // View Transaction Details
  function viewTransactionDetails(txnId) {
    const txn = transactionsData.find(t => t.txn_id === txnId);
    if (!txn) return;

    const modal = document.getElementById('transactionDetailsModal');
    const modalTitle = document.getElementById('transactionModalTitle');
    const modalContent = document.getElementById('transactionModalContent');

    modalTitle.textContent = `Transaction: ${txn.product_name}`;

    const isStockIn = in_array(txn.transaction_type, ['stock_in', 'stock_in_manual', 'adjustment_add']);

    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Product Information</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Product Name</p>
                <p class="text-sm dark:text-white">${txn.product_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Product Code</p>
                <p class="text-sm dark:text-white">${txn.product_code}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Location Information</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Property</p>
                <p class="text-sm dark:text-white">${txn.property_name || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Location</p>
                <p class="text-sm dark:text-white">${txn.location_name || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Location Type</p>
                <p class="text-sm dark:text-white">${txn.location_type ? txn.location_type.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) : '-'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Transaction Details</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Transaction Type</p>
                <p class="text-sm dark:text-white font-semibold">${txn.transaction_type.replace(/_/g, ' ')}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Quantity</p>
                <p class="text-2xl font-bold ${isStockIn ? 'text-emerald-500' : 'text-red-500'} dark:text-white">${isStockIn ? '+' : '-'}${parseFloat(txn.quantity).toFixed(3)} ${txn.uom_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Stock Before</p>
                <p class="text-sm dark:text-white">${parseFloat(txn.stock_before).toFixed(3)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Stock After</p>
                <p class="text-sm dark:text-white font-bold">${parseFloat(txn.stock_after).toFixed(3)}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Reference & Notes</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Reference Type</p>
                <p class="text-sm dark:text-white">${txn.reference_type || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Reference ID</p>
                <p class="text-sm dark:text-white">${txn.reference_id || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Transaction Date</p>
                <p class="text-sm dark:text-white">${new Date(txn.transaction_date).toLocaleString()}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Notes</p>
                <p class="text-sm dark:text-white">${txn.notes || '-'}</p>
              </div>
            </div>
          </div>
        </div>
      `;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeTransactionDetailsModal() {
    const modal = document.getElementById('transactionDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  // Add Transaction Modal Functions
  function openAddTransactionModal() {
    const modal = document.getElementById('addTransactionModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeAddTransactionModal() {
    const modal = document.getElementById('addTransactionModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('addTransactionForm').reset();
  }

  function submitAddTransaction(event) {
    event.preventDefault();

    Swal.fire({
      title: 'Processing...',
      text: 'Please wait while we process the stock transaction.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

    const form = document.getElementById('addTransactionForm');
    const payload = {
      product_id: form.elements['product_id'].value,
      location_id: form.elements['location_id'].value,
      transaction_type: form.elements['transaction_type'].value,
      uom_id: form.elements['uom_id'].value,
      quantity: form.elements['quantity'].value,
      transaction_date: form.elements['transaction_date'].value,
      reference_type: form.elements['reference_type'].value,
      reference_id: form.elements['reference_id'].value,
      notes: form.elements['notes'].value
    };

    fetch('../api/stock_transactions.php?action=create', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddTransactionModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchTransactions());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeAddTransactionModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while processing the stock transaction.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  // Reverse Transaction Modal Functions
  function openReverseTransactionModal(txnId) {
    const txn = transactionsData.find(t => t.txn_id === txnId);
    if (!txn) return;

    document.getElementById('reverseTxnId').value = txn.txn_id;

    const modalInfo = document.getElementById('reverseTxnInfo');
    const isStockIn = in_array(txn.transaction_type, ['stock_in', 'stock_in_manual', 'adjustment_add', 'transfer_in']);

    modalInfo.innerHTML = `
        <div class="space-y-2">
          <div><strong>Product:</strong> ${txn.product_name} (${txn.product_code})</div>
          <div><strong>Type:</strong> ${txn.transaction_type.replace(/_/g, ' ')}</div>
          <div><strong>Quantity:</strong> ${isStockIn ? '+' : '-'}${parseFloat(txn.quantity).toFixed(3)} ${txn.uom_name}</div>
          <div><strong>Date:</strong> ${new Date(txn.transaction_date).toLocaleDateString()}</div>
        </div>
      `;

    const modal = document.getElementById('reverseTransactionModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeReverseTransactionModal() {
    const modal = document.getElementById('reverseTransactionModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('reverseTransactionForm').reset();
  }

  function submitReverseTransaction(event) {
    event.preventDefault();

    Swal.fire({
      title: 'Reversing...',
      text: 'Please wait while we reverse the stock transaction.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

    const txnId = document.getElementById('reverseTxnId').value;
    const notes = document.getElementById('reverseTransactionForm').elements['notes'].value;
    const originalTxn = transactionsData.find(t => t.txn_id === txnId);
    if (!originalTxn) return;

    const reverseType = ['stock_in', 'stock_in_manual', 'adjustment_add', 'transfer_in'].includes(originalTxn.transaction_type) ? 'adjustment_deduct' : 'adjustment_add';
    const payload = {
      product_id: originalTxn.product_id,
      location_id: originalTxn.location_id,
      uom_id: originalTxn.uom_id,
      transaction_type: reverseType,
      quantity: originalTxn.quantity,
      transaction_date: new Date().toISOString().split('T')[0],
      reference_type: 'REVERSAL',
      reference_id: txnId,
      notes: notes
    };

    fetch('../api/stock_transactions.php?action=create', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeReverseTransactionModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Reversed!', text: 'Reverse transaction created.', confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchTransactions());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeReverseTransactionModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while reversing the stock transaction.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  // Helper function for in_array
  function in_array(needle, haystack) {
    return haystack.indexOf(needle) !== -1;
  }
</script>