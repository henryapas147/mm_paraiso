-- ============================================================================
-- RESORT / HOTEL RESERVATION SYSTEM
-- Module 02: Property & Rooms
-- Database: MySQL 8.0+  |  Engine: InnoDB  |  Charset: utf8mb4
-- ============================================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS properties (
    property_id         CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    property_code       VARCHAR(20)     NOT NULL                              COMMENT 'e.g. RST-001',
    property_name       VARCHAR(150)    NOT NULL,
    property_type       ENUM('resort','hotel','pension_house','hostel','villa','beach_resort','mountain_resort')
                                        NOT NULL DEFAULT 'resort',
    tagline             VARCHAR(255)    NULL,
    description         TEXT            NULL,
    address_line1       VARCHAR(255)    NOT NULL,
    address_line2       VARCHAR(255)    NULL,
    city                VARCHAR(80)     NOT NULL,
    province            VARCHAR(80)     NULL,
    postal_code         VARCHAR(20)     NULL,
    country             VARCHAR(60)     NOT NULL DEFAULT 'Philippines',
    latitude            DECIMAL(10,7)   NULL,
    longitude           DECIMAL(10,7)   NULL,
    phone_primary       VARCHAR(20)     NULL,
    phone_secondary     VARCHAR(20)     NULL,
    email               VARCHAR(180)    NULL,
    website_url         VARCHAR(500)    NULL,
    facebook_url        VARCHAR(500)    NULL,
    check_in_time       TIME            NOT NULL DEFAULT '14:00:00',
    check_out_time      TIME            NOT NULL DEFAULT '12:00:00',
    earliest_check_in   TIME            NOT NULL DEFAULT '08:00:00',
    latest_check_out    TIME            NOT NULL DEFAULT '22:00:00',
    late_check_out_fee  DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    timezone            VARCHAR(50)     NOT NULL DEFAULT 'Asia/Manila',
    currency_code       CHAR(3)         NOT NULL DEFAULT 'PHP',
    tax_rate_percent    DECIMAL(5,2)    NOT NULL DEFAULT 0.00,
    is_active           TINYINT(1)      NOT NULL DEFAULT 1,
    sort_order          SMALLINT        NOT NULL DEFAULT 0,
    created_by          CHAR(36)        NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (property_id),
    UNIQUE KEY uk_property_code (property_code),
    KEY idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Master property / branch — supports multi-property setup';

CREATE TABLE IF NOT EXISTS property_images (
    image_id        CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    property_id     CHAR(36)        NOT NULL                                  COMMENT 'FK → properties.property_id',
    image_url       VARCHAR(500)    NOT NULL,
    thumbnail_url   VARCHAR(500)    NULL,
    caption         VARCHAR(255)    NULL,
    image_type      ENUM('cover','gallery','facilities','amenities','map','other') NOT NULL DEFAULT 'gallery',
    sort_order      SMALLINT        NOT NULL DEFAULT 0,
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    uploaded_by     CHAR(36)        NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (image_id),
    KEY idx_property_id (property_id),
    KEY idx_prop_type_sort (property_id, image_type, sort_order),
    CONSTRAINT fk_pi_property_id FOREIGN KEY (property_id) REFERENCES properties(property_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Property images — cover, gallery, facilities';

CREATE TABLE IF NOT EXISTS room_categories (
    category_id             CHAR(36)        NOT NULL                          COMMENT 'PK – UUID v4',
    property_id             CHAR(36)        NOT NULL                          COMMENT 'FK → properties.property_id',
    category_code           VARCHAR(30)     NOT NULL                          COMMENT 'e.g. STD, DLX, STE, COT, FAM',
    category_name           VARCHAR(100)    NOT NULL                          COMMENT 'e.g. Standard Room, Deluxe Suite, Family Cottage',
    description             TEXT            NULL,
    -- Pricing
    base_price_per_night    DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    base_price_per_hour     DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    base_price_day_use      DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    minimum_hours           TINYINT(3)      NOT NULL DEFAULT 2                COMMENT 'Minimum hours for hourly booking',
    -- Capacity
    max_adult_capacity      TINYINT(3)      NOT NULL DEFAULT 2,
    max_child_capacity      TINYINT(3)      NOT NULL DEFAULT 2,
    max_total_capacity      TINYINT(3)      NOT NULL DEFAULT 4,
    extra_adult_fee         DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    extra_child_fee         DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    extra_senior_fee        DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    -- Physical
    floor_area_sqm          DECIMAL(6,2)    NULL,
    bed_configuration       VARCHAR(150)    NULL                              COMMENT 'e.g. 1 King Bed + 1 Sofa Bed',
    view_type               VARCHAR(60)     NULL                              COMMENT 'ocean, mountain, garden, pool, city',
    floor_level             VARCHAR(30)     NULL,
    smoking_policy          ENUM('non_smoking','smoking','designated_area') NOT NULL DEFAULT 'non_smoking',
    pet_friendly            TINYINT(1)      NOT NULL DEFAULT 0,
    -- Booking options
    allows_hourly           TINYINT(1)      NOT NULL DEFAULT 1,
    allows_overnight        TINYINT(1)      NOT NULL DEFAULT 1,
    allows_day_use          TINYINT(1)      NOT NULL DEFAULT 1,
    sort_order              SMALLINT        NOT NULL DEFAULT 0,
    is_active               TINYINT(1)      NOT NULL DEFAULT 1,
    created_by              CHAR(36)        NULL,
    created_at              TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at              TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (category_id),
    UNIQUE KEY uk_property_cat_code (property_id, category_code),
    KEY idx_property_id (property_id),
    KEY idx_is_active (is_active),
    CONSTRAINT fk_rc_property_id FOREIGN KEY (property_id) REFERENCES properties(property_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Room types/categories — defines pricing, capacity, booking rules';

CREATE TABLE IF NOT EXISTS rooms (
    room_id                     CHAR(36)        NOT NULL                      COMMENT 'PK – UUID v4',
    property_id                 CHAR(36)        NOT NULL                      COMMENT 'FK → properties.property_id',
    category_id                 CHAR(36)        NOT NULL                      COMMENT 'FK → room_categories.category_id',
    room_number                 VARCHAR(20)     NOT NULL                      COMMENT 'e.g. 101, A-02, Cottage-3',
    room_name                   VARCHAR(100)    NULL                          COMMENT 'e.g. Sunset Villa, Lagoon Cottage',
    floor_number                TINYINT(3)      NULL,
    building_block              VARCHAR(30)     NULL                          COMMENT 'e.g. Building A, East Wing',
    room_status                 ENUM(
                                    'available',
                                    'occupied',
                                    'reserved',
                                    'cleaning',
                                    'maintenance',
                                    'out_of_order',
                                    'blocked'
                                )               NOT NULL DEFAULT 'available'  COMMENT 'Real-time operational status',
    -- Price overrides — NULL means use category price
    override_price_per_night    DECIMAL(10,2)   NULL,
    override_price_per_hour     DECIMAL(10,2)   NULL,
    override_price_day_use      DECIMAL(10,2)   NULL,
    -- Housekeeping tracking
    last_cleaned_at             TIMESTAMP       NULL,
    last_cleaned_by             CHAR(36)        NULL,
    last_inspected_at           TIMESTAMP       NULL,
    last_inspected_by           CHAR(36)        NULL,
    internal_notes              TEXT            NULL,
    is_active                   TINYINT(1)      NOT NULL DEFAULT 1,
    created_by                  CHAR(36)        NULL,
    created_at                  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at                  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (room_id),
    UNIQUE KEY uk_property_room_number (property_id, room_number),
    KEY idx_property_id (property_id),
    KEY idx_category_id (category_id),
    KEY idx_room_status (room_status),
    KEY idx_prop_status (property_id, room_status),
    CONSTRAINT fk_r_property_id FOREIGN KEY (property_id) REFERENCES properties(property_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_r_category_id FOREIGN KEY (category_id) REFERENCES room_categories(category_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Individual rooms — inherits pricing/capacity from room_categories unless overridden';

CREATE TABLE IF NOT EXISTS room_images (
    image_id        CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    room_id         CHAR(36)        NULL                                      COMMENT 'FK → rooms.room_id (room-specific image)',
    category_id     CHAR(36)        NULL                                      COMMENT 'FK → room_categories.category_id (shared category image)',
    image_url       VARCHAR(500)    NOT NULL,
    thumbnail_url   VARCHAR(500)    NULL,
    caption         VARCHAR(255)    NULL,
    image_type      ENUM('interior','exterior','bathroom','view','amenity','bed','balcony','other') NOT NULL DEFAULT 'interior',
    is_cover        TINYINT(1)      NOT NULL DEFAULT 0,
    sort_order      SMALLINT        NOT NULL DEFAULT 0,
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    uploaded_by     CHAR(36)        NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (image_id),
    KEY idx_room_id (room_id),
    KEY idx_category_id (category_id),
    KEY idx_is_cover (is_cover),
    KEY idx_sort_order (sort_order),
    CONSTRAINT fk_ri_room_id FOREIGN KEY (room_id) REFERENCES rooms(room_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ri_category_id FOREIGN KEY (category_id) REFERENCES room_categories(category_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Room and category images — admin can add multiple images per room';

CREATE TABLE IF NOT EXISTS amenities (
    amenity_id      SMALLINT        NOT NULL AUTO_INCREMENT                   COMMENT 'PK',
    amenity_name    VARCHAR(80)     NOT NULL,
    icon_code       VARCHAR(50)     NULL                                      COMMENT 'Icon identifier for frontend',
    category        ENUM('comfort','technology','bathroom','kitchen','outdoor','safety','accessibility','entertainment','other')
                                    NOT NULL DEFAULT 'comfort',
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    sort_order      SMALLINT        NOT NULL DEFAULT 0,
    PRIMARY KEY (amenity_id),
    UNIQUE KEY uk_amenity_name (amenity_name),
    KEY idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Master list of amenities (WiFi, AC, TV, Pool, etc.)';

CREATE TABLE IF NOT EXISTS room_amenities (
    room_amenity_id INT             NOT NULL AUTO_INCREMENT                   COMMENT 'PK',
    category_id     CHAR(36)        NULL                                      COMMENT 'FK → room_categories.category_id',
    room_id         CHAR(36)        NULL                                      COMMENT 'FK → rooms.room_id (room-specific amenity)',
    amenity_id      SMALLINT        NOT NULL                                  COMMENT 'FK → amenities.amenity_id',
    notes           VARCHAR(255)    NULL                                      COMMENT 'e.g. 55-inch Smart TV, Nespresso machine',
    PRIMARY KEY (room_amenity_id),
    KEY idx_category_id (category_id),
    KEY idx_room_id (room_id),
    KEY idx_amenity_id (amenity_id),
    CONSTRAINT fk_ra_category_id FOREIGN KEY (category_id) REFERENCES room_categories(category_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ra_room_id FOREIGN KEY (room_id) REFERENCES rooms(room_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ra_amenity_id FOREIGN KEY (amenity_id) REFERENCES amenities(amenity_id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Amenities per room category or specific room';

CREATE TABLE IF NOT EXISTS room_availability_blocks (
    block_id        CHAR(36)        NOT NULL                                  COMMENT 'PK – UUID v4',
    room_id         CHAR(36)        NOT NULL                                  COMMENT 'FK → rooms.room_id',
    block_type      ENUM('maintenance','owner_use','event_hold','deep_cleaning','renovation','other')
                                    NOT NULL DEFAULT 'maintenance',
    date_start      DATE            NOT NULL,
    date_end        DATE            NOT NULL,
    time_start      TIME            NULL,
    time_end        TIME            NULL,
    reason          VARCHAR(255)    NULL,
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    created_by      CHAR(36)        NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (block_id),
    KEY idx_room_id (room_id),
    KEY idx_date_range (date_start, date_end),
    KEY idx_is_active (is_active),
    CONSTRAINT fk_rab_room_id FOREIGN KEY (room_id) REFERENCES rooms(room_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Manual availability blocks — maintenance, owner use, event holds';

SELECT '02_property_rooms.sql — OK' AS migration_status;
