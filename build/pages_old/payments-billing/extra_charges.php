<?php
require_once '../../config/db.php';

// Handle Delete Extra Charge Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_extra_charge') {
    try {
        error_log("Starting extra charge deletion process...");
        
        $charge_id = $_POST['charge_id'] ?? '';
        error_log("Deleting charge_id: " . $charge_id);

        if (empty($charge_id)) {
            throw new Exception("Extra Charge ID is required");
        }

        // Start transaction
        $pdo->beginTransaction();

        // Delete extra charge from database
        $stmt = $pdo->prepare("DELETE FROM extra_charges WHERE charge_id = ?");
        $stmt->execute([$charge_id]);
        error_log("Deleted extra charge from database");

        $pdo->commit();

        // Redirect to avoid form resubmission
        header('Location: extra_charges.php?success=extra_charge_deleted');
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Error deleting extra charge: " . $e->getMessage());
        $error_message = "Failed to delete extra charge. Please try again. Error: " . $e->getMessage();
    }
}

// Handle Edit Extra Charge Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_extra_charge') {
    try {
        error_log("Starting extra charge edit process...");
        
        $charge_id = $_POST['charge_id'] ?? '';
        error_log("Editing charge_id: " . $charge_id);

        if (empty($charge_id)) {
            throw new Exception("Extra Charge ID is required");
        }

        // Start transaction
        $pdo->beginTransaction();

        // Update extra charge data
        $stmt = $pdo->prepare("
            UPDATE extra_charges SET
                reservation_id = ?,
                charge_type = ?,
                description = ?,
                amount = ?,
                notes = ?
            WHERE charge_id = ?
        ");

        $stmt->execute([
            $_POST['reservation_id'],
            $_POST['charge_type'],
            $_POST['description'],
            $_POST['amount'],
            $_POST['notes'] ?? null,
            $charge_id
        ]);

        $pdo->commit();

        // Redirect to avoid form resubmission
        header('Location: extra_charges.php?success=extra_charge_updated');
        exit;

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("PDO Error editing extra charge: " . $e->getMessage());
        $error_message = "Failed to update extra charge. Please try again. Error: " . $e->getMessage();
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("General Error editing extra charge: " . $e->getMessage());
        $error_message = "Failed to update extra charge. Please try again. Error: " . $e->getMessage();
    }
}

// Handle Add Extra Charge Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_extra_charge') {
    try {
        error_log("Starting extra charge addition process...");

        // Generate UUID for charge_id
        $charge_id = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        // Start transaction
        $pdo->beginTransaction();

        // Insert extra charge data
        $stmt = $pdo->prepare("
            INSERT INTO extra_charges (
                charge_id, reservation_id, charge_type, description, amount, notes
            ) VALUES (?, ?, ?, ?, ?, ?)
        ");

        $params = [
            $charge_id,
            $_POST['reservation_id'],
            $_POST['charge_type'],
            $_POST['description'],
            $_POST['amount'],
            $_POST['notes'] ?? null
        ];

        error_log("Executing extra charge insert with params: " . json_encode($params));
        $stmt->execute($params);
        error_log("Extra charge inserted successfully");

        $pdo->commit();

        // Redirect to avoid form resubmission
        header('Location: extra_charges.php?success=extra_charge_added');
        exit;

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("PDO Error adding extra charge: " . $e->getMessage());
        error_log("Error code: " . $e->getCode());
        error_log("Error info: " . print_r($e->errorInfo, true));
        $error_message = "Failed to add extra charge. Please try again. Error: " . $e->getMessage();
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("General Error adding extra charge: " . $e->getMessage());
        $error_message = "Failed to add extra charge. Please try again. Error: " . $e->getMessage();
    }
}

// Fetch all extra charges
$stmt = $pdo->query("
    SELECT 
        ec.*
    FROM extra_charges ec
    ORDER BY ec.charged_at DESC
");
$extra_charges = $stmt->fetchAll();
?>

<script>
// Store extra charges data for JavaScript access
const extraChargesData = <?php echo json_encode($extra_charges); ?>;
</script>

<?php $assets_path = '../assets'; $page_title = 'Extra Charges'; ?>
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
                <h6 class="mb-0 font-bold dark:text-white">Total Charges</h6>
              </div>
              <div class="p-4 flex-auto">
                <h5 class="font-bold text-3xl dark:text-white"><?php echo count($extra_charges); ?></h5>
              </div>
            </div>
          </div>
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
                <h6 class="mb-0 font-bold dark:text-white">Total Amount</h6>
              </div>
              <div class="p-4 flex-auto">
                <?php 
                $totalAmount = array_sum(array_column($extra_charges, 'amount'));
                ?>
                <h5 class="font-bold text-3xl dark:text-white">₱<?php echo number_format($totalAmount, 2); ?></h5>
              </div>
            </div>
          </div>
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
                <h6 class="mb-0 font-bold dark:text-white">Damage Charges</h6>
              </div>
              <div class="p-4 flex-auto">
                <?php 
                $damageCharges = array_filter($extra_charges, function($c) { return $c['charge_type'] === 'damage'; });
                ?>
                <h5 class="font-bold text-3xl dark:text-white"><?php echo count($damageCharges); ?></h5>
              </div>
            </div>
          </div>
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
                <h6 class="mb-0 font-bold dark:text-white">Late Checkout</h6>
              </div>
              <div class="p-4 flex-auto">
                <?php 
                $lateCheckoutCharges = array_filter($extra_charges, function($c) { return $c['charge_type'] === 'late_checkout'; });
                ?>
                <h5 class="font-bold text-3xl dark:text-white"><?php echo count($lateCheckoutCharges); ?></h5>
              </div>
            </div>
          </div>
        </div>

        <!-- Extra Charges Table -->
        <div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
                <h6 class="dark:text-white">Extra Charges</h6>
                <button onclick="openAddExtraChargeModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                  <i class="fas fa-plus mr-1"></i> Add Extra Charge
                </button>
              </div>
              <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                  <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="extraChargesTable">
                    <thead class="align-bottom">
                      <tr>
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reservation</th>
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Description</th>
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Amount</th>
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Charged At</th>
                        <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($extra_charges)): ?>
                      <tr>
                        <td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No extra charges found. <a href="javascript:;" onclick="openAddExtraChargeModal()" class="text-blue-500 font-semibold">Add your first extra charge</a>.
                        </td>
                      </tr>
                      <?php else: ?>
                      <?php foreach ($extra_charges as $index => $charge): ?>
                      <tr class="extra-charge-row" data-charge-id="<?php echo $charge['charge_id']; ?>" data-reservation-id="<?php echo $charge['reservation_id']; ?>" data-charge-type="<?php echo htmlspecialchars($charge['charge_type']); ?>" data-description="<?php echo htmlspecialchars($charge['description']); ?>" data-amount="<?php echo $charge['amount']; ?>" data-charged-at="<?php echo $charge['charged_at']; ?>" data-notes="<?php echo htmlspecialchars($charge['notes'] ?? ''); ?>">
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo substr($charge['reservation_id'], 0, 8) . '...'; ?></p>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo ucfirst(str_replace('_', ' ', $charge['charge_type'])); ?></p>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white"><?php echo htmlspecialchars($charge['description']); ?></p>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">₱<?php echo number_format($charge['amount'], 2); ?></p>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo date('M d, Y H:i', strtotime($charge['charged_at'])); ?></p>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="flex items-center gap-2">
                            <button onclick="openViewExtraChargeModal('<?php echo $charge['charge_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
                              <i class="fas fa-info-circle mr-1.5 text-xs"></i>
                              <span>View</span>
                            </button>
                            <button onclick="openEditExtraChargeModal('<?php echo $charge['charge_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Extra Charge">
                              <i class="fas fa-edit mr-1.5 text-xs"></i>
                              <span>Edit</span>
                            </button>
                            <button onclick="confirmDeleteExtraCharge('<?php echo $charge['charge_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Extra Charge">
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

        <!-- Add Extra Charge Modal -->
    <div id="addExtraChargeModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
      <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddExtraChargeModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
        <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
          <h3 class="text-xl font-bold dark:text-white">Add New Extra Charge</h3>
          <button onclick="closeAddExtraChargeModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
            <i class="fas fa-times text-xl">×</i>
          </button>
        </div>
        <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
          <form method="POST" action="extra_charges.php">
            <input type="hidden" name="action" value="add_extra_charge">
              <div class="space-y-4">
                <div>
                  <label for="reservation_id" class="block text-sm font-medium text-gray-700">Reservation ID</label>
                  <input type="text" name="reservation_id" id="reservation_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Enter reservation UUID">
                </div>
                <div>
                  <label for="charge_type" class="block text-sm font-medium text-gray-700">Charge Type</label>
                  <select name="charge_type" id="charge_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="damage">Damage</option>
                    <option value="late_checkout">Late Checkout</option>
                    <option value="extra_amenity">Extra Amenity</option>
                    <option value="cleaning">Cleaning</option>
                    <option value="extension">Extension</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                  <input type="text" name="description" id="description" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., Broken window glass">
                </div>
                <div>
                  <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                  <input type="number" name="amount" id="amount" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., 500.00">
                </div>
                <div>
                  <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                  <textarea name="notes" id="notes" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
              </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
              <button type="button" onclick="closeAddExtraChargeModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                Add Extra Charge
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Extra Charge Modal -->
    <div id="editExtraChargeModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
      <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditExtraChargeModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
        <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
          <h3 class="text-xl font-bold dark:text-white">Edit Extra Charge</h3>
          <button onclick="closeEditExtraChargeModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
            <i class="fas fa-times text-xl">×</i>
          </button>
        </div>
        <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
          <form method="POST" action="extra_charges.php">
            <input type="hidden" name="action" value="edit_extra_charge">
            <input type="hidden" name="charge_id" id="edit_charge_id">
              <div class="space-y-4">
                <div>
                  <label for="edit_reservation_id" class="block text-sm font-medium text-gray-700">Reservation ID</label>
                  <input type="text" name="reservation_id" id="edit_reservation_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_charge_type" class="block text-sm font-medium text-gray-700">Charge Type</label>
                  <select name="charge_type" id="edit_charge_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="damage">Damage</option>
                    <option value="late_checkout">Late Checkout</option>
                    <option value="extra_amenity">Extra Amenity</option>
                    <option value="cleaning">Cleaning</option>
                    <option value="extension">Extension</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                  <input type="text" name="description" id="edit_description" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_amount" class="block text-sm font-medium text-gray-700">Amount</label>
                  <input type="number" name="amount" id="edit_amount" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_notes" class="block text-sm font-medium text-gray-700">Notes</label>
                  <textarea name="notes" id="edit_notes" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
              </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
              <button type="button" onclick="closeEditExtraChargeModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                Update Extra Charge
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- View Extra Charge Modal -->
    <div id="viewExtraChargeModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
      <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeViewExtraChargeModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
        <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
          <h3 class="text-xl font-bold dark:text-white">Extra Charge Details</h3>
          <button onclick="closeViewExtraChargeModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
            <i class="fas fa-times text-xl">×</i>
          </button>
        </div>
        <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-500">Reservation</label>
                <p id="view_reservation_id" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Charge Type</label>
                <p id="view_charge_type" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Description</label>
                <p id="view_description" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Amount</label>
                <p id="view_amount" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Charged At</label>
                <p id="view_charged_at" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Notes</label>
                <p id="view_notes" class="mt-1 text-sm text-gray-900"></p>
              </div>
            </div>
        </div>
      </div>
    </div>
    <script>
    // Modal Functions
    function openAddExtraChargeModal() {
      document.getElementById('addExtraChargeModal').style.display = 'block';
      document.getElementById('addExtraChargeModal').classList.remove('hidden');
    }

    function closeAddExtraChargeModal() {
      document.getElementById('addExtraChargeModal').style.display = 'none';
      document.getElementById('addExtraChargeModal').classList.add('hidden');
    }

    function openEditExtraChargeModal(chargeId) {
      const charge = extraChargesData.find(c => c.charge_id === chargeId);
      if (charge) {
        document.getElementById('edit_charge_id').value = charge.charge_id;
        document.getElementById('edit_reservation_id').value = charge.reservation_id;
        document.getElementById('edit_charge_type').value = charge.charge_type;
        document.getElementById('edit_description').value = charge.description;
        document.getElementById('edit_amount').value = charge.amount;
        document.getElementById('edit_notes').value = charge.notes || '';

        document.getElementById('editExtraChargeModal').style.display = 'block';
        document.getElementById('editExtraChargeModal').classList.remove('hidden');
      }
    }

    function closeEditExtraChargeModal() {
      document.getElementById('editExtraChargeModal').style.display = 'none';
      document.getElementById('editExtraChargeModal').classList.add('hidden');
    }

    function openViewExtraChargeModal(chargeId) {
      const charge = extraChargesData.find(c => c.charge_id === chargeId);
      if (charge) {
        document.getElementById('view_reservation_id').textContent = charge.reservation_id;
        document.getElementById('view_charge_type').textContent = charge.charge_type;
        document.getElementById('view_description').textContent = charge.description;
        document.getElementById('view_amount').textContent = '₱' + parseFloat(charge.amount).toFixed(2);
        document.getElementById('view_charged_at').textContent = new Date(charge.charged_at).toLocaleString();
        document.getElementById('view_notes').textContent = charge.notes || 'N/A';

        document.getElementById('viewExtraChargeModal').style.display = 'block';
        document.getElementById('viewExtraChargeModal').classList.remove('hidden');
      }
    }

    function closeViewExtraChargeModal() {
      document.getElementById('viewExtraChargeModal').style.display = 'none';
      document.getElementById('viewExtraChargeModal').classList.add('hidden');
    }

    function confirmDeleteExtraCharge(chargeId) {
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
          form.action = 'extra_charges.php';
          form.innerHTML = '<input type="hidden" name="action" value="delete_extra_charge"><input type="hidden" name="charge_id" value="' + chargeId + '">';
          document.body.appendChild(form);
          form.submit();
        }
      });
    }

    function filterTable() {
      const input = document.getElementById('searchInput');
      const filter = input.value.toUpperCase();
      const table = document.getElementById('extraChargesTable');
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

            <?php include('../../structure/footer.php'); ?>

        </div>
        <!-- end cards -->
    </main>

    <?php include('../../structure/customizer.php'); ?>
</body>

<?php include('../../structure/script.php'); ?>


</html>