<?php
/**
 * logout.php
 * ไฟล์สำหรับออกจากระบบ (destroy session และลบ cookie ของ session)
 * - รีเซ็ตตัวแปร session
 * - ลบ cookie session ถ้ามี
 * - ทำลาย session และ redirect กลับไปที่หน้า login
 * หมายเหตุ: คอมเมนต์นี้เป็นเพียงคำอธิบาย ไม่ได้เปลี่ยนพฤติกรรมโค้ด
 */
require_once __DIR__ . '/config.php';

// ล้างข้อมูล session ในหน่วยความจำ
$_SESSION = [];

// ถ้าใช้ cookie สำหรับ session ให้ลบ cookie ด้วย
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time()-42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// ทำลาย session ทั้งหมด
session_destroy();

// เปลี่ยนเส้นทางกลับไปยังหน้าเข้าสู่ระบบ
header('Location: login.php');
exit;
