<!-- PAGE CONTENT -->

<div class="flex flex-wrap -mx-3">
  <div class="flex-none w-full max-w-full px-3">
    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
        <h6 class="dark:text-white">Reservation Requirements List</h6>
      </div>
      <div class="flex-auto px-0 pt-0 pb-2">
        <div class="p-0 overflow-x-auto">
          <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="reservationRequirementsTable">
            <thead class="align-bottom">
              <tr>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reservation</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Requirement</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Guest</th>
                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">File</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Notes</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reviewed By</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reviewed At</th>
              </tr>
            </thead>
            <tbody id="reservationRequirementsTableBody">
              <tr><td colspan="8" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  let requirementsData = [];

  document.addEventListener('DOMContentLoaded', function() {
    fetchRequirementsData();
  });

  async function fetchRequirementsData() {
    try {
      const response = await fetch('../api/reservation_requirements.php?action=list');
      const result = await response.json();
      if (result.success) {
        requirementsData = result.data;
        renderRequirementsTable();
      } else {
        document.getElementById('reservationRequirementsTableBody').innerHTML = `<tr><td colspan="8" class="p-4 text-center text-red-400">${result.message}</td></tr>`;
      }
    } catch (error) {
      console.error('Error fetching requirements:', error);
      document.getElementById('reservationRequirementsTableBody').innerHTML = `<tr><td colspan="8" class="p-4 text-center text-red-400">Failed to load data</td></tr>`;
    }
  }

  function formatDateTime(dtStr) {
    if (!dtStr) return '-';
    const d = new Date(dtStr);
    return d.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) + ' ' +
      d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
  }

  function renderRequirementsTable() {
    const tbody = document.getElementById('reservationRequirementsTableBody');
    if (!tbody) return;

    if (requirementsData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="8" class="p-4 text-center text-slate-400 dark:text-white/60">No reservation requirements found</td></tr>`;
      return;
    }

    const statusMap = {
      'pending': 'bg-yellow-100 text-yellow-600',
      'submitted': 'bg-blue-100 text-blue-600',
      'approved': 'bg-green-100 text-green-600',
      'rejected': 'bg-red-100 text-red-600'
    };

    tbody.innerHTML = requirementsData.map(req => {
      const statusCls = statusMap[req.status] || 'bg-gray-100 text-gray-600';
      const guest = req.first_name
        ? `<p class="mb-0 text-xs leading-tight dark:text-white">${req.first_name} ${req.last_name}</p><p class="mb-0 text-xs leading-tight text-slate-400 capitalize">${req.guest_type || ''}</p>`
        : `<p class="mb-0 text-xs leading-tight text-slate-400">-</p>`;
      const fileCell = req.file_url
        ? `<a href="${req.file_url}" target="_blank" class="text-xs text-blue-500 hover:underline">View File</a>`
        : `<p class="mb-0 text-xs leading-tight text-slate-400">No file</p>`;
      const reviewedBy = req.reviewed_by ? 'Staff ID: ' + req.reviewed_by.substring(0, 8) : '-';
      return `
        <tr class="req-row">
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${req.reservation_code || '-'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${req.req_label || '-'}</p>
            <p class="mb-0 text-xs leading-tight text-slate-400">${req.req_code || ''}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">${guest}</td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-center">
            <span class="text-xs font-semibold ${statusCls} px-2 py-1 rounded capitalize">${req.status || '-'}</span>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">${fileCell}</td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 text-xs leading-tight dark:text-white">${req.notes || '-'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 text-xs leading-tight dark:text-white">${reviewedBy}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 text-xs leading-tight dark:text-white">${formatDateTime(req.reviewed_at)}</p>
          </td>
        </tr>
      `;
    }).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    document.querySelectorAll('#reservationRequirementsTable .req-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }
</script>