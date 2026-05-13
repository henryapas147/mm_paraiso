<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- DEBUG: assets_path = <?php echo isset($assets_path) ? $assets_path : 'NOT SET'; ?> -->
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo $assets_path ?? './assets'; ?>/img/apple-icon.png" />
<link rel="icon" type="image/png" href="<?php echo $assets_path ?? './assets'; ?>/img/favicon.png" />
<title>Paradise Booking System<?php echo isset($page_title) ? ' - ' . htmlspecialchars($page_title) : ''; ?></title>
<!--     Fonts and icons     -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />
<!-- Nucleo Icons -->
<link href="<?php echo $assets_path ?? './assets'; ?>/css/nucleo-icons.css" rel="stylesheet" />
<link href="<?php echo $assets_path ?? './assets'; ?>/css/nucleo-svg.css" rel="stylesheet" />
<!-- Popper -->
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="<?php echo $assets_path ?? './assets'; ?>/js/sweetalert2.min.js"></script>
<!-- Cropper.js -->
<link rel="stylesheet" href="<?php echo $assets_path ?? './assets'; ?>/css/cropper.min.css" />
<script src="<?php echo $assets_path ?? './assets'; ?>/js/cropper.min.js"></script>
<!-- Main Styling -->
<link href="<?php echo $assets_path ?? './assets'; ?>/css/argon-dashboard-tailwind.css?v=1.0.1" rel="stylesheet" />