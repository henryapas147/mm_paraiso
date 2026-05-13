<?php if (false) {} ?>
<script>
  let foodOrdersData = [];
  let propertiesData = [];
  let reservationsData = [];
  let menuItemsData = [];
  let paymentMethodsData = [];

  async function fetchOrders() {
    try {
      const res = await fetch('../api/food_orders.php?action=list');
      const json = await res.json();
      if (json.success) {
        foodOrdersData = json.data;
        renderStats();
        renderTable();
      }
    } catch (e) {
      console.error('Error fetching orders:', e);
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/food_orders.php?action=related');
      const json = await res.json();
      if (json.success) {
        propertiesData = json.data.properties;
        reservationsData = json.data.reservations;
        menuItemsData = json.data.menuItems;
        paymentMethodsData = json.data.paymentMethods;
        populateDropdowns();
      }
    } catch (e) {
      console.error('Error fetching related data:', e);
    }
  }

  function renderStats() {
    const pending = foodOrdersData.filter(o => ['pending', 'confirmed', 'preparing'].includes(o.order_status)).length;
    const ready = foodOrdersData.filter(o => ['ready', 'delivered'].includes(o.order_status)).length;
    const unpaid = foodOrdersData.filter(o => o.payment_status === 'unpaid').length;
    document.getElementById('statTotal').textContent = foodOrdersData.length;
    document.getElementById('statPending').textContent = pending;
    document.getElementById('statReady').textContent = ready;
    document.getElementById('statUnpaid').textContent = unpaid;
  }

  function renderTable() {
    const tbody = document.getElementById('ordersTableBody');
    if (!foodOrdersData.length) {
      tbody.innerHTML = `<tr><td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">No food orders found.</td></tr>`;
      return;
    }
    tbody.innerHTML = foodOrdersData.map(order => {
      const statusColors = {
        'pending': 'from-orange-500 to-amber-400',
        'confirmed': 'from-blue-500 to-cyan-400',
        'preparing': 'from-purple-500 to-pink-400',
        'ready': 'from-emerald-500 to-teal-400',
        'delivered': 'from-slate-500 to-slate-400',
        'cancelled': 'from-red-500 to-rose-400'
      };
      const color = statusColors[order.order_status] ?? 'from-slate-500 to-slate-400';
      return `
      <tr class="order-row">
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-bold leading-tight dark:text-white">${order.order_number}</p>
          <p class="mb-0 text-xs text-slate-400 dark:text-white/60">${new Date(order.created_at).toLocaleString()}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${order.property_name}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${order.guest_name || 'Walk-in'}</p>
          ${order.reservation_code ? `<p class="mb-0 text-xs text-slate-400 dark:text-white/60">${order.reservation_code}</p>` : ''}
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${order.order_type.replace(/_/g, ' ')}</p>
          ${order.table_number ? `<p class="mb-0 text-xs text-slate-400 dark:text-white/60">Table ${order.table_number}</p>` : ''}
        </td>
        <td class="p-2 text-right align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-sm font-bold leading-tight dark:text-white">${parseFloat(order.total_amount).toFixed(2)}</p>
          <p class="mb-0 text-xs text-slate-400 dark:text-white/60">${order.payment_status.replace(/_/g, ' ')}</p>
        </td>
        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <span class="bg-gradient-to-tl ${color} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${order.order_status.replace(/_/g, ' ')}</span>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex items-center gap-2">
            <button onclick="viewOrderDetails('${order.order_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
              <i class="fas fa-eye mr-1.5 text-xs"></i><span>View</span>
            </button>
            <button onclick="updateOrderStatus('${order.order_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Update Status">
              <i class="fas fa-sync mr-1.5 text-xs"></i><span>Status</span>
            </button>
            <button onclick="updateOrderPayment('${order.order_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-500/20 hover:text-emerald-600 dark:hover:text-emerald-400 hover:border-emerald-300 dark:hover:border-emerald-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Update Payment">
              <i class="fas fa-credit-card mr-1.5 text-xs"></i><span>Pay</span>
            </button>
          </div>
        </td>
      </tr>`;
    }).join('');
  }

  function populateDropdowns() {
    const propOpts = '<option value="">Select Property</option>' + propertiesData.map(p => `<option value="${p.property_id}">${p.property_name}</option>`).join('');
    const resOpts = '<option value="">Walk-in / Select Reservation</option>' + reservationsData.map(r => `<option value="${r.reservation_id}">${r.display}</option>`).join('');
    const itemOpts = menuItemsData.map(m => `<option value="${m.item_id}" data-price="${m.price}">${m.item_name} - ${m.item_code} - ${parseFloat(m.price).toFixed(2)}</option>`).join('');
    const payOpts = '<option value="">Select Payment Method</option>' + paymentMethodsData.map(p => `<option value="${p.method_id}">${p.method_name}</option>`).join('');
    ['addProperty', 'editProperty'].forEach(id => { const sel = document.getElementById(id); if (sel) sel.innerHTML = propOpts; });
    ['addReservation', 'editReservation'].forEach(id => { const sel = document.getElementById(id); if (sel) sel.innerHTML = resOpts; });
    const sel3 = document.getElementById('addMenuItem'); if (sel3) sel3.innerHTML = itemOpts;
    const sel4 = document.getElementById('addPaymentMethod'); if (sel4) sel4.innerHTML = payOpts;
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchOrders();
    fetchRelated();
  });
</script>

<!-- PAGE CONTENT -->

      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Orders</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statTotal">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Pending</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-orange-500" id="statPending">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Ready/Delivered</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-emerald-500" id="statReady">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Unpaid</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-red-500" id="statUnpaid">...</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Food Orders Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Food Orders</h6>
              <button onclick="openAddOrderModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> New Order
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="orderTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Order</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Customer</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Total</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="ordersTableBody">
                    <tr><td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- View Order Details Modal -->
      <div id="orderDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeOrderDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white" id="orderModalTitle">Order Details</h3>
            <button onclick="closeOrderDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6" id="orderModalContent">
            <!-- Content will be populated by JavaScript -->
          </div>
        </div>
      </div>

      <!-- Add Order Modal -->
      <div id="addOrderModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddOrderModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Create New Food Order</h3>
            <button onclick="closeAddOrderModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addOrderForm" onsubmit="submitAddOrder(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Property <span class="text-red-500">*</span></label>
                  <select name="property_id" id="addProperty" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Property</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Order Type <span class="text-red-500">*</span></label>
                  <select name="order_type" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="dine_in">Dine In</option>
                    <option value="room_service">Room Service</option>
                    <option value="takeout">Takeout</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Reservation (Optional)</label>
                  <select name="reservation_id" id="addReservation" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Walk-in Customer</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Customer Name</label>
                  <input type="text" name="customer_name" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Customer name">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Table Number</label>
                  <input type="text" name="table_number" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., T-01">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Order Status</label>
                  <select name="order_status" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="preparing">Preparing</option>
                    <option value="ready">Ready</option>
                    <option value="delivered">Delivered</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Payment Status</label>
                  <select name="payment_status" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="unpaid">Unpaid</option>
                    <option value="charged_to_room">Charged to Room</option>
                    <option value="paid">Paid</option>
                  </select>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Additional notes..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeAddOrderModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Create Order
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Order Modal -->
      <div id="editOrderModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditOrderModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Food Order</h3>
            <button onclick="closeEditOrderModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="editOrderForm" onsubmit="submitEditOrder(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="order_id" id="editOrderId">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Property <span class="text-red-500">*</span></label>
                  <select name="property_id" id="editProperty" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Property</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Order Type <span class="text-red-500">*</span></label>
                  <select name="order_type" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="dine_in">Dine In</option>
                    <option value="room_service">Room Service</option>
                    <option value="takeout">Takeout</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Reservation (Optional)</label>
                  <select name="reservation_id" id="editReservation" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Walk-in Customer</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Customer Name</label>
                  <input type="text" name="customer_name" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Customer name">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Table Number</label>
                  <input type="text" name="table_number" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., T-01">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Order Status</label>
                  <select name="order_status" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="preparing">Preparing</option>
                    <option value="ready">Ready</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Payment Status</label>
                  <select name="payment_status" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="unpaid">Unpaid</option>
                    <option value="charged_to_room">Charged to Room</option>
                    <option value="paid">Paid</option>
                  </select>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Additional notes..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeEditOrderModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Update Order
              </button>
            </div>
          </form>
        </div>
      </div>

<script>
  // Filter table function
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('orderTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
      const row = rows[i];
      const cells = row.getElementsByTagName('td');
      let found = false;

      for (let j = 0; j < cells.length; j++) {
        if (cells[j]) {
          const textValue = cells[j].textContent || cells[j].innerText;
          if (textValue.toLowerCase().indexOf(filter) > -1) {
            found = true;
            break;
          }
        }
      }

      row.style.display = found ? '' : 'none';
    }
  }

  // View Order Details
  async function viewOrderDetails(orderId) {
    try {
      const res = await fetch(`../api/food_orders.php?action=single&id=${orderId}`);
      const json = await res.json();
      if (!json.success || !json.data) return;
      const order = json.data;

    document.getElementById('orderModalTitle').textContent = `Order Details - ${order.order_number}`;
    const itemsHtml = order.items ? order.items.map(i => `
      <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-white/10 last:border-0">
        <div>
          <p class="font-semibold dark:text-white">${i.item_name}</p>
          <p class="text-xs text-slate-500 dark:text-white/60">Qty: ${i.quantity} @ ${parseFloat(i.unit_price).toFixed(2)}</p>
        </div>
        <p class="font-semibold dark:text-white">${parseFloat(i.subtotal).toFixed(2)}</p>
      </div>`).join('') : '<p class="text-sm dark:text-white/80 italic">No items</p>';

    document.getElementById('orderModalContent').innerHTML = `
          <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
              <p class="text-sm text-slate-500 dark:text-white/60">Order Number</p>
              <p class="font-semibold dark:text-white">${order.order_number}</p>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-white/60">Order Type</p>
              <p class="font-semibold dark:text-white">${order.order_type.replace(/_/g, ' ')}</p>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-white/60">Customer</p>
              <p class="font-semibold dark:text-white">${order.customer_name || (order.guest_name || 'Walk-in')}</p>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-white/60">Table Number</p>
              <p class="font-semibold dark:text-white">${order.table_number || '-'}</p>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-white/60">Order Status</p>
              <p class="font-semibold dark:text-white">${order.order_status.replace(/_/g, ' ')}</p>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-white/60">Payment Status</p>
              <p class="font-semibold dark:text-white">${order.payment_status.replace(/_/g, ' ')}</p>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-white/60">Subtotal</p>
              <p class="font-semibold dark:text-white">${parseFloat(order.subtotal).toFixed(2)}</p>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-white/60">Total</p>
              <p class="font-semibold dark:text-white">${parseFloat(order.total_amount).toFixed(2)}</p>
            </div>
          </div>
          ${order.notes ? `
          <div class="mb-4">
            <p class="text-sm text-slate-500 dark:text-white/60">Notes</p>
            <p class="font-semibold dark:text-white">${order.notes}</p>
          </div>
          ` : ''}
          <div class="p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg">
            <p class="text-sm text-slate-500 dark:text-white/60 mb-2">Order Items</p>
            ${itemsHtml}
          </div>
        `;
    document.getElementById('orderDetailsModal').style.display = 'block';
    } catch (e) {
      console.error('Error fetching order details:', e);
    }
  }

  function closeOrderDetailsModal() {
    document.getElementById('orderDetailsModal').style.display = 'none';
  }

  // Add Order Modal
  function openAddOrderModal() {
    document.getElementById('addOrderModal').style.display = 'block';
    document.getElementById('addOrderForm').reset();
  }

  function closeAddOrderModal() {
    document.getElementById('addOrderModal').style.display = 'none';
  }

  function submitAddOrder(event) {
    event.preventDefault();
    const form = document.getElementById('addOrderForm');
    const payload = {
      property_id: form.elements['property_id'].value,
      reservation_id: form.elements['reservation_id'].value || null,
      order_type: form.elements['order_type'].value,
      customer_name: form.elements['customer_name'].value,
      table_number: form.elements['table_number'].value,
      order_status: form.elements['order_status'].value,
      payment_status: form.elements['payment_status'].value,
      notes: form.elements['notes'].value,
      subtotal: 0,
      total_amount: 0,
      items: []
    };

    Swal.fire({
      title: 'Creating...',
      text: 'Please wait while we create the order.',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); },
      customClass: { popup: 'swal2-popup-high-z' }
    });

    fetch('../api/food_orders.php?action=create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddOrderModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchOrders());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeAddOrderModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while creating the order.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  // Edit Order Modal
  async function openEditOrderModal(orderId) {
    try {
      const res = await fetch(`../api/food_orders.php?action=single&id=${orderId}`);
      const json = await res.json();
      if (!json.success || !json.data) return;
      const order = json.data;

    document.getElementById('editOrderId').value = order.order_id;
    const form = document.getElementById('editOrderForm');
    form.elements['property_id'].value = order.property_id;
    form.elements['order_type'].value = order.order_type;
    form.elements['reservation_id'].value = order.reservation_id || '';
    form.elements['customer_name'].value = order.customer_name || '';
    form.elements['table_number'].value = order.table_number || '';
    form.elements['order_status'].value = order.order_status;
    form.elements['payment_status'].value = order.payment_status;
    form.elements['notes'].value = order.notes || '';

    document.getElementById('editOrderModal').style.display = 'block';
    } catch (e) {
      console.error('Error fetching order for edit:', e);
    }
  }

  function closeEditOrderModal() {
    document.getElementById('editOrderModal').style.display = 'none';
  }

  function submitEditOrder(event) {
    event.preventDefault();
    const form = document.getElementById('editOrderForm');
    const payload = {
      order_id: form.elements['order_id'].value,
      property_id: form.elements['property_id'].value,
      reservation_id: form.elements['reservation_id'].value || null,
      order_type: form.elements['order_type'].value,
      customer_name: form.elements['customer_name'].value,
      table_number: form.elements['table_number'].value,
      order_status: form.elements['order_status'].value,
      payment_status: form.elements['payment_status'].value,
      notes: form.elements['notes'].value
    };

    Swal.fire({
      title: 'Updating...',
      text: 'Please wait while we update the order.',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); },
      customClass: { popup: 'swal2-popup-high-z' }
    });

    fetch('../api/food_orders.php?action=update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditOrderModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchOrders());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeEditOrderModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while updating the order.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  // Delete Order
  function deleteOrder(orderId, orderNumber) {
    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to delete "${orderNumber}". This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
      customClass: { popup: 'swal2-popup-high-z' }
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Deleting...',
          text: 'Please wait while we delete the order.',
          allowOutsideClick: false,
          didOpen: () => { Swal.showLoading(); },
          customClass: { popup: 'swal2-popup-high-z' }
        });

        fetch('../api/food_orders.php?action=delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ order_id: orderId })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
                .then(() => fetchOrders());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
            }
          })
          .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while deleting the order.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
          });
      }
    });
  }

  // Update Order Status
  function updateOrderStatus(orderId) {
    Swal.fire({
      title: 'Update Status',
      input: 'select',
      inputOptions: {
        'pending': 'Pending',
        'confirmed': 'Confirmed',
        'preparing': 'Preparing',
        'ready': 'Ready',
        'delivered': 'Delivered',
        'cancelled': 'Cancelled'
      },
      inputPlaceholder: 'Select new status',
      showCancelButton: true,
      confirmButtonText: 'Update',
      cancelButtonText: 'Cancel',
      customClass: { popup: 'swal2-popup-high-z' }
    }).then((result) => {
      if (result.isConfirmed && result.value) {
        const payload = { order_id: orderId, order_status: result.value };
        fetch('../api/food_orders.php?action=update_status', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
                .then(() => fetchOrders());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
            }
          })
          .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while updating status.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
          });
      }
    });
  }

  // Change Payment Status
  function changePaymentStatus(orderId, orderNumber) {
    Swal.fire({
      title: 'Change Payment Status',
      text: `Change payment status for order "${orderNumber}"`,
      icon: 'question',
      input: 'select',
      inputOptions: {
        'unpaid': 'Unpaid',
        'charged_to_room': 'Charged to Room',
        'paid': 'Paid'
      },
      inputPlaceholder: 'Select payment status',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Change',
      cancelButtonText: 'Cancel',
      customClass: { popup: 'swal2-popup-high-z' }
    }).then((result) => {
      if (result.isConfirmed && result.value) {
        const payload = { order_id: orderId, payment_status: result.value };
        fetch('../api/food_orders.php?action=update_payment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Payment Status Changed!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
                .then(() => fetchOrders());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
            }
          })
          .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while updating payment status.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
          });
      }
    });
  }

  // Add Order Items
  function addOrderItems(orderId, orderNumber) {
    Swal.fire({
      title: 'Add Items to Order',
      text: `Add items to order "${orderNumber}"`,
      icon: 'info',
      confirmButtonColor: '#3085d6'
    });
    // TODO: Implement modal to add items to order
  }

  // Print Order
  function printOrder(orderId, orderNumber) {
    Swal.fire({
      title: 'Print Order',
      text: `Print order "${orderNumber}"`,
      icon: 'info',
      confirmButtonColor: '#3085d6'
    });
    // TODO: Implement print functionality
  }
</script>