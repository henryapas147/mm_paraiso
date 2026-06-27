<?php
// Load RBAC class for role checking
require_once __DIR__ . '/../build/core/RBAC.php';
use App\Core\RBAC;

// Get current route from URL for dynamic breadcrumb (supports clean URL routing)
$request_uri = strtok($_SERVER['REQUEST_URI'], '?');
$base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$current_route = trim(str_replace($base_path, '', $request_uri), '/');

// Calculate base_url if not set (same logic as sidebar.php)
if (!isset($base_url) || empty($base_url)) {
    $script_name = $_SERVER['SCRIPT_NAME'];
    $script_dir = dirname($script_name);
    if (substr($script_dir, -1) != '/') {
        $script_dir .= '/';
    }
    $base_url = $script_dir;
}

// Check if user can access POS
$canAccessPOS = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && RBAC::canAccessPOS();

// Get user's profile photo for navbar
// Dynamic default profile image path based on environment
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$basePath = dirname($_SERVER['SCRIPT_NAME']); // Gets /admin_booking/structure or equivalent
// Default profile is in build/assets/images, so we need to adjust basePath
$defaultProfilePath = dirname($basePath) . '/build/assets/images/default_profile.jpg';
$currentUserPhoto = $defaultProfilePath;

// Detect environment
$isLocalhost = in_array($host, ['localhost', '127.0.0.1']) || preg_match('/^192\.168\./', $host) || preg_match('/^10\./', $host);

function normalizeProfilePhotoUrl($url, $isLocalhost, $basePath)
{
    if (empty($url) || strpos($url, 'default_profile') !== false) {
        return null;
    }

    // Strip protocol and host if URL is absolute (e.g. http://localhost/...)
    if (preg_match('#^https?://[^/]+(.+)$#', $url, $matches)) {
        $url = $matches[1];
    }

    // Strip protocol-relative prefix (//uploads/...)
    if (strpos($url, '//') === 0) {
        $url = substr($url, 1);
    }

    // Ensure path starts with exactly one /
    $url = '/' . ltrim($url, '/');

    // If it's already in the current environment format, return as-is
    if ($isLocalhost) {
        // Localhost expects /admin_booking/uploads/profile_photos/
        if (strpos($url, '/admin_booking/uploads/profile_photos/') === 0) {
            return $url;
        }
        // Convert Hostinger path to localhost path
        if (strpos($url, '/uploads/profile_photos/') === 0) {
            $filename = basename($url);
            return '/admin_booking/uploads/profile_photos/' . $filename;
        }
        // Handle old /build/assets/images/ format
        if (strpos($url, '/build/assets/images/uploads/profile_photos/') === 0) {
            $filename = basename($url);
            return '/admin_booking/uploads/profile_photos/' . $filename;
        }
    } else {
        // Hostinger expects /uploads/profile_photos/
        if (strpos($url, '/uploads/profile_photos/') === 0) {
            return $url;
        }
        // Convert localhost path to Hostinger path
        if (strpos($url, '/admin_booking/uploads/profile_photos/') === 0) {
            $filename = basename($url);
            return '/uploads/profile_photos/' . $filename;
        }
        // Handle old /build/assets/images/ format
        if (strpos($url, '/build/assets/images/uploads/profile_photos/') === 0) {
            $filename = basename($url);
            return '/uploads/profile_photos/' . $filename;
        }
    }

    return $url;
}

// Check if profile data is available (from ProfileController)
if (isset($profile) && is_array($profile) && !empty($profile['profile_photo_url'])) {
    $normalizedUrl = normalizeProfilePhotoUrl($profile['profile_photo_url'], $isLocalhost, $basePath);
    if ($normalizedUrl) {
        $currentUserPhoto = $normalizedUrl;
    }
}
// Check if user data is available (from other controllers)
elseif (isset($user) && is_array($user) && !empty($user['profile_photo_url'])) {
    $normalizedUrl = normalizeProfilePhotoUrl($user['profile_photo_url'], $isLocalhost, $basePath);
    if ($normalizedUrl) {
        $currentUserPhoto = $normalizedUrl;
    }
}
// Fallback: fetch from database
elseif (isset($_SESSION['user_id'])) {
    if (isset($GLOBALS['pdo'])) {
        try {
            $stmt = $GLOBALS['pdo']->prepare("SELECT profile_photo_url FROM user_profiles WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $userPhoto = $stmt->fetchColumn();
            if (!empty($userPhoto)) {
                $normalizedUrl = normalizeProfilePhotoUrl($userPhoto, $isLocalhost, $basePath);
                if ($normalizedUrl) {
                    $currentUserPhoto = $normalizedUrl;
                }
            }
        } catch (Exception $e) {
            // Use default if query fails
        }
    }
}

// Function to get breadcrumb info based on current route
function getBreadcrumbInfo($route)
{
    $breadcrumb_map = [
        '' => ['Dashboard', 'Dashboard'],
        'properties-rooms' => ['Properties & Rooms', 'Properties & Rooms'],
        'properties-rooms/properties' => ['Properties & Rooms', 'Properties'],
        'properties-rooms/rooms' => ['Properties & Rooms', 'Rooms'],
        'properties-rooms/room-categories' => ['Properties & Rooms', 'Room Categories'],
        'properties-rooms/amenities' => ['Properties & Rooms', 'Amenities'],
        'properties-rooms/room-blocks' => ['Properties & Rooms', 'Room Blocks'],
        'inventory' => ['Inventory', 'Inventory'],
        'inventory/product' => ['Inventory', 'Products'],
        'inventory/inventory-categories' => ['Inventory', 'Categories'],
        'inventory/units-of-measure' => ['Inventory', 'Units of Measure'],
        'inventory/inventory-locations' => ['Inventory', 'Locations'],
        'inventory/suppliers' => ['Inventory', 'Suppliers'],
        'inventory/product-stock-per-location' => ['Inventory', 'Stock by Location'],
        'inventory/purchase-orders' => ['Inventory', 'Purchase Orders'],
        'inventory/stock-transactions' => ['Inventory', 'Stock Transactions'],
        'inventory/stock-transfers' => ['Inventory', 'Stock Transfers'],
        'inventory/inventory-sales' => ['Inventory', 'Inventory Sales'],
        'inventory/inventory-actions' => ['Inventory', 'Inventory Actions'],
        'inventory/pos' => ['Inventory', 'Point of Sale'],
        'food-canteen' => ['Food & Canteen', 'Food & Canteen'],
        'food-canteen/food-categories' => ['Food & Canteen', 'Food Categories'],
        'food-canteen/menu-items' => ['Food & Canteen', 'Menu Items'],
        'food-canteen/food-orders' => ['Food & Canteen', 'Food Orders'],
        'reservations' => ['Reservations', 'Reservations'],
        'reservations/reservations' => ['Reservations', 'Reservations'],
        'reservations/reservation-rooms' => ['Reservations', 'Reservation Rooms'],
        'reservations/reservation-guests' => ['Reservations', 'Reservation Guests'],
        'reservations/reservation-requirement-types' => ['Reservations', 'Requirement Types'],
        'reservations/reservation-requirements' => ['Reservations', 'Requirements'],
        'reservations/check-in-logs' => ['Reservations', 'Check-in Logs'],
        'reservations/entrance-logs' => ['Reservations', 'Entrance Logs'],
        'payments-billing' => ['Payments & Billing', 'Payments & Billing'],
        'payments-billing/payment-methods' => ['Payments & Billing', 'Payment Methods'],
        'payments-billing/payments' => ['Payments & Billing', 'Payments'],
        'payments-billing/invoices' => ['Payments & Billing', 'Invoices'],
        'payments-billing/invoice-line-items' => ['Payments & Billing', 'Invoice Line Items'],
        'payments-billing/discounts' => ['Payments & Billing', 'Discounts'],
        'payments-billing/discount-usages' => ['Payments & Billing', 'Discount Usages'],
        'payments-billing/extra-charges' => ['Payments & Billing', 'Extra Charges'],
        'payments-billing/billing' => ['Payments & Billing', 'Billing'],
        'settings/rbac-settings' => ['Settings', 'RBAC Settings'],
        'settings' => ['Settings', 'Settings'],
        'settings/system-settings' => ['Settings', 'System Settings'],
        'settings/pricing-rules' => ['Settings', 'Pricing Rules'],
        'settings/seasonal-rates' => ['Settings', 'Seasonal Rates'],
        'settings/booking-policies' => ['Settings', 'Booking Policies'],
        'settings/promotions' => ['Settings', 'Promotions'],
        'settings/promotion-images' => ['Settings', 'Promotion Images'],
        'settings/notifications' => ['Settings', 'Notifications'],
        'settings/notification-recipients' => ['Settings', 'Notification Recipients'],
        'profile' => ['Account', 'Profile'],
        'account-settings' => ['Account', 'Account Settings'],
        'sign-in' => ['Account', 'Sign In'],
        'sign-up' => ['Account', 'Sign Up'],
    ];

    return $breadcrumb_map[$route] ?? ['Pages', ucwords(str_replace('-', ' ', basename($route)))];
}

$breadcrumb = getBreadcrumbInfo($current_route);
?>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Lora:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">
<style>
    /* ── Navbar — Design System ── */
    .navbar-enhanced {
        --nb-gold: #C9A96E;
        --nb-gold-dark: #A07840;
        --nb-gold-light: #E8D5B0;
        --nb-ink: #1A1410;
        --nb-ink-soft: #3D3530;
        --nb-surface: #FFFFFF;
        --nb-surface-2: #FAFAF8;
        --nb-border: #EDEAE6;
        --nb-border-gold: rgba(201, 169, 110, .22);
        --nb-radius-md: 10px;
        --nb-radius-lg: 16px;
        --nb-shadow: 0 1px 3px rgba(0, 0, 0, .05), 0 4px 16px rgba(0, 0, 0, .07);
        font-family: 'DM Sans', system-ui, sans-serif;
        color: var(--nb-ink);
    }

    /* Preserve icon fonts */
    .navbar-enhanced .ni {
        font-family: 'NucleoIcons' !important;
    }

    .navbar-enhanced .fa,
    .navbar-enhanced .fas,
    .navbar-enhanced .far,
    .navbar-enhanced .fab {
        font-family: 'Font Awesome 6 Free', 'Font Awesome 6 Brands' !important;
    }

    /* ── Breadcrumb & page title ── */
    .navbar-enhanced nav nav ol li a {
        color: rgba(255, 255, 255, .7) !important;
        transition: color .2s ease;
    }

    .navbar-enhanced nav nav ol li a:hover {
        color: var(--nb-gold-light) !important;
    }

    .navbar-enhanced nav nav h6 {
        font-family: 'Lora', Georgia, serif !important;
        font-weight: 500 !important;
        font-style: italic !important;
        letter-spacing: .02em !important;
    }

    /* ── Search input ── */
    .navbar-enhanced input[type="text"] {
        border-color: var(--nb-border-gold) !important;
        border-radius: var(--nb-radius-md) !important;
        font-size: .85rem !important;
        color: var(--nb-ink) !important;
    }

    .navbar-enhanced input[type="text"]:focus {
        border-color: var(--nb-gold) !important;
        box-shadow: 0 0 0 3px rgba(201, 169, 110, .15) !important;
    }

    .navbar-enhanced input[type="text"]::placeholder {
        color: var(--nb-ink-soft) !important;
        opacity: .5 !important;
    }

    /* ── User dropdown ── */
    .navbar-enhanced [user-dropdown-menu] {
        border: 1px solid var(--nb-border) !important;
        border-radius: var(--nb-radius-lg) !important;
        box-shadow: var(--nb-shadow) !important;
    }

    .navbar-enhanced [user-dropdown-menu] a h6 {
        font-family: 'DM Sans', sans-serif !important;
        font-size: .85rem !important;
        font-weight: 500 !important;
        color: var(--nb-ink-soft) !important;
    }

    .navbar-enhanced [user-dropdown-menu] a:hover h6 {
        color: var(--nb-gold-dark) !important;
    }

    .navbar-enhanced [user-dropdown-menu] .border-t {
        border-color: var(--nb-border) !important;
    }

    /* ── Notification dropdown ── */
    .navbar-enhanced [dropdown-menu] {
        border: 1px solid var(--nb-border) !important;
        border-radius: var(--nb-radius-lg) !important;
        box-shadow: var(--nb-shadow) !important;
    }

    .navbar-enhanced [dropdown-menu] h6 {
        font-family: 'DM Sans', sans-serif !important;
        font-size: .7rem !important;
        font-weight: 600 !important;
        letter-spacing: .1em !important;
        text-transform: uppercase !important;
        color: var(--nb-gold-dark) !important;
    }

    .navbar-enhanced [dropdown-menu] .border-b,
    .navbar-enhanced [dropdown-menu] .border-t {
        border-color: var(--nb-border) !important;
    }

    .navbar-enhanced [dropdown-menu] a h6 {
        font-family: 'DM Sans', sans-serif !important;
        font-size: .85rem !important;
        font-weight: 500 !important;
        color: var(--nb-ink-soft) !important;
    }

    .navbar-enhanced [dropdown-menu] a:hover h6 {
        color: var(--nb-gold-dark) !important;
    }

    .navbar-enhanced [dropdown-menu] a.text-blue-500 {
        color: var(--nb-gold-dark) !important;
        font-weight: 600 !important;
    }

    .navbar-enhanced [dropdown-menu] a.text-blue-500:hover {
        color: var(--nb-gold) !important;
    }

    /* ── Notification badge ── */
    .navbar-enhanced #notifBadge {
        background: linear-gradient(135deg, var(--nb-gold), var(--nb-gold-dark)) !important;
        border: 2px solid white !important;
    }

    /* ── Profile photo ── */
    .navbar-enhanced #navbarProfilePhoto {
        border: 2px solid var(--nb-gold-light) !important;
        transition: border-color .2s ease;
    }

    .navbar-enhanced #navbarProfilePhoto:hover {
        border-color: var(--nb-gold) !important;
    }

    /* ── RESPONSIVE NAVBAR ── */
    @media (max-width: 1400px) {
        .navbar-enhanced nav nav ol {
            margin-right: 2rem !important;
        }
    }

    @media (max-width: 1200px) {
        .navbar-enhanced nav nav ol {
            margin-right: 1.5rem !important;
        }
        .navbar-enhanced nav nav h6 {
            font-size: 1.5rem !important;
        }
    }

    @media (max-width: 992px) {
        .navbar-enhanced nav nav ol {
            margin-right: 1rem !important;
        }
        .navbar-enhanced nav nav h6 {
            font-size: 1.25rem !important;
        }
        .navbar-enhanced .relative.flex.flex-wrap.items-stretch.w-full {
            width: 140px !important;
            max-width: 140px !important;
            flex-shrink: 0 !important;
            flex-grow: 0 !important;
        }
        .navbar-enhanced input[type="text"] {
            width: 100px !important;
        }
        .navbar-enhanced .flex.items-center.md:ml-auto.md:pr-4 {
            flex-shrink: 0 !important;
            margin-left: 0.5rem !important;
            margin-right: 0.5rem !important;
        }
        .navbar-enhanced .flex.items-center.mt-2.grow.sm:mt-0.sm:mr-6.md:mr-0.lg\:flex.lg\:basis-auto {
            flex-wrap: nowrap !important;
        }
    }

    @media (max-width: 768px) {
        .navbar-enhanced nav nav ol {
            margin-right: 0.5rem !important;
        }
        .navbar-enhanced nav nav h6 {
            font-size: 1.1rem !important;
        }
        .navbar-enhanced nav.flex-shrink-0.min-w-0 {
            margin-right: 1.5rem !important;
            flex-shrink: 1 !important;
        }
        .navbar-enhanced .relative.flex.flex-wrap.items-stretch.w-full {
            width: 120px !important;
            max-width: 120px !important;
            flex-shrink: 0 !important;
            flex-grow: 0 !important;
        }
        .navbar-enhanced input[type="text"] {
            width: 90px !important;
            font-size: .75rem !important;
        }
        .navbar-enhanced .flex.items-center.md:ml-auto.md:pr-4 {
            flex-shrink: 0 !important;
            margin-left: 1rem !important;
            margin-right: 0.75rem !important;
        }
        .navbar-enhanced .flex.items-center.mt-2.grow.sm:mt-0.sm:mr-6.md:mr-0.lg\:flex.lg\:basis-auto {
            flex-wrap: nowrap !important;
            gap: 0.75rem !important;
        }
        .navbar-enhanced #navbarProfilePhoto {
            width: 32px !important;
            height: 32px !important;
        }
        .navbar-enhanced [user-dropdown-menu],
        .navbar-enhanced [dropdown-menu] {
            min-width: 200px !important;
            right: -10px !important;
        }
    }

    @media (max-width: 640px) {
        .navbar-enhanced nav nav ol {
            margin-right: 0.25rem !important;
        }
        .navbar-enhanced nav nav h6 {
            font-size: 1rem !important;
        }
        .navbar-enhanced input[type="text"] {
            width: 100px !important;
            padding-left: 2rem !important;
            padding-right: 0.5rem !important;
        }
        .navbar-enhanced input[type="text"]::placeholder {
            font-size: 0.7rem !important;
        }
        .navbar-enhanced .fa-search {
            font-size: 0.7rem !important;
        }
        .navbar-enhanced #navbarProfilePhoto {
            width: 28px !important;
            height: 28px !important;
        }
        .navbar-enhanced [user-dropdown-menu],
        .navbar-enhanced [dropdown-menu] {
            min-width: 180px !important;
            right: -5px !important;
        }
        .navbar-enhanced [user-dropdown-menu] h6,
        .navbar-enhanced [dropdown-menu] h6 {
            font-size: .75rem !important;
        }
    }

    @media (max-width: 480px) {
        .navbar-enhanced nav nav ol {
            margin-right: 0 !important;
        }
        .navbar-enhanced nav nav ol li {
            font-size: .75rem !important;
        }
        .navbar-enhanced nav nav h6 {
            font-size: 0.9rem !important;
        }
        .navbar-enhanced input[type="text"] {
            width: 80px !important;
            padding: 0.5rem 0.5rem 0.5rem 1.75rem !important;
            font-size: .7rem !important;
        }
        .navbar-enhanced input[type="text"]::placeholder {
            font-size: 0.65rem !important;
        }
        .navbar-enhanced .fa-search {
            font-size: 0.65rem !important;
            padding: 0.5rem !important;
        }
        .navbar-enhanced #navbarProfilePhoto {
            width: 26px !important;
            height: 26px !important;
        }
        .navbar-enhanced #notifBadge {
            width: 14px !important;
            height: 14px !important;
            font-size: 9px !important;
        }
        .navbar-enhanced [user-dropdown-menu],
        .navbar-enhanced [dropdown-menu] {
            min-width: 160px !important;
            right: 0 !important;
        }
        .navbar-enhanced [user-dropdown-menu] a,
        .navbar-enhanced [dropdown-menu] a {
            padding: 0.5rem 0.75rem !important;
        }
        .navbar-enhanced [user-dropdown-menu] h6,
        .navbar-enhanced [dropdown-menu] h6 {
            font-size: .7rem !important;
        }
        .navbar-enhanced [user-dropdown-menu] i,
        .navbar-enhanced [dropdown-menu] i {
            font-size: 0.75rem !important;
            margin-right: 0.5rem !important;
        }
    }

    @media (max-width: 360px) {
        .navbar-enhanced nav nav h6 {
            font-size: 0.85rem !important;
        }
        .navbar-enhanced input[type="text"] {
            width: 60px !important;
        }
        .navbar-enhanced input[type="text"]::placeholder {
            display: none !important;
        }
        .navbar-enhanced #navbarProfilePhoto {
            width: 24px !important;
            height: 24px !important;
        }
        .navbar-enhanced [user-dropdown-menu],
        .navbar-enhanced [dropdown-menu] {
            min-width: 140px !important;
        }
    }

    /* Search Results Dropdown Styles */
    #searchResultsDropdown {
        min-width: 320px;
        animation: searchDropdownFadeIn 0.15s ease-out;
        z-index: 9999 !important;
    }

    @keyframes searchDropdownFadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .search-result-item {
        display: flex;
        align-items: center;
        padding: 0.625rem 1rem;
        cursor: pointer;
        transition: background-color 0.15s ease;
        text-decoration: none;
        color: inherit;
    }

    .search-result-item:hover {
        background-color: #f8f9fa;
    }

    .dark .search-result-item:hover {
        background-color: #334155;
    }

    .search-result-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .search-result-icon.product {
        background-color: #e9ecef;
        color: #5e72e4;
    }

    .search-result-icon.reservation {
        background-color: #d4edda;
        color: #28a745;
    }

    .search-result-icon.room {
        background-color: #fff3cd;
        color: #fd7e14;
    }

    .search-result-icon.customer {
        background-color: #f3e8ff;
        color: #825ee4;
    }

    .search-result-icon.sidebar {
        background-color: #e7f3ff;
        color: #1171ef;
    }

    .search-result-icon .search-result-product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 0.5rem;
    }

    .search-result-content {
        flex: 1;
        min-width: 0;
    }

    .search-result-title {
        font-size: 0.8125rem;
        font-weight: 600;
        color: #344767;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.5;
    }

    .dark .search-result-title {
        color: #f3f4f6;
    }

    .search-result-subtitle {
        font-size: 0.75rem;
        color: #6c757d;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.5;
    }

    .dark .search-result-subtitle {
        color: #adb5bd;
    }

    .search-result-type {
        font-size: 0.625rem;
        padding: 0.125rem 0.5rem;
        border-radius: 0.75rem;
        background-color: #e9ecef;
        color: #495057;
        text-transform: uppercase;
        font-weight: 600;
        flex-shrink: 0;
        margin-left: 0.5rem;
    }

    .dark .search-result-type {
        background-color: #495057;
        color: #dee2e6;
    }

    .search-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
        color: #6c757d;
    }

    .search-no-results {
        padding: 1.25rem;
        text-align: center;
        color: #6c757d;
        font-size: 0.8125rem;
    }

    .search-section-header {
        padding: 0.5rem 1rem;
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #adb5bd;
        letter-spacing: 0.5px;
    }

    #searchContainer {
        position: relative;
    }

    #searchResultsDropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        margin-top: 0.5rem;
    }
</style>

<nav class="navbar-enhanced relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all ease-in shadow-none duration-250 rounded-2xl lg:flex-nowrap lg:justify-start" navbar-main navbar-scroll="false">
    <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
        <nav class="flex-shrink-0 min-w-0">
            <!-- breadcrumb -->
            <ol class="flex flex-wrap pt-1 mr-4 md:mr-8 lg:mr-12 xl:mr-16 bg-transparent rounded-lg">
                <?php if ($breadcrumb[0] !== $breadcrumb[1]): ?>
                    <li class="text-sm leading-normal">
                        <a class="text-white opacity-50" href="javascript:;"><?php echo htmlspecialchars($breadcrumb[0]); ?></a>
                    </li>
                    <li class="text-sm pl-2 capitalize leading-normal text-white before:float-left before:pr-2 before:text-white before:content-['/']" aria-current="page"><?php echo htmlspecialchars($breadcrumb[1]); ?></li>
                <?php else: ?>
                    <li class="text-sm capitalize leading-normal text-white" aria-current="page"><?php echo htmlspecialchars($breadcrumb[1]); ?></li>
                <?php endif; ?>
            </ol>
            <h6 class="mb-0 font-bold text-white capitalize truncate" style="font-size: 2rem;"><?php echo htmlspecialchars($breadcrumb[1]); ?></h6>
        </nav>

        <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
            <div class="flex items-center md:ml-auto md:pr-4">
                <div class="relative flex flex-wrap items-stretch w-full transition-all rounded-lg ease" id="searchContainer">
                    <span class="text-sm ease leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="globalSearchInput" class="pl-9 text-sm focus:shadow-primary-outline ease w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" placeholder="Search products, reservations, rooms..." autocomplete="off" />

                    <!-- Search Results Dropdown -->
                    <div id="searchResultsDropdown" class="hidden absolute top-full left-0 right-0 mt-2 bg-white dark:bg-slate-850 rounded-lg shadow-xl border border-gray-200 dark:border-slate-700 z-50 max-h-96 overflow-y-auto">
                        <div id="searchResultsContent" class="py-2">
                            <!-- Results will be populated here -->
                        </div>
                        <div id="searchResultsFooter" class="hidden border-t border-gray-200 dark:border-slate-700 px-4 py-2 text-xs text-gray-500 dark:text-slate-400">
                            <span id="searchResultsCount">0</span> results found
                        </div>
                    </div>
                </div>
            </div>
            <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
                <!-- online builder btn  -->
                <!-- <li class="flex items-center">
                <a class="inline-block px-8 py-2 mb-0 mr-4 text-xs font-bold text-center text-blue-500 uppercase align-middle transition-all ease-in bg-transparent border border-blue-500 border-solid rounded-lg shadow-none cursor-pointer leading-pro hover:-translate-y-px active:shadow-xs hover:border-blue-500 active:bg-blue-500 active:hover:text-blue-500 hover:text-blue-500 tracking-tight-rem hover:bg-transparent hover:opacity-75 hover:shadow-none active:text-white active:hover:bg-transparent" target="_blank" href="https://www.creative-tim.com/builder/soft-ui?ref=navbar-dashboard&amp;_ga=2.76518741.1192788655.1647724933-1242940210.1644448053">Online Builder</a>
              </li> -->
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    <!-- user dropdown -->
                    <li class="relative flex items-center pr-2">
                        <p class="hidden transform-dropdown-show"></p>
                        <a href="javascript:;" class="block p-0 text-sm text-white transition-all ease-nav-brand" user-dropdown-trigger aria-expanded="false">
                            <img id="navbarProfilePhoto" src="<?php echo htmlspecialchars($currentUserPhoto); ?>" class="inline-flex items-center justify-center text-sm text-white h-9 w-9 max-w-none rounded-xl cursor-pointer object-cover" alt="User" />
                        </a>

                        <ul user-dropdown-menu class="text-sm transform-dropdown before:font-awesome before:leading-default before:duration-350 before:ease lg:shadow-3xl duration-250 min-w-44 before:sm:right-8 before:text-5.5 pointer-events-none absolute right-0 top-0 z-50 origin-top list-none rounded-lg border-0 border-solid border-transparent dark:shadow-dark-xl dark:bg-slate-850 bg-white bg-clip-padding px-2 py-4 text-left text-slate-500 opacity-0 transition-all before:absolute before:right-2 before:left-auto before:top-0 before:z-50 before:inline-block before:font-normal before:text-white before:antialiased before:transition-all before:content-['\f0d8'] sm:-mr-6 lg:absolute lg:right-0 lg:left-auto lg:mt-2 lg:block lg:cursor-pointer">
                            <li class="relative">
                                <a class="dark:hover:bg-slate-900 ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg bg-transparent px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors" href="<?php echo $base_url ?? './'; ?>profile">
                                    <div class="flex py-1">
                                        <div class="my-auto">
                                            <i class="fa fa-user mr-4 text-slate-400"></i>
                                        </div>
                                        <div class="flex flex-col justify-center">
                                            <h6 class="mb-0 text-sm font-normal leading-normal dark:text-white">Profile</h6>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="relative">
                                <a class="dark:hover:bg-slate-900 ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg bg-transparent px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors" href="<?php echo $base_url ?? './'; ?>account-settings">
                                    <div class="flex py-1">
                                        <div class="my-auto">
                                            <i class="fa fa-cog mr-4 text-slate-400"></i>
                                        </div>
                                        <div class="flex flex-col justify-center">
                                            <h6 class="mb-0 text-sm font-normal leading-normal dark:text-white">Account Settings</h6>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="relative border-t border-gray-200 mt-2 pt-2">
                                <a class="dark:hover:bg-slate-900 ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg bg-transparent px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors" href="<?php echo $base_url ?? './'; ?>logout" id="logoutDropdownLink">
                                    <div class="flex py-1">
                                        <div class="my-auto">
                                            <i class="fa fa-sign-out-alt mr-4 text-slate-400"></i>
                                        </div>
                                        <div class="flex flex-col justify-center">
                                            <h6 class="mb-0 text-sm font-normal leading-normal dark:text-white">Sign Out</h6>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="flex items-center">
                        <a href="<?php echo $base_url ?? './'; ?>sign-in" class="block px-0 py-2 text-sm font-semibold text-white transition-all ease-nav-brand">
                            <i class="fa fa-user sm:mr-1"></i>
                            <span class="hidden sm:inline">Sign In</span>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="flex items-center pl-4 xl:hidden">
                    <a href="javascript:;" class="block p-0 text-sm text-white transition-all ease-nav-brand" sidenav-trigger>
                        <div class="w-4.5 overflow-hidden">
                            <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-white transition-all"></i>
                            <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-white transition-all"></i>
                            <i class="ease relative block h-0.5 rounded-sm bg-white transition-all"></i>
                        </div>
                    </a>
                </li>
                <li class="flex items-center px-4">
                    <a href="javascript:;" class="p-0 text-sm text-white transition-all ease-nav-brand">
                        <i fixed-plugin-button-nav class="cursor-pointer fa fa-cog"></i>
                        <!-- fixed-plugin-button-nav  -->
                    </a>
                </li>

                <?php if ($canAccessPOS): ?>
                <!-- POS Button -->
                <li class="flex items-center px-3">
                    <a href="<?php echo $base_url ?? './'; ?>inventory/pos" 
                       class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg shadow-md hover:bg-green-700 hover:shadow-lg transition-all duration-200"
                       title="Point of Sale">
                        <i class="fas fa-cash-register" style="margin-right: 5px;"></i>
                        <span class="hidden sm:inline">POS</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- notifications -->

                <li class="relative flex items-center pr-2">
                    <p class="hidden transform-dropdown-show"></p>
                    <a href="javascript:;" class="block p-0 text-sm text-white transition-all ease-nav-brand relative" dropdown-trigger aria-expanded="false" id="notifBell">
                        <i class="cursor-pointer fa fa-bell"></i>
                        <span id="notifBadge" class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-red-500 text-xs text-white items-center justify-center" style="display: none;">0</span>
                    </a>

                    <ul dropdown-menu class="text-sm transform-dropdown before:font-awesome before:leading-default before:duration-350 before:ease lg:shadow-3xl duration-250 min-w-80 before:sm:right-8 before:text-5.5 pointer-events-none absolute right-0 top-0 z-50 origin-top list-none rounded-lg border-0 border-solid border-transparent dark:shadow-dark-xl dark:bg-slate-850 bg-white bg-clip-padding px-2 py-4 text-left text-slate-500 opacity-0 transition-all before:absolute before:right-2 before:left-auto before:top-0 before:z-50 before:inline-block before:font-normal before:text-white before:antialiased before:transition-all before:content-['\f0d8'] sm:-mr-6 lg:absolute lg:right-0 lg:left-auto lg:mt-2 lg:block lg:cursor-pointer max-h-96 overflow-y-auto">
                        <li class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                            <h6 class="text-xs font-semibold uppercase text-slate-400 dark:text-slate-500">Notifications</h6>
                        </li>
                        <li id="notifLoading" class="px-4 py-4 text-center text-sm text-slate-400">
                            Loading...
                        </li>
                        <li id="notifEmpty" class="hidden px-4 py-4 text-center text-sm text-slate-400">
                            No notifications
                        </li>
                        <li id="notifList"></li>
                        <li class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">
                            <a href="<?php echo $base_url ?? ''; ?>settings/notifications" class="text-xs font-semibold text-blue-500 hover:text-blue-600">View All Notifications</a>
                        </li>
                    </ul>
                </li>

                <script>
                    (function() {
                        const notifBell = document.getElementById('notifBell');
                        const notifBadge = document.getElementById('notifBadge');
                        const notifList = document.getElementById('notifList');
                        const notifLoading = document.getElementById('notifLoading');
                        const notifEmpty = document.getElementById('notifEmpty');

                        let notificationsData = [];

                        function loadNotifications() {
                            fetch('<?php echo $base_url ?? ''; ?>api/notifications?action=my-notifications&limit=5')
                                .then(r => r.json())
                                .then(res => {
                                    if (res.success) {
                                        notificationsData = res.data || [];
                                        renderNotifications(res.unread_count || 0);
                                    }
                                })
                                .catch(err => console.error('Failed to load notifications:', err));
                        }

                        function renderNotifications(unreadCount) {
                            notifLoading.classList.add('hidden');

                            if (notificationsData.length === 0) {
                                notifEmpty.classList.remove('hidden');
                                notifBadge.style.display = 'none';
                                return;
                            }

                            notifEmpty.classList.add('hidden');

                            if (unreadCount > 0) {
                                notifBadge.textContent = unreadCount > 9 ? '9+' : unreadCount;
                                notifBadge.style.display = 'flex';
                            } else {
                                notifBadge.style.display = 'none';
                            }

                            notifList.innerHTML = notificationsData.map(n => {
                                const timeAgo = getTimeAgo(n.created_at);
                                const unreadClass = !n.is_read ? 'bg-blue-50 dark:bg-slate-800' : '';
                                const unreadDot = !n.is_read ? '<span class="inline-block w-2 h-2 rounded-full bg-blue-500 mr-2"></span>' : '';
                                const actionUrl = n.action_url || 'javascript:;';

                                return `
                                <li class="relative mb-2">
                                    <a class="dark:hover:bg-slate-900 ease py-2 clear-both block w-full whitespace-nowrap rounded-lg px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors ${unreadClass}" 
                                       href="${actionUrl}" 
                                       onclick="markAsRead('${n.notif_id}', event)">
                                        <div class="flex">
                                            <div class="flex flex-col justify-center flex-1">
                                                <div class="flex items-center">
                                                    ${unreadDot}
                                                    <h6 class="text-sm font-normal leading-normal dark:text-white">${n.title}</h6>
                                                </div>
                                                <p class="mb-0 text-xs leading-tight text-slate-400 dark:text-white/80 truncate max-w-xs">
                                                    ${n.body.substring(0, 60)}${n.body.length > 60 ? '...' : ''}
                                                </p>
                                                <p class="mb-0 text-xs leading-tight text-slate-400 dark:text-white/80 mt-1">
                                                    <i class="mr-1 fa fa-clock"></i>${timeAgo}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            `;
                            }).join('');
                        }

                        function getTimeAgo(dateString) {
                            const date = new Date(dateString);
                            const now = new Date();
                            const seconds = Math.floor((now - date) / 1000);

                            if (seconds < 60) return 'Just now';
                            if (seconds < 3600) return Math.floor(seconds / 60) + ' minutes ago';
                            if (seconds < 86400) return Math.floor(seconds / 3600) + ' hours ago';
                            if (seconds < 604800) return Math.floor(seconds / 86400) + ' days ago';
                            return date.toLocaleDateString();
                        }

                        window.markAsRead = function(notifId, event) {
                            if (event) {
                                event.preventDefault();
                            }
                            fetch('<?php echo $base_url ?? ''; ?>api/notifications?action=mark-read&id=' + notifId)
                                .then(r => r.json())
                                .then(res => {
                                    if (res.success) {
                                        const notif = notificationsData.find(n => n.notif_id === notifId);
                                        if (notif && notif.action_url) {
                                            window.location.href = notif.action_url;
                                        }
                                    }
                                });
                        };

                        // Load notifications on page load
                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', loadNotifications);
                        } else {
                            loadNotifications();
                        }

                        // Reload notifications when dropdown is opened
                        notifBell.addEventListener('click', function() {
                            loadNotifications();
                        });
                    })();
                </script>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    // Handle user dropdown toggle
    document.addEventListener('DOMContentLoaded', function() {
        const userDropdownTrigger = document.querySelector('[user-dropdown-trigger]');
        const userDropdownMenu = document.querySelector('[user-dropdown-menu]');

        if (userDropdownTrigger && userDropdownMenu) {
            userDropdownTrigger.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const isExpanded = userDropdownTrigger.getAttribute('aria-expanded') === 'true';
                userDropdownTrigger.setAttribute('aria-expanded', !isExpanded);

                if (isExpanded) {
                    userDropdownMenu.classList.remove('pointer-events-auto', 'opacity-100');
                    userDropdownMenu.classList.add('pointer-events-none', 'opacity-0');
                } else {
                    userDropdownMenu.classList.remove('pointer-events-none', 'opacity-0');
                    userDropdownMenu.classList.add('pointer-events-auto', 'opacity-100');
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userDropdownTrigger.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                    userDropdownTrigger.setAttribute('aria-expanded', 'false');
                    userDropdownMenu.classList.remove('pointer-events-auto', 'opacity-100');
                    userDropdownMenu.classList.add('pointer-events-none', 'opacity-0');
                }
            });
        }

        // Handle logout with loading spinner (from dropdown)
        const logoutDropdownLink = document.getElementById('logoutDropdownLink');
        if (logoutDropdownLink) {
            logoutDropdownLink.addEventListener('click', function(e) {
                e.preventDefault();

                const link = this;
                const icon = link.querySelector('i');
                const text = link.querySelector('h6');

                // Show loading spinner
                if (icon) {
                    icon.className = 'fa fa-circle-notch mr-4 fa-spin text-slate-400';
                }
                if (text) {
                    text.textContent = 'Signing out...';
                }

                // Disable the link
                link.style.pointerEvents = 'none';
                link.style.opacity = '0.7';

                // Redirect after a short delay to show the spinner
                setTimeout(function() {
                    window.location.href = link.href;
                }, 500);
            });
        }

        // Handle logout with loading spinner (legacy)
        const logoutLink = document.getElementById('logoutLink');
        if (logoutLink) {
            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();

                const logoutIcon = document.getElementById('logoutIcon');
                const logoutText = document.getElementById('logoutText');

                // Show loading spinner
                if (logoutIcon) {
                    logoutIcon.className = 'fa fa-circle-notch sm:mr-1 fa-spin';
                }
                if (logoutText) {
                    logoutText.textContent = 'Signing out...';
                }

                // Disable the link
                logoutLink.style.pointerEvents = 'none';
                logoutLink.style.opacity = '0.7';

                // Redirect after a short delay to show the spinner
                setTimeout(function() {
                    window.location.href = logoutLink.href;
                }, 500);
            });
        }

        // Global Search Functionality
        const searchInput = document.getElementById('globalSearchInput');
        const searchDropdown = document.getElementById('searchResultsDropdown');
        const searchContent = document.getElementById('searchResultsContent');
        const searchFooter = document.getElementById('searchResultsFooter');
        const searchCount = document.getElementById('searchResultsCount');
        let searchTimeout = null;
        let currentSearchQuery = '';

        if (searchInput) {
            // Handle input with debounce
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.trim();

                // Clear previous timeout
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }

                // Hide dropdown if query is empty
                if (!query || query.length < 2) {
                    searchDropdown.classList.add('hidden');
                    return;
                }

                // Show loading state
                searchContent.innerHTML = '<div class="search-loading"><i class="fas fa-circle-notch fa-spin mr-2"></i>Searching...</div>';
                searchDropdown.classList.remove('hidden');
                searchFooter.classList.add('hidden');

                // Debounce search
                searchTimeout = setTimeout(function() {
                    performSearch(query);
                }, 300);
            });

            // Handle keyboard navigation
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    searchDropdown.classList.add('hidden');
                    searchInput.blur();
                }
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                const searchContainer = document.getElementById('searchContainer');
                if (searchContainer && !searchContainer.contains(e.target)) {
                    searchDropdown.classList.add('hidden');
                }
            });
        }

        async function performSearch(query) {
            currentSearchQuery = query;
            const normalizedQuery = query.toLowerCase().trim();

            try {
                // Calculate the correct API base path
                const scriptPath = '<?php echo $base_url; ?>';
                const apiBase = scriptPath.replace('/structure/', '/build/');

                // Search across multiple endpoints and sidebar
                const searchPromises = [
                    searchProducts(apiBase, normalizedQuery),
                    searchReservations(apiBase, normalizedQuery),
                    searchRooms(apiBase, normalizedQuery),
                    searchSidebar(normalizedQuery, apiBase)
                ];

                const results = await Promise.allSettled(searchPromises);
                let allResults = [];

                results.forEach((result, index) => {
                    if (result.status === 'fulfilled' && result.value) {
                        allResults.push(...result.value);
                    }
                });

                // Score and sort results by relevance
                allResults = scoreAndSortResults(allResults, normalizedQuery);

                // Limit to top 15 results
                const limitedResults = allResults.slice(0, 15);
                renderSearchResults(limitedResults, allResults.length);

            } catch (error) {
                console.error('Search error:', error);
                searchContent.innerHTML = '<div class="search-no-results"><i class="fas fa-exclamation-circle mr-2"></i>Error performing search</div>';
                searchFooter.classList.add('hidden');
            }
        }

        // Score results based on relevance to query
        function scoreAndSortResults(results, query) {
            return results.map(item => {
                let score = 0;
                const title = item.title.toLowerCase();
                const subtitle = (item.subtitle || '').toLowerCase();

                // Exact match gets highest score
                if (title === query) score += 100;
                // Starts with query gets high score
                else if (title.startsWith(query)) score += 80;
                // Contains query as whole word
                else if (new RegExp(`\\b${query}\\b`).test(title)) score += 60;
                // Contains query anywhere
                else if (title.includes(query)) score += 40;
                // Subtitle matches
                if (subtitle.includes(query)) score += 20;

                // Boost sidebar items slightly
                if (item.type === 'sidebar') score += 5;

                return { ...item, score };
            }).sort((a, b) => b.score - a.score);
        }

        // Check if item matches query with fuzzy matching
        function matchesQuery(item, query) {
            const searchFields = [item.title, item.subtitle || ''].map(f => f.toLowerCase());

            // Check for exact or partial match in any field
            return searchFields.some(field => {
                return field.includes(query) ||
                       field.replace(/[^a-z0-9]/g, '').includes(query.replace(/[^a-z0-9]/g, ''));
            });
        }

        async function searchProducts(apiBase, query) {
            try {
                const response = await fetch(`${apiBase}api/products.php?action=list&limit=100`);
                const data = await response.json();

                if (data.success && data.data && data.data.length > 0) {
                    return data.data
                        .filter(product => matchesQuery({
                            title: product.product_name,
                            subtitle: product.product_code
                        }, query))
                        .slice(0, 5)
                        .map(product => ({
                            type: 'product',
                            title: product.product_name,
                            subtitle: product.product_code || 'No code',
                            url: `${apiBase}inventory/product`,
                            icon: 'fa-box',
                            id: product.product_id,
                            imageUrl: product.product_image_url
                        }));
                }
                return [];
            } catch (e) {
                console.error('Product search error:', e);
                return [];
            }
        }

        async function searchReservations(apiBase, query) {
            try {
                const response = await fetch(`${apiBase}api/reservations.php?action=list&limit=100`);
                const data = await response.json();

                if (data.success && data.data && data.data.length > 0) {
                    return data.data
                        .filter(reservation => matchesQuery({
                            title: reservation.guest_name || reservation.customer_name || '',
                            subtitle: String(reservation.reservation_id || reservation.id || '')
                        }, query))
                        .slice(0, 5)
                        .map(reservation => ({
                            type: 'reservation',
                            title: reservation.guest_name || reservation.customer_name || 'Unknown Guest',
                            subtitle: `Reservation #${reservation.reservation_id || reservation.id || 'N/A'}`,
                            url: `${apiBase}reservations/reservations`,
                            icon: 'fa-calendar-check',
                            id: reservation.reservation_id || reservation.id
                        }));
                }
                return [];
            } catch (e) {
                console.error('Reservation search error:', e);
                return [];
            }
        }

        async function searchRooms(apiBase, query) {
            try {
                const response = await fetch(`${apiBase}api/rooms.php?action=list&limit=100`);
                const data = await response.json();

                if (data.success && data.data && data.data.length > 0) {
                    return data.data
                        .filter(room => matchesQuery({
                            title: room.room_name || room.room_number || '',
                            subtitle: room.property_name || room.category_name || ''
                        }, query))
                        .slice(0, 5)
                        .map(room => ({
                            type: 'room',
                            title: room.room_name || room.room_number || 'Unnamed Room',
                            subtitle: room.property_name || room.category_name || 'No property',
                            url: `${apiBase}properties-rooms/rooms`,
                            icon: 'fa-door-open',
                            id: room.room_id
                        }));
                }
                return [];
            } catch (e) {
                console.error('Room search error:', e);
                return [];
            }
        }

        // Search sidebar menu items
        function searchSidebar(query, apiBase) {
            const sidebarItems = collectSidebarItems(apiBase);
            return sidebarItems
                .filter(item => matchesQuery(item, query))
                .slice(0, 5)
                .map(item => ({
                    ...item,
                    type: 'sidebar',
                    icon: item.icon || 'fa-bars',
                    subtitle: item.parent || 'Menu'
                }));
        }

        // Collect all sidebar menu items
        function collectSidebarItems(apiBase) {
            const items = [];

            // Main menu items
            document.querySelectorAll('aside [data-active], aside a[href]').forEach(el => {
                const link = el.tagName === 'A' ? el : el.querySelector('a');
                if (!link) return;

                const href = link.getAttribute('href');
                if (!href || href === 'javascript:;' || href === '#') return;

                const textEl = link.querySelector('.ease.truncate') || link.querySelector('span:last-child');
                const title = textEl ? textEl.textContent.trim() : '';
                const iconEl = link.querySelector('i');
                const iconClass = iconEl ? iconEl.className.match(/fa-[^\s]+/) : 'fa-circle';

                if (title) {
                    items.push({
                        title: title,
                        url: href,
                        icon: iconClass ? iconClass[0] : 'fa-circle',
                        parent: findParentMenuName(link)
                    });
                }
            });

            // Dropdown items (submenu)
            document.querySelectorAll('aside .pl-2 a[href]').forEach(link => {
                const href = link.getAttribute('href');
                if (!href || href === 'javascript:;' || href === '#') return;

                const textEl = link.querySelector('.ease.truncate') || link.querySelector('span:last-child');
                const title = textEl ? textEl.textContent.trim() : '';

                if (title) {
                    items.push({
                        title: title,
                        url: href,
                        icon: 'fa-dot-circle',
                        parent: findParentMenuName(link)
                    });
                }
            });

            return items;
        }

        // Find parent menu name for a submenu item
        function findParentMenuName(element) {
            const parentDiv = element.closest('.relative');
            if (parentDiv) {
                const parentBtn = parentDiv.querySelector('button');
                if (parentBtn) {
                    const parentText = parentBtn.querySelector('.ease.truncate');
                    return parentText ? parentText.textContent.trim() : 'Menu';
                }
            }
            return 'Menu';
        }

        function renderSearchResults(results, totalCount) {
            if (results.length === 0) {
                searchContent.innerHTML = '<div class="search-no-results"><i class="fas fa-search mr-2"></i>No results found</div>';
                searchFooter.classList.add('hidden');
                return;
            }

            // Group results by type
            const grouped = results.reduce((acc, item) => {
                if (!acc[item.type]) acc[item.type] = [];
                acc[item.type].push(item);
                return acc;
            }, {});

            let html = '';
            const typeLabels = {
                product: 'Products',
                reservation: 'Reservations',
                room: 'Rooms',
                sidebar: 'Menu Items'
            };

            Object.keys(grouped).forEach(type => {
                const items = grouped[type];
                html += `<div class="search-section-header">${typeLabels[type] || type}</div>`;

                items.forEach(item => {
                    // Generate icon/image HTML
                    let iconHtml = '';
                    if (item.type === 'product' && item.imageUrl) {
                        const normalizedImageUrl = normalizeSearchImageUrl(item.imageUrl);
                        iconHtml = `<img src="${normalizedImageUrl}" alt="${escapeHtml(item.title)}" class="search-result-product-img" onerror="this.onerror=null;this.parentElement.innerHTML='<i class=\\'fas fa-box\\'></i>';this.parentElement.classList.add('product');">`;
                    } else {
                        iconHtml = `<i class="fas ${item.icon}"></i>`;
                    }

                    html += `
                        <a href="${item.url}" class="search-result-item" data-id="${item.id}" data-type="${item.type}">
                            <div class="search-result-icon ${item.type}">
                                ${iconHtml}
                            </div>
                            <div class="search-result-content">
                                <div class="search-result-title">${escapeHtml(item.title)}</div>
                                <div class="search-result-subtitle">${escapeHtml(item.subtitle)}</div>
                            </div>
                            <span class="search-result-type">${item.type}</span>
                        </a>
                    `;
                });
            });

            searchContent.innerHTML = html;

            // Update footer with count
            if (totalCount > results.length) {
                searchCount.textContent = `Showing ${results.length} of ${totalCount}`;
            } else {
                searchCount.textContent = `${totalCount}`;
            }
            searchFooter.classList.remove('hidden');
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Normalize product image URL for search results (dynamic path based on current URL)
        function normalizeSearchImageUrl(url) {
            if (!url) return null;

            // If it's already an absolute URL (http/https), use as-is
            if (url.startsWith('http')) return url;

            // Extract filename from various path formats
            const filename = url.split('/').pop();

            // Calculate path to uploads based on current URL
            const pathParts = window.location.pathname.split('/').filter(p => p);

            // Find the project root (folder containing 'build' or 'structure')
            let projectRootIndex = pathParts.findIndex(p => p === 'build' || p === 'structure');
            if (projectRootIndex === -1) {
                projectRootIndex = pathParts.findIndex(p => p.includes('admin'));
            }

            let relativePath = '';
            if (projectRootIndex > 0) {
                // We're in a subfolder of the project (e.g., admin_booking/structure/)
                // Go up from current location to project root
                // pathParts.length - projectRootIndex - 1 gives us the depth from project root
                const depthFromRoot = pathParts.length - projectRootIndex - 1;
                relativePath = '../'.repeat(Math.max(1, depthFromRoot));
            } else if (projectRootIndex === 0) {
                // We're directly in build/ or structure/ folder
                relativePath = '../';
            } else {
                // Fallback - assume we're at project root level
                relativePath = '';
            }

            return `${relativePath}uploads/products/${filename}`;
        }
    });
</script>