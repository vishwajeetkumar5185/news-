-- Run this SQL command in phpMyAdmin to add live_video_url column to existing settings table

ALTER TABLE settings ADD COLUMN live_video_url VARCHAR(500) AFTER about_content;
