-- ============================================================================
-- RESORT / HOTEL RESERVATION SYSTEM
-- Module 05: Food & Canteen Management
-- Database: MySQL 8.0+  |  Engine: InnoDB  |  Charset: utf8mb4
-- ============================================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS food_categories (
    category_id     SMALLINT        NOT NULL AUTO_INCREMENT                   COMMENT 'PK',
    property_id     CHAR(36)        NULL                                      COMMENT 'NULL = all properties',
    category_name   VARCHAR(80)     NOT NULL                                  COMMENT 'e.g. Breakfast, Lunch, Snacks, Beverages, Grilled',
    description     VARCHAR(255)    NULL,
    icon_url        VARCHAR(500)    NULL,
    sort_order      SMALLINT        NOT NULL DEFAULT 0,
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (category_id),
    KEY idx_property_id (property_id),
    KEY idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Food/menu categories — Breakfast, Lunch, Snacks, Beverages, etc.';

CREATE TABLE IF NOT EXISTS menu_items (
    item_id         CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    property_id     CHAR(36)        NULL                                      COMMENT 'FK → properties.property_id (NULL = all)',
    category_id     SMALLINT        NOT NULL                                  COMMENT 'FK → food_categories.category_id',
    item_code       VARCHAR(30)     NULL,
    item_name       VARCHAR(150)    NOT NULL,
    description     TEXT            NULL,
    price           DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    cost_price      DECIMAL(10,2)   NOT NULL DEFAULT 0.00                     COMMENT 'For profit margin tracking',
    is_available    TINYINT(1)      NOT NULL DEFAULT 1                        COMMENT '1 = available to order today',
    is_featured     TINYINT(1)      NOT NULL DEFAULT 0,
    sort_order      SMALLINT        NOT NULL DEFAULT 0,
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    created_by      CHAR(36)        NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (item_id),
    KEY idx_category_id (category_id),
    KEY idx_is_available (is_available),
    KEY idx_is_active (is_active),
    CONSTRAINT fk_mi_category_id FOREIGN KEY (category_id) REFERENCES food_categories(category_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Menu items with pricing and availability';

CREATE TABLE IF NOT EXISTS menu_item_images (
    image_id        CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    item_id         CHAR(36)        NOT NULL                                  COMMENT 'FK → menu_items.item_id',
    image_url       VARCHAR(500)    NOT NULL,
    thumbnail_url   VARCHAR(500)    NULL,
    is_cover        TINYINT(1)      NOT NULL DEFAULT 0,
    sort_order      SMALLINT        NOT NULL DEFAULT 0,
    uploaded_by     CHAR(36)        NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (image_id),
    KEY idx_item_id (item_id),
    CONSTRAINT fk_mii_item_id FOREIGN KEY (item_id) REFERENCES menu_items(item_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Images per menu item';

-- ============================================================================
-- TABLE: food_orders
-- Order placed by a guest, linked to a reservation or standalone (walk-in).
-- ============================================================================
CREATE TABLE IF NOT EXISTS food_orders (
    order_id            CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    property_id         CHAR(36)        NOT NULL                              COMMENT 'FK → properties.property_id',
    reservation_id      CHAR(36)        NULL                                  COMMENT 'FK → reservations.reservation_id (NULL = walk-in customer)',
    order_number        VARCHAR(20)     NOT NULL                              COMMENT 'e.g. FO-20240001',
    order_type          ENUM('dine_in','room_service','takeout')              NOT NULL DEFAULT 'dine_in',
    table_number        VARCHAR(20)     NULL,
    room_id             CHAR(36)        NULL                                  COMMENT 'FK → rooms.room_id (for room service)',
    customer_name       VARCHAR(150)    NULL,
    order_status        ENUM('pending','confirmed','preparing','ready','delivered','cancelled')
                                        NOT NULL DEFAULT 'pending',
    subtotal            DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    discount_amount     DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    tax_amount          DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    total_amount        DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    payment_status      ENUM('unpaid','charged_to_room','paid')               NOT NULL DEFAULT 'unpaid',
    payment_method_id   SMALLINT        NULL,
    notes               TEXT            NULL,
    served_by           CHAR(36)        NULL                                  COMMENT 'FK → user_accounts.user_id (staff)',
    created_by          CHAR(36)        NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (order_id),
    UNIQUE KEY uk_order_number (order_number),
    KEY idx_property_id (property_id),
    KEY idx_reservation_id (reservation_id),
    KEY idx_order_status (order_status),
    KEY idx_payment_status (payment_status),
    KEY idx_created_at (created_at),
    CONSTRAINT fk_fo_property_id FOREIGN KEY (property_id) REFERENCES properties(property_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Food/canteen orders — room service, dine-in, takeout';

CREATE TABLE IF NOT EXISTS food_order_items (
    order_item_id   CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    order_id        CHAR(36)        NOT NULL                                  COMMENT 'FK → food_orders.order_id',
    item_id         CHAR(36)        NOT NULL                                  COMMENT 'FK → menu_items.item_id',
    item_name       VARCHAR(150)    NOT NULL                                  COMMENT 'Snapshot of item name at order time',
    quantity        SMALLINT        NOT NULL DEFAULT 1,
    unit_price      DECIMAL(10,2)   NOT NULL,
    total_price     DECIMAL(12,2)   NOT NULL,
    special_request VARCHAR(255)    NULL,
    PRIMARY KEY (order_item_id),
    KEY idx_order_id (order_id),
    KEY idx_item_id (item_id),
    CONSTRAINT fk_foi_order_id FOREIGN KEY (order_id) REFERENCES food_orders(order_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_foi_item_id FOREIGN KEY (item_id) REFERENCES menu_items(item_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Line items per food order';

SELECT '05_food_canteen.sql — OK' AS migration_status;
