<?php

session_start();
require 'config.php';

if (isset($_POST['login'])) {
    $employee_id = $_POST['employee_id'];
    $password = $_POST['Password'];

    if (empty($employee_id)) {
        $_SESSION['error'] = "กรุณากรอก User ID";
        header("Location: login.php");
        exit();
    } else if (!preg_match('/^[a-zA-Z0-9]+$/', $employee_id)) {
        $_SESSION['error'] = "กรุณากรอก User ID ให้ถูกต้อง (ใช้ได้เฉพาะตัวอักษรและตัวเลข)";
        header("Location: login.php");
        exit();
    } else if (empty($password)) {
        $_SESSION['error'] = "กรุณากรอกรหัสผ่าน";
        header("Location: login.php");
        exit();
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM ข้อมูลพนักงาน WHERE employee_id = ?");
            $stmt->execute([$employee_id]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userData && $userData['Password'] === $password) {
                $_SESSION['user_id'] = $userData['employee_id'];
                $_SESSION['employee_id'] = $userData['employee_id'];
                header("Location: location.php"); 
                exit();
            } else {
                $_SESSION['error'] = "User ID หรือ Password ไม่ถูกต้อง";
                header("Location: login.php");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "มีข้อผิดพลาดเกิดขึ้น โปรดลองอีกครั้ง";
            header("Location: login.php");
            exit();
        }
    }
} else {
    header("Location: login.php");
    exit();
}
