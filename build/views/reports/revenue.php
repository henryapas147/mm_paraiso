<?php if (false) {} ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  let revenueChart;

  document.addEventListener('DOMContentLoaded', () => {
    loadRevenueData(30);
    loadRevenueBySource();
    loadMonthlyRevenue();
  });

  async function loadRevenueData(days) {
    try {
      const res = await fetch(`../api/reports.php?action=revenue_daily&days=${days}`);
      const json = await res.json();
      
      if (json.success && json.data.length > 0) {
        renderRevenueChart(json.data);
        renderRevenueTable(json.data);
        
        // Calculate totals
        const total = json.data.reduce((sum, row) => sum + parseFloat(row.revenue), 0);
        const transactions = json.data.reduce((sum, row) => sum + parseInt(row.transactions), 0);
        document.getElementById('totalRevenue').textContent = '₱' + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('totalTransactions').textContent = transactions.toLocaleString();
      } else {
        document.getElementById('revenueTableBody').innerHTML = '<tr><td colspan="4" class="p-4 text-center text-slate-400">No data available</td></tr>';
      }
    } catch (e) {
      console.error('Error loading revenue data:', e);
    }
  }

  async function loadRevenueBySource() {
    try {
      const res = await fetch('../api/reports.php?action=revenue_by_source');
      const json = await res.json();
      
      if (json.success) {
        renderSourceChart(json.data);
      }
    } catch (e) {
      console.error('Error loading source data:', e);
    }
  }

  async function loadMonthlyRevenue() {
    try {
      const res = await fetch('../api/reports.php?action=revenue_monthly');
      const json = await res.json();
      
      if (json.success) {
        renderMonthlyTable(json.data);
      }
    } catch (e) {
      console.error('Error loading monthly data:', e);
    }
  }

  function renderRevenueChart(data) {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    if (revenueChart) {
      revenueChart.destroy();
    }

    const labels = data.map(row => new Date(row.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
    const revenues = data.map(row => parseFloat(row.revenue));

    revenueChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels.reverse(),
        datasets: [{
          label: 'Revenue',
          data: revenues.reverse(),
          borderColor: '#10b981',
          backgroundColor: 'rgba(16, 185, 129, 0.1)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return '$' + value.toLocaleString();
              }
            }
          }
        }
      }
    });
  }

  function renderSourceChart(data) {
    const ctx = document.getElementById('sourceChart').getContext('2d');
    
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: data.map(row => row.source_channel || 'Unknown'),
        datasets: [{
          data: data.map(row => parseFloat(row.revenue)),
          backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  }

  function renderRevenueTable(data) {
    const tbody = document.getElementById('revenueTableBody');
    tbody.innerHTML = data.map(row => `
      <tr>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${new Date(row.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-right">
          <p class="mb-0 text-xs font-semibold leading-tight text-emerald-500">₱${parseFloat(row.revenue).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-center">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${row.transactions}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-right">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">₱${(parseFloat(row.revenue) / parseInt(row.transactions)).toFixed(2)}</p>
        </td>
      </tr>
    `).join('');
  }

  function renderMonthlyTable(data) {
    const tbody = document.getElementById('monthlyTableBody');
    tbody.innerHTML = data.map(row => `
      <tr>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${row.month}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-right">
          <p class="mb-0 text-xs font-semibold leading-tight text-emerald-500">₱${parseFloat(row.revenue).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-center">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${row.transactions}</p>
        </td>
      </tr>
    `).join('');
  }

  function changePeriod(days) {
    loadRevenueData(days);
  }

  function exportReport() {
    Swal.fire({
      title: 'Export Revenue Report',
      text: 'Export functionality will generate CSV/PDF',
      icon: 'info',
      confirmButtonText: 'OK'
    });
  }
</script>

<!-- PAGE CONTENT -->
<div class="flex flex-wrap -mx-3 mb-6">
  <!-- Summary Cards -->
  <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 xl:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-2/3 max-w-full px-3">
            <div>
              <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Total Revenue</p>
              <h5 class="mb-2 font-bold dark:text-white" id="totalRevenue">$0.00</h5>
            </div>
          </div>
          <div class="px-3 text-right basis-1/3">
            <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-emerald-500 to-teal-400">
              <i class="ni leading-none ni-money-coins text-lg relative top-3.5 text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 xl:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-2/3 max-w-full px-3">
            <div>
              <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Transactions</p>
              <h5 class="mb-2 font-bold dark:text-white" id="totalTransactions">0</h5>
            </div>
          </div>
          <div class="px-3 text-right basis-1/3">
            <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-blue-500 to-violet-500">
              <i class="ni leading-none ni-credit-card text-lg relative top-3.5 text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 xl:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div>
              <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Period</p>
              <div class="flex gap-2 mt-2">
                <button onclick="changePeriod(7)" class="px-3 py-1 text-xs font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600">7 Days</button>
                <button onclick="changePeriod(30)" class="px-3 py-1 text-xs font-semibold text-blue-500 bg-white border border-blue-500 rounded-lg hover:bg-blue-50">30 Days</button>
                <button onclick="changePeriod(90)" class="px-3 py-1 text-xs font-semibold text-blue-500 bg-white border border-blue-500 rounded-lg hover:bg-blue-50">90 Days</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 xl:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <button onclick="exportReport()" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-purple-500 to-pink-500 rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all duration-200">
              <i class="ni ni-cloud-download-95 mr-2"></i>Export Report
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Charts Row -->
<div class="flex flex-wrap -mx-3 mb-6">
  <div class="w-full max-w-full px-3 lg:w-2/3">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <h6 class="mb-0 dark:text-white">Daily Revenue Trend</h6>
      </div>
      <div class="p-4">
        <div style="height: 300px;">
          <canvas id="revenueChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="w-full max-w-full px-3 lg:w-1/3">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <h6 class="mb-0 dark:text-white">Revenue by Source</h6>
      </div>
      <div class="p-4">
        <div style="height: 300px;">
          <canvas id="sourceChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Tables Row -->
<div class="flex flex-wrap -mx-3">
  <div class="w-full max-w-full px-3 lg:w-1/2">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <h6 class="mb-0 dark:text-white">Daily Breakdown</h6>
      </div>
      <div class="p-0 overflow-x-auto">
        <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
          <thead class="align-bottom">
            <tr>
              <th class="px-6 py-3 font-bold text-left uppercase text-xxs border-b border-slate-200 dark:border-slate-600 text-slate-400 opacity-70">Date</th>
              <th class="px-6 py-3 font-bold text-right uppercase text-xxs border-b border-slate-200 dark:border-slate-600 text-slate-400 opacity-70">Revenue</th>
              <th class="px-6 py-3 font-bold text-center uppercase text-xxs border-b border-slate-200 dark:border-slate-600 text-slate-400 opacity-70">Transactions</th>
              <th class="px-6 py-3 font-bold text-right uppercase text-xxs border-b border-slate-200 dark:border-slate-600 text-slate-400 opacity-70">Avg/Trans</th>
            </tr>
          </thead>
          <tbody id="revenueTableBody">
            <tr><td colspan="4" class="p-4 text-center text-slate-400">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="w-full max-w-full px-3 lg:w-1/2">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <h6 class="mb-0 dark:text-white">Monthly Summary</h6>
      </div>
      <div class="p-0 overflow-x-auto">
        <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
          <thead class="align-bottom">
            <tr>
              <th class="px-6 py-3 font-bold text-left uppercase text-xxs border-b border-slate-200 dark:border-slate-600 text-slate-400 opacity-70">Month</th>
              <th class="px-6 py-3 font-bold text-right uppercase text-xxs border-b border-slate-200 dark:border-slate-600 text-slate-400 opacity-70">Revenue</th>
              <th class="px-6 py-3 font-bold text-center uppercase text-xxs border-b border-slate-200 dark:border-slate-600 text-slate-400 opacity-70">Transactions</th>
            </tr>
          </thead>
          <tbody id="monthlyTableBody">
            <tr><td colspan="3" class="p-4 text-center text-slate-400">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
