-- ============================================================================
-- RBAC Permissions Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS permissions (
    permission_id       CHAR(36)        NOT NULL DEFAULT (UUID()) COMMENT 'PK - UUID v4',
    permission_code     VARCHAR(100)    NOT NULL UNIQUE COMMENT 'e.g., dashboard.view',
    permission_label    VARCHAR(200)    NOT NULL COMMENT 'Human readable label',
    description         TEXT            NULL COMMENT 'Description of what this permission allows',
    category            VARCHAR(50)     NOT NULL DEFAULT 'General' COMMENT 'Grouping category',
    is_system           TINYINT(1)      NOT NULL DEFAULT 0 COMMENT 'System permissions cannot be deleted',
    is_active           TINYINT(1)      NOT NULL DEFAULT 1,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (permission_id),
    KEY idx_permission_code (permission_code),
    KEY idx_category (category),
    KEY idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Master list of all available permissions';

-- Insert default permissions
INSERT INTO permissions (permission_code, permission_label, description, category, is_system) VALUES
-- Dashboard
('dashboard.view', 'View Dashboard', 'Access the main dashboard', 'Dashboard', 1),
('dashboard.manage', 'Manage Dashboard', 'Manage dashboard settings', 'Dashboard', 1),

-- Users
('users.view', 'View Users', 'View user accounts', 'Users', 1),
('users.create', 'Create Users', 'Create new user accounts', 'Users', 1),
('users.edit', 'Edit Users', 'Edit existing user accounts', 'Users', 1),
('users.delete', 'Delete Users', 'Delete user accounts', 'Users', 1),
('users.roles.manage', 'Manage Roles', 'Manage user roles and permissions', 'Users', 1),

-- Properties
('properties.view', 'View Properties', 'View property listings', 'Properties', 1),
('properties.create', 'Create Properties', 'Create new properties', 'Properties', 1),
('properties.edit', 'Edit Properties', 'Edit property details', 'Properties', 1),
('properties.delete', 'Delete Properties', 'Delete properties', 'Properties', 1),

-- Reservations
('reservations.view', 'View Reservations', 'View reservations', 'Reservations', 1),
('reservations.create', 'Create Reservations', 'Create new reservations', 'Reservations', 1),
('reservations.edit', 'Edit Reservations', 'Edit existing reservations', 'Reservations', 1),
('reservations.delete', 'Delete Reservations', 'Delete reservations', 'Reservations', 1),
('reservations.check_in', 'Check In', 'Process guest check-in', 'Reservations', 1),
('reservations.check_out', 'Check Out', 'Process guest check-out', 'Reservations', 1),

-- Customers
('customers.view', 'View Customers', 'View customer profiles', 'Customers', 1),
('customers.create', 'Create Customers', 'Create new customers', 'Customers', 1),
('customers.edit', 'Edit Customers', 'Edit customer profiles', 'Customers', 1),
('customers.delete', 'Delete Customers', 'Delete customers', 'Customers', 1),

-- Inventory
('inventory.view', 'View Inventory', 'View inventory items', 'Inventory', 1),
('inventory.create', 'Create Inventory', 'Create inventory items', 'Inventory', 1),
('inventory.edit', 'Edit Inventory', 'Edit inventory items', 'Inventory', 1),
('inventory.delete', 'Delete Inventory', 'Delete inventory items', 'Inventory', 1),

-- Food & Canteen
('food_canteen.view', 'View Food & Canteen', 'View food menu and orders', 'Food & Canteen', 1),
('food_canteen.create', 'Create Food Items', 'Create food menu items', 'Food & Canteen', 1),
('food_canteen.edit', 'Edit Food Items', 'Edit food menu items', 'Food & Canteen', 1),
('food_canteen.delete', 'Delete Food Items', 'Delete food menu items', 'Food & Canteen', 1),

-- Payments
('payments.view', 'View Payments', 'View payment records', 'Payments', 1),
('payments.create', 'Create Payments', 'Process new payments', 'Payments', 1),
('payments.edit', 'Edit Payments', 'Edit payment records', 'Payments', 1),
('payments.delete', 'Delete Payments', 'Delete payment records', 'Payments', 1),

-- Reports
('reports.view', 'View Reports', 'View system reports', 'Reports', 1),

-- Settings
('settings.view', 'View Settings', 'View system settings', 'Settings', 1),
('settings.edit', 'Edit Settings', 'Edit system settings', 'Settings', 1),
('settings.manage', 'Manage Settings', 'Full settings management', 'Settings', 1),
('settings.system.view', 'View System Settings', 'View system configuration', 'Settings', 1),
('settings.company.view', 'View Company Settings', 'View company information', 'Settings', 1),
('settings.pricing_rules.view', 'View Pricing Rules', 'View pricing rules', 'Settings', 1),
('settings.seasonal_rates.view', 'View Seasonal Rates', 'View seasonal rates', 'Settings', 1),
('settings.booking_policies.view', 'View Booking Policies', 'View booking policies', 'Settings', 1),
('settings.promotions.view', 'View Promotions', 'View promotions', 'Settings', 1),
('settings.promotion_images.view', 'View Promotion Images', 'View promotion images', 'Settings', 1),
('settings.notifications.view', 'View Notifications', 'View notification settings', 'Settings', 1),
('settings.notification_recipients.view', 'View Notification Recipients', 'View notification recipients', 'Settings', 1),
('settings.rbac.view', 'View RBAC Settings', 'View role-based access control', 'Settings', 1);

SELECT 'Permissions table created and seeded' AS migration_status;
