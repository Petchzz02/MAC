<?php
/**
 * add.php
 * เพิ่มรายการสินค้าใหม่ในตาราง
 * Input (POST):
 * - location (string) - ชื่อสถานที่
 * - product_code (string) - รหัสสินค้า
 * - product_name (string) - ชื่อสินค้า
 * - image_path (string) - path รูปภาพ (optional)
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
$product_code = isset($_POST['product_code']) ? trim($_POST['product_code']) : '';
$product_name = isset($_POST['product_name']) ? trim($_POST['product_name']) : '';
$image_path = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';

$locations = ['เมืองสมุทรปราการ', 'พระประแดง', 'พระสมุทรเจดีย์', 'บางพลี', 'บางบ่อ', 'บางเสาธง'];

if (!in_array($location, $locations, true)) { 
    header('Location: ../location.php'); 
    exit; 
}

if (empty($product_code) || empty($product_name)) {
    $_SESSION['error'] = 'กรุณากรอกรหัสสินค้าและชื่อสินค้า';
    header('Location: checklist.php?location=' . urlencode($location)); 
    exit;
}

// เชื่อมต่อฐานข้อมูล checklist
$conn_checklist = getChecklistConnection();
if (!$conn_checklist) {
    $_SESSION['error'] = 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้';
    header('Location: checklist.php?location=' . urlencode($location)); 
    exit;
}

// เพิ่มรายการใหม่
$sql = "INSERT INTO `{$location}` (`product_code`, `product_name`, `image_path`, `status`) VALUES (?, ?, ?, 'in_stock')";
$stmt = mysqli_prepare($conn_checklist, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'sss', $product_code, $product_name, $image_path);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "เพิ่มรายการสินค้าใหม่สำเร็จ: {$product_name}";
    } else {
        $error = mysqli_stmt_error($stmt);
        if (strpos($error, 'Duplicate entry') !== false) {
            $_SESSION['error'] = "รหัสสินค้า {$product_code} มีอยู่แล้วในระบบ";
        } else {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการเพิ่มรายการ: " . $error;
        }
    }
    
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['error'] = "เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL";
}

mysqli_close($conn_checklist);

header('Location: checklist.php?location=' . urlencode($location));
exit;
?>