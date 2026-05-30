-- Migration: Add soft delete support to reservations table
-- Date: 2026-05-30
-- Description: Add deleted_at column for soft delete functionality to handle foreign key constraints

ALTER TABLE reservations 
ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL 
COMMENT 'Soft delete — NULL = not deleted' 
AFTER updated_at;

-- Add index for faster queries filtering deleted records
ALTER TABLE reservations 
ADD INDEX idx_deleted_at (deleted_at);
