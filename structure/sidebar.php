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

// Normalize a URL/route by stripping leading slash for consistent comparison
function normalizeSidebarUrl($url)
{
    return ltrim($url, '/');
}

// Function to check if a page is active
function isPageActive($route, $current_url)
{
    return normalizeSidebarUrl($current_url) === normalizeSidebarUrl($route);
}

// Function to add data-active attribute if link is active
function getActiveAttribute($route, $current_url)
{
    return isPageActive($route, $current_url) ? 'data-active="true"' : '';
}

// Function to check if any page in a dropdown is active
function isDropdownActive($routes, $current_url)
{
    $normalizedCurrent = normalizeSidebarUrl($current_url);
    foreach ($routes as $route) {
        if ($normalizedCurrent === normalizeSidebarUrl($route)) {
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
$inventory_pages = ['inventory/product', 'inventory/inventory-categories', 'inventory/units-of-measure', 'inventory/inventory-locations', 'inventory/suppliers', 'inventory/product-stock-per-location', 'inventory/product-uom-conversions', 'inventory/inventory-transactions', 'inventory/purchase-orders', 'inventory/purchase-orders-receiving', 'inventory/stock-transactions', 'inventory/stock-transfers', 'inventory/inventory-sales', 'inventory/inventory-actions'];
$food_canteen_pages = ['food-canteen/food-categories', 'food-canteen/menu-items', 'food-canteen/recipes', 'food-canteen/food-orders'];
$reservations_pages = ['reservations/reservations', 'reservations/reservation-rooms', 'reservations/reservation-guests', 'reservations/reservation-requirement-types', 'reservations/reservation-requirements', 'reservations/walk-in-guests', 'reservations/check-in-logs', 'reservations/entrance-logs', 'reservations/reservation-promotions', 'reservations/pricing-breakdown'];
$payments_billing_pages = ['payments-billing/payment-methods', 'payments-billing/payments', 'payments-billing/invoices', 'payments-billing/invoice-line-items', 'payments-billing/discounts', 'payments-billing/discount-usages', 'payments-billing/extra-charges', 'payments-billing/billing'];
$settings_pages = ['settings/system-settings', 'settings/company', 'settings/pricing-rules', 'settings/seasonal-rates', 'settings/booking-policies', 'settings/promotions', 'settings/promotion-images', 'settings/notifications', 'settings/notification-recipients', 'settings/rbac-settings', 'content-management/carousel'];
$reports_pages = ['reports/revenue', 'reports/occupancy', 'reports/inventory', 'reports/customers', 'reports/food-canteen', 'reports/financial', 'reports/pos-sales'];
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">

<style>
    /* ── Typography ─────────────────────────────────────── */
    #adminSidebar,
    #adminSidebar * {
        font-family: 'DM Sans', system-ui, sans-serif;
    }

    /* Preserve Nucleo Icons font - must come after the global override */
    #adminSidebar .ni {
        font-family: 'NucleoIcons' !important;
    }

    /* Preserve Font Awesome icons font - must come after the global override */
    #adminSidebar .fas,
    #adminSidebar .far,
    #adminSidebar .fab,
    #adminSidebar .fa-solid,
    #adminSidebar .fa-regular,
    #adminSidebar .fa-brands,
    #adminSidebar [class*="fa-"] {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "FontAwesome" !important;
    }

    #adminSidebar .fas,
    #adminSidebar .fa-solid {
        font-weight: 900 !important;
    }

    #adminSidebar .far,
    #adminSidebar .fa-regular {
        font-weight: 400 !important;
    }

    #adminSidebar .fab,
    #adminSidebar .fa-brands {
        font-family: "Font Awesome 6 Brands" !important;
        font-weight: 400 !important;
    }

    #adminSidebar .sidebar-brand-name {
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    #adminSidebar .sidebar-section-title {
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        font-size: 0.625rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    /* ── Sidebar base ───────────────────────────────────── */
    #adminSidebar {
        background: linear-gradient(180deg, #ffffff 0%, #f8faff 100%) !important;
        border-right: 1px solid rgba(99, 102, 241, 0.08);
        box-shadow: 4px 0 24px rgba(99, 102, 241, 0.06), 0 4px 16px rgba(0,0,0,0.04) !important;
    }

    .dark #adminSidebar {
        background: linear-gradient(180deg, #1e2235 0%, #181c2e 100%) !important;
        border-right: 1px solid rgba(99, 102, 241, 0.12);
        box-shadow: 4px 0 24px rgba(0,0,0,0.3) !important;
    }

    /* ── Brand header ───────────────────────────────────── */
    #adminSidebar .h-19 {
        background: linear-gradient(135deg, #eef2ff 0%, #f0f9ff 100%);
        border-bottom: 1px solid rgba(99, 102, 241, 0.1);
        padding-bottom: 0.25rem;
    }

    .dark #adminSidebar .h-19 {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(6, 182, 212, 0.06) 100%);
        border-bottom: 1px solid rgba(99, 102, 241, 0.2);
    }

    /* ── Nav items ──────────────────────────────────────── */
    #adminSidebar a.ease-nav-brand,
    #adminSidebar button.ease-nav-brand {
        font-size: 0.8125rem;
        font-weight: 500;
        letter-spacing: 0.005em;
        color: #4b5563;
        border-radius: 10px !important;
        transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .dark #adminSidebar a.ease-nav-brand,
    .dark #adminSidebar button.ease-nav-brand {
        color: rgba(255,255,255,0.82);
    }

    #adminSidebar a.ease-nav-brand:hover,
    #adminSidebar button.ease-nav-brand:hover {
        background: rgba(99, 102, 241, 0.07) !important;
        color: #4f46e5 !important;
        transform: translateX(2px);
    }

    .dark #adminSidebar a.ease-nav-brand:hover,
    .dark #adminSidebar button.ease-nav-brand:hover {
        background: rgba(99, 102, 241, 0.15) !important;
        color: #a5b4fc !important;
    }

    /* ── Active state ───────────────────────────────────── */
    :root {
        --sidebar-active-bg: linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(139, 92, 246, 0.08) 100%);
        --sidebar-active-color: #4338ca;
        --sidebar-active-shadow: inset 3px 0 0 #6366f1, 0 2px 8px rgba(99, 102, 241, 0.12);
        --sidebar-active-bg-dark: linear-gradient(135deg, rgba(99, 102, 241, 0.2) 0%, rgba(139, 92, 246, 0.12) 100%);
        --sidebar-active-color-dark: #a5b4fc;
        --sidebar-active-shadow-dark: inset 3px 0 0 #818cf8, 0 2px 8px rgba(99, 102, 241, 0.2);
    }

    #adminSidebar [data-active="true"],
    #adminSidebar .bg-blue-500\/13 {
        background: var(--sidebar-active-bg) !important;
        color: var(--sidebar-active-color) !important;
        font-weight: 600 !important;
        box-shadow: var(--sidebar-active-shadow);
        border-radius: 10px !important;
    }

    .dark #adminSidebar [data-active="true"],
    .dark #adminSidebar .bg-blue-500\/13 {
        background: var(--sidebar-active-bg-dark) !important;
        color: var(--sidebar-active-color-dark) !important;
        box-shadow: var(--sidebar-active-shadow-dark);
    }

    /* ── Icon containers ────────────────────────────────── */
    #adminSidebar .h-8.w-8 {
        background: rgba(255,255,255,0.9);
        border-radius: 8px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08), 0 0 0 1px rgba(0,0,0,0.04);
        transition: all 0.18s ease;
    }

    .dark #adminSidebar .h-8.w-8 {
        background: rgba(255,255,255,0.06);
        box-shadow: 0 1px 4px rgba(0,0,0,0.2), 0 0 0 1px rgba(255,255,255,0.06);
    }

    #adminSidebar a.ease-nav-brand:hover .h-8.w-8,
    #adminSidebar button.ease-nav-brand:hover .h-8.w-8 {
        background: rgba(255,255,255,1);
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15), 0 0 0 1px rgba(99, 102, 241, 0.1);
        transform: scale(1.05);
    }

    /* ── Dropdown sub-items ─────────────────────────────── */
    #adminSidebar .pl-2 a {
        padding-left: 0.875rem !important;
        padding-top: 0.45rem !important;
        padding-bottom: 0.45rem !important;
        font-size: 0.78rem;
        font-weight: 450;
        color: #6b7280;
        border-radius: 8px !important;
        transition: all 0.15s ease;
    }

    .dark #adminSidebar .pl-2 a {
        color: rgba(255,255,255,0.55);
    }

    #adminSidebar .pl-2 a:hover {
        color: #4f46e5 !important;
        background: rgba(99, 102, 241, 0.07) !important;
        transform: translateX(2px);
    }

    .dark #adminSidebar .pl-2 a:hover {
        color: #a5b4fc !important;
    }

    /* Sub-item dot - explicit styles for reliability */
    #adminSidebar .sidebar-dot {
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #94a3b8;
        flex-shrink: 0;
        transition: all 0.15s ease;
    }

    #adminSidebar .pl-2 a:hover .sidebar-dot,
    #adminSidebar .pl-2 a[class*="font-semibold"] .sidebar-dot {
        background-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    }

    .dark #adminSidebar .sidebar-dot {
        background-color: #64748b;
    }

    .dark #adminSidebar .pl-2 a:hover .sidebar-dot,
    .dark #adminSidebar .pl-2 a[class*="font-semibold"] .sidebar-dot {
        background-color: #818cf8;
    }

    /* ── Chevron ────────────────────────────────────────── */
    #adminSidebar .fa-chevron-down {
        color: #9ca3af;
        font-size: 0.65rem !important;
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), color 0.15s ease;
    }

    #adminSidebar button.ease-nav-brand:hover .fa-chevron-down {
        color: #6366f1;
    }

    /* ── Collapse button ────────────────────────────────── */
    #sidebarCollapseBtn {
        background: rgba(255,255,255,0.95) !important;
        border: 1px solid rgba(99, 102, 241, 0.15) !important;
        border-radius: 0 0 0 10px !important;
        color: #6b7280 !important;
        transition: all 0.18s ease !important;
        box-shadow: -2px 2px 8px rgba(99, 102, 241, 0.1) !important;
    }

    #sidebarCollapseBtn:hover {
        background: #eef2ff !important;
        color: #4f46e5 !important;
        border-color: rgba(99, 102, 241, 0.3) !important;
    }

    .dark #sidebarCollapseBtn {
        background: rgba(30, 34, 53, 0.95) !important;
        border-color: rgba(99, 102, 241, 0.2) !important;
        color: rgba(255,255,255,0.6) !important;
    }

    /* ── Help card ──────────────────────────────────────── */
    #adminSidebar .sidebar-help-section > div {
        background: linear-gradient(135deg, #eef2ff 0%, #f0f9ff 100%);
        border: 1px solid rgba(99, 102, 241, 0.15);
        border-radius: 14px !important;
        padding: 0.75rem;
    }

    .dark #adminSidebar .sidebar-help-section > div {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(6, 182, 212, 0.06) 100%);
        border-color: rgba(99, 102, 241, 0.2);
    }

    #adminSidebar .sidebar-help-section h6 {
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        font-weight: 700;
        font-size: 0.8125rem;
        letter-spacing: -0.01em;
    }

    #adminSidebar .sidebar-help-section p {
        font-size: 0.7rem;
        color: #6b7280;
        font-weight: 400;
    }

    /* ── Help buttons ───────────────────────────────────── */
    #adminSidebar a[href*="creative-tim"] {
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important;
        font-size: 0.7rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.04em !important;
        text-transform: uppercase !important;
        border-radius: 9px !important;
        transition: all 0.18s cubic-bezier(0.4,0,0.2,1) !important;
    }

    /* ── Divider ────────────────────────────────────────── */
    #adminSidebar hr {
        border: none;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.2), transparent) !important;
        margin: 0 0.5rem;
    }

    /* ── Section padding ────────────────────────────────── */
    #adminSidebar .flex.flex-col.mb-0 {
        padding: 0.5rem 0.75rem 0.5rem;
        gap: 1px;
    }

    /* ── Scrollbar ──────────────────────────────────────── */
    #adminSidebar::-webkit-scrollbar {
        width: 3px;
    }
    #adminSidebar::-webkit-scrollbar-track {
        background: transparent;
    }
    #adminSidebar::-webkit-scrollbar-thumb {
        background: rgba(99, 102, 241, 0.2);
        border-radius: 10px;
    }
    #adminSidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(99, 102, 241, 0.4);
    }

    /* ── Collapse styles ────────────────────────────────── */
    @media (min-width: 1280px) {
        aside.sidebar-collapsed {
            max-width: 4.5rem !important;
            width: 4.5rem !important;
        }

        body.sidebar-is-collapsed main {
            margin-left: 6rem !important;
        }
    }

    aside.sidebar-collapsed span.truncate,
    aside.sidebar-collapsed .sidebar-label,
    aside.sidebar-collapsed .sidebar-section-title,
    aside.sidebar-collapsed .sidebar-help-section {
        display: none !important;
    }

    aside.sidebar-collapsed .fa-chevron-down {
        display: none !important;
    }

    aside.sidebar-collapsed a.ease-nav-brand,
    aside.sidebar-collapsed button.ease-nav-brand {
        justify-content: center !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }

    aside.sidebar-collapsed .h-8.w-8 {
        margin-right: 0 !important;
    }

    aside.sidebar-collapsed .h-19 a {
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
    }

    /* ── Mobile overlay ─────────────────────────────────── */
    #sidebarOverlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 20, 50, 0.55);
        backdrop-filter: blur(2px);
        z-index: 989;
        cursor: pointer;
    }

    #sidebarOverlay.active {
        display: block;
    }
</style>

<aside id="adminSidebar" class="fixed inset-y-0 flex-wrap items-center justify-between block w-full p-0 my-4 overflow-y-auto antialiased transition-all duration-200 -translate-x-full bg-white border-0 shadow-xl dark:shadow-none dark:bg-slate-850 max-w-64 ease-nav-brand z-990 xl:ml-6 rounded-2xl xl:left-0 xl:translate-x-0" aria-expanded="false">
    <div class="h-19">
        <i class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times dark:text-white text-slate-400 xl:hidden" sidenav-close></i>
        <button id="sidebarCollapseBtn" class="hidden xl:flex absolute top-0 right-0 p-4 cursor-pointer items-center justify-center text-slate-600 hover:text-blue-500 bg-white/80 hover:bg-white dark:text-white dark:hover:text-blue-400 dark:bg-slate-800/80 dark:hover:bg-slate-800 rounded-bl-lg transition-colors duration-200 z-50 shadow-sm" title="Collapse sidebar" aria-label="Toggle Sidebar">
            <i id="sidebarCollapseIcon" class="fas fa-chevron-left text-xs transition-transform duration-300"></i>
        </button>
        <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap dark:text-white text-slate-700" target="_blank">
            <img src="<?php echo $assets_path ?? './assets'; ?>/img/logo.png" class="inline h-full max-w-full transition-all duration-200 dark:hidden ease-nav-brand max-h-8" alt="main_logo" />
            <img src="<?php echo $assets_path ?? './assets'; ?>/img/logo.png" class="hidden h-full max-w-full transition-all duration-200 dark:inline ease-nav-brand max-h-8" alt="main_logo" />
            <span class="ml-1 transition-all duration-200 ease-nav-brand sidebar-label sidebar-brand-name">Paraiso Dashboard</span>
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
                                <a href="<?php echo $base_url; ?>food-canteen/food-categories" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('food-canteen/food-categories', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('food-canteen/food-categories', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Food Categories</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('food_canteen.orders.view')): ?>
                                <a href="<?php echo $base_url; ?>food-canteen/food-orders" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('food-canteen/food-orders', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('food-canteen/food-orders', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Food Orders</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('food_canteen.menu_items.view')): ?>
                                <a href="<?php echo $base_url; ?>food-canteen/menu-items" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('food-canteen/menu-items', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('food-canteen/menu-items', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Menu Items</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('food_canteen.recipes.view')): ?>
                                <a href="<?php echo $base_url; ?>food-canteen/recipes" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('food-canteen/recipes', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('food-canteen/recipes', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Recipes</span>
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
                            <?php if (canShowSubPage('inventory.categories.view')): ?>
                                <a href="<?php echo $base_url; ?>inventory/inventory-categories" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/inventory-categories', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('inventory/inventory-categories', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Categories</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('inventory.sales.view')): ?>
                                <a href="<?php echo $base_url; ?>inventory/inventory-sales" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/inventory-sales', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('inventory/inventory-sales', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Inventory Sales</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('inventory.inventory_transactions.view')): ?>
                                <a href="<?php echo $base_url; ?>inventory/inventory-transactions" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/inventory-transactions', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('inventory/inventory-transactions', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Inventory Transactions</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('inventory.locations.view')): ?>
                                <a href="<?php echo $base_url; ?>inventory/inventory-locations" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/inventory-locations', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('inventory/inventory-locations', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Locations</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('inventory.purchase_orders.view')): ?>
                                <a href="<?php echo $base_url; ?>inventory/purchase-orders-receiving" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/purchase-orders-receiving', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('inventory/purchase-orders-receiving', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">PO Receiving</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('inventory.products.view')): ?>
                                <a href="<?php echo $base_url; ?>inventory/product" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/product', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('inventory/product', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Products</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('inventory.purchase_orders.view')): ?>
                                <a href="<?php echo $base_url; ?>inventory/purchase-orders" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/purchase-orders', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('inventory/purchase-orders', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Purchase Orders</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('inventory.stock_location.view')): ?>
                                <a href="<?php echo $base_url; ?>inventory/product-stock-per-location" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/product-stock-per-location', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('inventory/product-stock-per-location', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Stock by Location</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('inventory.stock_transactions.view')): ?>
                                <a href="<?php echo $base_url; ?>inventory/stock-transactions" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/stock-transactions', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('inventory/stock-transactions', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Stock Transactions</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('inventory.stock_transfers.view')): ?>
                                <a href="<?php echo $base_url; ?>inventory/stock-transfers" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/stock-transfers', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('inventory/stock-transfers', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Stock Transfers</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('inventory.stock_transfers.view')): ?>
                                <a href="<?php echo $base_url; ?>inventory/stock-in" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/stock-in', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('inventory/stock-in', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Stock In</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('inventory.suppliers.view')): ?>
                                <a href="<?php echo $base_url; ?>inventory/suppliers" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/suppliers', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('inventory/suppliers', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Suppliers</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('inventory.units.view')): ?>
                                <a href="<?php echo $base_url; ?>inventory/units-of-measure" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/units-of-measure', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('inventory/units-of-measure', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Units of Measure</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('inventory.uom_conversions.view')): ?>
                                <a href="<?php echo $base_url; ?>inventory/product-uom-conversions" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory/product-uom-conversions', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('inventory/product-uom-conversions', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">UOM Conversions</span>
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
                            <?php if (canShowSubPage('payments.billing.view')): ?>
                                <a href="<?php echo $base_url; ?>payments-billing/billing" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments-billing/billing', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('payments-billing/billing', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Billing</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('payments.discount_usages.view')): ?>
                                <a href="<?php echo $base_url; ?>payments-billing/discount-usages" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments-billing/discount-usages', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('payments-billing/discount-usages', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Discount Usages</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('payments.discounts.view')): ?>
                                <a href="<?php echo $base_url; ?>payments-billing/discounts" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments-billing/discounts', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('payments-billing/discounts', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Discounts</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('payments.extra_charges.view')): ?>
                                <a href="<?php echo $base_url; ?>payments-billing/extra-charges" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments-billing/extra-charges', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('payments-billing/extra-charges', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Extra Charges</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('payments.invoice_line_items.view')): ?>
                                <a href="<?php echo $base_url; ?>payments-billing/invoice-line-items" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments-billing/invoice-line-items', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('payments-billing/invoice-line-items', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Invoice Line Items</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('payments.invoices.view')): ?>
                                <a href="<?php echo $base_url; ?>payments-billing/invoices" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments-billing/invoices', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('payments-billing/invoices', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Invoices</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('payments.payment_methods.view')): ?>
                                <a href="<?php echo $base_url; ?>payments-billing/payment-methods" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments-billing/payment-methods', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('payments-billing/payment-methods', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Payment Methods</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('payments.payments.view')): ?>
                                <a href="<?php echo $base_url; ?>payments-billing/payments" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments-billing/payments', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('payments-billing/payments', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Payments</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
            <?php endif; ?>

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
                            <?php if (canShowSubPage('properties.amenities.view')): ?>
                                <a href="<?php echo $base_url; ?>properties-rooms/amenities" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('properties-rooms/amenities', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('properties-rooms/amenities', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Amenities</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('properties.properties.view')): ?>
                                <a href="<?php echo $base_url; ?>properties-rooms/properties" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('properties-rooms/properties', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('properties-rooms/properties', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Properties</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('properties.room_blocks.view')): ?>
                                <a href="<?php echo $base_url; ?>properties-rooms/room-blocks" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('properties-rooms/room-blocks', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('properties-rooms/room-blocks', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Room Blocks</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('properties.room_categories.view')): ?>
                                <a href="<?php echo $base_url; ?>properties-rooms/room-categories" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('properties-rooms/room-categories', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('properties-rooms/room-categories', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Room Categories</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('properties.rooms.view')): ?>
                                <a href="<?php echo $base_url; ?>properties-rooms/rooms" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('properties-rooms/rooms', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('properties-rooms/rooms', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Rooms</span>
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
                            <?php if (canShowSubPage('reports.customers.view')): ?>
                                <a href="<?php echo $base_url; ?>reports/customers" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reports/customers', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reports/customers', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Customer Reports</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('reports.financial.view')): ?>
                                <a href="<?php echo $base_url; ?>reports/financial" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reports/financial', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reports/financial', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Financial Reports</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('reports.food_canteen.view')): ?>
                                <a href="<?php echo $base_url; ?>reports/food-canteen" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reports/food-canteen', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reports/food-canteen', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Food Canteen Reports</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('reports.inventory.view')): ?>
                                <a href="<?php echo $base_url; ?>reports/inventory" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reports/inventory', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reports/inventory', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Inventory Reports</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('reports.occupancy.view')): ?>
                                <a href="<?php echo $base_url; ?>reports/occupancy" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reports/occupancy', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reports/occupancy', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Occupancy Reports</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('reports.pos_sales.view')): ?>
                                <a href="<?php echo $base_url; ?>reports/pos-sales" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reports/pos-sales', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reports/pos-sales', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">POS Sales Report</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('reports.dashboard.view')): ?>
                                <a href="<?php echo $base_url; ?>reports" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reports', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reports', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Reports Dashboard</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('reports.revenue.view')): ?>
                                <a href="<?php echo $base_url; ?>reports/revenue" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reports/revenue', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reports/revenue', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Revenue Reports</span>
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
                            <?php if (canShowSubPage('reservations.check_in_logs.view')): ?>
                                <a href="<?php echo $base_url; ?>reservations/check-in-logs" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/check-in-logs', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reservations/check-in-logs', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Check-in Logs</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('reservations.entrance_logs.view')): ?>
                                <a href="<?php echo $base_url; ?>reservations/entrance-logs" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/entrance-logs', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reservations/entrance-logs', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Entrance Logs</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('reservations.pricing.view')): ?>
                                <a href="<?php echo $base_url; ?>reservations/pricing-breakdown" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/pricing-breakdown', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reservations/pricing-breakdown', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Pricing Breakdown</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('reservations.requirement_types.view')): ?>
                                <a href="<?php echo $base_url; ?>reservations/reservation-requirement-types" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/reservation-requirement-types', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reservations/reservation-requirement-types', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Requirement Types</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('reservations.requirements.view')): ?>
                                <a href="<?php echo $base_url; ?>reservations/reservation-requirements" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/reservation-requirements', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reservations/reservation-requirements', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Requirements</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('reservations.guests.view')): ?>
                                <a href="<?php echo $base_url; ?>reservations/reservation-guests" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/reservation-guests', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reservations/reservation-guests', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Reservation Guests</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('reservations.promotions.view')): ?>
                                <a href="<?php echo $base_url; ?>reservations/reservation-promotions" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/reservation-promotions', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reservations/reservation-promotions', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Reservation Promotions</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('reservations.rooms.view')): ?>
                                <a href="<?php echo $base_url; ?>reservations/reservation-rooms" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/reservation-rooms', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reservations/reservation-rooms', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Reservation Rooms</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('reservations.reservations.view')): ?>
                                <a href="<?php echo $base_url; ?>reservations/reservations" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/reservations', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reservations/reservations', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Reservations</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('reservations.walk_in_guests.view')): ?>
                                <a href="<?php echo $base_url; ?>reservations/walk-in-guests" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations/walk-in-guests', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('reservations/walk-in-guests', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Walk-In Guests</span>
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
                            <?php if (canShowSubPage('settings.booking_policies.view')): ?>
                                <a href="<?php echo $base_url; ?>settings/booking-policies" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/booking-policies', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('settings/booking-policies', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Booking Policies</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('settings.company.view')): ?>
                                <a href="<?php echo $base_url; ?>settings/company" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/company', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('settings/company', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Company Settings</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('content_management.carousel.view')): ?>
                                <a href="<?php echo $base_url; ?>content-management/carousel" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('content-management/carousel', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('content-management/carousel', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Carousel Slides</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('settings.notification_recipients.view')): ?>
                                <a href="<?php echo $base_url; ?>settings/notification-recipients" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/notification-recipients', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('settings/notification-recipients', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Notification Recipients</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('settings.notifications.view')): ?>
                                <a href="<?php echo $base_url; ?>settings/notifications" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/notifications', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('settings/notifications', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Notifications</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('settings.pricing_rules.view')): ?>
                                <a href="<?php echo $base_url; ?>settings/pricing-rules" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/pricing-rules', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('settings/pricing-rules', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Pricing Rules</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('settings.promotion_images.view')): ?>
                                <a href="<?php echo $base_url; ?>settings/promotion-images" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/promotion-images', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('settings/promotion-images', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Promotion Images</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('settings.promotions.view')): ?>
                                <a href="<?php echo $base_url; ?>settings/promotions" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/promotions', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('settings/promotions', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Promotions</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('settings.rbac.view')): ?>
                                <a href="<?php echo $base_url; ?>settings/rbac-settings" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/rbac-settings', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('settings/rbac-settings', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">RBAC Settings</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('settings.seasonal_rates.view')): ?>
                                <a href="<?php echo $base_url; ?>settings/seasonal-rates" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/seasonal-rates', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('settings/seasonal-rates', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Seasonal Rates</span>
                                </a>
                            <?php endif; ?>
                            <?php if (canShowSubPage('settings.system.view')): ?>
                                <a href="<?php echo $base_url; ?>settings/system-settings" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('settings/system-settings', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg" <?php echo getActiveAttribute('settings/system-settings', $current_url); ?>>
                                    <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                                        <span class="sidebar-dot"></span>
                                    </span>
                                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">System Settings</span>
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
                <h6 class="px-3 text-xs font-bold leading-tight uppercase dark:text-white opacity-60 sidebar-section-title">Account pages</h6>
            </li>

            <?php if (canShowMenuItem('users.view')): ?>
                <li class="mt-0.5 w-full">
                    <?php $users_active = (strpos($current_url, '/users') === 0); ?>
                    <?php $active_class = $users_active ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
                    <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $active_class; ?>" <?php echo $users_active ? 'data-active="true"' : ''; ?> href="<?php echo $base_url; ?>users">
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
                    <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $active_class; ?>" <?php echo $customers_active ? 'data-active="true"' : ''; ?> href="<?php echo $base_url; ?>customers/list">
                        <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                            <i class="text-sm leading-normal text-blue-500 ni ni-circle-08"></i>
                        </span>
                        <span class="duration-300 opacity-100 pointer-events-none ease truncate">Customers</span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="mt-0.5 w-full">
                <?php $contact_active = (strpos($current_url, '/contact-inquiries') === 0); ?>
                <?php $active_class = $contact_active ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $active_class; ?>" <?php echo $contact_active ? 'data-active="true"' : ''; ?> href="<?php echo $base_url; ?>contact-inquiries">
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-orange-500 ni ni-email-83"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Contact Inquiries</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <?php $profile_active = ($current_url === '/profile' || $current_url === '/pages/profile'); ?>
                <?php $active_class = $profile_active ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $active_class; ?>" <?php echo $profile_active ? 'data-active="true"' : ''; ?> href="<?php echo $base_url; ?>profile">
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-slate-700 ni ni-single-02"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Profile</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors dark:text-white dark:opacity-80 hover:bg-emerald-50 dark:hover:bg-slate-700 rounded-lg" href="<?php echo rtrim(dirname(rtrim($base_url, '/')), '/'); ?>/pages/">
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-emerald-500 ni ni-world"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Back to Website</span>
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

    <div class="mx-4 sidebar-help-section" hidden>
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

<div id="sidebarOverlay"></div>

<script>
    // ── Dropdown toggle ───────────────────────────────────────────────────────
    // Single generic toggle used by all dropdown sections.
    // Each section passes its own IDs; localStorage key is derived from sectionKey.
    function toggleDropdown(dropdownId, arrowId, storageKey) {
        const dropdown = document.getElementById(dropdownId);
        const arrow    = document.getElementById(arrowId);
        const sidebar  = document.getElementById('adminSidebar');
        if (!dropdown || !arrow) return;

        const scrollTop = sidebar.scrollTop;
        const isOpen    = !dropdown.classList.contains('hidden');

        dropdown.classList.toggle('hidden', isOpen);
        arrow.classList.toggle('rotate-180', !isOpen);
        localStorage.setItem(storageKey, String(!isOpen));

        // Restore scroll so the sidebar doesn't jump when the list expands/collapses
        setTimeout(() => { sidebar.scrollTop = scrollTop; }, 0);
    }

    // Named wrappers kept so existing onclick="..." attributes continue to work
    function togglePropertyDropdown()        { toggleDropdown('property-dropdown',        'dropdown-arrow',                  'propertyDropdownOpen'); }
    function toggleInventoryDropdown()       { toggleDropdown('inventory-dropdown',        'inventory-dropdown-arrow',        'inventoryDropdownOpen'); }
    function toggleFoodCanteenDropdown()     { toggleDropdown('food-canteen-dropdown',     'food-canteen-dropdown-arrow',     'foodCanteenDropdownOpen'); }
    function toggleReservationsDropdown()    { toggleDropdown('reservations-dropdown',     'reservations-dropdown-arrow',     'reservationsDropdownOpen'); }
    function togglePaymentsBillingDropdown() { toggleDropdown('payments-billing-dropdown', 'payments-billing-dropdown-arrow', 'paymentsBillingDropdownOpen'); }
    function toggleSettingsDropdown()        { toggleDropdown('settings-dropdown',         'settings-dropdown-arrow',         'settingsDropdownOpen'); }
    function toggleReportsDropdown()         { toggleDropdown('reports-dropdown',          'reports-dropdown-arrow',          'reportsDropdownOpen'); }

    // Dropdown state: PHP tells JS which section is currently active.
    // We always force the active section open regardless of localStorage.
    const _phpActiveDropdowns = {
        property:        <?php echo json_encode(isset($property_active) && $property_active); ?>,
        inventory:       <?php echo json_encode(isset($inventory_active) && $inventory_active); ?>,
        foodCanteen:     <?php echo json_encode(isset($food_canteen_active) && $food_canteen_active); ?>,
        reservations:    <?php echo json_encode(isset($reservations_active) && $reservations_active); ?>,
        paymentsBilling: <?php echo json_encode(isset($payments_billing_active) && $payments_billing_active); ?>,
        settings:        <?php echo json_encode(isset($settings_active) && $settings_active); ?>,
        reports:         <?php echo json_encode(isset($reports_active) && $reports_active); ?>
    };

    // ── Restore dropdowns + scroll active item into view on page load ─────────
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('adminSidebar');

        // Map each section to its dropdown/arrow IDs and localStorage key
        const sections = [
            { key: 'property',        storageKey: 'propertyDropdownOpen',        dropdownId: 'property-dropdown',        arrowId: 'dropdown-arrow' },
            { key: 'inventory',       storageKey: 'inventoryDropdownOpen',       dropdownId: 'inventory-dropdown',       arrowId: 'inventory-dropdown-arrow' },
            { key: 'foodCanteen',     storageKey: 'foodCanteenDropdownOpen',     dropdownId: 'food-canteen-dropdown',     arrowId: 'food-canteen-dropdown-arrow' },
            { key: 'reservations',    storageKey: 'reservationsDropdownOpen',    dropdownId: 'reservations-dropdown',    arrowId: 'reservations-dropdown-arrow' },
            { key: 'paymentsBilling', storageKey: 'paymentsBillingDropdownOpen', dropdownId: 'payments-billing-dropdown', arrowId: 'payments-billing-dropdown-arrow' },
            { key: 'settings',        storageKey: 'settingsDropdownOpen',        dropdownId: 'settings-dropdown',        arrowId: 'settings-dropdown-arrow' },
            { key: 'reports',         storageKey: 'reportsDropdownOpen',         dropdownId: 'reports-dropdown',         arrowId: 'reports-dropdown-arrow' },
        ];

        sections.forEach(function (s) {
            const dropdown = document.getElementById(s.dropdownId);
            const arrow    = document.getElementById(s.arrowId);
            if (!dropdown || !arrow) return;

            // PHP active state always wins; otherwise respect what the user last chose
            const shouldOpen = _phpActiveDropdowns[s.key]
                || localStorage.getItem(s.storageKey) === 'true';

            if (shouldOpen) {
                dropdown.classList.remove('hidden');
                arrow.classList.add('rotate-180');
                // Keep localStorage in sync so re-opens survive future refreshes
                localStorage.setItem(s.storageKey, 'true');
            } else {
                dropdown.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        });

        // Scroll the active item into view now that dropdowns are expanded.
        // Two passes handle late reflows (e.g. CSS transitions finishing).
        function scrollActiveIntoView() {
            const activeItem = sidebar.querySelector('[data-active="true"]');
            if (activeItem) {
                activeItem.scrollIntoView({ block: 'nearest', behavior: 'instant' });
            }
        }
        setTimeout(scrollActiveIntoView, 50);
        setTimeout(scrollActiveIntoView, 250);
    });

    // ── Sidebar collapse + mobile overlay ─────────────────────────────────────
    (function () {
        var COLLAPSE_KEY = 'adminSidebarCollapsed';
        var sidebar      = document.getElementById('adminSidebar');
        var collapseBtn  = document.getElementById('sidebarCollapseBtn');
        var collapseIcon = document.getElementById('sidebarCollapseIcon');
        var overlay      = document.getElementById('sidebarOverlay');

        if (!sidebar) return;

        function setNavTooltips(collapsed) {
            sidebar.querySelectorAll('a.ease-nav-brand, button.ease-nav-brand').forEach(function (item) {
                if (collapsed) {
                    var label = item.querySelector('span.truncate');
                    if (label) item.setAttribute('title', label.textContent.trim());
                } else {
                    item.removeAttribute('title');
                }
            });
        }

        function closeAllDropdowns() {
            ['property-dropdown','inventory-dropdown','food-canteen-dropdown',
             'reservations-dropdown','payments-billing-dropdown','settings-dropdown','reports-dropdown'
            ].forEach(function (id) {
                var el = document.getElementById(id);
                if (el) el.classList.add('hidden');
            });
            ['dropdown-arrow','inventory-dropdown-arrow','food-canteen-dropdown-arrow',
             'reservations-dropdown-arrow','payments-billing-dropdown-arrow','settings-dropdown-arrow','reports-dropdown-arrow'
            ].forEach(function (id) {
                var el = document.getElementById(id);
                if (el) el.classList.remove('rotate-180');
            });
        }

        function applySidebarCollapse(save) {
            sidebar.classList.add('sidebar-collapsed');
            document.body.classList.add('sidebar-is-collapsed');
            if (collapseIcon) { collapseIcon.classList.replace('fa-chevron-left', 'fa-chevron-right'); }
            if (collapseBtn)  { collapseBtn.setAttribute('title', 'Expand sidebar'); }
            closeAllDropdowns();
            setNavTooltips(true);
            if (save) localStorage.setItem(COLLAPSE_KEY, 'true');
        }

        function applySidebarExpand(save) {
            sidebar.classList.remove('sidebar-collapsed');
            document.body.classList.remove('sidebar-is-collapsed');
            if (collapseIcon) { collapseIcon.classList.replace('fa-chevron-right', 'fa-chevron-left'); }
            if (collapseBtn)  { collapseBtn.setAttribute('title', 'Collapse sidebar'); }
            setNavTooltips(false);
            if (save) localStorage.setItem(COLLAPSE_KEY, 'false');
        }

        if (collapseBtn) {
            collapseBtn.addEventListener('click', function () {
                sidebar.classList.contains('sidebar-collapsed')
                    ? applySidebarExpand(true)
                    : applySidebarCollapse(true);
            });
        }

        if (localStorage.getItem(COLLAPSE_KEY) === 'true') { applySidebarCollapse(false); }

        if (overlay) {
            overlay.addEventListener('click', function () {
                sidebar.classList.add('-translate-x-full');
                sidebar.setAttribute('aria-expanded', 'false');
                overlay.classList.remove('active');
            });
        }

        var sidenavTrigger = document.querySelector('[sidenav-trigger]');
        if (sidenavTrigger && overlay) {
            sidenavTrigger.addEventListener('click', function () { overlay.classList.add('active'); });
        }

        var sidenavCloseEl = document.querySelector('[sidenav-close]');
        if (sidenavCloseEl && overlay) {
            sidenavCloseEl.addEventListener('click', function () { overlay.classList.remove('active'); });
        }
    })();
</script>