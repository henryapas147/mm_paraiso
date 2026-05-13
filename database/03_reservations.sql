-- ============================================================================
-- RESORT / HOTEL RESERVATION SYSTEM
-- Module 03: Reservations & Booking
-- Database: MySQL 8.0+  |  Engine: InnoDB  |  Charset: utf8mb4
-- ============================================================================

SET NAMES utf8mb4;

-- ============================================================================
-- TABLE: reservations
-- Master reservation record. One reservation can cover multiple rooms.
-- Supports overnight, hourly, and day-use booking types.
-- ============================================================================
CREATE TABLE IF NOT EXISTS reservations (
    reservation_id          CHAR(36)        NOT NULL                          COMMENT 'PK – UUID v4',
    property_id             CHAR(36)        NOT NULL                          COMMENT 'FK → properties.property_id',
    reservation_code        VARCHAR(20)     NOT NULL                          COMMENT 'Human-readable code: RSV-20240001',
    -- Who booked (registered customer or walk-in)
    customer_user_id        CHAR(36)        NULL                              COMMENT 'FK → user_accounts.user_id (NULL if walk-in)',
    walkin_first_name       VARCHAR(80)     NULL                              COMMENT 'Walk-in guest first name',
    walkin_last_name        VARCHAR(80)     NULL,
    walkin_email            VARCHAR(180)    NULL,
    walkin_phone            VARCHAR(20)     NULL,
    -- Booking type & dates
    booking_type            ENUM('overnight','hourly','day_use')              NOT NULL DEFAULT 'overnight',
    check_in_date           DATE            NOT NULL,
    check_out_date          DATE            NULL                              COMMENT 'NULL for hourly-only bookings',
    check_in_time           TIME            NOT NULL,
    check_out_time          TIME            NULL,
    duration_hours          DECIMAL(5,2)    NULL                              COMMENT 'For hourly bookings',
    actual_check_in_at      DATETIME        NULL                              COMMENT 'Actual timestamp when guest checked in',
    actual_check_out_at     DATETIME        NULL,
    -- Guest composition
    num_adults              TINYINT(3)      NOT NULL DEFAULT 1,
    num_children            TINYINT(3)      NOT NULL DEFAULT 0,
    num_seniors             TINYINT(3)      NOT NULL DEFAULT 0,
    num_infants             TINYINT(3)      NOT NULL DEFAULT 0,
    -- Status
    reservation_status      ENUM(
                                'pending',
                                'confirmed',
                                'checked_in',
                                'checked_out',
                                'cancelled',
                                'no_show',
                                'waitlisted'
                            )               NOT NULL DEFAULT 'pending',
    source_channel          ENUM('website','mobile_app','walk_in','phone','ota','staff')
                                            NOT NULL DEFAULT 'website',
    -- Requests & notes
    special_requests        TEXT            NULL,
    internal_notes          TEXT            NULL,
    -- Cancellation
    cancellation_reason     TEXT            NULL,
    cancelled_at            DATETIME        NULL,
    cancelled_by            CHAR(36)        NULL                              COMMENT 'FK → user_accounts.user_id',
    -- Confirmation
    confirmed_at            DATETIME        NULL,
    confirmed_by            CHAR(36)        NULL,
    -- Financials (denormalized totals for fast reporting)
    subtotal_amount         DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    discount_amount         DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    tax_amount              DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    extra_charges_amount    DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    total_amount            DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    paid_amount             DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    balance_amount          DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    payment_status          ENUM('unpaid','partial','paid','refunded','void')
                                            NOT NULL DEFAULT 'unpaid',
    -- Requirement status
    requirements_complete   TINYINT(1)      NOT NULL DEFAULT 0                COMMENT '1 = all required guest info submitted',
    created_by              CHAR(36)        NULL,
    created_at              TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at              TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (reservation_id),
    UNIQUE KEY uk_reservation_code (reservation_code),
    KEY idx_property_id (property_id),
    KEY idx_customer_user_id (customer_user_id),
    KEY idx_reservation_status (reservation_status),
    KEY idx_payment_status (payment_status),
    KEY idx_check_in_date (check_in_date),
    KEY idx_booking_type (booking_type),
    KEY idx_source_channel (source_channel),
    KEY idx_prop_checkin (property_id, check_in_date),
    CONSTRAINT fk_res_property_id FOREIGN KEY (property_id) REFERENCES properties(property_id) ON UPDATE CASCADE,
    CONSTRAINT fk_res_customer FOREIGN KEY (customer_user_id) REFERENCES user_accounts(user_id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Master reservation — one record per booking, covers multiple rooms';

-- ============================================================================
-- TABLE: reservation_rooms
-- A single reservation can include multiple rooms.
-- Each room in the reservation has its own pricing and status.
-- ============================================================================
CREATE TABLE IF NOT EXISTS reservation_rooms (
    res_room_id             CHAR(36)        NOT NULL                          COMMENT 'PK – UUID v4',
    reservation_id          CHAR(36)        NOT NULL                          COMMENT 'FK → reservations.reservation_id',
    room_id                 CHAR(36)        NOT NULL                          COMMENT 'FK → rooms.room_id',
    category_id             CHAR(36)        NOT NULL                          COMMENT 'FK → room_categories.category_id (snapshot)',
    -- Pricing snapshot at time of booking
    booking_type            ENUM('overnight','hourly','day_use') NOT NULL,
    price_per_night         DECIMAL(10,2)   NOT NULL DEFAULT 0.00             COMMENT 'Rate applied at booking time',
    price_per_hour          DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    num_nights              TINYINT(3)      NOT NULL DEFAULT 1,
    num_hours               DECIMAL(5,2)    NULL,
    extra_adult_fee         DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    extra_child_fee         DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    extra_charges           DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    room_total              DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    -- Status per room
    room_status             ENUM('reserved','checked_in','checked_out','cancelled','no_show')
                                            NOT NULL DEFAULT 'reserved',
    actual_check_in_at      DATETIME        NULL,
    actual_check_out_at     DATETIME        NULL,
    notes                   TEXT            NULL,
    created_at              TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at              TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (res_room_id),
    UNIQUE KEY uk_reservation_room (reservation_id, room_id),
    KEY idx_reservation_id (reservation_id),
    KEY idx_room_id (room_id),
    KEY idx_room_status (room_status),
    CONSTRAINT fk_rr_reservation_id FOREIGN KEY (reservation_id) REFERENCES reservations(reservation_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_rr_room_id FOREIGN KEY (room_id) REFERENCES rooms(room_id) ON UPDATE CASCADE,
    CONSTRAINT fk_rr_category_id FOREIGN KEY (category_id) REFERENCES room_categories(category_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Rooms per reservation — supports multi-room booking';

-- ============================================================================
-- TABLE: reservation_guests
-- Individual guest records attached to a reservation.
-- Required info (e.g. valid ID) before reservation is fully confirmed.
-- ============================================================================
CREATE TABLE IF NOT EXISTS reservation_guests (
    res_guest_id        CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    reservation_id      CHAR(36)        NOT NULL                              COMMENT 'FK → reservations.reservation_id',
    user_id             CHAR(36)        NULL                                  COMMENT 'FK → user_accounts.user_id (if registered)',
    guest_type          ENUM('primary','companion','child','senior','infant') NOT NULL DEFAULT 'companion',
    first_name          VARCHAR(80)     NOT NULL,
    last_name           VARCHAR(80)     NOT NULL,
    date_of_birth       DATE            NULL,
    age                 TINYINT(3)      NULL                                  COMMENT 'Age at time of booking',
    gender              ENUM('male','female','other') NULL,
    nationality         VARCHAR(60)     NULL,
    id_type             VARCHAR(30)     NULL,
    id_number           VARCHAR(80)     NULL,
    id_image_url        VARCHAR(500)    NULL,
    special_needs       TEXT            NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (res_guest_id),
    KEY idx_reservation_id (reservation_id),
    KEY idx_user_id (user_id),
    KEY idx_guest_type (guest_type),
    CONSTRAINT fk_rg_reservation_id FOREIGN KEY (reservation_id) REFERENCES reservations(reservation_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Individual guests per reservation — primary guest + companions';

-- ============================================================================
-- TABLE: reservation_requirements
-- Document/form requirements per reservation (e.g. valid ID upload,
-- filled-up guest info form, signed waiver, medical clearance, etc.)
-- Admin configures which requirements are needed per booking type.
-- ============================================================================
CREATE TABLE IF NOT EXISTS reservation_requirement_types (
    req_type_id     SMALLINT        NOT NULL AUTO_INCREMENT                   COMMENT 'PK',
    property_id     CHAR(36)        NULL                                      COMMENT 'NULL = applies to all properties',
    req_code        VARCHAR(50)     NOT NULL,
    req_label       VARCHAR(150)    NOT NULL                                  COMMENT 'e.g. Valid Government ID, Signed Waiver',
    description     TEXT            NULL,
    is_mandatory    TINYINT(1)      NOT NULL DEFAULT 1,
    applies_to      SET('overnight','hourly','day_use') NOT NULL DEFAULT 'overnight,hourly,day_use',
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    sort_order      SMALLINT        NOT NULL DEFAULT 0,
    PRIMARY KEY (req_type_id),
    UNIQUE KEY uk_req_code (property_id, req_code),
    KEY idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Types of requirements guests must submit before check-in';

CREATE TABLE IF NOT EXISTS reservation_requirements (
    req_id          CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    reservation_id  CHAR(36)        NOT NULL                                  COMMENT 'FK → reservations.reservation_id',
    req_type_id     SMALLINT        NOT NULL                                  COMMENT 'FK → reservation_requirement_types.req_type_id',
    res_guest_id    CHAR(36)        NULL                                      COMMENT 'FK → reservation_guests.res_guest_id (if guest-specific)',
    status          ENUM('pending','submitted','approved','rejected')         NOT NULL DEFAULT 'pending',
    file_url        VARCHAR(500)    NULL                                      COMMENT 'Uploaded document URL',
    notes           TEXT            NULL,
    reviewed_by     CHAR(36)        NULL,
    reviewed_at     TIMESTAMP       NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (req_id),
    KEY idx_reservation_id (reservation_id),
    KEY idx_req_type_id (req_type_id),
    KEY idx_status (status),
    CONSTRAINT fk_rreq_reservation_id FOREIGN KEY (reservation_id) REFERENCES reservations(reservation_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Per-reservation requirement tracking — IDs, waivers, forms';

-- ============================================================================
-- TABLE: check_in_logs
-- Records every check-in/check-out event by staff. Full audit trail.
-- ============================================================================
CREATE TABLE IF NOT EXISTS check_in_logs (
    log_id          CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    reservation_id  CHAR(36)        NOT NULL                                  COMMENT 'FK → reservations.reservation_id',
    res_room_id     CHAR(36)        NULL                                      COMMENT 'FK → reservation_rooms.res_room_id',
    event_type      ENUM('check_in','check_out','early_check_in','late_check_out','room_transfer','extension')
                                    NOT NULL,
    event_at        DATETIME        NOT NULL,
    processed_by    CHAR(36)        NULL                                      COMMENT 'FK → user_accounts.user_id (staff)',
    notes           TEXT            NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (log_id),
    KEY idx_reservation_id (reservation_id),
    KEY idx_event_type (event_type),
    KEY idx_event_at (event_at),
    CONSTRAINT fk_cil_reservation_id FOREIGN KEY (reservation_id) REFERENCES reservations(reservation_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Check-in / check-out event log per reservation';

-- ============================================================================
-- TABLE: entrance_logs
-- Monitors every person entering/exiting the property.
-- Tracks guest type (adult, child, senior, infant) for management reporting.
-- ============================================================================
CREATE TABLE IF NOT EXISTS entrance_logs (
    entry_id            CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    property_id         CHAR(36)        NOT NULL                              COMMENT 'FK → properties.property_id',
    reservation_id      CHAR(36)        NULL                                  COMMENT 'FK → reservations.reservation_id (NULL = walk-in visitor)',
    res_guest_id        CHAR(36)        NULL                                  COMMENT 'FK → reservation_guests.res_guest_id',
    visitor_name        VARCHAR(150)    NULL,
    visitor_type        ENUM('adult','child','senior','infant','visitor','staff','delivery','vendor')
                                        NOT NULL DEFAULT 'adult',
    entry_type          ENUM('entry','exit')                                  NOT NULL DEFAULT 'entry',
    event_at            DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_presented        VARCHAR(100)    NULL,
    logged_by           CHAR(36)        NULL                                  COMMENT 'FK → user_accounts.user_id (security staff)',
    notes               TEXT            NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (entry_id),
    KEY idx_property_id (property_id),
    KEY idx_reservation_id (reservation_id),
    KEY idx_visitor_type (visitor_type),
    KEY idx_event_at (event_at),
    KEY idx_prop_date (property_id, event_at),
    CONSTRAINT fk_el_property_id FOREIGN KEY (property_id) REFERENCES properties(property_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Entrance/exit log — tracks all persons entering/leaving the property';

SELECT '03_reservations.sql — OK' AS migration_status;
