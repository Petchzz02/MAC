-- Migration script to add updated_by column to all location tables
-- Run this script if you want to track who updated each record

USE db_sp_checklist;

-- Add updated_by column to all tables
ALTER TABLE `เมืองสมุทรปราการ` ADD COLUMN `updated_by` VARCHAR(50) DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `พระประแดง` ADD COLUMN `updated_by` VARCHAR(50) DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `พระสมุทรเจดีย์` ADD COLUMN `updated_by` VARCHAR(50) DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `บางพลี` ADD COLUMN `updated_by` VARCHAR(50) DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `บางบ่อ` ADD COLUMN `updated_by` VARCHAR(50) DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `บางเสาธง` ADD COLUMN `updated_by` VARCHAR(50) DEFAULT NULL AFTER `updated_at`;

-- Optional: Add index for better performance
CREATE INDEX idx_updated_by ON `เมืองสมุทรปราการ` (`updated_by`);
CREATE INDEX idx_updated_by ON `พระประแดง` (`updated_by`);
CREATE INDEX idx_updated_by ON `พระสมุทรเจดีย์` (`updated_by`);
CREATE INDEX idx_updated_by ON `บางพลี` (`updated_by`);
CREATE INDEX idx_updated_by ON `บางบ่อ` (`updated_by`);
CREATE INDEX idx_updated_by ON `บางเสาธง` (`updated_by`);