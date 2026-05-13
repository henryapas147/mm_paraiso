-- ============================================================================
-- RESORT / HOTEL RESERVATION SYSTEM
-- Module 07: Settings, Pricing Rules, Promotions & Notifications
-- Database: MySQL 8.0+  |  Engine: InnoDB  |  Charset: utf8mb4
-- ============================================================================

SET NAMES utf8mb4;

-- ============================================================================
-- TABLE: system_settings
-- Key-value config store. Admin can update values via settings page.
-- ============================================================================
CREATE TABLE IF NOT EXISTS system_settings (
    setting_id      SMALLINT        NOT NULL AUTO_INCREMENT                   COMMENT 'PK',
    property_id     CHAR(36)        NULL                                      COMMENT 'NULL = global setting',
    setting_group   VARCHAR(50)     NOT NULL                                  COMMENT 'booking, payment, notifications, display, security',
    setting_key     VARCHAR(100)    NOT NULL,
    setting_label   VARCHAR(150)    NULL,
    setting_value   TEXT            NULL,
    data_type       ENUM('string','integer','decimal','boolean','json','time','date')
                                    NOT NULL DEFAULT 'string',
    is_public       TINYINT(1)      NOT NULL DEFAULT 0                        COMMENT '1 = visible in mobile app API response',
    description     TEXT            NULL,
    updated_by      CHAR(36)        NULL,
    updated_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (setting_id),
    UNIQUE KEY uk_setting_key (property_id, setting_key),
    KEY idx_setting_group (setting_group),
    KEY idx_is_public (is_public)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Key-value system configuration — booking rules, payment settings, etc.';

-- ============================================================================
-- TABLE: pricing_rules
-- Dynamic pricing: weekend rates, holiday surcharges, minimum stay, etc.
-- ============================================================================
CREATE TABLE IF NOT EXISTS pricing_rules (
    rule_id         CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    property_id     CHAR(36)        NOT NULL,
    category_id     CHAR(36)        NULL                                      COMMENT 'NULL = applies to all categories',
    room_id         CHAR(36)        NULL                                      COMMENT 'Room-specific rule (overrides category)',
    rule_name       VARCHAR(100)    NOT NULL                                  COMMENT 'e.g. Weekend Rate, Holiday Surcharge',
    rule_type       ENUM(
                        'fixed_price',        -- set a flat price
                        'percentage_add',     -- add X% to base price
                        'percentage_discount',-- reduce X% from base price
                        'fixed_add',          -- add fixed amount
                        'minimum_stay'        -- require min N nights
                    )               NOT NULL,
    applies_to      ENUM('overnight','hourly','day_use','all')  NOT NULL DEFAULT 'all',
    rule_value      DECIMAL(10,2)   NOT NULL DEFAULT 0.00                     COMMENT 'Percentage or fixed amount depending on rule_type',
    minimum_nights  TINYINT(3)      NULL,
    -- When rule applies
    day_of_week     SET('mon','tue','wed','thu','fri','sat','sun')  NULL       COMMENT 'NULL = every day',
    date_start      DATE            NULL,
    date_end        DATE            NULL,
    priority        TINYINT(3)      NOT NULL DEFAULT 0                        COMMENT 'Higher = takes precedence when multiple rules match',
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    created_by      CHAR(36)        NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (rule_id),
    KEY idx_property_id (property_id),
    KEY idx_category_id (category_id),
    KEY idx_is_active (is_active),
    KEY idx_date_range (date_start, date_end)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Dynamic pricing rules — weekends, holidays, peak season, min-stay';

-- ============================================================================
-- TABLE: seasonal_rates
-- Named seasons with start/end dates and rate multipliers or overrides
-- ============================================================================
CREATE TABLE IF NOT EXISTS seasonal_rates (
    season_id       CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    property_id     CHAR(36)        NOT NULL,
    season_name     VARCHAR(100)    NOT NULL                                  COMMENT 'e.g. Summer 2024, Holy Week, Christmas Break',
    date_start      DATE            NOT NULL,
    date_end        DATE            NOT NULL,
    rate_multiplier DECIMAL(5,3)    NOT NULL DEFAULT 1.000                    COMMENT '1.5 = 50% more, 0.8 = 20% discount',
    applies_to      CHAR(36)        NULL                                      COMMENT 'NULL = all categories, or specific category_id',
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    created_by      CHAR(36)        NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (season_id),
    KEY idx_property_id (property_id),
    KEY idx_date_range (date_start, date_end)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Named seasonal rate multipliers — peak, off-peak, holiday seasons';

-- ============================================================================
-- TABLE: booking_policies
-- Cancellation windows, deposit requirements, late checkout rules
-- ============================================================================
CREATE TABLE IF NOT EXISTS booking_policies (
    policy_id       CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    property_id     CHAR(36)        NULL                                      COMMENT 'NULL = all properties',
    category_id     CHAR(36)        NULL                                      COMMENT 'NULL = all categories',
    policy_type     ENUM(
                        'cancellation',
                        'deposit',
                        'no_show',
                        'early_checkout',
                        'late_checkout',
                        'minimum_stay',
                        'advance_booking',
                        'child_policy',
                        'pet_policy'
                    )               NOT NULL,
    policy_name     VARCHAR(100)    NOT NULL,
    description     TEXT            NOT NULL,
    -- Cancellation fields
    free_cancel_hours   INT         NULL                                      COMMENT 'Hours before arrival; free cancel allowed',
    penalty_type    ENUM('percentage','fixed','first_night','full_stay')  NULL,
    penalty_value   DECIMAL(10,2)   NULL,
    -- Deposit fields
    deposit_type    ENUM('percentage','fixed','first_night')  NULL,
    deposit_value   DECIMAL(10,2)   NULL,
    -- Min stay fields
    min_nights      TINYINT(3)      NULL,
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    created_by      CHAR(36)        NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (policy_id),
    KEY idx_property_id (property_id),
    KEY idx_policy_type (policy_type),
    KEY idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Booking, cancellation, deposit, and stay policies';

-- ============================================================================
-- TABLE: promotions
-- Promos visible in the mobile app — banners, seasonal deals, packages
-- ============================================================================
CREATE TABLE IF NOT EXISTS promotions (
    promo_id        CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    property_id     CHAR(36)        NULL,
    promo_code      VARCHAR(30)     NULL                                      COMMENT 'Discount code customers enter (optional)',
    promo_title     VARCHAR(150)    NOT NULL,
    promo_type      ENUM('banner','package','seasonal_deal','referral','loyalty','voucher')
                                    NOT NULL DEFAULT 'banner',
    short_desc      VARCHAR(255)    NULL,
    full_description TEXT           NULL,
    terms_conditions TEXT           NULL,
    -- Linked discount
    discount_id     CHAR(36)        NULL                                      COMMENT 'FK → discounts.discount_id',
    -- Targeting
    applies_to_booking_type SET('overnight','hourly','day_use') NULL,
    -- Visibility
    valid_from      DATE            NOT NULL,
    valid_until     DATE            NOT NULL,
    is_featured     TINYINT(1)      NOT NULL DEFAULT 0,
    sort_order      SMALLINT        NOT NULL DEFAULT 0,
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    view_count      INT             NOT NULL DEFAULT 0,
    click_count     INT             NOT NULL DEFAULT 0,
    created_by      CHAR(36)        NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (promo_id),
    KEY idx_is_active (is_active),
    KEY idx_valid_range (valid_from, valid_until),
    KEY idx_is_featured (is_featured)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Promotions and deals displayed in the mobile app and website';

CREATE TABLE IF NOT EXISTS promotion_images (
    promo_image_id  CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    promo_id        CHAR(36)        NOT NULL,
    image_url       VARCHAR(500)    NOT NULL,
    thumbnail_url   VARCHAR(500)    NULL,
    image_type      ENUM('banner','thumbnail','detail','other') NOT NULL DEFAULT 'banner',
    sort_order      SMALLINT        NOT NULL DEFAULT 0,
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    uploaded_by     CHAR(36)        NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (promo_image_id),
    KEY idx_promo_id (promo_id),
    CONSTRAINT fk_pimg_promo_id FOREIGN KEY (promo_id) REFERENCES promotions(promo_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Images per promotion (banner, thumbnail, detail)';

-- ============================================================================
-- TABLE: notifications
-- Push/in-app notifications sent to customers and staff
-- ============================================================================
CREATE TABLE IF NOT EXISTS notifications (
    notif_id        CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    property_id     CHAR(36)        NULL,
    notif_type      ENUM(
                        'reservation_confirmed',
                        'reservation_cancelled',
                        'check_in_reminder',
                        'check_out_reminder',
                        'payment_received',
                        'payment_due',
                        'promo_announcement',
                        'system_alert',
                        'staff_alert',
                        'custom'
                    )               NOT NULL,
    title           VARCHAR(150)    NOT NULL,
    body            TEXT            NOT NULL,
    action_url      VARCHAR(500)    NULL                                      COMMENT 'Deep link or URL on tap',
    target_audience ENUM('all_customers','all_staff','specific_user','reservation_guests')
                                    NOT NULL DEFAULT 'specific_user',
    reference_type  VARCHAR(50)     NULL                                      COMMENT 'reservation, payment, promo, etc.',
    reference_id    CHAR(36)        NULL,
    scheduled_at    DATETIME        NULL                                      COMMENT 'NULL = send immediately',
    sent_at         DATETIME        NULL,
    status          ENUM('draft','scheduled','sent','failed','cancelled')     NOT NULL DEFAULT 'draft',
    created_by      CHAR(36)        NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (notif_id),
    KEY idx_notif_type (notif_type),
    KEY idx_status (status),
    KEY idx_scheduled_at (scheduled_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Notification templates and blast records';

CREATE TABLE IF NOT EXISTS notification_recipients (
    recipient_id    CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    notif_id        CHAR(36)        NOT NULL,
    user_id         CHAR(36)        NOT NULL,
    is_read         TINYINT(1)      NOT NULL DEFAULT 0,
    read_at         DATETIME        NULL,
    is_sent         TINYINT(1)      NOT NULL DEFAULT 0,
    sent_at         DATETIME        NULL,
    delivery_status ENUM('pending','delivered','failed')                      NOT NULL DEFAULT 'pending',
    error_message   VARCHAR(255)    NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (recipient_id),
    KEY idx_notif_id (notif_id),
    KEY idx_user_id (user_id),
    KEY idx_is_read (user_id, is_read),
    CONSTRAINT fk_nr_notif_id FOREIGN KEY (notif_id) REFERENCES notifications(notif_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Per-user delivery and read status for each notification';

SELECT '07_settings_promos_notifications.sql — OK' AS migration_status;
