-- ============================================================================
-- Add Geography Code Columns to Properties Table
-- Date: 2024-05-14
-- Description: Adds region_code, province_code, city_municipality_code, and barangay_code columns
--              to the properties table for better data integrity and geocoding support.
-- ============================================================================

ALTER TABLE properties 
ADD COLUMN region_code VARCHAR(12) NULL COMMENT 'PSGC region code (e.g., 100000000)' AFTER address_line2,
ADD COLUMN province_code VARCHAR(12) NULL COMMENT 'PSGC province code (e.g., 104200000)' AFTER region_code,
ADD COLUMN city_municipality_code VARCHAR(12) NULL COMMENT 'PSGC city/municipality code (e.g., 104216000)' AFTER province_code,
ADD COLUMN barangay_code VARCHAR(12) NULL COMMENT 'PSGC barangay code (e.g., 104216015)' AFTER city_municipality_code;

-- Add index for faster geography-based queries
ALTER TABLE properties 
ADD INDEX idx_geography_codes (region_code, province_code, city_municipality_code, barangay_code);
