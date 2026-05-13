<!-- PAGE CONTENT -->
      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Payments</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statTotal">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Confirmed</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statConfirmed">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Pending</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statPending">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Amount</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statAmount">-</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Payments Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Payments</h6>
              <button onclick="openAddPaymentModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Add Payment
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="paymentsTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Payment ID</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reservation</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Method</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Amount</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Date</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="paymentsTableBody">
                    <tr><td colspan="8" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add Payment Modal -->
      <div id="addPaymentModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddPaymentModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 600px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Add New Payment</h3>
            <button onclick="closeAddPaymentModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form id="addPaymentForm">
              <div class="space-y-4">
                <div>
                  <label for="reservation_id" class="block text-sm font-medium text-gray-700">Reservation ID</label>
                  <input type="text" name="reservation_id" id="reservation_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="UUID">
                </div>
                <div>
                  <label for="method_id" class="block text-sm font-medium text-gray-700">Payment Method</label>
                  <select name="method_id" id="method_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Loading...</option>
                  </select>
                </div>
                <div>
                  <label for="transaction_type" class="block text-sm font-medium text-gray-700">Transaction Type</label>
                  <select name="transaction_type" id="transaction_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="payment">Payment</option>
                    <option value="partial_payment">Partial Payment</option>
                    <option value="refund">Refund</option>
                    <option value="adjustment">Adjustment</option>
                    <option value="void">Void</option>
                  </select>
                </div>
                <div>
                  <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                  <input type="number" name="amount" id="amount" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="0.00">
                </div>
                <div>
                  <label for="reference_number" class="block text-sm font-medium text-gray-700">Reference Number</label>
                  <input type="text" name="reference_number" id="reference_number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="GCash ref #, bank ref #, etc.">
                </div>
                <div>
                  <label for="proof_image_url" class="block text-sm font-medium text-gray-700">Proof Image URL</label>
                  <input type="text" name="proof_image_url" id="proof_image_url" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="https://...">
                </div>
                <div>
                  <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                  <select name="payment_status" id="payment_status" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="rejected">Rejected</option>
                    <option value="refunded">Refunded</option>
                    <option value="voided">Voided</option>
                  </select>
                </div>
                <div>
                  <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date</label>
                  <input type="date" name="payment_date" id="payment_date" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="payment_time" class="block text-sm font-medium text-gray-700">Payment Time</label>
                  <input type="time" name="payment_time" id="payment_time" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                  <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                <button type="button" onclick="closeAddPaymentModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="button" onclick="submitAddPayment()" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Add Payment
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Edit Payment Modal -->
      <div id="editPaymentModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditPaymentModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 600px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Edit Payment</h3>
            <button onclick="closeEditPaymentModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form id="editPaymentForm">
              <input type="hidden" id="edit_payment_id">
              <div class="space-y-4">
                <div>
                  <label for="edit_reservation_id" class="block text-sm font-medium text-gray-700">Reservation ID</label>
                  <input type="text" name="reservation_id" id="edit_reservation_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_method_id" class="block text-sm font-medium text-gray-700">Payment Method</label>
                  <select name="method_id" id="edit_method_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Loading...</option>
                  </select>
                </div>
                <div>
                  <label for="edit_transaction_type" class="block text-sm font-medium text-gray-700">Transaction Type</label>
                  <select name="transaction_type" id="edit_transaction_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="payment">Payment</option>
                    <option value="partial_payment">Partial Payment</option>
                    <option value="refund">Refund</option>
                    <option value="adjustment">Adjustment</option>
                    <option value="void">Void</option>
                  </select>
                </div>
                <div>
                  <label for="edit_amount" class="block text-sm font-medium text-gray-700">Amount</label>
                  <input type="number" name="amount" id="edit_amount" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_reference_number" class="block text-sm font-medium text-gray-700">Reference Number</label>
                  <input type="text" name="reference_number" id="edit_reference_number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_proof_image_url" class="block text-sm font-medium text-gray-700">Proof Image URL</label>
                  <input type="text" name="proof_image_url" id="edit_proof_image_url" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                  <select name="payment_status" id="edit_payment_status" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="rejected">Rejected</option>
                    <option value="refunded">Refunded</option>
                    <option value="voided">Voided</option>
                  </select>
                </div>
                <div>
                  <label for="edit_payment_date" class="block text-sm font-medium text-gray-700">Payment Date</label>
                  <input type="date" name="payment_date" id="edit_payment_date" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_payment_time" class="block text-sm font-medium text-gray-700">Payment Time</label>
                  <input type="time" name="payment_time" id="edit_payment_time" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_notes" class="block text-sm font-medium text-gray-700">Notes</label>
                  <textarea name="notes" id="edit_notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                <button type="button" onclick="closeEditPaymentModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="button" onclick="submitEditPayment()" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Update Payment
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- View Payment Modal -->
      <div id="viewPaymentModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeViewPaymentModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 600px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Payment Details</h3>
            <button onclick="closeViewPaymentModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-500">Payment ID</label>
                <p id="view_payment_id" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Reservation</label>
                <p id="view_reservation_id" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Payment Method</label>
                <p id="view_method" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Transaction Type</label>
                <p id="view_transaction_type" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Amount</label>
                <p id="view_amount" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Reference Number</label>
                <p id="view_reference_number" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Payment Date</label>
                <p id="view_payment_date" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Payment Time</label>
                <p id="view_payment_time" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Status</label>
                <p id="view_payment_status" class="mt-1 text-sm text-gray-900"></p>
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
  let paymentsData = [];
  let paymentMethodsData = [];

  const STATUS_GRADIENT = {
    'confirmed': 'from-emerald-500 to-teal-400',
    'pending':   'from-amber-500 to-orange-400',
    'rejected':  'from-red-500 to-rose-400',
    'refunded':  'from-slate-500 to-slate-400',
    'voided':    'from-gray-600 to-gray-400',
  };

  document.addEventListener('DOMContentLoaded', function () {
    fetchPaymentsData();
    fetchPaymentMethods();
  });

  async function fetchPaymentsData() {
    try {
      const res = await fetch('../api/payments.php?action=list');
      const result = await res.json();
      if (result.success) {
        paymentsData = result.data;
        renderPaymentsTable();
        renderStats();
      } else {
        document.getElementById('paymentsTableBody').innerHTML = `<tr><td colspan="8" class="p-4 text-center text-red-400">${result.message}</td></tr>`;
      }
    } catch (e) {
      console.error('Error fetching payments:', e);
      document.getElementById('paymentsTableBody').innerHTML = `<tr><td colspan="8" class="p-4 text-center text-red-400">Failed to load data</td></tr>`;
    }
  }

  async function fetchPaymentMethods() {
    try {
      const res = await fetch('../api/payment_methods.php?action=active');
      const result = await res.json();
      if (result.success) {
        paymentMethodsData = result.data;
        populateMethodDropdowns();
      }
    } catch (e) {
      console.error('Error fetching payment methods:', e);
    }
  }

  function populateMethodDropdowns() {
    const opts = paymentMethodsData.map(m => `<option value="${m.method_id}">${m.method_label}</option>`).join('');
    document.getElementById('method_id').innerHTML = opts;
    document.getElementById('edit_method_id').innerHTML = opts;
  }

  function renderStats() {
    const confirmed = paymentsData.filter(p => p.payment_status === 'confirmed').length;
    const pending = paymentsData.filter(p => p.payment_status === 'pending').length;
    const total = paymentsData.reduce((s, p) => s + parseFloat(p.amount || 0), 0);
    document.getElementById('statTotal').textContent = paymentsData.length;
    document.getElementById('statConfirmed').textContent = confirmed;
    document.getElementById('statPending').textContent = pending;
    document.getElementById('statAmount').textContent = '₱' + total.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function renderPaymentsTable() {
    const tbody = document.getElementById('paymentsTableBody');
    if (!tbody) return;
    if (paymentsData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="8" class="p-4 text-center text-slate-400 dark:text-white/60">No payments found. <a href="javascript:;" onclick="openAddPaymentModal()" class="text-blue-500 font-semibold">Add your first payment</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = paymentsData.map(p => {
      const gradClass = STATUS_GRADIENT[p.payment_status] || 'from-blue-500 to-indigo-400';
      const dateStr = p.payment_date ? new Date(p.payment_date).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) : '-';
      const txType = (p.transaction_type || '').replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
      return `
        <tr class="payment-row">
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">${p.payment_id.substring(0, 8)}...</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${p.reservation_id ? p.reservation_id.substring(0, 8) + '...' : '-'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${p.method_label || 'N/A'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${txType}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">₱${parseFloat(p.amount).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${dateStr}</p>
          </td>
          <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <span class="bg-gradient-to-tl ${gradClass} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${p.payment_status}</span>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
            <div class="flex items-center gap-2">
              <button onclick="openViewPaymentModal('${p.payment_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-info-circle mr-1.5 text-xs"></i><span>View</span>
              </button>
              <button onclick="openEditPaymentModal('${p.payment_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 hover:text-amber-600 hover:border-amber-300 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-edit mr-1.5 text-xs"></i><span>Edit</span>
              </button>
              <button onclick="confirmDeletePayment('${p.payment_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 hover:text-red-700 hover:border-red-300 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-trash-alt mr-1.5 text-xs"></i><span>Delete</span>
              </button>
            </div>
          </td>
        </tr>`;
    }).join('');
  }

  function openAddPaymentModal() {
    document.getElementById('addPaymentForm').reset();
    document.getElementById('addPaymentModal').style.display = 'block';
    document.getElementById('addPaymentModal').classList.remove('hidden');
  }

  function closeAddPaymentModal() {
    document.getElementById('addPaymentModal').style.display = 'none';
    document.getElementById('addPaymentModal').classList.add('hidden');
  }

  async function submitAddPayment() {
    const form = document.getElementById('addPaymentForm');
    const data = {
      reservation_id: document.getElementById('reservation_id').value,
      method_id: document.getElementById('method_id').value,
      transaction_type: document.getElementById('transaction_type').value,
      amount: document.getElementById('amount').value,
      reference_number: document.getElementById('reference_number').value,
      proof_image_url: document.getElementById('proof_image_url').value,
      payment_status: document.getElementById('payment_status').value,
      payment_date: document.getElementById('payment_date').value,
      payment_time: document.getElementById('payment_time').value,
      notes: document.getElementById('notes').value,
    };
    try {
      const res = await fetch('../api/payments.php?action=create', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeAddPaymentModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Payment added successfully', timer: 2000, showConfirmButton: false });
        fetchPaymentsData();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to add payment', confirmButtonColor: '#3085d6' });
    }
  }

  function openEditPaymentModal(paymentId) {
    const payment = paymentsData.find(p => p.payment_id === paymentId);
    if (payment) {
      document.getElementById('edit_payment_id').value = payment.payment_id;
      document.getElementById('edit_reservation_id').value = payment.reservation_id;
      document.getElementById('edit_method_id').value = payment.method_id;
      document.getElementById('edit_transaction_type').value = payment.transaction_type;
      document.getElementById('edit_amount').value = payment.amount;
      document.getElementById('edit_reference_number').value = payment.reference_number || '';
      document.getElementById('edit_proof_image_url').value = payment.proof_image_url || '';
      document.getElementById('edit_payment_status').value = payment.payment_status;
      document.getElementById('edit_payment_date').value = payment.payment_date;
      document.getElementById('edit_payment_time').value = payment.payment_time || '';
      document.getElementById('edit_notes').value = payment.notes || '';
      document.getElementById('editPaymentModal').style.display = 'block';
      document.getElementById('editPaymentModal').classList.remove('hidden');
    }
  }

  function closeEditPaymentModal() {
    document.getElementById('editPaymentModal').style.display = 'none';
    document.getElementById('editPaymentModal').classList.add('hidden');
  }

  async function submitEditPayment() {
    const data = {
      payment_id: document.getElementById('edit_payment_id').value,
      reservation_id: document.getElementById('edit_reservation_id').value,
      method_id: document.getElementById('edit_method_id').value,
      transaction_type: document.getElementById('edit_transaction_type').value,
      amount: document.getElementById('edit_amount').value,
      reference_number: document.getElementById('edit_reference_number').value,
      proof_image_url: document.getElementById('edit_proof_image_url').value,
      payment_status: document.getElementById('edit_payment_status').value,
      payment_date: document.getElementById('edit_payment_date').value,
      payment_time: document.getElementById('edit_payment_time').value,
      notes: document.getElementById('edit_notes').value,
    };
    try {
      const res = await fetch('../api/payments.php?action=update', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        closeEditPaymentModal();
        Swal.fire({ icon: 'success', title: 'Success!', text: 'Payment updated successfully', timer: 2000, showConfirmButton: false });
        fetchPaymentsData();
      } else {
        Swal.fire({ icon: 'error', title: 'Error!', text: result.message, confirmButtonColor: '#3085d6' });
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to update payment', confirmButtonColor: '#3085d6' });
    }
  }

  function openViewPaymentModal(paymentId) {
    const payment = paymentsData.find(p => p.payment_id === paymentId);
    if (payment) {
      document.getElementById('view_payment_id').textContent = payment.payment_id;
      document.getElementById('view_reservation_id').textContent = payment.reservation_id || '-';
      document.getElementById('view_method').textContent = payment.method_label || 'N/A';
      document.getElementById('view_transaction_type').textContent = payment.transaction_type;
      document.getElementById('view_amount').textContent = '₱' + parseFloat(payment.amount).toFixed(2);
      document.getElementById('view_reference_number').textContent = payment.reference_number || 'N/A';
      document.getElementById('view_payment_date').textContent = payment.payment_date ? new Date(payment.payment_date).toLocaleDateString() : '-';
      document.getElementById('view_payment_time').textContent = payment.payment_time || 'N/A';
      document.getElementById('view_payment_status').textContent = payment.payment_status;
      document.getElementById('view_notes').textContent = payment.notes || 'N/A';
      document.getElementById('viewPaymentModal').style.display = 'block';
      document.getElementById('viewPaymentModal').classList.remove('hidden');
    }
  }

  function closeViewPaymentModal() {
    document.getElementById('viewPaymentModal').style.display = 'none';
    document.getElementById('viewPaymentModal').classList.add('hidden');
  }

  function confirmDeletePayment(paymentId) {
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
          const res = await fetch('../api/payments.php?action=delete', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ payment_id: paymentId })
          });
          const r = await res.json();
          if (r.success) {
            Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Payment deleted.', timer: 1500, showConfirmButton: false });
            fetchPaymentsData();
          } else {
            Swal.fire({ icon: 'error', title: 'Error!', text: r.message, confirmButtonColor: '#3085d6' });
          }
        } catch (e) {
          Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to delete payment', confirmButtonColor: '#3085d6' });
        }
      }
    });
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    document.querySelectorAll('#paymentsTable .payment-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }
</script>