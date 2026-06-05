-- ============================================================================
-- Add video_source_preference field to company_information table
-- ============================================================================

ALTER TABLE company_information 
ADD COLUMN video_source_preference ENUM('youtube', 'file', 'youtube_priority', 'file_priority') NULL DEFAULT 'youtube_priority' COMMENT 'Preference for video display: youtube, file, youtube_priority (youtube if available), file_priority (file if available)' AFTER video_file;

UPDATE company_information 
SET video_source_preference = 'youtube_priority' 
WHERE video_source_preference IS NULL;

SELECT 'add_video_source_preference.sql — OK' AS migration_status;
