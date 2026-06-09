-- ============================================================================
-- User Payment Methods Table
-- Stores user's saved payment methods (tokenized for security)
-- ============================================================================

CREATE TABLE IF NOT EXISTS user_payment_methods (
    payment_method_id CHAR(36) NOT NULL COMMENT 'PK – UUID v4',
    user_id CHAR(36) NOT NULL COMMENT 'FK → user_accounts.user_id',
    method_type ENUM('credit_card', 'debit_card', 'gcash', 'maya', 'bank_transfer', 'other') NOT NULL,
    provider VARCHAR(50) NULL COMMENT 'e.g., visa, mastercard, gcash, maya, bdo, bpi',
    display_name VARCHAR(100) NULL COMMENT 'User-friendly name for the method',
    tokenized_data TEXT NULL COMMENT 'Encrypted/tokenized payment data (never store raw card numbers)',
    last_4 VARCHAR(4) NULL COMMENT 'Last 4 digits for display only',
    expiry_month TINYINT(2) NULL COMMENT 'Card expiry month (1-12)',
    expiry_year SMALLINT(4) NULL COMMENT 'Card expiry year',
    is_default TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Whether this is the default payment method',
    is_active TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Whether the method is active/usable',
    metadata JSON NULL COMMENT 'Additional data (billing address, etc.)',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (payment_method_id),
    KEY idx_user_id (user_id),
    KEY idx_method_type (method_type),
    KEY idx_is_default (is_default),
    CONSTRAINT fk_upm_user_id FOREIGN KEY (user_id) REFERENCES user_accounts(user_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='User saved payment methods (tokenized for security)';

SELECT 'add_user_payment_methods_table.sql — OK' AS migration_status;
