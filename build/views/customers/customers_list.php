<?php if (false) {
} ?>


<!-- PAGE CONTENT -->
<!-- Stats Cards -->
<div class="flex flex-wrap -mx-3 mb-6">
  <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-2/3 max-w-full px-3">
            <div>
              <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Total Customers</p>
              <h5 class="mb-2 font-bold dark:text-white" id="statTotal">0</h5>
            </div>
          </div>
          <div class="px-3 text-right basis-1/3">
            <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-blue-500 to-violet-500">
              <i class="ni leading-none ni-single-02 text-lg relative top-3.5 text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-2/3 max-w-full px-3">
            <div>
              <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Active Customers</p>
              <h5 class="mb-2 font-bold dark:text-white" id="statActive">0</h5>
            </div>
          </div>
          <div class="px-3 text-right basis-1/3">
            <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-emerald-500 to-teal-400">
              <i class="ni leading-none ni-check-bold text-lg relative top-3.5 text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-2/3 max-w-full px-3">
            <div>
              <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Verified</p>
              <h5 class="mb-2 font-bold dark:text-white" id="statVerified">0</h5>
            </div>
          </div>
          <div class="px-3 text-right basis-1/3">
            <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-orange-500 to-yellow-500">
              <i class="ni leading-none ni-check-bold text-lg relative top-3.5 text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Customers Table -->
<div class="flex flex-wrap -mx-3">
  <div class="w-full max-w-full px-3">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="p-4 pb-0 mb-0 rounded-t-2xl">
        <div style="display: flex; flex-direction: column; gap: 16px;">
          <h6 class="mb-0 dark:text-white">Customers List</h6>
          <div class="controls-container" style="display: flex; flex-direction: column; gap: 12px;">
            <div class="search-wrapper" style="display: flex; flex-direction: column; gap: 12px; width: 100%;">
              <input
                type="text"
                id="searchInput"
                placeholder="Search customers..."
                onkeyup="filterTable()"
                style="padding: 8px 14px; font-size: 0.875rem; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; width: 100%;">
            </div>
            <div class="filter-button-group" style="display: flex; flex-direction: column; gap: 12px; width: 100%;">
              <select
                id="statusFilter"
                onchange="filterTable()"
                style="padding: 8px 14px; font-size: 0.875rem; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; width: 100%;">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
                <option value="pending_verification">Pending</option>
              </select>
              <button
                onclick="createCustomer()"
                style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 8px 18px; font-size: 0.75rem; font-weight: 600; color: white; background-color: #334155; border-radius: 8px; border: none; cursor: pointer; white-space: nowrap; width: 100%;">
                <i class="fas fa-plus" style="font-size: 0.75rem;"></i> Add New Customer
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Responsive Desktop Layout -->
      <style>
        @media (min-width: 640px) {
          .controls-container {
            flex-direction: row !important;
            align-items: center !important;
          }

          .search-wrapper {
            width: 210px !important;
          }

          .filter-button-group {
            flex-direction: row !important;
            width: auto !important;
          }

          .filter-button-group button {
            width: auto !important;
          }

          .filter-button-group select {
            width: 140px !important;
          }
        }

        @media (min-width: 768px) {
          .search-wrapper {
            width: 240px !important;
          }

          .filter-button-group select {
            width: 160px !important;
          }
        }

        @media (min-width: 1024px) {
          .controls-container {
            justify-content: space-between !important;
          }

          .search-wrapper {
            width: 280px !important;
          }

          .filter-button-group select {
            width: 180px !important;
          }
        }
      </style>
      <div class="p-0 overflow-x-auto">
        <table class="items-center justify-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
          <thead class="align-bottom">
            <tr>
              <th class="px-6 py-4 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Customer</th>
              <th class="px-6 py-4 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Phone</th>
              <th class="px-6 py-4 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">City</th>
              <th class="px-6 py-4 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Created</th>
              <th class="px-6 py-4 font-bold text-center uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
              <th class="px-6 py-4 font-bold text-center uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Actions</th>
            </tr>
          </thead>
          <tbody id="customersTableBody">
            <tr>
              <td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">Loading customers...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Cropper Modal -->
<div id="cropperModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:12px;padding:24px;max-width:90%;max-height:90%;overflow:auto;">
    <h3 style="margin:0 0 16px 0;font-size:18px;font-weight:600;color:#1e293b;">Crop Image</h3>
    <div style="margin-bottom:16px;">
      <img id="cropperImage" style="max-width:100%;max-height:500px;display:block;" alt="Image to crop">
    </div>
    <div style="display:flex;gap:8px;justify-content:flex-end;">
      <button onclick="cancelCrop()" style="padding:8px 16px;font-size:14px;font-weight:600;color:#fff;background:#dc2626;border:none;border-radius:8px;cursor:pointer;">Cancel</button>
      <button onclick="applyCrop()" style="padding:8px 16px;font-size:14px;font-weight:600;color:#fff;background:#3b82f6;border:none;border-radius:8px;cursor:pointer;">Apply Crop</button>
    </div>
  </div>
</div>

<script>
  let csrfToken = "<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>";
  let customersData = [];
  let croppedImageData = null;
  let currentPreviewId = null;
  let cropper = null;

  // Geocode data cache
  let regionsData = [];

  function getPhotoPreviewHtml(photoUrl, previewId, showRemove = false) {
    const imgOrIcon = photoUrl ?
      `<img src="${photoUrl}" class="w-full h-full object-cover" alt="Profile photo">` :
      `<i class="fas fa-user text-3xl text-slate-400"></i>`;

    const removeButton =
      showRemove && photoUrl ?
      `<button type="button" onclick="removePhoto('${previewId}')"
             class="text-xs text-red-500 hover:text-red-700 mt-1">
             <i class="fas fa-trash mr-1"></i>Remove
           </button>` :
      '';

    return `
      <div style="display:flex;flex-direction:column;align-items:center;gap:6px;">
        <div id="${previewId}"
             style="width:80px;height:80px;border-radius:50%;overflow:hidden;border:2px solid #e2e8f0;
                    background:#f1f5f9;display:flex;align-items:center;justify-content:center;
                    position:relative;flex-shrink:0;">
          ${imgOrIcon}
        </div>
        <label style="cursor:pointer;font-size:12px;color:#4f46e5;font-weight:600;">
          <i class="fas fa-camera" style="margin-right:3px;"></i>${showRemove ? 'Change' : 'Upload'}
          <input type="file" id="${previewId.replace('_preview', '')}"
                 accept="image/*" style="display:none;"
                 onchange="previewPhoto(this, '${previewId}')">
        </label>
        ${removeButton}
      </div>
    `;
  }

  /* ─── API ─────────────────────────────────────────────────────────────── */

  async function fetchCustomers() {
    try {
      const res = await fetch('../api/customers.php?action=list');
      const json = await res.json();
      if (json.success) {
        customersData = json.data;
        renderStats();
        renderTable();
      }
    } catch (e) {
      console.error('Error fetching customers:', e);
    }
  }

  function renderStats() {
    const total = customersData.length;
    const active = customersData.filter(c => c.account_status === 'active').length;
    const verified = customersData.filter(c => c.is_email_verified == 1).length;
    document.getElementById('statTotal').textContent = total;
    document.getElementById('statActive').textContent = active;
    document.getElementById('statVerified').textContent = verified;
  }

  function renderTable() {
    const tbody = document.getElementById('customersTableBody');
    if (!customersData.length) {
      tbody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">No customers found.</td></tr>`;
      return;
    }
    tbody.innerHTML = customersData.map(customer => {
      const parts = [
        customer.barangay_name,
        customer.city_municipality_name,
        customer.province_name,
        customer.region_name,
      ].filter(Boolean);
      const addressDisplay = parts.length ? parts.join(', ') : '-';

      return `
      <tr class="customer-row">
        <td class="p-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <div class="flex items-center">
            ${customer.profile_photo_url
              ? `<img src="${customer.profile_photo_url}" class="w-10 h-10 mr-4 rounded-full object-cover border-2 border-slate-200" alt="${customer.first_name}">` 
              : `<div class="inline-flex items-center justify-center w-10 h-10 mr-4 text-sm text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-full">
                   ${customer.first_name ? customer.first_name.charAt(0).toUpperCase() : '?'}
                 </div>`
            }
            <div>
              <p class="mb-0 text-sm font-semibold leading-tight dark:text-white">${customer.first_name || ''} ${customer.last_name || ''}</p>
              <p class="mb-0 text-xs leading-tight text-slate-400 dark:text-white/60">${customer.email}</p>
            </div>
          </div>
        </td>
        <td class="p-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <p class="mb-0 text-sm font-semibold leading-tight dark:text-white">${customer.phone_number || '-'}</p>
        </td>
        <td class="p-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <p class="mb-0 text-sm font-semibold leading-tight dark:text-white max-w-xs truncate" title="${addressDisplay}">${addressDisplay}</p>
        </td>
        <td class="p-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <p class="mb-0 text-sm font-semibold leading-tight dark:text-white">
            ${new Date(customer.created_at).toLocaleString('en-US', { month:'short', day:'numeric', year:'numeric', hour:'numeric', minute:'2-digit', hour12:true })}
          </p>
        </td>
        <td class="p-4 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <span class="bg-gradient-to-tl ${
            customer.account_status === 'active'    ? 'from-emerald-500 to-teal-400'  :
            customer.account_status === 'suspended' ? 'from-red-500 to-orange-500'    :
                                                      'from-slate-500 to-slate-400'
          } px-4 text-sm rounded-1.8 py-2 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
            ${customer.account_status || 'unknown'}
          </span>
        </td>
        <td class="p-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <div class="flex items-center gap-2">
            <button onclick="viewCustomer('${customer.user_id}')"
                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-600 dark:text-blue-400 bg-white dark:bg-slate-700/50 border border-blue-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 transition-all duration-200 shadow-sm">
              <i class="fas fa-eye mr-2 text-sm"></i>View
            </button>
            <button onclick="editCustomer('${customer.user_id}')"
                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 transition-all duration-200 shadow-sm">
              <i class="fas fa-edit mr-2 text-sm"></i>Edit
            </button>
            <button onclick="deleteCustomer('${customer.user_id}', '${customer.first_name || ''} ${customer.last_name || ''}')"
                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 transition-all duration-200 shadow-sm">
              <i class="fas fa-trash-alt mr-2 text-sm"></i>Delete
            </button>
          </div>
        </td>
      </tr>`;
    }).join('');
  }

  /* ─── VIEW ────────────────────────────────────────────────────────────── */

  async function viewCustomer(userId) {
    const res = await fetch(`../api/customers.php?action=single&id=${userId}`);
    const json = await res.json();
    if (!json.success) {
      return Swal.fire({
        icon: 'error',
        title: 'Error',
        text: json.message,
        confirmButtonColor: '#d33',
        customClass: {
          popup: 'swal2-popup-high-z'
        }
      });
    }

    const customer = json.data;
    let addressDetails = {};
    if (customer.region_code || customer.province_code || customer.city_municipality_code || customer.barangay_code) {
      const params = new URLSearchParams();
      if (customer.region_code) params.append('region_code', customer.region_code);
      if (customer.province_code) params.append('province_code', customer.province_code);
      if (customer.city_municipality_code) params.append('city_municipality_code', customer.city_municipality_code);
      if (customer.barangay_code) params.append('barangay_code', customer.barangay_code);

      const geoRes = await fetch(`../api/geocode.php?action=full_address&${params}`);
      const geoData = await geoRes.json();
      if (geoData.success) addressDetails = geoData.data;
    }

    const photoUrl = customer.profile_photo_url || '<?php echo dirname(dirname($_SERVER['PHP_SELF'])) . '/uploads/profile_photos/default_profile.jpg'; ?>';
    const fullAddress = [
      customer.address_line1,
      customer.address_line2,
      addressDetails.barangay,
      addressDetails.city,
      addressDetails.province,
      addressDetails.region,
      customer.postal_code,
      customer.country,
    ].filter(Boolean).join(', ') || 'No address provided';

    Swal.fire({
      title: 'Customer Details',
      html: `
      <div class="text-left flex flex-col items-center">
        <img src="${photoUrl}" class="w-24 h-24 rounded-full object-cover border-2 border-slate-200 shadow-md mb-4" alt="${customer.first_name}">
        <p class="text-lg font-semibold mb-3">${customer.first_name || ''} ${customer.last_name || ''}</p>
        <div class="w-full border-t pt-3 mt-2">
          <p><strong>Email:</strong> ${customer.email || '-'}</p>
          <p><strong>Phone:</strong> ${customer.phone_number || '-'}</p>
          <p><strong>Status:</strong>
            <span class="px-2 py-1 rounded text-xs font-bold uppercase ${
              customer.account_status === 'active'    ? 'bg-green-100 text-green-800' :
              customer.account_status === 'suspended' ? 'bg-red-100 text-red-800'     :
                                                        'bg-gray-100 text-gray-800'
            }">${customer.account_status || 'unknown'}</span>
          </p>
          <div class="mt-3">
            <p class="font-semibold mb-1">Address:</p>
            <p class="text-sm text-slate-600">${fullAddress}</p>
          </div>
          <p><strong>Email Verified:</strong> ${customer.is_email_verified ? '✓ Yes' : '✗ No'}</p>
          <p><strong>Phone Verified:</strong>  ${customer.is_phone_verified  ? '✓ Yes' : '✗ No'}</p>
          <p><strong>Created:</strong> ${new Date(customer.created_at).toLocaleString('en-US', { month:'short', day:'numeric', year:'numeric', hour:'numeric', minute:'2-digit', hour12:true })}</p>
          ${customer.last_login_at ? `<p><strong>Last Login:</strong> ${new Date(customer.last_login_at).toLocaleString('en-US', { month:'short', day:'numeric', year:'numeric', hour:'numeric', minute:'2-digit', hour12:true })}</p>` : ''}
        </div>
      </div>`,
      width: '40rem',
      confirmButtonColor: '#3085d6',
      customClass: {
        popup: 'swal2-popup-high-z'
      },
    });
  }

  /* ─── EDIT ────────────────────────────────────────────────────────────── */

  async function editCustomer(userId) {
    const regions = await fetchRegions();

    fetch(`../api/customers.php?action=single&id=${userId}`)
      .then(r => r.json())
      .then(async (data) => {
        if (!data.success) {
          return Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message,
            confirmButtonColor: '#d33',
            customClass: {
              popup: 'swal2-popup-high-z'
            }
          });
        }

        const customer = data.data;
        const photoUrl = customer.profile_photo_url || '<?php echo dirname(dirname($_SERVER['PHP_SELF'])) . '/uploads/profile_photos/default_profile.jpg'; ?>';

        Swal.fire({
          title: 'Edit Customer',
          html: `
          <form id="editCustomerForm" class="text-left" onsubmit="return false;">

            <!-- ── Photo + basic info ── -->
            <div style="display:flex;align-items:flex-start;gap:20px;margin-bottom:20px;">

              <!-- Photo column -->
              <div style="flex-shrink:0;">
                ${getPhotoPreviewHtml(photoUrl, 'edit_photo_preview', true)}
                <input type="hidden" id="edit_remove_photo" value="0">
              </div>

              <!-- Name / email / phone column -->
              <div style="flex:1;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                  <label style="display:block;font-size:13px;font-weight:600;margin-bottom:4px;">First Name <span style="color:#ef4444;">*</span></label>
                  <input type="text" id="edit_first_name" value="${escHtml(customer.first_name || '')}"
                         style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:14px;box-sizing:border-box;" required>
                </div>
                <div>
                  <label style="display:block;font-size:13px;font-weight:600;margin-bottom:4px;">Last Name <span style="color:#ef4444;">*</span></label>
                  <input type="text" id="edit_last_name" value="${escHtml(customer.last_name || '')}"
                         style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:14px;box-sizing:border-box;" required>
                </div>
                <div>
                  <label style="display:block;font-size:13px;font-weight:600;margin-bottom:4px;">Email <span style="color:#ef4444;">*</span></label>
                  <input type="email" id="edit_email" value="${escHtml(customer.email || '')}"
                         style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:14px;box-sizing:border-box;" required>
                </div>
                <div>
                  <label style="display:block;font-size:13px;font-weight:600;margin-bottom:4px;">Phone Number</label>
                  <input type="text" id="edit_phone_number" value="${escHtml(customer.phone_number || '')}"
                         style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:14px;box-sizing:border-box;">
                </div>
              </div>
            </div>

            <!-- ── Address ── -->
            <div style="border-top:1px solid #e2e8f0;padding-top:16px;margin-top:4px;">
              <p style="font-size:13px;font-weight:600;margin:0 0 12px;display:flex;align-items:center;gap:6px;">
                <i class="fas fa-map-marker-alt" style="color:#94a3b8;"></i> Address
              </p>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">

                <div>
                  <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Region</label>
                  <input type="text" id="edit_region_code" list="edit_region_list"
                         placeholder="Search region…"
                         style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;"
                         oninput="onRegionChange('edit')">
                  <datalist id="edit_region_list"></datalist>
                </div>

                <div>
                  <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Province</label>
                  <input type="text" id="edit_province_code" list="edit_province_list"
                         placeholder="Search province…"
                         style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;"
                         oninput="onProvinceChange('edit')">
                  <datalist id="edit_province_list"></datalist>
                </div>

                <div>
                  <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">City / Municipality</label>
                  <input type="text" id="edit_city_code" list="edit_city_list"
                         placeholder="Search city…"
                         style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;"
                         oninput="onCityChange('edit')">
                  <datalist id="edit_city_list"></datalist>
                </div>

                <div>
                  <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Barangay</label>
                  <input type="text" id="edit_barangay_code" list="edit_barangay_list"
                         placeholder="Search barangay…"
                         style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;"
                         oninput="onBarangayInput('edit')">
                  <datalist id="edit_barangay_list"></datalist>
                </div>

                <div style="grid-column:1/-1;">
                  <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Address Line 1</label>
                  <input type="text" id="edit_address_line1" value="${escHtml(customer.address_line1 || '')}"
                         placeholder="House/Unit number, Street name"
                         style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;">
                </div>

                <div>
                  <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Address Line 2</label>
                  <input type="text" id="edit_address_line2" value="${escHtml(customer.address_line2 || '')}"
                         placeholder="Building, Subdivision…"
                         style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;">
                </div>

                <div>
                  <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Postal Code</label>
                  <input type="text" id="edit_postal_code" value="${escHtml(customer.postal_code || '')}"
                         placeholder="Zip code"
                         style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;">
                </div>

              </div>
            </div>

            <!-- ── Account status ── -->
            <div style="border-top:1px solid #e2e8f0;padding-top:16px;margin-top:16px;">
              <div style="max-width:220px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:4px;">Account Status</label>
                <select id="edit_account_status"
                        style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;">
                  <option value="active"    ${customer.account_status === 'active'    ? 'selected' : ''}>Active</option>
                  <option value="suspended" ${customer.account_status === 'suspended' ? 'selected' : ''}>Suspended</option>
                  <option value="inactive"  ${customer.account_status === 'inactive'  ? 'selected' : ''}>Inactive</option>
                </select>
              </div>
            </div>

            <input type="hidden" id="edit_user_id" value="${customer.user_id}">

            <div style="border-top:1px solid #e2e8f0;padding-top:16px;margin-top:16px;display:flex;gap:12px;justify-content:flex-end;">
              <button id="edit_cancel_btn" type="button"
                      style="padding:10px 24px;font-size:14px;font-weight:600;color:#991b1b;
                             background-color:#fee2e2;border:2px solid #991b1b;border-radius:8px;
                             cursor:pointer;transition:all 0.2s;">
                Cancel
              </button>
              <button id="edit_save_btn" type="button"
                      style="padding:10px 24px;font-size:14px;font-weight:600;color:#1e3a8a;
                             background-color:#e0e7ff;border:2px solid #1e3a8a;border-radius:8px;
                             cursor:pointer;transition:all 0.2s;">
                Save Changes
              </button>
            </div>
          </form>
        `,
          width: '48rem',
          showConfirmButton: false,
          showCancelButton: false,
          didOpen: async () => {
            /* Custom button handlers */
            document.getElementById('edit_cancel_btn').addEventListener('click', () => Swal.close());
            document.getElementById('edit_save_btn').addEventListener('click', async () => {
              const first = document.getElementById('edit_first_name').value.trim();
              const last = document.getElementById('edit_last_name').value.trim();
              const email = document.getElementById('edit_email').value.trim();
              if (!first || !last || !email) {
                Swal.showValidationMessage('First name, last name and email are required');
                return;
              }

              const fd = new FormData();
              fd.append('user_id', document.getElementById('edit_user_id').value);
              fd.append('first_name', document.getElementById('edit_first_name').value.trim());
              fd.append('last_name', document.getElementById('edit_last_name').value.trim());
              fd.append('email', document.getElementById('edit_email').value.trim());
              fd.append('phone_number', document.getElementById('edit_phone_number').value.trim());
              fd.append('region_code', document.getElementById('edit_region_code').dataset.code || '');
              fd.append('province_code', document.getElementById('edit_province_code').dataset.code || '');
              fd.append('city_municipality_code', document.getElementById('edit_city_code').dataset.code || '');
              fd.append('barangay_code', document.getElementById('edit_barangay_code').dataset.code || '');
              fd.append('address_line1', document.getElementById('edit_address_line1').value.trim());
              fd.append('address_line2', document.getElementById('edit_address_line2').value.trim());
              fd.append('postal_code', document.getElementById('edit_postal_code').value.trim());
              fd.append('account_status', document.getElementById('edit_account_status').value);
              fd.append('remove_photo', document.getElementById('edit_remove_photo').value);

              if (croppedImageData) {
                fd.append('profile_photo', new File([croppedImageData], 'cropped_photo.jpg', {
                  type: 'image/jpeg'
                }));
                croppedImageData = null;
              } else {
                const raw = document.getElementById('edit_photo');
                if (raw && raw.files[0]) fd.append('profile_photo', raw.files[0]);
              }

              Swal.close();
              submitCustomerUpdate(fd);
            });

            /* Regions */
            populateDatalist('edit_region_list', regions, 'region', customer.region_code);

            if (customer.region_code) {
              const provinces = await fetchProvinces(customer.region_code);
              populateDatalist('edit_province_list', provinces, 'province', customer.province_code);

              if (customer.province_code) {
                const cities = await fetchCities(customer.province_code);
                /* NOTE: input id is edit_city_code (not edit_city_municipality_code) */
                populateDatalist('edit_city_list', cities, 'city', customer.city_municipality_code);

                if (customer.city_municipality_code) {
                  const barangays = await fetchBarangays(customer.city_municipality_code);
                  populateDatalist('edit_barangay_list', barangays, 'barangay', customer.barangay_code);
                }
              }
            }
          },
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });
      })
      .catch(() => {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to load customer details',
          confirmButtonColor: '#d33',
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });
      });
  }

  function submitCustomerUpdate(formData) {
    formData.append('csrf_token', csrfToken);
    Swal.fire({
      title: 'Updating…',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
      customClass: {
        popup: 'swal2-popup-high-z'
      }
    });
    fetch('../api/customers.php?action=update', {
        method: 'POST',
        body: formData
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
              icon: 'success',
              title: 'Updated!',
              text: data.message,
              confirmButtonColor: '#3085d6',
              customClass: {
                popup: 'swal2-popup-high-z'
              }
            })
            .then(() => fetchCustomers());
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
      });
  }

  /* ─── CREATE ──────────────────────────────────────────────────────────── */

  async function createCustomer() {
    const regions = await fetchRegions();

    Swal.fire({
      title: 'Add New Customer',
      html: `
      <form id="createCustomerForm" class="text-left" onsubmit="return false;">

        <!-- ── Photo + basic info ── -->
        <div style="display:flex;align-items:flex-start;gap:20px;margin-bottom:20px;">

          <!-- Photo column -->
          <div style="flex-shrink:0;">
            ${getPhotoPreviewHtml('', 'create_photo_preview', false)}
          </div>

          <!-- Name / email / phone column -->
          <div style="flex:1;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div>
              <label style="display:block;font-size:13px;font-weight:600;margin-bottom:4px;">First Name <span style="color:#ef4444;">*</span></label>
              <input type="text" id="create_first_name"
                     style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:14px;box-sizing:border-box;" required>
            </div>
            <div>
              <label style="display:block;font-size:13px;font-weight:600;margin-bottom:4px;">Last Name <span style="color:#ef4444;">*</span></label>
              <input type="text" id="create_last_name"
                     style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:14px;box-sizing:border-box;" required>
            </div>
            <div>
              <label style="display:block;font-size:13px;font-weight:600;margin-bottom:4px;">Email <span style="color:#ef4444;">*</span></label>
              <input type="email" id="create_email"
                     style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:14px;box-sizing:border-box;" required>
            </div>
            <div>
              <label style="display:block;font-size:13px;font-weight:600;margin-bottom:4px;">Phone Number</label>
              <input type="text" id="create_phone_number"
                     style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:14px;box-sizing:border-box;">
            </div>
          </div>
        </div>

        <!-- ── Address ── -->
        <div style="border-top:1px solid #e2e8f0;padding-top:16px;margin-top:4px;">
          <p style="font-size:13px;font-weight:600;margin:0 0 12px;display:flex;align-items:center;gap:6px;">
            <i class="fas fa-map-marker-alt" style="color:#94a3b8;"></i> Address
          </p>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div>
              <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Region</label>
              <input type="text" id="create_region_code" list="create_region_list"
                     placeholder="Search region…"
                     style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;"
                     oninput="onRegionChange('create')">
              <datalist id="create_region_list"></datalist>
            </div>
            <div>
              <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Province</label>
              <input type="text" id="create_province_code" list="create_province_list"
                     placeholder="Search province…"
                     style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;"
                     oninput="onProvinceChange('create')">
              <datalist id="create_province_list"></datalist>
            </div>
            <div>
              <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">City / Municipality</label>
              <input type="text" id="create_city_code" list="create_city_list"
                     placeholder="Search city…"
                     style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;"
                     oninput="onCityChange('create')">
              <datalist id="create_city_list"></datalist>
            </div>
            <div>
              <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Barangay</label>
              <input type="text" id="create_barangay_code" list="create_barangay_list"
                     placeholder="Search barangay…"
                     style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;"
                     oninput="onBarangayInput('create')">
              <datalist id="create_barangay_list"></datalist>
            </div>
            <div style="grid-column:1/-1;">
              <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Address Line 1</label>
              <input type="text" id="create_address_line1"
                     placeholder="House/Unit number, Street name"
                     style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;">
            </div>
            <div>
              <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Address Line 2</label>
              <input type="text" id="create_address_line2"
                     placeholder="Building, Subdivision…"
                     style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;">
            </div>
            <div>
              <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Postal Code</label>
              <input type="text" id="create_postal_code"
                     placeholder="Zip code"
                     style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;">
            </div>
          </div>
        </div>

        <!-- ── Password ── -->
        <div style="border-top:1px solid #e2e8f0;padding-top:16px;margin-top:16px;max-width:300px;">
          <label style="display:block;font-size:13px;font-weight:600;margin-bottom:4px;">Password</label>
          <input type="password" id="create_password" placeholder="Default: Customer@123"
                 style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;">
        </div>

        <!-- ── Action buttons ── -->
        <div style="border-top:1px solid #e2e8f0;padding-top:16px;margin-top:16px;display:flex;gap:12px;justify-content:flex-end;">
          <button id="create_cancel_btn" type="button"
                  style="padding:10px 24px;font-size:14px;font-weight:600;color:#991b1b;
                         background-color:#fee2e2;border:2px solid #991b1b;border-radius:8px;
                         cursor:pointer;transition:all 0.2s;">
            Cancel
          </button>
          <button id="create_save_btn" type="button"
                  style="padding:10px 24px;font-size:14px;font-weight:600;color:#1e3a8a;
                         background-color:#e0e7ff;border:2px solid #1e3a8a;border-radius:8px;
                         cursor:pointer;transition:all 0.2s;">
            Create Customer
          </button>
        </div>
      </form>`,
      width: '48rem',
      showConfirmButton: false,
      showCancelButton: false,
      customClass: {
        popup: 'swal2-popup-high-z'
      },
      didOpen: () => {
        populateDatalist('create_region_list', regions, 'region');

        document.getElementById('create_cancel_btn').addEventListener('click', () => Swal.close());
        document.getElementById('create_save_btn').addEventListener('click', () => {
          const first = document.getElementById('create_first_name').value.trim();
          const last = document.getElementById('create_last_name').value.trim();
          const email = document.getElementById('create_email').value.trim();
          if (!first || !last || !email) {
            Swal.showValidationMessage('First name, last name and email are required');
            return;
          }

          const fd = new FormData();
          fd.append('first_name', first);
          fd.append('last_name', last);
          fd.append('email', email);
          fd.append('phone_number', document.getElementById('create_phone_number').value.trim());
          fd.append('region_code', document.getElementById('create_region_code').dataset.code || '');
          fd.append('province_code', document.getElementById('create_province_code').dataset.code || '');
          fd.append('city_municipality_code', document.getElementById('create_city_code').dataset.code || '');
          fd.append('barangay_code', document.getElementById('create_barangay_code').dataset.code || '');
          fd.append('address_line1', document.getElementById('create_address_line1').value.trim());
          fd.append('address_line2', document.getElementById('create_address_line2').value.trim());
          fd.append('postal_code', document.getElementById('create_postal_code').value.trim());
          fd.append('password', document.getElementById('create_password').value || '');

          if (croppedImageData) {
            fd.append('profile_photo', new File([croppedImageData], 'cropped_photo.jpg', {
              type: 'image/jpeg'
            }));
            croppedImageData = null;
          } else {
            const raw = document.getElementById('create_photo');
            if (raw && raw.files[0]) fd.append('profile_photo', raw.files[0]);
          }

          Swal.close();
          Swal.fire({
            title: 'Creating…',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading(),
            customClass: {
              popup: 'swal2-popup-high-z'
            }
          });

          fd.append('csrf_token', csrfToken);
          fetch('../api/customers.php?action=create', {
              method: 'POST',
              body: fd
            })
            .then(r => r.json())
            .then(data => {
              if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Created!',
                    text: data.message,
                    confirmButtonColor: '#3085d6',
                    customClass: {
                      popup: 'swal2-popup-high-z'
                    }
                  })
                  .then(() => fetchCustomers());
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
            .catch(() => {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while creating the customer.',
                confirmButtonColor: '#d33',
                customClass: {
                  popup: 'swal2-popup-high-z'
                }
              });
            });
        });
      },
    });
  }

  /* ─── DELETE ──────────────────────────────────────────────────────────── */

  function deleteCustomer(userId, customerName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to delete: ${customerName}`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
      customClass: {
        popup: 'swal2-popup-high-z'
      },
    }).then((result) => {
      if (!result.isConfirmed) return;

      Swal.fire({
        title: 'Deleting…',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
        customClass: {
          popup: 'swal2-popup-high-z'
        }
      });

      fetch('../api/customers.php?action=delete', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            user_id: userId,
            csrf_token: csrfToken
          }),
        })
        .then(r => r.json())
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
              })
              .then(() => fetchCustomers());
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
        .catch(() => {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while deleting the customer.',
            confirmButtonColor: '#d33',
            customClass: {
              popup: 'swal2-popup-high-z'
            }
          });
        });
    });
  }

  /* ─── PHOTO ───────────────────────────────────────────────────────────── */

  function previewPhoto(input, previewId) {
    if (!input.files || !input.files[0]) return;

    currentPreviewId = previewId;
    const reader = new FileReader();
    reader.onload = e => {
      const cropImg = document.getElementById('cropperImage');
      cropImg.src = e.target.result;

      // Show modal
      document.getElementById('cropperModal').style.display = 'flex';

      // Initialize cropper
      if (cropper) {
        cropper.destroy();
      }
      cropper = new Cropper(cropImg, {
        aspectRatio: 1,
        viewMode: 1,
        dragMode: 'move',
        autoCropArea: 0.8,
        guides: true,
        center: true,
        cropBoxMovable: true,
        cropBoxResizable: true,
        toggleDragModeOnDblclick: false
      });
    };
    reader.readAsDataURL(input.files[0]);
  }

  function applyCrop() {
    if (!cropper || !currentPreviewId) return;

    cropper.getCroppedCanvas({
      width: 200,
      height: 200
    }).toBlob(blob => {
      croppedImageData = blob;

      const preview = document.getElementById(currentPreviewId);
      const reader = new FileReader();
      reader.onload = e => {
        preview.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;" alt="Cropped photo">`;
      };
      reader.readAsDataURL(blob);

      cropper.destroy();
      cropper = null;
      document.getElementById('cropperModal').style.display = 'none';
      currentPreviewId = null;
    }, 'image/jpeg', 0.9);
  }

  function cancelCrop() {
    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
    document.getElementById('cropperModal').style.display = 'none';

    const fileInput = document.getElementById(currentPreviewId ? currentPreviewId.replace('_preview', '') : '');
    if (fileInput) fileInput.value = '';
    currentPreviewId = null;
  }

  function removePhoto(previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = `<img src="<?php echo dirname(dirname($_SERVER['PHP_SELF'])) . '/uploads/profile_photos/default_profile.jpg'; ?>" style="width:100%;height:100%;object-fit:cover;" alt="Default profile">`;

    const removeInput = document.getElementById('edit_remove_photo');
    if (removeInput) removeInput.value = '1';

    const fileInput = document.getElementById('edit_photo');
    if (fileInput) fileInput.value = '';

    croppedImageData = null;
  }

  /* ─── GEOCODE ─────────────────────────────────────────────────────────── */

  async function fetchRegions() {
    if (regionsData.length > 0) return regionsData;
    try {
      const res = await fetch('../api/geocode.php?action=regions');
      const json = await res.json();
      if (json.success) {
        regionsData = json.data;
        return regionsData;
      }
    } catch (e) {
      console.error('fetchRegions:', e);
    }
    return [];
  }

  async function fetchProvinces(regionCode) {
    try {
      const res = await fetch(`../api/geocode.php?action=provinces&region_code=${regionCode}`);
      const json = await res.json();
      if (json.success) return json.data;
    } catch (e) {
      console.error('fetchProvinces:', e);
    }
    return [];
  }

  async function fetchCities(provinceCode) {
    try {
      const res = await fetch(`../api/geocode.php?action=cities&province_code=${provinceCode}`);
      const json = await res.json();
      if (json.success) return json.data;
    } catch (e) {
      console.error('fetchCities:', e);
    }
    return [];
  }

  async function fetchBarangays(cityCode) {
    try {
      const res = await fetch(`../api/geocode.php?action=barangays&city_municipality_code=${cityCode}`);
      const json = await res.json();
      if (json.success) return json.data;
    } catch (e) {
      console.error('fetchBarangays:', e);
    }
    return [];
  }

  /**
   * populateDatalist
   *
   * FIX: datalist <option> elements now use value = display name so the browser
   * shows human-readable text in the dropdown and in the input after selection.
   * The geo-code is stored in a separate data-code attribute on the <option>
   * so we can look it up when the user makes a selection.
   *
   * @param {string} datalistId   - id of the <datalist> element
   * @param {Array}  options      - array from API (region/province/city/barangay objects)
   * @param {string} type         - 'region' | 'province' | 'city' | 'barangay'
   * @param {string} [defaultCode] - pre-select this geo-code
   */
  function populateDatalist(datalistId, options, type, defaultCode = null) {
    const datalist = document.getElementById(datalistId);
    if (!datalist) return;

    const codeKey = {
      region: 'region_code',
      province: 'province_code',
      city: 'city_municipality_code',
      barangay: 'barangay_code',
    } [type];

    const nameKey = {
      region: 'region_name',
      province: 'province_name',
      city: 'city_municipality_name',
      barangay: 'barangay_name',
    } [type];

    datalist.innerHTML = '';
    options.forEach(opt => {
      const el = document.createElement('option');
      el.value = opt[nameKey]; // ← display name as value so input shows readable text
      el.dataset.code = opt[codeKey]; // ← actual geo-code stored here
      datalist.appendChild(el);
    });

    /* Pre-fill the linked input when editing an existing customer */
    if (defaultCode) {
      const prefix = datalistId.replace('_list', '').replace(/_region|_province|_city|_barangay/, '');
      const inputId = datalistId
        .replace('_region_list', '_region_code')
        .replace('_province_list', '_province_code')
        .replace('_city_list', '_city_code')
        .replace('_barangay_list', '_barangay_code');

      const input = document.getElementById(inputId);
      if (input) {
        const match = options.find(o => o[codeKey] === defaultCode);
        if (match) {
          input.value = match[nameKey];
          input.dataset.code = defaultCode;
        }
      }
    }
  }

  /**
   * resolveCode — given an input element and its associated datalist,
   * return the geo-code for whatever the user has typed/selected.
   * Checks dataset.code first; if the display text changed, looks up a fresh match.
   */
  function resolveCode(input, datalist, nameKey, codeKey) {
    if (!datalist) return input.dataset.code || '';

    const typed = input.value.trim().toLowerCase();
    const match = Array.from(datalist.options).find(o => o.value.toLowerCase() === typed);
    if (match) {
      input.dataset.code = match.dataset.code;
      return match.dataset.code;
    }
    /* User typed something that doesn't match any option — clear the stored code */
    input.dataset.code = '';
    return '';
  }

  /* Cascade handlers — prefix is 'edit' or 'create' */

  async function onRegionChange(prefix) {
    const regionInput = document.getElementById(`${prefix}_region_code`);
    const regionDatalist = document.getElementById(`${prefix}_region_list`);
    const regionCode = resolveCode(regionInput, regionDatalist, 'region_name', 'region_code');

    /* Clear downstream */
    clearGeoInput(`${prefix}_province_code`, `${prefix}_province_list`);
    clearGeoInput(`${prefix}_city_code`, `${prefix}_city_list`);
    clearGeoInput(`${prefix}_barangay_code`, `${prefix}_barangay_list`);

    if (!regionCode) return;
    const provinces = await fetchProvinces(regionCode);
    populateDatalist(`${prefix}_province_list`, provinces, 'province');
  }

  async function onProvinceChange(prefix) {
    const provinceInput = document.getElementById(`${prefix}_province_code`);
    const provinceDatalist = document.getElementById(`${prefix}_province_list`);
    const provinceCode = resolveCode(provinceInput, provinceDatalist, 'province_name', 'province_code');

    clearGeoInput(`${prefix}_city_code`, `${prefix}_city_list`);
    clearGeoInput(`${prefix}_barangay_code`, `${prefix}_barangay_list`);

    if (!provinceCode) return;
    const cities = await fetchCities(provinceCode);
    populateDatalist(`${prefix}_city_list`, cities, 'city');
  }

  async function onCityChange(prefix) {
    const cityInput = document.getElementById(`${prefix}_city_code`);
    const cityDatalist = document.getElementById(`${prefix}_city_list`);
    const cityCode = resolveCode(cityInput, cityDatalist, 'city_municipality_name', 'city_municipality_code');

    clearGeoInput(`${prefix}_barangay_code`, `${prefix}_barangay_list`);

    if (!cityCode) return;
    const barangays = await fetchBarangays(cityCode);
    populateDatalist(`${prefix}_barangay_list`, barangays, 'barangay');
  }

  function onBarangayInput(prefix) {
    const barangayInput = document.getElementById(`${prefix}_barangay_code`);
    const barangayDatalist = document.getElementById(`${prefix}_barangay_list`);
    resolveCode(barangayInput, barangayDatalist, 'barangay_name', 'barangay_code');
  }

  function clearGeoInput(inputId, datalistId) {
    const input = document.getElementById(inputId);
    const datalist = document.getElementById(datalistId);
    if (input) {
      input.value = '';
      input.dataset.code = '';
    }
    if (datalist) datalist.innerHTML = '';
  }

  /* ─── HELPERS ─────────────────────────────────────────────────────────── */

  /** Escape HTML to avoid XSS when inserting customer data into innerHTML */
  function escHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  /* ─── TABLE FILTER ────────────────────────────────────────────────────── */

  function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;

    document.querySelectorAll('#customersTableBody .customer-row').forEach(row => {
      const text = row.textContent.toLowerCase();
      const statusEl = row.querySelector('td:nth-child(5) span');
      const rowStatus = statusEl ? statusEl.textContent.trim().toLowerCase() : '';

      row.style.display = (text.includes(search) && (!status || rowStatus === status)) ? '' : 'none';
    });
  }

  /* ─── INIT ────────────────────────────────────────────────────────────── */

  document.addEventListener('DOMContentLoaded', fetchCustomers);

  async function createCustomer() {
    const regions = await fetchRegions();

    Swal.fire({
      title: 'Add New Customer',
      html: `
        <form id="createCustomerForm" class="text-left">
          <div class="grid grid-cols-2 gap-3">
            <div class="mb-3">
              <label class="block text-sm font-semibold mb-1">First Name</label>
              <input type="text" id="create_first_name" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-3">
              <label class="block text-sm font-semibold mb-1">Last Name</label>
              <input type="text" id="create_last_name" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-3">
              <label class="block text-sm font-semibold mb-1">Email</label>
              <input type="email" id="create_email" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-3">
              <label class="block text-sm font-semibold mb-1">Phone Number</label>
              <input type="text" id="create_phone_number" class="w-full px-3 py-2 border rounded-lg">
            </div>
          </div>
          
          <div class="border-t pt-4 mt-4">
            <h4 class="text-sm font-semibold mb-3 flex items-center gap-2">
              <i class="fas fa-map-marker-alt text-slate-400"></i> Address
            </h4>
            <div class="grid grid-cols-2 gap-3">
              <div class="mb-3">
                <label class="block text-xs font-semibold mb-1">Region</label>
                <input type="text" id="create_region_code" list="create_region_list" placeholder="Select or search region..." class="w-full px-3 py-2 border rounded-lg" onchange="onRegionChange('create_region_code')">
                <datalist id="create_region_list"></datalist>
              </div>
              <div class="mb-3">
                <label class="block text-xs font-semibold mb-1">Province</label>
                <input type="text" id="create_province_code" list="create_province_list" placeholder="Select or search province..." class="w-full px-3 py-2 border rounded-lg" onchange="onProvinceChange('create_province_code')">
                <datalist id="create_province_list"></datalist>
              </div>
              <div class="mb-3">
                <label class="block text-xs font-semibold mb-1">City/Municipality</label>
                <input type="text" id="create_city_municipality_code" list="create_city_list" placeholder="Select or search city..." class="w-full px-3 py-2 border rounded-lg" onchange="onCityChange('create_city_municipality_code')">
                <datalist id="create_city_list"></datalist>
              </div>
              <div class="mb-3">
                <label class="block text-xs font-semibold mb-1">Barangay</label>
                <input type="text" id="create_barangay_code" list="create_barangay_list" placeholder="Select or search barangay..." class="w-full px-3 py-2 border rounded-lg">
                <datalist id="create_barangay_list"></datalist>
              </div>
              <div class="mb-3 col-span-2">
                <label class="block text-xs font-semibold mb-1">Address Line 1</label>
                <input type="text" id="create_address_line1" class="w-full px-3 py-2 border rounded-lg" placeholder="House/Unit number, Street name">
              </div>
              <div class="mb-3">
                <label class="block text-xs font-semibold mb-1">Address Line 2</label>
                <input type="text" id="create_address_line2" class="w-full px-3 py-2 border rounded-lg" placeholder="Building, Subdivision, etc.">
              </div>
              <div class="mb-3">
                <label class="block text-xs font-semibold mb-1">Postal Code</label>
                <input type="text" id="create_postal_code" class="w-full px-3 py-2 border rounded-lg" placeholder="Zip code">
              </div>
            </div>
          </div>
          
          <div class="border-t pt-4 mt-4">
            <div class="mb-3">
              <label class="block text-sm font-semibold mb-1">Password</label>
              <input type="password" id="create_password" placeholder="Default: Customer@123" class="w-full px-3 py-2 border rounded-lg">
            </div>
          </div>
        </form>
      `,
      width: '56rem',
      didOpen: () => {
        populateDatalist('create_region_list', regions);
      },
      confirmButtonText: 'Create Customer',
      showCancelButton: true,
      cancelButtonText: 'Cancel',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      customClass: {
        popup: 'swal2-popup-high-z'
      },
      preConfirm: () => {
        const firstName = document.getElementById('create_first_name').value;
        const lastName = document.getElementById('create_last_name').value;
        const email = document.getElementById('create_email').value;
        if (!firstName || !lastName || !email) {
          Swal.showValidationMessage('Please fill in required fields');
          return false;
        }
      }
    }).then((result) => {
      if (result.isConfirmed) {
        const formData = {
          first_name: document.getElementById('create_first_name').value,
          last_name: document.getElementById('create_last_name').value,
          email: document.getElementById('create_email').value,
          phone_number: document.getElementById('create_phone_number').value,
          region_code: document.getElementById('create_region_code').dataset.code || document.getElementById('create_region_code').value || null,
          province_code: document.getElementById('create_province_code').dataset.code || document.getElementById('create_province_code').value || null,
          city_municipality_code: document.getElementById('create_city_municipality_code').dataset.code || document.getElementById('create_city_municipality_code').value || null,
          barangay_code: document.getElementById('create_barangay_code').dataset.code || document.getElementById('create_barangay_code').value || null,
          address_line1: document.getElementById('create_address_line1').value,
          address_line2: document.getElementById('create_address_line2').value,
          postal_code: document.getElementById('create_postal_code').value,
          password: document.getElementById('create_password').value || null
        };

        Swal.fire({
          title: 'Creating...',
          text: 'Please wait while we create the customer.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });

        fetch('../api/customers.php?action=create', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              ...formData,
              csrf_token: csrfToken
            })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({
                  icon: 'success',
                  title: 'Created!',
                  text: data.message,
                  confirmButtonColor: '#3085d6',
                  customClass: {
                    popup: 'swal2-popup-high-z'
                  }
                })
                .then(() => fetchCustomers());
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
          .catch(() => {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'An error occurred while creating the customer.',
              confirmButtonColor: '#d33',
              customClass: {
                popup: 'swal2-popup-high-z'
              }
            });
          });
      }
    });
  }

  function deleteCustomer(userId, customerName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to delete the customer: ${customerName}`,
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
        Swal.fire({
          title: 'Deleting...',
          text: 'Please wait while we delete the customer.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
          customClass: {
            popup: 'swal2-popup-high-z'
          }
        });

        fetch('../api/customers.php?action=delete', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              user_id: userId,
              csrf_token: csrfToken
            })
          })
          .then(r => r.json())
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
                })
                .then(() => fetchCustomers());
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
          .catch(() => {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'An error occurred while deleting the customer.',
              confirmButtonColor: '#d33',
              customClass: {
                popup: 'swal2-popup-high-z'
              }
            });
          });
      }
    });
  }
</script>

<script>
  function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;

    const rows = document.querySelectorAll('#customersTableBody .customer-row');
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      const statusCell = row.querySelector('td:nth-child(6) span');
      const rowStatus = statusCell ? statusCell.textContent.trim().toLowerCase() : '';

      const matchesSearch = text.includes(searchTerm);
      const matchesStatus = statusFilter === '' || rowStatus === statusFilter;

      row.style.display = matchesSearch && matchesStatus ? '' : 'none';
    });
  }

  function openAddCustomerModal() {
    // Implement add customer modal
    Swal.fire({
      title: 'Add New Customer',
      text: 'Add customer functionality will be implemented here',
      icon: 'info',
      confirmButtonText: 'OK'
    });
  }
</script>