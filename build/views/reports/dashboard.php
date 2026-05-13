<?php
// base_url is passed from Controller
// If not set or empty, calculate it from SCRIPT_NAME
if (!isset($base_url) || empty($base_url)) {
    $script_name = $_SERVER['SCRIPT_NAME'];
    $script_dir = dirname($script_name);
    if (substr($script_dir, -1) != '/') {
        $script_dir .= '/';
    }
    $base_url = $script_dir;
}
?>
<script>
  // Base URL for API calls
  const API_BASE_URL = '<?php echo $base_url; ?>api/reports.php';

  // Reports Dashboard JavaScript
  document.addEventListener('DOMContentLoaded', () => {
    loadDashboardStats();
  });

  async function loadDashboardStats() {
    try {
      // Load key metrics
      const [revenue, occupancy, customers, inventory] = await Promise.all([
        fetch(API_BASE_URL + '?action=revenue_summary').then(r => r.json()),
        fetch(API_BASE_URL + '?action=occupancy_summary').then(r => r.json()),
        fetch(API_BASE_URL + '?action=customer_summary').then(r => r.json()),
        fetch(API_BASE_URL + '?action=inventory_summary').then(r => r.json())
      ]);

      if (revenue.success) {
        document.getElementById('todayRevenue').textContent = '₱' + (revenue.data.today || 0).toLocaleString();
        document.getElementById('monthRevenue').textContent = '₱' + (revenue.data.month || 0).toLocaleString();
      }

      if (occupancy.success) {
        document.getElementById('todayOccupancy').textContent = (occupancy.data.today || 0) + '%';
        document.getElementById('monthOccupancy').textContent = (occupancy.data.month || 0) + '%';
      }

      if (customers.success) {
        document.getElementById('totalCustomers').textContent = (customers.data.total || 0).toLocaleString();
        document.getElementById('newCustomers').textContent = (customers.data.new_this_month || 0);
      }

      if (inventory.success) {
        document.getElementById('lowStockItems').textContent = inventory.data.low_stock || 0;
        document.getElementById('inventoryValue').textContent = '₱' + (inventory.data.total_value || 0).toLocaleString();
      }
    } catch (e) {
      console.error('Error loading dashboard stats:', e);
    }
  }
</script>

<!-- PAGE CONTENT -->
<div class="flex flex-wrap -mx-3 mb-6">
  <!-- Revenue Card -->
  <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-2/3 max-w-full px-3">
            <div>
              <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Today's Revenue</p>
              <h5 class="mb-2 font-bold dark:text-white" id="todayRevenue">$0</h5>
              <p class="mb-0 dark:text-white dark:opacity-60">
                <span class="text-sm font-bold leading-normal text-emerald-500">Month: </span>
                <span id="monthRevenue">$0</span>
              </p>
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

  <!-- Occupancy Card -->
  <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-2/3 max-w-full px-3">
            <div>
              <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Today's Occupancy</p>
              <h5 class="mb-2 font-bold dark:text-white" id="todayOccupancy">0%</h5>
              <p class="mb-0 dark:text-white dark:opacity-60">
                <span class="text-sm font-bold leading-normal text-blue-500">Month Avg: </span>
                <span id="monthOccupancy">0%</span>
              </p>
            </div>
          </div>
          <div class="px-3 text-right basis-1/3">
            <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-blue-500 to-violet-500">
              <i class="ni leading-none ni-building text-lg relative top-3.5 text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Customers Card -->
  <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-2/3 max-w-full px-3">
            <div>
              <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Total Customers</p>
              <h5 class="mb-2 font-bold dark:text-white" id="totalCustomers">0</h5>
              <p class="mb-0 dark:text-white dark:opacity-60">
                <span class="text-sm font-bold leading-normal text-orange-500">New This Month: </span>
                <span id="newCustomers">0</span>
              </p>
            </div>
          </div>
          <div class="px-3 text-right basis-1/3">
            <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-orange-500 to-yellow-500">
              <i class="ni leading-none ni-users text-lg relative top-3.5 text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Inventory Card -->
  <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-2/3 max-w-full px-3">
            <div>
              <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Low Stock Items</p>
              <h5 class="mb-2 font-bold dark:text-white" id="lowStockItems">0</h5>
              <p class="mb-0 dark:text-white dark:opacity-60">
                <span class="text-sm font-bold leading-normal text-red-500">Inventory Value: </span>
                <span id="inventoryValue">$0</span>
              </p>
            </div>
          </div>
          <div class="px-3 text-right basis-1/3">
            <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-red-500 to-orange-500">
              <i class="ni leading-none ni-box-2 text-lg relative top-3.5 text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Quick Links to Reports -->
<div class="flex flex-wrap -mx-3">
  <div class="w-full max-w-full px-3">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <h6 class="mb-0 dark:text-white">Available Reports</h6>
      </div>
      <div class="p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <!-- Revenue Reports -->
          <a href="<?php echo $base_url; ?>reports/revenue" class="flex items-center p-4 bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-slate-700 dark:to-slate-600 rounded-xl hover:shadow-md transition-all duration-200">
            <div class="inline-flex items-center justify-center w-12 h-12 mr-4 text-white bg-gradient-to-tl from-emerald-500 to-teal-400 rounded-xl">
              <i class="ni ni-money-coins text-xl"></i>
            </div>
            <div>
              <h6 class="mb-1 text-sm font-semibold dark:text-white">Revenue Reports</h6>
              <p class="mb-0 text-xs text-slate-500 dark:text-slate-300">Daily, monthly, yearly revenue analysis</p>
            </div>
          </a>

          <!-- Occupancy Reports -->
          <a href="<?php echo $base_url; ?>reports/occupancy" class="flex items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-slate-700 dark:to-slate-600 rounded-xl hover:shadow-md transition-all duration-200">
            <div class="inline-flex items-center justify-center w-12 h-12 mr-4 text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-xl">
              <i class="ni ni-building text-xl"></i>
            </div>
            <div>
              <h6 class="mb-1 text-sm font-semibold dark:text-white">Occupancy Reports</h6>
              <p class="mb-0 text-xs text-slate-500 dark:text-slate-300">Room occupancy & utilization stats</p>
            </div>
          </a>

          <!-- Inventory Reports -->
          <a href="<?php echo $base_url; ?>reports/inventory" class="flex items-center p-4 bg-gradient-to-br from-orange-50 to-amber-50 dark:from-slate-700 dark:to-slate-600 rounded-xl hover:shadow-md transition-all duration-200">
            <div class="inline-flex items-center justify-center w-12 h-12 mr-4 text-white bg-gradient-to-tl from-orange-500 to-yellow-500 rounded-xl">
              <i class="ni ni-box-2 text-xl"></i>
            </div>
            <div>
              <h6 class="mb-1 text-sm font-semibold dark:text-white">Inventory Reports</h6>
              <p class="mb-0 text-xs text-slate-500 dark:text-slate-300">Stock levels, valuations & movements</p>
            </div>
          </a>

          <!-- Customer Analytics -->
          <a href="<?php echo $base_url; ?>reports/customers" class="flex items-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-slate-700 dark:to-slate-600 rounded-xl hover:shadow-md transition-all duration-200">
            <div class="inline-flex items-center justify-center w-12 h-12 mr-4 text-white bg-gradient-to-tl from-purple-500 to-pink-500 rounded-xl">
              <i class="ni ni-chart-pie-35 text-xl"></i>
            </div>
            <div>
              <h6 class="mb-1 text-sm font-semibold dark:text-white">Customer Analytics</h6>
              <p class="mb-0 text-xs text-slate-500 dark:text-slate-300">Customer behavior & demographics</p>
            </div>
          </a>

          <!-- Food & Canteen -->
          <a href="<?php echo $base_url; ?>reports/food-canteen" class="flex items-center p-4 bg-gradient-to-br from-red-50 to-rose-50 dark:from-slate-700 dark:to-slate-600 rounded-xl hover:shadow-md transition-all duration-200">
            <div class="inline-flex items-center justify-center w-12 h-12 mr-4 text-white bg-gradient-to-tl from-red-500 to-rose-400 rounded-xl">
              <i class="ni ni-basket text-xl"></i>
            </div>
            <div>
              <h6 class="mb-1 text-sm font-semibold dark:text-white">Food & Canteen</h6>
              <p class="mb-0 text-xs text-slate-500 dark:text-slate-300">Sales reports & menu performance</p>
            </div>
          </a>

          <!-- Financial Reports -->
          <a href="<?php echo $base_url; ?>reports/financial" class="flex items-center p-4 bg-gradient-to-br from-cyan-50 to-blue-50 dark:from-slate-700 dark:to-slate-600 rounded-xl hover:shadow-md transition-all duration-200">
            <div class="inline-flex items-center justify-center w-12 h-12 mr-4 text-white bg-gradient-to-tl from-cyan-500 to-blue-500 rounded-xl">
              <i class="ni ni-paper-diploma text-xl"></i>
            </div>
            <div>
              <h6 class="mb-1 text-sm font-semibold dark:text-white">Financial Reports</h6>
              <p class="mb-0 text-xs text-slate-500 dark:text-slate-300">P&L, balance sheet & cash flow</p>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Recent Activity -->
<div class="flex flex-wrap -mx-3 mt-6">
  <div class="w-full max-w-full px-3 lg:w-1/2">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <h6 class="mb-0 dark:text-white">Recent Reservations</h6>
      </div>
      <div class="p-4">
        <div id="recentReservations">
          <p class="text-center text-slate-400 dark:text-white/60">Loading...</p>
        </div>
      </div>
    </div>
  </div>

  <div class="w-full max-w-full px-3 lg:w-1/2">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <h6 class="mb-0 dark:text-white">Top Selling Menu Items</h6>
      </div>
      <div class="p-4">
        <div id="topMenuItems">
          <p class="text-center text-slate-400 dark:text-white/60">Loading...</p>
        </div>
      </div>
    </div>
  </div>
</div>
