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
        <!-- Main Content Area -->
        <div class="w-full max-w-full px-3 shrink-0 md:w-8/12 md:flex-0">
          <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
              <div class="flex items-center">
                <p class="mb-0 dark:text-white/80">Dashboard Summary</p>
              </div>
            </div>
            <div class="flex-auto p-6">
              <!-- Quick Actions -->
              <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-5 mb-6">
                <h5 class="mb-4 dark:text-white font-semibold text-sm">Quick Actions</h5>
                <div class="flex gap-3">
                  <button class="flex-1 bg-white dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 rounded-lg p-3 text-center transition-colors shadow-sm">
                    <i class="ni ni-calendar-add text-xl text-indigo-600 mb-1"></i>
                    <p class="text-xs dark:text-white">Book New</p>
                  </button>
                  <button class="flex-1 bg-white dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 rounded-lg p-3 text-center transition-colors shadow-sm">
                    <i class="ni ni-calendar-grid-58 text-xl text-emerald-600 mb-1"></i>
                    <p class="text-xs dark:text-white">Calendar</p>
                  </button>
                  <button class="flex-1 bg-white dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 rounded-lg p-3 text-center transition-colors shadow-sm">
                    <i class="ni ni-credit-card text-xl text-purple-600 mb-1"></i>
                    <p class="text-xs dark:text-white">Payments</p>
                  </button>
                  <button class="flex-1 bg-white dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 rounded-lg p-3 text-center transition-colors shadow-sm">
                    <i class="ni ni-favourite-28 text-xl text-pink-600 mb-1"></i>
                    <p class="text-xs dark:text-white">Favorites</p>
                  </button>
                  <button class="flex-1 bg-white dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 rounded-lg p-3 text-center transition-colors shadow-sm">
                    <i class="ni ni-support-16 text-xl text-cyan-600 mb-1"></i>
                    <p class="text-xs dark:text-white">Support</p>
                  </button>
                </div>
              </div>

              <!-- Stats Cards -->
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div style="background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 0.75rem; padding: 1.25rem; color: white; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="font-size: 0.75rem; opacity: 0.8; margin: 0;">Upcoming Bookings</p>
                      <p style="font-size: 1.5rem; font-weight: 700; margin: 0.25rem 0;" id="upcomingBookingsCount">0</p>
                      <p style="font-size: 0.75rem; opacity: 0.7; margin: 0;">Next reservations</p>
                    </div>
                    <i class="ni ni-calendar-grid-58" style="font-size: 2rem; opacity: 0.5;"></i>
                  </div>
                </div>
                <div style="background: linear-gradient(135deg, #10b981, #059669); border-radius: 0.75rem; padding: 1.25rem; color: white; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="font-size: 0.75rem; opacity: 0.8; margin: 0;">Total Reservations</p>
                      <p style="font-size: 1.5rem; font-weight: 700; margin: 0.25rem 0;" id="totalReservationsCount">0</p>
                      <p style="font-size: 0.75rem; opacity: 0.7; margin: 0;">All time bookings</p>
                    </div>
                    <i class="ni ni-bag-17" style="font-size: 2rem; opacity: 0.5;"></i>
                  </div>
                </div>
                <div style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 0.75rem; padding: 1.25rem; color: white; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="font-size: 0.75rem; opacity: 0.8; margin: 0;">Total Payments</p>
                      <p style="font-size: 1.5rem; font-weight: 700; margin: 0.25rem 0;" id="totalPaymentsCount">₱0</p>
                      <p style="font-size: 0.75rem; opacity: 0.7; margin: 0;">All time payments</p>
                    </div>
                    <i class="ni ni-money-coins" style="font-size: 2rem; opacity: 0.5;"></i>
                  </div>
                </div>
                <div style="background: linear-gradient(135deg, #f97316, #ea580c); border-radius: 0.75rem; padding: 1.25rem; color: white; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                  <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <p style="font-size: 0.75rem; opacity: 0.8; margin: 0;">Loyalty Points</p>
                      <p style="font-size: 1.5rem; font-weight: 700; margin: 0.25rem 0;">1,250</p>
                      <p style="font-size: 0.75rem; opacity: 0.7; margin: 0;">Gold Member</p>
                    </div>
                    <i class="ni ni-star-round" style="font-size: 2rem; opacity: 0.5;"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar - Activity Card -->
        <div class="w-full max-w-full px-3 mt-6 shrink-0 md:w-4/12 md:flex-0 md:mt-0">
          <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-4 pb-0">
              <div class="flex gap-2">
                <button class="activity-tab-btn active flex-1 px-4 py-2 text-sm font-semibold text-indigo-600 border-b-2 border-indigo-600" data-activity-tab="checkins">
                  <i class="ni ni-calendar-grid-58 mr-1"></i>Check-ins
                </button>
                <button class="activity-tab-btn flex-1 px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" data-activity-tab="activity">
                  <i class="ni ni-bell-55 mr-1"></i>Activity
                </button>
              </div>
            </div>

            <div class="flex-auto p-6">
              <!-- Upcoming Check-ins Content -->
              <div id="checkins-content" class="activity-content">
                <div class="flex items-center justify-between mb-4">
                  <h5 class="dark:text-white font-semibold text-sm">Upcoming Check-ins</h5>
                  <a href="#" class="text-xs text-indigo-600 hover:text-indigo-700">View All</a>
                </div>
                <div class="space-y-3">
                  <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-700 rounded-lg">
                    <div class="bg-blue-100 text-blue-600 rounded-lg p-2 flex-shrink-0">
                      <i class="ni ni-building text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium dark:text-white truncate">Paradise Resort</p>
                      <p class="text-xs text-gray-500">May 15, 2024 • 3 nights</p>
                    </div>
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Confirmed</span>
                  </div>
                  <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-700 rounded-lg">
                    <div class="bg-purple-100 text-purple-600 rounded-lg p-2 flex-shrink-0">
                      <i class="ni ni-building text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium dark:text-white truncate">Beachside Villa</p>
                      <p class="text-xs text-gray-500">Jun 20, 2024 • 5 nights</p>
                    </div>
                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">Pending</span>
                  </div>
                  <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-700 rounded-lg">
                    <div class="bg-emerald-100 text-emerald-600 rounded-lg p-2 flex-shrink-0">
                      <i class="ni ni-building text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium dark:text-white truncate">Mountain Retreat</p>
                      <p class="text-xs text-gray-500">Jul 10, 2024 • 2 nights</p>
                    </div>
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Confirmed</span>
                  </div>
                </div>
              </div>

              <!-- Recent Activity Content -->
              <div id="activity-content" class="activity-content hidden">
                <div class="flex items-center justify-between mb-4">
                  <h5 class="dark:text-white font-semibold text-sm">Recent Activity</h5>
                  <a href="#" class="text-xs text-indigo-600 hover:text-indigo-700">View All</a>
                </div>
                <div class="space-y-3">
                  <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors">
                    <div class="bg-green-100 text-green-600 rounded-full p-2 flex-shrink-0">
                      <i class="ni ni-check-bold text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium dark:text-white">Payment received</p>
                      <p class="text-xs text-gray-500 mt-0.5">₱5,000 for booking #BK-2024-001</p>
                      <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                    </div>
                  </div>
                  <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors">
                    <div class="bg-blue-100 text-blue-600 rounded-full p-2 flex-shrink-0">
                      <i class="ni ni-calendar-check text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium dark:text-white">Booking confirmed</p>
                      <p class="text-xs text-gray-500 mt-0.5">Paradise Resort reservation</p>
                      <p class="text-xs text-gray-400 mt-1">5 hours ago</p>
                    </div>
                  </div>
                  <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors">
                    <div class="bg-purple-100 text-purple-600 rounded-full p-2 flex-shrink-0">
                      <i class="ni ni-single-02 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium dark:text-white">Profile updated</p>
                      <p class="text-xs text-gray-500 mt-0.5">Profile photo changed</p>
                      <p class="text-xs text-gray-400 mt-1">1 day ago</p>
                    </div>
                  </div>
                  <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors">
                    <div class="bg-orange-100 text-orange-600 rounded-full p-2 flex-shrink-0">
                      <i class="ni ni-gift text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium dark:text-white">Points earned</p>
                      <p class="text-xs text-gray-500 mt-0.5">+100 loyalty points</p>
                      <p class="text-xs text-gray-400 mt-1">3 days ago</p>
                    </div>
                  </div>
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
              <div class="flex items-center justify-between">
                <p class="mb-0 dark:text-white/80">Messages</p>
                <div class="flex gap-2">
                  <button class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg transition-colors" onclick="markAllAsRead()">Mark All Read</button>
                </div>
              </div>
            </div>
            <div class="flex-auto p-6">
              <!-- Message Categories -->
              <div class="flex gap-2 mb-6 flex-wrap">
                <button class="message-filter-btn active px-4 py-2 text-sm font-semibold rounded-lg text-indigo-600" data-filter="all">All</button>
                <button class="message-filter-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700" data-filter="system">System</button>
                <button class="message-filter-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700" data-filter="support">Support</button>
                <button class="message-filter-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700" data-filter="promotional">Promotional</button>
              </div>

              <!-- Messages List -->
              <div id="messagesList" class="space-y-4">
                <!-- System Notifications -->
                <div class="message-item bg-gray-50 dark:bg-slate-800 rounded-xl p-4 border-l-4 border-blue-500 cursor-pointer hover:shadow-md transition-shadow" data-type="system" data-read="false">
                  <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3">
                      <div class="bg-blue-100 text-blue-600 rounded-full p-2.5 flex-shrink-0">
                        <i class="ni ni-bell-55 text-lg"></i>
                      </div>
                      <div>
                        <div class="flex items-center gap-2 mb-1">
                          <span class="font-semibold text-sm dark:text-white">Booking Confirmation</span>
                          <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">System</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Your booking #BK-2024-001 has been confirmed. Check-in: May 15, 2024</p>
                        <p class="text-xs text-gray-400">2 hours ago</p>
                      </div>
                    </div>
                    <div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></div>
                  </div>
                </div>

                <div class="message-item bg-gray-50 dark:bg-slate-800 rounded-xl p-4 border-l-4 border-green-500 cursor-pointer hover:shadow-md transition-shadow" data-type="system" data-read="false">
                  <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3">
                      <div class="bg-green-100 text-green-600 rounded-full p-2.5 flex-shrink-0">
                        <i class="ni ni-credit-card text-lg"></i>
                      </div>
                      <div>
                        <div class="flex items-center gap-2 mb-1">
                          <span class="font-semibold text-sm dark:text-white">Payment Receipt</span>
                          <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">System</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Payment of ₱5,000 received for booking #BK-2024-001</p>
                        <p class="text-xs text-gray-400">5 hours ago</p>
                      </div>
                    </div>
                    <div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></div>
                  </div>
                </div>

                <!-- Support Messages -->
                <div class="message-item bg-gray-50 dark:bg-slate-800 rounded-xl p-4 border-l-4 border-purple-500 cursor-pointer hover:shadow-md transition-shadow" data-type="support" data-read="false">
                  <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3">
                      <div class="bg-purple-100 text-purple-600 rounded-full p-2.5 flex-shrink-0">
                        <i class="ni ni-support-16 text-lg"></i>
                      </div>
                      <div>
                        <div class="flex items-center gap-2 mb-1">
                          <span class="font-semibold text-sm dark:text-white">Support: Room Upgrade Request</span>
                          <span class="bg-purple-500 text-white text-xs px-2 py-0.5 rounded-full">Support</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Admin: We've processed your room upgrade request to Deluxe Suite...</p>
                        <p class="text-xs text-gray-400">1 day ago</p>
                      </div>
                    </div>
                    <div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></div>
                  </div>
                </div>

                <!-- Promotional Updates -->
                <div class="message-item bg-gray-50 dark:bg-slate-800 rounded-xl p-4 border-l-4 border-pink-500 cursor-pointer hover:shadow-md transition-shadow" data-type="promotional" data-read="true">
                  <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3">
                      <div class="bg-pink-100 text-pink-600 rounded-full p-2.5 flex-shrink-0">
                        <i class="ni ni-tag text-lg"></i>
                      </div>
                      <div>
                        <div class="flex items-center gap-2 mb-1">
                          <span class="font-semibold text-sm dark:text-white">Special Offer: 20% Off Summer Bookings</span>
                          <span class="bg-pink-500 text-white text-xs px-2 py-0.5 rounded-full">Promo</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Book now and get 20% off on all summer reservations!</p>
                        <p class="text-xs text-gray-400">2 days ago</p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="message-item bg-gray-50 dark:bg-slate-800 rounded-xl p-4 border-l-4 border-orange-500 cursor-pointer hover:shadow-md transition-shadow" data-type="promotional" data-read="true">
                  <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3">
                      <div class="bg-orange-100 text-orange-600 rounded-full p-2.5 flex-shrink-0">
                        <i class="ni ni-gift text-lg"></i>
                      </div>
                      <div>
                        <div class="flex items-center gap-2 mb-1">
                          <span class="font-semibold text-sm dark:text-white">Loyalty Reward: Free Spa Treatment</span>
                          <span class="bg-pink-500 text-white text-xs px-2 py-0.5 rounded-full">Promo</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">As a valued guest, enjoy a complimentary spa treatment on your next visit!</p>
                        <p class="text-xs text-gray-400">3 days ago</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Empty State -->
              <div id="emptyMessages" class="hidden text-center py-12">
                <i class="ni ni-email-83 text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No messages found</p>
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
              <!-- Settings Navigation -->
              <div class="flex gap-2 mb-6 flex-wrap">
                <button class="settings-nav-btn active px-4 py-2 text-sm font-semibold rounded-lg text-indigo-600" data-section="account">Account</button>
                <button class="settings-nav-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700" data-section="privacy">Privacy</button>
                <button class="settings-nav-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700" data-section="security">Security</button>
                <button class="settings-nav-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700" data-section="payment">Payment</button>
                <button class="settings-nav-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700" data-section="preferences">Preferences</button>
              </div>

              <!-- Settings Sections -->
              <div id="settingsSections">
                <!-- Account Settings -->
                <div class="settings-section" data-section="account">
                  <h5 class="mb-4 text-sm font-semibold dark:text-white">Account Settings</h5>

                  <!-- Change Password -->
                  <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 mb-4">
                    <div class="flex items-center justify-between mb-3">
                      <div class="flex items-center gap-3">
                        <div class="bg-indigo-100 text-indigo-600 rounded-full p-2.5">
                          <i class="ni ni-lock-circle-open text-lg"></i>
                        </div>
                        <div>
                          <p class="font-semibold text-sm dark:text-white">Change Password</p>
                          <p class="text-xs text-gray-500">Update your password regularly</p>
                        </div>
                      </div>
                      <button class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg transition-colors" onclick="showChangePasswordModal()">Change</button>
                    </div>
                  </div>

                  <!-- Email Preferences -->
                  <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-3 mb-4">
                      <div class="bg-blue-100 text-blue-600 rounded-full p-2.5">
                        <i class="ni ni-email-83 text-lg"></i>
                      </div>
                      <div>
                        <p class="font-semibold text-sm dark:text-white">Email Preferences</p>
                        <p class="text-xs text-gray-500">Manage your email notifications</p>
                      </div>
                    </div>
                    <div class="space-y-3">
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded" checked>
                        <span class="text-sm dark:text-white">Booking confirmations</span>
                      </label>
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded" checked>
                        <span class="text-sm dark:text-white">Payment receipts</span>
                      </label>
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded">
                        <span class="text-sm dark:text-white">Promotional emails</span>
                      </label>
                    </div>
                  </div>

                  <!-- Notification Settings -->
                  <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4">
                    <div class="flex items-center gap-3 mb-4">
                      <div class="bg-green-100 text-green-600 rounded-full p-2.5">
                        <i class="ni ni-bell-55 text-lg"></i>
                      </div>
                      <div>
                        <p class="font-semibold text-sm dark:text-white">Notification Settings</p>
                        <p class="text-xs text-gray-500">Choose how you receive notifications</p>
                      </div>
                    </div>
                    <div class="space-y-3">
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded" checked>
                        <span class="text-sm dark:text-white">Push notifications</span>
                      </label>
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded" checked>
                        <span class="text-sm dark:text-white">SMS notifications</span>
                      </label>
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded" checked>
                        <span class="text-sm dark:text-white">In-app notifications</span>
                      </label>
                    </div>
                  </div>
                </div>

                <!-- Privacy Settings -->
                <div class="settings-section hidden" data-section="privacy">
                  <h5 class="mb-4 text-sm font-semibold dark:text-white">Privacy Settings</h5>

                  <!-- Profile Visibility -->
                  <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-3 mb-4">
                      <div class="bg-purple-100 text-purple-600 rounded-full p-2.5">
                        <i class="ni ni-single-02 text-lg"></i>
                      </div>
                      <div>
                        <p class="font-semibold text-sm dark:text-white">Profile Visibility</p>
                        <p class="text-xs text-gray-500">Control who can see your profile</p>
                      </div>
                    </div>
                    <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:text-white dark:border-slate-600">
                      <option>Public</option>
                      <option>Friends Only</option>
                      <option>Private</option>
                    </select>
                  </div>

                  <!-- Data Sharing Preferences -->
                  <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4">
                    <div class="flex items-center gap-3 mb-4">
                      <div class="bg-orange-100 text-orange-600 rounded-full p-2.5">
                        <i class="ni ni-world text-lg"></i>
                      </div>
                      <div>
                        <p class="font-semibold text-sm dark:text-white">Data Sharing Preferences</p>
                        <p class="text-xs text-gray-500">Manage your data sharing settings</p>
                      </div>
                    </div>
                    <div class="space-y-3">
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded">
                        <span class="text-sm dark:text-white">Share data with resort partners</span>
                      </label>
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded" checked>
                        <span class="text-sm dark:text-white">Allow analytics tracking</span>
                      </label>
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded">
                        <span class="text-sm dark:text-white">Personalized recommendations</span>
                      </label>
                    </div>
                  </div>
                </div>

                <!-- Security Settings -->
                <div class="settings-section hidden" data-section="security">
                  <h5 class="mb-4 text-sm font-semibold dark:text-white">Security</h5>

                  <!-- Two-Factor Authentication -->
                  <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 mb-4">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-3">
                        <div class="bg-red-100 text-red-600 rounded-full p-2.5">
                          <i class="ni ni-shield-check text-lg"></i>
                        </div>
                        <div>
                          <p class="font-semibold text-sm dark:text-white">Two-Factor Authentication</p>
                          <p class="text-xs text-gray-500">Add an extra layer of security</p>
                        </div>
                      </div>
                      <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                      </label>
                    </div>
                  </div>

                  <!-- Login History -->
                  <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-3 mb-4">
                      <div class="bg-cyan-100 text-cyan-600 rounded-full p-2.5">
                        <i class="ni ni-time-alarm text-lg"></i>
                      </div>
                      <div>
                        <p class="font-semibold text-sm dark:text-white">Login History</p>
                        <p class="text-xs text-gray-500">Recent login activity</p>
                      </div>
                    </div>
                    <div class="space-y-3">
                      <div class="flex justify-between text-sm">
                        <span class="dark:text-white">Windows 11 - Chrome</span>
                        <span class="text-gray-500">Today, 2:30 PM</span>
                      </div>
                      <div class="flex justify-between text-sm">
                        <span class="dark:text-white">iPhone 15 - Safari</span>
                        <span class="text-gray-500">Yesterday, 9:15 AM</span>
                      </div>
                      <div class="flex justify-between text-sm">
                        <span class="dark:text-white">MacBook Pro - Chrome</span>
                        <span class="text-gray-500">May 10, 2024, 4:45 PM</span>
                      </div>
                    </div>
                  </div>

                  <!-- Active Sessions -->
                  <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4">
                    <div class="flex items-center gap-3 mb-4">
                      <div class="bg-yellow-100 text-yellow-600 rounded-full p-2.5">
                        <i class="ni ni-laptop text-lg"></i>
                      </div>
                      <div>
                        <p class="font-semibold text-sm dark:text-white">Active Sessions</p>
                        <p class="text-xs text-gray-500">Manage your active devices</p>
                      </div>
                    </div>
                    <div class="space-y-3">
                      <div class="flex justify-between items-center">
                        <div>
                          <p class="text-sm dark:text-white">Windows 11 - Chrome</p>
                          <p class="text-xs text-gray-500">Current session</p>
                        </div>
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Active</span>
                      </div>
                      <div class="flex justify-between items-center">
                        <div>
                          <p class="text-sm dark:text-white">iPhone 15 - Safari</p>
                          <p class="text-xs text-gray-500">Last active 1 hour ago</p>
                        </div>
                        <button class="text-xs text-red-600 hover:text-red-700">Revoke</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Payment Settings -->
                <div class="settings-section hidden" data-section="payment">
                  <h5 class="mb-4 text-sm font-semibold dark:text-white">Payment Settings</h5>

                  <!-- Payment Methods -->
                  <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 mb-4">
                    <div class="flex items-center justify-between mb-4">
                      <div class="flex items-center gap-3">
                        <div class="bg-emerald-100 text-emerald-600 rounded-full p-2.5">
                          <i class="ni ni-credit-card text-lg"></i>
                        </div>
                        <div>
                          <p class="font-semibold text-sm dark:text-white">Payment Methods</p>
                          <p class="text-xs text-gray-500">Manage your payment options</p>
                        </div>
                      </div>
                      <button class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg transition-colors">Add New</button>
                    </div>
                    <div class="space-y-3">
                      <div class="flex items-center justify-between p-3 bg-white dark:bg-slate-700 rounded-lg">
                        <div class="flex items-center gap-3">
                          <i class="ni ni-credit-card text-2xl text-blue-600"></i>
                          <div>
                            <p class="text-sm font-medium dark:text-white">•••• •••• •••• 4242</p>
                            <p class="text-xs text-gray-500">Expires 12/25</p>
                          </div>
                        </div>
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Default</span>
                      </div>
                      <div class="flex items-center justify-between p-3 bg-white dark:bg-slate-700 rounded-lg">
                        <div class="flex items-center gap-3">
                          <i class="ni ni-credit-card text-2xl text-purple-600"></i>
                          <div>
                            <p class="text-sm font-medium dark:text-white">•••• •••• •••• 8888</p>
                            <p class="text-xs text-gray-500">Expires 08/26</p>
                          </div>
                        </div>
                        <button class="text-xs text-red-600 hover:text-red-700">Remove</button>
                      </div>
                    </div>
                  </div>

                  <!-- Billing Address -->
                  <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-4">
                      <div class="flex items-center gap-3">
                        <div class="bg-pink-100 text-pink-600 rounded-full p-2.5">
                          <i class="ni ni-pin-3 text-lg"></i>
                        </div>
                        <div>
                          <p class="font-semibold text-sm dark:text-white">Billing Address</p>
                          <p class="text-xs text-gray-500">Update your billing information</p>
                        </div>
                      </div>
                      <button class="text-xs bg-pink-600 hover:bg-pink-700 text-white px-3 py-1.5 rounded-lg transition-colors">Edit</button>
                    </div>
                    <div class="text-sm dark:text-white">
                      <p>John Doe</p>
                      <p>123 Main Street</p>
                      <p>Metro Manila, Philippines 1000</p>
                    </div>
                  </div>
                </div>

                <!-- Preferences -->
                <div class="settings-section hidden" data-section="preferences">
                  <h5 class="mb-4 text-sm font-semibold dark:text-white">Preferences</h5>

                  <!-- Language -->
                  <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-3 mb-3">
                      <div class="bg-teal-100 text-teal-600 rounded-full p-2.5">
                        <i class="ni ni-world text-lg"></i>
                      </div>
                      <div>
                        <p class="font-semibold text-sm dark:text-white">Language</p>
                        <p class="text-xs text-gray-500">Select your preferred language</p>
                      </div>
                    </div>
                    <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:text-white dark:border-slate-600">
                      <option>English</option>
                      <option>Filipino</option>
                      <option>Spanish</option>
                    </select>
                  </div>

                  <!-- Timezone -->
                  <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-3 mb-3">
                      <div class="bg-amber-100 text-amber-600 rounded-full p-2.5">
                        <i class="ni ni-time-alarm text-lg"></i>
                      </div>
                      <div>
                        <p class="font-semibold text-sm dark:text-white">Timezone</p>
                        <p class="text-xs text-gray-500">Set your timezone</p>
                      </div>
                    </div>
                    <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:text-white dark:border-slate-600">
                      <option>UTC+8 (Philippines)</option>
                      <option>UTC+0 (GMT)</option>
                      <option>UTC-5 (Eastern Time)</option>
                    </select>
                  </div>

                  <!-- Currency -->
                  <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-3 mb-3">
                      <div class="bg-lime-100 text-lime-600 rounded-full p-2.5">
                        <i class="ni ni-money-coins text-lg"></i>
                      </div>
                      <div>
                        <p class="font-semibold text-sm dark:text-white">Currency</p>
                        <p class="text-xs text-gray-500">Choose your preferred currency</p>
                      </div>
                    </div>
                    <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:text-white dark:border-slate-600">
                      <option>₱ Philippine Peso (PHP)</option>
                      <option>$ US Dollar (USD)</option>
                      <option>€ Euro (EUR)</option>
                    </select>
                  </div>

                  <!-- Email Notification Frequency -->
                  <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4">
                    <div class="flex items-center gap-3 mb-4">
                      <div class="bg-violet-100 text-violet-600 rounded-full p-2.5">
                        <i class="ni ni-email-83 text-lg"></i>
                      </div>
                      <div>
                        <p class="font-semibold text-sm dark:text-white">Email Notification Frequency</p>
                        <p class="text-xs text-gray-500">How often you receive emails</p>
                      </div>
                    </div>
                    <div class="space-y-2">
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="emailFreq" class="w-4 h-4 text-indigo-600">
                        <span class="text-sm dark:text-white">Immediate</span>
                      </label>
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="emailFreq" class="w-4 h-4 text-indigo-600" checked>
                        <span class="text-sm dark:text-white">Daily digest</span>
                      </label>
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="emailFreq" class="w-4 h-4 text-indigo-600">
                        <span class="text-sm dark:text-white">Weekly summary</span>
                      </label>
                    </div>
                  </div>
                </div>
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
        formData.append('profile_photo', new File([blob], 'profile_photo.jpg', {
          type: 'image/jpeg'
        }));
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
        formData.append('cover_photo', new File([blob], 'cover_photo.jpg', {
          type: 'image/jpeg'
        }));
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

                // Fetch statistics
                fetchStatistics();
              }
            }
          })
          .catch(err => console.error('Error fetching profile data:', err));
      }

      // Fetch statistics via AJAX
      function fetchStatistics() {
        console.log('fetchStatistics() called');

        fetch('api/profile.php?action=get_statistics')
          .then(r => {
            if (!r.ok) {
              throw new Error(`HTTP error! status: ${r.status}`);
            }
            return r.json();
          })
          .then(data => {
            console.log('Statistics response:', data);

            if (data.success && data.data) {
              // Update upcoming bookings count
              const upcomingBookingsElement = document.getElementById('upcomingBookingsCount');
              if (upcomingBookingsElement) {
                const value = data.data.upcoming_bookings || 0;
                upcomingBookingsElement.textContent = value;
                console.log('Updated upcomingBookingsCount to:', value);
              } else {
                console.warn('Element upcomingBookingsCount not found in DOM');
              }

              // Update total reservations count
              const totalReservationsElement = document.getElementById('totalReservationsCount');
              if (totalReservationsElement) {
                const value = data.data.total_reservations || 0;
                totalReservationsElement.textContent = value;
                console.log('Updated totalReservationsCount to:', value);
              } else {
                console.warn('Element totalReservationsCount not found in DOM');
              }

              // Update total payments
              const totalPaymentsElement = document.getElementById('totalPaymentsCount');
              if (totalPaymentsElement) {
                const totalPayments = data.data.total_payments || 0;
                const formatted = '₱' + Number(totalPayments).toLocaleString('en-PH');
                totalPaymentsElement.textContent = formatted;
                console.log('Updated totalPaymentsCount to:', formatted);
              } else {
                console.warn('Element totalPaymentsCount not found in DOM');
              }
            } else {
              console.warn('API returned success=false or no data:', data);
            }
          })
          .catch(err => {
            console.error('Error fetching statistics:', err);
            // Set default values on error
            const upcomingEl = document.getElementById('upcomingBookingsCount');
            const totalResEl = document.getElementById('totalReservationsCount');
            const paymentsEl = document.getElementById('totalPaymentsCount');

            if (upcomingEl) upcomingEl.textContent = '0';
            if (totalResEl) totalResEl.textContent = '0';
            if (paymentsEl) paymentsEl.textContent = '₱0';
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

      // Fetch statistics when app tab is activated or on page load
      document.addEventListener('DOMContentLoaded', function() {
        const tabLinks = document.querySelectorAll('[data-tab]');
        let statsLoaded = false;
        
        // Listen for tab clicks
        tabLinks.forEach(link => {
          link.addEventListener('click', function(e) {
            if (this.getAttribute('data-tab') === 'app' && !statsLoaded) {
              // Small delay to ensure tab is rendered
              setTimeout(() => {
                console.log('App tab clicked - fetching statistics');
                fetchStatistics();
                statsLoaded = true;
              }, 100);
            }
          });
        });
        
        // Also fetch on initial load if app tab is active
        const savedTab = localStorage.getItem('selectedProfileTab');
        if (!savedTab || savedTab === 'app') {
          // Delay to ensure DOM elements are ready
          setTimeout(() => {
            console.log('App tab is default - fetching statistics');
            fetchStatistics();
            statsLoaded = true;
          }, 500);
        }
      });

      /* ─── SETTINGS TAB FUNCTIONALITY ───────────────────────────────────────────────── */
      // Settings navigation functionality
      document.addEventListener('DOMContentLoaded', function() {
        const settingsNavBtns = document.querySelectorAll('.settings-nav-btn');
        const settingsSections = document.querySelectorAll('.settings-section');

        settingsNavBtns.forEach(btn => {
          btn.addEventListener('click', function() {
            // Update active button state
            settingsNavBtns.forEach(b => {
              b.classList.remove('active', 'text-indigo-600', 'font-semibold');
              b.classList.add('bg-gray-100', 'text-gray-700');
            });
            this.classList.add('active', 'text-indigo-600', 'font-semibold');
            this.classList.remove('bg-gray-100', 'text-gray-700');

            // Show/hide settings sections
            const section = this.getAttribute('data-section');
            settingsSections.forEach(sec => {
              if (sec.getAttribute('data-section') === section) {
                sec.classList.remove('hidden');
              } else {
                sec.classList.add('hidden');
              }
            });
          });
        });
      });

      /* ─── MESSAGES TAB FUNCTIONALITY ───────────────────────────────────────────────── */
      // Message filter functionality
      document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.message-filter-btn');
        const messageItems = document.querySelectorAll('.message-item');
        const emptyMessages = document.getElementById('emptyMessages');

        filterButtons.forEach(btn => {
          btn.addEventListener('click', function() {
            // Update active button state
            filterButtons.forEach(b => {
              b.classList.remove('active', 'text-indigo-600', 'font-semibold');
              b.classList.add('bg-gray-100', 'text-gray-700');
            });
            this.classList.add('active', 'text-indigo-600', 'font-semibold');
            this.classList.remove('bg-gray-100', 'text-gray-700');

            // Filter messages
            const filter = this.getAttribute('data-filter');
            let visibleCount = 0;

            messageItems.forEach(item => {
              const itemType = item.getAttribute('data-type');
              if (filter === 'all' || itemType === filter) {
                item.classList.remove('hidden');
                visibleCount++;
              } else {
                item.classList.add('hidden');
              }
            });

            // Show/hide empty state
            if (visibleCount === 0) {
              emptyMessages.classList.remove('hidden');
            } else {
              emptyMessages.classList.add('hidden');
            }
          });
        });

        // Mark message as read on click
        messageItems.forEach(item => {
          item.addEventListener('click', function() {
            const unreadDot = this.querySelector('.w-2.h-2.bg-blue-500');
            if (unreadDot) {
              unreadDot.remove();
              this.setAttribute('data-read', 'true');
            }
          });
        });
      });

      // Mark all messages as read
      function markAllAsRead() {
        const unreadDots = document.querySelectorAll('.message-item .w-2.h-2.bg-blue-500');
        unreadDots.forEach(dot => dot.remove());

        const messageItems = document.querySelectorAll('.message-item');
        messageItems.forEach(item => item.setAttribute('data-read', 'true'));

        Swal.fire({
          icon: 'success',
          title: 'Marked as Read',
          text: 'All messages have been marked as read',
          confirmButtonText: 'OK',
          customClass: {
            confirmButton: 'px-4 py-2 text-sm font-semibold text-white bg-slate-700 hover:bg-slate-800 rounded-lg shadow-md'
          },
          buttonsStyling: false
        });
      }

      /* ─── ACTIVITY TAB FUNCTIONALITY ───────────────────────────────────────────────── */
      // Activity tab switching functionality
      document.addEventListener('DOMContentLoaded', function() {
        const activityTabBtns = document.querySelectorAll('.activity-tab-btn');
        const activityContents = document.querySelectorAll('.activity-content');

        activityTabBtns.forEach(btn => {
          btn.addEventListener('click', function() {
            // Update active button state
            activityTabBtns.forEach(b => {
              b.classList.remove('active', 'text-indigo-600', 'font-semibold', 'border-b-2', 'border-indigo-600');
              b.classList.add('text-gray-500', 'hover:text-gray-700', 'dark:text-gray-400', 'dark:hover:text-gray-200');
            });
            this.classList.add('active', 'text-indigo-600', 'font-semibold', 'border-b-2', 'border-indigo-600');
            this.classList.remove('text-gray-500', 'hover:text-gray-700', 'dark:text-gray-400', 'dark:hover:text-gray-200');

            // Show/hide activity content
            const tab = this.getAttribute('data-activity-tab');
            activityContents.forEach(content => {
              if (content.id === tab + '-content') {
                content.classList.remove('hidden');
              } else {
                content.classList.add('hidden');
              }
            });
          });
        });
      });

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