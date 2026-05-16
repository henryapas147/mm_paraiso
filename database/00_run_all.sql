-- ============================================================================
-- RESORT / HOTEL RESERVATION SYSTEM
-- MASTER MIGRATION RUNNER
-- 
-- HOW TO USE:
--   Option A — phpMyAdmin:
--     Import each file one by one in order (01 → 08)
--
--   Option B — MySQL CLI:
--     mysql -u YOUR_USER -p YOUR_DATABASE < 00_run_all.sql
--
--   Option C — MySQL CLI, per file:
--     mysql -u YOUR_USER -p YOUR_DATABASE < 01_users_auth.sql
--     mysql -u YOUR_USER -p YOUR_DATABASE < 02_property_rooms.sql
--     ... and so on
--
-- All files use CREATE TABLE IF NOT EXISTS and INSERT IGNORE
-- so they are safe to re-run without data loss.
-- ============================================================================

-- ============================================================================
-- FILE EXECUTION ORDER
-- ============================================================================

-- Step 1 — Foundation: Roles & Users
SOURCE 01_users_auth.sql;

-- Step 2 — Property, Rooms, Images, Amenities
SOURCE 02_property_rooms.sql;

-- Step 3 — Reservations, Guests, Check-in, Entrance Logs
SOURCE 03_reservations.sql;

-- Step 4 — Payments, Invoices, Discounts, Extra Charges
SOURCE 04_payments_billing.sql;

-- Step 5 — Food & Canteen: Categories, Menu, Orders
SOURCE 05_food_canteen.sql;

-- Step 6 — Full Inventory: Products, UOM, PO, Stock, Sales
SOURCE 06_inventory.sql;

-- Step 7 — Settings, Pricing Rules, Promos, Notifications
SOURCE 07_settings_promos_notifications.sql;

-- Step 8 — Seed Data (real records, safe to re-run)
SOURCE 08_seed_data.sql;

-- Step 9 — RBAC Permissions (seed role permissions)
SOURCE 09_rbac_permissions.sql;

-- Step 10 — PSGC Geocode Tables (Philippine Standard Geographic Code)
SOURCE standardgeocode.sql;

-- Step 11 — Company Information
SOURCE 10_company_information.sql;

-- Step 12 — Add Company Geocode Foreign Key Constraints
SOURCE 11_add_company_geocode_constraints.sql;

-- ============================================================================
-- FINAL VERIFICATION
-- ============================================================================
SELECT 'Resort DB migration complete!' AS status;

SELECT
    TABLE_NAME                         AS `table`,
    TABLE_ROWS                         AS `approx_rows`,
    ROUND(DATA_LENGTH / 1024, 1)       AS `data_kb`,
    TABLE_COMMENT                      AS `description`
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_TYPE = 'BASE TABLE'
ORDER BY TABLE_NAME;
