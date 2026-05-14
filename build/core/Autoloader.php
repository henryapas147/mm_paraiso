<?php

spl_autoload_register(function ($class) {
    $build_dir = __DIR__ . '/../';

    // Map class names to file locations
    $class_map = [
        'App\\Controllers\\Controller'       => $build_dir . 'core/Controller.php',
        'App\\Controllers\\AuthController'   => $build_dir . 'controllers/AuthController.php',
        'App\\Controllers\\DashboardController' => $build_dir . 'controllers/DashboardController.php',
        'App\\Controllers\\PropertiesController' => $build_dir . 'controllers/PropertiesController.php',
        'App\\Controllers\\InventoryController'  => $build_dir . 'controllers/InventoryController.php',
        'App\\Controllers\\FoodCanteenController' => $build_dir . 'controllers/FoodCanteenController.php',
        'App\\Controllers\\ReservationsController' => $build_dir . 'controllers/ReservationsController.php',
        'App\\Controllers\\PaymentsBillingController' => $build_dir . 'controllers/PaymentsBillingController.php',
        'App\\Controllers\\SettingsController' => $build_dir . 'controllers/SettingsController.php',
        'App\\Controllers\\UsersController'   => $build_dir . 'controllers/UsersController.php',
        'App\\Controllers\\CustomerController' => $build_dir . 'controllers/CustomerController.php',
        'App\\Controllers\\ProfileController'  => $build_dir . 'controllers/ProfileController.php',
        'App\\Controllers\\CustomerProfileController'  => $build_dir . 'controllers/CustomerProfileController.php',
        'App\\Controllers\\ReportsController'  => $build_dir . 'controllers/ReportsController.php',
    ];

    if (isset($class_map[$class]) && file_exists($class_map[$class])) {
        require $class_map[$class];
        return;
    }

    // Fallback: try controllers/ directory
    $prefix = 'App\\Controllers\\';
    if (strncmp($prefix, $class, strlen($prefix)) === 0) {
        $class_name = substr($class, strlen($prefix));
        $file = $build_dir . 'controllers/' . $class_name . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});
