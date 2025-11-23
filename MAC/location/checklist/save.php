<?php
/**
 * save.php
 * บันทึกข้อมูลการตรวจสอบสินค้าจากฟอร์ม
 * Input (POST):
 * - location (string) - ชื่อ        // อัปเดตข้อมูลด้วย Prepared Statement
        // ใช้ sprintf เพื่อป้องกันปัญหา SQL injection โดยไม่ใช้ placeholder สำหรับ table name
        $escaped_table = mysqli_real_escape_string($conn, $table);
        $sql = sprintf(
            "UPDATE `%s` SET `status` = ?, `note` = ?, `updated_at` = NOW() WHERE `id` = ?",
            $escaped_table
        );
        
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception("ไม่สามารถเตรียม statement ได้: " . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($stmt, 'ssi', $status, $note, $id);status[id] (array) - สถานะของแต่ละรายการ
 * - note[id] (array) - หมายเหตุของแต่ละรายการ
 */
session_start();
require_once __DIR__ . '/../../config.php';

if (empty($_SESSION['user'])) {
    header('Location: ../../login.php?error=3'); 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../location.php'); 
    exit;
}

// รับค่าตัวแปร
$location = trim($_POST['location'] ?? '');
$status_array = $_POST['status'] ?? [];
$note_array = $_POST['note'] ?? [];

// ตรวจสอบข้อมูล
$locations = ['เมืองสมุทรปราการ', 'พระประแดง', 'พระสมุทรเจดีย์', 'บางพลี', 'บางบ่อ', 'บางเสาธง'];

if (!in_array($location, $locations, true)) {
    $_SESSION['error'] = 'สถานที่ไม่ถูกต้อง';
    header('Location: ../location.php'); 
    exit;
}

if (empty($status_array) || empty($note_array)) {
    $_SESSION['error'] = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    header('Location: checklist.php?location=' . urlencode($location)); 
    exit;
}

// เชื่อมต่อฐานข้อมูล
    $conn = getChecklistConnection();
    if (!$conn) {
        $_SESSION['error'] = 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้';
        header('Location: checklist.php?location=' . urlencode($location)); 
        exit;
    }
    
    // เพิ่มการตั้งค่า charset
    mysqli_query($conn, "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    mysqli_query($conn, "SET character_set_connection=utf8mb4");
    mysqli_query($conn, "SET character_set_client=utf8mb4");
    mysqli_query($conn, "SET character_set_results=utf8mb4");
    
    // เพิ่มการ debug
    error_log("=== Save.php Debug Info ===");
    error_log("Location: " . $location);
    error_log("Status array count: " . count($status_array));
    error_log("Note array count: " . count($note_array));// เริ่มต้น Transaction
mysqli_begin_transaction($conn);

try {
    $updated_count = 0;
    $table = $location;
    
    // ตรวจสอบว่าตารางมีอยู่จริง โดยใช้วิธีที่ปลอดภัย
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
    
    foreach ($status_array as $id => $status) {
        $note = trim($note_array[$id] ?? '');
        
        // Validate input
        $id = (int)$id;
        if ($id <= 0) {
            continue;
        }
        
        // Validate status
        $valid_statuses = ['in_stock', 'out_of_stock', 'not_for_sale'];
        if (!in_array($status, $valid_statuses)) {
            continue;
        }
        
        // Validate note (required)
        if (empty($note)) {
            throw new Exception("หมายเหตุสำหรับรายการ ID: {$id} ไม่สามารถเว้นว่างได้");
        }
        
        // อัปเดตข้อมูลด้วย Prepared Statement
        // ใช้ sprintf เพื่อป้องกันปัญหา SQL injection โดยไม่ใช้ placeholder สำหรับ table name
        $escaped_table = mysqli_real_escape_string($conn, $table);
        $sql = sprintf(
            "UPDATE `%s` SET `status` = ?, `note` = ?, `updated_at` = NOW() WHERE `id` = ?",
            $escaped_table
        );
        
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception("ไม่สามารถเตรียม SQL statement ได้: " . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($stmt, 'ssi', $status, $note, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $updated_count++;
            }
        } else {
            mysqli_stmt_close($stmt);
            throw new Exception("ไม่สามารถอัปเดตข้อมูลรายการ ID: {$id} ได้");
        }
        
        mysqli_stmt_close($stmt);
    }
    
    // Commit transaction
    mysqli_commit($conn);
    
    // สร้างไฟล์ SQL Export
    createSqlExport($conn, $location, $table);
    
    $_SESSION['message'] = "บันทึกข้อมูลเรียบร้อยแล้ว ({$updated_count} รายการ)";
    
} catch (Exception $e) {
    // Rollback transaction
    mysqli_rollback($conn);
    $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
}

// ไม่ต้องปิด connection เองเพราะ Database class จะจัดการให้

// กลับไปหน้า checklist
header('Location: checklist.php?location=' . urlencode($location));
exit;

/**
 * สร้างไฟล์ SQL Export
 */
function createSqlExport($conn, $location, $table) {
    try {
        // สร้างโฟลเดอร์ exports หากไม่มี
        $exportDir = __DIR__ . '/../../exports';
        if (!is_dir($exportDir)) {
            mkdir($exportDir, 0755, true);
        }
        
        // ดึงข้อมูลทั้งหมดจากตาราง
        $sql = "SELECT * FROM `{$table}` ORDER BY `id` ASC";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $exportFile = $exportDir . '/checklist_export_' . date('Y-m-d_H-i-s') . '.sql';
            $sqlContent = "-- Export สำหรับ {$location}\n";
            $sqlContent .= "-- สร้างเมื่อ: " . date('Y-m-d H:i:s') . "\n";
            $sqlContent .= "-- ระบบ: Employee Checklist System\n\n";
            
            $sqlContent .= "DROP TABLE IF EXISTS `{$table}_backup`;\n";
            $sqlContent .= "CREATE TABLE `{$table}_backup` LIKE `{$table}`;\n\n";
            
            while ($row = mysqli_fetch_assoc($result)) {
                $values = [];
                foreach ($row as $value) {
                    if ($value === null) {
                        $values[] = 'NULL';
                    } else {
                        $values[] = "'" . mysqli_real_escape_string($conn, $value) . "'";
                    }
                }
                $sqlContent .= "INSERT INTO `{$table}_backup` VALUES (" . implode(', ', $values) . ");\n";
            }
            
            file_put_contents($exportFile, $sqlContent);
        }
    } catch (Exception $e) {
        // ไม่ให้ error ใน export ส่งผลต่อการบันทึกหลัก
        error_log("Export error: " . $e->getMessage());
    }
}
?>