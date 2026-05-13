<?php if (false) {} ?>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    loadInventoryValuation();
    loadStockMovements();
  });

  async function loadInventoryValuation() {
    try {
      const res = await fetch('../api/reports.php?action=inventory_valuation');
      const json = await res.json();
      
      if (json.success) {
        renderValuationTable(json.data);
        const total = json.data.reduce((sum, row) => sum + parseFloat(row.total_value || 0), 0);
        document.getElementById('totalValue').textContent = '₱' + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
      }
    } catch (e) {
      console.error('Error:', e);
    }
  }

  async function loadStockMovements() {
    try {
      const res = await fetch('../api/reports.php?action=stock_movements&days=30');
      const json = await res.json();
      
      if (json.success) {
        renderMovementsTable(json.data);
      }
    } catch (e) {
      console.error('Error:', e);
    }
  }

  function renderValuationTable(data) {
    const tbody = document.getElementById('valuationTableBody');
    tbody.innerHTML = data.map(row => `
      <tr>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40">${row.category_name}</td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 text-center">${row.product_count || 0}</td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 text-right font-semibold text-emerald-500">₱${parseFloat(row.total_value || 0).toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
      </tr>
    `).join('');
  }

  function renderMovementsTable(data) {
    const tbody = document.getElementById('movementsTableBody');
    tbody.innerHTML = data.map(row => `
      <tr>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40">${new Date(row.date).toLocaleDateString()}</td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40"><span class="px-2 py-1 text-xs rounded-full ${row.transaction_type === 'in' ? 'bg-emerald-100 text-emerald-700' : row.transaction_type === 'out' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'}">${row.transaction_type}</span></td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 text-center">${row.count}</td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 text-center">${row.total_quantity}</td>
      </tr>
    `).join('');
  }
</script>

<div class="flex flex-wrap -mx-3 mb-6">
  <div class="w-full max-w-full px-3 sm:w-1/2 lg:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 rounded-2xl">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-2/3 max-w-full px-3">
            <p class="mb-0 text-sm font-semibold uppercase dark:text-white/60">Total Inventory Value</p>
            <h5 class="mb-2 font-bold dark:text-white" id="totalValue">$0.00</h5>
          </div>
          <div class="px-3 text-right basis-1/3">
            <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-orange-500 to-yellow-500">
              <i class="ni ni-box-2 text-lg relative top-3.5 text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="flex flex-wrap -mx-3">
  <div class="w-full max-w-full px-3 lg:w-1/2">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 rounded-2xl">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <h6 class="mb-0 dark:text-white">Inventory Valuation by Category</h6>
      </div>
      <div class="p-0 overflow-x-auto">
        <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
          <thead>
            <tr>
              <th class="px-6 py-3 font-bold text-left uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Category</th>
              <th class="px-6 py-3 font-bold text-center uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Products</th>
              <th class="px-6 py-3 font-bold text-right uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Value</th>
            </tr>
          </thead>
          <tbody id="valuationTableBody">
            <tr><td colspan="3" class="p-4 text-center text-slate-400">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="w-full max-w-full px-3 lg:w-1/2">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 rounded-2xl">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <h6 class="mb-0 dark:text-white">Stock Movements (Last 30 Days)</h6>
      </div>
      <div class="p-0 overflow-x-auto">
        <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
          <thead>
            <tr>
              <th class="px-6 py-3 font-bold text-left uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Date</th>
              <th class="px-6 py-3 font-bold text-center uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Type</th>
              <th class="px-6 py-3 font-bold text-center uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Transactions</th>
              <th class="px-6 py-3 font-bold text-center uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Quantity</th>
            </tr>
          </thead>
          <tbody id="movementsTableBody">
            <tr><td colspan="4" class="p-4 text-center text-slate-400">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
