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
  let blocksData = [];
  let propertiesData = [];
  let roomsData = [];

  // Fetch data from API on page load
  document.addEventListener('DOMContentLoaded', async function() {
    await fetchBlocksData();
  });

  async function fetchBlocksData() {
    try {
      // Fetch blocks
      const response = await fetch('../api/room_blocks.php?action=list');
      const result = await response.json();
      if (result.success) {
        blocksData = result.data;
      }

      // Fetch properties for dropdowns
      const propsResponse = await fetch('../api/properties.php?action=list');
      const propsResult = await propsResponse.json();
      if (propsResult.success) {
        propertiesData = propsResult.data;
      }

      // Fetch rooms for dropdowns
      const roomsResponse = await fetch('../api/rooms.php?action=list');
      const roomsResult = await roomsResponse.json();
      if (roomsResult.success) {
        roomsData = roomsResult.data;
      }

      renderBlocksTable();
      populateDropdowns();
      updateStatsCounts();
    } catch (error) {
      console.error('Error fetching blocks:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to load blocks data',
        confirmButtonColor: '#3085d6'
      });
    }
  }

  function populateDropdowns() {
    const addPropertyDropdown = document.getElementById('addPropertyDropdown');
    const editPropertyDropdown = document.getElementById('editPropertyDropdown');
    const addRoomDropdown = document.getElementById('addRoomDropdown');
    const editRoomDropdown = document.getElementById('editRoomDropdown');
    
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

    if (addRoomDropdown && roomsData.length > 0) {
      addRoomDropdown.innerHTML = '<option value="">Select Room</option>';
      roomsData.forEach(room => {
        const option = document.createElement('option');
        option.value = room.room_id;
        option.textContent = `${room.room_name || room.room_number}`;
        addRoomDropdown.appendChild(option);
      });
    }

    if (editRoomDropdown && roomsData.length > 0) {
      editRoomDropdown.innerHTML = '<option value="">Select Room</option>';
      roomsData.forEach(room => {
        const option = document.createElement('option');
        option.value = room.room_id;
        option.textContent = `${room.room_name || room.room_number}`;
        editRoomDropdown.appendChild(option);
      });
    }
  }

  function updateStatsCounts() {
    const totalCount = blocksData.length;
    const maintenanceCount = blocksData.filter(b => b.block_type === 'maintenance').length;
    const ownerCount = blocksData.filter(b => b.block_type === 'owner_use').length;
    
    const today = new Date().toISOString().split('T')[0];
    const activeCount = blocksData.filter(b => b.start_date <= today && b.end_date >= today).length;

    const totalEl = document.getElementById('totalBlocksCount');
    const maintenanceEl = document.getElementById('maintenanceBlocksCount');
    const ownerEl = document.getElementById('ownerBlocksCount');
    const activeEl = document.getElementById('activeBlocksCount');

    if (totalEl) totalEl.textContent = totalCount;
    if (maintenanceEl) maintenanceEl.textContent = maintenanceCount;
    if (ownerEl) ownerEl.textContent = ownerCount;
    if (activeEl) activeEl.textContent = activeCount;
  }

  function renderBlocksTable() {
    const tbody = document.querySelector('#blocksTableBody');
    if (!tbody) return;
    
    if (blocksData.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">
            No blocks found. <a href="javascript:;" onclick="openAddBlockModal()" class="text-blue-500 font-semibold">Add your first block</a>.
          </td>
        </tr>
      `;
      return;
    }

    tbody.innerHTML = blocksData.map((block) => {
      const property = propertiesData.find(p => p.property_id === block.property_id);
      const room = roomsData.find(r => r.room_id === block.room_id);
      
      const statusColors = {
        'confirmed': 'from-emerald-500 to-teal-400',
        'pending': 'from-yellow-500 to-orange-400',
        'cancelled': 'from-red-500 to-rose-400',
        'completed': 'from-blue-500 to-cyan-400'
      };
      const statusClass = statusColors[block.status] || 'from-gray-500 to-slate-400';

      return `
        <tr class="block-row">
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <h6 class="mb-0 text-sm leading-normal dark:text-white">${block.block_name || 'N/A'}</h6>
            <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400">${block.block_type || 'N/A'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${property ? property.property_name : 'N/A'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${room ? (room.room_name || room.room_number) : 'N/A'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${block.start_date || 'N/A'}</p>
            <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400">${block.end_date || 'N/A'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <span class="bg-gradient-to-tl ${statusClass} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${block.status || 'pending'}</span>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <div class="flex items-center gap-2">
              <button onclick="openEditBlockModal('${block.block_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Block">
                <i class="fas fa-edit mr-1.5 text-xs"></i>
                <span>Edit</span>
              </button>
              <button onclick="confirmDeleteBlock('${block.block_id}', '${block.block_name || 'Block'}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Block">
                <i class="fas fa-trash-alt mr-1.5 text-xs"></i>
                <span>Delete</span>
              </button>
            </div>
          </td>
        </tr>
      `;
    }).join('');
  }

  // Delete Block Function (API-based)
  async function confirmDeleteBlock(blockId, blockName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete "${blockName}"? This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          const response = await fetch('../api/room_blocks.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              action: 'delete',
              block_id: blockId
            })
          });
          const result = await response.json();
          
          if (result.success) {
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Block deleted successfully!',
              timer: 2000,
              showConfirmButton: false
            });
            await fetchBlocksData();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: result.message || 'Failed to delete block',
              confirmButtonColor: '#3085d6'
            });
          }
        } catch (error) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while deleting the block',
            confirmButtonColor: '#3085d6'
          });
        }
      }
    });
  }

  // Add Block Form Submission (API-based)
  async function handleAddBlockSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    // Generate UUID for block_id
    const block_id = generateUUID();
    
    // Prepare data for API
    const data = {
      block_id: block_id,
      property_id: formData.get('property_id'),
      room_id: formData.get('room_id'),
      block_name: formData.get('block_name'),
      block_type: formData.get('block_type') || 'maintenance',
      start_date: formData.get('start_date'),
      end_date: formData.get('end_date'),
      start_time: formData.get('start_time'),
      end_time: formData.get('end_time'),
      reason: formData.get('reason'),
      status: formData.get('status') || 'pending',
      notes: formData.get('notes')
    };

    try {
      const response = await fetch('../api/room_blocks.php', {
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
          text: 'Block added successfully!',
          timer: 2000,
          showConfirmButton: false
        });
        closeAddBlockModal();
        await fetchBlocksData();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: result.message || 'Failed to add block',
          confirmButtonColor: '#3085d6'
        });
      }
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'An error occurred while adding the block',
        confirmButtonColor: '#3085d6'
      });
    }
  }

  // Edit Block Form Submission (API-based)
  async function handleEditBlockSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    const data = {
      block_id: formData.get('block_id'),
      property_id: formData.get('property_id'),
      room_id: formData.get('room_id'),
      block_name: formData.get('block_name'),
      block_type: formData.get('block_type') || 'maintenance',
      start_date: formData.get('start_date'),
      end_date: formData.get('end_date'),
      start_time: formData.get('start_time'),
      end_time: formData.get('end_time'),
      reason: formData.get('reason'),
      status: formData.get('status') || 'pending',
      notes: formData.get('notes')
    };

    try {
      const response = await fetch('../api/room_blocks.php', {
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
          text: 'Block updated successfully!',
          timer: 2000,
          showConfirmButton: false
        });
        closeEditBlockModal();
        await fetchBlocksData();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: result.message || 'Failed to update block',
          confirmButtonColor: '#3085d6'
        });
      }
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'An error occurred while updating the block',
        confirmButtonColor: '#3085d6'
      });
    }
  }
</script>

<!-- PAGE CONTENT -->
      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Blocks</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="totalBlocksCount">0</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Maintenance</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-orange-500" id="maintenanceBlocksCount">0</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Owner Use</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-blue-500" id="ownerBlocksCount">0</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Active Now</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl text-red-500" id="activeBlocksCount">0</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Room Blocks Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Room Availability Blocks</h6>
              <button onclick="openAddBlockModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Add Block
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="blocksTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Room</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Block Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Date Range</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reason</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="blocksTableBody">
                    <!-- Table rows will be populated by JavaScript -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add Block Modal -->
      <div id="addBlockModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddBlockModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Add New Block</h3>
            <button onclick="closeAddBlockModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form id="addBlockForm" class="p-6" onsubmit="handleAddBlockSubmit(event)">
              <div class="space-y-4">
                <div>
                  <label for="room_id" class="block text-sm font-medium text-gray-700">Room</label>
                  <select name="room_id" id="addRoomDropdown" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Select a room</option>
                  </select>
                </div>
                <div>
                  <label for="block_type" class="block text-sm font-medium text-gray-700">Block Type</label>
                  <select name="block_type" id="block_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="maintenance">Maintenance</option>
                    <option value="owner_use">Owner Use</option>
                    <option value="event_hold">Event Hold</option>
                    <option value="deep_cleaning">Deep Cleaning</option>
                    <option value="renovation">Renovation</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label for="date_start" class="block text-sm font-medium text-gray-700">Start Date</label>
                  <input type="date" name="date_start" id="date_start" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="date_end" class="block text-sm font-medium text-gray-700">End Date</label>
                  <input type="date" name="date_end" id="date_end" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="time_start" class="block text-sm font-medium text-gray-700">Start Time (Optional)</label>
                  <input type="time" name="time_start" id="time_start" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="time_end" class="block text-sm font-medium text-gray-700">End Time (Optional)</label>
                  <input type="time" name="time_end" id="time_end" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                  <textarea name="reason" id="reason" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Reason for blocking..."></textarea>
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
                <button type="button" onclick="closeAddBlockModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Add Block
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Edit Block Modal -->
      <div id="editBlockModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditBlockModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Edit Block</h3>
            <button onclick="closeEditBlockModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form id="editBlockForm" class="p-6" onsubmit="handleEditBlockSubmit(event)">
              <input type="hidden" name="block_id" id="edit_block_id">
              <div class="space-y-4">
                <div>
                  <label for="edit_room_id" class="block text-sm font-medium text-gray-700">Room</label>
                  <select name="room_id" id="editRoomDropdown" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Select a room</option>
                  </select>
                </div>
                <div>
                  <label for="edit_block_type" class="block text-sm font-medium text-gray-700">Block Type</label>
                  <select name="block_type" id="edit_block_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="maintenance">Maintenance</option>
                    <option value="owner_use">Owner Use</option>
                    <option value="event_hold">Event Hold</option>
                    <option value="deep_cleaning">Deep Cleaning</option>
                    <option value="renovation">Renovation</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label for="edit_date_start" class="block text-sm font-medium text-gray-700">Start Date</label>
                  <input type="date" name="date_start" id="edit_date_start" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_date_end" class="block text-sm font-medium text-gray-700">End Date</label>
                  <input type="date" name="date_end" id="edit_date_end" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_time_start" class="block text-sm font-medium text-gray-700">Start Time (Optional)</label>
                  <input type="time" name="time_start" id="edit_time_start" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_time_end" class="block text-sm font-medium text-gray-700">End Time (Optional)</label>
                  <input type="time" name="time_end" id="edit_time_end" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_reason" class="block text-sm font-medium text-gray-700">Reason</label>
                  <textarea name="reason" id="edit_reason" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Reason for blocking..."></textarea>
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
                <button type="button" onclick="closeEditBlockModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Update Block
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- View Block Modal -->
      <div id="viewBlockModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeViewBlockModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Block Details</h3>
            <button onclick="closeViewBlockModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-500">Room</label>
                <p id="view_room" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Property</label>
                <p id="view_property" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Block Type</label>
                <p id="view_block_type" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Date Range</label>
                <p id="view_date_range" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Time Range</label>
                <p id="view_time_range" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Reason</label>
                <p id="view_reason" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Status</label>
                <p id="view_status" class="mt-1 text-sm text-gray-900"></p>
              </div>
            </div>
          </div>
          <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end flex-shrink-0" style="padding: 1.5rem; border-top: 1px solid #e5e7eb;">
            <button type="button" onclick="closeViewBlockModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Form -->
    <form id="deleteBlockForm" method="POST" action="room_blocks.php" style="display: none;">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
      <input type="hidden" name="action" value="delete_block">
      <input type="hidden" name="block_id" id="delete_block_id">
    </form>

<script>
  // Filter table function
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('blocksTable');
    const rows = table.getElementsByClassName('block-row');

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
  function openAddBlockModal() {
    const modal = document.getElementById('addBlockModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }

  function closeAddBlockModal() {
    const modal = document.getElementById('addBlockModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    // Reset form
    const form = modal.querySelector('form');
    if (form) {
      form.reset();
    }
  }

  function openEditBlockModal(blockId) {
    const row = document.querySelector(`tr[data-block-id="${blockId}"]`);
    if (row) {
      document.getElementById('edit_block_id').value = blockId;
      document.getElementById('edit_room_id').value = row.dataset.roomId;
      document.getElementById('edit_block_type').value = row.dataset.blockType;
      document.getElementById('edit_date_start').value = row.dataset.dateStart;
      document.getElementById('edit_date_end').value = row.dataset.dateEnd;
      document.getElementById('edit_time_start').value = row.dataset.timeStart;
      document.getElementById('edit_time_end').value = row.dataset.timeEnd;
      document.getElementById('edit_reason').value = row.dataset.reason;
      document.getElementById('edit_is_active').checked = row.dataset.isActive === '1';

      const modal = document.getElementById('editBlockModal');
      modal.classList.remove('hidden');
      modal.style.display = 'block';
      document.body.style.overflow = 'hidden';
    }
  }

  function closeEditBlockModal() {
    const modal = document.getElementById('editBlockModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';

    // Reset form
    const form = modal.querySelector('form');
    if (form) {
      form.reset();
    }
  }

  function openViewBlockModal(blockId) {
    const row = document.querySelector(`tr[data-block-id="${blockId}"]`);
    if (row) {
      document.getElementById('view_room').textContent = row.dataset.roomNumber + (row.dataset.roomName ? ' - ' + row.dataset.roomName : '');
      document.getElementById('view_property').textContent = row.dataset.propertyName;
      document.getElementById('view_block_type').textContent = row.dataset.blockType.replace('_', ' ').replace(/\b\w/g, c => c.toUpperCase());

      const dateRange = row.dataset.dateStart + ' to ' + row.dataset.dateEnd;
      document.getElementById('view_date_range').textContent = dateRange;

      const timeRange = row.dataset.timeStart && row.dataset.timeEnd ?
        row.dataset.timeStart + ' - ' + row.dataset.timeEnd :
        'All day';
      document.getElementById('view_time_range').textContent = timeRange;

      document.getElementById('view_reason').textContent = row.dataset.reason || 'No reason provided';
      document.getElementById('view_status').textContent = row.dataset.isActive === '1' ? 'Active' : 'Inactive';

      const modal = document.getElementById('viewBlockModal');
      modal.classList.remove('hidden');
      modal.style.display = 'block';
      document.body.style.overflow = 'hidden';
    }
  }

  function closeViewBlockModal() {
    const modal = document.getElementById('viewBlockModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
  }

  function confirmDeleteBlock(blockId) {
    const row = document.querySelector(`tr[data-block-id="${blockId}"]`);
    const roomInfo = row ? row.dataset.roomNumber + ' (' + row.dataset.propertyName + ')' : 'this block';

    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to delete the block for "${roomInfo}". This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('delete_block_id').value = blockId;
        document.getElementById('deleteBlockForm').submit();
      }
    });
  }

  // Show success/error messages
  document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_GET['success'])): ?>
      <?php if ($_GET['success'] === 'block_added'): ?>
        Swal.fire({
          title: 'Success!',
          text: 'Block has been added successfully.',
          icon: 'success',
          confirmButtonColor: '#3085d6'
        });
      <?php elseif ($_GET['success'] === 'block_updated'): ?>
        Swal.fire({
          title: 'Success!',
          text: 'Block has been updated successfully.',
          icon: 'success',
          confirmButtonColor: '#3085d6'
        });
      <?php elseif ($_GET['success'] === 'block_deleted'): ?>
        Swal.fire({
          title: 'Deleted!',
          text: 'Block has been deleted successfully.',
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