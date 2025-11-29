-- Export สำหรับ พระประแดง
-- สร้างเมื่อ: 2025-11-29 03:34:41
-- ระบบ: Employee Checklist System

DROP TABLE IF EXISTS `พระประแดง_backup`;
CREATE TABLE `พระประแดง_backup` LIKE `พระประแดง`;

INSERT INTO `พระประแดง_backup` VALUES ('1', 'P1', 'น้ำดื่ม คริสตัล 350 มล.', 'เครื่องดื่ม', '../../image/products/water/คริสตัล350มล..jpg', 'in_stock', '1', '2025-11-29 09:34:41', NULL);
INSERT INTO `พระประแดง_backup` VALUES ('2', 'P2', 'น้ำดื่ม คริสตัล 600 มล.', 'เครื่องดื่ม', '../../image/products/water/คริสตัล600มล..jpg', 'out_of_stock', '1', '2025-11-29 09:34:41', NULL);
INSERT INTO `พระประแดง_backup` VALUES ('3', 'P3', 'น้ำดื่ม คริสตัล 1,000 มล.', 'เครื่องดื่ม', '../../image/products/water/คริสตัล1,000มล..jpg', 'in_stock', '1', '2025-11-29 09:34:41', NULL);
INSERT INTO `พระประแดง_backup` VALUES ('4', 'P4', 'น้ำดื่ม คริสตัล 1,500 มล.', 'เครื่องดื่ม', '../../image/products/water/คริสตัล1,500มล..jpg', 'not_for_sale', '1', '2025-11-29 09:34:41', NULL);
INSERT INTO `พระประแดง_backup` VALUES ('5', 'P5', 'น้ำดื่ม เนสท์เล่ 330 มล.', 'เครื่องดื่ม', '../../image/products/water/เนสท์เล่ 330มล.jpg', 'in_stock', '1', '2025-11-29 09:34:41', NULL);
INSERT INTO `พระประแดง_backup` VALUES ('6', 'P6', 'น้ำดื่ม เนสท์เล่ 600 มล.', 'เครื่องดื่ม', '../../image/products/water/เนสท์เล่600มล..jpg', 'out_of_stock', 'รอสิ้นค้าจัดส่ง', '2025-11-29 09:34:41', NULL);
INSERT INTO `พระประแดง_backup` VALUES ('7', 'P7', 'น้ำดื่ม เนสท์เล่ 1,500 มล.', 'เครื่องดื่ม', '../../image/products/water/เนสท์เล่1,500มล.jpg', 'in_stock', '10 แพ็ค', '2025-11-29 09:34:41', NULL);
INSERT INTO `พระประแดง_backup` VALUES ('8', 'P8', 'น้ำดื่ม เนสท์เล่ 6,000 มล.', 'เครื่องดื่ม', '../../image/products/water/เนสท์เล่6,000มล.jpg', 'not_for_sale', 'ยกเลิกการสั่งซื้อ', '2025-11-29 09:34:41', NULL);
INSERT INTO `พระประแดง_backup` VALUES ('9', 'S1', 'คอนเน่', 'ขนม', '../../image/products/snack/คอนเน่.png', 'in_stock', '1', '2025-11-29 09:34:41', NULL);
INSERT INTO `พระประแดง_backup` VALUES ('10', 'S2', 'ซันไบทส์', 'ขนม', '../../image/products/snack/ซันไบทส์.png', 'not_for_sale', '1', '2025-11-29 09:34:41', NULL);
INSERT INTO `พระประแดง_backup` VALUES ('11', 'S3', 'โดริโทส', 'ขนม', '../../image/products/snack/โดริโทส.png', 'out_of_stock', '1', '2025-11-29 09:34:41', NULL);
INSERT INTO `พระประแดง_backup` VALUES ('12', 'S4', 'โตโร', 'ขนม', '../../image/products/snack/โตโร.png', 'in_stock', '1', '2025-11-29 09:34:41', NULL);
INSERT INTO `พระประแดง_backup` VALUES ('13', 'S5', 'เทสโต', 'ขนม', '../../image/products/snack/เทสโต.png', 'in_stock', '1', '2025-11-29 09:34:41', NULL);
INSERT INTO `พระประแดง_backup` VALUES ('14', 'S6', 'เลย์', 'ขนม', '../../image/products/snack/เลย์.png', 'not_for_sale', '1', '2025-11-29 09:34:41', NULL);
INSERT INTO `พระประแดง_backup` VALUES ('15', 'S7', 'โลตัส ขาไก่', 'ขนม', '../../image/products/snack/โลตัส ขาไก่.png', 'in_stock', '1', '2025-11-29 09:34:41', NULL);
INSERT INTO `พระประแดง_backup` VALUES ('16', 'S8', 'อาริงาโต', 'ขนม', '../../image/products/snack/อาริงาโต.png', 'out_of_stock', '1', '2025-11-29 09:34:41', NULL);
