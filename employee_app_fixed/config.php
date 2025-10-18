<?php
/**
 * config.php
 * ไฟล์ตั้งค่ากลางสำหรับโปรเจกต์
 * - กำหนดการเชื่อมต่อฐานข้อมูลหลัก (db_employee)
 * - ฟังก์ชันช่วยเชื่อมต่อฐานข้อมูล checklist (db_sp_checklist)
 * - ตั้งค่า charset เป็น utf8mb4 เพื่อรองรับภาษาไทยและ emoji
 * - เริ่ม session หากยังไม่ได้เริ่ม
 * หมายเหตุ: อย่าเก็บรหัสผ่านจริงในไฟล์นี้เมื่อขึ้นโปรดักชัน ใช้ตัวแปรแวดล้อมแทน
 */

// เปิด error เฉพาะช่วง dev (ลบทิ้งในโปรดักชัน)
ini_set('display_errors', 1);
error_reporting(E_ALL);

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'db_employee';

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// สำคัญมาก: ตั้ง charset ให้รองรับภาษาไทย และป้องกันปัญหาการเข้ารหัส
if (!mysqli_set_charset($conn, 'utf8mb4')) {
    die('Error loading character set utf8mb4: ' . mysqli_error($conn));
}

// ฟังก์ชันสำหรับเชื่อมต่อกับฐานข้อมูล checklist
// คืนค่า connection object หรือ null เมื่อเชื่อมต่อไม่สำเร็จ
function getChecklistConnection() {
    $DB_HOST = 'localhost';
    $DB_USER = 'root';
    $DB_PASS = '';
    $DB_CHECKLIST = 'db_sp_checklist'; // ใช้ฐานข้อมูล checklist ที่ถูกต้อง
    
    $conn_checklist = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_CHECKLIST);
    if (!$conn_checklist) {
        error_log("Failed to connect to checklist database: " . mysqli_connect_error());
        return null;
    }
    
    // ตั้งค่า charset และ collation เพื่อรองรับภาษาไทย
    if (!mysqli_set_charset($conn_checklist, 'utf8mb4')) {
        error_log("Error setting charset to utf8mb4: " . mysqli_error($conn_checklist));
        mysqli_close($conn_checklist);
        return null;
    }
    
    // ตั้งค่า SQL mode ให้เข้มงวดน้อยลง (ปรับตามต้องการ)
    mysqli_query($conn_checklist, "SET sql_mode = ''");
    
    // ตั้งค่า timezone (ถ้าต้องการ)
    mysqli_query($conn_checklist, "SET time_zone = '+07:00'");
    
    return $conn_checklist;
}

// เริ่ม session ให้ทุกหน้าที่ include ไฟล์นี้ (แต่จะไม่ทำซ้ำถ้าเริ่มแล้ว)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
