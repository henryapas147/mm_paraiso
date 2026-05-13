<?php
if (false) {
  try {
    error_log("Starting line item deletion process...");

    $line_item_id = $_POST['line_item_id'] ?? '';
    error_log("Deleting line_item_id: " . $line_item_id);

    if (empty($line_item_id)) {
      throw new Exception("Line Item ID is required");
    }

    // Start transaction
    $pdo->beginTransaction();

    // Delete line item from database
    $stmt = $pdo->prepare("DELETE FROM invoice_line_items WHERE line_item_id = ?");
    $stmt->execute([$line_item_id]);
    error_log("Deleted line item from database");

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: invoice_line_items.php?success=line_item_deleted');
    exit;
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("Error deleting line item: " . $e->getMessage());
    $error_message = "Failed to delete line item. Please try again. Error: " . $e->getMessage();
  }
}

// Handle Edit Invoice Line Item Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_line_item') {
  try {
    error_log("Starting line item edit process...");

    $line_item_id = $_POST['line_item_id'] ?? '';
    error_log("Editing line_item_id: " . $line_item_id);

    if (empty($line_item_id)) {
      throw new Exception("Line Item ID is required");
    }

    // Start transaction
    $pdo->beginTransaction();

    // Update line item data
    $stmt = $pdo->prepare("
            UPDATE invoice_line_items SET
                invoice_id = ?,
                description = ?,
                item_type = ?,
                quantity = ?,
                unit_price = ?,
                total_price = ?,
                reference_id = ?,
                sort_order = ?
            WHERE line_item_id = ?
        ");

    $stmt->execute([
      $_POST['invoice_id'],
      $_POST['description'],
      $_POST['item_type'],
      $_POST['quantity'],
      $_POST['unit_price'],
      $_POST['total_price'],
      $_POST['reference_id'] ?? null,
      $_POST['sort_order'] ?? 0,
      $line_item_id
    ]);

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: invoice_line_items.php?success=line_item_updated');
    exit;
  } catch (PDOException $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("PDO Error editing line item: " . $e->getMessage());
    $error_message = "Failed to update line item. Please try again. Error: " . $e->getMessage();
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("General Error editing line item: " . $e->getMessage());
    $error_message = "Failed to update line item. Please try again. Error: " . $e->getMessage();
  }
}

// Handle Add Invoice Line Item Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_line_item') {
  try {
    error_log("Starting line item addition process...");

    // Generate UUID for line_item_id
    $line_item_id = sprintf(
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

    // Insert line item data
    $stmt = $pdo->prepare("
            INSERT INTO invoice_line_items (
                line_item_id, invoice_id, description, item_type, quantity, 
                unit_price, total_price, reference_id, sort_order
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

    $params = [
      $line_item_id,
      $_POST['invoice_id'],
      $_POST['description'],
      $_POST['item_type'],
      $_POST['quantity'],
      $_POST['unit_price'],
      $_POST['total_price'],
      $_POST['reference_id'] ?? null,
      $_POST['sort_order'] ?? 0
    ];

    error_log("Executing line item insert with params: " . json_encode($params));
    $stmt->execute($params);
    error_log("Line item inserted successfully");

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: invoice_line_items.php?success=line_item_added');
    exit;
  } catch (PDOException $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("PDO Error adding line item: " . $e->getMessage());
    error_log("Error code: " . $e->getCode());
    error_log("Error info: " . print_r($e->errorInfo, true));
    $error_message = "Failed to add line item. Please try again. Error: " . $e->getMessage();
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("General Error adding line item: " . $e->getMessage());
    $error_message = "Failed to add line item. Please try again. Error: " . $e->getMessage();
  }
}

?>

<!-- PAGE CONTENT -->
      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Items</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statTotal">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Room Charges</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statRooms">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Services</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statServices">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Value</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statValue">-</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Line Items Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Invoice Line Items</h6>
              <button onclick="openAddLineItemModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Add Line Item
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="lineItemsTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Description</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Invoice #</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Quantity</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Unit Price</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Total</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="lineItemsTableBody">
                    <tr><td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add Line Item Modal -->
      <div id="addLineItemModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddLineItemModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Add New Line Item</h3>
            <button onclick="closeAddLineItemModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form id="addLineItemForm">
              <div class="space-y-4">
                <div>
                  <label for="invoice_id" class="block text-sm font-medium text-gray-700">Invoice</label>
                  <select name="invoice_id" id="invoice_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Loading...</option>
                  </select>
                </div>
                <div>
                  <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                  <input type="text" name="description" id="description" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="item_type" class="block text-sm font-medium text-gray-700">Item Type</label>
                  <select name="item_type" id="item_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="room_charge">Room Charge</option>
                    <option value="extra_guest">Extra Guest</option>
                    <option value="food_order">Food Order</option>
                    <option value="service">Service</option>
                    <option value="discount">Discount</option>
                    <option value="tax">Tax</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                  <input type="number" name="quantity" id="quantity" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="1.00">
                </div>
                <div>
                  <label for="unit_price" class="block text-sm font-medium text-gray-700">Unit Price</label>
                  <input type="number" name="unit_price" id="unit_price" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="0.00">
                </div>
                <div>
                  <label for="total_price" class="block text-sm font-medium text-gray-700">Total Price</label>
                  <input type="number" name="total_price" id="total_price" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="0.00">
                </div>
                <div>
                  <label for="reference_id" class="block text-sm font-medium text-gray-700">Reference ID</label>
                  <input type="text" name="reference_id" id="reference_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                  <input type="number" name="sort_order" id="sort_order" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="0">
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                <button type="button" onclick="closeAddLineItemModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="button" onclick="submitAddLineItem()" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Add Line Item
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Edit Line Item Modal -->
      <div id="editLineItemModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditLineItemModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Edit Line Item</h3>
            <button onclick="closeEditLineItemModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form id="editLineItemForm">
              <input type="hidden" id="edit_line_item_id">
              <div class="space-y-4">
                <div>
                  <label for="edit_invoice_id" class="block text-sm font-medium text-gray-700">Invoice</label>
                  <select name="invoice_id" id="edit_invoice_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Loading...</option>
                  </select>
                </div>
                <div>
                  <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                  <input type="text" name="description" id="edit_description" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_item_type" class="block text-sm font-medium text-gray-700">Item Type</label>
                  <select name="item_type" id="edit_item_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="room_charge">Room Charge</option>
                    <option value="extra_guest">Extra Guest</option>
                    <option value="food_order">Food Order</option>
                    <option value="service">Service</option>
                    <option value="discount">Discount</option>
                    <option value="tax">Tax</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label for="edit_quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                  <input type="number" name="quantity" id="edit_quantity" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_unit_price" class="block text-sm font-medium text-gray-700">Unit Price</label>
                  <input type="number" name="unit_price" id="edit_unit_price" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_total_price" class="block text-sm font-medium text-gray-700">Total Price</label>
                  <input type="number" name="total_price" id="edit_total_price" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_reference_id" class="block text-sm font-medium text-gray-700">Reference ID</label>
                  <input type="text" name="reference_id" id="edit_reference_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                  <input type="number" name="sort_order" id="edit_sort_order" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                <button type="button" onclick="closeEditLineItemModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="button" onclick="submitEditLineItem()" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Update Line Item
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- View Line Item Modal -->
      <div id="viewLineItemModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeViewLineItemModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Line Item Details</h3>
            <button onclick="closeViewLineItemModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-500">Description</label>
                <p id="view_description" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Invoice</label>
                <p id="view_invoice" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Item Type</label>
                <p id="view_item_type" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Quantity</label>
                <p id="view_quantity" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Unit Price</label>
                <p id="view_unit_price" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Total Price</label>
                <p id="view_total_price" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Reference ID</label>
                <p id="view_reference_id" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Sort Order</label>
                <p id="view_sort_order" class="mt-1 text-sm text-gray-900"></p>
              </div>
            </div>
          </div>
        </div>
      </div>

<script>
  let lineItemsData = [];
  let invoicesDropdownData = [];

  document.addEventListener('DOMContentLoaded', function () {
    fetchLineItems();
    fetchInvoicesForDropdown();
  });

  async function fetchLineItems() {
    try {
      const res = await fetch('../api/invoice_line_items.php?action=list');
      const result = await res.json();
      if (result.success) {
        lineItemsData = result.data;
        renderTable();
        renderStats();
      } else {
        document.getElementById('lineItemsTableBody').innerHTML = `<tr><td colspan="7" class="p-4 text-center text-red-400">${result.message}</td></tr>`;
      }
    } catch (e) {
      document.getElementById('lineItemsTableBody').innerHTML = `<tr><td colspan="7" class="p-4 text-center text-red-400">Failed to load data</td></tr>`;
    }
  }

  async function fetchInvoicesForDropdown() {
    try {
      const res = await fetch('../api/invoices.php?action=list');
      const result = await res.json();
      if (result.success) {
        invoicesDropdownData = result.data;
        const opts = invoicesDropdownData.map(i => `<option value="${i.invoice_id}">${i.invoice_number}</option>`).join('');
        document.getElementById('invoice_id').innerHTML = opts || '<option value="">No invoices found</option>';
        document.getElementById('edit_invoice_id').innerHTML = opts || '<option value="">No invoices found</option>';
      }
    } catch (e) { console.error('Error fetching invoices:', e); }
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = lineItemsData.length;
    document.getElementById('statRooms').textContent = lineItemsData.filter(i => i.item_type === 'room_charge').length;
    document.getElementById('statServices').textContent = lineItemsData.filter(i => i.item_type === 'service').length;
    const total = lineItemsData.reduce((s, i) => s + parseFloat(i.line_total || i.total_price || 0), 0);
    document.getElementById('statValue').textContent = '₱' + total.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function renderTable() {
    const tbody = document.getElementById('lineItemsTableBody');
    if (!lineItemsData.length) {
      tbody.innerHTML = `<tr><td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">No line items found. <a href="javascript:;" onclick="openAddLineItemModal()" class="text-blue-500 font-semibold">Add your first line item</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = lineItemsData.map(item => {
      const typeLabel = (item.item_type || '').replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
      const unitPrice = parseFloat(item.unit_price || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 });
      const totalPrice = parseFloat(item.line_total || item.total_price || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 });
      return `
        <tr class="line-item-row">
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${item.description || '-'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${item.invoice_number || 'N/A'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${typeLabel}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${item.quantity}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">₱${unitPrice}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">₱${totalPrice}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <div class="flex items-center gap-2">
              <button onclick="openViewLineItemModal('${item.line_item_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-info-circle mr-1.5 text-xs"></i><span>View</span>
              </button>
              <button onclick="openEditLineItemModal('${item.line_item_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 hover:text-amber-600 hover:border-amber-300 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-edit mr-1.5 text-xs"></i><span>Edit</span>
              </button>
              <button onclick="confirmDeleteLineItem('${item.line_item_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 hover:text-red-700 hover:border-red-300 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-trash-alt mr-1.5 text-xs"></i><span>Delete</span>
              </button>
            </div>
          </td>
        </tr>`;
    }).join('');
  }

  function openAddLineItemModal() {
    document.getElementById('addLineItemForm').reset();
    document.getElementById('addLineItemModal').style.display = 'block';
    document.getElementById('addLineItemModal').classList.remove('hidden');
  }

  function closeAddLineItemModal() {
    document.getElementById('addLineItemModal').style.display = 'none';
    document.getElementById('addLineItemModal').classList.add('hidden');
  }

  async function submitAddLineItem() {
    const data = {
      invoice_id: document.getElementById('invoice_id').value,
      description: document.getElementById('description').value,
      item_type: document.getElementById('item_type').value,
      quantity: document.getElementById('quantity').value,
      unit_price: document.getElementById('unit_price').value,
      total_price: document.getElementById('total_price').value,
      sort_order: document.getElementById('sort_order').value,
    };
    try {
      const res = await fetch('../api/invoice_line_items.php?action=create', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeAddLineItemModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Line item added successfully', timer: 2000, showConfirmButton: false });
        fetchLineItems();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to add line item', confirmButtonColor: '#3085d6' });
    }
  }

  function openEditLineItemModal(lineItemId) {
    const item = lineItemsData.find(i => i.line_item_id === lineItemId);
    if (item) {
      document.getElementById('edit_line_item_id').value = item.line_item_id;
      document.getElementById('edit_invoice_id').value = item.invoice_id;
      document.getElementById('edit_description').value = item.description;
      document.getElementById('edit_item_type').value = item.item_type;
      document.getElementById('edit_quantity').value = item.quantity;
      document.getElementById('edit_unit_price').value = item.unit_price;
      document.getElementById('edit_total_price').value = item.line_total || item.total_price;
      document.getElementById('edit_reference_id').value = item.reference_id || '';
      document.getElementById('edit_sort_order').value = item.sort_order;
      document.getElementById('editLineItemModal').style.display = 'block';
      document.getElementById('editLineItemModal').classList.remove('hidden');
    }
  }

  function closeEditLineItemModal() {
    document.getElementById('editLineItemModal').style.display = 'none';
    document.getElementById('editLineItemModal').classList.add('hidden');
  }

  async function submitEditLineItem() {
    const data = {
      line_item_id: document.getElementById('edit_line_item_id').value,
      invoice_id: document.getElementById('edit_invoice_id').value,
      description: document.getElementById('edit_description').value,
      item_type: document.getElementById('edit_item_type').value,
      quantity: document.getElementById('edit_quantity').value,
      unit_price: document.getElementById('edit_unit_price').value,
      total_price: document.getElementById('edit_total_price').value,
      sort_order: document.getElementById('edit_sort_order').value,
    };
    try {
      const res = await fetch('../api/invoice_line_items.php?action=update', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeEditLineItemModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Line item updated successfully', timer: 2000, showConfirmButton: false });
        fetchLineItems();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to update line item', confirmButtonColor: '#3085d6' });
    }
  }

  function openViewLineItemModal(lineItemId) {
    const item = lineItemsData.find(i => i.line_item_id === lineItemId);
    if (item) {
      document.getElementById('view_description').textContent = item.description;
      document.getElementById('view_invoice').textContent = item.invoice_number || 'N/A';
      document.getElementById('view_item_type').textContent = item.item_type;
      document.getElementById('view_quantity').textContent = item.quantity;
      document.getElementById('view_unit_price').textContent = '₱' + parseFloat(item.unit_price).toFixed(2);
      document.getElementById('view_total_price').textContent = '₱' + parseFloat(item.line_total || item.total_price || 0).toFixed(2);
      document.getElementById('view_reference_id').textContent = item.reference_id || 'N/A';
      document.getElementById('view_sort_order').textContent = item.sort_order;
      document.getElementById('viewLineItemModal').style.display = 'block';
      document.getElementById('viewLineItemModal').classList.remove('hidden');
    }
  }

  function closeViewLineItemModal() {
    document.getElementById('viewLineItemModal').style.display = 'none';
    document.getElementById('viewLineItemModal').classList.add('hidden');
  }

  function confirmDeleteLineItem(lineItemId) {
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
          const res = await fetch('../api/invoice_line_items.php?action=delete', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ line_item_id: lineItemId })
          });
          const r = await res.json();
          if (r.success) {
            Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Line item deleted.', timer: 1500, showConfirmButton: false });
            fetchLineItems();
          } else {
            Swal.fire({ icon: 'error', title: 'Error!', text: r.message, confirmButtonColor: '#3085d6' });
          }
        } catch (e) {
          Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to delete line item', confirmButtonColor: '#3085d6' });
        }
      }
    });
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    document.querySelectorAll('#lineItemsTable .line-item-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }
</script>