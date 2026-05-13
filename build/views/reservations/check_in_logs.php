<!-- PAGE CONTENT -->

<div class="flex flex-wrap -mx-3">
  <div class="flex-none w-full max-w-full px-3">
    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
        <h6 class="dark:text-white">Check-in/Check-out Logs</h6>
      </div>
      <div class="flex-auto px-0 pt-0 pb-2">
        <div class="p-0 overflow-x-auto">
          <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="checkInLogsTable">
            <thead class="align-bottom">
              <tr>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reservation</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Room</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Event Type</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Event At</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Processed By</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Notes</th>
              </tr>
            </thead>
            <tbody id="checkInLogsTableBody">
              <tr><td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  let checkInLogsData = [];

  document.addEventListener('DOMContentLoaded', function() {
    fetchCheckInLogsData();
  });

  async function fetchCheckInLogsData() {
    try {
      const response = await fetch('../api/check_in_logs.php?action=list');
      const result = await response.json();
      if (result.success) {
        checkInLogsData = result.data;
        renderCheckInLogsTable();
      } else {
        document.getElementById('checkInLogsTableBody').innerHTML = `<tr><td colspan="7" class="p-4 text-center text-red-400">${result.message}</td></tr>`;
      }
    } catch (error) {
      console.error('Error fetching check-in logs:', error);
      document.getElementById('checkInLogsTableBody').innerHTML = `<tr><td colspan="7" class="p-4 text-center text-red-400">Failed to load data</td></tr>`;
    }
  }

  function formatDateTime(dtStr) {
    if (!dtStr) return '-';
    const d = new Date(dtStr);
    return d.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) + ' ' +
      d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
  }

  function renderCheckInLogsTable() {
    const tbody = document.getElementById('checkInLogsTableBody');
    if (!tbody) return;

    if (checkInLogsData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">No check-in logs found</td></tr>`;
      return;
    }

    const eventMap = {
      'check_in': 'bg-green-100 text-green-600',
      'check_out': 'bg-blue-100 text-blue-600',
      'early_check_in': 'bg-purple-100 text-purple-600',
      'late_check_out': 'bg-orange-100 text-orange-600',
      'room_transfer': 'bg-cyan-100 text-cyan-600',
      'extension': 'bg-pink-100 text-pink-600'
    };

    tbody.innerHTML = checkInLogsData.map(log => {
      const eventCls = eventMap[log.event_type] || 'bg-gray-100 text-gray-600';
      const room = log.room_number ? `${log.room_number}${log.room_name ? ' - ' + log.room_name : ''}` : '-';
      const processedBy = log.processed_by ? 'Staff ID: ' + log.processed_by.substring(0, 8) : '-';
      return `
        <tr class="checkin-row">
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${log.reservation_code || '-'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${log.property_name || '-'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 text-xs leading-tight dark:text-white">${room}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <span class="text-xs font-semibold ${eventCls} px-2 py-1 rounded capitalize">${(log.event_type || '-').replace(/_/g, ' ')}</span>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 text-xs leading-tight dark:text-white">${formatDateTime(log.event_at)}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 text-xs leading-tight dark:text-white">${processedBy}</p>
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
    document.querySelectorAll('#checkInLogsTable .checkin-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }
</script>