-- ============================================================================
-- RESORT / HOTEL RESERVATION SYSTEM
-- Module 08: Product UOM Conversions
-- Covers: Multiple UOMs per product with conversion factors
-- Database: MySQL 8.0+  |  Engine: InnoDB  |  Charset: utf8mb4
-- ============================================================================

SET NAMES utf8mb4;

-- ============================================================================
-- TABLE: product_uom_conversions
-- Defines conversion factors between different UOMs for the same product
-- Example: 1 sack = 50 kg, 1 kg = 1000 g
-- ============================================================================
CREATE TABLE IF NOT EXISTS product_uom_conversions (
    conversion_id       INT             NOT NULL AUTO_INCREMENT               COMMENT 'PK',
    product_id          CHAR(36)        NOT NULL                              COMMENT 'FK → products.product_id',
    from_uom_id         SMALLINT        NOT NULL                              COMMENT 'FK → units_of_measure.uom_id (source UOM)',
    to_uom_id           SMALLINT        NOT NULL                              COMMENT 'FK → units_of_measure.uom_id (target UOM)',
    conversion_factor   DECIMAL(12,6)   NOT NULL                              COMMENT 'Multiplier: to_qty = from_qty * conversion_factor',
    is_default          TINYINT(1)      NOT NULL DEFAULT 0                    COMMENT '1 = default conversion for this product',
    is_active           TINYINT(1)      NOT NULL DEFAULT 1,
    created_by          CHAR(36)        NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (conversion_id),
    UNIQUE KEY uk_product_from_to (product_id, from_uom_id, to_uom_id),
    KEY idx_product_id (product_id),
    KEY idx_from_uom (from_uom_id),
    KEY idx_to_uom (to_uom_id),
    CONSTRAINT fk_puc_product_id FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_puc_from_uom FOREIGN KEY (from_uom_id) REFERENCES units_of_measure(uom_id) ON UPDATE CASCADE,
    CONSTRAINT fk_puc_to_uom FOREIGN KEY (to_uom_id) REFERENCES units_of_measure(uom_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Conversion factors between UOMs for each product';

-- ============================================================================
-- VIEW: product_uom_conversions_view
-- Joins conversion data with product and UOM names for easy querying
-- ============================================================================
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
    puc.is_default,
    puc.is_active,
    puc.created_at,
    puc.updated_at
FROM product_uom_conversions puc
INNER JOIN products p ON puc.product_id = p.product_id
INNER JOIN units_of_measure base_uom ON p.uom_id = base_uom.uom_id
INNER JOIN units_of_measure from_uom ON puc.from_uom_id = from_uom.uom_id
INNER JOIN units_of_measure to_uom ON puc.to_uom_id = to_uom.uom_id;

SELECT '08_product_uom_conversions.sql — OK' AS migration_status;
