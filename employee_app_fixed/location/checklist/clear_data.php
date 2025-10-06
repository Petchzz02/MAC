<?php
require_once __DIR__ . '/../../config.php';
if (empty($_SESSION['user'])) {
    header('Location: ../../login.php?error=3'); 
    exit;
}

// ตรวจสอบว่าเป็น POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../location.php'); 
    exit;
}

$location = isset($_POST['location']) ? $_POST['location'] : '';
$locations = ['เมืองสมุทรปราการ', 'พระประแดง', 'พระสมุทรเจดีย์', 'บางพลี', 'บางบ่อ', 'บางเสาธง'];

if (!in_array($location, $locations, true)) { 
    header('Location: ../location.php'); 
    exit; 
}

// เชื่อมต่อฐานข้อมูล checklist
$conn_checklist = getChecklistConnection();
if (!$conn_checklist) {
    $_SESSION['error'] = 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้';
    header('Location: checklist.php?location=' . urlencode($location));
    exit;
}

try {
    // เลือกวิธีการลบข้อมูล
    $action = isset($_POST['action']) ? $_POST['action'] : 'reset';
    
    $table = mysqli_real_escape_string($conn_checklist, $location);
    
    if ($action === 'delete_all') {
        // ลบข้อมูลทั้งหมดออกจากตาราง (ลบแถวทั้งหมด)
        $sql = "DELETE FROM `{$table}` WHERE 1";
        $success_message = 'ลบข้อมูลทั้งหมดออกจากฐานข้อมูลเรียบร้อยแล้ว';
    } else {
        // รีเซ็ตข้อมูลการตรวจสอบ (เก็บสินค้าไว้แต่รีเซ็ตสถานะ)
        $sql = "UPDATE `{$table}` SET `status` = NULL, `note` = NULL WHERE 1";
        $success_message = 'รีเซ็ตข้อมูลการตรวจสอบเรียบร้อยแล้ว คุณสามารถเริ่มกรอกข้อมูลใหม่ได้';
    }
    
    if (mysqli_query($conn_checklist, $sql)) {
        $_SESSION['message'] = $success_message;
    } else {
        $_SESSION['error'] = 'เกิดข้อผิดพลาดในการดำเนินการ: ' . mysqli_error($conn_checklist);
    }
} catch (Exception $e) {
    $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
}

mysqli_close($conn_checklist);

// กลับไปหน้า checklist
header('Location: checklist.php?location=' . urlencode($location));
exit;
?>