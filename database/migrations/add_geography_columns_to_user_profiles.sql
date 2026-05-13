-- ============================================================================
-- Migration: Add Geography Columns to user_profiles Table
-- This migration adds columns to store geography codes (Region, Province, City/Municipality, Barangay)
-- The names will be fetched via LEFT JOIN with the PSGC geography tables
-- ============================================================================

-- Add geography code columns to user_profiles table
ALTER TABLE user_profiles
ADD COLUMN region_code VARCHAR(12) NULL AFTER address_line2,
ADD COLUMN province_code VARCHAR(12) NULL AFTER region_code,
ADD COLUMN city_municipality_code VARCHAR(12) NULL AFTER province_code,
ADD COLUMN barangay_code VARCHAR(12) NULL AFTER city_municipality_code;

-- Add indexes for better query performance
ALTER TABLE user_profiles
ADD INDEX idx_region_code (region_code),
ADD INDEX idx_province_code (province_code),
ADD INDEX idx_city_municipality_code (city_municipality_code),
ADD INDEX idx_barangay_code (barangay_code);
