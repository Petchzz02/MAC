<?php require_once __DIR__ . '/config.php'; 
/**
 * login.php
 * หน้าเข้าสู่ระบบ (login form)
 * - แสดงฟอร์มกรอกรหัสพนักงานและรหัสผ่าน
 * - ถ้าผู้ใช้ล็อกอินอยู่แล้ว จะ redirect ไปยังหน้า index
 * - อ่านพารามิเตอร์ error เพื่อแสดงข้อความเตือน
 */
// ถ้าล็อกอินแล้ว ให้ไปหน้าหลักทันที
if (!empty($_SESSION['user'])) {
  header('Location: index.php'); exit;
}
$err = isset($_GET['error']) ? (int)$_GET['error'] : 0;
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>เข้าสู่ระบบ</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="assets/login.css?v=<?php echo time(); ?>" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
  
  <main class="form-signin">
      <img src="image/MAC.png" alt="logo" class="logo mb-3">
      <?php if ($err === 1): ?>
        <div class="alert alert-danger text-center mb-3" role="alert">รหัสพนักงานหรือรหัสผ่านไม่ถูกต้อง</div>
      <?php elseif ($err === 2): ?>
        <div class="alert alert-danger text-center mb-3" role="alert">เกิดข้อผิดพลาดภายในระบบ กรุณาลองใหม่</div>
      <?php elseif ($err === 3): ?>
        <div class="alert alert-warning text-center mb-3" role="alert">กรุณาเข้าสู่ระบบก่อน</div>
      <?php endif; ?>

      <form class="js-login-form" action="login_db.php" method="post" autocomplete="off" novalidate>
        <div class="form-floating mb-3">
          <input class="form-control" type="text" id="employee_id" name="employee_id" placeholder="รหัสพนักงาน" required>
          <label for="employee_id">รหัสพนักงาน</label>
        </div>
        <div class="form-floating mb-4">
          <input class="form-control" type="password" id="password" name="password" placeholder="รหัสผ่าน" required>
          <label for="password">รหัสผ่าน</label>
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit">เข้าสู่ระบบ</button>
      </form>
  </main>
  <script src="assets/login.js"></script>
</body>
</html>
