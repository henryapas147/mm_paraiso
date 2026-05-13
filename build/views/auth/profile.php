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

<!-- PAGE CONTENT -->
      <!-- PAGE CONTENT -->
      <div class="relative flex flex-col flex-auto min-w-0 p-4 mx-6 overflow-hidden break-words bg-white border-0 dark:bg-slate-850 dark:shadow-dark-xl shadow-3xl rounded-2xl bg-clip-border">
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
          <div class="w-full max-w-full px-3 mx-auto mt-4 sm:my-auto sm:mr-0 md:w-1/2 md:flex-none lg:w-4/12">
            <div class="relative right-0">
              <ul class="relative flex flex-wrap p-1 list-none bg-gray-50 rounded-xl" nav-pills role="tablist">
                <li class="z-30 flex-auto text-center">
                  <a class="z-30 flex items-center justify-center w-full px-0 py-1 mb-0 transition-all ease-in-out border-0 rounded-lg bg-inherit text-slate-700" nav-link active href="javascript:;" role="tab" aria-selected="true">
                    <i class="ni ni-app"></i>
                    <span class="ml-2">App</span>
                  </a>
                </li>
                <li class="z-30 flex-auto text-center">
                  <a class="z-30 flex items-center justify-center w-full px-0 py-1 mb-0 transition-all ease-in-out border-0 rounded-lg bg-inherit text-slate-700" nav-link href="javascript:;" role="tab" aria-selected="false">
                    <i class="ni ni-email-83"></i>
                    <span class="ml-2">Messages</span>
                  </a>
                </li>
                <li class="z-30 flex-auto text-center">
                  <a class="z-30 flex items-center justify-center w-full px-0 py-1 mb-0 transition-colors ease-in-out border-0 rounded-lg bg-inherit text-slate-700" nav-link href="javascript:;" role="tab" aria-selected="false">
                    <i class="ni ni-settings-gear-65"></i>
                    <span class="ml-2">Settings</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="w-full p-6 mx-auto">
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
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?php echo addslashes($message); ?>',
                confirmButtonText: 'OK',
                customClass: {
                  confirmButton: 'swal2-confirm'
                }
              });
            <?php else: ?>
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo addslashes($message); ?>',
                confirmButtonText: 'OK',
                customClass: {
                  confirmButton: 'swal2-confirm'
                }
              });
              document.querySelector('.swal2-confirm').style.backgroundColor = '#dc2626';
            <?php endif; ?>
          </script>
        <?php endif; ?>
        <div class="flex flex-wrap -mx-3">
          <div class="w-full max-w-full px-3 shrink-0 md:w-8/12 md:flex-0">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
                <div class="flex items-center">
                  <p class="mb-0 dark:text-white/80">Edit Profile</p>
                </div>
              </div>
              <div class="flex-auto p-6">
                <form method="POST" action="">
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
                    <div class="w-full max-w-full px-3 shrink-0 md:w-full md:flex-0">
                      <div class="mb-4">
                        <label for="address_line1" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Address Line 1</label>
                        <input type="text" id="address_line1" name="address_line1" value="<?php echo htmlspecialchars($profile['address_line1'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-full md:flex-0">
                      <div class="mb-4">
                        <label for="address_line2" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Address Line 2</label>
                        <input type="text" id="address_line2" name="address_line2" value="<?php echo htmlspecialchars($profile['address_line2'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                      <div class="mb-4">
                        <label for="city" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">City</label>
                        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($profile['city'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                      <div class="mb-4">
                        <label for="country" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Country</label>
                        <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($profile['country'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                      <div class="mb-4">
                        <label for="postal_code" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Postal code</label>
                        <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($profile['postal_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                      </div>
                    </div>
                  </div>

                  <div class="mt-4 text-right">
                    <button type="submit" class="inline-block px-8 py-2 mb-4 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">Save Changes</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="w-full max-w-full px-3 mt-6 shrink-0 md:w-4/12 md:flex-0 md:mt-0">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <img class="w-full rounded-t-2xl" src="<?php echo $assets_path; ?>/img/bg-profile.jpg" alt="profile cover image">
              <div class="flex flex-wrap justify-center -mx-3">
                <div class="w-4/12 max-w-full px-3 flex-0 ">
                  <div class="mb-6 -mt-6 lg:mb-0 lg:-mt-16">
                    <div class="relative">
                      <img id="profilePhotoSidebar" class="h-auto max-w-full border-2 border-white border-solid rounded-circle cursor-pointer hover:opacity-80 transition-opacity" 
                           src="<?php echo htmlspecialchars($profilePhotoUrl); ?>" alt="profile image"
                           onclick="document.getElementById('profilePhotoInput').click()">
                      <div class="absolute bottom-0 right-0 bg-indigo-600 text-white rounded-full p-1.5 cursor-pointer shadow-md"
                           onclick="document.getElementById('profilePhotoInput').click()">
                        <i class="fas fa-camera text-xs"></i>
                      </div>
                      <input type="file" id="profilePhotoInput" accept="image/*" class="hidden" onchange="handleProfilePhotoUpload(this)">
                    </div>
                  </div>
                </div>
              </div>
              <div class="border-black/12.5 rounded-t-2xl p-6 text-center pt-0 pb-6 lg:pt-2 lg:pb-4">
                <div class="flex justify-between">
                  <button type="button" class="hidden px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in border-0 rounded-lg shadow-md cursor-pointer text-xs bg-cyan-500 lg:block tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">Connect</button>
                  <button type="button" class="block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in border-0 rounded-lg shadow-md cursor-pointer text-xs bg-cyan-500 lg:hidden tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                    <i class="ni ni-collection text-2.8"></i>
                  </button>
                  <button type="button" class="hidden px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in border-0 rounded-lg shadow-md cursor-pointer text-xs bg-slate-700 lg:block tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">Message</button>
                  <button type="button" class="block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in border-0 rounded-lg shadow-md cursor-pointer text-xs bg-slate-700 lg:hidden tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                    <i class="ni ni-email-83 text-2.8"></i>
                  </button>
                </div>
              </div>

              <div class="flex-auto p-6 pt-0">
                <div class="flex flex-wrap -mx-3">
                  <div class="w-full max-w-full px-3 flex-1-0">
                    <div class="flex justify-center">
                      <div class="grid text-center">
                        <span class="font-bold dark:text-white text-lg">0</span>
                        <span class="leading-normal dark:text-white text-sm opacity-80">Friends</span>
                      </div>
                      <div class="grid mx-6 text-center">
                        <span class="font-bold dark:text-white text-lg">0</span>
                        <span class="leading-normal dark:text-white text-sm opacity-80">Photos</span>
                      </div>
                      <div class="grid text-center">
                        <span class="font-bold dark:text-white text-lg">0</span>
                        <span class="leading-normal dark:text-white text-sm opacity-80">Comments</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="mt-6 text-center">
                  <h5 class="dark:text-white ">
                    <?php echo htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name'] ?? 'User'); ?>
                    <?php if ($profile && $profile['date_of_birth']): ?>
                      <span class="font-light">, <?php echo floor((time() - strtotime($profile['date_of_birth'])) / 31557600); ?></span>
                    <?php endif; ?>
                  </h5>
                  <div class="mb-2 font-semibold leading-relaxed text-base dark:text-white/80 text-slate-700">
                    <i class="mr-2 dark:text-white ni ni-pin-3"></i>
                    <?php echo htmlspecialchars(($profile['city'] ?? '') . ($profile['province'] ? ', ' . $profile['province'] : '') . ($profile['country'] ? ', ' . $profile['country'] : '')); ?>
                  </div>
                  <div class="mt-6 mb-2 font-semibold leading-relaxed text-base dark:text-white/80 text-slate-700">
                    <i class="mr-2 dark:text-white ni ni-briefcase-24"></i>
                    <?php echo htmlspecialchars($user['role_label'] ?? 'User'); ?>
                  </div>
                  <div class="dark:text-white/80">
                    <i class="mr-2 dark:text-white ni ni-hat-3"></i>
                    <?php echo htmlspecialchars($profile['nationality'] ?? 'Not specified'); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

<!-- Profile Photo Upload Script -->
<script>
  let profileCropper = null;
  let croppedProfileImage = null;

  function handleProfilePhotoUpload(input) {
    if (input.files && input.files[0]) {
      const file = input.files[0];
      const reader = new FileReader();
      reader.onload = e => {
        showProfileCropper(e.target.result);
      };
      reader.readAsDataURL(file);
    }
  }

  function showProfileCropper(imageSrc) {
    // Create inline cropper modal using SweetAlert
    const commonConfig = {
      customClass: { 
        popup: 'swal2-popup-high-z',
        confirmButton: 'inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-slate-700 hover:bg-slate-800 rounded-lg shadow-sm transition-all duration-200 whitespace-nowrap mr-2',
        cancelButton: 'inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-slate-500 hover:bg-slate-600 rounded-lg shadow-sm transition-all duration-200 whitespace-nowrap'
      },
      buttonsStyling: false
    };
    
    Swal.fire({
      title: 'Crop Profile Photo',
      html: `
        <div class="cropper-container" style="max-height: 400px; overflow: hidden;">
          <img id="profileCropperImage" src="${imageSrc}" style="max-width: 100%; display: block;">
        </div>
      `,
      width: '48rem',
      confirmButtonText: '<i class="fas fa-check mr-1"></i>Apply',
      showCancelButton: true,
      cancelButtonText: '<i class="fas fa-times mr-1"></i>Cancel',
      ...commonConfig,
      didOpen: () => {
        const image = document.getElementById('profileCropperImage');
        profileCropper = new Cropper(image, {
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
      },
      preConfirm: () => {
        if (profileCropper) {
          const canvas = profileCropper.getCroppedCanvas({
            width: 200,
            height: 200
          });
          return new Promise((resolve) => {
            canvas.toBlob((blob) => {
              croppedProfileImage = blob;
              resolve(true);
            }, 'image/jpeg', 0.9);
          });
        }
        return false;
      }
    }).then(result => {
      if (result.isConfirmed && croppedProfileImage) {
        // Update the profile photo preview
        const reader = new FileReader();
        reader.onload = e => {
          document.getElementById('profilePhoto').src = e.target.result;
        };
        reader.readAsDataURL(croppedProfileImage);
        
        // Submit the cropped image
        submitProfilePhoto(croppedProfileImage);
      }
      // Reset cropper
      if (profileCropper) {
        profileCropper.destroy();
        profileCropper = null;
      }
      document.getElementById('profilePhotoInput').value = '';
    });
  }

  function submitProfilePhoto(blob) {
    const formData = new FormData();
    formData.append('profile_photo', new File([blob], 'profile_photo.jpg', { type: 'image/jpeg' }));
    formData.append('update_profile_photo', '1');

    Swal.fire({
      title: 'Uploading...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });

    fetch('api/profile.php?action=update_photo', {
      method: 'POST',
      body: formData
    })
    .then(r => {
      console.log('Response status:', r.status);
      return r.json();
    })
    .then(data => {
      console.log('Response data:', data);
      if (data.success) {
        // Update the profile photo preview
        const reader = new FileReader();
        reader.onload = e => {
          document.getElementById('profilePhoto').src = e.target.result;
          // Also update the navbar profile photo if it exists
          const navbarPhoto = document.getElementById('navbarProfilePhoto');
          if (navbarPhoto) {
            navbarPhoto.src = e.target.result;
          }
          // Clear the cropped image after successful update
          croppedProfileImage = null;
        };
        reader.onerror = e => {
          console.error('FileReader error:', e);
        };
        reader.readAsDataURL(croppedProfileImage);
        
        Swal.fire({
          icon: 'success',
          title: 'Updated!',
          text: 'Profile photo updated successfully',
          confirmButtonText: 'OK',
          customClass: {
            confirmButton: 'px-4 py-2 text-sm font-semibold text-white bg-slate-700 hover:bg-slate-800 rounded-lg shadow-md'
          },
          buttonsStyling: false
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: data.message || 'Failed to update profile photo',
          confirmButtonText: 'OK',
          customClass: {
            confirmButton: 'px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-md'
          },
          buttonsStyling: false
        });
      }
    })
    .catch(err => {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Network error occurred',
        confirmButtonText: 'OK',
        customClass: {
          confirmButton: 'px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-md'
        },
        buttonsStyling: false
      });
    });
  }

  // Fetch profile data via AJAX on page load
  function fetchProfileData() {
    fetch('api/profile.php?action=get_profile')
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          // Update profile photo
          if (data.data.profile_photo_url) {
            const normalizedUrl = normalizeProfilePhotoUrl(data.data.profile_photo_url);
            if (normalizedUrl) {
              // Update main profile photo
              const profilePhoto = document.getElementById('profilePhoto');
              if (profilePhoto) {
                profilePhoto.src = normalizedUrl;
              }
              // Update sidebar profile photo
              const profilePhotoSidebar = document.getElementById('profilePhotoSidebar');
              if (profilePhotoSidebar) {
                profilePhotoSidebar.src = normalizedUrl;
              }
            }
          }
          // Update display name and role
          if (data.data) {
            const displayName = data.data.display_name || (data.data.first_name + ' ' + data.data.last_name) || 'User';
            const roleLabel = data.data.role_label || 'User';
            
            // Update display name
            const displayNameElement = document.getElementById('profileDisplayName');
            if (displayNameElement) {
              displayNameElement.textContent = displayName;
            }
            // Update role label
            const roleLabelElement = document.getElementById('profileRoleLabel');
            if (roleLabelElement) {
              roleLabelElement.textContent = roleLabel;
            }
          }
        }
      })
      .catch(err => console.error('Error fetching profile data:', err));
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

  // Fetch profile data when page loads
  document.addEventListener('DOMContentLoaded', fetchProfileData);
</script>