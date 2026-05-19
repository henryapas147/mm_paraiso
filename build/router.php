<?php

// Load autoloader
require_once 'core/Autoloader.php';

// Initialize router
$router = new Router();

// Define routes
// Dashboard
$router->add('', ['controller' => 'Dashboard', 'action' => 'index']);

// Profile
$router->add('profile', ['controller' => 'Profile', 'action' => 'index']);
$router->add('pages/profile', ['controller' => 'Profile', 'action' => 'index']);

// Properties & Routes
$router->add('properties-rooms', ['controller' => 'Properties', 'action' => 'index']);
$router->add('properties-rooms/properties', ['controller' => 'Properties', 'action' => 'properties']);
$router->add('properties-rooms/rooms', ['controller' => 'Properties', 'action' => 'rooms']);
$router->add('properties-rooms/room-categories', ['controller' => 'Properties', 'action' => 'roomCategories']);
$router->add('properties-rooms/amenities', ['controller' => 'Properties', 'action' => 'amenities']);
$router->add('properties-rooms/room-blocks', ['controller' => 'Properties', 'action' => 'roomBlocks']);

// Inventory
$router->add('inventory', ['controller' => 'Inventory', 'action' => 'index']);
$router->add('inventory/product', ['controller' => 'Inventory', 'action' => 'product']);
$router->add('inventory/inventory-categories', ['controller' => 'Inventory', 'action' => 'inventoryCategories']);
$router->add('inventory/units-of-measure', ['controller' => 'Inventory', 'action' => 'unitsOfMeasure']);
$router->add('inventory/inventory-locations', ['controller' => 'Inventory', 'action' => 'inventoryLocations']);
$router->add('inventory/suppliers', ['controller' => 'Inventory', 'action' => 'suppliers']);
$router->add('inventory/product-stock-per-location', ['controller' => 'Inventory', 'action' => 'productStockPerLocation']);
$router->add('inventory/purchase-orders', ['controller' => 'Inventory', 'action' => 'purchaseOrders']);
$router->add('inventory/stock-transactions', ['controller' => 'Inventory', 'action' => 'stockTransactions']);
$router->add('inventory/stock-transfers', ['controller' => 'Inventory', 'action' => 'stockTransfers']);
$router->add('inventory/inventory-sales', ['controller' => 'Inventory', 'action' => 'inventorySales']);
$router->add('inventory/inventory-actions', ['controller' => 'Inventory', 'action' => 'inventoryActions']);

// Food & Canteen
$router->add('food-canteen', ['controller' => 'FoodCanteen', 'action' => 'index']);
$router->add('food-canteen/food-categories', ['controller' => 'FoodCanteen', 'action' => 'foodCategories']);
$router->add('food-canteen/menu-items', ['controller' => 'FoodCanteen', 'action' => 'menuItems']);
$router->add('food-canteen/food-orders', ['controller' => 'FoodCanteen', 'action' => 'foodOrders']);

// Reservations
$router->add('reservations', ['controller' => 'Reservations', 'action' => 'index']);
$router->add('reservations/reservations', ['controller' => 'Reservations', 'action' => 'reservations']);
$router->add('reservations/reservation-rooms', ['controller' => 'Reservations', 'action' => 'reservationRooms']);
$router->add('reservations/reservation-guests', ['controller' => 'Reservations', 'action' => 'reservationGuests']);
$router->add('reservations/reservation-requirement-types', ['controller' => 'Reservations', 'action' => 'reservationRequirementTypes']);
$router->add('reservations/reservation-requirements', ['controller' => 'Reservations', 'action' => 'reservationRequirements']);
$router->add('reservations/check-in-logs', ['controller' => 'Reservations', 'action' => 'checkInLogs']);
$router->add('reservations/entrance-logs', ['controller' => 'Reservations', 'action' => 'entranceLogs']);

// Payments & Billing
$router->add('payments-billing', ['controller' => 'PaymentsBilling', 'action' => 'index']);
$router->add('payments-billing/payment-methods', ['controller' => 'PaymentsBilling', 'action' => 'paymentMethods']);
$router->add('payments-billing/payments', ['controller' => 'PaymentsBilling', 'action' => 'payments']);
$router->add('payments-billing/invoices', ['controller' => 'PaymentsBilling', 'action' => 'invoices']);
$router->add('payments-billing/invoice-line-items', ['controller' => 'PaymentsBilling', 'action' => 'invoiceLineItems']);
$router->add('payments-billing/discounts', ['controller' => 'PaymentsBilling', 'action' => 'discounts']);
$router->add('payments-billing/discount-usages', ['controller' => 'PaymentsBilling', 'action' => 'discountUsages']);
$router->add('payments-billing/extra-charges', ['controller' => 'PaymentsBilling', 'action' => 'extraCharges']);
$router->add('payments-billing/billing', ['controller' => 'PaymentsBilling', 'action' => 'billing']);

// Settings
$router->add('settings', ['controller' => 'Settings', 'action' => 'index']);
$router->add('settings/system-settings', ['controller' => 'Settings', 'action' => 'systemSettings']);
$router->add('settings/pricing-rules', ['controller' => 'Settings', 'action' => 'pricingRules']);
$router->add('settings/seasonal-rates', ['controller' => 'Settings', 'action' => 'seasonalRates']);
$router->add('settings/booking-policies', ['controller' => 'Settings', 'action' => 'bookingPolicies']);
$router->add('settings/promotions', ['controller' => 'Settings', 'action' => 'promotions']);
$router->add('settings/promotion-images', ['controller' => 'Settings', 'action' => 'promotionImages']);
$router->add('settings/notifications', ['controller' => 'Settings', 'action' => 'notifications']);
$router->add('settings/notification-recipients', ['controller' => 'Settings', 'action' => 'notificationRecipients']);
$router->add('settings/rbac-settings', ['controller' => 'Settings', 'action' => 'rbacSettings']);

// Users
$router->add('users', ['controller' => 'Users', 'action' => 'index']);
$router->add('users/create', ['controller' => 'Users', 'action' => 'create']);
$router->add('users/edit', ['controller' => 'Users', 'action' => 'edit']);

// Customers
$router->add('customers', ['controller' => 'Customer', 'action' => 'index']);
$router->add('customers/list', ['controller' => 'Customer', 'action' => 'customersList']);

// Contact Inquiries
$router->add('contact-inquiries', ['controller' => 'ContactInquiries', 'action' => 'index']);
$router->add('contact-inquiries/view', ['controller' => 'ContactInquiries', 'action' => 'detail']);
$router->add('contact-inquiries/updateStatus', ['controller' => 'ContactInquiries', 'action' => 'updateStatus']);
$router->add('contact-inquiries/delete', ['controller' => 'ContactInquiries', 'action' => 'delete']);

// Authentication
$router->add('sign-in', ['controller' => 'Auth', 'action' => 'signIn']);
$router->add('sign-up', ['controller' => 'Auth', 'action' => 'signUp']);
$router->add('sign-out', ['controller' => 'Auth', 'action' => 'logout']);

// Get the URL
$url = $_SERVER['REQUEST_URI'] ?? '/';

// Remove query string from URL
$url = strtok($url, '?');

// Remove base path if needed
$base_path = dirname($_SERVER['PHP_SELF']);
if ($base_path != '/') {
    $url = str_replace($base_path, '', $url);
}

// Remove leading slash if present
$url = ltrim($url, '/');

// Debug output
error_log("Router Debug - Original REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A'));
error_log("Router Debug - Base path: " . $base_path);
error_log("Router Debug - Final URL for dispatch: " . $url);

// Dispatch the router
try {
    $router->dispatch($url);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    echo '<br>Debug - URL processed: ' . htmlspecialchars($url);
    echo '<br>Debug - REQUEST_URI: ' . htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'N/A');
    echo '<br>Debug - Base path: ' . htmlspecialchars($base_path);
}
