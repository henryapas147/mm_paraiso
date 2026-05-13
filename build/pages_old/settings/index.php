<?php
$assets_path = '../assets';

// Get the page parameter, default to system_settings.php
$page = $_GET['page'] ?? 'system_settings.php';

// Validate the page to prevent directory traversal
$allowed_pages = [
    'system_settings.php',
    'pricing_rules.php',
    'seasonal_rates.php',
    'booking_policies.php',
    'promotions.php',
    'promotion_images.php',
    'notifications.php',
    'notification_recipients.php'
];

if (!in_array($page, $allowed_pages)) {
    $page = 'system_settings.php';
}

// Include the target page
$page_path = __DIR__ . '/' . $page;

if (!file_exists($page_path)) {
    die('Page not found');
}
?>
<!DOCTYPE html>
<html>
  <head>
    <?php include('../../structure/head.php');?>
  </head>

  <body class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">
    <div class="absolute w-full bg-blue-500 dark:hidden min-h-75"></div>
    <!-- sidenav  -->
    <?php include('../../structure/sidebar.php');?>

    <!-- end sidenav -->

    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">
      <!-- Navbar -->
      <?php include('../../structure/navbar.php');?>

      <!-- end Navbar -->

      <!-- cards -->
      <div class="w-full px-6 py-6 mx-auto">
        <!-- PAGE CONTENT -->
        <?php include($page_path); ?>
      </div>
    </main>

    <?php include('../../structure/footer.php');?>
  </body>
</html>
