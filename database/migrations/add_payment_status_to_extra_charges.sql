-- ============================================================================
-- Migration: Add payment_status and paid_at to extra_charges
-- ============================================================================

SET NAMES utf8mb4;

ALTER TABLE extra_charges
  ADD COLUMN payment_status ENUM('unpaid','paid','waived') NOT NULL DEFAULT 'unpaid'
    COMMENT 'Settlement status of this charge'
    AFTER notes,
  ADD COLUMN paid_at DATETIME NULL
    COMMENT 'Timestamp when this charge was settled'
    AFTER payment_status,
  ADD KEY idx_ec_payment_status (payment_status);

SELECT 'add_payment_status_to_extra_charges.sql — OK' AS migration_status;
