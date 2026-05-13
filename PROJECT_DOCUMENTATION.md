# Paradise Resort/Hotel Management System - Documentation

## Project Overview
A comprehensive web-based reservation and management system for resorts/hotels with modules for properties, rooms, inventory, food & canteen, reservations, payments, and customer management.

---

## Technology Stack
- **Backend**: PHP 8.0+ with custom MVC framework
- **Database**: MySQL 8.0+ (InnoDB)
- **Frontend**: Tailwind CSS, JavaScript (vanilla), Argon Dashboard UI
- **Authentication**: Session-based with UUID user IDs
- **Charset**: utf8mb4

---

## Database Schema (8 Modules)

### 01. Users & Authentication
| Table | Description |
|-------|-------------|
| `user_roles` | System roles (super_admin, admin, front_desk, housekeeping, canteen_staff, security, customer, api_client) |
| `user_accounts` | Core credentials with UUID PK, soft delete, brute-force lockout support |
| `user_profiles` | Extended PII data (name, address, ID verification, emergency contacts) |
| `user_sessions` | Multi-device session management |
| `audit_logs` | Immutable audit trail for compliance |
| `login_attempts` | Rate limiting and security monitoring |

**Implemented**: ✅ Full schema + seed data with 2 sample customers

---

### 02. Properties & Rooms
| Table | Description |
|-------|-------------|
| `properties` | Property master with tax rates, check-in/out times |
| `room_categories` | Room types with pricing tiers (standard, deluxe, suite) |
| `rooms` | Individual room inventory with status tracking |
| `room_amenities` | Many-to-many room-amenity relationships |
| `amenities` | Master amenity list (WiFi, AC, TV, etc.) |
| `room_blocks` | Maintenance/out-of-service blocking |

**Implemented**: ✅ Schema + UI + API

**UI Pages**:
- Properties (`properties-rooms/properties`)
- Rooms (`properties-rooms/rooms`)
- Room Categories (`properties-rooms/room-categories`)
- Amenities (`properties-rooms/amenities`)
- Room Blocks (`properties-rooms/room-blocks`)

**API Endpoints**:
- `GET/POST api/properties.php`
- `GET/POST api/rooms.php`
- `GET/POST api/room_categories.php`
- `GET/POST api/amenities.php`
- `GET/POST api/room_blocks.php`

---

### 03. Reservations & Booking
| Table | Description |
|-------|-------------|
| `reservations` | Master reservation with financial totals (denormalized for performance) |
| `reservation_rooms` | Line items - rooms per reservation |
| `reservation_guests` | Guest manifest per reservation room |
| `reservation_requirement_types` | Configurable required documents (ID, vaccination, etc.) |
| `reservation_requirements` | Submitted requirement documents |
| `check_in_logs` | Entry/exit tracking |
| `entrance_logs` | Gate access logging |

**Reservation Status Flow**:
```
pending → confirmed → checked_in → checked_out
   ↓         ↓           ↓
cancelled  no_show   (completed)
```

**Implemented**: ✅ Schema + UI + API

**UI Pages**:
- Reservations List (`reservations/reservations`)
- Reservation Rooms (`reservations/reservation-rooms`)
- Reservation Guests (`reservations/reservation-guests`)
- Requirement Types (`reservations/reservation-requirement-types`)
- Requirements (`reservations/reservation-requirements`)
- Check-in Logs (`reservations/check-in-logs`)
- Entrance Logs (`reservations/entrance-logs`)

---

### 04. Payments & Billing
| Table | Description |
|-------|-------------|
| `payment_methods` | Configurable payment types (cash, card, GCash, etc.) |
| `payments` | Payment transactions |
| `invoices` | Guest invoices with line items |
| `invoice_line_items` | Detailed charges per invoice |
| `discounts` | Promo codes and discount rules |
| `discount_usages` | Applied discounts tracking |
| `extra_charges` | Additional fees (late checkout, damages, etc.) |
| `billing` | Guest folio/billing summary |

**Implemented**: ✅ Schema + UI + API

**UI Pages**:
- Payment Methods, Payments, Invoices, Invoice Line Items
- Discounts, Discount Usages, Extra Charges, Billing

---

### 05. Food & Canteen
| Table | Description |
|-------|-------------|
| `food_categories` | Menu categories (Breakfast, Lunch, Snacks, Beverages) |
| `menu_items` | Food items with cost tracking for profit margins |
| `menu_item_images` | Item photos |
| `food_orders` | Orders linked to reservations or walk-in |
| `food_order_items` | Line items per order |

**Order Types**: `dine_in`, `room_service`, `takeout`

**Order Status**: `pending` → `confirmed` → `preparing` → `ready` → `delivered`

**Implemented**: ✅ Schema + UI + API

**UI Pages**:
- Food Categories (`food-canteen/food-categories`)
- Menu Items (`food-canteen/menu-items`)
- Food Orders (`food-canteen/food-orders`)

---

### 06. Inventory Management
| Table | Description |
|-------|-------------|
| `inventory_categories` | Hierarchical product categories |
| `units_of_measure` | UOM master (pcs, kg, L, box, etc.) |
| `inventory_locations` | Storage locations (warehouse, kitchen, bar) |
| `suppliers` | Vendor master with payment terms |
| `products` | Product catalog with reorder levels |
| `product_stock` | Stock levels per location |
| `purchase_orders` | POs to suppliers |
| `purchase_order_items` | PO line items |
| `stock_transactions` | Stock in/out/adjustments |
| `stock_transfers` | Inter-location transfers |
| `inventory_sales` | Direct inventory sales |

**Implemented**: ✅ Schema + UI + API

**UI Pages**:
- Products, Inventory Categories, Units of Measure, Inventory Locations
- Suppliers, Product Stock per Location, Purchase Orders
- Stock Transactions, Stock Transfers, Inventory Sales, Inventory Actions

---

### 07. Settings, Promotions & Notifications
| Table | Description |
|-------|-------------|
| `system_settings` | Global configuration |
| `pricing_rules` | Dynamic pricing by day/season |
| `seasonal_rates` | Date-range pricing overrides |
| `booking_policies` | Cancellation, no-show, minimum stay rules |
| `promotions` | Marketing promos with view tracking |
| `promotion_images` | Promo banners |
| `notifications` | System notifications |
| `notification_recipients` | User notification preferences |

**Implemented**: ✅ Schema + UI + API

**UI Pages**:
- System Settings, Pricing Rules, Seasonal Rates, Booking Policies
- Promotions, Promotion Images, Notifications, Notification Recipients

---

## Module Status Summary

| Module | Schema | UI | API | Status |
|--------|--------|-----|-----|--------|
| 01. Users & Auth | ✅ | ✅ | ✅ | Complete |
| 02. Properties & Rooms | ✅ | ✅ | ✅ | Complete |
| 03. Reservations | ✅ | ✅ | ✅ | Complete |
| 04. Payments & Billing | ✅ | ✅ | ✅ | Complete |
| 05. Food & Canteen | ✅ | ✅ | ✅ | Complete |
| 06. Inventory | ✅ | ✅ | ✅ | Complete |
| 07. Settings | ✅ | ✅ | ✅ | Complete |
| **08. Customers** | ✅ | ✅ | ✅ | **NEW - Complete** |

---

## NEW: Customer Management Module

**Files Created**:
```
build/
├── controllers/CustomerController.php
├── views/customers/customers_list.php
├── api/customers.php
└── index.php (routes added)

structure/
└── sidebar.php (menu item added)
```

**Routes**:
- `GET /customers` → CustomerController::index()
- `GET /customers/list` → CustomerController::customersList()

**API Endpoints**:
- `GET api/customers.php?action=list` - List all customers
- `GET api/customers.php?action=single&id={uuid}` - Get customer details
- `POST api/customers.php?action=create` - Create customer
- `POST api/customers.php?action=update` - Update customer
- `POST api/customers.php?action=delete` - Soft delete customer

**Database Tables Used**:
- `user_accounts` (role_id = 7 for customers)
- `user_profiles` (joined on user_id)

**Sample Data** (from seed):
1. Juan dela Cruz (juan.delacruz@email.com)
2. Maria Santos (maria.santos@email.com)

---

## UI/UX Improvements Made

### 1. Profile Page (MVC Refactor)
- ✅ Moved from `pages/profile.php` to `views/auth/profile.php`
- ✅ Added proper form IDs for accessibility
- ✅ Fixed label `for` attributes matching input IDs
- ✅ Fixed image paths using `$assets_path`
- ✅ Added `$pageTitle` variable support

### 2. Customizer Persistence (Dark Mode Fix)
- ✅ Fixed `Uncaught TypeError` in `fixed-plugin.js`
- ✅ Added localStorage persistence for:
  - Sidebar color
  - Sidenav style
  - Navbar fixed state
  - Dark mode toggle
- ✅ Added retry logic for async script loading
- ✅ Removed console logging after fix

### 3. Sidebar Navigation
- ✅ Added dropdown persistence with localStorage
- ✅ Added active state highlighting for all pages
- ✅ Fixed base URL calculation for correct links
- ✅ Removed auto-scroll feature (reverted due to Perfect Scrollbar conflicts)

### 4. Router Fixes
- ✅ Added `ltrim($url, '/')` to handle leading slashes
- ✅ Renamed `list()` method to `customersList()` (PHP reserved keyword)
- ✅ Added routes in correct file (`index.php` not `router.php`)

---

## Missing Implementations / Future Enhancements

### 1. Reports & Analytics
- ❌ Revenue reports
- ❌ Occupancy reports
- ❌ Inventory reports
- ❌ Customer analytics
- ❌ Food & canteen sales reports

### 2. Advanced Features
- ❌ Housekeeping module (cleaning schedules, room status)
- ❌ Maintenance ticketing system
- ❌ Staff scheduling/shift management
- ❌ Multi-property support (currently single property)
- ❌ Online booking portal (public-facing)
- ❌ Mobile app API endpoints
- ❌ Email/SMS notification system (queue-based)

### 3. Security Enhancements
- ❌ CSRF tokens on forms
- ❌ Rate limiting on API endpoints
- ❌ API key authentication for mobile clients
- ❌ Two-factor authentication (2FA)
- ❌ Password strength requirements UI
- ❌ Session timeout warnings

### 4. UX Improvements
- ❌ Loading spinners on all async operations
- ❌ Toast notifications (currently using Swal alerts)
- ❌ Bulk actions (delete multiple records)
- ❌ Data export (CSV, PDF)
- ❌ Print-friendly layouts
- ❌ Better mobile responsiveness

### 5. Database Missing Tables
From schema analysis, the following tables exist in schema but may need API/views:
- ✅ `menu_item_images` - exists but no dedicated UI
- ✅ `product_stock` - basic endpoint exists
- ✅ `reservation_rooms` - covered in reservations API
- ✅ `reservation_guests` - covered in reservations API
- ✅ `food_order_items` - covered in food_orders API
- ✅ `purchase_order_items` - covered in purchase_orders API

---

## File Structure

```
admin_booking/
├── build/                          # Web root
│   ├── api/                        # 39 API endpoints
│   ├── assets/
│   │   ├── css/                    # Tailwind + custom styles
│   │   ├── js/                     # Custom plugins + vendor libs
│   │   └── img/                    # Logos, avatars, illustrations
│   ├── config/
│   │   └── db.php                  # Database connection
│   ├── controllers/                # 10 MVC controllers
│   ├── core/                       # Framework core
│   │   ├── Router.php              # URL routing
│   │   ├── Autoloader.php          # PSR-4 autoloader
│   │   └── Controller.php          # Base controller
│   ├── views/                      # 48 view templates
│   │   ├── auth/                   # Login, profile, register
│   │   ├── customers/              # NEW: Customer management
│   │   ├── dashboard.php           # Main dashboard
│   │   ├── food-canteen/
│   │   ├── inventory/
│   │   ├── payments-billing/
│   │   ├── properties-rooms/
│   │   ├── reservations/
│   │   └── settings/
│   ├── .htaccess                   # URL rewriting
│   ├── index.php                 # Application entry point
│   └── router.php                # (legacy - not used)
├── database/                       # 8 SQL migration files
├── structure/                      # Shared components
│   ├── customizer.php              # Theme customizer panel
│   ├── footer.php                  # Page footer
│   ├── head.php                    # HTML head template
│   ├── navbar.php                  # Top navigation
│   ├── script.php                  # JS includes
│   └── sidebar.php                 # Side navigation
├── docs/                           # Documentation
└── pages_old/                      # Legacy pages (deprecated)
```

---

## API Patterns

All API endpoints follow consistent patterns:

### Response Format
```json
{
  "success": true|false,
  "data": [...] | {...},
  "message": "Operation result message"
}
```

### CRUD Operations
- **List**: `GET api/{entity}.php?action=list`
- **Single**: `GET api/{entity}.php?action=single&id={uuid}`
- **Create**: `POST api/{entity}.php?action=create` (JSON body)
- **Update**: `POST api/{entity}.php?action=update` (JSON body)
- **Delete**: `POST api/{entity}.php?action=delete` (JSON body with id)

### UUID Generation
```php
sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
  mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
  mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
  mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff))
```

---

## Controllers Structure

| Controller | Methods |
|------------|---------|
| `AuthController` | signIn, signUp, logout, accountSettings |
| `Controller` | Base class with view(), model() methods |
| `CustomerController` | index, customersList |
| `DashboardController` | index |
| `FoodCanteenController` | index, foodCategories, menuItems, foodOrders |
| `InventoryController` | index, product, inventoryCategories, unitsOfMeasure, inventoryLocations, suppliers, productStockPerLocation, purchaseOrders, stockTransactions, stockTransfers, inventorySales, inventoryActions |
| `PaymentsBillingController` | index, paymentMethods, payments, invoices, invoiceLineItems, discounts, discountUsages, extraCharges, billing |
| `ProfileController` | index |
| `PropertiesController` | index, properties, rooms, roomCategories, amenities, roomBlocks |
| `ReservationsController` | index, reservations, reservationRooms, reservationGuests, reservationRequirementTypes, reservationRequirements, checkInLogs, entranceLogs |
| `SettingsController` | index, systemSettings, pricingRules, seasonalRates, bookingPolicies, promotions, promotionImages, notifications, notificationRecipients |

---

## Known Issues & Fixes Applied

| Issue | Fix Applied |
|-------|-------------|
| `Uncaught TypeError: Cannot read properties of null (reading 'classList')` | Added null checks in `sidebarColor()` function |
| Dark mode not persisting | Added localStorage + retry logic in `fixed-plugin.js` |
| Profile page form accessibility | Added matching `id` attributes to all inputs |
| Route matching failing for `/customers/list` | Added `ltrim($url, '/')` in router |
| PHP reserved keyword `list` | Renamed to `customersList()` in controller |
| Perfect Scrollbar conflict with auto-scroll | Removed auto-scroll feature |

---

## Quick Start for Developers

1. **Database Setup**:
   ```bash
   mysql -u root -p < database/00_run_all.sql
   mysql -u root -p < database/08_seed_data.sql
   ```

2. **Configuration**:
   - Edit `build/config/db.php` with your database credentials

3. **Web Server**:
   - Point document root to `admin_booking/build/`
   - Ensure `.htaccess` rewrite rules are enabled

4. **Login**:
   - URL: `http://localhost/admin_booking/build/sign-in`
   - Email: `superadmin@resortms.com`
   - Password: `Admin@1234`

---

## Credits

- UI Theme: Argon Dashboard Tailwind by Creative Tim
- Icons: Nucleo Icons, Font Awesome
- Database Design: Custom schema for resort/hotel management

---

*Generated: May 9, 2026*
