-- Add paid_amount and balance_amount columns to reservations table
-- These columns track partial payment details

ALTER TABLE reservations 
ADD COLUMN IF NOT EXISTS paid_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00 AFTER total_amount,
ADD COLUMN IF NOT EXISTS balance_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00 AFTER paid_amount;

-- Update existing reservations to set correct values
UPDATE reservations 
SET paid_amount = 0.00, 
    balance_amount = total_amount
WHERE paid_amount = 0 OR balance_amount = 0;

SELECT 'Migration completed: Added paid_amount and balance_amount to reservations table' AS status;
