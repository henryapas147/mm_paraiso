-- ============================================================================
-- Migration: Add Cover Photo Column to user_profiles Table
-- This migration adds a column to store the cover/background photo URL
-- ============================================================================

-- Add cover_photo_url column to user_profiles table
ALTER TABLE user_profiles
ADD COLUMN cover_photo_url VARCHAR(500) NULL AFTER profile_photo_url;

-- Add index for better query performance
ALTER TABLE user_profiles
ADD INDEX idx_cover_photo_url (cover_photo_url);
