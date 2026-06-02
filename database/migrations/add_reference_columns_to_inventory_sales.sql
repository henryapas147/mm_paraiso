-- Migration: Add reference columns to inventory_sales table
-- Purpose: Enable integration with food_orders for unified inventory sales tracking
-- Date: 2026-06-02

USE mm_paraiso;

-- Add reference columns to inventory_sales table
ALTER TABLE inventory_sales
ADD COLUMN reference_type VARCHAR(50) NULL COMMENT 'Source: food_order, pos, etc.' AFTER notes,
ADD COLUMN reference_id CHAR(36) NULL COMMENT 'ID of the source record (e.g., food_order.order_id)' AFTER reference_type;

-- Make property_id nullable to support takeout orders
ALTER TABLE inventory_sales MODIFY COLUMN property_id CHAR(36) NULL COMMENT 'FK → properties.property_id (NULL for takeout/canteen)';

-- Add staff_member_id column for staff purchase tracking
ALTER TABLE inventory_sales
ADD COLUMN staff_member_id CHAR(36) NULL COMMENT 'FK → user_accounts.user_id (for staff_purchase)' AFTER reference_id;

-- Add index for faster lookups by reference
ALTER TABLE inventory_sales ADD INDEX idx_reference (reference_type, reference_id);

-- Add index for staff member lookups
ALTER TABLE inventory_sales ADD INDEX idx_staff_member_id (staff_member_id);

SELECT 'Migration completed: reference_type, reference_id, staff_member_id added and property_id made nullable in inventory_sales' AS status;
