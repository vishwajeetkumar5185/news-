-- Add live_video_url column to settings table if it doesn't exist
ALTER TABLE `settings` ADD COLUMN IF NOT EXISTS `live_video_url` VARCHAR(500) NULL AFTER `about_content`;

-- Alternative for MySQL versions that don't support IF NOT EXISTS
-- Run this if the above doesn't work:
-- ALTER TABLE `settings` ADD COLUMN `live_video_url` VARCHAR(500) NULL AFTER `about_content`;
