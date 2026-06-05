-- ============================================================================
-- Migration: Add extra_charges permissions to user_roles
-- ============================================================================

SET NAMES utf8mb4;

-- super_admin: add extra_charges permissions
UPDATE user_roles SET permissions = JSON_ARRAY_APPEND(
  JSON_ARRAY_APPEND(
    JSON_ARRAY_APPEND(
      JSON_ARRAY_APPEND(permissions, '$', 'extra_charges.view'),
      '$', 'extra_charges.create'
    ),
    '$', 'extra_charges.edit'
  ),
  '$', 'extra_charges.delete'
)
WHERE role_code = 'super_admin'
  AND NOT JSON_CONTAINS(permissions, '"extra_charges.view"');

-- admin: add extra_charges permissions
UPDATE user_roles SET permissions = JSON_ARRAY_APPEND(
  JSON_ARRAY_APPEND(
    JSON_ARRAY_APPEND(
      JSON_ARRAY_APPEND(permissions, '$', 'extra_charges.view'),
      '$', 'extra_charges.create'
    ),
    '$', 'extra_charges.edit'
  ),
  '$', 'extra_charges.delete'
)
WHERE role_code = 'admin'
  AND NOT JSON_CONTAINS(permissions, '"extra_charges.view"');

-- front_desk: add extra_charges view/create/edit
UPDATE user_roles SET permissions = JSON_ARRAY_APPEND(
  JSON_ARRAY_APPEND(
    JSON_ARRAY_APPEND(permissions, '$', 'extra_charges.view'),
    '$', 'extra_charges.create'
  ),
  '$', 'extra_charges.edit'
)
WHERE role_code = 'front_desk'
  AND NOT JSON_CONTAINS(permissions, '"extra_charges.view"');

SELECT 'add_extra_charges_permissions.sql — OK' AS migration_status;
