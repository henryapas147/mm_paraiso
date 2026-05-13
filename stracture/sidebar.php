<?php
// Get current page name for highlighting active link
$current_page = basename($_SERVER['PHP_SELF']);

// Function to check if a page is active
function isPageActive($page, $current_page)
{
  return $current_page === $page;
}

// Function to check if any page in a dropdown is active
function isDropdownActive($pages, $current_page)
{
  foreach ($pages as $page) {
    if ($current_page === $page) {
      return true;
    }
  }
  return false;
}

// Define dropdown pages
$property_pages = ['properties.php', 'rooms.php', 'room_categories.php', 'amenities.php', 'room_blocks.php'];
$inventory_pages = ['product.php', 'inventory_categories.php', 'units_of_measure.php', 'inventory_locations.php', 'suppliers.php', 'product_stock_per_location.php', 'purchase_orders.php', 'stock_transactions.php', 'stock_transfers.php', 'inventory_sales.php'];
$food_canteen_pages = ['food_categories.php', 'menu_items.php', 'food_orders.php'];
$reservations_pages = ['reservations.php', 'reservation_rooms.php', 'reservation_guests.php', 'reservation_requirement_types.php', 'reservation_requirements.php', 'check_in_logs.php', 'entrance_logs.php'];
$payments_billing_pages = ['payment_methods.php', 'payments.php', 'invoices.php', 'invoice_line_items.php', 'discounts.php', 'discount_usages.php', 'extra_charges.php'];
$settings_pages = ['system_settings.php', 'pricing_rules.php', 'seasonal_rates.php', 'booking_policies.php', 'promotions.php', 'promotion_images.php', 'notifications.php', 'notification_recipients.php'];
?>

<aside class="fixed top-0 bottom-0 flex flex-col items-center w-full p-0 h-screen overflow-hidden antialiased transition-transform duration-200 -translate-x-full bg-white border-0 shadow-xl dark:shadow-none dark:bg-slate-850 max-w-64 ease-nav-brand z-990 rounded-2xl xl:left-0 xl:translate-x-0" aria-expanded="false">
  <div class="h-19 shrink-0">
    <i class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times dark:text-white text-slate-400 xl:hidden" sidenav-close></i>
    <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap dark:text-white text-slate-700" href="https://demos.creative-tim.com/argon-dashboard-tailwind/pages/dashboard.php" target="_blank">
      <img src="../assets/img/logo-ct-dark.png" class="inline h-full max-w-full transition-all duration-200 dark:hidden ease-nav-brand max-h-8" alt="main_logo" />
      <img src="../assets/img/logo-ct.png" class="hidden h-full max-w-full transition-all duration-200 dark:inline ease-nav-brand max-h-8" alt="main_logo" />
      <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand"> Admin Dashboard </span>
    </a>
  </div>

  <hr class="h-px mt-0 shrink-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />

  <div class="flex-1 overflow-y-auto w-full min-h-0 sidebar-scroll px-3 py-2">
    <ul class="flex flex-col mb-0">

      <li class="mt-0.5 w-full">
        <?php $active_class = ($current_page === 'dashboard.php') ? 'bg-blue-500/13 font-semibold text-slate-700 rounded-lg' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg'; ?>
        <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors <?php echo $active_class; ?>" href="../pages/dashboard.php">
          <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
            <i class="text-sm leading-normal text-blue-500 ni ni-tv-2"></i>
          </span>
          <span class="duration-300 opacity-100 pointer-events-none ease truncate">Dashboard</span>
        </a>
      </li>

      <li class="mt-0.5 w-full">
        <div class="relative">
          <?php $property_active = isDropdownActive($property_pages, $current_page); ?>
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
            <a href="properties.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('properties.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Properties</span>
            </a>
            <a href="rooms.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('rooms.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Rooms</span>
            </a>
            <a href="room_categories.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('room_categories.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Room Categories</span>
            </a>
            <a href="amenities.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('amenities.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Amenities</span>
            </a>
            <a href="room_blocks.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('room_blocks.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Room Blocks</span>
            </a>
          </div>
        </div>
      </li>

      <li class="mt-0.5 w-full">
        <div class="relative">
          <?php $inventory_active = isDropdownActive($inventory_pages, $current_page); ?>
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
            <a href="product.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('product.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Products</span>
            </a>
            <a href="inventory_categories.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory_categories.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Categories</span>
            </a>
            <a href="units_of_measure.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('units_of_measure.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Units of Measure</span>
            </a>
            <a href="inventory_locations.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory_locations.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Locations</span>
            </a>
            <a href="suppliers.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('suppliers.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Suppliers</span>
            </a>
            <a href="product_stock_per_location.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('product_stock_per_location.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Stock by Location</span>
            </a>
            <a href="purchase_orders.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('purchase_orders.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Purchase Orders</span>
            </a>
            <a href="stock_transactions.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('stock_transactions.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Stock Transactions</span>
            </a>
            <a href="stock_transfers.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('stock_transfers.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Stock Transfers</span>
            </a>
            <a href="inventory_sales.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('inventory_sales.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Inventory Sales</span>
            </a>
          </div>
        </div>
      </li>

      <li class="mt-0.5 w-full">
        <div class="relative">
          <?php $food_canteen_active = isDropdownActive($food_canteen_pages, $current_page); ?>
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
            <a href="food_categories.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('food_categories.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Food Categories</span>
            </a>
            <a href="menu_items.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('menu_items.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Menu Items</span>
            </a>
            <a href="food_orders.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('food_orders.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Food Orders</span>
            </a>
          </div>
        </div>
      </li>

      <li class="mt-0.5 w-full">
        <div class="relative">
          <?php $reservations_active = isDropdownActive($reservations_pages, $current_page); ?>
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
            <a href="reservations.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservations.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Reservations</span>
            </a>
            <a href="reservation_rooms.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservation_rooms.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Reservation Rooms</span>
            </a>
            <a href="reservation_guests.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservation_guests.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Reservation Guests</span>
            </a>
            <a href="reservation_requirement_types.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservation_requirement_types.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Requirement Types</span>
            </a>
            <a href="reservation_requirements.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('reservation_requirements.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Requirements</span>
            </a>
            <a href="check_in_logs.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('check_in_logs.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Check-in Logs</span>
            </a>
            <a href="entrance_logs.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('entrance_logs.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Entrance Logs</span>
            </a>
          </div>
        </div>
      </li>

      <li class="mt-0.5 w-full">
        <div class="relative">
          <?php $payments_billing_active = isDropdownActive($payments_billing_pages, $current_page); ?>
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
            <a href="payment_methods.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payment_methods.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Payment Methods</span>
            </a>
            <a href="payments.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('payments.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Payments</span>
            </a>
            <a href="invoices.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('invoices.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Invoices</span>
            </a>
            <a href="invoice_line_items.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('invoice_line_items.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Invoice Line Items</span>
            </a>
            <a href="discounts.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('discounts.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Discounts</span>
            </a>
            <a href="discount_usages.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('discount_usages.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Discount Usages</span>
            </a>
            <a href="extra_charges.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('extra_charges.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Extra Charges</span>
            </a>
          </div>
        </div>
      </li>

      <li class="mt-0.5 w-full">
        <div class="relative">
          <?php $settings_active = isDropdownActive($settings_pages, $current_page); ?>
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
            <a href="system_settings.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('system_settings.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">System Settings</span>
            </a>
            <a href="pricing_rules.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('pricing_rules.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Pricing Rules</span>
            </a>
            <a href="seasonal_rates.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('seasonal_rates.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Seasonal Rates</span>
            </a>
            <a href="booking_policies.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('booking_policies.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Booking Policies</span>
            </a>
            <a href="promotions.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('promotions.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Promotions</span>
            </a>
            <a href="promotion_images.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('promotion_images.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Promotion Images</span>
            </a>
            <a href="notifications.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('notifications.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Notifications</span>
            </a>
            <a href="notification_recipients.php" class="py-2 text-sm ease-nav-brand flex items-center px-3 transition-colors <?php echo isPageActive('notification_recipients.php', $current_page) ? 'bg-blue-500/13 font-semibold text-slate-700' : 'dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700'; ?> rounded-lg">
              <span class="flex-shrink-0 w-5 flex items-center justify-center mr-2">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
              </span>
              <span class="duration-300 opacity-100 pointer-events-none ease truncate">Notification Recipients</span>
            </a>
          </div>
        </div>
      </li>

      <li class="mt-0.5 w-full">
        <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg" href="../pages/virtual-reality.html">
          <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
            <i class="text-sm leading-normal text-cyan-500 ni ni-app"></i>
          </span>
          <span class="duration-300 opacity-100 pointer-events-none ease truncate">Virtual Reality</span>
        </a>
      </li>

      <li class="mt-0.5 w-full">
        <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg" href="../pages/rtl.html">
          <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
            <i class="text-sm leading-normal text-red-600 ni ni-world-2"></i>
          </span>
          <span class="duration-300 opacity-100 pointer-events-none ease truncate">RTL</span>
        </a>
      </li>

      <li class="w-full mt-4">
        <h6 class="px-3 text-xs font-bold leading-tight uppercase dark:text-white opacity-60">Account pages</h6>
      </li>

      <li class="mt-0.5 w-full">
        <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg" href="../pages/profile.php">
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
        <a class="py-2.5 text-sm ease-nav-brand my-0 flex items-center px-3 transition-colors dark:text-white dark:opacity-80 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg" href="../pages/sign-up.html">
          <span class="flex-shrink-0 flex h-8 w-8 items-center justify-center mr-2">
            <i class="text-sm leading-normal text-cyan-500 ni ni-collection"></i>
          </span>
          <span class="duration-300 opacity-100 pointer-events-none ease truncate">Sign Up</span>
        </a>
      </li>
    </ul>
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
  <?php if ($property_active): ?>if (localStorage.getItem('propertyDropdownOpen') === null) localStorage.setItem('propertyDropdownOpen', 'true');<?php endif; ?>
  <?php if ($inventory_active): ?>if (localStorage.getItem('inventoryDropdownOpen') === null) localStorage.setItem('inventoryDropdownOpen', 'true');<?php endif; ?>
  <?php if ($food_canteen_active): ?>if (localStorage.getItem('foodCanteenDropdownOpen') === null) localStorage.setItem('foodCanteenDropdownOpen', 'true');<?php endif; ?>
  <?php if ($reservations_active): ?>if (localStorage.getItem('reservationsDropdownOpen') === null) localStorage.setItem('reservationsDropdownOpen', 'true');<?php endif; ?>
  <?php if ($payments_billing_active): ?>if (localStorage.getItem('paymentsBillingDropdownOpen') === null) localStorage.setItem('paymentsBillingDropdownOpen', 'true');<?php endif; ?>
  <?php if ($settings_active): ?>if (localStorage.getItem('settingsDropdownOpen') === null) localStorage.setItem('settingsDropdownOpen', 'true');<?php endif; ?>

  // On active page, always force the correct dropdown open
  <?php if ($property_active): ?>localStorage.setItem('propertyDropdownOpen', 'true');<?php endif; ?>
  <?php if ($inventory_active): ?>localStorage.setItem('inventoryDropdownOpen', 'true');<?php endif; ?>
  <?php if ($food_canteen_active): ?>localStorage.setItem('foodCanteenDropdownOpen', 'true');<?php endif; ?>
  <?php if ($reservations_active): ?>localStorage.setItem('reservationsDropdownOpen', 'true');<?php endif; ?>
  <?php if ($payments_billing_active): ?>localStorage.setItem('paymentsBillingDropdownOpen', 'true');<?php endif; ?>
  <?php if ($settings_active): ?>localStorage.setItem('settingsDropdownOpen', 'true');<?php endif; ?>

  // Restore dropdown state and scroll position on page load
  document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar-scroll');

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
  });
</script>