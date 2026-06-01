-- Add delivery address code columns to purchase_orders table
ALTER TABLE purchase_orders
ADD COLUMN delivery_region_code VARCHAR(20) NULL AFTER delivery_address,
ADD COLUMN delivery_province_code VARCHAR(20) NULL AFTER delivery_region_code,
ADD COLUMN delivery_city_code VARCHAR(20) NULL AFTER delivery_province_code,
ADD COLUMN delivery_barangay_code VARCHAR(20) NULL AFTER delivery_city_code;
