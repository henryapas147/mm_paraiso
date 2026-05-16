-- ============================================================================
-- RESORT / HOTEL RESERVATION SYSTEM
-- Migration: Add Geocode Foreign Key Constraints to company_information
-- Database: MySQL 8.0+  |  Engine: InnoDB  |  Charset: utf8mb4
-- ============================================================================

SET NAMES utf8mb4;
SET @dbname = DATABASE();

-- Convert geocode tables to utf8mb4_unicode_ci collation to match company_information
ALTER TABLE psgc_regions CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE psgc_provinces CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE psgc_cities_municipalities CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE psgc_barangays CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Add foreign key constraints to geocode tables
ALTER TABLE company_information
  ADD CONSTRAINT fk_ci_region_code
    FOREIGN KEY (region_code)
    REFERENCES psgc_regions(region_code)                          ON UPDATE CASCADE ON DELETE SET NULL,
  ADD CONSTRAINT fk_ci_province_code
    FOREIGN KEY (province_code)
    REFERENCES psgc_provinces(province_code)                      ON UPDATE CASCADE ON DELETE SET NULL,
  ADD CONSTRAINT fk_ci_city_municipality_code
    FOREIGN KEY (city_municipality_code)
    REFERENCES psgc_cities_municipalities(city_municipality_code) ON UPDATE CASCADE ON DELETE SET NULL,
  ADD CONSTRAINT fk_ci_barangay_code
    FOREIGN KEY (barangay_code)
    REFERENCES psgc_barangays(barangay_code)                      ON UPDATE CASCADE ON DELETE SET NULL;

SELECT '11_add_company_geocode_constraints.sql — OK' AS migration_status;
