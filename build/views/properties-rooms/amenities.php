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
  let amenitiesData = [];

  // Fetch data from API on page load
  document.addEventListener('DOMContentLoaded', async function() {
    await fetchAmenitiesData();
  });

  async function fetchAmenitiesData() {
    try {
      const response = await fetch('../api/amenities.php?action=list');
      const result = await response.json();
      if (result.success) {
        amenitiesData = result.data;
        renderAmenitiesTable();
        updateStatsCounts();
      }
    } catch (error) {
      console.error('Error fetching amenities:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to load amenities data',
        confirmButtonColor: '#3085d6'
      });
    }
  }

  function updateStatsCounts() {
    const totalCount = amenitiesData.length;
    const comfortCount = amenitiesData.filter(a => a.amenity_type === 'comfort').length;
    const techCount = amenitiesData.filter(a => a.amenity_type === 'technology').length;
    const safetyCount = amenitiesData.filter(a => a.amenity_type === 'safety').length;

    const totalEl = document.getElementById('totalAmenitiesCount');
    const comfortEl = document.getElementById('comfortAmenitiesCount');
    const techEl = document.getElementById('techAmenitiesCount');
    const safetyEl = document.getElementById('safetyAmenitiesCount');

    if (totalEl) totalEl.textContent = totalCount;
    if (comfortEl) comfortEl.textContent = comfortCount;
    if (techEl) techEl.textContent = techCount;
    if (safetyEl) safetyEl.textContent = safetyCount;
  }

  function renderAmenitiesTable() {
    const tbody = document.querySelector('#amenitiesTableBody');
    if (!tbody) return;
    
    if (amenitiesData.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="5" class="p-4 text-center text-slate-400 dark:text-white/60">
            No amenities found. <a href="javascript:;" onclick="openAddAmenityModal()" class="text-blue-500 font-semibold">Add your first amenity</a>.
          </td>
        </tr>
      `;
      return;
    }

    tbody.innerHTML = amenitiesData.map((amenity) => {
      const iconHtml = amenity.icon ? `<i class="${amenity.icon} mr-2"></i>` : '';
      
      return `
        <tr class="amenity-row">
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <div class="flex items-center">
              ${iconHtml}
              <h6 class="mb-0 text-sm leading-normal dark:text-white">${amenity.amenity_name}</h6>
            </div>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${amenity.amenity_code || 'N/A'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${amenity.amenity_type || 'general'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <span class="bg-gradient-to-tl ${amenity.is_active ? 'from-emerald-500 to-teal-400' : 'from-gray-500 to-slate-400'} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${amenity.is_active ? 'Active' : 'Inactive'}</span>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <div class="flex items-center gap-2">
              <button onclick="openEditAmenityModal('${amenity.amenity_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Amenity">
                <i class="fas fa-edit mr-1.5 text-xs"></i>
                <span>Edit</span>
              </button>
              <button onclick="confirmDeleteAmenity('${amenity.amenity_id}', '${amenity.amenity_name}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Amenity">
                <i class="fas fa-trash-alt mr-1.5 text-xs"></i>
                <span>Delete</span>
              </button>
            </div>
          </td>
        </tr>
      `;
    }).join('');
  }

  // Delete Amenity Function (API-based)
  async function confirmDeleteAmenity(amenityId, amenityName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete "${amenityName}"? This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          const response = await fetch('../api/amenities.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              action: 'delete',
              amenity_id: amenityId
            })
          });
          const result = await response.json();
          
          if (result.success) {
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Amenity deleted successfully!',
              timer: 2000,
              showConfirmButton: false
            });
            await fetchAmenitiesData();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: result.message || 'Failed to delete amenity',
              confirmButtonColor: '#3085d6'
            });
          }
        } catch (error) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while deleting the amenity',
            confirmButtonColor: '#3085d6'
          });
        }
      }
    });
  }

  // Add Amenity Form Submission (API-based)
  async function handleAddAmenitySubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    // Generate UUID for amenity_id
    const amenity_id = generateUUID();
    
    // Prepare data for API
    const data = {
      amenity_id: amenity_id,
      amenity_code: formData.get('amenity_code'),
      amenity_name: formData.get('amenity_name'),
      amenity_type: formData.get('amenity_type') || 'general',
      icon: formData.get('icon') || null,
      description: formData.get('description') || null,
      is_active: formData.get('is_active') || 1,
      sort_order: formData.get('sort_order') || 0
    };

    try {
      const response = await fetch('../api/amenities.php', {
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
          text: 'Amenity added successfully!',
          timer: 2000,
          showConfirmButton: false
        });
        closeAddAmenityModal();
        await fetchAmenitiesData();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: result.message || 'Failed to add amenity',
          confirmButtonColor: '#3085d6'
        });
      }
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'An error occurred while adding the amenity',
        confirmButtonColor: '#3085d6'
      });
    }
  }

  // Edit Amenity Form Submission (API-based)
  async function handleEditAmenitySubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    const data = {
      amenity_id: formData.get('amenity_id'),
      amenity_code: formData.get('amenity_code'),
      amenity_name: formData.get('amenity_name'),
      amenity_type: formData.get('amenity_type') || 'general',
      icon: formData.get('icon') || null,
      description: formData.get('description') || null,
      is_active: formData.get('is_active') || 1,
      sort_order: formData.get('sort_order') || 0
    };

    try {
      const response = await fetch('../api/amenities.php', {
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
          text: 'Amenity updated successfully!',
          timer: 2000,
          showConfirmButton: false
        });
        closeEditAmenityModal();
        await fetchAmenitiesData();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: result.message || 'Failed to update amenity',
          confirmButtonColor: '#3085d6'
        });
      }
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'An error occurred while updating the amenity',
        confirmButtonColor: '#3085d6'
      });
    }
  }
</script>

<!-- PAGE CONTENT -->
    <!-- cards -->
    <div class="w-full px-6 py-6 mx-auto">
      <!-- PAGE CONTENT -->
      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Amenities</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="totalAmenitiesCount">0</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Comfort</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="comfortAmenitiesCount">0</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Technology</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="techAmenitiesCount">0</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Safety</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="safetyAmenitiesCount">0</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Amenities Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Amenities</h6>
              <button onclick="openAddAmenityModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Add Amenity
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="amenitiesTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Amenity</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Icon Code</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Category</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="amenitiesTableBody">
                    <!-- Table rows will be populated by JavaScript -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add Amenity Modal -->
      <div id="addAmenityModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddAmenityModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Add New Amenity</h3>
            <button onclick="closeAddAmenityModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form id="addAmenityForm" class="p-6" onsubmit="handleAddAmenitySubmit(event)">
              <div class="space-y-4">
                <div>
                  <label for="amenity_name" class="block text-sm font-medium text-gray-700">Amenity Name</label>
                  <input type="text" name="amenity_name" id="amenity_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="icon_code" class="block text-sm font-medium text-gray-700">Icon Code</label>
                  <input type="text" name="icon_code" id="icon_code" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., fa-wifi">
                </div>
                <div>
                  <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                  <select name="category" id="category" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="comfort">Comfort</option>
                    <option value="technology">Technology</option>
                    <option value="bathroom">Bathroom</option>
                    <option value="kitchen">Kitchen</option>
                    <option value="outdoor">Outdoor</option>
                    <option value="safety">Safety</option>
                    <option value="accessibility">Accessibility</option>
                    <option value="entertainment">Entertainment</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                  <input type="number" name="sort_order" id="sort_order" value="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                  </label>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                <button type="button" onclick="closeAddAmenityModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Add Amenity
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Edit Amenity Modal -->
      <div id="editAmenityModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditAmenityModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Edit Amenity</h3>
            <button onclick="closeEditAmenityModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form id="editAmenityForm" class="p-6" onsubmit="handleEditAmenitySubmit(event)">
              <input type="hidden" name="amenity_id" id="edit_amenity_id">
              <div class="space-y-4">
                <div>
                  <label for="edit_amenity_name" class="block text-sm font-medium text-gray-700">Amenity Name</label>
                  <input type="text" name="amenity_name" id="edit_amenity_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_icon_code" class="block text-sm font-medium text-gray-700">Icon Code</label>
                  <input type="text" name="icon_code" id="edit_icon_code" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., fa-wifi">
                </div>
                <div>
                  <label for="edit_category" class="block text-sm font-medium text-gray-700">Category</label>
                  <select name="category" id="edit_category" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="comfort">Comfort</option>
                    <option value="technology">Technology</option>
                    <option value="bathroom">Bathroom</option>
                    <option value="kitchen">Kitchen</option>
                    <option value="outdoor">Outdoor</option>
                    <option value="safety">Safety</option>
                    <option value="accessibility">Accessibility</option>
                    <option value="entertainment">Entertainment</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label for="edit_sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                  <input type="number" name="sort_order" id="edit_sort_order" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" id="edit_is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                  </label>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                <button type="button" onclick="closeEditAmenityModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Update Amenity
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- View Amenity Modal -->
      <div id="viewAmenityModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeViewAmenityModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Amenity Details</h3>
            <button onclick="closeViewAmenityModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-500">Amenity Name</label>
                <p id="view_amenity_name" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Icon Code</label>
                <p id="view_icon_code" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Category</label>
                <p id="view_category" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Sort Order</label>
                <p id="view_sort_order" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Status</label>
                <p id="view_is_active" class="mt-1 text-sm text-gray-900"></p>
              </div>
            </div>
          </div>
          <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end flex-shrink-0" style="padding: 1.5rem; border-top: 1px solid #e5e7eb;">
            <button type="button" onclick="closeViewAmenityModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Form -->

<script>
    // Filter table function
    function filterTable() {
      const input = document.getElementById('searchInput');
      const filter = input.value.toLowerCase();
      const table = document.getElementById('amenitiesTable');
      const rows = table.getElementsByClassName('amenity-row');

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

  // Modal functions
  function openAddAmenityModal() {
    const modal = document.getElementById('addAmenityModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }

  function closeAddAmenityModal() {
    const modal = document.getElementById('addAmenityModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    // Reset form
    const form = modal.querySelector('form');
    if (form) {
      form.reset();
    }
  }

  function openEditAmenityModal(amenityId) {
    const row = document.querySelector(`tr[data-amenity-id="${amenityId}"]`);
    if (row) {
      document.getElementById('edit_amenity_id').value = amenityId;
      document.getElementById('edit_amenity_name').value = row.dataset.amenityName;
      document.getElementById('edit_icon_code').value = row.dataset.amenityIcon;
      document.getElementById('edit_category').value = row.dataset.amenityCategory;
      document.getElementById('edit_sort_order').value = row.dataset.amenitySort;
      document.getElementById('edit_is_active').checked = row.dataset.amenityActive === '1';

      const modal = document.getElementById('editAmenityModal');
      modal.classList.remove('hidden');
      modal.style.display = 'block';
      document.body.style.overflow = 'hidden';
    }
  }

  function closeEditAmenityModal() {
    const modal = document.getElementById('editAmenityModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';

    // Reset form
    const form = modal.querySelector('form');
    if (form) {
      form.reset();
    }
  }

  function openViewAmenityModal(amenityId) {
    const row = document.querySelector(`tr[data-amenity-id="${amenityId}"]`);
    if (row) {
      document.getElementById('view_amenity_name').textContent = row.dataset.amenityName;
      document.getElementById('view_icon_code').textContent = row.dataset.amenityIcon || '-';
      document.getElementById('view_category').textContent = row.dataset.amenityCategory;
      document.getElementById('view_sort_order').textContent = row.dataset.amenitySort;
      document.getElementById('view_is_active').textContent = row.dataset.amenityActive === '1' ? 'Active' : 'Inactive';

      const modal = document.getElementById('viewAmenityModal');
      modal.classList.remove('hidden');
      modal.style.display = 'block';
      document.body.style.overflow = 'hidden';
    }
  }

  function closeViewAmenityModal() {
    const modal = document.getElementById('viewAmenityModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
  }

  function confirmDeleteAmenity(amenityId) {
    const row = document.querySelector(`tr[data-amenity-id="${amenityId}"]`);
    const amenityName = row ? row.dataset.amenityName : 'this amenity';

    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to delete "${amenityName}". This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('delete_amenity_id').value = amenityId;
        document.getElementById('deleteAmenityForm').submit();
      }
    });
  }

  // Show success/error messages (wrapped in DOMContentLoaded to ensure SweetAlert is loaded)
  document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_GET['success'])): ?>
      <?php if ($_GET['success'] === 'amenity_added'): ?>
        Swal.fire({
          title: 'Success!',
          text: 'Amenity has been added successfully.',
          icon: 'success',
          confirmButtonColor: '#3085d6'
        });
      <?php elseif ($_GET['success'] === 'amenity_updated'): ?>
        Swal.fire({
          title: 'Success!',
          text: 'Amenity has been updated successfully.',
          icon: 'success',
          confirmButtonColor: '#3085d6'
        });
      <?php elseif ($_GET['success'] === 'amenity_deleted'): ?>
        Swal.fire({
          title: 'Deleted!',
          text: 'Amenity has been deleted successfully.',
          icon: 'success',
          confirmButtonColor: '#3085d6'
        });
      <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
      Swal.fire({
        title: 'Error!',
        text: '<?php echo addslashes($error_message); ?>',
        icon: 'error',
        confirmButtonColor: '#3085d6'
      });
    <?php endif; ?>
  });
</script>

</html>