<?php
require_once '../../config/db.php';

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

// Fetch all products with related data
$stmt = $pdo->query("
    SELECT 
        p.*,
        ic.category_name,
        ic.category_code,
        u.uom_code,
        u.uom_name,
        s.company_name as supplier_name
    FROM products p
    LEFT JOIN inventory_categories ic ON p.category_id = ic.category_id
    LEFT JOIN units_of_measure u ON p.uom_id = u.uom_id
    LEFT JOIN suppliers s ON p.preferred_supplier_id = s.supplier_id
    WHERE p.is_active = 1
    ORDER BY p.product_name ASC
");
$products = $stmt->fetchAll();

// Fetch categories
$stmt = $pdo->query("SELECT * FROM inventory_categories WHERE is_active = 1 ORDER BY category_name ASC");
$categories = $stmt->fetchAll();

// Fetch UOMs
$stmt = $pdo->query("SELECT * FROM units_of_measure WHERE is_active = 1 ORDER BY uom_name ASC");
$uoms = $stmt->fetchAll();

// Fetch suppliers
$stmt = $pdo->query("SELECT * FROM suppliers WHERE is_active = 1 ORDER BY company_name ASC");
$suppliers = $stmt->fetchAll();
?>

<script>
  // Store inventory data for JavaScript access
  const productsData = <?php echo json_encode($products); ?>;
  const categoriesData = <?php echo json_encode($categories); ?>;
  const uomsData = <?php echo json_encode($uoms); ?>;
  const suppliersData = <?php echo json_encode($suppliers); ?>;
</script>

<?php $assets_path = '../assets';
$page_title = 'Product'; ?>
<!DOCTYPE html>
<html>

<head>
  <?php include('../../structure/head.php'); ?>
  <style>
    .swal2-popup {
      border-radius: 1rem !important;
    }

    .swal2-popup-high-z {
      z-index: 10000 !important;
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
              <h6 class="mb-0 font-bold dark:text-white">Total Products</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($products); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Categories</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($categories); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Suppliers</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($suppliers); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Low Stock Items</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $lowStock = array_filter($products, function ($p) {
                return $p['current_stock'] <= $p['reorder_point'];
              });
              ?>
              <h5 class="font-bold text-3xl text-red-500 dark:text-red-400"><?php echo count($lowStock); ?></h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Products Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Products</h6>
              <button onclick="openAddProductModal()" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                Add Product
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="productsTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Product</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Category</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Stock</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Unit Cost</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($products)): ?>
                      <tr>
                        <td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No products found. <a href="javascript:;" class="text-blue-500 font-semibold">Add your first product</a>.
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($products as $product): ?>
                        <tr class="product-row">
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex px-2 py-1">
                              <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
                                <i class="fas fa-box"></i>
                              </div>
                              <div class="flex flex-col justify-center">
                                <h6 class="mb-0 text-sm leading-normal dark:text-white"><?php echo htmlspecialchars($product['product_name']); ?></h6>
                                <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400"><?php echo htmlspecialchars($product['product_code']); ?></p>
                              </div>
                            </div>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($product['category_name'] ?? '-'); ?></p>
                            <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400"><?php echo htmlspecialchars($product['category_code'] ?? '-'); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex items-center">
                              <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80 mr-2"><?php echo number_format($product['current_stock'], 3); ?> <?php echo htmlspecialchars($product['uom_code'] ?? ''); ?></p>
                              <?php if ($product['current_stock'] <= $product['reorder_point']): ?>
                                <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">Low</span>
                              <?php endif; ?>
                            </div>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">PHP <?php echo number_format($product['unit_cost'], 2); ?></p>
                          </td>
                          <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Active</span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex items-center gap-2">
                              <button onclick="viewProductDetails('<?php echo $product['product_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
                                <i class="fas fa-info-circle mr-1.5 text-xs"></i>
                                <span>View</span>
                              </button>
                              <button onclick="openEditProductModal('<?php echo $product['product_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Product">
                                <i class="fas fa-edit mr-1.5 text-xs"></i>
                                <span>Edit</span>
                              </button>
                              <button onclick="deleteProduct('<?php echo $product['product_id']; ?>', '<?php echo htmlspecialchars($product['product_name']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Product">
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

      <!-- Product Details Modal -->
      <div id="productDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeProductDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white" id="modalProductName" style="font-size: 1.25rem; font-weight: bold; margin: 0;">Product Details</h3>
            <button onclick="closeProductDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6" id="modalProductContent" style="padding: 1.5rem;">
            <!-- Content will be populated by JavaScript -->
          </div>
        </div>
      </div>

      <!-- Add Product Modal -->
      <div id="addProductModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddProductModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Add New Product</h3>
            <button onclick="closeAddProductModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addProductForm" onsubmit="submitAddProduct(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="md:col-span-2">
                  <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b border-gray-200 dark:border-white/10 pb-2">Basic Information</h4>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Product Code <span class="text-red-500">*</span></label>
                  <input type="text" name="product_code" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., PROD-001">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Product Name <span class="text-red-500">*</span></label>
                  <input type="text" name="product_name" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Premium Shampoo">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Category <span class="text-red-500">*</span></label>
                  <select name="category_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                      <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?> (<?php echo htmlspecialchars($cat['category_code']); ?>)</option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Unit of Measure <span class="text-red-500">*</span></label>
                  <select name="uom_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select UOM</option>
                    <?php foreach ($uoms as $uom): ?>
                      <option value="<?php echo $uom['uom_id']; ?>"><?php echo htmlspecialchars($uom['uom_name']); ?> (<?php echo htmlspecialchars($uom['uom_code']); ?>)</option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Product Type <span class="text-red-500">*</span></label>
                  <select name="product_type" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="consumable">Consumable</option>
                    <option value="non_consumable">Non-Consumable</option>
                    <option value="food">Food</option>
                    <option value="beverage">Beverage</option>
                    <option value="amenity">Amenity</option>
                    <option value="cleaning">Cleaning</option>
                    <option value="linen">Linen</option>
                    <option value="equipment">Equipment</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Brand</label>
                  <input type="text" name="brand" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Dove">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Description</label>
                  <textarea name="description" rows="3" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Product description..."></textarea>
                </div>

                <!-- Costing -->
                <div class="md:col-span-2">
                  <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b border-gray-200 dark:border-white/10 pb-2">Costing</h4>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Unit Cost (PHP)</label>
                  <input type="number" name="unit_cost" step="0.01" min="0" value="0" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Selling Price (PHP)</label>
                  <input type="number" name="selling_price" step="0.01" min="0" value="0" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                </div>

                <!-- Stock Control -->
                <div class="md:col-span-2">
                  <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b border-gray-200 dark:border-white/10 pb-2">Stock Control</h4>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Initial Stock</label>
                  <input type="number" name="initial_stock" step="0.001" min="0" value="0" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Reorder Point</label>
                  <input type="number" name="reorder_point" step="0.001" min="0" value="0" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Minimum Stock</label>
                  <input type="number" name="minimum_stock" step="0.001" min="0" value="0" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Maximum Stock</label>
                  <input type="number" name="maximum_stock" step="0.001" min="0" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Optional">
                </div>

                <!-- Supplier -->
                <div class="md:col-span-2">
                  <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b border-gray-200 dark:border-white/10 pb-2">Supplier</h4>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Preferred Supplier</label>
                  <select name="preferred_supplier_id" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">No Supplier</option>
                    <?php foreach ($suppliers as $sup): ?>
                      <option value="<?php echo $sup['supplier_id']; ?>"><?php echo htmlspecialchars($sup['company_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <!-- Settings -->
                <div class="md:col-span-2">
                  <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b border-gray-200 dark:border-white/10 pb-2">Settings</h4>
                </div>
                <div class="md:col-span-2 flex flex-wrap gap-4">
                  <label class="flex items-center">
                    <input type="checkbox" name="is_for_sale" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-slate-700 dark:border-white/20">
                    <span class="ml-2 text-sm text-slate-600 dark:text-white">Available for Sale</span>
                  </label>
                  <label class="flex items-center">
                    <input type="checkbox" name="is_for_rooms" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-slate-700 dark:border-white/20">
                    <span class="ml-2 text-sm text-slate-600 dark:text-white">For Rooms</span>
                  </label>
                  <label class="flex items-center">
                    <input type="checkbox" name="track_inventory" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-slate-700 dark:border-white/20">
                    <span class="ml-2 text-sm text-slate-600 dark:text-white">Track Inventory</span>
                  </label>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeAddProductModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Save Product
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
    const table = document.getElementById('productsTable');
    const rows = table.getElementsByClassName('product-row');

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

  // View Product Details
  function viewProductDetails(productId) {
    const product = productsData.find(p => p.product_id === productId);
    if (!product) return;

    const modal = document.getElementById('productDetailsModal');
    const modalTitle = document.getElementById('modalProductName');
    const modalContent = document.getElementById('modalProductContent');

    modalTitle.textContent = product.product_name;

    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Basic Information</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Product Code</p>
                <p class="text-sm dark:text-white">${product.product_code}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Product Name</p>
                <p class="text-sm dark:text-white">${product.product_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Category</p>
                <p class="text-sm dark:text-white">${product.category_name || '-'} (${product.category_code || '-'})</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Unit of Measure</p>
                <p class="text-sm dark:text-white">${product.uom_name || '-'} (${product.uom_code || '-'})</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Product Type</p>
                <p class="text-sm dark:text-white">${product.product_type || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Brand</p>
                <p class="text-sm dark:text-white">${product.brand || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Description</p>
                <p class="text-sm dark:text-white">${product.description || '-'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Costing & Stock</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Unit Cost</p>
                <p class="text-sm dark:text-white">PHP ${parseFloat(product.unit_cost).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Selling Price</p>
                <p class="text-sm dark:text-white">PHP ${parseFloat(product.selling_price).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Current Stock</p>
                <p class="text-sm dark:text-white">${parseFloat(product.current_stock).toFixed(3)} ${product.uom_code || ''}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Reorder Point</p>
                <p class="text-sm dark:text-white">${parseFloat(product.reorder_point).toFixed(3)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Minimum Stock</p>
                <p class="text-sm dark:text-white">${parseFloat(product.minimum_stock).toFixed(3)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Maximum Stock</p>
                <p class="text-sm dark:text-white">${product.maximum_stock ? parseFloat(product.maximum_stock).toFixed(3) : '-'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Supplier</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Preferred Supplier</p>
                <p class="text-sm dark:text-white">${product.supplier_name || '-'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Settings</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">For Sale</p>
                <p class="text-sm dark:text-white">${product.is_for_sale ? 'Yes' : 'No'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">For Rooms</p>
                <p class="text-sm dark:text-white">${product.is_for_rooms ? 'Yes' : 'No'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Track Inventory</p>
                <p class="text-sm dark:text-white">${product.track_inventory ? 'Yes' : 'No'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Status</p>
                <p class="text-sm dark:text-white">${product.is_active ? 'Active' : 'Inactive'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Created At</p>
                <p class="text-sm dark:text-white">${new Date(product.created_at).toLocaleString()}</p>
              </div>
            </div>
          </div>
        </div>
      `;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeProductDetailsModal() {
    const modal = document.getElementById('productDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  // Edit Product Modal Functions (placeholder)
  function openEditProductModal(productId) {
    Swal.fire({
      icon: 'info',
      title: 'Coming Soon',
      text: 'Edit product functionality will be implemented soon.',
      confirmButtonColor: '#3085d6'
    });
  }

  // Delete Product Function with SweetAlert
  function deleteProduct(productId, productName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete "${productName}"? This action cannot be undone.`,
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
          title: 'Deleting Product...',
          text: 'Please wait while we delete the product.',
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
        formData.append('action', 'delete_product');
        formData.append('product_id', productId);

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
              text: 'An error occurred while deleting the product.',
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

  // Add Product Modal Functions
  function openAddProductModal() {
    const modal = document.getElementById('addProductModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeAddProductModal() {
    const modal = document.getElementById('addProductModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('addProductForm').reset();
  }

  function submitAddProduct(event) {
    event.preventDefault();

    const form = document.getElementById('addProductForm');
    const formData = new FormData(form);
    formData.append('action', 'add_product');

    // Show loading state with higher z-index
    Swal.fire({
      title: 'Adding Product...',
      text: 'Please wait while we add the product.',
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
        closeAddProductModal();

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
        closeAddProductModal();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred while adding the product.',
          confirmButtonColor: '#d33',
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });
        console.error('Error:', error);
      });
  }
</script>

</html>