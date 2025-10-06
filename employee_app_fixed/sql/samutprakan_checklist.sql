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

INSERT INTO `เมืองสมุทรปราการ` (`product_code`,`product_name`,`image_path`,`status`) VALUES
  ('P1','น้ำดื่ม คริสตัล 350 มล.','/รูปMCA/คริสตัล350มล..jpg',NULL),
  ('P2','น้ำดื่ม คริสตัล 600 มล.','/รูปMCA/คริสตัล600มล..jpg',NULL),
  ('P3','น้ำดื่ม คริสตัล 1,000 มล.','/รูปMCA/คริสตัล1,000มล..jpg',NULL),
  ('P4','น้ำดื่ม คริสตัล 1,500 มล.','/รูปMCA/คริสตัล1,500มล..jpg',NULL),
  ('P5','น้ำดื่ม เนสท์เล่ 330 มล.','/รูปMCA/เนสท์เล่ 330มล.jpg',NULL),
  ('P6','น้ำดื่ม เนสท์เล่ 600 มล.','/รูปMCA/เนสท์เล่600มล..jpg',NULL),
  ('P7','น้ำดื่ม เนสท์เล่ 1,500 มล.','/รูปMCA/เนสท์เล่1,500มล.jpg',NULL),
  ('P8','น้ำดื่ม เนสท์เล่ 6,000 มล.','/รูปMCA/เนสท์เล่6,000มล.jpg',NULL);

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

INSERT INTO `พระประแดง` (`product_code`,`product_name`,`image_path`,`status`) VALUES
  ('P1','น้ำดื่ม คริสตัล 350 มล.','/รูปMCA/คริสตัล350มล..jpg',NULL),
  ('P2','น้ำดื่ม คริสตัล 600 มล.','/รูปMCA/คริสตัล600มล..jpg',NULL),
  ('P3','น้ำดื่ม คริสตัล 1,000 มล.','/รูปMCA/คริสตัล1,000มล..jpg',NULL),
  ('P4','น้ำดื่ม คริสตัล 1,500 มล.','/รูปMCA/คริสตัล1,500มล..jpg',NULL),
  ('P5','น้ำดื่ม เนสท์เล่ 330 มล.','/รูปMCA/เนสท์เล่ 330มล.jpg',NULL),
  ('P6','น้ำดื่ม เนสท์เล่ 600 มล.','/รูปMCA/เนสท์เล่600มล..jpg',NULL),
  ('P7','น้ำดื่ม เนสท์เล่ 1,500 มล.','/รูปMCA/เนสท์เล่1,500มล.jpg',NULL),
  ('P8','น้ำดื่ม เนสท์เล่ 6,000 มล.','/รูปMCA/เนสท์เล่6,000มล.jpg',NULL);

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

INSERT INTO `พระสมุทรเจดีย์` (`product_code`,`product_name`,`image_path`,`status`) VALUES
  ('P1','น้ำดื่ม คริสตัล 350 มล.','/รูปMCA/คริสตัล350มล..jpg',NULL),
  ('P2','น้ำดื่ม คริสตัล 600 มล.','/รูปMCA/คริสตัล600มล..jpg',NULL),
  ('P3','น้ำดื่ม คริสตัล 1,000 มล.','/รูปMCA/คริสตัล1,000มล..jpg',NULL),
  ('P4','น้ำดื่ม คริสตัล 1,500 มล.','/รูปMCA/คริสตัล1,500มล..jpg',NULL),
  ('P5','น้ำดื่ม เนสท์เล่ 330 มล.','/รูปMCA/เนสท์เล่ 330มล.jpg',NULL),
  ('P6','น้ำดื่ม เนสท์เล่ 600 มล.','/รูปMCA/เนสท์เล่600มล..jpg',NULL),
  ('P7','น้ำดื่ม เนสท์เล่ 1,500 มล.','/รูปMCA/เนสท์เล่1,500มล.jpg',NULL),
  ('P8','น้ำดื่ม เนสท์เล่ 6,000 มล.','/รูปMCA/เนสท์เล่6,000มล.jpg',NULL);

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

INSERT INTO `บางพลี` (`product_code`,`product_name`,`image_path`,`status`) VALUES
  ('P1','น้ำดื่ม คริสตัล 350 มล.','/รูปMCA/คริสตัล350มล..jpg',NULL),
  ('P2','น้ำดื่ม คริสตัล 600 มล.','/รูปMCA/คริสตัล600มล..jpg',NULL),
  ('P3','น้ำดื่ม คริสตัล 1,000 มล.','/รูปMCA/คริสตัล1,000มล..jpg',NULL),
  ('P4','น้ำดื่ม คริสตัล 1,500 มล.','/รูปMCA/คริสตัล1,500มล..jpg',NULL),
  ('P5','น้ำดื่ม เนสท์เล่ 330 มล.','/รูปMCA/เนสท์เล่ 330มล.jpg',NULL),
  ('P6','น้ำดื่ม เนสท์เล่ 600 มล.','/รูปMCA/เนสท์เล่600มล..jpg',NULL),
  ('P7','น้ำดื่ม เนสท์เล่ 1,500 มล.','/รูปMCA/เนสท์เล่1,500มล.jpg',NULL),
  ('P8','น้ำดื่ม เนสท์เล่ 6,000 มล.','/รูปMCA/เนสท์เล่6,000มล.jpg',NULL);

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

INSERT INTO `บางบ่อ` (`product_code`,`product_name`,`image_path`,`status`) VALUES
  ('P1','น้ำดื่ม คริสตัล 350 มล.','/รูปMCA/คริสตัล350มล..jpg',NULL),
  ('P2','น้ำดื่ม คริสตัล 600 มล.','/รูปMCA/คริสตัล600มล..jpg',NULL),
  ('P3','น้ำดื่ม คริสตัล 1,000 มล.','/รูปMCA/คริสตัล1,000มล..jpg',NULL),
  ('P4','น้ำดื่ม คริสตัล 1,500 มล.','/รูปMCA/คริสตัล1,500มล..jpg',NULL),
  ('P5','น้ำดื่ม เนสท์เล่ 330 มล.','/รูปMCA/เนสท์เล่ 330มล.jpg',NULL),
  ('P6','น้ำดื่ม เนสท์เล่ 600 มล.','/รูปMCA/เนสท์เล่600มล..jpg',NULL),
  ('P7','น้ำดื่ม เนสท์เล่ 1,500 มล.','/รูปMCA/เนสท์เล่1,500มล.jpg',NULL),
  ('P8','น้ำดื่ม เนสท์เล่ 6,000 มล.','/รูปMCA/เนสท์เล่6,000มล.jpg',NULL);

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

INSERT INTO `บางเสาธง` (`product_code`,`product_name`,`image_path`,`status`) VALUES
  ('P1','น้ำดื่ม คริสตัล 350 มล.','/รูปMCA/คริสตัล350มล..jpg',NULL),
  ('P2','น้ำดื่ม คริสตัล 600 มล.','/รูปMCA/คริสตัล600มล..jpg',NULL),
  ('P3','น้ำดื่ม คริสตัล 1,000 มล.','/รูปMCA/คริสตัล1,000มล..jpg',NULL),
  ('P4','น้ำดื่ม คริสตัล 1,500 มล.','/รูปMCA/คริสตัล1,500มล..jpg',NULL),
  ('P5','น้ำดื่ม เนสท์เล่ 330 มล.','/รูปMCA/เนสท์เล่ 330มล.jpg',NULL),
  ('P6','น้ำดื่ม เนสท์เล่ 600 มล.','/รูปMCA/เนสท์เล่600มล..jpg',NULL),
  ('P7','น้ำดื่ม เนสท์เล่ 1,500 มล.','/รูปMCA/เนสท์เล่1,500มล.jpg',NULL),
  ('P8','น้ำดื่ม เนสท์เล่ 6,000 มล.','/รูปMCA/เนสท์เล่6,000มล.jpg',NULL);