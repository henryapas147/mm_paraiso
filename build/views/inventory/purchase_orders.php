<script>
  let purchaseOrdersData = [];
  let suppliersData = [];
  let propertiesData = [];

  async function fetchPurchaseOrders() {
    try {
      const res = await fetch('../api/purchase_orders.php?action=list');
      const json = await res.json();
      if (json.success) {
        purchaseOrdersData = json.data;
        renderStats();
        renderTable();
      }
    } catch (e) {
      console.error('Error fetching purchase orders:', e);
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/purchase_orders.php?action=related');
      const json = await res.json();
      if (json.success) {
        suppliersData = json.data.suppliers;
        propertiesData = json.data.properties;
        populateDropdowns();
      }
    } catch (e) {
      console.error('Error fetching related data:', e);
    }
  }

  function renderStats() {
    const pending = purchaseOrdersData.filter(o => ['draft','submitted','approved'].includes(o.po_status));
    const received = purchaseOrdersData.filter(o => ['partially_received','fully_received'].includes(o.po_status));
    const unpaid = purchaseOrdersData.filter(o => ['unpaid','partial'].includes(o.payment_status));
    document.getElementById('statTotal').textContent = purchaseOrdersData.length;
    document.getElementById('statPending').textContent = pending.length;
    document.getElementById('statReceived').textContent = received.length;
    document.getElementById('statUnpaid').textContent = unpaid.length;
  }

  const statusColors = {
    'draft': 'from-slate-500 to-slate-400',
    'submitted': 'from-blue-500 to-blue-400',
    'approved': 'from-cyan-500 to-cyan-400',
    'partially_received': 'from-amber-500 to-amber-400',
    'fully_received': 'from-emerald-500 to-teal-400',
    'cancelled': 'from-red-500 to-rose-400',
    'closed': 'from-slate-500 to-slate-400'
  };

  function renderTable() {
    const tbody = document.getElementById('poTableBody');
    if (!purchaseOrdersData.length) {
      tbody.innerHTML = `<tr><td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">No purchase orders found. <a href="javascript:;" onclick="openAddPOModal()" class="text-blue-500 font-semibold">Create your first order</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = purchaseOrdersData.map(po => {
      const color = statusColors[po.po_status] || 'from-slate-500 to-slate-400';
      const dateStr = po.po_date ? new Date(po.po_date).toLocaleDateString('en-US', {month:'short', day:'2-digit', year:'numeric'}) : '-';
      return `
      <tr class="po-row">
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex px-2 py-1">
            <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
              <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="flex flex-col justify-center">
              <h6 class="mb-0 text-sm leading-normal dark:text-white">${po.po_number}</h6>
            </div>
          </div>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${po.company_name}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${po.property_name}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${dateStr}</p>
        </td>
        <td class="p-2 text-right align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-sm font-bold leading-tight dark:text-white">${parseFloat(po.total_amount).toFixed(2)}</p>
        </td>
        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <span class="bg-gradient-to-tl ${color} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${po.po_status.replace(/_/g, ' ')}</span>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex items-center gap-2">
            <button onclick="viewPODetails('${po.po_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
              <i class="fas fa-info-circle mr-1.5 text-xs"></i><span>View</span>
            </button>
            <button onclick="openEditPOModal('${po.po_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Order">
              <i class="fas fa-edit mr-1.5 text-xs"></i><span>Edit</span>
            </button>
            <button onclick="deletePO(this)" data-id="${po.po_id}" data-number="${po.po_number.replace(/"/g, '&quot;')}" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Order">
              <i class="fas fa-trash-alt mr-1.5 text-xs"></i><span>Delete</span>
            </button>
          </div>
        </td>
      </tr>`;
    }).join('');
  }

  function populateDropdowns() {
    const propertySelects = document.querySelectorAll('select[name="property_id"]');
    const supplierSelects = document.querySelectorAll('select[name="supplier_id"]');
    const propOptions = '<option value="">Select Property</option>' + propertiesData.map(p => `<option value="${p.property_id}">${p.property_name}</option>`).join('');
    const supOptions = '<option value="">Select Supplier</option>' + suppliersData.map(s => `<option value="${s.supplier_id}">${s.company_name}</option>`).join('');
    propertySelects.forEach(sel => sel.innerHTML = propOptions);
    supplierSelects.forEach(sel => sel.innerHTML = supOptions);
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchPurchaseOrders();
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
              <h6 class="mb-0 font-bold dark:text-white">Received</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-emerald-500" id="statReceived">...</h5>
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

      <!-- Purchase Orders Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Purchase Orders</h6>
              <button onclick="openAddPOModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> New Order
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="poTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">PO Number</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Supplier</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Date</th>
                      <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Total</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="poTableBody">
                    <tr><td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- View PO Details Modal -->
      <div id="poDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closePODetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white" id="poModalTitle">Purchase Order Details</h3>
            <button onclick="closePODetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6" id="poModalContent">
            <!-- Content will be populated by JavaScript -->
          </div>
        </div>
      </div>

      <!-- Add PO Modal -->
      <div id="addPOModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddPOModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Create New Purchase Order</h3>
            <button onclick="closeAddPOModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addPOForm" onsubmit="submitAddPO(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Property <span class="text-red-500">*</span></label>
                  <select name="property_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Property</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Supplier <span class="text-red-500">*</span></label>
                  <select name="supplier_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Supplier</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">PO Number <span class="text-red-500">*</span></label>
                  <input type="text" name="po_number" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., PO-2024-000001">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">PO Date <span class="text-red-500">*</span></label>
                  <input type="date" name="po_date" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Expected Delivery</label>
                  <input type="date" name="expected_delivery" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Status</label>
                  <select name="po_status" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="draft">Draft</option>
                    <option value="submitted">Submitted</option>
                    <option value="approved">Approved</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Payment Terms</label>
                  <input type="text" name="payment_terms" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Net 30, COD">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Delivery Address</label>
                  <textarea name="delivery_address" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Delivery address..."></textarea>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Additional notes..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeAddPOModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Create Order
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit PO Modal -->
      <div id="editPOModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditPOModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Purchase Order</h3>
            <button onclick="closeEditPOModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="editPOForm" onsubmit="submitEditPO(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="po_id" id="editPOId">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Property <span class="text-red-500">*</span></label>
                  <select name="property_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Property</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Supplier <span class="text-red-500">*</span></label>
                  <select name="supplier_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Supplier</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">PO Number <span class="text-red-500">*</span></label>
                  <input type="text" name="po_number" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., PO-2024-000001">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">PO Date <span class="text-red-500">*</span></label>
                  <input type="date" name="po_date" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Expected Delivery</label>
                  <input type="date" name="expected_delivery" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Status</label>
                  <select name="po_status" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="draft">Draft</option>
                    <option value="submitted">Submitted</option>
                    <option value="approved">Approved</option>
                    <option value="partially_received">Partially Received</option>
                    <option value="fully_received">Fully Received</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="closed">Closed</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Payment Terms</label>
                  <input type="text" name="payment_terms" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Net 30, COD">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Delivery Address</label>
                  <textarea name="delivery_address" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Delivery address..."></textarea>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Additional notes..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeEditPOModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
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
    const table = document.getElementById('poTable');
    const rows = table.getElementsByClassName('po-row');

    for (let i = 0; i < rows.length; i++) {
      const row = rows[i];
      const cells = row.getElementsByTagName('td');
      let found = false;

      for (let j = 0; j < cells.length; j++) {
        if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
          found = true;
          break;
        }
      }

      row.style.display = found ? '' : 'none';
    }
  }

  // View PO Details
  function viewPODetails(poId) {
    const po = purchaseOrdersData.find(p => p.po_id === poId);
    if (!po) return;

    const modal = document.getElementById('poDetailsModal');
    const modalTitle = document.getElementById('poModalTitle');
    const modalContent = document.getElementById('poModalContent');

    modalTitle.textContent = `PO: ${po.po_number}`;

    const statusColors = {
      'draft': 'from-slate-500 to-slate-400',
      'submitted': 'from-blue-500 to-blue-400',
      'approved': 'from-cyan-500 to-cyan-400',
      'partially_received': 'from-amber-500 to-amber-400',
      'fully_received': 'from-emerald-500 to-teal-400',
      'cancelled': 'from-red-500 to-rose-400',
      'closed': 'from-slate-500 to-slate-400'
    };
    const color = statusColors[po.po_status] ?? 'from-slate-500 to-slate-400';

    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Order Information</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">PO Number</p>
                <p class="text-sm dark:text-white font-semibold">${po.po_number}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">PO Date</p>
                <p class="text-sm dark:text-white">${new Date(po.po_date).toLocaleDateString()}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Expected Delivery</p>
                <p class="text-sm dark:text-white">${po.expected_delivery ? new Date(po.expected_delivery).toLocaleDateString() : '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Status</p>
                <span class="bg-gradient-to-tl ${color} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${po.po_status.replace(/_/g, ' ')}</span>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Supplier & Property</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Supplier</p>
                <p class="text-sm dark:text-white">${po.company_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Property</p>
                <p class="text-sm dark:text-white">${po.property_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Delivery Address</p>
                <p class="text-sm dark:text-white">${po.delivery_address || '-'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Financials</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Subtotal</p>
                <p class="text-sm dark:text-white">PHP ${parseFloat(po.subtotal).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Tax Amount</p>
                <p class="text-sm dark:text-white">PHP ${parseFloat(po.tax_amount).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Shipping Fee</p>
                <p class="text-sm dark:text-white">PHP ${parseFloat(po.shipping_fee).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Discount Amount</p>
                <p class="text-sm dark:text-white">PHP ${parseFloat(po.discount_amount).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60 font-semibold">Total Amount</p>
                <p class="text-2xl font-bold dark:text-white">PHP ${parseFloat(po.total_amount).toFixed(2)}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Payment</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Payment Status</p>
                <p class="text-sm dark:text-white">${po.payment_status.replace(/_/g, ' ')}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Payment Terms</p>
                <p class="text-sm dark:text-white">${po.payment_terms || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Amount Paid</p>
                <p class="text-sm dark:text-white">PHP ${parseFloat(po.amount_paid).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Balance</p>
                <p class="text-sm dark:text-white">PHP ${parseFloat(po.balance).toFixed(2)}</p>
              </div>
            </div>
          </div>

          <div class="md:col-span-2">
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Notes</h4>
            <p class="text-sm dark:text-white">${po.notes || '-'}</p>
          </div>
        </div>
      `;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closePODetailsModal() {
    const modal = document.getElementById('poDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  // Add PO Modal Functions
  function openAddPOModal() {
    const modal = document.getElementById('addPOModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeAddPOModal() {
    const modal = document.getElementById('addPOModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('addPOForm').reset();
  }

  function submitAddPO(event) {
    event.preventDefault();

    Swal.fire({ title: 'Creating...', text: 'Please wait while we create the purchase order.', allowOutsideClick: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'swal2-popup-high-z' } });

    const form = document.getElementById('addPOForm');
    const payload = {
      property_id: form.elements['property_id'].value,
      supplier_id: form.elements['supplier_id'].value,
      po_number: form.elements['po_number'].value,
      po_date: form.elements['po_date'].value,
      expected_delivery: form.elements['expected_delivery'].value,
      po_status: form.elements['po_status'].value,
      payment_terms: form.elements['payment_terms'].value,
      delivery_address: form.elements['delivery_address'].value,
      notes: form.elements['notes'].value
    };

    fetch('../api/purchase_orders.php?action=create', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddPOModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchPurchaseOrders());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeAddPOModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while creating the purchase order.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  // Edit PO Modal Functions
  function openEditPOModal(poId) {
    const po = purchaseOrdersData.find(p => p.po_id === poId);
    if (!po) return;

    document.getElementById('editPOId').value = po.po_id;
    const form = document.getElementById('editPOForm');
    form.elements['property_id'].value = po.property_id;
    form.elements['supplier_id'].value = po.supplier_id;
    form.elements['po_number'].value = po.po_number;
    form.elements['po_date'].value = po.po_date;
    form.elements['expected_delivery'].value = po.expected_delivery || '';
    form.elements['po_status'].value = po.po_status;
    form.elements['payment_terms'].value = po.payment_terms || '';
    form.elements['delivery_address'].value = po.delivery_address || '';
    form.elements['notes'].value = po.notes || '';

    const modal = document.getElementById('editPOModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeEditPOModal() {
    const modal = document.getElementById('editPOModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('editPOForm').reset();
  }

  function submitEditPO(event) {
    event.preventDefault();

    Swal.fire({ title: 'Updating...', text: 'Please wait while we update the purchase order.', allowOutsideClick: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'swal2-popup-high-z' } });

    const form = document.getElementById('editPOForm');
    const payload = {
      po_id: form.elements['po_id'].value,
      property_id: form.elements['property_id'].value,
      supplier_id: form.elements['supplier_id'].value,
      po_number: form.elements['po_number'].value,
      po_date: form.elements['po_date'].value,
      expected_delivery: form.elements['expected_delivery'].value,
      po_status: form.elements['po_status'].value,
      payment_terms: form.elements['payment_terms'].value,
      delivery_address: form.elements['delivery_address'].value,
      notes: form.elements['notes'].value
    };

    fetch('../api/purchase_orders.php?action=update', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditPOModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchPurchaseOrders());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeEditPOModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while updating the purchase order.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  // Delete PO Function with SweetAlert
  function deletePO(el) {
    const poId = el.dataset.id;
    const poNumber = el.dataset.number;
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete purchase order "${poNumber}"? This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Deleting...',
          text: 'Please wait while we delete the purchase order.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });

        fetch('../api/purchase_orders.php?action=delete', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ po_id: poId })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
                .then(() => fetchPurchaseOrders());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
            }
          })
          .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while deleting the purchase order.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
          });
      }
    });
  }
</script>