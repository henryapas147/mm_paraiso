-- Add template_url column to reservation_requirement_types table
ALTER TABLE reservation_requirement_types ADD COLUMN template_url VARCHAR(500) NULL AFTER description;

-- Add timestamp columns to reservation_requirement_types table
ALTER TABLE reservation_requirement_types ADD COLUMN created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP AFTER template_url;
ALTER TABLE reservation_requirement_types ADD COLUMN updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at;
