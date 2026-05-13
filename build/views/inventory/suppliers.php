<?php if (false) {} ?>


<!-- PAGE CONTENT -->

      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Suppliers</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statTotal">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">With Contact</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statContact">-</h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">With TIN</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white" id="statTin">-</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Suppliers Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Suppliers</h6>
              <button onclick="openAddSupplierModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Add Supplier
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="suppliersTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Supplier</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Contact Person</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Location</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Payment Terms</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody id="suppliersTableBody">
                    <tr><td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Supplier Details Modal -->
      <div id="supplierDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeSupplierDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 700px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white" id="modalSupplierName">Supplier Details</h3>
            <button onclick="closeSupplierDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6" id="modalSupplierContent">
            <!-- Content will be populated by JavaScript -->
          </div>
        </div>
      </div>

      <!-- Add Supplier Modal -->
      <div id="addSupplierModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddSupplierModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Add New Supplier</h3>
            <button onclick="closeAddSupplierModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addSupplierForm" onsubmit="submitAddSupplier(event)">
            <div class="p-6">
              <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Supplier Code</label>
                    <input type="text" name="supplier_code" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., SUP-001">
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Company Name <span class="text-red-500">*</span></label>
                    <input type="text" name="company_name" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., ABC Supplies">
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Contact Person</label>
                  <input type="text" name="contact_person" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Full name">
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="email@example.com">
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Phone (Primary)</label>
                    <input type="text" name="phone_primary" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+1 234 567 890">
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Phone (Secondary)</label>
                  <input type="text" name="phone_secondary" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+1 234 567 890">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Address Line 1</label>
                  <input type="text" name="address_line1" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Street address">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Address Line 2</label>
                  <input type="text" name="address_line2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Apartment, suite, etc.">
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">City</label>
                    <input type="text" name="city" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="City name">
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Province</label>
                    <input type="text" name="province" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="State/Province">
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">TIN Number</label>
                    <input type="text" name="tin_number" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Tax ID">
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Payment Terms</label>
                    <input type="text" name="payment_terms" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Net 30, COD">
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" rows="3" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Additional notes..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeAddSupplierModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Save Supplier
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Supplier Modal -->
      <div id="editSupplierModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditSupplierModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Supplier</h3>
            <button onclick="closeEditSupplierModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="editSupplierForm" onsubmit="submitEditSupplier(event)">
            <input type="hidden" name="supplier_id" id="editSupplierId">
            <div class="p-6">
              <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Supplier Code</label>
                    <input type="text" name="supplier_code" id="editSupplierCode" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., SUP-001">
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Company Name <span class="text-red-500">*</span></label>
                    <input type="text" name="company_name" id="editCompanyName" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., ABC Supplies">
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Contact Person</label>
                  <input type="text" name="contact_person" id="editContactPerson" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Full name">
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Email</label>
                    <input type="email" name="email" id="editEmail" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="email@example.com">
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Phone (Primary)</label>
                    <input type="text" name="phone_primary" id="editPhonePrimary" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+1 234 567 890">
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Phone (Secondary)</label>
                  <input type="text" name="phone_secondary" id="editPhoneSecondary" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+1 234 567 890">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Address Line 1</label>
                  <input type="text" name="address_line1" id="editAddressLine1" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Street address">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Address Line 2</label>
                  <input type="text" name="address_line2" id="editAddressLine2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Apartment, suite, etc.">
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">City</label>
                    <input type="text" name="city" id="editCity" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="City name">
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Province</label>
                    <input type="text" name="province" id="editProvince" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="State/Province">
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">TIN Number</label>
                    <input type="text" name="tin_number" id="editTinNumber" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Tax ID">
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Payment Terms</label>
                    <input type="text" name="payment_terms" id="editPaymentTerms" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Net 30, COD">
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" id="editNotes" rows="3" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Additional notes..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeEditSupplierModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Update Supplier
              </button>
            </div>
          </form>
        </div>
      </div>

<script>
  let suppliersData = [];

  document.addEventListener('DOMContentLoaded', function () {
    fetchSuppliers();
  });

  async function fetchSuppliers() {
    try {
      const res = await fetch('../api/suppliers.php?action=list');
      const result = await res.json();
      if (result.success) {
        suppliersData = result.data;
        renderTable();
        renderStats();
      } else {
        document.getElementById('suppliersTableBody').innerHTML = `<tr><td colspan="6" class="p-4 text-center text-red-400">${result.message}</td></tr>`;
      }
    } catch (e) {
      document.getElementById('suppliersTableBody').innerHTML = `<tr><td colspan="6" class="p-4 text-center text-red-400">Failed to load data</td></tr>`;
    }
  }

  function renderStats() {
    document.getElementById('statTotal').textContent = suppliersData.length;
    document.getElementById('statContact').textContent = suppliersData.filter(s => s.contact_person).length;
    document.getElementById('statTin').textContent = suppliersData.filter(s => s.tin_number).length;
  }

  function renderTable() {
    const tbody = document.getElementById('suppliersTableBody');
    if (!suppliersData.length) {
      tbody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">No suppliers found. <a href="javascript:;" onclick="openAddSupplierModal()" class="text-blue-500 font-semibold">Add your first supplier</a>.</td></tr>`;
      return;
    }
    tbody.innerHTML = suppliersData.map(s => `
      <tr class="supplier-row">
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex px-2 py-1">
            <div class="inline-flex items-center justify-center mr-4 text-sm text-white h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500"><i class="fas fa-building"></i></div>
            <div class="flex flex-col justify-center">
              <h6 class="mb-0 text-sm leading-normal dark:text-white">${s.company_name}</h6>
              <p class="mb-0 text-xs leading-tight text-slate-400 dark:text-white dark:opacity-80">${s.supplier_code || '-'}</p>
            </div>
          </div>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${s.contact_person || '-'}</p>
          <p class="mb-0 text-xs leading-tight text-slate-400 dark:text-white dark:opacity-60">${s.email || '-'}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${s.city || '-'}</p>
          <p class="mb-0 text-xs leading-tight text-slate-400 dark:text-white dark:opacity-60">${s.province || '-'}</p>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">${s.payment_terms || '-'}</p>
        </td>
        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center font-bold uppercase leading-none text-white">Active</span>
        </td>
        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
          <div class="flex items-center gap-2">
            <button onclick="viewSupplierDetails('${s.supplier_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-all duration-200 shadow-sm hover:shadow-md"><i class="fas fa-info-circle mr-1.5 text-xs"></i><span>View</span></button>
            <button onclick="openEditSupplierModal('${s.supplier_id}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 hover:text-amber-600 hover:border-amber-300 transition-all duration-200 shadow-sm hover:shadow-md"><i class="fas fa-edit mr-1.5 text-xs"></i><span>Edit</span></button>
            <button onclick="deleteSupplier('${s.supplier_id}', '${s.company_name.replace(/'/g, "\\'")}}')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 hover:text-red-700 hover:border-red-300 transition-all duration-200 shadow-sm hover:shadow-md"><i class="fas fa-trash-alt mr-1.5 text-xs"></i><span>Delete</span></button>
          </div>
        </td>
      </tr>`).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#suppliersTable .supplier-row').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
  }

  // View Supplier Details
  function viewSupplierDetails(supplierId) {
    const supplier = suppliersData.find(s => s.supplier_id === supplierId);
    if (!supplier) return;

    const modal = document.getElementById('supplierDetailsModal');
    const modalTitle = document.getElementById('modalSupplierName');
    const modalContent = document.getElementById('modalSupplierContent');

    modalTitle.textContent = supplier.company_name;

    const address = [supplier.address_line1, supplier.address_line2, supplier.city, supplier.province].filter(Boolean).join(', ');

    modalContent.innerHTML = `
        <div class="space-y-4">
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Basic Information</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Supplier Code</p>
                <p class="text-sm dark:text-white">${supplier.supplier_code || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Company Name</p>
                <p class="text-sm dark:text-white">${supplier.company_name}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Contact Person</p>
                <p class="text-sm dark:text-white">${supplier.contact_person || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Email</p>
                <p class="text-sm dark:text-white">${supplier.email || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Phone (Primary)</p>
                <p class="text-sm dark:text-white">${supplier.phone_primary || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Phone (Secondary)</p>
                <p class="text-sm dark:text-white">${supplier.phone_secondary || '-'}</p>
              </div>
            </div>
          </div>
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Address</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Address</p>
                <p class="text-sm dark:text-white">${address || '-'}</p>
              </div>
            </div>
          </div>
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Business Details</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">TIN Number</p>
                <p class="text-sm dark:text-white">${supplier.tin_number || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Payment Terms</p>
                <p class="text-sm dark:text-white">${supplier.payment_terms || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Notes</p>
                <p class="text-sm dark:text-white">${supplier.notes || '-'}</p>
              </div>
            </div>
          </div>
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Status</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Status</p>
                <p class="text-sm dark:text-white">${supplier.is_active ? 'Active' : 'Inactive'}</p>
              </div>
            </div>
          </div>
        </div>
      `;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeSupplierDetailsModal() {
    const modal = document.getElementById('supplierDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  // Add Supplier Modal Functions
  function openAddSupplierModal() {
    const modal = document.getElementById('addSupplierModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeAddSupplierModal() {
    const modal = document.getElementById('addSupplierModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('addSupplierForm').reset();
  }

  function submitAddSupplier(event) {
    event.preventDefault();

    const form = document.getElementById('addSupplierForm');
    const formData = new FormData(form);
    formData.append('action', 'add_supplier');

    // Show loading state with higher z-index
    Swal.fire({
      title: 'Adding Supplier...',
      text: 'Please wait while we add the supplier.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

    const payload = {
      supplier_code: formData.get('supplier_code'), company_name: formData.get('company_name'),
      contact_person: formData.get('contact_person'), email: formData.get('email'),
      phone_primary: formData.get('phone_primary'), phone_secondary: formData.get('phone_secondary'),
      address_line1: formData.get('address_line1'), address_line2: formData.get('address_line2'),
      city: formData.get('city'), province: formData.get('province'),
      tin_number: formData.get('tin_number'), payment_terms: formData.get('payment_terms'),
      notes: formData.get('notes')
    };
    fetch('../api/suppliers.php?action=create', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeAddSupplierModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6' }).then(() => fetchSuppliers());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33' });
        }
      })
      .catch(() => { closeAddSupplierModal(); Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred.', confirmButtonColor: '#d33' }); });
  }

  // Edit Supplier Modal Functions
  function openEditSupplierModal(supplierId) {
    const supplier = suppliersData.find(s => s.supplier_id === supplierId);
    if (!supplier) return;

    // Populate form with existing data
    document.getElementById('editSupplierId').value = supplier.supplier_id;
    document.getElementById('editSupplierCode').value = supplier.supplier_code || '';
    document.getElementById('editCompanyName').value = supplier.company_name;
    document.getElementById('editContactPerson').value = supplier.contact_person || '';
    document.getElementById('editEmail').value = supplier.email || '';
    document.getElementById('editPhonePrimary').value = supplier.phone_primary || '';
    document.getElementById('editPhoneSecondary').value = supplier.phone_secondary || '';
    document.getElementById('editAddressLine1').value = supplier.address_line1 || '';
    document.getElementById('editAddressLine2').value = supplier.address_line2 || '';
    document.getElementById('editCity').value = supplier.city || '';
    document.getElementById('editProvince').value = supplier.province || '';
    document.getElementById('editTinNumber').value = supplier.tin_number || '';
    document.getElementById('editPaymentTerms').value = supplier.payment_terms || '';
    document.getElementById('editNotes').value = supplier.notes || '';

    const modal = document.getElementById('editSupplierModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeEditSupplierModal() {
    const modal = document.getElementById('editSupplierModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('editSupplierForm').reset();
  }

  function submitEditSupplier(event) {
    event.preventDefault();

    const form = document.getElementById('editSupplierForm');
    const formData = new FormData(form);
    formData.append('action', 'edit_supplier');

    // Show loading state with higher z-index
    Swal.fire({
      title: 'Updating Supplier...',
      text: 'Please wait while we update the supplier.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });

    const payload = {
      supplier_id: formData.get('supplier_id'), supplier_code: formData.get('supplier_code'),
      company_name: formData.get('company_name'), contact_person: formData.get('contact_person'),
      email: formData.get('email'), phone_primary: formData.get('phone_primary'),
      phone_secondary: formData.get('phone_secondary'), address_line1: formData.get('address_line1'),
      address_line2: formData.get('address_line2'), city: formData.get('city'),
      province: formData.get('province'), tin_number: formData.get('tin_number'),
      payment_terms: formData.get('payment_terms'), notes: formData.get('notes')
    };
    fetch('../api/suppliers.php?action=update', {
        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        closeEditSupplierModal();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#3085d6' }).then(() => fetchSuppliers());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33' });
        }
      })
      .catch(() => { closeEditSupplierModal(); Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred.', confirmButtonColor: '#d33' }); });
  }

  // Delete Supplier Function with SweetAlert
  function deleteSupplier(supplierId, companyName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete "${companyName}"? This action cannot be undone.`,
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
          title: 'Deleting Supplier...',
          text: 'Please wait while we delete the supplier.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });

        fetch('../api/suppliers.php?action=delete', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ supplier_id: supplierId })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, confirmButtonColor: '#3085d6' }).then(() => fetchSuppliers());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33' });
            }
          })
          .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred.', confirmButtonColor: '#d33' }));
      }
    });
  }
</script>