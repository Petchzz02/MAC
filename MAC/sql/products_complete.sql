-- =====================================================
-- COMPLETE PRODUCT SETUP FOR SAMUTPRAKAN CHECKLIST SYSTEM
-- =====================================================
-- This file combines all product-related SQL scripts into one file
-- Run this file to setup complete product database with all features
-- =====================================================

-- =====================================================
-- 1. DATABASE AND TABLE CREATION
-- =====================================================
CREATE DATABASE IF NOT EXISTS db_sp_checklist CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE db_sp_checklist;

-- Table for เมืองสมุทรปราการ
CREATE TABLE IF NOT EXISTS `เมืองสมุทรปราการ` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_code` VARCHAR(32) NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('in_stock','out_of_stock','not_for_sale') DEFAULT NULL,
  `note` VARCHAR(255) DEFAULT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_p_808279` (`product_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table for พระประแดง
CREATE TABLE IF NOT EXISTS `พระประแดง` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_code` VARCHAR(32) NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('in_stock','out_of_stock','not_for_sale') DEFAULT NULL,
  `note` VARCHAR(255) DEFAULT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_p_662608` (`product_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table for พระสมุทรเจดีย์
CREATE TABLE IF NOT EXISTS `พระสมุทรเจดีย์` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_code` VARCHAR(32) NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('in_stock','out_of_stock','not_for_sale') DEFAULT NULL,
  `note` VARCHAR(255) DEFAULT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_p_777863` (`product_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table for บางพลี
CREATE TABLE IF NOT EXISTS `บางพลี` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_code` VARCHAR(32) NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('in_stock','out_of_stock','not_for_sale') DEFAULT NULL,
  `note` VARCHAR(255) DEFAULT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_p_565520` (`product_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table for บางบ่อ
CREATE TABLE IF NOT EXISTS `บางบ่อ` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_code` VARCHAR(32) NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('in_stock','out_of_stock','not_for_sale') DEFAULT NULL,
  `note` VARCHAR(255) DEFAULT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_p_751585` (`product_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table for บางเสาธง
CREATE TABLE IF NOT EXISTS `บางเสาธง` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_code` VARCHAR(32) NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('in_stock','out_of_stock','not_for_sale') DEFAULT NULL,
  `note` VARCHAR(255) DEFAULT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_p_341157` (`product_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 2. INSERT INITIAL WATER PRODUCTS (8 items)
-- =====================================================

-- Insert water products for เมืองสมุทรปราการ
INSERT INTO `เมืองสมุทรปราการ` (`product_code`,`product_name`,`image_path`,`status`) VALUES
  ('P1',' คริสตัล 350 มล.','../../image/products/water/คริสตัล350มล..jpg',NULL),
  ('P2',' คริสตัล 600 มล.','../../image/products/water/คริสตัล600มล..jpg',NULL),
  ('P3',' คริสตัล 1,000 มล.','../../image/products/water/คริสตัล1,000มล..jpg',NULL),
  ('P4',' คริสตัล 1,500 มล.','../../image/products/water/คริสตัล1,500มล..jpg',NULL),
  ('P5',' เนสท์เล่ 330 มล.','../../image/products/water/เนสท์เล่ 330มล.jpg',NULL),
  ('P6',' เนสท์เล่ 600 มล.','../../image/products/water/เนสท์เล่600มล.jpg',NULL),
  ('P7',' เนสท์เล่ 1,500 มล.','../../image/products/water/เนสท์เล่1,500มล.jpg',NULL),
  ('P8',' เนสท์เล่ 6,000 มล.','../../image/products/water/เนสท์เล่6,000มล.jpg',NULL);

-- Insert water products for พระประแดง
INSERT INTO `พระประแดง` (`product_code`,`product_name`,`image_path`,`status`) VALUES
  ('P1',' คริสตัล 350 มล.','../../image/products/water/คริสตัล350มล..jpg',NULL),
  ('P2',' คริสตัล 600 มล.','../../image/products/water/คริสตัล600มล..jpg',NULL),
  ('P3',' คริสตัล 1,000 มล.','../../image/products/water/คริสตัล1,000มล..jpg',NULL),
  ('P4',' คริสตัล 1,500 มล.','../../image/products/water/คริสตัล1,500มล..jpg',NULL),
  ('P5',' เนสท์เล่ 330 มล.','../../image/products/water/เนสท์เล่ 330มล.jpg',NULL),
  ('P6',' เนสท์เล่ 600 มล.','../../image/products/water/เนสท์เล่600มล..jpg',NULL),
  ('P7',' เนสท์เล่ 1,500 มล.','../../image/products/water/เนสท์เล่1,500มล.jpg',NULL),
  ('P8',' เนสท์เล่ 6,000 มล.','../../image/products/water/เนสท์เล่6,000มล.jpg',NULL);

-- Insert water products for พระสมุทรเจดีย์
INSERT INTO `พระสมุทรเจดีย์` (`product_code`,`product_name`,`image_path`,`status`) VALUES
  ('P1',' คริสตัล 350 มล.','../../image/products/water/คริสตัล350มล..jpg',NULL),
  ('P2',' คริสตัล 600 มล.','../../image/products/water/คริสตัล600มล..jpg',NULL),
  ('P3',' คริสตัล 1,000 มล.','../../image/products/water/คริสตัล1,000มล..jpg',NULL),
  ('P4',' คริสตัล 1,500 มล.','../../image/products/water/คริสตัล1,500มล..jpg',NULL),
  ('P5',' เนสท์เล่ 330 มล.','../../image/products/water/เนสท์เล่ 330มล.jpg',NULL),
  ('P6',' เนสท์เล่ 600 มล.','../../image/products/water/เนสท์เล่600มล..jpg',NULL),
  ('P7',' เนสท์เล่ 1,500 มล.','../../image/products/water/เนสท์เล่1,500มล.jpg',NULL),
  ('P8',' เนสท์เล่ 6,000 มล.','../../image/products/water/เนสท์เล่6,000มล.jpg',NULL);

-- Insert water products for บางพลี
INSERT INTO `บางพลี` (`product_code`,`product_name`,`image_path`,`status`) VALUES
  ('P1',' คริสตัล 350 มล.','../../image/products/water/คริสตัล350มล..jpg',NULL),
  ('P2',' คริสตัล 600 มล.','../../image/products/water/คริสตัล600มล..jpg',NULL),
  ('P3',' คริสตัล 1,000 มล.','../../image/products/water/คริสตัล1,000มล..jpg',NULL),
  ('P4',' คริสตัล 1,500 มล.','../../image/products/water/คริสตัล1,500มล..jpg',NULL),
  ('P5',' เนสท์เล่ 330 มล.','../../image/products/water/เนสท์เล่ 330มล.jpg',NULL),
  ('P6',' เนสท์เล่ 600 มล.','../../image/products/water/เนสท์เล่600มล..jpg',NULL),
  ('P7',' เนสท์เล่ 1,500 มล.','../../image/products/water/เนสท์เล่1,500มล.jpg',NULL),
  ('P8',' เนสท์เล่ 6,000 มล.','../../image/products/water/เนสท์เล่6,000มล.jpg',NULL);

-- Insert water products for บางบ่อ
INSERT INTO `บางบ่อ` (`product_code`,`product_name`,`image_path`,`status`) VALUES
  ('P1',' คริสตัล 350 มล.','../../image/products/water/คริสตัล350มล..jpg',NULL),
  ('P2',' คริสตัล 600 มล.','../../image/products/water/คริสตัล600มล..jpg',NULL),
  ('P3',' คริสตัล 1,000 มล.','../../image/products/water/คริสตัล1,000มล..jpg',NULL),
  ('P4',' คริสตัล 1,500 มล.','../../image/products/water/คริสตัล1,500มล..jpg',NULL),
  ('P5',' เนสท์เล่ 330 มล.','../../image/products/water/เนสท์เล่ 330มล.jpg',NULL),
  ('P6',' เนสท์เล่ 600 มล.','../../image/products/water/เนสท์เล่600มล..jpg',NULL),
  ('P7',' เนสท์เล่ 1,500 มล.','../../image/products/water/เนสท์เล่1,500มล.jpg',NULL),
  ('P8',' เนสท์เล่ 6,000 มล.','../../image/products/water/เนสท์เล่6,000มล.jpg',NULL);
-- Insert water products for บางเสาธง
INSERT INTO `บางเสาธง` (`product_code`,`product_name`,`image_path`,`status`) VALUES
  ('P1',' คริสตัล 350 มล.','../../image/products/water/คริสตัล350มล..jpg',NULL),
  ('P2',' คริสตัล 600 มล.','../../image/products/water/คริสตัล600มล..jpg',NULL),
  ('P3',' คริสตัล 1,000 มล.','../../image/products/water/คริสตัล1,000มล..jpg',NULL),
  ('P4',' คริสตัล 1,500 มล.','../../image/products/water/คริสตัล1,500มล..jpg',NULL),
  ('P5',' เนสท์เล่ 330 มล.','../../image/products/water/เนสท์เล่ 330มล.jpg',NULL),
  ('P6',' เนสท์เล่ 600 มล.','../../image/products/water/เนสท์เล่600มล..jpg',NULL),
  ('P7',' เนสท์เล่ 1,500 มล.','../../image/products/water/เนสท์เล่1,500มล.jpg',NULL),
  ('P8',' เนสท์เล่ 6,000 มล.','../../image/products/water/เนสท์เล่6,000มล.jpg',NULL);

-- =====================================================
-- 3. ADD CATEGORY COLUMN FOR PRODUCT CLASSIFICATION
-- =====================================================

-- Add category column to all tables
ALTER TABLE `เมืองสมุทรปราการ` ADD COLUMN `category` VARCHAR(50) DEFAULT 'เครื่องดื่ม' AFTER `product_name`;
ALTER TABLE `พระประแดง` ADD COLUMN `category` VARCHAR(50) DEFAULT 'เครื่องดื่ม' AFTER `product_name`;
ALTER TABLE `พระสมุทรเจดีย์` ADD COLUMN `category` VARCHAR(50) DEFAULT 'เครื่องดื่ม' AFTER `product_name`;
ALTER TABLE `บางพลี` ADD COLUMN `category` VARCHAR(50) DEFAULT 'เครื่องดื่ม' AFTER `product_name`;
ALTER TABLE `บางบ่อ` ADD COLUMN `category` VARCHAR(50) DEFAULT 'เครื่องดื่ม' AFTER `product_name`;
ALTER TABLE `บางเสาธง` ADD COLUMN `category` VARCHAR(50) DEFAULT 'เครื่องดื่ม' AFTER `product_name`;

-- =====================================================
-- 4. INSERT SNACK PRODUCTS (8 items)
-- =====================================================

-- Insert snack products for เมืองสมุทรปราการ
INSERT INTO `เมืองสมุทรปราการ` (`product_code`, `product_name`, `category`, `image_path`, `status`) VALUES
('S1', 'คอนเน่', 'ขนม', '../../image/products/snack/คอนเน่.png', NULL),
('S2', 'ซันไบทส์', 'ขนม', '../../image/products/snack/ซันไบทส์.png', NULL),
('S3', 'โดริโทส', 'ขนม', '../../image/products/snack/โดริโทส.png', NULL),
('S4', 'โตโร', 'ขนม', '../../image/products/snack/โตโร.png', NULL),
('S5', 'เทสโต', 'ขนม', '../../image/products/snack/เทสโต.png', NULL),
('S6', 'เลย์', 'ขนม', '../../image/products/snack/เลย์.png', NULL),
('S7', 'โลตัส ขาไก่', 'ขนม', '../../image/products/snack/โลตัส ขาไก่.png', NULL),
('S8', 'อาริงาโต', 'ขนม', '../../image/products/snack/อาริงาโต.png', NULL);

-- Insert snack products for พระประแดง
INSERT INTO `พระประแดง` (`product_code`, `product_name`, `category`, `image_path`, `status`) VALUES
('S1', 'คอนเน่', 'ขนม', '../../image/products/snack/คอนเน่.png', NULL),
('S2', 'ซันไบทส์', 'ขนม', '../../image/products/snack/ซันไบทส์.png', NULL),
('S3', 'โดริโทส', 'ขนม', '../../image/products/snack/โดริโทส.png', NULL),
('S4', 'โตโร', 'ขนม', '../../image/products/snack/โตโร.png', NULL),
('S5', 'เทสโต', 'ขนม', '../../image/products/snack/เทสโต.png', NULL),
('S6', 'เลย์', 'ขนม', '../../image/products/snack/เลย์.png', NULL),
('S7', 'โลตัส ขาไก่', 'ขนม', '../../image/products/snack/โลตัส ขาไก่.png', NULL),
('S8', 'อาริงาโต', 'ขนม', '../../image/products/snack/อาริงาโต.png', NULL);

-- Insert snack products for พระสมุทรเจดีย์
INSERT INTO `พระสมุทรเจดีย์` (`product_code`, `product_name`, `category`, `image_path`, `status`) VALUES
('S1', 'คอนเน่', 'ขนม', '../../image/products/snack/คอนเน่.png', NULL),
('S2', 'ซันไบทส์', 'ขนม', '../../image/products/snack/ซันไบทส์.png', NULL),
('S3', 'โดริโทส', 'ขนม', '../../image/products/snack/โดริโทส.png', NULL),
('S4', 'โตโร', 'ขนม', '../../image/products/snack/โตโร.png', NULL),
('S5', 'เทสโต', 'ขนม', '../../image/products/snack/เทสโต.png', NULL),
('S6', 'เลย์', 'ขนม', '../../image/products/snack/เลย์.png', NULL),
('S7', 'โลตัส ขาไก่', 'ขนม', '../../image/products/snack/โลตัส ขาไก่.png', NULL),
('S8', 'อาริงาโต', 'ขนม', '../../image/products/snack/อาริงาโต.png', NULL);

-- Insert snack products for บางพลี
INSERT INTO `บางพลี` (`product_code`, `product_name`, `category`, `image_path`, `status`) VALUES
('S1', 'คอนเน่', 'ขนม', '../../image/products/snack/คอนเน่.png', NULL),
('S2', 'ซันไบทส์', 'ขนม', '../../image/products/snack/ซันไบทส์.png', NULL),
('S3', 'โดริโทส', 'ขนม', '../../image/products/snack/โดริโทส.png', NULL),
('S4', 'โตโร', 'ขนม', '../../image/products/snack/โตโร.png', NULL),
('S5', 'เทสโต', 'ขนม', '../../image/products/snack/เทสโต.png', NULL),
('S6', 'เลย์', 'ขนม', '../../image/products/snack/เลย์.png', NULL),
('S7', 'โลตัส ขาไก่', 'ขนม', '../../image/products/snack/โลตัส ขาไก่.png', NULL),
('S8', 'อาริงาโต', 'ขนม', '../../image/products/snack/อาริงาโต.png', NULL);

-- Insert snack products for บางบ่อ
INSERT INTO `บางบ่อ` (`product_code`, `product_name`, `category`, `image_path`, `status`) VALUES
('S1', 'คอนเน่', 'ขนม', '../../image/products/snack/คอนเน่.png', NULL),
('S2', 'ซันไบทส์', 'ขนม', '../../image/products/snack/ซันไบทส์.png', NULL),
('S3', 'โดริโทส', 'ขนม', '../../image/products/snack/โดริโทส.png', NULL),
('S4', 'โตโร', 'ขนม', '../../image/products/snack/โตโร.png', NULL),
('S5', 'เทสโต', 'ขนม', '../../image/products/snack/เทสโต.png', NULL),
('S6', 'เลย์', 'ขนม', '../../image/products/snack/เลย์.png', NULL),
('S7', 'โลตัส ขาไก่', 'ขนม', '../../image/products/snack/โลตัส ขาไก่.png', NULL),
('S8', 'อาริงาโต', 'ขนม', '../../image/products/snack/อาริงาโต.png', NULL);

-- Insert snack products for บางเสาธง
INSERT INTO `บางเสาธง` (`product_code`, `product_name`, `category`, `image_path`, `status`) VALUES
('S1', 'คอนเน่', 'ขนม', '../../image/products/snack/คอนเน่.png', NULL),
('S2', 'ซันไบทส์', 'ขนม', '../../image/products/snack/ซันไบทส์.png', NULL),
('S3', 'โดริโทส', 'ขนม', '../../image/products/snack/โดริโทส.png', NULL),
('S4', 'โตโร', 'ขนม', '../../image/products/snack/โตโร.png', NULL),
('S5', 'เทสโต', 'ขนม', '../../image/products/snack/เทสโต.png', NULL),
('S6', 'เลย์', 'ขนม', '../../image/products/snack/เลย์.png', NULL),
('S7', 'โลตัส ขาไก่', 'ขนม', '../../image/products/snack/โลตัส ขาไก่.png', NULL),
('S8', 'อาริงาโต', 'ขนม', '../../image/products/snack/อาริงาโต.png', NULL);

-- =====================================================
-- 5. ADD UPDATED_BY COLUMN FOR TRACKING
-- =====================================================

-- Add updated_by column to all tables
ALTER TABLE `เมืองสมุทรปราการ` ADD COLUMN `updated_by` VARCHAR(50) DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `พระประแดง` ADD COLUMN `updated_by` VARCHAR(50) DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `พระสมุทรเจดีย์` ADD COLUMN `updated_by` VARCHAR(50) DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `บางพลี` ADD COLUMN `updated_by` VARCHAR(50) DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `บางบ่อ` ADD COLUMN `updated_by` VARCHAR(50) DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `บางเสาธง` ADD COLUMN `updated_by` VARCHAR(50) DEFAULT NULL AFTER `updated_at`;

-- =====================================================
-- 6. CREATE INDEXES FOR BETTER PERFORMANCE
-- =====================================================

-- Add index for category for better filtering performance
CREATE INDEX idx_category ON `เมืองสมุทรปราการ` (`category`);
CREATE INDEX idx_category ON `พระประแดง` (`category`);
CREATE INDEX idx_category ON `พระสมุทรเจดีย์` (`category`);
CREATE INDEX idx_category ON `บางพลี` (`category`);
CREATE INDEX idx_category ON `บางบ่อ` (`category`);
CREATE INDEX idx_category ON `บางเสาธง` (`category`);

-- Add index for updated_by column for better performance
CREATE INDEX idx_updated_by ON `เมืองสมุทรปราการ` (`updated_by`);
CREATE INDEX idx_updated_by ON `พระประแดง` (`updated_by`);
CREATE INDEX idx_updated_by ON `พระสมุทรเจดีย์` (`updated_by`);
CREATE INDEX idx_updated_by ON `บางพลี` (`updated_by`);
CREATE INDEX idx_updated_by ON `บางบ่อ` (`updated_by`);
CREATE INDEX idx_updated_by ON `บางเสาธง` (`updated_by`);

-- =====================================================
-- 7. UPDATE EXISTING WATER PRODUCTS TO HAVE CORRECT CATEGORY
-- =====================================================

-- Update existing water products to have proper category
UPDATE `เมืองสมุทรปราการ` SET `category` = 'เครื่องดื่ม' WHERE `category` IS NULL OR `category` = '';
UPDATE `พระประแดง` SET `category` = 'เครื่องดื่ม' WHERE `category` IS NULL OR `category` = '';
UPDATE `พระสมุทรเจดีย์` SET `category` = 'เครื่องดื่ม' WHERE `category` IS NULL OR `category` = '';
UPDATE `บางพลี` SET `category` = 'เครื่องดื่ม' WHERE `category` IS NULL OR `category` = '';
UPDATE `บางบ่อ` SET `category` = 'เครื่องดื่ม' WHERE `category` IS NULL OR `category` = '';
UPDATE `บางเสาธง` SET `category` = 'เครื่องดื่ม' WHERE `category` IS NULL OR `category` = '';

-- =====================================================
-- 8. VERIFY SETUP COMPLETION
-- =====================================================

-- Display completion message and data summary
SELECT 'Product setup completed successfully!' as message;

-- Show product count by category for each location
SELECT 'เมืองสมุทรปราการ' as location, category, COUNT(*) as product_count 
FROM `เมืองสมุทรปราการ` 
GROUP BY category
UNION ALL
SELECT 'พระประแดง' as location, category, COUNT(*) as product_count 
FROM `พระประแดง` 
GROUP BY category
UNION ALL
SELECT 'พระสมุทรเจดีย์' as location, category, COUNT(*) as product_count 
FROM `พระสมุทรเจดีย์` 
GROUP BY category
UNION ALL
SELECT 'บางพลี' as location, category, COUNT(*) as product_count 
FROM `บางพลี` 
GROUP BY category
UNION ALL
SELECT 'บางบ่อ' as location, category, COUNT(*) as product_count 
FROM `บางบ่อ` 
GROUP BY category
UNION ALL
SELECT 'บางเสาธง' as location, category, COUNT(*) as product_count 
FROM `บางเสาธง` 
GROUP BY category
ORDER BY location, category;

-- =====================================================
-- SETUP COMPLETE!
-- =====================================================
-- Database: db_sp_checklist
-- Total Products per Location: 16 items (8 water + 8 snacks)
-- Categories: เครื่องดื่ม, ขนม
-- Features: Product tracking, Category classification, User tracking
-- =====================================================
