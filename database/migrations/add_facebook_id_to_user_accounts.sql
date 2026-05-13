-- Migration: Add facebook_id column to user_accounts table
-- This allows users to sign in with Facebook OAuth

ALTER TABLE user_accounts
ADD COLUMN facebook_id VARCHAR(255) NULL UNIQUE AFTER google_id,
ADD INDEX idx_facebook_id (facebook_id);

-- Note: If you already have a google_id column, this will add facebook_id after it.
-- If you don't have google_id yet, adjust the AFTER clause accordingly.
