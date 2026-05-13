<?php
require_once '../../config/db.php';

// Fetch all stock transfers with from/to location details and items
$stmt = $pdo->query("
    SELECT
        st.*,
        from_loc.location_name as from_location_name,
        from_loc.location_type as from_location_type,
        to_loc.location_name as to_location_name,
        to_loc.location_type as to_location_type,
        from_prop.property_name as from_property_name,
        to_prop.property_name as to_property_name
    FROM stock_transfers st
    INNER JOIN inventory_locations from_loc ON st.from_location_id = from_loc.location_id
    INNER JOIN inventory_locations to_loc ON st.to_location_id = to_loc.location_id
    INNER JOIN properties from_prop ON from_loc.property_id = from_prop.property_id
    INNER JOIN properties to_prop ON to_loc.property_id = to_prop.property_id
    ORDER BY st.transfer_date DESC
");
$transfers = $stmt->fetchAll();

// Fetch transfer items for each transfer
foreach ($transfers as &$transfer) {
  $stmt = $pdo->prepare("
        SELECT
            sti.*,
            p.product_name,
            p.product_code,
            u.uom_name
        FROM stock_transfer_items sti
        INNER JOIN products p ON sti.product_id = p.product_id
        INNER JOIN units_of_measure u ON sti.uom_id = u.uom_id
        WHERE sti.transfer_id = ?
    ");
  $stmt->execute([$transfer['transfer_id']]);
  $transfer['items'] = $stmt->fetchAll();
}
unset($transfer);

// Fetch all active products
$stmt = $pdo->query("SELECT product_id, product_name, product_code FROM products WHERE is_active = 1 ORDER BY product_name ASC");
$products = $stmt->fetchAll();

// Fetch all active inventory locations with property
$stmt = $pdo->query("
    SELECT
        il.location_id,
        il.location_name,
        il.location_type,
        prop.property_name,
        prop.property_id
    FROM inventory_locations il
    INNER JOIN properties prop ON il.property_id = prop.property_id
    WHERE il.is_active = 1
    ORDER BY prop.property_name ASC, il.location_name ASC
");
$locations = $stmt->fetchAll();
?>

<script>
  // Store transfers data for JavaScript access
  const transfersData = <?php echo json_encode($transfers); ?>;
  const productsData = <?php echo json_encode($products); ?>;
  const locationsData = <?php echo json_encode($locations); ?>;
</script>

<?php $assets_path = '../assets';
$page_title = 'Stock Transfer'; ?>
<!DOCTYPE html>
<html>

<head>
  <?php include('../../structure/head.php'); ?>
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
              <h6 class="mb-0 font-bold dark:text-white">Total Transfers</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($transfers); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Pending</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $pendingTransfers = array_filter($transfers, function ($t) {
                return $t['transfer_status'] === 'pending';
              });
              ?>
              <h5 class="font-bold text-3xl text-orange-500"><?php echo count($pendingTransfers); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">In Transit</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $inTransit = array_filter($transfers, function ($t) {
                return $t['transfer_status'] === 'in_transit';
              });
              ?>
              <h5 class="font-bold text-3xl text-blue-500"><?php echo count($inTransit); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Completed</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $completedTransfers = array_filter($transfers, function ($t) {
                return $t['transfer_status'] === 'completed';
              });
              ?>
              <h5 class="font-bold text-3xl text-emerald-500"><?php echo count($completedTransfers); ?></h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Transfers Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Stock Transfers</h6>
              <button onclick="openAddTransferModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> New Transfer
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="transfersTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Transfer ID</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">From</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">To</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Date</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($transfers)): ?>
                      <tr>
                        <td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No stock transfers found. <a href="javascript:;" class="text-blue-500 font-semibold">Create your first transfer</a>.
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($transfers as $index => $transfer): ?>
                        <tr class="transfer-row">
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex px-2 py-1">
                              <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
                                <i class="fas fa-exchange-alt"></i>
                              </div>
                              <div class="flex flex-col justify-center">
                                <h6 class="mb-0 text-sm leading-normal dark:text-white"><?php echo substr($transfer['transfer_id'], 0, 8); ?>...</h6>
                                <p class="mb-0 text-xs text-slate-400 dark:text-white/60"><?php echo count($transfer['items']); ?> item(s)</p>
                              </div>
                            </div>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($transfer['from_location_name']); ?></p>
                            <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400"><?php echo htmlspecialchars($transfer['from_property_name']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($transfer['to_location_name']); ?></p>
                            <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400"><?php echo htmlspecialchars($transfer['to_property_name']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo date('M d, Y', strtotime($transfer['transfer_date'])); ?></p>
                          </td>
                          <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <?php
                            $statusColors = [
                              'pending' => 'from-orange-500 to-amber-400',
                              'in_transit' => 'from-blue-500 to-cyan-400',
                              'completed' => 'from-emerald-500 to-teal-400',
                              'cancelled' => 'from-red-500 to-rose-400'
                            ];
                            $color = $statusColors[$transfer['transfer_status']] ?? 'from-slate-500 to-slate-400';
                            ?>
                            <span class="bg-gradient-to-tl <?php echo $color; ?> px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white"><?php echo str_replace('_', ' ', $transfer['transfer_status']); ?></span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex items-center gap-2">
                              <button onclick="viewTransferDetails('<?php echo $transfer['transfer_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
                                <i class="fas fa-info-circle mr-1.5 text-xs"></i>
                                <span>View</span>
                              </button>
                              <button onclick="openEditTransferModal('<?php echo $transfer['transfer_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Transfer">
                                <i class="fas fa-edit mr-1.5 text-xs"></i>
                                <span>Edit</span>
                              </button>
                              <button onclick="deleteTransfer('<?php echo $transfer['transfer_id']; ?>', '<?php echo substr($transfer['transfer_id'], 0, 8); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Transfer">
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

      <!-- View Transfer Details Modal -->
      <div id="transferDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeTransferDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white" id="transferModalTitle">Transfer Details</h3>
            <button onclick="closeTransferDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6" id="transferModalContent">
            <!-- Content will be populated by JavaScript -->
          </div>
        </div>
      </div>

      <!-- Add Transfer Modal -->
      <div id="addTransferModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddTransferModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Create New Stock Transfer</h3>
            <button onclick="closeAddTransferModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addTransferForm" onsubmit="submitAddTransfer(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">From Location <span class="text-red-500">*</span></label>
                  <select name="from_location_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select From Location</option>
                    <?php foreach ($locations as $loc): ?>
                      <option value="<?php echo $loc['location_id']; ?>"><?php echo htmlspecialchars($loc['property_name']); ?> - <?php echo htmlspecialchars($loc['location_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">To Location <span class="text-red-500">*</span></label>
                  <select name="to_location_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select To Location</option>
                    <?php foreach ($locations as $loc): ?>
                      <option value="<?php echo $loc['location_id']; ?>"><?php echo htmlspecialchars($loc['property_name']); ?> - <?php echo htmlspecialchars($loc['location_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Transfer Date <span class="text-red-500">*</span></label>
                  <input type="date" name="transfer_date" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Status</label>
                  <select name="transfer_status" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="pending" selected>Pending</option>
                    <option value="in_transit">In Transit</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                  </select>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Additional notes..."></textarea>
                </div>
              </div>

              <div class="mt-6">
                <div class="flex justify-between items-center mb-3">
                  <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase">Transfer Items</h4>
                  <button type="button" onclick="addTransferItem()" class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-plus mr-1"></i> Add Item
                  </button>
                </div>
                <div id="transferItemsContainer" class="space-y-3">
                  <!-- Transfer items will be added here dynamically -->
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeAddTransferModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Create Transfer
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Transfer Modal -->
      <div id="editTransferModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditTransferModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Stock Transfer</h3>
            <button onclick="closeEditTransferModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="editTransferForm" onsubmit="submitEditTransfer(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="transfer_id" id="editTransferId">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">From Location <span class="text-red-500">*</span></label>
                  <select name="from_location_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select From Location</option>
                    <?php foreach ($locations as $loc): ?>
                      <option value="<?php echo $loc['location_id']; ?>"><?php echo htmlspecialchars($loc['property_name']); ?> - <?php echo htmlspecialchars($loc['location_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">To Location <span class="text-red-500">*</span></label>
                  <select name="to_location_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select To Location</option>
                    <?php foreach ($locations as $loc): ?>
                      <option value="<?php echo $loc['location_id']; ?>"><?php echo htmlspecialchars($loc['property_name']); ?> - <?php echo htmlspecialchars($loc['location_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Transfer Date <span class="text-red-500">*</span></label>
                  <input type="date" name="transfer_date" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Status</label>
                  <select name="transfer_status" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="pending" selected>Pending</option>
                    <option value="in_transit">In Transit</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                  </select>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Additional notes..."></textarea>
                </div>
              </div>

              <div class="mt-6">
                <div class="flex justify-between items-center mb-3">
                  <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase">Transfer Items</h4>
                  <button type="button" onclick="addTransferItem('edit')" class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-plus mr-1"></i> Add Item
                  </button>
                </div>
                <div id="editTransferItemsContainer" class="space-y-3">
                  <!-- Transfer items will be added here dynamically -->
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeEditTransferModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Update Transfer
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
    const table = document.getElementById('transfersTable');
    const rows = table.getElementsByClassName('transfer-row');

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

  // View Transfer Details
  function viewTransferDetails(transferId) {
    const transfer = transfersData.find(t => t.transfer_id === transferId);
    if (!transfer) return;

    const modal = document.getElementById('transferDetailsModal');
    const modalTitle = document.getElementById('transferModalTitle');
    const modalContent = document.getElementById('transferModalContent');

    modalTitle.textContent = `Transfer: ${transfer.transfer_id.substring(0, 8)}...`;

    const statusColors = {
      'pending': 'from-orange-500 to-amber-400',
      'in_transit': 'from-blue-500 to-cyan-400',
      'completed': 'from-emerald-500 to-teal-400',
      'cancelled': 'from-red-500 to-rose-400'
    };
    const color = statusColors[transfer.transfer_status] ?? 'from-slate-500 to-slate-400';

    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">From Location</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Property</p>
                <p class="text-sm dark:text-white">${transfer.from_property_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Location</p>
                <p class="text-sm dark:text-white">${transfer.from_location_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Location Type</p>
                <p class="text-sm dark:text-white">${transfer.from_location_type.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">To Location</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Property</p>
                <p class="text-sm dark:text-white">${transfer.to_property_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Location</p>
                <p class="text-sm dark:text-white">${transfer.to_location_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Location Type</p>
                <p class="text-sm dark:text-white">${transfer.to_location_type.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Transfer Details</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Transfer ID</p>
                <p class="text-sm dark:text-white font-mono">${transfer.transfer_id}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Transfer Date</p>
                <p class="text-sm dark:text-white">${new Date(transfer.transfer_date).toLocaleDateString()}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Status</p>
                <span class="bg-gradient-to-tl ${color} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${transfer.transfer_status.replace(/_/g, ' ')}</span>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Created At</p>
                <p class="text-sm dark:text-white">${new Date(transfer.created_at).toLocaleString()}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Notes</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Notes</p>
                <p class="text-sm dark:text-white">${transfer.notes || '-'}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-6">
          <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Transfer Items (${transfer.items.length})</h4>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b border-gray-200 dark:border-white/10">
                  <th class="text-left py-2 px-3 text-xs font-semibold text-slate-500 dark:text-white/60">Product</th>
                  <th class="text-left py-2 px-3 text-xs font-semibold text-slate-500 dark:text-white/60">Code</th>
                  <th class="text-right py-2 px-3 text-xs font-semibold text-slate-500 dark:text-white/60">Quantity</th>
                  <th class="text-left py-2 px-3 text-xs font-semibold text-slate-500 dark:text-white/60">UOM</th>
                </tr>
              </thead>
              <tbody>
                ${transfer.items.map(item => `
                  <tr class="border-b border-gray-100 dark:border-white/5">
                    <td class="py-2 px-3 dark:text-white">${item.product_name}</td>
                    <td class="py-2 px-3 dark:text-white/60">${item.product_code}</td>
                    <td class="py-2 px-3 text-right dark:text-white font-semibold">${parseFloat(item.quantity).toFixed(3)}</td>
                    <td class="py-2 px-3 dark:text-white/60">${item.uom_name}</td>
                  </tr>
                `).join('')}
                ${transfer.items.length === 0 ? '<tr><td colspan="4" class="py-4 text-center text-slate-400">No items in this transfer</td></tr>' : ''}
              </tbody>
            </table>
          </div>
        </div>
      `;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeTransferDetailsModal() {
    const modal = document.getElementById('transferDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  // Add Transfer Modal Functions
  let transferItemIndex = 0;

  function openAddTransferModal() {
    const modal = document.getElementById('addTransferModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    // Add first item by default
    document.getElementById('transferItemsContainer').innerHTML = '';
    transferItemIndex = 0;
    addTransferItem('add');
  }

  function closeAddTransferModal() {
    const modal = document.getElementById('addTransferModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('addTransferForm').reset();
    document.getElementById('transferItemsContainer').innerHTML = '';
    transferItemIndex = 0;
  }

  function addTransferItem(mode = 'add') {
    const containerId = mode === 'edit' ? 'editTransferItemsContainer' : 'transferItemsContainer';
    const container = document.getElementById(containerId);

    const itemHtml = `
        <div class="transfer-item-row bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg" data-item-index="${transferItemIndex}">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
              <label class="block text-xs font-semibold text-slate-600 dark:text-white mb-1">Product <span class="text-red-500">*</span></label>
              <select name="items[${transferItemIndex}][product_id]" required class="w-full px-3 py-1.5 text-xs border border-gray-300 dark:border-white/20 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Select Product</option>
                ${productsData.map(p => `<option value="${p.product_id}">${p.product_name} (${p.product_code})</option>`).join('')}
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-600 dark:text-white mb-1">Quantity <span class="text-red-500">*</span></label>
              <input type="number" step="0.001" name="items[${transferItemIndex}][quantity]" required class="w-full px-3 py-1.5 text-xs border border-gray-300 dark:border-white/20 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Qty">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-600 dark:text-white mb-1">UOM</label>
              <select name="items[${transferItemIndex}][uom_id]" required class="w-full px-3 py-1.5 text-xs border border-gray-300 dark:border-white/20 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Select UOM</option>
                <?php
                $uoms = $pdo->query("SELECT uom_id, uom_name FROM units_of_measure WHERE is_active = 1 ORDER BY uom_name ASC")->fetchAll();
                foreach ($uoms as $uom): ?>
                  <option value="<?php echo $uom['uom_id']; ?>"><?php echo htmlspecialchars($uom['uom_name']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="flex items-end">
              <button type="button" onclick="removeTransferItem(this)" class="w-full px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 dark:bg-red-500/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-500/30 transition-colors">
                <i class="fas fa-trash-alt mr-1"></i> Remove
              </button>
            </div>
          </div>
        </div>
      `;

    container.insertAdjacentHTML('beforeend', itemHtml);
    transferItemIndex++;
  }

  function removeTransferItem(button) {
    const row = button.closest('.transfer-item-row');
    row.remove();
  }

  function submitAddTransfer(event) {
    event.preventDefault();

    const form = document.getElementById('addTransferForm');
    const formData = new FormData(form);
    formData.append('action', 'add_stock_transfer');

    // Validate at least one item
    const itemRows = document.querySelectorAll('#transferItemsContainer .transfer-item-row');
    if (itemRows.length === 0) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Please add at least one item to the transfer.',
        confirmButtonColor: '#d33',
        customClass: {
          popup: 'swal2-popup-high-z'
        }
      });
      return;
    }

    Swal.fire({
      title: 'Creating...',
      text: 'Please wait while we create the stock transfer.',
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
        closeAddTransferModal();

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
        closeAddTransferModal();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred while creating the stock transfer.',
          confirmButtonColor: '#d33',
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });
        console.error('Error:', error);
      });
  }

  // Edit Transfer Modal Functions
  function openEditTransferModal(transferId) {
    const transfer = transfersData.find(t => t.transfer_id === transferId);
    if (!transfer) return;

    document.getElementById('editTransferId').value = transfer.transfer_id;
    const form = document.getElementById('editTransferForm');
    form.elements['from_location_id'].value = transfer.from_location_id;
    form.elements['to_location_id'].value = transfer.to_location_id;
    form.elements['transfer_date'].value = transfer.transfer_date.split(' ')[0];
    form.elements['transfer_status'].value = transfer.transfer_status;
    form.elements['notes'].value = transfer.notes || '';

    // Populate existing items
    const container = document.getElementById('editTransferItemsContainer');
    container.innerHTML = '';
    transferItemIndex = 0;

    if (transfer.items && transfer.items.length > 0) {
      transfer.items.forEach(item => {
        addTransferItem('edit');
        const lastRow = container.querySelector('.transfer-item-row:last-child');
        lastRow.querySelector(`select[name="items[${transferItemIndex - 1}][product_id]"]`).value = item.product_id;
        lastRow.querySelector(`input[name="items[${transferItemIndex - 1}][quantity]"]`).value = parseFloat(item.quantity);
        lastRow.querySelector(`select[name="items[${transferItemIndex - 1}][uom_id]"]`).value = item.uom_id;
      });
    } else {
      addTransferItem('edit');
    }

    const modal = document.getElementById('editTransferModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeEditTransferModal() {
    const modal = document.getElementById('editTransferModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('editTransferForm').reset();
    document.getElementById('editTransferItemsContainer').innerHTML = '';
    transferItemIndex = 0;
  }

  function submitEditTransfer(event) {
    event.preventDefault();

    const form = document.getElementById('editTransferForm');
    const formData = new FormData(form);
    formData.append('action', 'edit_stock_transfer');

    // Validate at least one item
    const itemRows = document.querySelectorAll('#editTransferItemsContainer .transfer-item-row');
    if (itemRows.length === 0) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Please add at least one item to the transfer.',
        confirmButtonColor: '#d33',
        customClass: {
          popup: 'swal2-popup-high-z'
        }
      });
      return;
    }

    Swal.fire({
      title: 'Updating...',
      text: 'Please wait while we update the stock transfer.',
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
        closeEditTransferModal();

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
        closeEditTransferModal();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred while updating the stock transfer.',
          confirmButtonColor: '#d33',
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });
        console.error('Error:', error);
      });
  }

  // Delete Transfer Function with SweetAlert
  function deleteTransfer(transferId, transferShortId) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete stock transfer "${transferShortId}..."? This action cannot be undone.`,
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
          text: 'Please wait while we delete the stock transfer.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });

        const formData = new FormData();
        formData.append('action', 'delete_stock_transfer');
        formData.append('transfer_id', transferId);

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
              text: 'An error occurred while deleting the stock transfer.',
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
</script>

</html>