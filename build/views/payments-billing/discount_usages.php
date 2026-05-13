<!-- PAGE CONTENT -->

      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Usages</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statTotal">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Savings</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statSavings">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Unique Discounts</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statUnique">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Avg Savings</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statAvg">-</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Discount Usages Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Discount Usages</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="discountUsagesTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Discount Code</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reservation</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Amount Saved</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Applied At</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="discountUsagesTableBody">
                    <tr><td colspan="5" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- View Usage Modal -->
      <div id="viewUsageModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeViewUsageModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Discount Usage Details</h3>
            <button onclick="closeViewUsageModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-500">Discount Code</label>
                <p id="view_discount_code" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Discount Name</label>
                <p id="view_discount_name" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Reservation</label>
                <p id="view_reservation" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Amount Saved</label>
                <p id="view_amount_saved" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Applied At</label>
                <p id="view_applied_at" class="mt-1 text-sm text-gray-900"></p>
              </div>
            </div>
          </div>
        </div>
      </div>

<script>
  let discountUsagesData = [];

  document.addEventListener('DOMContentLoaded', function () {
    fetchDiscountUsages();
  });

  async function fetchDiscountUsages() {
    try {
      const res = await fetch('../api/discount_usages.php?action=list');
      const result = await res.json();
      if (result.success) {
        discountUsagesData = result.data;
        renderTable();
        renderStats();
      } else {
        document.getElementById('discountUsagesTableBody').innerHTML = `<tr><td colspan="5" class="p-4 text-center text-red-400">${result.message}</td></tr>`;
      }
    } catch (e) {
      console.error('Error fetching discount usages:', e);
      document.getElementById('discountUsagesTableBody').innerHTML = `<tr><td colspan="5" class="p-4 text-center text-red-400">Failed to load data</td></tr>`;
    }
  }

  function renderStats() {
    const total = discountUsagesData.length;
    const savings = discountUsagesData.reduce((s, u) => s + parseFloat(u.amount_saved || 0), 0);
    const unique = new Set(discountUsagesData.map(u => u.discount_id)).size;
    const avg = total > 0 ? savings / total : 0;
    document.getElementById('statTotal').textContent = total;
    document.getElementById('statSavings').textContent = '₱' + savings.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('statUnique').textContent = unique;
    document.getElementById('statAvg').textContent = '₱' + avg.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function renderTable() {
    const tbody = document.getElementById('discountUsagesTableBody');
    if (!discountUsagesData.length) {
      tbody.innerHTML = `<tr><td colspan="5" class="p-4 text-center text-slate-400 dark:text-white/60">No discount usages found.</td></tr>`;
      return;
    }
    tbody.innerHTML = discountUsagesData.map(u => {
      const appliedAt = u.applied_at ? new Date(u.applied_at).toLocaleString('en-US', { month: 'short', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';
      return `
        <tr class="discount-usage-row">
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <div class="flex px-2 py-1">
              <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-red-500 to-orange-400">
                <i class="fas fa-tag"></i>
              </div>
              <div class="flex flex-col justify-center">
                <h6 class="mb-0 text-sm leading-normal dark:text-white">${u.discount_code || '-'}</h6>
                <p class="mb-0 text-xs leading-tight dark:text-white/60">${u.discount_name || '-'}</p>
              </div>
            </div>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${u.reservation_id ? u.reservation_id.substring(0, 8) + '...' : '-'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">₱${parseFloat(u.amount_saved || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${appliedAt}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <div class="flex items-center gap-2">
              <button onclick="openViewUsageModal('${u.usage_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-info-circle mr-1.5 text-xs"></i><span>View</span>
              </button>
            </div>
          </td>
        </tr>`;
    }).join('');
  }

  function openViewUsageModal(usageId) {
    const usage = discountUsagesData.find(u => u.usage_id === usageId);
    if (usage) {
      document.getElementById('view_discount_code').textContent = usage.discount_code || '-';
      document.getElementById('view_discount_name').textContent = usage.discount_name || '-';
      document.getElementById('view_reservation').textContent = usage.reservation_id || '-';
      document.getElementById('view_amount_saved').textContent = '₱' + parseFloat(usage.amount_saved).toFixed(2);
      document.getElementById('view_applied_at').textContent = usage.applied_at ? new Date(usage.applied_at).toLocaleString() : '-';
      document.getElementById('viewUsageModal').style.display = 'block';
      document.getElementById('viewUsageModal').classList.remove('hidden');
    }
  }

  function closeViewUsageModal() {
    document.getElementById('viewUsageModal').style.display = 'none';
    document.getElementById('viewUsageModal').classList.add('hidden');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    document.querySelectorAll('#discountUsagesTable .discount-usage-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }
</script>