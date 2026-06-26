<?php
// Check if this is the google-callback route (needs custom DB connection)
$url = $_SERVER['REQUEST_URI'] ?? '/';
$isGoogleCallback = strpos($url, 'build/google-callback') !== false || strpos($url, 'google-callback') !== false;

// Load db, session and autoloader before any output (skip db for google-callback)
if (!$isGoogleCallback) {
    require_once __DIR__ . '/../config/db.php';
}
require_once __DIR__ . '/core/Autoloader.php';
require_once __DIR__ . '/core/Router.php';

// Initialize router
$router = new Router();

// Define routes
// Google OAuth callback (standalone file, bypass router)
if (strpos($url, 'google-callback') !== false) {
    require_once __DIR__ . '/google-callback.php';
    exit;
}

// Facebook OAuth callback (standalone file, bypass router)
if (strpos($url, 'facebook-callback') !== false) {
    require_once __DIR__ . '/facebook-callback.php';
    exit;
}

// Privacy Policy and Data Deletion pages (standalone files, bypass router)
if (strpos($url, 'privacy-policy.php') !== false || strpos($url, 'privacy-policy') !== false) {
    require_once __DIR__ . '/privacy-policy.php';
    exit;
}
if (strpos($url, 'data-deletion.php') !== false || strpos($url, 'data-deletion') !== false) {
    require_once __DIR__ . '/data-deletion.php';
    exit;
}

// Dashboard
$router->add('', ['controller' => 'DashboardController', 'action' => 'index']);

// API routes - bypass router and include directly
// Check for notifications API requests
if (strpos($url, 'notifications.php') !== false || strpos($url, 'api/notifications') !== false) {
    // Debug logging
    error_log("API Route Debug - Caught notifications request: " . $url);
    error_log("API Route Debug - REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A'));
    require_once __DIR__ . '/api/notifications.php';
    exit;
}

// Properties & Rooms
$router->add('properties-rooms', ['controller' => 'PropertiesController', 'action' => 'index']);
$router->add('properties-rooms/properties', ['controller' => 'PropertiesController', 'action' => 'properties']);
$router->add('properties-rooms/rooms', ['controller' => 'PropertiesController', 'action' => 'rooms']);
$router->add('properties-rooms/room-categories', ['controller' => 'PropertiesController', 'action' => 'roomCategories']);
$router->add('properties-rooms/amenities', ['controller' => 'PropertiesController', 'action' => 'amenities']);
$router->add('properties-rooms/room-blocks', ['controller' => 'PropertiesController', 'action' => 'roomBlocks']);
$router->add('properties-rooms/dining-tables', ['controller' => 'PropertiesController', 'action' => 'diningTables']);

// Inventory
$router->add('inventory', ['controller' => 'InventoryController', 'action' => 'index']);
$router->add('inventory/product', ['controller' => 'InventoryController', 'action' => 'product']);
$router->add('inventory/inventory-categories', ['controller' => 'InventoryController', 'action' => 'inventoryCategories']);
$router->add('inventory/units-of-measure', ['controller' => 'InventoryController', 'action' => 'unitsOfMeasure']);
$router->add('inventory/inventory-locations', ['controller' => 'InventoryController', 'action' => 'inventoryLocations']);
$router->add('inventory/suppliers', ['controller' => 'InventoryController', 'action' => 'suppliers']);
$router->add('inventory/product-stock-per-location', ['controller' => 'InventoryController', 'action' => 'productStockPerLocation']);
$router->add('inventory/product-uom-conversions', ['controller' => 'InventoryController', 'action' => 'productUomConversions']);
$router->add('inventory/inventory-transactions', ['controller' => 'InventoryController', 'action' => 'inventoryTransactions']);
$router->add('inventory/purchase-orders', ['controller' => 'InventoryController', 'action' => 'purchaseOrders']);
$router->add('inventory/purchase-orders-receiving', ['controller' => 'InventoryController', 'action' => 'purchaseOrdersReceiving']);
$router->add('inventory/stock-transactions', ['controller' => 'InventoryController', 'action' => 'stockTransactions']);
$router->add('inventory/stock-transfers', ['controller' => 'InventoryController', 'action' => 'stockTransfers']);
$router->add('inventory/stock-in', ['controller' => 'InventoryController', 'action' => 'stockIn']);
$router->add('inventory/inventory-sales', ['controller' => 'InventoryController', 'action' => 'inventorySales']);
$router->add('inventory/pos', ['controller' => 'InventoryController', 'action' => 'pos']);


// Food & Canteen
$router->add('food-canteen', ['controller' => 'FoodCanteenController', 'action' => 'index']);
$router->add('food-canteen/food-categories', ['controller' => 'FoodCanteenController', 'action' => 'foodCategories']);
$router->add('food-canteen/menu-items', ['controller' => 'FoodCanteenController', 'action' => 'menuItems']);
$router->add('food-canteen/recipes', ['controller' => 'FoodCanteenController', 'action' => 'recipes']);
$router->add('food-canteen/food-orders', ['controller' => 'FoodCanteenController', 'action' => 'foodOrders']);

// Reservations
$router->add('reservations', ['controller' => 'ReservationsController', 'action' => 'index']);
$router->add('reservations/reservations', ['controller' => 'ReservationsController', 'action' => 'reservations']);
$router->add('reservations/reservation-rooms', ['controller' => 'ReservationsController', 'action' => 'reservationRooms']);
$router->add('reservations/reservation-guests', ['controller' => 'ReservationsController', 'action' => 'reservationGuests']);
$router->add('reservations/reservation-requirement-types', ['controller' => 'ReservationsController', 'action' => 'reservationRequirementTypes']);
$router->add('reservations/reservation-requirements', ['controller' => 'ReservationsController', 'action' => 'reservationRequirements']);
$router->add('reservations/walk-in-guests', ['controller' => 'ReservationsController', 'action' => 'walkInGuests']);
$router->add('reservations/check-in-logs', ['controller' => 'ReservationsController', 'action' => 'checkInLogs']);
$router->add('reservations/entrance-logs', ['controller' => 'ReservationsController', 'action' => 'entranceLogs']);
$router->add('reservations/reservation-promotions', ['controller' => 'ReservationsController', 'action' => 'reservationPromotions']);
$router->add('reservations/pricing-breakdown', ['controller' => 'ReservationsController', 'action' => 'pricingBreakdown']);

// Payments & Billing
$router->add('payments-billing', ['controller' => 'PaymentsBillingController', 'action' => 'index']);
$router->add('payments-billing/payment-methods', ['controller' => 'PaymentsBillingController', 'action' => 'paymentMethods']);
$router->add('payments-billing/payment-accounts', ['controller' => 'PaymentsBillingController', 'action' => 'paymentAccounts']);
$router->add('payments-billing/payments', ['controller' => 'PaymentsBillingController', 'action' => 'payments']);
$router->add('payments-billing/invoices', ['controller' => 'PaymentsBillingController', 'action' => 'invoices']);
$router->add('payments-billing/invoice-line-items', ['controller' => 'PaymentsBillingController', 'action' => 'invoiceLineItems']);
$router->add('payments-billing/discounts', ['controller' => 'PaymentsBillingController', 'action' => 'discounts']);
$router->add('payments-billing/discount-usages', ['controller' => 'PaymentsBillingController', 'action' => 'discountUsages']);
$router->add('payments-billing/extra-charges', ['controller' => 'PaymentsBillingController', 'action' => 'extraCharges']);
$router->add('payments-billing/billing', ['controller' => 'PaymentsBillingController', 'action' => 'billing']);

// Settings
$router->add('settings', ['controller' => 'SettingsController', 'action' => 'index']);
$router->add('settings/system-settings', ['controller' => 'SettingsController', 'action' => 'systemSettings']);
$router->add('settings/pricing-rules', ['controller' => 'SettingsController', 'action' => 'pricingRules']);
$router->add('settings/seasonal-rates', ['controller' => 'SettingsController', 'action' => 'seasonalRates']);
$router->add('settings/booking-policies', ['controller' => 'SettingsController', 'action' => 'bookingPolicies']);
$router->add('settings/promotions', ['controller' => 'SettingsController', 'action' => 'promotions']);
$router->add('settings/promotion-images', ['controller' => 'SettingsController', 'action' => 'promotionImages']);
$router->add('settings/notifications', ['controller' => 'SettingsController', 'action' => 'notifications']);
$router->add('settings/notification-recipients', ['controller' => 'SettingsController', 'action' => 'notificationRecipients']);
$router->add('settings/rbac-settings', ['controller' => 'SettingsController', 'action' => 'rbacSettings']);
$router->add('settings/company', ['controller' => 'SettingsController', 'action' => 'company']);

// Content Management
$router->add('content-management/carousel', ['controller' => 'SettingsController', 'action' => 'carouselSlides']);

// Users
$router->add('users', ['controller' => 'UsersController', 'action' => 'index']);
$router->add('users/create', ['controller' => 'UsersController', 'action' => 'create']);
$router->add('users/edit', ['controller' => 'UsersController', 'action' => 'edit']);

// Customers (admin management)
$router->add('customers', ['controller' => 'CustomerController', 'action' => 'index']);
$router->add('customers/list', ['controller' => 'CustomerController', 'action' => 'customersList']);

// Customer Portal (customer-facing)
$router->add('customer/dashboard', ['controller' => 'CustomerController', 'action' => 'dashboard']);
$router->add('customer/reservations', ['controller' => 'CustomerController', 'action' => 'reservations']);
$router->add('customer/food-orders', ['controller' => 'CustomerController', 'action' => 'foodOrders']);
$router->add('customer/payments', ['controller' => 'CustomerController', 'action' => 'payments']);
$router->add('customer/profile', ['controller' => 'CustomerProfileController', 'action' => 'index']);

// Reports & Analytics
$router->add('reports', ['controller' => 'ReportsController', 'action' => 'index']);
$router->add('reports/revenue', ['controller' => 'ReportsController', 'action' => 'revenue']);
$router->add('reports/occupancy', ['controller' => 'ReportsController', 'action' => 'occupancy']);
$router->add('reports/inventory', ['controller' => 'ReportsController', 'action' => 'inventory']);
$router->add('reports/customers', ['controller' => 'ReportsController', 'action' => 'customers']);
$router->add('reports/food-canteen', ['controller' => 'ReportsController', 'action' => 'foodCanteen']);
$router->add('reports/financial', ['controller' => 'ReportsController', 'action' => 'financial']);
$router->add('reports/pos-sales', ['controller' => 'ReportsController', 'action' => 'posSales']);

// Authentication
$router->add('sign-in', ['controller' => 'AuthController', 'action' => 'signIn']);
$router->add('sign-up', ['controller' => 'AuthController', 'action' => 'signUp']);
$router->add('sign-out', ['controller' => 'AuthController', 'action' => 'signout']);
$router->add('logout', ['controller' => 'AuthController', 'action' => 'logout']);
$router->add('profile', ['controller' => 'ProfileController', 'action' => 'index']);
$router->add('account-settings', ['controller' => 'AuthController', 'action' => 'accountSettings']);

// OAuth - Gmail refresh token
$router->add('get-refresh-token', ['file' => 'get_refresh_token.php']);

// OAuth - Google Sign-In callback
$router->add('build/google-callback', ['file' => 'google-callback.php']);

// Email Verification
$router->add('verify-email', ['file' => 'verify-email.php']);
$router->add('build/verify-email', ['file' => 'verify-email.php']);

// Contact Inquiries
$router->add('contact-inquiries', ['controller' => 'ContactInquiriesController', 'action' => 'index']);
$router->add('contact-inquiries/view', ['controller' => 'ContactInquiriesController', 'action' => 'detail']);
$router->add('contact-inquiries/updateStatus', ['controller' => 'ContactInquiriesController', 'action' => 'updateStatus']);
$router->add('contact-inquiries/delete', ['controller' => 'ContactInquiriesController', 'action' => 'delete']);

// Remove query string from URL (reuse $url set at line 3)
$url = strtok($url, '?');

// Remove base path if needed - use SCRIPT_NAME (not PHP_SELF) to prevent path injection
$base_path = dirname($_SERVER['SCRIPT_NAME']);
if ($base_path != '/' && $base_path != '\\') {
    $url = str_replace($base_path, '', $url);
}

// Remove leading slash if present for route matching
if (substr($url, 0, 1) == '/') {
    $url = substr($url, 1);
}

// Dispatch the router
try {
    $router->dispatch($url);
} catch (Exception $e) {
    error_log('Router error: ' . $e->getMessage());
    http_response_code(404);
    echo 'Page not found.';
}
