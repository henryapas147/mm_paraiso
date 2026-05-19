-- Tracks Gmail API polling state so we only process new messages
CREATE TABLE IF NOT EXISTS gmail_poll_state (
    id INT AUTO_INCREMENT PRIMARY KEY,
    history_id VARCHAR(64) NULL COMMENT 'Gmail historyId — start point for next incremental poll',
    last_polled_at DATETIME NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert initial row
INSERT INTO gmail_poll_state (history_id, last_polled_at) VALUES (NULL, NULL);
