-- Create table to track email replies to contact inquiries
CREATE TABLE IF NOT EXISTS inquiry_email_replies (
    email_id INT AUTO_INCREMENT PRIMARY KEY,
    inquiry_id INT NOT NULL,
    recipient_email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message_body TEXT NOT NULL,
    replied_by CHAR(36) NULL,
    sent_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('sent', 'failed', 'bounced') DEFAULT 'sent',
    error_message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (inquiry_id) REFERENCES contact_inquiries(inquiry_id) ON DELETE CASCADE,
    FOREIGN KEY (replied_by) REFERENCES user_accounts(user_id) ON DELETE SET NULL,
    INDEX idx_inquiry_id (inquiry_id),
    INDEX idx_sent_at (sent_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add reply tracking columns to contact_inquiries if not exists
SET @dbname = DATABASE();
SET @tablename = 'contact_inquiries';

-- Add replied_at column if it doesn't exist
SET @column_exists = (
    SELECT COUNT(*) 
    FROM information_schema.columns 
    WHERE table_schema = @dbname 
    AND table_name = @tablename 
    AND column_name = 'replied_at'
);

SET @sql = IF(@column_exists = 0, 
    'ALTER TABLE contact_inquiries ADD COLUMN replied_at DATETIME NULL AFTER status',
    'SELECT "Column replied_at already exists" as message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add/Fix replied_by column
SET @column_exists = (
    SELECT COUNT(*) 
    FROM information_schema.columns 
    WHERE table_schema = @dbname 
    AND table_name = @tablename 
    AND column_name = 'replied_by'
);

-- Add column if not exists
SET @sql = IF(@column_exists = 0, 
    'ALTER TABLE contact_inquiries ADD COLUMN replied_by CHAR(36) NULL AFTER replied_at',
    'SELECT "Column replied_by exists" as message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Alter column type to CHAR(36) if it's currently INT (to match user_id type)
SET @sql = '
    ALTER TABLE contact_inquiries 
    MODIFY COLUMN replied_by CHAR(36) NULL
';
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add foreign key for replied_by if not exists
SET @fk_exists = (
    SELECT COUNT(*) 
    FROM information_schema.table_constraints 
    WHERE table_schema = @dbname 
    AND table_name = @tablename 
    AND constraint_name = 'fk_contact_inquiries_replied_by'
);

SET @sql = IF(@fk_exists = 0, 
    'ALTER TABLE contact_inquiries ADD CONSTRAINT fk_contact_inquiries_replied_by FOREIGN KEY (replied_by) REFERENCES user_accounts(user_id) ON DELETE SET NULL',
    'SELECT "Foreign key fk_contact_inquiries_replied_by already exists" as message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
