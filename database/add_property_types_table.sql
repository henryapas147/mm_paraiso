-- ============================================================================
-- Add Property Types Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS property_types (
    property_type_id   CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    type_code          VARCHAR(50)     NOT NULL                              COMMENT 'e.g. resort, hotel, pension_house',
    type_name          VARCHAR(100)    NOT NULL                              COMMENT 'e.g. Resort, Hotel, Pension House',
    description        TEXT            NULL,
    sort_order         SMALLINT        NOT NULL DEFAULT 0,
    is_active          TINYINT(1)      NOT NULL DEFAULT 1,
    created_at         TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at         TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (property_type_id),
    UNIQUE KEY uk_type_code (type_code),
    KEY idx_is_active (is_active),
    KEY idx_sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Property types – resort, hotel, pension house, etc.';

-- Insert default property types
INSERT IGNORE INTO property_types (property_type_id, type_code, type_name, description, sort_order) VALUES
(UUID(), 'resort', 'Resort', 'A resort property with recreational facilities', 1),
(UUID(), 'hotel', 'Hotel', 'A hotel with guest rooms and amenities', 2),
(UUID(), 'pension_house', 'Pension House', 'Budget accommodation with basic amenities', 3),
(UUID(), 'hostel', 'Hostel', 'Budget accommodation with shared facilities', 4),
(UUID(), 'villa', 'Villa', 'Private villa accommodation', 5),
(UUID(), 'beach_resort', 'Beach Resort', 'Resort located on a beach', 6),
(UUID(), 'mountain_resort', 'Mountain Resort', 'Resort located in a mountain area', 7),
(UUID(), 'apartment', 'Apartment', 'Self-contained apartment unit', 8),
(UUID(), 'house', 'House', 'Entire house for rent', 9),
(UUID(), 'motel', 'Motel', 'Motel with parking for vehicles', 10),
(UUID(), 'guest_house', 'Guest House', 'Small private accommodation', 11),
(UUID(), 'other', 'Other', 'Other type of property', 99);
