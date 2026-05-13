<?php
require_once '../../config/db.php';

// Fetch all menu items with category and property details
$stmt = $pdo->query("
    SELECT
        mi.*,
        fc.category_name,
        p.property_name
    FROM menu_items mi
    INNER JOIN food_categories fc ON mi.category_id = fc.category_id
    LEFT JOIN properties p ON mi.property_id = p.property_id
    ORDER BY mi.sort_order ASC, mi.item_name ASC
");
$menuItems = $stmt->fetchAll();

// Fetch all food categories
$stmt = $pdo->query("SELECT category_id, category_name FROM food_categories WHERE is_active = 1 ORDER BY category_name ASC");
$categories = $stmt->fetchAll();

// Fetch all properties
$stmt = $pdo->query("SELECT property_id, property_name FROM properties ORDER BY property_name ASC");
$properties = $stmt->fetchAll();
?>

<script>
  // Store menu items data for JavaScript access
  const menuItemsData = <?php echo json_encode($menuItems); ?>;
  const categoriesData = <?php echo json_encode($categories); ?>;
  const propertiesData = <?php echo json_encode($properties); ?>;
</script>

<?php $assets_path = '../assets';
$page_title = 'Menu Items'; ?>
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
              <h6 class="mb-0 font-bold dark:text-white">Total Items</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($menuItems); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Available</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $availableItems = array_filter($menuItems, function ($i) {
                return $i['is_available'] == 1 && $i['is_active'] == 1;
              });
              ?>
              <h5 class="font-bold text-3xl text-emerald-500"><?php echo count($availableItems); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Featured</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $featuredItems = array_filter($menuItems, function ($i) {
                return $i['is_featured'] == 1;
              });
              ?>
              <h5 class="font-bold text-3xl text-amber-500"><?php echo count($featuredItems); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Inactive</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $inactiveItems = array_filter($menuItems, function ($i) {
                return $i['is_active'] == 0;
              });
              ?>
              <h5 class="font-bold text-3xl text-slate-500"><?php echo count($inactiveItems); ?></h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Menu Items Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Menu Items</h6>
              <button onclick="openAddItemModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> New Item
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="itemTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Item</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Category</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Price</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($menuItems)): ?>
                      <tr>
                        <td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No menu items found. <a href="javascript:;" onclick="openAddItemModal()" class="text-blue-500 font-semibold">Create your first item</a>.
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($menuItems as $index => $item): ?>
                        <tr class="item-row">
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex px-2 py-1">
                              <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-green-500 to-emerald-500">
                                <i class="fas fa-hamburger"></i>
                              </div>
                              <div class="flex flex-col justify-center">
                                <h6 class="mb-0 text-sm leading-normal dark:text-white"><?php echo htmlspecialchars($item['item_name']); ?></h6>
                                <?php if (!empty($item['item_code'])): ?>
                                  <span class="text-xs text-slate-400 dark:text-white/60"><?php echo htmlspecialchars($item['item_code']); ?></span>
                                <?php endif; ?>
                                <?php if ($item['is_featured'] == 1): ?>
                                  <span class="text-xs text-amber-500 font-semibold"><i class="fas fa-star mr-1"></i>Featured</span>
                                <?php endif; ?>
                              </div>
                            </div>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($item['category_name']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo $item['property_id'] ? htmlspecialchars($item['property_name']) : '<span class="text-blue-500">All Properties</span>'; ?></p>
                          </td>
                          <td class="p-2 text-right align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-sm font-bold leading-tight dark:text-white"><?php echo number_format($item['price'], 2); ?></p>
                          </td>
                          <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <?php
                            $statusClass = '';
                            $statusText = '';
                            if ($item['is_active'] == 0) {
                              $statusClass = 'from-slate-500 to-slate-400';
                              $statusText = 'Inactive';
                            } elseif ($item['is_available'] == 0) {
                              $statusClass = 'from-orange-500 to-amber-400';
                              $statusText = 'Unavailable';
                            } else {
                              $statusClass = 'from-emerald-500 to-teal-400';
                              $statusText = 'Available';
                            }
                            ?>
                            <span class="bg-gradient-to-tl <?php echo $statusClass; ?> px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white"><?php echo $statusText; ?></span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex items-center gap-2">
                              <button onclick="toggleItemAvailable('<?php echo $item['item_id']; ?>', <?php echo $item['is_available']; ?>)" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-500/20 hover:text-emerald-600 dark:hover:text-emerald-400 hover:border-emerald-300 dark:hover:border-emerald-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Toggle Availability">
                                <i class="fas fa-toggle-on mr-1.5 text-xs"></i>
                                <span><?php echo $item['is_available'] ? 'Unavailable' : 'Available'; ?></span>
                              </button>
                              <button onclick="toggleItemFeatured('<?php echo $item['item_id']; ?>', <?php echo $item['is_featured']; ?>)" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Toggle Featured">
                                <i class="fas fa-star mr-1.5 text-xs"></i>
                                <span><?php echo $item['is_featured'] ? 'Unfeature' : 'Feature'; ?></span>
                              </button>
                              <button onclick="manageItemImages('<?php echo $item['item_id']; ?>', '<?php echo htmlspecialchars($item['item_name']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-500/20 hover:text-purple-600 dark:hover:text-purple-400 hover:border-purple-300 dark:hover:border-purple-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Manage Images">
                                <i class="fas fa-images mr-1.5 text-xs"></i>
                                <span>Images</span>
                              </button>
                              <button onclick="duplicateItem('<?php echo $item['item_id']; ?>', '<?php echo htmlspecialchars($item['item_name']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Duplicate Item">
                                <i class="fas fa-copy mr-1.5 text-xs"></i>
                                <span>Duplicate</span>
                              </button>
                              <button onclick="viewItemOrderHistory('<?php echo $item['item_id']; ?>', '<?php echo htmlspecialchars($item['item_name']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-cyan-50 dark:hover:bg-cyan-500/20 hover:text-cyan-600 dark:hover:text-cyan-400 hover:border-cyan-300 dark:hover:border-cyan-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Order History">
                                <i class="fas fa-history mr-1.5 text-xs"></i>
                                <span>History</span>
                              </button>
                              <button onclick="openEditItemModal('<?php echo $item['item_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Item">
                                <i class="fas fa-edit mr-1.5 text-xs"></i>
                                <span>Edit</span>
                              </button>
                              <button onclick="deleteItem('<?php echo $item['item_id']; ?>', '<?php echo htmlspecialchars($item['item_name']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Item">
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

      <!-- Add Item Modal -->
      <div id="addItemModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddItemModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Create New Menu Item</h3>
            <button onclick="closeAddItemModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addItemForm" onsubmit="submitAddItem(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Item Name <span class="text-red-500">*</span></label>
                  <input type="text" name="item_name" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Burger, Pasta, Salad">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Item Code</label>
                  <input type="text" name="item_code" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., BRG-001">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Category <span class="text-red-500">*</span></label>
                  <select name="category_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                      <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Property</label>
                  <select name="property_id" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Properties (Global)</option>
                    <?php foreach ($properties as $prop): ?>
                      <option value="<?php echo $prop['property_id']; ?>"><?php echo htmlspecialchars($prop['property_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Price <span class="text-red-500">*</span></label>
                  <input type="number" step="0.01" name="price" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Cost Price</label>
                  <input type="number" step="0.01" name="cost_price" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Description</label>
                  <textarea name="description" rows="3" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Item description..."></textarea>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Sort Order</label>
                  <input type="number" name="sort_order" value="0" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex items-end gap-4">
                  <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Status</label>
                    <select name="is_active" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </div>
                  <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Available</label>
                    <select name="is_available" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                      <option value="1">Yes</option>
                      <option value="0">No</option>
                    </select>
                  </div>
                  <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Featured</label>
                    <select name="is_featured" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                      <option value="0">No</option>
                      <option value="1">Yes</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeAddItemModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Create Item
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Item Modal -->
      <div id="editItemModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditItemModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Menu Item</h3>
            <button onclick="closeEditItemModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="editItemForm" onsubmit="submitEditItem(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="item_id" id="editItemId">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Item Name <span class="text-red-500">*</span></label>
                  <input type="text" name="item_name" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Burger, Pasta, Salad">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Item Code</label>
                  <input type="text" name="item_code" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., BRG-001">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Category <span class="text-red-500">*</span></label>
                  <select name="category_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                      <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Property</label>
                  <select name="property_id" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Properties (Global)</option>
                    <?php foreach ($properties as $prop): ?>
                      <option value="<?php echo $prop['property_id']; ?>"><?php echo htmlspecialchars($prop['property_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Price <span class="text-red-500">*</span></label>
                  <input type="number" step="0.01" name="price" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Cost Price</label>
                  <input type="number" step="0.01" name="cost_price" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Description</label>
                  <textarea name="description" rows="3" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Item description..."></textarea>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Sort Order</label>
                  <input type="number" name="sort_order" value="0" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex items-end gap-4">
                  <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Status</label>
                    <select name="is_active" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </div>
                  <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Available</label>
                    <select name="is_available" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                      <option value="1">Yes</option>
                      <option value="0">No</option>
                    </select>
                  </div>
                  <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Featured</label>
                    <select name="is_featured" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                      <option value="0">No</option>
                      <option value="1">Yes</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeEditItemModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Update Item
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
    const table = document.getElementById('itemTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
      const row = rows[i];
      const cells = row.getElementsByTagName('td');
      let found = false;

      for (let j = 0; j < cells.length; j++) {
        if (cells[j]) {
          const textValue = cells[j].textContent || cells[j].innerText;
          if (textValue.toLowerCase().indexOf(filter) > -1) {
            found = true;
            break;
          }
        }
      }

      row.style.display = found ? '' : 'none';
    }
  }

  // Add Item Modal
  function openAddItemModal() {
    document.getElementById('addItemModal').style.display = 'block';
    document.getElementById('addItemForm').reset();
  }

  function closeAddItemModal() {
    document.getElementById('addItemModal').style.display = 'none';
  }

  function submitAddItem(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // Show loading
    Swal.fire({
      title: 'Creating...',
      text: 'Please wait while we create the menu item.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    // TODO: Implement AJAX call to create item
    // For now, just show success message
    setTimeout(() => {
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Menu item created successfully.',
        confirmButtonColor: '#3085d6'
      }).then(() => {
        closeAddItemModal();
        location.reload();
      });
    }, 1000);
  }

  // Edit Item Modal
  function openEditItemModal(itemId) {
    const item = menuItemsData.find(i => i.item_id == itemId);
    if (!item) return;

    document.getElementById('editItemId').value = item.item_id;
    document.getElementById('editItemForm').elements['item_name'].value = item.item_name;
    document.getElementById('editItemForm').elements['item_code'].value = item.item_code || '';
    document.getElementById('editItemForm').elements['category_id'].value = item.category_id;
    document.getElementById('editItemForm').elements['property_id'].value = item.property_id || '';
    document.getElementById('editItemForm').elements['price'].value = item.price;
    document.getElementById('editItemForm').elements['cost_price'].value = item.cost_price;
    document.getElementById('editItemForm').elements['description'].value = item.description || '';
    document.getElementById('editItemForm').elements['sort_order'].value = item.sort_order;
    document.getElementById('editItemForm').elements['is_active'].value = item.is_active;
    document.getElementById('editItemForm').elements['is_available'].value = item.is_available;
    document.getElementById('editItemForm').elements['is_featured'].value = item.is_featured;

    document.getElementById('editItemModal').style.display = 'block';
  }

  function closeEditItemModal() {
    document.getElementById('editItemModal').style.display = 'none';
  }

  function submitEditItem(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // Show loading
    Swal.fire({
      title: 'Updating...',
      text: 'Please wait while we update the menu item.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    // TODO: Implement AJAX call to update item
    // For now, just show success message
    setTimeout(() => {
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Menu item updated successfully.',
        confirmButtonColor: '#3085d6'
      }).then(() => {
        closeEditItemModal();
        location.reload();
      });
    }, 1000);
  }

  // Delete Item
  function deleteItem(itemId, itemName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to delete "${itemName}". This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        // TODO: Implement AJAX call to delete item
        // For now, just show success message
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'Menu item has been deleted.',
          confirmButtonColor: '#3085d6'
        }).then(() => {
          location.reload();
        });
      }
    });
  }

  // Toggle Item Available
  function toggleItemAvailable(itemId, currentStatus) {
    const newStatus = currentStatus === 1 ? 0 : 1;
    const statusText = newStatus === 1 ? 'mark as available' : 'mark as unavailable';

    Swal.fire({
      title: 'Change Availability?',
      text: `Are you sure you want to ${statusText} this item?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, change it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        // TODO: Implement AJAX call to toggle availability
        Swal.fire({
          icon: 'success',
          title: 'Availability Changed!',
          text: 'Item availability has been updated.',
          confirmButtonColor: '#3085d6'
        }).then(() => {
          location.reload();
        });
      }
    });
  }

  // Toggle Item Featured
  function toggleItemFeatured(itemId, currentStatus) {
    const newStatus = currentStatus === 1 ? 0 : 1;
    const statusText = newStatus === 1 ? 'feature' : 'unfeature';

    Swal.fire({
      title: 'Change Featured Status?',
      text: `Are you sure you want to ${statusText} this item?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, change it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        // TODO: Implement AJAX call to toggle featured
        Swal.fire({
          icon: 'success',
          title: 'Featured Status Changed!',
          text: 'Item featured status has been updated.',
          confirmButtonColor: '#3085d6'
        }).then(() => {
          location.reload();
        });
      }
    });
  }

  // Manage Item Images
  function manageItemImages(itemId, itemName) {
    Swal.fire({
      title: 'Manage Images',
      text: `Manage images for "${itemName}"`,
      icon: 'info',
      confirmButtonColor: '#3085d6'
    });
    // TODO: Implement modal to manage item images
  }

  // Duplicate Item
  function duplicateItem(itemId, itemName) {
    Swal.fire({
      title: 'Duplicate Item?',
      text: `Create a copy of "${itemName}"?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, duplicate it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        // TODO: Implement AJAX call to duplicate item
        Swal.fire({
          icon: 'success',
          title: 'Duplicated!',
          text: 'Menu item has been duplicated.',
          confirmButtonColor: '#3085d6'
        }).then(() => {
          location.reload();
        });
      }
    });
  }

  // View Item Order History
  function viewItemOrderHistory(itemId, itemName) {
    Swal.fire({
      title: 'Order History',
      text: `View order history for "${itemName}"`,
      icon: 'info',
      confirmButtonColor: '#3085d6'
    });
    // TODO: Implement modal to show order history
  }
</script>

</html>