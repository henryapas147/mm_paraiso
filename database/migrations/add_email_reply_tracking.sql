-- Add message_id to inquiry_email_replies for thread tracking
ALTER TABLE inquiry_email_replies
    ADD COLUMN IF NOT EXISTS message_id VARCHAR(255) NULL COMMENT 'SMTP Message-ID header for thread matching' AFTER email_id,
    ADD COLUMN IF NOT EXISTS direction ENUM('outbound', 'inbound') NOT NULL DEFAULT 'outbound' AFTER status,
    ADD COLUMN IF NOT EXISTS sender_email VARCHAR(255) NULL COMMENT 'Populated for inbound replies' AFTER direction,
    ADD COLUMN IF NOT EXISTS sender_name VARCHAR(255) NULL COMMENT 'Populated for inbound replies' AFTER sender_email;

-- Index for fast Message-ID lookups
ALTER TABLE inquiry_email_replies
    ADD INDEX IF NOT EXISTS idx_message_id (message_id);
