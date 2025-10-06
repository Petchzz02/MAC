<?php
/**
 * clear_latest.php
 * เคลียร์ข้อมูลที่อัปเดตล่าสุดในตาราง checklist
 * Input: location (GET/POST)
 */
session_start();
require_once __DIR__ . '/../../config.php';

// ตรวจสอบการล็อกอิน
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบก่อน';
    header('Location: ../../login.php?error=3');
    exit;
}

$location = trim($_POST['location'] ?? $_GET['location'] ?? '');

// ตรวจสอบสถานที่
$validLocations = [
    'เมืองสมุทรปราการ',
    'พระประแดง',
    'พระสมุทรเจดีย์',
    'บางพลี',
    'บางบ่อ',
    'บางเสาธง'
];

if (!in_array($location, $validLocations)) {
    $_SESSION['error'] = 'สถานที่ไม่ถูกต้อง';
    header('Location: ../location.php');
    exit;
}

try {
    $conn = getChecklistConnection();
    if (!$conn) {
        throw new Exception('ไม่สามารถเชื่อมต่อฐานข้อมูลได้');
    }
    
    $tableName = $location;
    $user_id = $_SESSION['user']['employee_id'] ?? $_SESSION['user']['id'];
    
    // ตรวจสอบว่าตารางมีอยู่จริง
    $table_check = mysqli_prepare($conn, "SHOW TABLES LIKE ?");
    mysqli_stmt_bind_param($table_check, 's', $tableName);
    mysqli_stmt_execute($table_check);
    $table_result = mysqli_stmt_get_result($table_check);
    mysqli_stmt_close($table_check);
    
    if (mysqli_num_rows($table_result) === 0) {
        throw new Exception("ไม่พบตาราง: {$tableName}");
    }
    
    // เริ่มต้น Transaction
    mysqli_begin_transaction($conn);
    
    // หา timestamp ล่าสุดที่มีการอัปเดต
    $latestQuery = "SELECT MAX(updated_at) as latest_update FROM `{$tableName}` WHERE updated_at IS NOT NULL";
    $latestResult = mysqli_query($conn, $latestQuery);
    
    if (!$latestResult) {
        throw new Exception("ไม่สามารถดึงข้อมูลการอัปเดตล่าสุดได้: " . mysqli_error($conn));
    }
    
    $latestRow = mysqli_fetch_assoc($latestResult);
    $latestUpdate = $latestRow['latest_update'];
    
    if (!$latestUpdate) {
        $_SESSION['message'] = 'ไม่มีข้อมูลที่จะเคลียร์';
        header('Location: checklist.php?location=' . urlencode($location));
        exit;
    }
    
    // นับจำนวนรายการที่จะถูกเคลียร์
    $countQuery = "SELECT COUNT(*) as count FROM `{$tableName}` WHERE updated_at = ?";
    $countStmt = mysqli_prepare($conn, $countQuery);
    mysqli_stmt_bind_param($countStmt, 's', $latestUpdate);
    mysqli_stmt_execute($countStmt);
    $countResult = mysqli_stmt_get_result($countStmt);
    $countRow = mysqli_fetch_assoc($countResult);
    $itemsToDelete = $countRow['count'];
    mysqli_stmt_close($countStmt);
    
    // รีเซ็ตข้อมูลที่อัปเดตในเวลาล่าสุด
    $clearQuery = "UPDATE `{$tableName}` 
                   SET `status` = NULL, 
                       `note` = NULL, 
                       `updated_at` = NULL,
                       `updated_by` = NULL
                   WHERE `updated_at` = ?";
    
    $clearStmt = mysqli_prepare($conn, $clearQuery);
    if (!$clearStmt) {
        throw new Exception("ไม่สามารถเตรียม statement ได้: " . mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($clearStmt, 's', $latestUpdate);
    
    if (!mysqli_stmt_execute($clearStmt)) {
        throw new Exception("ไม่สามารถเคลียร์ข้อมูลได้: " . mysqli_stmt_error($clearStmt));
    }
    
    $affectedRows = mysqli_stmt_affected_rows($clearStmt);
    mysqli_stmt_close($clearStmt);
    
    // Commit transaction
    mysqli_commit($conn);
    
    // สร้างไฟล์ log การเคลียร์
    createClearLog($location, $latestUpdate, $user_id, $affectedRows);
    
    $_SESSION['message'] = "เคลียร์ข้อมูลล่าสุดเรียบร้อยแล้ว ({$affectedRows} รายการ)";
    
} catch (Exception $e) {
    // Rollback transaction หากมีข้อผิดพลาด
    if (isset($conn)) {
        mysqli_rollback($conn);
    }
    $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
    error_log("Clear latest error: " . $e->getMessage());
}

// ปิดการเชื่อมต่อฐานข้อมูล
if (isset($conn)) {
    mysqli_close($conn);
}

// กลับไปหน้า checklist
header('Location: checklist.php?location=' . urlencode($location));
exit;

/**
 * สร้างไฟล์ log การเคลียร์ข้อมูล
 */
function createClearLog($location, $clearedTimestamp, $userId, $affectedRows) {
    try {
        $logDir = __DIR__ . '/../../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logFile = $logDir . '/clear_log_' . date('Y-m-d') . '.txt';
        $logEntry = sprintf(
            "[%s] Location: %s | Cleared Timestamp: %s | User: %s | Affected Rows: %d\n",
            date('Y-m-d H:i:s'),
            $location,
            $clearedTimestamp,
            $userId,
            $affectedRows
        );
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    } catch (Exception $e) {
        error_log("Clear log error: " . $e->getMessage());
    }
}
?>