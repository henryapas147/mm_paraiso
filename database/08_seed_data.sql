-- ============================================================================
-- RESORT / HOTEL RESERVATION SYSTEM
-- Module 08: Seed Data — Real Insertable Records
-- Run this AFTER all schema files (01–07) have been executed.
-- Safe to re-run: uses INSERT IGNORE throughout.
-- ============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================================
-- 01. USER ROLES
-- ============================================================================
INSERT IGNORE INTO user_roles (role_id, role_code, role_label, is_system_role, is_active) VALUES
(1, 'super_admin',   'Super Administrator', 1, 1),
(2, 'admin',         'Administrator',       1, 1),
(3, 'front_desk',    'Front Desk Staff',    1, 1),
(4, 'housekeeping',  'Housekeeping Staff',  1, 1),
(5, 'canteen_staff', 'Canteen Staff',       1, 1),
(6, 'security',      'Security Staff',      1, 1),
(7, 'customer',      'Customer / Guest',    1, 1),
(8, 'api_client',    'Mobile App / API',    1, 1);

-- ============================================================================
-- 02. USER ACCOUNTS
-- Passwords are bcrypt hash of 'Admin@1234' (change on first login)
-- UUID values are fixed for seeding references
-- ============================================================================
INSERT IGNORE INTO user_accounts
    (user_id, role_id, email, phone_number, password_hash, account_status,
     is_email_verified, is_phone_verified, email_verified_at, created_at)
VALUES
-- Super Admin
('ua-00000001-0000-0000-0000-000000000001', 1,
 'superadmin@resortms.com', '+639171234561',
 '$2y$10$W93.9GXTzg7f5u26XU0w0.K0eOJBZePAvmp.cRC4.8lH6MkbAFjBO',
 'active', 1, 1, NOW(), NOW()),
-- Admin
('ua-00000002-0000-0000-0000-000000000002', 2,
 'admin@resortms.com', '+639171234562',
 '$2y$10$W93.9GXTzg7f5u26XU0w0.K0eOJBZePAvmp.cRC4.8lH6MkbAFjBO',
 'active', 1, 1, NOW(), NOW()),
-- Front Desk
('ua-00000003-0000-0000-0000-000000000003', 3,
 'frontdesk@resortms.com', '+639171234563',
 '$2y$10$W93.9GXTzg7f5u26XU0w0.K0eOJBZePAvmp.cRC4.8lH6MkbAFjBO',
 'active', 1, 0, NOW(), NOW()),
-- Housekeeping
('ua-00000004-0000-0000-0000-000000000004', 4,
 'housekeeping@resortms.com', '+639171234564',
 '$2y$10$W93.9GXTzg7f5u26XU0w0.K0eOJBZePAvmp.cRC4.8lH6MkbAFjBO',
 'active', 1, 0, NOW(), NOW()),
-- Canteen Staff
('ua-00000005-0000-0000-0000-000000000005', 5,
 'canteen@resortms.com', '+639171234565',
 '$2y$10$W93.9GXTzg7f5u26XU0w0.K0eOJBZePAvmp.cRC4.8lH6MkbAFjBO',
 'active', 1, 0, NOW(), NOW()),
-- Security
('ua-00000006-0000-0000-0000-000000000006', 6,
 'security@resortms.com', '+639171234566',
 '$2y$10$W93.9GXTzg7f5u26XU0w0.K0eOJBZePAvmp.cRC4.8lH6MkbAFjBO',
 'active', 1, 0, NOW(), NOW()),
-- Sample Customer 1
('ua-00000007-0000-0000-0000-000000000007', 7,
 'juan.delacruz@email.com', '+639181234567',
 '$2y$10$W93.9GXTzg7f5u26XU0w0.K0eOJBZePAvmp.cRC4.8lH6MkbAFjBO',
 'active', 1, 1, NOW(), NOW()),
-- Sample Customer 2
('ua-00000008-0000-0000-0000-000000000008', 7,
 'maria.santos@email.com', '+639181234568',
 '$2y$10$W93.9GXTzg7f5u26XU0w0.K0eOJBZePAvmp.cRC4.8lH6MkbAFjBO',
 'active', 1, 0, NOW(), NOW());

-- ============================================================================
-- 03. USER PROFILES
-- ============================================================================
INSERT IGNORE INTO user_profiles
    (profile_id, user_id, first_name, last_name, city, province, country, created_at)
VALUES
('up-00000001-0000-0000-0000-000000000001', 'ua-00000001-0000-0000-0000-000000000001', 'System',    'Administrator', 'Davao City', 'Davao del Sur', 'Philippines', NOW()),
('up-00000002-0000-0000-0000-000000000002', 'ua-00000002-0000-0000-0000-000000000002', 'Resort',    'Admin',         'Davao City', 'Davao del Sur', 'Philippines', NOW()),
('up-00000003-0000-0000-0000-000000000003', 'ua-00000003-0000-0000-0000-000000000003', 'Ana',       'Reyes',         'Davao City', 'Davao del Sur', 'Philippines', NOW()),
('up-00000004-0000-0000-0000-000000000004', 'ua-00000004-0000-0000-0000-000000000004', 'Pedro',     'Cruz',          'Davao City', 'Davao del Sur', 'Philippines', NOW()),
('up-00000005-0000-0000-0000-000000000005', 'ua-00000005-0000-0000-0000-000000000005', 'Liza',      'Mendoza',       'Davao City', 'Davao del Sur', 'Philippines', NOW()),
('up-00000006-0000-0000-0000-000000000006', 'ua-00000006-0000-0000-0000-000000000006', 'Ramon',     'Torres',        'Davao City', 'Davao del Sur', 'Philippines', NOW()),
('up-00000007-0000-0000-0000-000000000007', 'ua-00000007-0000-0000-0000-000000000007', 'Juan',      'dela Cruz',     'Davao City', 'Davao del Sur', 'Philippines', NOW()),
('up-00000008-0000-0000-0000-000000000008', 'ua-00000008-0000-0000-0000-000000000008', 'Maria',     'Santos',        'General Santos City', 'South Cotabato', 'Philippines', NOW());

-- ============================================================================
-- 04. PROPERTY
-- ============================================================================
INSERT IGNORE INTO properties
    (property_id, property_code, property_name, property_type,
     tagline, description,
     address_line1, city, province, country,
     phone_primary, email,
     check_in_time, check_out_time, earliest_check_in,
     tax_rate_percent, is_active, created_by)
VALUES
('prop-0001-0000-0000-0000-000000000001',
 'RST-001',
 'Balay sa Dagat Resort',
 'beach_resort',
 'Your Home by the Sea',
 'A tranquil beachfront resort in Southern Mindanao offering private cottages, family rooms, and premium suites with ocean views, freshwater pools, and a full-service canteen.',
 'Brgy. Magallanes, Samal Island',
 'Island Garden City of Samal',
 'Davao del Norte',
 'Philippines',
 '+63822123456',
 'info@balaydagat.com',
 '14:00:00', '12:00:00', '08:00:00',
 12.00, 1,
 'ua-00000001-0000-0000-0000-000000000001');

-- ============================================================================
-- 05. ROOM CATEGORIES
-- ============================================================================
INSERT IGNORE INTO room_categories
    (category_id, property_id, category_code, category_name, description,
     base_price_per_night, base_price_per_hour, base_price_day_use,
     minimum_hours,
     max_adult_capacity, max_child_capacity, max_total_capacity,
     extra_adult_fee, extra_child_fee,
     floor_area_sqm, bed_configuration, view_type,
     allows_hourly, allows_overnight, allows_day_use,
     sort_order, is_active)
VALUES
('cat-0001-0000-0000-0000-000000000001',
 'prop-0001-0000-0000-0000-000000000001',
 'STD', 'Standard Room',
 'Comfortable air-conditioned room with double bed, private bathroom, cable TV, and basic amenities.',
 1500.00, 250.00, 800.00, 2, 2, 1, 3,
 300.00, 200.00, 20.00, '1 Double Bed', 'garden', 1, 1, 1, 1, 1),

('cat-0002-0000-0000-0000-000000000002',
 'prop-0001-0000-0000-0000-000000000001',
 'DLX', 'Deluxe Room',
 'Spacious room with king-size bed, ocean view balcony, mini-ref, and premium toiletries.',
 2500.00, 400.00, 1200.00, 2, 2, 2, 4,
 350.00, 200.00, 30.00, '1 King Bed', 'ocean', 1, 1, 1, 2, 1),

('cat-0003-0000-0000-0000-000000000003',
 'prop-0001-0000-0000-0000-000000000001',
 'FAM', 'Family Room',
 'Large room ideal for families with 1 queen bed and 2 single beds, sala area, private bathroom.',
 3500.00, 550.00, 1800.00, 2, 4, 3, 6,
 300.00, 150.00, 45.00, '1 Queen Bed + 2 Single Beds', 'garden', 1, 1, 1, 3, 1),

('cat-0004-0000-0000-0000-000000000004',
 'prop-0001-0000-0000-0000-000000000001',
 'COT', 'Beach Cottage',
 'Private native-style cottage steps from the beach. Perfect for groups with outdoor area and hammocks.',
 4500.00, 700.00, 2500.00, 3, 4, 4, 8,
 400.00, 200.00, 55.00, '2 Double Beds', 'ocean', 1, 1, 1, 4, 1),

('cat-0005-0000-0000-0000-000000000005',
 'prop-0001-0000-0000-0000-000000000001',
 'STE', 'Premier Suite',
 'Luxury suite with separate living room, king bed, jacuzzi, full kitchen, and panoramic ocean view.',
 8000.00, 1200.00, 4000.00, 3, 2, 2, 4,
 500.00, 300.00, 80.00, '1 King Bed + Sofa Bed', 'ocean', 0, 1, 0, 5, 1);

-- ============================================================================
-- 06. ROOMS
-- ============================================================================
INSERT IGNORE INTO rooms
    (room_id, property_id, category_id, room_number, room_name,
     floor_number, building_block, room_status, is_active)
VALUES
-- Standard Rooms
('room-0001-0000-0000-0000-000000000001', 'prop-0001-0000-0000-0000-000000000001', 'cat-0001-0000-0000-0000-000000000001', '101', 'Room 101', 1, 'Main Building', 'available', 1),
('room-0002-0000-0000-0000-000000000002', 'prop-0001-0000-0000-0000-000000000001', 'cat-0001-0000-0000-0000-000000000001', '102', 'Room 102', 1, 'Main Building', 'available', 1),
('room-0003-0000-0000-0000-000000000003', 'prop-0001-0000-0000-0000-000000000001', 'cat-0001-0000-0000-0000-000000000001', '103', 'Room 103', 1, 'Main Building', 'available', 1),
-- Deluxe Rooms
('room-0004-0000-0000-0000-000000000004', 'prop-0001-0000-0000-0000-000000000001', 'cat-0002-0000-0000-0000-000000000002', '201', 'Sunrise Room', 2, 'Main Building', 'available', 1),
('room-0005-0000-0000-0000-000000000005', 'prop-0001-0000-0000-0000-000000000001', 'cat-0002-0000-0000-0000-000000000002', '202', 'Sunset Room',  2, 'Main Building', 'available', 1),
-- Family Rooms
('room-0006-0000-0000-0000-000000000006', 'prop-0001-0000-0000-0000-000000000001', 'cat-0003-0000-0000-0000-000000000003', '301', 'Family Suite A', 3, 'Main Building', 'available', 1),
('room-0007-0000-0000-0000-000000000007', 'prop-0001-0000-0000-0000-000000000001', 'cat-0003-0000-0000-0000-000000000003', '302', 'Family Suite B', 3, 'Main Building', 'available', 1),
-- Beach Cottages
('room-0008-0000-0000-0000-000000000008', 'prop-0001-0000-0000-0000-000000000001', 'cat-0004-0000-0000-0000-000000000004', 'COT-01', 'Narra Cottage',    1, 'Beachfront', 'available', 1),
('room-0009-0000-0000-0000-000000000009', 'prop-0001-0000-0000-0000-000000000001', 'cat-0004-0000-0000-0000-000000000004', 'COT-02', 'Molave Cottage',   1, 'Beachfront', 'available', 1),
('room-0010-0000-0000-0000-000000000010', 'prop-0001-0000-0000-0000-000000000001', 'cat-0004-0000-0000-0000-000000000004', 'COT-03', 'Yakal Cottage',    1, 'Beachfront', 'maintenance', 1),
-- Premier Suite
('room-0011-0000-0000-0000-000000000011', 'prop-0001-0000-0000-0000-000000000001', 'cat-0005-0000-0000-0000-000000000005', 'STE-01', 'Pearl Suite',      1, 'Villa Wing', 'available', 1);

-- ============================================================================
-- 07. AMENITIES
-- ============================================================================
INSERT IGNORE INTO amenities (amenity_id, amenity_name, icon_code, category, sort_order) VALUES
(1,  'Air Conditioning',    'ac_unit',          'comfort',        1),
(2,  'Electric Fan',        'air',              'comfort',        2),
(3,  'Cable TV',            'tv',               'entertainment',  3),
(4,  'Smart TV',            'smart_display',    'entertainment',  4),
(5,  'WiFi',                'wifi',             'technology',     5),
(6,  'Mini Refrigerator',   'kitchen',          'comfort',        6),
(7,  'Mini Bar',            'liquor',           'comfort',        7),
(8,  'Hot & Cold Shower',   'shower',           'bathroom',       8),
(9,  'Bathtub',             'bathtub',          'bathroom',       9),
(10, 'Jacuzzi',             'hot_tub',          'bathroom',       10),
(11, 'Balcony',             'balcony',          'outdoor',        11),
(12, 'Ocean View',          'waves',            'outdoor',        12),
(13, 'Private Pool',        'pool',             'outdoor',        13),
(14, 'Hammock',             'nature',           'outdoor',        14),
(15, 'Hair Dryer',          'dry',              'bathroom',       15),
(16, 'In-Room Safe',        'lock',             'safety',         16),
(17, 'Toiletries Kit',      'soap',             'bathroom',       17),
(18, 'Kitchen Set',         'countertops',      'kitchen',        18),
(19, 'Microwave',           'microwave',        'kitchen',        19),
(20, 'Coffee Maker',        'coffee',           'kitchen',        20);

-- ============================================================================
-- 08. ROOM AMENITIES (per category)
-- ============================================================================
INSERT IGNORE INTO room_amenities (category_id, room_id, amenity_id) VALUES
-- Standard Room amenities
('cat-0001-0000-0000-0000-000000000001', NULL,  1),  -- AC
('cat-0001-0000-0000-0000-000000000001', NULL,  3),  -- Cable TV
('cat-0001-0000-0000-0000-000000000001', NULL,  5),  -- WiFi
('cat-0001-0000-0000-0000-000000000001', NULL,  8),  -- Hot Cold Shower
('cat-0001-0000-0000-0000-000000000001', NULL, 17),  -- Toiletries
-- Deluxe Room amenities
('cat-0002-0000-0000-0000-000000000002', NULL,  1),
('cat-0002-0000-0000-0000-000000000002', NULL,  4),  -- Smart TV
('cat-0002-0000-0000-0000-000000000002', NULL,  5),
('cat-0002-0000-0000-0000-000000000002', NULL,  6),  -- Mini ref
('cat-0002-0000-0000-0000-000000000002', NULL,  8),
('cat-0002-0000-0000-0000-000000000002', NULL, 11),  -- Balcony
('cat-0002-0000-0000-0000-000000000002', NULL, 12),  -- Ocean view
('cat-0002-0000-0000-0000-000000000002', NULL, 17),
-- Family Room
('cat-0003-0000-0000-0000-000000000003', NULL,  1),
('cat-0003-0000-0000-0000-000000000003', NULL,  4),
('cat-0003-0000-0000-0000-000000000003', NULL,  5),
('cat-0003-0000-0000-0000-000000000003', NULL,  6),
('cat-0003-0000-0000-0000-000000000003', NULL,  8),
('cat-0003-0000-0000-0000-000000000003', NULL, 17),
-- Beach Cottage
('cat-0004-0000-0000-0000-000000000004', NULL,  1),
('cat-0004-0000-0000-0000-000000000004', NULL,  5),
('cat-0004-0000-0000-0000-000000000004', NULL,  8),
('cat-0004-0000-0000-0000-000000000004', NULL, 12),
('cat-0004-0000-0000-0000-000000000004', NULL, 14),  -- Hammock
('cat-0004-0000-0000-0000-000000000004', NULL, 17),
-- Premier Suite
('cat-0005-0000-0000-0000-000000000005', NULL,  1),
('cat-0005-0000-0000-0000-000000000005', NULL,  4),
('cat-0005-0000-0000-0000-000000000005', NULL,  5),
('cat-0005-0000-0000-0000-000000000005', NULL,  7),  -- Mini bar
('cat-0005-0000-0000-0000-000000000005', NULL, 10),  -- Jacuzzi
('cat-0005-0000-0000-0000-000000000005', NULL, 11),
('cat-0005-0000-0000-0000-000000000005', NULL, 12),
('cat-0005-0000-0000-0000-000000000005', NULL, 16),  -- Safe
('cat-0005-0000-0000-0000-000000000005', NULL, 18),  -- Kitchen
('cat-0005-0000-0000-0000-000000000005', NULL, 20);  -- Coffee maker

-- ============================================================================
-- 09. PAYMENT METHODS
-- ============================================================================
INSERT IGNORE INTO payment_methods (method_id, method_code, method_label, method_type, is_active, sort_order) VALUES
(1,  'cash',           'Cash',                 'cash',           1, 1),
(2,  'gcash',          'GCash',                'digital_wallet', 1, 2),
(3,  'maya',           'Maya (PayMaya)',        'digital_wallet', 1, 3),
(4,  'bank_bdo',       'BDO Bank Transfer',    'bank_transfer',  1, 4),
(5,  'bank_bpi',       'BPI Bank Transfer',    'bank_transfer',  1, 5),
(6,  'bank_metrobank', 'Metrobank Transfer',   'bank_transfer',  1, 6),
(7,  'credit_card',    'Credit Card',          'card',           1, 7),
(8,  'debit_card',     'Debit Card',           'card',           1, 8),
(9,  'check',          'Manager\'s Check',     'check',          1, 9),
(10, 'shopeepay',      'ShopeePay',            'digital_wallet', 1, 10);

-- ============================================================================
-- 10. UNITS OF MEASURE
-- ============================================================================
INSERT IGNORE INTO units_of_measure (uom_id, uom_code, uom_name, uom_type) VALUES
(1,  'pcs',   'Piece',          'count'),
(2,  'box',   'Box',            'count'),
(3,  'pack',  'Pack',           'count'),
(4,  'doz',   'Dozen',          'count'),
(5,  'set',   'Set',            'count'),
(6,  'pair',  'Pair',           'count'),
(7,  'roll',  'Roll',           'count'),
(8,  'sack',  'Sack',           'count'),
(9,  'ctn',   'Carton',         'count'),
(10, 'bot',   'Bottle',         'count'),
(11, 'can',   'Can',            'count'),
(12, 'kg',    'Kilogram',       'weight'),
(13, 'g',     'Gram',           'weight'),
(14, 'lbs',   'Pound',          'weight'),
(15, 'l',     'Liter',          'volume'),
(16, 'ml',    'Milliliter',     'volume'),
(17, 'gal',   'Gallon',         'volume'),
(18, 'm',     'Meter',          'length'),
(19, 'sqm',   'Square Meter',   'area');

-- ============================================================================
-- 11. INVENTORY CATEGORIES
-- ============================================================================
INSERT IGNORE INTO inventory_categories (category_id, parent_id, category_code, category_name, sort_order) VALUES
-- Top-level categories
(1,  NULL, 'FB',       'Food & Beverage',       1),
(2,  NULL, 'HKPG',     'Housekeeping Supplies', 2),
(3,  NULL, 'AMTY',     'Room Amenities',        3),
(4,  NULL, 'MAINT',    'Maintenance Supplies',  4),
(5,  NULL, 'OFFICE',   'Office Supplies',       5),
(6,  NULL, 'EQUIP',    'Equipment',             6),
-- Food & Beverage sub-categories
(7,  1, 'FB-FOOD',     'Food Items',            1),
(8,  1, 'FB-BEV',      'Beverages',             2),
(9,  1, 'FB-CONDMT',   'Condiments & Spices',   3),
-- Housekeeping sub-categories
(10, 2, 'HKPG-CHEM',   'Cleaning Chemicals',    1),
(11, 2, 'HKPG-TOOL',   'Cleaning Tools',        2),
(12, 2, 'HKPG-LINEN',  'Linens & Towels',       3),
-- Amenities sub-categories
(13, 3, 'AMTY-BATH',   'Bath Toiletries',       1),
(14, 3, 'AMTY-ROOM',   'In-Room Supplies',      2);

-- ============================================================================
-- 12. INVENTORY LOCATIONS
-- ============================================================================
INSERT IGNORE INTO inventory_locations (location_id, property_id, location_name, location_type) VALUES
(1, 'prop-0001-0000-0000-0000-000000000001', 'Main Stockroom',       'main_warehouse'),
(2, 'prop-0001-0000-0000-0000-000000000001', 'Kitchen Pantry',       'kitchen'),
(3, 'prop-0001-0000-0000-0000-000000000001', 'Bar & Beverage Area',  'bar'),
(4, 'prop-0001-0000-0000-0000-000000000001', 'Housekeeping Closet',  'housekeeping'),
(5, 'prop-0001-0000-0000-0000-000000000001', 'Front Desk Storage',   'front_desk');

-- ============================================================================
-- 13. SUPPLIERS
-- ============================================================================
INSERT IGNORE INTO suppliers
    (supplier_id, supplier_code, company_name, contact_person,
     email, phone_primary, city, province, payment_terms, is_active)
VALUES
('sup-0001-0000-0000-0000-000000000001', 'SUP-001',
 'Davao Wholesale Trading',       'Ricardo Bautista',
 'orders@davaowholesale.com',     '+63822987654', 'Davao City', 'Davao del Sur', 'Net 30', 1),
('sup-0002-0000-0000-0000-000000000002', 'SUP-002',
 'Mindanao Food Distributors',    'Elena Figueroa',
 'sales@mindanaofood.com',        '+63823876543', 'Davao City', 'Davao del Sur', 'COD', 1),
('sup-0003-0000-0000-0000-000000000003', 'SUP-003',
 'CleanPro Supplies Inc.',        'Jose Navarro',
 'info@cleanprosupplies.com',     '+63824765432', 'Davao City', 'Davao del Sur', 'Net 15', 1),
('sup-0004-0000-0000-0000-000000000004', 'SUP-004',
 'Samal Island Amenities Corp.',  'Grace Domingo',
 'orders@samalamenities.com',     '+63825654321', 'Samal Island', 'Davao del Norte', 'COD', 1);

-- ============================================================================
-- 14. PRODUCTS
-- ============================================================================
INSERT IGNORE INTO products
    (product_id, category_id, uom_id, preferred_supplier_id,
     product_code, product_name, brand,
     unit_cost, selling_price, current_stock,
     reorder_point, minimum_stock,
     product_type, is_for_sale, is_for_rooms, track_inventory, is_active)
VALUES
-- Food items
('prod-0001-0000-0000-0000-000000000001', 7, 1, 'sup-0002-0000-0000-0000-000000000002',
 'FB-F-001', 'Rice (per kilo)',          NULL,   52.00,  0.00,  100.000, 20.000, 10.000,  'food',      0, 0, 1, 1),
('prod-0002-0000-0000-0000-000000000002', 7, 1, 'sup-0002-0000-0000-0000-000000000002',
 'FB-F-002', 'Chicken (Whole, per kilo)',NULL,  220.00,  0.00,   30.000,  5.000,  2.000,  'food',      0, 0, 1, 1),
('prod-0003-0000-0000-0000-000000000003', 7, 1, 'sup-0002-0000-0000-0000-000000000002',
 'FB-F-003', 'Eggs (per dozen)',         NULL,   90.00,  0.00,   20.000,  5.000,  2.000,  'food',      0, 0, 1, 1),
-- Beverages
('prod-0004-0000-0000-0000-000000000004', 8, 10, 'sup-0001-0000-0000-0000-000000000001',
 'FB-B-001', 'Mineral Water 500ml',     'Wilkins', 12.00, 25.00, 200.000, 50.000, 20.000, 'beverage',  1, 0, 1, 1),
('prod-0005-0000-0000-0000-000000000005', 8, 11, 'sup-0001-0000-0000-0000-000000000001',
 'FB-B-002', 'Softdrink 330ml Can',     'Coke',    18.00, 40.00, 144.000, 48.000, 24.000, 'beverage',  1, 0, 1, 1),
('prod-0006-0000-0000-0000-000000000006', 8, 10, 'sup-0001-0000-0000-0000-000000000001',
 'FB-B-003', 'Red Horse Beer 500ml',    'San Miguel', 45.00, 80.00, 96.000, 24.000, 12.000,'beverage',  1, 0, 1, 1),
-- Cleaning supplies
('prod-0007-0000-0000-0000-000000000007', 10, 10, 'sup-0003-0000-0000-0000-000000000003',
 'HK-C-001', 'Zonrox Bleach 1L',        'Zonrox',  55.00,  0.00,  24.000,  6.000,  3.000, 'cleaning',  0, 0, 1, 1),
('prod-0008-0000-0000-0000-000000000008', 10, 10, 'sup-0003-0000-0000-0000-000000000003',
 'HK-C-002', 'Floor Cleaner 1L',        'Mr. Muscle', 80.00, 0.00, 12.000, 4.000,  2.000, 'cleaning',  0, 0, 1, 1),
-- Linens
('prod-0009-0000-0000-0000-000000000009', 12, 1, 'sup-0003-0000-0000-0000-000000000003',
 'HK-L-001', 'Bath Towel (White)',       NULL,  180.00,  0.00,  50.000, 10.000,  5.000,  'non_consumable', 0, 1, 1, 1),
('prod-0010-0000-0000-0000-000000000010', 12, 3, 'sup-0003-0000-0000-0000-000000000003',
 'HK-L-002', 'Bed Sheet Set (Queen)',    NULL,  350.00,  0.00,  30.000,  8.000,  4.000,  'non_consumable', 0, 1, 1, 1),
-- Bath amenities
('prod-0011-0000-0000-0000-000000000011', 13, 3, 'sup-0004-0000-0000-0000-000000000004',
 'AM-B-001', 'Toiletries Kit (Basic)',   NULL,   85.00,  0.00, 200.000, 40.000, 20.000,  'amenity',   0, 1, 1, 1),
('prod-0012-0000-0000-0000-000000000012', 13, 1, 'sup-0004-0000-0000-0000-000000000004',
 'AM-B-002', 'Shampoo Sachet 10ml',      'Head&Shoulders', 5.00, 0.00, 500.000, 100.000, 50.000, 'amenity', 0, 1, 1, 1);

-- ============================================================================
-- 15. FOOD CATEGORIES
-- ============================================================================
INSERT IGNORE INTO food_categories (category_id, property_id, category_name, sort_order, is_active) VALUES
(1, NULL, 'Breakfast Meals',  1, 1),
(2, NULL, 'Rice Meals',       2, 1),
(3, NULL, 'Grilled & BBQ',    3, 1),
(4, NULL, 'Snacks & Pulutan', 4, 1),
(5, NULL, 'Beverages',        5, 1),
(6, NULL, 'Desserts',         6, 1);

-- ============================================================================
-- 16. MENU ITEMS
-- ============================================================================
INSERT IGNORE INTO menu_items
    (item_id, category_id, item_code, item_name, description, price, cost_price, is_available, is_active)
VALUES
-- Breakfast
('menu-0001-0000-0000-0000-000000000001', 1, 'BF-001', 'Tapsilog',
 'Beef tapa, garlic fried rice, sunny side up egg',         120.00, 60.00, 1, 1),
('menu-0002-0000-0000-0000-000000000002', 1, 'BF-002', 'Longsilog',
 'Longanisa, garlic fried rice, sunny side up egg',         110.00, 55.00, 1, 1),
('menu-0003-0000-0000-0000-000000000003', 1, 'BF-003', 'Bangsilog',
 'Grilled bangus, garlic fried rice, sunny side up egg',    130.00, 65.00, 1, 1),
('menu-0004-0000-0000-0000-000000000004', 1, 'BF-004', 'Champorado',
 'Chocolate rice porridge with dried fish',                  80.00, 35.00, 1, 1),
-- Rice Meals
('menu-0005-0000-0000-0000-000000000005', 2, 'RM-001', 'Chicken Adobo + Rice',
 'Classic Filipino chicken adobo with steamed rice',        150.00, 70.00, 1, 1),
('menu-0006-0000-0000-0000-000000000006', 2, 'RM-002', 'Kare-Kare + Rice',
 'Oxtail and vegetables in peanut sauce with bagoong',      200.00, 95.00, 1, 1),
('menu-0007-0000-0000-0000-000000000007', 2, 'RM-003', 'Sinigang na Hipon + Rice',
 'Shrimp in tamarind soup with vegetables',                 220.00, 100.00, 1, 1),
-- Grilled
('menu-0008-0000-0000-0000-000000000008', 3, 'GR-001', 'Inihaw na Liempo',
 'Grilled pork belly with atchara and rice',                180.00, 85.00, 1, 1),
('menu-0009-0000-0000-0000-000000000009', 3, 'GR-002', 'Inihaw na Bangus',
 'Grilled stuffed milkfish with tomato and onion',          200.00, 95.00, 1, 1),
('menu-0010-0000-0000-0000-000000000010', 3, 'GR-003', 'Chicken BBQ (per piece)',
 'Marinated chicken BBQ with Java rice',                    100.00, 50.00, 1, 1),
-- Snacks
('menu-0011-0000-0000-0000-000000000011', 4, 'SN-001', 'Calamares',
 'Crispy fried squid rings with dipping sauce',             160.00, 70.00, 1, 1),
('menu-0012-0000-0000-0000-000000000012', 4, 'SN-002', 'Nachos with Cheese',
 'Corn chips with nacho cheese and salsa',                  120.00, 50.00, 1, 1),
-- Beverages
('menu-0013-0000-0000-0000-000000000013', 5, 'BV-001', 'Buko Juice (Fresh)',
 'Fresh young coconut juice',                                60.00, 25.00, 1, 1),
('menu-0014-0000-0000-0000-000000000014', 5, 'BV-002', 'Mineral Water 500ml',
 'Cold bottled water',                                       25.00, 12.00, 1, 1),
('menu-0015-0000-0000-0000-000000000015', 5, 'BV-003', 'Softdrink (Canned)',
 'Coke, Sprite, Royal in can',                               40.00, 18.00, 1, 1),
('menu-0016-0000-0000-0000-000000000016', 5, 'BV-004', 'Red Horse Beer 500ml',
 'Cold Red Horse extra strong beer',                         80.00, 45.00, 1, 1),
-- Desserts
('menu-0017-0000-0000-0000-000000000017', 6, 'DS-001', 'Halo-Halo',
 'Mixed shaved ice dessert with leche flan, ube, and evap',  90.00, 35.00, 1, 1),
('menu-0018-0000-0000-0000-000000000018', 6, 'DS-002', 'Buko Pandan',
 'Young coconut strips in pandan-flavored cream',            70.00, 28.00, 1, 1);

-- ============================================================================
-- 17. DISCOUNTS
-- ============================================================================
INSERT IGNORE INTO discounts
    (discount_id, property_id, discount_code, discount_name, discount_type,
     discount_value, min_nights, applies_to, valid_from, valid_until, is_active, created_by)
VALUES
('disc-0001-0000-0000-0000-000000000001',
 'prop-0001-0000-0000-0000-000000000001',
 'WELCOME10', 'Welcome Discount 10%', 'percentage',
 10.00, NULL, 'overnight,day_use,hourly',
 '2024-01-01', '2026-12-31', 1,
 'ua-00000001-0000-0000-0000-000000000001'),

('disc-0002-0000-0000-0000-000000000002',
 'prop-0001-0000-0000-0000-000000000001',
 'STAY3SAVE', '3-Night Stay 15% Off', 'percentage',
 15.00, 3, 'overnight',
 '2024-01-01', '2026-12-31', 1,
 'ua-00000001-0000-0000-0000-000000000001'),

('disc-0003-0000-0000-0000-000000000003',
 'prop-0001-0000-0000-0000-000000000001',
 'SUMMER500', 'Summer Sale Flat 500 Off', 'fixed_amount',
 500.00, 2, 'overnight',
 '2025-03-15', '2025-05-31', 1,
 'ua-00000001-0000-0000-0000-000000000001');

-- ============================================================================
-- 18. PROMOTIONS
-- ============================================================================
INSERT IGNORE INTO promotions
    (promo_id, property_id, promo_title, promo_type,
     short_desc, full_description, terms_conditions,
     discount_id, valid_from, valid_until, is_featured, is_active)
VALUES
('promo-001-0000-0000-0000-000000000001',
 'prop-0001-0000-0000-0000-000000000001',
 'Summer Fiesta 2025 — Save ₱500!',
 'seasonal_deal',
 'Book 2 nights or more this summer and save ₱500 on your stay.',
 'Enjoy the summer breeze at Balay sa Dagat Resort. Book any room for minimum 2 nights from March 15 to May 31 and automatically save ₱500 off your total bill.',
 'Valid for new bookings only. Not combinable with other promos. Minimum 2-night stay required. Blackout dates may apply during Holy Week.',
 'disc-0003-0000-0000-0000-000000000003',
 '2025-03-01', '2025-05-31', 1, 1),

('promo-002-0000-0000-0000-000000000002',
 'prop-0001-0000-0000-0000-000000000001',
 'Stay 3 Nights, Save 15%',
 'package',
 'The longer you stay, the more you save. Book 3 nights or more and enjoy 15% off.',
 'Perfect for extended weekend getaways. Book a minimum of 3 consecutive nights in any room category and receive 15% discount on your total accommodation cost.',
 'Valid for overnight bookings only. Discount applied at checkout. Cannot be combined with other discount codes.',
 'disc-0002-0000-0000-0000-000000000002',
 '2024-01-01', '2026-12-31', 1, 1),

('promo-003-0000-0000-0000-000000000003',
 'prop-0001-0000-0000-0000-000000000001',
 'First-Time Guest? Get 10% Off!',
 'banner',
 'New to Balay sa Dagat? Use code WELCOME10 and get 10% off your first booking.',
 'Welcome to paradise! As a first-time guest, use the exclusive code WELCOME10 during booking to enjoy 10% off on any room type for any booking duration.',
 'One-time use per guest account. Valid for new bookings only. Applicable to all room types.',
 'disc-0001-0000-0000-0000-000000000001',
 '2024-01-01', '2026-12-31', 0, 1);

-- ============================================================================
-- 19. SYSTEM SETTINGS
-- ============================================================================
INSERT IGNORE INTO system_settings
    (property_id, setting_group, setting_key, setting_label, setting_value, data_type, is_public)
VALUES
(NULL, 'booking', 'require_id_before_checkin',     'Require valid ID before check-in',     '1',    'boolean', 0),
(NULL, 'booking', 'allow_partial_payment',          'Allow partial payment on booking',     '1',    'boolean', 0),
(NULL, 'booking', 'partial_payment_min_percent',    'Minimum deposit percentage',           '30',   'integer', 0),
(NULL, 'booking', 'advance_booking_days_max',       'Maximum advance booking days',         '365',  'integer', 1),
(NULL, 'booking', 'same_day_booking_cutoff_time',   'Same-day booking cutoff time',         '20:00','time',    1),
(NULL, 'booking', 'cancel_free_hours',              'Free cancellation window (hours)',     '24',   'integer', 1),
(NULL, 'payment', 'accepted_payment_methods',       'Accepted payment methods (CSV IDs)',   '1,2,3,4,5', 'string', 1),
(NULL, 'payment', 'invoice_prefix',                 'Invoice/OR number prefix',             'OR-',  'string',  0),
(NULL, 'payment', 'po_number_prefix',               'Purchase order number prefix',         'PO-',  'string',  0),
(NULL, 'security','max_failed_login_attempts',      'Max failed login before lockout',      '5',    'integer', 0),
(NULL, 'security','lockout_duration_minutes',       'Lockout duration in minutes',          '30',   'integer', 0),
(NULL, 'security','session_timeout_hours',          'Web session timeout in hours',         '8',    'integer', 0),
(NULL, 'display', 'resort_name',                    'Resort display name',                  'Balay sa Dagat Resort', 'string', 1),
(NULL, 'display', 'support_phone',                  'Customer support phone',               '+63822123456', 'string', 1),
(NULL, 'display', 'support_email',                  'Customer support email',               'info@balaydagat.com', 'string', 1),
(NULL, 'food_canteen', 'food_tax_rate_percent',          'Food/Canteen Tax Rate (%)',            '12',   'decimal', 1);

-- ============================================================================
-- 20. BOOKING POLICIES
-- ============================================================================
INSERT IGNORE INTO booking_policies
    (policy_id, property_id, policy_type, policy_name, description,
     free_cancel_hours, penalty_type, penalty_value,
     deposit_type, deposit_value, is_active, created_by)
VALUES
('pol-0001-0000-0000-0000-000000000001',
 'prop-0001-0000-0000-0000-000000000001',
 'cancellation', 'Standard Cancellation Policy',
 'Free cancellation up to 24 hours before check-in. Cancellations within 24 hours will forfeit the first night deposit.',
 24, 'first_night', NULL, NULL, NULL, 1,
 'ua-00000001-0000-0000-0000-000000000001'),

('pol-0002-0000-0000-0000-000000000002',
 'prop-0001-0000-0000-0000-000000000001',
 'deposit', 'Partial Payment / Deposit Policy',
 'A minimum of 30% deposit is required to confirm a reservation. Full payment must be settled upon check-in.',
 NULL, NULL, NULL, 'percentage', 30.00, 1,
 'ua-00000001-0000-0000-0000-000000000001'),

('pol-0003-0000-0000-0000-000000000003',
 'prop-0001-0000-0000-0000-000000000001',
 'no_show', 'No-Show Policy',
 'Guests who do not arrive and fail to cancel will forfeit 100% of their deposit.',
 0, 'full_stay', 100.00, NULL, NULL, 1,
 'ua-00000001-0000-0000-0000-000000000001'),

('pol-0004-0000-0000-0000-000000000004',
 'prop-0001-0000-0000-0000-000000000001',
 'child_policy', 'Child Policy',
 'Children below 3 years old stay FREE. Children 3-12 years old are charged the child rate. Children 12 and above are charged as adults.',
 NULL, NULL, NULL, NULL, NULL, 1,
 'ua-00000001-0000-0000-0000-000000000001');

-- ============================================================================
-- 21. REQUIREMENT TYPES
-- ============================================================================
INSERT IGNORE INTO reservation_requirement_types
    (req_type_id, property_id, req_code, req_label, description, is_mandatory, applies_to, is_active, sort_order)
VALUES
(1, NULL, 'REQ-VALID-ID',   'Valid Government ID',
 'At least one valid government-issued ID (passport, PhilSys, Driver\'s license, etc.)',
 1, 'overnight,hourly,day_use', 1, 1),
(2, NULL, 'REQ-WAIVER',     'Signed Guest Waiver',
 'Signed resort liability waiver form for all guests',
 1, 'overnight,day_use', 1, 2),
(3, NULL, 'REQ-GUEST-INFO', 'Complete Guest Information Form',
 'Full name, age, and ID of all accompanying guests',
 1, 'overnight', 1, 3),
(4, NULL, 'REQ-DEPOSIT',    'Reservation Deposit Receipt',
 'Proof of at least 30% deposit payment before reservation is confirmed',
 1, 'overnight,hourly', 1, 4);

SET FOREIGN_KEY_CHECKS = 1;

SELECT '08_seed_data.sql — OK — All seed data inserted successfully' AS migration_status;
