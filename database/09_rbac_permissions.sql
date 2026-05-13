-- ============================================================================
-- RESORT / HOTEL RESERVATION SYSTEM
-- Module 09: RBAC Permissions Seed Data
-- Database: MySQL 8.0+  |  Engine: InnoDB  |  Charset: utf8mb4
-- ============================================================================

SET NAMES utf8mb4;
SET @dbname = DATABASE();

-- Update user_roles with initial permissions
UPDATE user_roles SET permissions = '[
  "dashboard.view",
  "dashboard.manage",
  "users.view",
  "users.create",
  "users.edit",
  "users.delete",
  "users.roles.manage",
  "properties.view",
  "properties.create",
  "properties.edit",
  "properties.delete",
  "rooms.view",
  "rooms.create",
  "rooms.edit",
  "rooms.delete",
  "reservations.view",
  "reservations.create",
  "reservations.edit",
  "reservations.delete",
  "reservations.check_in",
  "reservations.check_out",
  "customers.view",
  "customers.create",
  "customers.edit",
  "customers.delete",
  "inventory.view",
  "inventory.create",
  "inventory.edit",
  "inventory.delete",
  "food_canteen.view",
  "food_canteen.create",
  "food_canteen.edit",
  "food_canteen.delete",
  "payments.view",
  "payments.create",
  "payments.edit",
  "payments.delete",
  "reports.view",
  "settings.view",
  "settings.edit",
  "settings.manage"
]' WHERE role_code = 'super_admin';

UPDATE user_roles SET permissions = '[
  "dashboard.view",
  "dashboard.manage",
  "users.view",
  "users.create",
  "users.edit",
  "properties.view",
  "properties.create",
  "properties.edit",
  "rooms.view",
  "rooms.create",
  "rooms.edit",
  "reservations.view",
  "reservations.create",
  "reservations.edit",
  "reservations.check_in",
  "reservations.check_out",
  "customers.view",
  "customers.create",
  "customers.edit",
  "inventory.view",
  "inventory.create",
  "inventory.edit",
  "food_canteen.view",
  "food_canteen.create",
  "food_canteen.edit",
  "payments.view",
  "payments.create",
  "payments.edit",
  "reports.view",
  "settings.view",
  "settings.edit"
]' WHERE role_code = 'admin';

UPDATE user_roles SET permissions = '[
  "dashboard.view",
  "properties.view",
  "rooms.view",
  "reservations.view",
  "reservations.create",
  "reservations.edit",
  "reservations.check_in",
  "reservations.check_out",
  "customers.view",
  "customers.create",
  "customers.edit",
  "payments.view",
  "payments.create",
  "reports.view"
]' WHERE role_code = 'front_desk';

UPDATE user_roles SET permissions = '[
  "dashboard.view",
  "rooms.view",
  "reservations.view",
  "inventory.view"
]' WHERE role_code = 'housekeeping';

UPDATE user_roles SET permissions = '[
  "dashboard.view",
  "food_canteen.view",
  "food_canteen.create",
  "food_canteen.edit",
  "inventory.view"
]' WHERE role_code = 'canteen_staff';

UPDATE user_roles SET permissions = '[
  "dashboard.view",
  "reservations.view",
  "customers.view"
]' WHERE role_code = 'security';

UPDATE user_roles SET permissions = '[
  "dashboard.view",
  "reservations.view",
  "reservations.create",
  "payments.view",
  "payments.create"
]' WHERE role_code = 'customer';

UPDATE user_roles SET permissions = '[
  "reservations.view",
  "reservations.create",
  "payments.view",
  "payments.create"
]' WHERE role_code = 'api_client';

SELECT '09_rbac_permissions.sql — OK' AS migration_status;
