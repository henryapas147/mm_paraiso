-- ============================================================================
-- Two-Factor Authentication (2FA) Table
-- Supports TOTP-based 2FA for user accounts
-- ============================================================================

CREATE TABLE IF NOT EXISTS user_2fa (
    user_id CHAR(36) NOT NULL COMMENT 'FK → user_accounts.user_id',
    secret VARCHAR(64) NOT NULL COMMENT 'TOTP secret key (base32 encoded)',
    enabled TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Whether 2FA is enabled for this user',
    backup_codes JSON NULL COMMENT 'Array of backup codes for recovery',
    last_used_at DATETIME NULL COMMENT 'Last time 2FA was successfully used',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id),
    CONSTRAINT fk_2fa_user_id FOREIGN KEY (user_id) REFERENCES user_accounts(user_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Two-Factor Authentication settings per user';

SELECT 'add_user_2fa_table.sql — OK' AS migration_status;
