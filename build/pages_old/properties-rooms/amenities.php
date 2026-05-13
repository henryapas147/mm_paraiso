<?php
require_once '../../config/db.php';

// Handle Delete Amenity Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_amenity') {
  try {
    error_log("Starting amenity deletion process...");

    $amenity_id = $_POST['amenity_id'] ?? '';
    error_log("Deleting amenity_id: " . $amenity_id);

    if (empty($amenity_id)) {
      throw new Exception("Amenity ID is required");
    }

    // Start transaction
    $pdo->beginTransaction();

    // Delete amenity from database (hard delete)
    $stmt = $pdo->prepare("DELETE FROM amenities WHERE amenity_id = ?");
    $stmt->execute([$amenity_id]);
    error_log("Deleted amenity from database");

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: amenities.php?success=amenity_deleted');
    exit;
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("Error deleting amenity: " . $e->getMessage());
    $error_message = "Failed to delete amenity. Please try again. Error: " . $e->getMessage();
  }
}

// Handle Edit Amenity Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_amenity') {
  try {
    error_log("Starting amenity edit process...");

    $amenity_id = $_POST['amenity_id'] ?? '';
    error_log("Editing amenity_id: " . $amenity_id);

    if (empty($amenity_id)) {
      throw new Exception("Amenity ID is required");
    }

    // Start transaction
    $pdo->beginTransaction();

    // Update amenity data
    $stmt = $pdo->prepare("
            UPDATE amenities SET
                amenity_name = ?,
                icon_code = ?,
                category = ?,
                is_active = ?,
                sort_order = ?
            WHERE amenity_id = ?
        ");

    $stmt->execute([
      $_POST['amenity_name'],
      $_POST['icon_code'] ?? null,
      $_POST['category'] ?? 'comfort',
      $_POST['is_active'] ?? 1,
      $_POST['sort_order'] ?? 0,
      $amenity_id
    ]);

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: amenities.php?success=amenity_updated');
    exit;
  } catch (PDOException $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("PDO Error editing amenity: " . $e->getMessage());
    $error_message = "Failed to update amenity. Please try again. Error: " . $e->getMessage();
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("General Error editing amenity: " . $e->getMessage());
    $error_message = "Failed to update amenity. Please try again. Error: " . $e->getMessage();
  }
}

// Handle Add Amenity Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_amenity') {
  try {
    error_log("Starting amenity addition process...");

    // Start transaction
    $pdo->beginTransaction();

    // Insert amenity data
    $stmt = $pdo->prepare("
            INSERT INTO amenities (
                amenity_name, icon_code, category, is_active, sort_order
            ) VALUES (?, ?, ?, ?, ?)
        ");

    $params = [
      $_POST['amenity_name'],
      $_POST['icon_code'] ?? null,
      $_POST['category'] ?? 'comfort',
      $_POST['is_active'] ?? 1,
      $_POST['sort_order'] ?? 0
    ];

    error_log("Executing amenity insert with params: " . json_encode($params));
    $stmt->execute($params);
    error_log("Amenity inserted successfully");

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: amenities.php?success=amenity_added');
    exit;
  } catch (PDOException $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("PDO Error adding amenity: " . $e->getMessage());
    error_log("Error code: " . $e->getCode());
    error_log("Error info: " . print_r($e->errorInfo, true));
    $error_message = "Failed to add amenity. Please try again. Error: " . $e->getMessage();
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("General Error adding amenity: " . $e->getMessage());
    $error_message = "Failed to add amenity. Please try again. Error: " . $e->getMessage();
  }
}

// Fetch all amenities
$stmt = $pdo->query("
    SELECT 
        *
    FROM amenities
    WHERE is_active = 1
    ORDER BY category ASC, sort_order ASC, amenity_name ASC
");
$amenities = $stmt->fetchAll();
?>

<script>
  // Store amenities data for JavaScript access
  const amenitiesData = <?php echo json_encode($amenities); ?>;
</script>
<?php $assets_path = '../assets'; ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $assets_path ?? './assets'; ?>/img/apple-icon.png" />
  <link rel="icon" type="image/png" href="<?php echo $assets_path ?? './assets'; ?>/img/favicon.png" />
  <title>Paradise Booking System - Amenities</title>
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
              <h6 class="mb-0 font-bold dark:text-white">Total Amenities</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($amenities); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Comfort</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $comfortAmenities = array_filter($amenities, function ($a) {
                return $a['category'] === 'comfort';
              });
              ?>
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($comfortAmenities); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Technology</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $techAmenities = array_filter($amenities, function ($a) {
                return $a['category'] === 'technology';
              });
              ?>
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($techAmenities); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Safety</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $safetyAmenities = array_filter($amenities, function ($a) {
                return $a['category'] === 'safety';
              });
              ?>
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($safetyAmenities); ?></h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Amenities Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Amenities</h6>
              <button onclick="openAddAmenityModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Add Amenity
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="amenitiesTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Amenity</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Icon Code</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Category</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($amenities)): ?>
                      <tr>
                        <td colspan="5" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No amenities found. <a href="javascript:;" class="text-blue-500 font-semibold">Add your first amenity</a>.
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($amenities as $index => $amenity): ?>
                        <tr class="amenity-row" data-amenity-id="<?php echo $amenity['amenity_id']; ?>" data-amenity-name="<?php echo htmlspecialchars($amenity['amenity_name']); ?>" data-amenity-icon="<?php echo htmlspecialchars($amenity['icon_code'] ?? ''); ?>" data-amenity-category="<?php echo htmlspecialchars($amenity['category']); ?>" data-amenity-active="<?php echo $amenity['is_active']; ?>" data-amenity-sort="<?php echo $amenity['sort_order']; ?>">
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex px-2 py-1">
                              <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
                                <i class="fas fa-star"></i>
                              </div>
                              <div class="flex flex-col justify-center">
                                <h6 class="mb-0 text-sm leading-normal dark:text-white"><?php echo htmlspecialchars($amenity['amenity_name']); ?></h6>
                              </div>
                            </div>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($amenity['icon_code'] ?? '-'); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo ucfirst($amenity['category']); ?></p>
                          </td>
                          <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Active</span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex items-center gap-2">
                              <button onclick="openViewAmenityModal(<?php echo $amenity['amenity_id']; ?>)" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
                                <i class="fas fa-info-circle mr-1.5 text-xs"></i>
                                <span>View</span>
                              </button>
                              <button onclick="openEditAmenityModal(<?php echo $amenity['amenity_id']; ?>)" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Amenity">
                                <i class="fas fa-edit mr-1.5 text-xs"></i>
                                <span>Edit</span>
                              </button>
                              <button onclick="confirmDeleteAmenity(<?php echo $amenity['amenity_id']; ?>)" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Amenity">
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

      <!-- Add Amenity Modal -->
      <div id="addAmenityModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddAmenityModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Add New Amenity</h3>
            <button onclick="closeAddAmenityModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form method="POST" action="amenities.php">
              <input type="hidden" name="action" value="add_amenity">
              <div class="space-y-4">
                <div>
                  <label for="amenity_name" class="block text-sm font-medium text-gray-700">Amenity Name</label>
                  <input type="text" name="amenity_name" id="amenity_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="icon_code" class="block text-sm font-medium text-gray-700">Icon Code</label>
                  <input type="text" name="icon_code" id="icon_code" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., fa-wifi">
                </div>
                <div>
                  <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                  <select name="category" id="category" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="comfort">Comfort</option>
                    <option value="technology">Technology</option>
                    <option value="bathroom">Bathroom</option>
                    <option value="kitchen">Kitchen</option>
                    <option value="outdoor">Outdoor</option>
                    <option value="safety">Safety</option>
                    <option value="accessibility">Accessibility</option>
                    <option value="entertainment">Entertainment</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                  <input type="number" name="sort_order" id="sort_order" value="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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
                <button type="button" onclick="closeAddAmenityModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Add Amenity
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Edit Amenity Modal -->
      <div id="editAmenityModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditAmenityModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Edit Amenity</h3>
            <button onclick="closeEditAmenityModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form method="POST" action="amenities.php">
              <input type="hidden" name="action" value="edit_amenity">
              <input type="hidden" name="amenity_id" id="edit_amenity_id">
              <div class="space-y-4">
                <div>
                  <label for="edit_amenity_name" class="block text-sm font-medium text-gray-700">Amenity Name</label>
                  <input type="text" name="amenity_name" id="edit_amenity_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_icon_code" class="block text-sm font-medium text-gray-700">Icon Code</label>
                  <input type="text" name="icon_code" id="edit_icon_code" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., fa-wifi">
                </div>
                <div>
                  <label for="edit_category" class="block text-sm font-medium text-gray-700">Category</label>
                  <select name="category" id="edit_category" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="comfort">Comfort</option>
                    <option value="technology">Technology</option>
                    <option value="bathroom">Bathroom</option>
                    <option value="kitchen">Kitchen</option>
                    <option value="outdoor">Outdoor</option>
                    <option value="safety">Safety</option>
                    <option value="accessibility">Accessibility</option>
                    <option value="entertainment">Entertainment</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label for="edit_sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                  <input type="number" name="sort_order" id="edit_sort_order" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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
                <button type="button" onclick="closeEditAmenityModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Update Amenity
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- View Amenity Modal -->
      <div id="viewAmenityModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeViewAmenityModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Amenity Details</h3>
            <button onclick="closeViewAmenityModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-500">Amenity Name</label>
                <p id="view_amenity_name" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Icon Code</label>
                <p id="view_icon_code" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Category</label>
                <p id="view_category" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Sort Order</label>
                <p id="view_sort_order" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Status</label>
                <p id="view_is_active" class="mt-1 text-sm text-gray-900"></p>
              </div>
            </div>
          </div>
          <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end flex-shrink-0" style="padding: 1.5rem; border-top: 1px solid #e5e7eb;">
            <button type="button" onclick="closeViewAmenityModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Form -->
    <form id="deleteAmenityForm" method="POST" action="amenities.php" style="display: none;">
      <input type="hidden" name="action" value="delete_amenity">
      <input type="hidden" name="amenity_id" id="delete_amenity_id">
    </form>

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
    const table = document.getElementById('amenitiesTable');
    const rows = table.getElementsByClassName('amenity-row');

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
  function openAddAmenityModal() {
    const modal = document.getElementById('addAmenityModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }

  function closeAddAmenityModal() {
    const modal = document.getElementById('addAmenityModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    // Reset form
    const form = modal.querySelector('form');
    if (form) {
      form.reset();
    }
  }

  function openEditAmenityModal(amenityId) {
    const row = document.querySelector(`tr[data-amenity-id="${amenityId}"]`);
    if (row) {
      document.getElementById('edit_amenity_id').value = amenityId;
      document.getElementById('edit_amenity_name').value = row.dataset.amenityName;
      document.getElementById('edit_icon_code').value = row.dataset.amenityIcon;
      document.getElementById('edit_category').value = row.dataset.amenityCategory;
      document.getElementById('edit_sort_order').value = row.dataset.amenitySort;
      document.getElementById('edit_is_active').checked = row.dataset.amenityActive === '1';

      const modal = document.getElementById('editAmenityModal');
      modal.classList.remove('hidden');
      modal.style.display = 'block';
      document.body.style.overflow = 'hidden';
    }
  }

  function closeEditAmenityModal() {
    const modal = document.getElementById('editAmenityModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';

    // Reset form
    const form = modal.querySelector('form');
    if (form) {
      form.reset();
    }
  }

  function openViewAmenityModal(amenityId) {
    const row = document.querySelector(`tr[data-amenity-id="${amenityId}"]`);
    if (row) {
      document.getElementById('view_amenity_name').textContent = row.dataset.amenityName;
      document.getElementById('view_icon_code').textContent = row.dataset.amenityIcon || '-';
      document.getElementById('view_category').textContent = row.dataset.amenityCategory;
      document.getElementById('view_sort_order').textContent = row.dataset.amenitySort;
      document.getElementById('view_is_active').textContent = row.dataset.amenityActive === '1' ? 'Active' : 'Inactive';

      const modal = document.getElementById('viewAmenityModal');
      modal.classList.remove('hidden');
      modal.style.display = 'block';
      document.body.style.overflow = 'hidden';
    }
  }

  function closeViewAmenityModal() {
    const modal = document.getElementById('viewAmenityModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
  }

  function confirmDeleteAmenity(amenityId) {
    const row = document.querySelector(`tr[data-amenity-id="${amenityId}"]`);
    const amenityName = row ? row.dataset.amenityName : 'this amenity';

    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to delete "${amenityName}". This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('delete_amenity_id').value = amenityId;
        document.getElementById('deleteAmenityForm').submit();
      }
    });
  }

  // Show success/error messages (wrapped in DOMContentLoaded to ensure SweetAlert is loaded)
  document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_GET['success'])): ?>
      <?php if ($_GET['success'] === 'amenity_added'): ?>
        Swal.fire({
          title: 'Success!',
          text: 'Amenity has been added successfully.',
          icon: 'success',
          confirmButtonColor: '#3085d6'
        });
      <?php elseif ($_GET['success'] === 'amenity_updated'): ?>
        Swal.fire({
          title: 'Success!',
          text: 'Amenity has been updated successfully.',
          icon: 'success',
          confirmButtonColor: '#3085d6'
        });
      <?php elseif ($_GET['success'] === 'amenity_deleted'): ?>
        Swal.fire({
          title: 'Deleted!',
          text: 'Amenity has been deleted successfully.',
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