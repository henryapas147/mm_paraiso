-- ============================================================================
-- RESORT / HOTEL RESERVATION SYSTEM
-- Migration: Add Geocode Address Fields to user_profiles
-- Database: MySQL 8.0+  |  Engine: InnoDB  |  Charset: utf8mb4
-- ============================================================================

SET NAMES utf8mb4;
USE booking;

-- Convert geocode tables to utf8mb4_unicode_ci collation to match user_profiles
ALTER TABLE psgc_regions CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE psgc_provinces CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE psgc_cities_municipalities CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE psgc_barangays CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Add ONLY the new geocode reference columns (address_line1, address_line2,
-- and postal_code already exist in the table from 01_users_auth.sql)
ALTER TABLE user_profiles
  ADD COLUMN region_code            VARCHAR(12) NULL AFTER country,
  ADD COLUMN province_code          VARCHAR(12) NULL AFTER region_code,
  ADD COLUMN city_municipality_code VARCHAR(12) NULL AFTER province_code,
  ADD COLUMN barangay_code          VARCHAR(12) NULL AFTER city_municipality_code;

-- Add foreign key constraints to geocode tables
ALTER TABLE user_profiles
  ADD CONSTRAINT fk_up_region_code
    FOREIGN KEY (region_code)
    REFERENCES psgc_regions(region_code)                          ON UPDATE CASCADE ON DELETE SET NULL,
  ADD CONSTRAINT fk_up_province_code
    FOREIGN KEY (province_code)
    REFERENCES psgc_provinces(province_code)                      ON UPDATE CASCADE ON DELETE SET NULL,
  ADD CONSTRAINT fk_up_city_municipality_code
    FOREIGN KEY (city_municipality_code)
    REFERENCES psgc_cities_municipalities(city_municipality_code) ON UPDATE CASCADE ON DELETE SET NULL,
  ADD CONSTRAINT fk_up_barangay_code
    FOREIGN KEY (barangay_code)
    REFERENCES psgc_barangays(barangay_code)                      ON UPDATE CASCADE ON DELETE SET NULL;

-- Add indexes for the new geocode columns
ALTER TABLE user_profiles
  ADD INDEX idx_region_code            (region_code),
  ADD INDEX idx_province_code          (province_code),
  ADD INDEX idx_city_municipality_code (city_municipality_code),
  ADD INDEX idx_barangay_code          (barangay_code);

SELECT '02_add_geocode_address.sql — OK' AS migration_status;