<?php
require_once '../../config/db.php';

// Fetch all product stock per location with product, location, and property details
$stmt = $pdo->query("
    SELECT
        psl.*,
        p.product_name,
        p.product_code,
        p.current_stock as total_stock,
        p.reorder_point,
        il.location_name,
        il.location_type,
        prop.property_name
    FROM product_stock_per_location psl
    INNER JOIN products p ON psl.product_id = p.product_id
    INNER JOIN inventory_locations il ON psl.location_id = il.location_id
    INNER JOIN properties prop ON il.property_id = prop.property_id
    WHERE p.is_active = 1 AND il.is_active = 1
    ORDER BY prop.property_name ASC, il.location_name ASC, p.product_name ASC
");
$stockPerLocation = $stmt->fetchAll();

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
  // Store stock data for JavaScript access
  const stockData = <?php echo json_encode($stockPerLocation); ?>;
  const productsData = <?php echo json_encode($products); ?>;
  const locationsData = <?php echo json_encode($locations); ?>;
</script>

<?php $assets_path = '../assets';
$page_title = 'Product Stock Location'; ?>
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
              <h6 class="mb-0 font-bold dark:text-white">Total Stock Records</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($stockPerLocation); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Below Reorder Point</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $belowReorder = array_filter($stockPerLocation, function ($s) {
                return $s['quantity'] < $s['reorder_point'] && $s['reorder_point'] > 0;
              });
              ?>
              <h5 class="font-bold text-3xl text-red-500"><?php echo count($belowReorder); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Zero Stock</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $zeroStock = array_filter($stockPerLocation, function ($s) {
                return $s['quantity'] == 0;
              });
              ?>
              <h5 class="font-bold text-3xl text-orange-500"><?php echo count($zeroStock); ?></h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Stock Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Stock by Location</h6>
              <button onclick="openAddStockModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Adjust Stock
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="stockTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Product</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Location</th>
                      <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Quantity</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($stockPerLocation)): ?>
                      <tr>
                        <td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No stock records found.
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($stockPerLocation as $index => $stock): ?>
                        <tr class="stock-row">
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex px-2 py-1">
                              <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
                                <i class="fas fa-box"></i>
                              </div>
                              <div class="flex flex-col justify-center">
                                <h6 class="mb-0 text-sm leading-normal dark:text-white"><?php echo htmlspecialchars($stock['product_name']); ?></h6>
                                <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400"><?php echo htmlspecialchars($stock['product_code']); ?></p>
                              </div>
                            </div>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($stock['property_name']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($stock['location_name']); ?></p>
                            <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400"><?php echo ucfirst(str_replace('_', ' ', $stock['location_type'])); ?></p>
                          </td>
                          <td class="p-2 text-right align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-sm font-bold leading-tight dark:text-white"><?php echo number_format($stock['quantity'], 3); ?></p>
                          </td>
                          <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <?php if ($stock['quantity'] == 0): ?>
                              <span class="bg-gradient-to-tl from-orange-500 to-amber-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Zero</span>
                            <?php elseif ($stock['quantity'] < $stock['reorder_point'] && $stock['reorder_point'] > 0): ?>
                              <span class="bg-gradient-to-tl from-red-500 to-rose-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Low Stock</span>
                            <?php else: ?>
                              <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">In Stock</span>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex items-center gap-2">
                              <button onclick="viewStockDetails(<?php echo $stock['stock_loc_id']; ?>)" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
                                <i class="fas fa-info-circle mr-1.5 text-xs"></i>
                                <span>View</span>
                              </button>
                              <button onclick="openEditStockModal(<?php echo $stock['stock_loc_id']; ?>)" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Adjust Stock">
                                <i class="fas fa-edit mr-1.5 text-xs"></i>
                                <span>Adjust</span>
                              </button>
                              <button onclick="deleteStock(<?php echo $stock['stock_loc_id']; ?>, '<?php echo htmlspecialchars($stock['product_name']); ?>', '<?php echo htmlspecialchars($stock['location_name']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Stock">
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

      <!-- View Stock Details Modal -->
      <div id="stockDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeStockDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white" id="stockModalTitle" style="font-size: 1.25rem; font-weight: bold; margin: 0;">Stock Details</h3>
            <button onclick="closeStockDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6" id="stockModalContent" style="padding: 1.5rem;">
            <!-- Content will be populated by JavaScript -->
          </div>
        </div>
      </div>

      <!-- Add Stock Modal -->
      <div id="addStockModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddStockModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 600px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Add Stock Adjustment</h3>
            <button onclick="closeAddStockModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addStockForm" onsubmit="submitAddStock(event)">
            <div class="p-6">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Product <span class="text-red-500">*</span></label>
                  <select name="product_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Product</option>
                    <?php foreach ($products as $prod): ?>
                      <option value="<?php echo $prod['product_id']; ?>"><?php echo htmlspecialchars($prod['product_name']); ?> (<?php echo htmlspecialchars($prod['product_code']); ?>)</option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Location <span class="text-red-500">*</span></label>
                  <select name="location_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Location</option>
                    <?php foreach ($locations as $loc): ?>
                      <option value="<?php echo $loc['location_id']; ?>"><?php echo htmlspecialchars($loc['property_name']); ?> - <?php echo htmlspecialchars($loc['location_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Adjustment Type <span class="text-red-500">*</span></label>
                  <select name="adjustment_type" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="add">Add Stock (+)</option>
                    <option value="deduct">Deduct Stock (-)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Quantity <span class="text-red-500">*</span></label>
                  <input type="number" name="quantity" step="0.001" min="0" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.000">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Reason for adjustment..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeAddStockModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Save Adjustment
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Stock Modal -->
      <div id="editStockModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditStockModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 600px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Stock</h3>
            <button onclick="closeEditStockModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="editStockForm" onsubmit="submitEditStock(event)">
            <div class="p-6">
              <div class="space-y-4">
                <input type="hidden" name="stock_loc_id" id="editStockLocId">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Product</label>
                  <input type="text" id="editProductName" readonly class="w-full px-4 py-2 text-sm border border-gray-200 dark:border-white/20 dark:bg-slate-700/30 dark:text-white rounded-lg bg-gray-50">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Location</label>
                  <input type="text" id="editLocationName" readonly class="w-full px-4 py-2 text-sm border border-gray-200 dark:border-white/20 dark:bg-slate-700/30 dark:text-white rounded-lg bg-gray-50">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Current Quantity</label>
                  <input type="text" id="editCurrentQuantity" readonly class="w-full px-4 py-2 text-sm border border-gray-200 dark:border-white/20 dark:bg-slate-700/30 dark:text-white rounded-lg bg-gray-50">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Adjustment Type <span class="text-red-500">*</span></label>
                  <select name="adjustment_type" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="add">Add Stock (+)</option>
                    <option value="deduct">Deduct Stock (-)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Quantity <span class="text-red-500">*</span></label>
                  <input type="number" name="quantity" step="0.001" min="0" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.000">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Reason for adjustment..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeEditStockModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Save Adjustment
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
    const table = document.getElementById('stockTable');
    const rows = table.getElementsByClassName('stock-row');

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

  // View Stock Details
  function viewStockDetails(stockLocId) {
    const stock = stockData.find(s => s.stock_loc_id === stockLocId);
    if (!stock) return;

    const modal = document.getElementById('stockDetailsModal');
    const modalTitle = document.getElementById('stockModalTitle');
    const modalContent = document.getElementById('stockModalContent');

    modalTitle.textContent = `${stock.product_name} - ${stock.location_name}`;

    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Product Information</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Product Name</p>
                <p class="text-sm dark:text-white">${stock.product_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Product Code</p>
                <p class="text-sm dark:text-white">${stock.product_code}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Total Stock (All Locations)</p>
                <p class="text-sm dark:text-white">${parseFloat(stock.total_stock).toFixed(3)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Reorder Point</p>
                <p class="text-sm dark:text-white">${parseFloat(stock.reorder_point).toFixed(3)}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Location Information</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Property</p>
                <p class="text-sm dark:text-white">${stock.property_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Location</p>
                <p class="text-sm dark:text-white">${stock.location_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Location Type</p>
                <p class="text-sm dark:text-white">${stock.location_type.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Stock at This Location</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Current Quantity</p>
                <p class="text-2xl font-bold dark:text-white">${parseFloat(stock.quantity).toFixed(3)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Status</p>
                ${stock.quantity == 0
                  ? '<span class="bg-gradient-to-tl from-orange-500 to-amber-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Zero</span>'
                  : stock.quantity < stock.reorder_point && stock.reorder_point > 0
                  ? '<span class="bg-gradient-to-tl from-red-500 to-rose-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Low Stock</span>'
                  : '<span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">In Stock</span>'
                }
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Timestamp</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Last Updated</p>
                <p class="text-sm dark:text-white">${new Date(stock.updated_at).toLocaleString()}</p>
              </div>
            </div>
          </div>
        </div>
      `;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeStockDetailsModal() {
    const modal = document.getElementById('stockDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  // Add Stock Modal Functions
  function openAddStockModal() {
    const modal = document.getElementById('addStockModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeAddStockModal() {
    const modal = document.getElementById('addStockModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('addStockForm').reset();
  }

  function submitAddStock(event) {
    event.preventDefault();

    const form = document.getElementById('addStockForm');
    const formData = new FormData(form);
    formData.append('action', 'add_stock_per_location');

    Swal.fire({
      title: 'Processing...',
      text: 'Please wait while we process the stock adjustment.',
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
        closeAddStockModal();

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
        closeAddStockModal();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred while processing the stock adjustment.',
          confirmButtonColor: '#d33',
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });
        console.error('Error:', error);
      });
  }

  // Edit Stock Modal Functions
  function openEditStockModal(stockLocId) {
    const stock = stockData.find(s => s.stock_loc_id === stockLocId);
    if (!stock) return;

    document.getElementById('editStockLocId').value = stock.stock_loc_id;
    document.getElementById('editProductName').value = `${stock.product_name} (${stock.product_code})`;
    document.getElementById('editLocationName').value = `${stock.property_name} - ${stock.location_name}`;
    document.getElementById('editCurrentQuantity').value = parseFloat(stock.quantity).toFixed(3);

    const modal = document.getElementById('editStockModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeEditStockModal() {
    const modal = document.getElementById('editStockModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('editStockForm').reset();
  }

  function submitEditStock(event) {
    event.preventDefault();

    const form = document.getElementById('editStockForm');
    const formData = new FormData(form);
    formData.append('action', 'edit_stock_per_location');

    Swal.fire({
      title: 'Processing...',
      text: 'Please wait while we process the stock adjustment.',
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
        closeEditStockModal();

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
        closeEditStockModal();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred while processing the stock adjustment.',
          confirmButtonColor: '#d33',
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });
        console.error('Error:', error);
      });
  }

  // Delete Stock Function with SweetAlert
  function deleteStock(stockLocId, productName, locationName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete the stock record for "${productName}" at "${locationName}"? This action cannot be undone.`,
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
          text: 'Please wait while we delete the stock record.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });

        const formData = new FormData();
        formData.append('action', 'delete_stock_per_location');
        formData.append('stock_loc_id', stockLocId);

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
              text: 'An error occurred while deleting the stock record.',
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