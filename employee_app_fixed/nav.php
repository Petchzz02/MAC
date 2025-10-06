<?php 
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
?>
<link rel="stylesheet" href="assets/nav.css">
<nav class="custom-nav">
  <div class="nav-container">
    <a class="nav-brand" href="index.php"><h3>MAC</h3></a>
    
    <button class="nav-toggle" id="navToggle">
      <span></span>
      <span></span>
      <span></span>
    </button>
    
    <div class="nav-menu" id="navMenu">
      <?php if (!empty($_SESSION['user'])): ?>
        <span class="nav-user">คุณ: <?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
        <a class="nav-link" href="index.php">หน้าหลัก</a>
        <a class="nav-link" href="location/location.php">สถานที่</a>
        <a class="nav-link" href="history/history.php">ประวัติการทำงาน</a>
        <a class="nav-link logout" href="logout.php">ออกจากระบบ</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const navToggle = document.getElementById('navToggle');
  const navMenu = document.getElementById('navMenu');
  
  if (navToggle && navMenu) {
    navToggle.addEventListener('click', function() {
      navMenu.classList.toggle('active');
    });
  }
});
</script>
