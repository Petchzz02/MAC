<?php
/**
 * clear_data.php
 * ลบข้อมูลและไฟล์ export ล่าสุดของสถานที่
 * Input (POST): location - ชื่อสถานที่
 */
session_start();
require_once __DIR__ . '/../../config.php';

// ตรวจสอบการเข้าสู่ระบบ
if (empty($_SESSION['user'])) {
    header('Location: ../../login.php?error=3'); 
    exit;
}

// ตรวจสอบ HTTP method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../location.php'); 
    exit;
}

// รับค่าตัวแปร
$location = trim($_POST['location'] ?? '');

// ตรวจสอบสถานที่
$locations = ['เมืองสมุทรปราการ', 'พระประแดง', 'พระสมุทรเจดีย์', 'บางพลี', 'บางบ่อ', 'บางเสาธง'];

if (!in_array($location, $locations, true)) {
    $_SESSION['error'] = 'สถานที่ไม่ถูกต้อง';
    header('Location: ../location.php'); 
    exit;
}

try {
    // เชื่อมต่อฐานข้อมูล
    $conn = getChecklistConnection();
    if (!$conn) {
        throw new Exception('ไม่สามารถเชื่อมต่อฐานข้อมูลได้');
    }
    
    // เพิ่มการตั้งค่า charset
    mysqli_query($conn, "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    mysqli_query($conn, "SET character_set_connection=utf8mb4");
    mysqli_query($conn, "SET character_set_client=utf8mb4");
    mysqli_query($conn, "SET character_set_results=utf8mb4");
    
    // ตรวจสอบตาราง
    $table = $location;
    $valid_tables = ['เมืองสมุทรปราการ', 'พระประแดง', 'พระสมุทรเจดีย์', 'บางพลี', 'บางบ่อ', 'บางเสาธง'];
    
    if (!in_array($table, $valid_tables, true)) {
        throw new Exception("ตารางไม่ถูกต้อง: {$table}");
    }
    
    // ตรวจสอบว่าตารางมีอยู่จริงในฐานข้อมูล
    $tables_query = "SHOW TABLES";
    $tables_result = mysqli_query($conn, $tables_query);
    $table_exists = false;
    
    if ($tables_result) {
        while ($row = mysqli_fetch_row($tables_result)) {
            if ($row[0] === $table) {
                $table_exists = true;
                break;
            }
        }
    }
    
    if (!$table_exists) {
        throw new Exception("ไม่พบตาราง: {$table}");
    }
    
    // เริ่ม transaction
    mysqli_begin_transaction($conn);
    
    // ล้างข้อมูลในฐานข้อมูล (รีเซ็ตเป็นค่าเริ่มต้น)
    $escaped_table = mysqli_real_escape_string($conn, $table);
    $clear_sql = sprintf(
        "UPDATE `%s` SET `status` = '', `note` = '', `updated_at` = NOW()",
        $escaped_table
    );
    
    $clear_result = mysqli_query($conn, $clear_sql);
    if (!$clear_result) {
        throw new Exception("ไม่สามารถล้างข้อมูลในฐานข้อมูลได้: " . mysqli_error($conn));
    }
    
    $affected_rows = mysqli_affected_rows($conn);
    
    // Commit transaction
    mysqli_commit($conn);
    mysqli_close($conn);
    
    // ลบไฟล์ export ล่าสุดของสถานที่นี้
    $deleted_files = deleteLatestExportFiles($location);
    
    // สร้างข้อความสำเร็จ
    $message = "ล้างข้อมูลเรียบร้อยแล้ว ({$affected_rows} รายการ)";
    $_SESSION['message'] = $message;
    
} catch (Exception $e) {
    // Rollback transaction หากมี connection
    if (isset($conn) && $conn) {
        mysqli_rollback($conn);
        mysqli_close($conn);
    }
    
    $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
    error_log("Clear data error: " . $e->getMessage());
}

// กลับไปหน้า checklist
header('Location: checklist.php?location=' . urlencode($location));
exit;

/**
 * ลบไฟล์ export ล่าสุดของสถานที่
 */
function deleteLatestExportFiles($location) {
    $exportDir = __DIR__ . '/../../exports';
    $deleted_count = 0;
    
    try {
        if (!is_dir($exportDir)) {
            return 0;
        }
        
        // ดึงรายการไฟล์ทั้งหมดใน exports directory
        $files = glob($exportDir . '/checklist_export_*.sql');
        
        if (empty($files)) {
            return 0;
        }
        
        // เรียงลำดับไฟล์ตามวันที่ล่าสุด (โดยใช้ชื่อไฟล์)
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        // ตรวจสอบไฟล์ล่าสุดว่าเกี่ยวข้องกับสถานที่นี้หรือไม่
        // โดยการอ่านเนื้อหาไฟล์และตรวจสอบ comment header
        foreach ($files as $file) {
            $content = file_get_contents($file);
            
            // ตรวจสอบว่าไฟล์นี้เป็นของสถานที่ที่ระบุหรือไม่
            if (strpos($content, "-- Export สำหรับ {$location}") !== false) {
                // ลบไฟล์
                if (unlink($file)) {
                    $deleted_count++;
                    error_log("Deleted export file: " . basename($file) . " for location: {$location}");
                    
                    // ลบเฉพาะไฟล์ล่าสุดของสถานที่นี้
                    break;
                }
            }
        }
        
    } catch (Exception $e) {
        error_log("Error deleting export files: " . $e->getMessage());
    }
    
    return $deleted_count;
}
?>