<?php
/**
 * index.php
 * หน้าหลักของระบบหลังล็อกอิน
 * - ตั้งตัวแปรสำหรับ header template (title, path, extra css)
 * - รวม header และ footer จาก includes
 */
// กำหนดค่าสำหรับ header template
$page_title = 'หน้าหลัก MAC';
$current_path = '';
$extra_css = ['assets/index.css'];

// รวม header template (ซึ่งจะตรวจสอบ session ด้วย)
include __DIR__ . '/includes/header.php';
?>
    
    <!-- ส่วนหลักของหน้าเว็บ -->
    <main class="main-content">
        <div class="container-fluid">
            <!-- Header section แสดงข้อมูลผู้ใช้ -->
            <div class="welcome-section">
                <div class="row">
                                        <div class="col-12">
                        <div class="welcome-card">
                            <div class="welcome-content">
                                <h1 class="welcome-title">
                                    <i class="fas fa-home"></i>
                                    ยินดีต้อนรับเข้าสู่ระบบ MAC
                                </h1>
                                <div class="employee-info">
                                    <span>สวัสดี</span>
                                    <strong><?php echo htmlspecialchars($user['fname'].' '.$user['lname']); ?></strong>
                                    <span class="employee-id">รหัสพนักงาน: <?php echo htmlspecialchars($user['employee_id']); ?></span>
                                </div>
                            </div>
                            <div class="welcome-logo">
                                <img src="image/MAC.png" alt="MAC Logo" class="company-logo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Cards Section - เมนูหลักของระบบ -->
            <div class="menu-section">
                                <div class="row">
                    <div class="col-12">
                        <h2 class="section-title">
                            <i class="fas fa-th-large"></i>
                            เมนูหลัก
                        </h2>
                    </div>
                </div>
                
                <div class="row g-4">
                    <!-- การ์ดเมนู: สถานที่ทำงาน -->
                    <div class="col-lg-4 col-md-6">
                        <div class="menu-card">
                            <div class="menu-card-body">
                                <div class="menu-icon location-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <h3 class="menu-title">สถานที่ทำงาน</h3>
                                <p class="menu-description">
                                    เลือกสถานที่ทำงานและดูรายละเอียดงานที่ต้องปฏิบัติ
                                </p>
                                <a href="location/location.php" class="menu-btn btn-location">
                                    <i class="fas fa-arrow-right"></i>
                                    เข้าสู่หน้าสถานที่
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- การ์ดเมนู: ประวัติการทำงาน -->
                    <div class="col-lg-4 col-md-6">
                        <div class="menu-card">
                            <div class="menu-card-body">
                                <div class="menu-icon history-icon">
                                    <i class="fas fa-history"></i>
                                </div>
                                <h3 class="menu-title">ประวัติการทำงาน</h3>
                                <p class="menu-description">
                                    ดูประวัติการทำงานและการปฏิบัติงานที่ผ่านมา
                                </p>
                                <a href="history/history.php" class="menu-btn btn-history">
                                    <i class="fas fa-arrow-right"></i>
                                    ดูประวัติการทำงาน
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- การ์ดเมนู: รายงานและสถิติ -->
                    <div class="col-lg-4 col-md-6">
                        <div class="menu-card">
                            <div class="menu-card-body">
                                <div class="menu-icon report-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <h3 class="menu-title">รายงานและสถิติ</h3>
                                <p class="menu-description">
                                    ดูรายงานผลการทำงานและสถิติต่างๆ 
                                </p>
                                <a href="reports.php" class="menu-btn btn-report">
                                    <i class="fas fa-arrow-right"></i>
                                    ดูรายงาน
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                        <!-- Quick Stats Section - สถิติเร็ว -->
            <div class="stats-section">
                <div class="row">
                    <div class="col-12">
                        <h2 class="section-title">
                            <i class="fas fa-chart-bar"></i>
                            สถิติการทำงาน
                        </h2>
                    </div>
                </div>
                
                <div class="row g-4">
                    <!-- สถิติงานที่เสร็จสิ้น -->
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-card completed">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">45</div>
                                <div class="stat-label">งานที่เสร็จสิ้น</div>
                            </div>
                        </div>
                    </div>

                    <!-- สถิติงานที่รอดำเนินการ -->
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-card pending">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">12</div>
                                <div class="stat-label">งานที่รอดำเนินการ</div>
                            </div>
                        </div>
                    </div>

                    <!-- สถิติสถานที่ทั้งหมด -->
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-card locations">
                            <div class="stat-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">6</div>
                                <div class="stat-label">สถานที่ทั้งหมด</div>
                            </div>
                        </div>
                    </div>

                    <!-- สถิติชั่วโมงการทำงาน -->
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-card hours">
                            <div class="stat-icon">
                                <i class="fas fa-user-clock"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">168</div>
                                <div class="stat-label">ชั่วโมงทำงาน</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php
// กำหนด JavaScript สำหรับหน้านี้
$inline_js = '
    // ฟังก์ชันสำหรับทำให้การ์ดมีเอฟเฟ็กต์เมื่อ hover
    document.addEventListener("DOMContentLoaded", function() {
        // เพิ่มเอฟเฟ็กต์ hover สำหรับ menu cards
        const menuCards = document.querySelectorAll(".menu-card");
        menuCards.forEach(card => {
            card.addEventListener("mouseenter", function() {
                this.style.transform = "translateY(-5px)";
            });
            
            card.addEventListener("mouseleave", function() {
                this.style.transform = "translateY(0)";
            });
        });
        
        // เพิ่มเอฟเฟ็กต์ hover สำหรับ stat cards
        const statCards = document.querySelectorAll(".stat-card");
        statCards.forEach(card => {
            card.addEventListener("mouseenter", function() {
                this.style.transform = "scale(1.05)";
            });
            
            card.addEventListener("mouseleave", function() {
                this.style.transform = "scale(1)";
            });
        });
    });
    
    // ฟังก์ชันสำหรับแสดงข้อความต้อนรับแบบ animate
    function showWelcomeMessage() {
        const welcomeTitle = document.querySelector(".welcome-title");
        if (welcomeTitle) {
            welcomeTitle.style.opacity = "0";
            welcomeTitle.style.transform = "translateY(20px)";
            
            setTimeout(() => {
                welcomeTitle.style.transition = "all 0.6s ease";
                welcomeTitle.style.opacity = "1";
                welcomeTitle.style.transform = "translateY(0)";
            }, 100);
        }
    }
    
    // เรียกใช้ฟังก์ชันเมื่อหน้าเว็บโหลดเสร็จ
    window.addEventListener("load", showWelcomeMessage);
';

// รวม footer template
include __DIR__ . '/includes/footer.php';
?>