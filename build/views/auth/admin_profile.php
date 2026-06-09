<?php

/**
 * @var string $message
 * @var string $messageType
 * @var array|null $user
 * @var array|null $profile
 * @var string $assets_path
 */
// session_start() is already called in config/db.php
// Database connection is handled by the controller
// Layout includes are handled by the Controller's view() method
// This view should only contain the page content

// Default variable declarations to fix static analysis warnings
$message = $message ?? '';
$messageType = $messageType ?? '';
$user = $user ?? null;
$profile = $profile ?? null;

// Get profile photo URL - will be fetched via AJAX
// Dynamic default profile image path based on environment
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$basePath = dirname($_SERVER['SCRIPT_NAME']); // Gets /admin_booking/build or equivalent
$defaultProfilePath = $basePath . '/assets/images/default_profile.jpg';
$profilePhotoUrl = $defaultProfilePath; // Default, will be updated by AJAX
?>

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Lora:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">
<style>
/* ── Admin Profile — Design System ── */
.ap-page {
  --ap-gold:        #C9A96E;
  --ap-gold-dark:   #A07840;
  --ap-gold-light:  #E8D5B0;
  --ap-ink:         #1A1410;
  --ap-ink-soft:    #3D3530;
  --ap-surface:     #FFFFFF;
  --ap-surface-2:   #FAFAF8;
  --ap-border:      #EDEAE6;
  --ap-border-gold: rgba(201,169,110,.22);
  --ap-radius-md:   10px;
  --ap-radius-lg:   16px;
  --ap-radius-xl:   20px;
  --ap-shadow:      0 1px 3px rgba(0,0,0,.05),0 4px 16px rgba(0,0,0,.07);
  font-family: 'DM Sans', system-ui, sans-serif;
  color: var(--ap-ink);
}

/* Preserve icon fonts */
.ap-page .ni { font-family: 'NucleoIcons' !important; }
.ap-page .fa,.ap-page .fas,.ap-page .far,.ap-page .fab { font-family: 'Font Awesome 6 Free','Font Awesome 6 Brands' !important; }

/* ── Header card ── */
.ap-page > .relative.flex.flex-col.flex-auto {
  background: var(--ap-surface) !important;
  border: 1px solid var(--ap-border-gold) !important;
  border-radius: var(--ap-radius-xl) !important;
  box-shadow: var(--ap-shadow) !important;
}

/* Display name */
.ap-page #profileDisplayName {
  font-family: 'Lora', Georgia, serif !important;
  font-size: 1.4rem !important;
  font-weight: 500 !important;
  font-style: italic !important;
  color: var(--ap-ink) !important;
}

/* Role label */
.ap-page #profileRoleLabel {
  font-size: 0.68rem !important;
  font-weight: 600 !important;
  letter-spacing: .14em !important;
  text-transform: uppercase !important;
  color: var(--ap-gold) !important;
  opacity: 1 !important;
}

/* ── Form card ── */
.ap-page .shadow-xl {
  box-shadow: var(--ap-shadow) !important;
  border: 1px solid var(--ap-border) !important;
  border-radius: var(--ap-radius-xl) !important;
}

/* Card header title */
.ap-page .border-black\/12\.5 > .flex > p.mb-0 {
  font-family: 'Lora', Georgia, serif !important;
  font-size: .95rem !important;
  font-weight: 500 !important;
  font-style: italic !important;
  color: var(--ap-ink) !important;
  opacity: 1 !important;
}

/* Section labels */
.ap-page p.leading-normal.uppercase {
  font-size: .65rem !important;
  font-weight: 600 !important;
  letter-spacing: .18em !important;
  color: var(--ap-gold) !important;
}

/* Form labels */
.ap-page label.inline-block.font-bold {
  font-size: .7rem !important;
  font-weight: 600 !important;
  letter-spacing: .06em !important;
  text-transform: uppercase !important;
  color: var(--ap-ink-soft) !important;
}

/* Inputs */
.ap-page input.rounded-lg:not([type=checkbox]):not([type=radio]):not([type=file]),
.ap-page select.rounded-lg {
  font-size: .85rem !important;
  border-color: var(--ap-border) !important;
  border-radius: var(--ap-radius-md) !important;
  color: var(--ap-ink) !important;
}
.ap-page input.rounded-lg:focus, .ap-page select.rounded-lg:focus {
  border-color: var(--ap-gold) !important;
  box-shadow: 0 0 0 3px rgba(201,169,110,.15) !important;
}

/* Save button */
.ap-page button[type="submit"] {
  background: linear-gradient(135deg, var(--ap-gold), var(--ap-gold-dark)) !important;
  border-radius: var(--ap-radius-md) !important;
  font-size: .82rem !important;
  font-weight: 600 !important;
  letter-spacing: .06em !important;
  box-shadow: 0 2px 8px rgba(201,169,110,.35) !important;
  border: none !important;
}
.ap-page button[type="submit"]:hover { opacity: .9 !important; }
</style>

<!-- PAGE CONTENT -->
<div class="ap-page">
<div class="relative flex flex-col flex-auto min-w-0 p-4 overflow-hidden break-words bg-white border-0 dark:bg-slate-850 dark:shadow-dark-xl shadow-3xl rounded-2xl bg-clip-border">
  <div class="flex flex-wrap -mx-3">
    <div class="flex-none w-auto max-w-full px-3">
      <div class="relative inline-flex items-center justify-center text-white transition-all duration-200 ease-in-out text-base h-19 w-19 rounded-xl">
        <img id="profilePhoto" src="<?php echo htmlspecialchars($profilePhotoUrl); ?>" alt="profile_image" class="w-full h-full object-cover shadow-2xl rounded-xl" />
      </div>
    </div>
    <div class="flex-none w-auto max-w-full px-3 my-auto">
      <div class="h-full">
        <h5 id="profileDisplayName" class="mb-1 dark:text-white"><?php echo htmlspecialchars($profile['display_name'] ?? ($profile ? ($profile['first_name'] . ' ' . $profile['last_name']) : 'User')); ?></h5>
        <p id="profileRoleLabel" class="mb-0 font-semibold leading-normal dark:text-white dark:opacity-60 text-sm"><?php echo htmlspecialchars($user['role_label'] ?? 'User'); ?></p>
      </div>
    </div>
  </div>
</div>

<div class="mt-6">
  <?php if ($message): ?>
    <style>
      .swal2-confirm {
        background-color: #1d4ed8 !important;
        color: white !important;
        border: none !important;
        padding: 8px 16px !important;
        font-size: 14px !important;
        border-radius: 6px !important;
      }
      .swal2-popup.swal2-modal {
        border-radius: 12px !important;
      }
    </style>
    <script>
      <?php if ($messageType === 'success'): ?>
        Swal.fire({ icon: 'success', title: 'Success', text: '<?php echo addslashes($message); ?>', confirmButtonText: 'OK', customClass: { confirmButton: 'swal2-confirm' }});
      <?php else: ?>
        Swal.fire({ icon: 'error', title: 'Error', text: '<?php echo addslashes($message); ?>', confirmButtonText: 'OK', customClass: { confirmButton: 'swal2-confirm' }});
        document.querySelector('.swal2-confirm').style.backgroundColor = '#dc2626';
      <?php endif; ?>
    </script>
  <?php endif; ?>

  <!-- Profile Edit Form -->
  <div class="bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl">
    <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
      <div class="flex items-center">
        <p class="mb-0 dark:text-white/80">Edit Profile</p>
      </div>
    </div>
    <div class="flex-auto p-6">
          <form method="POST" action="<?php echo $base_url ?? './'; ?>profile">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <input type="hidden" name="update_profile" value="1">

            <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm">User Information</p>
            <div class="flex flex-wrap -mx-3">
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="username" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Username</label>
                  <input type="text" id="username" name="username" value="<?php echo htmlspecialchars(explode('@', $user['email'])[0] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" readonly />
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="email" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Email address</label>
                  <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" readonly />
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="first_name" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">First name</label>
                  <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($profile['first_name'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" required />
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="last_name" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Last name</label>
                  <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($profile['last_name'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" required />
                </div>
              </div>
            </div>
            <hr class="h-px mx-0 my-4 bg-transparent border-0 opacity-25 bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent " />

            <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm">Contact Information</p>
            <div class="flex flex-wrap -mx-3">
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="region_code_display" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Region</label>
                  <input type="text" id="region_code_display" list="region_list" value="<?php echo htmlspecialchars($profile['region_name'] ?? ''); ?>" data-code="<?php echo htmlspecialchars($profile['region_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" onchange="onRegionChange()" />
                  <input type="hidden" id="region_code" name="region_code" value="<?php echo htmlspecialchars($profile['region_code'] ?? ''); ?>" />
                  <datalist id="region_list"></datalist>
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="province_code_display" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Province</label>
                  <input type="text" id="province_code_display" list="province_list" value="<?php echo htmlspecialchars($profile['province_name'] ?? ''); ?>" data-code="<?php echo htmlspecialchars($profile['province_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" onchange="onProvinceChange()" />
                  <input type="hidden" id="province_code" name="province_code" value="<?php echo htmlspecialchars($profile['province_code'] ?? ''); ?>" />
                  <datalist id="province_list"></datalist>
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="city_municipality_code_display" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">City/Municipality</label>
                  <input type="text" id="city_municipality_code_display" list="city_list" value="<?php echo htmlspecialchars($profile['city_municipality_name'] ?? ''); ?>" data-code="<?php echo htmlspecialchars($profile['city_municipality_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" onchange="onCityChange()" />
                  <input type="hidden" id="city_municipality_code" name="city_municipality_code" value="<?php echo htmlspecialchars($profile['city_municipality_code'] ?? ''); ?>" />
                  <datalist id="city_list"></datalist>
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="barangay_code_display" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Barangay</label>
                  <input type="text" id="barangay_code_display" list="barangay_list" value="<?php echo htmlspecialchars($profile['barangay_name'] ?? ''); ?>" data-code="<?php echo htmlspecialchars($profile['barangay_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                  <input type="hidden" id="barangay_code" name="barangay_code" value="<?php echo htmlspecialchars($profile['barangay_code'] ?? ''); ?>" />
                  <datalist id="barangay_list"></datalist>
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="address_line1" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Address Line 1</label>
                  <input type="text" id="address_line1" name="address_line1" value="<?php echo htmlspecialchars($profile['address_line1'] ?? ''); ?>" placeholder="House/Unit number, Street name" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="address_line2" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Address Line 2</label>
                  <input type="text" id="address_line2" name="address_line2" value="<?php echo htmlspecialchars($profile['address_line2'] ?? ''); ?>" placeholder="Apartment, Suite, Unit, Building, Floor, etc." class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="country" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Country</label>
                  <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($profile['country'] ?? 'Philippines'); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="postal_code" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Postal Code</label>
                  <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($profile['postal_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>
              </div>
            </div>

            <div class="mt-6">
              <button type="submit" class="bg-blue-500 text-white active:bg-blue-600 font-bold text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none ease-linear transition-all duration-150">
                Save Changes
              </button>
            </div>
          </form>
    </div>
  </div>

  <!-- Change Password Section -->
  <div class="mt-6 bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl">
    <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
      <div class="flex items-center justify-between">
        <p class="mb-0 dark:text-white/80">Change Password</p>
        <button type="button" onclick="showChangePasswordModal()" class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg transition-colors">Change</button>
      </div>
    </div>
    <div class="flex-auto p-6">
      <p class="text-sm text-gray-500">Update your password regularly to keep your account secure</p>
    </div>
  </div>
</div>
</div><!-- /ap-page -->

<!-- Toast Notification -->
<div id="toast" class="toast"></div>
<style>
.toast {
  position: fixed;
  bottom: 30px;
  right: 30px;
  padding: 16px 24px;
  border-radius: 12px;
  color: white;
  font-size: 14px;
  font-weight: 500;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
  z-index: 10000;
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.3s ease, transform 0.3s ease;
  pointer-events: none;
}
.toast.show {
  opacity: 1;
  transform: translateY(0);
}
.toast.success {
  background: linear-gradient(135deg, #10b981, #059669);
}
.toast.error {
  background: linear-gradient(135deg, #ef4444, #dc2626);
}
.toast.warning {
  background: linear-gradient(135deg, #f59e0b, #d97706);
}
.toast.info {
  background: linear-gradient(135deg, #3b82f6, #2563eb);
}
</style>

<script>
  // Toast notification function
  function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    if (!toast) return;
    
    toast.textContent = message;
    toast.className = 'toast show ' + type;
    
    setTimeout(() => {
      toast.classList.remove('show');
    }, 3000);
  }

  // Change Password Modal
  async function showChangePasswordModal() {
    // Check if user has a password set (Google users might not)
    let hasPassword = true;
    try {
      const res = await fetch('api/auth.php?action=check_password_status');
      const result = await res.json();
      if (result.success !== undefined) {
        hasPassword = result.success;
      }
    } catch (error) {
      console.error('Failed to check password status:', error);
    }

    const modalTitle = hasPassword ? 'Change Password' : 'Set Password';
    const modalSubtitle = hasPassword 
      ? 'Enter your current password and choose a new secure password'
      : 'Set a password to enable login with your email and password';

    Swal.fire({
      title: '',
      html: `
        <div style="text-align: center; margin-bottom: 24px;">
          <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 16px;">
            <div style="background: #C9A96E; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
              <i class="ni ni-lock-circle-open text-white text-xl"></i>
            </div>
            <h3 style="margin: 0; font-family: 'Lora', Georgia, serif; font-size: 1.3rem; font-weight: 500; font-style: italic; color: #1A1410;">${modalTitle}</h3>
          </div>
          <p style="margin: 0; color: #3D3530; font-size: 14px; line-height: 1.6;">${modalSubtitle}</p>
        </div>
        <div style="text-align: left; max-width: 320px; margin: 0 auto;">
          ${hasPassword ? `
          <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 12px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 8px; color: #C9A96E;">Current Password</label>
            <input type="password" id="current-password" class="swal2-input" style="width: 100%; padding: 12px 16px; border: 2px solid #EDEAE6; border-radius: 12px; font-size: 14px; color: #1A1410; transition: border-color 0.2s;" placeholder="Enter current password" onfocus="this.style.borderColor='#C9A96E'" onblur="this.style.borderColor='#EDEAE6'">
          </div>
          ` : ''}
          <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 12px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 8px; color: #C9A96E;">New Password</label>
            <input type="password" id="new-password" class="swal2-input" style="width: 100%; padding: 12px 16px; border: 2px solid #EDEAE6; border-radius: 12px; font-size: 14px; color: #1A1410; transition: border-color 0.2s;" placeholder="Enter new password" onfocus="this.style.borderColor='#C9A96E'" onblur="this.style.borderColor='#EDEAE6'">
          </div>
          <div style="margin-bottom: 8px;">
            <label style="display: block; font-size: 12px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 8px; color: #C9A96E;">Confirm New Password</label>
            <input type="password" id="confirm-password" class="swal2-input" style="width: 100%; padding: 12px 16px; border: 2px solid #EDEAE6; border-radius: 12px; font-size: 14px; color: #1A1410; transition: border-color 0.2s;" placeholder="Confirm new password" onfocus="this.style.borderColor='#C9A96E'" onblur="this.style.borderColor='#EDEAE6'">
          </div>
        </div>
      `,
      confirmButtonText: `<i class="ni ni-check-bold mr-1"></i>${hasPassword ? 'Change Password' : 'Set Password'}`,
      showCancelButton: true,
      cancelButtonText: '<i class="ni ni-fat-delete mr-1"></i>Cancel',
      customClass: {
        popup: 'swal2-popup-high-z',
        confirmButton: 'inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white rounded-lg shadow-sm transition-all duration-200 whitespace-nowrap',
        cancelButton: 'inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white rounded-lg shadow-sm transition-all duration-200 whitespace-nowrap'
      },
      buttonsStyling: false,
      didOpen: () => {
        const confirmBtn = Swal.getConfirmButton();
        const cancelBtn = Swal.getCancelButton();
        if (confirmBtn) {
          confirmBtn.style.background = 'linear-gradient(135deg, #C9A96E, #A07840)';
          confirmBtn.style.boxShadow = '0 2px 8px rgba(201, 169, 110, 0.35)';
        }
        if (cancelBtn) {
          cancelBtn.style.background = '#3D3530';
        }
      },
      preConfirm: async () => {
        const currentPassword = hasPassword ? document.getElementById('current-password').value : '';
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        if (hasPassword && !currentPassword) {
          Swal.showValidationMessage('Current password is required');
          return false;
        }

        if (!newPassword || !confirmPassword) {
          Swal.showValidationMessage('New password fields are required');
          return false;
        }

        if (newPassword !== confirmPassword) {
          Swal.showValidationMessage('New passwords do not match');
          return false;
        }

        if (newPassword.length < 8) {
          Swal.showValidationMessage('Password must be at least 8 characters long');
          return false;
        }

        try {
          const csrfToken = document.querySelector('input[name="csrf_token"]')?.value || '';
          const res = await fetch('api/auth.php?action=change_password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              current_password: currentPassword,
              new_password: newPassword,
              csrf_token: csrfToken
            })
          });
          const result = await res.json();
          if (!result.success) {
            Swal.showValidationMessage(result.message || 'Failed to change password');
            return false;
          }
          return result;
        } catch (error) {
          Swal.showValidationMessage('Network error. Please try again.');
          return false;
        }
      }
    }).then((result) => {
      if (result.isConfirmed) {
        showToast(result.value.message || 'Password updated successfully', 'success');
      }
    });
  }
</script>

<!-- Profile Photo Upload Script -->
<script>
  // Fetch profile data when page loads
  document.addEventListener('DOMContentLoaded', async () => {
    const regions = await fetchRegions();
    populateDatalist('region_list', regions, 'region_name');
    fetchProfileData();
  });

  function fetchProfileData() {
    fetch('api/profile.php?action=get_profile')
      .then(r => {
        if (!r.ok) {
          throw new Error(`HTTP error! status: ${r.status}`);
        }
        return r.json();
      })
      .then(data => {
        if (data.success) {
          // Update profile photo
          const profilePhotoElement = document.getElementById('profilePhoto');
          if (profilePhotoElement && data.data.profile_photo_url) {
            profilePhotoElement.src = normalizeProfilePhotoUrl(data.data.profile_photo_url);
          }

          // Update display name
          const firstName = data.data.first_name || '';
          const lastName = data.data.last_name || '';
          const displayName = data.data.display_name || (firstName && lastName ? firstName + ' ' + lastName : 'User');
          const roleLabel = data.data.role_label || 'User';

          const displayNameElement = document.getElementById('profileDisplayName');
          if (displayNameElement) {
            displayNameElement.textContent = displayName;
          }
          // Update role label
          const roleLabelElement = document.getElementById('profileRoleLabel');
          if (roleLabelElement) {
            roleLabelElement.textContent = roleLabel;
          }

          // Populate all form fields
          const fields = ['first_name', 'last_name', 'address_line1', 'address_line2', 'country', 'postal_code'];
          fields.forEach(field => {
            const element = document.getElementById(field);
            if (element && data.data[field] !== undefined) {
              element.value = data.data[field];
            }
          });

          // Populate geography fields (names in display input, codes in hidden input)
          // Also populate dependent datalists
          if (data.data.region_code) {
            const regionInput = document.getElementById('region_code_display');
            const regionHidden = document.getElementById('region_code');
            if (regionInput) {
              regionInput.value = data.data.region_name || '';
              regionInput.dataset.code = data.data.region_code || '';
            }
            if (regionHidden) {
              regionHidden.value = data.data.region_code || '';
            }

            // Populate provinces based on region
            fetchProvinces(data.data.region_code).then(provinces => {
              populateDatalist('province_list', provinces, 'province_name');
            });
          }
          if (data.data.province_code) {
            const provinceInput = document.getElementById('province_code_display');
            const provinceHidden = document.getElementById('province_code');
            if (provinceInput) {
              provinceInput.value = data.data.province_name || '';
              provinceInput.dataset.code = data.data.province_code || '';
            }
            if (provinceHidden) {
              provinceHidden.value = data.data.province_code || '';
            }

            // Populate cities based on province
            fetchCitiesMunicipalities(data.data.province_code).then(cities => {
              populateDatalist('city_list', cities, 'city_municipality_name');
            });
          }
          if (data.data.city_municipality_code) {
            const cityInput = document.getElementById('city_municipality_code_display');
            const cityHidden = document.getElementById('city_municipality_code');
            if (cityInput) {
              cityInput.value = data.data.city_municipality_name || '';
              cityInput.dataset.code = data.data.city_municipality_code || '';
            }
            if (cityHidden) {
              cityHidden.value = data.data.city_municipality_code || '';
            }

            // Populate barangays based on city
            fetchBarangays(data.data.city_municipality_code).then(barangays => {
              populateDatalist('barangay_list', barangays, 'barangay_name');
            });
          }
          if (data.data.barangay_code) {
            const barangayInput = document.getElementById('barangay_code_display');
            const barangayHidden = document.getElementById('barangay_code');
            if (barangayInput) {
              barangayInput.value = data.data.barangay_name || '';
              barangayInput.dataset.code = data.data.barangay_code || '';
            }
            if (barangayHidden) {
              barangayHidden.value = data.data.barangay_code || '';
            }
          }
        }
      })
      .catch(err => console.error('Error fetching profile data:', err));
  }

  // Change handlers for geography fields
  async function onRegionChange() {
    const regionInput = document.getElementById('region_code_display');
    const regionName = regionInput.value;
    const regions = await fetchRegions();
    const region = regions.find(r => r.region_name === regionName);
    if (region) {
      regionInput.dataset.code = region.region_code;
      const regionHidden = document.getElementById('region_code');
      if (regionHidden) regionHidden.value = region.region_code || '';
      const provinces = await fetchProvinces(region.region_code);
      populateDatalist('province_list', provinces, 'province_name');
    }
  }

  async function onProvinceChange() {
    const provinceInput = document.getElementById('province_code_display');
    const provinceName = provinceInput.value;
    const regionCode = document.getElementById('region_code').value;
    if (regionCode) {
      const provinces = await fetchProvinces(regionCode);
      const province = provinces.find(p => p.province_name === provinceName);
      if (province) {
        provinceInput.dataset.code = province.province_code;
        const provinceHidden = document.getElementById('province_code');
        if (provinceHidden) provinceHidden.value = province.province_code || '';
        const cities = await fetchCitiesMunicipalities(province.province_code);
        populateDatalist('city_list', cities, 'city_municipality_name');
      }
    }
  }

  async function onCityChange() {
    const cityInput = document.getElementById('city_municipality_code_display');
    const cityName = cityInput.value;
    const provinceCode = document.getElementById('province_code').value;
    if (provinceCode) {
      const cities = await fetchCitiesMunicipalities(provinceCode);
      const city = cities.find(c => c.city_municipality_name === cityName);
      if (city) {
        cityInput.dataset.code = city.city_municipality_code;
        const cityHidden = document.getElementById('city_municipality_code');
        if (cityHidden) cityHidden.value = city.city_municipality_code || '';
        const barangays = await fetchBarangays(city.city_municipality_code);
        populateDatalist('barangay_list', barangays, 'barangay_name');
      }
    }
  }

  // Handle barangay input to update hidden code
  document.addEventListener('input', function(e) {
    if (e.target.id === 'barangay_code_display') {
      const barangayInput = document.getElementById('barangay_code_display');
      const barangayDatalist = document.getElementById('barangay_list');
      const barangayHidden = document.getElementById('barangay_code');
      if (barangayHidden) {
        const options = Array.from(barangayDatalist.options);
        const match = options.find(opt => opt.value === barangayInput.value);
        if (match) {
          barangayHidden.value = match.dataset.code || '';
        }
      }
    }
  });

  // Helper function to populate datalist
  function populateDatalist(datalistId, data, textField) {
    const datalist = document.getElementById(datalistId);
    if (!datalist) return;
    datalist.innerHTML = '';
    data.forEach(item => {
      const option = document.createElement('option');
      option.value = item[textField];
      datalist.appendChild(option);
    });
  }

  // Normalize profile photo URL based on environment
  function normalizeProfilePhotoUrl(url) {
    if (!url || url.includes('default_profile')) {
      return '<?php echo $defaultProfilePath; ?>';
    }

    const isLocalhost = window.location.hostname === 'localhost' ||
      window.location.hostname === '127.0.0.1' ||
      window.location.hostname.match(/^192\.168\./) ||
      window.location.hostname.match(/^10\./);

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
      if (url.startsWith('/build/assets/images/')) {
        return url;
      }
    }

    return url;
  }

  /* ─── GEOCODE DROPDOWNS ─────────────────────────────────────────────────────────── */
  let regionsData = [];

  async function fetchRegions() {
    if (regionsData.length > 0) return regionsData;
    try {
      const res = await fetch('api/geocode.php?action=regions');
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
      const res = await fetch(`api/geocode.php?action=provinces&region_code=${encodeURIComponent(regionCode)}`);
      const json = await res.json();
      if (json.success) return json.data;
    } catch (e) {
      console.error('fetchProvinces:', e);
    }
    return [];
  }

  async function fetchCitiesMunicipalities(provinceCode) {
    try {
      const res = await fetch(`api/geocode.php?action=cities_municipalities&province_code=${encodeURIComponent(provinceCode)}`);
      const json = await res.json();
      if (json.success) return json.data;
    } catch (e) {
      console.error('fetchCitiesMunicipalities:', e);
    }
    return [];
  }

  async function fetchBarangays(cityMunicipalityCode) {
    try {
      const res = await fetch(`api/geocode.php?action=barangays&city_municipality_code=${encodeURIComponent(cityMunicipalityCode)}`);
      const json = await res.json();
      if (json.success) return json.data;
    } catch (e) {
      console.error('fetchBarangays:', e);
    }
    return [];
  }

  // Initialize region dropdown
</script>
