<?php if (false) {} ?>

<!-- PAGE CONTENT -->

      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total UOMs</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statTotal">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Count Type</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statCount">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Weight Type</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statWeight">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Volume Type</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statVolume">-</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- UOM Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Units of Measure</h6>
              <button onclick="openAddUomModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Add UOM
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="uomsTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Unit</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Code</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="uomsTableBody">
                    <tr><td colspan="5" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- UOM Details Modal -->
      <div id="uomDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeUomDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white" id="modalUomName" style="font-size: 1.25rem; font-weight: bold; margin: 0;">UOM Details</h3>
            <button onclick="closeUomDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6" id="modalUomContent" style="padding: 1.5rem;">
            <!-- Content will be populated by JavaScript -->
          </div>
        </div>
      </div>

      <!-- Add UOM Modal -->
      <div id="addUomModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddUomModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Add New Unit of Measure</h3>
            <button onclick="closeAddUomModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addUomForm" onsubmit="submitAddUom(event)">
            <div class="p-6">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">UOM Code <span class="text-red-500">*</span></label>
                  <input type="text" name="uom_code" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., PCS, KG, LTR">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">UOM Name <span class="text-red-500">*</span></label>
                  <input type="text" name="uom_name" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Pieces, Kilograms, Liters">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">UOM Type <span class="text-red-500">*</span></label>
                  <select name="uom_type" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Type</option>
                    <option value="count">Count</option>
                    <option value="weight">Weight</option>
                    <option value="volume">Volume</option>
                    <option value="length">Length</option>
                    <option value="area">Area</option>
                    <option value="other">Other</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeAddUomModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Save UOM
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit UOM Modal -->
      <div id="editUomModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditUomModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Unit of Measure</h3>
            <button onclick="closeEditUomModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="editUomForm" onsubmit="submitEditUom(event)">
            <input type="hidden" name="uom_id" id="editUomId">
            <div class="p-6">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">UOM Code <span class="text-red-500">*</span></label>
                  <input type="text" name="uom_code" id="editUomCode" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., PCS, KG, LTR">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">UOM Name <span class="text-red-500">*</span></label>
                  <input type="text" name="uom_name" id="editUomName" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Pieces, Kilograms, Liters">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">UOM Type <span class="text-red-500">*</span></label>
                  <select name="uom_type" id="editUomType" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Type</option>
                    <option value="count">Count</option>
                    <option value="weight">Weight</option>
                    <option value="volume">Volume</option>
                    <option value="length">Length</option>
                    <option value="area">Area</option>
                    <option value="other">Other</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeEditUomModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Update UOM
              </button>
            </div>
          </form>
        </div>
      </div>

<script>
  let uomsData = [];

  document.addEventListener('DOMContentLoaded', function () {
    fetchUoms();
  });

  async function fetchUoms() {
    try {
      const res = await fetch('../api/units_of_measure.php?action=list');
      const result = await res.json();
      if (result.success) {
        uomsData = result.data;
        renderTable();
        renderStats();
      } else {
        document.getElementById('uomsTableBody').innerHTML = `<tr><td colspan="5" class="p-4 text-center text-red-400">${result.message}</td></tr>`;
      }
    } catch (e) {
      document.getElementById('uomsTableBody').innerHTML = `<tr><td colspan="5" class="p-4 text-center text-red-400">Failed to load data</td></tr>`;
    }
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = uomsData.length;
    document.getElementById('statCount').textContent = uomsData.filter(u => u.uom_type === 'count').length;
    document.getElementById('statWeight').textContent = uomsData.filter(u => u.uom_type === 'weight').length;
    document.getElementById('statVolume').textContent = uomsData.filter(u => u.uom_type === 'volume').length;
  }

  function renderTable() {
    const tbody = document.getElementById('uomsTableBody');
    if (!uomsData.length) {
      tbody.innerHTML = `<tr><td colspan="5" class="p-4 text-center text-slate-400 dark:text-white/60">No units of measure found. <a href="javascript:;" onclick="openAddUomModal()" class="text-blue-500 font-semibold">Add your first unit</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = uomsData.map(u => `
      <tr class="uom-row">
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex px-2 py-1">
            <div class="inline-flex items-center justify-center mr-4 text-sm text-white h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500"><i class="fas fa-ruler"></i></div>
            <div class="flex flex-col justify-center"><h6 class="mb-0 text-sm leading-normal dark:text-white">${u.uom_name}</h6></div>
          </div>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${u.uom_code}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${ucfirst(u.uom_type)}</p>
        </td>
        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center font-bold uppercase leading-none text-white">Active</span>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex items-center gap-2">
            <button onclick="viewUomDetails(${u.uom_id})" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-all duration-200 shadow-sm hover:shadow-md"><i class="fas fa-info-circle mr-1.5 text-xs"></i><span>View</span></button>
            <button onclick="openEditUomModal(${u.uom_id})" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 hover:text-amber-600 hover:border-amber-300 transition-all duration-200 shadow-sm hover:shadow-md"><i class="fas fa-edit mr-1.5 text-xs"></i><span>Edit</span></button>
            <button onclick="deleteUom(${u.uom_id}, '${u.uom_name.replace(/'/g, "\\'")}}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 hover:text-red-700 hover:border-red-300 transition-all duration-200 shadow-sm hover:shadow-md"><i class="fas fa-trash-alt mr-1.5 text-xs"></i><span>Delete</span></button>
          </div>
        </td>
      </tr>`).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#uomsTable .uom-row').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
  }

  // View UOM Details
  function viewUomDetails(uomId) {
    const uom = uomsData.find(u => u.uom_id == uomId);
    if (!uom) return;

    const modal = document.getElementById('uomDetailsModal');
    const modalTitle = document.getElementById('modalUomName');
    const modalContent = document.getElementById('modalUomContent');

    modalTitle.textContent = uom.uom_name;

    modalContent.innerHTML = `
        <div class="space-y-4">
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Basic Information</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">UOM Code</p>
                <p class="text-sm dark:text-white">${uom.uom_code}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">UOM Name</p>
                <p class="text-sm dark:text-white">${uom.uom_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">UOM Type</p>
                <p class="text-sm dark:text-white">${ucfirst(uom.uom_type.replace(/_/g, ' '))}</p>
              </div>
            </div>
          </div>
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Settings</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Status</p>
                <p class="text-sm dark:text-white">${uom.is_active ? 'Active' : 'Inactive'}</p>
              </div>
            </div>
          </div>
        </div>
      `;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeUomDetailsModal() {
    const modal = document.getElementById('uomDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  // Add UOM Modal Functions
  function openAddUomModal() {
    const modal = document.getElementById('addUomModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeAddUomModal() {
    const modal = document.getElementById('addUomModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('addUomForm').reset();
  }

  function submitAddUom(event) {
    event.preventDefault();

    const form = document.getElementById('addUomForm');
    const formData = new FormData(form);
    formData.append('action', 'add_uom');

    // Show loading state with higher z-index
    Swal.fire({
      title: 'Adding UOM...',
      text: 'Please wait while we add the unit of measure.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

    const payload = { uom_code: formData.get('uom_code'), uom_name: formData.get('uom_name'), uom_type: formData.get('uom_type') };
    fetch('../api/units_of_measure.php?action=create', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddUomModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6' }).then(() => fetchUoms());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33' });
        }
      })
      .catch(() => { closeAddUomModal(); Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred.', confirmButtonColor: '#d33' }); });
  }

  // Edit UOM Modal Functions
  function openEditUomModal(uomId) {
    const uom = uomsData.find(u => u.uom_id == uomId);
    if (!uom) return;

    // Populate form with existing data
    document.getElementById('editUomId').value = uom.uom_id;
    document.getElementById('editUomCode').value = uom.uom_code;
    document.getElementById('editUomName').value = uom.uom_name;
    document.getElementById('editUomType').value = uom.uom_type;

    const modal = document.getElementById('editUomModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeEditUomModal() {
    const modal = document.getElementById('editUomModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('editUomForm').reset();
  }

  function submitEditUom(event) {
    event.preventDefault();

    const form = document.getElementById('editUomForm');
    const formData = new FormData(form);
    formData.append('action', 'edit_uom');

    // Show loading state with higher z-index
    Swal.fire({
      title: 'Updating UOM...',
      text: 'Please wait while we update the unit of measure.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

    const payload = { uom_id: formData.get('uom_id'), uom_code: formData.get('uom_code'), uom_name: formData.get('uom_name'), uom_type: formData.get('uom_type') };
    fetch('../api/units_of_measure.php?action=update', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditUomModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6' }).then(() => fetchUoms());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33' });
        }
      })
      .catch(() => { closeEditUomModal(); Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred.', confirmButtonColor: '#d33' }); });
  }

  // Delete UOM Function with SweetAlert
  function deleteUom(uomId, uomName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete "${uomName}"? This action cannot be undone.`,
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
          title: 'Deleting UOM...',
          text: 'Please wait while we delete the unit of measure.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });

        fetch('../api/units_of_measure.php?action=delete', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ uom_id: uomId })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, confirmButtonColor: '#3085d6' }).then(() => fetchUoms());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33' });
            }
          })
          .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred.', confirmButtonColor: '#d33' }));
      }
    });
  }

  // Helper function to capitalize first letter
  function ucfirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }
</script>