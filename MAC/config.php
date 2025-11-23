<?php
/**
 * config.php
 * ไฟล์ตั้งค่ากลางสำหรับโปรเจกต์ (ปรับปรุงใหม่ - ลดความซ้ำซ้อน)
 * - ใช้ DatabaseHelper class เพื่อจัดการฐานข้อมูลแบบมีประสิทธิภาพ
 * - รองรับ backward compatibility สำหรับโค้ดเก่า
 * - ตั้งค่า charset เป็น utf8mb4 เพื่อรองรับภาษาไทยและ emoji
 * - เริ่ม session หากยังไม่ได้เริ่ม
 * หมายเหตุ: อย่าเก็บรหัสผ่านจริงในไฟล์นี้เมื่อขึ้นโปรดักชัน ใช้ตัวแปรแวดล้อมแทน
 */

// เปิด error เฉพาะช่วง dev (ลบทิ้งในโปรดักชัน)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// โหลด DatabaseHelper class
require_once __DIR__ . '/includes/database_helper.php';

// สร้าง global database instance
$db = Database::getInstance();

// Backward compatibility - ตัวแปรเดิมที่โค้ดอื่นอาจใช้
$conn = $db->getEmployeeConnection();

// ตรวจสอบการเชื่อมต่อ
if (!$conn) {
    die('Database connection failed: Unable to connect to employee database');
}

// Backward compatibility - ฟังก์ชันเดิม
function getChecklistConnection(): ?mysqli {
    global $db;
    return $db->getChecklistConnection();
}

// เริ่ม session ให้ทุกหน้าที่ include ไฟล์นี้ (แต่จะไม่ทำซ้ำถ้าเริ่มแล้ว)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ฟังก์ชันช่วยเหลือเพิ่มเติม
function isValidLocation(string $location): bool {
    global $db;
    return $db->isValidLocation($location);
}

function getValidLocations(): array {
    global $db;
    return $db->getValidLocations();
}
