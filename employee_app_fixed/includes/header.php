<?php
/**
 * includes/header.php
 * Template ส่วนหัวของหน้า (HTML head + navigation)
 * คำอธิบาย:
 * - ทำหน้าที่แสดง <head> ของ HTML และเมนูนำทาง
 * - ตรวจสอบการล็อกอิน (auth) ยกเว้นเมื่อกำหนด $skip_auth = true
 * ตัวแปรที่รองรับเมื่อต้องการปรับแต่ง:
 * - $page_title (string) - ชื่อเรื่องของหน้า
 * - $current_path (string) - path ที่จะถูก prefix ให้ asset และลิงก์ (เช่น '../' หรือ '../../')
 * - $include_nav (bool) - กำหนดว่าจะ include navigation หรือไม่
 * - $extra_css (array) - เพิ่มไฟล์ CSS เฉพาะหน้า
 * - $extra_head (string) - HTML/inline CSS/JS ที่ต้องใส่ใน <head>
 */

// กำหนดค่าเริ่มต้นสำหรับตัวแปรที่อาจจะไม่ได้ส่งมา
$page_title = isset($page_title) ? $page_title : 'ระบบพนักงาน MAC';
$current_path = isset($current_path) ? $current_path : '';
$include_nav = isset($include_nav) ? $include_nav : true;
$extra_css = isset($extra_css) ? $extra_css : [];
$extra_head = isset($extra_head) ? $extra_head : '';

// Authentication check - ตรวจสอบการเข้าสู่ระบบ (ยกเว้นเมื่อ $skip_auth = true)
if (!isset($skip_auth) || !$skip_auth) {
    require_once __DIR__ . '/../config.php';
    if (empty($_SESSION['user'])) {
        $redirect_url = $current_path ? $current_path . '/login.php?error=3' : 'login.php?error=3';
        header('Location: ' . $redirect_url);
        exit;
    }
    $user = $_SESSION['user'];
}
?>
<!doctype html>
<html lang="th">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Navigation CSS -->
    <?php if ($include_nav): ?>
        <link rel="stylesheet" href="<?php echo $current_path; ?>assets/nav.css">
    <?php endif; ?>
    
    <!-- Additional CSS Files -->
    <?php foreach ($extra_css as $css_file): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($css_file); ?>">
    <?php endforeach; ?>
    
    <!-- Additional Head Content -->
    <?php echo $extra_head; ?>
</head>
<body>
    <?php if ($include_nav): ?>
        <?php 
        // Include navigation with path correction
        if ($current_path) {
            // สำหรับไฟล์ในโฟลเดอร์ย่อย - ใช้ output buffering เพื่อให้ PHP execute ได้
            ob_start();
            include __DIR__ . '/../nav.php';
            $nav_content = ob_get_clean();
            
            // แทนที่ path ต่างๆ ให้ถูกต้องตาม current_path
            $replacements = [
                'href="index.php"' => 'href="' . $current_path . 'index.php"',
                'href="logout.php"' => 'href="' . $current_path . 'logout.php"',
                'href="login.php"' => 'href="' . $current_path . 'login.php"',
                'href="assets/nav.css"' => 'href="' . $current_path . 'assets/nav.css"'
            ];
            
            // แทนที่ path ของเมนูต่างๆ
            if ($current_path === '../../') {
                // สำหรับไฟล์ในโฟลเดอร์ย่อยลึก (เช่น location/checklist/)
                $replacements['href="location/location.php"'] = 'href="../../location/location.php"';
                $replacements['href="history/history.php"'] = 'href="../../history/history.php"';
            } else {
                // สำหรับไฟล์ในโฟลเดอร์ย่อยระดับแรก (เช่น location/, history/)
                $replacements['href="location/location.php"'] = 'href="' . $current_path . 'location/location.php"';
                $replacements['href="history/history.php"'] = 'href="' . $current_path . 'history/history.php"';
            }
            
            foreach ($replacements as $search => $replace) {
                $nav_content = str_replace($search, $replace, $nav_content);
            }
            
            echo $nav_content;
        } else {
            // สำหรับไฟล์ในโฟลเดอร์หลัก
            include __DIR__ . '/../nav.php';
        }
        ?>
    <?php endif; ?>

    <!-- Main Content Area -->
    <main class="main-content">
