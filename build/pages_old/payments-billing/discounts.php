<?php
require_once '../../config/db.php';

// Handle Delete Discount Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_discount') {
  try {
    error_log("Starting discount deletion process...");

    $discount_id = $_POST['discount_id'] ?? '';
    error_log("Deleting discount_id: " . $discount_id);

    if (empty($discount_id)) {
      throw new Exception("Discount ID is required");
    }

    // Start transaction
    $pdo->beginTransaction();

    // Delete discount from database
    $stmt = $pdo->prepare("DELETE FROM discounts WHERE discount_id = ?");
    $stmt->execute([$discount_id]);
    error_log("Deleted discount from database");

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: discounts.php?success=discount_deleted');
    exit;
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("Error deleting discount: " . $e->getMessage());
    $error_message = "Failed to delete discount. Please try again. Error: " . $e->getMessage();
  }
}

// Handle Edit Discount Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_discount') {
  try {
    error_log("Starting discount edit process...");

    $discount_id = $_POST['discount_id'] ?? '';
    error_log("Editing discount_id: " . $discount_id);

    if (empty($discount_id)) {
      throw new Exception("Discount ID is required");
    }

    // Start transaction
    $pdo->beginTransaction();

    // Update discount data
    $stmt = $pdo->prepare("
            UPDATE discounts SET
                property_id = ?,
                discount_code = ?,
                discount_name = ?,
                description = ?,
                discount_type = ?,
                discount_value = ?,
                min_nights = ?,
                min_booking_amount = ?,
                max_discount_amount = ?,
                applies_to = ?,
                usage_limit = ?,
                per_user_limit = ?,
                valid_from = ?,
                valid_until = ?,
                is_active = ?
            WHERE discount_id = ?
        ");

    $stmt->execute([
      $_POST['property_id'] ?? null,
      $_POST['discount_code'],
      $_POST['discount_name'],
      $_POST['description'] ?? null,
      $_POST['discount_type'],
      $_POST['discount_value'],
      $_POST['min_nights'] ?? null,
      $_POST['min_booking_amount'] ?? null,
      $_POST['max_discount_amount'] ?? null,
      implode(',', $_POST['applies_to'] ?? ['overnight', 'hourly', 'day_use']),
      $_POST['usage_limit'] ?? null,
      $_POST['per_user_limit'] ?? null,
      $_POST['valid_from'],
      $_POST['valid_until'],
      $_POST['is_active'] ?? 1,
      $discount_id
    ]);

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: discounts.php?success=discount_updated');
    exit;
  } catch (PDOException $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("PDO Error editing discount: " . $e->getMessage());
    $error_message = "Failed to update discount. Please try again. Error: " . $e->getMessage();
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("General Error editing discount: " . $e->getMessage());
    $error_message = "Failed to update discount. Please try again. Error: " . $e->getMessage();
  }
}

// Handle Add Discount Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_discount') {
  try {
    error_log("Starting discount addition process...");

    // Generate UUID for discount_id
    $discount_id = sprintf(
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

    // Start transaction
    $pdo->beginTransaction();

    // Insert discount data
    $stmt = $pdo->prepare("
            INSERT INTO discounts (
                discount_id, property_id, discount_code, discount_name, description, 
                discount_type, discount_value, min_nights, min_booking_amount, 
                max_discount_amount, applies_to, usage_limit, per_user_limit, 
                valid_from, valid_until, is_active
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

    $params = [
      $discount_id,
      $_POST['property_id'] ?? null,
      $_POST['discount_code'],
      $_POST['discount_name'],
      $_POST['description'] ?? null,
      $_POST['discount_type'],
      $_POST['discount_value'],
      $_POST['min_nights'] ?? null,
      $_POST['min_booking_amount'] ?? null,
      $_POST['max_discount_amount'] ?? null,
      implode(',', $_POST['applies_to'] ?? ['overnight', 'hourly', 'day_use']),
      $_POST['usage_limit'] ?? null,
      $_POST['per_user_limit'] ?? null,
      $_POST['valid_from'],
      $_POST['valid_until'],
      $_POST['is_active'] ?? 1
    ];

    error_log("Executing discount insert with params: " . json_encode($params));
    $stmt->execute($params);
    error_log("Discount inserted successfully");

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: discounts.php?success=discount_added');
    exit;
  } catch (PDOException $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("PDO Error adding discount: " . $e->getMessage());
    error_log("Error code: " . $e->getCode());
    error_log("Error info: " . print_r($e->errorInfo, true));
    $error_message = "Failed to add discount. Please try again. Error: " . $e->getMessage();
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("General Error adding discount: " . $e->getMessage());
    $error_message = "Failed to add discount. Please try again. Error: " . $e->getMessage();
  }
}

// Fetch all discounts
$stmt = $pdo->query("
    SELECT 
        d.*,
        p.property_name
    FROM discounts d
    LEFT JOIN properties p ON d.property_id = p.property_id
    ORDER BY d.valid_from DESC, d.discount_name ASC
");
$discounts = $stmt->fetchAll();
?>

<script>
  // Store discounts data for JavaScript access
  const discountsData = <?php echo json_encode($discounts); ?>;
</script>

<?php $assets_path = '../assets'; $page_title = 'Discounts'; ?>
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
              <h6 class="mb-0 font-bold dark:text-white">Total Discounts</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($discounts); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Active</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $activeDiscounts = array_filter($discounts, function ($d) {
                return $d['is_active'] == 1;
              });
              ?>
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($activeDiscounts); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Percentage</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $percentageDiscounts = array_filter($discounts, function ($d) {
                return $d['discount_type'] === 'percentage';
              });
              ?>
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($percentageDiscounts); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Uses</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $totalUses = array_sum(array_column($discounts, 'usage_count'));
              ?>
              <h5 class="font-bold text-3xl dark:text-white"><?php echo $totalUses; ?></h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Discounts Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Discounts</h6>
              <button onclick="openAddDiscountModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Add Discount
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="discountsTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Code</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Name</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Value</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Valid Period</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($discounts)): ?>
                      <tr>
                        <td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No discounts found. <a href="javascript:;" onclick="openAddDiscountModal()" class="text-blue-500 font-semibold">Add your first discount</a>.
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($discounts as $index => $discount): ?>
                        <tr class="discount-row" data-discount-id="<?php echo $discount['discount_id']; ?>" data-property-id="<?php echo htmlspecialchars($discount['property_id'] ?? ''); ?>" data-discount-code="<?php echo htmlspecialchars($discount['discount_code']); ?>" data-discount-name="<?php echo htmlspecialchars($discount['discount_name']); ?>" data-description="<?php echo htmlspecialchars($discount['description'] ?? ''); ?>" data-discount-type="<?php echo htmlspecialchars($discount['discount_type']); ?>" data-discount-value="<?php echo $discount['discount_value']; ?>" data-min-nights="<?php echo $discount['min_nights']; ?>" data-min-booking-amount="<?php echo $discount['min_booking_amount']; ?>" data-max-discount-amount="<?php echo $discount['max_discount_amount']; ?>" data-applies-to="<?php echo htmlspecialchars($discount['applies_to']); ?>" data-usage-limit="<?php echo $discount['usage_limit']; ?>" data-per-user-limit="<?php echo $discount['per_user_limit']; ?>" data-valid-from="<?php echo $discount['valid_from']; ?>" data-valid-until="<?php echo $discount['valid_until']; ?>" data-is-active="<?php echo $discount['is_active']; ?>">
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex px-2 py-1">
                              <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-red-500 to-orange-400">
                                <i class="fas fa-tag"></i>
                              </div>
                              <div class="flex flex-col justify-center">
                                <h6 class="mb-0 text-sm leading-normal dark:text-white"><?php echo htmlspecialchars($discount['discount_code']); ?></h6>
                              </div>
                            </div>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($discount['discount_name']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo ucfirst($discount['discount_type']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white"><?php echo $discount['discount_type'] === 'percentage' ? $discount['discount_value'] . '%' : '₱' . number_format($discount['discount_value'], 2); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo date('M d, Y', strtotime($discount['valid_from'])) . ' - ' . date('M d, Y', strtotime($discount['valid_until'])); ?></p>
                          </td>
                          <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <?php if ($discount['is_active'] == 1): ?>
                              <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Active</span>
                            <?php else: ?>
                              <span class="bg-gradient-to-tl from-slate-600 to-slate-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Inactive</span>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex items-center gap-2">
                              <button onclick="openViewDiscountModal('<?php echo $discount['discount_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
                                <i class="fas fa-info-circle mr-1.5 text-xs"></i>
                                <span>View</span>
                              </button>
                              <button onclick="openEditDiscountModal('<?php echo $discount['discount_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Discount">
                                <i class="fas fa-edit mr-1.5 text-xs"></i>
                                <span>Edit</span>
                              </button>
                              <button onclick="confirmDeleteDiscount('<?php echo $discount['discount_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Discount">
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

      <!-- Add Discount Modal -->
      <div id="addDiscountModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddDiscountModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 600px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Add New Discount</h3>
            <button onclick="closeAddDiscountModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form method="POST" action="discounts.php">
              <input type="hidden" name="action" value="add_discount">
              <div class="space-y-4">
                <div>
                  <label for="discount_code" class="block text-sm font-medium text-gray-700">Discount Code</label>
                  <input type="text" name="discount_code" id="discount_code" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., SUMMER2024">
                </div>
                <div>
                  <label for="discount_name" class="block text-sm font-medium text-gray-700">Discount Name</label>
                  <input type="text" name="discount_name" id="discount_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., Summer Sale">
                </div>
                <div>
                  <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                  <textarea name="description" id="description" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
                <div>
                  <label for="discount_type" class="block text-sm font-medium text-gray-700">Discount Type</label>
                  <select name="discount_type" id="discount_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="percentage">Percentage</option>
                    <option value="fixed_amount">Fixed Amount</option>
                    <option value="free_night">Free Night</option>
                    <option value="upgrade">Upgrade</option>
                  </select>
                </div>
                <div>
                  <label for="discount_value" class="block text-sm font-medium text-gray-700">Discount Value</label>
                  <input type="number" name="discount_value" id="discount_value" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., 10 for 10%">
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label for="min_nights" class="block text-sm font-medium text-gray-700">Min Nights</label>
                    <input type="number" name="min_nights" id="min_nights" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  </div>
                  <div>
                    <label for="min_booking_amount" class="block text-sm font-medium text-gray-700">Min Booking Amount</label>
                    <input type="number" name="min_booking_amount" id="min_booking_amount" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  </div>
                </div>
                <div>
                  <label for="max_discount_amount" class="block text-sm font-medium text-gray-700">Max Discount Amount</label>
                  <input type="number" name="max_discount_amount" id="max_discount_amount" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Applies To</label>
                  <div class="mt-2 space-y-2">
                    <label class="flex items-center">
                      <input type="checkbox" name="applies_to[]" value="overnight" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                      <span class="ml-2 text-sm text-gray-700">Overnight</span>
                    </label>
                    <label class="flex items-center">
                      <input type="checkbox" name="applies_to[]" value="hourly" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                      <span class="ml-2 text-sm text-gray-700">Hourly</span>
                    </label>
                    <label class="flex items-center">
                      <input type="checkbox" name="applies_to[]" value="day_use" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                      <span class="ml-2 text-sm text-gray-700">Day Use</span>
                    </label>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label for="usage_limit" class="block text-sm font-medium text-gray-700">Usage Limit</label>
                    <input type="number" name="usage_limit" id="usage_limit" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Empty for unlimited">
                  </div>
                  <div>
                    <label for="per_user_limit" class="block text-sm font-medium text-gray-700">Per User Limit</label>
                    <input type="number" name="per_user_limit" id="per_user_limit" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label for="valid_from" class="block text-sm font-medium text-gray-700">Valid From</label>
                    <input type="date" name="valid_from" id="valid_from" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  </div>
                  <div>
                    <label for="valid_until" class="block text-sm font-medium text-gray-700">Valid Until</label>
                    <input type="date" name="valid_until" id="valid_until" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  </div>
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
                <button type="button" onclick="closeAddDiscountModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Add Discount
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Edit Discount Modal -->
      <div id="editDiscountModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditDiscountModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 600px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Edit Discount</h3>
            <button onclick="closeEditDiscountModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form method="POST" action="discounts.php">
              <input type="hidden" name="action" value="edit_discount">
              <input type="hidden" name="discount_id" id="edit_discount_id">
              <div class="space-y-4">
                <div>
                  <label for="edit_discount_code" class="block text-sm font-medium text-gray-700">Discount Code</label>
                  <input type="text" name="discount_code" id="edit_discount_code" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_discount_name" class="block text-sm font-medium text-gray-700">Discount Name</label>
                  <input type="text" name="discount_name" id="edit_discount_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                  <textarea name="description" id="edit_description" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
                <div>
                  <label for="edit_discount_type" class="block text-sm font-medium text-gray-700">Discount Type</label>
                  <select name="discount_type" id="edit_discount_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="percentage">Percentage</option>
                    <option value="fixed_amount">Fixed Amount</option>
                    <option value="free_night">Free Night</option>
                    <option value="upgrade">Upgrade</option>
                  </select>
                </div>
                <div>
                  <label for="edit_discount_value" class="block text-sm font-medium text-gray-700">Discount Value</label>
                  <input type="number" name="discount_value" id="edit_discount_value" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label for="edit_min_nights" class="block text-sm font-medium text-gray-700">Min Nights</label>
                    <input type="number" name="min_nights" id="edit_min_nights" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  </div>
                  <div>
                    <label for="edit_min_booking_amount" class="block text-sm font-medium text-gray-700">Min Booking Amount</label>
                    <input type="number" name="min_booking_amount" id="edit_min_booking_amount" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  </div>
                </div>
                <div>
                  <label for="edit_max_discount_amount" class="block text-sm font-medium text-gray-700">Max Discount Amount</label>
                  <input type="number" name="max_discount_amount" id="edit_max_discount_amount" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Applies To</label>
                  <div class="mt-2 space-y-2">
                    <label class="flex items-center">
                      <input type="checkbox" name="applies_to[]" value="overnight" id="edit_applies_overnight" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                      <span class="ml-2 text-sm text-gray-700">Overnight</span>
                    </label>
                    <label class="flex items-center">
                      <input type="checkbox" name="applies_to[]" value="hourly" id="edit_applies_hourly" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                      <span class="ml-2 text-sm text-gray-700">Hourly</span>
                    </label>
                    <label class="flex items-center">
                      <input type="checkbox" name="applies_to[]" value="day_use" id="edit_applies_day_use" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                      <span class="ml-2 text-sm text-gray-700">Day Use</span>
                    </label>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label for="edit_usage_limit" class="block text-sm font-medium text-gray-700">Usage Limit</label>
                    <input type="number" name="usage_limit" id="edit_usage_limit" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  </div>
                  <div>
                    <label for="edit_per_user_limit" class="block text-sm font-medium text-gray-700">Per User Limit</label>
                    <input type="number" name="per_user_limit" id="edit_per_user_limit" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label for="edit_valid_from" class="block text-sm font-medium text-gray-700">Valid From</label>
                    <input type="date" name="valid_from" id="edit_valid_from" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  </div>
                  <div>
                    <label for="edit_valid_until" class="block text-sm font-medium text-gray-700">Valid Until</label>
                    <input type="date" name="valid_until" id="edit_valid_until" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  </div>
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
                <button type="button" onclick="closeEditDiscountModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Update Discount
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- View Discount Modal -->
      <div id="viewDiscountModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeViewDiscountModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 600px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Discount Details</h3>
            <button onclick="closeViewDiscountModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-500">Discount Code</label>
                <p id="view_discount_code" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Discount Name</label>
                <p id="view_discount_name" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Description</label>
                <p id="view_description" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Discount Type</label>
                <p id="view_discount_type" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Discount Value</label>
                <p id="view_discount_value" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Valid Period</label>
                <p id="view_valid_period" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Applies To</label>
                <p id="view_applies_to" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Usage Limit</label>
                <p id="view_usage_limit" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Usage Count</label>
                <p id="view_usage_count" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Status</label>
                <p id="view_is_active" class="mt-1 text-sm text-gray-900"></p>
              </div>
            </div>
          </div>
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
  // Modal Functions
  function openAddDiscountModal() {
    document.getElementById('addDiscountModal').style.display = 'block';
    document.getElementById('addDiscountModal').classList.remove('hidden');
  }

  function closeAddDiscountModal() {
    document.getElementById('addDiscountModal').style.display = 'none';
    document.getElementById('addDiscountModal').classList.add('hidden');
  }

  function openEditDiscountModal(discountId) {
    const discount = discountsData.find(d => d.discount_id === discountId);
    if (discount) {
      document.getElementById('edit_discount_id').value = discount.discount_id;
      document.getElementById('edit_discount_code').value = discount.discount_code;
      document.getElementById('edit_discount_name').value = discount.discount_name;
      document.getElementById('edit_description').value = discount.description || '';
      document.getElementById('edit_discount_type').value = discount.discount_type;
      document.getElementById('edit_discount_value').value = discount.discount_value;
      document.getElementById('edit_min_nights').value = discount.min_nights || '';
      document.getElementById('edit_min_booking_amount').value = discount.min_booking_amount || '';
      document.getElementById('edit_max_discount_amount').value = discount.max_discount_amount || '';

      // Handle applies_to checkboxes
      const appliesTo = discount.applies_to ? discount.applies_to.split(',') : [];
      document.getElementById('edit_applies_overnight').checked = appliesTo.includes('overnight');
      document.getElementById('edit_applies_hourly').checked = appliesTo.includes('hourly');
      document.getElementById('edit_applies_day_use').checked = appliesTo.includes('day_use');

      document.getElementById('edit_usage_limit').value = discount.usage_limit || '';
      document.getElementById('edit_per_user_limit').value = discount.per_user_limit || '';
      document.getElementById('edit_valid_from').value = discount.valid_from;
      document.getElementById('edit_valid_until').value = discount.valid_until;
      document.getElementById('edit_is_active').checked = discount.is_active == 1;

      document.getElementById('editDiscountModal').style.display = 'block';
      document.getElementById('editDiscountModal').classList.remove('hidden');
    }
  }

  function closeEditDiscountModal() {
    document.getElementById('editDiscountModal').style.display = 'none';
    document.getElementById('editDiscountModal').classList.add('hidden');
  }

  function openViewDiscountModal(discountId) {
    const discount = discountsData.find(d => d.discount_id === discountId);
    if (discount) {
      document.getElementById('view_discount_code').textContent = discount.discount_code;
      document.getElementById('view_discount_name').textContent = discount.discount_name;
      document.getElementById('view_description').textContent = discount.description || 'N/A';
      document.getElementById('view_discount_type').textContent = discount.discount_type;
      document.getElementById('view_discount_value').textContent = discount.discount_type === 'percentage' ? discount.discount_value + '%' : '₱' + parseFloat(discount.discount_value).toFixed(2);
      document.getElementById('view_valid_period').textContent = new Date(discount.valid_from).toLocaleDateString() + ' - ' + new Date(discount.valid_until).toLocaleDateString();
      document.getElementById('view_applies_to').textContent = discount.applies_to ? discount.applies_to.replace(/,/g, ', ') : 'N/A';
      document.getElementById('view_usage_limit').textContent = discount.usage_limit || 'Unlimited';
      document.getElementById('view_usage_count').textContent = discount.usage_count;
      document.getElementById('view_is_active').textContent = discount.is_active == 1 ? 'Active' : 'Inactive';

      document.getElementById('viewDiscountModal').style.display = 'block';
      document.getElementById('viewDiscountModal').classList.remove('hidden');
    }
  }

  function closeViewDiscountModal() {
    document.getElementById('viewDiscountModal').style.display = 'none';
    document.getElementById('viewDiscountModal').classList.add('hidden');
  }

  function confirmDeleteDiscount(discountId) {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'discounts.php';
        form.innerHTML = '<input type="hidden" name="action" value="delete_discount"><input type="hidden" name="discount_id" value="' + discountId + '">';
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toUpperCase();
    const table = document.getElementById('discountsTable');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
      const td = tr[i].getElementsByTagName('td');
      let visible = false;
      for (let j = 0; j < td.length - 1; j++) {
        if (td[j]) {
          const txtValue = td[j].textContent || td[j].innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            visible = true;
            break;
          }
        }
      }
      tr[i].style.display = visible ? '' : 'none';
    }
  }

  // Success/Error Messages
  <?php if (isset($_GET['success'])): ?>
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: '<?php echo htmlspecialchars($_GET['success']); ?>',
      timer: 2000,
      showConfirmButton: false
    });
  <?php endif; ?>

  <?php if (isset($error_message)): ?>
    Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: '<?php echo htmlspecialchars($error_message); ?>',
      confirmButtonColor: '#3085d6'
    });
  <?php endif; ?>
</script>

</html>