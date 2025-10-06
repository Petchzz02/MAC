# การรวมส่วนซ้ำซ้อนในเฮดเดอร์

## สรุปการแก้ไข

เราได้ทำการรวมส่วนที่ซ้ำซ้อนในเฮดเดอร์ของหน้าต่างๆ เพื่อให้ง่ายต่อการแก้ไขจากที่เดียว โดยสร้างไฟล์ template ส่วนกลางดังนี้:

## ไฟล์ที่สร้างขึ้นใหม่

### 1. `includes/header.php`
- รวม HTML head section (meta tags, CSS links)
- รวมการตรวจสอบ authentication
- รวมการ include navigation bar
- รองรับการกำหนด path ที่ถูกต้องสำหรับไฟล์ในโฟลเดอร์ย่อย
- รองรับการกำหนด CSS และ head content เพิ่มเติม

**ตัวแปรที่ใช้งาน:**
- `$page_title` - หัวข้อหน้า
- `$current_path` - path สำหรับแก้ไข link ใน navigation
- `$extra_css` - array ของไฟล์ CSS เพิ่มเติม
- `$extra_head` - HTML เพิ่มเติมใน head section
- `$include_nav` - เปิด/ปิดการแสดง navigation
- `$skip_auth` - ข้าม authentication check (สำหรับหน้า login)

### 2. `includes/footer.php`
- รวม Bootstrap JavaScript
- รองรับการเพิ่ม JavaScript files เพิ่มเติม
- รองรับ inline JavaScript

**ตัวแปรที่ใช้งาน:**
- `$extra_js` - array ของไฟล์ JavaScript เพิ่มเติม
- `$inline_js` - JavaScript code ที่จะแทรกใน footer

## ไฟล์ที่แก้ไข

### 1. `index.php`
**เปลี่ยนจาก:**
```php
<?php 
require_once __DIR__ . '/config.php'; 
if (empty($_SESSION['user'])) {
    header('Location: login.php?error=3'); 
    exit;
}
$user = $_SESSION['user'];
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>หน้าหลัก MAC</title>
    <!-- CSS links -->
</head>
<body>
    <?php include 'nav.php'; ?>
```

**เป็น:**
```php
<?php
$page_title = 'หน้าหลัก MAC';
$current_path = '';
$extra_css = ['assets/index.css'];
include __DIR__ . '/includes/header.php';
?>
```

### 2. `location/location.php`
**เปลี่ยนจาก:**
```php
<?php require_once __DIR__ . '/../config.php';
if (empty($_SESSION['user'])) {
    header('Location: ../login.php?error=3'); exit;
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>Location</title>
    <!-- CSS และ style tags -->
</head>
<body>
    <?php /* nav.php path correction */ ?>
```

**เป็น:**
```php
<?php
$page_title = 'Location';
$current_path = '../';
$extra_css = ['assets/location.css'];
$extra_head = '<style>/* inline styles */</style>';
include __DIR__ . '/../includes/header.php';
?>
```

### 3. `history/history.php`
**เปลี่ยนจาก:**
```php
<?php require_once __DIR__ . '/../config.php';
if (empty($_SESSION['user'])) {
    header('Location: ../login.php?error=3'); exit;
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>ประวัติการทำงาน</title>
    <!-- CSS links -->
</head>
<body>
    <?php include __DIR__ . '/../nav.php'; ?>
```

**เป็น:**
```php
<?php
$page_title = 'ประวัติการทำงาน';
$current_path = '../';
$extra_css = ['assets/location.css'];
include __DIR__ . '/../includes/header.php';
?>
```

### 4. `location/checklist/checklist.php`
**เปลี่ยนจาก:**
```php
<?php
/* database operations */
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>เช็คลิสต์สินค้า - <?php echo htmlspecialchars($location); ?></title>
    <!-- CSS links -->
</head>
<body>
    <?php /* nav.php path correction */ ?>
```

**เป็น:**
```php
<?php
/* database operations */
$page_title = 'เช็คลิสต์สินค้า - ' . htmlspecialchars($location);
$current_path = '../../';
$extra_css = ['../assets/location.css', 'checklist.css'];
include __DIR__ . '/../../includes/header.php';
?>
```

## ประโยชน์ที่ได้รับ

1. **ลดการซ้ำซ้อนของโค้ด** - HTML head, authentication check, navigation ถูกรวมอยู่ที่เดียว
2. **ง่ายต่อการแก้ไข** - เมื่อต้องการเปลี่ยน Bootstrap version หรือเพิ่ม CSS ใหม่ แก้ไขที่เดียวเท่านั้น
3. **จัดการ path อัตโนมัติ** - ระบบจะแก้ไข path ของ navigation ให้ถูกต้องตาม current_path
4. **ยืดหยุ่น** - สามารถเพิ่ม CSS, JavaScript หรือ head content เฉพาะหน้าได้
5. **รักษาความสอดคล้อง** - ทุกหน้าจะมี structure และ styling ที่เหมือนกัน

## วิธีการใช้งาน

สำหรับไฟล์ใหม่:
```php
<?php
$page_title = 'ชื่อหน้า';
$current_path = ''; // '' สำหรับไฟล์ในโฟลเดอร์หลัก, '../' สำหรับโฟลเดอร์ย่อย
$extra_css = ['path/to/style.css']; // ถ้ามี
$extra_head = '<style>/* CSS เพิ่มเติม */</style>'; // ถ้ามี
include __DIR__ . '/includes/header.php';
?>

<!-- HTML content ของหน้า -->

<?php
$inline_js = 'console.log("JavaScript code");'; // ถ้ามี
include __DIR__ . '/includes/footer.php';
?>
```

## การตรวจสอบ

หลังจากการแก้ไข ให้ทดสอบ:
1. การทำงานของ navigation ในทุกหน้า
2. การแสดงผล CSS ถูกต้อง
3. JavaScript ทำงานปกติ
4. Authentication ทำงานถูกต้อง
5. Path ของ CSS และ image ถูกต้อง
