<!-- PAGE CONTENT -->

<div class="flex flex-wrap -mx-3">
  <div class="flex-none w-full max-w-full px-3">
    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
        <h6 class="dark:text-white">Reservations List</h6>
      </div>
      <div class="flex-auto px-0 pt-0 pb-2">
        <div class="p-0 overflow-x-auto">
          <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="reservationsTable">
            <thead class="align-bottom">
              <tr>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Code</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Guest</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Check-in</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Check-out</th>
                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Payment</th>
                <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Total</th>
              </tr>
            </thead>
            <tbody id="reservationsTableBody">
              <tr>
                <td colspan="9" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  let reservationsData = [];

  document.addEventListener('DOMContentLoaded', function() {
    fetchReservationsData();
  });

  async function fetchReservationsData() {
    try {
      const response = await fetch('../api/reservations.php?action=list');
      const result = await response.json();
      if (result.success) {
        reservationsData = result.data;
        renderReservationsTable();
      } else {
        showError('reservationsTableBody', 9, result.message);
      }
    } catch (error) {
      console.error('Error fetching reservations:', error);
      showError('reservationsTableBody', 9, 'Failed to load reservations data');
    }
  }

  function formatDate(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
  }

  function formatTime(timeStr) {
    if (!timeStr) return '-';
    const [h, m] = timeStr.split(':');
    const hour = parseInt(h);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    return `${hour % 12 || 12}:${m} ${ampm}`;
  }

  function statusBadge(status, map) {
    const cls = map[status] || 'bg-gray-100 text-gray-600';
    const label = status ? status.replace(/_/g, ' ') : '-';
    return `<span class="text-xs font-semibold ${cls} px-2 py-1 rounded capitalize">${label}</span>`;
  }

  function renderReservationsTable() {
    const tbody = document.getElementById('reservationsTableBody');
    if (!tbody) return;

    if (reservationsData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="9" class="p-4 text-center text-slate-400 dark:text-white/60">No reservations found</td></tr>`;
      return;
    }

    const statusMap = {
      'pending': 'bg-yellow-100 text-yellow-600',
      'confirmed': 'bg-green-100 text-green-600',
      'checked_in': 'bg-blue-100 text-blue-600',
      'checked_out': 'bg-gray-100 text-gray-600',
      'cancelled': 'bg-red-100 text-red-600',
      'no_show': 'bg-orange-100 text-orange-600',
      'waitlisted': 'bg-purple-100 text-purple-600'
    };
    const paymentMap = {
      'unpaid': 'bg-red-100 text-red-600',
      'partial': 'bg-yellow-100 text-yellow-600',
      'paid': 'bg-green-100 text-green-600',
      'refunded': 'bg-blue-100 text-blue-600',
      'void': 'bg-gray-100 text-gray-600'
    };

    tbody.innerHTML = reservationsData.map(r => `
      <tr class="reservation-row">
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${r.reservation_code || '-'}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${r.guest_name || '-'}</p>
          <p class="mb-0 text-xs leading-tight text-slate-400">${r.guest_email || '-'}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${r.property_name || '-'}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <span class="text-xs font-semibold bg-blue-100 text-blue-600 px-2 py-1 rounded capitalize">${r.booking_type || '-'}</span>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <p class="mb-0 text-xs leading-tight dark:text-white">${formatDate(r.check_in_date)}</p>
          <p class="mb-0 text-xs leading-tight text-slate-400">${formatTime(r.check_in_time)}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <p class="mb-0 text-xs leading-tight dark:text-white">${formatDate(r.check_out_date)}</p>
          <p class="mb-0 text-xs leading-tight text-slate-400">${r.check_out_date ? formatTime(r.check_out_time) : ''}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-center">
          ${statusBadge(r.reservation_status, statusMap)}
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-center">
          ${statusBadge(r.payment_status, paymentMap)}
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-right">
          <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${parseFloat(r.total_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
        </td>
      </tr>
    `).join('');
  }

  function showError(tbodyId, colspan, msg) {
    const tbody = document.getElementById(tbodyId);
    if (tbody) tbody.innerHTML = `<tr><td colspan="${colspan}" class="p-4 text-center text-red-400">${msg}</td></tr>`;
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    const rows = document.querySelectorAll('#reservationsTable .reservation-row');
    rows.forEach(row => {
      const text = row.textContent.toUpperCase();
      row.style.display = text.includes(filter) ? '' : 'none';
    });
  }
</script>