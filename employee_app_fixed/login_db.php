<?php
require_once __DIR__ . '/config.php';

try {
    // ป้องกันการยิงตรงนอก POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: login.php'); exit;
    }

    // รับค่าและ trim
    $employee_id = isset($_POST['employee_id']) ? trim($_POST['employee_id']) : '';
    $password    = isset($_POST['password']) ? $_POST['password'] : '';

    if ($employee_id === '' || $password === '') {
        header('Location: login.php?error=1'); exit;
    }

    // ใช้ชื่อตารางภาษาอังกฤษ
    $sql = "SELECT `ID`,`fname`,`lname`,`email`,`employee_id`,`Password`
            FROM `employees`
            WHERE `employee_id` = ?
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) { throw new Exception('Prepare failed: '.mysqli_error($conn)); }

    mysqli_stmt_bind_param($stmt, 's', $employee_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $stored = $row['Password']; // อาจเป็น hash หรือ plain เดิม

        $login_ok = false;

        // เคสปกติ: เป็น hash แล้ว
        if (strlen($stored) >= 55 && password_get_info($stored)['algo']) {
            $login_ok = password_verify($password, $stored);
        } else {
            // เคสเก่า: เก็บเป็น plain text — เปรียบเทียบตรง
            if (hash_equals($stored, $password)) {
                $login_ok = true;

                // auto-migrate: อัปเดตเป็น hash ทันที
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $up = mysqli_prepare($conn, "UPDATE `employees` SET `Password` = ? WHERE `ID` = ?");
                if ($up) {
                    mysqli_stmt_bind_param($up, 'si', $newHash, $row['ID']);
                    mysqli_stmt_execute($up);
                    mysqli_stmt_close($up);
                }
            }
        }

        if ($login_ok) {
            // ตั้ง session
            $_SESSION['user'] = [
                'id' => (int)$row['ID'],
                'employee_id' => $row['employee_id'],
                'fname' => $row['fname'],
                'lname' => $row['lname'],
                'name' => $row['fname'].' '.$row['lname'],  
                'email' => $row['email']
            ];
            // ป้องกัน session fixation
            session_regenerate_id(true);
            header('Location: index.php'); exit;
        }
    }

    // ไม่พบหรือรหัสผ่านไม่ถูกต้อง
    header('Location: login.php?error=1'); exit;

} catch (Throwable $e) {
    // ในโปรดักชันควรล็อกไฟล์ ไม่ echo ข้อความจริงให้ผู้ใช้เห็น
    header('Location: login.php?error=2'); exit;
}
