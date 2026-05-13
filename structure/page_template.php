<?php $assets_path = '../../assets'; $page_title = ''; ?>
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


            <?php include('../../structure/footer.php'); ?>

        </div>
        <!-- end cards -->
    </main>

    <?php include('../../structure/customizer.php'); ?>
</body>

<?php include('../../structure/script.php'); ?>

</html>