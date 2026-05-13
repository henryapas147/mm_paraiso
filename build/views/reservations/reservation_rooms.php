<!-- PAGE CONTENT -->

<div class="flex flex-wrap -mx-3">
  <div class="flex-none w-full max-w-full px-3">
    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
        <h6 class="dark:text-white">Reservation Rooms List</h6>
      </div>
      <div class="flex-auto px-0 pt-0 pb-2">
        <div class="p-0 overflow-x-auto">
          <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="reservationRoomsTable">
            <thead class="align-bottom">
              <tr>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reservation</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Room</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Category</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Price/Night</th>
                <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nights/Hours</th>
                <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Room Total</th>
                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
              </tr>
            </thead>
            <tbody id="reservationRoomsTableBody">
              <tr><td colspan="9" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  let reservationRoomsData = [];

  document.addEventListener('DOMContentLoaded', function() {
    fetchReservationRoomsData();
  });

  async function fetchReservationRoomsData() {
    try {
      const response = await fetch('../api/reservation_rooms.php?action=list');
      const result = await response.json();
      if (result.success) {
        reservationRoomsData = result.data;
        renderReservationRoomsTable();
      } else {
        document.getElementById('reservationRoomsTableBody').innerHTML = `<tr><td colspan="9" class="p-4 text-center text-red-400">${result.message}</td></tr>`;
      }
    } catch (error) {
      console.error('Error fetching reservation rooms:', error);
      document.getElementById('reservationRoomsTableBody').innerHTML = `<tr><td colspan="9" class="p-4 text-center text-red-400">Failed to load data</td></tr>`;
    }
  }

  function renderReservationRoomsTable() {
    const tbody = document.getElementById('reservationRoomsTableBody');
    if (!tbody) return;

    if (reservationRoomsData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="9" class="p-4 text-center text-slate-400 dark:text-white/60">No reservation rooms found</td></tr>`;
      return;
    }

    const statusMap = {
      'reserved': 'bg-blue-100 text-blue-600',
      'checked_in': 'bg-green-100 text-green-600',
      'checked_out': 'bg-gray-100 text-gray-600',
      'cancelled': 'bg-red-100 text-red-600',
      'no_show': 'bg-orange-100 text-orange-600'
    };

    tbody.innerHTML = reservationRoomsData.map(rr => {
      const statusCls = statusMap[rr.room_status] || 'bg-gray-100 text-gray-600';
      const nightsHours = rr.booking_type === 'hourly'
        ? `${rr.num_hours || '-'} hrs`
        : `${rr.num_nights || '-'} nights`;
      return `
        <tr class="resroom-row">
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${rr.reservation_code || '-'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${rr.property_name || '-'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${(rr.room_number || '') + (rr.room_name ? ' - ' + rr.room_name : '')}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 text-xs leading-tight dark:text-white">${rr.category_name || '-'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <span class="text-xs font-semibold bg-blue-100 text-blue-600 px-2 py-1 rounded capitalize">${rr.booking_type || '-'}</span>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-right">
            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${parseFloat(rr.price_per_night || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-right">
            <p class="mb-0 text-xs leading-tight dark:text-white">${nightsHours}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-right">
            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${parseFloat(rr.room_total || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-center">
            <span class="text-xs font-semibold ${statusCls} px-2 py-1 rounded capitalize">${(rr.room_status || '-').replace(/_/g, ' ')}</span>
          </td>
        </tr>
      `;
    }).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    document.querySelectorAll('#reservationRoomsTable .resroom-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }
</script>