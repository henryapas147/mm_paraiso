-- ============================================================================
-- Migration: Add Pricing Mode to Products
-- Purpose: Support flexible pricing modes (fixed price, percentage markup, fixed markup)
-- ============================================================================

SET NAMES utf8mb4;

-- Add pricing columns to products table
ALTER TABLE products
    ADD COLUMN pricing_mode ENUM('fixed_price', 'percentage_markup', 'fixed_markup') NULL
        COMMENT 'Pricing calculation mode for selling price'
        AFTER selling_price,
    ADD COLUMN markup_value DECIMAL(12,4) NULL
        COMMENT 'Markup value (percentage or fixed amount based on pricing_mode)'
        AFTER pricing_mode;

-- Set default values for existing products
UPDATE products SET pricing_mode = 'fixed_price' WHERE pricing_mode IS NULL;

SELECT 'add_product_pricing_mode.sql — OK' AS migration_status;
