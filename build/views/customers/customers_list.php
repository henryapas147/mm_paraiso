<?php if (false) {
} ?>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Lora:ital,wght@1,500;1,600&display=swap" rel="stylesheet">

<style>
  body {
    font-family: 'DM Sans', sans-serif;
  }

  .page-title {
    font-family: 'Lora', serif;
    font-style: italic;
    font-size: 22px;
    font-weight: 500;
    margin: 0;
  }

  /* Stats Cards */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }

  @media (min-width: 640px) {
    .stats-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (min-width: 1024px) {
    .stats-grid {
      grid-template-columns: repeat(3, 1fr);
    }
  }

  .stat-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .stat-info {
    display: flex;
    flex-direction: column;
  }

  .stat-label {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
    text-transform: uppercase;
    margin-bottom: 4px;
  }

  .stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #0f172a;
  }

  .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #fff;
  }

  .stat-icon.blue {
    background: #3b82f6;
  }

  .stat-icon.green {
    background: #10b981;
  }

  .stat-icon.orange {
    background: #f59e0b;
  }

  /* Table Card */
  .table-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
  }

  .table-header {
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  @media (min-width: 768px) {
    .table-header {
      flex-direction: row;
      align-items: center;
      justify-content: space-between;
    }
  }

  .controls-container {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
  }

  .search-input {
    padding: 8px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    min-width: 200px;
  }

  .filter-select {
    padding: 8px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    outline: none;
  }

  .btn {
    padding: 8px 18px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
  }

  .btn-primary {
    background: #334155;
    color: #fff;
  }

  .btn-primary:hover {
    background: #1e293b;
  }

  .btn-secondary {
    background: #e2e8f0;
    color: #334155;
  }

  .btn-secondary:hover {
    background: #cbd5e1;
  }

  .btn-danger {
    background: #fee2e2;
    color: #991b1b;
  }

  .btn-danger:hover {
    background: #fecaca;
  }

  .btn-sm {
    padding: 6px 12px;
    font-size: 13px;
  }

  /* Table */
  .data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
  }

  .data-table th {
    padding: 14px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    border-bottom: 1px solid #e2e8f0;
  }

  .data-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #e2e8f0;
  }

  .data-table tr:hover {
    background: #f8fafc;
  }

  .customer-row {
    transition: background 0.2s;
  }

  /* Customer Cell */
  .customer-cell {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .customer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e2e8f0;
  }

  .customer-avatar-initial {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
    color: #fff;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
  }

  .customer-name {
    font-weight: 600;
    color: #0f172a;
  }

  .customer-email {
    font-size: 12px;
    color: #64748b;
  }

  /* Badges */
  .badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
  }

  .badge-status-active {
    background: #dcfce7;
    color: #166534;
  }

  .badge-status-inactive {
    background: #f1f5f9;
    color: #475569;
  }

  .badge-status-suspended {
    background: #fee2e2;
    color: #991b1b;
  }

  .badge-status-pending {
    background: #fef3c7;
    color: #92400e;
  }

  /* Actions */
  .actions-cell {
    display: flex;
    gap: 8px;
    justify-content: center;
  }

  .action-btn {
    padding: 6px 12px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 6px;
    border: 1px solid;
    cursor: pointer;
    transition: all 0.2s;
  }

  .action-btn.view {
    background: #eff6ff;
    color: #1d4ed8;
    border-color: #bfdbfe;
  }

  .action-btn.view:hover {
    background: #dbeafe;
  }

  .action-btn.edit {
    background: #fffbeb;
    color: #b45309;
    border-color: #fde68a;
  }

  .action-btn.edit:hover {
    background: #fef3c7;
  }

  .action-btn.delete {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fecaca;
  }

  .action-btn.delete:hover {
    background: #fee2e2;
  }

  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 40px;
    color: #94a3b8;
  }

  .empty-state i {
    font-size: 48px;
    margin-bottom: 12px;
  }

  /* Modal Styles */
  .modal-title {
    font-family: 'Lora', serif;
    font-style: italic;
    font-size: 18px;
  }

  .info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }

  .info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .info-label {
    font-size: 12px;
    color: #64748b;
    font-weight: 500;
    text-transform: uppercase;
  }

  .info-value {
    font-size: 14px;
    color: #0f172a;
    font-weight: 500;
  }

  .form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .form-label {
    font-size: 13px;
    color: #374151;
    font-weight: 500;
  }

  .form-input,
  .form-select {
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    width: 100%;
    box-sizing: border-box;
  }

  .form-input:focus,
  .form-select:focus {
    border-color: #3b82f6;
  }

  /* Photo Preview */
  .photo-preview {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
  }

  .photo-preview img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e2e8f0;
  }

  .photo-actions {
    display: flex;
    gap: 8px;
  }

  .photo-actions label {
    cursor: pointer;
    font-size: 12px;
    color: #4f46e5;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .photo-actions input {
    display: none;
  }

  .photo-actions button {
    font-size: 12px;
    color: #dc2626;
    background: none;
    border: none;
    cursor: pointer;
  }

  /* Cropper Modal */
  .cropper-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 9999;
    align-items: center;
    justify-content: center;
  }

  .cropper-modal {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    max-width: 90%;
    max-height: 90%;
    overflow: auto;
  }

  .cropper-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
    margin-top: 16px;
  }

  /* Responsive */
  @media (max-width: 640px) {
    .stats-grid {
      gap: 12px
    }
  }

  @media (max-width: 640px) {
    .stat-card {
      padding: 16px
    }
  }

  @media (max-width: 640px) {
    .stat-label {
      font-size: 11px
    }
  }

  @media (max-width: 640px) {
    .stat-value {
      font-size: 20px
    }
  }

  @media (max-width: 640px) {
    .stat-icon {
      width: 40px;
      height: 40px;
      font-size: 18px
    }
  }

  @media (max-width: 640px) {
    .table-header {
      padding: 14px 16px 12px
    }
  }

  @media (max-width: 640px) {
    .controls-container {
      flex-direction: column;
      width: 100%
    }
  }

  @media (max-width: 640px) {
    .search-input {
      width: 100%;
      min-width: auto
    }
  }

  @media (max-width: 640px) {
    .filter-select {
      width: 100%
    }
  }

  @media (max-width: 640px) {
    .btn {
      font-size: 12px;
      padding: 6px 14px
    }
  }

  @media (max-width: 640px) {
    .data-table th {
      padding: 8px 12px;
      font-size: 10px
    }
  }

  @media (max-width: 640px) {
    .data-table td {
      padding: 10px 12px;
      font-size: 12px
    }
  }

  @media (max-width: 640px) {
    .customer-avatar, .customer-avatar-initial {
      width: 32px;
      height: 32px;
      font-size: 14px
    }
  }

  @media (max-width: 640px) {
    .customer-name {
      font-size: 13px
    }
  }

  @media (max-width: 640px) {
    .customer-email {
      font-size: 11px
    }
  }

  @media (max-width: 640px) {
    .badge {
      padding: 3px 8px;
      font-size: 10px
    }
  }

  @media (max-width: 640px) {
    .actions-cell {
      flex-direction: column
    }
  }

  @media (max-width: 640px) {
    .action-btn {
      width: 100%;
      font-size: 11px
    }
  }

  @media (max-width: 640px) {
    .empty-state {
      padding: 32px 16px
    }
  }

  @media (max-width: 640px) {
    .empty-state i {
      font-size: 36px
    }
  }

  @media (max-width: 640px) {
    .info-grid {
      grid-template-columns: 1fr
    }
  }

  @media (max-width: 640px) {
    .info-label {
      font-size: 11px
    }
  }

  @media (max-width: 640px) {
    .info-value {
      font-size: 13px
    }
  }

  @media (max-width: 640px) {
    .form-grid {
      grid-template-columns: 1fr
    }
  }

  @media (max-width: 640px) {
    .form-label {
      font-size: 12px
    }
  }

  @media (max-width: 640px) {
    .form-input, .form-select {
      padding: 6px 10px;
      font-size: 12px
    }
  }

  @media (max-width: 640px) {
    .photo-preview img {
      width: 64px;
      height: 64px
    }
  }

  @media (max-width: 640px) {
    .cropper-modal {
      padding: 16px
    }
  }

  @media (max-width: 480px) {
    .stats-grid {
      gap: 10px
    }
  }

  @media (max-width: 480px) {
    .stat-card {
      padding: 14px
    }
  }

  @media (max-width: 480px) {
    .stat-value {
      font-size: 18px
    }
  }

  @media (max-width: 480px) {
    .stat-icon {
      width: 36px;
      height: 36px;
      font-size: 16px
    }
  }

  @media (max-width: 480px) {
    .table-header {
      padding: 12px 14px 10px
    }
  }

  @media (max-width: 480px) {
    .btn {
      font-size: 11px;
      padding: 5px 12px
    }
  }

  @media (max-width: 480px) {
    .data-table th {
      padding: 6px 10px;
      font-size: 9px
    }
  }

  @media (max-width: 480px) {
    .data-table td {
      padding: 8px 10px;
      font-size: 11px
    }
  }

  @media (max-width: 480px) {
    .customer-avatar, .customer-avatar-initial {
      width: 28px;
      height: 28px;
      font-size: 12px
    }
  }

  @media (max-width: 480px) {
    .badge {
      padding: 2px 6px;
      font-size: 9px
    }
  }

  @media (max-width: 480px) {
    .form-input, .form-select {
      font-size: 11px
    }
  }

  @media (max-width: 480px) {
    .photo-preview img {
      width: 56px;
      height: 56px
    }
  }
</style>

<!-- Stats Cards -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-info">
      <span class="stat-label">Total Customers</span>
      <span class="stat-value" id="statTotal">0</span>
    </div>
    <div class="stat-icon blue">
      <i class="fas fa-users"></i>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-info">
      <span class="stat-label">Active Customers</span>
      <span class="stat-value" id="statActive">0</span>
    </div>
    <div class="stat-icon green">
      <i class="fas fa-check-circle"></i>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-info">
      <span class="stat-label">Verified</span>
      <span class="stat-value" id="statVerified">0</span>
    </div>
    <div class="stat-icon orange">
      <i class="fas fa-envelope-circle-check"></i>
    </div>
  </div>
</div>

<!-- Customers Table -->
<div class="table-card">
  <div class="table-header">
    <h6 class="page-title">Customers List</h6>
    <div class="controls-container">
      <input type="text" id="searchInput" class="search-input" placeholder="Search customers..." onkeyup="filterTable()">
      <select id="statusFilter" class="filter-select" onchange="filterTable()">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
        <option value="suspended">Suspended</option>
        <option value="pending">Pending</option>
      </select>
      <button class="btn btn-primary" onclick="createCustomer()">
        <i class="fas fa-plus"></i> Add New Customer
      </button>
    </div>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>Customer</th>
        <th>Phone</th>
        <th>City</th>
        <th>Created</th>
        <th>Status</th>
        <th style="text-align: center;">Actions</th>
      </tr>
    </thead>
    <tbody id="customersTableBody">
      <tr>
        <td colspan="6" class="empty-state">
          <i class="fas fa-spinner fa-spin"></i> Loading customers...
        </td>
      </tr>
    </tbody>
  </table>
</div>

<!-- Cropper Modal -->
<div id="cropperModal" class="cropper-overlay">
  <div class="cropper-modal">
    <h3 class="modal-title" style="margin: 0 0 16px 0;">Crop Image</h3>
    <div>
      <img id="cropperImage" style="max-width: 100%; max-height: 500px; display: block;">
    </div>
    <div class="cropper-actions">
      <button class="btn btn-danger" onclick="cancelCrop()">Cancel</button>
      <button class="btn btn-primary" onclick="applyCrop()">Apply Crop</button>
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

  const DEFAULT_PROFILE_PATH = '<?php echo dirname(dirname($_SERVER['PHP_SELF'])) . '/uploads/profile_photos/default_profile.jpg'; ?>';

  function normalizeProfilePhotoUrl(url) {
    if (!url) return DEFAULT_PROFILE_PATH;
    const isLocalhost = location.hostname === 'localhost' || location.hostname === '127.0.0.1';
    if (url.startsWith('http')) return url;
    if (isLocalhost) {
      if (url.startsWith('/../uploads/profile_photos/')) return url;
      if (url.startsWith('/uploads/profile_photos/')) return '/../uploads/profile_photos/' + url.split('/').pop();
      if (url.startsWith('/build/assets/images/uploads/profile_photos/')) return '/../uploads/profile_photos/' + url.split('/').pop();
    } else {
      if (url.startsWith('/uploads/profile_photos/')) return url;
      if (url.startsWith('/../uploads/profile_photos/')) return '/uploads/profile_photos/' + url.split('/').pop();
      if (url.startsWith('/build/assets/images/uploads/profile_photos/')) return '/uploads/profile_photos/' + url.split('/').pop();
    }
    return url;
  }

  function getPhotoPreviewHtml(photoUrl, previewId, showRemove = false) {
    const normalizedUrl = normalizeProfilePhotoUrl(photoUrl);
    const imgContent = photoUrl ?
      `<img src="${normalizedUrl}" style="width:100%;height:100%;object-fit:cover;" alt="Profile photo" onerror="this.src='${DEFAULT_PROFILE_PATH}'">` :
      `<img src="${DEFAULT_PROFILE_PATH}" style="width:100%;height:100%;object-fit:cover;" alt="Profile photo">`;

    const removeButton = showRemove && photoUrl ?
      `<button type="button" onclick="removePhoto('${previewId}')"><i class="fas fa-trash"></i> Remove</button>` :
      '';

    return `
      <div class="photo-preview">
        <div id="${previewId}" style="width:80px;height:80px;border-radius:50%;overflow:hidden;border:3px solid #e2e8f0;background:#f1f5f9;">
          ${imgContent}
        </div>
        <div class="photo-actions">
          <label><i class="fas fa-camera"></i> ${showRemove ? 'Change' : 'Upload'}
            <input type="file" id="${previewId.replace('_preview', '')}" accept="image/*" onchange="previewPhoto(this, '${previewId}')">
          </label>
          ${removeButton}
        </div>
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

  function getStatusBadgeClass(status) {
    const classes = {
      'active': 'badge-status-active',
      'inactive': 'badge-status-inactive',
      'suspended': 'badge-status-suspended',
      'pending': 'badge-status-pending',
      'pending_verification': 'badge-status-pending'
    };
    return classes[status] || 'badge-status-inactive';
  }

  function renderTable() {
    const tbody = document.getElementById('customersTableBody');
    if (!customersData.length) {
      tbody.innerHTML = `<tr><td colspan="6" class="empty-state"><i class="fas fa-inbox"></i><div>No customers found.</div></td></tr>`;
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
      const statusClass = getStatusBadgeClass(customer.account_status);
      const displayStatus = customer.account_status === 'pending_verification' ? 'pending' : (customer.account_status || 'unknown');

      return `
      <tr class="customer-row">
        <td>
          <div class="customer-cell">
            ${customer.profile_photo_url
              ? `<img src="${normalizeProfilePhotoUrl(customer.profile_photo_url)}" class="customer-avatar" alt="${customer.first_name}" onerror="this.onerror=null;this.src='${DEFAULT_PROFILE_PATH}'">`
              : `<div class="customer-avatar-initial">${customer.first_name ? customer.first_name.charAt(0).toUpperCase() : '?'}</div>`
            }
            <div>
              <div class="customer-name">${customer.first_name || ''} ${customer.last_name || ''}</div>
              <div class="customer-email">${customer.email}</div>
            </div>
          </div>
        </td>
        <td>
          <div class="customer-name">${customer.phone_number || '-'}</div>
        </td>
        <td>
          <div class="customer-name" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${addressDisplay}">${addressDisplay}</div>
        </td>
        <td>
          <div class="customer-name">
            ${new Date(customer.created_at).toLocaleString('en-US', { month:'short', day:'numeric', year:'numeric', hour:'numeric', minute:'2-digit', hour12:true })}
          </div>
        </td>
        <td>
          <span class="badge ${statusClass}">${displayStatus}</span>
        </td>
        <td>
          <div class="actions-cell">
            <button onclick="viewCustomer('${customer.user_id}')" class="action-btn view">
              <i class="fas fa-eye"></i> View
            </button>
            <button onclick="editCustomer('${customer.user_id}')" class="action-btn edit">
              <i class="fas fa-edit"></i> Edit
            </button>
            <button onclick="deleteCustomer('${customer.user_id}', '${customer.first_name || ''} ${customer.last_name || ''}')" class="action-btn delete">
              <i class="fas fa-trash-alt"></i> Delete
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
        confirmButtonText: 'OK',
        customClass: {
          confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
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

    const photoUrl = normalizeProfilePhotoUrl(customer.profile_photo_url);
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
    const statusClass = getStatusBadgeClass(customer.account_status);

    Swal.fire({
      title: 'Customer Details',
      html: `
        <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 24px;">
          <img src="${photoUrl}" alt="" style="width: 96px; height: 96px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 12px; display: block; margin-left: auto; margin-right: auto;" onerror="this.src='${DEFAULT_PROFILE_PATH}'">
          <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 4px; text-align: center;">${customer.first_name || ''} ${customer.last_name || ''}</h3>
          <p style="font-size: 13px; color: #666; margin-bottom: 12px; text-align: center;">${customer.email}</p>
          <span class="badge ${statusClass}">${customer.account_status || 'unknown'}</span>
        </div>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">Phone</div>
            <div class="info-value">${customer.phone_number || '-'}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Email Verified</div>
            <div class="info-value" style="color: ${customer.is_email_verified ? '#059669' : '#DC2626'}">${customer.is_email_verified ? 'Yes' : 'No'}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Phone Verified</div>
            <div class="info-value" style="color: ${customer.is_phone_verified ? '#059669' : '#DC2626'}">${customer.is_phone_verified ? 'Yes' : 'No'}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Created</div>
            <div class="info-value">${new Date(customer.created_at).toLocaleDateString()}</div>
          </div>
        </div>
        <div class="info-item" style="margin-top: 16px;">
          <div class="info-label">Address</div>
          <div class="info-value">${fullAddress}</div>
        </div>
        ${customer.last_login_at ? `
        <div class="info-item" style="margin-top: 12px;">
          <div class="info-label">Last Login</div>
          <div class="info-value">${new Date(customer.last_login_at).toLocaleString()}</div>
        </div>` : ''}
      `,
      width: '480px',
      confirmButtonText: 'Close',
      customClass: {
        confirmButton: 'btn btn-primary'
      },
      buttonsStyling: false
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
            confirmButtonText: 'OK',
            customClass: {
              confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
          });
        }

        const customer = data.data;
        const photoUrl = normalizeProfilePhotoUrl(customer.profile_photo_url);

        Swal.fire({
          title: 'Edit Customer',
          html: `
            <form id="editCustomerForm" style="text-align: left;">
              <!-- Profile Photo Section -->
              <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e2e8f0;">
                ${getPhotoPreviewHtml(photoUrl, 'edit_photo_preview', true)}
                <input type="hidden" id="edit_remove_photo" value="0">
              </div>

              <!-- Personal Info Section -->
              <div class="form-grid" style="margin-bottom: 20px;">
                <div class="form-group">
                  <label class="form-label">First Name *</label>
                  <input type="text" id="edit_first_name" class="form-input" value="${escHtml(customer.first_name || '')}" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Last Name *</label>
                  <input type="text" id="edit_last_name" class="form-input" value="${escHtml(customer.last_name || '')}" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Email *</label>
                  <input type="email" id="edit_email" class="form-input" value="${escHtml(customer.email || '')}" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Phone Number</label>
                  <input type="text" id="edit_phone_number" class="form-input" value="${escHtml(customer.phone_number || '')}">
                </div>
              </div>

              <div style="border-top: 1px solid #e2e8f0; padding-top: 16px; margin-top: 16px;">
                <p style="font-size: 13px; font-weight: 600; margin: 0 0 12px;">
                  <i class="fas fa-map-marker-alt" style="color: #94a3b8; margin-right: 6px;"></i> Address
                </p>
                <div class="form-grid">
                  <div class="form-group">
                    <label class="form-label">Region</label>
                    <input type="text" id="edit_region_code" list="edit_region_list" class="form-input" placeholder="Search region..." oninput="onRegionChange('edit')">
                    <datalist id="edit_region_list"></datalist>
                  </div>
                  <div class="form-group">
                    <label class="form-label">Province</label>
                    <input type="text" id="edit_province_code" list="edit_province_list" class="form-input" placeholder="Search province..." oninput="onProvinceChange('edit')">
                    <datalist id="edit_province_list"></datalist>
                  </div>
                  <div class="form-group">
                    <label class="form-label">City / Municipality</label>
                    <input type="text" id="edit_city_code" list="edit_city_list" class="form-input" placeholder="Search city..." oninput="onCityChange('edit')">
                    <datalist id="edit_city_list"></datalist>
                  </div>
                  <div class="form-group">
                    <label class="form-label">Barangay</label>
                    <input type="text" id="edit_barangay_code" list="edit_barangay_list" class="form-input" placeholder="Search barangay..." oninput="onBarangayInput('edit')">
                    <datalist id="edit_barangay_list"></datalist>
                  </div>
                  <div class="form-group" style="grid-column: 1/-1;">
                    <label class="form-label">Address Line 1</label>
                    <input type="text" id="edit_address_line1" class="form-input" value="${escHtml(customer.address_line1 || '')}" placeholder="House/Unit number, Street name">
                  </div>
                  <div class="form-group">
                    <label class="form-label">Address Line 2</label>
                    <input type="text" id="edit_address_line2" class="form-input" value="${escHtml(customer.address_line2 || '')}" placeholder="Building, Subdivision...">
                  </div>
                  <div class="form-group">
                    <label class="form-label">Postal Code</label>
                    <input type="text" id="edit_postal_code" class="form-input" value="${escHtml(customer.postal_code || '')}" placeholder="Zip code">
                  </div>
                </div>
              </div>

              <div style="border-top: 1px solid #e2e8f0; padding-top: 16px; margin-top: 16px;">
                <div class="form-group" style="max-width: 220px;">
                  <label class="form-label">Account Status</label>
                  <select id="edit_account_status" class="form-select">
                    <option value="active" ${customer.account_status === 'active' ? 'selected' : ''}>Active</option>
                    <option value="inactive" ${customer.account_status === 'inactive' ? 'selected' : ''}>Inactive</option>
                    <option value="suspended" ${customer.account_status === 'suspended' ? 'selected' : ''}>Suspended</option>
                  </select>
                </div>
              </div>

              <input type="hidden" id="edit_user_id" value="${customer.user_id}">

              <div style="border-top: 1px solid #e2e8f0; padding-top: 16px; margin-top: 16px; display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" class="btn btn-danger" id="edit_cancel_btn">Cancel</button>
                <button type="button" class="btn btn-primary" id="edit_save_btn">Save Changes</button>
              </div>
            </form>
          `,
          width: '600px',
          showConfirmButton: false,
          showCancelButton: false,
          didOpen: async () => {
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
              fd.append('first_name', first);
              fd.append('last_name', last);
              fd.append('email', email);
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

            populateDatalist('edit_region_list', regions, 'region', customer.region_code);

            if (customer.region_code) {
              const provinces = await fetchProvinces(customer.region_code);
              populateDatalist('edit_province_list', provinces, 'province', customer.province_code);

              if (customer.province_code) {
                const cities = await fetchCities(customer.province_code);
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
          confirmButtonText: 'OK',
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          buttonsStyling: false
        });
      });
  }

  function submitCustomerUpdate(formData) {
    formData.append('csrf_token', csrfToken);
    Swal.fire({
      title: 'Updating...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
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
            confirmButtonText: 'OK',
            customClass: {
              confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
          }).then(() => fetchCustomers());
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message,
            confirmButtonText: 'OK',
            customClass: {
              confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
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
        <form id="createCustomerForm" style="text-align: left;">
          <!-- Profile Photo Section -->
          <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e2e8f0;">
            ${getPhotoPreviewHtml('', 'create_photo_preview', false)}
          </div>

          <!-- Personal Info Section -->
          <div class="form-grid" style="margin-bottom: 20px;">
            <div class="form-group">
              <label class="form-label">First Name *</label>
              <input type="text" id="create_first_name" class="form-input" required>
            </div>
            <div class="form-group">
              <label class="form-label">Last Name *</label>
              <input type="text" id="create_last_name" class="form-input" required>
            </div>
            <div class="form-group">
              <label class="form-label">Email *</label>
              <input type="email" id="create_email" class="form-input" required>
            </div>
            <div class="form-group">
              <label class="form-label">Phone Number</label>
              <input type="text" id="create_phone_number" class="form-input">
            </div>
          </div>

          <div style="border-top: 1px solid #e2e8f0; padding-top: 16px; margin-top: 16px;">
            <p style="font-size: 13px; font-weight: 600; margin: 0 0 12px;">
              <i class="fas fa-map-marker-alt" style="color: #94a3b8; margin-right: 6px;"></i> Address
            </p>
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">Region</label>
                <input type="text" id="create_region_code" list="create_region_list" class="form-input" placeholder="Search region..." oninput="onRegionChange('create')">
                <datalist id="create_region_list"></datalist>
              </div>
              <div class="form-group">
                <label class="form-label">Province</label>
                <input type="text" id="create_province_code" list="create_province_list" class="form-input" placeholder="Search province..." oninput="onProvinceChange('create')">
                <datalist id="create_province_list"></datalist>
              </div>
              <div class="form-group">
                <label class="form-label">City / Municipality</label>
                <input type="text" id="create_city_code" list="create_city_list" class="form-input" placeholder="Search city..." oninput="onCityChange('create')">
                <datalist id="create_city_list"></datalist>
              </div>
              <div class="form-group">
                <label class="form-label">Barangay</label>
                <input type="text" id="create_barangay_code" list="create_barangay_list" class="form-input" placeholder="Search barangay..." oninput="onBarangayInput('create')">
                <datalist id="create_barangay_list"></datalist>
              </div>
              <div class="form-group" style="grid-column: 1/-1;">
                <label class="form-label">Address Line 1</label>
                <input type="text" id="create_address_line1" class="form-input" placeholder="House/Unit number, Street name">
              </div>
              <div class="form-group">
                <label class="form-label">Address Line 2</label>
                <input type="text" id="create_address_line2" class="form-input" placeholder="Building, Subdivision...">
              </div>
              <div class="form-group">
                <label class="form-label">Postal Code</label>
                <input type="text" id="create_postal_code" class="form-input" placeholder="Zip code">
              </div>
            </div>
          </div>

          <div style="border-top: 1px solid #e2e8f0; padding-top: 16px; margin-top: 16px;">
            <div class="form-group" style="max-width: 300px;">
              <label class="form-label">Password</label>
              <input type="password" id="create_password" class="form-input" placeholder="Default: Customer@123">
            </div>
          </div>

          <div style="border-top: 1px solid #e2e8f0; padding-top: 16px; margin-top: 16px; display: flex; gap: 12px; justify-content: flex-end;">
            <button type="button" class="btn btn-danger" id="create_cancel_btn">Cancel</button>
            <button type="button" class="btn btn-primary" id="create_save_btn">Create Customer</button>
          </div>
        </form>
      `,
      width: '600px',
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
            title: 'Creating...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
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
                  confirmButtonText: 'OK',
                  customClass: {
                    confirmButton: 'btn btn-primary'
                  },
                  buttonsStyling: false
                }).then(() => fetchCustomers());
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: data.message,
                  confirmButtonText: 'OK',
                  customClass: {
                    confirmButton: 'btn btn-primary'
                  },
                  buttonsStyling: false
                });
              }
            })
            .catch(() => {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while creating the customer.',
                confirmButtonText: 'OK',
                customClass: {
                  confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
              });
            });
        });
      }
    });
  }

  /* ─── DELETE ──────────────────────────────────────────────────────────── */

  function deleteCustomer(userId, customerName) {
    Swal.fire({
      title: 'Delete Customer',
      text: `Are you sure you want to delete ${customerName}? This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'btn btn-danger',
        cancelButton: 'btn btn-secondary'
      },
      buttonsStyling: false
    }).then((result) => {
      if (!result.isConfirmed) return;

      Swal.fire({
        title: 'Deleting...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
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
              confirmButtonText: 'OK',
              customClass: {
                confirmButton: 'btn btn-primary'
              },
              buttonsStyling: false
            }).then(() => fetchCustomers());
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.message,
              confirmButtonText: 'OK',
              customClass: {
                confirmButton: 'btn btn-primary'
              },
              buttonsStyling: false
            });
          }
        })
        .catch(() => {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while deleting the customer.',
            confirmButtonText: 'OK',
            customClass: {
              confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
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
</script>