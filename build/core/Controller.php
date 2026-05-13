<?php

namespace App\Controllers;

class Controller
{
    protected $view_path;
    protected $structure_path;

    public function __construct($params = [])
    {
        // Use __FILE__ to get the Controller.php file location (always build/core/Controller.php)
        // So dirname(__FILE__) = build/core, dirname(dirname(__FILE__)) = build
        $build_dir = dirname(__FILE__) . '/..';

        // Views directory: build/views/
        $this->view_path = realpath($build_dir . '/views') . DIRECTORY_SEPARATOR;

        // Structure directory: structure/ (parent of build/)
        $this->structure_path = realpath($build_dir . '/../structure') . DIRECTORY_SEPARATOR;
    }

    public function view($view, $data = [])
    {
        global $pdo;
        // Extract data to make it available in the view
        extract($data);

        // Calculate base_url dynamically from SCRIPT_NAME
        $script_name = $_SERVER['SCRIPT_NAME'];
        $script_dir = dirname($script_name);
        if (substr($script_dir, -1) != '/') {
            $script_dir .= '/';
        }
        $base_url = $script_dir;

        // Detect environment for admin-bg.jpg path
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $isLocalhost = in_array($host, ['localhost', '127.0.0.1']) || preg_match('/^192\.168\./', $host) || preg_match('/^10\./', $host);
        $adminBgPath = $isLocalhost ? '/admin_booking/uploads/admin-bg.jpg' : '/uploads/admin-bg.jpg';

        // Set assets_path for the structure files (absolute path from web root)
        $assets_path = $base_url . 'assets';
        // Ensure assets_path starts with / for absolute reference
        if (substr($assets_path, 0, 1) !== '/') {
            $assets_path = '/' . $assets_path;
        }

        // Output HTML shell
        echo '<!DOCTYPE html>
<html>

<head>';
        include $this->structure_path . 'head.php';
        echo '</head>

<body class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">
  <img src="' . $adminBgPath . '" class="absolute w-full dark:hidden min-h-20 object-cover" alt="">';
        // Include sidebar and navbar
        include $this->structure_path . 'sidebar.php';

        echo '<main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">';
        include $this->structure_path . 'navbar.php';

        echo '<div class="w-full px-6 py-6 mx-auto">';

        // Include the actual view file
        $view_file = $this->view_path . $view . '.php';
        if (file_exists($view_file)) {
            include $view_file;
        } else {
            echo "View not found: $view_file";
        }

        include $this->structure_path . 'footer.php';
        echo '</div></main>';
        include $this->structure_path . 'customizer.php';
        echo '</body>

';
        include $this->structure_path . 'script.php';
        echo '</html>';
    }

    // Render a view with the customer layout (customer sidebar, no admin menus)
    public function viewCustomer($view, $data = [])
    {
        global $pdo;
        extract($data);

        $script_name = $_SERVER['SCRIPT_NAME'];
        $script_dir = dirname($script_name);
        if (substr($script_dir, -1) != '/') {
            $script_dir .= '/';
        }
        $base_url = $script_dir;

        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $isLocalhost = in_array($host, ['localhost', '127.0.0.1']) || preg_match('/^192\.168\./', $host) || preg_match('/^10\./', $host);
        $adminBgPath = $isLocalhost ? '/admin_booking/uploads/admin-bg.jpg' : '/uploads/admin-bg.jpg';

        $assets_path = $base_url . 'assets';
        if (substr($assets_path, 0, 1) !== '/') {
            $assets_path = '/' . $assets_path;
        }

        echo '<!DOCTYPE html>
<html>

<head>';
        include $this->structure_path . 'head.php';
        echo '</head>

<body class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">
  <img src="' . $adminBgPath . '" class="absolute w-full dark:hidden min-h-20 object-cover" alt="">';
        include $this->structure_path . 'customer_sidebar.php';

        echo '<main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">';
        include $this->structure_path . 'navbar.php';

        echo '<div class="w-full px-6 py-6 mx-auto">';

        $view_file = $this->view_path . $view . '.php';
        if (file_exists($view_file)) {
            include $view_file;
        } else {
            echo "View not found: $view_file";
        }

        include $this->structure_path . 'footer.php';
        echo '</div></main>';
        include $this->structure_path . 'customizer.php';
        echo '</body>

';
        include $this->structure_path . 'script.php';
        echo '</html>';
    }

    // Render a standalone view without the main layout (for auth pages etc.)
    public function viewRaw($view, $data = [])
    {
        global $pdo;
        extract($data);

        $view_file = $this->view_path . $view . '.php';
        if (file_exists($view_file)) {
            include $view_file;
        } else {
            echo "View not found: $view_file";
        }
    }

    public function model($model)
    {
        $model_path = __DIR__ . '/../models/' . $model . '.php';
        if (file_exists($model_path)) {
            require_once $model_path;
            $model_class = 'App\\Models\\' . $model;
            return new $model_class();
        }
        return null;
    }
}
