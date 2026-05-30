-- Migration: Add base_amount column to reservations table
-- Date: 2026-05-30
-- Description: Add column to store base amount before any discounts are applied

ALTER TABLE reservations 
ADD COLUMN base_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00 
AFTER subtotal_amount;
