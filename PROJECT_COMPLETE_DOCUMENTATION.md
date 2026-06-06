# M&M Paraiso Resort Management System - Complete Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [Technology Stack](#technology-stack)
3. [System Architecture](#system-architecture)
4. [Core Modules](#core-modules)
5. [Database Schema](#database-schema)
6. [API Endpoints](#api-endpoints)
7. [Frontend Structure](#frontend-structure)
8. [Integrations](#integrations)
9. [Security Features](#security-features)
10. [Directory Structure](#directory-structure)
11. [Deployment Guide](#deployment-guide)

---

## Project Overview

**M&M Paraiso Resort Management System** is a comprehensive web-based reservation and management platform for resorts and hotels. It provides end-to-end functionality from property management, room reservations, food & canteen services, inventory management, to customer relationship management.

### Key Features
- Multi-property resort management
- Online and walk-in reservation system
- Dynamic pricing with seasonal rates and promotions
- Food ordering (dine-in, room service, takeout)
- Complete inventory and stock management
- Customer portal with booking history
- Role-based access control (RBAC)
- Email notifications via Gmail API
- Facebook/Google OAuth integration
- Real-time availability checking

---

## Technology Stack

### Backend
| Component | Technology | Version |
|-----------|------------|---------|
| Language | PHP | 8.0+ |
| Database | MySQL | 8.0+ (InnoDB) |
| Framework | Custom MVC | - |
| Template Engine | Native PHP | - |
| Mail Service | PHPMailer | 6.9 |
| OAuth | Google API Client | 2.13 |

### Frontend
| Component | Technology | Version |
|-----------|------------|---------|
| CSS Framework | Tailwind CSS | 3.0.19 |
| UI Theme | Argon Dashboard 2 | 1.0.1 |
| Icons | Nucleo Icons, Font Awesome | - |
| Build Tool | PostCSS/CLI | 9.1.0 |
| JS Libraries | Vanilla JS, Perfect Scrollbar | - |

### Infrastructure
| Component | Technology |
|-----------|------------|
| Web Server | Apache (XAMPP) |
| Session Storage | File-based with secure cookies |
| Asset Storage | Local filesystem |
| Email | Gmail API / SMTP |

---

## System Architecture

### MVC Pattern
```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│    View     │◄────┤  Controller │◄────┤    Model    │
│  (Template) │     │  (Logic)    │     │  (Database) │
└─────────────┘     └─────────────┘     └─────────────┘
       │                   │                   │
       └───────────────────┴───────────────────┘
                          │
                    ┌─────────────┐
                    │   Router    │
                    │  (index.php)│
                    └─────────────┘
```

### Request Flow
1. **Entry Point**: `build/index.php` - Router initialization
2. **URL Routing**: `build/core/Router.php` - Route matching
3. **Controller**: `build/controllers/{Controller}.php` - Business logic
4. **API Layer**: `build/api/{entity}.php` - Data operations
5. **Database**: MySQL via PDO
6. **Response**: JSON (API) or HTML (Views)

---

## Core Modules

### 1. Users & Authentication Module
**Files**: `build/controllers/AuthController.php`, `build/api/auth.php`, `build/views/auth/`

| Feature | Description | Status |
|---------|-------------|--------|
| User Roles | 8 roles (super_admin, admin, front_desk, housekeeping, canteen_staff, security, customer, api_client) | ✅ Complete |
| Authentication | Session-based with UUID PK | ✅ Complete |
| OAuth | Google & Facebook login | ✅ Complete |
| RBAC | Role-based permissions | ✅ Complete |
| Password Reset | Email-based reset flow | ✅ Complete |
| Email Verification | Gmail API integration | ✅ Complete |

**Database Tables**:
- `user_roles` - System role definitions
- `user_accounts` - Core credentials with UUID PK
- `user_profiles` - Extended PII data
- `user_sessions` - Multi-device session management
- `audit_logs` - Immutable audit trail
- `login_attempts` - Rate limiting and security monitoring

---

### 2. Properties & Rooms Module
**Files**: `build/controllers/PropertiesController.php`, `build/views/properties-rooms/`

| Feature | Description | Status |
|---------|-------------|--------|
| Property Management | Multi-property support with geocoding | ✅ Complete |
| Room Categories | Standard, deluxe, suite pricing tiers | ✅ Complete |
| Room Inventory | Individual room status tracking | ✅ Complete |
| Amenities | Many-to-many room-amenity relationships | ✅ Complete |
| Room Blocks | Maintenance/out-of-service blocking | ✅ Complete |
| Availability API | Real-time availability checking | ✅ Complete |

**API Endpoints**:
- `GET/POST api/properties.php` - Property CRUD
- `GET/POST api/rooms.php` - Room CRUD
- `GET/POST api/room_categories.php` - Category management
- `GET/POST api/amenities.php` - Amenity management
- `GET/POST api/room_blocks.php` - Room blocking

---

### 3. Reservations & Booking Module
**Files**: `build/controllers/ReservationsController.php`, `build/views/reservations/`, `build/api/reservations.php`

| Feature | Description | Status |
|---------|-------------|--------|
| Online Booking | Public-facing booking form | ✅ Complete |
| Walk-in Reservations | Staff-created reservations | ✅ Complete |
| Guest Management | Multiple guests per reservation | ✅ Complete |
| Check-in/Out | Digital check-in logs | ✅ Complete |
| Entrance Logging | Gate access tracking | ✅ Complete |
| Requirements | Document submission tracking | ✅ Complete |

**Reservation Status Flow**:
```
pending → confirmed → checked_in → checked_out
   ↓         ↓           ↓
cancelled  no_show   (completed)
```

**API Endpoints**:
- `GET/POST api/reservations.php` - Reservation CRUD
- `GET/POST api/reservation_rooms.php` - Room assignments
- `GET/POST api/reservation_guests.php` - Guest management
- `GET/POST api/check_in_logs.php` - Check-in tracking
- `GET/POST api/entrance_logs.php` - Entrance tracking

---

### 4. Payments & Billing Module
**Files**: `build/controllers/PaymentsBillingController.php`, `build/views/payments-billing/`

| Feature | Description | Status |
|---------|-------------|--------|
| Payment Methods | Cash, card, GCash, etc. | ✅ Complete |
| Invoice Generation | Automated invoicing | ✅ Complete |
| Discounts | Promo codes and discount rules | ✅ Complete |
| Extra Charges | Late checkout, damages, etc. | ✅ Complete |
| Billing Summary | Guest folio management | ✅ Complete |

**API Endpoints**:
- `GET/POST api/payment_methods.php`
- `GET/POST api/payments.php`
- `GET/POST api/invoices.php`
- `GET/POST api/discounts.php`
- `GET/POST api/discount_usages.php`
- `GET/POST api/extra_charges.php`
- `GET/POST api/billing.php`

---

### 5. Food & Canteen Module
**Files**: `build/controllers/FoodCanteenController.php`, `build/views/food-canteen/`

| Feature | Description | Status |
|---------|-------------|--------|
| Menu Management | Categories and items with images | ✅ Complete |
| Food Orders | Dine-in, room service, takeout | ✅ Complete |
| Recipe Management | Ingredient tracking | ✅ Complete |
| Cost Tracking | Profit margin analysis | ✅ Complete |
| Kitchen Display | Order status tracking | ✅ Complete |

**Order Types**: `dine_in`, `room_service`, `takeout`

**Order Status Flow**:
```
pending → confirmed → preparing → ready → delivered
```

**API Endpoints**:
- `GET/POST api/food_categories.php`
- `GET/POST api/menu_items.php`
- `GET/POST api/food_orders.php`
- `GET/POST api/recipes.php`

---

### 6. Inventory Management Module
**Files**: `build/controllers/InventoryController.php`, `build/views/inventory/`

| Feature | Description | Status |
|---------|-------------|--------|
| Product Catalog | Hierarchical categories | ✅ Complete |
| Stock Management | Multi-location inventory | ✅ Complete |
| Purchase Orders | Supplier PO management | ✅ Complete |
| Stock Transfers | Inter-location transfers | ✅ Complete |
| UOM Conversions | Unit of measure handling | ✅ Complete |
| POS Integration | Direct inventory sales | ✅ Complete |
| Low Stock Alerts | Automated notifications | ✅ Complete |

**API Endpoints**:
- `GET/POST api/products.php`
- `GET/POST api/inventory_categories.php`
- `GET/POST api/inventory_locations.php`
- `GET/POST api/suppliers.php`
- `GET/POST api/purchase_orders.php`
- `GET/POST api/stock_transactions.php`
- `GET/POST api/stock_transfers.php`
- `GET/POST api/inventory_sales.php`

---

### 7. Settings & Promotions Module
**Files**: `build/controllers/SettingsController.php`, `build/views/settings/`

| Feature | Description | Status |
|---------|-------------|--------|
| System Settings | Global configuration | ✅ Complete |
| Pricing Rules | Dynamic pricing by day/season | ✅ Complete |
| Seasonal Rates | Date-range pricing overrides | ✅ Complete |
| Booking Policies | Cancellation, no-show rules | ✅ Complete |
| Promotions | Marketing campaigns with banners | ✅ Complete |
| Notifications | System and scheduled notifications | ✅ Complete |

**API Endpoints**:
- `GET/POST api/system_settings.php`
- `GET/POST api/pricing_rules.php`
- `GET/POST api/seasonal_rates.php`
- `GET/POST api/booking_policies.php`
- `GET/POST api/promotions.php`
- `GET/POST api/notifications.php`

---

### 8. Customer Management Module
**Files**: `build/controllers/CustomerController.php`, `build/views/customers/`

| Feature | Description | Status |
|---------|-------------|--------|
| Customer Portal | Self-service dashboard | ✅ Complete |
| Booking History | Reservation view/tracking | ✅ Complete |
| Profile Management | Personal information | ✅ Complete |
| Admin Management | Staff customer management | ✅ Complete |

**API Endpoints**:
- `GET/POST api/customers.php`
- `GET/POST api/profile.php`

---

### 9. Reports & Analytics Module
**Files**: `build/controllers/ReportsController.php`, `build/views/reports/`, `build/api/reports.php`

| Feature | Description | Status |
|---------|-------------|--------|
| Revenue Reports | Financial analytics | ✅ Complete |
| Occupancy Reports | Room utilization | ✅ Complete |
| Inventory Reports | Stock analysis | ✅ Complete |
| Customer Analytics | Guest behavior | ✅ Complete |
| Food Sales Reports | Canteen performance | ✅ Complete |

---

### 10. Contact & Inquiry Module
**Files**: `build/controllers/ContactInquiriesController.php`, `build/services/EmailService.php`

| Feature | Description | Status |
|---------|-------------|--------|
| Contact Form | Public inquiry submission | ✅ Complete |
| Email Replies | Gmail API integration | ✅ Complete |
| Inquiry Tracking | Status management | ✅ Complete |
| Poll Monitoring | Gmail inbox polling | ✅ Complete |

---

## Database Schema

### Complete Table List (60+ Tables)

#### Users & Authentication (6 tables)
| Table | Purpose |
|-------|---------|
| `user_roles` | Role definitions |
| `user_accounts` | User credentials |
| `user_profiles` | Extended user data |
| `user_sessions` | Session tracking |
| `audit_logs` | Activity logging |
| `login_attempts` | Security monitoring |

#### Properties & Rooms (6 tables)
| Table | Purpose |
|-------|---------|
| `properties` | Property master |
| `property_types` | Property type categories |
| `room_categories` | Room type definitions |
| `rooms` | Room inventory |
| `amenities` | Amenity master |
| `room_amenities` | Room-amenity links |
| `room_blocks` | Room blocking |

#### Reservations (8 tables)
| Table | Purpose |
|-------|---------|
| `reservations` | Master reservation |
| `reservation_rooms` | Room assignments |
| `reservation_guests` | Guest manifest |
| `reservation_requirement_types` | Required documents |
| `reservation_requirements` | Submitted docs |
| `check_in_logs` | Check-in tracking |
| `entrance_logs` | Gate access |
| `reservation_pricing_breakdown` | Price audit trail |
| `reservation_promotions` | Promo tracking |

#### Payments & Billing (7 tables)
| Table | Purpose |
|-------|---------|
| `payment_methods` | Payment types |
| `payments` | Transactions |
| `invoices` | Guest invoices |
| `invoice_line_items` | Charge details |
| `discounts` | Discount rules |
| `discount_usages` | Applied discounts |
| `extra_charges` | Additional fees |
| `billing` | Folio summary |

#### Food & Canteen (6 tables)
| Table | Purpose |
|-------|---------|
| `food_categories` | Menu categories |
| `menu_items` | Food items |
| `menu_item_images` | Item photos |
| `food_orders` | Order master |
| `food_order_items` | Order line items |
| `food_order_status_history` | Status changes |
| `recipes` | Ingredient tracking |

#### Inventory (11 tables)
| Table | Purpose |
|-------|---------|
| `inventory_categories` | Product categories |
| `units_of_measure` | UOM master |
| `inventory_locations` | Storage locations |
| `suppliers` | Vendor master |
| `products` | Product catalog |
| `product_stock` | Stock levels |
| `purchase_orders` | PO master |
| `purchase_order_items` | PO line items |
| `stock_transactions` | Stock movements |
| `stock_transfers` | Inter-location |
| `inventory_sales` | Direct sales |
| `product_uom_conversions` | Unit conversions |

#### Settings & Promotions (9 tables)
| Table | Purpose |
|-------|---------|
| `system_settings` | Global config |
| `pricing_rules` | Dynamic pricing |
| `seasonal_rates` | Seasonal overrides |
| `booking_policies` | Policy rules |
| `promotions` | Marketing campaigns |
| `promotion_images` | Banner images |
| `notifications` | System notifications |
| `notification_recipients` | User preferences |
| `scheduled_notifications` | Automated sends |

#### RBAC & Permissions (2 tables)
| Table | Purpose |
|-------|---------|
| `permissions` | Permission definitions |
| `role_permissions` | Role-permission links |

#### Company & Contact (2 tables)
| Table | Purpose |
|-------|---------|
| `company_information` | Business details |
| `contact_inquiries` | Public inquiries |

---

## API Endpoints

### Complete API List (65+ Endpoints)

#### Authentication
| Endpoint | Method | Action |
|----------|--------|--------|
| `api/auth.php?action=login` | POST | User login |
| `api/auth.php?action=logout` | POST | User logout |
| `api/auth.php?action=register` | POST | User registration |
| `api/auth.php?action=verify` | GET | Email verification |
| `api/auth.php?action=reset_password` | POST | Password reset |

#### Properties & Rooms
| Endpoint | Method | Action |
|----------|--------|--------|
| `api/properties.php?action=list` | GET | List properties |
| `api/properties.php?action=single&id={uuid}` | GET | Get property |
| `api/properties.php?action=create` | POST | Create property |
| `api/properties.php?action=update` | POST | Update property |
| `api/rooms.php?action=list` | GET | List rooms |
| `api/rooms.php?action=check_availability` | GET | Check availability |
| `api/room_categories.php?action=list` | GET | List categories |

#### Reservations
| Endpoint | Method | Action |
|----------|--------|--------|
| `api/reservations.php?action=list` | GET | List reservations |
| `api/reservations.php?action=single&id={uuid}` | GET | Get reservation |
| `api/reservations.php?action=create` | POST | Create reservation |
| `api/reservations.php?action=create_walkin` | POST | Walk-in booking |
| `api/reservations.php?action=update` | POST | Update reservation |
| `api/reservations.php?action=cancel` | POST | Cancel reservation |
| `api/reservations.php?action=check_in` | POST | Check-in guest |
| `api/reservations.php?action=check_out` | POST | Check-out guest |

#### Payments
| Endpoint | Method | Action |
|----------|--------|--------|
| `api/payments.php?action=list` | GET | List payments |
| `api/payments.php?action=create` | POST | Record payment |
| `api/invoices.php?action=list` | GET | List invoices |
| `api/invoices.php?action=generate` | POST | Generate invoice |

#### Food & Canteen
| Endpoint | Method | Action |
|----------|--------|--------|
| `api/food_orders.php?action=list` | GET | List orders |
| `api/food_orders.php?action=create` | POST | Create order |
| `api/food_orders.php?action=update_status` | POST | Update status |
| `api/menu_items.php?action=list` | GET | List menu items |

#### Inventory
| Endpoint | Method | Action |
|----------|--------|--------|
| `api/products.php?action=list` | GET | List products |
| `api/products.php?action=single&id={uuid}` | GET | Get product |
| `api/stock_transactions.php?action=list` | GET | List transactions |
| `api/stock_transactions.php?action=add` | POST | Add stock |
| `api/purchase_orders.php?action=list` | GET | List POs |
| `api/purchase_orders.php?action=create` | POST | Create PO |

#### Reports
| Endpoint | Method | Action |
|----------|--------|--------|
| `api/reports.php?action=revenue` | GET | Revenue report |
| `api/reports.php?action=occupancy` | GET | Occupancy report |
| `api/reports.php?action=inventory` | GET | Inventory report |
| `api/reports.php?action=customers` | GET | Customer analytics |

---

## Frontend Structure

### Admin Dashboard (build/views/)
```
build/views/
├── auth/                    # Authentication pages
│   ├── sign-in.php
│   ├── sign-up.php
│   ├── forgot-password.php
│   ├── reset-password.php
│   ├── verify-email.php
│   └── profile.php
├── dashboard.php            # Main dashboard
├── properties-rooms/        # Property management
│   ├── properties.php
│   ├── rooms.php
│   ├── room_categories.php
│   ├── amenities.php
│   └── room_blocks.php
├── reservations/            # Booking management
│   ├── reservations.php
│   ├── reservation_rooms.php
│   ├── reservation_guests.php
│   ├── walk_in_guests.php
│   ├── check_in_logs.php
│   ├── entrance_logs.php
│   └── pricing_breakdown.php
├── payments-billing/        # Financial management
│   ├── payment_methods.php
│   ├── payments.php
│   ├── invoices.php
│   ├── discounts.php
│   └── billing.php
├── food-canteen/           # Food service
│   ├── food_categories.php
│   ├── menu_items.php
│   ├── food_orders.php
│   └── recipes.php
├── inventory/              # Stock management
│   ├── products.php
│   ├── inventory_categories.php
│   ├── inventory_locations.php
│   ├── suppliers.php
│   ├── purchase_orders.php
│   ├── stock_transactions.php
│   └── inventory_sales.php
├── settings/               # System configuration
│   ├── system_settings.php
│   ├── pricing_rules.php
│   ├── seasonal_rates.php
│   ├── booking_policies.php
│   ├── promotions.php
│   ├── notifications.php
│   └── company.php
├── customers/              # Customer management
│   └── customers_list.php
├── reports/                # Analytics
│   ├── revenue.php
│   ├── occupancy.php
│   ├── inventory.php
│   └── customers.php
└── users/                  # User management
    └── users_list.php
```

### Public Pages (pages/)
```
pages/
├── index.php               # Public landing
├── properties.php          # Property listings
├── property-details.php    # Property detail
├── rooms.php               # Room listings
├── room-details.php        # Room detail
├── food-menu.php           # Public menu
├── schedule-visit.php      # Booking form
├── contact.php             # Contact form
└── api/                    # Public APIs
    ├── properties.php
    ├── rooms.php
    ├── check-availability.php
    ├── calculate-price.php
    ├── food-menu.php
    └── contact_inquiries.php
```

### Shared Components (structure/)
```
structure/
├── head.php                # HTML head template
├── navbar.php              # Top navigation
├── sidebar.php             # Side navigation (admin)
├── customer_sidebar.php    # Customer portal nav
├── footer.php              # Page footer
├── script.php              # JS includes
├── customizer.php          # Theme customizer
└── page_template.php       # Base template
```

---

## Integrations

### 1. Google OAuth / Gmail API
**Files**: `oauth_helper.php`, `build/google-callback.php`, `build/services/EmailService.php`

| Feature | Implementation |
|---------|----------------|
| Sign-In | Google OAuth 2.0 flow |
| Email Sending | Gmail API via PHPMailer |
| Email Polling | GmailPollerService for replies |
| Token Management | Refresh token storage |

**Configuration**: `config/gmail-credentials.json`

### 2. Facebook OAuth
**Files**: `build/facebook-callback.php`

| Feature | Implementation |
|---------|----------------|
| Sign-In | Facebook Login SDK |
| Permissions | email, public_profile |
| Callback | `facebook-callback.php` |

**Configuration**: `config/facebook-credentials.json`

### 3. Email Services
**Files**: `build/services/EmailService.php`, `build/services/GmailPollerService.php`

| Feature | Implementation |
|---------|----------------|
| SMTP | Gmail SMTP (587/TLS) |
| Inquiry Replies | HTML templates with tracking |
| Notifications | Automated email sends |
| Polling | IMAP-based reply detection |

### 4. Maps/Geocoding
**Files**: `build/api/geocode.php`

| Feature | Implementation |
|---------|----------------|
| Property Locations | OpenStreetMap Nominatim |
| Coordinates | Latitude/longitude storage |
| Distance | Haversine formula for proximity |

---

## Security Features

### Authentication Security
| Feature | Implementation |
|---------|----------------|
| Password Hashing | bcrypt (password_hash) |
| Session Security | HttpOnly, Secure, SameSite=Strict |
| CSRF Protection | Token validation |
| Rate Limiting | Login attempt tracking |
| Account Lockout | After 5 failed attempts |

### Data Security
| Feature | Implementation |
|---------|----------------|
| SQL Injection | PDO prepared statements |
| XSS Prevention | Output encoding |
| Input Validation | Server-side validation |
| File Upload | Type/size validation |
| API Security | Session-based auth |

### Infrastructure Security
| Feature | Implementation |
|---------|----------------|
| HTTPS | Forced on production |
| Security Headers | CSP, HSTS, X-Frame-Options |
| Error Handling | Generic error messages |
| Logging | Error and audit logs |
| Credentials | Gitignored config files |

---

## Directory Structure

```
admin_booking/
├── build/                          # Web root (admin dashboard)
│   ├── api/                        # 65+ API endpoints
│   ├── assets/
│   │   ├── css/                    # Tailwind + custom styles
│   │   ├── js/                     # Custom plugins
│   │   └── img/                    # Logos, avatars
│   ├── config/
│   │   └── db.php                  # Database connection
│   ├── controllers/                # 15 MVC controllers
│   ├── core/                       # Framework core
│   │   ├── Router.php              # URL routing
│   │   ├── Autoloader.php          # PSR-4 autoloader
│   │   ├── Controller.php          # Base controller
│   │   ├── Model.php               # Base model
│   │   └── RBAC.php                # Role-based access
│   ├── cron/                       # Scheduled tasks
│   ├── services/                   # Business services
│   │   ├── EmailService.php        # Email handling
│   │   ├── GmailPollerService.php  # Email polling
│   │   └── NotificationService.php
│   ├── views/                      # 50+ view templates
│   ├── uploads/                    # File uploads
│   ├── google-callback.php         # Google OAuth
│   ├── facebook-callback.php       # Facebook OAuth
│   ├── privacy-policy.php          # Privacy page
│   ├── data-deletion.php           # GDPR deletion
│   └── index.php                   # Application entry
│
├── pages/                          # Public-facing site
│   ├── api/                        # Public APIs
│   ├── assets/                     # Public assets
│   ├── structure/                # Public templates
│   ├── index.php                   # Landing page
│   ├── properties.php              # Property list
│   ├── room-details.php            # Room booking
│   ├── food-menu.php               # Public menu
│   └── schedule-visit.php          # Booking form
│
├── config/                         # Configuration (gitignored)
│   ├── db.php                      # Main config
│   ├── credentials.php             # Secrets
│   ├── auth.php                    # Auth settings
│   ├── gmail-credentials.json      # Gmail OAuth
│   └── facebook-credentials.json   # Facebook OAuth
│
├── database/                       # Database migrations
│   ├── 00_run_all.sql              # Master setup
│   ├── 01_users_auth.sql
│   ├── 02_property_rooms.sql
│   ├── 03_reservations.sql
│   ├── 04_payments_billing.sql
│   ├── 05_food_canteen.sql
│   ├── 06_inventory.sql
│   ├── 07_settings_promos_notifications.sql
│   ├── 08_seed_data.sql
│   └── migrations/                 # Additional migrations
│
├── structure/                      # Shared components
│   ├── sidebar.php
│   ├── navbar.php
│   ├── footer.php
│   └── head.php
│
├── src/                            # Source assets
│   └── styles.css                  # Tailwind input
│
├── logs/                           # Application logs
│   ├── php_errors.log
│   └── facebook-oauth-error.log
│
├── uploads/                        # File storage
├── vendor/                         # Composer dependencies
├── node_modules/                   # NPM dependencies
├── .htaccess                       # URL rewriting
├── composer.json                   # PHP dependencies
├── package.json                    # JS dependencies
├── tailwind.config.js              # Tailwind config
└── postcss.config.js               # PostCSS config
```

---

## Deployment Guide

### Requirements
- PHP 8.0+
- MySQL 8.0+
- Apache/Nginx
- Composer
- Node.js (for asset building)

### Installation Steps

1. **Clone/Extract Files**
```bash
# Extract to web root
# Point document root to: admin_booking/build/
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Configure Database**
```bash
# Import database schema
mysql -u root -p < database/00_run_all.sql
mysql -u root -p < database/08_seed_data.sql
```

4. **Configure Environment**
```bash
# Copy and edit credentials
cp config/credentials.example.php config/credentials.php
# Edit: config/credentials.php, config/gmail-credentials.json
```

5. **Build Assets**
```bash
npm run build
```

6. **Set Permissions**
```bash
chmod 755 uploads/
chmod 644 config/credentials.php
```

### Default Login
- **URL**: `http://localhost/admin_booking/build/sign-in`
- **Email**: `superadmin@resortms.com`
- **Password**: `Admin@1234`

### Environment Variables (CLI/Cron)
```bash
# Local DB
LOCAL_DB_HOST=localhost
LOCAL_DB_NAME=booking
LOCAL_DB_USER=root
LOCAL_DB_PASS=

# Online DB (production)
ONLINE_DB_HOST=your-host
ONLINE_DB_NAME=your-db
ONLINE_DB_USER=your-user
ONLINE_DB_PASS=your-pass

# Gmail
GMAIL_USER=your-email@gmail.com
GMAIL_APP_PASSWORD=your-app-password
```

---

## Maintenance & Support

### Log Files
- `logs/php_errors.log` - PHP errors
- `logs/facebook-oauth-error.log` - OAuth issues

### Cron Jobs
- Email polling: `build/cron/poll-emails.php`
- Notification sending: `build/cron/send-notifications.php`

### Backup Strategy
1. Database: Daily mysqldump
2. Uploads: Weekly file backup
3. Configuration: Version controlled (secrets excluded)

---

*Generated: June 2026*
*Version: 1.0.1*
*Maintainer: M&M Paraiso Development Team*
