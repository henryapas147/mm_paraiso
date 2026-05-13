<?php
// Helper function to generate UUID
function generateUUID()
{
  return sprintf(
    '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0x0fff) | 0x4000,
    mt_rand(0, 0x3fff) | 0x8000,
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff)
  );
}
?>

<script>
  // Data will be fetched from API on page load
  let categoriesData = [];
  let propertiesData = [];

  // Fetch data from API on page load
  document.addEventListener('DOMContentLoaded', async function() {
    await fetchCategoriesData();
  });

  async function fetchCategoriesData() {
    try {
      // Fetch categories
      const response = await fetch('../api/room_categories.php?action=list');
      const result = await response.json();
      if (result.success) {
        categoriesData = result.data;
      }

      // Fetch properties for dropdowns
      const propsResponse = await fetch('../api/properties.php?action=list');
      const propsResult = await propsResponse.json();
      if (propsResult.success) {
        propertiesData = propsResult.data;
      }

      renderCategoriesTable();
      populatePropertyDropdowns();
    } catch (error) {
      console.error('Error fetching categories:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to load categories data',
        confirmButtonColor: '#3085d6'
      });
    }
  }

  function populatePropertyDropdowns() {
    const addPropertyDropdown = document.getElementById('addPropertyDropdown');
    const editPropertyDropdown = document.getElementById('editPropertyDropdown');
    
    if (addPropertyDropdown && propertiesData.length > 0) {
      addPropertyDropdown.innerHTML = '<option value="">Select Property</option>';
      propertiesData.forEach(property => {
        const option = document.createElement('option');
        option.value = property.property_id;
        option.textContent = `${property.property_name} (${property.property_code})`;
        addPropertyDropdown.appendChild(option);
      });
    }

    if (editPropertyDropdown && propertiesData.length > 0) {
      editPropertyDropdown.innerHTML = '<option value="">Select Property</option>';
      propertiesData.forEach(property => {
        const option = document.createElement('option');
        option.value = property.property_id;
        option.textContent = `${property.property_name} (${property.property_code})`;
        editPropertyDropdown.appendChild(option);
      });
    }
  }

  function renderCategoriesTable() {
    const tbody = document.querySelector('#categoriesTableBody');
    if (!tbody) return;
    
    if (categoriesData.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">
            No categories found. <a href="javascript:;" onclick="openAddCategoryModal()" class="text-blue-500 font-semibold">Add your first category</a>.
          </td>
        </tr>
      `;
      return;
    }

    tbody.innerHTML = categoriesData.map((category) => {
      const property = propertiesData.find(p => p.property_id === category.property_id);
      
      return `
        <tr class="category-row">
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <h6 class="mb-0 text-sm leading-normal dark:text-white">${category.category_name}</h6>
            <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400">${category.category_code}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${property ? property.property_name : 'N/A'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${category.base_price_per_night || 'N/A'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${category.max_total_capacity || 'N/A'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <span class="bg-gradient-to-tl ${category.is_active ? 'from-emerald-500 to-teal-400' : 'from-gray-500 to-slate-400'} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${category.is_active ? 'Active' : 'Inactive'}</span>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <div class="flex items-center gap-2">
              <button onclick="openEditCategoryModal('${category.category_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Category">
                <i class="fas fa-edit mr-1.5 text-xs"></i>
                <span>Edit</span>
              </button>
              <button onclick="confirmDeleteCategory('${category.category_id}', '${category.category_name}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Category">
                <i class="fas fa-trash-alt mr-1.5 text-xs"></i>
                <span>Delete</span>
              </button>
            </div>
          </td>
        </tr>
      `;
    }).join('');
  }

  // Delete Category Function (API-based)
  async function confirmDeleteCategory(categoryId, categoryName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete "${categoryName}"? This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          const response = await fetch('../api/room_categories.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              action: 'delete',
              category_id: categoryId
            })
          });
          const result = await response.json();
          
          if (result.success) {
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Category deleted successfully!',
              timer: 2000,
              showConfirmButton: false
            });
            await fetchCategoriesData();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: result.message || 'Failed to delete category',
              confirmButtonColor: '#3085d6'
            });
          }
        } catch (error) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while deleting the category',
            confirmButtonColor: '#3085d6'
          });
        }
      }
    });
  }

  // Add Category Form Submission (API-based)
  async function handleAddCategorySubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    // Generate UUID for category_id
    const category_id = generateUUID();
    
    // Prepare data for API
    const data = {
      category_id: category_id,
      property_id: formData.get('property_id'),
      category_code: formData.get('category_code'),
      category_name: formData.get('category_name'),
      description: formData.get('description') || null,
      base_price_per_night: formData.get('base_price_per_night') || 0,
      base_price_per_hour: formData.get('base_price_per_hour') || 0,
      base_price_day_use: formData.get('base_price_day_use') || 0,
      minimum_hours: formData.get('minimum_hours') || 2,
      max_adult_capacity: formData.get('max_adult_capacity') || 2,
      max_child_capacity: formData.get('max_child_capacity') || 2,
      max_total_capacity: formData.get('max_total_capacity') || 4,
      extra_adult_fee: formData.get('extra_adult_fee') || 0,
      extra_child_fee: formData.get('extra_child_fee') || 0,
      extra_senior_fee: formData.get('extra_senior_fee') || 0,
      floor_area_sqm: formData.get('floor_area_sqm') || null,
      bed_configuration: formData.get('bed_configuration') || null,
      view_type: formData.get('view_type') || null,
      floor_level: formData.get('floor_level') || null,
      smoking_policy: formData.get('smoking_policy') || 'non_smoking',
      pet_friendly: formData.get('pet_friendly') || 0,
      allows_hourly: formData.get('allows_hourly') || 1,
      allows_overnight: formData.get('allows_overnight') || 1,
      allows_day_use: formData.get('allows_day_use') || 1,
      sort_order: formData.get('sort_order') || 0,
      is_active: 1
    };

    try {
      const response = await fetch('../api/room_categories.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: 'create', ...data })
      });
      const result = await response.json();
      
      if (result.success) {
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: 'Category added successfully!',
          timer: 2000,
          showConfirmButton: false
        });
        closeCategoryFormModal();
        await fetchCategoriesData();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: result.message || 'Failed to add category',
          confirmButtonColor: '#3085d6'
        });
      }
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'An error occurred while adding the category',
        confirmButtonColor: '#3085d6'
      });
    }
  }

  // Edit Category Form Submission (API-based)
  async function handleEditCategorySubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    const data = {
      category_id: formData.get('category_id'),
      property_id: formData.get('property_id'),
      category_code: formData.get('category_code'),
      category_name: formData.get('category_name'),
      description: formData.get('description') || null,
      base_price_per_night: formData.get('base_price_per_night') || 0,
      base_price_per_hour: formData.get('base_price_per_hour') || 0,
      base_price_day_use: formData.get('base_price_day_use') || 0,
      minimum_hours: formData.get('minimum_hours') || 2,
      max_adult_capacity: formData.get('max_adult_capacity') || 2,
      max_child_capacity: formData.get('max_child_capacity') || 2,
      max_total_capacity: formData.get('max_total_capacity') || 4,
      extra_adult_fee: formData.get('extra_adult_fee') || 0,
      extra_child_fee: formData.get('extra_child_fee') || 0,
      extra_senior_fee: formData.get('extra_senior_fee') || 0,
      floor_area_sqm: formData.get('floor_area_sqm') || null,
      bed_configuration: formData.get('bed_configuration') || null,
      view_type: formData.get('view_type') || null,
      floor_level: formData.get('floor_level') || null,
      smoking_policy: formData.get('smoking_policy') || 'non_smoking',
      pet_friendly: formData.get('pet_friendly') || 0,
      allows_hourly: formData.get('allows_hourly') || 1,
      allows_overnight: formData.get('allows_overnight') || 1,
      allows_day_use: formData.get('allows_day_use') || 1,
      sort_order: formData.get('sort_order') || 0,
      is_active: formData.get('is_active') || 1
    };

    try {
      const response = await fetch('../api/room_categories.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: 'update', ...data })
      });
      const result = await response.json();
      
      if (result.success) {
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: 'Category updated successfully!',
          timer: 2000,
          showConfirmButton: false
        });
        closeCategoryFormModal();
        await fetchCategoriesData();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: result.message || 'Failed to update category',
          confirmButtonColor: '#3085d6'
        });
      }
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'An error occurred while updating the category',
        confirmButtonColor: '#3085d6'
      });
    }
  }
</script>

<!-- PAGE CONTENT -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Room Categories</h6>
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
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Capacity</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Base Price (Night)</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="categoriesTableBody">
                    <!-- Table rows will be populated by JavaScript -->
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

      <!-- Add/Edit Category Modal -->
      <div id="categoryFormModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCategoryFormModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white" id="formModalTitle">Add Category</h3>
            <button onclick="closeCategoryFormModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white">
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>
          <form id="addCategoryForm" class="p-6" onsubmit="handleAddCategorySubmit(event)">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Basic Information -->
              <div class="md:col-span-2">
                <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b pb-2">Basic Information</h4>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Property *</label>
                <select name="property_id" id="addPropertyDropdown" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
                  <option value="">Select Property</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Category Code *</label>
                <input type="text" name="category_code" id="formCategoryCode" required maxlength="30" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500" placeholder="e.g. STD, DLX, STE">
              </div>

              <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Category Name *</label>
                <input type="text" name="category_name" id="formCategoryName" required maxlength="100" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500" placeholder="e.g. Standard Room, Deluxe Suite">
              </div>

              <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Description</label>
                <textarea name="description" id="formDescription" rows="3" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500" placeholder="Category description..."></textarea>
              </div>

              <!-- Pricing -->
              <div class="md:col-span-2 mt-4">
                <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b pb-2">Pricing</h4>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Base Price Per Night (PHP)</label>
                <input type="number" name="base_price_per_night" id="formBasePriceNight" step="0.01" min="0" value="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Base Price Per Hour (PHP)</label>
                <input type="number" name="base_price_per_hour" id="formBasePriceHour" step="0.01" min="0" value="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Base Price Day Use (PHP)</label>
                <input type="number" name="base_price_day_use" id="formBasePriceDayUse" step="0.01" min="0" value="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Minimum Hours</label>
                <input type="number" name="minimum_hours" id="formMinimumHours" min="1" max="24" value="2" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <!-- Capacity -->
              <div class="md:col-span-2 mt-4">
                <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b pb-2">Capacity</h4>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Max Adult Capacity</label>
                <input type="number" name="max_adult_capacity" id="formMaxAdultCapacity" min="0" value="2" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Max Child Capacity</label>
                <input type="number" name="max_child_capacity" id="formMaxChildCapacity" min="0" value="2" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Max Total Capacity</label>
                <input type="number" name="max_total_capacity" id="formMaxTotalCapacity" min="0" value="4" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div></div>

              <!-- Extra Fees -->
              <div class="md:col-span-2 mt-4">
                <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b pb-2">Extra Fees</h4>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Extra Adult Fee (PHP)</label>
                <input type="number" name="extra_adult_fee" id="formExtraAdultFee" step="0.01" min="0" value="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Extra Child Fee (PHP)</label>
                <input type="number" name="extra_child_fee" id="formExtraChildFee" step="0.01" min="0" value="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Extra Senior Fee (PHP)</label>
                <input type="number" name="extra_senior_fee" id="formExtraSeniorFee" step="0.01" min="0" value="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div></div>

              <!-- Physical Details -->
              <div class="md:col-span-2 mt-4">
                <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b pb-2">Physical Details</h4>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Floor Area (sqm)</label>
                <input type="number" name="floor_area_sqm" id="formFloorArea" step="0.01" min="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Bed Configuration</label>
                <input type="text" name="bed_configuration" id="formBedConfiguration" maxlength="150" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500" placeholder="e.g. 1 King Bed + 1 Sofa Bed">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">View Type</label>
                <input type="text" name="view_type" id="formViewType" maxlength="60" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500" placeholder="e.g. ocean, mountain, garden">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Floor Level</label>
                <input type="text" name="floor_level" id="formFloorLevel" maxlength="30" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <!-- Policies -->
              <div class="md:col-span-2 mt-4">
                <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b pb-2">Policies</h4>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Smoking Policy</label>
                <select name="smoking_policy" id="formSmokingPolicy" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
                  <option value="non_smoking">Non Smoking</option>
                  <option value="smoking">Smoking</option>
                  <option value="designated_area">Designated Area</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Pet Friendly</label>
                <select name="pet_friendly" id="formPetFriendly" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
                  <option value="0">No</option>
                  <option value="1">Yes</option>
                </select>
              </div>

              <!-- Booking Options -->
              <div class="md:col-span-2 mt-4">
                <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b pb-2">Booking Options</h4>
              </div>

              <div>
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="allows_hourly" id="formAllowsHourly" value="1" checked class="rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                  <span class="text-xs font-semibold text-slate-500 dark:text-white/60">Allows Hourly</span>
                </label>
              </div>

              <div>
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="allows_overnight" id="formAllowsOvernight" value="1" checked class="rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                  <span class="text-xs font-semibold text-slate-500 dark:text-white/60">Allows Overnight</span>
                </label>
              </div>

              <div>
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="allows_day_use" id="formAllowsDayUse" value="1" checked class="rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                  <span class="text-xs font-semibold text-slate-500 dark:text-white/60">Allows Day Use</span>
                </label>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Sort Order</label>
                <input type="number" name="sort_order" id="formSortOrder" min="0" value="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
              <button type="button" onclick="closeCategoryFormModal()" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
              <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors">Save Category</button>
            </div>
          </form>
        </div>
      </div>

    </div>
    <!-- end cards -->
<script>
  function viewCategoryDetails(categoryId) {
    const category = categoriesData.find(c => c.category_id === categoryId);
    if (!category) return;

    const modal = document.getElementById('categoryDetailsModal');
    const modalName = document.getElementById('modalCategoryName');
    const modalContent = document.getElementById('modalCategoryContent');

    modalName.textContent = category.category_name;

    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Basic Information</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Category Code</p>
                <p class="text-sm font-semibold dark:text-white">${category.category_code}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Category Name</p>
                <p class="text-sm dark:text-white">${category.category_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Description</p>
                <p class="text-sm dark:text-white">${category.description || '-'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Property</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Property Name</p>
                <p class="text-sm font-semibold dark:text-white">${category.property_name}</p>
                <p class="text-xs text-slate-400 dark:text-white/60">${category.property_code}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Pricing</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Base Price Per Night</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(category.base_price_per_night || 0).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Base Price Per Hour</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(category.base_price_per_hour || 0).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Base Price Day Use</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(category.base_price_day_use || 0).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Minimum Hours</p>
                <p class="text-sm dark:text-white">${category.minimum_hours || 2} hours</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Capacity</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Max Adult Capacity</p>
                <p class="text-sm font-semibold dark:text-white">${category.max_adult_capacity} adults</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Max Child Capacity</p>
                <p class="text-sm font-semibold dark:text-white">${category.max_child_capacity} children</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Max Total Capacity</p>
                <p class="text-sm font-semibold dark:text-white">${category.max_total_capacity} guests</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Extra Fees</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Extra Adult Fee</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(category.extra_adult_fee || 0).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Extra Child Fee</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(category.extra_child_fee || 0).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Extra Senior Fee</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(category.extra_senior_fee || 0).toFixed(2)}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Physical Details</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Floor Area</p>
                <p class="text-sm dark:text-white">${category.floor_area_sqm ? category.floor_area_sqm + ' sqm' : '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Bed Configuration</p>
                <p class="text-sm dark:text-white">${category.bed_configuration || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">View Type</p>
                <p class="text-sm dark:text-white">${category.view_type || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Floor Level</p>
                <p class="text-sm dark:text-white">${category.floor_level || '-'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Policies</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Smoking Policy</p>
                <p class="text-sm dark:text-white">${category.smoking_policy ? category.smoking_policy.replace(/_/g, ' ').toUpperCase() : 'NON SMOKING'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Pet Friendly</p>
                <p class="text-sm dark:text-white">${category.pet_friendly ? 'Yes' : 'No'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Booking Options</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Allows Hourly</p>
                <p class="text-sm dark:text-white">${category.allows_hourly ? 'Yes' : 'No'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Allows Overnight</p>
                <p class="text-sm dark:text-white">${category.allows_overnight ? 'Yes' : 'No'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Allows Day Use</p>
                <p class="text-sm dark:text-white">${category.allows_day_use ? 'Yes' : 'No'}</p>
              </div>
            </div>
          </div>

          <div class="md:col-span-2">
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">System Information</h4>
            <div class="grid grid-cols-3 gap-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Sort Order</p>
                <p class="text-sm dark:text-white">${category.sort_order || 0}</p>
              </div>
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

  // Add Category Modal Functions
  function openAddCategoryModal() {
    const modal = document.getElementById('categoryFormModal');
    const form = document.getElementById('categoryForm');
    const title = document.getElementById('formModalTitle');

    // Reset form
    form.reset();
    document.getElementById('formAction').value = 'add_category';
    document.getElementById('formCategoryId').value = '';
    title.textContent = 'Add Category';

    // Set default checkbox values
    document.getElementById('formAllowsHourly').checked = true;
    document.getElementById('formAllowsOvernight').checked = true;
    document.getElementById('formAllowsDayUse').checked = true;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  // Edit Category Modal Functions
  function openEditCategoryModal(categoryId) {
    const category = categoriesData.find(c => c.category_id === categoryId);
    if (!category) return;

    const modal = document.getElementById('categoryFormModal');
    const form = document.getElementById('categoryForm');
    const title = document.getElementById('formModalTitle');

    // Set form values
    document.getElementById('formAction').value = 'edit_category';
    document.getElementById('formCategoryId').value = category.category_id;
    title.textContent = 'Edit Category';

    document.getElementById('formPropertyId').value = category.property_id;
    document.getElementById('formCategoryCode').value = category.category_code;
    document.getElementById('formCategoryName').value = category.category_name;
    document.getElementById('formDescription').value = category.description || '';
    document.getElementById('formBasePriceNight').value = category.base_price_per_night || 0;
    document.getElementById('formBasePriceHour').value = category.base_price_per_hour || 0;
    document.getElementById('formBasePriceDayUse').value = category.base_price_day_use || 0;
    document.getElementById('formMinimumHours').value = category.minimum_hours || 2;
    document.getElementById('formMaxAdultCapacity').value = category.max_adult_capacity || 2;
    document.getElementById('formMaxChildCapacity').value = category.max_child_capacity || 2;
    document.getElementById('formMaxTotalCapacity').value = category.max_total_capacity || 4;
    document.getElementById('formExtraAdultFee').value = category.extra_adult_fee || 0;
    document.getElementById('formExtraChildFee').value = category.extra_child_fee || 0;
    document.getElementById('formExtraSeniorFee').value = category.extra_senior_fee || 0;
    document.getElementById('formFloorArea').value = category.floor_area_sqm || '';
    document.getElementById('formBedConfiguration').value = category.bed_configuration || '';
    document.getElementById('formViewType').value = category.view_type || '';
    document.getElementById('formFloorLevel').value = category.floor_level || '';
    document.getElementById('formSmokingPolicy').value = category.smoking_policy || 'non_smoking';
    document.getElementById('formPetFriendly').value = category.pet_friendly || '0';
    document.getElementById('formAllowsHourly').checked = category.allows_hourly == 1;
    document.getElementById('formAllowsOvernight').checked = category.allows_overnight == 1;
    document.getElementById('formAllowsDayUse').checked = category.allows_day_use == 1;
    document.getElementById('formSortOrder').value = category.sort_order || 0;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeCategoryFormModal() {
    const modal = document.getElementById('categoryFormModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  // Delete Category Function with SweetAlert
  function deleteCategory(categoryId, categoryName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to delete "${categoryName}". This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'room_categories.php';

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete_category';

        const categoryIdInput = document.createElement('input');
        categoryIdInput.type = 'hidden';
        categoryIdInput.name = 'category_id';
        categoryIdInput.value = categoryId;

        form.appendChild(actionInput);
        form.appendChild(categoryIdInput);
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  // Show SweetAlert on page load for success/error messages
  <?php if (isset($_GET['success'])): ?>
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: <?php
            switch ($_GET['success']) {
              case 'category_added':
                echo "'Category added successfully!'";
                break;
              case 'category_updated':
                echo "'Category updated successfully!'";
                break;
              case 'category_deleted':
                echo "'Category deleted successfully!'";
                break;
              default:
                echo "'Operation completed successfully!'";
            }
            ?>,
      timer: 2000,
      showConfirmButton: false
    });
  <?php endif; ?>

  <?php if (isset($error_message)): ?>
    Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: <?php echo json_encode($error_message); ?>,
      confirmButtonColor: '#3085d6'
    });
  <?php endif; ?>

  // Filter table function
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('categoriesTable');
    const rows = table.getElementsByClassName('category-row');

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
</script>