-- ============================================================================
-- Add video title and description fields to company_information table
-- ============================================================================

ALTER TABLE company_information 
ADD COLUMN video_title VARCHAR(255) NULL COMMENT 'Video section title for public page' AFTER youtube_url,
ADD COLUMN video_description TEXT NULL COMMENT 'Video section description for public page' AFTER video_title;

UPDATE company_information 
SET video_title = 'Get Closer View & Different Feeling' 
WHERE video_title IS NULL;

SELECT 'add_video_fields_to_company.sql — OK' AS migration_status;
