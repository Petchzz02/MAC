<?php
require_once __DIR__ . '/../../config.php';
if (empty($_SESSION['user'])) {
    header('Location: ../../login.php?error=3'); exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: ../location.php'); exit; 
}

$location = isset($_POST['location']) ? $_POST['location'] : '';
$locations = ['เมืองสมุทรปราการ', 'พระประแดง', 'พระสมุทรเจดีย์', 'บางพลี', 'บางบ่อ', 'บางเสาธง'];

if (!in_array($location, $locations, true)) { 
    die('สถานที่ไม่ถูกต้อง'); 
}

// เชื่อมต่อฐานข้อมูล checklist
$conn_checklist = getChecklistConnection();
if (!$conn_checklist) {
    die('ไม่สามารถเชื่อมต่อฐานข้อมูลได้');
}

$table = $location;

$code = isset($_POST['product_code']) ? trim($_POST['product_code']) : '';
$name = isset($_POST['product_name']) ? trim($_POST['product_name']) : '';
$img  = isset($_POST['image_path']) ? trim($_POST['image_path']) : NULL;
$st   = isset($_POST['status']) && $_POST['status'] !== '' ? $_POST['status'] : NULL;

if ($code === '' || $name === '') { 
    $_SESSION['error'] = 'ข้อมูลไม่ครบ'; 
    header('Location: checklist.php?location=' . urlencode($location));
    exit;
}

if ($st !== NULL && !in_array($st, ['in_stock','out_of_stock','not_for_sale'], true)) { 
    $st = NULL; 
}

$sql = "INSERT INTO `{$table}` (`product_code`,`product_name`,`image_path`,`status`) VALUES (?,?,?,?)";
$stmt = mysqli_prepare($conn_checklist, $sql);
if (!$stmt) { 
    $_SESSION['error'] = 'Prepare failed: '.mysqli_error($conn_checklist); 
    header('Location: checklist.php?location=' . urlencode($location));
    exit;
}

mysqli_stmt_bind_param($stmt, 'ssss', $code, $name, $img, $st);
if (!mysqli_stmt_execute($stmt)) {
    if (mysqli_errno($conn_checklist) == 1062) { 
        $_SESSION['error'] = 'รหัสสินค้านี้มีอยู่แล้ว'; 
    } else {
        $_SESSION['error'] = 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล';
    }
} else {
    $_SESSION['message'] = 'เพิ่มสินค้าใหม่สำเร็จ';
}

mysqli_stmt_close($stmt);
mysqli_close($conn_checklist);
header('Location: checklist.php?location=' . urlencode($location));
exit;
