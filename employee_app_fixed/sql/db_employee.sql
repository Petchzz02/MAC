-- OPTIONAL: โครงสร้างชื่ออังกฤษ (ใช้เมื่อย้ายจากตารางภาษาไทย)
CREATE DATABASE IF NOT EXISTS db_employee CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE db_employee;

-- สร้างตารางข้อมูลพนักงาน
CREATE TABLE IF NOT EXISTS `employees` (
  `ID` INT AUTO_INCREMENT PRIMARY KEY,
  `fname` VARCHAR(255) NOT NULL,
  `lname` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `employee_id` VARCHAR(64) NOT NULL UNIQUE,
  `Password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `employees` (`fname`, `lname`, `email`, `employee_id`, `Password`) VALUES
('เพชร', 'เนียมชาติ', 'petch1325@gmail.com', 'STD33776', '111111'),
('อรุชา', 'วัตสังข์', 'nuadultindy@gmail.com', 'STD33940', '111111');

