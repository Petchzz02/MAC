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
$statusData = isset($_POST['status']) ? $_POST['status'] : [];
$noteData = isset($_POST['note']) ? $_POST['note'] : [];

$updated = 0;
foreach ($statusData as $id => $status) {
    $id = (int)$id;
    $note = isset($noteData[$id]) ? trim($noteData[$id]) : '';
    
    if (empty($status)) continue;
    
    if (!in_array($status, ['in_stock', 'out_of_stock', 'not_for_sale'], true)) continue;
    
    $sql = "UPDATE `{$table}` SET `status` = ?, `note` = ? WHERE `id` = ?";
    $stmt = mysqli_prepare($conn_checklist, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ssi', $status, $note, $id);
        if (mysqli_stmt_execute($stmt)) {
            $updated++;
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn_checklist);

$_SESSION['message'] = "อัพเดตสถานะสำเร็จ {$updated} รายการ";
header('Location: checklist.php?location=' . urlencode($location));
exit;
