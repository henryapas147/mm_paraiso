-- Inventory Transactions Table
-- Tracks all inventory movements (deductions, additions, rollbacks)

CREATE TABLE IF NOT EXISTS inventory_transactions (
  transaction_id CHAR(36) NOT NULL PRIMARY KEY,
  product_id CHAR(36) NOT NULL,
  transaction_type ENUM('deduct', 'add', 'rollback') NOT NULL,
  quantity DECIMAL(10, 3) NOT NULL,
  uom_id SMALLINT NOT NULL,
  reference_type ENUM('food_order', 'reservation', 'manual_adjustment', 'purchase') NOT NULL,
  reference_id CHAR(36) NULL,
  reason VARCHAR(255) NULL,
  stock_before DECIMAL(10, 3) NOT NULL,
  stock_after DECIMAL(10, 3) NOT NULL,
  created_by CHAR(36) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  rollback_transaction_id CHAR(36) NULL,
  FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
  FOREIGN KEY (uom_id) REFERENCES units_of_measure(uom_id) ON DELETE RESTRICT,
  FOREIGN KEY (created_by) REFERENCES user_accounts(user_id) ON DELETE RESTRICT,
  FOREIGN KEY (rollback_transaction_id) REFERENCES inventory_transactions(transaction_id) ON DELETE SET NULL,
  INDEX idx_product_id (product_id),
  INDEX idx_transaction_type (transaction_type),
  INDEX idx_reference (reference_type, reference_id),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add stock_on_hand column to products table
ALTER TABLE products 
ADD COLUMN stock_on_hand DECIMAL(10, 3) DEFAULT 0,
ADD COLUMN low_stock_threshold DECIMAL(10, 3) DEFAULT 10;

-- Note: products table already has uom_id (SMALLINT) from the original schema
-- No need to add it again
