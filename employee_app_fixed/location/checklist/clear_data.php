<?php
/**
 * clear_data.php
 * ลบหรือรีเซ็ตข้อมูลในตาราง
 * Input (POST):
 * - location (string) - ชื่อสถานที่
 * - action (string) - 'reset' หรือ 'delete_all'
 */
require_once __DIR__ . '/../../config.php';

if (empty($_SESSION['user'])) {
  header('Location: ../../login.php?error=3'); 
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../location.php'); 
    exit;
}

$location = isset($_POST['location']) ? $_POST['location'] : '';
$action = isset($_POST['action']) ? $_POST['action'] : '';

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

$success = false;
$message = '';

if ($action === 'reset') {
    // รีเซ็ตเฉพาะ status และ note
    $sql = "UPDATE `{$location}` SET `status` = NULL, `note` = NULL";
    $result = mysqli_query($conn_checklist, $sql);
    
    if ($result) {
        $affected_rows = mysqli_affected_rows($conn_checklist);
        $success = true;
        $message = "รีเซ็ตข้อมูลสำเร็จ {$affected_rows} รายการ";
    } else {
        $message = "เกิดข้อผิดพลาดในการรีเซ็ตข้อมูล: " . mysqli_error($conn_checklist);
    }
    
} elseif ($action === 'delete_all') {
    // ลบข้อมูลทั้งหมด
    $sql = "DELETE FROM `{$location}`";
    $result = mysqli_query($conn_checklist, $sql);
    
    if ($result) {
        $affected_rows = mysqli_affected_rows($conn_checklist);
        $success = true;
        $message = "ลบข้อมูลทั้งหมดสำเร็จ {$affected_rows} รายการ";
    } else {
        $message = "เกิดข้อผิดพลาดในการลบข้อมูล: " . mysqli_error($conn_checklist);
    }
    
} else {
    $message = "การดำเนินการไม่ถูกต้อง";
}

mysqli_close($conn_checklist);

// ตั้งค่าข้อความผลลัพธ์
if ($success) {
    $_SESSION['message'] = $message;
} else {
    $_SESSION['error'] = $message;
}

header('Location: checklist.php?location=' . urlencode($location));
exit;
?>