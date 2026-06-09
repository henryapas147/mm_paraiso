-- Fix: Change sale_item_id from INT to BIGINT to handle larger auto-increment values
-- This fixes the "Out of range value for column 'sale_item_id'" error

ALTER TABLE inventory_sale_items 
MODIFY COLUMN sale_item_id BIGINT NOT NULL AUTO_INCREMENT COMMENT 'PK';

SELECT 'fix_inventory_sale_items_id_type.sql — OK' AS migration_status;
