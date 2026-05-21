-- =====================================================
-- Payment System Extension for Schedule Visit
-- Works with existing 04_payments_billing.sql tables
-- =====================================================

-- Payment Settings - Global configuration for payment requirements
CREATE TABLE IF NOT EXISTS payment_settings (
    setting_id VARCHAR(36) PRIMARY KEY,
    property_id VARCHAR(36) NULL,
    payment_type ENUM('percentage', 'fixed') NOT NULL DEFAULT 'percentage',
    payment_value DECIMAL(10, 2) NOT NULL DEFAULT 30.00 COMMENT 'Percentage (e.g., 30.00 for 30%) or fixed amount',
    min_payment_required DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'Minimum amount that must be paid',
    allow_full_payment TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Allow guests to pay full amount',
    allow_partial_payment TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Allow guests to pay partial amount',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by VARCHAR(36) NULL,
    
    FOREIGN KEY (property_id) REFERENCES properties(property_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES user_accounts(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default global settings
INSERT INTO payment_settings (setting_id, property_id, payment_type, payment_value, min_payment_required, allow_full_payment, allow_partial_payment, is_active)
VALUES ('pay-set-0000-0000-0000-000000000001', NULL, 'percentage', 30.00, 500.00, 1, 1, 1)
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Note: Using existing payment_methods from 04_payments_billing.sql
-- Available methods in your system: cash, gcash, maya, bank_bdo, bank_bpi, bank_metrobank, credit_card, debit_card, check
-- No need to insert - they already exist

-- Payment Accounts - Account details for each property and method
-- Note: method_id references payment_methods.method_id (SMALLINT from 04_payments_billing.sql)
CREATE TABLE IF NOT EXISTS payment_accounts (
    account_id VARCHAR(36) PRIMARY KEY,
    property_id VARCHAR(36) NOT NULL,
    method_id SMALLINT NOT NULL,
    account_name VARCHAR(100) NOT NULL COMMENT 'Account holder name',
    account_number VARCHAR(100) NULL COMMENT 'Account number / mobile number for GCash',
    bank_name VARCHAR(100) NULL COMMENT 'For bank transfers only',
    branch_name VARCHAR(100) NULL,
    qr_code_image VARCHAR(255) NULL COMMENT 'Path to QR code image',
    instructions TEXT NULL COMMENT 'Custom instructions for this payment method',
    is_default TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Default account for this method',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by VARCHAR(36) NULL,
    
    FOREIGN KEY (property_id) REFERENCES properties(property_id) ON DELETE CASCADE,
    FOREIGN KEY (method_id) REFERENCES payment_methods(method_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES user_accounts(user_id) ON DELETE SET NULL,
    UNIQUE KEY unique_property_method_default (property_id, method_id, is_default)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Note: Uses existing 'payments' table from 04_payments_billing.sql
-- Existing structure supports: payment_id, reservation_id, method_id (SMALLINT), 
-- transaction_type ('payment','partial_payment','refund','adjustment','void'),
-- amount, reference_number, proof_image_url, payment_status, etc.
-- 
-- Additional columns for schedule visit workflow:
ALTER TABLE payments 
    ADD COLUMN IF NOT EXISTS account_id VARCHAR(36) NULL COMMENT 'Which payment account was used' AFTER method_id,
    ADD COLUMN IF NOT EXISTS amount_required DECIMAL(12,2) NULL COMMENT 'Required payment amount for partial payments' AFTER amount,
    ADD COLUMN IF NOT EXISTS processed_at TIMESTAMP NULL AFTER confirmed_at;

-- Add foreign key for account_id if not exists
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS 
    WHERE CONSTRAINT_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'payments' 
    AND CONSTRAINT_NAME = 'fk_payments_account_id');
    
SET @sql = IF(@constraint_exists = 0, 
    'ALTER TABLE payments ADD CONSTRAINT fk_payments_account_id FOREIGN KEY (account_id) REFERENCES payment_accounts(account_id) ON DELETE SET NULL',
    'SELECT 1');
    
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Payment Attachments - Proof of payment files (extends existing payments table)
CREATE TABLE IF NOT EXISTS payment_attachments (
    attachment_id VARCHAR(36) PRIMARY KEY,
    payment_id CHAR(36) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL COMMENT 'Relative path from uploads directory',
    file_type VARCHAR(50) NOT NULL COMMENT 'MIME type',
    file_size INT NOT NULL COMMENT 'Size in bytes',
    original_name VARCHAR(255) NOT NULL COMMENT 'Original filename before sanitization',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    uploaded_by VARCHAR(36) NULL COMMENT 'User who uploaded (null for guest uploads)',
    
    FOREIGN KEY (payment_id) REFERENCES payments(payment_id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES user_accounts(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Indexes for performance
CREATE INDEX IF NOT EXISTS idx_payment_settings_property ON payment_settings(property_id);
CREATE INDEX IF NOT EXISTS idx_payment_accounts_property ON payment_accounts(property_id);
CREATE INDEX IF NOT EXISTS idx_payment_accounts_method ON payment_accounts(method_id);
CREATE INDEX IF NOT EXISTS idx_payment_attachments_payment ON payment_attachments(payment_id);

-- Payment Status History (optional - for audit trail)
-- Uses existing payments table from 04_payments_billing.sql
CREATE TABLE IF NOT EXISTS payment_status_history (
    history_id VARCHAR(36) PRIMARY KEY,
    payment_id CHAR(36) NOT NULL,
    old_status ENUM('pending', 'confirmed', 'rejected', 'refunded', 'voided') NULL,
    new_status ENUM('pending', 'confirmed', 'rejected', 'refunded', 'voided') NOT NULL,
    changed_by VARCHAR(36) NULL,
    change_reason TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (payment_id) REFERENCES payments(payment_id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES user_accounts(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX IF NOT EXISTS idx_payment_history_payment ON payment_status_history(payment_id);

-- Add index on payments table for schedule visit queries
CREATE INDEX IF NOT EXISTS idx_payments_account_id ON payments(account_id);

-- =====================================================
-- TEMPORARY SAMPLE DATA FOR TESTING
-- =====================================================
-- Note: Replace 'PROP-0000-0000-0000-000000000001' with your actual property_id

-- =====================================================
-- Booking Type Settings - Configurable thresholds for booking type detection
-- =====================================================
CREATE TABLE IF NOT EXISTS booking_type_settings (
    setting_id VARCHAR(36) PRIMARY KEY,
    property_id VARCHAR(36) NULL,
    
    -- Hourly thresholds
    hourly_min_hours INT NOT NULL DEFAULT 3 COMMENT 'Minimum hours for hourly rate (e.g., 3)',
    hourly_max_hours INT NOT NULL DEFAULT 12 COMMENT 'Maximum hours for hourly rate (e.g., 12)',
    
    -- Overnight threshold
    overnight_min_hours INT NOT NULL DEFAULT 12 COMMENT 'Minimum hours to qualify for overnight rate',
    overnight_requires_midnight TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Must span midnight for overnight',
    
    -- Day use threshold
    day_use_max_hours INT NOT NULL DEFAULT 12 COMMENT 'Maximum hours for day use (same day)',
    
    -- Hourly preset options (JSON array of common durations)
    hourly_presets JSON NULL COMMENT 'JSON array of preset hours like [3,4,5,6,7]',
    
    -- Hourly duration-specific rates (JSON object with hours as keys)
    hourly_rates JSON NULL COMMENT 'JSON object with rates per duration e.g., {"3": 500, "4": 650, "5": 800}',
    
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by VARCHAR(36) NULL,
    
    FOREIGN KEY (property_id) REFERENCES properties(property_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES user_accounts(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO booking_type_settings (
    setting_id, property_id, 
    hourly_min_hours, hourly_max_hours, 
    overnight_min_hours, overnight_requires_midnight,
    day_use_max_hours, hourly_presets, hourly_rates, is_active
) VALUES (
    'book-type-0000-0000-0000-000000000001', NULL,
    3, 12,
    12, 1,
    12,
    '[3,4,5,6,7,8]',
    '{"3": 500.00, "4": 650.00, "5": 800.00, "6": 950.00, "7": 1100.00, "8": 1250.00}',
    1
)
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Sample Payment Accounts (for testing purposes)
-- These should be customized with actual account details per property
INSERT INTO payment_accounts (account_id, property_id, method_id, account_name, account_number, bank_name, branch_name, qr_code_image, instructions, is_default, is_active, created_by) VALUES
-- GCash Account Sample
('pay-acc-0000-0000-0000-000000000001', 'prop-0001-0000-0000-0000-00000000000', (SELECT method_id FROM payment_methods WHERE method_code = 'gcash' LIMIT 1), 'M&M Paraiso Resort', '0917XXXXXXX', NULL, NULL, 'uploads/payments/payment_accounts/qr_gcash_sample.png', '1. Open GCash app\n2. Tap "Send Money"\n3. Enter number: 0917XXXXXXX\n4. Enter amount\n5. Take screenshot of confirmation\n6. Upload proof below', 1, 1, NULL),

-- Bank Transfer Sample (BDO)
('pay-acc-0000-0000-0000-000000000002', 'prop-0001-0000-0000-0000-00000000000', (SELECT method_id FROM payment_methods WHERE method_code = 'bank_bdo' LIMIT 1), 'M&M Paraiso Resort Inc.', '1234567890', 'BDO', 'Makati Main Branch', NULL, 'Bank: BDO\nAccount Name: M&M Paraiso Resort Inc.\nAccount Number: 1234567890\n\nPlease upload deposit slip or transfer receipt.', 1, 1, NULL),

-- Bank Transfer Sample (BPI) - Alternative
('pay-acc-0000-0000-0000-000000000003', 'prop-0001-0000-0000-0000-00000000000', (SELECT method_id FROM payment_methods WHERE method_code = 'bank_bpi' LIMIT 1), 'M&M Paraiso Resort Inc.', '0987654321', 'BPI', 'Alabang Branch', NULL, 'Bank: BPI\nAccount Name: M&M Paraiso Resort Inc.\nAccount Number: 0987654321\n\nPlease upload deposit slip or transfer receipt.', 0, 1, NULL),

-- PayMaya Account Sample
('pay-acc-0000-0000-0000-000000000004', 'prop-0001-0000-0000-0000-00000000000', (SELECT method_id FROM payment_methods WHERE method_code = 'maya' LIMIT 1), 'M&M Paraiso Resort', '0918XXXXXXX', NULL, NULL, 'uploads/payments/payment_accounts/qr_maya_sample.png', '1. Open PayMaya app\n2. Tap "Send Money"\n3. Enter number: 0918XXXXXXX\n4. Enter amount\n5. Take screenshot of confirmation\n6. Upload proof below', 1, 1, NULL),

-- Cash on Arrival (No account details needed, just instructions)
('pay-acc-0000-0000-0000-000000000005', 'prop-0001-0000-0000-0000-00000000000', (SELECT method_id FROM payment_methods WHERE method_code = 'cash' LIMIT 1), 'Cash Payment at Reception', NULL, NULL, NULL, NULL, 'Pay in cash at the reception upon arrival. Reservation must be confirmed by admin before arrival.', 1, 1, NULL)
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- =====================================================
-- END OF TEMPORARY SAMPLE DATA
-- =====================================================

SELECT '05_payments.sql — OK (Adjusted for 04_payments_billing.sql compatibility)' AS migration_status;
