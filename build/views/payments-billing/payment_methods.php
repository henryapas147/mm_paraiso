<?php
if (false) {
    try {
        error_log("Starting payment method deletion process...");
        
        $method_id = $_POST['method_id'] ?? '';
        error_log("Deleting method_id: " . $method_id);

        if (empty($method_id)) {
            throw new Exception("Payment Method ID is required");
        }

        // Start transaction
        $pdo->beginTransaction();

        // Delete payment method from database
        $stmt = $pdo->prepare("DELETE FROM payment_methods WHERE method_id = ?");
        $stmt->execute([$method_id]);
        error_log("Deleted payment method from database");

        $pdo->commit();

        // Redirect to avoid form resubmission
        header('Location: payment_methods.php?success=payment_method_deleted');
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Error deleting payment method: " . $e->getMessage());
        $error_message = "Failed to delete payment method. Please try again. Error: " . $e->getMessage();
    }
}

// Handle Edit Payment Method Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_payment_method') {
    try {
        error_log("Starting payment method edit process...");
        
        $method_id = $_POST['method_id'] ?? '';
        error_log("Editing method_id: " . $method_id);

        if (empty($method_id)) {
            throw new Exception("Payment Method ID is required");
        }

        // Start transaction
        $pdo->beginTransaction();

        // Update payment method data
        $stmt = $pdo->prepare("
            UPDATE payment_methods SET
                method_code = ?,
                method_label = ?,
                method_type = ?,
                icon_url = ?,
                is_active = ?,
                sort_order = ?
            WHERE method_id = ?
        ");

        $stmt->execute([
            $_POST['method_code'],
            $_POST['method_label'],
            $_POST['method_type'],
            $_POST['icon_url'] ?? null,
            $_POST['is_active'] ?? 1,
            $_POST['sort_order'] ?? 0,
            $method_id
        ]);

        $pdo->commit();

        // Redirect to avoid form resubmission
        header('Location: payment_methods.php?success=payment_method_updated');
        exit;

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("PDO Error editing payment method: " . $e->getMessage());
        $error_message = "Failed to update payment method. Please try again. Error: " . $e->getMessage();
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("General Error editing payment method: " . $e->getMessage());
        $error_message = "Failed to update payment method. Please try again. Error: " . $e->getMessage();
    }
}

// Handle Add Payment Method Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_payment_method') {
    try {
        error_log("Starting payment method addition process...");

        // Start transaction
        $pdo->beginTransaction();

        // Insert payment method data
        $stmt = $pdo->prepare("
            INSERT INTO payment_methods (
                method_code, method_label, method_type, icon_url, is_active, sort_order
            ) VALUES (?, ?, ?, ?, ?, ?)
        ");

        $params = [
            $_POST['method_code'],
            $_POST['method_label'],
            $_POST['method_type'],
            $_POST['icon_url'] ?? null,
            $_POST['is_active'] ?? 1,
            $_POST['sort_order'] ?? 0
        ];

        error_log("Executing payment method insert with params: " . json_encode($params));
        $stmt->execute($params);
        error_log("Payment method inserted successfully");

        $pdo->commit();

        // Redirect to avoid form resubmission
        header('Location: payment_methods.php?success=payment_method_added');
        exit;

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("PDO Error adding payment method: " . $e->getMessage());
        error_log("Error code: " . $e->getCode());
        error_log("Error info: " . print_r($e->errorInfo, true));
        $error_message = "Failed to add payment method. Please try again. Error: " . $e->getMessage();
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("General Error adding payment method: " . $e->getMessage());
        $error_message = "Failed to add payment method. Please try again. Error: " . $e->getMessage();
    }
}

?>

<!-- PAGE CONTENT -->
                <!-- Stats Cards -->
        <div class="flex flex-wrap -mx-3 mb-6">
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
                <h6 class="mb-0 font-bold dark:text-white">Total Methods</h6>
              </div>
              <div class="p-4 flex-auto">
                <h5 class="font-bold text-3xl dark:text-white" id="statTotal">-</h5>
              </div>
            </div>
          </div>
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
                <h6 class="mb-0 font-bold dark:text-white">Active</h6>
              </div>
              <div class="p-4 flex-auto">
                <h5 class="font-bold text-3xl dark:text-white" id="statActive">-</h5>
              </div>
            </div>
          </div>
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
                <h6 class="mb-0 font-bold dark:text-white">Cash</h6>
              </div>
              <div class="p-4 flex-auto">
                <h5 class="font-bold text-3xl dark:text-white" id="statCash">-</h5>
              </div>
            </div>
          </div>
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
                <h6 class="mb-0 font-bold dark:text-white">Digital Wallet</h6>
              </div>
              <div class="p-4 flex-auto">
                <h5 class="font-bold text-3xl dark:text-white" id="statDigital">-</h5>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment Methods Table -->
        <div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
                <h6 class="dark:text-white">Payment Methods</h6>
                <button onclick="openAddPaymentMethodModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                  <i class="fas fa-plus mr-1"></i> Add Payment Method
                </button>
              </div>
              <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                  <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="paymentMethodsTable">
                    <thead class="align-bottom">
                      <tr>
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Method</th>
                        <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Code</th>
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                        <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                      </tr>
                    </thead>
                    <tbody id="paymentMethodsTableBody">
                      <tr><td colspan="5" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Add Payment Method Modal -->
    <div id="addPaymentMethodModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
      <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddPaymentMethodModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
        <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
          <h3 class="text-xl font-bold dark:text-white">Add New Payment Method</h3>
          <button onclick="closeAddPaymentMethodModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
            <i class="fas fa-times text-xl">×</i>
          </button>
        </div>
        <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
          <form id="addPaymentMethodForm">
              <div class="space-y-4">
                <div>
                  <label for="method_code" class="block text-sm font-medium text-gray-700">Method Code</label>
                  <input type="text" name="method_code" id="method_code" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., gcash, maya, bdo">
                </div>
                <div>
                  <label for="method_label" class="block text-sm font-medium text-gray-700">Method Label</label>
                  <input type="text" name="method_label" id="method_label" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., GCash, Maya, BDO">
                </div>
                <div>
                  <label for="method_type" class="block text-sm font-medium text-gray-700">Method Type</label>
                  <select name="method_type" id="method_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="cash">Cash</option>
                    <option value="digital_wallet">Digital Wallet</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="card">Card</option>
                    <option value="check">Check</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label for="icon_url" class="block text-sm font-medium text-gray-700">Icon URL</label>
                  <input type="text" name="icon_url" id="icon_url" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="https://...">
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
              <button type="button" onclick="closeAddPaymentMethodModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                Cancel
              </button>
              <button type="button" onclick="submitAddPaymentMethod()" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                Add Payment Method
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Payment Method Modal -->
    <div id="editPaymentMethodModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
      <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditPaymentMethodModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
        <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
          <h3 class="text-xl font-bold dark:text-white">Edit Payment Method</h3>
          <button onclick="closeEditPaymentMethodModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
            <i class="fas fa-times text-xl">×</i>
          </button>
        </div>
        <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
          <form id="editPaymentMethodForm">
            <input type="hidden" id="edit_method_id">
              <div class="space-y-4">
                <div>
                  <label for="edit_method_code" class="block text-sm font-medium text-gray-700">Method Code</label>
                  <input type="text" name="method_code" id="edit_method_code" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_method_label" class="block text-sm font-medium text-gray-700">Method Label</label>
                  <input type="text" name="method_label" id="edit_method_label" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_method_type" class="block text-sm font-medium text-gray-700">Method Type</label>
                  <select name="method_type" id="edit_method_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="cash">Cash</option>
                    <option value="digital_wallet">Digital Wallet</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="card">Card</option>
                    <option value="check">Check</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label for="edit_icon_url" class="block text-sm font-medium text-gray-700">Icon URL</label>
                  <input type="text" name="icon_url" id="edit_icon_url" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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
              <button type="button" onclick="closeEditPaymentMethodModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                Cancel
              </button>
              <button type="button" onclick="submitEditPaymentMethod()" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                Update Payment Method
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- View Payment Method Modal -->
    <div id="viewPaymentMethodModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
      <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeViewPaymentMethodModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
        <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
          <h3 class="text-xl font-bold dark:text-white">Payment Method Details</h3>
          <button onclick="closeViewPaymentMethodModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
            <i class="fas fa-times text-xl">×</i>
          </button>
        </div>
        <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-500">Method Label</label>
                <p id="view_method_label" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Method Code</label>
                <p id="view_method_code" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Method Type</label>
                <p id="view_method_type" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Icon URL</label>
                <p id="view_icon_url" class="mt-1 text-sm text-gray-900"></p>
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
      </div>
    </div>

<script>
  let paymentMethodsData = [];

  document.addEventListener('DOMContentLoaded', function () {
    fetchPaymentMethods();
  });

  async function fetchPaymentMethods() {
    try {
      const res = await fetch('../api/payment_methods.php?action=list');
      const result = await res.json();
      if (result.success) {
        paymentMethodsData = result.data;
        renderTable();
        renderStats();
      } else {
        document.getElementById('paymentMethodsTableBody').innerHTML = `<tr><td colspan="5" class="p-4 text-center text-red-400">${result.message}</td></tr>`;
      }
    } catch (e) {
      console.error('Error fetching payment methods:', e);
      document.getElementById('paymentMethodsTableBody').innerHTML = `<tr><td colspan="5" class="p-4 text-center text-red-400">Failed to load data</td></tr>`;
    }
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = paymentMethodsData.length;
    document.getElementById('statActive').textContent = paymentMethodsData.filter(m => m.is_active == 1).length;
    document.getElementById('statCash').textContent = paymentMethodsData.filter(m => m.method_type === 'cash').length;
    document.getElementById('statDigital').textContent = paymentMethodsData.filter(m => m.method_type === 'digital_wallet').length;
  }

  function renderTable() {
    const tbody = document.getElementById('paymentMethodsTableBody');
    if (!paymentMethodsData.length) {
      tbody.innerHTML = `<tr><td colspan="5" class="p-4 text-center text-slate-400 dark:text-white/60">No payment methods found. <a href="javascript:;" onclick="openAddPaymentMethodModal()" class="text-blue-500 font-semibold">Add your first payment method</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = paymentMethodsData.map(m => {
      const typeLabel = (m.method_type || '').replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
      const statusBadge = m.is_active == 1
        ? `<span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Active</span>`
        : `<span class="bg-gradient-to-tl from-slate-600 to-slate-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Inactive</span>`;
      return `
        <tr class="payment-method-row">
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <div class="flex px-2 py-1">
              <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
                <i class="fas fa-credit-card"></i>
              </div>
              <div class="flex flex-col justify-center">
                <h6 class="mb-0 text-sm leading-normal dark:text-white">${m.method_label}</h6>
              </div>
            </div>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${m.method_code}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${typeLabel}</p>
          </td>
          <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">${statusBadge}</td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <div class="flex items-center gap-2">
              <button onclick="openViewPaymentMethodModal('${m.method_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-info-circle mr-1.5 text-xs"></i><span>View</span>
              </button>
              <button onclick="openEditPaymentMethodModal('${m.method_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 hover:text-amber-600 hover:border-amber-300 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-edit mr-1.5 text-xs"></i><span>Edit</span>
              </button>
              <button onclick="confirmDeletePaymentMethod('${m.method_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 hover:text-red-700 hover:border-red-300 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-trash-alt mr-1.5 text-xs"></i><span>Delete</span>
              </button>
            </div>
          </td>
        </tr>`;
    }).join('');
  }

  function openAddPaymentMethodModal() {
    document.getElementById('addPaymentMethodForm').reset();
    document.getElementById('addPaymentMethodModal').style.display = 'block';
    document.getElementById('addPaymentMethodModal').classList.remove('hidden');
  }

  function closeAddPaymentMethodModal() {
    document.getElementById('addPaymentMethodModal').style.display = 'none';
    document.getElementById('addPaymentMethodModal').classList.add('hidden');
  }

  async function submitAddPaymentMethod() {
    const data = {
      method_code: document.getElementById('method_code').value,
      method_label: document.getElementById('method_label').value,
      method_type: document.getElementById('method_type').value,
      description: document.getElementById('icon_url').value,
      sort_order: document.getElementById('sort_order').value,
      is_active: document.querySelector('[name="is_active"]').checked ? 1 : 0,
    };
    try {
      const res = await fetch('../api/payment_methods.php?action=create', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeAddPaymentMethodModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Payment method added successfully', timer: 2000, showConfirmButton: false });
        fetchPaymentMethods();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to add payment method', confirmButtonColor: '#3085d6' });
    }
  }

  function openEditPaymentMethodModal(methodId) {
    const method = paymentMethodsData.find(m => m.method_id === methodId);
    if (method) {
      document.getElementById('edit_method_id').value = method.method_id;
      document.getElementById('edit_method_code').value = method.method_code;
      document.getElementById('edit_method_label').value = method.method_label;
      document.getElementById('edit_method_type').value = method.method_type;
      document.getElementById('edit_icon_url').value = method.icon_url || '';
      document.getElementById('edit_sort_order').value = method.sort_order;
      document.getElementById('edit_is_active').checked = method.is_active == 1;
      document.getElementById('editPaymentMethodModal').style.display = 'block';
      document.getElementById('editPaymentMethodModal').classList.remove('hidden');
    }
  }

  function closeEditPaymentMethodModal() {
    document.getElementById('editPaymentMethodModal').style.display = 'none';
    document.getElementById('editPaymentMethodModal').classList.add('hidden');
  }

  async function submitEditPaymentMethod() {
    const data = {
      method_id: document.getElementById('edit_method_id').value,
      method_code: document.getElementById('edit_method_code').value,
      method_label: document.getElementById('edit_method_label').value,
      method_type: document.getElementById('edit_method_type').value,
      description: document.getElementById('edit_icon_url').value,
      sort_order: document.getElementById('edit_sort_order').value,
      is_active: document.getElementById('edit_is_active').checked ? 1 : 0,
    };
    try {
      const res = await fetch('../api/payment_methods.php?action=update', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeEditPaymentMethodModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Payment method updated successfully', timer: 2000, showConfirmButton: false });
        fetchPaymentMethods();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to update payment method', confirmButtonColor: '#3085d6' });
    }
  }

  function openViewPaymentMethodModal(methodId) {
    const method = paymentMethodsData.find(m => m.method_id === methodId);
    if (method) {
      document.getElementById('view_method_label').textContent = method.method_label;
      document.getElementById('view_method_code').textContent = method.method_code;
      document.getElementById('view_method_type').textContent = method.method_type;
      document.getElementById('view_icon_url').textContent = method.icon_url || 'N/A';
      document.getElementById('view_sort_order').textContent = method.sort_order;
      document.getElementById('view_is_active').textContent = method.is_active == 1 ? 'Active' : 'Inactive';
      document.getElementById('viewPaymentMethodModal').style.display = 'block';
      document.getElementById('viewPaymentMethodModal').classList.remove('hidden');
    }
  }

  function closeViewPaymentMethodModal() {
    document.getElementById('viewPaymentMethodModal').style.display = 'none';
    document.getElementById('viewPaymentMethodModal').classList.add('hidden');
  }

  function confirmDeletePaymentMethod(methodId) {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          const res = await fetch('../api/payment_methods.php?action=delete', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ method_id: methodId })
          });
          const r = await res.json();
          if (r.success) {
            Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Payment method deleted.', timer: 1500, showConfirmButton: false });
            fetchPaymentMethods();
          } else {
            Swal.fire({ icon: 'error', title: 'Error!', text: r.message, confirmButtonColor: '#3085d6' });
          }
        } catch (e) {
          Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to delete payment method', confirmButtonColor: '#3085d6' });
        }
      }
    });
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    document.querySelectorAll('#paymentMethodsTable .payment-method-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }
    </script>