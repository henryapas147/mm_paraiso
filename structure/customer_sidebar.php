<?php
// Get current URL for highlighting active link
$current_url = $_SERVER['REQUEST_URI'] ?? '/';
$current_url = strtok($current_url, '?');

$base_path = dirname($_SERVER['PHP_SELF']);
if ($base_path != '/' && $base_path != '\\') {
    $current_url = str_replace($base_path, '', $current_url);
}
if (substr($current_url, 0, 1) != '/') {
    $current_url = '/' . $current_url;
}

if (!isset($base_url) || empty($base_url)) {
    $script_name = $_SERVER['SCRIPT_NAME'];
    $script_dir = dirname($script_name);
    if (substr($script_dir, -1) != '/') {
        $script_dir .= '/';
    }
    $base_url = $script_dir;
}

function customerIsActive($route, $current_url) {
    return strpos($current_url, $route) !== false;
}

function customerActiveClass($route, $current_url) {
    return customerIsActive($route, $current_url)
        ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg'
        : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg';
}
?>

<aside class="fixed inset-y-0 flex-wrap items-center justify-between block w-full p-0 my-4 overflow-y-auto antialiased transition-transform duration-200 -translate-x-full bg-white border-0 shadow-xl dark:shadow-none dark:bg-slate-850 max-w-64 ease-nav-brand z-990 xl:ml-6 rounded-2xl xl:left-0 xl:translate-x-0" aria-expanded="false">
    <div class="h-19">
        <i class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times dark:text-white text-slate-400 xl:hidden" sidenav-close></i>
        <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap dark:text-white text-slate-700" href="<?php echo $base_url; ?>customer/dashboard">
            <img src="<?php echo $assets_path ?? './assets'; ?>/img/logo-ct-dark.png" class="inline h-full max-w-full transition-all duration-200 dark:hidden ease-nav-brand max-h-8" alt="main_logo" />
            <img src="<?php echo $assets_path ?? './assets'; ?>/img/logo-ct.png" class="hidden h-full max-w-full transition-all duration-200 dark:inline ease-nav-brand max-h-8" alt="main_logo" />
            <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">My Portal</span>
        </a>
    </div>

    <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />

    <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full">
        <ul class="flex flex-col mb-0">

            <li class="mt-0.5 w-full">
                <?php $dash_class = customerIsActive('customer/dashboard', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $dash_class; ?>" href="<?php echo $base_url; ?>customer/dashboard">
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-blue-500 ni ni-tv-2"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Dashboard</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <?php $res_class = customerIsActive('customer/reservations', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $res_class; ?>" href="<?php echo $base_url; ?>customer/reservations">
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-emerald-500 ni ni-calendar-grid-58"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">My Reservations</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <?php $pay_class = customerIsActive('customer/payments', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $pay_class; ?>" href="<?php echo $base_url; ?>customer/payments">
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-violet-500 ni ni-credit-card"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">My Payments</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <?php $prof_class = customerIsActive('profile', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $prof_class; ?>" href="<?php echo $base_url; ?>profile">
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-orange-500 ni ni-single-02"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">My Profile</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg" href="<?php echo $base_url; ?>logout">
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-red-500 ni ni-button-power"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Sign Out</span>
                </a>
            </li>

        </ul>
    </div>
</aside>
