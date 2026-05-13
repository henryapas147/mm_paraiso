<?php

namespace App\Core;

class RBAC
{
    // Database connection
    private static $pdo = null;
    private static $permissionsCache = [];

    // Initialize database connection
    private static function initDB()
    {
        if (self::$pdo === null) {
            require_once __DIR__ . '/../../config/db.php';
            global $pdo;
            self::$pdo = $pdo;
        }
    }

    // Role hierarchy - higher number = more privileges
    private static $roleHierarchy = [
        'customer' => 1,
        'api_client' => 2,
        'security' => 3,
        'housekeeping' => 4,
        'canteen_staff' => 5,
        'front_desk' => 6,
        'admin' => 7,
        'super_admin' => 8
    ];

    /**
     * Get user's current role from session
     */
    private static function getCurrentRole()
    {
        return $_SESSION['role_code'] ?? null;
    }

    /**
     * Get permissions for a role from database
     */
    private static function getRolePermissionsFromDB($role)
    {
        self::initDB();

        // Check cache first
        if (isset(self::$permissionsCache[$role])) {
            return self::$permissionsCache[$role];
        }

        try {
            $stmt = self::$pdo->prepare("
                SELECT permissions 
                FROM user_roles 
                WHERE role_code = ? AND is_active = 1
                LIMIT 1
            ");
            $stmt->execute([$role]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($result && !empty($result['permissions'])) {
                $permissions = json_decode($result['permissions'], true);
                self::$permissionsCache[$role] = $permissions ?? [];
                return self::$permissionsCache[$role];
            }

            // Return empty array if no permissions found
            self::$permissionsCache[$role] = [];
            return [];
        } catch (\PDOException $e) {
            error_log("RBAC: Failed to get permissions from database: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Check if user has specific permission
     */
    public static function hasPermission($permission)
    {
        $role = self::getCurrentRole();
        if (!$role) {
            return false;
        }

        $permissions = self::getRolePermissionsFromDB($role);
        return in_array($permission, $permissions);
    }

    /**
     * Check if user has any of the specified permissions
     */
    public static function hasAnyPermission($permissions)
    {
        $role = self::getCurrentRole();
        if (!$role) {
            return false;
        }

        $rolePermissions = self::getRolePermissionsFromDB($role);
        foreach ($permissions as $permission) {
            if (in_array($permission, $rolePermissions)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the specified permissions
     */
    public static function hasAllPermissions($permissions, $userRole = null)
    {
        foreach ($permissions as $permission) {
            if (!self::hasPermission($permission, $userRole)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if user's role has equal or higher hierarchy level
     */
    public static function hasRoleLevel($requiredRole, $userRole = null)
    {
        if ($userRole === null) {
            $userRole = $_SESSION['role_code'] ?? null;
        }

        if (!$userRole || !isset(self::$roleHierarchy[$userRole]) || !isset(self::$roleHierarchy[$requiredRole])) {
            return false;
        }

        return self::$roleHierarchy[$userRole] >= self::$roleHierarchy[$requiredRole];
    }

    /**
     * Get all permissions for a role from database
     */
    public static function getRolePermissions($role)
    {
        return self::getRolePermissionsFromDB($role);
    }

    /**
     * Update permissions for a role in database
     */
    public static function updateRolePermissions($role, $permissions)
    {
        self::initDB();

        try {
            $stmt = self::$pdo->prepare("
                UPDATE user_roles 
                SET permissions = ?,
                    updated_at = CURRENT_TIMESTAMP
                WHERE role_code = ?
            ");
            $stmt->execute([json_encode($permissions), $role]);

            // Clear cache for this role
            unset(self::$permissionsCache[$role]);

            return true;
        } catch (\PDOException $e) {
            error_log("RBAC: Failed to update permissions: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all roles with their permissions
     */
    public static function getAllRoles()
    {
        self::initDB();

        try {
            $stmt = self::$pdo->query("
                SELECT role_id, role_code, role_label, permissions, is_system_role, is_active
                FROM user_roles
                ORDER BY role_hierarchy ASC
            ");
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("RBAC: Failed to get roles: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get available permission definitions
     */
    public static function getAvailablePermissions()
    {
        return [
            'dashboard' => ['dashboard.view', 'dashboard.manage'],
            'users' => ['users.view', 'users.create', 'users.edit', 'users.delete', 'users.roles.manage'],
            'properties' => [
                'properties.view', 'properties.create', 'properties.edit', 'properties.delete',
                'properties.properties.view', 'properties.rooms.view', 'properties.room_categories.view',
                'properties.amenities.view', 'properties.room_blocks.view'
            ],
            'rooms' => ['rooms.view', 'rooms.create', 'rooms.edit', 'rooms.delete'],
            'reservations' => [
                'reservations.view', 'reservations.create', 'reservations.edit', 'reservations.delete',
                'reservations.check_in', 'reservations.check_out',
                'reservations.reservations.view', 'reservations.rooms.view', 'reservations.guests.view',
                'reservations.requirement_types.view', 'reservations.requirements.view',
                'reservations.check_in_logs.view', 'reservations.entrance_logs.view'
            ],
            'customers' => ['customers.view', 'customers.create', 'customers.edit', 'customers.delete'],
            'inventory' => [
                'inventory.view', 'inventory.create', 'inventory.edit', 'inventory.delete',
                'inventory.products.view', 'inventory.categories.view', 'inventory.units.view',
                'inventory.locations.view', 'inventory.suppliers.view', 'inventory.stock_location.view',
                'inventory.purchase_orders.view', 'inventory.stock_transactions.view',
                'inventory.stock_transfers.view', 'inventory.sales.view'
            ],
            'food_canteen' => [
                'food_canteen.view', 'food_canteen.create', 'food_canteen.edit', 'food_canteen.delete',
                'food_canteen.categories.view', 'food_canteen.menu_items.view', 'food_canteen.orders.view'
            ],
            'payments' => [
                'payments.view', 'payments.create', 'payments.edit', 'payments.delete',
                'payments.payment_methods.view', 'payments.payments.view', 'payments.invoices.view',
                'payments.invoice_line_items.view', 'payments.discounts.view',
                'payments.discount_usages.view', 'payments.extra_charges.view', 'payments.billing.view'
            ],
            'reports' => [
                'reports.view',
                'reports.dashboard.view', 'reports.revenue.view', 'reports.occupancy.view',
                'reports.inventory.view', 'reports.customers.view', 'reports.food_canteen.view',
                'reports.financial.view'
            ],
            'settings' => [
                'settings.view', 'settings.edit', 'settings.manage',
                'settings.system.view', 'settings.pricing_rules.view', 'settings.seasonal_rates.view',
                'settings.booking_policies.view', 'settings.promotions.view', 'settings.promotion_images.view',
                'settings.notifications.view', 'settings.notification_recipients.view', 'settings.rbac.view'
            ]
        ];
    }

    /**
     * Check if user can access a sub-page.
     * Strict mode: requires the specific sub-page permission to be granted.
     * The parent .view permission only controls dropdown visibility, not sub-page access.
     */
    public static function canAccessSubPage($subPermission)
    {
        return self::hasPermission($subPermission);
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Require login - redirect to sign-in if not logged in
     */
    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {
            header('Location: /admin_booking/build/sign-in');
            exit;
        }
    }

    /**
     * Require specific permission - redirect to unauthorized page if not authorized
     */
    public static function requirePermission($permission)
    {
        self::requireLogin();

        if (!self::hasPermission($permission)) {
            self::unauthorized();
        }
    }

    /**
     * Require any of the specified permissions
     */
    public static function requireAnyPermission($permissions)
    {
        self::requireLogin();

        if (!self::hasAnyPermission($permissions)) {
            self::unauthorized();
        }
    }

    /**
     * Handle unauthorized access
     */
    public static function unauthorized()
    {
        http_response_code(403);
        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        } else {
            $_SESSION['error_message'] = 'You do not have permission to access this page.';
            header('Location: /admin_booking/build/');
        }
        exit;
    }

    /**
     * Check if route is accessible for current user
     */
    public static function canAccessRoute($route)
    {
        // super_admin always has full access
        if (self::getCurrentRole() === 'super_admin') {
            return true;
        }

        // Define route permissions (parent permission OR sub-page permission grants access)
        $routePermissions = [
            '' => ['dashboard.view'],
            // Properties & Rooms
            'properties-rooms' => ['properties.view'],
            'properties-rooms/properties' => ['properties.view', 'properties.properties.view'],
            'properties-rooms/rooms' => ['rooms.view', 'properties.rooms.view'],
            'properties-rooms/room-categories' => ['rooms.view', 'properties.room_categories.view'],
            'properties-rooms/amenities' => ['properties.view', 'properties.amenities.view'],
            'properties-rooms/room-blocks' => ['rooms.view', 'properties.room_blocks.view'],
            // Inventory
            'inventory' => ['inventory.view'],
            'inventory/product' => ['inventory.view', 'inventory.products.view'],
            'inventory/inventory-categories' => ['inventory.view', 'inventory.categories.view'],
            'inventory/units-of-measure' => ['inventory.view', 'inventory.units.view'],
            'inventory/inventory-locations' => ['inventory.view', 'inventory.locations.view'],
            'inventory/suppliers' => ['inventory.view', 'inventory.suppliers.view'],
            'inventory/product-stock-per-location' => ['inventory.view', 'inventory.stock_location.view'],
            'inventory/purchase-orders' => ['inventory.view', 'inventory.purchase_orders.view'],
            'inventory/stock-transactions' => ['inventory.view', 'inventory.stock_transactions.view'],
            'inventory/stock-transfers' => ['inventory.view', 'inventory.stock_transfers.view'],
            'inventory/inventory-sales' => ['inventory.view', 'inventory.sales.view'],
            // Food & Canteen
            'food-canteen' => ['food_canteen.view'],
            'food-canteen/food-categories' => ['food_canteen.view', 'food_canteen.categories.view'],
            'food-canteen/menu-items' => ['food_canteen.view', 'food_canteen.menu_items.view'],
            'food-canteen/food-orders' => ['food_canteen.view', 'food_canteen.orders.view'],
            // Reservations
            'reservations' => ['reservations.view'],
            'reservations/reservations' => ['reservations.view', 'reservations.reservations.view'],
            'reservations/reservation-rooms' => ['reservations.view', 'reservations.rooms.view'],
            'reservations/reservation-guests' => ['reservations.view', 'reservations.guests.view'],
            'reservations/reservation-requirement-types' => ['reservations.view', 'reservations.requirement_types.view'],
            'reservations/reservation-requirements' => ['reservations.view', 'reservations.requirements.view'],
            'reservations/check-in-logs' => ['reservations.view', 'reservations.check_in_logs.view'],
            'reservations/entrance-logs' => ['reservations.view', 'reservations.entrance_logs.view'],
            // Payments & Billing
            'payments-billing' => ['payments.view'],
            'payments-billing/payment-methods' => ['payments.view', 'payments.payment_methods.view'],
            'payments-billing/payments' => ['payments.view', 'payments.payments.view'],
            'payments-billing/invoices' => ['payments.view', 'payments.invoices.view'],
            'payments-billing/invoice-line-items' => ['payments.view', 'payments.invoice_line_items.view'],
            'payments-billing/discounts' => ['payments.view', 'payments.discounts.view'],
            'payments-billing/discount-usages' => ['payments.view', 'payments.discount_usages.view'],
            'payments-billing/extra-charges' => ['payments.view', 'payments.extra_charges.view'],
            'payments-billing/billing' => ['payments.view', 'payments.billing.view'],
            // Settings
            'settings' => ['settings.view'],
            'settings/system-settings' => ['settings.view', 'settings.system.view'],
            'settings/pricing-rules' => ['settings.view', 'settings.pricing_rules.view'],
            'settings/seasonal-rates' => ['settings.view', 'settings.seasonal_rates.view'],
            'settings/booking-policies' => ['settings.view', 'settings.booking_policies.view'],
            'settings/promotions' => ['settings.view', 'settings.promotions.view'],
            'settings/promotion-images' => ['settings.view', 'settings.promotion_images.view'],
            'settings/notifications' => ['settings.view', 'settings.notifications.view'],
            'settings/notification-recipients' => ['settings.view', 'settings.notification_recipients.view'],
            'settings/rbac-settings' => ['settings.view', 'settings.rbac.view'],
            // Reports
            'reports' => ['reports.view'],
            'reports/revenue' => ['reports.view', 'reports.revenue.view'],
            'reports/occupancy' => ['reports.view', 'reports.occupancy.view'],
            'reports/inventory' => ['reports.view', 'reports.inventory.view'],
            'reports/customers' => ['reports.view', 'reports.customers.view'],
            'reports/food-canteen' => ['reports.view', 'reports.food_canteen.view'],
            'reports/financial' => ['reports.view', 'reports.financial.view'],
            // Users
            'users' => ['users.view'],
            'users/create' => ['users.create'],
            'users/edit' => ['users.edit'],
            // Customers
            'customers/list' => ['customers.view']
        ];

        $required = $routePermissions[$route] ?? ['dashboard.view'];
        return self::hasAnyPermission($required);
    }
}
