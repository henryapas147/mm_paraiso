-- ============================================================================
-- RESORT / HOTEL RESERVATION SYSTEM
-- Module 04: Payments & Billing
-- Database: MySQL 8.0+  |  Engine: InnoDB  |  Charset: utf8mb4
-- ============================================================================

SET NAMES utf8mb4;

-- ============================================================================
-- TABLE: payment_methods
-- Master list of accepted payment methods
-- ============================================================================
CREATE TABLE IF NOT EXISTS payment_methods (
    method_id       SMALLINT        NOT NULL AUTO_INCREMENT                   COMMENT 'PK',
    method_code     VARCHAR(30)     NOT NULL                                  COMMENT 'cash, gcash, maya, bdo, bpi, credit_card, debit_card, bank_transfer, check',
    method_label    VARCHAR(60)     NOT NULL,
    method_type     ENUM('cash','digital_wallet','bank_transfer','card','check','other')
                                    NOT NULL DEFAULT 'cash',
    icon_url        VARCHAR(500)    NULL,
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    sort_order      SMALLINT        NOT NULL DEFAULT 0,
    PRIMARY KEY (method_id),
    UNIQUE KEY uk_method_code (method_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Master list of accepted payment methods';

-- ============================================================================
-- TABLE: payments
-- Every payment transaction — partial, full, refund, void.
-- One reservation can have multiple payment rows (installment/partial support).
-- ============================================================================
CREATE TABLE IF NOT EXISTS payments (
    payment_id          CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    reservation_id      CHAR(36)        NOT NULL                              COMMENT 'FK → reservations.reservation_id',
    method_id           SMALLINT        NOT NULL                              COMMENT 'FK → payment_methods.method_id',
    transaction_type    ENUM('payment','partial_payment','refund','adjustment','void')
                                        NOT NULL DEFAULT 'payment',
    amount              DECIMAL(12,2)   NOT NULL,
    reference_number    VARCHAR(100)    NULL                                  COMMENT 'GCash ref #, bank ref #, receipt #, etc.',
    proof_image_url     VARCHAR(500)    NULL                                  COMMENT 'Screenshot/photo of payment proof',
    payment_status      ENUM('pending','confirmed','rejected','refunded','voided')
                                        NOT NULL DEFAULT 'pending',
    payment_date        DATE            NOT NULL,
    payment_time        TIME            NULL,
    notes               TEXT            NULL,
    -- Staff who processed / confirmed
    processed_by        CHAR(36)        NULL                                  COMMENT 'FK → user_accounts.user_id',
    confirmed_by        CHAR(36)        NULL,
    confirmed_at        DATETIME        NULL,
    voided_by           CHAR(36)        NULL,
    voided_at           DATETIME        NULL,
    void_reason         VARCHAR(255)    NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (payment_id),
    KEY idx_reservation_id (reservation_id),
    KEY idx_method_id (method_id),
    KEY idx_payment_status (payment_status),
    KEY idx_transaction_type (transaction_type),
    KEY idx_payment_date (payment_date),
    CONSTRAINT fk_pay_reservation_id FOREIGN KEY (reservation_id) REFERENCES reservations(reservation_id) ON UPDATE CASCADE,
    CONSTRAINT fk_pay_method_id FOREIGN KEY (method_id) REFERENCES payment_methods(method_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Payment transactions — supports partial payments, refunds, and voids';

-- ============================================================================
-- TABLE: invoices
-- Official receipts / invoices per reservation
-- ============================================================================
CREATE TABLE IF NOT EXISTS invoices (
    invoice_id          CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    reservation_id      CHAR(36)        NOT NULL                              COMMENT 'FK → reservations.reservation_id',
    invoice_number      VARCHAR(30)     NOT NULL                              COMMENT 'e.g. OR-2024-000001',
    invoice_type        ENUM('official_receipt','proforma','billing_statement','refund_receipt')
                                        NOT NULL DEFAULT 'official_receipt',
    invoice_date        DATE            NOT NULL,
    due_date            DATE            NULL,
    subtotal            DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    discount_amount     DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    tax_amount          DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    total_amount        DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    amount_paid         DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    balance             DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    status              ENUM('draft','sent','paid','partial','overdue','void') NOT NULL DEFAULT 'draft',
    notes               TEXT            NULL,
    generated_by        CHAR(36)        NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (invoice_id),
    UNIQUE KEY uk_invoice_number (invoice_number),
    KEY idx_reservation_id (reservation_id),
    KEY idx_status (status),
    KEY idx_invoice_date (invoice_date),
    CONSTRAINT fk_inv_reservation_id FOREIGN KEY (reservation_id) REFERENCES reservations(reservation_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Official receipts and invoices per reservation';

-- ============================================================================
-- TABLE: invoice_line_items
-- Breakdown of charges per invoice
-- ============================================================================
CREATE TABLE IF NOT EXISTS invoice_line_items (
    line_item_id    CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    invoice_id      CHAR(36)        NOT NULL                                  COMMENT 'FK → invoices.invoice_id',
    description     VARCHAR(255)    NOT NULL,
    item_type       ENUM('room_charge','extra_guest','food_order','service','discount','tax','other')
                                    NOT NULL DEFAULT 'room_charge',
    quantity        DECIMAL(8,2)    NOT NULL DEFAULT 1.00,
    unit_price      DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    total_price     DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    reference_id    CHAR(36)        NULL                                      COMMENT 'Optional: res_room_id, food_order_id, etc.',
    sort_order      SMALLINT        NOT NULL DEFAULT 0,
    PRIMARY KEY (line_item_id),
    KEY idx_invoice_id (invoice_id),
    KEY idx_item_type (item_type),
    CONSTRAINT fk_ili_invoice_id FOREIGN KEY (invoice_id) REFERENCES invoices(invoice_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Individual line items / charges per invoice';

-- ============================================================================
-- TABLE: discounts
-- Admin-defined discount codes, promos, and seasonal deals
-- ============================================================================
CREATE TABLE IF NOT EXISTS discounts (
    discount_id         CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    property_id         CHAR(36)        NULL                                  COMMENT 'NULL = applies to all properties',
    discount_code       VARCHAR(30)     NOT NULL,
    discount_name       VARCHAR(100)    NOT NULL,
    description         TEXT            NULL,
    discount_type       ENUM('percentage','fixed_amount','free_night','upgrade')
                                        NOT NULL DEFAULT 'percentage',
    discount_value      DECIMAL(10,2)   NOT NULL DEFAULT 0.00                 COMMENT '% value or fixed PHP amount',
    min_nights          TINYINT(3)      NULL                                  COMMENT 'Minimum nights required to qualify',
    min_booking_amount  DECIMAL(10,2)   NULL,
    max_discount_amount DECIMAL(10,2)   NULL                                  COMMENT 'Cap on discount (for percentage types)',
    applies_to          SET('overnight','hourly','day_use') NOT NULL DEFAULT 'overnight,hourly,day_use',
    usage_limit         INT             NULL                                  COMMENT 'NULL = unlimited uses',
    usage_count         INT             NOT NULL DEFAULT 0,
    per_user_limit      TINYINT(3)      NULL                                  COMMENT 'Times one user can use this code',
    valid_from          DATE            NOT NULL,
    valid_until         DATE            NOT NULL,
    is_active           TINYINT(1)      NOT NULL DEFAULT 1,
    created_by          CHAR(36)        NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (discount_id),
    UNIQUE KEY uk_discount_code (discount_code),
    KEY idx_property_id (property_id),
    KEY idx_is_active (is_active),
    KEY idx_valid_range (valid_from, valid_until)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Discount codes, promos, and deals — admin configurable';

CREATE TABLE IF NOT EXISTS discount_usages (
    usage_id        CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    discount_id     CHAR(36)        NOT NULL                                  COMMENT 'FK → discounts.discount_id',
    reservation_id  CHAR(36)        NOT NULL                                  COMMENT 'FK → reservations.reservation_id',
    user_id         CHAR(36)        NULL                                      COMMENT 'FK → user_accounts.user_id',
    amount_saved    DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    applied_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (usage_id),
    KEY idx_discount_id (discount_id),
    KEY idx_reservation_id (reservation_id),
    KEY idx_user_id (user_id),
    CONSTRAINT fk_du_discount_id FOREIGN KEY (discount_id) REFERENCES discounts(discount_id) ON UPDATE CASCADE,
    CONSTRAINT fk_du_reservation_id FOREIGN KEY (reservation_id) REFERENCES reservations(reservation_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Tracks which discount code was used per reservation';

-- ============================================================================
-- TABLE: extra_charges
-- Ad-hoc charges added to a reservation: damage fee, extra towels, etc.
-- ============================================================================
CREATE TABLE IF NOT EXISTS extra_charges (
    charge_id       CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    reservation_id  CHAR(36)        NOT NULL                                  COMMENT 'FK → reservations.reservation_id',
    charge_type     ENUM('damage','late_checkout','extra_amenity','cleaning','extension','other')
                                    NOT NULL DEFAULT 'other',
    description     VARCHAR(255)    NOT NULL,
    amount          DECIMAL(10,2)   NOT NULL,
    charged_by      CHAR(36)        NULL                                      COMMENT 'FK → user_accounts.user_id (staff)',
    charged_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    notes           TEXT            NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (charge_id),
    KEY idx_reservation_id (reservation_id),
    KEY idx_charge_type (charge_type),
    CONSTRAINT fk_ec_reservation_id FOREIGN KEY (reservation_id) REFERENCES reservations(reservation_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Extra/ad-hoc charges on a reservation (damage, late check-out, etc.)';

SELECT '04_payments_billing.sql — OK' AS migration_status;
