<?php if (false) {} ?>
<script>
  let menuItemsData = [];
  let categoriesData = [];
  let propertiesData = [];

  async function fetchMenuItems() {
    try {
      const res = await fetch('../api/menu_items.php?action=list');
      const json = await res.json();
      if (json.success) {
        menuItemsData = json.data;
        renderStats();
        renderTable();
      }
    } catch (e) {
      console.error('Error fetching menu items:', e);
    }
  }

  async function fetchRelated() {
    try {
      const res = await fetch('../api/menu_items.php?action=related');
      const json = await res.json();
      if (json.success) {
        categoriesData = json.data.categories;
        propertiesData = json.data.properties;
        populateDropdowns();
      }
    } catch (e) {
      console.error('Error fetching related data:', e);
    }
  }

  function renderStats() {
    const available = menuItemsData.filter(i => i.is_available == 1 && i.is_active == 1).length;
    const featured = menuItemsData.filter(i => i.is_featured == 1).length;
    const inactive = menuItemsData.filter(i => i.is_active == 0).length;
    document.getElementById('statTotal').textContent = menuItemsData.length;
    document.getElementById('statAvailable').textContent = available;
    document.getElementById('statFeatured').textContent = featured;
    document.getElementById('statInactive').textContent = inactive;
  }

  function renderTable() {
    const tbody = document.getElementById('itemTableBody');
    if (!menuItemsData.length) {
      tbody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">No menu items found. <a href="javascript:;" onclick="openAddItemModal()" class="text-blue-500 font-semibold">Create your first item</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = menuItemsData.map(item => {
      let statusClass = '', statusText = '';
      if (item.is_active == 0) {
        statusClass = 'from-slate-500 to-slate-400';
        statusText = 'Inactive';
      } else if (item.is_available == 0) {
        statusClass = 'from-orange-500 to-amber-400';
        statusText = 'Unavailable';
      } else {
        statusClass = 'from-emerald-500 to-teal-400';
        statusText = 'Available';
      }
      return `
      <tr class="item-row">
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex px-2 py-1">
            <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-green-500 to-emerald-500">
              <i class="fas fa-hamburger"></i>
            </div>
            <div class="flex flex-col justify-center">
              <h6 class="mb-0 text-sm leading-normal dark:text-white">${item.item_name}</h6>
              ${item.item_code ? `<span class="text-xs text-slate-400 dark:text-white/60">${item.item_code}</span>` : ''}
              ${item.is_featured == 1 ? `<span class="text-xs text-amber-500 font-semibold"><i class="fas fa-star mr-1"></i>Featured</span>` : ''}
            </div>
          </div>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${item.category_name}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${item.property_id ? item.property_name : '<span class="text-blue-500">All Properties</span>'}</p>
        </td>
        <td class="p-2 text-right align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-sm font-bold leading-tight dark:text-white">${parseFloat(item.price).toFixed(2)}</p>
        </td>
        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <span class="bg-gradient-to-tl ${statusClass} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${statusText}</span>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex items-center gap-2">
            <button onclick="toggleItemAvailable(this)" data-id="${item.item_id}" data-status="${item.is_available}" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-500/20 hover:text-emerald-600 dark:hover:text-emerald-400 hover:border-emerald-300 dark:hover:border-emerald-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Toggle Availability">
              <i class="fas fa-toggle-on mr-1.5 text-xs"></i><span>${item.is_available ? 'Unavailable' : 'Available'}</span>
            </button>
            <button onclick="toggleItemFeatured(this)" data-id="${item.item_id}" data-status="${item.is_featured}" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Toggle Featured">
              <i class="fas fa-star mr-1.5 text-xs"></i><span>${item.is_featured ? 'Unfeature' : 'Feature'}</span>
            </button>
            <button onclick="openEditItemModal('${item.item_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Item">
              <i class="fas fa-edit mr-1.5 text-xs"></i><span>Edit</span>
            </button>
            <button onclick="deleteItem(this)" data-id="${item.item_id}" data-name="${item.item_name}" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Item">
              <i class="fas fa-trash-alt mr-1.5 text-xs"></i><span>Delete</span>
            </button>
          </div>
        </td>
      </tr>`;
    }).join('');
  }

  function populateDropdowns() {
    const catOpts = '<option value="">Select Category</option>' + categoriesData.map(c => `<option value="${c.category_id}">${c.category_name}</option>`).join('');
    const propOpts = '<option value="">All Properties (Global)</option>' + propertiesData.map(p => `<option value="${p.property_id}">${p.property_name}</option>`).join('');
    ['addCategory', 'editCategory'].forEach(id => { const sel = document.getElementById(id); if (sel) sel.innerHTML = catOpts; });
    ['addProperty', 'editProperty'].forEach(id => { const sel = document.getElementById(id); if (sel) sel.innerHTML = propOpts; });
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchMenuItems();
    fetchRelated();
  });
</script>

<!-- PAGE CONTENT -->

      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Items</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statTotal">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Available</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-emerald-500" id="statAvailable">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Featured</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-amber-500" id="statFeatured">...</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Inactive</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-slate-500" id="statInactive">...</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Menu Items Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Menu Items</h6>
              <button onclick="openAddItemModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> New Item
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="itemTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Item</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Category</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Price</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="itemTableBody">
                    <tr><td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add Item Modal -->
      <div id="addItemModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddItemModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Create New Menu Item</h3>
            <button onclick="closeAddItemModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addItemForm" onsubmit="submitAddItem(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Item Name <span class="text-red-500">*</span></label>
                  <input type="text" name="item_name" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Burger, Pasta, Salad">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Item Code</label>
                  <input type="text" name="item_code" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., BRG-001">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Category <span class="text-red-500">*</span></label>
                  <select name="category_id" id="addCategory" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Category</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Property</label>
                  <select name="property_id" id="addProperty" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Properties (Global)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Price <span class="text-red-500">*</span></label>
                  <input type="number" step="0.01" name="price" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Cost Price</label>
                  <input type="number" step="0.01" name="cost_price" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Description</label>
                  <textarea name="description" rows="3" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Item description..."></textarea>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Sort Order</label>
                  <input type="number" name="sort_order" value="0" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex items-end gap-4">
                  <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Status</label>
                    <select name="is_active" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </div>
                  <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Available</label>
                    <select name="is_available" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                      <option value="1">Yes</option>
                      <option value="0">No</option>
                    </select>
                  </div>
                  <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Featured</label>
                    <select name="is_featured" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                      <option value="0">No</option>
                      <option value="1">Yes</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeAddItemModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Create Item
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Item Modal -->
      <div id="editItemModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditItemModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Menu Item</h3>
            <button onclick="closeEditItemModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="editItemForm" onsubmit="submitEditItem(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="item_id" id="editItemId">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Item Name <span class="text-red-500">*</span></label>
                  <input type="text" name="item_name" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Burger, Pasta, Salad">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Item Code</label>
                  <input type="text" name="item_code" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., BRG-001">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Category <span class="text-red-500">*</span></label>
                  <select name="category_id" id="editCategory" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Category</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Property</label>
                  <select name="property_id" id="editProperty" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Properties (Global)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Price <span class="text-red-500">*</span></label>
                  <input type="number" step="0.01" name="price" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Cost Price</label>
                  <input type="number" step="0.01" name="cost_price" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Description</label>
                  <textarea name="description" rows="3" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Item description..."></textarea>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Sort Order</label>
                  <input type="number" name="sort_order" value="0" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex items-end gap-4">
                  <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Status</label>
                    <select name="is_active" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </div>
                  <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Available</label>
                    <select name="is_available" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                      <option value="1">Yes</option>
                      <option value="0">No</option>
                    </select>
                  </div>
                  <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Featured</label>
                    <select name="is_featured" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                      <option value="0">No</option>
                      <option value="1">Yes</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeEditItemModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Update Item
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
    const table = document.getElementById('itemTable');
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

  // Add Item Modal
  function openAddItemModal() {
    document.getElementById('addItemModal').style.display = 'block';
    document.getElementById('addItemForm').reset();
  }

  function closeAddItemModal() {
    document.getElementById('addItemModal').style.display = 'none';
  }

  function submitAddItem(event) {
    event.preventDefault();
    const form = document.getElementById('addItemForm');
    const payload = {
      item_name: form.elements['item_name'].value,
      item_code: form.elements['item_code'].value,
      category_id: form.elements['category_id'].value,
      property_id: form.elements['property_id'].value || null,
      price: form.elements['price'].value,
      cost_price: form.elements['cost_price'].value,
      description: form.elements['description'].value,
      sort_order: form.elements['sort_order'].value,
      is_active: form.elements['is_active'].value,
      is_available: form.elements['is_available'].value,
      is_featured: form.elements['is_featured'].value
    };

    Swal.fire({
      title: 'Creating...',
      text: 'Please wait while we create the menu item.',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); },
      customClass: { popup: 'swal2-popup-high-z' }
    });

    fetch('../api/menu_items.php?action=create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddItemModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchMenuItems());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeAddItemModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while creating the menu item.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  // Edit Item Modal
  async function openEditItemModal(itemId) {
    try {
      const res = await fetch(`../api/menu_items.php?action=single&id=${itemId}`);
      const json = await res.json();
      if (!json.success || !json.data) return;
      const item = json.data;

    document.getElementById('editItemId').value = item.item_id;
    const form = document.getElementById('editItemForm');
    form.elements['item_name'].value = item.item_name;
    form.elements['item_code'].value = item.item_code || '';
    form.elements['category_id'].value = item.category_id;
    form.elements['property_id'].value = item.property_id || '';
    form.elements['price'].value = item.price;
    form.elements['cost_price'].value = item.cost_price;
    form.elements['description'].value = item.description || '';
    form.elements['sort_order'].value = item.sort_order;
    form.elements['is_active'].value = item.is_active;
    form.elements['is_available'].value = item.is_available;
    form.elements['is_featured'].value = item.is_featured;

    document.getElementById('editItemModal').style.display = 'block';
    } catch (e) {
      console.error('Error fetching item for edit:', e);
    }
  }

  function closeEditItemModal() {
    document.getElementById('editItemModal').style.display = 'none';
  }

  function submitEditItem(event) {
    event.preventDefault();
    const form = document.getElementById('editItemForm');
    const payload = {
      item_id: form.elements['item_id'].value,
      item_name: form.elements['item_name'].value,
      item_code: form.elements['item_code'].value,
      category_id: form.elements['category_id'].value,
      property_id: form.elements['property_id'].value || null,
      price: form.elements['price'].value,
      cost_price: form.elements['cost_price'].value,
      description: form.elements['description'].value,
      sort_order: form.elements['sort_order'].value,
      is_active: form.elements['is_active'].value,
      is_available: form.elements['is_available'].value,
      is_featured: form.elements['is_featured'].value
    };

    Swal.fire({
      title: 'Updating...',
      text: 'Please wait while we update the menu item.',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); },
      customClass: { popup: 'swal2-popup-high-z' }
    });

    fetch('../api/menu_items.php?action=update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditItemModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
            .then(() => fetchMenuItems());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
        }
      })
      .catch(() => {
        closeEditItemModal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while updating the menu item.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
      });
  }

  // Delete Item
  function deleteItem(button) {
    const itemId = button.dataset.id;
    const itemName = button.dataset.name;

    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to delete "${itemName}". This action cannot be undone.`,
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
          text: 'Please wait while we delete the menu item.',
          allowOutsideClick: false,
          didOpen: () => { Swal.showLoading(); },
          customClass: { popup: 'swal2-popup-high-z' }
        });

        fetch('../api/menu_items.php?action=delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ item_id: itemId })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
                .then(() => fetchMenuItems());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
            }
          })
          .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while deleting the menu item.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
          });
      }
    });
  }

  // Toggle Item Available
  function toggleItemAvailable(button) {
    const itemId = button.dataset.id;
    const currentStatus = parseInt(button.dataset.status);
    const newStatus = currentStatus === 1 ? 0 : 1;
    const statusText = newStatus === 1 ? 'mark as available' : 'mark as unavailable';

    Swal.fire({
      title: 'Change Availability?',
      text: `Are you sure you want to ${statusText} this item?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, change it!',
      cancelButtonText: 'Cancel',
      customClass: { popup: 'swal2-popup-high-z' }
    }).then((result) => {
      if (result.isConfirmed) {
        const payload = { item_id: itemId, is_available: newStatus };
        fetch('../api/menu_items.php?action=update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Availability Changed!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
                .then(() => fetchMenuItems());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
            }
          })
          .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while updating availability.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
          });
      }
    });
  }

  // Toggle Item Featured
  function toggleItemFeatured(button) {
    const itemId = button.dataset.id;
    const currentStatus = parseInt(button.dataset.status);
    const newStatus = currentStatus === 1 ? 0 : 1;
    const statusText = newStatus === 1 ? 'feature' : 'unfeature';

    Swal.fire({
      title: 'Change Featured Status?',
      text: `Are you sure you want to ${statusText} this item?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, change it!',
      cancelButtonText: 'Cancel',
      customClass: { popup: 'swal2-popup-high-z' }
    }).then((result) => {
      if (result.isConfirmed) {
        const payload = { item_id: itemId, is_featured: newStatus };
        fetch('../api/menu_items.php?action=update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Featured Status Changed!', text: data.message, confirmButtonColor: '#3085d6', customClass: { popup: 'swal2-popup-high-z' } })
                .then(() => fetchMenuItems());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
            }
          })
          .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while updating featured status.', confirmButtonColor: '#d33', customClass: { popup: 'swal2-popup-high-z' } });
          });
      }
    });
  }

  // Manage Item Images
  function manageItemImages(itemId, itemName) {
    Swal.fire({
      title: 'Manage Images',
      text: `Manage images for "${itemName}"`,
      icon: 'info',
      confirmButtonColor: '#3085d6'
    });
    // TODO: Implement modal to manage item images
  }

  // Duplicate Item
  function duplicateItem(itemId, itemName) {
    Swal.fire({
      title: 'Duplicate Item?',
      text: `Create a copy of "${itemName}"?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, duplicate it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        // TODO: Implement AJAX call to duplicate item
        Swal.fire({
          icon: 'success',
          title: 'Duplicated!',
          text: 'Menu item has been duplicated.',
          confirmButtonColor: '#3085d6'
        }).then(() => {
          location.reload();
        });
      }
    });
  }

  // View Item Order History
  function viewItemOrderHistory(itemId, itemName) {
    Swal.fire({
      title: 'Order History',
      text: `View order history for "${itemName}"`,
      icon: 'info',
      confirmButtonColor: '#3085d6'
    });
    // TODO: Implement modal to show order history
  }
</script>