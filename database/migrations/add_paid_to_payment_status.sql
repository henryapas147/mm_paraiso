-- Migration: Add 'paid' to payment_status ENUM in payments table
-- Date: 2026-05-28
-- Description: Add 'paid' as a valid payment status to support check-in payment collection

-- Modify the payment_status ENUM to include 'paid'
ALTER TABLE payments 
MODIFY COLUMN payment_status ENUM('pending','confirmed','paid','rejected','refunded','voided') 
NOT NULL DEFAULT 'pending';
