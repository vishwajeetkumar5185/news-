-- Add live_status column to settings table for admin control
ALTER TABLE `settings` ADD COLUMN IF NOT EXISTS `live_status` TINYINT(1) DEFAULT 0 AFTER `live_video_url`;

-- Alternative for older MySQL versions:
-- ALTER TABLE `settings` ADD COLUMN `live_status` TINYINT(1) DEFAULT 0 AFTER `live_video_url`;