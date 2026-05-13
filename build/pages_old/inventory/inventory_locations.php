<?php
require_once '../../config/db.php';

// Fetch all inventory locations with property names
$stmt = $pdo->query("
    SELECT 
        il.*,
        p.property_name
    FROM inventory_locations il
    INNER JOIN properties p ON il.property_id = p.property_id
    WHERE il.is_active = 1
    ORDER BY p.property_name ASC, il.location_type ASC, il.location_name ASC
");
$locations = $stmt->fetchAll();

// Fetch properties
$stmt = $pdo->query("SELECT * FROM properties WHERE is_active = 1 ORDER BY property_name ASC");
$properties = $stmt->fetchAll();
?>

<script>
  // Store locations data for JavaScript access
  const locationsData = <?php echo json_encode($locations); ?>;
  const propertiesData = <?php echo json_encode($properties); ?>;
</script>
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
<?php $assets_path = '../assets';
$page_title = 'Inventory Locations'; ?>
<!DOCTYPE html>
<html>

<head>
  <?php include('../../structure/head.php'); ?>
</head>

<body class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">
  <div class="absolute w-full bg-blue-500 dark:hidden min-h-75"></div>
  <!-- sidenav  -->
  <?php include('../../structure/sidebar.php'); ?>

  <!-- end sidenav -->

  <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">
    <!-- Navbar -->
    <?php include('../../structure/navbar.php'); ?>

    <!-- end Navbar -->

    <!-- cards -->
    <div class="w-full px-6 py-6 mx-auto">
      <!-- PAGE CONTENT -->
      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Locations</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($locations); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Main Warehouses</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $warehouseLocations = array_filter($locations, function ($l) {
                return $l['location_type'] === 'main_warehouse';
              });
              ?>
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($warehouseLocations); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Kitchens</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $kitchenLocations = array_filter($locations, function ($l) {
                return $l['location_type'] === 'kitchen';
              });
              ?>
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($kitchenLocations); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Bars</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $barLocations = array_filter($locations, function ($l) {
                return $l['location_type'] === 'bar';
              });
              ?>
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($barLocations); ?></h5>
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
                  <tbody>
                    <?php if (empty($locations)): ?>
                      <tr>
                        <td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No locations found. <a href="javascript:;" class="text-blue-500 font-semibold">Add your first location</a>.
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($locations as $index => $location): ?>
                        <tr class="location-row">
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex px-2 py-1">
                              <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
                                <i class="fas fa-map-big"></i>
                              </div>
                              <div class="flex flex-col justify-center">
                                <h6 class="mb-0 text-sm leading-normal dark:text-white"><?php echo htmlspecialchars($location['location_name']); ?></h6>
                              </div>
                            </div>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($location['property_name']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo ucfirst(str_replace('_', ' ', $location['location_type'])); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars(substr($location['description'] ?? '', 0, 50)); ?>...</p>
                          </td>
                          <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Active</span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex items-center gap-2">
                              <button onclick="viewLocationDetails('<?php echo $location['location_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
                                <i class="fas fa-info-circle mr-1.5 text-xs"></i>
                                <span>View</span>
                              </button>
                              <button onclick="openEditLocationModal('<?php echo $location['location_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Location">
                                <i class="fas fa-edit mr-1.5 text-xs"></i>
                                <span>Edit</span>
                              </button>
                              <button onclick="deleteLocation('<?php echo $location['location_id']; ?>', '<?php echo htmlspecialchars($location['location_name']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Location">
                                <i class="fas fa-trash-alt mr-1.5 text-xs"></i>
                                <span>Delete</span>
                              </button>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
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
                  <select name="property_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Property</option>
                    <?php foreach ($properties as $prop): ?>
                      <option value="<?php echo $prop['property_id']; ?>"><?php echo htmlspecialchars($prop['property_name']); ?></option>
                    <?php endforeach; ?>
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
                    <?php foreach ($properties as $prop): ?>
                      <option value="<?php echo $prop['property_id']; ?>"><?php echo htmlspecialchars($prop['property_name']); ?></option>
                    <?php endforeach; ?>
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

      <?php include('../../structure/footer.php'); ?>

    </div>
    <!-- end cards -->
  </main>

  <?php include('../../structure/customizer.php'); ?>
</body>

<?php include('../../structure/script.php'); ?>
<script>
  // Filter table function
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('locationsTable');
    const rows = table.getElementsByClassName('location-row');

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

  // View Location Details
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

    fetch('inventory_actions.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        // Close modal before showing result
        closeAddLocationModal();

        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: data.message,
            confirmButtonColor: '#3085d6',
            customClass: {
              popup: 'swal2-popup-high-z'
            }
          }).then(() => {
            location.reload();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message,
            confirmButtonColor: '#d33',
            customClass: {
              popup: 'swal2-popup-high-z'
            }
          });
        }
      })
      .catch(error => {
        closeAddLocationModal();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred while adding the location.',
          confirmButtonColor: '#d33',
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });
        console.error('Error:', error);
      });
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

    fetch('inventory_actions.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        // Close modal before showing result
        closeEditLocationModal();

        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: data.message,
            confirmButtonColor: '#3085d6',
            customClass: {
              popup: 'swal2-popup-high-z'
            }
          }).then(() => {
            location.reload();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message,
            confirmButtonColor: '#d33',
            customClass: {
              popup: 'swal2-popup-high-z'
            }
          });
        }
      })
      .catch(error => {
        closeEditLocationModal();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred while updating the location.',
          confirmButtonColor: '#d33',
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });
        console.error('Error:', error);
      });
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

        // Send AJAX request
        const formData = new FormData();
        formData.append('action', 'delete_location');
        formData.append('location_id', locationId);

        fetch('inventory_actions.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: data.message,
                confirmButtonColor: '#3085d6',
                customClass: {
                  popup: 'swal2-popup-high-z'
                }
              }).then(() => {
                location.reload();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonColor: '#d33',
                customClass: {
                  popup: 'swal2-popup-high-z'
                }
              });
            }
          })
          .catch(error => {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'An error occurred while deleting the location.',
              confirmButtonColor: '#d33',
              customClass: {
                popup: 'swal2-popup-high-z'
              }
            });
            console.error('Error:', error);
          });
      }
    });
  }

  // Helper function to capitalize first letter
  function ucfirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }
</script>

</html>