-- Food Order Status History Table
-- Tracks all status changes for food orders for audit trail and customer tracking

CREATE TABLE IF NOT EXISTS food_order_status_history (
    history_id VARCHAR(36) PRIMARY KEY,
    order_id VARCHAR(36) NOT NULL,
    old_status VARCHAR(50),
    new_status VARCHAR(50) NOT NULL,
    changed_by VARCHAR(36),
    changed_by_type ENUM('admin', 'customer', 'system') DEFAULT 'admin',
    change_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES food_orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES user_accounts(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Index for faster lookups
CREATE INDEX idx_fosh_order_id ON food_order_status_history(order_id);
CREATE INDEX idx_fosh_created_at ON food_order_status_history(created_at);
