-- ============================================================================
-- Migration: Add pricing fields to product_uom_conversions
-- Purpose: Support alternative product codes and flexible pricing per UOM
-- ============================================================================

SET NAMES utf8mb4;

-- Add new columns to product_uom_conversions table
ALTER TABLE product_uom_conversions
    ADD COLUMN alt_product_code VARCHAR(50) NULL
        COMMENT 'Alternative product code for this UOM (e.g., RICE-SACK)'
        AFTER conversion_factor,
    ADD COLUMN barcode VARCHAR(100) NULL
        COMMENT 'Barcode for scanning this UOM'
        AFTER alt_product_code,
    ADD COLUMN selling_price DECIMAL(15,4) NULL
        COMMENT 'Selling price for this specific UOM'
        AFTER barcode,
    ADD COLUMN pricing_mode ENUM('fixed_price', 'percentage_markup', 'fixed_markup')
        NOT NULL DEFAULT 'fixed_price'
        COMMENT 'How the selling price is calculated: fixed_price=manual, percentage_markup=% over cost, fixed_markup=amount over cost'
        AFTER selling_price,
    ADD COLUMN markup_value DECIMAL(15,4) NULL
        COMMENT 'Markup value: % for percentage_markup, amount for fixed_markup'
        AFTER pricing_mode,
    ADD COLUMN last_po_cost DECIMAL(15,4) NULL
        COMMENT 'Last purchase order cost per base UOM unit (auto-updated)'
        AFTER markup_value,
    ADD COLUMN calculated_selling_price DECIMAL(15,4) NULL
        COMMENT 'Auto-calculated selling price based on pricing_mode and markup'
        AFTER last_po_cost;

-- Add index for alt_product_code lookups
CREATE INDEX idx_alt_product_code ON product_uom_conversions(alt_product_code);

-- Add index for barcode lookups
CREATE INDEX idx_barcode ON product_uom_conversions(barcode);

-- Update the view to include new fields
CREATE OR REPLACE VIEW product_uom_conversions_view AS
SELECT
    puc.conversion_id,
    puc.product_id,
    p.product_name,
    p.product_code,
    p.uom_id AS base_uom_id,
    base_uom.uom_code AS base_uom_code,
    base_uom.uom_name AS base_uom_name,
    puc.from_uom_id,
    from_uom.uom_code AS from_uom_code,
    from_uom.uom_name AS from_uom_name,
    puc.to_uom_id,
    to_uom.uom_code AS to_uom_code,
    to_uom.uom_name AS to_uom_name,
    puc.conversion_factor,
    puc.alt_product_code,
    puc.barcode,
    puc.selling_price,
    puc.pricing_mode,
    puc.markup_value,
    puc.last_po_cost,
    puc.calculated_selling_price,
    puc.is_default,
    puc.is_active,
    puc.created_at,
    puc.updated_at
FROM product_uom_conversions puc
INNER JOIN products p ON puc.product_id = p.product_id
INNER JOIN units_of_measure base_uom ON p.uom_id = base_uom.uom_id
INNER JOIN units_of_measure from_uom ON puc.from_uom_id = from_uom.uom_id
INNER JOIN units_of_measure to_uom ON puc.to_uom_id = to_uom.uom_id;

SELECT 'add_uom_conversion_pricing.sql — OK' AS migration_status;