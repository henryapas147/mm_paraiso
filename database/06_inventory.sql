-- ============================================================================
-- RESORT / HOTEL RESERVATION SYSTEM
-- Module 06: Inventory Management
-- Covers: Categories, UOM, Products, Suppliers, Purchase Orders,
--         Stock In, Stock Out, Stock Adjustments, Sales
-- Database: MySQL 8.0+  |  Engine: InnoDB  |  Charset: utf8mb4
-- ============================================================================

SET NAMES utf8mb4;

-- ============================================================================
-- TABLE: inventory_categories
-- Hierarchical categories: Toiletries > Shampoo, F&B > Beverages > Softdrinks
-- ============================================================================
CREATE TABLE IF NOT EXISTS inventory_categories (
    category_id     SMALLINT        NOT NULL AUTO_INCREMENT                   COMMENT 'PK',
    parent_id       SMALLINT        NULL                                      COMMENT 'FK → self (for sub-categories)',
    category_code   VARCHAR(30)     NOT NULL,
    category_name   VARCHAR(100)    NOT NULL,
    description     VARCHAR(255)    NULL,
    sort_order      SMALLINT        NOT NULL DEFAULT 0,
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (category_id),
    UNIQUE KEY uk_category_code (category_code),
    KEY idx_parent_id (parent_id),
    CONSTRAINT fk_ic_parent_id FOREIGN KEY (parent_id) REFERENCES inventory_categories(category_id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Product categories — supports parent/sub-category hierarchy';

-- ============================================================================
-- TABLE: units_of_measure
-- UOM master: pcs, box, pack, kg, g, liter, ml, dozen, pair, set, roll, etc.
-- ============================================================================
CREATE TABLE IF NOT EXISTS units_of_measure (
    uom_id          SMALLINT        NOT NULL AUTO_INCREMENT                   COMMENT 'PK',
    uom_code        VARCHAR(20)     NOT NULL                                  COMMENT 'pcs, box, kg, g, L, ml, doz, pk, roll, set',
    uom_name        VARCHAR(60)     NOT NULL,
    uom_type        ENUM('count','weight','volume','length','area','other')   NOT NULL DEFAULT 'count',
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    PRIMARY KEY (uom_id),
    UNIQUE KEY uk_uom_code (uom_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Units of measure master list';

-- ============================================================================
-- TABLE: inventory_locations
-- Where stock is physically stored: Main Bodega, Kitchen, Housekeeping Closet
-- ============================================================================
CREATE TABLE IF NOT EXISTS inventory_locations (
    location_id     SMALLINT        NOT NULL AUTO_INCREMENT                   COMMENT 'PK',
    property_id     CHAR(36)        NOT NULL                                  COMMENT 'FK → properties.property_id',
    location_name   VARCHAR(100)    NOT NULL                                  COMMENT 'e.g. Main Stockroom, Kitchen Pantry, Bar',
    location_type   ENUM('main_warehouse','kitchen','bar','housekeeping','front_desk','other')
                                    NOT NULL DEFAULT 'main_warehouse',
    description     VARCHAR(255)    NULL,
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    PRIMARY KEY (location_id),
    KEY idx_property_id (property_id),
    CONSTRAINT fk_il_property_id FOREIGN KEY (property_id) REFERENCES properties(property_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Physical storage locations per property';

-- ============================================================================
-- TABLE: suppliers
-- Vendor/supplier master list
-- ============================================================================
CREATE TABLE IF NOT EXISTS suppliers (
    supplier_id         CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    supplier_code       VARCHAR(30)     NULL,
    company_name        VARCHAR(150)    NOT NULL,
    contact_person      VARCHAR(120)    NULL,
    email               VARCHAR(180)    NULL,
    phone_primary       VARCHAR(20)     NULL,
    phone_secondary     VARCHAR(20)     NULL,
    address_line1       VARCHAR(255)    NULL,
    address_line2       VARCHAR(255)    NULL,
    city                VARCHAR(80)     NULL,
    province            VARCHAR(80)     NULL,
    tin_number          VARCHAR(20)     NULL                                  COMMENT 'Tax Identification Number',
    payment_terms       VARCHAR(100)    NULL                                  COMMENT 'e.g. Net 30, COD, Prepaid',
    notes               TEXT            NULL,
    is_active           TINYINT(1)      NOT NULL DEFAULT 1,
    created_by          CHAR(36)        NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (supplier_id),
    UNIQUE KEY uk_supplier_code (supplier_code),
    KEY idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Supplier/vendor master list';

-- ============================================================================
-- TABLE: products
-- Master product catalog. Links to category, UOM, and supplier.
-- ============================================================================
CREATE TABLE IF NOT EXISTS products (
    product_id          CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    category_id         SMALLINT        NOT NULL                              COMMENT 'FK → inventory_categories.category_id',
    uom_id              SMALLINT        NOT NULL                              COMMENT 'FK → units_of_measure.uom_id (base UOM)',
    preferred_supplier_id CHAR(36)      NULL                                  COMMENT 'FK → suppliers.supplier_id',
    product_code        VARCHAR(50)     NOT NULL,
    barcode             VARCHAR(100)    NULL,
    product_name        VARCHAR(200)    NOT NULL,
    brand               VARCHAR(100)    NULL,
    description         TEXT            NULL,
    product_type        ENUM('consumable','non_consumable','food','beverage','amenity','cleaning','linen','equipment','other')
                                        NOT NULL DEFAULT 'consumable',
    -- Costing
    unit_cost           DECIMAL(12,2)   NOT NULL DEFAULT 0.00                 COMMENT 'Latest purchase cost per base UOM',
    selling_price       DECIMAL(12,2)   NOT NULL DEFAULT 0.00                 COMMENT 'Selling price (for canteen/POS items)',
    -- Stock control
    current_stock       DECIMAL(12,3)   NOT NULL DEFAULT 0.000                COMMENT 'Live qty — updated by trigger or app on each transaction',
    reorder_point       DECIMAL(12,3)   NOT NULL DEFAULT 0.000                COMMENT 'Alert when stock falls below this',
    minimum_stock       DECIMAL(12,3)   NOT NULL DEFAULT 0.000,
    maximum_stock       DECIMAL(12,3)   NULL,
    -- Images
    product_image_url   VARCHAR(500)    NULL,
    -- Flags
    is_for_sale         TINYINT(1)      NOT NULL DEFAULT 0                    COMMENT '1 = available in canteen/POS',
    is_for_rooms        TINYINT(1)      NOT NULL DEFAULT 0                    COMMENT '1 = used as room amenity/supply',
    track_inventory     TINYINT(1)      NOT NULL DEFAULT 1                    COMMENT '0 = non-stocked item (services)',
    is_active           TINYINT(1)      NOT NULL DEFAULT 1,
    created_by          CHAR(36)        NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (product_id),
    UNIQUE KEY uk_product_code (product_code),
    KEY idx_category_id (category_id),
    KEY idx_uom_id (uom_id),
    KEY idx_product_type (product_type),
    KEY idx_is_for_sale (is_for_sale),
    KEY idx_is_active (is_active),
    KEY idx_reorder (current_stock, reorder_point),
    CONSTRAINT fk_prod_category_id FOREIGN KEY (category_id) REFERENCES inventory_categories(category_id) ON UPDATE CASCADE,
    CONSTRAINT fk_prod_uom_id FOREIGN KEY (uom_id) REFERENCES units_of_measure(uom_id) ON UPDATE CASCADE,
    CONSTRAINT fk_prod_supplier_id FOREIGN KEY (preferred_supplier_id) REFERENCES suppliers(supplier_id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Product master catalog — consumables, F&B, amenities, equipment';

-- ============================================================================
-- TABLE: product_stock_per_location
-- Tracks how much of each product is in each physical location
-- ============================================================================
CREATE TABLE IF NOT EXISTS product_stock_per_location (
    stock_loc_id    INT             NOT NULL AUTO_INCREMENT                   COMMENT 'PK',
    product_id      CHAR(36)        NOT NULL                                  COMMENT 'FK → products.product_id',
    location_id     SMALLINT        NOT NULL                                  COMMENT 'FK → inventory_locations.location_id',
    quantity        DECIMAL(12,3)   NOT NULL DEFAULT 0.000,
    updated_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (stock_loc_id),
    UNIQUE KEY uk_product_location (product_id, location_id),
    KEY idx_product_id (product_id),
    KEY idx_location_id (location_id),
    CONSTRAINT fk_psl_product_id FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_psl_location_id FOREIGN KEY (location_id) REFERENCES inventory_locations(location_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Per-location stock quantities for multi-stockroom management';

-- ============================================================================
-- TABLE: purchase_orders
-- PO header: who, when, which supplier, status
-- ============================================================================
CREATE TABLE IF NOT EXISTS purchase_orders (
    po_id               CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    property_id         CHAR(36)        NOT NULL                              COMMENT 'FK → properties.property_id',
    supplier_id         CHAR(36)        NOT NULL                              COMMENT 'FK → suppliers.supplier_id',
    po_number           VARCHAR(30)     NOT NULL                              COMMENT 'e.g. PO-2024-000001',
    po_date             DATE            NOT NULL,
    expected_delivery   DATE            NULL,
    actual_delivery     DATE            NULL,
    po_status           ENUM(
                            'draft',
                            'submitted',
                            'approved',
                            'partially_received',
                            'fully_received',
                            'cancelled',
                            'closed'
                        )               NOT NULL DEFAULT 'draft',
    subtotal            DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    tax_amount          DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    shipping_fee        DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    discount_amount     DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    total_amount        DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    amount_paid         DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    balance             DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    payment_status      ENUM('unpaid','partial','paid')                       NOT NULL DEFAULT 'unpaid',
    payment_terms       VARCHAR(100)    NULL,
    delivery_address    TEXT            NULL,
    notes               TEXT            NULL,
    approved_by         CHAR(36)        NULL,
    approved_at         DATETIME        NULL,
    created_by          CHAR(36)        NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (po_id),
    UNIQUE KEY uk_po_number (po_number),
    KEY idx_property_id (property_id),
    KEY idx_supplier_id (supplier_id),
    KEY idx_po_status (po_status),
    KEY idx_po_date (po_date),
    KEY idx_payment_status (payment_status),
    CONSTRAINT fk_po_property_id FOREIGN KEY (property_id) REFERENCES properties(property_id) ON UPDATE CASCADE,
    CONSTRAINT fk_po_supplier_id FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Purchase order headers — draft, approved, received lifecycle';

CREATE TABLE IF NOT EXISTS purchase_order_items (
    po_item_id          INT             NOT NULL AUTO_INCREMENT               COMMENT 'PK',
    po_id               CHAR(36)        NOT NULL                              COMMENT 'FK → purchase_orders.po_id',
    product_id          CHAR(36)        NOT NULL                              COMMENT 'FK → products.product_id',
    uom_id              SMALLINT        NOT NULL                              COMMENT 'FK → units_of_measure.uom_id',
    ordered_qty         DECIMAL(12,3)   NOT NULL,
    received_qty        DECIMAL(12,3)   NOT NULL DEFAULT 0.000,
    unit_cost           DECIMAL(12,2)   NOT NULL,
    total_cost          DECIMAL(12,2)   NOT NULL,
    item_status         ENUM('pending','partially_received','fully_received','cancelled')
                                        NOT NULL DEFAULT 'pending',
    notes               VARCHAR(255)    NULL,
    PRIMARY KEY (po_item_id),
    KEY idx_po_id (po_id),
    KEY idx_product_id (product_id),
    CONSTRAINT fk_poi_po_id FOREIGN KEY (po_id) REFERENCES purchase_orders(po_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_poi_product_id FOREIGN KEY (product_id) REFERENCES products(product_id) ON UPDATE CASCADE,
    CONSTRAINT fk_poi_uom_id FOREIGN KEY (uom_id) REFERENCES units_of_measure(uom_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Line items per purchase order with received quantity tracking';

-- ============================================================================
-- TABLE: po_payments
-- Payments made to suppliers for purchase orders
-- ============================================================================
CREATE TABLE IF NOT EXISTS po_payments (
    po_payment_id       CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    po_id               CHAR(36)        NOT NULL                              COMMENT 'FK → purchase_orders.po_id',
    method_id           SMALLINT        NULL                                  COMMENT 'FK → payment_methods.method_id',
    amount              DECIMAL(12,2)   NOT NULL,
    reference_number    VARCHAR(100)    NULL,
    payment_date        DATE            NOT NULL,
    notes               TEXT            NULL,
    processed_by        CHAR(36)        NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (po_payment_id),
    KEY idx_po_id (po_id),
    CONSTRAINT fk_pop_po_id FOREIGN KEY (po_id) REFERENCES purchase_orders(po_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Payments to suppliers per purchase order';

-- ============================================================================
-- TABLE: stock_transactions
-- THE SINGLE SOURCE OF TRUTH for all stock movements.
-- Every stock in, stock out, adjustment, transfer writes one row here.
-- products.current_stock should be recomputed from this table.
-- ============================================================================
CREATE TABLE IF NOT EXISTS stock_transactions (
    txn_id              CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    product_id          CHAR(36)        NOT NULL                              COMMENT 'FK → products.product_id',
    location_id         SMALLINT        NULL                                  COMMENT 'FK → inventory_locations.location_id',
    transaction_type    ENUM(
                            'stock_in',         -- receiving from PO
                            'stock_in_manual',  -- manual addition / opening balance
                            'stock_out_sale',   -- sold via canteen/POS
                            'stock_out_use',    -- consumed (housekeeping, rooms)
                            'stock_out_damage', -- spoilage, breakage
                            'adjustment_add',   -- positive inventory count adjustment
                            'adjustment_deduct',-- negative adjustment
                            'transfer_out',     -- moved to another location
                            'transfer_in',      -- received from another location
                            'return_to_supplier'
                        )               NOT NULL,
    quantity            DECIMAL(12,3)   NOT NULL                              COMMENT 'Always positive; direction in transaction_type',
    uom_id              SMALLINT        NOT NULL                              COMMENT 'FK → units_of_measure.uom_id',
    unit_cost           DECIMAL(12,2)   NULL                                  COMMENT 'Cost at time of transaction (for stock_in)',
    total_cost          DECIMAL(12,2)   NULL,
    stock_before        DECIMAL(12,3)   NOT NULL DEFAULT 0.000                COMMENT 'Stock snapshot before this transaction',
    stock_after         DECIMAL(12,3)   NOT NULL DEFAULT 0.000                COMMENT 'Stock snapshot after this transaction',
    -- Source reference (which module triggered this)
    reference_type      VARCHAR(50)     NULL                                  COMMENT 'purchase_order, food_order, manual, reservation, transfer',
    reference_id        CHAR(36)        NULL                                  COMMENT 'FK to source record',
    reference_number    VARCHAR(100)    NULL,
    transaction_date    DATE            NOT NULL,
    expiry_date         DATE            NULL                                  COMMENT 'For perishable items',
    batch_number        VARCHAR(50)     NULL,
    notes               TEXT            NULL,
    created_by          CHAR(36)        NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (txn_id),
    KEY idx_product_id (product_id),
    KEY idx_location_id (location_id),
    KEY idx_transaction_type (transaction_type),
    KEY idx_transaction_date (transaction_date),
    KEY idx_reference (reference_type, reference_id),
    KEY idx_prod_date (product_id, transaction_date),
    CONSTRAINT fk_st_product_id FOREIGN KEY (product_id) REFERENCES products(product_id) ON UPDATE CASCADE,
    CONSTRAINT fk_st_location_id FOREIGN KEY (location_id) REFERENCES inventory_locations(location_id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_st_uom_id FOREIGN KEY (uom_id) REFERENCES units_of_measure(uom_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='General ledger for all stock movements — immutable source of truth';

-- ============================================================================
-- TABLE: stock_transfer_headers
-- When stock moves between two locations, links the two stock_transactions
-- ============================================================================
CREATE TABLE IF NOT EXISTS stock_transfers (
    transfer_id         CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    from_location_id    SMALLINT        NOT NULL,
    to_location_id      SMALLINT        NOT NULL,
    transfer_date       DATE            NOT NULL,
    transfer_status     ENUM('pending','in_transit','completed','cancelled')  NOT NULL DEFAULT 'completed',
    notes               TEXT            NULL,
    created_by          CHAR(36)        NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (transfer_id),
    KEY idx_from_location (from_location_id),
    KEY idx_to_location (to_location_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Stock transfer header linking two location movements';

CREATE TABLE IF NOT EXISTS stock_transfer_items (
    transfer_item_id    INT             NOT NULL AUTO_INCREMENT               COMMENT 'PK',
    transfer_id         CHAR(36)        NOT NULL,
    product_id          CHAR(36)        NOT NULL,
    quantity            DECIMAL(12,3)   NOT NULL,
    uom_id              SMALLINT        NOT NULL,
    out_txn_id          CHAR(36)        NULL                                  COMMENT 'FK → stock_transactions.txn_id (transfer_out)',
    in_txn_id           CHAR(36)        NULL                                  COMMENT 'FK → stock_transactions.txn_id (transfer_in)',
    PRIMARY KEY (transfer_item_id),
    KEY idx_transfer_id (transfer_id),
    KEY idx_product_id (product_id),
    CONSTRAINT fk_sti_transfer_id FOREIGN KEY (transfer_id) REFERENCES stock_transfers(transfer_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Products and quantities per stock transfer';

-- ============================================================================
-- TABLE: inventory_sales
-- Records inventory items sold directly (canteen/POS walk-in, not via food_orders)
-- Links to stock_transactions for stock deduction.
-- ============================================================================
CREATE TABLE IF NOT EXISTS inventory_sales (
    sale_id             CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    property_id         CHAR(36)        NOT NULL,
    sale_number         VARCHAR(30)     NOT NULL,
    sale_date           DATE            NOT NULL,
    sale_type           ENUM('pos','canteen','room_charge','staff_purchase')  NOT NULL DEFAULT 'pos',
    reservation_id      CHAR(36)        NULL,
    customer_name       VARCHAR(150)    NULL,
    subtotal            DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    discount_amount     DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    tax_amount          DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    total_amount        DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    payment_method_id   SMALLINT        NULL,
    payment_status      ENUM('unpaid','paid','charged_to_room')               NOT NULL DEFAULT 'paid',
    notes               TEXT            NULL,
    created_by          CHAR(36)        NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (sale_id),
    UNIQUE KEY uk_sale_number (sale_number),
    KEY idx_property_id (property_id),
    KEY idx_sale_date (sale_date),
    KEY idx_sale_type (sale_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Direct inventory sales — POS, canteen, room charges';

CREATE TABLE IF NOT EXISTS inventory_sale_items (
    sale_item_id    INT             NOT NULL AUTO_INCREMENT                   COMMENT 'PK',
    sale_id         CHAR(36)        NOT NULL,
    product_id      CHAR(36)        NOT NULL,
    uom_id          SMALLINT        NOT NULL,
    quantity        DECIMAL(12,3)   NOT NULL,
    unit_price      DECIMAL(12,2)   NOT NULL,
    unit_cost       DECIMAL(12,2)   NOT NULL DEFAULT 0.00                     COMMENT 'Cost at time of sale for margin calc',
    total_price     DECIMAL(12,2)   NOT NULL,
    txn_id          CHAR(36)        NULL                                      COMMENT 'FK → stock_transactions.txn_id (the deduction)',
    PRIMARY KEY (sale_item_id),
    KEY idx_sale_id (sale_id),
    KEY idx_product_id (product_id),
    CONSTRAINT fk_isi_sale_id FOREIGN KEY (sale_id) REFERENCES inventory_sales(sale_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_isi_product_id FOREIGN KEY (product_id) REFERENCES products(product_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Line items per inventory sale transaction';

SELECT '06_inventory.sql — OK' AS migration_status;
