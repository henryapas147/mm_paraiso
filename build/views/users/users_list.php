<?php
// Dynamic default profile image path based on environment
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$basePath = dirname($_SERVER['SCRIPT_NAME']);
$defaultProfilePath = $basePath . '/assets/images/default_profile.jpg';
?>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Lora:ital,wght@0,600;1,500&display=swap" rel="stylesheet">

<style>
  * { box-sizing: border-box; margin: 0; padding: 0 }

  body {
    font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
    background: #f5f5f5;
    color: #1a1a1a
  }

  .page {
    padding: 24px;
    max-width: 100%;
    margin: 0 auto
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 24px
  }

  @media (max-width: 768px) {
    .stats-grid {
      grid-template-columns: 1fr
    }
  }

  @media (max-width: 640px) {
    .stats-grid {
      gap: 12px
    }
  }

  @media (max-width: 640px) {
    .stat-card {
      padding: 16px;
      gap: 12px
    }
  }

  @media (max-width: 640px) {
    .stat-card .icon {
      width: 48px;
      height: 48px;
      font-size: 20px
    }
  }

  @media (max-width: 640px) {
    .stat-card .label {
      font-size: 10px
    }
  }

  @media (max-width: 640px) {
    .stat-card .value {
      font-size: 24px
    }
  }

  @media (max-width: 640px) {
    .page {
      padding: 16px
    }
  }

  @media (max-width: 640px) {
    .card-header {
      padding: 14px 16px 12px;
      flex-direction: column;
      align-items: stretch;
      gap: 12px
    }
  }

  @media (max-width: 640px) {
    .card-title {
      font-size: 14px
    }
  }

  @media (max-width: 640px) {
    .controls {
      flex-direction: column;
      align-items: stretch
    }
  }

  @media (max-width: 640px) {
    .controls-left, .controls-right {
      width: 100%;
      justify-content: space-between
    }
  }

  @media (max-width: 640px) {
    .search-input {
      width: 100%
    }
  }

  @media (max-width: 640px) {
    .btn {
      font-size: 11px;
      padding: 6px 12px
    }
  }

  @media (max-width: 640px) {
    th, td {
      padding: 8px 12px;
      font-size: 12px
    }
  }

  @media (max-width: 640px) {
    th {
      font-size: 10px
    }
  }

  @media (max-width: 640px) {
    .user-avatar, .user-avatar-placeholder {
      width: 32px;
      height: 32px;
      font-size: 14px
    }
  }

  @media (max-width: 640px) {
    .user-info .name {
      font-size: 13px
    }
  }

  @media (max-width: 640px) {
    .user-info .email {
      font-size: 11px
    }
  }

  @media (max-width: 640px) {
    .badge {
      padding: 4px 8px;
      font-size: 10px
    }
  }

  @media (max-width: 640px) {
    .actions {
      flex-direction: column
    }
  }

  @media (max-width: 640px) {
    .actions .btn {
      width: 100%
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
    .modal {
      width: 95%;
      max-height: 95vh
    }
  }

  @media (max-width: 640px) {
    .modal-header {
      padding: 14px 16px 12px
    }
  }

  @media (max-width: 640px) {
    .modal-title {
      font-size: 14px
    }
  }

  @media (max-width: 640px) {
    .modal-body {
      padding: 16px
    }
  }

  @media (max-width: 640px) {
    .modal-footer {
      padding: 14px 16px;
      flex-direction: column
    }
  }

  @media (max-width: 640px) {
    .modal-footer .btn {
      width: 100%
    }
  }

  @media (max-width: 640px) {
    .form-group {
      margin-bottom: 12px
    }
  }

  @media (max-width: 640px) {
    .form-label {
      font-size: 11px
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
      width: 56px;
      height: 56px
    }
  }

  @media (max-width: 640px) {
    .info-grid {
      grid-template-columns: 1fr
    }
  }

  @media (max-width: 640px) {
    .info-item {
      padding: 10px
    }
  }

  @media (max-width: 640px) {
    .info-label {
      font-size: 10px
    }
  }

  @media (max-width: 640px) {
    .info-value {
      font-size: 12px
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
    .stat-card .value {
      font-size: 20px
    }
  }

  @media (max-width: 480px) {
    .page {
      padding: 12px
    }
  }

  @media (max-width: 480px) {
    .card-header {
      padding: 12px 14px 10px
    }
  }

  @media (max-width: 480px) {
    .card-title {
      font-size: 13px
    }
  }

  @media (max-width: 480px) {
    .btn {
      font-size: 10px;
      padding: 5px 10px
    }
  }

  @media (max-width: 480px) {
    th, td {
      padding: 6px 10px;
      font-size: 11px
    }
  }

  @media (max-width: 480px) {
    th {
      font-size: 9px
    }
  }

  @media (max-width: 480px) {
    .user-avatar, .user-avatar-placeholder {
      width: 28px;
      height: 28px;
      font-size: 12px
    }
  }

  @media (max-width: 480px) {
    .badge {
      padding: 3px 6px;
      font-size: 9px
    }
  }

  @media (max-width: 480px) {
    .modal {
      width: 98%
    }
  }

  @media (max-width: 480px) {
    .modal-body {
      padding: 14px
    }
  }

  @media (max-width: 480px) {
    .form-input, .form-select {
      font-size: 11px
    }
  }

  @media (max-width: 480px) {
    .photo-preview img {
      width: 48px;
      height: 48px
    }
  }

  .stat-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px
  }

  .stat-card .icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0
  }

  .stat-card .icon.violet {
    background: #E0E7FF;
    color: #5B21B6
  }

  .stat-card .icon.emerald {
    background: #D1FAE5;
    color: #047857
  }

  .stat-card .icon.red {
    background: #FEE2E2;
    color: #DC2626
  }

  .stat-card .label {
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    color: #666;
    margin-bottom: 4px
  }

  .stat-card .value {
    font-size: 28px;
    font-weight: 700;
    color: #1a1a1a
  }

  .card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px
  }

  .card-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap
  }

  .card-title {
    font-family: 'Lora', Georgia, serif;
    font-size: 16px;
    font-weight: 600;
    font-style: italic
  }

  .card-body {
    padding: 0;
    overflow-x: auto
  }

  .controls {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    width: 100%
  }

  .controls-left, .controls-right {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap
  }

  .search-input {
    padding: 8px 12px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    font-size: 13px;
    width: 240px
  }

  .search-input:focus {
    outline: none;
    border-color: #185FA5
  }

  select {
    padding: 8px 12px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    font-size: 13px;
    background: #fff
  }

  .btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all .2s
  }

  .btn-primary {
    background: #185FA5;
    color: #fff
  }

  .btn-primary:hover {
    background: #145090
  }

  .btn-secondary {
    background: #fff;
    color: #1a1a1a;
    border: 1px solid #e0e0e0
  }

  .btn-secondary:hover {
    background: #f5f5f5
  }

  .btn-success {
    background: #059669;
    color: #fff
  }

  .btn-success:hover {
    background: #047857
  }

  .btn-danger {
    background: #DC2626;
    color: #fff
  }

  .btn-danger:hover {
    background: #B91C1C
  }

  .btn-amber {
    background: #D97706;
    color: #fff
  }

  .btn-amber:hover {
    background: #B45309
  }

  .btn-sm {
    padding: 6px 12px;
    font-size: 12px
  }

  table {
    width: 100%;
    border-collapse: collapse
  }

  th, td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
    font-size: 13px
  }

  th {
    font-weight: 600;
    color: #666;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px
  }

  tr:hover {
    background: #f9f9f9
  }

  .user-cell {
    display: flex;
    align-items: center;
    gap: 12px
  }

  .user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover
  }

  .user-avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #E0E7FF;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5B21B6;
    font-size: 16px
  }

  .user-info .name {
    font-weight: 500;
    color: #1a1a1a
  }

  .user-info .email {
    font-size: 12px;
    color: #666
  }

  .badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px
  }

  .role-super_admin {
    background: #4C1D95;
    color: #fff
  }

  .role-admin {
    background: #1E40AF;
    color: #fff
  }

  .role-front_desk {
    background: #0E7490;
    color: #fff
  }

  .role-housekeeping {
    background: #047857;
    color: #fff
  }

  .role-canteen_staff {
    background: #C2410C;
    color: #fff
  }

  .role-security {
    background: #374151;
    color: #fff
  }

  .role-customer {
    background: #475569;
    color: #fff
  }

  .status-active {
    background: #D1FAE5;
    color: #059669
  }

  .status-inactive {
    background: #F5F5F5;
    color: #666
  }

  .status-suspended {
    background: #FEE2E2;
    color: #DC2626
  }

  .actions {
    display: flex;
    gap: 8px
  }

  .empty-state {
    text-align: center;
    padding: 48px 24px;
    color: #666
  }

  .empty-state i {
    font-size: 48px;
    color: #e0e0e0;
    margin-bottom: 16px
  }

  .modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center
  }

  .modal-overlay.active {
    display: flex
  }

  .modal {
    background: #fff;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto
  }

  .modal-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: space-between
  }

  .modal-title {
    font-family: 'Lora', Georgia, serif;
    font-size: 16px;
    font-weight: 600;
    font-style: italic
  }

  .modal-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #666
  }

  .modal-body {
    padding: 20px
  }

  .modal-footer {
    padding: 16px 20px;
    border-top: 1px solid #e0e0e0;
    display: flex;
    justify-content: flex-end;
    gap: 12px
  }

  .form-group {
    margin-bottom: 16px
  }

  .form-label {
    display: block;
    font-size: 12px;
    font-weight: 500;
    color: #666;
    margin-bottom: 4px
  }

  .form-input, .form-select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    font-size: 13px
  }

  .form-input:focus, .form-select:focus {
    outline: none;
    border-color: #185FA5
  }

  .form-input:disabled {
    background: #f5f5f5;
    color: #666
  }

  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px
  }

  @media (max-width: 640px) {
    .form-grid {
      grid-template-columns: 1fr
    }
  }

  .photo-preview {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 24px
  }

  .photo-preview img {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e0e0e0
  }

  .photo-actions {
    display: flex;
    gap: 12px;
    margin-top: 12px
  }

  .photo-actions label {
    font-size: 12px;
    color: #185FA5;
    cursor: pointer
  }

  .photo-actions label input {
    display: none
  }

  .photo-actions button {
    font-size: 12px;
    color: #DC2626;
    background: none;
    border: none;
    cursor: pointer
  }

  .crop-area {
    display: none;
    margin-top: 16px;
    padding: 16px;
    background: #f9f9f9;
    border-radius: 8px
  }

  .crop-area img {
    max-width: 100%;
    display: block
  }

  .crop-actions {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 12px
  }

  .info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-top: 16px
  }

  .info-item {
    padding: 12px;
    background: #f9f9f9;
    border-radius: 6px
  }

  .info-label {
    font-size: 11px;
    color: #666;
    margin-bottom: 4px
  }

  .info-value {
    font-size: 13px;
    font-weight: 500;
    color: #1a1a1a
  }
</style>

<script>
  let usersData = [];
  let croppedImageData = null;
  let csrfToken = '';
  const cropperInstances = {};

  const DEFAULT_PROFILE_PATH = '<?php echo $defaultProfilePath; ?>';

  function normalizeProfilePhotoUrl(url) {
    if (!url || url.includes('default_profile')) {
      return DEFAULT_PROFILE_PATH;
    }
    if (url.startsWith('http://') || url.startsWith('https://')) {
      return url;
    }

    const isLocalhost = window.location.hostname === 'localhost' ||
      window.location.hostname === '127.0.0.1' ||
      window.location.hostname.match(/^192\.168\./) ||
      window.location.hostname.match(/^10\./);

    if (!url.startsWith('/')) {
      url = '/' + url;
    }

    if (isLocalhost) {
      if (url.startsWith('/../uploads/profile_photos/')) return url;
      if (url.startsWith('/uploads/profile_photos/')) {
        return '/../uploads/profile_photos/' + url.split('/').pop();
      }
      if (url.startsWith('/build/assets/images/uploads/profile_photos/')) {
        return '/../uploads/profile_photos/' + url.split('/').pop();
      }
    } else {
      if (url.startsWith('/uploads/profile_photos/')) return url;
      if (url.startsWith('/../uploads/profile_photos/')) {
        return '/uploads/profile_photos/' + url.split('/').pop();
      }
      if (url.startsWith('/build/assets/images/uploads/profile_photos/')) {
        return '/uploads/profile_photos/' + url.split('/').pop();
      }
    }
    return url;
  }

  function getRoleBadgeClass(roleCode) {
    const classes = {
      'super_admin': 'role-super_admin',
      'admin': 'role-admin',
      'front_desk': 'role-front_desk',
      'housekeeping': 'role-housekeeping',
      'canteen_staff': 'role-canteen_staff',
      'security': 'role-security',
      'customer': 'role-customer'
    };
    return classes[roleCode] || 'role-customer';
  }

  function getStatusBadgeClass(status) {
    const classes = {
      'active': 'status-active',
      'inactive': 'status-inactive',
      'suspended': 'status-suspended'
    };
    return classes[status] || 'status-inactive';
  }

  async function fetchCsrfToken() {
    try {
      const res = await fetch('api/users.php?action=csrf_token');
      const json = await res.json();
      if (json.success) csrfToken = json.csrf_token;
    } catch (e) {
      console.error('Error fetching CSRF token:', e);
    }
  }

  async function fetchUsers() {
    try {
      const res = await fetch('api/users.php?action=list');
      const json = await res.json();
      if (json.success) {
        usersData = json.data;
        renderStats();
        renderTable();
      }
    } catch (e) {
      console.error('Error fetching users:', e);
    }
  }

  function renderStats() {
    const total = usersData.length;
    const active = usersData.filter(u => u.account_status === 'active').length;
    const inactive = usersData.filter(u => u.account_status !== 'active').length;
    document.getElementById('statTotal').textContent = total;
    document.getElementById('statActive').textContent = active;
    document.getElementById('statInactive').textContent = inactive;
  }

  function renderTable() {
    const tbody = document.getElementById('usersTableBody');
    if (!usersData.length) {
      tbody.innerHTML = `
        <tr>
          <td colspan="7" class="empty-state">
            <i class="fas fa-users"></i>
            <p>No users found.</p>
          </td>
        </tr>`;
      return;
    }
    tbody.innerHTML = usersData.map(user => {
      const photoUrl = normalizeProfilePhotoUrl(user.profile_photo_url);
      const roleClass = getRoleBadgeClass(user.role_code);
      const statusClass = getStatusBadgeClass(user.account_status);
      const lastLogin = user.last_login_at ?
        new Date(user.last_login_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true }) :
        'Never';
      const created = new Date(user.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true });

      return `
        <tr class="user-row">
          <td>
            <div class="user-cell">
              <img src="${photoUrl}" alt="" class="user-avatar" onerror="this.src='${DEFAULT_PROFILE_PATH}'">
              <div class="user-info">
                <div class="name">${user.first_name || ''} ${user.last_name || ''}</div>
                <div class="email">${user.email}</div>
              </div>
            </div>
          </td>
          <td>
            <span class="badge ${roleClass}">${user.role_label || user.role_code || '-'}</span>
          </td>
          <td>${user.phone_number || '-'}</td>
          <td>${lastLogin}</td>
          <td>${created}</td>
          <td style="text-align: center">
            <span class="badge ${statusClass}">${user.account_status || 'unknown'}</span>
          </td>
          <td>
            <div class="actions">
              <button class="btn btn-secondary btn-sm" onclick="viewUser('${user.user_id}')">
                <i class="fas fa-eye"></i> View
              </button>
              <button class="btn btn-amber btn-sm" onclick="editUser('${user.user_id}')">
                <i class="fas fa-edit"></i> Edit
              </button>
              <button class="btn btn-danger btn-sm" onclick="deleteUser('${user.user_id}')">
                <i class="fas fa-trash"></i> Delete
              </button>
            </div>
          </td>
        </tr>`;
    }).join('');
  }

  function viewUser(userId) {
    const user = usersData.find(u => u.user_id === userId);
    if (!user) return;
    const statusClass = getStatusBadgeClass(user.account_status);
    const roleClass = getRoleBadgeClass(user.role_code);

    Swal.fire({
      title: 'User Details',
      html: `
        <div style="text-align: center; margin-bottom: 24px">
          <img src="${normalizeProfilePhotoUrl(user.profile_photo_url)}" alt="" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 12px" onerror="this.src='${DEFAULT_PROFILE_PATH}'">
          <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 4px">${user.first_name || ''} ${user.last_name || ''}</h3>
          <p style="font-size: 13px; color: #666; margin-bottom: 12px">${user.email}</p>
          <span class="badge ${statusClass}">${user.account_status || 'unknown'}</span>
        </div>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">Role</div>
            <div class="info-value"><span class="badge ${roleClass}">${user.role_label || user.role_code || '-'}</span></div>
          </div>
          <div class="info-item">
            <div class="info-label">Phone</div>
            <div class="info-value">${user.phone_number || '-'}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Email Verified</div>
            <div class="info-value" style="color: ${user.is_email_verified ? '#059669' : '#DC2626'}">${user.is_email_verified ? 'Yes' : 'No'}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Last Login</div>
            <div class="info-value">${user.last_login_at ? new Date(user.last_login_at).toLocaleString() : 'Never'}</div>
          </div>
        </div>
        <div class="info-item" style="margin-top: 16px">
          <div class="info-label">Account Created</div>
          <div class="info-value">${new Date(user.created_at).toLocaleDateString()}</div>
        </div>
      `,
      confirmButtonText: 'Close',
      customClass: { confirmButton: 'btn btn-primary' },
      buttonsStyling: false
    });
  }

  async function editUser(userId) {
    const user = usersData.find(u => u.user_id === userId);
    if (!user) return;

    let rolesOptions = [];
    try {
      const res = await fetch('api/users.php?action=roles');
      const json = await res.json();
      if (json.success) {
        rolesOptions = json.data.map(r => ({ value: r.role_id, label: r.role_label }));
      }
    } catch (e) {}

    const photoUrl = normalizeProfilePhotoUrl(user.profile_photo_url);

    Swal.fire({
      title: 'Edit User',
      html: `
        <form id="editUserForm" style="text-align: left">
          <div class="photo-preview">
            <img id="edit_photo_img" src="${photoUrl}" alt="" onerror="this.src='${DEFAULT_PROFILE_PATH}'">
            <div class="photo-actions">
              <label><i class="fas fa-camera"></i> Change
                <input type="file" id="edit_photo" accept="image/*" onchange="previewPhoto(this, 'edit_photo_img')">
              </label>
              <button type="button" onclick="removePhoto('edit_photo_img')"><i class="fas fa-trash"></i> Remove</button>
            </div>
            <input type="hidden" id="edit_remove_photo" value="0">
          </div>
          <div class="crop-area" id="edit_crop_area">
            <img id="edit_crop_img" style="max-width: 100%">
            <div class="crop-actions">
              <button type="button" class="btn btn-primary btn-sm" onclick="applyCrop('edit')">Apply Crop</button>
              <button type="button" class="btn btn-secondary btn-sm" onclick="cancelCrop('edit')">Cancel</button>
            </div>
          </div>
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">First Name *</label>
              <input type="text" id="edit_first_name" class="form-input" value="${user.first_name || ''}" required>
            </div>
            <div class="form-group">
              <label class="form-label">Last Name *</label>
              <input type="text" id="edit_last_name" class="form-input" value="${user.last_name || ''}" required>
            </div>
            <div class="form-group">
              <label class="form-label">Email (Read-only)</label>
              <input type="text" class="form-input" value="${user.email}" disabled>
            </div>
            <div class="form-group">
              <label class="form-label">Phone Number</label>
              <input type="text" id="edit_phone_number" class="form-input" value="${user.phone_number || ''}">
            </div>
            <div class="form-group">
              <label class="form-label">Role</label>
              <select id="edit_role_id" class="form-select">
                ${rolesOptions.map(opt => `<option value="${opt.value}" ${opt.value == user.role_id ? 'selected' : ''}>${opt.label}</option>`).join('')}
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Account Status</label>
              <select id="edit_account_status" class="form-select">
                <option value="active" ${user.account_status === 'active' ? 'selected' : ''}>Active</option>
                <option value="inactive" ${user.account_status === 'inactive' ? 'selected' : ''}>Inactive</option>
                <option value="suspended" ${user.account_status === 'suspended' ? 'selected' : ''}>Suspended</option>
              </select>
            </div>
          </div>
          <input type="hidden" id="edit_user_id" value="${user.user_id}">
        </form>
      `,
      width: '600px',
      showCancelButton: true,
      confirmButtonText: 'Save Changes',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-secondary'
      },
      buttonsStyling: false,
      preConfirm: () => {
        if (!document.getElementById('edit_first_name').value || !document.getElementById('edit_last_name').value) {
          Swal.showValidationMessage('First and last name are required');
          return false;
        }
      }
    }).then(result => {
      if (result.isConfirmed) {
        const fd = new FormData();
        fd.append('user_id', document.getElementById('edit_user_id').value);
        fd.append('first_name', document.getElementById('edit_first_name').value);
        fd.append('last_name', document.getElementById('edit_last_name').value);
        fd.append('phone_number', document.getElementById('edit_phone_number').value);
        fd.append('role_id', document.getElementById('edit_role_id').value);
        fd.append('account_status', document.getElementById('edit_account_status').value);
        fd.append('remove_photo', document.getElementById('edit_remove_photo').value);
        fd.append('csrf_token', csrfToken);

        if (croppedImageData) {
          const file = new File([croppedImageData], 'cropped_photo.jpg', { type: 'image/jpeg' });
          fd.append('profile_photo', file);
          croppedImageData = null;
        } else {
          const photoFile = document.getElementById('edit_photo').files[0];
          if (photoFile) fd.append('profile_photo', photoFile);
        }

        Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        fetch('api/users.php?action=update', { method: 'POST', body: fd })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Updated!', text: data.message, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-primary' }, buttonsStyling: false })
                .then(() => fetchUsers());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-primary' }, buttonsStyling: false });
            }
          });
      }
    });
  }

  async function deleteUser(userId) {
    const user = usersData.find(u => u.user_id === userId);
    if (!user) return;

    Swal.fire({
      title: 'Delete User',
      text: `Are you sure you want to delete ${user.first_name || ''} ${user.last_name || ''}? This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'btn btn-danger',
        cancelButton: 'btn btn-secondary'
      },
      buttonsStyling: false
    }).then(result => {
      if (result.isConfirmed) {
        const fd = new FormData();
        fd.append('user_id', userId);
        fd.append('csrf_token', csrfToken);

        Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        fetch('api/users.php?action=delete', { method: 'POST', body: fd })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-primary' }, buttonsStyling: false })
                .then(() => fetchUsers());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-primary' }, buttonsStyling: false });
            }
          });
      }
    });
  }

  function createUser() {
    fetch('api/users.php?action=roles')
      .then(r => r.json())
      .then(json => {
        const rolesOptions = json.success ? json.data.map(r => ({ value: r.role_id, label: r.role_label })) : [];

        Swal.fire({
          title: 'Add New User',
          html: `
            <form id="createUserForm" style="text-align: left">
              <div class="photo-preview">
                <img id="create_photo_img" src="${DEFAULT_PROFILE_PATH}" alt="">
                <div class="photo-actions">
                  <label><i class="fas fa-camera"></i> Upload
                    <input type="file" id="create_photo" accept="image/*" onchange="previewPhoto(this, 'create_photo_img')">
                  </label>
                </div>
              </div>
              <div class="crop-area" id="create_crop_area">
                <img id="create_crop_img" style="max-width: 100%">
                <div class="crop-actions">
                  <button type="button" class="btn btn-primary btn-sm" onclick="applyCrop('create')">Apply Crop</button>
                  <button type="button" class="btn btn-secondary btn-sm" onclick="cancelCrop('create')">Cancel</button>
                </div>
              </div>
              <div class="form-grid">
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
                <div class="form-group">
                  <label class="form-label">Role</label>
                  <select id="create_role_id" class="form-select">
                    ${rolesOptions.map(opt => `<option value="${opt.value}">${opt.label}</option>`).join('')}
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">Password</label>
                  <input type="password" id="create_password" class="form-input" placeholder="Leave blank for default">
                </div>
              </div>
            </form>
          `,
          width: '600px',
          showCancelButton: true,
          confirmButtonText: 'Create User',
          cancelButtonText: 'Cancel',
          customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-secondary'
          },
          buttonsStyling: false,
          preConfirm: () => {
            if (!document.getElementById('create_first_name').value || !document.getElementById('create_last_name').value || !document.getElementById('create_email').value) {
              Swal.showValidationMessage('First name, last name, and email are required');
              return false;
            }
          }
        }).then(result => {
          if (result.isConfirmed) {
            const fd = new FormData();
            fd.append('first_name', document.getElementById('create_first_name').value);
            fd.append('last_name', document.getElementById('create_last_name').value);
            fd.append('email', document.getElementById('create_email').value);
            fd.append('phone_number', document.getElementById('create_phone_number').value);
            fd.append('role_id', document.getElementById('create_role_id').value);
            fd.append('password', document.getElementById('create_password').value || '');
            fd.append('csrf_token', csrfToken);

            if (croppedImageData) {
              const file = new File([croppedImageData], 'cropped_photo.jpg', { type: 'image/jpeg' });
              fd.append('profile_photo', file);
              croppedImageData = null;
            } else {
              const photoFile = document.getElementById('create_photo').files[0];
              if (photoFile) fd.append('profile_photo', photoFile);
            }

            Swal.fire({ title: 'Creating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            fetch('api/users.php?action=create', { method: 'POST', body: fd })
              .then(r => r.json())
              .then(data => {
                if (data.success) {
                  Swal.fire({ icon: 'success', title: 'Created!', text: data.message, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-primary' }, buttonsStyling: false })
                    .then(() => fetchUsers());
                } else {
                  Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-primary' }, buttonsStyling: false });
                }
              });
          }
        });
      });
  }

  function previewPhoto(input, previewId) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = e => {
        const cropImg = document.getElementById(previewId.replace('_img', '_crop_img'));
        const cropArea = document.getElementById(previewId.replace('_img', '_crop_area'));
        const prefix = previewId.startsWith('edit') ? 'edit' : 'create';

        if (cropperInstances[prefix]) {
          cropperInstances[prefix].destroy();
          delete cropperInstances[prefix];
        }

        cropImg.src = e.target.result;
        cropArea.style.display = 'block';

        cropperInstances[prefix] = new Cropper(cropImg, {
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
  }

  function applyCrop(prefix) {
    const cropper = cropperInstances[prefix];
    if (!cropper) return;

    const canvas = cropper.getCroppedCanvas({ width: 200, height: 200 });
    canvas.toBlob(blob => {
      croppedImageData = blob;
      const preview = document.getElementById(prefix + '_photo_img');
      const reader = new FileReader();
      reader.onload = e => { preview.src = e.target.result; };
      reader.readAsDataURL(blob);

      cropper.destroy();
      delete cropperInstances[prefix];
      document.getElementById(prefix + '_crop_area').style.display = 'none';
    }, 'image/jpeg', 0.9);
  }

  function cancelCrop(prefix) {
    if (cropperInstances[prefix]) {
      cropperInstances[prefix].destroy();
      delete cropperInstances[prefix];
    }
    document.getElementById(prefix + '_crop_area').style.display = 'none';
    const fileInput = document.getElementById(prefix + '_photo');
    if (fileInput) fileInput.value = '';
  }

  function removePhoto(previewId) {
    const prefix = previewId.startsWith('edit') ? 'edit' : 'create';
    if (cropperInstances[prefix]) {
      cropperInstances[prefix].destroy();
      delete cropperInstances[prefix];
    }
    const cropArea = document.getElementById(prefix + '_crop_area');
    if (cropArea) cropArea.style.display = 'none';

    document.getElementById(previewId).src = DEFAULT_PROFILE_PATH;
    const removeInput = document.getElementById('edit_remove_photo');
    if (removeInput) removeInput.value = '1';
    const fileInput = document.getElementById('edit_photo');
    if (fileInput) fileInput.value = '';
    croppedImageData = null;
  }

  function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;
    document.querySelectorAll('#usersTableBody .user-row').forEach(row => {
      const text = row.textContent.toLowerCase();
      const statusCell = row.querySelector('td:nth-child(6) .badge');
      const rowStatus = statusCell ? statusCell.textContent.trim().toLowerCase() : '';
      row.style.display = (text.includes(search) && (status === '' || rowStatus === status)) ? '' : 'none';
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    fetchCsrfToken();
    fetchUsers();
  });
</script>

<div class="page">
  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="icon violet">
        <i class="fas fa-users"></i>
      </div>
      <div>
        <div class="label">Total Users</div>
        <div class="value" id="statTotal">...</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="icon emerald">
        <i class="fas fa-user-check"></i>
      </div>
      <div>
        <div class="label">Active</div>
        <div class="value" id="statActive">...</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="icon red">
        <i class="fas fa-user-slash"></i>
      </div>
      <div>
        <div class="label">Inactive / Suspended</div>
        <div class="value" id="statInactive">...</div>
      </div>
    </div>
  </div>

  <!-- Users Table -->
  <div class="card">
    <div class="card-header">
      <div class="controls">
        <div class="controls-left">
          <h2 class="card-title">User Accounts</h2>
        </div>
        <div class="controls-right">
          <input type="text" id="searchInput" class="search-input" placeholder="Search users..." onkeyup="filterTable()">
          <select id="statusFilter" onchange="filterTable()">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="suspended">Suspended</option>
          </select>
          <button class="btn btn-primary" onclick="createUser()">
            <i class="fas fa-plus mr-1"></i> Add New User
          </button>
        </div>
      </div>
    </div>
    <div class="card-body">
      <table>
        <thead>
          <tr>
            <th>User</th>
            <th>Role</th>
            <th>Phone</th>
            <th>Last Login</th>
            <th>Created</th>
            <th style="text-align: center">Status</th>
            <th style="text-align: center">Actions</th>
          </tr>
        </thead>
        <tbody id="usersTableBody">
          <tr>
            <td colspan="7" class="empty-state">
              <i class="fas fa-spinner fa-spin"></i>
              <p>Loading users...</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>