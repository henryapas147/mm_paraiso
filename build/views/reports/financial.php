<?php if (false) {} ?>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    loadFinancialSummary();
  });

  async function loadFinancialSummary() {
    try {
      // This would fetch P&L, balance sheet data
      document.getElementById('financialContent').innerHTML = `
        <div class="p-8 text-center">
          <i class="ni ni-paper-diploma text-6xl text-slate-300 mb-4"></i>
          <h6 class="text-lg font-semibold text-slate-500 mb-2">Financial Reports</h6>
          <p class="text-slate-400 max-w-md mx-auto">
            Comprehensive financial reports including Profit & Loss statements, 
            Balance Sheets, and Cash Flow analysis will be implemented here.
          </p>
          <div class="mt-6 flex justify-center gap-4">
            <div class="text-center px-4 py-3 bg-slate-50 dark:bg-slate-700 rounded-lg">
              <p class="text-xs text-slate-500 dark:text-slate-300">Revenue (YTD)</p>
              <p class="text-lg font-bold text-emerald-500">₱0.00</p>
            </div>
            <div class="text-center px-4 py-3 bg-slate-50 dark:bg-slate-700 rounded-lg">
              <p class="text-xs text-slate-500 dark:text-slate-300">Expenses (YTD)</p>
              <p class="text-lg font-bold text-red-500">₱0.00</p>
            </div>
            <div class="text-center px-4 py-3 bg-slate-50 dark:bg-slate-700 rounded-lg">
              <p class="text-xs text-slate-500 dark:text-slate-300">Net Profit</p>
              <p class="text-lg font-bold text-blue-500">₱0.00</p>
            </div>
          </div>
        </div>
      `;
    } catch (e) {
      console.error('Error:', e);
    }
  }
</script>

<div class="flex flex-wrap -mx-3 mb-6">
  <div class="w-full max-w-full px-3">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 rounded-2xl">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl border-b border-slate-200 dark:border-slate-700">
        <div class="flex items-center justify-between">
          <h6 class="mb-0 dark:text-white">Financial Reports</h6>
          <div class="flex gap-2">
            <button class="px-3 py-1.5 text-xs font-semibold text-blue-500 bg-white border border-blue-500 rounded-lg hover:bg-blue-50">
              P&L Statement
            </button>
            <button class="px-3 py-1.5 text-xs font-semibold text-slate-500 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
              Balance Sheet
            </button>
            <button class="px-3 py-1.5 text-xs font-semibold text-slate-500 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
              Cash Flow
            </button>
          </div>
        </div>
      </div>
      <div class="p-0" id="financialContent">
        <div class="p-8 text-center">
          <p class="text-slate-400">Loading financial data...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="flex flex-wrap -mx-3">
  <div class="w-full max-w-full px-3 lg:w-1/3">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 rounded-2xl">
      <div class="p-4">
        <h6 class="mb-4 text-sm font-semibold dark:text-white">Report Types</h6>
        <ul class="space-y-2">
          <li class="flex items-center p-3 rounded-lg bg-blue-50 dark:bg-slate-700">
            <i class="ni ni-money-coins text-blue-500 mr-3"></i>
            <div>
              <p class="text-sm font-semibold dark:text-white">Profit & Loss</p>
              <p class="text-xs text-slate-500">Income vs Expenses</p>
            </div>
          </li>
          <li class="flex items-center p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer">
            <i class="ni ni-balance text-slate-500 mr-3"></i>
            <div>
              <p class="text-sm font-semibold dark:text-white">Balance Sheet</p>
              <p class="text-xs text-slate-500">Assets, Liabilities, Equity</p>
            </div>
          </li>
          <li class="flex items-center p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer">
            <i class="ni ni-chart-bar-32 text-slate-500 mr-3"></i>
            <div>
              <p class="text-sm font-semibold dark:text-white">Cash Flow</p>
              <p class="text-xs text-slate-500">Operating, Investing, Financing</p>
            </div>
          </li>
          <li class="flex items-center p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer">
            <i class="ni ni-chart-pie-35 text-slate-500 mr-3"></i>
            <div>
              <p class="text-sm font-semibold dark:text-white">Tax Reports</p>
              <p class="text-xs text-slate-500">VAT, Sales Tax summaries</p>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="w-full max-w-full px-3 lg:w-2/3">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 rounded-2xl">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <h6 class="mb-0 dark:text-white">Period Selection</h6>
      </div>
      <div class="p-4">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
          <button class="p-4 text-center border border-slate-200 dark:border-slate-600 rounded-xl hover:bg-blue-50 dark:hover:bg-slate-700 hover:border-blue-300 transition-all">
            <p class="text-sm font-semibold dark:text-white">This Month</p>
            <p class="text-xs text-slate-500">Oct 2026</p>
          </button>
          <button class="p-4 text-center border border-slate-200 dark:border-slate-600 rounded-xl hover:bg-blue-50 dark:hover:bg-slate-700 hover:border-blue-300 transition-all">
            <p class="text-sm font-semibold dark:text-white">Last Month</p>
            <p class="text-xs text-slate-500">Sep 2026</p>
          </button>
          <button class="p-4 text-center border border-slate-200 dark:border-slate-600 rounded-xl hover:bg-blue-50 dark:hover:bg-slate-700 hover:border-blue-300 transition-all">
            <p class="text-sm font-semibold dark:text-white">This Quarter</p>
            <p class="text-xs text-slate-500">Q4 2026</p>
          </button>
          <button class="p-4 text-center border border-slate-200 dark:border-slate-600 rounded-xl hover:bg-blue-50 dark:hover:bg-slate-700 hover:border-blue-300 transition-all">
            <p class="text-sm font-semibold dark:text-white">This Year</p>
            <p class="text-xs text-slate-500">2026</p>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
