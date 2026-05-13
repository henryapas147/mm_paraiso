<?php
$assets_path = '../assets';

// Get the page parameter, default to reservations.php
$page = $_GET['page'] ?? 'reservations.php';

// Validate the page to prevent directory traversal
$allowed_pages = [
    'reservations.php',
    'reservation_rooms.php',
    'reservation_guests.php',
    'reservation_requirement_types.php',
    'reservation_requirements.php',
    'check_in_logs.php',
    'entrance_logs.php'
];

if (!in_array($page, $allowed_pages)) {
    $page = 'reservations.php';
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
