<?php if (false) {} ?>

<!-- PAGE CONTENT -->
    <!-- cards -->
    <div class="w-full px-6 py-6 mx-auto">
      <!-- PAGE CONTENT -->
      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Categories</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statTotal">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Parent Categories</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statParent">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Sub Categories</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statSub">-</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Categories Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Categories</h6>
              <button onclick="openAddCategoryModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Add Category
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="categoriesTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Category</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Parent Category</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Description</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="categoriesTableBody">
                    <tr><td colspan="5" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Category Details Modal -->
      <div id="categoryDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCategoryDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white" id="modalCategoryName" style="font-size: 1.25rem; font-weight: bold; margin: 0;">Category Details</h3>
            <button onclick="closeCategoryDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6" id="modalCategoryContent" style="padding: 1.5rem;">
            <!-- Content will be populated by JavaScript -->
          </div>
        </div>
      </div>

      <!-- Add Category Modal -->
      <div id="addCategoryModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddCategoryModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Add New Category</h3>
            <button onclick="closeAddCategoryModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addCategoryForm" onsubmit="submitAddCategory(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="md:col-span-2">
                  <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b border-gray-200 dark:border-white/10 pb-2">Basic Information</h4>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Category Code <span class="text-red-500">*</span></label>
                  <input type="text" name="category_code" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., CAT-001">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Category Name <span class="text-red-500">*</span></label>
                  <input type="text" name="category_name" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Toiletries">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Parent Category</label>
                  <select name="parent_id" id="addParentId" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">No Parent (Root Category)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Sort Order</label>
                  <input type="number" name="sort_order" step="1" min="0" value="0" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Description</label>
                  <textarea name="description" rows="3" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Category description..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeAddCategoryModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Save Category
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Category Modal -->
      <div id="editCategoryModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditCategoryModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Category</h3>
            <button onclick="closeEditCategoryModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="editCategoryForm" onsubmit="submitEditCategory(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="md:col-span-2">
                  <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b border-gray-200 dark:border-white/10 pb-2">Basic Information</h4>
                </div>
                <input type="hidden" name="category_id">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Category Code <span class="text-red-500">*</span></label>
                  <input type="text" name="category_code" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., CAT-001">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Category Name <span class="text-red-500">*</span></label>
                  <input type="text" name="category_name" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Toiletries">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Parent Category</label>
                  <select name="parent_id" id="editParentId" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">No Parent (Root Category)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Sort Order</label>
                  <input type="number" name="sort_order" step="1" min="0" value="0" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Description</label>
                  <textarea name="description" rows="3" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Category description..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeEditCategoryModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Update Category
              </button>
            </div>
          </form>
        </div>
      </div>

<script>
  let categoriesData = [];

  document.addEventListener('DOMContentLoaded', function () {
    fetchCategories();
  });

  async function fetchCategories() {
    try {
      const res = await fetch('../api/inventory_categories.php?action=list');
      const result = await res.json();
      if (result.success) {
        categoriesData = result.data;
        renderTable();
        renderStats();
        populateParentDropdowns();
      } else {
        document.getElementById('categoriesTableBody').innerHTML = `<tr><td colspan="5" class="p-4 text-center text-red-400">${result.message}</td></tr>`;
      }
    } catch (e) {
      document.getElementById('categoriesTableBody').innerHTML = `<tr><td colspan="5" class="p-4 text-center text-red-400">Failed to load data</td></tr>`;
    }
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = categoriesData.length;
    document.getElementById('statParent').textContent = categoriesData.filter(c => !c.parent_id).length;
    document.getElementById('statSub').textContent = categoriesData.filter(c => !!c.parent_id).length;
  }

  function populateParentDropdowns() {
    const opts = '<option value="">No Parent (Root Category)</option>' +
      categoriesData.map(c => `<option value="${c.category_id}">${c.category_name} (${c.category_code})</option>`).join('');
    document.getElementById('addParentId').innerHTML = opts;
    document.getElementById('editParentId').innerHTML = opts;
  }

  function renderTable() {
    const tbody = document.getElementById('categoriesTableBody');
    if (!categoriesData.length) {
      tbody.innerHTML = `<tr><td colspan="5" class="p-4 text-center text-slate-400 dark:text-white/60">No categories found. <a href="javascript:;" onclick="openAddCategoryModal()" class="text-blue-500 font-semibold">Add your first category</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = categoriesData.map(c => `
      <tr class="category-row">
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex px-2 py-1">
            <div class="inline-flex items-center justify-center mr-4 text-sm text-white h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500"><i class="fas fa-layer-group"></i></div>
            <div class="flex flex-col justify-center">
              <h6 class="mb-0 text-sm leading-normal dark:text-white">${c.category_name}</h6>
              <p class="mb-0 text-xs leading-tight text-slate-400 dark:text-white dark:opacity-80">${c.category_code}</p>
            </div>
          </div>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${c.parent_name || '-'}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-80">${(c.description || '').substring(0, 50)}${c.description && c.description.length > 50 ? '...' : ''}</p>
        </td>
        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center font-bold uppercase leading-none text-white">Active</span>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex items-center gap-2">
            <button onclick="viewCategoryDetails('${c.category_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-all duration-200 shadow-sm hover:shadow-md"><i class="fas fa-info-circle mr-1.5 text-xs"></i><span>View</span></button>
            <button onclick="openEditCategoryModal('${c.category_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 hover:text-amber-600 hover:border-amber-300 transition-all duration-200 shadow-sm hover:shadow-md"><i class="fas fa-edit mr-1.5 text-xs"></i><span>Edit</span></button>
            <button onclick="deleteCategory('${c.category_id}', '${c.category_name.replace(/'/g, "\\'")}}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 hover:text-red-700 hover:border-red-300 transition-all duration-200 shadow-sm hover:shadow-md"><i class="fas fa-trash-alt mr-1.5 text-xs"></i><span>Delete</span></button>
          </div>
        </td>
      </tr>`).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#categoriesTable .category-row').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
  }

  // View Category Details
  function viewCategoryDetails(categoryId) {
    const category = categoriesData.find(c => String(c.category_id) === String(categoryId));
    if (!category) return;

    const modal = document.getElementById('categoryDetailsModal');
    const modalTitle = document.getElementById('modalCategoryName');
    const modalContent = document.getElementById('modalCategoryContent');

    modalTitle.textContent = category.category_name;

    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Basic Information</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Category Code</p>
                <p class="text-sm dark:text-white">${category.category_code}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Category Name</p>
                <p class="text-sm dark:text-white">${category.category_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Parent Category</p>
                <p class="text-sm dark:text-white">${category.parent_name || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Sort Order</p>
                <p class="text-sm dark:text-white">${category.sort_order || '0'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Description</p>
                <p class="text-sm dark:text-white">${category.description || '-'}</p>
              </div>
            </div>
          </div>
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Settings</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Status</p>
                <p class="text-sm dark:text-white">${category.is_active ? 'Active' : 'Inactive'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Created At</p>
                <p class="text-sm dark:text-white">${new Date(category.created_at).toLocaleString()}</p>
              </div>
            </div>
          </div>
        </div>
      `;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeCategoryDetailsModal() {
    const modal = document.getElementById('categoryDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  // Edit Category Modal Functions
  function openEditCategoryModal(categoryId) {
    const category = categoriesData.find(c => String(c.category_id) === String(categoryId));
    if (!category) return;

    const modal = document.getElementById('editCategoryModal');
    const form = document.getElementById('editCategoryForm');

    form.querySelector('[name="category_id"]').value = category.category_id;
    form.querySelector('[name="category_code"]').value = category.category_code;
    form.querySelector('[name="category_name"]').value = category.category_name;
    document.getElementById('editParentId').value = category.parent_id || '';
    form.querySelector('[name="sort_order"]').value = category.sort_order || 0;
    form.querySelector('[name="description"]').value = category.description || '';

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeEditCategoryModal() {
    const modal = document.getElementById('editCategoryModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('editCategoryForm').reset();
  }

  function submitEditCategory(event) {
    event.preventDefault();

    const form = document.getElementById('editCategoryForm');
    const formData = new FormData(form);
    formData.append('action', 'edit_category');

    // Show loading state
    Swal.fire({
      title: 'Updating Category...',
      text: 'Please wait while we update the category.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

    const data = {
      category_id: formData.get('category_id'),
      category_code: formData.get('category_code'),
      category_name: formData.get('category_name'),
      parent_id: formData.get('parent_id'),
      sort_order: formData.get('sort_order'),
      description: formData.get('description'),
    };
    fetch('../api/inventory_categories.php?action=update', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      })
      .then(r => r.json())
      .then(data => {
        closeEditCategoryModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6' }).then(() => fetchCategories());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33' });
        }
      })
      .catch(() => { closeEditCategoryModal(); Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred.', confirmButtonColor: '#d33' }); });
  }

  // Delete Category Function with SweetAlert
  function deleteCategory(categoryId, categoryName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete "${categoryName}"? This action cannot be undone.`,
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
        // Show loading state
        Swal.fire({
          title: 'Deleting Category...',
          text: 'Please wait while we delete the category.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });

        fetch('../api/inventory_categories.php?action=delete', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ category_id: categoryId })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, confirmButtonColor: '#3085d6' }).then(() => fetchCategories());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33' });
            }
          })
          .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred.', confirmButtonColor: '#d33' }));
      }
    });
  }

  // Add Category Modal Functions
  function openAddCategoryModal() {
    const modal = document.getElementById('addCategoryModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeAddCategoryModal() {
    const modal = document.getElementById('addCategoryModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('addCategoryForm').reset();
  }

  function submitAddCategory(event) {
    event.preventDefault();

    const form = document.getElementById('addCategoryForm');
    const formData = new FormData(form);
    formData.append('action', 'add_category');

    // Show loading state with higher z-index
    Swal.fire({
      title: 'Adding Category...',
      text: 'Please wait while we add the category.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

    const data = {
      category_code: formData.get('category_code'),
      category_name: formData.get('category_name'),
      parent_id: formData.get('parent_id'),
      sort_order: formData.get('sort_order'),
      description: formData.get('description'),
    };
    fetch('../api/inventory_categories.php?action=create', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      })
      .then(r => r.json())
      .then(data => {
        closeAddCategoryModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6' }).then(() => fetchCategories());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33' });
        }
      })
      .catch(() => { closeAddCategoryModal(); Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred.', confirmButtonColor: '#d33' }); });
  }
</script>