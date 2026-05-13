<?php if (false) {} ?>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    loadCustomerDemographics();
    loadTopCustomers();
  });

  async function loadCustomerDemographics() {
    try {
      const res = await fetch('../api/reports.php?action=customer_demographics');
      const json = await res.json();
      
      if (json.success) {
        const tbody = document.getElementById('demographicsTableBody');
        tbody.innerHTML = json.data.map(row => `
          <tr>
            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40">${row.city || 'Unknown'}</td>
            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 text-center">${row.count}</td>
          </tr>
        `).join('');
      }
    } catch (e) {
      console.error('Error:', e);
    }
  }

  async function loadTopCustomers() {
    try {
      const res = await fetch('../api/reports.php?action=customer_booking_frequency');
      const json = await res.json();
      
      if (json.success) {
        const tbody = document.getElementById('topCustomersTableBody');
        tbody.innerHTML = json.data.map(row => `
          <tr>
            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40">${row.first_name} ${row.last_name}</td>
            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 text-center">${row.booking_count}</td>
            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 text-right">₱${parseFloat(row.total_spent).toLocaleString()}</td>
          </tr>
        `).join('');
      }
    } catch (e) {
      console.error('Error:', e);
    }
  }
</script>

<div class="flex flex-wrap -mx-3 mb-6">
  <div class="w-full max-w-full px-3">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 rounded-2xl">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <h6 class="mb-0 dark:text-white">Customer Analytics</h6>
      </div>
      <div class="p-4">
        <p class="text-slate-500">Customer demographics, booking frequency, and spending analysis.</p>
      </div>
    </div>
  </div>
</div>

<div class="flex flex-wrap -mx-3">
  <div class="w-full max-w-full px-3 lg:w-1/2">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 rounded-2xl">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <h6 class="mb-0 dark:text-white">Customers by City</h6>
      </div>
      <div class="p-0 overflow-x-auto">
        <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
          <thead>
            <tr>
              <th class="px-6 py-3 font-bold text-left uppercase text-xxs border-b dark:border-slate-600 text-slate-400">City</th>
              <th class="px-6 py-3 font-bold text-center uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Customers</th>
            </tr>
          </thead>
          <tbody id="demographicsTableBody">
            <tr><td colspan="2" class="p-4 text-center text-slate-400">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="w-full max-w-full px-3 lg:w-1/2">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 rounded-2xl">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <h6 class="mb-0 dark:text-white">Top Customers by Bookings</h6>
      </div>
      <div class="p-0 overflow-x-auto">
        <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
          <thead>
            <tr>
              <th class="px-6 py-3 font-bold text-left uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Customer</th>
              <th class="px-6 py-3 font-bold text-center uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Bookings</th>
              <th class="px-6 py-3 font-bold text-right uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Total Spent</th>
            </tr>
          </thead>
          <tbody id="topCustomersTableBody">
            <tr><td colspan="3" class="p-4 text-center text-slate-400">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
