<?php
require_once '../../config/db.php';

// Handle Add Category Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_category') {
  try {
    $pdo->beginTransaction();

    $category_id = generateUUID();
    $property_id = $_POST['property_id'] ?? '';
    $category_code = $_POST['category_code'] ?? '';
    $category_name = $_POST['category_name'] ?? '';

    if (empty($property_id) || empty($category_code) || empty($category_name)) {
      throw new Exception("Property, Category Code, and Category Name are required fields.");
    }

    // Check if category code already exists for this property
    $stmt = $pdo->prepare("SELECT category_id FROM room_categories WHERE property_id = ? AND category_code = ? AND is_active = 1");
    $stmt->execute([$property_id, $category_code]);
    if ($stmt->fetch()) {
      throw new Exception("Category code '$category_code' already exists for this property.");
    }

    $stmt = $pdo->prepare("
            INSERT INTO room_categories (
                category_id, property_id, category_code, category_name, description,
                base_price_per_night, base_price_per_hour, base_price_day_use, minimum_hours,
                max_adult_capacity, max_child_capacity, max_total_capacity,
                extra_adult_fee, extra_child_fee, extra_senior_fee,
                floor_area_sqm, bed_configuration, view_type, floor_level,
                smoking_policy, pet_friendly,
                allows_hourly, allows_overnight, allows_day_use,
                sort_order, is_active, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), NOW())
        ");
    $stmt->execute([
      $category_id,
      $property_id,
      $category_code,
      $category_name,
      $_POST['description'] ?? null,
      $_POST['base_price_per_night'] ?? 0,
      $_POST['base_price_per_hour'] ?? 0,
      $_POST['base_price_day_use'] ?? 0,
      $_POST['minimum_hours'] ?? 2,
      $_POST['max_adult_capacity'] ?? 2,
      $_POST['max_child_capacity'] ?? 2,
      $_POST['max_total_capacity'] ?? 4,
      $_POST['extra_adult_fee'] ?? 0,
      $_POST['extra_child_fee'] ?? 0,
      $_POST['extra_senior_fee'] ?? 0,
      $_POST['floor_area_sqm'] ?? null,
      $_POST['bed_configuration'] ?? null,
      $_POST['view_type'] ?? null,
      $_POST['floor_level'] ?? null,
      $_POST['smoking_policy'] ?? 'non_smoking',
      $_POST['pet_friendly'] ?? 0,
      $_POST['allows_hourly'] ?? 1,
      $_POST['allows_overnight'] ?? 1,
      $_POST['allows_day_use'] ?? 1,
      $_POST['sort_order'] ?? 0
    ]);

    $pdo->commit();
    header('Location: room_categories.php?success=category_added');
    exit;
  } catch (Exception $e) {
    $pdo->rollBack();
    $error_message = $e->getMessage();
  }
}

// Handle Edit Category Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_category') {
  try {
    $pdo->beginTransaction();

    $category_id = $_POST['category_id'] ?? '';
    $property_id = $_POST['property_id'] ?? '';
    $category_code = $_POST['category_code'] ?? '';
    $category_name = $_POST['category_name'] ?? '';

    if (empty($category_id) || empty($property_id) || empty($category_code) || empty($category_name)) {
      throw new Exception("Category ID, Property, Category Code, and Category Name are required fields.");
    }

    // Check if category code already exists for this property (excluding current category)
    $stmt = $pdo->prepare("SELECT category_id FROM room_categories WHERE property_id = ? AND category_code = ? AND category_id != ? AND is_active = 1");
    $stmt->execute([$property_id, $category_code, $category_id]);
    if ($stmt->fetch()) {
      throw new Exception("Category code '$category_code' already exists for this property.");
    }

    $stmt = $pdo->prepare("
            UPDATE room_categories SET
                property_id = ?,
                category_code = ?,
                category_name = ?,
                description = ?,
                base_price_per_night = ?,
                base_price_per_hour = ?,
                base_price_day_use = ?,
                minimum_hours = ?,
                max_adult_capacity = ?,
                max_child_capacity = ?,
                max_total_capacity = ?,
                extra_adult_fee = ?,
                extra_child_fee = ?,
                extra_senior_fee = ?,
                floor_area_sqm = ?,
                bed_configuration = ?,
                view_type = ?,
                floor_level = ?,
                smoking_policy = ?,
                pet_friendly = ?,
                allows_hourly = ?,
                allows_overnight = ?,
                allows_day_use = ?,
                sort_order = ?,
                updated_at = NOW()
            WHERE category_id = ?
        ");
    $stmt->execute([
      $property_id,
      $category_code,
      $category_name,
      $_POST['description'] ?? null,
      $_POST['base_price_per_night'] ?? 0,
      $_POST['base_price_per_hour'] ?? 0,
      $_POST['base_price_day_use'] ?? 0,
      $_POST['minimum_hours'] ?? 2,
      $_POST['max_adult_capacity'] ?? 2,
      $_POST['max_child_capacity'] ?? 2,
      $_POST['max_total_capacity'] ?? 4,
      $_POST['extra_adult_fee'] ?? 0,
      $_POST['extra_child_fee'] ?? 0,
      $_POST['extra_senior_fee'] ?? 0,
      $_POST['floor_area_sqm'] ?? null,
      $_POST['bed_configuration'] ?? null,
      $_POST['view_type'] ?? null,
      $_POST['floor_level'] ?? null,
      $_POST['smoking_policy'] ?? 'non_smoking',
      $_POST['pet_friendly'] ?? 0,
      $_POST['allows_hourly'] ?? 1,
      $_POST['allows_overnight'] ?? 1,
      $_POST['allows_day_use'] ?? 1,
      $_POST['sort_order'] ?? 0,
      $category_id
    ]);

    $pdo->commit();
    header('Location: room_categories.php?success=category_updated');
    exit;
  } catch (Exception $e) {
    $pdo->rollBack();
    $error_message = $e->getMessage();
  }
}

// Handle Delete Category Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_category') {
  try {
    $category_id = $_POST['category_id'] ?? '';

    if (empty($category_id)) {
      throw new Exception("Category ID is required");
    }

    $pdo->beginTransaction();

    // Check if category has rooms
    $stmt = $pdo->prepare("SELECT COUNT(*) as room_count FROM rooms WHERE category_id = ? AND is_active = 1");
    $stmt->execute([$category_id]);
    $result = $stmt->fetch();

    if ($result['room_count'] > 0) {
      throw new Exception("Cannot delete category. It has {$result['room_count']} room(s) associated with it.");
    }

    // Soft delete - set is_active = 0
    $stmt = $pdo->prepare("UPDATE room_categories SET is_active = 0 WHERE category_id = ?");
    $stmt->execute([$category_id]);

    $pdo->commit();
    header('Location: room_categories.php?success=category_deleted');
    exit;
  } catch (Exception $e) {
    $pdo->rollBack();
    $error_message = $e->getMessage();
  }
}

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

// Fetch properties for dropdown
$stmt = $pdo->query("SELECT property_id, property_name, property_code FROM properties WHERE is_active = 1 ORDER BY property_name ASC");
$properties = $stmt->fetchAll();

// Fetch all room categories with related data
$stmt = $pdo->query("
    SELECT 
        rc.*,
        p.property_name,
        p.property_code
    FROM room_categories rc
    INNER JOIN properties p ON rc.property_id = p.property_id
    WHERE rc.is_active = 1
    ORDER BY p.property_name ASC, rc.sort_order ASC, rc.category_name ASC
");
$categories = $stmt->fetchAll();
?>

<script>
  // Store categories data for JavaScript access
  const categoriesData = <?php echo json_encode($categories); ?>;
</script>

<?php $assets_path = '../assets';
$page_title = 'Room Categories'; ?>
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
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Room Categories</h6>
              <button onclick="openAddCategoryModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Add Category
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="categoriesTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Category</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Capacity</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Base Price (Night)</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($categories)): ?>
                      <tr>
                        <td colspan="5" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No room categories found. Add a category to get started.
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($categories as $index => $category): ?>
                        <tr class="category-row">
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex px-2 py-1">
                              <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
                                <i class="fas fa-layer-group"></i>
                              </div>
                              <div class="flex flex-col justify-center">
                                <h6 class="mb-0 text-sm leading-normal dark:text-white"><?php echo htmlspecialchars($category['category_name']); ?></h6>
                                <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400"><?php echo htmlspecialchars($category['category_code']); ?></p>
                              </div>
                            </div>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($category['property_name']); ?></p>
                            <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400"><?php echo htmlspecialchars($category['property_code']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo $category['max_total_capacity']; ?> guests</p>
                            <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400"><?php echo $category['max_adult_capacity']; ?> adults, <?php echo $category['max_child_capacity']; ?> children</p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">PHP <?php echo number_format($category['base_price_per_night'], 2); ?></p>
                            <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400">per night</p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex items-center gap-2">
                              <button onclick="viewCategoryDetails('<?php echo $category['category_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
                                <i class="fas fa-info-circle mr-1.5 text-xs"></i>
                                <span>View</span>
                              </button>
                              <button onclick="openEditCategoryModal('<?php echo $category['category_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Category">
                                <i class="fas fa-edit mr-1.5 text-xs"></i>
                                <span>Edit</span>
                              </button>
                              <button onclick="deleteCategory('<?php echo $category['category_id']; ?>', '<?php echo htmlspecialchars($category['category_name']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Category">
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

      <!-- Category Details Modal -->
      <div id="categoryDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCategoryDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white" id="modalCategoryName" style="font-size: 1.25rem; font-weight: bold; margin: 0;">Category Details</h3>
            <button onclick="closeCategoryDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6" id="modalCategoryContent" style="padding: 1.5rem;">
            <!-- Content will be populated by JavaScript -->
          </div>
        </div>
      </div>

      <!-- Add/Edit Category Modal -->
      <div id="categoryFormModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCategoryFormModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white" id="formModalTitle">Add Category</h3>
            <button onclick="closeCategoryFormModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white">
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>
          <form id="categoryForm" method="POST" action="room_categories.php" class="p-6">
            <input type="hidden" name="action" id="formAction" value="add_category">
            <input type="hidden" name="category_id" id="formCategoryId">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Basic Information -->
              <div class="md:col-span-2">
                <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b pb-2">Basic Information</h4>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Property *</label>
                <select name="property_id" id="formPropertyId" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
                  <option value="">Select Property</option>
                  <?php foreach ($properties as $prop): ?>
                    <option value="<?php echo $prop['property_id']; ?>"><?php echo htmlspecialchars($prop['property_name'] . ' (' . $prop['property_code'] . ')'); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Category Code *</label>
                <input type="text" name="category_code" id="formCategoryCode" required maxlength="30" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500" placeholder="e.g. STD, DLX, STE">
              </div>

              <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Category Name *</label>
                <input type="text" name="category_name" id="formCategoryName" required maxlength="100" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500" placeholder="e.g. Standard Room, Deluxe Suite">
              </div>

              <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Description</label>
                <textarea name="description" id="formDescription" rows="3" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500" placeholder="Category description..."></textarea>
              </div>

              <!-- Pricing -->
              <div class="md:col-span-2 mt-4">
                <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b pb-2">Pricing</h4>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Base Price Per Night (PHP)</label>
                <input type="number" name="base_price_per_night" id="formBasePriceNight" step="0.01" min="0" value="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Base Price Per Hour (PHP)</label>
                <input type="number" name="base_price_per_hour" id="formBasePriceHour" step="0.01" min="0" value="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Base Price Day Use (PHP)</label>
                <input type="number" name="base_price_day_use" id="formBasePriceDayUse" step="0.01" min="0" value="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Minimum Hours</label>
                <input type="number" name="minimum_hours" id="formMinimumHours" min="1" max="24" value="2" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <!-- Capacity -->
              <div class="md:col-span-2 mt-4">
                <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b pb-2">Capacity</h4>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Max Adult Capacity</label>
                <input type="number" name="max_adult_capacity" id="formMaxAdultCapacity" min="0" value="2" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Max Child Capacity</label>
                <input type="number" name="max_child_capacity" id="formMaxChildCapacity" min="0" value="2" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Max Total Capacity</label>
                <input type="number" name="max_total_capacity" id="formMaxTotalCapacity" min="0" value="4" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div></div>

              <!-- Extra Fees -->
              <div class="md:col-span-2 mt-4">
                <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b pb-2">Extra Fees</h4>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Extra Adult Fee (PHP)</label>
                <input type="number" name="extra_adult_fee" id="formExtraAdultFee" step="0.01" min="0" value="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Extra Child Fee (PHP)</label>
                <input type="number" name="extra_child_fee" id="formExtraChildFee" step="0.01" min="0" value="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Extra Senior Fee (PHP)</label>
                <input type="number" name="extra_senior_fee" id="formExtraSeniorFee" step="0.01" min="0" value="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div></div>

              <!-- Physical Details -->
              <div class="md:col-span-2 mt-4">
                <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b pb-2">Physical Details</h4>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Floor Area (sqm)</label>
                <input type="number" name="floor_area_sqm" id="formFloorArea" step="0.01" min="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Bed Configuration</label>
                <input type="text" name="bed_configuration" id="formBedConfiguration" maxlength="150" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500" placeholder="e.g. 1 King Bed + 1 Sofa Bed">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">View Type</label>
                <input type="text" name="view_type" id="formViewType" maxlength="60" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500" placeholder="e.g. ocean, mountain, garden">
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Floor Level</label>
                <input type="text" name="floor_level" id="formFloorLevel" maxlength="30" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>

              <!-- Policies -->
              <div class="md:col-span-2 mt-4">
                <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b pb-2">Policies</h4>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Smoking Policy</label>
                <select name="smoking_policy" id="formSmokingPolicy" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
                  <option value="non_smoking">Non Smoking</option>
                  <option value="smoking">Smoking</option>
                  <option value="designated_area">Designated Area</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Pet Friendly</label>
                <select name="pet_friendly" id="formPetFriendly" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
                  <option value="0">No</option>
                  <option value="1">Yes</option>
                </select>
              </div>

              <!-- Booking Options -->
              <div class="md:col-span-2 mt-4">
                <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4 border-b pb-2">Booking Options</h4>
              </div>

              <div>
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="allows_hourly" id="formAllowsHourly" value="1" checked class="rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                  <span class="text-xs font-semibold text-slate-500 dark:text-white/60">Allows Hourly</span>
                </label>
              </div>

              <div>
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="allows_overnight" id="formAllowsOvernight" value="1" checked class="rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                  <span class="text-xs font-semibold text-slate-500 dark:text-white/60">Allows Overnight</span>
                </label>
              </div>

              <div>
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="allows_day_use" id="formAllowsDayUse" value="1" checked class="rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                  <span class="text-xs font-semibold text-slate-500 dark:text-white/60">Allows Day Use</span>
                </label>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-white/60 mb-1">Sort Order</label>
                <input type="number" name="sort_order" id="formSortOrder" min="0" value="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:outline-none focus:border-blue-500">
              </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
              <button type="button" onclick="closeCategoryFormModal()" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
              <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors">Save Category</button>
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
  function viewCategoryDetails(categoryId) {
    const category = categoriesData.find(c => c.category_id === categoryId);
    if (!category) return;

    const modal = document.getElementById('categoryDetailsModal');
    const modalName = document.getElementById('modalCategoryName');
    const modalContent = document.getElementById('modalCategoryContent');

    modalName.textContent = category.category_name;

    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Basic Information</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Category Code</p>
                <p class="text-sm font-semibold dark:text-white">${category.category_code}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Category Name</p>
                <p class="text-sm dark:text-white">${category.category_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Description</p>
                <p class="text-sm dark:text-white">${category.description || '-'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Property</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Property Name</p>
                <p class="text-sm font-semibold dark:text-white">${category.property_name}</p>
                <p class="text-xs text-slate-400 dark:text-white/60">${category.property_code}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Pricing</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Base Price Per Night</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(category.base_price_per_night || 0).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Base Price Per Hour</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(category.base_price_per_hour || 0).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Base Price Day Use</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(category.base_price_day_use || 0).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Minimum Hours</p>
                <p class="text-sm dark:text-white">${category.minimum_hours || 2} hours</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Capacity</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Max Adult Capacity</p>
                <p class="text-sm font-semibold dark:text-white">${category.max_adult_capacity} adults</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Max Child Capacity</p>
                <p class="text-sm font-semibold dark:text-white">${category.max_child_capacity} children</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Max Total Capacity</p>
                <p class="text-sm font-semibold dark:text-white">${category.max_total_capacity} guests</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Extra Fees</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Extra Adult Fee</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(category.extra_adult_fee || 0).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Extra Child Fee</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(category.extra_child_fee || 0).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Extra Senior Fee</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(category.extra_senior_fee || 0).toFixed(2)}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Physical Details</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Floor Area</p>
                <p class="text-sm dark:text-white">${category.floor_area_sqm ? category.floor_area_sqm + ' sqm' : '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Bed Configuration</p>
                <p class="text-sm dark:text-white">${category.bed_configuration || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">View Type</p>
                <p class="text-sm dark:text-white">${category.view_type || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Floor Level</p>
                <p class="text-sm dark:text-white">${category.floor_level || '-'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Policies</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Smoking Policy</p>
                <p class="text-sm dark:text-white">${category.smoking_policy ? category.smoking_policy.replace(/_/g, ' ').toUpperCase() : 'NON SMOKING'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Pet Friendly</p>
                <p class="text-sm dark:text-white">${category.pet_friendly ? 'Yes' : 'No'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Booking Options</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Allows Hourly</p>
                <p class="text-sm dark:text-white">${category.allows_hourly ? 'Yes' : 'No'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Allows Overnight</p>
                <p class="text-sm dark:text-white">${category.allows_overnight ? 'Yes' : 'No'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Allows Day Use</p>
                <p class="text-sm dark:text-white">${category.allows_day_use ? 'Yes' : 'No'}</p>
              </div>
            </div>
          </div>

          <div class="md:col-span-2">
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">System Information</h4>
            <div class="grid grid-cols-3 gap-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Sort Order</p>
                <p class="text-sm dark:text-white">${category.sort_order || 0}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Status</p>
                <p class="text-sm dark:text-white">${category.is_active ? 'Active' : 'Inactive'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Created At</p>
                <p class="text-sm dark:text-white">${new Date(category.created_at).toLocaleString()}</p>
              </div>
            </div>
          </div>
        </div>
      `;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeCategoryDetailsModal() {
    const modal = document.getElementById('categoryDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  // Add Category Modal Functions
  function openAddCategoryModal() {
    const modal = document.getElementById('categoryFormModal');
    const form = document.getElementById('categoryForm');
    const title = document.getElementById('formModalTitle');

    // Reset form
    form.reset();
    document.getElementById('formAction').value = 'add_category';
    document.getElementById('formCategoryId').value = '';
    title.textContent = 'Add Category';

    // Set default checkbox values
    document.getElementById('formAllowsHourly').checked = true;
    document.getElementById('formAllowsOvernight').checked = true;
    document.getElementById('formAllowsDayUse').checked = true;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  // Edit Category Modal Functions
  function openEditCategoryModal(categoryId) {
    const category = categoriesData.find(c => c.category_id === categoryId);
    if (!category) return;

    const modal = document.getElementById('categoryFormModal');
    const form = document.getElementById('categoryForm');
    const title = document.getElementById('formModalTitle');

    // Set form values
    document.getElementById('formAction').value = 'edit_category';
    document.getElementById('formCategoryId').value = category.category_id;
    title.textContent = 'Edit Category';

    document.getElementById('formPropertyId').value = category.property_id;
    document.getElementById('formCategoryCode').value = category.category_code;
    document.getElementById('formCategoryName').value = category.category_name;
    document.getElementById('formDescription').value = category.description || '';
    document.getElementById('formBasePriceNight').value = category.base_price_per_night || 0;
    document.getElementById('formBasePriceHour').value = category.base_price_per_hour || 0;
    document.getElementById('formBasePriceDayUse').value = category.base_price_day_use || 0;
    document.getElementById('formMinimumHours').value = category.minimum_hours || 2;
    document.getElementById('formMaxAdultCapacity').value = category.max_adult_capacity || 2;
    document.getElementById('formMaxChildCapacity').value = category.max_child_capacity || 2;
    document.getElementById('formMaxTotalCapacity').value = category.max_total_capacity || 4;
    document.getElementById('formExtraAdultFee').value = category.extra_adult_fee || 0;
    document.getElementById('formExtraChildFee').value = category.extra_child_fee || 0;
    document.getElementById('formExtraSeniorFee').value = category.extra_senior_fee || 0;
    document.getElementById('formFloorArea').value = category.floor_area_sqm || '';
    document.getElementById('formBedConfiguration').value = category.bed_configuration || '';
    document.getElementById('formViewType').value = category.view_type || '';
    document.getElementById('formFloorLevel').value = category.floor_level || '';
    document.getElementById('formSmokingPolicy').value = category.smoking_policy || 'non_smoking';
    document.getElementById('formPetFriendly').value = category.pet_friendly || '0';
    document.getElementById('formAllowsHourly').checked = category.allows_hourly == 1;
    document.getElementById('formAllowsOvernight').checked = category.allows_overnight == 1;
    document.getElementById('formAllowsDayUse').checked = category.allows_day_use == 1;
    document.getElementById('formSortOrder').value = category.sort_order || 0;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeCategoryFormModal() {
    const modal = document.getElementById('categoryFormModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  // Delete Category Function with SweetAlert
  function deleteCategory(categoryId, categoryName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to delete "${categoryName}". This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'room_categories.php';

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete_category';

        const categoryIdInput = document.createElement('input');
        categoryIdInput.type = 'hidden';
        categoryIdInput.name = 'category_id';
        categoryIdInput.value = categoryId;

        form.appendChild(actionInput);
        form.appendChild(categoryIdInput);
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  // Show SweetAlert on page load for success/error messages
  <?php if (isset($_GET['success'])): ?>
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: <?php
            switch ($_GET['success']) {
              case 'category_added':
                echo "'Category added successfully!'";
                break;
              case 'category_updated':
                echo "'Category updated successfully!'";
                break;
              case 'category_deleted':
                echo "'Category deleted successfully!'";
                break;
              default:
                echo "'Operation completed successfully!'";
            }
            ?>,
      timer: 2000,
      showConfirmButton: false
    });
  <?php endif; ?>

  <?php if (isset($error_message)): ?>
    Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: <?php echo json_encode($error_message); ?>,
      confirmButtonColor: '#3085d6'
    });
  <?php endif; ?>

  // Filter table function
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('categoriesTable');
    const rows = table.getElementsByClassName('category-row');

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

</html>