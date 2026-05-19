-- ============================================================================
-- RESORT / HOTEL RESERVATION SYSTEM
-- Module 10: Company Information
-- Database: MySQL 8.0+  |  Engine: InnoDB  |  Charset: utf8mb4
-- ============================================================================

SET NAMES utf8mb4;
SET @dbname = DATABASE();

CREATE TABLE IF NOT EXISTS company_information (
    company_id              CHAR(36)        NOT NULL                              COMMENT 'PK – UUID v4',
    company_name            VARCHAR(200)    NOT NULL,
    legal_name              VARCHAR(200)    NULL                                  COMMENT 'Official legal name if different from company name',
    business_reg_number     VARCHAR(100)    NULL                                  COMMENT 'Business registration number',
    tax_id                  VARCHAR(100)    NULL                                  COMMENT 'Tax identification number',
    address_line1           VARCHAR(255)    NOT NULL,
    address_line2           VARCHAR(255)    NULL,
    city                    VARCHAR(80)     NOT NULL,
    province                VARCHAR(80)     NULL,
    postal_code             VARCHAR(20)     NULL,
    country                 VARCHAR(60)     NOT NULL DEFAULT 'Philippines',
    region_code             VARCHAR(12)     NULL                                  COMMENT 'FK → psgc_regions.region_code',
    province_code           VARCHAR(12)     NULL                                  COMMENT 'FK → psgc_provinces.province_code',
    city_municipality_code  VARCHAR(12)     NULL                                  COMMENT 'FK → psgc_cities_municipalities.city_municipality_code',
    barangay_code           VARCHAR(12)     NULL                                  COMMENT 'FK → psgc_barangays.barangay_code',
    barangay                VARCHAR(100)    NULL                                  COMMENT 'Barangay name',
    phone_number            VARCHAR(20)     NOT NULL,
    phone_number_2          VARCHAR(20)     NULL                                  COMMENT 'Secondary phone number',
    email                   VARCHAR(180)    NOT NULL,
    email_2                 VARCHAR(180)    NULL                                  COMMENT 'Secondary email',
    website                 VARCHAR(255)    NULL,
    logo_url                VARCHAR(500)    NULL,
    description             TEXT            NULL                                  COMMENT 'Company description / about us',
    business_hours          JSON            NULL                                  COMMENT 'JSON object with operating hours per day',
    facebook_url            VARCHAR(255)    NULL,
    instagram_url           VARCHAR(255)    NULL,
    twitter_url             VARCHAR(255)    NULL,
    linkedin_url            VARCHAR(255)    NULL,
    youtube_url             VARCHAR(255)    NULL,
    is_active               TINYINT(1)      NOT NULL DEFAULT 1,
    created_by              CHAR(36)        NULL                                  COMMENT 'FK → user_accounts.user_id',
    updated_by              CHAR(36)        NULL                                  COMMENT 'FK → user_accounts.user_id',
    created_at              TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at              TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (company_id),
    UNIQUE KEY uk_company_name (company_name),
    KEY idx_is_active (is_active),
    KEY idx_city (city),
    KEY idx_country (country),
    KEY idx_region_code (region_code),
    KEY idx_province_code (province_code),
    KEY idx_city_municipality_code (city_municipality_code),
    KEY idx_barangay_code (barangay_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Company profile and contact information';

-- ============================================================================
-- SEED DATA: Default Company Information
-- ============================================================================

INSERT INTO company_information (
    company_id,
    company_name,
    legal_name,
    business_reg_number,
    tax_id,
    address_line1,
    address_line2,
    city,
    province,
    postal_code,
    country,
    region_code,
    province_code,
    city_municipality_code,
    barangay_code,
    phone_number,
    email,
    website,
    description,
    business_hours,
    is_active
) VALUES (
    UUID(),
    'Paraiso Resort & Hotel',
    'Paraiso Resort & Hotel Corporation',
    '2023-12345-CO',
    'TIN-123-456-789',
    '123 Beach Road',
    'Block 5, Lot 12',
    'Puerto Galera',
    'Mindoro Oriental',
    '5203',
    'Philippines',
    NULL,
    NULL,
    NULL,
    NULL,
    '+63-917-123-4567',
    'info@paraisoresort.com',
    'https://www.paraisoresort.com',
    'A beautiful beachfront resort offering luxurious accommodations, excellent dining, and unforgettable experiences in Puerto Galera.',
    '{
        "monday": {"open": "08:00", "close": "22:00", "is_open": true},
        "tuesday": {"open": "08:00", "close": "22:00", "is_open": true},
        "wednesday": {"open": "08:00", "close": "22:00", "is_open": true},
        "thursday": {"open": "08:00", "close": "22:00", "is_open": true},
        "friday": {"open": "08:00", "close": "23:00", "is_open": true},
        "saturday": {"open": "07:00", "close": "23:00", "is_open": true},
        "sunday": {"open": "07:00", "close": "22:00", "is_open": true}
    }',
    1
) ON DUPLICATE KEY UPDATE company_name=VALUES(company_name);

SELECT '10_company_information.sql — OK' AS migration_status;
