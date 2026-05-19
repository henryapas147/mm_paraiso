-- Migration: Add barangay and google_maps_embed_url columns to company_information table
-- Created: 2026-05-18

ALTER TABLE company_information
ADD COLUMN barangay VARCHAR(100) NULL COMMENT 'Barangay name'
AFTER address_line2;

ALTER TABLE company_information
ADD COLUMN google_maps_embed_url VARCHAR(500) NULL COMMENT 'Google Maps embed iframe URL'
AFTER website;
