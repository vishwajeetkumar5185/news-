-- Add PIN column to admin_users table
ALTER TABLE `admin_users` ADD COLUMN `pin` VARCHAR(4) DEFAULT NULL AFTER `password`;
ALTER TABLE `admin_users` ADD COLUMN `pin_created_at` TIMESTAMP NULL DEFAULT NULL AFTER `pin`;
ALTER TABLE `admin_users` ADD COLUMN `last_pin_login` TIMESTAMP NULL DEFAULT NULL AFTER `pin_created_at`;

-- Update existing admin user (if exists)
UPDATE `admin_users` SET `pin` = NULL WHERE `pin` IS NULL;