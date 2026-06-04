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

function customerIsActive($route, $current_url)
{
    return strpos($current_url, $route) !== false;
}

function customerActiveClass($route, $current_url)
{
    return customerIsActive($route, $current_url)
        ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg'
        : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg';
}

function customerGetActiveAttribute($route, $current_url)
{
    return customerIsActive($route, $current_url) ? 'data-active="true"' : '';
}
?>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">

<style>
    /* ── Typography ─────────────────────────────────────── */
    #customerSidebar,
    #customerSidebar * {
        font-family: 'DM Sans', system-ui, sans-serif;
    }

    /* Preserve Nucleo Icons font */
    #customerSidebar .ni {
        font-family: 'NucleoIcons' !important;
    }

    /* Preserve Font Awesome icons font */
    #customerSidebar .fas,
    #customerSidebar .far,
    #customerSidebar .fab,
    #customerSidebar .fa-solid,
    #customerSidebar .fa-regular,
    #customerSidebar .fa-brands,
    #customerSidebar [class*="fa-"] {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "FontAwesome" !important;
    }

    #customerSidebar .fas,
    #customerSidebar .fa-solid {
        font-weight: 900 !important;
    }

    #customerSidebar .far,
    #customerSidebar .fa-regular {
        font-weight: 400 !important;
    }

    #customerSidebar .fab,
    #customerSidebar .fa-brands {
        font-family: "Font Awesome 6 Brands" !important;
        font-weight: 400 !important;
    }

    #customerSidebar .sidebar-brand-name {
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    /* ── Sidebar base ───────────────────────────────────── */
    #customerSidebar {
        background: linear-gradient(180deg, #ffffff 0%, #f8faff 100%) !important;
        border-right: 1px solid rgba(99, 102, 241, 0.08);
        box-shadow: 4px 0 24px rgba(99, 102, 241, 0.06), 0 4px 16px rgba(0,0,0,0.04) !important;
    }

    .dark #customerSidebar {
        background: linear-gradient(180deg, #1e2235 0%, #181c2e 100%) !important;
        border-right: 1px solid rgba(99, 102, 241, 0.12);
        box-shadow: 4px 0 24px rgba(0,0,0,0.3) !important;
    }

    /* ── Brand header ───────────────────────────────────── */
    #customerSidebar .h-19 {
        background: linear-gradient(135deg, #eef2ff 0%, #f0f9ff 100%);
        border-bottom: 1px solid rgba(99, 102, 241, 0.1);
        padding-bottom: 0.25rem;
    }

    .dark #customerSidebar .h-19 {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(6, 182, 212, 0.06) 100%);
        border-bottom: 1px solid rgba(99, 102, 241, 0.2);
    }

    /* ── Nav items ──────────────────────────────────────── */
    #customerSidebar a.ease-nav-brand {
        font-size: 0.8125rem;
        font-weight: 500;
        letter-spacing: 0.005em;
        color: #4b5563;
        border-radius: 10px !important;
        transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .dark #customerSidebar a.ease-nav-brand {
        color: rgba(255,255,255,0.82);
    }

    #customerSidebar a.ease-nav-brand:hover {
        background: rgba(99, 102, 241, 0.07) !important;
        color: #4f46e5 !important;
        transform: translateX(2px);
    }

    .dark #customerSidebar a.ease-nav-brand:hover {
        background: rgba(99, 102, 241, 0.15) !important;
        color: #a5b4fc !important;
    }

    /* ── Active state ───────────────────────────────────── */
    #customerSidebar [data-active="true"],
    #customerSidebar .bg-blue-500\/13 {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(139, 92, 246, 0.08) 100%) !important;
        color: #4338ca !important;
        font-weight: 600 !important;
        box-shadow: inset 3px 0 0 #6366f1, 0 2px 8px rgba(99, 102, 241, 0.12);
        border-radius: 10px !important;
    }

    .dark #customerSidebar [data-active="true"],
    .dark #customerSidebar .bg-blue-500\/13 {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.2) 0%, rgba(139, 92, 246, 0.12) 100%) !important;
        color: #a5b4fc !important;
        box-shadow: inset 3px 0 0 #818cf8, 0 2px 8px rgba(99, 102, 241, 0.2);
    }

    /* ── Icon containers ────────────────────────────────── */
    #customerSidebar .h-8.w-8 {
        background: rgba(255,255,255,0.9);
        border-radius: 8px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08), 0 0 0 1px rgba(0,0,0,0.04);
        transition: all 0.18s ease;
    }

    .dark #customerSidebar .h-8.w-8 {
        background: rgba(255,255,255,0.06);
        box-shadow: 0 1px 4px rgba(0,0,0,0.2), 0 0 0 1px rgba(255,255,255,0.06);
    }

    #customerSidebar a.ease-nav-brand:hover .h-8.w-8 {
        background: rgba(255,255,255,1);
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15), 0 0 0 1px rgba(99, 102, 241, 0.1);
        transform: scale(1.05);
    }

    /* ── Chevron ────────────────────────────────────────── */
    #customerSidebar .fa-chevron-left {
        color: #9ca3af;
        font-size: 0.65rem !important;
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), color 0.15s ease;
    }

    #customerSidebar button:hover .fa-chevron-left {
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

    /* ── Divider ────────────────────────────────────────── */
    #customerSidebar hr {
        border: none;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.2), transparent) !important;
        margin: 0 0.5rem;
    }

    /* ── Scrollbar ──────────────────────────────────────── */
    #customerSidebar::-webkit-scrollbar {
        width: 3px;
    }
    #customerSidebar::-webkit-scrollbar-track {
        background: transparent;
    }
    #customerSidebar::-webkit-scrollbar-thumb {
        background: rgba(99, 102, 241, 0.2);
        border-radius: 10px;
    }
    #customerSidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(99, 102, 241, 0.4);
    }

    /* Customer sidebar collapse styles */
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
    aside.sidebar-collapsed .sidebar-label {
        display: none !important;
    }

    aside.sidebar-collapsed a.ease-nav-brand {
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

<aside id="customerSidebar" class="fixed inset-y-0 flex-wrap items-center justify-between block w-full p-0 my-4 overflow-y-auto antialiased transition-all duration-200 -translate-x-full bg-white border-0 shadow-xl dark:shadow-none dark:bg-slate-850 max-w-64 ease-nav-brand z-990 xl:ml-6 rounded-2xl xl:left-0 xl:translate-x-0" aria-expanded="false">
    <div class="h-19">
        <i class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times dark:text-white text-slate-400 xl:hidden" sidenav-close></i>
        <button id="sidebarCollapseBtn" class="hidden xl:flex absolute top-0 right-0 p-4 cursor-pointer items-center justify-center text-slate-400 hover:text-blue-500 dark:text-white dark:hover:text-blue-400 transition-colors duration-200 z-10" title="Collapse sidebar" aria-label="Toggle Sidebar">
            <i id="sidebarCollapseIcon" class="fas fa-chevron-left text-xs transition-transform duration-300"></i>
        </button>
        <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap dark:text-white text-slate-700" href="<?php echo $base_url; ?>customer/dashboard">
            <img src="<?php echo $assets_path ?? './assets'; ?>/img/logo.png" class="inline h-full max-w-full transition-all duration-200 dark:hidden ease-nav-brand max-h-8" alt="main_logo" />
            <img src="<?php echo $assets_path ?? './assets'; ?>/img/logo.png" class="hidden h-full max-w-full transition-all duration-200 dark:inline ease-nav-brand max-h-8" alt="main_logo" />
            <span class="ml-1 transition-all duration-200 ease-nav-brand sidebar-label sidebar-brand-name">My Portal</span>
        </a>
    </div>

    <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />

    <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full">
        <ul class="flex flex-col mb-0">

            <li class="mt-0.5 w-full">
                <?php $dash_class = customerIsActive('customer/dashboard', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $dash_class; ?>" href="<?php echo $base_url; ?>customer/dashboard" <?php echo customerGetActiveAttribute('customer/dashboard', $current_url); ?>>
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-blue-500 ni ni-tv-2"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Dashboard</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <?php $res_class = customerIsActive('customer/reservations', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $res_class; ?>" href="<?php echo $base_url; ?>customer/reservations" <?php echo customerGetActiveAttribute('customer/reservations', $current_url); ?>>
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-emerald-500 ni ni-calendar-grid-58"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">My Reservations</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <?php $food_class = customerIsActive('customer/food-orders', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $food_class; ?>" href="<?php echo $base_url; ?>customer/food-orders" <?php echo customerGetActiveAttribute('customer/food-orders', $current_url); ?>>
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-amber-500 ni ni-cart"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">Food Orders</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <?php $pay_class = customerIsActive('customer/payments', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $pay_class; ?>" href="<?php echo $base_url; ?>customer/payments" <?php echo customerGetActiveAttribute('customer/payments', $current_url); ?>>
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-violet-500 ni ni-credit-card"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">My Payments</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <?php $prof_class = customerIsActive('customer/profile', $current_url) ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
                <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $prof_class; ?>" href="<?php echo $base_url; ?>customer/profile" <?php echo customerGetActiveAttribute('customer/profile', $current_url); ?>>
                    <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
                        <i class="text-sm leading-normal text-orange-500 ni ni-single-02"></i>
                    </span>
                    <span class="duration-300 opacity-100 pointer-events-none ease truncate">My Profile</span>
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

<div id="sidebarOverlay"></div>

<script>
    // Customer sidebar collapse and mobile overlay
    (function() {
        var COLLAPSE_KEY = 'customerSidebarCollapsed';
        var sidebar = document.getElementById('customerSidebar');
        var collapseBtn = document.getElementById('sidebarCollapseBtn');
        var collapseIcon = document.getElementById('sidebarCollapseIcon');
        var overlay = document.getElementById('sidebarOverlay');

        if (!sidebar) return;

        function setNavTooltips(collapsed) {
            sidebar.querySelectorAll('a.ease-nav-brand').forEach(function(item) {
                if (collapsed) {
                    var label = item.querySelector('span.truncate');
                    if (label) item.setAttribute('title', label.textContent.trim());
                } else {
                    item.removeAttribute('title');
                }
            });
        }

        function applySidebarCollapse(save) {
            sidebar.classList.add('sidebar-collapsed');
            document.body.classList.add('sidebar-is-collapsed');
            if (collapseIcon) {
                collapseIcon.classList.remove('fa-chevron-left');
                collapseIcon.classList.add('fa-chevron-right');
            }
            if (collapseBtn) collapseBtn.setAttribute('title', 'Expand sidebar');
            setNavTooltips(true);
            if (save) localStorage.setItem(COLLAPSE_KEY, 'true');
        }

        function applySidebarExpand(save) {
            sidebar.classList.remove('sidebar-collapsed');
            document.body.classList.remove('sidebar-is-collapsed');
            if (collapseIcon) {
                collapseIcon.classList.remove('fa-chevron-right');
                collapseIcon.classList.add('fa-chevron-left');
            }
            if (collapseBtn) collapseBtn.setAttribute('title', 'Collapse sidebar');
            setNavTooltips(false);
            if (save) localStorage.setItem(COLLAPSE_KEY, 'false');
        }

        if (collapseBtn) {
            collapseBtn.addEventListener('click', function() {
                if (sidebar.classList.contains('sidebar-collapsed')) {
                    applySidebarExpand(true);
                } else {
                    applySidebarCollapse(true);
                }
            });
        }

        if (localStorage.getItem(COLLAPSE_KEY) === 'true') {
            applySidebarCollapse(false);
        }

        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                sidebar.setAttribute('aria-expanded', 'false');
                overlay.classList.remove('active');
            });
        }

        var sidenavTrigger = document.querySelector('[sidenav-trigger]');
        if (sidenavTrigger && overlay) {
            sidenavTrigger.addEventListener('click', function() {
                overlay.classList.add('active');
            });
        }

        var sidenavCloseEl = document.querySelector('[sidenav-close]');
        if (sidenavCloseEl && overlay) {
            sidenavCloseEl.addEventListener('click', function() {
                overlay.classList.remove('active');
            });
        }

        // Auto-scroll active item into view on page load
        document.addEventListener('DOMContentLoaded', function() {
            function scrollActiveIntoView() {
                const activeItem = sidebar.querySelector('[data-active="true"]');
                if (activeItem) {
                    activeItem.scrollIntoView({ block: 'nearest', behavior: 'instant' });
                }
            }
            setTimeout(scrollActiveIntoView, 50);
            setTimeout(scrollActiveIntoView, 250);
        });
    })();
</script>