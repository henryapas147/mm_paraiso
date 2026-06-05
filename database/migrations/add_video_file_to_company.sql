-- ============================================================================
-- Add video_file field to company_information table for uploaded videos
-- ============================================================================

ALTER TABLE company_information 
ADD COLUMN video_file VARCHAR(500) NULL COMMENT 'Path to uploaded video file' AFTER video_description;

SELECT 'add_video_file_to_company.sql — OK' AS migration_status;
