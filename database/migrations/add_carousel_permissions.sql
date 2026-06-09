-- ============================================================================
-- CAROUSEL PERMISSIONS
-- Add content_management permissions to the permissions table
-- ============================================================================

-- Insert carousel permissions if they don't exist
INSERT INTO permissions (permission_code, permission_label, description, category, is_system, is_active) VALUES
(
    'content_management.carousel.view',
    'View Carousel Slides',
    'Access to view and manage hero banner carousel slides',
    'content_management',
    1,
    1
)
ON DUPLICATE KEY UPDATE 
    permission_label = VALUES(permission_label),
    description = VALUES(description),
    category = VALUES(category),
    is_system = VALUES(is_system),
    is_active = VALUES(is_active);

-- Add create permission
INSERT INTO permissions (permission_code, permission_label, description, category, is_system, is_active) VALUES
(
    'content_management.carousel.create',
    'Create Carousel Slides',
    'Create new carousel slides',
    'content_management',
    1,
    1
)
ON DUPLICATE KEY UPDATE 
    permission_label = VALUES(permission_label),
    description = VALUES(description),
    category = VALUES(category),
    is_system = VALUES(is_system),
    is_active = VALUES(is_active);

-- Add edit permission
INSERT INTO permissions (permission_code, permission_label, description, category, is_system, is_active) VALUES
(
    'content_management.carousel.edit',
    'Edit Carousel Slides',
    'Edit existing carousel slides',
    'content_management',
    1,
    1
)
ON DUPLICATE KEY UPDATE 
    permission_label = VALUES(permission_label),
    description = VALUES(description),
    category = VALUES(category),
    is_system = VALUES(is_system),
    is_active = VALUES(is_active);

-- Add delete permission
INSERT INTO permissions (permission_code, permission_label, description, category, is_system, is_active) VALUES
(
    'content_management.carousel.delete',
    'Delete Carousel Slides',
    'Delete carousel slides',
    'content_management',
    1,
    1
)
ON DUPLICATE KEY UPDATE 
    permission_label = VALUES(permission_label),
    description = VALUES(description),
    category = VALUES(category),
    is_system = VALUES(is_system),
    is_active = VALUES(is_active);

-- Add general content_management view permission
INSERT INTO permissions (permission_code, permission_label, description, category, is_system, is_active) VALUES
(
    'content_management.view',
    'View Content Management',
    'General access to content management section',
    'content_management',
    1,
    1
)
ON DUPLICATE KEY UPDATE 
    permission_label = VALUES(permission_label),
    description = VALUES(description),
    category = VALUES(category),
    is_system = VALUES(is_system),
    is_active = VALUES(is_active);

SELECT 'add_carousel_permissions.sql — OK' AS migration_status;
