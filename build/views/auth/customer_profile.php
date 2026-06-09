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

<link
href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Lora:ital,wght@0,500;0,600;1,500;1,600&display=swap"
rel="stylesheet">
<style>
/* ── Customer Profile — Design System ── */
.cp-page {
--cp-gold: #C9A96E;
--cp-gold-dark: #A07840;
--cp-gold-light: #E8D5B0;
--cp-ink: #1A1410;
--cp-ink-soft: #3D3530;
--cp-surface: #FFFFFF;
--cp-surface-2: #FAFAF8;
--cp-border: #EDEAE6;
--cp-border-gold: rgba(201, 169, 110, .22);
--cp-radius-md: 10px;
--cp-radius-lg: 16px;
--cp-radius-xl: 20px;
--cp-shadow: 0 1px 3px rgba(0, 0, 0, .05), 0 4px 16px rgba(0, 0, 0, .07);
font-family: 'DM Sans', system-ui, sans-serif;
color: var(--cp-ink);
}

/* Preserve icon fonts */
.cp-page .ni {
font-family: 'NucleoIcons' !important;
}

.cp-page .fa,
.cp-page .fas,
.cp-page .far,
.cp-page .fab {
font-family: 'Font Awesome 6 Free', 'Font Awesome 6 Brands' !important;
}

/* ── Header card ── */
.cp-page>.relative.flex.flex-col.flex-auto {
background: var(--cp-surface) !important;
border: 1px solid var(--cp-border-gold) !important;
border-radius: var(--cp-radius-xl) !important;
box-shadow: var(--cp-shadow) !important;
}

/* Display name */
.cp-page #profileDisplayName {
font-family: 'Lora', Georgia, serif !important;
font-size: 1.4rem !important;
font-weight: 500 !important;
font-style: italic !important;
color: var(--cp-ink) !important;
}

/* Role label */
.cp-page #profileRoleLabel {
font-size: 0.68rem !important;
font-weight: 600 !important;
letter-spacing: .14em !important;
text-transform: uppercase !important;
color: var(--cp-gold) !important;
opacity: 1 !important;
}

/* ── Tab nav ── */
.cp-page ul[role="tablist"] {
background: var(--cp-surface-2) !important;
border: 1px solid var(--cp-border) !important;
border-radius: var(--cp-radius-lg) !important;
}

.cp-page ul[role="tablist"] a {
font-size: .8rem !important;
font-weight: 500 !important;
color: var(--cp-ink-soft) !important;
border-radius: var(--cp-radius-md) !important;
}

.cp-page ul[role="tablist"] a.active {
background: var(--cp-surface) !important;
color: var(--cp-gold-dark) !important;
font-weight: 600 !important;
box-shadow: 0 1px 4px rgba(0, 0, 0, .08) !important;
}

/* ── Tab content cards ── */
.cp-page .shadow-xl {
box-shadow: var(--cp-shadow) !important;
border: 1px solid var(--cp-border) !important;
border-radius: var(--cp-radius-xl) !important;
}

/* Card section titles */
.cp-page .border-black\/12\.5>.flex>p.mb-0 {
font-family: 'Lora', Georgia, serif !important;
font-size: .95rem !important;
font-weight: 500 !important;
font-style: italic !important;
color: var(--cp-ink) !important;
opacity: 1 !important;
}

.cp-page h5 {
font-family: 'Lora', Georgia, serif !important;
color: var(--cp-ink) !important;
}

/* ── Quick Actions strip ── */
.cp-page .bg-gray-50.rounded-xl {
background: var(--cp-surface-2) !important;
border: 1px solid var(--cp-border) !important;
}

.cp-page .bg-gray-50.rounded-xl h5 {
font-family: 'DM Sans', sans-serif !important;
font-size: .72rem !important;
font-style: normal !important;
letter-spacing: .1em !important;
text-transform: uppercase !important;
color: var(--cp-gold-dark) !important;
}

.cp-page .flex.gap-3>button {
border: 1px solid var(--cp-border) !important;
box-shadow: none !important;
border-radius: var(--cp-radius-md) !important;
}

.cp-page .flex.gap-3>button:hover {
border-color: var(--cp-gold-light) !important;
background: #FFFDF9 !important;
}

.cp-page .flex.gap-3>button>p {
font-size: .72rem !important;
font-weight: 500 !important;
color: var(--cp-ink-soft) !important;
margin-top: 4px !important;
}

/* ── Settings / messages bg strips ── */
.cp-page .bg-gray-50.dark\:bg-slate-800 {
background: var(--cp-surface-2) !important;
border: 1px solid var(--cp-border) !important;
border-radius: var(--cp-radius-lg) !important;
}

.cp-page .settings-section>h5 {
font-family: 'DM Sans', sans-serif !important;
font-size: .65rem !important;
font-style: normal !important;
font-weight: 600 !important;
letter-spacing: .18em !important;
text-transform: uppercase !important;
color: var(--cp-gold-dark) !important;
}

/* ── Form ── */
.cp-page p.leading-normal.uppercase {
font-size: .65rem !important;
font-weight: 600 !important;
letter-spacing: .18em !important;
color: var(--cp-gold) !important;
}

.cp-page label.inline-block.font-bold {
font-size: .7rem !important;
font-weight: 600 !important;
letter-spacing: .06em !important;
text-transform: uppercase !important;
color: var(--cp-ink-soft) !important;
}

.cp-page input.rounded-lg:not([type=checkbox]):not([type=radio]):not([type=file]),
.cp-page select.rounded-lg {
font-size: .85rem !important;
border-color: var(--cp-border) !important;
border-radius: var(--cp-radius-md) !important;
color: var(--cp-ink) !important;
}

.cp-page input.rounded-lg:focus,
.cp-page select.rounded-lg:focus {
border-color: var(--cp-gold) !important;
box-shadow: 0 0 0 3px rgba(201, 169, 110, .15) !important;
}

.cp-page button[type="submit"] {
background: linear-gradient(135deg, var(--cp-gold), var(--cp-gold-dark)) !important;
border-radius: var(--cp-radius-md) !important;
font-size: .8rem !important;
font-weight: 600 !important;
letter-spacing: .06em !important;
box-shadow: 0 2px 8px rgba(201, 169, 110, .35) !important;
}

/* ── Settings action buttons ── */
.cp-page .bg-indigo-600,
.cp-page .bg-emerald-600,
.cp-page .bg-pink-600 {
background: linear-gradient(135deg, var(--cp-gold), var(--cp-gold-dark)) !important;
border-radius: var(--cp-radius-md) !important;
}

.cp-page .bg-blue-500.text-white.px-3 {
background: linear-gradient(135deg, var(--cp-gold), var(--cp-gold-dark)) !important;
border-radius: var(--cp-radius-md) !important;
}

/* ── Profile sidebar ── */
.cp-page .rounded-circle {
border: 3px solid var(--cp-gold-light) !important;
}

.cp-page .bg-cyan-500 {
background: var(--cp-ink) !important;
border-radius: var(--cp-radius-md) !important;
}

.cp-page .bg-slate-700 {
background: var(--cp-ink-soft) !important;
border-radius: var(--cp-radius-md) !important;
}

/* ── Stat counts ── */
.cp-page #upcomingBookingsCount,
.cp-page #totalReservationsCount,
.cp-page #totalPaymentsCount {
font-family: 'Lora', Georgia, serif !important;
}

/* ── Nav filter buttons ── */
.cp-page .settings-nav-btn,
.cp-page .message-filter-btn {
border-radius: var(--cp-radius-md) !important;
}

.cp-page .settings-nav-btn.active,
.cp-page .message-filter-btn.active {
color: var(--cp-gold-dark) !important;
}

.cp-page .activity-tab-btn.active {
color: var(--cp-gold-dark) !important;
border-color: var(--cp-gold-dark) !important;
}

/* ── Messages ── */
.cp-page .message-item {
background: var(--cp-surface-2) !important;
border-radius: var(--cp-radius-lg) !important;
}

/* ── Profile sidebar stats ── */
.cp-page .grid.text-center span.font-bold {
font-family: 'Lora', Georgia, serif !important;
}

/* ── Inner white cards (payment methods etc.) ── */
.cp-page .bg-white.dark\:bg-slate-700.rounded-lg {
border: 1px solid var(--cp-border) !important;
}

/* ── RESPONSIVE ── */
@media (max-width: 480px) {

/* Profile header */
.cp-page .flex.flex-wrap.-mx-3 {
  flex-direction: column;
  text-align: center;
}

.cp-page .flex-none.w-auto.max-w-full.px-3 {
  width: 100%;
  margin-bottom: 1rem;
}

.cp-page .w-full.max-w-full.px-3.mx-auto.mt-4 {
  width: 100%;
  margin-top: 1rem;
}

/* Tab navigation */
.cp-page ul[role="tablist"] {
  flex-direction: column;
}

.cp-page ul[role="tablist"] li {
  width: 100%;
  margin-bottom: 0.25rem;
}

/* Stats cards */
.cp-page .flex.gap-3 {
  flex-direction: column;
}

.cp-page .flex.gap-3 button {
  width: 100%;
}

/* Stats grid */
.cp-page [style*="grid-template-columns: 1fr 1fr"] {
  grid-template-columns: 1fr !important;
}

/* Activity card */
.cp-page .flex.flex-wrap.-mx-3>div {
  width: 100% !important;
  margin-top: 1rem;
}

/* Settings nav */
.cp-page .settings-nav-btn {
  width: 100%;
  margin-bottom: 0.5rem;
}

/* Message filters */
.cp-page .flex.gap-2.mb-6 {
  flex-direction: column;
}

.cp-page .message-filter-btn {
  width: 100%;
}
}

@media (min-width: 481px) and (max-width: 768px) {

/* Tab navigation */
.cp-page ul[role="tablist"] li {
  flex: 1 0 50%;
}

/* Stats grid */
.cp-page [style*="grid-template-columns: 1fr 1fr"] {
  grid-template-columns: 1fr 1fr !important;
}

/* Activity card */
.cp-page .flex.flex-wrap.-mx-3>div {
  width: 100% !important;
  margin-top: 1rem;
}

/* Quick actions */
.cp-page .flex.gap-3 {
  flex-wrap: wrap;
}

.cp-page .flex.gap-3 button {
  flex: 1 0 45%;
}
}

@media (min-width: 769px) and (max-width: 1024px) {

/* Activity card */
.cp-page .flex.flex-wrap.-mx-3>div:nth-child(1) {
  width: 100% !important;
}

.cp-page .flex.flex-wrap.-mx-3>div:nth-child(2) {
  width: 100% !important;
  margin-top: 1rem;
}
}

@media (max-width: 1024px) {

/* Main content padding */
.cp-page .w-full.p-6.mx-auto {
  padding: 1rem;
}

.cp-page .relative.flex.flex-col.flex-auto.min-w-0.p-4 {
  padding: 1rem;
}
}
</style>

<!-- PAGE CONTENT -->
<!-- PAGE CONTENT -->
<div class="cp-page">
<div
class="relative flex flex-col flex-auto min-w-0 p-4 mx-6 overflow-hidden break-words bg-white border-0 dark:bg-slate-850 dark:shadow-dark-xl shadow-3xl rounded-2xl bg-clip-border">
<div class="flex flex-wrap -mx-3">
  <div class="flex-none w-auto max-w-full px-3">
    <div
      class="relative inline-flex items-center justify-center text-white transition-all duration-200 ease-in-out text-base h-19 w-19 rounded-xl">
      <img id="profilePhoto" src="<?php echo htmlspecialchars($profilePhotoUrl); ?>" alt="profile_image"
        class="w-full h-full object-cover shadow-2xl rounded-xl" />
    </div>
  </div>
  <div class="flex-none w-auto max-w-full px-3 my-auto">
    <div class="h-full">
      <h5 id="profileDisplayName" class="mb-1 dark:text-white">
        <?php echo htmlspecialchars($profile['display_name'] ?? ($profile ? ($profile['first_name'] . ' ' . $profile['last_name']) : 'User')); ?>
      </h5>
      <p id="profileRoleLabel" class="mb-0 font-semibold leading-normal dark:text-white dark:opacity-60 text-sm">
        <?php echo htmlspecialchars($user['role_label'] ?? 'User'); ?>
      </p>
    </div>
  </div>
  <div class="w-full max-w-full px-3 mx-auto mt-4 sm:my-auto sm:mr-0 md:w-1/2 md:flex-none lg:w-4/12">
    <div class="relative right-0">
      <ul class="relative flex flex-wrap p-1 list-none bg-gray-50 rounded-xl" role="tablist">
        <li class="z-30 flex-auto text-center">
          <a class="z-30 flex items-center justify-center w-full px-0 py-1 mb-0 transition-all ease-in-out border-0 rounded-lg bg-inherit text-slate-700"
            href="javascript:;" role="tab" data-tab="app" aria-selected="false">
            <i class="ni ni-app"></i>
            <span class="ml-2">App</span>
          </a>
        </li>
        <li class="z-30 flex-auto text-center">
          <a class="z-30 flex items-center justify-center w-full px-0 py-1 mb-0 transition-all ease-in-out border-0 rounded-lg bg-inherit text-slate-700"
            href="javascript:;" role="tab" data-tab="messages" aria-selected="false">
            <i class="ni ni-email-83"></i>
            <span class="ml-2">Messages</span>
          </a>
        </li>
        <li class="z-30 flex-auto text-center">
          <a class="z-30 flex items-center justify-center w-full px-0 py-1 mb-0 transition-all ease-in-out border-0 rounded-lg bg-inherit text-slate-700"
            href="javascript:;" role="tab" data-tab="settings" aria-selected="false">
            <i class="ni ni-settings-gear-65"></i>
            <span class="ml-2">Settings</span>
          </a>
        </li>
        <li class="z-30 flex-auto text-center">
          <a class="z-30 flex items-center justify-center w-full px-0 py-1 mb-0 transition-all ease-in-out border-0 rounded-lg bg-inherit text-slate-700"
            href="javascript:;" role="tab" data-tab="profile" aria-selected="false">
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
        <div
          class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
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
                <button id="btnBookNew"
                  class="flex-1 bg-white dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 rounded-lg p-3 text-center transition-colors shadow-sm">
                  <i class="ni ni-bold-right text-xl text-indigo-600 mb-1"></i>
                  <p class="text-xs dark:text-white">Book New</p>
                </button>
                <button id="btnCalendar"
                  class="flex-1 bg-white dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 rounded-lg p-3 text-center transition-colors shadow-sm">
                  <i class="ni ni-calendar-grid-58 text-xl text-emerald-600 mb-1"></i>
                  <p class="text-xs dark:text-white">Calendar</p>
                </button>
                <button id="btnPayments"
                  class="flex-1 bg-white dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 rounded-lg p-3 text-center transition-colors shadow-sm">
                  <i class="ni ni-credit-card text-xl text-purple-600 mb-1"></i>
                  <p class="text-xs dark:text-white">Payments</p>
                </button>
                <button id="btnFavorites"
                  class="flex-1 bg-white dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 rounded-lg p-3 text-center transition-colors shadow-sm">
                  <i class="ni ni-favourite-28 text-xl text-pink-600 mb-1"></i>
                  <p class="text-xs dark:text-white">Favorites</p>
                </button>
                <button id="btnSupport"
                  class="flex-1 bg-white dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 rounded-lg p-3 text-center transition-colors shadow-sm">
                  <i class="ni ni-support-16 text-xl text-cyan-600 mb-1"></i>
                  <p class="text-xs dark:text-white">Support</p>
                </button>
              </div>
            </div>

            <!-- Stats Cards -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <div
                style="background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 0.75rem; padding: 1.25rem; color: white; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                  <div>
                    <p style="font-size: 0.75rem; opacity: 0.8; margin: 0;">Upcoming Bookings</p>
                    <p style="font-size: 1.5rem; font-weight: 700; margin: 0.25rem 0;" id="upcomingBookingsCount">0
                    </p>
                    <p style="font-size: 0.75rem; opacity: 0.7; margin: 0;">Next reservations</p>
                  </div>
                  <i class="ni ni-calendar-grid-58" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
              </div>
              <div
                style="background: linear-gradient(135deg, #10b981, #059669); border-radius: 0.75rem; padding: 1.25rem; color: white; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                  <div>
                    <p style="font-size: 0.75rem; opacity: 0.8; margin: 0;">Total Reservations</p>
                    <p style="font-size: 1.5rem; font-weight: 700; margin: 0.25rem 0;" id="totalReservationsCount">0
                    </p>
                    <p style="font-size: 0.75rem; opacity: 0.7; margin: 0;">All time bookings</p>
                  </div>
                  <i class="ni ni-bag-17" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
              </div>
              <div
                style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 0.75rem; padding: 1.25rem; color: white; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                  <div>
                    <p style="font-size: 0.75rem; opacity: 0.8; margin: 0;">Total Payments</p>
                    <p style="font-size: 1.5rem; font-weight: 700; margin: 0.25rem 0;" id="totalPaymentsCount">₱0
                    </p>
                    <p style="font-size: 0.75rem; opacity: 0.7; margin: 0;">All time payments</p>
                  </div>
                  <i class="ni ni-money-coins" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
              </div>
              <div
                style="background: linear-gradient(135deg, #f97316, #ea580c); border-radius: 0.75rem; padding: 1.25rem; color: white; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
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
        <div
          class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
          <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-4 pb-0">
            <div class="flex gap-2">
              <button
                class="activity-tab-btn active flex-1 px-4 py-2 text-sm font-semibold text-indigo-600 border-b-2 border-indigo-600"
                data-activity-tab="checkins">
                <i class="ni ni-calendar-grid-58 mr-1"></i>Check-ins
              </button>
              <button
                class="activity-tab-btn flex-1 px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                data-activity-tab="activity">
                <i class="ni ni-bell-55 mr-1"></i>Activity
              </button>
            </div>
          </div>

          <div class="flex-auto p-6">
            <!-- Upcoming Check-ins Content -->
            <div id="checkins-content" class="activity-content">
              <div class="flex items-center justify-between mb-4">
                <h5 class="dark:text-white font-semibold text-sm">Upcoming Check-ins</h5>
                <a href="../customers/reservations.php" class="text-xs text-indigo-600 hover:text-indigo-700">View
                  All</a>
              </div>
              <div id="checkins-list" class="space-y-3">
                <p class="text-xs text-gray-500 dark:text-gray-400">Loading...</p>
              </div>
            </div>

            <!-- Recent Activity Content -->
            <div id="activity-content" class="activity-content hidden">
              <div class="flex items-center justify-between mb-4">
                <h5 class="dark:text-white font-semibold text-sm">Recent Activity</h5>
                <a href="#" class="text-xs text-indigo-600 hover:text-indigo-700">View All</a>
              </div>
              <div id="activity-list" class="space-y-3">
                <p class="text-xs text-gray-500 dark:text-gray-400">Loading...</p>
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
        <div
          class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
          <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
            <div class="flex items-center justify-between">
              <p class="mb-0 dark:text-white/80">Messages</p>
              <div class="flex gap-2">
                <button
                  class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg transition-colors"
                  onclick="markAllAsRead()">Mark All Read</button>
              </div>
            </div>
          </div>
          <div class="flex-auto p-6">
            <!-- Message Categories -->
            <div class="flex gap-2 mb-6 flex-wrap">
              <button class="message-filter-btn active px-4 py-2 text-sm font-semibold rounded-lg text-indigo-600"
                data-filter="all">All</button>
              <button
                class="message-filter-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700"
                data-filter="system">System</button>
              <button
                class="message-filter-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700"
                data-filter="support">Support</button>
              <button
                class="message-filter-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700"
                data-filter="promotional">Promotional</button>
            </div>

            <!-- Messages List -->
            <div id="messagesList" class="space-y-4">
              <p class="text-xs text-gray-500 dark:text-gray-400">Loading messages...</p>
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
        <div
          class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
          <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
            <div class="flex items-center">
              <p class="mb-0 dark:text-white/80">Settings</p>
            </div>
          </div>
          <div class="flex-auto p-6">
            <!-- Settings Navigation -->
            <div class="flex gap-2 mb-6 flex-wrap">
              <button class="settings-nav-btn active px-4 py-2 text-sm font-semibold rounded-lg text-indigo-600"
                data-section="account">Account</button>
              <button
                class="settings-nav-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700"
                data-section="privacy">Privacy</button>
              <button
                class="settings-nav-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700"
                data-section="security">Security</button>
              <button
                class="settings-nav-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700"
                data-section="payment">Payment</button>
              <button
                class="settings-nav-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700"
                data-section="preferences">Preferences</button>
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
                      <div style="margin-left: 10px;">
                        <p class="font-semibold text-sm dark:text-white">Change Password</p>
                        <p class="text-xs text-gray-500">Update your password regularly</p>
                      </div>
                    </div>
                    <button
                      class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg transition-colors"
                      onclick="showChangePasswordModal()">Change</button>
                  </div>
                </div>

                <!-- Email Preferences -->
                <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 mb-4">
                  <div class="flex items-center gap-3 mb-4">
                    <div class="bg-blue-100 text-blue-600 rounded-full p-2.5">
                      <i class="ni ni-email-83 text-lg"></i>
                    </div>
                    <div style="margin-left: 10px;">
                      <p class="font-semibold text-sm dark:text-white">Email Preferences</p>
                      <p class="text-xs text-gray-500">Manage your email notifications</p>
                    </div>
                  </div>
                  <div class="space-y-3 mb-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                      <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded" value="booking_confirmations"
                        checked>
                      <span class="text-sm dark:text-white" style="margin-left: 10px;">Booking confirmations</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                      <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded" value="payment_receipts"
                        checked>
                      <span class="text-sm dark:text-white" style="margin-left: 10px;">Payment receipts</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                      <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded" value="promotional">
                      <span class="text-sm dark:text-white" style="margin-left: 10px;">Promotional emails</span>
                    </label>
                  </div>
                  <div class="border-t border-gray-200 dark:border-slate-600 pt-4">
                    <p class="text-xs font-semibold text-gray-500 mb-3">Email Notification Frequency</p>
                    <div class="space-y-2">
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="emailFreq" class="w-4 h-4 text-indigo-600" value="immediate">
                        <span class="text-sm dark:text-white" style="margin-left: 10px;">Immediate</span>
                      </label>
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="emailFreq" class="w-4 h-4 text-indigo-600" value="daily" checked>
                        <span class="text-sm dark:text-white" style="margin-left: 10px;">Daily digest</span>
                      </label>
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="emailFreq" class="w-4 h-4 text-indigo-600" value="weekly">
                        <span class="text-sm dark:text-white" style="margin-left: 10px;">Weekly summary</span>
                      </label>
                    </div>
                  </div>
                </div>

                <!-- Notification Settings -->
                <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4">
                  <div class="flex items-center gap-3 mb-4">
                    <div class="bg-green-100 text-green-600 rounded-full p-2.5">
                      <i class="ni ni-bell-55 text-lg"></i>
                    </div>
                    <div style="margin-left: 10px;">
                      <p class="font-semibold text-sm dark:text-white">Notification Settings</p>
                      <p class="text-xs text-gray-500">Choose how you receive notifications</p>
                    </div>
                  </div>
                  <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                      <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded" value="push_notifications"
                        checked>
                      <span class="text-sm dark:text-white" style="margin-left: 10px;">Push notifications</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                      <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded" value="sms_notifications"
                        checked>
                      <span class="text-sm dark:text-white" style="margin-left: 10px;">SMS notifications</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                      <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded" value="in_app_notifications"
                        checked>
                      <span class="text-sm dark:text-white" style="margin-left: 10px;">In-app notifications</span>
                    </label>
                  </div>
                  <div class="mt-4">
                    <button type="button" onclick="saveUserSettings()"
                      class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg transition-colors">Save
                      Settings</button>
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
                    <div style="margin-left: 10px;">
                      <p class="font-semibold text-sm dark:text-white">Profile Visibility</p>
                      <p class="text-xs text-gray-500">Control who can see your profile</p>
                    </div>
                  </div>
                  <select
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:text-white dark:border-slate-600"
                    id="profileVisibility">
                    <option value="public">Public</option>
                    <option value="friends">Friends Only</option>
                    <option value="private">Private</option>
                  </select>
                </div>

                <!-- Data Sharing Preferences -->
                <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4">
                  <div class="flex items-center gap-3 mb-4">
                    <div class="bg-orange-100 text-orange-600 rounded-full p-2.5">
                      <i class="ni ni-world text-lg"></i>
                    </div>
                    <div style="margin-left: 10px;">
                      <p class="font-semibold text-sm dark:text-white">Data Sharing Preferences</p>
                      <p class="text-xs text-gray-500">Manage your data sharing settings</p>
                    </div>
                  </div>
                  <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                      <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded" value="share_data_partners">
                      <span class="text-sm dark:text-white" style="margin-left: 10px;">Share data with resort
                        partners</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                      <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded" value="allow_analytics"
                        checked>
                      <span class="text-sm dark:text-white" style="margin-left: 10px;">Allow analytics
                        tracking</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                      <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded"
                        value="personalized_recommendations">
                      <span class="text-sm dark:text-white" style="margin-left: 10px;">Personalized
                        recommendations</span>
                    </label>
                  </div>
                  <div class="mt-4">
                    <button type="button" onclick="saveUserSettings()"
                      class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg transition-colors">Save
                      Settings</button>
                  </div>
                </div>
              </div>

              <!-- Security Settings -->
              <div class="settings-section hidden" data-section="security">
                <h5 class="mb-4 text-sm font-semibold dark:text-white">Security</h5>

                <!-- Two-Factor Authentication -->
                <div class="border border-gray-200 dark:border-slate-600 rounded-xl overflow-hidden mb-4">
                  <!-- Header row -->
                  <div class="flex items-center justify-between gap-3 p-4 bg-white dark:bg-slate-800">
                    <!-- Icon + Text -->
                    <div class="flex items-center gap-3 min-w-0">
                      <div class="bg-red-100 text-red-600 rounded-lg p-2 flex-shrink-0">
                        <i class="ni ni-lock-circle-open text-base"></i>
                      </div>
                      <div class="min-w-0">
                        <p class="font-semibold text-sm dark:text-white">Two-Factor Authentication</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" id="2fa-status-text">Add an extra layer of security to your account</p>
                      </div>
                    </div>
                    <!-- Toggle -->
                    <label class="relative cursor-pointer select-none flex-shrink-0" title="Toggle 2FA">
                      <input type="checkbox" id="2fa-toggle" class="sr-only" onchange="toggle2FA()">
                      <div id="2fa-toggle-track" class="relative rounded-full transition-all duration-300 ease-in-out"
                        style="width:64px;height:32px;background-color:#e5e7eb;border:2px solid #d1d5db;">
                        <span id="2fa-label-off" class="absolute text-[10px] font-bold text-gray-500 transition-opacity duration-200" style="top:50%;transform:translateY(-50%);left:8px;">OFF</span>
                        <span id="2fa-label-on" class="absolute text-[10px] font-bold text-white transition-opacity duration-200" style="top:50%;transform:translateY(-50%);right:8px;opacity:0;">ON</span>
                        <div id="2fa-toggle-thumb"
                          class="absolute bg-white rounded-full shadow transition-all duration-300 ease-in-out"
                          style="width:24px;height:24px;top:2px;left:2px;border:1px solid #d1d5db;transform:translateX(0);">
                        </div>
                      </div>
                    </label>
                  </div>
                  <!-- Description strip -->
                  <div class="px-4 py-3 bg-gray-50 dark:bg-slate-700 border-t border-gray-100 dark:border-slate-600">
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                      <i class="ni ni-satisfied text-gray-400 mr-1"></i>
                      When enabled, you'll be required to enter a one-time code from your authenticator app each time you sign in.
                    </p>
                  </div>
                  <!-- Backup code actions (visible when 2FA is enabled) -->
                  <div id="2fa-actions" class="hidden px-4 py-3 bg-white dark:bg-slate-800 border-t border-gray-200 dark:border-slate-600 flex items-center gap-3">
                    <button onclick="showBackupCodes()" class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-all shadow-sm hover:shadow">
                      <i class="ni ni-bullet-list-67"></i> View Backup Codes
                    </button>
                    <button onclick="regenerateBackupCodes()" class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium text-white bg-gray-600 rounded-lg hover:bg-gray-700 transition-all shadow-sm hover:shadow">
                      <i class="ni ni-settings-gear-65"></i> Regenerate Codes
                    </button>
                  </div>
                </div>

                <!-- Login History -->
                <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 mb-4">
                  <div class="flex items-center gap-3 mb-4">
                    <div class="bg-cyan-100 text-cyan-600 rounded-full p-2.5">
                      <i class="ni ni-time-alarm text-lg"></i>
                    </div>
                    <div style="margin-left: 10px;">
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
                    <div style="margin-left: 10px;">
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
                      <div style="margin-left: 10px;">
                        <p class="font-semibold text-sm dark:text-white">Payment Methods</p>
                        <p class="text-xs text-gray-500">Manage your payment options</p>
                      </div>
                    </div>
                    <button onclick="showAddPaymentMethodModal()"
                      class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg transition-colors">Add
                      New</button>
                  </div>
                  <div id="payment-methods-list" class="space-y-3">
                    <p class="text-sm text-gray-500">Loading payment methods...</p>
                  </div>
                </div>

                <!-- Billing Address -->
                <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4">
                  <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                      <div class="bg-pink-100 text-pink-600 rounded-full p-2.5">
                        <i class="ni ni-pin-3 text-lg"></i>
                      </div>
                      <div style="margin-left: 10px;">
                        <p class="font-semibold text-sm dark:text-white">Billing Address</p>
                        <p class="text-xs text-gray-500">Update your billing information</p>
                      </div>
                    </div>
                    <button
                      class="text-xs bg-pink-600 hover:bg-pink-700 text-white px-3 py-1.5 rounded-lg transition-colors">Edit</button>
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
                    <div style="margin-left: 10px;">
                      <p class="font-semibold text-sm dark:text-white">Language</p>
                      <p class="text-xs text-gray-500">Select your preferred language</p>
                    </div>
                  </div>
                  <select
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:text-white dark:border-slate-600"
                    id="languageSelect">
                    <option value="en">English</option>
                    <option value="fil">Filipino</option>
                    <option value="es">Spanish</option>
                  </select>
                </div>

                <!-- Timezone -->
                <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 mb-4">
                  <div class="flex items-center gap-3 mb-3">
                    <div class="bg-amber-100 text-amber-600 rounded-full p-2.5">
                      <i class="ni ni-time-alarm text-lg"></i>
                    </div>
                    <div style="margin-left: 10px;">
                      <p class="font-semibold text-sm dark:text-white">Timezone</p>
                      <p class="text-xs text-gray-500">Set your timezone</p>
                    </div>
                  </div>
                  <select
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:text-white dark:border-slate-600"
                    id="timezoneSelect">
                    <option value="Asia/Manila">UTC+8 (Philippines)</option>
                    <option value="UTC">UTC+0 (GMT)</option>
                    <option value="America/New_York">UTC-5 (Eastern Time)</option>
                  </select>
                </div>

                <!-- Currency -->
                <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 mb-4">
                  <div class="flex items-center gap-3 mb-3">
                    <div class="bg-lime-100 text-lime-600 rounded-full p-2.5">
                      <i class="ni ni-money-coins text-lg"></i>
                    </div>
                    <div style="margin-left: 10px;">
                      <p class="font-semibold text-sm dark:text-white">Currency</p>
                      <p class="text-xs text-gray-500">Choose your preferred currency</p>
                    </div>
                  </div>
                  <select
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-slate-700 dark:text-white dark:border-slate-600"
                    id="currencySelect">
                    <option value="PHP">₱ Philippine Peso (PHP)</option>
                    <option value="USD">$ US Dollar (USD)</option>
                    <option value="EUR">€ Euro (EUR)</option>
                  </select>
                </div>
                <div class="mt-4">
                  <button type="button" onclick="saveUserSettings()"
                    class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg transition-colors">Save
                    Settings</button>
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
        <div
          class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
          <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
            <div class="flex items-center">
              <p class="mb-0 dark:text-white/80">Edit Profile</p>
            </div>
          </div>
          <div class="flex-auto p-6">
            <form id="profileForm" method="POST" action="">
              <input type="hidden" name="csrf_token"
                value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
              <input type="hidden" name="update_profile" value="1">
              <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm">User Information</p>
              <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                  <div class="mb-4">
                    <label for="username"
                      class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Username</label>
                    <input type="text" id="username" name="username"
                      value="<?php echo htmlspecialchars(explode('@', $user['email'])[0] ?? ''); ?>"
                      class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                      readonly />
                  </div>
                </div>
                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                  <div class="mb-4">
                    <label for="email"
                      class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Email
                      address</label>
                    <input type="email" id="email" name="email"
                      value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                      class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                      readonly />
                  </div>
                </div>
                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                  <div class="mb-4">
                    <label for="first_name"
                      class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">First
                      name</label>
                    <input type="text" id="first_name" name="first_name"
                      value="<?php echo htmlspecialchars($profile['first_name'] ?? ''); ?>"
                      data-orig-value="<?php echo htmlspecialchars($profile['first_name'] ?? ''); ?>"
                      class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                      required />
                  </div>
                </div>
                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                  <div class="mb-4">
                    <label for="last_name"
                      class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Last
                      name</label>
                    <input type="text" id="last_name" name="last_name"
                      value="<?php echo htmlspecialchars($profile['last_name'] ?? ''); ?>"
                      data-orig-value="<?php echo htmlspecialchars($profile['last_name'] ?? ''); ?>"
                      class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                      required />
                  </div>
                </div>
              </div>
              <hr
                class="h-px mx-0 my-4 bg-transparent border-0 opacity-25 bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent " />

              <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm">Contact Information</p>
              <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                  <div class="mb-4">
                    <label for="region_code_display"
                      class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Region</label>
                    <input type="text" id="region_code_display" list="region_list"
                      value="<?php echo htmlspecialchars($profile['region_name'] ?? ''); ?>"
                      data-code="<?php echo htmlspecialchars($profile['region_code'] ?? ''); ?>"
                      class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                      onchange="onRegionChange('profile')" />
                    <input type="hidden" id="region_code" name="region_code"
                      value="<?php echo htmlspecialchars($profile['region_code'] ?? ''); ?>" />
                    <datalist id="region_list"></datalist>
                  </div>
                </div>
                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                  <div class="mb-4">
                    <label for="province_code_display"
                      class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Province</label>
                    <input type="text" id="province_code_display" list="province_list"
                      value="<?php echo htmlspecialchars($profile['province_name'] ?? ''); ?>"
                      data-code="<?php echo htmlspecialchars($profile['province_code'] ?? ''); ?>"
                      class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                      onchange="onProvinceChange('profile')" />
                    <input type="hidden" id="province_code" name="province_code"
                      value="<?php echo htmlspecialchars($profile['province_code'] ?? ''); ?>" />
                    <datalist id="province_list"></datalist>
                  </div>
                </div>
                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                  <div class="mb-4">
                    <label for="city_municipality_code_display"
                      class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">City/Municipality</label>
                    <input type="text" id="city_municipality_code_display" list="city_list"
                      value="<?php echo htmlspecialchars($profile['city_municipality_name'] ?? ''); ?>"
                      data-code="<?php echo htmlspecialchars($profile['city_municipality_code'] ?? ''); ?>"
                      class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                      onchange="onCityChange('profile')" />
                    <input type="hidden" id="city_municipality_code" name="city_municipality_code"
                      value="<?php echo htmlspecialchars($profile['city_municipality_code'] ?? ''); ?>" />
                    <datalist id="city_list"></datalist>
                  </div>
                </div>
                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                  <div class="mb-4">
                    <label for="barangay_code_display"
                      class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Barangay</label>
                    <input type="text" id="barangay_code_display" list="barangay_list"
                      value="<?php echo htmlspecialchars($profile['barangay_name'] ?? ''); ?>"
                      data-code="<?php echo htmlspecialchars($profile['barangay_code'] ?? ''); ?>"
                      class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                      oninput="onBarangayInput('profile')" />
                    <input type="hidden" id="barangay_code" name="barangay_code"
                      value="<?php echo htmlspecialchars($profile['barangay_code'] ?? ''); ?>" />
                    <datalist id="barangay_list"></datalist>
                  </div>
                </div>
                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                  <div class="mb-4">
                    <label for="address_line1"
                      class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Address
                      Line 1</label>
                    <input type="text" id="address_line1" name="address_line1"
                      value="<?php echo htmlspecialchars($profile['address_line1'] ?? ''); ?>"
                      placeholder="House/Unit number, Street name"
                      class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                  </div>
                </div>
                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                  <div class="mb-4">
                    <label for="address_line2"
                      class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Address
                      Line 2</label>
                    <input type="text" id="address_line2" name="address_line2"
                      value="<?php echo htmlspecialchars($profile['address_line2'] ?? ''); ?>"
                      placeholder="Building, Subdivision..."
                      class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                  </div>
                </div>
                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                  <div class="mb-4">
                    <label for="postal_code"
                      class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Postal
                      Code</label>
                    <input type="text" id="postal_code" name="postal_code"
                      value="<?php echo htmlspecialchars($profile['postal_code'] ?? ''); ?>"
                      class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                  </div>
                </div>
                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                  <div class="mb-4">
                    <label for="country"
                      class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Country</label>
                    <input type="text" id="country" name="country"
                      value="<?php echo htmlspecialchars($profile['country'] ?? 'Philippines'); ?>"
                      class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                  </div>
                </div>
              </div>

              <div class="mt-4 text-right">
                <button type="submit"
                  class="inline-block px-8 py-2 mb-4 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">Save
                  Changes</button>
              </div>
            </form>

            <script>
              // Handle profile form submission via AJAX
              document.getElementById('profileForm').addEventListener('submit', function (e) {
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

                fetch('../api/profile.php', {
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
        <div
          class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
          <div class="relative overflow-visible">
            <img id="coverPhoto" class="w-full rounded-t-2xl object-cover h-48"
              src="<?php echo $assets_path; ?>/img/bg-profile.jpg" alt="profile cover image">
            <div class="absolute top-3 right-3 z-10">
              <button type="button"
                class="bg-indigo-600 text-white rounded-lg px-4 py-2 cursor-pointer shadow-md hover:bg-indigo-700 transition-colors text-xs font-semibold flex items-center gap-2 whitespace-nowrap"
                onclick="document.getElementById('coverPhotoInput').click()">
                <span>Edit Cover Photo</span>
              </button>
              <input type="file" id="coverPhotoInput" accept="image/*" class="hidden"
                onchange="handleCoverPhotoUpload(this)">
            </div>
          </div>
          <div class="flex flex-wrap justify-center -mx-3">
            <div class="w-4/12 max-w-full px-3 flex-0 ">
              <div class="mb-6 -mt-6 lg:mb-0 lg:-mt-16">
                <div class="relative">
                  <img id="profilePhotoSidebar"
                    class="h-auto max-w-full border-2 border-white border-solid rounded-circle cursor-pointer hover:opacity-80 transition-opacity"
                    src="<?php echo htmlspecialchars($profilePhotoUrl); ?>" alt="profile image"
                    onclick="document.getElementById('profilePhotoInput').click()">
                  <div
                    class="absolute bottom-0 right-0 bg-indigo-600 text-white rounded-full p-1.5 cursor-pointer shadow-md flex items-center justify-center"
                    onclick="document.getElementById('profilePhotoInput').click()">
                    <span class="text-lg">📷</span>
                  </div>
                  <input type="file" id="profilePhotoInput" accept="image/*" class="hidden"
                    onchange="handleProfilePhotoUpload(this)">
                </div>
              </div>
            </div>
          </div>
          <div class="border-black/12.5 rounded-t-2xl p-6 text-center pt-0 pb-6 lg:pt-2 lg:pb-4">
            <div class="flex justify-between">
              <button type="button"
                class="hidden px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in border-0 rounded-lg shadow-md cursor-pointer text-xs bg-cyan-500 lg:block tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">Connect</button>
              <button type="button"
                class="block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in border-0 rounded-lg shadow-md cursor-pointer text-xs bg-cyan-500 lg:hidden tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                <i class="ni ni-collection text-2.8"></i>
              </button>
              <button type="button"
                class="hidden px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in border-0 rounded-lg shadow-md cursor-pointer text-xs bg-slate-700 lg:block tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">Message</button>
              <button type="button"
                class="block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in border-0 rounded-lg shadow-md cursor-pointer text-xs bg-slate-700 lg:hidden tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
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
                  <span class="font-light">,
                    <?php echo floor((time() - strtotime($profile['date_of_birth'])) / 31557600); ?></span>
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
</div><!-- /cp-page -->

<!-- Profile Photo Upload Script -->
<script>
  // Tab switching functionality
  document.addEventListener('DOMContentLoaded', function () {
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
      tabLink.addEventListener('click', function (e) {
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

    fetch('../api/profile.php?action=update_photo', {
      method: 'POST',
      body: formData
    })
      .then(r => {
        return r.json();
      })
      .then(data => {
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

    fetch('../api/profile.php', {
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
    fetch('../api/profile.php?action=get_profile')
      .then(r => {
        if (!r.ok) {
          throw new Error(`HTTP error! status: ${r.status}`);
        }
        return r.json();
      })
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

            // Populate form fields
            const fields = ['first_name', 'last_name', 'address_line1', 'address_line2', 'country', 'postal_code'];
            fields.forEach(field => {
              const element = document.getElementById(field);
              if (element && data.data[field] !== undefined) {
                element.value = data.data[field];
              }
            });

            // Update region/province/city/barangay display values if using datalist
            // Populate datalists based on profile data
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
              // Populate provinces
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
              // Populate cities (regardless of whether user has selected one)
              fetchCities(data.data.province_code).then(cities => {
                populateDatalist('city_list', cities, 'city_municipality_name');
              }).catch(err => {
                console.error('Error fetching cities:', err);
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
              // Populate barangays (only if user has selected a city)
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

            // Update sidebar address
            const sidebarAddress = document.getElementById('sidebarAddress');
            if (sidebarAddress) {
              const addressParts = [
                data.data.barangay_name || '',
                data.data.city_municipality_name || '',
                data.data.province_name || '',
                data.data.region_name || '',
                data.data.country || ''
              ];
              sidebarAddress.textContent = addressParts.filter(p => p).join(', ');
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
    fetch('../api/profile.php?action=get_statistics')
      .then(r => {
        if (!r.ok) {
          throw new Error(`HTTP error! status: ${r.status}`);
        }
        return r.json();
      })
      .then(data => {
        if (data.success && data.data) {
          // Update upcoming bookings count
          const upcomingBookingsElement = document.getElementById('upcomingBookingsCount');
          if (upcomingBookingsElement) {
            const value = data.data.upcoming_bookings || 0;
            upcomingBookingsElement.textContent = value;
          } else {
            console.warn('Element upcomingBookingsCount not found in DOM');
          }

          // Update total reservations count
          const totalReservationsElement = document.getElementById('totalReservationsCount');
          if (totalReservationsElement) {
            const value = data.data.total_reservations || 0;
            totalReservationsElement.textContent = value;
          } else {
            console.warn('Element totalReservationsCount not found in DOM');
          }

          // Update total payments
          const totalPaymentsElement = document.getElementById('totalPaymentsCount');
          if (totalPaymentsElement) {
            const totalPayments = data.data.total_payments || 0;
            const formatted = '₱' + Number(totalPayments).toLocaleString('en-PH');
            totalPaymentsElement.textContent = formatted;
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

  // Fetch upcoming check-ins
  function fetchUpcomingCheckins() {
    fetch('../api/profile.php?action=get_upcoming_checkins')
      .then(r => r.json())
      .then(data => {
        if (data.success && data.data) {
          const list = document.getElementById('checkins-list');
          if (!list) return;

          if (data.data.length === 0) {
            list.innerHTML = '<p class="text-xs text-gray-500 dark:text-gray-400">No upcoming check-ins</p>';
            return;
          }

          const statusColors = {
            'confirmed': 'bg-green-100 text-green-700',
            'pending': 'bg-yellow-100 text-yellow-700',
            'cancelled': 'bg-red-100 text-red-700',
            'checked_in': 'bg-blue-100 text-blue-700',
            'checked_out': 'bg-gray-100 text-gray-700'
          };

          list.innerHTML = data.data.map(checkin => {
            const checkInDate = new Date(checkin.check_in_date);
            const formattedDate = checkInDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            const statusClass = statusColors[checkin.reservation_status] || 'bg-gray-100 text-gray-700';
            const statusLabel = checkin.reservation_status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());

            return `
              <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-700 rounded-lg">
                <div class="bg-blue-100 text-blue-600 rounded-lg p-2 flex-shrink-0">
                  <i class="ni ni-building text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium dark:text-white truncate">${checkin.property_name}</p>
                  <p class="text-xs text-gray-500">${formattedDate} • ${checkin.nights} night${checkin.nights > 1 ? 's' : ''}</p>
                </div>
                <span class="text-xs ${statusClass} px-2 py-1 rounded-full">${statusLabel}</span>
              </div>
            `;
          }).join('');
        }
      })
      .catch(err => {
        console.error('Error fetching check-ins:', err);
        const list = document.getElementById('checkins-list');
        if (list) list.innerHTML = '<p class="text-xs text-gray-500 dark:text-gray-400">Error loading check-ins</p>';
      });
  }

  // Fetch recent activity
  function fetchRecentActivity() {
    fetch('../api/profile.php?action=get_recent_activity')
      .then(r => r.json())
      .then(data => {
        if (data.success && data.data) {
          const list = document.getElementById('activity-list');
          if (!list) return;

          if (data.data.length === 0) {
            list.innerHTML = '<p class="text-xs text-gray-500 dark:text-gray-400">No recent activity</p>';
            return;
          }

          const iconColors = {
            'payment': 'bg-green-100 text-green-600',
            'booking': 'bg-blue-100 text-blue-600'
          };

          const icons = {
            'payment': 'ni-check-bold',
            'booking': 'ni-calendar-check'
          };

          list.innerHTML = data.data.map(activity => {
            const iconClass = iconColors[activity.type] || 'bg-gray-100 text-gray-600';
            const icon = icons[activity.type] || 'ni-bell-55';
            const activityDate = new Date(activity.created_at);
            const now = new Date();
            const diffMs = now - activityDate;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);

            let timeAgo;
            if (diffMins < 60) {
              timeAgo = `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`;
            } else if (diffHours < 24) {
              timeAgo = `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
            } else {
              timeAgo = `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
            }

            return `
              <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors">
                <div class="${iconClass} rounded-full p-2 flex-shrink-0">
                  <i class="ni ${icon} text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium dark:text-white">${activity.description}</p>
                  <p class="text-xs text-gray-500 mt-0.5">${activity.details}</p>
                  <p class="text-xs text-gray-400 mt-1">${timeAgo}</p>
                </div>
              </div>
            `;
          }).join('');
        }
      })
      .catch(err => {
        console.error('Error fetching activity:', err);
        const list = document.getElementById('activity-list');
        if (list) list.innerHTML = '<p class="text-xs text-gray-500 dark:text-gray-400">Error loading activity</p>';
      });
  }

  // Normalize profile photo URL based on environment
  function normalizeProfilePhotoUrl(url) {
    if (!url || url.includes('default_profile')) {
      return '<?php echo $defaultProfilePath; ?>';
    }

    // Unescape JSON escaped backslashes
    url = url.replace(/\\\//g, '/');

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
  document.addEventListener('DOMContentLoaded', function () {
    const tabLinks = document.querySelectorAll('[data-tab]');
    let statsLoaded = false;

    // Listen for tab clicks
    tabLinks.forEach(link => {
      link.addEventListener('click', function (e) {
        if (this.getAttribute('data-tab') === 'app' && !statsLoaded) {
          // Small delay to ensure tab is rendered
          setTimeout(() => {
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
        fetchStatistics();
        statsLoaded = true;
      }, 500);
    }

    // Quick Actions button handlers
    document.getElementById('btnBookNew')?.addEventListener('click', function () {
      window.location.href = '../customers/reservations.php';
    });

    document.getElementById('btnCalendar')?.addEventListener('click', function () {
      // Navigate to reservations page which has calendar view
      window.location.href = '../customers/reservations.php';
    });

    document.getElementById('btnPayments')?.addEventListener('click', function () {
      window.location.href = '../customers/payments.php';
    });

    document.getElementById('btnFavorites')?.addEventListener('click', function () {
      // Show placeholder alert for favorites
      Swal.fire({
        icon: 'info',
        title: 'Favorites',
        text: 'Favorites feature coming soon!',
        confirmButtonColor: '#C9A96E'
      });
    });

    document.getElementById('btnSupport')?.addEventListener('click', function () {
      // Navigate to contact inquiries or show support modal
      window.location.href = '../contact-inquiries/index.php';
    });
  });

  /* ─── SETTINGS TAB FUNCTIONALITY ───────────────────────────────────────────────── */
  // Settings navigation functionality
  let settingsLoaded = false;

  document.addEventListener('DOMContentLoaded', function () {
    const settingsNavBtns = document.querySelectorAll('.settings-nav-btn');
    const settingsSections = document.querySelectorAll('.settings-section');

    settingsNavBtns.forEach(btn => {
      btn.addEventListener('click', function () {
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

  // Load settings when settings tab is activated
  const tabLinks = document.querySelectorAll('[data-tab]');
  tabLinks.forEach(link => {
    link.addEventListener('click', function () {
      if (this.getAttribute('data-tab') === 'settings' && !settingsLoaded) {
        setTimeout(() => {
          loadUserSettings();
          settingsLoaded = true;
        }, 100);
      }
    });
  });

  // Load settings on initial load if settings tab is active
  const savedTab = localStorage.getItem('selectedProfileTab');
  if (savedTab === 'settings') {
    setTimeout(() => {
      loadUserSettings();
      settingsLoaded = true;
    }, 500);
  }

  // Load user settings from API
  async function loadUserSettings() {
    try {
      const response = await fetch('../api/user_settings.php?action=get_settings');
      const result = await response.json();

      if (result.success && result.data) {
        applySettings(result.data);
      }
    } catch (error) {
      console.error('Error loading settings:', error);
    }
  }

  // Apply loaded settings to form elements
  function applySettings(settings) {
    // Email preferences
    if (settings.email_booking_confirmations !== undefined) {
      const checkbox = document.querySelector('input[type="checkbox"][value="booking_confirmations"]');
      if (checkbox) checkbox.checked = settings.email_booking_confirmations;
    }
    if (settings.email_payment_receipts !== undefined) {
      const checkbox = document.querySelector('input[type="checkbox"][value="payment_receipts"]');
      if (checkbox) checkbox.checked = settings.email_payment_receipts;
    }
    if (settings.email_promotional !== undefined) {
      const checkbox = document.querySelector('input[type="checkbox"][value="promotional"]');
      if (checkbox) checkbox.checked = settings.email_promotional;
    }

    // Email frequency
    if (settings.email_frequency) {
      const radio = document.querySelector(`input[name="emailFreq"][value="${settings.email_frequency}"]`);
      if (radio) radio.checked = true;
    }

    // Notification settings
    if (settings.push_notifications !== undefined) {
      const checkbox = document.querySelector('input[type="checkbox"][value="push_notifications"]');
      if (checkbox) checkbox.checked = settings.push_notifications;
    }
    if (settings.sms_notifications !== undefined) {
      const checkbox = document.querySelector('input[type="checkbox"][value="sms_notifications"]');
      if (checkbox) checkbox.checked = settings.sms_notifications;
    }
    if (settings.in_app_notifications !== undefined) {
      const checkbox = document.querySelector('input[type="checkbox"][value="in_app_notifications"]');
      if (checkbox) checkbox.checked = settings.in_app_notifications;
    }

    // Privacy settings
    if (settings.profile_visibility) {
      const select = document.getElementById('profileVisibility');
      if (select) select.value = settings.profile_visibility;
    }
    if (settings.share_data_partners !== undefined) {
      const checkbox = document.querySelector('input[type="checkbox"][value="share_data_partners"]');
      if (checkbox) checkbox.checked = settings.share_data_partners;
    }
    if (settings.allow_analytics !== undefined) {
      const checkbox = document.querySelector('input[type="checkbox"][value="allow_analytics"]');
      if (checkbox) checkbox.checked = settings.allow_analytics;
    }
    if (settings.personalized_recommendations !== undefined) {
      const checkbox = document.querySelector('input[type="checkbox"][value="personalized_recommendations"]');
      if (checkbox) checkbox.checked = settings.personalized_recommendations;
    }

    // Preferences
    if (settings.language) {
      const select = document.getElementById('languageSelect');
      if (select) select.value = settings.language;
    }
    if (settings.timezone) {
      const select = document.getElementById('timezoneSelect');
      if (select) select.value = settings.timezone;
    }
    if (settings.currency) {
      const select = document.getElementById('currencySelect');
      if (select) select.value = settings.currency;
    }
  }

  // Save user settings
  async function saveUserSettings() {
    const settings = {};

    // Email preferences
    const bookingConfirmations = document.querySelector('input[type="checkbox"][value="booking_confirmations"]');
    if (bookingConfirmations) settings.email_booking_confirmations = bookingConfirmations.checked;

    const paymentReceipts = document.querySelector('input[type="checkbox"][value="payment_receipts"]');
    if (paymentReceipts) settings.email_payment_receipts = paymentReceipts.checked;

    const promotional = document.querySelector('input[type="checkbox"][value="promotional"]');
    if (promotional) settings.email_promotional = promotional.checked;

    const emailFreq = document.querySelector('input[name="emailFreq"]:checked');
    if (emailFreq) settings.email_frequency = emailFreq.value;

    // Notification settings
    const pushNotif = document.querySelector('input[type="checkbox"][value="push_notifications"]');
    if (pushNotif) settings.push_notifications = pushNotif.checked;

    const smsNotif = document.querySelector('input[type="checkbox"][value="sms_notifications"]');
    if (smsNotif) settings.sms_notifications = smsNotif.checked;

    const inAppNotif = document.querySelector('input[type="checkbox"][value="in_app_notifications"]');
    if (inAppNotif) settings.in_app_notifications = inAppNotif.checked;

    // Privacy settings
    const profileVisibility = document.getElementById('profileVisibility');
    if (profileVisibility) settings.profile_visibility = profileVisibility.value;

    const shareData = document.querySelector('input[type="checkbox"][value="share_data_partners"]');
    if (shareData) settings.share_data_partners = shareData.checked;

    const analytics = document.querySelector('input[type="checkbox"][value="allow_analytics"]');
    if (analytics) settings.allow_analytics = analytics.checked;

    const recommendations = document.querySelector('input[type="checkbox"][value="personalized_recommendations"]');
    if (recommendations) settings.personalized_recommendations = recommendations.checked;

    // Preferences
    const language = document.getElementById('languageSelect');
    if (language) settings.language = language.value;

    const timezone = document.getElementById('timezoneSelect');
    if (timezone) settings.timezone = timezone.value;

    const currency = document.getElementById('currencySelect');
    if (currency) settings.currency = currency.value;

    try {
      const response = await fetch('../api/user_settings.php?action=save_settings', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ settings: settings })
      });

      const result = await response.json();

      if (result.success) {
        showToast('Settings saved successfully!');
      } else {
        showToast('Failed to save settings: ' + (result.message || 'Unknown error'), 'error');
      }
    } catch (error) {
      console.error('Error saving settings:', error);
      showToast('Failed to save settings. Please try again.', 'error');
    }
  }

  /* ─── MESSAGES TAB FUNCTIONALITY ───────────────────────────────────────────────── */
  // Message filter functionality
  document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.message-filter-btn');
    const messageItems = document.querySelectorAll('.message-item');
    const emptyMessages = document.getElementById('emptyMessages');

    filterButtons.forEach(btn => {
      btn.addEventListener('click', function () {
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
      item.addEventListener('click', function () {
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
  document.addEventListener('DOMContentLoaded', function () {
    const activityTabBtns = document.querySelectorAll('.activity-tab-btn');
    const activityContents = document.querySelectorAll('.activity-content');
    let checkinsLoaded = false;
    let activityLoaded = false;

    activityTabBtns.forEach(btn => {
      btn.addEventListener('click', function () {
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

        // Fetch data when tab is switched
        if (tab === 'checkins' && !checkinsLoaded) {
          fetchUpcomingCheckins();
          checkinsLoaded = true;
        } else if (tab === 'activity' && !activityLoaded) {
          fetchRecentActivity();
          activityLoaded = true;
        }
      });
    });

    // Load check-ins on initial load if app tab is active
    const savedTab = localStorage.getItem('selectedProfileTab');
    if (!savedTab || savedTab === 'app') {
      setTimeout(() => {
        fetchUpcomingCheckins();
        checkinsLoaded = true;
      }, 600);
    }
  });

  /* ─── GEOCODE DROPDOWNS ─────────────────────────────────────────────────────────── */
  let regionsData = [];

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
      const res = await fetch(`../api/geocode.php?action=cities_municipalities&province_code=${provinceCode}`);
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

  /* ─── MESSAGES TAB FUNCTIONALITY ───────────────────────────────────────────────── */
  let currentMessageFilter = 'all';
  let messagesLoaded = false;

  function fetchMessages(filter = 'all') {
    currentMessageFilter = filter;
    fetch(`../api/profile.php?action=get_messages&filter=${filter}`)
      .then(r => r.json())
      .then(data => {
        if (data.success && data.data) {
          renderMessages(data.data);
        } else {
          document.getElementById('messagesList').innerHTML = '<p class="text-xs text-gray-500 dark:text-gray-400">Error loading messages</p>';
        }
      })
      .catch(err => {
        console.error('Error fetching messages:', err);
        document.getElementById('messagesList').innerHTML = '<p class="text-xs text-gray-500 dark:text-gray-400">Error loading messages</p>';
      });
  }

  function renderMessages(messages) {
    const list = document.getElementById('messagesList');
    const emptyState = document.getElementById('emptyMessages');

    if (!list) return;

    if (messages.length === 0) {
      list.innerHTML = '';
      emptyState.classList.remove('hidden');
      return;
    }

    emptyState.classList.add('hidden');

    const categoryColors = {
      'system': { border: 'border-blue-500', bg: 'bg-blue-100', text: 'text-blue-600', badge: 'bg-blue-500' },
      'support': { border: 'border-purple-500', bg: 'bg-purple-100', text: 'text-purple-600', badge: 'bg-purple-500' },
      'promotional': { border: 'border-pink-500', bg: 'bg-pink-100', text: 'text-pink-600', badge: 'bg-pink-500' }
    };

    const categoryIcons = {
      'system': 'ni-bell-55',
      'support': 'ni-support-16',
      'promotional': 'ni-tag'
    };

    const categoryLabels = {
      'system': 'System',
      'support': 'Support',
      'promotional': 'Promo'
    };

    list.innerHTML = messages.map(msg => {
      const colors = categoryColors[msg.category] || categoryColors['system'];
      const icon = categoryIcons[msg.category] || categoryIcons['system'];
      const label = categoryLabels[msg.category] || 'System';
      const msgDate = new Date(msg.created_at);
      const now = new Date();
      const diffMs = now - msgDate;
      const diffMins = Math.floor(diffMs / 60000);
      const diffHours = Math.floor(diffMs / 3600000);
      const diffDays = Math.floor(diffMs / 86400000);

      let timeAgo;
      if (diffMins < 60) {
        timeAgo = `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`;
      } else if (diffHours < 24) {
        timeAgo = `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
      } else {
        timeAgo = `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
      }

      return `
        <div class="message-item bg-gray-50 dark:bg-slate-800 rounded-xl p-4 border-l-4 ${colors.border} cursor-pointer hover:shadow-md transition-shadow" 
              data-recipient-id="${msg.recipient_id}" 
              data-read="${msg.is_read ? 'true' : 'false'}"
              onclick="markAsRead('${msg.recipient_id}', this)">
          <div class="flex items-start justify-between">
            <div class="flex items-start gap-4">
              <div class="${colors.bg} ${colors.text} rounded-full p-3 flex-shrink-0">
                <i class="ni ${icon} text-lg"></i>
              </div>
              <div>
                <div class="flex items-center gap-4 mb-1">
                  <span class="font-semibold text-sm dark:text-white">${msg.title}</span>
                  <span class="${colors.badge} text-white text-xs px-2.5 py-0.5 rounded-full" style="margin-left: 10px;">${label}</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">${msg.body}</p>
                <p class="text-xs text-gray-400">${timeAgo}</p>
              </div>
            </div>
            ${!msg.is_read ? '<div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></div>' : ''}
          </div>
        </div>
      `;
    }).join('');
  }

  function markAsRead(recipientId, element) {
    const formData = new FormData();
    formData.append('recipient_id', recipientId);
    formData.append('action', 'mark_message_read');
    formData.append('csrf_token', document.querySelector('input[name="csrf_token"]')?.value || '');

    fetch('../api/profile.php', {
      method: 'POST',
      body: formData
    })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          element.dataset.read = 'true';
          const unreadDot = element.querySelector('.w-2.h-2.bg-blue-500');
          if (unreadDot) unreadDot.remove();
        }
      })
      .catch(err => console.error('Error marking as read:', err));
  }

  function markAllAsRead() {
    const formData = new FormData();
    formData.append('action', 'mark_all_read');
    formData.append('csrf_token', document.querySelector('input[name="csrf_token"]')?.value || '');

    fetch('../api/profile.php', {
      method: 'POST',
      body: formData
    })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'All messages marked as read',
            confirmButtonColor: '#C9A96E'
          });
          fetchMessages(currentMessageFilter);
        }
      })
      .catch(err => console.error('Error marking all as read:', err));
  }

  // Message filter functionality
  document.addEventListener('DOMContentLoaded', function () {
    const filterBtns = document.querySelectorAll('.message-filter-btn');
    filterBtns.forEach(btn => {
      btn.addEventListener('click', function () {
        filterBtns.forEach(b => {
          b.classList.remove('active', 'text-indigo-600', 'font-semibold');
          b.classList.add('bg-gray-100', 'text-gray-700');
        });
        this.classList.add('active', 'text-indigo-600', 'font-semibold');
        this.classList.remove('bg-gray-100', 'text-gray-700');

        const filter = this.getAttribute('data-filter');
        fetchMessages(filter);
      });
    });

    // Load messages when messages tab is activated
    const tabLinks = document.querySelectorAll('[data-tab]');
    tabLinks.forEach(link => {
      link.addEventListener('click', function () {
        if (this.getAttribute('data-tab') === 'messages' && !messagesLoaded) {
          setTimeout(() => {
            fetchMessages('all');
            messagesLoaded = true;
          }, 100);
        }
      });
    });

    // Load messages on initial load if messages tab is active
    const savedTab = localStorage.getItem('selectedProfileTab');
    if (savedTab === 'messages') {
      setTimeout(() => {
        fetchMessages('all');
        messagesLoaded = true;
      }, 500);
    }
  });

  function clearGeoInput(inputId, datalistId, hiddenInputId) {
    const input = document.getElementById(inputId);
    const datalist = document.getElementById(datalistId);
    const hidden = document.getElementById(hiddenInputId);
    if (input) {
      input.value = '';
      input.dataset.code = '';
    }
    if (hidden) {
      hidden.value = '';
    }
    if (datalist) {
      datalist.innerHTML = '';
    }
  }

  async function onRegionChange(prefix) {
    const regionInput = document.getElementById('region_code_display');
    const regionDatalist = document.getElementById('region_list');
    const regionCode = resolveCode(regionInput, regionDatalist, 'region_name', 'region_code');

    // Update hidden input
    const regionHidden = document.getElementById('region_code');
    if (regionHidden) regionHidden.value = regionCode || '';

    clearGeoInput('province_code_display', 'province_list', 'province_code');
    clearGeoInput('city_municipality_code_display', 'city_list', 'city_municipality_code');
    clearGeoInput('barangay_code_display', 'barangay_list', 'barangay_code');

    if (!regionCode) return;
    const provinces = await fetchProvinces(regionCode);
    populateDatalist('province_list', provinces, 'province_name');
  }

  async function onProvinceChange(prefix) {
    const provinceInput = document.getElementById('province_code_display');
    const provinceDatalist = document.getElementById('province_list');
    const provinceCode = resolveCode(provinceInput, provinceDatalist, 'province_name', 'province_code');

    // Update hidden input
    const provinceHidden = document.getElementById('province_code');
    if (provinceHidden) provinceHidden.value = provinceCode || '';

    clearGeoInput('city_municipality_code_display', 'city_list', 'city_municipality_code');
    clearGeoInput('barangay_code_display', 'barangay_list', 'barangay_code');

    if (!provinceCode) return;
    const cities = await fetchCities(provinceCode);
    populateDatalist('city_list', cities, 'city_municipality_name');
  }

  async function onCityChange(prefix) {
    const cityInput = document.getElementById('city_municipality_code_display');
    const cityDatalist = document.getElementById('city_list');
    const cityCode = resolveCode(cityInput, cityDatalist, 'city_municipality_name', 'city_municipality_code');

    // Update hidden input
    const cityHidden = document.getElementById('city_municipality_code');
    if (cityHidden) cityHidden.value = cityCode || '';

    clearGeoInput('barangay_code_display', 'barangay_list', 'barangay_code');

    if (!cityCode) return;
    const barangays = await fetchBarangays(cityCode);
    populateDatalist('barangay_list', barangays, 'barangay_name');
  }

  function onBarangayInput(prefix) {
    const barangayInput = document.getElementById('barangay_code_display');
    const barangayDatalist = document.getElementById('barangay_list');
    const barangayCode = resolveCode(barangayInput, barangayDatalist, 'barangay_name', 'barangay_code');

    // Update hidden input
    const barangayHidden = document.getElementById('barangay_code');
    if (barangayHidden) barangayHidden.value = barangayCode || '';
  }

  // Initialize geography dropdowns on page load
  document.addEventListener('DOMContentLoaded', async () => {
    const regions = await fetchRegions();
    populateDatalist('region_list', regions, 'region_name');

    // If user has existing region code, populate dependent dropdowns
    const regionInput = document.getElementById('region_code_display');
    if (regionInput && regionInput.dataset.code) {
      const provinces = await fetchProvinces(regionInput.dataset.code);
      populateDatalist('province_list', provinces, 'province_name');

      const provinceInput = document.getElementById('province_code_display');
      if (provinceInput && provinceInput.dataset.code) {
        const cities = await fetchCities(provinceInput.dataset.code);
        populateDatalist('city_list', cities, 'city_municipality_name');

        const cityInput = document.getElementById('city_municipality_code_display');
        if (cityInput && cityInput.dataset.code) {
          const barangays = await fetchBarangays(cityInput.dataset.code);
          populateDatalist('barangay_list', barangays, 'barangay_name');
        }
      }
    }

    // Load 2FA status
    load2FAStatus();

    // Load payment methods when payment section is activated
    const tabButtons = document.querySelectorAll('[role="tab"]');
    tabButtons.forEach(button => {
      button.addEventListener('click', function () {
        if (this.getAttribute('data-tab') === 'settings') {
          const settingsNavBtns = document.querySelectorAll('.settings-nav-btn');
          settingsNavBtns.forEach(btn => {
            btn.addEventListener('click', function () {
              if (this.getAttribute('data-section') === 'payment') {
                loadPaymentMethods();
              }
            });
          });
        }
      });
    });
  });

  /* ─── TWO-FACTOR AUTHENTICATION ─────────────────────────────────────────────── */
  async function load2FAStatus() {
    try {
      const res = await fetch('../api/2fa.php?action=status');
      const result = await res.json();
      if (result.success) {
        const toggle = document.getElementById('2fa-toggle');
        const statusText = document.getElementById('2fa-status-text');
        const actions = document.getElementById('2fa-actions');
        const track = document.getElementById('2fa-toggle-track');
        const thumb = document.getElementById('2fa-toggle-thumb');

        if (toggle) {
          toggle.checked = result.data.enabled;
          updateToggleVisual(result.data.enabled);
        }
        if (statusText) statusText.textContent = result.data.enabled ? '2FA is enabled' : 'Add an extra layer of security';
        if (actions && result.data.enabled) actions.classList.remove('hidden');
      }
    } catch (error) {
      console.error('Failed to load 2FA status:', error);
    }
  }

  function updateToggleVisual(isChecked) {
    const track = document.getElementById('2fa-toggle-track');
    const thumb = document.getElementById('2fa-toggle-thumb');
    const labelOff = document.getElementById('2fa-label-off');
    const labelOn = document.getElementById('2fa-label-on');

    if (track && thumb) {
      if (isChecked) {
        // ON state - Green with white labels
        track.style.backgroundColor = '#10b981'; // emerald-500
        track.style.borderColor = '#059669';
        thumb.style.transform = 'translateX(36px)';
        thumb.style.borderColor = '#059669';

        // Labels
        if (labelOff) labelOff.style.opacity = '0';
        if (labelOn) labelOn.style.opacity = '1';
      } else {
        // OFF state - Gray with dark labels
        track.style.backgroundColor = '#e5e7eb'; // gray-200
        track.style.borderColor = '#d1d5db';
        thumb.style.transform = 'translateX(0)';
        thumb.style.borderColor = '#d1d5db';

        // Labels
        if (labelOff) labelOff.style.opacity = '1';
        if (labelOn) labelOn.style.opacity = '0';
      }
    }
  }

  async function toggle2FA() {
    const toggle = document.getElementById('2fa-toggle');
    const isEnabling = toggle.checked;

    // Update visual immediately
    updateToggleVisual(isEnabling);

    if (isEnabling) {
      // Setup 2FA
      try {
        const res = await fetch('../api/2fa.php?action=setup');
        const result = await res.json();
        if (result.success) {
          // Show QR code modal
          showQRCodeModal(result.data.qr_code_svg, result.data.manual_entry_key);
        } else {
          showToast('Failed to setup 2FA: ' + result.message, 'error');
          toggle.checked = false;
          updateToggleVisual(false);
        }
      } catch (error) {
        console.error('Failed to setup 2FA:', error);
        showToast('Failed to setup 2FA', 'error');
        toggle.checked = false;
        updateToggleVisual(false);
      }
    } else {
      // Disable 2FA
      const { value: password } = await Swal.fire({
        title: '',
        html: `
          <div style="text-align: center; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 16px;">
              <div style="background: #C9A96E; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="ni ni-lock-circle-open text-white text-xl"></i>
              </div>
              <h3 style="margin: 0; font-family: 'Lora', Georgia, serif; font-size: 1.3rem; font-weight: 500; font-style: italic; color: #1A1410;">Disable 2FA</h3>
            </div>
            <p style="margin: 0; color: #3D3530; font-size: 14px; line-height: 1.6;">Enter your password to disable two-factor authentication</p>
          </div>
          <div style="text-align: left; max-width: 320px; margin: 0 auto;">
            <label style="display: block; font-size: 12px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 8px; color: #C9A96E;">Password</label>
            <input type="password" id="disable-2fa-password" class="swal2-input" style="width: 100%; padding: 12px 16px; border: 2px solid #EDEAE6; border-radius: 12px; font-size: 14px; color: #1A1410; transition: border-color 0.2s;" placeholder="Enter your password" onfocus="this.style.borderColor='#C9A96E'" onblur="this.style.borderColor='#EDEAE6'">
          </div>
        `,
        confirmButtonText: '<i class="ni ni-check-bold mr-1"></i>Disable 2FA',
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
          const input = document.getElementById('disable-2fa-password');
          if (input) input.focus();
        },
        preConfirm: () => {
          const password = document.getElementById('disable-2fa-password').value;
          if (!password) {
            Swal.showValidationMessage('Password is required');
            return false;
          }
          return password;
        }
      });

      if (password) {
        try {
          const csrfToken = document.querySelector('input[name="csrf_token"]')?.value || '';
          const res = await fetch('../api/2fa.php?action=disable', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ password: password, csrf_token: csrfToken })
          });
          const result = await res.json();
          if (result.success) {
            document.getElementById('2fa-status-text').textContent = 'Add an extra layer of security';
            document.getElementById('2fa-actions').classList.add('hidden');
            showToast('2FA disabled successfully');
          } else {
            showToast('Failed to disable 2FA: ' + result.message, 'error');
            toggle.checked = true;
            updateToggleVisual(true);
          }
        } catch (error) {
          console.error('Failed to disable 2FA:', error);
          showToast('Failed to disable 2FA', 'error');
          toggle.checked = true;
          updateToggleVisual(true);
        }
      } else {
        toggle.checked = true;
        updateToggleVisual(true);
      }
    }
  }

  async function enable2FAWithCode(code) {
    try {
      const csrfToken = document.querySelector('input[name="csrf_token"]')?.value || '';
      const res = await fetch('../api/2fa.php?action=enable', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ code: code, csrf_token: csrfToken })
      });
      const result = await res.json();
      if (result.success) {
        document.getElementById('2fa-status-text').textContent = '2FA is enabled';
        document.getElementById('2fa-actions').classList.remove('hidden');
        showToast('2FA enabled successfully! Your backup codes: ' + result.data.backup_codes.join(', '));
        // Close modal if exists
        const modal = document.getElementById('qr-code-modal');
        if (modal) modal.remove();
      } else {
        showToast('Failed to enable 2FA: ' + result.message, 'error');
        document.getElementById('2fa-toggle').checked = false;
      }
    } catch (error) {
      console.error('Failed to enable 2FA:', error);
      showToast('Failed to enable 2FA', 'error');
      document.getElementById('2fa-toggle').checked = false;
    }
  }

  function showQRCodeModal(qrCodeSvg, manualKey) {
    // Remove existing modal if any
    const existingModal = document.getElementById('qr-code-modal');
    if (existingModal) existingModal.remove();

    // Create modal
    const modal = document.createElement('div');
    modal.id = 'qr-code-modal';
    modal.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(26,20,16,0.6);display:flex;align-items:center;justify-content:center;z-index:9999;backdrop-filter:blur(4px);';
    modal.innerHTML = `
      <div style="background:#FAFAF8;padding:40px;border-radius:20px;max-width:440px;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,0.12);border:1px solid #EDEAE6;">
        <div style="display:flex;align-items:center;justify-content:center;gap:12px;margin-bottom:24px;">
          <div style="background:#C9A96E;width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;">
            <i class="ni ni-key-25 text-white text-xl"></i>
          </div>
          <h3 style="margin:0;font-family:'Lora',Georgia,serif;font-size:1.3rem;font-weight:500;font-style:italic;color:#1A1410;">Setup Two-Factor Authentication</h3>
        </div>

        <p style="margin:0 0 24px 0;color:#3D3530;font-size:14px;line-height:1.6;">Scan this QR code with your authenticator app (Google Authenticator, Authy, etc.)</p>

        <div style="background:white;padding:16px;border-radius:16px;border:1px solid #EDEAE6;margin:0 auto 24px auto;width:fit-content;max-width:100%;">
          <div style="width:220px;height:220px;display:flex;align-items:center;justify-content:center;">${qrCodeSvg}</div>
        </div>

        <div style="margin-bottom:24px;">
          <p style="margin:0 0 8px 0;color:#3D3530;font-size:12px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;">Or enter manually:</p>
          <code style="display:block;padding:12px 16px;background:#E8D5B0;border-radius:8px;font-size:13px;font-family:'DM Sans',monospace;color:#1A1410;letter-spacing:1px;font-weight:500;word-break:break-all;border:1px solid #C9A96E;">${manualKey}</code>
        </div>

        <div style="margin-bottom:24px;">
          <p style="margin:0 0 12px 0;color:#3D3530;font-size:14px;">Enter the 6-digit code from your app:</p>
          <input type="text" id="2fa-verify-code" placeholder="000000" maxlength="6" style="width:100%;padding:14px;font-size:20px;text-align:center;border:2px solid #EDEAE6;border-radius:12px;font-family:'DM Sans',monospace;letter-spacing:4px;color:#1A1410;transition:border-color 0.2s;" onfocus="this.style.borderColor='#C9A96E'" onblur="this.style.borderColor='#EDEAE6'">
        </div>

        <div style="display:flex;gap:12px;justify-content:center;">
          <button onclick="enable2FAWithCode(document.getElementById('2fa-verify-code').value)" style="padding:12px 32px;background:linear-gradient(135deg,#C9A96E,#A07840);color:white;border:none;border-radius:10px;cursor:pointer;font-size:14px;font-weight:600;letter-spacing:0.06em;box-shadow:0 4px 12px rgba(201,169,110,0.35);transition:transform 0.2s,box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 16px rgba(201,169,110,0.45)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 12px rgba(201,169,110,0.35)'">Verify & Enable</button>
          <button onclick="cancel2FASetup()" style="padding:12px 32px;background:#3D3530;color:white;border:none;border-radius:10px;cursor:pointer;font-size:14px;font-weight:500;transition:background 0.2s;" onmouseover="this.style.background='#1A1410'" onmouseout="this.style.background='#3D3530'">Cancel</button>
        </div>
      </div>
    `;
    document.body.appendChild(modal);

    // Focus the input
    setTimeout(() => {
      const input = document.getElementById('2fa-verify-code');
      if (input) input.focus();
    }, 100);
  }

  function cancel2FASetup() {
    const modal = document.getElementById('qr-code-modal');
    if (modal) modal.remove();
    document.getElementById('2fa-toggle').checked = false;
  }

  /* ── Password strength helpers ── */
  function _cpwStrength(pw) {
    let score = 0;
    const checks = {
      length:    pw.length >= 8,
      uppercase: /[A-Z]/.test(pw),
      number:    /[0-9]/.test(pw),
      special:   /[^A-Za-z0-9]/.test(pw)
    };
    score = Object.values(checks).filter(Boolean).length;
    const levels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
    const colors = ['', '#ef4444', '#f59e0b', '#3b82f6', '#10b981'];
    return { score, checks, label: levels[score] || '', color: colors[score] || '' };
  }

  function _cpwToggle(inputId, btnId) {
    const inp = document.getElementById(inputId);
    const btn = document.getElementById(btnId);
    if (!inp || !btn) return;
    const isHidden = inp.type === 'password';
    inp.type = isHidden ? 'text' : 'password';
    btn.innerHTML = isHidden
      ? `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
      : `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
  }

  function _cpwUpdateStrength() {
    const pw = document.getElementById('new-password')?.value || '';
    const { score, checks, label, color } = _cpwStrength(pw);

    /* strength bar segments */
    [1,2,3,4].forEach(i => {
      const seg = document.getElementById('cpw-seg-' + i);
      if (seg) seg.style.background = i <= score ? color : '#EDEAE6';
    });

    const lbl = document.getElementById('cpw-strength-label');
    if (lbl) { lbl.textContent = label; lbl.style.color = color; }

    /* requirements checklist */
    const req = { length: checks.length, uppercase: checks.uppercase, number: checks.number, special: checks.special };
    Object.entries(req).forEach(([key, met]) => {
      const el = document.getElementById('cpw-req-' + key);
      if (!el) return;
      el.querySelector('.cpw-req-icon').textContent = met ? '✓' : '○';
      el.querySelector('.cpw-req-icon').style.color = met ? '#10b981' : '#9ca3af';
      el.style.color = met ? '#1A1410' : '#9ca3af';
    });

    /* confirm match indicator */
    _cpwUpdateMatch();
  }

  function _cpwUpdateMatch() {
    const np = document.getElementById('new-password')?.value || '';
    const cp = document.getElementById('confirm-password')?.value || '';
    const ind = document.getElementById('cpw-match-indicator');
    if (!ind || !cp) { if (ind) ind.textContent = ''; return; }
    if (np === cp) {
      ind.textContent = '✓ Passwords match';
      ind.style.color = '#10b981';
    } else {
      ind.textContent = '✗ Passwords do not match';
      ind.style.color = '#ef4444';
    }
  }

  async function showChangePasswordModal() {
    // Check if user has a password set (Google users might not)
    let hasPassword = true;
    try {
      const res = await fetch('../api/auth.php?action=check_password_status');
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

    /* ── shared input + toggle wrapper style ── */
    const inputWrap = `position: relative; display: flex; align-items: center;`;
    const inputStyle = `width: 100%; padding: 11px 44px 11px 14px; border: 2px solid #EDEAE6; border-radius: 10px; font-size: 14px; color: #1A1410; background: #FAFAF8; outline: none; box-sizing: border-box; transition: border-color 0.2s, box-shadow 0.2s; margin: 0;`;
    const toggleBtnStyle = `position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #9ca3af; padding: 0; display: flex; align-items: center;`;
    const eyeIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
    const labelStyle = `display: block; font-size: 11px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 7px; color: #C9A96E;`;

    Swal.fire({
      title: '',
      width: 420,
      html: `
        <style>
          #cpw-current-password:focus, #new-password:focus, #confirm-password:focus {
            border-color: #C9A96E !important;
            box-shadow: 0 0 0 3px rgba(201,169,110,.15) !important;
          }
          .cpw-toggle-btn:hover { color: #C9A96E !important; }
        </style>

        <!-- Header -->
        <div style="text-align:center; margin-bottom:22px;">
          <div style="display:inline-flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#C9A96E,#A07840); width:52px; height:52px; border-radius:14px; margin-bottom:14px; box-shadow:0 4px 12px rgba(201,169,110,.35);">
            <i class="ni ni-lock-circle-open" style="color:#fff; font-size:22px;"></i>
          </div>
          <h3 style="margin:0 0 6px; font-family:'Lora',Georgia,serif; font-size:1.25rem; font-weight:500; font-style:italic; color:#1A1410;">${modalTitle}</h3>
          <p style="margin:0; color:#6b7280; font-size:13px; line-height:1.5;">${modalSubtitle}</p>
        </div>

        <div style="text-align:left;">

          ${hasPassword ? `
          <!-- Current Password -->
          <div style="margin-bottom:16px;">
            <label style="${labelStyle}">Current Password</label>
            <div style="${inputWrap}">
              <input type="password" id="cpw-current-password"
                style="${inputStyle}" placeholder="Enter current password"
                onfocus="this.style.borderColor='#C9A96E';this.style.boxShadow='0 0 0 3px rgba(201,169,110,.15)'"
                onblur="this.style.borderColor='#EDEAE6';this.style.boxShadow='none'">
              <button type="button" id="cpw-toggle-current" class="cpw-toggle-btn"
                style="${toggleBtnStyle}" onclick="_cpwToggle('cpw-current-password','cpw-toggle-current')">${eyeIcon}</button>
            </div>
          </div>
          ` : ''}

          <!-- New Password -->
          <div style="margin-bottom:6px;">
            <label style="${labelStyle}">New Password</label>
            <div style="${inputWrap}">
              <input type="password" id="new-password"
                style="${inputStyle}" placeholder="Enter new password"
                oninput="_cpwUpdateStrength()"
                onfocus="this.style.borderColor='#C9A96E';this.style.boxShadow='0 0 0 3px rgba(201,169,110,.15)'"
                onblur="this.style.borderColor='#EDEAE6';this.style.boxShadow='none'">
              <button type="button" id="cpw-toggle-new" class="cpw-toggle-btn"
                style="${toggleBtnStyle}" onclick="_cpwToggle('new-password','cpw-toggle-new')">${eyeIcon}</button>
            </div>
          </div>

          <!-- Strength bar -->
          <div style="margin-bottom:10px;">
            <div style="display:flex; gap:5px; margin-bottom:5px;">
              ${[1,2,3,4].map(i => `<div id="cpw-seg-${i}" style="flex:1; height:4px; border-radius:4px; background:#EDEAE6; transition:background 0.3s;"></div>`).join('')}
            </div>
            <span id="cpw-strength-label" style="font-size:11px; font-weight:600; letter-spacing:0.04em;"></span>
          </div>

          <!-- Requirements checklist -->
          <div style="background:#FAFAF8; border:1px solid #EDEAE6; border-radius:10px; padding:12px 14px; margin-bottom:16px;">
            <p style="margin:0 0 8px; font-size:11px; font-weight:600; letter-spacing:0.08em; text-transform:uppercase; color:#9ca3af;">Password requirements</p>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:5px;">
              ${[
                ['length',   'At least 8 characters'],
                ['uppercase','One uppercase letter'],
                ['number',   'One number'],
                ['special',  'One special character'],
              ].map(([key, text]) => `
                <div id="cpw-req-${key}" style="display:flex; align-items:center; gap:6px; font-size:12px; color:#9ca3af; transition:color 0.2s;">
                  <span class="cpw-req-icon" style="font-size:13px; width:14px; text-align:center; color:#9ca3af; transition:color 0.2s;">○</span>
                  <span>${text}</span>
                </div>
              `).join('')}
            </div>
          </div>

          <!-- Confirm Password -->
          <div style="margin-bottom:6px;">
            <label style="${labelStyle}">Confirm New Password</label>
            <div style="${inputWrap}">
              <input type="password" id="confirm-password"
                style="${inputStyle}" placeholder="Confirm new password"
                oninput="_cpwUpdateMatch()"
                onfocus="this.style.borderColor='#C9A96E';this.style.boxShadow='0 0 0 3px rgba(201,169,110,.15)'"
                onblur="this.style.borderColor='#EDEAE6';this.style.boxShadow='none'">
              <button type="button" id="cpw-toggle-confirm" class="cpw-toggle-btn"
                style="${toggleBtnStyle}" onclick="_cpwToggle('confirm-password','cpw-toggle-confirm')">${eyeIcon}</button>
            </div>
          </div>
          <div id="cpw-match-indicator" style="font-size:12px; font-weight:500; min-height:18px; margin-bottom:2px;"></div>

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
        /* focus first field */
        setTimeout(() => {
          const first = document.getElementById(hasPassword ? 'cpw-current-password' : 'new-password');
          if (first) first.focus();
        }, 100);
      },
      preConfirm: async () => {
        const currentPassword = hasPassword ? document.getElementById('cpw-current-password').value : '';
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
          const res = await fetch('../api/auth.php?action=change_password', {
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

  async function showBackupCodes() {
    showToast('Backup codes can only be viewed during initial setup. Use "Regenerate Backup Codes" to get new ones.', 'info');
  }

  async function regenerateBackupCodes() {
    const { isConfirmed } = await Swal.fire({
      title: '',
      html: `
        <div style="text-align: center; margin-bottom: 24px;">
          <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 16px;">
            <div style="background: #C9A96E; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
              <i class="ni ni-key-25 text-white text-xl"></i>
            </div>
            <h3 style="margin: 0; font-family: 'Lora', Georgia, serif; font-size: 1.3rem; font-weight: 500; font-style: italic; color: #1A1410;">Regenerate Backup Codes</h3>
          </div>
          <p style="margin: 0; color: #3D3530; font-size: 14px; line-height: 1.6;">This will invalidate your existing backup codes. Are you sure?</p>
        </div>
      `,
      confirmButtonText: '<i class="ni ni-check-bold mr-1"></i>Regenerate',
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
      }
    });

    if (isConfirmed) {
      try {
        const csrfToken = document.querySelector('input[name="csrf_token"]')?.value || '';
        const res = await fetch('../api/2fa.php?action=regenerate_backup', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ csrf_token: csrfToken })
        });
        const result = await res.json();
        if (result.success) {
          showToast('New backup codes: ' + result.data.backup_codes.join(', '));
        } else {
          showToast('Failed to regenerate backup codes: ' + result.message, 'error');
        }
      } catch (error) {
        console.error('Failed to regenerate backup codes:', error);
        showToast('Failed to regenerate backup codes', 'error');
      }
    }
  }

  /* ─── PAYMENT METHODS ─────────────────────────────────────────────────────── */
  async function loadPaymentMethods() {
    try {
      const res = await fetch('../api/user_payment_methods.php?action=list');
      const result = await res.json();
      if (result.success) {
        renderPaymentMethods(result.data);
      } else {
        document.getElementById('payment-methods-list').innerHTML = '<p class="text-sm text-gray-500">Failed to load payment methods</p>';
      }
    } catch (error) {
      console.error('Failed to load payment methods:', error);
      document.getElementById('payment-methods-list').innerHTML = '<p class="text-sm text-gray-500">Failed to load payment methods</p>';
    }
  }

  function renderPaymentMethods(methods) {
    const container = document.getElementById('payment-methods-list');
    if (!methods || methods.length === 0) {
      container.innerHTML = '<p class="text-sm text-gray-500">No payment methods saved</p>';
      return;
    }

    container.innerHTML = methods.map(method => {
      const iconClass = getPaymentMethodIcon(method.method_type);
      const displayText = getPaymentMethodDisplayText(method);
      const defaultBadge = method.is_default ? '<span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Default</span>' : '';
      const removeButton = !method.is_default ? `<button onclick="removePaymentMethod('${method.payment_method_id}')" class="text-xs text-red-600 hover:text-red-700">Remove</button>` : '';
      const setDefaultButton = !method.is_default ? `<button onclick="setDefaultPaymentMethod('${method.payment_method_id}')" class="text-xs text-indigo-600 hover:text-indigo-700 mr-2">Set Default</button>` : '';

      return `
        <div class="flex items-center justify-between p-3 bg-white dark:bg-slate-700 rounded-lg">
          <div class="flex items-center gap-3">
            <i class="ni ${iconClass} text-2xl text-blue-600"></i>
            <div style="margin-left: 10px;">
              <p class="text-sm font-medium dark:text-white">${displayText}</p>
              ${method.expiry_month && method.expiry_year ? `<p class="text-xs text-gray-500">Expires ${method.expiry_month}/${method.expiry_year}</p>` : ''}
            </div>
          </div>
          <div class="flex items-center gap-2">
            ${setDefaultButton}
            ${defaultBadge}
            ${removeButton}
          </div>
        </div>
      `;
    }).join('');
  }

  function getPaymentMethodIcon(methodType) {
    const icons = {
      'credit_card': 'ni-credit-card',
      'debit_card': 'ni-credit-card',
      'gcash': 'ni-mobile',
      'maya': 'ni-mobile',
      'bank_transfer': 'ni-bank',
      'other': 'ni-credit-card'
    };
    return icons[methodType] || 'ni-credit-card';
  }

  function getPaymentMethodDisplayText(method) {
    if (method.last_4) {
      return `•••• •••• •••• ${method.last_4}`;
    }
    if (method.display_name) {
      return method.display_name;
    }
    return method.method_type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
  }

  function showAddPaymentMethodModal() {
    Swal.fire({
      title: '',
      html: `
        <div style="text-align: center; margin-bottom: 24px;">
          <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 16px;">
            <div style="background: #C9A96E; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
              <i class="ni ni-credit-card text-white text-xl"></i>
            </div>
            <h3 style="margin: 0; font-family: 'Lora', Georgia, serif; font-size: 1.3rem; font-weight: 500; font-style: italic; color: #1A1410;">Add Payment Method</h3>
          </div>
          <p style="margin: 0; color: #3D3530; font-size: 14px; line-height: 1.6;">Add a new payment method to your account</p>
        </div>
        <div style="text-align: left; max-width: 320px; margin: 0 auto;">
          <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 12px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 8px; color: #C9A96E;">Payment Type</label>
            <select id="payment-method-type" class="swal2-input" style="width: 100%; padding: 12px 16px; border: 2px solid #EDEAE6; border-radius: 12px; font-size: 14px; color: #1A1410; transition: border-color 0.2s;" onfocus="this.style.borderColor='#C9A96E'" onblur="this.style.borderColor='#EDEAE6'">
              <option value="credit_card">Credit Card</option>
              <option value="debit_card">Debit Card</option>
              <option value="gcash">GCash</option>
              <option value="maya">Maya</option>
              <option value="bank_transfer">Bank Transfer</option>
            </select>
          </div>
          <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 12px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 8px; color: #C9A96E;">Last 4 Digits</label>
            <input type="text" id="payment-last4" class="swal2-input" style="width: 100%; padding: 12px 16px; border: 2px solid #EDEAE6; border-radius: 12px; font-size: 14px; color: #1A1410; transition: border-color 0.2s;" placeholder="1234" maxlength="4" onfocus="this.style.borderColor='#C9A96E'" onblur="this.style.borderColor='#EDEAE6'">
          </div>
          <div style="display: flex; gap: 12px;">
            <div style="flex: 1;">
              <label style="display: block; font-size: 12px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 8px; color: #C9A96E;">Expiry Month</label>
              <input type="number" id="payment-expiry-month" class="swal2-input" style="width: 100%; padding: 12px 16px; border: 2px solid #EDEAE6; border-radius: 12px; font-size: 14px; color: #1A1410; transition: border-color 0.2s;" placeholder="12" min="1" max="12" onfocus="this.style.borderColor='#C9A96E'" onblur="this.style.borderColor='#EDEAE6'">
            </div>
            <div style="flex: 1;">
              <label style="display: block; font-size: 12px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 8px; color: #C9A96E;">Expiry Year</label>
              <input type="number" id="payment-expiry-year" class="swal2-input" style="width: 100%; padding: 12px 16px; border: 2px solid #EDEAE6; border-radius: 12px; font-size: 14px; color: #1A1410; transition: border-color 0.2s;" placeholder="2025" min="2024" max="2030" onfocus="this.style.borderColor='#C9A96E'" onblur="this.style.borderColor='#EDEAE6'">
            </div>
          </div>
        </div>
      `,
      confirmButtonText: '<i class="ni ni-check-bold mr-1"></i>Add Method',
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
      preConfirm: () => {
        const methodType = document.getElementById('payment-method-type').value;
        const last4 = document.getElementById('payment-last4').value;
        const expiryMonth = document.getElementById('payment-expiry-month').value;
        const expiryYear = document.getElementById('payment-expiry-year').value;

        if (!last4) {
          Swal.showValidationMessage('Last 4 digits are required');
          return false;
        }

        return {
          method_type: methodType,
          last_4: last4,
          expiry_month: expiryMonth,
          expiry_year: expiryYear
        };
      }
    }).then((result) => {
      if (result.isConfirmed) {
        addPaymentMethod(result.value);
      }
    });
  }

  async function addPaymentMethod(data) {
    try {
      const res = await fetch('../api/user_payment_methods.php?action=add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        showToast('Payment method added successfully');
        loadPaymentMethods();
      } else {
        showToast('Failed to add payment method: ' + result.message, 'error');
      }
    } catch (error) {
      console.error('Failed to add payment method:', error);
      showToast('Failed to add payment method', 'error');
    }
  }

  async function removePaymentMethod(paymentMethodId) {
    const { isConfirmed } = await Swal.fire({
      title: '',
      html: `
        <div style="text-align: center; margin-bottom: 24px;">
          <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 16px;">
            <div style="background: #C9A96E; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
              <i class="ni ni-credit-card text-white text-xl"></i>
            </div>
            <h3 style="margin: 0; font-family: 'Lora', Georgia, serif; font-size: 1.3rem; font-weight: 500; font-style: italic; color: #1A1410;">Remove Payment Method</h3>
          </div>
          <p style="margin: 0; color: #3D3530; font-size: 14px; line-height: 1.6;">Are you sure you want to remove this payment method?</p>
        </div>
      `,
      confirmButtonText: '<i class="ni ni-check-bold mr-1"></i>Remove',
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
      }
    });

    if (!isConfirmed) return;

    try {
      const res = await fetch('../api/user_payment_methods.php?action=remove', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ payment_method_id: paymentMethodId })
      });
      const result = await res.json();
      if (result.success) {
        showToast('Payment method removed successfully');
        loadPaymentMethods();
      } else {
        showToast('Failed to remove payment method: ' + result.message, 'error');
      }
    } catch (error) {
      console.error('Failed to remove payment method:', error);
      showToast('Failed to remove payment method', 'error');
    }
  }

  async function setDefaultPaymentMethod(paymentMethodId) {
    try {
      const res = await fetch('../api/user_payment_methods.php?action=set_default', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ payment_method_id: paymentMethodId })
      });
      const result = await res.json();
      if (result.success) {
        showToast('Default payment method updated');
        loadPaymentMethods();
      } else {
        showToast('Failed to set default payment method: ' + result.message, 'error');
      }
    } catch (error) {
      console.error('Failed to set default payment method:', error);
      showToast('Failed to set default payment method', 'error');
    }
  }

  /* ─── TOAST NOTIFICATIONS ─────────────────────────────────────────────────── */
  let toastTimer;

  function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    if (!t) return;
    t.textContent = msg;
    t.className = 'toast ' + type + ' show';
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
      t.classList.remove('show');
    }, 3000);
  }
</script>

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