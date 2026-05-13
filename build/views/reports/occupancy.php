<?php if (false) {} ?>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    loadOccupancyData();
    loadRoomTypeOccupancy();
  });

  async function loadOccupancyData() {
    try {
      const res = await fetch('../api/reports.php?action=occupancy_daily');
      const json = await res.json();
      
      if (json.success) {
        renderOccupancyTable(json.data);
      }
    } catch (e) {
      console.error('Error:', e);
    }
  }

  async function loadRoomTypeOccupancy() {
    try {
      const res = await fetch('../api/reports.php?action=occupancy_by_room_type');
      const json = await res.json();
      
      if (json.success) {
        renderRoomTypeTable(json.data);
      }
    } catch (e) {
      console.error('Error:', e);
    }
  }

  function renderOccupancyTable(data) {
    const tbody = document.getElementById('occupancyTableBody');
    tbody.innerHTML = data.map(row => `
      <tr>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40">${new Date(row.date).toLocaleDateString()}</td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 text-center">${row.check_ins || 0}</td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 text-center">${row.check_outs || 0}</td>
      </tr>
    `).join('');
  }

  function renderRoomTypeTable(data) {
    const tbody = document.getElementById('roomTypeTableBody');
    tbody.innerHTML = data.map(row => {
      const pct = row.total_rooms > 0 ? Math.round((row.occupied / row.total_rooms) * 100) : 0;
      return `
        <tr>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40">${row.category_name}</td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 text-center">${row.total_rooms}</td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 text-center">${row.occupied || 0}</td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 text-center">
            <span class="px-2 py-1 text-xs rounded-full ${pct > 80 ? 'bg-emerald-100 text-emerald-700' : pct > 50 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700'}">${pct}%</span>
          </td>
        </tr>
      `;
    }).join('');
  }
</script>

<div class="flex flex-wrap -mx-3 mb-6">
  <div class="w-full max-w-full px-3">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 rounded-2xl">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <h6 class="mb-0 dark:text-white">Occupancy Reports</h6>
      </div>
      <div class="p-4">
        <p class="text-slate-500">Occupancy analytics and room utilization statistics.</p>
      </div>
    </div>
  </div>
</div>

<div class="flex flex-wrap -mx-3">
  <div class="w-full max-w-full px-3 lg:w-1/2">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 rounded-2xl">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <h6 class="mb-0 dark:text-white">Daily Check-ins/Check-outs</h6>
      </div>
      <div class="p-0 overflow-x-auto">
        <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
          <thead>
            <tr>
              <th class="px-6 py-3 font-bold text-left uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Date</th>
              <th class="px-6 py-3 font-bold text-center uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Check-ins</th>
              <th class="px-6 py-3 font-bold text-center uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Check-outs</th>
            </tr>
          </thead>
          <tbody id="occupancyTableBody">
            <tr><td colspan="3" class="p-4 text-center text-slate-400">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="w-full max-w-full px-3 lg:w-1/2">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 rounded-2xl">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <h6 class="mb-0 dark:text-white">Occupancy by Room Type</h6>
      </div>
      <div class="p-0 overflow-x-auto">
        <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
          <thead>
            <tr>
              <th class="px-6 py-3 font-bold text-left uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Room Type</th>
              <th class="px-6 py-3 font-bold text-center uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Total</th>
              <th class="px-6 py-3 font-bold text-center uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Occupied</th>
              <th class="px-6 py-3 font-bold text-center uppercase text-xxs border-b dark:border-slate-600 text-slate-400">Rate</th>
            </tr>
          </thead>
          <tbody id="roomTypeTableBody">
            <tr><td colspan="4" class="p-4 text-center text-slate-400">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
