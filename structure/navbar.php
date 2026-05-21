<?php
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

    // Ensure path starts with /
    if (strpos($url, '/') !== 0) {
        $url = '/' . $url;
    }

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
<nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all ease-in shadow-none duration-250 rounded-2xl lg:flex-nowrap lg:justify-start" navbar-main navbar-scroll="false">
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
                <div class="relative flex flex-wrap items-stretch w-full transition-all rounded-lg ease">
                    <span class="text-sm ease leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="pl-9 text-sm focus:shadow-primary-outline ease w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" placeholder="Type here..." />
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
    });
</script>