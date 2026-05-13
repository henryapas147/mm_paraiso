<?php
// Dynamic default profile image path based on environment
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$basePath = dirname($_SERVER['SCRIPT_NAME']); // Gets /admin_booking/build or equivalent
$defaultProfilePath = $basePath . '/assets/images/default_profile.jpg';
?>
<script>
  let usersData = [];
  let croppedImageData = null;

  // Default profile image path from PHP
  const DEFAULT_PROFILE_PATH = '<?php echo $defaultProfilePath; ?>';

  // Normalize profile photo URL based on environment
  function normalizeProfilePhotoUrl(url) {
    if (!url || url.includes('default_profile')) {
      return DEFAULT_PROFILE_PATH;
    }

    const isLocalhost = window.location.hostname === 'localhost' || 
                        window.location.hostname === '127.0.0.1' ||
                        window.location.hostname.match(/^192\.168\./) ||
                        window.location.hostname.match(/^10\./);

    const basePath = '<?php echo $basePath; ?>';

    // Ensure path starts with /
    if (!url.startsWith('/')) {
      url = '/' + url;
    }

    if (isLocalhost) {
      // Localhost expects /admin_booking/uploads/profile_photos/
      if (url.startsWith('/admin_booking/uploads/profile_photos/')) {
        return url;
      }
      // Convert Hostinger path to localhost path
      if (url.startsWith('/uploads/profile_photos/')) {
        const filename = url.split('/').pop();
        return '/admin_booking/uploads/profile_photos/' + filename;
      }
      // Handle old /build/assets/images/ format
      if (url.startsWith('/build/assets/images/uploads/profile_photos/')) {
        const filename = url.split('/').pop();
        return '/admin_booking/uploads/profile_photos/' + filename;
      }
    } else {
      // Hostinger expects /uploads/profile_photos/
      if (url.startsWith('/uploads/profile_photos/')) {
        return url;
      }
      // Convert localhost path to Hostinger path
      if (url.startsWith('/admin_booking/uploads/profile_photos/')) {
        const filename = url.split('/').pop();
        return '/uploads/profile_photos/' + filename;
      }
      // Handle old /build/assets/images/ format
      if (url.startsWith('/build/assets/images/uploads/profile_photos/')) {
        const filename = url.split('/').pop();
        return '/uploads/profile_photos/' + filename;
      }
    }

    return url;
  }

  const cropperInstances = {};

  function getPhotoPreviewHtml(photoUrl, previewId, showRemove = false, fallbackInitial = '?') {
    const imgOrIcon = photoUrl
      ? `<img src="${photoUrl}" class="w-full h-full object-cover">`
      : `<i class="fas fa-user text-2xl text-slate-400"></i>`;

    const removeButton = showRemove && photoUrl
      ? `<button type="button" onclick="removePhoto('${previewId}', '${fallbackInitial}')" class="text-xs text-red-500 hover:text-red-700"><i class="fas fa-trash mr-1"></i>Remove</button>`
      : '';

    return `
      <div id="${previewId}" class="w-16 h-16 rounded-full overflow-hidden border-3 border-slate-200 shadow-md flex items-center justify-center bg-slate-100">
        ${imgOrIcon}
      </div>
      <div class="flex gap-2 mt-2">
        <label class="cursor-pointer text-xs text-indigo-600 font-semibold hover:text-indigo-800">
          <i class="fas fa-camera mr-1"></i>${showRemove ? 'Change' : 'Upload'}
          <input type="file" id="${previewId.replace('_preview', '')}" accept="image/*" class="hidden" onchange="previewPhoto(this, '${previewId}', '${fallbackInitial}')">
        </label>
        ${removeButton}
      </div>
      <div id="${previewId}_cropArea" style="display:none;" class="mt-4 w-full bg-slate-50 p-4 rounded-lg border border-slate-200">
        <div style="max-height: 300px; overflow: hidden;">
          <img id="${previewId}_cropImg" style="max-width:100%; display:block;">
        </div>
        <div class="flex gap-3 mt-4 justify-center">
          <button type="button" onclick="applyCrop('${previewId}')"
            class="px-4 py-2 text-sm font-semibold text-white bg-slate-700 rounded-lg hover:bg-slate-800 shadow-md">
            <i class="fas fa-check mr-2"></i>Apply Crop
          </button>
          <button type="button" onclick="cancelCrop('${previewId}')"
            class="px-4 py-2 text-sm font-semibold text-white bg-slate-500 rounded-lg hover:bg-slate-600 shadow-md">
            <i class="fas fa-times mr-2"></i>Cancel
          </button>
        </div>
      </div>
    `;
  }

  function getFormFieldHtml(id, label, value = '', type = 'text', disabled = false, required = false) {
    const disabledAttr = disabled ? 'disabled' : '';
    const requiredAttr = required ? 'required' : '';
    const extraClasses = disabled ? 'border-slate-200 bg-slate-100 text-slate-500' : 'border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent';
    return `
      <div>
        <label class="block text-xs font-semibold text-slate-600 mb-1">${label}</label>
        <input type="${type}" id="${id}" value="${value}" ${disabledAttr} ${requiredAttr} class="w-full px-3 py-2 text-sm border ${extraClasses} rounded-lg focus:outline-none">
      </div>
    `;
  }

  function getSelectFieldHtml(id, label, options, selectedValue = '') {
    const optionsHtml = options.map(opt =>
      `<option value="${opt.value}" ${opt.value == selectedValue ? 'selected' : ''}>${opt.label}</option>`
    ).join('');
    return `
      <div>
        <label class="block text-xs font-semibold text-slate-600 mb-1">${label}</label>
        <select id="${id}" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
          ${optionsHtml}
        </select>
      </div>
    `;
  }

  function getCommonSwalConfig() {
    return {
      customClass: {
        popup: 'swal2-popup-high-z',
        confirmButton: 'inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-slate-700 hover:bg-slate-800 rounded-lg shadow-sm transition-all duration-200 dark:bg-slate-600 dark:hover:bg-slate-500 whitespace-nowrap mr-2',
        cancelButton: 'inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-slate-500 hover:bg-slate-600 rounded-lg shadow-sm transition-all duration-200 dark:bg-slate-700 dark:hover:bg-slate-600 whitespace-nowrap'
      },
      buttonsStyling: false
    };
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
      tbody.innerHTML = `<tr><td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">No users found.</td></tr>`;
      return;
    }
    tbody.innerHTML = usersData.map(user => `
      <tr class="user-row">
        <td class="p-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <div class="flex items-center">
            <div class="inline-flex items-center justify-center w-10 h-10 mr-4 text-sm text-white bg-gradient-to-tl from-indigo-500 to-violet-500 rounded-circle overflow-hidden flex-shrink-0">
              <img src="${normalizeProfilePhotoUrl(user.profile_photo_url)}" alt="avatar" class="w-full h-full object-cover rounded-circle">
            </div>
            <div>
              <p class="mb-0 text-sm font-semibold leading-tight dark:text-white">${user.first_name || ''} ${user.last_name || ''}</p>
              <p class="mb-0 text-xs leading-tight text-slate-400 dark:text-white/60">${user.email}</p>
            </div>
          </div>
        </td>
        <td class="p-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <span class="bg-gradient-to-tl from-slate-600 to-slate-400 px-4 text-sm rounded-1.8 py-2 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${user.role_label || user.role_code || '-'}</span>
        </td>
        <td class="p-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <p class="mb-0 text-sm font-semibold leading-tight dark:text-white">${user.phone_number || '-'}</p>
        </td>
        <td class="p-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <p class="mb-0 text-sm font-semibold leading-tight dark:text-white">${user.last_login_at ? new Date(user.last_login_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true }) : 'Never'}</p>
        </td>
        <td class="p-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <p class="mb-0 text-sm font-semibold leading-tight dark:text-white">${new Date(user.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true })}</p>
        </td>
        <td class="p-4 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <span class="bg-gradient-to-tl ${user.account_status === 'active' ? 'from-emerald-500 to-teal-400' : user.account_status === 'suspended' ? 'from-red-500 to-orange-500' : 'from-slate-500 to-slate-400'} px-4 text-sm rounded-1.8 py-2 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">${user.account_status || 'unknown'}</span>
        </td>
        <td class="p-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
          <div class="flex items-center gap-2">
            <button onclick="viewUser('${user.user_id}')" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-600 dark:text-blue-400 bg-white dark:bg-slate-700/50 border border-blue-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 transition-all duration-200 shadow-sm">
              <i class="fas fa-eye mr-2 text-sm"></i>View
            </button>
            <button onclick="editUser('${user.user_id}')" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 transition-all duration-200 shadow-sm">
              <i class="fas fa-edit mr-2 text-sm"></i>Edit
            </button>
          </div>
        </td>
      </tr>`).join('');
  }

  function viewUser(userId) {
    const user = usersData.find(u => u.user_id === userId);
    if (!user) return;
    const statusColors = {
      'active': 'bg-emerald-100 text-emerald-700',
      'inactive': 'bg-slate-100 text-slate-700',
      'suspended': 'bg-red-100 text-red-700'
    };
    const statusClass = statusColors[user.account_status] || 'bg-slate-100 text-slate-700';
    const commonConfig = getCommonSwalConfig();
    Swal.fire({
      title: 'User Details',
      html: `
        <div class="text-left">
          <div class="flex flex-col items-center mb-8">
            <img src="${normalizeProfilePhotoUrl(user.profile_photo_url)}" alt="avatar" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg">
            <h3 class="mt-4 text-lg font-bold text-slate-800">${user.first_name || ''} ${user.last_name || ''}</h3>
            <p class="text-sm text-slate-500">${user.email}</p>
            <span class="mt-3 px-3 py-1 text-xs font-semibold rounded-full ${statusClass}">${user.account_status || 'unknown'}</span>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div class="p-3 bg-slate-50 rounded-lg">
              <p class="text-xs text-slate-500 mb-1">Role</p>
              <p class="text-sm font-semibold text-slate-800">${user.role_label || user.role_code || '-'}</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-lg">
              <p class="text-xs text-slate-500 mb-1">Phone</p>
              <p class="text-sm font-semibold text-slate-800">${user.phone_number || '-'}</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-lg">
              <p class="text-xs text-slate-500 mb-1">Email Verified</p>
              <p class="text-sm font-semibold ${user.is_email_verified ? 'text-emerald-600' : 'text-red-600'}">${user.is_email_verified ? 'Yes' : 'No'}</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-lg">
              <p class="text-xs text-slate-500 mb-1">Last Login</p>
              <p class="text-sm font-semibold text-slate-800">${user.last_login_at ? new Date(user.last_login_at).toLocaleString() : 'Never'}</p>
            </div>
          </div>
          <div class="mt-4 p-3 bg-slate-50 rounded-lg">
            <p class="text-xs text-slate-500 mb-1">Account Created</p>
            <p class="text-sm font-semibold text-slate-800">${new Date(user.created_at).toLocaleDateString()}</p>
          </div>
        </div>
      `,
      confirmButtonText: 'Close',
      ...commonConfig
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

    const fallbackInitial = user.first_name ? user.first_name.charAt(0).toUpperCase() : '?';
    const commonConfig = getCommonSwalConfig();

    Swal.fire({
      title: 'Edit User',
      html: `
        <form id="editUserForm" class="text-left">
          <div class="flex flex-col items-center mb-8">
            ${getPhotoPreviewHtml(normalizeProfilePhotoUrl(user.profile_photo_url), 'edit_photo_preview', true, fallbackInitial)}
            <input type="hidden" id="edit_remove_photo" value="0">
          </div>
          <div class="grid grid-cols-2 gap-4">
            ${getFormFieldHtml('edit_first_name', 'First Name', user.first_name || '', 'text', false, true)}
            ${getFormFieldHtml('edit_last_name', 'Last Name', user.last_name || '', 'text', false, true)}
            ${getFormFieldHtml('edit_email', 'Email (Read-only)', user.email, 'text', true, false)}
            ${getFormFieldHtml('edit_phone_number', 'Phone Number', user.phone_number || '')}
            ${getSelectFieldHtml('edit_role_id', 'Role', rolesOptions, user.role_id)}
            ${getSelectFieldHtml('edit_account_status', 'Account Status', [
              { value: 'active', label: 'Active' },
              { value: 'inactive', label: 'Inactive' },
              { value: 'suspended', label: 'Suspended' }
            ], user.account_status)}
          </div>
          <input type="hidden" id="edit_user_id" value="${user.user_id}">
        </form>
      `,
      width: '52rem',
      confirmButtonText: 'Save Changes',
      showCancelButton: true,
      cancelButtonText: 'Cancel',
      ...commonConfig,
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

        if (croppedImageData) {
          const file = new File([croppedImageData], 'cropped_photo.jpg', { type: 'image/jpeg' });
          fd.append('profile_photo', file);
          croppedImageData = null;
          submitUserUpdate(fd);
        } else {
          const photoFile = document.getElementById('edit_photo').files[0];
          if (photoFile) fd.append('profile_photo', photoFile);
          submitUserUpdate(fd);
        }
      }
    });

    function submitUserUpdate(formData) {
      Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading(), ...getCommonSwalConfig() });
      fetch('api/users.php?action=update', {
        method: 'POST',
        body: formData
      }).then(r => r.json()).then(data => {
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Updated!', text: data.message, confirmButtonColor: '#3085d6', ...getCommonSwalConfig() }).then(() => fetchUsers());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', ...getCommonSwalConfig() });
        }
      });
    }
  }

  function createUser() {
    fetch('api/users.php?action=roles')
      .then(r => r.json())
      .then(json => {
        const rolesOptions = json.success ? json.data.map(r => ({ value: r.role_id, label: r.role_label })) : [];
        const commonConfig = getCommonSwalConfig();
        Swal.fire({
          title: 'Add New User',
          html: `
            <form id="createUserForm" class="text-left">
              <div class="flex flex-col items-center mb-8">
                ${getPhotoPreviewHtml(null, 'create_photo_preview', false, '?')}
              </div>
              <div class="grid grid-cols-2 gap-4">
                ${getFormFieldHtml('create_first_name', 'First Name', '', 'text', false, true)}
                ${getFormFieldHtml('create_last_name', 'Last Name', '', 'text', false, true)}
                ${getFormFieldHtml('create_email', 'Email', '', 'email', false, true)}
                ${getFormFieldHtml('create_phone_number', 'Phone Number', '')}
                ${getSelectFieldHtml('create_role_id', 'Role', rolesOptions)}
                <div>
                  <label class="block text-xs font-semibold text-slate-600 mb-1">Password</label>
                  <input type="password" id="create_password" placeholder="Leave blank for default" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
              </div>
            </form>
          `,
          width: '52rem',
          confirmButtonText: 'Create User',
          showCancelButton: true,
          cancelButtonText: 'Cancel',
          ...commonConfig,
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

            if (croppedImageData) {
              const file = new File([croppedImageData], 'cropped_photo.jpg', { type: 'image/jpeg' });
              fd.append('profile_photo', file);
              croppedImageData = null;
              submitUserCreate(fd);
            } else {
              const photoFile = document.getElementById('create_photo').files[0];
              if (photoFile) fd.append('profile_photo', photoFile);
              submitUserCreate(fd);
            }
          }
        });

        function submitUserCreate(formData) {
          Swal.fire({ title: 'Creating...', allowOutsideClick: false, didOpen: () => Swal.showLoading(), ...getCommonSwalConfig() });
          fetch('api/users.php?action=create', {
            method: 'POST',
            body: formData
          }).then(r => r.json()).then(data => {
            if (data.success) {
              Swal.fire({ icon: 'success', title: 'Created!', text: data.message, confirmButtonColor: '#3085d6', ...getCommonSwalConfig() }).then(() => fetchUsers());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#d33', ...getCommonSwalConfig() });
            }
          });
        }
      });
  }

  function previewPhoto(input, previewId, fallbackInitial) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = e => {
        const cropImg = document.getElementById(`${previewId}_cropImg`);
        const cropArea = document.getElementById(`${previewId}_cropArea`);

        if (cropperInstances[previewId]) {
          cropperInstances[previewId].destroy();
          delete cropperInstances[previewId];
        }

        cropImg.src = e.target.result;
        cropArea.style.display = 'block';

        cropperInstances[previewId] = new Cropper(cropImg, {
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

  function applyCrop(previewId) {
    const cropper = cropperInstances[previewId];
    if (!cropper) return;

    const canvas = cropper.getCroppedCanvas({ width: 200, height: 200 });
    canvas.toBlob(blob => {
      croppedImageData = blob;

      const preview = document.getElementById(previewId);
      const reader = new FileReader();
      reader.onload = e => {
        preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
      };
      reader.readAsDataURL(blob);

      cropper.destroy();
      delete cropperInstances[previewId];
      document.getElementById(`${previewId}_cropArea`).style.display = 'none';
    }, 'image/jpeg', 0.9);
  }

  function cancelCrop(previewId) {
    if (cropperInstances[previewId]) {
      cropperInstances[previewId].destroy();
      delete cropperInstances[previewId];
    }
    document.getElementById(`${previewId}_cropArea`).style.display = 'none';

    const fileInput = document.getElementById(previewId.replace('_preview', ''));
    if (fileInput) fileInput.value = '';
  }

  function removePhoto(previewId, fallbackInitial) {
    if (cropperInstances[previewId]) {
      cropperInstances[previewId].destroy();
      delete cropperInstances[previewId];
    }
    const cropArea = document.getElementById(`${previewId}_cropArea`);
    if (cropArea) cropArea.style.display = 'none';

    const preview = document.getElementById(previewId);
    preview.innerHTML = `<img src="${DEFAULT_PROFILE_PATH}" class="w-full h-full object-cover">`;
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
      const statusCell = row.querySelector('td:nth-child(6) span');
      const rowStatus = statusCell ? statusCell.textContent.trim().toLowerCase() : '';
      row.style.display = (text.includes(search) && (status === '' || rowStatus === status)) ? '' : 'none';
    });
  }

  document.addEventListener('DOMContentLoaded', () => fetchUsers());
</script>

<!-- PAGE CONTENT -->
<!-- Stats Cards -->
<div class="flex flex-wrap -mx-3 mb-6">
  <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
      <div class="flex-auto p-4">
        <div class="flex flex-row -mx-3">
          <div class="flex-none w-2/3 max-w-full px-3">
            <p class="mb-0 font-sans text-xs font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Total Users</p>
            <h5 class="mb-0 font-bold dark:text-white" id="statTotal">...</h5>
          </div>
          <div class="px-3 text-right basis-1/3">
            <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-indigo-500 to-violet-500">
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
            <p class="mb-0 font-sans text-xs font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Active</p>
            <h5 class="mb-0 font-bold dark:text-white" id="statActive">...</h5>
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
            <p class="mb-0 font-sans text-xs font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Inactive / Suspended</p>
            <h5 class="mb-0 font-bold dark:text-white" id="statInactive">...</h5>
          </div>
          <div class="px-3 text-right basis-1/3">
            <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-red-500 to-orange-400">
              <i class="ni leading-none ni-fat-remove text-lg relative top-3.5 text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Users Table -->
<div class="flex flex-wrap -mx-3">
  <div class="w-full max-w-full px-3">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">

      <!-- Table Header -->
      <div class="p-6 pb-0 mb-0 rounded-t-2xl">
        <div class="flex items-center justify-between">
          <h6 class="mb-0 dark:text-white">User Accounts</h6>
          <div style="display: flex; align-items: center; gap: 12px;">
            <input
              type="text"
              id="searchInput"
              placeholder="Search users..."
              onkeyup="filterTable()"
              style="padding: 8px 14px; font-size: 0.875rem; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; width: 210px;">
            <select
              id="statusFilter"
              onchange="filterTable()"
              style="padding: 8px 14px; font-size: 0.875rem; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; width: 140px;">
              <option value="">All Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
              <option value="suspended">Suspended</option>
            </select>
            <button
              onclick="createUser()"
              style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px; font-size: 0.75rem; font-weight: 600; color: white; background-color: #334155; border-radius: 8px; border: none; cursor: pointer; white-space: nowrap;">
              <i class="fas fa-plus" style="font-size: 0.75rem;"></i> Add New User
            </button>
          </div>
        </div>
      </div>

      <!-- Table Body -->
      <div class="p-0 overflow-x-auto">
        <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
          <thead class="align-bottom">
            <tr>
              <th class="px-6 py-4 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">User</th>
              <th class="px-6 py-4 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Role</th>
              <th class="px-6 py-4 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Phone</th>
              <th class="px-6 py-4 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Last Login</th>
              <th class="px-6 py-4 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Created</th>
              <th class="px-6 py-4 font-bold text-center uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
              <th class="px-6 py-4 font-bold text-center uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Actions</th>
            </tr>
          </thead>
          <tbody id="usersTableBody">
            <tr>
              <td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">Loading users...</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>