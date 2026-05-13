<!-- PAGE CONTENT -->

<div class="flex flex-wrap -mx-3">
  <div class="flex-none w-full max-w-full px-3">
    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
        <h6 class="dark:text-white">Entrance/Exit Logs</h6>
      </div>
      <div class="flex-auto px-0 pt-0 pb-2">
        <div class="p-0 overflow-x-auto">
          <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="entranceLogsTable">
            <thead class="align-bottom">
              <tr>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reservation</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Visitor Name</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Visitor Type</th>
                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Entry Type</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Event At</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">ID Presented</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Logged By</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Notes</th>
              </tr>
            </thead>
            <tbody id="entranceLogsTableBody">
              <tr><td colspan="9" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  let entranceLogsData = [];

  document.addEventListener('DOMContentLoaded', function() {
    fetchEntranceLogsData();
  });

  async function fetchEntranceLogsData() {
    try {
      const response = await fetch('../api/entrance_logs.php?action=list');
      const result = await response.json();
      if (result.success) {
        entranceLogsData = result.data;
        renderEntranceLogsTable();
      } else {
        document.getElementById('entranceLogsTableBody').innerHTML = `<tr><td colspan="9" class="p-4 text-center text-red-400">${result.message}</td></tr>`;
      }
    } catch (error) {
      console.error('Error fetching entrance logs:', error);
      document.getElementById('entranceLogsTableBody').innerHTML = `<tr><td colspan="9" class="p-4 text-center text-red-400">Failed to load data</td></tr>`;
    }
  }

  function formatDateTime(dtStr) {
    if (!dtStr) return '-';
    const d = new Date(dtStr);
    return d.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) + ' ' +
      d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
  }

  function renderEntranceLogsTable() {
    const tbody = document.getElementById('entranceLogsTableBody');
    if (!tbody) return;

    if (entranceLogsData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="9" class="p-4 text-center text-slate-400 dark:text-white/60">No entrance logs found</td></tr>`;
      return;
    }

    const typeMap = {
      'adult': 'bg-blue-100 text-blue-600',
      'child': 'bg-green-100 text-green-600',
      'senior': 'bg-orange-100 text-orange-600',
      'infant': 'bg-pink-100 text-pink-600',
      'visitor': 'bg-purple-100 text-purple-600',
      'staff': 'bg-gray-100 text-gray-600',
      'delivery': 'bg-cyan-100 text-cyan-600',
      'vendor': 'bg-indigo-100 text-indigo-600'
    };

    tbody.innerHTML = entranceLogsData.map(log => {
      const typeCls = typeMap[log.visitor_type] || 'bg-gray-100 text-gray-600';
      const entryCls = log.entry_type === 'entry' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600';
      const entryLabel = log.entry_type === 'entry' ? 'Entry' : 'Exit';
      const visitorName = log.visitor_name || (log.first_name ? `${log.first_name} ${log.last_name}` : '-');
      const reservation = log.reservation_code || '<span class="text-slate-400">Walk-in</span>';
      const loggedBy = log.logged_by ? 'Staff ID: ' + log.logged_by.substring(0, 8) : '-';
      return `
        <tr class="entrance-row">
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${log.property_name || '-'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${reservation}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${visitorName}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <span class="text-xs font-semibold ${typeCls} px-2 py-1 rounded capitalize">${log.visitor_type || '-'}</span>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-center">
            <span class="text-xs font-semibold ${entryCls} px-2 py-1 rounded">${entryLabel}</span>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 text-xs leading-tight dark:text-white">${formatDateTime(log.event_at)}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 text-xs leading-tight dark:text-white">${log.id_presented || '-'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 text-xs leading-tight dark:text-white">${loggedBy}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 text-xs leading-tight dark:text-white">${log.notes || '-'}</p>
          </td>
        </tr>
      `;
    }).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    document.querySelectorAll('#entranceLogsTable .entrance-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }
</script>