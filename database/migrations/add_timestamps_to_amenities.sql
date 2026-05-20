-- Migration: Add created_at and updated_at columns to amenities table
-- Date: 2026-05-20
-- Description: Add timestamp columns for tracking amenity creation and modification

ALTER TABLE amenities
ADD COLUMN created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when amenity was created',
ADD COLUMN updated_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp when amenity was last updated';
