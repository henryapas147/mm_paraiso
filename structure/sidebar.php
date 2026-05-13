<?php
// Load RBAC class
require_once __DIR__ . '/../build/core/RBAC.php';

// Get current URL for highlighting active link
$current_url = $_SERVER['REQUEST_URI'] ?? '/';
// Remove query string from URL
$current_url = strtok($current_url, '?');

// Remove base path if needed (the path to the build directory)
$base_path = dirname($_SERVER['PHP_SELF']);
if ($base_path != '/' && $base_path != '\\') {
    $current_url = str_replace($base_path, '', $current_url);
}
// Ensure current_url starts with /
if (substr($current_url, 0, 1) != '/') {
    $current_url = '/' . $current_url;
}

// base_url is passed from Controller
// If not set or empty, calculate it from SCRIPT_NAME
if (!isset($base_url) || empty($base_url)) {
    $script_name = $_SERVER['SCRIPT_NAME'];
    $script_dir = dirname($script_name);
    if (substr($script_dir, -1) != '/') {
        $script_dir .= '/';
    }
    $base_url = $script_dir;
}

// Function to check if a page is active
function isPageActive($route, $current_url)
{
    return $current_url === $route;
}

// Function to add data-active attribute if link is active
function getActiveAttribute($route, $current_url)
{
    return isPageActive($route, $current_url) ? 'data-active="true"' : '';
}

// Function to check if any page in a dropdown is active
function isDropdownActive($routes, $current_url)
{
    foreach ($routes as $route) {
        if ($current_url === $route) {
            return true;
        }
    }
    return false;
}

// Function to check if user has permission for a menu item
function canShowMenuItem($permission)
{
    return \App\Core\RBAC::hasPermission($permission);
}

// Function to check if user has any of the specified permissions
function canShowAnyMenuItem($permissions)
{
    return \App\Core\RBAC::hasAnyPermission($permissions);
}

// Function to check if user can view a sub-page (backward compatible)
function canShowSubPage($subPermission)
{
    return \App\Core\RBAC::canAccessSubPage($subPermission);
}

// Define dropdown pages with MVC routes
$property_pages = ['properties-rooms/properties', 'properties-rooms/rooms', 'properties-rooms/room-categories', 'properties-rooms/amenities', 'properties-rooms/room-blocks'];
$inventory_pages = ['inventory/product', 'inventory/inventory-categories', 'inventory/units-of-measure', 'inventory/inventory-locations', 'inventory/suppliers', 'inventory/product-stock-per-location', 'inventory/purchase-orders', 'inventory/stock-transactions', 'inventory/stock-transfers', 'inventory/inventory-sales', 'inventory/inventory-actions'];
$food_canteen_pages = ['food-canteen/food-categories', 'food-canteen/menu-items', 'food-canteen/food-orders'];
$reservations_pages = ['reservations/reservations', 'reservations/reservation-rooms', 'reservations/reservation-guests', 'reservations/reservation-requirement-types', 'reservations/reservation-requirements', 'reservations/check-in-logs', 'reservations/entrance-logs'];
$payments_billing_pages = ['payments-billing/payment-methods', 'payments-billing/payments', 'payments-billing/invoices', 'payments-billing/invoice-line-items', 'payments-billing/discounts', 'payments-billing/discount-usages', 'payments-billing/extra-charges', 'payments-billing/billing'];
$settings_pages = ['settings/system-settings', 'settings/pricing-rules', 'settings/seasonal-rates', 'settings/booking-policies', 'settings/promotions', 'settings/promotion-images', 'settings/notifications', 'settings/notification-recipients', 'settings/rbac-settings'];
$reports_pages = ['reports/revenue', 'reports/occupancy', 'reports/inventory', 'reports/customers', 'reports/food-canteen', 'reports/financial'];
?>

<aside class="fixed inset-y-0 flex-wrap items-center justify-between block w-full p-0 my-4 overflow-y-auto antialiased transition-transform duration-200 -translate-x-full bg-white border-0 shadow-xl dark:shadow-none dark:bg-slate-850 max-w-64 ease-nav-brand z-990 xl:ml-6 rounded-2xl xl:left-0 xl:translate-x-0" aria-expanded="false">
    <div class="h-19">
        <i class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times dark:text-white text-slate-400 xl:hidden" sidenav-close></i>
        <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap dark:text-white text-slate-700" href="https://demos.creative-tim.com/argon-dashboard-tailwind/pages/dashboard.php" target="_blank">
            <img src="<?php echo $assets_path ?? './assets'; ?>/img/logo-ct-dark.png" class="inline h-full max-w-full transition-all duration-200 dark:hidden ease-nav-brand max-h-8" alt="main_logo" />
            <img src="<?php echo $assets_path ?? './assets'; ?>/img/logo-ct.png" class="hidden h-full max-w-full transition-all duration-200 dark:inline ease-nav-brand max-h-8" alt="main_logo" />
            <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">Paradise Dashboard </span>
        </a>
    </div>

    <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />

    <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full">
        <ul class="flex flex-col mb-0">

            <li class="mt-0.5 w-full">
                <?php $active_class = ($current_url === '' || $current_url === '/') ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $active_class; ?>" <?php echo ($current_url === '' || $current_url === '/') ? 'data-active="true"' : ''; ?> href="<?php echo $base_url; ?>">
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-blue-500 ni ni-tv-2"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Dashboard</span>
                </a>
            </li>

            <?php if (canShowAnyMenuItem(['properties.view', 'rooms.view'])): ?>
            <li class="mt-0.5 w-full">
                <div class="relative">
                    <?php $property_active = isDropdownActive($property_pages, $current_url); ?>
                    <?php $property_button_class = $property_active ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?>
                    <button onclick="togglePropertyDropdown()" class="py-2.5 text-sm ease-nav-brand my-0 flex items-center justify-between w-full px-3 transition-colors <?php echo $property_button_class; ?> rounded-lg">
                        <div class="flex items-center min-w-0">
                            <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                                <i class="text-sm leading-normal text-orange-500 ni ni-calendar-grid-58"></i>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Properties & Rooms</span>
                        </div>
                        <i id="dropdown-arrow" class="fas fa-chevron-down text-xs ml-2 flex-shrink-0 transition-transform duration-200"></i>
                    </button>

                    <div id="property-dropdown" class="hidden pl-2 mt-1 space-y-0.5">
                        <?php if (canShowSubPage('properties.properties.view')): ?>
                        <a href="<?php echo $base_url; ?>properties-rooms/properties" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('properties-rooms/properties', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Properties</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('properties.rooms.view')): ?>
                        <a href="<?php echo $base_url; ?>properties-rooms/rooms" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('properties-rooms/rooms', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Rooms</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('properties.room_categories.view')): ?>
                        <a href="<?php echo $base_url; ?>properties-rooms/room-categories" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('properties-rooms/room-categories', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Room Categories</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('properties.amenities.view')): ?>
                        <a href="<?php echo $base_url; ?>properties-rooms/amenities" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('properties-rooms/amenities', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Amenities</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('properties.room_blocks.view')): ?>
                        <a href="<?php echo $base_url; ?>properties-rooms/room-blocks" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('properties-rooms/room-blocks', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Room Blocks</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endif; ?>

            <?php if (canShowMenuItem('inventory.view')): ?>
            <li class="mt-0.5 w-full">
                <div class="relative">
                    <?php $inventory_active = isDropdownActive($inventory_pages, $current_url); ?>
                    <?php $inventory_button_class = $inventory_active ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?>
                    <button onclick="toggleInventoryDropdown()" class="py-2.5 text-sm ease-nav-brand my-0 flex items-center justify-between w-full px-3 transition-colors <?php echo $inventory_button_class; ?> rounded-lg">
                        <div class="flex items-center min-w-0">
                            <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                                <i class="text-sm leading-normal text-emerald-500 ni ni-bag-17"></i>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Inventory</span>
                        </div>
                        <i id="inventory-dropdown-arrow" class="fas fa-chevron-down text-xs ml-2 flex-shrink-0 transition-transform duration-200"></i>
                    </button>

                    <div id="inventory-dropdown" class="hidden pl-2 mt-1 space-y-0.5">
                        <?php if (canShowSubPage('inventory.products.view')): ?>
                        <a href="<?php echo $base_url; ?>inventory/product" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/product', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Products</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('inventory.categories.view')): ?>
                        <a href="<?php echo $base_url; ?>inventory/inventory-categories" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/inventory-categories', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Categories</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('inventory.units.view')): ?>
                        <a href="<?php echo $base_url; ?>inventory/units-of-measure" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/units-of-measure', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Units of Measure</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('inventory.locations.view')): ?>
                        <a href="<?php echo $base_url; ?>inventory/inventory-locations" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/inventory-locations', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Locations</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('inventory.suppliers.view')): ?>
                        <a href="<?php echo $base_url; ?>inventory/suppliers" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/suppliers', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Suppliers</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('inventory.stock_location.view')): ?>
                        <a href="<?php echo $base_url; ?>inventory/product-stock-per-location" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/product-stock-per-location', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Stock by Location</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('inventory.purchase_orders.view')): ?>
                        <a href="<?php echo $base_url; ?>inventory/purchase-orders" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/purchase-orders', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Purchase Orders</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('inventory.stock_transactions.view')): ?>
                        <a href="<?php echo $base_url; ?>inventory/stock-transactions" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/stock-transactions', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Stock Transactions</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('inventory.stock_transfers.view')): ?>
                        <a href="<?php echo $base_url; ?>inventory/stock-transfers" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/stock-transfers', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Stock Transfers</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('inventory.sales.view')): ?>
                        <a href="<?php echo $base_url; ?>inventory/inventory-sales" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/inventory-sales', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Inventory Sales</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endif; ?>

            <?php if (canShowMenuItem('food_canteen.view')): ?>
            <li class="mt-0.5 w-full">
                <div class="relative">
                    <?php $food_canteen_active = isDropdownActive($food_canteen_pages, $current_url); ?>
                    <?php $food_canteen_button_class = $food_canteen_active ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?>
                    <button onclick="toggleFoodCanteenDropdown()" class="py-2.5 text-sm ease-nav-brand my-0 flex items-center justify-between w-full px-3 transition-colors <?php echo $food_canteen_button_class; ?> rounded-lg">
                        <div class="flex items-center min-w-0">
                            <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                                <i class="text-sm leading-normal text-red-500 ni ni-fat-add"></i>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Food & Canteen</span>
                        </div>
                        <i id="food-canteen-dropdown-arrow" class="fas fa-chevron-down text-xs ml-2 flex-shrink-0 transition-transform duration-200"></i>
                    </button>

                    <div id="food-canteen-dropdown" class="hidden pl-2 mt-1 space-y-0.5">
                        <?php if (canShowSubPage('food_canteen.categories.view')): ?>
                        <a href="<?php echo $base_url; ?>food-canteen/food-categories" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('food-canteen/food-categories', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Food Categories</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('food_canteen.menu_items.view')): ?>
                        <a href="<?php echo $base_url; ?>food-canteen/menu-items" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('food-canteen/menu-items', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Menu Items</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('food_canteen.orders.view')): ?>
                        <a href="<?php echo $base_url; ?>food-canteen/food-orders" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('food-canteen/food-orders', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Food Orders</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endif; ?>

            <?php if (canShowMenuItem('reservations.view')): ?>
            <li class="mt-0.5 w-full">
                <div class="relative">
                    <?php $reservations_active = isDropdownActive($reservations_pages, $current_url); ?>
                    <?php $reservations_button_class = $reservations_active ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?>
                    <button onclick="toggleReservationsDropdown()" class="py-2.5 text-sm ease-nav-brand my-0 flex items-center justify-between w-full px-3 transition-colors <?php echo $reservations_button_class; ?> rounded-lg">
                        <div class="flex items-center min-w-0">
                            <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                                <i class="text-sm leading-normal text-blue-500 ni ni-calendar-grid-58"></i>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Reservations</span>
                        </div>
                        <i id="reservations-dropdown-arrow" class="fas fa-chevron-down text-xs ml-2 flex-shrink-0 transition-transform duration-200"></i>
                    </button>

                    <div id="reservations-dropdown" class="hidden pl-2 mt-1 space-y-0.5">
                        <?php if (canShowSubPage('reservations.reservations.view')): ?>
                        <a href="<?php echo $base_url; ?>reservations/reservations" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/reservations', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Reservations</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('reservations.rooms.view')): ?>
                        <a href="<?php echo $base_url; ?>reservations/reservation-rooms" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/reservation-rooms', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Reservation Rooms</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('reservations.guests.view')): ?>
                        <a href="<?php echo $base_url; ?>reservations/reservation-guests" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/reservation-guests', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Reservation Guests</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('reservations.requirement_types.view')): ?>
                        <a href="<?php echo $base_url; ?>reservations/reservation-requirement-types" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/reservation-requirement-types', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Requirement Types</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('reservations.requirements.view')): ?>
                        <a href="<?php echo $base_url; ?>reservations/reservation-requirements" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/reservation-requirements', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Requirements</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('reservations.check_in_logs.view')): ?>
                        <a href="<?php echo $base_url; ?>reservations/check-in-logs" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/check-in-logs', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Check-in Logs</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('reservations.entrance_logs.view')): ?>
                        <a href="<?php echo $base_url; ?>reservations/entrance-logs" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/entrance-logs', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Entrance Logs</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endif; ?>

            <?php if (canShowMenuItem('payments.view')): ?>
            <li class="mt-0.5 w-full">
                <div class="relative">
                    <?php $payments_billing_active = isDropdownActive($payments_billing_pages, $current_url); ?>
                    <?php $payments_billing_button_class = $payments_billing_active ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?>
                    <button onclick="togglePaymentsBillingDropdown()" class="py-2.5 text-sm ease-nav-brand my-0 flex items-center justify-between w-full px-3 transition-colors <?php echo $payments_billing_button_class; ?> rounded-lg">
                        <div class="flex items-center min-w-0">
                            <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                                <i class="text-sm leading-normal text-emerald-500 ni ni-credit-card"></i>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Payments & Billing</span>
                        </div>
                        <i id="payments-billing-dropdown-arrow" class="fas fa-chevron-down text-xs ml-2 flex-shrink-0 transition-transform duration-200"></i>
                    </button>

                    <div id="payments-billing-dropdown" class="hidden pl-2 mt-1 space-y-0.5">
                        <?php if (canShowSubPage('payments.payment_methods.view')): ?>
                        <a href="<?php echo $base_url; ?>payments-billing/payment-methods" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments-billing/payment-methods', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Payment Methods</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('payments.payments.view')): ?>
                        <a href="<?php echo $base_url; ?>payments-billing/payments" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments-billing/payments', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Payments</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('payments.invoices.view')): ?>
                        <a href="<?php echo $base_url; ?>payments-billing/invoices" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments-billing/invoices', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Invoices</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('payments.invoice_line_items.view')): ?>
                        <a href="<?php echo $base_url; ?>payments-billing/invoice-line-items" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments-billing/invoice-line-items', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Invoice Line Items</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('payments.discounts.view')): ?>
                        <a href="<?php echo $base_url; ?>payments-billing/discounts" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments-billing/discounts', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Discounts</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('payments.discount_usages.view')): ?>
                        <a href="<?php echo $base_url; ?>payments-billing/discount-usages" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments-billing/discount-usages', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Discount Usages</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('payments.extra_charges.view')): ?>
                        <a href="<?php echo $base_url; ?>payments-billing/extra-charges" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments-billing/extra-charges', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Extra Charges</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('payments.billing.view')): ?>
                        <a href="<?php echo $base_url; ?>payments-billing/billing" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments-billing/billing', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Billing</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endif; ?>

            <?php if (canShowMenuItem('settings.view')): ?>
            <li class="mt-0.5 w-full">
                <div class="relative">
                    <?php $settings_active = isDropdownActive($settings_pages, $current_url); ?>
                    <?php $settings_button_class = $settings_active ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?>
                    <button onclick="toggleSettingsDropdown()" class="py-2.5 text-sm ease-nav-brand my-0 flex items-center justify-between w-full px-3 transition-colors <?php echo $settings_button_class; ?> rounded-lg">
                        <div class="flex items-center min-w-0">
                            <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                                <i class="text-sm leading-normal text-purple-500 ni ni-settings"></i>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Settings & Promotions</span>
                        </div>
                        <i id="settings-dropdown-arrow" class="fas fa-chevron-down text-xs ml-2 flex-shrink-0 transition-transform duration-200"></i>
                    </button>

                    <div id="settings-dropdown" class="hidden pl-2 mt-1 space-y-0.5">
                        <?php if (canShowSubPage('settings.system.view')): ?>
                        <a href="<?php echo $base_url; ?>settings/system-settings" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/system-settings', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">System Settings</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('settings.pricing_rules.view')): ?>
                        <a href="<?php echo $base_url; ?>settings/pricing-rules" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/pricing-rules', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Pricing Rules</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('settings.seasonal_rates.view')): ?>
                        <a href="<?php echo $base_url; ?>settings/seasonal-rates" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/seasonal-rates', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Seasonal Rates</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('settings.booking_policies.view')): ?>
                        <a href="<?php echo $base_url; ?>settings/booking-policies" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/booking-policies', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Booking Policies</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('settings.promotions.view')): ?>
                        <a href="<?php echo $base_url; ?>settings/promotions" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/promotions', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Promotions</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('settings.promotion_images.view')): ?>
                        <a href="<?php echo $base_url; ?>settings/promotion-images" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/promotion-images', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Promotion Images</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('settings.notifications.view')): ?>
                        <a href="<?php echo $base_url; ?>settings/notifications" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/notifications', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Notifications</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('settings.notification_recipients.view')): ?>
                        <a href="<?php echo $base_url; ?>settings/notification-recipients" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/notification-recipients', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Notification Recipients</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('settings.rbac.view')): ?>
                        <a href="<?php echo $base_url; ?>settings/rbac-settings" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/rbac-settings', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">RBAC Settings</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endif; ?>

            <?php if (canShowMenuItem('reports.view')): ?>
            <li class="mt-0.5 w-full">
                <div class="relative">
                    <?php $reports_active = isDropdownActive($reports_pages, $current_url); ?>
                    <?php $reports_button_class = $reports_active ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?>
                    <button onclick="toggleReportsDropdown()" class="py-2.5 text-sm ease-nav-brand my-0 flex items-center justify-between w-full px-3 transition-colors <?php echo $reports_button_class; ?> rounded-lg">
                        <div class="flex items-center min-w-0">
                            <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                                <i class="text-sm leading-normal text-purple-500 ni ni-chart-bar-32"></i>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Reports</span>
                        </div>
                        <i id="reports-dropdown-arrow" class="fas fa-chevron-down text-xs ml-2 flex-shrink-0 transition-transform duration-200"></i>
                    </button>

                    <div id="reports-dropdown" class="hidden pl-2 mt-1 space-y-0.5">
                        <?php if (canShowSubPage('reports.dashboard.view')): ?>
                        <a href="<?php echo $base_url; ?>reports" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reports', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Reports Dashboard</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('reports.revenue.view')): ?>
                        <a href="<?php echo $base_url; ?>reports/revenue" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reports/revenue', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Revenue Reports</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('reports.occupancy.view')): ?>
                        <a href="<?php echo $base_url; ?>reports/occupancy" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reports/occupancy', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Occupancy Reports</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('reports.inventory.view')): ?>
                        <a href="<?php echo $base_url; ?>reports/inventory" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reports/inventory', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Inventory Reports</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('reports.customers.view')): ?>
                        <a href="<?php echo $base_url; ?>reports/customers" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reports/customers', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Customer Reports</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('reports.food_canteen.view')): ?>
                        <a href="<?php echo $base_url; ?>reports/food-canteen" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reports/food-canteen', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Food Canteen Reports</span>
                        </a>
                        <?php endif; ?>
                        <?php if (canShowSubPage('reports.financial.view')): ?>
                        <a href="<?php echo $base_url; ?>reports/financial" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reports/financial', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
                            <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            </span>
                            <span class="duration-300 opacity-100 pointer-events-none ease truncate">Financial Reports</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endif; ?>

            <li class="mt-0.5 w-full">
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg hidden" href="../pages/virtual-reality.php">
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-cyan-500 ni ni-app"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Virtual Reality</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg hidden" href="../pages/rtl.php">
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-red-600 ni ni-world-2"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">RTL</span>
                </a>
            </li>

            <li class="w-full mt-4">
                <h6 class="px-3 text-xs font-bold leading-tight uppercase dark:text-white opacity-60">Account pages</h6>
            </li>

            <?php if (canShowMenuItem('users.view')): ?>
            <li class="mt-0.5 w-full">
                <?php $users_active = (strpos($current_url, '/users') === 0); ?>
                <?php $active_class = $users_active ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $active_class; ?>" href="<?php echo $base_url; ?>users">
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-indigo-500 ni ni-single-02"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">User Accounts</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (canShowMenuItem('customers.view')): ?>
            <li class="mt-0.5 w-full">
                <?php $customers_active = ($current_url === '/customers' || $current_url === '/customers/list'); ?>
                <?php $active_class = $customers_active ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $active_class; ?>" href="<?php echo $base_url; ?>customers/list">
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-blue-500 ni ni-circle-08"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Customers</span>
                </a>
            </li>
            <?php endif; ?>

            <li class="mt-0.5 w-full">
                <?php $profile_active = ($current_url === '/profile' || $current_url === '/pages/profile'); ?>
                <?php $active_class = $profile_active ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $active_class; ?>" href="<?php echo $base_url; ?>profile">
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-slate-700 ni ni-single-02"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Profile</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg" href="../pages/logout.php">
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-orange-500 ni ni-button-power"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Log Out</span>
                </a>
            </li>

            <li class="mt-0.5 w-full mb-4">
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg" href="../pages/sign-up.php">
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-cyan-500 ni ni-collection"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Sign Up</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="mx-4">
        <!-- load phantom colors for card after: -->
        <p class="invisible hidden text-gray-800 text-red-500 text-red-600 text-blue-500 bg-gray-500/30 bg-cyan-500/30 bg-emerald-500/30 bg-orange-500/30 bg-red-500/30 after:bg-gradient-to-tl after:from-zinc-800 after:to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 after:from-blue-700 after:to-cyan-500 after:from-orange-500 after:to-yellow-500 after:from-green-600 after:to-lime-400 after:from-red-600 after:to-orange-600 after:from-slate-600 after:to-slate-300 text-emerald-500 text-cyan-500 text-slate-400"></p>
        <div class="relative flex flex-col min-w-0 break-words bg-transparent border-0 shadow-none rounded-2xl bg-clip-border" sidenav-card>
            <img class="w-1/2 mx-auto" src="<?php echo $assets_path ?? './assets'; ?>/img/illustrations/icon-documentation.svg" alt="sidebar illustrations" />
            <div class="flex-auto w-full p-4 pt-0 text-center">
                <div class="transition-all duration-200 ease-nav-brand">
                    <h6 class="mb-0 dark:text-white text-slate-700">Need help?</h6>
                    <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">Please check our docs</p>
                </div>
            </div>
        </div>
        <a href="https://www.creative-tim.com/learning-lab/tailwind/html/quick-start/argon-dashboard/" target="_blank" class="inline-block w-full px-8 py-2 mb-4 text-xs font-bold leading-normal text-center text-white capitalize transition-all ease-in rounded-lg shadow-md bg-slate-700 bg-150 hover:shadow-xs hover:-translate-y-px">Documentation</a>
        <!-- pro btn  -->
        <a class="inline-block w-full px-8 py-2 text-xs font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md select-none bg-150 bg-x-25 hover:shadow-xs hover:-translate-y-px" href="https://www.creative-tim.com/product/argon-dashboard-pro-tailwind?ref=sidebarfree" target="_blank">Upgrade to pro</a>
    </div>
</aside>

<script>
    function togglePropertyDropdown() {
        const dropdown = document.getElementById('property-dropdown');
        const arrow = document.getElementById('dropdown-arrow');
        const sidebar = document.querySelector('aside');

        // Save current scroll position
        const scrollTop = sidebar.scrollTop;

        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            arrow.classList.add('rotate-180');
            localStorage.setItem('propertyDropdownOpen', 'true');
        } else {
            dropdown.classList.add('hidden');
            arrow.classList.remove('rotate-180');
            localStorage.setItem('propertyDropdownOpen', 'false');
        }

        // Restore scroll position to prevent jumping
        setTimeout(() => {
            sidebar.scrollTop = scrollTop;
        }, 0);
    }

    function toggleInventoryDropdown() {
        const dropdown = document.getElementById('inventory-dropdown');
        const arrow = document.getElementById('inventory-dropdown-arrow');
        const sidebar = document.querySelector('aside');

        // Save current scroll position
        const scrollTop = sidebar.scrollTop;

        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            arrow.classList.add('rotate-180');
            localStorage.setItem('inventoryDropdownOpen', 'true');
        } else {
            dropdown.classList.add('hidden');
            arrow.classList.remove('rotate-180');
            localStorage.setItem('inventoryDropdownOpen', 'false');
        }

        // Restore scroll position to prevent jumping
        setTimeout(() => {
            sidebar.scrollTop = scrollTop;
        }, 0);
    }

    function toggleSettingsDropdown() {
        const dropdown = document.getElementById('settings-dropdown');
        const arrow = document.getElementById('settings-dropdown-arrow');
        const sidebar = document.querySelector('aside');

        // Save current scroll position
        const scrollTop = sidebar.scrollTop;

        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            arrow.classList.add('rotate-180');
            localStorage.setItem('settingsDropdownOpen', 'true');
        } else {
            dropdown.classList.add('hidden');
            arrow.classList.remove('rotate-180');
            localStorage.setItem('settingsDropdownOpen', 'false');
        }

        // Restore scroll position to prevent jumping
        setTimeout(() => {
            sidebar.scrollTop = scrollTop;
        }, 0);
    }

    function toggleFoodCanteenDropdown() {
        const dropdown = document.getElementById('food-canteen-dropdown');
        const arrow = document.getElementById('food-canteen-dropdown-arrow');
        const sidebar = document.querySelector('aside');

        // Save current scroll position
        const scrollTop = sidebar.scrollTop;

        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            arrow.classList.add('rotate-180');
            localStorage.setItem('foodCanteenDropdownOpen', 'true');
        } else {
            dropdown.classList.add('hidden');
            arrow.classList.remove('rotate-180');
            localStorage.setItem('foodCanteenDropdownOpen', 'false');
        }

        // Restore scroll position to prevent jumping
        setTimeout(() => {
            sidebar.scrollTop = scrollTop;
        }, 0);
    }

    function toggleReservationsDropdown() {
        const dropdown = document.getElementById('reservations-dropdown');
        const arrow = document.getElementById('reservations-dropdown-arrow');
        const sidebar = document.querySelector('aside');

        // Save current scroll position
        const scrollTop = sidebar.scrollTop;

        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            arrow.classList.add('rotate-180');
            localStorage.setItem('reservationsDropdownOpen', 'true');
        } else {
            dropdown.classList.add('hidden');
            arrow.classList.remove('rotate-180');
            localStorage.setItem('reservationsDropdownOpen', 'false');
        }

        // Restore scroll position to prevent jumping
        setTimeout(() => {
            sidebar.scrollTop = scrollTop;
        }, 0);
    }

    function toggleReportsDropdown() {
        const dropdown = document.getElementById('reports-dropdown');
        const arrow = document.getElementById('reports-dropdown-arrow');
        const sidebar = document.querySelector('aside');

        // Save current scroll position
        const scrollTop = sidebar.scrollTop;

        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            arrow.classList.add('rotate-180');
            localStorage.setItem('reportsDropdownOpen', 'true');
        } else {
            dropdown.classList.add('hidden');
            arrow.classList.remove('rotate-180');
            localStorage.setItem('reportsDropdownOpen', 'false');
        }

        // Restore scroll position to prevent jumping
        setTimeout(() => {
            sidebar.scrollTop = scrollTop;
        }, 0);
    }

    function togglePaymentsBillingDropdown() {
        const dropdown = document.getElementById('payments-billing-dropdown');
        const arrow = document.getElementById('payments-billing-dropdown-arrow');
        const sidebar = document.querySelector('aside');

        // Save current scroll position
        const scrollTop = sidebar.scrollTop;

        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            arrow.classList.add('rotate-180');
            localStorage.setItem('paymentsBillingDropdownOpen', 'true');
        } else {
            dropdown.classList.add('hidden');
            arrow.classList.remove('rotate-180');
            localStorage.setItem('paymentsBillingDropdownOpen', 'false');
        }

        // Restore scroll position to prevent jumping
        setTimeout(() => {
            sidebar.scrollTop = scrollTop;
        }, 0);
    }

    // Seed localStorage from PHP active state (only if not already set by user interaction)
    <?php if (isset($property_active) && $property_active): ?>if(localStorage.getItem('propertyDropdownOpen') === null) localStorage.setItem('propertyDropdownOpen', 'true');
    <?php endif; ?>
    <?php if (isset($inventory_active) && $inventory_active): ?>if(localStorage.getItem('inventoryDropdownOpen') === null) localStorage.setItem('inventoryDropdownOpen', 'true');
    <?php endif; ?>
    <?php if (isset($food_canteen_active) && $food_canteen_active): ?>if(localStorage.getItem('foodCanteenDropdownOpen') === null) localStorage.setItem('foodCanteenDropdownOpen', 'true');
    <?php endif; ?>
    <?php if (isset($reservations_active) && $reservations_active): ?>if(localStorage.getItem('reservationsDropdownOpen') === null) localStorage.setItem('reservationsDropdownOpen', 'true');
    <?php endif; ?>
    <?php if (isset($payments_billing_active) && $payments_billing_active): ?>if(localStorage.getItem('paymentsBillingDropdownOpen') === null) localStorage.setItem('paymentsBillingDropdownOpen', 'true');
    <?php endif; ?>
    <?php if (isset($settings_active) && $settings_active): ?>if(localStorage.getItem('settingsDropdownOpen') === null) localStorage.setItem('settingsDropdownOpen', 'true');
    <?php endif; ?>
    <?php if (isset($reports_active) && $reports_active): ?>if(localStorage.getItem('reportsDropdownOpen') === null) localStorage.setItem('reportsDropdownOpen', 'true');
    <?php endif; ?>

    // On active page, always force the correct dropdown open
    <?php if (isset($property_active) && $property_active): ?>localStorage.setItem('propertyDropdownOpen', 'true');
    <?php endif; ?>
    <?php if (isset($inventory_active) && $inventory_active): ?>localStorage.setItem('inventoryDropdownOpen', 'true');
    <?php endif; ?>
    <?php if (isset($food_canteen_active) && $food_canteen_active): ?>localStorage.setItem('foodCanteenDropdownOpen', 'true');
    <?php endif; ?>
    <?php if (isset($reservations_active) && $reservations_active): ?>localStorage.setItem('reservationsDropdownOpen', 'true');
    <?php endif; ?>
    <?php if (isset($payments_billing_active) && $payments_billing_active): ?>localStorage.setItem('paymentsBillingDropdownOpen', 'true');
    <?php endif; ?>
    <?php if (isset($settings_active) && $settings_active): ?>localStorage.setItem('settingsDropdownOpen', 'true');
    <?php endif; ?>
    <?php if (isset($reports_active) && $reports_active): ?>localStorage.setItem('reportsDropdownOpen', 'true');
    <?php endif; ?>

    // Restore dropdown state and scroll position on page load
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('aside');

        // Restore sidebar scroll position with slight delay to ensure rendering
        const savedScrollPosition = localStorage.getItem('sidebarScrollPosition');
        if (savedScrollPosition) {
            setTimeout(() => {
                sidebar.scrollTop = parseInt(savedScrollPosition);
            }, 100);
        }

        // Save scroll position before any navigation
        const allLinks = document.querySelectorAll('aside a');
        allLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                localStorage.setItem('sidebarScrollPosition', sidebar.scrollTop);
            });
        });
        // Property dropdown
        const dropdown = document.getElementById('property-dropdown');
        const arrow = document.getElementById('dropdown-arrow');
        const isOpen = localStorage.getItem('propertyDropdownOpen') === 'true';

        if (isOpen) {
            dropdown.classList.remove('hidden');
            arrow.classList.add('rotate-180');
        } else {
            dropdown.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }

        // Prevent dropdown from closing when clicking on links inside
        const dropdownLinks = document.querySelectorAll('#property-dropdown a');
        dropdownLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Keep dropdown open when navigating to new page
                localStorage.setItem('propertyDropdownOpen', 'true');
                // Scroll position is already saved by the global link handler above
            });
        });

        // Inventory dropdown
        const inventoryDropdown = document.getElementById('inventory-dropdown');
        const inventoryArrow = document.getElementById('inventory-dropdown-arrow');
        const isInventoryOpen = localStorage.getItem('inventoryDropdownOpen') === 'true';

        if (isInventoryOpen) {
            inventoryDropdown.classList.remove('hidden');
            inventoryArrow.classList.add('rotate-180');
        } else {
            inventoryDropdown.classList.add('hidden');
            inventoryArrow.classList.remove('rotate-180');
        }

        // Prevent inventory dropdown from closing when clicking on links inside
        const inventoryDropdownLinks = document.querySelectorAll('#inventory-dropdown a');
        inventoryDropdownLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Keep dropdown open when navigating to new page
                localStorage.setItem('inventoryDropdownOpen', 'true');
            });
        });

        // Settings dropdown
        const settingsDropdown = document.getElementById('settings-dropdown');
        const settingsArrow = document.getElementById('settings-dropdown-arrow');
        const isSettingsOpen = localStorage.getItem('settingsDropdownOpen') === 'true';

        if (isSettingsOpen) {
            settingsDropdown.classList.remove('hidden');
            settingsArrow.classList.add('rotate-180');
        } else {
            settingsDropdown.classList.add('hidden');
            settingsArrow.classList.remove('rotate-180');
        }

        // Prevent settings dropdown from closing when clicking on links inside
        const settingsDropdownLinks = document.querySelectorAll('#settings-dropdown a');
        settingsDropdownLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Keep dropdown open when navigating to new page
                localStorage.setItem('settingsDropdownOpen', 'true');
            });
        });

        // Food & Canteen dropdown
        const foodCanteenDropdown = document.getElementById('food-canteen-dropdown');
        const foodCanteenArrow = document.getElementById('food-canteen-dropdown-arrow');
        const isFoodCanteenOpen = localStorage.getItem('foodCanteenDropdownOpen') === 'true';

        if (isFoodCanteenOpen) {
            foodCanteenDropdown.classList.remove('hidden');
            foodCanteenArrow.classList.add('rotate-180');
        } else {
            foodCanteenDropdown.classList.add('hidden');
            foodCanteenArrow.classList.remove('rotate-180');
        }

        // Prevent food canteen dropdown from closing when clicking on links inside
        const foodCanteenDropdownLinks = document.querySelectorAll('#food-canteen-dropdown a');
        foodCanteenDropdownLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Keep dropdown open when navigating to new page
                localStorage.setItem('foodCanteenDropdownOpen', 'true');
            });
        });

        // Reservations dropdown
        const reservationsDropdown = document.getElementById('reservations-dropdown');
        const reservationsArrow = document.getElementById('reservations-dropdown-arrow');
        const isReservationsOpen = localStorage.getItem('reservationsDropdownOpen') === 'true';

        if (isReservationsOpen) {
            reservationsDropdown.classList.remove('hidden');
            reservationsArrow.classList.add('rotate-180');
        } else {
            reservationsDropdown.classList.add('hidden');
            reservationsArrow.classList.remove('rotate-180');
        }

        // Prevent reservations dropdown from closing when clicking on links inside
        const reservationsDropdownLinks = document.querySelectorAll('#reservations-dropdown a');
        reservationsDropdownLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Keep dropdown open when navigating to new page
                localStorage.setItem('reservationsDropdownOpen', 'true');
            });
        });

        // Payments & Billing dropdown
        const paymentsBillingDropdown = document.getElementById('payments-billing-dropdown');
        const paymentsBillingArrow = document.getElementById('payments-billing-dropdown-arrow');
        const isPaymentsBillingOpen = localStorage.getItem('paymentsBillingDropdownOpen') === 'true';

        if (isPaymentsBillingOpen) {
            paymentsBillingDropdown.classList.remove('hidden');
            paymentsBillingArrow.classList.add('rotate-180');
        } else {
            paymentsBillingDropdown.classList.add('hidden');
            paymentsBillingArrow.classList.remove('rotate-180');
        }

        // Prevent payments billing dropdown from closing when clicking on links inside
        const paymentsBillingDropdownLinks = document.querySelectorAll('#payments-billing-dropdown a');
        paymentsBillingDropdownLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Keep dropdown open when navigating to new page
                localStorage.setItem('paymentsBillingDropdownOpen', 'true');
            });
        });

        // Reports dropdown
        const reportsDropdown = document.getElementById('reports-dropdown');
        const reportsArrow = document.getElementById('reports-dropdown-arrow');
        const isReportsOpen = localStorage.getItem('reportsDropdownOpen') === 'true';

        if (reportsDropdown && reportsArrow) {
            if (isReportsOpen) {
                reportsDropdown.classList.remove('hidden');
                reportsArrow.classList.add('rotate-180');
            } else {
                reportsDropdown.classList.add('hidden');
                reportsArrow.classList.remove('rotate-180');
            }
        }

        // Prevent reports dropdown from closing when clicking on links inside
        const reportsDropdownLinks = document.querySelectorAll('#reports-dropdown a');
        reportsDropdownLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Keep dropdown open when navigating to new page
                localStorage.setItem('reportsDropdownOpen', 'true');
            });
        });
    });
</script>