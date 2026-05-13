<?php if (false) {} ?>
<style>
  .swal2-popup {
    border-radius: 1rem !important;
  }

  .swal2-confirm {
    background-color: #3085d6 !important;
    color: white !important;
    border: none !important;
    padding: 0.75rem 1.5rem !important;
    border-radius: 0.5rem !important;
    font-weight: 600 !important;
  }

  .swal2-cancel {
    background-color: #d33 !important;
    color: white !important;
    border: none !important;
    padding: 0.75rem 1.5rem !important;
    border-radius: 0.5rem !important;
    font-weight: 600 !important;
  }
</style>
<!-- PAGE CONTENT -->

      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Locations</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statTotal">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Main Warehouses</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statWarehouse">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Kitchens</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statKitchen">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Bars</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statBar">-</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Locations Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Locations</h6>
              <button onclick="openAddLocationModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Add Location
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="locationsTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Location</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Description</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="locationsTableBody">
                    <tr><td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Location Details Modal -->
      <div id="locationDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeLocationDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white" id="modalLocationName" style="font-size: 1.25rem; font-weight: bold; margin: 0;">Location Details</h3>
            <button onclick="closeLocationDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6" id="modalLocationContent" style="padding: 1.5rem;">
            <!-- Content will be populated by JavaScript -->
          </div>
        </div>
      </div>

      <!-- Add Location Modal -->
      <div id="addLocationModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddLocationModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Add New Location</h3>
            <button onclick="closeAddLocationModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addLocationForm" onsubmit="submitAddLocation(event)">
            <div class="p-6">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Property <span class="text-red-500">*</span></label>
                  <select name="property_id" id="addPropertyId" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Property</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Location Name <span class="text-red-500">*</span></label>
                  <input type="text" name="location_name" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Main Stockroom">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Location Type <span class="text-red-500">*</span></label>
                  <select name="location_type" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Type</option>
                    <option value="main_warehouse">Main Warehouse</option>
                    <option value="kitchen">Kitchen</option>
                    <option value="bar">Bar</option>
                    <option value="housekeeping">Housekeeping</option>
                    <option value="front_desk">Front Desk</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Description</label>
                  <textarea name="description" rows="3" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Optional description..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeAddLocationModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Save Location
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Location Modal -->
      <div id="editLocationModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditLocationModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Location</h3>
            <button onclick="closeEditLocationModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="editLocationForm" onsubmit="submitEditLocation(event)">
            <input type="hidden" name="location_id" id="editLocationId">
            <div class="p-6">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Property <span class="text-red-500">*</span></label>
                  <select name="property_id" id="editPropertyId" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Property</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Location Name <span class="text-red-500">*</span></label>
                  <input type="text" name="location_name" id="editLocationName" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Main Stockroom">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Location Type <span class="text-red-500">*</span></label>
                  <select name="location_type" id="editLocationType" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Type</option>
                    <option value="main_warehouse">Main Warehouse</option>
                    <option value="kitchen">Kitchen</option>
                    <option value="bar">Bar</option>
                    <option value="housekeeping">Housekeeping</option>
                    <option value="front_desk">Front Desk</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Description</label>
                  <textarea name="description" id="editLocationDescription" rows="3" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Optional description..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeEditLocationModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Update Location
              </button>
            </div>
          </form>
        </div>
      </div>

<script>
  let locationsData = [];
  let propertiesData = [];

  document.addEventListener('DOMContentLoaded', function () {
    fetchLocations();
  });

  async function fetchLocations() {
    try {
      const [locRes, propRes] = await Promise.all([
        fetch('../api/inventory_locations.php?action=list'),
        fetch('../api/inventory_locations.php?action=properties')
      ]);
      const locResult = await locRes.json();
      const propResult = await propRes.json();
      if (locResult.success) {
        locationsData = locResult.data;
        renderTable();
        renderStats();
      } else {
        document.getElementById('locationsTableBody').innerHTML = `<tr><td colspan="6" class="p-4 text-center text-red-400">${locResult.message}</td></tr>`;
      }
      if (propResult.success) {
        propertiesData = propResult.data;
        populatePropertyDropdowns();
      }
    } catch (e) {
      document.getElementById('locationsTableBody').innerHTML = `<tr><td colspan="6" class="p-4 text-center text-red-400">Failed to load data</td></tr>`;
    }
  }

  function populatePropertyDropdowns() {
    const opts = '<option value="">Select Property</option>' +
      propertiesData.map(p => `<option value="${p.property_id}">${p.property_name}</option>`).join('');
    document.getElementById('addPropertyId').innerHTML = opts;
    document.getElementById('editPropertyId').innerHTML = opts;
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = locationsData.length;
    document.getElementById('statWarehouse').textContent = locationsData.filter(l => l.location_type === 'main_warehouse').length;
    document.getElementById('statKitchen').textContent = locationsData.filter(l => l.location_type === 'kitchen').length;
    document.getElementById('statBar').textContent = locationsData.filter(l => l.location_type === 'bar').length;
  }

  function renderTable() {
    const tbody = document.getElementById('locationsTableBody');
    if (!locationsData.length) {
      tbody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">No locations found. <a href="javascript:;" onclick="openAddLocationModal()" class="text-blue-500 font-semibold">Add your first location</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = locationsData.map(l => `
      <tr class="location-row">
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex px-2 py-1">
            <div class="inline-flex items-center justify-center mr-4 text-sm text-white h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500"><i class="fas fa-map-marker-alt"></i></div>
            <div class="flex flex-col justify-center"><h6 class="mb-0 text-sm leading-normal dark:text-white">${l.location_name}</h6></div>
          </div>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${l.property_name || '-'}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${ucfirst((l.location_type || '').replace(/_/g, ' '))}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-80">${l.description ? l.description.substring(0, 50) + (l.description.length > 50 ? '...' : '') : '-'}</p>
        </td>
        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center font-bold uppercase leading-none text-white">Active</span>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex items-center gap-2">
            <button onclick="viewLocationDetails(${l.location_id})" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-all duration-200 shadow-sm hover:shadow-md"><i class="fas fa-info-circle mr-1.5 text-xs"></i><span>View</span></button>
            <button onclick="openEditLocationModal(${l.location_id})" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 hover:text-amber-600 hover:border-amber-300 transition-all duration-200 shadow-sm hover:shadow-md"><i class="fas fa-edit mr-1.5 text-xs"></i><span>Edit</span></button>
            <button onclick="deleteLocation(${l.location_id}, '${(l.location_name || '').replace(/'/g, "\\'")}}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 hover:text-red-700 hover:border-red-300 transition-all duration-200 shadow-sm hover:shadow-md"><i class="fas fa-trash-alt mr-1.5 text-xs"></i><span>Delete</span></button>
          </div>
        </td>
      </tr>`).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#locationsTable .location-row').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
  }

  function ucfirst(str) {
    return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
  }

  function viewLocationDetails(locationId) {
    const location = locationsData.find(l => l.location_id == locationId);
    if (!location) return;

    const modal = document.getElementById('locationDetailsModal');
    const modalTitle = document.getElementById('modalLocationName');
    const modalContent = document.getElementById('modalLocationContent');

    modalTitle.textContent = location.location_name;

    modalContent.innerHTML = `
        <div class="space-y-4">
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Basic Information</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Location Name</p>
                <p class="text-sm dark:text-white">${location.location_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Property</p>
                <p class="text-sm dark:text-white">${location.property_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Location Type</p>
                <p class="text-sm dark:text-white">${ucfirst(location.location_type.replace(/_/g, ' '))}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Description</p>
                <p class="text-sm dark:text-white">${location.description || '-'}</p>
              </div>
            </div>
          </div>
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Settings</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Status</p>
                <p class="text-sm dark:text-white">${location.is_active ? 'Active' : 'Inactive'}</p>
              </div>
            </div>
          </div>
        </div>
      `;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeLocationDetailsModal() {
    const modal = document.getElementById('locationDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  // Add Location Modal Functions
  function openAddLocationModal() {
    const modal = document.getElementById('addLocationModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeAddLocationModal() {
    const modal = document.getElementById('addLocationModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('addLocationForm').reset();
  }

  function submitAddLocation(event) {
    event.preventDefault();

    const form = document.getElementById('addLocationForm');
    const formData = new FormData(form);
    formData.append('action', 'add_location');

    // Show loading state with higher z-index
    Swal.fire({
      title: 'Adding Location...',
      text: 'Please wait while we add the location.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

    const payload = { property_id: formData.get('property_id'), location_name: formData.get('location_name'), location_type: formData.get('location_type'), description: formData.get('description') };
    fetch('../api/inventory_locations.php?action=create', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddLocationModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6' }).then(() => fetchLocations());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33' });
        }
      })
      .catch(() => { closeAddLocationModal(); Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred.', confirmButtonColor: '#d33' }); });
  }

  // Edit Location Modal Functions
  function openEditLocationModal(locationId) {
    const location = locationsData.find(l => l.location_id == locationId);
    if (!location) return;

    // Populate form with existing data
    document.getElementById('editLocationId').value = location.location_id;
    document.getElementById('editPropertyId').value = location.property_id;
    document.getElementById('editLocationName').value = location.location_name;
    document.getElementById('editLocationType').value = location.location_type;
    document.getElementById('editLocationDescription').value = location.description || '';

    const modal = document.getElementById('editLocationModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeEditLocationModal() {
    const modal = document.getElementById('editLocationModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('editLocationForm').reset();
  }

  function submitEditLocation(event) {
    event.preventDefault();

    const form = document.getElementById('editLocationForm');
    const formData = new FormData(form);
    formData.append('action', 'edit_location');

    // Show loading state with higher z-index
    Swal.fire({
      title: 'Updating Location...',
      text: 'Please wait while we update the location.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

    const payload = { location_id: formData.get('location_id'), property_id: formData.get('property_id'), location_name: formData.get('location_name'), location_type: formData.get('location_type'), description: formData.get('description') };
    fetch('../api/inventory_locations.php?action=update', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditLocationModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6' }).then(() => fetchLocations());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33' });
        }
      })
      .catch(() => { closeEditLocationModal(); Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred.', confirmButtonColor: '#d33' }); });
  }

  // Delete Location Function with SweetAlert
  function deleteLocation(locationId, locationName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete "${locationName}"? This action cannot be undone.`,
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
          title: 'Deleting Location...',
          text: 'Please wait while we delete the location.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });

        fetch('../api/inventory_locations.php?action=delete', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ location_id: locationId })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, confirmButtonColor: '#3085d6' }).then(() => fetchLocations());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33' });
            }
          })
          .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred.', confirmButtonColor: '#d33' }));
      }
    });
  }
</script>