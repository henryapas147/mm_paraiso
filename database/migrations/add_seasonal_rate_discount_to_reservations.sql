-- Migration: Add seasonal_rate_discount column to reservations table
-- Date: 2026-05-30
-- Description: Add column to track seasonal rate discounts separately from promo discounts

ALTER TABLE reservations 
ADD COLUMN seasonal_rate_discount DECIMAL(12,2) NOT NULL DEFAULT 0.00 
AFTER discount_amount;
