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
  let roomsData = [];
  let propertiesData = [];
  let categoriesData = [];

  // Fetch data from API on page load
  document.addEventListener('DOMContentLoaded', async function() {
    await fetchRoomsData();
  });

  async function fetchRoomsData() {
    try {
      // Fetch rooms
      const response = await fetch('../api/rooms.php?action=list');
      const result = await response.json();
      if (result.success) {
        roomsData = result.data;
      }

      // Fetch properties for dropdowns
      const propsResponse = await fetch('../api/properties.php?action=list');
      const propsResult = await propsResponse.json();
      if (propsResult.success) {
        propertiesData = propsResult.data;
      }

      // Fetch categories for dropdowns
      const catsResponse = await fetch('../api/room_categories.php?action=list');
      const catsResult = await catsResponse.json();
      if (catsResult.success) {
        categoriesData = catsResult.data;
      }

      renderRoomsTable();
      populateDropdowns();
    } catch (error) {
      console.error('Error fetching rooms:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to load rooms data',
        confirmButtonColor: '#3085d6'
      });
    }
  }

  function populateDropdowns() {
    // Populate property dropdowns
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

    // Populate category dropdowns
    const addCategoryDropdown = document.getElementById('addCategoryDropdown');
    const editCategoryDropdown = document.getElementById('editCategoryDropdown');
    
    if (addCategoryDropdown && categoriesData.length > 0) {
      addCategoryDropdown.innerHTML = '<option value="">Select Category</option>';
      categoriesData.forEach(category => {
        const option = document.createElement('option');
        option.value = category.category_id;
        option.textContent = `${category.category_name} (${category.category_code})`;
        addCategoryDropdown.appendChild(option);
      });
    }

    if (editCategoryDropdown && categoriesData.length > 0) {
      editCategoryDropdown.innerHTML = '<option value="">Select Category</option>';
      categoriesData.forEach(category => {
        const option = document.createElement('option');
        option.value = category.category_id;
        option.textContent = `${category.category_name} (${category.category_code})`;
        editCategoryDropdown.appendChild(option);
      });
    }
  }

  function renderRoomsTable() {
    const tbody = document.querySelector('#roomsTableBody');
    if (!tbody) return;
    
    if (roomsData.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">
            No rooms found. <a href="javascript:;" onclick="openAddRoomModal()" class="text-blue-500 font-semibold">Add your first room</a>.
          </td>
        </tr>
      `;
      return;
    }

    tbody.innerHTML = roomsData.map((room) => {
      const property = propertiesData.find(p => p.property_id === room.property_id);
      const category = categoriesData.find(c => c.category_id === room.category_id);
      
      const statusColors = {
        'available': 'from-emerald-500 to-teal-400',
        'occupied': 'from-red-500 to-rose-400',
        'reserved': 'from-blue-500 to-cyan-400',
        'cleaning': 'from-yellow-500 to-orange-400',
        'maintenance': 'from-gray-500 to-slate-400',
        'out_of_order': 'from-red-600 to-red-400',
        'blocked': 'from-purple-500 to-violet-400'
      };
      const statusClass = statusColors[room.room_status] || 'from-gray-500 to-slate-400';

      return `
        <tr class="room-row">
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <div class="flex items-center">
              <div class="inline-flex items-center justify-center mr-3 text-sm text-white transition-all duration-200 ease-in-out h-8 w-8 rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500">
                <i class="fas fa-door-open text-xs"></i>
              </div>
              <div>
                <h6 class="mb-0 text-xs leading-normal dark:text-white">${room.room_name || room.room_number}</h6>
                <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400">${room.room_number}</p>
              </div>
            </div>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${property ? property.property_name : 'N/A'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${category ? category.category_name : 'N/A'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${room.floor_number || 'N/A'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <span class="bg-gradient-to-tl ${statusClass} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${room.room_status || 'available'}</span>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <div class="flex items-center gap-2">
              <button onclick="openEditRoomModal('${room.room_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Room">
                <i class="fas fa-edit mr-1.5 text-xs"></i>
                <span>Edit</span>
              </button>
              <button onclick="confirmDeleteRoom('${room.room_id}', '${room.room_number}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Room">
                <i class="fas fa-trash-alt mr-1.5 text-xs"></i>
                <span>Delete</span>
              </button>
            </div>
          </td>
        </tr>
      `;
    }).join('');
  }

  // Add Room Form Submission (API-based)
  async function handleAddRoomSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    // Generate UUID for room_id
    const room_id = generateUUID();
    
    // Prepare data for API
    const data = {
      room_id: room_id,
      property_id: formData.get('property_id'),
      category_id: formData.get('category_id'),
      room_number: formData.get('room_number'),
      room_name: formData.get('room_name') || null,
      floor_number: formData.get('floor_number') || null,
      building_block: formData.get('building_block') || null,
      room_status: formData.get('room_status') || 'available',
      override_price_per_night: formData.get('override_price_per_night') || null,
      override_price_per_hour: formData.get('override_price_per_hour') || null,
      override_price_day_use: formData.get('override_price_day_use') || null,
      internal_notes: formData.get('internal_notes') || null
    };

    try {
      const response = await fetch('../api/rooms.php', {
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
          text: 'Room added successfully!',
          timer: 2000,
          showConfirmButton: false
        });
        closeAddRoomModal();
        await fetchRoomsData();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: result.message || 'Failed to add room',
          confirmButtonColor: '#3085d6'
        });
      }
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'An error occurred while adding the room',
        confirmButtonColor: '#3085d6'
      });
    }
  }

  // Edit Room Form Submission (API-based)
  async function handleEditRoomSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    const data = {
      room_id: formData.get('room_id'),
      property_id: formData.get('property_id'),
      category_id: formData.get('category_id'),
      room_number: formData.get('room_number'),
      room_name: formData.get('room_name') || null,
      floor_number: formData.get('floor_number') || null,
      building_block: formData.get('building_block') || null,
      room_status: formData.get('room_status') || 'available',
      override_price_per_night: formData.get('override_price_per_night') || null,
      override_price_per_hour: formData.get('override_price_per_hour') || null,
      override_price_day_use: formData.get('override_price_day_use') || null,
      internal_notes: formData.get('internal_notes') || null
    };

    try {
      const response = await fetch('../api/rooms.php', {
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
          text: 'Room updated successfully!',
          timer: 2000,
          showConfirmButton: false
        });
        closeEditRoomModal();
        await fetchRoomsData();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: result.message || 'Failed to update room',
          confirmButtonColor: '#3085d6'
        });
      }
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'An error occurred while updating the room',
        confirmButtonColor: '#3085d6'
      });
    }
  }

  // Delete Room Function (API-based)
  async function confirmDeleteRoom(roomId, roomNumber) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete room "${roomNumber}"? This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          const response = await fetch('../api/rooms.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              action: 'delete',
              room_id: roomId
            })
          });
          const result = await response.json();
          
          if (result.success) {
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Room deleted successfully!',
              timer: 2000,
              showConfirmButton: false
            });
            await fetchRoomsData();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: result.message || 'Failed to delete room',
              confirmButtonColor: '#3085d6'
            });
          }
        } catch (error) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while deleting the room',
            confirmButtonColor: '#3085d6'
          });
        }
      }
    });
  }
</script>

<!-- PAGE CONTENT -->
<!-- table 1 -->
<div class="flex flex-wrap -mx-3">
  <div class="flex-none w-full max-w-full px-3">
    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
        <h6 class="dark:text-white">Rooms</h6>
        <button onclick="openAddRoomModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
          <i class="fas fa-plus mr-1"></i> Add Room
        </button>
      </div>
      <div class="flex-auto px-0 pt-0 pb-2">
        <div class="p-0 overflow-x-auto">
          <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="roomsTable">
            <thead class="align-bottom">
              <tr>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Room</th>
                <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Category</th>
                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
              </tr>
            </thead>
            <tbody id="roomsTableBody">
              <!-- Table rows will be populated by JavaScript -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Room Details Modal -->
<div id="roomDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeRoomDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
  <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
    <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
      <h3 class="text-xl font-bold dark:text-white" id="modalRoomName" style="font-size: 1.25rem; font-weight: bold; margin: 0;">Room Details</h3>
      <button onclick="closeRoomDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
        <i class="fas fa-times text-xl">×</i>
      </button>
    </div>
    <div class="p-6" id="modalRoomContent" style="padding: 1.5rem;">
      <!-- Content will be populated by JavaScript -->
    </div>
  </div>
</div>

<!-- Add Room Modal -->
<div id="addRoomModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddRoomModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
  <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
    <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
      <h3 class="text-xl font-bold dark:text-white">Add New Room</h3>
      <button onclick="closeAddRoomModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
        <i class="fas fa-times text-xl">×</i>
      </button>
    </div>
    <form id="addRoomForm" class="p-6" onsubmit="handleAddRoomSubmit(event)">

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Property Selection -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Property *</label>
          <select name="property_id" id="addPropertyDropdown" required class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Select Property</option>
          </select>
        </div>

        <!-- Category Selection -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Category *</label>
          <select name="category_id" id="addCategoryDropdown" required class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Select Category</option>
          </select>
        </div>

        <!-- Room Number -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Number *</label>
          <input type="text" name="room_number" required class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 101, A-02, Cottage-3">
        </div>

        <!-- Room Name -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Name</label>
          <input type="text" name="room_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Sunset Villa, Lagoon Cottage">
        </div>

        <!-- Floor Number -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Floor Number</label>
          <input type="number" name="floor_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 1, 2, 3">
        </div>

        <!-- Building Block -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Building Block</label>
          <input type="text" name="building_block" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Building A, East Wing">
        </div>

        <!-- Room Status -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Status *</label>
          <select name="room_status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="available">Available</option>
            <option value="occupied">Occupied</option>
            <option value="reserved">Reserved</option>
            <option value="cleaning">Cleaning</option>
            <option value="maintenance">Maintenance</option>
            <option value="out_of_order">Out of Order</option>
            <option value="blocked">Blocked</option>
          </select>
        </div>

        <!-- Price Override Per Night -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Override Price Per Night (PHP)</label>
          <input type="number" step="0.01" name="override_price_per_night" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Leave empty to use category price">
        </div>

        <!-- Price Override Per Hour -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Override Price Per Hour (PHP)</label>
          <input type="number" step="0.01" name="override_price_per_hour" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Leave empty to use category price">
        </div>

        <!-- Price Override Day Use -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Override Price Day Use (PHP)</label>
          <input type="number" step="0.01" name="override_price_day_use" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Leave empty to use category price">
        </div>

        <!-- Internal Notes -->
        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Internal Notes</label>
          <textarea name="internal_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any additional notes about this room..."></textarea>
        </div>

        <!-- Image Upload Section -->
        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Images</label>
          <div id="imageUploadContainer">
            <div class="image-entry mb-4 p-4 border border-gray-200 rounded-lg dark:border-white/20">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image File</label>
                  <input type="file" name="room_images[]" accept="image/*" class="w-full text-sm text-slate-500 dark:text-white/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-white">
                </div>
                <div>
                  <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image Type</label>
                  <select name="image_type[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="interior">Interior</option>
                    <option value="exterior">Exterior</option>
                    <option value="bathroom">Bathroom</option>
                    <option value="view">View</option>
                    <option value="amenity">Amenity</option>
                    <option value="bed">Bed</option>
                    <option value="balcony">Balcony</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Caption</label>
                  <input type="text" name="image_caption[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Image caption">
                </div>
              </div>
            </div>
          </div>
          <button type="button" onclick="addImageField()" class="mt-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
            <i class="fas fa-plus mr-1"></i> Add Another Image
          </button>
        </div>
      </div>

      <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-white/10">
        <button type="button" onclick="closeAddRoomModal()" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600">
          Cancel
        </button>
        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:shadow-md transition-all">
          <i class="fas fa-save mr-1"></i> Save Room
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Room Modal -->
<div id="editRoomModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditRoomModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
  <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
    <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
      <h3 class="text-xl font-bold dark:text-white">Edit Room</h3>
      <button onclick="closeEditRoomModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
        <i class="fas fa-times text-xl">×</i>
      </button>
    </div>
    <form id="editRoomForm" class="p-6" onsubmit="handleEditRoomSubmit(event)">
      <input type="hidden" name="room_id" id="edit_room_id">

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Property Selection -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Property *</label>
          <select name="property_id" id="editPropertyDropdown" required class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Select Property</option>
          </select>
        </div>

        <!-- Category Selection -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Category *</label>
          <select name="category_id" id="editCategoryDropdown" required class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Select Category</option>
          </select>
        </div>

        <!-- Room Number -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Number *</label>
          <input type="text" name="room_number" id="edit_room_number" required class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 101, A-02, Cottage-3">
        </div>

        <!-- Room Name -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Name</label>
          <input type="text" name="room_name" id="edit_room_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Sunset Villa, Lagoon Cottage">
        </div>

        <!-- Floor Number -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Floor Number</label>
          <input type="number" name="floor_number" id="edit_floor_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 1, 2, 3">
        </div>

        <!-- Building Block -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Building Block</label>
          <input type="text" name="building_block" id="edit_building_block" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Building A, East Wing">
        </div>

        <!-- Room Status -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Status *</label>
          <select name="room_status" id="edit_room_status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="available">Available</option>
            <option value="occupied">Occupied</option>
            <option value="reserved">Reserved</option>
            <option value="cleaning">Cleaning</option>
            <option value="maintenance">Maintenance</option>
            <option value="out_of_order">Out of Order</option>
            <option value="blocked">Blocked</option>
          </select>
        </div>

        <!-- Price Override Per Night -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Override Price Per Night (PHP)</label>
          <input type="number" step="0.01" name="override_price_per_night" id="edit_override_price_per_night" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Leave empty to use category price">
        </div>

        <!-- Price Override Per Hour -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Override Price Per Hour (PHP)</label>
          <input type="number" step="0.01" name="override_price_per_hour" id="edit_override_price_per_hour" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Leave empty to use category price">
        </div>

        <!-- Price Override Day Use -->
        <div>
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Override Price Day Use (PHP)</label>
          <input type="number" step="0.01" name="override_price_day_use" id="edit_override_price_day_use" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Leave empty to use category price">
        </div>

        <!-- Internal Notes -->
        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Internal Notes</label>
          <textarea name="internal_notes" id="edit_internal_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any additional notes about this room..."></textarea>
        </div>

        <!-- Existing Images Section -->
        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Existing Images</label>
          <div id="existingImagesContainer" class="mb-4">
            <!-- Existing images will be loaded here -->
          </div>
        </div>

        <!-- Image Upload Section -->
        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Add New Images</label>
          <div id="editImageUploadContainer">
            <div class="image-entry mb-4 p-4 border border-gray-200 rounded-lg dark:border-white/20">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image File</label>
                  <input type="file" name="room_images[]" accept="image/*" class="w-full text-sm text-slate-500 dark:text-white/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-white">
                </div>
                <div>
                  <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image Type</label>
                  <select name="image_type[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="interior">Interior</option>
                    <option value="exterior">Exterior</option>
                    <option value="bathroom">Bathroom</option>
                    <option value="view">View</option>
                    <option value="amenity">Amenity</option>
                    <option value="bed">Bed</option>
                    <option value="balcony">Balcony</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Caption</label>
                  <input type="text" name="image_caption[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Image caption">
                </div>
              </div>
            </div>
          </div>
          <button type="button" onclick="addEditImageField()" class="mt-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
            <i class="fas fa-plus mr-1"></i> Add Another Image
          </button>
        </div>
      </div>

      <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-white/10">
        <button type="button" onclick="closeEditRoomModal()" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600">
          Cancel
        </button>
        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:shadow-md transition-all">
          <i class="fas fa-save mr-1"></i> Update Room
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function viewRoomDetails(roomId) {
    const room = roomsData.find(r => r.room_id === roomId);
    if (!room) return;

    const modal = document.getElementById('roomDetailsModal');
    const modalName = document.getElementById('modalRoomName');
    const modalContent = document.getElementById('modalRoomContent');

    modalName.textContent = room.room_name || room.room_number;

    const statusColors = {
      'available': '#10b981',
      'occupied': '#ef4444',
      'reserved': '#3b82f6',
      'cleaning': '#eab308',
      'maintenance': '#6b7280',
      'out_of_order': '#dc2626',
      'blocked': '#a855f7'
    };
    const statusColor = statusColors[room.room_status] || '#6b7280';
    const statusLabel = room.room_status ? room.room_status.replace(/_/g, ' ').toUpperCase() : 'UNKNOWN';

    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Basic Information</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Room Number</p>
                <p class="text-sm font-semibold dark:text-white">${room.room_number}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Room Name</p>
                <p class="text-sm dark:text-white">${room.room_name || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Floor Number</p>
                <p class="text-sm dark:text-white">${room.floor_number || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Building Block</p>
                <p class="text-sm dark:text-white">${room.building_block || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Status</p>
                <span class="px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none" style="background-color: ${statusColor}; color: white !important;">${statusLabel}</span>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Property & Category</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Property</p>
                <p class="text-sm font-semibold dark:text-white">${room.property_name}</p>
                <p class="text-xs text-slate-400 dark:text-white/60">${room.property_code}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Category</p>
                <p class="text-sm font-semibold dark:text-white">${room.category_name}</p>
                <p class="text-xs text-slate-400 dark:text-white/60">${room.category_code}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Pricing (Category Base)</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Per Night</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(room.base_price_per_night || 0).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Per Hour</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(room.base_price_per_hour || 0).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Day Use</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(room.base_price_day_use || 0).toFixed(2)}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Price Overrides</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Override Per Night</p>
                <p class="text-sm dark:text-white">${room.override_price_per_night ? 'PHP ' + parseFloat(room.override_price_per_night).toFixed(2) : 'Using category price'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Override Per Hour</p>
                <p class="text-sm dark:text-white">${room.override_price_per_hour ? 'PHP ' + parseFloat(room.override_price_per_hour).toFixed(2) : 'Using category price'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Override Day Use</p>
                <p class="text-sm dark:text-white">${room.override_price_day_use ? 'PHP ' + parseFloat(room.override_price_day_use).toFixed(2) : 'Using category price'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Housekeeping</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Last Cleaned At</p>
                <p class="text-sm dark:text-white">${room.last_cleaned_at ? new Date(room.last_cleaned_at).toLocaleString() : 'Never'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Last Inspected At</p>
                <p class="text-sm dark:text-white">${room.last_inspected_at ? new Date(room.last_inspected_at).toLocaleString() : 'Never'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Notes</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Internal Notes</p>
                <p class="text-sm dark:text-white">${room.internal_notes || '-'}</p>
              </div>
            </div>
          </div>

          <div class="md:col-span-2">
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">System Information</h4>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Created At</p>
                <p class="text-sm dark:text-white">${new Date(room.created_at).toLocaleString()}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Updated At</p>
                <p class="text-sm dark:text-white">${new Date(room.updated_at).toLocaleString()}</p>
              </div>
            </div>
          </div>
        </div>
      `;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeRoomDetailsModal() {
    const modal = document.getElementById('roomDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  function deleteRoom(roomId, roomName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete the room "${roomName}"? This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.location.href;

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete_room';

        const roomIdInput = document.createElement('input');
        roomIdInput.type = 'hidden';
        roomIdInput.name = 'room_id';
        roomIdInput.value = roomId;

        form.appendChild(actionInput);
        form.appendChild(roomIdInput);
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  function openAddRoomModal() {
    const modal = document.getElementById('addRoomModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeAddRoomModal() {
    const modal = document.getElementById('addRoomModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('addRoomForm').reset();
  }

  function addImageField() {
    const container = document.getElementById('imageUploadContainer');
    const imageEntry = document.createElement('div');
    imageEntry.className = 'image-entry mb-4 p-4 border border-gray-200 rounded-lg dark:border-white/20 relative';
    imageEntry.innerHTML = `
        <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
          <i class="fas fa-times"></i>
        </button>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image File</label>
            <input type="file" name="room_images[]" accept="image/*" class="w-full text-sm text-slate-500 dark:text-white/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-white">
          </div>
          <div>
            <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image Type</label>
            <select name="image_type[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="interior">Interior</option>
              <option value="exterior">Exterior</option>
              <option value="bathroom">Bathroom</option>
              <option value="view">View</option>
              <option value="amenity">Amenity</option>
              <option value="bed">Bed</option>
              <option value="balcony">Balcony</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div>
            <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Caption</label>
            <input type="text" name="image_caption[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Image caption">
          </div>
        </div>
      `;
    container.appendChild(imageEntry);
  }

  function openEditRoomModal(roomId) {
    const room = roomsData.find(r => r.room_id === roomId);
    if (!room) return;

    // Populate form fields with existing data
    document.getElementById('edit_room_id').value = room.room_id;
    document.getElementById('edit_property_id').value = room.property_id;
    document.getElementById('edit_category_id').value = room.category_id;
    document.getElementById('edit_room_number').value = room.room_number;
    document.getElementById('edit_room_name').value = room.room_name || '';
    document.getElementById('edit_floor_number').value = room.floor_number || '';
    document.getElementById('edit_building_block').value = room.building_block || '';
    document.getElementById('edit_room_status').value = room.room_status;
    document.getElementById('edit_override_price_per_night').value = room.override_price_per_night || '';
    document.getElementById('edit_override_price_per_hour').value = room.override_price_per_hour || '';
    document.getElementById('edit_override_price_day_use').value = room.override_price_day_use || '';
    document.getElementById('edit_internal_notes').value = room.internal_notes || '';

    // Load existing images
    loadExistingImages(roomId);

    const modal = document.getElementById('editRoomModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeEditRoomModal() {
    const modal = document.getElementById('editRoomModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('editRoomForm').reset();
    document.getElementById('existingImagesContainer').innerHTML = '';
    document.getElementById('editImageUploadContainer').innerHTML = `
        <div class="image-entry mb-4 p-4 border border-gray-200 rounded-lg dark:border-white/20">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image File</label>
              <input type="file" name="room_images[]" accept="image/*" class="w-full text-sm text-slate-500 dark:text-white/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-white">
            </div>
            <div>
              <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image Type</label>
              <select name="image_type[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="interior">Interior</option>
                <option value="exterior">Exterior</option>
                <option value="bathroom">Bathroom</option>
                <option value="view">View</option>
                <option value="amenity">Amenity</option>
                <option value="bed">Bed</option>
                <option value="balcony">Balcony</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Caption</label>
              <input type="text" name="image_caption[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Image caption">
            </div>
          </div>
        </div>
      `;
  }

  function addEditImageField() {
    const container = document.getElementById('editImageUploadContainer');
    const imageEntry = document.createElement('div');
    imageEntry.className = 'image-entry mb-4 p-4 border border-gray-200 rounded-lg dark:border-white/20 relative';
    imageEntry.innerHTML = `
        <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
          <i class="fas fa-times"></i>
        </button>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image File</label>
            <input type="file" name="room_images[]" accept="image/*" class="w-full text-sm text-slate-500 dark:text-white/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-white">
          </div>
          <div>
            <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image Type</label>
            <select name="image_type[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="interior">Interior</option>
              <option value="exterior">Exterior</option>
              <option value="bathroom">Bathroom</option>
              <option value="view">View</option>
              <option value="amenity">Amenity</option>
              <option value="bed">Bed</option>
              <option value="balcony">Balcony</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div>
            <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Caption</label>
            <input type="text" name="image_caption[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Image caption">
          </div>
        </div>
      `;
    container.appendChild(imageEntry);
  }

  function loadExistingImages(roomId) {
    const container = document.getElementById('existingImagesContainer');
    const images = roomImagesData[roomId] || [];

    if (images.length === 0) {
      container.innerHTML = '<p class="text-sm text-slate-500">No images uploaded yet.</p>';
      return;
    }

    container.innerHTML = images.map(img => `
        <div class="relative inline-block mr-2 mb-2">
          <img src="../${img.image_url}" alt="${img.caption || ''}" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
          <button type="button" onclick="markImageForDeletion('${img.image_id}', this)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
            <i class="fas fa-times"></i>
          </button>
          <input type="hidden" name="delete_images[]" id="delete_img_${img.image_id}" value="">
          <p class="text-xs text-slate-500 mt-1 w-24 truncate">${img.caption || img.image_type}</p>
        </div>
      `).join('');
  }

  function markImageForDeletion(imageId, button) {
    const input = document.getElementById(`delete_img_${imageId}`);
    if (input.value === imageId) {
      input.value = '';
      button.classList.remove('bg-red-600');
      button.classList.add('bg-red-500');
      button.parentElement.classList.remove('opacity-50');
    } else {
      input.value = imageId;
      button.classList.remove('bg-red-500');
      button.classList.add('bg-red-600');
      button.parentElement.classList.add('opacity-50');
    }
  }

  // Filter categories based on selected property
  document.addEventListener('DOMContentLoaded', function() {
    const propertySelect = document.querySelector('select[name="property_id"]');
    const categorySelect = document.querySelector('select[name="category_id"]');

    if (propertySelect && categorySelect) {
      propertySelect.addEventListener('change', function() {
        const selectedPropertyId = this.value;
        const categoryOptions = categorySelect.querySelectorAll('option[data-property-id]');

        categoryOptions.forEach(option => {
          if (option.dataset.propertyId === selectedPropertyId || selectedPropertyId === '') {
            option.style.display = '';
          } else {
            option.style.display = 'none';
          }
        });

        // Reset category selection if current selection is not valid
        if (selectedPropertyId !== '' && categorySelect.value !== '') {
          const selectedOption = categorySelect.querySelector('option[value="' + categorySelect.value + '"]');
          if (selectedOption && selectedOption.dataset.propertyId !== selectedPropertyId) {
            categorySelect.value = '';
          }
        }
      });
    }
  });

  // Show success/error messages
  window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);

    // Show error message if present
    <?php if (isset($error_message)): ?>
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: <?php echo json_encode($error_message); ?>,
        confirmButtonColor: '#3085d6'
      });
    <?php endif; ?>

    if (urlParams.get('success') === 'room_added') {
      const newUrl = window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);

      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Room added successfully!',
        timer: 3000,
        showConfirmButton: false
      });
    }

    if (urlParams.get('success') === 'room_deleted') {
      const newUrl = window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);

      Swal.fire({
        icon: 'success',
        title: 'Deleted!',
        text: 'Room deleted successfully!',
        timer: 3000,
        showConfirmButton: false
      });
    }

    if (urlParams.get('success') === 'room_updated') {
      const newUrl = window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);

      Swal.fire({
        icon: 'success',
        title: 'Updated!',
        text: 'Room updated successfully!',
        timer: 3000,
        showConfirmButton: false
      });
    }
  });

  // Filter table function
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('roomsTable');
    const rows = table.getElementsByClassName('room-row');

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