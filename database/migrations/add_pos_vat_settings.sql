-- Migration: Add POS VAT/Tax settings
-- Created: 2026-06-08
-- Description: Adds configurable VAT/tax settings for Point of Sale module

-- Insert POS VAT settings into system_settings table
INSERT INTO system_settings (setting_group, setting_key, setting_label, setting_value, data_type, is_public, description) 
VALUES 
  ('pos', 'pos_vat_enabled', 'Enable VAT for POS', '0', 'boolean', 1, 'Enable or disable VAT/tax calculation in POS (0 = disabled, 1 = enabled)'),
  ('pos', 'pos_vat_rate', 'VAT Rate (%)', '12', 'number', 1, 'VAT percentage rate applied to POS transactions (e.g., 12 for 12%)'),
  ('pos', 'pos_tax_inclusive', 'Tax Inclusive Pricing', '0', 'boolean', 1, 'Whether product prices already include tax (0 = exclusive, 1 = inclusive)');

-- Note: After running this migration, you can manage these settings via the System Settings page
-- or directly update the database to toggle VAT on/off for POS transactions.
