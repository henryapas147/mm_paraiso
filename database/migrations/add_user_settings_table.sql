-- Create user_settings table to store user preferences and settings
CREATE TABLE IF NOT EXISTS user_settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    setting_key VARCHAR(100) NOT NULL,
    setting_value TEXT,
    setting_type ENUM('boolean', 'string', 'integer', 'json') DEFAULT 'string',
    category VARCHAR(50) DEFAULT 'general',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_setting (user_id, setting_key),
    KEY idx_user_id (user_id),
    FOREIGN KEY (user_id) REFERENCES user_accounts(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default settings for existing users
INSERT INTO user_settings (user_id, setting_key, setting_value, setting_type, category)
SELECT u.user_id, 'email_booking_confirmations', '1', 'boolean', 'email'
FROM user_accounts u
WHERE NOT EXISTS (
    SELECT 1 FROM user_settings us 
    WHERE us.user_id = u.user_id AND us.setting_key = 'email_booking_confirmations'
);

INSERT INTO user_settings (user_id, setting_key, setting_value, setting_type, category)
SELECT u.user_id, 'email_payment_receipts', '1', 'boolean', 'email'
FROM user_accounts u
WHERE NOT EXISTS (
    SELECT 1 FROM user_settings us 
    WHERE us.user_id = u.user_id AND us.setting_key = 'email_payment_receipts'
);

INSERT INTO user_settings (user_id, setting_key, setting_value, setting_type, category)
SELECT u.user_id, 'email_promotional', '0', 'boolean', 'email'
FROM user_accounts u
WHERE NOT EXISTS (
    SELECT 1 FROM user_settings us 
    WHERE us.user_id = u.user_id AND us.setting_key = 'email_promotional'
);

INSERT INTO user_settings (user_id, setting_key, setting_value, setting_type, category)
SELECT u.user_id, 'email_frequency', 'daily', 'string', 'email'
FROM user_accounts u
WHERE NOT EXISTS (
    SELECT 1 FROM user_settings us 
    WHERE us.user_id = u.user_id AND us.setting_key = 'email_frequency'
);

INSERT INTO user_settings (user_id, setting_key, setting_value, setting_type, category)
SELECT u.user_id, 'push_notifications', '1', 'boolean', 'notification'
FROM user_accounts u
WHERE NOT EXISTS (
    SELECT 1 FROM user_settings us 
    WHERE us.user_id = u.user_id AND us.setting_key = 'push_notifications'
);

INSERT INTO user_settings (user_id, setting_key, setting_value, setting_type, category)
SELECT u.user_id, 'sms_notifications', '1', 'boolean', 'notification'
FROM user_accounts u
WHERE NOT EXISTS (
    SELECT 1 FROM user_settings us 
    WHERE us.user_id = u.user_id AND us.setting_key = 'sms_notifications'
);

INSERT INTO user_settings (user_id, setting_key, setting_value, setting_type, category)
SELECT u.user_id, 'in_app_notifications', '1', 'boolean', 'notification'
FROM user_accounts u
WHERE NOT EXISTS (
    SELECT 1 FROM user_settings us 
    WHERE us.user_id = u.user_id AND us.setting_key = 'in_app_notifications'
);

INSERT INTO user_settings (user_id, setting_key, setting_value, setting_type, category)
SELECT u.user_id, 'profile_visibility', 'public', 'string', 'privacy'
FROM user_accounts u
WHERE NOT EXISTS (
    SELECT 1 FROM user_settings us 
    WHERE us.user_id = u.user_id AND us.setting_key = 'profile_visibility'
);

INSERT INTO user_settings (user_id, setting_key, setting_value, setting_type, category)
SELECT u.user_id, 'share_data_partners', '0', 'boolean', 'privacy'
FROM user_accounts u
WHERE NOT EXISTS (
    SELECT 1 FROM user_settings us 
    WHERE us.user_id = u.user_id AND us.setting_key = 'share_data_partners'
);

INSERT INTO user_settings (user_id, setting_key, setting_value, setting_type, category)
SELECT u.user_id, 'allow_analytics', '1', 'boolean', 'privacy'
FROM user_accounts u
WHERE NOT EXISTS (
    SELECT 1 FROM user_settings us 
    WHERE us.user_id = u.user_id AND us.setting_key = 'allow_analytics'
);

INSERT INTO user_settings (user_id, setting_key, setting_value, setting_type, category)
SELECT u.user_id, 'personalized_recommendations', '0', 'boolean', 'privacy'
FROM user_accounts u
WHERE NOT EXISTS (
    SELECT 1 FROM user_settings us 
    WHERE us.user_id = u.user_id AND us.setting_key = 'personalized_recommendations'
);

INSERT INTO user_settings (user_id, setting_key, setting_value, setting_type, category)
SELECT u.user_id, 'language', 'en', 'string', 'preferences'
FROM user_accounts u
WHERE NOT EXISTS (
    SELECT 1 FROM user_settings us 
    WHERE us.user_id = u.user_id AND us.setting_key = 'language'
);

INSERT INTO user_settings (user_id, setting_key, setting_value, setting_type, category)
SELECT u.user_id, 'timezone', 'Asia/Manila', 'string', 'preferences'
FROM user_accounts u
WHERE NOT EXISTS (
    SELECT 1 FROM user_settings us 
    WHERE us.user_id = u.user_id AND us.setting_key = 'timezone'
);

INSERT INTO user_settings (user_id, setting_key, setting_value, setting_type, category)
SELECT u.user_id, 'currency', 'PHP', 'string', 'preferences'
FROM user_accounts u
WHERE NOT EXISTS (
    SELECT 1 FROM user_settings us 
    WHERE us.user_id = u.user_id AND us.setting_key = 'currency'
);
