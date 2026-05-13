<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  header('Location: sign-in.php');
  exit;
}

$message = '';
$messageType = '';

// Handle profile update submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
  $user_id = $_SESSION['user_id'];
  $firstName = trim($_POST['first_name'] ?? '');
  $lastName = trim($_POST['last_name'] ?? '');
  $addressLine1 = trim($_POST['address_line1'] ?? '');
  $addressLine2 = trim($_POST['address_line2'] ?? '');
  $city = trim($_POST['city'] ?? '');
  $country = trim($_POST['country'] ?? '');
  $postalCode = trim($_POST['postal_code'] ?? '');

  try {
    // Update user profile
    $stmt = $pdo->prepare("
            UPDATE user_profiles
            SET first_name = ?, last_name = ?, address_line1 = ?, address_line2 = ?,
                city = ?, country = ?, postal_code = ?, updated_at = NOW()
            WHERE user_id = ?
        ");
    $stmt->execute([$firstName, $lastName, $addressLine1, $addressLine2, $city, $country, $postalCode, $user_id]);

    // Set session message and redirect (PRG pattern)
    $_SESSION['profile_message'] = 'Profile updated successfully!';
    $_SESSION['profile_message_type'] = 'success';
    header('Location: profile.php');
    exit;
  } catch (PDOException $e) {
    error_log("Profile update error: " . $e->getMessage());
    $_SESSION['profile_message'] = 'Error updating profile. Please try again.';
    $_SESSION['profile_message_type'] = 'error';
    header('Location: profile.php');
    exit;
  }
}

// Get message from session and clear it
$message = $_SESSION['profile_message'] ?? '';
$messageType = $_SESSION['profile_message_type'] ?? '';
unset($_SESSION['profile_message'], $_SESSION['profile_message_type']);

// Fetch user profile data
$user_id = $_SESSION['user_id'];
$user = null;
$profile = null;

try {
  // Get user account data
  $stmt = $pdo->prepare("
        SELECT ua.user_id, ua.email, ua.phone_number, ua.account_status,
               ur.role_code, ur.role_label
        FROM user_accounts ua
        LEFT JOIN user_roles ur ON ua.role_id = ur.role_id
        WHERE ua.user_id = ? AND ua.deleted_at IS NULL
    ");
  $stmt->execute([$user_id]);
  $user = $stmt->fetch();

  // Get user profile data
  if ($user) {
    $stmt = $pdo->prepare("
            SELECT first_name, last_name, middle_name, display_name,
                   profile_photo_url, address_line1, address_line2, city,
                   province, postal_code, country, date_of_birth
            FROM user_profiles
            WHERE user_id = ?
        ");
    $stmt->execute([$user_id]);
    $profile = $stmt->fetch();
  }
} catch (PDOException $e) {
  error_log("Profile fetch error: " . $e->getMessage());
}
?>

<?php $assets_path = '../assets';
$page_title = 'Profile'; ?>
<!DOCTYPE html>
<html>

<head>
  <?php include('../../structure/head.php'); ?>
</head>

<body class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">
  <div class="absolute w-full bg-blue-500 dark:hidden min-h-75"></div>
  <!-- sidenav  -->
  <?php include('../../structure/sidebar.php'); ?>

  <!-- end sidenav -->

  <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">
    <!-- Navbar -->
    <?php include('../../structure/navbar.php'); ?>

    <!-- end Navbar -->

    <!-- cards -->
    <div class="w-full px-6 py-6 mx-auto">
      <!-- PAGE CONTENT -->
      <div class="relative flex flex-col flex-auto min-w-0 p-4 mx-6 overflow-hidden break-words bg-white border-0 dark:bg-slate-850 dark:shadow-dark-xl shadow-3xl rounded-2xl bg-clip-border">
        <div class="flex flex-wrap -mx-3">
          <div class="flex-none w-auto max-w-full px-3">
            <div class="relative inline-flex items-center justify-center text-white transition-all duration-200 ease-in-out text-base h-19 w-19 rounded-xl">
              <img src="../assets/img/team-1.jpg" alt="profile_image" class="w-full shadow-2xl rounded-xl" />
            </div>
          </div>
          <div class="flex-none w-auto max-w-full px-3 my-auto">
            <div class="h-full">
              <h5 class="mb-1 dark:text-white"><?php echo htmlspecialchars($profile['display_name'] ?? ($profile ? ($profile['first_name'] . ' ' . $profile['last_name']) : 'User')); ?></h5>
              <p class="mb-0 font-semibold leading-normal dark:text-white dark:opacity-60 text-sm"><?php echo htmlspecialchars($user['role_label'] ?? 'User'); ?></p>
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
                        <input type="text" name="username" value="<?php echo htmlspecialchars(explode('@', $user['email'])[0] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" readonly />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                      <div class="mb-4">
                        <label for="email" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Email address</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" readonly />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                      <div class="mb-4">
                        <label for="first_name" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">First name</label>
                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($profile['first_name'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" required />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                      <div class="mb-4">
                        <label for="last_name" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Last name</label>
                        <input type="text" name="last_name" value="<?php echo htmlspecialchars($profile['last_name'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" required />
                      </div>
                    </div>
                  </div>
                  <hr class="h-px mx-0 my-4 bg-transparent border-0 opacity-25 bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent " />

                  <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm">Contact Information</p>
                  <div class="flex flex-wrap -mx-3">
                    <div class="w-full max-w-full px-3 shrink-0 md:w-full md:flex-0">
                      <div class="mb-4">
                        <label for="address_line1" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Address Line 1</label>
                        <input type="text" name="address_line1" value="<?php echo htmlspecialchars($profile['address_line1'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-full md:flex-0">
                      <div class="mb-4">
                        <label for="address_line2" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Address Line 2</label>
                        <input type="text" name="address_line2" value="<?php echo htmlspecialchars($profile['address_line2'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                      <div class="mb-4">
                        <label for="city" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">City</label>
                        <input type="text" name="city" value="<?php echo htmlspecialchars($profile['city'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                      <div class="mb-4">
                        <label for="country" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Country</label>
                        <input type="text" name="country" value="<?php echo htmlspecialchars($profile['country'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                      <div class="mb-4">
                        <label for="postal_code" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Postal code</label>
                        <input type="text" name="postal_code" value="<?php echo htmlspecialchars($profile['postal_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
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
              <img class="w-full rounded-t-2xl" src="../assets/img/bg-profile.jpg" alt="profile cover image">
              <div class="flex flex-wrap justify-center -mx-3">
                <div class="w-4/12 max-w-full px-3 flex-0 ">
                  <div class="mb-6 -mt-6 lg:mb-0 lg:-mt-16">
                    <a href="javascript:;">
                      <img class="h-auto max-w-full border-2 border-white border-solid rounded-circle" src="../assets/img/team-2.jpg" alt="profile image">
                    </a>
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
        <footer class="pt-4">
          <div class="w-full px-6 mx-auto">
            <div class="flex flex-wrap items-center -mx-3 lg:justify-between">
              <div class="w-full max-w-full px-3 mt-0 mb-6 shrink-0 lg:mb-0 lg:w-1/2 lg:flex-none">
                <div class="leading-normal text-center text-sm text-slate-500 lg:text-left">
                  ©
                  <script>
                    document.write(new Date().getFullYear() + ",");
                  </script>
                  made with <i class="fa fa-heart"></i> by
                  <a href="https://www.creative-tim.com" class="font-semibold dark:text-white text-slate-700" target="_blank">Creative Tim</a>
                  for a better web.
                </div>
              </div>
              <div class="w-full max-w-full px-3 mt-0 shrink-0 lg:w-1/2 lg:flex-none">
                <ul class="flex flex-wrap justify-center pl-0 mb-0 list-none lg:justify-end">
                  <li class="nav-item">
                    <a href="https://www.creative-tim.com" class="block px-4 pt-0 pb-1 font-normal transition-colors ease-in-out text-sm text-slate-500" target="_blank">Creative Tim</a>
                  </li>
                  <li class="nav-item">
                    <a href="https://www.creative-tim.com/presentation" class="block px-4 pt-0 pb-1 font-normal transition-colors ease-in-out text-sm text-slate-500" target="_blank">About Us</a>
                  </li>
                  <li class="nav-item">
                    <a href="https://creative-tim.com/blog" class="block px-4 pt-0 pb-1 font-normal transition-colors ease-in-out text-sm text-slate-500" target="_blank">Blog</a>
                  </li>
                  <li class="nav-item">
                    <a href="https://www.creative-tim.com/license" class="block px-4 pt-0 pb-1 pr-0 font-normal transition-colors ease-in-out text-sm text-slate-500" target="_blank">License</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>

      <?php include('../../structure/footer.php'); ?>

    </div>
    <!-- end cards -->
  </main>

  <?php include('../../structure/customizer.php'); ?>
</body>

<?php include('../../structure/script.php'); ?>

</html>