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
              <ul class="relative flex flex-wrap p-1 list-none bg-gray-50 rounded-xl" role="tablist">
                <li class="z-30 flex-auto text-center">
                  <a class="z-30 flex items-center justify-center w-full px-0 py-1 mb-0 transition-all ease-in-out border-0 rounded-lg bg-inherit text-slate-700" href="javascript:;" role="tab" data-tab="app" aria-selected="false">
                    <i class="ni ni-app"></i>
                    <span class="ml-2">App</span>
                  </a>
                </li>
                <li class="z-30 flex-auto text-center">
                  <a class="z-30 flex items-center justify-center w-full px-0 py-1 mb-0 transition-all ease-in-out border-0 rounded-lg bg-inherit text-slate-700" href="javascript:;" role="tab" data-tab="messages" aria-selected="false">
                    <i class="ni ni-email-83"></i>
                    <span class="ml-2">Messages</span>
                  </a>
                </li>
                <li class="z-30 flex-auto text-center">
                  <a class="z-30 flex items-center justify-center w-full px-0 py-1 mb-0 transition-all ease-in-out border-0 rounded-lg bg-inherit text-slate-700" href="javascript:;" role="tab" data-tab="settings" aria-selected="false">
                    <i class="ni ni-settings-gear-65"></i>
                    <span class="ml-2">Settings</span>
                  </a>
                </li>
                <li class="z-30 flex-auto text-center">
                  <a class="z-30 flex items-center justify-center w-full px-0 py-1 mb-0 transition-all ease-in-out border-0 rounded-lg bg-inherit text-slate-700" href="javascript:;" role="tab" data-tab="profile" aria-selected="false">
                    <i class="ni ni-single-02"></i>
                    <span class="ml-2">Profile</span>
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

        <!-- Tab Content -->
        <div class="tab-content">
          <!-- App Tab -->
          <div id="app-tab" class="tab-pane hidden">
            <div class="flex flex-wrap -mx-3">
              <div class="w-full max-w-full px-3 shrink-0">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                  <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
                    <div class="flex items-center">
                      <p class="mb-0 dark:text-white/80">Dashboard Summary</p>
                    </div>
                  </div>
                  <div class="flex-auto p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                      <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                          <div>
                            <p class="text-sm opacity-80">Upcoming Bookings</p>
                            <p class="text-3xl font-bold mt-2" id="upcomingBookingsCount">0</p>
                          </div>
                          <i class="ni ni-calendar-grid-58 text-4xl opacity-50"></i>
                        </div>
                      </div>
                      <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                          <div>
                            <p class="text-sm opacity-80">Total Reservations</p>
                            <p class="text-3xl font-bold mt-2" id="totalReservationsCount">0</p>
                          </div>
                          <i class="ni ni-bag-17 text-4xl opacity-50"></i>
                        </div>
                      </div>
                      <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                          <div>
                            <p class="text-sm opacity-80">Total Payments</p>
                            <p class="text-3xl font-bold mt-2" id="totalPaymentsCount">₱0</p>
                          </div>
                          <i class="ni ni-money-coins text-4xl opacity-50"></i>
                        </div>
                      </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-6">
                      <h5 class="mb-4 dark:text-white font-semibold">Quick Actions</h5>
                      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <button class="bg-white dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 rounded-lg p-4 text-center transition-colors shadow-sm">
                          <i class="ni ni-calendar-add text-2xl text-indigo-600 mb-2"></i>
                          <p class="text-sm dark:text-white">Book New</p>
                        </button>
                        <button class="bg-white dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 rounded-lg p-4 text-center transition-colors shadow-sm">
                          <i class="ni ni-calendar-grid-58 text-2xl text-emerald-600 mb-2"></i>
                          <p class="text-sm dark:text-white">View Calendar</p>
                        </button>
                        <button class="bg-white dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 rounded-lg p-4 text-center transition-colors shadow-sm">
                          <i class="ni ni-credit-card text-2xl text-purple-600 mb-2"></i>
                          <p class="text-sm dark:text-white">Payments</p>
                        </button>
                        <button class="bg-white dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 rounded-lg p-4 text-center transition-colors shadow-sm">
                          <i class="ni ni-favourite-28 text-2xl text-pink-600 mb-2"></i>
                          <p class="text-sm dark:text-white">Favorites</p>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Messages Tab -->
          <div id="messages-tab" class="tab-pane hidden">
            <div class="flex flex-wrap -mx-3">
              <div class="w-full max-w-full px-3 shrink-0">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                  <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
                    <div class="flex items-center">
                      <p class="mb-0 dark:text-white/80">Messages</p>
                    </div>
                  </div>
                  <div class="flex-auto p-6">
                    <div class="text-center py-12">
                      <i class="ni ni-email-83 text-6xl text-gray-300 mb-4"></i>
                      <p class="text-gray-500">No messages yet</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Settings Tab -->
          <div id="settings-tab" class="tab-pane hidden">
            <div class="flex flex-wrap -mx-3">
              <div class="w-full max-w-full px-3 shrink-0">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                  <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
                    <div class="flex items-center">
                      <p class="mb-0 dark:text-white/80">Settings</p>
                    </div>
                  </div>
                  <div class="flex-auto p-6">
                    <div class="text-center py-12">
                      <i class="ni ni-settings-gear-65 text-6xl text-gray-300 mb-4"></i>
                      <p class="text-gray-500">Settings coming soon</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Profile Tab -->
          <div id="profile-tab" class="tab-pane hidden">
            <div class="flex flex-wrap -mx-3">
              <div class="w-full max-w-full px-3 shrink-0 md:w-8/12 md:flex-0">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                  <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
                    <div class="flex items-center">
                      <p class="mb-0 dark:text-white/80">Edit Profile</p>
                    </div>
                  </div>
                  <div class="flex-auto p-6">
                    <form id="profileForm" method="POST" action="">
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
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($profile['first_name'] ?? ''); ?>" data-orig-value="<?php echo htmlspecialchars($profile['first_name'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" required />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                      <div class="mb-4">
                        <label for="last_name" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Last name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($profile['last_name'] ?? ''); ?>" data-orig-value="<?php echo htmlspecialchars($profile['last_name'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" required />
                      </div>
                    </div>
                  </div>
                  <hr class="h-px mx-0 my-4 bg-transparent border-0 opacity-25 bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent " />

                  <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm">Contact Information</p>
                  <div class="flex flex-wrap -mx-3">
                    <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                      <div class="mb-4">
                        <label for="region_code" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Region</label>
                        <input type="text" id="region_code" list="region_list" name="region_code" value="<?php echo htmlspecialchars($profile['region_name'] ?? ''); ?>" data-code="<?php echo htmlspecialchars($profile['region_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" onchange="onRegionChange('profile')" />
                        <datalist id="region_list"></datalist>
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                      <div class="mb-4">
                        <label for="province_code" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Province</label>
                        <input type="text" id="province_code" list="province_list" name="province_code" value="<?php echo htmlspecialchars($profile['province_name'] ?? ''); ?>" data-code="<?php echo htmlspecialchars($profile['province_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" onchange="onProvinceChange('profile')" />
                        <datalist id="province_list"></datalist>
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                      <div class="mb-4">
                        <label for="city_municipality_code" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">City/Municipality</label>
                        <input type="text" id="city_municipality_code" list="city_list" name="city_municipality_code" value="<?php echo htmlspecialchars($profile['city_municipality_name'] ?? ''); ?>" data-code="<?php echo htmlspecialchars($profile['city_municipality_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" onchange="onCityChange('profile')" />
                        <datalist id="city_list"></datalist>
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                      <div class="mb-4">
                        <label for="barangay_code" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Barangay</label>
                        <input type="text" id="barangay_code" list="barangay_list" name="barangay_code" value="<?php echo htmlspecialchars($profile['barangay_name'] ?? ''); ?>" data-code="<?php echo htmlspecialchars($profile['barangay_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" oninput="onBarangayInput('profile')" />
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
                        <input type="text" id="address_line2" name="address_line2" value="<?php echo htmlspecialchars($profile['address_line2'] ?? ''); ?>" placeholder="Building, Subdivision..." class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                      <div class="mb-4">
                        <label for="postal_code" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Postal Code</label>
                        <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($profile['postal_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                      <div class="mb-4">
                        <label for="country" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Country</label>
                        <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($profile['country'] ?? 'Philippines'); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                      </div>
                    </div>
                  </div>

                  <div class="mt-4 text-right">
                    <button type="submit" class="inline-block px-8 py-2 mb-4 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">Save Changes</button>
                  </div>
                </form>

                <script>
                // Handle profile form submission via AJAX
                document.getElementById('profileForm').addEventListener('submit', function(e) {
                  e.preventDefault();

                  const formData = new FormData(this);
                  formData.append('action', 'update_profile');

                  // Replace geography names with codes from data-code attributes
                  const regionInput = document.getElementById('region_code');
                  const provinceInput = document.getElementById('province_code');
                  const cityInput = document.getElementById('city_municipality_code');
                  const barangayInput = document.getElementById('barangay_code');

                  if (regionInput && regionInput.dataset.code) {
                    formData.set('region_code', regionInput.dataset.code);
                  }
                  if (provinceInput && provinceInput.dataset.code) {
                    formData.set('province_code', provinceInput.dataset.code);
                  }
                  if (cityInput && cityInput.dataset.code) {
                    formData.set('city_municipality_code', cityInput.dataset.code);
                  }
                  if (barangayInput && barangayInput.dataset.code) {
                    formData.set('barangay_code', barangayInput.dataset.code);
                  }

                  fetch('api/profile.php', {
                    method: 'POST',
                    body: formData
                  })
                  .then(response => response.json())
                  .then(data => {
                    if (data.success) {
                      Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        confirmButtonText: 'OK',
                        customClass: {
                          confirmButton: 'swal2-confirm'
                        }
                      });
                      // Refresh profile data
                      fetchProfileData();
                    } else {
                      Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonText: 'OK',
                        customClass: {
                          confirmButton: 'swal2-confirm'
                        }
                      });
                      document.querySelector('.swal2-confirm').style.backgroundColor = '#dc2626';
                    }
                  })
                  .catch(err => {
                    Swal.fire({
                      icon: 'error',
                      title: 'Error',
                      text: 'Network error occurred',
                      confirmButtonText: 'OK',
                      customClass: {
                        confirmButton: 'swal2-confirm'
                      }
                    });
                  });
                });
                </script>
              </div>
            </div>
          </div>

          <!-- Sidebar - Profile tab only -->
          <div class="w-full max-w-full px-3 mt-6 shrink-0 md:w-4/12 md:flex-0 md:mt-0">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="relative overflow-visible">
                <img id="coverPhoto" class="w-full rounded-t-2xl object-cover h-48" src="<?php echo $assets_path; ?>/img/bg-profile.jpg" alt="profile cover image">
                <div class="absolute top-3 right-3 z-10">
                  <button type="button" class="bg-indigo-600 text-white rounded-lg px-4 py-2 cursor-pointer shadow-md hover:bg-indigo-700 transition-colors text-xs font-semibold flex items-center gap-2 whitespace-nowrap" onclick="document.getElementById('coverPhotoInput').click()">
                    <span>Edit Cover Photo</span>
                  </button>
                  <input type="file" id="coverPhotoInput" accept="image/*" class="hidden" onchange="handleCoverPhotoUpload(this)">
                </div>
              </div>
              <div class="flex flex-wrap justify-center -mx-3">
                <div class="w-4/12 max-w-full px-3 flex-0 ">
                  <div class="mb-6 -mt-6 lg:mb-0 lg:-mt-16">
                    <div class="relative">
                      <img id="profilePhotoSidebar" class="h-auto max-w-full border-2 border-white border-solid rounded-circle cursor-pointer hover:opacity-80 transition-opacity" 
                           src="<?php echo htmlspecialchars($profilePhotoUrl); ?>" alt="profile image"
                           onclick="document.getElementById('profilePhotoInput').click()">
                      <div class="absolute bottom-0 right-0 bg-indigo-600 text-white rounded-full p-1.5 cursor-pointer shadow-md flex items-center justify-center"
                           onclick="document.getElementById('profilePhotoInput').click()">
                        <span class="text-lg">📷</span>
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
                    <?php echo htmlspecialchars(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? '') ?: 'User'); ?>
                    <?php if ($profile && $profile['date_of_birth']): ?>
                      <span class="font-light">, <?php echo floor((time() - strtotime($profile['date_of_birth'])) / 31557600); ?></span>
                    <?php endif; ?>
                  </h5>
                  <div class="mb-2 font-semibold leading-relaxed text-base dark:text-white/80 text-slate-700">
                    <i class="mr-2 dark:text-white ni ni-pin-3"></i>
                    <span id="sidebarAddress">
                    <?php
                    $addressParts = [
                        $profile['barangay_name'] ?? '',
                        $profile['city_municipality_name'] ?? '',
                        $profile['province_name'] ?? '',
                        $profile['region_name'] ?? '',
                        $profile['country'] ?? ''
                    ];
                    echo htmlspecialchars(implode(', ', array_filter($addressParts)));
                    ?>
                    </span>
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
        </div> <!-- End Profile Tab -->

        </div> <!-- End Tab Content -->

<!-- Profile Photo Upload Script -->
<script>
  // Tab switching functionality
  document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('[data-tab]');
    const tabPanes = document.querySelectorAll('.tab-pane');

    // Function to activate a specific tab
    function activateTab(tabName) {
      // Remove active class from all tab links
      tabLinks.forEach(link => {
        link.classList.remove('active', 'bg-white', 'shadow-sm');
        link.classList.add('bg-inherit');
        link.setAttribute('aria-selected', 'false');
      });

      // Find and activate the target tab link
      const targetTab = document.querySelector('[data-tab="' + tabName + '"]');
      if (targetTab) {
        targetTab.classList.add('active', 'bg-white', 'shadow-sm');
        targetTab.classList.remove('bg-inherit');
        targetTab.setAttribute('aria-selected', 'true');
      }

      // Hide all tab panes
      tabPanes.forEach(pane => {
        pane.classList.add('hidden');
      });

      // Show selected tab pane
      const tabId = tabName + '-tab';
      const selectedPane = document.getElementById(tabId);
      if (selectedPane) {
        selectedPane.classList.remove('hidden');
      }

      // Save selected tab to localStorage
      localStorage.setItem('selectedProfileTab', tabName);
    }

    // Add click event listeners to all tabs
    tabLinks.forEach(tabLink => {
      tabLink.addEventListener('click', function(e) {
        e.preventDefault();
        activateTab(this.getAttribute('data-tab'));
      });
    });

    // Set initial tab state from localStorage or default to 'app'
    const savedTab = localStorage.getItem('selectedProfileTab');
    activateTab(savedTab || 'app');
  });

  let profileCropper = null;
  let croppedProfileImage = null;
  let coverCropper = null;
  let croppedCoverImage = null;

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

  function handleCoverPhotoUpload(input) {
    if (input.files && input.files[0]) {
      const file = input.files[0];
      const reader = new FileReader();
      reader.onload = e => {
        showCoverPhotoCropper(e.target.result);
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

  function showCoverPhotoCropper(imageSrc) {
    const commonConfig = {
      customClass: {
        popup: 'swal2-popup-high-z',
        confirmButton: 'inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-slate-700 hover:bg-slate-800 rounded-lg shadow-sm transition-all duration-200 whitespace-nowrap mr-2',
        cancelButton: 'inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-slate-500 hover:bg-slate-600 rounded-lg shadow-sm transition-all duration-200 whitespace-nowrap'
      },
      buttonsStyling: false
    };

    Swal.fire({
      title: 'Crop Cover Photo',
      html: `
        <div class="cropper-container" style="max-height: 400px; overflow: hidden;">
          <img id="coverCropperImage" src="${imageSrc}" style="max-width: 100%; display: block;">
        </div>
      `,
      width: '48rem',
      confirmButtonText: '<i class="fas fa-check mr-1"></i>Apply',
      showCancelButton: true,
      cancelButtonText: '<i class="fas fa-times mr-1"></i>Cancel',
      ...commonConfig,
      didOpen: () => {
        const image = document.getElementById('coverCropperImage');
        coverCropper = new Cropper(image, {
          aspectRatio: 16 / 9,
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
        if (coverCropper) {
          const canvas = coverCropper.getCroppedCanvas({
            width: 1200,
            height: 675
          });
          return new Promise((resolve) => {
            canvas.toBlob((blob) => {
              croppedCoverImage = blob;
              resolve(true);
            }, 'image/jpeg', 0.9);
          });
        }
        return false;
      }
    }).then(result => {
      if (result.isConfirmed && croppedCoverImage) {
        // Update the cover photo preview
        const reader = new FileReader();
        reader.onload = e => {
          document.getElementById('coverPhoto').src = e.target.result;
        };
        reader.readAsDataURL(croppedCoverImage);

        // Submit the cropped image
        submitCoverPhoto(croppedCoverImage);
      }
      // Reset cropper
      if (coverCropper) {
        coverCropper.destroy();
        coverCropper = null;
      }
      document.getElementById('coverPhotoInput').value = '';
    });
  }

  function submitCoverPhoto(blob) {
    const formData = new FormData();
    formData.append('cover_photo', new File([blob], 'cover_photo.jpg', { type: 'image/jpeg' }));
    formData.append('action', 'update_cover_photo');

    Swal.fire({
      title: 'Uploading...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });

    fetch('api/profile.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      Swal.close();
      if (data.success) {
        Swal.fire({
          icon: 'success',
          title: 'Updated!',
          text: 'Cover photo updated successfully',
          confirmButtonText: 'OK',
          customClass: {
            confirmButton: 'px-4 py-2 text-sm font-semibold text-white bg-slate-700 hover:bg-slate-800 rounded-lg shadow-md'
          },
          buttonsStyling: false
        });
        // Update cover photo with the returned URL
        const coverPhoto = document.getElementById('coverPhoto');
        if (coverPhoto && data.cover_photo_url) {
          coverPhoto.src = normalizeProfilePhotoUrl(data.cover_photo_url);
        }
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: data.message || 'Failed to update cover photo',
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
          // Update cover photo
          if (data.data.cover_photo_url) {
            const normalizedCoverUrl = normalizeProfilePhotoUrl(data.data.cover_photo_url);
            if (normalizedCoverUrl) {
              const coverPhoto = document.getElementById('coverPhoto');
              if (coverPhoto) {
                coverPhoto.src = normalizedCoverUrl;
              }
            }
          }
          // Update display name and role
          if (data.data) {
            const firstName = data.data.first_name || '';
            const lastName = data.data.last_name || '';
            const displayName = data.data.display_name || (firstName && lastName ? firstName + ' ' + lastName : 'User');
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

            // Update form input fields if they're empty
            const firstNameInput = document.getElementById('first_name');
            if (firstNameInput && !firstNameInput.value) {
              firstNameInput.value = firstName;
            }
            const lastNameInput = document.getElementById('last_name');
            if (lastNameInput && !lastNameInput.value) {
              lastNameInput.value = lastName;
            }

            // Update geography fields
            const regionInput = document.getElementById('region_code');
            if (regionInput && data.data.region_name) {
              regionInput.value = data.data.region_name;
              if (data.data.region_code) {
                regionInput.dataset.code = data.data.region_code;
              }
            }
            const provinceInput = document.getElementById('province_code');
            if (provinceInput && data.data.province_name) {
              provinceInput.value = data.data.province_name;
              if (data.data.province_code) {
                provinceInput.dataset.code = data.data.province_code;
              }
            }
            const cityInput = document.getElementById('city_municipality_code');
            if (cityInput && data.data.city_municipality_name) {
              cityInput.value = data.data.city_municipality_name;
              if (data.data.city_municipality_code) {
                cityInput.dataset.code = data.data.city_municipality_code;
              }
            }
            const barangayInput = document.getElementById('barangay_code');
            if (barangayInput && data.data.barangay_name) {
              barangayInput.value = data.data.barangay_name;
              if (data.data.barangay_code) {
                barangayInput.dataset.code = data.data.barangay_code;
              }
            }

            // Update address fields
            const addressLine1Input = document.getElementById('address_line1');
            if (addressLine1Input && data.data.address_line1) {
              addressLine1Input.value = data.data.address_line1;
            }
            const addressLine2Input = document.getElementById('address_line2');
            if (addressLine2Input && data.data.address_line2) {
              addressLine2Input.value = data.data.address_line2;
            }
            const postalCodeInput = document.getElementById('postal_code');
            if (postalCodeInput && data.data.postal_code) {
              postalCodeInput.value = data.data.postal_code;
            }
            const countryInput = document.getElementById('country');
            if (countryInput && data.data.country) {
              countryInput.value = data.data.country;
            }

            // Update sidebar address
            const sidebarAddressElement = document.getElementById('sidebarAddress');
            if (sidebarAddressElement && data.data) {
              const sidebarAddressParts = [
                data.data.barangay_name || '',
                data.data.city_municipality_name || '',
                data.data.province_name || '',
                data.data.region_name || '',
                data.data.country || ''
              ];
              sidebarAddressElement.textContent = sidebarAddressParts.filter(p => p).join(', ');
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
      const res = await fetch(`api/geocode.php?action=provinces&region_code=${regionCode}`);
      const json = await res.json();
      if (json.success) return json.data;
    } catch (e) {
      console.error('fetchProvinces:', e);
    }
    return [];
  }

  async function fetchCities(provinceCode) {
    try {
      const res = await fetch(`api/geocode.php?action=cities&province_code=${provinceCode}`);
      const json = await res.json();
      if (json.success) return json.data;
    } catch (e) {
      console.error('fetchCities:', e);
    }
    return [];
  }

  async function fetchBarangays(cityCode) {
    try {
      const res = await fetch(`api/geocode.php?action=barangays&city_municipality_code=${cityCode}`);
      const json = await res.json();
      if (json.success) return json.data;
    } catch (e) {
      console.error('fetchBarangays:', e);
    }
    return [];
  }

  function populateDatalist(datalistId, items, nameField) {
    const datalist = document.getElementById(datalistId);
    if (!datalist) return;
    datalist.innerHTML = '';
    items.forEach(item => {
      const option = document.createElement('option');
      option.value = item[nameField];
      option.dataset.code = item.code || item.region_code || item.province_code || item.city_municipality_code || item.barangay_code;
      datalist.appendChild(option);
    });
  }

  function resolveCode(input, datalist, nameField, codeField) {
    const val = input.value.trim();
    if (!val) {
      input.dataset.code = '';
      return '';
    }
    const options = Array.from(datalist.options);
    const match = options.find(opt => opt.value === val);
    if (match) {
      input.dataset.code = match.dataset.code;
      return match.dataset.code;
    }
    input.dataset.code = '';
    return '';
  }

  function clearGeoInput(inputId, datalistId) {
    const input = document.getElementById(inputId);
    const datalist = document.getElementById(datalistId);
    if (input) {
      input.value = '';
      input.dataset.code = '';
    }
    if (datalist) {
      datalist.innerHTML = '';
    }
  }

  async function onRegionChange(prefix) {
    const regionInput = document.getElementById('region_code');
    const regionDatalist = document.getElementById('region_list');
    const regionCode = resolveCode(regionInput, regionDatalist, 'region_name', 'region_code');

    clearGeoInput('province_code', 'province_list');
    clearGeoInput('city_municipality_code', 'city_list');
    clearGeoInput('barangay_code', 'barangay_list');

    if (!regionCode) return;
    const provinces = await fetchProvinces(regionCode);
    populateDatalist('province_list', provinces, 'province_name');
  }

  async function onProvinceChange(prefix) {
    const provinceInput = document.getElementById('province_code');
    const provinceDatalist = document.getElementById('province_list');
    const provinceCode = resolveCode(provinceInput, provinceDatalist, 'province_name', 'province_code');

    clearGeoInput('city_municipality_code', 'city_list');
    clearGeoInput('barangay_code', 'barangay_list');

    if (!provinceCode) return;
    const cities = await fetchCities(provinceCode);
    populateDatalist('city_list', cities, 'city_municipality_name');
  }

  async function onCityChange(prefix) {
    const cityInput = document.getElementById('city_municipality_code');
    const cityDatalist = document.getElementById('city_list');
    const cityCode = resolveCode(cityInput, cityDatalist, 'city_municipality_name', 'city_municipality_code');

    clearGeoInput('barangay_code', 'barangay_list');

    if (!cityCode) return;
    const barangays = await fetchBarangays(cityCode);
    populateDatalist('barangay_list', barangays, 'barangay_name');
  }

  function onBarangayInput(prefix) {
    const barangayInput = document.getElementById('barangay_code');
    const barangayDatalist = document.getElementById('barangay_list');
    resolveCode(barangayInput, barangayDatalist, 'barangay_name', 'barangay_code');
  }

  // Initialize geography dropdowns on page load
  document.addEventListener('DOMContentLoaded', async () => {
    const regions = await fetchRegions();
    populateDatalist('region_list', regions, 'region_name');

    // If user has existing region code, populate dependent dropdowns
    const regionInput = document.getElementById('region_code');
    if (regionInput && regionInput.dataset.code) {
      const provinces = await fetchProvinces(regionInput.dataset.code);
      populateDatalist('province_list', provinces, 'province_name');

      const provinceInput = document.getElementById('province_code');
      if (provinceInput && provinceInput.dataset.code) {
        const cities = await fetchCities(provinceInput.dataset.code);
        populateDatalist('city_list', cities, 'city_municipality_name');

        const cityInput = document.getElementById('city_municipality_code');
        if (cityInput && cityInput.dataset.code) {
          const barangays = await fetchBarangays(cityInput.dataset.code);
          populateDatalist('barangay_list', barangays, 'barangay_name');
        }
      }
    }
  });
</script>