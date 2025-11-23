<?php
// ทดสอบ SQL statement ที่จะใช้ใน save.php

require_once '../../config.php';

$conn = getChecklistConnection();
if (!$conn) {
    echo "Connection failed!";
    exit;
}

$table = 'เมืองสมุทรปราการ';
$escaped_table = mysqli_real_escape_string($conn, $table);

// ทดสอบ SQL ที่จะใช้จริง
$sql = sprintf(
    "UPDATE `%s` SET `status` = ?, `note` = ?, `updated_at` = NOW() WHERE `id` = ?",
    $escaped_table
);

echo "SQL Statement to be used:\n";
echo $sql . "\n\n";

// ทดสอบ prepare
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    echo "✅ SQL prepare successful!\n";
    mysqli_stmt_close($stmt);
} else {
    echo "❌ SQL prepare failed: " . mysqli_error($conn) . "\n";
}

// ตรวจสอบ table structure
echo "\nTable structure:\n";
$desc_sql = "DESCRIBE `$escaped_table`";
$result = mysqli_query($conn, $desc_sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
} else {
    echo "Error describing table: " . mysqli_error($conn) . "\n";
}

// ไม่ต้องปิด connection เองเพราะ Database class จะจัดการให้
?>